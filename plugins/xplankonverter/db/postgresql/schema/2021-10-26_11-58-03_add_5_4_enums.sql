BEGIN;
  UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr SET beschreibung = 'Flächen für Ladeinfrastruktur elektrisch betriebener Fahrzeuge.' WHERE wert = '3500';
/*

Inserts enum value 2700 after enum value 2600
Unlike ALTER TYPE ADD VALUE AFTER, this also works within a begin-commit transaction block (i.e. a kvwmap migration)
*/
INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.xp_zweckbestimmunggruen'::regtype::oid, '2700', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_zweckbestimmunggruen'::regtype 
			AND enumlabel = '2600'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_zweckbestimmunggruen'::regtype 
			AND enumlabel = '2600'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.xp_zweckbestimmunggruen'::regtype AND enumlabel = '2700' )
;
INSERT INTO xplan_gml.enum_xp_zweckbestimmunggruen (wert, abkuerzung, beschreibung)
  VALUES ('2700', 'Naturerfahrungsraum', 'Naturerfahrungsräume sollen insbesondere Kindern und Jugendlichen die Möglichkeit geben, in ihrem direkten Umfeld Natur vorzufinden, um eigenständig Erfahrung mit Pflanzen und Tieren sammeln zu können.');


INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.bp_planart'::regtype::oid, '10002', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.bp_planart'::regtype 
			AND enumlabel = '10001'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.bp_planart'::regtype 
			AND enumlabel = '10001'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.bp_planart'::regtype AND enumlabel = '10002' )
;

INSERT INTO xplan_gml.enum_bp_planart (wert, abkuerzung, beschreibung)
  VALUES ('10002', 'Bebauungsplan zur Wohnraumversorgung', 'Bebauungsplan zur Wohnraumversorgung für im Zusammenhang bebaute Ortsteile (§ 34) nach §9 Absatz 2d BauGB');

INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.so_gebietsart'::regtype::oid, '2300', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype 
			AND enumlabel = '2200'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype 
			AND enumlabel = '2200'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype AND enumlabel = '2300' )
;

INSERT INTO xplan_gml.enum_so_gebietsart (wert, abkuerzung, beschreibung)
  VALUES ('2300', 'Städtebauliche Entwicklungskonzept Innenentwicklung', 'Städtebauliches Entwicklungskonzept zur Stärkung der Innenentwicklung.');


INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.so_gebietsart'::regtype::oid, '2400', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype 
			AND enumlabel = '2300'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype 
			AND enumlabel = '2300'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype AND enumlabel = '2400' )
;

INSERT INTO xplan_gml.enum_so_gebietsart (wert, abkuerzung, beschreibung)
VALUES ('2400', 'Gebiet mit angespanntem Wohnungsmarkt', 'Gebiet mit einem angespannten Wohnungsmarkt.');


INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.so_gebietsart'::regtype::oid, '2500', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype 
			AND enumlabel = '2400'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype 
			AND enumlabel = '2400'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.so_gebietsart'::regtype AND enumlabel = '2500' )
;

INSERT INTO xplan_gml.enum_so_gebietsart (wert, abkuerzung, beschreibung)
  VALUES ('2500', 'Genehmigung Wohnungseigentum', 'Gebiet mit angespanntem Wohnungsmarkt, in dem die Begründung oder Teilung von Wohnungseigentum oder Teileigentum der Genehmigung bedarf.');

INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.xp_besondereartderbaulnutzung'::regtype::oid, '1450', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_besondereartderbaulnutzung'::regtype 
			AND enumlabel = '1400'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_besondereartderbaulnutzung'::regtype 
			AND enumlabel = '1400'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.xp_besondereartderbaulnutzung'::regtype AND enumlabel = '1450' )
;
INSERT INTO xplan_gml.enum_xp_besondereartderbaulnutzung (wert, abkuerzung, beschreibung)
VALUES ('1450', 'Dörfliches Wohngebiet', 'Dörfliches Wohngebiet nach §5a BauNVO.');


INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.xp_externereferenztyp'::regtype::oid, '3100', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype 
			AND enumlabel = '3000'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype 
			AND enumlabel = '3000'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype AND enumlabel = '3100' )
;
INSERT INTO xplan_gml.enum_xp_externereferenztyp (wert, beschreibung)
VALUES ('3100', 'Städtebauliches Entwicklungskonzept zur Stärkung der Innenentwicklung.');
COMMIT;