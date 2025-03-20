BEGIN;

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.fahrbahn
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.gehweg
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.gehweg
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.bankett
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.bankett
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.baumscheibe
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.baumscheibe
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.bord_flaeche
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.bord_flaeche
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.dammschuettung
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.dammschuettung
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.gruenflaeche
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.gruenflaeche
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.hecke
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.hecke
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.parkplatz
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.parkplatz
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.parkstreifen
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.parkstreifen
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.platz
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.platz
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.rad_und_gehweg
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.rad_und_gehweg
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.radweg
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.radweg
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.sonstige_flaeche
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.sonstige_flaeche
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.spielplatz
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.spielplatz
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.sportplatz
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.sportplatz
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.strasse
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.strasse
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.strassengraben
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.strassengraben
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.ueberfahrt
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.ueberfahrt
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
  BEFORE INSERT
  ON ukos_doppik.ueberweg
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

CREATE TRIGGER tr_before_update_calc_flaecheninhalt
  BEFORE UPDATE
  ON ukos_doppik.ueberweg
  FOR EACH ROW
  EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();


COMMIT;
