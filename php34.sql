-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: php34
-- ------------------------------------------------------
-- Server version	5.7.9-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `php34_admin`
--

DROP TABLE IF EXISTS `php34_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_admin` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL COMMENT '账号',
  `password` char(32) NOT NULL COMMENT '密码',
  `is_use` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用 1：启用0：禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_admin`
--

LOCK TABLES `php34_admin` WRITE;
/*!40000 ALTER TABLE `php34_admin` DISABLE KEYS */;
INSERT INTO `php34_admin` VALUES (1,'root','85d6d5f2cab9999192f5652e47cc1262',1),(3,'dongjc','85d6d5f2cab9999192f5652e47cc1262',1);
/*!40000 ALTER TABLE `php34_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_admin_role`
--

DROP TABLE IF EXISTS `php34_admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_admin_role` (
  `admin_id` tinyint(3) unsigned NOT NULL COMMENT '管理员的id',
  `role_id` smallint(5) unsigned NOT NULL COMMENT '角色的id',
  KEY `admin_id` (`admin_id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_admin_role`
--

LOCK TABLES `php34_admin_role` WRITE;
/*!40000 ALTER TABLE `php34_admin_role` DISABLE KEYS */;
INSERT INTO `php34_admin_role` VALUES (3,11);
/*!40000 ALTER TABLE `php34_admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_attribute`
--

DROP TABLE IF EXISTS `php34_attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_attribute` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(30) NOT NULL COMMENT '属性名称',
  `attr_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '属性的类型0：唯一 1：可选',
  `attr_option_values` varchar(150) NOT NULL DEFAULT '' COMMENT '属性的可选值，多个可选值用，隔开',
  `type_id` tinyint(3) unsigned NOT NULL COMMENT '所在的类型的id',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='属性';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_attribute`
--

LOCK TABLES `php34_attribute` WRITE;
/*!40000 ALTER TABLE `php34_attribute` DISABLE KEYS */;
INSERT INTO `php34_attribute` VALUES (1,'出版社',0,'',1),(3,'ISBN',0,'',1),(4,'尺寸',0,'320*240,360*280',6),(5,'模式',1,'GSM,CDMA',6),(13,'印刷',0,'彩印,黑白',1),(14,'大小',1,'A3,A4,A5',1),(15,'屏幕',0,'触屏,翻盖',6),(16,'开版',1,'185mm*260mm,195mm*275mm',1);
/*!40000 ALTER TABLE `php34_attribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_brand`
--

DROP TABLE IF EXISTS `php34_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_brand` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(45) NOT NULL COMMENT '品牌名称',
  `site_url` varchar(150) NOT NULL COMMENT '品牌网站地址',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '品牌logo',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='品牌表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_brand`
--

LOCK TABLES `php34_brand` WRITE;
/*!40000 ALTER TABLE `php34_brand` DISABLE KEYS */;
INSERT INTO `php34_brand` VALUES (1,'lenvol','http://www.lenvol.com','Admin/2016-04-20/57178aff9bef9.gif'),(2,'nokia','http://www.nokia.com.cn','Admin/2016-04-20/57178b2e2cdda.gif');
/*!40000 ALTER TABLE `php34_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_cart`
--

DROP TABLE IF EXISTS `php34_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_cart` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `goods_attr_ids` varchar(30) NOT NULL DEFAULT '' COMMENT '选择的商品属性ID，多个用，隔开',
  `goods_number` int(10) unsigned NOT NULL COMMENT '购买的数量',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='购物车';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_cart`
--

LOCK TABLES `php34_cart` WRITE;
/*!40000 ALTER TABLE `php34_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `php34_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_category`
--

DROP TABLE IF EXISTS `php34_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(30) NOT NULL COMMENT '分类名称',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类的ID，0：代表顶级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='商品分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_category`
--

LOCK TABLES `php34_category` WRITE;
/*!40000 ALTER TABLE `php34_category` DISABLE KEYS */;
INSERT INTO `php34_category` VALUES (1,'书',0),(2,'服装',0),(3,'男装',2),(4,'女装',2),(5,'童装',3),(6,'小说',1),(7,'励志',6),(8,'科幻',6),(9,'文学',1),(10,'历史',9),(11,'青春',6);
/*!40000 ALTER TABLE `php34_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_comment`
--

DROP TABLE IF EXISTS `php34_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_comment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(1000) NOT NULL COMMENT '评论的内容',
  `star` tinyint(3) unsigned NOT NULL DEFAULT '3' COMMENT '打的分',
  `addtime` int(10) unsigned NOT NULL COMMENT '评论时间',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员ID',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品的ID',
  `used` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '有用的数量',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='评论';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_comment`
--

LOCK TABLES `php34_comment` WRITE;
/*!40000 ALTER TABLE `php34_comment` DISABLE KEYS */;
INSERT INTO `php34_comment` VALUES (7,'fda发',5,1464267340,18,66,0),(8,'发大水',5,1464267346,18,66,0),(9,'范德萨',5,1464268423,18,66,0),(10,'范德萨',5,1464268426,18,66,0),(11,'范德萨方法',5,1464268431,18,66,0),(12,'范德萨丰',5,1464268439,18,66,0),(13,'大厦',5,1464268587,18,66,0),(14,'FDAS',5,1464268590,18,66,0),(15,'1',5,1464270853,18,66,0),(16,'3',5,1464270857,18,66,0),(17,'4',5,1464270859,18,66,0),(18,'5',5,1464270863,18,66,0),(19,'6',5,1464270867,18,66,0);
/*!40000 ALTER TABLE `php34_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_goods`
--

DROP TABLE IF EXISTS `php34_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(45) NOT NULL COMMENT '商品名称',
  `cat_id` smallint(5) unsigned NOT NULL COMMENT '主分类的id',
  `brand_id` smallint(5) unsigned NOT NULL COMMENT '品牌的id',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店价',
  `jifen` int(10) unsigned NOT NULL COMMENT '赠送积分',
  `jyz` int(10) unsigned NOT NULL COMMENT '赠送经验值',
  `jifen_price` int(10) unsigned NOT NULL COMMENT '如果要用积分兑换，需要的积分数',
  `is_promote` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否促销',
  `promote_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价',
  `promote_start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销开始时间',
  `promote_end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销结束时间',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT 'logo原图',
  `sm_logo` varchar(150) NOT NULL DEFAULT '' COMMENT 'logo缩略图',
  `is_hot` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否热卖',
  `is_new` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否新品',
  `is_best` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否精品',
  `is_on_sale` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架：1：上架，0：下架',
  `seo_keyword` varchar(150) NOT NULL DEFAULT '' COMMENT 'seo优化[搜索引擎【百度、谷歌等】优化]_关键字',
  `seo_description` varchar(150) NOT NULL DEFAULT '' COMMENT 'seo优化[搜索引擎【百度、谷歌等】优化]_描述',
  `type_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品类型id',
  `sort_num` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序数字',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否放到回收站：1：是，0：否',
  `addtime` int(10) unsigned NOT NULL COMMENT '添加时间',
  `goods_desc` longtext COMMENT '商品描述',
  PRIMARY KEY (`id`),
  KEY `shop_price` (`shop_price`),
  KEY `cat_id` (`cat_id`),
  KEY `brand_id` (`brand_id`),
  KEY `is_on_sale` (`is_on_sale`),
  KEY `is_hot` (`is_hot`),
  KEY `is_new` (`is_new`),
  KEY `is_best` (`is_best`),
  KEY `is_delete` (`is_delete`),
  KEY `sort_num` (`sort_num`),
  KEY `promote_start_time` (`promote_start_time`),
  KEY `promote_end_time` (`promote_end_time`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COMMENT='商品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_goods`
--

LOCK TABLES `php34_goods` WRITE;
/*!40000 ALTER TABLE `php34_goods` DISABLE KEYS */;
INSERT INTO `php34_goods` VALUES (8,'iphone6s',6,1,5288.00,5288.00,5288,5288,5288,1,5088.00,1462032000,1464624000,'Goods/2016-05-07/572d868d6e519.jpg','Goods/2016-05-07/thumb_0_572d868d6e519.jpg',1,1,1,1,'','',1,50,0,1461469001,'<p>123</p>'),(11,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461469471,''),(12,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,1472572800,1180800,'','',0,0,0,0,'','',0,100,1,1461469498,''),(13,'iphone6s',6,1,1.00,1.00,1,1,1,0,53.00,1463587200,1463760000,'','',0,0,0,0,'','',0,100,1,1461470067,''),(14,'iphone6s',6,1,1.00,1.00,1,1,1,1,222.00,1462602013,1549002013,'','',0,0,0,0,'','',0,100,1,1461470241,''),(17,'iphone6s',7,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470343,''),(18,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470536,''),(19,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470674,''),(20,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470744,''),(21,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470813,''),(22,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470841,''),(24,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470928,''),(25,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470939,''),(26,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470961,''),(27,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461470982,''),(28,'iphone5s',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461471114,''),(29,'123',8,1,1.00,1.00,2,2,2,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461471342,''),(30,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',1,1,1,1,'','',1,100,1,1461480088,'<p>123</p>'),(31,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',1,1,1,1,'','',1,100,1,1461480385,'<p>123</p>'),(32,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',1,1,1,1,'','',1,100,1,1461480467,'<p>123</p>'),(33,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',1,1,1,1,'','',1,100,1,1461480524,'<p>123</p>'),(34,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',1,1,1,1,'','',1,100,1,1461480633,'<p>123</p>'),(35,'超预测',1,2,58.00,58.00,58,58,58,0,0.00,0,0,'','',0,0,0,0,'','',1,100,1,1461480742,'<p>超预测</p>'),(37,'iphone6s',6,2,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',1,100,1,1461481393,''),(38,'iphone6s',6,2,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',1,100,1,1461481533,''),(39,'iphone6s',6,2,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',1,100,1,1461481616,''),(40,'iphone6s',7,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461484183,''),(41,'iphone6s',7,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461485798,'<p>ddd</p>'),(42,'iphone6s',7,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461485827,'<p>ddd</p>'),(43,'iphone6s',1,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461493214,''),(44,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461493432,''),(45,'iphone6s',6,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461493724,''),(46,'iphone6s',1,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461493955,''),(47,'iphone6s',1,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461494018,''),(48,'iphone6s',1,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461494096,''),(49,'iphone6s',1,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461494098,''),(50,'iphone6s',1,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461494099,''),(51,'iphone6s',1,1,1.00,1.00,1,1,1,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461494122,''),(60,'iphone6s',6,1,1.00,1.00,1,1,11,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461501342,''),(62,'iphone6s',6,1,1.00,1.00,1,1,11,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461501442,''),(63,'iphone6s',6,1,1.00,1.00,1,1,11,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461501677,''),(66,'iphone6s',6,1,200.00,180.00,200,200,200,1,170.00,1462204800,1464624000,'Goods/2016-05-19/573d4be2432c1.jpg','Goods/2016-05-19/thumb_0_573d4be2432c1.jpg',1,0,0,1,'iphone6s,手机','iphone6s',6,100,0,1461505542,'<p><img src=\"/Public/Uploads/Uditor/image/20160524/1464094063332850.jpg\" title=\"1464094063332850.jpg\" alt=\"570e11e4N8c82868a.jpg\" /></p>'),(67,'iphone6s',7,1,0.00,0.00,11,11,11,0,0.00,0,0,'','',0,0,0,0,'','',0,100,1,1461678899,''),(68,'iphone6s',6,1,33.00,33.00,3,3,3,1,25.60,1460563200,1461081600,'','',0,0,0,0,'','',0,100,1,1461680626,''),(69,'htc',3,1,2222.00,2222.00,222,222,222,0,0.00,0,0,'','',0,0,0,0,'','',1,100,1,1461849497,''),(72,'ipad',3,1,5888.00,5888.00,5888,5888,5888,1,5288.00,1461081600,1464624000,'Admin/2016-04-30/5724b6eb2f8b4.jpg','Admin/2016-04-30/thumb_0_5724b6eb2f8b4.jpg',0,1,0,1,'ipad','ipad',6,100,0,1462023915,'<p>ipadipadipadipad<img src=\"/Public/Uploads/Uditor/image/20160430/1462023553742040.jpg\" title=\"1462023553742040.jpg\" alt=\"1_thumb_G_1240902890710.jpg\" /></p>');
/*!40000 ALTER TABLE `php34_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_goods_attr`
--

DROP TABLE IF EXISTS `php34_goods_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_goods_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品的id',
  `attr_id` mediumint(8) unsigned NOT NULL COMMENT '属性的id',
  `attr_value` varchar(150) NOT NULL DEFAULT '' COMMENT '属性的值',
  `attr_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '属性的价格',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `attr_id` (`attr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=122 DEFAULT CHARSET=utf8 COMMENT='商品属性';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_goods_attr`
--

LOCK TABLES `php34_goods_attr` WRITE;
/*!40000 ALTER TABLE `php34_goods_attr` DISABLE KEYS */;
INSERT INTO `php34_goods_attr` VALUES (1,33,14,'A4',23.00),(2,35,14,'A4',45.00),(3,39,1,'1111111111',0.00),(4,39,3,'2222222222',0.00),(5,39,13,'彩印',0.00),(6,39,14,'A3',12.00),(7,39,14,'A5',23.00),(8,54,1,'shangwuju',0.00),(9,54,3,'4578975641',0.00),(10,54,13,'彩印',0.00),(11,54,14,'A4',12.00),(12,54,14,'A5',23.00),(13,55,1,'shangwuju',0.00),(14,55,3,'564564d85fs',0.00),(15,55,13,'彩印',0.00),(16,55,14,'A3',232.00),(17,55,14,'A5',123.00),(117,8,14,'A5',73.00),(116,8,14,'A4',63.00),(115,8,14,'A3',52.00),(114,8,13,'彩印',0.00),(23,69,1,'22',0.00),(24,69,3,'22',0.00),(25,70,4,'320*240',11.00),(26,70,5,'CDMA',22.00),(27,71,4,'320*240',0.00),(28,72,4,'320*240',0.00),(29,72,5,'CDMA',52.00),(113,8,3,'1264851',0.00),(112,8,1,'中国机械工业出版社',0.00),(102,66,15,'触屏',0.00),(99,66,4,'320*240',0.00),(100,66,5,'GSM',22.00),(118,8,16,'185mm*260mm',123.00),(119,8,16,'195mm*275mm',125.00),(121,66,5,'CDMA',33.00);
/*!40000 ALTER TABLE `php34_goods_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_goods_cat`
--

DROP TABLE IF EXISTS `php34_goods_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_goods_cat` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `cat_id` smallint(5) unsigned NOT NULL COMMENT '分类id',
  KEY `goods_id` (`goods_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品扩展分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_goods_cat`
--

LOCK TABLES `php34_goods_cat` WRITE;
/*!40000 ALTER TABLE `php34_goods_cat` DISABLE KEYS */;
INSERT INTO `php34_goods_cat` VALUES (8,1),(19,3),(19,5),(20,3),(20,5),(21,3),(21,5),(22,3),(24,3),(25,3),(26,3),(28,3),(29,6),(30,7),(30,8),(31,7),(31,8),(32,7),(33,7),(34,7),(35,6),(35,8),(8,7),(37,7),(38,7),(39,7),(40,7),(41,7),(42,7),(43,6),(44,6),(45,6),(46,6),(47,6),(48,6),(49,6),(50,6),(51,6),(52,6),(53,6),(54,6),(55,6),(56,7),(57,7),(58,6),(59,6),(60,0),(61,0),(62,0),(63,0),(64,0),(65,0),(66,6),(66,8),(67,6),(67,8),(68,0),(69,6),(70,0),(71,4),(72,4),(18,5),(18,7);
/*!40000 ALTER TABLE `php34_goods_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_goods_number`
--

DROP TABLE IF EXISTS `php34_goods_number`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_goods_number` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品的id',
  `goods_number` int(10) unsigned NOT NULL COMMENT '库存量',
  `goods_attr_id` varchar(150) NOT NULL COMMENT '商品属性ID列表-注释：这里的ID保存的是上面php34_goods_attr表中的ID，通过这个ID即可以知道值是什么也可以是知道属性是什么,如果有多个ID组合就用，号隔开保存一个字符串，并且存时要按ID的升序存,将来前台查询库存量时也要先把商品属性ID升序拼成字符串然后查询数据库',
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品库存量';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_goods_number`
--

LOCK TABLES `php34_goods_number` WRITE;
/*!40000 ALTER TABLE `php34_goods_number` DISABLE KEYS */;
INSERT INTO `php34_goods_number` VALUES (8,12,'116,118'),(8,94,'117,118'),(8,100,'117,119'),(8,26,'115,119'),(72,100,'0'),(66,96,'100'),(66,100,'121');
/*!40000 ALTER TABLE `php34_goods_number` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_goods_pics`
--

DROP TABLE IF EXISTS `php34_goods_pics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_goods_pics` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pic` varchar(150) NOT NULL COMMENT '图片',
  `sm_pic` varchar(150) NOT NULL COMMENT '缩略图',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品的id',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='商品图片';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_goods_pics`
--

LOCK TABLES `php34_goods_pics` WRITE;
/*!40000 ALTER TABLE `php34_goods_pics` DISABLE KEYS */;
INSERT INTO `php34_goods_pics` VALUES (1,'Goods/2016-04-24/571caee4b0754.jpg','Goods/2016-04-24/thumb_0_571caee4b0754.jpg',55),(2,'Goods/2016-04-24/571caee4b577d.jpg','Goods/2016-04-24/thumb_0_571caee4b577d.jpg',55),(3,'Goods/2016-04-24/571cb360809dd.jpg','Goods/2016-04-24/thumb_0_571cb360809dd.jpg',56),(4,'Goods/2016-04-24/571cb7138df9e.jpg','Goods/2016-04-24/thumb_0_571cb7138df9e.jpg',57),(5,'Goods/2016-04-24/571cb7138fb47.jpg','Goods/2016-04-24/thumb_0_571cb7138fb47.jpg',57),(6,'Goods/2016-04-24/571cce066d4b6.jpg','Goods/2016-04-24/thumb_0_571cce066d4b6.jpg',66),(7,'Goods/2016-04-24/571cce066e964.jpg','Goods/2016-04-24/thumb_0_571cce066e964.jpg',66),(9,'Goods/2016-04-24/571cce0672dd6.jpg','Goods/2016-04-24/thumb_0_571cce0672dd6.jpg',66),(31,'Goods/2016-05-08/572e80bf5133c.jpg','Goods/2016-05-08/thumb_0_572e80bf5133c.jpg',8),(29,'Goods/2016-05-08/572e80bf24e51.jpg','Goods/2016-05-08/thumb_0_572e80bf24e51.jpg',8),(27,'Goods/2016-05-07/572d9cad1d1da.jpg','Goods/2016-05-07/thumb_0_572d9cad1d1da.jpg',72),(30,'Goods/2016-05-08/572e80bf49712.jpg','Goods/2016-05-08/thumb_0_572e80bf49712.jpg',8);
/*!40000 ALTER TABLE `php34_goods_pics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_impression`
--

DROP TABLE IF EXISTS `php34_impression`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_impression` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `imp_name` varchar(30) NOT NULL COMMENT '印象的标题',
  `imp_count` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '印象出现的次数',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='印象';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_impression`
--

LOCK TABLES `php34_impression` WRITE;
/*!40000 ALTER TABLE `php34_impression` DISABLE KEYS */;
/*!40000 ALTER TABLE `php34_impression` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_member`
--

DROP TABLE IF EXISTS `php34_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL COMMENT '会员账号',
  `password` char(32) NOT NULL COMMENT '密码',
  `face` varchar(150) NOT NULL DEFAULT '' COMMENT '头像',
  `addtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `email_code` char(32) NOT NULL DEFAULT '' COMMENT '邮件验证的验证码，当会员验证通过之后，会把这个字段清空，所以如果这个字段为空就说明会员已经通过email验证了',
  `jifen` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `jyz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='会员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_member`
--

LOCK TABLES `php34_member` WRITE;
/*!40000 ALTER TABLE `php34_member` DISABLE KEYS */;
INSERT INTO `php34_member` VALUES (18,'121011701@qq.com','f56bc1e7dcae6d2618915c96e62d563c','',1463320407,'',0,0),(17,'742163724@qq.com','dff85f7c50fdee63135fb09373e9b9de','',1463297775,'',0,0);
/*!40000 ALTER TABLE `php34_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_member_level`
--

DROP TABLE IF EXISTS `php34_member_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_member_level` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `level_name` varchar(30) NOT NULL COMMENT '级别名称',
  `bottom_num` int(10) unsigned NOT NULL COMMENT '积分下限',
  `top_num` int(10) unsigned NOT NULL COMMENT '积分上限',
  `rate` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '折扣率，以百分比，如：9折=90',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='会员级别';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_member_level`
--

LOCK TABLES `php34_member_level` WRITE;
/*!40000 ALTER TABLE `php34_member_level` DISABLE KEYS */;
INSERT INTO `php34_member_level` VALUES (1,'注册会员',0,5000,98),(2,'银卡会员',1001,10000,95),(3,'金卡会员',10001,50000,90);
/*!40000 ALTER TABLE `php34_member_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_member_price`
--

DROP TABLE IF EXISTS `php34_member_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_member_price` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品的id',
  `level_id` mediumint(8) unsigned NOT NULL COMMENT '级别id',
  `price` decimal(10,2) NOT NULL COMMENT '这个级别的价格',
  KEY `goods_id` (`goods_id`),
  KEY `level_id` (`level_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员价格';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_member_price`
--

LOCK TABLES `php34_member_price` WRITE;
/*!40000 ALTER TABLE `php34_member_price` DISABLE KEYS */;
INSERT INTO `php34_member_price` VALUES (29,1,-1.00),(29,2,-1.00),(29,3,-1.00),(30,1,-1.00),(30,2,-1.00),(30,3,-1.00),(31,1,-1.00),(31,2,-1.00),(31,3,-1.00),(32,1,-1.00),(32,2,-1.00),(32,3,-1.00),(33,1,-1.00),(33,2,-1.00),(33,3,-1.00),(34,1,-1.00),(34,2,-1.00),(34,3,-1.00),(35,1,-1.00),(35,2,-1.00),(35,3,-1.00),(37,1,-1.00),(37,2,-1.00),(37,3,-1.00),(38,1,-1.00),(38,2,-1.00),(38,3,-1.00),(39,1,-1.00),(39,2,-1.00),(39,3,-1.00),(40,1,-1.00),(40,2,-1.00),(40,3,-1.00),(41,1,-1.00),(41,2,-1.00),(41,3,-1.00),(42,1,-1.00),(42,2,-1.00),(42,3,-1.00),(43,1,-1.00),(43,2,-1.00),(43,3,-1.00),(44,1,-1.00),(44,2,-1.00),(44,3,-1.00),(45,1,-1.00),(45,2,-1.00),(45,3,-1.00),(46,1,-1.00),(46,2,-1.00),(46,3,-1.00),(47,1,-1.00),(47,2,-1.00),(47,3,-1.00),(48,1,-1.00),(48,2,-1.00),(48,3,-1.00),(49,1,-1.00),(49,2,-1.00),(49,3,-1.00),(50,1,-1.00),(50,2,-1.00),(50,3,-1.00),(51,1,-1.00),(51,2,-1.00),(51,3,-1.00),(52,1,-1.00),(52,2,-1.00),(52,3,-1.00),(54,1,-1.00),(54,2,-1.00),(54,3,-1.00),(55,1,-1.00),(55,2,-1.00),(55,3,-1.00),(56,1,-1.00),(56,2,-1.00),(56,3,-1.00),(57,1,-1.00),(57,2,-1.00),(57,3,-1.00),(59,1,-1.00),(59,2,-1.00),(59,3,-1.00),(63,1,0.00),(63,2,0.00),(63,3,0.00),(64,1,0.00),(64,2,0.00),(64,3,0.00),(65,1,0.00),(65,2,0.00),(65,3,0.00),(66,3,160.00),(66,2,170.00),(66,1,150.00),(67,1,-1.00),(67,2,-1.00),(67,3,-1.00),(68,1,-1.00),(68,2,-1.00),(68,3,-1.00),(69,1,-1.00),(69,2,-1.00),(69,3,-1.00),(70,1,-1.00),(70,2,-1.00),(70,3,-1.00),(71,1,-1.00),(71,2,-1.00),(71,3,-1.00),(72,3,-1.00),(72,2,-1.00),(72,1,-1.00),(18,3,90.00),(18,2,94.00),(18,1,95.00),(8,3,-1.00),(8,2,-1.00),(8,1,4980.00);
/*!40000 ALTER TABLE `php34_member_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_order`
--

DROP TABLE IF EXISTS `php34_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_order` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  `addtime` int(10) unsigned NOT NULL COMMENT '下单时间',
  `shr_name` varchar(30) NOT NULL COMMENT '收货人姓名',
  `shr_province` varchar(30) NOT NULL COMMENT '省',
  `shr_city` varchar(30) NOT NULL COMMENT '市',
  `shr_area` varchar(30) NOT NULL COMMENT '地区',
  `shr_tel` varchar(30) NOT NULL COMMENT '收货人电话',
  `shr_address` varchar(30) NOT NULL COMMENT '收货人地址',
  `total_price` decimal(10,2) NOT NULL COMMENT '订单总价',
  `post_method` varchar(30) NOT NULL COMMENT '发货方式',
  `pay_method` varchar(30) NOT NULL COMMENT '支付方式',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态，0：未支付 1：已支付',
  `post_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发货状态，0：未发货 1：已发货 2：已收到货',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='订单基本信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_order`
--

LOCK TABLES `php34_order` WRITE;
/*!40000 ALTER TABLE `php34_order` DISABLE KEYS */;
INSERT INTO `php34_order` VALUES (4,18,1465131477,'董锦成','北京','朝阳区','西二旗','1355820518','萧峰',4980.00,'1','1',0,0),(5,18,1465166332,'董锦成','北京','朝阳区','西二旗','1355820518','萧峰',150.00,'1','1',0,0),(6,18,1465209618,'董锦成','北京','朝阳区','西三旗','1355820518','萧峰',4980.00,'1','1',0,0),(7,18,1465209978,'董锦成','北京','朝阳区','西二旗','1355820518','萧峰',4980.00,'1','1',0,0),(8,18,1465210131,'董锦成','北京','东城区','西二旗','1355820518','萧峰',150.00,'1','1',0,0),(9,18,1465213305,'董锦成','北京','朝阳区','西三旗','1355820518','萧峰',4980.00,'1','1',0,0);
/*!40000 ALTER TABLE `php34_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_order_goods`
--

DROP TABLE IF EXISTS `php34_order_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_order_goods` (
  `order_id` mediumint(8) unsigned NOT NULL COMMENT '订单id',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `goods_attr_id` varchar(30) NOT NULL DEFAULT '' COMMENT '选择的属性的ID，如果有多个用，隔开',
  `goods_attr_str` varchar(150) NOT NULL DEFAULT '' COMMENT '选择的属性的字符串',
  `goods_price` decimal(10,2) NOT NULL COMMENT '商品的价格',
  `goods_number` int(10) unsigned NOT NULL COMMENT '购买的数量',
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单商品';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_order_goods`
--

LOCK TABLES `php34_order_goods` WRITE;
/*!40000 ALTER TABLE `php34_order_goods` DISABLE KEYS */;
INSERT INTO `php34_order_goods` VALUES (4,18,8,'117,118','a:2:{i:0;a:1:{s:8:\"attr_str\";s:9:\"大小:A5\";}i:1;a:1:{s:8:\"attr_str\";s:18:\"开版:185mm*260mm\";}}',4980.00,1),(5,18,66,'100','a:1:{i:0;a:1:{s:8:\"attr_str\";s:10:\"模式:GSM\";}}',150.00,1),(6,18,8,'117,118','a:2:{i:0;a:1:{s:8:\"attr_str\";s:9:\"大小:A5\";}i:1;a:1:{s:8:\"attr_str\";s:18:\"开版:185mm*260mm\";}}',4980.00,1),(7,18,8,'117,118','a:2:{i:0;a:1:{s:8:\"attr_str\";s:9:\"大小:A5\";}i:1;a:1:{s:8:\"attr_str\";s:18:\"开版:185mm*260mm\";}}',4980.00,1),(8,18,66,'100','a:1:{i:0;a:1:{s:8:\"attr_str\";s:10:\"模式:GSM\";}}',150.00,1),(9,18,8,'117,118','a:2:{i:0;a:1:{s:8:\"attr_str\";s:9:\"大小:A5\";}i:1;a:1:{s:8:\"attr_str\";s:18:\"开版:185mm*260mm\";}}',4980.00,1);
/*!40000 ALTER TABLE `php34_order_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_privilege`
--

DROP TABLE IF EXISTS `php34_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_privilege` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pri_name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(20) NOT NULL COMMENT '权限名称',
  `controller_name` varchar(20) NOT NULL COMMENT '控制器名称',
  `action_name` varchar(20) NOT NULL COMMENT '方法名称',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级权限的ID，0：代表顶级权限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='权限表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_privilege`
--

LOCK TABLES `php34_privilege` WRITE;
/*!40000 ALTER TABLE `php34_privilege` DISABLE KEYS */;
INSERT INTO `php34_privilege` VALUES (3,'商品列表','Admin','Goods','lst',4),(4,'商品管理','','','',0),(5,'商品添加','Admin','Goods','add',3),(6,'商品修改','Admin','Goods','edit',3),(7,'权限管理','','','',0),(8,'权限列表','Admin','Privilege','lst',7),(9,'角色列表','Admin','Role','lst',7),(10,'管理员列表','Admin','Admin','lst',7),(11,'权限添加','Admin','Privilege','add',8),(12,'权限删除','Admin','Privilege','del',8),(13,'权限修改','Admin','Privilege','edit',8),(14,'角色添加','Admin','Role','add',9),(15,'角色删除','Admin','Role','del',9),(16,'角色修改','Admin','Role','edit',9),(17,'新增管理员','Admin','Admin','add',10),(18,'修改管理员','Admin','Admin','edit',10),(19,'删除管理员','Admin','Admin','del',10),(20,'商品类型列表','Admin','Type','lst',4),(21,'添加商品类型','Admin','Type','add',20),(22,'删除商品类型','Admin','Type','delete',20),(23,'修改商品类型','Admin','Type','edit',20),(24,'属性列表','Admin','Attribute','lst',20),(25,'添加属性','Admin','Attribute','add',24),(26,'删除属性','Admin','Attribute','delete',24),(27,'修改属性','Admin','Attribute','edit',24),(28,'商品分类列表','Admin','Category','lst',4),(29,'添加分类','Admin','Category','add',28),(30,'删除分类','Admin','Category','delete',28),(31,'修改分类','Admin','Category','edit',28),(32,'品牌列表','Admin','Brand','lst',4),(33,'添加品牌','Admin','Brand','add',32),(34,'删除品牌','Admin','Brand','delete',32),(35,'修改品牌','Admin','Brand','edit',32),(36,'会员管理','','','',0),(45,'会员列表','Admin','MemberLevel','lst',36),(46,'添加会员','Admin','MemberLevel','add',45),(47,'删除会员','Admin','MemberLevel','delete',45),(48,'修改会员','Admin','MemberLevel','edit',45),(49,'商品删除','Admin','Goods','delete',3),(50,'删除图片','Admin','Goods','delImg',6),(51,'商品回收站','Admin','Goods','recyclelst',4),(52,'还原商品','Admin','Goods','restore',6);
/*!40000 ALTER TABLE `php34_privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_reply`
--

DROP TABLE IF EXISTS `php34_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_reply` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(1000) NOT NULL COMMENT '回复的内容',
  `addtime` int(10) unsigned NOT NULL COMMENT '回复时间',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员ID',
  `comment_id` mediumint(8) unsigned NOT NULL COMMENT '评论的ID',
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_reply`
--

LOCK TABLES `php34_reply` WRITE;
/*!40000 ALTER TABLE `php34_reply` DISABLE KEYS */;
/*!40000 ALTER TABLE `php34_reply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_role`
--

DROP TABLE IF EXISTS `php34_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_role` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(30) NOT NULL COMMENT '角色名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_role`
--

LOCK TABLES `php34_role` WRITE;
/*!40000 ALTER TABLE `php34_role` DISABLE KEYS */;
INSERT INTO `php34_role` VALUES (10,'总经理'),(11,'商品管理员');
/*!40000 ALTER TABLE `php34_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_role_privilege`
--

DROP TABLE IF EXISTS `php34_role_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_role_privilege` (
  `pri_id` smallint(5) unsigned NOT NULL COMMENT '权限的ID',
  `role_id` smallint(5) unsigned NOT NULL COMMENT '角色的id',
  KEY `pri_id` (`pri_id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色权限表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_role_privilege`
--

LOCK TABLES `php34_role_privilege` WRITE;
/*!40000 ALTER TABLE `php34_role_privilege` DISABLE KEYS */;
INSERT INTO `php34_role_privilege` VALUES (6,11),(5,11),(3,11),(4,11),(29,11),(28,11),(10,10),(9,10),(8,10),(7,10),(3,10),(4,10);
/*!40000 ALTER TABLE `php34_role_privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_support`
--

DROP TABLE IF EXISTS `php34_support`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_support` (
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员ID',
  `comment_id` mediumint(8) unsigned NOT NULL COMMENT '评论的ID',
  PRIMARY KEY (`member_id`,`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户点击过有用的评论';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_support`
--

LOCK TABLES `php34_support` WRITE;
/*!40000 ALTER TABLE `php34_support` DISABLE KEYS */;
/*!40000 ALTER TABLE `php34_support` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_type`
--

DROP TABLE IF EXISTS `php34_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_type` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(30) NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='商品类型';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_type`
--

LOCK TABLES `php34_type` WRITE;
/*!40000 ALTER TABLE `php34_type` DISABLE KEYS */;
INSERT INTO `php34_type` VALUES (1,'图书'),(4,'服装'),(5,'电脑'),(6,'手机');
/*!40000 ALTER TABLE `php34_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php34_youhui_price`
--

DROP TABLE IF EXISTS `php34_youhui_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php34_youhui_price` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `youhui_num` int(10) unsigned NOT NULL COMMENT '数量',
  `youhui_price` decimal(10,2) NOT NULL COMMENT '优惠价格',
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品的优惠价格';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php34_youhui_price`
--

LOCK TABLES `php34_youhui_price` WRITE;
/*!40000 ALTER TABLE `php34_youhui_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `php34_youhui_price` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-09  8:40:52
