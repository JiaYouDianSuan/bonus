<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author jinlee
 */
abstract class Page {

    protected $_sClassType;
    protected $_sApp;
    protected $_sDirection;

    function __construct($sClassType, $sApp) {        
        $this->setClassType($sClassType);    
        $this->setApp($sApp);
        $this->setDirection('Left');
        $this->loadJsFile();
    }

    abstract function show();

    static function create($sClassType, $sApp) {
        return Factory::create(__CLASS__, $sClassType, $sApp);
    }

    function save() {
        Factory::save(__CLASS__, $this);
    }

    function getDb() {
        return Database::create();
    }

    function setClassType($sClassType) {
        $this->_sClassType = $sClassType;
    }

    function getClassType() {
        return $this->_sClassType;
    }

    function setApp($sApp) {
        $this->_sApp = $sApp;
    }

    function getApp() {
        return $this->_sApp;
    }

    function setDirection($sDirection) {
        $this->_sDirection = $sDirection;
    }

    function getDirection() {
        return $this->_sDirection;
    }

    function getRegistor() {
        return Registor::create();
    }

    function getAppStruct($sApp = '') {
        $mapAppStruct = $this->getRegistor()->get('appStruct');
        return $sApp == '' ? $mapAppStruct : $mapAppStruct[$sApp];
    }

    function loadJsFile(){
        $sFilePath = JFRAME_DISK_ROOT."Ui/Js/Web/Page/".get_class($this).".js";
        if ((file_exists($sFilePath) === true) && (is_readable($sFilePath) === true)) {
            $sFilePath = JFRAME_WWW_ROOT."Ui/Js/Web/Page/".get_class($this).".js";
            echo "<script src='".$sFilePath."' type='text/javascript'></script>";
        }
    }

}

?>
