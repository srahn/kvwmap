BEGIN;

CREATE INDEX bp_abgrabungsflaeche_gist
    ON xplan_gml.bp_abgrabungsflaeche USING gist
    ("position");
CREATE INDEX bp_abstandsflaeche_gist
    ON xplan_gml.bp_abstandsflaeche USING gist
    ("position");
CREATE INDEX bp_abstandsmass_gist
    ON xplan_gml.bp_abstandsmass USING gist
    ("position");
CREATE INDEX bp_abweichungvonbaugrenze_gist
    ON xplan_gml.bp_abweichungvonbaugrenze USING gist
    ("position");
CREATE INDEX bp_abweichungvonueberbauberergrundstuecksflaeche_gist
    ON xplan_gml.bp_abweichungvonueberbauberergrundstuecksflaeche USING gist
    ("position");
CREATE INDEX bp_anpflanzungbindungerhaltung_gist
    ON xplan_gml.bp_anpflanzungbindungerhaltung USING gist
    ("position");
CREATE INDEX bp_aufschuettungsflaeche_gist
    ON xplan_gml.bp_aufschuettungsflaeche USING gist
    ("position");
CREATE INDEX bp_ausgleichsflaeche_gist
    ON xplan_gml.bp_ausgleichsflaeche USING gist
    ("position");
CREATE INDEX bp_ausgleichsmassnahme_gist
    ON xplan_gml.bp_ausgleichsmassnahme USING gist
    ("position");
CREATE INDEX bp_baugebietsteilflaeche_gist
    ON xplan_gml.bp_baugebietsteilflaeche USING gist
    ("position");
CREATE INDEX bp_baugrenze_gist
    ON xplan_gml.bp_baugrenze USING gist
    ("position");
CREATE INDEX bp_baulinie_gist
    ON xplan_gml.bp_baulinie USING gist
    ("position");
CREATE INDEX bp_bereich_gist
    ON xplan_gml.bp_bereich USING gist
    ("geltungsbereich");
CREATE INDEX bp_bereichohneeinausfahrtlinie_gist
    ON xplan_gml.bp_bereichohneeinausfahrtlinie USING gist
    ("position");
CREATE INDEX bp_besonderernutzungszweckflaeche_gist
    ON xplan_gml.bp_besonderernutzungszweckflaeche USING gist
    ("position");
CREATE INDEX bp_bodenschaetzeflaeche_gist
    ON xplan_gml.bp_bodenschaetzeflaeche USING gist
    ("position");
CREATE INDEX bp_einfahrtpunkt_gist
    ON xplan_gml.bp_einfahrtpunkt USING gist
    ("position");
CREATE INDEX bp_einfahrtsbereichlinie_gist
    ON xplan_gml.bp_einfahrtsbereichlinie USING gist
    ("position");
CREATE INDEX bp_eingriffsbereich_gist
    ON xplan_gml.bp_eingriffsbereich USING gist
    ("position");
CREATE INDEX bp_erhaltungsbereichflaeche_gist
    ON xplan_gml.bp_erhaltungsbereichflaeche USING gist
    ("position");
CREATE INDEX bp_festsetzungnachlandesrecht_gist
    ON xplan_gml.bp_festsetzungnachlandesrecht USING gist
    ("position");
CREATE INDEX bp_firstrichtungslinie_gist
    ON xplan_gml.bp_firstrichtungslinie USING gist
    ("position");
CREATE INDEX bp_flaechenobjekt_gist
    ON xplan_gml.bp_flaechenobjekt USING gist
    ("position");
CREATE INDEX bp_flaechenschlussobjekt_gist
    ON xplan_gml.bp_flaechenschlussobjekt USING gist
    ("position");
CREATE INDEX bp_flaecheohnefestsetzung_gist
    ON xplan_gml.bp_flaecheohnefestsetzung USING gist
    ("position");
CREATE INDEX bp_foerderungsflaeche_gist
    ON xplan_gml.bp_foerderungsflaeche USING gist
    ("position");
CREATE INDEX bp_freiflaeche_gist
    ON xplan_gml.bp_freiflaeche USING gist
    ("position");
CREATE INDEX bp_gebaeudeflaeche_gist
    ON xplan_gml.bp_gebaeudeflaeche USING gist
    ("position");
CREATE INDEX bp_gemeinbedarfsflaeche_gist
    ON xplan_gml.bp_gemeinbedarfsflaeche USING gist
    ("position");
CREATE INDEX bp_gemeinschaftsanlagenflaeche_gist
    ON xplan_gml.bp_gemeinschaftsanlagenflaeche USING gist
    ("position");
CREATE INDEX bp_gemeinschaftsanlagenzuordnung_gist
    ON xplan_gml.bp_gemeinschaftsanlagenzuordnung USING gist
    ("position");
CREATE INDEX bp_generischesobjekt_gist
    ON xplan_gml.bp_generischesobjekt USING gist
    ("position");
CREATE INDEX bp_geometrieobjekt_gist
    ON xplan_gml.bp_geometrieobjekt USING gist
    ("position");
CREATE INDEX bp_gewaesserflaeche_gist
    ON xplan_gml.bp_gewaesserflaeche USING gist
    ("position");
CREATE INDEX bp_gruenflaeche_gist
    ON xplan_gml.bp_gruenflaeche USING gist
    ("position");
CREATE INDEX bp_hoehenmass_gist
    ON xplan_gml.bp_hoehenmass USING gist
    ("position");
CREATE INDEX bp_immissionsschutz_gist
    ON xplan_gml.bp_immissionsschutz USING gist
    ("position");
CREATE INDEX bp_kennzeichnungsflaeche_gist
    ON xplan_gml.bp_kennzeichnungsflaeche USING gist
    ("position");
CREATE INDEX bp_kleintierhaltungflaeche_gist
    ON xplan_gml.bp_kleintierhaltungflaeche USING gist
    ("position");
CREATE INDEX bp_landwirtschaft_gist
    ON xplan_gml.bp_landwirtschaft USING gist
    ("position");
CREATE INDEX bp_landwirtschaftsflaeche_gist
    ON xplan_gml.bp_landwirtschaftsflaeche USING gist
    ("position");
CREATE INDEX bp_linienobjekt_gist
    ON xplan_gml.bp_linienobjekt USING gist
    ("position");
CREATE INDEX bp_nebenanlagenausschlussflaeche_gist
    ON xplan_gml.bp_nebenanlagenausschlussflaeche USING gist
    ("position");
CREATE INDEX bp_nebenanlagenflaeche_gist
    ON xplan_gml.bp_nebenanlagenflaeche USING gist
    ("position");
CREATE INDEX bp_nichtueberbaubaregrundstuecksflaeche_gist
    ON xplan_gml.bp_nichtueberbaubaregrundstuecksflaeche USING gist
    ("position");
CREATE INDEX bp_nutzungsartengrenze_gist
    ON xplan_gml.bp_nutzungsartengrenze USING gist
    ("position");
CREATE INDEX bp_persgruppenbestimmteflaeche_gist
    ON xplan_gml.bp_persgruppenbestimmteflaeche USING gist
    ("position");
CREATE INDEX bp_plan_gist
    ON xplan_gml.bp_plan USING gist
    ("raeumlichergeltungsbereich");
CREATE INDEX bp_punktobjekt_gist
    ON xplan_gml.bp_punktobjekt USING gist
    ("position");
CREATE INDEX bp_regelungvergnuegungsstaetten_gist
    ON xplan_gml.bp_regelungvergnuegungsstaetten USING gist
    ("position");
CREATE INDEX bp_rekultivierungsflaeche_gist
    ON xplan_gml.bp_rekultivierungsflaeche USING gist
    ("position");
CREATE INDEX bp_richtungssektorgrenze_gist
    ON xplan_gml.bp_richtungssektorgrenze USING gist
    ("position");
CREATE INDEX bp_schutzpflegeentwicklungsflaeche_gist
    ON xplan_gml.bp_schutzpflegeentwicklungsflaeche USING gist
    ("position");
CREATE INDEX bp_schutzpflegeentwicklungsmassnahme_gist
    ON xplan_gml.bp_schutzpflegeentwicklungsmassnahme USING gist
    ("position");
CREATE INDEX bp_sichtflaeche_gist
    ON xplan_gml.bp_sichtflaeche USING gist
    ("position");
CREATE INDEX bp_speziellebauweise_gist
    ON xplan_gml.bp_speziellebauweise USING gist
    ("position");
CREATE INDEX bp_spielsportanlagenflaeche_gist
    ON xplan_gml.bp_spielsportanlagenflaeche USING gist
    ("position");
CREATE INDEX bp_strassenbegrenzungslinie_gist
    ON xplan_gml.bp_strassenbegrenzungslinie USING gist
    ("position");
CREATE INDEX bp_strassenkoerper_gist
    ON xplan_gml.bp_strassenkoerper USING gist
    ("position");
CREATE INDEX bp_strassenverkehrsflaeche_gist
    ON xplan_gml.bp_strassenverkehrsflaeche USING gist
    ("position");
CREATE INDEX bp_technischemassnahmenflaeche_gist
    ON xplan_gml.bp_technischemassnahmenflaeche USING gist
    ("position");
CREATE INDEX bp_textlichefestsetzungsflaeche_gist
    ON xplan_gml.bp_textlichefestsetzungsflaeche USING gist
    ("position");
CREATE INDEX bp_ueberbaubaregrundstuecksflaeche_gist
    ON xplan_gml.bp_ueberbaubaregrundstuecksflaeche USING gist
    ("position");
CREATE INDEX bp_ueberlagerungsobjekt_gist
    ON xplan_gml.bp_ueberlagerungsobjekt USING gist
    ("position");
CREATE INDEX bp_unverbindlichevormerkung_gist
    ON xplan_gml.bp_unverbindlichevormerkung USING gist
    ("position");
CREATE INDEX bp_veraenderungssperre_gist
    ON xplan_gml.bp_veraenderungssperre USING gist
    ("position");
CREATE INDEX bp_verentsorgung_gist
    ON xplan_gml.bp_verentsorgung USING gist
    ("position");
CREATE INDEX bp_verkehrsflaechebesondererzweckbestimmung_gist
    ON xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung USING gist
    ("position");
CREATE INDEX bp_waldflaeche_gist
    ON xplan_gml.bp_waldflaeche USING gist
    ("position");
CREATE INDEX bp_wasserwirtschaftsflaeche_gist
    ON xplan_gml.bp_wasserwirtschaftsflaeche USING gist
    ("position");
CREATE INDEX bp_wegerecht_gist
    ON xplan_gml.bp_wegerecht USING gist
    ("position");
CREATE INDEX bp_wohngebaeudeflaeche_gist
    ON xplan_gml.bp_wohngebaeudeflaeche USING gist
    ("position");
CREATE INDEX bp_zentralerversorgungsbereich_gist
    ON xplan_gml.bp_zentralerversorgungsbereich USING gist
    ("position");
CREATE INDEX bp_zusatzkontingentlaerm_gist
    ON xplan_gml.bp_zusatzkontingentlaerm USING gist
    ("position");
CREATE INDEX bp_zusatzkontingentlaermflaeche_gist
    ON xplan_gml.bp_zusatzkontingentlaermflaeche USING gist
    ("position");
CREATE INDEX fp_abgrabung_gist
    ON xplan_gml.fp_abgrabung USING gist
    ("position");
CREATE INDEX fp_anpassungklimawandel_gist
    ON xplan_gml.fp_anpassungklimawandel USING gist
    ("position");
CREATE INDEX fp_aufschuettung_gist
    ON xplan_gml.fp_aufschuettung USING gist
    ("position");
CREATE INDEX fp_ausgleichsflaeche_gist
    ON xplan_gml.fp_ausgleichsflaeche USING gist
    ("position");
CREATE INDEX fp_bebauungsflaeche_gist
    ON xplan_gml.fp_bebauungsflaeche USING gist
    ("position");
CREATE INDEX fp_bereich_gist
    ON xplan_gml.fp_bereich USING gist
    ("geltungsbereich");
CREATE INDEX fp_bodenschaetze_gist
    ON xplan_gml.fp_bodenschaetze USING gist
    ("position");
CREATE INDEX fp_flaechenobjekt_gist
    ON xplan_gml.fp_flaechenobjekt USING gist
    ("position");
CREATE INDEX fp_flaechenschlussobjekt_gist
    ON xplan_gml.fp_flaechenschlussobjekt USING gist
    ("position");
CREATE INDEX fp_gemeinbedarf_gist
    ON xplan_gml.fp_gemeinbedarf USING gist
    ("position");
CREATE INDEX fp_generischesobjekt_gist
    ON xplan_gml.fp_generischesobjekt USING gist
    ("position");
CREATE INDEX fp_geometrieobjekt_gist
    ON xplan_gml.fp_geometrieobjekt USING gist
    ("position");
CREATE INDEX fp_gewaesser_gist
    ON xplan_gml.fp_gewaesser USING gist
    ("position");
CREATE INDEX fp_gruen_gist
    ON xplan_gml.fp_gruen USING gist
    ("position");
CREATE INDEX fp_keinezentrabwasserbeseitigungflaeche_gist
    ON xplan_gml.fp_keinezentrabwasserbeseitigungflaeche USING gist
    ("position");
CREATE INDEX fp_kennzeichnung_gist
    ON xplan_gml.fp_kennzeichnung USING gist
    ("position");
CREATE INDEX fp_landwirtschaft_gist
    ON xplan_gml.fp_landwirtschaft USING gist
    ("position");
CREATE INDEX fp_landwirtschaftsflaeche_gist
    ON xplan_gml.fp_landwirtschaftsflaeche USING gist
    ("position");
CREATE INDEX fp_linienobjekt_gist
    ON xplan_gml.fp_linienobjekt USING gist
    ("position");
CREATE INDEX fp_nutzungsbeschraenkungsflaeche_gist
    ON xplan_gml.fp_nutzungsbeschraenkungsflaeche USING gist
    ("position");
CREATE INDEX fp_plan_gist
    ON xplan_gml.fp_plan USING gist
    ("raeumlichergeltungsbereich");
CREATE INDEX fp_privilegiertesvorhaben_gist
    ON xplan_gml.fp_privilegiertesvorhaben USING gist
    ("position");
CREATE INDEX fp_punktobjekt_gist
    ON xplan_gml.fp_punktobjekt USING gist
    ("position");
CREATE INDEX fp_schutzpflegeentwicklung_gist
    ON xplan_gml.fp_schutzpflegeentwicklung USING gist
    ("position");
CREATE INDEX fp_spielsportanlage_gist
    ON xplan_gml.fp_spielsportanlage USING gist
    ("position");
CREATE INDEX fp_strassenverkehr_gist
    ON xplan_gml.fp_strassenverkehr USING gist
    ("position");
CREATE INDEX fp_textlichedarstellungsflaeche_gist
    ON xplan_gml.fp_textlichedarstellungsflaeche USING gist
    ("position");
CREATE INDEX fp_ueberlagerungsobjekt_gist
    ON xplan_gml.fp_ueberlagerungsobjekt USING gist
    ("position");
CREATE INDEX fp_unverbindlichevormerkung_gist
    ON xplan_gml.fp_unverbindlichevormerkung USING gist
    ("position");
CREATE INDEX fp_verentsorgung_gist
    ON xplan_gml.fp_verentsorgung USING gist
    ("position");
CREATE INDEX fp_vorbehalteflaeche_gist
    ON xplan_gml.fp_vorbehalteflaeche USING gist
    ("position");
CREATE INDEX fp_waldflaeche_gist
    ON xplan_gml.fp_waldflaeche USING gist
    ("position");
CREATE INDEX fp_wasserwirtschaft_gist
    ON xplan_gml.fp_wasserwirtschaft USING gist
    ("position");
CREATE INDEX fp_zentralerversorgungsbereich_gist
    ON xplan_gml.fp_zentralerversorgungsbereich USING gist
    ("position");
CREATE INDEX rp_achse_gist
    ON xplan_gml.rp_achse USING gist
    ("position");
CREATE INDEX rp_bereich_gist
    ON xplan_gml.rp_bereich USING gist
    ("geltungsbereich");
CREATE INDEX rp_bodenschutz_gist
    ON xplan_gml.rp_bodenschutz USING gist
    ("position");
CREATE INDEX rp_einzelhandel_gist
    ON xplan_gml.rp_einzelhandel USING gist
    ("position");
CREATE INDEX rp_energieversorgung_gist
    ON xplan_gml.rp_energieversorgung USING gist
    ("position");
CREATE INDEX rp_entsorgung_gist
    ON xplan_gml.rp_entsorgung USING gist
    ("position");
CREATE INDEX rp_erholung_gist
    ON xplan_gml.rp_erholung USING gist
    ("position");
CREATE INDEX rp_erneuerbareenergie_gist
    ON xplan_gml.rp_erneuerbareenergie USING gist
    ("position");
CREATE INDEX rp_forstwirtschaft_gist
    ON xplan_gml.rp_forstwirtschaft USING gist
    ("position");
CREATE INDEX rp_freiraum_gist
    ON xplan_gml.rp_freiraum USING gist
    ("position");
CREATE INDEX rp_funktionszuweisung_gist
    ON xplan_gml.rp_funktionszuweisung USING gist
    ("position");
CREATE INDEX rp_generischesobjekt_gist
    ON xplan_gml.rp_generischesobjekt USING gist
    ("position");
CREATE INDEX rp_geometrieobjekt_gist
    ON xplan_gml.rp_geometrieobjekt USING gist
    ("position");
CREATE INDEX rp_gewaesser_gist
    ON xplan_gml.rp_gewaesser USING gist
    ("position");
CREATE INDEX rp_grenze_gist
    ON xplan_gml.rp_grenze USING gist
    ("position");
CREATE INDEX rp_gruenzuggruenzaesur_gist
    ON xplan_gml.rp_gruenzuggruenzaesur USING gist
    ("position");
CREATE INDEX rp_hochwasserschutz_gist
    ON xplan_gml.rp_hochwasserschutz USING gist
    ("position");
CREATE INDEX rp_industriegewerbe_gist
    ON xplan_gml.rp_industriegewerbe USING gist
    ("position");
CREATE INDEX rp_klimaschutz_gist
    ON xplan_gml.rp_klimaschutz USING gist
    ("position");
CREATE INDEX rp_kommunikation_gist
    ON xplan_gml.rp_kommunikation USING gist
    ("position");
CREATE INDEX rp_kulturlandschaft_gist
    ON xplan_gml.rp_kulturlandschaft USING gist
    ("position");
CREATE INDEX rp_laermschutzbauschutz_gist
    ON xplan_gml.rp_laermschutzbauschutz USING gist
    ("position");
CREATE INDEX rp_landwirtschaft_gist
    ON xplan_gml.rp_landwirtschaft USING gist
    ("position");
CREATE INDEX rp_luftverkehr_gist
    ON xplan_gml.rp_luftverkehr USING gist
    ("position");
CREATE INDEX rp_naturlandschaft_gist
    ON xplan_gml.rp_naturlandschaft USING gist
    ("position");
CREATE INDEX rp_naturschutzrechtlichesschutzgebiet_gist
    ON xplan_gml.rp_naturschutzrechtlichesschutzgebiet USING gist
    ("position");
CREATE INDEX rp_plan_gist
    ON xplan_gml.rp_plan USING gist
    ("raeumlichergeltungsbereich");
CREATE INDEX rp_planungsraum_gist
    ON xplan_gml.rp_planungsraum USING gist
    ("position");
CREATE INDEX rp_radwegwanderweg_gist
    ON xplan_gml.rp_radwegwanderweg USING gist
    ("position");
CREATE INDEX rp_raumkategorie_gist
    ON xplan_gml.rp_raumkategorie USING gist
    ("position");
CREATE INDEX rp_rohstoff_gist
    ON xplan_gml.rp_rohstoff USING gist
    ("position");
CREATE INDEX rp_schienenverkehr_gist
    ON xplan_gml.rp_schienenverkehr USING gist
    ("position");
CREATE INDEX rp_siedlung_gist
    ON xplan_gml.rp_siedlung USING gist
    ("position");
CREATE INDEX rp_sonstigeinfrastruktur_gist
    ON xplan_gml.rp_sonstigeinfrastruktur USING gist
    ("position");
CREATE INDEX rp_sonstigerfreiraumschutz_gist
    ON xplan_gml.rp_sonstigerfreiraumschutz USING gist
    ("position");
CREATE INDEX rp_sonstigersiedlungsbereich_gist
    ON xplan_gml.rp_sonstigersiedlungsbereich USING gist
    ("position");
CREATE INDEX rp_sonstverkehr_gist
    ON xplan_gml.rp_sonstverkehr USING gist
    ("position");
CREATE INDEX rp_sozialeinfrastruktur_gist
    ON xplan_gml.rp_sozialeinfrastruktur USING gist
    ("position");
CREATE INDEX rp_sperrgebiet_gist
    ON xplan_gml.rp_sperrgebiet USING gist
    ("position");
CREATE INDEX rp_sportanlage_gist
    ON xplan_gml.rp_sportanlage USING gist
    ("position");
CREATE INDEX rp_strassenverkehr_gist
    ON xplan_gml.rp_strassenverkehr USING gist
    ("position");
CREATE INDEX rp_verkehr_gist
    ON xplan_gml.rp_verkehr USING gist
    ("position");
CREATE INDEX rp_wasserschutz_gist
    ON xplan_gml.rp_wasserschutz USING gist
    ("position");
CREATE INDEX rp_wasserverkehr_gist
    ON xplan_gml.rp_wasserverkehr USING gist
    ("position");
CREATE INDEX rp_wasserwirtschaft_gist
    ON xplan_gml.rp_wasserwirtschaft USING gist
    ("position");
CREATE INDEX rp_wohnensiedlung_gist
    ON xplan_gml.rp_wohnensiedlung USING gist
    ("position");
CREATE INDEX rp_zentralerort_gist
    ON xplan_gml.rp_zentralerort USING gist
    ("position");
CREATE INDEX so_bereich_gist
    ON xplan_gml.so_bereich USING gist
    ("geltungsbereich");
CREATE INDEX so_bodenschutzrecht_gist
    ON xplan_gml.so_bodenschutzrecht USING gist
    ("position");
CREATE INDEX so_denkmalschutzrecht_gist
    ON xplan_gml.so_denkmalschutzrecht USING gist
    ("position");
CREATE INDEX so_flaechenobjekt_gist
    ON xplan_gml.so_flaechenobjekt USING gist
    ("position");
CREATE INDEX so_forstrecht_gist
    ON xplan_gml.so_forstrecht USING gist
    ("position");
CREATE INDEX so_gebiet_gist
    ON xplan_gml.so_gebiet USING gist
    ("position");
CREATE INDEX so_geometrieobjekt_gist
    ON xplan_gml.so_geometrieobjekt USING gist
    ("position");
CREATE INDEX so_gewaesser_gist
    ON xplan_gml.so_gewaesser USING gist
    ("position");
CREATE INDEX so_grenze_gist
    ON xplan_gml.so_grenze USING gist
    ("position");
CREATE INDEX so_linienobjekt_gist
    ON xplan_gml.so_linienobjekt USING gist
    ("position");
CREATE INDEX so_luftverkehrsrecht_gist
    ON xplan_gml.so_luftverkehrsrecht USING gist
    ("position");
CREATE INDEX so_plan_gist
    ON xplan_gml.so_plan USING gist
    ("raeumlichergeltungsbereich");
CREATE INDEX so_punktobjekt_gist
    ON xplan_gml.so_punktobjekt USING gist
    ("position");
CREATE INDEX so_schienenverkehrsrecht_gist
    ON xplan_gml.so_schienenverkehrsrecht USING gist
    ("position");
CREATE INDEX so_schutzgebietnaturschutzrecht_gist
    ON xplan_gml.so_schutzgebietnaturschutzrecht USING gist
    ("position");
CREATE INDEX so_schutzgebietsonstigesrecht_gist
    ON xplan_gml.so_schutzgebietsonstigesrecht USING gist
    ("position");
CREATE INDEX so_schutzgebietwasserrecht_gist
    ON xplan_gml.so_schutzgebietwasserrecht USING gist
    ("position");
CREATE INDEX so_sonstigesrecht_gist
    ON xplan_gml.so_sonstigesrecht USING gist
    ("position");
CREATE INDEX so_strassenverkehrsrecht_gist
    ON xplan_gml.so_strassenverkehrsrecht USING gist
    ("position");
CREATE INDEX so_wasserrecht_gist
    ON xplan_gml.so_wasserrecht USING gist
    ("position");
CREATE INDEX xp_bereich_gist
    ON xplan_gml.xp_bereich USING gist
    ("geltungsbereich");
CREATE INDEX xp_fpo_gist
    ON xplan_gml.xp_fpo USING gist
    ("position");
CREATE INDEX xp_lpo_gist
    ON xplan_gml.xp_lpo USING gist
    ("position");
CREATE INDEX xp_lto_gist
    ON xplan_gml.xp_lto USING gist
    ("position");
CREATE INDEX xp_nutzungsschablone_gist
    ON xplan_gml.xp_nutzungsschablone USING gist
    ("position");
CREATE INDEX xp_plan_gist
    ON xplan_gml.xp_plan USING gist
    ("raeumlichergeltungsbereich");
CREATE INDEX xp_ppo_gist
    ON xplan_gml.xp_ppo USING gist
    ("position");
CREATE INDEX xp_pto_gist
    ON xplan_gml.xp_pto USING gist
    ("position");

COMMIT;
