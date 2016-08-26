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
class Segment_Input_Combox extends Segment_Input {

    protected $_arrData;

    function __construct($sInput, $sApp) {
        parent::__construct($sInput, $sApp);
    }

    function setData($arrData) {    	 	   
        $this->_arrData = empty($arrData)?array():$arrData;
    }

    function getData() {
        return $this->_arrData;
    }

    function setFieldInfo($arrField) {      	    	 
        parent::setFieldInfo($arrField);
        $this->setData($arrField['comboxData']);
    }

    function show() {
        $arrData = $this->getData();             
        $sHtml = '<p>';
        $sHtml .= '<label>' . $this->getLabel() . '：</label>';
        $sHtml .= '<select class="combox" name="' . $this->getField() . '">';
        $sHtml .= '<option value="">全部</option>';    

        foreach ($arrData as $k => $v) {
            $sSelected = $this->getValue() == $k ? ' selected=selected ' : '';
            $sHtml .= '<option value="' . $k . '"' . $sSelected . '>' . $v . '</option>';
        }
        $sHtml .= '</select>';
        $sHtml .= '</p>';
        return $sHtml;
    }
    
    function showContent() {        
        $arrData = $this->getData();        
        return $arrData[$this->getValue()];
    }
    
    function showSearch() {
        return $this->show();       
    }

}

?>
