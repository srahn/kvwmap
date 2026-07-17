BEGIN;

ALTER TABLE kvwmap.styles ALTER COLUMN outlinecolor TYPE varchar(50) USING outlinecolor::varchar(50);

ALTER TABLE kvwmap.labels ALTER COLUMN color TYPE varchar(50) USING color::varchar(50);

ALTER TABLE kvwmap.labels ALTER COLUMN outlinecolor TYPE varchar(50) USING outlinecolor::varchar(50);

ALTER TABLE kvwmap.labels ALTER COLUMN backgroundcolor TYPE varchar(50) USING backgroundcolor::varchar(50);

COMMIT;
