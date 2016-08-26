<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/UploadFile'; // Relative to the root
//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) ) {
	$arrFileInfo = $_FILES['uploadFile'];
	$tempFile = $arrFileInfo['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$sFileName = $_POST["sFileName"]==""?$arrFileInfo['name']:$_POST["sFileName"];
	$fileParts = pathinfo($arrFileInfo['name']);
	$targetFile = rtrim($targetPath,'/') . '/' . $sFileName;
	move_uploaded_file($tempFile,$targetFile);

	/*// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','txt'); // File extensions
	$fileParts = pathinfo($arrFileInfo['name']);
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
	} else {
		echo 'Invalid file type.';
	}*/
}
?>