<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment
 *
 * @author jinlee
 */
class Segment {

    protected $_sApp;
    protected $_sSegment;
    protected $_oModel;

    function __construct($sSegment, $sApp) {
        $this->setApp($sApp);
        $this->setSegment($sSegment);
        $this->setModel($this->createModel());
    }

    static function create($sSegment, $sApp) {
        return Factory::create(__CLASS__, $sSegment, $sApp);
    }

    function createModel() {
    	//echo $this->getSegment().','. $this->getApp().'</br>';
        return Model::create($this->getSegment(), $this->getApp());
    }

    function getDb() {
        return Database::create();
    }

    function getRegistor() {
        return Registor::create();
    }

    function setApp($sApp) {
        $this->_sApp = $sApp;
    }

    function getApp() {
        return $this->_sApp;
    }

    function setSegment($sSegment) {
        $this->_sSegment = $sSegment;
    }

    function getSegment() {
        return $this->_sSegment;
    }

    function setModel(Model $oModel) {
        $this->_oModel = $oModel;
    }

    function getModel() {
        return $this->_oModel;
    }

}

?>
