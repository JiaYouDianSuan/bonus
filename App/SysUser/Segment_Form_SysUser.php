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
class Segment_Form_SysUser extends Segment_Form {


    function __construct($sSegment, $sApp) {
        parent::__construct($sSegment, $sApp);
        
    }

  

    function showInputs() {
        $arrField = $this->getModel()->fetchField();
        $mapInputValue = $this->getInputValue();                 
        $sHtml = '';
        foreach ($arrField as $v) {
            if ($v['display'] != true ) continue;
            //系统管理员只允许修改密码
            if($mapInputValue['sId'] == 'admin' && $v['name']!='sPassword') continue;
            $oInput = Segment_Input::create($v['type'], $this->getApp());
            $oInput->setFieldInfo($v);
            $oInput->setValue($mapInputValue[$oInput->getField()]);
            $sHtml .= $oInput->show();
        }
        return $sHtml;
    }

   

}

?>
