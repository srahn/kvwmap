BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES (
  'LAYER_ID_FLURSTUECKE', 
  '', 
  (select 
	  layer_id 
  from 
	  kvwmap.layer 
	  join kvwmap.config on config."name" = 'LAYERNAME_FLURSTUECKE' and config.value = layer.name), 
  'Layer-ID des Flurstückslayers', 
  'numeric', 
  'Plugins/alkis', 
  'alkis', 
  0, 
  2);


COMMIT;
