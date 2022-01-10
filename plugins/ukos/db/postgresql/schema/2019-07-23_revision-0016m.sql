BEGIN;

ALTER TABLE ukos_okstra.verbindungspunkt ALTER COLUMN art_des_verbindungspunktes SET DEFAULT '00';

COMMIT;
