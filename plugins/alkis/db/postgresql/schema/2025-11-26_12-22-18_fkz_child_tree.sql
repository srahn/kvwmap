BEGIN;

CREATE OR REPLACE FUNCTION alkis.fkz_child_tree(
	fkz character varying,
	visited_fkz character varying[] DEFAULT ARRAY[]::character varying[])
 RETURNS text[]
 LANGUAGE plpgsql
AS $function$
declare
	visited text[];
	nachfolgende text[];
	nfkz text;
begin
-- 1. Prüfen, ob das fkz schon verarbeitet wurde
	if $1 = any($2) then
		return $2;
	end if;
-- 2. fkz zur liste hinzufügen
	visited := array_append($2, $1);
-- 3. Daten ermitteln
	select
		array_cat(f.nachfolgerflurstueckskennzeichen, f.vorgaengerflurstueckskennzeichen) -- Änderung
	into nachfolgende
	from alkis.pp_flurstueckshistorie f
	where f.flurstueckskennzeichen = $1;
-- 5. Prüfen, ob Nachfolger vorhanden sind
	if nachfolgende is null
		or array_length(nachfolgende, 1) is null then
		return $2;
	end if;
-- 6. Rekursion für jeden Nachfolger
	foreach nfkz in array nachfolgende loop
		visited := alkis.fkz_child_tree(
			nfkz,
			visited);
	end loop;
-- 7. Ausgabe der Daten
	return visited;
end;
$function$
;

COMMIT;
