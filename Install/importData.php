<?php

require_once('../Init.php');

$iStart = PRIVATE_AUTO_INCREMENT + 1;
$sSql = <<<EOD
TRUNCATE TABLE `sys_table`;
INSERT INTO `sys_table` (`iPk`, `sName`, `sKeyField`) VALUES (1, 'sys_table', 'iPk');
INSERT INTO `sys_table` (`iPk`, `sName`, `sKeyField`) VALUES (2, 'sys_button', 'sCode');
INSERT INTO `sys_table` (`iPk`, `sName`, `sKeyField`) VALUES (3, 'sys_menu_class', 'iPk');
INSERT INTO `sys_table` (`iPk`, `sName`, `sKeyField`) VALUES (4, 'sys_menu', 'iPk');
INSERT INTO `sys_table` (`iPk`, `sName`, `sKeyField`) VALUES (5, 'sys_user_grp', 'iPk');
INSERT INTO `sys_table` (`iPk`, `sName`, `sKeyField`) VALUES (6, 'sys_user', 'iPk');
INSERT INTO `sys_table` (`iPk`, `sName`, `sKeyField`) VALUES (7, 'sys_role', 'iPk');
ALTER TABLE `sys_table` AUTO_INCREMENT=$iStart;

TRUNCATE TABLE `sys_table_field`;
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (1, 'sys_table', 'iPk', '', 'Checkbox', 0, 0, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (1, 'sys_table', 'sName', '表名', 'Text', 1, 1, 0, 1, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (1, 'sys_table', 'sKeyField', '主键', 'Text', 1, 1, 0, 2, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (1, 'sys_table', 'sSnPre', '自增流水前缀', 'Text', 1, 1, 0, 3, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (2, 'sys_button', 'sCode', '按钮代码', 'Text', 1, 1, 0, 2, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (2, 'sys_button', 'sTarget', '弹出类型', 'Combox', 1, 1, 0, 3, '', '{\"dialog\":\"对话框\",\"ajaxTodo\":\"Ajax\"}');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (2, 'sys_button', 'sName', '操作名称', 'Text', 1, 1, 0, 1, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (2, 'sys_button', 'sImage', '图片', 'Text', 1, 1, 0, 5, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (2, 'sys_button', 'sType', '操作类型', 'Combox', 1, 1, 0, 4, '', '{\"Table\":\"表\",\"Row\":\"行\"}');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (2, 'sys_button', 'iSort', '排序', 'Text', 0, 1, 0, 6, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (3, 'sys_menu_class', 'iPk', '', 'Checkbox', 0, 0, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (3, 'sys_menu_class', 'sName', '菜单分类名称', 'Text', 1, 1, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'iPk', '', 'Checkbox', 0, 0, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'iTablePk', '关联数据库表', 'Relate', 1, 1, 0, 6, 'sys_table.sName', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'sApp', 'App', 'Text', 1, 1, 0, 3, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'sAppDetail', 'App_Detail', 'Text', 0, 1, 0, 4, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'iClassPk', '菜单分类', 'Relate', 1, 1, 1, 1, 'sys_menu_class.sName', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'sPage', 'Page', 'Text', 1, 1, 0, 5, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'sName', '菜单名', 'Text', 1, 1, 1, 2, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'bDisplay', '显示状态', 'Combox', 1, 1, 0, 7, '', '{\"1\":\"显示\",\"0\":\"隐藏\"}');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'iSort', '排序', 'Text', 0, 0, 0, 8, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (4, 'sys_menu', 'sButton', '操作按钮', 'Checkbox', 0, 1, 0, 9, 'sys_button.sName', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (5, 'sys_user_grp', 'iPk', '', 'Checkbox', 0, 0, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (5, 'sys_user_grp', 'iParentPk', '父节点', 'Text', 0, 0, 0, 2, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (5, 'sys_user_grp', 'sName', '名称', 'Text', 1, 1, 0, 1, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (5, 'sys_user_grp', 'iRolePk', '角色', 'Relate', 0, 1, 0, 3, 'sys_role.sName', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (5, 'sys_user_grp', 'iLftVal', '左值', 'Text', 1, 0, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (5, 'sys_user_grp', 'iRgtVal', '右值', 'Text', 1, 0, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (5, 'sys_user_grp', 'sRemark', '备注', 'Text', 0, 1, 0, 4, '', '');      
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (6, 'sys_user', 'iPk', '', 'Checkbox', 0, 0, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (6, 'sys_user', 'sId', '账号', 'Text', 1, 1, 0, 1, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (6, 'sys_user', 'sPassword', '密码', 'Text', 1, 1, 0, 2, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (6, 'sys_user', 'sName', '名称', 'Text', 1, 1, 0, 3, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (6, 'sys_user', 'iGrpPk', '用户组', 'Relate', 1, 1, 0, 4, 'sys_user_grp.sName', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (6, 'sys_user', 'iRolePk', '角色', 'Relate', 0, 1, 0, 5, 'sys_role.sName', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (6, 'sys_user', 'sRemark', '备注', 'Text', 0, 1, 0, 6, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (7, 'sys_role', 'iPk', '', 'Checkbox', 0, 0, 0, 0, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (7, 'sys_role', 'sName', '角色名', 'Text', 1, 1, 0, 1, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (7, 'sys_role', 'sAuthorityCodes', '权限代码', 'Text', 1, 1, 0, 3, '', '');
INSERT INTO `sys_table_field` (`iTablePk`, `sTable`, `sName`, `sDisplay`, `sType`, `bRequired`, `bDisplay`, `bSearch`, `iSort`, `sJoinField`, `sComboxData`) VALUES (7, 'sys_role', 'sMemo', '备注', 'Text', 0, 1, 0, 2, '', '');
ALTER TABLE `sys_table_field` AUTO_INCREMENT=$iStart;
        
TRUNCATE TABLE `sys_table_join`;
INSERT INTO `sys_table_join` (`iPk`, `iMainTablePk`, `sName`, `sJoin`, `sJoinMainField`, `sJoinWhere`) VALUES (1, 4, 'sys_table', 'Left', 'iTablePk', '');
INSERT INTO `sys_table_join` (`iPk`, `iMainTablePk`, `sName`, `sJoin`, `sJoinMainField`, `sJoinWhere`) VALUES (2, 4, 'sys_menu_class', 'Left', 'iClassPk', '');
INSERT INTO `sys_table_join` (`iPk`, `iMainTablePk`, `sName`, `sJoin`, `sJoinMainField`, `sJoinWhere`) VALUES (3, 4, 'sys_button', 'Left', 'sButton', '');
INSERT INTO `sys_table_join` (`iPk`, `iMainTablePk`, `sName`, `sJoin`, `sJoinMainField`, `sJoinWhere`) VALUES (4, 5, 'sys_role', 'Left', 'iRolePk', '');
INSERT INTO `sys_table_join` (`iPk`, `iMainTablePk`, `sName`, `sJoin`, `sJoinMainField`, `sJoinWhere`) VALUES (5, 6, 'sys_user_grp', 'Left', 'iGrpPk', '');
INSERT INTO `sys_table_join` (`iPk`, `iMainTablePk`, `sName`, `sJoin`, `sJoinMainField`, `sJoinWhere`) VALUES (6, 6, 'sys_role', 'Left', 'iRolePk', '');
ALTER TABLE `sys_table_join` AUTO_INCREMENT=$iStart;
    
TRUNCATE TABLE `sys_menu_class`;
INSERT INTO `sys_menu_class` (`iPk`, `sName`) VALUES (1, '功能管理');
INSERT INTO `sys_menu_class` (`iPk`, `sName`) VALUES (2, '系统配置');
INSERT INTO `sys_menu_class` (`iPk`, `sName`) VALUES (101, '业务');
ALTER TABLE `sys_menu_class` AUTO_INCREMENT=$iStart;

TRUNCATE TABLE `sys_menu`;
INSERT INTO `sys_menu` (`iPk`, `iClassPk`, `sName`, `sApp`, `sPage`, `iTablePk`, `sButton`, `bDisplay`, `iSort`) VALUES (1, 1, '表结构', 'SysTable', 'Grid', 1, 'Add,Edit,Del', 1, NULL);
INSERT INTO `sys_menu` (`iPk`, `iClassPk`, `sName`, `sApp`, `sPage`, `iTablePk`, `sButton`, `bDisplay`, `iSort`) VALUES (2, 1, '按钮', 'SysButton', 'Grid', 2, 'Add,Edit,Del,Search', 1,  NULL);
INSERT INTO `sys_menu` (`iPk`, `iClassPk`, `sName`, `sApp`, `sPage`, `iTablePk`, `sButton`, `bDisplay`, `iSort`) VALUES (3, 2, '菜单分类', 'SysMenuClass', 'Grid', 3, 'Add,Edit,Del', 1,  NULL);
INSERT INTO `sys_menu` (`iPk`, `iClassPk`, `sName`, `sApp`, `sPage`, `iTablePk`, `sButton`, `bDisplay`, `iSort`) VALUES (4, 2, '菜单', 'SysMenu', 'Grid', 4, 'Add,Edit,Del,Search', 1,  NULL);
INSERT INTO `sys_menu` (`iPk`, `iClassPk`, `sName`, `sApp`, `sPage`, `iTablePk`, `sButton`, `bDisplay`, `iSort`) VALUES (5, 2, '用户组', 'SysUserGrp', 'HFrame_TreeForm', 5, 'Add,Edit,Del', 1,  NULL);
INSERT INTO `sys_menu` (`iPk`, `iClassPk`, `sName`, `sApp`, `sPage`, `iTablePk`, `sButton`, `bDisplay`, `iSort`) VALUES (6, 2, '用户', 'SysUser', 'Grid', 6, 'Add,Edit,Del,Search', 1,  NULL);
INSERT INTO `sys_menu` (`iPk`, `iClassPk`, `sName`, `sApp`, `sPage`, `iTablePk`, `sButton`, `bDisplay`, `iSort`) VALUES (7, 2, '角色', 'SysRole', 'Grid', 7, 'Add,Edit,Del,Search', 1,  NULL);
ALTER TABLE `sys_menu` AUTO_INCREMENT=$iStart;

TRUNCATE TABLE `sys_role`;
INSERT INTO `sys_role` (`iPk`, `sName`, `sMemo`, `sAuthorityCodes`) VALUES (1, '系统管理员', NULL, 'All');        

TRUNCATE TABLE `sys_user`;
INSERT INTO `sys_user` (`iPk`, `iGrpPk`, `iRolePk`, `sId`, `sPassword`, `sName`, `sRemark`) VALUES (1, NULL, 1, 'admin', 'admin', '系统管理员', '');

TRUNCATE TABLE `sys_button`;
INSERT INTO `sys_button` VALUES ('Edit', '修改', 'Edit.gif', 'Row', 'dialog', 2);
INSERT INTO `sys_button` VALUES ('Add', '新增', 'Add.gif', 'Table', 'dialog', 1);
INSERT INTO `sys_button` VALUES ('Del', '删除', 'Delete.gif', 'Row', 'ajaxTodo', 5);
INSERT INTO `sys_button` VALUES ('Search', '搜索', 'Search.gif', 'Table', 'dialog', 6);
INSERT INTO `sys_button` VALUES ('Confirm', '审核', 'Confirm.gif', 'Row', 'ajaxTodo', 4);
INSERT INTO `sys_button` VALUES ('View', '详细', 'View.gif', 'Row', 'dialog', 3);
INSERT INTO `sys_button` VALUES ('Export', '导出', 'Export.gif', 'Table', 'dwzExport', 7);

EOD;

$arrSql = explode(';', $sSql);
foreach ($arrSql as $v) {
    $sSql = trim($v);
    if ($sSql != '') {
        Database::create()->query($sSql);        
    }
}

//echo $sSql;
?>
