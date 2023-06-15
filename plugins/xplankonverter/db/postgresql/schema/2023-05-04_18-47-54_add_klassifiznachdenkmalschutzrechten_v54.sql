BEGIN;
/*
Inserts enum value 1500 after enum value 1400
Unlike ALTER TYPE ADD VALUE AFTER, this also works within a begin-commit transaction block (i.e. a kvwmap migration)
*/
INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.so_klassifiznachdenkmalschutzrecht'::regtype::oid, '1500', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum
			WHERE enumtypid = 'xplan_gml.so_klassifiznachdenkmalschutzrecht'::regtype 
			AND enumlabel = '1400'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.so_klassifiznachdenkmalschutzrecht'::regtype 
			AND enumlabel = '1400'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.so_klassifiznachdenkmalschutzrecht'::regtype AND enumlabel = '1500' )
;

/*
Inserts enum value 1600 after enum value 1500
Unlike ALTER TYPE ADD VALUE AFTER, this also works within a begin-commit transaction block (i.e. a kvwmap migration)
*/
INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.so_klassifiznachdenkmalschutzrecht'::regtype::oid, '1600', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum
			WHERE enumtypid = 'xplan_gml.so_klassifiznachdenkmalschutzrecht'::regtype 
			AND enumlabel = '1500'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.so_klassifiznachdenkmalschutzrecht'::regtype 
			AND enumlabel = '1500'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.so_klassifiznachdenkmalschutzrecht'::regtype AND enumlabel = '1600' )
;

-- Update enum Tables
INSERT INTO xplan_gml.enum_so_klassifiznachdenkmalschutzrecht (wert, abkuerzung, beschreibung) VALUES
(1500, 'ArcheologischesDenkmal', 'Archäologisches Denkmal'),
(1600, 'Bodendenkmal', 'Bodendenkmal');

-- UPDATE xplan_uml
-- the following values are not used as they are set automatically or are NULL: modelId,isSpecification,updatedAt,id
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61242ECE_6D9D_3602_A66D_4AE8CA42042F', 'ArcheologischesDenkmal',359,'public','','changeable','instance','unordered',now()::timestamp,'eaxmiid0','','EAID_354F23B0_B89D_4281_AD8D_EBFBE64EA8B1','EAID_354F23B0_B89D_4281_AD8D_FBFBE34EB8B2','1','1','EAID_3B78EC00_C6D5_4d17_8589_3897AD339537','1500'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61242ECE_6D9D_3602_A66D_4AE8CA42042F'
	);
	
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61242ECE_6D9D_3601_A56C_3AE8CA42031F', 'Bodendenkmal',359,'public','','changeable','instance','unordered',now()::timestamp,'eaxmiid0','','EAID_254F23B0_B89D_4281_AD8D_EBFBE64EA8B4','EAID_354F23B0_C87D_4281_AD8D_FCFBE33BF2B1','1','1','EAID_3B78EC00_C3C5_4c18_8587_4897BC439546','1600'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61242ECE_6D9D_3601_A56C_3AE8CA42031F'
	);

COMMIT;