BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES
  ('PDF_FONT_PATH', 'WWWROOT.APPLVERSION', 'fonts/PDFClass', 'Pfad zum Verzeichnis mit den Fonts, die für den PDF-Datendruck und -Kartendruck verwendet werden sollen.', 'string', 'Pfadeinstellungen', '', 0, 3);

COMMIT;
