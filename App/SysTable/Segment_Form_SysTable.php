<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Form
 *
 * @author jinlee
 */
class Segment_Form_SysTable extends Segment_Form {

    function __construct($sSegment, $sApp) {
        parent::__construct($sSegment, $sApp);
    }

    function fetchInputType() {
        $arrInputType = array();
        $sDir = JFRAME_DISK_ROOT . 'View/Web/Segment';
        if (is_dir($sDir)) {
            $hDir = opendir($sDir);
            $file = array();
            while (($sFileName = readdir($hDir)) !== false) {
                $str = str_replace('.php', '', $sFileName);
                if (strpos($str, 'Segment_Input_') !== FALSE){
                	$sKey = str_replace('Segment_Input_', '', $str);
                	$arrInputType[$sKey] = $this->_arrFormElement[$sKey];
                }                	
            }
            closedir($hDir);
        }
        return $arrInputType;
    }

    function showInputs() {
        return $this->getPageType() == 'Add' ? $this->showAdd() : $this->showEdit();
    }

    function showEdit() {
        $iId = $this->getModel()->getId();
        $sSql = 'Select * From sys_table Where iPk=' . $iId;
        $arrRecord = $this->getDb()->select($sSql);
        $sTableName = $arrRecord[0]['sName'];
        $iTablePk = $arrRecord[0]['iPk'];
        $arrMust = array('1' => '是', '0' => '否');
        $arrType = $this->fetchInputType();
		
        $sSql = 'Select sSnPre From sys_table Where iPk="'.$iId.'"';
        $arrRecord = $this->getDb()->select($sSql);
        $sSnPre = $arrRecord[0]['sSnPre'];

        $sSql = 'Select
            COL.COLUMN_NAME As sColumn,
            COL.COLUMN_KEY as sColumnKey,
            F.*
            From information_schema.`COLUMNS` As COL 
            Left Join '.DB_NAME.'.sys_table_field As F
            On F.sName=COL.COLUMN_NAME
            And F.iTablePK="' . $iId . '"
            Where COL.TABLE_NAME="' . $sTableName . '"
            Order By F.iSort';
        $arrRecord = $this->getDb()->select($sSql);

        $sHtml  = '<table ><tr><td>流水号前缀:</td><td><input type="text" maxlength="3" class="lettersonly" value="'.$sSnPre.'" size="5" name="sSnPre"></td></tr></table>';
        $sHtml .= '<table class="list" >';
        $sHtml .= '<thead>';
        $sHtml .= '<tr>';
        $sHtml .= '<th>字段名</th>';
        $sHtml .= '<th>显示名</th>';
        $sHtml .= '<th>显示类型</th>';
        $sHtml .= '<th>必填</th>';        
        $sHtml .= '<th>列显示</th>';        
        $sHtml .= '<th>搜索</th>';
        $sHtml .= '<th>列宽度</th>';
        $sHtml .= '<th>关联字段信息</th>';
        $sHtml .= '<th>下拉框选项</th>';        
        $sHtml .= '<th>排序</th>';
        $sHtml .= '</tr>';
        $sHtml .= '</thead>';

        $sHtml .= '<tbody>';

        foreach ($arrRecord as $v) {
            $sHtml .= '<tr>';
            $sHtml .= '<td ' . ($v['sColumnKey'] == 'PRI' ? 'style="color:red;"' : '') . '>';
            $sHtml .= '<input type="hidden" name="arrField[iPk][]" value="' . $v['iPk'] . '" />
                       <input type="hidden" name="arrField[sName][]" value="' . $v['sColumn'] . '" />
                       <input type="hidden" name="arrField[iTablePk][]" value="' . $iTablePk . '" />
                       <input type="hidden" name="arrField[sTable][]" value="' . $sTableName . '" />';
            $sHtml .= $v['sColumn'];
            $sHtml .= '</td>';
            $sHtml .= '<td><input type="text" size="10" class="" name="arrField[sDisplay][]" value="' . $v['sDisplay'] . '" /></td>';
            $sHtml .= '<td><select name="arrField[sType][]">';
            foreach ($arrType as $sTypeKey => $sTypeName) {
                $sSelected = $sTypeKey === $v['sType'] ? 'selected="selected"' : '';
                $sHtml .= '<option value="' . $sTypeKey . '" ' . $sSelected . ' >' . $sTypeName . '</option>';
            }
            $sHtml .= '</select></td>';

            $sHtml .= '<td><select name="arrField[bRequired][]">';
            foreach ($arrMust as $sKey => $sValue) {
                $sSelected = $sKey == $v['bRequired'] ? 'selected="selected"' : '';
                $sHtml .= '<option value="' . $sKey . '" ' . $sSelected . ' >' . $sValue . '</option>';
            }
            $sHtml .= '</select></td>';

            $sHtml .= '<td><select name="arrField[bDisplay][]">';
            foreach ($arrMust as $sKey => $sValue) {
                $sSelected = $sKey == $v['bDisplay'] ? 'selected="selected"' : '';
                $sHtml .= '<option value="' . $sKey . '" ' . $sSelected . ' >' . $sValue . '</option>';
            }
            $sHtml .= '</select></td>';

            $sHtml .= '<td><select name="arrField[bSearch][]">';
            foreach ($arrMust as $sKey => $sValue) {
                $sSelected = $sKey == $v['bSearch'] ? 'selected="selected"' : '';
                $sHtml .= '<option value="' . $sKey . '" ' . $sSelected . ' >' . $sValue . '</option>';
            }
            $sHtml .= '</select></td>';

            $sHtml .= '<td><input type="text" size="1" class="" name="arrField[iWidth][]" value="' . $v['iWidth'] . '"/></td>';
            $sHtml .= '<td><input type="text" size="15" class="" name="arrField[sJoinField][]" value="' . htmlspecialchars($v['sJoinField']) . '" /></td>';
            $sHtml .= '<td><input type="text" size="15" class="" name="arrField[sComboxData][]" value="' . htmlspecialchars($v['sComboxData']) . '" /></td>';
            $sHtml .= '<td><input type="text" size="1" class="" name="arrField[iSort][]" value="' . $v['iSort'] . '"/></td>';
            $sHtml .= '</tr>';
        }
        $sHtml .= '</tbody>';
        $sHtml .= '</table>';
        return $sHtml;
    }

    function showAdd() {
        $sSql = 'Select * From sys_table';
        $arrRecord = $this->getDb()->select($sSql);
        foreach ($arrRecord as $v) {
            $mapTableUsed[$v['sName']] = $v;
        }

        $sSql = 'SELECT
            t.TABLE_NAME As sTable,
            c.COLUMN_NAME As sKeyField
            FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS t,
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS c
            WHERE t.TABLE_NAME = c.TABLE_NAME
            AND t.TABLE_NAME Not Like "sys_%"
            AND t.TABLE_SCHEMA = "'.DB_NAME.'"
            AND t.CONSTRAINT_TYPE = "PRIMARY KEY"';
        $arrRecord = $this->getDb()->select($sSql);
        foreach ($arrRecord as $v) {
            if (!array_key_exists($v['sTable'], $mapTableUsed)) {
                $arrTable[$v['sTable']] = $v['sTable'];
            }
        }
        $oInput = Segment_Input::create('Combox', $this->getApp());
        $oInput->setLabel('表');
        $oInput->setField('sTableName');
        $oInput->setData($arrTable);        
        $sHtml = $oInput->show();
        return $sHtml;
    }

}

?>
