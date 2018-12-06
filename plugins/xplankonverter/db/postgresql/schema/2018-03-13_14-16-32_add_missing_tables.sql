BEGIN;

CREATE TABLE xplan_gml.bp_detailzweckbestgruenflaeche
(
  codespace text,
  id character varying NOT NULL,
  value text,
  CONSTRAINT bp_detailzweckbestgruenflaeche_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.bp_detailzweckbestgruenflaeche
  IS 'Alias: "BP_DetailZweckbestGruenFlaeche", UML-Typ: Code Liste';
COMMENT ON COLUMN xplan_gml.bp_detailzweckbestgruenflaeche.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN xplan_gml.bp_detailzweckbestgruenflaeche.id IS 'id  character varying ';

CREATE TABLE xplan_gml.bp_detailzweckbestlandwirtschaft
(
  codespace text,
  id character varying NOT NULL,
  value text,
  CONSTRAINT bp_detailzweckbestlandwirtschaft_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.bp_detailzweckbestlandwirtschaft
  IS 'Alias: "BP_DetailZweckbestLandwirtschaft", UML-Typ: Code Liste';
COMMENT ON COLUMN xplan_gml.bp_detailzweckbestlandwirtschaft.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN xplan_gml.bp_detailzweckbestlandwirtschaft.id IS 'id  character varying ';

CREATE TABLE xplan_gml.bp_detailzweckbestwaldflaeche
(
  codespace text,
  id character varying NOT NULL,
  value text,
  CONSTRAINT bp_detailzweckbestwaldflaeche_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.bp_detailzweckbestwaldflaeche
  IS 'Alias: "BP_DetailZweckbestWaldFlaeche", UML-Typ: Code Liste';
COMMENT ON COLUMN xplan_gml.bp_detailzweckbestwaldflaeche.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN xplan_gml.bp_detailzweckbestwaldflaeche.id IS 'id  character varying ';

CREATE TABLE xplan_gml.fp_detailzweckbestverentsorgung
(
  codespace text,
  id character varying NOT NULL,
  value text,
  CONSTRAINT fp_detailzweckbestverentsorgung_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.fp_detailzweckbestverentsorgung
  IS 'Alias: "FP_DetailZweckbestVerEntsorgung", UML-Typ: Code Liste';
COMMENT ON COLUMN xplan_gml.fp_detailzweckbestverentsorgung.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN xplan_gml.fp_detailzweckbestverentsorgung.id IS 'id  character varying ';

CREATE TABLE xplan_gml.fp_zentralerversorgungsbereichauspraegung
(
  codespace text,
  id character varying NOT NULL,
  value text,
  CONSTRAINT fp_zentralerversorgungsbereichauspraegung_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.fp_zentralerversorgungsbereichauspraegung
  IS 'Alias: "FP_ZentralerVersorgungsbereichAuspraegung", UML-Typ: Code Liste';
COMMENT ON COLUMN xplan_gml.fp_zentralerversorgungsbereichauspraegung.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN xplan_gml.fp_zentralerversorgungsbereichauspraegung.id IS 'id  character varying ';


CREATE TABLE xplan_gml.bp_gruenflaeche
(
  maxzahlwohnungen integer,
  fmin double precision,
  fmax double precision,
  bmin double precision,
  bmax double precision,
  tmin double precision,
  tmax double precision,
  gfzmin double precision,
  gfzmax double precision,
  gfz double precision,
  gfz_ausn double precision,
  gfmin double precision,
  gfmax double precision,
  gf double precision,
  gf_ausn double precision,
  bmz double precision,
  bmz_ausn double precision,
  bm double precision,
  bm_ausn double precision,
  grzmin double precision,
  grzmax double precision,
  grz double precision,
  grz_ausn double precision,
  grmin double precision,
  grmax double precision,
  gr double precision,
  gr_ausn double precision,
  zmin integer,
  zmax integer,
  zzwingend integer,
  z integer,
  z_ausn integer,
  z_staffel integer,
  z_dach integer,
  zumin integer,
  zumax integer,
  zuzwingend integer,
  zu integer,
  zu_ausn integer,
  detailliertezweckbestimmung xplan_gml.bp_detailzweckbestgruenflaeche[],
  nutzungsform xplan_gml.xp_nutzungsform,
  zugunstenvon character varying,
  zweckbestimmung xplan_gml.xp_zweckbestimmunggruen[]
)
INHERITS (xplan_gml.bp_flaechenschlussobjekt)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.bp_gruenflaeche
  IS 'FeatureType: "BP_GruenFlaeche"';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.fmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision Fmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.fmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision Fmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.bmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision Bmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.bmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision Bmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.tmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision Tmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.tmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision Tmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gfzmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GFZmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gfzmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GFZmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gfz IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GFZ double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gfz_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GFZ_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gfmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GFZmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gfmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GFZmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gf IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GF double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gf_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GF_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.bmz IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision BMZ double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.bmz_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision BMZ_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.bm IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision BM double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.bm_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision BM_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.grzmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GRZmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.grzmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GRZmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.grz IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GRZ double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.grz_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GRZ_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.grmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GRmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.grmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GRmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gr IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GR double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.gr_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet double precision GR_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer Zmin integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer Zmax integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zzwingend IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer Zzwingend integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.z IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer Z integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.z_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer Z_Ausn integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.z_staffel IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer Z_Staffel integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.z_dach IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer Z_Dach integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zumin IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer ZUmin integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zumax IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer ZUmax integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zuzwingend IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer integerZUzwingend 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zu IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer zu integer0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zu_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer zu_ausn integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungGruen 0..*';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestGruenFlaeche 0..*';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.nutzungsform IS 'nutzungsform enumeation XP_Nutzungsform 0..1';
COMMENT ON COLUMN xplan_gml.bp_gruenflaeche.zugunstenvon IS 'zugunstenVon CharacterString 0..1';

CREATE TABLE xplan_gml.bp_waldflaeche
(
  detailliertezweckbestimmung xplan_gml.bp_detailzweckbestwaldflaeche[],
  zweckbestimmung xplan_gml.xp_zweckbestimmungwald[]
)
INHERITS (xplan_gml.bp_flaechenschlussobjekt)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.bp_waldflaeche
  IS 'FeatureType: "BP_WaldFlaeche"';
COMMENT ON COLUMN xplan_gml.bp_waldflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungWald 0..*';
COMMENT ON COLUMN xplan_gml.bp_waldflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestWaldFlaeche 0..*';


CREATE TABLE xplan_gml.bp_kleintierhaltungflaeche
(
)
INHERITS (xplan_gml.bp_flaechenschlussobjekt)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.bp_kleintierhaltungflaeche
  IS 'FeatureType: "BP_KleintierhaltungFlaeche"';

CREATE TABLE xplan_gml.bp_landwirtschaft
(
  detailliertezweckbestimmung xplan_gml.bp_detailzweckbestlandwirtschaft[],
  zweckbestimmung xplan_gml.xp_zweckbestimmunglandwirtschaft[]
)
INHERITS (xplan_gml.bp_geometrieobjekt)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.bp_landwirtschaft
  IS 'FeatureType: "BP_Landwirtschaft"';
COMMENT ON COLUMN xplan_gml.bp_landwirtschaft.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungLandwirtschaft 0..*';
COMMENT ON COLUMN xplan_gml.bp_landwirtschaft.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbesLandwirtschaft 0..*';

CREATE TABLE xplan_gml.fp_verentsorgung
(
  detailliertezweckbestimmung xplan_gml.fp_detailzweckbestverentsorgung[],
  zweckbestimmung xplan_gml.xp_zweckbestimmungverentsorgung[],
  zugunstenvon Character Varying,
  textlicheergaenzung Character Varying
)
INHERITS (xplan_gml.fp_geometrieobjekt)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.fp_verentsorgung
  IS 'FeatureType: "FP_VerEntsorgung"';
COMMENT ON COLUMN xplan_gml.fp_verentsorgung.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungLandwirtschaft 0..*';
COMMENT ON COLUMN xplan_gml.fp_verentsorgung.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbesLandwirtschaft 0..*';
COMMENT ON COLUMN xplan_gml.fp_verentsorgung.zugunstenvon IS 'zugunstenVon Character Varying 0..1';
COMMENT ON COLUMN xplan_gml.fp_verentsorgung.textlicheergaenzung IS 'textlicheErgaenzung Character Varying 0..1';

CREATE TABLE xplan_gml.fp_zentralerversorgungsbereich
(
  auspraegung xplan_gml.fp_zentralerversorgungsbereichauspraegung
)
INHERITS (xplan_gml.fp_ueberlagerungsobjekt)
WITH (
  OIDS=TRUE
);
COMMENT ON TABLE xplan_gml.fp_verentsorgung
  IS 'FeatureType: "FP_VerEntsorgung"';
COMMENT ON COLUMN xplan_gml.fp_zentralerversorgungsbereich.auspraegung IS 'auspraegung CodeList FP_ZentralerVersorgungsbereichAuspraegung 0..1';

COMMIT;
