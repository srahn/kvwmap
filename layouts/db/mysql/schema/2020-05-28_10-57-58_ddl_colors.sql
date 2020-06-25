BEGIN;

CREATE TABLE `ddl_colors` (
  `id` int(11) NOT NULL,
  `red` smallint(3) NOT NULL DEFAULT '0',
  `green` smallint(3) NOT NULL DEFAULT '0',
  `blue` smallint(3) NOT NULL DEFAULT '0'
) ENGINE=INNODB;

ALTER TABLE `ddl_colors` ADD PRIMARY KEY (`id`);

ALTER TABLE `ddl_colors` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `ddl_colors` (red, green, blue) VALUES 
(200, 200, 200),
(215, 215, 215),
(230, 230, 230),
(181, 217, 255),
(218, 255, 149),
(255, 203, 172);

COMMIT;
