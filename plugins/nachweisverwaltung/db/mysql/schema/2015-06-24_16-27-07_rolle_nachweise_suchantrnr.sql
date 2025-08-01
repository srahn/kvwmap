BEGIN;

CREATE TABLE rolle_nachweise (
  user_id int(11) NOT NULL default '0',
  stelle_id int(11) NOT NULL default '0',
  suchffr char(1) NOT NULL default '0',
  suchkvz char(1) NOT NULL default '0',
  suchgn char(1) NOT NULL default '0',
  suchan CHAR(1) NOT NULL DEFAULT '0',
  abfrageart varchar(10) NOT NULL default '',
  suchgemarkung varchar(10) NOT NULL default '',
  suchflur varchar(3) NOT NULL,
  suchstammnr varchar(15) NOT NULL default '',
  suchrissnr varchar(20) NOT NULL,
  suchfortf int(4) NULL,
  suchpolygon text,
  suchantrnr varchar(11) NOT NULL default '',
	sdatum VARCHAR( 10 ) NULL,
	sdatum2 VARCHAR( 10 ) NULL,
	sVermStelle INT( 11 ) NULL,
  showffr char(1) NOT NULL default '0',
  showkvz char(1) NOT NULL default '0',
  showgn char(1) NOT NULL default '0',
  showan CHAR(1) NOT NULL DEFAULT '0',
  markffr char(1) NOT NULL default '0',
  markkvz char(1) NOT NULL default '0',
  markgn char(1) NOT NULL default '0',
  PRIMARY KEY  (user_id,stelle_id)
) ENGINE=MyISAM;

ALTER TABLE `rolle_nachweise` CHANGE `suchantrnr` `suchantrnr` VARCHAR( 23 ) NOT NULL DEFAULT '';

COMMIT;
