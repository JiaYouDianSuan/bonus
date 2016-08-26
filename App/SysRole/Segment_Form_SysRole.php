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
class Segment_Form_SysRole extends Segment_Form {

    function __construct($sSegment, $sApp) {
        parent::__construct($sSegment, $sApp);
    }

    function showMenu() {
        $sHtml = '';
        $sAuthorityCodes = '';
        $mapButton = array();
        $iId = $this->getModel()->getId();

        if (!empty($iId)) {
            $sSql = 'Select sAuthorityCodes From sys_role Where iPk=' . $iId;
            $arrRecord = $this->getDb()->select($sSql);
            $sAuthorityCodes = $arrRecord[0]['sAuthorityCodes'];
            $arrAuthorityCode = json_decode($sAuthorityCodes, true);
            $arrAuthority = $arrAuthorityCode['Authority'];
            $arrAuthorityRange = $arrAuthorityCode['Range'];
        }

        $sSql = 'Select * From sys_button Order By iSort Asc';
        $arrRecord = $this->getDb()->select($sSql);
        foreach ($arrRecord as $v) {
            $mapButton[$v['sCode']] = $v['sName'];
        }

        $sSql = 'Select * From sys_menu ';
        $arrRecord = $this->getDb()->select($sSql);
        foreach ($arrRecord as $arrMenu) {
            $arrButtonChecked = empty($arrAuthority[$arrMenu['sApp']]) ? array() : $arrAuthority[$arrMenu['sApp']];
            $sMenuChecked = ($sAuthorityCodes == 'All' || !empty($arrButtonChecked)) ? ' checked=checked ' : '';
            $sHtml .= '<input type="hidden" name="sAuthorityCodes" value="' . $sAuthorityCodes . '">';
            $sHtml .= '<div id="menuCheck">';
            $arrButton = empty($arrMenu['sButton']) ? array() : explode(',', $arrMenu['sButton']);
            $sHtml .= '<dl>';
            $sHtml .= '<dt class="nowrap"><b>'. $arrMenu['sName'] . '</b><input type="checkbox"  value="' . $arrMenu['sApp'] . '"' . $sMenuChecked . ' /></dt>';
            $sHtml .= '<dd>';
            foreach ($arrButton as $sButtonCode) {
                $sButtonChecked = ($sAuthorityCodes == 'All' || in_array($sButtonCode, $arrButtonChecked)) ? ' checked=checked ' : '';
                $sHtml .= '<label>' . $mapButton[$sButtonCode] . '<input type="checkbox" name="authority[' . $arrMenu['sApp'] . '][]" value="' . $sButtonCode . '"' . $sButtonChecked . ' /></label>';
            }            
            $sHtml .= '</dd>';
            $sHtml .= '</dl>';
            $sHtml .= '</div>';              
            if(substr($arrMenu['sApp'], 0, 3) != 'Sys'){
                if($sAuthorityCodes == 'All' || $arrAuthorityRange['read'][$arrMenu['sApp']] == 'All'){
                    $sRangeChecked_All = ' checked=checked ';
                    $sRangeChecked_Self = '';
                }else if($arrAuthorityRange['read'][$arrMenu['sApp']] == 'Self'){
                    $sRangeChecked_All = '';
                    $sRangeChecked_Self = ' checked=checked ';
                }
                $sRangeChecked = ($sAuthorityCodes == 'All' ) ? ' checked=checked ' : '';
                $sHtml .= '<dl>';
                $sHtml .= '<dt class="nowrap"><b>可见范围</b></dt>';
                $sHtml .= '<dd>';
                $sHtml .= '<label>全部<input type="radio" name="authority_range_read[' . $arrMenu['sApp'] . ']" value="All"' . $sRangeChecked_All . ' /></label>';
                $sHtml .= '<label>自己<input type="radio" name="authority_range_read[' . $arrMenu['sApp'] . ']" value="Self"' . $sRangeChecked_Self . ' /></label>';               
                $sHtml .= '</dd>';
                $sHtml .= '</dl>';
                
                if($sAuthorityCodes == 'All' || $arrAuthorityRange['edit'][$arrMenu['sApp']] == 'All'){
                    $sRangeChecked_All = ' checked=checked ';
                    $sRangeChecked_Self = '';
                }else if($arrAuthorityRange['edit'][$arrMenu['sApp']] == 'Self'){
                    $sRangeChecked_All = '';
                    $sRangeChecked_Self = ' checked=checked ';
                }
                $sHtml .= '<dl>';
                $sHtml .= '<dt class="nowrap"><b>编辑范围</b></dt>';
                $sHtml .= '<dd>';
                $sHtml .= '<label>全部<input type="radio" name="authority_range_edit[' . $arrMenu['sApp'] . ']" value="All"' . $sRangeChecked_All . ' /></label>';
                $sHtml .= '<label>自己<input type="radio" name="authority_range_edit[' . $arrMenu['sApp'] . ']" value="Self"' . $sRangeChecked_Self . ' /></label>';                
                $sHtml .= '</dd>';
                $sHtml .= '</dl>';
            }
                
            $sHtml .= '<div class="divider"></div>';
        }
        return $sHtml;
    }

    function showInputs() {
        $arrField = $this->getModel()->fetchField();
        $mapInputValue = $this->getInputValue();
        $sHtml = '';
        $sHtml .= '<fieldset><legend>角色信息</legend>';        
        foreach ($arrField as $v) {
            if ($v['display'] != true ) continue;
            if ($v['name'] == 'sAuthorityCodes') continue;
            $oInput = Segment_Input::create($v['type'], $this->getApp());
            $oInput->setFieldInfo($v);
            $oInput->setValue($mapInputValue[$oInput->getField()]);
            $sHtml .= $oInput->show();
        }
        $sHtml .= '</fieldset>';

        $sHtml .= '<fieldset><legend>权限信息</legend>';
        $sHtml .= $this->showMenu();
        $sHtml .= '</fieldset>';
        $sHtml .= $this->showCheckboxChangeEvent();
        return $sHtml;
    }

    function showCheckboxChangeEvent() {
        $sHtml = '
        <script>
            $(function(){ 
                $("div>dl>dt>:checkbox").change(function() {                    
                    var sApp = $(this).val();
                    $("[name=\'authority["+sApp+"][]\']").attr("checked", Boolean($(this).attr("checked")));                    
                });
            }); 
        </script>';
        return $sHtml;
    }

}

?>
