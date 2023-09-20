BEGIN;

	CREATE TABLE `layer_charts` ( 
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`layer_id` INT(11) NOT NULL,
		`title` VARCHAR(255) NULL,
		`type` enum('bar','pie','doughnut') NOT NULL DEFAULT 'bar',
		`aggregate_function` enum('sum', 'average', 'min', 'max') NULL,
		`value_attribute_label` VARCHAR(100),
		`value_attribute_name` VARCHAR(65),
		`label_attribute_name` VARCHAR(65),
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

	ALTER TABLE `layer_charts`	ADD CONSTRAINT `fk_layer_charts_value_attribute_name` FOREIGN KEY (`layer_id`, `value_attribute_name`) REFERENCES `layer_attributes` (`layer_id`, `name`) ON DELETE CASCADE ON UPDATE CASCADE;
	ALTER TABLE `layer_charts`	ADD CONSTRAINT `fk_layer_charts_label_attribute_name` FOREIGN KEY (`layer_id`, `label_attribute_name`) REFERENCES `layer_attributes` (`layer_id`, `name`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
