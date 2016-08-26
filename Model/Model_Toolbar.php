<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Toolbar
 *
 * @author jinlee
 */
class Model_Toolbar extends Model{
    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
    }
    
    function fetchButton() {
        $oModel_Button = Model::create('Button', $this->getApp());
        return $oModel_Button->fetchInTable();
        //return array('Add');
    }
}

?>
