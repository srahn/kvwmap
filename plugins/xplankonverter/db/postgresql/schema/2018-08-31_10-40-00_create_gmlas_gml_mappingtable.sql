BEGIN;

DROP TABLE IF EXISTS xplankonverter.mappingtable_gmlas_to_gml;
CREATE TABLE xplankonverter.mappingtable_gmlas_to_gml AS
SELECT
	t.id,
	t.feature_class,
	t.o_table,
	t.o_column,
	t.o_data_type,
	t.t_table,
	t.t_column,
	t.t_data_type,
	t.codespace,
	t.complex_type,
	CASE 
		WHEN t.regel IS NOT NULL OR t.regel = '' THEN t.regel
		WHEN t.t_data_type IS NULL THEN NULL
		WHEN t.codespace IS NOT NULL THEN '(gmlas.' || t.codespace || ',gmlas.' || t_column || ',NULL)::xplan_gml.' || t_data_type || ' AS ' ||t.t_column 
		WHEN t.t_data_type IN ('integer', 'boolean', 'text', 'character varying') THEN 'gmlas.' || t.o_column || ' AS ' || t.t_column
		WHEN t.t_data_type IN ('uuid','interval') THEN 'gmlas.' || t.o_column || '::' || t_data_type ||' AS ' || t.t_column
		-- Arrays should already be taken care of
		WHEN t.o_data_type != t.t_data_type THEN 'gmlas.' || t.o_column || '::xplan_gml.' || t.t_data_type || ' AS ' || t.t_column
		ELSE 'gmlas.' || t.o_column || ' AS ' || t.t_column
	END AS regel
FROM (
	SELECT
		row_number() OVER (ORDER BY table_name, column_name) AS id,
		CASE
			WHEN table_name LIKE 'bp_%' THEN TRUE
			WHEN table_name LIKE 'fp_%' THEN TRUE
			WHEN table_name LIKE 'rp_%' THEN TRUE
			WHEN table_name LIKE 'lp_%' THEN TRUE
			WHEN table_name LIKE 'so_%' THEN TRUE
			ELSE FALSE
			END AS feature_class,
		table_name AS o_table,
		column_name AS o_column,
		CASE 	WHEN udt_name = '_int4' THEN 'integer[]'
			WHEN udt_name = 'geometry' THEN 'geometry'
			WHEN udt_name = '_varchar' THEN 'ARRAY character varying'
			ELSE data_type
			END AS o_data_type,
		CASE 
			WHEN table_name LIKE '%_%' THEN split_part(table_name, '_', 1) || '_' || split_part(table_name, '_', 2)
			ELSE table_name
			END AS t_table, 
		CASE

			WHEN column_name IN ('uuid','text','rechtsstand','gesetzlichegrundlage','gliederung1','gliederung2','hatgenerattribut', 'hoehenangabe', 'rechtscharakter','zweckbestimmung', 'startbedingung', 'endebedingung','konkretisierung', 'typ', 'status', 'allgemeinertyp', 'bezeichnung', 'besonderertyp', 'sonsttyp', 'spezifischertyp', 'baumart','massnahme', 'sonstziel', 'ziel', 'grund','kurzbeschreibung', 'nutzung', 'detailliertezweckbestimmung', 'laermpegelbereich', 'detailtyp', 'zulaessigkeit', 'thema', 'zugunstenvon', 'spezifischepraegung', 'allgartderbaulnutzung','besondereartderbaulnutzung','detaillierteartderbaulnutzung','nutzungstext','spezifischepraegung','abweichendebauweise','abweichungbaunvo','bauweise','bebauungrueckwaertigegrenze','bebauungsart','bebauungseitlichegrenze','bebauungsart','bebauungseitlichegrenze','bebauungvorderegrenze','nutzungtext','sondernutzung','wohnnutzungegstrasse','abbaugut','nutzungsform','technischemassnahme','vormerkung','ags','artderfestlegung','auspraegung''auszuschliessendenutzungen','auszuschliessendenutzungenkuerzel','bedingung','begruendung','begruendungkuerzel','beschreibung','bindung','bindungkuerzel','datumrelativ','detailartderfestlegung','eigenname','erfordernisregelung','erfordernisregelungkuerzel','folgenutzungtext','gebietsart','gemeindename','georefmimetype','georefurl','gewaessertyp','auspraegung','auszuschliessendenutzungen','horizontaleausrichtung','informationssystemurl','kennziffer','klassifizmassnahme','laermschutzzone','legendenbezeichnung','massnahmekuerzel','massnahmetext','name','nummer','ortsteilname','planname','rechtsstandgebiet','referenzmimetype','referenzname','referenzurl','regelung','rs','schriftinhalt','sonstgebietsart','sonstrechtscharakter','sonstrechtsstandgebiet','spannung','stylesheetid','textlicheergaenzung','tiefe','traegermassnahme','verlaengerung','vertikaleausrichtung','vorbehalt','vorhaben','zeitstufe','zeitstufetext','zone') THEN column_name
			WHEN split_part(table_name, '_', 3) = 'hoehenangabe' AND column_name = 'ogc_fid' THEN 'hoehenangabe'
			WHEN data_type IN ('integer', 'boolean','double precision','date') THEN column_name
			WHEN udt_name IN ('_int4','geometry', '_varchar') THEN column_name
			WHEN column_name = 'xplan_name' THEN 'name'
			WHEN column_name = 'id' THEN 'gml_id'
			WHEN column_name LIKE '%_href' THEN REPLACE(column_name, '_href', '')
			WHEN column_name = 'href' THEN split_part(table_name, '_', 3)
			ELSE NULL
			END AS t_column, 
		CASE
			WHEN split_part(table_name, '_', 3) = 'hoehenangabe' AND column_name = 'ogc_fid' THEN 'xp_hoehenangabe'
			WHEN split_part(table_name, '_', 3) = 'externereferenz' AND column_name = 'ogc_fid' THEN 'xp_spezexternereferenz'
			WHEN data_type IN ('integer','boolean','double precision','date') THEN data_type
			WHEN udt_name = '_int4' THEN 'integer[]'
			WHEN udt_name IN ('geometry') THEN udt_name
			WHEN column_name IN ('gliederung1', 'gliederung2', 'text', 'uuid','xplan_name', 'sonstziel', 'eigenname','zugunstenvon','ags','gemeindename','folgenutzungtext','abbaugut','schriftinhalt','rs', 'referenzurl','referenzname','informationssystemurl','georefurl','nutzungtext','textlicheergaenzung','textlichemassnahme','vormerkung','massnahmekuerzel','massnahmetext','ortsteilname','kurzbeschreibung','legendenbezeichnung','gewaessertyp','thema', 'nummer') THEN 'character varying'
			WHEN table_name = 'rp_gewaesser' AND column_name = 'gewaessertyp' THEN data_type
			WHEN column_name LIKE '%_href' THEN 'text'
			WHEN column_name = 'href' THEN 'text'
			WHEN column_name = 'id' THEN 'uuid'
			WHEN column_name = 'rechtsstand' THEN 'xp_rechtsstand'
			WHEN column_name = 'gesetzlichegrundlage' THEN 'xp_gesetzlichegrundlage'
			WHEN column_name = 'ziel' THEN 'xp_speziele'
			WHEN column_name = 'stylesheetid' THEN 'xp_stylsheetliste'
			WHEN column_name = 'allgartderbaulnutzung' THEN 'xp_allgartderbaulnutzung'
			WHEN table_name LIKE 'xp_%' AND column_name IN ('referenzmimetype','georefmimetype') THEN 'xp_mimetypes'
			WHEN table_name LIKE 'xp_%' AND column_name = 'horizontaleausrichtung' THEN 'xp_horizontaleausrichtung'
			WHEN table_name LIKE 'xp_%' AND column_name = 'vertikaleausrichtung' THEN 'xp_vertikaleausrichtung'
			WHEN table_name LIKE 'bp_%' AND column_name = 'dachform' THEN 'bp_dachform'
			WHEN table_name LIKE 'bp_%' AND column_name = 'abweichendebauweise' THEN 'bp_abweichendebauweise'
			WHEN table_name LIKE 'bp_%' AND column_name = 'abweichungbaunvo' THEN 'xp_abweichungbaunvotypen'
			WHEN table_name = 'xp_spemassnahmendaten' AND column_name = 'klassifizmassnahme' THEN 'xp_spemassnahmentypen'
			WHEN table_name = 'xp_spezexternereferenz' AND column_name = 'typ' THEN 'xp_externereferenztyp'
			WHEN table_name = 'bp_anpflanzungbindungerhaltung' AND column_name = 'gegenstand' THEN 'xp_anpflanzungbindungerhaltungsgegenstand[]'
			WHEN table_name IN ('fp_kennzeichnung', 'bp_kennzeichnungsflaeche') AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmungkennzeichnung[]'
			WHEN table_name = 'bp_gemeinschaftsanlagenflaeche' AND column_name = 'zweckbestimmung' THEN 'bp_zweckbestimmunggemeinschaftsanlagen[]'
			WHEN table_name = 'bp_nebenanlagenflaeche' AND column_name = 'zweckbestimmung' THEN 'bp_zweckbestimmungnebenanlagen[]'
			WHEN table_name IN ('fp_verentsorgung','bp_verentsorgung') AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmungverentsorgung[]'
			WHEN table_name IN ('bp_spielsportanlagenflaeche','fp_spielsportanlage') AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmungspielsportanlage[]'
			WHEN table_name IN ('bp_gruenflaeche','fp_gruen') AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmunggruen[]'
			WHEN table_name IN ('bp_waldflaeche','fp_waldflaeche') AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmungwald[]'
			WHEN table_name IN ('bp_gemeinbedarfsflaeche','fp_gemeinbedarf') AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmunggemeinbedarf[]'
			WHEN table_name IN ('bp_landwirtschaft','fp_landwirtschaftsflaeche') AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmunglandwirtschaft[]'
			WHEN table_name = 'fp_privilegiertesvorhaben' AND column_name = 'zweckbestimmung' THEN 'fp_zweckbestimmungprivilegiertesvorhaben[]'
			WHEN table_name = 'bp_wegerecht' AND column_name = 'thema' THEN column_name
			WHEN table_name = 'fp_zentralerversorgungsbereich' AND column_name = 'auspraegung' THEN 'fp_zentralerversorgungsbereichauspraegung'
			WHEN table_name = 'bp_anpflanzungbindungerhaltung' AND column_name = 'baumart' THEN 'vegetationsobjekttypen'
			WHEN table_name IN ('bp_baugebietsteilflaeche','bp_ueberbaubaregrundstuecksflaeche') AND column_name = 'bauweise' THEN 'bp_bauweise'
			WHEN table_name IN ('bp_baugebietsteilflaeche','bp_ueberbaubaregrundstuecksflaeche') AND column_name IN ('bebauungrueckwaertigegrenze','bebauungseitlichegrenze','bebauungvorderegrenze') THEN 'bp_grenzebebauung'
			WHEN table_name IN ('bp_baugebietsteilflaeche','bp_ueberbaubaregrundstuecksflaeche') AND column_name = 'bebauungsart' THEN 'bp_bebauungsart'
			WHEN table_name IN ('bp_baugebietsteilflaeche','bp_ueberbaubaregrundstuecksflaeche') AND column_name = 'wohnnutzungegstrasse' THEN 'bp_zulaessigkeit'
			WHEN table_name IN ('bp_baugebietsteilflaeche','fp_bebauungsflaeche') AND column_name = 'besondereartderbaulnutzung' THEN 'xp_besondereartderbaulnutzung'
			WHEN table_name IN ('bp_baugebietsteilflaeche','fp_bebauungsflaeche') AND column_name = 'sondernutzung' THEN 'xp_sondernutzungen'
			WHEN table_name = 'so_strassenverkehrsrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifiznachstrassenverkehrsrecht'
			WHEN table_name = 'so_sonstigesrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifiznachsonstigemrecht'
			WHEN table_name = 'so_forstrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifiznachforstrecht'
			WHEN table_name = 'so_luftverkehrsrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifiznachluftverkehrsrecht'
			WHEN table_name = 'so_wasserrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifiznachwasserrecht'
			WHEN table_name = 'so_denkmalschutzrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifiznachdenkmalschutzrecht'
			WHEN table_name = 'so_schienenverkehrsrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifiznachschienenverkehrsrecht'
			WHEN table_name = 'so_bodenschutzrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifiznachbodenschutzrecht'
			WHEN table_name = 'so_schutzgebietwasserrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifizschutzgebietwasserrecht'
			WHEN table_name = 'so_schutzgebietsonstigesrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifizschutzgebietsonstrecht'
			WHEN table_name = 'so_schutzgebietnaturschutzrecht' AND column_name = 'detailartderfestlegung' THEN 'so_detailklassifizschutzgebietnaturschutzrecht'
			WHEN table_name = 'fp_bebauungsflaeche' AND column_name = 'detaillierteartderbaulnutzung' THEN 'fp_detailartderbaulnutzung'
			WHEN table_name = 'bp_baugebietsteilflaeche' AND column_name = 'detaillierteartderbaulnutzung' THEN 'bp_detailartderbaulnutzung'
			WHEN table_name = 'bp_wasserwirtschaftsflaeche' AND column_name = 'detailliertezweckbestimmung' THEN 'bp_detailzweckbestwasserwirtschaft'
			WHEN table_name = 'fp_gewaesser' AND column_name = 'detailliertezweckbestimmung' THEN 'fp_detailzweckbestgewaesser'
			WHEN table_name = 'bp_verkehrsflaechebesondererzweckbestimmung' AND column_name = 'detailliertezweckbestimmung' THEN 'bp_detailzweckbeststrassenverkehr'
			WHEN table_name = 'fp_strassenverkehr' AND column_name = 'detailliertezweckbestimmung' THEN 'fp_detailzweckbeststrassenverkehr'
			WHEN table_name = 'fp_wasserwirtschaft' AND column_name = 'detailliertezweckbestimmung' THEN 'fp_detailzweckbestwasserwirtschaft'
			WHEN table_name = 'bp_gewaesserflaeche' AND column_name = 'detailliertezweckbestimmung' THEN 'bp_detailzweckbestgewaesser'
			WHEN table_name = 'bp_nutzungsartengrenze' AND column_name = 'detailtyp' THEN 'bp_detailabgrenzungentypen'
			WHEN table_name = 'bp_erhaltungsbereichflaeche' AND column_name = 'grund' THEN 'bp_erhaltunggrund'
			WHEN table_name = 'bp_immissionsschutz' AND column_name = 'laermpegelbereich' THEN 'bp_laermpegelbereich'
			WHEN table_name = 'so_luftverkehrsrecht' AND column_name = 'laermschutzzone' THEN 'so_laermschutzzonetypen'
			WHEN table_name = 'bp_anpflanzungbindungerhaltung' AND column_name = 'massnahme' THEN 'xp_abemassnahmentypen'
			WHEN table_name IN ('bp_immissionsschutz', 'bp_freiflaeche') AND column_name = 'nutzung' THEN data_type
			WHEN table_name = 'so_gebiet' AND column_name = 'traegermassnahme' THEN data_type
			WHEN table_name = 'bp_technischemassnahmenflaeche' AND column_name = 'technischemassnahme' THEN data_type
			WHEN table_name = 'fp_vorbehalteflaeche' AND column_name = 'vorbehalt' THEN data_type
			WHEN table_name = 'fp_privilegiertesvorhaben' AND column_name = 'vorhaben' THEN data_type
			WHEN table_name = 'bp_besonderernutzungszweckflaeche' AND column_name = 'zweckbestimmung' THEN data_type
			WHEN table_name LIKE 'rp_%' AND column_name IN('bezeichnung','konkretisierung','folgenuzungtext','zeitstufetext') THEN data_type
			WHEN table_name IN ('fp_strassenverkehr','bp_gruenflaeche','bp_strassenverkehrsflaeche','bp_verkehrsflaechebesondererzweckbestimmung', 'fp_gruen') AND column_name = 'nutzungsform' THEN 'xp_nutzungsform'
			WHEN table_name = 'bp_speziellebauweise' AND column_name = 'sonsttyp' THEN 'bp_speziellebauweisesonsttypen'
			WHEN table_name IN ('bp_einfahrtsbereichlinie','bp_einfahrtpunkt') AND column_name = 'typ' THEN 'bp_einfahrttypen'
			WHEN table_name = 'bp_nebenanlagenausschlussflaeche' AND column_name = 'typ' THEN 'bp_nebenanlagenausschlusstyp'
			WHEN table_name = 'so_grenze' AND column_name = 'typ' THEN 'xp_grenzetypen'
			WHEN table_name = 'bp_speziellebauweise' AND column_name = 'typ' THEN 'bp_speziellebauweisetypen'
			WHEN table_name = 'bp_strassenkoerper' AND column_name = 'typ' THEN 'bp_strassenkoerperherstellung'
			WHEN table_name = 'bp_nutzungsartengrenze' AND column_name = 'typ' THEN 'bp_abgrenzungentypen'
			WHEN table_name = 'bp_wegerecht' AND column_name = 'typ' THEN 'bp_wegerechttypen'
			WHEN table_name = 'bp_veraenderungssperre' AND column_name = 'verlaengerung' THEN 'xp_verlaengerungveraenderungssperre'
			WHEN table_name = 'bp_regelungvergnuegungsstaetten' AND column_name = 'zulaessigkeit' THEN 'bp_zulaessigkeit'
			WHEN table_name = 'bp_wasserwirtschaftsflaeche' AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmungwasserwirtschaft'
			WHEN table_name = 'bp_verkehrsflaechebesondererzweckbestimmung' AND column_name = 'zweckbestimmung' THEN 'bp_zweckbestimmungstrassenverkehr'
			WHEN table_name = 'bp_technischemassnahmenflaeche' AND column_name = 'zweckbestimmung' THEN 'bp_zweckbestimmungtmf'
			WHEN table_name IN ('bp_gewaesserflaeche','fp_gewaesser') AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmunggewaesser'
			WHEN table_name = 'fp_strassenverkehr' AND column_name = 'zweckbestimmung' THEN 'fp_zweckbestimmungstrassenverkehr'
			WHEN table_name = 'fp_wasserwirtschaft' AND column_name = 'zweckbestimmung' THEN 'xp_zweckbestimmungwasserwitschaft'
			WHEN table_name = 'so_schutzgebietwasserrecht' AND column_name = 'zone' THEN 'so_schutzzonenwasserrecht'
			WHEN table_name = 'so_schutzgebietnaturschutzrecht' AND column_name = 'zone' THEN 'so_schutzzonennaturschutzrecht'
			WHEN table_name = 'so_schutzgebietnaturschutzrecht' AND column_name = 'artderfestlegung' THEN 'xp_klassifizschutzgebietnaturschutzrecht'
			WHEN table_name = 'so_wasserrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifiznachwasserrecht'
			WHEN table_name = 'so_forstrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifiznachforstrecht'
			WHEN table_name = 'so_strassenverkehrsrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifiznachstrassenverkehrsrecht'
			WHEN table_name = 'so_sonstigesrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifiznachsonstigemrecht'
			WHEN table_name = 'so_luftverkehrsrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifiznachluftverkehrsrecht'
			WHEN table_name = 'so_schutzgebietwasserrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifizschutzgebietwasserrecht'
			WHEN table_name = 'so_schienenverkehrsrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifiznachschienenverkehrsrecht'
			WHEN table_name = 'so_schutzgebietsonstigesrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifizschutzgebietsonstrecht'
			WHEN table_name = 'so_bodenschutzrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifiznachbodenschutzrecht'
			WHEN table_name = 'so_denkmalschutzrecht' AND column_name = 'artderfestlegung' THEN 'so_klassifiznachdenkmalschutzrecht'
			WHEN table_name = 'so_gebiet' AND column_name = 'gebietsart' THEN 'so_gebietsart'
			WHEN table_name = 'so_gebiet' AND column_name = 'rechtsstandgebiet' THEN 'so_rechtsstandgebiettyp'
			WHEN table_name = 'so_gebiet' AND column_name = 'sonstgebietsart' THEN 'so_sonstgebietsart'
			WHEN table_name = 'so_gebiet' AND column_name = 'sonstrechtsstandgebiet' THEN 'so_sonstrechtsstandgebiettyp'
			WHEN table_name = 'so_grenze' AND column_name = 'sonsttyp' THEN 'so_sonstgrenzetypen'
			WHEN table_name = 'rp_achse' AND column_name = 'typ' THEN 'rp_achsentypen[]'
			WHEN table_name = 'rp_bodenschutz' AND column_name = 'typ' THEN 'rp_bodenschutztypen'
			WHEN table_name = 'rp_einzelhandel' AND column_name = 'typ' THEN 'rp_einzelhandeltypen[]'
			WHEN table_name = 'rp_energieversorgung' AND column_name = 'primaerenergietyp' THEN 'rp_primaerenergietypen[]'
			WHEN table_name = 'rp_energieversorgung' AND column_name = 'spannung' THEN 'rp_spannungtypen'
			WHEN table_name = 'rp_energieversorgung' AND column_name = 'typ' THEN 'rp_energieversorgungtypen[]'
			WHEN table_name = 'rp_entsorgung' AND column_name = 'typae' THEN 'rp_abfallentsorgungtypen[]'
			WHEN table_name = 'rp_entsorgung' AND column_name = 'typaw' THEN 'rp_abwassertypen[]'
			WHEN table_name = 'rp_erholung' AND column_name = 'besonderertyp' THEN 'rp_besonderetourismuserholungtypen'
			WHEN table_name = 'rp_erholung' AND column_name = 'typerholung' THEN 'rp_erholungtypen[]'
			WHEN table_name = 'rp_erholung' AND column_name = 'typtourismus' THEN 'rp_tourismustypen[]'
			WHEN table_name = 'rp_erneuerbareenergie' AND column_name = 'typ' THEN 'rp_erneuerbareenergietypen'
			WHEN table_name = 'rp_forstwirtschaft' AND column_name = 'typ' THEN 'rp_forstwirtschafttypen'
			WHEN table_name = 'rp_funktionszuweisung' AND column_name = 'typ' THEN 'rp_funktionszuweisungtypen[]'
			WHEN table_name = 'rp_grenze' AND column_name = 'sonsttyp' THEN 'rp_sonstgrenzetypen'
			WHEN table_name = 'rp_grenze' AND column_name = 'spezifischertyp' THEN 'rp_spezifischegrenzetypen'
			WHEN table_name = 'rp_grenze' AND column_name = 'typ' THEN 'xp_grenzetypen[]'
			WHEN table_name = 'rp_gruenzuggruenzaesur' AND column_name = 'typ' THEN 'rp_zaesurtypen[]'
			WHEN table_name = 'rp_hochwasserschutz' AND column_name = 'typ' THEN 'rp_hochwasserschutztypen[]'
			WHEN table_name = 'rp_industriegewerbe' AND column_name = 'typ' THEN 'rp_industriegewerbetypen[]'
			WHEN table_name = 'rp_klimaschutz' AND column_name = 'typ' THEN 'rp_lufttypen[]'
			WHEN table_name = 'rp_kommunikation' AND column_name = 'typ' THEN 'rp_kommunikationtypen'
			WHEN table_name = 'rp_kulturlandschaft' AND column_name = 'typ' THEN 'rp_kulturlandschafttypen'
			WHEN table_name = 'rp_laermschutzbauschutz' AND column_name = 'typ' THEN 'rp_laermschutztypen'
			WHEN table_name = 'rp_landwirtschaft' AND column_name = 'typ' THEN 'rp_landwirtschafttypen'
			WHEN table_name in('rp_luftverkehr','rp_wasserverkehr','rp_strassenverkehr', 'rp_schienenverkehr', 'rp_verkehr', 'rp_sonstverkehr') AND column_name = 'status' THEN 'rp_verkehrstatus[]'
			WHEN table_name = 'rp_luftverkehr' AND column_name = 'typ' THEN 'rp_luftverkehrtypen[]'
			WHEN table_name = 'rp_naturlandschaft' AND column_name = 'typ' THEN 'rp_naturlandschafttypen[]'
			WHEN table_name = 'rp_naturschutzrechtlichesschutzgebiet' AND column_name = 'typ' THEN 'xp_klassifizschutzgebietnaturschutzrecht[]'
			WHEN table_name = 'rp_radwegwanderweg' AND column_name = 'typ' THEN 'rp_radwegwanderwegtypen[]'
			WHEN table_name = 'rp_raumkategorie' AND column_name = 'besonderertyp' THEN 'rp_besondereraumkategorietypen'
			WHEN table_name = 'rp_raumkategorie' AND column_name = 'typ' THEN 'rp_raumkategorietypen[]'
			WHEN table_name = 'rp_rohstoff' AND column_name = 'rohstofftyp' THEN 'rp_rohstofftypen[]'
			WHEN table_name = 'rp_rohstoff' AND column_name = 'tiefe' THEN 'rp_bodenschatztiefen'
			WHEN table_name = 'rp_rohstoff' AND column_name = 'zeitstufe' THEN 'rp_zeitstufen'
			WHEN table_name = 'rp_schienenverkehr' AND column_name = 'typ' THEN 'rp_schienenverkehrtypen[]'
			WHEN table_name = 'rp_sonstverkehr' AND column_name = 'typ' THEN 'rp_sonstverkehrtypen[]'
			WHEN table_name = 'rp_sozialeinfrastruktur' AND column_name = 'typ' THEN 'rp_sozialeinfrastrukturtypen[]'
			WHEN table_name = 'rp_sperrgebiet' AND column_name = 'typ' THEN 'rp_sperrgebiettypen'
			WHEN table_name = 'rp_sportanlage' AND column_name = 'typ' THEN 'rp_sportanlagetypen'
			WHEN table_name = 'rp_strassenverkehr' AND column_name = 'typ' THEN 'rp_strassenverkehrtypen[]'
			WHEN table_name = 'rp_wasserschutz' AND column_name = 'typ' THEN 'rp_wasserschutztypen'
			WHEN table_name = 'rp_wasserschutz' AND column_name = 'zone' THEN 'rp_wasserschutzzonen[]'
			WHEN table_name = 'rp_wasserverkehr' AND column_name = 'typ' THEN 'rp_wasserverkehrtypen[]'
			WHEN table_name = 'rp_wasserwirtschaft' AND column_name = 'typ' THEN 'rp_wasserwirtschafttypen[]'
			WHEN table_name = 'rp_wohnensiedlung' AND column_name = 'typ' THEN 'rp_wohnensiedlungtypen[]'
			WHEN table_name = 'rp_zentralerort' AND column_name = 'sonstigertyp' THEN 'rp_zentralerortsonstigetypen[]'
			WHEN table_name = 'rp_zentralerort' AND column_name = 'typ' THEN 'rp_zentralerorttypen[]'
			WHEN table_name LIKE 'bp_%' AND column_name = 'rechtscharakter' THEN 'bp_rechtscharakter'
			WHEN table_name LIKE 'fp_%' AND column_name = 'rechtscharakter' THEN 'fp_rechtscharakter'
			WHEN table_name LIKE 'rp_%' AND column_name = 'rechtscharakter' THEN 'rp_rechtscharakter'
			WHEN table_name LIKE 'lp_%' AND column_name = 'rechtscharakter' THEN 'lp_rechtscharakter'
			WHEN table_name LIKE 'so_%' AND column_name = 'rechtscharakter' THEN 'so_rechtscharakter'
			WHEN table_name LIKE 'so_%' AND column_name = 'sonstrechtscharakter' THEN 'so_sonstrechtscharakter'
			WHEN table_name LIKE 'fp_%' AND column_name = 'spezifischepraegung' THEN 'fp_spezifischepraegungtypen'
			WHEN table_name LIKE 'rp_%' AND column_name = 'allgemeinertyp' THEN 'rp_verkehrtypen[]'
			WHEN table_name LIKE 'rp_%' AND column_name = 'bedeutsamkeit' THEN 'rp_bedeutsamkeit[]'
			WHEN table_name LIKE 'rp_%' AND column_name = 'gebietstyp' THEN 'rp_gebietstyp[]'
			WHEN table_name LIKE 'rp_%' AND column_name = 'bergbauplanungtyp' THEN 'rp_bergbauplanungtypen[]'
			WHEN table_name LIKE 'rp_%' AND column_name = 'folgenutzung' THEN 'rp_bergbaufolgenutzung[]'
			WHEN table_name = 'rp_schienenverkehr' AND column_name = 'besonderertyp' THEN 'rp_besondererschienenverkehrtypen[]'
			WHEN table_name = 'rp_strassenverkehr' AND column_name = 'besonderertyp' THEN 'rp_besondererstrassenverkehrtypen[]'
			WHEN table_name = 'rp_entsorgung' AND column_name = 'abfalltyp' THEN 'rp_abfalltypen[]'
			WHEN table_name LIKE 'lp_%' AND column_name IN('begruendung','begruendungkuerzel','bindung','bindungkuerzel','auszuschliessendenutzungen','auszuschliessendenutzungenkuerzel') THEN data_type
			WHEN table_name = 'xp_wirksamkeitbedingung' AND column_name = 'datumrelativ' THEN 'interval'
			WHEN table_name IN ('xp_fpo','xp_lpo','xp_lto','xp_nutzungsschablone','xp_ppo','xp_praesentationsobjekt','xp_pto') AND column_name = 'art' THEN 'character varying[]'
			WHEN table_name IN ('xp_spezexternereferenz', 'xp_externereferenz') AND column_name = 'art' THEN 'xp_externereferenzart'
			ELSE NULL
			END AS t_data_type,
		CASE 
			WHEN EXISTS(
				SELECT
					x.table_name,
					x.column_name
				FROM
					information_schema.columns x
				WHERE
					x.table_name = i.table_name
				AND
					x.table_schema = 'xplan_gmlas'
				AND
					x.column_name = i.column_name || '_codespace'
			) THEN column_name || '_codespace'
			ELSE NULL
			END AS codespace,
		CASE
			-- Complex Types
			WHEN split_part(table_name, '_', 3) = 'hoehenangabe' AND column_name = 'ogc_fid' THEN 'xp_hoehenangabe[]'
			WHEN split_part(table_name, '_', 3) = 'externereferenz' AND column_name = 'ogc_fid' THEN 'xp_spezexternereferenz'
			ELSE NULL
			END AS complex_type,
		CASE
			-- Regel
			WHEN split_part(table_name, '_', 3) = 'hoehenangabe' AND column_name = 'ogc_fid' THEN  'ARRAY[gmlas.xp_hoehenangabe_abweichenderhoehenbezug,gmlas.xp_hoehenangabe_hoehenbezug::xplan_gml.xp_arthoehenbezung,gmlas.xp_hoehenangabe_bezugspunkt::xplan_gml.xp_arthoehenbezugspunkt,gmlas.xp_hoehenangabe_hmin,gmlas.xp_hoehenangabe_hmax,gmlas.xp_hoehenangabe_hzwingend,gmlas.xp_hoehenangabe_h,gmlas.xp_hoehenangabe_abweichenderbezugspunkt]::xplan_gml.xp_hoehenangabe[] AS hoehenangabe'
			-- Removes GML_ for gml_id to allow casting to uuid and #GML_ for gehoertzubereich_href associations (e.g. gehoertzubereich_href)
			-- XPointer # should only be used for href inside a document (e.g. object -> bereich association), not for hrefs to external links, e.g. URLs
			-- TODO Also handle associations just called href (..* associations) which are defined according to their class + attribute name
			WHEN column_name = 'id' THEN 'CASE WHEN gmlas.id LIKE ''GML_%'' OR gmlas.id LIKE ''gml_%'' OR gmlas.id LIKE ''Gml_%'' THEN substring(gmlas.id, 5)::uuid ELSE gmlas.id::uuid END AS gml_id'
			WHEN column_name LIKE '%_href' THEN 'CASE WHEN gmlas.' || column_name || ' LIKE ''#GML_%'' OR gmlas.' || column_name || ' LIKE ''#gml_%'' OR gmlas.' || column_name || ' LIKE ''#Gml_%'' THEN substring(gmlas.' || column_name || ', 6) ELSE gmlas.' || column_name || ' END AS ' || REPLACE(column_name, '_href', '')
			WHEN column_name = 'gehoertzubereich_href' THEN 'CASE WHEN gmlas.gehoertzubereich_href LIKE ''#GML_%'' OR gmlas.gehoertzubereich_href LIKE ''#gml_%'' OR gmlas.gehoertzubereich_href LIKE ''#Gml_%'' THEN substring(gmlas.gehoertzubereich_href, 6) ELSE gmlas.gehoertzubereich_href END AS gehoertzubereich'
			WHEN data_type IN ('integer', 'boolean','double precision', 'date') THEN 'gmlas.' ||column_name || ' AS ' || column_name
			WHEN udt_name IN ('geometry', '_int4') THEN 'gmlas.' ||column_name || ' AS ' || column_name
			ELSE NULL
			END AS regel
		
	FROM
		information_schema.columns i
	WHERE
		table_schema = 'xplan_gmlas'
	AND
		table_name NOT IN ('xplanauszug', 'xplanauszug_featuremember', 'xplanauszug_name', 'bp_plan', 'bp_bereich', 'fp_plan', 'fp_bereich', 'rp_plan', 'rp_bereich', 'lp_plan', 'lp_bereich', 'so_plan', 'so_bereich', 'abstractdiscretecoverage', 'abstractdiscretecoverage_name','xp_generattribut', 'xp_doubleattribut', 'xp_stringattribut', 'xp_integerattribut', 'xp_urlattribut', 'xp_datumattribut','xp_verbundenerplan','xp_plangeber')
	AND	
		table_name NOT LIKE '%_hatgenerattribut'
	AND
		table_name NOT LIKE '%_generischesobjekt%'
	AND
		table_name NOT LIKE 'rectifiedgridcoverage%'
	AND
		table_name NOT LIKE 'multipointcoverage%'
	AND
		table_name NOT LIKE '%textabschnitt%'
	AND
		table_name NOT LIKE '%begruendungabschnitt%'
	AND
		table_name NOT LIKE '%raster%'
	AND
		table_name NOT LIKE 'bp_plan%'
	AND
		table_name NOT LIKE 'fp_plan%'
	AND
		table_name NOT LIKE 'rp_plan%'
	AND
		table_name NOT LIKE 'lp_plan%'
	AND
		table_name NOT LIKE 'so_plan%'
	AND
		table_name NOT LIKE 'bp_bereich%'
	AND
		table_name NOT LIKE 'fp_bereich%'
	AND
		table_name NOT LIKE 'rp_bereich%'
	AND
		table_name NOT LIKE 'lp_bereich%'
	AND
		table_name NOT LIKE 'so_bereich%'
	AND
		column_name NOT IN ('description', 'nilreason', 'title', 'owns', 'identifier', 'identifier_codespace', 'occurrence', 'hat_title', 'hat_owns', 'hat_nilreason', 'descriptionreference_href', 'codespace', 'value','child_pkid', 'parent_pkid', 'ogr_pkid', 'parent_id')
	AND
		column_name NOT LIKE '%nilreason'
	AND
		column_name NOT LIKE '%_owns'
	AND
		column_name NOT LIKE '%_title'
	AND
		column_name NOT LIKE '%axisorder'
	AND
		column_name NOT LIKE '%_pkid'
	AND
		column_name NOT LIKE '%_uom'
	AND
		column_name NOT LIKE '%_codespace'
	AND
		table_name NOT LIKE '%_name'
	AND
		((column_name != 'ogc_fid') OR (split_part(table_name, '_', 3) IN ('hoehenangabe','externereferenz')))
) t
ORDER BY t.t_table, t.t_column;
ALTER TABLE xplankonverter.mappingtable_gmlas_to_gml ADD PRIMARY KEY (id);

COMMIT;