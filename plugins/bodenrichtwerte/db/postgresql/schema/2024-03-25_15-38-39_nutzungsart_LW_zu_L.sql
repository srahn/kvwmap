BEGIN;

UPDATE bodenrichtwerte.bw_zonen
SET nutzungsart = 'L'
WHERE nutzungsart = 'LW' AND stichtag = '01.01.2024';

COMMIT;
