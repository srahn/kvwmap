BEGIN;

CREATE TABLE ukos_okstra.wlo_art_des_verbindungspunktes (
 CONSTRAINT pk_wlo_art_des_verbindungspunktes PRIMARY KEY (kennung)
) INHERITS (
 ukos_base.werteliste
 );

INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('nd', 'nicht definiert');
INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('gw', 'Gemeindewechsel');
INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('kv', 'Kreisverkehr');
INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('kr', 'Kreuzung');
INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('sk', 'Straßenklasse');
INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('we', 'Wechsel');
INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('wa', 'Wendeanlage');

ALTER TABLE ukos_okstra.verbindungspunkt ADD art_des_verbindungspunktes character varying DEFAULT 'nd';

UPDATE ukos_okstra.verbindungspunkt SET
 art_des_verbindungspunktes = 'nd';

ALTER TABLE ukos_okstra.verbindungspunkt ALTER COLUMN art_des_verbindungspunktes SET NOT NULL;

ALTER TABLE ukos_okstra.verbindungspunkt ADD CONSTRAINT fk_verbindungspunkt_art_des_verbindungspunktes FOREIGN KEY (art_des_verbindungspunktes) REFERENCES ukos_okstra.wlo_art_des_verbindungspunktes (kennung);

COMMIT;
