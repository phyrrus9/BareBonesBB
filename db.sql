-- MySQL dump 10.16  Distrib 10.1.14-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: BareBonesBB
-- ------------------------------------------------------
-- Server version	10.1.14-MariaDB

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
-- Table structure for table `forums`
--

DROP TABLE IF EXISTS `forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forums` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `displayorder` tinyint(4) DEFAULT '0',
  `name` varchar(64) NOT NULL,
  `description` varchar(128) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `category` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forums`
--

LOCK TABLES `forums` WRITE;
/*!40000 ALTER TABLE `forums` DISABLE KEYS */;
INSERT INTO `forums` VALUES (8,0,'Announcements',NULL,NULL,1),(9,1,'News',NULL,NULL,1),(10,2,'General',NULL,NULL,1),(12,0,'Board Announcements','Board wide announcements',8,0),(13,1,'Introductions','Break the ice!',8,0),(14,2,'Suggestions','Suggest a new feature',8,0),(15,0,'Achievements','Do something noteworthy?',9,0),(16,1,'Internet','Whats new online?',9,0),(17,2,'Local News','What happened next door',9,0),(18,3,'World News','WWIII started?',9,0),(19,0,'The Lounge','Mildly off topic discussion',10,0),(20,1,'/dev/random','Complete random talk',10,0);
/*!40000 ALTER TABLE `forums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `override_userqueue`
--

DROP TABLE IF EXISTS `override_userqueue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `override_userqueue` (
  `p_uid` tinyint(4) NOT NULL DEFAULT '0',
  `p_post` tinyint(4) NOT NULL DEFAULT '0',
  `p_reply` tinyint(4) NOT NULL DEFAULT '0',
  `p_lock_own` tinyint(4) NOT NULL DEFAULT '0',
  `p_unlock_own` tinyint(4) NOT NULL DEFAULT '0',
  `p_delete_own` tinyint(4) NOT NULL DEFAULT '0',
  `p_warn` tinyint(4) NOT NULL DEFAULT '0',
  `p_manage_flags` tinyint(4) NOT NULL DEFAULT '0',
  `p_move` tinyint(4) NOT NULL DEFAULT '0',
  `p_lock` tinyint(4) NOT NULL DEFAULT '0',
  `p_delete` tinyint(4) NOT NULL DEFAULT '0',
  `p_ban` tinyint(4) NOT NULL DEFAULT '0',
  `p_moderator` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `override_userqueue`
--

LOCK TABLES `override_userqueue` WRITE;
/*!40000 ALTER TABLE `override_userqueue` DISABLE KEYS */;
/*!40000 ALTER TABLE `override_userqueue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `uid` int(11) NOT NULL,
  `can_view` tinyint(4) NOT NULL DEFAULT '1',
  `can_post` int(11) NOT NULL DEFAULT '0',
  `can_reply` int(11) NOT NULL DEFAULT '0',
  `can_lock` tinyint(4) NOT NULL DEFAULT '0',
  `can_unlock` tinyint(4) NOT NULL DEFAULT '0',
  `can_lock_own` tinyint(4) NOT NULL DEFAULT '0',
  `can_unlock_own` tinyint(4) NOT NULL DEFAULT '0',
  `can_move` tinyint(4) NOT NULL DEFAULT '0',
  `can_delete` tinyint(4) NOT NULL DEFAULT '0',
  `can_delete_own` tinyint(4) NOT NULL DEFAULT '0',
  `can_manage_flags` tinyint(4) NOT NULL DEFAULT '0',
  `can_warn` tinyint(4) NOT NULL DEFAULT '0',
  `can_ban` tinyint(4) NOT NULL DEFAULT '0',
  `can_create_user` tinyint(4) NOT NULL DEFAULT '0',
  `can_delete_user` tinyint(4) NOT NULL DEFAULT '0',
  `can_create_forum` tinyint(4) NOT NULL DEFAULT '0',
  `can_delete_forum` tinyint(4) NOT NULL DEFAULT '0',
  `is_moderator` tinyint(4) NOT NULL DEFAULT '0',
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `is_super` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),(2,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `whendt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parentpid` int(11) DEFAULT NULL,
  `msgtitle` varchar(64) DEFAULT NULL,
  `message` varchar(8192) DEFAULT NULL,
  `locked` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (25,2,1,'2016-06-10 19:29:15',NULL,'Title','Message',0),(26,2,1,'2016-06-10 19:29:46',NULL,'Title2','Message',0),(34,NULL,1,'2016-06-10 19:47:48',26,'RE: Title2','Hello, World!',0),(35,12,1,'2016-06-10 20:06:37',NULL,'Hello, World!','This is the first post!',0);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userqueue`
--

DROP TABLE IF EXISTS `userqueue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userqueue` (
  `p_uid` tinyint(4) NOT NULL DEFAULT '-1',
  `p_post` tinyint(4) NOT NULL DEFAULT '-1',
  `p_reply` tinyint(4) NOT NULL DEFAULT '-1',
  `p_lock_own` tinyint(4) NOT NULL DEFAULT '-1',
  `p_unlock_own` tinyint(4) NOT NULL DEFAULT '-1',
  `p_delete_own` tinyint(4) NOT NULL DEFAULT '-1',
  `p_warn` tinyint(4) NOT NULL DEFAULT '-1',
  `p_manage_flags` tinyint(4) NOT NULL DEFAULT '-1',
  `p_move` tinyint(4) NOT NULL DEFAULT '-1',
  `p_lock` tinyint(4) NOT NULL DEFAULT '-1',
  `p_delete` tinyint(4) NOT NULL DEFAULT '-1',
  `p_ban` tinyint(4) NOT NULL DEFAULT '-1',
  `p_moderator` tinyint(4) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userqueue`
--

LOCK TABLES `userqueue` WRITE;
/*!40000 ALTER TABLE `userqueue` DISABLE KEYS */;
/*!40000 ALTER TABLE `userqueue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','*26F47B878EE2CB233E45DDC0A5F0E4BFE9120112','admin@localhost'),(2,'test','*94BDCEBE19083CE2A1F959FD02F964C7AF4CFC29','test@localhost');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-10 20:22:56
