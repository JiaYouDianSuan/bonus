<?php
/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/8/3
 * Time: 16:22
 */
require_once('../../Init.php');

$iPaymentPk = $_GET["iPaymentPk"];

$oFPayment = new FinancePayment();
$sMsg = $oFPayment->importPaymentResult($iPaymentPk);
echo $sMsg;
