--#####################
--# Metadatentabellen #
--#####################
--#2005-11-29_pk
CREATE TABLE md_metadata
(
  id serial NOT NULL,
  mdfileid varchar(255) NOT NULL,
  mdlang varchar(25) NOT NULL DEFAULT 'de'::character varying,
  mddatest date NOT NULL DEFAULT ('now'::text)::date,
  mdcontact int4,
  spatrepinfo int4,
  refsysinfo int4,
  mdextinfo int4,
  dataidinfo int4,
  continfo int4,
  distinfo int4,
  idtype text,
  restitle varchar(256),
  idabs text,
  tpcat varchar(255),
  reseddate date,
  validfrom date,
  validtill date,
  westbl varchar(25),
  eastbl varchar(25),
  southbl varchar(25),
  northbl varchar(25),
  identcode text,
  rporgname text,
  postcode int4,
  city text,
  delpoint text,
  adminarea text,
  country text,
  linkage text,
  servicetype text,
  spatialtype text,
  serviceversion varchar(255),
  vector_scale int4,
  databinding bool,
  solution varchar(255),
  status text,
  onlinelinke text,
  cyclus text,
  sparefsystem text,
  sformat text,
  sformatversion text,
  download text,
  onlinelink text,
  accessrights text,
  datalang varchar(25),
  CONSTRAINT md_metadata_pkey PRIMARY KEY (id)
) 
WITH OIDS;
COMMENT ON TABLE md_metadata IS 'Metadatendokumente';

SELECT AddGeometryColumn('public', 'md_metadata','the_geom',25833,'POLYGON', 2);
CREATE INDEX md_metadata_the_geom_gist ON md_metadata USING GIST (the_geom);

--# Diese Tabellen sind für ein normalisiertes Datenbankmodell für Metadaten geplant
--# und werden noch nicht verwendet 
CREATE TABLE md_identification
(
  id serial NOT NULL,
  idcitation int4 NOT NULL,
  idabs text,
  idpurp text,
  descKeysTheme varchar(255)[],
  descKeysPlace varchar(255)[],  
  idtype varchar(25)
) 
WITH OIDS;
COMMENT ON TABLE md_identification IS 'Identifikations Informationen';

CREATE TABLE md_dataidentification
(
  id serial NOT NULL,
  datalang varchar(25),
  tpcat text NOT NULL
) 
WITH OIDS;
COMMENT ON TABLE md_dataidentification IS 'Datenidentifizierungs Informationen';

CREATE TABLE md_ci_citation
(
  id serial NOT NULL,
  restitle varchar(255),
  resrefdate int4,
  reseddate int4,
  citrespparty int4
) 
WITH OIDS;
COMMENT ON TABLE md_ci_citation IS 'Quellenangaben und Verantwortliche Einrichtung oder Person';

CREATE TABLE md_ci_responsibleparty
(
  id serial NOT NULL,
  rporgname varchar(255),
  rpcntinfo int4
) 
WITH OIDS;
COMMENT ON TABLE md_ci_responsibleparty IS 'Verantwortliche Einrichtung oder Person';

--# Hinzufügen der Tabelle md_keywords
--#2005-11-29_pk
CREATE TABLE md_keywords
(
  id serial NOT NULL,
  keyword varchar(255) NOT NULL,
  keytyp varchar(25),
  thesaname int4,
  CONSTRAINT md_keywords_pkey PRIMARY KEY (id)
) 
WITHOUT OIDS;
COMMENT ON TABLE md_keywords IS 'Beschreibende Schlagwörter';

--# Hinzufügen der Tabelle mn_keywords2metadata für die Verknüpfung zwischen Metadaten und Schlagwörtern
--#2005-11-29_pk
CREATE TABLE md_keywords2metadata
(
  keyword_id int4 NOT NULL,
  metadata_id int4 NOT NULL,
  CONSTRAINT md_keywords2metadata_pkey PRIMARY KEY (keyword_id, metadata_id),
  CONSTRAINT "fkKWD" FOREIGN KEY (keyword_id) REFERENCES md_keywords (id) ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT "fkMD" FOREIGN KEY (metadata_id) REFERENCES md_metadata (id) ON UPDATE NO ACTION ON DELETE CASCADE
) 
WITHOUT OIDS;

