BEGIN;

CREATE TABLE lenris.lea_recherchen (
	id serial4 NOT NULL,
	lea_id int4 not NULL,
	user_id int4 not NULL,
	stelle_id int4 not null,
	zeit timestamp,
	search_params jsonb,
	CONSTRAINT lea_recherchen_pkey PRIMARY KEY (id)
);

CREATE TABLE lenris.lea_recherche_ergebnisse (
	id serial4 NOT NULL,
	recherche_id int4 not NULL,
	client_id int2 not NULL,
	client_nachweis_id int4 not null,
	flurid int4 NULL,
	blattnummer varchar NULL,
	vermstelle int4 NULL,
	stammnr varchar(15) NULL,
	CONSTRAINT lea_recherche_ergebnisse_pkey PRIMARY KEY (id)
);

COMMIT;
