BEGIN;

DROP TABLE jagdkataster.jagdpaechter;

CREATE TABLE jagdkataster.jagdpaechter(
	id integer NOT NULL,
	anrede character varying(20),
	akad_grad character varying(20),
	nachname character varying(80),
	vorname character varying(80),
	geburtstag character varying(20),
	geburtsort character varying(50),
	strasse character varying(60),
	hausnr character varying(50),
	plz character varying(80),
	ort character varying(50),
	ortsteil character varying(50),
	telefongesch character varying(100),
	telefonpriv character varying(100),
	telefonmobil character varying(50),
	fax character varying(100),
	emailgesch character varying(150),
	emailpriv character varying(150)
)
WITH(OIDS=TRUE);

COMMIT;
