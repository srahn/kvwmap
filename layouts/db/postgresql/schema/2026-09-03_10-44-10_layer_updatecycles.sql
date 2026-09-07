BEGIN;

create table kvwmap.layer_updatecycles (
	id serial PRIMARY KEY,
	updatecycle varchar(100) NOT NULL UNIQUE
);

insert into kvwmap.layer_updatecycles (updatecycle)

WITH predefined(updatecycle, sort_order) AS (
    VALUES
        ('kontinuierlich (mehrmals täglich)', 1),
        ('täglich',                           2),
        ('wöchentlich',                       3),
        ('14-tägig',                          4),
        ('monatlich',                         5),
        ('vierteljährlich',                   6),
        ('halbjährlich',                      7),
        ('jährlich',                          8),
        ('zweijährlich',                      9),
        ('periodisch (regelmäßig)',          10),
        ('bei Bedarf',                        11),
        ('unregelmäßig',                      12),
        ('nicht geplant',                     13),
        ('unbekannt',                         14)
)
SELECT updatecycle
FROM (
    SELECT updatecycle, sort_order
    FROM predefined

    UNION ALL

    SELECT DISTINCT
        l.updatecycle,
        1000
    FROM kvwmap.layer l
    WHERE l.updatecycle IS NOT null and l.updatecycle != ''
      AND NOT EXISTS (
          SELECT 1
          FROM predefined p
          WHERE p.updatecycle = l.updatecycle
      )
) x
ORDER BY sort_order, updatecycle;

UPDATE kvwmap.layer l
SET updatecycle = u.id::varchar
FROM kvwmap.layer_updatecycles u
WHERE l.updatecycle = u.updatecycle;

UPDATE kvwmap.layer l
SET updatecycle = null
where updatecycle = '';

ALTER TABLE kvwmap.layer
ALTER COLUMN updatecycle DROP DEFAULT;

-- varchar -> integer
ALTER TABLE kvwmap.layer
ALTER COLUMN updatecycle TYPE integer
USING updatecycle::integer;

-- FK
ALTER TABLE kvwmap.layer
ADD CONSTRAINT layer_updatecycle_fk
FOREIGN KEY (updatecycle)
REFERENCES kvwmap.layer_updatecycles(id);

COMMIT;
