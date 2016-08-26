<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Button
 *
 * @author jinlee
 */
class Model_Button extends Model {

    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
    }
    
    function getAuthority(){
        $JFrame = new Frame();        
        return $JFrame->getAuthority();
    }

    function fetchAll() {
        $sSql = 'Select * From sys_button';
        $arrRecord = $this->getDb()->select($sSql);
        return $arrRecord;
    }
    
    function fetchInTable(){
        $arrReturn = array();
        $arrAuthority = $this->getAuthority();
        
        $sSql = 'Select sButton From sys_menu Where sApp="'.$this->getApp().'"';
        $arrRecord = $this->getDb()->select($sSql);
        $sButton = $arrRecord[0]['sButton'];
        
        $sFind = str_replace(',', '","', $sButton);
        $sSql = 'Select 
            Group_Concat(sCode) As sCode 
            From sys_button 
            Where sType="Table" 
            And sCode In ("'.$sFind.'")';
        if($arrAuthority != 'All' && !empty($arrAuthority['Authority'][$this->getApp()])){
            $sSql .= ' And sCode In ("'.implode('","', $arrAuthority['Authority'][$this->getApp()]).'")';
        }
        $arrRecord = $this->getDb()->select($sSql);

        return $arrRecord[0]['sCode'];        
    }
    
    function fetchInRow(){
        $arrReturn = array();
        $arrAuthority = $this->getAuthority();
        
        $sSql = 'Select sButton From sys_menu Where sApp="'.$this->getApp().'"';
        $arrRecord = $this->getDb()->select($sSql);
        $sButton = $arrRecord[0]['sButton'];
        
        $sFind = str_replace(',', '","', $sButton);
        $sSql = 'Select 
            Group_Concat(sCode Order By iSort) As sCode 
            From sys_button 
            Where sType="Row"
            And sCode In ("'.$sFind.'")';       
        if($arrAuthority != 'All' && !empty($arrAuthority['Authority'][$this->getApp()])){
            $sSql .= ' And sCode In ("'.implode('","', $arrAuthority['Authority'][$this->getApp()]).'")';
        }
        $arrRecord = $this->getDb()->select($sSql);        
        return $arrRecord[0]['sCode'];        
    }

}

?>
