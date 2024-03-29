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
-- Dumping data for table `word_spellings`
--

LOCK TABLES `word_spellings` WRITE;
/*!40000 ALTER TABLE `word_spellings` DISABLE KEYS */;
INSERT INTO `word_spellings` (`id`, `user_id`, `word_spelling_group_id`, `slug`, `word`, `replacement`, `deleted_at`, `created_at`, `updated_at`) VALUES
(NULL,1,2,'r-u','r u','are you',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'are-u','are u','are you',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'r-you','r you','are you',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'u-r','u r','you are',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'u-are','u are','you are',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'you-r','you r','you are',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'rofl','rofl','rolling on floor laughing',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'stfu','stfu','shut the fuck up',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'icymi','icymi','in case you missed it',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'tldr','tldr',"too long, didn't read",NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'tl-dr','tl;dr',"too long, didn't read",NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'lmk','lmk','let me know',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'nvm','nvm','nevermind',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'tgif','tgif',"thank goodness it's friday",NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'tbh','tbh','to be honest',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'tbf','tbf','to be frank',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'rn','rn','right now',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'qotd','qotd','quote of the day',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'ootd','ootd','outfit of the day',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'brb','brb','be right back',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'btw','btw','by the way',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'lol','lol','laugh out loud',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'ttyl','ttyl','talk to you later',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'hmu','hmu','hit me up',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'fwiw','fwiw',"for what it's worth",NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'imo','imo','in my opinion',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'imho','imho','in my humble opinion',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'idk','idk',"i don't know",NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'tba','tba','to be announced',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'tbd','tbd','to be decided',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'eod','eod','end of day',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'faq','faq','frequently asked question',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'asap','asap','as soon as possible',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'diy','diy','do it yourself',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'lmgtfy','lmgtfy','let me google that for you',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'np','np','no problem',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'n/a','n-a','not applicable or not available',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'ooo','ooo','out of office',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'tia','tia','thanks in advance',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'ily','ily','i love you',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'mcm','mcm','man crush monday',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'wcw','wcw','woman crush wednesday',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'bf','bf','boyfriend',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(NULL,1,2,'gf','gf','girlfriend',NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP);

/*!40000 ALTER TABLE `word_spellings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-05  8:50:58
