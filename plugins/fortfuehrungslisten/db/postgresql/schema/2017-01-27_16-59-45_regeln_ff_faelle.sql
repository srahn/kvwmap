BEGIN;

CREATE OR REPLACE RULE altes_flst_pruefen_on_insert AS
    ON INSERT TO fortfuehrungslisten.ff_faelle
   WHERE ( SELECT substring(new.laufendenummer from 1 for 4)::int >= 2017 AND (COALESCE(array_length(new.zeigtaufaltesflurstueck, 1), 0) = 0 OR count(*) <> COALESCE(array_length(new.zeigtaufaltesflurstueck, 1), 0))
           FROM alkis.ax_flurstueck
          WHERE ax_flurstueck.endet IS NULL AND (ax_flurstueck.flurstueckskennzeichen IN ( SELECT unnest(new.zeigtaufaltesflurstueck) AS unnest))) DO INSTEAD  SELECT 
        CASE
            WHEN COALESCE(array_length(new.zeigtaufaltesflurstueck, 1), 0) = 0 THEN 'Es muss mindestens ein altes Flurstück angegeben werden.'::text
            ELSE ( SELECT 'Folgende alte Flurstücke existieren nicht als bestehende Flurstücke im aktuellen ALKIS Bestand: '::text || array_to_string(array_agg(pruefung.flurstueckskennzeichen), ', '::text)
               FROM ( SELECT unnest(new.zeigtaufaltesflurstueck) AS flurstueckskennzeichen
                      WHERE array_length(new.zeigtaufaltesflurstueck, 1) > 0) pruefung
          LEFT JOIN ( SELECT ax_flurstueck.flurstueckskennzeichen
                       FROM alkis.ax_flurstueck
                      WHERE ax_flurstueck.endet IS NULL) bestand ON bestand.flurstueckskennzeichen = pruefung.flurstueckskennzeichen::bpchar
         WHERE bestand.flurstueckskennzeichen IS NULL)
        END AS msg, 'error' AS msg_type;
COMMENT ON RULE altes_flst_pruefen_on_insert ON fortfuehrungslisten.ff_faelle IS 'Die Regel prüft ab 2017 in laufendenummer ob die in zeigtaufaltesflurstueck angegebenen Flurstücke alle schon als noch aktuelle Flurstuecken in ALKIS enthalten sind. Wenn nicht kommt ein Fehlermeldung an Stelle der Ausführung des INSERT Befehls zurück.';

CREATE OR REPLACE RULE altes_flst_pruefen_on_update AS
    ON UPDATE TO fortfuehrungslisten.ff_faelle
   WHERE ( SELECT substring(new.laufendenummer from 1 for 4)::int >= 2017 AND (COALESCE(array_length(new.zeigtaufaltesflurstueck, 1), 0) = 0 OR count(*) <> COALESCE(array_length(new.zeigtaufaltesflurstueck, 1), 0))
           FROM alkis.ax_flurstueck
          WHERE ax_flurstueck.endet IS NULL AND (ax_flurstueck.flurstueckskennzeichen IN ( SELECT unnest(new.zeigtaufaltesflurstueck) AS unnest))) DO INSTEAD  SELECT 
        CASE
            WHEN COALESCE(array_length(new.zeigtaufaltesflurstueck, 1), 0) = 0 THEN 'Es muss mindestens ein altes Flurstück angegeben werden.'::text
            ELSE ( SELECT 'Folgende alte Flurstücke existieren nicht als bestehende Flurstücke im aktuellen ALKIS Bestand: '::text || array_to_string(array_agg(pruefung.flurstueckskennzeichen), ', '::text)
               FROM ( SELECT unnest(new.zeigtaufaltesflurstueck) AS flurstueckskennzeichen
                      WHERE array_length(new.zeigtaufaltesflurstueck, 1) > 0) pruefung
          LEFT JOIN ( SELECT ax_flurstueck.flurstueckskennzeichen
                       FROM alkis.ax_flurstueck
                      WHERE ax_flurstueck.endet IS NULL) bestand ON bestand.flurstueckskennzeichen = pruefung.flurstueckskennzeichen::bpchar
         WHERE bestand.flurstueckskennzeichen IS NULL)
        END AS msg, 'error' AS msg_type;
COMMENT ON RULE altes_flst_pruefen_on_update ON fortfuehrungslisten.ff_faelle IS 'Die Regel prüft ab 2017 in laufendenummer ob die in zeigtaufaltesflurstueck angegebenen Flurstücke alle schon als noch aktuelle Flurstuecken in ALKIS enthalten sind. Wenn nicht kommt ein Fehlermeldung an Stelle der Ausführung des INSERT Befehls zurück.';

COMMIT;