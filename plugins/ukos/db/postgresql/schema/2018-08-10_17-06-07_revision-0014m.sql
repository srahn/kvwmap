BEGIN;

CREATE EXTENSION IF NOT EXISTS postgis_topology;

SELECT topology.CreateTopology('ukos_topo', 25833, 1);

--SELECT TopoGeo_AddLineString('ukos_topo', geometryfromtext('LINESTRING(500000 6000000, 500100 6000000)', 25833), 1);
--SELECT TopoGeo_AddLineString('ukos_topo', geometryfromtext('LINESTRING(500050 5999950, 500050 6000050)', 25833), 1);

COMMIT;