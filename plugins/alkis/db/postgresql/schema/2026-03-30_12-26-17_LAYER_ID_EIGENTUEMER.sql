BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES (
  'LAYER_ID_EIGENTUEMER', 
  '', 
  '', 
  'Layer-ID des Layers der zur Filterung der Flurstücke verwendet wird, bei denen die Eigentümer angezeigt werden dürfen.', 
  'numeric', 
  'Plugins/alkis', 
  'alkis', 
  0, 
  2);

COMMIT;
