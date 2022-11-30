BEGIN;
DELETE FROM xplan_gml.fp_detailzweckbestgruen;
INSERT INTO xplan_gml.fp_detailzweckbestgruen (codespace,value,id)
VALUES
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Grünanlage','1000_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Grünanlage z.T. mit Freizeiteinrichtungen','1000_2'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Grabeland','1200_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Reitsport Ferienanlage','1400_0_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Hundevereinsplatz','1400_1_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Hundeauslaufplatz','1400_1_2'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Hundeschule','1400_1_3'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Freispielfläche','1600_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Feuerstelle, Grillplatz','2200_2'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Fahrradtourismus','2200_3'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Wassertourismus','2200_4'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Steilufer','2400_10'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Strand','2400_11'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Grünverbindung Entwicklung: Grünverbindungen - Planung','2400_12'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Grünverbindung Sicherung: Grünverbindungen','2400_13'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Lärmschutzanlage','2400_14'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Ortsrandeingrünung','2400_15'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Eigentümergärten','2400_16'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Wohnmobilstellplatz','2400_6_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Aufforstung','2400_7'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','naturnahe Grünfläche, Naturschutz','2400_8'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Tierfriedhof','2600_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Segelfluggelände','9999_1'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Bootslager/ Stellplätze für Boote','9999_2'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Modellflugplatz','9999_3'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Angelsport','9999_4'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Gastronomie','9999_5'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Grünverbindung','9999_6'),
('https://registry.gdi-de.org/codelist/de.xleitstelle.xplanung/FP_DetailZweckbestGruen','Pferdebezogene Anlagen und Nutzungen','9999_7');
COMMIT;