BEGIN;

-- Bedeutung Pflicht wenn detaillierte Bedeutung (3.1.2.4)
CREATE RULE check_bedeutung_on_insert AS
ON INSERT TO xplan_gml.bp_bereich
WHERE
  new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL
DO INSTEAD
  SELECT 'Wenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';

CREATE RULE check_bedeutung_on_update AS
ON UPDATE TO xplan_gml.bp_bereich
WHERE
  new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL
DO INSTEAD
  SELECT 'Wenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';

CREATE RULE check_bedeutung_on_insert AS
ON INSERT TO xplan_gml.fp_bereich
WHERE
  new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL
DO INSTEAD
  SELECT 'Wenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';

CREATE RULE check_bedeutung_on_update AS
ON UPDATE TO xplan_gml.fp_bereich
WHERE
  new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL
DO INSTEAD
  SELECT 'Wenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';

CREATE RULE check_bedeutung_on_insert AS
ON INSERT TO xplan_gml.so_bereich
WHERE
  new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL
DO INSTEAD
  SELECT 'Wenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';

CREATE RULE check_bedeutung_on_update AS
ON UPDATE TO xplan_gml.so_bereich
WHERE
  new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL
DO INSTEAD
  SELECT 'Wenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';

CREATE RULE check_bedeutung_on_insert AS
ON INSERT TO xplan_gml.rp_bereich
WHERE
  new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL
DO INSTEAD
  SELECT 'Wenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';

CREATE RULE check_bedeutung_on_update AS
ON UPDATE TO xplan_gml.rp_bereich
WHERE
  new.detailliertebedeutung IS NOT NULL AND new.bedeutung IS NULL
DO INSTEAD
  SELECT 'Wenn eine detaillierte Bedeutung für den Bereich angegeben ist, muss auch eine Bedeutung angegeben werden.';

COMMIT;
