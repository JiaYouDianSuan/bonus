<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Input_Combox
 *
 * @author jinlee
 */
class Model_Input_Relate extends Model {

    private $_sTable;
    private $_sKeyField;
    private $_sDisplayField;

    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
    }

    function setTable($sTable) {
        $this->_sTable = $sTable;
    }

    function getTable() {
        return $this->_sTable;
    }

    function setKeyField($sKeyField) {
        $this->_sKeyField = $sKeyField;
    }

    function getKeyField() {
        return $this->_sKeyField;
    }

    function setDisplayField($sDisplayField) {
        $this->_sDisplayField = $sDisplayField;
    }

    function getDisplayField() {
        return $this->_sDisplayField;
    }

    function createStruct($sJoinField) {
        $mapTableStruct = $this->getTableStruct();
        $arr = explode('.', $sJoinField);
        $sTable = $arr[0];
        $sField = $arr[1];
        $this->setDisplayField($sField);
        $this->setTable($sTable);
        $this->setKeyField($mapTableStruct[$sTable]['key']);
    }

    function fetchData() {
        
        $sSql = 'Select ' . $this->getKeyField() . ' As keyField, ' . $this->getDisplayField() . ' As displayField From ' . $this->getTable();
        if($this->getTable() == 'sys_table')
            $sSql .= ' Where iPk>'.PRIVATE_AUTO_INCREMENT;
        $arrRecord = $this->getDb()->select($sSql);
        return $arrRecord;
    }

}

?>
