-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: lemurengine
-- ------------------------------------------------------
-- Server version	5.7.28-0ubuntu0.18.04.4

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
-- Dumping data for table `category_groups`
--

LOCK TABLES `category_groups` WRITE;
/*!40000 ALTER TABLE `category_groups` DISABLE KEYS */;
INSERT INTO `category_groups` (`id`, `user_id`, `language_id`, `slug`, `name`, `description`, `status`, `is_master`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1,1,1,'dev-testcases','dev-testcases','Part of the master set of Lemur Engine AIML categories. This group is used in development to test everything is working as expected.','A',1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(2,1,1,'std-critical','std-critical','Part of the master set of Lemur Engine AIML categories. This group contains a few critical categories. Make sure this is always linked to your bot .','A',1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(3,1,1,'std-rating','std-rating','Part of the master set of Lemur Engine AIML categories. This group contains patterns to request clients to rate your bot.','A',1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(4,1,1,'std-hello','std-hello','Part of the master set of Lemur Engine AIML categories. This group contains responses to common greetings and goodbyes.','A',1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),
(5,1,1,'std-65percent','std-65percent','Part of the master set of Lemur Engine AIML categories. This group contains responses to 65% of the conversation inputs your bot will receive.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(6,1,1,'std-atomic','std-atomic','Part of the master set of Lemur Engine AIML categories. This group contains responses to small one liners from the client.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(7,1,1,'std-botmaster','std-botmaster','Part of the master set of Lemur Engine AIML categories. This group contains responses to questions about the bot creator.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(8,1,1,'std-brain','std-brain','Part of the master set of Lemur Engine AIML categories. This group contains responses to common conversations inputs.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(9,1,1,'std-dictionary','std-dictionary',"Part of the master set of Lemur Engine AIML categories. This group contains responses to requests to 'define' a word.",'A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(10,1,1,'std-howto','std-howto','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of the Lemur Engine and how to use, extend and talk to the bot.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(11,1,1,'std-inventions','std-inventions',"Part of the master set of Lemur Engine AIML categories. This group contains responses to questions about inventions.",'A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(12,1,1,'std-gender','std-gender','Part of the master set of Lemur Engine AIML categories. This group contains categories to determine a clients gender.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(13,1,1,'std-geography','std-geography','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of geographical locations.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(14,1,1,'std-german','std-german','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations spoken in german.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(15,1,1,'std-gossip','std-gossip','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of gossip.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(16,1,1,'std-knowledge','std-knowledge','Part of the master set of Lemur Engine AIML categories. This group contains responses to general questions on a range of subjects.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(17,1,1,'std-lizards','std-lizards','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of lizards.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(18,1,1,'std-numbers','std-numbers','Part of the master set of Lemur Engine AIML categories. This group contains responses to basic math questions.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(19,1,1,'std-personality','std-personality',"Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of the client and the bot\'s personality.",'A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(20,1,1,'std-pickup','std-pickup','Part of the master set of Lemur Engine AIML categories. This group contains catchall responses when the bot gets confused.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(21,1,1,'std-politics','std-politics','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of politics.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(22,1,1,'std-profile','std-profile',"Part of the master set of Lemur Engine AIML categories. This group contains conversations about the client's profile.",'A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(23,1,1,'std-religion','std-religion','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of religion.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(24,1,1,'std-robot','std-robot','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of robots.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(25,1,1,'std-sports','std-sports','Part of the master set of Lemur Engine AIML categories. This group contains responses around the subject sports.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(26,1,1,'std-sales','std-sales','Part of the master set of Lemur Engine AIML categories. This group contains responses around the subject sales and customer service. The bot will respond as if it is a customer service bot.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(27,1,1,'std-sextalk','std-sextalk','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the topic of sex. Yes you read right. Whether we like it or not this is a common conversation topic.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(28,1,1,'std-srai','std-srai','Part of the master set of Lemur Engine AIML categories. This group reduces common client inputs to single category and responds using that common category.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(29,1,1,'std-that','std-that','Part of the master set of Lemur Engine AIML categories. This group catches and responds to client based upon how the bot just replied. This gives the conversation greater content and flow.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(30,1,1,'std-suffixes','std-suffixes','Part of the master set of Lemur Engine AIML categories. This group catches and responds to general conversations by identifying common topics using a wildcard prefix and a subject suffix pattern match.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(31,1,1,'std-turing','std-turing','Part of the master set of Lemur Engine AIML categories. This group provides bot responses to questions and interactions about the English mathematician, computer scientist and all round hero Alan Turing.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(32,1,1,'std-yesno','std-yesno','Part of the master set of Lemur Engine AIML categories. This group provides bot responses after the client has said yes or no.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(33,1,1,'std-learn','std-learn','Part of the master set of Lemur Engine AIML categories. This group provides categories to help the bot learn from the client.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(34,1,1,'std-happybirthday','std-happybirthday','Part of the master set of Lemur Engine AIML categories. This group provides conversations around birthdays.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(35,1,1,'std-horoscope','std-horoscope','Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the topic of horoscopes.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(36,1,1,'std-howmany','std-howmany',"Part of the master set of Lemur Engine AIML categories. This group contains responses to a clients 'how many?' questions.",'A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(37,1,1,'std-jokes','std-jokes','Part of the master set of Lemur Engine AIML categories. This group provides jokes.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(38,1,1,'std-knockknock','std-knockknock','Part of the master set of Lemur Engine AIML categories. TThis group provides knock knock jokes.','A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(39,1,1,'std-yomama','std-yomama',"Part of the master set of Lemur Engine AIML categories. TThis group provides 'yo mama' jokes.",'A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(40,1,1,'std-shutup','std-shutup',"Part of the master set of Lemur Engine AIML categories. This group provides bot responses after the client told the bot to 'shut up'.",'A',1,NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
/*!40000 ALTER TABLE `category_groups` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-15 11:12:40


