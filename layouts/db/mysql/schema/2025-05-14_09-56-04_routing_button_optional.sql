BEGIN;

UPDATE 
	`rolle` 
SET 
	buttons = concat(buttons, 'routing,') 
WHERE
	(select value from config where	config.name = 'ROUTING_URL') != '';

COMMIT;
