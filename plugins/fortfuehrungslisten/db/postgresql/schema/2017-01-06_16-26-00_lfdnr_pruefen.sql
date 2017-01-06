CREATE OR REPLACE FUNCTION fortfuehrungslisten.ff_auftraege_next_lfdnr(in in_jahr integer, IN in_gemkgnr integer, OUT lfdnr integer)
  RETURNS integer AS
$BODY$
  SELECT coalesce(lfdnr, 0) + increment_table.n FROM (SELECT 1 AS n) AS increment_table left join (SELECT max(lfdnr) lfdnr FROM fortfuehrungslisten.ff_auftraege WHERE jahr = in_jahr AND gemkgnr = in_gemkgnr) AS lfdnr_table ON true
    $BODY$
  LANGUAGE sql VOLATILE
  COST 100;

COMMENT ON FUNCTION fortfuehrungslisten.ff_auftraege_next_lfdnr(integer, integer) IS 'Liefert die nächste freie Nummer pro Fortführungsjahr (jahr) und Gemarkung (gemkgnr) aus der Tabelle der Fortführungsaufträge (ff_auftraege).';

CREATE OR REPLACE FUNCTION fortfuehrungslisten.ff_auftraege_set_next_lfdnr() RETURNS trigger AS $$
BEGIN
	NEW.lfdnr := fortfuehrungslisten.ff_auftraege_next_lfdnr(new.jahr, new.gemkgnr);
		RETURN new;
	END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER ff_auftraege_set_next_lfdnr before insert or update on fortfuehrungslisten.ff_auftraege
	FOR EACH ROW
	EXECUTE PROCEDURE fortfuehrungslisten.ff_auftraege_set_next_lfdnr();