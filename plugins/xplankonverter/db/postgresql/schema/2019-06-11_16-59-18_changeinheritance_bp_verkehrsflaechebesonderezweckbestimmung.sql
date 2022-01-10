BEGIN;
-- Fixes inheritance of bp_strassenverkehrsflaeche from bp_flaechenschlussobjekt to bp_geometrieobjekt
-- Uses a temporary different name to transfer data
CREATE TABLE xplan_gml.bp_strassenverkehrsflaeche_temp
(
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  gml_id uuid NOT NULL DEFAULT uuid_generate_v1mc(),
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  uuid character varying,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  text character varying,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  rechtsstand xplan_gml.xp_rechtsstand,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  gesetzlichegrundlage xplan_gml.xp_gesetzlichegrundlage,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  gliederung1 character varying,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  gliederung2 character varying,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  ebene integer,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  hatgenerattribut xplan_gml.xp_generattribut[],
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  hoehenangabe xplan_gml.xp_hoehenangabe[],
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  user_id integer,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  created_at timestamp without time zone NOT NULL DEFAULT now(),
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  updated_at timestamp without time zone NOT NULL DEFAULT now(),
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  konvertierung_id integer,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  refbegruendunginhalt text,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  gehoertzubereich text,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  wirddargestelltdurch text,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  externereferenz xplan_gml.xp_spezexternereferenz[],
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  startbedingung xplan_gml.xp_wirksamkeitbedingung,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  endebedingung xplan_gml.xp_wirksamkeitbedingung,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  rechtscharakter xplan_gml.bp_rechtscharakter NOT NULL,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  wirdausgeglichendurchspemassnahme text,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  wirdausgeglichendurchmassnahme text,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  wirdausgeglichendurchspeflaeche text,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  wirdausgeglichendurchflaeche text,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  wirdausgeglichendurchabe text,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  reftextinhalt text,
-- Geerbt from table xplan_gml.fp_geometrieobjekt:  nordwinkel double precision,
-- Geerbt from table xplan_gml.fp_geometrieobjekt:  flussrichtung boolean,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  "position" geometry(MultiPolygon) NOT NULL,
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  flaechenschluss boolean,
  nutzungsform xplan_gml.xp_nutzungsform, -- nutzungsform enumeration XP_Nutzungsform 0..1
  begrenzungslinie text, -- Assoziation zu: FeatureType BP_StrassenbegrenzungsLinie (bp_strassenbegrenzungslinie) 0..*
  maxzahlwohnungen integer,
  fmin double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet Fmin double precision 0..1
  fmax double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet Fmax double precision 0..1
  bmin double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet Bmax double precision 0..1
  bmax double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet Bmax double precision 0..1
  tmin double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet Tmin double precision 0..1
  tmax double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet Tmax double precision 0..1
  gfzmin double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GFZmin double precision 0..1
  gfzmax double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GFZmax double precision 0..1
  gfz double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GFZ double precision 0..1
  gfz_ausn double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GFZ_Ausn double precision 0..1
  gfmin double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GFZmin double precision 0..1
  gfmax double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GFZmax double precision 0..1
  gf double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GF double precision 0..1
  gf_ausn double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GF_Ausn double precision 0..1
  bmz double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet BMZ double precision 0..1
  bmz_ausn double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet BMZ_Ausn double precision 0..1
  bm double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet BM double precision 0..1
  bm_ausn double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet BM_Ausn double precision 0..1
  grzmin double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GRZmin double precision 0..1
  grzmax double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GRZmax double precision 0..1
  grz double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GRZ double precision 0..1
  grz_ausn double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GRZ_Ausn double precision 0..1
  grmin double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GRmin double precision 0..1
  grmax double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GRmax double precision 0..1
  gr double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GR double precision 0..1
  gr_ausn double precision, -- Ableitung von Type BP_FestsetzungenBaugebiet GR_Ausn double precision 0..1
  zmin integer, -- Ableitung von Type BP_FestsetzungenBaugebiet Zmin integer 0..1
  zmax integer, -- Ableitung von Type BP_FestsetzungenBaugebiet Zmax integer 0..1
  zzwingend integer, -- Ableitung von Type BP_FestsetzungenBaugebiet Zzwingend integer 0..1
  z integer, -- Ableitung von Type BP_FestsetzungenBaugebiet Z integer 0..1
  z_ausn integer, -- Ableitung von Type BP_FestsetzungenBaugebiet Z_Ausn integer 0..1
  z_staffel integer, -- Ableitung von Type BP_FestsetzungenBaugebiet Z_Staffel integer 0..1
  z_dach integer, -- Ableitung von Type BP_FestsetzungenBaugebiet Z_Dach integer 0..1
  zumin integer, -- Ableitung von Type BP_FestsetzungenBaugebiet ZUmin integer 0..1
  zumax integer, -- Ableitung von Type BP_FestsetzungenBaugebiet ZUmax integer 0..1
  zuzwingend integer, -- Ableitung von Type BP_FestsetzungenBaugebiet integerZUzwingend 0..1
  zu integer, -- Ableitung von Type BP_FestsetzungenBaugebiet zu integer0..1
  zu_ausn integer -- Ableitung von Type BP_FestsetzungenBaugebiet zu_ausn integer 0..1
-- Geerbt from table xplan_gml.bp_geometrieobjekt:  aufschrift character varying
)
INHERITS (xplan_gml.bp_geometrieobjekt)
WITH (
  OIDS=TRUE
);

ALTER TABLE xplan_gml.bp_strassenverkehrsflaeche_temp
  OWNER TO kvwmap;
COMMENT ON TABLE xplan_gml.bp_strassenverkehrsflaeche_temp
  IS 'FeatureType: "BP_StrassenVerkehrsFlaeche"';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.nutzungsform IS 'nutzungsform enumeration XP_Nutzungsform 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.begrenzungslinie IS 'Assoziation zu: FeatureType BP_StrassenbegrenzungsLinie (bp_strassenbegrenzungslinie) 0..*';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.fmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet Fmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.fmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet Fmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.bmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet Bmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.bmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet Bmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.tmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet Tmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.tmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet Tmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gfzmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet GFZmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gfzmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet GFZmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gfz IS 'Ableitung von Type BP_FestsetzungenBaugebiet GFZ double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gfz_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet GFZ_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gfmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet GFZmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gfmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet GFZmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gf IS 'Ableitung von Type BP_FestsetzungenBaugebiet GF double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gf_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet GF_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.bmz IS 'Ableitung von Type BP_FestsetzungenBaugebiet BMZ double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.bmz_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet BMZ_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.bm IS 'Ableitung von Type BP_FestsetzungenBaugebiet BM double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.bm_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet BM_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.grzmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet GRZmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.grzmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet GRZmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.grz IS 'Ableitung von Type BP_FestsetzungenBaugebiet GRZ double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.grz_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet GRZ_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.grmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet GRmin double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.grmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet GRmax double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gr IS 'Ableitung von Type BP_FestsetzungenBaugebiet GR double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.gr_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet GR_Ausn double precision 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.zmin IS 'Ableitung von Type BP_FestsetzungenBaugebiet Zmin integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.zmax IS 'Ableitung von Type BP_FestsetzungenBaugebiet Zmax integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.zzwingend IS 'Ableitung von Type BP_FestsetzungenBaugebiet Zzwingend integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.z IS 'Ableitung von Type BP_FestsetzungenBaugebiet Z integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.z_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet Z_Ausn integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.z_staffel IS 'Ableitung von Type BP_FestsetzungenBaugebiet Z_Staffel integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.z_dach IS 'Ableitung von Type BP_FestsetzungenBaugebiet Z_Dach integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.zumin IS 'Ableitung von Type BP_FestsetzungenBaugebiet ZUmin integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.zumax IS 'Ableitung von Type BP_FestsetzungenBaugebiet ZUmax integer 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.zuzwingend IS 'Ableitung von Type BP_FestsetzungenBaugebiet integerZUzwingend 0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.zu IS 'Ableitung von Type BP_FestsetzungenBaugebiet zu integer0..1';
COMMENT ON COLUMN xplan_gml.bp_strassenverkehrsflaeche_temp.zu_ausn IS 'Ableitung von Type BP_FestsetzungenBaugebiet zu_ausn integer 0..1';

INSERT INTO
    xplan_gml.bp_strassenverkehrsflaeche_temp(gml_id,uuid,text,rechtsstand,gesetzlichegrundlage,gliederung1,gliederung2,ebene,hatgenerattribut,hoehenangabe,user_id,created_at,updated_at,konvertierung_id,refbegruendunginhalt,gehoertzubereich,wirddargestelltdurch,externereferenz,startbedingung,endebedingung,rechtscharakter,wirdausgeglichendurchspemassnahme,wirdausgeglichendurchmassnahme,wirdausgeglichendurchspeflaeche,wirdausgeglichendurchflaeche,wirdausgeglichendurchabe,reftextinhalt,"position",flaechenschluss,nutzungsform,begrenzungslinie,maxzahlwohnungen,fmin,fmax,bmin,bmax,tmin,tmax,gfzmin,gfzmax,gfz,gfz_ausn,gfmin,gfmax,gf,gf_ausn,bmz,bmz_ausn,bm,bm_ausn,grzmin,grzmax,grz,grz_ausn,grmin,grmax,gr,gr_ausn,zmin,zmax,zzwingend,z,z_ausn,z_staffel,z_dach,zumin,zumax,zuzwingend,zu,zu_ausn,aufschrift)
SELECT
    a.gml_id as gml_id,
    a.uuid as uuid,
    a.text as text,
    a.rechtsstand as rechtsstand,
    a.gesetzlichegrundlage as gesetzlichegrundlage,
    a.gliederung1 as gliederung1,
    a.gliederung2 as gliederung2,
    a.ebene as ebene,
    a.hatgenerattribut as hatgenerattribut,
    a.hoehenangabe as hoehenangabe,
    a.user_id as user_id,
    a.created_at as created_at,
    a.updated_at as updated_at,
    a.konvertierung_id as konvertierung_id,
    a.refbegruendunginhalt as refbegruendunginhalt,
    a.gehoertzubereich as gehoertzubereich,
    a.wirddargestelltdurch as wirddargestelltdurch,
    a.externereferenz as externereferenz,
    a.startbedingung as startbedingung,
    a.endebedingung as endebedingung,
    a.rechtscharakter as rechtscharakter,
    a.wirdausgeglichendurchspemassnahme as wirdausgeglichendurchspemassnahme,
    a.wirdausgeglichendurchmassnahme as wirdausgeglichendurchmassnahme,
    a.wirdausgeglichendurchspeflaeche as wirdausgeglichendurchspeflaeche,
    a.wirdausgeglichendurchflaeche as wirdausgeglichendurchflaeche,
    a.wirdausgeglichendurchabe as wirdausgeglichendurchabe,
    a.reftextinhalt as reftextinhalt,
    a."position" as "position",
    a.flaechenschluss as flaechenschluss,
    a.nutzungsform as nutzungsform,
    a.begrenzungslinie as begrenzungslinie,
    a.maxzahlwohnungen as maxzahlwohnungen,
    a.fmin as fmin,
    a.fmax as fmax,
    a.bmin as bmin,
    a.bmax as bmax,
    a.tmin as tmin,
    a.tmax as tmax,
    a.gfzmin as gfzmin,
    a.gfzmax as gfzmax,
    a.gfz as gfz,
    a.gfz_ausn as gfz_ausn,
    a.gfmin as gfmin,
    a.gfmax as gfmax,
    a.gf as gf,
    a.gf_ausn as gf_ausn,
    a.bmz as bmz,
    a.bmz_ausn as bmz_ausn,
    a.bm as bm,
    a.bm_ausn as bm_ausn,
    a.grzmin as grzmin,
    a.grzmax as grzmax,
    a.grz as grz,
    a.grz_ausn as grz_ausn,
    a.grmin as grmin,
    a.grmax as grmax,
    a.gr as gr,
    a.gr_ausn as gr_ausn,
    a.zmin as zmin,
    a.zmax as zmax,
    a.zzwingend as zzwingend,
    a.z as z,
    a.z_ausn as z_ausn,
    a.z_staffel as z_staffel,
    a.z_dach as z_dach,
    a.zumin as zumin,
    a.zumax as zumax,
    a.zuzwingend as zuzwingend,
    a.zu as zu,
    a.zu_ausn as zu_ausn,
    a.aufschrift as aufschrift
FROM
    xplan_gml.bp_strassenverkehrsflaeche a;

DROP TABLE xplan_gml.bp_strassenverkehrsflaeche;

ALTER TABLE
    xplan_gml.bp_strassenverkehrsflaeche_temp
RENAME TO
    bp_strassenverkehrsflaeche;

COMMIT;