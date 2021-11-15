BEGIN;
/* 
-- As types cannot be altered in transaction blocks, type has to be changed, type dropped, new type created and tables changed back
*/
ALTER TABLE xplan_gml.bp_verentsorgung ALTER COLUMN zweckbestimmung TYPE text[];
ALTER TABLE xplan_gml.fp_verentsorgung ALTER COLUMN zweckbestimmung TYPE text[];


DROP TYPE xplan_gml.xp_zweckbestimmungverentsorgung;
CREATE TYPE xplan_gml.xp_zweckbestimmungverentsorgung AS ENUM
    ('1000', '10000', '10001', '10002', '10003', '10004', '10005', '10006', '10007', '10008', '10009', '100010', '1200', '12000', '12001', '12002', '12003', '12004', '12005', '1300', '13000', '13001', '13002', '13003', '1400', '14000', '14001', '14002', '1600', '16000', '16001', '16002', '16003', '16004', '16005', '1800', '18000', '18001', '18002', '18003', '18004', '18005', '18006', '2000', '20000', '20001', '2200', '22000', '22001', '22002', '22003', '2400', '24000', '24001', '24002', '24003', '24004', '24005', '2600', '26000', '26001', '26002', '2800', '3000', '9999', '99990');

ALTER TABLE xplan_gml.bp_verentsorgung ALTER COLUMN zweckbestimmung TYPE xplan_gml.xp_zweckbestimmungverentsorgung[] USING zweckbestimmung::xplan_gml.xp_zweckbestimmungverentsorgung[];
ALTER TABLE xplan_gml.fp_verentsorgung ALTER COLUMN zweckbestimmung TYPE xplan_gml.xp_zweckbestimmungverentsorgung[] USING zweckbestimmung::xplan_gml.xp_zweckbestimmungverentsorgung[];

COMMIT;
