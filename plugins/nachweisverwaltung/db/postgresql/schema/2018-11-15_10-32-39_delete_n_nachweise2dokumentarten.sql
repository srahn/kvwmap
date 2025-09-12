BEGIN;

SELECT setval('nachweisverwaltung.n_dokumentarten_id_seq', max(id), true)
FROM nachweisverwaltung.n_dokumentarten;


--DELETE 
--FROM nachweisverwaltung.n_nachweise2dokumentarten n2d
--USING nachweisverwaltung.n_nachweise n, nachweisverwaltung.n_nachweise2dokumentarten n2d2
--LEFT JOIN nachweisverwaltung.n_dokumentarten d ON d.id = n2d2.dokumentart_id
--WHERE n2d.oid = n2d2.oid 
--AND n.id = n2d.nachweis_id
--AND (d.hauptart != n.art OR d.id IS NULL);


INSERT INTO nachweisverwaltung.n_dokumentarten (art, geometrie_relevant, hauptart)
select 'Fortführungsriss', true, 1
where not exists (SELECT * FROM nachweisverwaltung.n_dokumentarten WHERE art = 'Fortführungsriss');

INSERT INTO nachweisverwaltung.n_nachweise2dokumentarten (nachweis_id, dokumentart_id)
SELECT n.id, (SELECT id FROM nachweisverwaltung.n_dokumentarten WHERE art = 'Fortführungsriss')
FROM nachweisverwaltung.n_nachweise n
LEFT JOIN nachweisverwaltung.n_nachweise2dokumentarten n2d ON n2d.nachweis_id = n.id
WHERE n.art = 1
AND n2d.nachweis_id IS NULL;


INSERT INTO nachweisverwaltung.n_dokumentarten (art, geometrie_relevant, hauptart)
select 'Koordinatenverzeichnis', true, 2
where not exists (SELECT * FROM nachweisverwaltung.n_dokumentarten WHERE art = 'Koordinatenverzeichnis');

INSERT INTO nachweisverwaltung.n_nachweise2dokumentarten (nachweis_id, dokumentart_id)
SELECT n.id, (SELECT id FROM nachweisverwaltung.n_dokumentarten WHERE art = 'Koordinatenverzeichnis')
FROM nachweisverwaltung.n_nachweise n
LEFT JOIN nachweisverwaltung.n_nachweise2dokumentarten n2d ON n2d.nachweis_id = n.id
WHERE n.art = 2 
AND n2d.nachweis_id IS NULL;


INSERT INTO nachweisverwaltung.n_dokumentarten (art, geometrie_relevant, hauptart)
select 'Grenzniederschrift', true, 3
where not exists (SELECT * FROM nachweisverwaltung.n_dokumentarten WHERE art = 'Grenzniederschrift');

INSERT INTO nachweisverwaltung.n_nachweise2dokumentarten (nachweis_id, dokumentart_id)
SELECT n.id, (SELECT id FROM nachweisverwaltung.n_dokumentarten WHERE art = 'Grenzniederschrift')
FROM nachweisverwaltung.n_nachweise n
LEFT JOIN nachweisverwaltung.n_nachweise2dokumentarten n2d ON n2d.nachweis_id = n.id
WHERE n.art = 3 
AND n2d.nachweis_id IS NULL;


UPDATE nachweisverwaltung.n_nachweise n
SET art = dokumentart_id
FROM nachweisverwaltung.n_nachweise2dokumentarten n2d
WHERE n2d.nachweis_id = n.id;


DROP table nachweisverwaltung.n_nachweise2dokumentarten;

COMMIT;
