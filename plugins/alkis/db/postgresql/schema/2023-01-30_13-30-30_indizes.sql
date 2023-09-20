BEGIN;

/*
 *  die auskomentierten Zeilen sind die alten Definitionen (nur für alle Fälle)
 */


DROP INDEX IF EXISTS alkis.aa_aktivitaet_endet;
--CREATE INDEX aa_aktivitaet_endet ON alkis.aa_aktivitaet USING btree (endet);
CREATE INDEX aa_aktivitaet_endet ON alkis.aa_aktivitaet USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_aktivitaet_gml;
--CREATE UNIQUE INDEX aa_aktivitaet_gml ON alkis.aa_aktivitaet USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX aa_aktivitaet_gml ON alkis.aa_aktivitaet USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_antragsgebiet_endet;
--CREATE INDEX aa_antragsgebiet_endet ON alkis.aa_antragsgebiet USING btree (endet);
CREATE INDEX aa_antragsgebiet_endet ON alkis.aa_antragsgebiet USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_antragsgebiet_gml;
--CREATE UNIQUE INDEX aa_antragsgebiet_gml ON alkis.aa_antragsgebiet USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX aa_antragsgebiet_gml ON alkis.aa_antragsgebiet USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_meilenstein_endet;
--CREATE INDEX aa_meilenstein_endet ON alkis.aa_meilenstein USING btree (endet);
CREATE INDEX aa_meilenstein_endet ON alkis.aa_meilenstein USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_meilenstein_gml;
--CREATE UNIQUE INDEX aa_meilenstein_gml ON alkis.aa_meilenstein USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX aa_meilenstein_gml ON alkis.aa_meilenstein USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_projektsteuerung_endet;
--CREATE INDEX aa_projektsteuerung_endet ON alkis.aa_projektsteuerung USING btree (endet);
CREATE INDEX aa_projektsteuerung_endet ON alkis.aa_projektsteuerung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_projektsteuerung_gml;
--CREATE UNIQUE INDEX aa_projektsteuerung_gml ON alkis.aa_projektsteuerung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX aa_projektsteuerung_gml ON alkis.aa_projektsteuerung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_vorgang_endet;
--CREATE INDEX aa_vorgang_endet ON alkis.aa_vorgang USING btree (endet);
CREATE INDEX aa_vorgang_endet ON alkis.aa_vorgang USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_vorgang_gml;
--CREATE UNIQUE INDEX aa_vorgang_gml ON alkis.aa_vorgang USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX aa_vorgang_gml ON alkis.aa_vorgang USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_darstellung_endet;
--CREATE INDEX ap_darstellung_endet ON alkis.ap_darstellung USING btree (endet);
CREATE INDEX ap_darstellung_endet ON alkis.ap_darstellung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_darstellung_gml;
--CREATE UNIQUE INDEX ap_darstellung_gml ON alkis.ap_darstellung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ap_darstellung_gml ON alkis.ap_darstellung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_fpo_endet;
--CREATE INDEX ap_fpo_endet ON alkis.ap_fpo USING btree (endet);
CREATE INDEX ap_fpo_endet ON alkis.ap_fpo USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_fpo_gml;
--CREATE UNIQUE INDEX ap_fpo_gml ON alkis.ap_fpo USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ap_fpo_gml ON alkis.ap_fpo USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_kpo_3d_endet;
--CREATE INDEX ap_kpo_3d_endet ON alkis.ap_kpo_3d USING btree (endet);
CREATE INDEX ap_kpo_3d_endet ON alkis.ap_kpo_3d USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_kpo_3d_gml;
--CREATE UNIQUE INDEX ap_kpo_3d_gml ON alkis.ap_kpo_3d USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ap_kpo_3d_gml ON alkis.ap_kpo_3d USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_lpo_endet;
--CREATE INDEX ap_lpo_endet ON alkis.ap_lpo USING btree (endet);
CREATE INDEX ap_lpo_endet ON alkis.ap_lpo USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_lpo_gml;
--CREATE UNIQUE INDEX ap_lpo_gml ON alkis.ap_lpo USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ap_lpo_gml ON alkis.ap_lpo USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_lto_endet;
--CREATE INDEX ap_lto_endet ON alkis.ap_lto USING btree (endet);
CREATE INDEX ap_lto_endet ON alkis.ap_lto USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_lto_gml;
--CREATE UNIQUE INDEX ap_lto_gml ON alkis.ap_lto USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ap_lto_gml ON alkis.ap_lto USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_ppo_endet;
--CREATE INDEX ap_ppo_endet ON alkis.ap_ppo USING btree (endet);
CREATE INDEX ap_ppo_endet ON alkis.ap_ppo USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_ppo_gml;
--CREATE UNIQUE INDEX ap_ppo_gml ON alkis.ap_ppo USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ap_ppo_gml ON alkis.ap_ppo USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_pto_endet;
--CREATE INDEX ap_pto_endet ON alkis.ap_pto USING btree (endet);
CREATE INDEX ap_pto_endet ON alkis.ap_pto USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ap_pto_gml;
--CREATE UNIQUE INDEX ap_pto_gml ON alkis.ap_pto USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ap_pto_gml ON alkis.ap_pto USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_geometrieobjekt_3d_endet;
--CREATE INDEX au_geometrieobjekt_3d_endet ON alkis.au_geometrieobjekt_3d USING btree (endet);
CREATE INDEX au_geometrieobjekt_3d_endet ON alkis.au_geometrieobjekt_3d USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_geometrieobjekt_3d_gml;
--CREATE UNIQUE INDEX au_geometrieobjekt_3d_gml ON alkis.au_geometrieobjekt_3d USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX au_geometrieobjekt_3d_gml ON alkis.au_geometrieobjekt_3d USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_koerperobjekt_3d_endet;
--CREATE INDEX au_koerperobjekt_3d_endet ON alkis.au_koerperobjekt_3d USING btree (endet);
CREATE INDEX au_koerperobjekt_3d_endet ON alkis.au_koerperobjekt_3d USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_koerperobjekt_3d_gml;
--CREATE UNIQUE INDEX au_koerperobjekt_3d_gml ON alkis.au_koerperobjekt_3d USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX au_koerperobjekt_3d_gml ON alkis.au_koerperobjekt_3d USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_mehrfachflaechenobjekt_3d_endet;
--CREATE INDEX au_mehrfachflaechenobjekt_3d_endet ON alkis.au_mehrfachflaechenobjekt_3d USING btree (endet);
CREATE INDEX au_mehrfachflaechenobjekt_3d_endet ON alkis.au_mehrfachflaechenobjekt_3d USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_mehrfachflaechenobjekt_3d_gml;
--CREATE UNIQUE INDEX au_mehrfachflaechenobjekt_3d_gml ON alkis.au_mehrfachflaechenobjekt_3d USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX au_mehrfachflaechenobjekt_3d_gml ON alkis.au_mehrfachflaechenobjekt_3d USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_mehrfachlinienobjekt_3d_endet;
--CREATE INDEX au_mehrfachlinienobjekt_3d_endet ON alkis.au_mehrfachlinienobjekt_3d USING btree (endet);
CREATE INDEX au_mehrfachlinienobjekt_3d_endet ON alkis.au_mehrfachlinienobjekt_3d USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_mehrfachlinienobjekt_3d_gml;
--CREATE UNIQUE INDEX au_mehrfachlinienobjekt_3d_gml ON alkis.au_mehrfachlinienobjekt_3d USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX au_mehrfachlinienobjekt_3d_gml ON alkis.au_mehrfachlinienobjekt_3d USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_punkthaufenobjekt_3d_endet;
--CREATE INDEX au_punkthaufenobjekt_3d_endet ON alkis.au_punkthaufenobjekt_3d USING btree (endet);
CREATE INDEX au_punkthaufenobjekt_3d_endet ON alkis.au_punkthaufenobjekt_3d USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_punkthaufenobjekt_3d_gml;
--CREATE UNIQUE INDEX au_punkthaufenobjekt_3d_gml ON alkis.au_punkthaufenobjekt_3d USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX au_punkthaufenobjekt_3d_gml ON alkis.au_punkthaufenobjekt_3d USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_trianguliertesoberflaechenobjekt_3d_endet;
--CREATE INDEX au_trianguliertesoberflaechenobjekt_3d_endet ON alkis.au_trianguliertesoberflaechenobjekt_3d USING btree (endet);
CREATE INDEX au_trianguliertesoberflaechenobjekt_3d_endet ON alkis.au_trianguliertesoberflaechenobjekt_3d USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_trianguliertesoberflaechenobjekt_3d_gml;
--CREATE UNIQUE INDEX au_trianguliertesoberflaechenobjekt_3d_gml ON alkis.au_trianguliertesoberflaechenobjekt_3d USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX au_trianguliertesoberflaechenobjekt_3d_gml ON alkis.au_trianguliertesoberflaechenobjekt_3d USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_umringobjekt_3d_endet;
--CREATE INDEX au_umringobjekt_3d_endet ON alkis.au_umringobjekt_3d USING btree (endet);
CREATE INDEX au_umringobjekt_3d_endet ON alkis.au_umringobjekt_3d USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.au_umringobjekt_3d_gml;
--CREATE UNIQUE INDEX au_umringobjekt_3d_gml ON alkis.au_umringobjekt_3d USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX au_umringobjekt_3d_gml ON alkis.au_umringobjekt_3d USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_abgeleitetehoehenlinie_endet;
--CREATE INDEX ax_abgeleitetehoehenlinie_endet ON alkis.ax_abgeleitetehoehenlinie USING btree (endet);
CREATE INDEX ax_abgeleitetehoehenlinie_endet ON alkis.ax_abgeleitetehoehenlinie USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_abgeleitetehoehenlinie_gml;
--CREATE UNIQUE INDEX ax_abgeleitetehoehenlinie_gml ON alkis.ax_abgeleitetehoehenlinie USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_abgeleitetehoehenlinie_gml ON alkis.ax_abgeleitetehoehenlinie USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_abschnitt_endet;
--CREATE INDEX ax_abschnitt_endet ON alkis.ax_abschnitt USING btree (endet);
CREATE INDEX ax_abschnitt_endet ON alkis.ax_abschnitt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_abschnitt_gml;
--CREATE UNIQUE INDEX ax_abschnitt_gml ON alkis.ax_abschnitt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_abschnitt_gml ON alkis.ax_abschnitt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_anderefestlegungnachstrassenrecht_endet;
--CREATE INDEX ax_anderefestlegungnachstrassenrecht_endet ON alkis.ax_anderefestlegungnachstrassenrecht USING btree (endet);
CREATE INDEX ax_anderefestlegungnachstrassenrecht_endet ON alkis.ax_anderefestlegungnachstrassenrecht USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_anderefestlegungnachstrassenrecht_gml;
--CREATE UNIQUE INDEX ax_anderefestlegungnachstrassenrecht_gml ON alkis.ax_anderefestlegungnachstrassenrecht USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_anderefestlegungnachstrassenrecht_gml ON alkis.ax_anderefestlegungnachstrassenrecht USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_anderefestlegungnachwasserrecht_endet;
--CREATE INDEX ax_anderefestlegungnachwasserrecht_endet ON alkis.ax_anderefestlegungnachwasserrecht USING btree (endet);
CREATE INDEX ax_anderefestlegungnachwasserrecht_endet ON alkis.ax_anderefestlegungnachwasserrecht USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_anderefestlegungnachwasserrecht_gml;
--CREATE UNIQUE INDEX ax_anderefestlegungnachwasserrecht_gml ON alkis.ax_anderefestlegungnachwasserrecht USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_anderefestlegungnachwasserrecht_gml ON alkis.ax_anderefestlegungnachwasserrecht USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_anschrift_endet;
--CREATE INDEX ax_anschrift_endet ON alkis.ax_anschrift USING btree (endet);
CREATE INDEX ax_anschrift_endet ON alkis.ax_anschrift USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_anschrift_gml;
--CREATE UNIQUE INDEX ax_anschrift_gml ON alkis.ax_anschrift USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_anschrift_gml ON alkis.ax_anschrift USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_ast_endet;
--CREATE INDEX ax_ast_endet ON alkis.ax_ast USING btree (endet);
CREATE INDEX ax_ast_endet ON alkis.ax_ast USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_ast_gml;
--CREATE UNIQUE INDEX ax_ast_gml ON alkis.ax_ast USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_ast_gml ON alkis.ax_ast USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_aufnahmepunkt_endet;
--CREATE INDEX ax_aufnahmepunkt_endet ON alkis.ax_aufnahmepunkt USING btree (endet);
CREATE INDEX ax_aufnahmepunkt_endet ON alkis.ax_aufnahmepunkt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_aufnahmepunkt_gml;
--CREATE UNIQUE INDEX ax_aufnahmepunkt_gml ON alkis.ax_aufnahmepunkt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_aufnahmepunkt_gml ON alkis.ax_aufnahmepunkt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_aussparungsflaeche_endet;
--CREATE INDEX ax_aussparungsflaeche_endet ON alkis.ax_aussparungsflaeche USING btree (endet);
CREATE INDEX ax_aussparungsflaeche_endet ON alkis.ax_aussparungsflaeche USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_aussparungsflaeche_gml;
--CREATE UNIQUE INDEX ax_aussparungsflaeche_gml ON alkis.ax_aussparungsflaeche USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_aussparungsflaeche_gml ON alkis.ax_aussparungsflaeche USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bahnstrecke_endet;
--CREATE INDEX ax_bahnstrecke_endet ON alkis.ax_bahnstrecke USING btree (endet);
CREATE INDEX ax_bahnstrecke_endet ON alkis.ax_bahnstrecke USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bahnstrecke_gml;
--CREATE UNIQUE INDEX ax_bahnstrecke_gml ON alkis.ax_bahnstrecke USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bahnstrecke_gml ON alkis.ax_bahnstrecke USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bahnverkehr_endet;
--CREATE INDEX ax_bahnverkehr_endet ON alkis.ax_bahnverkehr USING btree (endet);
CREATE INDEX ax_bahnverkehr_endet ON alkis.ax_bahnverkehr USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bahnverkehr_gml;
--CREATE UNIQUE INDEX ax_bahnverkehr_gml ON alkis.ax_bahnverkehr USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bahnverkehr_gml ON alkis.ax_bahnverkehr USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bahnverkehrsanlage_endet;
--CREATE INDEX ax_bahnverkehrsanlage_endet ON alkis.ax_bahnverkehrsanlage USING btree (endet);
CREATE INDEX ax_bahnverkehrsanlage_endet ON alkis.ax_bahnverkehrsanlage USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bahnverkehrsanlage_gml;
--CREATE UNIQUE INDEX ax_bahnverkehrsanlage_gml ON alkis.ax_bahnverkehrsanlage USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bahnverkehrsanlage_gml ON alkis.ax_bahnverkehrsanlage USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_baublock_endet;
--CREATE INDEX ax_baublock_endet ON alkis.ax_baublock USING btree (endet);
CREATE INDEX ax_baublock_endet ON alkis.ax_baublock USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_baublock_gml;
--CREATE UNIQUE INDEX ax_baublock_gml ON alkis.ax_baublock USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_baublock_gml ON alkis.ax_baublock USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauraumoderbodenordnungsrecht_endet;
--CREATE INDEX ax_bauraumoderbodenordnungsrecht_endet ON alkis.ax_bauraumoderbodenordnungsrecht USING btree (endet);
CREATE INDEX ax_bauraumoderbodenordnungsrecht_endet ON alkis.ax_bauraumoderbodenordnungsrecht USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauraumoderbodenordnungsrecht_gml;
--CREATE UNIQUE INDEX ax_bauraumoderbodenordnungsrecht_gml ON alkis.ax_bauraumoderbodenordnungsrecht USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bauraumoderbodenordnungsrecht_gml ON alkis.ax_bauraumoderbodenordnungsrecht USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauteil_endet;
--CREATE INDEX ax_bauteil_endet ON alkis.ax_bauteil USING btree (endet);
CREATE INDEX ax_bauteil_endet ON alkis.ax_bauteil USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauteil_gml;
--CREATE UNIQUE INDEX ax_bauteil_gml ON alkis.ax_bauteil USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bauteil_gml ON alkis.ax_bauteil USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauwerkimgewaesserbereich_endet;
--CREATE INDEX ax_bauwerkimgewaesserbereich_endet ON alkis.ax_bauwerkimgewaesserbereich USING btree (endet);
CREATE INDEX ax_bauwerkimgewaesserbereich_endet ON alkis.ax_bauwerkimgewaesserbereich USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauwerkimgewaesserbereich_gml;
--CREATE UNIQUE INDEX ax_bauwerkimgewaesserbereich_gml ON alkis.ax_bauwerkimgewaesserbereich USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bauwerkimgewaesserbereich_gml ON alkis.ax_bauwerkimgewaesserbereich USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauwerkimverkehrsbereich_endet;
--CREATE INDEX ax_bauwerkimverkehrsbereich_endet ON alkis.ax_bauwerkimverkehrsbereich USING btree (endet);
CREATE INDEX ax_bauwerkimverkehrsbereich_endet ON alkis.ax_bauwerkimverkehrsbereich USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauwerkimverkehrsbereich_gml;
--CREATE UNIQUE INDEX ax_bauwerkimverkehrsbereich_gml ON alkis.ax_bauwerkimverkehrsbereich USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bauwerkimverkehrsbereich_gml ON alkis.ax_bauwerkimverkehrsbereich USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauwerkoderanlagefuerindustrieundgewerbe_endet;
--CREATE INDEX ax_bauwerkoderanlagefuerindustrieundgewerbe_endet ON alkis.ax_bauwerkoderanlagefuerindustrieundgewerbe USING btree (endet);
CREATE INDEX ax_bauwerkoderanlagefuerindustrieundgewerbe_endet ON alkis.ax_bauwerkoderanlagefuerindustrieundgewerbe USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauwerkoderanlagefuerindustrieundgewerbe_gml;
--CREATE UNIQUE INDEX ax_bauwerkoderanlagefuerindustrieundgewerbe_gml ON alkis.ax_bauwerkoderanlagefuerindustrieundgewerbe USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bauwerkoderanlagefuerindustrieundgewerbe_gml ON alkis.ax_bauwerkoderanlagefuerindustrieundgewerbe USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauwerkoderanlagefuersportfreizeitunderholung_endet;
--CREATE INDEX ax_bauwerkoderanlagefuersportfreizeitunderholung_endet ON alkis.ax_bauwerkoderanlagefuersportfreizeitunderholung USING btree (endet);
CREATE INDEX ax_bauwerkoderanlagefuersportfreizeitunderholung_endet ON alkis.ax_bauwerkoderanlagefuersportfreizeitunderholung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bauwerkoderanlagefuersportfreizeitunderholung_gml;
--CREATE UNIQUE INDEX ax_bauwerkoderanlagefuersportfreizeitunderholung_gml ON alkis.ax_bauwerkoderanlagefuersportfreizeitunderholung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bauwerkoderanlagefuersportfreizeitunderholung_gml ON alkis.ax_bauwerkoderanlagefuersportfreizeitunderholung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_benutzer_endet;
--CREATE INDEX ax_benutzer_endet ON alkis.ax_benutzer USING btree (endet);
CREATE INDEX ax_benutzer_endet ON alkis.ax_benutzer USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_benutzer_gml;
--CREATE UNIQUE INDEX ax_benutzer_gml ON alkis.ax_benutzer USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_benutzer_gml ON alkis.ax_benutzer USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_benutzergruppemitzugriffskontrolle_endet;
--CREATE INDEX ax_benutzergruppemitzugriffskontrolle_endet ON alkis.ax_benutzergruppemitzugriffskontrolle USING btree (endet);
CREATE INDEX ax_benutzergruppemitzugriffskontrolle_endet ON alkis.ax_benutzergruppemitzugriffskontrolle USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_benutzergruppemitzugriffskontrolle_gml;
--CREATE UNIQUE INDEX ax_benutzergruppemitzugriffskontrolle_gml ON alkis.ax_benutzergruppemitzugriffskontrolle USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_benutzergruppemitzugriffskontrolle_gml ON alkis.ax_benutzergruppemitzugriffskontrolle USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_benutzergruppenba_endet;
--CREATE INDEX ax_benutzergruppenba_endet ON alkis.ax_benutzergruppenba USING btree (endet);
CREATE INDEX ax_benutzergruppenba_endet ON alkis.ax_benutzergruppenba USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_benutzergruppenba_gml;
--CREATE UNIQUE INDEX ax_benutzergruppenba_gml ON alkis.ax_benutzergruppenba USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_benutzergruppenba_gml ON alkis.ax_benutzergruppenba USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bergbaubetrieb_endet;
--CREATE INDEX ax_bergbaubetrieb_endet ON alkis.ax_bergbaubetrieb USING btree (endet);
CREATE INDEX ax_bergbaubetrieb_endet ON alkis.ax_bergbaubetrieb USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bergbaubetrieb_gml;
--CREATE UNIQUE INDEX ax_bergbaubetrieb_gml ON alkis.ax_bergbaubetrieb USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bergbaubetrieb_gml ON alkis.ax_bergbaubetrieb USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besondereflurstuecksgrenze_endet;
--CREATE INDEX ax_besondereflurstuecksgrenze_endet ON alkis.ax_besondereflurstuecksgrenze USING btree (endet);
CREATE INDEX ax_besondereflurstuecksgrenze_endet ON alkis.ax_besondereflurstuecksgrenze USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besondereflurstuecksgrenze_gml;
--CREATE UNIQUE INDEX ax_besondereflurstuecksgrenze_gml ON alkis.ax_besondereflurstuecksgrenze USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_besondereflurstuecksgrenze_gml ON alkis.ax_besondereflurstuecksgrenze USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besonderegebaeudelinie_endet;
--CREATE INDEX ax_besonderegebaeudelinie_endet ON alkis.ax_besonderegebaeudelinie USING btree (endet);
CREATE INDEX ax_besonderegebaeudelinie_endet ON alkis.ax_besonderegebaeudelinie USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besonderegebaeudelinie_gml;
--CREATE UNIQUE INDEX ax_besonderegebaeudelinie_gml ON alkis.ax_besonderegebaeudelinie USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_besonderegebaeudelinie_gml ON alkis.ax_besonderegebaeudelinie USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besondererbauwerkspunkt_endet;
--CREATE INDEX ax_besondererbauwerkspunkt_endet ON alkis.ax_besondererbauwerkspunkt USING btree (endet);
CREATE INDEX ax_besondererbauwerkspunkt_endet ON alkis.ax_besondererbauwerkspunkt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besondererbauwerkspunkt_gml;
--CREATE UNIQUE INDEX ax_besondererbauwerkspunkt_gml ON alkis.ax_besondererbauwerkspunkt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_besondererbauwerkspunkt_gml ON alkis.ax_besondererbauwerkspunkt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besonderergebaeudepunkt_endet;
--CREATE INDEX ax_besonderergebaeudepunkt_endet ON alkis.ax_besonderergebaeudepunkt USING btree (endet);
CREATE INDEX ax_besonderergebaeudepunkt_endet ON alkis.ax_besonderergebaeudepunkt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besonderergebaeudepunkt_gml;
--CREATE UNIQUE INDEX ax_besonderergebaeudepunkt_gml ON alkis.ax_besonderergebaeudepunkt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_besonderergebaeudepunkt_gml ON alkis.ax_besonderergebaeudepunkt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besondererhoehenpunkt_endet;
--CREATE INDEX ax_besondererhoehenpunkt_endet ON alkis.ax_besondererhoehenpunkt USING btree (endet);
CREATE INDEX ax_besondererhoehenpunkt_endet ON alkis.ax_besondererhoehenpunkt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besondererhoehenpunkt_gml;
--CREATE UNIQUE INDEX ax_besondererhoehenpunkt_gml ON alkis.ax_besondererhoehenpunkt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_besondererhoehenpunkt_gml ON alkis.ax_besondererhoehenpunkt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besonderertopographischerpunkt_endet;
--CREATE INDEX ax_besonderertopographischerpunkt_endet ON alkis.ax_besonderertopographischerpunkt USING btree (endet);
CREATE INDEX ax_besonderertopographischerpunkt_endet ON alkis.ax_besonderertopographischerpunkt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_besonderertopographischerpunkt_gml;
--CREATE UNIQUE INDEX ax_besonderertopographischerpunkt_gml ON alkis.ax_besonderertopographischerpunkt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_besonderertopographischerpunkt_gml ON alkis.ax_besonderertopographischerpunkt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bewertung_endet;
--CREATE INDEX ax_bewertung_endet ON alkis.ax_bewertung USING btree (endet);
CREATE INDEX ax_bewertung_endet ON alkis.ax_bewertung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bewertung_gml;
--CREATE UNIQUE INDEX ax_bewertung_gml ON alkis.ax_bewertung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bewertung_gml ON alkis.ax_bewertung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bodenschaetzung_endet;
--CREATE INDEX ax_bodenschaetzung_endet ON alkis.ax_bodenschaetzung USING btree (endet);
CREATE INDEX ax_bodenschaetzung_endet ON alkis.ax_bodenschaetzung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bodenschaetzung_gml;
--CREATE UNIQUE INDEX ax_bodenschaetzung_gml ON alkis.ax_bodenschaetzung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bodenschaetzung_gml ON alkis.ax_bodenschaetzung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_boeschungkliff_endet;
--CREATE INDEX ax_boeschungkliff_endet ON alkis.ax_boeschungkliff USING btree (endet);
CREATE INDEX ax_boeschungkliff_endet ON alkis.ax_boeschungkliff USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_boeschungkliff_gml;
--CREATE UNIQUE INDEX ax_boeschungkliff_gml ON alkis.ax_boeschungkliff USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_boeschungkliff_gml ON alkis.ax_boeschungkliff USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_boeschungsflaeche_endet;
--CREATE INDEX ax_boeschungsflaeche_endet ON alkis.ax_boeschungsflaeche USING btree (endet);
CREATE INDEX ax_boeschungsflaeche_endet ON alkis.ax_boeschungsflaeche USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_boeschungsflaeche_gml;
--CREATE UNIQUE INDEX ax_boeschungsflaeche_gml ON alkis.ax_boeschungsflaeche USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_boeschungsflaeche_gml ON alkis.ax_boeschungsflaeche USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_buchungsblatt_endet;
--CREATE INDEX ax_buchungsblatt_endet ON alkis.ax_buchungsblatt USING btree (endet);
CREATE INDEX ax_buchungsblatt_endet ON alkis.ax_buchungsblatt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_buchungsblatt_gml;
--CREATE UNIQUE INDEX ax_buchungsblatt_gml ON alkis.ax_buchungsblatt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_buchungsblatt_gml ON alkis.ax_buchungsblatt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_buchungsblattbezirk_endet;
--CREATE INDEX ax_buchungsblattbezirk_endet ON alkis.ax_buchungsblattbezirk USING btree (endet);
CREATE INDEX ax_buchungsblattbezirk_endet ON alkis.ax_buchungsblattbezirk USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_buchungsblattbezirk_gml;
--CREATE UNIQUE INDEX ax_buchungsblattbezirk_gml ON alkis.ax_buchungsblattbezirk USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_buchungsblattbezirk_gml ON alkis.ax_buchungsblattbezirk USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_buchungsstelle_endet;
--CREATE INDEX ax_buchungsstelle_endet ON alkis.ax_buchungsstelle USING btree (endet);
CREATE INDEX ax_buchungsstelle_endet ON alkis.ax_buchungsstelle USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_buchungsstelle_gml;
--CREATE UNIQUE INDEX ax_buchungsstelle_gml ON alkis.ax_buchungsstelle USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_buchungsstelle_gml ON alkis.ax_buchungsstelle USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bundesland_endet;
--CREATE INDEX ax_bundesland_endet ON alkis.ax_bundesland USING btree (endet);
CREATE INDEX ax_bundesland_endet ON alkis.ax_bundesland USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_bundesland_gml;
--CREATE UNIQUE INDEX ax_bundesland_gml ON alkis.ax_bundesland USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_bundesland_gml ON alkis.ax_bundesland USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_dammwalldeich_endet;
--CREATE INDEX ax_dammwalldeich_endet ON alkis.ax_dammwalldeich USING btree (endet);
CREATE INDEX ax_dammwalldeich_endet ON alkis.ax_dammwalldeich USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_dammwalldeich_gml;
--CREATE UNIQUE INDEX ax_dammwalldeich_gml ON alkis.ax_dammwalldeich USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_dammwalldeich_gml ON alkis.ax_dammwalldeich USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_denkmalschutzrecht_endet;
--CREATE INDEX ax_denkmalschutzrecht_endet ON alkis.ax_denkmalschutzrecht USING btree (endet);
CREATE INDEX ax_denkmalschutzrecht_endet ON alkis.ax_denkmalschutzrecht USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_denkmalschutzrecht_gml;
--CREATE UNIQUE INDEX ax_denkmalschutzrecht_gml ON alkis.ax_denkmalschutzrecht USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_denkmalschutzrecht_gml ON alkis.ax_denkmalschutzrecht USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_duene_endet;
--CREATE INDEX ax_duene_endet ON alkis.ax_duene USING btree (endet);
CREATE INDEX ax_duene_endet ON alkis.ax_duene USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_duene_gml;
--CREATE UNIQUE INDEX ax_duene_gml ON alkis.ax_duene USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_duene_gml ON alkis.ax_duene USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_einrichtungenfuerdenschiffsverkehr_endet;
--CREATE INDEX ax_einrichtungenfuerdenschiffsverkehr_endet ON alkis.ax_einrichtungenfuerdenschiffsverkehr USING btree (endet);
CREATE INDEX ax_einrichtungenfuerdenschiffsverkehr_endet ON alkis.ax_einrichtungenfuerdenschiffsverkehr USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_einrichtungenfuerdenschiffsverkehr_gml;
--CREATE UNIQUE INDEX ax_einrichtungenfuerdenschiffsverkehr_gml ON alkis.ax_einrichtungenfuerdenschiffsverkehr USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_einrichtungenfuerdenschiffsverkehr_gml ON alkis.ax_einrichtungenfuerdenschiffsverkehr USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_einrichtunginoeffentlichenbereichen_endet;
--CREATE INDEX ax_einrichtunginoeffentlichenbereichen_endet ON alkis.ax_einrichtunginoeffentlichenbereichen USING btree (endet);
CREATE INDEX ax_einrichtunginoeffentlichenbereichen_endet ON alkis.ax_einrichtunginoeffentlichenbereichen USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_einrichtunginoeffentlichenbereichen_gml;
--CREATE UNIQUE INDEX ax_einrichtunginoeffentlichenbereichen_gml ON alkis.ax_einrichtunginoeffentlichenbereichen USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_einrichtunginoeffentlichenbereichen_gml ON alkis.ax_einrichtunginoeffentlichenbereichen USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_einschnitt_endet;
--CREATE INDEX ax_einschnitt_endet ON alkis.ax_einschnitt USING btree (endet);
CREATE INDEX ax_einschnitt_endet ON alkis.ax_einschnitt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_einschnitt_gml;
--CREATE UNIQUE INDEX ax_einschnitt_gml ON alkis.ax_einschnitt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_einschnitt_gml ON alkis.ax_einschnitt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fahrbahnachse_endet;
--CREATE INDEX ax_fahrbahnachse_endet ON alkis.ax_fahrbahnachse USING btree (endet);
CREATE INDEX ax_fahrbahnachse_endet ON alkis.ax_fahrbahnachse USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fahrbahnachse_gml;
--CREATE UNIQUE INDEX ax_fahrbahnachse_gml ON alkis.ax_fahrbahnachse USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_fahrbahnachse_gml ON alkis.ax_fahrbahnachse USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fahrwegachse_endet;
--CREATE INDEX ax_fahrwegachse_endet ON alkis.ax_fahrwegachse USING btree (endet);
CREATE INDEX ax_fahrwegachse_endet ON alkis.ax_fahrwegachse USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fahrwegachse_gml;
--CREATE UNIQUE INDEX ax_fahrwegachse_gml ON alkis.ax_fahrwegachse USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_fahrwegachse_gml ON alkis.ax_fahrwegachse USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_felsenfelsblockfelsnadel_endet;
--CREATE INDEX ax_felsenfelsblockfelsnadel_endet ON alkis.ax_felsenfelsblockfelsnadel USING btree (endet);
CREATE INDEX ax_felsenfelsblockfelsnadel_endet ON alkis.ax_felsenfelsblockfelsnadel USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_felsenfelsblockfelsnadel_gml;
--CREATE UNIQUE INDEX ax_felsenfelsblockfelsnadel_gml ON alkis.ax_felsenfelsblockfelsnadel USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_felsenfelsblockfelsnadel_gml ON alkis.ax_felsenfelsblockfelsnadel USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_firstlinie_endet;
--CREATE INDEX ax_firstlinie_endet ON alkis.ax_firstlinie USING btree (endet);
CREATE INDEX ax_firstlinie_endet ON alkis.ax_firstlinie USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_firstlinie_gml;
--CREATE UNIQUE INDEX ax_firstlinie_gml ON alkis.ax_firstlinie USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_firstlinie_gml ON alkis.ax_firstlinie USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flaechebesondererfunktionalerpraegung_endet;
--CREATE INDEX ax_flaechebesondererfunktionalerpraegung_endet ON alkis.ax_flaechebesondererfunktionalerpraegung USING btree (endet);
CREATE INDEX ax_flaechebesondererfunktionalerpraegung_endet ON alkis.ax_flaechebesondererfunktionalerpraegung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flaechebesondererfunktionalerpraegung_gml;
--CREATE UNIQUE INDEX ax_flaechebesondererfunktionalerpraegung_gml ON alkis.ax_flaechebesondererfunktionalerpraegung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_flaechebesondererfunktionalerpraegung_gml ON alkis.ax_flaechebesondererfunktionalerpraegung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flaechegemischternutzung_endet;
--CREATE INDEX ax_flaechegemischternutzung_endet ON alkis.ax_flaechegemischternutzung USING btree (endet);
CREATE INDEX ax_flaechegemischternutzung_endet ON alkis.ax_flaechegemischternutzung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flaechegemischternutzung_gml;
--CREATE UNIQUE INDEX ax_flaechegemischternutzung_gml ON alkis.ax_flaechegemischternutzung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_flaechegemischternutzung_gml ON alkis.ax_flaechegemischternutzung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flaechezurzeitunbestimmbar_endet;
--CREATE INDEX ax_flaechezurzeitunbestimmbar_endet ON alkis.ax_flaechezurzeitunbestimmbar USING btree (endet);
CREATE INDEX ax_flaechezurzeitunbestimmbar_endet ON alkis.ax_flaechezurzeitunbestimmbar USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flaechezurzeitunbestimmbar_gml;
--CREATE UNIQUE INDEX ax_flaechezurzeitunbestimmbar_gml ON alkis.ax_flaechezurzeitunbestimmbar USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_flaechezurzeitunbestimmbar_gml ON alkis.ax_flaechezurzeitunbestimmbar USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fliessgewaesser_endet;
--CREATE INDEX ax_fliessgewaesser_endet ON alkis.ax_fliessgewaesser USING btree (endet);
CREATE INDEX ax_fliessgewaesser_endet ON alkis.ax_fliessgewaesser USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fliessgewaesser_gml;
--CREATE UNIQUE INDEX ax_fliessgewaesser_gml ON alkis.ax_fliessgewaesser USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_fliessgewaesser_gml ON alkis.ax_fliessgewaesser USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flugverkehr_endet;
--CREATE INDEX ax_flugverkehr_endet ON alkis.ax_flugverkehr USING btree (endet);
CREATE INDEX ax_flugverkehr_endet ON alkis.ax_flugverkehr USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flugverkehr_gml;
--CREATE UNIQUE INDEX ax_flugverkehr_gml ON alkis.ax_flugverkehr USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_flugverkehr_gml ON alkis.ax_flugverkehr USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flugverkehrsanlage_endet;
--CREATE INDEX ax_flugverkehrsanlage_endet ON alkis.ax_flugverkehrsanlage USING btree (endet);
CREATE INDEX ax_flugverkehrsanlage_endet ON alkis.ax_flugverkehrsanlage USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flugverkehrsanlage_gml;
--CREATE UNIQUE INDEX ax_flugverkehrsanlage_gml ON alkis.ax_flugverkehrsanlage USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_flugverkehrsanlage_gml ON alkis.ax_flugverkehrsanlage USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_forstrecht_endet;
--CREATE INDEX ax_forstrecht_endet ON alkis.ax_forstrecht USING btree (endet);
CREATE INDEX ax_forstrecht_endet ON alkis.ax_forstrecht USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_forstrecht_gml;
--CREATE UNIQUE INDEX ax_forstrecht_gml ON alkis.ax_forstrecht USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_forstrecht_gml ON alkis.ax_forstrecht USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fortfuehrungsfall_endet;
--CREATE INDEX ax_fortfuehrungsfall_endet ON alkis.ax_fortfuehrungsfall USING btree (endet);
CREATE INDEX ax_fortfuehrungsfall_endet ON alkis.ax_fortfuehrungsfall USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fortfuehrungsfall_gml;
--CREATE UNIQUE INDEX ax_fortfuehrungsfall_gml ON alkis.ax_fortfuehrungsfall USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_fortfuehrungsfall_gml ON alkis.ax_fortfuehrungsfall USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fortfuehrungsnachweisdeckblatt_endet;
--CREATE INDEX ax_fortfuehrungsnachweisdeckblatt_endet ON alkis.ax_fortfuehrungsnachweisdeckblatt USING btree (endet);
CREATE INDEX ax_fortfuehrungsnachweisdeckblatt_endet ON alkis.ax_fortfuehrungsnachweisdeckblatt USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_fortfuehrungsnachweisdeckblatt_gml;
--CREATE UNIQUE INDEX ax_fortfuehrungsnachweisdeckblatt_gml ON alkis.ax_fortfuehrungsnachweisdeckblatt USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_fortfuehrungsnachweisdeckblatt_gml ON alkis.ax_fortfuehrungsnachweisdeckblatt USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_friedhof_endet;
--CREATE INDEX ax_friedhof_endet ON alkis.ax_friedhof USING btree (endet);
CREATE INDEX ax_friedhof_endet ON alkis.ax_friedhof USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_friedhof_gml;
--CREATE UNIQUE INDEX ax_friedhof_gml ON alkis.ax_friedhof USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_friedhof_gml ON alkis.ax_friedhof USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebaeude_endet;
--CREATE INDEX ax_gebaeude_endet ON alkis.ax_gebaeude USING btree (endet);
CREATE INDEX ax_gebaeude_endet ON alkis.ax_gebaeude USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebaeude_gml;
--CREATE UNIQUE INDEX ax_gebaeude_gml ON alkis.ax_gebaeude USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gebaeude_gml ON alkis.ax_gebaeude USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebaeudeausgestaltung_endet;
--CREATE INDEX ax_gebaeudeausgestaltung_endet ON alkis.ax_gebaeudeausgestaltung USING btree (endet);
CREATE INDEX ax_gebaeudeausgestaltung_endet ON alkis.ax_gebaeudeausgestaltung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebaeudeausgestaltung_gml;
--CREATE UNIQUE INDEX ax_gebaeudeausgestaltung_gml ON alkis.ax_gebaeudeausgestaltung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gebaeudeausgestaltung_gml ON alkis.ax_gebaeudeausgestaltung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_bundesland_endet;
--CREATE INDEX ax_gebiet_bundesland_endet ON alkis.ax_gebiet_bundesland USING btree (endet);
CREATE INDEX ax_gebiet_bundesland_endet ON alkis.ax_gebiet_bundesland USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_bundesland_gml;
--CREATE UNIQUE INDEX ax_gebiet_bundesland_gml ON alkis.ax_gebiet_bundesland USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gebiet_bundesland_gml ON alkis.ax_gebiet_bundesland USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_kreis_endet;
--CREATE INDEX ax_gebiet_kreis_endet ON alkis.ax_gebiet_kreis USING btree (endet);
CREATE INDEX ax_gebiet_kreis_endet ON alkis.ax_gebiet_kreis USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_kreis_gml;
--CREATE UNIQUE INDEX ax_gebiet_kreis_gml ON alkis.ax_gebiet_kreis USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gebiet_kreis_gml ON alkis.ax_gebiet_kreis USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_nationalstaat_endet;
--CREATE INDEX ax_gebiet_nationalstaat_endet ON alkis.ax_gebiet_nationalstaat USING btree (endet);
CREATE INDEX ax_gebiet_nationalstaat_endet ON alkis.ax_gebiet_nationalstaat USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_nationalstaat_gml;
--CREATE UNIQUE INDEX ax_gebiet_nationalstaat_gml ON alkis.ax_gebiet_nationalstaat USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gebiet_nationalstaat_gml ON alkis.ax_gebiet_nationalstaat USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_regierungsbezirk_endet;
--CREATE INDEX ax_gebiet_regierungsbezirk_endet ON alkis.ax_gebiet_regierungsbezirk USING btree (endet);
CREATE INDEX ax_gebiet_regierungsbezirk_endet ON alkis.ax_gebiet_regierungsbezirk USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_regierungsbezirk_gml;
--CREATE UNIQUE INDEX ax_gebiet_regierungsbezirk_gml ON alkis.ax_gebiet_regierungsbezirk USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gebiet_regierungsbezirk_gml ON alkis.ax_gebiet_regierungsbezirk USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_verwaltungsgemeinschaft_endet;
--CREATE INDEX ax_gebiet_verwaltungsgemeinschaft_endet ON alkis.ax_gebiet_verwaltungsgemeinschaft USING btree (endet);
CREATE INDEX ax_gebiet_verwaltungsgemeinschaft_endet ON alkis.ax_gebiet_verwaltungsgemeinschaft USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebiet_verwaltungsgemeinschaft_gml;
--CREATE UNIQUE INDEX ax_gebiet_verwaltungsgemeinschaft_gml ON alkis.ax_gebiet_verwaltungsgemeinschaft USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gebiet_verwaltungsgemeinschaft_gml ON alkis.ax_gebiet_verwaltungsgemeinschaft USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebietsgrenze_endet;
--CREATE INDEX ax_gebietsgrenze_endet ON alkis.ax_gebietsgrenze USING btree (endet);
CREATE INDEX ax_gebietsgrenze_endet ON alkis.ax_gebietsgrenze USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gebietsgrenze_gml;
--CREATE UNIQUE INDEX ax_gebietsgrenze_gml ON alkis.ax_gebietsgrenze USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gebietsgrenze_gml ON alkis.ax_gebietsgrenze USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gehoelz_endet;
--CREATE INDEX ax_gehoelz_endet ON alkis.ax_gehoelz USING btree (endet);
CREATE INDEX ax_gehoelz_endet ON alkis.ax_gehoelz USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gehoelz_gml;
--CREATE UNIQUE INDEX ax_gehoelz_gml ON alkis.ax_gehoelz USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gehoelz_gml ON alkis.ax_gehoelz USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gelaendekante_endet;
--CREATE INDEX ax_gelaendekante_endet ON alkis.ax_gelaendekante USING btree (endet);
CREATE INDEX ax_gelaendekante_endet ON alkis.ax_gelaendekante USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gelaendekante_gml;
--CREATE UNIQUE INDEX ax_gelaendekante_gml ON alkis.ax_gelaendekante USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gelaendekante_gml ON alkis.ax_gelaendekante USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gemarkungsteilflur_endet;
--CREATE INDEX ax_gemarkungsteilflur_endet ON alkis.ax_gemarkungsteilflur USING btree (endet);
CREATE INDEX ax_gemarkungsteilflur_endet ON alkis.ax_gemarkungsteilflur USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gemarkungsteilflur_gml;
--CREATE UNIQUE INDEX ax_gemarkungsteilflur_gml ON alkis.ax_gemarkungsteilflur USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gemarkungsteilflur_gml ON alkis.ax_gemarkungsteilflur USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gemeindeteil_endet;
--CREATE INDEX ax_gemeindeteil_endet ON alkis.ax_gemeindeteil USING btree (endet);
CREATE INDEX ax_gemeindeteil_endet ON alkis.ax_gemeindeteil USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gemeindeteil_gml;
--CREATE UNIQUE INDEX ax_gemeindeteil_gml ON alkis.ax_gemeindeteil USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gemeindeteil_gml ON alkis.ax_gemeindeteil USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_georeferenziertegebaeudeadresse_endet;
--CREATE INDEX ax_georeferenziertegebaeudeadresse_endet ON alkis.ax_georeferenziertegebaeudeadresse USING btree (endet);
CREATE INDEX ax_georeferenziertegebaeudeadresse_endet ON alkis.ax_georeferenziertegebaeudeadresse USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_georeferenziertegebaeudeadresse_gml;
--CREATE UNIQUE INDEX ax_georeferenziertegebaeudeadresse_gml ON alkis.ax_georeferenziertegebaeudeadresse USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_georeferenziertegebaeudeadresse_gml ON alkis.ax_georeferenziertegebaeudeadresse USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_geripplinie_endet;
--CREATE INDEX ax_geripplinie_endet ON alkis.ax_geripplinie USING btree (endet);
CREATE INDEX ax_geripplinie_endet ON alkis.ax_geripplinie USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_geripplinie_gml;
--CREATE UNIQUE INDEX ax_geripplinie_gml ON alkis.ax_geripplinie USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_geripplinie_gml ON alkis.ax_geripplinie USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewaesserachse_endet;
--CREATE INDEX ax_gewaesserachse_endet ON alkis.ax_gewaesserachse USING btree (endet);
CREATE INDEX ax_gewaesserachse_endet ON alkis.ax_gewaesserachse USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewaesserachse_gml;
--CREATE UNIQUE INDEX ax_gewaesserachse_gml ON alkis.ax_gewaesserachse USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gewaesserachse_gml ON alkis.ax_gewaesserachse USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewaesserbegrenzung_endet;
--CREATE INDEX ax_gewaesserbegrenzung_endet ON alkis.ax_gewaesserbegrenzung USING btree (endet);
CREATE INDEX ax_gewaesserbegrenzung_endet ON alkis.ax_gewaesserbegrenzung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewaesserbegrenzung_gml;
--CREATE UNIQUE INDEX ax_gewaesserbegrenzung_gml ON alkis.ax_gewaesserbegrenzung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gewaesserbegrenzung_gml ON alkis.ax_gewaesserbegrenzung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewaessermerkmal_endet;
--CREATE INDEX ax_gewaessermerkmal_endet ON alkis.ax_gewaessermerkmal USING btree (endet);
CREATE INDEX ax_gewaessermerkmal_endet ON alkis.ax_gewaessermerkmal USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewaessermerkmal_gml;
--CREATE UNIQUE INDEX ax_gewaessermerkmal_gml ON alkis.ax_gewaessermerkmal USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gewaessermerkmal_gml ON alkis.ax_gewaessermerkmal USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewaesserstationierungsachse_endet;
--CREATE INDEX ax_gewaesserstationierungsachse_endet ON alkis.ax_gewaesserstationierungsachse USING btree (endet);
CREATE INDEX ax_gewaesserstationierungsachse_endet ON alkis.ax_gewaesserstationierungsachse USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewaesserstationierungsachse_gml;
--CREATE UNIQUE INDEX ax_gewaesserstationierungsachse_gml ON alkis.ax_gewaesserstationierungsachse USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gewaesserstationierungsachse_gml ON alkis.ax_gewaesserstationierungsachse USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewann_endet;
--CREATE INDEX ax_gewann_endet ON alkis.ax_gewann USING btree (endet);
CREATE INDEX ax_gewann_endet ON alkis.ax_gewann USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gewann_gml;
--CREATE UNIQUE INDEX ax_gewann_gml ON alkis.ax_gewann USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gewann_gml ON alkis.ax_gewann USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gleis_endet;
--CREATE INDEX ax_gleis_endet ON alkis.ax_gleis USING btree (endet);
CREATE INDEX ax_gleis_endet ON alkis.ax_gleis USING btree (endet DESC, beginnt DESC);

COMMIT;

BEGIN;

DROP INDEX IF EXISTS alkis.ax_flurstueck_kennz;
--CREATE INDEX ax_flurstueck_kennz ON alkis.ax_flurstueck USING btree (flurstueckskennzeichen, beginnt DESC);
CREATE INDEX ax_flurstueck_kennz ON alkis.ax_flurstueck USING btree (flurstueckskennzeichen text_pattern_ops, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_flurstueck_gml;
--CREATE UNIQUE INDEX ax_flurstueck_gml ON alkis.ax_flurstueck USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_flurstueck_gml ON alkis.ax_flurstueck USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_historischesflurstueckohneraumbezug_kennz;
CREATE INDEX ax_historischesflurstueckohneraumbezug_kennz ON alkis.ax_historischesflurstueckohneraumbezug USING btree (flurstueckskennzeichen text_pattern_ops, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_historischesflurstueckohneraumbezug_lgfzn;
CREATE INDEX ax_historischesflurstueckohneraumbezug_lgfzn ON alkis.ax_historischesflurstueckohneraumbezug USING btree (land, gemarkungsnummer, flurnummer, zaehler, nenner);

ALTER TABLE alkis.n_nutzung DROP CONSTRAINT IF EXISTS n_nutzung_pk;

DROP INDEX IF EXISTS alkis.n_nutzung_pk;
--CREATE UNIQUE INDEX n_nutzung_pk ON alkis.n_nutzung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX n_nutzung_pk ON alkis.n_nutzung USING btree (gml_id, endet DESC, beginnt DESC);

COMMIT;

BEGIN;

DROP INDEX IF EXISTS alkis.ax_gemarkung_endet;
--CREATE INDEX ax_gemarkung_endet ON alkis.ax_gemarkung USING btree (endet);
CREATE INDEX ax_gemarkung_endet ON alkis.ax_gemarkung USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gemarkung_gml;
--CREATE UNIQUE INDEX ax_gemarkung_gml ON alkis.ax_gemarkung USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gemarkung_gml ON alkis.ax_gemarkung USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gemeinde_endet;
--CREATE INDEX ax_gemeinde_endet ON alkis.ax_gemeinde USING btree (endet);
CREATE INDEX ax_gemeinde_endet ON alkis.ax_gemeinde USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_gemeinde_gml;
--CREATE UNIQUE INDEX ax_gemeinde_gml ON alkis.ax_gemeinde USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_gemeinde_gml ON alkis.ax_gemeinde USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_dienststelle_endet;
--CREATE INDEX ax_dienststelle_endet ON alkis.ax_dienststelle USING btree (endet);
CREATE INDEX ax_dienststelle_endet ON alkis.ax_dienststelle USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.ax_dienststelle_gml;
--CREATE UNIQUE INDEX ax_dienststelle_gml ON alkis.ax_dienststelle USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX ax_dienststelle_gml ON alkis.ax_dienststelle USING btree (gml_id, endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_antrag_endet;
--CREATE INDEX aa_antrag_endet ON alkis.aa_antrag USING btree (endet);
CREATE INDEX aa_antrag_endet ON alkis.aa_antrag USING btree (endet DESC, beginnt DESC);

DROP INDEX IF EXISTS alkis.aa_antrag_gml;
--CREATE UNIQUE INDEX aa_antrag_gml ON alkis.aa_antrag USING btree (gml_id, beginnt);
CREATE UNIQUE INDEX aa_antrag_gml ON alkis.aa_antrag USING btree (gml_id, endet DESC, beginnt DESC);

COMMIT;

