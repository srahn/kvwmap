BEGIN;

DO $$

DECLARE
    r record;
BEGIN
    FOR r IN
        SELECT
            format(
                'UPDATE kvwmap.layer_attributes
                 SET "options" = replace("options", %L, %L)
                 WHERE layer_id = %s
                   AND "name" = %L',
                quote_literal(concat('<requires>', m.requires_value[1], '</requires>')),
                concat('<requires>', m.requires_value[1], '</requires>'),
                la.layer_id,
                la."name"
            ) AS q
        FROM
            kvwmap.layer_attributes la
            CROSS JOIN LATERAL regexp_matches(
                la."options",
                '<requires>(\w+)</requires>',
                'g'
            ) AS m(requires_value)
        WHERE
            la."options" ~ '''<requires>'
    LOOP
        --RAISE NOTICE 'Führe aus: %', r.q;
        EXECUTE r.q;
    END LOOP;
END $$;

COMMIT;
