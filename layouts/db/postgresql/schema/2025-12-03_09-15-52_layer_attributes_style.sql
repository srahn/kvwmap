BEGIN;

ALTER TABLE kvwmap.layer_attributes ADD style_attribute varchar NULL;

with style_layers as
(
	SELECT 
		layer_id,
		name as style_attribute
	FROM 
		kvwmap.layer_attributes
	where
		form_element_type = 'Style'
)
UPDATE
	kvwmap.layer_attributes
set
	style_attribute = style_layers.style_attribute
from
	style_layers
where
	layer_attributes.layer_id = style_layers.layer_id;

COMMIT;
