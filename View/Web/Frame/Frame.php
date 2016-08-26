<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Frame
 *
 * @author jinlee
 */
class Frame {

    function __construct() {
        
    }

    static function getLoginInfo() {
        return $_SESSION['login'];
    }
    
    static function getAuthorityInfo(){
    	return Registor::create()->get('authority');
    }

    function setAuthority($arrAuthority) {
        $this->getRegistor()->set('authority', $arrAuthority);
    }

    function getAuthority() {
        return $this->getRegistor()->get('authority');
    }

    function show() {
        $oMenu = new Frame_Menu();
        $oContainer = new Frame_Container();

        $sHtml = $oMenu->show() . $oContainer->show();
        return $sHtml;
    }

    function getDb() {
        return Database::create();
    }

    function getRegistor() {
        return Registor::create();
    }

    function checkLogin() {
        if ($_SESSION['login']['status'] != true) {
            header('Location:Login.php');
        }
    }

    function login($sId, $sPassword) {    	
        $sSql = 'Select sName From sys_user Where sId="' . $sId . '" And md5(concat(sPassword,"'.$_SESSION['code'].'"))="'.$sPassword.'"';
        $arrRecord = $this->getDb()->select($sSql);
        if(count($arrRecord)!=0){
        	$_SESSION['login'] = array(
        		'status' => true,
        		'id' => $sId,
        		'sPassword' => $sPassword,
        		'name' => $arrRecord[0]['sName']
        	);
        	$this->createAuthority();
        	return true;        	
        }else {
        	return false;            
        }
    }

    function logout() {
        //print_R($_SESSION);
        session_destroy();
        /*$_SESSION['login'] = array(
            'status' => false
        );*/
        $this->destoryAuthority();
        header('Location:Index.php');
    }
    
    function checkPassword($sPassword){
    	$sId = $_SESSION['login']['id'];
    	$sSql = 'Select count(iPk) As iCount From sys_user Where sId="'.$sId.'" And sPassword="'.md5($sPassword).'"';
    	$arrRecord = $this->getDb()->select($sSql);
    	if($arrRecord[0]['iCount'] == 0){
    		$arrReturn = array(
    			"statusCode" => '300',
    			"message" => '密码错误！',
    			"navTabId" => "",
    			"rel" => "",
    			"callbackType" => "",
    			"forwardUrl" => "",
    			"confirmMsg" => ""
    		);
    		return json_encode($arrReturn);
    	}
    	return true;
    	
    }
    function modifyPassword($sPassword){
    	$sId = $_SESSION['login']['id'];
    	$sSql = 'Update sys_user Set sPassword="'.md5($sPassword).'" Where sId="'.$sId.'"';
    	$this->getDb()->query($sSql);
    	$arrReturn = array(
    		"statusCode" => '200',
    		"message" => '密码修改成功！',
    		"navTabId" => "",
    		"rel" => "",
    		"callbackType" => "",
    		"forwardUrl" => "",
    		"confirmMsg" => ""
    	);
    	return json_encode($arrReturn);
    }

    function createAuthority() {
        $arrAuthority = $this->getAuthority();        
        if (empty($arrAuthority)) {        	
            $arrRolePk = array();
            $sId = $_SESSION['login']['id'];
            $sSql = 'Select iRolePk, iGrpPk From sys_user Where sId="' . $sId . '"';
            $arrRecord = $this->getDb()->select($sSql);
            $iGrpPk = $arrRecord[0]['iGrpPk'];
            !empty($arrRecord[0]['iRolePk']) && $arrRolePk[] = $arrRecord[0]['iRolePk'];

            if ($iGrpPk != '') {
                $sSql = 'Select iRolePk From sys_user_grp Where iPk="' . $iGrpPk . '"';
                $arrRecord = $this->getDb()->select($sSql);
                !empty($arrRecord[0]['iRolePk']) && $arrRolePk[] = $arrRecord[0]['iRolePk'];
            }

            $arrAuthority = array();
            $arrRolePk = array_unique($arrRolePk);            
            foreach ($arrRolePk as $v) {
                $sSql = 'Select sAuthorityCodes From sys_role Where iPk="' . $v . '"';
                $arrRecode = $this->getDb()->select($sSql);
                $sAuthorityCodes = $arrRecode[0]['sAuthorityCodes'];
                if ($sAuthorityCodes == 'All') {
                    $arrAuthority = 'All';
                    break;
                } else {
                    $arrTemp = json_decode($sAuthorityCodes, true);
                    $arrAuthority = array_merge_recursive($arrAuthority, $arrTemp);
                }
            }          
            if($arrAuthority != 'All'){
            	$arrAuthority['Authority'] = array_map('array_unique', $arrAuthority['Authority']);
            	foreach($arrAuthority['Range']['read'] as $k => $v){
            		is_array($v)&&$arrAuthority['Range']['read'][$k] = in_array('All', $v)?'All':'Self';            			            	         
            	}
            	foreach($arrAuthority['Range']['edit'] as $k => $v){
            		is_array($v)&&$arrAuthority['Range']['edit'][$k] = in_array('All', $v)?'All':'Self';
            	}
            }        
            $this->setAuthority($arrAuthority);            
        }
    }

    function destoryAuthority() {
        $this->setAuthority(array());
    }

}

?>
