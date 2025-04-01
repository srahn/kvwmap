BEGIN;

  --DROP FUNCTION ukos_okstra.clear_topo(se_idents character varying);
  CREATE OR REPLACE FUNCTION ukos_okstra.clear_topo(se_idents character varying)
  RETURNS BOOLEAN AS
  $BODY$
    DECLARE
      tolerance     NUMERIC;
      sql           TEXT;
      invalid_ed    TEXT;
      invalid_se    TEXT;
      debug         BOOLEAN = false;
    BEGIN

      --------------------------------------------------------------------------------------------------------
      -- Initialisierung
      EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;
      EXECUTE 'SELECT value FROM ukos_base.config WHERE key = $1' USING 'Debugmodus' INTO debug;

      --------------------------------------------------------------------------------------------------------
      -- Frage edges ab, die zu SE gehören, die mehr als eine edge in der TopoGeom haben
      sql = FORMAT('
        SELECT
          string_agg(edge_id::text, '', '')
        FROM
          (
            SELECT DISTINCT
              edge_id
            FROM
              (
                SELECT
                  se.ident,
                  r.element_id AS edge_id,
                  count(r.element_id) OVER (PARTITION BY se.ident) num
                FROM
                  ukos_topo.relation r JOIN
                  ukos_okstra.strassenelement se ON r.topogeo_id = (liniengeometrie_topo).id
                WHERE se.ident IN (%1$s)
              ) foo
            WHERE
              num > 1
          ) bar
      ', se_idents);
      RAISE NOTICE 'Invalide Edges: %', invalid_ed;
      EXECUTE sql INTO invalid_ed;

      -- Frage die SE ab, die mehr als eine edge haben und setze die TopoGeom zurück
      sql = FORMAT('
        SELECT DISTINCT
          '''''''' || string_agg(ident, '''''', '''''') || ''''''''
        FROM
          (
            SELECT
              se.ident
            FROM
              ukos_topo.relation r JOIN
              ukos_okstra.strassenelement se ON r.topogeo_id = (liniengeometrie_topo).id
            WHERE
              se.ident IN (%1$s)
            GROUP BY
              se.ident
            HAVING
              count(se.ident) > 1
          ) foo;
      ', se_idents);
      RAISE NOTICE 'Invalid Strassenelemente: %', invalid_se;
      EXECUTE sql INTO invalid_se;

      -- Clear Topo Geom
      sql = FORMAT('
        UPDATE
          ukos_okstra.strassenelement
        SET
          liniengeometrie_topo = topology.clearTopoGeom(liniengeometrie_topo)
        WHERE
          ident IN (%1$s)
      ', invalid_se);
      RAISE NOTICE 'Clear Topo Geom with SQL: %', sql;
      -- EXECUTE sql;

      -- Remove Edges
      sql = FORMAT('
        SELECT
          topology.ST_RemEdgeModFace(''ukos_topo'', edge_id)
        FROM
          ukos_topo.edge_data
        WHERE
          edge_id IN (%1$s)
      ', invalid_ed);
      RAISE NOTICE 'Remove Edges with SQL: %', sql;
      EXECUTE sql;

      sql = FORMAT('
        SELECT
          topology.ST_RemoveIsoNode(''ukos_topo'', node_id)
        FROM
          ukos_topo.node n LEFT JOIN
          ukos_topo.edge_data es ON n.node_id = es.start_node LEFT JOIN
          ukos_topo.edge_data ee ON n.node_id = ee.end_node
        WHERE
          es.edge_id IS NULL AND
          ee.edge_id IS NULL
      ');
      RAISE NOTICE 'Entferne Isolierte Knoten';
      EXECUTE sql;

      -- Stelle TopoGeom wieder her
      sql = FORMAT('
        UPDATE
          ukos_okstra.strassenelement
        SET
          liniengeometrie_topo = topology.toTopoGeom(liniengeometrie, ''ukos_topo'', 1, %2$s)
        WHERE
          ident IN (%1$s)
      ', invalid_se, tolerance);
      RAISE NOTICE 'Recreate Topo Geom with SQL: %', sql;
      EXECUTE sql;

      RETURN true;
    END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
  COMMENT ON FUNCTION ukos_okstra.clear_topo(character varying) IS 'Die Funktion löscht die Topologien von Strassenelementen, die mehr als ein Kante in der Topologie haben und baut die Topologie neu auf. Die Funktion wird angewendet auf alle im Parameter angegebenen ident, die Hochkommas und Kommasepariert übergeben werden müssen.';

COMMIT;
