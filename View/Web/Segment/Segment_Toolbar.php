<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Toolbar
 *
 * @author jinlee
 */
class Segment_Toolbar extends Segment{
    function __construct($sSegment, $sApp) {
        parent::__construct($sSegment, $sApp);
    }
        
    function show($arrButton=array()){
        $oButton = Segment_Button::create('Button', $this->getApp());
        $sButtonCodes = $this->getModel()->fetchButton();
        if($sButtonCodes == "") return "";

        $sHtml = '<div class="panelBar">';
        $sHtml .= '<ul class="toolBar">';
        $sHtml .= empty($arrButton)?$oButton->showInTable($sButtonCodes):  implode('', $arrButton);        
        $sHtml .= '</ul>';
        $sHtml .= '</div>';
        return $sHtml;
    }
}

?>
