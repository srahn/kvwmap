BEGIN;

	-- DROP FUNCTION ukos_okstra.validate_strassenelement();
	CREATE OR REPLACE FUNCTION ukos_okstra.validate_strassenelement()
	RETURNS trigger AS
	$BODY$
		DECLARE
			vp_beginnt_bei_geometrie geometry(Point, 25833);
			vp_endet_bei_geometrie	 geometry(Point, 25833);
			rec													 RECORD;
			tolerance										 NUMERIC;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Initialisierung
			vp_beginnt_bei_geometrie	= ST_StartPoint(NEW.liniengeometrie);
			vp_endet_bei_geometrie		= ST_EndPoint(NEW.liniengeometrie);
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'tolerance' INTO tolerance;

			--------------------------------------------------------------------------------------------------------
			-- Exceptions

	    -- Prüfe ob eine Liniengeometrie existiert
			IF NEW.liniengeometrie IS NULL THEN
				RAISE EXCEPTION 'Liniengeometrie leer. Strassenelement muss eine Liniengeometrie haben.';
			END IF;

			-- Prüfe ob Strassenelement mit gleicher Geometrie schon existiert
			FOR rec IN EXECUTE 'SELECT id FROM ukos_okstra.strassenelement '
				|| 'WHERE ST_Equals($1, liniengeometrie)'
				USING NEW.liniengeometrie
			LOOP
				RAISE EXCEPTION 'Strassenelement mit gleicher Liniengeometrie existiert schon.';
			END LOOP;

			-- ToDo: Prüfen ob neues Strassenelement
			--				- vorhandenes Strassenelemnt schneidet
			--				- Verbindungspunkt schneidet
			--				- auf einem anderen Strassenelement liegt

			--------------------------------------------------------------------------------------------------------
	    -- Berechnungen
	    -- Berechnung der Länge
	    NEW.laenge = ST_Length(NEW.liniengeometrie);

		RETURN NEW;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	CREATE TRIGGER validate_strassenelement
	BEFORE INSERT
	ON ukos_okstra.strassenelement
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_strassenelement();

	-- DROP FUNCTION ukos_okstra.add_strassenelement();
	CREATE OR REPLACE FUNCTION ukos_okstra.add_strassenelement()
	RETURNS trigger AS
	$BODY$
		DECLARE
			uuid_vp             				 character varying;
			vp_beginnt_bei_geometrie geometry(Point, 25833);
			vp_endet_bei_geometrie	 geometry(Point, 25833);
			rec													 RECORD;
			tolerance										 NUMERIC;
		BEGIN
			--------------------------------------------------------------------------------------------------------
      -- Initialisierung
			vp_beginnt_bei_geometrie	= ST_StartPoint(NEW.liniengeometrie);
			vp_endet_bei_geometrie		= ST_EndPoint(NEW.liniengeometrie);
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'tolerance' INTO tolerance;

			--------------------------------------------------------------------------------------------------------
			EXECUTE '
				SELECT id, punktgeometrie
				FROM ukos_okstra.verbindungspunkt WHERE ST_Distance(punktgeometrie, $1) < $2
				ORDER BY ST_Distance(punktgeometrie, $1)
				LIMIT 1
			'
			USING vp_beginnt_bei_geometrie, tolerance
			INTO rec;

			IF rec.id IS NULL THEN --wenn kein Knoten an der Stelle gefunden wird
				uuid_vp = uuid_generate_v4(); --dann uuid neu generieren
        RAISE NOTICE 'Kein Verbindungspunkt (VP) am Anfang des Strassenelementes gefunden. Lege neuen VP an mit id: %', uuid_vp;
				EXECUTE 'INSERT INTO ukos_okstra.verbindungspunkt (id, id_strasse, punktgeometrie) VALUES ( $1, $2, $3 )'
				USING uuid_vp, NEW.id_strasse, vp_beginnt_bei_geometrie; --und Datensatz anlegen
				NEW.beginnt_bei_vp = uuid_vp; --und die id im neuen strassenelement eintragen
			ELSE
				RAISE NOTICE 'Verbindungspunkt id: % am Anfang des Strassenelementes gefunden.', rec.id;
				NEW.beginnt_bei_vp = rec.id; --sonst nur die id des gefundenen Verbindungspunktes im neuen strassenelement eintragen
			END IF;

			--------------------------------------------------------------------------------------------------------
			EXECUTE '
				SELECT id, punktgeometrie
				FROM ukos_okstra.verbindungspunkt WHERE ST_Distance(punktgeometrie, $1) <= $2
				ORDER BY ST_Distance(punktgeometrie, $1)
				LIMIT 1
			'
			USING vp_endet_bei_geometrie, tolerance
			INTO rec;

			IF rec.id IS NULL THEN --wenn kein Knoten an der Stelle gefunden wird
				uuid_vp = uuid_generate_v4(); --dann uuid neu generieren
        RAISE NOTICE 'Kein Verbindungspunkt (VP) am Ende des Strassenelementes gefunden. Lege neuen VP an mit id: %', uuid_vp;
				EXECUTE 'INSERT INTO ukos_okstra.verbindungspunkt (id, id_strasse, punktgeometrie) VALUES ( $1, $2, $3 )'
				USING uuid_vp, NEW.id_strasse, vp_endet_bei_geometrie; --und Datensatz anlegen
				NEW.endet_bei_vp = uuid_vp; --und die id im neuen strassenelement eintragen
			ELSE
				RAISE NOTICE 'Verbindungspunkt id: % am Ende des Strassenelementes gefunden.', rec.id;
				NEW.endet_bei_vp = rec.id; --sonst nur die id des gefundenen Verbindungspunktes im neuen strassenelement eintragen
			END IF;

			EXECUTE 'UPDATE ukos_okstra.strassenelement SET beginnt_bei_vp = $1, endet_bei_vp = $2 WHERE id = $3'
			USING  NEW.beginnt_bei_vp, NEW.endet_bei_vp, NEW.id;

		RETURN NEW;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	CREATE TRIGGER add_strassenelement
	AFTER INSERT
	ON ukos_okstra.strassenelement
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_strassenelement();

	CREATE INDEX strassenelement_gist
	ON ukos_okstra.strassenelement
	USING gist
	(liniengeometrie);
	
/*
INSERT INTO ukos_okstra.strassenelement (liniengeometrie, id_strasse, angelegt_am, erfassungsdatum) values 
  (ST_geometryfromtext('Linestring(400130 5900070, 400100 5900020.11)', 25833), '00000000-0000-0000-0000-000000000000', now(), '2018-10-16');

select id, beginnt_bei_vp, endet_bei_vp, laenge from ukos_okstra.strassenelement
delete from ukos_okstra.strassenelement

select id from ukos_okstra.verbindungspunkt
delete from ukos_okstra.verbindungspunkt where id not like '00000000-0000-0000-0000-000000000000'

select * from ukos_base.idents
delete from ukos_base.idents
*/	

COMMIT;