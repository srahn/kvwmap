BEGIN;
/* fix to add primary keys to tables before adding bereich foreign key
all tables already have an abstract primary key xp_plan_pkey from xp_plan
*/
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'bp_plan_pkey' AND conrelid::regclass::text = 'xplan_gml.bp_plan') THEN
			ALTER TABLE
				xplan_gml.bp_plan
			ADD PRIMARY KEY (gml_id);
    END IF;
END;
$$;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fp_plan_pkey' AND conrelid::regclass::text = 'xplan_gml.fp_plan') THEN
			ALTER TABLE
				xplan_gml.fp_plan
			ADD PRIMARY KEY (gml_id);
    END IF;
END;
$$;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'so_plan_pkey' AND conrelid::regclass::text = 'xplan_gml.so_plan') THEN
			ALTER TABLE
				xplan_gml.so_plan
			ADD PRIMARY KEY (gml_id);
    END IF;
END;
$$;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'rp_plan_pkey' AND conrelid::regclass::text = 'xplan_gml.rp_plan') THEN
			ALTER TABLE
				xplan_gml.rp_plan
			ADD PRIMARY KEY (gml_id);
    END IF;
END;
$$;

--bp_bereich
ALTER TABLE
	xplan_gml.bp_bereich
ALTER COLUMN
	gehoertzuplan
SET DATA TYPE
	uuid
USING
	gehoertzuplan::uuid;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'bp_bereich_gehoertzuplan_fkey') THEN
			ALTER TABLE
				xplan_gml.bp_bereich
			ADD CONSTRAINT
				bp_bereich_gehoertzuplan_fkey
			FOREIGN KEY
				(gehoertzuplan)
			REFERENCES 
				xplan_gml.bp_plan (gml_id)
			MATCH SIMPLE
			ON UPDATE CASCADE
			ON DELETE CASCADE;
    END IF;
END;
$$;

--fp_bereich
ALTER TABLE
	xplan_gml.fp_bereich
ALTER COLUMN
	gehoertzuplan
SET DATA TYPE
	uuid
USING
	gehoertzuplan::uuid;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fp_bereich_gehoertzuplan_fkey') THEN
			ALTER TABLE
				xplan_gml.fp_bereich
			ADD CONSTRAINT
				fp_bereich_gehoertzuplan_fkey
			FOREIGN KEY
				(gehoertzuplan)
			REFERENCES 
				xplan_gml.fp_plan (gml_id)
			MATCH SIMPLE
			ON UPDATE CASCADE
			ON DELETE CASCADE;
    END IF;
END;
$$;

--rp_bereich
ALTER TABLE
	xplan_gml.rp_bereich
ALTER COLUMN
	gehoertzuplan
SET DATA TYPE
	uuid
USING
	gehoertzuplan::uuid;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'rp_bereich_gehoertzuplan_fkey') THEN
			ALTER TABLE
				xplan_gml.rp_bereich
			ADD CONSTRAINT
				rp_bereich_gehoertzuplan_fkey
			FOREIGN KEY
				(gehoertzuplan)
			REFERENCES 
				xplan_gml.rp_plan (gml_id)
			MATCH SIMPLE
			ON UPDATE CASCADE
			ON DELETE CASCADE;
    END IF;
END;
$$;

--so_bereich
ALTER TABLE
	xplan_gml.so_bereich
ALTER COLUMN
	gehoertzuplan
SET DATA TYPE
	uuid
USING
	gehoertzuplan::uuid;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'so_bereich_gehoertzuplan_fkey') THEN
			ALTER TABLE
				xplan_gml.so_bereich
			ADD CONSTRAINT
				so_bereich_gehoertzuplan_fkey
			FOREIGN KEY
				(gehoertzuplan)
			REFERENCES 
				xplan_gml.so_plan (gml_id)
			MATCH SIMPLE
			ON UPDATE CASCADE
			ON DELETE CASCADE;
    END IF;
END;
$$;
COMMIT;