BEGIN;

	CREATE TABLE `layer_datasources` (
  	`layer_id` int(11) NOT NULL,
  	`datasource_id` int(11) NOT NULL,
  	`sortorder` int(11)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	ALTER TABLE `layer_datasources` ADD PRIMARY KEY (`layer_id`, `datasource_id`);

	ALTER TABLE `layer_datasources` ADD CONSTRAINT `layer_datasource_fk_layer_id` FOREIGN KEY (`layer_id`) REFERENCES `layer` (`Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

	ALTER TABLE `layer_datasources` ADD CONSTRAINT `layer_datasource_fk_datasource_id` FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

	INSERT INTO layer_datasources (layer_id, datasource_id)
	SELECT
		Layer_ID,
		datasource
	FROM
		layer l JOIN
		datasources d ON l.datasource = d.id

	#ALTER TABLE 'layer' DROP column datasource;

COMMIT;