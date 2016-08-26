<?php

/*
 * To change this template, choose Tools | Templates and open the template in the editor.
 */

/**
 * Description of Segment_Button
 *
 * @author jinlee
 */
class Segment_Button extends Segment {
	function __construct($sSegment, $sApp) {
		parent::__construct ( $sSegment, $sApp );
		$this->createButton ();
	}
	function getButton() {
		return $this->getRegistor ()->get ( 'button' );
	}
	function setButton($mapButton) {
		$this->getRegistor ()->set ( 'button', $mapButton );
	}
	function showInTable($sButtonCodes) {
		$arrButton = array ();
		$mapButton = $this->getButton ();
		$arrCode = explode ( ',', $sButtonCodes );
		foreach ( $arrCode as $v ) {
			$arrButton [] = str_replace ( '{App}', $this->getApp (), $mapButton [$v] );
		}
		return implode ( ' ', $arrButton );
	}
	function isCanShowInRow(&$arrRow,$sButtonCode){
		return ture;
	}
	function showInRow(&$arrRow, $sId) {
		$sButtonCodes = $arrRow [OPT_FIELD_NAME];
		$iConfirmFlag = isset ( $arrRow [CONFIRM_FIELD_NAME] ) ? $arrRow [CONFIRM_FIELD_NAME] : '';
		$iDeleteFlag = $arrRow[DELETE_FIELD_NAME];
		$arrButton = array ();
		$mapButton = $this->getButton ();
		$arrButtonInDeleteFlag = array("View","Detail");

		$iConfirmFlag == 1 && $sButtonCodes = str_replace('Confirm', 'UnConfirm', $sButtonCodes);		
		$arrCode = explode ( ',', $sButtonCodes );		
		foreach ( $arrCode as $v ) {
			if(!$this->isCanShowInRow($arrRow,$v)) continue;
			if($iDeleteFlag == 1 && !in_array($v,$arrButtonInDeleteFlag)) continue;//删除状态，只显示某些按钮
			if($iConfirmFlag == 1 && $v!='UnConfirm' && $v!='View') continue;
			
			$sButton = str_replace ( '{Id}', $sId, $mapButton [$v] );
			$sButton = str_replace ( '{App}', $this->getApp (), $sButton );
			$arrButton [] = $sButton;
		}
		return implode ( ' ', $arrButton );
	}
	function createUnButton(&$mapButtonHtml, $arrButton) {
		$sCode = $arrButton ['sCode'];
		$sTitle = $arrButton ['sName'];
		$sButtonHtml = $mapButtonHtml [$sCode];
		$sButtonHtml = str_replace ( $sCode, 'Un' . $sCode, $sButtonHtml );
		$sButtonHtml = str_replace ( $sTitle, '取消' . $sTitle, $sButtonHtml );		
		$mapButtonHtml ['Un' . $sCode] = $sButtonHtml;
	}
	function createButton() {
		$mapAppStuct = $this->getModel ()->getAppStruct ();
		$sPage = $mapAppStuct [$this->getApp ()] ['table'] ['page'];
		
		$mapButton = $this->getButton ();
		$mapButton = array(); //增加按钮后需要重新生成。
		if (empty ( $mapButton )) {
			$arrButton = $this->getModel ()->fetchAll ();
			foreach ( $arrButton as $v ) {
				$sImgName = $v["sImage"];
				$sCode = $v["sCode"];
				$sName = $v["sName"];
				$sTarget = $v["sTarget"];

				$sImgPath = JFRAME_WWW_ROOT . 'Ui/Image/Button/' . $v ['sImage'];
				if ($v ['sType'] == 'Row') {
					$sHref = JFRAME_WWW_ROOT . 'Router.php?App={App}&Page=' . $v ['sCode'] . '&Id={Id}';
					$mapButton[$sCode] = "<a iconClass='$sImgName'  href='$sHref' target='$sTarget' title='$sName' width='800' height='480' mask='true'></a>";
					if ($v ['sCode'] == 'Confirm') {
						$this->createUnButton ( $mapButton, $v );
					}
				} else if ($v ['sType'] == 'Table') {
					$sHref = JFRAME_WWW_ROOT."Router.php?App={App}&Page=$sCode";
					$mapButton[$sCode] = "<li><a iconClass='$sImgName'  href='$sHref' target='$sTarget' width='800' height='480' mask='true'><span>$sName</span></a></li>";
				}
				
				if ($sPage == 'HFrame_TreeForm' && $v ['sCode'] == 'Add') {
					$sHref = JFRAME_WWW_ROOT."Router.php?App={App}&Page=$sCode";
					$mapButton[$sCode] = "<li><a iconClass='$sImgName'  href='$sHref' target='ajax' onclick='addTreeNode(this);' rel='page_hframe_right' width='800' height='480' mask='true'><span>$sName</span></a></li>";
				}
				/*
				 * else if ($sPage == 'HFrame_GridGrid_Editor' && $v['sCode'] == 'Add') { $mapButton[$v['sCode']] = '<li><a href="javascript:void(0);" onclick="' . $v['sCode'] . '(this);"> <span style="background: url(' . $sImgPath . ') no-repeat; background-position:0px 3px;" >' . $v['sName'] . '</span></a></li>'; } else if ($sPage == 'HFrame_GridGrid_Editor' && $v['sCode'] == 'Edit') { $mapButton[$v['sCode']] = '<a title="' . $v['sName'] . '" href="javascript:void(0);" onclick="' . $v['sCode'] . '(this, event);" ><img src="' . $sImgPath . '" /></a>'; }
				 */
			}
			//print_R( $mapButton);
			$this->setButton ( $mapButton );
		}
	}
}

?>
