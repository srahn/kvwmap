BEGIN;

UPDATE layer_attributes2stelle 
INNER JOIN layer_attributes ON layer_attributes.layer_id = layer_attributes2stelle.layer_id 
AND layer_attributes.name = layer_attributes2stelle.attributename
AND layer_attributes.form_element_type IN ('SubFormPK', 'SubFormEmbeddedPK')
SET layer_attributes2stelle.privileg = 1;

COMMIT;
