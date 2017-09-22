/*
Navicat MySQL Data Transfer

Source Server         : xampp
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : questionnaire

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-09-21 23:45:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for answers
-- ----------------------------
DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `aid` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `qnid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `okey` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `pkey` int(11) DEFAULT NULL,
  `qid` int(11) NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of answers
-- ----------------------------

-- ----------------------------
-- Table structure for editors
-- ----------------------------
DROP TABLE IF EXISTS `editors`;
CREATE TABLE `editors` (
  `id` int(11) NOT NULL,
  `qnid` int(11) NOT NULL,
  `twt_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of editors
-- ----------------------------

-- ----------------------------
-- Table structure for options
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `qnid` int(11) NOT NULL,
  `okey` varchar(255) DEFAULT NULL,
  `option` varchar(255) DEFAULT NULL,
  `test` int(11) DEFAULT NULL,
  `pkey` varchar(11) DEFAULT NULL,
  `problem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `options` VALUES ('55', '22', '13', null, null, null, '0', 'AA');
INSERT INTO `options` VALUES ('56', '22', '13', null, null, null, '1', 'AAA');
INSERT INTO `options` VALUES ('57', '22', '13', null, null, null, '2', '254');
INSERT INTO `options` VALUES ('58', '22', '13', '0', 'A', null, null, null);
INSERT INTO `options` VALUES ('59', '22', '13', '1', 'B', null, null, null);
INSERT INTO `options` VALUES ('60', '22', '13', '2', 'C', null, null, null);
INSERT INTO `options` VALUES ('61', '23', '13', null, null, null, '0', 'balabala');
INSERT INTO `options` VALUES ('62', '23', '13', null, null, null, '1', 'ooo');
INSERT INTO `options` VALUES ('63', '23', '13', null, null, null, '2', '520');
INSERT INTO `options` VALUES ('64', '23', '13', '0', '111', null, null, null);
INSERT INTO `options` VALUES ('65', '23', '13', '1', '23', null, null, null);
INSERT INTO `options` VALUES ('66', '23', '13', '2', '456', null, null, null);
INSERT INTO `options` VALUES ('67', '24', '13', null, null, null, '0', '还有');
INSERT INTO `options` VALUES ('68', '24', '13', null, null, null, '1', '叉叉');
INSERT INTO `options` VALUES ('69', '24', '13', null, null, null, '2', '噢噢');
INSERT INTO `options` VALUES ('70', '24', '13', '0', '全都', null, null, null);
INSERT INTO `options` VALUES ('71', '24', '13', '1', '是', null, null, null);
INSERT INTO `options` VALUES ('72', '24', '13', '2', '哈哈哈哈', null, null, null);
INSERT INTO `options` VALUES ('73', '25', '13', null, null, null, '0', '三个傻瓜');
INSERT INTO `options` VALUES ('74', '25', '13', null, null, null, '1', '叉叉');
INSERT INTO `options` VALUES ('75', '25', '13', null, null, null, '2', '噢噢');
INSERT INTO `options` VALUES ('76', '25', '13', '0', '哈哈哈哈哈哈哈啊啊啊', null, null, null);
INSERT INTO `options` VALUES ('77', '25', '13', '1', '是', null, null, null);
INSERT INTO `options` VALUES ('78', '25', '13', '2', '哈哈哈哈', null, null, null);
INSERT INTO `options` VALUES ('79', '26', '14', null, null, null, '0', 'a');
INSERT INTO `options` VALUES ('80', '26', '14', null, null, null, '1', 'aa');
INSERT INTO `options` VALUES ('81', '26', '14', null, null, null, '2', 'aa');
INSERT INTO `options` VALUES ('82', '26', '14', '0', 'hh', null, null, null);
INSERT INTO `options` VALUES ('83', '26', '14', '1', 'hhh', null, null, null);
INSERT INTO `options` VALUES ('84', '26', '14', '2', 'hhhh', null, null, null);
INSERT INTO `options` VALUES ('85', '27', '14', null, null, null, '0', '或者');
INSERT INTO `options` VALUES ('86', '27', '14', null, null, null, '1', '两个');
INSERT INTO `options` VALUES ('87', '27', '14', null, null, null, '2', '啊');
INSERT INTO `options` VALUES ('88', '27', '14', '0', '必须', null, null, null);
INSERT INTO `options` VALUES ('89', '27', '14', '1', '选', null, null, null);
INSERT INTO `options` VALUES ('90', '27', '14', '2', '一个', null, null, null);
INSERT INTO `options` VALUES ('91', '29', '14', '0', '啊', null, null, null);
INSERT INTO `options` VALUES ('92', '29', '14', '1', '我', null, null, null);
INSERT INTO `options` VALUES ('93', '29', '14', '2', '的', null, null, null);
INSERT INTO `options` VALUES ('94', '31', '4', '0', '啊', null, null, null);
INSERT INTO `options` VALUES ('95', '31', '14', '1', '威威', null, null, null);
INSERT INTO `options` VALUES ('96', '31', '14', '2', '的', null, null, null);
INSERT INTO `options` VALUES ('97', '32', '14', null, null, null, '0', '2');
INSERT INTO `options` VALUES ('98', '32', '14', null, null, null, '1', '1');
INSERT INTO `options` VALUES ('99', '32', '14', null, null, null, '2', '0');
INSERT INTO `options` VALUES ('100', '32', '14', '0', '5', null, null, null);
INSERT INTO `options` VALUES ('101', '32', '14', '1', '4', null, null, null);
INSERT INTO `options` VALUES ('102', '32', '14', '2', '3', null, null, null);
INSERT INTO `options` VALUES ('115', '36', '16', '0', '12', null, null, null);
INSERT INTO `options` VALUES ('116', '36', '16', '1', '21', null, null, null);
INSERT INTO `options` VALUES ('117', '36', '16', '2', '12', null, null, null);
INSERT INTO `options` VALUES ('120', '47', '143', 'A', 'aaa', null, null, null);
INSERT INTO `options` VALUES ('121', '47', '143', 'B', 'fff', null, null, null);
INSERT INTO `options` VALUES ('122', '47', '143', 'C', '344aa', null, null, null);
INSERT INTO `options` VALUES ('123', '49', '144', 'A', 'AA', null, null, null);
INSERT INTO `options` VALUES ('124', '49', '144', 'B', 'BB', null, null, null);
INSERT INTO `options` VALUES ('125', '50', '144', 'A', '选C', null, null, null);
INSERT INTO `options` VALUES ('126', '50', '144', 'B', '傻逼', null, null, null);
INSERT INTO `options` VALUES ('127', '50', '144', 'C', '说你呢', null, null, null);
INSERT INTO `options` VALUES ('128', '51', '144', 'A', '第一', null, null, null);
INSERT INTO `options` VALUES ('129', '51', '144', 'B', '第二', null, null, null);
INSERT INTO `options` VALUES ('130', '52', '146', 'A', 'vd什么v的模式v的', null, null, null);
INSERT INTO `options` VALUES ('131', '52', '146', 'B', 'v没收到反馈v么快', null, null, null);
INSERT INTO `options` VALUES ('132', '52', '146', 'C', 'v没地方看来是v麦当劳', null, null, null);
INSERT INTO `options` VALUES ('133', '53', '146', 'A', 'v开始v呢会计法v你', null, null, null);
INSERT INTO `options` VALUES ('134', '53', '146', 'B', 'vs的夫君开发的你', null, null, null);
INSERT INTO `options` VALUES ('135', '53', '146', 'C', 'v你的jfk是v你打开', null, null, null);
INSERT INTO `options` VALUES ('136', '56', '146', 'A', 'v没地方看v的开始', null, null, null);
INSERT INTO `options` VALUES ('137', '56', '146', 'B', 'v等级考试v你打开', null, null, null);
INSERT INTO `options` VALUES ('138', '56', '146', 'C', 'v空间的烦恼是v的看法今年', null, null, null);

-- ----------------------------
-- Table structure for questionnaires
-- ----------------------------
DROP TABLE IF EXISTS `questionnaires`;
CREATE TABLE `questionnaires` (
  `qnid` int(11) NOT NULL AUTO_INCREMENT,
  `twt_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `qcount` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `hasnumber` int(11) DEFAULT NULL,
  `recovery_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ischecked` int(11) DEFAULT NULL,
  `onceanswer` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  PRIMARY KEY (`qnid`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of questionnaires
-- ----------------------------
INSERT INTO `questionnaires` VALUES ('13', '', 'hahh', 'testupdate', '4', '1', '2017-09-21 00:33:47', '2017-09-20 16:33:47', null, '2017-09-21 00:33:47', null, null, null, null);
INSERT INTO `questionnaires` VALUES ('14', '', 'hhh', '', '8', '1', '2017-09-06 19:38:28', '2017-09-06 11:38:28', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('16', '', '测试题目数量', '', '1', '1', '2017-09-10 16:28:48', '2017-09-10 08:28:48', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('17', 'qyhxxx', '事成', '啊啊', '0', '1', '2017-09-17 02:26:35', '2017-09-17 02:26:35', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('143', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '1', '1', '2017-09-21 19:06:37', '2017-09-21 11:06:37', null, '2017-09-21 19:06:37', null, null, null, null);
INSERT INTO `questionnaires` VALUES ('144', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '4', '1', '2017-09-21 19:13:00', '2017-09-21 11:13:00', null, '2017-09-21 19:13:00', null, null, null, null);
INSERT INTO `questionnaires` VALUES ('145', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:06:47', '2017-09-21 15:06:47', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('146', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '5', '1', '2017-09-21 23:11:29', '2017-09-21 15:11:29', null, '2017-09-21 23:11:29', null, null, null, null);
INSERT INTO `questionnaires` VALUES ('147', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:29:34', '2017-09-21 15:29:34', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('148', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:29:58', '2017-09-21 15:29:58', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('149', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:30:16', '2017-09-21 15:30:16', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('150', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:31:18', '2017-09-21 15:31:18', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('151', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:31:30', '2017-09-21 15:31:30', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('152', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:31:33', '2017-09-21 15:31:33', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('153', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:31:35', '2017-09-21 15:31:35', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('154', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:31:38', '2017-09-21 15:31:38', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('155', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:31:40', '2017-09-21 15:31:40', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('156', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:31:42', '2017-09-21 15:31:42', null, null, null, null, null, null);
INSERT INTO `questionnaires` VALUES ('157', 'newyingyi', '问卷标题', '为了给您提供更好的服务，希望您能抽出几分钟时间，将您的感受和建议告诉我们，我们非常重视每位用户的宝贵意见，期待您的参与！现在我们就马上开始吧！', '0', '1', '2017-09-21 15:31:52', '2017-09-21 15:31:52', null, null, null, null, null, null);

-- ----------------------------
-- Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `qid` int(11) NOT NULL AUTO_INCREMENT,
  `qnid` int(11) NOT NULL,
  `qnum` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `qtype` int(11) NOT NULL,
  `isrequired` int(11) NOT NULL DEFAULT '0',
  `stype` varchar(255) DEFAULT NULL,
  `srange` int(11) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  `test` int(11) DEFAULT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of questions
-- ----------------------------
INSERT INTO `questions` VALUES ('22', '13', '1', '去你妈的', '没有功能', '8', '1', '', null, '1', '2', null);
INSERT INTO `questions` VALUES ('23', '13', '2', 'eie嘿嘿', '无', '8', '1', '', null, '1', '2', null);
INSERT INTO `questions` VALUES ('24', '13', '3', '哈哈哈哈', '无', '8', '1', '', null, '1', '2', null);
INSERT INTO `questions` VALUES ('25', '13', '4', '哈哈哈哈', '无', '8', '1', '', null, '1', '2', null);
INSERT INTO `questions` VALUES ('26', '14', '1', 'quanbu', 'doushi', '8', '1', null, null, '2', '3', null);
INSERT INTO `questions` VALUES ('27', '14', '2', '啦啦啦', '略', '8', '1', null, null, '1', '2', null);
INSERT INTO `questions` VALUES ('28', '14', '3', '单行文本', '没有呀', '3', '1', null, null, null, null, null);
INSERT INTO `questions` VALUES ('29', '14', '4', '这次是单选', '没有功能', '0', '1', null, null, null, null, null);
INSERT INTO `questions` VALUES ('30', '14', '5', '多行文本肯定行', '。', '4', '1', null, null, null, null, null);
INSERT INTO `questions` VALUES ('31', '14', '6', '多选啊', '5', '1', '0', null, null, '2', '3', null);
INSERT INTO `questions` VALUES ('32', '14', '7', '矩阵担心', '22', '7', '0', null, null, null, null, null);
INSERT INTO `questions` VALUES ('36', '16', '1', '单选', '没', '0', '0', null, null, null, null, null);
INSERT INTO `questions` VALUES ('47', '143', '1', 'aa', 'fff', '1', '0', null, null, '1', '3', null);
INSERT INTO `questions` VALUES ('48', '144', '1', '量表', '备注', '5', '1', null, null, null, '5', '0');
INSERT INTO `questions` VALUES ('49', '144', '2', '单选', '哈哈哈', '0', '1', null, null, null, '5', '0');
INSERT INTO `questions` VALUES ('50', '144', '3', '多选', '无', '1', '1', null, null, '2', '3', '0');
INSERT INTO `questions` VALUES ('51', '144', '4', '排序肯定', 'OJBK', '6', '1', null, null, '2', '3', '0');
INSERT INTO `questions` VALUES ('52', '146', '1', '尽快答复v你说的空间', 'v的开发商v麦当劳', '0', '0', null, null, null, null, null);
INSERT INTO `questions` VALUES ('53', '146', '2', '放电脑手机看你说的', 'v那就打开v你的伤口', '1', '0', null, null, null, null, null);
INSERT INTO `questions` VALUES ('54', '146', '3', 'vf的时刻均可看到', 'v你的接口是否v你的反馈v你', '3', '0', null, null, null, null, null);
INSERT INTO `questions` VALUES ('55', '146', '4', 'v的恐惧顺利打开房门vs', 'v肯定就是女的空间妇女', '4', '0', null, null, null, null, null);
INSERT INTO `questions` VALUES ('56', '146', '5', 'v发的是空军的富家女', 'v没地方开始v的父母', '6', '0', null, null, null, null, null);

-- ----------------------------
-- Table structure for submits
-- ----------------------------
DROP TABLE IF EXISTS `submits`;
CREATE TABLE `submits` (
  `sid` int(11) NOT NULL,
  `qnid` int(11) NOT NULL,
  `twt_name` varchar(20) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of submits
-- ----------------------------

-- ----------------------------
-- Table structure for usrs
-- ----------------------------
DROP TABLE IF EXISTS `usrs`;
CREATE TABLE `usrs` (
  `twt_name` varchar(255) NOT NULL,
  `user_number` bigint(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`twt_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of usrs
-- ----------------------------
INSERT INTO `usrs` VALUES ('Daisy_Louise', '3016218068', '2017-09-07 04:23:20', '2017-09-07 04:23:20');
INSERT INTO `usrs` VALUES ('newyingyi', '3016218105', '2017-09-18 04:46:37', '2017-09-18 04:46:37');
INSERT INTO `usrs` VALUES ('qyhxxx', '3016218094', '2017-09-01 02:58:32', '2017-09-01 02:58:32');
