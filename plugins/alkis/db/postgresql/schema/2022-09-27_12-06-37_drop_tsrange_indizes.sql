BEGIN;

BEGIN;

DROP INDEX aa_aktivitaet_tsrange
ON alkis.aa_aktivitaet
USING gist
(tsrange(beginnt, endet));

DROP INDEX aa_antrag_tsrange
ON alkis.aa_antrag
USING gist
(tsrange(beginnt, endet));



DROP INDEX ap_darstellung_tsrange
ON alkis.ap_darstellung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ap_fpo_tsrange
ON alkis.ap_fpo
USING gist
(tsrange(beginnt, endet));



DROP INDEX ap_lpo_tsrange
ON alkis.ap_lpo
USING gist
(tsrange(beginnt, endet));

DROP INDEX ap_lto_tsrange
ON alkis.ap_lto
USING gist
(tsrange(beginnt, endet));

DROP INDEX ap_ppo_tsrange
ON alkis.ap_ppo
USING gist
(tsrange(beginnt, endet));

DROP INDEX ap_pto_tsrange
ON alkis.ap_pto
USING gist
(tsrange(beginnt, endet));



DROP INDEX ax_abgeleitetehoehenlinie_tsrange
ON alkis.ax_abgeleitetehoehenlinie
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_abschnitt_tsrange
ON alkis.ax_abschnitt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_anderefestlegungnachstrassenrecht_tsrange
ON alkis.ax_anderefestlegungnachstrassenrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_anderefestlegungnachwasserrecht_tsrange
ON alkis.ax_anderefestlegungnachwasserrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_anschrift_tsrange
ON alkis.ax_anschrift
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_ast_tsrange
ON alkis.ax_ast
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_aufnahmepunkt_tsrange
ON alkis.ax_aufnahmepunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_aussparungsflaeche_tsrange
ON alkis.ax_aussparungsflaeche
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bahnstrecke_tsrange
ON alkis.ax_bahnstrecke
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bahnverkehr_tsrange
ON alkis.ax_bahnverkehr
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bahnverkehrsanlage_tsrange
ON alkis.ax_bahnverkehrsanlage
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_baublock_tsrange
ON alkis.ax_baublock
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bauraumoderbodenordnungsrecht_tsrange
ON alkis.ax_bauraumoderbodenordnungsrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bauteil_tsrange
ON alkis.ax_bauteil
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bauwerkimgewaesserbereich_tsrange
ON alkis.ax_bauwerkimgewaesserbereich
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bauwerkimverkehrsbereich_tsrange
ON alkis.ax_bauwerkimverkehrsbereich
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bauwerkoderanlagefuerindustrieundgewerbe_tsrange
ON alkis.ax_bauwerkoderanlagefuerindustrieundgewerbe
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bauwerkoderanlagefuersportfreizeitunderholung_tsrange
ON alkis.ax_bauwerkoderanlagefuersportfreizeitunderholung
USING gist
(tsrange(beginnt, endet));



DROP INDEX ax_bergbaubetrieb_tsrange
ON alkis.ax_bergbaubetrieb
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_besondereflurstuecksgrenze_tsrange
ON alkis.ax_besondereflurstuecksgrenze
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_besonderegebaeudelinie_tsrange
ON alkis.ax_besonderegebaeudelinie
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_besondererbauwerkspunkt_tsrange
ON alkis.ax_besondererbauwerkspunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_besonderergebaeudepunkt_tsrange
ON alkis.ax_besonderergebaeudepunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_besondererhoehenpunkt_tsrange
ON alkis.ax_besondererhoehenpunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_besonderertopographischerpunkt_tsrange
ON alkis.ax_besonderertopographischerpunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bewertung_tsrange
ON alkis.ax_bewertung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bodenschaetzung_tsrange
ON alkis.ax_bodenschaetzung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_boeschungkliff_tsrange
ON alkis.ax_boeschungkliff
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_boeschungsflaeche_tsrange
ON alkis.ax_boeschungsflaeche
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_buchungsblatt_tsrange
ON alkis.ax_buchungsblatt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_buchungsblattbezirk_tsrange
ON alkis.ax_buchungsblattbezirk
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_buchungsstelle_tsrange
ON alkis.ax_buchungsstelle
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_bundesland_tsrange
ON alkis.ax_bundesland
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_dammwalldeich_tsrange
ON alkis.ax_dammwalldeich
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_denkmalschutzrecht_tsrange
ON alkis.ax_denkmalschutzrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_dienststelle_tsrange
ON alkis.ax_dienststelle
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_duene_tsrange
ON alkis.ax_duene
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_einrichtungenfuerdenschiffsverkehr_tsrange
ON alkis.ax_einrichtungenfuerdenschiffsverkehr
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_einrichtunginoeffentlichenbereichen_tsrange
ON alkis.ax_einrichtunginoeffentlichenbereichen
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_einschnitt_tsrange
ON alkis.ax_einschnitt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_fahrbahnachse_tsrange
ON alkis.ax_fahrbahnachse
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_fahrwegachse_tsrange
ON alkis.ax_fahrwegachse
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_felsenfelsblockfelsnadel_tsrange
ON alkis.ax_felsenfelsblockfelsnadel
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_firstlinie_tsrange
ON alkis.ax_firstlinie
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_flaechebesondererfunktionalerpraegung_tsrange
ON alkis.ax_flaechebesondererfunktionalerpraegung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_flaechegemischternutzung_tsrange
ON alkis.ax_flaechegemischternutzung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_flaechezurzeitunbestimmbar_tsrange
ON alkis.ax_flaechezurzeitunbestimmbar
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_fliessgewaesser_tsrange
ON alkis.ax_fliessgewaesser
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_flugverkehr_tsrange
ON alkis.ax_flugverkehr
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_flugverkehrsanlage_tsrange
ON alkis.ax_flugverkehrsanlage
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_flurstueck_tsrange
ON alkis.ax_flurstueck
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_forstrecht_tsrange
ON alkis.ax_forstrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_fortfuehrungsfall_tsrange
ON alkis.ax_fortfuehrungsfall
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_fortfuehrungsnachweisdeckblatt_tsrange
ON alkis.ax_fortfuehrungsnachweisdeckblatt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_friedhof_tsrange
ON alkis.ax_friedhof
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gebaeude_tsrange
ON alkis.ax_gebaeude
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gebaeudeausgestaltung_tsrange
ON alkis.ax_gebaeudeausgestaltung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gebiet_bundesland_tsrange
ON alkis.ax_gebiet_bundesland
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gebiet_kreis_tsrange
ON alkis.ax_gebiet_kreis
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gebiet_nationalstaat_tsrange
ON alkis.ax_gebiet_nationalstaat
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gebiet_regierungsbezirk_tsrange
ON alkis.ax_gebiet_regierungsbezirk
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gebiet_verwaltungsgemeinschaft_tsrange
ON alkis.ax_gebiet_verwaltungsgemeinschaft
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gebietsgrenze_tsrange
ON alkis.ax_gebietsgrenze
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gehoelz_tsrange
ON alkis.ax_gehoelz
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gelaendekante_tsrange
ON alkis.ax_gelaendekante
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gemarkung_tsrange
ON alkis.ax_gemarkung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gemarkungsteilflur_tsrange
ON alkis.ax_gemarkungsteilflur
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gemeinde_tsrange
ON alkis.ax_gemeinde
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gemeindeteil_tsrange
ON alkis.ax_gemeindeteil
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_georeferenziertegebaeudeadresse_tsrange
ON alkis.ax_georeferenziertegebaeudeadresse
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_geripplinie_tsrange
ON alkis.ax_geripplinie
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gewaesserachse_tsrange
ON alkis.ax_gewaesserachse
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gewaesserbegrenzung_tsrange
ON alkis.ax_gewaesserbegrenzung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gewaessermerkmal_tsrange
ON alkis.ax_gewaessermerkmal
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gewaesserstationierungsachse_tsrange
ON alkis.ax_gewaesserstationierungsachse
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gewann_tsrange
ON alkis.ax_gewann
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_gleis_tsrange
ON alkis.ax_gleis
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_grablochderbodenschaetzung_tsrange
ON alkis.ax_grablochderbodenschaetzung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_grenzpunkt_tsrange
ON alkis.ax_grenzpunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_grenzuebergang_tsrange
ON alkis.ax_grenzuebergang
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_hafen_tsrange
ON alkis.ax_hafen
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_hafenbecken_tsrange
ON alkis.ax_hafenbecken
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_halde_tsrange
ON alkis.ax_halde
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_heide_tsrange
ON alkis.ax_heide
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_heilquellegasquelle_tsrange
ON alkis.ax_heilquellegasquelle
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_historischesbauwerkoderhistorischeeinrichtung_tsrange
ON alkis.ax_historischesbauwerkoderhistorischeeinrichtung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_historischesflurstueck_tsrange
ON alkis.ax_historischesflurstueck
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_historischesflurstueckalb_tsrange
ON alkis.ax_historischesflurstueckalb
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_historischesflurstueckohneraumbezug_tsrange
ON alkis.ax_historischesflurstueckohneraumbezug
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_hoehenfestpunkt_tsrange
ON alkis.ax_hoehenfestpunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_hoehenlinie_tsrange
ON alkis.ax_hoehenlinie
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_hoehleneingang_tsrange
ON alkis.ax_hoehleneingang
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_industrieundgewerbeflaeche_tsrange
ON alkis.ax_industrieundgewerbeflaeche
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_insel_tsrange
ON alkis.ax_insel
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_kanal_tsrange
ON alkis.ax_kanal
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_klassifizierungnachstrassenrecht_tsrange
ON alkis.ax_klassifizierungnachstrassenrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_klassifizierungnachwasserrecht_tsrange
ON alkis.ax_klassifizierungnachwasserrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_kleinraeumigerlandschaftsteil_tsrange
ON alkis.ax_kleinraeumigerlandschaftsteil
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_kommunalesgebiet_tsrange
ON alkis.ax_kommunalesgebiet
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_kondominium_tsrange
ON alkis.ax_kondominium
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_kreisregion_tsrange
ON alkis.ax_kreisregion
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_lagebezeichnungkatalogeintrag_tsrange
ON alkis.ax_lagebezeichnungkatalogeintrag
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_lagebezeichnungmithausnummer_tsrange
ON alkis.ax_lagebezeichnungmithausnummer
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_lagebezeichnungmitpseudonummer_tsrange
ON alkis.ax_lagebezeichnungmitpseudonummer
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_lagebezeichnungohnehausnummer_tsrange
ON alkis.ax_lagebezeichnungohnehausnummer
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_lagefestpunkt_tsrange
ON alkis.ax_lagefestpunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_landschaft_tsrange
ON alkis.ax_landschaft
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_landwirtschaft_tsrange
ON alkis.ax_landwirtschaft
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_leitung_tsrange
ON alkis.ax_leitung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_markantergelaendepunkt_tsrange
ON alkis.ax_markantergelaendepunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_meer_tsrange
ON alkis.ax_meer
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_moor_tsrange
ON alkis.ax_moor
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_musterlandesmusterundvergleichsstueck_tsrange
ON alkis.ax_musterlandesmusterundvergleichsstueck
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_namensnummer_tsrange
ON alkis.ax_namensnummer
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_nationalstaat_tsrange
ON alkis.ax_nationalstaat
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_naturumweltoderbodenschutzrecht_tsrange
ON alkis.ax_naturumweltoderbodenschutzrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_netzknoten_tsrange
ON alkis.ax_netzknoten
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_nullpunkt_tsrange
ON alkis.ax_nullpunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_ortslage_tsrange
ON alkis.ax_ortslage
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_person_tsrange
ON alkis.ax_person
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_personengruppe_tsrange
ON alkis.ax_personengruppe
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_platz_tsrange
ON alkis.ax_platz
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_polder_tsrange
ON alkis.ax_polder
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_punktkennunguntergegangen_tsrange
ON alkis.ax_punktkennunguntergegangen
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_punktkennungvergleichend_tsrange
ON alkis.ax_punktkennungvergleichend
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_punktortag_tsrange
ON alkis.ax_punktortag
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_punktortau_tsrange
ON alkis.ax_punktortau
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_punktortta_tsrange
ON alkis.ax_punktortta
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_referenzstationspunkt_tsrange
ON alkis.ax_referenzstationspunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_regierungsbezirk_tsrange
ON alkis.ax_regierungsbezirk
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_reservierung_tsrange
ON alkis.ax_reservierung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_schifffahrtsliniefaehrverkehr_tsrange
ON alkis.ax_schifffahrtsliniefaehrverkehr
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_schiffsverkehr_tsrange
ON alkis.ax_schiffsverkehr
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_schleuse_tsrange
ON alkis.ax_schleuse
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_schutzgebietnachnaturumweltoderbodenschutzrecht_tsrange
ON alkis.ax_schutzgebietnachnaturumweltoderbodenschutzrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_schutzgebietnachwasserrecht_tsrange
ON alkis.ax_schutzgebietnachwasserrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_schutzzone_tsrange
ON alkis.ax_schutzzone
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_schwere_tsrange
ON alkis.ax_schwere
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_schwerefestpunkt_tsrange
ON alkis.ax_schwerefestpunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_seilbahnschwebebahn_tsrange
ON alkis.ax_seilbahnschwebebahn
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_sicherungspunkt_tsrange
ON alkis.ax_sicherungspunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_sickerstrecke_tsrange
ON alkis.ax_sickerstrecke
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_siedlungsflaeche_tsrange
ON alkis.ax_siedlungsflaeche
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_skizze_tsrange
ON alkis.ax_skizze
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_soll_tsrange
ON alkis.ax_soll
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_sonstigervermessungspunkt_tsrange
ON alkis.ax_sonstigervermessungspunkt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_sonstigesbauwerkodersonstigeeinrichtung_tsrange
ON alkis.ax_sonstigesbauwerkodersonstigeeinrichtung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_sonstigesrecht_tsrange
ON alkis.ax_sonstigesrecht
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_sportfreizeitunderholungsflaeche_tsrange
ON alkis.ax_sportfreizeitunderholungsflaeche
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_stehendesgewaesser_tsrange
ON alkis.ax_stehendesgewaesser
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_strasse_tsrange
ON alkis.ax_strasse
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_strassenachse_tsrange
ON alkis.ax_strassenachse
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_strassenverkehr_tsrange
ON alkis.ax_strassenverkehr
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_strassenverkehrsanlage_tsrange
ON alkis.ax_strassenverkehrsanlage
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_strukturierterfasstegelaendepunkte_tsrange
ON alkis.ax_strukturierterfasstegelaendepunkte
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_sumpf_tsrange
ON alkis.ax_sumpf
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_tagebaugrubesteinbruch_tsrange
ON alkis.ax_tagebaugrubesteinbruch
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_tagesabschnitt_tsrange
ON alkis.ax_tagesabschnitt
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_testgelaende_tsrange
ON alkis.ax_testgelaende
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_topographischelinie_tsrange
ON alkis.ax_topographischelinie
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_transportanlage_tsrange
ON alkis.ax_transportanlage
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_turm_tsrange
ON alkis.ax_turm
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_unlandvegetationsloseflaeche_tsrange
ON alkis.ax_unlandvegetationsloseflaeche
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_untergeordnetesgewaesser_tsrange
ON alkis.ax_untergeordnetesgewaesser
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_vegetationsmerkmal_tsrange
ON alkis.ax_vegetationsmerkmal
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_verband_tsrange
ON alkis.ax_verband
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_vertretung_tsrange
ON alkis.ax_vertretung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_verwaltung_tsrange
ON alkis.ax_verwaltung
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_verwaltungsgemeinschaft_tsrange
ON alkis.ax_verwaltungsgemeinschaft
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_vorratsbehaelterspeicherbauwerk_tsrange
ON alkis.ax_vorratsbehaelterspeicherbauwerk
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_wald_tsrange
ON alkis.ax_wald
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_wasserlauf_tsrange
ON alkis.ax_wasserlauf
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_wasserspiegelhoehe_tsrange
ON alkis.ax_wasserspiegelhoehe
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_weg_tsrange
ON alkis.ax_weg
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_wegpfadsteig_tsrange
ON alkis.ax_wegpfadsteig
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_wirtschaftlicheeinheit_tsrange
ON alkis.ax_wirtschaftlicheeinheit
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_wohnbauflaeche_tsrange
ON alkis.ax_wohnbauflaeche
USING gist
(tsrange(beginnt, endet));

DROP INDEX ax_wohnplatz_tsrange
ON alkis.ax_wohnplatz
USING gist
(tsrange(beginnt, endet));

DROP INDEX n_nutzung_tsrange
ON alkis.n_nutzung
USING gist
(tsrange(beginnt, endet));

DROP INDEX pp_flurstueck_nr_tsrange
ON alkis.pp_flurstueck_nr
USING gist
(tsrange(beginnt, endet));

DROP INDEX pp_zuordungspfeilspitze_flurstueck_tsrange
ON alkis.pp_zuordungspfeilspitze_flurstueck
USING gist
(tsrange(beginnt, endet));



COMMIT;


COMMIT;
