<?php

session_start();
if (!defined('JFRAME_WWW_ROOT')) {
    $sDocumentDir = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $sJFrameDir = str_replace('\\', '/', dirname(__FILE__));    
    $sJFrameRoot = str_replace($sDocumentDir, '', $sJFrameDir);
    define('JFRAME_WWW_ROOT', $sJFrameRoot . '/');
}
//echo JFRAME_WWW_ROOT;

if (!defined('JFRAME_DISK_ROOT')) {
    define('JFRAME_DISK_ROOT', dirname(__FILE__) . '/');
    require(JFRAME_DISK_ROOT . 'Common/Frame/AutoLoader.php');
}

AutoLoader::Register('Common');
AutoLoader::Register('Common_Frame');
AutoLoader::Register('Model');
AutoLoader::Register('View');
AutoLoader::Register('App');
AutoLoader::Register('Business');

/*define('DB_SERVER', '10.10.16.62');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bonus');*/

define('DB_SERVER', '10.10.16.44');
define('DB_USER', 'jinli');
define('DB_PASS', 'jinli123');
define('DB_NAME', 'bonus');

define('KEY_FIELD_NAME', 'MAINTABLE_PK');
define('OPT_FIELD_NAME', 'TABLE_OPERATION');

define('USER_FIELD_NAME', 'sSysUser');
define('TIME_FIELD_NAME', 'dtSysTime');

define('CONFIRM_FIELD_NAME', 'iSysConfirmFlag');
define('CONFIRM_USER_FIELD_NAME', 'sSysConfirmUser');
define('CONFIRM_TIME_FIELD_NAME', 'dtSysConfirmTime');

define('DELETE_FIELD_NAME', 'iSysDelete');

define('TREE_NODE_FIELD_NAME', 'TREE_NODE_PK');
define('SN_FIELD_VALUE', 'SN_FIELD');
define('SN_DIGIT_COUNT', 4);
define('PRIVATE_AUTO_INCREMENT', 100);

define('PASSWORD_MIN_LENGTH', 6);
define('PASSWORD_MAX_LENGTH', 20);
define('DEFAULT_PASSWORD', '111111');


define('NUM_PER_PAGE', 20);
?>
