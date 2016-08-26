<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_Edit_SysUserGrp
 *
 * @author jinlee
 */
class Page_Edit_SysUserGrp extends Page_Edit{
    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);
    }
    
    function getForm() {
        $oSegment_Form = Segment::create('Form', $this->getApp());
        $oSegment_Form->setLayoutH('55');
        return $oSegment_Form;
    }

    function show() {
        $sDelUrl = JFRAME_WWW_ROOT."Router.php?App=".$this->getApp()."&Page=Del&Type=Tree&Id=".$_GET['Id'];
        $sHtmlScript = "onclick='window.location.href=\"$sDelUrl\"'";

        $sHtml = '<div class="pageContent">';
        $sHtml .= '<form method="post" action="' . JFRAME_WWW_ROOT . 'Router.php?Page=Submit&App=' . $this->getApp() . '" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">';
        $sHtml .= $this->showContent();
        $sHtml .= '<div class="formBar">';
        $sHtml .= '<ul>';
        $sHtml .= '<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>';
        $sHtml .= '<li><div class="button"><div class="buttonContent"><a href="'.$sDelUrl.'" target="ajaxTodo" class="close">删除</a></div></div></li>';
        $sHtml .= '</ul>';
        $sHtml .= '</div>';
        $sHtml .= '</form>';
        $sHtml .= ' </div>';

        return $sHtml;
    }
}

?>
