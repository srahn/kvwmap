BEGIN;

  ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM('Text','Textfeld','Auswahlfeld','Autovervollständigungsfeld','Autovervollständigungsfeld zweispaltig','Radiobutton','Checkbox','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','dynamicLink','User','UserID','Stelle','StelleID','Fläche','Länge','Zahl','mailto','Winkel','Style','Editiersperre') NOT NULL DEFAULT 'Text';

COMMIT;
