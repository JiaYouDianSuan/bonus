<?PHP

require_once('Init.php');
if($_POST['Action'] == 'Login'){		
	if($_POST['code'] != $_SESSION['code']){
		$sMessage = '验证码不正确！';		
	}else{
		$JFrame = new Frame();
		if($JFrame->login($_POST['id'], $_POST['password']) === true){
			header('Location:Index.php');
		}else{
			$sMessage = '用户名或密码错误，请重新输入！';
		}	
	}    
}

include(JFRAME_DISK_ROOT.'View/Web/Login.php');
?>