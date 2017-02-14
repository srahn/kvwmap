BEGIN;

COMMENT ON TABLE nachweisverwaltung.fp_afismv
  IS 'Tabelle wird gefüllt über: ogr2ogr -f PostgreSQL -append "PG:user=XXXXX host= XXXXX dbname= XXXXX schemas=nachweisverwaltung password=XXXXX" "WFS:http://www.geodaten-mv.de/dienste/afis_wfs?service=WFS&request=GetFeature&version=1.1.0&typeName=afismv:afis_wfs&srsName=EPSG:25833" -nln fp_afismv --config OGR_TRUNCATE YES --config PG_USE_COPY YES';


COMMIT;
