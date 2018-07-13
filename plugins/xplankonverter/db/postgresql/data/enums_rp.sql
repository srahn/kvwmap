BEGIN;
-- Version vom 05.06.2018 17:40
-- gewählte Pakete: 'Basisklassen', 'Raumordnungsplan', 'RP__Basisobjekte', 'RP_Freiraumstruktur', 'RP_Infrastruktur', 'RP_Raster', 'RP_Siedlungsstruktur', 'RP_Sonstiges', 'XP_Basisobjekte', 'XP_Enumerationen', 'XP_Praesentationsobjekte', 'XP_Raster'
-- gewählte Filter: Ohne Attribute objektkoordinaten.
SET search_path = xplan_gml, public;

TRUNCATE
  enum_xp_arthoehenbezugspunkt;
INSERT INTO
  enum_xp_arthoehenbezugspunkt (wert,beschreibung)
VALUES
  ('1000', 'TH', 'Traufhöhe als Höhenbezugspunkt'),
  ('2000', 'FH', 'Firsthöhe als Höhenbezugspunkt.'),
  ('3000', 'OK', 'Oberkante als Höhenbezugspunkt.'),
  ('3500', 'LH', 'Lichte Höhe'),
  ('4000', 'SH', 'Sockelhöhe'),
  ('4500', 'EFH', 'Erdgeschoss Fußbodenhöhe'),
  ('5000', 'HBA', 'Höhe Baulicher Anlagen'),
  ('5500', 'UK', 'Unterkante'),
  ('6000', 'GBH', 'Gebäudehöhe');

TRUNCATE
  enum_xp_spemassnahmentypen;
INSERT INTO
  enum_xp_spemassnahmentypen (wert,beschreibung)
VALUES
  ('1000', 'ArtentreicherGehoelzbestand', 'Artenreicher Gehölzbestand ist aus unterschiedlichen, standortgerechten Gehölzarten aufgebaut und weist einen Strauchanteil auf.'),
  ('1100', 'NaturnaherWald', 'Naturnahe Wälder zeichnen sich durch eine standortgemäße Gehölzzusammensetzung unterschiedlicher Altersstufen, durch eine Schichtung der Gehölze (z.B. Strauchschicht, sich überlagernder erster Baumschicht in 10-15 m Höhe und zweiter Baumschicht in 20-25 m Höhe) sowie durch eine in der Regeln artenreiche Krautschicht aus. Kennzeichnend sind zudem das gleichzeitige Nebeneinander von aufwachsenden Gehölzen, Altbäumen und Lichtungen in kleinräumigen Wechsel sowie ein gewisser Totholzanteil.'),
  ('1200', 'ExtensivesGruenland', 'Gegenüber einer intensiven Nutzung sind bei extensiver Grünlandnutzung sowohl Beweidungsintensitäten als auch der Düngereinsatz deutlich geringer. Als Folge finden eine Reihe von eher konkurrenzschwachen, oft auch trittempflindlichen Pflanzenarten Möglichkeiten, sich neben den in der Regel sehr robusten, wuchskräftigen, jedoch sehr nährstoffbedürftigen Pflanzen intensiver Wirtschaftsflächen zu behaupten.  Dadurch kommt es zur Ausprägung von standortbedingt unterschiedlichen Grünlandgesellschaften mit deutlichen höheren Artenzahlen (größere Vielfalt).'),
  ('1300', 'Feuchtgruenland', 'Artenreiches Feuchtgrünland entwickelt sich bei extensiver Bewirtschaftung auf feuchten bis wechselnassen Standorten. Die geringe Tragfähigkeit des vielfach anstehenden Niedermoorbodens erschwert den Einsatz von Maschinen, so dass die Flächen vorwiegend beweidet bzw. erst spät im Jahr gemäht werden.'),
  ('1400', 'Obstwiese', 'Obstwiesen umfassen mittel- oder hochstämmige, großkronige Obstbäume auf beweidetem (Obstweide) oder gemähtem (obstwiese) Grünland. Im Optimalfall setzt sich der aufgelockerte Baumbestand aus verschiedenen, möglichst alten, regional-typischen Kultursorten zusammen.'),
  ('1500', 'NaturnaherUferbereich', 'Naturahne Uferbereiche umfassen unterschiedlich zusammengesetzte Röhrichte und Hochstaudenrieder oder Seggen-Gesellschaften sowie Ufergehölze, die sich vorwiegend aus strauch- oder baumförmigen Weiden, Erlen oder Eschen zusammensetzen.'),
  ('1600', 'Roehrichtzone', 'Im flachen Wasser oder auf nassen Böden bilden sich hochwüchsige, oft artenarme Bestände aus überwiegend windblütigen Röhrichtarten aus. Naturliche Bestände finden sich im Uferbereich von Still- und Fließgewässern.'),
  ('1700', 'Ackerrandstreifen', 'Ackerrandstreifen sind breite Streifen im Randbereich eines konventionell oder ökologisch genutzten Ackerschlages.'),
  ('1800', 'Ackerbrache', 'Als Ackerbrachflächen werden solche Biotope angesprochen, die seit kurzer Zeit aus der Nutzung herausgenommen worden sind. Sie entstehen, indem Ackerflächen mindestens eine Vegetationsperiode nicht mehr bewirtschaftet werden.'),
  ('1900', 'Gruenlandbrache', 'Als Grünlandbrachen werden solche Biotope angesprochen, die seit kurzer Zeit aus der Nutzung herausgenommen worden sind. Sie entstehen, indem Grünland mindestens eine Vegetationsperiode nicht mehr bewirtschaftet wird.'),
  ('2000', 'Sukzessionsflaeche', 'Sukzessionsflächen umfassen dauerhaft ungenutzte, der natürlichen Entwicklung überlassene Vegetationsbestände auf trockenen bis feuchten Standorten.'),
  ('2100', 'Hochstaudenflur', 'Hochwüchsige, zumeist artenreiche Staudenfluren feuchter bis nasser Standorte entwickeln sich in der Regel auf Feuchtgrünland-Brachen, an gehölzfreien Uferstreifen oder an anderen zeitweilig gestörten Standorten mit hohen Grundwasserständen.'),
  ('2200', 'Trockenrasen', 'Trockenrasen sind durch zumindest zeitweilige extreme Trockenheit (Regelwasser versickert rasch) sowie durch Nährstoffarmut charakterisiert, die nur Arten mit speziell angepassten Lebensstrategien Entwicklungsmöglichkeiten bieten.'),
  ('2300', 'Heide', 'Heiden sind Zwergstrauchgesellschaften auf nährstoffarmen, sauren, trockenen (Calluna-Heide) oder feuchten (Erica-Heide) Standorten. Im Binnenland haben sie in der Regel nach Entwaldung (Abholzung) und langer Übernutzung (Beweidung) primär nährstoffarmer Standorte entwickelt.'),
  ('9999', 'Sonstiges', 'Sonstiges');

TRUNCATE
  enum_xp_rechtsstand;
INSERT INTO
  enum_xp_rechtsstand (wert,beschreibung)
VALUES
  ('1000', 'Geplant', 'Der Planinhalt bezieht sich auf eine Planung'),
  ('2000', 'Bestehend', 'Der Planinhalt stellt den aktuellen Zustand dar.'),
  ('3000', 'Fortfallend', 'Der Planinhalt beschreibt einen zukünftig fortfallenden Zustand.');

TRUNCATE
  enum_xp_arthoehenbezug;
INSERT INTO
  enum_xp_arthoehenbezug (wert,beschreibung)
VALUES
  ('1000', 'absolutNHN', 'Absolute Höhenangabe'),
  ('2000', 'relativGelaendeoberkante', 'Höhenangabe relativ zur Geländeoberkante an der Position des Planinhalts.'),
  ('2500', 'relativGehwegOberkante', 'Höhenangabe relativ zur Gehweg-Oberkante an der Position des Planinhalts.'),
  ('3000', 'relativBezugshoehe', 'Höhenangabe relativ zu der auf Planebene festgelegten absoluten Bezugshöhe (Attribut bezugshoehe von XP_Plan).');

TRUNCATE
  enum_xp_rechtscharakterplanaenderung;
INSERT INTO
  enum_xp_rechtscharakterplanaenderung (wert,beschreibung)
VALUES
  ('1000', 'Aenderung', 'Änderung eines Planes: Der Geltungsbereich des neueren Plans überdeckt nicht den gesamten Geltungsbereich des Ausgangsplans. Im Überlappungsbereich gilt das neuere Planrecht.'),
  ('1100', 'Ergaenzung', 'Ergänzung eines Plans: Die Inhalte des neuen Plans ergänzen die alten Inhalte, z.B. durch zusätzliche textliche Planinhalte oder Überlagerungsobjekte. Die Inhalte des älteren Plans bleiben aber gültig.'),
  ('2000', 'Aufhebung', 'Aufhebung des Plans: Der Geltungsbereich des neuen Plans überdeckt den alten Plan, und die Inhalte des neuen Plans ersetzen die alten Inhalte  vollständig.');

TRUNCATE
  enum_xp_bedeutungenbereich;
INSERT INTO
  enum_xp_bedeutungenbereich (wert,beschreibung)
VALUES
  ('1600', 'Teilbereich', 'Räumliche oder sachliche Aufteilung der Planinhalte.'),
  ('1800', 'Kompensationsbereich', 'Aggregation von Objekten außerhalb des Geltungsbereiches gemäß Eingriffsregelung.'),
  ('9999', 'Sonstiges', 'Bereich, für den keine der aufgeführten Bedeutungen zutreffend ist. In dem Fall kann die Bedeutung über das Textattribut "detaillierteBedeutung" angegeben werden.');

TRUNCATE
  enum_xp_zweckbestimmungwasserwirtschaft;
INSERT INTO
  enum_xp_zweckbestimmungwasserwirtschaft (wert,beschreibung)
VALUES
  ('1000', 'HochwasserRueckhaltebecken', 'Hochwasser-Rückhaltebecken'),
  ('1100', 'Ueberschwemmgebiet', 'Überschwemmungs-gefährdetes Gebiet'),
  ('1200', 'Versickerungsflaeche', 'Versickerungsfläche'),
  ('1300', 'Entwaesserungsgraben', 'Entwässerungsgraben'),
  ('1400', 'Deich', 'Deich'),
  ('9999', 'Sonstiges', 'Sonstige Wasserwirtschaftsfläche, sofern keiner der anderen Codes zutreffend ist.');

TRUNCATE
  enum_xp_grenzetypen;
INSERT INTO
  enum_xp_grenzetypen (wert,beschreibung)
VALUES
  ('1000', 'Bundesgrenze', 'Bundesgrenze'),
  ('1100', 'Landesgrenze', 'Grenze eines Bundeslandes'),
  ('1200', 'Regierungsbezirksgrenze', 'Grenze eines Regierungsbezirks'),
  ('1250', 'Bezirksgrenze', 'Grenze eines Bezirks.'),
  ('1300', 'Kreisgrenze', 'Grenze eines Kreises.'),
  ('1400', 'Gemeindegrenze', 'Grenze einer Gemeinde.'),
  ('1450', 'Verbandsgemeindegrenze', 'Grenze einer Verbandsgemeinde'),
  ('1500', 'Samtgemeindegrenze', 'Grenze einer Samtgemeinde'),
  ('1510', 'Mitgliedsgemeindegrenze', 'Mitgliedsgemeindegrenze'),
  ('1550', 'Amtsgrenze', 'Amtsgrenze'),
  ('1600', 'Stadtteilgrenze', 'Stadtteilgrenze'),
  ('2000', 'VorgeschlageneGrundstuecksgrenze', 'Hinweis auf eine vorgeschlagene Grundstücksgrenze im BPlan.'),
  ('2100', 'GrenzeBestehenderBebauungsplan', 'Hinweis auf den Geltungsbereich eines bestehenden BPlan.'),
  ('9999', 'SonstGrenze', 'Sonstige Grenze');

TRUNCATE
  enum_xp_nutzungsform;
INSERT INTO
  enum_xp_nutzungsform (wert,beschreibung)
VALUES
  ('1000', 'Privat', 'Private Nutzung'),
  ('2000', 'Oeffentlich', 'Öffentliche Nutzung');

TRUNCATE
  enum_xp_zweckbestimmungkennzeichnung;
INSERT INTO
  enum_xp_zweckbestimmungkennzeichnung (wert,beschreibung)
VALUES
  ('1000', 'Naturgewalten', 'Flächen, bei deren Bebauung besondere bauliche Sicherungsmaßnahmen gegen Naturgewalten erforderlich sind (§5, Abs. 3, Nr. 1 BauGB).'),
  ('2000', 'Abbauflaeche', 'Flächen, unter denen der Bergbau umgeht oder die für den Abbau von Mineralien bestimmt sind (§5, Abs. 3, Nr. 2 BauGB).'),
  ('3000', 'AeussereEinwirkungen', 'Flächen, bei deren Bebauung besondere bauliche Sicherungsmaßnahmen gegen äußere Einwirkungen erforderlich sind (§5, Abs. 3, Nr. 1 BauGB).'),
  ('4000', 'SchadstoffBelastBoden', 'Für bauliche Nutzung vorgesehene Flächen, deren Böden erheblich mit umweltgefährdenden Stoffen belastet sind (§5, Abs. 3, Nr. 3 BauGB).'),
  ('5000', 'LaermBelastung', 'Für bauliche Nutzung vorgesehene Flächen, die erheblicher Lärmbelastung ausgesetzt sind.'),
  ('6000', 'Bergbau', 'Flächen für den Bergbau'),
  ('7000', 'Bodenordnung', 'Bodenordnung'),
  ('8000', 'Vorhabensgebiet', 'Räumlich besonders gekennzeichnetes Vorhabengebiets, das kleiner als der Geltungsbereich ist, innerhalb eines vorhabenbezogenen BPlans.'),
  ('9999', 'AndereGesetzlVorschriften', 'Kennzeichnung nach anderen gesetzlichen Vorschriften.');

TRUNCATE
  enum_xp_zweckbestimmunglandwirtschaft;
INSERT INTO
  enum_xp_zweckbestimmunglandwirtschaft (wert,beschreibung)
VALUES
  ('1000', 'LandwirtschaftAllgemein', 'Allgemeine Landwirtschaft'),
  ('1100', 'Ackerbau', 'Ackerbau'),
  ('1200', 'WiesenWeidewirtschaft', 'Wiesen- und Weidewirtschaft'),
  ('1300', 'GartenbaulicheErzeugung', 'Gartenbauliche Erzeugung'),
  ('1400', 'Obstbau', 'Obstbau'),
  ('1500', 'Weinbau', 'Weinbau'),
  ('1600', 'Imkerei', 'Imkerei'),
  ('1700', 'Binnenfischerei', 'Binnenfischerei'),
  ('9999', 'Sonstiges', 'Sonstiges');

TRUNCATE
  enum_xp_zweckbestimmunggemeinbedarf;
INSERT INTO
  enum_xp_zweckbestimmunggemeinbedarf (wert,beschreibung)
VALUES
  ('1000', 'OeffentlicheVerwaltung', 'Einrichtungen und Anlagen für öffentliche Verwaltung'),
  ('10000', 'KommunaleEinrichtung', 'Kommunale Einrichtung wie z. B. Rathaus, Gesundheitsamt, Gesundheitsfürsorgestelle, Gartenbauamt, Gartenarbeitsstützpunkt, Fuhrpark.'),
  ('10001', 'BetriebOeffentlZweckbestimmung', 'Betrieb mit öffentlicher Zweckbestimmung wie z.B. ein Stadtreinigungsbetrieb, Autobusbetriebshof, Omnibusbahnhof.'),
  ('10002', 'AnlageBundLand', 'Eine Anlage des Bundes oder eines Bundeslandes wie z. B.  Arbeitsamt, Autobahnmeisterei, Brückenmeisterei, Patentamt, Wasserbauhof, Finanzamt.'),
  ('10003', 'SonstigeOeffentlicheVerwaltung', 'Sonstige Einrichtung oder Anlage der öffentlichen Verwaltung wie z. B. die Industrie und Handelskammer oder Handwerkskammer.'),
  ('1200', 'BildungForschung', 'Einrichtungen und Anlagen für Bildung und Forschung'),
  ('12000', 'Schule', 'Schulische Einrichtung. Darunter fallen u. a. Allgemeinbildende Schule, Oberstufenzentrum, Sonderschule, Fachschule, Volkshochschule,
Konservatorium.'),
  ('12001', 'Hochschule', 'Hochschule, Fachhochschule, Berufsakademie, o. Ä.'),
  ('12002', 'BerufsbildendeSchule', 'Berufsbildende Schule'),
  ('12003', 'Forschungseinrichtung', 'Forschungseinrichtung, Forschungsinstitut.'),
  ('12004', 'SonstigesBildungForschung', 'Sonstige Anlage oder Einrichtung aus Bildung und Forschung.'),
  ('1400', 'Kirche', 'Kirchliche Einrichtungen.'),
  ('14000', 'Sakralgebaeude', 'Religiösen Zwecken dienendes Gebäude wie z. B. Kirche, 
 Kapelle, Moschee, Synagoge, Gebetssaal.'),
  ('14001', 'KirchlicheVerwaltung', 'Kirchliches Verwaltungsgebäude, z. B. Pfarramt, Bischöfliches Ordinariat, Konsistorium.'),
  ('14002', 'Kirchengemeinde', 'Religiöse Gemeinde- oder Versammlungseinrichtung, z. B. Gemeindehaus, Gemeindezentrum.'),
  ('14003', 'SonstigesKirche', 'Sonstige religiösen Zwecken dienende Anlage oder Einrichtung.'),
  ('1600', 'Sozial', 'Einrichtungen und Anlagen für soziale Zwecke.'),
  ('16000', 'EinrichtungKinder', 'Soziale Einrichtung für Kinder, wie z. B. Kinderheim, Kindertagesstätte, Kindergarten.'),
  ('16001', 'EinrichtungJugendliche', 'Soziale Einrichtung für Jugendliche, wie z. B. Jugendfreizeitheim/-stätte, Jugendgästehaus, Jugendherberge, Jugendheim.'),
  ('16002', 'EinrichtungFamilienErwachsene', 'Soziale Einrichtung für Familien und Erwachsene, wie z. B. Bildungszentrum, Volkshochschule, Kleinkinderfürsorgestelle, Säuglingsfürsorgestelle, Nachbarschaftsheim.'),
  ('16003', 'EinrichtungSenioren', 'Soziale Einrichtung für Senioren, wie z. B. Alten-/Seniorentagesstätte, Alten-/Seniorenheim, Alten-/Seniorenwohnheim, Altersheim.'),
  ('16004', 'SonstigeSozialeEinrichtung', 'Sonstige soziale Einrichtung, z. B. Pflegeheim, Schwesternwohnheim, Studentendorf, Studentenwohnheim. Tierheim, Übergangsheim.'),
  ('1800', 'Gesundheit', 'Einrichtungen und Anlagen für gesundheitliche Zwecke.'),
  ('18000', 'Krankenhaus', 'Krankenhaus oder vergleichbare Einrichtung (z. B. Klinik, Hospital, Krankenheim, Heil- und Pflegeanstalt),'),
  ('18001', 'SonstigesGesundheit', 'Sonstige Gesundheits-Einrichtung, z. B. Sanatorium, Kurklinik, Desinfektionsanstalt.'),
  ('2000', 'Kultur', 'Einrichtungen und Anlagen für kulturelle Zwecke.'),
  ('20000', 'MusikTheater', 'Kulturelle Einrichtung aus dem Bereich Musik oder Theater (z. B. Theater, Konzerthaus, Musikhalle, Oper).'),
  ('20001', 'Bildung', 'Kulturelle Einrichtung mit Bildungsfunktion ( z. B. Museum, Bibliothek, Bücherei, Stadtbücherei, Volksbücherei).'),
  ('20002', 'SonstigeKultur', 'Sonstige kulturelle Einrichtung, wie z. B. Archiv, Landesbildstelle, Rundfunk und Fernsehen, Kongress- und Veranstaltungshalle, Mehrzweckhalle..'),
  ('2200', 'Sport', 'Einrichtungen und Anlagen für sportliche Zwecke.'),
  ('22000', 'Bad', 'Schwimmbad, Freibad, Hallenbad, Schwimmhalle o. Ä..'),
  ('22001', 'SportplatzSporthalle', 'Sportplatz, Sporthalle, Tennishalle o. Ä.'),
  ('22002', 'SonstigerSport', 'Sonstige Sporteinrichtung.'),
  ('2400', 'SicherheitOrdnung', 'Einrichtungen und Anlagen für Sicherheit und Ordnung.'),
  ('24000', 'Feuerwehr', 'Einrichtung oder Anlage der Feuerwehr.'),
  ('24001', 'Schutzbauwerk', 'Schutzbauwerk'),
  ('24002', 'Justiz', 'Einrichtung der Justiz, wie z. B. Justizvollzug, Gericht, Haftanstalt.'),
  ('24003', 'SonstigeSicherheitOrdnung', 'Sonstige Anlage oder Einrichtung für Sicherheit und Ordnung, z. B. Polizei, Zoll, Feuerwehr, Zivilschutz, Bundeswehr, Landesverteidigung.'),
  ('2600', 'Infrastruktur', 'Einrichtungen und Anlagen der Infrastruktur.'),
  ('26000', 'Post', 'Einrichtung der Post.'),
  ('26001', 'SonstigeInfrastruktur', 'Sonstige Anlage oder Einrichtung der Infrastruktur.'),
  ('9999', 'Sonstiges', 'Sonstige Einrichtungen und Anlagen, die keiner anderen Kategorie zuzuordnen sind.');

TRUNCATE
  enum_xp_anpflanzungbindungerhaltungsgegenstand;
INSERT INTO
  enum_xp_anpflanzungbindungerhaltungsgegenstand (wert,beschreibung)
VALUES
  ('1000', 'Baeume', 'Bäume'),
  ('1100', 'Kopfbaeume', 'Kopfbäume'),
  ('1200', 'Baumreihe', 'Baumreihe'),
  ('2000', 'Straeucher', 'Sträucher'),
  ('2050', 'BaeumeUndStraeucher', 'Bäume und Sträucher'),
  ('2100', 'Hecke', 'Hecke'),
  ('2200', 'Knick', 'Knick'),
  ('3000', 'SonstBepflanzung', 'Sonstige Bepflanzung'),
  ('4000', 'Gewaesser', 'Gewässer (nur Erhaltung)'),
  ('5000', 'Fassadenbegruenung', 'Fassadenbegrünung'),
  ('6000', 'Dachbegruenung', 'Dachbegrünung');

TRUNCATE
  enum_xp_zweckbestimmungverentsorgung;
INSERT INTO
  enum_xp_zweckbestimmungverentsorgung (wert,beschreibung)
VALUES
  ('1000', 'Elektrizitaet', 'Elektrizität allgemein'),
  ('10000', 'Hochspannungsleitung', 'Hochspannungsleitung'),
  ('10001', 'TrafostationUmspannwerk', 'Trafostation, auch Umspannwerk'),
  ('100010', 'Leitungsmast', 'Leitungsmast'),
  ('10002', 'Solarkraftwerk', 'Solarkraftwerk'),
  ('10003', 'Windkraftwerk', 'Windkraftwerk, Windenergieanlage, Windrad.'),
  ('10004', 'Geothermiekraftwerk', 'Geothermie Kraftwerk'),
  ('10005', 'Elektrizitaetswerk', 'Elektrizitätswerk allgemein'),
  ('10006', 'Wasserkraftwerk', 'Wasserkraftwerk'),
  ('10007', 'BiomasseKraftwerk', 'Biomasse-Kraftwerk'),
  ('10008', 'Kabelleitung', 'Kabelleitung'),
  ('10009', 'Niederspannungsleitung', 'Niederspannungsleitung'),
  ('1200', 'Gas', 'Gas allgemein'),
  ('12000', 'Ferngasleitung', 'Ferngasleitung'),
  ('12001', 'Gaswerk', 'Gaswerk'),
  ('12002', 'Gasbehaelter', 'Gasbehälter'),
  ('12003', 'Gasdruckregler', 'Gasdruckregler'),
  ('12004', 'Gasstation', 'Gasstation'),
  ('12005', 'Gasleitung', 'Gasleitung'),
  ('1300', 'Erdoel', 'Erdöl allgemein'),
  ('13000', 'Erdoelleitung', 'Erdölleitung'),
  ('13001', 'Bohrstelle', 'Bohrstelle'),
  ('13002', 'Erdoelpumpstation', 'Erdölpumpstation'),
  ('13003', 'Oeltank', 'Öltank'),
  ('1400', 'Waermeversorgung', 'Wärmeversorgung allgemein'),
  ('14000', 'Blockheizkraftwerk', 'Blockheizkraftwerk'),
  ('14001', 'Fernwaermeleitung', 'Fernwärmeleitung'),
  ('14002', 'Fernheizwerk', 'Fernheizwerk'),
  ('1600', 'Wasser', 'Trink- und Brauchwasser allgemein'),
  ('16000', 'Wasserwerk', 'Wasserwerk'),
  ('16001', 'Wasserleitung', 'Trinkwasserleitung'),
  ('16002', 'Wasserspeicher', 'Wasserspeicher'),
  ('16003', 'Brunnen', 'Brunnen'),
  ('16004', 'Pumpwerk', 'Pumpwerk'),
  ('16005', 'Quelle', 'Quelle'),
  ('1800', 'Abwasser', 'Abwasser allgemein'),
  ('18000', 'Abwasserleitung', 'Abwasserleitung'),
  ('18001', 'Abwasserrueckhaltebecken', 'Abwasserrückhaltebecken'),
  ('18002', 'Abwasserpumpwerk', 'Abwasserpumpwerk, auch Abwasserhebeanlage'),
  ('18003', 'Klaeranlage', 'Kläranlage'),
  ('18004', 'AnlageKlaerschlamm', 'Anlage zur Speicherung oder Behandlung von Klärschlamm.'),
  ('18005', 'SonstigeAbwasserBehandlungsanlage', 'Sonstige Abwasser-Behandlungsanlage.'),
  ('18006', 'Salz oder Soleleitungen', 'Salz- oder Sole-Leitungen'),
  ('2000', 'Regenwasser', 'Regenwasser allgemein'),
  ('20000', 'RegenwasserRueckhaltebecken', 'Regenwasser Rückhaltebecken'),
  ('20001', 'Niederschlagswasserleitung', 'Niederschlagswasser-Leitung'),
  ('2200', 'Abfallentsorgung', 'Abfallentsorgung allgemein'),
  ('22000', 'Muellumladestation', 'Müll-Umladestation'),
  ('22001', 'Muellbeseitigungsanlage', 'Müllbeseitigungsanlage'),
  ('22002', 'Muellsortieranlage', 'Müllsortieranlage'),
  ('22003', 'Recyclinghof', 'Recyclinghof'),
  ('2400', 'Ablagerung', 'Ablagerung allgemein'),
  ('24000', 'Erdaushubdeponie', 'Erdaushub-Deponie'),
  ('24001', 'Bauschuttdeponie', 'Bauschutt-Deponie'),
  ('24002', 'Hausmuelldeponie', 'Hausmüll-Deponie'),
  ('24003', 'Sondermuelldeponie', 'Sondermüll-Deponie'),
  ('24004', 'StillgelegteDeponie', 'Stillgelegte Deponie'),
  ('24005', 'RekultivierteDeponie', 'Rekultivierte Deponie'),
  ('2600', 'Telekommunikation', 'Telekommunikation allgemein'),
  ('26000', 'Fernmeldeanlage', 'Fernmeldeanlage'),
  ('26001', 'Mobilfunkstrecke', 'Mobilfunkstrecke'),
  ('26002', 'Fernmeldekabel', 'Fernmeldekabel'),
  ('2800', 'ErneuerbareEnergien', 'Erneuerbare Energien allgemein'),
  ('3000', 'KraftWaermeKopplung', 'Fläche oder Anlage für Kraft-Wärme Kopplung'),
  ('9999', 'Sonstiges', 'Sonstige, durch keinen anderen Code abbildbare Ver- oder Entsorgungsfläche bzw. -Anlage.'),
  ('99990', 'Produktenleitung', 'Produktenleitung');

TRUNCATE
  enum_xp_verlaengerungveraenderungssperre;
INSERT INTO
  enum_xp_verlaengerungveraenderungssperre (wert,beschreibung)
VALUES
  ('1000', 'Keine', 'Veränderungssperre wurde noch nicht verlängert.'),
  ('2000', 'ErsteVerlaengerung', 'Veränderungssperre wurde einmal verlängert.'),
  ('3000', 'ZweiteVerlaengerung', 'Veränderungssperre wurde zweimal verlängert.');

TRUNCATE
  enum_xp_bundeslaender;
INSERT INTO
  enum_xp_bundeslaender (wert,beschreibung)
VALUES
  ('1000', 'BB', 'Brandenburg'),
  ('1100', 'BE', 'Berlin'),
  ('1200', 'BW', 'Baden-Württemberg'),
  ('1300', 'BY', 'Bayern'),
  ('1400', 'HB', 'Bremen'),
  ('1500', 'HE', 'Hessen'),
  ('1600', 'HH', 'Hamburg'),
  ('1700', 'MV', 'Mecklenburg-Vorpommern'),
  ('1800', 'NI', 'Niedersachsen'),
  ('1900', 'NW', 'Nordrhein-Westfalen'),
  ('2000', 'RP', 'Rheinland-Pfalz'),
  ('2100', 'SH', 'Schleswig-Holstein'),
  ('2200', 'SL', 'Saarland'),
  ('2300', 'SN', 'Sachsen'),
  ('2400', 'ST', 'Sachsen-Anhalt'),
  ('2500', 'TH', 'Thüringen'),
  ('3000', 'Bund', 'Der Bund.');

TRUNCATE
  enum_xp_zweckbestimmungwald;
INSERT INTO
  enum_xp_zweckbestimmungwald (wert,beschreibung)
VALUES
  ('1000', 'Naturwald', 'Naturwald'),
  ('1200', 'Nutzwald', 'Nutzwald'),
  ('1400', 'Erholungswald', 'Erholungswald'),
  ('1600', 'Schutzwald', 'Schutzwald'),
  ('1800', 'FlaecheForstwirtschaft', 'Fläche für die Forstwirtschaft.'),
  ('9999', 'Sonstiges', 'Sonstige Zweckbestimmung');

TRUNCATE
  enum_xp_abemassnahmentypen;
INSERT INTO
  enum_xp_abemassnahmentypen (wert,beschreibung)
VALUES
  ('1000', 'BindungErhaltung', 'Bindung und Erhaltung von Bäumen, Sträuchern und sonstigen Bepflanzungen, sowie von Gewässern.'),
  ('2000', 'Anpflanzung', 'Anpflanzung von Bäumen, Sträuchern oder sonstigen Bepflanzungen.'),
  ('3000', 'AnpflanzungBindungErhaltung', 'Bindung und Erhaltung von Bäumen, Sträuchern und sonstigen Bepflanzungen.');

TRUNCATE
  enum_xp_sondernutzungen;
INSERT INTO
  enum_xp_sondernutzungen (wert,beschreibung)
VALUES
  ('1000', 'Wochenendhausgebiet', 'Wochenendhausgebiet'),
  ('1100', 'Ferienhausgebiet', 'Ferienhausgebiet'),
  ('1200', 'Campingplatzgebiet', 'Campingplatzgebiet'),
  ('1300', 'Kurgebiet', 'Kurgebiet'),
  ('1400', 'SonstSondergebietErholung', 'Sonstiges Sondergebiet für Erholung'),
  ('1500', 'Einzelhandelsgebiet', 'Einzelhandelsgebiet'),
  ('1600', 'GrossflaechigerEinzelhandel', 'Gebiet für großflächigen Einzelhandel'),
  ('16000', 'Ladengebiet', 'Ladengebiet'),
  ('16001', 'Einkaufszentrum', 'Einkaufszentrum'),
  ('16002', 'SonstGrossflEinzelhandel', 'Sonstiges Gebiet für großflächigen Einzelhandel'),
  ('1700', 'Verkehrsuebungsplatz', 'Verkehrsübungsplatz'),
  ('1800', 'Hafengebiet', 'Hafengebiet'),
  ('1900', 'SondergebietErneuerbareEnergie', 'Sondergebiet für Erneuerbare Energien'),
  ('2000', 'SondergebietMilitaer', 'Militärisches Sondergebiet'),
  ('2100', 'SondergebietLandwirtschaft', 'Sondergebiet Landwirtschaft'),
  ('2200', 'SondergebietSport', 'Sondergebiet Sport'),
  ('2300', 'SondergebietGesundheitSoziales', 'Sondergebiet für Gesundheit und Soziales'),
  ('2400', 'Golfplatz', 'Golfplatz'),
  ('2500', 'SondergebietKultur', 'Sondergebiet für Kultur'),
  ('2600', 'SondergebietTourismus', 'Sondergebiet Tourismus'),
  ('2700', 'SondergebietBueroUndVerwaltung', 'Sondergebiet für Büros und Verwaltung'),
  ('2800', 'SondergebietHochschuleEinrichtungen', 'Sondergebiet Hochschule'),
  ('2900', 'SondergebietMesse', 'Sondergebiet für Messe'),
  ('9999', 'SondergebietAndereNutzungen', 'Sonstiges Sondergebiet');

TRUNCATE
  enum_xp_speziele;
INSERT INTO
  enum_xp_speziele (wert,beschreibung)
VALUES
  ('1000', 'SchutzPflege', 'Schutz und Pflege'),
  ('2000', 'Entwicklung', 'Entwicklung'),
  ('3000', 'Anlage', 'Neu-Anlage'),
  ('4000', 'SchutzPflegeEntwicklung', 'Schutz, Pflege und Entwicklung'),
  ('9999', 'Sonstiges', 'Sonstiges Ziel');

TRUNCATE
  enum_xp_klassifizschutzgebietnaturschutzrecht;
INSERT INTO
  enum_xp_klassifizschutzgebietnaturschutzrecht (wert,beschreibung)
VALUES
  ('1000', 'Naturschutzgebiet', 'Naturschutzgebiet gemäß §23 BNatSchG.'),
  ('1100', 'Nationalpark', 'Nationalpark gemäß §24 BNatSchG'),
  ('1200', 'Biosphaerenreservat', 'Biosphärenreservat gemäß §25 BNatSchG.'),
  ('1300', 'Landschaftsschutzgebiet', 'Landschaftsschutzgebiet gemäß §65 BNatSchG.'),
  ('1400', 'Naturpark', 'Naturpark gemäß §27 BNatSchG.'),
  ('1500', 'Naturdenkmal', 'Naturdenkmal gemäß §28 BNatSchG.'),
  ('1600', 'GeschuetzterLandschaftsBestandteil', 'Geschützter Bestandteil der Landschaft gemäß §29 BNatSchG.'),
  ('1700', 'GesetzlichGeschuetztesBiotop', 'Gesetzlich geschützte Biotope gemäß §30 BNatSchG.'),
  ('1800', 'Natura2000', 'Schutzgebiet nach Europäischem Recht. Dies umfasst das "Gebiet Gemeinschaftlicher Bedeutung" (FFH-Gebiet) und das "Europäische Vogelschutzgebiet"'),
  ('18000', 'GebietGemeinschaftlicherBedeutung', 'Gebiete von gemeinschaftlicher Bedeutung'),
  ('18001', 'EuropaeischesVogelschutzgebiet', 'Europäische Vogelschutzgebiete'),
  ('2000', 'NationalesNaturmonument', 'Nationales Naturmonument gemäß §24 Abs. (4)  BNatSchG.'),
  ('9999', 'Sonstiges', 'Sonstiges Naturschutzgebiet');

TRUNCATE
  enum_xp_zweckbestimmunggewaesser;
INSERT INTO
  enum_xp_zweckbestimmunggewaesser (wert,beschreibung)
VALUES
  ('1000', 'Hafen', 'Hafen'),
  ('1100', 'Wasserflaeche', 'Stehende Wasserfläche, auch See, Teich.'),
  ('1200', 'Fliessgewaesser', 'Fließgewässer, auch Fluss, Bach'),
  ('9999', 'Sonstiges', 'Sonstiges Gewässer, sofern keiner der anderen Codes zutreffend ist.');

TRUNCATE
  enum_xp_abweichungbaunvotypen;
INSERT INTO
  enum_xp_abweichungbaunvotypen (wert,beschreibung)
VALUES
  ('1000', 'EinschraenkungNutzung', 'Einschränkung einer generell erlaubten Nutzung.'),
  ('2000', 'AusschlussNutzung', 'Ausschluss einer generell erlaubten Nutzung.'),
  ('3000', 'AusweitungNutzung', 'Eine nur ausnahmsweise zulässige Nutzung wird generell zulässig.'),
  ('9999', 'SonstAbweichung', 'Sonstige Abweichung.');

TRUNCATE
  enum_xp_allgartderbaulnutzung;
INSERT INTO
  enum_xp_allgartderbaulnutzung (wert,beschreibung)
VALUES
  ('1000', 'WohnBauflaeche', 'Wohnbaufläche nach §1 Abs. (1) BauNVO'),
  ('2000', 'GemischteBauflaeche', 'Gemischte Baufläche nach §1 Abs. (1) BauNVO.'),
  ('3000', 'GewerblicheBauflaeche', 'Gewerbliche Baufläche nach §1 Abs. (1) BauNVO.'),
  ('4000', 'SonderBauflaeche', 'Sonderbaufläche nach §1 Abs. (1) BauNVO.'),
  ('9999', 'SonstigeBauflaeche', 'Sonstige Baufläche');

TRUNCATE
  enum_xp_zweckbestimmungspielsportanlage;
INSERT INTO
  enum_xp_zweckbestimmungspielsportanlage (wert,beschreibung)
VALUES
  ('1000', 'Sportanlage', 'Sportanlage'),
  ('2000', 'Spielanlage', 'Spielanlage'),
  ('3000', 'SpielSportanlage', 'Spiel- und/oder Sportanlage.'),
  ('9999', 'Sonstiges', 'Sonstiges');

TRUNCATE
  enum_xp_zweckbestimmunggruen;
INSERT INTO
  enum_xp_zweckbestimmunggruen (wert,beschreibung)
VALUES
  ('1000', 'Parkanlage', 'Parkanlage; auch: Erholungsgrün, Grünanlage, Naherholung.'),
  ('10000', 'ParkanlageHistorisch', 'Historische Parkanlage'),
  ('10001', 'ParkanlageNaturnah', 'Naturnahe Parkanlage'),
  ('10002', 'ParkanlageWaldcharakter', 'Parkanlage mit Waldcharakter'),
  ('10003', 'NaturnaheUferParkanlage', 'Ufernahe Parkanlage'),
  ('1200', 'Dauerkleingarten', 'Dauerkleingarten; auch: Gartenfläche, Hofgärten, Gartenland.'),
  ('12000', 'ErholungsGaerten', 'Erholungsgarten'),
  ('1400', 'Sportplatz', 'Sportplatz'),
  ('14000', 'Reitsportanlage', 'Reitsportanlage'),
  ('14001', 'Hundesportanlage', 'Hundesportanlage'),
  ('14002', 'Wassersportanlage', 'Wassersportanlage'),
  ('14003', 'Schiessstand', 'Schießstand'),
  ('14004', 'Golfplatz', 'Golfplatz'),
  ('14005', 'Skisport', 'Anlage für Skisport'),
  ('14006', 'Tennisanlage', 'Tennisanlage'),
  ('14007', 'SonstigerSportplatz', 'Sonstiger Sportplatz'),
  ('1600', 'Spielplatz', 'Spielplatz'),
  ('16000', 'Bolzplatz', 'Bolzplatz'),
  ('16001', 'Abenteuerspielplatz', 'Abenteuerspielplatz'),
  ('1800', 'Zeltplatz', 'Zeltplatz'),
  ('18000', 'Campingplatz', 'Campingplatz'),
  ('2000', 'Badeplatz', 'Badeplatz, auch Schwimmbad, Liegewiese.'),
  ('2200', 'FreizeitErholung', 'Anlage für Freizeit und Erholung.'),
  ('22000', 'Kleintierhaltung', 'Anlage für Kleintierhaltung'),
  ('22001', 'Festplatz', 'Festplatz'),
  ('2400', 'SpezGruenflaeche', 'Spezielle Grünfläche'),
  ('24000', 'StrassenbegleitGruen', 'Straßenbegleitgrün'),
  ('24001', 'BoeschungsFlaeche', 'Böschungsfläche'),
  ('24002', 'FeldWaldWiese', 'Feld, Wald, Wiese allgemein'),
  ('24003', 'Uferschutzstreifen', 'Uferstreifen'),
  ('24004', 'Abschirmgruen', 'Abschirmgrün'),
  ('24005', 'UmweltbildungsparkSchaugatter', 'Umweltbildungspark, Schaugatter'),
  ('24006', 'RuhenderVerkehr', 'Fläche für den ruhenden Verkehr.'),
  ('2600', 'Friedhof', 'Friedhof'),
  ('9999', 'Sonstiges', 'Sonstige Zweckbestimmung, falls keine der aufgeführten Klassifikationen anwendbar ist.'),
  ('99990', 'Gaertnerei', 'Gärtnerei');

TRUNCATE
  enum_xp_besondereartderbaulnutzung;
INSERT INTO
  enum_xp_besondereartderbaulnutzung (wert,beschreibung)
VALUES
  ('1000', 'Kleinsiedlungsgebiet', 'Kleinsiedlungsgebiet nach § 2 BauNVO.'),
  ('1100', 'ReinesWohngebiet', 'Reines Wohngebiet nach § 3 BauNVO.'),
  ('1200', 'AllgWohngebiet', 'Allgemeines Wohngebiet nach § 4 BauNVO.'),
  ('1300', 'BesonderesWohngebiet', 'Gebiet zur Erhaltung und Entwicklung der Wohnnutzung (Besonderes Wohngebiet) nach § 4a BauNVO.'),
  ('1400', 'Dorfgebiet', 'Dorfgebiet nach $ 5 BauNVO.'),
  ('1500', 'Mischgebiet', 'Mischgebiet nach $ 6 BauNVO.'),
  ('1550', 'UrbanesGebiet', 'Urbanes Gebiet nach § 6a BauNVO'),
  ('1600', 'Kerngebiet', 'Kerngebiet nach § 7 BauNVO.'),
  ('1700', 'Gewerbegebiet', 'Gewerbegebiet nach § 8 BauNVO.'),
  ('1800', 'Industriegebiet', 'Industriegebiet nach § 9 BauNVO.'),
  ('2000', 'SondergebietErholung', 'Sondergebiet, das der Erholung dient nach § 10 BauNVO von 1977 und 1990.'),
  ('2100', 'SondergebietSonst', 'Sonstiges Sondergebiet nach§ 11 BauNVO 1977 und 1990; z.B. Klinikgebiet'),
  ('3000', 'Wochenendhausgebiet', 'Wochenendhausgebiet nach §10 der BauNVO von 1962 und 1968'),
  ('4000', 'Sondergebiet', 'Sondergebiet nach §11der BauNVO von 1962 und 1968'),
  ('9999', 'SonstigesGebiet', 'Sonstiges Gebiet');

TRUNCATE
  enum_rp_bedeutsamkeit;
INSERT INTO
  enum_rp_bedeutsamkeit (wert,beschreibung)
VALUES
  ('1000', 'Regional', 'Regional Bedeutsam.'),
  ('2000', 'Ueberregional', 'Überregional Bedeutsam.'),
  ('3000', 'Grossraeumig', 'Großräumig Bedeutsam.'),
  ('4000', 'Landesweit', 'Landesweit Bedeutsam.'),
  ('5000', 'Bundesweit', 'Bundesweit Bedeutsam.'),
  ('6000', 'Europaeisch', 'Europäisch Bedeutsam.'),
  ('7000', 'International', 'International Bedeutsam.'),
  ('8000', 'Flaechenerschliessend', 'Flächenerschließend Bedeutsam.'),
  ('9000', 'Herausragend', 'Herausragend Bedeutsam.');

TRUNCATE
  enum_rp_art;
INSERT INTO
  enum_rp_art (wert,beschreibung)
VALUES
  ('1000', 'Regionalplan', 'Regionalplan.'),
  ('2000', 'SachlicherTeilplanRegionalebene', 'Sachlicher Teilplan auf der räumlichen Ebene einer Region'),
  ('2001', 'SachlicherTeilplanLandesebene', 'Sachlicher Teilplan auf räumlicher Ebene eines Bundeslandes'),
  ('3000', 'Braunkohlenplan', 'Braunkohlenplan.'),
  ('4000', 'LandesweiterRaumordnungsplan', 'Landesweiter Raumordnungsplan auf räumlicher Ebene eines Bundeslandes'),
  ('5000', 'StandortkonzeptBund', 'Raumordnungsplan für das Bundesgebiet mit übergreifenden Standortkonzepten für Seehäfen, Binnenhäfen sowie Flughäfen gem. §17 Abs. 2 ROG.'),
  ('5001', 'AWZPlan', 'Plan des Bundes für den Gesamtraum und die ausschließliche Wirtschaftszone (AWZ).'),
  ('6000', 'RaeumlicherTeilplan', 'Räumlicher Teilplan auf regionaler Ebene'),
  ('9999', 'Sonstiges', 'Sonstiges Planwerk der Raumordnung auf Bundesebene, Landesebene oder regionaler Ebene.');

TRUNCATE
  enum_rp_gebietstyp;
INSERT INTO
  enum_rp_gebietstyp (wert,beschreibung)
VALUES
  ('1000', 'Vorranggebiet', 'Vorranggebiete sind für bestimmte raumbedeutsame Funktionen oder Nutzungen vorgesehen. In ihnen sind andere raumbedeutsame Nutzungen ausgeschlossen, soweit diese mit den vorrangigen Funktionen, Nutzungen oder Zielen der Raumordnung nicht vereinbar sind.'),
  ('1001', 'Vorrangstandort', 'Vorrangstandort.'),
  ('1100', 'Vorbehaltsgebiet', 'Vorbehaltsgebiete sind Gebiete, in denen bestimmten raumbedeutsamen Funktionen oder Nutzungen bei der Abwägung mit konkurrierenden raumbedeutsamen Nutzungen besonderes Gewicht begemessen werden soll. Vorbehaltsgebiete besitzen den Charakter von Grundsätzen der Raumordnung.'),
  ('1101', 'Vorbehaltsstandort', 'Vorbehaltsstandort.'),
  ('1200', 'Eignungsgebiet', 'Eignungsgebiete steuern raumbedeutsame Maßnahmen im bauplanungsrechtlichen Außenbereich. Diese Maßnahmen sind außerhalb dieser Gebiete regelmäßig ausgeschlossen, z.B. die Planung und Einrichtung von Windkraftanlagen. 
Eignungsgebiete haben den Charakter von Zielen der Raumordnung.'),
  ('1300', 'VorrangundEignungsgebiet', 'Vorrang und Eignungsgebiet.'),
  ('1400', 'Ausschlussgebiet', 'Ausschlussgebiet.'),
  ('1500', 'Vorsorgegebiet', 'Vorsorgegebiet.'),
  ('1501', 'Vorsorgestandort', 'Vorsorgestandort.'),
  ('1600', 'Vorzugsraum', 'Vorzugsraum.'),
  ('1700', 'Potenzialgebiet', 'Potenzialgebiet.'),
  ('1800', 'Schwerpunktraum', 'Schwerpunktraum.'),
  ('9999', 'SonstigesGebiet', 'Sonstiges Gebiet.');

TRUNCATE
  enum_rp_rechtsstand;
INSERT INTO
  enum_rp_rechtsstand (wert,beschreibung)
VALUES
  ('1000', 'Aufstellungsbeschluss', 'Aufstellungsbeschluss.'),
  ('2000', 'Entwurf', 'Entwurf.'),
  ('2001', 'EntwurfGenehmigt', 'Entwurf genehmigt.'),
  ('2002', 'EntwurfGeaendert', 'Entwurf geändert.'),
  ('2003', 'EntwurfAufgegeben', 'Entwurf aufgegeben.'),
  ('2004', 'EntwurfRuht', 'Entwurf ruht.'),
  ('3000', 'Plan', 'Plan.'),
  ('4000', 'Inkraftgetreten', 'Inkraftgetreten.'),
  ('5000', 'AllgemeinePlanungsabsicht', 'Allgemeine Planungsabsicht.'),
  ('6000', 'AusserKraft', 'Außer Kraft.'),
  ('7000', 'PlanUngueltig', 'Plan ungültig.');

TRUNCATE
  enum_rp_verfahren;
INSERT INTO
  enum_rp_verfahren (wert,beschreibung)
VALUES
  ('1000', 'Aenderung', 'Änderung.'),
  ('2000', 'Teilfortschreibung', 'Teilfortschreibung.'),
  ('3000', 'Neuaufstellung', 'Neuaufstellung.'),
  ('4000', 'Gesamtfortschreibung', 'Gesamtfortschreibung.'),
  ('5000', 'Aktualisierung', 'Aktualisierung.');

TRUNCATE
  enum_rp_rechtscharakter;
INSERT INTO
  enum_rp_rechtscharakter (wert,beschreibung)
VALUES
  ('1000', 'ZielDerRaumordnung', 'Ziel der Raumordnung. Verbindliche räumliche und sachliche Festlegung zur Entwicklung, Ordnung und Sicherung des Raumes.'),
  ('2000', 'GrundsatzDerRaumordnung', 'Grundsätze der Raumordnung sind nach §3 Abs. Aussagen zur Entwicklung, Ordnung und Sicherung des Raums als Vorgaben für nachfolgende Abwägungs- oder Ermessensentscheidungen. Grundsätze der Raumordnung können durch Gesetz oder Festlegungen in einem Raumordnungsplan (§7 Abs. 1 und 2, ROG) aufgestellt werden.'),
  ('3000', 'NachrichtlicheUebernahme', 'Nachrichtliche Übernahme.'),
  ('4000', 'NachrichtlicheUebernahmeZiel', 'Nachrichtliche Übernahme Ziel.'),
  ('5000', 'NachrichtlicheUebernahmeGrundsatz', 'Nachrichtliche Übernahme Grundsatz.'),
  ('6000', 'NurInformationsgehalt', 'Nur Informationsgehalt.'),
  ('7000', 'TextlichesZiel', 'Textliches Ziel.'),
  ('8000', 'ZielundGrundsatz', 'Ziel und Grundsatz.'),
  ('9000', 'Vorschlag', 'Vorschlag.'),
  ('9998', 'Unbekannt', 'Unbekannter Rechtscharakter');

TRUNCATE
  enum_rp_bergbaufolgenutzung;
INSERT INTO
  enum_rp_bergbaufolgenutzung (wert,beschreibung)
VALUES
  ('1000', 'Landwirtschaft', 'Folgenutzung Landwirtschaft.'),
  ('2000', 'Forstwirtschaft', 'Folgenutzung Forstwirtschaft.'),
  ('3000', 'Gruenlandbewirtschaftung', 'Folgenutzung Grünlandbewirtschaftung.'),
  ('4000', 'NaturLandschaft', 'Folgenutzung NaturLandschaft.'),
  ('5000', 'Naturschutz', 'Folgenutzung Naturschutz.'),
  ('6000', 'Erholung', 'Folgenutzung Erholung.'),
  ('7000', 'Gewaesser', 'Folgenutzung Gewässer.'),
  ('8000', 'Verkehr', 'Folgenutzung Verkehr.'),
  ('9000', 'Altbergbau', 'Folgenutzung Altbergbau.'),
  ('9999', 'SonstigeNutzung', 'Sonstige Folgenutzung.');

TRUNCATE
  enum_rp_sportanlagetypen;
INSERT INTO
  enum_rp_sportanlagetypen (wert,beschreibung)
VALUES
  ('1000', 'Sportanlage', 'Sportanlage.'),
  ('2000', 'Wassersport', 'Wassersport.'),
  ('3000', 'Motorsport', 'Motorsport.'),
  ('4000', 'Flugsport', 'Flugsport.'),
  ('5000', 'Reitsport', 'Reitsport.'),
  ('6000', 'Golfsport', 'Golfsport.'),
  ('7000', 'Sportzentrum', 'Sportzentrum.'),
  ('9999', 'SonstigeSportanlage', 'Sonstige Sportanlage.');

TRUNCATE
  enum_rp_erneuerbareenergietypen;
INSERT INTO
  enum_rp_erneuerbareenergietypen (wert,beschreibung)
VALUES
  ('1000', 'Windenergie', 'Windenergie.'),
  ('2000', 'Solarenergie', 'Solarenergie.'),
  ('3000', 'Geothermie', 'Geothermie.'),
  ('4000', 'Biomasse', 'Biomasse.'),
  ('9999', 'SonstigeErneuerbareEnergie', 'Sonstige Erneuerbare Energie.');

TRUNCATE
  enum_rp_erholungtypen;
INSERT INTO
  enum_rp_erholungtypen (wert,beschreibung)
VALUES
  ('1000', 'Erholung', 'Erholung.'),
  ('2000', 'RuhigeErholungInNaturUndLandschaft', 'Ruhige Erholung in Natur und Landschaft.'),
  ('3000', 'ErholungMitStarkerInanspruchnahmeDurchBevoelkerung', 'Erholung mit starker Inanspruchnahme durch die Bevölkerung.'),
  ('4000', 'Erholungswald', 'Erholungswald sind Waldgebiete, oft im Umfeld von Ballungszentren, die hauptsächlich der Erholung der Bevölkerung dienen (gegenüber forstwirtschaftlicher Nutzung oder Naturschutz). Nach § 13 Bundeswaldgesetz (1) kann Wald "zu Erholungswald erklärt werden, wenn es das Wohl der Allgemeinheit erfordert, Waldflächen für Zwecke der Erholung zu schützen, zu pflegen oder zu gestalten".'),
  ('5000', 'Freizeitanlage', 'Freizeitanlage.'),
  ('5001', 'Ferieneinrichtung', ''),
  ('6000', 'ErholungslandschaftAlpen', 'Erholungslandschaft in den Alpen.'),
  ('7000', 'Kureinrichtung', 'Kureinrichtung.'),
  ('9999', 'SonstigeErholung', 'Sonstige Erholung.');

TRUNCATE
  enum_rp_rohstofftypen;
INSERT INTO
  enum_rp_rohstofftypen (wert,beschreibung)
VALUES
  ('1000', 'Anhydritstein', 'Anhydritstein.'),
  ('1100', 'Baryt', 'Baryt.'),
  ('1200', 'BasaltDiabas', 'BasaltDiabas.'),
  ('1300', 'Bentonit', 'Bentonit.'),
  ('1400', 'Blaehton', 'Blaehton.'),
  ('1500', 'Braunkohle', 'Braunkohle.'),
  ('1600', 'Buntsandstein', 'Buntsandstein.'),
  ('1700', 'Dekostein', ''),
  ('1800', 'Diorit', 'Diorit.'),
  ('1900', 'Dolomitstein', 'Dolomitstein.'),
  ('2000', 'Erdgas', 'Erdgas.'),
  ('2100', 'Erdoel', 'Erdöl.'),
  ('2200', 'Erz', 'Erz.'),
  ('2300', 'Feldspat', 'Feldspat.'),
  ('2400', 'Festgestein', 'Festgestein.'),
  ('2500', 'Flussspat', 'Flussspat.'),
  ('2600', 'Gangquarz', 'Gangquarz.'),
  ('2700', 'Gipsstein', 'Gipsstein.'),
  ('2800', 'Gneis', 'Gneis.'),
  ('2900', 'Granit', 'Granit.'),
  ('3000', 'Grauwacke', 'Grauwacke.'),
  ('3100', 'Hartgestein', ''),
  ('3200', 'KalkKalktuffKreide', 'KalkKalktuffKreide.'),
  ('3300', 'Kalkmergelstein', 'Kalkmergelstein.'),
  ('3400', 'Kalkstein', 'Kalkstein.'),
  ('3500', 'Kaolin', 'Kaolin.'),
  ('3600', 'Karbonatgestein', 'Karbonatgestein.'),
  ('3700', 'Kies', 'Kies.'),
  ('3800', 'Kieselgur', 'Kieselgur.'),
  ('3900', 'KieshaltigerSand', 'KieshaltigerSand.'),
  ('4000', 'KiesSand', 'KiesSand.'),
  ('4100', 'Klei', 'Klei.'),
  ('4200', 'Kristallin', 'Kristallin.'),
  ('4300', 'Kupfer', 'Kupfer.'),
  ('4400', 'Lehm', 'Lehm.'),
  ('4500', 'Marmor', 'Marmor.'),
  ('4600', 'Mergel', 'Mergel.'),
  ('4700', 'Mergelstein', 'Mergelstein.'),
  ('4800', 'MikrogranitGranitporphyr', 'MikrogranitGranitporphyr.'),
  ('4900', 'Monzonit', 'Monzonit.'),
  ('5000', 'Muschelkalk', 'Muschelkalk.'),
  ('5100', 'Naturstein', 'Naturstein.'),
  ('5200', 'Naturwerkstein', 'Naturwerkstein.'),
  ('5300', 'Oelschiefer', 'Ölschiefer.'),
  ('5400', 'Pegmatitsand', 'Pegmatitsand.'),
  ('5500', 'Quarzit', 'Quarzit.'),
  ('5600', 'Quarzsand', 'Quarzsand.'),
  ('5700', 'Rhyolith', 'Rhyolith.'),
  ('5800', 'RhyolithQuarzporphyr', 'RhyolithQuarzporphyr.'),
  ('5900', 'Salz', 'Salz.'),
  ('6000', 'Sand', 'Sand.'),
  ('6100', 'Sandstein', 'Sandstein.'),
  ('6200', 'Spezialton', 'Spezialton.'),
  ('6300', 'SteineundErden', 'Steine und Erden.'),
  ('6400', 'Steinkohle', 'Steinkohle.'),
  ('6500', 'Ton', 'Ton.'),
  ('6600', 'Tonstein', 'Tonstein.'),
  ('6700', 'Torf', 'Torf.'),
  ('6800', 'TuffBimsstein', 'TuffBimsstein.'),
  ('6900', 'Uran', 'Uran.'),
  ('7000', 'Vulkanit', 'Vulkanit.'),
  ('7100', 'Werkstein', ''),
  ('9999', 'Sonstiges', 'Sonstiges.');

TRUNCATE
  enum_rp_wasserschutztypen;
INSERT INTO
  enum_rp_wasserschutztypen (wert,beschreibung)
VALUES
  ('1000', 'Wasserschutzgebiet', 'Wasserschutzgebiet.
Nach DIN 4046 "Einzugsgebiet oder Teil des Einzugsgebietes einer Wassergewinnungsanlage, das zum Schutz des Wassers Nutzungsbeschränkungen unterliegt."'),
  ('2000', 'Grundwasserschutz', 'Grundwasserschutz.'),
  ('2001', 'Grundwasservorkommen', 'Grundwasservorkommen.'),
  ('2002', 'Gewaesserschutz', 'Einzugsgebiet einer Talsperre.'),
  ('3000', 'Trinkwasserschutz', 'Trinkwasserschutz.'),
  ('4000', 'Trinkwassergewinnung', 'Trinkwassergewinnung.'),
  ('5000', 'Oberflaechenwasserschutz', 'Oberflächenwasserschutz.'),
  ('6000', 'Heilquelle', 'Heilquelle.'),
  ('7000', 'Wasserversorgung', 'Wasserversorgung.'),
  ('9999', 'SonstigerWasserschutz', 'Sonstiger Wasserschutz.');

TRUNCATE
  enum_rp_radwegwanderwegtypen;
INSERT INTO
  enum_rp_radwegwanderwegtypen (wert,beschreibung)
VALUES
  ('1000', 'Wanderweg', 'Wanderweg.'),
  ('1001', 'Fernwanderweg', 'Fernwanderweg.'),
  ('2000', 'Radwandern', 'Radwandern.'),
  ('2001', 'Fernradweg', 'Fernradweg.'),
  ('3000', 'Reiten', 'Reiten.'),
  ('4000', 'Wasserwandern', 'Wasserwandern.'),
  ('9999', 'SonstigerWanderweg', 'Sonstiger Wanderweg.');

TRUNCATE
  enum_rp_landwirtschafttypen;
INSERT INTO
  enum_rp_landwirtschafttypen (wert,beschreibung)
VALUES
  ('1000', 'LandwirtschaftlicheNutzung', 'Allgemeine Landwirtschaftliche Nutzung.'),
  ('1001', 'KernzoneLandwirtschaft', 'Kernzone Landwirtschaft.'),
  ('2000', 'IntensivLandwirtschaft', 'Intensive Landwirtschaft.'),
  ('3000', 'Fischerei', 'Fischerei.'),
  ('4000', 'Weinbau', 'Weinbau.'),
  ('5000', 'AufGrundHohenErtragspotenzials', 'Landwirtschaft auf Grund hohen Ertragspotenzials.'),
  ('6000', 'AufGrundBesondererFunktionen', 'Landwirtschaft auf Grund besonderer Funktionen.'),
  ('7000', 'Gruenlandbewirtschaftung', 'Grünlandbewirtschaftung.'),
  ('8000', 'Sonderkultur', ''),
  ('9999', 'SonstigeLandwirtschaft', 'Sonstige Landwirtschaft');

TRUNCATE
  enum_rp_kulturlandschafttypen;
INSERT INTO
  enum_rp_kulturlandschafttypen (wert,beschreibung)
VALUES
  ('1000', 'KulturellesSachgut', 'Kulturelles Sachgut.'),
  ('2000', 'Welterbe', 'Welterbe. Von der UNESCO verliehener Titel an Stätten mit außergewöhnlichem, universellem Wert, die als "Teile des Kultur- und Naturerbes von außergewöhnlicher Bedeutung sind und daher als Bestandteil des Welterbes der ganzen Menschheit erhalten werden müssen" (Präambel der Welterbekonvention von 1972)'),
  ('3000', 'KulturerbeLandschaft', 'Landschaftliches Kulturerbe.'),
  ('4000', 'KulturDenkmalpflege', ''),
  ('9999', 'SonstigeKulturlandschaftTypen', 'Sonstige Kulturlandschafttypen.');

TRUNCATE
  enum_rp_lufttypen;
INSERT INTO
  enum_rp_lufttypen (wert,beschreibung)
VALUES
  ('1000', 'Kaltluft', 'Kaltluft.'),
  ('2000', 'Frischluft', 'Frischluft.'),
  ('9999', 'SonstigeLufttypen', 'Sonstige Lufttypen.');

TRUNCATE
  enum_rp_bergbauplanungtypen;
INSERT INTO
  enum_rp_bergbauplanungtypen (wert,beschreibung)
VALUES
  ('1000', 'Lagerstaette', 'Lagerstätte.'),
  ('1100', 'Sicherung', 'Sicherung.'),
  ('1200', 'Gewinnung', 'Gewinnung.'),
  ('1300', 'Abbaubereich', 'Abbaubereich.'),
  ('1400', 'Sicherheitszone', 'Sicherheitszone.'),
  ('1500', 'AnlageEinrichtungBergbau', 'Anlage und/oder Einrichtung des Bergbaus.'),
  ('1600', 'Halde', 'Halde, Aufschüttung und/oder Ablagerung.'),
  ('1700', 'Sanierungsflaeche', 'Sanierungsfläche.'),
  ('1800', 'AnsiedlungUmsiedlung', 'Ansiedlung und/oder Umsiedlung.'),
  ('1900', 'Bergbaufolgelandschaft', 'Bergbaufolgelandschaft.'),
  ('9999', 'SonstigeBergbauplanung', 'Sonstige Bergbauplanung.');

TRUNCATE
  enum_rp_zeitstufen;
INSERT INTO
  enum_rp_zeitstufen (wert,beschreibung)
VALUES
  ('1000', 'Zeitstufe1', 'Zeitstufe 1.'),
  ('2000', 'Zeitstufe2', 'Zeitstufe 2.');

TRUNCATE
  enum_rp_bodenschutztypen;
INSERT INTO
  enum_rp_bodenschutztypen (wert,beschreibung)
VALUES
  ('1000', 'BeseitigungErheblicherBodenbelastung', 'Beseitigung von erheblicher Bodenbelastung.'),
  ('2000', 'SicherungSanierungAltlasten', 'Sicherung und/oder Sanierung von Altlasten.'),
  ('3000', 'Erosionsschutz', 'Erosionsschutz.'),
  ('9999', 'SonstigerBodenschutz', 'Sonstiger Bodenschutz.');

TRUNCATE
  enum_rp_hochwasserschutztypen;
INSERT INTO
  enum_rp_hochwasserschutztypen (wert,beschreibung)
VALUES
  ('1000', 'Hochwasserschutz', 'Hochwasserschutz.'),
  ('1001', 'TechnischerHochwasserschutz', 'Technischer Hochwasserschutz.'),
  ('1100', 'Hochwasserrueckhaltebecken', 'Hochwasserrückhaltebecken.'),
  ('1101', 'HochwasserrueckhaltebeckenPolder', 'Hochwasserrückhaltebecken: Polder.'),
  ('1102', 'HochwasserrueckhaltebeckenBauwerk', 'Hochwasserrückhaltebecken: Bauwerk.'),
  ('1200', 'RisikobereichHochwasser', 'Risikobereich Hochwasser.'),
  ('1300', 'Kuestenhochwasserschutz', 'Küstenhochwasserschutz.'),
  ('1301', 'Deich', 'Deich.'),
  ('1302', 'Deichrueckverlegung', 'Deichrückverlegung.'),
  ('1303', 'DeichgeschuetztesGebiet', ''),
  ('1400', 'Sperrwerk', 'Sperrwerk.'),
  ('1500', 'HochwGefaehrdeteKuestenniederung', 'Hochwassergefährdete Küstenniederung.'),
  ('1600', 'Ueberschwemmungsgebiet', 'Überschwemmungsgebiet.'),
  ('1700', 'UeberschwemmungsgefaehrdeterBereich', 'Überschwemmungsgefährdeter Bereich.'),
  ('1800', 'Retentionsraum', 'Retentionsraum.'),
  ('1801', 'PotenziellerRetentionsraum', 'Potenzieller Retentionsraum.'),
  ('9999', 'SonstigerHochwasserschutz', 'Sonstiger Hochwasserschutz.');

TRUNCATE
  enum_rp_naturlandschafttypen;
INSERT INTO
  enum_rp_naturlandschafttypen (wert,beschreibung)
VALUES
  ('1000', 'NaturLandschaft', 'NaturLandschaft.'),
  ('1100', 'NaturschutzLandschaftspflege', 'Naturschutz und Landschaftspflege.'),
  ('1101', 'NaturschutzLandschaftspflegeAufGewaessern', 'Naturschutz und Landschaftspflege auf Gewässern.'),
  ('1200', 'Flurdurchgruenung', 'Flurdurchgrünung.'),
  ('1300', 'UnzerschnitteneRaeume', 'Unzerschnittene Räume.'),
  ('1301', 'UnzerschnitteneVerkehrsarmeRaeume', 'Unzerschnittene verkehrsarme Räume.'),
  ('1400', 'Feuchtgebiet', 'Feuchtgebiet.'),
  ('1500', 'OekologischesVerbundssystem', 'Ökologisches Verbundssystem.'),
  ('1501', 'OekologischerRaum', 'Ökologischer Raum.'),
  ('1600', 'VerbesserungLandschaftsstrukturNaturhaushalt', 'Verbesserung der Landschaftsstruktur und des Naturhaushalts.'),
  ('1700', 'Biotop', 'Biotop.'),
  ('1701', 'Biotopverbund', 'Biotopverbund.'),
  ('1702', 'Biotopverbundachse', 'Biotopverbundsachse.'),
  ('1703', 'ArtenBiotopschutz', 'Arten- und/oder Biotopschutz.'),
  ('1704', 'Regionalpark', 'Regionalpark.'),
  ('1800', 'KompensationEntwicklung', 'Kompensation für Entwicklung.'),
  ('1900', 'GruenlandBewirtschaftungPflegeEntwicklung', 'Grünlandbewirtschaftung, -pflege und -entwicklung.'),
  ('2000', 'Landschaftsstruktur', 'Landschaftsstruktur.'),
  ('2100', 'LandschaftErholung', 'Landschaftsgebiet für Erholung.'),
  ('2200', 'Landschaftspraegend', 'Landschaftsprägend.'),
  ('2300', 'SchutzderNatur', 'Schutz der Natur.'),
  ('2400', 'SchutzdesLandschaftsbildes', 'Schutz des Landschaftsbildes.'),
  ('2500', 'Alpenpark', 'Alpenpark.'),
  ('9999', 'SonstigerNaturLandschaftSchutz', 'Sonstiger NaturLandschaftsschutz.');

TRUNCATE
  enum_rp_bodenschatztiefen;
INSERT INTO
  enum_rp_bodenschatztiefen (wert,beschreibung)
VALUES
  ('1000', 'Oberflaechennah', 'Oberflächennaher Bodenschatz.'),
  ('2000', 'Tiefliegend', 'Tiefliegender Bodenschatz.');

TRUNCATE
  enum_rp_forstwirtschafttypen;
INSERT INTO
  enum_rp_forstwirtschafttypen (wert,beschreibung)
VALUES
  ('1000', 'Wald', 'Wald.'),
  ('1001', 'Bannwald', 'Bannwald.
Nach §32 (2) Baden-Württemberg "ein sich selbst überlassenes Waldreservat. Pflegemaßnahmen sind nicht erlaubt; anfallendes Holz darf nicht entnommen werden. [...] Fußwege sind zulässig."
Nach $11 (1) BayWaldG Bayern " Wald, der auf Grund seiner Lage und seiner flächenmäßigen Ausdehnung vor allem in Verdichtungsräumen und waldarmen Bereichen unersetzlich ist, und deshalb in seiner Flächensubstanz erhalten werden muss und welchem eine außergewöhnliche Bedeutung für das Klima, den Wasserhaushalt oder die Luftreinigung zukommt.
Nach §13 (2) ForstG Hessen ein Wald, der "in seiner Flächensubstanz in besonderem Maße schützenswert ist".
In anderen Ländern ist ein Bannwald ggf. abweichend definiert.'),
  ('1002', 'Schonwald', 'Schonwald.
Nach §32 (3) Baden-Württemberg "ein Waldreservat, in dem eine bestimmte Waldgesellschaft mit ihren Tier- und Pflanzenarten, ein bestimmter Bestandsaufbau oder ein bestimmter Waldbiotop zu erhalten, zu entwickeln oder zu erneuern ist. Die Forstbehörde legt PFlegemaßnahmen mti Zustimmung des Waldbesitzers fest." In anderen Ländern ist ein Schonwald ggf. abweichend definiert.'),
  ('2000', 'Waldmehrung', 'Waldmehrung.'),
  ('2001', 'WaldmehrungErholung', 'Waldmehrung für Erholung.'),
  ('2002', 'VergroesserungDesWaldanteils', 'Vergrößerung des Waldanteils.'),
  ('3000', 'Waldschutz', 'Waldschutz.'),
  ('3001', 'BesondereSchutzfunktionDesWaldes', 'Besondere Schutzfunktion des Waldes.'),
  ('4000', 'VonAufforstungFreizuhalten', 'Von Aufforstung freizuhaltendes Gebiet.'),
  ('9999', 'SonstigeForstwirtschaft', 'Sonstige Forstwirtschaft.');

TRUNCATE
  enum_rp_besonderetourismuserholungtypen;
INSERT INTO
  enum_rp_besonderetourismuserholungtypen (wert,beschreibung)
VALUES
  ('1000', 'Entwicklungsgebiet', 'Entwicklungsgebiet.'),
  ('2000', 'Kernbereich', 'Kernbereich.'),
  ('3000', 'BesondereEntwicklungsaufgabe', 'Besondere Entwicklungsaufgabe von Tourismus und/oder Erholung.');

TRUNCATE
  enum_rp_wasserschutzzonen;
INSERT INTO
  enum_rp_wasserschutzzonen (wert,beschreibung)
VALUES
  ('1000', 'Zone1', 'Zone 1.
Für Grundwasser beinhaltet die Zone 1 den Fassungsbereich. In diesem Bereich und der unmittelbaren Umgebung um die Wassergewinnungsanlage muss jegliche Verunreinigung unterbleiben. Bei Talsperren  beinhaltet die Zone 1 den Stauraum mit Uferzone. Diese soll den Schutz vor unmittelbaren Verunreinigungen und sonstigen Beeinträchtigungen des Talsperrenwassers gewährleisten.
Die Ausdehnung der Zone I sollte im allgemeinen von Brunnen allseitig 10 m, von Quellen in Richtung des ankommenden Grundwassers mindestens 20 m und von Kaarstgrundwasserleitern mindestens 30 m betragen'),
  ('2000', 'Zone2', 'Zone 2.
Die engere Schutzzone.
Die Zone II reicht von der Grenze der Zone I bis zu einer Linie, von der aus das Grundwasser etwa 50 Tage bis zum Eintreffen in die Trinkwassergewinnungsanlage benötigt. Eine Zone II kann entfallen, wenn nur tiefere, abgedichtete Grundwasserstockwerke oder solche genutzt werden, die von der 50 Tage-Linie bis zur Fassung von undurchlässigen Schichten gegenüber der Mächtigkeit abgedeckt sind.'),
  ('3000', 'Zone3', 'Zone 3.
Die Weitere Schutzzone.
Die Zone III reicht von der Grenze des Einzugsgebietes bis zur Außengrenze der Zone II. Wenn das Einzugsgebiet weiter als 2 km reicht, so kann eine Aufgliederung in eine Zone III A bis etwa 2 km Entfernung ab Fassung und eine Zone III B etwa 2 km bis zur Grenze des Einzugsgebietes zweckmäßig sein.');

TRUNCATE
  enum_rp_tourismustypen;
INSERT INTO
  enum_rp_tourismustypen (wert,beschreibung)
VALUES
  ('1000', 'Tourismus', 'Tourismus.'),
  ('2000', 'Kuestenraum', 'Tourismus im Küstenraum.'),
  ('9999', 'SonstigerTourismus', 'Sonstiger Tourismus.');

TRUNCATE
  enum_rp_zaesurtypen;
INSERT INTO
  enum_rp_zaesurtypen (wert,beschreibung)
VALUES
  ('1000', 'Gruenzug', 'Grünzug.'),
  ('2000', 'Gruenzaesur', 'Grünzäsur.'),
  ('3000', 'Siedlungszaesur', 'Siedlungszäsur.');

TRUNCATE
  enum_rp_verkehrstatus;
INSERT INTO
  enum_rp_verkehrstatus (wert,beschreibung)
VALUES
  ('1000', 'Ausbau', 'Ausbau.'),
  ('1001', 'LinienfuehrungOffen', 'Linienführung offen.'),
  ('2000', 'Sicherung', 'Sicherung.'),
  ('3000', 'Neubau', 'Neubau.'),
  ('4000', 'ImBau', 'Im Bau befindliche Verkehrsinfrastruktur.'),
  ('5000', 'VorhPlanfestgestLinienbestGrobtrasse', 'Vorhandene planfestgestellte linienbestimmte Grobtrasse.'),
  ('6000', 'BedarfsplanmassnahmeOhneRaeumlFestlegung', 'Bedarfsplanmassnahme ohne räumliche Festlegung.'),
  ('7000', 'Korridor', 'Korridor.'),
  ('8000', 'Verlegung', 'Verlegung.'),
  ('9999', 'SonstigerVerkehrStatus', 'Sonstiger Verkehrsstatus.');

TRUNCATE
  enum_rp_besondererschienenverkehrtypen;
INSERT INTO
  enum_rp_besondererschienenverkehrtypen (wert,beschreibung)
VALUES
  ('1000', 'Eingleisig', 'Eingleisig.'),
  ('1001', 'Zweigleisig', 'Zweigleisig.'),
  ('1002', 'Mehrgleisig', 'Mehrgleisig.'),
  ('2000', 'OhneBetrieb', 'Schienenverkehrsinfrastruktur ohne Betrieb.'),
  ('3000', 'MitFernverkehrsfunktion', 'Schienenverkehrsinfrastruktur mit Fernverkehrsfunktion.'),
  ('3001', 'MitVerknuepfungsfunktionFuerOEPNV', 'Schienenverkehrsinfrastruktur mit Verknüpfungsfunktion für den öffentlichen Personennahverkehr.'),
  ('4000', 'ElektrischerBetrieb', 'Elektrischer Betrieb.'),
  ('4001', 'ZuElektrifizieren', 'Zu Elektrifizieren.'),
  ('5000', 'VerbesserungLeistungsfaehigkeit', 'Verbesserung der Leistungsfähigkeit.'),
  ('6000', 'RaeumlicheFreihaltungentwidmeterBahntrassen', 'Räumliche Freihaltung entwidmeter Bahntrassen.'),
  ('6001', 'NachnutzungstillgelegterStrecken', 'Nachnutzung stillgelegter Strecken.'),
  ('7000', 'Personenverkehr', 'Personenverkehr.'),
  ('7001', 'Gueterverkehr', 'Güterverkehr.'),
  ('8000', 'Nahverkehr', 'Nahverkehr.'),
  ('8001', 'Fernverkehr', 'Fernverkehr.');

TRUNCATE
  enum_rp_abfallentsorgungtypen;
INSERT INTO
  enum_rp_abfallentsorgungtypen (wert,beschreibung)
VALUES
  ('1000', 'BeseitigungEntsorgung', 'Beseitung beziehungsweise Entsorgung von Abfall.'),
  ('1100', 'Abfallbeseitigungsanlage', 'Abfallbeseitigungsanlage'),
  ('1101', 'ZentraleAbfallbeseitigungsanlage', 'Zentrale Abfallbeseitungsanlage.'),
  ('1200', 'Deponie', 'Deponie.'),
  ('1300', 'Untertageeinlagerung', 'Untertageeinlagerung von Abfall.'),
  ('1400', 'Behandlung', 'Behandlung von Abfall.'),
  ('1500', 'Kompostierung', 'Kompostierung von Abfall.'),
  ('1600', 'Verbrennung', 'Verbrennung von Abfall.'),
  ('1700', 'Umladestation', 'Umladestation.'),
  ('1800', 'Standortsicherung', 'Standortsicherung.'),
  ('9999', 'SonstigeAbfallentsorgung', 'Sonstige Abfallentsorgung.');

TRUNCATE
  enum_rp_wasserverkehrtypen;
INSERT INTO
  enum_rp_wasserverkehrtypen (wert,beschreibung)
VALUES
  ('1000', 'Hafen', 'Hafen.'),
  ('1001', 'Seehafen', 'Seehafen.'),
  ('1002', 'Binnenhafen', 'Binnenhafen.'),
  ('1003', 'Sportboothafen', 'Sportboothafen.'),
  ('1004', 'Laende', 'Lände.'),
  ('2000', 'Umschlagplatz', 'Umschlagplatz.'),
  ('3000', 'SchleuseHebewerk', 'Schleuse und/oder Hebewerk.'),
  ('4000', 'Schifffahrt', 'Schifffahrt.'),
  ('4001', 'WichtigerSchifffahrtsweg', 'Wichtiger Schifffahrtsweg.'),
  ('4002', 'SonstigerSchifffahrtsweg', 'Sonstiger Schifffahrtsweg.'),
  ('4003', 'Wasserstrasse', 'Wasserstraße.'),
  ('5000', 'Reede', 'Reede.'),
  ('9999', 'SonstigerWasserverkehr', 'Sonstiger Wasserverkehr.');

TRUNCATE
  enum_rp_spannungtypen;
INSERT INTO
  enum_rp_spannungtypen (wert,beschreibung)
VALUES
  ('1000', '110KV', '110 Kilovolt.'),
  ('2000', '220KV', '220 Kilovolt.'),
  ('3000', '330KV', '330 Kilovolt.'),
  ('4000', '380KV', '380 Kilovolt.');

TRUNCATE
  enum_rp_sozialeinfrastrukturtypen;
INSERT INTO
  enum_rp_sozialeinfrastrukturtypen (wert,beschreibung)
VALUES
  ('1000', 'Kultur', 'Kulturbezogene Infrastruktur.'),
  ('2000', 'Sozialeinrichtung', 'Sozialeinrichtung.'),
  ('3000', 'Gesundheit', 'Gesundheitsinfrastruktur.'),
  ('3001', 'Krankenhaus', 'Krankenhaus.'),
  ('4000', 'BildungForschung', 'Bildungs- und/oder Forschungsinfrastruktur.'),
  ('4001', 'Hochschule', 'Hochschule.'),
  ('5000', 'Polizei', 'Polizeiliche Infrastruktur'),
  ('9999', 'SonstigeSozialeInfrastruktur', 'Sonstige Soziale Infrastruktur.');

TRUNCATE
  enum_rp_strassenverkehrtypen;
INSERT INTO
  enum_rp_strassenverkehrtypen (wert,beschreibung)
VALUES
  ('1000', 'Strassenverkehr', 'Straßenverkehr.'),
  ('1001', 'Hauptverkehrsstrasse', 'Hauptverkehrsstraße.'),
  ('1002', 'Autobahn', 'Autobahn.'),
  ('1003', 'Bundesstrasse', 'Bundesstraße.'),
  ('1004', 'Staatsstrasse', 'Staatsstraße.'),
  ('1005', 'Landesstrasse', 'Landesstraße.'),
  ('1006', 'Kreisstrasse', 'Kreisstraße.'),
  ('1007', 'Fernstrasse', 'Fernstraße.'),
  ('2000', 'Trasse', 'Trasse.'),
  ('3000', 'Strassennetz', 'Straßennetz.'),
  ('4000', 'Busverkehr', 'Busverkehr.'),
  ('5000', 'Anschlussstelle', 'Anschlussstelle.'),
  ('6000', 'Strassentunnel', 'Straßentunnel.'),
  ('9999', 'SonstigerStrassenverkehr', 'Sonstiger Straßenverkehr.');

TRUNCATE
  enum_rp_energieversorgungtypen;
INSERT INTO
  enum_rp_energieversorgungtypen (wert,beschreibung)
VALUES
  ('1000', 'Leitungstrasse', 'Leitungstrasse.'),
  ('1001', 'Hochspannungsleitung', 'Hochspannungsleitung.'),
  ('1002', 'KabeltrasseNetzanbindung', 'Kabeltrasse-Netzanbindung.'),
  ('2000', 'Pipeline', 'Pipeline.'),
  ('2001', 'Uebergabestation', 'Übergabestation.'),
  ('3000', 'Kraftwerk', 'Kraftwerk.'),
  ('3001', 'Grosskraftwerk', 'Großkraftwerk.'),
  ('3002', 'Energiegewinnung', 'Energiegewinnung.'),
  ('4000', 'Energiespeicherung', 'Energiespeicherung.'),
  ('4001', 'VerstetigungSpeicherung', 'Verstetigung-Speicherung.'),
  ('4002', 'Untergrundspeicher', 'Untergrundspeicher.'),
  ('5000', 'Umspannwerk', 'Umspannwerk.'),
  ('6000', 'Raffinerie', 'Raffinerie.'),
  ('7000', 'Leitungsabbau', 'Leitungsabbau.'),
  ('9999', 'SonstigeEnergieversorgung', 'Sonstige Energieversorgung.');

TRUNCATE
  enum_rp_primaerenergietypen;
INSERT INTO
  enum_rp_primaerenergietypen (wert,beschreibung)
VALUES
  ('1000', 'Erdoel', 'Erdöl.'),
  ('2000', 'Gas', 'Gas.'),
  ('2001', 'Ferngas', 'Ferngas.'),
  ('3000', 'Fernwaerme', 'Fernwärme.'),
  ('4000', 'Kraftstoff', 'Kraftstoff.'),
  ('5000', 'Kohle', 'Kohle.'),
  ('6000', 'Wasser', 'Wasser.'),
  ('7000', 'Kernenergie', 'Kernenergie.'),
  ('8000', 'Reststoffverwertung', 'Reststoffverwertung.'),
  ('9000', 'ErneuerbareEnergie', 'Erneuerbare Energie.'),
  ('9001', 'Windenergie', 'Windenergie.'),
  ('9999', 'SonstigePrimaerenergie', 'Sonstige Primärenergie.');

TRUNCATE
  enum_rp_sonstverkehrtypen;
INSERT INTO
  enum_rp_sonstverkehrtypen (wert,beschreibung)
VALUES
  ('1000', 'Verkehrsanlage', 'Verkehrsanlage.'),
  ('1100', 'Gueterverkehrszentrum', 'Güterverkehrszentrum.'),
  ('1200', 'Logistikzentrum', 'Logistikzentrum.'),
  ('1300', 'TerminalkombinierterVerkehr', 'Terminal des kombinierten Verkehrs.'),
  ('1400', 'OEPNV', 'ÖPNV.'),
  ('1500', 'VerknuepfungspunktBahnBus', 'Verknüpfungspunkt Bahn-Bus.'),
  ('1600', 'ParkandRideBikeandRide', 'Park-and-Ride und/oder Bike-and-Ride.'),
  ('1700', 'Faehrverkehr', 'Fährverkehr.'),
  ('1800', 'Infrastrukturkorridor', 'Infrastrukturkorridor.'),
  ('1900', 'Tunnel', 'Tunnel.'),
  ('2000', 'NeueVerkehrstechniken', 'Neue Verkehrstechniken.'),
  ('9999', 'SonstigerVerkehr', 'Sonstiger Verkehr.');

TRUNCATE
  enum_rp_abwassertypen;
INSERT INTO
  enum_rp_abwassertypen (wert,beschreibung)
VALUES
  ('1000', 'Klaeranlage', 'Kläranlage.'),
  ('1001', 'ZentraleKlaeranlage', 'Zentrale Kläranlage.'),
  ('1002', 'Grossklaerwerk', 'Großklärwerk.'),
  ('2000', 'Hauptwasserableitung', 'Hauptwasserableitung.'),
  ('3000', 'Abwasserverwertungsflaeche', 'Abwasserverwertungsfläche.'),
  ('4000', 'Abwasserbehandlungsanlage', 'Abwasserbehandlungsanlage.'),
  ('9999', 'SonstigeAbwasserinfrastruktur', 'Sonstige Abwasserinfrastruktur.');

TRUNCATE
  enum_rp_besondererstrassenverkehrtypen;
INSERT INTO
  enum_rp_besondererstrassenverkehrtypen (wert,beschreibung)
VALUES
  ('1000', 'Zweistreifig', 'Zweistreifig.'),
  ('1001', 'Dreistreifig', 'Dreistreifig.'),
  ('1002', 'Vierstreifig', 'Vierstreifig.'),
  ('1003', 'Sechsstreifig', 'Sechsstreifig.'),
  ('2000', 'Problembereich', 'Problembereich.'),
  ('3000', 'GruenbrueckeQuerungsmoeglichkeit', 'Grünbrückenquerungsmöglichkeit.');

TRUNCATE
  enum_rp_abfalltypen;
INSERT INTO
  enum_rp_abfalltypen (wert,beschreibung)
VALUES
  ('1000', 'Siedlungsabfall', 'Siedlungsabfall.'),
  ('2000', 'Mineralstoffabfall', 'Mineralstoffabfall.'),
  ('3000', 'Industrieabfall', 'Industrieabfall.'),
  ('4000', 'Sonderabfall', 'Sonderabfall.'),
  ('5000', 'RadioaktiverAbfall', 'Radioaktiver Abfall.'),
  ('9999', 'SonstigerAbfall', 'Sonstiger Abfall.');

TRUNCATE
  enum_rp_luftverkehrtypen;
INSERT INTO
  enum_rp_luftverkehrtypen (wert,beschreibung)
VALUES
  ('1000', 'Flughafen', 'Flughafen.'),
  ('1001', 'Verkehrsflughafen', 'Verkehrsflughafen.'),
  ('1002', 'Regionalflughafen', 'Regionalflughafen.'),
  ('1003', 'InternationalerFlughafen', 'Internationaler Flughafen.'),
  ('1004', 'InternationalerVerkehrsflughafen', 'Internationaler Verkehrsflughafen.'),
  ('1005', 'Flughafenentwicklung', 'Flughafenentwicklung.'),
  ('2000', 'Flugplatz', 'Flugplatz.'),
  ('2001', 'Regionalflugplatz', 'Regionalflugplatz.'),
  ('2002', 'Segelflugplatz', 'Segelflugplatz.'),
  ('2003', 'SonstigerFlugplatz', 'Sonstiger Flugplatz.'),
  ('3000', 'Bauschutzbereich', 'Bauschutzbereich.'),
  ('4000', 'Militaerflughafen', 'Militärflughafen.'),
  ('5000', 'Landeplatz', 'Landeplatz.'),
  ('5001', 'Verkehrslandeplatz', 'Verkehrslandeplatz.'),
  ('5002', 'Hubschrauberlandeplatz', 'Hubschrauberlandeplatz.'),
  ('5003', 'Landebahn', 'Landebahn.'),
  ('9999', 'SonstigerLuftverkehr', 'Sonstiger Luftverkehr.');

TRUNCATE
  enum_rp_laermschutztypen;
INSERT INTO
  enum_rp_laermschutztypen (wert,beschreibung)
VALUES
  ('1000', 'Laermbereich', 'Lärmbereich.'),
  ('1001', 'Laermschutzbereich', 'Lärmschutzbereich'),
  ('2000', 'Siedlungsbeschraenkungsbereich', 'Siedlungsbeschränkungsbereich.'),
  ('3000', 'ZoneA', 'Zone A.'),
  ('4000', 'ZoneB', 'Zone B.'),
  ('5000', 'ZoneC', 'Zone C.'),
  ('9999', 'SonstigerLaermschutzBauschutz', 'Sonstiger Lärmschutz oder Bauschutz.');

TRUNCATE
  enum_rp_kommunikationtypen;
INSERT INTO
  enum_rp_kommunikationtypen (wert,beschreibung)
VALUES
  ('1000', 'Richtfunkstrecke', 'Richtfunkstrecke.'),
  ('2000', 'Fernmeldeanlage', 'Fernmeldeanlage.'),
  ('2001', 'SendeEmpfangsstation', 'Sende- und/oder Empfangsstation.'),
  ('2002', 'TonFernsehsender', 'Ton- und/oder Fernsehsender.'),
  ('9999', 'SonstigeKommunikation', 'Sonstige Kommunikationstypen.');

TRUNCATE
  enum_rp_wasserwirtschafttypen;
INSERT INTO
  enum_rp_wasserwirtschafttypen (wert,beschreibung)
VALUES
  ('1000', 'Wasserleitung', 'Wasserleitung.'),
  ('2000', 'Wasserwerk', 'Wasserwerk.'),
  ('3000', 'StaudammDeich', 'Staudamm und/oder Deich.'),
  ('4000', 'Speicherbecken', 'Speicherbecken.'),
  ('5000', 'Rueckhaltebecken', 'Rückhaltebecken.'),
  ('6000', 'Talsperre', 'Talsperre.'),
  ('7000', 'PumpwerkSchoepfwerk', 'Pumpwerk und/oder Schöpfwerk.'),
  ('9999', 'SonstigeWasserwirtschaft', 'Sonstige Wasserwirtschaft.');

TRUNCATE
  enum_rp_schienenverkehrtypen;
INSERT INTO
  enum_rp_schienenverkehrtypen (wert,beschreibung)
VALUES
  ('1000', 'Schienenverkehr', 'Schienenverkehr.'),
  ('1001', 'Eisenbahnstrecke', 'Eisenbahnstrecke.'),
  ('1002', 'Haupteisenbahnstrecke', 'Haupteisenbahnstrecke.'),
  ('1100', 'Trasse', 'Trasse.'),
  ('1200', 'Schienennetz', 'Schienennetz.'),
  ('1300', 'Stadtbahn', 'Stadtbahn.'),
  ('1301', 'Strassenbahn', 'Straßenbahn.'),
  ('1302', 'SBahn', 'S-Bahn.'),
  ('1303', 'UBahn', 'U-Bahn.'),
  ('1400', 'AnschlussgleisIndustrieGewerbe', 'Anschlussgleis für Industrie und Gewerbe.'),
  ('1500', 'Haltepunkt', 'Haltepunkt.'),
  ('1600', 'Bahnhof', 'Bahnhof.'),
  ('1700', 'Hochgeschwindigkeitsverkehr', 'Hochgeschwindigkeitsverkehr.'),
  ('1800', 'Bahnbetriebsgelaende', 'Bahnbetriebsgelände.'),
  ('1801', 'AnlagemitgrossemFlaechenbedarf', 'Anlage mit großem Flächenbedarf.'),
  ('9999', 'SonstigerSchienenverkehr', 'Sonstiger Schienenverkehr.');

TRUNCATE
  enum_rp_verkehrtypen;
INSERT INTO
  enum_rp_verkehrtypen (wert,beschreibung)
VALUES
  ('1000', 'Schienenverkehr', 'Schienenverkehr.'),
  ('2000', 'Strassenverkehr', 'Straßenverkehr.'),
  ('3000', 'Luftverkehr', 'Luftverkehr.'),
  ('4000', 'Wasserverkehr', 'Wasserverkehr.'),
  ('9999', 'SonstigerVerkehr', 'Sonstiger Verkehr.');

TRUNCATE
  enum_rp_achsentypen;
INSERT INTO
  enum_rp_achsentypen (wert,beschreibung)
VALUES
  ('1000', 'Achse', 'Achsen.'),
  ('2000', 'Siedlungsachse', 'Siedlungsachsen sind Achsen in Verdichtungsräumen, oft entlang von Strecken des öffentlichen Nahverkehrs.'),
  ('3000', 'Entwicklungsachse', 'Entwicklungsachse.'),
  ('3001', 'Landesentwicklungsachse', 'Landesentwicklungsachse.'),
  ('3002', 'Verbindungsachse', 'Verbindungsachsen sind durch Verkehrsbeziehungen zwischen zentralen Orten verschiedener Stufen gekennzeichnet.'),
  ('3003', 'Entwicklungskorridor', 'Entwicklungskorridor.'),
  ('4000', 'AbgrenzungEntwicklungsEntlastungsorte', 'Abgrenzung von Entwicklungs- und Entlastungsorten.'),
  ('5000', 'Achsengrundrichtung', 'Achsengrundrichtung.'),
  ('6000', 'AuessererAchsenSchwerpunkt', 'Äußerer Achsenschwerpunkt.'),
  ('9999', 'SonstigeAchse', 'Sonstige Achse.');

TRUNCATE
  enum_rp_zentralerortsonstigetypen;
INSERT INTO
  enum_rp_zentralerortsonstigetypen (wert,beschreibung)
VALUES
  ('1000', 'Doppelzentrum', 'Doppelzentrum.'),
  ('1100', 'Funktionsteilig', 'Funktionsteiliger Zentraler Ort.'),
  ('1101', 'MitOberzentralerTeilfunktion', 'Zentraler Ort mit oberzentraler Teilfunktion.'),
  ('1102', 'MitMittelzentralerTeilfunktion', 'Zentraler Ort mit mittelzentraler Teilfunktion.'),
  ('1200', 'ImVerbund', 'Zentraler Ort im Verbund.'),
  ('1300', 'Kooperierend', 'Kooperierender Zentraler Ort.'),
  ('1301', 'KooperierendFreiwillig', 'Freiwillig kooperierender Zentraler Ort.'),
  ('1302', 'KooperierendVerpflichtend', 'Verpflichtend kooperierender Zentraler Ort.'),
  ('1400', 'ImVerdichtungsraum', 'Zentraler Ort im Verdichtungsraum.'),
  ('1500', 'SiedlungsGrundnetz', 'Siedlungsgrundnetz.'),
  ('1501', 'SiedlungsErgaenzungsnetz', 'Siedlungsergänzungsnetz.'),
  ('1600', 'Entwicklungsschwerpunkt', 'Entwicklungsschwerpunkt.'),
  ('1700', 'Ueberschneidungsbereich', 'Überschneidungsbereich.'),
  ('1800', 'Ergaenzungsfunktion', 'Zentraler Ort mit Ergänzungsfunktion.'),
  ('1900', 'Nachbar', 'Zentraler Ort in Nachbarregionen oder Ländern.'),
  ('2000', 'MoeglichesZentrum', 'Mögliches Zentrum, zum Beispiel "mögliches Mittelzentrum".'),
  ('2100', 'FunktionsraumEindeutigeAusrichtung', 'Funktionsraum, eindeutige Ausrichtung.'),
  ('2101', 'FunktionsraumBilateraleAusrichtung', 'Funktionsraum, bilaterale Ausrichtung.'),
  ('9999', 'SonstigeSonstigerZentralerOrt', 'Sonstiger Sonstiger Zentraler Ort.');

TRUNCATE
  enum_rp_einzelhandeltypen;
INSERT INTO
  enum_rp_einzelhandeltypen (wert,beschreibung)
VALUES
  ('1000', 'Einzelhandel', 'Einzelhandel.'),
  ('2000', 'ZentralerVersorgungsbereich', 'Zentraler Versorgungsbereich.'),
  ('3000', 'ZentralerEinkaufsbereich', 'Zentraler Einkaufsbereich.'),
  ('4000', 'ZentrenrelevantesGrossprojekt', 'Zentrenrelevantes Großprojekt.'),
  ('5000', 'NichtzentrenrelevantesGrossprojekt', 'Nichtzentrenrelevantes Großprojekt.'),
  ('6000', 'GrossflaechigerEinzelhandel', 'Großflächiger Einzelhandel.'),
  ('7000', 'Fachmarktstandort', 'Fachmarktstandort.'),
  ('8000', 'Ergaenzungsstandort', 'Ergänzungsstandort.'),
  ('9000', 'StaedtischerKernbereich', 'Städtischer Kernbereich.'),
  ('9999', 'SonstigerEinzelhandel', 'Sonstiger Einzelhandel.');

TRUNCATE
  enum_rp_funktionszuweisungtypen;
INSERT INTO
  enum_rp_funktionszuweisungtypen (wert,beschreibung)
VALUES
  ('1000', 'Wohnen', 'Wohnfunktion.'),
  ('2000', 'Arbeit', 'Arbeitsfunktion.'),
  ('3000', 'GewerbeDienstleistung', 'Gewerbe- und/oder Dienstleistungsfunktion.'),
  ('4000', 'Einzelhandel', 'Einzelhandelsfunktion.'),
  ('5000', 'Landwirtschaft', 'Landwirtschaftliche Funktion.'),
  ('6000', 'ErholungFremdenverkehr', 'Erholungs-, Fremdenverkehrs- und/oder Tourismusfunktion.'),
  ('7000', 'Verteidigung', 'Verteidigungsfunktion.'),
  ('8000', 'UeberoertlicheVersorgungsfunktionLaendlicherRaum', 'Überörtliche Versorgungsfunktion.'),
  ('9999', 'SonstigeFunktion', 'Sonstige Funktion.');

TRUNCATE
  enum_rp_sperrgebiettypen;
INSERT INTO
  enum_rp_sperrgebiettypen (wert,beschreibung)
VALUES
  ('1000', 'Verteidigung', 'Verteidigung.'),
  ('2000', 'SondergebietBund', 'Sondergebiet Bund.'),
  ('3000', 'Warngebiet', 'Warngebiet.'),
  ('4000', 'MilitaerischeEinrichtung', 'Militärische Einrichtung.'),
  ('4001', 'GrosseMilitaerischeAnlage', 'Große militärische Anlage.'),
  ('5000', 'MilitaerischeLiegenschaft', 'Militärische Liegenschaft.'),
  ('6000', 'Konversionsflaeche', 'Konversionsfläche.'),
  ('9999', 'SonstigesSperrgebiet', 'Sonstige Sperrgebiete.');

TRUNCATE
  enum_rp_raumkategorietypen;
INSERT INTO
  enum_rp_raumkategorietypen (wert,beschreibung)
VALUES
  ('1000', 'Ordnungsraum', 'Ordnungsraum. Von der Ministerkonferenz für Raumordnung nach einheitlichen Abgrenzungskritierien definierter Strukturraum. Besteht aus Verdichtungsraum und der Randzone des Verdichtungsraums.'),
  ('1001', 'OrdnungsraumTourismusErholung', 'Ordnungsraum in Bezug auf Tourismus und Erholung.'),
  ('1100', 'Verdichtungsraum', 'Verdichtungsraum mit höherer Dichte an Siedlungen und Infrastruktur.'),
  ('1101', 'KernzoneVerdichtungsraum', 'Kernzone des Verdichtungsraum.'),
  ('1102', 'RandzoneVerdichtungsraum', 'Randzone des Verdichtungsraums.'),
  ('1103', 'Ballungskernzone', 'Ballungskernzone.'),
  ('1104', 'Ballungsrandzone', 'Ballungsrandzone.'),
  ('1105', 'HochverdichteterRaum', 'Hochverdichteter Raum'),
  ('1106', 'StadtUmlandBereichVerdichtungsraum', 'Stadt-Umland-Bereich im Verdichtungsraum'),
  ('1200', 'LaendlicherRaum', 'Ländlicher Raum.'),
  ('1201', 'VerdichteterBereichimLaendlichenRaum', 'Verdichteter Bereich im ländlichen Raum.'),
  ('1202', 'Gestaltungsraum', 'Gestaltungsraum.'),
  ('1203', 'LaendlicherGestaltungsraum', 'Ländlicher Gestaltungsraum.'),
  ('1300', 'StadtUmlandRaum', 'Stadt-Umland-Raum'),
  ('1301', 'StadtUmlandBereichLaendlicherRaum', 'Stadt-Umland-Bereich im ländlichen Raum.'),
  ('1400', 'AbgrenzungOrdnungsraum', 'Abgrenzung eines Ordnungsraums.'),
  ('1500', 'DuennbesiedeltesAbgelegenesGebiet', 'Dünnbesiedeltes, abgelegenes Gebiet.'),
  ('1600', 'Umkreis10KM', 'Umkreis von zehn Kilometern.'),
  ('1700', 'RaummitbesonderemHandlungsbedarf', 'Raum mit besonderem Handlungsbedarf, zum Beispiel vor dem Hintergrund des demographischen Wandels.'),
  ('1800', 'Funktionsraum', 'Funktionsraum.'),
  ('1900', 'GrenzeWirtschaftsraum', 'Grenze eines Wirtschaftsraums.'),
  ('2000', 'Funktionsschwerpunkt', 'Funktionsschwerpunkt.'),
  ('2100', 'Grundversorgung', 'Grundversorgung-Raumkategorie'),
  ('2200', 'Alpengebiet', 'Alpengebiet-Raumkategorie.'),
  ('2300', 'RaeumeMitGuenstigenEntwicklungsvoraussetzungen', 'Räume mit günstigen Entwicklungsaufgaben.'),
  ('2400', 'RaeumeMitAusgeglichenenEntwicklungspotenzialen', 'Raeume mit ausgeglichenen Entwicklungsvoraussetzungen.'),
  ('2500', 'RaeumeMitBesonderenEntwicklungsaufgaben', 'Räume mit besonderen Entwicklungspotentialen.'),
  ('9999', 'SonstigeRaumkategorie', 'Sonstige Raumkategorien');

TRUNCATE
  enum_rp_besondereraumkategorietypen;
INSERT INTO
  enum_rp_besondereraumkategorietypen (wert,beschreibung)
VALUES
  ('1000', 'Grenzgebiet', 'Grenzgebiete.'),
  ('2000', 'Bergbaufolgelandschaft', 'Bergbaufolgelandschaft.'),
  ('3000', 'Braunkohlenfolgelandschaft', 'Braunkohlenfolgelandschaften.');

TRUNCATE
  enum_rp_wohnensiedlungtypen;
INSERT INTO
  enum_rp_wohnensiedlungtypen (wert,beschreibung)
VALUES
  ('1000', 'Wohnen', 'Wohnen'),
  ('2000', 'Baugebietsgrenze', 'Baugebietsgrenze.'),
  ('3000', 'Siedlungsgebiet', 'Siedlungsgebiet.'),
  ('3001', 'Siedlungsschwerpunkt', 'Siedlungsschwerpunkt.'),
  ('3002', 'Siedlungsentwicklung', 'Siedlungsentwicklung.'),
  ('3003', 'Siedlungsbeschraenkung', 'Siedlungsbeschränkung.'),
  ('3004', 'Siedlungsnutzung', 'Sonstige WohnenSiedlungstypen.'),
  ('4000', 'SicherungEntwicklungWohnstaetten', ''),
  ('5000', 'AllgemeinerSiedlungsbereichASB', ''),
  ('9999', 'SonstigeWohnenSiedlung', '');

TRUNCATE
  enum_rp_industriegewerbetypen;
INSERT INTO
  enum_rp_industriegewerbetypen (wert,beschreibung)
VALUES
  ('1000', 'Industrie', 'Industrie.'),
  ('1001', 'IndustrielleAnlage', 'Industrielle Anlage.'),
  ('2000', 'Gewerbe', 'Gewerbe'),
  ('2001', 'GewerblicherBereich', 'Gewerblicher Bereich.'),
  ('2002', 'Gewerbepark', 'Gewerbepark.'),
  ('2003', 'DienstleistungGewerbeZentrum', ''),
  ('3000', 'GewerbeIndustrie', 'Gewerbe-Industrie.'),
  ('3001', 'BedeutsamerEntwicklungsstandortGewerbeIndustrie', 'Bedeutsamer Entwicklungsstandort von Gewerbe-Industrie.'),
  ('4000', 'SicherungundEntwicklungvonArbeitsstaetten', 'Sicherung und Entwicklung von Arbeitsstätten.'),
  ('5000', 'FlaechenintensivesGrossvorhaben', 'Flächenintensives Großvorhaben.'),
  ('6000', 'BetriebsanlageBergbau', 'Betriebsanlage des Bergbaus.'),
  ('7000', 'HafenorientierteWirtschaftlicheAnlage', 'Hafenorientierte wirtschaftliche Anlage.'),
  ('8000', 'TankRastanlage', 'Tankanlagen und Rastanlagen.'),
  ('9000', 'BereichFuerGewerblicheUndIndustrielleNutzungGIB', 'Sonstige Typen von Industrie und Gewerbe.'),
  ('9999', 'SonstigeIndustrieGewerbe', '');

TRUNCATE
  enum_rp_zentralerorttypen;
INSERT INTO
  enum_rp_zentralerorttypen (wert,beschreibung)
VALUES
  ('1000', 'Oberzentrum', 'Oberzentrum.'),
  ('1001', 'GemeinsamesOberzentrum', 'Gemeinsames Oberzentrum.'),
  ('1500', 'Oberbereich', 'Oberbereich.'),
  ('2000', 'Mittelzentrum', 'Mittelzentrum.'),
  ('2500', 'Mittelbereich', 'Mittelbereich.'),
  ('3000', 'Grundzentrum', 'Grundzentrum'),
  ('3001', 'Unterzentrum', 'Unterzentrum.'),
  ('3500', 'Nahbereich', 'Nahbereich.'),
  ('4000', 'Kleinzentrum', 'Kleinzentrum.'),
  ('5000', 'LaendlicherZentralort', 'Ländlicher Zentralort.'),
  ('6000', 'Stadtrandkern1Ordnung', 'Stadtrandkern 1. Ordnung'),
  ('6001', 'Stadtrandkern2Ordnung', 'Stadtrandkern 2. Ordnung'),
  ('7000', 'VersorgungskernSiedlungskern', 'Versorgungskern und/oder Siedlungskern'),
  ('8000', 'ZentralesSiedlungsgebiet', 'Zentrales Siedlungsgebiet.'),
  ('9000', 'Metropole', 'Metropole.'),
  ('9999', 'SonstigerZentralerOrt', 'Sonstiger Zentraler Ort.');

TRUNCATE
  enum_rp_spezifischegrenzetypen;
INSERT INTO
  enum_rp_spezifischegrenzetypen (wert,beschreibung)
VALUES
  ('1000', 'Zwoelfmeilenzone', 'Grenze der Zwölf-Seemeilen-Zone, in der Küstenstaaten das Recht haben, ihre Hoheitsgewässer auf bis zu 12 Seemeilen auszudehnen (nach Seerechtsübereinkommen der UN vom 10. Dezember 1982).'),
  ('1001', 'BegrenzungDesKuestenmeeres', 'Begrenzung des Küstenmeeres.'),
  ('2000', 'VerlaufUmstritten', 'Grenze mit umstrittenem Verlauf, beispielsweise zwischen Deutschland und den Niederlanden im Ems-Ästuar.'),
  ('3000', 'GrenzeDtAusschlWirtschaftszone', 'Grenze der Deutschen Ausschließlichen Wirtschaftszone (AWZ).'),
  ('4000', 'MittlereTideHochwasserlinie', 'Maß von Küstenlinien bei langjährig gemitteltem Küstenhochwasser.'),
  ('5000', 'PlanungsregionsgrenzeRegion', 'Grenze einer regionalen Planungsregion (z.B. Grenze eines Regionalplans).'),
  ('6000', 'PlanungsregionsgrenzeLand', 'Grenze einer landesweiten Planungsregion (z.B. Grenze eines Landesentwicklungsplans).'),
  ('7000', 'GrenzeBraunkohlenplan', 'Grenze eines Braunkohlenplans.'),
  ('8000', 'Grenzuebergangsstelle', 'Grenzübergangsstelle');
COMMIT;
