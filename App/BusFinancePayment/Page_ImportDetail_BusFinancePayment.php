<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/7/22
 * Time: 17:55
 */
class Page_ImportDetail_BusFinancePayment extends Page
{
    function __construct($sClassType, $sApp)
    {
        parent::__construct($sClassType, $sApp); // TODO: Change the autogenerated stub
    }

    function show()
    {
        $sJsFilePath = JFRAME_WWW_ROOT."Ui/Js/Web/Page/Page_ImportDetail_BusFinancePayment.js";
        $sUrl = JFRAME_WWW_ROOT."Router.php?Page=Submit&App=$this->getApp()";
        $sHtml = <<<EOD
        <script src='{$sJsFilePath}' type='text/javascript'></script>
        <div class="pageContent">
            <form method="post" action={$sUrl} class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
                <div class="pageFormContent nowrap" layoutH="97">
                <input id="iPaymentPk" type="hidden" value="{$_GET["Id"]}" />
                    <dl>
			            <dt>附件：</dt>
			            <dd>
			                <input id="uploadFile" type="file" name="uploadFile"
		                    uploaderOption="{
                            swf:'Ui/uploadify/scripts/uploadify.swf',
                            uploader:'Ui/uploadify/uploadify.php',
                            formData:{sFileName:'payment_result_file.txt'},
                            buttonText:'请选择文件',
                            fileSizeLimit:'2000KB',
                            fileTypeDesc:'*.txt;',
                            fileTypeExts:'*.txt;',
                            auto:true,
                            multi:true,
                            onUploadSuccess:uploadFileSuccess
                            }"/></dd>
                    </dl>
                    <dt> </dt>
                    <dd id="uploadFileMessage"></dd>
                </div>

                <div class="formBar">
                    <ul>

                        <li><div class="button"><div class="buttonContent"><button type="button" class="close">关闭</button></div></div></li>
                    </ul>
                </div>
            </form>
        </div>
EOD;

        return $sHtml;
    }


}