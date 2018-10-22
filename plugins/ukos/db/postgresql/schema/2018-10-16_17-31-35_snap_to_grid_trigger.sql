BEGIN;
	CREATE EXTENSION hstore;

	CREATE OR REPLACE FUNCTION ukos_base.first_snap_to_grid()
	RETURNS trigger AS
	$BODY$
		DECLARE
			rec RECORD;
			accuracy NUMERIC;
      table_schema CHARACTER VARYING = TG_TABLE_SCHEMA;
      table_name CHARACTER VARYING = TG_TABLE_NAME;
		BEGIN
      RAISE NOTICE 'SnapToGrid in table: %.% ', table_schema, table_name;
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Koordinatengenauigkeit' INTO accuracy;
			IF accuracy IS NOT NULL THEN
				-- Runde Koordinaten auf die Genauigkeit accuracy nach dem Komma
				FOR rec IN EXECUTE '
          SELECT
            g.f_geometry_column
          FROM
						geometry_columns g
						LEFT JOIN (
							SELECT
								n.nspname AS table_schema,
								c.relname as table_name,
								a.attname as column_name
							FROM pg_class c
								JOIN pg_inherits i on c.oid = i.inhrelid
								JOIN pg_attribute a on i.inhparent = a.attrelid
								JOIN pg_namespace n ON n.oid = c.relnamespace
							WHERE
								a.attnum > 0
						) inherited ON g.f_table_schema = inherited.table_schema AND g.f_table_name = inherited.table_name AND g.f_geometry_column = inherited.column_name
					WHERE
						g.f_table_schema = ' || quote_literal(table_schema) || ' AND
						g.f_table_name = ' || quote_literal(table_name) || ' AND
						inherited.column_name IS NULL
					ORDER BY
						g.f_table_schema,
						g.f_geometry_column
				'
				LOOP
					IF (hstore(NEW)->rec.f_geometry_column) IS NOT NULL THEN
						NEW = populate_record(NEW, (rec.f_geometry_column || ' => ' || ST_SnapToGrid(hstore(NEW)->rec.f_geometry_column, accuracy)::text)::hstore);
					END IF;
				END LOOP;
			END IF;
			RETURN NEW;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF geometrie_streckenobjekt
	ON ukos_base.punktundstreckenobjekt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF geometrie_streckenobjekt
	ON ukos_base.streckenobjekt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF wkb_geometry
	ON ukos_kataster.gemeinde
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF wkb_geometry
	ON ukos_kataster.gemeindeteil
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF wkb_geometry
	ON ukos_kataster.gemeindeverband
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF wkb_geometry
	ON ukos_kataster.kreis
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF flaechengeometrie
	ON ukos_okstra.aufbauschicht
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF position_linker_pfosten, geometrie_netzbezugsobjekt_vpunkt, geometrie_netzbezugsobjekt_kompknoten, position_rechter_pfosten
	ON ukos_okstra.aufstellvorrichtung_schild
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF multigeometrie
	ON ukos_okstra.bewuchs
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF geometrie_streckenobjekt, multigeometrie
	ON ukos_okstra.durchlass
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF flaechengeometrie
	ON ukos_okstra.fahrstreifen_nummer
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF multigeometrie
	ON ukos_okstra.kommunikationsobjekt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF liniengeometrie
	ON ukos_okstra.leitung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF flaechengeometrie
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF multigeometrie
	ON ukos_okstra.schacht
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF multigeometrie
	ON ukos_okstra.schutzeinrichtung_aus_stahl
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF multigeometrie
	ON ukos_okstra.strassenablauf
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF multigeometrie
	ON ukos_okstra.strassenausstattung_punkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF multigeometrie
	ON ukos_okstra.strassenausstattung_strecke
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF liniengeometrie
	ON ukos_okstra.strassenelement
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF punktgeometrie
	ON ukos_okstra.strassenelementpunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF punktgeometrie_gauss_krueger, punktgeometrie_utm
	ON ukos_okstra.teilbauwerk
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF punktgeometrie
	ON ukos_okstra.verbindungspunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF flaeche
	ON ukos_okstra.verkehrsflaeche
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF geometrie_streckenobjekt
	ON ukos_okstra.widmung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


	CREATE TRIGGER __first_snap_to_grid
	BEFORE INSERT OR UPDATE OF multigeometrie, geometrie_bereichsobjekt
	ON ukos_okstra.zustaendigkeit
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.first_snap_to_grid();


COMMIT;