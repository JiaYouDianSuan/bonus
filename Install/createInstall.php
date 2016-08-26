<?php
require_once('../Init.php');

function createTableSql($sTable){
	$sSql = 'Show Create Table '.$sTable;
	$arrRecord = Database::create()->select($sSql);
	$sCreateTable = $arrRecord[0]['Create Table'].';';
	$sDropTable = 'DROP TABLE if exists `'.$sTable.'`;'."\n";
	return $sDropTable.$sCreateTable;
}

function createDataSql($sTable,$arrField){
	$iStart = PRIVATE_AUTO_INCREMENT + 1;
	$sSql = 'Select '.implode(',', $arrField).' From '.$sTable.' Where '.$arrField[0].'<'.PRIVATE_AUTO_INCREMENT.' Order By '.$arrField[0];
	$arrRecord = Database::create()->select($sSql);
		
	$arrValue = array();
	foreach($arrRecord as $arrRow){		
		$arrValue[] = "('".implode("','", $arrRow)."')";	
	}	
	$sSqlInsert = 'Insert Into '.$sTable. ' ('.implode(',', $arrField).') values '.implode(',', $arrValue).';';
	$sSql = 'TRUNCATE TABLE `'.$sTable.'`;'."\n";
	$sSql .= $sSqlInsert."\n";
	$sSql .= 'ALTER TABLE `'.$sTable.'` AUTO_INCREMENT='.$iStart.';';
	return $sSql;   
}

$sImportSql = '';
$sSql = 'Select TABLE_NAME From INFORMATION_SCHEMA.TABLE_CONSTRAINTS Where TABLE_SCHEMA="'.DB_NAME.'" And TABLE_NAME Like "sys_%"';
$arrRecord = Database::create()->select($sSql);
foreach($arrRecord as $v){
	$sImportSql .= createTableSql($v['TABLE_NAME'])."\n";	
}
file_put_contents('importTable.sql', $sImportSql);

$sImportSql = '';
$arrTableStruct = array(
	'sys_table'=>array('iPk', 'sName', 'sKeyField'),
	'sys_table_field'=>array('iTablePk', 'sTable', 'sName', 'sDisplay', 'sType', 'bRequired', 'bDisplay', 'bSearch', 'iSort', 'sJoinField', 'sComboxData'),
	'sys_table_join'=>array('iPk', 'iMainTablePk', 'sName', 'sJoin', 'sJoinMainField', 'sJoinWhere'),
	'sys_menu_class'=>array('iPk', 'sName'),
	'sys_menu'=>array('iPk', 'iClassPk', 'sName', 'sApp', 'sPage', 'iTablePk', 'sButton', 'bDisplay', 'iSort'),	
	'sys_button'=>array('iPk','sCode', 'sName', 'sImage', 'sType', 'sTarget','iSort'),
	'sys_role'=>array('iPk', 'sName', 'sMemo', 'sAuthorityCodes'),
	'sys_user'=>array('iPk', 'iGrpPk', 'iRolePk', 'sId', 'sPassword', 'sName', 'sRemark'),
	'sys_user_grp'=>array('iPk', 'iParentPk', 'iRolePk', 'sName', 'sRemark', 'iLftVal', 'iRgtVal')
);
foreach($arrTableStruct as $k => $v){
	$sImportSql .=createDataSql($k,$v)."\n";	
}
$sImportSql .= "Insert Into `sys_menu_class` (`sName`) Values ('业务');\n";
file_put_contents('importData.sql', $sImportSql);
?>