BEGIN;

  CREATE EXTENSION IF NOT EXISTS postgis_topology;

  CREATE TABLE xplankonverter.flaechenschlussobjekte (
      gml_id uuid NOT NULL,
      konvertierung_id integer NOT NULL,
      teilpolygon_nr integer,
      teilpolygon geometry(Polygon,25833),
      CONSTRAINT flaechenschlussobjekte_pkey PRIMARY KEY (gml_id, konvertierung_id)
  )
  WITH ( OIDS = TRUE );

  SELECT CreateTopology('flaechenschluss_topology', 25833, 0.002)
  WHERE NOT EXISTS (
      SELECT * FROM topology.topology WHERE name = 'flaechenschluss_topology'
  );

  SELECT topology.AddTopoGeometryColumn('flaechenschluss_topology', 'xplankonverter', 'flaechenschlussobjekte', 'topo', 'POLYGON');

  INSERT INTO xplankonverter.validierungen(
  	name, beschreibung, functionsname, msg_success, msg_error, msg_correcture
  )
  VALUES (
    'Keine Überlagerung von Flächenschlussobjekten',
    'Es wird geprüft ob es Überschneidungen zwischen Objekte mit Attribut flaechenschluss = true und ebene = 0 gibt, da diese die Flächenschlussbedingung nicht erfüllen. Gilt zusammen mit der Prüfung der Lücken als Validierung des Flächenschlusses in BP- und FP-Plänen.',
    'flaechenschluss_ueberlappungen',
    'Es gibt keine Überlappungen von Flächenschlussobjekten.',
    'Die Flächenschlussobjekte erfüllen nicht die Bedingung es Flächenschlusses. Es gibt Überlappungen.',
    'Stellen Sie sicher, dass sich keine Objekte mit Attribut flaechenschluss = true und ebene = 0 gegenseitig überlappen.'
  ), (
    'Keine Lücken zwischen Flächenschlussobjekten und Geltungsbereich',
    'Es wird geprüft ob alle Objekte mit Attribut flaechenschluss = true und ebene = 0 zusammengenommen den Geltungsbereich flächig vollständig abdecken. Wenn nicht, wird die flächenschlussbedingung nicht erfüllt. Gilt zusammen mit der Prüfung der Überlagerung als Validierung des Flächenschlusses in BP- und FP-Plänen.',
    'flaechenschluss_luecken',
    'Es gibt keine Lücken zwischen Flächenschlussobjekten und dem Geltungsbereich. Die Flächenschlussbedingung ist erfüllt.',
    'Die Flächenschlussobjekte erfüllen nicht die Bedingung es Flächenschlusses. Es gibt Lücken.',
    'Stellen Sie sicher, dass es keine Lücken zwischen Objekte mit Attribut flaechenschluss = true und ebene = 0 untereinander und zur Außenlinie des Geltungsbereiches gibt. Alle Flächenschlussobjekte zusammengenommen müssen die Fläche des Geltungsbereiches abdecken.'
  );

COMMIT;