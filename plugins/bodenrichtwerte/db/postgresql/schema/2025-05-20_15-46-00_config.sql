BEGIN;

INSERT INTO kvwmap.config (name, prefix, value, description, type, "group", plugin, saved, editable) VALUES
('LAYERNAME_BODENRICHTWERTE', '', 'BORIS_ALKIS', '', 'string', 'Plugins/bodenrichtwerte', 'bodenrichtwerte', 0, 2);

COMMIT;
