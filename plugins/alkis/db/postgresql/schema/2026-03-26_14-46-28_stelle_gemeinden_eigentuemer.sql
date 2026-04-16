BEGIN;

CREATE TABLE kvwmap.stelle_gemeinden_eigentuemer (
	stelle_id int4 DEFAULT 0 NOT NULL,
	gemeinde_id int4 DEFAULT 0 NOT NULL,
	gemarkung int4 NULL,
	flur int2 NULL,
	flurstueck varchar(10) DEFAULT NULL::character varying NULL,
	CONSTRAINT stelle_gemeinden_eigentuemer_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON DELETE CASCADE ON UPDATE CASCADE
);

COMMIT;
