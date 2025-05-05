BEGIN;

CREATE TABLE `rolle_export_settings` (
  `stelle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `format` varchar(11) NOT NULL,
  `epsg` int(6) DEFAULT NULL,
  `attributes` text NOT NULL,
  `metadata` tinyint(1) DEFAULT NULL,
  `groupnames` tinyint(1) DEFAULT NULL,
  `documents` tinyint(1) DEFAULT NULL,
  `geom` longtext,
  `within` tinyint(1) DEFAULT NULL,
  `singlegeom` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `rolle_export_settings`
  ADD PRIMARY KEY (`stelle_id`,`user_id`,`layer_id`,`name`);
COMMIT;

COMMIT;
