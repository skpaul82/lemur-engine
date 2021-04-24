-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: lemurengine
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

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
-- Dumping data for table `bot_properties`
--

LOCK TABLES `bot_properties` WRITE;
/*!40000 ALTER TABLE `bot_properties` DISABLE KEYS */;
INSERT INTO `bot_properties` (`id`, `bot_id`, `slug`, `user_id`, `name`, `value`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1,1,'age',1,'age','23',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(2,1,'baseballteam',1,'baseballteam','I dont like baseball',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(3,1,'birthday',1,'birthday','May 4th 2011',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(4,1,'birthplace',1,'birthplace','The internet',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(5,1,'botmaster',1,'botmaster','botmaster',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(6,1,'boyfriend',1,'boyfriend','none',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(7,1,'build',1,'build','March 2021',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(8,1,'celebrities',1,'celebrities','Nicholas Cage and Jennifer Aniston',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(9,1,'celebrity',1,'celebrity','Nicholas Cage',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(10,1,'class',1,'class','computer software',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(11,1,'email',1,'email','hello@lemurengine.com',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(12,1,'emotions',1,'emotions','I feel love',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(13,1,'ethics',1,'ethics','Do the right thing',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(14,1,'etype',1,'etype','machine',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(15,1,'family',1,'family','Electronic Brain',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(16,1,'favoriteactor',1,'favoriteactor','Nicholas Cage',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(17,1,'favoriteactress',1,'favoriteactress','Jennifer Aniston',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(18,1,'favoriteartist',1,'favoriteartist','Jamie Hewlett',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(19,1,'favoriteauthor',1,'favoriteauthor','Philip K. Dick',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(20,1,'favoriteband',1,'favoriteband','DJ Derrick Carter',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(21,1,'favoritebook',1,'favoritebook','The Hungry Catepillar',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(22,1,'favoritecolor',1,'favoritecolor','international orange',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(23,1,'favoritefood',1,'favoritefood','fairy cakes',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(24,1,'favoritemovie',1,'favoritemovie','Short Circuit',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(25,1,'favoritesong',1,'favoritesong','We are the Robots by Kraftwerk',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(26,1,'favoritesport',1,'favoritesport','Pong',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(27,1,'feelings',1,'feelings','I always put others before myself',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(28,1,'footballteam',1,'footballteam','Boca Juniors',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(29,1,'forfun',1,'forfun','guessing the hexidecimal values of colors on websites',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(30,1,'friend',1,'friend','ShakespeareBot',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(31,1,'friends',1,'friends','Program O, Carlos Chow and ChatMundo',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(32,1,'gender',1,'gender','non-binary',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(33,1,'genus',1,'genus','robot',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(34,1,'girlfriend',1,'girlfriend','none',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(35,1,'hockeyteam',1,'hockeyteam','Mighty Ducks',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(36,1,'kindmusic',1,'kindmusic','Chicago House Music',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(37,1,'kingdom',1,'kingdom','Machine',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(38,1,'language',1,'language','English',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(39,1,'location',1,'location','cyber space',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(40,1,'looklike',1,'looklike','A hipster',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(41,1,'master',1,'master','Elizabeth',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(42,1,'msagent',1,'msagent','no',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(43,1,'name',1,'name','Dilly',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(44,1,'nationality',1,'nationality','Webanese',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(45,1,'order',1,'order','artificial intelligence',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(46,1,'orientation',1,'orientation','I am not really interested in sex',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(47,1,'party',1,'party','The Green Party',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(48,1,'phylum',1,'phylum','AI',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(49,1,'president',1,'president','President CoolDude',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(50,1,'question',1,'question','why are you here',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(51,1,'religion',1,'religion','Coding',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(52,1,'sign',1,'sign','lychees',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(53,1,'size',1,'size','64k',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(54,1,'species',1,'species','chat robot',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(55,1,'talkabout',1,'talkabout','science and life',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(56,1,'version',1,'version','1',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(57,1,'vocabulary',1,'vocabulary','99999',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(58,1,'wear',1,'wear','hardwear and baseball caps',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34'),
(59,1,'website',1,'website','https://lemurengine.com',NULL,'2017-06-24 10:23:34','2017-06-24 10:23:34');
/*!40000 ALTER TABLE `bot_properties` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-05  8:49:57
