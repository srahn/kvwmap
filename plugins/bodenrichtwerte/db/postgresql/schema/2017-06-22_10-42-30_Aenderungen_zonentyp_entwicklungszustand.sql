BEGIN;

UPDATE bodenrichtwerte.bw_zonen SET zonentyp = 'Ackerland' WHERE zonentyp LIKE 'Ackerlandfläche';
UPDATE bodenrichtwerte.bw_zonen SET zonentyp = 'sonstige Flächen' WHERE zonentyp LIKE 'Gartenfläche';
UPDATE bodenrichtwerte.bw_zonen SET zonentyp = 'gemischte Bauflächen' WHERE zonentyp LIKE 'gemischte Baufläche';
UPDATE bodenrichtwerte.bw_zonen SET zonentyp = 'gewerbliche Bauflächen' WHERE zonentyp LIKE 'Gewerbeflächen';
UPDATE bodenrichtwerte.bw_zonen SET zonentyp = 'Grünland' WHERE zonentyp LIKE 'Grünlandfläche';
UPDATE bodenrichtwerte.bw_zonen SET zonentyp = 'Wohnbauflächen' WHERE zonentyp LIKE 'Wohnbaufläche';
UPDATE bodenrichtwerte.bw_zonen SET zonentyp = 'Sonderbauflächen' WHERE zonentyp LIKE 'Sonderbaufläche';
UPDATE bodenrichtwerte.bw_zonen SET zonentyp = 'Sanierungsgebiet' WHERE zonentyp LIKE 'Sanierungsflächen';

UPDATE bodenrichtwerte.bw_zonen SET entwicklungszustand = 'R ' WHERE entwicklungszustand LIKE 'R';
UPDATE bodenrichtwerte.bw_zonen SET entwicklungszustand = 'E ' WHERE entwicklungszustand LIKE 'E';
UPDATE bodenrichtwerte.bw_zonen SET entwicklungszustand = 'B ' WHERE entwicklungszustand LIKE 'B';

COMMIT;