BEGIN;

SET search_path = xplan_uml, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 807 (class 1259 OID 877586)
-- Name: association_classes; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE association_classes (
    xmi_id character varying(255),
    model_id integer,
    visibility character varying,
    "isLeaf" boolean,
    "isAbstract" boolean,
    "isActive" boolean,
    package_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    "isRoot" boolean,
    id integer NOT NULL
);


--
-- TOC entry 808 (class 1259 OID 877592)
-- Name: association_classes_testid_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE association_classes_testid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12029 (class 0 OID 0)
-- Dependencies: 808
-- Name: association_classes_testid_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE association_classes_testid_seq OWNED BY association_classes.id;


--
-- TOC entry 809 (class 1259 OID 877597)
-- Name: association_ends; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE association_ends (
    id integer NOT NULL,
    assoc_id integer,
    name character varying,
    visibility character varying,
    aggregation character varying,
    "isOrdered" boolean,
    "isNavigable" boolean,
    type character varying,
    created_at timestamp without time zone,
    multiplicity_range_lower character varying,
    multiplicity_range_upper character varying,
    "targetScope" character varying,
    changeability character varying,
    ordering character varying,
    participant character varying
);


--
-- TOC entry 810 (class 1259 OID 877603)
-- Name: association_ends_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE association_ends_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12030 (class 0 OID 0)
-- Dependencies: 810
-- Name: association_ends_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE association_ends_id_seq OWNED BY association_ends.id;


--
-- TOC entry 825 (class 1259 OID 877685)
-- Name: uml_classes; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE uml_classes (
    xmi_id character varying(255),
    name character varying(255),
    visibility character varying(255),
    "isSpecification" boolean,
    "isRoot" boolean,
    "isLeaf" boolean,
    "isActive" boolean,
    package_id integer,
    model_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    "isAbstract" boolean,
    id integer NOT NULL,
    stereotype_id character varying,
    general_id character varying
);


--
-- TOC entry 834 (class 1259 OID 877756)
-- Name: class_assoziations; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW class_assoziations AS
 WITH assoc_ends AS (
         WITH namea AS (
                 SELECT sub.name AS namea,
                    sub.assoc_id,
                    sub.visibility AS visibilitya,
                    sub.aggregation AS aggregationa,
                    sub."isOrdered" AS isordereda,
                    sub."isNavigable" AS isnavigablea,
                    sub.multiplicity_range_lower AS multiplicity_range_lowera,
                    sub.multiplicity_range_upper AS multiplicity_range_uppera,
                    sub."targetScope" AS targetscopea,
                    sub.changeability AS changeabilitya,
                    sub.ordering AS orderinga,
                    sub.participant AS participanta
                   FROM ( SELECT row_number() OVER () AS num,
                            association_ends.name,
                            association_ends.assoc_id,
                            association_ends.visibility,
                            association_ends.aggregation,
                            association_ends."isOrdered",
                            association_ends."isNavigable",
                            association_ends.multiplicity_range_lower,
                            association_ends.multiplicity_range_upper,
                            association_ends."targetScope",
                            association_ends.changeability,
                            association_ends.ordering,
                            association_ends.participant
                           FROM association_ends) sub
                  WHERE (mod(sub.num, (2)::bigint) = 0)
                ), nameb AS (
                 SELECT sub.name AS nameb,
                    sub.assoc_id,
                    sub.visibility AS visibilityb,
                    sub.aggregation AS aggregationb,
                    sub."isOrdered" AS isorderedb,
                    sub."isNavigable" AS isnavigableb,
                    sub.multiplicity_range_lower AS multiplicity_range_lowerb,
                    sub.multiplicity_range_upper AS multiplicity_range_upperb,
                    sub."targetScope" AS targetscopeb,
                    sub.changeability AS changeabilityb,
                    sub.ordering AS orderingb,
                    sub.participant AS participantb
                   FROM ( SELECT row_number() OVER () AS num,
                            association_ends.name,
                            association_ends.assoc_id,
                            association_ends.visibility,
                            association_ends.aggregation,
                            association_ends."isOrdered",
                            association_ends."isNavigable",
                            association_ends.multiplicity_range_lower,
                            association_ends.multiplicity_range_upper,
                            association_ends."targetScope",
                            association_ends.changeability,
                            association_ends.ordering,
                            association_ends.participant
                           FROM association_ends) sub
                  WHERE (mod(sub.num, (2)::bigint) = 1)
                )
         SELECT namea.namea,
            namea.assoc_id,
            namea.visibilitya,
            namea.aggregationa,
            namea.isordereda,
            namea.isnavigablea,
            namea.multiplicity_range_lowera,
            namea.multiplicity_range_uppera,
            namea.targetscopea,
            namea.changeabilitya,
            namea.orderinga,
            namea.participanta,
            nameb.nameb,
            nameb.assoc_id,
            nameb.visibilityb,
            nameb.aggregationb,
            nameb.isorderedb,
            nameb.isnavigableb,
            nameb.multiplicity_range_lowerb,
            nameb.multiplicity_range_upperb,
            nameb.targetscopeb,
            nameb.changeabilityb,
            nameb.orderingb,
            nameb.participantb
           FROM (namea
             JOIN nameb ON ((namea.assoc_id = nameb.assoc_id)))
        )
 SELECT assoc_ends.visibilitya AS class1_visibility,
    assoc_ends.aggregationa AS class1_aggregation,
    assoc_ends.isordereda AS class1_isordered,
    assoc_ends.isnavigablea AS class1_isnavigable,
    assoc_ends.multiplicity_range_lowera AS class1_multiplicity_range_lower,
    assoc_ends.multiplicity_range_uppera AS class1_multiplicity_range_upper,
    assoc_ends.targetscopea AS class1_targetscope,
    assoc_ends.changeabilitya AS class1_changeability,
    assoc_ends.orderinga AS class1_ordering,
    assoc_ends.namea AS class1_assoc_name,
    uc1.name AS class1,
    uc2.name AS class2,
    assoc_ends.nameb AS class2_assoc_name,
    assoc_ends.visibilityb AS class2_visibility,
    assoc_ends.aggregationb AS class2_aggregation,
    assoc_ends.isorderedb AS class2_isordered,
    assoc_ends.isnavigableb AS class2_isnavigable,
    assoc_ends.multiplicity_range_lowerb AS class2_multiplicity_range_lower,
    assoc_ends.multiplicity_range_upperb AS class2_multiplicity_range_upper,
    assoc_ends.targetscopeb AS class2_targetscope,
    assoc_ends.changeabilityb AS class2_changeability,
    assoc_ends.orderingb AS class2_ordering
   FROM ((assoc_ends assoc_ends(namea, assoc_id, visibilitya, aggregationa, isordereda, isnavigablea, multiplicity_range_lowera, multiplicity_range_uppera, targetscopea, changeabilitya, orderinga, participanta, nameb, assoc_id_1, visibilityb, aggregationb, isorderedb, isnavigableb, multiplicity_range_lowerb, multiplicity_range_upperb, targetscopeb, changeabilityb, orderingb, participantb)
     LEFT JOIN uml_classes uc1 ON (((assoc_ends.participanta)::text = (uc1.xmi_id)::text)))
     LEFT JOIN uml_classes uc2 ON (((assoc_ends.participantb)::text = (uc2.xmi_id)::text)));


--
-- TOC entry 811 (class 1259 OID 877608)
-- Name: class_generalizations; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE class_generalizations (
    xmi_id character varying(255),
    name character varying(255),
    model_id integer,
    "isSpecification" boolean,
    package_id integer,
    parent_id character varying,
    child_id character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    id integer NOT NULL
);


--
-- TOC entry 830 (class 1259 OID 877716)
-- Name: class_children; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW class_children AS
 SELECT uml_classes.name AS parent_name,
    class_generalizations.child_id
   FROM (uml_classes
     LEFT JOIN class_generalizations ON (((class_generalizations.parent_id)::text = (uml_classes.xmi_id)::text)));


--
-- TOC entry 812 (class 1259 OID 877614)
-- Name: class_generalizations_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE class_generalizations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12031 (class 0 OID 0)
-- Dependencies: 812
-- Name: class_generalizations_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE class_generalizations_id_seq OWNED BY class_generalizations.id;


--
-- TOC entry 823 (class 1259 OID 877674)
-- Name: uml_attributes; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE uml_attributes (
    xmi_id character varying(255),
    name character varying(255),
    model_id integer,
    uml_class_id integer,
    visibility character varying(255),
    "isSpecification" boolean,
    "ownerSpace" character varying(255),
    changeability character varying(255),
    "targetScope" character varying(255),
    ordering character varying(255),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    id integer NOT NULL,
    datatype character varying,
    classifier character varying,
    multiplicity_id character varying,
    multiplicity_range_id character varying,
    multiplicity_range_lower character varying,
    multiplicity_range_upper character varying,
    initialvalue_id character varying,
    initialvalue_body character varying
);


--
-- TOC entry 829 (class 1259 OID 877707)
-- Name: classes_attributes; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW classes_attributes AS
 SELECT uml_classes.name AS class_name,
    uml_attributes.name AS atribute_name,
    uml_classes.xmi_id AS class_xmi_id,
    uml_classes.id AS class_id,
    uml_classes.package_id,
    uml_attributes.xmi_id AS attribute_xmi_id,
    uml_attributes.id AS attribute_id,
    uml_attributes.datatype AS attribute_datatype,
    uml_attributes.classifier AS attribute_classifier
   FROM (uml_classes
     LEFT JOIN uml_attributes ON ((uml_attributes.uml_class_id = uml_classes.id)));


--
-- TOC entry 813 (class 1259 OID 877619)
-- Name: datatypes; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE datatypes (
    id integer NOT NULL,
    xmi_id character varying,
    name character varying,
    "isRoot" boolean,
    "isLeaf" boolean,
    "isAbstract" boolean,
    visibility character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- TOC entry 836 (class 1259 OID 877812)
-- Name: classes_attributes_types; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW classes_attributes_types AS
 SELECT t1.class_name,
    t1.atribute_name,
    t1.class_xmi_id,
    t1.class_id,
    t1.attribute_xmi_id,
    t1.attribute_id,
    t1.package_id,
    ((COALESCE(t2.name, ''::character varying))::text || (COALESCE(t4.name, ''::character varying))::text) AS datatype,
    t1.attribute_datatype,
    t3.name AS classifier,
    t1.attribute_classifier
   FROM (((classes_attributes t1
     LEFT JOIN datatypes t2 ON (((t1.attribute_datatype)::text = (t2.xmi_id)::text)))
     LEFT JOIN datatypes t4 ON (((t1.attribute_classifier)::text = (t4.xmi_id)::text)))
     LEFT JOIN uml_classes t3 ON (((t1.attribute_classifier)::text = (t3.xmi_id)::text)));


--
-- TOC entry 827 (class 1259 OID 877696)
-- Name: comments; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE comments (
    id integer NOT NULL,
    xmi_id character varying,
    "isSpecification" boolean,
    body character varying,
    class_id character varying,
    package_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- TOC entry 831 (class 1259 OID 877720)
-- Name: classes_comments; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW classes_comments AS
 SELECT comments.id AS comment_id,
    comments.xmi_id AS comment_xmi_id,
    comments."isSpecification" AS "comment_isSpecification",
    comments.body,
    comments.class_id,
    uml_classes.xmi_id,
    uml_classes.name,
    uml_classes.visibility,
    uml_classes."isSpecification",
    uml_classes."isRoot",
    uml_classes."isLeaf",
    uml_classes."isActive",
    uml_classes.package_id,
    uml_classes.model_id,
    uml_classes.created_at,
    uml_classes.updated_at,
    uml_classes."isAbstract",
    uml_classes.id,
    uml_classes.stereotype_id,
    uml_classes.general_id
   FROM (uml_classes
     LEFT JOIN comments ON (((comments.class_id)::text = (uml_classes.xmi_id)::text)));


--
-- TOC entry 832 (class 1259 OID 877725)
-- Name: generalizations; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW generalizations AS
 SELECT class_children.parent_name,
    classes_comments.name,
    class_children.child_id,
    classes_comments.package_id,
    classes_comments.body AS comment
   FROM (classes_comments
     LEFT JOIN class_children ON (((class_children.child_id)::text = (classes_comments.xmi_id)::text)));


--
-- TOC entry 837 (class 1259 OID 877817)
-- Name: classes_attributes_types_gen; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW classes_attributes_types_gen AS
 SELECT generalizations.parent_name AS gen_name,
    classes_attributes_types.class_name,
    generalizations.child_id,
    generalizations.comment,
    classes_attributes_types.atribute_name,
    classes_attributes_types.class_xmi_id,
    classes_attributes_types.class_id,
    classes_attributes_types.attribute_xmi_id,
    classes_attributes_types.attribute_id,
    classes_attributes_types.package_id,
    classes_attributes_types.datatype,
    classes_attributes_types.attribute_datatype,
    classes_attributes_types.classifier,
    classes_attributes_types.attribute_classifier
   FROM (classes_attributes_types
     LEFT JOIN generalizations ON (((classes_attributes_types.class_xmi_id)::text = (generalizations.child_id)::text)));


--
-- TOC entry 817 (class 1259 OID 877641)
-- Name: stereotypes; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE stereotypes (
    xmi_id character varying(255),
    name character varying(255),
    model_id integer,
    "isSpecification" boolean,
    "isRoot" boolean,
    "isLeaf" boolean,
    "isAbstract" boolean,
    "baseClass" character varying(255),
    stereotype_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    id integer NOT NULL
);


--
-- TOC entry 835 (class 1259 OID 877761)
-- Name: classes_with_attributes; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW classes_with_attributes AS
 SELECT c.id AS class_id,
    c.name AS class_name,
    cs.name AS class_sterotype,
    a.name AS attribute_name,
        CASE
            WHEN ((a.datatype)::text <> ''::text) THEN 'datatype'::text
            ELSE 'classifier'::text
        END AS datatype_type,
        CASE
            WHEN ((a.datatype)::text <> ''::text) THEN ad.name
            ELSE ac.name
        END AS attribute_datatype,
        CASE
            WHEN ((a.datatype)::text <> ''::text) THEN adcs.name
            ELSE acs.name
        END AS attribute_stereotype,
    a.multiplicity_range_lower,
    a.multiplicity_range_upper
   FROM (((((((uml_classes c
     JOIN stereotypes cs ON (((c.stereotype_id)::text = (cs.xmi_id)::text)))
     LEFT JOIN uml_attributes a ON ((c.id = a.uml_class_id)))
     LEFT JOIN datatypes ad ON (((a.datatype)::text = (ad.xmi_id)::text)))
     LEFT JOIN uml_classes ac ON (((a.classifier)::text = (ac.xmi_id)::text)))
     LEFT JOIN stereotypes acs ON (((ac.stereotype_id)::text = (acs.xmi_id)::text)))
     LEFT JOIN uml_classes adc ON (((ad.name)::text = (adc.name)::text)))
     LEFT JOIN stereotypes adcs ON (((adc.stereotype_id)::text = (adcs.xmi_id)::text)));


--
-- TOC entry 828 (class 1259 OID 877702)
-- Name: comments_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12032 (class 0 OID 0)
-- Dependencies: 828
-- Name: comments_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE comments_id_seq OWNED BY comments.id;


--
-- TOC entry 814 (class 1259 OID 877625)
-- Name: datatypes_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE datatypes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12033 (class 0 OID 0)
-- Dependencies: 814
-- Name: datatypes_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE datatypes_id_seq OWNED BY datatypes.id;


--
-- TOC entry 815 (class 1259 OID 877630)
-- Name: packages; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE packages (
    xmi_id character varying(255),
    name character varying(255),
    visibility character varying(255),
    "isSpecification" boolean,
    "isRoot" boolean,
    "isLeaf" boolean,
    "isAbstract" boolean,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    model_id integer,
    parent_package_id integer,
    id integer NOT NULL,
    stereotype_id character varying
);


--
-- TOC entry 833 (class 1259 OID 877733)
-- Name: packages_parent-name; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW "packages_parent-name" AS
 WITH RECURSIVE subpackage AS (
         SELECT p.xmi_id,
            p.name,
            p.visibility,
            p."isSpecification",
            p."isRoot",
            p."isLeaf",
            p."isAbstract",
            p.created_at,
            p.updated_at,
            p.model_id,
            p.parent_package_id,
            p.id,
            p.stereotype_id
           FROM packages p
        UNION ALL
         SELECT sp.xmi_id,
            sp.name,
            sp.visibility,
            sp."isSpecification",
            sp."isRoot",
            sp."isLeaf",
            sp."isAbstract",
            sp.created_at,
            sp.updated_at,
            sp.model_id,
            sp.parent_package_id,
            sp.id,
            sp.stereotype_id
           FROM (packages sp
             JOIN subpackage spc ON ((sp.parent_package_id = spc.id)))
        )
 SELECT DISTINCT ON (pc.id) pc.xmi_id,
    pc.name,
    pc.visibility,
    pc."isSpecification",
    pc."isRoot",
    pc."isLeaf",
    pc."isAbstract",
    pc.created_at,
    pc.updated_at,
    pc.model_id,
    pc.parent_package_id,
    pc.id,
    pc.stereotype_id,
    parent.name AS parent_package_name
   FROM (subpackage pc
     LEFT JOIN packages parent ON ((parent.id = pc.parent_package_id)))
  ORDER BY pc.id;


--
-- TOC entry 838 (class 1259 OID 877821)
-- Name: full_model; Type: VIEW; Schema: xplan_uml; Owner: -
--

CREATE VIEW full_model AS
 SELECT "packages_parent-name".parent_package_name,
    "packages_parent-name".name AS package_name,
    classes_attributes_types_gen.gen_name,
    classes_attributes_types_gen.class_name,
    classes_attributes_types_gen.atribute_name,
    classes_attributes_types_gen.datatype,
    classes_attributes_types_gen.classifier,
    classes_attributes_types_gen.comment
   FROM classes_attributes_types_gen,
    "packages_parent-name"
  WHERE (classes_attributes_types_gen.package_id = "packages_parent-name".id)
  ORDER BY "packages_parent-name".name, classes_attributes_types_gen.class_name;


--
-- TOC entry 816 (class 1259 OID 877636)
-- Name: packages_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE packages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12034 (class 0 OID 0)
-- Dependencies: 816
-- Name: packages_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE packages_id_seq OWNED BY packages.id;


--
-- TOC entry 818 (class 1259 OID 877647)
-- Name: stereotypes_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE stereotypes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12035 (class 0 OID 0)
-- Dependencies: 818
-- Name: stereotypes_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE stereotypes_id_seq OWNED BY stereotypes.id;


--
-- TOC entry 819 (class 1259 OID 877652)
-- Name: tagdefinitions; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE tagdefinitions (
    id integer NOT NULL,
    xmi_id character varying,
    name character varying,
    "isSpecification" character varying,
    "tagType" character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- TOC entry 820 (class 1259 OID 877658)
-- Name: tagdefinitions_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE tagdefinitions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12036 (class 0 OID 0)
-- Dependencies: 820
-- Name: tagdefinitions_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE tagdefinitions_id_seq OWNED BY tagdefinitions.id;


--
-- TOC entry 821 (class 1259 OID 877663)
-- Name: taggedvalues; Type: TABLE; Schema: xplan_uml; Owner: -; Tablespace: 
--

CREATE TABLE taggedvalues (
    id integer NOT NULL,
    xmi_id character varying,
    "isSpecification" character varying,
    datavalue character varying,
    type character varying,
    created_at timestamp without time zone,
    attribute_id integer,
    class_id integer
);


--
-- TOC entry 822 (class 1259 OID 877669)
-- Name: taggedvalues_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE taggedvalues_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12037 (class 0 OID 0)
-- Dependencies: 822
-- Name: taggedvalues_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE taggedvalues_id_seq OWNED BY taggedvalues.id;


--
-- TOC entry 824 (class 1259 OID 877680)
-- Name: uml_attributes_id_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE uml_attributes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12038 (class 0 OID 0)
-- Dependencies: 824
-- Name: uml_attributes_id_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE uml_attributes_id_seq OWNED BY uml_attributes.id;


--
-- TOC entry 826 (class 1259 OID 877691)
-- Name: uml_classes_id2_seq; Type: SEQUENCE; Schema: xplan_uml; Owner: -
--

CREATE SEQUENCE uml_classes_id2_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12039 (class 0 OID 0)
-- Dependencies: 826
-- Name: uml_classes_id2_seq; Type: SEQUENCE OWNED BY; Schema: xplan_uml; Owner: -
--

ALTER SEQUENCE uml_classes_id2_seq OWNED BY uml_classes.id;


--
-- TOC entry 11867 (class 2604 OID 877594)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY association_classes ALTER COLUMN id SET DEFAULT nextval('association_classes_testid_seq'::regclass);


--
-- TOC entry 11868 (class 2604 OID 877605)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY association_ends ALTER COLUMN id SET DEFAULT nextval('association_ends_id_seq'::regclass);


--
-- TOC entry 11869 (class 2604 OID 877616)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY class_generalizations ALTER COLUMN id SET DEFAULT nextval('class_generalizations_id_seq'::regclass);


--
-- TOC entry 11877 (class 2604 OID 877704)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY comments ALTER COLUMN id SET DEFAULT nextval('comments_id_seq'::regclass);


--
-- TOC entry 11870 (class 2604 OID 877627)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY datatypes ALTER COLUMN id SET DEFAULT nextval('datatypes_id_seq'::regclass);


--
-- TOC entry 11871 (class 2604 OID 877638)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY packages ALTER COLUMN id SET DEFAULT nextval('packages_id_seq'::regclass);


--
-- TOC entry 11872 (class 2604 OID 877649)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY stereotypes ALTER COLUMN id SET DEFAULT nextval('stereotypes_id_seq'::regclass);


--
-- TOC entry 11873 (class 2604 OID 877660)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY tagdefinitions ALTER COLUMN id SET DEFAULT nextval('tagdefinitions_id_seq'::regclass);


--
-- TOC entry 11874 (class 2604 OID 877671)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY taggedvalues ALTER COLUMN id SET DEFAULT nextval('taggedvalues_id_seq'::regclass);


--
-- TOC entry 11875 (class 2604 OID 877682)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY uml_attributes ALTER COLUMN id SET DEFAULT nextval('uml_attributes_id_seq'::regclass);


--
-- TOC entry 11876 (class 2604 OID 877693)
-- Name: id; Type: DEFAULT; Schema: xplan_uml; Owner: -
--

ALTER TABLE ONLY uml_classes ALTER COLUMN id SET DEFAULT nextval('uml_classes_id2_seq'::regclass);


--
-- TOC entry 11879 (class 2606 OID 877596)
-- Name: association_classes_pkey; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY association_classes
    ADD CONSTRAINT association_classes_pkey PRIMARY KEY (id);


--
-- TOC entry 11881 (class 2606 OID 877607)
-- Name: association_ends_pkey; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY association_ends
    ADD CONSTRAINT association_ends_pkey PRIMARY KEY (id);


--
-- TOC entry 11883 (class 2606 OID 877618)
-- Name: class_generalizations_id_key; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY class_generalizations
    ADD CONSTRAINT class_generalizations_id_key UNIQUE (id);


--
-- TOC entry 11899 (class 2606 OID 877706)
-- Name: comments_pkey; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);


--
-- TOC entry 11885 (class 2606 OID 877629)
-- Name: datatypes_pkey; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY datatypes
    ADD CONSTRAINT datatypes_pkey PRIMARY KEY (id);


--
-- TOC entry 11887 (class 2606 OID 877640)
-- Name: packages_pkey; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY packages
    ADD CONSTRAINT packages_pkey PRIMARY KEY (id);


--
-- TOC entry 11889 (class 2606 OID 877651)
-- Name: stereotypes_id_key; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY stereotypes
    ADD CONSTRAINT stereotypes_id_key UNIQUE (id);


--
-- TOC entry 11891 (class 2606 OID 877662)
-- Name: tagdefinitions_pkey; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tagdefinitions
    ADD CONSTRAINT tagdefinitions_pkey PRIMARY KEY (id);


--
-- TOC entry 11893 (class 2606 OID 877673)
-- Name: taggedvalues_pkey; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY taggedvalues
    ADD CONSTRAINT taggedvalues_pkey PRIMARY KEY (id);


--
-- TOC entry 11895 (class 2606 OID 877684)
-- Name: uml_attributes_id_key; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY uml_attributes
    ADD CONSTRAINT uml_attributes_id_key UNIQUE (id);


--
-- TOC entry 11897 (class 2606 OID 877695)
-- Name: uml_classes_pkey; Type: CONSTRAINT; Schema: xplan_uml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY uml_classes
    ADD CONSTRAINT uml_classes_pkey PRIMARY KEY (id);


-- Completed on 2017-03-03 12:05:40

--
-- PostgreSQL database dump complete
--

COMMIT;
