BEGIN;


CREATE TABLE bauleitplanung.f_plan_stammdaten
(
  plan_id serial NOT NULL,
  gkz integer,
  art character varying(50),
  pl_nr character varying(50),
  gemeinde_alt character varying(50),
  bezeichnung character varying(100),
  aktuell character varying(1),
  lfd_rok_nr character varying(50),
  aktenzeichen character varying(255),
  datumeing date,
  datumzust date,
  datumabl date,
  datumgenehm date,
  datumbeka date,
  datumaufh date,
  erteilteaufl text,
  ert_hinweis text,
  ert_bemerkungen text,
  CONSTRAINT f_plan_stammdaten_pkey PRIMARY KEY (plan_id )
)
WITH (
  OIDS=TRUE
);

	
CREATE TABLE bauleitplanung.f_plan_gebiete
(
  plan_id integer NOT NULL,
  gebietstyp integer NOT NULL,
  flaeche numeric(18,2),
  kap_gemziel integer,
  kap_nachstell integer
)
WITH (
  OIDS=TRUE
);

CREATE TABLE bauleitplanung.f_plan_sondergebiete
(
  plan_id integer,
  gebietstyp integer NOT NULL,
  flaeche numeric(18,2),
  kap_gemziel integer,
  kap_nachstell integer,
  bemerkungen text
)
WITH (
  OIDS=TRUE
);

CREATE TABLE bauleitplanung.gebietstypen_fnp
(
  id serial NOT NULL,
  typ character varying(100),
  art boolean,
  einheit character varying(30)
)
WITH (
  OIDS=TRUE
);
		

COMMIT;
