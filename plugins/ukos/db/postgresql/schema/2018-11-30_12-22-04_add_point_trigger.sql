BEGIN;
	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.abfallbehaelter FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.abfallbehaelter FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.anleger FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.anleger FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.anschlagsaeule FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.anschlagsaeule FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.auslauf FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.auslauf FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.bank FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.bank FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.blumenkuebel FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.blumenkuebel FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.brunnen FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.brunnen FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.dalben FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.dalben FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.einlauf FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.einlauf FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.fahne FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.fahne FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.fahrradstaender FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.fahrradstaender FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.hinweistafel FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.hinweistafel FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.infoterminal FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.infoterminal FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.kabelkasten FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.kabelkasten FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.kabelschacht FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.kabelschacht FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.kilometerstein FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.kilometerstein FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.klaeranlage FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.klaeranlage FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.kunstwerk FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.kunstwerk FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.lampe FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.lampe FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.leitpfosten FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.leitpfosten FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.loeschwasserentnahmestelle_saugstutzen FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.loeschwasserentnahmestelle_saugstutzen FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.markierung FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.markierung FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.mast FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.mast FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.medien FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.medien FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.papierkorb FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.papierkorb FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.parkscheinautomat FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.parkscheinautomat FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.poller FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.poller FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.schaukasten FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.schaukasten FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.schranke FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.schranke FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.sonstiges_punktobjekt FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.sonstiges_punktobjekt FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.spielgeraet FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.spielgeraet FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.spundwand FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.spundwand FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.stationaere_geschwindigkeitsueberwachung FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.stationaere_geschwindigkeitsueberwachung FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.telefon FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.telefon FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.tor FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.tor FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.turm FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.turm FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.ueberdachung_fahrradstaender FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.ueberdachung_fahrradstaender FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.ueberwachungsanlage FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.ueberwachungsanlage FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.uhr FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.uhr FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.verkehrsspiegel FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.verkehrsspiegel FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.wartestelle FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.wartestelle FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang BEFORE DELETE ON ukos_doppik.wehr FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.untergang();
	CREATE TRIGGER tr_before_delete_99_stop BEFORE DELETE ON ukos_doppik.wehr FOR EACH ROW EXECUTE PROCEDURE ukos_okstra.stop();

COMMIT;
