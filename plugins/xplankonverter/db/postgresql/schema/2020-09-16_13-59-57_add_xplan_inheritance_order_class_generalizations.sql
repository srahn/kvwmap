BEGIN;
-- Add column if not exists
-- IF NOT EXISTS syntax for columns only supported for Postgres 9.6+, which is not running on all machines yet
-- So Instead do information_schema check
DO
$$
BEGIN
IF NOT EXISTS (SELECT 1
                FROM  information_schema.columns 
               WHERE  table_schema = 'xplan_uml' 
                 AND  table_name = 'class_generalizations' 
                 AND  column_name = 'inheritance_order' ) THEN
ALTER TABLE xplan_uml.class_generalizations ADD COLUMN inheritance_order INTEGER DEFAULT NULL;
ELSE
RAISE NOTICE 'Already exists';
END IF;
END;
$$;

UPDATE xplan_uml.class_generalizations
SET inheritance_order = 1
WHERE xmi_id = 'EAID_B858746D_3792_4e42_9139_92AEED0FB390';

UPDATE xplan_uml.class_generalizations
SET inheritance_order = 2
WHERE xmi_id = 'EAID_82A2516E_CF6E_4704_8965_1ED67B7AF0E9';

UPDATE xplan_uml.class_generalizations
SET inheritance_order = 3
WHERE xmi_id = 'EAID_1190BFAF_0EC4_4bcb_94A7_543058CA5C0F';
COMMIT;