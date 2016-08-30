<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Input_Combox
 *
 * @author jinlee
 */
class Segment_Input_Relate extends Segment_Input {

    function __construct($sInput, $sApp) {
        parent::__construct($sInput, $sApp);
    }

    function setFieldInfo($arrField) {
        parent::setFieldInfo($arrField);
        $arr = explode('.', $arrField['mainField']);
        $this->setField($arr[1]);
        $this->getModel()->createStruct($arrField['joinField']);
    }   

    function show() {
        $oModel = $this->getModel();
        $arrData = $oModel->fetchData();
        $sHtml = '<p>';
        $sHtml .= '<label>' . $this->getLabel() . '：</label>';
        $sHtml .= '<select class="combox" name="' . $this->getField() . '">';
        $sHtml .= '<option value="">无</option>';
        foreach ($arrData as $v) {
            $sSelected = $this->getValue() == $v['keyField'] ? ' selected=selected ' : '';
            $sHtml .= '<option value="' . $v['keyField'] . '"' . $sSelected . '>' . $v['displayField'] . '</option>';
        }
        $sHtml .= '</select>';
        $sHtml .= '</p>';
        return $sHtml;
    }
    
    function showSearch() {
        return $this->show();       
    }

}

?>
