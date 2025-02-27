BEGIN;

CREATE TABLE `u_consumeNachweise` (
  `antrag_nr` varchar(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
	`time_id` datetime NOT NULL,
  `suchffr` char(1) NOT NULL DEFAULT '0',
  `suchkvz` char(1) NOT NULL DEFAULT '0',
  `suchgn` char(1) NOT NULL DEFAULT '0',
  `suchan` char(1) NOT NULL DEFAULT '0',
  `abfrageart` varchar(10) NOT NULL,
  `suchgemarkung` varchar(10) NULL,
  `suchflur` varchar(3) NULL,
  `suchstammnr` varchar(15) NULL,
  `suchrissnr` varchar(20) NULL,
  `suchfortf` int(4) NULL,
  `suchpolygon` text NULL,
  `suchantrnr` varchar(23) NULL,
  `sdatum` varchar(10) NULL,
  `sdatum2` varchar(10) NULL,
  `sVermStelle` int(11) NULL,
	`flur_thematisch` boolean NULL,
	`such_andere_art` varchar(255),
  PRIMARY KEY (`antrag_nr`,`stelle_id`,`time_id`)
) ENGINE=MyISAM;

COMMIT;
