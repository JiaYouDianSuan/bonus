<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Input_Hidden
 *
 * @author jinlee
 */
class Segment_Input_Hidden extends Segment_Input {

    function __construct($sInput, $sApp) {
        parent::__construct($sInput, $sApp);
    }

    function show() {
        $sHtml = '<input type="hidden" name="' . $this->getField() . '" value="' . $this->getValue() . '"  />';
        return $sHtml;
    }

}

?>
