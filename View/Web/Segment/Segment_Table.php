<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Table
 *
 * @author jinlee
 */
class Segment_Table extends Segment {

    protected $_arrSqlWhere;
    protected $_sShowType = 'Content';

    function __construct($sSegment, $sApp) {
        parent::__construct($sSegment, $sApp);        
    }

    function setSqlWhere($arrSqlWhere) {
        $this->_arrSqlWhere = $arrSqlWhere;
    }

    function getSqlWhere() {
        return $this->_arrSqlWhere;
    }

    function show() {
        $oToolbar = Segment_Toolbar::create('Toolbar', $this->getApp());
        $sHtmlToolbar = $oToolbar->show();
        $sLayoutH = $sHtmlToolbar==""?"48":"75";
        $sTable = "{$sHtmlToolbar}<table class='table' width='100%' layoutH='{$sLayoutH}'>{$this->showHeader()}{$this->showBody()}</table>";
        //$sTable = $sHtmlToolbar . '<table class="table" width="100%" layoutH="75" >' . $this->showHeader() . $this->showBody() . '</table>';
        return $sTable;
    }

    function showHeader() {
        $oModel = $this->getModel();
        $arrField = $oModel->fetchField();
        empty($arrField) && $arrField=array();

        $sHtml = '<thead><tr>';
        foreach ($arrField as $v) {
            if ($v['display'] != true || $v['type']=='Textarea') continue;
            $sHtml .= '<th width="'.$v['width'].'">' . $v['label'] . '</th>';
        }
        $sHtml .= '</tr></thead>';
        return $sHtml;
    }

    function showBody() {    	
    	$iPageNum = empty($_POST['pageNum'])?1:$_POST['pageNum'];
    	$iNumPerPage = empty($_POST['numPerPage'])?NUM_PER_PAGE:$_POST['numPerPage'];

    	$iStart = ($iPageNum-1)*$iNumPerPage;
    	$iLimit = $iNumPerPage;
    	
        $oButton = Segment_Button::create('Button', $this->getApp());
        $oModel = $this->getModel();

        $arrRecord = $oModel->fetchRecord($iStart,$iLimit);
        $arrField = $oModel->fetchField();

        $sHtml = "<tbody>";
        foreach ($arrRecord as $arrRow) {        	
            $sRowId = $arrRow[KEY_FIELD_NAME];
            unset($arrRow[KEY_FIELD_NAME]);
            if ($arrRow[OPT_FIELD_NAME] != '')
                $arrRow[OPT_FIELD_NAME] = $oButton->showInRow($arrRow, $sRowId);

            $sHtmlRed = $arrRow[DELETE_FIELD_NAME] == 1?"style='background:red;'":"";

            $sHtml .= "<tr $sHtmlRed target='row_id_'$this->getApp() rel='$sRowId'>";
            foreach ($arrField as $v) {
                if ($v['display'] != true || $v['type']=='Textarea') continue;
                $oSegment_Input = Segment_Input::create($v['type'], $this->getApp());
                $oSegment_Input->setFieldInfo($v);
                $oSegment_Input->setValue($arrRow[$v['name']]);
                $oSegment_Input->setShowType($this->_sShowType);
                $sWidth = empty($v['width'])?'':' width="'.$v['width'].'" ';
                $sHtml .= '<td '.$sWidth.'>' . $oSegment_Input->showGrid() . '</td>';
            }            
            $sHtml .= '</tr>';
        }
        $sHtml .= '</tbody>';
        return $sHtml;
    }

}

?>
