BEGIN;
  UPDATE
    kvwmap.classes c
  SET
    expression = '(NOT (''[massnahme]'' IN ''1000'',''2000'') OR ''[massnahme]'' = '''')'
  FROM
    kvwmap.layer l
  WHERE
    c.name = 'allgemein' AND
    c.layer_id = l.layer_id AND
    l.name = 'bp_anpflanzungbindungerhaltung_polygons';

  UPDATE
    kvwmap.classes c
  SET
    expression = '(NOT (''[zweckbestimmung]'' IN ''1000'',''1200'',''1100'',''1400'',''1300'',''1500'',''1800'') OR  ''[zweckbestimmung]'' = ''9999'' OR ''[zweckbestimmung]'' = '''')'
  FROM
    kvwmap.layer l
  WHERE
    c.name = 'allgemein' AND
    c.layer_id = l.layer_id AND
    l.name = 'bp_verkehrsflaechebesondererzweckbestimmung_points';

  UPDATE
    kvwmap.classes c
  SET
    expression = '(NOT (''[allgartderbaulnutzung]'' IN  ''1000'',''2000'',''3000'',''4000'')'
  FROM
    kvwmap.layer l
  WHERE
    c.name = 'Sonstige Bauflaechen' AND
    c.layer_id = l.layer_id AND
    l.name = 'fp_bebauungsflaeche_polygons';

COMMIT;