BEGIN;
ALTER TABLE xplan_uml.class_generalizations ADD COLUMN IF NOT EXISTS inheritance_order INTEGER;

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