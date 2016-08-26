<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Segment_Table
 *
 * @author jinlee
 */
class Segment_Table_Editor extends Segment_Table {

    function __construct($sSegment, $sApp) {
        parent::__construct($sSegment, $sApp);
        //$this->_sShowType = empty($_GET['ShowType']) ? 'Content' : $_GET['ShowType'];
        //$this->_sShowType = 'Edit';
        $this->_sShowType = 'Content';
    }

    function showScript() {
        $oModel = Model::create('', $this->getApp());
        $mapAppStuct = $oModel->getAppStruct($this->getApp());
        $sAppDetail = $mapAppStuct['app']['detail'];
        $sApp = $this->getApp();

        $sHtml = <<<EOD
        <script>                   
            function AddRow(el){
                alert(123);
                var sUrl="Router.php?App=$sAppDetail&Page=Grid_Editor&ShowType=Edit&Action=Add&LookupField=iApplyPk&LookupId="+sId;
                $("#page_hframe_right").loadUrl(
                    sUrl, 
                    '',
                    function(){ $("#page_hframe_right").find("[layoutH]").layoutH(); }
                 );                
            }                       
        </script>
EOD;
        return $sHtml;
    }

    function show($isEmpty = false) {
        //layoutH="51"
        $sBody = $isEmpty === false ? $this->showBody() : $this->showEmptyBody();
        $sTable = '<table class="list nowrap itemDetail" addButton="新建条目" width="100%"  >' . $this->showHeader() . $sBody . '</table>';

        return $sTable;
    }

    function showHeader() {
        $oModel = $this->getModel();
        $arrField = $oModel->fetchField();
        $sHtml = '<thead><tr>';
        foreach ($arrField as $v) {
            if ($v['display'] == true && $v['name'] != TABLE_OPERATION)
                $sHtml .= '<th type="text" name="detailItems_insert[#index#][' . $v['name'] . ']" size="12" fieldClass="required">' . $v['label'] . '</th>';
        }
        $sHtml .= '<th type="del" width="60">操作</th>';
        $sHtml .= '</tr></thead>';
        return $sHtml;
    }

    function showBody() {
        $oButton = Segment_Button::create('Button', $this->getApp());
        $oModel = $this->getModel();
        $arrRecord = $oModel->fetchRecord();
        $arrField = $oModel->fetchField();

        $sHtml = '<tbody>';
        foreach ($arrRecord as $arrRow) {
            $sRowId = $arrRow[KEY_FIELD_NAME];
            unset($arrRow[KEY_FIELD_NAME]);
            //unset($arrRow[OPT_FIELD_NAME]);
            //编辑模式可以去掉编辑按钮            
            $arrRow[OPT_FIELD_NAME] = str_replace('Edit', '', $arrRow[OPT_FIELD_NAME]);

            if ($arrRow[OPT_FIELD_NAME] != '')
                $arrRow[OPT_FIELD_NAME] = $oButton->showInRow($arrRow[OPT_FIELD_NAME], $sRowId);

            $sHtml .= '<tr target="row_id_' . $this->getApp() . '" rel="' . $sRowId . '">';
            foreach ($arrField as $v) {
                if ($v['display'] != true)
                    continue;
                $oSegment_Input = Segment_Input::create($v['type'], $this->getApp());
                $oSegment_Input->setFieldInfo($v);
                $oSegment_Input->setValue($arrRow[$v['name']]);
                $oSegment_Input->setShowType($this->_sShowType);
                $sHtml .= '<td>' . $oSegment_Input->showGrid() . '</td>';
            }
            $sHtml .= '</tr>';
        }
        $sHtml .= '</tbody>';
        return $sHtml;
    }

    function showEmptyBody() {
        $oModel = $this->getModel();
        $arrField = $oModel->fetchField();

        $sHtml = '<tbody>';
        $sHtml .= '<tr target="row_id_' . $this->getApp() . '" rel="' . $sRowId . '">';
        
        foreach ($arrField as $v) {
            if ($v['display'] != true)
                continue;
            $oSegment_Input = Segment_Input::create($v['type'], $this->getApp());
            $oSegment_Input->setFieldInfo($v);
            $oSegment_Input->setValue('');
            $oSegment_Input->setShowType($this->_sShowType);
            $sHtml .= '<td>' . $oSegment_Input->showGrid() . '</td>';
        }
        $sHtml .= '</tr>';
        $sHtml .= '</tbody>';
        return $sHtml;
    }

}

?>
