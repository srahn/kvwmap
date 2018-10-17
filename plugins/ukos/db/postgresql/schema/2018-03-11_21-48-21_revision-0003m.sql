ALTER TABLE ukos_strassennetz.strasse ALTER COLUMN nachrichtlich SET NOT NULL;

ALTER TABLE ukos_strassennetz.strassenelement ALTER COLUMN nachrichtlich SET NOT NULL;

ALTER TABLE ukos_strassennetz.verbindungspunkt ALTER COLUMN nachrichtlich SET NOT NULL;
