<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JFrame</title>
<link href="Ui/Dwz/themes/css/login.css" rel="stylesheet" type="text/css" />
<script src="Ui/md5.js" type="text/javascript"></script>
<script>
function encodePassword(){
	document.getElementById('password').value = hex_md5(hex_md5(document.getElementById('password').value)+document.getElementById('code').value);
}
</script>
</head>

<body>
	<div id="login">
		<div id="login_header">
			<h1 class="login_logo">
				<a href="http://demo.dwzjs.com">
					<img src="Ui/Dwz/themes/default/images/login_logo.gif" />
				</a>
			</h1>
			<div class="login_headerContent">
				<div class="navList">
					<ul>
						<li>
							<a href="#">设为首页</a>
						</li>
						<li>
							<a href="http://bbs.dwzjs.com">反馈</a>
						</li>
						<li>
							<a href="doc/dwz-user-guide.pdf" target="_blank">帮助</a>
						</li>
					</ul>
				</div>
				<h2 class="login_title">
					<img src="Ui/Dwz/themes/default/images/login_title.png" />
				</h2>
			</div>
		</div>
		<div id="login_content">
			<div class="loginForm">
				<div align='center'>
					<font color='red'><?php echo $sMessage;?></font>
				</div>
				<form action="Login.php" method="post" onsubmit="encodePassword();">
					<input type="hidden" name="Action" value="Login" />
					<p>
						<label>用户名：</label>
						<input type="text" name="id" size="20" class="login_input"
							value="<?php echo $_POST['id'];?>" />
					</p>
					<p>
						<label>密码：</label>
						<input type="password" id="password" name="password" size="20"
							class="login_input" />
					</p>
					<p>
						<label>验证码：</label>
						<input id="code" class="code require" name="code" type="text"
							size="4" maxlength="4" />
						<span>
							<img src="./Code.php" alt="" width="75" height="24" border="0"
								align="absmiddle"
								onclick="alert(hex_md5('test'));this.src = './Code.php?'+ Math.random(); "
								style="cursor: pointer;" title="看不清？点击换一张。" />
						</span>
					</p>
					<div class="login_bar">
						<input class="sub" type="submit" value=" " />
					</div>
				</form>
			</div>
			<div class="login_banner">
				<img src="Ui/Dwz/themes/default/images/login_banner.jpg" />
			</div>
			<div class="login_main">
				<ul class="helpList">
					<li>
						<a href="#">下载驱动程序</a>
					</li>
					<li>
						<a href="#">如何安装密钥驱动程序？</a>
					</li>
					<li>
						<a href="/FindPassword.php">忘记密码怎么办？</a>
					</li>
					<li>
						<a href="#">为什么登录失败？</a>
					</li>
				</ul>
				<div class="login_inner">
					<p>您可以使用 网易网盘 ，随时存，随地取</p>
					<p>您还可以使用 闪电邮 在桌面随时提醒邮件到达，快速收发邮件。</p>
					<p>在 百宝箱 里您可以查星座，订机票，看小说，学做菜…</p>
				</div>
			</div>
		</div>
		<div id="login_footer">Copyright &copy; 2009 www.dwzjs.com Inc. All
			Rights Reserved.</div>
	</div>
</body>
</html>