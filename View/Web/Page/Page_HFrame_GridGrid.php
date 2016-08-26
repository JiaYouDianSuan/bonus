<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page_HFrame_TreeForm
 *
 * @author jinlee
 */
class Page_HFrame_GridGrid extends Page_HFrame {

    protected $_sAppDetail;

    function __construct($sPage, $sApp) {
        parent::__construct($sPage, $sApp);

        $oModel = Model::create('', $this->getApp());
        $mapAppStuct = $oModel->getAppStruct($this->getApp());
        $this->setAppDetail($mapAppStuct['app']['detail']);
    }

    function setAppDetail($sAppDetail) {
        $this->_sAppDetail = $sAppDetail;
    }

    function getAppDetail() {
        return $this->_sAppDetail;
    }

    function showScript() {
        $oModel = Model::create('', $this->getApp());
        $mapAppStuct = $oModel->getAppStruct($this->getApp());
        $sMainTable = $mapAppStuct['table']['name'];
        $sAppDetail = $mapAppStuct['app']['detail'];
        $sAppDetailField = $mapAppStuct['app']['detailField'];

        $sHtml = <<<EOD
        <script>
            $(function(){                
                $("tr[target='row_id_BusApply']").live("click", function(){                    
                    var sUrl="Router.php?App=$sAppDetail&Page=Grid&LookupField=$sAppDetailField&LookupId="+$(this).attr('rel');                    		
                    $("#page_hframe_right").loadUrl(
                        sUrl, 
                        '',
                        function(){ $("#page_hframe_right").find("[layoutH]").layoutH(); }
                     );
                });
            });                             
        </script>
EOD;
        return $sHtml;
    }

    function showRight() {
        $oPage_Grid = Page::create('Grid', $this->getAppDetail());        
        $oPage_Grid->setDirection('Right');
        $oPage_Grid->save();
        //var_dump($oPage_Grid);        
        //var_dump(Registor::create()->get('Page_Grid_BusApplyFile'));                
        return $oPage_Grid->show();
    }

    function showLeft() {
        $oPage_Grid = Page::create('Grid', $this->getApp());
        return $this->showScript() . $oPage_Grid->show();
    }

}

?>
