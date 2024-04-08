 BEGIN;
	ALTER TYPE xplan_gml.bp_laerrmpegelbereich RENAME TO bp_laermpegelbereich;
 
	ALTER TABLE xplan_gml.bp_immissionsschutz
	ALTER COLUMN laermpegelbereich SET DATA TYPE xplan_gml.bp_laermpegelbereich;
 COMMIT;