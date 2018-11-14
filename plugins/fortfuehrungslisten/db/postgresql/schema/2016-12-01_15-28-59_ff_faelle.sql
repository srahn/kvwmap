CREATE TABLE fortfuehrungslisten.ff_faelle (
  id serial NOT NULL,
  ff_auftrag_id integer NOT NULL,
  fortfuehrungsfallnummer integer NOT NULL,
  laufendenummer character(9) NOT NULL,
  ueberschriftimfortfuehrungsnachweis character(6),
  zeigtaufaltesflurstueck character varying[],
  zeigtaufneuesflurstueck character varying[],
  anlassart character varying,
  anlassarten character varying[],
  CONSTRAINT ff_faelle_pkey PRIMARY KEY (id)
) WITH ( OIDS=TRUE );