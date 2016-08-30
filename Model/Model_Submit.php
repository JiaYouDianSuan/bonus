<?php

/*
 * To change this template, choose Tools | Templates and open the template in the editor.
 */

/**
 * Description of Model_Submit
 *
 * @author jinlee
 */
class Model_Submit extends Model {
	function __construct($sModel, $sApp) {
		parent::__construct ( $sModel, $sApp );
	}
	function unsetField($arrField = array()) {
		foreach ( $arrField as $v ) {
			unset ( $_POST [$v] );
		}
		unset ( $_POST [KEY_FIELD_NAME] );
		unset ( $_POST [TREE_NODE_FIELD_NAME] );
		unset ( $_POST ['detailItems_insert'] );
		unset ( $_POST ['dwz_rowNum'] );
	}
	function beforeFormSave($iId) {
		return true;
	}
	function afterFormSave($iId) {
	}
	function genSN($sTable, $sField) {
		$sSql = 'Select sSnPre From sys_table Where sName="' . $sTable . '"';
		$arrRecord = $this->getDb ()->select ( $sSql );
		$sSnPre = $arrRecord [0] ['sSnPre'];
		
		$sDate = date ( 'Ymd' );
		$sSql = "Select max($sField) As sMaxSN From $sTable Where $sField Like ('$sSnPre$sDate%')";
		$arrRecord = $this->getDb ()->select ( $sSql );
		$sMaxSN = $arrRecord [0] ['sMaxSN'];
		$sAutoIncrease = substr ( $sMaxSN, - SN_DIGIT_COUNT );
		$sSn = $sSnPre . $sDate . sprintf ( '%0' . SN_DIGIT_COUNT . 'd', $sAutoIncrease + 1 );
		return $sSn;
	}
	function formSave() {
		$arrDetailItems_insert = $_POST ['detailItems_insert'];
		$arrDetailItems_update = $_POST ['detailItems_update'];
		$iDetailNum = $_POST ['dwz_rowNum'];
		$iId = $_POST [KEY_FIELD_NAME];

		$this->unsetField ();
		if (true !== $result = $this->beforeFormSave ( $iId ))
			return $result;
		
		$mapTableStruct = $this->getTableStruct ();
		$mapAppStruct = $this->getAppStruct($this->getApp());
		$arrAppSql = $this->getAppSql ( $this->getApp () );
		$sMainTable = $arrAppSql ['from'];
		$sKeyField = $mapTableStruct [$sMainTable] ['key'];
		$arrField = $mapAppStruct['field'];
		
		$arrSqlWhere_KeyField = array();
		$arrKeyFieldName = array();
		foreach($arrField as $v){
			if($v['type']=='KeyField' && array_key_exists($v['name'],$_POST)){
				$arrSqlWhere_KeyField[] = $v['name'].'="'.$_POST[$v['name']].'"';
				$arrKeyFieldName[] = $v['label'];
			}
		}		
		if(!empty($arrSqlWhere_KeyField)){//如果表中有唯一类型字段，验证该字段的唯一性
			$sSql = "Select count({$sKeyField}) As iCount From {$sMainTable} Where ".implode(' And ', $arrSqlWhere_KeyField)." And {$sKeyField}!='{$iId}'";
			$arrRecord = $this->getDb ()->select ( $sSql );
			if($arrRecord[0]['iCount'] > 0){
				return array (
					'status' => 'error',
					'message' => '字段 【'.implode(',',$arrKeyFieldName).'】 不允许重复！'
				);
			}
		} 			
		
		$arrSqlSet = array ();
		if($sMainTable == 'sys_user') $arrSqlSet[] = 'sPassword="'. md5(DEFAULT_PASSWORD).'"';
		foreach ( $_POST as $k => $v ) {			
			if ($v == SN_FIELD_VALUE) {
				if ($iId == - 1)
					$v = $this->genSN ( $sMainTable, $k );
				else
					continue;
			}			
			$arrSqlSet [] = $k . '="' . addslashes ( $v ) . '"';
		}
		if (substr ( $sMainTable, 0, 4 ) == 'bus_') {
			$arrLogin = Frame::getLoginInfo ();
			$arrSqlSet [] = USER_FIELD_NAME . '="' . $arrLogin ['id'] . '"';
			$arrSqlSet [] = TIME_FIELD_NAME . '=now()';
		}
		if ($iId == - 1) { // Add
			$sSql = 'Insert Into ' . $sMainTable . ' Set ' . implode ( ',', $arrSqlSet );
			$iId = $this->getDb ()->insert ( $sSql );
			Log::create ()->createInsertLog ( $sMainTable, $iId );
		} else { // Edit
			Log::create ()->createUpdateLogStart ( $sMainTable, $iId );
			$sSql = 'Update ' . $sMainTable . ' Set ' . implode ( ',', $arrSqlSet ) . ' Where ' . $sKeyField . '="' . $iId . '"';			
			$this->getDb ()->query ( $sSql );
			Log::create ()->createUpdateLogEnd ( $sMainTable, $iId );
		}
		
		$mapAppStuct = $this->getAppStruct ( $this->getApp () );
		$sAppDetail = $mapAppStuct ['app'] ['detail'];
		$sDetailField = $mapAppStuct ['app'] ['detailField'];
		if ($sAppDetail != '') {
			$oModel_ItemSubmit = Model::create ( 'Submit', $sAppDetail );
			$oModel_ItemSubmit->itemSave ( $sDetailField, $iId, $arrDetailItems_insert );
		}

		$this->afterFormSave ( $iId );
		return true;
	}
	function itemSave($sDetailField, $iId, $arrItem) {
		if (empty ( $arrItem ))
			return;
		$mapTableStruct = $this->getTableStruct ();
		$arrAppSql = $this->getAppSql ( $this->getApp () );
		$sMainTable = $arrAppSql ['from'];
		foreach ( $arrItem as $arrRow ) {
			$arrSqlSet = array (
				$sDetailField . '="' . $iId . '"' 
			);
			foreach ( $arrRow as $field => $value ) {
				$arrSqlSet [] = $field . '="' . addslashes ( $value ) . '"';
			}
			$sSql = 'Insert Into ' . $sMainTable . ' Set ' . implode ( ',', $arrSqlSet );
			$this->getDb ()->query ( $sSql );
		}
	}
	function treeSave() {
		$oModel_Tree = Model::create ( 'Tree', $this->getApp () );
		$iChildNodePk = $oModel_Tree->insertChild ( $_POST [TREE_NODE_FIELD_NAME] );

		if ($iChildNodePk == false) {
			return array (
				'status' => 'error',
				'message' => '请选择节点后编辑！' 
			);
		} else {
			$_POST [KEY_FIELD_NAME] = $iChildNodePk;
			$_POST ['iParentPk'] = $_POST [TREE_NODE_FIELD_NAME];
			return true;
		}
	}
	function save() {
		if ($_POST [TREE_NODE_FIELD_NAME] != '') {
			if (true !== $result = $this->treeSave ()) {
				return $result;
			}
		}

		if (true !== $result = $this->formSave ()) {
			return $result;
		}
	}
	function beforeDelete($iId) {
		return true;
	}
	function delete() {
		$iId = $_GET ['Id'];

		if (true !== $result = $this->beforeDelete ( $iId ))
			return $result;
		
		$mapTableStruct = $this->getTableStruct ();
		$arrAppSql = $this->getAppSql ( $this->getApp () );
		$sMainTable = $arrAppSql ['from'];
		$sKeyField = $mapTableStruct [$sMainTable] ['key'];

		if($_GET['Type'] == 'Tree'){
			$sSql = "Select count(*) as iCount From $sMainTable Where iParentPk=$iId";
			$rs = $this->getDb()->select($sSql);
			if($rs[0]["iCount"]>0){
				$result = array (
					'status' => 'error',
					'message' => '有子节点，不允许删除！'
				);
			}else{
				$oModel_Tree = Model::create ( 'Tree', $this->getApp () );
				Log::create ()->createDeleteLog ( $sMainTable, $iId );
				$oModel_Tree->deleteNode($iId);
				$result = array (
					'status' => 'ok',
					'message' => '删除成功！'
				);
			}
		}else{
			Log::create ()->createDeleteLog ( $sMainTable, $iId );
			$sSql = 'Delete From ' . $sMainTable . ' Where ' . $sKeyField . '="' . $iId . '"';
			$this->getDb ()->query ( $sSql );

			$mapAppStuct = $this->getAppStruct ( $this->getApp () );
			$sAppDetail = $mapAppStuct ['app'] ['detail'];
			$sDetailField = $mapAppStuct ['app'] ['detailField'];
			if ($sAppDetail != '') {
				$oModel_ItemSubmit = Model::create ( 'Submit', $sAppDetail );
				$oModel_ItemSubmit->itemDelete ( $sDetailField, $iId );
			}
			$result = array (
				'status' => 'ok',
				'message' => '删除成功！'
			);
		}
		return $result;
	}
	function itemDelete($sDetailField, $iId) {
		$mapTableStruct = $this->getTableStruct ();
		$arrAppSql = $this->getAppSql ( $this->getApp () );
		$sMainTable = $arrAppSql ['from'];
		
		$sSql = 'Delete From ' . $sMainTable . ' Where ' . $sDetailField . '="' . $iId . '"';
		$this->getDb ()->query ( $sSql );
	}
	function confirm($iConfirmFlag) {
		$iId = $_GET ['Id'];
		$arrLogin = Frame::getLoginInfo ();
		$mapTableStruct = $this->getTableStruct ();
		$arrAppSql = $this->getAppSql ( $this->getApp () );
		$sMainTable = $arrAppSql ['from'];
		$sKeyField = $mapTableStruct [$sMainTable] ['key'];
		
		$sUserId = $iConfirmFlag == 1 ? $arrLogin ['id'] : '';
		$sTime = $iConfirmFlag == 1 ? 'now()' : '""';
		$arrSqlSet [] = CONFIRM_FIELD_NAME . '="' . $iConfirmFlag . '"';
		$arrSqlSet [] = CONFIRM_USER_FIELD_NAME . '="' . $sUserId . '"';
		$arrSqlSet [] = CONFIRM_TIME_FIELD_NAME . '=' . $sTime;
		
		$sSql = 'Update ' . $sMainTable . ' Set ' . implode ( ',', $arrSqlSet ) . ' Where ' . $sKeyField . '="' . $iId . '"';
		$this->getDb ()->query ( $sSql );
		
		return array (
			'status' => 'ok',
			'message' => $iConfirmFlag == 1 ? '审核成功！' : '取消审核成功！' 
		);
	}
}

?>
