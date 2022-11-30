BEGIN;
DELETE FROM xplan_gml.fp_detailzweckbestwaldflaeche;
INSERT INTO xplan_gml.fp_detailzweckbestwaldflaeche (codespace,value,id)
VALUES
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestWaldFlaeche','Sukzessionswald','1000_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestWaldFlaeche','Aufforstung','1800_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestWaldFlaeche','Begräbniswald, Bestattungswald','9999_1');
COMMIT;
