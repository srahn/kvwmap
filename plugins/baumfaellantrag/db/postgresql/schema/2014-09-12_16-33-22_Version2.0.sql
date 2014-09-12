
BEGIN;

CREATE SCHEMA baumfaellung;

SET search_path = baumfaellung, public;

CREATE TABLE antraege
(
  id serial NOT NULL,
  nr bigint,
  surname character varying(255),
  forename character varying(255),
  streetname character varying(255),
  streetno character varying(10),
  place character varying(255),
  postcode character varying(5),
  phone character varying(20),
  fax character varying(20),
  email character varying(255),
  ownerinfo character varying(100),
  mandatereference character varying(255),
  locationsketchreference character varying(255),
  reason text,
  cadastre_stateid integer,
  cadastre_districtid integer,
  cadastre_municipalityid integer,
  cadastre_municipalityname character varying(255),
  cadastre_boundaryid integer,
  cadastre_boundaryname character varying(255),
  cadastre_sectionid integer,
  cadastre_parcelid character varying(25),
  cadastre_parcelno character varying(10),
  authority_municipalitynr integer,
  authority_municipalityname character varying(255),
  authority_districtnr integer,
  authority_email character varying(255),
  authority_contactperson character varying(255),
  authority_processingtime character varying(255),
  statute_id integer,
  statute_name character varying(255),
  statute_type character varying(255),
  statute_alloweddiameter integer,
  wood_species character varying(255),
  trunk_circumference integer,
  crown_diameter integer,
  created_at timestamp without time zone NOT NULL DEFAULT now(),
  npa_authenticated boolean NOT NULL DEFAULT false,
  decision_at timestamp without time zone,
  approved boolean NOT NULL DEFAULT false,
  decision text,
  provider_id character varying(100),
  CONSTRAINT antraege_pkey_id PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
SELECT AddGeometryColumn('baumfaellung', 'antraege', 'tree_geometry', 4326,'POINT', 2);

COMMIT;