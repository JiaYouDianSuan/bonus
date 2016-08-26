<?PHP
$JFrame = new Frame();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=7" />
        <title>简单实用国产jQuery UI框架 - DWZ富客户端框架(J-UI.com)</title>

        <link href="Ui/Dwz/themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="Ui/Dwz/themes/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="Ui/Dwz/themes/css/print.css" rel="stylesheet" type="text/css" media="print"/>
        <link href="Ui/Dwz/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
        <!--[if IE]>
        <link href="themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
        <![endif]-->

        <!--[if lte IE 9]>
        <script src="js/speedup.js" type="text/javascript"></script>
        <![endif]-->
        <script src="Ui/Dwz/js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/jquery.cookie.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/jquery.validate.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/jquery.bgiframe.js" type="text/javascript"></script>

        <script src="Ui/Dwz/uploadify/scripts/jquery.uploadify.js" type="text/javascript"></script>

        <!-- svg图表  supports Firefox 3.0+, Safari 3.0+, Chrome 5.0+, Opera 9.5+ and Internet Explorer 6.0+ -->
        <script type="text/javascript" src="Ui/Dwz/chart/raphael.js"></script>
        <script type="text/javascript" src="Ui/Dwz/chart/g.raphael.js"></script>
        <script type="text/javascript" src="Ui/Dwz/chart/g.bar.js"></script>
        <script type="text/javascript" src="Ui/Dwz/chart/g.line.js"></script>
        <script type="text/javascript" src="Ui/Dwz/chart/g.pie.js"></script>
        <script type="text/javascript" src="Ui/Dwz/chart/g.dot.js"></script>

        <script src="Ui/Dwz/js/dwz.core.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.util.date.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.validate.method.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.regional.zh.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.barDrag.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.drag.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.tree.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.accordion.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.ui.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.theme.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.switchEnv.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.alertMsg.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.contextmenu.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.navTab.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.tab.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.resize.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.dialog.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.dialogDrag.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.sortDrag.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.cssTable.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.stable.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.taskBar.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.ajax.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.pagination.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.database.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.datepicker.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.effects.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.panel.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.checkbox.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.history.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.combox.js" type="text/javascript"></script>
        <script src="Ui/Dwz/js/dwz.print.js" type="text/javascript"></script>
        <!--
        <script src="bin/dwz.min.js" type="text/javascript"></script>
        -->
        <script src="Ui/Dwz/js/dwz.regional.zh.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(function() {
                DWZ.init("Ui/Dwz/dwz.frag.xml", {
                    loginUrl: "login_dialog.html", loginTitle: "登录", // 弹出登录对话框
                    //		loginUrl:"login.html",	// 跳到登录页面
                    statusCode: {ok: 200, error: 300, timeout: 301}, //【可选】
                    pageInfo: {pageNum: "pageNum", numPerPage: "numPerPage", orderField: "orderField", orderDirection: "orderDirection"}, //【可选】
                    debug: false, // 调试模式 【true|false】
                    callback: function() {
                        initEnv();
                        $("#themeList").theme({themeBase: "Ui/Dwz/themes"}); // themeBase 相对于index页面的主题base路径
                    }
                });
            });
        </script>
    </head>

    <body scroll="no" >        
        <div id="layout">
            <div id="header">
                <div class="headerNav">                    
                    <a class="logo" href="http://j-ui.com">标志</a>                    
                    <ul class="nav">                    
                        <li style='padding-top: 2px;'><a style='TEXT-DECORATION:none; cursor:default;'><?php echo $_SESSION['login']['name']; ?> 欢迎您</a></li>
                        <li style='padding-top: 2px;'><a href="ChangePassword.php" target="dialog" width="600">设置</a></li>                                                                        
                        <li style='padding-top: 2px;'><a href="Logout.php">退出</a></li>
                    </ul>
                    <ul class="themeList" id="themeList">
                        <li theme="default"><div class="selected">蓝色</div></li>
                        <li theme="green"><div>绿色</div></li>
                        <!--<li theme="red"><div>红色</div></li>-->
                        <li theme="purple"><div>紫色</div></li>
                        <li theme="silver"><div>银色</div></li>
                        <li theme="azure"><div>天蓝</div></li>
                    </ul>
                </div>
            </div>
            <?PHP echo $JFrame->show(); ?>
        </div>
        <div id="footer">Copyright &copy; 2010 <a href="demo_page2.html" target="dialog">DWZ团队</a> Tel：010-52897073</div>
    </body>
</html>
<script>
    function dialogAjaxDone(json) {
        DWZ.ajaxDone(json);
        if (json.statusCode == DWZ.statusCode.ok) {            
            if (json.navTabId) {                
                navTab.reload(json.forwardUrl, {}, json.navTabId);                
            }
            $.pdialog._current!=null && $.pdialog.closeCurrent();
        }
    }
</script>