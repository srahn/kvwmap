BEGIN;

DROP TABLE IF EXISTS xplankonverter.mappingtable_standard_shp_to_db;
CREATE TABLE xplankonverter.mappingtable_standard_shp_to_db AS (
	SELECT
		DISTINCT table_name AS tabelle,
		column_name AS db_attribute,
		CASE
			WHEN char_length(column_name) < 11 THEN column_name
			WHEN column_name='abweichendebauweise' THEN 'abweichend'
			WHEN column_name='abweichungbaunvo' THEN 'abweichung'
			WHEN column_name='abweichungtext' THEN 'abweichu_1'
			WHEN column_name='aenderungenbisdatum' THEN 'aenderunge'
			WHEN column_name='allgartderbaulnutzung' THEN 'allgartder'
			WHEN column_name='allgemeinertyp' THEN 'allgemeine'
			WHEN column_name='amtlicherschluessel' THEN 'amtlichers'
			WHEN column_name='artderfestlegung' THEN 'artderfest'
			WHEN column_name='aufstellungsbeschhlussdatum' THEN 'aufstellun'
			WHEN column_name='aufstellungsbeschlussdatum' THEN 'aufstellun'
			WHEN column_name='ausfertigungsdatum' THEN 'ausfertigu'
			WHEN column_name='auslegungenddatum' THEN 'auslegunge'
			WHEN column_name='auslegungsenddatum' THEN 'auslegungs'
			WHEN column_name='auslegungsstartdatum' THEN 'auslegun_1'
			WHEN column_name='auslegungstartdatum' THEN 'auslegungs'
			WHEN column_name='auspraegung' THEN 'auspraegun'
			WHEN column_name='bauhoehenbeschraenkung' THEN 'bauhoehenb'
			WHEN column_name='bebauungrueckwaertigegrenze' THEN 'bebauungru'
			WHEN column_name='bebauungsart' THEN 'bebauungsa'
			WHEN column_name='bebauungseitlichegrenze' THEN 'bebauungse'
			WHEN column_name='bebauungvorderegrenze' THEN 'bebauungvo'
			WHEN column_name='bedeutsamkeit' THEN 'bedeutsamk'
			WHEN column_name='begrenzungslinie' THEN 'begrenzung'
			WHEN column_name='begruendungstexte' THEN 'begruendun'
			WHEN column_name='bergbauplanungtyp' THEN 'bergbaupla'
			WHEN column_name='beschreibung' THEN 'beschreibu'
			WHEN column_name='besondereartderbaulnutzung' THEN 'besonderea'
			WHEN column_name='besonderertyp' THEN 'besonderer'
			WHEN column_name='bezeichnung' THEN 'bezeichnun'
			WHEN column_name='bezugshoehe' THEN 'bezugshoeh'
			WHEN column_name='bp_anpflanzungbindungerhaltung_gml_id' THEN 'bp_anpflan'
			WHEN column_name='bp_ausgleichsflaeche_gml_id' THEN 'bp_ausglei'
			WHEN column_name='bp_ausgleichsmassnahme_gml_id' THEN 'bp_ausglei'
			WHEN column_name='bp_baugebietsteilflaeche_gml_id' THEN 'bp_baugebie'
			WHEN column_name='bp_baugrenze_gml_id' THEN 'bp_baugren'
			WHEN column_name='bp_baulinie_gml_id' THEN 'bp_baulini'
			WHEN column_name='bp_gemeinschaftsanlagenflaeche_gml_id' THEN 'bp_gemeins'
			WHEN column_name='bp_gemeinschaftsanlagenzuordnung_gml_id' THEN 'bp_gemeins'
			WHEN column_name='bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae' THEN 'bp_gemeins'
			WHEN column_name='bp_nebenanlagenausschlussflaeche_gml_id' THEN 'bp_nebenan'
			WHEN column_name='bp_objekt_gml_id' THEN 'bp_objekt_'
			WHEN column_name='bp_schutzpflegeentwicklungsflaeche_gml_id' THEN 'bp_schutzp'
			WHEN column_name='bp_schutzpflegeentwicklungsmassnahme_gml_id' THEN 'bp_schutzp'
			WHEN column_name='bp_strassenbegrenzungslinie_gml_id' THEN 'bp_stras_1'
			WHEN column_name='bp_strassenverkehrsflaeche_gml_id' THEN 'bp_strasse'
			WHEN column_name='bp_textabschnitt_gml_id' THEN 'bp_textabs'
			WHEN column_name='bp_ueberbaubaregrundstuecksflaeche_gml_id' THEN 'bp_ueberba'
			WHEN column_name='bp_verkehrsflaechebesondererzweckbestimmung_gml_id' THEN 'bp_verkehr'
			WHEN column_name='bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre' THEN 'bp_verkehr'
			WHEN column_name='darstellungsprioritaet' THEN 'darstellun'
			WHEN column_name='datumdesinkrafttretens' THEN 'datumdesin'
			WHEN column_name='detailartderfestlegung' THEN 'detailartd'
			WHEN column_name='detaillierteartderbaulnutzung' THEN 'detaillier'
			WHEN column_name='detailliertebedeutung' THEN 'detaillier'
			WHEN column_name='detailliertedachform' THEN 'detailli_1'
			WHEN column_name='detailliertezweckbestimmung' THEN 'detaillier'
			WHEN column_name='dientzurdarstellungvon' THEN 'dientzurda'
			WHEN column_name='durchfuehrungenddatum' THEN 'durchfuehr'
			WHEN column_name='durchfuehrungstartdatum' THEN 'durchfue_1'
			WHEN column_name='durchfuehrungsvertrag' THEN 'durchfuehr'
			WHEN column_name='eigentuemer' THEN 'eigentueme'
			WHEN column_name='endebedingung' THEN 'endebeding'
			WHEN column_name='entwurfsbeschlussdatum' THEN 'entwurfsbe'
			WHEN column_name='erschliessungsvertrag' THEN 'erschliess'
			WHEN column_name='erstellungsmassstab' THEN 'erstellung'
			WHEN column_name='externereferenz' THEN 'externeref'
			WHEN column_name='flaechenschluss' THEN 'flaechensc'
			WHEN column_name='flussrichtung' THEN 'flussricht'
			WHEN column_name='folgenutzungtext' THEN 'folgenut_1'
			WHEN column_name='folgenutzung' THEN 'folgenutzu'
			WHEN column_name='fontsperrung' THEN 'fontsperru'
			WHEN column_name='fp_ausgleichsflaeche_gml_id' THEN 'fp_ausglei'
			WHEN column_name='fp_objekt_gml_id' THEN 'fp_objekt_'
			WHEN column_name='fp_schutzpflegeentwicklung_gml_id' THEN 'fp_schutzp'
			WHEN column_name='fp_textabschnitt_gml_id' THEN 'fp_textabs'
			WHEN column_name='gehoertzubereich' THEN 'gehoertzub'
			WHEN column_name='gehoertzuplan' THEN 'gehoertzup'
			WHEN column_name='gehoertzupraesentationsobjekt' THEN 'gehoertzup'
			WHEN column_name='geltungsmassstab' THEN 'geltungsma'
			WHEN column_name='genehmigungsdatum' THEN 'genehmigun'
			WHEN column_name='geschossmax' THEN 'geschossma'
			WHEN column_name='geschossmin' THEN 'geschossmi'
			WHEN column_name='gesetzlichegrundlage' THEN 'gesetzlich'
			WHEN column_name='gewaessertyp' THEN 'gewaessert'
			WHEN column_name='gfantgewerbe' THEN 'gfantgewer'
			WHEN column_name='gfantwohnen' THEN 'gfantwohne'
			WHEN column_name='gliederung1' THEN 'gliederung'
			WHEN column_name='gliederung2' THEN 'gliederu_1'
			WHEN column_name='gruenordnungsplan' THEN 'gruenordnu'
			WHEN column_name='gueltigkeitsdatum' THEN 'gueltigkei'
			WHEN column_name='hatgenerattribut' THEN 'hatgenerat'
			WHEN column_name='hoehenangabe' THEN 'hoehenanga'
			WHEN column_name='hoehenbezug' THEN 'hoehenbezu'
			WHEN column_name='horizontaleausrichtung' THEN 'horizontal'
			WHEN column_name='inkrafttretensdatum' THEN 'inkrafttre'
			WHEN column_name='inverszu_abweichungtext_bp_baugebietsteilflaeche' THEN 'inverszu_a'
			WHEN column_name='inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche' THEN 'inverszu_1'
			WHEN column_name='inverszu_baugrenze_bp_ueberbaubaregrundstuecksflaeche' THEN 'inverszu_b'
			WHEN column_name='inverszu_baulinie_bp_ueberbaubaregrundstuecksflaeche' THEN 'inverszu_b'
			WHEN column_name='inverszu_begrenzungslinie_bp_strassenverkehrsflaeche' THEN 'inverszu_b'
			WHEN column_name='inverszu_begrenzungslinie_bp_verkehrsflaechebesondererzwec' THEN 'inverszu_1'
			WHEN column_name='inverszu_begruendungstexte_xp_plan' THEN 'inverszub'
			WHEN column_name='inverszu_eigentuemer_bp_gemeinschaftsanlagenflaeche' THEN 'inverszu_e'
			WHEN column_name='inverszu_gehoertzupraesentationsobjekt_rp_legendenobjekt' THEN 'inverszu_g'
			WHEN column_name='inverszu_hat_xp_ppo' THEN 'inverszu_h'
			WHEN column_name='inverszu_hat_xp_tpo' THEN 'inverszu_1'
			WHEN column_name='inverszu_rasterbasis_xp_bereich' THEN 'inverszu_r'
			WHEN column_name='inverszu_refbegruendunginhalt_xp_objekt' THEN 'inverszu_r'
			WHEN column_name='inverszu_reftextinhalt_bp_objekt' THEN 'inverszu_r'
			WHEN column_name='inverszu_reftextinhalt_fp_objekt' THEN 'inverszu_r'
			WHEN column_name='inverszu_reftextinhalt_rp_objekt' THEN 'inverszu_r'
			WHEN column_name='inverszu_reftextinhalt_so_objekt' THEN 'inverszu_r'
			WHEN column_name='inverszu_texte_xp_plan' THEN 'inverszu_t'
			WHEN column_name='inverszu_verbundenerplan_xp_verbundenerplan' THEN 'inverszu_v'
			WHEN column_name='inverszu_wirdausgeglichendurchabe_bp_objekt' THEN 'inverszu_w'
			WHEN column_name='inverszu_wirdausgeglichendurchflaeche_bp_objekt' THEN 'inverszu_w'
			WHEN column_name='inverszu_wirdausgeglichendurchflaeche_fp_objekt' THEN 'inverszu_w'
			WHEN column_name='inverszu_wirdausgeglichendurchmassnahme_bp_objekt' THEN 'inverszu_w'
			WHEN column_name='inverszu_wirdausgeglichendurchspeflaeche_bp_objekt' THEN 'inverszu_w'
			WHEN column_name='inverszu_wirdausgeglichendurchspe_fp_objekt' THEN 'inverszu_w'
			WHEN column_name='inverszu_wirdausgeglichendurchspemassnahme_bp_objekt' THEN 'inverszu_w'
			WHEN column_name='inverszu_zuordnung_bp_gemeinschaftsanlagenzuordnung' THEN 'inverszu_z'
			WHEN column_name='istaufschuettungablagerung' THEN 'istaufschu'
			WHEN column_name='istausgleichsgebiet' THEN 'istausglei'
			WHEN column_name='istausgleich' THEN 'istausglei'
			WHEN column_name='istkernzone' THEN 'istkernzon'
			WHEN column_name='istnatuerlichesuberschwemmungsgebiet' THEN 'istnatuerl'
			WHEN column_name='istsiedlungsbeschraenkung' THEN 'istsiedlun'
			WHEN column_name='istverdachtsflaeche' THEN 'istverdach'
			WHEN column_name='istzweckbindung' THEN 'istzweckbi'
			WHEN column_name='konkretisierung' THEN 'konkretisi'
			WHEN column_name='konvertierung_id' THEN 'konvertier'
			WHEN column_name='kronendurchmesser' THEN 'kronendurc'
			WHEN column_name='kuestenmeer' THEN 'kuestenmee'
			WHEN column_name='kurzbeschreibung' THEN 'kurzbeschr'
			WHEN column_name='laermpegelbereich' THEN 'laermpegel'
			WHEN column_name='laermschutzzone' THEN 'laermschut'
			WHEN column_name='legendenbezeichnung' THEN 'legendenbe'
			WHEN column_name='maxzahlwohnungen' THEN 'maxzahlwoh'
			WHEN column_name='nutzungsform' THEN 'nutzungsfo'
			WHEN column_name='nutzungtext' THEN 'nutzungtex'
			WHEN column_name='pflanztiefe' THEN 'pflanztief'
			WHEN column_name='planbeschlussdatum' THEN 'planbeschl'
			WHEN column_name='planungsraumbeschreibung' THEN 'planungsra'
			WHEN column_name='planungsregion' THEN 'planungsre'
			WHEN column_name='praesentationsobjekt' THEN 'praesentat'
			WHEN column_name='primaerenergietyp' THEN 'primaerene'
			WHEN column_name='rasterbasis' THEN 'rasterbasi'
			WHEN column_name='rechtscharakter' THEN 'rechtschar'
			WHEN column_name='rechtsstandgebiet' THEN 'rechsst_1'
			WHEN column_name='rechtsstand' THEN 'rechtsstan'
			WHEN column_name='rechtsverordnungsdatum' THEN 'rechtveror'
			WHEN column_name='refbegruendunginhalt' THEN 'refbegruen'
			WHEN column_name='refbeschluss' THEN 'refbeschlu'
			WHEN column_name='refgebaeudequerschnitt' THEN 'refgebaeud'
			WHEN column_name='reflandschaftsplan' THEN 'reflandsch'
			WHEN column_name='reflegendenbild' THEN 'reflegende'
			WHEN column_name='refmassnahmentext' THEN 'refmassnah'
			WHEN column_name='reftextinhalt' THEN 'reftextinh'
			WHEN column_name='rohstofftyp' THEN 'rohstoffty'
			WHEN column_name='rp_objekt_gml_id' THEN 'rp_objekt_'
			WHEN column_name='rp_textabschnitt_gml_id' THEN 'rp_textabs'
			WHEN column_name='satzungsbeschlussdatum' THEN 'satzungsbe'
			WHEN column_name='schriftinhalt' THEN 'schriftinh'
			WHEN column_name='sondernutzung' THEN 'sondernu'
			WHEN column_name='sonstgebietsart' THEN 'sonstgebie'
			WHEN column_name='sonstigertyp' THEN 'sonstigert'
			WHEN column_name='sonstplanart' THEN 'sonstplana'
			WHEN column_name='sonstrechtscharakter' THEN 'sonstrecht'
			WHEN column_name='sonstrechtsstandgebiet' THEN 'sonstrec_1'
			WHEN column_name='so_objekt_gml_id' THEN 'so_objekt_'
			WHEN column_name='so_textabschnitt_gml_id' THEN 'so_textabs'
			WHEN column_name='spezifischepraegung' THEN 'spezifisch'
			WHEN column_name='spezifischertyp' THEN 'spezifisch'
			WHEN column_name='staedtebaulichervertrag' THEN 'staedtebau'
			WHEN column_name='startbedingung' THEN 'startbed'
			WHEN column_name='startwinkel' THEN 'startwinke'
			WHEN column_name='stylesheetid' THEN 'stylesheet'
			WHEN column_name='technherstelldatum' THEN 'technherst'
			WHEN column_name='technischemassnahme' THEN 'technische'
			WHEN column_name='teilabschnitt' THEN 'teilabschn'
			WHEN column_name='textlicheergaenzung' THEN 'textlichee'
			WHEN column_name='traegerbeteiligungsenddatum' THEN 'traegerb_1' -- Für BP, für FP umgekehrt (da unterschiedliche Sequenz?)
			WHEN column_name='traegerbeteiligungsstartdatum' THEN 'traegerbet' -- Für FP, für BP umgekehrt (da unterschiedliche Sequenz?)
			WHEN column_name='traegermassnahme' THEN 'traegermas'
			WHEN column_name='typerholung' THEN 'typerholun'
			WHEN column_name='typtourismus' THEN 'typtourism'
			WHEN column_name='untergangsdatum' THEN 'untergangs'
			WHEN column_name='veraenderungssperredatum' THEN 'veraenderu'
			WHEN column_name='veraenderungssperre' THEN 'veraende_1'
			WHEN column_name='verfahrensmerkmale' THEN 'verfahrens'
			WHEN column_name='verlaengerung' THEN 'verlaenger'
			WHEN column_name='versionbaugbdatum' THEN 'versionb_3'
			WHEN column_name='versionbaugbtext' THEN 'versionb_1'
			WHEN column_name='versionbaunvodatum' THEN 'versionbau'
			WHEN column_name='versionbaunvotext' THEN 'versionb_2'
			WHEN column_name='versionbrogtext' THEN 'versionb_1'
			WHEN column_name='versionbrog' THEN 'versionbro'
			WHEN column_name='versionlplgtext' THEN 'versionL_1'
			WHEN column_name='versionlplg' THEN 'versionlpl'
			WHEN column_name='versionsonstrechtsgrundlagedatum' THEN 'versions_1'
			WHEN column_name='versionsonstrechtsgrundlagetext' THEN 'versionson'
			WHEN column_name='vertikaleausrichtung' THEN 'vertikalea'
			WHEN column_name='vertikaledifferenzierung' THEN 'vertikaled'
			WHEN column_name='weltkulturerbe' THEN 'weltkultur'
			WHEN column_name='wirdausgeglichendurchabe' THEN 'wirdausg_4'
			WHEN column_name='wirdausgeglichendurchflaeche' THEN 'wirdausg_3'
			WHEN column_name='wirdausgeglichendurchmassnahme' THEN 'wirdausg_1'
			WHEN column_name='wirdausgeglichendurchspeflaeche' THEN 'wirdausg_2'
			WHEN column_name='wirdausgeglichendurchspemassnahme' THEN 'wirdausgeg'
			WHEN column_name='wirdausgeglichendurchspe' THEN 'wirdausgeg'
			WHEN column_name='wirddargestelltdurch' THEN 'wirddarges'
			WHEN column_name='wirksamkeitsdatum' THEN 'wirksamkei'
			WHEN column_name='wohnnutzungegstrasse' THEN 'wohnnutzun'
			WHEN column_name='wurdegeaendertvon' THEN 'wurdegeaen'
			WHEN column_name='xp_abstraktespraesentationsobjekt_gml_id' THEN 'xp_abstrak'
			WHEN column_name='xp_begruendungabschnitt_gml_id' THEN 'xp_begruen'
			WHEN column_name='xp_objekt_gml_id' THEN 'xp_objekt_'
			WHEN column_name='zeitstufetext' THEN 'zeitstufet'
			WHEN column_name='zugunstenvon' THEN 'zugunstenv'
			WHEN column_name='zulaessigkeit' THEN 'zulaessigk'
			WHEN column_name='zweckbestimmung' THEN 'zweckbesti'
			ELSE ''
			END AS shp_attribute,
			CASE
				WHEN column_name = 'traegerbeteiligungsenddatum' THEN 'traegerb_1 bei BP, traegerbet bei FP, da Sequenzorder unterschiedlich'
				WHEN column_name='traegerbeteiligungsstartdatum' THEN 'traegerbet bei BP, traegerb_1 bei FP, da Sequenzorder unterschiedlich'
				WHEN column_name ='wirdausgeglichendurchflaeche' THEN 'wirdausg_1 für FP_SchutzPflegeEntwicklung, wirdausg_3 bei BP_SchutzPflegeEntwicklungsFlaeche'
			ELSE NULL
			END AS ambiguous_fields,
		CASE
			WHEN g.t_data_type = 'xp_hoehenangabe' THEN 'xp_hoehenangabe[]' -- Complex_type issue
			ELSE g.t_data_type
			END AS data_type,
		NULL AS regel
	FROM
		information_schema.columns i
	INNER JOIN
		xplankonverter.mappingtable_gmlas_to_gml g ON i.column_name = g.t_column AND i.table_name = g.t_table
	INNER JOIN
		xplan_uml.uml_classes u ON (i.table_name = LOWER(u.name))
	WHERE
		i.column_name = g.t_column AND
		column_name NOT IN ('geltungsbereich', 'raeumlichergeltungsbereich', 'position') AND
		table_schema = 'xplan_gml' AND
		(
			table_name LIKE 'bp\_%' OR
			table_name LIKE 'fp\_%' OR
			table_name LIKE 'so\_%'
		) AND
		table_name NOT LIKE '%\_textabschnitt' AND
		column_name NOT LIKE '%\_zu\_%' AND
		column_name NOT LIKE '%_gml_id' AND
		table_name NOT LIKE '%\_bereich' AND
		table_name NOT LIKE '%\_plan' AND
		table_name NOT IN ('bp_punktobjekt','bp_punktobjekt','bp_punktobjekt','bp_flaechenobjekt', 'fp_flaechenobjekt', 'so_flaechenobjekt', 'bp_objekt', 'fp_objekt', 'so_objekt','bp_flaechenschlussobjekt','fp_flaechenschlussobjekt','bp_linienobjekt', 'fp_linienobjekt', 'so_linienobjekt', 'bp_ueberlagerungsobjekt', 'bp_geometrieobjekt', 'fp_geometrieobjekt','fp_punktobjekt','fp_ueberlagerungsobjekt','so_punktobjekt') AND
		i.table_name NOT LIKE 'xp\_%' AND
		i.table_name NOT LIKE 'lp\_%' AND
		i.table_name NOT LIKE 'rp\_%' AND
		i.table_name NOT LIKE '%\_plan' AND
		i.table_name NOT LIKE '%\_bereich' AND
		i.table_name NOT LIKE '%\_textabschnitt' AND
		u.general_id != '-1'
);
ALTER TABLE xplankonverter.mappingtable_standard_shp_to_db SET WITH OIDS;
COMMENT ON TABLE xplankonverter.mappingtable_standard_shp_to_db IS 'This table holds the mapping of all attributes that are longer than 10 letters. For characters with 10 or less letters, the attributename can be taken as is. Ambiguous attributes have to be evaluated specifically depending on the used table and/or schema, as the sequenceorder of elements may vary.';

-- GML ID cut 'GML_'
-- TODO Cases of gml_, Gml_
UPDATE
	xplankonverter.mappingtable_standard_shp_to_db
SET
	regel = 'trim(leading ''GML_'' FROM gml_id)::text::uuid AS gml_id'
WHERE
	db_attribute = 'gml_id' AND
	regel IS NULL;

-- Booleans need special casts over int
UPDATE
	xplankonverter.mappingtable_standard_shp_to_db
SET
	regel = shp_attribute || '::int::bool AS ' || db_attribute
WHERE
	db_attribute IN ('flaechenschluss', 'flussrichtung') AND
	regel IS NULL;

UPDATE
	xplankonverter.mappingtable_standard_shp_to_db
SET
	regel = shp_attribute || ' AS ' || db_attribute
WHERE
	data_type IN ('boolean', 'integer', 'text','character varying', 'double precision') AND
	regel IS NULL;

UPDATE
	xplankonverter.mappingtable_standard_shp_to_db
SET
	regel = shp_attribute || '::' || data_type || ' AS ' || db_attribute
WHERE
	data_type IN ('date', 'uuid') AND
	regel IS NULL;

UPDATE
	xplankonverter.mappingtable_standard_shp_to_db
SET
	regel = shp_attribute || '::xplan_gml.' || data_type || ' AS ' || db_attribute
WHERE
	regel IS NULL;

COMMIT;