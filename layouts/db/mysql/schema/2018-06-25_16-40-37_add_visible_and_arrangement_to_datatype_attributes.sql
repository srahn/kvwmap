BEGIN;

	ALTER TABLE `datatype_attributes` ADD `visible` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Zeigt oder versteckt Attribut im Layereditor (default: Zeigen).';
	ALTER TABLE `datatype_attributes` ADD `arrangement` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Zeigt Attribut unter oder neben dem vorgehenden Attribut (default: darunter).';
	ALTER TABLE `datatype_attributes` ADD `labeling` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Zeigt Beschriftung gar nicht, über oder links neben dem Attributwert (default: links daneben).';

COMMIT;
