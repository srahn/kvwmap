BEGIN;
  INSERT INTO notifications (notification, ablaufdatum) VALUES (
    'Die Liste der Pläne hat jetzt zusätzlich die Attribute Genehmigung und Bekanntmachung zur Auswahhl. Außerdem werden die Einstellungen für die Sichtbarkeit der Attribute in der Tabelle für den Nutzer pro Stelle gespeichert. In den zusätzlichen Attributen aber auch in den schon vorhandenen ID-Attributen kann jetzt auch gesucht werden.',
    DATE(NOW() + INTERVAL 6 MONTH)
  );
  SET @last_insert_id = LAST_INSERT_ID();
  INSERT INTO user2notifications (notification_id, user_id) SELECT @last_insert_id, ID FROM user WHERE archived IS NULL OR archived = 1;

  INSERT INTO notifications (notification, ablaufdatum) VALUES (
    'Die Attributgruppe für Zeitangaben von B- und F-Plänen wurde umbenannt von Status in Chronologie. Die Reihenfolge und teilweise die Bezeichnung der Attribute hat sich auch geändert. Das Attribut Datum der Veröffentlichung wurde in die Gruppe Zusatzinfos verschoben.',
    DATE(NOW() + INTERVAL 6 MONTH)
  );
  SET @last_insert_id = LAST_INSERT_ID();
  INSERT INTO user2notifications (notification_id, user_id) SELECT @last_insert_id, ID FROM user WHERE archived IS NULL OR archived = 1;
COMMIT;