BEGIN;

CREATE TABLE `druckfreilinien` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `endposx` int(11) NOT NULL,
  `endposy` int(11) NOT NULL,
   breite int(3) not null,
  `offset_attribute` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM;

CREATE TABLE `ddl2freilinien` (
  `ddl_id` int(11) NOT NULL,
  `line_id` int(11) NOT NULL
) ENGINE=MyISAM;


ALTER TABLE `ddl2freilinien` ADD PRIMARY KEY (`ddl_id`,`line_id`);
	
COMMIT;

