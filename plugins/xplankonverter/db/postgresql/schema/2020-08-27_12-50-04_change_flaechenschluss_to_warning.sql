BEGIN;

  UPDATE xplankonverter.validierungen SET msg_warning = msg_error, msg_error = null WHERE name IN ('Keine Überlagerung von Flächenschlussobjekten', 'Keine Lücken zwischen Flächenschlussobjekten und Geltungsbereich');
  ALTER TABLE xplankonverter.flaechenschlussobjekte ADD COLUMN uuid character varying;

COMMIT;