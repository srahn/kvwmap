BEGIN;
  UPDATE xplankonverter.validierungen
  SET msg_correcture = 'Vergeben Sie einen räumlichen Geltungsbereich für den Plan.'
  WHERE msg_correcture = 'Vergeben Sie einen räumlichen Geltungsbereich für den Plan an.';
COMMIT;
