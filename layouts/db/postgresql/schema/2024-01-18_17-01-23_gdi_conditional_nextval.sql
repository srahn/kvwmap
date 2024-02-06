BEGIN;
	CREATE OR REPLACE FUNCTION public.gdi_conditional_nextval(
		_schema character varying,
		_table character varying,
		_column character varying,
		_condition character varying
	)
	RETURNS integer 
	LANGUAGE plpgsql
	COST 100
	VOLATILE PARALLEL UNSAFE
	AS $BODY$
		DECLARE
			_sql text;
			_next_value integer;
		BEGIN
			_sql = FORMAT('
				SELECT
					max(%3$s) + 1
				FROM
					%1$s.%2$s
				WHERE
					%4$s
				',
				_schema, _table, _column, _condition
			);
			RAISE NOTICE 'Abfrage des next val: %', _sql;
			EXECUTE _sql INTO _next_value;
			RAISE NOTICE 'next_val: %', _next_value;
			RETURN _next_value;
		END
	$BODY$;

	COMMENT ON FUNCTION public.gdi_conditional_nextval(character varying, character varying, character varying, character varying)
			IS 'Liefert den höchsten Wert aus der Spalte _column der _table im _schema mit der Bedingung _condition. Wird auch von kvmobile unterstütz z.B. zum Hochzählen von Nummern pro Nutzer. Kann im Attributeditor als Default Wert mit Layerparametern, $user_id, $stelle_id usw. verwendet werden. condition darf nur Attribute der angegebenen Tabelle enthalten.';
COMMIT;