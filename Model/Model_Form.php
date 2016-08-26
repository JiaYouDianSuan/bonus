<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Form
 *
 * @author jinlee
 */
class Model_Form extends Model {

    protected $_iId;
    protected $_arrData;

    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
        $this->setId($_GET['Id']);
        $this->_arrData = array();
        if($this->getId()!=''){
            $this->_arrData = $this->createData($this->getId());
            
        }
    }
    
    function setId($iId){
        $this->_iId = $iId;
    }
    
    function getId(){
        return $this->_iId;
    }
    
    function fetchField() {
        $mapAppStruct = $this->getAppStruct($this->getApp());
        return $mapAppStruct['field'];
    }

    function fetchData() {
        return $this->_arrData;
    }

}

?>
