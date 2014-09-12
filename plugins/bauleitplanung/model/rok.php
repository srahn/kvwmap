<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 # 
# Copyright (C) 2004  Peter Korduan                               #
#                                                                 # 
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  # 
# published by the Free Software Foundation; either version 2 of  # 
# the License, or (at your option) any later version.             # 
#                                                                 #   
# This program is distributed in the hope that it will be useful, #  
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #  
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  # 
# MA 02111-1307, USA.                                             # 
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
#############################
# Klasse rok #
#############################

class rok {
    
  function rok($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }
  
	function update_bplan_from_rok($plan_id){
		$gebiet2rok_table = array(	1 => 'wohngebiet',				#"Wohngebiet"
																2 => 'wohngebiet',	      #"spez. Wohnen"
																3 => 'mischgebiet',	      #"Mischgebiet"
																4 => 'gewerbegebiet',	      #"Gewerbegebiet"
																5 => 'industriegebiet',	      #"Industriegebiet"
																6 => 'gemeinbedarf',	      #"Gemeinbedarf"
																7 => 'ver_entsorgungsflaechen',	      #"Ver- und Entsorgungsfläche"
																8 => 'verkehrsflaechen',	      #"Verkehrsfläche"
																9 => 'landwirtschaft',	      #"Landwirtschaftsfläche"
																10 => 'waldgebiete',	      #"Waldfläche"
																11 => 'gruenflaechen',	      #"Grünfläche"
																12 => 'wasserflaechen',	      #"Wasserfläche"
																13 => 'rohstoffgebiete',	      #"Aufschüttung"
																14 => 'rohstoffgebiete',	      #"Abgrabung"
																15 => 'so_sport_freizeit',	      #"Sport und Freizeit"
																16 => 'so_sport_freizeit',	      #"Golfplatz"
																17 => 'so_sport_freizeit',	      #"Freizeitpark"
																18 => 'so_touristische_versorgung',	      #"touristische Versorgung"
																19 => 'so_wassertourismus',	      #"Wassertourismus"
																21 => 'so_sozialwesen',	      #"Sozialwesen"
																22 => 'so_militaer',	      #"Militär"
																23 => 'so_kulturwesen',	      #"Kulturwesen"
																24 => 'so_justiz',	      #"Justiz"
																25 => 'so_hafen',	      #"Wirtschaftshafen"
																26 => 'so_hafen',	      #"Hafen"
																27 => 'so_gesundheit',	      #"Gesundheitswesen"
																28 => 'so_erneuerbare_energie',	      #"erneuerbare Energie"
																29 => 'so_einzelhandel',	      #"Einzelhandel"
																30 => 'so_bildung_wissenschaft',	      #"Bildungswesen"
																31 => 'so_beherbergung',	      #"Beherbergung"
																32 => 'so_beherbergung',	      #"Wochenendhaus"
																33 => 'so_wohnen_ferienwohnen',	      #"Wohnen+Ferienwohnen"
																35 => 'so_beherbergung',	      #"Camping und Caravan"
															);

		$gebiet2rok_konkret = array(1 => array('WA','WB', 'WR', 'WS'),														#"Wohngebiet"
																13 => array('Aufschüttung'),								#'Aufschüttung'
																14 => array('Abgrabung', 'Bergsenkung'),			#'Abgrabung'
																32 => array('Wochenendhaus', 'Wochenendplatzgebiet')																#"Wochenendhaus"
																);

		$konkret2rok_konkret = array(	1	=> array('betreutes Wohnen'),
																	2	=> array('altersgerechtes Wohnen'),
																	3	=> array('Mehrgenerationenhaus'),
																	4	=> array('Altenwohnheim'),
																	5	=> array('Seniorenwohnheim'),
																	6	=> array('Seniorenresidenz'),
																	7	=> array('Altenstift'),
																	8	=> array('öffentl. Verwaltung'),	      #'öffentliche Verwaltung'
																	9 => array('Kirche und Religion'),      	#'Kirche/Religion'
																	10 => array('Post'),
																	11 => array('Feuerwehr'),
																	12 => array('Rettungswache'),
																	28 => array('Sportplatz'),
																	29 => array('Sporthalle'),
																	30 => array('Spielplatz'),
																	31 => array('Dressurplatz/Reitplatz'),										#'Dressurplatz, Reitplatz'
																	32 => array('Schwimmhalle'),
																	33 => array('Badeanstalt, Badeplatz, Strand'),	      		#'Badeanstalt, -platz, Strand'
																	34 => array('Schießsportanlage'),
																	35 => array('Motocross'),
																	36 => array('Jugendclub'),
																	37 => array('Tennisanlage'),
																	38 => array('Wild-, Tier-, Haustierpark'),	      				#'Wildpark, Tierpark, Haustierpark'
																	39 => array('Zoo'),
																	40 => array('Reiterhof'),
																	41 => array('Erlebnishof'),
																	42 => array('Minigolf'),
																	43 => array('Spielpark'),
																	44 => array('Freilichtbühne/Veranstaltungsplatz'),
																	45 => array('Golfinfrastruktur'),
																	46 => array('Golfspielfeld'),
																	47 => array('Wassersportzentrum'),
																	48 => array('Kanuverleih'),
																	49 => array('Wasserwanderrastplatz'),
																	50 => array('Seebrücke'),
																	51 => array('Altenwohnheim'),	      					#'Altenheim'
																	52 => array('Altenpflegeheim'),
																	53 => array('Behinderteneinrichtung'),
																	54 => array('Hospiz'),
																	55 => array('soziale Zwecke allgem.'),            	            #'soziale Zwecke allg.'
																	56 => array('Theater'),
																	57 => array('Oper'),
																	58 => array('Musical'),
																	59 => array('Kino'),
																	60 => array('Ausstellung'),
																	61 => array('Messe'),
																	62 => array('Kongress'),
																	63 => array('Galerie'),
																	64 => array('Kultur allgem.'), 							#'Kultur allg.'
																	65 => array('Museum'),
																	66 => array('Hafengebiet'),
																	67 => array('Sportboothafen'),
																	68 => array('Yachthafen'),
																	69 => array('Marina'),
																	70 => array('Werft'),
																	71 => array('Bootsservice'),
																	72 => array('Anleger'),
																	73 => array('Klinik'),
																	74 => array('Krankenhaus'),
																	75 => array('Kureinrichtung'),
																	76 => array('Rehabilitationseinrichtung'),	      #'Reha'
																	77 => array('Therapie'),
																	78 => array('Wellness'),
																	79 => array('Gesundheitshaus'),
																	84 => array('Schule'),
																	85 => array('Hochschule'),
																	86 => array('Fachhochschule'),
																	87 => array('Fortbildungseinrichtung'),
																	88 => array('Kita'),
																	89 => array('Krippe'),
																	90 => array('Hort'),
																	91 => array('Hotel'),
																	92 => array('Ferienhaus'),				#'Ferienhäuser'
																	93 => array('Pension'),
																	94 => array('Gasthof/Gastronomie'),
																	95 => array('Hostel'),
																	96 => array('Campingplatz'),
																	97 => array('Zeltplatz'),
																	98 => array('Caravanplatz/Wohnmobil'),
																	104 => array('Wald'),
																	105 => array('Aufforstung'),
																	106 => array('Tierklinik'),
																	107 => array('Geldinstitut'),
																	108 => array('EKZ/Fachmarktzentrum'),
																	109 => array('Lebensmittelmarkt'),
																	110 => array('Möbelmarkt'),
																	111 => array('Baumarkt/GartenCenter'),
																	112 => array('sonst. Einzelhandel')
																);

		$konkret2rok_themennr = array(13 => array(70110, 70115),	      #'Stromversorgung'
																	14 => array(70210, 70215),						#'Gasversorgung'
																	15 => array(70310, 70315),				#'Wärmeversorgung'
																	16 => array(70410, 70415),			#'Wasserversorgung'
																	17 => array(70510, 70515),	#'Abwasserentsorgung'
																	18 => array(70610, 70615),			#'Abfallbehandlung'
																	19 => array(70710, 70715), 			#'Abfallentsorgung'
																	20 => array(6030010, 6030015), 										#'Straßenverkehrsfläche'
																	22 => array(6050010, 6050015),										#'Schienenverkehrsfläche'
																	23 => array(6060010, 6060015, 6060110, 6060115, 6060210, 6060215),		#'Luftverkehrsfläche'
																	24 => array(6070010, 6070015),																		#'Umschlagplatz'
																	25 => array(6080010, 6080015),												#'Verkehrseinrichtung'
																	26 => array(90110, 90115),																					#'Grünflächen'
																	27 => array(90210, 90215),	      								#'Ausgleichs-, Kompensations- und Entwicklungsflächen'
																	80 => array(1043010, 1043015),	      #'Photovoltaik/Solar'
																	81 => array(1043110, 1043115),				#'Windenergie'
																	82 => array(1043210, 1043215),					#'Biomasse'
																	83 => array(1043310, 1043315),				#'sonstige Erneuerbare Energie'
																	99 => array(12010110, 12010115), 					#'Landwirtschaft/Acker'
																	100 => array(12010210, 12010215, 12010310, 12010315, 12010410, 12010415, 12010510, 12010515, 12010610, 12010615, 12010710, 12010715, 12010810, 12010815, 12010910, 12010915),		# 'Tierhaltung'
																	101 => array(70810),
																	102 => array(70910, 70915),			#Ablagerung
																	103 => array(71010, 71015) 					#'sonst. Versorgung'
																);
		# Gebiete aktualisieren
		$sql = "SELECT * FROM b_plan_gebiete WHERE plan_id = ".$plan_id;
		$ret = $this->database->execSQL($sql, 4, 0);
		while($gebiet = pg_fetch_array($ret[1])){
			$sql = "SELECT round((st_area(st_union(rok.shape))/10000)::numeric, 2) FROM b_plan_stammdaten as sd, rok_edit.".$gebiet2rok_table[$gebiet['gebietstyp']]." as rok ";
			$sql.= "WHERE sd.plan_id = ".$plan_id." AND rok.roknr = sd.lfd_rok_nr ";
			if($gebiet2rok_konkret[$gebiet['gebietstyp']] != ''){
				if($gebiet2rok_konkret[$gebiet['gebietstyp']][0] == 'NULL'){
					$sql .= "AND rok.konkretisierung IS NULL ";
				}
				else{
					$sql .= "AND rok.konkretisierung IN ('".implode("','", $gebiet2rok_konkret[$gebiet['gebietstyp']])."') ";
				}
			}
			if($konkret2rok_konkret[$gebiet['konkretisierung']] != ''){
				$sql .= "AND rok.konkretisierung IN ('".implode("','", $konkret2rok_konkret[$gebiet['konkretisierung']])."') ";
			}
			if($konkret2rok_themennr[$gebiet['konkretisierung']] != ''){
				$sql .= "AND rok.themennr IN (".implode(",", $konkret2rok_themennr[$gebiet['konkretisierung']]).") ";
			}
			#echo $sql.'<br>';
			$ret1 = $this->database->execSQL($sql, 4, 0);
			$flaeche = pg_fetch_array($ret1[1]);
			if($flaeche[0] != ''){
				$sql = "UPDATE b_plan_gebiete SET flaeche = ".$flaeche[0]." ";
				$sql.= "WHERE b_plan_gebiete.plan_id = ".$plan_id." ";
				$sql.= "AND b_plan_gebiete.gebietstyp = ".$gebiet['gebietstyp']." ";
				if($gebiet['konkretisierung'] != ''){
					$sql.= "AND b_plan_gebiete.konkretisierung = ".$gebiet['konkretisierung']." ";
				}
			}			
			#echo $sql.'<br>';
			$ret2 = $this->database->execSQL($sql,4, 1);
		}

		# Sondergebiete aktualisieren
		$sql = "SELECT * FROM b_plan_sondergebiete WHERE plan_id = ".$plan_id;
		$ret = $this->database->execSQL($sql, 4, 0);
		while($gebiet = pg_fetch_array($ret[1])){
			$sql = "SELECT round((st_area(st_union(rok.shape))/10000)::numeric, 2) FROM b_plan_stammdaten as sd, rok_edit.".$gebiet2rok_table[$gebiet['gebietstyp']]." as rok ";
			$sql.= "WHERE sd.plan_id = ".$plan_id." AND rok.roknr = sd.lfd_rok_nr ";
			if($konkret2rok_konkret[$gebiet['konkretisierung']] != ''){
				$sql .= "AND rok.konkretisierung IN ('".implode("','", $konkret2rok_konkret[$gebiet['konkretisierung']])."') ";
			}
			if($konkret2rok_themennr[$gebiet['konkretisierung']] != ''){
				$sql .= "AND rok.themennr IN (".implode(",", $konkret2rok_themennr[$gebiet['konkretisierung']]).") ";
			}
			#echo $sql.'<br>';
			$ret1 = $this->database->execSQL($sql, 4, 0);
			$flaeche = pg_fetch_array($ret1[1]);
			if($flaeche[0] != ''){
				$sql = "UPDATE b_plan_sondergebiete SET flaeche = ".$flaeche[0]." ";
				$sql.= "WHERE b_plan_sondergebiete.plan_id = ".$plan_id." ";
				$sql.= "AND b_plan_sondergebiete.gebietstyp = ".$gebiet['gebietstyp']." ";
				if($gebiet['konkretisierung'] != ''){
					$sql.= "AND b_plan_sondergebiete.konkretisierung = ".$gebiet['konkretisierung']." ";
				}
			}			
			#echo $sql.'<br>';
			$ret2 = $this->database->execSQL($sql,4, 1);
		}
		
		# Fläche des Geltungsbereichs aktualisieren
		$sql = "SELECT round((st_area(shape)/10000)::numeric, 2) FROM rok_edit.bpl_geltungsbereiche as rok, b_plan_stammdaten as sd ";
		$sql.= "WHERE sd.plan_id = ".$plan_id." AND rok.roknr = sd.lfd_rok_nr ";
		#echo $sql.'<br>';
		$ret1 = $this->database->execSQL($sql, 4, 0);
		$flaeche = pg_fetch_array($ret1[1]);
		if($flaeche[0] != ''){
			$sql = "UPDATE b_plan_stammdaten SET geltungsbereich = ".$flaeche[0]." ";
			$sql.= "WHERE plan_id = ".$plan_id." ";
		}			
		#echo $sql.'<br>';
		$ret2 = $this->database->execSQL($sql,4, 1);
		
	}
	
  function delete_bplan($plan_id){
  	$sql = "DELETE FROM b_plan_stammdaten WHERE plan_id = ".$plan_id.';';
  	$sql.= "DELETE FROM b_plan_gebiete WHERE plan_id = ".$plan_id.';';
  	$sql.= "DELETE FROM b_plan_sondergebiete WHERE plan_id = ".$plan_id.';';
  	#echo $sql;
  	$ret = $this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
     showAlert('Löschen fehlgeschlagen');
    }
    else{
    	showAlert('Löschen erfolgreich');
    }
  }
  
  function copy_bplan($plan_id){
  	$sql = "BEGIN;";
  	$sql.= "INSERT INTO b_plan_stammdaten (gkz, art, pl_nr, gemeinde_alt, geltungsbereich, bezeichnung, aktuell, lfd_rok_nr, aktenzeichen, kap_gemziel, kap_nachstell, datumeing, datumzust, datumabl, datumgenehm, datumbeka, datumaufh, erteilteaufl, ert_hinweis, ert_bemerkungen) ";
  	$sql.= "SELECT gkz, art, pl_nr, gemeinde_alt, geltungsbereich, bezeichnung, aktuell, lfd_rok_nr, aktenzeichen, kap_gemziel, kap_nachstell, datumeing, datumzust, datumabl, datumgenehm, datumbeka, datumaufh, erteilteaufl, ert_hinweis, ert_bemerkungen FROM b_plan_stammdaten ";
  	$sql.= "WHERE plan_id = ".$plan_id;
  	$ret = $this->database->execSQL($sql,4, 1);
  	$new_oid = pg_last_oid($ret[1]);
  	$sql = "SELECT plan_id FROM b_plan_stammdaten WHERE oid = ".$new_oid;
  	$ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$sql = "INSERT INTO b_plan_gebiete SELECT ".$rs['plan_id'].", gebietstyp, flaeche, kap_gemziel, kap_nachstell, konkretisierung FROM b_plan_gebiete WHERE plan_id = ".$plan_id.";";
		$sql.= "INSERT INTO b_plan_sondergebiete SELECT ".$rs['plan_id'].", gebietstyp, flaeche, kap1_gemziel, kap1_nachstell, kap2_gemziel, kap2_nachstell, konkretisierung FROM b_plan_sondergebiete WHERE plan_id = ".$plan_id.";";
		$sql.= "COMMIT;"; 
		$ret = $this->database->execSQL($sql, 4, 0);
  	if ($ret[0]) {
     showAlert('Kopieren fehlgeschlagen');
    }
    else{
    	showAlert('Kopieren erfolgreich');
    }
    return $new_oid;
  }
  
  function delete_fplan($oid){
  	$success = true;
  	$sql = "DELETE FROM tblf_plan WHERE oid = ".$oid;
  	#echo $sql;
  	$ret = $this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
     showAlert('Löschen fehlgeschlagen');
    }
    else{
    	showAlert('Löschen erfolgreich');
    }
  }
 	
  
  function getExtentFromRokNrBplan($roknr, $art, $border, $epsg) {
		if(in_array($art, array("B-Plan", "vb-Plan", "Einzelvorhaben", "BImSchG"))) $table = "rok_edit.bpl_geltungsbereiche";
		if(in_array($art, array("Innenbereichssatzung", "Außenbereichssatzung"))) $table = "rok_edit.satzungen ";
		if(in_array($art, array("Planungsanzeige"))) $table = "rok_edit.plananzeigen";
		$sql = "SELECT st_xmin(st_extent(st_transform(shape,".$epsg."))) AS minx,st_ymin(st_extent(st_transform(shape, ".$epsg."))) as miny,st_xmax(st_extent(st_transform(shape, ".$epsg."))) AS maxx,st_ymax(st_extent(st_transform(shape, ".$epsg."))) AS maxy FROM ".$table." WHERE roknr = '".$roknr."'";
		#echo $sql;
	  $ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = ms_newRectObj();
    $rect->minx=$rs['minx'];
    $rect->maxx=$rs['maxx'];
    $rect->miny=$rs['miny'];
    $rect->maxy=$rs['maxy'];
    $randx=($rect->maxx-$rect->minx)*$border/100;
    $randy=($rect->maxy-$rect->miny)*$border/100;
    $rect->minx -= $randx;
    $rect->miny -= $randy;
    $rect->maxx += $randx;
    $rect->maxy += $randy;
    return $rect;	
	}
	
	function getExtentFromRokNrFplan($gkz, $border, $epsg) {
		$sql = "SELECT st_xmin(st_extent(st_transform(the_geom,".$epsg."))) AS minx,st_ymin(st_extent(st_transform(the_geom, ".$epsg."))) as miny,st_xmax(st_extent(st_transform(the_geom, ".$epsg."))) AS maxx,st_ymax(st_extent(st_transform(the_geom, ".$epsg."))) AS maxy FROM kataster.adm_gem09 WHERE gemnr = '".$gkz."'";
		#echo $sql;
	  $ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = ms_newRectObj();
    $rect->minx=$rs['minx'];
    $rect->maxx=$rs['maxx'];
    $rect->miny=$rs['miny'];
    $rect->maxy=$rs['maxy'];
    $randx=($rect->maxx-$rect->minx)*$border/100;
    $randy=($rect->maxy-$rect->miny)*$border/100;
    $rect->minx -= $randx;
    $rect->miny -= $randy;
    $rect->maxx += $randx;
    $rect->maxy += $randy;
    return $rect;	
	}
}
	
?>
