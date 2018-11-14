BEGIN;

DO	$$
	BEGIN
		IF NOT EXISTS (
			SELECT
				column_name
			FROM
				information_schema.columns
			WHERE
				table_schema = 'public' AND
				table_name = 'uko_polygon' AND
				column_name = 'dateiname'
			)
		THEN
			ALTER TABLE uko_polygon ADD COLUMN dateiname varchar(100);
		END IF;
	END
$$;

COMMIT;
