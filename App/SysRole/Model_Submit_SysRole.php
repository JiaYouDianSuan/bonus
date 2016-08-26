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
class Model_Submit_SysRole extends Model_Submit {

    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
    }
    
    function beforeFormSave($iId) {
        $mapAppStruct = $this->getAppStruct($this->getApp());        
        if ($mapAppStruct['table']['name'] == 'sys_role' && $_POST['sAuthorityCodes'] == 'All') {//系统管理员角色不允许修改
            return array('status' => 'error', 'message' => '系统管理员角色不允许修改！');
        }              
        $arrAuthority = array(
            'Authority'=>empty($_POST['authority'])?array():$_POST['authority'],
            'Range'=>array(
                'read'=>$_POST['authority_range_read'],
                'edit'=>$_POST['authority_range_edit'],
            )
        );
        
        $_POST['sAuthorityCodes'] = json_encode($arrAuthority);     
        $this->unsetField(array('authority'));
        $this->unsetField(array('authority_range_read'));
        $this->unsetField(array('authority_range_edit'));
        return true;
    }  

    function beforeDelete($iId) {
        $sSql = 'Select sAuthorityCodes From sys_role Where iPk="' . $iId . '"';
        $arrRecord = $this->getDb()->select($sSql);
        if ($arrRecord[0]['sAuthorityCodes'] == 'All') {
            return array('status' => 'error', 'message' => '系统管理员角色不允许删除！');
        }
        return true;
    }

}

?>
