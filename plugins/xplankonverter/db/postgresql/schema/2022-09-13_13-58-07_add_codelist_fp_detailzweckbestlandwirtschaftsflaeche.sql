BEGIN;

DELETE FROM xplan_gml.fp_detailzweckbestlandwirtschaftsflaeche;
INSERT INTO xplan_gml.fp_detailzweckbestlandwirtschaftsflaeche (codespace,value,id)
VALUES
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestLandwirtschaftsFlaeche','Landwirtschaftliche Fläche ökologisch wertvoll','1000_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestLandwirtschaftsFlaeche','Gewächshausanlagen','1000_2');
COMMIT;
