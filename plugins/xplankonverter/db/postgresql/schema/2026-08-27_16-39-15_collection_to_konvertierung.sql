BEGIN;
  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN IF NOT EXISTS layer_collection_id integer;
  ALTER TABLE xplankonverter.konvertierungen DROP CONSTRAINT IF EXISTS layer_collection_id_fk;
  ALTER TABLE xplankonverter.konvertierungen ADD CONSTRAINT layer_collection_id_fk FOREIGN KEY (layer_collection_id) REFERENCES kvwmap.collections(id) ON UPDATE CASCADE ON DELETE SET NULL;

  DROP TRIGGER IF EXISTS before_delete ON xplankonverter.konvertierungen;
  DROP FUNCTION IF EXISTS xplankonverter.delete_collection();
  CREATE OR REPLACE FUNCTION xplankonverter.delete_collection()
  RETURNS TRIGGER
  LANGUAGE plpgsql AS $$
  BEGIN
    DELETE FROM kvwmap.collections WHERE id = OLD.collection_id;
    RETURN OLD;
  END;
  $$;  

  CREATE TRIGGER before_delete
  BEFORE DELETE ON xplankonverter.konvertierungen
  FOR EACH ROW
  EXECUTE FUNCTION xplankonverter.delete_collection();
COMMIT;