# Hinzufügen von Plattdeutsch bei der Angabe einer Sprache und Character Set für die Rolle
ALTER TABLE `rolle` CHANGE `language` `language` ENUM( 'german', 'low-german', 'english', 'vietnamese' ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT 'german';

# Hinzufügen einer Spalte für die plattdeutsche Bezeichnung der Stellen
ALTER TABLE `stelle` ADD `Bezeichnung_low-german_windows-1252` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NULL AFTER `Bezeichnung`;
# Übernahme der deutschen Bezeichnungen der Stellen als vorläufige Maßnahme:
# Überarbeiten!
UPDATE `stelle` SET `Bezeichnung_low-german_windows-1252` = `Bezeichnung`;

# Neue Spalte für Plattdeutsche Menübezeichnung in der Tabelle u_menues
ALTER TABLE `u_menues` ADD `name_low-german_windows-1252` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_german1_ci NULL AFTER `name`;
# Übernahme der deutschen Bezeichnungen der Menüs als vorläufige Maßnahme:
# Überarbeiten!
UPDATE `u_menues` SET `name_low-german_windows-1252` = `name`;

# Übersetzungshilfen Deutsch - Plattdeutsch
#
# Administration: Administratschoon
# Adresse: Adress
# Antrag: Andrag
# Allgemein: Allgemeen
# Auskunft: Utkunft
# Blatt: Blääd (Mz. Bläder)
# Bodenrichtwert: Boddenrichtweert
# Drucken: Printen
# Druckrahmen: Printrohmen
# Eigentümer: Egendömer
# Fachschale: Fachschaal
# Festpunkt: Wisstüttel
# Funktion: Funkschoon (Mz. Funkschonen)
# Gutachterausschuss: Utschuss vun'n Gootachters
# Hilfe: Hülp
# Karte: Koort
# Nachweis: Nahwies
# Nutzer: Bruker
# Sonstige: Süssige
# Stelle: Stäe
# Suche: Sök
# Zone: Zoon
#
# Abrechnung: Afreken
# Änderung: Ännerung
# Erfassung: Upnahm
# Verwaltung: Verwalten
#
# aktualisieren: opfrischen
# anlegen: anleggen
# eingeben: ingeven
# einfügen: infögen
# erstellen: herstellen
# kopieren:  koperen
# übernehmen: övernehmen

