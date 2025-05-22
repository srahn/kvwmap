BEGIN;

INSERT INTO kvwmap.config (name, prefix, value, description, type, "group", plugin, saved, editable) VALUES
('LAYER_ID_JAGDBEZIRKE', '', '432', '', 'numeric', 'Plugins/jagdkataster', 'jagdkataster', 0, 2);

COMMIT;
