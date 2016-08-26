<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/8/26
 * Time: 17:32
 */
class Page_Detail_BusFinanceTax extends Page
{
    function show()
    {
        // TODO: Implement show() method.
        $sApp = "BusFinanceTaxDetail";
        $oSegment_Table = Segment::create('Table', $sApp);
        //$oSegment_Page = Segment::create('Page', $sApp);

        $sId = "page_grid_{$sApp}";
        $sGrid = "<div id='$sId'>".$oSegment_Table->show()."</div>";
        return $sGrid;
    }
}