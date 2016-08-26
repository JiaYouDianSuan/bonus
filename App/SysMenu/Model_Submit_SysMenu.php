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
class Model_Submit_SysMenu extends Model_Submit {

    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
    }

    function beforeFormSave($iId) {
        $iRelTablePk = $_POST['iTablePk'];
        $sSql = 'Select count(iPk) As iCount From sys_menu Where iTablePk="' . $iRelTablePk . '"';
        $arrRecord = $this->getDb()->select($sSql);
        if ($arrRecord[0]['iCount'] > 0 && $iId=='-1') {
            return array('status' => 'error', 'message' => '改关联数据库表已存在，不允许重复选择！');
        }

        $arrCheckButtonCode = empty($_POST['checkButtonCode']) ? array() : $_POST['checkButtonCode'];
        $sCheckButtonCode = implode(',', $arrCheckButtonCode);
        $_POST['sButton'] = $sCheckButtonCode;
        $this->unsetField(array('checkButtonCode'));
        
        return true;
    }
    
    function afterFormSave($iId){
    	$sButton = $_POST['sButton'];
    	$iTablePk = $_POST['iTablePk'];
    	$sApp = $_POST['sApp'];    	
    	$mapAppStruct = $this->getAppStruct($sApp);    	
    	$sTableName = $mapAppStruct['table']['name'];
    	    	    	    				
		$arrButton = explode(',', $sButton);
		if(in_array('Confirm', $arrButton)){									
			$sSql = 'Select Group_Concat(COLUMN_NAME) as sColumnNames From information_schema.`COLUMNS` Where TABLE_SCHEMA="'.DB_NAME.'" And TABLE_NAME="' . $sTableName . '"';
			$arrRecord = $this->getDb ()->select ( $sSql );
			$arrColumnName = explode(',', $arrRecord[0]['sColumnNames']);
			if(!in_array(CONFIRM_FIELD_NAME, $arrColumnName)){
				$sSql = 'ALTER TABLE `'.$sTableName.'` ADD COLUMN `'.CONFIRM_FIELD_NAME.'` int(2) DEFAULT 0 NULL;';
				$this->getDb ()->query ( $sSql );
			}
			if(!in_array(CONFIRM_USER_FIELD_NAME, $arrColumnName)){
				$sSql = 'ALTER TABLE `'.$sTableName.'` ADD COLUMN `'.CONFIRM_USER_FIELD_NAME.'`  varchar(20) NULL;';
				$this->getDb ()->query ( $sSql );
			}
			if(!in_array(CONFIRM_TIME_FIELD_NAME, $arrColumnName)){
				$sSql = 'ALTER TABLE `'.$sTableName.'` ADD COLUMN `'.CONFIRM_TIME_FIELD_NAME.'`  varchar(20) NULL;';
				$this->getDb ()->query ( $sSql );
			}			
		}
		$this->setTableStruct ( array () );
		$this->setAppStruct ( array () );
		$this->setAppSql ( array () );
		$this->getRegistor()->set('button', array());
    }

}

?>
