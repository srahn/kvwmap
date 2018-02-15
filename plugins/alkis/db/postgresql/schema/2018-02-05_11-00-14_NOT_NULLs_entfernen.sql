BEGIN;

ALTER TABLE alkis.ax_buchungsstelle ALTER COLUMN istbestandteilvon DROP NOT NULL;
ALTER TABLE alkis.ax_flurstueck ALTER COLUMN istgebucht DROP NOT NULL;
ALTER TABLE alkis.ax_gebaeudeausgestaltung ALTER COLUMN zeigtauf DROP NOT NULL;

COMMIT;
