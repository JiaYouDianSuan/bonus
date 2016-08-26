TRUNCATE TABLE `sys_table`;
Insert Into sys_table (iPk,sName,sKeyField) values ('1','sys_table','iPk'),('2','sys_button','iPk'),('3','sys_menu_class','iPk'),('4','sys_menu','iPk'),('5','sys_user_grp','iPk'),('6','sys_user','iPk'),('7','sys_role','iPk');
ALTER TABLE `sys_table` AUTO_INCREMENT=101;
TRUNCATE TABLE `sys_table_field`;
Insert Into sys_table_field (iTablePk,sTable,sName,sDisplay,sType,bRequired,bDisplay,bSearch,iSort,sJoinField,sComboxData) values ('1','sys_table','iPk','','Checkbox','0','0','0','0','',''),('1','sys_table','sName','表名','Text','1','1','0','1','',''),('1','sys_table','sKeyField','主键','Text','1','1','0','2','',''),('1','sys_table','sSnPre','自增流水前缀','Text','0','1','0','3','',''),('2','sys_button','sCode','按钮代码','Text','1','1','0','2','',''),('2','sys_button','sTarget','弹出类型','Combox','1','1','0','3','','{"dialog":"对话框","ajaxTodo":"Ajax","dwzExport":"导出"}'),('2','sys_button','sName','操作名称','Text','1','1','0','1','',''),('2','sys_button','sImage','图片','Text','1','1','0','5','',''),('2','sys_button','sType','操作类型','Combox','1','1','0','4','','{"Table":"表","Row":"行"}'),('2','sys_button','iSort','排序','Text','0','1','0','6','',''),('2','sys_button','iPk','','Text','0','0','0','0','',''),('3','sys_menu_class','iPk','','Checkbox','0','0','0','0','',''),('3','sys_menu_class','sName','菜单分类名称','Text','1','1','0','0','',''),('4','sys_menu','iPk','','Checkbox','0','0','0','0','',''),('4','sys_menu','iTablePk','关联数据库表','Relate','1','1','0','6','sys_table.sName',''),('4','sys_menu','sApp','App','Text','1','1','0','3','',''),('4','sys_menu','sAppDetail','App_Detail','Text','0','1','0','4','',''),('4','sys_menu','iClassPk','菜单分类','Relate','1','1','1','1','sys_menu_class.sName',''),('4','sys_menu','sPage','Page','Text','1','1','0','5','',''),('4','sys_menu','sName','菜单名','Text','1','1','1','2','',''),('4','sys_menu','bDisplay','显示状态','Combox','1','1','0','7','','{"1":"显示","0":"隐藏"}'),('4','sys_menu','iSort','排序','Text','0','0','0','8','',''),('4','sys_menu','sButton','操作按钮','Checkbox','0','1','0','9','sys_button.sName',''),('5','sys_user_grp','iPk','','Checkbox','0','0','0','0','',''),('5','sys_user_grp','sName','名称','Text','1','1','0','1','',''),('5','sys_user_grp','iRolePk','角色','Relate','0','1','0','3','sys_role.sName',''),('5','sys_user_grp','iLftVal','','Text','1','0','0','0','',''),('5','sys_user_grp','iRgtVal','','Text','1','0','0','0','',''),('5','sys_user_grp','sRemark','备注','Text','0','1','0','4','',''),('6','sys_user','iPk','','Checkbox','0','0','0','0','',''),('6','sys_user','sId','账号','KeyField','1','1','0','1','',''),('6','sys_user','sName','名称','Text','1','1','0','3','',''),('6','sys_user','iGrpPk','用户组','Relate','1','1','0','4','sys_user_grp.sName',''),('6','sys_user','iRolePk','角色','Relate','0','1','0','5','sys_role.sName',''),('6','sys_user','sRemark','备注','Text','0','1','0','6','',''),('7','sys_role','iPk','','Checkbox','0','0','0','0','',''),('7','sys_role','sName','角色名','Text','1','1','0','1','',''),('7','sys_role','sAuthorityCodes','权限代码','Text','1','1','0','3','',''),('7','sys_role','sMemo','备注','Text','0','1','0','2','','');
ALTER TABLE `sys_table_field` AUTO_INCREMENT=101;
TRUNCATE TABLE `sys_table_join`;
Insert Into sys_table_join (iPk,iMainTablePk,sName,sJoin,sJoinMainField,sJoinWhere) values ('1','4','sys_table','Left','iTablePk',''),('2','4','sys_menu_class','Left','iClassPk',''),('3','4','sys_button','Left','sButton',''),('4','5','sys_role','Left','iRolePk',''),('5','6','sys_user_grp','Left','iGrpPk',''),('6','6','sys_role','Left','iRolePk','');
ALTER TABLE `sys_table_join` AUTO_INCREMENT=101;
TRUNCATE TABLE `sys_menu_class`;
Insert Into sys_menu_class (iPk,sName) values ('1','功能管理'),('2','系统配置');
ALTER TABLE `sys_menu_class` AUTO_INCREMENT=101;
TRUNCATE TABLE `sys_menu`;
Insert Into sys_menu (iPk,iClassPk,sName,sApp,sPage,iTablePk,sButton,bDisplay,iSort) values ('1','1','表结构','SysTable','Grid','1','Add,Edit,Del','1','0'),('2','1','按钮','SysButton','Grid','2','Add,Edit,Del,Search','1','0'),('3','2','菜单分类','SysMenuClass','Grid','3','Add,Edit,Del','1','0'),('4','2','菜单','SysMenu','Grid','4','Add,Edit,Del,Search','1','0'),('5','2','用户组','SysUserGrp','HFrame_TreeForm','5','Add,Edit,Del','1','0'),('6','2','用户','SysUser','Grid','6','Add,Edit,Del,Search','1','0'),('7','2','角色','SysRole','Grid','7','Add,Edit,Del,Search','1','0');
ALTER TABLE `sys_menu` AUTO_INCREMENT=101;
TRUNCATE TABLE `sys_button`;
Insert Into sys_button (iPk,sCode,sName,sImage,sType,sTarget,iSort) values ('1','Edit','修改','Edit','Row','dialog','2'),('2','Add','新增','Add','Table','dialog','1'),('3','Del','删除','Delete','Row','ajaxTodo','99'),('4','Search','搜索','Search','Table','dialog','6'),('5','Confirm','审核','Confirm','Row','ajaxTodo','5'),('6','View','详细','View','Row','dialog','3'),('7','Export','导出','Export','Table','dwzExport','7');
ALTER TABLE `sys_button` AUTO_INCREMENT=101;
TRUNCATE TABLE `sys_role`;
Insert Into sys_role (iPk,sName,sMemo,sAuthorityCodes) values ('1','系统管理员','','All'),('2','普通用户','','{"Authority":{"BusApplyDb":["Add","Edit","View","Confirm","Del","Search","Export"]},"Range":{"read":{"BusApplyDb":"All"},"edit":{"BusApplyDb":"All"}}}');
ALTER TABLE `sys_role` AUTO_INCREMENT=101;
TRUNCATE TABLE `sys_user`;
Insert Into sys_user (iPk,iGrpPk,iRolePk,sId,sPassword,sName,sRemark) values ('1','0','1','admin','e3ceb5881a0a1fdaad01296d7554868d','系统管理员','');
ALTER TABLE `sys_user` AUTO_INCREMENT=101;
TRUNCATE TABLE `sys_user_grp`;
Insert Into sys_user_grp (iPk,iParentPk,iRolePk,sName,sRemark,iLftVal,iRgtVal) values ('1','0','2','全部','','1','2');
ALTER TABLE `sys_user_grp` AUTO_INCREMENT=101;
Insert Into `sys_menu_class` (`sName`) Values ('业务');
