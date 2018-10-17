CREATE INDEX gemeinde_s ON ukos_kataster.gemeinde USING gist(wkb_geometry);

CREATE INDEX gemeindeteil_s ON ukos_kataster.gemeindeteil USING gist(wkb_geometry);

CREATE INDEX gemeindeverband_s ON ukos_kataster.gemeindeverband USING gist(wkb_geometry);

CREATE INDEX kreis_s ON ukos_kataster.kreis USING gist(wkb_geometry);
