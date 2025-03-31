BEGIN;
DELETE FROM xplankonverter.validierungen
WHERE name = 'Belegtes Flächenschlussattribut bei Flächengeometrie'
AND konformitaet_nummer = '2.2.1.1'
AND konformitaet_version_von = '4.1';

INSERT INTO xplankonverter.validierungen(name,beschreibung,functionsname,msg_success,msg_warning,msg_error,msg_correcture,konformitaet_nummer,konformitaet_version_von,functionsargumente)
VALUES
(
	'Belegtes Flächenschlussattribut bei Flächengeometrie',
	'Bei allen Objekten mit flächenhaftem Raumbezug, die nach dem XPlanGML Schema ein Attribut flaechenschluss besitzen,muss dieses auch belegt sein',
	'flaechenschluss_exists_on_poly',
	'Konformität Nummer 2.2.1.1: Belegtes Flächenschlussattribut bei Flächengeometrie bestanden',
	NULL,
	'Konformität Nummer 2.2.1.1: Belegtes Flächenschlussattribut bei Flächengeometrie nicht bestanden',
	'Das Flächenschluss-Attribut muss bei flächenhaften Geometrien immer verwiesen werden',
	'2.2.1.1',
	'4.1',
	ARRAY['(ST_GeometryType(position) IN (''ST_Polygon'',''ST_MultiPolygon'') AND flaechenschluss = TRUE)']
);
COMMIT;