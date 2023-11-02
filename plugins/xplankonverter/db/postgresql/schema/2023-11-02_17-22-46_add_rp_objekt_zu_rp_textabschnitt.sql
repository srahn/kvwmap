BEGIN;
-- Table: xplan_gml.rp_objekt_zu_rp_textabschnitt

-- DROP TABLE xplan_gml.rp_objekt_zu_rp_textabschnitt;

CREATE TABLE IF NOT EXISTS xplan_gml.rp_objekt_zu_rp_textabschnitt
(
    rp_objekt_gml_id uuid NOT NULL,
    rp_textabschnitt_gml_id text COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT rp_objekt_zu_rp_textabschnitt_pkey PRIMARY KEY (rp_objekt_gml_id, rp_textabschnitt_gml_id)
)

TABLESPACE pg_default;

ALTER TABLE xplan_gml.rp_objekt_zu_rp_textabschnitt
    OWNER to postgres;

COMMENT ON TABLE xplan_gml.rp_objekt_zu_rp_textabschnitt
    IS 'Association RP_Objekt _zu_ RP_TextAbschnitt';

COMMENT ON COLUMN xplan_gml.rp_objekt_zu_rp_textabschnitt.rp_objekt_gml_id
    IS 'refTextInhalt';

COMMENT ON COLUMN xplan_gml.rp_objekt_zu_rp_textabschnitt.rp_textabschnitt_gml_id
    IS '<undefined>';


COMMIT;
