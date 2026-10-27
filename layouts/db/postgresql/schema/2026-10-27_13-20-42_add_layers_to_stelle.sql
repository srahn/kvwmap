BEGIN;


CREATE OR REPLACE FUNCTION kvwmap.add_layers_to_stelle(
    p_layer_ids integer[],
    p_stelle_id integer,
    p_filter text DEFAULT '',
    p_assign_default_values boolean DEFAULT false,
    p_privileg text DEFAULT 'default'
)
RETURNS boolean
LANGUAGE plpgsql
AS $function$
DECLARE
    v_inherited_ids integer[] := ARRAY[]::integer[];
    v_default_attr_ids integer[] := ARRAY[]::integer[];
BEGIN
    /*
     * 1. Ermitteln, für welche Layer Einstellungen geerbt werden.
     *
     * Ein vorhandener Kindstellen-Eintrag mit
     * use_parent_privileges = false verhindert die Vererbung.
     * Gibt es noch keinen Eintrag, gilt der bisherige Standard true.
     */
    IF NOT p_assign_default_values THEN
        SELECT COALESCE(
            array_agg(i.layer_id ORDER BY i.layer_id),
            ARRAY[]::integer[]
        )
        INTO v_inherited_ids
        FROM (
            SELECT DISTINCT ids.layer_id
            FROM unnest(
                COALESCE(p_layer_ids, ARRAY[]::integer[])
            ) AS ids(layer_id)
            WHERE COALESCE(
                (
                    SELECT ul.use_parent_privileges
                    FROM kvwmap.used_layer ul
                    WHERE ul.stelle_id = p_stelle_id
                      AND ul.layer_id = ids.layer_id
                ),
                true
            )
            AND EXISTS (
                SELECT 1
                FROM kvwmap.stellen_hierarchie sh
                JOIN kvwmap.used_layer parent_ul
                  ON parent_ul.stelle_id = sh.parent_id
                 AND parent_ul.layer_id = ids.layer_id
                WHERE sh.child_id = p_stelle_id
            )
        ) i;

        /*
         * Layer, die neu angelegt werden und keine anwendbare
         * Vererbung haben, benötigen später Standard-Attributrechte.
         */
        SELECT COALESCE(
            array_agg(i.layer_id ORDER BY i.layer_id),
            ARRAY[]::integer[]
        )
        INTO v_default_attr_ids
        FROM (
            SELECT DISTINCT ids.layer_id
            FROM unnest(
                COALESCE(p_layer_ids, ARRAY[]::integer[])
            ) AS ids(layer_id)
            JOIN kvwmap.layer l
              ON l.layer_id = ids.layer_id
            WHERE NOT EXISTS (
                SELECT 1
                FROM kvwmap.used_layer child_ul
                WHERE child_ul.stelle_id = p_stelle_id
                  AND child_ul.layer_id = ids.layer_id
            )
            AND NOT (ids.layer_id = ANY(v_inherited_ids))
        ) i;

        /*
         * 2. Geerbte Layer-Einstellungen übernehmen.
         *
         * Bei mehreren Eltern wird die Elternstelle mit der
         * kleinsten parent_id gewählt. Attributrechte werden
         * weiter unten unabhängig davon aggregiert.
         */
        INSERT INTO kvwmap.used_layer (
            stelle_id,
            layer_id,
            queryable,
            use_geom,
            minscale,
            maxscale,
            symbolscale,
            offsite,
            transparency,
            template,
            header,
            footer,
            privileg,
            export_privileg,
            postlabelcache,
            requires,
            start_aktiv
        )
        SELECT
            p_stelle_id,
            src.layer_id,
            src.queryable,
            src.use_geom,
            src.minscale,
            src.maxscale,
            src.symbolscale,
            src.offsite,
            src.transparency,
            src.template,
            src.header,
            src.footer,
            src.privileg,
            src.export_privileg,
            src.postlabelcache,
            src.requires,
            src.start_aktiv
        FROM (
            SELECT
                parent_ul.*,
                row_number() OVER (
                    PARTITION BY parent_ul.layer_id
                    ORDER BY sh.parent_id
                ) AS rn
            FROM kvwmap.stellen_hierarchie sh
            JOIN kvwmap.used_layer parent_ul
              ON parent_ul.stelle_id = sh.parent_id
            WHERE sh.child_id = p_stelle_id
              AND parent_ul.layer_id = ANY(v_inherited_ids)
        ) src
        WHERE src.rn = 1
        ON CONFLICT (stelle_id, layer_id) DO UPDATE SET
            queryable      = EXCLUDED.queryable,
            use_geom       = EXCLUDED.use_geom,
            minscale       = EXCLUDED.minscale,
            maxscale       = EXCLUDED.maxscale,
            symbolscale    = EXCLUDED.symbolscale,
            offsite        = EXCLUDED.offsite,
            transparency   = EXCLUDED.transparency,
            template       = EXCLUDED.template,
            header         = EXCLUDED.header,
            footer         = EXCLUDED.footer,
            postlabelcache = EXCLUDED.postlabelcache,
            privileg       = EXCLUDED.privileg,
            export_privileg = EXCLUDED.export_privileg,
            requires       = EXCLUDED.requires,
            start_aktiv    = EXCLUDED.start_aktiv;
    END IF;

    /*
     * 3. Standardwerte einsetzen.
     *
     * assign_default_values = true:
     *   alle angegebenen Layer aktualisieren.
     *
     * Sonst:
     *   nur Layer ohne anwendbare Vererbung einfügen;
     *   vorhandene Kindstellen-Einträge bleiben unverändert.
     */
    IF p_assign_default_values THEN
        INSERT INTO kvwmap.used_layer (
            stelle_id,
            layer_id,
            queryable,
            use_geom,
            minscale,
            maxscale,
            symbolscale,
            offsite,
            transparency,
            template,
            header,
            footer,
            privileg,
            export_privileg,
            postlabelcache,
            requires,
            start_aktiv,
            filter
        )
        SELECT
            p_stelle_id,
            l.layer_id,
            l.queryable,
            l.use_geom,
            l.minscale,
            l.maxscale,
            l.symbolscale,
            l.offsite,
            l.transparency,
            l.template,
            NULL,
            NULL,
            CASE
                WHEN p_privileg = 'editable' THEN 1::smallint
                ELSE l.privileg
            END,
            l.export_privileg,
            l.postlabelcache,
            l.requires,
            false,
            p_filter
        FROM kvwmap.layer l
        WHERE l.layer_id = ANY(
            COALESCE(p_layer_ids, ARRAY[]::integer[])
        )
        ON CONFLICT (stelle_id, layer_id) DO UPDATE SET
            queryable      = EXCLUDED.queryable,
            use_geom       = EXCLUDED.use_geom,
            minscale       = EXCLUDED.minscale,
            maxscale       = EXCLUDED.maxscale,
            symbolscale    = EXCLUDED.symbolscale,
            offsite        = EXCLUDED.offsite,
            transparency   = EXCLUDED.transparency,
            template       = EXCLUDED.template,
            postlabelcache = EXCLUDED.postlabelcache,
            requires       = EXCLUDED.requires;
    ELSE
        INSERT INTO kvwmap.used_layer (
            stelle_id,
            layer_id,
            queryable,
            use_geom,
            minscale,
            maxscale,
            symbolscale,
            offsite,
            transparency,
            template,
            header,
            footer,
            privileg,
            export_privileg,
            postlabelcache,
            requires,
            start_aktiv,
            filter
        )
        SELECT
            p_stelle_id,
            l.layer_id,
            l.queryable,
            l.use_geom,
            l.minscale,
            l.maxscale,
            l.symbolscale,
            l.offsite,
            l.transparency,
            l.template,
            NULL,
            NULL,
            CASE
                WHEN p_privileg = 'editable' THEN 1::smallint
                ELSE l.privileg
            END,
            l.export_privileg,
            l.postlabelcache,
            l.requires,
            false,
            p_filter
        FROM kvwmap.layer l
        WHERE l.layer_id = ANY(
            COALESCE(p_layer_ids, ARRAY[]::integer[])
        )
          AND NOT (l.layer_id = ANY(v_inherited_ids))
        ON CONFLICT (stelle_id, layer_id) DO NOTHING;
    END IF;

    /*
     * 4. Geerbte Attributrechte aus ALLEN Elternstellen
     * aggregieren. MAX() liefert das höchste Recht sowie
     * den höchsten Tooltip-Wert.
     */
    IF NOT p_assign_default_values THEN
        INSERT INTO kvwmap.layer_attributes2stelle (
            layer_id,
            attributename,
            stelle_id,
            privileg,
            tooltip
        )
        SELECT
            parent_attr.layer_id,
            parent_attr.attributename,
            p_stelle_id,
            MAX(parent_attr.privileg),
            MAX(parent_attr.tooltip)
        FROM kvwmap.stellen_hierarchie sh
        JOIN kvwmap.layer_attributes2stelle parent_attr
          ON parent_attr.stelle_id = sh.parent_id
        WHERE sh.child_id = p_stelle_id
          AND parent_attr.layer_id = ANY(v_inherited_ids)
        GROUP BY
            parent_attr.layer_id,
            parent_attr.attributename
        ON CONFLICT (layer_id, attributename, stelle_id)
        DO UPDATE SET
            privileg = EXCLUDED.privileg,
            tooltip  = EXCLUDED.tooltip;

        /*
         * Lokale Attributrechte entfernen, wenn das Attribut
         * bei keiner Elternstelle mehr vorhanden ist.
         */
        DELETE FROM kvwmap.layer_attributes2stelle child_attr
        WHERE child_attr.stelle_id = p_stelle_id
          AND child_attr.layer_id = ANY(v_inherited_ids)
          AND NOT EXISTS (
              SELECT 1
              FROM kvwmap.stellen_hierarchie sh
              JOIN kvwmap.layer_attributes2stelle parent_attr
                ON parent_attr.stelle_id = sh.parent_id
              WHERE sh.child_id = p_stelle_id
                AND parent_attr.layer_id = child_attr.layer_id
                AND parent_attr.attributename =
                    child_attr.attributename
          );

        /*
         * Für neu zugeordnete Layer ohne anwendbare Vererbung
         * Standard-Attributrechte einfügen.
         */
        INSERT INTO kvwmap.layer_attributes2stelle (
            layer_id,
            attributename,
            stelle_id,
            privileg,
            tooltip
        )
        SELECT
            la.layer_id,
            la.name,
            p_stelle_id,
            CASE
                WHEN p_privileg = 'editable' THEN 1::smallint
                ELSE la.privileg
            END,
            la.query_tooltip
        FROM kvwmap.layer_attributes la
        WHERE la.layer_id = ANY(v_default_attr_ids)
          AND la.privileg IS NOT NULL
        ON CONFLICT (layer_id, attributename, stelle_id)
        DO NOTHING;
    END IF;

    RETURN true;
END;
$function$;


COMMIT;
