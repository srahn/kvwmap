ALTER TABLE strassennetz.strasse ADD COLUMN nachrichtlich boolean;
ALTER TABLE strassennetz.strasse ALTER COLUMN nachrichtlich SET DEFAULT false;

ALTER TABLE strassennetz.strassenelement ADD COLUMN nachrichtlich boolean;
ALTER TABLE strassennetz.strassenelement ALTER COLUMN nachrichtlich SET DEFAULT false;

ALTER TABLE strassennetz.verbindungspunkt ADD COLUMN nachrichtlich boolean;
ALTER TABLE strassennetz.verbindungspunkt ALTER COLUMN nachrichtlich SET DEFAULT false;
