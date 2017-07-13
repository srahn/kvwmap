-- --------------------------------------------------------
-- Host:                         localhost
-- Server Version:               5.5.56 - MySQL Community Server (GPL)
-- Server Betriebssystem:        linux-glibc2.5
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Exportiere Datenbank Struktur für kvwmapdb
CREATE DATABASE IF NOT EXISTS `kvwmapdb` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `kvwmapdb`;

-- Exportiere Struktur von Tabelle kvwmapdb.stelle
CREATE TABLE IF NOT EXISTS `stelle` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Bezeichnung` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `Bezeichnung_low-german` varchar(255) DEFAULT NULL,
  `Bezeichnung_english` varchar(255) CHARACTER SET cp1250 DEFAULT NULL,
  `Bezeichnung_polish` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Bezeichnung_vietnamese` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `start` date NOT NULL DEFAULT '0000-00-00',
  `stop` date NOT NULL DEFAULT '0000-00-00',
  `minxmax` double DEFAULT NULL,
  `minymax` double DEFAULT NULL,
  `maxxmax` double DEFAULT NULL,
  `maxymax` double DEFAULT NULL,
  `epsg_code` varchar(6) DEFAULT '2398',
  `Referenzkarte_ID` int(11) DEFAULT NULL,
  `Authentifizierung` enum('0','1') NOT NULL DEFAULT '1',
  `ALB_status` enum('30','35') NOT NULL DEFAULT '30',
  `wappen` varchar(150) NOT NULL DEFAULT 'stz.gif',
  `wappen_link` varchar(255) DEFAULT NULL,
  `alb_raumbezug` set('','Kreis','Amtsverwaltung','Gemeinde') NOT NULL DEFAULT '',
  `alb_raumbezug_wert` varchar(255) NOT NULL DEFAULT '',
  `logconsume` enum('0','1') CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `pgdbhost` varchar(25) NOT NULL DEFAULT 'localhost',
  `pgdbname` varchar(25) DEFAULT NULL,
  `pgdbuser` varchar(25) DEFAULT NULL,
  `pgdbpasswd` varchar(25) DEFAULT NULL,
  `ows_title` varchar(255) DEFAULT NULL,
  `wms_accessconstraints` varchar(255) DEFAULT NULL,
  `ows_abstract` varchar(255) DEFAULT NULL,
  `ows_contactperson` varchar(255) DEFAULT NULL,
  `ows_contactorganization` varchar(255) DEFAULT NULL,
  `ows_contactemailaddress` varchar(255) DEFAULT NULL,
  `ows_contactposition` varchar(255) DEFAULT NULL,
  `ows_fees` varchar(255) DEFAULT NULL,
  `ows_srs` varchar(255) DEFAULT NULL,
  `check_client_ip` enum('0','1') NOT NULL DEFAULT '0',
  `check_password_age` enum('0','1') NOT NULL DEFAULT '0',
  `allowed_password_age` tinyint(4) NOT NULL DEFAULT '6',
  `use_layer_aliases` enum('0','1') NOT NULL DEFAULT '0',
  `selectable_layer_params` text,
  `hist_timestamp` tinyint(1) NOT NULL DEFAULT '0',
  `default_user_id` int(11) DEFAULT NULL COMMENT 'Nutzer Id der default Rolle. Die Einstellungen dieser Rolle werden für das Anlegen neuer Rollen für diese Stelle verwendet. Ist dieser Wert nicht angegeben oder die angegebene Rolle existiert nicht, werden die Defaultwerte der Rollenoptionen bei der Zuordnung eines Nutzers zu dieser Stelle verwendet. Die Angabe ist nützlich, wenn die Einstellungen in Gaststellen am Anfang immer gleich sein sollen.',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Exportiere Daten aus Tabelle kvwmapdb.stelle: 2 rows
DELETE FROM `stelle`;
/*!40000 ALTER TABLE `stelle` DISABLE KEYS */;
INSERT INTO `stelle` (`ID`, `Bezeichnung`, `Bezeichnung_low-german`, `Bezeichnung_english`, `Bezeichnung_polish`, `Bezeichnung_vietnamese`, `start`, `stop`, `minxmax`, `minymax`, `maxxmax`, `maxymax`, `epsg_code`, `Referenzkarte_ID`, `Authentifizierung`, `ALB_status`, `wappen`, `wappen_link`, `alb_raumbezug`, `alb_raumbezug_wert`, `logconsume`, `pgdbhost`, `pgdbname`, `pgdbuser`, `pgdbpasswd`, `ows_title`, `wms_accessconstraints`, `ows_abstract`, `ows_contactperson`, `ows_contactorganization`, `ows_contactemailaddress`, `ows_contactposition`, `ows_fees`, `ows_srs`, `check_client_ip`, `check_password_age`, `allowed_password_age`, `use_layer_aliases`, `selectable_layer_params`, `hist_timestamp`, `default_user_id`) VALUES
	(1, 'Administration', NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', 201165, 5867815, 477900, 6081468, '25833', 1, '1', '30', 'Logo_GDI-Service_200x47.png', '', '', '', NULL, 'localhost', '', '', '', '', '', '', '', '', '', '', '', '', '0', '0', 6, '0', NULL, 0, NULL),
	(2, 'Dateneingeber', NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', 201165, 5867815, 477900, 6081468, '35833', 1, '1', '30', 'logo_lung.jpg', '', '', '', NULL, 'localhost', '', '', '', '', '', '', '', '', '', '', '', '', '0', '0', 6, '0', NULL, 0, NULL),
	(4, 'Entscheider', NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', 201165, 5867815, 477900, 6081468, '35833', 1, '1', '30', 'logo_lung.jpg', '', '', '', NULL, 'localhost', '', '', '', '', '', '', '', '', '', '', '', '', '0', '0', 6, '0', NULL, 0, NULL);
/*!40000 ALTER TABLE `stelle` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
