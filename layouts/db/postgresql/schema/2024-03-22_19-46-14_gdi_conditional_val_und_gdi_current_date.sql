BEGIN;
	CREATE OR REPLACE FUNCTION public.gdi_conditional_val(
		_schema character varying,
		_table character varying,
		_column character varying,
		_condition character varying
	)
			RETURNS integer
			LANGUAGE 'plpgsql'
			COST 100
			VOLATILE PARALLEL UNSAFE
	AS $BODY$
			DECLARE
				_sql text;
				_value integer;
			BEGIN
				_sql = FORMAT('
					SELECT
						%3$s
					FROM
						%1$s.%2$s
					WHERE
						%4$s
					',
					_schema, _table, _column, _condition
				);
				EXECUTE _sql INTO _value;
				RETURN _value;
			END
		
	$BODY$;
	COMMENT ON FUNCTION public.gdi_conditional_val(character varying, character varying, character varying, character varying)
			IS 'Liefert den Wert aus der Spalte _column der _table im _schema mit der Bedingung _condition. Wird auch von kvmobile unterstütz z.B. um Werte vom übergeordneten Objekt abzufragen. Kann im Attributeditor als Default Wert mit Layerparametern, $user_id, $stelle_id usw. verwendet werden. condition darf nur Attribute der angegebenen Tabelle enthalten.';

	CREATE OR REPLACE FUNCTION public.gdi_current_date(
		_date_format character varying
	)
	RETURNS character varying
	LANGUAGE 'plpgsql'
	COST 100
	VOLATILE PARALLEL UNSAFE
	AS $BODY$
		DECLARE
			_formatted_date character varying;
		BEGIN
			_formatted_date = to_char(now(), _date_format);
			RETURN _formatted_date;
		END
	$BODY$;
	COMMENT ON FUNCTION public.gdi_current_date(character varying)
			IS 'Liefert das aktuelle Datum im angegebenen Format. Wird auch von kvmobile unterstütz z.B. um das Datum als Default-Wert zu setzen. Die Funktion kann im Attributeditor als Default Wert verwendet werden.';
COMMIT;