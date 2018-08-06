BEGIN;

DELETE datatype_attributes, datatypes FROM datatype_attributes LEFT JOIN datatypes ON datatype_attributes.datatype_id = datatypes.id WHERE datatypes.id IS NULL;
ALTER TABLE datatype_attributes ADD FOREIGN KEY (datatype_id) REFERENCES datatypes (id) ON DELETE CASCADE;

COMMIT;
