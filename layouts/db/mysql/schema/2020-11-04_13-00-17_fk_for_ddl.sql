BEGIN;
  DELETE
    ddl2freilinien
  FROM
    ddl2freilinien LEFT JOIN
    druckfreilinien ON ddl2freilinien.line_id = druckfreilinien.id
  WHERE
    druckfreilinien.id IS NULL;

  ALTER TABLE ddl2freilinien ADD FOREIGN KEY (line_id) REFERENCES druckfreilinien (id) ON DELETE CASCADE;

  DELETE
    ddl2freitexte
  FROM
    ddl2freitexte LEFT JOIN
    druckfreitexte ON ddl2freitexte.freitext_id = druckfreitexte.id
  WHERE
    druckfreitexte.id IS NULL;

  ALTER TABLE ddl2freitexte ADD FOREIGN KEY (freitext_id) REFERENCES druckfreitexte (id) ON DELETE CASCADE;

  DELETE
    ddl2freirechtecke
  FROM
    ddl2freirechtecke LEFT JOIN
    druckfreirechtecke ON ddl2freirechtecke.rect_id = druckfreirechtecke.id
  WHERE
    druckfreirechtecke.id IS NULL;

  ALTER TABLE ddl2freirechtecke ADD FOREIGN KEY (rect_id) REFERENCES druckfreirechtecke (id) ON DELETE CASCADE;

COMMIT;
