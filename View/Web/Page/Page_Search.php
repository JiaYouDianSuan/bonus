<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_Search
 *
 * @author jinlee
 */
class Page_Search extends Page{
    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);
    }
    
    function getForm(){
        $oSegment_Form = Segment::create('Form', $this->getApp());
        return $oSegment_Form;
    }

    function show() {
        $oSegment_Form = $this->getForm();

        $sHtml = '<div class="pageContent">';
        $sHtml .= '<form method="post" action="' . JFRAME_WWW_ROOT . 'Router.php?Page=Submit&App=' . $this->getApp() . '&Search=1" onsubmit="return navTabSearch(this);">';
        $sHtml .= $oSegment_Form->showSearch();        
        $sHtml .= '<div class="formBar">';
        $sHtml .= '<ul>';
        $sHtml .= '<li><div class="buttonActive"><div class="buttonContent"><button type="submit">搜索</button></div></div></li>';
        $sHtml .= '<li><div class="button"><div class="buttonContent"><button type="button" class="close">关闭</button></div></div></li>';
        $sHtml .= '</ul>';
        $sHtml .= '</div>';
        $sHtml .= '</form>';
        $sHtml .= ' </div>';

        return $sHtml;
    }
}

?>
