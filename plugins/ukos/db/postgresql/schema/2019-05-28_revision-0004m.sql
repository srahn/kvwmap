BEGIN;

ALTER TABLE ukos_okstra.strassenelement ADD COLUMN kfz_verkehr boolean;
ALTER TABLE ukos_okstra.strassenelement ADD COLUMN radverkehr boolean;
ALTER TABLE ukos_okstra.strassenelement ADD COLUMN fussgaengerverkehr boolean;

COMMIT;