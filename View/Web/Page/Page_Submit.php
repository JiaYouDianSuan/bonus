<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_Submit
 *
 * @author jinlee
 */

class Page_Submit extends Page {

    protected $_arrStatus = array();

    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);
        $this->_arrStatus = array(
            'ok' => array(
                'code' => 200,
                'message' => '编辑成功！'
            ),
            'error' => array(
                'code' => 300,
                'message' => '编辑失败！'
            ),
            'timeout' => array(
                'code' => 301,
                'message' => '超时！'
            )
        );
    }
    
    function returnStatus($arrResult){
    	$arrStatus = $this->_arrStatus;    	
    	
    	$sStatus = empty($arrResult['status']) ? 'ok' : $arrResult['status'];
    	$arrReturn = array(
    		"statusCode" => $arrStatus[$sStatus]['code'],
    		"message" => empty($arrResult['message']) ? $arrStatus[$sStatus]['message'] : $arrResult['message'],
    		"navTabId" => "navTab_" . $this->getApp(),
    		"rel" => "",
    		"callbackType" => "",
    		"forwardUrl" => "",
    		"confirmMsg" => ""
    	);
    	return json_encode($arrReturn);
    }

    function save() {
        $arrStatus = $this->_arrStatus;

        $oModel_Submit = Model::create('Submit', $this->getApp());
        $mapAppStuct = $oModel_Submit->getAppStruct($this->getApp());
        $sMainApp = $mapAppStuct['app']['main'];
        $sNavTabId = empty($sMainApp)?"navTab_" . $this->getApp():"navTab_" . $sMainApp;
        $arrResult = $oModel_Submit->save();
		
        return $this->returnStatus($arrResult);        
    }

    function show() {
        if ($_GET['Search'] == 1) {
            $oPage_Grid = Page::create('Grid', $this->getApp());
            return $oPage_Grid->show();
        } else {
            return $this->save();
        }
    }

    function delete() {
        $oModel_Submit = Model::create('Submit', $this->getApp());
        $arrResult = $oModel_Submit->delete();               
        return $this->returnStatus($arrResult);
    }
    
    function confirm() {
    	$oModel_Submit = Model::create('Submit', $this->getApp());
    	$arrResult = $oModel_Submit->confirm(1);
    	return $this->returnStatus($arrResult);    	
    }
    
    function unConfirm() {
    	$oModel_Submit = Model::create('Submit', $this->getApp());
    	$arrResult = $oModel_Submit->Confirm(0);
    	return $this->returnStatus($arrResult);
    }
    
    function export1(){
    
    	$oModel =  Model::create('Table', $this->getApp());
    	$arrRecord = $oModel->fetchRecord();
    	$arrField = $oModel->fetchField();
    	//print_r($arrField);exit();
    	 
    	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header ( 'Content-Disposition: attachment;filename="user.xls"' );
		header ( 'Cache-Control: max-age=0' );    	
    	 
    	foreach($arrField as $v){
    		if($v['label']=='' || $v['name']==OPT_FIELD_NAME) continue;
    
    		$sContent = iconv('utf-8', 'gbk', $v['label']);
    		$arrHeadContent[] = $sContent;
    	}
    	echo implode("\t", $arrHeadContent)."\n";    	
    	 
    	foreach($arrRecord as $arrRow){
    		$arrRowContent = array();
    		foreach($arrField as $v){
    			if($v['label']=='' || $v['name']==OPT_FIELD_NAME) continue;
    
    			$oSegment_Input = Segment_Input::create($v['type'], $this->getApp());
    			$oSegment_Input->setFieldInfo($v);
    			$oSegment_Input->setValue($arrRow[$v['name']]);
    			$oSegment_Input->setShowType('Content');
    
    			$sContent = iconv('utf-8', 'gbk', $oSegment_Input->showGrid());
    			$arrRowContent[] = $sContent;
    		}
    		echo implode("\t", $arrRowContent)."\n";    		
    	}
    }
    
    function export(){    	

    	$oModel =  Model::create('Table', $this->getApp());    	 
    	$arrRecord = $oModel->fetchRecord();    	
    	$arrField = $oModel->fetchField();
    	//print_r($arrField);exit();
    	
    	header ( 'Content-Type: application/vnd.ms-excel;charset=gbk' );
    	header ( 'Content-Disposition: attachment;filename="user.csv"' );
    	header ( 'Cache-Control: max-age=0' );
    	$fp = fopen('php://output', 'a');
    	
    	foreach($arrField as $v){
    		if($v['label']=='' || $v['name']==OPT_FIELD_NAME) continue;
    		
    		$sContent = iconv('utf-8', 'gbk', $v['label']);
    		$arrHeadContent[] = $sContent;
    	}
    	fputcsv($fp,$arrHeadContent);
    	
    	foreach($arrRecord as $arrRow){
    		$arrRowContent = array();
    		foreach($arrField as $v){
    			if($v['label']=='' || $v['name']==OPT_FIELD_NAME) continue;
    			 
    			$oSegment_Input = Segment_Input::create($v['type'], $this->getApp());
    			$oSegment_Input->setFieldInfo($v);
    			$oSegment_Input->setValue($arrRow[$v['name']]);
    			$oSegment_Input->setShowType('Content');
    			$sContent = $oSegment_Input->showGrid();
    			if(is_numeric($sContent) && strlen($sContent)>11){
    				$sContent = "'".$sContent;
    			}
    			 
    			$sContent = iconv('utf-8', 'gbk', $sContent);    			    		
    			$arrRowContent[] = $sContent;    			    			    					    			    
    		}
    		//print_R($arrRowContent);
    		fputcsv($fp,$arrRowContent);
    	}
    }
}

?>
