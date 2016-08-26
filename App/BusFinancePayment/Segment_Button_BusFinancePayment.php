<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/8/8
 * Time: 16:20
 */
class Segment_Button_BusFinancePayment extends Segment_Button
{
    function isCanShowInRow(&$arrRow, $sButtonCode)
    {
        $arrShowButton = array("View","Detail");
        if($arrRow["iStatus"] == 2 && !in_array($sButtonCode,$arrShowButton)){
            return false;
        }else if($arrRow["iStatus"] == 1 && $sButtonCode=="Del"){
            return false;
        }else{
            return true;
        }
    }

}