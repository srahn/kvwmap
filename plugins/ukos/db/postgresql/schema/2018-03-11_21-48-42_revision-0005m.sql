SELECT AddGeometryColumn ('ukos_kataster', 'gemeinde', 'wkb_geometry', 25833, 'MULTIPOLYGON', 2);

SELECT AddGeometryColumn ('ukos_kataster', 'gemeindeteil', 'wkb_geometry', 25833, 'MULTIPOLYGON', 2);

SELECT AddGeometryColumn ('ukos_kataster', 'gemeindeverband', 'wkb_geometry', 25833, 'MULTIPOLYGON', 2);

SELECT AddGeometryColumn ('ukos_kataster', 'kreis', 'wkb_geometry', 25833, 'MULTIPOLYGON', 2);
