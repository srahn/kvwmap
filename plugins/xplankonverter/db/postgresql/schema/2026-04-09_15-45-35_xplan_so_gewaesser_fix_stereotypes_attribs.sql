BEGIN;
-- fix for wrongly entered stereotype_id
UPDATE xplan_uml.uml_classes
SET stereotype_id = 'EAID_8D09BC59_987A_481a_9FDE_6816AD7DCEF1'
WHERE stereotype_id = 'EAID_7C09BC56_677A_591a_9FDE_6816BD7DCDD2';

UPDATE xplan_uml.uml_classes
SET stereotype_id = 'EAID_E95F3575_1A65_42f2_B5F2_2EA449652444'
WHERE stereotype_id = 'EAID_D85F3564_2B65_42f2_B5F2_3BA348652443';

COMMIT;