<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_Confirm
 *
 * @author jinlee
 */
class Page_Confirm extends Page {

    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);
    }

    function show() {
        $oPage_Submit = Page::create('Submit', $this->getApp());
        return $oPage_Submit->confirm();
    }

}

?>
