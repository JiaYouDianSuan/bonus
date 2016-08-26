<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Page
 *
 * @author jinlee
 */
class Segment_Page extends Segment {

    protected $_iPageNum;
    protected $_iNumPerPage;

    function __construct($sSegment, $sApp) {
        parent::__construct($sSegment, $sApp);
        $iPageNum = $_POST['pageNum'];
        $iNumPerPage = $_POST['numPerPage'];

        $this->_iPageNum = $iPageNum > 0 ? $iPageNum : 1;
        $this->_iNumPerPage = $iNumPerPage > 0 ? $iNumPerPage : NUM_PER_PAGE;
    }

    function show() {
        $oModel = $this->getModel();
        $iDataCount = $oModel->fetchTotalCount();
        $iPageCount = ceil($iDataCount / $this->_iNumPerPage);
        $this->_iPageNum = $iPageCount < $this->_iPageNum ? '1' : $this->_iPageNum;

        $sId = "page_grid_{$this->getApp()}";

        $sHtml = '<div class="panelBar">';
        $sHtml .= '<div class="pages" >';
        $sHtml .= $this->showForm();
        $sHtml .= '<span>显示</span>';
        $sHtml .= $this->showSelect();
        $sHtml .= '<span>条，共' . $iDataCount . '条，共' . $iPageCount . '页</span>';
        $sHtml .= '</div>';
        $sHtml .= '<div 
                class="pagination"
                targetType="navTab"
rel="'.$sId.'"
                pageNumShown="5" 
                totalCount="' . $iDataCount . '" 
                numPerPage="' . $this->_iNumPerPage . '" 
                currentPage="' . $this->_iPageNum . '"></div>';
        $sHtml .= '</div>';
        return $sHtml;
    }

    function showSelect() {
        $arrPageSelect = array(20, 50, 100, 200);
        $sHtml = '<select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">';
        foreach ($arrPageSelect as $v) {
            $sSelect = $this->_iNumPerPage == $v ? 'selected="selected"' : '';
            $sHtml .= '<option value="' . $v . '" ' . $sSelect . ' >' . $v . '</option>';
        }
        $sHtml .= '</select>';
        return $sHtml;
    }

    function showForm() {
        $sAction = "Router.php?App={$this->getApp()}&Page={$_GET["Page"]}";
        $sHtml = '<form id="pagerForm" method="post" action="'.$sAction.'"	>';
        $sHtml .= '<input type="hidden" name="pageNum" value="' . $this->_iPageNum . '" />';
        $sHtml .= '<input type="hidden" name="numPerPage" value="' . $this->_iNumPerPage . '" />';
        $sHtml .= '<input type="hidden" name="orderField" value="" />';
        $sHtml .= '<input type="hidden" name="orderDirection" value="" />';
        $sHtml .= '</form>';
        return $sHtml;
    }

}

?>
