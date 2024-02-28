BEGIN;
	CREATE OR REPLACE FUNCTION public.gdi_parent_val(
		_parent_schema character varying,
		_parent_table character varying,
		_parent_column character varying,
		_condition character varying)
			RETURNS integer
			LANGUAGE 'plpgsql'
			COST 100
			VOLATILE PARALLEL UNSAFE
	AS $BODY$
			DECLARE
				_sql text;
				_parent_value integer;
			BEGIN
				_sql = FORMAT('
					SELECT
						%3$s
					FROM
						%1$s.%2$s
					WHERE
						%4$s
					',
					_parent_schema, _parent_table, _parent_column, _condition
				);
				RAISE NOTICE 'Abfrage des parent val: %', _sql;
				EXECUTE _sql INTO _parent_value;
				RAISE NOTICE 'parent_val: %', _parent_value;
				RETURN _parent_value;
			END
	$BODY$;

	COMMENT ON FUNCTION public.gdi_parent_val(character varying, character varying, character varying, character varying)
			IS 'Liefert den Wert aus der Spalte _parent_column der _parent_table im _parent_schema mit der Bedingung _condition. Wird auch von kvmobile unterstütz z.B. zum Abfragen von Attributen eines Parent Features. Kann im Attributeditor als Default Wert mit Layerparametern, $user_id, $stelle_id usw. verwendet werden. condition darf nur Attribute der angegebenen Tabelle enthalten.';
COMMIT;