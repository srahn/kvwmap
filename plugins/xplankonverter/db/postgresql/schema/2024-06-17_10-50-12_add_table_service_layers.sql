BEGIN;
CREATE TABLE IF NOT EXISTS xplankonverter.service_layers
(
    layer_id integer NOT NULL,
    planart character varying COLLATE pg_catalog."default" NOT NULL,
    reihenfolge integer,
    CONSTRAINT service_layers_pkey PRIMARY KEY (layer_id)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE xplankonverter.service_layers
    OWNER to kvwmap;

COMMENT ON TABLE xplankonverter.service_layers
    IS 'Layers belonging to one type of planart for ows service generation.';
COMMIT;
