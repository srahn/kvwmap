BEGIN;

ALTER TABLE xplan_gml.bp_plan ALTER COLUMN nummer SET DEFAULT '';
ALTER TABLE xplan_gml.bp_plan ALTER COLUMN nummer SET NOT NULL;

ALTER TABLE xplan_gml.fp_plan ALTER COLUMN nummer SET DEFAULT '';
ALTER TABLE xplan_gml.fp_plan ALTER COLUMN nummer SET NOT NULL;

ALTER TABLE xplan_gml.bp_plan ALTER COLUMN rechtsstand SET DEFAULT '1000'::xplan_gml.bp_rechtsstand;
ALTER TABLE xplan_gml.bp_plan ALTER COLUMN rechtsstand SET NOT NULL;

ALTER TABLE xplan_gml.fp_plan ALTER COLUMN rechtsstand SET DEFAULT '1000'::xplan_gml.fp_rechtsstand;
ALTER TABLE xplan_gml.fp_plan ALTER COLUMN rechtsstand SET NOT NULL;

ALTER TABLE xplan_gml.so_plan ALTER COLUMN gemeinde SET NOT NULL;

-- auslegungsdatum bp_plan
CREATE OR REPLACE RULE check_auslegungsdatum_on_insert AS
ON INSERT TO xplan_gml.bp_plan
WHERE
  new.rechtsstand = '2400'::xplan_gml.bp_rechtsstand AND
  (
    new.auslegungsstartdatum IS NULL OR
    new.auslegungsenddatum IS NULL
  )
DO INSTEAD
  SELECT 'Wenn der Rechtsstand Öffentliche Auslegung ausgewählt ist, muss auch ein Start und ein Enddatum für die Auslegung angegeben werden.';

CREATE OR REPLACE RULE check_auslegungsdatum_on_update AS
ON UPDATE TO xplan_gml.bp_plan
WHERE
  new.rechtsstand = '2400'::xplan_gml.bp_rechtsstand AND
  (
    new.auslegungsstartdatum IS NULL OR
    new.auslegungsenddatum IS NULL
  )
DO INSTEAD
  SELECT 'Wenn der Rechtsstand Öffentliche Auslegung ausgewählt ist, muss auch ein Start und ein Enddatum für die Auslegung angegeben werden.';

-- auslegungsdatum fp_plan
CREATE OR REPLACE RULE check_auslegungsdatum_on_insert AS
ON INSERT TO xplan_gml.fp_plan
WHERE
  new.rechtsstand = '2400'::xplan_gml.fp_rechtsstand AND
  (
    new.auslegungsstartdatum IS NULL OR
    new.auslegungsenddatum IS NULL
  )
DO INSTEAD
  SELECT 'Wenn der Rechtsstand Öffentliche Auslegung ausgewählt ist, muss auch ein Start und ein Enddatum für die Auslegung angegeben werden.';

CREATE OR REPLACE RULE check_auslegungsdatum_on_update AS
ON UPDATE TO xplan_gml.fp_plan
WHERE
  new.rechtsstand = '2400'::xplan_gml.fp_rechtsstand AND
  (
    new.auslegungsstartdatum IS NULL OR
    new.auslegungsenddatum IS NULL
  )
DO INSTEAD
  SELECT 'Wenn der Rechtsstand Öffentliche Auslegung ausgewählt ist, muss auch ein Start und ein Enddatum für die Auslegung angegeben werden.';

-- inkrafttretensdatum bp_plan
CREATE OR REPLACE RULE check_inkrafttretensdatum_on_insert AS
ON INSERT TO xplan_gml.bp_plan
WHERE
  new.rechtsstand >= '3000'::xplan_gml.bp_rechtsstand AND
  new.inkrafttretensdatum IS NULL
DO INSTEAD
  SELECT 'Wenn der Rechtsstand 3000 oder größer ist, muss auch ein Datum des Inkrafttretens des Planes angegeben werden.';

CREATE OR REPLACE RULE check_inkrafttretensdatum_on_update AS
ON UPDATE TO xplan_gml.bp_plan
WHERE
  new.rechtsstand >= '3000'::xplan_gml.bp_rechtsstand AND
  new.inkrafttretensdatum IS NULL
DO INSTEAD
  SELECT 'Wenn der Rechtsstand 3000 oder größer ist, muss auch ein Datum des Inkrafttretens des Planes angegeben werden.';

-- wirksamkeitsdatum bp_plan
CREATE OR REPLACE RULE check_wirksamkeitsdatum_on_insert AS
ON INSERT TO xplan_gml.fp_plan
WHERE
  new.rechtsstand >= '3000'::xplan_gml.fp_rechtsstand AND
  new.wirksamkeitsdatum IS NULL
DO INSTEAD
  SELECT 'Wenn der Rechtsstand 3000 oder größer ist, muss auch ein Datum der Wirksamkeit des Planes angegeben werden.';

CREATE OR REPLACE RULE check_wirksamkeitsdatum_on_update AS
ON UPDATE TO xplan_gml.fp_plan
WHERE
  new.rechtsstand >= '3000'::xplan_gml.fp_rechtsstand AND
  new.wirksamkeitsdatum IS NULL
DO INSTEAD
  SELECT 'Wenn der Rechtsstand 3000 oder größer ist, muss auch ein Datum der Wirksamkeit des Planes angegeben werden.';

COMMIT;
