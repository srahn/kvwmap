ALTER TABLE strassennetz.strasse ALTER COLUMN nachrichtlich SET NOT NULL;

ALTER TABLE strassennetz.strassenelement ALTER COLUMN nachrichtlich SET NOT NULL;

ALTER TABLE strassennetz.verbindungspunkt ALTER COLUMN nachrichtlich SET NOT NULL;
