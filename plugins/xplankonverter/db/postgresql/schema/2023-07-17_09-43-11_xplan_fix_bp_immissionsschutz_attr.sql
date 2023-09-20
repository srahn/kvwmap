BEGIN;
-- Change column name if exists
DO $$
BEGIN
  IF EXISTS(SELECT *
    FROM information_schema.columns
    WHERE table_name='bp_immissionsschutz' and column_name='detailliertetechnvorkehrungtyp')
  THEN
      ALTER TABLE "xplan_gml"."bp_immissionsschutz" RENAME COLUMN "detailliertetechnvorkehrungtyp" TO "detailliertetechnvorkehrung";
  END IF;
END $$;
COMMIT;
