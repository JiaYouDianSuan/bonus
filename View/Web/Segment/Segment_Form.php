<?php

/*
 * To change this template, choose Tools | Templates and open the template in the editor.
 */

/**
 * Description of Segment_Form
 *
 * @author jinlee
 */
class Segment_Form extends Segment {
	protected $_mapInputValue;
	protected $_sPageType;
	protected $_iLayoutH = 97;
	protected $_arrFormElement = array(
		'Checkbox' => '复选框',
		'Combox' => '下拉框',
		'DateTime' => '日期时间',
		'Hidden' => '隐藏框',
		'KeyField' => '主键',
		'Relate' => '关联类型',
		'SN' => '自增流水',
		'Text' => '文本框',
		'Textarea' => '多行文本',
	);
	protected $_arrHiddenField = array (
		USER_FIELD_NAME,
		TIME_FIELD_NAME,
		CONFIRM_FIELD_NAME,
		CONFIRM_USER_FIELD_NAME,
		CONFIRM_TIME_FIELD_NAME
	);
	function __construct($sSegment, $sApp) {
		parent::__construct ( $sSegment, $sApp );
		
		$mapData = $this->getModel ()->fetchData ();
		$sPage = empty ( $mapData ) ? 'Add' : 'Edit';
		if ($sPage == 'Add') { // 主键值设为-1，提交后处理页面认为是新增操作。
			$mapData [KEY_FIELD_NAME] = '-1';
			if ($this->analysisTreeForm () === true) {
				$mapData [TREE_NODE_FIELD_NAME] = empty ( $_GET [TREE_NODE_FIELD_NAME] ) ? '0' : $_GET [TREE_NODE_FIELD_NAME];
			}
		}		
		$this->setInputValue ( $mapData );
		$this->setPageType ( $sPage );
	}
	function setInputValue($mapInputValue) {
		$this->_mapInputValue = $mapInputValue;
	}
	function getInputValue() {
		return $this->_mapInputValue;
	}
	function setPageType($sPageType) {
		$this->_sPageType = $sPageType;
	}
	function getPageType() {
		return $this->_sPageType;
	}
	function setLayoutH($iLayoutH) {
		$this->_iLayoutH = $iLayoutH;
	}
	function getLayoutH() {
		return $this->_iLayoutH;
	}
	function analysisTreeForm() {
		$mapAppStruct = $this->getModel ()->getAppStruct ( $this->getApp () );
		$sPage = $mapAppStruct ['table'] ['page'];
		if (strpos ( $sPage, 'Tree' ) != false && strpos ( $sPage, 'Form' ) != false) {
			return true;
		} else {
			return false;
		}
	}
	function showInputs() {
		$arrField = $this->getModel ()->fetchField ();
		$mapInputValue = $this->getInputValue ();
		$sHtml = '';
		foreach ( $arrField as $v ) {
			//if ($v ['display'] != true) continue;		
			if($v['label'] == '') continue;	
			if (in_array ( $v ['name'], $this->_arrHiddenField )) continue;
			$oInput = Segment_Input::create ( $v ['type'], $this->getApp () );
			$oInput->setFieldInfo ( $v );
			$oInput->setValue ( $mapInputValue [$oInput->getField ()] );
			$sHtml .= $oInput->show ();
		}
		return $sHtml;
	}
	function showHiddenInputs() {
		$sHtml = '';
		$mapInputValue = $this->getInputValue ();
		
		$keyHiddenInput = Segment_Input::create ( 'Hidden', $this->getApp () );
		$keyHiddenInput->setField ( KEY_FIELD_NAME );
		$keyHiddenInput->setValue ( $mapInputValue [KEY_FIELD_NAME] );
		$sHtml .= $keyHiddenInput->show ();
		
		if ($mapInputValue [TREE_NODE_FIELD_NAME] != '') {			
			$treeNodeHiddenInput = Segment_Input::create ( 'Hidden', $this->getApp () );
			$treeNodeHiddenInput->setField ( TREE_NODE_FIELD_NAME );
			$treeNodeHiddenInput->setValue ( $mapInputValue [TREE_NODE_FIELD_NAME] );
			$sHtml .= $treeNodeHiddenInput->show ();
		}
		return $sHtml;
	}
	function show() {
		$sHtml = '<div class="pageFormContent nowrap" layoutH="' . $this->getLayoutH () . '">';
		$sHtml .= $this->showHiddenInputs ();
		$sHtml .= $this->showInputs ();
		$sHtml .= '</div>';
		return $sHtml;
	}
	function showSearchInputs() {
		$arrField = $this->getModel ()->fetchField ();
		$mapInputValue = $this->getInputValue ();
		$sHtml = '';
		//print_R($arrField);
		foreach ( $arrField as $v ) {
			if ($v ['search'] == 1) {
				$oInput = Segment_Input::create ( $v ['type'], $this->getApp () );
				$oInput->setFieldInfo ( $v );
				$oInput->setValue ( $mapInputValue [$oInput->getField ()] );
				$sHtml .= $oInput->showSearch ();
			}
		}
		return $sHtml;
	}
	function showSearch() {
		$sHtml = '<div class="pageFormContent nowrap" layoutH="' . $this->getLayoutH () . '">';
		$sHtml .= $this->showSearchInputs ();
		$sHtml .= '</div>';
		return $sHtml;
	}
}

?>
