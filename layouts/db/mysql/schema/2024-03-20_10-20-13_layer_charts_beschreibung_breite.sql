BEGIN;

ALTER TABLE `layer_charts` ADD `beschreibung` text NOT NULL AFTER `label_attribute_name`, ADD `breite` varchar(255) NOT NULL DEFAULT '100%' AFTER `beschreibung`;

COMMIT;
