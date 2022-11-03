BEGIN;
DELETE FROM xplan_gml.fp_detailzweckbestwasserwirtschaft;
INSERT INTO xplan_gml.fp_detailzweckbestwasserwirtschaft (codespace,value,id)
VALUES
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestWasserwirtschaft','Sperrwerk / Siel','9999_1');
COMMIT;
