BEGIN;
	CREATE TYPE xplan_gml.xp_spezexternereferenzauslegung AS
	(
		georefurl character varying,
		georefmimetype xplan_gml.xp_mimetypes,
		art xplan_gml.xp_externereferenzart,
		informationssystemurl character varying,
		referenzname character varying,
		referenzurl character varying,
		referenzmimetype xplan_gml.xp_mimetypes,
		beschreibung character varying,
		datum date,
		typ xplan_gml.xp_externereferenztyp,
		nurzurauslegung boolean
	);

	CREATE OR REPLACE FUNCTION public.gdi_make_spezexternereferenzauslegung(
		externereferenz xplan_gml.xp_spezexternereferenz[],
		nurzurauslegung boolean
	)
	RETURNS xplan_gml.xp_spezexternereferenzauslegung[]
	LANGUAGE 'plpgsql'
	COST 100
	VOLATILE PARALLEL UNSAFE
	AS $BODY$
	DECLARE
		_output xplan_gml.xp_spezexternereferenzauslegung[];
		_sql text;
	BEGIN
			SELECT
						array_agg(
							(
								(e_sub).georefurl,
								(e_sub).georefmimetype,
								(e_sub).art,
								(e_sub).informationssystemurl,
								(e_sub).referenzname,
								(e_sub).referenzurl,
								(e_sub).referenzmimetype,
								(e_sub).beschreibung,
								(e_sub).datum,
								(e_sub).typ,
								nurzurauslegung
							)::xplan_gml.xp_spezexternereferenzauslegung
						)::xplan_gml.xp_spezexternereferenzauslegung[] output
					FROM
						(
							SELECT
								unnest(externereferenz) AS e_sub
						) foo
				INTO _output;
			RAISE NOTICE 'output: %', _output;
			RETURN _output;
		END
	$BODY$;

	ALTER TABLE xplan_gml.xp_plan ALTER column externereferenz TYPE xplan_gml.xp_spezexternereferenzauslegung[] USING gdi_make_spezexternereferenzauslegung(externereferenz, false);
COMMIT;