/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : tp5shop

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-07-10 13:27:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sh_attribute
-- ----------------------------
DROP TABLE IF EXISTS `sh_attribute`;
CREATE TABLE `sh_attribute` (
  `attr_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(4) DEFAULT NULL,
  `attr_name` varchar(30) DEFAULT '',
  `attr_type` tinyint(4) DEFAULT '0' COMMENT '0-唯一属性，1-单选属性',
  `attr_input_type` tinyint(4) DEFAULT '0' COMMENT '0-手工输入，1-列表选择',
  `attr_values` varchar(255) DEFAULT '',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`attr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_attribute
-- ----------------------------
INSERT INTO `sh_attribute` VALUES ('18', '6', '制式', '0', '1', '3G|4G', '0', '0');
INSERT INTO `sh_attribute` VALUES ('20', '6', '颜色', '1', '1', '黑色|红色|玫瑰金', '0', '0');
INSERT INTO `sh_attribute` VALUES ('21', '6', '内存', '1', '1', '4G|8G|16G', '0', '0');
INSERT INTO `sh_attribute` VALUES ('22', '6', '产地', '0', '1', '美国|上海|广州', '0', '0');
INSERT INTO `sh_attribute` VALUES ('23', '6', '毛重', '0', '0', '', '0', '0');
INSERT INTO `sh_attribute` VALUES ('24', '6', '套餐', '1', '0', '', '0', '0');

-- ----------------------------
-- Table structure for sh_auth
-- ----------------------------
DROP TABLE IF EXISTS `sh_auth`;
CREATE TABLE `sh_auth` (
  `auth_id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_name` varchar(40) DEFAULT '',
  `auth_c` varchar(40) DEFAULT '',
  `auth_a` varchar(40) DEFAULT '',
  `pid` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_auth
-- ----------------------------
INSERT INTO `sh_auth` VALUES ('35', '权限管理', '', '', '0', '0', '0');
INSERT INTO `sh_auth` VALUES ('36', '权限列表', 'auth', 'index', '35', '0', '0');
INSERT INTO `sh_auth` VALUES ('37', '添加权限', 'auth', 'add', '35', '0', '0');
INSERT INTO `sh_auth` VALUES ('38', '用户管理', '', '', '0', '0', '0');
INSERT INTO `sh_auth` VALUES ('39', '用户列表', 'user', 'index', '38', '0', '0');
INSERT INTO `sh_auth` VALUES ('40', '添加用户', 'user', 'add', '38', '0', '0');
INSERT INTO `sh_auth` VALUES ('41', '用户删除', 'user', 'del', '39', '0', '0');
INSERT INTO `sh_auth` VALUES ('42', '用户编辑', 'user', 'upd', '39', '0', '0');
INSERT INTO `sh_auth` VALUES ('43', '权限编辑', 'auth', 'upd', '36', '0', '0');
INSERT INTO `sh_auth` VALUES ('44', '权限删除', 'auth', 'del', '36', '0', '0');

-- ----------------------------
-- Table structure for sh_cart
-- ----------------------------
DROP TABLE IF EXISTS `sh_cart`;
CREATE TABLE `sh_cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT '0',
  `goods_attr_ids` varchar(80) DEFAULT '',
  `goods_number` int(11) DEFAULT '0',
  `member_id` int(11) DEFAULT '0',
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_cart
-- ----------------------------

-- ----------------------------
-- Table structure for sh_category
-- ----------------------------
DROP TABLE IF EXISTS `sh_category`;
CREATE TABLE `sh_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(40) DEFAULT '',
  `pid` smallint(6) DEFAULT '0',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '1-显示 0-不显示',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_category
-- ----------------------------
INSERT INTO `sh_category` VALUES ('21', '手机', '0', '1', '0', '0');
INSERT INTO `sh_category` VALUES ('22', '电脑', '0', '1', '0', '0');
INSERT INTO `sh_category` VALUES ('23', '电器', '0', '0', '0', '0');
INSERT INTO `sh_category` VALUES ('24', '国内手机', '21', '1', '0', '0');
INSERT INTO `sh_category` VALUES ('25', '国外手机', '21', '0', '0', '0');
INSERT INTO `sh_category` VALUES ('26', '小米', '24', '1', '0', '0');
INSERT INTO `sh_category` VALUES ('27', '华为', '24', '1', '0', '0');
INSERT INTO `sh_category` VALUES ('28', 'vivo', '24', '0', '0', '0');
INSERT INTO `sh_category` VALUES ('29', 'oppo', '24', '0', '0', '0');
INSERT INTO `sh_category` VALUES ('30', '魅族', '24', '1', '0', '0');
INSERT INTO `sh_category` VALUES ('31', '三星', '25', '1', '0', '0');
INSERT INTO `sh_category` VALUES ('32', '苹果', '25', '1', '0', '0');
INSERT INTO `sh_category` VALUES ('33', '摩托罗拉', '25', '1', '0', '0');

-- ----------------------------
-- Table structure for sh_goods
-- ----------------------------
DROP TABLE IF EXISTS `sh_goods`;
CREATE TABLE `sh_goods` (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(40) DEFAULT '',
  `goods_sn` varchar(150) DEFAULT '',
  `goods_price` decimal(10,2) DEFAULT NULL COMMENT 'decimal(10,2)',
  `goods_number` int(11) DEFAULT '0',
  `type_id` smallint(6) DEFAULT '0',
  `cat_id` smallint(6) DEFAULT '0',
  `goods_img` text,
  `goods_middle` text,
  `goods_thumb` text,
  `add_time` int(11) DEFAULT NULL,
  `is_delete` tinyint(4) DEFAULT '0' COMMENT '是否在回站 0-不在回收站 1-在回收站',
  `is_sale` tinyint(4) DEFAULT '1' COMMENT '默认为1： 0-未上架 1-已上架',
  `is_new` tinyint(4) DEFAULT '1' COMMENT '默认为1： 0-不是新品 1-是新品',
  `is_best` tinyint(4) DEFAULT '1' COMMENT '默认为1： 0-不是推荐 1-是推荐',
  `is_hot` tinyint(4) DEFAULT '1' COMMENT '默认为1： 0-不是热卖 1-是热卖商品',
  `goods_desc` text,
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of sh_goods
-- ----------------------------
INSERT INTO `sh_goods` VALUES ('44', '苹果8', 'sn_1806091203596223', '8000.00', '786', '6', '32', '[\"20180613\\/4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/s_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/s_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/s_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', null, '0', '1', '1', '1', '0', '<p>苹果8苹果8苹果8苹果8苹果8苹果8苹果8</p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\"></span></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span><br/></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span></p><p><br/></p>', '0', '0');
INSERT INTO `sh_goods` VALUES ('45', '苹果7', 'sn_1806091203596263', '7000.00', '100', '6', '32', '[\"20180613\\\\4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\\\d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\\\50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/s_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/s_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/s_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', null, '0', '1', '0', '1', '1', '<p>苹果8苹果8苹果8苹果8苹果8苹果8苹果8</p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\"></span></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span><br/></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span></p><p><br/></p>', '0', '0');
INSERT INTO `sh_goods` VALUES ('46', '苹果6', 'sn_1806091203596263', '6000.00', '600', '6', '32', '[\"20180613\\\\4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\\\d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\\\50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/s_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/s_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/s_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', null, '0', '1', '1', '0', '1', '<p>苹果8苹果8苹果8苹果8苹果8苹果8苹果8</p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\"></span></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span><br/></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span></p><p><br/></p>', '0', '0');
INSERT INTO `sh_goods` VALUES ('47', '小米2', 'sn_1806091203596264', '2000.00', '686', '6', '26', '[\"20180613\\\\4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\\\d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\\\50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180609\\/thumb_fb13050c41f0324966351b93ef2f29f0.jpg\",\"20180609\\/thumb_85cb9d67aa6a16e7fccd156db727bcc6.jpg\",\"20180609\\/thumb_30b405e4be923a3d77527cfa92600047.jpg\"]', null, '0', '1', '1', '1', '0', '<p>苹果8苹果8苹果8苹果8苹果8苹果8苹果8</p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\"></span></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span><br/></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span></p><p><br/></p>', '0', '0');
INSERT INTO `sh_goods` VALUES ('48', '小米3', 'sn_1806091203596277', '3000.00', '695', '6', '26', '[\"20180613\\\\4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\\\d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\\\50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/s_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/s_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/s_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', null, '0', '1', '0', '1', '1', '<p>苹果8苹果8苹果8苹果8苹果8苹果8苹果8</p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\"></span></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span><br/></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span></p><p><br/></p>', '0', '0');
INSERT INTO `sh_goods` VALUES ('49', '小米4', 'sn_1806091203596242', '4000.00', '700', '6', '26', '[\"20180613\\\\4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\\\d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\\\50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/s_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/s_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/s_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', null, '0', '1', '0', '1', '1', '<p>苹果8苹果8苹果8苹果8苹果8苹果8苹果8</p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\"></span></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span><br/></p><p><span style=\"white-space:pre\">			</span></p><p><span style=\"white-space:pre\">			</span></p><p><br/></p>', '0', '0');
INSERT INTO `sh_goods` VALUES ('50', '苹果6s', 'sn_1806090255424764', '6000.00', '595', '6', '32', '[\"20180613\\\\4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\\\d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\\\50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/s_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/s_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/s_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', null, '0', '1', '0', '1', '0', '<p>苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s苹果6s</p>', '0', '0');
INSERT INTO `sh_goods` VALUES ('51', '苹果x', 'sn_1806131216479609', '6666.00', '555', '0', '32', '[\"20180613\\\\4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\\\d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\\\50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/s_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/s_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/s_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', null, '0', '1', '1', '1', '1', '                    ', '0', '0');
INSERT INTO `sh_goods` VALUES ('52', '苹果4444', 'sn_1806131222498815', '4444.00', '31', '6', '32', '[\"20180613\\/4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/m_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/m_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/m_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', '[\"20180613\\/s_thumb_4b1bd32f33fb1798dabeb320100b75d9.jpg\",\"20180613\\/s_thumb_d48b0037716fa39fe0daafebe8068423.jpg\",\"20180613\\/s_thumb_50878842c4f9758b8338b58786aa166c.jpg\"]', null, '0', '1', '1', '1', '1', '                    ', '0', '0');
INSERT INTO `sh_goods` VALUES ('53', '苹果9', 'sn_1806130131136906', '99.00', '993', '6', '32', '[\"20180613\\/6d12362b679f385093bf6ca05aa7ca7b.jpg\",\"20180613\\/9cda9c63fb74b8543ccdfd434019a788.jpg\",\"20180613\\/c7edab83c534602533137aa90aad1168.jpg\",\"20180613\\/a439eb3a3a88d4e68caab180f44ec8d8.jpg\"]', '[\"20180613\\/m_thumb_6d12362b679f385093bf6ca05aa7ca7b.jpg\",\"20180613\\/m_thumb_9cda9c63fb74b8543ccdfd434019a788.jpg\",\"20180613\\/m_thumb_c7edab83c534602533137aa90aad1168.jpg\",\"20180613\\/m_thumb_a439eb3a3a88d4e68caab180f44ec8d8.jpg\"]', '[\"20180613\\/s_thumb_6d12362b679f385093bf6ca05aa7ca7b.jpg\",\"20180613\\/s_thumb_9cda9c63fb74b8543ccdfd434019a788.jpg\",\"20180613\\/s_thumb_c7edab83c534602533137aa90aad1168.jpg\",\"20180613\\/s_thumb_a439eb3a3a88d4e68caab180f44ec8d8.jpg\"]', null, '0', '1', '1', '1', '1', '      苹果9苹果9苹果9苹果9苹果9苹果9              ', '0', '0');

-- ----------------------------
-- Table structure for sh_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `sh_goods_attr`;
CREATE TABLE `sh_goods_attr` (
  `goods_attr_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL,
  `attr_id` smallint(5) unsigned NOT NULL,
  `attr_value` varchar(255) DEFAULT '',
  `attr_price` decimal(10,2) DEFAULT NULL,
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`goods_attr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_goods_attr
-- ----------------------------
INSERT INTO `sh_goods_attr` VALUES ('61', '44', '18', '3G', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('62', '44', '20', '黑色', '100.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('63', '44', '20', '红色', '200.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('64', '44', '21', '4G', '400.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('65', '44', '21', '8G', '800.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('66', '44', '21', '16G', '1600.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('67', '44', '22', '美国', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('68', '44', '23', '2kg', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('69', '44', '24', '套餐1', '1100.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('70', '44', '24', '套餐2', '2200.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('71', '50', '18', '4G', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('72', '50', '20', '红色', '100.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('73', '50', '20', '玫瑰金', '300.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('74', '50', '21', '4G', '400.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('75', '50', '21', '8G', '800.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('76', '50', '21', '16G', '1600.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('77', '50', '22', '上海', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('78', '50', '23', '2kg', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('79', '50', '24', '套餐1', '200.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('80', '50', '24', '套餐2', '300.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('81', '52', '18', '3G', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('82', '52', '20', '黑色', '100.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('83', '52', '21', '16G', '1600.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('84', '52', '22', '美国', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('85', '52', '23', '2kg', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('86', '52', '24', '套餐1', '200.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('87', '52', '24', '套餐2', '400.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('88', '53', '18', '3G', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('89', '53', '20', '黑色', '800.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('90', '53', '21', '16G', '900.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('91', '53', '22', '上海', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('92', '53', '23', '2kg', null, '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('93', '53', '24', '套餐1', '100.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('94', '53', '24', '套餐2', '200.00', '0', '0');
INSERT INTO `sh_goods_attr` VALUES ('95', '53', '24', '套餐3', '300.00', '0', '0');

-- ----------------------------
-- Table structure for sh_member
-- ----------------------------
DROP TABLE IF EXISTS `sh_member`;
CREATE TABLE `sh_member` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(200) DEFAULT '' COMMENT 'qq用户的昵称',
  `username` varchar(30) DEFAULT '',
  `password` char(32) DEFAULT '',
  `email` varchar(50) DEFAULT '',
  `phone` varchar(20) DEFAULT NULL,
  `openid` varchar(50) DEFAULT '',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_member
-- ----------------------------
INSERT INTO `sh_member` VALUES ('6', '', 'dachui', 'a5749e23780acb3dc2963526b02547eb', '1259481020@qq.com', null, '', '0', '0');
INSERT INTO `sh_member` VALUES ('7', '', 'xiaochui', '1b12704a0b207cae0901b5648d428e1b', 'xiaochui@qq.com', null, '', '0', '0');
INSERT INTO `sh_member` VALUES ('8', '', 'qiegewala', 'a5749e23780acb3dc2963526b02547eb', 'qiegewala@qq.com', '18948727439', '', '0', '0');
INSERT INTO `sh_member` VALUES ('9', '追赶的人儿', '', '', '', null, '7FB9638A10ABC81CA3EB8DBC2A183B1C', '0', '0');

-- ----------------------------
-- Table structure for sh_order
-- ----------------------------
DROP TABLE IF EXISTS `sh_order`;
CREATE TABLE `sh_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(80) DEFAULT NULL,
  `receiver` varchar(30) DEFAULT NULL,
  `address` varchar(80) DEFAULT '',
  `tel` char(15) DEFAULT NULL,
  `zcode` varchar(6) DEFAULT NULL COMMENT '邮编',
  `total_price` decimal(10,2) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `pay_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-未付款 ,1-已付款',
  `send_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' 0-未发货 ， 1-已发货 ，2-已收货 ,3-退货中 4-退货成功',
  `ali_order_id` varchar(255) NOT NULL DEFAULT '' COMMENT '支付成功支付宝返回的订单号',
  `order_time` int(11) DEFAULT NULL,
  `company` varchar(255) DEFAULT '',
  `number` varchar(100) DEFAULT '' COMMENT '物流公司',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_order
-- ----------------------------
INSERT INTO `sh_order` VALUES ('1', '2018061515290753255b23d67d94413', '凤姐', '上海市万香创新港5栋', '13411121212', '123456', '5394.00', '6', '1', '1', '2018061521001004420200696452', null, 'yunda', '3900321280110', '1529075325', '1529304591');
INSERT INTO `sh_order` VALUES ('2', '2018061615290790255b23e4f1e2899', '凤姐', '上海市万香创新港5栋', '13411121212', '123456', '45108.00', '6', '1', '0', '2018061621001004420200696936', null, '', '', '1529079025', '1529079025');
INSERT INTO `sh_order` VALUES ('3', '2018061615290803785b23ea3ad860a', '汪玮', '上海迪士尼', '18912345678', '333444', '18732.00', '6', '0', '0', '', null, '', '', '1529080378', '1529080378');
INSERT INTO `sh_order` VALUES ('5', '2018061615290813895b23ee2d795be', '凤姐22222', '2222', '222', '22222', '13332.00', '6', '1', '1', '2018061621001004420200696937', null, 'yunda', '3900321280110', '1529081389', '1529304695');
INSERT INTO `sh_order` VALUES ('6', 'gsdfgsdf', null, '上海迪士尼', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('7', 'gsdf', null, '上海迪士尼', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('8', 'sd', null, '上海迪士尼', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('9', 'g', null, '广州', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('10', 'fg', null, '广州', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('15', '好地方换个地方给', null, '广州', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('16', '53245', null, '广州', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('17', '5234', null, '广州', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('18', '23', null, '广州', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('19', '545646745ytgf', null, '广州', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('20', '23', null, '', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('21', '235', null, '', null, null, null, null, '0', '0', '', null, '', '', '0', '0');
INSERT INTO `sh_order` VALUES ('22', '4', null, '', null, null, null, null, '0', '0', '', null, '', '', '0', '0');

-- ----------------------------
-- Table structure for sh_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `sh_order_goods`;
CREATE TABLE `sh_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(100) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `goods_attr_ids` varchar(30) DEFAULT NULL,
  `goods_number` int(5) DEFAULT NULL,
  `goods_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_order_goods
-- ----------------------------
INSERT INTO `sh_order_goods` VALUES ('1', '1', '53', '72,74,80', '6', '5394.00');
INSERT INTO `sh_order_goods` VALUES ('2', '2', '52', '89,90,95', '7', '45108.00');
INSERT INTO `sh_order_goods` VALUES ('3', '3', '52', '89,90,93', '3', '18732.00');
INSERT INTO `sh_order_goods` VALUES ('4', '5', '52', '', '3', '13332.00');

-- ----------------------------
-- Table structure for sh_role
-- ----------------------------
DROP TABLE IF EXISTS `sh_role`;
CREATE TABLE `sh_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(40) DEFAULT '',
  `auth_id_list` varchar(100) DEFAULT '',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_role
-- ----------------------------
INSERT INTO `sh_role` VALUES ('4', '超级管理员', '*', '0', '0');
INSERT INTO `sh_role` VALUES ('6', '销售人员11', '35,36,43,38,39,41,42', '0', '0');
INSERT INTO `sh_role` VALUES ('7', '商品人员', '38,39,41', '0', '0');

-- ----------------------------
-- Table structure for sh_type
-- ----------------------------
DROP TABLE IF EXISTS `sh_type`;
CREATE TABLE `sh_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(40) DEFAULT '',
  `mark` text,
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_type
-- ----------------------------
INSERT INTO `sh_type` VALUES ('6', '手机类型', null, '0', '0');
INSERT INTO `sh_type` VALUES ('7', '电脑类型', null, '0', '0');
INSERT INTO `sh_type` VALUES ('8', '汽车类型', null, '0', '0');

-- ----------------------------
-- Table structure for sh_user
-- ----------------------------
DROP TABLE IF EXISTS `sh_user`;
CREATE TABLE `sh_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT '',
  `password` char(32) DEFAULT '',
  `is_active` tinyint(4) DEFAULT '1' COMMENT '0-账号禁用(封号），1-账号可用',
  `role_id` tinyint(4) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sh_user
-- ----------------------------
INSERT INTO `sh_user` VALUES ('29', 'admin', 'a5749e23780acb3dc2963526b02547eb', null, '4', '0', null);
INSERT INTO `sh_user` VALUES ('30', 'test1', '56fdcef3e366d0bf272ea2e45ac03c61', null, '7', '0', '1528520028');
