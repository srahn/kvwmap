BEGIN;

	INSERT INTO config (`name`, `value`, `prefix`, `type`, `description`, `group`, `plugin`, `saved`) VALUES
		('XPLANKONVERTER_SEND_NOTIFICATION', 'true', '', 'boolean', 'Standardwert für das Versenden von Benachrichtigungen im Fehlerfall. Sendet E-Mail-Benachrichtigung wenn nicht anders beim Aufruf der Funktion send_error angegeben.', 'Plugins/xplankonverter', 'xplankonverter', 1),
		('XPLANKONVERTER_CREATE_TICKET', 'true', '', 'boolean', 'Standardwert für das Erzeugen eines Tickets im Fehlerfall. Erzeugt Ticket wenn nicht anders beim Aufruf der Funktion send_error angegeben.', 'Plugins/xplankonverter', 'xplankonverter', 1);

COMMIT;
