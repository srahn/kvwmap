CREATE INDEX gemeinde_s ON kataster.gemeinde USING gist(wkb_geometry);

CREATE INDEX gemeindeteil_s ON kataster.gemeindeteil USING gist(wkb_geometry);

CREATE INDEX gemeindeverband_s ON kataster.gemeindeverband USING gist(wkb_geometry);

CREATE INDEX kreis_s ON kataster.kreis USING gist(wkb_geometry);
