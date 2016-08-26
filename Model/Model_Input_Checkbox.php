<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Input_Checkbox
 *
 * @author jinlee
 */
class Model_Input_Checkbox extends Model {

    private $_sTable;
    private $_sKeyField;
    private $_sDisplayField;
    private $_sSortField = '';

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

    function setSortField($sField) {
        $this->_sSortField = $sField;
    }

    function getSortField() {
        return $this->_sSortField;
    }

    function createStruct($sJoinField) {
        $mapTableStruct = $this->getTableStruct();
        $arr = explode('.', $sJoinField);
        $sTable = $arr[0];
        $sField = $arr[1];
        $this->setDisplayField($sField);
        $this->setTable($sTable);
        $this->setKeyField($mapTableStruct[$sTable]['key']);
        if (!empty($mapTableStruct[$sTable]['field']['iSort'])) {
            $this->setSortField('iSort');
        }
    }

    function fetchData() {
        $sSql = 'Select 
            ' . $this->getKeyField() . ' As keyField, 
            ' . $this->getDisplayField() . ' As displayField 
            From ' . $this->getTable();
        if($this->getSortField() != '')
            $sSql .= ' Order By '.$this->getSortField().' Asc ';
        
        $arrRecord = $this->getDb()->select($sSql);
        return $arrRecord;
    }

}

?>