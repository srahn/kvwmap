BEGIN;

ALTER TABLE ukos_okstra.verbindungspunkt DROP CONSTRAINT fk_verbindungspunkt_art_des_verbindungspunktes;

ALTER TABLE ukos_okstra.verbindungspunkt ADD CONSTRAINT fk_verbindungspunkt_art_des_verbindungspunktes FOREIGN KEY (art_des_verbindungspunktes) REFERENCES ukos_okstra.wlo_art_des_verbindungspunktes (kennung) MATCH SIMPLE ON UPDATE CASCADE ON DELETE RESTRICT;

UPDATE ukos_okstra.wlo_art_des_verbindungspunktes SET kennung = '00', langtext = 'nicht zugewiesen' WHERE kennung = 'nd';
UPDATE ukos_okstra.wlo_art_des_verbindungspunktes SET kennung = '02', langtext = 'Gemeindegrenze' WHERE kennung = 'gw';
UPDATE ukos_okstra.wlo_art_des_verbindungspunktes SET kennung = '03' WHERE kennung = 'kr';
UPDATE ukos_okstra.wlo_art_des_verbindungspunktes SET kennung = '04' WHERE kennung = 'kv';
UPDATE ukos_okstra.wlo_art_des_verbindungspunktes SET kennung = '05', langtext = 'Nutzungsänderung' WHERE kennung = 'sk';
UPDATE ukos_okstra.wlo_art_des_verbindungspunktes SET kennung = '07', langtext = 'Wechsel Straßenbezeichnung' WHERE kennung = 'we';
UPDATE ukos_okstra.wlo_art_des_verbindungspunktes SET kennung = '08' WHERE kennung = 'wa';

INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('01', 'Einmündung');
INSERT INTO ukos_okstra.wlo_art_des_verbindungspunktes (kennung, langtext) VALUES ('06', 'Ortsein- und -ausgang');

ALTER TABLE ukos_okstra.verbindungspunkt DROP CONSTRAINT fk_verbindungspunkt_art_des_verbindungspunktes;

ALTER TABLE ukos_okstra.verbindungspunkt ADD CONSTRAINT fk_verbindungspunkt_art_des_verbindungspunktes FOREIGN KEY (art_des_verbindungspunktes) REFERENCES ukos_okstra.wlo_art_des_verbindungspunktes (kennung) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION;

COMMIT;