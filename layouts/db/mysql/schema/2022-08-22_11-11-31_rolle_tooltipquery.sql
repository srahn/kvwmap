BEGIN;

ALTER TABLE `rolle` CHANGE `highlighting` `tooltipquery` TINYINT(1) NOT NULL DEFAULT '0';

COMMIT;
