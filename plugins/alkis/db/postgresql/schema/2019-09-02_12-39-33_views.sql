BEGIN;

CREATE OR REPLACE VIEW alkis.s_hausnummer_gebaeude AS 
 SELECT p.ogc_fid,
    p.beginnt,
    p.endet,
    l.hausnummer,
    p.drehwinkel * 57.296::double precision AS drehwinkel,
    p.wkb_geometry
   FROM alkis.ap_pto p
     JOIN alkis.ax_lagebezeichnungmithausnummer l ON (l.gml_id = ANY (p.dientzurdarstellungvon)) AND l.endet IS NULL
  WHERE p.art = 'HNR';

CREATE OR REPLACE VIEW alkis.s_zugehoerigkeitshaken_flurstueck AS 
 SELECT p.ogc_fid,
    p.beginnt,
    p.endet,
    p.wkb_geometry,
    p.drehwinkel * 57.296::double precision AS drehwinkel,
    f.flurstueckskennzeichen,
    f.abweichenderrechtszustand
   FROM alkis.ap_ppo p
     JOIN alkis.ax_flurstueck f ON f.gml_id = ANY (p.dientzurdarstellungvon)
  WHERE p.art = 'Haken';

CREATE OR REPLACE VIEW alkis.s_zuordungspfeil_flurstueck AS 
 SELECT l.ogc_fid,
    l.beginnt,
    l.endet,
    f.abweichenderrechtszustand,
    l.wkb_geometry
   FROM alkis.ap_lpo l
     JOIN alkis.ax_flurstueck f ON f.gml_id = ANY (l.dientzurdarstellungvon)
  WHERE l.art = 'Pfeil' AND ('DKKM1000' ~~ ANY (l.advstandardmodell));  

COMMIT;
