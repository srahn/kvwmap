BEGIN;

INSERT INTO kvwmap.config (name, prefix, value, description, type, "group", plugin, saved, editable) VALUES
  ('LAYERDEF_EXPORT_FILE', '', '/var/www/apps/layerdef.json', 'Speicherort der Konfigurationsdatei für die kvportal-Anwendung\r\n', 'string', 'Plugins/portal', 'portal' , 0, 2);

COMMIT;
