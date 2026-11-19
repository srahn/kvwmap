BEGIN;
  ALTER TABLE xplankonverter.import_jobs ADD COLUMN IF NOT EXISTS upload_file varchar NULL;

  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Originalplan zugeordnet' AFTER 'Plan hochgeladen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Importjob angelegt' AFTER 'Originalplan zugeordnet';
COMMIT;