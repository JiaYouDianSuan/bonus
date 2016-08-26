<?php

/*
 * To change this template, choose Tools | Templates and open the template in the editor.
 */

/**
 * Description of Model_Submit
 *
 * @author jinlee
 */
class Model_Submit_SysTable extends Model_Submit {
	function __construct($sModel, $sApp) {
		parent::__construct ( $sModel, $sApp );
	}
	function updateJoinTable($iMainTablePk) {
		$sSql = 'Select sJoinField, sName From sys_table_field Where iTablePk=' . $iMainTablePk . ' And sJoinField!=""';
		$arrRecord = $this->getDb ()->select ( $sSql );
		
		$mapJoinTableNew = array ();
		foreach ( $arrRecord as $v ) {
			$array = explode ( '.', $v ['sJoinField'] );
			$sJoinTable = $array [0];
			if (! array_key_exists ( $sJoinTable, $mapJoinTableNew )) {
				$mapJoinTableNew [$sJoinTable] = array (
						'mainTablePk' => $iMainTablePk,
						'joinTable' => $sJoinTable,
						'joinMainField' => $v ['sName'] 
				);
			}
		}
		
		$sSql = 'Select * From sys_table_join Where iMainTablePk=' . $iMainTablePk;
		$arrRecord = $this->getDb ()->select ( $sSql );
		$mapJoinTableUsed = array ();
		foreach ( $arrRecord as $v ) {
			$mapJoinTableUsed [$v ['sName']] = array (
					'pk' => $v ['iPk'] 
			);
		}
		
		$arrJoinTableNew = array_keys ( $mapJoinTableNew );
		$arrJoinTableUsed = array_keys ( $mapJoinTableUsed );
		
		$arrTableInsert = array_diff ( $arrJoinTableNew, $arrJoinTableUsed );
		$arrTableDelete = array_diff ( $mapJoinTableUsed, $mapJoinTableNew );
		
		foreach ( $arrTableInsert as $sTableName ) {
			$array = &$mapJoinTableNew [$sTableName];
			$sSql = 'Insert Into sys_table_join (iMainTablePk, sName, sJoin, sJoinMainField, sJoinWhere) Values(
                "' . $array ['mainTablePk'] . '",
                "' . $array ['joinTable'] . '",
                "Left",
                "' . $array ['joinMainField'] . '",
                "")';
			$this->getDb ()->query ( $sSql );
		}
		
		foreach ( $arrTableDelete as $sTableName ) {
			$array = &$mapJoinTableUsed [$sTableName];
			$sSql = 'Delete From sys_join_table Where iPk=' . $array ['pk'];
			$this->getDb ()->query ( $sSql );
		}
	}
	function save() {
		$iId = $_POST [KEY_FIELD_NAME];
		if ($iId == - 1) {
			$sTableName = $_POST ['sTableName'];
			$sSql = 'Select
                t.TABLE_NAME As sTable,
                c.COLUMN_NAME As sKeyField
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS t,
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS c
                WHERE t.TABLE_NAME = c.TABLE_NAME
                AND t.TABLE_SCHEMA = "'.DB_NAME.'"
                AND t.CONSTRAINT_TYPE = "PRIMARY KEY"
                And t.TABLE_NAME="' . $sTableName . '"';
			$arrRecord = $this->getDb ()->select ( $sSql );
			$sKeyField = $arrRecord [0] ['sKeyField'];
			
			$sSql = 'Select Group_Concat(COLUMN_NAME) as sColumnNames From information_schema.`COLUMNS` Where TABLE_SCHEMA="'.DB_NAME.'" And TABLE_NAME="' . $sTableName . '"';			
			$arrRecord = $this->getDb ()->select ( $sSql );
			$arrColumnName = explode(',', $arrRecord[0]['sColumnNames']);
			if(!in_array(USER_FIELD_NAME, $arrColumnName)){
				$sSql = 'ALTER TABLE `'.$sTableName.'` ADD COLUMN `'.USER_FIELD_NAME.'`  varchar(20) NULL;';
				$this->getDb ()->query ( $sSql );
			} 
			if(!in_array(TIME_FIELD_NAME, $arrColumnName)){
				$sSql = 'ALTER TABLE `'.$sTableName.'` ADD COLUMN `'.TIME_FIELD_NAME.'`  datetime NULL;';
				$this->getDb ()->query ( $sSql );
			}			
			
			$sSql = 'Insert Into sys_table (sName, sKeyField) Values ("' . $sTableName . '", "' . $sKeyField . '")';
			$this->getDb ()->query ( $sSql );
		} else {
			$arrFieldValue = $_POST ['arrField'];
			$arrPk = $arrFieldValue ['iPk'];
			unset ( $arrFieldValue ['iPk'] );			
			
			$sSql = 'Update sys_table Set sSnPre="'.$_POST['sSnPre'].'" Where iPk="'.$iId.'"';
			$this->getDb ()->query ( $sSql );
			
			foreach ( $arrPk as $k => $iPk ) {
				$arrSqlSet = array ();
				foreach ( $arrFieldValue as $sField => $arrValue ) {
					$arrSqlSet [] = $sField . '="' . addslashes ( $arrValue [$k] ) . '"';
				}
				if ($iPk == '') {
					$sSql = 'Insert Into sys_table_field Set ' . implode ( ',', $arrSqlSet );
				} else {
					$sSql = 'Update sys_table_field Set ' . implode ( ',', $arrSqlSet ) . ' Where iPk=' . $iPk;
				}
				$this->getDb ()->query ( $sSql );
				//$this->updateJoinTable ( $arrFieldValue ['iTablePk'] [0], $sJoinTableName, $sJoinMainField );
				$this->updateJoinTable ( $arrFieldValue ['iTablePk'] [0]);
			}			
		}
		$this->setTableStruct ( array () );
		$this->setAppStruct ( array () );
		$this->setAppSql ( array () );
		return true;
	}

	/**
	 * 删除sys_table的同时，清空关联的sys_table_field中的数据
	 * @param $iId
	 * @return bool
	 */
	function beforeDelete($iId)
	{
		$sSql = "Delete From sys_table_field Where iTablePk='$iId'";
		$this->getDb()->query($sSql);
		return true;
	}


}

?>
