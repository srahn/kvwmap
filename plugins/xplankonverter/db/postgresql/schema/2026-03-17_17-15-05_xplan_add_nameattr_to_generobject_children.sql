BEGIN;
DROP TYPE IF EXISTS xplan_gml.xp_stringattribut;

CREATE TYPE xplan_gml.xp_stringattribut AS
(
	name character varying,
	wert character varying
);

ALTER TYPE xplan_gml.xp_stringattribut
    OWNER TO postgres;

COMMENT ON TYPE xplan_gml.xp_stringattribut
    IS 'Alias: "XP_StringAttribut",  1';


DROP TYPE IF EXISTS xplan_gml.xp_urlattribut;

CREATE TYPE xplan_gml.xp_urlattribut AS
(
	name character varying,
	wert character varying
);

ALTER TYPE xplan_gml.xp_urlattribut
    OWNER TO postgres;

COMMENT ON TYPE xplan_gml.xp_urlattribut
    IS 'Alias: "XP_URLAttribut",  1';

DROP TYPE IF EXISTS xplan_gml.xp_integerattribut;

CREATE TYPE xplan_gml.xp_integerattribut AS
(
	name character varying,
	wert integer
);

ALTER TYPE xplan_gml.xp_integerattribut
    OWNER TO postgres;

COMMENT ON TYPE xplan_gml.xp_integerattribut
    IS 'Alias: "XP_IntegerAttribut",  1';

DROP TYPE IF EXISTS xplan_gml.xp_doubleattribut;

CREATE TYPE xplan_gml.xp_doubleattribut AS
(
	name character varying,
	wert double precision
);

ALTER TYPE xplan_gml.xp_doubleattribut
    OWNER TO postgres;

COMMENT ON TYPE xplan_gml.xp_doubleattribut
    IS 'Alias: "XP_DoubleAttribut",  1';

DROP TYPE IF EXISTS xplan_gml.xp_datumattribut;

CREATE TYPE xplan_gml.xp_datumattribut AS
(
	name character varying,
	wert date
);

ALTER TYPE xplan_gml.xp_datumattribut
    OWNER TO postgres;

COMMENT ON TYPE xplan_gml.xp_datumattribut
    IS 'Alias: "XP_DatumAttribut",  1';

COMMIT;
