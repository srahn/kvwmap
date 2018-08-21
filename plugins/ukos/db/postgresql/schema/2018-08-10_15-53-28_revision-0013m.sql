BEGIN;

ALTER TABLE base.punktobjekt ADD COLUMN bei_strassenelementpunkt_id CHARACTER VARYING;

UPDATE base.punktobjekt p
SET bei_strassenelementpunkt_id = sep.id
FROM
  (
    SELECT DISTINCT
      id,
      punktgeometrie,
      station,
      abstand_zur_bestandsachse,
      abstand_zur_fahrbahnoberkante,
      auf_strassenelement
    FROM
      okstra.strassenelementpunkt
  ) AS sep
WHERE
  p.bei_strassenelementpunkt_station = sep.station AND
  p.bei_strassenelementpunkt_abstand_zur_bestandsachse = sep.abstand_zur_bestandsachse AND
  p.bei_strassenelementpunkt_abstand_zur_fahrbahnoberkante = sep.abstand_zur_fahrbahnoberkante AND
  p.bei_strassenelementpunkt_auf_strassenelement = sep.auf_strassenelement AND
  p.geometrie_punktobjekt = sep.punktgeometrie;

ALTER TABLE base.punktobjekt
  DROP COLUMN bei_strassenelementpunkt_station,
  DROP COLUMN bei_strassenelementpunkt_abstand_zur_bestandsachse,
  DROP COLUMN bei_strassenelementpunkt_abstand_zur_fahrbahnoberkante,
  DROP COLUMN bei_strassenelementpunkt_auf_strassenelement,
  DROP COLUMN geometrie_punktobjekt,
  DROP COLUMN bei_strassenpunkt_station,
  DROP COLUMN bei_strassenpunkt_abstand_zur_bestandsachse,
  DROP COLUMN bei_strassenpunkt_abstand_zur_fahrbahnoberkante,
  DROP COLUMN bei_strassenpunkt_auf_abschnitt_oder_ast;

COMMIT;