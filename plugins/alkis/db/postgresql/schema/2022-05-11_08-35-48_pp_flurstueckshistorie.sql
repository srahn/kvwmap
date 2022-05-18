BEGIN;

DROP INDEX alkis.ax_flurstueck_kennz;

CREATE INDEX ax_flurstueck_kennz
    ON alkis.ax_flurstueck
 USING btree (flurstueckskennzeichen, beginnt DESC);

CREATE TABLE alkis.pp_flurstueckshistorie
(
  flurstueckskennzeichen character varying,
  zeitpunktderentstehung date,
  vorgaengerflurstueckskennzeichen character varying[],
  nachfolgerflurstueckskennzeichen character varying[]
);

CREATE INDEX ON alkis.pp_flurstueckshistorie
 USING btree (flurstueckskennzeichen);

CREATE INDEX ON alkis.pp_flurstueckshistorie
 USING gin (vorgaengerflurstueckskennzeichen);

CREATE INDEX ON alkis.pp_flurstueckshistorie
 USING gin (nachfolgerflurstueckskennzeichen);
 

COMMIT;
