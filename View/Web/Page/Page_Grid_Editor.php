<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_Grid
 *
 * @author jinlee
 */
class Page_Grid_Editor extends Page_Grid {

    function show($isEmpty=false) {        
        $sApp = $this->getApp();        
        $oSegment_Table_Editor = Segment::create('Table_Editor', $sApp);       
        return $oSegment_Table_Editor->show($isEmpty);
        
//      $oSegment_Page = Segment::create('Page', $sApp);
//      $sGrid = $oSegment_Table_Editor->show() . $oSegment_Page->show();
//      return $sGrid;
    }

}

?>
