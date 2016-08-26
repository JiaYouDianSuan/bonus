<?PHP

require_once('Init.php');
$JFrame = new Frame();
$JFrame->checkLogin();

//print_R($_SERVER);exit();
include(JFRAME_DISK_ROOT.'View/Web/Index.php');
?>