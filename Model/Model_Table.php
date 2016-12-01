<?php

/*
 * To change this template, choose Tools | Templates and open the template in the editor.
 */

/**
 * Description of Model_Table
 *
 * @author jinlee
 */
class Model_Table extends Model {
	function __construct($sModel, $sApp) {
		parent::__construct ( $sModel, $sApp );
	}
	function isHaveOptField($sPage) {
		$arrFind = array (
			'Grid',
			'HFrame_GridGrid' 
		);
		if (in_array ( $sPage, $arrFind )) {
			$sSql = "Select sButton From sys_menu Where sApp='{$this->getApp()}'";
			$arrRecords = $this->getDb()->select($sSql);
			if($arrRecords[0]['sButton'] == ""){
				return false;
			}else{
				return true;
			}
		} else {
			return false;
		}
	}
	function genSqlWhere_Search() {
		$arrSqlWhere = array ();
		if ($_GET ['Search'] == 1) {
			$mapAppStruct = $this->getAppStruct ( $this->getApp () );
			$sTable = $mapAppStruct ['table'] ['name'];
			foreach ( $_POST as $k => $v ) {
				$arrSqlWhere [] = $sTable . '.' . $k . ' Like "%' . $v . '%"';
			}
		}
		return $arrSqlWhere;
	}
	function genSqlWhere_Private() {
		$arrSqlWhere = array ();
		$arrPrivateTable = array (
			'sys_table',
			'sys_table_field',
			'sys_table_join',
			'sys_menu_class',
			'sys_menu' 
		);
		
		$mapAppStruct = $this->getAppStruct ( $this->getApp () );
		$sTable = $mapAppStruct ['table'] ['name'];
		$sKeyField = $mapAppStruct ['table'] ['keyField'];
		if (in_array ( $sTable, $arrPrivateTable )) {
			$arrSqlWhere [] = $sTable . '.' . $sKeyField . '>' . PRIVATE_AUTO_INCREMENT;
		}
		return $arrSqlWhere;
	}
	function genSqlWhere_Lookup() {
		$arrSqlWhere = array ();
		$sLookupField = $_GET ['LookupField'];
		$sLookupId = $_GET ['LookupId'];
		if (! empty ( $sLookupField )) {
			$mapAppStruct = $this->getAppStruct ( $this->getApp () );
			$sTable = $mapAppStruct ['table'] ['name'];
			$arrSqlWhere [] = $sTable . '.' . $sLookupField . '=' . $sLookupId;
		}
		return $arrSqlWhere;
	}
	function genSqlWhere_AuthorityRange() {
		$arrLogin = Frame::getLoginInfo ();
		$arrAuthority = Frame::getAuthorityInfo ();
		$arrSqlSet [] = USER_FIELD_NAME . '="' . $arrLogin ['id'] . '"';
		$arrSqlWhere = array ();
		$mapAppStruct = $this->getAppStruct ( $this->getApp () );
		$sTable = $mapAppStruct ['table'] ['name'];

		if (is_array($arrAuthority) && ! empty ( $arrAuthority ['Range'] ['read'] [$this->getApp ()] ) && $arrAuthority ['Range'] ['read'] [$this->getApp ()] == 'Self') {
			$arrSqlWhere [] = $sTable . '.' . USER_FIELD_NAME . ' = "' . $arrLogin ['id'] . '"';
		}
		return $arrSqlWhere;
	}
	function fetchField() {
		$mapAppStruct = $this->getAppStruct ( $this->getApp () );
		if ($this->isHaveOptField ( $mapAppStruct ['table'] ['page'] )) {
			$mapAppStruct ['field'] [] = array (
				'name' => OPT_FIELD_NAME,
				'label' => '操作',
				'width' => 150,
				'display' => true 
			);
		}
		return $mapAppStruct ['field'];
	}
	function fetchRecord($iStart = '', $iLimit = '') {
		$arrSqlWhere = array ();
		$arrSqlWhere = array_merge ( $arrSqlWhere, $this->genSqlWhere_Search () );
		$arrSqlWhere = array_merge ( $arrSqlWhere, $this->genSqlWhere_Private () );
		$arrSqlWhere = array_merge ( $arrSqlWhere, $this->genSqlWhere_Lookup () );
		$arrSqlWhere = array_merge ( $arrSqlWhere, $this->genSqlWhere_AuthorityRange () );
		
		$oModel_Button = Model::create ( 'Button', $this->getApp () );
		$mapAppStruct = $this->getAppStruct ( $this->getApp () );
		// 主键放在记录行的第一个
		$arrAppSql = $this->getAppSql ( $this->getApp () );

	//	if(empty($arrAppSql)) return array();

		$sSql = $this->joinSql ( $arrAppSql );
		if (! empty ( $arrSqlWhere )) {
			$sSql .= ' Where ' . implode ( ' And ', $arrSqlWhere );
		}

		$sSql .= " Order by ".KEY_FIELD_NAME." desc ";

		if ($iStart !== '' && $iLimit !== '') {
			$sSql .= ' limit ' . $iStart . ',' . $iLimit;
		}

		$arrRecord = $this->getDb ()->select ( $sSql );

		if ($this->isHaveOptField ( $mapAppStruct ['table'] ['page'] )) {
			for($i = 0, $iMax = count ( $arrRecord ); $i < $iMax; $i ++) {
				$arrRecord [$i] ['TABLE_OPERATION'] = $oModel_Button->fetchInRow ();
			}
		}
		return $arrRecord;
	}
	function fetchFieldHash() {
		$mapField = array ();
		$arrField = $this->fetchField ();
		foreach ( $arrField as $v ) {
			$mapField [$v ['name']] = $v;
		}
		return $mapField;
	}
}

?>
