BEGIN;

UPDATE bodenrichtwerte.bw_zonen SET basiskarte = 'ALKIS' WHERE basiskarte = 'ALK';
UPDATE bodenrichtwerte.bw_zonen SET basiskarte = 'ALKISDOP' WHERE basiskarte = 'ALKDOP';

COMMIT;
