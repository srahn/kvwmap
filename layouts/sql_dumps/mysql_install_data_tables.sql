/*-----------------------------------------------------------------------
-- Statements zur Erstellung der Datenstruktur von Sachdaten in MySQL
-- Diese Datei wird nicht mehr weiter gepflegt seit: 2008-01-15
-- die Statements werden nicht mehr länger benötigt, nur wer unbedingt
-- Administrative Grenzen
-- ALK Daten (aus shape Konverter)
-- ALB Daten (aus WLDGE2SQL)
-- in MySQL vorhalten will. Wir empfehlen dafür Postgres

-----------------------------------------------------------------------
-- Diese Tabellen werden im normalen Betrieb nicht benötigt
-- Daten werden besser aus der PostgreSQL Datenbank bezogen
-- Tabellen für ALK sollten mit dem EDBS2WKT Konverter in der postgres
-- Datenbank ertellt werden
-- Statements zur Erstellung von Datentabellen für ALB befinden
-- sich in der Datei postgis_install.sql

-- Tabellen für Administrative Daten
-- --------------------------------------------------------------------
*/
-- 
-- Table structure for table `Adm_Fluren`
-- 

CREATE TABLE Adm_Fluren (
  KREIS varchar(255) default NULL,
  AMT varchar(255) default NULL,
  GEMEINDE varchar(255) default NULL,
  GEMARKUNG varchar(255) default NULL,
  FLUR int(3) NOT NULL default '0',
  FLUR_ID int(9) NOT NULL default '0',
  ID int(11) NOT NULL default '0',
  GemkgSchl int(6) NOT NULL default '0',
  Labeltxt varchar(255) NOT NULL default '',
  PRIMARY KEY  (ID),
  KEY GemkgSchl (GemkgSchl),
  KEY FLUR_ID (FLUR_ID),
  KEY FLUR (FLUR)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `Adm_Gemarkungen`
-- 

CREATE TABLE Adm_Gemarkungen (
  GEMEINDE_L int(8) NOT NULL default '0',
  GEMEINDE_I int(3) NOT NULL default '0',
  GEMARKUNG_ int(6) NOT NULL default '0',
  COUNT int(3) default NULL,
  GEMARK_ID int(4) NOT NULL default '0',
  GEMARKUNG varchar(255) default NULL,
  ID int(11) NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY GEMEINDE_L (GEMEINDE_L),
  KEY GEMARKUNG_ (GEMARKUNG_),
  KEY GEMARK_ID (GEMARK_ID)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `Adm_Gemeinden`
-- 

CREATE TABLE Adm_Gemeinden (
  GEMEINDE_L int(8) NOT NULL default '0',
  COUNT int(3) default NULL,
  AMT_LANG_I int(4) NOT NULL default '0',
  AMT_ID int(2) NOT NULL default '0',
  GEMEINDE_I int(3) NOT NULL default '0',
  GEMEINDE varchar(255) default NULL,
  POLYNAME varchar(255) default NULL,
  ID int(11) NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY GEMEINDE_L (GEMEINDE_L),
  KEY AMT_LANG_I (AMT_LANG_I),
  KEY GEMEINDE_I (GEMEINDE_I),
  KEY AMT_ID (AMT_ID)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `Adm_Landkreise`
-- 

CREATE TABLE Adm_Landkreise (
  AREA text,
  PERIMETER double default NULL,
  KREIS_ double default NULL,
  KREIS_ID double default NULL,
  KREIS text,
  ID int(11) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;


-- Tabellen für ALK Daten aus shape Konverter
-- --------------------------------------------------------

-- 
-- Table structure for table `ALK_Ausgest`
-- 

CREATE TABLE ALK_Ausgest (
  OBJGR smallint(6) default NULL,
  OBJID text,
  FOLIE smallint(6) default NULL,
  OBJART smallint(6) default NULL,
  INFOART smallint(6) default NULL,
  INFOTEXT text,
  KURZTEXT text,
  ARTGEO smallint(6) default NULL,
  DARST smallint(6) default NULL,
  AKTUAL smallint(6) default NULL,
  QUELLE smallint(6) default NULL,
  ID int(11) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `ALK_Flurst`
-- 

CREATE TABLE ALK_Flurst (
  OBJGR smallint(6) default NULL,
  OBJID varchar(255) NOT NULL default '',
  FOLIE smallint(6) default NULL,
  OBJART smallint(6) default NULL,
  TEXTART varchar(255) default NULL,
  INFOART smallint(6) default NULL,
  INFOTEXT varchar(21) default NULL,
  AKTUAL smallint(6) default NULL,
  QUELLE smallint(6) default NULL,
  ID int(11) NOT NULL default '0',
  FKZ varchar(23) NOT NULL default '',
  GEMKGSCHL int(6) NOT NULL default '0',
  FLURNR int(3) NOT NULL default '0',
  ZAEHLER int(5) NOT NULL default '0',
  NENNER int(3) NOT NULL default '0',
  FLURSTNR varchar(9) NOT NULL default '',
  FLURSTBEZ varchar(15) NOT NULL default '',
  PRIMARY KEY  (ID),
  KEY GemkgSchl (GEMKGSCHL),
  KEY Zaehler (ZAEHLER),
  KEY Nenner (NENNER),
  KEY FlurNr (FLURNR),
  KEY FlurstKennz (FKZ)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `ALK_Fortfuehrung`
-- 

CREATE TABLE ALK_Fortfuehrung (
  id int(11) NOT NULL auto_increment,
  timestamp timestamp(14) NOT NULL,
  anzflurstuecke int(11) NOT NULL default '0',
  anzgebaeude int(11) NOT NULL default '0',
  anznutzungen int(11) NOT NULL default '0',
  anzausgestaltungen int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `ALK_Gebaeude`
-- 

CREATE TABLE ALK_Gebaeude (
  OBJGR smallint(6) default NULL,
  OBJID text,
  FOLIE smallint(6) default NULL,
  OBJART smallint(6) default NULL,
  TEXTART text,
  INFOART smallint(6) default NULL,
  INFOTEXT text,
  AKTUAL smallint(6) default NULL,
  QUELLE smallint(6) default NULL,
  ID int(11) NOT NULL default '0',
  GEMEINDE int(8) NOT NULL default '0',
  STRKEY varchar(5) default NULL,
  HAUSNR varchar(8) default NULL,
  PRIMARY KEY  (ID),
  KEY Gemeinde (GEMEINDE),
  KEY StrassenKey (STRKEY)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `ALK_Nutzung`
-- 

CREATE TABLE ALK_Nutzung (
  OBJGR smallint(6) default NULL,
  OBJID text,
  FOLIE smallint(6) default NULL,
  OBJART smallint(6) default NULL,
  TEXTART text,
  INFOART smallint(6) default NULL,
  INFOTEXT text,
  AKTUAL smallint(6) default NULL,
  QUELLE smallint(6) default NULL,
  ID int(11) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;


-- Tabellen für ALB Daten aus WLDGE2SQL
-- --------------------------------------------------------

-- 
-- Table structure for table `ALB_Fortfuehrung`
-- 

CREATE TABLE ALB_Fortfuehrung (
  lfdNr int(11) NOT NULL auto_increment,
  Grundausstattung date NOT NULL default '0000-00-00',
  FFZeitraum_von datetime NOT NULL default '0000-00-00 00:00:00',
  FFZeitraum_bis datetime NOT NULL default '0000-00-00 00:00:00',
  FF_Timestamp timestamp(14) NOT NULL,
  PRIMARY KEY  (lfdNr),
  KEY FFZeitraum_bis (FFZeitraum_bis)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `Flurstuecke`
-- 

CREATE TABLE Flurstuecke (
  FlurstKennz varchar(23) NOT NULL default '',
  GemkgSchl int(6) NOT NULL default '0',
  FlurNr text,
  Pruefzeichen text,
  Status text,
  Entsteh text,
  LetzFF text,
  Flaeche int(11) default NULL,
  AktuNr smallint(2) default NULL,
  Karte text,
  BauBlock text,
  KoorRW double default NULL,
  KoorHW double default NULL,
  Forstamt smallint(4) default NULL,
  Finanzamt smallint(6) default NULL,
  Erbbau text,
  PRIMARY KEY  (FlurstKennz),
  KEY GemkgSchl (GemkgSchl)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `Grundbuecher`
-- 

CREATE TABLE Grundbuecher (
  Bezirk int(6) NOT NULL default '0',
  Blatt varchar(6) NOT NULL default '',
  Pruefzeichen char(1) default NULL,
  AktualitaetsNr varchar(4) default NULL,
  Zusatz_Eigentuemer mediumtext,
  Bestandsflaeche int(11) default NULL,
  PRIMARY KEY  (Bezirk,Blatt)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Adressen`
-- 

CREATE TABLE f_Adressen (
  FlurstKennz varchar(23) NOT NULL default '',
  Gemeinde int(8) NOT NULL default '0',
  Strasse varchar(5) NOT NULL default '',
  HausNr varchar(8) NOT NULL default '',
  KEY FlurstKennz (FlurstKennz),
  KEY Gemeinde (Gemeinde),
  KEY Straße (Strasse),
  KEY HausNr (HausNr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Anlieger`
-- 

CREATE TABLE f_Anlieger (
  FlurstKennz varchar(23) NOT NULL default '',
  Kennung char(1) default NULL,
  AnlFlstKennz varchar(23) default NULL,
  AnlFlstPruefz char(1) default NULL,
  KEY FlurstKennz (FlurstKennz)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Baulasten`
-- 

CREATE TABLE f_Baulasten (
  FlurstKennz varchar(23) NOT NULL default '',
  BlattNr varchar(10) NOT NULL default '',
  PRIMARY KEY  (FlurstKennz,BlattNr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Hinweise`
-- 

CREATE TABLE f_Hinweise (
  FlurstKennz varchar(23) NOT NULL default '',
  HinwZFlst char(2) NOT NULL default '',
  PRIMARY KEY  (FlurstKennz,HinwZFlst)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Historie`
-- 

CREATE TABLE f_Historie (
  Vorgaenger varchar(23) NOT NULL default '',
  Nachfolger varchar(23) NOT NULL default '',
  PRIMARY KEY  (Vorgaenger,Nachfolger)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Klassifizierungen`
-- 

CREATE TABLE f_Klassifizierungen (
  FlurstKennz varchar(23) NOT NULL default '',
  TabKenn char(2) NOT NULL default '',
  Klass char(3) NOT NULL default '',
  Flaeche int(11) NOT NULL default '0',
  Angaben varchar(23) default NULL,
  KEY FlurstKennz (FlurstKennz),
  KEY TabKenn (TabKenn),
  KEY Klass (Klass)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Lage`
-- 

CREATE TABLE f_Lage (
  FlurstKennz varchar(23) NOT NULL default '',
  lfdNr char(2) NOT NULL default '',
  Lagebezeichnung varchar(30) default NULL,
  PRIMARY KEY  (FlurstKennz,lfdNr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Nutzungen`
-- 

CREATE TABLE f_Nutzungen (
  FlurstKennz varchar(23) NOT NULL default '',
  Nutzungsart char(3) NOT NULL default '',
  Flaeche int(11) default NULL,
  KEY FlurstKennz (FlurstKennz),
  KEY Nutzungsart (Nutzungsart)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Texte`
-- 

CREATE TABLE f_Texte (
  FlurstKennz varchar(23) NOT NULL default '',
  lfdNr char(2) NOT NULL default '',
  Text varchar(52) default NULL,
  PRIMARY KEY  (FlurstKennz,lfdNr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `f_Verfahren`
-- 

CREATE TABLE f_Verfahren (
  FlurstKennz varchar(23) NOT NULL default '',
  AusfStelle varchar(5) default NULL,
  VerfNr varchar(6) default NULL,
  VerfBem char(2) default NULL,
  KEY FlurstKennz (FlurstKennz)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `g_Buchungen`
-- 

CREATE TABLE g_Buchungen (
  FlurstKennz varchar(23) NOT NULL default '',
  Bezirk int(6) NOT NULL default '0',
  Blatt varchar(6) NOT NULL default '',
  BVNR varchar(4) NOT NULL default '',
  ErbbaurechtsHinw char(1) NOT NULL default '',
  PRIMARY KEY  (FlurstKennz,Bezirk,Blatt,BVNR)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `g_Eigentuemer`
-- 

CREATE TABLE g_Eigentuemer (
  Bezirk int(6) NOT NULL default '0',
  Blatt varchar(6) NOT NULL default '',
  NamensNr varchar(16) NOT NULL default '',
  Eigentuemerart char(2) NOT NULL default '',
  Anteilsverhaeltnis varchar(25) NOT NULL default '',
  lfd_Nr_Name int(11) NOT NULL default '0',
  PRIMARY KEY  (Bezirk,Blatt,NamensNr),
  KEY Eigentuemerart (Eigentuemerart),
  KEY lfd_Nr_Name (lfd_Nr_Name)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `g_Grundstuecke`
-- 

CREATE TABLE g_Grundstuecke (
  Bezirk int(6) NOT NULL default '0',
  Blatt varchar(6) NOT NULL default '',
  BVNR varchar(4) NOT NULL default '',
  Buchungsart char(1) NOT NULL default '',
  Anteil varchar(24) default NULL,
  AuftPlanNr varchar(12) default NULL,
  Sondereigentum mediumtext,
  PRIMARY KEY  (Bezirk,Blatt,BVNR)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `g_Namen`
-- 

CREATE TABLE g_Namen (
  lfd_Nr_Name int(11) NOT NULL auto_increment,
  Name1 varchar(52) NOT NULL default '',
  Name2 varchar(52) NOT NULL default '',
  Name3 varchar(52) NOT NULL default '',
  Name4 varchar(52) NOT NULL default '',
  PRIMARY KEY  (lfd_Nr_Name),
  KEY Name1 (Name1),
  KEY Name2 (Name2),
  KEY Name3 (Name3),
  KEY Name4 (Name4)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Amtsgerichte`
-- 

CREATE TABLE v_Amtsgerichte (
  Amtsgericht varchar(4) NOT NULL default '',
  Name varchar(80) default NULL,
  PRIMARY KEY  (Amtsgericht)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_AusfuehrendeStellen`
-- 

CREATE TABLE v_AusfuehrendeStellen (
  AusfStelle varchar(5) NOT NULL default '',
  Name text,
  PRIMARY KEY  (AusfStelle)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_BemerkgZumVerfahren`
-- 

CREATE TABLE v_BemerkgZumVerfahren (
  VerfBem char(2) NOT NULL default '',
  Bezeichnung text,
  PRIMARY KEY  (VerfBem)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Buchungsarten`
-- 

CREATE TABLE v_Buchungsarten (
  Buchungsart char(1) NOT NULL default '0',
  Bezeichnung varchar(60) default NULL,
  PRIMARY KEY  (Buchungsart)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_EigentuemerArten`
-- 

CREATE TABLE v_EigentuemerArten (
  Eigentuemerart char(2) NOT NULL default '',
  Bezeichnung varchar(80) default NULL,
  PRIMARY KEY  (Eigentuemerart)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Finanzaemter`
-- 

CREATE TABLE v_Finanzaemter (
  Finanzamt smallint(4) NOT NULL default '0',
  Name text,
  PRIMARY KEY  (Finanzamt)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Forstaemter`
-- 

CREATE TABLE v_Forstaemter (
  Forstamt smallint(4) NOT NULL default '0',
  Name varchar(78) default NULL,
  PRIMARY KEY  (Forstamt)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Gemarkungen`
-- 

CREATE TABLE v_Gemarkungen (
  GemkgSchl int(6) NOT NULL default '0',
  Gemeinde int(8) default '0',
  Amtsgericht varchar(4) default NULL,
  GemkgName varchar(255) default NULL,
  PRIMARY KEY  (GemkgSchl),
  KEY Gemeinde (Gemeinde)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Gemeinden`
-- 

CREATE TABLE v_Gemeinden (
  Gemeinde int(8) NOT NULL default '0',
  GemeindeName varchar(26) NOT NULL default '',
  PRIMARY KEY  (Gemeinde),
  KEY GemeindeName (GemeindeName)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Grundbuchbezirke`
-- 

CREATE TABLE v_Grundbuchbezirke (
  GrundbuchbezSchl int(6) NOT NULL default '0',
  Amtsgericht varchar(4) NOT NULL default '0',
  Bezeichnung varchar(50) NOT NULL default '',
  PRIMARY KEY  (GrundbuchbezSchl),
  KEY Amtsgericht (Amtsgericht)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Hinweise`
-- 

CREATE TABLE v_Hinweise (
  HinwZFlst char(2) NOT NULL default '',
  Bezeichnung varchar(80) NOT NULL default '',
  PRIMARY KEY  (HinwZFlst)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Katasteraemter`
-- 

CREATE TABLE v_Katasteraemter (
  Katasteramt varchar(4) NOT NULL default '',
  ArtAmt varchar(26) default NULL,
  Name text,
  PRIMARY KEY  (Katasteramt)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Klassifizierungen`
-- 

CREATE TABLE v_Klassifizierungen (
  TabKenn char(2) NOT NULL default '',
  Klass char(3) NOT NULL default '',
  Bezeichnung varchar(90) NOT NULL default '',
  Abkuerzung varchar(12) NOT NULL default '',
  Bez1 varchar(30) default NULL,
  Kurz1 varchar(4) default NULL,
  Bez2 varchar(30) default NULL,
  Kurz2 varchar(4) NOT NULL default '',
  Bez3 varchar(30) default NULL,
  Kurz3 varchar(4) default NULL,
  PRIMARY KEY  (TabKenn,Klass)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Kreise`
-- 

CREATE TABLE v_Kreise (
  Kreis int(5) NOT NULL default '0',
  KreisName varchar(26) default NULL,
  PRIMARY KEY  (Kreis)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Nutzungsarten`
-- 

CREATE TABLE v_Nutzungsarten (
  Nutzungsart char(3) NOT NULL default '',
  Bezeichnung varchar(90) default NULL,
  Abkuerzung varchar(12) default NULL,
  PRIMARY KEY  (Nutzungsart)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `v_Strassen`
-- 

CREATE TABLE v_Strassen (
  Gemeinde int(8) NOT NULL default '0',
  Strasse varchar(5) NOT NULL default '',
  StrassenName varchar(30) NOT NULL default '',
  PRIMARY KEY  (Gemeinde,Strasse),
  KEY StrassenName (StrassenName)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_Flurstuecke`
-- 

CREATE TABLE x_Flurstuecke (
  FlurstKennz varchar(23) NOT NULL default '',
  GemkgSchl int(6) NOT NULL default '0',
  FlurNr text,
  Pruefzeichen text,
  Status text,
  Entsteh text,
  LetzFF text,
  Flaeche int(11) default NULL,
  AktuNr smallint(2) default NULL,
  Karte text,
  BauBlock text,
  KoorRW double default NULL,
  KoorHW double default NULL,
  Forstamt smallint(6) default NULL,
  Finanzamt smallint(6) default NULL,
  Erbbau text,
  PRIMARY KEY  (FlurstKennz),
  KEY GemkgSchl (GemkgSchl)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_Grundbuecher`
-- 

CREATE TABLE x_Grundbuecher (
  Bezirk int(6) NOT NULL default '0',
  Blatt varchar(6) NOT NULL default '',
  Pruefzeichen char(1) default NULL,
  AktualitaetsNr varchar(4) default NULL,
  Zusatz_Eigentuemer mediumtext,
  Bestandsflaeche int(11) default NULL,
  PRIMARY KEY  (Bezirk,Blatt)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Adressen`
-- 

CREATE TABLE x_f_Adressen (
  FlurstKennz varchar(23) NOT NULL default '',
  Gemeinde int(8) NOT NULL default '0',
  Strasse varchar(5) NOT NULL default '',
  HausNr varchar(8) NOT NULL default '',
  KEY FlurstKennz (FlurstKennz),
  KEY Gemeinde (Gemeinde),
  KEY Straße (Strasse),
  KEY HausNr (HausNr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Anlieger`
-- 

CREATE TABLE x_f_Anlieger (
  FlurstKennz varchar(23) NOT NULL default '',
  Kennung char(1) default NULL,
  AnlFlstKennz varchar(23) default NULL,
  AnlFlstPruefz char(1) default NULL,
  KEY FlurstKennz (FlurstKennz)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Baulasten`
-- 

CREATE TABLE x_f_Baulasten (
  FlurstKennz varchar(23) NOT NULL default '',
  BlattNr varchar(10) NOT NULL default '',
  PRIMARY KEY  (FlurstKennz,BlattNr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Hinweise`
-- 

CREATE TABLE x_f_Hinweise (
  FlurstKennz varchar(23) NOT NULL default '',
  HinwZFlst char(2) NOT NULL default '',
  PRIMARY KEY  (FlurstKennz,HinwZFlst)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Historie`
-- 

CREATE TABLE x_f_Historie (
  Vorgaenger varchar(23) NOT NULL default '',
  Nachfolger varchar(23) NOT NULL default '',
  PRIMARY KEY  (Vorgaenger,Nachfolger)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Klassifizierungen`
-- 

CREATE TABLE x_f_Klassifizierungen (
  FlurstKennz varchar(23) NOT NULL default '',
  TabKenn char(2) NOT NULL default '',
  Klass char(3) NOT NULL default '',
  Flaeche int(11) NOT NULL default '0',
  Angaben varchar(23) default NULL,
  KEY FlurstKennz (FlurstKennz),
  KEY TabKenn (TabKenn),
  KEY Klass (Klass)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Lage`
-- 

CREATE TABLE x_f_Lage (
  FlurstKennz varchar(23) NOT NULL default '',
  lfdNr char(2) NOT NULL default '',
  Lagebezeichnung varchar(30) default NULL,
  PRIMARY KEY  (FlurstKennz,lfdNr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Nutzungen`
-- 

CREATE TABLE x_f_Nutzungen (
  FlurstKennz varchar(23) NOT NULL default '',
  Nutzungsart char(3) NOT NULL default '',
  Flaeche int(11) default NULL,
  KEY FlurstKennz (FlurstKennz),
  KEY Nutzungsart (Nutzungsart)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Texte`
-- 

CREATE TABLE x_f_Texte (
  FlurstKennz varchar(23) NOT NULL default '',
  lfdNr char(2) NOT NULL default '',
  Text varchar(52) default NULL,
  PRIMARY KEY  (FlurstKennz,lfdNr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_f_Verfahren`
-- 

CREATE TABLE x_f_Verfahren (
  FlurstKennz varchar(23) NOT NULL default '',
  AusfStelle varchar(5) default NULL,
  VerfNr varchar(6) default NULL,
  VerfBem char(2) default NULL,
  KEY FlurstKennz (FlurstKennz)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_g_Buchungen`
-- 

CREATE TABLE x_g_Buchungen (
  FlurstKennz varchar(23) NOT NULL default '',
  Bezirk int(6) NOT NULL default '0',
  Blatt varchar(6) NOT NULL default '',
  BVNR varchar(4) NOT NULL default '',
  ErbbaurechtsHinw char(1) NOT NULL default '',
  PRIMARY KEY  (FlurstKennz,Bezirk,Blatt,BVNR)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_g_Eigentuemer`
-- 

CREATE TABLE x_g_Eigentuemer (
  Bezirk int(6) NOT NULL default '0',
  Blatt varchar(6) NOT NULL default '',
  NamensNr varchar(16) NOT NULL default '',
  Eigentuemerart char(2) NOT NULL default '',
  Anteilsverhaeltnis varchar(25) NOT NULL default '',
  lfd_Nr_Name int(11) NOT NULL default '0',
  PRIMARY KEY  (Bezirk,Blatt,NamensNr),
  KEY Eigentuemerart (Eigentuemerart),
  KEY lfd_Nr_Name (lfd_Nr_Name)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_g_Grundstuecke`
-- 

CREATE TABLE x_g_Grundstuecke (
  Bezirk int(6) NOT NULL default '0',
  Blatt varchar(6) NOT NULL default '',
  BVNR varchar(4) NOT NULL default '',
  Buchungsart char(1) NOT NULL default '',
  Anteil varchar(24) default NULL,
  AuftPlanNr varchar(12) default NULL,
  Sondereigentum mediumtext,
  PRIMARY KEY  (Bezirk,Blatt,BVNR)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_g_Namen`
-- 

CREATE TABLE x_g_Namen (
  lfd_Nr_Name int(11) NOT NULL default '0',
  Name1 varchar(52) NOT NULL default '',
  Name2 varchar(52) NOT NULL default '',
  Name3 varchar(52) NOT NULL default '',
  Name4 varchar(52) NOT NULL default '',
  lfd_Nr_Name_alt int(11) NOT NULL default '0',
  PRIMARY KEY  (lfd_Nr_Name),
  KEY Name1 (Name1),
  KEY Name2 (Name2),
  KEY Name3 (Name3),
  KEY Name4 (Name4)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Amtsgerichte`
-- 

CREATE TABLE x_v_Amtsgerichte (
  Amtsgericht varchar(4) NOT NULL default '',
  Name varchar(80) default NULL,
  PRIMARY KEY  (Amtsgericht)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_AusfuehrendeStellen`
-- 

CREATE TABLE x_v_AusfuehrendeStellen (
  AusfStelle varchar(5) NOT NULL default '',
  Name text,
  PRIMARY KEY  (AusfStelle)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_BemerkgZumVerfahren`
-- 

CREATE TABLE x_v_BemerkgZumVerfahren (
  VerfBem char(2) NOT NULL default '',
  Bezeichnung text,
  PRIMARY KEY  (VerfBem)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Buchungsarten`
-- 

CREATE TABLE x_v_Buchungsarten (
  Buchungsart char(1) NOT NULL default '0',
  Bezeichnung varchar(60) default NULL,
  PRIMARY KEY  (Buchungsart)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_EigentuemerArten`
-- 

CREATE TABLE x_v_EigentuemerArten (
  Eigentuemerart char(2) NOT NULL default '',
  Bezeichnung varchar(80) default NULL,
  PRIMARY KEY  (Eigentuemerart)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Finanzaemter`
-- 

CREATE TABLE x_v_Finanzaemter (
  Finanzamt smallint(4) NOT NULL default '0',
  Name text,
  PRIMARY KEY  (Finanzamt)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Forstaemter`
-- 

CREATE TABLE x_v_Forstaemter (
  Forstamt smallint(4) NOT NULL default '0',
  Name varchar(78) default NULL,
  PRIMARY KEY  (Forstamt)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Gemarkungen`
-- 

CREATE TABLE x_v_Gemarkungen (
  GemkgSchl int(6) NOT NULL default '0',
  Gemeinde int(8) default '0',
  Amtsgericht varchar(4) default NULL,
  GemkgName varchar(255) default NULL,
  PRIMARY KEY  (GemkgSchl),
  KEY Gemeinde (Gemeinde)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Gemeinden`
-- 

CREATE TABLE x_v_Gemeinden (
  Gemeinde int(8) NOT NULL default '0',
  GemeindeName varchar(26) NOT NULL default '',
  PRIMARY KEY  (Gemeinde),
  KEY GemeindeName (GemeindeName)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Grundbuchbezirke`
-- 

CREATE TABLE x_v_Grundbuchbezirke (
  GrundbuchbezSchl int(6) NOT NULL default '0',
  Amtsgericht varchar(4) NOT NULL default '0',
  Bezeichnung varchar(50) NOT NULL default '',
  PRIMARY KEY  (GrundbuchbezSchl),
  KEY Amtsgericht (Amtsgericht)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Hinweise`
-- 

CREATE TABLE x_v_Hinweise (
  HinwZFlst char(2) NOT NULL default '',
  Bezeichnung varchar(80) NOT NULL default '',
  PRIMARY KEY  (HinwZFlst)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Katasteraemter`
-- 

CREATE TABLE x_v_Katasteraemter (
  Katasteramt varchar(4) NOT NULL default '',
  ArtAmt varchar(26) default NULL,
  Name text,
  PRIMARY KEY  (Katasteramt)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Klassifizierungen`
-- 

CREATE TABLE x_v_Klassifizierungen (
  TabKenn char(2) NOT NULL default '',
  Klass char(3) NOT NULL default '',
  Bezeichnung varchar(90) NOT NULL default '',
  Abkuerzung varchar(12) NOT NULL default '',
  Bez1 varchar(30) default NULL,
  Kurz1 varchar(4) default NULL,
  Bez2 varchar(30) default NULL,
  Kurz2 varchar(4) NOT NULL default '',
  Bez3 varchar(30) default NULL,
  Kurz3 varchar(4) default NULL,
  PRIMARY KEY  (TabKenn,Klass)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Kreise`
-- 

CREATE TABLE x_v_Kreise (
  Kreis int(5) NOT NULL default '0',
  KreisName varchar(26) default NULL,
  PRIMARY KEY  (Kreis)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Nutzungsarten`
-- 

CREATE TABLE x_v_Nutzungsarten (
  Nutzungsart char(3) NOT NULL default '',
  Bezeichnung varchar(90) default NULL,
  Abkuerzung varchar(12) default NULL,
  PRIMARY KEY  (Nutzungsart)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `x_v_Strassen`
-- 

CREATE TABLE x_v_Strassen (
  Gemeinde int(8) NOT NULL default '0',
  Strasse varchar(5) NOT NULL default '',
  StrassenName varchar(30) NOT NULL default '',
  PRIMARY KEY  (Gemeinde,Strasse),
  KEY StrassenName (StrassenName)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `z_Fluren`
-- 

CREATE TABLE z_Fluren (
  GemkgSchl varchar(6) NOT NULL default '',
  FlurNr varchar(6) NOT NULL default '',
  PRIMARY KEY  (GemkgSchl,FlurNr)
) TYPE=MyISAM;


-- --------------------------------------------------------

-- 
-- Table structure for table `tmp_Adressen`
-- 

CREATE TABLE tmp_Adressen (
  Quelle char(3) NOT NULL default '',
  Gemeinde int(11) NOT NULL default '0',
  GemeindeName varchar(255) default NULL,
  Strasse varchar(5) NOT NULL default '',
  StrassenName varchar(255) default NULL,
  HausNr varchar(8) NOT NULL default '',
  PRIMARY KEY  (Gemeinde,Strasse,HausNr)
) TYPE=MyISAM;