BEGIN;
-- BP_AbweichendeBauweise
--BP_DetailAbgrenzungenTypen
--BP_DetailArtDerBaulNutzung
--BP_DetailDachform
--BP_DetailZweckbestGemeinbedarf
--BP_DetailZweckbestGemeinschaftsanlagen
--BP_DetailZweckbestGewaesser
--BP_DetailZweckbestGruenFlaeche
--BP_DetailZweckbestLandwirtschaft
--BP_DetailZweckbestNebenanlagen
--BP_DetailZweckbestSpielSportanlage
--BP_DetailZweckbestStrassenverkehr
--BP_DetailZweckbestVerEntsorgung
--BP_DetailZweckbestWaldFlaeche
--BP_DetailZweckbestWasserwirtschaft
--BP_SonstPlanArt
TRUNCATE
	xplan_gml.bp_sonstplanart;
INSERT INTO 
	xplan_gml.bp_sonstplanart (codespace, id, value)
VALUES
	('https://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml', '1000', 'Sanierungssatzung'),
	('https://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml', '2000', 'Erhaltungssatzung'),
	('https://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml', '3000', 'Stadtumbaugebietssatzung'),
	('https://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml', '4000', 'sonstige Bausatzung'),
	('https://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml', '5000', 'Entwicklungssatzung'),
	('https://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml', '6000', 'städtebauliche Entwicklungssatzung'),
	('https://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml', '7000', 'städtebauliches Entwicklungskonzept'),
	('https://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml', '8000', 'Vorhaben- und Erschließungsplan')
;
--BP_SpezielleBauweiseSonstTypen
--BP_Status
TRUNCATE
	xplan_gml.bp_status;
INSERT INTO 
	xplan_gml.bp_status (codespace, id, value)
VALUES
	('https://bauleitplaene-mv.de/codelist/BP_Status/BP_Status.xml', '1000', 'Aufstellung'),
	('https://bauleitplaene-mv.de/codelist/BP_Status/BP_Status.xml', '2000', 'Entwurf'),
	('https://bauleitplaene-mv.de/codelist/BP_Status/BP_Status.xml', '3000', 'Satzung'),
	('https://bauleitplaene-mv.de/codelist/BP_Status/BP_Status.xml', '4000', 'Rechtskraft'),
	('https://bauleitplaene-mv.de/codelist/BP_Status/BP_Status.xml', '5000', 'Unwirksamkeit, Aufhebung')
;
--BP_ZweckbestimmungGenerischeObjekte
--FP_DetailArtDerBaulNutzung
--FP_DetailZweckbestGemeinbedarf
--FP_DetailZweckbestGewaesser
--FP_DetailZweckbestGruen
--FP_DetailZweckbestLandwirtschaftsFlaeche
--FP_DetailZweckbestSpielSportanlage
--FP_DetailZweckbestStrassenverkehr
--FP_DetailZweckbestVerEntsorgung
--FP_DetailZweckbestWaldFlaeche
--FP_DetailZweckbestWasserwirtschaft 
--FP_SonstPlanArt
--FP_SpezifischePraegungTypen
--FP_Status
TRUNCATE
	xplan_gml.fp_status;
INSERT INTO 
	xplan_gml.fp_status (codespace, id, value)
VALUES
	('https://bauleitplaene-mv.de/codelist/FP_Status/FP_Status.xml', '1000', 'Aufstellung'),
	('https://bauleitplaene-mv.de/codelist/FP_Status/FP_Status.xml', '2000', 'Entwurf'),
	('https://bauleitplaene-mv.de/codelist/FP_Status/FP_Status.xml', '3000', 'Satzung'),
	('https://bauleitplaene-mv.de/codelist/FP_Status/FP_Status.xml', '4000', 'Rechtskraft'),
	('https://bauleitplaene-mv.de/codelist/FP_Status/FP_Status.xml', '5000', 'Unwirksamkeit, Aufhebung')
;
--FP_ZentralerVersorgungsbereichAuspraegung
--FP_ZweckbestimmungGenerischeObjekte
--LP_BodenschutzrechtDetailTypen
--LP_ErholungFreizeitDetailFunktionen	 
--LP_InternatSchutzobjektDetailTypen
--LP_MassnahmeLandschaftsbild
--LP_Pflanzart
--LP_SchutzobjektLandesrechtDetailTypen
--LP_SonstPlanArt
--LP_SonstRechtDetailTypen
--LP_WaldschutzDetailTypen
--LP_WasserrechtGemeingebrEinschraenkungNaturschutzDetailTypen
--LP_WasserrechtSchutzgebietDetailTypen
--LP_WasserrechtSonstigeTypen
--LP_WasserrechtWirtschaftAbflussHochwSchutzDetailTypen
--LP_ZweckbestimmungGenerischeObjekte
--RP_GenerischesObjektTypen
--RP_SonstGrenzeTypen
--RP_SonstPlanArt
--RP_Status
--SO_DetailKlassifizNachBodenschutzrecht
--SO_DetailKlassifizNachDenkmalschutzrecht
--SO_DetailKlassifizNachForstrecht
--SO_DetailKlassifizNachLuftverkehrsrecht
--SO_DetailKlassifizNachSchienenverkehrsrecht
--SO_DetailKlassifizNachSonstigemRecht
--SO_DetailKlassifizNachStrassenverkehrsrecht
--SO_DetailKlassifizNachWasserrecht
--SO_DetailKlassifizSchutzgebietNaturschutzrecht
--SO_DetailKlassifizSchutzgebietSonstRecht
--SO_DetailKlassifizSchutzgebietWasserrecht
--SO_PlanArt
--SO_SonstGebietsArt
--SO_SonstGrenzeTypen
--SO_SonstRechtscharakter
--SO_SonstRechtsstandGebietTyp
--VegetationsobjektTypen
--XP_GesetzlicheGrundlage
--XP_MimeTypes
-- XP_MimeTypes
TRUNCATE
	xplan_gml.xp_mimetypes;
INSERT INTO 
	xplan_gml.xp_mimetypes (codespace, id, value)
VALUES
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/pdf', 'application/pdf'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/zip', 'application/zip'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/xml', 'application/xml'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/msword', 'application/msword'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/msexcel', 'application/msexcel'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/vnd.ogc.sld+xml', 'application/vnd.ogc.sld+xml'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/vnd.ogc.wms_xml', 'application/vnd.ogc.wms_xml'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/vnd.ogc.gml', 'application/vnd.ogc.gml'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'application/odt', 'application/odt'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'image/jpg', 'image/jpg'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'image/png', 'image/png'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'image/tiff', 'image/tiff'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'image/ecw', 'image/ecw'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'image/svg+xml', 'image/svg+xml'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'text/html', 'text/html'),
	('https://bauleitplaene-mv.de/codelist/XP_MimeType/XP_MimeType.xml', 'text/plain', 'text/plain')
;
COMMIT;
--XP_StylesheetListe