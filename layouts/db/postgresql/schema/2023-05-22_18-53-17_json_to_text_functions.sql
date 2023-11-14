BEGIN;
	CREATE OR REPLACE FUNCTION public.gdi_enum_json_to_text(
		value json,
		schema character varying,
		type character varying,
		is_array boolean)
			RETURNS text
			LANGUAGE 'plpgsql'
			COST 100
			
	AS $BODY$
			DECLARE
				sql text;
				result text;
			BEGIN
				IF is_array THEN
					sql = FORMAT('
						SELECT
							STRING_AGG(
								concat_ws('' '', e.abkuerzung, ''('' || e.wert || '')''),
								'', '')
						FROM
							json_array_elements(
								%1$L
							) ts(wert) JOIN
							%2$s.enum_%3$s e ON (ts #>> ''{}'')::integer = e.wert;
					', value, schema, type);
				ELSE
					sql = FORMAT('
						SELECT
							concat_ws('' '', e.abkuerzung, ''('' || e.wert || '')'')
						FROM
							%2$s.enum_%3$s e
						WHERE
							%1$L = ''"'' || e.wert || ''"''
					', value, schema, type);
				END IF;
				EXECUTE sql INTO result;
				RETURN result;
			END;
	$BODY$;

	CREATE OR REPLACE FUNCTION public.gdi_datatype_json_to_text(
		value json,
		is_array boolean)
			RETURNS text
			LANGUAGE 'plpgsql'
			COST 100
			
	AS $BODY$
			DECLARE
				sql text;
				result text;
			BEGIN
				IF is_array THEN
					sql = FORMAT('
						SELECT
							STRING_AGG(he::text, '', '')
						FROM
							(
								SELECT json_array_elements(json_strip_nulls(%1$L)) he
							) et
					', value);
				ELSE
					sql = FORMAT('
						SELECT CASE WHEN json_strip_nulls(%1$L)::text = ''{}'' THEN NULL ELSE json_strip_nulls(%1$L)::text END 
					', value);
				END IF;
				EXECUTE sql INTO result;
				RETURN result;
			END;
	$BODY$;

	CREATE OR REPLACE FUNCTION public.gdi_codelist_extract_ids(
		input_value anyelement,
		only_first boolean)
			RETURNS text
			LANGUAGE 'plpgsql'
			COST 100
			
	AS $BODY$
	DECLARE
		is_array boolean;
		result text;
	BEGIN
		is_array = pg_typeof(input_value)::text LIKE '%[]';
		IF input_value IS NULL THEN
			RETURN NULL;
		ELSE
			IF is_array THEN
				IF array_length(input_value, 1) IS NULL OR array_length(input_value, 1) = 0 THEN
					RETURN NULL;
				END IF;
				IF only_first THEN
					RETURN (input_value[1]).id;
				ELSE
					SELECT
						string_agg((dt).id, ',')
					FROM
						unnest($1) AS dt
					INTO result;
					RETURN result;
				END IF;
			ELSE
				RETURN (input_value).id;
			END IF;
		END IF;
	END;
	$BODY$;

	CREATE OR REPLACE FUNCTION public.gdi_codelist_json_to_text(
		codelist json)
			RETURNS text
			LANGUAGE 'sql'
			COST 100
			
	AS $BODY$
			SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(json_strip_nulls(codelist)::text, '['::text, ''::text), ']', ''), '{', ''), '}', ''), '"', ''), ', ', ','), ',', ', '), ':', ': ')
	$BODY$;

	CREATE OR REPLACE FUNCTION public.gdi_codelist_extract_ids(
		input_value anyelement,
		only_first boolean)
			RETURNS text
			LANGUAGE 'plpgsql'
			COST 100
			
	AS $BODY$
	DECLARE
		is_array boolean;
		result text;
	BEGIN
		is_array = pg_typeof(input_value)::text LIKE '%[]';
		IF input_value IS NULL THEN
			RETURN NULL;
		ELSE
			IF is_array THEN
				IF array_length(input_value, 1) IS NULL OR array_length(input_value, 1) = 0 THEN
					RETURN NULL;
				END IF;
				IF only_first THEN
					RETURN (input_value[1]).id;
				ELSE
					SELECT
						string_agg((dt).id, ',')
					FROM
						unnest($1) AS dt
					INTO result;
					RETURN result;
				END IF;
			ELSE
				RETURN (input_value).id;
			END IF;
		END IF;
	END;
	$BODY$;

	CREATE OR REPLACE FUNCTION public.gdi_curve_to_polygon(
		curve geometry)
			RETURNS geometry
			LANGUAGE 'sql'
			COST 100
			
	AS $BODY$
		WITH polygons AS (
			SELECT
				(ST_Dump(ST_Polygonize(line))).path[1] AS id,
				ST_NumGeometries(ST_Polygonize(line)) AS num_geom,
				(ST_Dump(ST_Polygonize(line))).geom AS geom
			FROM
				(
					SELECT
						ST_CurveToLine((ST_Dump(curve)).geom) line
				) lines
		)
		SELECT DISTINCT
			geom2
		FROM
			(
				SELECT
					a.id AS id1,
					b.id aS id2,
					a.num_geom,
					ST_MakePolygon(ST_ExteriorRing(a.geom)) AS exring1,
					b.geom AS geom2,
					ST_MakePolygon(ST_ExteriorRing(b.geom)) AS exring2
				FROM
					polygons AS a,
					polygons AS b
			) list
		WHERE
			num_geom = 1
			OR (
				id1 != id2
				AND ST_Within(exring1, exring2)
			)
	$BODY$;
	COMMENT ON FUNCTION public.gdi_curve_to_polygon(geometry)
			IS 'Die Funktion wandelt eine curve vom Typ ST_CurvePolygon in ein Polygon vom Typ ST_Polygon um. Die Componenten der curve werden zunächst mit ST_CurveToLine() in LineStrings gewandelt. Mit der Aggregationsfunktion ST_Polygonize() werden aus den sich durch die LineStrings ergebenen Flächen Polygone gemacht, die die gesamte Fläche ausfüllen. Besteht das Resultat aus nur einem Polygon, wird dieses zurückgeliefert. Existieren mehrere in Folge von inneren Ringen, werden im nächsten Schritt die äußeren Ringe der sich ergebenen Polygone extrahiert und geprüft welche vollständig innerhalb der anderen liegen. Ausgegeben werden dann nur die Polygone, die andere Polygone vollständig beinhalten. Damit werden die Polygone, die die Löcher repräsentieren rausgefiltert.';
COMMIT;