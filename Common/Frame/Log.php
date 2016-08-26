<?php
define ( 'OPERATION_INSERT', 1 );
define ( 'OPERATION_UPDATE', 2 );
define ( 'OPERATION_DELETE', 3 );
class Log {
	static $_oInstance = false;
	protected $_mapLogTableInfo = array ();
	protected $_arrUpdatePre = array ();
	function __construct() {
		$this->createLogTableInfo ();
	}
	static function create() {
		if (self::$_oInstance === false) {
			self::$_oInstance = new self ();
		}
		return self::$_oInstance;
	}
	function getDb() {
		return Database::create ();
	}
	function getLogTableInfo() {
		return $this->_mapLogTableInfo;
	}
	function setLogTableInfo($mapLogTableInfo) {
		$this->_mapLogTableInfo = $mapLogTableInfo;
	}
	function createLogTableInfo() {
		$mapLogTableInfo = array ();
		$sSql = 'Select * From sys_log_table';
		$arrRs = $this->getDb ()->select ( $sSql );
		foreach ( $arrRs as $v ) {
			$mapLogTableInfo [$v ['sTableName']] = $v;
		}
		$this->setLogTableInfo ( $mapLogTableInfo );
	}
	function inTableInfo($sTable) {
		$arrTable = array_keys ( $this->getLogTableInfo () );
		return in_array ( $sTable, $arrTable );
	}
	function addLogTableInfo($sTable) {		
		$sSql = 'Select count(*) As iCount From sys_log_table Where sTableName="' . $sTable . '"';
		$arrRs = $this->getDb ()->select ( $sSql );
		if ($arrRs [0] ['iCount'] == 0) {
			$sSql = 'Select COLUMN_NAME as sKeyField From INFORMATION_SCHEMA.KEY_COLUMN_USAGE where TABLE_NAME="' . $sTable . '"';
			$arrRs = $this->getDb ()->select ( $sSql );
			$sKeyField = $arrRs [0] ['sKeyField'];
			
			$sSql = 'Select SYSMENU.sApp, SYSMENU.sName From sys_menu As SYSMENU Left Join sys_table As SYSTABLE On SYSTABLE.iPk=SYSMENU.iTablePk Where SYSTABLE.sName="' . $sTable . '"';
			$arrRs = $this->getDb ()->select ( $sSql );
			$sApp = $arrRs [0] ['sApp'];
			
			$sSql = 'Insert Into sys_log_table (sTableName, sKeyField, sApp) Values ("' . $sTable . '", "' . $sKeyField . '", "' . $sApp . '" )';
			$this->getDb ()->insert ( $sSql );
			$this->createLogTableInfo ();
		}
	}
	function createInsertLog($sTable, $sKeyValue) {
		! $this->inTableInfo ( $sTable ) && $this->addLogTableInfo ( $sTable );
		$mapLogTableInfo = $this->getLogTableInfo ();
		$iLogTablePk = $mapLogTableInfo [$sTable] ['iPk'];
		$sKeyField = $mapLogTableInfo [$sTable] ['sKeyField'];
		
		$sSql = 'Select * From ' . $sTable . ' Where ' . $sKeyField . '="' . $sKeyValue . '"';
		$arrRs = $this->getDb ()->select ( $sSql );
		$sJsonContent = addslashes ( json_encode ( $arrRs ) );
		$sCreateUser = $_SESSION ['login'] ['id'];
		
		$sSql = 'Insert Into sys_log_row (iLogTablePk, skeyValue, iOperation, sJsonContent, sCreateUser, dtCreateTime) Values ( "' . $iLogTablePk . '", "' . $sKeyValue . '", ' . OPERATION_INSERT . ', "' . $sJsonContent . '", "' . $sCreateUser . '", now() )';
		$this->getDb ()->insert ( $sSql );
	}
	function createDeleteLog($sTable, $sKeyValue) {
		! $this->inTableInfo ( $sTable ) && $this->addLogTableInfo ( $sTable );
		$mapLogTableInfo = $this->getLogTableInfo ();
		$iLogTablePk = $mapLogTableInfo [$sTable] ['iPk'];
		$sKeyField = $mapLogTableInfo [$sTable] ['sKeyField'];
		
		$sSql = 'Select * From ' . $sTable . ' Where ' . $sKeyField . '="' . $sKeyValue . '"';
		$arrRs = $this->getDb ()->select ( $sSql );
		$sJsonContent = addslashes ( json_encode ( $arrRs ) );
		$sCreateUser = $_SESSION ['login'] ['id'];
		
		$sSql = 'Insert Into sys_log_row (iLogTablePk, skeyValue, iOperation, sJsonContent, sCreateUser, dtCreateTime) Values ( "' . $iLogTablePk . '", "' . $sKeyValue . '", ' . OPERATION_DELETE . ', "' . $sJsonContent . '", "' . $sCreateUser . '", now() )';
		$this->getDb ()->insert ( $sSql );
	}
	function createUpdateLogStart($sTable, $sKeyValue) {
		! $this->inTableInfo ( $sTable ) && $this->addLogTableInfo ( $sTable );
		$mapLogTableInfo = $this->getLogTableInfo ();
		$iLogTablePk = $mapLogTableInfo [$sTable] ['iPk'];
		$sKeyField = $mapLogTableInfo [$sTable] ['sKeyField'];
		
		$sSql = 'Select * From ' . $sTable . ' Where ' . $sKeyField . '="' . $sKeyValue . '"';
		$arrRs = $this->getDb ()->select ( $sSql );
		$this->_arrUpdatePre = $arrRs [0];
	}
	function createUpdateLogEnd($sTable, $sKeyValue) {
		! $this->inTableInfo ( $sTable ) && $this->addLogTableInfo ( $sTable );
		$mapLogTableInfo = $this->getLogTableInfo ();
		$iLogTablePk = $mapLogTableInfo [$sTable] ['iPk'];
		$sKeyField = $mapLogTableInfo [$sTable] ['sKeyField'];
		
		$sSql = 'Select * From ' . $sTable . ' Where ' . $sKeyField . '="' . $sKeyValue . '"';
		$arrRs = $this->getDb ()->select ( $sSql );
		
		$arrDiff = array_diff_assoc ( $this->_arrUpdatePre, $arrRs [0] );
		if (! empty ( $arrDiff )) {
			$sJsonContent = addslashes ( json_encode ( array (
				'pre' => $this->_arrUpdatePre,
				'after' => $arrRs [0] 
			) ) );
			$sCreateUser = $_SESSION ['login'] ['id'];
			$sSql = 'Insert Into sys_log_row (iLogTablePk, skeyValue, iOperation, sJsonContent, sCreateUser, dtCreateTime) Values ( "' . $iLogTablePk . '", "' . $sKeyValue . '", ' . OPERATION_UPDATE . ', "' . $sJsonContent . '", "' . $sCreateUser . '", now() )';
			$this->getDb ()->insert ( $sSql );
		}
	}
}

?>