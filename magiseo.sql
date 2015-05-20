-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: magiseo
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Table structure for table `counter_port_vm`
--

DROP TABLE IF EXISTS `counter_port_vm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `counter_port_vm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `counter` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `counter_port_vm`
--

LOCK TABLES `counter_port_vm` WRITE;
/*!40000 ALTER TABLE `counter_port_vm` DISABLE KEYS */;
INSERT INTO `counter_port_vm` VALUES (1,1128);
/*!40000 ALTER TABLE `counter_port_vm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reporting`
--

DROP TABLE IF EXISTS `reporting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `title` varchar(85) NOT NULL,
  `description` text NOT NULL,
  `type` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=738 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reporting`
--

LOCK TABLES `reporting` WRITE;
/*!40000 ALTER TABLE `reporting` DISABLE KEYS */;
/*!40000 ALTER TABLE `reporting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reporting_type`
--

DROP TABLE IF EXISTS `reporting_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporting_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reporting_type`
--

LOCK TABLES `reporting_type` WRITE;
/*!40000 ALTER TABLE `reporting_type` DISABLE KEYS */;
INSERT INTO `reporting_type` VALUES (1,'Error'),(2,'Bug'),(3,'Warning'),(4,'Security'),(5,'Internal Error'),(6,'Log');
/*!40000 ALTER TABLE `reporting_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `server_information`
--

DROP TABLE IF EXISTS `server_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idserver` int(11) NOT NULL,
  `disk_max_size` int(11) NOT NULL,
  `disk_current_size` int(11) NOT NULL,
  `nb_max_proc` int(11) NOT NULL,
  `nb_current_proc` int(11) NOT NULL,
  `flash_max_size` int(11) NOT NULL,
  `flash_current_size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server_information`
--

LOCK TABLES `server_information` WRITE;
/*!40000 ALTER TABLE `server_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `server_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `server_slave`
--

DROP TABLE IF EXISTS `server_slave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server_slave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `IPV4` varchar(15) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `keysshpath` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server_slave`
--

LOCK TABLES `server_slave` WRITE;
/*!40000 ALTER TABLE `server_slave` DISABLE KEYS */;
/*!40000 ALTER TABLE `server_slave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `avatar_path` varchar(50) NOT NULL,
  `date_last_connection` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'admin2','*8563732068DA8F406E3BE0B9B56B403EB8E39722','firstname','lastname','admin2@email.com','image/avatar/avatar6.jpg','2014-05-13');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vm`
--

DROP TABLE IF EXISTS `vm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idserver` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `port` int(11) NOT NULL DEFAULT '22',
  `name` varchar(30) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `ram` int(11) NOT NULL,
  `hdd` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vm`
--

LOCK TABLES `vm` WRITE;
/*!40000 ALTER TABLE `vm` DISABLE KEYS */;
/*!40000 ALTER TABLE `vm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vm_processing`
--

DROP TABLE IF EXISTS `vm_processing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vm_processing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idvm` int(11) NOT NULL,
  `idserver` int(11) NOT NULL,
  `ipserver` varchar(15) NOT NULL,
  `date_begin` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `porttunnel` int(11) NOT NULL,
  `ipmaster` varchar(40) NOT NULL,
  `ipalgo` varchar(25) NOT NULL,
  `urlclient` varchar(50) NOT NULL,
  `isarchive` int(1) NOT NULL,
  `interval` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vm_processing`
--

LOCK TABLES `vm_processing` WRITE;
/*!40000 ALTER TABLE `vm_processing` DISABLE KEYS */;
/*!40000 ALTER TABLE `vm_processing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vm_results`
--

DROP TABLE IF EXISTS `vm_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vm_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idvm` int(11) NOT NULL,
  `stdout` text NOT NULL,
  `stderr` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vm_results`
--

LOCK TABLES `vm_results` WRITE;
/*!40000 ALTER TABLE `vm_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `vm_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vm_state`
--

DROP TABLE IF EXISTS `vm_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vm_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vm_state`
--

LOCK TABLES `vm_state` WRITE;
/*!40000 ALTER TABLE `vm_state` DISABLE KEYS */;
INSERT INTO `vm_state` VALUES (1,'Installing'),(2,'Ready'),(3,'Using'),(4,'Canceled'),(5,'Finished');
/*!40000 ALTER TABLE `vm_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vm_todo`
--

DROP TABLE IF EXISTS `vm_todo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vm_todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `ram` int(11) NOT NULL,
  `hdd` int(11) NOT NULL,
  `ipalgo` varchar(25) NOT NULL,
  `urlclient` varchar(50) NOT NULL,
  `isarchive` int(11) NOT NULL,
  `interval` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vm_todo`
--

LOCK TABLES `vm_todo` WRITE;
/*!40000 ALTER TABLE `vm_todo` DISABLE KEYS */;
/*!40000 ALTER TABLE `vm_todo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-20  2:21:02
