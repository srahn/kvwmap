BEGIN;

	-- fehlende Trigger
	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_abfallbehaelter
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_ampel
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_anleger
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_anschlagsaeule
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_auslauf
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_bank
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_blumenkuebel
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_brunnen
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_dalben
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_einlauf
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_fahne
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_fahrradstaender
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_haltestelle
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_hinweistafel
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_hydrant
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_infoterminal
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_kabelkasten
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_kabelschacht
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_kilometerstein
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_klaeranlage
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_kunstwerk
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_lampe
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_leitpfosten
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_loeschwasserentnahmestelle_saugstutzen
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_markierung
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_mast
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_medien
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_papierkorb
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_parkscheinautomat
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_poller
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_schaukasten
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_schranke
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_sonstiges_punktobjekt
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_spielgeraet
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_spundwand
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_stationaere_geschwindigkeitsueberwachung
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_telefon
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_tor
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_turm
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_ueberdachung_fahrradstaender
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_ueberwachungsanlage
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_uhr
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_verkehrsspiegel
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_wartestelle
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_wehr
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

  -- Trigger: ukos_doppik.sep_aufstellvorrichtung_schild
	CREATE TRIGGER tr_instead_10_insert_or_update_validate_sap
		INSTEAD OF INSERT OR UPDATE 
		ON ukos_doppik.sep_aufstellvorrichtung_schild
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.validate_sap();

	CREATE TRIGGER tr_instead_20_insert_create_with_sep
		INSTEAD OF INSERT
		ON ukos_doppik.sep_aufstellvorrichtung_schild
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.create_with_sep();

	CREATE TRIGGER tr_instead_20_update_create_with_sep
		INSTEAD OF UPDATE 
		ON ukos_doppik.sep_aufstellvorrichtung_schild
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.update_with_sep();

	CREATE TRIGGER tr_instead_delete_delete_with_sep
		INSTEAD OF DELETE
		ON ukos_doppik.sep_aufstellvorrichtung_schild
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_okstra.delete_with_sep();

	-- View: ukos_doppik.sep_durchlass
	DROP VIEW ukos_doppik.sep_durchlass;
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
			a.bei_strassenelementpunkt_id,
			sep.station,
			sep.abstand_zur_bestandsachse,
			sep.punktgeometrie
		 FROM ukos_doppik.durchlass a
			 JOIN ukos_okstra.strassenelementpunkt sep ON a.bei_strassenelementpunkt_id::text = sep.id::text;

	ALTER TABLE ukos_doppik.sep_durchlass
			OWNER TO kvwmap;


	CREATE TRIGGER tr_instead_10_insert_or_update_validate_sap
			INSTEAD OF INSERT OR UPDATE 
			ON ukos_doppik.sep_durchlass
			FOR EACH ROW
			EXECUTE PROCEDURE ukos_okstra.validate_sap();


	CREATE TRIGGER tr_instead_20_insert_create_with_sep
			INSTEAD OF INSERT
			ON ukos_doppik.sep_durchlass
			FOR EACH ROW
			EXECUTE PROCEDURE ukos_okstra.create_with_sep();


	CREATE TRIGGER tr_instead_20_update_create_with_sep
			INSTEAD OF UPDATE 
			ON ukos_doppik.sep_durchlass
			FOR EACH ROW
			EXECUTE PROCEDURE ukos_okstra.update_with_sep();


	CREATE TRIGGER tr_instead_delete_delete_with_sep
			INSTEAD OF DELETE
			ON ukos_doppik.sep_durchlass
			FOR EACH ROW
			EXECUTE PROCEDURE ukos_okstra.delete_with_sep();

COMMIT;
