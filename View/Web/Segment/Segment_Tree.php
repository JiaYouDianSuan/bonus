<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Tree
 *
 * @author jinlee
 */
class Segment_Tree extends Segment {
    protected $_sReloadId;
            function __construct($sSegment, $sApp) {
        parent::__construct($sSegment, $sApp);
    }
    
    function setReloadId($sId){
        $this->_sReloadId = $sId;
    }
    
    function getReloadId(){
        return $this->_sReloadId;
    }
    
    function showTree($arrTreeNode) {        
        $sHtml = '';           
        foreach ($arrTreeNode as $v) {
            
            $sItem = '<a href="Router.php?App='.$this->getApp().'&Page=Edit&Id='.$v['pk'].'" target="ajax" rel="'.$this->getReloadId().'">' . $v['text'] . '</a>';
            if (empty($v['child'])) {
                $sHtml .= '<li target="'.TREE_NODE_FIELD_NAME.'" rel="'.$v['pk'].'">' . $sItem . '</li>';
            } else {
                $sHtml .= '<li target="'.TREE_NODE_FIELD_NAME.'" rel="'.$v['pk'].'">' . $sItem;
                $sHtml .= '<ul >' . $this->showTree($v['child']) . '</ul>';
                $sHtml .= '</li>';
            }
        }
        return $sHtml;
    }

    function show() {
        $oModel = $this->getModel();
        $arrTreeNode = $oModel->fetchTreeNode();
        //print_R($arrTreeNode);exit();

        $sHtml = '<ul class="tree treeFolder expand">';
        $sHtml .= $this->showTree($arrTreeNode);
        $sHtml .= '</ul>';
        return $sHtml;
    }

}

?>
