<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/8/4
 * Time: 16:14
 */
class Page_Detail_BusFinancePayment extends Page
{
    function show()
    {
        // TODO: Implement show() method.
        $sApp = "BusFinancePaymentDetail";
        $oSegment_Table = Segment::create('Table', $sApp);
        //$oSegment_Page = Segment::create('Page', $sApp);

        $sId = "page_grid_{$sApp}";
        $sGrid = "<div id='$sId'>".$oSegment_Table->show()."</div>";
        return $sGrid;
    }

}