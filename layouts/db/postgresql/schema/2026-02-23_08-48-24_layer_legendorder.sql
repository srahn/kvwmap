BEGIN;

UPDATE kvwmap.layer l
SET legendorder = sub.new_legendorder
FROM (
    SELECT 
        layer_id,  
        row_number() OVER (
            PARTITION BY gruppe
            ORDER BY drawingorder DESC
        ) AS new_legendorder
    FROM kvwmap.layer
) sub
WHERE
	l.legendorder IS NULL AND 
	l.layer_id = sub.layer_id;

COMMIT;
