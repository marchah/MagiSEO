-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 29, 2014 at 10:57 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `magiseo`
--
CREATE DATABASE IF NOT EXISTS `magiseo` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `magiseo`;

-- --------------------------------------------------------

--
-- Table structure for table `reporting`
--

CREATE TABLE IF NOT EXISTS `reporting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `title` varchar(85) NOT NULL,
  `description` text NOT NULL,
  `type` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=342 ;

--
-- Dumping data for table `reporting`
--

INSERT INTO `reporting` (`id`, `iduser`, `title`, `description`, `type`, `date`) VALUES
(15, 1, 'log 1', 'log 1', 6, '2014-05-07'),
(119, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-20'),
(120, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-20'),
(121, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-20'),
(122, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-20'),
(123, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-20'),
(124, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-20'),
(125, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-20'),
(126, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-20'),
(127, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(128, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(129, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(130, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(131, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(132, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(134, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(135, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(136, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(137, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(138, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(139, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(140, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(141, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(142, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(143, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(144, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(145, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(146, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(147, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(148, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(149, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(150, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(151, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(152, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(153, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(154, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(155, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-24'),
(156, 1, 'Desinstall Server Slave 1', 'This server hasn''t been configurated before.', 4, '2014-05-24'),
(157, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.147. Error 10061. No connection could be made because the target machine actively refused it., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-05-24'),
(158, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-24'),
(159, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(160, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(161, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-25'),
(162, 1, 'Desinstall Server Slave 1', 'This server hasn''t been configurated before.', 4, '2014-05-25'),
(163, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(164, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(165, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(166, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(167, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(168, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(169, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(170, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-25'),
(171, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(172, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-25'),
(173, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(174, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-25'),
(175, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(176, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(177, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(178, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(179, 1, 'Install Server Slave 192.', 'Success', 6, '2014-05-25'),
(180, 1, 'Desinstall Server Slave 1', 'Success', 6, '2014-05-25'),
(181, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.153. Error 10061. No connection could be made because the target machine actively refused it., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-04'),
(182, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/install/install.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:247\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(247): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(294): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\UnitTest\\ServerUnitTest.php(19): installServerSlave()\n#3 {main}', 1, '2014-06-04'),
(184, 1, 'cpAndPutConstantesInstall', 'Error: Copy file failed', 1, '2014-06-04'),
(185, 1, 'cpAndPutConstantesInstall', 'Error: Copy file failed', 1, '2014-06-04'),
(186, 1, 'SSH Create File Public Ke', 'Error: Save key failed', 1, '2014-06-04'),
(187, 1, 'cpAndPutConstantesInstall', 'Error: Copy file failed', 1, '2014-06-04'),
(188, 1, 'compressFolder() Error', 'exception ''UnexpectedValueException'' with message ''Cannot create phar ''C:/wamp/www/MagiSEO/site/PATH_MASTER_SCRIPT_SERVER_SLAVE_INSTALL_ARCHIVE'', file extension (or combination) not recognised or the directory does not exist'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:245\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(245): PharData->__construct(''C:/wamp/www/Mag...'')\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(295): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\UnitTest\\ServerUnitTest.php(19): installServerSlave()\n#3 {main}', 1, '2014-06-04'),
(189, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:247\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(247): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(295): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\UnitTest\\ServerUnitTest.php(19): installServerSlave()\n#3 {main}', 1, '2014-06-04'),
(190, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:247\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(247): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(296): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\UnitTest\\ServerUnitTest.php(19): installServerSlave()\n#3 {main}', 1, '2014-06-04'),
(191, 1, 'cpAndPutConstantesInstall', 'Error: Copy file failed authorized_keys', 1, '2014-06-04'),
(192, 1, 'cpAndPutConstantesInstall', 'Error: Copy file failed authorized_keys', 1, '2014-06-04'),
(193, 1, 'installServerSlave() Erro', 'Error: SSH securise script failed ret:0', 1, '2014-06-04'),
(194, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:250\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(250): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(300): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\UnitTest\\ServerUnitTest.php(19): installServerSlave()\n#3 {main}', 1, '2014-06-04'),
(195, 0, 'COUCOU', 'salut toto', 5, '2014-06-04'),
(196, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.153. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-04'),
(197, 1, 'uploadInstallScripts() Er', 'Error: Upload scripts failed', 1, '2014-06-04'),
(198, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.162.234.153. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-04'),
(199, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.153. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-04'),
(200, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:235\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(235): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(285): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(368): installServerSlave()\n#3 {main}', 1, '2014-06-04'),
(201, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to nlkn,k. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-04'),
(202, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:235\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(235): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(285): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(368): installServerSlave()\n#3 {main}', 1, '2014-06-04'),
(203, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to sdgsdg. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-04'),
(204, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:235\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(235): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(285): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(368): installServerSlave()\n#3 {main}', 1, '2014-06-04'),
(205, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to efsdfsdf. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(206, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:235\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(235): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(285): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(368): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(207, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to dfgsdfg. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(208, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:235\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(235): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(285): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(368): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(209, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to aqdad. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(210, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:237\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(237): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(293): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(382): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(211, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to sqfqsf. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(212, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:237\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(237): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(293): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(379): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(213, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to sfsfsf. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(214, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:237\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(237): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(293): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(379): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(215, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to sqqsf. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(216, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:237\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(237): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(293): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(379): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(217, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:237\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(237): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(293): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(379): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(218, 1, 'SSH Create File Private K', 'Error: Save key failed', 1, '2014-06-05'),
(219, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:237\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(237): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(293): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(379): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(220, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:237\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(237): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(293): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(379): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(221, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:239\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(239): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(295): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\UnitTest\\ServerUnitTest.php(19): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(222, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:239\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(239): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(295): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(381): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(223, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to qq. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(224, 1, 'compressFolder() Error', 'exception ''BadMethodCallException'' with message ''phar "C:/wamp/www/MagiSEO/site/ScriptServerSlave/ScriptServerSFTP/ScriptServerSFTP.tar.gz" exists and must be unlinked prior to conversion'' in C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php:239\nStack trace:\n#0 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(239): PharData->compress(4096)\n#1 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(295): compressFolder()\n#2 C:\\wamp\\www\\MagiSEO\\site\\PHP\\AJAX\\serverAjax.php(381): installServerSlave()\n#3 {main}', 1, '2014-06-05'),
(225, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to qq. Error 0. php_network_getaddresses: getaddrinfo failed: No such host is known., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(226, 1, 'uploadInstallScripts() Er', 'Error: Upload scripts failed', 1, '2014-06-05'),
(227, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(228, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(229, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(230, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(231, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(232, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(233, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.153. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(234, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(235, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(236, 1, 'uploadInstallScripts() Er', 'Error: Upload scripts failed', 1, '2014-06-05'),
(237, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(238, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(239, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(240, 0, 'UNKNOW IP ADDRESS', 'ServerInfo::getIdServer()', 4, '2014-06-04'),
(241, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(242, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(243, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.154. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-05'),
(244, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(245, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(246, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(247, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(248, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(249, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(250, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(251, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(252, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(253, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(254, 1, 'Install Server Slave 192.', 'Success', 6, '2014-06-05'),
(255, 1, 'Install Server Slave 192.168.234.154', 'Success', 6, '2014-06-05'),
(256, 1, 'Install Server Slave 192.168.234.154', 'Success', 6, '2014-06-06'),
(257, 1, 'Install Server Slave 192.168.234.154', 'Success', 6, '2014-06-06'),
(258, 1, 'Install Server Slave 192.168.234.154', 'Success', 6, '2014-06-06'),
(259, 1, 'Install Server Slave 192.168.234.154', 'Success', 6, '2014-06-06'),
(260, 1, 'Install Server Slave 192.168.234.154', 'Success', 6, '2014-06-06'),
(261, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.155. Error 10061. No connection could be made because the target machine actively refused it., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-06'),
(262, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-06'),
(263, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-06'),
(264, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-06'),
(265, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(266, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(267, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(268, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(269, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(270, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(271, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(272, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(273, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(274, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(275, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(276, 1, 'Desinstall Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(277, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(278, 1, 'Desinstall Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(279, 1, 'REQUEST SQL FAILED', 'ServerDAO::deletetServer()', 5, '2014-06-07'),
(280, 1, 'Desinstall Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(281, 1, 'Desinstall Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(282, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(283, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(284, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(285, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(286, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(287, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(288, 1, 'Install Server Slave 192.168.234.155', 'Success', 6, '2014-06-07'),
(289, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(290, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(291, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(292, 1, 'Desinstall Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(293, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(294, 1, 'Desinstall Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(295, 1, 'Desinstall Server Slave 192.168.234.156', 'This server hasn''t been configurated before.', 4, '2014-06-08'),
(296, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(297, 1, 'Desinstall Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(298, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(299, 1, 'Desinstall Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(300, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(301, 1, 'Desinstall Server Slave 192.168.234.156', 'Success', 6, '2014-06-08'),
(302, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.154. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-08'),
(303, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.154. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-08'),
(304, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 127.192.158.25. Error 10061. No connection could be made because the target machine actively refused it., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-08'),
(305, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-08'),
(306, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-08'),
(307, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-08'),
(308, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-08'),
(309, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-08'),
(310, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-08'),
(311, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.1. Error 10061. No connection could be made because the target machine actively refused it., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-18'),
(312, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-18'),
(313, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-18'),
(314, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-18'),
(315, 1, 'Desinstall Server Slave 192.168.234.156', 'Success', 6, '2014-06-18'),
(316, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-18'),
(317, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-19'),
(318, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-19'),
(319, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-19'),
(320, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-19'),
(321, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-28'),
(322, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.156. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-28'),
(323, 0, 'REQUEST SQL FAILED', 'Script getIp.php getVMProcessing()', 5, '2014-06-28'),
(324, 0, 'REQUEST SQL FAILED', 'Script getIp.php getVMProcessing()', 5, '2014-06-28'),
(325, 0, 'REQUEST SQL FAILED', 'Script getIp.php getVMProcessing()', 5, '2014-06-28'),
(326, 0, 'REQUEST SQL FAILED', 'Script getIp.php getVMProcessing()', 5, '2014-06-28'),
(327, 0, 'REQUEST SQL FAILED', 'Script getIp.php addVM()', 5, '2014-06-28'),
(328, 0, 'REQUEST SQL FAILED', 'Script getIp.php addVM()', 5, '2014-06-28'),
(329, 1, 'Desinstall Server Slave 192.168.234.156', 'Success', 6, '2014-06-29'),
(330, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-29'),
(331, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-29'),
(332, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-29'),
(333, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-29'),
(334, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-29'),
(335, 1, 'Install Server Slave 192.168.234.156', 'Success', 6, '2014-06-29'),
(336, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-29'),
(337, 1, 'Install Server Slave 192.168.234.157', 'Success', 6, '2014-06-29'),
(338, 0, 'REQUEST SQL FAILED', 'Script getIp.php addVM()', 5, '2014-06-29'),
(339, 1, 'Desinstall Server Slave 192.168.234.157', 'Success', 6, '2014-06-29'),
(340, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.156. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-29'),
(341, 1, 'PHPseclib internal error', 'errno=1024, errstr=Cannot connect to 192.168.234.156. Error 10060. A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond., errfile:C:\\wamp\\www\\MagiSEO\\site\\phpseclib\\Net\\SSH2.php, errline:875', 1, '2014-06-29');

-- --------------------------------------------------------

--
-- Table structure for table `reporting_type`
--

CREATE TABLE IF NOT EXISTS `reporting_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `reporting_type`
--

INSERT INTO `reporting_type` (`id`, `name`) VALUES
(1, 'Error'),
(2, 'Bug'),
(3, 'Warning'),
(4, 'Security'),
(5, 'Internal Error'),
(6, 'Log');

-- --------------------------------------------------------

--
-- Table structure for table `server_information`
--

CREATE TABLE IF NOT EXISTS `server_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idserver` int(11) NOT NULL,
  `disk_max_size` int(11) NOT NULL,
  `disk_current_size` int(11) NOT NULL,
  `nb_max_proc` int(11) NOT NULL,
  `nb_current_proc` int(11) NOT NULL,
  `flash_max_size` int(11) NOT NULL,
  `flash_current_size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `server_slave`
--

CREATE TABLE IF NOT EXISTS `server_slave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `IPV4` varchar(15) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `keysshpath` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `avatar_path` varchar(50) NOT NULL,
  `date_last_connection` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `firstname`, `lastname`, `email`, `avatar_path`, `date_last_connection`) VALUES
(1, 'admin1', '*8563732068DA8F406E3BE0B9B56B403EB8E39722', 'firstname', 'lastname', 'admin1@email.com', 'image/avatar/avatar8.jpg', '2014-06-28'),
(2, 'admin2', '*8563732068DA8F406E3BE0B9B56B403EB8E39722', 'firstname', 'lastname', 'admin2@email.com', 'image/avatar/avatar6.jpg', '2014-05-13');

-- --------------------------------------------------------

--
-- Table structure for table `vm`
--

CREATE TABLE IF NOT EXISTS `vm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idserver` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `name` varchar(30) NOT NULL,
  `ram` int(11) NOT NULL,
  `hdd` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `vm_processing`
--

CREATE TABLE IF NOT EXISTS `vm_processing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idserver` int(11) NOT NULL,
  `ipserver` varchar(15) NOT NULL,
  `date_begin` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
