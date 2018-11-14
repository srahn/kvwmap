BEGIN;

	INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
		('OWS_HOURSOFSERVICE', '', 'Wochentags 8:00 - 16:00 Uhr', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 0),
		('OWS_CONTACTINSTRUCTIONS', '', 'Telefon oder E-Mail', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 0),
		('OWS_ROLE', '', 'GIS-Administrator', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 0);

COMMIT;
