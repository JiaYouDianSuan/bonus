<?php

/**
 * @author JinLi
 * @copyright 2013
 */
class AutoLoader {

    function __construct() {

    }

    public static function Register($sRule) {
        return spl_autoload_register(array('AutoLoader', $sRule));
    }
    public static function Common($sClassName) {
        if(class_exists($sClassName, false)) return true;

        $sFileName = $sClassName . '.php';
        $sFileDir = JFRAME_DISK_ROOT . 'Common/';
        $sFilePath = $sFileDir . $sFileName;
        if ((file_exists($sFilePath) === true) && (is_readable($sFilePath) === true)) {
            require_once ($sFilePath);
        }
    }

    public static function Common_Frame($sClassName) {
        if(class_exists($sClassName, false)) return true;

        $sFileName = $sClassName . '.php';
        $sFileDir = JFRAME_DISK_ROOT . 'Common/Frame/';
        $sFilePath = $sFileDir . $sFileName;
        if ((file_exists($sFilePath) === true) && (is_readable($sFilePath) === true)) {
            require_once ($sFilePath);
        }
    }

    public static function Model($sClassName) {
        if(class_exists($sClassName, false)) return true;

        $sFileName = $sClassName . '.php';
        $sFileDir = JFRAME_DISK_ROOT . 'Model/';
        $sFilePath = $sFileDir . $sFileName;
        //echo $sFilePath.'</br>';
        if ((file_exists($sFilePath) === true) && (is_readable($sFilePath) === true)) {

            require_once ($sFilePath);
        }
    }

    public static function View($sClassName) {
        if(class_exists($sClassName, false)) return true;

        $arrClassName = explode('_', $sClassName);
        $sFileName = $sClassName . '.php';
        $sFileDir = JFRAME_DISK_ROOT . 'View/Web/' . $arrClassName[0] . '/';
        $sFilePath = $sFileDir . $sFileName;        
        if ((file_exists($sFilePath) === true) && (is_readable($sFilePath) === true)) {
            require_once ($sFilePath);
        }
    }


    public static function App($sClassName) {
        if(class_exists($sClassName, false)) return true;

        $arrClassName = explode('_', $sClassName);
        $sFileName = $sClassName . '.php';
        $sFileDir = JFRAME_DISK_ROOT . 'App/' . $arrClassName[count($arrClassName) - 1] . '/';
        $sFilePath = $sFileDir . $sFileName;
        if ((file_exists($sFilePath) === true) && (is_readable($sFilePath) === true)) {
            require_once ($sFilePath);
        }
    }

    public static function Business($sClassName) {
        if(class_exists($sClassName, false)) return true;

        $sFileName = $sClassName . '.php';
        $sFileDir = JFRAME_DISK_ROOT . 'App/AppBusiness/';
        $sFilePath = $sFileDir . $sFileName;
        if ((file_exists($sFilePath) === true) && (is_readable($sFilePath) === true)) {
            require_once ($sFilePath);
        }
    }

}

?>