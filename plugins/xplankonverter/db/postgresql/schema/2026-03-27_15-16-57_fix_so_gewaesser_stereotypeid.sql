BEGIN;
-- fix for wrongly entered stereotype_id
UPDATE xplan_uml.uml_classes
SET stereotype_id = 'EAID_6FDC92A7_2818_42bf_9B7D_10397E273699'
WHERE stereotype_id = 'EAID_6FDC92A7_3718_42bf_9B7C_11397E273688';
COMMIT;
