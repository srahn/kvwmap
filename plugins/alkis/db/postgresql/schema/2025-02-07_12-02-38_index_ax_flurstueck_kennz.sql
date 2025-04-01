BEGIN;

DROP INDEX IF EXISTS alkis.ax_flurstueck_kennz;
CREATE INDEX ax_flurstueck_kennz ON alkis.ax_flurstueck USING btree (flurstueckskennzeichen text_pattern_ops, endet DESC, beginnt DESC);

COMMIT;
