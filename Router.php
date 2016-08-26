<?php

require_once('Init.php');


$sApp = $_GET['App'];
$sPage = $_GET['Page'];
$sButton = $_GET['Button'];

if(empty($sButton)){
    $oPage = Page::create($sPage, $sApp);
    echo $oPage->show();
}else{
    
}

?>
