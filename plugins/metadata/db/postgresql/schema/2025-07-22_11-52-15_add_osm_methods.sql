BEGIN;

  INSERT INTO metadata.download_methods (name, beschreibung, reihenfolge) VALUES ('overpass', 'Download mit overpass-api', 7);
  INSERT INTO metadata.unpack_methods (name, beschreibung, reihenfolge) VALUES ('osmtogeojson', 'OSMJSON/GeoJSON Konvertierung', 8);
  INSERT INTO metadata.import_methods (name, beschreibung, reihenfolge) VALUES ('ogr2ogr_geojson', 'Import GeoJSON in Postgres', 7);

COMMIT;