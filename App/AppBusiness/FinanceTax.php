<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/7/14
 * Time: 17:12
 */
class FinanceTax extends AppBusiness
{
    public function __construct()
    {
    }


    /**
     * 取指定经销商的调整税金
     * 正：预扣税多，需要退税
     * 负：预扣税少，需要补税
     * @param $sDealerNo
     * @return mixed
     */
    public function fetchAdjustTaxFromDealer($sDealerNo)
    {
        $sSql = "Select dAdjustTax From bus_finance_pool Where sDealerNo='{$sDealerNo}'";
        $arrResults = $this->getDb()->select($sSql);
        return $arrResults[0]["dAdjustTax"];
    }

    public function getNoFromPk($iPk){
        $sSql = "Select sNo From bus_finance_tax Where iPk='$iPk'";
        $arrResults = $this->getDb()->select($sSql);
        $sNo = $arrResults[0]['sNo'];
        return $sNo;
    }

    public function getMonthFromPK($iPk){
        $sSql = "Select sMonth From bus_finance_tax Where iPk='$iPk'";
        $arrResults = $this->getDb()->select($sSql);
        $sMonth = $arrResults[0]["sMonth"];
        return $sMonth;
    }

    /**
     * 计算实际纳税额
     * @param $dMoney
     * @return mixed
     */
    public function calcTax($dMoney)
    {
        $AddedValueTaxRate = 0.03; //增值税比率
        $AddedValueExtraTaxRate = 0.13; //增值税附税比率
        $ReduceCostRate = 0.2; //减除费用比率


        /*$IncomeTaxRate = 0; //所得税比率
        $DeductValue = 800; //扣除费用*/

        // 计算增值税
        $AddedValueTax = $dMoney > 30000 ? $dMoney / (1 + $AddedValueTaxRate) * $AddedValueTaxRate : 0;

        //计算增值税附税
        $AddedValueExtraTax = $AddedValueTax*$AddedValueExtraTaxRate;

        //扣除增值税及附加税后收入
        $dMoney1=$dMoney-$AddedValueTax-$AddedValueExtraTax;

        //减除费用
        $ReduceCost = $dMoney1<4000?800:$dMoney1*$ReduceCostRate;

        //应纳税所得额
        $dMoney2 = $dMoney1-$ReduceCost;

        //个人所得税
        if($dMoney2<20000){
            $PersonTax = $dMoney2*0.2;
        }else if($dMoney2>50000){
            $PersonTax = $dMoney2*0.4-7000;
        }else{
            $PersonTax = $dMoney2*0.3-2000;
        }
        $PersonTax = $PersonTax<0?0:$PersonTax;
        //echo "计算增值税:$AddedValueTax</br>-计算增值税附税:$AddedValueExtraTax</br>扣除增值税及附加税后收入:$dMoney1</br>减除费用:$ReduceCost</br>应纳税所得额：$dMoney2</br>个人所得税:$PersonTax</br>";
        $TotalTax = $AddedValueTax+$AddedValueExtraTax+$PersonTax;
        return $TotalTax;
    }

    /**
     * 按月份生成奖金的实际纳税金额
     * @param $iPk
     */
    public function createFinanceTax($iPk)
    {
        $iTaxPk = $iPk;
        $sMonth = $this->getMonthFromPK($iTaxPk);
        $sTaxNo = $this->getNoFromPk($iTaxPk);

        $sSql = "Select
        MAIN.iPk,
        DETAIL.sDealerNo,
        SUM(DETAIL.dTotal) As dTotal,
        SUM(DETAIL.dBonus) As dBonus,
        SUM(DETAIL.dDeductTax) As dDeductTax
        From bus_finance_payment As MAIN
        Left Join bus_finance_payment_detail As DETAIL
        On DETAIL.iPaymentPk=MAIN.iPk
        Where DATE_FORMAT(MAIN.dtCreateTime,'%Y-%m')='$sMonth'
        And DETAIL.iStatus=2
        Group By DETAIL.sDealerNo";

        $arrResults = $this->getDb()->select($sSql);
        $arrInsertData = array();
        $dSumTotal = $dSumBonus = $dSumDeductTax = $dSumShouldDeductTax = 0;
        foreach ($arrResults as $v) {
            $iPaymentPk = $v['iPk'];
            $sDealerNo = $v["sDealerNo"];
            $dTotal = $v["dTotal"];
            $dBonus = $v["dBonus"];
            $dDeductTax = $v["dDeductTax"];
            $dShouldDeductTax = $this->calcTax($dTotal);
            $dSumTotal += $dTotal;
            $dSumBonus += $dBonus;
            $dSumDeductTax += $dDeductTax;
            $dSumShouldDeductTax += $dShouldDeductTax;
            $arrInsertData[] = array(
                "sMonth" => $sMonth,
                "sDealerNo" => $sDealerNo,
                "dTotal" => $dTotal,
                "dBonus" => $dBonus,
                "dDeductTax" => $dDeductTax,
                "dShouldDeductTax" => $dShouldDeductTax
            );

            $sSql = "Insert Into bus_finance_tax_detail (
            iTaxPk,
            sTaxNo,
            sMonth,
            sDealerNo,
            dTotal,
            dBonus,
            dDeductTax,
            dShouldDeductTax
            ) VALUE (
            '$iTaxPk',
            '$sTaxNo',
            '$sMonth',
            '$sDealerNo',
            '$dTotal',
            '$dBonus',
            '$dDeductTax',
            '$dShouldDeductTax')";
            $this->getDb()->query($sSql);

            //dAdjustTax,正为预扣税多退税，负为预扣税少补税
            $dAdjustTax = $dDeductTax - $dShouldDeductTax;
            $oFPTax = new FinancePoolTax($sDealerNo, $sTaxNo);
            if ($dAdjustTax > 0) {//需要退税
                $oFPTax->setSMemo("{$sMonth}税金计算，公司退税。[".__METHOD__."]");
                $oFPTax->increaseReturnTax($dAdjustTax);
            } else if ($dAdjustTax < 0) {//需要补税
                $oFPTax->setSMemo("{$sMonth}税金计算，经销商补税。[".__METHOD__."]");
                $oFPTax->increaseAddTax($dAdjustTax);
            }

            //设置奖金发放记录状态为【已算税金】，并向奖金发放记录表中回写税金的PK和NO。
            $sSql = "Update bus_finance_payment Set iTaxPk='$iTaxPk', sTaxNo='$sTaxNo', iStatus=2 Where iPk='$iPaymentPk'";
            $this->getDb()->query($sSql);
        }

        $sSql = "Update bus_finance_tax Set
        dTotal='$dSumTotal',
        dBonus='$dSumBonus',
        dDeductTax='$dSumDeductTax',
        dShouldDeductTax='$dSumShouldDeductTax'
        Where iPk='$iTaxPk'";
        $this->getDb()->query($sSql);
    }

    public function deleteFinanceTax($iTaxPk)
    {
        $arrResult = array();

        $sSql = "Select iSysConfirmFlag From bus_finance_tax Where iPk='$iTaxPk'";
        $arrRecords = $this->getDb()->select($sSql);
        if($arrRecords[0]['iSysConfirmFlag'] == 1){
            $arrResult = array (
                'status' => 'error',
                'message' => '该次税金记录已审核，不允许删除！'
            );
        }else{
            $sSql = "Select
            MAIN.iPk,
            MAIN.sNo,
            MAIN.sMonth,
            DETAIL.iPk,
            DETAIL.sDealerNo,
            DETAIL.dDeductTax-DETAIL.dShouldDeductTax As dAdjustTax
            From bus_finance_tax As MAIN
            Left Join bus_finance_tax_detail As DETAIL
            On DETAIL.iTaxPk=MAIN.iPk
            Where MAIN.iPk='$iTaxPk'
            And MAIN.iSysDelete=0";
            $arrRecords = $this->getDb()->select($sSql);

            foreach ($arrRecords as $v) {
                $oFPTax = new FinancePoolTax($v["sDealerNo"], $v["sNo"]);
                $oFPTax->setSMemo("删除{$v["sMonth"]}税金管理记录。[".__METHOD__."]");
                if ($v["dAdjustTax"] > 0) {//需要退税
                    $oFPTax->decreaseReturnTax($v["dAdjustTax"]);
                } else if ($v["dAdjustTax"] < 0) {//需要补税
                    $oFPTax->decreaseAddTax($v["dAdjustTax"]);
                }
            }

            $sSql = "Update bus_finance_tax Set iSysDelete=1 Where iPk='$iTaxPk' And iSysDelete=0";
            $this->getDb()->query($sSql);

            $sSql = "Update bus_finance_payment Set iStatus=1,iTaxPk='',sTaxNo='' Where iTaxPk='$iTaxPk'";
            $this->getDb()->query($sSql);

            $arrResult = array (
                'status' => 'ok',
                'message' => '删除成功！'
            );
        }
        return $arrResult;
    }
}