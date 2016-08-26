<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Input_Text
 *
 * @author jinlee
 */
class Segment_Input_SN extends Segment_Input {

    function __construct($sInput, $sApp) {
        parent::__construct($sInput, $sApp);
    }

    function show() {
        $sSnValue = $this->getValue();        
        empty($sSnValue)&&$sSnValue='自增流水号';
        $sCssClass = implode(' ', $this->createCssClass());
        $sHtml = '<p>';
        $sHtml .= $this->showLabel();
        $sHtml .= '<input type="text" value="'.$sSnValue.'" style="border-top:0px; border-left:0px; border-right:0px;" readonly="true" size="30" maxlength="20" class="' . $sCssClass . '" />';
        $sHtml .= '<input type="hidden" name="' . $this->getField() . '" value="' . SN_FIELD_VALUE . '" />';
        $sHtml .= '</p>';
        return $sHtml;
    }

}

?>
