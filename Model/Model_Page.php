<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Page
 *
 * @author jinlee
 */
class Model_Page extends Model {

    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
        $this->createTableStruct();
        $this->createAppSql();
    }

    function fetchTotalCount() {
        $mapTableStruct = $this->getTableStruct();
        $arrAppSql = $this->getAppSql($this->getApp());
        if(empty($arrAppSql)) return 0;
        $sMainTable = $arrAppSql['from'];
        $sKeyField = $sMainTable . '.' . $mapTableStruct[$sMainTable]['key'];
        $arrAppSql['select'] = 'count(' . $sKeyField . ') As iCount';
        $sSql = $this->joinSql($arrAppSql);
        $arrRecord = $this->getDb()->select($sSql);
        return $arrRecord[0]['iCount'];
    }
}

?>
