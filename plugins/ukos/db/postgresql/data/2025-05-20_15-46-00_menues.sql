INSERT INTO kvwmap.u_menues (name, links, menueebene, title)
SELECT
  *
FROM (
  SELECT 'Datensätze anlegen' AS name, 'index.php?go=changemenue' AS liks, 1 AS menueebene, 'Neue Datensätze anlegen' AS title
) AS tmp
WHERE NOT EXISTS (
  SELECT name FROM kvwmap.u_menues WHERE name = 'Datensätze anlegen'
);

INSERT INTO kvwmap.u_menues (name, links, obermenue, menueebene, title, "order")
SELECT
  *
FROM (
  SELECT
    'Doppikobjekte' AS name,
    'index.php?go=ukos_show_doppikklassen' AS links,
    (SELECT id FROM kvwmap.u_menues WHERE name = 'Datensätze anlegen') AS obermenue,
    2 AS menueebene,
    'Doppikobjekte anlegen' AS title,
    100 AS "order"
) tmp
WHERE NOT EXISTS (
  SELECT name FROM kvwmap.u_menues WHERE name = 'Doppikobjekte'
);

