<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_Edit
 *
 * @author jinlee
 */
class Page_Edit extends Page {

    private $_oForm = Null;

    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);
    }

    function getForm() {
        if ($this->_oForm == Null)
            $this->_oForm = Segment::create('Form', $this->getApp());
        return $this->_oForm;
    }

    function showGridEditor() {
        $oModel = Model::create('', $this->getApp());
        $mapAppStuct = $oModel->getAppStruct($this->getApp());

        $oPage_Grid = Page::create('Grid_Editor', $mapAppStuct['app']['detail']);

        return $oPage_Grid->show();
    }

    function showContent() {
        $oModel = Model::create('', $this->getApp());
        $mapAppStuct = $oModel->getAppStruct($this->getApp());
        //print_R($mapAppStuct);
        if ($mapAppStuct['app']['detail'] != '') {
            $_GET['LookupField'] = $mapAppStuct['app']['detailField'];
            $_GET['LookupId'] = $_GET['Id'];
        
            $this->getForm()->setLayoutH(111);
            $sHtmlContent = <<<EOD
            <div class="tabs" currentIndex="0" eventType="click" >
                <div class="tabsHeader">
                    <div class="tabsHeaderContent">
                        <ul>
                            <li><a href="javascript:;"><span>内容</span></a></li>
                            <li><a href="javascript:;"><span>明细</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="tabsContent" layoutH="90">
                    <div>{$this->getForm()->show()}</div>
                    <div>{$this->showGridEditor()}</div>
                </div>
                <div class="tabsFooter">
                    <div class="tabsFooterContent"></div>
                </div>
            </div>
EOD;
        } else {
            $sHtmlContent .= $this->getForm()->show();
        }
        return $sHtmlContent;
    }

    function show() {
        $sHtml = '<div class="pageContent">';
        $sHtml .= '<form method="post" action="' . JFRAME_WWW_ROOT . 'Router.php?Page=Submit&App=' . $this->getApp() . '" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">';
        $sHtml .= $this->showContent();
        $sHtml .= '<div class="formBar">';
        $sHtml .= '<ul>';
        $sHtml .= '<li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>';
        $sHtml .= '<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>';
        $sHtml .= '</ul>';
        $sHtml .= '</div>';
        $sHtml .= '</form>';
        $sHtml .= ' </div>';

        return $sHtml;
    }

}

?>
