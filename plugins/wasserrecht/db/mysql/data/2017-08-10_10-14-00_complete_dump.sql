-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: 172.17.0.2:3306
-- Generation Time: Oct 25, 2017 at 09:52 AM
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
(2, 'FisWrV-WRe Anlagen', NULL, NULL, NULL, NULL, '', 0, 11, 'SELECT a.id AS anlage_id, a.name, a.klasse, a.zustaend_stalu, a.zustaend_uwb, a.abwasser_koerperschaft, a.trinkwasser_koerperschaft, \'\' AS wasserrechtliche_zulassungen, true AS aktuell, \'\' AS gewaesserbenutzungen,  a.betreiber, a.anlage_bearbeiter_name , a.anlage_bearbeiter_stelle, a.anlage_bearbeiter_datum, a.kommentar, a.objektid_geodin, a.the_geom FROM fiswrv_anlagen a WHERE 1=1', 'fiswrv_anlagen', 0, 'the_geom from (select oid, * from wasserrecht.fiswrv_anlagen) as foo using unique oid using srid=35833', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(3, 'Anlagenklasse', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_anlagen_klasse WHERE 1=1', 'fiswrv_anlagen_klasse', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(6, 'Körperschaft', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name,art FROM fiswrv_koerperschaft WHERE 1=1', 'fiswrv_koerperschaft', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(9, 'FisWrV-WRe Personen', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT a.id AS personen_id, a.typ, a.klasse, a.status, d.bezeichnung, a.name, a.abkuerzung, a.namenszusatz, a.adresse as adress_id, COALESCE(b.strasse,\'\') ||\'  \'|| COALESCE(b.hausnummer,\'\') AS strasse_hausnummer, COALESCE(b.plz::text,\'\') ||\'  \'|| COALESCE(b.ort,\'\') AS plz_ort,  a.register_amtsgericht, a.register_nummer,  a.telefon, a.fax, a.email, a.zimmer, a.verwendungszweck_wee, a.konto as konto_id, COALESCE(c.name,\'\') AS kontoname, COALESCE(c.iban,\'\') AS iban, COALESCE(c.bic,\'\') AS bic, COALESCE(c.verwendungszweck,\'\') AS verwendungszweck, COALESCE(c.personenkonto,\'\') AS personenkonto, COALESCE(c.kassenzeichen,\'\') AS kassenzeichen, a.behoerde, a.wrzaussteller, a.wrzadressat, a.wrzrechtsnachfolger, CASE when a.betreiber = \'ja\' then \'Betreiber\' ELSE \'false\' end AS betreiber, a.betreiber AS betreiber_id, CASE when a.bearbeiter  = \'ja\' then \'Bearbeiter\' ELSE \'false\' end AS bearbeiter, a.bearbeiter AS bearbeiter_id,  a.abwasser_koerperschaft, a.trinkwasser_koerperschaft, a.weeerklaerer, a.kommentar,  true AS aktuell, \'\' AS per_wrz, \'\' AS per_wrz_ben FROM fiswrv_personen a LEFT JOIN fiswrv_personen_bezeichnung d ON a.id=d.id LEFT JOIN fiswrv_adresse b ON a.adresse=b.id LEFT JOIN fiswrv_konto c ON a.konto=c.id WHERE 1=1', 'fiswrv_personen', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 3, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(10, 'Personen_Klasse', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_personen_klasse WHERE 1=1', 'fiswrv_personen_klasse', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(11, 'Personen_Status', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_personen_status WHERE 1=1', 'fiswrv_personen_status', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(12, 'Adresse', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id as adress_id, strasse, hausnummer, plz, ort FROM fiswrv_adresse WHERE 1=1', 'fiswrv_adresse', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(13, 'Personen_Typ', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_personen_typ WHERE 1=1', 'fiswrv_personen_typ', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(14, 'Weeerklaerer', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_weeerklaerer WHERE 1=1', 'fiswrv_weeerklaerer ', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(16, 'Konto', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id as konto_id, name, iban, bic, bankname, verwendungszweck, personenkonto, kassenzeichen FROM fiswrv_konto WHERE 1=1', 'fiswrv_konto', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(17, 'Behoerde', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, abkuerzung, status, adresse as adress_id, art, konto as konto_id FROM fiswrv_behoerde WHERE 1=1', 'fiswrv_behoerde', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(20, 'Wasserrechtliche_Zulassungen_Status', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_status WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_status', 0, '', 'wasserrecht', '', '', '', '', 'id', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(24, 'Dokument', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, pfad, document FROM fiswrv_dokument WHERE 1=1', 'fiswrv_dokument', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(25, 'FisWrV-WRe WrZ', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT COALESCE(d.name,\'\') AS anlage_klasse, a.anlage AS anlage_id, b.name AS anlage_anzeige, a.id as wrz_id, COALESCE(b.id::text,\'\') ||\' -\'|| COALESCE(a.id::text,\'\') AS wrz_wid, a.ausstellbehoerde, \'\' AS ausstellbehoerde_link, a.adressat AS personen_id, \'\' AS adressat_link, COALESCE(e.name,\'\') AS adressat_name, COALESCE(e.namenszusatz,\'\') AS adressat_namenszusatz, COALESCE(f.strasse,\'\') ||\'  \'|| COALESCE(f.hausnummer,\'\') AS adressat_strasse_hausnummer, COALESCE(f.plz::text,\'\') ||\'  \'|| COALESCE(f.ort,\'\') AS adressat_plz_ort, a.bearbeiter, \'\' AS bearbeiter_link, COALESCE(g.name,\'\') AS bearbeiter_name, COALESCE(g.namenszusatz,\'\') AS bearbeiter_namenszusatz, COALESCE(h.strasse,\'\') ||\'  \'|| COALESCE(h.hausnummer,\'\') AS bearbeiter_strasse_hausnummer, COALESCE(h.plz::text,\'\') ||\'  \'|| COALESCE(h.ort,\'\') AS bearbeiter_plz_ort, COALESCE(g.zimmer,\'\') AS bearbeiter_zimmer, COALESCE(g.telefon,\'\') AS bearbeiter_telefon, COALESCE(g.fax,\'\') AS bearbeiter_fax, COALESCE(g.email,\'\') AS bearbeiter_email,  c.bezeichnung, a.typus, a.bearbeiterzeichen, a.aktenzeichen, a.regnummer, a.bergamt_aktenzeichen, a.ort,  a.datum, a.fassung_auswahl, a.fassung_nummer, a.fassung_typus, a.fassung_bearbeiterzeichen, a.fassung_aktenzeichen, a.fassung_datum, a.gueltig_seit, a.befristet_bis, a.status, a.aktuell, CASE WHEN a.befristet_bis < current_date THEN \'nein\' ELSE \'ja\' END AS wirksam, a.ungueltig_seit, a.ungueltig_aufgrund, a.datum_postausgang,a.datum_bestand_mat, a.datum_bestand_form, a.dokument AS dokument, a.nachfolger AS nachfolger, a.vorgaenger AS vorgaenger, a.freigegeben, \'\' AS wrz_ben  FROM fiswrv_wasserrechtliche_zulassungen a LEFT JOIN fiswrv_wasserrechtliche_zulassungen_bezeichnung c ON a.id = c.id LEFT JOIN fiswrv_anlagen b ON a.anlage=b.id LEFT JOIN fiswrv_anlagen_klasse d ON b.klasse=d.id LEFT JOIN fiswrv_personen e ON a.adressat=e.id LEFT JOIN fiswrv_adresse f ON e.adresse=f.id LEFT JOIN fiswrv_personen g ON a.bearbeiter=g.id LEFT JOIN fiswrv_adresse h ON g.adresse=h.id WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen', 0, '', 'wasserrecht', '/var/www/data/wasserrecht/wrz/', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(26, 'Wasserrechtliche_Zulassungen_Typus', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_typus WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_typus', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(48, 'Wasserrechtliche_Zulassungen_Fassung_Typus', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_fassung_typus WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 3, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(30, 'Ort', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_ort WHERE 1=1', 'fiswrv_ort', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(31, 'Wasserrechtliche_Zulassungen_Fassung_Auswahl', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_fassung_auswahl WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(32, 'Wasserrechtliche_Zulassungen_Ungueltigkeit_Aufgrund', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(33, 'FisWrV-WRe Gewässerbenutzungen', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT f.name AS anlage_anzeige, a.anlage AS anlage_id, b.id as gwb_id, b.wasserrechtliche_zulassungen as wrz_id, \'\' AS wasserrechtliche_zulassungen_link, b.kennnummer, b.wasserbuchnummer, c.bezeichnung, b.freitext_art, b.art, b.freitext_zweck, b.zweck, b.umfang_entnahme, a.adressat as personen_id, a.aktuell, \'\' AS gewaesserbenutzungen_lage FROM fiswrv_gewaesserbenutzungen b LEFT JOIN fiswrv_gewaesserbenutzungen_bezeichnung c ON c.id = b.id LEFT JOIN fiswrv_wasserrechtliche_zulassungen a ON b.wasserrechtliche_zulassungen = a.id  LEFT JOIN fiswrv_anlagen f ON a.anlage=f.id WHERE 1=1', 'fiswrv_gewaesserbenutzungen', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(34, 'Gewaesserbenutzungen_Art', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_gewaesserbenutzungen_art WHERE 1=1', 'fiswrv_gewaesserbenutzungen_art', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(36, 'Gewaesserbenutzungen_Zweck', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, nummer, name FROM fiswrv_gewaesserbenutzungen_zweck WHERE 1=1', 'fiswrv_gewaesserbenutzungen_zweck', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(37, 'Gewaesserbenutzungen_Umfang_Entnahme', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, max_ent_s, max_ent_h, max_ent_d, max_ent_w, max_ent_m,        max_ent_a, max_ent_wee, max_ent_wee_beschreib, max_ent_wb, max_ent_wb_beschreib,        max_ent_frei, max_ent_frei_beschreib, freitext FROM fiswrv_gewaesserbenutzungen_umfang_entnahme WHERE 1=1', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(38, 'Gewaesserbenutzungen_Lage', NULL, NULL, NULL, NULL, '', 0, 11, 'SELECT id, name, gewaesserbenutzungen as gwb_id, the_geo FROM fiswrv_gewaesserbenutzungen_lage WHERE 1=1', 'fiswrv_gewaesserbenutzungen_lage', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(39, 'Betriebszustand', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_betriebszustand WHERE 1=1', 'fiswrv_betriebszustand', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(40, 'Messtischblatt', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, nummer FROM fiswrv_messtischblatt WHERE 1=1', 'fiswrv_messtischblatt ', 0, '', 'wasserrecht', '', '', '', '', 'nummer', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(41, 'Archivnummer', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, nummer FROM fiswrv_archivnummer WHERE 1=1', 'fiswrv_archivnummer', 0, '', 'wasserrecht', '', '', '', '', 'nummer', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(42, 'Körperschaftsart', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_koerperschaft_art WHERE 1=1', 'fiswrv_koerperschaft_art', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(43, 'Mengenbestimmung', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_mengenbestimmung WHERE 1=1', 'fiswrv_mengenbestimmung ', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(44, 'Teilgewaesserbenutzungen_Art', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name FROM fiswrv_teilgewaesserbenutzungen_art WHERE 1=1', 'fiswrv_teilgewaesserbenutzungen_art', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(45, 'Gewaesserbenutzungen_Art_Benutzung', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, abkuerzung FROM fiswrv_gewaesserbenutzungen_art_benutzung WHERE 1=1', 'fiswrv_gewaesserbenutzungen_art_benutzung', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(46, 'Teilgewaesserbenutzungen', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, art, zweck, umfang, wiedereinleitung_nutzer, wiedereinleitung_bearbeiter,        mengenbestimmung, art_benutzung, befreiungstatbestaende, entgeltsatz,        teilgewaesserbenutzungen_art, gewaesserbenutzungen FROM fiswrv_teilgewaesserbenutzungen WHERE 1=1', 'fiswrv_teilgewaesserbenutzungen', 0, '', 'wasserrecht', '', '', '', '', 'id', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(47, 'Gewaesserbenutzungen_WEE_Satz', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, jahr, satz_ow, satz_gw FROM fiswrv_gewaesserbenutzungen_wee_satz WHERE 1=1', 'fiswrv_gewaesserbenutzungen_wee_satz', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', ''),
(49, 'Behoerde_Art', NULL, NULL, NULL, NULL, '', 5, 11, 'SELECT id, name, abkuerzung FROM fiswrv_behoerde_art WHERE 1=1', 'fiswrv_behoerde_art', 0, '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', 0, 'host=pgsql user=kvwmap_wr password=gdi0DVZ dbname=kvwmapsp_wr', '', 6, '', '', 'id', NULL, 10, 'pixels', '35833', '', '1', NULL, 100, NULL, NULL, NULL, '', NULL, 'epsg:35833', '', '', '', 60, '', '', '', '', '0', '0', '', '', '', '', '0', 1, '', '');

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
(2, 'anlage_id', 'id', 'fiswrv_anlagen', 'a', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'Primärschlüssel ANL', NULL, NULL, NULL, NULL, '', 'Stammdaten Anlage', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(2, 'name', 'name', 'fiswrv_anlagen', 'a', 'varchar', '', '', 0, 255, NULL, '', 'Text', '', '<strong>Name ANL</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD! Name der wasserrechtlich relevanten Anlage. (Beispiel: Kläranlage Musterow, Wasserwerk Musterin, Holzwerk Musterstadt)', 'Stammdaten Anlage', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(2, 'klasse', 'klasse', 'fiswrv_anlagen', 'a', 'int4', '', '', 0, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from fiswrv_anlagen_klasse;layer_id=3 embedded', '<strong>Klasse ANL</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD! Auswahlfeld für die Klasse der wasserrechtlich relevanten Anlage.  [Neue Klassen müssen beim Admin beantragt werden]', 'Stammdaten Anlage', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(2, 'zustaend_uwb', 'zustaend_uwb', 'fiswrv_anlagen', 'a', 'int4', '', '', 0, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, abkuerzung AS output FROM fiswrv_behoerde a WHERE a.art=1;layer_id=17 embedded', '<strong>Amtsbereich UWB</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD! AUTOVERVOLLSTÄNDIGKEITSFELD für fir Untere Wasserbehörde in deren Amtsbereich die Anlage liegt.\r\n\r\nHILFE: % und _ entsprechen * und ? als Platzhalter bei der Namenssuche.', 'Zuständigkeiten', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(9, 'behoerde', 'behoerde', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, abkuerzung as output from wasserrecht.fiswrv_behoerde;layer_id=17 embedded', 'Behörde', NULL, NULL, NULL, NULL, 'Behörde deren Mitarbeiter die betreffende Person ist.', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 25, 0, 0),
(2, 'zustaend_stalu', 'zustaend_stalu', 'fiswrv_anlagen', 'a', 'int4', '', '', 0, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, abkuerzung AS output FROM fiswrv_behoerde a WHERE a.art=2;layer_id=17 embedded', '<strong>Amtsbereich StALU</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD! AUTOVERVOLLSTÄNDIGKEITSFELD für das Staatliche Amt für Landwirtschaft und Umwelt in dessen Amtsbereich die Anlage liegt.\r\n\r\nHILFE: % und _ entsprechen * und ? als Platzhalter bei der Namenssuche.', 'Zuständigkeiten', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(9, 'bearbeiter_id', 'bearbeiter', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 10, NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Bearbeiter', NULL, NULL, NULL, NULL, '', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 32, 0, 0),
(2, 'objektid_geodin', 'objektid_geodin', 'fiswrv_anlagen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Geometrie', 0, 2, NULL, -1, -1, NULL, 15, 0, 0),
(9, 'verwendungszweck_wee', 'verwendungszweck_wee', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Verwendungszweck WEE', NULL, NULL, NULL, NULL, 'Personenbezogeneer Verwendungszweck für die Überweisung des Wasserentnahmeentgelts.', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 17, 0, 0),
(2, 'abwasser_koerperschaft', 'abwasser_koerperschaft', 'fiswrv_anlagen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM\r\n  fiswrv_koerperschaft z LEFT JOIN	\r\n  fiswrv_koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id=2\r\nGROUP BY\r\n  z.id, z.name ;layer_id=6 embedded', 'Abwasserbeseitigungspflichtige Körperschaft', NULL, NULL, NULL, NULL, '', 'Zuständigkeiten', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(2, 'trinkwasser_koerperschaft', 'trinkwasser_koerperschaft', 'fiswrv_anlagen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM\r\n  fiswrv_koerperschaft z LEFT JOIN	\r\n  fiswrv_koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id=1\r\nGROUP BY\r\n  z.id, z.name ;layer_id=6 embedded', 'Träger der öffentlichen Wasserversorgung', NULL, NULL, NULL, NULL, '', 'Zuständigkeiten', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(2, 'kommentar', 'kommentar', 'fiswrv_anlagen', 'a', 'text', '', '', 1, NULL, NULL, '', 'Textfeld', '', 'Kommentar ans LUNG', NULL, NULL, NULL, NULL, '', 'letzte Änderung', 0, 0, NULL, 0, 0, NULL, 14, 0, 0),
(2, 'the_geom', 'the_geom', 'fiswrv_anlagen', 'a', 'geometry', 'POINT', '', 1, NULL, NULL, '', 'Geometrie', '', 'Geometrie', NULL, NULL, NULL, NULL, '', 'Geometrie', 0, 0, NULL, 0, 0, NULL, 16, 0, 0),
(3, 'id', 'id', 'fiswrv_anlagen_klasse', 'fiswrv_anlagen_klasse', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(3, 'name', 'name', 'fiswrv_anlagen_klasse', 'fiswrv_anlagen_klasse', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(6, 'id', 'id', 'fiswrv_koerperschaft', 'fiswrv_koerperschaft', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(6, 'name', 'name', 'fiswrv_koerperschaft', 'fiswrv_koerperschaft', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(9, 'klasse', 'klasse', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen_klasse;layer_id=10 embedded', 'Klasse', NULL, NULL, NULL, NULL, '', 'Systemdaten', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(9, 'status', 'status', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen_status;layer_id=11 embedded', 'Status', NULL, NULL, NULL, NULL, '', 'Systemdaten', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(12, 'adress_id', 'id', 'fiswrv_adresse', 'fiswrv_adresse', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(9, 'typ', 'typ', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen_typ;layer_id=13 embedded', 'Körperschaft', NULL, NULL, NULL, NULL, 'Auswahlfeld. Handelt es sich bei der Person um eine Körperschaft des öffentlichen Rechts oder des Privatrechts.', 'Systemdaten', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(9, 'wrzadressat', 'wrzadressat', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 10, NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Adressat WrZ', NULL, NULL, NULL, NULL, 'Die Person ist Adressat einer Wasserrechtlichen Zulassung.', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 27, 0, 0),
(9, 'wrzrechtsnachfolger', 'wrzrechtsnachfolger', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 10, NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Rechtsnachfolger WrZ', NULL, NULL, NULL, NULL, 'Die Person ist Rechtsnachfolger eines Adressaten einer Wasserrechtlichen Zulassung.', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 28, 0, 0),
(9, 'betreiber', '', 'fiswrv_personen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Gruppen', 0, 0, NULL, -1, -1, NULL, 29, 0, 0),
(9, 'bearbeiter', '', 'fiswrv_personen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', 'Gruppen', 0, 0, NULL, -1, -1, NULL, 31, 0, 0),
(9, 'weeerklaerer', 'weeerklaerer', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_weeerklaerer;layer_id=14 embedded', 'Erklärer WEE', NULL, NULL, NULL, NULL, 'Die Person hat eine Erklärung zum Wassernahmeentgelt abgegeben.', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 35, 0, 0),
(9, 'telefon', 'telefon', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 50, NULL, '', 'Text', '', 'Telefonnummer', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 13, 0, 0),
(9, 'fax', 'fax', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 50, NULL, '', 'Text', '', 'Faxnummer', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 14, 0, 0),
(9, 'email', 'email', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 50, NULL, '', 'Text', '', 'E-Mail-Adresse', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 15, 0, 0),
(9, 'abkuerzung', 'abkuerzung', 'fiswrv_personen', 'a', 'varchar', '', '', 0, 30, NULL, '', 'Text', '', '<strong>Abkürzung</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD!  Gängie und möglichst eindeutige Abkürzung oder Kurzbezeichnung des Namens der Person (z.B. LUNG, StALU MM, EURAWASSER, REWA etc.)', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(9, 'bezeichnung', 'bezeichnung', 'fiswrv_personen_bezeichnung', 'd', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', 'Systemdaten', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(9, 'wrzaussteller', 'wrzaussteller', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 10, NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Aussteller WrZ', NULL, NULL, NULL, NULL, 'Die Person kann Wasserrechtliche Zulassungen erteilen. [Kann nur vom Admin bearbeitet werden]', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 26, 0, 0),
(25, 'personen_id', 'adressat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen a WHERE a.wrzadressat IS NOT NULL AND a.wrzadressat = \'ja\';layer_id=9 embedded', 'Adressat [Auswahlfeld]', NULL, NULL, NULL, NULL, '', 'Adressat', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(9, 'abwasser_koerperschaft', 'abwasser_koerperschaft', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM\r\n  wasserrecht.fiswrv_koerperschaft z LEFT JOIN	\r\n  wasserrecht.fiswrv_koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id = 2\r\nGROUP BY\r\n  z.id, z.name ;layer_id=6 embedded', 'Abwasserbeseitigungspflichtige Körperschaft', NULL, NULL, NULL, NULL, 'Die Person ist eine abwasserbeseitigungspflichtige Körperschaft (nach §40 Satz 1 LWaG M-V).', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 33, 0, 0),
(9, 'trinkwasser_koerperschaft', 'trinkwasser_koerperschaft', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM\r\n  wasserrecht.fiswrv_koerperschaft z LEFT JOIN	\r\n  wasserrecht.fiswrv_koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id = 1\r\nGROUP BY\r\n  z.id, z.name ;layer_id=6 embedded', 'Träger der öffentlichen Wasserversorgung', NULL, NULL, NULL, NULL, 'Die Person ist Träger der öffentlichen Wasserversorgung (nach § 43 Satz 1 LWaG M-V).', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 34, 0, 0),
(9, 'kommentar', 'kommentar', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Textfeld', '', 'Kommentar ans LUNG', NULL, NULL, NULL, NULL, 'KOMMENTARFELD!', 'Sonstiges', 0, 0, NULL, 0, 0, NULL, 36, 0, 0),
(9, 'zimmer', 'zimmer', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Zimmer', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 16, 0, 0),
(9, 'register_amtsgericht', 'register_amtsgericht', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Registergericht', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(9, 'register_nummer', 'register_nummer', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Register + Nummer', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 12, 0, 0),
(17, 'id', 'id', 'fiswrv_behoerde', 'fiswrv_behoerde', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, -1, NULL, 0, 0, 0),
(10, 'id', 'id', 'fiswrv_personen_klasse', 'fiswrv_personen_klasse', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(10, 'name', 'name', 'fiswrv_personen_klasse', 'fiswrv_personen_klasse', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(11, 'id', 'id', 'fiswrv_personen_status', 'fiswrv_personen_status', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(11, 'name', 'name', 'fiswrv_personen_status', 'fiswrv_personen_status', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(12, 'strasse', 'strasse', 'fiswrv_adresse', 'fiswrv_adresse', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Straße', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(12, 'hausnummer', 'hausnummer', 'fiswrv_adresse', 'fiswrv_adresse', 'varchar', '', '', 1, 10, NULL, '', 'Text', '', 'Hausnummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(12, 'plz', 'plz', 'fiswrv_adresse', 'fiswrv_adresse', 'int4', '', '', 1, 32, 0, '', 'Zahl', '', 'Postleitzahl', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(12, 'ort', 'ort', 'fiswrv_adresse', 'fiswrv_adresse', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Ort', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(13, 'id', 'id', 'fiswrv_personen_typ', 'fiswrv_personen_typ', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(9, 'adress_id', 'adresse', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, ort||\' \'||plz||\' \'||strasse||\' \'||hausnummer as output from wasserrecht.fiswrv_adresse where 1=1;layer_id=12 embedded', 'Adresse', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(13, 'name', 'name', 'fiswrv_personen_typ', 'fiswrv_personen_typ', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(14, 'id', 'id', 'fiswrv_weeerklaerer', 'fiswrv_weeerklaerer', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(14, 'name', 'name', 'fiswrv_weeerklaerer', 'fiswrv_weeerklaerer', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(25, 'ausstellbehoerde_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=17&value_id=$ausstellbehoerde&operator_id==;Behörde anzeigen', 'Ausstellbehörde [Link]', NULL, NULL, NULL, NULL, 'Link auf die ausgeählte Behörde, die die Wasserrechtliche Zulassung erteilt hat. ', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(9, 'konto_id', 'konto', 'fiswrv_personen', 'a', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, name||\' \'||iban||\' \'||bic||\' \'||verwendungszweck||\' \'||personenkonto||\' \'||kassenzeichen as output from wasserrecht.fiswrv_konto where 1=1;layer_id=16 embedded', 'Konto', NULL, NULL, NULL, NULL, '', 'Konto', 0, 0, NULL, 0, 0, NULL, 18, 0, 0),
(16, 'name', 'name', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(16, 'iban', 'iban', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', 1, 22, NULL, '', 'Text', '', 'IBAN', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(16, 'bic', 'bic', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', 1, 11, NULL, '', 'Text', '', 'BIC', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(16, 'verwendungszweck', 'verwendungszweck', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Verwendungszweck', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(16, 'personenkonto', 'personenkonto', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Personenkonto', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(16, 'kassenzeichen', 'kassenzeichen', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Kassenzeichen', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(16, 'konto_id', 'id', 'fiswrv_konto', 'fiswrv_konto', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(17, 'name', 'name', 'fiswrv_behoerde', 'fiswrv_behoerde', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(17, 'abkuerzung', 'abkuerzung', 'fiswrv_behoerde', 'fiswrv_behoerde', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Abkürzung', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(17, 'status', 'status', 'fiswrv_behoerde', 'fiswrv_behoerde', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Status', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(20, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_status', 'fiswrv_wasserrechtliche_zulassungen_status', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(20, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_status', 'fiswrv_wasserrechtliche_zulassungen_status', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(33, 'umfang_entnahme', 'umfang_entnahme', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_umfang_entnahme;layer_id=37 embedded', 'Umfang', NULL, NULL, NULL, NULL, '', 'Umfang', 0, 0, NULL, 0, 0, NULL, 12, 0, 0),
(9, 'aktuell', 'true', '', '', '', '', '', NULL, NULL, NULL, '', 'Autovervollständigungsfeld', 'select \'aktuelle:\' as output, true as value', '', NULL, NULL, NULL, NULL, '', 'Adressat von', 0, 2, NULL, -1, -1, NULL, 37, 0, 0),
(48, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(25, 'typus', 'typus', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 0, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus;layer_id=26 embedded', '<strong>Typus Wrz</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD! Typus der Wasserrechtlichen Zulassung (z.B. Wasserrechtliche Erlaubnis, Wasserrechtliche Nutzungsgenehmigung, etc.) [Neue Typen müssen über den Admin beantragt werden]', 'Typus, Aktenzeichen o.ä., Ort, Datum', 0, 0, NULL, 0, 0, NULL, 24, 0, 0),
(25, 'fassung_typus', 'fassung_typus', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus;layer_id=48 embedded', 'Typus Fassung WrZ', NULL, NULL, NULL, NULL, 'Typus der eventuellen Fassung einer Wasserrechtlichen Zulassung (z.B. Änderungsbescheid, Anpassungsbescheid, etc.) [Neue Typen müssen über den Admin beantragt werden]', 'Fassung', 0, 0, NULL, 0, 0, NULL, 33, 0, 0),
(25, 'bearbeiter', 'bearbeiter', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT\r\n	z.id as value, z.name || \' (\' || coalesce(string_agg(b.name, \', \'),  \'keiner Behörde zugeordnet\') || \')\' AS output FROM\r\n  fiswrv_personen z LEFT JOIN\r\n  fiswrv_behoerde b ON z.behoerde = b.id WHERE NOT (behoerde IS NULL)\r\nGROUP BY\r\n  z.id, z.name ;layer_id=9 embedded', 'Bearbeiter [Auswahlfeld]', NULL, NULL, NULL, NULL, 'Das Feld dient der Auswahl der Bearbeiter-Person der Wasserrechtlichen Zulassung. % und _ entsprechen * und ? als Platzhalter. Nicht vorhandene Bearbeiter-Personen müssen neu angelegt werden.', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 13, 0, 0),
(25, 'bearbeiter_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$bearbeiter&operator_personen_id==;Bearbeiter anzeigen', 'Bearbeiter [Link]', NULL, NULL, NULL, NULL, 'Link auf die ausgewählte Bearbeiter-Person im FisWrV-Per (Personenmodul) der Wasserrechtlichen Zulassung.', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 14, 0, 0),
(25, 'bearbeiter_name', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Name', NULL, NULL, NULL, NULL, 'Name der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 15, 0, 0),
(25, 'bearbeiter_namenszusatz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Namenszusatz', NULL, NULL, NULL, NULL, 'Namenszusatz der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 16, 0, 0),
(25, 'bearbeiter_strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Straße und Hausnummer', NULL, NULL, NULL, NULL, 'Straße und Hausnummer aus der Adresse der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 17, 0, 0),
(24, 'id', 'id', 'fiswrv_dokument', 'fiswrv_dokument', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(24, 'name', 'name', 'fiswrv_dokument', 'fiswrv_dokument', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(24, 'pfad', 'pfad', 'fiswrv_dokument', 'fiswrv_dokument', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Pfad', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(24, 'document', 'document', 'fiswrv_dokument', 'fiswrv_dokument', 'bytea', '', '', 1, NULL, NULL, '', 'Dokument', '', 'Dokument', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(25, 'ausstellbehoerde', 'ausstellbehoerde', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde;layer_id=17 embedded', 'Ausstellbehörde [Auswahlfeld]', NULL, NULL, NULL, NULL, 'Die Behörde, die die Wasserrechtliche Zulassung erteilt hat. [Neue Behörden müssen beim Admin beantragt werden.]', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(33, 'wasserbuchnummer', 'wasserbuchnummer', 'fiswrv_gewaesserbenutzungen', 'b', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Wasserbuchnummer', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(25, 'status', 'status', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_status;layer_id=20 embedded', 'Status des Dokuments', NULL, NULL, NULL, NULL, 'Gibt an ob es sich bei dem Datensatz um eine geprüft Abschrift einer Wasserrechtlichen Zulassung (geringste Fehlerrate), eine Übertragung aus einer geprüften LUNG-Datenbank (mittlere Fehlerrate) oder um Erstbefüllungsdaten (höchste Fehlerrate) handelt.', 'Status der Wasserrechtliche Zulassung', 0, 0, NULL, 0, 0, NULL, 39, 0, 0),
(25, 'wirksam', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Wirksam', NULL, NULL, NULL, NULL, '', 'Status der Wasserrechtliche Zulassung', 0, 0, NULL, 0, 0, NULL, 41, 0, 0),
(25, 'bergamt_aktenzeichen', 'bergamt_aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Bergamt Aktenzeichen', NULL, NULL, NULL, NULL, 'Alternatives Aktenzeichen des Bergamtes. Erscheint nur auf den dem LUNG übersendeten Tabellen.', 'Typus, Aktenzeichen o.ä., Ort, Datum', 0, 0, NULL, 0, 0, NULL, 28, 0, 0),
(25, 'anlage_klasse', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Klasse ANL', NULL, NULL, NULL, NULL, 'Klasse aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(9, 'namenszusatz', 'namenszusatz', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Namenszusatz', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(9, 'iban', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'IBAN', NULL, NULL, NULL, NULL, '', 'Konto', 0, 0, NULL, 0, 0, NULL, 20, 0, 0),
(9, 'plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Postleitzahl Ort', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(9, 'bic', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'BIC', NULL, NULL, NULL, NULL, '', 'Konto', 0, 0, NULL, 0, 0, NULL, 21, 0, 0),
(2, 'gewaesserbenutzungen', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,anlage_id,aktuell,<b>Benutzung:</b> bezeichnung;no_new_window', 'Benutzungen', NULL, NULL, NULL, NULL, '', '<h2>Wasserrechtliche Zulassungen</h2>', 0, 0, NULL, -1, 0, NULL, 9, 0, 0),
(26, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_typus', 'fiswrv_wasserrechtliche_zulassungen_typus', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(48, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(26, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_typus', 'fiswrv_wasserrechtliche_zulassungen_typus', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(31, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(31, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(25, 'bearbeiter_fax', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Fax', NULL, NULL, NULL, NULL, 'Fax der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 21, 0, 0),
(33, 'freitext_zweck', 'freitext_zweck', 'fiswrv_gewaesserbenutzungen', 'b', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Freitext: Zweck der Gewässerbenutzung', NULL, NULL, NULL, NULL, '', 'Zweck', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(30, 'id', 'id', 'fiswrv_ort', 'fiswrv_ort', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(30, 'name', 'name', 'fiswrv_ort', 'fiswrv_ort', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(32, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(32, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(33, 'kennnummer', 'kennnummer', 'fiswrv_gewaesserbenutzungen', 'b', 'varchar', '', '', 1, 255, NULL, '', 'Text', 'SELECT case when \'$wrz_id\' = \'\' then \'Bitte erst eine Wasserrechtliche Zulassung auswählen!\' else (select a.id ||\'-\'|| c.id ||\'-\'|| d.id ||\'-\'|| b.id ||\'-\'|| (select CASE WHEN \'$gwb_id\' = \'\' THEN (last_value + 1)::text ELSE \'$gwb_id\' END as id from wasserrecht.fiswrv_gewaesserbenutzungen_id_seq) AS output FROM wasserrecht.fiswrv_wasserrechtliche_zulassungen b INNER JOIN wasserrecht.fiswrv_behoerde a ON a.id = b.ausstellbehoerde INNER JOIN wasserrecht.fiswrv_personen c ON c.id = b.adressat INNER JOIN wasserrecht.fiswrv_anlagen d ON d.id = b.anlage WHERE b.id::text = \'$wrz_id\') end', 'Benutzungsnummer', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(33, 'art', 'art', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_art;layer_id=34 embedded', 'Art nach WHG', NULL, NULL, NULL, NULL, '', 'Art', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(9, 'verwendungszweck', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Verwendungszweck', NULL, NULL, NULL, NULL, '', 'Konto', 0, 0, NULL, 0, 0, NULL, 22, 0, 0),
(33, 'zweck', 'zweck', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, coalesce(nummer) || \') \' || name AS output from wasserrecht.fiswrv_gewaesserbenutzungen_zweck;layer_id=36 embedded', 'Zweck', NULL, NULL, NULL, NULL, '', 'Zweck', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(16, 'bankname', 'bankname', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Bankname', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(33, 'wasserrechtliche_zulassungen_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=25&value_wrz_id=$wrz_id&operator_wrz_id==;Wasserrechtliche Zulassung anzeigen;no_new_window', 'Wasserrechtliche Zulassung [Link]', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, 0, NULL, 4, 0, 0),
(34, 'id', 'id', 'fiswrv_gewaesserbenutzungen_art', 'fiswrv_gewaesserbenutzungen_art', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, -1, NULL, 0, 0, 0),
(34, 'name', 'name', 'fiswrv_gewaesserbenutzungen_art', 'fiswrv_gewaesserbenutzungen_art', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(43, 'id', 'id', 'fiswrv_mengenbestimmung', 'fiswrv_mengenbestimmung', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(33, 'freitext_art', 'freitext_art', 'fiswrv_gewaesserbenutzungen', 'b', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Freitext: Art der Gewässerbenutzung', NULL, NULL, NULL, NULL, '', 'Art', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(36, 'id', 'id', 'fiswrv_gewaesserbenutzungen_zweck', 'fiswrv_gewaesserbenutzungen_zweck', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, -1, NULL, 0, 0, 0),
(36, 'name', 'name', 'fiswrv_gewaesserbenutzungen_zweck', 'fiswrv_gewaesserbenutzungen_zweck', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(43, 'name', 'name', 'fiswrv_mengenbestimmung', 'fiswrv_mengenbestimmung', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(37, 'id', 'id', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(37, 'name', 'name', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(37, 'max_ent_s', 'max_ent_s', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(37, 'max_ent_h', 'max_ent_h', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(37, 'max_ent_d', 'max_ent_d', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(37, 'max_ent_w', 'max_ent_w', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(37, 'max_ent_m', 'max_ent_m', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(37, 'max_ent_a', 'max_ent_a', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(37, 'max_ent_wee', 'max_ent_wee', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(37, 'max_ent_wee_beschreib', 'max_ent_wee_beschreib', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'text', '', '', 1, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(37, 'max_ent_wb', 'max_ent_wb', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(37, 'max_ent_wb_beschreib', 'max_ent_wb_beschreib', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'text', '', '', 1, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(37, 'max_ent_frei', 'max_ent_frei', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'numeric', '', '', 1, 15, 0, '', 'Zahl', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 12, 0, 0),
(37, 'max_ent_frei_beschreib', 'max_ent_frei_beschreib', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'text', '', '', 1, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 13, 0, 0),
(37, 'freitext', 'freitext', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'fiswrv_gewaesserbenutzungen_umfang_entnahme', 'text', '', '', 1, NULL, NULL, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 14, 0, 0),
(38, 'id', 'id', 'fiswrv_gewaesserbenutzungen_lage', 'fiswrv_gewaesserbenutzungen_lage', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(38, 'name', 'name', 'fiswrv_gewaesserbenutzungen_lage', 'fiswrv_gewaesserbenutzungen_lage', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(38, 'the_geo', 'the_geo', 'fiswrv_gewaesserbenutzungen_lage', 'fiswrv_gewaesserbenutzungen_lage', 'geometry', 'POINT', '', 1, NULL, NULL, '', 'Geometrie', '', 'Geometrie', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(9, 'name', 'name', 'fiswrv_personen', 'a', 'varchar', '', '', 0, 255, NULL, '', 'Text', '', '<strong>Name</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD! Offizieller vollständiger Name der Person.', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(39, 'id', 'id', 'fiswrv_betriebszustand', 'fiswrv_betriebszustand', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(39, 'name', 'name', 'fiswrv_betriebszustand', 'fiswrv_betriebszustand', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(40, 'id', 'id', 'fiswrv_messtischblatt', 'fiswrv_messtischblatt', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(40, 'nummer', 'nummer', 'fiswrv_messtischblatt', 'fiswrv_messtischblatt', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Nummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(41, 'id', 'id', 'fiswrv_archivnummer', 'fiswrv_archivnummer', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(41, 'nummer', 'nummer', 'fiswrv_archivnummer', 'fiswrv_archivnummer', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Nummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(6, 'art', 'art', 'fiswrv_koerperschaft', 'fiswrv_koerperschaft', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_koerperschaft_art;layer_id=42 embedded', 'Körperschaftsart', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(42, 'id', 'id', 'fiswrv_koerperschaft_art', 'fiswrv_koerperschaft_art', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(42, 'name', 'name', 'fiswrv_koerperschaft_art', 'fiswrv_koerperschaft_art', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(45, 'name', 'name', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(44, 'id', 'id', 'fiswrv_teilgewaesserbenutzungen_art', 'fiswrv_teilgewaesserbenutzungen_art', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(44, 'name', 'name', 'fiswrv_teilgewaesserbenutzungen_art', 'fiswrv_teilgewaesserbenutzungen_art', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(45, 'id', 'id', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(25, 'bezeichnung', 'bezeichnung', 'fiswrv_wasserrechtliche_zulassungen_bezeichnung', 'c', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Bezeichnung Wrz', NULL, NULL, NULL, NULL, 'Standartisierte eindeutige Bezeichnung der Wasserrechtlichen Zulassung zur Identifikation in anderen Tabellen', 'Typus, Aktenzeichen o.ä., Ort, Datum', 0, 0, NULL, 0, 0, NULL, 23, 0, 0),
(25, 'wrz_id', 'id', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'Primärschlüssel WrZ', NULL, NULL, NULL, NULL, 'Primärschlüssel der Wasserrechtlichen Zulassungen.', 'Systemfeld', 0, 0, NULL, -1, 0, NULL, 3, 0, 0),
(2, 'wasserrechtliche_zulassungen', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,anlage_id, <b>Wasserrechtliche Zulassung ( aktuell ):</b> bezeichnung;no_new_window', 'WrZ', NULL, NULL, NULL, NULL, '', '<h2>Wasserrechtliche Zulassungen</h2>', 0, 0, NULL, -1, 0, NULL, 7, 0, 0),
(2, 'aktuell', 'true', '', '', '', '', '', NULL, NULL, NULL, '', 'Autovervollständigungsfeld', 'select \'Aktuelle:\' as output, true as value', 'Aktuell', NULL, NULL, NULL, NULL, '', '<h2>Wasserrechtliche Zulassungen</h2>', 0, 2, NULL, -1, -1, NULL, 8, 0, 0),
(2, 'anlage_bearbeiter_datum', 'anlage_bearbeiter_datum', 'fiswrv_anlagen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Time', '', 'Datum und Uhrzeit', NULL, NULL, NULL, NULL, '', 'letzte Änderung', 0, 0, NULL, -1, 0, NULL, 13, 0, 0),
(9, 'betreiber_id', 'betreiber', 'fiswrv_personen', 'a', 'varchar', '', '', 1, 10, NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Betreiber ANL', NULL, NULL, NULL, NULL, 'Die Person ist Betreiber einer wasserrechtsrelevanten Anlage.', 'Gruppen', 0, 0, NULL, 0, 0, NULL, 30, 0, 0),
(2, 'anlage_bearbeiter_name', 'anlage_bearbeiter_name', 'fiswrv_anlagen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'User', '', 'Name', NULL, NULL, NULL, NULL, 'Name des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', 0, 0, NULL, -1, 0, NULL, 11, 0, 0),
(47, 'jahr', 'jahr', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Jahr', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(47, 'satz_ow', 'satz_ow', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', 1, NULL, NULL, '', 'Zahl', '', 'Satz Oberflächenwasser', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(47, 'satz_gw', 'satz_gw', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', 1, NULL, NULL, '', 'Zahl', '', 'Satz Grundwasser', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(17, 'art', 'art', 'fiswrv_behoerde', 'fiswrv_behoerde', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde_art;layer_id=49 embedded', 'Art', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(49, 'id', 'id', 'fiswrv_behoerde_art', 'fiswrv_behoerde_art', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(49, 'name', 'name', 'fiswrv_behoerde_art', 'fiswrv_behoerde_art', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(49, 'abkuerzung', 'abkuerzung', 'fiswrv_behoerde_art', 'fiswrv_behoerde_art', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Abkürzung', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(9, 'personenkonto', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Personenkonto', NULL, NULL, NULL, NULL, '', 'Konto', 0, 0, NULL, 0, 0, NULL, 23, 0, 0),
(9, 'kassenzeichen', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Kassenzeichen', NULL, NULL, NULL, NULL, '', 'Konto', 0, 0, NULL, 0, 0, NULL, 24, 0, 0),
(47, 'id', 'id', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(47, 'name', 'name', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Name', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(45, 'abkuerzung', 'abkuerzung', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'varchar', '', '', 1, 100, NULL, '', 'Text', '', 'Abkürzung', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(25, 'datum_postausgang', 'datum_postausgang', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', 1, NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then (nullif(\'$fassung_datum\', \'\')::date + 1)::text else case when \'$datum\' != \'\' then (nullif(\'$datum\', \'\')::date + 1)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Postausgang', NULL, NULL, NULL, NULL, '', 'Status der Wasserrechtliche Zulassung', 0, 0, NULL, 0, 0, NULL, 44, 0, 0),
(25, 'datum_bestand_mat', 'datum_bestand_mat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', 1, NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then (nullif(\'$fassung_datum\', \'\')::date + 4)::text else case when \'$datum\' != \'\' then (nullif(\'$datum\', \'\')::date + 4)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Bekanntgegeben', NULL, NULL, NULL, NULL, 'Tag an dem der Bescheid als bekanntgeben gilt und materiell bestandskräftig ist (Dreitagesfiktion §41 Satz 2 VwVfG)', 'Status der Wasserrechtliche Zulassung', 0, 0, NULL, 0, 0, NULL, 45, 0, 0),
(25, 'datum_bestand_form', 'datum_bestand_form', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', 1, NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then ((nullif(\'$fassung_datum\', \'\')::date +  integer \'4\' + interval \'1 month\')::date)::text else case when \'$datum\' != \'\' then ((nullif(\'$datum\', \'\')::date + integer \'4\' + interval \'1 month\')::date)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Unanfechtbar', NULL, NULL, NULL, NULL, 'Tag an dem der Bescheid als unanfechtbar gilt und formell bestandskräftig ist (Dreitagesfiktion §41 Satz 2 VwVfG + 1 Monat)', 'Status der Wasserrechtliche Zulassung', 0, 0, NULL, 0, 0, NULL, 46, 0, 0),
(25, 'ort', 'ort', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_ort;layer_id=30 embedded', '<strong>Ort</strong>', NULL, NULL, NULL, NULL, 'Ort an dem der Bescheid ausgestellt wurde.', 'Typus, Aktenzeichen o.ä., Ort, Datum', 0, 0, NULL, 0, 0, NULL, 29, 0, 0),
(25, 'regnummer', 'regnummer', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Registriernummer WrZ', NULL, NULL, NULL, NULL, 'Registriernummer der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', 0, 0, NULL, 0, 0, NULL, 27, 0, 0),
(25, 'bearbeiterzeichen', 'bearbeiterzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Bearbeiterzeichen WrZ', NULL, NULL, NULL, NULL, 'Bearbeiterzeichen der Wasserrechtlichen Zulassung ohne unnötige Lesezeichen. [Bearbeiterzeichen werden vor die Aktenzeichen geschrieben und sollen zu besseren Identifizierbarkeit der WrZ getrennt vom Aktenzeichen gespeichert werden. Unnötig sind dabei all', 'Typus, Aktenzeichen o.ä., Ort, Datum', 0, 0, NULL, 0, 0, NULL, 25, 0, 0),
(25, 'aktenzeichen', 'aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', 0, 255, NULL, '', 'Text', '', '<strong>Aktenzeichen  WrZ</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD! Aktenzeichen der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', 0, 0, NULL, 0, 0, NULL, 26, 0, 0),
(25, 'bearbeiter_email', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Email', NULL, NULL, NULL, NULL, 'Email der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 22, 0, 0),
(2, 'anlage_bearbeiter_stelle', 'anlage_bearbeiter_stelle', 'fiswrv_anlagen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Stelle', '', 'Stelle', NULL, NULL, NULL, NULL, 'Stelle des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', 0, 0, NULL, -1, 0, NULL, 12, 0, 0),
(2, 'betreiber', 'betreiber', 'fiswrv_anlagen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen WHERE betreiber IS NOT NULL;layer_id=9 embedded', 'Betreiber', NULL, NULL, NULL, NULL, '', '<h2>Zugehörige Personen</h2>', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(33, 'bezeichnung', 'bezeichnung', 'fiswrv_gewaesserbenutzungen_bezeichnung', 'c', 'text', '', '', 1, NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, 0, NULL, 7, 0, 0),
(46, 'id', 'id', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(46, 'art', 'art', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_art;layer_id=34 embedded', 'Art', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(46, 'zweck', 'zweck', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_zweck;layer_id=36 embedded', 'Zweck', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(46, 'umfang', 'umfang', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'numeric', '', '', 1, 15, 3, '', 'Text', '', 'Umfang', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 3, 0, 0);
INSERT INTO `layer_attributes` (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `arrangement`, `labeling`, `raster_visibility`, `dont_use_for_new`, `mandatory`, `quicksearch`, `order`, `privileg`, `query_tooltip`) VALUES
(46, 'wiedereinleitung_nutzer', 'wiedereinleitung_nutzer', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', 'Wiedereinleitung nach Benutzer', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(46, 'wiedereinleitung_bearbeiter', 'wiedereinleitung_bearbeiter', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', 'Wiedereinleitung nach Bearbeiter', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 5, 0, 0),
(46, 'mengenbestimmung', 'mengenbestimmung', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_mengenbestimmung;layer_id=43 embedded', 'Mengenbestimmung', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(46, 'art_benutzung', 'art_benutzung', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_art_benutzung;layer_id=45 embedded', 'Art der Benutzung', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 7, 0, 0),
(46, 'befreiungstatbestaende', 'befreiungstatbestaende', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'bool', '', '', 1, NULL, NULL, '', 'Checkbox', '', 'Befreiungstatbestände nach § 16 LWaG', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(46, 'entgeltsatz', 'entgeltsatz', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, jahr as output from wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz;layer_id=47 embedded', 'Entgeltsatz', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(25, 'aktuell', 'aktuell', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', 1, NULL, NULL, '', 'Auswahlfeld', 'select \'aktuell\' as output, true as value\r\nunion\r\nselect \'historisch\' as output, false as value', 'Aktualität', NULL, NULL, NULL, NULL, 'Gibt an ob die Wasserrechtliche Zulassung aktuell ist. Sie gilt dabei so lange als \'aktuell\' solange sie nicht aufgehoben, widerrufen etc. ist. Keine Wasserechtliche Zulassung darf sowohl \'aktuell\' wie auch \'historisch\' sein.', 'Status der Wasserrechtliche Zulassung', 0, 0, NULL, 0, 0, NULL, 40, 0, 0),
(25, 'fassung_nummer', 'fassung_nummer', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Nummer Fassung WrZ', NULL, NULL, NULL, NULL, 'Das Feld dient  der korrekten Nummerierung der eventuellen Fassung einer Wasserrechtlichen Zulassung.', 'Fassung', 0, 0, NULL, 0, 0, NULL, 32, 0, 0),
(25, 'fassung_auswahl', 'fassung_auswahl', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_auswahl;layer_id=31 embedded', 'Fassung WrZ', NULL, NULL, NULL, NULL, 'Das Feld dient  der korrekten Bezeichnung der eventuellen Fassung einer Wasserrechtlichen Zulassung.', 'Fassung', 0, 0, NULL, 0, 0, NULL, 31, 0, 0),
(25, 'fassung_datum', 'fassung_datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Datum Fassung WrZ', NULL, NULL, NULL, NULL, 'Datum auf das die Fassung der WrZ datiert wurde.', 'Fassung', 0, 0, NULL, 0, 0, NULL, 36, 0, 0),
(46, 'teilgewaesserbenutzungen_art', 'teilgewaesserbenutzungen_art', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_teilgewaesserbenutzungen_art;layer_id=44 embedded', 'Teilgewässerbenutzungen', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(46, 'gewaesserbenutzungen', 'gewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', 0, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, kennnummer as output from wasserrecht.fiswrv_gewaesserbenutzungen;layer_id=33 embedded', 'Gewässerbenutzungen', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(9, 'per_wrz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,personen_id,aktuell,<b>Wasserrechtliche Zulassung:</b> bezeichnung;no_new_window', 'Wasserechtliche Zulassungen', NULL, NULL, NULL, NULL, 'Die Person ist Adressat der aufgeführten Wasserrechtlichen Zulassungen.', 'Adressat von', 0, 0, NULL, -1, 0, NULL, 38, 0, 0),
(9, 'per_wrz_ben', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,personen_id,aktuell,<b>Gewässerbenutzungen:</b> bezeichnung;no_new_window', 'Gewässerbenutzungen', NULL, NULL, NULL, NULL, 'Die Person ist Benutzer der aufgeführten Benutzungen. Da sie Adressat der dazugehörigen Wasserrechtlichen Zulassungen ist.', 'Adressat von', 0, 0, NULL, -1, 0, NULL, 39, 0, 0),
(9, 'personen_id', 'id', 'fiswrv_personen', 'a', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'Primärschlüssel Personen', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, 0, NULL, 0, 0, 0),
(25, 'anlage_anzeige', 'name', 'fiswrv_anlagen', 'b', 'varchar', '', '', 0, 255, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=2&value_anlage_id=$anlage_id&operator_anlage_id==;Name: $anlage_anzeige;no_new_window', 'Anlage', NULL, NULL, NULL, NULL, '', 'Wasserrechtlich relevante Anlage', 0, 0, NULL, -1, 0, NULL, 2, 0, 0),
(25, 'anlage_id', 'anlage', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 0, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_anlagen', '<strong>Name ANL</strong>', NULL, NULL, NULL, NULL, 'Name aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(25, 'adressat_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$personen_id&operator_personen_id==;Adressaten anzeigen', 'Adressat [Link]', NULL, NULL, NULL, NULL, '', 'Adressat', 0, 0, NULL, 0, 0, NULL, 8, 0, 0),
(38, 'gwb_id', 'gewaesserbenutzungen', 'fiswrv_gewaesserbenutzungen_lage', 'fiswrv_gewaesserbenutzungen_lage', 'int4', '', '', 0, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, kennnummer as output from wasserrecht.fiswrv_gewaesserbenutzungen;', 'Gewässerbenutzung', NULL, NULL, NULL, NULL, 'Zugehörige Gewässerbenutzung', '', 0, 0, NULL, 0, 0, NULL, 2, 0, 0),
(25, 'wrz_ben', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,wrz_id,bezeichnung;no_new_window', 'Zugelassene Benutzungen', NULL, NULL, NULL, NULL, '', '<h3>Zugelassene Benutzungen</h3>', 0, 0, NULL, -1, 0, NULL, 51, 0, 0),
(33, 'wrz_id', 'wasserrechtliche_zulassungen', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', '', 0, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT  a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a', 'Wasserrechtliche Zulassung [Auswahlfeld]', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, 0, 0, NULL, 3, 0, 0),
(33, 'gwb_id', 'id', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', 'PRIMARY KEY', 1, 32, 0, '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', 'Stammdaten', 0, 0, NULL, -1, -1, NULL, 2, 0, 0),
(33, 'aktuell', 'aktuell', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', 1, NULL, NULL, '', 'Autovervollständigungsfeld', 'select \'ja\' as output, true as value\r\nunion\r\nselect \'nein\' as output, false as value', 'Aktuell?', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, -1, NULL, 14, 0, 0),
(33, 'personen_id', 'adressat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Primärschlüssel Personen', NULL, NULL, NULL, NULL, '', 'Wasserrechtliche Zulassungen', 0, 0, NULL, -1, -1, NULL, 13, 0, 0),
(33, 'gewaesserbenutzungen_lage', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '38,gwb_id, <b>Gewässerbenutzungen:</b> name;no_new_window', 'Lage', NULL, NULL, NULL, NULL, '', 'Lage', 0, 0, NULL, 0, 0, NULL, 15, 0, 0),
(36, 'nummer', 'nummer', 'fiswrv_gewaesserbenutzungen_zweck', 'fiswrv_gewaesserbenutzungen_zweck', 'int4', '', '', 1, 32, 0, '', 'Text', '', 'Nummer', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 1, 0, 0),
(33, 'anlage_id', 'anlage', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 0, 32, 0, '', 'Text', '', 'Anlage ID', NULL, NULL, NULL, NULL, '', 'Anlage', 1, 2, NULL, -1, -1, NULL, 1, 0, 0),
(9, 'strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Straße Hausnummer', NULL, NULL, NULL, NULL, '', 'Adressdaten', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(9, 'kontoname', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Kontoname', NULL, NULL, NULL, NULL, '', 'Konto', 0, 0, NULL, 0, 0, NULL, 19, 0, 0),
(25, 'befristet_bis', 'befristet_bis', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Befristet bis', NULL, NULL, NULL, NULL, 'Datum bis zu dem die Wrz gültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ unbegrenzt gültig ist.]', 'Materielle Bestandskraft', 0, 0, NULL, 0, 0, NULL, 38, 0, 0),
(25, 'gueltig_seit', 'gueltig_seit', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Gültig seit', NULL, NULL, NULL, NULL, 'Datum seit dem die Wrz gültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ ab dem Ausstellungsdatum gültig ist.]', 'Materielle Bestandskraft', 0, 0, NULL, 0, 0, NULL, 37, 0, 0),
(25, 'wrz_wid', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Metaschlüssel WrZ', NULL, NULL, NULL, NULL, 'Zusammengesetzter Schlüssel aus dem Primärschlüssel der wasserrechtlich relevanten Anlagen und dem Primärschlüssel der Wasserrechtlichen Zulassungen, getrennt durch ein Minuszeichen.', 'Systemfeld', 0, 0, NULL, -1, 0, NULL, 4, 0, 0),
(25, 'adressat_name', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Name', NULL, NULL, NULL, NULL, 'Name der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', 0, 0, NULL, 0, 0, NULL, 9, 0, 0),
(25, 'adressat_namenszusatz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Namenszusatz', NULL, NULL, NULL, NULL, 'Namenszusatz der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', 0, 0, NULL, 0, 0, NULL, 10, 0, 0),
(25, 'adressat_strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Straße und Hausnummer', NULL, NULL, NULL, NULL, 'Straße und Hausnummer aus der Adresse der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', 0, 0, NULL, 0, 0, NULL, 11, 0, 0),
(25, 'adressat_plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, PLZ und Ort', NULL, NULL, NULL, NULL, 'Postleitzahl und Ort aus der Adresse der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', 0, 0, NULL, 0, 0, NULL, 12, 0, 0),
(25, 'datum', 'datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', 0, NULL, NULL, '', 'Text', '', '<strong>Datum WrZ</strong>', NULL, NULL, NULL, NULL, 'PFLICHTFELD! Datum auf das die WrZ datiert wurde.', 'Typus, Aktenzeichen o.ä., Ort, Datum', 0, 0, NULL, 0, 0, NULL, 30, 0, 0),
(25, 'fassung_bearbeiterzeichen', 'fassung_bearbeiterzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Bearbeiterzeichen der Änderung (LUNG, StALU und StAUN)', NULL, NULL, NULL, NULL, 'Abweichendes Bearbeiterzeichen eines Änderungsbescheides (o. ä.) zu einer Wasserrechtlichen Zulassung ohne unnötige Leerzeichen. [Bearbeiterzeichen werden vom LUNG und den StÄLU (historisch auch StÄUN) vor die Aktenzeichen geschrieben und sollen zu besser', 'Fassung', 0, 0, NULL, 0, 0, NULL, 34, 0, 0),
(25, 'fassung_aktenzeichen', 'fassung_aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Text', '', 'Aktenzeichen oder Registriernummer der Änderung', NULL, NULL, NULL, NULL, 'Abweichendes  Aktenzeichen eines Änderungsbescheides (o. ä.) zu einer Wasserrechtlichen Zulassung. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Fassung', 0, 0, NULL, 0, 0, NULL, 35, 0, 0),
(25, 'ungueltig_seit', 'ungueltig_seit', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', 1, NULL, NULL, '', 'Text', '', 'Ungültig seit', NULL, NULL, NULL, NULL, 'Datum seit dem die WrZ ungültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ gültig ist.]', 'Status der Wasserrechtliche Zulassung', 0, 0, NULL, 0, 0, NULL, 42, 0, 0),
(25, 'ungueltig_aufgrund', 'ungueltig_aufgrund', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund;layer_id=32 embedded', 'Ungültig aufgrund', NULL, NULL, NULL, NULL, 'Grund warum die WrZ ungültig ist.', 'Status der Wasserrechtliche Zulassung', 0, 0, NULL, 0, 0, NULL, 43, 0, 0),
(25, 'bearbeiter_plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, PLZ und Ort', NULL, NULL, NULL, NULL, 'Postleitzahl und Ort aus der Adresse der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 18, 0, 0),
(25, 'bearbeiter_zimmer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Zimmer', NULL, NULL, NULL, NULL, 'Zimmer der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 19, 0, 0),
(25, 'bearbeiter_telefon', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Telefon', NULL, NULL, NULL, NULL, 'Telefon der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', 0, 0, NULL, 0, 0, NULL, 20, 0, 0),
(17, 'adress_id', 'adresse', 'fiswrv_behoerde', 'fiswrv_behoerde', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, ort||\' \'||plz||\' \'||strasse||\' \'||hausnummer as output from wasserrecht.fiswrv_adresse where 1=1;layer_id=12 embedded', 'Adresse', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 4, 0, 0),
(17, 'konto_id', 'konto', 'fiswrv_behoerde', 'fiswrv_behoerde', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT id as value, name||\' \'||iban||\' \'||bic||\' \'||verwendungszweck||\' \'||personenkonto||\' \'||kassenzeichen as output from wasserrecht.fiswrv_konto where 1=1;layer_id=16 embedded', 'Konto', NULL, NULL, NULL, NULL, '', '', 0, 0, NULL, 0, 0, NULL, 6, 0, 0),
(25, 'dokument', 'dokument', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', 1, 255, NULL, '', 'Dokument', '', 'Dokument', NULL, NULL, NULL, NULL, 'Eingescanntes Dokument der Wasserrechtluchen Zulassung', 'Dokument', 0, 0, NULL, 0, 0, NULL, 47, 0, 0),
(33, 'anlage_anzeige', 'name', 'fiswrv_anlagen', 'f', 'varchar', '', '', 0, 255, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=2&value_anlage_id=$anlage_id&operator_anlage_id==;Name: $anlage_anzeige;no_new_window', 'Anlage', NULL, NULL, NULL, NULL, '', 'Anlage', 0, 0, NULL, 0, 0, NULL, 0, 0, 0),
(25, 'nachfolger', 'nachfolger', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT  a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a;layer_id=25 embedded', 'Nachfolger', NULL, NULL, NULL, NULL, 'Nachfolge WrZ', 'Historienverwaltung', 0, 0, NULL, 0, 0, NULL, 48, 0, 0),
(25, 'vorgaenger', 'vorgaenger', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', 1, 32, 0, '', 'Autovervollständigungsfeld', 'SELECT  a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a;layer_id=25 embedded', 'Vorgänger', NULL, NULL, NULL, NULL, 'Vorgänger WrZ', 'Historienverwaltung', 0, 0, NULL, 0, 0, NULL, 49, 0, 0),
(25, 'freigegeben', 'freigegeben', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', 1, NULL, NULL, 'SELECT false', 'Auswahlfeld', 'select \'ja\' as output, true as value\r\nunion\r\nselect \'nein\' as output, false as value', 'Freigegeben', NULL, NULL, NULL, NULL, 'WrZ freigegeben?', 'Historienverwaltung', 0, 0, NULL, 0, 0, NULL, 50, 0, 0);

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
(25, 'wrz_ben', 4, 0, 0),
(2, 'kommentar', 1, 1, 0),
(2, 'objektid_geodin', 1, -1, 0),
(38, 'id', 1, 0, 0),
(2, 'anlage_id', 2, 0, 0),
(2, 'aktuell', 1, 0, 0),
(2, 'gewaesserbenutzungen', 1, 1, 0),
(2, 'abwasser_koerperschaft', 1, 1, 0),
(2, 'anlage_id', 1, 0, 0),
(2, 'name', 1, 1, 0),
(2, 'klasse', 1, 1, 0),
(2, 'zustaend_stalu', 1, 1, 0),
(2, 'zustaend_uwb', 1, 1, 0),
(3, 'name', 1, 1, 0),
(3, 'id', 1, -1, 0),
(16, 'konto_id', 4, -1, 0),
(43, 'id', 1, -1, 0),
(36, 'id', 2, 0, 0),
(36, 'nummer', 2, 1, 0),
(10, 'name', 1, 1, 0),
(11, 'name', 1, 1, 0),
(6, 'name', 1, 1, 0),
(6, 'id', 1, -1, 0),
(3, 'id', 4, -1, 0),
(2, 'aktuell', 2, 0, 0),
(2, 'gewaesserbenutzungen', 2, 1, 0),
(21, 'aktenzeichen', 2, 1, 0),
(9, 'trinkwasser_koerperschaft', 1, 1, 0),
(9, 'namenszusatz', 1, 1, 0),
(9, 'adress_id', 1, 1, 0),
(9, 'typ', 1, 1, 0),
(9, 'klasse', 1, 1, 0),
(9, 'status', 1, 1, 0),
(9, 'bezeichnung', 1, 0, 0),
(9, 'name', 1, 1, 0),
(9, 'abkuerzung', 1, 1, 0),
(9, 'wrzrechtsnachfolger', 4, 1, 0),
(9, 'betreiber', 4, 0, 0),
(9, 'weeerklaerer', 2, 1, 0),
(9, 'strasse_hausnummer', 1, 0, 0),
(10, 'id', 1, -1, 0),
(9, 'kassenzeichen', 2, 0, 0),
(9, 'anlage_id', 2, 1, 0),
(9, 'wrzadressat', 1, 1, 0),
(9, 'wrzaussteller', 1, 1, 0),
(9, 'behoerde', 1, 1, 0),
(9, 'anlage_id', 1, 1, 0),
(9, 'kassenzeichen', 1, 0, 0),
(9, 'verwendungszweck', 1, 0, 0),
(9, 'personenkonto', 1, 0, 0),
(9, 'personen_id', 1, 0, 0),
(11, 'id', 1, -1, 0),
(12, 'strasse', 1, 1, 0),
(12, 'strasse', 2, 1, 0),
(12, 'plz', 1, 1, 0),
(9, 'bearbeiter_id', 1, 1, 0),
(13, 'name', 1, 1, 0),
(14, 'id', 1, -1, 0),
(16, 'name', 1, 1, 0),
(16, 'iban', 1, 1, 0),
(16, 'bic', 1, 1, 0),
(16, 'bankname', 1, 1, 0),
(16, 'verwendungszweck', 1, 1, 0),
(16, 'personenkonto', 1, 1, 0),
(16, 'konto_id', 1, -1, 0),
(17, 'status', 1, 1, 0),
(17, 'adress_id', 1, 1, 0),
(17, 'art', 1, 1, 0),
(17, 'konto_id', 1, 1, 0),
(17, 'name', 1, 1, 0),
(17, 'abkuerzung', 1, 1, 0),
(17, 'id', 1, 0, 0),
(17, 'status', 2, 1, 0),
(17, 'adress_id', 2, 1, 0),
(17, 'name', 2, 1, 0),
(17, 'abkuerzung', 2, 1, 0),
(17, 'art', 4, 1, 0),
(17, 'name', 4, 1, 0),
(17, 'abkuerzung', 4, 1, 0),
(17, 'id', 4, 0, 0),
(20, 'name', 1, 1, 0),
(46, 'mengenbestimmung', 2, 1, 0),
(24, 'name', 1, 1, 0),
(24, 'pfad', 1, 1, 0),
(48, 'id', 1, -1, 0),
(48, 'name', 1, 1, 0),
(13, 'name', 4, 1, 0),
(13, 'id', 4, -1, 0),
(13, 'id', 2, -1, 0),
(26, 'name', 1, 1, 0),
(44, 'id', 1, -1, 0),
(31, 'id', 1, -1, 0),
(31, 'name', 1, 1, 0),
(12, 'hausnummer', 1, 1, 0),
(32, 'name', 1, 1, 0),
(32, 'id', 1, -1, 0),
(12, 'adress_id', 1, -1, 0),
(30, 'name', 1, 1, 0),
(30, 'id', 1, -1, 0),
(25, 'wrz_ben', 1, 0, 0),
(16, 'kassenzeichen', 4, 1, 0),
(33, 'anlage_anzeige', 4, 0, 0),
(33, 'anlage_anzeige', 2, 0, 0),
(33, 'zweck', 1, 1, 0),
(33, 'umfang_entnahme', 1, 1, 0),
(34, 'id', 1, 0, 0),
(13, 'id', 1, -1, 0),
(23, 'id', 4, -1, 0),
(36, 'name', 1, 1, 0),
(36, 'id', 1, 0, 0),
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
(38, 'name', 1, 1, 0),
(38, 'gwb_id', 1, 1, 0),
(25, 'freigegeben', 1, 1, 0),
(46, 'art_benutzung', 2, 1, 0),
(39, 'name', 1, 1, 0),
(40, 'id', 1, -1, 0),
(41, 'nummer', 1, 1, 0),
(6, 'art', 1, 1, 0),
(42, 'name', 1, 1, 0),
(2, 'the_geom', 1, 1, 0),
(2, 'the_geom', 2, 1, 0),
(2, 'objektid_geodin', 2, -1, 0),
(2, 'kommentar', 2, 1, 0),
(2, 'abwasser_koerperschaft', 2, 1, 0),
(2, 'betreiber', 2, 1, 0),
(2, 'anlage_bearbeiter_name', 2, 0, 0),
(33, 'wasserbuchnummer', 2, 1, 0),
(33, 'bezeichnung', 2, 0, 0),
(33, 'freitext_art', 2, 1, 0),
(33, 'art', 2, 1, 0),
(33, 'freitext_zweck', 2, 1, 0),
(9, 'typ', 2, 1, 0),
(9, 'klasse', 2, 1, 0),
(9, 'status', 2, 1, 0),
(9, 'wrzrechtsnachfolger', 1, 1, 0),
(9, 'email', 1, 1, 0),
(9, 'zimmer', 1, 1, 0),
(9, 'verwendungszweck_wee', 1, 1, 0),
(9, 'konto_id', 1, 1, 0),
(9, 'bezeichnung', 2, 0, 0),
(9, 'name', 2, 1, 0),
(9, 'abkuerzung', 2, 1, 0),
(9, 'namenszusatz', 2, 1, 0),
(9, 'adress_id', 2, 1, 0),
(9, 'strasse_hausnummer', 2, 0, 0),
(9, 'personen_id', 2, 0, 0),
(44, 'name', 1, 1, 0),
(43, 'id', 2, -1, 0),
(43, 'name', 2, 1, 0),
(38, 'the_geo', 2, 1, 0),
(38, 'name', 2, 1, 0),
(38, 'gwb_id', 2, 0, 0),
(46, 'mengenbestimmung', 4, 1, 0),
(50, 'wrz_id', 2, 1, 0),
(50, 'wrz_id', 4, 1, 0),
(25, 'freigegeben', 4, 1, 0),
(13, 'name', 2, 1, 0),
(25, 'vorgaenger', 1, 1, 0),
(25, 'nachfolger', 1, 1, 0),
(25, 'dokument', 1, 1, 0),
(25, 'datum_bestand_form', 1, 1, 0),
(25, 'datum_bestand_mat', 1, 1, 0),
(25, 'datum_postausgang', 1, 1, 0),
(25, 'ungueltig_aufgrund', 1, 1, 0),
(3, 'id', 2, -1, 0),
(16, 'verwendungszweck', 4, 1, 0),
(33, 'freitext_art', 1, 1, 0),
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
(23, 'name', 2, 1, 0),
(23, 'name', 4, 1, 0),
(23, 'id', 2, -1, 0),
(25, 'vorgaenger', 4, 1, 0),
(2, 'anlage_bearbeiter_name', 4, 0, 0),
(2, 'anlage_bearbeiter_stelle', 4, 0, 0),
(2, 'aktuell', 4, 0, 0),
(2, 'gewaesserbenutzungen', 4, 1, 0),
(2, 'wasserrechtliche_zulassungen', 4, 0, 0),
(2, 'anlage_id', 4, 0, 0),
(2, 'name', 4, 1, 0),
(2, 'klasse', 4, 1, 0),
(2, 'zustaend_stalu', 4, 1, 0),
(2, 'zustaend_uwb', 4, 1, 0),
(2, 'abwasser_koerperschaft', 4, 1, 0),
(2, 'trinkwasser_koerperschaft', 4, 1, 0),
(3, 'name', 4, 1, 0),
(41, 'id', 2, -1, 0),
(41, 'id', 4, -1, 0),
(41, 'nummer', 2, 1, 0),
(41, 'nummer', 4, 1, 0),
(41, 'id', 1, -1, 0),
(3, 'name', 2, 1, 0),
(17, 'art', 2, 1, 0),
(49, 'name', 1, 1, 0),
(49, 'id', 1, 0, 0),
(17, 'konto_id', 4, 1, 0),
(17, 'adress_id', 4, 1, 0),
(49, 'abkuerzung', 1, 1, 0),
(25, 'ungueltig_seit', 1, 1, 0),
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
(36, 'nummer', 1, 1, 0),
(33, 'umfang_entnahme', 4, 1, 0),
(33, 'kennnummer', 4, 1, 0),
(33, 'aktuell', 2, 0, 0),
(33, 'personen_id', 2, 0, 0),
(33, 'umfang_entnahme', 2, 1, 0),
(33, 'personen_id', 4, 0, 0),
(33, 'wrz_id', 4, 0, 0),
(33, 'wasserrechtliche_zulassungen_link', 4, 0, 0),
(25, 'nachfolger', 4, 1, 0),
(34, 'id', 2, 0, 0),
(34, 'name', 2, 1, 0),
(25, 'wirksam', 1, 0, 0),
(34, 'name', 4, 1, 0),
(34, 'id', 4, 0, 0),
(34, 'name', 1, 1, 0),
(38, 'the_geo', 4, 1, 0),
(38, 'name', 4, 1, 0),
(38, 'gwb_id', 4, 0, 0),
(38, 'id', 4, 0, 0),
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
(33, 'gewaesserbenutzungen_lage', 2, 0, 0),
(36, 'name', 4, 1, 0),
(36, 'id', 4, 0, 0),
(36, 'nummer', 4, 1, 0),
(16, 'personenkonto', 2, 1, 0),
(16, 'verwendungszweck', 2, 1, 0),
(16, 'konto_id', 2, -1, 0),
(16, 'bankname', 4, 1, 0),
(16, 'name', 4, 1, 0),
(16, 'iban', 4, 1, 0),
(16, 'bic', 4, 1, 0),
(16, 'bankname', 2, 1, 0),
(16, 'bic', 2, 1, 0),
(16, 'personenkonto', 4, 1, 0),
(16, 'kassenzeichen', 2, 1, 0),
(25, 'aktuell', 1, 1, 0),
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
(9, 'bearbeiter', 4, 0, 0),
(9, 'kommentar', 2, 1, 0),
(9, 'telefon', 4, 1, 0),
(9, 'fax', 4, 1, 0),
(9, 'email', 4, 1, 0),
(9, 'zimmer', 4, 1, 0),
(9, 'verwendungszweck_wee', 4, 1, 0),
(9, 'konto_id', 4, 1, 0),
(9, 'kontoname', 4, 0, 0),
(9, 'iban', 4, 0, 0),
(9, 'bic', 4, 0, 0),
(9, 'verwendungszweck', 4, 0, 0),
(9, 'personenkonto', 4, 0, 0),
(9, 'kassenzeichen', 4, 0, 0),
(9, 'anlage_id', 4, 1, 0),
(9, 'behoerde', 4, 1, 0),
(9, 'wrzaussteller', 4, 1, 0),
(9, 'personen_id', 4, 0, 0),
(9, 'typ', 4, 1, 0),
(9, 'klasse', 4, 1, 0),
(9, 'status', 4, 1, 0),
(9, 'bezeichnung', 4, 0, 0),
(9, 'name', 4, 1, 0),
(9, 'abkuerzung', 4, 1, 0),
(9, 'per_wrz_ben', 2, 1, 0),
(9, 'aktuell', 2, 0, 0),
(9, 'per_wrz', 2, 1, 0),
(9, 'wrzrechtsnachfolger', 2, 1, 0),
(9, 'wrzadressat', 2, 1, 0),
(9, 'abwasser_koerperschaft', 1, 1, 0),
(9, 'register_nummer', 4, 1, 0),
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
(23, 'bearbeiterzeichen', 2, 1, 0),
(23, 'bearbeiterzeichen', 4, 1, 0),
(43, 'id', 4, -1, 0),
(43, 'name', 4, 1, 0),
(25, 'vorgaenger', 2, 1, 0),
(25, 'freigegeben', 2, 1, 0),
(25, 'dokument', 4, 1, 0),
(25, 'datum_bestand_form', 4, 1, 0),
(25, 'datum_bestand_mat', 4, 1, 0),
(25, 'datum_postausgang', 4, 1, 0),
(25, 'ungueltig_aufgrund', 4, 1, 0),
(25, 'ungueltig_seit', 4, 1, 0),
(25, 'wirksam', 4, 0, 0),
(25, 'aktuell', 4, 1, 0),
(25, 'status', 4, 1, 0),
(25, 'befristet_bis', 4, 1, 0),
(25, 'gueltig_seit', 4, 1, 0),
(25, 'fassung_datum', 4, 1, 0),
(25, 'fassung_aktenzeichen', 4, 1, 0),
(21, 'datum_bestand_mat', 2, 1, 0),
(21, 'datum_bestand_form', 2, 1, 0),
(21, 'ort', 2, 1, 0),
(21, 'id', 2, -1, 0),
(21, 'datum_bestand_form', 4, 1, 0),
(21, 'ort', 4, 1, 0),
(21, 'nummer', 4, 1, 0),
(21, 'fassung', 4, 1, 0),
(21, 'fassung', 2, 1, 0),
(33, 'bezeichnung', 1, 0, 0),
(21, 'datum_postausgang', 2, 1, 0),
(21, 'nummer', 2, 1, 0),
(21, 'id', 4, -1, 0),
(21, 'aktenzeichen', 4, 1, 0),
(21, 'datum_postausgang', 4, 1, 0),
(21, 'datum_bestand_mat', 4, 1, 0),
(18, 'name', 2, 1, 0),
(18, 'id', 2, -1, 0),
(25, 'status', 1, 1, 0),
(2, 'betreiber', 4, 1, 0),
(9, 'register_amtsgericht', 2, 1, 0),
(9, 'register_nummer', 2, 1, 0),
(9, 'telefon', 2, 1, 0),
(18, 'datum_bestand_form', 2, 1, 0),
(18, 'ort', 2, 1, 0),
(18, 'regnummer', 2, 1, 0),
(18, 'ausstellbehoerde', 2, 1, 0),
(9, 'betreiber_id', 1, 1, 0),
(26, 'id', 2, -1, 0),
(26, 'id', 4, -1, 0),
(26, 'id', 1, -1, 0),
(26, 'name', 2, 1, 0),
(26, 'name', 4, 1, 0),
(29, 'id', 2, -1, 0),
(29, 'id', 4, -1, 0),
(29, 'name', 2, 1, 0),
(29, 'name', 4, 1, 0),
(19, 'auswahl', 2, 1, 0),
(19, 'klasse', 2, 1, 0),
(19, 'aktenzeichen', 2, 1, 0),
(19, 'auswahl', 4, 1, 0),
(19, 'klasse', 4, 1, 0),
(19, 'aktenzeichen', 4, 1, 0),
(19, 'id', 2, -1, 0),
(19, 'id', 4, -1, 0),
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
(25, 'wrz_ben', 2, 0, 0),
(15, 'name', 2, 1, 0),
(15, 'name', 4, 1, 0),
(17, 'id', 2, 0, 0),
(2, 'wasserrechtliche_zulassungen', 2, 0, 0),
(2, 'trinkwasser_koerperschaft', 2, 1, 0),
(2, 'name', 2, 1, 0),
(2, 'anlage_bearbeiter_datum', 4, 0, 0),
(25, 'befristet_bis', 1, 1, 0),
(25, 'gueltig_seit', 1, 1, 0),
(25, 'fassung_datum', 1, 1, 0),
(25, 'fassung_aktenzeichen', 1, 1, 0),
(25, 'fassung_bearbeiterzeichen', 1, 1, 0),
(25, 'fassung_bearbeiterzeichen', 4, 1, 0),
(25, 'fassung_typus', 4, 1, 0),
(25, 'fassung_nummer', 4, 1, 0),
(25, 'fassung_auswahl', 4, 1, 0),
(25, 'nachfolger', 2, 1, 0),
(25, 'dokument', 2, 1, 0),
(25, 'datum_bestand_form', 2, 1, 0),
(25, 'datum_bestand_mat', 2, 1, 0),
(25, 'datum_postausgang', 2, 1, 0),
(25, 'ungueltig_aufgrund', 2, 1, 0),
(25, 'ungueltig_seit', 2, 1, 0),
(25, 'wirksam', 2, 0, 0),
(25, 'aktuell', 2, 1, 0),
(25, 'datum', 4, 1, 0),
(25, 'ort', 4, 1, 0),
(25, 'bergamt_aktenzeichen', 4, 1, 0),
(25, 'fassung_typus', 1, 1, 0),
(25, 'fassung_auswahl', 1, 1, 0),
(50, 'freigegeben', 4, 1, 0),
(44, 'id', 2, -1, 0),
(44, 'name', 2, 1, 0),
(2, 'the_geom', 4, 1, 0),
(2, 'objektid_geodin', 4, -1, 0),
(9, 'bearbeiter', 2, 0, 0),
(9, 'bearbeiter_id', 2, 1, 0),
(9, 'abwasser_koerperschaft', 2, 1, 0),
(9, 'fax', 2, 1, 0),
(9, 'email', 2, 1, 0),
(9, 'zimmer', 2, 1, 0),
(9, 'verwendungszweck_wee', 2, 1, 0),
(9, 'konto_id', 2, 1, 0),
(9, 'kontoname', 2, 0, 0),
(9, 'iban', 2, 0, 0),
(9, 'bic', 2, 0, 0),
(9, 'verwendungszweck', 2, 0, 0),
(9, 'personenkonto', 2, 0, 0),
(38, 'the_geo', 1, 1, 0),
(44, 'id', 4, -1, 0),
(44, 'name', 4, 1, 0),
(9, 'betreiber_id', 4, 1, 0),
(9, 'wrzadressat', 4, 1, 0),
(46, 'art_benutzung', 4, 1, 0),
(45, 'id', 1, -1, 0),
(45, 'name', 1, 1, 0),
(45, 'abkuerzung', 1, 1, 0),
(46, 'art_benutzung', 1, 1, 0),
(46, 'gewaesserbenutzungen', 2, 1, 0),
(46, 'id', 2, -1, 0),
(46, 'art', 2, 1, 0),
(46, 'zweck', 2, 1, 0),
(46, 'umfang', 2, 1, 0),
(46, 'wiedereinleitung_nutzer', 2, 1, 0),
(46, 'wiedereinleitung_bearbeiter', 2, 1, 0),
(46, 'entgeltsatz', 4, 1, 0),
(46, 'zweck', 4, 1, 0),
(25, 'fassung_nummer', 1, 1, 0),
(2, 'anlage_bearbeiter_stelle', 2, 0, 0),
(2, 'wrz_ben_lage', 2, 1, 0),
(9, 'per_wrz_ben', 1, 1, 0),
(2, 'anlage_bearbeiter_datum', 2, 0, 0),
(25, 'status', 2, 1, 0),
(25, 'datum', 1, 1, 0),
(33, 'gewaesserbenutzungen_lage', 1, 0, 0),
(33, 'gewaesserbenutzungen_lage', 4, 0, 0),
(33, 'aktuell', 4, 0, 0),
(46, 'gewaesserbenutzungen', 4, 1, 0),
(46, 'wiedereinleitung_bearbeiter', 4, 1, 0),
(45, 'id', 4, -1, 0),
(45, 'name', 4, 1, 0),
(45, 'abkuerzung', 4, 1, 0),
(46, 'befreiungstatbestaende', 1, 1, 0),
(46, 'entgeltsatz', 1, 1, 0),
(46, 'teilgewaesserbenutzungen_art', 1, 1, 0),
(46, 'mengenbestimmung', 1, 1, 0),
(46, 'teilgewaesserbenutzungen_art', 2, 1, 0),
(46, 'wiedereinleitung_nutzer', 4, 1, 0),
(46, 'umfang', 4, 1, 0),
(46, 'art', 4, 1, 0),
(46, 'befreiungstatbestaende', 2, 1, 0),
(46, 'teilgewaesserbenutzungen_art', 4, 1, 0),
(46, 'id', 4, -1, 0),
(50, 'id', 2, 0, 0),
(50, 'id', 4, 0, 0),
(38, 'id', 2, 0, 0),
(2, 'wrz_ben_lage', 4, 1, 0),
(9, 'plz_ort', 2, 0, 0),
(2, 'klasse', 2, 1, 0),
(2, 'zustaend_stalu', 2, 1, 0),
(2, 'zustaend_uwb', 2, 1, 0),
(2, 'kommentar', 4, 1, 0),
(9, 'per_wrz_ben', 4, 1, 0),
(2, 'anlage_bearbeiter_name', 1, 0, 0),
(9, 'aktuell', 4, 0, 0),
(9, 'trinkwasser_koerperschaft', 4, 1, 0),
(9, 'weeerklaerer', 4, 1, 0),
(9, 'adress_id', 4, 1, 0),
(9, 'strasse_hausnummer', 4, 0, 0),
(47, 'satz_ow', 4, 1, 0),
(47, 'satz_gw', 4, 1, 0),
(47, 'id', 4, -1, 0),
(47, 'name', 4, 1, 0),
(47, 'jahr', 4, 1, 0),
(47, 'jahr', 1, 1, 0),
(47, 'satz_ow', 1, 1, 0),
(47, 'satz_gw', 1, 1, 0),
(18, 'datum_postausgang', 2, 1, 0),
(18, 'klasse', 2, 1, 0),
(18, 'aktenzeichen', 2, 1, 0),
(18, 'datum_bestand_form', 4, 1, 0),
(18, 'ort', 4, 1, 0),
(18, 'regnummer', 4, 1, 0),
(18, 'ausstellbehoerde', 4, 1, 0),
(9, 'kommentar', 1, 1, 0),
(9, 'betreiber', 1, 0, 0),
(18, 'datum_bestand_mat', 4, 1, 0),
(18, 'id', 4, -1, 0),
(18, 'name', 4, 1, 0),
(18, 'klasse', 4, 1, 0),
(18, 'aktenzeichen', 4, 1, 0),
(18, 'datum_postausgang', 4, 1, 0),
(47, 'jahr', 2, 1, 0),
(47, 'satz_ow', 2, 1, 0),
(47, 'satz_gw', 2, 1, 0),
(47, 'id', 2, -1, 0),
(47, 'name', 2, 1, 0),
(16, 'kassenzeichen', 1, 1, 0),
(48, 'id', 4, -1, 0),
(48, 'name', 4, 1, 0),
(33, 'bezeichnung', 4, 0, 0),
(33, 'freitext_art', 4, 1, 0),
(33, 'wasserbuchnummer', 4, 1, 0),
(48, 'id', 2, -1, 0),
(48, 'name', 2, 1, 0),
(25, 'befristet_bis', 2, 1, 0),
(25, 'gueltig_seit', 2, 1, 0),
(9, 'bearbeiter_id', 4, 1, 0),
(9, 'wrzaussteller', 2, 1, 0),
(9, 'behoerde', 2, 1, 0),
(9, 'bearbeiter', 1, 0, 0),
(2, 'wrz_ben_lage', 1, 1, 0),
(2, 'betreiber', 1, 1, 0),
(25, 'fassung_datum', 2, 1, 0),
(25, 'ort', 1, 1, 0),
(25, 'bergamt_aktenzeichen', 1, 1, 0),
(25, 'regnummer', 1, 1, 0),
(25, 'aktenzeichen', 1, 1, 0),
(25, 'fassung_aktenzeichen', 2, 1, 0),
(25, 'fassung_bearbeiterzeichen', 2, 1, 0),
(25, 'regnummer', 4, 1, 0),
(25, 'aktenzeichen', 4, 1, 0),
(47, 'id', 1, -1, 0),
(46, 'gewaesserbenutzungen', 1, 1, 0),
(45, 'id', 2, -1, 0),
(45, 'name', 2, 1, 0),
(45, 'abkuerzung', 2, 1, 0),
(46, 'entgeltsatz', 2, 1, 0),
(46, 'umfang', 1, 1, 0),
(46, 'wiedereinleitung_nutzer', 1, 1, 0),
(46, 'wiedereinleitung_bearbeiter', 1, 1, 0),
(46, 'id', 1, -1, 0),
(46, 'art', 1, 1, 0),
(46, 'zweck', 1, 1, 0),
(9, 'per_wrz', 1, 1, 0),
(9, 'kontoname', 1, 0, 0),
(9, 'iban', 1, 0, 0),
(9, 'bic', 1, 0, 0),
(9, 'abwasser_koerperschaft', 4, 1, 0),
(9, 'plz_ort', 4, 0, 0),
(9, 'register_amtsgericht', 4, 1, 0),
(9, 'weeerklaerer', 1, 1, 0),
(9, 'per_wrz', 4, 1, 0),
(9, 'namenszusatz', 4, 1, 0),
(2, 'anlage_bearbeiter_stelle', 1, 0, 0),
(9, 'plz_ort', 1, 0, 0),
(9, 'register_amtsgericht', 1, 1, 0),
(9, 'register_nummer', 1, 1, 0),
(9, 'telefon', 1, 1, 0),
(9, 'fax', 1, 1, 0),
(47, 'name', 1, 1, 0),
(46, 'befreiungstatbestaende', 4, 1, 0),
(25, 'bearbeiterzeichen', 1, 1, 0),
(25, 'bearbeiterzeichen', 4, 1, 0),
(25, 'typus', 4, 1, 0),
(25, 'typus', 1, 1, 0),
(25, 'bezeichnung', 1, 0, 0),
(25, 'bezeichnung', 4, 0, 0),
(25, 'fassung_typus', 2, 1, 0),
(25, 'fassung_nummer', 2, 1, 0),
(25, 'fassung_auswahl', 2, 1, 0),
(25, 'datum', 2, 1, 0),
(9, 'trinkwasser_koerperschaft', 2, 1, 0),
(18, 'datum_bestand_mat', 2, 1, 0),
(25, 'bearbeiter_email', 1, 0, 0),
(25, 'bearbeiter_email', 4, 0, 0),
(9, 'aktuell', 1, 0, 0),
(25, 'ort', 2, 1, 0),
(25, 'bergamt_aktenzeichen', 2, 1, 0),
(43, 'name', 1, 1, 0),
(9, 'kommentar', 4, 1, 0),
(16, 'name', 2, 1, 0),
(16, 'iban', 2, 1, 0),
(25, 'bearbeiter_fax', 1, 0, 0),
(33, 'freitext_zweck', 4, 1, 0),
(33, 'aktuell', 1, 0, 0),
(33, 'personen_id', 1, 0, 0),
(33, 'art', 1, 1, 0),
(33, 'anlage_id', 1, 0, 0),
(33, 'gwb_id', 1, 0, 0),
(33, 'wrz_id', 1, 1, 0),
(33, 'wasserrechtliche_zulassungen_link', 1, 0, 0),
(33, 'kennnummer', 1, 1, 0),
(33, 'wasserbuchnummer', 1, 1, 0),
(9, 'betreiber_id', 2, 1, 0),
(9, 'betreiber', 2, 0, 0),
(50, 'freigegeben', 2, 1, 0),
(2, 'anlage_bearbeiter_datum', 1, 0, 0),
(2, 'trinkwasser_koerperschaft', 1, 1, 0),
(2, 'wasserrechtliche_zulassungen', 1, 0, 0),
(17, 'konto_id', 2, 1, 0),
(49, 'abkuerzung', 2, 1, 0),
(49, 'id', 2, 0, 0),
(49, 'name', 2, 1, 0),
(25, 'bearbeiter_telefon', 1, 0, 0),
(25, 'bearbeiter_zimmer', 1, 0, 0),
(25, 'bearbeiter_fax', 4, 0, 0),
(25, 'bearbeiter_telefon', 4, 0, 0),
(25, 'bearbeiter_zimmer', 4, 0, 0),
(17, 'status', 4, 1, 0),
(49, 'abkuerzung', 4, 1, 0),
(49, 'id', 4, 0, 0),
(49, 'name', 4, 1, 0),
(33, 'freitext_zweck', 1, 1, 0),
(33, 'anlage_anzeige', 1, 0, 0),
(33, 'zweck', 4, 1, 0),
(33, 'art', 4, 1, 0),
(33, 'anlage_id', 4, 0, 0),
(33, 'gwb_id', 4, 0, 0),
(33, 'zweck', 2, 1, 0),
(33, 'anlage_id', 2, 0, 0),
(33, 'gwb_id', 2, 0, 0),
(33, 'wrz_id', 2, 0, 0),
(33, 'wasserrechtliche_zulassungen_link', 2, 0, 0),
(33, 'kennnummer', 2, 1, 0),
(25, 'regnummer', 2, 1, 0),
(25, 'bearbeiter_plz_ort', 4, 0, 0),
(25, 'aktenzeichen', 2, 1, 0),
(25, 'bearbeiterzeichen', 2, 1, 0),
(25, 'typus', 2, 1, 0),
(25, 'bezeichnung', 2, 0, 0),
(25, 'bearbeiter_email', 2, 0, 0),
(25, 'bearbeiter_plz_ort', 1, 0, 0),
(25, 'bearbeiter_fax', 2, 0, 0),
(25, 'bearbeiter_telefon', 2, 0, 0),
(25, 'bearbeiter_zimmer', 2, 0, 0),
(25, 'bearbeiter_plz_ort', 2, 0, 0),
(25, 'bearbeiter_namenszusatz', 4, 0, 0),
(25, 'bearbeiter_strasse_hausnummer', 2, 0, 0),
(25, 'bearbeiter_strasse_hausnummer', 4, 0, 0),
(25, 'bearbeiter_strasse_hausnummer', 1, 0, 0),
(25, 'bearbeiter_namenszusatz', 2, 0, 0),
(25, 'bearbeiter_name', 2, 0, 0),
(25, 'bearbeiter_link', 2, 0, 0),
(25, 'bearbeiter_name', 4, 0, 0),
(25, 'bearbeiter', 2, 1, 0),
(25, 'bearbeiter_link', 4, 0, 0),
(25, 'bearbeiter_namenszusatz', 1, 0, 0),
(25, 'bearbeiter_name', 1, 0, 0),
(25, 'bearbeiter_link', 1, 0, 0),
(25, 'adressat_plz_ort', 2, 0, 0),
(25, 'adressat_strasse_hausnummer', 2, 0, 0),
(25, 'adressat_namenszusatz', 2, 0, 0),
(25, 'adressat_name', 2, 0, 0),
(25, 'adressat_link', 2, 0, 0),
(25, 'bearbeiter', 4, 1, 0),
(25, 'adressat_plz_ort', 4, 0, 0),
(25, 'bearbeiter', 1, 1, 0),
(25, 'adressat_strasse_hausnummer', 4, 0, 0),
(25, 'adressat_namenszusatz', 4, 0, 0),
(25, 'adressat_name', 4, 0, 0),
(25, 'adressat_plz_ort', 1, 0, 0),
(25, 'adressat_link', 4, 0, 0),
(25, 'personen_id', 4, 1, 0),
(25, 'adressat_strasse_hausnummer', 1, 0, 0),
(25, 'adressat_namenszusatz', 1, 0, 0),
(25, 'personen_id', 2, 1, 0),
(25, 'adressat_name', 1, 0, 0),
(25, 'wrz_wid', 1, 0, 0),
(25, 'ausstellbehoerde_link', 2, 0, 0),
(25, 'ausstellbehoerde', 2, 1, 0),
(25, 'wrz_wid', 2, 0, 0),
(25, 'adressat_link', 1, 0, 0),
(25, 'personen_id', 1, 1, 0),
(25, 'ausstellbehoerde_link', 1, 0, 0),
(25, 'wrz_id', 2, 0, 0),
(25, 'ausstellbehoerde_link', 4, 0, 0),
(25, 'ausstellbehoerde', 4, 1, 0),
(25, 'wrz_wid', 4, 0, 0),
(25, 'wrz_id', 4, 0, 0),
(25, 'ausstellbehoerde', 1, 1, 0),
(25, 'anlage_anzeige', 2, 1, 0),
(25, 'anlage_anzeige', 4, 1, 0),
(25, 'wrz_id', 1, 0, 0),
(25, 'anlage_anzeige', 1, 1, 0),
(25, 'anlage_id', 2, 0, 0),
(25, 'anlage_klasse', 2, 0, 0),
(25, 'anlage_id', 4, 0, 0),
(25, 'anlage_klasse', 4, 0, 0),
(25, 'anlage_id', 1, 1, 0),
(25, 'anlage_klasse', 1, 0, 0);

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
(1, 1, 1198, 768, 1, 33365637.312722, 5874041.6305345, 33365936.189127, 5874241.6305345, 2, 'zoomin', '35833', '', 'dec', 0, '2017-10-25 09:20:42', 'gui.php', 'german', '0', '0', 15, 0, 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure,', 0, 1, 0, 0, 1, 1, 0, 0, 0, 400, 150, NULL, 0, 0, '', 1, 0),
(2, 2, 1198, 770, 1, 33194087.317244, 5867814.9999176, 33484977.682765, 6081467.9999999, 2, 'zoomin', '35833', NULL, 'dec', 0, '2017-10-25 09:52:01', 'gui.php', 'german', '0', '0', 15, 0, 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure', 0, 1, 0, 0, 1, 1, 0, 0, 0, 400, 150, NULL, 0, 0, '', 0, 0);

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
(1, 1, 'Layer-Suche_Suchen', 25, 'SELECT * FROM (SELECT a.oid AS fiswrv_wasserrechtliche_zulassungen_oid,  COALESCE(d.name,\'\') AS anlage_klasse,  a.anlage AS anlage_id,  b.name AS anlage_anzeige,  a.id as wrz_id,  COALESCE(b.id::text,\'\') ||\' -\'|| COALESCE(a.id::text,\'\') AS wrz_wid,  a.ausstellbehoerde,  \'\' AS ausstellbehoerde_link,  a.adressat AS personen_id,  \'\' AS adressat_link,  COALESCE(e.name,\'\') AS adressat_name,  COALESCE(e.namenszusatz,\'\') AS adressat_namenszusatz,  COALESCE(f.strasse,\'\') ||\'  \'|| COALESCE(f.hausnummer,\'\') AS adressat_strasse_hausnummer,  COALESCE(f.plz::text,\'\') ||\'  \'|| COALESCE(f.ort,\'\') AS adressat_plz_ort,  a.bearbeiter,  \'\' AS bearbeiter_link,  COALESCE(g.name,\'\') AS bearbeiter_name,  COALESCE(g.namenszusatz,\'\') AS bearbeiter_namenszusatz,  COALESCE(h.strasse,\'\') ||\'  \'|| COALESCE(h.hausnummer,\'\') AS bearbeiter_strasse_hausnummer,  COALESCE(h.plz::text,\'\') ||\'  \'|| COALESCE(h.ort,\'\') AS bearbeiter_plz_ort,  COALESCE(g.zimmer,\'\') AS bearbeiter_zimmer,  COALESCE(g.telefon,\'\') AS bearbeiter_telefon,  COALESCE(g.fax,\'\') AS bearbeiter_fax,  COALESCE(g.email,\'\') AS bearbeiter_email,   c.bezeichnung,  a.typus,  a.bearbeiterzeichen,  a.aktenzeichen,  a.regnummer,  a.bergamt_aktenzeichen,  a.ort,   a.datum,  a.fassung_auswahl,  a.fassung_nummer,  a.fassung_typus,  a.fassung_bearbeiterzeichen,  a.fassung_aktenzeichen,  a.fassung_datum,  a.gueltig_seit,  a.befristet_bis,  a.status,  a.aktuell,  CASE WHEN a.befristet_bis < current_date THEN \'nein\' ELSE \'ja\' END AS wirksam,  a.ungueltig_seit,  a.ungueltig_aufgrund,  a.datum_postausgang, a.datum_bestand_mat,  a.datum_bestand_form,  a.dokument AS dokument,  a.nachfolger AS nachfolger,  a.vorgaenger AS vorgaenger,  a.freigegeben,  \'\' AS wrz_ben   FROM fiswrv_wasserrechtliche_zulassungen a LEFT JOIN fiswrv_wasserrechtliche_zulassungen_bezeichnung c ON a.id = c.id LEFT JOIN fiswrv_anlagen b ON a.anlage=b.id LEFT JOIN fiswrv_anlagen_klasse d ON b.klasse=d.id LEFT JOIN fiswrv_personen e ON a.adressat=e.id LEFT JOIN fiswrv_adresse f ON e.adresse=f.id LEFT JOIN fiswrv_personen g ON a.bearbeiter=g.id LEFT JOIN fiswrv_adresse h ON g.adresse=h.id WHERE 1=1) as query WHERE 1=1  AND ( (1=1))', ' ORDER BY fiswrv_wasserrechtliche_zulassungen_oid ', 10, NULL),
(2, 2, 'Layer-Suche_Suchen', 33, 'SELECT * FROM (SELECT b.oid AS fiswrv_gewaesserbenutzungen_oid,  f.name AS anlage_anzeige,  a.anlage AS anlage_id,  b.id as gwb_id,  b.wasserrechtliche_zulassungen as wrz_id,  \'\' AS wasserrechtliche_zulassungen_link,  b.kennnummer,  b.wasserbuchnummer,  c.bezeichnung,  b.freitext_art,  b.art,  b.freitext_zweck,  b.zweck,  b.umfang_entnahme,  a.adressat as personen_id,  a.aktuell,  \'\' AS gewaesserbenutzungen_lage  FROM fiswrv_gewaesserbenutzungen b LEFT JOIN fiswrv_gewaesserbenutzungen_bezeichnung c ON c.id = b.id LEFT JOIN fiswrv_wasserrechtliche_zulassungen a ON b.wasserrechtliche_zulassungen = a.id  LEFT JOIN fiswrv_anlagen f ON a.anlage=f.id WHERE 1=1) as query WHERE 1=1  AND ( (1=1))', ' ORDER BY fiswrv_gewaesserbenutzungen_oid ', 10, NULL);

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
(1, 43, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
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
(1, 49, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 13, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 20, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 48, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 13, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 24, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 25, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', '', '', 0, NULL, '0', '2', 1, '0', 1),
(1, 26, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 31, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 32, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(2, 50, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 30, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 32, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 33, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 33, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 34, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 34, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 36, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 37, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 38, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 39, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 40, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 41, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 42, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 33, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 9, '1', 0, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 43, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
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
(4, 43, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
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
(1, 46, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 44, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 44, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 44, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 45, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 45, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(1, 45, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 46, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 46, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(0, 47, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '0', 1, '0', 1),
(1, 47, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 47, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 47, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(2, 48, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 48, '1', 100, NULL, -1, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
(4, 50, '1', 100, NULL, 0, 0, '', NULL, '0', '', '', NULL, NULL, 0, NULL, '0', '2', 1, '0', 1),
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
(1, 'kvwmap', 'Kvwmap', 'Hans', '', '536f8942987f8def483f847fd1631b09', '2017-06-15 07:57:32', '0000-00-00', '0000-00-00', '', 'admin', 1, '0385 / 4800532', 'admin@localhost.de'),
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
(1, 1, '2017-08-10 10:07:41', 'getMap', 1198, 802, '35833', 33303381.787194, 5987685.9045419, 33303594.521571, 5987829.4599217, '2017-08-10 09:45:59', NULL),
(1, 1, '2017-08-10 14:13:16', 'getMap', 1198, 802, '35833', 33303394.332608, 5987685.9045418, 33303581.976157, 5987829.4599218, '2017-08-10 10:07:41', NULL),
(1, 1, '2017-08-10 14:14:27', 'getMap', 1198, 802, '35833', 33303394.332608, 5987685.9045418, 33303581.976157, 5987829.4599218, '2017-08-10 14:13:16', NULL),
(1, 1, '2017-08-10 14:16:04', 'getMap', 1198, 802, '35833', 33303394.332608, 5987685.9045418, 33303581.976157, 5987829.4599218, '2017-08-10 14:14:27', NULL),
(1, 1, '2017-08-10 14:16:13', 'getMap', 1198, 802, '35833', 33303394.332608, 5987685.9045418, 33303581.976157, 5987829.4599218, '2017-08-10 14:16:04', NULL),
(1, 1, '2017-08-10 14:20:09', 'getMap', 1198, 772, '35833', 33303390.681955, 5987685.9045418, 33303585.62681, 5987829.4599218, '2017-08-10 14:16:13', NULL),
(1, 1, '2017-08-11 10:58:30', 'getMap', 1198, 772, '35833', 33303390.681955, 5987685.9045416, 33303585.62681, 5987829.459922, '2017-08-10 14:20:09', NULL),
(1, 1, '2017-08-11 11:00:07', 'getMap', 1198, 772, '35833', 33303362.402209, 5987660.2430876, 33303596.235028, 5987832.435163, '2017-08-11 10:58:30', NULL),
(1, 1, '2017-08-18 08:52:33', 'getMap', 500, 500, '35833', 33303332.169678, 5987651.5589808, 33303626.467559, 5987841.1192698, '2017-08-11 11:00:07', NULL),
(1, 1, '2017-08-21 10:03:53', 'getMap', 1198, 802, '35833', 33303286.977869, 5987599.1901848, 33303671.659368, 5987893.4880658, '2017-08-18 08:52:33', NULL),
(1, 1, '2017-08-21 10:14:33', 'getMap', 1198, 802, '35833', 33303261.258953, 5987599.1901848, 33303697.378284, 5987893.4880658, '2017-08-21 10:03:53', NULL),
(1, 1, '2017-08-30 10:14:52', 'getMap', 1198, 802, '35833', 33303259.366247, 5987578.0661076, 33303699.27099, 5987914.612143, '2017-08-21 10:14:33', NULL),
(1, 1, '2017-08-30 10:23:44', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 10:14:52', NULL),
(1, 1, '2017-08-30 10:24:43', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 10:23:44', NULL),
(1, 1, '2017-08-30 10:38:26', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 10:24:43', NULL),
(1, 1, '2017-08-30 11:34:13', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 10:38:26', NULL),
(1, 1, '2017-08-30 11:45:58', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 11:34:13', NULL),
(1, 1, '2017-08-30 13:33:32', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 11:45:58', NULL),
(1, 1, '2017-08-30 14:09:18', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 13:33:32', NULL),
(1, 1, '2017-08-30 14:17:00', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:09:18', NULL),
(1, 1, '2017-08-30 14:17:19', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:17:00', NULL),
(1, 1, '2017-08-30 14:20:03', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:17:19', NULL),
(1, 1, '2017-08-30 14:21:03', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:20:03', NULL),
(1, 1, '2017-08-30 14:21:30', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:21:03', NULL),
(1, 1, '2017-08-30 14:23:36', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:21:30', NULL),
(1, 1, '2017-08-30 14:24:20', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:23:36', NULL),
(1, 1, '2017-08-30 14:31:36', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:24:20', NULL),
(1, 1, '2017-08-30 14:34:41', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:31:36', NULL),
(1, 1, '2017-08-30 14:44:52', 'getMap', 1198, 802, '35833', 33303427.268821, 5987728.6848893, 33303664.850962, 5987910.445439, '2017-08-30 14:34:41', NULL),
(1, 1, '2017-08-30 15:16:16', 'getMap', 1198, 770, '35833', 33303422.325629, 5987728.6848893, 33303669.794154, 5987910.445439, '2017-08-30 14:44:52', NULL),
(1, 1, '2017-08-30 15:23:54', 'getMap', 1198, 770, '35833', 33303422.325629, 5987728.6848892, 33303669.794154, 5987910.4454391, '2017-08-30 15:16:16', NULL),
(1, 1, '2017-08-30 15:24:59', 'getMap', 1198, 770, '35833', 33303422.325629, 5987728.6848892, 33303669.794154, 5987910.4454391, '2017-08-30 15:23:54', NULL),
(1, 1, '2017-08-30 15:26:42', 'getMap', 1198, 770, '35833', 33303422.325629, 5987728.6848892, 33303669.794154, 5987910.4454391, '2017-08-30 15:24:59', NULL),
(1, 1, '2017-08-30 15:27:32', 'getMap', 1198, 802, '35833', 33303507.570035, 5987795.3482381, 33303579.827066, 5987850.6279726, '2017-08-30 15:26:42', NULL),
(1, 1, '2017-08-30 15:31:59', 'getMap', 1198, 770, '35833', 33303506.066638, 5987795.3482381, 33303581.330463, 5987850.6279726, '2017-08-30 15:27:32', NULL),
(1, 1, '2017-08-30 15:32:24', 'getMap', 1198, 770, '35833', 33303506.066638, 5987795.3482381, 33303581.330463, 5987850.6279726, '2017-08-30 15:31:59', NULL),
(1, 1, '2017-08-30 15:33:28', 'getMap', 1198, 770, '35833', 33303506.066638, 5987795.3482381, 33303581.330463, 5987850.6279726, '2017-08-30 15:32:24', NULL),
(1, 1, '2017-08-30 15:36:32', 'getMap', 1198, 770, '35833', 33303506.066638, 5987795.3482381, 33303581.330463, 5987850.6279726, '2017-08-30 15:33:28', NULL),
(1, 1, '2017-08-30 15:36:56', 'getMap', 1198, 802, '35833', 33303517.054582, 5987815.7699085, 33303564.453556, 5987852.0321607, '2017-08-30 15:36:32', NULL),
(1, 1, '2017-08-30 15:37:00', 'getMap', 1198, 802, '35833', 33303520.808508, 5987818.8723412, 33303563.820364, 5987851.7782591, '2017-08-30 15:36:56', NULL),
(1, 1, '2017-08-30 15:39:18', 'getMap', 1198, 770, '35833', 33303519.913593, 5987818.8723412, 33303564.715279, 5987851.7782591, '2017-08-30 15:37:00', NULL),
(1, 1, '2017-08-30 15:40:36', 'getMap', 1198, 770, '35833', 33303519.913593, 5987818.872341, 33303564.715279, 5987851.7782593, '2017-08-30 15:39:18', NULL),
(1, 1, '2017-08-30 15:42:13', 'getMap', 1198, 770, '35833', 33303519.913593, 5987818.872341, 33303564.715279, 5987851.7782593, '2017-08-30 15:40:36', NULL),
(1, 1, '2017-08-30 15:43:28', 'getMap', 1198, 770, '35833', 33303519.913593, 5987818.872341, 33303564.715279, 5987851.7782593, '2017-08-30 15:42:13', NULL),
(1, 1, '2017-08-30 15:43:30', 'getMap', 1198, 770, '35833', 33303519.913593, 5987818.872341, 33303564.715279, 5987851.7782593, '2017-08-30 15:43:28', NULL),
(1, 1, '2017-08-30 15:44:14', 'getMap', 1198, 802, '35833', 33303519.913593, 5987818.1876925, 33303564.715279, 5987852.4629078, '2017-08-30 15:43:30', NULL),
(1, 1, '2017-08-30 15:46:11', 'getMap', 1198, 802, '35833', 33303519.913593, 5987818.1876925, 33303564.715279, 5987852.4629078, '2017-08-30 15:44:14', NULL),
(1, 1, '2017-08-31 08:53:25', 'getMap', 500, 500, '35833', 33303519.913593, 5987818.1876925, 33303564.715279, 5987852.4629078, '2017-08-30 15:46:11', NULL),
(1, 1, '2017-08-31 10:34:01', 'getMap', 1198, 802, '35833', 33303513.033933, 5987812.9244571, 33303571.594939, 5987857.7261432, '2017-08-31 08:53:25', NULL),
(1, 1, '2017-08-31 10:40:31', 'getMap', 1198, 802, '35833', 33303513.033933, 5987812.9244569, 33303571.594939, 5987857.7261434, '2017-08-31 10:34:01', NULL),
(1, 1, '2017-08-31 14:12:55', 'getMap', 1198, 802, '35833', 33303513.033933, 5987812.9244569, 33303571.594939, 5987857.7261434, '2017-08-31 10:40:31', NULL),
(1, 1, '2017-08-31 14:13:12', 'getMap', 1198, 802, '35833', 33303513.033933, 5987812.9244569, 33303571.594939, 5987857.7261434, '2017-08-31 14:12:55', NULL),
(1, 1, '2017-08-31 14:13:26', 'getMap', 1198, 802, '35833', 33303513.033933, 5987812.9244569, 33303571.594939, 5987857.7261434, '2017-08-31 14:13:12', NULL),
(1, 1, '2017-08-31 14:14:17', 'getMap', 1198, 802, '35833', 33303513.033933, 5987812.9244569, 33303571.594939, 5987857.7261434, '2017-08-31 14:13:26', NULL),
(1, 1, '2017-08-31 14:16:34', 'getMap', 1198, 802, '35833', 33303513.033933, 5987812.9244569, 33303571.594939, 5987857.7261434, '2017-08-31 14:14:17', NULL),
(1, 1, '2017-08-31 14:16:49', 'getMap', 1198, 802, '35833', 33303408.540535, 5987764.6447464, 33303583.385371, 5987898.4085605, '2017-08-31 14:16:34', NULL),
(1, 1, '2017-08-31 14:16:53', 'getMap', 1198, 802, '35833', 33303472.772655, 5987808.4944998, 33303510.310907, 5987837.212876, '2017-08-31 14:16:49', NULL),
(1, 1, '2017-08-31 14:16:58', 'getMap', 1198, 802, '35833', 33303495.231132, 5987810.2384191, 33303499.565224, 5987813.5541858, '2017-08-31 14:16:53', NULL),
(1, 1, '2017-08-31 14:17:05', 'getMap', 1198, 802, '35833', 33303495.417233, 5987810.3119462, 33303499.461834, 5987813.40624, '2017-08-31 14:16:58', NULL),
(1, 1, '2017-08-31 14:17:17', 'getMap', 1198, 802, '35833', 33303492.437625, 5987810.1306098, 33303500.824373, 5987816.5468325, '2017-08-31 14:17:05', NULL),
(1, 1, '2017-08-31 14:17:22', 'getMap', 1198, 802, '35833', 33303478.526563, 5987798.5942094, 33303503.571777, 5987817.7548744, '2017-08-31 14:17:17', NULL),
(1, 1, '2017-08-31 14:17:26', 'getMap', 1198, 802, '35833', 33303485.039124, 5987798.9525759, 33303503.463908, 5987813.0483271, '2017-08-31 14:17:22', NULL),
(1, 1, '2017-08-31 14:17:49', 'getMap', 1198, 802, '35833', 33303424.156465, 5987774.4153861, 33303506.681213, 5987837.5503654, '2017-08-31 14:17:26', NULL),
(1, 1, '2017-08-31 14:17:54', 'getMap', 1198, 802, '35833', 33303478.254272, 5987794.0798662, 33303502.271494, 5987812.4540729, '2017-08-31 14:17:49', NULL),
(1, 1, '2017-08-31 14:17:57', 'getMap', 1198, 802, '35833', 33303490.194131, 5987799.6224321, 33303501.308972, 5987808.1257626, '2017-08-31 14:17:54', NULL),
(1, 1, '2017-08-31 14:17:59', 'getMap', 1198, 802, '35833', 33303492.919812, 5987800.0027718, 33303501.086251, 5987806.2504482, '2017-08-31 14:17:57', NULL),
(1, 1, '2017-08-31 14:18:01', 'getMap', 1198, 802, '35833', 33303496.395224, 5987800.7968138, 33303500.805724, 5987804.1710362, '2017-08-31 14:17:59', NULL),
(1, 1, '2017-08-31 14:18:03', 'getMap', 1198, 802, '35833', 33303497.47476, 5987801.0576639, 33303500.719392, 5987803.5399472, '2017-08-31 14:18:01', NULL),
(1, 1, '2017-08-31 14:18:05', 'getMap', 1198, 802, '35833', 33303498.270438, 5987801.1222656, 33303500.654376, 5987802.9460806, '2017-08-31 14:18:03', NULL),
(1, 1, '2017-08-31 14:18:07', 'getMap', 1198, 802, '35833', 33303498.882346, 5987801.8746813, 33303499.257679, 5987802.1618277, '2017-08-31 14:18:05', NULL),
(1, 1, '2017-08-31 14:18:14', 'getMap', 1198, 802, '35833', 33303498.882346, 5987801.8746813, 33303499.257679, 5987802.1618277, '2017-08-31 14:18:07', NULL),
(1, 1, '2017-08-31 14:19:03', 'getMap', 1198, 802, '35833', 33303498.882346, 5987801.8746813, 33303499.257679, 5987802.1618277, '2017-08-31 14:18:14', NULL),
(1, 1, '2017-08-31 15:29:06', 'getMap', 1198, 802, '35833', 33303498.882346, 5987801.8746813, 33303499.257679, 5987802.1618277, '2017-08-31 14:19:03', NULL),
(1, 1, '2017-09-04 17:42:26', 'getMap', 1198, 802, '35833', 33303498.882346, 5987801.8746813, 33303499.257679, 5987802.1618277, '2017-08-31 15:29:06', NULL),
(1, 1, '2017-09-05 09:44:26', 'getMap', 1198, 802, '35833', 33303498.882346, 5987801.8746813, 33303499.257679, 5987802.1618277, '2017-09-04 17:42:26', NULL),
(1, 1, '2017-09-05 11:07:55', 'getMap', 1198, 802, '35833', 33303483.234709, 5987789.9035667, 33303514.905316, 5987814.1329423, '2017-09-05 09:44:26', NULL),
(1, 1, '2017-09-05 15:12:47', 'getMap', 1198, 802, '35833', 33303483.234709, 5987789.9035667, 33303514.905316, 5987814.1329423, '2017-09-05 11:07:55', NULL),
(1, 1, '2017-09-05 15:19:30', 'getMap', 1198, 802, '35833', 33303483.234709, 5987789.9035667, 33303514.905316, 5987814.1329423, '2017-09-05 15:12:47', NULL),
(1, 1, '2017-09-13 15:15:55', 'getMap', 1198, 802, '35833', 33303480.937982, 5987789.782571, 33303517.202043, 5987814.253938, '2017-09-05 15:19:30', NULL),
(1, 1, '2017-09-15 09:59:57', 'getMap', 1198, 802, '35833', 33303480.937982, 5987788.1464719, 33303517.202043, 5987815.8900371, '2017-09-13 15:15:55', NULL),
(1, 1, '2017-09-15 10:52:17', 'getMap', 1198, 802, '35833', 33303480.937982, 5987788.1464719, 33303517.202043, 5987815.8900371, '2017-09-15 09:59:57', NULL),
(1, 1, '2017-09-15 10:52:25', 'getMap', 1198, 802, '35833', 33303442.2171, 5987760.4925578, 33303532.462015, 5987829.5337966, '2017-09-15 10:52:17', NULL),
(1, 1, '2017-09-15 10:52:29', 'getMap', 1198, 802, '35833', 33303443.078216, 5987785.9674587, 33303484.842322, 5987817.9187953, '2017-09-15 10:52:25', NULL),
(1, 1, '2017-09-15 11:47:00', 'getMap', 1198, 802, '35833', 33303443.078216, 5987785.9674587, 33303484.842322, 5987817.9187953, '2017-09-15 10:52:29', NULL),
(1, 1, '2017-09-18 09:31:51', 'getMap', 1198, 802, '35833', 33303422.423147, 5987766.3265491, 33303504.939407, 5987821.5441967, '2017-09-15 11:47:00', NULL),
(1, 1, '2017-09-18 09:31:52', 'getMap', 1198, 802, '35833', 33179892.898819, 5867814.9999175, 33499172.10119, 6081468, '2017-09-18 09:31:51', NULL),
(1, 1, '2017-09-18 17:53:37', 'getMap', 1198, 770, '35833', 33179892.898819, 5857389.4910651, 33499172.10119, 6091893.5088524, '2017-09-18 09:31:52', NULL),
(1, 1, '2017-09-19 16:02:21', 'getMap', 1198, 802, '35833', 33201165.000009, 5867814.9999175, 33477900, 6081468, '2017-09-18 17:53:37', NULL),
(1, 1, '2017-09-20 10:30:11', 'getMap', 1198, 802, '35833', 33199897.861374, 5867814.9999175, 33479167.138635, 6081468, '2017-09-19 16:02:21', NULL),
(1, 1, '2017-09-20 11:51:46', 'getMap', 1198, 802, '35833', 33181471.535172, 5853718.0684107, 33497593.464837, 6095564.9315068, '2017-09-20 10:30:11', NULL),
(1, 1, '2017-10-10 08:41:36', 'getMap', 1198, 802, '35833', 33201165.000009, 5867814.9999175, 33477900, 6081468, '2017-09-20 11:51:46', NULL),
(1, 1, '2017-10-10 09:58:12', 'getMap', 1438, 872, '35833', 33181684.388003, 5867814.9999175, 33497380.612006, 6081468, '2017-10-10 08:41:36', NULL),
(1, 1, '2017-10-10 10:01:35', 'getMap', 1438, 872, '35833', 33163287.172152, 5867814.9999175, 33515777.827857, 6081468, '2017-10-10 09:58:12', NULL),
(1, 1, '2017-10-10 10:01:42', 'getMap', 1438, 872, '35833', 33163287.172152, 5867814.9999173, 33515777.827857, 6081468.0000002, '2017-10-10 10:01:35', NULL),
(1, 1, '2017-10-10 10:01:57', 'getMap', 1438, 872, '35833', 33163287.172152, 5867814.9999173, 33515777.827857, 6081468.0000002, '2017-10-10 10:01:42', NULL),
(1, 1, '2017-10-10 10:06:43', 'getMap', 1438, 872, '35833', 33201165.000009, 5867814.9999175, 33477900, 6081468, '2017-10-10 10:01:57', NULL),
(1, 1, '2017-10-10 10:21:52', 'getMap', 1438, 872, '35833', 33181684.388003, 5867814.9999175, 33497380.612006, 6081468, '2017-10-10 10:06:43', NULL),
(1, 1, '2017-10-10 10:21:58', 'getMap', 1438, 872, '35833', 33321278.866618, 5987137.2602847, 33416856.622509, 6051821.1960896, '2017-10-10 10:21:52', NULL),
(1, 1, '2017-10-10 10:22:41', 'getMap', 1438, 872, '35833', 33321278.866618, 5987137.2602846, 33416856.622509, 6051821.1960897, '2017-10-10 10:21:58', NULL),
(1, 1, '2017-10-10 11:10:33', 'getMap', 1438, 872, '35833', 33321278.866618, 5987137.2602846, 33416856.622509, 6051821.1960897, '2017-10-10 10:22:41', NULL),
(1, 1, '2017-10-10 11:12:55', 'getMap', 1438, 872, '35833', 33321278.866618, 5987137.2602846, 33416856.622509, 6051821.1960897, '2017-10-10 11:10:33', NULL),
(1, 1, '2017-10-10 11:13:39', 'getMap', 1438, 872, '35833', 33321278.866618, 5987137.2602846, 33416856.622509, 6051821.1960897, '2017-10-10 11:12:55', NULL),
(1, 1, '2017-10-10 11:16:13', 'getMap', 1438, 872, '35833', 33321278.866618, 5987137.2602846, 33416856.622509, 6051821.1960897, '2017-10-10 11:13:39', NULL),
(1, 1, '2017-10-10 11:28:42', 'getMap', 1438, 872, '35833', 33321278.866618, 5987137.2602846, 33416856.622509, 6051821.1960897, '2017-10-10 11:16:13', NULL),
(2, 2, '2017-10-11 17:25:17', 'getMap', 1198, 770, '35833', 224298.39316204, 5890464.3996456, 468644.96396628, 6047442.271783, '2017-07-12 11:18:57', NULL),
(2, 2, '2017-10-11 17:25:25', 'getMap', 1198, 770, '35833', 33201165.000009, 5867814.9999176, 33477900, 6081467.9999999, '2017-10-11 17:25:17', NULL),
(1, 1, '2017-10-12 07:58:04', 'getMap', 1198, 802, '35833', 33326793.037455, 5987137.2602849, 33411342.451672, 6051821.1960894, '2017-10-10 11:28:42', NULL),
(1, 1, '2017-10-12 07:58:05', 'getMap', 1198, 802, '35833', 33326793.037455, 5987137.2602847, 33411342.451672, 6051821.1960896, '2017-10-12 07:58:04', NULL),
(1, 1, '2017-10-12 11:23:11', 'getMap', 1198, 768, '35833', 33320736.489159, 5984073.2368832, 33417398.999968, 6054885.2194911, '2017-10-12 07:58:05', NULL),
(1, 1, '2017-10-13 08:24:15', 'getMap', 1198, 802, '35833', 33320736.489159, 5982503.7405281, 33417398.999968, 6056454.7158462, '2017-10-12 11:23:11', NULL),
(1, 1, '2017-10-13 14:01:31', 'getMap', 1198, 802, '35833', 33320736.489159, 5982503.7405281, 33417398.999968, 6056454.7158462, '2017-10-13 08:24:15', NULL),
(1, 1, '2017-10-13 14:03:13', 'getMap', 1198, 802, '35833', 33320736.489159, 5982503.7405281, 33417398.999968, 6056454.7158462, '2017-10-13 14:01:31', NULL),
(1, 1, '2017-10-13 14:04:01', 'getMap', 1198, 802, '35833', 33320736.489159, 5982503.7405281, 33417398.999968, 6056454.7158462, '2017-10-13 14:03:13', NULL),
(1, 1, '2017-10-13 14:07:09', 'getMap', 1198, 802, '35833', 33320736.489159, 5982503.7405281, 33417398.999968, 6056454.7158462, '2017-10-13 14:04:01', NULL),
(1, 1, '2017-10-17 16:31:47', 'getMap', 1198, 772, '35833', 33318855.895564, 5982503.7405283, 33419279.593563, 6056454.715846, '2017-10-13 14:07:09', NULL),
(1, 1, '2017-10-24 17:00:42', 'getMap', 1198, 802, '35833', 33311662.220922, 5975561.5353153, 33426473.268205, 6063396.921059, '2017-10-17 16:31:47', NULL),
(2, 2, '2017-10-24 17:07:20', 'getMap', 1198, 770, '35833', 33201165.000009, 5867814.9999176, 33477900, 6081467.9999999, '2017-10-11 17:25:25', NULL),
(1, 1, '2017-10-24 17:12:44', 'getMap', 1198, 802, '35833', 33311662.220922, 5975561.5353153, 33426473.268205, 6063396.921059, '2017-10-24 17:00:42', NULL),
(2, 2, '2017-10-24 17:16:29', 'getMap', 1198, 770, '35833', 33194087.317244, 5867814.9999176, 33484977.682765, 6081467.9999999, '2017-10-24 17:07:20', NULL),
(2, 2, '2017-10-24 17:18:02', 'getMap', 1198, 770, '35833', 33194087.317244, 5867814.9999176, 33484977.682765, 6081467.9999999, '2017-10-24 17:16:29', NULL),
(2, 2, '2017-10-24 17:23:02', 'getMap', 1198, 770, '35833', 33194087.317244, 5867814.9999176, 33484977.682765, 6081467.9999999, '2017-10-24 17:18:02', NULL),
(1, 1, '2017-10-25 08:53:48', 'getMap', 1198, 768, '35833', 33309117.517136, 5975561.5353153, 33429017.971991, 6063396.921059, '2017-10-24 17:12:44', NULL),
(1, 1, '2017-10-25 08:57:30', 'getMap', 1198, 768, '35833', 33309117.517136, 5975561.5353152, 33429017.971991, 6063396.9210591, '2017-10-25 08:53:48', NULL),
(1, 1, '2017-10-25 09:20:42', 'getMap', 1198, 802, '35833', 33365637.312722, 5874041.6305345, 33365936.189127, 5874241.6305345, '2017-10-25 08:57:30', NULL),
(2, 2, '2017-10-25 09:47:36', 'getMap', 1198, 770, '35833', 33194087.317244, 5867814.9999176, 33484977.682765, 6081467.9999999, '2017-10-24 17:23:02', NULL),
(2, 2, '2017-10-25 09:52:01', 'getMap', 1198, 770, '35833', 33194087.317244, 5867814.9999176, 33484977.682765, 6081467.9999999, '2017-10-25 09:47:36', NULL);

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
(1, 1, 50, 0),
(1, 1, 49, 0),
(1, 1, 48, 0),
(1, 1, 47, 0),
(1, 1, 46, 0),
(1, 1, 45, 0),
(1, 1, 44, 0),
(1, 1, 43, 1),
(1, 1, 42, 0),
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
(1, 1, 25, 1),
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
(2, 2, 49, 0),
(2, 2, 48, 0),
(2, 2, 47, 0),
(2, 2, 46, 0),
(2, 2, 45, 0),
(2, 2, 44, 0),
(2, 2, 43, 1),
(2, 2, 41, 0),
(2, 2, 38, 0),
(2, 2, 37, 0),
(2, 2, 30, 0),
(2, 2, 29, 0),
(2, 2, 28, 0),
(2, 2, 27, 0),
(2, 2, 26, 0),
(2, 2, 25, 1),
(2, 2, 24, 0),
(2, 2, 23, 0),
(2, 2, 22, 0),
(2, 2, 20, 0),
(2, 2, 19, 1),
(2, 2, 10, 0),
(2, 2, 9, 0),
(1, 1, 9, 0),
(1, 1, 8, 0),
(1, 1, 7, 0),
(2, 2, 8, 0),
(2, 2, 7, 0),
(1, 1, 6, 0),
(1, 1, 5, 0),
(1, 1, 4, 1),
(1, 1, 3, 0),
(2, 2, 6, 0),
(2, 2, 5, 0),
(2, 2, 4, 1),
(2, 2, 3, 0),
(1, 1, 2, 0),
(2, 2, 2, 0),
(1, 1, 1, 0),
(1, 1, 51, 0),
(2, 2, 50, 0),
(2, 2, 51, 0);

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
(1, 33, 49),
(1, 32, 48),
(1, 31, 47),
(1, 30, 46),
(1, 29, 45),
(1, 28, 44),
(1, 27, 43),
(1, 26, 42),
(1, 37, 41),
(1, 25, 40),
(1, 24, 39),
(1, 23, 38),
(1, 22, 37),
(1, 21, 36),
(1, 20, 35),
(1, 19, 34),
(1, 18, 33),
(1, 17, 32),
(1, 16, 31),
(1, 15, 30),
(1, 14, 29),
(1, 13, 28),
(1, 12, 27),
(1, 11, 26),
(1, 40, 25),
(1, 39, 24),
(1, 10, 23),
(1, 9, 22),
(1, 8, 21),
(1, 7, 20),
(1, 6, 19),
(1, 5, 18),
(1, 41, 17),
(1, 4, 16),
(1, 3, 15),
(1, 2, 14),
(1, 1, 13),
(1, 36, 12),
(1, 42, 11),
(1, 35, 10),
(1, 51, 9),
(2, 29, 29),
(2, 37, 28),
(2, 30, 27),
(2, 28, 26),
(2, 25, 25),
(2, 23, 24),
(2, 24, 23),
(2, 20, 22),
(2, 22, 21),
(2, 19, 20),
(2, 8, 19),
(2, 9, 18),
(2, 41, 17),
(2, 5, 16),
(2, 10, 15),
(2, 7, 14),
(4, 46, 3),
(4, 20, 13),
(4, 25, 17),
(4, 51, 8),
(4, 3, 10),
(4, 48, 5),
(4, 44, 6),
(4, 23, 16),
(4, 24, 15),
(4, 21, 14),
(4, 28, 18),
(4, 45, 2),
(4, 38, 0),
(4, 43, 1),
(4, 30, 19),
(4, 47, 4),
(2, 6, 13),
(2, 4, 12),
(2, 3, 11),
(2, 2, 10),
(2, 51, 9),
(2, 50, 8),
(2, 49, 7),
(1, 50, 8),
(1, 49, 7),
(1, 44, 6),
(2, 44, 6),
(2, 48, 5),
(4, 37, 20),
(4, 29, 21),
(1, 45, 5),
(1, 48, 4),
(1, 47, 3),
(1, 46, 2),
(2, 47, 4),
(2, 46, 3),
(2, 45, 2),
(2, 43, 1),
(4, 50, 7),
(4, 2, 9),
(4, 22, 12),
(4, 19, 11),
(1, 43, 1),
(2, 38, 0),
(1, 38, 0),
(1, 34, 50),
(2, 26, 30),
(2, 27, 31),
(4, 26, 22),
(4, 27, 23);

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
(42, 'Wasserrecht Deploy', '', '', '', '', 'index.php?go=wasserrecht_deploy', '', 35, 2, '', 0, '', ''),
(43, 'Wasserrecht', '', '', '', '', 'index.php?go=changemenue', '', 0, 1, '', 0, '', ''),
(44, 'Wasserentnahmebenutzer', '', '', '', '', 'index.php?go=wasserentnahmebenutzer', '', 43, 2, '', 1, '', ''),
(45, 'Neue FisWrV-WRe Anlage', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=2', '', 43, 2, '', 0, '', ''),
(46, 'Neue FisWrV-WRe Gewässerbenutzung', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=33', '', 43, 2, '', 0, '', ''),
(47, 'Neue FisWrV-WRe Person', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=9', '', 43, 2, '', 0, '', ''),
(48, 'Neue FisWrV-WRe WrZ', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=25', '', 43, 2, '', 0, '', ''),
(49, 'Wasserentnahmeentgelt', '', '', '', '', 'index.php?go=wasserentnahmeentgelt', '', 43, 2, '', 2, '', ''),
(50, 'Zentrale Stelle', '', '', '', '', 'index.php?go=zentrale_stelle', '', 43, 2, '', 3, '', ''),
(51, 'Erstattung des Verwaltungsaufwands', '', '', '', '', 'index.php?go=erstattung_des_verwaltungsaufwands', '', 43, 2, '', 4, '', '');

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
(1, 1, 43, '0', '0', NULL, 1, '0', NULL),
(2, 2, 1, '1', '1', NULL, 1, '0', 51),
(1, 1, 9, '0', '0', 1, 1, '0', NULL),
(1, 1, 10, '0', '0', NULL, 1, '0', NULL),
(2, 2, 25, '0', '0', NULL, 1, '0', NULL),
(1, 1, 11, '0', '0', NULL, 1, '0', NULL),
(1, 1, 12, '0', '0', NULL, 1, '0', NULL),
(1, 1, 13, '0', '0', NULL, 1, '0', NULL),
(1, 1, 14, '0', '0', NULL, 1, '0', NULL),
(1, 1, 47, '0', '0', NULL, 1, '0', NULL),
(1, 1, 16, '0', '0', NULL, 1, '0', NULL),
(2, 2, 2, '0', '0', NULL, 1, '0', NULL),
(1, 1, 17, '0', '0', NULL, 1, '0', NULL),
(1, 1, 48, '0', '0', NULL, 1, '0', NULL),
(1, 1, 20, '0', '0', NULL, 1, '0', NULL),
(1, 1, 49, '0', '0', NULL, 1, '0', NULL),
(2, 2, 13, '0', '0', NULL, 1, '0', NULL),
(2, 2, 50, '0', '0', NULL, 1, '0', NULL),
(1, 1, 24, '0', '0', NULL, 1, '0', NULL),
(1, 1, 25, '0', '0', 1, 1, '0', NULL),
(1, 1, 26, '0', '0', NULL, 1, '0', NULL),
(1, 1, 31, '0', '0', NULL, 1, '0', NULL),
(1, 1, 32, '0', '0', NULL, 1, '0', NULL),
(1, 1, 30, '0', '0', NULL, 1, '0', NULL),
(1, 1, 33, '0', '0', NULL, 1, '0', NULL),
(1, 1, 34, '0', '0', NULL, 1, '0', NULL),
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
(1, 1, 45, '0', '0', NULL, 1, '0', NULL),
(2, 2, 44, '0', '0', NULL, 1, '0', NULL),
(1, 1, 44, '0', '0', NULL, 1, '0', NULL),
(2, 2, 45, '0', '0', NULL, 1, '0', NULL),
(2, 2, 48, '0', '0', NULL, 1, '0', NULL),
(2, 2, 46, '0', '0', NULL, 1, '0', NULL),
(1, 1, 46, '0', '0', NULL, 1, '0', NULL),
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
  MODIFY `Layer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;