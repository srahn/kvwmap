-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: 172.17.0.2:3306
-- Generation Time: Aug 10, 2017 at 10:52 AM
-- Server version: 5.5.56
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kvwmapdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `Class_ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL DEFAULT '',
  `Name_low-german` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name_english` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name_polish` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name_vietnamese` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Layer_ID` int(11) NOT NULL DEFAULT '0',
  `Expression` text,
  `drawingorder` int(11) UNSIGNED DEFAULT NULL,
  `legendorder` int(11) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `classification` varchar(50) DEFAULT NULL,
  `legendgraphic` varchar(255) DEFAULT NULL,
  `legendimagewidth` int(11) DEFAULT NULL,
  `legendimageheight` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`Class_ID`, `Name`, `Name_low-german`, `Name_english`, `Name_polish`, `Name_vietnamese`, `Layer_ID`, `Expression`, `drawingorder`, `legendorder`, `text`, `classification`, `legendgraphic`, `legendimagewidth`, `legendimageheight`) VALUES
(1, 'alle', NULL, NULL, NULL, NULL, 2, '', 1, NULL, '', '', '', NULL, NULL),
(2, 'alle', NULL, NULL, NULL, NULL, 3, '', 1, NULL, '', '', '', NULL, NULL),
(8, 'alle', NULL, NULL, NULL, NULL, 6, '', 1, NULL, '', '', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `red` smallint(3) NOT NULL DEFAULT '0',
  `green` smallint(3) NOT NULL DEFAULT '0',
  `blue` smallint(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `red`, `green`, `blue`) VALUES
(1, NULL, 166, 206, 227),
(2, NULL, 31, 120, 180),
(3, NULL, 178, 223, 138),
(4, NULL, 51, 160, 44),
(5, NULL, 251, 154, 153),
(6, NULL, 227, 26, 28),
(7, NULL, 253, 191, 111),
(8, NULL, 255, 127, 0),
(9, NULL, 202, 178, 214),
(10, NULL, 106, 61, 154),
(11, NULL, 0, 0, 0),
(12, NULL, 122, 12, 45);

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bezeichnung` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci,
  `time` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0 6 1 * *',
  `query` text COLLATE utf8_unicode_ci,
  `function` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `stelle_id` int(11) DEFAULT NULL,
  `aktiv` tinyint(1) NOT NULL DEFAULT '0',
  `dbname` varchar(68) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `datatypes`
--

CREATE TABLE `datatypes` (
  `id` int(11) NOT NULL,
  `name` varchar(58) DEFAULT NULL,
  `schema` varchar(58) NOT NULL DEFAULT 'public',
  `dbname` varchar(50) NOT NULL,
  `host` varchar(50) DEFAULT NULL,
  `port` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `datatype_attributes`
--

CREATE TABLE `datatype_attributes` (
  `datatype_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `real_name` varchar(255) DEFAULT NULL,
  `tablename` varchar(100) DEFAULT NULL,
  `table_alias_name` varchar(100) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `geometrytype` varchar(20) DEFAULT NULL,
  `constraints` varchar(255) DEFAULT NULL,
  `nullable` tinyint(1) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `decimal_length` int(11) DEFAULT NULL,
  `default` varchar(255) DEFAULT NULL,
  `form_element_type` enum('Text','Textfeld','Auswahlfeld','Checkbox','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','User','Stelle','FlÃ¤che','dynamicLink','Zahl','UserID','LÃ¤nge','mailto') NOT NULL DEFAULT 'Text',
  `options` text,
  `alias` varchar(255) DEFAULT NULL,
  `alias_low-german` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias_english` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias_polish` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias_vietnamese` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tooltip` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `raster_visibility` tinyint(1) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `quicksearch` tinyint(1) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `privileg` tinyint(1) DEFAULT '0',
  `query_tooltip` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `datendrucklayouts`
--

CREATE TABLE `datendrucklayouts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `bgsrc` varchar(255) DEFAULT NULL,
  `bgposx` int(11) DEFAULT NULL,
  `bgposy` int(11) DEFAULT NULL,
  `bgwidth` int(11) DEFAULT NULL,
  `bgheight` int(11) DEFAULT NULL,
  `dateposx` int(11) DEFAULT NULL,
  `dateposy` int(11) DEFAULT NULL,
  `datesize` int(11) DEFAULT NULL,
  `userposx` int(11) DEFAULT NULL,
  `userposy` int(11) DEFAULT NULL,
  `usersize` int(11) DEFAULT NULL,
  `font_date` varchar(255) DEFAULT NULL,
  `font_user` varchar(255) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `gap` int(11) NOT NULL DEFAULT '20',
  `no_record_splitting` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `datendrucklayouts`
--

INSERT INTO `datendrucklayouts` (`id`, `name`, `layer_id`, `bgsrc`, `bgposx`, `bgposy`, `bgwidth`, `bgheight`, `dateposx`, `dateposy`, `datesize`, `userposx`, `userposy`, `usersize`, `font_date`, `font_user`, `type`, `gap`, `no_record_splitting`) VALUES
(1, 'Test Wasserentnahmebenutzer Layout', 43, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 0, 20, 0),
(2, 'test', 43, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 0, 20, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ddl2freilinien`
--

CREATE TABLE `ddl2freilinien` (
  `ddl_id` int(11) NOT NULL,
  `line_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ddl2freitexte`
--

CREATE TABLE `ddl2freitexte` (
  `ddl_id` int(11) NOT NULL,
  `freitext_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ddl2freitexte`
--

INSERT INTO `ddl2freitexte` (`ddl_id`, `freitext_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ddl2stelle`
--

CREATE TABLE `ddl2stelle` (
  `stelle_id` int(11) NOT NULL,
  `ddl_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ddl2stelle`
--

INSERT INTO `ddl2stelle` (`stelle_id`, `ddl_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ddl_elemente`
--

CREATE TABLE `ddl_elemente` (
  `ddl_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `xpos` double DEFAULT NULL,
  `ypos` double DEFAULT NULL,
  `offset_attribute` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `border` tinyint(1) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  `fontsize` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ddl_elemente`
--

INSERT INTO `ddl_elemente` (`ddl_id`, `name`, `xpos`, `ypos`, `offset_attribute`, `width`, `border`, `font`, `fontsize`) VALUES
(1, 'anlage', 70, 750, NULL, NULL, NULL, '', 13),
(1, 'wasserrechtliche_zulassung', 70, 730, NULL, NULL, NULL, '', 13),
(1, 'benutzungsnummer', 70, 710, NULL, NULL, NULL, '', 13),
(2, 'anlage', 70, 750, NULL, NULL, NULL, 'Helvetica.afm', 13),
(2, 'wrz_id', 70, 730, NULL, NULL, NULL, 'Helvetica.afm', 13),
(2, 'wasserrechtliche_zulassung', 70, 710, NULL, NULL, NULL, 'Helvetica.afm', 13),
(2, 'benutzungsnummer', 70, 690, NULL, NULL, NULL, 'Helvetica.afm', 13);

-- --------------------------------------------------------

--
-- Table structure for table `druckausschnitte`
--

CREATE TABLE `druckausschnitte` (
  `stelle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `center_x` float NOT NULL,
  `center_y` float NOT NULL,
  `print_scale` int(11) NOT NULL,
  `angle` int(11) NOT NULL,
  `frame_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckfreibilder`
--

CREATE TABLE `druckfreibilder` (
  `id` int(11) NOT NULL,
  `src` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckfreilinien`
--

CREATE TABLE `druckfreilinien` (
  `id` int(11) NOT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `endposx` int(11) NOT NULL,
  `endposy` int(11) NOT NULL,
  `breite` int(3) NOT NULL,
  `offset_attribute` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckfreitexte`
--

CREATE TABLE `druckfreitexte` (
  `id` int(11) NOT NULL,
  `text` text,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `offset_attribute` varchar(255) DEFAULT NULL,
  `size` int(11) NOT NULL,
  `font` varchar(255) NOT NULL,
  `angle` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `druckfreitexte`
--

INSERT INTO `druckfreitexte` (`id`, `text`, `posx`, `posy`, `offset_attribute`, `size`, `font`, `angle`, `type`) VALUES
(1, 'ghdfhgfgh gfjkg jhg ', 70, 300, NULL, 11, 'Times-Bold.afm', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `druckrahmen`
--

CREATE TABLE `druckrahmen` (
  `Name` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `dhk_call` varchar(10) DEFAULT NULL,
  `headsrc` varchar(255) NOT NULL,
  `headposx` int(11) NOT NULL,
  `headposy` int(11) NOT NULL,
  `headwidth` int(11) NOT NULL,
  `headheight` int(11) NOT NULL,
  `mapposx` int(11) NOT NULL,
  `mapposy` int(11) NOT NULL,
  `mapwidth` int(11) NOT NULL,
  `mapheight` int(11) NOT NULL,
  `refmapsrc` varchar(255) DEFAULT NULL,
  `refmapfile` varchar(255) DEFAULT NULL,
  `refmapposx` int(11) DEFAULT NULL,
  `refmapposy` int(11) DEFAULT NULL,
  `refmapwidth` int(11) DEFAULT NULL,
  `refmapheight` int(11) DEFAULT NULL,
  `refposx` int(11) DEFAULT NULL,
  `refposy` int(11) DEFAULT NULL,
  `refwidth` int(11) DEFAULT NULL,
  `refheight` int(11) DEFAULT NULL,
  `refzoom` int(11) DEFAULT NULL,
  `dateposx` int(11) DEFAULT NULL,
  `dateposy` int(11) DEFAULT NULL,
  `datesize` int(11) DEFAULT NULL,
  `scaleposx` int(11) DEFAULT NULL,
  `scaleposy` int(11) DEFAULT NULL,
  `scalesize` int(11) DEFAULT NULL,
  `scalebarposx` int(11) DEFAULT NULL,
  `scalebarposy` int(11) DEFAULT NULL,
  `oscaleposx` int(11) DEFAULT NULL,
  `oscaleposy` int(11) DEFAULT NULL,
  `oscalesize` int(11) DEFAULT NULL,
  `lageposx` int(11) DEFAULT NULL,
  `lageposy` int(11) DEFAULT NULL,
  `lagesize` int(11) DEFAULT NULL,
  `gemeindeposx` int(11) DEFAULT NULL,
  `gemeindeposy` int(11) DEFAULT NULL,
  `gemeindesize` int(11) DEFAULT NULL,
  `gemarkungposx` int(11) DEFAULT NULL,
  `gemarkungposy` int(11) DEFAULT NULL,
  `gemarkungsize` int(11) DEFAULT NULL,
  `flurposx` int(11) DEFAULT NULL,
  `flurposy` int(11) DEFAULT NULL,
  `flursize` int(11) DEFAULT NULL,
  `flurstposx` int(11) DEFAULT NULL,
  `flurstposy` int(11) DEFAULT NULL,
  `flurstsize` int(11) DEFAULT NULL,
  `legendposx` int(11) DEFAULT NULL,
  `legendposy` int(11) DEFAULT NULL,
  `legendsize` int(11) DEFAULT NULL,
  `arrowposx` int(11) DEFAULT NULL,
  `arrowposy` int(11) DEFAULT NULL,
  `arrowlength` int(11) DEFAULT NULL,
  `userposx` int(11) DEFAULT NULL,
  `userposy` int(11) DEFAULT NULL,
  `usersize` int(11) DEFAULT NULL,
  `watermarkposx` int(11) DEFAULT NULL,
  `watermarkposy` int(11) DEFAULT NULL,
  `watermark` varchar(255) DEFAULT '',
  `watermarksize` int(11) DEFAULT NULL,
  `watermarkangle` int(11) DEFAULT NULL,
  `watermarktransparency` int(11) DEFAULT NULL,
  `variable_freetexts` tinyint(1) DEFAULT NULL,
  `format` varchar(10) NOT NULL DEFAULT 'A4hoch',
  `preis` int(11) DEFAULT NULL,
  `font_date` varchar(255) DEFAULT NULL,
  `font_scale` varchar(255) DEFAULT NULL,
  `font_lage` varchar(255) DEFAULT NULL,
  `font_gemeinde` varchar(255) DEFAULT NULL,
  `font_gemarkung` varchar(255) DEFAULT NULL,
  `font_flur` varchar(255) DEFAULT NULL,
  `font_flurst` varchar(255) DEFAULT NULL,
  `font_oscale` varchar(255) DEFAULT NULL,
  `font_legend` varchar(255) DEFAULT NULL,
  `font_watermark` varchar(255) DEFAULT NULL,
  `font_user` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `druckrahmen`
--

INSERT INTO `druckrahmen` (`Name`, `id`, `dhk_call`, `headsrc`, `headposx`, `headposy`, `headwidth`, `headheight`, `mapposx`, `mapposy`, `mapwidth`, `mapheight`, `refmapsrc`, `refmapfile`, `refmapposx`, `refmapposy`, `refmapwidth`, `refmapheight`, `refposx`, `refposy`, `refwidth`, `refheight`, `refzoom`, `dateposx`, `dateposy`, `datesize`, `scaleposx`, `scaleposy`, `scalesize`, `scalebarposx`, `scalebarposy`, `oscaleposx`, `oscaleposy`, `oscalesize`, `lageposx`, `lageposy`, `lagesize`, `gemeindeposx`, `gemeindeposy`, `gemeindesize`, `gemarkungposx`, `gemarkungposy`, `gemarkungsize`, `flurposx`, `flurposy`, `flursize`, `flurstposx`, `flurstposy`, `flurstsize`, `legendposx`, `legendposy`, `legendsize`, `arrowposx`, `arrowposy`, `arrowlength`, `userposx`, `userposy`, `usersize`, `watermarkposx`, `watermarkposy`, `watermark`, `watermarksize`, `watermarkangle`, `watermarktransparency`, `variable_freetexts`, `format`, `preis`, `font_date`, `font_scale`, `font_lage`, `font_gemeinde`, `font_gemarkung`, `font_flur`, `font_flurst`, `font_oscale`, `font_legend`, `font_watermark`, `font_user`) VALUES
('A4-hoch-leer', 1, NULL, 'A4-hoch.jpg', 0, 0, 595, 842, 44, 50, 511, 714, '', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 503, 784, 11, 422, 74, 0, NULL, NULL, 422, 87, 0, NULL, NULL, NULL, NULL, NULL, NULL, 238, 54, 0, 238, 64, 0, NULL, NULL, NULL, 58, 50, 0, 540, 770, 0, 140, 800, 0, 155, 155, '', 120, 45, 77, NULL, 'A4hoch', 0, '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', NULL, NULL, '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', NULL, NULL, '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm', '/home/fgs/fgs/apps/PDFClass/fonts/Courier-Bold.afm');

-- --------------------------------------------------------

--
-- Table structure for table `druckrahmen2freibilder`
--

CREATE TABLE `druckrahmen2freibilder` (
  `druckrahmen_id` int(11) NOT NULL,
  `freibild_id` int(11) NOT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `angle` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckrahmen2freitexte`
--

CREATE TABLE `druckrahmen2freitexte` (
  `druckrahmen_id` int(11) NOT NULL,
  `freitext_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckrahmen2stelle`
--

CREATE TABLE `druckrahmen2stelle` (
  `stelle_id` int(11) NOT NULL,
  `druckrahmen_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `druckrahmen2stelle`
--

INSERT INTO `druckrahmen2stelle` (`stelle_id`, `druckrahmen_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE `labels` (
  `Label_ID` int(11) NOT NULL,
  `font` varchar(25) NOT NULL DEFAULT 'arial',
  `type` int(1) DEFAULT NULL,
  `color` varchar(11) NOT NULL DEFAULT '',
  `outlinecolor` varchar(11) DEFAULT NULL,
  `shadowcolor` varchar(11) DEFAULT NULL,
  `shadowsizex` int(3) DEFAULT NULL,
  `shadowsizey` int(3) DEFAULT NULL,
  `backgroundcolor` varchar(11) DEFAULT NULL,
  `backgroundshadowcolor` varchar(11) DEFAULT NULL,
  `backgroundshadowsizex` int(3) DEFAULT NULL,
  `backgroundshadowsizey` int(3) DEFAULT NULL,
  `size` int(2) DEFAULT NULL,
  `minsize` tinyint(3) DEFAULT NULL,
  `maxsize` tinyint(3) DEFAULT NULL,
  `position` tinyint(1) DEFAULT NULL,
  `offsetx` tinyint(3) DEFAULT NULL,
  `offsety` tinyint(3) DEFAULT NULL,
  `angle` double DEFAULT NULL,
  `autoangle` tinyint(1) DEFAULT NULL,
  `buffer` tinyint(3) DEFAULT NULL,
  `antialias` tinyint(1) DEFAULT NULL,
  `minfeaturesize` int(11) DEFAULT NULL,
  `maxfeaturesize` int(11) DEFAULT NULL,
  `partials` int(1) DEFAULT NULL,
  `maxlength` int(3) DEFAULT NULL,
  `wrap` tinyint(3) DEFAULT NULL,
  `the_force` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `layer`
--

CREATE TABLE `layer` (
  `Layer_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Name_low-german` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name_english` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name_polish` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name_vietnamese` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `Datentyp` tinyint(4) NOT NULL DEFAULT '2',
  `Gruppe` int(11) NOT NULL DEFAULT '0',
  `pfad` text CHARACTER SET latin1 COLLATE latin1_german2_ci,
  `maintable` varchar(255) DEFAULT NULL,
  `maintable_is_view` tinyint(1) NOT NULL DEFAULT '0',
  `Data` text CHARACTER SET latin1 COLLATE latin1_german2_ci,
  `schema` varchar(50) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `document_path` text CHARACTER SET latin1 COLLATE latin1_german2_ci,
  `tileindex` varchar(100) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `tileitem` varchar(100) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `labelangleitem` varchar(25) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `labelitem` varchar(100) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `labelmaxscale` int(11) DEFAULT NULL,
  `labelminscale` int(11) DEFAULT NULL,
  `labelrequires` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `postlabelcache` tinyint(1) DEFAULT '0',
  `connection` text CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `printconnection` text CHARACTER SET latin1 COLLATE latin1_german2_ci,
  `connectiontype` tinyint(4) NOT NULL DEFAULT '0',
  `classitem` varchar(100) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `classification` varchar(50) DEFAULT NULL,
  `filteritem` varchar(100) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT 'ID',
  `cluster_maxdistance` int(11) DEFAULT NULL,
  `tolerance` double NOT NULL DEFAULT '3',
  `toleranceunits` enum('pixels','feet','inches','kilometers','meters','miles','dd') CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT 'pixels',
  `epsg_code` varchar(6) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT '2398',
  `template` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `queryable` enum('0','1') CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT '0',
  `transparency` int(3) DEFAULT NULL,
  `drawingorder` int(11) DEFAULT NULL,
  `minscale` int(11) DEFAULT NULL,
  `maxscale` int(11) DEFAULT NULL,
  `symbolscale` int(11) DEFAULT NULL,
  `offsite` varchar(11) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `requires` int(11) DEFAULT NULL,
  `ows_srs` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT 'EPSG:2398',
  `wms_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `wms_server_version` varchar(8) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT '1.1.0',
  `wms_format` varchar(50) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT 'image/png',
  `wms_connectiontimeout` int(11) NOT NULL DEFAULT '60',
  `wms_auth_username` varchar(50) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `wms_auth_password` varchar(50) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `wfs_geom` varchar(100) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `selectiontype` varchar(20) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `querymap` enum('0','1') CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT '0',
  `logconsume` enum('0','1') CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT '0',
  `processing` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `kurzbeschreibung` text,
  `datenherr` varchar(100) DEFAULT NULL,
  `metalink` varchar(255) DEFAULT NULL,
  `privileg` enum('0','1','2') NOT NULL DEFAULT '0',
  `export_privileg` tinyint(1) NOT NULL DEFAULT '1',
  `status` varchar(255) DEFAULT NULL,
  `trigger_function` varchar(255) DEFAULT NULL COMMENT 'Wie heist die Trigger Funktion, die ausgelÃ¶st werden soll.'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `layer`
--

INSERT INTO `layer` (`Layer_ID`, `Name`, `Name_low-german`, `Name_english`, `Name_polish`, `Name_vietnamese`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `maintable_is_view`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `postlabelcache`, `connection`, `printconnection`, `connectiontype`, `classitem`, `classification`, `filteritem`, `cluster_maxdistance`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `symbolscale`, `offsite`, `requires`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `export_privileg`, `status`, `trigger_function`) VALUES
(1, 'ORKa-MV (OSM)', NULL, NULL, NULL, NULL, '', 3, 1, '', '', 0, '', '', '', '', '', '', '', NULL, NULL, '', 0, 'http://www.orka-mv.de/geodienste/orkamv/wms?VERSION=1.1.1&LAYERS=orkamv&STYLES=&FORMAT=image/jpeg', '', 7, '', '', '', NULL, 3, 'pixels', '25833', '', '0', NULL, NULL, NULL, NULL, NULL, '', NULL, 'EPSG:25833', 'stadtplan', '1.1.1', 'image/png', 20, '', '', '', 'radio', '0', '0', '', '', '', '', '0', 1, '', ''),
(2, 'Anlagen', NULL, NULL, NULL, NULL, '', 0, 11, 'SELECT id AS anlage_id, name, klasse, zustaend_uwb, zustaend_stalu, bearbeiter, objektid_geodin, betreiber, kommentar, \'aktuell\' AS aktuell, \'\' AS aktuelle_wasserrechtliche_zulassungen,  \'historisch\' AS historisch,\'\' AS historische_wasserrechtliche_zulassungen, \'\' AS gewaesserbenutzungen,  \'\' AS wrz_ben_lage, abwasser_koerperschaft,        trinkwasser_koerperschaft, the_geom FROM anlagen WHERE 1=1', 'anlagen', 0, 'the_geom from (select oid, * from wasserrecht.anlagen) as foo using unique oid using srid=35833', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(3, 'Anlagenklasse', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM anlagen_klasse WHERE 1=1', 'anlagen_klasse', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(6, 'Körperschaft', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name,art FROM koerperschaft WHERE 1=1', 'koerperschaft', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(9, 'Personen', NULL, NULL, NULL, NULL, '', 5, 11, 'select id AS personen_id, name, bezeichnung, klasse, status, adresse as adress_id, typ, wrzadressat, wrzrechtsnachfolger, betreiber, bearbeiter, weeerklaerer, telefon, fax, email, abkuerzung, wrzaussteller, kommentar, zimmer, behoerde, register_amtsgericht, register_nummer, konto as konto_id,   \'aktuell\' AS aktuell, \'\' AS per_wrz, \'\' AS per_wrz_ben, abwasser_koerperschaft, trinkwasser_koerperschaft, the_geo FROM personen WHERE 1=1', 'personen', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 3, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(10, 'Personen_Klasse', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM personen_klasse WHERE 1=1', 'personen_klasse', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(11, 'Personen_Status', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM personen_status WHERE 1=1', 'personen_status', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(12, 'Adresse', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id as adress_id, strasse, hausnummer, plz, ort FROM adresse WHERE 1=1', 'adresse', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(13, 'Personen_Typ', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM personen_typ WHERE 1=1', 'personen_typ', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(14, 'Weeerklaerer', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM weeerklaerer WHERE 1=1', 'weeerklaerer ', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(16, 'Konto', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id as konto_id, name, iban, bic, verwendungszweck, personenkonto, kassenzeichen FROM konto WHERE 1=1', 'konto', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(17, 'Behoerde', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, abkuerzung, status FROM behoerde WHERE 1=1', 'behoerde', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(18, 'Wasserrechtliche_Zulassungen_Ausgangsbescheide', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, klasse, bearbeiterzeichen, aktenzeichen, datum, ort, regnummer, ausstellbehoerde FROM wasserrechtliche_zulassungen_ausgangsbescheide WHERE 1=1', 'wasserrechtliche_zulassungen_ausgangsbescheide', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(19, 'Wasserrechtliche_Zulassungen_Fassung', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, auswahl, aktenzeichen, klasse, datum, ort, nummer FROM wasserrechtliche_zulassungen_fassung WHERE 1=1', 'wasserrechtliche_zulassungen_fassung', 0, '', 'wasserrecht', '', '', '', '', 'nummer ', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(20, 'Wasserrechtliche_Zulassungen_Status', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM wasserrechtliche_zulassungen_status WHERE 1=1', 'wasserrechtliche_zulassungen_status', 0, '', 'wasserrecht', '', '', '', '', 'id', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(21, 'Wasserrechtliche_Zulassungen_Aenderungsbescheide', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, bearbeiterzeichen, aktenzeichen, datum_postausgang, datum_bestand_mat,        datum_bestand_form, ort, nummer FROM wasserrechtliche_zulassungen_aenderungsbescheide WHERE 1=1', 'wasserrechtliche_zulassungen_aenderungsbescheide', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(22, 'Wasserrechtliche_Zulassungen_Gueltigkeit', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, gueltig_seit, gueltig_bis, ungueltig_seit, ungueltig_aufgrund, CASE WHEN gueltig_bis < current_date THEN \'nein\' ELSE \'ja\' END AS wirksam, abgelaufen FROM wasserrechtliche_zulassungen_gueltigkeit WHERE 1=1', 'wasserrechtliche_zulassungen_gueltigkeit', 0, '', 'wasserrecht', '', '', '', '', 'id', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(23, 'Aktenzeichen', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM aktenzeichen WHERE 1=1', 'aktenzeichen', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(24, 'Dokument', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, pfad, document FROM dokument WHERE 1=1', 'dokument', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(25, 'Wasserrechtliche_Zulassungen', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT a.id as wrz_id, a.name, COALESCE(c.name,\'\') ||\' (Aktenzeichen: \'|| COALESCE(d.name,\'\') ||\')\'||\' vom \'|| COALESCE(b.datum::text,\'\') AS bezeichnung, a.ausgangsbescheid, a.fassung, a.status, a.adresse as adress_id,  a.aenderungsbescheid, a.gueltigkeit, CASE when a.aktuell then \'aktuell\' else \'false\' end AS aktuell, case when a.historisch then \'historisch\' else \'false\' end as historisch, a.bergamt_aktenzeichen, a.dokument, a.ausstellbehoerde, \'\' AS ausstellbehoerde_link, a.sachbearbeiter, \'\' AS sachbearbeiter_link, a.adressat AS personen_id, \'\' AS adressat_link, a.anlage AS anlage_id, \'\' AS anlage_anzeige,  \'\' AS wrz_ben FROM wasserrechtliche_zulassungen a LEFT JOIN wasserrechtliche_zulassungen_ausgangsbescheide b ON a.ausgangsbescheid = b.id LEFT JOIN wasserrechtliche_zulassungen_ausgangsbescheide_klasse c ON b.klasse = c.id LEFT JOIN aktenzeichen d ON b.aktenzeichen = d.id WHERE 1=1', 'wasserrechtliche_zulassungen', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(26, 'Wasserrechtliche_Zulassungen_Ausgangsbescheide_Klasse', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM wasserrechtliche_zulassungen_ausgangsbescheide_klasse WHERE 1=1', 'wasserrechtliche_zulassungen_ausgangsbescheide_klasse ', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(29, 'Wasserrechtliche_Zulassungen_Bearbeiterzeichen', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM wasserrechtliche_zulassungen_bearbeiterzeichen WHERE 1=1', 'wasserrechtliche_zulassungen_bearbeiterzeichen', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(30, 'Ort', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name,the_geo FROM ort WHERE 1=1', 'ort', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(31, 'Wasserrechtliche_Zulassungen_Fassung_Auswahl', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM wasserrechtliche_zulassungen_fassung_auswahl WHERE 1=1', 'wasserrechtliche_zulassungen_fassung_auswahl', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(32, 'Wasserrechtliche_Zulassungen_Gueltigkeit_Aufgrund', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM wasserrechtliche_zulassungen_ungueltig_aufgrund WHERE 1=1', 'wasserrechtliche_zulassungen_ungueltig_aufgrund', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(33, 'Gewaesserbenutzungen', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT b.id, b.kennnummer, COALESCE(a.name,\'\') ||\' (Aktenzeichen: \'|| COALESCE(h.name,\'\') ||\')\'||\' vom \'|| COALESCE(f.datum::text,\'\') || \' zum \' || COALESCE(c.name,\'\') || \' von \' || COALESCE(d.max_ent_a::text,\'\') || \' m³/Jahr\' AS bezeichnung, b.art, b.wasserbuch, b.zweck, b.umfang, b.gruppe_wee, b.lage,  \'\' AS  lage_link, b.wasserrechtliche_zulassungen as wrz_id, \'\' AS wasserrechtliche_zulassungen_link, e.namelang AS wrz_ben_lage_namelang, a.adressat as personen_id,  CASE when a.aktuell then \'aktuell\' else \'false\' end AS aktuell, CASE when a.historisch then \'historisch\' else \'false\' end as historisch, a.anlage AS anlage_id, \'\' AS anlage FROM wasserrechtliche_zulassungen a LEFT JOIN wasserrechtliche_zulassungen_ausgangsbescheide f ON a.ausgangsbescheid = f.id LEFT JOIN wasserrechtliche_zulassungen_ausgangsbescheide_klasse g ON f.klasse = g.id LEFT JOIN aktenzeichen h ON f.aktenzeichen = h.id INNER JOIN gewaesserbenutzungen b ON b.wasserrechtliche_zulassungen = a.id LEFT JOIN gewaesserbenutzungen_art c ON c.id = b.art LEFT JOIN gewaesserbenutzungen_umfang d ON b.umfang = d.id LEFT JOIN gewaesserbenutzungen_lage e ON b.lage = e.id WHERE 1=1', 'gewaesserbenutzungen', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(34, 'Gewaesserbenutzungen_Art', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, freitext, wgh FROM gewaesserbenutzungen_art WHERE 1=1', 'gewaesserbenutzungen_art', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(35, 'Wasserbuch', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, nummer FROM wasserbuch WHERE 1=1', 'wasserbuch', 0, '', 'wasserrecht', '', '', '', '', 'nummer', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(36, 'Gewaesserbenutzungen_Zweck', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, freitext FROM gewaesserbenutzungen_zweck WHERE 1=1', 'gewaesserbenutzungen_zweck', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(37, 'Gewaesserbenutzungen_Umfang', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, max_ent_s, max_ent_h, max_ent_d, max_ent_w, max_ent_m,        max_ent_a, max_ent_wee, max_ent_wee_beschreib, max_ent_wb, max_ent_wb_beschreib,        max_ent_frei, max_ent_frei_beschreib, freitext FROM gewaesserbenutzungen_umfang WHERE 1=1', 'gewaesserbenutzungen_umfang', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(38, 'Gewaesserbenutzungen_Lage', NULL, NULL, NULL, NULL, '', 0, 11, 'SELECT id, name, betreiber, wwident, namelang, namekurz, bohrungsname,        baujahr, endteufe, filterok, filteruk, betriebszustand, messtischblatt,        archivnummer, schichtenverzeichnis, invid, the_geo FROM gewaesserbenutzungen_lage WHERE 1=1', 'gewaesserbenutzungen_lage', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(39, 'Betriebszustand', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM betriebszustand WHERE 1=1', 'betriebszustand', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(40, 'Messtischblatt', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, nummer FROM messtischblatt WHERE 1=1', 'messtischblatt ', 0, '', 'wasserrecht', '', '', '', '', 'nummer', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(41, 'Archivnummer', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, nummer FROM archivnummer WHERE 1=1', 'archivnummer', 0, '', 'wasserrecht', '', '', '', '', 'nummer', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(42, 'Körperschaftsart', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM koerperschaft_art WHERE 1=1', 'koerperschaft_art', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `layer_attributes`
--

CREATE TABLE `layer_attributes` (
  `layer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `real_name` varchar(255) DEFAULT NULL,
  `tablename` varchar(100) DEFAULT NULL,
  `table_alias_name` varchar(100) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `geometrytype` varchar(20) DEFAULT NULL,
  `constraints` varchar(255) DEFAULT NULL,
  `nullable` tinyint(1) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `decimal_length` int(11) DEFAULT NULL,
  `default` varchar(255) DEFAULT NULL,
  `form_element_type` enum('Text','Textfeld','Auswahlfeld','Autovervollständigungsfeld','Radiobutton','Checkbox','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','dynamicLink','User','UserID','Stelle','StelleID','Fläche','Länge','Zahl','mailto','Winkel') NOT NULL DEFAULT 'Text',
  `options` text,
  `alias` varchar(255) DEFAULT NULL,
  `alias_low-german` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias_english` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias_polish` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias_vietnamese` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tooltip` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `arrangement` tinyint(1) NOT NULL DEFAULT '0',
  `labeling` tinyint(1) NOT NULL DEFAULT '0',
  `raster_visibility` tinyint(1) DEFAULT NULL,
  `dont_use_for_new` tinyint(1) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `quicksearch` tinyint(1) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `privileg` tinyint(1) DEFAULT '0',
  `query_tooltip` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `layer_attributes`
--

INSERT INTO `layer_attributes` (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `arrangement`, `labeling`, `raster_visibility`, `dont_use_for_new`, `mandatory`, `quicksearch`, `order`, `privileg`, `query_tooltip`) VALUES
(2, 'anlage_id', 'id', 'anlagen', 'anlagen', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'Id', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(2, 'name', 'name', 'anlagen', 'anlagen', 'varchar', '', '', 0, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(2, 'klasse', 'klasse', 'anlagen', 'anlagen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.anlagen_klasse;layer_id=3 embedded', 'Klasse', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(2, 'zustaend_uwb', 'zustaend_uwb', 'anlagen', 'anlagen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(b.name, \', \'),  \'keiner Behörde zugeordnet\') || \')\' AS output FROM\r\n  personen z LEFT JOIN\r\n  behoerde b ON z.behoerde = b.id WHERE z.behoerde = 1\r\nGROUP BY\r\n  z.id, z.name ;layer_id=9 embedded', 'Zuständiger Untere Wasserbehörde', NULL, NULL, NULL, NULL, 'Die Auswahlliste enthält einen Namen und seine zugehörigkeit zu ein oder mehreren Behörden', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(9, 'behoerde', 'behoerde', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.behoerde;layer_id=17 embedded', 'Behörde', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 19, 0, 0),
(2, 'zustaend_stalu', 'zustaend_stalu', 'anlagen', 'anlagen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(b.name, \', \'),  \'keiner Behörde zugeordnet\') || \')\' AS output FROM\r\n  personen z LEFT JOIN\r\n  behoerde b ON z.behoerde = b.id  WHERE z.behoerde = 2\r\nGROUP BY\r\n  z.id, z.name ;layer_id=9 embedded', 'Zuständiger Staatliches Amt für Landwirtschaft Umwelt', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(2, 'bearbeiter', 'bearbeiter', 'anlagen', 'anlagen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.personen WHERE bearbeiter=true;layer_id=9 embedded', 'Bearbeiter', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(2, 'objektid_geodin', 'objektid_geodin', 'anlagen', 'anlagen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(2, 'historische_wasserrechtliche_zulassungen', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,anlage_id,historisch,<b>Wasserrechtliche Zulassung:</b> bezeichnung;no_new_window', 'Historische Wasserrechtliche Zulassungen', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 1, 2, NULL, -1, 0, NULL, 12, 0, 0),
(2, 'abwasser_koerperschaft', 'abwasser_koerperschaft', 'anlagen', 'anlagen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM\r\n  wasserrecht.koerperschaft z LEFT JOIN	\r\n  wasserrecht.koerperschaft_art z2b ON z.art = z2b.id\r\nGROUP BY\r\n  z.id, z.name ;layer_id=6 embedded', 'Abwasser Körperschaft', NULL, NULL, NULL, NULL, '', 'Körperschaft', 0, 0, NULL, 0, 0, NULL, 15, 0, 0),
(2, 'trinkwasser_koerperschaft', 'trinkwasser_koerperschaft', 'anlagen', 'anlagen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM\r\n  wasserrecht.koerperschaft z LEFT JOIN	\r\n  wasserrecht.koerperschaft_art z2b ON z.art = z2b.id\r\nGROUP BY\r\n  z.id, z.name ;layer_id=6 embedded', 'Trinkwasser Körperschaft', NULL, NULL, NULL, NULL, '', 'Körperschaft', 0, 0, NULL, 0, 0, NULL, 16, 0, 0),
(2, 'kommentar', 'kommentar', 'anlagen', 'anlagen', 'text', '', '', 1, NULL, NULL, '', 'Textfeld', '', 'Kommentar', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(2, 'the_geom', 'the_geom', 'anlagen', 'anlagen', 'geometry', 'POINT', '', 1, NULL, NULL, '', 'Geometrie', '', 'Geometrie', NULL, NULL, NULL, NULL, '', 'Geometrie', 0, 0, NULL, 0, 0, NULL, 17, 0, 0),
(3, 'id', 'id', 'anlagen_klasse', 'anlagen_klasse', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(3, 'name', 'name', 'anlagen_klasse', 'anlagen_klasse', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(22, 'gueltig_seit', 'gueltig_seit', 'wasserrechtliche_zulassungen_gueltigkeit', 'wasserrechtliche_zulassungen_gueltigkeit', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Gültig Seit', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(6, 'id', 'id', 'koerperschaft', 'koerperschaft', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(6, 'name', 'name', 'koerperschaft', 'koerperschaft', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(9, 'the_geo', 'the_geo', 'personen', 'personen', 'geometry', 'POINT', '', 1, NULL, NULL, '', 'Geometrie', '', '', NULL, NULL, NULL, NULL, '', 'Geometrie', 0, 0, NULL, 0, 0, NULL, 28, 0, 0),
(9, 'klasse', 'klasse', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.personen_klasse;layer_id=10 embedded', 'Klasse', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(9, 'status', 'status', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.personen_status;layer_id=11 embedded', 'Status', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(12, 'adress_id', 'id', 'adresse', 'adresse', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(9, 'typ', 'typ', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.personen_typ;layer_id=13 embedded', 'Typ', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(9, 'wrzadressat', 'wrzadressat', 'personen', 'personen', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(9, 'wrzrechtsnachfolger', 'wrzrechtsnachfolger', 'personen', 'personen', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(9, 'betreiber', 'betreiber', 'personen', 'personen', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', 'Betreiber', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(9, 'bearbeiter', 'bearbeiter', 'personen', 'personen', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', 'Bearbeiter', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(9, 'weeerklaerer', 'weeerklaerer', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.weeerklaerer;layer_id=14 embedded', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(9, 'telefon', 'telefon', 'personen', 'personen', 'varchar', '', '', 1, 50, NULL, '', 'Text', '', 'Telefon', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 12, 0, 0),
(9, 'fax', 'fax', 'personen', 'personen', 'varchar', '', '', 1, 50, NULL, '', 'Text', '', 'Fax', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 13, 0, 0),
(9, 'email', 'email', 'personen', 'personen', 'varchar', '', '', 1, 50, NULL, '', 'Text', '', 'E-Mail', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 14, 0, 0),
(9, 'abkuerzung', 'abkuerzung', 'personen', 'personen', 'varchar', '', '', 1, 30, NULL, '', 'Text', '', 'Abkürzung', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 15, 0, 0),
(9, 'bezeichnung', 'bezeichnung', 'personen', 'personen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(9, 'wrzaussteller', 'wrzaussteller', 'personen', 'personen', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 16, 0, 0),
(25, 'personen_id', 'adressat', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.personen;layer_id=9 embedded', 'Adressat [Auswahlfeld]', NULL, NULL, NULL, NULL, '', 'Adressat', 0, 0, NULL, 0, 0, NULL, 17, 0, 0),
(9, 'abwasser_koerperschaft', 'abwasser_koerperschaft', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM\r\n  wasserrecht.koerperschaft z LEFT JOIN	\r\n  wasserrecht.koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id = 2\r\nGROUP BY\r\n  z.id, z.name ;layer_id=6 embedded', '', NULL, NULL, NULL, NULL, '', 'Körperschaft', 0, 0, NULL, 0, 0, NULL, 26, 0, 0),
(9, 'trinkwasser_koerperschaft', 'trinkwasser_koerperschaft', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM\r\n  wasserrecht.koerperschaft z LEFT JOIN	\r\n  wasserrecht.koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id = 1\r\nGROUP BY\r\n  z.id, z.name ;layer_id=6 embedded', '', NULL, NULL, NULL, NULL, '', 'Körperschaft', 0, 0, NULL, 0, 0, NULL, 27, 0, 0),
(9, 'kommentar', 'kommentar', 'personen', 'personen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Kommentar', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 17, 0, 0),
(9, 'zimmer', 'zimmer', 'personen', 'personen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Zimmer', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 18, 0, 0),
(9, 'register_amtsgericht', 'register_amtsgericht', 'personen', 'personen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 20, 0, 0),
(9, 'register_nummer', 'register_nummer', 'personen', 'personen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 21, 0, 0),
(17, 'id', 'id', 'behoerde', 'behoerde', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(10, 'id', 'id', 'personen_klasse', 'personen_klasse', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(10, 'name', 'name', 'personen_klasse', 'personen_klasse', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(11, 'id', 'id', 'personen_status', 'personen_status', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(11, 'name', 'name', 'personen_status', 'personen_status', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(12, 'strasse', 'strasse', 'adresse', 'adresse', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Straße', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(12, 'hausnummer', 'hausnummer', 'adresse', 'adresse', 'varchar', '', '', 1, 10, NULL, '', 'Text', '', 'Hausnummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(12, 'plz', 'plz', 'adresse', 'adresse', 'int4', '', '', 1, 32, 0, '', 'Zahl', '', 'Postleitzahl', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(12, 'ort', 'ort', 'adresse', 'adresse', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Ort', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(13, 'id', 'id', 'personen_typ', 'personen_typ', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(9, 'adress_id', 'adresse', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, ort||\' \'||plz||\' \'||strasse||\' \'||hausnummer as output from wasserrecht.adresse where 1=1;layer_id=12 embedded', 'Adresse', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(13, 'name', 'name', 'personen_typ', 'personen_typ', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(14, 'id', 'id', 'weeerklaerer', 'weeerklaerer', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(14, 'name', 'name', 'weeerklaerer', 'weeerklaerer', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(25, 'ausstellbehoerde_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=17&value_id=$ausstellbehoerde&operator_id==;Behörde anzeigen;no_new_window', 'Ausstellbehörde [Link]', NULL, NULL, NULL, NULL, '', 'Ausstellbehörde', 0, 0, NULL, -1, 0, NULL, 14, 0, 0),
(9, 'konto_id', 'konto', 'personen', 'personen', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, name||\' \'||iban||\' \'||bic||\' \'||verwendungszweck||\' \'||personenkonto||\' \'||kassenzeichen as output from wasserrecht.konto where 1=1;layer_id=16 embedded', 'Konto', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 22, 0, 0),
(16, 'name', 'name', 'konto', 'konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(16, 'iban', 'iban', 'konto', 'konto', 'varchar', '', '', 1, 22, NULL, '', 'Text', '', 'IBAN', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(16, 'bic', 'bic', 'konto', 'konto', 'varchar', '', '', 1, 11, NULL, '', 'Text', '', 'BIC', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(16, 'verwendungszweck', 'verwendungszweck', 'konto', 'konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Verwendungszweck', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(16, 'personenkonto', 'personenkonto', 'konto', 'konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Personenkonto', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(16, 'kassenzeichen', 'kassenzeichen', 'konto', 'konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Kassenzeichen', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(16, 'konto_id', 'id', 'konto', 'konto', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(17, 'name', 'name', 'behoerde', 'behoerde', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(17, 'abkuerzung', 'abkuerzung', 'behoerde', 'behoerde', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Abkürzung', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(17, 'status', 'status', 'behoerde', 'behoerde', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Status', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(18, 'id', 'id', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(18, 'klasse', 'klasse', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse;layer_id=26 embedded', 'Klasse', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(18, 'aktenzeichen', 'aktenzeichen', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.aktenzeichen;layer_id=23 embedded', 'Aktenzeichen', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(18, 'datum', 'datum', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Datum', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(18, 'ort', 'ort', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.ort;layer_id=30 embedded', 'Ort', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(18, 'regnummer', 'regnummer', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Regnummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(18, 'ausstellbehoerde', 'ausstellbehoerde', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.behoerde;layer_id=17 embedded', 'Ausstellbehörde', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(19, 'id', 'id', 'wasserrechtliche_zulassungen_fassung', 'wasserrechtliche_zulassungen_fassung', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(19, 'auswahl', 'auswahl', 'wasserrechtliche_zulassungen_fassung', 'wasserrechtliche_zulassungen_fassung', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.wasserrechtliche_zulassungen_fassung_auswahl;layer_id=31 embedded', 'Auswahl', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(19, 'aktenzeichen', 'aktenzeichen', 'wasserrechtliche_zulassungen_fassung', 'wasserrechtliche_zulassungen_fassung', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.aktenzeichen;layer_id=23 embedded', 'Aktenzeichen', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(19, 'klasse', 'klasse', 'wasserrechtliche_zulassungen_fassung', 'wasserrechtliche_zulassungen_fassung', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.wasserrechtliche_zulassungen_fassung_klasse;layer_id=23 embedded', 'Klasse', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(19, 'datum', 'datum', 'wasserrechtliche_zulassungen_fassung', 'wasserrechtliche_zulassungen_fassung', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Datum', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(19, 'ort', 'ort', 'wasserrechtliche_zulassungen_fassung', 'wasserrechtliche_zulassungen_fassung', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.ort;layer_id=30 embedded', 'Ort', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(19, 'nummer', 'nummer', 'wasserrechtliche_zulassungen_fassung', 'wasserrechtliche_zulassungen_fassung', 'int4', '', '', 1, 32, 0, '', 'Zahl', '', 'Nummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(20, 'id', 'id', 'wasserrechtliche_zulassungen_status', 'wasserrechtliche_zulassungen_status', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(20, 'name', 'name', 'wasserrechtliche_zulassungen_status', 'wasserrechtliche_zulassungen_status', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(21, 'id', 'id', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(21, 'aktenzeichen', 'aktenzeichen', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.aktenzeichen;layer_id=23 embedded', 'Aktenzeichen', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(21, 'datum_postausgang', 'datum_postausgang', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Datum Postausgang', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(21, 'datum_bestand_mat', 'datum_bestand_mat', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Datum Bestand Mat', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(21, 'datum_bestand_form', 'datum_bestand_form', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Datum Bestand Form', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(21, 'ort', 'ort', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.ort;layer_id=30 embedded', 'Ort', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(21, 'nummer', 'nummer', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Nummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(9, 'aktuell', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Adressat von', 0, 2, NULL, -1, -1, NULL, 23, 0, 0),
(33, 'wrz_ben_lage_namelang', 'namelang', 'gewaesserbenutzungen_lage', 'e', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Lage Name (lang)', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, 0, NULL, 12, 0, 0),
(22, 'ungueltig_seit', 'ungueltig_seit', 'wasserrechtliche_zulassungen_gueltigkeit', 'wasserrechtliche_zulassungen_gueltigkeit', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Ungültig Seit', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(22, 'ungueltig_aufgrund', 'ungueltig_aufgrund', 'wasserrechtliche_zulassungen_gueltigkeit', 'wasserrechtliche_zulassungen_gueltigkeit', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.wasserrechtliche_zulassungen_ungueltig_aufgrund;layer_id=32 embedded', 'Ungültig Aufgrund', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(22, 'id', 'id', 'wasserrechtliche_zulassungen_gueltigkeit', 'wasserrechtliche_zulassungen_gueltigkeit', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(22, 'abgelaufen', 'abgelaufen', 'wasserrechtliche_zulassungen_gueltigkeit', 'wasserrechtliche_zulassungen_gueltigkeit', 'bool', '', '', 1, NULL, NULL, '', 'Text', '', 'Abgelaufen', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(22, 'gueltig_bis', 'gueltig_bis', 'wasserrechtliche_zulassungen_gueltigkeit', 'wasserrechtliche_zulassungen_gueltigkeit', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Gültig Bis', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(23, 'id', 'id', 'aktenzeichen', 'aktenzeichen', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(23, 'name', 'name', 'aktenzeichen', 'aktenzeichen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(24, 'id', 'id', 'dokument', 'dokument', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(24, 'name', 'name', 'dokument', 'dokument', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(24, 'pfad', 'pfad', 'dokument', 'dokument', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Pfad', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(24, 'document', 'document', 'dokument', 'dokument', 'bytea', '', '', 1, NULL, NULL, '', 'Dokument', '', 'Dokument', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(25, 'ausstellbehoerde', 'ausstellbehoerde', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.behoerde;layer_id=17 embedded', 'Ausstellbehörde [Auswahlfeld]', NULL, NULL, NULL, NULL, '', 'Ausstellbehörde', 0, 0, NULL, 0, 0, NULL, 13, 0, 0),
(25, 'ausgangsbescheid', 'ausgangsbescheid', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide;layer_id=18 embedded', 'Ausgangsbescheid', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(25, 'fassung', 'fassung', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, nummer as output from wasserrecht.wasserrechtliche_zulassungen_fassung;layer_id=27 embedded', 'Fassung', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(25, 'status', 'status', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.wasserrechtliche_zulassungen_status;layer_id=20 embedded', 'Status', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(25, 'aenderungsbescheid', 'aenderungsbescheid', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, nummer as output from wasserrecht.wasserrechtliche_zulassungen_aenderungsbescheide;layer_id=21 embedded', 'Änderungsbescheid', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(25, 'gueltigkeit', 'gueltigkeit', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, gueltig_seit as output from wasserrecht.wasserrechtliche_zulassungen_gueltigkeit;layer_id=22 embedded', 'Gültigkeit', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(25, 'bergamt_aktenzeichen', 'bergamt_aktenzeichen', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.aktenzeichen;layer_id=23 embedded', 'Bergamt Aktenzeichen', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(25, 'dokument', 'dokument', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.dokument;layer_id=24 embedded', 'Dokument', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 12, 0, 0),
(21, 'bearbeiterzeichen', 'bearbeiterzeichen', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'wasserrechtliche_zulassungen_aenderungsbescheide', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output FROM wasserrecht.wasserrechtliche_zulassungen_bearbeiterzeichen;layer_id=29 embedded', 'Bearbeiterzeichen', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(18, 'bearbeiterzeichen', 'bearbeiterzeichen', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output FROM wasserrecht.wasserrechtliche_zulassungen_bearbeiterzeichen;layer_id=29 embedded', 'Bearbeiterzeichen', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(2, 'gewaesserbenutzungen', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,anlage_id,aktuell,<b>Benutzung:</b> bezeichnung;no_new_window', 'Aktuelle Benutzungen', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, 0, NULL, 13, 0, 0),
(26, 'id', 'id', 'wasserrechtliche_zulassungen_ausgangsbescheide_klasse', 'wasserrechtliche_zulassungen_ausgangsbescheide_klasse', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(26, 'name', 'name', 'wasserrechtliche_zulassungen_ausgangsbescheide_klasse', 'wasserrechtliche_zulassungen_ausgangsbescheide_klasse', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(31, 'id', 'id', 'wasserrechtliche_zulassungen_fassung_auswahl', 'wasserrechtliche_zulassungen_fassung_auswahl', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(31, 'name', 'name', 'wasserrechtliche_zulassungen_fassung_auswahl', 'wasserrechtliche_zulassungen_fassung_auswahl', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(25, 'adress_id', 'adresse', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, ort||\' \'||plz||\' \'||strasse||\' \'||hausnummer as output from wasserrecht.adresse where 1=1;layer_id=12 embedded', 'Adresse', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(29, 'id', 'id', 'wasserrechtliche_zulassungen_bearbeiterzeichen', 'wasserrechtliche_zulassungen_bearbeiterzeichen', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(29, 'name', 'name', 'wasserrechtliche_zulassungen_bearbeiterzeichen', 'wasserrechtliche_zulassungen_bearbeiterzeichen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(30, 'id', 'id', 'ort', 'ort', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(30, 'name', 'name', 'ort', 'ort', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(30, 'the_geo', 'the_geo', 'ort', 'ort', 'geometry', 'POINT', '', 1, NULL, NULL, '', 'Geometrie', '', 'Geometrie', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(32, 'id', 'id', 'wasserrechtliche_zulassungen_ungueltig_aufgrund', 'wasserrechtliche_zulassungen_ungueltig_aufgrund', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(32, 'name', 'name', 'wasserrechtliche_zulassungen_ungueltig_aufgrund', 'wasserrechtliche_zulassungen_ungueltig_aufgrund', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(33, 'id', 'id', 'gewaesserbenutzungen', 'b', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(33, 'kennnummer', 'kennnummer', 'gewaesserbenutzungen', 'b', 'varchar', '', '', 1, 255, NULL, '', 'Text', 'SELECT case when \'$wrz_id\' = \'\' then \'Bitte erst eine Wasserrechtliche Zulassung auswählen!\' else (select (select CASE WHEN \'$id\' = \'\' THEN (last_value + 1)::text ELSE \'$id\' END as id from wasserrecht.gewaesserbenutzungen_id_seq) ||\'-\'|| a.id ||\'-\'|| c.id ||\'-\'|| d.id AS output FROM wasserrecht.wasserrechtliche_zulassungen b INNER JOIN wasserrecht.behoerde a ON a.id = b.ausstellbehoerde INNER JOIN wasserrecht.personen c ON c.id = b.adressat INNER JOIN wasserrecht.anlagen d ON d.id = b.anlage WHERE b.id::text = \'$wrz_id\') end', 'Kennummer', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(33, 'art', 'art', 'gewaesserbenutzungen', 'b', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.gewaesserbenutzungen_art;layer_id=34 embedded', 'Art', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(33, 'wasserbuch', 'wasserbuch', 'gewaesserbenutzungen', 'b', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, nummer as output from wasserrecht.wasserbuch;layer_id=35 embedded', 'Wasserbuch', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(33, 'zweck', 'zweck', 'gewaesserbenutzungen', 'b', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.gewaesserbenutzungen_zweck;layer_id=36 embedded', 'Zweck', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(33, 'umfang', 'umfang', 'gewaesserbenutzungen', 'b', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.gewaesserbenutzungen_umfang;layer_id=37 embedded', 'Umfang', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(33, 'gruppe_wee', 'gruppe_wee', 'gewaesserbenutzungen', 'b', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(33, 'lage', 'lage', 'gewaesserbenutzungen', 'b', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.gewaesserbenutzungen_lage;layer_id=38 no_new_window', 'Lage [Auswahlfeld]', NULL, NULL, NULL, NULL, '', 'Lage', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(33, 'wasserrechtliche_zulassungen_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=25&value_wrz_id=$wrz_id&operator_wrz_id==;Wasserrechtliche Zulassung anzeigen;no_new_window', 'Wasserrechtliche Zulassung [Link]', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, 0, NULL, 11, 0, 0),
(34, 'id', 'id', 'gewaesserbenutzungen_art', 'gewaesserbenutzungen_art', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(34, 'name', 'name', 'gewaesserbenutzungen_art', 'gewaesserbenutzungen_art', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(34, 'freitext', 'freitext', 'gewaesserbenutzungen_art', 'gewaesserbenutzungen_art', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Freitext', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(34, 'wgh', 'wgh', 'gewaesserbenutzungen_art', 'gewaesserbenutzungen_art', 'int4', '', '', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(35, 'id', 'id', 'wasserbuch', 'wasserbuch', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(35, 'nummer', 'nummer', 'wasserbuch', 'wasserbuch', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Nummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(36, 'id', 'id', 'gewaesserbenutzungen_zweck', 'gewaesserbenutzungen_zweck', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(36, 'name', 'name', 'gewaesserbenutzungen_zweck', 'gewaesserbenutzungen_zweck', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(36, 'freitext', 'freitext', 'gewaesserbenutzungen_zweck', 'gewaesserbenutzungen_zweck', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Freitext', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(37, 'id', 'id', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(37, 'name', 'name', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(37, 'max_ent_s', 'max_ent_s', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(37, 'max_ent_h', 'max_ent_h', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(37, 'max_ent_d', 'max_ent_d', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(37, 'max_ent_w', 'max_ent_w', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(37, 'max_ent_m', 'max_ent_m', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(37, 'max_ent_a', 'max_ent_a', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(37, 'max_ent_wee', 'max_ent_wee', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(37, 'max_ent_wee_beschreib', 'max_ent_wee_beschreib', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'text', '', '', 1, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(37, 'max_ent_wb', 'max_ent_wb', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(37, 'max_ent_wb_beschreib', 'max_ent_wb_beschreib', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'text', '', '', 1, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(37, 'max_ent_frei', 'max_ent_frei', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'numeric', '', '', 1, 15, 3, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 12, 0, 0),
(37, 'max_ent_frei_beschreib', 'max_ent_frei_beschreib', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'text', '', '', 1, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 13, 0, 0),
(37, 'freitext', 'freitext', 'gewaesserbenutzungen_umfang', 'gewaesserbenutzungen_umfang', 'text', '', '', 1, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 14, 0, 0),
(38, 'id', 'id', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(38, 'name', 'name', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(38, 'betreiber', 'betreiber', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.personen WHERE betreiber=true;layer_id=9 embedded', 'Betreiber', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(38, 'wwident', 'wwident', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(38, 'namelang', 'namelang', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name lang', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(38, 'namekurz', 'namekurz', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name kurz', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(38, 'bohrungsname', 'bohrungsname', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Bohrungsname', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(38, 'baujahr', 'baujahr', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Baujahr', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(38, 'endteufe', 'endteufe', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'numeric', '', '', 1, 6, 2, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(38, 'filterok', 'filterok', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'numeric', '', '', 1, 6, 2, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(38, 'filteruk', 'filteruk', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'numeric', '', '', 1, 6, 2, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(38, 'betriebszustand', 'betriebszustand', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.betriebszustand ;layer_id=39 embedded', 'Betriebszustand', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(38, 'messtischblatt', 'messtischblatt', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, nummer as output from wasserrecht.messtischblatt;layer_id=40 embedded', 'Messtischblatt', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 12, 0, 0),
(38, 'archivnummer', 'archivnummer', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, nummer as output from wasserrecht.archivnummer;layer_id=41 embedded', 'Archivnummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 13, 0, 0),
(38, 'schichtenverzeichnis', 'schichtenverzeichnis', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', 'Schichtenverzeichnis', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 14, 0, 0),
(38, 'invid', 'invid', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 15, 0, 0),
(38, 'the_geo', 'the_geo', 'gewaesserbenutzungen_lage', 'gewaesserbenutzungen_lage', 'geometry', 'POINT', '', 1, NULL, NULL, '', 'Geometrie', '', 'Geometrie', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 16, 0, 0),
(25, 'name', 'name', 'wasserrechtliche_zulassungen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(9, 'name', 'name', 'personen', 'personen', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(39, 'id', 'id', 'betriebszustand', 'betriebszustand', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(39, 'name', 'name', 'betriebszustand', 'betriebszustand', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(40, 'id', 'id', 'messtischblatt', 'messtischblatt', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(40, 'nummer', 'nummer', 'messtischblatt', 'messtischblatt', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Nummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(41, 'id', 'id', 'archivnummer', 'archivnummer', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(41, 'nummer', 'nummer', 'archivnummer', 'archivnummer', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Nummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(6, 'art', 'art', 'koerperschaft', 'koerperschaft', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.koerperschaft_art;layer_id=42 embedded', 'Körperschaftsart', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(42, 'id', 'id', 'koerperschaft_art', 'koerperschaft_art', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(42, 'name', 'name', 'koerperschaft_art', 'koerperschaft_art', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(22, 'wirksam', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Wirksam', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(25, 'sachbearbeiter', 'sachbearbeiter', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(b.name, \', \'),  \'keiner Behörde zugeordnet\') || \')\' AS output FROM\r\n  personen z LEFT JOIN\r\n  behoerde b ON z.behoerde = b.id WHERE NOT (behoerde IS NULL)\r\nGROUP BY\r\n  z.id, z.name ;layer_id=9 embedded', 'Sachbearbeiter [Auswahlfeld]', NULL, NULL, NULL, NULL, '', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 15, 0, 0),
(25, 'bezeichnung', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, 0, NULL, 2, 0, 0),
(25, 'wrz_id', 'id', 'wasserrechtliche_zulassungen', 'a', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(2, 'betreiber', 'betreiber', 'anlagen', 'anlagen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.personen WHERE betreiber=true;layer_id=9 embedded', 'Betreiber', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(2, 'wrz_ben_lage', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,anlage_id,aktuell,<b>Lage:</b> wrz_ben_lage_namelang;no_new_window', 'Lage der Benutzung', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, 0, NULL, 14, 0, 0),
(2, 'aktuelle_wasserrechtliche_zulassungen', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,anlage_id,aktuell, <b>Wasserrechtliche Zulassung:</b> bezeichnung;no_new_window', 'Aktuelle Wasserrechtliche Zulassungen', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 1, 2, NULL, -1, 0, NULL, 10, 0, 0),
(33, 'bezeichnung', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, 0, NULL, 2, 0, 0),
(25, 'aktuell', '', 'wasserrechtliche_zulassungen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Checkbox', '', 'Akutell', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(25, 'historisch', '', 'wasserrechtliche_zulassungen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Checkbox', '', 'Historisch', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(9, 'per_wrz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,personen_id,aktuell,<b>Wasserrechtliche Zulassung:</b> bezeichnung;no_new_window', 'Wasserechtliche Zulassungen', NULL, NULL, NULL, NULL, '', 'Adressat von', 0, 0, NULL, -1, 0, NULL, 24, 0, 0),
(9, 'per_wrz_ben', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,personen_id,aktuell,<b>Gewässerbenutzungen:</b> bezeichnung;no_new_window', 'Gewässerbenutzungen', NULL, NULL, NULL, NULL, '', 'Adressat von', 0, 0, NULL, -1, 0, NULL, 25, 0, 0),
(9, 'personen_id', 'id', 'personen', 'personen', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(25, 'anlage_anzeige', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '2,anlage_id,<b>Name:</b> name;no_new_window', 'Anlage', NULL, NULL, NULL, NULL, '', 'Anlage', 0, 0, NULL, -1, 0, NULL, 20, 0, 0),
(25, 'anlage_id', 'anlage', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.anlagen;layer_id=2 embedded', 'Anlage ID', NULL, NULL, NULL, NULL, '', 'Anlage', 0, 0, NULL, 0, 0, NULL, 19, 0, 0);
INSERT INTO `layer_attributes` (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `arrangement`, `labeling`, `raster_visibility`, `dont_use_for_new`, `mandatory`, `quicksearch`, `order`, `privileg`, `query_tooltip`) VALUES
(25, 'adressat_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$personen_id&operator_personen_id==;Adressaten anzeigen;no_new_window', 'Adressat [Link]', NULL, NULL, NULL, NULL, '', 'Adressat', 0, 0, NULL, -1, 0, NULL, 18, 0, 0),
(18, 'name', 'name', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'wasserrechtliche_zulassungen_ausgangsbescheide', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(25, 'sachbearbeiter_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$sachbearbeiter&operator_personen_id==;Bearbeiter anzeigen;no_new_window', 'Sachbearbeiter [Link]', NULL, NULL, NULL, NULL, '', 'Bearbeiter', 0, 0, NULL, -1, 0, NULL, 16, 0, 0),
(25, 'wrz_ben', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,wrz_id,kennnummer;no_new_window', 'Zugelassene Benutzungen', NULL, NULL, NULL, NULL, '', 'Gewässerbenutzungen', 0, 0, NULL, -1, 0, NULL, 21, 0, 0),
(33, 'wrz_id', 'wasserrechtliche_zulassungen', 'gewaesserbenutzungen', 'b', 'int4', '', '', 0, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.wasserrechtliche_zulassungen;layer_id=25 embedded', 'Wasserrechtliche Zulassung [Auswahlfeld]', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(33, 'lage_link', 'lage_link', '', '', '', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=38&value_id=$lage&operator_id==;Lage anzeigen;no_new_window', 'Lage [Link]', NULL, NULL, NULL, NULL, '', 'Lage', 0, 0, NULL, -1, 0, NULL, 9, 0, 0),
(33, 'anlage', '', 'wasserrechtliche_zulassungen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '2,anlage_id,<b>Name: </b> name;no_new_window', 'Anlage', NULL, NULL, NULL, NULL, '', 'Anlagen', 0, 0, NULL, -1, 0, NULL, 17, 0, 0),
(33, 'aktuell', '', 'wasserrechtliche_zulassungen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, 0, NULL, 14, 0, 0),
(33, 'personen_id', 'adressat', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, 0, NULL, 13, 0, 0),
(2, 'aktuell', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 2, NULL, -1, 0, NULL, 9, 0, 0),
(2, 'historisch', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 2, NULL, -1, 0, NULL, 11, 0, 0),
(33, 'anlage_id', 'anlage', 'wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Anlage ID', NULL, NULL, NULL, NULL, '', 'Anlagen', 0, 0, NULL, -1, 0, NULL, 16, 0, 0),
(33, 'historisch', '', 'wasserrechtliche_zulassungen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, 0, NULL, 15, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `layer_attributes2stelle`
--

CREATE TABLE `layer_attributes2stelle` (
  `layer_id` int(11) NOT NULL,
  `attributename` varchar(255) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `privileg` tinyint(1) NOT NULL,
  `tooltip` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `layer_attributes2stelle`
--

INSERT INTO `layer_attributes2stelle` (`layer_id`, `attributename`, `stelle_id`, `privileg`, `tooltip`) VALUES
(17, 'abkuerzung', 4, 1, 0),
(2, 'kommentar', 1, 1, 0),
(2, 'trinkwasser_koerperschaft', 1, 1, 0),
(2, 'wrz_ben_lage', 4, 1, 0),
(2, 'abwasser_koerperschaft', 4, 1, 0),
(2, 'gewaesserbenutzungen', 1, 1, 0),
(2, 'zustaend_stalu', 1, 1, 0),
(2, 'bearbeiter', 1, 1, 0),
(2, 'objektid_geodin', 1, 1, 0),
(2, 'betreiber', 1, 1, 0),
(2, 'aktuell', 1, 0, 0),
(3, 'name', 1, 1, 0),
(3, 'id', 1, -1, 0),
(10, 'name', 1, 1, 0),
(11, 'name', 1, 1, 0),
(6, 'name', 1, 1, 0),
(6, 'id', 1, -1, 0),
(3, 'id', 4, -1, 0),
(2, 'trinkwasser_koerperschaft', 2, 1, 0),
(9, 'betreiber', 1, 1, 0),
(9, 'bearbeiter', 1, 1, 0),
(9, 'weeerklaerer', 1, 1, 0),
(9, 'telefon', 1, 1, 0),
(9, 'adress_id', 1, 1, 0),
(9, 'typ', 1, 1, 0),
(9, 'wrzadressat', 1, 1, 0),
(22, 'ungueltig_aufgrund', 1, 1, 0),
(22, 'id', 1, -1, 0),
(9, 'email', 2, 1, 0),
(9, 'aktuell', 1, 0, 0),
(9, 'trinkwasser_koerperschaft', 2, 1, 0),
(9, 'email', 1, 1, 0),
(9, 'abkuerzung', 1, 1, 0),
(9, 'wrzaussteller', 1, 1, 0),
(10, 'id', 1, -1, 0),
(21, 'ort', 1, 1, 0),
(25, 'personen_id', 4, 1, 0),
(9, 'zimmer', 1, 1, 0),
(9, 'behoerde', 1, 1, 0),
(9, 'register_amtsgericht', 1, 1, 0),
(9, 'abwasser_koerperschaft', 1, 1, 0),
(9, 'fax', 1, 1, 0),
(9, 'wrzrechtsnachfolger', 1, 1, 0),
(9, 'name', 1, 1, 0),
(9, 'bezeichnung', 1, 1, 0),
(9, 'klasse', 1, 1, 0),
(9, 'status', 1, 1, 0),
(11, 'id', 1, -1, 0),
(12, 'strasse', 1, 1, 0),
(12, 'strasse', 2, 1, 0),
(12, 'plz', 1, 1, 0),
(18, 'id', 1, -1, 0),
(21, 'id', 1, -1, 0),
(13, 'id', 1, -1, 0),
(14, 'id', 1, -1, 0),
(16, 'personenkonto', 1, 1, 0),
(16, 'konto_id', 1, -1, 0),
(16, 'name', 1, 1, 0),
(16, 'iban', 1, 1, 0),
(16, 'bic', 1, 1, 0),
(16, 'verwendungszweck', 1, 1, 0),
(25, 'bergamt_aktenzeichen', 4, 1, 0),
(17, 'status', 1, 1, 0),
(17, 'name', 1, 1, 0),
(18, 'regnummer', 1, 1, 0),
(18, 'klasse', 1, 1, 0),
(18, 'bearbeiterzeichen', 1, 1, 0),
(18, 'aktenzeichen', 1, 1, 0),
(18, 'datum', 1, 1, 0),
(18, 'ort', 1, 1, 0),
(18, 'name', 1, 1, 0),
(19, 'ort', 1, 1, 0),
(19, 'datum', 1, 1, 0),
(19, 'auswahl', 1, 1, 0),
(19, 'aktenzeichen', 1, 1, 0),
(20, 'name', 1, 1, 0),
(21, 'nummer', 1, 1, 0),
(21, 'aktenzeichen', 1, 1, 0),
(21, 'datum_postausgang', 1, 1, 0),
(21, 'datum_bestand_mat', 1, 1, 0),
(21, 'datum_bestand_form', 1, 1, 0),
(46, 'historisch', 2, 1, 0),
(22, 'abgelaufen', 1, 1, 0),
(22, 'wirksam', 1, 0, 0),
(22, 'gueltig_seit', 1, 1, 0),
(22, 'gueltig_bis', 1, 1, 0),
(22, 'ungueltig_seit', 1, 1, 0),
(23, 'name', 1, 1, 0),
(24, 'name', 1, 1, 0),
(24, 'pfad', 1, 1, 0),
(2, 'objektid_geodin', 2, 1, 0),
(25, 'ausstellbehoerde', 1, 1, 0),
(25, 'wrz_id', 1, 0, 0),
(25, 'name', 1, 1, 0),
(25, 'bezeichnung', 1, 0, 0),
(25, 'ausgangsbescheid', 1, 1, 0),
(25, 'fassung', 1, 1, 0),
(25, 'status', 1, 1, 0),
(25, 'adress_id', 1, 1, 0),
(25, 'aenderungsbescheid', 1, 1, 0),
(26, 'name', 1, 1, 0),
(43, 'wrz_id', 2, 0, 0),
(31, 'id', 1, -1, 0),
(31, 'name', 1, 1, 0),
(12, 'hausnummer', 1, 1, 0),
(32, 'name', 1, 1, 0),
(32, 'id', 1, -1, 0),
(12, 'adress_id', 1, -1, 0),
(25, 'adressat_link', 2, 0, 0),
(29, 'name', 1, 1, 0),
(30, 'name', 1, 1, 0),
(30, 'id', 1, -1, 0),
(33, 'aktuell', 1, 0, 0),
(33, 'historisch', 1, 0, 0),
(33, 'lage', 1, 1, 0),
(33, 'lage_link', 1, 0, 0),
(33, 'wrz_id', 1, 1, 0),
(33, 'wasserrechtliche_zulassungen_link', 1, 0, 0),
(34, 'freitext', 1, 1, 0),
(34, 'name', 1, 1, 0),
(13, 'name', 1, 1, 0),
(35, 'id', 1, -1, 0),
(36, 'id', 1, -1, 0),
(36, 'name', 1, 1, 0),
(37, 'max_ent_wb_beschreib', 1, 1, 0),
(37, 'id', 1, -1, 0),
(37, 'name', 1, 1, 0),
(37, 'max_ent_s', 1, 1, 0),
(37, 'max_ent_h', 1, 1, 0),
(37, 'max_ent_d', 1, 1, 0),
(37, 'max_ent_w', 1, 1, 0),
(37, 'max_ent_m', 1, 1, 0),
(37, 'max_ent_a', 1, 1, 0),
(37, 'max_ent_wee', 1, 1, 0),
(37, 'max_ent_wee_beschreib', 1, 1, 0),
(37, 'max_ent_wb', 1, 1, 0),
(38, 'messtischblatt', 1, 1, 0),
(38, 'archivnummer', 1, 1, 0),
(38, 'betriebszustand', 1, 1, 0),
(38, 'filteruk', 1, 1, 0),
(38, 'filterok', 1, 1, 0),
(38, 'baujahr', 1, 1, 0),
(38, 'id', 1, -1, 0),
(38, 'name', 1, 1, 0),
(38, 'betreiber', 1, 1, 0),
(38, 'wwident', 1, 1, 0),
(38, 'namelang', 1, 1, 0),
(46, 'aktuell', 2, 1, 0),
(39, 'name', 1, 1, 0),
(40, 'id', 1, -1, 0),
(41, 'nummer', 1, 1, 0),
(6, 'art', 1, 1, 0),
(42, 'name', 1, 1, 0),
(9, 'personen_id', 1, 0, 0),
(2, 'betreiber', 2, 1, 0),
(2, 'aktuell', 2, 0, 0),
(2, 'aktuelle_wasserrechtliche_zulassungen', 2, 1, 0),
(2, 'historisch', 2, 0, 0),
(2, 'historische_wasserrechtliche_zulassungen', 2, 1, 0),
(2, 'gewaesserbenutzungen', 2, 1, 0),
(2, 'wrz_ben_lage', 2, 1, 0),
(33, 'gruppe_wee', 4, 1, 0),
(33, 'lage', 4, 1, 0),
(33, 'anlage_id', 2, 0, 0),
(33, 'anlage', 2, 0, 0),
(9, 'konto_id', 2, 1, 0),
(9, 'aktuell', 2, 0, 0),
(9, 'per_wrz', 2, 1, 0),
(9, 'zimmer', 2, 1, 0),
(9, 'behoerde', 2, 1, 0),
(9, 'register_amtsgericht', 2, 1, 0),
(9, 'abkuerzung', 2, 1, 0),
(9, 'wrzaussteller', 2, 1, 0),
(9, 'fax', 2, 1, 0),
(9, 'telefon', 2, 1, 0),
(9, 'klasse', 2, 1, 0),
(9, 'status', 2, 1, 0),
(9, 'adress_id', 2, 1, 0),
(9, 'typ', 2, 1, 0),
(9, 'wrzadressat', 2, 1, 0),
(9, 'wrzrechtsnachfolger', 2, 1, 0),
(43, 'wasserrechtliche_zulassung', 2, 0, 0),
(43, 'anlage', 2, 0, 0),
(38, 'betriebszustand', 2, 1, 0),
(38, 'bohrungsname', 2, 1, 0),
(38, 'baujahr', 2, 1, 0),
(38, 'endteufe', 2, 1, 0),
(38, 'filterok', 2, 1, 0),
(38, 'filteruk', 2, 1, 0),
(38, 'namelang', 2, 1, 0),
(38, 'wwident', 2, 1, 0),
(38, 'id', 2, -1, 0),
(38, 'namekurz', 2, 1, 0),
(46, 'anlage_id', 2, 1, 0),
(2, 'the_geom', 2, 1, 0),
(25, 'wrz_ben', 2, 0, 0),
(25, 'sachbearbeiter', 2, 1, 0),
(25, 'sachbearbeiter_link', 2, 0, 0),
(25, 'ausstellbehoerde_link', 2, 0, 0),
(25, 'wrz_id', 2, 0, 0),
(25, 'name', 2, 1, 0),
(25, 'bezeichnung', 2, 0, 0),
(2, 'zustaend_stalu', 2, 1, 0),
(2, 'bearbeiter', 2, 1, 0),
(3, 'id', 2, -1, 0),
(33, 'anlage_id', 1, 0, 0),
(12, 'adress_id', 2, -1, 0),
(12, 'hausnummer', 4, 1, 0),
(12, 'adress_id', 4, -1, 0),
(12, 'hausnummer', 2, 1, 0),
(12, 'strasse', 4, 1, 0),
(12, 'ort', 1, 1, 0),
(12, 'plz', 2, 1, 0),
(12, 'ort', 2, 1, 0),
(12, 'plz', 4, 1, 0),
(12, 'ort', 4, 1, 0),
(23, 'id', 2, -1, 0),
(23, 'id', 4, -1, 0),
(23, 'id', 1, -1, 0),
(23, 'name', 2, 1, 0),
(23, 'name', 4, 1, 0),
(25, 'personen_id', 2, 1, 0),
(2, 'zustaend_uwb', 4, 1, 0),
(2, 'zustaend_stalu', 4, 1, 0),
(2, 'bearbeiter', 4, 1, 0),
(2, 'objektid_geodin', 4, 1, 0),
(2, 'betreiber', 4, 1, 0),
(2, 'aktuell', 4, 0, 0),
(2, 'aktuelle_wasserrechtliche_zulassungen', 4, 1, 0),
(2, 'historisch', 4, 0, 0),
(2, 'historische_wasserrechtliche_zulassungen', 4, 1, 0),
(2, 'anlage_id', 4, 0, 0),
(2, 'name', 4, 1, 0),
(2, 'klasse', 4, 1, 0),
(3, 'name', 4, 1, 0),
(41, 'id', 2, -1, 0),
(41, 'id', 4, -1, 0),
(41, 'nummer', 2, 1, 0),
(41, 'nummer', 4, 1, 0),
(41, 'id', 1, -1, 0),
(3, 'name', 2, 1, 0),
(2, 'aktuelle_wasserrechtliche_zulassungen', 1, 1, 0),
(17, 'status', 2, 1, 0),
(17, 'name', 2, 1, 0),
(17, 'status', 4, 1, 0),
(17, 'name', 4, 1, 0),
(17, 'id', 1, 0, 0),
(17, 'id', 2, 0, 0),
(17, 'id', 4, 0, 0),
(25, 'anlage_id', 1, 1, 0),
(39, 'id', 2, -1, 0),
(39, 'id', 4, -1, 0),
(39, 'id', 1, -1, 0),
(39, 'name', 2, 1, 0),
(39, 'name', 4, 1, 0),
(24, 'name', 2, 1, 0),
(24, 'id', 2, -1, 0),
(24, 'name', 4, 1, 0),
(24, 'id', 4, -1, 0),
(24, 'id', 1, -1, 0),
(24, 'document', 1, 1, 0),
(24, 'pfad', 2, 1, 0),
(24, 'document', 2, 1, 0),
(24, 'pfad', 4, 1, 0),
(24, 'document', 4, 1, 0),
(33, 'wrz_id', 4, 1, 0),
(33, 'wasserrechtliche_zulassungen_link', 4, 0, 0),
(33, 'anlage', 4, 0, 0),
(33, 'anlage_id', 4, 0, 0),
(33, 'aktuell', 2, 0, 0),
(33, 'wrz_ben_lage_namelang', 2, 0, 0),
(33, 'bezeichnung', 4, 0, 0),
(33, 'art', 4, 1, 0),
(33, 'wasserbuch', 4, 1, 0),
(33, 'zweck', 4, 1, 0),
(33, 'umfang', 4, 1, 0),
(34, 'freitext', 2, 1, 0),
(34, 'name', 2, 1, 0),
(34, 'freitext', 4, 1, 0),
(34, 'name', 4, 1, 0),
(34, 'id', 1, -1, 0),
(34, 'id', 2, -1, 0),
(34, 'id', 4, -1, 0),
(34, 'wgh', 1, 1, 0),
(34, 'wgh', 2, 1, 0),
(34, 'wgh', 4, 1, 0),
(38, 'betriebszustand', 4, 1, 0),
(38, 'bohrungsname', 4, 1, 0),
(38, 'baujahr', 4, 1, 0),
(38, 'endteufe', 4, 1, 0),
(38, 'filterok', 4, 1, 0),
(38, 'filteruk', 4, 1, 0),
(38, 'namelang', 4, 1, 0),
(38, 'wwident', 4, 1, 0),
(38, 'id', 4, -1, 0),
(38, 'namekurz', 4, 1, 0),
(38, 'schichtenverzeichnis', 1, 1, 0),
(38, 'endteufe', 1, 1, 0),
(38, 'bohrungsname', 1, 1, 0),
(38, 'namekurz', 1, 1, 0),
(38, 'betreiber', 2, 1, 0),
(38, 'name', 2, 1, 0),
(38, 'betreiber', 4, 1, 0),
(38, 'name', 4, 1, 0),
(38, 'invid', 1, 1, 0),
(38, 'the_geo', 1, 1, 0),
(38, 'messtischblatt', 2, 1, 0),
(38, 'archivnummer', 2, 1, 0),
(38, 'schichtenverzeichnis', 2, 1, 0),
(38, 'invid', 2, 1, 0),
(38, 'the_geo', 2, 1, 0),
(38, 'messtischblatt', 4, 1, 0),
(38, 'archivnummer', 4, 1, 0),
(38, 'schichtenverzeichnis', 4, 1, 0),
(38, 'invid', 4, 1, 0),
(38, 'the_geo', 4, 1, 0),
(37, 'max_ent_wee_beschreib', 2, 1, 0),
(37, 'name', 2, 1, 0),
(37, 'max_ent_s', 2, 1, 0),
(37, 'max_ent_h', 2, 1, 0),
(37, 'max_ent_d', 2, 1, 0),
(37, 'max_ent_w', 2, 1, 0),
(37, 'max_ent_m', 2, 1, 0),
(37, 'max_ent_a', 2, 1, 0),
(37, 'id', 2, -1, 0),
(37, 'max_ent_wee_beschreib', 4, 1, 0),
(37, 'name', 4, 1, 0),
(37, 'max_ent_s', 4, 1, 0),
(37, 'max_ent_h', 4, 1, 0),
(37, 'max_ent_d', 4, 1, 0),
(37, 'max_ent_w', 4, 1, 0),
(37, 'max_ent_m', 4, 1, 0),
(37, 'max_ent_a', 4, 1, 0),
(37, 'id', 4, -1, 0),
(37, 'max_ent_frei', 1, 1, 0),
(37, 'max_ent_frei_beschreib', 1, 1, 0),
(37, 'max_ent_wee', 2, 1, 0),
(37, 'max_ent_wb', 2, 1, 0),
(37, 'max_ent_wee', 4, 1, 0),
(37, 'max_ent_wb', 4, 1, 0),
(37, 'freitext', 1, 1, 0),
(37, 'max_ent_wb_beschreib', 2, 1, 0),
(37, 'max_ent_frei', 2, 1, 0),
(37, 'max_ent_frei_beschreib', 2, 1, 0),
(37, 'freitext', 2, 1, 0),
(37, 'max_ent_wb_beschreib', 4, 1, 0),
(37, 'max_ent_frei', 4, 1, 0),
(37, 'max_ent_frei_beschreib', 4, 1, 0),
(37, 'freitext', 4, 1, 0),
(36, 'name', 2, 1, 0),
(36, 'id', 2, -1, 0),
(36, 'name', 4, 1, 0),
(36, 'id', 4, -1, 0),
(36, 'freitext', 1, 1, 0),
(36, 'freitext', 2, 1, 0),
(36, 'freitext', 4, 1, 0),
(16, 'bic', 2, 1, 0),
(16, 'konto_id', 2, -1, 0),
(16, 'name', 2, 1, 0),
(16, 'verwendungszweck', 2, 1, 0),
(16, 'bic', 4, 1, 0),
(16, 'konto_id', 4, -1, 0),
(16, 'name', 4, 1, 0),
(16, 'verwendungszweck', 4, 1, 0),
(16, 'kassenzeichen', 1, 1, 0),
(16, 'iban', 2, 1, 0),
(16, 'iban', 4, 1, 0),
(16, 'personenkonto', 2, 1, 0),
(16, 'kassenzeichen', 2, 1, 0),
(16, 'personenkonto', 4, 1, 0),
(16, 'kassenzeichen', 4, 1, 0),
(6, 'name', 2, 1, 0),
(6, 'id', 2, -1, 0),
(6, 'name', 4, 1, 0),
(6, 'id', 4, -1, 0),
(6, 'art', 2, 1, 0),
(6, 'art', 4, 1, 0),
(42, 'id', 2, -1, 0),
(42, 'id', 4, -1, 0),
(42, 'id', 1, -1, 0),
(42, 'name', 2, 1, 0),
(42, 'name', 4, 1, 0),
(40, 'id', 4, -1, 0),
(40, 'id', 2, -1, 0),
(40, 'nummer', 1, 1, 0),
(40, 'nummer', 2, 1, 0),
(40, 'nummer', 4, 1, 0),
(30, 'the_geo', 2, 1, 0),
(30, 'name', 2, 1, 0),
(30, 'id', 2, -1, 0),
(30, 'the_geo', 4, 1, 0),
(30, 'name', 4, 1, 0),
(30, 'id', 4, -1, 0),
(30, 'the_geo', 1, 1, 0),
(9, 'konto_id', 4, 1, 0),
(9, 'aktuell', 4, 0, 0),
(9, 'per_wrz', 4, 1, 0),
(9, 'wrzrechtsnachfolger', 4, 1, 0),
(9, 'betreiber', 4, 1, 0),
(9, 'bearbeiter', 4, 1, 0),
(9, 'weeerklaerer', 4, 1, 0),
(9, 'telefon', 4, 1, 0),
(9, 'fax', 4, 1, 0),
(9, 'email', 4, 1, 0),
(9, 'abkuerzung', 4, 1, 0),
(9, 'wrzaussteller', 4, 1, 0),
(9, 'abwasser_koerperschaft', 4, 1, 0),
(9, 'klasse', 4, 1, 0),
(9, 'status', 4, 1, 0),
(9, 'adress_id', 4, 1, 0),
(9, 'typ', 4, 1, 0),
(9, 'wrzadressat', 4, 1, 0),
(9, 'the_geo', 2, -1, 0),
(9, 'betreiber', 2, 1, 0),
(9, 'bearbeiter', 2, 1, 0),
(9, 'weeerklaerer', 2, 1, 0),
(9, 'trinkwasser_koerperschaft', 1, 1, 0),
(9, 'register_nummer', 4, 1, 0),
(9, 'trinkwasser_koerperschaft', 4, 1, 0),
(9, 'kommentar', 4, 1, 0),
(9, 'personen_id', 4, 0, 0),
(9, 'name', 4, 1, 0),
(9, 'bezeichnung', 4, 1, 0),
(10, 'id', 2, -1, 0),
(10, 'id', 4, -1, 0),
(10, 'name', 2, 1, 0),
(10, 'name', 4, 1, 0),
(11, 'id', 2, -1, 0),
(11, 'id', 4, -1, 0),
(11, 'name', 2, 1, 0),
(11, 'name', 4, 1, 0),
(35, 'nummer', 2, 1, 0),
(35, 'nummer', 4, 1, 0),
(35, 'id', 2, -1, 0),
(35, 'id', 4, -1, 0),
(35, 'nummer', 1, 1, 0),
(43, 'wasserrechtliche_zulassung', 4, 0, 0),
(43, 'anlage', 4, 0, 0),
(43, 'benutzungsnummer', 2, 0, 0),
(43, 'wrz_id', 4, 0, 0),
(43, 'benutzungsnummer', 4, 0, 0),
(17, 'abkuerzung', 1, 1, 0),
(25, 'anlage_id', 4, 1, 0),
(25, 'anlage_anzeige', 4, 1, 0),
(25, 'adressat_link', 4, 0, 0),
(25, 'ausstellbehoerde', 4, 1, 0),
(25, 'wrz_id', 4, 0, 0),
(25, 'name', 4, 1, 0),
(25, 'bezeichnung', 4, 0, 0),
(25, 'ausgangsbescheid', 4, 1, 0),
(25, 'fassung', 4, 1, 0),
(25, 'status', 4, 1, 0),
(25, 'adress_id', 4, 1, 0),
(25, 'aenderungsbescheid', 4, 1, 0),
(25, 'gueltigkeit', 4, 1, 0),
(25, 'aktuell', 4, 1, 0),
(25, 'historisch', 4, 1, 0),
(21, 'aktenzeichen', 2, 1, 0),
(21, 'datum_bestand_mat', 2, 1, 0),
(21, 'bearbeiterzeichen', 2, 1, 0),
(21, 'aktenzeichen', 4, 1, 0),
(21, 'datum_bestand_mat', 4, 1, 0),
(21, 'bearbeiterzeichen', 4, 1, 0),
(21, 'bearbeiterzeichen', 1, 1, 0),
(21, 'datum_postausgang', 2, 1, 0),
(21, 'id', 2, -1, 0),
(21, 'datum_postausgang', 4, 1, 0),
(21, 'id', 4, -1, 0),
(21, 'datum_bestand_form', 2, 1, 0),
(21, 'ort', 2, 1, 0),
(21, 'nummer', 2, 1, 0),
(21, 'datum_bestand_form', 4, 1, 0),
(21, 'ort', 4, 1, 0),
(21, 'nummer', 4, 1, 0),
(18, 'ausstellbehoerde', 2, 1, 0),
(18, 'klasse', 2, 1, 0),
(18, 'bearbeiterzeichen', 2, 1, 0),
(18, 'aktenzeichen', 2, 1, 0),
(18, 'datum', 2, 1, 0),
(25, 'dokument', 1, 1, 0),
(18, 'datum', 4, 1, 0),
(18, 'ort', 4, 1, 0),
(18, 'regnummer', 4, 1, 0),
(18, 'ausstellbehoerde', 4, 1, 0),
(18, 'id', 2, -1, 0),
(18, 'name', 2, 1, 0),
(18, 'id', 4, -1, 0),
(18, 'name', 4, 1, 0),
(18, 'klasse', 4, 1, 0),
(18, 'bearbeiterzeichen', 4, 1, 0),
(18, 'aktenzeichen', 4, 1, 0),
(26, 'id', 2, -1, 0),
(26, 'id', 4, -1, 0),
(26, 'id', 1, -1, 0),
(26, 'name', 2, 1, 0),
(26, 'name', 4, 1, 0),
(29, 'id', 2, -1, 0),
(29, 'id', 4, -1, 0),
(29, 'id', 1, -1, 0),
(29, 'name', 2, 1, 0),
(29, 'name', 4, 1, 0),
(19, 'auswahl', 2, 1, 0),
(19, 'klasse', 2, 1, 0),
(19, 'aktenzeichen', 2, 1, 0),
(19, 'auswahl', 4, 1, 0),
(19, 'klasse', 4, 1, 0),
(19, 'aktenzeichen', 4, 1, 0),
(19, 'klasse', 1, 1, 0),
(19, 'id', 1, -1, 0),
(19, 'id', 2, -1, 0),
(19, 'id', 4, -1, 0),
(19, 'nummer', 1, 1, 0),
(19, 'datum', 2, 1, 0),
(19, 'ort', 2, 1, 0),
(19, 'nummer', 2, 1, 0),
(19, 'datum', 4, 1, 0),
(19, 'ort', 4, 1, 0),
(19, 'nummer', 4, 1, 0),
(31, 'id', 2, -1, 0),
(31, 'id', 4, -1, 0),
(31, 'name', 2, 1, 0),
(31, 'name', 4, 1, 0),
(22, 'ungueltig_seit', 2, 1, 0),
(22, 'gueltig_seit', 2, 1, 0),
(22, 'gueltig_bis', 2, 1, 0),
(22, 'ungueltig_aufgrund', 2, 1, 0),
(22, 'ungueltig_seit', 4, 1, 0),
(22, 'gueltig_seit', 4, 1, 0),
(22, 'gueltig_bis', 4, 1, 0),
(22, 'ungueltig_aufgrund', 4, 1, 0),
(22, 'id', 2, -1, 0),
(22, 'id', 4, -1, 0),
(22, 'wirksam', 2, 0, 0),
(22, 'abgelaufen', 2, 1, 0),
(22, 'wirksam', 4, 0, 0),
(22, 'abgelaufen', 4, 1, 0),
(32, 'id', 2, -1, 0),
(32, 'id', 4, -1, 0),
(32, 'name', 2, 1, 0),
(32, 'name', 4, 1, 0),
(20, 'id', 2, -1, 0),
(20, 'id', 4, -1, 0),
(20, 'id', 1, -1, 0),
(20, 'name', 2, 1, 0),
(20, 'name', 4, 1, 0),
(14, 'id', 2, -1, 0),
(14, 'id', 4, -1, 0),
(14, 'name', 1, 1, 0),
(14, 'name', 2, 1, 0),
(14, 'name', 4, 1, 0),
(15, 'id', 2, -1, 0),
(15, 'id', 4, -1, 0),
(25, 'ausstellbehoerde', 2, 1, 0),
(15, 'name', 2, 1, 0),
(15, 'name', 4, 1, 0),
(2, 'wrz_ben_lage', 1, 1, 0),
(2, 'abwasser_koerperschaft', 1, 1, 0),
(17, 'abkuerzung', 2, 1, 0),
(2, 'zustaend_uwb', 2, 1, 0),
(2, 'anlage_id', 2, 0, 0),
(25, 'personen_id', 1, 1, 0),
(25, 'adressat_link', 1, 0, 0),
(25, 'dokument', 2, 1, 0),
(25, 'sachbearbeiter_link', 4, 0, 0),
(25, 'status', 2, 1, 0),
(25, 'wrz_ben', 4, 0, 0),
(25, 'dokument', 4, 1, 0),
(25, 'anlage_id', 2, 1, 0),
(44, 'adressat', 2, 1, 0),
(44, 'ausgangsbescheid', 2, 1, 0),
(44, 'fassung', 2, 1, 0),
(44, 'status', 2, 1, 0),
(44, 'adress_id', 2, 1, 0),
(44, 'aenderungsbescheid', 2, 1, 0),
(44, 'gueltigkeit', 2, 1, 0),
(44, 'aktuell', 2, 1, 0),
(44, 'name', 2, 1, 0),
(44, 'wrz_id', 2, 0, 0),
(44, 'adressat', 4, 1, 0),
(44, 'ausgangsbescheid', 4, 1, 0),
(44, 'fassung', 4, 1, 0),
(44, 'status', 4, 1, 0),
(44, 'adress_id', 4, 1, 0),
(44, 'aenderungsbescheid', 4, 1, 0),
(44, 'gueltigkeit', 4, 1, 0),
(44, 'aktuell', 4, 1, 0),
(44, 'name', 4, 1, 0),
(44, 'wrz_id', 4, 0, 0),
(44, 'historisch', 2, 1, 0),
(44, 'bergamt_aktenzeichen', 2, 1, 0),
(44, 'ausstellbehoerde', 2, 1, 0),
(44, 'historisch', 4, 1, 0),
(44, 'bergamt_aktenzeichen', 4, 1, 0),
(44, 'ausstellbehoerde', 4, 1, 0),
(44, 'dokument', 2, 1, 0),
(44, 'dokument', 4, 1, 0),
(44, 'bezeichnung', 2, 1, 0),
(44, 'bezeichnung', 4, 1, 0),
(48, 'dokument', 2, 1, 0),
(44, 'sachbearbeiter', 2, 1, 0),
(44, 'sachbearbeiter', 4, 1, 0),
(44, 'anlage_id', 2, 1, 0),
(44, 'anlage_id', 4, 1, 0),
(9, 'per_wrz', 1, 1, 0),
(2, 'the_geom', 4, 1, 0),
(25, 'sachbearbeiter_link', 1, 0, 0),
(2, 'kommentar', 2, 1, 0),
(45, 'gruppe_wee', 2, 1, 0),
(45, 'gewaesserbenutzungen_id', 2, 1, 0),
(45, 'zweck', 4, 1, 0),
(45, 'gewaesserbenutzungen_id', 4, 1, 0),
(45, 'kennnummer', 2, 1, 0),
(45, 'art', 2, 1, 0),
(45, 'kennnummer', 4, 1, 0),
(45, 'art', 4, 1, 0),
(45, 'personen_id', 2, 0, 0),
(45, 'personen_id', 4, 0, 0),
(46, 'name', 2, 1, 0),
(46, 'wrz_id', 2, 0, 0),
(46, 'anlage_id', 4, 1, 0),
(46, 'bezeichnung', 4, 1, 0),
(46, 'name', 4, 1, 0),
(46, 'wrz_id', 4, 0, 0),
(46, 'bezeichnung', 2, 1, 0),
(46, 'aktuell', 4, 1, 0),
(46, 'historisch', 4, 1, 0),
(45, 'wasserbuch', 2, 1, 0),
(45, 'wasserbuch', 4, 1, 0),
(2, 'trinkwasser_koerperschaft', 4, 1, 0),
(2, 'abwasser_koerperschaft', 2, 1, 0),
(2, 'name', 2, 1, 0),
(2, 'klasse', 2, 1, 0),
(2, 'kommentar', 4, 1, 0),
(47, 'adress_id', 2, 1, 0),
(47, 'aenderungsbescheid', 2, 1, 0),
(47, 'wrz_id', 2, 1, 0),
(47, 'aenderungsbescheid', 4, 1, 0),
(47, 'gueltigkeit', 4, 1, 0),
(47, 'wrz_id', 4, 1, 0),
(47, 'anlage_id', 2, 1, 0),
(47, 'aktuell', 2, 1, 0),
(47, 'status', 4, 1, 0),
(47, 'historisch', 2, 1, 0),
(47, 'bergamt_aktenzeichen', 2, 1, 0),
(47, 'dokument', 2, 1, 0),
(47, 'gueltigkeit', 2, 1, 0),
(47, 'name', 2, 1, 0),
(47, 'bezeichnung', 2, 1, 0),
(47, 'ausstellbehoerde', 2, 1, 0),
(47, 'sachbearbeiter', 4, 1, 0),
(47, 'personen_id', 4, 1, 0),
(47, 'anlage_id', 4, 1, 0),
(47, 'aktuell', 4, 1, 0),
(47, 'name', 4, 1, 0),
(47, 'bezeichnung', 4, 1, 0),
(47, 'ausstellbehoerde', 4, 1, 0),
(47, 'status', 2, 1, 0),
(47, 'ausgangsbescheid', 2, 1, 0),
(47, 'fassung', 2, 1, 0),
(47, 'adress_id', 4, 1, 0),
(47, 'ausgangsbescheid', 4, 1, 0),
(47, 'fassung', 4, 1, 0),
(45, 'umfang', 2, 1, 0),
(48, 'adressat', 2, 1, 0),
(48, 'aenderungsbescheid', 2, 1, 0),
(48, 'gueltigkeit', 2, 1, 0),
(48, 'aktuell', 2, 1, 0),
(48, 'historisch', 2, 1, 0),
(48, 'bergamt_aktenzeichen', 2, 1, 0),
(48, 'name', 2, 1, 0),
(48, 'bezeichnung', 2, 1, 0),
(48, 'wrz_id', 2, 1, 0),
(48, 'aktuell', 4, 1, 0),
(48, 'fassung', 4, 1, 0),
(48, 'status', 4, 1, 0),
(48, 'adress_id', 4, 1, 0),
(48, 'aenderungsbescheid', 4, 1, 0),
(48, 'gueltigkeit', 4, 1, 0),
(48, 'name', 4, 1, 0),
(48, 'bezeichnung', 4, 1, 0),
(48, 'ausstellbehoerde', 4, 1, 0),
(48, 'wrz_id', 4, 1, 0),
(48, 'ausstellbehoerde', 2, 1, 0),
(48, 'ausgangsbescheid', 2, 1, 0),
(48, 'fassung', 2, 1, 0),
(48, 'sachbearbeiter', 4, 1, 0),
(48, 'historisch', 4, 1, 0),
(48, 'bergamt_aktenzeichen', 4, 1, 0),
(48, 'status', 2, 1, 0),
(48, 'adress_id', 2, 1, 0),
(48, 'dokument', 4, 1, 0),
(48, 'ausgangsbescheid', 4, 1, 0),
(48, 'sachbearbeiter', 2, 1, 0),
(48, 'anlage_id', 4, 1, 0),
(47, 'personen_id', 2, 1, 0),
(47, 'sachbearbeiter', 2, 1, 0),
(47, 'historisch', 4, 1, 0),
(47, 'bergamt_aktenzeichen', 4, 1, 0),
(47, 'dokument', 4, 1, 0),
(48, 'anlage_id', 2, 1, 0),
(48, 'adressat', 4, 1, 0),
(25, 'gueltigkeit', 1, 1, 0),
(25, 'aktuell', 1, 1, 0),
(25, 'historisch', 1, 1, 0),
(25, 'bergamt_aktenzeichen', 1, 1, 0),
(25, 'fassung', 2, 1, 0),
(45, 'wrz_ben_lage_namelang', 2, 1, 0),
(45, 'wrz_id', 2, 1, 0),
(45, 'wasserrechtliche_zulassungen', 2, 1, 0),
(45, 'wrz_ben_lage_namelang', 4, 1, 0),
(45, 'wrz_id', 4, 1, 0),
(45, 'wasserrechtliche_zulassungen', 4, 1, 0),
(45, 'bezeichnung', 2, 1, 0),
(45, 'bezeichnung', 4, 1, 0),
(45, 'lage', 2, 1, 0),
(45, 'lage', 4, 1, 0),
(9, 'per_wrz_ben', 2, 1, 0),
(9, 'personen_id', 2, 0, 0),
(9, 'name', 2, 1, 0),
(9, 'bezeichnung', 2, 1, 0),
(9, 'the_geo', 4, -1, 0),
(9, 'per_wrz_ben', 4, 1, 0),
(9, 'kommentar', 1, 1, 0),
(9, 'kommentar', 2, 1, 0),
(9, 'abwasser_koerperschaft', 2, 1, 0),
(9, 'register_amtsgericht', 4, 1, 0),
(9, 'zimmer', 4, 1, 0),
(9, 'per_wrz_ben', 1, 1, 0),
(9, 'the_geo', 1, -1, 0),
(9, 'register_nummer', 1, 1, 0),
(9, 'konto_id', 1, 1, 0),
(9, 'register_nummer', 2, 1, 0),
(9, 'behoerde', 4, 1, 0),
(45, 'gruppe_wee', 4, 1, 0),
(45, 'zweck', 2, 1, 0),
(45, 'umfang', 4, 1, 0),
(25, 'ausstellbehoerde_link', 4, 0, 0),
(25, 'sachbearbeiter', 4, 1, 0),
(25, 'anlage_anzeige', 1, 1, 0),
(25, 'sachbearbeiter', 1, 1, 0),
(25, 'adress_id', 2, 1, 0),
(25, 'aenderungsbescheid', 2, 1, 0),
(25, 'gueltigkeit', 2, 1, 0),
(25, 'aktuell', 2, 1, 0),
(25, 'historisch', 2, 1, 0),
(25, 'bergamt_aktenzeichen', 2, 1, 0),
(18, 'ausstellbehoerde', 1, 1, 0),
(18, 'ort', 2, 1, 0),
(18, 'regnummer', 2, 1, 0),
(25, 'ausstellbehoerde_link', 1, 0, 0),
(25, 'anlage_anzeige', 2, 1, 0),
(2, 'historisch', 1, 0, 0),
(25, 'wrz_ben', 1, 0, 0),
(25, 'ausgangsbescheid', 2, 1, 0),
(33, 'aktuell', 4, 0, 0),
(33, 'wrz_ben_lage_namelang', 4, 0, 0),
(33, 'personen_id', 4, 0, 0),
(33, 'lage_link', 4, 0, 0),
(33, 'id', 4, 0, 0),
(33, 'kennnummer', 4, 1, 0),
(33, 'anlage', 1, 0, 0),
(33, 'wrz_ben_lage_namelang', 1, 0, 0),
(33, 'personen_id', 1, 0, 0),
(33, 'kennnummer', 1, 1, 0),
(33, 'bezeichnung', 1, 0, 0),
(33, 'art', 1, 1, 0),
(33, 'wasserbuch', 1, 1, 0),
(33, 'zweck', 1, 1, 0),
(33, 'umfang', 1, 1, 0),
(33, 'gruppe_wee', 1, 1, 0),
(33, 'id', 1, 0, 0),
(2, 'gewaesserbenutzungen', 4, 1, 0),
(2, 'the_geom', 1, 1, 0),
(2, 'historische_wasserrechtliche_zulassungen', 1, 1, 0),
(2, 'anlage_id', 1, 0, 0),
(2, 'name', 1, 1, 0),
(2, 'klasse', 1, 1, 0),
(2, 'zustaend_uwb', 1, 1, 0),
(49, 'wrz_id', 2, 0, 0),
(49, 'anlage_id', 2, 0, 0),
(49, 'anlage_name', 2, 0, 0),
(49, 'anlage', 2, 1, 0),
(49, 'wrz_id', 4, 0, 0),
(49, 'anlage_id', 4, 0, 0),
(49, 'anlage_name', 4, 0, 0),
(49, 'anlage', 4, 1, 0),
(33, 'historisch', 4, 0, 0),
(33, 'historisch', 2, 0, 0),
(33, 'personen_id', 2, 0, 0),
(33, 'art', 2, 1, 0),
(33, 'wasserbuch', 2, 1, 0),
(33, 'zweck', 2, 1, 0),
(33, 'umfang', 2, 1, 0),
(33, 'gruppe_wee', 2, 1, 0),
(33, 'lage', 2, 1, 0),
(33, 'lage_link', 2, 0, 0),
(33, 'wrz_id', 2, 1, 0),
(33, 'wasserrechtliche_zulassungen_link', 2, 0, 0),
(33, 'id', 2, 0, 0),
(33, 'kennnummer', 2, 1, 0),
(33, 'bezeichnung', 2, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `layer_parameter`
--

CREATE TABLE `layer_parameter` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `default_value` varchar(255) NOT NULL,
  `options_sql` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `component` varchar(50) NOT NULL,
  `type` enum('mysql','postgresql') NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`component`, `type`, `filename`) VALUES
('kvwmap', 'mysql', '2014-09-12_16-33-22_Version2.0.sql'),
('kvwmap', 'mysql', '2014-11-07_11-37-59_layer_attributes_Autovervollstaendigungsfeld.sql'),
('kvwmap', 'mysql', '2014-11-24_10-29-21_druckrahmen_lage_gemeinde_flurst.sql'),
('kvwmap', 'mysql', '2014-11-27_11-16-24_druckrahmen_scalebar.sql'),
('kvwmap', 'mysql', '2014-12-03_10-25-40_zwischenablage.sql'),
('kvwmap', 'mysql', '2015-01-14_16-44-46_styles_initialgap_opacity.sql'),
('kvwmap', 'mysql', '2015-02-20_11-11-00_rolle_instant_reload_menu_auto_close.sql'),
('kvwmap', 'mysql', '2015-03-12_15-03-13_styles_colorrange_datarange.sql'),
('kvwmap', 'mysql', '2015-03-16_15-15-35_rollenlayer_query.sql'),
('kvwmap', 'mysql', '2015-03-17_10-29-08_rollenlayer_queryStatus.sql'),
('kvwmap', 'mysql', '2015-03-27_11-36-34_rollenlayer_Data_longtext.sql'),
('kvwmap', 'mysql', '2015-05-05_13-53-32_u_polygon2used_layer_loeschen.sql'),
('kvwmap', 'mysql', '2015-05-07_11-29-06_search_attributes2rolle_PK.sql'),
('kvwmap', 'mysql', '2015-05-08_14-03-33_rolle_last_query_sql_longtext.sql'),
('kvwmap', 'mysql', '2015-05-22_14-34-59_layer_postlabelcache.sql'),
('kvwmap', 'mysql', '2015-05-28_11-08-55_rolle_auto_map_resize.sql'),
('kvwmap', 'mysql', '2015-06-16_16-16-14_u_rolle2usedlayer_gle_view.sql'),
('kvwmap', 'mysql', '2015-06-23_16-29-07_layer_requires.sql'),
('kvwmap', 'mysql', '2015-08-05_14-02-01_rolle_saved_layers.sql'),
('kvwmap', 'mysql', '2015-09-09_15-10-25_rolle_saved_layers_query.sql'),
('kvwmap', 'mysql', '2015-10-07_09-19-25_layer_attributes_StelleID.sql'),
('kvwmap', 'mysql', '2015-10-29_13-50-26_u_consumeALB_format.sql'),
('kvwmap', 'mysql', '2015-12-08_12-01-43_user_loginname.sql'),
('kvwmap', 'mysql', '2016-01-07_11-26-53_datendrucklayouts_gap.sql'),
('kvwmap', 'mysql', '2016-01-18_11-43-24_u_consume_epsg_code.sql'),
('kvwmap', 'mysql', '2016-01-26_14-04-28_druckrahmen_dhk_call.sql'),
('kvwmap', 'mysql', '2016-02-09_15-34-14_layer_cluster_maxdistance.sql'),
('kvwmap', 'mysql', '2016-02-17_13-55-03_rolle_coord_query.sql'),
('kvwmap', 'mysql', '2016-04-21_10-42-03_stelle_gemeinden_gemarkung_flur.sql'),
('kvwmap', 'mysql', '2016-04-28_16-35-05_add_layer_params.sql'),
('kvwmap', 'mysql', '2016-05-31_11-22-56_stelle_hist_timestamp.sql'),
('kvwmap', 'mysql', '2016-05-31_13-18-54_stelle_wasserzeichen.sql'),
('kvwmap', 'mysql', '2016-06-22_16-24-48_layer_attributes_arrangement_labeling.sql'),
('kvwmap', 'mysql', '2016-06-29_10-50-56_layer_maintable_is_view.sql'),
('kvwmap', 'mysql', '2016-06-30_10-54-02_u_rolle2used_layer_transparency.sql'),
('kvwmap', 'mysql', '2016-07-12_15-46-25_used_layer_use_geom.sql'),
('kvwmap', 'mysql', '2016-07-29_14-24-25_add_visually_impaired_to_rolle.sql'),
('kvwmap', 'mysql', '2016-08-17_13-57-20_add_datatypes_and_datatype_attributes.sql'),
('kvwmap', 'mysql', '2016-08-29_11-45-00_datatypes_dbname_host_port.sql'),
('kvwmap', 'mysql', '2016-08-30_14-11-10_add_trigger_function_to_layer.sql'),
('kvwmap', 'mysql', '2016-10-05_11-09-37_styles_symbolname.sql'),
('kvwmap', 'mysql', '2016-10-05_12-11-57_styles_rangeitem.sql'),
('kvwmap', 'mysql', '2016-11-22_15-23-00_set_relative_fonts_pfade.sql'),
('kvwmap', 'mysql', '2016-11-23_11-47-01_layer_classification.sql'),
('kvwmap', 'mysql', '2016-11-24_10-56-43_styles_minscale_maxscale.sql'),
('kvwmap', 'mysql', '2017-01-10_13-46-46_cronjobs.sql'),
('kvwmap', 'mysql', '2017-01-12_13-19-18_datendrucklayouts_no_record_splitting.sql'),
('kvwmap', 'mysql', '2017-01-23_13:47:15_add_aktiv_to_cronjobs.sql'),
('kvwmap', 'mysql', '2017-02-02_15-57-57_add_db_to_cronjobs.sql'),
('kvwmap', 'mysql', '2017-03-24_14-56-40_add_custom_legend_graphic_and_order.sql'),
('kvwmap', 'mysql', '2017-03-29_16-48-03_add_title_to_menues.sql'),
('kvwmap', 'mysql', '2017-04-05_16-14-15_add_default_rolle_to_stelle.sql'),
('kvwmap', 'mysql', '2017-04-06_09-59-50_add_showmapfunctions_to_rolle.sql'),
('kvwmap', 'mysql', '2017-04-07_12-17-10_add_showlayeroptions.sql'),
('kvwmap', 'mysql', '2017-04-13_17-00-45_add_onclick_on_menues.sql'),
('kvwmap', 'mysql', '2017-04-20_15-17-29_u_menues_button_class.sql'),
('kvwmap', 'mysql', '2017-04-20_15-59-03_rolle_menue_buttons.sql'),
('kvwmap', 'mysql', '2017-04-21_10-49-49_u_menues_logout.sql'),
('kvwmap', 'mysql', '2017-06-07_16-01-20_add_dont_use_for_new.sql'),
('kvwmap', 'mysql', '2017-07-04_14-37-35_rolle_gui_button.sql'),
('kvwmap', 'mysql', '2017-07-11_10-20-03_layer_attributes_radiobutton.sql'),
('kvwmap', 'mysql', '2017-07-11_14-57-05_druckfreilinien.sql'),
('kvwmap', 'mysql', '2017-07-13_10-37-26_layer_attributes_Winkel.sql'),
('kvwmap', 'mysql', '2017-07-14_13-51-46_classes_legendimageheight_width.sql'),
('kvwmap', 'mysql', '2017-07-17_09-31-55_label_maxlength.sql'),
('kvwmap', 'postgresql', '2014-09-12_16-33-22_Version2.0.sql'),
('kvwmap', 'postgresql', '2015-05-06_08-47-01_st_area_utm.sql'),
('kvwmap', 'postgresql', '2015-05-28_14-07-36_bug_st_area_utm.sql'),
('kvwmap', 'postgresql', '2015-05-29_09-33-07_bug_st_area_utm.sql'),
('kvwmap', 'postgresql', '2017-04-28_14-42-46_Bug_st_area_utm.sql'),
('kvwmap', 'postgresql', '2017-04-28_14-49-32_Bug_st_area_utm.sql'),
('kvwmap', 'postgresql', '2017-05-03_09-08-07_Bug_st_area_utm.sql'),
('kvwmap', 'postgresql', '2017-05-03_12-13-08_Bug_st_area_utm.sql'),
('kvwmap', 'postgresql', '2017-05-15_15-28-45_spatial_ref_sys_srs_params.sql'),
('wasserrecht', 'mysql', '2017-07-03_09-51-00_layer.sql'),
('wasserrecht', 'mysql', '2017-07-03_14-44-00_groups.sql'),
('wasserrecht', 'mysql', '2017-07-07_10-11-00_stelle.sql'),
('wasserrecht', 'postgresql', '2017-06-22_11-00-00_create_wasserrecht.sql'),
('wasserrecht', 'postgresql', '2017-06-30_13-20-00_add_wasserrecht_data.sql');

-- --------------------------------------------------------

--
-- Table structure for table `polygon`
--

CREATE TABLE `polygon` (
  `polygon_id` int(11) NOT NULL,
  `polygonname` varchar(25) NOT NULL DEFAULT '',
  `datei` varchar(30) NOT NULL DEFAULT '',
  `art` varchar(25) NOT NULL DEFAULT '',
  `feldname` varchar(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referenzkarten`
--

CREATE TABLE `referenzkarten` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Dateiname` varchar(100) NOT NULL DEFAULT '',
  `epsg_code` int(11) NOT NULL DEFAULT '2398',
  `xmin` double NOT NULL DEFAULT '0',
  `ymin` double NOT NULL DEFAULT '0',
  `xmax` double NOT NULL DEFAULT '0',
  `ymax` double NOT NULL DEFAULT '0',
  `width` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `height` int(4) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `referenzkarten`
--

INSERT INTO `referenzkarten` (`ID`, `Name`, `Dateiname`, `epsg_code`, `xmin`, `ymin`, `xmax`, `ymax`, `width`, `height`) VALUES
(1, 'Uebersichtskarte', 'uebersicht_mv.png', 25833, 201165, 5867815, 477900, 6081468, 205, 146);

-- --------------------------------------------------------

--
-- Table structure for table `rolle`
--

CREATE TABLE `rolle` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `nImageWidth` int(3) NOT NULL DEFAULT '500',
  `nImageHeight` int(3) NOT NULL DEFAULT '500',
  `auto_map_resize` tinyint(1) NOT NULL DEFAULT '1',
  `minx` double NOT NULL DEFAULT '4501025',
  `miny` double NOT NULL DEFAULT '6001879',
  `maxx` double NOT NULL DEFAULT '4502834',
  `maxy` double NOT NULL DEFAULT '6003236',
  `nZoomFactor` int(11) NOT NULL DEFAULT '2',
  `selectedButton` varchar(20) NOT NULL DEFAULT 'zoomin',
  `epsg_code` varchar(6) DEFAULT '2398',
  `epsg_code2` varchar(6) DEFAULT NULL,
  `coordtype` enum('dec','dms','dmin') NOT NULL DEFAULT 'dec',
  `active_frame` int(11) NOT NULL DEFAULT '0',
  `last_time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gui` varchar(100) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT 'gui.php',
  `language` enum('german','low-german','english','polish','vietnamese') NOT NULL DEFAULT 'german',
  `hidemenue` enum('0','1') NOT NULL DEFAULT '0',
  `hidelegend` enum('0','1') NOT NULL DEFAULT '0',
  `fontsize_gle` int(2) DEFAULT '15',
  `highlighting` tinyint(1) NOT NULL DEFAULT '0',
  `buttons` varchar(255) DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure',
  `scrollposition` int(11) NOT NULL DEFAULT '0',
  `result_color` int(11) DEFAULT '1',
  `always_draw` tinyint(1) DEFAULT NULL,
  `runningcoords` tinyint(1) NOT NULL DEFAULT '0',
  `showmapfunctions` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Schaltet die Menüleiste mit den Kartenfunktionen unter der Karte ein oder aus.',
  `showlayeroptions` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Schaltet die Layeroptionen in der Legende ein oder aus.',
  `singlequery` tinyint(1) NOT NULL DEFAULT '0',
  `querymode` tinyint(1) NOT NULL DEFAULT '0',
  `geom_edit_first` tinyint(1) NOT NULL DEFAULT '0',
  `overlayx` int(11) NOT NULL DEFAULT '400',
  `overlayy` int(11) NOT NULL DEFAULT '150',
  `hist_timestamp` timestamp NULL DEFAULT NULL,
  `instant_reload` tinyint(1) NOT NULL DEFAULT '0',
  `menu_auto_close` tinyint(1) NOT NULL DEFAULT '0',
  `layer_params` text,
  `menue_buttons` tinyint(1) NOT NULL DEFAULT '0',
  `visually_impaired` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rolle`
--

INSERT INTO `rolle` (`user_id`, `stelle_id`, `nImageWidth`, `nImageHeight`, `auto_map_resize`, `minx`, `miny`, `maxx`, `maxy`, `nZoomFactor`, `selectedButton`, `epsg_code`, `epsg_code2`, `coordtype`, `active_frame`, `last_time_id`, `gui`, `language`, `hidemenue`, `hidelegend`, `fontsize_gle`, `highlighting`, `buttons`, `scrollposition`, `result_color`, `always_draw`, `runningcoords`, `showmapfunctions`, `showlayeroptions`, `singlequery`, `querymode`, `geom_edit_first`, `overlayx`, `overlayy`, `hist_timestamp`, `instant_reload`, `menu_auto_close`, `layer_params`, `menue_buttons`, `visually_impaired`) VALUES
(1, 1, 1198, 802, 1, 33303380.891093, 5987685.9045419, 33303595.417672, 5987829.4599217, 2, 'zoomin', '35833', '', 'dec', 0, '2017-08-10 10:07:41', 'gui.php', 'german', '0', '0', 15, 0, 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure,', 0, 1, 0, 0, 1, 1, 0, 0, 0, 400, 150, NULL, 0, 0, '', 1, 0),
(2, 2, 1198, 770, 1, 239608.32867608, 5890464.3996456, 453335.02845224, 6047442.271783, 2, 'zoomin', '35833', NULL, 'dec', 0, '2017-07-12 11:18:57', 'gui.php', 'german', '0', '0', 15, 0, 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure', 0, 1, 0, 0, 1, 1, 0, 0, 0, 400, 150, NULL, 0, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `rollenlayer`
--

CREATE TABLE `rollenlayer` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `aktivStatus` enum('0','1','2') CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `queryStatus` enum('0','1','2') NOT NULL,
  `Name` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `Gruppe` int(11) NOT NULL,
  `Typ` enum('search','import') NOT NULL DEFAULT 'search',
  `Datentyp` int(11) NOT NULL,
  `Data` longtext NOT NULL,
  `query` text,
  `connectiontype` int(11) NOT NULL,
  `connection` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `epsg_code` int(11) NOT NULL,
  `transparency` int(11) NOT NULL,
  `labelitem` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rolle_csv_attributes`
--

CREATE TABLE `rolle_csv_attributes` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `attributes` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rolle_last_query`
--

CREATE TABLE `rolle_last_query` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `go` varchar(50) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `sql` longtext NOT NULL,
  `orderby` text,
  `limit` int(11) DEFAULT NULL,
  `offset` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rolle_last_query`
--

INSERT INTO `rolle_last_query` (`user_id`, `stelle_id`, `go`, `layer_id`, `sql`, `orderby`, `limit`, `offset`) VALUES
(2, 2, 'Layer-Suche_Suchen', 25, 'SELECT * FROM (SELECT wasserrechtliche_zulassungen.oid AS wasserrechtliche_zulassungen_oid,   name,  ausstellbehoerde,  ausgangsbescheid,  fassung,  status,  adresse as adress_id,   aenderungsbescheid,  gueltigkeit,  bergamt_aktenzeichen,  dokument,  sachbearbeiter,  adressat,  anlage  FROM wasserrechtliche_zulassungen WHERE 1=1) as query WHERE 1=1  AND ( (1=1)) AND wasserrechtliche_zulassungen_oid = 113298', ' ORDER BY fassung, wasserrechtliche_zulassungen_oid ', 10, NULL),
(1, 1, 'Layer-Suche_Suchen', 33, 'SELECT * FROM (SELECT b.oid AS gewaesserbenutzungen_oid,  b.id,  b.kennnummer,  COALESCE(a.name,\'\') ||\' (Aktenzeichen: \'|| COALESCE(h.name,\'\') ||\')\'||\' vom \'|| COALESCE(f.datum::text,\'\') || \' zum \' || COALESCE(c.name,\'\') || \' von \' || COALESCE(d.max_ent_a::text,\'\') || \' m³/Jahr\' AS bezeichnung,  b.art,  b.wasserbuch,  b.zweck,  b.umfang,  b.gruppe_wee,  b.lage,   \'\' AS  lage_link,  b.wasserrechtliche_zulassungen as wrz_id,  \'\' AS wasserrechtliche_zulassungen_link,  e.namelang AS wrz_ben_lage_namelang,  a.adressat as personen_id,   CASE when a.aktuell then \'aktuell\' else \'false\' end AS aktuell,  CASE when a.historisch then \'historisch\' else \'false\' end as historisch,  a.anlage AS anlage_id,  \'\' AS anlage  FROM wasserrechtliche_zulassungen a LEFT JOIN wasserrechtliche_zulassungen_ausgangsbescheide f ON a.ausgangsbescheid = f.id LEFT JOIN wasserrechtliche_zulassungen_ausgangsbescheide_klasse g ON f.klasse = g.id LEFT JOIN aktenzeichen h ON f.aktenzeichen = h.id INNER JOIN gewaesserbenutzungen b ON b.wasserrechtliche_zulassungen = a.id LEFT JOIN gewaesserbenutzungen_art c ON c.id = b.art LEFT JOIN gewaesserbenutzungen_umfang d ON b.umfang = d.id LEFT JOIN gewaesserbenutzungen_lage e ON b.lage = e.id WHERE 1=1) as query WHERE 1=1  AND ( (1=1))', ' ORDER BY gewaesserbenutzungen_oid ', 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rolle_saved_layers`
--

CREATE TABLE `rolle_saved_layers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `layers` text NOT NULL,
  `query` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `search_attributes2rolle`
--

CREATE TABLE `search_attributes2rolle` (
  `name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `attribute` varchar(50) NOT NULL,
  `operator` varchar(11) NOT NULL,
  `value1` text,
  `value2` text,
  `searchmask_number` int(11) NOT NULL DEFAULT '0',
  `searchmask_operator` enum('AND','OR') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stelle`
--

CREATE TABLE `stelle` (
  `ID` int(11) NOT NULL,
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
  `default_user_id` int(11) DEFAULT NULL COMMENT 'Nutzer Id der default Rolle. Die Einstellungen dieser Rolle werden für das Anlegen neuer Rollen für diese Stelle verwendet. Ist dieser Wert nicht angegeben oder die angegebene Rolle existiert nicht, werden die Defaultwerte der Rollenoptionen bei der Zuordnung eines Nutzers zu dieser Stelle verwendet. Die Angabe ist nützlich, wenn die Einstellungen in Gaststellen am Anfang immer gleich sein sollen.'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stelle`
--

INSERT INTO `stelle` (`ID`, `Bezeichnung`, `Bezeichnung_low-german`, `Bezeichnung_english`, `Bezeichnung_polish`, `Bezeichnung_vietnamese`, `start`, `stop`, `minxmax`, `minymax`, `maxxmax`, `maxymax`, `epsg_code`, `Referenzkarte_ID`, `Authentifizierung`, `ALB_status`, `wappen`, `wappen_link`, `alb_raumbezug`, `alb_raumbezug_wert`, `logconsume`, `pgdbhost`, `pgdbname`, `pgdbuser`, `pgdbpasswd`, `ows_title`, `wms_accessconstraints`, `ows_abstract`, `ows_contactperson`, `ows_contactorganization`, `ows_contactemailaddress`, `ows_contactposition`, `ows_fees`, `ows_srs`, `check_client_ip`, `check_password_age`, `allowed_password_age`, `use_layer_aliases`, `selectable_layer_params`, `hist_timestamp`, `default_user_id`) VALUES
(1, 'Administration', NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', 201165, 5867815, 477900, 6081468, '25833', 1, '1', '30', 'Logo_GDI-Service_200x47.png', '', '', '', NULL, 'localhost', '', '', '', '', '', '', '', '', '', '', '', '', '0', '0', 6, '0', '', 0, NULL),
(2, 'Dateneingeber', NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', 201165, 5867815, 477900, 6081468, '25833', 1, '1', '30', 'logo_lung.jpg', '', '', '', NULL, 'localhost', '', '', '', '', '', '', '', '', '', '', '', '', '0', '0', 6, '0', '', 0, NULL),
(4, 'Entscheider', NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', 201165, 5867815, 477900, 6081468, '25833', 1, '1', '30', 'logo_lung.jpg', '', '', '', NULL, 'localhost', '', '', '', '', '', '', '', '', '', '', '', '', '0', '0', 6, '0', '', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stelle_gemeinden`
--

CREATE TABLE `stelle_gemeinden` (
  `Stelle_ID` int(11) NOT NULL DEFAULT '0',
  `Gemeinde_ID` int(8) NOT NULL DEFAULT '0',
  `Gemarkung` int(6) DEFAULT NULL,
  `Flur` int(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `styles`
--

CREATE TABLE `styles` (
  `Style_ID` int(11) NOT NULL,
  `symbol` int(3) DEFAULT NULL,
  `symbolname` text,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(11) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `backgroundcolor` varchar(11) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `outlinecolor` varchar(11) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `colorrange` varchar(23) DEFAULT NULL,
  `datarange` varchar(255) DEFAULT NULL,
  `rangeitem` varchar(50) DEFAULT NULL,
  `opacity` int(11) DEFAULT NULL,
  `minsize` int(11) UNSIGNED DEFAULT NULL,
  `maxsize` int(11) UNSIGNED DEFAULT NULL,
  `minscale` int(11) UNSIGNED DEFAULT NULL,
  `maxscale` int(11) UNSIGNED DEFAULT NULL,
  `angle` varchar(11) DEFAULT NULL,
  `angleitem` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `antialias` tinyint(1) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `minwidth` int(11) DEFAULT NULL,
  `maxwidth` int(11) DEFAULT NULL,
  `sizeitem` varchar(255) CHARACTER SET latin1 COLLATE latin1_german2_ci DEFAULT NULL,
  `offsetx` int(11) DEFAULT NULL,
  `offsety` int(11) DEFAULT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  `geomtransform` varchar(20) DEFAULT NULL,
  `gap` int(11) DEFAULT NULL,
  `initialgap` decimal(5,2) DEFAULT NULL,
  `linecap` varchar(8) DEFAULT NULL,
  `linejoin` varchar(5) DEFAULT NULL,
  `linejoinmaxsize` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `styles`
--

INSERT INTO `styles` (`Style_ID`, `symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `colorrange`, `datarange`, `rangeitem`, `opacity`, `minsize`, `maxsize`, `minscale`, `maxscale`, `angle`, `angleitem`, `antialias`, `width`, `minwidth`, `maxwidth`, `sizeitem`, `offsetx`, `offsety`, `pattern`, `geomtransform`, `gap`, `initialgap`, `linecap`, `linejoin`, `linejoinmaxsize`) VALUES
(1, NULL, 'circle', '8', '30 149 255', NULL, '0 0 0', NULL, NULL, NULL, NULL, NULL, 10, 6, NULL, '360', '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 'circle', '8', '30 149 255', NULL, '0 0 0', NULL, NULL, NULL, NULL, NULL, 10, NULL, NULL, '360', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, NULL, 'circle', '8', '30 149 255', NULL, '0 0 0', NULL, NULL, NULL, NULL, NULL, 10, NULL, NULL, '360', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `used_layer`
--

CREATE TABLE `used_layer` (
  `Stelle_ID` int(11) NOT NULL DEFAULT '0',
  `Layer_ID` int(11) NOT NULL DEFAULT '0',
  `queryable` enum('0','1') NOT NULL DEFAULT '1',
  `drawingorder` int(11) NOT NULL DEFAULT '0',
  `legendorder` int(11) DEFAULT NULL,
  `minscale` int(11) DEFAULT NULL,
  `maxscale` int(11) DEFAULT NULL,
  `offsite` varchar(11) DEFAULT NULL,
  `transparency` tinyint(3) DEFAULT NULL,
  `postlabelcache` enum('0','1') NOT NULL DEFAULT '0',
  `Filter` longtext,
  `template` varchar(255) DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  `footer` varchar(255) DEFAULT NULL,
  `symbolscale` int(11) UNSIGNED DEFAULT NULL,
  `requires` int(11) DEFAULT NULL,
  `logconsume` enum('0','1') NOT NULL DEFAULT '0',
  `privileg` enum('0','1','2') NOT NULL DEFAULT '0',
  `export_privileg` tinyint(1) NOT NULL DEFAULT '1',
  `start_aktiv` enum('0','1') NOT NULL DEFAULT '0',
  `use_geom` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `used_layer`
--

INSERT INTO `used_layer` (`Stelle_ID`, `Layer_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES
(1, 1, '0', 0, NULL, 0, 0, '', NULL, '0', NULL, '', NULL, NULL, NULL, NULL, '0', '0', 1, '0', 1),
(0, 2, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 2, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', '', '', 0, NULL, '0', '2', 1, '0', 1),
(0, 3, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 3, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 4, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(0, 5, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(0, 6, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 6, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 7, '0', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(4, 1, '0', 0, NULL, 0, 0, '', NULL, '0', NULL, '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(2, 1, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', '', '', 0, NULL, '0', '0', 1, '0', 1),
(1, 9, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 10, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 2, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 11, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 12, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 13, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 14, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 49, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 16, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 43, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 17, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 18, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 19, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 20, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 21, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 22, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 23, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 24, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 25, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', '', '', 0, NULL, '0', '2', 1, '0', 1),
(1, 26, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 31, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 32, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 29, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 30, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 32, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 33, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 33, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 34, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 34, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 35, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 36, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 37, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 38, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 39, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 40, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 41, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 42, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 33, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 9, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 43, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(2, 38, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 25, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 3, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 12, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 12, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 23, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 23, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 2, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 3, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 41, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 41, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 17, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 17, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 39, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 39, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 24, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 24, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 33, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 34, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 34, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 38, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 37, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 37, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 36, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 36, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 16, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 16, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 6, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 6, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 42, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 42, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 40, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 40, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 30, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 30, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 9, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 10, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 10, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 11, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 11, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 35, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 35, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 43, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(4, 25, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 21, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 21, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 18, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 18, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 26, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 26, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 29, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 29, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 19, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 19, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 31, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 31, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 22, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 22, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 32, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 32, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 20, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 20, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 14, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 14, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 15, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 15, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 44, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(2, 44, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 44, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 45, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 45, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 46, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 46, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 47, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(2, 47, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 47, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 48, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 48, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 49, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 49, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `login_name` varchar(100) NOT NULL DEFAULT '',
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Vorname` varchar(100) DEFAULT NULL,
  `Namenszusatz` varchar(50) DEFAULT NULL,
  `passwort` varchar(32) NOT NULL DEFAULT '',
  `password_setting_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start` date NOT NULL DEFAULT '0000-00-00',
  `stop` date NOT NULL DEFAULT '0000-00-00',
  `ips` text,
  `Funktion` enum('admin','user','gast') NOT NULL DEFAULT 'user',
  `stelle_id` int(11) DEFAULT NULL,
  `phon` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `login_name`, `Name`, `Vorname`, `Namenszusatz`, `passwort`, `password_setting_time`, `start`, `stop`, `ips`, `Funktion`, `stelle_id`, `phon`, `email`) VALUES
(1, 'kvwmap', 'kvwmap', 'hans', NULL, '536f8942987f8def483f847fd1631b09', '2017-06-15 07:57:32', '0000-00-00', '0000-00-00', NULL, 'admin', 1, '', 'admin@localhost.de'),
(2, 'TEST_DATENEINGEBER', 'Dateneingeber', 'Test', '', '098f6bcd4621d373cade4e832627b4f6', '2017-07-11 12:45:57', '0000-00-00', '0000-00-00', '', 'user', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `u_attributfilter2used_layer`
--

CREATE TABLE `u_attributfilter2used_layer` (
  `Stelle_ID` int(11) NOT NULL,
  `Layer_ID` int(11) NOT NULL,
  `attributname` varchar(255) NOT NULL,
  `attributvalue` text CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `operator` enum('=','!=','>','<','like','IS','IN','st_within','st_intersects') NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consume`
--

CREATE TABLE `u_consume` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `nimagewidth` int(11) DEFAULT NULL,
  `nimageheight` int(11) DEFAULT NULL,
  `epsg_code` varchar(6) DEFAULT NULL,
  `minx` double DEFAULT NULL,
  `miny` double DEFAULT NULL,
  `maxx` double DEFAULT NULL,
  `maxy` double DEFAULT NULL,
  `prev` datetime DEFAULT NULL,
  `next` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_consume`
--

INSERT INTO `u_consume` (`user_id`, `stelle_id`, `time_id`, `activity`, `nimagewidth`, `nimageheight`, `epsg_code`, `minx`, `miny`, `maxx`, `maxy`, `prev`, `next`) VALUES
(1, 1, '2017-06-15 09:58:30', 'getMap', 1194, 798, '25833', 246321.74306602, 5919725.4326488, 335782.64196243, 5979491.0122133, '2017-06-15 09:58:30', NULL),
(1, 1, '2017-06-15 09:58:32', 'getMap', 1194, 798, '25833', 281836.37127113, 5931086.2068678, 309483.83500042, 5949556.4739191, '2017-06-15 09:58:30', NULL),
(1, 1, '2017-06-15 09:58:34', 'getMap', 1194, 798, '25833', 292349.93558193, 5934372.8959922, 297789.34887203, 5938006.7705875, '2017-06-15 09:58:32', NULL),
(1, 1, '2017-06-15 09:58:36', 'getMap', 1194, 798, '25833', 294078.93133986, 5935219.8893189, 295094.56113964, 5935898.3947258, '2017-06-15 09:58:34', NULL),
(1, 1, '2017-06-15 09:58:39', 'getMap', 1194, 798, '25833', 294418.41155791, 5935390.7910567, 294656.41001977, 5935549.7891909, '2017-06-15 09:58:36', NULL),
(1, 1, '2017-06-15 09:58:47', 'getMap', 1194, 798, '25833', 236284.99496861, 5895292.3155645, 421919.21065943, 6019307.7974635, '2017-06-15 09:58:39', NULL),
(1, 1, '2017-06-16 15:35:46', 'getMap', 500, 500, '25833', 235816.81283577, 5895292.3155645, 422387.39279227, 6019307.7974635, '2017-06-15 09:58:47', NULL),
(1, 1, '2017-06-20 13:42:05', 'getMap', 1194, 794, '25833', 201165, 5867815, 477900, 6081468, '2017-06-16 15:35:46', NULL),
(1, 1, '2017-06-20 13:42:25', 'getMap', 1194, 794, '25833', 180168.37704918, 5867815, 498896.62295082, 6081468, '2017-06-20 13:42:05', NULL),
(1, 1, '2017-06-20 13:56:24', 'getMap', 1662, 948, '25833', 201165, 5972613.1487342, 388534.39440338, 6079439.6487342, '2017-06-20 13:42:25', NULL),
(1, 1, '2017-06-20 13:56:55', 'getMap', 1662, 948, '25833', 201165, 5867815, 477900, 6081468, '2017-06-20 13:56:24', NULL),
(1, 1, '2017-06-20 14:02:20', 'getMap', 1198, 802, '25833', 298505.73874922, 5944666.2835013, 397691.31539945, 6011038.5866733, '2017-06-20 13:56:55', NULL),
(1, 1, '2017-06-20 14:02:22', 'getMap', 1198, 802, '25833', 283830.8990563, 5936224.91826, 402927.79470995, 6015921.337156, '2017-06-20 14:02:20', NULL),
(1, 1, '2017-06-26 15:01:07', 'getMap', 1198, 802, '25833', 280578.57810872, 5934048.5531146, 406180.11565753, 6018097.7023014, '2017-06-20 14:02:22', NULL),
(1, 1, '2017-06-28 17:09:08', 'getMap', 1198, 772, '25833', 278134.96842878, 5934048.5531146, 408623.72533747, 6018097.7023014, '2017-06-26 15:01:07', NULL),
(1, 1, '2017-06-30 13:55:39', 'getMap', 1198, 738, '25833', 166030.02985075, 5867815, 513034.97014925, 6081468, '2017-06-28 17:09:08', NULL),
(1, 1, '2017-06-30 13:56:22', 'getMap', 500, 500, '25833', 166030.02985075, 5867815, 513034.97014925, 6081468, '2017-06-30 13:55:39', NULL),
(1, 1, '2017-06-30 13:56:30', 'getMap', 500, 500, '25833', 166030.02985075, 5801139.0298508, 513034.97014925, 6148143.9701492, '2017-06-30 13:56:22', NULL),
(1, 1, '2017-06-30 13:58:19', 'getMap', 500, 500, '25833', 166030.02985075, 5801139.0298508, 513034.97014925, 6148143.9701492, '2017-06-30 13:56:30', NULL),
(1, 1, '2017-06-30 13:58:45', 'getMap', 500, 500, '25833', 166030.02985075, 5801139.0298508, 513034.97014925, 6148143.9701492, '2017-06-30 13:58:19', NULL),
(1, 1, '2017-06-30 13:59:14', 'getMap', 1198, 738, '25833', 281253.8666026, 5975581.8927921, 332419.78352021, 6007085.0513287, '2017-06-30 13:58:45', NULL),
(1, 1, '2017-06-30 14:08:14', 'getMap', 1198, 772, '35833', 33281253.941412, 5974855.2738107, 33332419.708708, 6007811.6702893, '2017-06-30 13:59:14', NULL),
(1, 1, '2017-06-30 15:57:05', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.7102701, 33303534.354522, 5987787.7102701, '2017-06-30 14:08:14', NULL),
(1, 1, '2017-06-30 15:57:11', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.7102701, 33303534.354522, 5987787.7102701, '2017-06-30 15:57:05', NULL),
(1, 1, '2017-06-30 15:57:20', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.71027, 33303534.354522, 5987787.7102702, '2017-06-30 15:57:11', NULL),
(1, 1, '2017-06-30 15:57:24', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.71027, 33303534.354522, 5987787.7102702, '2017-06-30 15:57:20', NULL),
(1, 1, '2017-06-30 15:57:37', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.71027, 33303534.354522, 5987787.7102702, '2017-06-30 15:57:24', NULL),
(1, 1, '2017-06-30 15:57:43', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.71027, 33303534.354522, 5987787.7102702, '2017-06-30 15:57:37', NULL),
(1, 1, '2017-06-30 15:57:57', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.7102701, 33303534.354522, 5987787.7102701, '2017-06-30 15:57:43', NULL),
(1, 1, '2017-06-30 15:58:22', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.71027, 33303534.354522, 5987787.7102702, '2017-06-30 15:57:57', NULL),
(1, 1, '2017-06-30 15:58:28', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.71027, 33303534.354522, 5987787.7102702, '2017-06-30 15:58:22', NULL),
(1, 1, '2017-06-30 15:58:40', 'getMap', 1198, 772, '35833', 33303243.303938, 5987587.71027, 33303514.899269, 5987787.7102702, '2017-06-30 15:58:28', NULL),
(1, 1, '2017-06-30 15:59:48', 'getMap', 1198, 772, '35833', 33303243.303938, 5987587.71027, 33303514.899269, 5987787.7102702, '2017-06-30 15:58:40', NULL),
(1, 1, '2017-06-30 16:00:32', 'getMap', 1198, 772, '35833', 33303243.303938, 5987587.71027, 33303514.899269, 5987787.7102702, '2017-06-30 15:59:48', NULL),
(1, 1, '2017-06-30 16:02:05', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.7102701, 33303534.354522, 5987787.7102701, '2017-06-30 16:00:32', NULL),
(1, 1, '2017-06-30 16:02:14', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.71027, 33303534.354522, 5987787.7102702, '2017-06-30 16:02:05', NULL),
(1, 1, '2017-06-30 16:46:41', 'getMap', 1198, 772, '35833', 33303243.303938, 5987587.71027, 33303514.899269, 5987787.7102702, '2017-06-30 16:02:14', NULL),
(1, 1, '2017-06-30 16:47:27', 'getMap', 1198, 772, '35833', 33303243.303938, 5987587.71027, 33303514.899269, 5987787.7102702, '2017-06-30 16:46:41', NULL),
(1, 1, '2017-06-30 16:47:41', 'getMap', 1198, 772, '35833', 33303209.613677, 5987529.0814482, 33303571.136089, 5987795.3028225, '2017-06-30 16:47:27', NULL),
(1, 1, '2017-06-30 16:48:00', 'getMap', 1198, 772, '35833', 33303209.613677, 5987529.0814478, 33303571.136089, 5987795.3028229, '2017-06-30 16:47:41', NULL),
(1, 1, '2017-06-30 16:48:18', 'getMap', 1198, 772, '35833', 33303209.613677, 5987529.0814478, 33303571.136089, 5987795.3028229, '2017-06-30 16:48:00', NULL),
(1, 1, '2017-06-30 16:48:53', 'getMap', 1198, 772, '35833', 33303209.613677, 5987529.0814478, 33303571.136089, 5987795.3028229, '2017-06-30 16:48:18', NULL),
(1, 1, '2017-06-30 16:53:01', 'getMap', 1198, 772, '35833', 33303183.716656, 5987529.0814478, 33303597.03311, 5987795.3028229, '2017-06-30 16:48:53', NULL),
(1, 1, '2017-06-30 16:53:24', 'getMap', 1198, 772, '35833', 33303209.613677, 5987529.0814478, 33303571.136089, 5987795.3028229, '2017-06-30 16:53:01', NULL),
(1, 1, '2017-06-30 16:54:23', 'getMap', 1198, 772, '35833', 33303209.613677, 5987529.0814478, 33303571.136089, 5987795.3028229, '2017-06-30 16:53:24', NULL),
(1, 1, '2017-06-30 16:55:14', 'getMap', 1198, 772, '35833', 33303209.613677, 5987529.0814478, 33303571.136089, 5987795.3028229, '2017-06-30 16:54:23', NULL),
(1, 1, '2017-06-30 17:00:49', 'getMap', 1198, 772, '35833', 33303209.613677, 5987529.0814478, 33303571.136089, 5987795.3028229, '2017-06-30 16:55:14', NULL),
(1, 1, '2017-06-30 17:13:11', 'getMap', 1198, 772, '35833', 33303209.61337, 5987529.081, 33303571.13663, 5987795.303, '2017-06-30 17:00:49', NULL),
(1, 1, '2017-07-03 08:35:33', 'getMap', 1198, 802, '35833', 33302878.632606, 5987199.020871, 33303868.576848, 5987861.4647625, '2017-06-30 17:13:11', NULL),
(1, 1, '2017-07-03 09:35:47', 'getMap', 1198, 802, '35833', 33302825.747438, 5987171.487415, 33304014.011061, 5987966.6412681, '2017-07-03 08:35:33', NULL),
(1, 1, '2017-07-03 10:10:53', 'getMap', 1198, 802, '35833', 33302900.200046, 5987171.4874151, 33303939.558453, 5987966.641268, '2017-07-03 09:35:47', NULL),
(1, 1, '2017-07-03 10:13:39', 'getMap', 1198, 802, '35833', 33302900.200046, 5987171.4874151, 33303939.558453, 5987966.641268, '2017-07-03 10:10:53', NULL),
(1, 1, '2017-07-03 10:19:02', 'getMap', 1198, 802, '35833', 33302900.200046, 5987171.4874151, 33303939.558453, 5987966.641268, '2017-07-03 10:13:39', NULL),
(1, 1, '2017-07-03 10:26:43', 'getMap', 500, 500, '35833', 33302900.200046, 5987171.4874151, 33303939.558453, 5987966.641268, '2017-07-03 10:19:02', NULL),
(1, 1, '2017-07-03 11:54:51', 'getMap', 1198, 802, '35833', 33302740.598193, 5987049.385138, 33304099.160306, 5988088.7435451, '2017-07-03 10:26:43', NULL),
(1, 1, '2017-07-03 13:15:02', 'getMap', 1198, 802, '35833', 33302740.598193, 5987049.3851378, 33304099.160306, 5988088.7435453, '2017-07-03 11:54:51', NULL),
(1, 1, '2017-07-03 13:16:38', 'getMap', 1198, 802, '35833', 33302740.598193, 5987049.3851378, 33304099.160306, 5988088.7435453, '2017-07-03 13:15:02', NULL),
(1, 1, '2017-07-03 13:20:56', 'getMap', 1198, 802, '35833', 33302740.598193, 5987049.3851378, 33304099.160306, 5988088.7435453, '2017-07-03 13:16:38', NULL),
(1, 1, '2017-07-03 13:57:58', 'getMap', 1198, 802, '35833', 33302740.598193, 5987049.3851378, 33304099.160306, 5988088.7435453, '2017-07-03 13:20:56', NULL),
(1, 1, '2017-07-03 14:11:35', 'getMap', 1198, 802, '35833', 33302062.458302, 5985933.5652016, 33305443.617875, 5988520.2975973, '2017-07-03 13:57:58', NULL),
(1, 1, '2017-07-03 14:11:40', 'getMap', 1198, 802, '35833', 33301501.082571, 5985280.0313071, 33306308.265552, 5988957.7329002, '2017-07-03 14:11:35', NULL),
(1, 1, '2017-07-03 14:12:09', 'getMap', 1198, 802, '35833', 33301844.879292, 5985601.0277055, 33306028.686924, 5988801.8203638, '2017-07-03 14:11:40', NULL),
(1, 1, '2017-07-03 14:12:33', 'getMap', 1198, 802, '35833', 33304403.868502, 5987391.8007001, 33304886.922627, 5987761.3578677, '2017-07-03 14:12:09', NULL),
(1, 1, '2017-07-03 14:13:08', 'getMap', 1198, 802, '35833', 33304403.868502, 5987391.8007001, 33304886.922627, 5987761.3578677, '2017-07-03 14:12:33', NULL),
(1, 1, '2017-07-03 14:13:14', 'getMap', 1198, 802, '35833', 33304076.976374, 5987179.8352424, 33305079.223338, 5987946.597246, '2017-07-03 14:13:08', NULL),
(1, 1, '2017-07-03 14:13:16', 'getMap', 1198, 802, '35833', 33303950.739161, 5987118.3097108, 33305153.818055, 5988038.7167728, '2017-07-03 14:13:14', NULL),
(1, 1, '2017-07-03 14:13:21', 'getMap', 1198, 802, '35833', 33303443.174865, 5986861.2384116, 33305175.788484, 5988186.7622979, '2017-07-03 14:13:16', NULL),
(1, 1, '2017-07-03 14:21:59', 'getMap', 1198, 802, '35833', 33303443.174865, 5986861.2384116, 33305175.788484, 5988186.7622979, '2017-07-03 14:13:21', NULL),
(1, 1, '2017-07-03 14:22:37', 'getMap', 1198, 802, '35833', 33303773.826319, 5987231.396464, 33304864.976117, 5988066.1729573, '2017-07-03 14:21:59', NULL),
(1, 1, '2017-07-03 14:22:39', 'getMap', 1198, 802, '35833', 33303529.00645, 5986918.0950619, 33305100.425424, 5988120.2981165, '2017-07-03 14:22:37', NULL),
(1, 1, '2017-07-03 14:22:41', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.7351732, 33305306.040172, 5988232.7235892, '2017-07-03 14:22:39', NULL),
(1, 1, '2017-07-03 14:29:21', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-03 14:22:41', NULL),
(1, 1, '2017-07-03 14:33:25', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-03 14:29:21', NULL),
(1, 1, '2017-07-03 14:48:13', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-03 14:33:25', NULL),
(1, 1, '2017-07-03 14:48:54', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-03 14:48:13', NULL),
(1, 1, '2017-07-03 14:50:52', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-03 14:48:54', NULL),
(1, 1, '2017-07-04 09:56:52', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.7351733, 33305306.040172, 5988232.7235891, '2017-07-03 14:50:52', NULL),
(1, 1, '2017-07-04 10:02:16', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 09:56:52', NULL),
(1, 1, '2017-07-04 10:16:54', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 10:02:16', NULL),
(1, 1, '2017-07-04 10:18:38', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 10:16:54', NULL),
(1, 1, '2017-07-04 10:20:48', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 10:18:38', NULL),
(1, 1, '2017-07-04 11:29:21', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 10:20:48', NULL),
(1, 1, '2017-07-04 11:30:31', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 11:29:21', NULL),
(1, 1, '2017-07-04 11:31:44', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 11:30:31', NULL),
(1, 1, '2017-07-04 12:46:28', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 11:31:44', NULL),
(1, 1, '2017-07-04 13:22:16', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 12:46:28', NULL),
(1, 1, '2017-07-04 14:05:18', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 13:22:16', NULL),
(1, 1, '2017-07-04 14:27:35', 'getMap', 1198, 802, '35833', 33303311.396137, 5986706.735173, 33305306.040172, 5988232.7235894, '2017-07-04 14:05:18', NULL),
(1, 1, '2017-07-04 14:28:45', 'getMap', 1198, 802, '35833', 33288250.691701, 5978467.8781487, 33307548.111425, 5993231.2336396, '2017-07-04 14:27:35', NULL),
(1, 1, '2017-07-04 14:29:37', 'getMap', 1198, 802, '35833', 33288250.691701, 5978467.8781487, 33307548.111425, 5993231.2336396, '2017-07-04 14:28:45', NULL),
(1, 1, '2017-07-04 14:30:43', 'getMap', 1198, 802, '35833', 33288250.691701, 5978467.8781487, 33307548.111425, 5993231.2336396, '2017-07-04 14:29:37', NULL),
(1, 1, '2017-07-05 08:27:57', 'getMap', 1198, 802, '35833', 33288250.691701, 5978467.8781487, 33307548.111425, 5993231.2336396, '2017-07-04 14:30:43', NULL),
(1, 1, '2017-07-05 08:28:01', 'getMap', 1198, 802, '35833', 33285194.03934, 5975700.1529903, 33308358.308436, 5993421.8144479, '2017-07-05 08:27:57', NULL),
(1, 1, '2017-07-05 08:28:06', 'getMap', 1198, 802, '35833', 33292908.094602, 5982040.2366932, 33303628.200052, 5990241.5781121, '2017-07-05 08:28:01', NULL),
(1, 1, '2017-07-05 08:28:25', 'getMap', 1198, 802, '35833', 33272920.417074, 5972701.1330354, 33304927.2968, 5997187.7716801, '2017-07-05 08:28:06', NULL),
(1, 1, '2017-07-05 08:28:29', 'getMap', 1198, 802, '35833', 33272548.290105, 5973250.7084663, 33302001.004866, 5995783.3011344, '2017-07-05 08:28:25', NULL),
(1, 1, '2017-07-05 08:30:03', 'getMap', 1198, 802, '35833', 33272548.290105, 5973250.7084663, 33302001.004866, 5995783.3011344, '2017-07-05 08:28:29', NULL),
(1, 1, '2017-07-05 08:31:22', 'getMap', 1198, 802, '35833', 33272548.290105, 5973250.7084663, 33302001.004866, 5995783.3011344, '2017-07-05 08:30:03', NULL),
(1, 1, '2017-07-05 08:31:55', 'getMap', 1198, 802, '35833', 33272548.290105, 5973250.7084663, 33302001.004866, 5995783.3011344, '2017-07-05 08:31:22', NULL),
(1, 1, '2017-07-05 08:34:26', 'getMap', 1198, 802, '35833', 33248182.351462, 5971536.8828394, 33307087.780984, 6016602.0681757, '2017-07-05 08:31:55', NULL),
(1, 1, '2017-07-05 08:34:33', 'getMap', 1198, 802, '35833', 33256613.4721, 5981566.9770819, 33286066.186861, 6004099.5697501, '2017-07-05 08:34:26', NULL),
(1, 1, '2017-07-05 08:34:37', 'getMap', 1198, 802, '35833', 33276352.036086, 5991709.4533328, 33276388.760169, 5991737.5488349, '2017-07-05 08:34:33', NULL),
(1, 1, '2017-07-05 08:34:45', 'getMap', 1198, 802, '35833', 33276347.088426, 5991706.8960115, 33276391.1848, 5991740.6316331, '2017-07-05 08:34:37', NULL),
(1, 1, '2017-07-05 08:34:48', 'getMap', 1198, 802, '35833', 33276303.17636, 5991684.0971251, 33276412.922449, 5991768.0575998, '2017-07-05 08:34:45', NULL),
(1, 1, '2017-07-05 08:34:55', 'getMap', 1198, 802, '35833', 33264275.561847, 5985440.452827, 33282366.698309, 5999280.9497763, '2017-07-05 08:34:48', NULL),
(1, 1, '2017-07-05 08:35:04', 'getMap', 1198, 802, '35833', 33274805.70807, 5991031.9414029, 33278189.164889, 5993620.4312904, '2017-07-05 08:34:55', NULL),
(1, 1, '2017-07-05 08:35:12', 'getMap', 1198, 802, '35833', 33274344.034095, 5991587.078885, 33277727.490915, 5994175.5687725, '2017-07-05 08:35:04', NULL),
(1, 1, '2017-07-05 08:35:15', 'getMap', 1198, 802, '35833', 33274466.71669, 5992313.2761725, 33277850.17351, 5994901.7660606, '2017-07-05 08:35:12', NULL),
(1, 1, '2017-07-05 08:35:17', 'getMap', 1198, 802, '35833', 33274152.93329, 5992074.4379534, 33278215.612739, 5995182.5623452, '2017-07-05 08:35:15', NULL),
(1, 1, '2017-07-05 08:35:20', 'getMap', 1198, 802, '35833', 33271354.026265, 5989945.2239384, 33281464.205657, 5997679.9457084, '2017-07-05 08:35:17', NULL),
(1, 1, '2017-07-05 08:35:23', 'getMap', 1198, 802, '35833', 33267947.956175, 5987331.6209463, 33285420.161459, 6000698.6089428, '2017-07-05 08:35:20', NULL),
(1, 1, '2017-07-05 08:35:26', 'getMap', 1198, 802, '35833', 33266327.575522, 5986081.5908967, 33287307.293338, 6002131.976733, '2017-07-05 08:35:23', NULL),
(1, 1, '2017-07-05 08:35:49', 'getMap', 1198, 802, '35833', 33248470.792801, 5975086.3081627, 33300679.861564, 6015028.4897095, '2017-07-05 08:35:26', NULL),
(1, 1, '2017-07-05 08:35:59', 'getMap', 1198, 802, '35833', 33268348.100469, 5977676.0755448, 33320557.169233, 6017618.2570916, '2017-07-05 08:35:49', NULL),
(1, 1, '2017-07-05 12:52:02', 'getMap', 1198, 802, '35833', 33268348.100469, 5977676.0755446, 33320557.169233, 6017618.2570918, '2017-07-05 08:35:59', NULL),
(1, 1, '2017-07-05 12:52:09', 'getMap', 1198, 802, '35833', 33268348.100469, 5977676.0755446, 33320557.169233, 6017618.2570918, '2017-07-05 12:52:02', NULL),
(1, 1, '2017-07-05 13:57:46', 'getMap', 1198, 802, '35833', 33268348.100469, 5977676.0755447, 33320557.169233, 6017618.2570917, '2017-07-05 12:52:09', NULL),
(1, 1, '2017-07-05 13:58:37', 'getMap', 1198, 802, '35833', 33268348.100469, 5977676.0755446, 33320557.169233, 6017618.2570918, '2017-07-05 13:57:46', NULL),
(1, 1, '2017-07-05 16:16:47', 'getMap', 1198, 802, '35833', 33268348.100469, 5977676.0755447, 33320557.169233, 6017618.2570917, '2017-07-05 13:58:37', NULL),
(1, 1, '2017-07-06 10:03:42', 'getMap', 1198, 802, '35833', 33268348.100469, 5977676.0755446, 33320557.169233, 6017618.2570918, '2017-07-05 16:16:47', NULL),
(1, 1, '2017-07-06 10:04:15', 'getMap', 1198, 802, '35833', 33279308.019484, 5984791.5678766, 33317667.736038, 6014138.3997386, '2017-07-06 10:03:42', NULL),
(1, 1, '2017-07-06 10:04:49', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 10:04:15', NULL),
(1, 1, '2017-07-06 10:05:28', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 10:04:49', NULL),
(1, 1, '2017-07-06 10:08:18', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 10:05:28', NULL),
(1, 1, '2017-07-06 10:09:17', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 10:08:18', NULL),
(1, 1, '2017-07-06 10:11:29', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 10:09:17', NULL),
(1, 1, '2017-07-06 14:22:04', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 10:11:29', NULL),
(1, 1, '2017-07-06 14:23:34', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 14:22:04', NULL),
(1, 1, '2017-07-06 14:24:16', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 14:23:34', NULL),
(1, 1, '2017-07-06 14:25:06', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 14:24:16', NULL),
(1, 1, '2017-07-06 16:56:57', 'getMap', 1198, 802, '35833', 33261370.236941, 5973521.2134955, 33327662.714676, 6024237.80821, '2017-07-06 14:25:06', NULL),
(1, 1, '2017-07-07 09:46:33', 'getMap', 1198, 802, '35833', 33201165.000009, 5867814.9999175, 33477900, 6081468, '2017-07-06 16:56:57', NULL),
(1, 1, '2017-07-07 09:46:38', 'getMap', 1198, 802, '35833', 33252797.462436, 5919496.6483165, 33458244.935733, 6076672.7955094, '2017-07-07 09:46:33', NULL),
(1, 1, '2017-07-07 09:46:41', 'getMap', 1198, 802, '35833', 33268284.437999, 5928491.1686089, 33444326.261492, 6063170.7298486, '2017-07-07 09:46:38', NULL),
(2, 2, '2017-07-07 11:20:36', 'getMap', 1198, 770, '35833', 474858.00890846, 6001359.1946623, 477900, 6003313.489691, '2017-07-07 11:20:36', NULL),
(2, 2, '2017-07-07 11:20:39', 'getMap', 1198, 770, '35833', 473518.74270063, 6000610.4712422, 477900, 6003425.1636927, '2017-07-07 11:20:36', NULL),
(2, 2, '2017-07-07 11:20:42', 'getMap', 1198, 770, '35833', 471591.428346, 5999530.7938353, 477900, 6003583.6690249, '2017-07-07 11:20:39', NULL),
(1, 1, '2017-07-07 16:15:47', 'getMap', 1198, 802, '35833', 33268284.437999, 5928491.1686089, 33444326.261492, 6063170.7298486, '2017-07-07 09:46:41', NULL),
(1, 1, '2017-07-11 14:42:45', 'getMap', 1198, 772, '35833', 33251758.375164, 5928491.1686086, 33460852.324327, 6063170.7298489, '2017-07-07 16:15:47', NULL),
(2, 2, '2017-07-11 14:46:44', 'getMap', 1198, 770, '35833', 471912.64944022, 5999814.9862976, 477631.43810156, 6003488.9616364, '2017-07-07 11:20:42', NULL),
(2, 2, '2017-07-11 14:49:20', 'getMap', 1198, 770, '35833', 472270.96953178, 5999814.9862976, 477273.11801, 6003488.9616364, '2017-07-11 14:46:44', NULL),
(2, 2, '2017-07-11 14:50:23', 'getMap', 1198, 770, '35833', 472270.96953178, 5999814.9862976, 477273.11801, 6003488.9616364, '2017-07-11 14:49:20', NULL),
(2, 2, '2017-07-11 15:00:24', 'getMap', 1198, 770, '35833', 471961.21630533, 6000001.0707628, 477301.2280553, 6003431.7048779, '2017-07-11 14:50:23', NULL),
(2, 2, '2017-07-11 15:00:26', 'getMap', 1198, 770, '35833', 471961.2163053, 6000001.0707628, 477301.22805533, 6003431.7048779, '2017-07-11 15:00:24', NULL),
(2, 2, '2017-07-11 15:00:29', 'getMap', 1198, 770, '35833', 471961.2163053, 6000001.0707628, 477301.22805533, 6003431.7048779, '2017-07-11 15:00:26', NULL),
(1, 1, '2017-07-11 15:01:04', 'getMap', 1198, 772, '35833', 33251758.375164, 5928491.1686086, 33460852.324327, 6063170.7298489, '2017-07-11 14:42:45', NULL),
(2, 2, '2017-07-11 15:03:29', 'getMap', 1198, 770, '35833', 471961.2163053, 6000001.0707628, 477301.22805533, 6003431.7048779, '2017-07-11 15:00:29', NULL),
(2, 2, '2017-07-11 15:03:55', 'getMap', 1198, 770, '35833', 173249.89856957, 5867815, 505815.10143043, 6081468, '2017-07-11 15:03:29', NULL),
(2, 2, '2017-07-11 15:04:13', 'getMap', 1198, 770, '35833', 201165, 5867815, 477900, 6081468, '2017-07-11 15:03:55', NULL),
(2, 2, '2017-07-11 15:04:28', 'getMap', 1198, 770, '35833', 173249.89856957, 5867815, 505815.10143043, 6081468, '2017-07-11 15:04:13', NULL),
(2, 2, '2017-07-12 11:10:15', 'getMap', 1198, 770, '35833', 201165, 5867815, 477900, 6081468, '2017-07-11 15:04:28', NULL),
(2, 2, '2017-07-12 11:10:45', 'getMap', 1198, 770, '35833', 239608.32867608, 5890464.3996456, 453335.02845224, 6047442.271783, '2017-07-12 11:10:15', NULL),
(2, 2, '2017-07-12 11:18:57', 'getMap', 1198, 770, '35833', 239608.32867608, 5890464.3996456, 453335.02845224, 6047442.271783, '2017-07-12 11:10:45', NULL),
(1, 1, '2017-07-13 08:28:47', 'getMap', 1198, 772, '35833', 33264859.499798, 5928491.1686086, 33447751.199693, 6063170.7298489, '2017-07-11 15:01:04', NULL),
(1, 1, '2017-07-13 08:29:30', 'getMap', 1198, 772, '35833', 33264859.499798, 5928491.1686084, 33447751.199693, 6063170.7298491, '2017-07-13 08:28:47', NULL),
(1, 1, '2017-07-13 08:29:39', 'getMap', 1198, 772, '35833', 33214460.14212, 5876677.9177165, 33477900, 6070672.3116281, '2017-07-13 08:29:30', NULL),
(1, 1, '2017-07-13 08:29:41', 'getMap', 1198, 772, '35833', 33201165.000009, 5867814.9999175, 33477900, 6081468, '2017-07-13 08:29:39', NULL),
(1, 1, '2017-07-13 13:11:41', 'getMap', 500, 500, '35833', 33173681.241186, 5867814.9999175, 33505383.758823, 6081468, '2017-07-13 08:29:41', NULL),
(1, 1, '2017-07-14 12:38:52', 'getMap', 1198, 772, '35833', 33114310.362543, 5808790.2411403, 33564754.637466, 6140492.7587772, '2017-07-13 13:11:41', NULL),
(1, 1, '2017-07-28 08:45:40', 'getMap', 1198, 802, '35833', 33114310.362543, 5802336.8847661, 33564754.637466, 6146946.1151514, '2017-07-14 12:38:52', NULL),
(1, 1, '2017-07-28 08:51:06', 'getMap', 1198, 802, '35833', 33114310.362543, 5802336.8847661, 33564754.637466, 6146946.1151514, '2017-07-28 08:45:40', NULL),
(1, 1, '2017-07-28 09:38:45', 'getMap', 1198, 802, '35833', 33114310.362543, 5802336.8847661, 33564754.637466, 6146946.1151514, '2017-07-28 08:51:06', NULL),
(1, 1, '2017-07-28 15:21:30', 'getMap', 1198, 802, '35833', 33201165.000009, 5867814.9999175, 33477900, 6081468, '2017-07-28 09:38:45', NULL),
(1, 1, '2017-07-28 15:21:40', 'getMap', 1198, 802, '35833', 33199897.861374, 5867814.9999175, 33479167.138635, 6081468, '2017-07-28 15:21:30', NULL),
(1, 1, '2017-07-28 15:21:52', 'getMap', 1198, 802, '35833', 33239070.174119, 5942929.7023222, 33414945.863998, 6077482.1642063, '2017-07-28 15:21:40', NULL),
(1, 1, '2017-07-28 15:22:03', 'getMap', 1198, 802, '35833', 33201165.000009, 5867814.9999175, 33477900, 6081468, '2017-07-28 15:21:52', NULL),
(1, 1, '2017-07-28 15:51:10', 'getMap', 1198, 802, '35833', 33199897.861374, 5867814.9999175, 33479167.138635, 6081468, '2017-07-28 15:22:03', NULL),
(1, 1, '2017-07-31 16:10:11', 'getMap', 1198, 772, '35833', 33201165.000009, 5867814.9999178, 33477900, 6081467.9999997, '2017-07-28 15:51:10', NULL),
(1, 1, '2017-07-31 16:12:37', 'getMap', 1198, 772, '35833', 33303243.303938, 5987587.7102701, 33303514.899269, 5987787.7102701, '2017-07-31 16:10:11', NULL),
(1, 1, '2017-08-01 13:05:57', 'getMap', 1198, 772, '35833', 33303243.303938, 5987587.71027, 33303514.899269, 5987787.7102702, '2017-07-31 16:12:37', NULL),
(1, 1, '2017-08-01 16:05:59', 'getMap', 1198, 772, '35833', 33303223.848685, 5987587.7102701, 33303534.354522, 5987787.7102701, '2017-08-01 13:05:57', NULL),
(1, 1, '2017-08-01 16:25:16', 'getMap', 1198, 772, '35833', 33303225.145702, 5987587.71027, 33303533.057505, 5987787.7102702, '2017-08-01 16:05:59', NULL),
(1, 1, '2017-08-08 09:37:10', 'getMap', 500, 500, '35833', 33303208.311953, 5987573.4224589, 33303549.891254, 5987801.9980813, '2017-08-01 16:25:16', NULL),
(1, 1, '2017-08-10 09:41:19', 'getMap', 1198, 802, '35833', 33303142.481909, 5987506.6860337, 33303615.721298, 5987868.7345065, '2017-08-08 09:37:10', NULL),
(1, 1, '2017-08-10 09:42:17', 'getMap', 1198, 802, '35833', 33303142.481909, 5987506.6860337, 33303615.721298, 5987868.7345065, '2017-08-10 09:41:19', NULL),
(1, 1, '2017-08-10 09:42:39', 'getMap', 1198, 802, '35833', 33303394.332608, 5987685.9045421, 33303581.976157, 5987829.4599215, '2017-08-10 09:42:17', NULL),
(1, 1, '2017-08-10 09:45:59', 'getMap', 1198, 802, '35833', 33303394.332608, 5987685.9045419, 33303581.976157, 5987829.4599217, '2017-08-10 09:42:39', NULL),
(1, 1, '2017-08-10 10:07:41', 'getMap', 1198, 802, '35833', 33303381.787194, 5987685.9045419, 33303594.521571, 5987829.4599217, '2017-08-10 09:45:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `u_consume2comments`
--

CREATE TABLE `u_consume2comments` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `comment` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consume2layer`
--

CREATE TABLE `u_consume2layer` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `layer_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consumeALB`
--

CREATE TABLE `u_consumeALB` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `format` varchar(50) NOT NULL,
  `log_number` varchar(255) NOT NULL,
  `wz` enum('0','1') DEFAULT NULL,
  `numpages` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consumeALK`
--

CREATE TABLE `u_consumeALK` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `druckrahmen_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consumeCSV`
--

CREATE TABLE `u_consumeCSV` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `art` varchar(20) NOT NULL,
  `numdatasets` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consumeShape`
--

CREATE TABLE `u_consumeShape` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `layer_id` int(11) NOT NULL,
  `numdatasets` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_funktion2stelle`
--

CREATE TABLE `u_funktion2stelle` (
  `funktion_id` int(11) NOT NULL DEFAULT '0',
  `stelle_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_funktion2stelle`
--

INSERT INTO `u_funktion2stelle` (`funktion_id`, `stelle_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1);

-- --------------------------------------------------------

--
-- Table structure for table `u_funktionen`
--

CREATE TABLE `u_funktionen` (
  `id` int(11) NOT NULL,
  `bezeichnung` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_funktionen`
--

INSERT INTO `u_funktionen` (`id`, `bezeichnung`, `link`) VALUES
(1, 'ALB-Auszug 35', NULL),
(2, 'FestpunktDateiAktualisieren', NULL),
(3, 'FestpunktDateiUebernehmen', NULL),
(4, 'Antrag_loeschen', NULL),
(5, 'Nachweisanzeige_zum_Auftrag_hinzufuegen', NULL),
(6, 'Antrag_Aendern', NULL),
(7, 'FestpunkteSkizzenZuordnung_Senden', NULL),
(8, 'Nachweisanzeige_aus_Auftrag_entfernen', NULL),
(9, 'ohneWasserzeichen', NULL),
(10, 'Flurstueck_Anzeigen', NULL),
(11, 'Bauakteneinsicht', NULL),
(12, 'Namensuche', NULL),
(13, 'ALB-Auszug 40', NULL),
(14, 'Nachweisloeschen', NULL),
(15, 'ALB-Auszug 20', NULL),
(16, 'ALB-Auszug 25', NULL),
(17, 'Externer_Druck', NULL),
(18, 'Adressaenderungen', NULL),
(19, 'sendeFestpunktskizze', NULL),
(20, 'Nachweise_bearbeiten', NULL),
(21, 'ALB-Auszug 30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `u_groups`
--

CREATE TABLE `u_groups` (
  `id` int(11) NOT NULL,
  `Gruppenname` varchar(255) NOT NULL,
  `Gruppenname_low-german` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Gruppenname_english` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Gruppenname_polish` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Gruppenname_vietnamese` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `obergruppe` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `legendorder` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_groups`
--

INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_low-german`, `Gruppenname_english`, `Gruppenname_polish`, `Gruppenname_vietnamese`, `obergruppe`, `order`, `legendorder`) VALUES
(1, 'Hintergrundkarten', NULL, NULL, NULL, NULL, NULL, 1000, NULL),
(2, 'Verwaltungsgrenzen', NULL, NULL, NULL, NULL, NULL, 900, NULL),
(3, 'Kataster', NULL, NULL, NULL, NULL, NULL, 800, NULL),
(4, 'Umwelt', NULL, NULL, NULL, NULL, NULL, 700, NULL),
(5, 'Bauen', NULL, NULL, NULL, NULL, NULL, 600, NULL),
(6, 'Raumordnung', NULL, NULL, NULL, NULL, NULL, 500, NULL),
(7, 'Soziales', NULL, NULL, NULL, NULL, NULL, 400, NULL),
(8, 'Verkehr', NULL, NULL, NULL, NULL, NULL, 300, NULL),
(9, 'Administration', NULL, NULL, NULL, NULL, NULL, 100, NULL),
(10, 'Suchergebnis', NULL, NULL, NULL, NULL, NULL, 0, NULL),
(11, 'Wasserwirtschaft', NULL, NULL, NULL, NULL, NULL, 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `u_groups2rolle`
--

CREATE TABLE `u_groups2rolle` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_groups2rolle`
--

INSERT INTO `u_groups2rolle` (`user_id`, `stelle_id`, `id`, `status`) VALUES
(1, 1, 1, 1),
(1, 1, 4, 1),
(1, 1, 10, 1),
(1, 1, 11, 1),
(2, 2, 1, 1),
(2, 2, 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `u_labels2classes`
--

CREATE TABLE `u_labels2classes` (
  `class_id` int(11) NOT NULL DEFAULT '0',
  `label_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_menue2rolle`
--

CREATE TABLE `u_menue2rolle` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `menue_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_menue2rolle`
--

INSERT INTO `u_menue2rolle` (`user_id`, `stelle_id`, `menue_id`, `status`) VALUES
(1, 1, 41, 0),
(1, 1, 40, 0),
(1, 1, 39, 1),
(1, 1, 38, 0),
(1, 1, 37, 0),
(1, 1, 36, 0),
(1, 1, 35, 1),
(1, 1, 34, 0),
(1, 1, 33, 0),
(1, 1, 32, 0),
(1, 1, 31, 0),
(1, 1, 30, 0),
(1, 1, 29, 0),
(1, 1, 28, 0),
(1, 1, 27, 0),
(1, 1, 26, 0),
(1, 1, 25, 0),
(1, 1, 24, 0),
(1, 1, 23, 0),
(1, 1, 22, 0),
(1, 1, 21, 0),
(1, 1, 20, 0),
(1, 1, 19, 1),
(1, 1, 18, 0),
(1, 1, 17, 0),
(1, 1, 16, 1),
(1, 1, 15, 0),
(1, 1, 14, 0),
(1, 1, 13, 0),
(1, 1, 12, 0),
(1, 1, 11, 1),
(1, 1, 10, 0),
(1, 1, 9, 0),
(1, 1, 8, 0),
(1, 1, 7, 0),
(1, 1, 6, 0),
(1, 1, 5, 0),
(1, 1, 4, 1),
(1, 1, 3, 0),
(1, 1, 2, 0),
(1, 1, 1, 0),
(2, 2, 41, 0),
(2, 2, 38, 0),
(2, 2, 37, 0),
(2, 2, 30, 0),
(2, 2, 29, 0),
(2, 2, 28, 0),
(2, 2, 27, 0),
(2, 2, 26, 0),
(2, 2, 25, 0),
(2, 2, 24, 0),
(2, 2, 23, 0),
(2, 2, 22, 0),
(2, 2, 20, 0),
(2, 2, 19, 0),
(2, 2, 10, 0),
(2, 2, 9, 0),
(2, 2, 8, 0),
(2, 2, 7, 0),
(2, 2, 6, 0),
(2, 2, 5, 0),
(2, 2, 4, 0),
(2, 2, 3, 0),
(2, 2, 2, 0),
(1, 1, 42, 0);

-- --------------------------------------------------------

--
-- Table structure for table `u_menue2stelle`
--

CREATE TABLE `u_menue2stelle` (
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `menue_id` int(11) NOT NULL DEFAULT '0',
  `menue_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_menue2stelle`
--

INSERT INTO `u_menue2stelle` (`stelle_id`, `menue_id`, `menue_order`) VALUES
(1, 33, 40),
(1, 32, 39),
(1, 31, 38),
(1, 30, 37),
(1, 29, 36),
(1, 28, 35),
(1, 27, 34),
(1, 26, 33),
(1, 37, 32),
(1, 25, 31),
(1, 24, 30),
(1, 23, 29),
(1, 22, 28),
(1, 21, 27),
(1, 20, 26),
(1, 19, 25),
(1, 18, 24),
(1, 17, 23),
(1, 16, 22),
(1, 15, 21),
(1, 14, 20),
(1, 13, 19),
(1, 12, 18),
(1, 11, 17),
(1, 40, 16),
(1, 39, 15),
(1, 10, 14),
(1, 9, 13),
(1, 8, 12),
(1, 7, 11),
(1, 6, 10),
(1, 5, 9),
(1, 41, 8),
(1, 4, 7),
(1, 3, 6),
(1, 2, 5),
(1, 1, 4),
(1, 36, 3),
(1, 42, 2),
(1, 35, 1),
(1, 38, 0),
(2, 27, 22),
(2, 26, 21),
(2, 29, 20),
(2, 37, 19),
(2, 30, 18),
(2, 28, 17),
(2, 25, 16),
(2, 23, 15),
(2, 24, 14),
(2, 20, 13),
(2, 22, 12),
(2, 19, 11),
(2, 8, 10),
(2, 9, 9),
(2, 41, 8),
(2, 5, 7),
(4, 38, 0),
(4, 27, 15),
(4, 26, 14),
(4, 29, 13),
(4, 28, 10),
(4, 37, 12),
(4, 30, 11),
(4, 23, 8),
(4, 25, 9),
(4, 24, 7),
(4, 22, 4),
(4, 3, 2),
(4, 2, 1),
(4, 19, 3),
(4, 21, 6),
(4, 20, 5),
(2, 10, 6),
(2, 7, 5),
(2, 6, 4),
(2, 4, 3),
(2, 3, 2),
(2, 2, 1),
(2, 38, 0),
(1, 34, 41);

-- --------------------------------------------------------

--
-- Table structure for table `u_menues`
--

CREATE TABLE `u_menues` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `name_low-german` varchar(100) DEFAULT NULL,
  `name_english` varchar(100) CHARACTER SET cp1250 DEFAULT NULL,
  `name_polish` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_vietnamese` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `links` varchar(255) NOT NULL DEFAULT '',
  `onclick` text COMMENT 'JavaScript welches beim Klick auf den Menüpunkt ausgeführt werden soll.',
  `obermenue` int(11) NOT NULL DEFAULT '0',
  `menueebene` tinyint(4) NOT NULL DEFAULT '1',
  `target` varchar(10) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `button_class` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_menues`
--

INSERT INTO `u_menues` (`id`, `name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `onclick`, `obermenue`, `menueebene`, `target`, `order`, `title`, `button_class`) VALUES
(1, 'Stelle wählen', '', '', '', '', 'index.php?go=Stelle Wählen', '', 0, 1, '', 1, '', 'optionen'),
(2, 'Übersicht', '', '', '', '', 'index.php?go=Full_Extent', '', 0, 1, '', 2, '', 'gesamtansicht'),
(3, 'Karte', '', '', '', '', 'index.php', '', 0, 1, '', 3, '', 'karte'),
(4, 'Suchen', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 4, NULL, NULL),
(5, 'Layer-Suche', NULL, NULL, NULL, NULL, 'index.php?go=Layer-Suche', NULL, 4, 2, NULL, 0, NULL, NULL),
(6, 'Adressen', NULL, NULL, NULL, NULL, 'index.php?go=Adresse_Auswaehlen', NULL, 4, 2, NULL, 0, NULL, NULL),
(7, 'Flurstücke', NULL, NULL, NULL, NULL, 'index.php?go=Flurstueck_Auswaehlen', NULL, 4, 2, NULL, 0, NULL, NULL),
(8, 'Namen', NULL, NULL, NULL, NULL, 'index.php?go=Namen_Auswaehlen', NULL, 4, 2, NULL, 0, NULL, NULL),
(9, 'Metadaten', NULL, NULL, NULL, NULL, 'index.php?go=Metadaten_Auswaehlen', NULL, 4, 2, NULL, 0, NULL, NULL),
(10, 'Grundbuchblatt', NULL, NULL, NULL, NULL, 'index.php?go=Grundbuchblatt_Auswaehlen', NULL, 4, 2, NULL, 0, NULL, NULL),
(11, 'Stellenverwaltung', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 10, NULL, NULL),
(12, 'Stellen anlegen', NULL, NULL, NULL, NULL, 'index.php?go=Stelleneditor', NULL, 11, 2, NULL, 0, NULL, NULL),
(13, 'Stellen anzeigen', NULL, NULL, NULL, NULL, 'index.php?go=Stellen_Anzeigen', NULL, 11, 2, NULL, 0, NULL, NULL),
(14, 'Layer-Rechte', NULL, NULL, NULL, NULL, 'index.php?go=Layerattribut-Rechteverwaltung', NULL, 11, 2, NULL, 0, NULL, NULL),
(15, 'Filterverwaltung', NULL, NULL, NULL, NULL, 'index.php?go=Filterverwaltung', NULL, 11, 2, NULL, 0, NULL, NULL),
(16, 'Nutzerverwaltung', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 20, NULL, NULL),
(17, 'Nutzer anlegen', NULL, NULL, NULL, NULL, 'index.php?go=Benutzerdaten_Formular', NULL, 16, 2, NULL, 0, NULL, NULL),
(18, 'Nutzer anzeigen', NULL, NULL, NULL, NULL, 'index.php?go=Benutzerdaten_Anzeigen', NULL, 16, 2, NULL, 0, NULL, NULL),
(19, 'Layerverwaltung', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 30, NULL, NULL),
(20, 'Layer anzeigen', NULL, NULL, NULL, NULL, 'index.php?go=Layer_Anzeigen', NULL, 19, 2, NULL, 0, NULL, NULL),
(21, 'Layer erstellen', NULL, NULL, NULL, NULL, 'index.php?go=Layereditor', NULL, 19, 2, NULL, 0, NULL, NULL),
(22, 'Attribut-Editor', NULL, NULL, NULL, NULL, 'index.php?go=Attributeditor', NULL, 19, 2, NULL, 0, NULL, NULL),
(23, 'Style-u.Labeleditor', NULL, NULL, NULL, NULL, 'index.php?go=Style_Label_Editor', NULL, 19, 2, NULL, 0, NULL, NULL),
(24, 'neuer Datensatz', NULL, NULL, NULL, NULL, 'index.php?go=neuer_Layer_Datensatz', NULL, 19, 2, NULL, 0, NULL, NULL),
(25, 'Import/Export', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 40, NULL, NULL),
(26, 'WMS-Export', NULL, NULL, NULL, NULL, 'index.php?go=WMS_Export', NULL, 25, 2, NULL, 0, NULL, NULL),
(27, 'WMS-Import', NULL, NULL, NULL, NULL, 'index.php?go=WMS_Import', NULL, 25, 2, NULL, 0, NULL, NULL),
(28, 'Daten-Export', NULL, NULL, NULL, NULL, 'index.php?go=Daten_Export', NULL, 25, 2, NULL, 0, NULL, NULL),
(29, 'Shape-Anzeigen', NULL, NULL, NULL, NULL, 'index.php?go=SHP_Anzeigen', NULL, 25, 2, NULL, 0, NULL, NULL),
(30, 'Druckausgabe', NULL, NULL, NULL, NULL, 'index.php?go=Schnelle_Druckausgabe', NULL, 25, 2, '_blank', 0, NULL, 'schnelldruck'),
(31, 'Druckmanager', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 0, NULL, NULL),
(32, 'Kartendrucklayouteditor', NULL, NULL, NULL, NULL, 'index.php?go=Druckrahmen', NULL, 31, 2, NULL, 0, NULL, NULL),
(33, 'Datendrucklayouteditor', NULL, NULL, NULL, NULL, 'index.php?go=sachdaten_druck_editor', NULL, 31, 2, NULL, 0, NULL, NULL),
(34, 'Drucken', NULL, NULL, NULL, NULL, 'index.php?go=Druckausschnittswahl', NULL, 31, 2, NULL, 0, NULL, 'drucken'),
(35, 'Administration', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 0, NULL, NULL),
(36, 'Funktionen', NULL, NULL, NULL, NULL, 'index.php?go=Administratorfunktionen', NULL, 35, 2, NULL, 0, NULL, NULL),
(37, 'Layer-Export', NULL, NULL, NULL, NULL, 'index.php?go=Layer_Export', NULL, 25, 2, NULL, 0, NULL, NULL),
(38, 'Logout', '', '', '', '', 'index.php?go=logout', '', 0, 1, '', -1, '', 'logout'),
(39, 'Menüverwaltung', '', '', '', '', 'index.php?go=changemenue', '', 0, 1, '', 10, '', ''),
(40, 'Menüs Anzeigen', '', '', '', '', 'index.php?go=Menues_Anzeigen', '', 39, 2, '', 0, '', ''),
(41, 'Letztes Suchergebnis', '', '', '', '', 'index.php?go=get_last_query', '', 4, 2, '', 0, '', ''),
(42, 'Wasserrecht Deploy', '', '', '', '', 'index.php?go=wasserrecht_deploy', '', 35, 2, '', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `u_rolle2used_class`
--

CREATE TABLE `u_rolle2used_class` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `class_id` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_rolle2used_layer`
--

CREATE TABLE `u_rolle2used_layer` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `layer_id` int(11) NOT NULL DEFAULT '0',
  `aktivStatus` enum('0','1','2') NOT NULL DEFAULT '0',
  `queryStatus` enum('0','1','2') NOT NULL DEFAULT '0',
  `gle_view` tinyint(1) DEFAULT NULL,
  `showclasses` tinyint(1) NOT NULL DEFAULT '1',
  `logconsume` enum('0','1') NOT NULL DEFAULT '0',
  `transparency` tinyint(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_rolle2used_layer`
--

INSERT INTO `u_rolle2used_layer` (`user_id`, `stelle_id`, `layer_id`, `aktivStatus`, `queryStatus`, `gle_view`, `showclasses`, `logconsume`, `transparency`) VALUES
(1, 1, 1, '1', '0', NULL, 1, '', NULL),
(1, 1, 2, '1', '1', 1, 1, '0', NULL),
(1, 1, 3, '0', '0', NULL, 1, '0', NULL),
(2, 2, 9, '0', '0', NULL, 1, '0', NULL),
(1, 1, 6, '0', '0', NULL, 1, '0', NULL),
(2, 2, 1, '1', '1', NULL, 1, '0', 51),
(1, 1, 9, '0', '0', 1, 1, '0', NULL),
(1, 1, 10, '0', '0', NULL, 1, '0', NULL),
(2, 2, 25, '0', '0', NULL, 1, '0', NULL),
(1, 1, 11, '0', '0', NULL, 1, '0', NULL),
(1, 1, 12, '0', '0', NULL, 1, '0', NULL),
(1, 1, 13, '0', '0', NULL, 1, '0', NULL),
(1, 1, 14, '0', '0', NULL, 1, '0', NULL),
(1, 1, 16, '0', '0', NULL, 1, '0', NULL),
(2, 2, 2, '0', '0', NULL, 1, '0', NULL),
(1, 1, 17, '0', '0', NULL, 1, '0', NULL),
(1, 1, 18, '0', '0', NULL, 1, '0', NULL),
(1, 1, 19, '0', '0', NULL, 1, '0', NULL),
(1, 1, 20, '0', '0', NULL, 1, '0', NULL),
(1, 1, 21, '0', '0', NULL, 1, '0', NULL),
(1, 1, 22, '0', '0', 0, 1, '0', NULL),
(1, 1, 23, '0', '0', NULL, 1, '0', NULL),
(1, 1, 24, '0', '0', NULL, 1, '0', NULL),
(1, 1, 25, '0', '0', 1, 1, '0', NULL),
(1, 1, 26, '0', '0', NULL, 1, '0', NULL),
(1, 1, 31, '0', '0', NULL, 1, '0', NULL),
(1, 1, 32, '0', '0', NULL, 1, '0', NULL),
(1, 1, 29, '0', '0', NULL, 1, '0', NULL),
(1, 1, 30, '0', '0', NULL, 1, '0', NULL),
(1, 1, 33, '0', '0', NULL, 1, '0', NULL),
(1, 1, 34, '0', '0', NULL, 1, '0', NULL),
(1, 1, 35, '0', '0', NULL, 1, '0', NULL),
(1, 1, 36, '0', '0', NULL, 1, '0', NULL),
(1, 1, 37, '0', '0', NULL, 1, '0', NULL),
(1, 1, 38, '0', '0', NULL, 1, '0', NULL),
(1, 1, 39, '0', '0', NULL, 1, '0', NULL),
(1, 1, 40, '0', '0', NULL, 1, '0', NULL),
(1, 1, 41, '0', '0', NULL, 1, '0', NULL),
(1, 1, 42, '0', '0', NULL, 1, '0', NULL),
(2, 2, 33, '0', '0', NULL, 1, '0', NULL),
(2, 2, 38, '0', '0', NULL, 1, '0', NULL),
(2, 2, 43, '0', '0', NULL, 1, '0', NULL),
(2, 2, 3, '0', '0', NULL, 1, '0', NULL),
(2, 2, 12, '0', '0', NULL, 1, '0', NULL),
(2, 2, 23, '0', '0', NULL, 1, '0', NULL),
(2, 2, 41, '0', '0', NULL, 1, '0', NULL),
(2, 2, 17, '0', '0', NULL, 1, '0', NULL),
(2, 2, 39, '0', '0', NULL, 1, '0', NULL),
(2, 2, 24, '0', '0', NULL, 1, '0', NULL),
(2, 2, 34, '0', '0', NULL, 1, '0', NULL),
(2, 2, 37, '0', '0', NULL, 1, '0', NULL),
(2, 2, 36, '0', '0', NULL, 1, '0', NULL),
(2, 2, 16, '0', '0', NULL, 1, '0', NULL),
(2, 2, 6, '0', '0', NULL, 1, '0', NULL),
(2, 2, 42, '0', '0', NULL, 1, '0', NULL),
(2, 2, 40, '0', '0', NULL, 1, '0', NULL),
(2, 2, 30, '0', '0', NULL, 1, '0', NULL),
(2, 2, 10, '0', '0', NULL, 1, '0', NULL),
(2, 2, 11, '0', '0', NULL, 1, '0', NULL),
(2, 2, 35, '0', '0', NULL, 1, '0', NULL),
(2, 2, 21, '0', '0', NULL, 1, '0', NULL),
(2, 2, 18, '0', '0', NULL, 1, '0', NULL),
(2, 2, 26, '0', '0', NULL, 1, '0', NULL),
(2, 2, 29, '0', '0', NULL, 1, '0', NULL),
(2, 2, 19, '0', '0', NULL, 1, '0', NULL),
(2, 2, 31, '0', '0', NULL, 1, '0', NULL),
(2, 2, 22, '0', '0', NULL, 1, '0', NULL),
(2, 2, 32, '0', '0', NULL, 1, '0', NULL),
(2, 2, 20, '0', '0', NULL, 1, '0', NULL),
(2, 2, 14, '0', '0', NULL, 1, '0', NULL),
(2, 2, 15, '0', '0', NULL, 1, '0', NULL),
(2, 2, 44, '0', '0', NULL, 1, '0', NULL),
(2, 2, 45, '0', '0', NULL, 1, '0', NULL),
(2, 2, 48, '0', '0', NULL, 1, '0', NULL),
(2, 2, 46, '0', '0', NULL, 1, '0', NULL),
(2, 2, 47, '0', '0', NULL, 1, '0', NULL),
(2, 2, 49, '0', '0', NULL, 1, '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `u_styles2classes`
--

CREATE TABLE `u_styles2classes` (
  `class_id` int(11) NOT NULL DEFAULT '0',
  `style_id` int(11) NOT NULL DEFAULT '0',
  `drawingorder` int(11) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `u_styles2classes`
--

INSERT INTO `u_styles2classes` (`class_id`, `style_id`, `drawingorder`) VALUES
(1, 1, 1),
(2, 2, 1),
(8, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `zwischenablage`
--

CREATE TABLE `zwischenablage` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `oid` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`Class_ID`),
  ADD KEY `Layer_ID` (`Layer_ID`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `datatypes`
--
ALTER TABLE `datatypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `datatype_attributes`
--
ALTER TABLE `datatype_attributes`
  ADD PRIMARY KEY (`datatype_id`,`name`);

--
-- Indexes for table `datendrucklayouts`
--
ALTER TABLE `datendrucklayouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ddl2freilinien`
--
ALTER TABLE `ddl2freilinien`
  ADD PRIMARY KEY (`ddl_id`,`line_id`);

--
-- Indexes for table `ddl2freitexte`
--
ALTER TABLE `ddl2freitexte`
  ADD PRIMARY KEY (`ddl_id`,`freitext_id`);

--
-- Indexes for table `ddl2stelle`
--
ALTER TABLE `ddl2stelle`
  ADD PRIMARY KEY (`stelle_id`,`ddl_id`);

--
-- Indexes for table `ddl_elemente`
--
ALTER TABLE `ddl_elemente`
  ADD PRIMARY KEY (`ddl_id`,`name`);

--
-- Indexes for table `druckausschnitte`
--
ALTER TABLE `druckausschnitte`
  ADD PRIMARY KEY (`id`,`stelle_id`,`user_id`);

--
-- Indexes for table `druckfreibilder`
--
ALTER TABLE `druckfreibilder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `druckfreilinien`
--
ALTER TABLE `druckfreilinien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `druckfreitexte`
--
ALTER TABLE `druckfreitexte`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `druckrahmen`
--
ALTER TABLE `druckrahmen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `druckrahmen2freibilder`
--
ALTER TABLE `druckrahmen2freibilder`
  ADD PRIMARY KEY (`druckrahmen_id`,`freibild_id`);

--
-- Indexes for table `druckrahmen2freitexte`
--
ALTER TABLE `druckrahmen2freitexte`
  ADD PRIMARY KEY (`druckrahmen_id`,`freitext_id`);

--
-- Indexes for table `druckrahmen2stelle`
--
ALTER TABLE `druckrahmen2stelle`
  ADD PRIMARY KEY (`stelle_id`,`druckrahmen_id`);

--
-- Indexes for table `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`Label_ID`);

--
-- Indexes for table `layer`
--
ALTER TABLE `layer`
  ADD PRIMARY KEY (`Layer_ID`),
  ADD KEY `Gruppe` (`Gruppe`);

--
-- Indexes for table `layer_attributes`
--
ALTER TABLE `layer_attributes`
  ADD PRIMARY KEY (`layer_id`,`name`);

--
-- Indexes for table `layer_attributes2stelle`
--
ALTER TABLE `layer_attributes2stelle`
  ADD PRIMARY KEY (`layer_id`,`attributename`,`stelle_id`);

--
-- Indexes for table `layer_parameter`
--
ALTER TABLE `layer_parameter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`component`,`type`,`filename`);

--
-- Indexes for table `polygon`
--
ALTER TABLE `polygon`
  ADD PRIMARY KEY (`polygon_id`);

--
-- Indexes for table `referenzkarten`
--
ALTER TABLE `referenzkarten`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `rolle`
--
ALTER TABLE `rolle`
  ADD PRIMARY KEY (`user_id`,`stelle_id`);

--
-- Indexes for table `rollenlayer`
--
ALTER TABLE `rollenlayer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rolle_csv_attributes`
--
ALTER TABLE `rolle_csv_attributes`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`name`);

--
-- Indexes for table `rolle_saved_layers`
--
ALTER TABLE `rolle_saved_layers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_attributes2rolle`
--
ALTER TABLE `search_attributes2rolle`
  ADD PRIMARY KEY (`name`,`user_id`,`stelle_id`,`layer_id`,`attribute`,`searchmask_number`);

--
-- Indexes for table `stelle`
--
ALTER TABLE `stelle`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `styles`
--
ALTER TABLE `styles`
  ADD PRIMARY KEY (`Style_ID`);

--
-- Indexes for table `used_layer`
--
ALTER TABLE `used_layer`
  ADD PRIMARY KEY (`Stelle_ID`,`Layer_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `u_attributfilter2used_layer`
--
ALTER TABLE `u_attributfilter2used_layer`
  ADD PRIMARY KEY (`Stelle_ID`,`Layer_ID`,`attributname`);

--
-- Indexes for table `u_consume`
--
ALTER TABLE `u_consume`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indexes for table `u_consume2comments`
--
ALTER TABLE `u_consume2comments`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indexes for table `u_consume2layer`
--
ALTER TABLE `u_consume2layer`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`,`layer_id`);

--
-- Indexes for table `u_consumeALB`
--
ALTER TABLE `u_consumeALB`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`,`log_number`);

--
-- Indexes for table `u_consumeALK`
--
ALTER TABLE `u_consumeALK`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indexes for table `u_consumeCSV`
--
ALTER TABLE `u_consumeCSV`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indexes for table `u_consumeShape`
--
ALTER TABLE `u_consumeShape`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indexes for table `u_funktion2stelle`
--
ALTER TABLE `u_funktion2stelle`
  ADD PRIMARY KEY (`funktion_id`,`stelle_id`);

--
-- Indexes for table `u_funktionen`
--
ALTER TABLE `u_funktionen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `u_groups`
--
ALTER TABLE `u_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `u_groups2rolle`
--
ALTER TABLE `u_groups2rolle`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `user_id_3` (`user_id`);

--
-- Indexes for table `u_labels2classes`
--
ALTER TABLE `u_labels2classes`
  ADD PRIMARY KEY (`class_id`,`label_id`);

--
-- Indexes for table `u_menue2rolle`
--
ALTER TABLE `u_menue2rolle`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`menue_id`);

--
-- Indexes for table `u_menue2stelle`
--
ALTER TABLE `u_menue2stelle`
  ADD PRIMARY KEY (`stelle_id`,`menue_id`);

--
-- Indexes for table `u_menues`
--
ALTER TABLE `u_menues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `u_rolle2used_class`
--
ALTER TABLE `u_rolle2used_class`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`class_id`);

--
-- Indexes for table `u_rolle2used_layer`
--
ALTER TABLE `u_rolle2used_layer`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`layer_id`);

--
-- Indexes for table `u_styles2classes`
--
ALTER TABLE `u_styles2classes`
  ADD PRIMARY KEY (`class_id`,`style_id`);

--
-- Indexes for table `zwischenablage`
--
ALTER TABLE `zwischenablage`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`layer_id`,`oid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `Class_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `datatypes`
--
ALTER TABLE `datatypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `datendrucklayouts`
--
ALTER TABLE `datendrucklayouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `druckausschnitte`
--
ALTER TABLE `druckausschnitte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `druckfreibilder`
--
ALTER TABLE `druckfreibilder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `druckfreilinien`
--
ALTER TABLE `druckfreilinien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `druckfreitexte`
--
ALTER TABLE `druckfreitexte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `druckrahmen`
--
ALTER TABLE `druckrahmen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `labels`
--
ALTER TABLE `labels`
  MODIFY `Label_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `layer`
--
ALTER TABLE `layer`
  MODIFY `Layer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `layer_parameter`
--
ALTER TABLE `layer_parameter`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `polygon`
--
ALTER TABLE `polygon`
  MODIFY `polygon_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `referenzkarten`
--
ALTER TABLE `referenzkarten`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `rollenlayer`
--
ALTER TABLE `rollenlayer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rolle_saved_layers`
--
ALTER TABLE `rolle_saved_layers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stelle`
--
ALTER TABLE `stelle`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `styles`
--
ALTER TABLE `styles`
  MODIFY `Style_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `u_funktionen`
--
ALTER TABLE `u_funktionen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `u_groups`
--
ALTER TABLE `u_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `u_menues`
--
ALTER TABLE `u_menues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;