<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author jinlee
 */
class Model {
    protected $_sApp;
    protected $_mapTableStruct;
    protected $_mapAppStruct;
    protected $_arrAppSql;

    function __construct($sModel, $sApp) {        
        $this->setApp($sApp);
        $this->createTableStruct();
        $this->createAppStruct();
        $this->createAppSql();
    }

    static function create($sModel, $sApp) {
        return Factory::create(__CLASS__, $sModel, $sApp);
    }

    function getApp() {
        return $this->_sApp;
    }

    function setApp($sApp) {        
        $this->_sApp = $sApp;
    }

    function getTableStruct() {
        //return $this->_mapTableStruct;
        return $this->getRegistor()->get('tableStruct');
    }

    function setTableStruct($mapTableStruct) {
        //$this->_mapTableStruct = $mapTableStruct;
        $this->getRegistor()->set('tableStruct', $mapTableStruct);
    }

    function getAppStruct($sApp = '') {        
        //$mapAppStruct = $this->_mapAppStruct;
        $mapAppStruct = $this->getRegistor()->get('appStruct');                
        return $sApp == '' ? $mapAppStruct : $mapAppStruct[$sApp];
    }

    function setAppStruct($mapAppStruct) {
        //$this->_mapAppStruct = $mapAppStruct;
        $this->getRegistor()->set('appStruct', $mapAppStruct);
    }

    function getAppSql($sApp = '') {
        //$arrAppSql = $this->_arrAppSql;
        $arrAppSql = $this->getRegistor()->get('appSql');
        return $sApp == '' ? $arrAppSql : $arrAppSql[$sApp];
    }

    function setAppSql($arrAppSql) {
        //$this->_arrAppSql = $arrAppSql;
        $this->getRegistor()->set('appSql', $arrAppSql);
    }

    function getDb() {
        return Database::create();
    }

    function getRegistor() {
        return Registor::create();
    }

    function createTableStruct() {
        $mapTableStruct = $this->getTableStruct();
        //$mapTableStruct = array(); //调试，正式发布取消。
        if (empty($mapTableStruct)) {
            $sSql = 'Show Tables';
            $arrTable = $this->getDb()->select($sSql);
            foreach ($arrTable as $table) {
                $sTableName = array_pop($table);
                //if (substr($sTableName, 0, 3) != 'sys') {
                $mapTableStruct[$sTableName] = array();
                $sSql = 'Desc ' . $sTableName;
                $arrField = $this->getDb()->select($sSql);
                foreach ($arrField as $field) {
                    if ($field['Key'] == 'PRI') {
                        $mapTableStruct[$sTableName]['key'] = $field['Field'];
                    }
                    $mapTableStruct[$sTableName]['field'][$field['Field']] = array('name' => $field['Field']);
                }
                //}
            }
            $this->setTableStruct($mapTableStruct);
        }
    }

    function createAppStruct() {
        $mapTableStruct = $this->getTableStruct();
        $mapAppStruct = $this->getAppStruct();
        $mapRealTableField = array();
        $mapMainApp = array();        
        //$mapAppStruct = array(); //调试，正式发布取消。
        if (empty($mapAppStruct)) {        	
            $sSql = 'Select MENU.sApp, MENU.sAppDetail, MENU.sPage, TAB.sName, TAB.sKeyField From sys_menu As MENU Left Join sys_table As TAB On TAB.iPk=MENU.iTablePk';
            $arrTable = $this->getDb()->select($sSql);
            foreach ($arrTable as $v) {
            	if(empty($v['sName']))continue;
                $sSql = "SHOW TABLES LIKE '".$v['sName']."'";
                $arrResults = $this->getDb()->select($sSql);
                if(empty($arrResults))continue;

                $sSql = 'Desc '.$v['sName'];
                $arrRecord = $this->getDb()->select($sSql);
                foreach($arrRecord as $arrField){
                    $mapRealTableField[$v['sName']][] = $arrField['Field'];
                }
                
                $mapAppStruct[$v['sApp']]['table'] = array(
                    'page' => $v['sPage'],
                    'name' => $v['sName'],
                    'keyField' => $v['sKeyField']
                );
                if ($v['sAppDetail'] != '') {
                    $mapMainApp[$v['sAppDetail']] = $v['sApp'];
                    $sSql = 'Select TAB.sName From sys_menu As MENU Left Join sys_table As TAB On TAB.iPk=MENU.iTablePk Where MENU.sApp="' . $v['sAppDetail'] . '"';
                    $arrTableDetail = $this->getDb()->select($sSql);
                    $sDetailTable = $arrTableDetail[0]['sName'];
                    $sMainTable = $v['sName'];

                    $sSql = 'Select sName From sys_table_field Where sTable="' . $sDetailTable . '" And sJoinField like "' . $sMainTable . '.%"';
                    $arrRecord = $this->getDb()->select($sSql);
                    $sDetailField = $arrRecord[0]['sName'];

                    $mapAppStruct[$v['sApp']]['app'] = array(
                        'detail' => $v['sAppDetail'],
                        'detailField' => $sDetailField
                    );
                }
            }            
            //回写app的父关系
            foreach($mapMainApp as $detailApp => $mainApp){
                $mapAppStruct[$detailApp]['app']['main'] = $mainApp;
            }

            $sSql = 'Select 
                MENU.sApp, 
                FIELD.sName,
                FIELD.sDisplay,
                FIELD.sType,
                FIELD.bRequired,
                FIELD.bSearch,
                FIELD.bDisplay,            	
                FIELD.sJoinField,
            	FIELD.iWidth,
                FIELD.sComboxData
                From sys_menu As MENU 
                Left Join sys_table_field As FIELD 
                On FIELD.iTablePk=MENU.iTablePk                 
                Order By FIELD.iSort';
            $arrField = $this->getDb()->select($sSql);

            foreach ($arrField as $v) {                                
                $sMainTable = $mapAppStruct[$v['sApp']]['table']['name'];

                if(empty($mapRealTableField[$sMainTable])|| !in_array($v['sName'], $mapRealTableField[$sMainTable])) continue;//过滤掉实体表没有的字段
                if($v['sName'] == CONFIRM_FIELD_NAME && empty($v['sComboxData'])) $v['sComboxData'] = '{"0":"未审核","1":"已审核"}';
                $arrTemp = array(
                    'name' => $v['sName'],
                    'label' => $v['sDisplay'],
                    'type' => $v['sType'],
                    'required' => (bool) $v['bRequired'],
                    'search' => (bool) $v['bSearch'],
                    'display' => (bool) $v['bDisplay'],
                	'width' => $v['iWidth'],                	
                    'mainField' => $sMainTable . '.' . $v['sName'],
                    'joinField' => $v['sJoinField'],
                    'comboxData' => json_decode($v['sComboxData'], true)
                );

                switch ($v['sType']) {
                    case 'Relate':
                        if ($v['sJoinField'] != '') {
                            $arrTemp['name'] = str_replace('.', '_', $v['sJoinField']);
                        }
                        break;
                    case 'Checkbox';
                        if ($v['sJoinField'] != '') {
                            $arr = explode('.', $v['sJoinField']);
                            $sTable = $arr[0];
                            $sDisplayField = $arr[1];
                            $sKeyField = $mapTableStruct[$sTable]['key'];
                            $sSql = 'Select ' . $sKeyField . ' As keyField, ' . $sDisplayField . ' As displayField From ' . $sTable;
                            $arrRecord = $this->getDb()->select($sSql);
                            foreach ($arrRecord as $arrRow) {
                                $arrMap[$arrRow['keyField']] = $arrRow['displayField'];
                            }
                            $arrTemp['checkboxData'] = $arrMap;
                        }
                        break;
                }

                $mapAppStruct[$v['sApp']]['field'][] = $arrTemp;
            }

            $sSql = 'Select 
                MENU.sApp, 
                TABJOIN.sName,
                TABJOIN.sJoin,
                TABJOIN.sJoinMainField,
                TABJOIN.sJoinWhere
                From sys_menu As MENU Left Join sys_table_join As TABJOIN On TABJOIN.iMainTablePk=MENU.iTablePk';
            $arrTableJoin = $this->getDb()->select($sSql);
            foreach ($arrTableJoin as $v) {
                $mapAppStruct[$v['sApp']]['tableJoin'][] = array(
                    'table' => $v['sName'],
                    'field' => $v['sJoinMainField'],
                    'where' => $v['sJoinWhere'],
                    'join' => $v['sJoin']
                );
            }
            $this->setAppStruct($mapAppStruct);
        }
    }

    function createAppSql() {        
        $sApp = $this->getApp();
        $mapAppStuct = $this->getAppStruct();
        $mapTableStruct = $this->getTableStruct();
        $arrAppSql = $this->getAppSql();
        //$arrAppSql = array(); //调试，正式发布取消。

        if (empty($arrAppSql[$sApp]) && !empty($mapAppStuct[$sApp]['table'])) {
            $arrMainTable = $mapAppStuct[$sApp]['table'];
            $arrJoinTable = $mapAppStuct[$sApp]['tableJoin'];
            $arrField = $mapAppStuct[$sApp]['field'];

            $arrSelect[] = $arrMainTable['name'] . '.' . $mapTableStruct[$arrMainTable['name']]['key'] . ' As MAINTABLE_PK';
            foreach ($arrField as $v) {
                if ($v['type'] == 'Relate') {//关联类型
                    $arrSelect[] = $v['joinField'] . ' As ' . $v['name'];
                    if (array_search($v['mainField'], $arrSelect) === false) {
                        $arrSelect[] = $v['mainField'];
                    }
                } else {
                    $arrSelect[] = $v['mainField'];
                }
            }

            $arrSql['select'] = implode(', ', $arrSelect);
            $arrSql['from'] = $arrMainTable['name'];
            foreach ($arrJoinTable as $v) {
                if ($v['table'] == '')
                    continue;
                $sJoin = $v['join'] . ' Join';
                $sMainField = $arrMainTable['name'] . '.' . $v['field'];
                $sJoinField = $v['table'] . '.' . $mapTableStruct[$v['table']]['key'];
                $arrSql[$sJoin][] = $v['table'] . ' On ' . $sMainField . '=' . $sJoinField;
            }
            $arrSql['where'] = '';
            $arrAppSql[$sApp] = $arrSql;
            $this->setAppSql($arrAppSql);
        }
    }

    function joinSql($arrSql) {
        $arrTemp = array();
        foreach ($arrSql as $k => $v) {
            if ($v != '') {
                if (strpos($k, 'Join') !== false) {
                    $arrTemp[] = $k . ' ' . implode(' ' . $k . ' ', $v);
                } else {
                    $arrTemp[] = $k . ' ' . $v;
                }
            }
        }
        $sSql = implode(' ', $arrTemp);
        return $sSql;
    }
    
    function createData($iId) {    	
    	$mapTableStruct = $this->getTableStruct();
    	$arrAppSql = $this->getAppSql($this->getApp());
    	$sMainTable = $arrAppSql['from'];
    	$sKeyField = $sMainTable . '.' . $mapTableStruct[$sMainTable]['key'];
    	$arrAppSql['where'] = $sKeyField . '="' . $iId.'"';
    	$sSql = $this->joinSql($arrAppSql);
    	$arrRecord = $this->getDb()->select($sSql);
    	return $arrRecord[0];    	
    }

}

?>
