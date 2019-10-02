BEGIN;

  DROP VIEW IF EXISTS sep_bankett, sep_baumscheibe, sep_bord_flaeche, sep_dammschuettung, sep_duecker, sep_fahrbahn, sep_gruenflaeche, sep_hecke, sep_parkplatz, sep_platz, sep_sonstige_flaeche, sep_spielplatz, sep_sportplatz, sep_strassengraben, sep_ueberfahrt, sep_ueberweg, sep_leitung, sep_mauer, sep_rinne, sep_sonstige_flaeche, sep_sonstige_linie, sep_strasse, sep_zaun, sep_gehweg, sep_rad_und_gehweg, sep_radweg, sep_leitplanke;

  DROP VIEW IF EXISTS ukos_doppik.sep_baum;

  CREATE OR REPLACE VIEW ukos_doppik.sep_baum AS 
   SELECT a.oid,
      a.id,
      a.stelle_id,
      a.gueltig_von,
      a.gueltig_bis,
      a.id_strassenelement,
      a.id_preisermittlung,
      a.id_zustand,
      a.id_zustandsbewertung_01,
      a.id_zustandsbewertung_02,
      a.id_zustandsbewertung_03,
      a.id_zustandsbewertung_04,
      a.id_zustandsbewertung_05,
      a.id_eigentuemer,
      a.id_baulasttraeger,
      a.ahk,
      a.baujahr,
      a.angelegt_am,
      a.angelegt_von,
      a.geaendert_am,
      a.geaendert_von,
      a.ident_hist,
      a.bemerkung,
      a.objektname,
      a.zusatzbezeichnung,
      a.objekt_id,
      a.objektart,
      a.objektart_kurz,
      a.objektnummer,
      a.zustandsnote,
      a.datum_der_benotung,
      a.pauschalpreis,
      a.baulasttraeger,
      a.baulasttraeger_dritter,
      a.abschreibung,
      a.art_der_preisermittlung,
      a.eroeffnungsbilanzwert,
      a.zeitwert,
      a.fremdobjekt,
      a.fremddatenbestand,
      a.kommunikationsobjekt,
      a.erzeugt_von_ereignis,
      a.geloescht_von_ereignis,
      a.hat_vorgaenger_hist_objekt,
      a.hat_nachfolger_hist_objekt,
      a.ident,
      a.bei_strassenelementpunkt_id,
      a.kreuzungszuordnung,
      a.unterhaltsbezug_sp,
      a.hat_objekt_id,
      a.biotoptyp_schluessel,
      a.biotoptyp_version_schluessel,
      a.biotoptyp_biotoptypangabe,
      a.flaechengroesse,
      a.laenge,
      a.multigeometrie,
      a.bestandsstatus,
      a.beschreibung,
      a.schutzstatus,
      a.zustaendigkeit,
      a.verkehrsraumeinschraenkung,
      a.erfassungsqualitaet_erfassung_verfahren,
      a.erfassungsqualitaet_standardabweichung,
      a.gehoert_zu_massnahme,
      a.gehoert_zu_biotopkomplex,
      a.ausgangsbiotop_von,
      a.zielbiotop_von,
      a.hat_lpf_teilelement,
      a.zu_konfliktbestandteil,
      a.hat_leistungsbeschreibung,
      a.hat_pflegemassnahme,
      a.hat_dokument,
      a.lage,
      a.baumgattung,
      a.baumart,
      a.stammumfang,
      a.stammdurchmesser,
      a.kronendurchmesser,
      a.wurzelhalsdurchmesser,
      a.stammhoehe,
      a.baumhoehe,
      a.baumscheibe,
      a.pflanzjahr,
      a.gefaellt,
      a.datum_der_faellung,
      a.letzte_baumschau,
      a.schiefstand,
      a.zustandsbeurteilung,
      a.lagebeschreibung,
      a.detaillierungsgrad,
      a.stellt_teilhindernis_dar,
      a.hat_baumschaeden,
      a.zu_baumreihenabschnitt,
      a.material,
      sep.station,
      sep.abstand_zur_bestandsachse,
      sep.punktgeometrie
     FROM ukos_doppik.baum a
       JOIN ukos_okstra.strassenelementpunkt sep ON a.bei_strassenelementpunkt_id::text = sep.id::text;

  -- Trigger: tr_instead_10_insert_or_update_validate_sap on ukos_doppik.sep_baum

  -- DROP TRIGGER tr_instead_10_insert_or_update_validate_sap ON ukos_doppik.sep_baum;

  CREATE TRIGGER tr_instead_10_insert_or_update_validate_sap
    INSTEAD OF INSERT OR UPDATE
    ON ukos_doppik.sep_baum
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.validate_sap();

  -- Trigger: tr_instead_20_insert_create_with_sep on ukos_doppik.sep_baum

  -- DROP TRIGGER tr_instead_20_insert_create_with_sep ON ukos_doppik.sep_baum;

  CREATE TRIGGER tr_instead_20_insert_create_with_sep
    INSTEAD OF INSERT
    ON ukos_doppik.sep_baum
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.create_with_sep();

  -- Trigger: tr_instead_20_update_create_with_sep on ukos_doppik.sep_baum

  -- DROP TRIGGER tr_instead_20_update_create_with_sep ON ukos_doppik.sep_baum;

  CREATE TRIGGER tr_instead_20_update_create_with_sep
    INSTEAD OF UPDATE
    ON ukos_doppik.sep_baum
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.update_with_sep();

  -- Trigger: tr_instead_delete_delete_with_sep on ukos_doppik.sep_baum

  -- DROP TRIGGER tr_instead_delete_delete_with_sep ON ukos_doppik.sep_baum;

  CREATE TRIGGER tr_instead_delete_delete_with_sep
    INSTEAD OF DELETE
    ON ukos_doppik.sep_baum
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.delete_with_sep();

  DROP VIEW if exists ukos_doppik.sep_bewuchs;

  CREATE OR REPLACE VIEW ukos_doppik.sep_bewuchs AS 
   SELECT a.oid,
      a.id,
      a.stelle_id,
      a.gueltig_von,
      a.gueltig_bis,
      a.id_strassenelement,
      a.id_preisermittlung,
      a.id_zustand,
      a.id_zustandsbewertung_01,
      a.id_zustandsbewertung_02,
      a.id_zustandsbewertung_03,
      a.id_zustandsbewertung_04,
      a.id_zustandsbewertung_05,
      a.id_eigentuemer,
      a.id_baulasttraeger,
      a.ahk,
      a.baujahr,
      a.angelegt_am,
      a.angelegt_von,
      a.geaendert_am,
      a.geaendert_von,
      a.ident_hist,
      a.bemerkung,
      a.objektname,
      a.zusatzbezeichnung,
      a.objekt_id,
      a.objektart,
      a.objektart_kurz,
      a.objektnummer,
      a.zustandsnote,
      a.datum_der_benotung,
      a.pauschalpreis,
      a.baulasttraeger,
      a.baulasttraeger_dritter,
      a.abschreibung,
      a.art_der_preisermittlung,
      a.eroeffnungsbilanzwert,
      a.zeitwert,
      a.fremdobjekt,
      a.fremddatenbestand,
      a.kommunikationsobjekt,
      a.erzeugt_von_ereignis,
      a.geloescht_von_ereignis,
      a.hat_vorgaenger_hist_objekt,
      a.hat_nachfolger_hist_objekt,
      a.ident,
      a.bei_strassenelementpunkt_id,
      a.kreuzungszuordnung,
      a.unterhaltsbezug_sp,
      a.hat_objekt_id,
      a.biotoptyp_schluessel,
      a.biotoptyp_version_schluessel,
      a.biotoptyp_biotoptypangabe,
      a.flaechengroesse,
      a.laenge,
      a.multigeometrie,
      a.bestandsstatus,
      a.beschreibung,
      a.schutzstatus,
      a.zustaendigkeit,
      a.verkehrsraumeinschraenkung,
      a.erfassungsqualitaet_erfassung_verfahren,
      a.erfassungsqualitaet_standardabweichung,
      a.gehoert_zu_massnahme,
      a.gehoert_zu_biotopkomplex,
      a.ausgangsbiotop_von,
      a.zielbiotop_von,
      a.hat_lpf_teilelement,
      a.zu_konfliktbestandteil,
      a.hat_leistungsbeschreibung,
      a.hat_pflegemassnahme,
      a.hat_dokument,
      a.breite,
      a.hoehe,
      sep.station,
      sep.abstand_zur_bestandsachse,
      sep.punktgeometrie
     FROM ukos_doppik.bewuchs a
       JOIN ukos_okstra.strassenelementpunkt sep ON a.bei_strassenelementpunkt_id::text = sep.id::text;

  -- Trigger: tr_instead_10_insert_or_update_validate_sap on ukos_doppik.sep_bewuchs

  -- DROP TRIGGER tr_instead_10_insert_or_update_validate_sap ON ukos_doppik.sep_bewuchs;

  CREATE TRIGGER tr_instead_10_insert_or_update_validate_sap
    INSTEAD OF INSERT OR UPDATE
    ON ukos_doppik.sep_bewuchs
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.validate_sap();

  -- Trigger: tr_instead_20_insert_create_with_sep on ukos_doppik.sep_bewuchs

  -- DROP TRIGGER tr_instead_20_insert_create_with_sep ON ukos_doppik.sep_bewuchs;

  CREATE TRIGGER tr_instead_20_insert_create_with_sep
    INSTEAD OF INSERT
    ON ukos_doppik.sep_bewuchs
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.create_with_sep();

  -- Trigger: tr_instead_20_update_create_with_sep on ukos_doppik.sep_bewuchs

  -- DROP TRIGGER tr_instead_20_update_create_with_sep ON ukos_doppik.sep_bewuchs;

  CREATE TRIGGER tr_instead_20_update_create_with_sep
    INSTEAD OF UPDATE
    ON ukos_doppik.sep_bewuchs
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.update_with_sep();

  -- Trigger: tr_instead_delete_delete_with_sep on ukos_doppik.sep_bewuchs

  -- DROP TRIGGER tr_instead_delete_delete_with_sep ON ukos_doppik.sep_bewuchs;

  CREATE TRIGGER tr_instead_delete_delete_with_sep
    INSTEAD OF DELETE
    ON ukos_doppik.sep_bewuchs
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.delete_with_sep();

  DROP VIEW if exists ukos_doppik.sep_denkmal;

  CREATE OR REPLACE VIEW ukos_doppik.sep_denkmal AS 
   SELECT a.oid,
      a.id,
      a.stelle_id,
      a.gueltig_von,
      a.gueltig_bis,
      a.id_strassenelement,
      a.id_preisermittlung,
      a.id_zustand,
      a.id_zustandsbewertung_01,
      a.id_zustandsbewertung_02,
      a.id_zustandsbewertung_03,
      a.id_zustandsbewertung_04,
      a.id_zustandsbewertung_05,
      a.id_eigentuemer,
      a.id_baulasttraeger,
      a.ahk,
      a.baujahr,
      a.angelegt_am,
      a.angelegt_von,
      a.geaendert_am,
      a.geaendert_von,
      a.ident_hist,
      a.bemerkung,
      a.objektname,
      a.zusatzbezeichnung,
      a.objekt_id,
      a.objektart,
      a.objektart_kurz,
      a.objektnummer,
      a.zustandsnote,
      a.datum_der_benotung,
      a.pauschalpreis,
      a.baulasttraeger,
      a.baulasttraeger_dritter,
      a.abschreibung,
      a.art_der_preisermittlung,
      a.eroeffnungsbilanzwert,
      a.zeitwert,
      a.fremdobjekt,
      a.fremddatenbestand,
      a.kommunikationsobjekt,
      a.erzeugt_von_ereignis,
      a.geloescht_von_ereignis,
      a.hat_vorgaenger_hist_objekt,
      a.hat_nachfolger_hist_objekt,
      a.ident,
      a.bei_strassenelementpunkt_id,
      a.geometrie_streckenobjekt,
      a.hat_objekt_id,
      a.punktgeometrie_gauss_krueger,
      a.punktgeometrie_utm,
      a.koordinaten_vom_system_berechnet,
      a.strassenbezeichnung_strassenklasse,
      a.strassenbezeichnung_strassennummer,
      a.strassenbezeichnung_zusatzbuchstabe,
      a.strassenbezeichnung_identifizierungskennzeichen,
      a.teilbauwerksnummer,
      a.interne_teilbauwerksnummer,
      a.name_des_teilbauwerks,
      a.interner_sortierschluessel,
      a.unterhaltung_instandsetzung,
      a.bauwerksart,
      a.stadium_teilbauwerk,
      a.stationierung,
      a.bauwerksakte_nummer,
      a.baulast_konstruktion,
      a.anderes_bauwerk_nach_din1076,
      a.denkmalschutz,
      a.unterlagen,
      a.datenerfassung_abgeschlossen,
      a.unterhaltungslast_ueberbau,
      a.konkretisierung_ueberbau,
      a.unterhaltungslast_unterbau,
      a.konkretisierung_unterbau,
      a.konstruktion,
      a.bauwerksrichtung_text,
      a.massgebendes_teilbauwerk,
      a.bemerkungen_zuordnung,
      a.name_ui_ua_partner,
      a.sachverhaltsnummer,
      a.tragfaehigkeit,
      a.stat_system_in_bauwerksachse,
      a.stat_system_quer_zu_bauw_achse,
      a.sperrung_fuer_schwertransporte,
      a.statistischer_auslastungsgrad,
      a.max_schadbw_standsicherheit,
      a.anzahl_der_fahrstr_in_stat,
      a.anzahl_der_fahrstr_gegen_stat,
      a.min_breite_in_stationierung,
      a.min_breite_gegen_stationierung,
      a.hat_strecke,
      a.hat_abdichtungen,
      a.hat_abgeschlossene_pruefung,
      a.hat_anlagen_bauwerksbuch,
      a.ist_aufstellvorrichtung,
      a.hat_ausstattung,
      a.hat_bau_und_erhaltungsmassn,
      a.hat_bauwerkseinzelheiten,
      a.hat_bauwerksueberfahrt,
      a.hat_betonersatzsystem,
      a.hat_brueckenseile_und_kabel,
      a.hat_durchgef_pruefungen_messgn,
      a.ist_durchlass,
      a.hat_entwuerfe_und_berechnungen,
      a.hat_erd_und_felsanker,
      a.hat_fahrbahnuebergang,
      a.hat_gegenw_dok_bauwerkszustand,
      a.hat_gestaltungen,
      a.hat_gruendungen,
      a.ist_hindernis,
      a.hat_kappe,
      a.von_kreuzung_strasse_weg,
      a.auf_laermschutzwall,
      a.hat_leitungen_an_bauwerken,
      a.hat_oberflaechenschutzsystem,
      a.hat_pruefanweisungen,
      a.hat_prueffahrzeuge_pruefger,
      a.hat_reaktionsharzgeb_duennbel,
      a.hat_sachverhalt,
      a.hat_schutzeinrichtungen,
      a.hat_statistisches_system_tragfgkt,
      a.hat_strassenausstattung_punkt,
      a.hat_strategie_bms,
      a.hat_teilmassnahme_bwk,
      a.hat_verfuellungen,
      a.hat_verwaltungsmassnahme,
      a.ist_vorschalteinrichtung,
      a.hat_vorspannungen,
      a.bauwerk,
      a.baudienststelle,
      a.material,
      sep.station,
      sep.abstand_zur_bestandsachse,
      sep.punktgeometrie
     FROM ukos_doppik.denkmal a
       JOIN ukos_okstra.strassenelementpunkt sep ON a.bei_strassenelementpunkt_id::text = sep.id::text;

  -- Trigger: tr_instead_10_insert_or_update_validate_sap on ukos_doppik.sep_denkmal

  -- DROP TRIGGER tr_instead_10_insert_or_update_validate_sap ON ukos_doppik.sep_denkmal;

  CREATE TRIGGER tr_instead_10_insert_or_update_validate_sap
    INSTEAD OF INSERT OR UPDATE
    ON ukos_doppik.sep_denkmal
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.validate_sap();

  -- Trigger: tr_instead_20_insert_create_with_sep on ukos_doppik.sep_denkmal

  -- DROP TRIGGER tr_instead_20_insert_create_with_sep ON ukos_doppik.sep_denkmal;

  CREATE TRIGGER tr_instead_20_insert_create_with_sep
    INSTEAD OF INSERT
    ON ukos_doppik.sep_denkmal
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.create_with_sep();

  -- Trigger: tr_instead_20_update_create_with_sep on ukos_doppik.sep_denkmal

  -- DROP TRIGGER tr_instead_20_update_create_with_sep ON ukos_doppik.sep_denkmal;

  CREATE TRIGGER tr_instead_20_update_create_with_sep
    INSTEAD OF UPDATE
    ON ukos_doppik.sep_denkmal
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.update_with_sep();

  -- Trigger: tr_instead_delete_delete_with_sep on ukos_doppik.sep_denkmal

  -- DROP TRIGGER tr_instead_delete_delete_with_sep ON ukos_doppik.sep_denkmal;

  CREATE TRIGGER tr_instead_delete_delete_with_sep
    INSTEAD OF DELETE
    ON ukos_doppik.sep_denkmal
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.delete_with_sep();

  DROP VIEW if exists ukos_doppik.sep_durchlass;

  CREATE OR REPLACE VIEW ukos_doppik.sep_durchlass AS 
   SELECT a.oid,
      a.id,
      a.stelle_id,
      a.gueltig_von,
      a.gueltig_bis,
      a.id_strassenelement,
      a.id_preisermittlung,
      a.id_zustand,
      a.id_zustandsbewertung_01,
      a.id_zustandsbewertung_02,
      a.id_zustandsbewertung_03,
      a.id_zustandsbewertung_04,
      a.id_zustandsbewertung_05,
      a.id_eigentuemer,
      a.id_baulasttraeger,
      a.ahk,
      a.baujahr,
      a.angelegt_am,
      a.angelegt_von,
      a.geaendert_am,
      a.geaendert_von,
      a.ident_hist,
      a.bemerkung,
      a.objektname,
      a.zusatzbezeichnung,
      a.objekt_id,
      a.objektart,
      a.objektart_kurz,
      a.objektnummer,
      a.zustandsnote,
      a.datum_der_benotung,
      a.pauschalpreis,
      a.baulasttraeger,
      a.baulasttraeger_dritter,
      a.abschreibung,
      a.art_der_preisermittlung,
      a.eroeffnungsbilanzwert,
      a.zeitwert,
      a.fremdobjekt,
      a.fremddatenbestand,
      a.kommunikationsobjekt,
      a.erzeugt_von_ereignis,
      a.geloescht_von_ereignis,
      a.hat_vorgaenger_hist_objekt,
      a.hat_nachfolger_hist_objekt,
      a.ident,
      a.geometrie_streckenobjekt,
      a.kreuzungszuordnung,
      a.unterhaltsbezug_sp,
      a.erfassungsdatum,
      a.systemdatum,
      a.textfeld,
      a.art_der_erfassung,
      a.art_der_erfassung_sonst,
      a.quelle_der_information,
      a.quelle_der_information_sonst,
      a.rfid,
      a.migrationshinweise,
      a.unscharf,
      a.abstand_von_station,
      a.abstand_bis_station,
      a.lage,
      a.ueberdeckung_von_station,
      a.ueberdeckung_bis_station,
      a.mittlere_ueberdeckung,
      a.profil,
      a.hauptsaechliches_material,
      a.lichte_hoehe_durchmesser,
      a.lichte_weite,
      a.flaeche_der_verblendung,
      a.tatsaechliche_laenge,
      a.unterhaltungspflicht,
      a.sonstige_unterhaltspflichtige,
      a.funktion,
      a.permanente_nutzungseinschr,
      a.schutzeinrichtung,
      a.stadium,
      a.detaillierungsgrad,
      a.multigeometrie,
      a.dokument,
      a.hat_rechtliches_ereignis,
      a.hat_zustaendigkeit,
      a.zu_hausnummernblock,
      a.zu_hausnummernbereich,
      a.hat_strecke,
      a.material,
      a.pflasterflaeche,
      sep.station,
      sep.abstand_zur_bestandsachse,
      sep.punktgeometrie
     FROM ukos_doppik.durchlass a
       JOIN ukos_okstra.strassenelementpunkt sep ON a.bei_strassenelementpunkt_id::text = sep.id::text;

  -- Trigger: tr_instead_10_insert_or_update_validate_sap on ukos_doppik.sep_durchlass

  -- DROP TRIGGER tr_instead_10_insert_or_update_validate_sap ON ukos_doppik.sep_durchlass;

  CREATE TRIGGER tr_instead_10_insert_or_update_validate_sap
    INSTEAD OF INSERT OR UPDATE
    ON ukos_doppik.sep_durchlass
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.validate_sap();

  -- Trigger: tr_instead_20_insert_create_with_sep on ukos_doppik.sep_durchlass

  -- DROP TRIGGER tr_instead_20_insert_create_with_sep ON ukos_doppik.sep_durchlass;

  CREATE TRIGGER tr_instead_20_insert_create_with_sep
    INSTEAD OF INSERT
    ON ukos_doppik.sep_durchlass
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.create_with_sep();

  -- Trigger: tr_instead_20_update_create_with_sep on ukos_doppik.sep_durchlass

  -- DROP TRIGGER tr_instead_20_update_create_with_sep ON ukos_doppik.sep_durchlass;

  CREATE TRIGGER tr_instead_20_update_create_with_sep
    INSTEAD OF UPDATE
    ON ukos_doppik.sep_durchlass
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.update_with_sep();

  -- Trigger: tr_instead_delete_delete_with_sep on ukos_doppik.sep_durchlass

  -- DROP TRIGGER tr_instead_delete_delete_with_sep ON ukos_doppik.sep_durchlass;

  CREATE TRIGGER tr_instead_delete_delete_with_sep
    INSTEAD OF DELETE
    ON ukos_doppik.sep_durchlass
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.delete_with_sep();

  DROP VIEW IF EXISTS ukos_doppik.sep_schacht;

  CREATE OR REPLACE VIEW ukos_doppik.sep_schacht AS 
  SELECT a.oid,
  a.id,
  a.stelle_id,
  a.gueltig_von,
  a.gueltig_bis,
  a.id_strassenelement,
  a.id_preisermittlung,
  a.id_zustand,
  a.id_zustandsbewertung_01,
  a.id_zustandsbewertung_02,
  a.id_zustandsbewertung_03,
  a.id_zustandsbewertung_04,
  a.id_zustandsbewertung_05,
  a.id_eigentuemer,
  a.id_baulasttraeger,
  a.ahk,
  a.baujahr,
  a.angelegt_am,
  a.angelegt_von,
  a.geaendert_am,
  a.geaendert_von,
  a.ident_hist,
  a.bemerkung,
  a.objektname,
  a.zusatzbezeichnung,
  a.objekt_id,
  a.objektart,
  a.objektart_kurz,
  a.objektnummer,
  a.zustandsnote,
  a.datum_der_benotung,
  a.pauschalpreis,
  a.baulasttraeger,
  a.baulasttraeger_dritter,
  a.abschreibung,
  a.art_der_preisermittlung,
  a.eroeffnungsbilanzwert,
  a.zeitwert,
  a.fremdobjekt,
  a.fremddatenbestand,
  a.kommunikationsobjekt,
  a.erzeugt_von_ereignis,
  a.geloescht_von_ereignis,
  a.hat_vorgaenger_hist_objekt,
  a.hat_nachfolger_hist_objekt,
  a.ident,
  a.bei_strassenelementpunkt_id,
  a.kreuzungszuordnung,
  a.unterhaltsbezug_sp,
  a.erfassungsdatum,
  a.systemdatum,
  a.textfeld,
  a.art_der_erfassung,
  a.art_der_erfassung_sonst,
  a.quelle_der_information,
  a.quelle_der_information_sonst,
  a.rfid,
  a.migrationshinweise,
  a.unscharf,
  a.art,
  a.lage,
  a.angaben_zum_konus,
  a.schachttiefe,
  a.unterhaltungspflicht,
  a.sonstige_unterhaltspflicht,
  a.detaillierungsgrad,
  a.multigeometrie,
  a.dokument,
  a.hat_rechtliches_ereignis,
  a.hat_zustaendigkeit,
  a.zu_hausnummernblock,
  a.zu_hausnummernbereich,
  a.stellt_teilhindernis_dar,
  a.material,
  a.medium,
  a.bauform,
  a.abmasse,
  a.sonstige_unterhaltungspflichtige,
  sep.station,
  sep.abstand_zur_bestandsachse,
  sep.punktgeometrie
  FROM ukos_doppik.schacht a
   JOIN ukos_okstra.strassenelementpunkt sep ON a.bei_strassenelementpunkt_id::text = sep.id::text;

  -- Trigger: tr_instead_10_insert_or_update_validate_sap on ukos_doppik.sep_schacht

  -- DROP TRIGGER tr_instead_10_insert_or_update_validate_sap ON ukos_doppik.sep_schacht;

  CREATE TRIGGER tr_instead_10_insert_or_update_validate_sap
    INSTEAD OF INSERT OR UPDATE
    ON ukos_doppik.sep_schacht
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.validate_sap();

  -- Trigger: tr_instead_20_insert_create_with_sep on ukos_doppik.sep_schacht

  -- DROP TRIGGER tr_instead_20_insert_create_with_sep ON ukos_doppik.sep_schacht;

  CREATE TRIGGER tr_instead_20_insert_create_with_sep
    INSTEAD OF INSERT
    ON ukos_doppik.sep_schacht
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.create_with_sep();

  -- Trigger: tr_instead_20_update_create_with_sep on ukos_doppik.sep_schacht

  -- DROP TRIGGER tr_instead_20_update_create_with_sep ON ukos_doppik.sep_schacht;

  CREATE TRIGGER tr_instead_20_update_create_with_sep
    INSTEAD OF UPDATE
    ON ukos_doppik.sep_schacht
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.update_with_sep();

  -- Trigger: tr_instead_delete_delete_with_sep on ukos_doppik.sep_schacht

  -- DROP TRIGGER tr_instead_delete_delete_with_sep ON ukos_doppik.sep_schacht;

  CREATE TRIGGER tr_instead_delete_delete_with_sep
    INSTEAD OF DELETE
    ON ukos_doppik.sep_schacht
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.delete_with_sep();

  DROP VIEW ukos_doppik.sep_strassenablauf;

  CREATE OR REPLACE VIEW ukos_doppik.sep_strassenablauf AS 
   SELECT a.oid,
      a.id,
      a.stelle_id,
      a.gueltig_von,
      a.gueltig_bis,
      a.id_strassenelement,
      a.id_preisermittlung,
      a.id_zustand,
      a.id_zustandsbewertung_01,
      a.id_zustandsbewertung_02,
      a.id_zustandsbewertung_03,
      a.id_zustandsbewertung_04,
      a.id_zustandsbewertung_05,
      a.id_eigentuemer,
      a.id_baulasttraeger,
      a.ahk,
      a.baujahr,
      a.angelegt_am,
      a.angelegt_von,
      a.geaendert_am,
      a.geaendert_von,
      a.ident_hist,
      a.bemerkung,
      a.objektname,
      a.zusatzbezeichnung,
      a.objekt_id,
      a.objektart,
      a.objektart_kurz,
      a.objektnummer,
      a.zustandsnote,
      a.datum_der_benotung,
      a.pauschalpreis,
      a.baulasttraeger,
      a.baulasttraeger_dritter,
      a.abschreibung,
      a.art_der_preisermittlung,
      a.eroeffnungsbilanzwert,
      a.zeitwert,
      a.fremdobjekt,
      a.fremddatenbestand,
      a.kommunikationsobjekt,
      a.erzeugt_von_ereignis,
      a.geloescht_von_ereignis,
      a.hat_vorgaenger_hist_objekt,
      a.hat_nachfolger_hist_objekt,
      a.ident,
      a.bei_strassenelementpunkt_id,
      a.kreuzungszuordnung,
      a.unterhaltsbezug_sp,
      a.erfassungsdatum,
      a.systemdatum,
      a.textfeld,
      a.art_der_erfassung,
      a.art_der_erfassung_sonst,
      a.quelle_der_information,
      a.quelle_der_information_sonst,
      a.rfid,
      a.migrationshinweise,
      a.unscharf,
      a.lage,
      a.aufsatz,
      a.unterteil,
      a.art_unterteil_sonst,
      a.unterhaltungspflicht,
      a.sonstige_unterhaltspflichtige,
      a.detaillierungsgrad,
      a.multigeometrie,
      a.dokument,
      a.hat_rechtliches_ereignis,
      a.hat_zustaendigkeit,
      a.zu_hausnummernblock,
      a.zu_hausnummernbereich,
      a.material,
      a.art_des_ablaufes,
      a.abmasse_abdeckung,
      sep.station,
      sep.abstand_zur_bestandsachse,
      sep.punktgeometrie
     FROM ukos_doppik.strassenablauf a
       JOIN ukos_okstra.strassenelementpunkt sep ON a.bei_strassenelementpunkt_id::text = sep.id::text;

  -- Trigger: tr_instead_10_insert_or_update_validate_sap on ukos_doppik.sep_strassenablauf

  -- DROP TRIGGER tr_instead_10_insert_or_update_validate_sap ON ukos_doppik.sep_strassenablauf;

  CREATE TRIGGER tr_instead_10_insert_or_update_validate_sap
    INSTEAD OF INSERT OR UPDATE
    ON ukos_doppik.sep_strassenablauf
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.validate_sap();

  -- Trigger: tr_instead_20_insert_create_with_sep on ukos_doppik.sep_strassenablauf

  -- DROP TRIGGER tr_instead_20_insert_create_with_sep ON ukos_doppik.sep_strassenablauf;

  CREATE TRIGGER tr_instead_20_insert_create_with_sep
    INSTEAD OF INSERT
    ON ukos_doppik.sep_strassenablauf
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.create_with_sep();

  -- Trigger: tr_instead_20_update_create_with_sep on ukos_doppik.sep_strassenablauf

  -- DROP TRIGGER tr_instead_20_update_create_with_sep ON ukos_doppik.sep_strassenablauf;

  CREATE TRIGGER tr_instead_20_update_create_with_sep
    INSTEAD OF UPDATE
    ON ukos_doppik.sep_strassenablauf
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.update_with_sep();

  -- Trigger: tr_instead_delete_delete_with_sep on ukos_doppik.sep_strassenablauf

  -- DROP TRIGGER tr_instead_delete_delete_with_sep ON ukos_doppik.sep_strassenablauf;

  CREATE TRIGGER tr_instead_delete_delete_with_sep
    INSTEAD OF DELETE
    ON ukos_doppik.sep_strassenablauf
    FOR EACH ROW
    EXECUTE PROCEDURE ukos_okstra.delete_with_sep();


COMMIT;
