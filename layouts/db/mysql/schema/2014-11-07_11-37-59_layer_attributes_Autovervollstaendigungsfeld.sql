BEGIN;

ALTER TABLE  `layer_attributes` CHANGE  `form_element_type`  `form_element_type` ENUM(  'Text',  'Textfeld',  'Auswahlfeld',  'Autovervollständigungsfeld',  'Checkbox',  'Geometrie',  'SubFormPK',  'SubFormFK',  'SubFormEmbeddedPK',  'Time',  'Dokument',  'Link',  'User',  'Stelle',  'Fläche',  'dynamicLink',  'Zahl',  'UserID',  'Länge',  'mailto' ) NOT NULL DEFAULT  'Text';

COMMIT;
