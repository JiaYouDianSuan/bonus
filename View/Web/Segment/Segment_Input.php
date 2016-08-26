<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Input
 *
 * @author jinlee
 */
class Segment_Input extends Segment {

    protected $_sInput;
    protected $_bRequired = false;
    protected $_sLabel;
    protected $_sValue;
    protected $_sField;
    protected $_bDisplay = true;
    protected $_sShowType;

    function __construct($sInput, $sApp) {
        parent::__construct('Input_' . $sInput, $sApp);
        $this->setInput($sInput);
        $this->setShowType('Content');
        //$this->setShowType('Edit');
    }

    static function create($sInput, $sApp) {
        return Factory::create(__CLASS__, $sInput, $sApp);
    }

    function setInput($sInput) {
        $this->_sInput = $sInput;
    }

    function getInput() {
        return $this->_sInput;
    }

    function setRequired($bRequired) {
        $this->_bRequired = (bool) $bRequired;
    }

    function getRequired() {
        return $this->_bRequired;
    }

    function setDisplay($bDisplay) {
        $this->_bDisplay = (bool) $bDisplay;
    }

    function getDisplay() {
        return $this->_bDisplay;
    }

    function setLabel($sLabel) {
        $this->_sLabel = $sLabel;
    }

    function getLabel() {
        return $this->_sLabel;
    }

    function setValue($sValue) {
        $this->_sValue = $sValue;
    }

    function getValue() {
        return $this->_sValue;
    }

    function setField($sField) {
        $this->_sField = $sField;
    }

    function getField() {
        return $this->_sField;
    }
    
    function setShowType($sShowType){
        !empty($sShowType) && $this->_sShowType = $sShowType;
    }
    
    function getShowType(){
        return $this->_sShowType;
    }

    function setFieldInfo($arrField) {
        $this->setRequired($arrField['required']);
        $this->setLabel($arrField['label']);
        $this->setField($arrField['name']);
        $this->setDisplay($arrField['display']);
    }

    function createCssClass() {
        $sCssRequired = $this->getRequired() ? 'required' : '';
        return array($sCssRequired);
    }

    function showLabel() {
        return '<label>' . $this->getLabel() . '：</label>';
    }

    function show() {//Form中的实现       
        $sHtml = '<p>';
        $sHtml .= '在子类[' . $this->getInput() . ']中实现show方法';
        $sHtml .= '</p>';
        return $sHtml;
    }

    function showGrid($sRowId='') {//Grid中的实现
        $sHtml = '';
        switch ($this->getShowType()){
            case 'Content':                
                $sHtml = $this->showContent();
                break;
            case 'Edit':                
                $sHtml = $this->showEdit($sRowId);
                break;
        }
        return $sHtml;
    }

    function showContent() {//Grid中显示模式
        return $this->getValue();
    }

    function showEdit($sRowId) {//Grid中编辑模式      
        return $this->getField()==OPT_FIELD_NAME?$this->getValue():'<input type="text" name="detailItems_update['.$sRowId.']['.$this->getField().']" value="'.$this->getValue().'" />';
    }

    function showSearch() {//搜索中的实现
        $sHtml = '<p>';
        $sHtml .= $this->showLabel();
        $sHtml .= '<input type="text" name="' . $this->getField() . '" value="" size="30" maxlength="20" />';
        $sHtml .= '</p>';
        return $sHtml;
    }

}

?>
