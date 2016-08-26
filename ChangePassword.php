<?PHP
require_once('Init.php');
if($_POST['oldPassword']!='' && $_POST['newPassword']!=''){	
	$JFrame = new Frame();
	$result = $JFrame->checkPassword($_POST['oldPassword']);
	if($result !== true){
		echo $result;
	}else{
		echo $JFrame->modifyPassword($_POST['newPassword']);
	}
	exit();
}
?>

<div class="pageContent">
	
	<form method="post" action='ChangePassword.php'  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
		<div class="pageFormContent" layoutH="58">

			<div class="unit">
				<label>旧密码：</label>
				<input type="password" name="oldPassword" size="30" minlength="<?php echo PASSWORD_MIN_LENGTH?>" maxlength="<?php echo PASSWORD_MAX_LENGTH?>" class="required" />
			</div>
			<div class="unit">
				<label>新密码：</label>
				<input type="password" id="cp_newPassword" name="newPassword" size="30" minlength="<?php echo PASSWORD_MIN_LENGTH?>" maxlength="<?php echo PASSWORD_MAX_LENGTH?>" class="required alphanumeric"/>
			</div>
			<div class="unit">
				<label>重复输入新密码：</label>
				<input type="password" name="rnewPassword" size="30" equalTo="#cp_newPassword" class="required alphanumeric"/>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>	
</div>
