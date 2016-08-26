<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_HFrame_TreeForm
 *
 * @author jinlee
 */
class Page_HFrame_TreeForm extends Page_HFrame {

    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);

    }

    function showScript() {
        //echo TREE_NODE_FIELD_NAME;
        $sHtml = '<script>addTreeNode();';
        /*$sHtml .= 'function addTreeNode(el){
        		var sValue = $("#' . TREE_NODE_FIELD_NAME . '").attr("value");
        		if(typeof(sValue) == "undefined") sValue=0;        		
        		$(el).attr("href",$(el).attr("href")+"&' . TREE_NODE_FIELD_NAME . '="+sValue);
    	}';*/
        $sHtml .= '</script>';
        return $sHtml;
    }

    function showRight() {       
        $oPage_Edit = Page::create('Add', $this->getApp());
        $oPage_Edit->setDirection('Right');
        return $oPage_Edit->show();
    }

    function showLeft() {
        $sHtml = '';
        $oSegment_Toolbar = Segment::create('Toolbar', $this->getApp());
        $oSegment_Tree = Segment::create('Tree', $this->getApp());
        $oSegment_Tree->setReloadId('page_hframe_right');

        //$sHtml .= $this->showScript();
        $sHtml .= $oSegment_Toolbar->show();        
        $sHtml .= '<div layoutH="25">';
        $sHtml .= $oSegment_Tree->show();
        $sHtml .= '</div>';
        return $sHtml;
    }

}

?>
