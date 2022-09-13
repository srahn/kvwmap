BEGIN;

DELETE FROM xplan_gml.fp_detailzweckbeststrassenverkehr;
INSERT INTO xplan_gml.fp_detailzweckbeststrassenverkehr (codespace,value,id)
VALUES
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestStrassenverkehr','Grenze der Ortsdurchfahrt','1300_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestStrassenverkehr','Faehre','1400_14'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestStrassenverkehr','Autohof','14008_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestStrassenverkehr','Wohnmobilstellplatz','1600_0_1');

COMMIT;