<?php

require_once('../Init.php');

$iStart = PRIVATE_AUTO_INCREMENT + 1;
$sSql = <<<EOD
DROP TABLE if exists `sys_user_grp`;
CREATE TABLE `sys_user_grp` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `iParentPk` int(11) DEFAULT NULL,
  `iRolePk` int(11) DEFAULT NULL,
  `sName` varchar(50) DEFAULT NULL,
  `sRemark` varchar(100) DEFAULT NULL,
  `iLftVal` int(11) DEFAULT NULL,
  `iRgtVal` int(11) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE if exists `sys_user_grp`;
CREATE TABLE `sys_user_grp` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `iParentPk` int(11) DEFAULT NULL,
  `iRolePk` int(11) DEFAULT NULL,
  `sName` varchar(50) DEFAULT NULL,
  `sRemark` varchar(100) DEFAULT NULL,
  `iLftVal` int(11) DEFAULT NULL,
  `iRgtVal` int(11) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE if exists `sys_table_join`;
CREATE TABLE `sys_table_join` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `iMainTablePk` int(11) DEFAULT NULL,
  `sName` varchar(50) DEFAULT NULL,
  `sJoin` varchar(20) DEFAULT NULL COMMENT '关联方式：left,inner。',
  `sJoinMainField` varchar(50) DEFAULT NULL COMMENT '关联字段,记录主表的字段',
  `sJoinWhere` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	
DROP TABLE if exists `sys_table_field`;
CREATE TABLE `sys_table_field` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `iTablePk` int(50) DEFAULT NULL,
  `sTable` varchar(50) DEFAULT NULL COMMENT '如果是主表字段存空，关联表字段存关联表表名',
  `sName` varchar(50) DEFAULT NULL COMMENT '如果是关联的字段，则记录格式为alias.field',
  `sDisplay` varchar(50) DEFAULT NULL,
  `sType` varchar(20) DEFAULT NULL COMMENT '字段类型：int,string,text,date,select',
  `bRequired` int(11) DEFAULT '1' COMMENT '是否必填',
  `bDisplay` int(11) DEFAULT '1' COMMENT '是否显示',
  `bSearch` int(11) DEFAULT NULL,
  `iWidth` int(11) DEFAULT NULL,
  `iSort` int(11) DEFAULT NULL,
  `sJoinField` varchar(50) DEFAULT NULL,
  `sComboxData` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		
DROP TABLE if exists `sys_table`;
CREATE TABLE `sys_table` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(50) DEFAULT NULL,
  `sKeyField` varchar(50) DEFAULT NULL,
  `sSnPre` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		
DROP TABLE if exists `sys_role`;
CREATE TABLE `sys_role` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(20) DEFAULT NULL,
  `sMemo` varchar(255) DEFAULT NULL,
  `sAuthorityCodes` text,
  `sRangeCodes` text,
  PRIMARY KEY (`iPk`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		
DROP TABLE if exists `sys_menu_class`;
CREATE TABLE `sys_menu_class` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		
DROP TABLE if exists `sys_menu`;
CREATE TABLE `sys_menu` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `iClassPk` int(11) DEFAULT NULL,
  `sName` varchar(20) DEFAULT NULL,
  `sApp` varchar(20) DEFAULT NULL,
  `sAppDetail` varchar(20) DEFAULT NULL,
  `sPage` varchar(30) DEFAULT NULL,
  `iTablePk` int(11) DEFAULT NULL,
  `sButton` text COMMENT '拥有的按钮权限。逗号分隔存储按钮的Code',
  `bDisplay` int(11) DEFAULT NULL,
  `iSort` int(11) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		
DROP TABLE if exists `sys_log_table`;
CREATE TABLE `sys_log_table` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `sTableName` varchar(20) DEFAULT NULL,
  `sKeyField` varchar(10) DEFAULT NULL,
  `sApp` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		
DROP TABLE if exists `sys_log_row`;
CREATE TABLE `sys_log_row` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `iLogTablePk` int(11) DEFAULT NULL,
  `sKeyValue` varchar(10) DEFAULT NULL,
  `iOperation` int(2) DEFAULT NULL COMMENT '1:insert 2:update 3:delete',
  `sJsonContent` text,
  `sCreateUser` varchar(20) DEFAULT NULL,
  `dtCreateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		
DROP TABLE if exists `sys_button`;
CREATE TABLE `sys_button` (
  `sCode` varchar(10) NOT NULL DEFAULT '',
  `sName` varchar(20) DEFAULT NULL,
  `sImage` varchar(50) DEFAULT NULL,
  `sType` varchar(10) DEFAULT NULL COMMENT 'row:行上的操作按钮，table:表格上的操作按钮',
  `sTarget` varchar(10) DEFAULT NULL,
  `iSort` int(11) DEFAULT NULL,
  PRIMARY KEY (`sCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		
DROP TABLE if exists `sys_user_grp`;
CREATE TABLE `sys_authority` (
  `iPk` int(11) NOT NULL AUTO_INCREMENT,
  `iRolePK` int(11) DEFAULT NULL,
  `iGrpPK` int(11) DEFAULT NULL,
  `iUserPK` int(11) DEFAULT NULL,
  PRIMARY KEY (`iPk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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
