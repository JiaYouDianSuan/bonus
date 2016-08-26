<?php

class Registor {

    static $_oInstance = false;
    private $_arrGlobal = NULL;

    private function __construct() {
        //$this->_arrGlobal = &$_SESSION['_Registor_' . md5(__DIR__) . '_'];
        //$this->_arrGlobal = &$_SESSION['_Registor_'];
        $this->_arrGlobal = array();
    }

    static public function create() {
        if (empty($_SESSION['_Registor_'])) {
            if (self::$_oInstance === false) {
                self::$_oInstance = new self();
            }
            $_SESSION['_Registor_'] = serialize(self::$_oInstance);
        }
        return unserialize($_SESSION['_Registor_']);
    }

    public function isValid($sKey) {
        return array_key_exists($sKey, $this->_arrGlobal);
    }

    public function get($sKey) {
        return $this->isValid($sKey) ? $this->_arrGlobal[$sKey] : array();
    }

    public function set($sKey, $mixData) {
        $this->_arrGlobal[$sKey] = $mixData;
        $_SESSION['_Registor_'] = serialize($this);
    }

}

?>