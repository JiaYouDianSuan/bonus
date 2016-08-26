<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Input_DateTime
 *
 * @author jinlee
 */
class Segment_Input_DateTime extends Segment_Input {

    function __construct($sInput, $sApp) {
        parent::__construct($sInput, $sApp);
    }

    function show() {
        $sCssClass = implode(' ', $this->createCssClass()).' date';
        $sHtml = '<p>';
        $sHtml .= $this->showLabel();        
        $sHtml .= '<input type="text" class="' . $sCssClass . '" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true" name="' . $this->getField() . '" value="' . $this->getValue() . '" size="30" maxlength="50" />';
        $sHtml .= '<a class="inputDateButton" href="javascript:;">选择</a>';
        $sHtml .= '</p>';
        return $sHtml;
    }
    
    function showSearch() {//搜索中的实现
    	$sHtml = '<p>';
    	$sHtml .= $this->showLabel();
    	$sHtml .= '<input type="text" class="date" dateFmt="yyyy-MM-dd HH:mm:ss" name="' . $this->getField() . '"  size="30" maxlength="50" />';
        $sHtml .= '<a class="inputDateButton" href="javascript:;">选择</a>';
    	$sHtml .= '</p>';
    	return $sHtml;
    }

}

?>
