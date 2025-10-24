BEGIN;

  ALTER TABLE metadata.ressources ADD COLUMN IF NOT EXISTS datasource_id integer;

  ALTER TABLE metadata.ressources DROP CONSTRAINT IF EXISTS dateninhaber_fk;

  ALTER TABLE metadata.ressources ADD CONSTRAINT datasource_fk FOREIGN KEY (datasource_id) REFERENCES kvwmap.datasources(id) ON UPDATE SET NULL ON DELETE SET NULL;

  INSERT INTO kvwmap.datasources (name, beschreibung)
  SELECT
    di.abk AS name,
    di.dateninhaber AS beschreibung
  FROM
    metadata.dateninhaber di LEFT JOIN
    kvwmap.datasources ds ON di.abk = ds.name
  WHERE
    ds.id IS NULL;

  UPDATE
    metadata.ressources r
  SET
    datasource_id = d.datasource_id
  FROM
    (
      SELECT
        ds.id AS datasource_id,
        di.id AS dateninhaber_id
      FROM
        kvwmap.datasources ds JOIN
        metadata.dateninhaber di ON ds.name = di.abk
    ) AS d
  WHERE
    r.dateninhaber_id = d.dateninhaber_id;
  
  -- Change layer ressources manually

  ALTER TABLE metadata.ressources DROP COLUMN IF EXISTS dateninhaber_id;

COMMIT;