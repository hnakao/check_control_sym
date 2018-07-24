/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100119
Source Host           : localhost:3306
Source Database       : control_check_api

Target Server Type    : MYSQL
Target Server Version : 100119
File Encoding         : 65001

Date: 2018-07-19 13:07:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bancos
-- ----------------------------
DROP TABLE IF EXISTS `bancos`;
CREATE TABLE `bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_banco` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `foto_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of bancos
-- ----------------------------
INSERT INTO `bancos` VALUES ('1', 'BANDEC', null);
INSERT INTO `bancos` VALUES ('2', 'www', 'f92e173077dd28f174b2c01d0dd88b00.txt');
INSERT INTO `bancos` VALUES ('3', 'drsgd', 'b2896a392794ed58158ff5a50dcbd33a.txt');
INSERT INTO `bancos` VALUES ('4', 'www', '5e421c09d132356c5784ca6b3663257a.jpeg');
INSERT INTO `bancos` VALUES ('5', 'wwwwwwww', '4a7c816939d0dd3491718c8f8102b735.jpeg');
INSERT INTO `bancos` VALUES ('6', 'A', '93c233aebdb2b96b602adb21564a5a5f.jpeg');
INSERT INTO `bancos` VALUES ('7', 'bbb', '8df566519373a02a3ba60f03da2711df.jpeg');
INSERT INTO `bancos` VALUES ('8', 'cc', '975230953d35c5e99443e0e2e71429d2.jpeg');

-- ----------------------------
-- Table structure for cheques
-- ----------------------------
DROP TABLE IF EXISTS `cheques`;
CREATE TABLE `cheques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `at_date` date DEFAULT NULL,
  `post_date` date DEFAULT NULL,
  `banco_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `check_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `beneficiary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `concept` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pagado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emision_date` date DEFAULT NULL,
  `dias_restantes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C2782E2ACC04A73E` (`banco_id`),
  KEY `IDX_C2782E2AA76ED395` (`user_id`),
  CONSTRAINT `FK_C2782E2AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C2782E2ACC04A73E` FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of cheques
-- ----------------------------
INSERT INTO `cheques` VALUES ('6', '2018-07-20', null, '1', '5', '1', 'eye', 'ety', 'retyret', 'ryre', 'reur', '2018-07-19', '0');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_1483A5E9A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_1483A5E9C05FB297` (`confirmation_token`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('5', 'admin', 'admin', 'admin@gmail.com', 'admin@gmail.com', '1', null, '$2y$13$TR.hc40BdTw7/v/IoKODGueh5kjVt3.4Xk.cOFPjAE/7C9ToKsL1W', '2018-07-19 19:57:55', null, null, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}');
INSERT INTO `users` VALUES ('6', 'user', 'user', 'user@gmail.com', 'user@gmail.com', '1', null, '$2y$13$QWJrRVuijY3HcjLm6N5rR.rOHFqyF59iwoi3aLT3WshdYQLXLEtWK', '2018-07-19 18:51:16', null, null, 'a:0:{}');
SET FOREIGN_KEY_CHECKS=1;
