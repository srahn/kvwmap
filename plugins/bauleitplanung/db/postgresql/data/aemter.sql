--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.9
-- Dumped by pg_dump version 9.1.3
-- Started on 2014-07-28 10:47:54

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = bauleitplanung, pg_catalog;

--
-- TOC entry 11470 (class 0 OID 883230)
-- Dependencies: 586
-- Data for Name: aemter; Type: TABLE DATA; Schema: bauleitplanung; Owner: kvwmap
--

INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (1, 'Rostock, Hansestadt', 1, 'Rostock');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (2, 'Schwerin, Landeshauptstadt', 1, 'Schwerin');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (101, 'Neubrandenburg, Stadt', 1, 'Neubrandenburg');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (111, 'Dargun, Stadt', 1, 'Dargun');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (112, 'Demmin, Hansestadt', 1, 'Demmin');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (113, 'Feldberger Seenlandschaft', 1, 'Feldberger Seenlandschaft');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (114, 'Neustrelitz, Stadt', 1, 'Neustrelitz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (115, 'Waren (Müritz), Stadt', 1, 'Waren (Müritz)');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (211, 'Bad Doberan, Stadt', 1, 'Bad Doberan');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (212, 'Dummerstorf, ehemals Warnow Ost', 1, 'Dummerstorf');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (213, 'Graal-Müritz', 1, 'Graal-Müritz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (214, 'Güstrow, Stadt', 1, 'Güstrow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (215, 'Kröpelin, Stadt', 1, 'Kröpelin');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (216, 'Kühlungsborn, Stadt', 1, 'Kühlungsborn');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (217, 'Neubukow, Stadt', 1, 'Neubukow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (218, 'Sanitz', 1, 'Sanitz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (219, 'Satow', 1, 'Satow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (220, 'Teterow, Stadt', 1, 'Teterow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (311, 'Binz', 1, 'Binz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (312, 'Grimmen, Stadt', 1, 'Grimmen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (313, 'Marlow, Stadt', 1, 'Marlow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (314, 'Putbus, Stadt', 1, 'Putbus');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (315, 'Sassnitz, Stadt', 1, 'Sassnitz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (316, 'Süderholz', 1, 'Süderholz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (317, 'Zingst', 1, 'Zingst');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (401, 'Wismar, Hansestadt', 1, 'Wismar');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (411, 'Grevesmühlen, Stadt', 1, 'Grevesmühlen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (412, 'Insel Poel', 1, 'Insel Poel');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (501, 'Greifswald, Hansestadt', 1, 'Greifswald');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (501, 'Stralsund, Hansestadt', 1, 'Stralsund');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (511, 'Anklam, Stadt', 1, 'Anklam');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (512, 'Heringsdorf', 1, 'Heringsdorf');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (513, 'Pasewalk, Stadt', 1, 'Pasewalk');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (514, 'Strasburg (Uckermark), Stadt', 1, 'Strasburg (Uckermark)');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (515, 'Ueckermünde, Stadt', 1, 'Ueckermünde');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (611, 'Boizenburg/Elbe, Stadt', 1, 'Boizenburg/Elbe');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (612, 'Hagenow, Stadt', 1, 'Hagenow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (613, 'Lübtheen, Stadt', 1, 'Lübtheen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (614, 'Ludwigslust, Stadt', 1, 'Ludwigslust');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (615, 'Parchim, Stadt', 1, 'Parchim');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5151, 'Demmin-Land', 0, 'Amt Demmin-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5152, 'Friedland', 0, 'Amt Friedland');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5153, 'Malchin am Kummerower See', 0, 'Amt Malchin am Kummerower See');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5154, 'Malchow', 0, 'Amt Malchow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5155, 'Mecklenburgische Kleinseenplatte', 0, 'Amt Mecklenburgische Kleinseenplatte');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5156, 'Neustrelitz-Land', 0, 'Amt Neustrelitz-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5157, 'Neverin', 0, 'Amt Neverin');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5158, 'Penzliner Land', 0, 'Amt Penzliner Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5159, 'Röbel-Müritz', 0, 'Amt  Röbel-Müritz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5160, 'Amt Seenlandschaft Waren', 0, 'Amt Seenlandschaft Waren');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5161, 'Stargarder Land', 0, 'Amt Stargarder Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5162, 'Stavenhagen', 0, 'Amt Stavenhagen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5163, 'Treptower Tollensewinkel', 0, 'Amt Treptower Tollensewinkel');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5164, 'Woldegk', 0, 'Amt Woldegk');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5251, 'Bad Doberan-Land', 0, 'Amt Bad Doberan-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5252, 'Bützow-Land', 0, 'Amt Bützow-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5253, 'Carbäk', 0, 'Amt Carbäk');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5254, 'Gnoien', 0, 'Amt Gnoien');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5255, 'Güstrow-Land', 0, 'Amt Güstrow-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5256, 'Krakow am See', 0, 'Amt Krakow am See');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5257, 'Laage', 0, 'Amt Laage');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5258, 'Mecklenburgische Schweiz', 0, 'Amt Mecklenburgische Schweiz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5259, 'Neubukow-Salzhaff', 0, 'Amt Neubukow-Salzhaff');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5260, 'Rostocker Heide', 0, 'Amt Rostocker Heide');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5261, 'Schwaan', 0, 'Amt Schwaan');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5262, 'Tessin', 0, 'Amt Tessin');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5263, 'Warnow-West', 0, 'Amt Warnow-West');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5351, 'Altenpleen', 0, 'Amt Altenpleen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5352, 'Barth', 0, 'Amt Barth');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5353, 'Bergen auf Rügen', 0, 'Amt Bergen auf Rügen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5354, 'Darß/Fischland', 0, 'Amt Darß/Fischland');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5355, 'Franzburg-Richtenberg', 0, 'Amt Franzburg-Richtenberg');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5356, 'Miltzow', 0, 'Amt Miltzow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5357, 'Mönchgut-Granitz', 0, 'Amt Mönchgut-Granitz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5358, 'Niepars', 0, 'Amt Niepars');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5359, 'Nord-Rügen', 0, 'Amt Nord-Rügen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5360, 'Recknitz-Trebeltal', 0, 'Amt Recknitz-Trebetal');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5361, 'Ribnitz-Damgarten', 0, 'Amt Ribnitz-Damgarten');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5362, 'West-Rügen', 0, 'Amt West-Rügen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5451, 'Dorf Mecklenburg-Bad Kleinen', 0, 'Amt Dorf Mecklenburg-Bad Kleinen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5452, 'Gadebusch', 0, 'Amt Gadebusch');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5453, 'Grevesmühlen-Land', 0, 'Amt Grevesmühlen-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5454, 'Klützer Winkel', 0, 'Amt Klützer Winkel');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5455, 'Lützow-Lübstorf', 0, 'Amt Lützow-Lübstorf');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5456, 'Neuburg', 0, 'Amt Neuburg');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5457, 'Neukloster-Warin', 0, 'Amt Neukloster-Warin');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5458, 'Rehna', 0, 'Amt Rehna');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5459, 'Schönberger Land', 0, 'Amt Schönberger Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5551, 'Am Peenestrom', 0, 'Amt Am Peenestrom');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5552, 'Am Stettiner Haff', 0, 'Am Stettiner Haff');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5553, 'Anklam-Land', 0, 'Anklam-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5554, 'Jarmen-Tutow', 0, 'Amt Jarmen-Tutow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5555, 'Landhagen', 0, 'Amt Landhagen');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5556, 'Löcknitz-Penkun', 0, 'Amt Löcknitz-Penkun');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5557, 'Lubmin', 0, 'Amt Lubmin');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5558, 'Peenetal/Loitz', 0, 'Amt Peenetal/Loitz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5559, 'Torgelow-Ferdinandshof', 0, 'Amt Torgelow-Ferdinandshof');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5560, 'Uecker-Randow-Tal', 0, 'Amt Uecker-Randow-Tal');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5561, 'Usedom-Nord', 0, 'Amt Usedom-Nord');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5562, 'Usedom-Süd', 0, 'Amt Usedom-Süd');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5563, 'Züssow', 0, 'Amt Züssow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5651, 'Banzkow', 0, 'Amt Banzkow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5652, 'Boizenburg-Land', 0, 'Amt Boizenburg-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5653, 'Crivitz', 0, 'Amt Crivitz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5654, 'Dömitz-Malliß', 0, 'Amt Dömitz-Malliß');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5655, 'Eldenburg Lübz', 0, 'Amt Eldenburg Lübz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5656, 'Goldberg-Mildenitz', 0, 'Amt Goldberg-Mildenitz');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5657, 'Grabow', 0, 'Amt Grabow');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5658, 'Hagenow-Land', 0, 'Amt Hagenow-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5659, 'Ludwigslust-Land', 0, 'Amt Ludwigslust-Land');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5660, 'Neustadt-Glewe', 0, 'Amt Neustadt-Glewe');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5661, 'Ostufer Schweriner See', 0, 'Amt Ostufer Schweriner See');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5662, 'Parchimer Umland', 0, 'Amt Parchimer Umland');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5663, 'Plau am See', 0, 'Amt Plau am See');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5664, 'Sternberger Seenlandschaft', 0, 'Amt Sternberger Seenlandschaft');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5665, 'Stralendorf', 0, 'Amt Stralendorf');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5666, 'Wittenburg', 0, 'Amt Wittenburg');
INSERT INTO aemter (amtsnr, name, amtsfrei, beschriftung) VALUES (5667, 'Zarrentin', 0, 'Amt Zarrentin');


-- Completed on 2014-07-28 10:47:56

--
-- PostgreSQL database dump complete
--

