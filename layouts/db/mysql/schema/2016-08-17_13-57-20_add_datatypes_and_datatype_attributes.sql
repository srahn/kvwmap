BEGIN;

CREATE TABLE datatypes (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`name` VARCHAR(58) NULL,
	`schema` VARCHAR(58) NOT NULL default 'public'
);

CREATE TABLE datatype_attributes (
	datatype_id int(11) NOT NULL,
	`name` varchar(255) NOT NULL,
	real_name varchar(255) default NULL,
	tablename varchar(100) default NULL,
	table_alias_name varchar(100) default NULL,
	`type` varchar(30) default NULL,
	geometrytype varchar(20) default NULL,
	constraints varchar(255) default NULL,
	nullable tinyint(1) default NULL,
	length int(11) default NULL,
	`decimal_length` INT( 11 ) NULL,
	`default` VARCHAR( 255 ) NULL,
	form_element_type enum('Text','Textfeld','Auswahlfeld','Checkbox', 'Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','User','Stelle','Fläche','dynamicLink','Zahl','UserID','Länge','mailto') NOT NULL default 'Text',
	options text,
	alias varchar(255) default NULL,
	`alias_low-german` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
	`alias_english` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
	`alias_polish` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
	`alias_vietnamese` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
	tooltip varchar(255) default NULL,
	`group` VARCHAR( 255 ) DEFAULT NULL,
	`raster_visibility` BOOLEAN NULL,
	`mandatory` BOOL NULL,
	`quicksearch` BOOLEAN NULL DEFAULT NULL,
	`order` int(11) default NULL,
	`privileg` BOOLEAN NULL DEFAULT '0',
	`query_tooltip` BOOLEAN NULL DEFAULT '0',
	PRIMARY KEY  (datatype_id,`name`)
);

COMMIT;
