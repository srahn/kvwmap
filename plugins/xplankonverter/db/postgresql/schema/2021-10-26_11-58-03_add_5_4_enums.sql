--BEGIN; Can not run in a transaction

  UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr SET beschreibung = 'Flächen für Ladeinfrastruktur elektrisch betriebener Fahrzeuge.' WHERE wert = '3500';

  ALTER TYPE xplan_gml.xp_zweckbestimmunggruen ADD VALUE '2700' AFTER '1000';
  INSERT INTO xplan_gml.enum_xp_zweckbestimmunggruen (wert, abkuerzung, beschreibung)
  VALUES ('2700', 'Naturerfahrungsraum', 'Naturerfahrungsräume sollen insbesondere Kindern und Jugendlichen die Möglichkeit geben, in ihrem direkten Umfeld Natur vorzufinden, um eigenständig Erfahrung mit Pflanzen und Tieren sammeln zu können.');

  ALTER TYPE xplan_gml.bp_planart ADD VALUE '10002' AFTER '10001';
  INSERT INTO xplan_gml.enum_bp_planart (wert, abkuerzung, beschreibung)
  VALUES ('10002', 'Bebauungsplan zur Wohnraumversorgung', 'Bebauungsplan zur Wohnraumversorgung für im Zusammenhang bebaute Ortsteile (§ 34) nach §9 Absatz 2d BauGB');

  ALTER TYPE xplan_gml.so_gebietsart ADD VALUE '2300' AFTER '2200';
  INSERT INTO xplan_gml.enum_so_gebietsart (wert, abkuerzung, beschreibung)
  VALUES ('2300', 'Städtebauliche Entwicklungskonzept Innenentwicklung', 'Städtebauliches Entwicklungskonzept zur Stärkung der Innenentwicklung.');

  ALTER TYPE xplan_gml.so_gebietsart ADD VALUE '2400' AFTER '2300';
  INSERT INTO xplan_gml.enum_so_gebietsart (wert, abkuerzung, beschreibung)
  VALUES ('2400', 'Gebiet mit angespanntem Wohnungsmarkt', 'Gebiet mit einem angespannten Wohnungsmarkt.');

  ALTER TYPE xplan_gml.so_gebietsart ADD VALUE '2500' AFTER '2400';
  INSERT INTO xplan_gml.enum_so_gebietsart (wert, abkuerzung, beschreibung)
  VALUES ('2500', 'Genehmigung Wohnungseigentum', 'Gebiet mit angespanntem Wohnungsmarkt, in dem die Begründung oder Teilung von Wohnungseigentum oder Teileigentum der Genehmigung bedarf.');

  ALTER TYPE xplan_gml.xp_besondereartderbaulnutzung ADD VALUE '1450' AFTER '1440';
  INSERT INTO xplan_gml.enum_xp_besondereartderbaulnutzung (wert, abkuerzung, beschreibung)
  VALUES ('1450', 'Dörfliches Wohngebiet', 'Dörfliches Wohngebiet nach §5a BauNVO.');

  ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '3100' AFTER '3000';
  INSERT INTO xplan_gml.enum_xp_externereferenztyp (wert, beschreibung)
  VALUES ('3100', 'Städtebauliches Entwicklungskonzept zur Stärkung der Innenentwicklung.');

--COMMIT;
