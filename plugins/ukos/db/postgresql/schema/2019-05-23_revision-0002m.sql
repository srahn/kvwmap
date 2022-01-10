BEGIN;

DROP TRIGGER __first_snap_to_grid ON ukos_kataster.gemeinde;
DROP TRIGGER __first_snap_to_grid ON ukos_kataster.gemeindeteil;
DROP TRIGGER __first_snap_to_grid ON ukos_kataster.gemeindeverband;
DROP TRIGGER __first_snap_to_grid ON ukos_kataster.kreis;

COMMIT;