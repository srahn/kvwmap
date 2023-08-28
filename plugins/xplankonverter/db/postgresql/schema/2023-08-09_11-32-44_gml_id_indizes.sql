BEGIN;

CREATE INDEX bp_abgrabungsflaeche_gml_id
    ON xplan_gml.bp_abgrabungsflaeche USING btree
    (gml_id);
CREATE INDEX bp_abstandsflaeche_gml_id
    ON xplan_gml.bp_abstandsflaeche USING btree
    (gml_id);
CREATE INDEX bp_abstandsmass_gml_id
    ON xplan_gml.bp_abstandsmass USING btree
    (gml_id);
CREATE INDEX bp_abweichungvonbaugrenze_gml_id
    ON xplan_gml.bp_abweichungvonbaugrenze USING btree
    (gml_id);
CREATE INDEX bp_abweichungvonueberbauberergrundstuecksflaeche_gml_id
    ON xplan_gml.bp_abweichungvonueberbauberergrundstuecksflaeche USING btree
    (gml_id);
CREATE INDEX bp_anpflanzungbindungerhaltung_gml_id
    ON xplan_gml.bp_anpflanzungbindungerhaltung USING btree
    (gml_id);
CREATE INDEX bp_aufschuettungsflaeche_gml_id
    ON xplan_gml.bp_aufschuettungsflaeche USING btree
    (gml_id);
CREATE INDEX bp_ausgleichsflaeche_gml_id
    ON xplan_gml.bp_ausgleichsflaeche USING btree
    (gml_id);
CREATE INDEX bp_ausgleichsmassnahme_gml_id
    ON xplan_gml.bp_ausgleichsmassnahme USING btree
    (gml_id);
CREATE INDEX bp_baugebietsteilflaeche_gml_id
    ON xplan_gml.bp_baugebietsteilflaeche USING btree
    (gml_id);
CREATE INDEX bp_baugrenze_gml_id
    ON xplan_gml.bp_baugrenze USING btree
    (gml_id);
CREATE INDEX bp_baulinie_gml_id
    ON xplan_gml.bp_baulinie USING btree
    (gml_id);
CREATE INDEX bp_bereich_gml_id
    ON xplan_gml.bp_bereich USING btree
    (gml_id);
CREATE INDEX bp_bereichohneeinausfahrtlinie_gml_id
    ON xplan_gml.bp_bereichohneeinausfahrtlinie USING btree
    (gml_id);
CREATE INDEX bp_besonderernutzungszweckflaeche_gml_id
    ON xplan_gml.bp_besonderernutzungszweckflaeche USING btree
    (gml_id);
CREATE INDEX bp_bodenschaetzeflaeche_gml_id
    ON xplan_gml.bp_bodenschaetzeflaeche USING btree
    (gml_id);
CREATE INDEX bp_einfahrtpunkt_gml_id
    ON xplan_gml.bp_einfahrtpunkt USING btree
    (gml_id);
CREATE INDEX bp_einfahrtsbereichlinie_gml_id
    ON xplan_gml.bp_einfahrtsbereichlinie USING btree
    (gml_id);
CREATE INDEX bp_eingriffsbereich_gml_id
    ON xplan_gml.bp_eingriffsbereich USING btree
    (gml_id);
CREATE INDEX bp_erhaltungsbereichflaeche_gml_id
    ON xplan_gml.bp_erhaltungsbereichflaeche USING btree
    (gml_id);
CREATE INDEX bp_festsetzungnachlandesrecht_gml_id
    ON xplan_gml.bp_festsetzungnachlandesrecht USING btree
    (gml_id);
CREATE INDEX bp_firstrichtungslinie_gml_id
    ON xplan_gml.bp_firstrichtungslinie USING btree
    (gml_id);
CREATE INDEX bp_flaechenobjekt_gml_id
    ON xplan_gml.bp_flaechenobjekt USING btree
    (gml_id);
CREATE INDEX bp_flaechenschlussobjekt_gml_id
    ON xplan_gml.bp_flaechenschlussobjekt USING btree
    (gml_id);
CREATE INDEX bp_flaecheohnefestsetzung_gml_id
    ON xplan_gml.bp_flaecheohnefestsetzung USING btree
    (gml_id);
CREATE INDEX bp_foerderungsflaeche_gml_id
    ON xplan_gml.bp_foerderungsflaeche USING btree
    (gml_id);
CREATE INDEX bp_freiflaeche_gml_id
    ON xplan_gml.bp_freiflaeche USING btree
    (gml_id);
CREATE INDEX bp_gebaeudeflaeche_gml_id
    ON xplan_gml.bp_gebaeudeflaeche USING btree
    (gml_id);
CREATE INDEX bp_gemeinbedarfsflaeche_gml_id
    ON xplan_gml.bp_gemeinbedarfsflaeche USING btree
    (gml_id);
CREATE INDEX bp_gemeinschaftsanlagenflaeche_gml_id
    ON xplan_gml.bp_gemeinschaftsanlagenflaeche USING btree
    (gml_id);
CREATE INDEX bp_gemeinschaftsanlagenzuordnung_gml_id
    ON xplan_gml.bp_gemeinschaftsanlagenzuordnung USING btree
    (gml_id);
CREATE INDEX bp_generischesobjekt_gml_id
    ON xplan_gml.bp_generischesobjekt USING btree
    (gml_id);
CREATE INDEX bp_geometrieobjekt_gml_id
    ON xplan_gml.bp_geometrieobjekt USING btree
    (gml_id);
CREATE INDEX bp_gewaesserflaeche_gml_id
    ON xplan_gml.bp_gewaesserflaeche USING btree
    (gml_id);
CREATE INDEX bp_gruenflaeche_gml_id
    ON xplan_gml.bp_gruenflaeche USING btree
    (gml_id);
CREATE INDEX bp_hoehenmass_gml_id
    ON xplan_gml.bp_hoehenmass USING btree
    (gml_id);
CREATE INDEX bp_immissionsschutz_gml_id
    ON xplan_gml.bp_immissionsschutz USING btree
    (gml_id);
CREATE INDEX bp_kennzeichnungsflaeche_gml_id
    ON xplan_gml.bp_kennzeichnungsflaeche USING btree
    (gml_id);
CREATE INDEX bp_kleintierhaltungflaeche_gml_id
    ON xplan_gml.bp_kleintierhaltungflaeche USING btree
    (gml_id);
CREATE INDEX bp_landwirtschaft_gml_id
    ON xplan_gml.bp_landwirtschaft USING btree
    (gml_id);
CREATE INDEX bp_landwirtschaftsflaeche_gml_id
    ON xplan_gml.bp_landwirtschaftsflaeche USING btree
    (gml_id);
CREATE INDEX bp_linienobjekt_gml_id
    ON xplan_gml.bp_linienobjekt USING btree
    (gml_id);
CREATE INDEX bp_nebenanlagenausschlussflaeche_gml_id
    ON xplan_gml.bp_nebenanlagenausschlussflaeche USING btree
    (gml_id);
CREATE INDEX bp_nebenanlagenflaeche_gml_id
    ON xplan_gml.bp_nebenanlagenflaeche USING btree
    (gml_id);
CREATE INDEX bp_nichtueberbaubaregrundstuecksflaeche_gml_id
    ON xplan_gml.bp_nichtueberbaubaregrundstuecksflaeche USING btree
    (gml_id);
CREATE INDEX bp_nutzungsartengrenze_gml_id
    ON xplan_gml.bp_nutzungsartengrenze USING btree
    (gml_id);
CREATE INDEX bp_objekt_gml_id
    ON xplan_gml.bp_objekt USING btree
    (gml_id);
CREATE INDEX bp_persgruppenbestimmteflaeche_gml_id
    ON xplan_gml.bp_persgruppenbestimmteflaeche USING btree
    (gml_id);
CREATE INDEX bp_plan_gml_id
    ON xplan_gml.bp_plan USING btree
    (gml_id);
CREATE INDEX bp_punktobjekt_gml_id
    ON xplan_gml.bp_punktobjekt USING btree
    (gml_id);
CREATE INDEX bp_regelungvergnuegungsstaetten_gml_id
    ON xplan_gml.bp_regelungvergnuegungsstaetten USING btree
    (gml_id);
CREATE INDEX bp_rekultivierungsflaeche_gml_id
    ON xplan_gml.bp_rekultivierungsflaeche USING btree
    (gml_id);
CREATE INDEX bp_richtungssektorgrenze_gml_id
    ON xplan_gml.bp_richtungssektorgrenze USING btree
    (gml_id);
CREATE INDEX bp_schutzpflegeentwicklungsflaeche_gml_id
    ON xplan_gml.bp_schutzpflegeentwicklungsflaeche USING btree
    (gml_id);
CREATE INDEX bp_schutzpflegeentwicklungsmassnahme_gml_id
    ON xplan_gml.bp_schutzpflegeentwicklungsmassnahme USING btree
    (gml_id);
CREATE INDEX bp_sichtflaeche_gml_id
    ON xplan_gml.bp_sichtflaeche USING btree
    (gml_id);
CREATE INDEX bp_speziellebauweise_gml_id
    ON xplan_gml.bp_speziellebauweise USING btree
    (gml_id);
CREATE INDEX bp_spielsportanlagenflaeche_gml_id
    ON xplan_gml.bp_spielsportanlagenflaeche USING btree
    (gml_id);
CREATE INDEX bp_strassenbegrenzungslinie_gml_id
    ON xplan_gml.bp_strassenbegrenzungslinie USING btree
    (gml_id);
CREATE INDEX bp_strassenkoerper_gml_id
    ON xplan_gml.bp_strassenkoerper USING btree
    (gml_id);
CREATE INDEX bp_strassenverkehrsflaeche_gml_id
    ON xplan_gml.bp_strassenverkehrsflaeche USING btree
    (gml_id);
CREATE INDEX bp_technischemassnahmenflaeche_gml_id
    ON xplan_gml.bp_technischemassnahmenflaeche USING btree
    (gml_id);
CREATE INDEX bp_textabschnitt_gml_id
    ON xplan_gml.bp_textabschnitt USING btree
    (gml_id);
CREATE INDEX bp_textlichefestsetzungsflaeche_gml_id
    ON xplan_gml.bp_textlichefestsetzungsflaeche USING btree
    (gml_id);
CREATE INDEX bp_ueberbaubaregrundstuecksflaeche_gml_id
    ON xplan_gml.bp_ueberbaubaregrundstuecksflaeche USING btree
    (gml_id);
CREATE INDEX bp_ueberlagerungsobjekt_gml_id
    ON xplan_gml.bp_ueberlagerungsobjekt USING btree
    (gml_id);
CREATE INDEX bp_unverbindlichevormerkung_gml_id
    ON xplan_gml.bp_unverbindlichevormerkung USING btree
    (gml_id);
CREATE INDEX bp_veraenderungssperre_gml_id
    ON xplan_gml.bp_veraenderungssperre USING btree
    (gml_id);
CREATE INDEX bp_verentsorgung_gml_id
    ON xplan_gml.bp_verentsorgung USING btree
    (gml_id);
CREATE INDEX bp_verkehrsflaechebesondererzweckbestimmung_gml_id
    ON xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung USING btree
    (gml_id);
CREATE INDEX bp_waldflaeche_gml_id
    ON xplan_gml.bp_waldflaeche USING btree
    (gml_id);
CREATE INDEX bp_wasserwirtschaftsflaeche_gml_id
    ON xplan_gml.bp_wasserwirtschaftsflaeche USING btree
    (gml_id);
CREATE INDEX bp_wegerecht_gml_id
    ON xplan_gml.bp_wegerecht USING btree
    (gml_id);
CREATE INDEX bp_wohngebaeudeflaeche_gml_id
    ON xplan_gml.bp_wohngebaeudeflaeche USING btree
    (gml_id);
CREATE INDEX bp_zentralerversorgungsbereich_gml_id
    ON xplan_gml.bp_zentralerversorgungsbereich USING btree
    (gml_id);
CREATE INDEX bp_zusatzkontingentlaerm_gml_id
    ON xplan_gml.bp_zusatzkontingentlaerm USING btree
    (gml_id);
CREATE INDEX bp_zusatzkontingentlaermflaeche_gml_id
    ON xplan_gml.bp_zusatzkontingentlaermflaeche USING btree
    (gml_id);
CREATE INDEX fp_abgrabung_gml_id
    ON xplan_gml.fp_abgrabung USING btree
    (gml_id);
CREATE INDEX fp_anpassungklimawandel_gml_id
    ON xplan_gml.fp_anpassungklimawandel USING btree
    (gml_id);
CREATE INDEX fp_aufschuettung_gml_id
    ON xplan_gml.fp_aufschuettung USING btree
    (gml_id);
CREATE INDEX fp_ausgleichsflaeche_gml_id
    ON xplan_gml.fp_ausgleichsflaeche USING btree
    (gml_id);
CREATE INDEX fp_bebauungsflaeche_gml_id
    ON xplan_gml.fp_bebauungsflaeche USING btree
    (gml_id);
CREATE INDEX fp_bereich_gml_id
    ON xplan_gml.fp_bereich USING btree
    (gml_id);
CREATE INDEX fp_bodenschaetze_gml_id
    ON xplan_gml.fp_bodenschaetze USING btree
    (gml_id);
CREATE INDEX fp_flaechenobjekt_gml_id
    ON xplan_gml.fp_flaechenobjekt USING btree
    (gml_id);
CREATE INDEX fp_flaechenschlussobjekt_gml_id
    ON xplan_gml.fp_flaechenschlussobjekt USING btree
    (gml_id);
CREATE INDEX fp_gemeinbedarf_gml_id
    ON xplan_gml.fp_gemeinbedarf USING btree
    (gml_id);
CREATE INDEX fp_generischesobjekt_gml_id
    ON xplan_gml.fp_generischesobjekt USING btree
    (gml_id);
CREATE INDEX fp_geometrieobjekt_gml_id
    ON xplan_gml.fp_geometrieobjekt USING btree
    (gml_id);
CREATE INDEX fp_gewaesser_gml_id
    ON xplan_gml.fp_gewaesser USING btree
    (gml_id);
CREATE INDEX fp_gruen_gml_id
    ON xplan_gml.fp_gruen USING btree
    (gml_id);
CREATE INDEX fp_keinezentrabwasserbeseitigungflaeche_gml_id
    ON xplan_gml.fp_keinezentrabwasserbeseitigungflaeche USING btree
    (gml_id);
CREATE INDEX fp_kennzeichnung_gml_id
    ON xplan_gml.fp_kennzeichnung USING btree
    (gml_id);
CREATE INDEX fp_landwirtschaft_gml_id
    ON xplan_gml.fp_landwirtschaft USING btree
    (gml_id);
CREATE INDEX fp_landwirtschaftsflaeche_gml_id
    ON xplan_gml.fp_landwirtschaftsflaeche USING btree
    (gml_id);
CREATE INDEX fp_linienobjekt_gml_id
    ON xplan_gml.fp_linienobjekt USING btree
    (gml_id);
CREATE INDEX fp_nutzungsbeschraenkungsflaeche_gml_id
    ON xplan_gml.fp_nutzungsbeschraenkungsflaeche USING btree
    (gml_id);
CREATE INDEX fp_objekt_gml_id
    ON xplan_gml.fp_objekt USING btree
    (gml_id);
CREATE INDEX fp_plan_gml_id
    ON xplan_gml.fp_plan USING btree
    (gml_id);
CREATE INDEX fp_privilegiertesvorhaben_gml_id
    ON xplan_gml.fp_privilegiertesvorhaben USING btree
    (gml_id);
CREATE INDEX fp_punktobjekt_gml_id
    ON xplan_gml.fp_punktobjekt USING btree
    (gml_id);
CREATE INDEX fp_schutzpflegeentwicklung_gml_id
    ON xplan_gml.fp_schutzpflegeentwicklung USING btree
    (gml_id);
CREATE INDEX fp_spielsportanlage_gml_id
    ON xplan_gml.fp_spielsportanlage USING btree
    (gml_id);
CREATE INDEX fp_strassenverkehr_gml_id
    ON xplan_gml.fp_strassenverkehr USING btree
    (gml_id);
CREATE INDEX fp_textabschnitt_gml_id
    ON xplan_gml.fp_textabschnitt USING btree
    (gml_id);
CREATE INDEX fp_textlichedarstellungsflaeche_gml_id
    ON xplan_gml.fp_textlichedarstellungsflaeche USING btree
    (gml_id);
CREATE INDEX fp_ueberlagerungsobjekt_gml_id
    ON xplan_gml.fp_ueberlagerungsobjekt USING btree
    (gml_id);
CREATE INDEX fp_unverbindlichevormerkung_gml_id
    ON xplan_gml.fp_unverbindlichevormerkung USING btree
    (gml_id);
CREATE INDEX fp_verentsorgung_gml_id
    ON xplan_gml.fp_verentsorgung USING btree
    (gml_id);
CREATE INDEX fp_vorbehalteflaeche_gml_id
    ON xplan_gml.fp_vorbehalteflaeche USING btree
    (gml_id);
CREATE INDEX fp_waldflaeche_gml_id
    ON xplan_gml.fp_waldflaeche USING btree
    (gml_id);
CREATE INDEX fp_wasserwirtschaft_gml_id
    ON xplan_gml.fp_wasserwirtschaft USING btree
    (gml_id);
CREATE INDEX fp_zentralerversorgungsbereich_gml_id
    ON xplan_gml.fp_zentralerversorgungsbereich USING btree
    (gml_id);
CREATE INDEX rp_achse_gml_id
    ON xplan_gml.rp_achse USING btree
    (gml_id);
CREATE INDEX rp_bereich_gml_id
    ON xplan_gml.rp_bereich USING btree
    (gml_id);
CREATE INDEX rp_bodenschutz_gml_id
    ON xplan_gml.rp_bodenschutz USING btree
    (gml_id);
CREATE INDEX rp_einzelhandel_gml_id
    ON xplan_gml.rp_einzelhandel USING btree
    (gml_id);
CREATE INDEX rp_energieversorgung_gml_id
    ON xplan_gml.rp_energieversorgung USING btree
    (gml_id);
CREATE INDEX rp_entsorgung_gml_id
    ON xplan_gml.rp_entsorgung USING btree
    (gml_id);
CREATE INDEX rp_erholung_gml_id
    ON xplan_gml.rp_erholung USING btree
    (gml_id);
CREATE INDEX rp_erneuerbareenergie_gml_id
    ON xplan_gml.rp_erneuerbareenergie USING btree
    (gml_id);
CREATE INDEX rp_forstwirtschaft_gml_id
    ON xplan_gml.rp_forstwirtschaft USING btree
    (gml_id);
CREATE INDEX rp_freiraum_gml_id
    ON xplan_gml.rp_freiraum USING btree
    (gml_id);
CREATE INDEX rp_funktionszuweisung_gml_id
    ON xplan_gml.rp_funktionszuweisung USING btree
    (gml_id);
CREATE INDEX rp_generischesobjekt_gml_id
    ON xplan_gml.rp_generischesobjekt USING btree
    (gml_id);
CREATE INDEX rp_geometrieobjekt_gml_id
    ON xplan_gml.rp_geometrieobjekt USING btree
    (gml_id);
CREATE INDEX rp_gewaesser_gml_id
    ON xplan_gml.rp_gewaesser USING btree
    (gml_id);
CREATE INDEX rp_grenze_gml_id
    ON xplan_gml.rp_grenze USING btree
    (gml_id);
CREATE INDEX rp_gruenzuggruenzaesur_gml_id
    ON xplan_gml.rp_gruenzuggruenzaesur USING btree
    (gml_id);
CREATE INDEX rp_hochwasserschutz_gml_id
    ON xplan_gml.rp_hochwasserschutz USING btree
    (gml_id);
CREATE INDEX rp_industriegewerbe_gml_id
    ON xplan_gml.rp_industriegewerbe USING btree
    (gml_id);
CREATE INDEX rp_klimaschutz_gml_id
    ON xplan_gml.rp_klimaschutz USING btree
    (gml_id);
CREATE INDEX rp_kommunikation_gml_id
    ON xplan_gml.rp_kommunikation USING btree
    (gml_id);
CREATE INDEX rp_kulturlandschaft_gml_id
    ON xplan_gml.rp_kulturlandschaft USING btree
    (gml_id);
CREATE INDEX rp_laermschutzbauschutz_gml_id
    ON xplan_gml.rp_laermschutzbauschutz USING btree
    (gml_id);
CREATE INDEX rp_landwirtschaft_gml_id
    ON xplan_gml.rp_landwirtschaft USING btree
    (gml_id);
CREATE INDEX rp_legendenobjekt_gml_id
    ON xplan_gml.rp_legendenobjekt USING btree
    (gml_id);
CREATE INDEX rp_luftverkehr_gml_id
    ON xplan_gml.rp_luftverkehr USING btree
    (gml_id);
CREATE INDEX rp_naturlandschaft_gml_id
    ON xplan_gml.rp_naturlandschaft USING btree
    (gml_id);
CREATE INDEX rp_naturschutzrechtlichesschutzgebiet_gml_id
    ON xplan_gml.rp_naturschutzrechtlichesschutzgebiet USING btree
    (gml_id);
CREATE INDEX rp_objekt_gml_id
    ON xplan_gml.rp_objekt USING btree
    (gml_id);
CREATE INDEX rp_plan_gml_id
    ON xplan_gml.rp_plan USING btree
    (gml_id);
CREATE INDEX rp_planungsraum_gml_id
    ON xplan_gml.rp_planungsraum USING btree
    (gml_id);
CREATE INDEX rp_radwegwanderweg_gml_id
    ON xplan_gml.rp_radwegwanderweg USING btree
    (gml_id);
CREATE INDEX rp_raumkategorie_gml_id
    ON xplan_gml.rp_raumkategorie USING btree
    (gml_id);
CREATE INDEX rp_rohstoff_gml_id
    ON xplan_gml.rp_rohstoff USING btree
    (gml_id);
CREATE INDEX rp_schienenverkehr_gml_id
    ON xplan_gml.rp_schienenverkehr USING btree
    (gml_id);
CREATE INDEX rp_siedlung_gml_id
    ON xplan_gml.rp_siedlung USING btree
    (gml_id);
CREATE INDEX rp_sonstigeinfrastruktur_gml_id
    ON xplan_gml.rp_sonstigeinfrastruktur USING btree
    (gml_id);
CREATE INDEX rp_sonstigerfreiraumschutz_gml_id
    ON xplan_gml.rp_sonstigerfreiraumschutz USING btree
    (gml_id);
CREATE INDEX rp_sonstigersiedlungsbereich_gml_id
    ON xplan_gml.rp_sonstigersiedlungsbereich USING btree
    (gml_id);
CREATE INDEX rp_sonstverkehr_gml_id
    ON xplan_gml.rp_sonstverkehr USING btree
    (gml_id);
CREATE INDEX rp_sozialeinfrastruktur_gml_id
    ON xplan_gml.rp_sozialeinfrastruktur USING btree
    (gml_id);
CREATE INDEX rp_sperrgebiet_gml_id
    ON xplan_gml.rp_sperrgebiet USING btree
    (gml_id);
CREATE INDEX rp_sportanlage_gml_id
    ON xplan_gml.rp_sportanlage USING btree
    (gml_id);
CREATE INDEX rp_strassenverkehr_gml_id
    ON xplan_gml.rp_strassenverkehr USING btree
    (gml_id);
CREATE INDEX rp_textabschnitt_gml_id
    ON xplan_gml.rp_textabschnitt USING btree
    (gml_id);
CREATE INDEX rp_verkehr_gml_id
    ON xplan_gml.rp_verkehr USING btree
    (gml_id);
CREATE INDEX rp_wasserschutz_gml_id
    ON xplan_gml.rp_wasserschutz USING btree
    (gml_id);
CREATE INDEX rp_wasserverkehr_gml_id
    ON xplan_gml.rp_wasserverkehr USING btree
    (gml_id);
CREATE INDEX rp_wasserwirtschaft_gml_id
    ON xplan_gml.rp_wasserwirtschaft USING btree
    (gml_id);
CREATE INDEX rp_wohnensiedlung_gml_id
    ON xplan_gml.rp_wohnensiedlung USING btree
    (gml_id);
CREATE INDEX rp_zentralerort_gml_id
    ON xplan_gml.rp_zentralerort USING btree
    (gml_id);
CREATE INDEX so_bereich_gml_id
    ON xplan_gml.so_bereich USING btree
    (gml_id);
CREATE INDEX so_bodenschutzrecht_gml_id
    ON xplan_gml.so_bodenschutzrecht USING btree
    (gml_id);
CREATE INDEX so_denkmalschutzrecht_gml_id
    ON xplan_gml.so_denkmalschutzrecht USING btree
    (gml_id);
CREATE INDEX so_flaechenobjekt_gml_id
    ON xplan_gml.so_flaechenobjekt USING btree
    (gml_id);
CREATE INDEX so_forstrecht_gml_id
    ON xplan_gml.so_forstrecht USING btree
    (gml_id);
CREATE INDEX so_gebiet_gml_id
    ON xplan_gml.so_gebiet USING btree
    (gml_id);
CREATE INDEX so_gelaendemorphologie_gml_id
    ON xplan_gml.so_gelaendemorphologie USING btree
    (gml_id);
CREATE INDEX so_geometrieobjekt_gml_id
    ON xplan_gml.so_geometrieobjekt USING btree
    (gml_id);
CREATE INDEX so_gewaesser_gml_id
    ON xplan_gml.so_gewaesser USING btree
    (gml_id);
CREATE INDEX so_grenze_gml_id
    ON xplan_gml.so_grenze USING btree
    (gml_id);
CREATE INDEX so_linienobjekt_gml_id
    ON xplan_gml.so_linienobjekt USING btree
    (gml_id);
CREATE INDEX so_luftverkehrsrecht_gml_id
    ON xplan_gml.so_luftverkehrsrecht USING btree
    (gml_id);
CREATE INDEX so_objekt_gml_id
    ON xplan_gml.so_objekt USING btree
    (gml_id);
CREATE INDEX so_plan_gml_id
    ON xplan_gml.so_plan USING btree
    (gml_id);
CREATE INDEX so_punktobjekt_gml_id
    ON xplan_gml.so_punktobjekt USING btree
    (gml_id);
CREATE INDEX so_schienenverkehrsrecht_gml_id
    ON xplan_gml.so_schienenverkehrsrecht USING btree
    (gml_id);
CREATE INDEX so_schutzgebietnaturschutzrecht_gml_id
    ON xplan_gml.so_schutzgebietnaturschutzrecht USING btree
    (gml_id);
CREATE INDEX so_schutzgebietsonstigesrecht_gml_id
    ON xplan_gml.so_schutzgebietsonstigesrecht USING btree
    (gml_id);
CREATE INDEX so_schutzgebietwasserrecht_gml_id
    ON xplan_gml.so_schutzgebietwasserrecht USING btree
    (gml_id);
CREATE INDEX so_sonstigesrecht_gml_id
    ON xplan_gml.so_sonstigesrecht USING btree
    (gml_id);
CREATE INDEX so_strassenverkehrsrecht_gml_id
    ON xplan_gml.so_strassenverkehrsrecht USING btree
    (gml_id);
CREATE INDEX so_textabschnitt_gml_id
    ON xplan_gml.so_textabschnitt USING btree
    (gml_id);
CREATE INDEX so_wasserrecht_gml_id
    ON xplan_gml.so_wasserrecht USING btree
    (gml_id);
CREATE INDEX xp_abstraktespraesentationsobjekt_gml_id
    ON xplan_gml.xp_abstraktespraesentationsobjekt USING btree
    (gml_id);
CREATE INDEX xp_begruendungabschnitt_gml_id
    ON xplan_gml.xp_begruendungabschnitt USING btree
    (gml_id);
CREATE INDEX xp_bereich_gml_id
    ON xplan_gml.xp_bereich USING btree
    (gml_id);
CREATE INDEX xp_fpo_gml_id
    ON xplan_gml.xp_fpo USING btree
    (gml_id);
CREATE INDEX xp_lpo_gml_id
    ON xplan_gml.xp_lpo USING btree
    (gml_id);
CREATE INDEX xp_lto_gml_id
    ON xplan_gml.xp_lto USING btree
    (gml_id);
CREATE INDEX xp_nutzungsschablone_gml_id
    ON xplan_gml.xp_nutzungsschablone USING btree
    (gml_id);
CREATE INDEX xp_objekt_gml_id
    ON xplan_gml.xp_objekt USING btree
    (gml_id);
CREATE INDEX xp_plan_gml_id
    ON xplan_gml.xp_plan USING btree
    (gml_id);
CREATE INDEX xp_ppo_gml_id
    ON xplan_gml.xp_ppo USING btree
    (gml_id);
CREATE INDEX xp_praesentationsobjekt_gml_id
    ON xplan_gml.xp_praesentationsobjekt USING btree
    (gml_id);
CREATE INDEX xp_pto_gml_id
    ON xplan_gml.xp_pto USING btree
    (gml_id);
CREATE INDEX xp_rasterdarstellung_gml_id
    ON xplan_gml.xp_rasterdarstellung USING btree
    (gml_id);
CREATE INDEX xp_textabschnitt_gml_id
    ON xplan_gml.xp_textabschnitt USING btree
    (gml_id);
CREATE INDEX xp_tpo_gml_id
    ON xplan_gml.xp_tpo USING btree
    (gml_id);

COMMIT;
