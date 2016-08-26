<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/8/22
 * Time: 17:16
 */
class Segment_Button_BusFinanceTax extends Segment_Button
{
    function isCanShowInRow(&$arrRow, $sButtonCode)
    {
        $arrShowButton = array("View","Detail");
        if($arrRow["iSysConfirmFlag"] == 1 && !in_array($sButtonCode,$arrShowButton)){
            return false;
        }else{
            return true;
        }
    }
}