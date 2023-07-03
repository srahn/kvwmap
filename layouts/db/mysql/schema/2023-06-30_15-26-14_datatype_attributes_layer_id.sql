BEGIN;

ALTER TABLE `datatype_attributes` DROP CONSTRAINT IF EXISTS `datatype_attributes_ibfk_1`;

ALTER TABLE `datatype_attributes` ADD `layer_id` INT(11) NULL FIRST;

ALTER TABLE `datatype_attributes`
  DROP PRIMARY KEY;

INSERT INTO `datatype_attributes`
SELECT 
	la.layer_id, 
	dta.`datatype_id`, 
	dta.`name`, 
	dta.`real_name`, 
	dta.`tablename`, 
	dta.`table_alias_name`, 
	dta.`type`, 
	dta.`geometrytype`, 
	dta.`constraints`, 
	dta.`nullable`, 
	dta.`length`, 
	dta.`decimal_length`, 
	dta.`default`, 
	dta.`form_element_type`, 
	dta.`options`, 
	dta.`alias`, 
	dta.`alias_low-german`, 
	dta.`alias_english`, 
	dta.`alias_polish`, 
	dta.`alias_vietnamese`, 
	dta.`tooltip`, 
	dta.`group`, 
	dta.`raster_visibility`, 
	dta.`mandatory`, 
	dta.`quicksearch`, 
	dta.`order`, 
	dta.`privileg`, 
	dta.`query_tooltip`, 
	dta.`visible`, 
	dta.`vcheck_attribute`, 
	dta.`vcheck_operator`,
	dta.`vcheck_value`, 
	dta.`arrangement`, 
	dta.`labeling`
FROM
	`layer_attributes` la JOIN
	`datatypes` dt ON substr(la.type, 2) REGEXP '^[0-9]+$' AND substr(la.type, 2) = dt.id JOIN 
	`datatype_attributes` dta ON dta.datatype_id = dt.id;

DELETE FROM 
	`datatype_attributes`
WHERE
	layer_id IS NULL;

ALTER TABLE `datatype_attributes`
   ADD PRIMARY KEY(
     `layer_id`,
     `datatype_id`,
     `name`
   );
	 
COMMIT;
