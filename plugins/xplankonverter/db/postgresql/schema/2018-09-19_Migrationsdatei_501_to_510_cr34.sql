BEGIN;
-- CR 34
	INSERT INTO xplan_gml.enum_bp_speziellebauweisetypen (wert, abkuerzung, beschreibung) VALUES
		(1600,'Bruecke','Brücke'),
		(1700,'Tunnel','Tunnel'),
		(1800,'Rampe','Rampe');
	ALTER TYPE xplan_gml.bp_speziellebauweisetypen ADD VALUE '1600' AFTER '1500';
	ALTER TYPE xplan_gml.bp_speziellebauweisetypen ADD VALUE '1700' AFTER '1600';
	ALTER TYPE xplan_gml.bp_speziellebauweisetypen ADD VALUE '1800' AFTER '1700';
COMMIT;