/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50508
Source Host           : localhost:3306
Source Database       : tradewifi

Target Server Type    : MYSQL
Target Server Version : 50508
File Encoding         : 65001

Date: 2014-03-19 14:51:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `wifi_access`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_access`;
CREATE TABLE `wifi_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `wifi_agentpushset`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_agentpushset`;
CREATE TABLE `wifi_agentpushset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0',
  `pushflag` tinyint(4) DEFAULT NULL COMMENT '是否启用广告推送',
  `showtime` int(11) DEFAULT '3',
  `add_time` varchar(20) DEFAULT NULL,
  `update_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for `wifi_member`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_member`;
CREATE TABLE `wifi_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(30) NOT NULL DEFAULT '' COMMENT '帐号',
  `password` varchar(50) DEFAULT NULL COMMENT '密码',
  `mode` varchar(10) DEFAULT NULL COMMENT '认证模式 根据认证表ID 注册认证，手机认证，qq认证，微博认证等',
  `shopid` int(11) DEFAULT NULL COMMENT '帐号ID',
  `routeid` int(11) DEFAULT NULL COMMENT '路由ID',
  `token` varchar(100) DEFAULT NULL COMMENT '使用口令',
  `phone` varchar(20) DEFAULT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `mac` varchar(50) DEFAULT NULL COMMENT 'mac地址',
  `login_time` varchar(30) DEFAULT NULL COMMENT '路由登录时间',
  `login_count` int(11) DEFAULT '0' COMMENT '路由登录次数',
  `login_ip` varchar(50) DEFAULT NULL,
  `add_time` varchar(20) DEFAULT NULL,
  `update_time` varchar(20) DEFAULT NULL,
  `browser` varchar(20) DEFAULT NULL,
  `online_time` varchar(20) DEFAULT NULL COMMENT '在线有效期',
  `add_date` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`user`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wifi_authlist`;
CREATE TABLE `wifi_authlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `shopid` int(11) DEFAULT NULL,
  `routeid` int(11) DEFAULT NULL,
  `mode` tinyint(4) DEFAULT NULL COMMENT '认证模式',
  `mac` varchar(50) DEFAULT NULL,
  `add_date` varchar(50) DEFAULT NULL COMMENT '日期',
  `pingcount` int(4) DEFAULT '0' COMMENT '检测链接次数',
  `login_time` varchar(50) DEFAULT NULL COMMENT '登录时间',
  `login_ip` varchar(20) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL COMMENT '浏览器',
  `agent` varchar(100) DEFAULT NULL COMMENT '机器消息',
  `over_time` varchar(50) NOT NULL COMMENT '允许在线时长',
  `update_time` varchar(50) DEFAULT NULL,
  `last_time` varchar(50) DEFAULT NULL COMMENT '最后在线时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `wifi_ad`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_ad`;
CREATE TABLE `wifi_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '上传作者ID',
  `ad_pos` tinyint(4) NOT NULL DEFAULT '0' COMMENT '广告位置 0首页 ',
  `ad_thumb` varchar(50) DEFAULT NULL COMMENT '广告缩略图',
  `ad_sort` int(11) DEFAULT NULL COMMENT '广告排序',
  `title` varchar(255) DEFAULT NULL,
  `info` text,
  `mode` tinyint(4) DEFAULT '0' COMMENT '0：图片 1 图文 2 链接',
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `ad_show` int(11) DEFAULT '0' COMMENT '广告展示次数',
  `ad_hit` int(11) DEFAULT '0' COMMENT '广告点击次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='广告表';


-- ----------------------------
-- Records of wifi_ad
-- ----------------------------
DROP TABLE IF EXISTS `wifi_hours`;
CREATE TABLE `wifi_hours` (
  `t` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_hours
-- ----------------------------
INSERT INTO `wifi_hours` VALUES ('00');
INSERT INTO `wifi_hours` VALUES ('01');
INSERT INTO `wifi_hours` VALUES ('02');
INSERT INTO `wifi_hours` VALUES ('03');
INSERT INTO `wifi_hours` VALUES ('04');
INSERT INTO `wifi_hours` VALUES ('05');
INSERT INTO `wifi_hours` VALUES ('06');
INSERT INTO `wifi_hours` VALUES ('07');
INSERT INTO `wifi_hours` VALUES ('08');
INSERT INTO `wifi_hours` VALUES ('09');
INSERT INTO `wifi_hours` VALUES ('10');
INSERT INTO `wifi_hours` VALUES ('11');
INSERT INTO `wifi_hours` VALUES ('12');
INSERT INTO `wifi_hours` VALUES ('13');
INSERT INTO `wifi_hours` VALUES ('14');
INSERT INTO `wifi_hours` VALUES ('15');
INSERT INTO `wifi_hours` VALUES ('16');
INSERT INTO `wifi_hours` VALUES ('17');
INSERT INTO `wifi_hours` VALUES ('18');
INSERT INTO `wifi_hours` VALUES ('19');
INSERT INTO `wifi_hours` VALUES ('20');
INSERT INTO `wifi_hours` VALUES ('21');
INSERT INTO `wifi_hours` VALUES ('22');
INSERT INTO `wifi_hours` VALUES ('23');


DROP TABLE IF EXISTS `wifi_month`;
CREATE TABLE `wifi_month` (
  `id` int(11) NOT NULL,
  `mon` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_month
-- ----------------------------
INSERT INTO `wifi_month` VALUES ('1', '01');
INSERT INTO `wifi_month` VALUES ('2', '02');
INSERT INTO `wifi_month` VALUES ('3', '03');
INSERT INTO `wifi_month` VALUES ('4', '04');
INSERT INTO `wifi_month` VALUES ('5', '05');
INSERT INTO `wifi_month` VALUES ('6', '06');
INSERT INTO `wifi_month` VALUES ('7', '07');
INSERT INTO `wifi_month` VALUES ('8', '08');
INSERT INTO `wifi_month` VALUES ('9', '09');
INSERT INTO `wifi_month` VALUES ('10', '10');
INSERT INTO `wifi_month` VALUES ('11', '11');
INSERT INTO `wifi_month` VALUES ('12', '12');

DROP TABLE IF EXISTS `wifi_day`;
CREATE TABLE `wifi_day` (
  `id` int(11) NOT NULL,
  `tname` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_day
-- ----------------------------
INSERT INTO `wifi_day` VALUES ('0', '00');
INSERT INTO `wifi_day` VALUES ('1', '01');
INSERT INTO `wifi_day` VALUES ('2', '02');
INSERT INTO `wifi_day` VALUES ('3', '03');
INSERT INTO `wifi_day` VALUES ('4', '04');
INSERT INTO `wifi_day` VALUES ('5', '05');
INSERT INTO `wifi_day` VALUES ('6', '06');
INSERT INTO `wifi_day` VALUES ('7', '07');
INSERT INTO `wifi_day` VALUES ('8', '08');
INSERT INTO `wifi_day` VALUES ('9', '09');
INSERT INTO `wifi_day` VALUES ('10', '10');
INSERT INTO `wifi_day` VALUES ('11', '11');
INSERT INTO `wifi_day` VALUES ('12', '12');
INSERT INTO `wifi_day` VALUES ('13', '13');
INSERT INTO `wifi_day` VALUES ('14', '14');
INSERT INTO `wifi_day` VALUES ('15', '15');
INSERT INTO `wifi_day` VALUES ('16', '16');
INSERT INTO `wifi_day` VALUES ('17', '17');
INSERT INTO `wifi_day` VALUES ('18', '18');
INSERT INTO `wifi_day` VALUES ('19', '19');
INSERT INTO `wifi_day` VALUES ('20', '20');
INSERT INTO `wifi_day` VALUES ('21', '21');
INSERT INTO `wifi_day` VALUES ('22', '22');
INSERT INTO `wifi_day` VALUES ('23', '23');
INSERT INTO `wifi_day` VALUES ('24', '24');
INSERT INTO `wifi_day` VALUES ('25', '25');
INSERT INTO `wifi_day` VALUES ('26', '26');
INSERT INTO `wifi_day` VALUES ('27', '27');
INSERT INTO `wifi_day` VALUES ('28', '28');
INSERT INTO `wifi_day` VALUES ('29', '29');
INSERT INTO `wifi_day` VALUES ('30', '30');
INSERT INTO `wifi_day` VALUES ('31', '31');
-- ----------------------------
-- Table structure for `wifi_adcount`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_adcount`;
CREATE TABLE `wifi_adcount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) DEFAULT NULL,
  `aid` int(11) DEFAULT NULL COMMENT '广告ID',
  `showup` int(11) DEFAULT NULL,
  `hit` int(11) DEFAULT NULL,
  `add_time` varchar(20) DEFAULT NULL,
  `add_date` varchar(20) DEFAULT NULL,
  `mode` tinyint(4) DEFAULT NULL COMMENT '类型  1 商户广告统计  99 运营商投放广告 ',
  `agent` varchar(100) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `browser` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3102 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `wifi_admin`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_admin`;
CREATE TABLE `wifi_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` int(11) DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '0:停用 1 正常',
  `last_loginip` varchar(255) DEFAULT NULL,
  `last_logintime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_admin
-- ----------------------------
INSERT INTO `wifi_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '0', null, '1389750196', '1393145054', '1', '127.0.0.1', '1395125445');


-- ----------------------------
-- Table structure for `wifi_agent`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_agent`;
CREATE TABLE `wifi_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `linker` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `info` text,
  `money` decimal(11,2) DEFAULT '0.00' COMMENT '金额',
  `fee` decimal(11,2) DEFAULT '0.00' COMMENT '代理费',
  `level` int(11) DEFAULT NULL COMMENT '等级',
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `point_x` varchar(255) DEFAULT NULL,
  `point_y` varchar(255) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '0:停用 1：启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_agent
-- ----------------------------
INSERT INTO `wifi_agent` VALUES ('1', 'xc', 'e10adc3949ba59abbe56e057f20f883e', '11222', '13705094040', '平台管理员', null, '28000.00', '2.00', '1', '内蒙古自治区', '呼和浩特市', '玉泉区', '', null, null, '1389337345', '1390032691', '1');

-- ----------------------------
-- Table structure for `wifi_agentlevel`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_agentlevel`;
CREATE TABLE `wifi_agentlevel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `openpay` decimal(10,2) DEFAULT '0.00' COMMENT '开户金额',
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_agentlevel
-- ----------------------------
INSERT INTO `wifi_agentlevel` VALUES ('1', '普通代理', null, '8000.00', '1390032691', '1390032691', '1');

-- ----------------------------
-- Table structure for `wifi_agentpay`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_agentpay`;
CREATE TABLE `wifi_agentpay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT NULL COMMENT '代理商ID',
  `do` tinyint(4) DEFAULT NULL COMMENT '模式 0 扣款 1 充值',
  `oldmoney` decimal(10,0) DEFAULT NULL COMMENT '原金额',
  `nowmoney` decimal(10,0) DEFAULT NULL COMMENT '当前金额',
  `paymoney` decimal(10,0) DEFAULT NULL COMMENT '支付金额',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述信息',
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_agentpay
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_arts`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_arts`;
CREATE TABLE `wifi_arts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `titlepic` varchar(255) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `info` text,
  `topflag` tinyint(4) DEFAULT '0' COMMENT '0:否 1 是 是否置顶',
  `add_time` varchar(20) DEFAULT NULL,
  `update_time` varchar(20) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '0: stop 1:ok',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_arts
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_authlist`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_authlist`;
CREATE TABLE `wifi_authlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `shopid` int(11) DEFAULT NULL,
  `routeid` int(11) DEFAULT NULL,
  `mode` tinyint(4) DEFAULT NULL COMMENT '认证模式',
  `mac` varchar(50) DEFAULT NULL,
  `add_date` varchar(50) DEFAULT NULL COMMENT '日期',
  `pingcount` int(4) DEFAULT '0' COMMENT '检测链接次数',
  `login_time` varchar(50) DEFAULT NULL COMMENT '登录时间',
  `login_ip` varchar(20) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL COMMENT '浏览器',
  `agent` varchar(100) DEFAULT NULL COMMENT '机器消息',
  `over_time` varchar(50) NOT NULL COMMENT '允许在线时长',
  `update_time` varchar(50) DEFAULT NULL,
  `last_time` varchar(50) DEFAULT NULL COMMENT '最后在线时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `wifi_month`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_month`;
CREATE TABLE `wifi_month` (
  `id` int(11) NOT NULL,
  `mon` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_month
-- ----------------------------
INSERT INTO `wifi_month` VALUES ('1', '01');
INSERT INTO `wifi_month` VALUES ('2', '02');
INSERT INTO `wifi_month` VALUES ('3', '03');
INSERT INTO `wifi_month` VALUES ('4', '04');
INSERT INTO `wifi_month` VALUES ('5', '05');
INSERT INTO `wifi_month` VALUES ('6', '06');
INSERT INTO `wifi_month` VALUES ('7', '07');
INSERT INTO `wifi_month` VALUES ('8', '08');
INSERT INTO `wifi_month` VALUES ('9', '09');
INSERT INTO `wifi_month` VALUES ('10', '10');
INSERT INTO `wifi_month` VALUES ('11', '11');
INSERT INTO `wifi_month` VALUES ('12', '12');

-- ----------------------------
-- Table structure for `wifi_nav`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_nav`;
CREATE TABLE `wifi_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `title` varchar(255) DEFAULT NULL COMMENT '栏目名称',
  `mode` tinyint(4) DEFAULT NULL COMMENT '0:单页 1:列表 ',
  `config` varchar(255) DEFAULT NULL COMMENT '配置json',
  `img` varchar(255) DEFAULT NULL COMMENT '图片信息',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '0:停用 1:启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_nav
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_news`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_news`;
CREATE TABLE `wifi_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(100) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `info` text,
  `mode` varchar(10) DEFAULT NULL,
  `add_time` varchar(20) DEFAULT NULL,
  `update_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;



-- ----------------------------
-- Table structure for `wifi_node`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_node`;
CREATE TABLE `wifi_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `bmenu` tinyint(4) DEFAULT '0',
  `single` tinyint(4) DEFAULT '0' COMMENT '是否还有子节点 0:否 1 是',
  `ico` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_node
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_notice`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_notice`;
CREATE TABLE `wifi_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `info` text,
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_notice
-- ----------------------------

DROP TABLE IF EXISTS `wifi_pushadv`;
CREATE TABLE `wifi_pushadv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `mode` tinyint(4) DEFAULT '0' COMMENT '投放路径 ',
  `pic` varchar(255) DEFAULT NULL COMMENT '广告存放路径',
  `info` varchar(200) DEFAULT NULL COMMENT '备注',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `showcount` int(11) DEFAULT '0' COMMENT '展示次数',
  `add_time` varchar(20) DEFAULT NULL,
  `startdate` varchar(20) DEFAULT NULL,
  `enddate` varchar(20) DEFAULT NULL,
  `update_time` varchar(20) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '0:停止 1 正常',
  `aid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_pushadv
-- ----------------------------


-- ----------------------------
-- Table structure for `wifi_role`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_role`;
CREATE TABLE `wifi_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_role
-- ----------------------------
INSERT INTO `wifi_role` VALUES ('1', '平台管理员', '0', '1', '');

-- ----------------------------
-- Table structure for `wifi_role_user`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_role_user`;
CREATE TABLE `wifi_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_role_user
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_routemap`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_routemap`;
CREATE TABLE `wifi_routemap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL COMMENT '帐号ID',
  `routename` varchar(20) DEFAULT NULL COMMENT '路由名称',
  `gw_address` varchar(50) DEFAULT NULL,
  `gw_port` varchar(20) DEFAULT NULL,
  `gw_id` varchar(30) NOT NULL COMMENT '网关ID',
  `sortid` int(11) DEFAULT '0',
  `add_time` varchar(20) DEFAULT NULL,
  `update_time` varchar(20) DEFAULT NULL,
  `sys_uptime` varchar(20) DEFAULT NULL COMMENT '路由时间',
  `sys_memfree` varchar(20) DEFAULT NULL,
  `wifidog_uptime` varchar(20) DEFAULT NULL COMMENT '路由跟踪时间',
  `sys_load` varchar(20) DEFAULT NULL,
  `last_heartbeat_ip` varchar(20) DEFAULT NULL COMMENT '心跳包IP',
  `last_heartbeat_time` varchar(20) DEFAULT NULL COMMENT '心跳时间',
  `user_agent` varchar(255) DEFAULT NULL COMMENT '消息头',
  `notes` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `state` tinyint(4) DEFAULT '1' COMMENT '0:停用 1：启用',
  PRIMARY KEY (`id`,`gw_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_routemap
-- ----------------------------
INSERT INTO `wifi_routemap` VALUES ('1', '1', '1号路由器', null, null, 'mywifi2', '0', null, '1389339761', '773216', '9460', '182910', '0.01', null, '1395210969', 'WiFiDog 20130917', null, '1');

DROP TABLE IF EXISTS `wifi_shop`;
CREATE TABLE `wifi_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) NOT NULL COMMENT '管理帐号',
  `password` varchar(255) NOT NULL,
  `shopname` varchar(255) DEFAULT NULL COMMENT '门店名称',
  `pid` int(11) DEFAULT '0' COMMENT '代理商ID',
  `mode` tinyint(4) DEFAULT '0' COMMENT '0:注册用户 1：代理商添加 ',
  `logo` varchar(255) DEFAULT NULL,
  `linker` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `info` text COMMENT '门店描述',
  `address` varchar(255) DEFAULT NULL,
  `point_x` varchar(255) DEFAULT NULL,
  `point_y` varchar(255) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '0:停用 1：启用',
  `tpl_id` int(11) DEFAULT NULL,
  `tpl_path` varchar(255) DEFAULT NULL COMMENT '模板地址',
  `trade` varchar(255) DEFAULT NULL COMMENT '行业类型 #区分',
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `maxcount` int(255) DEFAULT '0' COMMENT '认证使用次数',
  `end_time` varchar(255) DEFAULT NULL COMMENT '帐号有效期',
  `shopgroup` varchar(255) DEFAULT NULL COMMENT '商圈',
  `shoplevel` varchar(255) DEFAULT NULL COMMENT '店铺消费水平 多组 用 #区分',
  `routetype` tinyint(4) DEFAULT '0' COMMENT '0:单路由 1 多路由',
  `authmode` varchar(255) DEFAULT NULL COMMENT '认证模式',
  `authaction` tinyint(4) DEFAULT NULL,
  `jumpurl` varchar(255) DEFAULT NULL,
  `linkflag` tinyint(2) DEFAULT '0' COMMENT '0:限制注册上限  1 不限制使用',
  `timelimit` int(11) DEFAULT '0',
  `sh` int(4) DEFAULT '0' COMMENT '开始时段',
  `eh` int(4) DEFAULT '0' COMMENT '结束时段',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_shop
-- ----------------------------
INSERT INTO `wifi_shop` VALUES ('1', 'xc', '202cb962ac59075b964b07152d234b70', '协成智慧无线营销系统', '1', null, null, '123456', '13959166837', null, '', '', '', '1394782485', '1395023433', '1', null, null, '#餐饮##酒店##咖啡厅##足浴##KTV##购物商超##酒店宾馆##休闲娱乐#', '北京', '市辖区', '东城区', '600', '1394782485', null, '', '0', '#0##1#', '2', 'http://www.qq.com', '1', '200','0','23');

-- ----------------------------
-- Table structure for `wifi_sms`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_sms`;
CREATE TABLE `wifi_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '帐号ID',
  `mode` tinyint(4) DEFAULT '0' COMMENT '0:马上发送 1：定时发送',
  `phone` varchar(255) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `lens` int(11) DEFAULT NULL COMMENT '字数',
  `unit` int(11) DEFAULT NULL COMMENT '短信条数',
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `send_time` varchar(255) DEFAULT NULL COMMENT '发送时间',
  `ready_time` varchar(255) DEFAULT NULL COMMENT '预发送时间',
  `state` tinyint(4) DEFAULT '0' COMMENT '0:为发送 1 已发送 2 发送失败  ',
  `lostcount` int(11) DEFAULT NULL COMMENT '失败次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_sms
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_smscfg`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_smscfg`;
CREATE TABLE `wifi_smscfg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '0:停用 1 启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `wifi_tpl`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_tpl`;
CREATE TABLE `wifi_tpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tpl_name` varchar(255) DEFAULT NULL,
  `tpl_path` varchar(255) DEFAULT NULL,
  `group` varchar(10) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '0：停用 1 使用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_tpl
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_tradead`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_tradead`;
CREATE TABLE `wifi_tradead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL COMMENT '商户ID',
  `mode` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_tradead
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_treenode`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_treenode`;
CREATE TABLE `wifi_treenode` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL COMMENT '名称',
  `g` varchar(50) NOT NULL DEFAULT '' COMMENT '分组名称',
  `m` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `a` varchar(50) NOT NULL DEFAULT '' COMMENT 'action 名称',
  `ico` varchar(50) DEFAULT NULL COMMENT '图标',
  `pid` int(11) DEFAULT NULL COMMENT '0',
  `level` tinyint(4) DEFAULT '1' COMMENT '层级 1,2,3',
  `menuflag` tinyint(4) DEFAULT '1' COMMENT '0: 否 1 是',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `single` tinyint(4) DEFAULT '1' COMMENT '是否单节点 0:否 1 是',
  `remark` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '0 停用 1 启用',
  PRIMARY KEY (`id`,`a`,`m`,`g`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_treenode
-- ----------------------------
INSERT INTO `wifi_treenode` VALUES ('1', 'WIFI管理', 'wifiadmin', 'Index', 'index', null, '0', '1', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('2', '首页', 'wifiadmin', 'index', 'index', 'icon-home', '1', '2', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('3', '密码修改', 'wifiadmin', 'index', 'pwd', '1', '2', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('100', '系统管理', 'wifiadmin', 'System', 'index', 'icon-asterisk', '1', '2', '1', '0', '0', null, '1');
INSERT INTO `wifi_treenode` VALUES ('101', '角色管理', 'wifiadmin', 'system', 'role', null, '100', '2', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('102', '添加角色', 'wifiadmin', 'system', 'addrole', null, '100', '2', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('103', '编辑角色', 'wifiadmin', 'system', 'editrole', null, '100', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('104', '角色权限', 'wifiadmin', 'system', 'roleaccess', null, '100', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('105', '用户管理', 'wifiadmin', 'system', 'userlist', null, '100', '2', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('106', '添加用户', 'wifiadmin', 'system', 'adduser', null, '100', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('107', '编辑用户', 'wifiadmin', 'system', 'edituser', null, '100', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('200', '网站管理', 'wifiadmin', '', '', 'icon-cogs', '1', '1', '1', '0', '0', null, '1');
INSERT INTO `wifi_treenode` VALUES ('201', '网站设置', 'wifiadmin', 'system', 'index', null, '200', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('202', '参数设置', 'wifiadmin', 'System', 'setting', null, '200', '1', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('300', '商户管理', 'wifiadmin', 'Shop', 'index', 'icon-group', '1', '1', '1', '0', '0', null, '1');
INSERT INTO `wifi_treenode` VALUES ('301', '商户列表', 'wifiadmin', 'Shop', 'index', null, '300', '2', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('302', '添加商户', 'wifiadmin', 'shop', 'addshop', null, '300', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('303', '编辑商户', 'wifiadmin', 'shop', 'editshop', null, '300', '1', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('400', '代理商管理', 'wifiadmin', 'agent', '', 'icon-user-md', '1', '2', '1', '0', '0', null, '1');
INSERT INTO `wifi_treenode` VALUES ('401', '代理商等级', 'wifiadmin', 'agent', 'level', null, '400', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('402', '代理商列表', 'wifiadmin', 'agent', 'index', null, '400', '3', '1', '2', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('403', '添加代理商', 'wifiadmin', 'agent', 'add', null, '400', '3', '1', '3', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('404', '编辑代理商', 'wifiadmin', 'agent', 'edit', null, '400', '3', '0', '4', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('405', '添加等级', 'wifiadmin', 'agent', 'addlevel', null, '400', '3', '1', '1', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('406', '删除代理商', 'wifiadmin', 'agent', 'del', null, '400', '3', '0', '5', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('407', '扣款记录', 'wifiadmin', 'agent', 'paylist', null, '400', '3', '1', '7', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('408', '帐号金额调整', 'wifiadmin', 'agent', 'dopay', null, '400', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('500', '广告管理', 'wifiadmin', 'ad', 'index', 'icon-cloud', '1', '1', '1', '0', '0', null, '1');
INSERT INTO `wifi_treenode` VALUES ('501', '广告列表', 'wifiadmin', 'ad', 'index', null, '500', '1', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('502', '编辑广告', 'wifiadmin', 'ad', 'editad', null, '500', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('504', '删除广告', 'wifiadmin', 'ad', 'delad', null, '500', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('505', '广告统计', 'wifiadmin', 'ad', 'rpt', null, '500', '1', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('600', '统计信息', 'wifiadmin', 'report', '', 'icon-bar-chart', '1', '2', '1', '0', '0', null, '1');
INSERT INTO `wifi_treenode` VALUES ('601', '注册用户', 'wifiadmin', 'report', 'user', null, '600', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('602', '上网记录', 'wifiadmin', 'report', 'online', '', '600', '3', '1', '0', '1', '', '1');
INSERT INTO `wifi_treenode` VALUES ('603', '用户统计报表', 'wifiadmin', 'report', 'userchart', null, '600', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('604', '上网统计报表', 'wifiadmin', 'report', 'authchart', null, '600', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('605', '在线路由统计', 'wifiadmin', 'report', 'routechart', null, '600', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('700', '信息管理', 'wifiadmin', 'notice', '', 'icon-bullhorn', '1', '1', '1', '0', '0', '', '1');
INSERT INTO `wifi_treenode` VALUES ('701', '系统消息', 'wifiadmin', 'notice', 'index', '', '700', '3', '1', '0', '1', '', '1');
INSERT INTO `wifi_treenode` VALUES ('702', '添加系统消息', 'wifiadmin', 'notice', 'add', '', '700', '3', '1', '2', '1', '', '1');
INSERT INTO `wifi_treenode` VALUES ('703', '删除系统消息', 'wifiadmin', 'notice', 'del', '', '700', '3', '0', '3', '1', '', '1');
INSERT INTO `wifi_treenode` VALUES ('704', '编辑系统消息', 'wifiadmin', 'notice', 'edit', '', '700', '3', '0', '4', '1', '', '1');
INSERT INTO `wifi_treenode` VALUES ('705', '新闻管理', 'wifiadmin', 'news', 'index', '', '700', '3', '1', '4', '1', '', '1');
INSERT INTO `wifi_treenode` VALUES ('706', '添加新闻', 'wifiadmin', 'news', 'add', '', '700', '3', '1', '5', '1', '', '1');
INSERT INTO `wifi_treenode` VALUES ('800', '广告推送', 'wifiadmin', 'pushadv', 'index', 'icon-facetime-video', '1', '1', '1', '3', '0', null, '1');
INSERT INTO `wifi_treenode` VALUES ('801', '推送设置', 'wifiadmin', 'pushadv', 'set', null, '800', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('802', '推送广告管理', 'wifiadmin', 'pushadv', 'index', null, '800', '3', '1', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('803', '添加推送广告', 'wifiadmin', 'pushadv', 'add', null, '800', '3', '1', '3', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('804', '编辑推送广告', 'wifiadmin', 'pushadv', 'edit', null, '800', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('805', '删除推送广告', 'wifiadmin', 'pushadv', 'del', null, '800', '1', '0', '4', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('806', '广告推送统计', 'wifiadmin', 'Pushadv', 'rpt', null, '800', '3', '1', '5', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('108', '删除用户', 'wifiadmin', 'system', 'deluser', null, '100', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('109', '删除角色', 'wifiadmin', 'System', 'delrole', null, '100', '3', '0', '0', '1', null, '1');
INSERT INTO `wifi_treenode` VALUES ('606', '导出用户资料', 'wifiadmin', 'report', 'downuser', null, '600', '3', '0', '10', '1', null, '1');

-- ----------------------------
-- Table structure for `wifi_wap`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_wap`;
CREATE TABLE `wifi_wap` (
  `uid` int(11) NOT NULL,
  `home_tpl` int(11) DEFAULT NULL,
  `home_tpl_path` varchar(255) DEFAULT NULL,
  `list_tpl` int(11) DEFAULT NULL,
  `list_tpl_path` varchar(255) DEFAULT NULL,
  `info_tpl` int(11) DEFAULT NULL,
  `info_tpl_path` varchar(255) DEFAULT NULL,
  `state` smallint(6) DEFAULT NULL,
  `shopname` varchar(255) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `point_x` varchar(20) DEFAULT NULL,
  `point_y` varchar(20) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `add_time` varchar(50) DEFAULT NULL,
  `update_time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_wap
-- ----------------------------
INSERT INTO `wifi_wap` VALUES ('1', '9', 'home_t3', '12', 'list_hotel', '16', 'info_house', null, '协成智慧无线营销系统', '1395698566', '119.373499', '25.734112', null, null, null, '福清市福塘路清辉小区沿街2号2楼', '1393837434', '1393837434');

-- ----------------------------
-- Table structure for `wifi_wapcatelog`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_wapcatelog`;
CREATE TABLE `wifi_wapcatelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `mode` varchar(20) DEFAULT 'art' COMMENT '栏目类别',
  `modematch` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `icopic` varchar(255) DEFAULT NULL,
  `titlepic` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `point_x` varchar(20) DEFAULT NULL,
  `point_y` varchar(20) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  `update_time` varchar(255) DEFAULT NULL,
  `state` tinyint(2) DEFAULT '1' COMMENT '0:停用 1 使用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_wapcatelog
-- ----------------------------

-- ----------------------------
-- Table structure for `wifi_waptpl`
-- ----------------------------
DROP TABLE IF EXISTS `wifi_waptpl`;
CREATE TABLE `wifi_waptpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` smallint(6) DEFAULT NULL COMMENT '0:首页 1 列表 2 图文',
  `title` varchar(255) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `tplpath` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `isdefault` smallint(6) DEFAULT '0' COMMENT '1 默认模板 0 不是默认',
  `state` smallint(6) DEFAULT '1' COMMENT '0: 停用 1 启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wifi_waptpl
-- ----------------------------
INSERT INTO `wifi_waptpl` VALUES ('2', '1', '默认模板', null, 'list1.png', 'list_t1', '0', '1', '1');
INSERT INTO `wifi_waptpl` VALUES ('6', '2', '科技模板', null, 'news_tech.png', 'info_tech', '1', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('8', '1', '默认模板3', null, 'list2.png', 'list_t2', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('9', '0', '默认模板4', null, 'cate1.png', 'home_t3', '3', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('11', '0', '酒店主题模板', null, 'home_hotel.png', 'home_hotel', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('12', '1', '酒店主题模板', null, 'list_hotel.png', 'list_hotel', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('13', '2', '酒店主题模板', null, 'info_hotel.png', 'info_hotel', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('15', '1', '房产主题模板', null, 'list_house.png', 'list_house', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('16', '2', '房产主题', null, 'info_house.png', 'info_house', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('17', '0', '主题模板10', null, 'home10.png', 'home_t10', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('19', '0', '酒吧模板', null, 'home_s.png', 'home_bar', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('20', '3', '默认模板1', null, 'shop_home_t1.png', 'home_t1', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('21', '3', '默认模板2', null, null, 'index', '0', '0', '1');
INSERT INTO `wifi_waptpl` VALUES ('22', '99', '默认模板', null, null, 'index', '0', '1', '1');
INSERT INTO `wifi_waptpl` VALUES ('23', '99', '小清新', null, null, 't_green', '0', '0', '1');
