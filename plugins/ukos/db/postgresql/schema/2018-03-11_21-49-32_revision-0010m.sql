ALTER TABLE strassennetz.strasse DROP CONSTRAINT uk1_strasse;
ALTER TABLE strassennetz.strasse ADD CONSTRAINT uk1_strasse UNIQUE (id_gemeinde, schluessel, gueltig_bis);
