BEGIN;

ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM('Text','Textfeld','Auswahlfeld','Auswahlfeld_Bild','Autovervollständigungsfeld','Autovervollständigungsfeld_zweispaltig','Radiobutton','Checkbox','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','dynamicLink','User','UserID','Stelle','StelleID','ClientID', 'Fläche','Länge','Zahl','mailto','Winkel','Style','Editiersperre','ExifLatLng','ExifRichtung','ExifErstellungszeit','Farbauswahl') NOT NULL;

COMMIT;