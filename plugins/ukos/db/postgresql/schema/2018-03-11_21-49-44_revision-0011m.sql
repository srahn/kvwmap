ALTER TABLE kataster.kreis DROP CONSTRAINT uk1_kreis;
ALTER TABLE kataster.kreis ADD CONSTRAINT uk1_kreis UNIQUE (schluessel, gueltig_bis);

ALTER TABLE kataster.gemeindeverband DROP CONSTRAINT uk1_gemeindeverband;
ALTER TABLE kataster.gemeindeverband ADD CONSTRAINT uk1_gemeindeverband UNIQUE (id_kreis, schluessel, gueltig_bis);

ALTER TABLE kataster.gemeinde DROP CONSTRAINT uk1_gemeinde;
ALTER TABLE kataster.gemeinde ADD CONSTRAINT uk1_gemeinde UNIQUE (id_gemeindeverband, schluessel, gueltig_bis);

ALTER TABLE kataster.gemeindeteil DROP CONSTRAINT uk1_gemeindeteil;
ALTER TABLE kataster.gemeindeteil ADD CONSTRAINT uk1_gemeindeteil UNIQUE (id_gemeinde, schluessel, gueltig_bis);
