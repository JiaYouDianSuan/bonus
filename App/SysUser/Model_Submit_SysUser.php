<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Submit
 *
 * @author jinlee
 */
class Model_Submit_SysUser extends Model_Submit {

    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
    }

    function beforeDelete($iId) {
        $sSql = 'Select sId From sys_user Where iPk="' . $iId . '"';
        $arrRecord = $this->getDb()->select($sSql);
        if ($arrRecord[0]['sId'] == 'admin') {
            return array('status' => 'error', 'message' => '系统管理员不允许删除！');
        }
        return true;
    }

}

?>
