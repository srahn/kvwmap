BEGIN;

ALTER TABLE `stelle` DROP `alb_raumbezug`, DROP `alb_raumbezug_wert`;

COMMIT;
