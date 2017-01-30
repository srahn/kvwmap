BEGIN;

BEGIN;

CREATE OR REPLACE RULE altes_flst_pruefen_on_insert AS
    ON INSERT TO fortfuehrungslisten.ff_faelle
   WHERE (
    SELECT
			coalesce(array_length(new.zeigtaufaltesflurstueck, 1), 0) = 0 OR
			count(*) != coalesce(array_length(new.zeigtaufaltesflurstueck, 1), 0)
		FROM
			alkis.ax_flurstueck
		WHERE
      flurstueckskennzeichen IN (SELECT unnest(new.zeigtaufaltesflurstueck))
   ) DO INSTEAD 
SELECT
  CASE
    WHEN coalesce(array_length(new.zeigtaufaltesflurstueck, 1), 0) = 0
    THEN 'Es muss mindestens ein altes Flurstück angegeben werden.'
    ELSE (
      SELECT
        'Folgende alte Flurstücke existieren nicht im aktuellen ALKIS Bestand: ' || array_to_string(array_agg(pruefung.flurstueckskennzeichen), ', ')
      FROM
        (
          SELECT unnest(new.zeigtaufaltesflurstueck) AS flurstueckskennzeichen WHERE array_length(new.zeigtaufaltesflurstueck, 1) > 0
        ) AS pruefung LEFT JOIN
        alkis.ax_flurstueck bestand ON bestand.flurstueckskennzeichen = pruefung.flurstueckskennzeichen
      WHERE
        bestand.flurstueckskennzeichen IS NULL
    )
  END AS msg,
  'error' AS msg_type;
COMMENT ON RULE altes_flst_pruefen_on_insert ON fortfuehrungslisten.ff_faelle IS 'Die Regel prüft ob die in zeigtaufaltesflurstueck angegebenen Flurstücke alle schon in ALKIS enthalten sind. Wenn nicht kommt ein Fehlermeldung an Stelle der Ausführung des INSERT Befehls zurück.';

CREATE OR REPLACE RULE altes_flst_pruefen_on_update AS
    ON UPDATE TO fortfuehrungslisten.ff_faelle
   WHERE (
    SELECT
			coalesce(array_length(new.zeigtaufaltesflurstueck, 1), 0) = 0 OR
			count(*) != coalesce(array_length(new.zeigtaufaltesflurstueck, 1), 0)
		FROM
			alkis.ax_flurstueck
		WHERE
      flurstueckskennzeichen IN (SELECT unnest(new.zeigtaufaltesflurstueck))
   ) DO INSTEAD 
SELECT
  CASE
    WHEN coalesce(array_length(new.zeigtaufaltesflurstueck, 1), 0) = 0
    THEN 'Es muss mindestens ein altes Flurstück angegeben werden.'
    ELSE (
      SELECT
        'Folgende alte Flurstücke existieren nicht im aktuellen ALKIS Bestand: ' || array_to_string(array_agg(pruefung.flurstueckskennzeichen), ', ')
      FROM
        (
          SELECT unnest(new.zeigtaufaltesflurstueck) AS flurstueckskennzeichen WHERE array_length(new.zeigtaufaltesflurstueck, 1) > 0
        ) AS pruefung LEFT JOIN
        alkis.ax_flurstueck bestand ON bestand.flurstueckskennzeichen = pruefung.flurstueckskennzeichen
      WHERE
        bestand.flurstueckskennzeichen IS NULL
    )
  END AS msg,
  'error' AS msg_type;
COMMENT ON RULE altes_flst_pruefen_on_update ON fortfuehrungslisten.ff_faelle IS 'Die Regel prüft ob die in zeigtaufaltesflurstueck angegebenen Flurstücke alle schon in ALKIS enthalten sind. Wenn nicht kommt ein Fehlermeldung an Stelle der Ausführung des INSERT Befehls zurück.';  

COMMIT;

COMMIT;