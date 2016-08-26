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
class Page_Grid extends Page {

    protected $_arrSqlWhere;

    function __construct($sPage, $sApp) {             
        parent::__construct($sPage, $sApp);
    }

    function setSqlWhere($arrSqlWhere) {
        $this->_arrSqlWhere = $arrSqlWhere;
    }
    
    function getSqlWhere(){
        return $this->_arrSqlWhere;
    }

    function show() {   
        //echo $this->getApp().'='.$this->getDirection();
        //if($this->getDirection() == 'Right') print_R($_SERVER['REQUEST_URI']);
        $sApp = $this->getApp();                
        $oSegment_Table = Segment::create('Table', $sApp);
        $oSegment_Page = Segment::create('Page', $sApp);

        $sId = "page_grid_{$this->getApp()}";
        $sGrid = "<div id='$sId'>".$oSegment_Table->show() . $oSegment_Page->show()."</div>";
        return $sGrid;
    }

}

?>
