ALTER TABLE ukos_strassennetz.strasse ADD COLUMN nachrichtlich boolean;
ALTER TABLE ukos_strassennetz.strasse ALTER COLUMN nachrichtlich SET DEFAULT false;

ALTER TABLE ukos_strassennetz.strassenelement ADD COLUMN nachrichtlich boolean;
ALTER TABLE ukos_strassennetz.strassenelement ALTER COLUMN nachrichtlich SET DEFAULT false;

ALTER TABLE ukos_strassennetz.verbindungspunkt ADD COLUMN nachrichtlich boolean;
ALTER TABLE ukos_strassennetz.verbindungspunkt ALTER COLUMN nachrichtlich SET DEFAULT false;
