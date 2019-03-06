BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
('GEO_NAME_SEARCH_URL', '', 'https://nominatim.openstreetmap.org/search.php?format=geojson&q=', 'URL eines Geo-Namen-Such-Dienstes. Der Dienst muss GeoJSON zurückliefern.', 'string', 'Administration', NULL, 0),
('GEO_NAME_SEARCH_PROPERTY', '', 'display_name', 'Das Attribut welches als Suchergebnis bei der Geo-Namen-Suche angezeigt werden soll.', 'string', 'Administration', NULL, 0);

COMMIT;
