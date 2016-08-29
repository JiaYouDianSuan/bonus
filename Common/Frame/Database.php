<?PHP

class Database
{
    static $_oInstance = false;
    protected $_sDBServer = NULL;
    protected $_sDBUser = NULL;
    protected $_sDBPass = NULL;
    protected $_sDBName = NULL;
    protected $_oConn = NULL;

    public function __construct()
    {
        $this->setServerInfo();
    }

    static function create()
    {
        if (self::$_oInstance === false) {
            self::$_oInstance = new self ();
            self::$_oInstance->connect();
            //self::$_oInstance->query('set names utf8');
        }
        return self::$_oInstance;
    }

    function setServerInfo(){
        $this->_sDBServer = DB_SERVER;
        $this->_sDBUser = DB_USER;
        $this->_sDBPass = DB_PASS;
        $this->_sDBName = DB_NAME;
    }

    function getConn()
    {
        return $this->_oConn;
    }

    function connect()
    {
        $oConn = mysql_connect($this->_sDBServer,$this->_sDBUser,$this->_sDBPass);
        if ($oConn === false) {
            $this->_oConn = NULL;
            die ('Could not connect: ' . mysql_error());
        }
        if (mysql_select_db($this->_sDBName, $oConn) === false) {
            die ('Could not select database:' . mysql_error());
        }
        $this->_oConn = $oConn;
        $this->query('set names utf8');
        // self::onQuery('set names utf8');
    }

    function insert($sSql)
    {
        $this->query($sSql);
        return mysql_insert_id();
    }

    function select($sSql, $iResultType = MYSQL_ASSOC)
    {
        $arrRecord = array();
        $oRecord = $this->query($sSql);
        while ($row = mysql_fetch_array($oRecord, $iResultType)) {
            $arrRecord [] = $row;
        }
        return $arrRecord;
    }

    function selectWithPage($sSql, $arrPage)
    {
    }

    function query($sSql)
    {
        $oRecord = mysql_query($sSql, $this->_oConn);
        if ($oRecord === false) {
            die ("Invalid query: " . mysql_error() . '</br>' . $sSql);
        }
        return $oRecord;
    }
}

?>