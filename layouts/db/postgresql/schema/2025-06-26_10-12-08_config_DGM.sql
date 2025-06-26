BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES
  ('DGM_LAYER_ID', '', '', 'Layer-ID des DGM-Vektorlayers, der für die Erzeugung der Höhenprofile verwendet wird.', 'numeric', 'Layer-IDs', '', 0, 2),
  ('DGM_HEIGHT_ATTRIBUTE', '', '', 'Name des Attributs im DGM-Vektorlayer, in dem die Höhe gespeichert ist.', 'string', 'Layer-IDs', '', 0, 2);

COMMIT;
