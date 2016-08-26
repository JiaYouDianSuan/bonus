<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Input_Checkbox
 *
 * @author jinlee
 */
class Segment_Input_Checkbox extends Segment_Input {

    protected $_arrData;

    function __construct($sInput, $sApp) {
        parent::__construct($sInput, $sApp);
    }

    function setData($arrData) {
        $this->_arrData = empty($arrData) ? array() : $arrData;
    }

    function getData() {
        return $this->_arrData;
    }

    function setFieldInfo($arrField) {
        parent::setFieldInfo($arrField);
        $this->getModel()->createStruct($arrField['joinField']);
        $this->setData($arrField['checkboxData']);
    }

    function show() {
        $sValue = ',' . $this->getValue() . ',';
        $arrRecord = $this->getModel()->fetchData();
        $sHtml = '<div class="divider"></div>';
        $sHtml .= $this->showLabel();
        foreach ($arrRecord as $v) {
            $sChecked = strpos($sValue, ",".$v['keyField'].",") === false ? '' : 'checked="checked"';
            $sHtml .= '<label><input type="checkbox" 
                name="checkButtonCode[]" 
                value="' . $v['keyField'] . '" ' . $sChecked . ' />' . $v['displayField'] . '</label>';
        }
        $sHtml .= '<div class="divider"></div>';
        return $sHtml;
    }

    function showContent() {
        $mapData = $this->getData();
        $sContent = $this->getValue();
        $sContent = ',' . $sContent . ',';
        foreach ($mapData as $k => $v) {
            $sContent = str_replace(',' . $k . ',', ',' . $v . ',', $sContent);
        }
        return trim($sContent, ',');
    }

}

?>
