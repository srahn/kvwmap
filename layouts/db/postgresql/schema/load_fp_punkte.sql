-- Starten der Transaktion
START TRANSACTION;

-- Löschen der temporären Tabelle
delete from fp_punkte_temp;

-- Laden der Festpunktdaten in die Tabelle
copy fp_punkte_temp (PKZ,RW,HW,HOE,S,ZST,VMA,BEM,ENT,UNT,ZUO,TEX,LS,LG,LZ,LBJ,LAH,HS,HG,HZ,HBJ,HAH) FROM '/www/kvwmap/var/data/Festpunkte/festpunkte.csv' WITH DELIMITER AS ';';

UPDATE fp_punkte_temp SET
 rw=replace(rw,',','.'),
 hw=replace(hw,',','.'),
 hoe=replace(hoe,',','.');

-- Auffüllen der Geometriespalten aus den Angaben zu Rechts-, Hochwert und Höhe
UPDATE fp_punkte_temp SET the_geom=force_3DZ(GeometryFromText('POINT('||rw||' '||hw||' '||hoe||')',2398));

-- Selektieren der Punktnummern aus den Punktkennzeichen
UPDATE ".$this->tabellenname." SET pktnr=TRIM('0' FROM SUBSTRING(pkz FROM '.....$'));

-- Selektieren der Punktarten aus den Punktkennzeichen
UPDATE fp_Punkte_temp SET art=CAST(substring(pkz from '-(.)-') AS int);

-- Selektieren der Sicherungspunkte aus der Spalte ent
UPDATE fp_Punkte_temp SET art=5 WHERE ent LIKE '*%';

-- Selektieren des Dateinamens der Einmessungsskizze
UPDATE fp_Punkte_temp SET pkz=trim(both ' ' from pkz);
UPDATE fp_Punkte_temp SET datei=substring(pkz from 0 for position('-' in pkz))||'/'||replace(pkz,'-','')||'.tif';

-- Selektieren ob verhandelt oder nicht
UPDATE fp_Punkte_temp SET verhandelt=1 WHERE lah LIKE '%*';


-- Selektieren ob vermarkt oder unvermarkt
UPDATE fp_Punkte_temp SET vermarkt=1 WHERE vma NOT IN ('000','070','071','073','088','089','090','091','093');

-- Beenden der Transaktion
COMMIT;