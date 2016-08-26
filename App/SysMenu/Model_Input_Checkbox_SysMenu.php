<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Input_Checkbox_Menu
 *
 * @author jinlee
 */
class Model_Input_Checkbox_SysMenu extends Model_Input_Checkbox {
	function __construct($sModel, $sApp) {
		parent::__construct($sModel, $sApp);
	}
	
    function createStruct($sJoinField) {
        $mapTableStruct = $this->getTableStruct();
        $arr = explode('.', $sJoinField);
        $sTable = $arr[0];
        $sField = $arr[1];
        $this->setDisplayField($sField);
        $this->setTable($sTable);        
        $this->setKeyField($sTable=='sys_button'?'sCode':$mapTableStruct[$sTable]['key']);
        if (!empty($mapTableStruct[$sTable]['field']['iSort'])) {
            $this->setSortField('iSort');
        }
    }

}

?>