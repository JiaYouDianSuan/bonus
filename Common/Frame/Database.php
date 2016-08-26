<?PHP
class Database {
	static $_oInstance = false;
	private $_sServerName = NULL;
	private $_sUserName = NULL;
	private $_sUserPass = NULL;
	private $_sDataBase = NULL;
	private $_oConn = NULL;
	private function __consuct() {
	}
	static function create() {
		if (self::$_oInstance === false) {
			self::$_oInstance = new self ();
			self::$_oInstance->connect ( DB_SERVER, DB_USER, DB_PASS, DB_NAME );
			self::$_oInstance->query ( 'set names utf8' );
		}
		return self::$_oInstance;
	}
	function setServerName($sServerName) {
		$this->_sServerName = $sServerName;
	}
	function getServerName() {
		return $this->_sServerName;
	}
	function setUserName($sUserName) {
		$this->_sUserName = $sUserName;
	}
	function getUserName() {
		return $this->_sUserName;
	}
	function setUserPass($sUserPass) {
		$this->_sUserPass = $sUserPass;
	}
	function getUserPass() {
		return $this->_sUserPass;
	}
	function setDataBase($sDataBase) {
		$this->_sDataBase = $sDataBase;
	}
	function getDataBase() {
		return $this->_sDataBase;
	}
	function getConn() {
		return $this->_oConn;
	}
	function connect($sServerName, $sUserName, $sUserPass, $sDataBase) {
		$sServerName != '' && $this->setServerName ( $sServerName );
		$sUserName != '' && $this->setUserName ( $sUserName );
		$sUserPass != '' && $this->setUserPass ( $sUserPass );
		$sDataBase != '' && $this->setDataBase ( $sDataBase );
		
		$oConn = mysql_connect ( $sServerName, $sUserName, $sUserPass );
		if ($oConn === false) {
			$this->_oConn = NULL;
			die ( 'Could not connect: ' . mysql_error () );
		}
		if (mysql_select_db ( $sDataBase, $oConn ) === false) {
			die ( 'Could not select database:' . mysql_error () );
		}
		$this->_oConn = $oConn;
		// self::onQuery('set names utf8');
	}
	function insert($sSql) {
		$this->query ( $sSql );
		return mysql_insert_id ();
	}
	function select($sSql,$iResultType=MYSQL_ASSOC) {
		$arrRecord = array ();
		$oRecord = $this->query ( $sSql );
		while ( $row = mysql_fetch_array ( $oRecord, $iResultType ) ) {
			$arrRecord [] = $row;
		}
		return $arrRecord;
	}
	function selectWithPage($sSql, $arrPage) {
	}
	function query($sSql) {
		$oRecord = mysql_query ( $sSql, $this->_oConn );
		if ($oRecord === false) {
			die ( "Invalid query: " . mysql_error () . '</br>' . $sSql );
		}
		return $oRecord;
	}
}

?>