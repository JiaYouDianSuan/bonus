<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_View
 *
 * @author jinlee
 */
class Page_View extends Page {

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
        $mapAppStruct = $oModel->getAppStruct($this->getApp());        
        $arrField = $mapAppStruct['field'];               
        $mapData = $oModel->createData($_GET['Id']);        
        $sHtml = '';        
        foreach($arrField as $v){
        	if($v['label']=='') continue;
        	
        	$oSegment_Input = Segment_Input::create($v['type'], $this->getApp());
        	$oSegment_Input->setFieldInfo($v);
        	$oSegment_Input->setValue($mapData[$v['name']]);        	
        	$oSegment_Input->setShowType('Content');
        	        	
        	$sContent = $v['type']!='Textarea'?$oSegment_Input->showGrid():str_replace("\n", "</br>", $oSegment_Input->showGrid());
        	$sHtml .= '<dl>';
        	$sHtml .= '<dt>'.$v['label'].'：</dt>';
        	$sHtml .= '<dd>'.$sContent.'</dd>';
        	$sHtml .= '</dl>';        	        	        	        	        	     
        }               
        return $sHtml;
    }

    function show() {
        $sHtml = '<div class="pageContent ">';
        $sHtml .= '<div class="pageFormContent nowrap" layoutH="111">';
        $sHtml .= $this->showContent();
        $sHtml .= '</div>';
        $sHtml .= '<div class="formBar">';
        $sHtml .= '<ul>';        
        $sHtml .= '<li><div class="button"><div class="buttonContent"><button type="button" class="close">关闭</button></div></div></li>';
        $sHtml .= '</ul>';
        $sHtml .= '</div>';
        $sHtml .= '</div>';

        return $sHtml;
    }

}

?>
