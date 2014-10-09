 -- View zum Austausch der BRWs
 
BEGIN;

SET search_path = bodenrichtwerte, public;
SET default_with_oids = true;
 
CREATE OR REPLACE VIEW bw_boris_view AS 
	SELECT 
	bw.gutachterausschuss||'_'||LPAD(cast(bodenrichtwertnummer as char(13)), 7 , '0') as brwid,
	k.kreisname as kreis_name,
	k.kreis as kreis_schluessel ,
	--e.gemeindeverband_name,
	--e.gemeindeverband_schluessel,
	--e.gemeinde_schluessel,
	NULL as gemeindeteil_schluessel,
	bw.gemeinde AS gesl,
	g.gemeindename AS gena, 
	bw.gutachterausschuss AS gasl, 
	NULL as gabe,
	substring(cast(bw.gemarkung as text) from 3 for 4) as genu,
	NULL as gema,
	bw.ortsteilname AS ortst,
	LPAD(cast(bw.bodenrichtwertnummer as char(13)), 7 , '0') as wnum,
	CASE
			WHEN bw.brwu IS NOT NULL THEN bw.brwu
			WHEN bw.brwb IS NOT NULL THEN bw.brwb
			ELSE bw.bodenrichtwert
	END AS brw,
	bw.stichtag AS stag, 
	'1' AS brke, 
	bw.bedarfswert as bedw, 
	bw.postleitzahl as plz, 
	bw.basiskarte as basbe,
	'' AS basma, 
	'EPSG:25833' AS bezug,
	bw.entwicklungszustand AS entw,
	bw.beitragszustand AS beit,
	bw.nutzungsart AS nuta,
	bw.ergaenzende_nutzung AS ergnuta,
	bw.bauweise AS bauw,
	bw.geschosszahl AS gez,
	bw.geschossflaechenzahl AS wgfz,
	bw.grundflaechenzahl AS grz,
	bw.baumassenzahl AS bmz,
	bw.flaeche AS flae,
	bw.tiefe AS gtie,
	bw.breite AS gbrei,
	bw.erschliessungsverhaeltnisse as erve,
	bw.verfahrensgrund AS verg, 
	CASE
			WHEN bw.brwu IS NOT NULL AND bw.verfahrensgrund::text = 'San'::text THEN 'SU'::character varying
			WHEN bw.brwu IS NOT NULL AND bw.verfahrensgrund::text = 'Entw'::text THEN 'EU'::character varying
			WHEN bw.brwb IS NOT NULL AND bw.verfahrensgrund::text = 'San'::text THEN 'SB'::character varying
			WHEN bw.brwb IS NOT NULL AND bw.verfahrensgrund::text = 'Entw'::text THEN 'EB'::character varying
			ELSE bw.verfahrensgrund_zusatz
	END AS verf,
	bw.bodenart AS bod,
	bw.ackerzahl AS acza,
	bw.gruenlandzahl AS grza, 
	bw.aufwuchs as aufw,
	bw.wegeerschliessung as weer,
	bw.bemerkungen as bem,
	''as frei,
	bw.oertliche_bezeichnung as brzname, 
	'0' AS umdart, 
	('http://pfad/zur/umrechungstabelle/tabelle'::text || bw.stichtag) || '.pdf' ::text AS lumnum,
	bw.zonentyp AS typ,
	(bw.stichtag + Interval '1 Days') as guelt_von,
	(bw.stichtag + Interval '2 Year') as guelt_bis,
	bw.the_geom as geometrie
	FROM alb_v_kreise k, bodenrichtwerte.bw_zonen bw
	LEFT JOIN alb_v_gemeinden g ON bw.gemeinde = g.gemeinde;
	
COMMIT;