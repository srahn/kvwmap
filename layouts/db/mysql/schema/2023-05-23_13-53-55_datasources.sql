BEGIN;

CREATE TABLE `datasources` ( 
	`id` INT(11) NOT NULL AUTO_INCREMENT, 
	`name` VARCHAR(50) NULL, 
	`beschreibung` TEXT NOT NULL, 
	PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
	
INSERT INTO datasources (beschreibung)
SELECT distinct 
	datasource 
FROM 
	`layer` 
WHERE 
	datasource IS NOT NULL AND 
	datasource != '';
	
UPDATE 
	`layer` 
INNER JOIN 
	datasources ON datasource = datasources.beschreibung
SET 
	datasource = datasources.id;

UPDATE 
	`layer` 
SET 
	datasource = NULL 
WHERE 
	datasource = '';

ALTER TABLE `layer` CHANGE `datasource` `datasource` INT(11) NULL DEFAULT NULL;


COMMIT;
