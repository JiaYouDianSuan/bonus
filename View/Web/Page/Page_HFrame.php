<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 水平方向的分隔框架
 */

/**
 * Description of Page_Frame
 *
 * @author jinlee
 */
abstract class Page_HFrame extends Page {

    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);
    }

    abstract function showLeft();
    
    abstract function showRight();        

    function show() {
        $sHtml = '<div class="pageContent">';
        $sHtml .= '<table width="100%" cellspacing="0" cellpadding="0" >';
        $sHtml .= '<tr>';
        $sHtml .= '<td style="width: 25%; border-right:1px solid #b8d0d6;">';                       
        $sHtml .= $this->showLeft();
        $sHtml .= '</td>';        
        $sHtml .= '<td id="page_hframe_right">';     
        $sHtml .= $this->showRight();        
        $sHtml .= '</td>';
        $sHtml .= '</tr>';
        $sHtml .= '</table>';
        $sHtml .= '</div>';

        return $sHtml;
    }

}

?>
