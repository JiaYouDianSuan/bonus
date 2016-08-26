<?php
require_once ('../Init.php');
function import($sSql) {
	$arrSql = explode ( ";\n", $sSql );
	foreach ( $arrSql as $v ) {
		$sSql = trim ( $v );
		if ($sSql != '') {
			Database::create ()->query ( $sSql );
		}
	}
}

$sSql = 'create database if not exists `' . DB_NAME . '`';
$oConn = mysql_connect ( DB_SERVER, DB_USER, DB_PASS );
if ($oConn === false) {
	$this->_oConn = NULL;
	die ( 'Could not connect: ' . mysql_error () );
}

if (mysql_query ( $sSql, $oConn )) {
	if (mysql_select_db ( DB_NAME, $oConn ) === false) {
		die ( 'Could not select database:' . mysql_error () );
	}
} else {
	echo "创建数据库出错，错误号：" . mysql_errno () . " 错误原因：" . mysql_error ();
}

$sImportTableSql = file_get_contents ( 'importTable.sql' );
import ( $sImportTableSql );

$sImportDataSql = file_get_contents ( 'importData.sql' );
import ( $sImportDataSql );

?>