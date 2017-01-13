CREATE TABLE fortfuehrungslisten.ff_auftraege
(
  id serial NOT NULL,
  jahr integer NOT NULL DEFAULT date_part('year'::text, now()),
  gemkgnr integer NOT NULL,
  lfdnr integer,
  antragsnr integer,
  bemerkung text,
  auftragsdatei character varying,
  datumderausgabe date,
  profilkennung character varying,
  auftragsnummer integer,
  impliziteloeschungderreservierung integer,
  verarbeitungsart integer,
  geometriebehandlung boolean,
  mittemporaeremarbeitsbereich boolean,
  mitobjektenimfortfuehrungsgebiet boolean,
  mitfortfuehrungsnachweis boolean,
  created_at timestamp without time zone NOT NULL DEFAULT now(),
  updated_at timestamp without time zone NOT NULL DEFAULT now(),
  user_name character varying NOT NULL,
  gebaeude character varying,
  CONSTRAINT ff_auftrag_pkey PRIMARY KEY (id)
) WITH ( OIDS=TRUE);