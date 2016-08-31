<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/7/12
 * Time: 15:25
 */
class FinancePayment extends AppBusiness
{
    protected $_dDeductTaxRate = 0.2;

    public function __construct()
    {
    }

    public function getNoFromPk($iPk){
        $sSql = "Select sNo From bus_finance_payment Where iPk='$iPk'";
        $arrResults = $this->getDb()->select($sSql);
        $sNo = $arrResults[0]['sNo'];
        return $sNo;
    }

    public function getStatusFromPk($iPk){
        $sSql = "Select iStatus From bus_finance_payment Where iPk='$iPk'";
        $arrRecord = $this->getDb()->select($sSql);
        return $arrRecord[0]["iStatus"];
    }

    public function fetchUsableBonus(){
        $sSql = "Select
        sDealerNo,
        dTotalMoney,
        dLockedMoney,
        dReturnTax,
        dAddTax,
        ((dTotalMoney-dLockedMoney)*(1-{$this->_dDeductTaxRate})+dReturnTax-dAddTax) As dRealMoney
        From bus_finance_pool
        Where dTotalMoney-dLockedMoney>0
        HAVING dRealMoney>=50";
        $arrResults = $this->getDb()->select($sSql);

        return $arrResults;
    }

    /**
     * 新增一次财务发放，更新发放记录，新增发放明细记录
     * 更新奖金池中占用金额
     * @param $iPk
     */
    public function createPayment($iPk){
        $iPaymentPk = $iPk;
        $sPaymentNo = $this->getNoFromPk($iPaymentPk);
        $arrResults = $this->fetchUsableBonus();

        $iNumber = $dSumTotal = $dSumBonus = $dSumDeductTax = $dSumReturnTax = $dSumAddTax = $dSumRealMoney = 0;
        foreach($arrResults as $v){
            $sDealerNo = $v["sDealerNo"];
            $dTotal =$v["dTotalMoney"]-$v["dLockedMoney"];
            $dReturnTax = $v["dReturnTax"];
            $dAddTax = $v["dAddTax"];
            $dRealMoney = $v["dRealMoney"];
            $dDeductTax = $dTotal * $this->_dDeductTaxRate;
            $dBonus = $dTotal - $dDeductTax;
            $iStatus = 0;//0：发放中，1：失败，2：完成

            $iNumber++;
            $dSumTotal+=$dTotal;
            $dSumBonus+=$dBonus;
            $dSumDeductTax+=$dDeductTax;
            $dSumReturnTax+=$dReturnTax;
            $dSumAddTax+=$dAddTax;
            $dSumRealMoney+=$dRealMoney;

            $sSql = "Insert Into bus_finance_payment_detail(
            iPaymentPk,
            sPaymentNo,
            sDealerNo,
            dTotal,
            dBonus,
            dDeductTax,
            dReturnTax,
            dAddTax,
            dRealMoney,
            iStatus
            ) VALUES (
            '$iPaymentPk',
            '$sPaymentNo',
            '$sDealerNo',
            '$dTotal',
            '$dBonus',
            '$dDeductTax',
            '$dReturnTax',
            '$dAddTax',
            '$dRealMoney',
            '$iStatus')";
            $this->getDb()->query($sSql);
            $oFPoolMoney = new FinancePoolMoney($sDealerNo,$sPaymentNo);
            $oFPoolMoney->setSMemo("新增奖金发放。[".__METHOD__."]");
            $oFPoolMoney->increaseLockedMoney($dTotal);
        }

        $sSql = "Update bus_finance_payment Set
        iNumber='$iNumber',
        dTotal='$dSumTotal',
        dBonus='$dSumBonus',
        dDeductTax='$dSumDeductTax',
        dReturnTax='$dSumReturnTax',
        dAddTax='$dSumAddTax',
        dRealMoney='$dSumRealMoney',
        dtCreateTime=now(),
        iStatus=0
        Where iPk='$iPaymentPk'";
        $this->getDb()->query($sSql);
    }

    /**
      * 完成奖金发放，更新每个经销商的奖金发放结果
     * detail表中 iStatus：  0：发放中，1：失败，2：完成
     * @param $iPk
     * @param $mapPaymentResults
     * @return bool
     */
   /* public function completePayment($iPk,$mapPaymentResults){
        $iPaymentPk = $iPk;
        $sPaymentNo = $this->getNoFromPk($iPaymentPk);
        $bValid = true;

        $sSql = "Select sDealerNo,dTotal,dReturnTax,dAddTax from bus_finance_payment_detail Where iPaymentPk='{$iPaymentPk}'";
        $arrResults = $this->getDb()->select($sSql);
        //验证$mapPaymentResults中经销商数据是否完整有效
        foreach($arrResults as $v){
            $sDealerNo = $v["sDealerNo"];
            if(!array_key_exists($sDealerNo,$mapPaymentResults)){
                $bValid = false;
                break;
            }
        }
        if(count($arrResults)!=count($mapPaymentResults))
            $bValid = false;

        if($bValid){
            foreach($arrResults as $v){
                $sDealerNo = $v["sDealerNo"];
                $dTotal = $v["dTotal"];
                $dReturnTax = $v["dReturnTax"];
                $dAddTax = $v["dAddTax"];
                $iStatus = $mapPaymentResults[$sDealerNo];

                $sSql = "Update bus_finance_payment_detail Set iStatus='{$iStatus}' Where iPaymentPk='{$iPaymentPk}' And sDealerNo='{$sDealerNo}'";
                $this->getDb()->query($sSql);

                $oFPoolMoney = new FinancePoolMoney($sDealerNo,$sPaymentNo);
                if($iStatus == 1){//失败，释放占用金额
                    $oFPoolMoney->setSMemo("[".__METHOD__."]完成奖金发放--失败");
                    $oFPoolMoney->decreaseLockedMoney($sDealerNo,$dTotal);
                }elseif($iStatus == 2){//成功，释放占用金额并减去总金额
                    $oFPoolMoney->setSMemo("[".__METHOD__."]完成奖金发放--成功");
                    $oFPoolMoney->decreaseLockedMoney($sDealerNo,$dTotal);
                    $oFPoolMoney->decreaseTotalMoney($sDealerNo,$dTotal);

                    $oFPoolTax = new FinancePoolTax($sDealerNo,$sPaymentNo);
                    if($dReturnTax != 0){
                        $oFPoolTax->setSMemo("[".__METHOD__."]完成奖金发放--成功，公司退税--成功");
                        $oFPoolTax->decreaseReturnTax($dReturnTax);
                    }
                    if($dAddTax != 0){
                        $oFPoolTax->setSMemo("[".__METHOD__."]完成奖金发放--成功，经销商补税--成功");
                        $oFPoolTax->decreaseAddTax($dAddTax);
                    }
                }

            }

            $sSql = "Update bus_finance_payment Set
            dtFinishTime=now(),
            iStatus=1
            Where iPk='$iPaymentPk'";
            $this->getDb()->query($sSql);
        }
        return $bValid;
    }*/

    public function deletePayment($iPk){
        $iPaymentPk = $iPk;
        $arrResult = array();
        if($this->getStatusFromPk($iPaymentPk) == 0){//发放中状态允许删除
            $sSql = "Select sDealerNo,sPaymentNo,dTotal From bus_finance_payment_detail Where iPaymentPk='$iPaymentPk'";
            $arrRecords = $this->getDb()->select($sSql);
            foreach($arrRecords as $v){
                $sDealerNo = $v["sDealerNo"];
                $sPaymentNo = $v["sPaymentNo"];
                $dTotal = $v["dTotal"];
                $oFPoolMoney = new FinancePoolMoney($sDealerNo,$sPaymentNo);
                $oFPoolMoney->setSMemo("删除奖金发放。[".__METHOD__."]");
                $oFPoolMoney->decreaseLockedMoney($dTotal);
            }
            $sSql = "Update bus_finance_payment Set iSysDelete=1 Where iPk='$iPaymentPk'";
            $this->getDb()->query($sSql);

            $arrResult = array (
                'status' => 'ok',
                'message' => '删除成功！'
            );
        }else{
            $arrResult = array (
                'status' => 'error',
                'message' => '该次发放已经完成，不允许删除！'
            );
        }
        return $arrResult;
    }

    public function importPaymentResult($iPaymentPk){
        $sMsg = "";
        if($this->getStatusFromPk($iPaymentPk) != 0){
            $sMsg = "该次发放已经完成，不允许重复导入！";
        }else{
            $sSql = "Select iPk,CONCAT(sPaymentNo,'-',iPk) As sNo,sDealerNo,dTotal,dReturnTax,dAddTax From bus_finance_payment_detail Where iPaymentPk='$iPaymentPk'";
            $arrRecord = $this->getDb()->select($sSql);
            $arrNoDataBase = $mapDetailInfo = array();
            foreach($arrRecord as $v){
                $arrNoDataBase[] = $v['sNo'];
                $mapDetailInfo[$v["iPk"]] = array(
                    "sDealerNo"=>$v["sDealerNo"],
                    "dTotal"=>$v["dTotal"],
                    "dReturnTax"=>$v["dReturnTax"],
                    "dAddTax"=>$v["dAddTax"]
                );
            }

            $arrPaymentResult = array();
            $arrNoFile = array();
            $arrNoFileRepeat = array();
            $arrFileContent = file(JFRAME_DISK_ROOT.'UploadFile/payment_result_file.txt');
            foreach($arrFileContent as $sLine){
                $arrTemp1 = explode(",", $sLine);
                $arrTemp2 = explode("-",$arrTemp1[0]);
                $sPaymentNo = $arrTemp2[0];
                $iPaymentDetailPk = $arrTemp2[1];
                $iResult = $arrTemp1[1];
                if($iResult == "") continue;
                if(in_array($arrTemp1[0],$arrNoFile)) $arrNoFileRepeat[] = $arrTemp1[0];
                $arrNoFile[] = $arrTemp1[0];
                $arrPaymentResult[] = array(
                    "sPaymentNo"=>$sPaymentNo,
                    "iPaymentDetailPk"=>$iPaymentDetailPk,
                    "iResult"=>$iResult
                );
            }

            $arrDiff1 = array_diff($arrNoDataBase,$arrNoFile);
            $arrDiff2 = array_diff($arrNoFile,$arrNoDataBase);
            if(!empty($arrDiff1)){
                $sMsg .= "导入发放结果文本中少".implode($arrDiff1,",")."</br>";
            }
            if(!empty($arrDiff2)){
                $sMsg .= "导入发放结果文本中多".implode($arrDiff2,",")."</br>";
            }
            if(!empty($arrNoFileRepeat)){
                $sMsg .= "导入发放结果文本中".implode($arrNoFileRepeat,",")."重复</br>";
            }

            if($sMsg == ""){
                foreach($arrPaymentResult as $v){
                    $iPaymentDetailPk = $v["iPaymentDetailPk"];
                    $sPaymentNo = $v["sPaymentNo"];
                    $iStatus = $v["iResult"]==1?2:1;//1:失败，2:完成

                    $sSql = "Update bus_finance_payment_detail Set iStatus={$iStatus} Where sPaymentNo='{$sPaymentNo}' And iPk='{$iPaymentDetailPk}'";
                    $this->getDb()->query($sSql);

                    $sDealerNo = $mapDetailInfo[$iPaymentDetailPk]["sDealerNo"];
                    $dMoney = $mapDetailInfo[$iPaymentDetailPk]["dTotal"];
                    $dReturnTax = $mapDetailInfo[$iPaymentDetailPk]["dReturnTax"];
                    $dAddTax = $mapDetailInfo[$iPaymentDetailPk]["dAddTax"];
                    $sRelationNo = "{$sPaymentNo}-{$iPaymentDetailPk}";

                    $oFPoolMoney = new FinancePoolMoney($sDealerNo,$sRelationNo);
                    if($iStatus == 1){//失败，释放占用金额
                        $oFPoolMoney->setSMemo("发放结果导入，失败。[".__METHOD__."]");
                        $oFPoolMoney->decreaseLockedMoney($dMoney);
                    }else if($iStatus == 2){//成功，释放占用金额并减去总金额
                        $oFPoolMoney->setSMemo("发放结果导入，成功。[".__METHOD__."]");
                        $oFPoolMoney->decreaseLockedMoney($dMoney);
                        $oFPoolMoney->decreaseTotalMoney($dMoney);

                        $oFPoolTax = new FinancePoolTax($sDealerNo,$sRelationNo);
                        if($dReturnTax != 0){
                            $oFPoolTax->setSMemo("完成奖金发放--成功，公司退税--成功。[".__METHOD__."]");
                            $oFPoolTax->decreaseReturnTax($dReturnTax);
                        }
                        if($dAddTax != 0){
                            $oFPoolTax->setSMemo("完成奖金发放--成功，经销商补税--成功。[".__METHOD__."]");
                            $oFPoolTax->decreaseAddTax($dAddTax);
                        }
                    }
                }
                $sMsg = "导入成功！";
                $sSql = "Update bus_finance_payment Set iStatus=1,dtFinishTime=now() Where iPk='{$iPaymentPk}'";
                $this->getDb()->query($sSql);
            }
        }
        return $sMsg;
    }
}