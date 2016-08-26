<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_Add_SysUserGrp
 *
 * @author jinlee
 */
class Page_Add_SysUserGrp extends Page_Add{
    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);
    }
    
    function getForm() {
        $oSegment_Form = Segment::create('Form', $this->getApp());
        $oSegment_Form->setLayoutH('55');
        return $oSegment_Form;
    }
}

?>
