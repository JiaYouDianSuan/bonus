<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Input_Textarea
 *
 * @author jinlee
 */
class Segment_Input_Textarea extends Segment_Input {

    function __construct($sInput, $sApp) {
        parent::__construct($sInput, $sApp);
    }

    function show() {        
        $sCssClass = implode(' ', $this->createCssClass());
        $sHtml = '<p>';
        $sHtml .= $this->showLabel();   
        $sHtml .= '<textarea name="' . $this->getField() . '" cols="30" rows="4">' . $this->getValue() . '</textarea>';
        //$sHtml .= '<input type="textarea" name="' . $this->getField() . '" value="' . $this->getValue() . '" size="30" maxlength="50" class="' . $sCssClass . '" />';
        $sHtml .= '</p>';
        return $sHtml;
    }

}

?>
