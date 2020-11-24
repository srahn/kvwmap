BEGIN;
/* Fix wasserwirtschaft typo */
	DO $$
		BEGIN 
			IF EXISTS
				( 
					SELECT 
						1
					FROM
						information_schema.tables 
					WHERE
						table_schema = 'xplankonverter'
					AND
						table_name = 'mappingtable_standard_shp_to_db'
				)
			THEN
				UPDATE 
					xplankonverter.mappingtable_standard_shp_to_db 
				SET 
					regel = 'zweckbesti::xplan_gml.xp_zweckbestimmungwasserwirtschaft AS zweckbestimmung'
				WHERE 
					regel = 'zweckbesti::xplan_gml.xp_zweckbestimmungwasserwitschaft AS zweckbestimmung;';
			END IF ;
		END
	$$ ;
COMMIT;