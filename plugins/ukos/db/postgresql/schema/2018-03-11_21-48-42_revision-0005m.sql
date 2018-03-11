SELECT AddGeometryColumn ('kataster', 'gemeinde', 'wkb_geometry', 25833, 'MULTIPOLYGON', 2);

SELECT AddGeometryColumn ('kataster', 'gemeindeteil', 'wkb_geometry', 25833, 'MULTIPOLYGON', 2);

SELECT AddGeometryColumn ('kataster', 'gemeindeverband', 'wkb_geometry', 25833, 'MULTIPOLYGON', 2);

SELECT AddGeometryColumn ('kataster', 'kreis', 'wkb_geometry', 25833, 'MULTIPOLYGON', 2);
