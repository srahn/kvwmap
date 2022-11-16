BEGIN;
DELETE FROM xplan_gml.fp_detailzweckbestgewaesser;
INSERT INTO xplan_gml.fp_detailzweckbestgewaesser (codespace,value,id)
VALUES

('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Bootsliegeplätze','1000_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Freizeitnutzung auf Wasserflächen','1100_2'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Handelshafen / Frachthafen','1000_3'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Industriehafen','1000_5'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Schiffsanleger, Anleger, Anlegestelle, Landungssteg','1000_2'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Wattflächen','1100_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Yachthafen, Marina','1000_4');

COMMIT;