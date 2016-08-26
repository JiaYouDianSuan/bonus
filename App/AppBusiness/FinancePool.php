<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/7/8
 * Time: 9:06
 */
class FinancePool extends AppBusiness
{
    protected $_sRelationNo = "";
    protected $_sMemo = "";

    public function __construct($_sDealerNo, $sRelationNo)
    {
        parent::__construct($_sDealerNo); // TODO: Change the autogenerated stub
        $this->_sRelationNo = $sRelationNo;

        $sSql = "Select count(iPk) As iCount From bus_finance_pool Where sDealerNo='$this->_sDealerNo'";
        $arrResult = $this->getDb()->select($sSql);
        if ($arrResult[0][iCount] == 0) {
            $sSql = "Insert Into bus_finance_pool (sDealerNo, dTotalMoney, dLockedMoney, dReturnTax, dAddTax) Value ('$this->_sDealerNo', 0, 0, 0,0)";
            $this->getDb()->query($sSql);
        }
    }

    public function writeLog($dValue,$sType,$iDirection){
        $sSql = "Insert Into bus_finance_pool_log(sDealerNo,dValue,sType,iDirection,sRelatedNo,dtCreateTime,sMemo) VALUES (
        '$this->_sDealerNo',
        '$dValue',
        '$sType',
        '$iDirection',
        '$this->_sRelationNo',
        now(),
        '$this->_sMemo')";

        $this->getDb()->query($sSql);
    }

    public function increase($sField,$dValue,$sLogType){
        $dValue = abs($dValue);
        $sSql = "Update bus_finance_pool Set $sField=$sField+$dValue Where sDealerNo='$this->_sDealerNo'";
        $this->getDb()->query($sSql);
        $this->writeLog($dValue,$sLogType,1);
    }

    public function decrease($sField,$dValue,$sLogType){
        $dValue = abs($dValue);
        $sSql = "Update bus_finance_pool Set $sField=$sField-$dValue Where sDealerNo='$this->_sDealerNo'";
        $this->getDb()->query($sSql);
        $this->writeLog($dValue,$sLogType,-1);
    }

    /**
     * @param string $sMemo
     */
    public function setSMemo($sMemo)
    {
        $this->_sMemo = $sMemo;
    }






}