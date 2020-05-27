BEGIN;

CREATE TABLE `druckfreirechtecke` (
  `id` int(11) NOT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `endposx` int(11) NOT NULL,
  `endposy` int(11) NOT NULL,
  `breite` float NOT NULL,
  `color` int(11) DEFAULT NULL,
  `offset_attribute_start` varchar(255) DEFAULT NULL,
  `offset_attribute_end` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=INNODB;

CREATE TABLE `ddl2freirechtecke` (
	`ddl_id` int(11) NOT NULL,
	`rect_id` int(11) NOT NULL
) ENGINE=INNODB;

COMMIT;
