<?php
###################################################################
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2008  Peter Korduan                               #
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
# peter.korduan@auf.uni-rostock.de                                #
###################################################################

################################################################################
# Klassenbibliothek für die Internet-GIS Anwendung der Kreisverwaltung Doberan #
################################################################################
# Liste der Klassen:
########################################
# GUI - Das Programm
# debugfile - Klasse für die Debugdatei
# LogFile
# FormObj
# selectFormObject extends FormObject
# db_MapObj
# Menue
########################################

###############################################################
# Klasse für die Funktionen der graphische Benutzeroberfläche #
###############################################################
# Klasse GUI #
##############
class GUI extends GUI_core{

  var $layout;
  var $style;
  var $mime_type;
  var $menue;
  var $pdf;
  var $addressliste;
  var $debug;
  var $dbConn;
  var $flst;
  var $formvars;
  var $legende;
  var $map;
  var $mapDB;
  var $img;
  var $FormObject;
  var $StellenForm;
  var $Fehlermeldung;
  var $Hinweis;
  var $Stelle;
  var $ALB;
  var $activeLayer;
  var $nImageWidth;
  var $nImageHeight;
  var $user;
  var $qlayerset;

  ###################### Liste der Funktionen ####################################
  #
  # adresswahl()
  # adresseSuchen()
  # aendernBodenRichtWert()
  # ALK_Fortfuehrung()
  # Adm_Fortfuehrung()
  # ALB_Anzeigen($FlurstKennz,$formnummer)
  # tmp_Adr_Tabelle_Aktualisieren()
  # ALB_Aenderung()
  # ALB_Fortfuehren()
  # ALB_Grundausstattung()
  # aktualisiereFestpunkte()
  # bestaetigungsformAnzeigen()
  # bodenrichtwerterfassung()
  # bodenRichtWertZoneLoeschen
  # changeMap()
  # changemenue_with_ajax($id, $status)
  # commitBodenrichtwertCopy
  # composePoint2Array($point,$minx,$miny,$scale)
  # composePolygon2Array($umring,$minx,$miny,$scale)
  # composePolygonWKTString($pathx,$pathy,$minx,$miny,$scale)
  # copyBodenrichtwertzonen
  # drawMap()
  # druckausschnittswahl()
  # editLayerForm($layerName,$oid)
  # erzeugenUebergabeprotokollNachweise()
  # exportMapToPDF()
  # festpunkteErgebnisanzeige
  # festpunkteSuchen()
  # festpunkteWahl()
  # festpunkteZeigen()
  # festpunkteZuAuftragSenden
  # flurstSuchen()
	# flurstSuchenByLatLng
  # flurstAnzeige($FlurstKennz)
  # flurstwahl()
  # getFormObjGemGemkgFlur($Gemeinde,$Gemarkung,$Flur)
  # getFormObjVermStelle($VermStelle)
  # getFunktionen()
  # getFeatureIDbyPolygon($data,$filterdatei,$filterattribut,$filtervalue)
  # GUI ($main,$style,$mime_type,$db) - constructor
  # grundbuchblattWahl()
  # loadMap($loadMapSource)
  # loadMultiLingualWords()
  # mapCommentForm()
  # metadateneingabe()
  # metadatensatzspeichern()
  # metadatenSuchen()
  # navMap($cmd)
  # nachweisDokumentAnzeigen()
  # nachweisFormSenden()
  # nachweisFormAnzeige()
  # nachweisAnzeige()
  # nachweisLoeschen()
  # nachweisRechercheByPolygon
  # nameSuchen($name)
  # neuerLayer()
  # notizErfassung
  # notizSpeichern
  # notizKatanlegen
  # notizKatbearbeiten
  # notizKategorieAenderung
  # output()
  # queryMap()
  # rechercheFormAnzeigen()
  # rollenwahl($Stelle_ID)
  # setFullExtent()
  # setNextMapExtent($consumetime)
  # setPrevMapExtent($consumetime)
  # setSpatialFilter($layername)
  # scaleMap($nScale)
  # showConstants
  # showStyles
  # setStoredMapExtent()
  # StatistikAuswahl()
  # StatistikAuswahlErgebnis()
  # suchparameterSetzen()
  # suchparameterLesen()
  # saveMap($saveMapDestination)
  # SachdatenAnzeige($rect)
  # zoomToALKGebaeude($GebaeudeListe,$border)
  # zoomToALKGemeinde($GemID,$border)
  # zoomToBodenrichtwertzone($oid,$border);
  # zoomToGebaeude($GebaeudeListe,$border)
  # zoomToGemarkung($GemID,$GemkgID,$border)
  # zoomToGemeinde($GemID,$border)
  # zoomToFestpunkte($FestpunktListe,$border)
  # zoomToFlur($GemID,$GemkgID,$FlurID,$border)
  # zoomToFlurst($FlurstListe,$border)
  # zoomToNachweis($nachweis,$border)
  # zoomToStoredMapExtent($storetime,$prevtime)
  # zoomMap($nZoomFactor)
  # zoomToRefExt()
  # versiegelungsFlaechenErfassung()
  # versiegelungsFlaechenSenden()
  # waehleBodenwertStichtagToCopy
  # WLDGE_Auswaehlen()
  # wmsExport()
  # wmsExportSenden()
  ################################################################################
  ################################################################################
  #2005-11-29_pk
  # Grundsätzliches zu Formularen:
  # Es gibt folgende Zustände, die ein Formular haben kann:
  # Ein Formular kann:
  # 1. völlig leer sein
  # 2. mit Defaultvalues gefüllt sein
  # 3. mit Werten eines vorhandenen Datensatzes gefüllt sein
  # 4. die Werte der vorherigen Eingabe enthalten
  # letzteres läßt sich noch unterteilen in:
  # 4.1. die Werte aus der vorherigen Eingabe ohne dass der Datensatz schon
  #      eingetragen wurde, z.B. bei Fehlermeldungen zu unvollständigen oder
  #      falschen Eingaben, dabei müssen alle Werte so wie vorher eingestellt sein
  # 4.2. die Werte aus der vorherigen Eingabe nachdem die Werte in die Datenbank
  #      eingetagen wurden. Hier bei kann es sein, dass nicht alle Werte aus der
  #      vorherigen Eingabe über nommen werden sollen, z.B. eine fortlaufende Nr.
  # Diese Zustände gilt es immer wieder bei der Bearbeitung von Dokumenten zu
  # berücksichtigen und zu vereinheitlichen.

  # Konstruktor
  function GUI($main, $style, $mime_type) {
    # Debugdatei setzen
    global $debug;
    $this->debug=$debug;
    # Logdatei für Mysql setzen
    global $log_mysql;
    $this->log_mysql=$log_mysql;
    # Logdatei für PostgreSQL setzten
    global $log_postgres;
    $this->log_postgres=$log_postgres;
    # layout Templatedatei zur Anzeige der Daten
    if ($main!="") $this->main=$main;
    # Stylesheetdatei
    if (isset($style)) $this->style=$style;
    # mime_type html, pdf
    if (isset ($mime_type)) $this->mime_type=$mime_type;
  }

  function loadPlugins(){
  	global $kvwmap_plugins;
	  $this->goNotExecutedInPlugins = false;
  	$plugins = scandir(PLUGINS, 1);
  	$code = '
		switch($this->go){';
			for($i = 0; $i < count($plugins)-2; $i++){
				if(in_array($plugins[$i], $kvwmap_plugins)){
					$code.= file_get_contents(PLUGINS.$plugins[$i].'/control/index.php');
				}
			}
			$code.= '
			default : {
				$this->goNotExecutedInPlugins = true;
			}		
		}';
		eval($code);
  }
  
  function truncateAlbAlkTables(){
  	echo 'ALB und ALK Tabellen werden geleert<br>';
 		echo 'mit Befehl:<br>';
  	$sql="SELECT * FROM pg_tables WHERE schemaname = 'public' AND tablename like 'alb%' OR tablename like 'alk%' OR tablename like 'edbs%'";
  	echo $sql;
  	echo '<br>Folgende Tabellen wurden geleert:';
  	$ret=$this->pgdatabase->execSQL($sql,4, 1);
  	$sql="TRUNCATE TABLE ";
  	while ($rs=pg_fetch_array($ret[1])) {
  		echo '<br>'.$rs['tablename'];
      if($sql=='TRUNCATE TABLE '){
      	$sql.="public.".$rs['tablename'];
      }
      else{
      	$sql.=",public.".$rs['tablename'];
      }
    }
    $sql.=" CASCADE";
    $this->pgdatabase->execSQL($sql,4, 1);
  } 
  
  function checkCaseAllowed($case){
  	if(!$this->Stelle->isMenueAllowed($case) AND !$this->Stelle->isFunctionAllowed($case)) {
      $this->Fehlermeldung=$this->TaskChangeWarning;
      $this->rollenwahl($this->Stelle->id);
      $this->output();
      exit;
    }
  }
  
	function getSVG_vertices(){
		# Diese Funktion liefert die Eckpunkte der Geometrien von allen aktiven Postgis-Layern, die im aktuellen Kartenausschnitt liegen
		$this->user->rolle->readSettings();
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->nurAktiveLayerOhneRequires = true;
		$layer = $mapDB->read_Layer(0);     # 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
		$anzLayer = count($layer);
		for($i = 0; $i < $anzLayer; $i++){
			if($layer[$i]['connectiontype'] == MS_POSTGIS){
				if($this->formvars['scale'] < $layer[$i]['minscale'] OR $layer[$i]['maxscale'] > 0 AND $this->formvars['scale'] > $layer[$i]['maxscale']){
        	continue;
      	}
				$layerdb = $mapDB->getlayerdatabase($layer[$i]['Layer_ID'], $this->Stelle->pgdbhost);
				$select = $mapDB->getSelectFromData($layer[$i]['Data']);
				$extent = 'st_transform(st_geomfromtext(\'POLYGON(('.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layer[$i]['epsg_code'].')';				
				$fromwhere = 'from ('.$select.') as foo1 WHERE st_intersects(the_geom, '.$extent.')';
				if($layer[$i]['Datentyp'] == 0){	# POINT
					$sql = 'SELECT st_x(the_geom), st_y(the_geom) FROM (SELECT st_transform(the_geom, '.$this->user->rolle->epsg_code.') as the_geom '.$fromwhere.') foo LIMIT 10000';
				}
				else{	# LINE / POLYGON
					$sql = 'SELECT st_x(the_geom), st_y(the_geom) FROM (SELECT st_transform(st_pointn(foo.linestring, foo.count1), '.$this->user->rolle->epsg_code.') AS the_geom
					FROM (SELECT generate_series(1, st_npoints(foo4.linestring)) AS count1, foo4.linestring FROM (
					SELECT st_GeometryN(foo2.linestring, foo2.count2) as linestring FROM (
					SELECT generate_series(1, st_NumGeometries(foo5.linestring)) AS count2, foo5.linestring FROM (SELECT st_multi(linefrompoly(st_intersection(the_geom, '.$extent.'))) AS linestring '.$fromwhere.') foo5) foo2
					) foo4) foo
					WHERE (foo.count1 + 1) <= st_npoints(foo.linestring)) foo3 LIMIT 10000';
				}
				#echo $sql;
				$ret=$layerdb->execSQL($sql,4, 0);
        if(!$ret[0]){
        	while ($rs=pg_fetch_array($ret[1])){
          	echo $rs[0].' '.$rs[1].'|';
          }
        }
			}
		} 
	}
	
	function getSVG_foreign_vertices(){
		# Diese Funktion liefert die Eckpunkte der Geometrien des übergebenen Postgis-Layers, die im aktuellen Kartenausschnitt liegen
		$this->user->rolle->readSettings();
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->nurAktiveLayerOhneRequires = true;
		if($this->formvars['layer_id'] > 0){
			$layer = $mapDB->get_Layer($this->formvars['layer_id']);
		}
		else{
			$rollenlayer = $mapDB->read_RollenLayer(-$this->formvars['layer_id'], NULL);
			$layer = $rollenlayer[0];
		}
		$offset = 0;
		if($layer['connectiontype'] == MS_POSTGIS){
			$layerdb = $mapDB->getlayerdatabase($layer['Layer_ID'], $this->Stelle->pgdbhost);
    	$data_attributes = $mapDB->getDataAttributes($layerdb, $layer['Layer_ID']);
    	if(in_array($data_attributes['geomtype'][$data_attributes['the_geom']] , array('MULTIPOLYGON', 'POLYGON', 'GEOMETRY'))){
    		$offset = 1;
    	}
    	$select = strtolower($mapDB->getSelectFromData($layer['Data']));
			if($this->formvars['layer_id'] > 0)$select = str_replace(' from ', ', '.$data_attributes['table_alias_name'][$data_attributes['the_geom']].'.oid as exclude_oid'.' from ', $select);		# bei Rollenlayern nicht machen
			$extent = 'st_transform(st_geomfromtext(\'POLYGON(('.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layer['epsg_code'].')';				
			$fromwhere = 'from ('.$select.') as foo1 WHERE st_intersects(the_geom, '.$extent.') ';
			if($this->formvars['layer_id'] > 0 AND $this->formvars['oid']){
				$fromwhere .= 'AND exclude_oid != '.$this->formvars['oid'];
			}
			# LINE / POLYGON
			$sql = 'SELECT st_x(the_geom), st_y(the_geom) FROM (SELECT st_transform(st_pointn(foo.linestring, foo.count1), '.$this->user->rolle->epsg_code.') AS the_geom
					FROM (SELECT generate_series(1, st_npoints(foo4.linestring)) AS count1, foo4.linestring FROM (
					SELECT CASE WHEN st_GeometryN(foo2.linestring, foo2.count2) IS NULL THEN foo2.linestring ELSE st_GeometryN(foo2.linestring, foo2.count2) END as linestring FROM (
					SELECT generate_series(1, st_NumGeometries(foo5.linestring)) AS count2, foo5.linestring FROM (SELECT st_multi(linefrompoly(st_intersection(the_geom, '.$extent.'))) AS linestring '.$fromwhere.') foo5) foo2
					) foo4) foo
					WHERE (foo.count1 + '.$offset.') <= st_npoints(foo.linestring)) foo3 LIMIT 10000';
			#echo $sql;
			$ret=$layerdb->execSQL($sql,4, 0);
      if(!$ret[0]){
      	while ($rs=pg_fetch_array($ret[1])){
        	echo $rs[0].' '.$rs[1].'|';
        }
      }
		} 
	}

	function reset_layers(){
		$this->user->rolle->resetLayers();
		$this->user->rolle->resetQuerys();
	}
	
	function reset_querys(){
		$this->user->rolle->resetQuerys();
	}

	function resizeMap2Window(){
		$width = $this->formvars['width']-475;
		if($this->user->rolle->hideMenue == 1){$width = $width + 195;}
		if($this->user->rolle->hideLegend == 1){$width = $width + 244;}
		$height = $this->formvars['height']-152;
		$this->user->rolle->setSize($width.'x'.$height);
		$this->user->rolle->readSettings();
	}
	
	function split_multi_geometries(){
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$spatial_processor = new spatial_processor($this->user->rolle, $this->database, $layerdb);
		$single_geoms = $spatial_processor->split_multi_geometries($this->formvars['newpathwkt'], $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
		$this->split_datasets($this->formvars['selected_layer_id'], array('oid'), array($this->formvars['oid']), array($this->formvars['layer_columnname']), $single_geoms, $mapdb);
		$this->loadMap('DataBase');					# Karte anzeigen
		$currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
		$this->output(); 
	}
	
	function split_datasets($layer_id, $id_names, $id_values, $update_columns, $update_values, $mapdb){
		# Diese Funktion teilt einen über die Arrays $id_names und $id_values bestimmten Datensatz in einem Layer auf x neue Datensätze auf.
		# Jeder Datensatz unterscheidet sich in den Attributen, die über das Array $update_columns definiert werden, von den anderen.
		# D.h. der Datensatz wird x-mal kopiert und alle Attribute in $update_columns auf den entsprechenden Wert im Array $update_values gesetzt.
		# Wie oft das passiert, hängt von der Größe des Arrays $update_values ab.
		# Außerdem wird dieses Splitting rekursiv auf den über SubformPK- oder embeddedPK verknüpften Layern durchgeführt. 
		
		$layerset = $this->user->rolle->getLayer($layer_id);
		$layerdb = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
		$layerattributes = $mapdb->read_layer_attributes($layer_id, $layerdb, NULL);
		
		# Attribute, die kopiert werden sollen ermitteln
		$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '".$layerset[0]['maintable']."' AND table_schema = '".$layerdb->schema."' ";
				
		$ret=$layerdb->execSQL($sql,4, 0);
		if(!$ret[0]){
			while ($rs=pg_fetch_row($ret[1])){
				if($layerattributes['constraints'][$rs[0]] != 'PRIMARY KEY') $attributes[] = $rs[0];
			}
		}
				
		for($i = 0; $i < count($update_values); $i++){
			$sql = "INSERT INTO ".$layerset[0]['maintable']." (".implode(',', $attributes).") SELECT ".implode(',', $attributes)." FROM ".$layerset[0]['maintable']." WHERE ";
			for($n = 0; $n < count($id_names); $n++){
				$sql.= $id_names[$n]." = '".$id_values[$n]."' AND ";
			}
			$sql.= "1=1 RETURNING oid"; 
			#echo $sql.'<br>';
			$ret = $layerdb->execSQL($sql,4, 0);
			$new_oids = array();
			if(!$ret[0]){
				while($rs=pg_fetch_row($ret[1])){
					$new_oids[] = $rs[0];
					$all_new_oids[] = $rs[0];
				}
			}
			if($new_oids[0] != ''){			# nur updaten, wenn auch was eingetragen wurde
				for($u = 0; $u < count($update_columns); $u++){
					$sql = "UPDATE ".$layerset[0]['maintable']." SET ".$update_columns[$u]." = '".$update_values[$i][$u]."' WHERE oid IN (".implode(',', $new_oids).")";
					$ret = $layerdb->execSQL($sql,4, 0);
				}
			}
		}
			
		if($all_new_oids[0] != ''){			# nur weitermachen, wenn auch was eingetragen wurde
			$j = 0;
			for($l = 0; $l < count($layerattributes['name']); $l++){
	    	if(in_array($layerattributes['form_element_type'][$l], array('SubFormEmbeddedPK', 'SubFormPK'))){
	    		$subform_pks = array();
	    		$pkvalues = array();
	    		$subform_pks = array();
	    		$next_update_values = array();;
					$options = explode(';', $layerattributes['options'][$l]);  
	        $subform = explode(',', $options[0]);  
	        $subform_layerid = $subform[0];
	        if($layerattributes['form_element_type'][$l] == 'SubFormEmbeddedPK')$minus = 1;
	        else $minus = 0;
	        for($k = 1; $k < count($subform)-$minus; $k++){
	        	$subform_pks[] = $subform[$k];																																# das sind die Namen der SubformPK-Schlüssel
	        }
	        $sql = "SELECT ".implode(',', $subform_pks)." FROM ".$layerset[0]['maintable']." WHERE ";			# die Werte der SubformPK-Schlüssel aus dem alten Datensatz abfragen
	        for($n = 0; $n < count($id_names); $n++){
						$sql.= $id_names[$n]." = '".$id_values[$n]."' AND ";
					}
					$sql.= "1=1";
					#echo $sql.'<br>';
	    		$ret=$layerdb->execSQL($sql,4, 0);
					if(!$ret[0]){
						$pkvalues=pg_fetch_row($ret[1]);
					}
	    		$sql = "SELECT ".implode(',', $subform_pks)." FROM ".$layerset[0]['maintable']." WHERE ";			# die Werte der SubformPK-Schlüssel aus den neuen Datensätzen abfragen
	        $sql.= "oid IN (".implode(',', $all_new_oids).")";
	        #echo $sql.'<br>';
	    		$ret=$layerdb->execSQL($sql,4, 0);
					if(!$ret[0]){
						while($rs=pg_fetch_row($ret[1])){
							$next_update_values[] = $rs;
						}
					}					
	        $j++;     	           
	        $this->split_datasets($subform_layerid, $subform_pks, $pkvalues, $subform_pks, $next_update_values, $mapdb);
		    }
			}
			$sql = "DELETE FROM ".$layerset[0]['maintable']." WHERE ";
			for($n = 0; $n < count($id_names); $n++){
				$sql.= $id_names[$n]." = '".$id_values[$n]."' AND ";
			}
			$sql.= "1=1";#
			#echo $sql.'<br>';
			$ret = $layerdb->execSQL($sql,4, 0);
		}
	}

  function import_layer(){
    if($this->formvars['neuladen']){
      $this->changeMap();
    }
    else{
      $this->formvars['nurFremdeLayer'] = true;
      $this->loadMap('DataBase');
    }
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
    }
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Client-Daten einlesen';
    $this->main='import_layer_form.php';
    $this->output();
  }

  function import_layer_importieren(){
    $this->loadMap('DataBase');
    $this->synchro = new synchro($this->Stelle, $this->user, $this->pgdatabase);
    $layerset = $this->user->rolle->getLayer('');
    for($i = 0; $i < count($layerset); $i++){
      if($this->formvars['thema'.$layerset[$i]['Layer_ID']]==1 AND $layerset[$i]['connectiontype'] == 6){
        $import_layerset[] = $layerset[$i];
      }
    }
    $this->synchro->import_layer_tables($import_layerset, $this->formvars);
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Layer-Import';
    $this->main='import_layer_form.php';
    $this->output();
  }

  function export_layer(){
    if($this->formvars['neuladen']){
      $this->changeMap();
    }
    else{
      $this->formvars['nurFremdeLayer'] = true;
      $this->loadMap('DataBase');
    }
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
    }
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Primär-Daten einlesen';
    $this->main='export_layer_form.php';
    $this->output();
  }

  function export_layer_exportieren(){
    $this->loadMap('DataBase');
    $this->synchro = new synchro($this->Stelle, $this->user, $this->pgdatabase);
    $layerset = $this->user->rolle->getLayer('');
    for($i = 0; $i < count($layerset); $i++){
      if($this->formvars['thema'.$layerset[$i]['Layer_ID']]==1 AND $layerset[$i]['connectiontype'] == 6){
        $export_layerset[] = $layerset[$i];
      }
    }
    $this->synchro->export_layer_tables($export_layerset, $this->formvars);
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Layer-Export';
    $this->main='export_layer_form.php';
    $this->output();
  }

  function get_select_list(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $attributenames[0] = $this->formvars['attribute'];
    $attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
    $req_start = strpos(strtolower($attributes['options'][0]), "<requires>");
    $req_end = strpos(strtolower($attributes['options'][0]), "</requires>")+11;
    $reqby_start = strpos(strtolower($attributes['options'][0]), "<required by>");
    if($reqby_start > 0)$ende = $reqby_start-$req_end;else $ende = strlen($attributes['options'][0]); 
    if($req_start > 0){
    	$sql_rest = substr($attributes['options'][0], $req_end, $ende);
      $sql = substr($attributes['options'][0], 0, $req_start)."'".$this->formvars['value']."' ".$sql_rest;    # requires-Tag aus SQL entfernen und um den übergebenen Wert erweitern
      $ret=$layerdb->execSQL($sql,4,0);
      if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
      switch($this->formvars['type']) {
  			case 'select-one' : {					# ein Auswahlfeld soll mit den Optionen aufgefüllt werden 
      		$html = '>';			# Workaround für dummen IE Bug
			$html .= '<option value="">-- Auswahl --</option>';
      		while($rs = pg_fetch_array($ret[1])){
        		$html .= '<option value="'.$rs['value'].'">'.$rs['output'].'</option>';
      		}
  			}break;
  			
  			case 'text' : {								#  ein Textfeld soll nur mit dem ersten Wert aufgefüllt werden
  				$rs = pg_fetch_array($ret[1]);
        	$html = $rs['output'];
  			}break;
      }
      echo $html;
    }
  }

  function showMapImage(){
  	$this->loadMap('DataBase');
  	$this->drawMap();
  	$randomnumber = rand(0, 1000000);
  	$svgfile  = $randomnumber.'.svg';
  	$jpgfile = $randomnumber.'.jpg';
  	$fpsvg = fopen(IMAGEPATH.$svgfile,w);
  	$svg='<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg id="svgmap" zoomAndPan="disable" width="'.$this->map->width.'" height="'.$this->map->height.'"  
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink">
<title> kvwmap </title><desc> kvwmap - WebGIS application - kvwmap.sourceforge.net </desc>';
		$this->formvars['svg_string'] = str_replace(IMAGEURL, IMAGEPATH, stripslashes($this->formvars['svg_string'])).'</svg>';
		$svg.= str_replace('points=""', 'points="-1000,-1000 -2000,-2000 -3000,-3000 -1000,-1000"', $this->formvars['svg_string']); 
		fputs($fpsvg, utf8_encode($svg));
  	fclose($fpsvg);
  	exec(IMAGEMAGICKPATH.'convert '.IMAGEPATH.$svgfile.' '.IMAGEPATH.$jpgfile);
  	#echo IMAGEMAGICKPATH.'convert '.IMAGEPATH.$svgfile.' '.IMAGEPATH.$jpgfile;

    if(function_exists('imagecreatefromjpeg')){
    	$mainimage = imagecreatefromjpeg(IMAGEPATH.$jpgfile);
    	if(strtolower(array_pop(explode('.', basename($this->img['scalebar'])))) == 'jpg'){
        $scaleimage = imagecreatefromjpeg(IMAGEPATH.basename($this->img['scalebar']));
      }
      elseif(strtolower(array_pop(explode('.', basename($this->img['scalebar'])))) == 'png'){
        $scaleimage = imagecreatefrompng(IMAGEPATH.basename($this->img['scalebar']));
      }
      ImageCopy($mainimage, $scaleimage, imagesx($mainimage)-imagesx($scaleimage), imagesy($mainimage)-imagesy($scaleimage), 0, 0, imagesx($scaleimage), imagesy($scaleimage));
      ob_end_clean();
      ob_start("output_handler");
      ImagePNG($mainimage);
    }
    else{
    	ob_end_clean();
      header('content-type: image/jpg');
      readfile(IMAGEPATH.$jpgfile);
    }
  }

  # Funktion zu Testzwecken der postgresql-Datenbankanfragens
  function loadDenkmale_laden(){
    # Erzeugen eines HIDA XML-Export-Dokument-Objektes
    $hidaDoc=new hidaDocument(DEFAULT_DENKMAL_IMPORT_FILE);
    # Einlesen der Felder in die Datenbank
    $hidaDoc->loadDocInDatabase();
    # Übergabe der Felder zur Ausgabe in HTML
    $this->fields=$hidaDoc->fields;
    # Löschen des Objektes
    unset($hidaDoc);
    # Setzen des Ausgabetemplates
    $this->main='denkmale_geladen.php';
  }
  
  function get_classes(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->classdaten = $mapDB->read_Classes($this->formvars['layer_id']);
    echo'
      <select style="width:200px" size="4" class="select" name="class_1" onchange="change_class();"';
    if(count($this->classdaten)==0){
      echo ' disabled';
    }
    echo ' >';
    for($i = 0; $i < count($this->classdaten); $i++){
      echo html_umlaute('<option value="'.$this->classdaten[$i]['Class_ID'].'">'.$this->classdaten[$i]['Name'].'</option>');
    }
    echo'
      </select>';
  }

  function get_styles(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['class_id']);
    echo'
      <table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td height="25" valign="top">Styles</td><td align="right"><a href="javascript:add_style();">neuer Style</a></td>
        </tr>';
    if(count($this->classdaten[0]['Style']) > 0){
      $this->classdaten[0]['Style'] = array_reverse($this->classdaten[0]['Style']);
      for($i = 0; $i < count($this->classdaten[0]['Style']); $i++){
        echo'
          <tr>
            <td ';
            if($this->formvars['style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID']){echo 'style="background-color:lightsteelblue;" ';}
            echo 'id="td1_style_'.$this->classdaten[0]['Style'][$i]['Style_ID'].'" onclick="get_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');">';
              echo '<img src="'.IMAGEURL.$this->getlegendimage($this->formvars['layer_id'], $this->classdaten[0]['Style'][$i]['Style_ID']).'"></td>';
              echo '<td align="right" id="td2_style_'.$this->classdaten[0]['Style'][$i]['Style_ID'].'" ';
              if($this->formvars['style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID']){echo 'style="background-color:lightsteelblue;" ';}
              echo '>';
              if($i < count($this->classdaten[0]['Style'])-1){echo '<a href="javascript:movedown_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="in der Zeichenreihenfolge nach unten verschieben"><img src="'.GRAPHICSPATH.'pfeil.gif" border="0"></a>';}
              if($i > 0){echo '&nbsp;<a href="javascript:moveup_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="in der Zeichenreihenfolge nach oben verschieben"><img src="'.GRAPHICSPATH.'pfeil2.gif" border="0"></a>';}
              echo html_umlaute('&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:delete_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');">löschen</a>');
        echo'
            </td>
          </tr>
          ';
      }
    }
    echo'
      </table>';
  }

  function get_labels(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['class_id']);
      echo'
        <table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td height="25" valign="top">Labels</td><td colspan="2" align="right"><a href="javascript:add_label();">neues Label</a></td>
          </tr>';
      if(count($this->classdaten[0]['Label']) > 0){
        for($i = 0; $i < count($this->classdaten[0]['Label']); $i++){
          echo'
            <tr>
              <td ';
              if($this->formvars['label_id'] == $this->classdaten[0]['Label'][$i]['Label_ID']){echo 'style="background-color:lightsteelblue;" ';}
              echo' id="td1_label_'.$this->classdaten[0]['Label'][$i]['Label_ID'].'" onclick="get_label('.$this->classdaten[0]['Label'][$i]['Label_ID'].');">';
                echo 'Label '.$this->classdaten[0]['Label'][$i]['Label_ID'].'</td>';
                echo '<td align="right" id="td2_label_'.$this->classdaten[0]['Label'][$i]['Label_ID'].'" ';
                if($this->formvars['label_id'] == $this->classdaten[0]['Label'][$i]['Label_ID']){echo 'style="background-color:lightsteelblue;" ';}
                echo html_umlaute('><a href="javascript:delete_label('.$this->classdaten[0]['Label'][$i]['Label_ID'].');">löschen</a>');
          echo'
              </td>
            </tr>';
        }
      }
      echo'
        </table>';
  }

  function get_styles_labels(){
    $this->get_styles();
    echo '^';
    $this->get_labels();
  }

  function save_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->save_Style($this->formvars);
    $this->get_styles();
    echo '^';
    $this->get_style();
  }

  function add_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $style = array();
    $style['color'] = '0 0 0';
    $style['size'] = 1;
    $style['maxsize'] = 1;
    if (MAPSERVERVERSION > '500') {
    	$style['angle'] = 360;
    }
    $new_style_id = $mapDB->new_Style($style);
    $mapDB->addStyle2Class($this->formvars['class_id'], $new_style_id, NULL);
    $this->get_styles();
  }

  function delete_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $classes = $mapDB->get_classes2style($this->formvars['style_id']);
    if(count($classes) == 1){
    	$mapDB->delete_Style($this->formvars['style_id']);
    }
    $mapDB->removeStyle2Class($this->formvars['class_id'], $this->formvars['style_id']);
    $this->formvars['style_id'] = $this->formvars['selected_style_id'];
    $this->get_styles();
  }
  
  function moveup_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->moveup_Style($this->formvars['style_id'], $this->formvars['class_id']);
    $this->formvars['style_id'] = $this->formvars['selected_style_id'];
    $this->get_styles();
  }
  
  function movedown_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->movedown_Style($this->formvars['style_id'], $this->formvars['class_id']);
    $this->formvars['style_id'] = $this->formvars['selected_style_id'];
    $this->get_styles();
  }

  function add_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $empty_label->font = 'arial';
    $empty_label->size = '8';
    $empty_label->minsize = '6';
    $empty_label->maxsize = '10';
    $new_label_id = $mapDB->new_Label($empty_label);
    $mapDB->addLabel2Class($this->formvars['class_id'], $new_label_id, 0);
    $this->get_labels();
  }

  function delete_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->delete_Label($this->formvars['label_id']);
    $mapDB->removeLabel2Class($this->formvars['class_id'], $this->formvars['label_id']);
    $this->formvars['label_id'] = $this->formvars['selected_label_id'];
    $this->get_labels();
  }

  function get_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->styledaten = $mapDB->get_Style($this->formvars['style_id']);
    if(is_array($this->styledaten)){
      echo'
        <table align="left" border="0" cellspacing="0" cellpadding="3">';
      for($i = 0; $i < count($this->styledaten); $i++){
        echo'
          <tr>
            <td class="verysmall">';
              echo key($this->styledaten).'</td><td><input ';
              if($i === 0)echo 'onkeyup="if(event.keyCode != 8)get_style(this.value)"';
              echo ' name="style_'.key($this->styledaten).'" size="11" type="text" value="'.$this->styledaten[key($this->styledaten)].'">';
        echo'
            </td>
          </tr>';
        next($this->styledaten);
      }
      echo'
          <tr>
            <td height="30" colspan="2" valign="bottom" align="center"><input class="button" type="button" name="style_save" value="Speichern" onclick="save_style('.$this->styledaten['Style_ID'].')"></td>
          </tr>
        </table>';
    }
  }

  function save_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->save_Label($this->formvars);
    $this->get_labels();
    echo '^';
    $this->get_label();
  }

  function get_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->labeldaten = $mapDB->get_Label($this->formvars['label_id']);
    if(count($this->labeldaten) > 0){
      echo'
        <table align="left" border="0" cellspacing="0" cellpadding="3">';
      for($i = 0; $i < count($this->labeldaten); $i++){
        echo'
          <tr>
            <td class="verysmall">';
              echo key($this->labeldaten).'</td><td><input name="label_'.key($this->labeldaten).'" size="11" type="text" value="'.$this->labeldaten[key($this->labeldaten)].'">';
        echo'
            </td>
          </tr>';
        next($this->labeldaten);
      }
      echo'
          <tr>
            <td height="30" colspan="2" valign="bottom" align="center"><input class="button" type="button" name="label_save" value="Speichern" onclick="save_label('.$this->labeldaten['Label_ID'].')"></td>
          </tr>
        </table>';
    }
  }

  function get_sub_menues(){
    $this->Menue = new menue($this->user->rolle->language,$this->user->rolle->charset);
    $submenues = $this->Menue->getsubmenues($this->formvars['menue_id']);
    echo '<select name="submenues" size="5" multiple style="width:200px">';
    for($i=0; $i < count($submenues["Bezeichnung"]); $i++){
      echo '<option selected title="'.$submenues["Bezeichnung"][$i].'" id="'.$submenues["ORDER"][$i].'_all_'.$submenues["menueebene"][$i].'_'.$i.'" value="'.$submenues["ID"][$i].'">&nbsp;&nbsp;-->&nbsp;'.$submenues["Bezeichnung"][$i].'</option>';
    }
    echo '</select>';
  }
  
	function getlayerfromgroup(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layer = $mapDB->get_layersfromgroup($this->formvars['group_id']);
    echo '<select name="alllayer" size="5" multiple style="width:200px">';
    for($i=0; $i < count($layer["Bezeichnung"]); $i++){
      echo '<option selected title="'.str_replace(' ', '&nbsp;', $layer["Bezeichnung"][$i]).'" value="'.$layer["ID"][$i].'">'.$layer["Bezeichnung"][$i].'</option>';
    }
    echo '</select>';
  }

  function get_group_legend(){
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    # Ein- oder Ausblenden der Klassen
    $this->user->rolle->setClassStatus($this->formvars);
    $this->loadMap('DataBase');
    echo $this->create_group_legend($this->formvars['group']);
  }

  function create_dynamic_legend(){
		foreach($this->groupset as $group){
			if($group['obergruppe'] == ''){
				$legend .= $this->create_group_legend($group['id']);
			}
		}
		$legend .= '<input type="hidden" name="layers" value="'.$this->layer_id_string.'">';
		return $legend;
  }
	
  function create_group_legend($group_id){
		if($this->groupset[$group_id]['untergruppen'] == NULL AND $this->groups_with_layers[$group_id] == NULL)return;			# wenns keine Layer oder Untergruppen gibt, nix machen
    $groupname = $this->groupset[$group_id]['Gruppenname'];
	  $groupstatus = $this->groupset[$group_id]['status'];
    $legend .=  '
	  <div id="groupdiv_'.$group_id.'" style="width:100%">
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%"><tr><td>
      <input id="group_'.$group_id.'" name="group_'.$group_id.'" type="hidden" value="'.$groupstatus.'">
      <a href="javascript:getlegend(\''.$group_id.'\', \'\', document.GUI.nurFremdeLayer.value)">
        <img border="0" id="groupimg_'.$group_id.'" src="graphics/';
		if($groupstatus == 1){
			$legend .=  'minus.gif">&nbsp;';
		}
		else{
			$legend .=  'plus.gif">&nbsp;';
		}
    $legend .=  '</a>';
		if($this->group_has_active_layers[$group_id] == ''){
			$legend .=  '<font color="firebrick" size="2">'.html_umlaute($groupname).'</font><br>';
		}
		else{
			$legend .=  '<b><font color="firebrick" size="2">'.html_umlaute($groupname).'</font></b><br>';
		}
		$legend .= '</td></tr><tr><td style="width:100%"><div id="layergroupdiv_'.$group_id.'" style="width:100%"><table cellspacing="0" cellpadding="0" style="width:100%">';
		$layercount = count($this->groups_with_layers[$group_id]);
    if($groupstatus == 1){		# Gruppe aufgeklappt
			for($u = 0; $u < count($this->groupset[$group_id]['untergruppen']); $u++){			# die Untergruppen rekursiv durchlaufen
				$legend .= '<tr><td style="width:100%"><table cellspacing="0" cellpadding="0" style="width:100%"><tr><td>&nbsp;&nbsp;&nbsp;</td><td style="width:100%">';
				$legend .= $this->create_group_legend($this->groupset[$group_id]['untergruppen'][$u]);
				$legend .= '</td></tr></table></td></tr>';
			}
			if($layercount > 0){		# Layer vorhanden
				if(!$this->formvars['nurFremdeLayer']){
					$legend .=  '<tr><td><input name="layers_of_group_'.$group_id.'" type="hidden" value="'.implode(',', $this->layers_of_group[$group_id]).'">
					<img border="0" src="graphics/leer.gif" width="8">
								<a href="javascript:selectgroupquery(document.GUI.layers_of_group_'.$group_id.')">
								<img border="0" src="graphics/pfeil.gif" title="Alle Abfragen ein/ausschalten"></a>
								<img border="0" src="graphics/leer.gif" width="1">
								<a href="javascript:selectgroupthema(document.GUI.layers_of_group_'.$group_id.')">
								<img border="0" src="graphics/pfeil.gif" title="Alle Themen ein/ausschalten"></a>
								<img border="0" src="graphics/leer.gif" width="4">alle</td></tr>';
				}
				for($j = 0; $j < $layercount; $j++){
					$layer = $this->layerset[$this->groups_with_layers[$group_id][$j]];
					$visible = $this->check_layer_visibility($layer);
					# sichtbare Layer					
					if($visible){
						if($layer['requires'] == ''){
							$legend .= '<tr><td>';
							$legend .=  '&nbsp;&nbsp;';
							if($layer['queryable'] == 1 AND !$this->formvars['nurFremdeLayer']){
								$legend .=  '<input id="qLayer'.$layer['Layer_ID'].'" title="Abfrage ein/ausschalten" ';
								
								if($this->user->rolle->singlequery){			# singlequery-Modus
									$legend .=  'type="radio" ';
									if($layer['selectiontype'] == 'radio'){
										$legend .=  ' onClick="if(event.preventDefault){event.preventDefault();}else{event.returnValue = false;};" onMouseDown="updateThema(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$group_id.', document.GUI.layers)"';
									}
									else{
										$legend .=  ' onClick="if(event.preventDefault){event.preventDefault();}else{event.returnValue = false;};" onMouseDown="updateThema(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), \'\', document.GUI.layers)"';
									}
								}
								else{			# normaler Modus
									if($layer['selectiontype'] == 'radio'){
										$legend .=  'type="radio" ';
										$legend .=  ' onClick="if(event.preventDefault){event.preventDefault();}else{event.returnValue = false;};" onMouseDown="updateThema(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$group_id.', \'\')"';
									}								
									else{
										$legend .=  'type="checkbox" ';
										$legend .=  ' onClick="updateThema(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), \'\', \'\')"';
									}
								}
								
								$legend .=  ' name="qLayer'.$layer['Layer_ID'].'" value="1" ';
								if($layer['queryStatus'] == 1){
									$legend .=  'checked';
								}
								$legend .=  ' >';
							}
							$legend .=  '<img border="0" src="graphics/leer.gif" width="1">';
							if ($layer['queryable'] != 1){
								$legend .=  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							}
							// die sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen, welches immer den value 0 hat, damit sie beim Neuladen ausgeschaltet werden können, denn eine nicht angehakte Checkbox/Radiobutton wird ja nicht übergeben
							$legend .=  '<input type="hidden" id="thema'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" value="0">';
							
							$legend .=  '<input id="thema_'.$layer['Layer_ID'].'" ';
							if($layer['selectiontype'] == 'radio'){
								$legend .=  'type="radio" ';
								$legend .=  ' onClick="if(event.preventDefault){event.preventDefault();}else{event.returnValue = false;};" onMouseDown="updateQuery(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$group_id.')"';
								$radiolayers[$group_id] .= $layer['Layer_ID'].'|';
							}
							else{
								$legend .=  'type="checkbox" ';
								$legend .=  ' onClick="updateQuery(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), \'\')"';
							}
							$legend .=  'title="Thema ein/ausschalten" name="thema'.$layer['Layer_ID'].'" value="1" ';
							if($layer['aktivStatus'] == 1){
								$legend .=  'checked';
							}
							$legend .= ' >';
							if($layer['metalink'] != ''){
								$legend .= '<a ';
								if(substr($layer['metalink'], 0, 10) != 'javascript'){
									$legend .= 'target="_blank"';
								}
								$legend .= ' class="metalink" href="'.$layer['metalink'].'">';
							}
							$legend .= '<font ';
							if($layer['minscale'] != -1 AND $layer['maxscale'] > 0){
								$legend .= 'title="'.$layer['minscale'].' - '.$layer['maxscale'].'"';
							}			  
							$legend .=' size="2">'.html_umlaute($layer['alias']).'</font>';
							if($layer['metalink'] != ''){
								$legend .= '</a>';
							}
							# Bei eingeschalteten Layern kann man auf die maximale Ausdehnung des Layers zoomen
							if ($layer['aktivStatus'] == 1) {
								if ($layer['connectiontype']==6) {
									# Link zum Zoomen auf maximalen Extent des Layers erstmal nur für PostGIS Layer
									$legend.='&nbsp;<a href="index.php?go=zoomToMaxLayerExtent&layer_id='.$layer['Layer_ID'].'"><img src="graphics/maxLayerExtent.gif" border="0" title="volle Layerausdehnung"></a>';
								}
							}
						}
						if($layer['aktivStatus'] == 1 AND $layer['Class'][0]['Name'] != ''){
							if($layer['requires'] == '' AND $layer['Layer_ID'] > 0){
								$legend .=  ' <a href="javascript:getlegend(\''.$group_id.'\', '.$layer['Layer_ID'].', document.GUI.nurFremdeLayer.value)" title="Klassen ein/ausblenden"><img border="0" src="graphics/';
								if($layer['showclasses']){
									$legend .=  'minus.gif';
								}
								else{
									$legend .=  'plus.gif';
								}
								$legend .=  '"></a>
								<input id="classes_'.$layer['Layer_ID'].'" name="classes_'.$layer['Layer_ID'].'" type="hidden" value="'.$layer['showclasses'].'">';
							}
							if($layer['showclasses'] != 0){
								if($layer['connectiontype'] == 7){      # WMS   
									$layersection = substr($layer['connection'], strpos(strtolower($layer['connection']), 'layers')+7);
									$layersection = substr($layersection, 0, strpos($layersection, '&'));
									$layers = explode(',', $layersection);
									for($l = 0; $l < count($layers); $l++){
									$legend .=  '<div style="display:inline" id="lg'.$j.'_'.$l.'"><br><img src="'.$layer['connection'].'&layer='.$layers[$l].'&request=getlegendgraphic" onerror="ImageLoadFailed(\'lg'.$j.'_'.$l.'\')"></div>';
									}
								}
								else{
									$legend .= '<table border="0" cellspacing="2" cellpadding="0">';
									$maplayer = $this->map->getLayerByName($layer['alias']);
									for($k = 0; $k < $maplayer->numclasses; $k++){
										$class = $maplayer->getClass($k);
										for($s = 0; $s < $class->numstyles; $s++){
											$style = $class->getStyle($s);
											if($current_group[$j]->type > 0){
												$symbol = $this->map->getSymbolObjectById($style->symbol);
												if($symbol->type == 1006){ 	# 1006 == hatch
													$style->set('size', 2*$style->width);					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt 
													$style->set('maxsize', 2*$style->width);
												}
												else{
													$style->set('size', 2);					# size und maxsize bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt 
													$style->set('maxsize', 2);
												}
											}
											else{
												$style->set('maxsize', $style->size);		# maxsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
											}
											if (MAPSERVERVERSION > 500){
												if($current_group[$j]->opacity < 100 AND $current_group[$j]->opacity > 0){			# Layer-Transparenz auch in Legendenbildchen berücksichtigen
													$hsv = rgb2hsv($style->color->red,$style->color->green, $style->color->blue);
													$hsv[1] = $hsv[1]*$current_group[$j]->opacity/100;
													$rgb = hsv2rgb($hsv[0], $hsv[1], $hsv[2]);
													$style->color->setRGB($rgb[0],$rgb[1],$rgb[2]);
												}
											}
											else {
												if($current_group[$j]->transparency < 100 AND $current_group[$j]->transparency > 0){			# Layer-Transparenz auch in Legendenbildchen berücksichtigen
													$hsv = rgb2hsv($style->color->red,$style->color->green, $style->color->blue);
													$hsv[1] = $hsv[1]*$current_group[$j]->transparency/100;
													$rgb = hsv2rgb($hsv[0], $hsv[1], $hsv[2]);
													$style->color->setRGB($rgb[0],$rgb[1],$rgb[2]);
												}												
											}
										}
										$image = $class->createLegendIcon(18,12);
										$filename = $this->map_saveWebImage($image,'jpeg');
										$newname = $this->user->id.basename($filename);
										rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
										#Anne
										$classid = $layer['Class'][$k]['Class_ID'];
										if($class->status=='MS_OFF'){
											$legend .= '<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" size="2" name="class'.$classid.'" value="0"><a href="#" onmouseover="mouseOverClassStatus('.$classid.',\''.TEMPPATH_REL.$newname.'\')" onmouseout="mouseOutClassStatus('.$classid.',\''.TEMPPATH_REL.$newname.'\')" onclick="changeClassStatus('.$classid.',\''.TEMPPATH_REL.$newname.'\')"><img border="0" name="imgclass'.$classid.'" src="graphics/inactive.jpg"></a>&nbsp;<span class="small">'.html_umlaute($class->name).'</span></td>
													</tr>';
										}
										else{
											$legend .= '<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" size="2" name="class'.$classid.'" value="1"><a href="#" onmouseover="mouseOverClassStatus('.$classid.',\''.TEMPPATH_REL.$newname.'\')" onmouseout="mouseOutClassStatus('.$classid.',\''.TEMPPATH_REL.$newname.'\')" onclick="changeClassStatus('.$classid.',\''.TEMPPATH_REL.$newname.'\')"><img border="0" name="imgclass'.$classid.'" src="'.TEMPPATH_REL.$newname.'"></a>&nbsp;<span class="small">'.html_umlaute($class->name).'</span></td>
													</tr>';
										}
									}
									$legend .= '</table>';
								}
							}
						}
						if($j+1 < $count AND $current_groupgetMetaData_off_requires != 1){		// todo
							$legend .= '</td></tr>';
						}
					}

					# unsichtbare Layer
					if($layer['requires'] == '' AND !$visible){
						$legend .=  '
									<tr>
									<td>
										&nbsp;&nbsp;';
						if($layer['queryable'] == 1){
							$legend .=  '<input ';
							if($layer['selectiontype'] == 'radio'){
								$legend .=  'type="radio" ';
							}
							else{
								$legend .=  'type="checkbox" ';
							}
							if($layer['queryStatus'] == 1){
								$legend .=  'checked="true"';
							}
							$legend .=' type="checkbox" name="pseudoqLayer'.$layer['Layer_ID'].'" disabled>';
						}
						$legend .=  '<img border="0" src="graphics/leer.gif" width="1">';
						if($layer['queryable'] != 1){
							$legend .=  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						}
						// die nicht sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen nur bei Radiolayern, damit sie beim Neuladen ausgeschaltet werden können, denn ein disabledtes input-Feld wird ja nicht übergeben
						$legend .=  '<input type="hidden" id="thema'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" value="'.$layer['aktivStatus'].'">';
						$legend .=  '<input ';
						if($layer['selectiontype'] == 'radio'){
							$legend .=  'type="radio" ';
							$radiolayers[$group_id] .= $layer['Layer_ID'].'|';
						}
						else{
							$legend .=  'type="checkbox" ';
						}
						if($layer['aktivStatus'] == 1){
							$legend .=  'checked="true" ';
						}
						$legend .= 'id="thema_'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" disabled="true">
						<font color="gray" ';
						if($layer['minscale'] != -1 AND $layer['maxscale'] != -1){
							$legend .= 'title="'.$layer['minscale'].' - '.$layer['maxscale'].'"';
						}
						$legend .= ' size="2">'.html_umlaute($layer['alias']).'</font>';
						if($layer['queryable'] == 1){
							$legend .=  '<input type="hidden" name="qLayer'.$layer['Layer_ID'].'"';
							if($layer['queryStatus'] != 0){
								$legend .=  ' value="1"';
							}
							$legend .=  '>';
						}
						$legend .=  '</td>
								</tr>';
					}
				}
			}
	  }
    $legend .= '</table></div></td></tr></table>';
    $legend .= '<input type="hidden" name="radiolayers_'.$group_id.'" value="'.$radiolayers[$group_id].'">';
	  $legend .= '</div>';
    return $legend;
  }
  
  function get_gps_position(){
    // erzeuge GPS Objekt
    $gps = new gps(GPSPATH);
    // frage aktuelle GPS-Position
    $gps->readPosition();
    // transformiere in gewünschtes Koordinatensystem
    $point=transform($gps->lon,$gps->lat,'4326',$this->formvars['srs']);
    // Ausgabe der Koordinaten im Format Rechtswert^Hochwert
    echo $point->x.'^'.$point->y;
 }

  function export_ESAF64(){
    $this->titel='Adressänderungen der Eigentümer exportieren';
    $this->main='export_esaf64.php';
    $this->output();
  }

  function export_ESAF64_exportieren(){
    $esaf = new esaf($this->pgdatabase);
    $esaf->delete_old_entries();
    $esaf->read_eigentuemer_data();
    $this->filename = $esaf->export_into_file();
    $this->export_ESAF64();
  }

  function export_ESAF64_bereiningen(){
    $esaf = new esaf($this->pgdatabase);
    $esaf->delete_old_entries();
    $this->export_ESAF64();
  }
  
  function exportWMC(){
    $this->WMCFileName = 'wmc-'.$this->Stelle->id.'-'.$this->user->id.'.xml'; 
  
    $this->loadMap('DataBase');
    
    $wmcMapObject = $this->prepareLayerForWMCExport($this->map);
    $wmcMapObject->saveMapContext(IMAGEPATH.$this->WMCFileName);
    $this->main  = 'wmc_exportiert.php';
    $this->titel = 'WMC-Export';
 	  $this->output();  
  }
  
  function prepareLayerForWMCExport($mapObject) {
    return $mapObject;
	}
 
  function spatialDocIndexing() {
    $doc=new textdocument($this->Gazdb);
    #$ret=$doc->spatialDocIndexing("/www/kvwmap/var/data/docs/","test.pdf",false,true);
    $test = $doc->pdf2string("/www/kvwmap/var/data/docs/Adressen_Katasteraemter.pdf");
    echo $test;
    return $ret;
  }

  function rewriteLayer() {
    ## in Entwicklung Konzept noch nicht zuende gedacht pk
    # Diese Funktion nimmt folgende Veränderungen in der MySQL Datenbank vor:
    # 1. löscht die vorhandenen Rollenlayer des Benutzers in der aktuellen Stelle
    # 2. trägt die im Formular übersendeten WMS Layer als Rollenlayer ein,
    # 3. ordnet diese der aktuellen Stelle und dem Benutzers zu
    # 4. trägt die im Formular übersendeten Map-Parameter in der Stelle und Rolle ein
    # zu 1:
    $this->LayerLoeschen(0);

  }

  function showStyles() {
    ob_end_flush();
    $this->main='styledaten.php';
    $this->titel='Styles';
  }

  function showFlurstueckKoordinaten() {
    $flurst=new flurstueck($this->formvars['FlurstKennz'],$this->pgdatabase);
    $ret=$flurst->getKoordinaten();
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      echo 'Lfdnr&nbsp;RW&nbsp;HW';
      $Punkte=$ret[1];
      for ($i=0;$i<count($Punkte);$i++) {
        echo '<br>'.$this->formvars['FlurstKennz'].'-'.$Punkte[$i]['lfdnr'];
        echo '&nbsp;'.$Punkte[$i]['x'];
        echo '&nbsp;'.$Punkte[$i]['y'];
      }
    }
  }
    
  function https_proxy(){
    $params = array_keys($this->formvars);
    for($i = 0; $i < count($this->formvars); $i++){
      if(in_array(strtolower($params[$i]), array('service', 'request', 'version', 'layer', 'layers', 'format', 'username', 'bbox', 'width', 'height', 'srs', 'user', 'pw'))){
        $url.='&'.$params[$i].'='.$this->formvars[$params[$i]];
      }
    }
    ob_end_clean();
    header('content-type:'.$this->formvars['format']);
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Disposition: filename=test.png');
    if(PHPVERSION > 500){
      $ctx = stream_context_create(array('http' => array('timeout' => 3)));
      print(file_get_contents($this->formvars['url'].'?'.$url, 0, $ctx));
    }
    else{                       # wenn php < Version 5, muss curl-Untrstützung da sein
      $ch = curl_init();    
      curl_setopt ($ch, CURLOPT_URL, $this->formvars['url'].'?'.$url);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3);
      print(curl_exec($ch));
    }
  }

  function createOWSException(){
    ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
    header('Content-type: text/xml');
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Disposition: filename=owsresponse');
    echo '<?xml version=\'1.0\' encoding="ISO-8859-1" standalone="no" ?>
    <!DOCTYPE ServiceExceptionReport SYSTEM "http://www.digitalearth.gov/wmt/xml/exception_1_0_1.dtd">
    <ServiceExceptionReport version="1.0.1">
    <ServiceException>
    '.$this->Fehlermeldung.'
    </ServiceException>
    </ServiceExceptionReport>
    ';
  }

  function owsProxy() {
    # prüft zunächst ob ein Bild schon mal abgefragt wurde
    # wenn ja, liefert der Dienst dieses Bild aus
    # wenn nicht wird der ows-respone neu erzeugt

    # wms Anfragestringobjekt erzeugen
    $wms_request = new wms_request_obj('');
    # Parameter und Werte in Kleinschreibung zurückgeben
    $wms_param=$wms_request->getKvpsToLower($this->formvars);
    $this->formvars = $wms_param;
    # Folgendes bezieht sich nur auf getMap Anfragen
    # alle anderen Operationen werden als ganz normale OWS-Requests abgearbeitet.
    if ($wms_param['request']=='getmap') {
      # Dateiformat zuweisen
      $imageformat=$wms_param['format'];
      # Dateiendung zuweisen
      $imageextention=substr(strstr($imageformat,'/'),1); # z.B. macht aus image/png png
      # eindeutigen Dateinamen erzeugen aus bbox Parameter
      $bbox=explode(',',$wms_param['bbox']);
      $box=$bbox[0].','.$bbox[1].','.$bbox[2].','.$bbox[3];
      $zoomstufe=round(log(720/($bbox[2]-$bbox[0]),2));
      $sw=round($bbox[0],1).','.round($bbox[1],1);
      $tmpfile = CACHEPATH.
        $wms_param['layers'].'_'.
        $zoomstufe.'-'.
        $sw.'_'.
        $wms_param['width'].'x'.
        $wms_param['height'].'.'.$imageextention;
      # Prüfen ob die Datei schon existiert
      if(file_exists($tmpfile)) {
        # Datei existiert schon, Ausgeben des Bildes an den Browser
        ob_end_clean();
        header('content-type:'.$imageformat);
        echo file_get_contents($tmpfile);
      }
      else {
        $this->tmpfile=$tmpfile;
        $this->writeTmpFile=true;
        //$this->createOWSResponse();
        $this->createBufferOWSResponse(200);
      }
    }
    else {
      $this->createOWSResponse();
    }
  }
  
  function createBufferOWSResponse($pixelbuffer){     # Angabe in Pixeln, wie groß der Buffer sein soll
    $width = $this->formvars['width'];
    $height = $this->formvars['height'];
    # Parameter um Buffer erweitern
    $buffer = $pixelbuffer/$this->formvars['width'];
    $bbox = explode(',', $this->formvars['bbox']);
    $extent_width = $bbox[2] - $bbox[0];
    $extent_buffer = $extent_width * $buffer;
    $this->formvars['width'] = $this->formvars['width'] + 2*$pixelbuffer;
    $this->formvars['height'] = $this->formvars['height'] + 2*$pixelbuffer;
    $bbox[0] = $bbox[0] - $extent_buffer;
    $bbox[1] = $bbox[1] - $extent_buffer;
    $bbox[2] = $bbox[2] + $extent_buffer;
    $bbox[3] = $bbox[3] + $extent_buffer;
    $this->formvars['bbox'] = $bbox[0].','.$bbox[1].','.$bbox[2].','.$bbox[3];
    # GetMap-Response erzeugen
    $this->class_load_level = 2;    # die Klassen von allen Layern laden
    $this->loadMap('DataBase');
    $requestobject = ms_newOwsRequestObj();
    $params = array_keys($this->formvars);
    for($i = 0; $i < count($this->formvars); $i++){
      $requestobject->setParameter($params[$i],$this->formvars[$params[$i]]);
    }
    ms_ioinstallstdouttobuffer();
    $this->map->owsdispatch($requestobject);
    $contenttype = ms_iostripstdoutbuffercontenttype();
    ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
    ob_start();
    if ($contenttype == 'image/png'){
      header('Content-type: image/png');
    }
    if ($contenttype == 'image/jpeg'){
      header('Content-type: image/jpeg');
    }
    ms_iogetStdoutBufferBytes();
    $contents = ob_get_contents();
    ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
    $image  = imagecreatefromstring($contents);
    # Bild clippen
    $clippedimage = imagecreatetruecolor($width, $height);
    $backgroundColor = imagecolorallocate($clippedimage, 255,255,255);
    imagefill($clippedimage, 0, 0, $backgroundColor);
    imagecolortransparent($clippedimage, $backgroundColor);
    ImageCopy($clippedimage, $image, 0, 0, $pixelbuffer, $pixelbuffer, $width, $height);
    if($contenttype == 'image/png'){
      imagepng($clippedimage);
      if ($this->writeTmpFile) {
        imagepng($clippedimage, $this->tmpfile);
      }
    }
    elseif($contenttype == 'image/jpeg'){
      imagejpeg($clippedimage);
      if ($this->writeTmpFile) {
        imagejpeg($clippedimage, $this->tmpfile);
      }
    }
    ob_end_flush();
    ms_ioresethandlers();   
  }

  function createOWSResponse(){
    $this->map_factor = $this->formvars['mapfactor'];   # der durchgeschleifte MapFactor
    $this->class_load_level = 2;    # die Klassen von allen Layern laden
    $this->loadMap('DataBase');
    $requestobject = ms_newOwsRequestObj();
    $params = array_keys($this->formvars);
    for($i = 0; $i < count($this->formvars); $i++){
      $requestobject->setParameter($params[$i],$this->formvars[$params[$i]]);
    }
    //$requestobject->loadparams();   # geht nur wenn php als cgi läuft
    ms_ioinstallstdouttobuffer();
    $this->map->owsdispatch($requestobject);
    $contenttype = ms_iostripstdoutbuffercontenttype();
    ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
    ob_start();
    if ($contenttype == 'image/png'){
      header('Content-type: image/png');
    }
    ms_iogetStdoutBufferBytes();
    if ($this->writeTmpFile) {
      $wms_response = new wms_response_obj($this->tmpfile);
      $wms_response->save(ob_get_contents());
    }
    ob_end_flush();
    ms_ioresethandlers();
  }

  function wmsImportieren() {
    echo 'todo'.$this->formvars['capabilitiesURI'].' als Layer in kvwmap eintragen';
  }

  function wmsImportFormular() {
    $this->main='wms_import.php';
    $this->titel='WMS Import';
    # Aufrufen eines Formulars für die Eingabe des getCapabilities Requests
    # das gleiche Formular wird verwendet für die Anzeige der gefundenen Layer eines Requests
    # nach der Eingabe und der Aktualisierung des Forms
    $this->formvars['capabilitiesURI']=TRIM($this->formvars['capabilitiesURI']);
    if ($this->formvars['capabilitiesURI']!='') {
      # Prüfen ob das Dokument gelesen werden kann
      if (file_exists($url)) {
        # kann nicht gelesen werden, Fehlermeldung
        $errmsg ="Fehler beim Anfragen des Dokumentes<br>";
        $errmsg.="<a href='".$this->formvars['capabilitiesURI']."' target='_blank'>";
        $errmsg.=$this->formvars['capabilitiesURI']."</a>";
        $errmsg.="<br>Das Capabilitiesdokument konnte nicht gefunden werden.";
        $errmsg.="<br>Prüfen Sie die Adresse und prüfen Sie diese ggf. in einem separaten Browserfenster.";
        $this->Fehlermeldung=$errmsg;
      }
      else {
        # Auswertung des Capabilities Requests
        # mit Mapbenderclasse class_wms.php
        include(MAPBENDER_CLASSPATH.'class_wms.php');
        $this->wms = new wms();
        $this->wms->createObjFromXML($this->formvars['capabilitiesURI']);
        /*
        # mit domxml selbst auslesen
        $dom = @domxml_open_file($this->formvars['capabilitiesURI']);
        # Abfragfen der online Resource des wms
        $requestelements=$dom->get_elements_by_tagname('Request');
        $getmapelements=$requestelements[0]->get_elements_by_tagname('GetMap');
        if (count($getmapelements)==0) {
          # GetMap-Element nicht vorhanden, suchen nach Operation
          $operationelements=$requestelements[0]->get_elements_by_tagname('Operation');
          foreach ($operationelements AS $operationelement) {
            if ($operationelement->get_attribute('name')=='GetMap') {
              $getmapelements[0]=$operationelement;
            }
          }
        }
        $dcptypeelement=$getmapelements[0]->get_elements_by_tagname('DCPType');
        $httpelement=$dcptypeelement[0]->get_elements_by_tagname('HTTP');
        $getelement=$httpelement[0]->get_elements_by_tagname('Get');
        $onlineresourceelement=$getelement[0]->get_elements_by_tagname('OnlineResource');
        # Zuweisung value of attribute href of node onlineresource
        $href=$onlineresourceelement[0]->get_attribute('href');
        # Behandeln der Trennzeichen zwischen URI und Parameterliste
        # Prüfen ob ein ? in der Zeichenkette vorkommt
        $questionmarkstr=strstr($href,'?');
        if ($questionmarkstr==false) {
          # Wenn das Zeichen nicht gefunden wurde wird es angehängt
          $href.='?';
        }
        else {
          # Prüfen ob hinter dem ? noch was kommt
          if ($questionmarkstr!='?') {
            # hinter dem ? kommt noch was, suche nach & Zeichen
            # Zunächst &amp; durch einfache & ersetzten
            $questionmarkstr=str_replace('&amp;','&',$questionmarkstr);
            # prüfen, ob ein & am Ende steht
            if (substr($questionmarkstr,-1,1)!='&') {
              # kein & Zeichen gefunden, ein & wird angehängt
              $href.='&';
            }
          } # ende hinter ? kommt noch was
        } # ende ein ? kommt in href vor
        $wmt_ms_capabilitieselement=$dom->document_element();
        $getmaprequest=$href.'request=GetMap';
        # Abfragen der WMS-Version
        # Zuweisung value of attribute version of node wmt_ms_capabilities
        $version='&version='.$wmt_ms_capabilitieselement->get_attribute('version');
        # GetMap-request
        # Abfragen der Layer und zusammensetzen der getMapURI
        $layerelements=$dom->get_elements_by_tagname('Layer');
        foreach($layerelements as $layerelement) {
          $sublayerelements=$layerelement->get_elements_by_tagname('Layer');
          if (count($sublayerelements)==0) {
            $names=$layerelement->get_elements_by_tagname('Name');
            # Zuweisung content of name
            if (count($names)>0) {
              $this->WMSlayer[]=$getmaprequest.$version.'&layers='.$names[0]->get_content();
              #echo '<br>'.$getmaprequest.$version.'&layers='.$name[0]->get_content();
            }
          }
        } */
      } # ende CapabilitiesURI konnte gelesen werden
    } # ende CapabilitiesURI wurde übergeben
    $this->output();
  }

  function adminFunctions() {
    switch ($this->formvars['func']) {
      case "showConstants" : {
        $this->showConstants();
      } break;
      case "closelogfiles" : {
        $this->closelogfiles();
      } break;
      case "showStyles" : {
        $this->showStyles();
      } break;
      case "showalkisclasses" : { # Version 1.6.5
        $this->showalkisclasses();
      } break;
      case "createRandomPassword" : {
        $this->createRandomPassword();
      } break;
      case "save_all_layer_attributes" : {
        $this->save_all_layer_attributes();
      } break;
      default : {
        $this->showAdminFunctions();
      }
    }
  }

	function save_all_layer_attributes(){
		$this->main='genericTemplate.php';
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layerdaten = $mapDB->get_postgis_layers(NULL);
		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
			$layer = $mapDB->get_Layer($this->layerdaten['ID'][$i]);
			if($layer['pfad'] != '' AND strpos($layer['connection'], 'host') === false){
				$this->param['str1'].= 'Layer: '.$layer['Name'].'<br>';
				echo 'Layer: '.$layer['Name'].'<br>';
				$layerdb = $mapDB->getlayerdatabase($layer['Layer_ID'], $this->Stelle->pgdbhost);
	    	$attributes = $mapDB->load_attributes($layerdb, $layer['pfad']);
	    	$mapDB->save_postgis_attributes($layer['Layer_ID'], $attributes, '');
	    	$mapDB->delete_old_attributes($layer['Layer_ID'], $attributes);
			}
		}
	}

  function showalkisclasses () { # Version 1.6.5
    $this->titel='ALKIS Klassen';
    $this->main='showalkisclasses.php';
    $this->alkisclasses = new alkisClass();
    $this->alkisclasses->database = $this->ALKISdb;
  }

  function createRandomPassword() {
    $this->titel='Zufälliges Passwort';
    $this->main='genericTemplate.php';
    $this->param['height']=400;
    $this->param['str1']='<h3>10 sichere und zufällig erzeugte Passwörter</h3>';
    while($i++ < 10) {
      $this->param['str1'].='<br><b>'.createRandomPassword(8).'</b>';
    }
  }

  function closelogfiles(){
    $dump_rolle =  $this->database->create_update_dump('rolle');
    $dump_rolle2usedlayer =  $this->database->create_update_dump('u_rolle2used_layer');
    $dump_menue2rolle =  $this->database->create_update_dump('u_menue2rolle');
    $dump_groups2rolle =  $this->database->create_update_dump('u_groups2rolle');
    $this->database->logfile->write($dump_rolle);
    $this->database->logfile->write($dump_rolle2usedlayer);
    $this->database->logfile->write($dump_menue2rolle);
    $this->database->logfile->write($dump_groups2rolle);
    $this->main='showadminfunctions.php';
    $this->titel='Administrationsfunktionen';
  }

  function showAdminFunctions() {
    $this->main='showadminfunctions.php';
    $this->titel='Administrationsfunktionen';
  }

  function showConstants() {
    $this->main='showconstants.php';
    $this->titel='Konstanten';
  }

  function grundbuchblattWahl() {
    $this->titel='Suche nach Grundbuchblättern';
    $this->main='grundbuchblattsuchform.php';
    $grundbuch = new grundbuch('', '', $this->pgdatabase);
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    if($GemeindenStelle != ''){   // Stelle ist auf Gemeinden eingeschränkt
      $Gemeinde=new gemeinde('',$this->pgdatabase);
      if(ALKIS)$GemListe=$Gemeinde->getGemeindeListeALKIS($GemeindenStelle,'bezeichnung');
    	else $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle,'GemeindeName');
      $Gemarkung=new gemarkung('',$this->pgdatabase);
      if(ALKIS)$GemkgListe=$Gemarkung->getGemarkungListeALKIS($GemListe['ID'],'','');
      else$GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','');
      $gbliste = $grundbuch->getGrundbuchbezirkslisteByGemkgIDs($GemkgListe['GemkgID']);
    }
    else{
      $gbliste = $grundbuch->getGrundbuchbezirksliste();
    }
    // Sortieren der Grundbuchbezirke unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($gbliste['bezeichnung'], $gbliste['schluessel']);
    $gbliste['schluessel'] = $sorted_arrays['second_array'];
    $sorted_arrays = umlaute_sortieren($gbliste['bezeichnung'], $gbliste['beides']);
    $gbliste['bezeichnung'] = $sorted_arrays['array'];
    $gbliste['beides'] = $sorted_arrays['second_array'];
    $this->gbliste = $gbliste;
		####### Import ###########
		$_files = $_FILES;
    if($_files['importliste']['name']){
			$importliste = file($_files['importliste']['tmp_name'], FILE_IGNORE_NEW_LINES);
			$this->formvars['selBlatt'] = implode(', ', $importliste);
			$this->formvars['Bezirk'] = substr($importliste[0], 0, 6);
		}
		##########################
    if($this->formvars['Bezirk'] != ''){
    	if($this->formvars['selBlatt'])$this->selblattliste = explode(', ',$this->formvars['selBlatt']);
    	$this->blattliste = $grundbuch->getGrundbuchblattliste($this->formvars['Bezirk']);
    }
    $this->output();
  }

  function grundbuchblattSuchen() {
  	$blaetter = explode(', ', $this->formvars['selBlatt']);
  	for($i = 0; $i < count($blaetter); $i++){
  		$blatt = explode('-', $blaetter[$i]);		# bezirk-blatt
	    # Prüfen der eingegebenen Parameter
	    $grundbuch=new grundbuch($blatt[0],$blatt[1],$this->pgdatabase);
	    $ret=$grundbuch->grundbuchblattSuchParameterPruefen();
	    if ($ret[0]) {
	      $this->Fehlermeldung='Angaben fehlerhaft:'.$ret[1];
	      $this->grundbuchblattWahl();
	    }
	    else {
	      # Suchparameter sind in Ordnung
	      # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
	      $ret=$grundbuch->getBuchungen('','','',1);
	      if ($ret[0]) {
	        # Fehler bei der Abfrage der Flurstücke des Grundbuchblattes
	        $this->Fehlermeldung=$ret[1];
	        $this->grundbuchblattWahl();
	      }
	      else {
	        $buchungen=$ret[1];
	        # Test ob Flurstücke gefunden wurden
	        $anzFlst=count($buchungen);
	        if ($anzFlst==0) {
	          # Wenn keine Flurstücke gefunden wurden
	          $this->Fehlermeldung.='Es konnten keine Flurstücke zu dem Grundbuchblatt '.$blatt[0].'-'.$blatt[1].' gefunden werden.<br>';
	          $this->grundbuchblattWahl();
	        }
	        else {
	          # Es wurden Flurstücke gefunden, ins Ergebnisarray aufnehmen
	          $gbblaetter[] = $buchungen;			
	        } # Ende mit Flurstücksanzeige
	      } # Ende mit Flurstücke erfolgreich abgefragt
	    } # Ende mit Suchparameter sind in Ordnung
  	}
  	$this->grundbuchblattanzeige($gbblaetter);
  }

  function grundbuchblattanzeige($gbblaetter) {
    $this->main='grundbuchblattanzeige.php';
    $this->titel='Buchungen zum Grundbuchblatt';
    $this->gbblaetter=$gbblaetter;
    $this->output();
  }

  function changemenue_with_ajax($id, $status){
    $this->changemenue($id, $status);
    if($status == 'on'){
      echo $this->Stelle->getsubmenues($id);
    }
  }

  function getMenueWithAjax() {
    $this->loadMap('DataBase');
    $this->drawMap();
    # erzeugen des Menueobjektes
    $this->Menue=new menue($this->user->rolle->language,$this->user->rolle->charset);
    # laden des Menues der Stelle und der Rolle
    $this->Menue->loadMenue($this->Stelle->id, $this->user->id);
    $this->Menue->get_menue_width($this->Stelle->id);
    $this->user->rolle->hideMenue(0);
    include(LAYOUTPATH."snippets/".$this->formvars['menuebodyfile']);
  }

  function hideMenueWithAjax() {
    $this->user->rolle->hideMenue(1);
  }

  function changemenue($id, $status){
    $sql ='SELECT status from u_menue2rolle WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id.' AND `menue_id` ='.$id;
    $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    if($rs[0] == 0 AND $status == 'on'){
      $sql ='UPDATE u_menue2rolle SET `status` = 1 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id.' AND `menue_id` ='.$id;
      $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    }
    elseif($rs[0] == 1 AND $status == 'off'){
      $sql ='UPDATE u_menue2rolle SET `status` = 0 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id.' AND `menue_id` ='.$id;
      $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    }
  }
  
  function changeLegendDisplay(){
  	$this->user->rolle->changeLegendDisplay($this->formvars['hide']);
  }

  function googlemaps(){
    $this->loadMap('DataBase');
    $onlineresource=URL.APPLVERSION.'index.php?go=OWS&request=getmap&service=wms&version=1.1.0';
    if ($this->mapDB->Layer[0]['aktivStatus']) {
      $layers =$this->mapDB->Layer[0]['Name'];
      $komma=1;
    }
    for($i = 1; $i<$this->mapDB->anzLayer; $i++){
      if($this->mapDB->Layer[$i]['aktivStatus']) {
        if($komma){
          $layers.=',';
        }
        $layers.=$this->mapDB->Layer[$i]['Name'];
        $komma=1;
      }
    }
    $projection = $this->map->getProjection();
    $projFROM = ms_newprojectionobj($projection);
    $projTO = ms_newprojectionobj("proj=latlong");
    $this->map->extent->project($projFROM, $projTO);
    $this->main='GoogleMaps.php';
    $this->titel='Google Maps Viewer';
    $this->googlelink = 'http://www.gdi-service.de/gmaps/google-wms-overlay.php?onlineresource='.$onlineresource.'&layers='.$layers.'&format=image/png&width='.$this->user->rolle->nImageWidth.'&height='.$this->user->rolle->nImageHeight.'&srs=EPSG:4326&bbox='.$this->map->extent->minx.','.$this->map->extent->miny.','.$this->map->extent->maxx.','.$this->map->extent->maxy;
    $this->output();
  }

  function PointEditor(){
    $this->main='PointEditor.php';
    $this->titel='Geometrie bearbeiten';
    # aktuellen Kartenausschnitt laden
    $this->loadMap('DataBase');
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $layerdb = $this->mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    $oldscale=round($this->map_scaledenom);
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
    }
    elseif($oldscale!=$this->formvars['nScale'] AND $this->formvars['nScale'] != '') {
      $this->scaleMap($this->formvars['nScale']);
    }
    elseif($this->formvars['oid'] != '') {
      $this->point = $pointeditor->getpoint($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
      if($this->point['pointx'] != ''){
        $this->formvars['loc_x']=$this->point['pointx'];
        $this->formvars['loc_y']=$this->point['pointy'];
        $rect = ms_newRectObj();
        $rect->minx = $this->point['pointx']-100;
        $rect->maxx = $this->point['pointx']+100;
        $rect->miny = $this->point['pointy']-100;
        $rect->maxy = $this->point['pointy']+100;
        $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
      	if (MAPSERVERVERSION > 600) {
					$this->map_scaledenom = $this->map->scaledenom;
				}
				else {
					$this->map_scaledenom = $this->map->scale;
				}
      }
    }
    $this->saveMap('');
    if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
    	$currenttime=date('Y-m-d H:i:s',time());
    	$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    }
    $this->drawMap();
    $this->output();
  }

  function PointEditor_Senden(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    # eingeabewerte pruefen:
    $ret = $pointeditor->pruefeEingabedaten($this->formvars['loc_x'], $this->formvars['loc_y']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->PointEditor();
      return;
    }
    else{
      $ret = $pointeditor->eintragenPunkt($this->formvars['loc_x'],$this->formvars['loc_y'], $this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->formvars['dimension']);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        showAlert('Eintrag erfolgreich!');
      }
      $this->PointEditor();
    }
  }

  function LineEditor(){
    $this->main='LineEditor.php';
    $this->titel='Geometrie bearbeiten';
    # aktuellen Kartenausschnitt laden
    $this->loadMap('DataBase');
    $layerdb = $this->mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
    $lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    $oldscale=round($this->map_scaledenom);
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveDrawmode($this->formvars['always_draw']);
    }
    elseif($oldscale!=$this->formvars['nScale'] AND $this->formvars['nScale'] != '') {
      $this->scaleMap($this->formvars['nScale']);
    }
    elseif($this->formvars['oid'] != '' AND $this->formvars['no_load'] != 'true'){
      # Linien abfragen
      $this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
      $this->lines = $lineeditor->getlines($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
      if($this->lines['wktgeom'] != ''){
        $this->formvars['newpathwkt'] = $this->lines['wktgeom'];
        $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
        $this->formvars['newpath'] = str_replace('-', '', $this->lines['svggeom']);
        $this->formvars['newpath'] = str_replace('L ', '', $this->formvars['newpath']);		# neuere Postgis-Versionen haben ein L mit drin
        $this->formvars['firstline'] = 'true';
        if($this->formvars['zoom'] != 'false'){
          $rect = $lineeditor->zoomToLine($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10);
          $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
        	if (MAPSERVERVERSION > 600) {
						$this->map_scaledenom = $this->map->scaledenom;
					}
					else {
						$this->map_scaledenom = $this->map->scale;
					}
        }
      }
    }
    # Spaltenname und from-where abfragen
    $data = $this->mapDB->getData($this->formvars['selected_layer_id']);
    $data_explosion = explode(' ', $data);
    $this->formvars['columnname'] = $data_explosion[0];
    $select = $this->mapDB->getSelectFromData($data);
    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
      $this->formvars['fromwhere'] .= ' where (1=1)';
    }

    $this->saveMap('');
    if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
    	$currenttime=date('Y-m-d H:i:s',time());
    	$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    }
    $this->drawMap();
    $this->output();
  }

  function LineEditor_Senden(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    # eingeabewerte pruefen:
    $ret = $lineeditor->pruefeEingabedaten($this->formvars['newpathwkt']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->LineEditor();
      return;
    }
    else{
      # wenn Time-Attribute vorhanden, aktuelle Zeit speichern      
      $this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
      for($i = 0; $i < count($this->attributes['type']); $i++){
        if($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Time'){
          $sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".date('Y-m-d G:i:s')."' WHERE oid = '".$this->formvars['oid']."'";
          $this->debug->write("<p>file:kvwmap :LineEditor_Senden :",4);
          $ret = $layerdb->execSQL($sql,4, 1);
        }
        elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Länge'){
          $sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->formvars['linelength']."' WHERE oid = '".$this->formvars['oid']."'";
          $this->debug->write("<p>file:kvwmap :LineEditor_Senden :",4);
          $ret = $layerdb->execSQL($sql,4, 1);
        }
        elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'User'){
          $sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->user->Vorname." ".$this->user->Name."' WHERE oid = '".$this->formvars['oid']."'";
          $this->debug->write("<p>file:kvwmap :LineEditor_Senden :",4);
          $ret = $layerdb->execSQL($sql,4, 1);
        }
      }
      $umring = $this->formvars['newpathwkt'];
      $ret = $lineeditor->eintragenLinie($umring, $this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $this->formvars['newpath']="";
        $this->formvars['newpathwkt']="";
        $this->formvars['pathwkt']="";
        $this->formvars['firstline']="";
        $this->formvars['secondline']="";
        $this->formvars['secondpoly']="";
        showAlert('Eintrag erfolgreich!');
      }
      $this->formvars['CMD'] = '';
      $this->LineEditor();
    }
  }

  function PolygonEditor(){
   	$this->main='PolygonEditor.php';
    $this->titel='Geometrie bearbeiten';
    # aktuellen Kartenausschnitt laden
    $this->loadMap('DataBase');
    $layerdb = $this->mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
    $polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    $oldscale=round($this->map_scaledenom);
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveDrawmode($this->formvars['always_draw']);
    }
    elseif($oldscale!=$this->formvars['nScale'] AND $this->formvars['nScale'] != '') {
      $this->scaleMap($this->formvars['nScale']);
    }
    elseif($this->formvars['oid'] != '' AND $this->formvars['no_load'] != 'true'){
      # Polygon abfragen
      $this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
      $this->polygon = $polygoneditor->getpolygon($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->map->extent);
      if($this->polygon['wktgeom'] != ''){
        $this->formvars['newpathwkt'] = $this->polygon['wktgeom'];
        $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
        $this->formvars['newpath'] = $this->polygon['svggeom'];
        $this->formvars['firstpoly'] = 'true';
        if($this->formvars['zoom'] != 'false'){
          $rect = $polygoneditor->zoomTopolygon($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10);
          $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
        	if (MAPSERVERVERSION > 600) {
						$this->map_scaledenom = $this->map->scaledenom;
					}
					else {
						$this->map_scaledenom = $this->map->scale;
					}
        }
      }
    }
    # Geometrie-Übernahme-Layer:
    # Spaltenname und from-where abfragen
    $data = $this->mapDB->getData($this->formvars['layer_id']);
    #echo $data;
    $data_explosion = explode(' ', $data);
    $this->formvars['columnname'] = $data_explosion[0];
    $select = $this->mapDB->getSelectFromData($data);
    
    # order by rausnehmen
  	$orderbyposition = strpos(strtolower($select), 'order by');
  	if($orderbyposition !== false){
	  	$select = substr($select, 0, $orderbyposition);
  	}
    
    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
      $this->formvars['fromwhere'] .= ' where (1=1)';
    }    
    if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
    	$currenttime=date('Y-m-d H:i:s',time());
    	$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    }
    $this->drawMap();
    $this->saveMap('');
    $this->output();
  }

  function PolygonEditor_Senden(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    # eingeabewerte pruefen:
    $ret = $polygoneditor->pruefeEingabedaten($this->formvars['newpathwkt']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->PolygonEditor();
      return;
    }
    else{
      # wenn Time-Attribute vorhanden, aktuelle Zeit speichern
      $this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
      for($i = 0; $i < count($this->attributes['type']); $i++){
        if($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Time'){
          $sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".date('Y-m-d G:i:s')."' WHERE oid = '".$this->formvars['oid']."'";
          $this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
          $ret = $layerdb->execSQL($sql,4, 1);
        }
        elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Fläche'){
          $sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->formvars['area']."' WHERE oid = '".$this->formvars['oid']."'";
          $this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
          $ret = $layerdb->execSQL($sql,4, 1);
        }
        elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'User'){
          $sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->user->Vorname." ".$this->user->Name."' WHERE oid = '".$this->formvars['oid']."'";
          $this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
          $ret = $layerdb->execSQL($sql,4, 1);
        }
      }
      $umring = $this->formvars['newpathwkt'];
      $ret = $polygoneditor->eintragenFlaeche($umring, $this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $this->formvars['newpath']="";
        $this->formvars['newpathwkt']="";
        $this->formvars['pathwkt']="";
        $this->formvars['firstpoly']="";
        $this->formvars['secondpoly']="";
        showAlert('Eintrag erfolgreich!');
      }
      $this->formvars['CMD'] = '';
      $this->PolygonEditor();
    }
  }

	function zoomto_selected_datasets(){
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
        $oids[] = $element[3];
      }
    }
    if($oids != ''){
      # Layer erzeugen
      $data = $dbmap->getData($this->formvars['chosen_layer_id']);
      $select = $dbmap->getSelectFromData($data);
      $orderbyposition = strpos(strtolower($select), 'order by');
      if($orderbyposition !== false){
      	$orderby = substr($select, $orderbyposition);
        $select = substr($select, 0, $orderbyposition);
      }
      if(strpos(strtolower($select), 'oid') === false){
      	$select = str_replace($this->formvars['layer_columnname'], 'oid, '.$this->formvars['layer_columnname'], $select);
      	$select = str_replace('*', '*, oid', $select);
      }
      if($this->formvars['klass_'.$this->formvars['chosen_layer_id']] != '' AND strpos($select, '*') === false AND strpos($select, $this->formvars['klass_'.$this->formvars['chosen_layer_id']]) === false){			# Attribut für automatische Klassifizierung mit ins data packen
      	$select = str_replace(' from ', ', '.$this->formvars['klass_'.$this->formvars['chosen_layer_id']].' from ', strtolower($select));
      }
      if(strpos(strtolower($select), ' where ') === false){
        $select .= " WHERE ";
      }
      else{
        $select .= " AND ";
      }
      $oid = 'oid';
      $explosion = explode(',', $select);							# wenn im Data sowas wie tabelle.oid vorkommt, soll das anstatt oid verwendet werden
      for($i = 0; $i < count($explosion); $i++){
      	if(strpos(strtolower($explosion[$i]), '.oid') !== false){
      		$oid = str_replace('select ', '', strtolower($explosion[$i]));
      		break;		
      	}
      }
      $select .= $oid." IN (";
      for($i = 0; $i < count($oids); $i++){
      	$select .= "'".$oids[$i]."',";
      }
      $select = substr($select, 0, -1);
      $select .= ")";
      $datastring = $this->formvars['layer_columnname']." from (".$select.' '.$orderby;
      $datastring.=") as foo using unique oid using srid=".$layerset[0]['epsg_code'];
      $legendentext = $layerset[0]['Name']." (".date('d.m. H:i',time()).")";

      $group = $dbmap->getGroupbyName('Suchergebnis');
      if($group != ''){
        $groupid = $group['id'];
      }
      else{
        $groupid = $dbmap->newGroup('Suchergebnis');
      }

      $this->formvars['user_id'] = $this->user->id;
      $this->formvars['stelle_id'] = $this->Stelle->id;
      $this->formvars['aktivStatus'] = 1;
      $this->formvars['Name'] = $legendentext;
      $this->formvars['Gruppe'] = $groupid;
      $this->formvars['Typ'] = 'search';
      $this->formvars['Datentyp'] = $layerset[0]['Datentyp'];;
      $this->formvars['Data'] = $datastring;
      $this->formvars['connectiontype'] = 6;
      $this->formvars['labelitem'] = $layerset[0]['labelitem'];
      $connectionstring ='user='.$layerdb->user;
      if($layerdb->passwd != ''){
        $connectionstring.=' password='.$layerdb->passwd;
      }
      $connectionstring.=' dbname='.$layerdb->dbName;
      if($layerdb->host != ''){
        $connectionstring.=' host='.$layerdb->host;
      }
      if($layerdb->port != ''){
        $connectionstring.=' port='.$layerdb->port;
      }
      $this->formvars['connection'] = $connectionstring;
      $this->formvars['epsg_code'] = $layerset[0]['epsg_code'];
      $this->formvars['transparency'] = 75;
      
      $layer_id = $dbmap->newRollenLayer($this->formvars);
      
      if($this->formvars['selektieren'] != 'true'){      # highlighten (gelb)
      	# ------------ automatische Klassifizierung -------------------
      	if($this->formvars['klass_'.$this->formvars['chosen_layer_id']] != ''){
      		$count = 0;				
      		$form_fields = explode('|', $this->formvars['form_field_names']);
      		for($i = 0; $i < count($form_fields); $i++){
			      if($form_fields[$i] != ''){
			        $element = explode(';', $form_fields[$i]);
		        	if($element[1] == $this->formvars['klass_'.$this->formvars['chosen_layer_id']] AND $element[3] == $oids[$count]){		# Test ob attributename == Klassifizierungsattribut und die oid im Array der selektierten oids vorkommt
		        		$values[] = $this->formvars[$form_fields[$i]];
		        		$count++;
		        	}
			      }
      		}
      		$dbmap->createAutoClasses(array_values(array_unique($values)), $this->formvars['klass_'.$this->formvars['chosen_layer_id']], $layer_id, $this->formvars['Datentyp'], $this->database);
      	}
      	# ------------ automatische Klassifizierung -------------------
      	else{
      		$color = $this->user->rolle->readcolor();
	        $classdata[0] = ' ';
	        $classdata[1] = -$layer_id;
	        $classdata[2] = '';
	        $classdata[3] = 0;
	        $class_id = $dbmap->new_Class($classdata);
	        if($this->formvars['Datentyp'] == 0){			# Punkt						
						if(defined('ZOOM2POINT_STYLE_ID') AND ZOOM2POINT_STYLE_ID != ''){
							$style_id = $dbmap->copyStyle(ZOOM2POINT_STYLE_ID);
						}
						else{
							# highlighten (mit der ausgewählten Farbe)
							$color = $this->user->rolle->readcolor();
							$style['colorred'] = $color['red'];
							$style['colorgreen'] = $color['green'];
							$style['colorblue'] = $color['blue'];
							$style['outlinecolorred'] = 0;
							$style['outlinecolorgreen'] = 0;
							$style['outlinecolorblue'] = 0;
							$style['size'] = 10;
							$style['symbolname'] = 'circle';
							$style['backgroundcolor'] = NULL;
							$style['minsize'] = NULL;
							$style['maxsize'] = 100000;
							if (MAPSERVERVERSION > '500') {
								$style['angle'] = 360;
							}
							$style_id = $dbmap->new_Style($style);
						}
	        }
	        else{
	        	$style['colorred'] = $color['red'];
		        $style['colorgreen'] = $color['green'];
		        $style['colorblue'] = $color['blue'];
		        $style['outlinecolorred'] = 0;
		        $style['outlinecolorgreen'] = 0;
		        $style['outlinecolorblue'] = 0;
		       	$style['size'] = 3;
		       	if($this->formvars['Datentyp'] == 1){		# Linie
		       		$style['symbol'] = 9;	
		       	}
		       	else{
		       		$style['symbol'] = NULL;
		       	}
		        $style['symbolname'] = NULL;
		        $style['backgroundcolor'] = NULL;
		        $style['minsize'] = 3;
		        $style['maxsize'] = 3;
		        if (MAPSERVERVERSION > '500') {
		        	$style['angle'] = 360;
		        }
		        $style_id = $dbmap->new_Style($style);
	        }
					$dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
      	}  
      }
      else{         # selektieren (eigenen Style verwenden)
        $class_id =  $dbmap->getClassFromObject($this->formvars['layer_tablename'], $this->formvars['oid'], $this->formvars['chosen_layer_id']);
        $dbmap->copyClass($class_id, -$layer_id);
        $this->user->rolle->setOneLayer($this->formvars['chosen_layer_id'], 0);
      }
      
      $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
      $this->loadMap('DataBase');
      # Polygon abfragen und Extent setzen
      $rect = $dbmap->zoomToDatasets($oids, $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10, $layerdb, $this->user->rolle->epsg_code);
      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }

    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
	}

  function zoom_toLine(){
    # aktuellen Kartenausschnitt laden
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    if($this->formvars['oid'] != ''){
      # Layer erzeugen
      $data = $dbmap->getData($this->formvars['layer_id']);
      $select = $dbmap->getSelectFromData($data);
      $orderbyposition = strpos(strtolower($select), 'order by');
      if($orderbyposition !== false){
        $select = substr($select, 0, $orderbyposition);
      }
      if(strpos(strtolower($select), 'oid') === false){
      	$select = str_replace('*', '*, oid', $select);
      	$select = str_replace($this->formvars['layer_columnname'], 'oid, '.$this->formvars['layer_columnname'], $select);
      }
      
      if(strpos(strtolower($select), ' where ') === false){
        $select .= " WHERE ";
      }
      else{
        $select .= " AND ";
      }
      $oid = 'oid';
      $explosion = explode(',', $select);							# wenn im Data sowas wie tabelle.oid vorkommt, soll das anstatt oid verwendet werden
      for($i = 0; $i < count($explosion); $i++){
      	if(strpos(strtolower($explosion[$i]), '.oid') !== false){
      		$oid = str_replace('select ', '', strtolower($explosion[$i]));
      		break;		
      	}
      }
      $select .= $oid." = '".$this->formvars['oid']."'";
      
      $datastring = $this->formvars['layer_columnname']." from (".$select;
      $datastring.=") as foo using unique oid using srid=".$layerset[0]['epsg_code'];
      $legendentext = $layerset[0]['Name']." (".date('d.m. H:i',time()).")";

      $group = $dbmap->getGroupbyName('Suchergebnis');
      if($group != ''){
        $groupid = $group['id'];
      }
      else{
        $groupid = $dbmap->newGroup('Suchergebnis');
      }

      $this->formvars['user_id'] = $this->user->id;
      $this->formvars['stelle_id'] = $this->Stelle->id;
      $this->formvars['aktivStatus'] = 1;
      $this->formvars['Name'] = $legendentext;
      $this->formvars['Gruppe'] = $groupid;
      $this->formvars['Typ'] = 'search';
      $this->formvars['Datentyp'] = 1;
      $this->formvars['Data'] = $datastring;
      $this->formvars['connectiontype'] = 6;
      $this->formvars['labelitem'] = $layerset[0]['labelitem'];
      $connectionstring ='user='.$layerdb->user;
      if($layerdb->passwd != ''){
        $connectionstring.=' password='.$layerdb->passwd;
      }
      $connectionstring.=' dbname='.$layerdb->dbName;
      if($layerdb->host != ''){
        $connectionstring.=' host='.$layerdb->host;
      }
    	if($layerdb->port != ''){
        $connectionstring.=' port='.$layerdb->port;
      }
      $this->formvars['connection'] = $connectionstring;
      $this->formvars['epsg_code'] = $layerset[0]['epsg_code'];
      $this->formvars['transparency'] = 60;

			$layer_id = $dbmap->newRollenLayer($this->formvars);

      if($this->formvars['selektieren'] == 'false'){      # highlighten (mit der ausgewählten Farbe)
      	$color = $this->user->rolle->readcolor();
        $classdata[0] = '';
        $classdata[1] = -$layer_id;
        $classdata[2] = '';
        $classdata[3] = 0;
        $class_id = $dbmap->new_Class($classdata);
        $this->formvars['class'] = $class_id;
        $style['colorred'] = $color['red'];
		    $style['colorgreen'] = $color['green'];
		    $style['colorblue'] = $color['blue'];
        $style['outlinecolorred'] = -1;
        $style['outlinecolorgreen'] = -1;
        $style['outlinecolorblue'] = -1;
        $style['size'] = 3;
        $style['symbol'] = 9;
        $style['symbolname'] = NULL;
        $style['backgroundcolor'] = NULL;
        $style['minsize'] = NULL;
        $style['maxsize'] = 3;
        if (MAPSERVERVERSION > '500') {
        	$style['angle'] = 360;
        }
        $style_id = $dbmap->new_Style($style);
        $dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
      }
      else{         # selektieren (eigenen Style verwenden)
        $class_id =  $dbmap->getClassFromObject($select, $this->formvars['layer_id']);
        $this->formvars['class'] = $dbmap->copyClass($class_id, -$layer_id);
        $this->user->rolle->setOneLayer($this->formvars['layer_id'], 0);
      }
      
      $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
      $this->loadMap('DataBase');
      # Linie abfragen und Extent setzen
      $rect = $lineeditor->zoomToLine($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10);
      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }

    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function zoom_toPolygon(){
    # aktuellen Kartenausschnitt laden
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    if($this->formvars['oid'] != ''){
    	if($this->formvars['selektieren'] != 'zoomonly'){
	      # Layer erzeugen
	      $data = $dbmap->getData($this->formvars['layer_id']);
	      $select = $dbmap->getSelectFromData($data);
	      $orderbyposition = strpos(strtolower($select), 'order by');
	      if($orderbyposition !== false){
	        $select = substr($select, 0, $orderbyposition);
	      }
	      if(strpos(strtolower($select), 'oid') === false){
					$select = str_replace('*', '*, oid', $select);
	      	$select = str_replace($this->formvars['layer_columnname'], 'oid, '.$this->formvars['layer_columnname'], $select);
	      }
	      
	      if(strpos(strtolower($select), ' where ') === false){
	        $select .= " WHERE ";
	      }
	      else{
	        $select .= " AND ";
	      }
	      $oid = 'oid';
	      $explosion = explode(',', $select);							# wenn im Data sowas wie tabelle.oid vorkommt, soll das anstatt oid verwendet werden
	      for($i = 0; $i < count($explosion); $i++){
	      	if(strpos(strtolower($explosion[$i]), '.oid') !== false){
	      			$oid = str_replace('select ', '', strtolower($explosion[$i]));
	      		break;		
	      	}
	      }
	      $select .= $oid." = '".$this->formvars['oid']."'";
	      
	      $datastring = $this->formvars['layer_columnname']." from (".$select;
	      $datastring.=") as foo using unique oid using srid=".$layerset[0]['epsg_code'];
	      $legendentext = $layerset[0]['Name']." (".date('d.m. H:i',time()).")";
	
	      $group = $dbmap->getGroupbyName('Suchergebnis');
	      if($group != ''){
	        $groupid = $group['id'];
	      }
	      else{
	        $groupid = $dbmap->newGroup('Suchergebnis');
	      }
	
	      $this->formvars['user_id'] = $this->user->id;
	      $this->formvars['stelle_id'] = $this->Stelle->id;
	      $this->formvars['aktivStatus'] = 1;
	      $this->formvars['Name'] = $legendentext;
	      $this->formvars['Gruppe'] = $groupid;
	      $this->formvars['Typ'] = 'search';
	      $this->formvars['Datentyp'] = 2;
	      $this->formvars['Data'] = $datastring;
	      $this->formvars['connectiontype'] = 6;
	      $this->formvars['labelitem'] = $layerset[0]['labelitem'];
	      $connectionstring ='user='.$layerdb->user;
	      if($layerdb->passwd != ''){
	        $connectionstring.=' password='.$layerdb->passwd;
	      }
	      $connectionstring.=' dbname='.$layerdb->dbName;
	      if($layerdb->host != ''){
	        $connectionstring.=' host='.$layerdb->host;
	      }
	      if($layerdb->port != ''){
	        $connectionstring.=' port='.$layerdb->port;
	      }
	      $this->formvars['connection'] = $connectionstring;
	      $this->formvars['epsg_code'] = $layerset[0]['epsg_code'];
	      $this->formvars['transparency'] = 60;
	      
	      $layer_id = $dbmap->newRollenLayer($this->formvars);
	      
	      if($this->formvars['selektieren'] == 'false'){      # highlighten (mit der ausgewählten Farbe)
	      	$color = $this->user->rolle->readcolor();
	        $classdata[0] = '';
	        $classdata[1] = -$layer_id;
	        $classdata[2] = '';
	        $classdata[3] = 0;
	        $class_id = $dbmap->new_Class($classdata);
	        $this->formvars['class'] = $class_id;
	        $style['colorred'] = $color['red'];
			    $style['colorgreen'] = $color['green'];
			    $style['colorblue'] = $color['blue'];
	        $style['outlinecolorred'] = 0;
	        $style['outlinecolorgreen'] = 0;
	        $style['outlinecolorblue'] = 0;
	        $style['size'] = 1;
	        $style['symbol'] = NULL;
	        $style['symbolname'] = NULL;
	        $style['backgroundcolor'] = NULL;
	        $style['minsize'] = NULL;
	        $style['maxsize'] = 100000;
	        if (MAPSERVERVERSION > '500') {
	        	$style['angle'] = 360;
	        }
	        $style_id = $dbmap->new_Style($style);
	        $dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
	      }
	      else{         # selektieren (eigenen Style verwenden)
	        //$class_id =  $dbmap->getClassFromObject($this->formvars['layer_tablename'], $this->formvars['oid'], $this->formvars['layer_id']);
	        $class_id =  $dbmap->getClassFromObject($select, $this->formvars['layer_id']);
	        $this->formvars['class'] = $dbmap->copyClass($class_id, -$layer_id);
	        $this->user->rolle->setOneLayer($this->formvars['layer_id'], 0);
	      } 
	      $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
    	}
      $this->loadMap('DataBase');
      # Polygon abfragen und Extent setzen
      $rect = $polygoneditor->zoomTopolygon($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10);
      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }

    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function zoom_toPoint(){
    # aktuellen Kartenausschnitt laden
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    if($this->formvars['oid'] != '') {
    	if($layerset[0]['schema'] != ''){
    		$this->formvars['layer_tablename'] = $layerset[0]['schema'].'.'.$this->formvars['layer_tablename'];
    	}
      $this->point = $pointeditor->getpoint($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
      $rect = ms_newRectObj();
      $rect->minx = $this->point['pointx']-100;
      $rect->maxx = $this->point['pointx']+100;
      $rect->miny = $this->point['pointy']-100;
      $rect->maxy = $this->point['pointy']+100;
      #---------- Punkt-Rollenlayer erzeugen --------#
      $datastring =$this->formvars['layer_columnname']." from (select oid, ".$this->formvars['layer_columnname']." from ".$this->formvars['layer_tablename'];
      $datastring.=" WHERE oid = '".$this->formvars['oid']."'";
      $datastring.=") as foo using unique oid using srid=".$layerset[0]['epsg_code'];
      $legendentext = $layerset[0]['Name']." (".date('d.m. H:i',time()).")";
      $group = $dbmap->getGroupbyName('Suchergebnis');
      if($group != ''){
        $groupid = $group['id'];
      }
      else{
        $groupid = $dbmap->newGroup('Suchergebnis');
      }
      $this->formvars['user_id'] = $this->user->id;
      $this->formvars['stelle_id'] = $this->Stelle->id;
      $this->formvars['aktivStatus'] = 1;
      $this->formvars['Name'] = $legendentext;
      $this->formvars['Gruppe'] = $groupid;
      $this->formvars['Typ'] = 'search';
      $this->formvars['Datentyp'] = 0;
      $this->formvars['Data'] = $datastring;
      $this->formvars['connectiontype'] = 6;
      $connectionstring ='user='.$layerdb->user;
      if($layerdb->passwd != ''){
        $connectionstring.=' password='.$layerdb->passwd;
      }
      $connectionstring.=' dbname='.$layerdb->dbName;
    	if($layerdb->host != ''){
        $connectionstring.=' host='.$layerdb->host;
      }
      if($layerdb->port != ''){
        $connectionstring.=' port='.$layerdb->port;
      }
      $this->formvars['connection'] = $connectionstring;
      $this->formvars['epsg_code'] = $layerset[0]['epsg_code'];
      $this->formvars['transparency'] = 60;

      $layer_id = $dbmap->newRollenLayer($this->formvars);
      
      $classdata[0] = '';
      $classdata[1] = -$layer_id;
      $classdata[2] = '';
      $classdata[3] = 0;
      $class_id = $dbmap->new_Class($classdata);

			if(defined('ZOOM2POINT_STYLE_ID') AND ZOOM2POINT_STYLE_ID != ''){
				$style_id = $dbmap->copyStyle(ZOOM2POINT_STYLE_ID);
			}
			else{
				# highlighten (mit der ausgewählten Farbe)
				$color = $this->user->rolle->readcolor();
				$style['colorred'] = $color['red'];
				$style['colorgreen'] = $color['green'];
				$style['colorblue'] = $color['blue'];
				$style['outlinecolorred'] = 0;
				$style['outlinecolorgreen'] = 0;
				$style['outlinecolorblue'] = 0;
				$style['size'] = 10;
				$style['symbolname'] = 'circle';
				$style['backgroundcolor'] = NULL;
				$style['minsize'] = NULL;
				$style['maxsize'] = 100000;
				if (MAPSERVERVERSION > '500') {
					$style['angle'] = 360;
				}
				$style_id = $dbmap->new_Style($style);
			}

      $dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
      $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
      $this->loadMap('DataBase');

      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function Anliegerbeiträge_editor(){
    $this->main='anliegerbeitraege_editor.php';
    $this->titel='Anliegerbeiträge';
    # aktuellen Kartenausschnitt laden + zeichnen!
    $this->loadMap('DataBase');
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveDrawmode($this->formvars['always_draw']);
    }
    $this->queryable_postgis_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);

  	if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
      $data = $this->mapDB->getData($this->formvars['layer_id']);
      $data_explosion = explode(' ', $data);
      $this->formvars['columnname'] = $data_explosion[0];
      $select = $this->mapDB->getSelectFromData($data);
      $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
      if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
        $this->formvars['fromwhere'] .= ' where (1=1)';
      }
    }
    else{
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }

    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function Anliegerbeiträge_strasse_speichern(){
    $anliegerbeitraege = new anliegerbeitraege($this->pgdatabase);
    $layerset = $this->user->rolle->getLayer('AB_Strassen');
    $anliegerbeitraege->layerepsg = $layerset[0]['epsg_code'];
    $anliegerbeitraege->clientepsg = $this->user->rolle->epsg_code;
    # eingeabewerte pruefen:
    $ret = $anliegerbeitraege->pruefeEingabedaten($this->formvars['newpathwkt']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->Anliegerbeiträge_editor();
      return;
    }
    else{
      $umring = $this->formvars['newpathwkt'];
      $ret = $anliegerbeitraege->eintragenNeueStrasse($umring);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $this->formvars['secondpoly']="true";
        showAlert('Eintrag erfolgreich!');
      }
      $this->Anliegerbeiträge_editor();
    }
  }

  function Anliegerbeiträge_buffer_speichern(){
    $anliegerbeitraege = new anliegerbeitraege($this->pgdatabase);
    $layerset = $this->user->rolle->getLayer('AB_Bereiche');
    $anliegerbeitraege->layerepsg = $layerset[0]['epsg_code'];
    $anliegerbeitraege->clientepsg = $this->user->rolle->epsg_code;
    # eingeabewerte pruefen:
    $ret = $anliegerbeitraege->pruefeEingabedaten($this->formvars['newpathwkt']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->Anliegerbeiträge_editor();
      return;
    }
    else{
      $umring = $this->formvars['newpathwkt'];
      $ret = $anliegerbeitraege->eintragenNeueBereiche($umring);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $this->formvars['secondpoly']="true";
        showAlert('Eintrag erfolgreich!');
      }
      $this->Anliegerbeiträge_editor();
    }
  }

  function jagdbezirk_show_data(){
    $jagdkataster = new jagdkataster($this->pgdatabase);
    $jagdkataster->clientepsg = $this->user->rolle->epsg_code;
    $jagdbezirk = $jagdkataster->getjagdbezirk($this->formvars['oid']);
    $this->qlayerset[0]['shape'][0] = $jagdbezirk;
    $layerset = $this->user->rolle->getLayer(LAYER_ID_JAGDBEZIRKE);
    $this->qlayerset[0]['Layer_ID'] = $layerset[0]['Layer_ID']; 
    $i = 0;
    $this->main='jagdbezirke.php';
    $this->output();
  }

  function zoomtojagdbezirk(){
    $jagdkataster = new jagdkataster($this->pgdatabase);
    $jagdkataster->clientepsg = $this->user->rolle->epsg_code;
    $rect = $jagdkataster->zoomTojagdbezirk($this->formvars['oid'], 10);
    $this->loadMap('DataBase');
    $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->output();
  }

  function jagdbezirke_auswaehlen_suchen_csv(){
  	$jagdkataster = new jagdkataster($this->pgdatabase);
    $this->jagdbezirke = $jagdkataster->suchen($this->formvars);
    $anz = count($this->jagdbezirke);
    for($i = 0; $i < $anz; $i++) {          	
    	if($this->jagdbezirke[$i]['art']=='ejb' OR $this->jagdbezirke[$i]['art']=='gjb'){
    		$csv.= "'".$this->jagdbezirke[$i]['id']."';";
    	}
    	else{
    		$csv.= "'".$this->jagdbezirke[$i]['jb_zuordnung']."';"; 
    	}
    	$csv.= $this->jagdbezirke[$i]['name'].';';
      $csv.= "'".$this->jagdbezirke[$i]['flaeche']."';";
      $csv.= $this->jagdbezirke[$i]['art'].';';
      $csv.= chr(10); 
    }
    $csv = 'lfd. Nummer;Name;Fläche;Typ'.chr(10).$csv;
    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
  
  function jagdbezirke_auswaehlen_suchen(){
    $jagdkataster = new jagdkataster($this->pgdatabase);
    $this->jagdbezirke = $jagdkataster->suchen($this->formvars);
    $this->jagdbezirke_auswaehlen();
  }

  function jagdbezirke_auswaehlen(){
    $this->main='jagdkatastersuchform.php';
    $this->titel='Jagdbezirke suchen';
    $this->output();
  }

  function jagdkatastereditor(){
    $this->main='jagdkatastereditor.php';
    $this->titel='Jagdbezirk anlegen';
    $this->loadMap('DataBase');
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
  	if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    # Spaltenname und from-where abfragen
    if($this->formvars['layer_id']){
	    $data = $this->mapDB->getData($this->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $this->formvars['columnname'] = $data_explosion[0];
	    $select = $this->mapDB->getSelectFromData($data);
	    
	    # order by rausnehmen
	  	$orderbyposition = strpos(strtolower($select), 'order by');
	  	if($orderbyposition !== false){
		  	$select = substr($select, 0, $orderbyposition);
	  	}
	    
	    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
	    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
	      $this->formvars['fromwhere'] .= ' where (1=1)';
	    }
    }
    $jagdkataster = new jagdkataster($this->pgdatabase);
    $layerset = $this->user->rolle->getLayer(LAYER_ID_JAGDBEZIRKE);
    $jagdkataster->layerepsg = $layerset[0]['epsg_code'];
    $jagdkataster->clientepsg = $this->user->rolle->epsg_code;
    if ($this->formvars['oid']!='') {           # Jagdbezirk bearbeiten
    	$this->titel='Jagdbezirk bearbeiten';
      $rect = $jagdkataster->zoomTojagdbezirk($this->formvars['oid'], 10);
      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
      $this->jagdbezirk = $jagdkataster->getjagdbezirk($this->formvars['oid']);
      $this->formvars['newpathwkt'] = $this->jagdbezirk['wktgeom'];
      $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
      $this->formvars['newpath'] = transformCoordsSVG($this->jagdbezirk['svggeom']);
      $this->formvars['firstpoly'] = 'true';
    }
    if($this->formvars['lfd_nr_name'] != ''){   # von der Namenssuche
      $jagdkataster->flurstgeometryWKT = $jagdkataster->getflurstgeometryfromnamen($this->formvars, 'wkt');
      $jagdkataster->flurstgeometrySVG = $jagdkataster->getflurstgeometryfromnamen($this->formvars, 'svg');
      $jagdkataster->flurstgeometrySVG = str_replace('-', '', $jagdkataster->flurstgeometrySVG);
      $jagdkataster->extent = $jagdkataster->getflurstBBox($jagdkataster->FlurstListe, $this->user->rolle->epsg_code);
      $randx=($jagdkataster->extent->maxx-$jagdkataster->extent->minx)* 20/100;
      $randy=($jagdkataster->extent->maxy-$jagdkataster->extent->miny)* 20/100;
      $this->map->setextent($jagdkataster->extent->minx-$randx,$jagdkataster->extent->miny-$randy,$jagdkataster->extent->maxx+$randx,$jagdkataster->extent->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
      $this->formvars['newpathwkt'] = $jagdkataster->flurstgeometryWKT;
      $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
      $this->formvars['newpath'] = $jagdkataster->flurstgeometrySVG;
      $this->formvars['firstpoly'] = 'true';
    }
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveDrawmode($this->formvars['always_draw']);
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }
	
  function jagdkatastereditor_senden(){
    $jagdkataster = new jagdkataster($this->pgdatabase);
    $layerset = $this->user->rolle->getLayer(LAYER_ID_JAGDBEZIRKE);
    $jagdkataster->layerepsg = $layerset[0]['epsg_code'];
    $jagdkataster->clientepsg = $this->user->rolle->epsg_code;
    # eingeabewerte pruefen:
    $ret = $jagdkataster->pruefeEingabedaten($this->formvars['newpathwkt'], $this->formvars['nummer']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->jagdkatastereditor();
      return;
    }
    else{
      $umring = $this->formvars['newpathwkt'];
      $ret = $jagdkataster->eintragenNeueFlaeche($umring, $this->formvars['nummer'], $this->formvars['name'], $this->formvars['art'], $this->formvars['area'], $this->formvars['jb_zuordnung'], $this->formvars['status'], $this->formvars['verzicht'], $this->formvars['oid']);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $this->formvars['newpath']="";
        $this->formvars['newpathwkt']="";
        $this->formvars['pathwkt']="";
        $this->formvars['firstpoly']="";
        $this->formvars['secondpoly']="";
        showAlert('Eintrag erfolgreich!');
      }
      $this->jagdkatastereditor();
    }
  }

  function jagdkatastereditor_loeschen(){
    $jagdkataster = new jagdkataster($this->pgdatabase);
    $jagdkataster->deletejagdbezirk($this->formvars['oid']);
    $this->loadMap('DataBase');
    $this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->saveMap('');
    $this->output();
  }

  function jagdkatastereditor_kopieren(){
    $jagdkataster = new jagdkataster($this->pgdatabase);
    $this->formvars['oid'] = $jagdkataster->copyjagdbezirk($this->formvars['oid']);
    $this->jagdkatastereditor();
  }

  function jagdkatastereditor_listflurst_csv(){
  	$this->jagdkataster = new jagdkataster($this->pgdatabase);
  	if(ALKIS){$this->flurstuecke = $this->jagdkataster->getIntersectedFlurstALKIS($this->formvars);}
    else{$this->flurstuecke = $this->jagdkataster->getIntersectedFlurst($this->formvars);}
  	for($i = 0; $i < count($this->flurstuecke); $i++){          	
    	$csv .= $this->flurstuecke[$i]['gemkgname'].';';
      $csv .= $this->flurstuecke[$i]['flur'].';';
      $csv .= $this->flurstuecke[$i]['zaehlernenner'].';';
      for($j=0; $j < count($this->flurstuecke[$i]['eigentuemer']); $j++){
      	$csv .= $this->flurstuecke[$i]['eigentuemer'][$j].'   ';
      }
      $csv .= ';';
      $csv .= $this->flurstuecke[$i]['albflaeche'].';';
			$csv .= str_replace('.', ',', round($this->flurstuecke[$i]['schnittflaeche'], 2)).';';
      $csv .= str_replace('.', ',', $this->flurstuecke[$i]['anteil']).';';
     	$csv .= chr(10);  
    }
    $csv = 'Gemarkung;Flur;Zähler/Nenner;Eigentümer;Flst-Fläche(ALB);Anteil m²;Anteil %'.chr(10).$csv;
    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
  
  function jagdkatastereditor_listflurst(){
    $this->main='jagdkataster_flurstlist.php';
    if($this->formvars['oid'])$this->titel='Im Jagdbezirk '.$this->formvars['name'].' enthaltene Flurstücke';
    else $this->titel='Enthaltene Flurstücke in Jagdbezirken';
    $this->jagdkataster = new jagdkataster($this->pgdatabase);
    if(ALKIS){$this->flurstuecke = $this->jagdkataster->getIntersectedFlurstALKIS($this->formvars);}
    else{$this->flurstuecke = $this->jagdkataster->getIntersectedFlurst($this->formvars);}
    $this->output();
  }
  
	function jagdkatastereditor_listeigentuemer(){
    $this->main='jagdkataster_eigentuemerlist.php';
    $this->titel='Eigentümer im Jagdbezirk '.$this->formvars['name'];
    $this->jagdkataster = new jagdkataster($this->pgdatabase);
    if(ALKIS){$this->eigentuemer = $this->jagdkataster->getEigentuemerListeALKIS($this->formvars);}
    else{$this->eigentuemer = $this->jagdkataster->getEigentuemerListe($this->formvars);}
    $this->output();
  }
  
  function jagdkatastereditor_listpaechter(){
    $this->main='jagdkataster_paechterlist.php';
    $this->titel='P&auml;chter im Jagdbezirk '.$this->formvars['name'].'';
    $jagdkataster = new jagdkataster($this->pgdatabase);
    $this->paechter = $jagdkataster->get_paechter($this->formvars['oid']);
    $this->output();
  }

  function bauleitplanung(){
    $this->main='bauleitplanungsaenderung.php';
    $this->titel='Änderung in der Bauleitplanung';
    # aktuellen Kartenausschnitt laden + zeichnen!
    $this->loadMap('DataBase');
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function bauleitplanungSenden() {
    $bplanung = new bauleitplanung($this->pgdatabase);
    # eingeabewerte pruefen:
      $ret = $bplanung->pruefeEingabedaten($this->formvars['newpathwkt'],$this->formvars['email'], $this->formvars['user']);
      if ($ret[0]) { # fehlerhafte eingabedaten
        $this->Meldung=$ret[1];
        $this->bauleitplanung();
        return;
      }
      else { # eintraege gueltig
        $this->Meldung='';
        # umring generieren:
        $umring = $this->formvars['newpathwkt'];
        $datum = date('Y-m-d H:i:s',time());
        $ret = $bplanung->eintragenNeueFlaeche($umring, $this->formvars['user'], $this->formvars['hinweis'], $this->formvars['bemerkung'], $datum);
        if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
        }
        else { # eintrag erfolgreich
          mail($this->formvars['email'], 'Bauleitplanungänderung', 'Der Nutzer '.$this->formvars['user'].' hat am '.$datum.' eine Änderung am B-Plan mit der Nummer '.$this->formvars['bplannumber'].' vorgenommen. Bemerkung:'.$this->formvars['bemerkung'].'.', 'From: mail@kvwmap.de');
          $this->formvars['newpath']="";
          $this->formvars['newpathwkt']="";
          $this->formvars['pathwkt']="";
          $this->formvars['firstpoly']="";
          $this->formvars['secondpoly']="";
          showAlert('Eintrag erfolgreich!');
        }
      }
    $this->bauleitplanung();
  }

  function bauleitplanungLoeschen() {
    $bplanung = new bauleitplanung($this->pgdatabase);
    $loeschdatum = date('Y-m-d H:i:s',time());
    $bplanung->FlaecheLoeschen($this->formvars['id'], $this->user->Name, $loeschdatum);
  }

  function haltestellenSuche() {
    $this->main='haltestellensuche.php';
    $this->titel='Haltestellensuche';
    if ($this->formvars['defaultAddress'] == '') {
	  echo $this->formvars['defaultAddress'];
	  $this->formvars['defaultAddress']='hier eine Adresse eingeben';
	}
  }
  
  function bauauskunftSuche() {
    $this->bau = new Bauauskunft($this->baudatabase);
    $this->bau->readvorhaben();
    $this->bau->readverfahrensart();
    $this->bau->readaktualitaet();

    # Abfragen für welche Gemeinden die Stelle Zugriffsrechte hat
    # GemeindenStelle wird eine Liste mit ID´s der Gemeinden zugewiesen, die zur Stelle gehören
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$this->pgdatabase);
    # Abfrage der Gemeinde Namen
    if(ALKIS)$GemListe=$Gemeinde->getGemeindeListeALKIS($GemeindenStelle, 'bezeichnung');
    else $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');
    # Abfragen der Gemarkungen zur Gemeinde
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    if(ALKIS)$this->GemkgListe=$Gemarkung->getGemarkungListeALKIS($GemListe['ID'],'','gmk.bezeichnung');
    else $this->GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
    $this->main='bauauskunftsuche.php';
    $this->titel='Bauauskunftsuche';
  }

  function bauauskunftSucheSenden($flurstkennz) {
    $this->bau = new Bauauskunft($this->baudatabase);
    if($this->formvars['flurstkennz'] != ''){
      $this->formvars['flurstkennz'] = $flurstkennz;
    }
    if($this->bau->checkformdaten($this->formvars)){
      if(!$this->formvars['anzahl']){
        $this->formvars['anzahl'] = $this->bau->countbaudaten($this->formvars);
      }
      $searchvars  = $this->bau->getbaudaten($this->formvars);
      $this->formvars['gemarkung'] = $searchvars['gemarkung'];
      $this->formvars['flur'] = $searchvars['flur'];
      $this->formvars['flurstueck'] = $searchvars['flurstueck'];

      for($i = 0; $i < count($this->bau->baudata); $i++){
        $gemarkungs_searchvars['jahr'] = $this->bau->baudata[$i]['feld1'];
        $gemarkungs_searchvars['obergruppe'] = $this->bau->baudata[$i]['feld2'];
        $gemarkungs_searchvars['nummer'] = $this->bau->baudata[$i]['feld3'];
        $baudata = $this->bau->getbaudaten2($gemarkungs_searchvars);
        $Gemarkung=new gemarkung('13'.$baudata[0]['feld12'],$this->pgdatabase);
        $this->bau->baudata[$i]['bauort'] = $Gemarkung->getGemkgName();
      }
      $this->main='bauauskunftsuchergebnis.php';
      $this->titel='Suchergebnis';
    }
    else{
      $this->main='bauauskunftsuche.php';
      $this->titel='Bauauskunftsuche';
    }
  }

  function bauauskunftanzeige() {
    $this->bau = new Bauauskunft($this->baudatabase);
    $this->bau->getbaudaten($this->formvars);
    for($i = 0; $i < count($this->bau->baudata); $i++){
      $this->bau->grundstueck[] = '13'.$this->bau->baudata[$i]['feld12'].'-'.$this->bau->baudata[$i]['feld13'].'-'.$this->bau->baudata[$i]['feld14'];
    }
    $Gemarkung=new gemarkung($this->bau->baudata[0]['feld12'],$this->pgdatabase);
    $this->bau->baudata[0]['bauort'] = $Gemarkung->getGemkgName();
    $this->main='bauauskunftanzeige.php';
    $this->titel='Baudatenanzeige';
  }

  function druckrahmen_init() {
    $Document=new Document($this->database);
    $this->Document=$Document;
  }

  function druckrahmen_load(){
    if(IMAGEMAGICK == 'true'){
      $this->druckrahmen_load_pdf();
    }
    else{
      $this->druckrahmen_load_html();
    }
  }

  function druckrahmen_load_pdf(){
    $this->Document->frames = $this->Document->load_frames(NULL, NULL);
    $frameid = $this->Document->get_active_frameid($this->user->id, $this->Stelle->id);
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    if(!$this->formvars['aktiverRahmen']){
      $this->formvars['aktiverRahmen'] = $frameid;
    }
    $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);
    $this->Document->selectedframe = $this->Document->load_frames(NULL, $this->formvars['aktiverRahmen']);
    if($this->Document->selectedframe != NULL){
      $ratio = $this->Document->selectedframe[0]['mapwidth']/$this->Document->selectedframe[0]['mapheight'];
      $this->formvars['worldprintwidth'] = $this->Document->selectedframe[0]['mapwidth'] * $this->formvars['printscale'] * 0.0003526;
      $this->formvars['worldprintheight'] = $this->Document->selectedframe[0]['mapheight'] * $this->formvars['printscale'] * 0.0003526;
			$this->formvars['map_factor'] = 1;
      $this->previewfile = $this->createMapPDF($this->formvars['aktiverRahmen'], true, true);

      # Fonts auslesen
      $this->document->fonts = searchdir(PDFCLASSPATH.'fonts/', true);

      $this->Document->cent = $this->Document->selectedframe[0]['preis']%100;
      $this->Document->euro = ($this->Document->selectedframe[0]['preis'] - $this->Document->cent)/100;
      $this->Document->cent = str_pad ($this->Document->cent, 2, "0", STR_PAD_LEFT);

      for($i = 0; $i < count($this->Document->selectedframe[0]['texts']); $i++){
        $this->Document->selectedframe[0]['texts'][$i]['text'] = str_replace(';', chr(10), $this->Document->selectedframe[0]['texts'][$i]['text']);
      }
    }
    $this->main='druckrahmen.php';
    $this->titel='Druckrahmenverwaltung';
  }

  function druckrahmen_load_html() {
    $this->Document->frames = $this->Document->load_frames(NULL, NULL);
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    $frameid = $this->Document->get_active_frameid($this->user->id, $this->Stelle->id);
    if(!$this->formvars['aktiverRahmen']){
      $this->formvars['aktiverRahmen'] = $frameid;
    }
    $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);
    $this->Document->selectedframe = $this->Document->load_frames(NULL, $this->formvars['aktiverRahmen']);
    if($this->Document->selectedframe != NULL){
      # Fonts auslesen
      $this->document->fonts = searchdir(PDFCLASSPATH.'fonts/', true);

      if($this->Document->selectedframe[0]['headsrc'] != '' && file_exists(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['headsrc']))){
        $this->Document->headsize = GetImageSize(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['headsrc']));
      }
      else{
        $this->Document->headsize[0] = 1;
        $this->Document->headsize[1] = 1;
      }
      if($this->Document->selectedframe[0]['refmapsrc'] != '' && file_exists(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['refmapsrc']))){
        $this->Document->refmapsize = GetImageSize(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['refmapsrc']));
      }
      else{
        $this->Document->refmapsize[0] = 1;
        $this->Document->refmapsize[1] = 1;
      }
      $this->Document->cent = $this->Document->selectedframe[0]['preis']%100;
      $this->Document->euro = ($this->Document->selectedframe[0]['preis'] - $this->Document->cent)/100;
      $this->Document->cent = str_pad ($this->Document->cent, 2, "0", STR_PAD_LEFT);

      for($i = 0; $i < count($this->Document->selectedframe[0]['texts']); $i++){
        $this->Document->selectedframe[0]['texts'][$i]['text'] = str_replace(';', chr(10), $this->Document->selectedframe[0]['texts'][$i]['text']);
      }

      switch ($this->Document->selectedframe[0]['format']){
      	case 'A5hoch' : {
          $ratio = 420/595;
          $height = 595;
        } break;
        case 'A5quer' : {
          $ratio = 595/595;
          $height = 420/$ratio;
        } break;
        case 'A4hoch' : {
          $ratio = 595/595;
          $height = 842;
        } break;
        case 'A4quer' : {
          $ratio = 842/595;
          $height = 595/$ratio;
        } break;
        case 'A3hoch' : {
          $ratio = 842/595;
          $height = 842;
        } break;
        case 'A3quer' : {
          $ratio = 1191/595;
          $height = 842/$ratio;
        } break;
        case 'A2hoch' : {
          $ratio = 1191/595;
          $height = 1684/$ratio;
        } break;
        case 'A2quer' : {
          $ratio = 1684/595;
          $height = 1191/$ratio;
        } break;
        case 'A1hoch' : {
          $ratio = 1684/595;
          $height = 2384/$ratio;
        } break;
        case 'A1quer' : {
          $ratio = 2384/595;
          $height = 1684/$ratio;
        } break;
        case 'A0hoch' : {
          $ratio = 2384/595;
          $height = 3370/$ratio;
        } break;
        case 'A0quer' : {
          $ratio = 3370/595;
          $height = 2384/$ratio;
        } break;
      }
      $this->Document->headposx = $this->Document->selectedframe[0]['headposx']/$ratio;
      $this->Document->headposy = $this->Document->selectedframe[0]['headposy']/$ratio;
      $this->Document->headwidth = $this->Document->selectedframe[0]['headwidth']/$ratio;
      $this->Document->headheight = $this->Document->selectedframe[0]['headheight']/$ratio;
      $this->Document->mapposx = $this->Document->selectedframe[0]['mapposx']/$ratio;
      $this->Document->mapposy = $this->Document->selectedframe[0]['mapposy']/$ratio;
      $this->Document->mapwidth = $this->Document->selectedframe[0]['mapwidth']/$ratio;
      $this->Document->mapheight = $this->Document->selectedframe[0]['mapheight']/$ratio;
      $this->Document->refmapposx = $this->Document->selectedframe[0]['refmapposx']/$ratio;
      $this->Document->refmapposy = $this->Document->selectedframe[0]['refmapposy']/$ratio;
      $this->Document->refmapwidth = $this->Document->selectedframe[0]['refmapwidth']/$ratio;
      $this->Document->refmapheight = $this->Document->selectedframe[0]['refmapheight']/$ratio;
      $this->Document->refposx = $this->Document->selectedframe[0]['refposx']/$ratio;
      $this->Document->refposy = $this->Document->selectedframe[0]['refposy']/$ratio;
      $this->Document->refwidth = $this->Document->selectedframe[0]['refwidth']/$ratio;
      $this->Document->refheight = $this->Document->selectedframe[0]['refheight']/$ratio;
      $this->Document->dateposx = $this->Document->selectedframe[0]['dateposx']/$ratio;
      $this->Document->dateposy = $this->Document->selectedframe[0]['dateposy']/$ratio;
      $this->Document->datesize = $this->Document->selectedframe[0]['datesize']/$ratio;
      $this->Document->dateposy = $this->Document->dateposy - $this->Document->datesize/4;
      $this->Document->scaleposx = $this->Document->selectedframe[0]['scaleposx']/$ratio;
      $this->Document->scaleposy = $this->Document->selectedframe[0]['scaleposy']/$ratio;
      $this->Document->scalesize = $this->Document->selectedframe[0]['scalesize']/$ratio;
      $this->Document->scaleposy = $this->Document->scaleposy - $this->Document->scalesize/4;
      $this->Document->oscaleposx = $this->Document->selectedframe[0]['oscaleposx']/$ratio;
      $this->Document->oscaleposy = $this->Document->selectedframe[0]['oscaleposy']/$ratio;
      $this->Document->oscalesize = $this->Document->selectedframe[0]['oscalesize']/$ratio;
      $this->Document->oscaleposy = $this->Document->oscaleposy - $this->Document->oscalesize/4;
      $this->Document->gemarkungposx = $this->Document->selectedframe[0]['gemarkungposx']/$ratio;
      $this->Document->gemarkungposy = $this->Document->selectedframe[0]['gemarkungposy']/$ratio;
      $this->Document->gemarkungsize = $this->Document->selectedframe[0]['gemarkungsize']/$ratio;
      $this->Document->gemarkungposy = $this->Document->gemarkungposy - $this->Document->gemarkungsize/4;
      $this->Document->flurposx = $this->Document->selectedframe[0]['flurposx']/$ratio;
      $this->Document->flurposy = $this->Document->selectedframe[0]['flurposy']/$ratio;
      $this->Document->flursize = $this->Document->selectedframe[0]['flursize']/$ratio;
      $this->Document->flurposy = $this->Document->flurposy - $this->Document->flursize/4;
      $this->Document->userposx = $this->Document->selectedframe[0]['userposx']/$ratio;
      $this->Document->userposy = $this->Document->selectedframe[0]['userposy']/$ratio;
      $this->Document->usersize = $this->Document->selectedframe[0]['usersize']/$ratio;
      $this->Document->userposy = $this->Document->userposy - $this->Document->usersize/4;

      for($i = 0; $i < count($this->Document->selectedframe[0]['texts']); $i++){
        $this->Document->textposx[$i] = $this->Document->selectedframe[0]['texts'][$i]['posx']/$ratio;
        $this->Document->textposy[$i] = $this->Document->selectedframe[0]['texts'][$i]['posy']/$ratio;
        $this->Document->textsize[$i] = $this->Document->selectedframe[0]['texts'][$i]['size']/$ratio;
        $this->Document->textposy[$i] = $this->Document->textposy[$i] - $this->Document->textsize[$i]/4;
      }

      $this->Document->legendposx = $this->Document->selectedframe[0]['legendposx']/$ratio;
      $this->Document->legendposy = $this->Document->selectedframe[0]['legendposy']/$ratio;
      $this->Document->legendsize = $this->Document->selectedframe[0]['legendsize']/$ratio;
      $this->Document->legendwidth = $this->Document->legendsize * 13;
      $this->Document->legendheight = $this->Document->legendwidth * 2;

      $this->Document->height = $height;
    }

    $this->main='druckrahmen_html.php';
    $this->titel='Druckrahmenverwaltung';
  }
  
  function metadaten_uebersicht(){
  	# Abfragen der Layer
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    if($this->formvars['order'] == ''){
      $this->formvars['order'] = 'Name';
    }
    $this->layerdaten = $this->Stelle->getLayers(NULL, $this->formvars['order']);
    $this->titel='Metadaten Erfassen/Bearbeiten';
    $this->main='metadaten_layer.php';
    $this->output();
  }
  
  function metadaten_suche(){
    $this->titel='Metadaten Recherchieren';
    $this->main='metadaten_search.php';
    $this->output();
  }
  
  function metadaten_generieren($layer_id){
  	$md = new metadata_csw($this->database);
  	$md->make_xml($layer_id);
  	return $md->create_csw_insert();
  }

  function metadatenSuchForm() {
    if ($this->formvars['expertensuche']) {
      $this->titel='Metadaten Expertensuche';
      $this->main='metadatensuchformular.php';
    }
    else {
      $this->titel='Metadatensuche';
      $this->main='metadatensuchformular.php';
    }
    $this->loadMap('DataBase');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function druckausschnittswahl($loadmapsource){
    $this->titel='Druckausschnitt wählen';
    $this->main="druckausschnittswahl.php";
    # aktuellen Kartenausschnitt laden + zeichnen!
  	if($this->formvars['neuladen']){
      $this->changeMap();
    }
    else{
      $this->loadMap($loadmapsource);
    }
    #echo '<br>Karte geladen: ';
    # aktuellen Druckkopf laden
    $this->Document=new Document($this->database);
    if($this->formvars['angle'] == ''){
      $this->formvars['angle'] = 0;
    }
    if($this->formvars['aktiverRahmen']){
      $this->Document->save_active_frame($this->formvars['aktiverRahmen'], $this->user->id, $this->Stelle->id);
    }
    $frameid = $this->Document->get_active_frameid($this->user->id, $this->Stelle->id);
    $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);
    $this->Document->frames = $this->Document->load_frames($this->Stelle->id, NULL);
    #echo '<br>Druckrahmen geladen.';

    # alle Druckausschnitte der Rolle laden
    $this->Document->ausschnitte = $this->Document->load_ausschnitte($this->Stelle->id, $this->user->id, NULL);
    # wenn Druckausschnitts-ID übergeben, Ausschnitt laden
    if($this->formvars['druckausschnitt'] != ''){
      $this->Document->ausschnitt = $this->Document->load_ausschnitte($this->Stelle->id, $this->user->id, $this->formvars['druckausschnitt']);
      # Druckrahmen setzen
      $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $this->Document->ausschnitt[0]['frame_id']);
      # Extent setzen
      $width = $this->Document->activeframe[0]['mapwidth'] * $this->Document->ausschnitt[0]['print_scale'] * 0.00035277;
      $height = $this->Document->activeframe[0]['mapheight'] * $this->Document->ausschnitt[0]['print_scale'] * 0.00035277;
      $rect= ms_newRectObj();
      $rect->minx = $this->Document->ausschnitt[0]['center_x'] - $width/2;
      $rect->miny = $this->Document->ausschnitt[0]['center_y'] - $height/2;
      $rect->maxx = $this->Document->ausschnitt[0]['center_x'] + $width/2;
      $rect->maxy = $this->Document->ausschnitt[0]['center_y'] + $height/2;
      $rand = 10;
      $this->map->setextent($rect->minx-$rand,$rect->miny-$rand,$rect->maxx+$rand,$rect->maxy+$rand);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
      # Position setzen
      $this->formvars['center_x'] = $this->Document->ausschnitt[0]['center_x'];
      $this->formvars['center_y'] = $this->Document->ausschnitt[0]['center_y']; 
      # Druckmaßstab setzen
      $this->formvars['printscale'] = $this->Document->ausschnitt[0]['print_scale'];
      # Drehwinkel setzen
      $this->formvars['angle'] = $this->Document->ausschnitt[0]['angle'];
    }

    # Wenn Navigiert werden soll, wird eine eventuell schon gesetzte Position
    # in Weltkoordinaten umgerechnet und danach wieder zurück.
    if ($this->formvars['CMD']!='') {
    	$this->navMap($this->formvars['CMD']);
      $this->saveMap('');
      $currenttime=date('Y-m-d H:i:s',time());
      $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
      $this->drawMap();
    }
    else {
      $this->saveMap('');
      $this->drawMap();
    }
    $this->output();
  }

  function druckausschnitt_löschen($loadmapsource){
    $this->Document = new Document($this->database);
    $this->Document->delete_ausschnitt($this->Stelle->id, $this->user->id, $this->formvars['druckausschnitt']);
    $this->formvars['druckausschnitt'] = '';
    $this->druckausschnittswahl($loadmapsource);
  }

  function druckausschnitt_speichern($loadmapsource){
    $this->loadMap($loadmapsource);
    $this->Document = new Document($this->database);
    $this->Document->save_ausschnitt($this->Stelle->id, $this->user->id, $this->formvars['name'], $this->formvars['center_x'], $this->formvars['center_y'], $this->formvars['printscale'], $this->formvars['angle'], $this->formvars['aktiverRahmen']);
    $this->druckausschnittswahl($loadmapsource);
  }

  function druckvorschau(){
    $this->previewfile = $this->createMapPDF($this->formvars['aktiverRahmen'], true);
    $this->main = 'druckvorschau.php';
    $this->titel = 'Druckvorschau';
  }

  function druckvorschau_html(){
    $this->main = 'druckvorschau_html.php';
    $this->titel = 'Druckvorschau';
    $Document=new Document($this->database);
    $this->Document=$Document;
    $frameid = $this->Document->get_active_frameid($this->user->id, $this->Stelle->id);
    $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);

    # Text für die html-Vorschau
    $this->Document->text = str_replace(';', '<br>', $this->Document->activeframe[0]['text']);

    if($this->formvars['vorschauzoom'] == ''){
      $this->formvars['vorschauzoom'] = 1;
    }
    $zoom = $this->formvars['vorschauzoom'];

    switch ($this->Document->activeframe[0]['format']){
    	case 'A5hoch' : {
        $ratio = 420/595/$zoom;
        $height = 595/$ratio;
      } break;
      case 'A5quer' : {
        $ratio = 595/595/$zoom;
        $height = 420/$ratio;
      } break;
      case 'A4hoch' : {
        $ratio = 595/595/$zoom;
        $height = 842/$ratio;
      } break;
      case 'A4quer' : {
        $ratio = 842/595/$zoom;
        $height = 595/$ratio;
      } break;
      case 'A3hoch' : {
        $ratio = 842/595/$zoom;
        $height = 842/$ratio;
      } break;
      case 'A3quer' : {
        $ratio = 1191/595/$zoom;
        $height = 842/$ratio;
      } break;
      case 'A2hoch' : {
        $ratio = 1191/595/$zoom;
        $height = 1684/$ratio;
      } break;
      case 'A2quer' : {
        $ratio = 1684/595/$zoom;
        $height = 1191/$ratio;
      } break;
      case 'A1hoch' : {
        $ratio = 1684/595/$zoom;
        $height = 2384/$ratio;
      } break;
      case 'A1quer' : {
        $ratio = 2384/595/$zoom;
        $height = 1684/$ratio;
      } break;
      case 'A0hoch' : {
        $ratio = 2384/595/$zoom;
        $height = 3370/$ratio;
      } break;
      case 'A0quer' : {
        $ratio = 3370/595/$zoom;
        $height = 2384/$ratio;
      } break;
    }
    $this->Document->width = 595*$zoom;
    $this->Document->headposx = $this->Document->activeframe[0]['headposx']/$ratio;
    $this->Document->headposy = $this->Document->activeframe[0]['headposy']/$ratio;
    $this->Document->headwidth = $this->Document->activeframe[0]['headwidth']/$ratio;
    $this->Document->headheight = $this->Document->activeframe[0]['headheight']/$ratio;
    $this->Document->mapposx = $this->Document->activeframe[0]['mapposx']/$ratio;
    $this->Document->mapposy = $this->Document->activeframe[0]['mapposy']/$ratio;
    $this->Document->mapwidth = $this->Document->activeframe[0]['mapwidth']/$ratio;
    $this->Document->mapheight = $this->Document->activeframe[0]['mapheight']/$ratio;
    $this->Document->refmapposx = $this->Document->activeframe[0]['refmapposx']/$ratio;
    $this->Document->refmapposy = $this->Document->activeframe[0]['refmapposy']/$ratio;
    $this->Document->refmapwidth = $this->Document->activeframe[0]['refmapwidth']/$ratio;
    $this->Document->refmapheight = $this->Document->activeframe[0]['refmapheight']/$ratio;
    $this->Document->refposx = $this->Document->activeframe[0]['refposx']/$ratio;
    $this->Document->refposy = $this->Document->activeframe[0]['refposy']/$ratio;
    $this->Document->refwidth = $this->Document->activeframe[0]['refwidth']/$ratio;
    $this->Document->refheight = $this->Document->activeframe[0]['refheight']/$ratio;
    $this->Document->dateposx = $this->Document->activeframe[0]['dateposx']/$ratio;
    $this->Document->dateposy = $this->Document->activeframe[0]['dateposy']/$ratio;
    $this->Document->datesize = $this->Document->activeframe[0]['datesize']/$ratio;
    $this->Document->dateposy = $this->Document->dateposy - $this->Document->datesize/4;
    $this->Document->scaleposx = $this->Document->activeframe[0]['scaleposx']/$ratio;
    $this->Document->scaleposy = $this->Document->activeframe[0]['scaleposy']/$ratio;
    $this->Document->scalesize = round($this->Document->activeframe[0]['scalesize']/$ratio);
    $this->Document->scaleposy = $this->Document->scaleposy - $this->Document->scalesize/4;
    $this->Document->oscaleposx = $this->Document->activeframe[0]['oscaleposx']/$ratio;
    $this->Document->oscaleposy = $this->Document->activeframe[0]['oscaleposy']/$ratio;
    $this->Document->oscalesize = $this->Document->activeframe[0]['oscalesize']/$ratio;
    $this->Document->oscaleposy = $this->Document->oscaleposy - $this->Document->oscalesize/4;
    $this->Document->gemarkungposx = $this->Document->activeframe[0]['gemarkungposx']/$ratio;
    $this->Document->gemarkungposy = $this->Document->activeframe[0]['gemarkungposy']/$ratio;
    $this->Document->gemarkungsize = $this->Document->activeframe[0]['gemarkungsize']/$ratio;
    $this->Document->gemarkungposy = $this->Document->gemarkungposy - $this->Document->gemarkungsize/4;
    $this->Document->flurposx = $this->Document->activeframe[0]['flurposx']/$ratio;
    $this->Document->flurposy = $this->Document->activeframe[0]['flurposy']/$ratio;
    $this->Document->flursize = $this->Document->activeframe[0]['flursize']/$ratio;
    $this->Document->flurposy = $this->Document->flurposy - $this->Document->flursize/4;
    $this->Document->textposx = $this->Document->activeframe[0]['textposx']/$ratio;
    $this->Document->textposy = $this->Document->activeframe[0]['textposy']/$ratio;
    $this->Document->textsize = $this->Document->activeframe[0]['textsize']/$ratio;
    $this->Document->textposy = $this->Document->textposy - $this->Document->textsize/4;
    $this->Document->legendposx = $this->Document->activeframe[0]['legendposx']/$ratio;
    $this->Document->legendposy = $this->Document->activeframe[0]['legendposy']/$ratio;
    $this->Document->legendsize = $this->Document->activeframe[0]['legendsize']/$ratio;
    $this->Document->userposx = $this->Document->activeframe[0]['userposx']/$ratio;
    $this->Document->userposy = $this->Document->activeframe[0]['userposy']/$ratio;
    $this->Document->usersize = $this->Document->activeframe[0]['usersize']/$ratio;
    $this->Document->userposy = $this->Document->userposy - $this->Document->usersize/4;

    $this->Document->height = $height;
    
    if($this->formvars['map_factor'] != ''){
    	$this->map_factor = $this->formvars['map_factor'];
    }
    else{
      $this->map_factor = MAPFACTOR;
    }
    
    if($this->formvars['loadmapsource']){
      $this->loadMap($this->formvars['loadmapsource']);
    }
    else{
      $this->loadMap('DataBase');
    }
    $this->map->selectOutputFormat('jpeg');
    $breite = $this->formvars['worldprintwidth']/2;
    $höhe = $this->formvars['worldprintheight']/2;

    if($this->formvars['angle'] != 0){
      $diag = sqrt(pow($breite, 2) + pow($höhe, 2));
      $gamma = asin($breite/$diag);
      $alpha = deg2rad(90) - deg2rad(abs($this->formvars['angle'])) - $gamma;
      $bboxwidth = cos($alpha) * $diag;
      $alpha2 = $gamma - deg2rad(abs($this->formvars['angle']));
      $bboxheight = cos($alpha2) * $diag;
      $minx = $this->formvars['center_x'] - $bboxwidth;
      $miny = $this->formvars['center_y'] - $bboxheight;
      $maxx = $this->formvars['center_x'] + $bboxwidth;
      $maxy = $this->formvars['center_y'] + $bboxheight;

      $widthratio = $bboxwidth / $breite;
      $heightratio = $bboxheight / $höhe;

      $this->map->set('width', $this->Document->activeframe[0]['mapwidth'] * $widthratio * $this->map_factor);
      $this->map->set('height', $this->Document->activeframe[0]['mapheight'] * $heightratio * $this->map_factor);
    }
    else{
      $minx = $this->formvars['center_x'] - $this->formvars['worldprintwidth']/2;
      $miny = $this->formvars['center_y'] - $this->formvars['worldprintheight']/2;
      $maxx = $this->formvars['center_x'] + $this->formvars['worldprintwidth']/2;
      $maxy = $this->formvars['center_y'] + $this->formvars['worldprintheight']/2;
      $this->map->set('width', $this->Document->activeframe[0]['mapwidth']*$this->map_factor);
      $this->map->set('height', $this->Document->activeframe[0]['mapheight']*$this->map_factor);
    }

    $this->map->setextent($minx,$miny,$maxx,$maxy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'print_preview',$this->user->rolle->last_time_id);
    $this->drawMap();

    if($this->formvars['angle'] != 0){
      $angle = -1 * $this->formvars['angle'];
      $image = imagecreatefromjpeg(IMAGEPATH.basename($this->img['hauptkarte']));
      $rotatedimage = imagerotate($image, $angle, 0);
      $width = imagesx($rotatedimage);
      $height = imagesy($rotatedimage);
      $clipwidth = $this->Document->activeframe[0]['mapwidth']*$this->map_factor;
      $clipheight = $this->Document->activeframe[0]['mapheight']*$this->map_factor;
      $clipx = ($width - $clipwidth) / 2;
      $clipy = ($height - $clipheight) / 2;
      $clippedimage = imagecreatetruecolor($clipwidth, $clipheight);
      ImageCopy($clippedimage, $rotatedimage, 0, 0, $clipx, $clipy, $clipwidth, $clipheight);
      imagejpeg($clippedimage, IMAGEPATH.basename($this->img['hauptkarte']) , 100);
    }

    #setzen der rollen-Kartenparameter fürs loggen
    $this->user->rolle->oGeorefExt->minx = $minx;
    $this->user->rolle->oGeorefExt->miny = $miny;
    $this->user->rolle->oGeorefExt->maxx = $maxx;
    $this->user->rolle->oGeorefExt->maxy = $maxy;
    $this->user->rolle->nImageWidth = $this->map->width;
    $this->user->rolle->nImageHeight = $this->map->height;
    # Lagebezeichnung
    $flur=new Flur('','','',$this->pgdatabase);
    $bildmitte['rw']=$this->formvars['center_x'];
    $bildmitte['hw']=$this->formvars['center_y'];
    $this->lagebezeichnung = $flur->getBezeichnungFromPosition($bildmitte, $this->user->rolle->epsg_code);
    # Übersichtskarte
    if($this->Document->activeframe[0]['refmapfile']){
      $refmapfile = DRUCKRAHMEN_PATH.$this->Document->activeframe[0]['refmapfile'];
      $zoomfactor = $this->Document->activeframe[0]['refzoom'];
      $this->Document->referencemap = $this->createReferenceMap($this->Document->activeframe[0]['refwidth']*$this->map_factor, $this->Document->activeframe[0]['refheight']*$this->map_factor, $minx, $miny, $maxx, $maxy, $zoomfactor, $refmapfile);
    }
    # Legende rendern
    if($this->Document->activeframe[0]['legendsize'] > 0){
      $legend = $this->createlegend($this->Document->activeframe[0]['legendsize']);
      $this->Document->legend = IMAGEURL.basename($legend['name']);
      $this->Document->legendwidth = $legend['width']/$ratio;
    }
    # Wasserzeichen hinzufügen
    if($this->Document->activeframe[0]['watermark'] != ''){
      $this->addwatermark($this->Document->activeframe[0]);
    }

    # Freitexte
    for($j = 0; $j < count($this->Document->activeframe[0]['texts']); $j++){
      if($this->Document->activeframe[0]['texts'][$j]['text'] == '' AND $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']] != ''){    // ein Freitext hat keinen Text aber in der Druckausschnittswahl wurde ein Text vom Nutzer eingefügt
        $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']] = str_replace(chr(10), ';', $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']]);
        $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']] = str_replace(chr(13), '', $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']]);
      }
    }
  }

  function addwatermark($frame) {
    $text = $frame['watermark'];
    $textsize = $frame['watermarksize'];
    $textposx = $frame['watermarkposx'];
    $angle = $frame['watermarkangle'];
    $textposy = $frame['mapheight'] - $frame['watermarkposy'];
    $mapimage = imagecreatefromjpeg(IMAGEPATH.basename($this->img['hauptkarte']));
    $red = ImageColorAllocatealpha ($mapimage, 255, 0, 0, $frame['watermarktransparency']);
    imagettftext($mapimage, $textsize*$this->map_factor, $angle, $textposx*$this->map_factor, $textposy*$this->map_factor, $red, dirname(FONTSET).'/arial.ttf', $text);
    imagejpeg($mapimage,IMAGEPATH.basename($this->img['hauptkarte']), 100);
  }

  function createlegend($size){
    $this->map->legend->set("keysizex", $size*1.8*$this->map_factor);
    $this->map->legend->set("keysizey", $size*1.8*$this->map_factor);
    $this->map->legend->set("keyspacingx", $size*4*$this->map_factor);
    $this->map->legend->set("keyspacingy", $size*0.83*$this->map_factor);
    $this->map->legend->label->set("size", $size*$this->map_factor);
		$this->map->legend->label->set("type", 'truetype');
		$this->map->legend->label->set("font", 'arial');
    $this->map->legend->label->set("position", MS_LR);
    $this->map->legend->label->set("offsetx", $size*-3.3*$this->map_factor);
    $this->map->legend->label->set("offsety", -1*$size*$this->map_factor);
    $this->map->legend->label->color->setRGB(0,0,0);
    $this->map->legend->outlinecolor->setRGB(0,0,0);
    $legendmapDB = new db_mapObj($this->Stelle->id, $this->user->id);
    $legendmapDB->nurAktiveLayerOhneRequires = 1;
    $layerset = $legendmapDB->read_Layer(1);
    $rollenlayer = $legendmapDB->read_RollenLayer();
    $layerset = array_merge($layerset, $rollenlayer);		
    for($i = 0; $i < $this->map->numlayers; $i++){
      $layer = $this->map->getlayer($i);
      $layer->set('status', 0);
    }
    $scale = $this->map_scaledenom * $this->map_factor / 1.414;
    $legendimage = imagecreatetruecolor(1,1);
    $backgroundColor = ImageColorAllocate($legendimage, 255, 255, 255);
    imagefill ($legendimage, 0, 0, $backgroundColor);
		
    for($i = 0; $i < count($layerset); $i++){
      if($layerset[$i]['aktivStatus'] != 0){
        if(($layerset[$i]['minscale'] < $scale OR $layerset[$i]['minscale'] == 0) AND ($layerset[$i]['maxscale'] > $scale OR $layerset[$i]['maxscale'] == 0)){
          $layer = $this->map->getLayerByName($layerset[$i]['Name']);
          if($layerset[$i]['showclasses']){
            for($j = 0; $j < $layer->numclasses; $j++){
              $class = $layer->getClass($j);
              $draw = true;
              if($class->name == ''){
                $class->set('name', ' ');
              }
            }
          }
        }
        if($draw == true){
          $layer->set('status', 1);
          if($layer->connectiontype != 7){
	          $classimage = $this->map->drawLegend();
	          $filename = $this->map_saveWebImage($classimage,'jpeg');
	          $newname = $this->user->id.basename($filename);
	          rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
	          $classimage = imagecreatefromjpeg(IMAGEPATH.$newname);
          }
	        else{
          	$layersection = substr($layer->connection, strpos(strtolower($layer->connection), 'layers')+7);
            $layersection = substr($layersection, 0, strpos($layersection, '&'));
            $layers = explode(',', $layersection);
            for($l = 0; $l < count($layers); $l++){
              $classimage = ImageCreateFromPNG($layer->connection.'&layer='.$layers[$l].'&request=getlegendgraphic');
            }
          }
          $classheight = imagesy($classimage);
          $classwidth = imagesx($classimage);
          $textbox = imagettfbbox($size*$this->map_factor, 0, dirname(FONTSET).'/arial.ttf', $layer->name);
          $textwidth = $textbox[2] - $textbox[0] + $size*0.66*$this->map_factor;
          $layernameimage = imagecreatetruecolor($textwidth,$size*3.3*$this->map_factor);
          $backgroundColor = ImageColorAllocate ($layernameimage, 255, 255, 255);
          $black = ImageColorAllocate ($layernameimage, 0, 0, 0);
          imagefill ($layernameimage, 0, 0, $backgroundColor);
          imagettftext($layernameimage, $size*$this->map_factor, 0, 3, $size*2.55*$this->map_factor, $black, dirname(FONTSET).'/arial.ttf', umlaute_html($layer->name));
          $height = $classheight + imagesy($legendimage) + $size*3.66*$this->map_factor;
          if(imagesx($legendimage) > $textwidth){
            if($classwidth > imagesx($legendimage)){
              $width = $classwidth;
            }
            else{
              $width = imagesx($legendimage);
            }
          }
          else{
            if($textwidth > $classwidth){
              $width = $textwidth;
            }
            else{
              $width = $classwidth;
            }
          }
          $newlegendimage = imagecreatetruecolor($width+$size*0.55*$this->map_factor,$height);
          $backgroundColor = ImageColorAllocate ($newlegendimage, 255, 255, 255);
          imagefilledrectangle($newlegendimage, 0, 0, imagesx($newlegendimage), imagesy($newlegendimage), $backgroundColor);
          ImageCopy($newlegendimage, $layernameimage, 0, 0, 0, 0, imagesx($layernameimage), $size*3.3*$this->map_factor);
          if($layerset[$i]['showclasses']){
            ImageCopy($newlegendimage, $classimage, 0, $size*3.3*$this->map_factor, 0, 0, imagesx($classimage), imagesy($classimage));
          }
          ImageCopy($newlegendimage, $legendimage, 0, $size*3.3*$this->map_factor+$classheight, 0, 0, imagesx($legendimage), imagesy($legendimage));
          $legendimage = $newlegendimage;

          $layer->set('status', 0);
          $draw = false;
          $classheight = 0;
        }
      }
    }
    $newlegendimage = imagecreatetruecolor(imagesx($legendimage)+$size*0.55*$this->map_factor,$size*3*$this->map_factor+imagesy($legendimage)+$size*0.55*$this->map_factor);
    $backgroundColor = ImageColorAllocate ($newlegendimage, 255, 255, 255);
    imagefilledrectangle($newlegendimage, 0, 0, imagesx($newlegendimage), imagesy($newlegendimage), $backgroundColor);
    ImageCopy($newlegendimage, $legendimage, $size*0.55*$this->map_factor, $size*3*$this->map_factor, 0, 0, imagesx($legendimage), imagesy($legendimage));
    $legendimage = $newlegendimage;
    $black = ImageColorAllocate ($legendimage, 0, 0, 0);
    imagettftext($legendimage, $size*1.1*$this->map_factor, 0, $size*0.55*$this->map_factor, $size*2.55*$this->map_factor, $black, dirname(FONTSET).'/arial_bold.ttf', 'Legende');
    imagesetthickness ($legendimage, 1*$this->map_factor);
    imagerectangle($legendimage, $this->map_factor, $this->map_factor, imagesx($legendimage)-$this->map_factor, imagesy($legendimage)-$this->map_factor, $black);
    $legendimagename = IMAGEPATH.rand(0, 1000000).'.jpg';
    imagejpeg($legendimage, $legendimagename, 100);
    $legend['width'] = imagesx($legendimage);
    $legend['name'] = $legendimagename;
    return $legend;
  }

  function getlegendimage($layer_id, $style_id){
    # liefert eine url zu einem Legendenbild eines Layers mit einem bestimmten Style
    $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
    $map = ms_newMapObj(DEFAULTMAPFILE);
    $map->setextent(100,100,200,200);
    $map->set('width',10);
    $map->set('height',10);
    $map->web->set('imagepath', IMAGEPATH);
    $map->web->set('imageurl', IMAGEURL);
    $map->setSymbolSet(SYMBOLSET);
    $map->setFontSet(FONTSET);

    $layer=ms_newLayerObj($map);
    $layerset = $mapDB->get_Layer($layer_id);
    $layer->set('data',$layerset['Data']);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name', 'test');
    $layer->set('type', $layerset['Datentyp']);
    if (MAPSERVERVERSION < '540') {
      $layer->set('connectiontype',$layerset['connectiontype']);
    }
    else {
      $layer->setConnectionType($layerset['connectiontype']);
    }
    $layer->set('connection', $layerset['connection']);
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $dbStyle = $mapDB->get_Style($style_id);
    $style = ms_newStyleObj($klasse);
    if($dbStyle['symbolname']!='') {
      $style -> set('symbolname',$dbStyle['symbolname']);
    }
    if($dbStyle['symbol']>0) {
      $style->set('symbol',$dbStyle['symbol']);
    }
    $style->set('size', $dbStyle['size']);
    if($dbStyle['width']!='') {
      $style->set('width', $dbStyle['width']);
    }
    if($dbStyle['angle']!='') {
      $style->set('angle', $dbStyle['angle']);
    }
    if (MAPSERVERVERSION < 500 AND $dbStyle['sizeitem']!='') {
      $style->sizeitem = $dbStyle['sizeitem'];
    }
  	if(MAPSERVERVERSION >= 620) {
    	if($dbStyle['geomtransform'] != '') {
      	$style->setGeomTransform($dbStyle['geomtransform']);
      }
      if($dbStyle['pattern']!='') {
      	$style->setPattern(explode(' ',$dbStyle['pattern']));
        $style->linecap = 'butt';
      }
			if($dbStyle['gap'] != '') {
				$style->set('gap', $dbStyle['gap']);
			}
			if($dbStyle['linecap'] != '') {
				$style->set('linecap', constant(MS_CJC_.strtoupper($dbStyle['linecap'])));
			}
			if($dbStyle['linejoin'] != '') {
				$style->set('linejoin', constant(MS_CJC_.strtoupper($dbStyle['linejoin'])));
			}
			if($dbStyle['linejoinmaxsize'] != '') {
				$style->set('linejoinmaxsize', $dbStyle['linejoinmaxsize']);
			}
    }
    #######################################################
    if($layer->type > 0){
    	$symbol = $map->getSymbolObjectById($style->symbol);
    	if($symbol->type == 1006){ 	# 1006 == hatch
    		$style->set('size', 2*$style->width);					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt 
    		$style->set('maxsize', 2*$style->width);
    	}
    	else{
				if($dbStyle['size'] < 2)$style->set('size', 2);					# size und maxsize bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt 
    		if($dbStyle['maxsize'] < 2)$style->set('maxsize', 2);
    	}
    }
    else{
    	$style->set('maxsize', $style->size);		# maxsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
    }
    #######################################################
    $RGB=explode(" ",$dbStyle['color']);
    if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
    $style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
    $RGB=explode(" ",$dbStyle['outlinecolor']);
    $style->outlinecolor->setRGB(intval($RGB[0]),intval($RGB[1]),intval($RGB[2]));
    if($dbStyle['backgroundcolor']!='') {
      $RGB=explode(" ",$dbStyle['backgroundcolor']);
      $style->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
    }
		
    $image = $klasse->createLegendIcon(25,18);
    $filename = $this->map_saveWebImage($image,'jpeg');
    $newname = $this->user->id.basename($filename);
    rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
    return $newname;
  }

  function notizErfassung() {
    # Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
    if ($this->formvars['oid']=='') {
      $this->titel='Neue Notiz';
    }
    else {
      $this->titel='Notiz Bearbeiten';
    }
    $this->main="notizerfassung.php";
    # aktuellen Kartenausschnitt laden + zeichnen!
    $this->loadMap('DataBase');
    $this->notizen=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->notizen->anlegenKategorien = $this->notizen->getKategorie(NULL, $this->Stelle->id, NULL, 'true', NULL);
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
      $this->saveMap('');
      $currenttime=date('Y-m-d H:i:s',time());
      $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
      $this->drawMap();
    }
    else {
      # Wenn nicht navigiert wurde, also kein cmd knopf gedrückt wurde,
      # und eine oid angegeben wurde, werden die Daten der notiz aus der Datenbank gelesen.
      if ($this->formvars['oid']!='') {
        $ret=$this->notizen->getNotizen($this->formvars['oid'],'','','','');
        $this->formvars['notiz']=$ret[1][0]['notiz'];
        $this->formvars['kategorie_id']=$ret[1][0]['kategorie_id'];
        $this->formvars['person']=$ret[1][0]['person'];
        $this->formvars['datum']=$ret[1][0]['datum'];
        $this->notizen->notizKategorie = $this->notizen->getKategorie($ret[1][0]['kategorie_id'], NULL, NULL, NULL, NULL);
        # Bildung der Textposition zur SVG-Ausgabe
        if(strpos($ret[1][0]['textgeom'], 'POINT') === false){    # Polygon
          $PolygonAsSVG = transformCoordsSVG($ret[1][0]['svggeom']);
          $this->formvars['newpath'] = $PolygonAsSVG;
          $this->formvars['newpathwkt'] = $ret[1][0]['textgeom'];
          $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
        }
        else{   # Punkt
          $point_teil=strrpos($ret[1][0]['textgeom'],'(')+1;
          $point_paar=substr($ret[1][0]['textgeom'],$point_teil,count($point_teil)-2);
          $point_xy=explode(' ',$point_paar);
          $this->formvars['loc_x']=$point_xy[0];
          $this->formvars['loc_y']=$point_xy[1];
        }
      }
      $this->saveMap('');
      $this->drawMap();
    }
    if ($this->formvars['person']=='') {
      $this->formvars['person']=$this->user->Name;
    }
    $this->output();
  }

  function notizSpeichern() {
    # Zusammensetzen der übergebenen Parameter für die Textposition
    #echo 'formvars[loc_x, loc_y]: '.$this->formvars['loc_x'].', '.$this->formvars['loc_x'];
    if ($this->formvars['loc_x'] > 0 AND $this->formvars['loc_y'] > 0) {
      $location_x = $this->formvars['loc_x'];
      $location_y = $this->formvars['loc_y'];
      $this->formvars['textposition']="POINT(".$location_x." ".$location_y.")";
      #echo '<br/>formvars[textposition]: '.$this->formvars['textposition'];
    }
    elseif($this->formvars['newpathwkt'] != ''){
      $this->formvars['textposition'] = $this->formvars['newpathwkt'];
      #echo '<br/>formvars[textposition]: '.$this->formvars['textposition'];
    }
    else {
      $this->formvars['textposition']="";
    }
    # 2006-06-21 pk
    # aktuellen EPSG Code der Stelle in Variable formvar übergeben
    $this->formvars['epsg_von']=$this->user->rolle->epsg_code;

    # Notizobjekt erzeugen
    $notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);

    # 1. Prüfen der Eingabewerte
    #echo '<br>Prüfen der Eingabewerte.';
    $this->formvars['stelle_id'] = $this->Stelle->id;
    $ret=$notiz->pruefeEingabedaten($this->formvars);
    if ($ret[0]) {
      # Es wurde ein oder mehrere Fehler bei den Eingabewerten gefunden
      $this->Meldung=$ret[1];
    }
    else {
      # Eingabewerte fehlerfrei
      #echo 'Eingabe fehlerfrei:';
      if ($this->formvars['oid']=='') {
        # 2. eintragenNeueZone
        $ret=$notiz->eintragenNeueNotiz($this->formvars);
        if ($ret[0]) {
          # 2.1 Eintragung fehlerhaft
          $this->Meldung=$ret[1];
        }
        else {
          #  2.2 Eintragung erfolgreich
          $alertmsg='\nNotiz erfolgreich in die Datenbank eingetragen.'.
          $this->formvars['pathx']='';    $this->formvars['loc_x']='';
          $this->formvars['pathy']='';    $this->formvars['loc_y']='';
          $this->formvars['umring']='';   $this->formvars['textposition']='';
        }
      }
      else {
        # 3. Notiz Aktualisieren
        $ret=$notiz->aktualisierenNotiz($this->formvars['oid'],$this->formvars);
        if ($ret[0]) {
          # 3.1 Eintragung fehlerhaft
          $this->Meldung=$ret[1];
        }
        else {
          # 3.2 Aktualisierung erfolgreich
          $alertmsg='\nNotiz erfolgreich in die Datenbank aktualisiert.';
        }
      }
    }
    $this->notizErfassung();
  }

  function notizLoeschen($oid){
    $notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $notiz->NotizLoeschen($oid);
  }

  function notizKatVerwaltung() {
    $this->stelle=new stelle('',$this->database);
    $this->stellen=$this->stelle->getStellen('Bezeichnung');
    $this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->AllKat=$this->notiz->selectKategorie('','','');
    if($this->formvars['kategorie_id'] != ''){
      $this->Kat=$this->notiz->getKategorie($this->formvars['kategorie_id'],'','','','');
      $this->Kat2Stelle=$this->notiz->selectKat2stelle($this->formvars['kategorie_id']);
    }
    $this->titel='Notizkategorienverwaltung';
    $this->main='Kat_bearbeiten.php';
    $this->output();
  } # END of funtion notizKatVerwaltung

  function notizKategoriehinzufügen(){
    $this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->notiz->insertKategorie($this->formvars['newKategorie']);
    $kat=$this->notiz->selectKategorie('',$this->formvars['newKategorie'],'');
    $this->formvars['kategorie_id']=$kat[0]['id'];
    $this->notizKatVerwaltung();
  } # END of function notizKathinzufügen

  function notizKategorieAendern(){
    $this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    if($this->formvars['kategorie_id'] != ''){
      $this->notiz->notizKategorieAenderung($this->formvars);
    }
    $this->notizKatVerwaltung();
  } # END of function notizKategorieAendern

  function notizKategorieLoeschen() {
    $this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->notiz->notizKategorieLoeschen($this->formvars['kategorie_id'],$this->formvars['plus_notiz']);
    $max_id=$this->notiz->selectKategorie('','','');
    $this->formvars['kategorie_id']=$max_id[0]['id'];
    $this->notizKatVerwaltung();
  } # END of function notizKategorieLoeschen

  function metadatenSuchen() {
    # Zuweisen von Titel und Layoutdatei
    $this->titel='Metadaten Suchergebnisse';
    $this->main='Metadaten.php';
    # Abfragen der Metadaten in der Datenbank
    $this->metadaten=new metadatensatz('',$this->pgdatabase);
    $ret=$this->metadaten->getMetadatenQuickSearch($this->formvars);
    if ($ret[0]) {
      $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
    }
    # Zuweisen von Werten zu Variablen in der Layoutdatei
    $i=0;
    $this->qlayerset[0]['Name']=$this->titel;
    $this->qlayerset[0]['shape']=$ret[1];
    # Ausgabe an den Client
    $this->output();
  }

  function metadatenblattanzeige() {
    # Zuweisen von Titel und Layoutdatei
    $this->titel='Metadatenblattanzeige';
    $this->main='Metadatenblatt.php';
    # Abfragen der Metadaten in der Datenbank
    $this->metadaten=new metadatensatz('',$this->pgdatabase);
    $ret=$this->metadaten->getMetadaten($this->formvars);
    if ($ret[0]) {
      $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
    }
    else {
      # Zuweisen der Werte des abgefragten Datensatzes zur Variable für die Anzeige in Layoutdatei
      $this->metadataset=$ret[1][0];
    }
    # Ausgabe an den Client
    $this->output();
  }

  function metadateneingabe() {
    #2005-11-29_pk
    $metadatensatz=new metadatensatz($this->formvars['oid'],$this->pgdatabase);
    if ($this->formvars['oid']!='') {
      # Es handelt sich um eine Änderung eines Datensatzes
      # Auslesen der Metadaten aus der Datenbank und Zuweisung zu Formularobjekten
      $ret=$metadatensatz->getMetadaten($this->formvars);
      if ($ret[0]) {
        $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
      }
      else {
        $this->formvars=array_merge($this->formvars,$ret[1][0]);
      }
      $this->titel='Metadatenänderung';
    }
    else {
      # Anzeigen des Metadateneingabeformulars
      $this->titel='Metadateneingabe';
      # Zuweisen von defaultwerten für die Metadatenelemente wenn nicht vorher
      # schon ein Formular ausgefüllt wurde
      if ($this->formvars['mdfileid']=='') {
        $defaultvalues=$metadatensatz->readDefaultValues($this->user);
        $this->formvars=array_merge($this->formvars,$defaultvalues);
      }
      else {
        # Wenn das Formular erfolgreich eingetragen wurde neue mdfileid vergeben
        if ($this->Fehlermeldung=='') {
          $this->formvars['mdfileid']=rand();
        }
      }
    }
    # Erzeugen der Formularobjekte für die Schlagworteingabe
    $ret=$metadatensatz->getKeywords('','','theme','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $this->formvars['allthemekeywords']=$ret[1];
    }
    $ret=$metadatensatz->getKeywords('','','place','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $this->formvars['allplacekeywords']=$ret[1];
    }
    $this->allthemekeywordsFormObj=new FormObject("allthemekeywords","select",$this->formvars['allthemekeywords']['id'],explode(", ",$this->formvars['selectedthemekeywordids']),$this->formvars['allthemekeywords']['keyword'],4,0,1,NULL);
    $this->allplacekeywordsFormObj=new FormObject("allplacekeywords","select",$this->formvars['allplacekeywords']['id'],explode(", ",$this->formvars['selectedplacekeywordids']),$this->formvars['allplacekeywords']['keyword'],4,0,1,NULL);
    $this->main='metadateneingabeformular.php';
    $this->loadMap('DataBase');
    if ($this->formvars['refmap_x']!='') {
      $this->zoomToRefExt();
    }
    $this->navMap($this->formvars['CMD']);
    $this->saveMap('');
    $this->drawMap();
    $this->output();
  }

  function metadatensatzspeichern() {
    #2005-11-29_pk
    $metadatensatz=new metadatensatz($this->formvars['mdfileid'],$this->pgdatabase);
    $ret=$metadatensatz->checkMetadata($this->formvars);
    if ($ret[0]) {
      # Fehler in den Metadaten oder es fehlen welche
      $this->Fehlermeldung='Fehler:'.$ret[1];
    }
    else {
      $ret=$metadatensatz->speichern($ret[1]);
      if ($ret[0]) {
        $this->Fehlermeldung='Der Metadatensatz konnte nicht gespeichert werden.<br>'.$ret[1];
      }
    }
    $this->metadateneingabe();
  }

  function festpunkteZuAntragZeigen() {
    # Funktion fragt alle Festpunkte zum einem Antrag heraus und übergibt diese an die Funktion
    # zum Anzeigen der Festpunkte in der Karte
    $festpunkte=new Festpunkte('',$this->pgdatabase);
    $ret=$festpunkte->getFestpunkte('','','','','',$this->formvars['antr_selected'],'','pkz');
    if ($ret[0]) {
      $errmsg="Die Festpuntke zum Antrag $this->formvars['antr_selected'] konnten nicht abgefragt werden.";
    }
    else {
      if ($festpunkte->anzPunkte==0) {
        $this->festpunkteErgebnisanzeige();
      }
      else {
        # Zuweisen der Punktkennzeichen zu einem Array, welches von der Funktion zum Anzeigen in der Karte verwendet wird.
        foreach($festpunkte->liste AS $punkt) {
          $this->formvars['pkz'][$punkt['pkz']]=$punkt['pkz'];
        }
        $this->festpunkteZeigen();
      }
    }
  }

  function festpunkteZeigen() {
    $this->loadMap('DataBase');
    if (is_array($this->formvars['pkz'])) {
      $punktliste=array_keys($this->formvars['pkz']);
    }
    else {
      $punktliste=$this->formvars['pkz'];
    }
    $this->zoomToFestpunkte($punktliste,20);
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function zoomToFestpunkte($FestpunktListe,$border) {
    # Abfragen der Ausdehnung der Festpunkte in der Liste
    $Festpunkte=new Festpunkte('',$this->pgdatabase);
    $ret=$Festpunkte->getBBoxAsRectObj($FestpunktListe);
    if ($ret[0]) {
      # Fehler bei der Abfrag der BoundingBox
      # Es erfolgt keine Änderung der aktuellen Ausdehnung
    }
    else {
      $rect=$ret[1];
      # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
      # Setzen der neuen Kartenausdehnung.
      $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
    	if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
  }

  function festpunkteErgebnisanzeige() {
    $this->titel='Suche nach Festpunkten';
    #$this->main='festpunktsuchform.php';
    $this->qlayerset[0]['shape']=$this->festpunkte->liste;
    $i=0;
    $this->main='Festpunkte.php';
    $this->output();
  }

  function festpunkteWahl() {
    $this->titel='Suche nach Festpunkten';
    $this->main='festpunktsuchform.php';
    $this->output();
  }

  function festpunkteSuchen() {
    if ($this->formvars['antr_selected']=='' AND $this->formvars['pkz']=='' AND $this->formvars['kiloquad']=='') {
      $this->Fehlermeldung='<br>Geben Sie mindestens eine Antragsnummer, Kilometerquadrat oder Punktkennzeichen zu Suche an!';
    }
    else {
      $this->festpunkte=new festpunkte('',$this->pgdatabase);
      $ret=$this->festpunkte->getFestpunkte(array($this->formvars['pkz']),array(0,1,2,3,4,5,6),'','','',$this->formvars['antr_selected'],$this->formvars['kiloquad'],'pkz');
      if ($ret[0]) {
        $this->Fehlermeldung='<br>Es konnten keine Festpunkte abgefragt werden'.$ret[1];
      }
      else {
        if ($this->festpunkte->anzPunkte==0) {
          $this->Fehlermeldung='<br>Es konnten keine Festpunkte gefunden werden, bitte ändern Sie die Anfrage!';
        }
      } # ende Abfrage war erfolgreich
    }
    if ($this->Fehlermeldung!='') {
      $this->festpunkteWahl();
    }
    else {
      $this->festpunkteErgebnisanzeige();
    }
  }

  function nutzungsuchen(){
    # 2006-29-06 sr: auf Gemarkungen der Stelle einschränken
    if($this->formvars['GemkgID'] > 0){
      $Liste['GemkgID'][] = $this->formvars['GemkgID'];
      $this->formvars['GemkgID'] = $Liste['GemkgID'];
    }
    else{
      $GemeindenStelle=$this->Stelle->getGemeindeIDs();
      if($GemeindenStelle != NULL){
        $Gemeinde=new gemeinde('',$this->pgdatabase);
        # Auswahl aller Gemeinden der Stelle
        $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');
        # Abfragen der Gemarkungen mit dazugehörigen Namen der Gemeinden
        $Gemarkung=new gemarkung('',$this->pgdatabase);
        $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
        $this->formvars['GemkgID'] = $GemkgListe['GemkgID'];
      }
    }
    if($this->formvars['GemkgID'][0] != '-'){
      $flurstueck=new flurstueck('',$this->pgdatabase);
      $ret=$flurstueck->getFlurstByNutzungen($this->formvars['GemkgID'][0], $this->formvars['nutzung'], $this->formvars['anzahl']);
      if ($ret[0] == 1) {
        $this->Fehlermeldung='<br>Es konnten keine Flurstücke abgefragt werden'.$ret[1];
      }
      else {
        $this->flurstuecke=$ret[1];
        if (count($this->flurstuecke)==0) {
          $this->Fehlermeldung='<br>Es konnten keine Flurstücke gefunden werden, bitte ändern Sie die Anfrage!';
        }
        else {
          $ret=$flurstueck->getFlurstByNutzungen($this->formvars['GemkgID'][0], $this->formvars['nutzung'], NULL);
          $this->anzNamenGesamt=count($ret[1]);
        }
      } # ende Abfrage war erfolgreich
    }
    $this->nutzungWahl();
  }

  function nameSuchen() {
    # 2006-29-06 sr: auf Gemarkungen der Stelle einschränken
    if($this->formvars['GemkgID'] > 0){       # es wurde eine Gemarkung ausgewählt
      $GemkgListe['GemkgID'] = array($this->formvars['GemkgID']);
    }
    else{                                     # es wurde keine Gemarkung ausgewählt -> wenn Stelle eingeschränkt, erlaubte Gemarkungen setzen
      $GemeindenStelle=$this->Stelle->getGemeindeIDs();
      if($GemeindenStelle != NULL){
        $Gemeinde=new gemeinde('',$this->pgdatabase);
        # Auswahl aller Gemeinden der Stelle
        if(ALKIS)$GemListe=$Gemeinde->getGemeindeListeALKIS($GemeindenStelle, 'bezeichnung');
        else $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');
        # Abfragen der Gemarkungen mit dazugehörigen Namen der Gemeinden
        $Gemarkung=new gemarkung('',$this->pgdatabase);
        if(ALKIS)$GemkgListe=$Gemarkung->getGemarkungListeALKIS($GemListe['ID'],'','gmk.bezeichnung');
        else $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
      }
    }

    # 2006-02-01 pk
    $flurstueck=new flurstueck('',$this->pgdatabase);
    $ret=$flurstueck->getNamen('%'.$this->formvars['name1'].'%','%'.$this->formvars['name2'].'%','%'.$this->formvars['name3'].'%','%'.$this->formvars['name4'].'%',$this->formvars['bezirk'],$this->formvars['blatt'],$GemkgListe['GemkgID'], $this->formvars['FlurID'], $this->formvars['anzahl'], $this->formvars['offset'],$this->formvars['caseSensitive'], $this->formvars['order']);
    if ($ret[0]) {
      $this->Fehlermeldung='<br>Es konnten keine Namen abgefragt werden'.$ret[1];
      $this->namenWahl();
    }
    else {
      $this->namen=$ret[1];
      if (count($this->namen)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Namen gefunden werden, bitte ändern Sie die Anfrage!';
      }
      else {
        $ret=$flurstueck->getNamen('%'.$this->formvars['name1'].'%','%'.$this->formvars['name2'].'%','%'.$this->formvars['name3'].'%','%'.$this->formvars['name4'].'%',$this->formvars['bezirk'],$this->formvars['blatt'],$GemkgListe['GemkgID'],$this->formvars['FlurID'],'','',$this->formvars['caseSensitive'], $this->formvars['order']);
        $this->anzNamenGesamt=count($ret[1]);

        if($this->formvars['withflurst'] == 'on'){
          for($i = 0; $i < count($this->namen); $i++){
            $ret[1] = $flurstueck->getFlurstByGrundbuecher(array($this->namen[$i]['bezirk'].'-'.$this->namen[$i]['blatt']));
            $this->namen[$i]['flurstuecke'] = $ret[1];
            for($j = 0; $j < count($this->namen[$i]['flurstuecke']); $j++){
              if(ALKIS)$ret = $this->pgdatabase->getALBDataALKIS($this->namen[$i]['flurstuecke'][$j]);
              else $ret = $this->pgdatabase->getALBData($this->namen[$i]['flurstuecke'][$j]);
              $this->namen[$i]['alb_data'][$j] = $ret[1];
            }
          }
        }

      }
      $this->namenWahl();
    } # ende Abfrage war erfolgreich
  }

  function flurstuecksAnzeigeByGrundbuecher(){
    $flurstueck=new flurstueck('',$this->database);
    $flurstueck->database=$this->pgdatabase;
    $gbarray = explode(';', $this->formvars['Grundbuecher']);
    $Flurstuecke = $flurstueck->getFlurstByGrundbuecher($gbarray);
    if (count($Flurstuecke)==0) {
      $this->Fehlermeldung='<br>Es konnten keine Flurstücke gefunden werden, bitte ändern Sie die Anfrage!';
      $this->namenWahl();
    }
    else {
      # Anzeige der Flurstuecke
      $this->zoomToALKFlurst($Flurstuecke,10);
      $currenttime=date('Y-m-d H:i:s',time());
      $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
      $this->drawMap();
      $this->saveMap('');
      $this->output();
    }
  }

  function flurstuecksSucheByGrundbuecher(){
    $flurstueck=new flurstueck('',$this->database);
    $flurstueck->database=$this->pgdatabase;
    $gbarray = explode(';', $this->formvars['Grundbuecher']);
    $Flurstuecke = $flurstueck->getFlurstByGrundbuecher($gbarray);
    if(count($Flurstuecke)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Flurstücke gefunden werden, bitte ändern Sie die Anfrage!';
        $this->namenWahl();
    }
    else {
      $this->flurstAnzeige($Flurstuecke);
      $this->output();
    }
  }

  function flurstuecksSucheByNamen() {
    $flurstueck=new flurstueck('',$this->database);
    $flurstueck->database=$this->pgdatabase;
    $ret=$flurstueck->getFlurstByLfdNrName($this->formvars['lfd_nr_name'],$this->formvars['anzahl']);
    if ($ret[0]) {
      $this->Fehlermeldung='<br>Es konnten keine Namen abgefragt werden'.$ret[1];
      $this->namenWahl();
    }
    else {
      $FlurstKennz=$ret[1];
      if (count($FlurstKennz)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Namen gefunden werden, bitte ändern Sie die Anfrage!';
        $this->namenWahl();
      }
      else {
        # Anzeige der Namen
        $this->flurstAnzeige($FlurstKennz);
        $this->output();
      } # ende Ergebnisanzahl größer 0
    } # ende Abfrage war erfolgreich
  }

  function flurstuecksAnzeigeByNamen() {
    $flurstueck=new flurstueck('',$this->database);
    $flurstueck->database=$this->pgdatabase;
    $ret=$flurstueck->getFlurstByLfdNrName($this->formvars['lfd_nr_name'],$this->formvars['anzahl']);
    if ($ret[0]) {
      $this->Fehlermeldung='<br>Es konnten keine Namen abgefragt werden'.$ret[1];
      $this->namenWahl();
    }
    else {
      $FlurstKennz=$ret[1];
      if (count($FlurstKennz)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Namen gefunden werden, bitte ändern Sie die Anfrage!';
        $this->namenWahl();
      }
      else {
        # Anzeige der Flurstuecke
        $this->zoomToALKFlurst($FlurstKennz,10);
        $currenttime=date('Y-m-d H:i:s',time());
        $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
        $this->drawMap();
        $this->saveMap('');
        $this->output();
      } # ende Ergebnisanzahl größer 0
    } # ende Abfrage war erfolgreich
  }

  function namenWahl() {
    if ($this->formvars['anzahl']==0) {
      $this->formvars['anzahl']=10;
    }
    $this->titel='Namenssuche im ALB';
    $this->main='namensuchform.php';

    # 2006-29-06 sr: Gemarkungsformobjekt nur für Gemeinden der Stelle
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$this->pgdatabase);
    # Auswahl aller Gemeinden der Stelle
    if(ALKIS)$GemListe=$Gemeinde->getGemeindeListeALKIS($GemeindenStelle, 'bezeichnung');
    else $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');

    # Abfragen der Gemarkungen mit dazugehörigen Namen der Gemeinden
    $GemkgID=$this->formvars['GemkgID'];
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    if(ALKIS)$GemkgListe=$Gemarkung->getGemarkungListeALKIS($GemListe['ID'],'','gmk.bezeichnung');
    else $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl    
    $this->GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    $this->GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $this->GemkgFormObj->outputHTML();
    $GemkgID=$this->formvars['GemkgID'];
    
    # Abragen der Fluren zur Gemarkung
    if($GemkgID > 0){
    	$Flur=new Flur('','','',$this->pgdatabase);
    	if(ALKIS)$FlurListe=$Flur->getFlurListeALKIS($GemkgID,'','gemarkungsteilflur');
    	else $FlurListe=$Flur->getFlurListe($GemkgID,'','FlurNr');
    	# Erzeugen des Formobjektes für die Flurauswahl
    	if (count($FlurListe['FlurID'])==1) { $FlurID=$FlurListe['FlurID'][0]; }
    }
    $this->FlurFormObj=new FormObject("FlurID","select",$FlurListe['FlurID'],$this->formvars['FlurID'],$FlurListe['Name'],"1","","",NULL);
    $this->FlurFormObj->insertOption(-1,0,'--Auswahl--',0);
    $this->FlurFormObj->outputHTML();
    $this->output();
  }

  function nutzungWahl(){
    if ($this->formvars['anzahl'] == 0) {
      $this->formvars['anzahl'] = 10;
    }
    $this->titel='Flurstückssuche nach Nutzung';
    $this->main='nutzungensuchform.php';

    # 2006-29-06 sr: Gemarkungsformobjekt nur für Gemeinden der Stelle
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$this->pgdatabase);
    # Auswahl aller Gemeinden der Stelle
    $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');

    # Abfragen der Gemarkungen mit dazugehörigen Namen der Gemeinden
    $GemkgID=$this->formvars['GemkgID'];
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $this->GemkgFormObj=new FormObject("GemkgID","select",$GemkgListe['GemkgID'],$GemkgID,$GemkgListe['Bezeichnung'],"1","","",NULL);
    $this->GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $this->GemkgFormObj->outputHTML();
    $this->output();
  }

  function sendImage($name,$format) {
    #var_dump(gd_info());
    #phpinfo();
    $im = ImageCreateFromPng($name);
    ob_end_clean();
    ob_start("output_handler");
    ImagePNG($im);
    ob_end_flush();
  }

	function deleteDokument($path){
		$path = array_pop(explode('&dokument=', $path));
		$path = array_shift(explode('&original_name', $path));
		if(file_exists($path)){
			unlink($path);
		}
	}

  # 2008-03-26 pk
  function sendeDokument($dokument, $original_name) {
    $dateiname=basename($dokument);
    if($original_name == '')$original_name=$dateiname;
    $dateinamensteil=explode('.',$dateiname);
    ob_end_clean();
    header("Content-type: image/".$dateinamensteil[1]);
    header("Content-Disposition: attachment; filename=".$original_name);
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    readfile($dokument);
    ob_flush();
    return 1;
  }

  function sendeDokument_mit_vorschau($dokument, $original_name) {
  	$type = strtolower(array_pop(explode('.', $dokument)));
  	echo '<html>
					<head>
					<link rel="stylesheet" href="layouts/'.$this->style.'">
					</head>
					<body style="background-image:url('.GRAPHICSPATH.'bg.gif)" ><table border="0" cellpadding="0" cellspacing="0"><tr><td>';
  	if($type == 'jpg' OR $type == 'png' OR $type == 'gif' ){
  		echo '<a href="index.php?go=sendeDokument&dokument='.$dokument.'&original_name='.$original_name.'"><img style="border:1px solid black" height="140" src="index.php?go=sendeDokument&dokument='.$dokument.'"></a>';
  	}
  	else{
  		switch ($type) {
  			case 'pdf' :{
  				echo '<a href="index.php?go=sendeDokument&dokument='.$dokument.'&original_name='.$original_name.'"><img style="border:0px solid black" src="'.GRAPHICSPATH.'pdf.gif"></a>';
  			}break;
  			
  			case 'doc' :{
  				echo '<a href="index.php?go=sendeDokument&dokument='.$dokument.'&original_name='.$original_name.'"><img style="border:0px solid black" src="'.GRAPHICSPATH.'openoffice.gif"></a>';
  			}break;
  			
  			default : {
  				$image = imagecreatefromgif(GRAPHICSPATH.'document.gif');
          $textbox = imagettfbbox(13, 0, dirname(FONTSET).'/arial.ttf', '.'.$type);
          $textwidth = $textbox[2] - $textbox[0] + 13;
          $blue = ImageColorAllocate ($image, 26, 87, 150);
          imagettftext($image, 13, 0, 22, 34, $blue, dirname(FONTSET).'/arial_bold.ttf', $type);
          $filename = rand(0,100000).'.gif';
          imagegif($image, IMAGEPATH.$filename);
  				echo '<a href="index.php?go=sendeDokument&dokument='.$dokument.'&original_name='.$original_name.'"><img style="border:0px solid black" src="'.TEMPPATH_REL.$filename.'"></a>';
  			}
  		}
  	}
  	echo '</td><td valign="middle">&nbsp;&nbsp;<a href="index.php?go=sendeDokument&dokument='.$dokument.'&original_name='.$original_name.'">'.$original_name.'</a></td></tr></table>';
  	echo '</body></html>';
    return 1;
  }

  function sendeFestpunktskizze($Bild,$Pfad) {
    $dateiname=basename($Bild);
    $dateinamensteil=explode('.',$dateiname);
    ob_end_clean();
    if (in_array($dateinamensteil[1],array('png','jpg','gif'))) {
      header("Content-type: image/".$dateinamensteil[1]);
    }
    elseif ($dateinamensteil[1]=='pdf') {
      header("Content-type: application/pdf");
    }
    else{
    	header("Content-Disposition: attachment; filename=".$dateiname);
    }
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    readfile($Pfad.$Bild);
    ob_flush();
    return 1;
  }

  function showFestpunkteSkizze() {
    # Daten sind in Datenbank eingelesen. Herausfiltern von Fehlern

    # 1) Übergeben der Liste von Punkten, die geprüft werden sollen
    if (is_array($this->formvars['pkz'])) {
      $abgefragtefestpunkte=array_values($this->formvars['pkz']);
    }

    # 2) Abfragen der zu prüfenden Festpunkte
    $festpunkte=new Festpunkte('',$this->pgdatabase);
    $festpunkte->getFestpunkte($abgefragtefestpunkte,array(0,1),'','','','','','pkz');
    # 3) Übernehmen der Punkte in eine Liste, die mindestens eine Datei/Blatt haben.
    for ($i=0;$i<$festpunkte->anzPunkte;$i++) {
      $festpunkte->liste[$i]['skizze']=$festpunkte->checkSkizzen($festpunkte->liste[$i]['pkz']);
      if ($festpunkte->liste[$i]['skizze']['is_file']) {
        # Wenn mindestens eine Datei gefunden wurde, pkz in die Liste aufnehmen
        $punktnummern[]=trim(str_replace('-','',$festpunkte->liste[$i]['pkz']));
      }
    }

    # 4) Ermittlung der Kilometerquadrate, in denen Festpunkte gefunden wurden
    $kilometerquadrate=$festpunkte->getKilometerQuadrate();

    # 5) Ermitteln welche Dateien, keine Zuordnungen zu vorhandenen Festpunkten haben
    foreach ($kilometerquadrate AS $kilometerquadrat) {
      # Ausführen für jedes Kilometerquadrad
      # Es werden alle Dateien in dem für das Kiloquad vorgesehenem Verzeichnis mit png und tif Endung gesucht.
      # Es wird geprüft, ob irgend eine Datei im Verzeichnis rumliegt, die sich nicht eindeutig
      # einem Festpunkt zuordnen läßt.
      foreach (glob(PUNKTDATEIPATH.$kilometerquadrat."/*.tif") as $filename) {
        $skizzennummern[]=trim(basename ($filename,".tif"));
      }
    }
    @$this->skizzenohnezuordnung=array_values(array_diff($skizzennummern,$punktnummern));
    $this->festpunkte=$festpunkte;
    $this->titel="Zuordnung Festpunkte zu Einmessungsskizzen";
    $this->main="festpunkteskizzenzuordnung.php";
    $this->output();
  }

  function ordneFestpunktSkizzen() {
  	$_files = $_FILES;
    ####################################################
    # 1) Verschieben von Dateien, die zu Festpunkten zugeordnet waren,
    # aber jetzt neu zu anderen pkz zugeordnet werden sollen (aus oberen Formularteil)
    # Variable $name
    $Festpunkte=new Festpunkte('',$this->pgdatabase);
    if (!is_array($this->formvars['name'])) {
      $this->formvars['name']=array();
    }
    $vonPkz=array_keys($this->formvars['name']);
    $nachNameStern=array_values($this->formvars['name']);
    $anzZuordnungen=count($vonPkz);
    # Zerlegen von nachNameStern in Bestandteile :
    # 45601234/45601234120001.*
    # rhhhrzhz/rhhhrzhzapktnr.* davon sind
    # Pfad: 45601234/45601234120001 (rhhhrzhz/rhhhrzhzapktnr)
    # Kiloquadr: 45601234 (rhhhrzhz)
    # Name: 45601234120001 (rhhhrzhzapktnr)
    # Pkz: 45601234-1-20001 (rhhhrzhz-a-pktnr)
    for ($i=0;$i<$anzZuordnungen;$i++) {
      # extrahieren von Kilometerquadrat, Dateiname und Pfad zur Datei an Hand des Punktkennzeichens
      $vonKiloquad[$i]=substr(trim($vonPkz[$i]),0,-8);
      $vonName[$i]=str_replace('-','',trim($vonPkz[$i]));
      $vonPfad[$i]=$vonKiloquad[$i].'/'.$vonName[$i];
      # extrahieren des Punktkennzeichen, Dateinamen und Pfad zum neuen Speicherort der Datei
      $nachName[$i]=basename(substr($nachNameStern[$i],0,-2));
      $nachKiloquad[$i]=dirname($nachNameStern[$i]); # entnommen aus Verzeichnisnamen
      $nachPfad[$i]=$nachKiloquad[$i].'/'.$nachName[$i];
      $nachPkz[$i]=$nachKiloquad[$i].'-'.substr($filebasename,-6,-5).'-'.substr($nachName[$i],-5);
      # Vergleich, ob in einem Feld Änderungen vorgenommen wurden.
      if ($vonPfad[$i]!=$nachPfad[$i]) {
        if ($Festpunkte->is_valid_pfad($nachPfad[$i])) {
          echo '<br>Verschiebe '.$vonPfad[$i].'.* nach: '.$nachPfad[$i].'.*';
          $move['von']=PUNKTDATEIPATH.$vonPfad[$i];
          $move['nach']=PUNKTDATEIPATH.$nachPfad[$i];
          $moveListe[]=$move;
        }
      } # Ende Behandlung von Änderungen
    } # Ende der Schleife zum Auswählen der zu verschiebenden Dateien
    # Verschieben der zu verändernden Datein an neunen Ort
    $Festpunkte->moveFiles($moveListe);

    #################################################################
    # 2) Kopieren der hochgeladenen Dateien an die Speicherplätze, die den PKZ entsprechen.
    # Variable $_FILES
    $uploadedFiles=array_values($_files);
    $uploadedFilesPKZ=array_keys($_files);
    $anzUploadedFiles=count($uploadedFiles);
    for ($i=0;$i<$anzUploadedFiles;$i++) {
      if ($uploadedFiles[$i]['tmp_name']!='') {
        # Zusammensetzung der Dateinamen
        $pkz=substr(trim($uploadedFilesPKZ[$i]),9);
        $ext=substr(trim($uploadedFilesPKZ[$i]),0,3);
        $nachDatei=PUNKTDATEIPATH.$Festpunkte->pkz2pfad($pkz).'.'.$ext;
        if (move_uploaded_file($uploadedFiles[$i]['tmp_name'],$nachDatei)) {
          echo '<br>Lade '.$uploadedFiles[$i]['tmp_name'].' nach '.$nachDatei.' hoch';
        }
        else {
          echo '<br>Datei: '.$uploadedFiles[$i]['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
    }

    ############################################################
    # 3) Verschieben von Dateien, die vorher nicht zu Punkten zugeordnet werden konnten
    # Variable: $renamefile (untere Tabelle im Formular)
    if (!is_array($this->formvars['renamefile'])) {
      $this->formvars['renamefile']=array();
    }
    $vonPfad=array_keys($this->formvars['renamefile']);
    $nachPfadStern=array_values($this->formvars['renamefile']);
    $anzRenameFile=count($vonPfad);
    unset($moveListe);
    for ($i=0;$i<$anzRenameFile;$i++) {
      # extrahieren von Kilometerquadrat, Dateiname und Pfad zur Datei an Hand des Punktkennzeichens
      $nachPfad[$i]=substr($nachPfadStern[$i],0,-2);
      # Vergleich, ob in einem Feld Änderungen vorgenommen wurden.
      if ($vonPfad[$i]!=$nachPfad[$i]) {
        if ($Festpunkte->is_valid_pfad($nachPfad[$i])) {
          echo '<br>Verschiebe '.$vonPfad[$i].'.* nach: '.$nachPfad[$i].'.*';
          $move['von']=PUNKTDATEIPATH.$vonPfad[$i];
          $move['nach']=PUNKTDATEIPATH.$nachPfad[$i];
          $moveListe[]=$move;
        }
      } # Ende Behandlung von Änderungen
    } # Ende der Schleife zum Auswählen der zu verschiebenden Dateien
    # Verschieben der zu verändernden Datein an neunen Ort
    $Festpunkte->moveFiles($moveListe);

    ####################################
    # 4 Verschieben der im Formular ausgewählten nicht zuordbaren Dateinen ins Archiv.
    if (!is_array($this->formvars['archivfile'])) {
      $this->formvars['archivfile']=array();
    }
    $archivFile=array_keys($this->formvars['archivfile']);
    $anzArchivFile=count($archivFile);
    if ($anzArchivFile>0) {
      # Anlegen des archiv-Verzeichnisses, wenn noch nicht vorhanden
      if (!file_exists(PUNKTDATEIARCHIVPATH)) {
        mkdir(PUNKTDATEIARCHIVPATH);
      }
    }
    for ($i=0;$i<$anzArchivFile;$i++) {
      $part=explode('/',$archivFile[$i]);
      $kiloquad=trim($part[0]);
      if (!file_exists(PUNKTDATEIARCHIVPATH.$kiloquad)) {
        mkdir(PUNKTDATEIARCHIVPATH.$kiloquad);
      }
      $archiv['von']=PUNKTDATEIPATH.$archivFile[$i];
      $archiv['nach']=PUNKTDATEIARCHIVPATH.$archivFile[$i];
      $archivListe[$i]=$archiv;
    }
    # Verschieben der zu verändernden Datein ins Archiv
    $Festpunkte->moveFiles($archivListe);

    ####################################
    # 5 Löschen der im Formular ausgewählten nicht zuordbaren Dateinen.
    if (!is_array($this->formvars['deletefile'])) {
      $this->formvars['deletefile']=array();
    }
    $deleteFile=array_keys($this->formvars['deletefile']);
    $anzDeleteFile=count($deleteFile);
    for ($i=0;$i<$anzDeleteFile;$i++) {
      $deleteFileAbs=PUNKTDATEIPATH.$deleteFile[$i];
      if (file_exists($deleteFileAbs.'.tif')) {
        echo '<br>Lösche Datei: '.$deleteFileAbs.'.tif';
        unlink($deleteFileAbs.'.tif');
      }
      if (file_exists($deleteFileAbs.'.png')) {
        echo '<br>Lösche Datei: '.$deleteFileAbs.'.png';
        unlink($deleteFileAbs.'.png');
      }
    }
    ###################################
    $this->showFestpunkteSkizze();
  }

  function uebernehmeFestpunkte() {
    $Festpunkte=new Festpunkte(PUNKTDATEIPATH.PUNKTDATEINAME,$this->pgdatabase);
    $ret=$Festpunkte->uebernehmen();
    if ($ret[0]) { # Fehler bei der Aktualisierung der Festpunkte
      $this->Fehlermeldung=$ret[1];
    }
    else {
      $this->Protokoll=$ret[1];
    }
    $this->Festpunkte=$Festpunkte;
    $this->titel='Übernahme der Festpunkte';
    $this->main="aktualisierungfestpunkte.php";
    $this->output();
  }

  function festpunkteZuAuftragFormular() {
    $this->titel='Festpunkte zum Auftrag Hinzufügen';
    $this->main='festpunktezuauftragformular.php';
    $this->pkz=array_keys($this->formvars['pkz']);
    $this->anzPunkte=count($this->pkz);
    $this->FormObjAntr_nr=$this->getFormObjAntr_nr('');
    $this->FormObjAntr_nr->select['name']='antr_selected';
    $this->output();
  }

  function festpunkteZuAuftragSenden() {
    # Prüfen, ob eine Auftragsnummer mit übergeben wurde
    if ($this->formvars['antr_selected']=='') {
      $this->Fehlermeldung='Sie müssen erst eine Antragsnummer angeben.';
      $this->festpunkteZuAuftragFormular();
    }
    $pkz=array_keys($this->formvars['pkz']);
    $anzPunkte=count($pkz);
    $auftrag=new antrag($this->formvars['antr_selected'],$this->pgdatabase);
    $anzPunkteAdd=0;
    for ($i=0;$i<$anzPunkte;$i++) {
      $ret=$auftrag->addFestpunkt($pkz[$i]);
      if (!$ret[0]) {
        if (pg_affected_rows($ret[1])) {
          $anzPunkteAdd++;
        }
      }
    }
    $this->Meldung ='Es wurden '.$anzPunkteAdd.' Festpunkte zum Auftrag ';
    $this->Meldung.=$this->formvars['antr_selected'].' hinzugefügt!';
    $this->Antraege_Anzeigen();
  }

  function festpunkteInKVZschreiben() {
    #19.06.2008, H.Riedel; Abfrage, ob Antrag ausgewaehlt wurde
    if ($this->formvars['antr_selected']=='') {
      $this->Fehlermeldung= '<br>Wählen Sie eine Antragsnummer aus!';
    }
    else {
      $festpunkte=new Festpunkte('',$this->pgdatabase);
      $ret=$festpunkte->createKVZdatei($this->formvars['antr_selected'], $this->formvars['pkz']);
      if ($ret[0]) {
        $this->Fehlermeldung=$ret[1];
      }
      else {
        $this->Meldung=$ret[1];
        $this->datei = $ret[2];
      }
    }
  }


  function aktualisiereFestpunkte() {
    if (is_file(PUNKTDATEIPATH.PUNKTDATEINAME)) {
      # Datei ist vorhanden, Einlesen und Aufbereiten der Punkte in Datenbank
      $Festpunkte=new Festpunkte(PUNKTDATEIPATH.PUNKTDATEINAME,$this->pgdatabase);
      $ret=$Festpunkte->aktualisieren();
      if ($ret[0]) { # Fehler bei der Aktualisierung der Festpunkte
        $this->Fehlermeldung=$ret[1];
      }
      else {
        $this->Protokoll=$ret[1];
      }
    }
    else {
      $this->Fehlermeldung='Die Datei '.PUNKTDATEIPATH.PUNKTDATEINAME.' existiert nicht auf dem Server.';
    }
    $this->Festpunkte=$Festpunkte;
    $this->titel='Aktualisierung der Festpunkte';
    $this->main="aktualisierungfestpunkte.php";
    $this->output();
  }

  function exportMapToPDF() {
    # Abfrage der aktuellen Karte
    $this->loadMap('DataBase');
    $this->map->selectOutputFormat('jpeg');
    # Zeichnen der Karte
    $this->drawMap();
    # Einbinden der PDF Klassenbibliotheken
    include (PDFCLASSPATH."class.ezpdf.php");
    # Erzeugen neue Dokument-Klasse
    $Document=new Document($this->database);
    $this->Docu=$Document;

    # Erzeugen neue pdf-Klasse
    $pdf=new Cezpdf();
    $pdf->selectFont(PDFCLASSPATH.'fonts/Helvetica-Bold.afm');

    $massstab = explode('.', $this->map_scaledenom);
    $row = 712;

    $pdf->addText(50,$row,14,utf8_decode('Gemeinde: '.$this->Lagebezeichung['gemeindename'].'   Gemarkung: '.$this->Lagebezeichung['gemkgname'].'   Flur: '.$this->Lagebezeichung['flur']));
    $pdf->addText(50,$row-30,14,utf8_decode('Maßstab ca. 1:'.$massstab[0]));
    $pdf->addJpegFromFile(IMAGEPATH.basename($this->img['hauptkarte']),50,100,500);
    $this->pdf=$pdf;
    $this->mime_type='pdf';

    $dateipfad=IMAGEPATH;
    $currenttime = date('Y-m-d_H_i_s',time());
    $name = str_replace('ä', 'ae', $this->user->Name);
    $name = str_replace('ü', 'ue', $name);
    $name = str_replace('ö', 'oe', $name);
    $name = str_replace('Ä', 'Ae', $name);
    $name = str_replace('Ü', 'Ue', $name);
    $name = str_replace('Ö', 'Oe', $name);
    $name = str_replace('ß', 'ss', $name);
    $dateiname = $name.'-'.$currenttime.'_'.rand(0,99999999).'.pdf';
    $this->outputfile = $dateiname;
    $fp=fopen($dateipfad.$dateiname,'wb');
    fwrite($fp,$this->pdf->ezOutput());
    fclose($fp);

    $this->output();
  }

  function export_flurst_csv(){
		$this->attribute_selections = $this->user->rolle->get_csv_attribute_selections();
    $this->attribute = explode(';', $this->formvars['attributliste']);
    $this->main = 'export_flurstuecke_csv.php';
   	$this->titel = $this->formvars['formnummer'].'-CSV-Export';
    $this->output();
  }
  
  function export_flurst_csv_auswahl_speichern(){
  	$this->user->rolle->save_csv_attribute_selection($this->formvars['name'], $this->formvars['attributes']);
  	$this->formvars['selection'] = $this->formvars['name'];
  	$this->export_flurst_csv_auswahl_laden();
  }
  
  function export_flurst_csv_auswahl_laden(){
  	$this->selection = $this->user->rolle->get_csv_attribute_selection($this->formvars['selection']);
  	$attributes = explode('|', $this->selection['attributes']);
  	for($i = 0; $i < count($attributes); $i++){
  		$this->formvars[$attributes[$i]] = 'true';
  	}
  	$this->export_flurst_csv();
  }
  
  function export_flurst_csv_auswahl_loeschen(){
  	$this->user->rolle->delete_csv_attribute_selection($this->formvars['selection']);
  	$this->export_flurst_csv();
  }

  function export_flurst_csv_exportieren(){
    $flurstuecke = explode(';', $this->formvars['FlurstKennz']);
    $ret = $this->Stelle->getFlurstueckeAllowed($flurstuecke, $this->pgdatabase);
    if ($ret[0]) {
      $this->Fehlermeldung=$ret[1];
      showAlert($ret[1]);
    }
    else {
      $flurstuecke = $ret[1];
      $ALB = new ALB($this->pgdatabase);
      $currenttime=date('Y-m-d H:i:s',time());
      switch ($this->formvars['formnummer']){
      	case 'Flurstück' : {
      		$ALB->export_flurst_csv($flurstuecke, $this->formvars);
      		$this->user->rolle->setConsumeCSV($currenttime,'Flurstück',count($flurstuecke));
      	}break;
      	case 'Nutzungsarten' : {
      		$ALB->export_nutzungsarten_csv($flurstuecke, $this->formvars);
      		$this->user->rolle->setConsumeCSV($currenttime,'Nutzungsarten',count($flurstuecke));
      	}break;
      	case 'Eigentümer' : {
      		$ALB->export_eigentuemer_csv($flurstuecke, $this->formvars);
      		$this->user->rolle->setConsumeCSV($currenttime,'Eigentümer',count($flurstuecke));
      	}break;
      	case 'Klassifizierung' : {
      		$ALB->export_klassifizierung_csv($flurstuecke, $this->formvars);
      		$this->user->rolle->setConsumeCSV($currenttime,'Klassifizierung',count($flurstuecke));
      	}break;
      }
    }
  }
 
  
  function createMapPDF($frame_id, $preview, $fast = false) {
    # Abfrage der aktuellen Karte
    if($this->formvars['post_map_factor']){
      $this->map_factor = $this->formvars['post_map_factor'];
    }
    elseif($this->formvars['map_factor'] != ''){
    	$this->map_factor = $this->formvars['map_factor'];
    }
    else{
      $this->map_factor = MAPFACTOR;
    }
    # Wenn in der Anfrage für loadmapsource POST übergeben wurde, werden alle Kartenparameter aus formvars entnommen
    if($this->formvars['loadmapsource']){
      $this->loadMap($this->formvars['loadmapsource']);
    }
    else{
      $this->loadMap('DataBase');
    }
    # Erzeugen neue Dokument-Klasse
    # Enthält das Template für den Druchrahmen und alle Einstellungen zur Ausgestalltung
    $Document=new Document($this->database);
    $this->Docu=$Document;
    $this->Docu->activeframe = $this->Docu->load_frames(NULL, $frame_id);

    # Karte
    if($this->map->selectOutputFormat('jpeg_print') == 1){
      $this->map->selectOutputFormat('jpeg');
    }
    if($fast == true){			# schnelle Druckausgabe ohne Druckausschnittswahl, beim Schnelldruck und im Druckrahmeneditor
    	$this->formvars['referencemap'] = 1;
    	$this->formvars['printscale'] = round($this->map_scaledenom);
    	$this->formvars['center_x'] = $this->map->extent->minx + ($this->map->extent->maxx-$this->map->extent->minx)/2;
    	$this->formvars['center_y'] = $this->map->extent->miny + ($this->map->extent->maxy-$this->map->extent->miny)/2;    	
    	$this->formvars['worldprintwidth'] = $this->Docu->activeframe[0]['mapwidth'] * $this->formvars['printscale'] * 0.0003526;
    	$this->formvars['worldprintheight'] = $this->Docu->activeframe[0]['mapheight'] * $this->formvars['printscale'] * 0.0003526;
    }
    #echo $this->formvars['center_x'].'<br>';
    #echo $this->formvars['center_y'].'<br>';
    #echo $this->formvars['worldprintwidth'].'<br>';
    #echo $this->formvars['worldprintheight'].'<br>';
    $breite = $this->formvars['worldprintwidth']/2;
    $höhe = $this->formvars['worldprintheight']/2;

    if($this->formvars['angle'] != 0){
      $diag = sqrt(pow($breite, 2) + pow($höhe, 2));
      $gamma = asin($breite/$diag);
      $alpha = deg2rad(90) - deg2rad(abs($this->formvars['angle'])) - $gamma;
      $bboxwidth = cos($alpha) * $diag;
      $alpha2 = $gamma - deg2rad(abs($this->formvars['angle']));
      $bboxheight = cos($alpha2) * $diag;
      $minx = $this->formvars['center_x'] - $bboxwidth;
      $miny = $this->formvars['center_y'] - $bboxheight;
      $maxx = $this->formvars['center_x'] + $bboxwidth;
      $maxy = $this->formvars['center_y'] + $bboxheight;
      $widthratio = $bboxwidth / $breite;
      $heightratio = $bboxheight / $höhe;
      $this->map->set('width', $this->Docu->activeframe[0]['mapwidth'] * $widthratio * $this->map_factor);
      $this->map->set('height', $this->Docu->activeframe[0]['mapheight'] * $heightratio * $this->map_factor);
    }
    else{
      $minx = $this->formvars['center_x'] - $this->formvars['worldprintwidth']/2;
      $miny = $this->formvars['center_y'] - $this->formvars['worldprintheight']/2;
      $maxx = $this->formvars['center_x'] + $this->formvars['worldprintwidth']/2;
      $maxy = $this->formvars['center_y'] + $this->formvars['worldprintheight']/2;
      $this->map->set('width', $this->Docu->activeframe[0]['mapwidth']*$this->map_factor);
      $this->map->set('height', $this->Docu->activeframe[0]['mapheight']*$this->map_factor);
    }

    # copyright-layer aus dem Mapfile
    @$creditslayer = $this->map->getLayerByName('credits');
    if($creditslayer != false){
      $newcredits = ms_newLayerObj($this->map, $creditslayer);
      $feature = $newcredits->getShape(-1, 0);
      if(MAPSERVERVERSION > 500){
        $feature=$newcredits->getFeature(0,-1);
      }
      else{
        $feature=$newcredits->getShape(-1, 0);
      }
      $line = $feature->line(0);
      $point = $line->point(0);
      $point->setXY(0, $this->map->height - 2);
      $newcredits->addFeature($feature);
    }

    $this->map->setextent($minx,$miny,$maxx,$maxy);
    
    if(MAPSERVERVERSION >= 600 ) {
    		$this->map_scaledenom = $this->map->scaledenom;
    	}
    	else {
    		$this->map_scaledenom = $this->map->scale;
	}
    
    $currenttime=date('Y-m-d H:i:s',time());
    # loggen der Druckausgabe
    if($preview == true){
      $this->user->rolle->setConsumeActivity($currenttime,'print_preview',$this->user->rolle->last_time_id);
    }
    else{
      $this->user->rolle->oGeorefExt->minx = $minx;
      $this->user->rolle->oGeorefExt->miny = $miny;
      $this->user->rolle->oGeorefExt->maxx = $maxx;
      $this->user->rolle->oGeorefExt->maxy = $maxy;
      $this->user->rolle->nImageWidth = $this->map->width;
      $this->user->rolle->nImageHeight = $this->map->height;
      $this->user->rolle->setConsumeActivity($currenttime,'print',$this->user->rolle->last_time_id);
      $this->user->rolle->setConsumeALK($currenttime, $this->Docu->activeframe[0]['id']);
    }

    /**
    * Problem: Es gibt WMS, die trotz der Einstellung EXCEPTIONS=application/vnd.ogc.se_inimage kein Bild mit Fehlermeldung
    * schicken, sondern gar kein Bild bzw. nichts.
    * Der Fall und auch andere Fälle bei denen kein Bild zurück kommt müssen abgefangen werden.
    * 1) Es wird für jeden WMS Layer getestet ob der GetMap Request ein Bild liefert
    * 2) Wennn kein Bild geliefert wird, wird an Stelle der WMS online_url eine url zu einem Proxy gesetzt
    *    der die Fehlermeldung in ein Bild integriert und ausliefert
    * Eingefügt am 19.09.2008 von pk
    
    # Schritt 1)
    $extent=$this->map->extent;
    for ($l=1;$l<=$this->map->numlayers;$l++) {
      $layer=$this->map->getLayer($l);
      if($layer->status == 1 AND $layer->connectiontype == 7 AND $layer->connection!='') {
        $wmsRequestStr=$layer->connection.'&BBOX='.$extent->minx.','.$extent->miny.','.$extent->maxx.','.$extent->maxy.'&WIDTH='.$this->map->width.'&HEIGHT='.$this->map->height;
        if (getimagesize($wmsRequestStr)==false) {
          # Es handelt sich nicht um ein Bild,
          # Schritt 2)
          if (0) {
            echo 'Der Layer <b>'.$layer->name.'</b> kann in der Größe und Auflösung von '.strval(72*$this->map_factor).'dpi nicht für den Druck verwendet werden.';
            echo '<br><font size="-2">Die Anfrage: <a href="'.$wmsRequestStr.'" target="_blank">'.$wmsRequestStr.'</a> liefert kein Bild sondern die folgende Fehlermeldung:</font>';
            echo '<br><b><font color="#FF0000">'.trim(strip_tags(file_get_contents($wmsRequestStr))).'</font></b>';
            echo '<br>Wenden Sie sich an den WMS Anbieter oder drucken Sie die Karte in einem kleineren Format aus.<br><hr><br>';
          }
          $newConnection="http://www.gdi-service.de/wmstileproxy/index.php?online_resource_url=".str_replace("?","&",$layer->connection);
          $layer->set('connection',$newConnection);
        }
      }
    }
*/
		#$this->saveMap('');
		#$this->debug->write("<p>Maßstab des Drucks:".$this->map_scaledenom,4);
    $this->drawMap();

    if($this->formvars['angle'] != 0){
      $angle = -1 * $this->formvars['angle'];
      $image = imagecreatefromjpeg(IMAGEPATH.basename($this->img['hauptkarte']));
      $rotatedimage = imagerotate($image, $angle, 0);
      $width = imagesx($rotatedimage);
      $height = imagesy($rotatedimage);
      $clipwidth = $this->Docu->activeframe[0]['mapwidth']*$this->map_factor;
      $clipheight = $this->Docu->activeframe[0]['mapheight']*$this->map_factor;
      $clipx = ($width - $clipwidth) / 2;
      $clipy = ($height - $clipheight) / 2;
      $clippedimage = imagecreatetruecolor($clipwidth, $clipheight);
      ImageCopy($clippedimage, $rotatedimage, 0, 0, $clipx, $clipy, $clipwidth, $clipheight);
      imagejpeg($clippedimage, IMAGEPATH.basename($this->img['hauptkarte']), 100);
    }

    # Übersichtskarte
    if($this->Docu->activeframe[0]['refmapfile'] AND $this->formvars['referencemap']){
      $refmapfile = DRUCKRAHMEN_PATH.$this->Docu->activeframe[0]['refmapfile'];
      $zoomfactor = $this->Docu->activeframe[0]['refzoom'];
      $this->Docu->referencemap = $this->createReferenceMap($this->Docu->activeframe[0]['refwidth']*$this->map_factor, $this->Docu->activeframe[0]['refheight']*$this->map_factor, $minx,$miny,$maxx,$maxy, $zoomfactor,  $refmapfile);
    }

    # Einbinden der PDF Klassenbibliotheken
    include (PDFCLASSPATH."class.ezpdf.php");
    switch ($this->Docu->activeframe[0]['format']) {
    	case "A5hoch" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A5', 'portrait');
        $this->Docu->height = 595;
      } break;

      case "A5quer" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A5', 'landscape');
        $this->Docu->height = 420;
      } break;
    	
      case "A4hoch" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf();
        $this->Docu->height = 842;
      } break;

      case "A4quer" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A4', 'landscape');
        $this->Docu->height = 595;
      } break;

      case "A3hoch" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A3', 'portrait');
        $this->Docu->height = 1191;
      } break;

      case "A3quer" : {
       # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A3','landscape');
        $this->Docu->height = 842;
      } break;

      case "A2hoch" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A2', 'portrait');
        $this->Docu->height = 1684;
      } break;

      case "A2quer" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A2', 'landscape');
        $this->Docu->height = 1191;
      } break;

      case "A1hoch" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A1', 'portrait');
        $this->Docu->height = 2384;
      } break;

      case "A1quer" : {
       # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A1','landscape');
        $this->Docu->height = 1684;
      } break;

      case "A0hoch" : {
        # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A0', 'portrait');
        $this->Docu->height = 3370;
      } break;

      case "A0quer" : {
       # Erzeugen neue pdf-Klasse
        $pdf=new Cezpdf('A0','landscape');
        $this->Docu->height = 2384;
      } break;
    }

    # Wasserzeichen hinzufügen
    if($this->Docu->activeframe[0]['watermark'] != ''){
      $this->addwatermark($this->Docu->activeframe[0]);
    }

    # Lagebezeichnung
    if(LAGEBEZEICHNUNGSART == 'Flurbezeichnung'){
	    $flur = new Flur('','','',$this->pgdatabase);
	    $bildmitte['rw']=$this->formvars['center_x'];
	    $bildmitte['hw']=$this->formvars['center_y'];
	    $this->lagebezeichnung = $flur->getBezeichnungFromPosition($bildmitte, $this->user->rolle->epsg_code);
    }

    # Hinzufügen des Hintergrundbildes als Druckrahmen
    $pdf->addJpegFromFile(DRUCKRAHMEN_PATH.basename($this->Docu->activeframe[0]['headsrc']),$this->Docu->activeframe[0]['headposx'],$this->Docu->activeframe[0]['headposy'],$this->Docu->activeframe[0]['headwidth']);

    # Hinzufügen der vom MapServer produzierten Karte
    $pdf->addJpegFromFile(IMAGEPATH.basename($this->img['hauptkarte']),$this->Docu->activeframe[0]['mapposx'],$this->Docu->activeframe[0]['mapposy'],$this->Docu->activeframe[0]['mapwidth'], $this->Docu->activeframe[0]['mapheight']);

    # Hinzufügen der Referenzkarte, wenn eine angegeben ist.
    if($this->Docu->activeframe[0]['refmapfile'] AND $this->formvars['referencemap']){
      $pdf->addJpegFromFile(DRUCKRAHMEN_PATH.basename($this->Docu->activeframe[0]['refmapsrc']),$this->Docu->activeframe[0]['refmapposx'],$this->Docu->activeframe[0]['refmapposy'],$this->Docu->activeframe[0]['refmapwidth']);
      $pdf->addJpegFromFile(IMAGEPATH.basename($this->Docu->referencemap),$this->Docu->activeframe[0]['refposx'],$this->Docu->activeframe[0]['refposy'],$this->Docu->activeframe[0]['refwidth'], $this->Docu->activeframe[0]['refheight']);
    }
    $pdf->selectFont($this->Docu->activeframe[0]['font_date']);
    $pdf->addText($this->Docu->activeframe[0]['dateposx'],$this->Docu->activeframe[0]['dateposy'],$this->Docu->activeframe[0]['datesize'],date("d.m.Y"));
    $pdf->selectFont($this->Docu->activeframe[0]['font_scale']);
    $pdf->addText($this->Docu->activeframe[0]['scaleposx'],$this->Docu->activeframe[0]['scaleposy'],$this->Docu->activeframe[0]['scalesize'],'1: '.$this->formvars['printscale']);
    $pdf->selectFont($this->Docu->activeframe[0]['font_oscale']);
    $pdf->addText($this->Docu->activeframe[0]['oscaleposx'],$this->Docu->activeframe[0]['oscaleposy'],$this->Docu->activeframe[0]['oscalesize'],'1:xxxx');
    $pdf->selectFont($this->Docu->activeframe[0]['font_gemarkung']);
    $pdf->addText($this->Docu->activeframe[0]['gemarkungposx'],$this->Docu->activeframe[0]['gemarkungposy'],$this->Docu->activeframe[0]['gemarkungsize'],utf8_decode('Gemarkung: '.$this->lagebezeichnung[1]['gemkgschl'].' / '.$this->lagebezeichnung[1]['gemkgname']));
    $pdf->selectFont($this->Docu->activeframe[0]['font_flur']);
    $pdf->addText($this->Docu->activeframe[0]['flurposx'],$this->Docu->activeframe[0]['flurposy'],$this->Docu->activeframe[0]['flursize'],utf8_decode('Flur: '.$this->lagebezeichnung[1]['flur']));

    # Freie Graphiken
    for($j = 0; $j < count($this->Docu->activeframe[0]['bilder']); $j++){
      $bild=$this->Docu->activeframe[0]['bilder'][$j];
      #var_dump($bild);
      if ($bild['height']>0) {
        $pdf->addJpegFromFile(GRAPHICSPATH.'custom/'.$bild['src'],$bild['posx'],$bild['posy'],$bild['width'],$bild['height']);
      }
      else {
        $pdf->addJpegFromFile(GRAPHICSPATH.'custom/'.$bild['src'],$bild['posx'],$bild['posy'],$bild['width']);
      }
    }

    # Freitexte
    for($j = 0; $j < count($this->Docu->activeframe[0]['texts']); $j++){
      $pdf->selectFont($this->Docu->activeframe[0]['texts'][$j]['font']);
      if($this->Docu->activeframe[0]['texts'][$j]['text'] == '' AND $this->formvars['freetext'.$this->Docu->activeframe[0]['texts'][$j]['id']] != ''){    // ein Freitext hat keinen Text aber in der Druckausschnittswahl wurde ein Text vom Nutzer eingefügt
        $this->formvars['freetext'.$this->Docu->activeframe[0]['texts'][$j]['id']] = str_replace(chr(10), ';', $this->formvars['freetext'.$this->Docu->activeframe[0]['texts'][$j]['id']]);
        $this->formvars['freetext'.$this->Docu->activeframe[0]['texts'][$j]['id']] = str_replace(chr(13), '', $this->formvars['freetext'.$this->Docu->activeframe[0]['texts'][$j]['id']]);
        $this->Docu->activeframe[0]['texts'][$j]['text'] = $this->formvars['freetext'.$this->Docu->activeframe[0]['texts'][$j]['id']];
      }
      $freitext = explode(';', $this->substituteFreitext($this->Docu->activeframe[0]['texts'][$j]['text']));
      $anzahlzeilen = count($freitext);
      $alpha = $this->Docu->activeframe[0]['texts'][$j]['angle'];
      for($i = 0; $i < $anzahlzeilen; $i++){
        $h = $i * $this->Docu->activeframe[0]['texts'][$j]['size'] * 1.25;
        $a = sin(deg2rad($alpha)) * $h;
        $b = cos(deg2rad($alpha)) * $h;
        $posx = $this->Docu->activeframe[0]['texts'][$j]['posx'] + $a;
        $posy = $this->Docu->activeframe[0]['texts'][$j]['posy'] - $b;
        
      	if($posx < 0){		# rechtsbündig
      		$posx = $pdf->ez['pageWidth'] + $posx;
      		$justification = 'right';
      		$orientation = 'left';
      		$data = array(array(1 => utf8_decode($freitext[$i])));
      		$pdf->ezSetY($posy+$this->Docu->activeframe[0]['texts'][$j]['size']);
	      	$pdf->ezTable($data, NULL, NULL, 
	      	array('xOrientation'=>$orientation, 
								'xPos'=>$posx, 
								#'width'=>$this->layout['elements'][$attributes['name'][$j]]['width'], 
								#'maxWidth'=>$this->layout['elements'][$attributes['name'][$j]]['width'], 
								'fontSize' => $this->Docu->activeframe[0]['texts'][$j]['size'], 
								'showHeadings'=>0, 
								'shaded'=>0, 
								'cols'=>array(1 => array('justification'=>$justification)),
								'showLines'=>0
								)
	      	);
				}
				else{
        	$pdf->addText($posx,$posy,$this->Docu->activeframe[0]['texts'][$j]['size'],utf8_decode($freitext[$i]), -1 * $alpha);
				}
      }
    }
    

    # Legende
    if($this->Docu->activeframe[0]['legendsize'] > 0){
      $legend = $this->createlegend($this->Docu->activeframe[0]['legendsize']);
      $pdf->addJpegFromFile(IMAGEPATH.basename($legend['name']),$this->Docu->activeframe[0]['legendposx'],$this->Docu->activeframe[0]['legendposy'],$legend['width']/$this->map_factor);
    }
    
    # Nutzer
    if($this->Docu->activeframe[0]['usersize'] > 0){
      $pdf->selectFont($this->Docu->activeframe[0]['font_user']);
    	$pdf->addText($this->Docu->activeframe[0]['userposx'],$this->Docu->activeframe[0]['userposy'],$this->Docu->activeframe[0]['usersize'], utf8_decode('Stelle: '.$this->Stelle->Bezeichnung.', Nutzer: '.$this->user->Name));
    }

    # Nordpfeil
    if($this->Docu->activeframe[0]['arrowposx'] != 0){
      $arrow_start = rotate(array(0, -1*$this->Docu->activeframe[0]['arrowlength']/2), -1*$this->formvars['angle']);
      $arrow_end = rotate(array(0, $this->Docu->activeframe[0]['arrowlength']/2), -1*$this->formvars['angle']);
      $arrow_base_length = $this->Docu->activeframe[0]['arrowlength'] * 0.375;
      $arrow_head_length = $this->Docu->activeframe[0]['arrowlength'] * 0.4625;
      $arrow_head_width = $this->Docu->activeframe[0]['arrowlength'] * 0.1125;
      $pdf->setLineStyle(0.6,'round');
      $pdf->line($this->Docu->activeframe[0]['arrowposx'] + $arrow_start[0], $this->Docu->activeframe[0]['arrowposy'] + $arrow_start[1], $this->Docu->activeframe[0]['arrowposx'] + $arrow_end[0], $this->Docu->activeframe[0]['arrowposy'] + $arrow_end[1]);
      $pdata = translate(rotate(array(0,$this->Docu->activeframe[0]['arrowlength']/2-$arrow_base_length, -1*$arrow_head_width/2,$this->Docu->activeframe[0]['arrowlength']/2-$arrow_head_length,0,$this->Docu->activeframe[0]['arrowlength']/2), -1*$this->formvars['angle']),$this->Docu->activeframe[0]['arrowposx'],$this->Docu->activeframe[0]['arrowposy']);
      $pdf->polygon($pdata,3);
      $pdf->polygon($pdata,3,1);
      $pdata = translate(rotate(array(0,$this->Docu->activeframe[0]['arrowlength']/2-$arrow_base_length, $arrow_head_width/2,$this->Docu->activeframe[0]['arrowlength']/2-$arrow_head_length,0,$this->Docu->activeframe[0]['arrowlength']/2), -1*$this->formvars['angle']),$this->Docu->activeframe[0]['arrowposx'],$this->Docu->activeframe[0]['arrowposy']);
      $pdf->polygon($pdata,3);
      $pdf->setColor(1,1,1);
      $pdf->polygon($pdata,3,1);
    }
    
    # variable Freitexte
        for($j = 1; $j <= $this->formvars['last_freetext_id']; $j++){
          $pdf->selectFont(PDFCLASSPATH.'fonts/Helvetica.afm');
          if(strpos($this->Docu->activeframe[0]['format'], 'quer') !== false)$height = 420;			# das ist die Höhe des Vorschaubildes
          else $height = 842;																																		# das ist die Höhe des Vorschaubildes
          $ratio = $height/$this->Docu->height;
          if($this->formvars['freetext'.$j] != ''){      
          	$posx = ($this->formvars['freetext_posx'.$j]+1)/$ratio;
          	$posy = ($this->formvars['freetext_posy'.$j]+1-$height)/$ratio*-1;
          	$boxwidth = ($this->formvars['freetext_width'.$j]+6)/$ratio;
          	$boxheight = ($this->formvars['freetext_height'.$j]+8)/$ratio;
          	$pdf->setColor(1,1,1);
          	$pdf->filledRectangle($posx, $posy-$boxheight, $boxwidth, $boxheight);
          	$pdf->setColor(0,0,0);
          	$pdf->Rectangle($posx, $posy-$boxheight, $boxwidth, $boxheight);
          
            $this->formvars['freetext'.$j] = str_replace(chr(10), ';', $this->formvars['freetext'.$j]);
            $this->formvars['freetext'.$j] = str_replace(chr(13), '', $this->formvars['freetext'.$j]);
          	$freitext = explode(';', $this->formvars['freetext'.$j]);
          	$anzahlzeilen = count($freitext);
          	for($i = 0; $i < $anzahlzeilen; $i++){
          		$h = $i * 12 * 1.25;
           		$pdf->addText($posx+4,$posy-$h-14,12,utf8_decode($freitext[$i]), 0);
          	}      	
          }
    }
    
    $this->pdf=$pdf;

    $dateipfad=IMAGEPATH;
    $currenttime = date('Y-m-d_H_i_s',time());
    $name = umlaute_umwandeln($this->user->Name);    
    $dateiname = $name.'-'.$currenttime.'.pdf';
    $this->outputfile = $dateiname;
    $fp=fopen($dateipfad.$dateiname,'wb');
    fwrite($fp,$this->pdf->ezOutput());
    fclose($fp);

    if($preview == true){
      exec(IMAGEMAGICKPATH.'convert -density 300x300 '.$dateipfad.$dateiname.' -resize 595 '.$dateipfad.$name.'-'.$currenttime.'.jpg');
      #echo IMAGEMAGICKPATH.'convert -density 300x300  '.$dateipfad.$dateiname.' -resize 595 '.$dateipfad.$name.'-'.$currenttime.'.jpg';
      return TEMPPATH_REL.$name.'-'.$currenttime.'.jpg';
    }
  }
  
  function substituteFreitext($text){
  	$text = str_replace('$stelle', $this->Stelle->Bezeichnung, $text);
  	$text = str_replace('$user', $this->user->Name, $text);
  	return $text;
  }

  function wmsExportSenden() {
    $this->titel='WMS Map-Datei erfolgreich exportiert';
    $this->main="ows_exportiert.php";
    # laden der aktuellen Karteneinstellungen
    $this->formvars['nurAktiveLayerOhneRequires'] = true;
    $this->class_load_level = 2;    # die Klassen von allen Layern laden
    $this->loadMap('DataBase');
    # setzen der WMS-Metadaten
    $this->map->setMetaData("ows_title",$this->formvars['ows_title']);
    $this->map->setMetaData("ows_abstract",$this->formvars['ows_abstract']);
    $bb=$this->map->extent;
    $this->map->setMetaData("wms_extent",$bb->minx.' '.$bb->miny.' '.$bb->maxx.'  '.$bb->maxy);
    $this->map->setMetaData("wms_accessconstraints","none");
    $this->map->setMetaData("ows_contactperson",$this->formvars['ows_contactperson']);
    $this->map->setMetaData("ows_contactorganization",$this->formvars['ows_contactorganization']);
    $this->map->setMetaData("ows_contactelectronicmailaddress",$this->formvars['ows_contactelectronicmailaddress']);
    $this->map->setMetaData("ows_contactposition",OWS_CONTACTPOSITION);
    $this->map->setMetaData("ows_fees",$this->formvars['ows_fees']);
    $this->wms_onlineresource=MAPSERV_CGI_BIN."?map=".WMS_MAPFILE_PATH.$this->formvars['mapfile_name']."&";
    $this->map->setMetaData("wms_onlineresource",$this->wms_onlineresource);
    $this->map->setMetaData("ows_srs",OWS_SRS);
    $this->saveMap(WMS_MAPFILE_PATH.$this->formvars['mapfile_name']);
    $getMapRequestExample=$this->wms_onlineresource.'request=getMap&VERSION='.SUPORTED_WMS_VERSION;
    $getMapRequestExample.='&layers='.$this->mapDB->Layer[0]['Name'];
    for ($i=1;$i<$this->mapDB->anzLayer;$i++) {
      $getMapRequestExample.=','.$this->mapDB->Layer[$i]['Name'];
    }
    $getMapRequestExample.='&srs=EPSG:'.EPSGCODE;
    $getMapRequestExample.='&bbox='.$this->map->extent->minx.','.$this->map->extent->miny.','.$this->map->extent->maxx.','.$this->map->extent->maxy;
    $getMapRequestExample.='&width='.$this->map->width.'&height='.$this->map->height;
    $this->getMapRequestExample=$getMapRequestExample;
    $this->output();
  }

  function wmsExport() {
    $this->titel='MapService Map-Datei Export';
    $this->main="ows_export.php";
    $this->output();
  }

  function setSize() {
    $this->user->setSize($this->formars['mapsize']);
    $teil=explode('x',$this->formars['mapsize']);
    $nImageWidth=$teil[0];
    $nImageHeight=$teil[1];
    $this->map->set('width',$this->user->rolle->nImageWidth);
    $this->map->set('height',$this->user->rolle->nImageHeight);
    return 1;
  }

  function versiegelungsFlaechenErfassung() {
    # 2006-01-23 pk
    # Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
    if ($this->formvars['oid']=='') {
      $this->titel='Erfassung versiegelter Flächen';
    }
    else {
      $this->titel='Ändern der versiegelten Flächen';
    }
    $this->main="versiegelungsflaechenerfassung.php";
    # Gemeindedaten laden
    $GemObj=new gemeinde(0,$this->pgdatabase);
    $Gemeindeliste=$GemObj->getGemeindeListe(Array(), "g.Gemeindename");
    # Formularobjekt für Gemeinde bilden
    $this->GemFormObj=new FormObject("gemeinde_id","select",$Gemeindeliste["ID"],$this->formvars['gemeinde_id'],$Gemeindeliste["Name"],1,0,0,NULL);
    # aktuellen Kartenausschnitt laden + zeichnen!
    $this->loadMap('DataBase');
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function versiegelungsFlaechenSenden() {
    #echo  "variablen: pathx/y: ".$this->formvars['pathx']." ~ ".$this->formvars['pathy']." ~ vgrad: ".$this->formvars['versiegelungsgrad']." ~ ".$this->formvars['minx']." ~ ".$this->formvars['miny']." ~ scale: ".$this->user->rolle->pixsize."<br/>";
    # objekt erstellen
    $vflaeche=new versiegelungsflaeche($this->pgdatabase);
    # eingeabewerte pruefen:
      $ret=$vflaeche->pruefeEingabedaten($this->formvars['newpathwkt'],$this->formvars['versiegelungsgrad']);
      if ($ret[0]) { # fehlerhafte eingabedaten
        $this->Meldung=$ret[1];
        $this->versiegelungsFlaechenErfassung();
        return;
      }
      else { # eintraege gueltig
        $this->Meldung='';
        # umring generieren:
        $umring = $this->formvars['newpathwkt'];
        $ret=$vflaeche->eintragenNeueFlaeche($umring,$this->formvars['versiegelungsgrad']);
        if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
        }
        else { # eintrag erfolgreich
          $this->formvars['newpath']="";
          $this->formvars['newpathwkt']="";
          $this->formvars['pathwkt']="";
          $this->formvars['firstpoly']="";
          $this->formvars['secondpoly']="";
          $this->formvars['versiegelungsgrad']="";
          showAlert('Eintrag erfolgreich!');
        }
      }
    $this->versiegelungsFlaechenErfassung();
  }

  function aendernBodenRichtWert() {
    # Bodenrichtwertzone aus der Datenbank abfragen
    $layer = $this->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $bodenrichtwertzone=new bodenrichtwertzone($this->pgdatabase, $layer[0]['epsg_code'], $this->user->rolle->epsg_code);
    $ret=$bodenrichtwertzone->getBodenrichtwertzonen($this->formvars['oid']);
    if ($ret[0]) {
      # Fehler bei der Abfrage
      showAlert($ret);
    }
    else {
      # Abfrage war erfolgreich
      # Zoom zum Polygon des Dokumentes
      $this->loadMap('DataBase');
      $this->zoomToBodenrichtwertzone($this->formvars['oid'],20);
      $this->user->rolle->saveSettings($this->map->extent);
      $this->user->rolle->readSettings();
      # Zuweisen der Werte der Zone zum Formular
      $this->formvars=array_merge($this->formvars,$bodenrichtwertzone->zonen[0]);
      $datumteile=explode('-',$this->formvars['datum']);
      $this->formvars['datum']=$datumteile[0];

      $PolygonAsSVG = transformCoordsSVG($this->formvars['svg_umring']);
      $this->formvars['newpath'] = $PolygonAsSVG;
      $this->formvars['newpathwkt'] = $this->formvars['wkt_umring'];
      $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];

      # Bildung der Textposition zur SVG-Ausgabe
      $point_teil=strrpos($this->formvars['wkt_textposition'],'(')+1;
      $point_paar=substr($this->formvars['wkt_textposition'],$point_teil,count($point_teil)-2);
      $point_xy=explode(' ',$point_paar);
      $this->formvars['loc_x']=$point_xy[0];
      $this->formvars['loc_y']=$point_xy[1];
    }
    $this->bodenRichtWertErfassung();
  }

  function bodenRichtWertZoneLoeschen() {
    $layer = $this->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $zone=new bodenrichtwertzone($this->pgdatabase, $layer[0]['epsg_code'], $this->user->rolle->epsg_code);
    $ret=$zone->deleteBodenrichtwertzonen(array($this->formvars['oid']));
    if ($ret[0]) {
      echo 'Bodenrichtwertzone konnte nicht gelöscht werden.<br>'.$ret[1];
    }
    else {
      //echo 'Bodenrichtwertzone mit oid: '.$this->formvars['oid'].' erfolgreich gelöscht.';
    }
    $this->loadMap('DataBase');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    #$this->queryMap();
    $this->output();
  }

  function zoomToBodenrichtwertzone($oid,$border) {
    $layer = $this->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $zone=new bodenrichtwertzone($this->pgdatabase, $layer[0]['epsg_code'], $this->user->rolle->epsg_code);
    $ret=$zone->getBBoxAsRectObj($oid);
    if ($ret[0]) {
      # Fehler bei der Abfrag der BoundingBox
      # Es erfolgt keine Änderung der aktuellen Ausdehnung
    }
    else {
      $rect=$ret[1];
      # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
      # Setzen der neuen Kartenausdehnung.
      $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
    	if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
  }

  function bodenRichtWertErfassung() {
    if ($this->formvars['oid']=='') {
      $this->titel='Bodenrichtwerterfassung';
    }
    else {
      $this->titel='Bodenrichtwertzone Ändern';
    }
    if($this->formvars['go'] == 'Bodenrichtwertformular_Anzeige'){
    	$this->titel='Bodenrichtwertzone Anzeigen';
      $this->formvars['loc_y'] = $this->formvars['loc_x'] = $this->formvars['pathwkt'] = $this->formvars['newpath'] = $this->formvars['newpathwkt'] = '';
    }
    $layer = $this->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $this->formvars['boris_layer_id'] = $layer[0]['Layer_ID'];
    $this->main="bodenrichtwerterfassung_vboris.php";
    $this->loadMap('DataBase');
    $this->Lagebezeichnung = $this->getLagebezeichnung($this->user->rolle->epsg_code);
    if($this->formvars['gemeinde'] == ''){
    	$this->formvars['gemeinde'] = $this->Lagebezeichnung['gemeinde'];
    }
    if($this->formvars['gemarkung'] == ''){
    	$this->formvars['gemarkung'] = $this->Lagebezeichnung['gemkgschl'];
    }
    # Bodenrichtwertzonenobjekt erzeugen
    $bodenrichtwertzone=new bodenrichtwertzone($this->pgdatabase, $layer[0]['epsg_code'], $this->user->rolle->epsg_code);
    # Formularobjekt für Gemeinde bilden
    $GemObj=new gemeinde(0,$this->pgdatabase);
  	if(ALKIS){$Gemeindeliste=$GemObj->getGemeindeListeALKIS(array(), 'bezeichnung');}
    else{$Gemeindeliste=$GemObj->getGemeindeListe(array(), 'g.GemeindeName');}
    $this->GemFormObj=new FormObject("gemeinde","select",$Gemeindeliste["ID"],$this->formvars['gemeinde'],$Gemeindeliste["Name"],1,0,0,158);
    $this->GemFormObj->addJavaScript('onchange', "update_require_attribute('gemarkung', ".$this->formvars['boris_layer_id'].", this.value);");
    # Formularobjekt für Gemarkung bilden
    $GemkgObj = new gemarkung(0,$this->pgdatabase);
  	if(ALKIS){$gemarkungsliste=$GemkgObj->getGemarkungListeALKIS(array($this->formvars['gemeinde']),array(),'gmk.bezeichnung');}
    else{$gemarkungsliste=$GemkgObj->getGemarkungListe(array($this->formvars['gemeinde']),array(),'gmk.GemkgName');}
    $this->GemkgFormObj=new FormObject('gemarkung','select',$gemarkungsliste['GemkgID'],$this->formvars['gemarkung'],$gemarkungsliste['Name'],1,0,0,158);
    
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
    # Spaltenname und from-where abfragen
    if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($this->formvars['layer_id']){
      $data = $this->mapDB->getData($this->formvars['layer_id']);
      $data_explosion = explode(' ', $data);
      $this->formvars['columnname'] = $data_explosion[0];
      $select = $this->mapDB->getSelectFromData($data);
      $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
      if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
        $this->formvars['fromwhere'] .= ' where (1=1)';
      }
    }
    $oldscale=round($this->map_scaledenom);
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveDrawmode($this->formvars['always_draw']);
    }
    elseif($oldscale!=$this->formvars['nScale'] AND $this->formvars['nScale'] != '') {
      $this->scaleMap($this->formvars['nScale']);
    }
    $this->saveMap('');
  	if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
    	$currenttime=date('Y-m-d H:i:s',time());
    	$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    }
    $this->drawMap();
    $this->output();
  }

  function bodenRichtWertFormSenden() {
    # Zusammensetzen der übergebenen Parameter für das Polygon und die Textposition
    #echo 'formvars[loc_x, loc_y]: '.$this->formvars['loc_x'].', '.$this->formvars['loc_x'];
    if ($this->formvars['loc_x']!='' OR $this->formvars['loc_y']!='') {
      $location_x = $this->formvars['loc_x'];
      $location_y = $this->formvars['loc_y'];
      $this->formvars['textposition']="POINT(".$location_x." ".$location_y.")";
      #echo '<br/>formvars[textposition]: '.$this->formvars['textposition'];
    }
    else {
      $this->formvars['textposition']="";
    }
    $this->formvars['umring'] = $this->formvars['newpathwkt'];
    # Bodenrichtwertzonenobjekt erzeugen
    $layer = $this->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $bodenrichtwertzone=new bodenrichtwertzone($this->pgdatabase, $layer[0]['epsg_code'], $this->user->rolle->epsg_code);

		if ($this->formvars['oid']=='') {
			# 2. eintragenNeueZone
			$ret=$bodenrichtwertzone->eintragenNeueZone($this->formvars);
			if ($ret[0]) {
				# 2.1 eintrageung fehlerhaft
				$this->Meldung=$ret[1];
			}
			else {
				#  2.2 eintragung erfolgreich
				$alertmsg='\nBodenrichtwertzone erfolgreich in die Datenbank eingetragen.'.
				$this->formvars['pathx']='';    $this->formvars['loc_x']='';
				$this->formvars['pathy']='';    $this->formvars['loc_y']='';
				$this->formvars['umring']='';   $this->formvars['textposition']='';
			}
		}
		else {
			# 3. aktualisierenZone
			$ret=$bodenrichtwertzone->aktualisierenZone($this->formvars['oid'],$this->formvars);
			if ($ret[0]) {
				# 3.1 eintrageung fehlerhaft
				$this->Meldung=$ret[1];
			}
			else {
				# 3.2 Aktualisierung erfolgreich
				$alertmsg='\nBodenrichtwertzone erfolgreich in die Datenbank aktualisiert.';
			}
		}
    $this->bodenRichtWertErfassung();
  }

  function copyBodenrichtwertzonen() {
    # Bodenrichtwertzonenobjekt erzeugen
    $layer = $this->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $bodenrichtwertzone=new bodenrichtwertzone($this->pgdatabase, $layer[0]['epsg_code'], $this->user->rolle->epsg_code);
    # Abfragen, ob der Vorgang schon bestätigt wurde
    if ($this->formvars['bestaetigung']!='Ja') {
      # nein
      # zum Bestätigungsformular
      $this->commitBodenrichtwertCopy();
    }
    else { # Kopiervorgang wurde bestätigt
      # Starten einer Transaktion
      $bodenrichtwertzone->database->begintransaction();
      $ret=$bodenrichtwertzone->copyZonenToNewStichtag($this->formvars['oldStichtag'],$this->formvars['newStichtag']);
      if ($ret=0) { # Fehler bei der Datenbank aktion
        # Zurückrollen der Transaktion
        $bodenrichtwertzone->database->rollbacktransaction();
        # Zurück zum Auswahlformular
        $this->waehleBodenwertStichtagToCopy();
      }
      else {
        # Anlegen eines neuen Layers für die Bodenrichtwertzonen mit dem neuen Stichtag
        # wenn es ausgewählt wurde
        # Beschließen der Transaktion
        $bodenrichtwertzone->database->committransaction();
        # Starten der letzten Kartenansicht
        # Karteninformationen lesen
        $this->loadMap('DataBase');
        # Karte zeichnen, protokollieren und ausgeben
        $currenttime=date('Y-m-d H:i:s',time());
        $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
        $this->drawMap();
        $this->saveMap('');
        $this->output();
      } # ende kopiervorgang erfolgreich
    } # ende kopiervorgang wurde bestätigt
  } # ende function copyBodenrichtwertzonen()

  function waehleBodenwertStichtagToCopy() {
    $this->main='waehlebodenwertstichtagtocopy.php';
    $this->titel='Kopieren von Bodenrichtwertzonen auf einen neuen Stichtag';
    # Bodenrichtwertzonenobjekt erzeugen
    $layer = $this->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $bodenrichtwertzone=new bodenrichtwertzone($this->pgdatabase, $layer[0]['epsg_code'], $this->user->rolle->epsg_code);
    # Abfragen der bisher vorhandenen Stichtage
    $ret=$bodenrichtwertzone->getStichtage();
    if ($ret[0]) { # Fehler bei der Abfrage der vorhandenen Stichtage

    }
    else { # Stichtage erfolgreich abgefragt
      # Erzeugen des Formularobjektes zur Auswahl der vorhandenen Stichtage
      $this->Stichtagform=new FormObject('oldStichtag','select',$ret[1],$ret[1][0],$ret[1],1,'',0,NULL);
    }
    $this->output();
  }

  function commitBodenrichtwertCopy() {
    # Frage eine Bestätigung für die Aktion ab
    $this->main='bestaetigebodenwertstichtagtocopy.php';
    $this->titel='Bodenrichtwertzonen kopieren';
    $this->output();
  }

  function DokumenteOrdnerPacken(){
    if ($this->formvars['antr_selected']!=''){
      $antrag=new antrag($this->formvars['antr_selected'],$this->pgdatabase);
      if (is_dir(RECHERCHEERGEBNIS_PATH.$antrag->nr)){
        //$result = exec(RECHERCHE_PACK_SKRIPT.' '.$antrag->nr.' '.$antrag->nr.' '.RECHERCHEERGEBNIS_PATH);
        //$result = exec('zip -r '.RECHERCHEERGEBNIS_PATH.$antrag->nr.' '.RECHERCHEERGEBNIS_PATH.$antrag->nr);
        chdir(RECHERCHEERGEBNIS_PATH);
        $result = exec(ZIP_PATH.' -r '.RECHERCHEERGEBNIS_PATH.$antrag->nr.' '.'./'.$antrag->nr);
      }
    }
    $filename = RECHERCHEERGEBNIS_PATH.$antrag->nr.'.zip';
    $tmpfilename = copy_file_to_tmp($filename);
    unlink($filename);
    return $tmpfilename;
  }

  function DokumenteZumAntragInOrdnerZusammenstellen() {
    if ($this->formvars['antr_selected']!=''){
      # Vorbereiten des Pfades für die Speicherung der recherchierten Dokumente
      $antrag=new antrag($this->formvars['antr_selected'],$this->pgdatabase);
      $antrag->clearRecherchePfad();
      # Zusammenstellen der Dokumente der Nachweisverwaltung
      $nachweis=new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
      $ret=$nachweis->getNachw2Antr($this->formvars['antr_selected']);
      if($ret==''){
        $ret=$nachweis->getNachweise($nachweis->nachweise_id,'','','','','','','','multibleIDs','','');
        if ($ret==''){
          $ret=$antrag->DokumenteInOrdnerZusammenstellen($nachweis);
          $msg.=$ret;
        }
      }

      # Zusammenstellen der Einmessungsskizzen der Festpunkte
      $festpunkte=new Festpunkte('',$this->pgdatabase);
      $ret=$festpunkte->getFestpunkte('',array('0','1'),'','','',$this->formvars['antr_selected'],'','pkz');
      if ($ret[0]) {
        $errmsg="Festpunkte konnten nicht abgefragt werden.";
      }
      else {
        $ret=$antrag->EinmessungsskizzenInOrdnerZusammenstellen($festpunkte);
        $msg.=$ret;
      }
      # Schreiben des Koordinatenverzeichnisses der zugeordneten Festpunkte
      $this->festpunkteInKVZschreiben();
    }
    else {
      $ret='Geben Sie bitte die entspechende Antragsnummer an';
    }
    return $ret;
  }

  function nachweisAenderungsformular() {
    #2005-11-25_pk
    # Anzeige des Formulars zum Eintragen neuer/Ändern vorhandener Metadaten zu einem Nachweisdokument
    # (FFR, KVZ oder GN)
    $this->menue='menue.php';
    $this->main='dokumenteneingabeformular.php';
    $this->titel='Dokument überarbeiten';    
    # Nachweisdaten aus Datenbank abfragen
    $nachweis=new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    # abfragen der Dokumentarten
    $this->dokumentarten = $nachweis->getDokumentarten();
    #echo 'Suche nach id:'.$this->formvars['id'];
    $ret=$nachweis->getNachweise($this->formvars['id'],'','','','','','','','bySingleID','',0,0);
    if ($ret!='') {
      # Fehler bei der Abfrage des Nachweises
      # Anzeige des letzten Rechercheergebnisses
      $this->nachweisAnzeige();
      showAlert($ret);
    }
    else {
      # Abfrage war erfolgreich
      $nachweis->document=$nachweis->Dokumente[0];
      # Laden der letzten Karteneinstellung
      $this->loadMap('DataBase');
      
      $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
	    if(!$this->formvars['layer_id']){
	      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
	      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
	    }
	    # Spaltenname und from-where abfragen
	    if($this->formvars['layer_id']){
		    $data = $this->mapDB->getData($this->formvars['layer_id']);
		    $data_explosion = explode(' ', $data);
		    $this->formvars['columnname'] = $data_explosion[0];
		    $select = $this->mapDB->getSelectFromData($data);
		    
		    # order by rausnehmen
		  	$orderbyposition = strpos(strtolower($select), 'order by');
		  	if($orderbyposition !== false){
			  	$select = substr($select, 0, $orderbyposition);
		  	}
		    
		    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
		    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
		      $this->formvars['fromwhere'] .= ' where (1=1)';
		    }
	    }
      
      # Ausführen von Aktionen vor der Anzeige der Karte und der Zeichnung
      if ($this->formvars['CMD']!='') {
        # Es soll navigiert werden
        # Navigieren
        $this->navMap($this->formvars['CMD']);
        $this->user->rolle->saveSettings($this->map->extent);
        $this->user->rolle->readSettings();
      }
      elseif($nachweis->document['wkt_umring'] != ''){
        # Zoom zum Polygon des Dokumentes
        $this->zoomToNachweis($nachweis,10);
        $this->user->rolle->saveSettings($this->map->extent);
        $this->user->rolle->readSettings();
        # Übernahme des Nachweisumrings aus der PostGIS-Datenbank
        $this->formvars['newpath'] = transformCoordsSVG($nachweis->document['svg_umring']);
        $this->formvars['newpathwkt'] = $nachweis->document['wkt_umring'];
        $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
      }
      else{
      	showAlert('Achtung! Nachweis hat noch keine Geometrie!');
      }
      # Zuweisen der Werte des Dokumentes zum Formular
      $this->formvars['flurid']=$nachweis->document['flurid'];
      $this->formvars['stammnr']=$nachweis->document['stammnr'];
      $this->formvars['art']=$nachweis->document['art'];
      $this->formvars['Blattnr']=$nachweis->document['blattnummer'];
      $this->formvars['datum']=$nachweis->document['datum'];
      $this->formvars['VermStelle']=$nachweis->document['vermstelle'];
      $this->formvars['Blattformat']=$nachweis->document['format'];
      $this->formvars['gueltigkeit']=$nachweis->document['gueltigkeit'];
      $this->formvars['Gemeinde']=$nachweis->document['Gemeinde'];
      $this->formvars['Gemarkung']=substr($this->formvars['flurid'],0,6);
      $this->formvars['Flur']=intval(substr($this->formvars['flurid'],6,9));
      $this->formvars['Bilddatei']=NACHWEISDOCPATH.$nachweis->document['link_datei'];
      $this->formvars['andere_art']=$nachweis->document['andere_art'];
      $this->formvars['rissnummer']=$nachweis->document['rissnummer'];
      $this->formvars['fortfuehrung']=$nachweis->document['fortfuehrung'];
      $this->formvars['bemerkungen']=$nachweis->document['bemerkungen'];

      # Abfragen der Gemarkungen
      # 2006-01-26 pk
      $Gemarkung=new gemarkung('',$this->pgdatabase);
      $GemkgListe=$Gemarkung->getGemarkungListe('','','gmk.GemkgName');
      # Erzeugen des Formobjektes für die Gemarkungsauswahl
      $this->GemkgFormObj=new FormObject("Gemarkung","select",$GemkgListe['GemkgID'],$this->formvars['Gemarkung'],$GemkgListe['Bezeichnung'],"1","","",NULL);

      # erzeugen des Formularobjektes für Vermessungsstellen
      $this->FormObjVermStelle=$this->getFormObjVermStelle($this->formvars['VermStelle']);
      $currenttime=date('Y-m-d H:i:s',time());
      $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
      $this->drawMap();
      $this->saveMap('');
      $this->output();
    }
  }

  function Layer2Stelle_EditorSpeichern(){
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->titel='Layereigenschaften stellenbezogen';
    $this->main='layer2stelle_formular.php';
    $Stelle->updateLayer($this->formvars);
    $result = $Stelle->getLayer($this->formvars['selected_layer_id']);
    $stelle_id = $this->formvars['selected_stelle_id'];
    $layer_id = $this->formvars['selected_layer_id'];
    $stellenname = $this->formvars['stellen_name'];
    $this->formvars = $result[0];
    $this->formvars['selected_stelle_id'] = $stelle_id;
    $this->formvars['selected_layer_id'] = $layer_id;
    $this->formvars['stellen_name'] = $stellenname;
    $this->output();
  }

  function Layer2Stelle_Editor(){
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->titel='Layereigenschaften stellenbezogen';
    $this->main='layer2stelle_formular.php';
    $result = $Stelle->getLayer($this->formvars['selected_layer_id']);
    $stelle_id = $this->formvars['selected_stelle_id'];
    $layer_id = $this->formvars['selected_layer_id'];
    $stellenname = $this->formvars['stellen_name'];
    $this->formvars = $result[0];
    $this->formvars['selected_stelle_id'] = $stelle_id;
    $this->formvars['selected_layer_id'] = $layer_id;
    $this->formvars['stellen_name'] = $stellenname;
    $this->output();
  }

  function Layer2Stelle_Reihenfolge(){
    $this->selected_stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->main='layer2stelle_order.php';
    $this->layers = $this->selected_stelle->getLayers(NULL, $this->formvars['order']);
    $this->output();
  }

  function Layer2Stelle_ReihenfolgeSpeichern(){
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->titel='Layer der Stelle '.$Stelle->Bezeichnung;
    $this->main='layer2stelle_order.php';
    $this->layers = $Stelle->getLayers(NULL);
    for($i = 0; $i < count($this->layers['ID']); $i++){
      $this->formvars['selected_layer_id'] = $this->layers['ID'][$i];
      $this->formvars['drawingorder'] = $this->formvars['drawingorder_layer'.$this->layers['ID'][$i]];
      $Stelle->updateLayerdrawingorder($this->formvars);
    }
    $this->layers = $Stelle->getLayers(NULL);
    $this->output();
  }
  
  function layer_export(){
  	# Abfragen aller Layer
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->layerdaten = $mapDB->getall_Layer('Name');
    $this->titel='Layer-Export';
    $this->main='layer_export.php';
    $this->output();
  }
  
  function layer_export_exportieren(){
  	$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
  	$export_layer_ids = explode(', ', $this->formvars['selected_layers']);
  	$this->layer_dumpfile = $mapDB->create_layer_dumpfile($this->database, $export_layer_ids);
  	$this->layer_export();
  }

  function Layereditor() {
    $this->titel='Layer Editor';
    $this->main='layer_formular.php';
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    # Abfragen der Layerdaten wenn eine layer_id zur Änderung selektiert ist
    if ($this->formvars['selected_layer_id'] > 0) {
      $this->classes = $mapDB->read_Classes($this->formvars['selected_layer_id']);
      $save = $this->formvars['selected_layer_id'];
      $this->formvars = $mapDB->get_Layer($this->formvars['selected_layer_id']);
      $this->formvars['selected_layer_id'] = $save;
      # Abfragen der Stellen des Layer
      $this->formvars['selstellen']=$mapDB->get_stellen_from_layer($this->formvars['selected_layer_id']);
    }
    $this->stellen=$this->Stelle->getStellen('Bezeichnung');
    $this->Groups = $mapDB->getall_Groups();
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    $this->output();
  }

  function Layereditor_KlasseLoeschen(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->delete_Class($this->formvars['class_id']);
    $this->Layereditor();
  }

  function Layereditor_KlasseHinzufuegen(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $attrib[0] = '';
    $attrib[1] = $this->formvars['selected_layer_id'];
    $attrib[2] = '';
    $attrib[3] = 1;
    $mapDB->new_Class($attrib);
    $this->Layereditor();
  }
	
  function LayerAnlegen(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
	  if (trim($this->formvars['id'])!='' and $mapDB->id_exists('layer',$this->formvars['id'])) {
		  $table_information = $mapDB->get_table_information($this->Stelle->database->dbName,'layer');
			$this->Meldung = "Die Id: ".$this->formvars['id']." existiert schon. Nächste freie Layer_ID ist ".$table_information['AUTO_INCREMENT'];
		}
		else {
			$this->formvars['selected_layer_id'] = $mapDB->newLayer($this->formvars);
			
			if($this->formvars['connectiontype'] == 6 AND $this->formvars['pfad'] != ''){
				#---------- Speichern der Layerattribute -------------------
				$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
				$path = stripslashes($this->formvars['pfad']);
				$attributes = $mapDB->load_attributes($layerdb, $path);
				$mapDB->save_postgis_attributes($this->formvars['selected_layer_id'], $attributes, $this->formvars['maintable']);
				$mapDB->delete_old_attributes($this->formvars['selected_layer_id'], $attributes);
				#---------- Speichern der Layerattribute -------------------
			}
			
			# Klassen übernehmen (aber als neue Klassen anlegen)
			$name = @array_values($this->formvars['name']);
			$expression = @array_values($this->formvars['expression']);
			$order = @array_values($this->formvars['order']);
			$ID = @array_values($this->formvars['ID']);
			for($i = 0; $i < count($name); $i++){
				$attrib[0] = $name[$i];
				$attrib[1] = $this->formvars['selected_layer_id'];
				$attrib[2] = $expression[$i];
				$attrib[3] = $order[$i];
				$class_id = $mapDB->new_Class($attrib);
				# Styles übernehmen (in u_styles2classes eintragen)
				$styles = $mapDB->read_Styles($ID[$i]);
				for($j = 0; $j < count($styles); $j++){
					$style_id = $mapDB->new_Style($styles[$j]);
					$mapDB->addStyle2Class($class_id, $style_id, $styles[$j]['drawingorder']);
				}
				# Labels übernehmen (in u_labels2classes eintragen)
				$labels = $mapDB->read_Label($ID[$i]);
				for($j = 0; $j < count($labels); $j++){
					$label_id = $mapDB->new_Label($labels[$j]);
					$mapDB->addLabel2Class($class_id, $label_id);
				}
			}
		}
    $this->Layereditor();
  }

  function LayerAendern(){
  	$this->formvars['pfad'] = stripslashes($this->formvars['pfad']);
  	$this->formvars['Data'] = stripslashes($this->formvars['Data']);
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->updateLayer($this->formvars);
    $old_layer_id = $this->formvars['selected_layer_id'];
    if($this->formvars['id'] != ''){
      $this->formvars['selected_layer_id'] = $this->formvars['id'];
    }

		if($this->formvars['connectiontype'] == 6){
			if($this->formvars['connection'] != ''){
				if($this->formvars['pfad'] != ''){
					#---------- Speichern der Layerattribute -------------------
			    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
			    $layerdb->setClientEncoding();
			    $path = $this->formvars['pfad'];
			    $attributes = $mapDB->load_attributes($layerdb, $path);
			    $mapDB->save_postgis_attributes($this->formvars['selected_layer_id'], $attributes, $this->formvars['maintable']);
			    #---------- Speichern der Layerattribute -------------------
				}
				if($this->formvars['pfad'] == '' OR $attributes != NULL){
					$mapDB->delete_old_attributes($this->formvars['selected_layer_id'], $attributes);
				}
			}
			else{
				showAlert('Keine connection angegeben.');
			}
		}
		
    $name = @array_values($this->formvars['name']);
    $expression = @array_values($this->formvars['expression']);
    $order = @array_values($this->formvars['order']);

    # Stellenzuweisung
    $stellen = explode(', ',$this->formvars['selstellen']);
    for($i = 0; $i < count($stellen); $i++){
      $stelle = new stelle($stellen[$i], $this->database);
      $stelle->addLayer(array($this->formvars['selected_layer_id']), 0);
      $users = $stelle->getUser();
      for($j = 0; $j < count($users['ID']); $j++){
        $this->user->rolle->setGroups($users['ID'][$j], array($stellen[$i]), array($this->formvars['selected_layer_id']), 0); # Hinzufügen der Layergruppen der selektierten Layer zur Rolle
        $this->user->rolle->setLayer($users['ID'][$j], array($stellen[$i]), 0); # Hinzufügen der Layer zur Rolle
      }
    }
    # Löschen der in der Selectbox entfernten Stellen
      $layerstellen = $mapDB->get_stellen_from_layer($this->formvars['selected_layer_id']);
      for($i = 0; $i < count($layerstellen['ID']); $i++){
        $found = false;
        for($j = 0; $j < count($stellen); $j++){
          if($stellen[$j] == $layerstellen['ID'][$i]){
            $found = true;
          }
        }
        if($found == false){
          $deletestellen[] = $layerstellen['ID'][$i];
        }
      }
      if($deletestellen != 0){
        for($i = 0; $i < count($deletestellen); $i++){
          $stelle = new stelle($deletestellen[$i], $this->database);
          $stelle->deleteLayer(array($this->formvars['selected_layer_id']));
          $users = $stelle->getUser();
          for($j = 0; $j < count($users['ID']); $j++){
            $this->user->rolle->deleteLayer($users['ID'][$j], array($deletestellen[$i]), array($this->formvars['selected_layer_id']));
            $this->user->rolle->updateGroups($users['ID'][$j],$deletestellen[$i], $this->formvars['selected_layer_id']);
          }
        }
      }
    # /Löschen der in der Selectbox entfernten Stellen

    $this->classes = $mapDB->read_Classes($old_layer_id);
    for($i = 0; $i < count($name); $i++){
      $attrib[0] = $name[$i];
      $attrib[1] = $this->formvars['selected_layer_id'];
      $attrib[2] = $expression[$i];
      $attrib[3] = $order[$i];
      $attrib[4] = $this->classes[$i]['Class_ID'];
      $mapDB->update_Class($attrib);
    }
    $this->Layereditor();
  }

  function LayerLoeschen(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->deleteLayer($this->formvars['selected_layer_id']);
    # auch die Klassen löschen
    $this->classes = $mapDB->read_Classes($this->formvars['selected_layer_id']);
    for($i = 0; $i < count($this->classes); $i++){
      $mapDB->delete_Class($this->classes[$i]['Class_ID']);
    }
    # layer_attributes löschen
    $mapDB->delete_layer_attributes($this->formvars['selected_layer_id']);
    $mapDB->delete_layer_attributes2stelle($this->formvars['selected_layer_id'], $this->Stelle->id);
    # Filter löschen
    $mapDB->delete_layer_filterattributes($this->formvars['selected_layer_id']);

    $layer[] = $this->formvars['selected_layer_id'];
    $stelle[] = $this->Stelle->id;
    $this->Stelle->deleteLayer($layer);
    $this->user->rolle->deleteLayer('', $stelle, $layer);
    $this->LayerAnzeigen();
  }

  function LayerAnzeigen() {
    # Abfragen aller Layer
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    if($this->formvars['order'] == ''){
      $this->formvars['order'] = 'Name';
    }
    $this->layerdaten = $mapDB->getall_Layer($this->formvars['order']);
    $this->titel='Layerdaten';
    $this->main='layerdaten.php';
    $this->output();
  }

  function GenerischeSuche_Suchen(){
		if($this->last_query != ''){
			$this->formvars['selected_layer_id'] = $this->last_query['layer_ids'][0];
		}
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    switch ($layerset[0]['connectiontype']) {
      case MS_POSTGIS : {	  
        $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
        $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
        $layerdb->setClientEncoding();
        $path = $layerset[0]['pfad'];
        $privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
        $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
        $layerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
		    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		   	# $layerset[0]['attributes'] = $mapDB->add_attribute_values($layerset[0]['attributes'], $layerdb, NULL, true); kann weg, weils weiter unten steht

    		# order by rausnehmen
		  	$orderbyposition = strpos(strtolower($newpath), 'order by');
		  	if($orderbyposition !== false){
			  	$layerset[0]['attributes']['orderby'] = ' '.substr($newpath, $orderbyposition);
			  	$newpath = substr($newpath, 0, $orderbyposition);
		  	}
		  	
		  	# group by rausnehmen
				$groupbyposition = strpos(strtolower($newpath), 'group by');
				if($groupbyposition !== false){
					$layerset[0]['attributes']['groupby'] = ' '.substr($newpath, $groupbyposition);
					$newpath = substr($newpath, 0, $groupbyposition);
		  	}
            
        if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
          for($j = 0; $j < count($layerset[0]['attributes']['name']); $j++){
            $layerset[0]['attributes']['privileg'][$j] = '0';
            $layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['name'][$j]] = '0';
          }
        }
        else{
          for($j = 0; $j < count($layerset[0]['attributes']['name']); $j++){
            $layerset[0]['attributes']['privileg'][$j] = $privileges[$layerset[0]['attributes']['name'][$j]];
            $layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['name'][$j]] = $privileges[$layerset[0]['attributes']['name'][$j]];
          }
        }
        
        for($i = 0; $i < count($layerset[0]['attributes']['name']); $i++){
          if($this->formvars['value_'.$layerset[0]['attributes']['name'][$i]] != ''){
            if($this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'LIKE' OR $this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'NOT LIKE'){
              $sql_where .= ' AND LOWER(CAST(query.'.$layerset[0]['attributes']['name'][$i].' AS TEXT)) '.$this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]].' ';
              $sql_where.='LOWER(\''.$this->formvars['value_'.$layerset[0]['attributes']['name'][$i]].'\')';
            }
            else{
              if($this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'IN'){
                $parts = explode('|', $this->formvars['value_'.$layerset[0]['attributes']['name'][$i]]);
                for($j = 0; $j < count($parts); $j++){
                  if(substr($parts[$j], 0, 1) != '\''){$parts[$j] = '\''.$parts[$j];}
                  if(substr($parts[$j], -1) != '\''){$parts[$j] = $parts[$j].'\'';}
                }
                $instring = implode(',', $parts);
                $sql_where .= ' AND LOWER(CAST(query.'.$layerset[0]['attributes']['name'][$i].' AS TEXT)) '.$this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]].' ';
                $sql_where .= '('.strtolower($instring).')';
              }
              else{
                $sql_where .= ' AND query.'.$layerset[0]['attributes']['name'][$i].' '.$this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]].' ';
                $sql_where.='\''.$this->formvars['value_'.$layerset[0]['attributes']['name'][$i]].'\'';
              }
            }
          }
          elseif($this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'IS NULL' OR $this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'IS NOT NULL'){
          	if($layerset[0]['attributes']['type'][$i] == 'bpchar' OR $layerset[0]['attributes']['type'][$i] == 'varchar' OR $layerset[0]['attributes']['type'][$i] == 'text'){
          		if($this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'IS NULL'){
          			$sql_where .= ' AND (query.'.$layerset[0]['attributes']['name'][$i].' '.$this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]].' OR query.'.$layerset[0]['attributes']['name'][$i].' = \'\') ';
          		}
          		else{
          			$sql_where .= ' AND query.'.$layerset[0]['attributes']['name'][$i].' '.$this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]].' AND query.'.$layerset[0]['attributes']['name'][$i].' != \'\' ';
          		}
          	}
          	else{
            	$sql_where .= ' AND query.'.$layerset[0]['attributes']['name'][$i].' '.$this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]].' ';
          	}
          }
          if($this->formvars['value2_'.$layerset[0]['attributes']['name'][$i]] != ''){
            $sql_where.=' AND \''.$this->formvars['value2_'.$layerset[0]['attributes']['name'][$i]].'\'';
          }
          # räumliche Einschränkung
          if($layerset[0]['attributes']['name'][$i] == $layerset[0]['attributes']['the_geom']){
          	if($this->formvars['newpathwkt'] != ''){
							if (strpos(strtolower($this->formvars['newpathwkt']), 'polygon') !== false) {
								# Suche im Suchpolygon
								$sql_where.=' AND st_intersects('.$layerset[0]['attributes']['the_geom'].', (st_transform(st_geomfromtext(\''.$this->formvars['newpathwkt'].'\', '.$this->user->rolle->epsg_code.'), '.$layerset[0]['epsg_code'].')))';  
							}
							if (strpos(strtolower($this->formvars['newpathwkt']), 'point') !== false) {
								# Suche an Punktkoordinaten mit übergebener SRID
								$sql_where.=" AND st_within(st_transform(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$this->formvars['epsg_code']."), ".$layerset[0]['epsg_code']."), ".$layerset[0]['attributes']['the_geom'].")";
							}
						}
          	# Suche nur im Stellen-Extent
            $sql_where.=' AND ('.$layerset[0]['attributes']['the_geom'].' && st_transform(st_geomfromtext(\'POLYGON(('.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->miny.', '.$this->Stelle->MaxGeorefExt->maxx.' '.$this->Stelle->MaxGeorefExt->miny.', '.$this->Stelle->MaxGeorefExt->maxx.' '.$this->Stelle->MaxGeorefExt->maxy.', '.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->maxy.', '.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layerset[0]['epsg_code'].') OR '.$layerset[0]['attributes']['the_geom'].' IS NULL)';
          }
        }
        $distinctpos = strpos(strtolower($newpath), 'distinct');
        if($distinctpos !== false && $distinctpos < 10){
          $pfad = substr(trim($newpath), $distinctpos+8);
          $distinct = true;
        }
        else{
          $pfad = substr(trim($newpath), 7);
        }
        $j = 0;
        foreach($layerset[0]['attributes']['all_table_names'] as $tablename){
          if($layerset[0]['attributes']['oids'][$j]){     # hat Tabelle oids?
            $pfad = $layerset[0]['attributes']['table_alias_name'][$tablename].'.oid AS '.$tablename.'_oid, '.$pfad;
            if($this->formvars['value_'.$tablename.'_oid']){
              $sql_where .= ' AND '.$tablename.'_oid = '.$this->formvars['value_'.$tablename.'_oid'];
            }
          }
          $j++;
        }
        
        # 2008-10-22 sr   Filter zur Where-Klausel hinzugefügt
        if($layerset[0]['Filter'] != ''){
        	$layerset[0]['Filter'] = str_replace('$userid', $this->user->id, $layerset[0]['Filter']);
          $sql_where .= " AND ".$layerset[0]['Filter'];
        }
        
        if($distinct == true){
          $pfad = 'DISTINCT '.$pfad;
        }
        
        # group by wieder einbauen
				if($layerset[0]['attributes']['groupby'] != ''){
					$pfad .= $layerset[0]['attributes']['groupby'];
					$j = 0;
					foreach($layerset[0]['attributes']['all_table_names'] as $tablename){
								if($layerset[0]['attributes']['oids'][$j]){      # hat Tabelle oids?
									$pfad .= ','.$tablename.'_oid ';
								}
								$j++;
					}
  			}
        $sql = "SELECT * FROM (SELECT ".$pfad.") as query WHERE 1=1 ".$sql_where;
                
        # order by 
        if($this->formvars['orderby'.$layerset[0]['Layer_ID']] != ''){									# Fall 1: im GLE soll nach einem Attribut sortiert werden
          $sql_order = ' ORDER BY '.$this->formvars['orderby'.$layerset[0]['Layer_ID']];
        }
        elseif($layerset[0]['attributes']['orderby'] != ''){										# Fall 2: der Layer hat im Pfad ein ORDER BY
        	$sql_order = $layerset[0]['attributes']['orderby'];
        }
        																																						# standardmäßig wird nach der oid sortiert
				$j = 0;
				foreach($layerset[0]['attributes']['all_table_names'] as $tablename){
					if($tablename == $layerset[0]['maintable'] AND $layerset[0]['attributes']['oids'][$j]){      # hat die Haupttabelle oids, dann wird immer ein order by oid gemacht, sonst ist die Sortierung nicht eindeutig
						if($sql_order == '')$sql_order = ' ORDER BY '.$layerset[0]['maintable'].'_oid ';
						else $sql_order .= ', '.$layerset[0]['maintable'].'_oid ';
					}
					$j++;
				}    	
      	        
				if($this->last_query != ''){
					$sql = $this->last_query[$layerset[0]['Layer_ID']]['sql'];
					if($this->formvars['orderby'.$layerset[0]['Layer_ID']] == '')$sql_order = $this->last_query[$layerset[0]['Layer_ID']]['orderby'];
					$this->formvars['anzahl'] = $this->last_query[$layerset[0]['Layer_ID']]['limit'];
					if($this->formvars['offset_'.$layerset[0]['Layer_ID']] == '')$this->formvars['offset_'.$layerset[0]['Layer_ID']] = $this->last_query[$layerset[0]['Layer_ID']]['offset'];
				}
		
        if($this->formvars['embedded_subformPK'] == ''){
        	if($this->formvars['anzahl'] == ''){
	          $this->formvars['anzahl'] = MAXQUERYROWS;
	        }
        	$sql_limit.=' LIMIT '.$this->formvars['anzahl'];
        	if($this->formvars['offset_'.$layerset[0]['Layer_ID']] != ''){
          	$sql_limit.=' OFFSET '.$this->formvars['offset_'.$layerset[0]['Layer_ID']];
        	}
			$this->user->rolle->delete_last_query();
			$this->user->rolle->save_last_query('Layer-Suche_Suchen', $this->formvars['selected_layer_id'], $sql, $sql_order, $this->formvars['anzahl'], $this->formvars['offset_'.$layerset[0]['Layer_ID']]);
        }
		
		$layerset[0]['sql'] = $sql;
    
        #echo $sql;
        $ret=$layerdb->execSQL($sql.$sql_order.$sql_limit,4, 0);
        if (!$ret[0]) {
          while ($rs=pg_fetch_array($ret[1])) {
            $layerset[0]['shape'][]=$rs;
          }
          # Anzahl der Datensätze abfragen
          $sql = "SELECT count(*) FROM (".$sql.") as foo";
          $ret=$layerdb->execSQL($sql,4, 0);
          if (!$ret[0]) {
            $rs=pg_fetch_array($ret[1]);
            $layerset[0]['count'] = $rs[0];
          }
        }
        # Hier nach der Abfrage der Sachdaten die weiteren Attributinformationen hinzufügen
        # Steht an dieser Stelle, weil die Auswahlmöglichkeiten von Auswahlfeldern abhängig sein können
        $layerset[0]['attributes'] = $mapDB->add_attribute_values($layerset[0]['attributes'], $layerdb, $layerset[0]['shape']);
        
        # Querymaps erzeugen
        if($layerset[0]['querymap'] == 1 AND $layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['the_geom']] >= '0' AND ($layerset[0]['Datentyp'] == 1 OR $layerset[0]['Datentyp'] == 2)){
          for($k = 0; $k < count($layerset[0]['shape']); $k++){
            $layerset[0]['querymaps'][$k] = $this->createQueryMap($layerset[0], $k);
          }
        }
        
        # Datendrucklayouts abfragen
        $ddl = new ddl($this->database);
        $layerset[0]['layouts'] = $ddl->load_layouts($this->Stelle->id, NULL, $layerset[0]['Layer_ID'], array(0,1));
        
        $this->qlayerset[0]=$layerset[0];
    
        # wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
        if(is_array($this->formvars['attributenames'])){
          $attributenames = array_values($this->formvars['attributenames']);
          $values = array_values($this->formvars['values']);
        }
        for($i = 0; $i < count($attributenames); $i++){
          $this->qlayerset[0]['shape'][0][$attributenames[$i]] = $values[$i];
        }
      }break;
      
      case MS_WFS : {
        $url = $layerset[0]['connection'];
        $version = '1.0.0';
        $typename = $layerset[0]['wms_name'];
        $wfs = new wfs($url, $version, $typename);
        # Attributnamen ermitteln
        $wfs->describe_featuretype_request();
        $wfs->parse_gml('sequence');
        $layerset[0]['attributes'] = $wfs->get_attributes();
        # Filterstring erstellen
        for($i = 0; $i < count($layerset[0]['attributes']['name']); $i++){
          if($this->formvars['value_'.$layerset[0]['attributes']['name'][$i]] != '' OR $this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'IS NULL' OR $this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'IS NOT NULL'){
            $attributenames[] = $layerset[0]['attributes']['name'][$i];
            $operators[] = $this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]];
            $values[] = $this->formvars['value_'.$layerset[0]['attributes']['name'][$i]];
          }
        }
        $filter = $wfs->create_filter($attributenames, $operators, $values);
        # Abfrage mit Filter absetzen
        if($this->formvars['anzahl'] == ''){
          $this->formvars['anzahl'] = MAXQUERYROWS;
        }        
        $wfs->get_feature_request(NULL, $filter, $this->formvars['anzahl']);
        $wfs->parse_gml('gml:featureMember');
        $features = $wfs->extract_features();
        for($j = 0; $j < count($features); $j++){
          for($k = 0; $k < count($layerset[0]['attributes']['name']); $k++){
            $layerset[0]['shape'][$j][$layerset[0]['attributes']['name'][$k]] = $features[$j]['value'][$k];
            $layerset[0]['attributes']['privileg'][$k] = 0;
          }
          $layerset[0]['shape'][$j]['geom'] = $features[$j]['geom'];
        }
        $this->qlayerset[]=$layerset[0];
      }break;
    }   # Ende switch connectiontype

    $i = 0;
    $this->search = true;
		if($this->formvars['embedded_dataPDF']){
			for($k = 0; $k < count($this->qlayerset[$i]['shape']); $k++){
				$checkbox_names[$k] = 'check;'.$layerset[0]['attributes']['table_alias_name'][$this->qlayerset[$i]['maintable']].';'.$this->qlayerset[$i]['maintable'].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['maintable'].'_oid'];
				$this->formvars[$checkbox_names[$k]] = 'on';
			}
			$this->formvars['checkbox_names_'.$this->formvars['selected_layer_id']] = implode('|', $checkbox_names);
		}
    elseif($this->formvars['embedded_subformPK'] != ''){
      header('Content-type: text/html; charset=UTF-8');      
      include(LAYOUTPATH.'snippets/embedded_subformPK.php');			# listenförmige Ausgabe mit Links untereinander
    }
    elseif($this->formvars['embedded'] != ''){
    	ob_end_clean();
      header('Content-type: text/html; charset=UTF-8');
      include(LAYOUTPATH.'snippets/sachdatenanzeige_embedded.php');		# ein aufgeklappter Link
    }
    else{
      $this->main = 'sachdatenanzeige.php';
      if($this->formvars['printversion'] != ''){
        $this->mime_type = 'printversion';
      }
      $this->output();
    }
  }

  function GenerischeSuche(){
  	if($this->formvars['titel'] == ''){
      $this->titel='Layer-Suche';
    }
    else{
      $this->titel=$this->formvars['titel'];
    }
    $this->main='generic_search.php';
    $this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, NULL);
    $this->layergruppen['ID'] = array_values(array_unique($this->layerdaten['Gruppe']));
    $this->layergruppen['Bezeichnung'] = array_values(array_unique($this->layerdaten['Gruppenname']));
    // Sortieren der User unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($this->layergruppen['Bezeichnung'], $this->layergruppen['ID']);
    $this->layergruppen['Bezeichnung'] = $sorted_arrays['array'];
    $this->layergruppen['ID'] = $sorted_arrays['second_array'];
    # wenn Gruppe ausgewählt, Einschränkung auf Layer dieser Gruppe 
    if($this->formvars['selected_group_id']){
    	$this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, NULL, $this->formvars['selected_group_id']);	
    }
    if($this->formvars['selected_layer_id']){
    	if($this->formvars['layer_id'] == '')$this->formvars['layer_id'] = $this->formvars['selected_layer_id'];
    	$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    	$data = $mapdb->getData($this->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $this->formvars['columnname'] = $data_explosion[0];
    	if($this->formvars['map_flag'] != ''){
	    	################# Map ###############################################
	    	$this->loadMap('DataBase');
		    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
		    # Geometrie-Übernahme-Layer:
		    # Spaltenname und from-where abfragen
		    $select = $this->mapDB->getSelectFromData($data);
		    # order by rausnehmen
		  	$orderbyposition = strpos(strtolower($select), 'order by');
		  	if($orderbyposition !== false){
			  	$select = substr($select, 0, $orderbyposition);
		  	}
		    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
		    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
		      $this->formvars['fromwhere'] .= ' where (1=1)';
		    } 
		    if($this->formvars['CMD']== 'Full_Extent' OR $this->formvars['CMD'] == 'recentre' OR $this->formvars['CMD'] == 'zoomin' OR $this->formvars['CMD'] == 'zoomout' OR $this->formvars['CMD'] == 'previous' OR $this->formvars['CMD'] == 'next') {
		      $this->navMap($this->formvars['CMD']);
		    }
		    $this->saveMap('');
		    $currenttime=date('Y-m-d H:i:s',time());
		    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
		    $this->drawMap();
	    	########################################################################
    	}    	
      $this->formvars['anzahl'] = MAXQUERYROWS;
      $layerset=$this->user->rolle->getLayer($this->formvars['selected_layer_id']);
      $this->formvars['selected_group_id'] = $layerset[0]['Gruppe']; 
      switch ($layerset[0]['connectiontype']) {
        case MS_POSTGIS : {
          $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
          $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
          $layerdb->setClientEncoding();
          $path = $mapdb->getPath($this->formvars['selected_layer_id']);
          $privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
          $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
          $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
          # wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
	        for($i = 0; $i < count($this->attributes['name']); $i++){
	          $this->qlayerset['shape'][0][$this->attributes['name'][$i]] = $this->formvars['value_'.$this->attributes['name'][$i]];
	        }
          # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
					$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, $this->qlayerset['shape'], true);
        }break;
        
        case MS_WFS : {
          $url = $layerset[0]['connection'];
          $version = '1.0.0';
          $typename = $layerset[0]['wms_name'];
          $wfs = new wfs($url, $version, $typename);
          $wfs->describe_featuretype_request();
          $wfs->parse_gml('sequence');
          $this->attributes = $wfs->get_attributes();
        }break;
      }
      # Speichern einer neuen Suchabfrage
      if($this->formvars['go_plus'] == 'Suchabfrage_speichern'){
      	$this->user->rolle->save_search($this->attributes, $this->formvars);
      	$this->formvars['searches'] = $this->formvars['search_name'];
      }
      # Löschen einer Suchabfrage
      if($this->formvars['go_plus'] == 'Suchabfrage_löschen'){
      	$this->user->rolle->delete_search($this->formvars['searches'], $this->formvars['selected_layer_id']);
      	$this->formvars['searches'] = '';
      }
      # die Namen aller gespeicherten Suchabfragen dieser Rolle zu diesem Layer laden
    	$this->searchset=$this->user->rolle->getsearches($this->formvars['selected_layer_id']);
    	# die ausgewählte Suchabfrage laden
    	if($this->formvars['searches'] != ''){
    		$this->selected_search=$this->user->rolle->getsearch($this->formvars['selected_layer_id'], $this->formvars['searches']);
    		# alle Suchparameter leeren
    		for($i = 0; $i < count($this->attributes['name']); $i++){
    			$this->formvars['operator_'.$this->attributes['name'][$i]] = '';
    			$this->formvars['value_'.$this->attributes['name'][$i]] = '';
    			$this->formvars['value2_'.$this->attributes['name'][$i]] = '';
    		}
    		# die gespeicherten Suchparameter setzen
    		for($i = 0; $i < count($this->selected_search); $i++){
    			$this->formvars['operator_'.$this->selected_search[$i]['attribute']] = $this->selected_search[$i]['operator'];
    			$this->formvars['value_'.$this->selected_search[$i]['attribute']] = $this->selected_search[$i]['value1'];
    			$this->formvars['value2_'.$this->selected_search[$i]['attribute']] = $this->selected_search[$i]['value2']; 
    		}
    	}
    }
    $this->output();
  }

	function dokument_loeschen(){
		$_FILES[$this->formvars['document_attributename']]['name'] = 'delete';
		$this->sachdaten_speichern();
	}

  function layer_Datensaetze_loeschen(){
    $success = true;
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapdb->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $filter = $mapdb->getFilter($this->formvars['chosen_layer_id'], $this->Stelle->id);
    $checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
        $sql = "DELETE FROM ".$element[2]." WHERE oid = ".$element[3];
        $oids[] = $element[3];
        #echo $sql.'<br>';
        if($filter != ''){
        	$filter = str_replace('$userid', $this->user->id, $filter);
        	$sql .= " AND ".$filter;
        }
        $ret = $layerdb->execSQL($sql,4, 1);
        if ($ret[0]) {
         $success = false;
        }
      }
    }
    # Dokumente auch löschen
    $form_fields = explode('|', $this->formvars['form_field_names']);
    for($i = 0; $i < count($form_fields); $i++){
      if($form_fields[$i] != ''){
        $element = explode(';', $form_fields[$i]);
        if($element[4] == 'Dokument' AND in_array($element[3], $oids)){
        	$this->deleteDokument($this->formvars[str_replace(';Dokument;', ';Dokument_alt;', $form_fields[$i])]);
        }
      }
    }
    if($this->formvars['embedded'] == ''){
      if($success == false){
        showAlert('Löschen fehlgeschlagen');
      }
      else{
        showAlert('Löschen erfolgreich');
      }
      $this->loadMap('DataBase');
      $this->user->rolle->newtime = $this->user->rolle->last_time_id;
      $this->drawMap();
      $this->output();
    }
    else{
      header('Content-type: text/html; charset=UTF-8');
      $attributenames[0] = $this->formvars['targetattribute'];
      $attributes = $mapdb->read_layer_attributes($this->formvars['targetlayer_id'], $layerdb, $attributenames);
      switch ($attributes['form_element_type'][0]){
        case 'SubFormEmbeddedPK' : {
          $this->formvars['embedded_subformPK'] = true;
          echo '^';
          $this->GenerischeSuche_Suchen();
        }break;
      }
    }
  }

  function neuer_Layer_Datensatz_speichern(){
  	$_files = $_FILES;
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $layer_epsg = $layerset[0]['epsg_code'];
    $client_epsg = $this->user->rolle->epsg_code;
    $form_fields = explode('|', $this->formvars['form_field_names']);
    $success = true;
    for($i = 0; $i < count($form_fields); $i++){
      if($form_fields[$i] != ''){
        $element = explode(';', $form_fields[$i]);
        $tablename[$element[2]]['tablename'] = $element[2];
        $tablename[$element[2]]['attributname'][] = $element[1];
        $tablename[$element[2]]['type'][] = $element[4];
        $tablename[$element[2]]['formfield'][] = $form_fields[$i];

        # Prüfen ob ein neues Bild angegebeben wurde
        if($element[4] == 'Dokument'){
          if($_files[$form_fields[$i]]['name']){
            # Dateiname erzeugen
            $name_array=explode('.',basename($_files[$form_fields[$i]]['name']));
            $datei_name=$name_array[0];
            $datei_erweiterung=array_pop($name_array);
            if($layerset[0]['document_path'] == '')$layerset[0]['document_path'] = CUSTOM_IMAGE_PATH;
            $currenttime = date('Y-m-d_H_i_s',time());
            $nachDatei = $layerset[0]['document_path'].$currenttime.'-'.rand(0, 1000000).'.'.$datei_erweiterung; 
            # Bild in das Datenverzeichnis kopieren
            if (move_uploaded_file($_files[$form_fields[$i]]['tmp_name'],$nachDatei)) {
              //echo '<br>Lade '.$_files[$form_fields[$i]]['tmp_name'].' nach '.$nachDatei.' hoch';
              $this->formvars[$form_fields[$i]] = $nachDatei."&original_name=".$_files[$form_fields[$i]]['name'];
            } # ende von Datei wurde erfolgreich in Datenverzeichnis kopiert
            else {
              echo '<br>Datei: '.$_files[$form_fields[$i]]['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
            }
          } # ende vom Fall, dass ein neues Dokument hochgeladen wurde
        }
      }
    }
    
    if($this->formvars['geomtype'] == 'POLYGON' OR $this->formvars['geomtype'] == 'MULTIPOLYGON' OR $this->formvars['geomtype'] == 'GEOMETRY'){
      if($this->formvars['newpathwkt'] == '' AND $this->formvars['newpath'] != ''){   # wenn keine WKT-Geoemtrie da ist, muss die WKT-Geometrie aus dem SVG erzeugt werden
        $spatial_pro = new spatial_processor($this->user->rolle, $this->database, $this->pgdatabase);
        $this->formvars['newpathwkt'] = $spatial_pro->composeMultipolygonWKTStringFromSVGPath($this->formvars['newpath']);
      }
      if($this->formvars['newpathwkt'] != ''){
        $polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
        $ret = $polygoneditor->pruefeEingabedaten($this->formvars['newpathwkt']);
        if ($ret[0]) { # fehlerhafte eingabedaten
          $this->Meldung1=$ret[1];
          $this->neuer_Layer_Datensatz();
          return;
        }
      }
    }
    elseif($this->formvars['geomtype'] == 'MULTILINESTRING'){
      if($this->formvars['newpathwkt'] == '' AND $this->formvars['newpath'] != ''){   # wenn keine WKT-Geoemtrie da ist, muss die WKT-Geometrie aus dem SVG erzeugt werden
        $spatial_pro = new spatial_processor($this->user->rolle, $this->database, $this->pgdatabase);
        $this->formvars['newpathwkt'] = $spatial_pro->composeMultilineWKTStringFromSVGPath($this->formvars['newpath']);
      }
      if($this->formvars['newpathwkt'] != ''){
        $lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
        # eingeabewerte pruefen:
        $ret = $lineeditor->pruefeEingabedaten($this->formvars['newpathwkt']);
        if ($ret[0]) { # fehlerhafte eingabedaten
          $this->Meldung1=$ret[1];
          $this->neuer_Layer_Datensatz();
          return;
        }
      }
    }
    $success = true;
    foreach($tablename as $table){
      $execute = false;
      if($table['tablename'] != ''){
        $sql = "INSERT INTO ".$table['tablename']." (";
        for($i = 0; $i < count($table['attributname']); $i++){
          if(($table['type'][$i] != 'Text_not_saveable' AND $table['type'][$i] != 'Auswahlfeld_not_saveable' AND $table['type'][$i] != 'SubFormPK' AND $table['type'][$i] != 'SubFormFK' AND $this->formvars[$table['formfield'][$i]] != '') 
          OR $table['type'][$i] == 'Time' OR $table['type'][$i] == 'User' OR $table['type'][$i] == 'UserID' OR $table['type'][$i] == 'Stelle' OR $table['type'][$i] == 'Geometrie'){
            if($table['type'][$i] == 'Geometrie'){
              if($this->formvars['geomtype'] == 'POINT' AND $this->formvars['loc_x'] != ''){
                $sql .= $table['attributname'][$i].", ";
                $execute = true;
              }
              elseif($this->formvars['newpathwkt'] != ''){
                $sql .= $table['attributname'][$i].", ";
                $execute = true;
              }
            }
            else{
              $sql .= $table['attributname'][$i].", ";
              $execute = true;
            }
          }
        }
        $sql = substr($sql, 0, strlen($sql)-2);
        $sql.= ") VALUES (";
        for($i = 0; $i < count($table['attributname']); $i++){
          if($table['type'][$i] == 'Time'){                       # Typ "Time"
            $sql.= "(now())::timestamp(0), ";
          }
          elseif($table['type'][$i] == 'User'){                       # Typ "User"
            $sql.= "'".$this->user->Vorname." ".$this->user->Name."', ";
          }
        	elseif($table['type'][$i] == 'UserID'){                       # Typ "UserID"
            $sql.= "'".$this->user->id."', ";
          }
        	elseif($table['type'][$i] == 'Stelle'){                       # Typ "Stelle"
            $sql.= "'".$this->Stelle->Bezeichnung."', ";
          }
          elseif($table['type'][$i] != 'Text_not_saveable' AND $table['type'][$i] != 'Auswahlfeld_not_saveable' AND $table['type'][$i] != 'SubFormPK' AND $table['type'][$i] != 'SubFormFK' AND $this->formvars[$table['formfield'][$i]] != ''){
          	if($table['type'][$i] == 'Zahl'){                       # Typ "Zahl"
	            $this->formvars[$table['formfield'][$i]] = str_replace(' ', '', $this->formvars[$table['formfield'][$i]]);		# bei Zahlen das Leerzeichen (Tausendertrenner) entfernen
	          }
	          if($table['type'][$i] == 'Checkbox' AND $this->formvars[$table['formfield'][$i]] == ''){                       # Typ "Checkbox"
	          	$this->formvars[$table['formfield'][$i]] = 'f';
	          }
            $sql.= "'".pg_escape_string(stripslashes($this->formvars[$table['formfield'][$i]]))."', ";      # Typ "normal"
          }
          elseif($table['type'][$i] == 'Geometrie'){                    # Typ "Geometrie"
            if($this->formvars['geomtype'] == 'POINT'){
              if($this->formvars['loc_x'] != ''){
                if($this->formvars['dimension'] == 3){
                  $sql .= "st_transform(st_geomfromtext('POINT(".$this->formvars['loc_x']." ".$this->formvars['loc_y']." 0)', ".$client_epsg."), ".$layer_epsg."), ";
                }
                else{
                  $sql .= "st_transform(st_geomfromtext('POINT(".$this->formvars['loc_x']." ".$this->formvars['loc_y'].")', ".$client_epsg."), ".$layer_epsg."), ";
                }
              }
            }
            elseif($this->formvars['newpathwkt'] != ''){
              $sql .= "st_transform(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$client_epsg."), ".$layer_epsg."), ";
            }   
          }
        }
        $sql = substr($sql, 0, strlen($sql)-2);
        $sql.= ")";

        #echo $sql.'<br>';
        if($execute == true){
          $this->debug->write("<p>file:kvwmap class:neuer_Layer_Datensatz_speichern :",4);
          if($this->formvars['embedded'] == ''){
            $ret = $layerdb->execSQL($sql,4, 1);
            if(!$ret[0]){
            	if(pg_affected_rows($ret[1]) > 0){
              	$this->formvars['value_'.$table['tablename'].'_oid'] = pg_last_oid($ret[1]);
            	}
            	else{
            		$result = pg_fetch_row($ret[1]);
            		$ret[0] = 1;
            	}
            }
          }
          else{
            $ret = $layerdb->execSQL($sql,4, 1);
            if(!$ret[0]){
              $last_oid = pg_last_oid($ret[1]);
            }
          }

          if ($ret[0]) {
            $success = false;
          }
        }
      }
    }
    if($this->formvars['embedded'] != ''){    # wenn es ein neuer Datensatz aus einem embedded-Formular ist, muss das entsprechende Attribut des Hauptformulars aktualisiert werden
      header('Content-type: text/html; charset=UTF-8');
      $attributenames[0] = $this->formvars['targetattribute'];
      $attributes = $mapdb->read_layer_attributes($this->formvars['targetlayer_id'], $layerdb, $attributenames);
      switch ($attributes['form_element_type'][0]){
        case 'Auswahlfeld' : {
          list($sql) = explode(';', $attributes['options'][0]);
          $sql = str_replace(' from ', ',oid from ', strtolower($sql));    # auch die oid abfragen
          $re=$layerdb->execSQL($sql,4,0);
          if ($re[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; var_dump($layerdb); return 0; }
          while($rs = pg_fetch_array($re[1])){
            $html .= '<option ';
            if($rs['oid'] == $last_oid){$html .= 'selected ';}
            $html .= 'value="'.$rs['value'].'">'.$rs['output'].'</option>';
          }
          echo '^'.$html;
        }break;
        
        case 'SubFormEmbeddedPK' : {
          $this->formvars['embedded_subformPK'] = true;
          echo '^';
          $this->GenerischeSuche_Suchen();
        }break;
      }
    }
    else{
      if($success == false){
        showAlert('Eintrag fehlgeschlagen.\n'.$result[0]);
        $this->neuer_Layer_Datensatz();
      }
      else{
        if($this->formvars['close_window'] == ""){
          showAlert('Eintrag erfolgreich.');
        }
        if($this->formvars['weiter_erfassen'] == 1){
        	$this->formvars['firstpoly'] = '';
        	$this->formvars['firstline'] = '';
        	$this->formvars['secondpoly'] = '';
        	$this->formvars['pathwkt'] = '';
        	$this->formvars['newpathwkt'] = '';
        	$this->formvars['newpath'] = '';
        	$this->formvars['last_doing'] = '';
        	$this->neuer_Layer_Datensatz();
        }
        else{
        	$this->GenerischeSuche_Suchen();
        }
      }
    }
  }

  function neuer_Layer_Datensatz(){
    $this->layerdaten = $this->Stelle->getqueryablePostgisLayers(1);
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel='neuen Datensatz einfügen';
    $this->main='new_layer_data.php';
    
    if($this->formvars['chosen_layer_id']){			# von einer Sachdatenanzeige übergebene Formvars
    	$this->formvars['CMD'] = '';
    	$this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];
    }
    
    if($this->formvars['selected_layer_id']){
      $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
      if($layerset[0]['privileg'] > 0){   # überprüfen, ob Recht zum Erstellen von neuen Datensätzen gesetzt ist
        $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
        $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
        $layerdb->setClientEncoding();
        $privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
        $layerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
        
        ######### von einer Sachdatenanzeige übergebene Formvars #######
	      if($this->formvars['chosen_layer_id']){			
		    	$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
		      for($i = 0; $i < count($checkbox_names); $i++){
		        if($this->formvars[$checkbox_names[$i]] == 'on'){
		        	$element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
		          $oid = $element[3];
		        }
		      }
		      $form_fields = explode('|', $this->formvars['form_field_names']);
		      for($i = 0; $i < count($form_fields); $i++){
			      if($form_fields[$i] != ''){
			        $element = explode(';', $form_fields[$i]);
			        if($element[3] == $oid AND $layerset[0]['attributes']['constraints'][$element[1]] != 'PRIMARY KEY'){		# Primärschlüssel werden nicht mitübergeben
				        $element[3] = '';
				        $this->formvars[implode(';', $element)] = $this->formvars[$form_fields[$i]];
			        }
			      }
		      }
		    }
		    ######### von einer Sachdatenanzeige übergebene Formvars #######

        if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
          for($j = 0; $j < count($layerset[0]['attributes']['name']); $j++){
            $layerset[0]['attributes']['privileg'][$j] = '0';
            $layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['name'][$j]] = '0';
          }
        }
        else{
          for($j = 0; $j < count($layerset[0]['attributes']['name']); $j++){
            $layerset[0]['attributes']['privileg'][$j] = $privileges[$layerset[0]['attributes']['name'][$j]];
            $layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['name'][$j]] = $privileges[$layerset[0]['attributes']['name'][$j]];
            $layerset[0]['shape'][0][$layerset[0]['attributes']['name'][$j]] = $this->formvars[$layerset[0]['Layer_ID'].';'.$layerset[0]['attributes']['real_name'][$layerset[0]['attributes']['name'][$j]].';'.$layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['name'][$j]].';;'.$layerset[0]['attributes']['form_element_type'][$j].';'.$layerset[0]['attributes']['nullable'][$j].';'.$layerset[0]['attributes']['type'][$j]];
          }
        }
        $this->formvars['layer_columnname'] = $layerset[0]['attributes']['name'][$j];
        $this->formvars['layer_tablename'] = $layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['name'][$j]];
        $this->qlayerset[0]=$layerset[0];				

        # wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
        if(is_array($this->formvars['attributenames'])){
          $attributenames = array_values($this->formvars['attributenames']);
          $values = array_values($this->formvars['values']);
        }
        for($i = 0; $i < count($attributenames); $i++){
          $this->qlayerset[0]['shape'][0][$attributenames[$i]] = $values[$i];
        }
        
        # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
				$this->qlayerset[0]['attributes'] = $mapDB->add_attribute_values($this->qlayerset[0]['attributes'], $layerdb, $this->qlayerset[0]['shape'], true);
        $this->new_entry = true;

        $this->geomtype = $this->qlayerset[0]['attributes']['geomtype'][$this->qlayerset[0]['attributes']['the_geom']];
        if($this->geomtype != ''){
          $this->loadMap('DataBase');
        	if($this->formvars['layer_id'] != '' AND $this->formvars['oid'] != '' AND $this->formvars['tablename'] != '' AND $this->formvars['columnname'] != ''){			# das sind die Sachen vom "Mutter"-Layer
        		$layerdb = $this->mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
        		$rect = $this->mapDB->zoomToDatasets(array($this->formvars['oid']), $this->formvars['tablename'], $this->formvars['columnname'], 10, $layerdb, $this->user->rolle->epsg_code);
			      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);		# Zoom auf den "Mutter"-Datensatz
				    if (MAPSERVERVERSION > 600) {
							$this->map_scaledenom = $this->map->scaledenom;
						}
						else {
							$this->map_scaledenom = $this->map->scale;
						}
        	}
          $oldscale=round($this->map_scaledenom);
          if($this->formvars['CMD']!='') {
            $this->navMap($this->formvars['CMD']);
            $this->user->rolle->saveDrawmode($this->formvars['always_draw']);
          }
			    elseif($oldscale!=$this->formvars['nScale'] AND $this->formvars['nScale'] != '') {
			      $this->scaleMap($this->formvars['nScale']);
			    }																																									# Achtung: evtl. Bug-Report wegen fehlendem $this->geomtype == 'LINESTRING'
          if($this->geomtype == 'POLYGON' OR $this->geomtype == 'MULTIPOLYGON' OR $this->geomtype == 'GEOMETRY' OR $this->geomtype == 'LINESTRING' OR $this->geomtype == 'MULTILINESTRING'){
            #-----Polygoneditor und Linieneditor---#
            # aktuellen Kartenausschnitt laden
            $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
            $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
            $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
            # Spaltenname und from-where abfragen
            if($this->formvars['layer_id'] == ''){
              $this->formvars['layer_id'] = $this->formvars['selected_layer_id'];
            }
            $data = $mapdb->getData($this->formvars['layer_id']);
            $space_explosion = explode(' ', $data);
            $this->formvars['columnname'] = $space_explosion[0];
            $select = $mapdb->getSelectFromData($data);
            $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
            if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
              $this->formvars['fromwhere'] .= ' where (1=1)';
            }
            #-----Polygoneditor und Linieneditor---#
          }
          elseif($this->geomtype == 'POINT'){
            #-----Pointeditor-----#
            # aktuellen Kartenausschnitt laden
            $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
            $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
            #-----Pointeditor-----#
          }
          $this->saveMap('');
          if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
			    	$currenttime=date('Y-m-d H:i:s',time());
			    	$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
			    }
          $this->drawMap();
        }

      }
      else{
        $this->Fehler = 'Das Erstellen von neuen Datensätzen ist für diesen Layer in dieser Stelle nicht erlaubt.';
      }
    }
    if($this->formvars['embedded'] != ''){
    	ob_end_clean();
      header('Content-type: text/html; charset=UTF-8');
      include(LAYOUTPATH.'snippets/new_layer_data_embedded.php');
    }
    else{
      $this->output();
    }
  }

	function sachdaten_druck_editor(){
		$ddl=new ddl($this->database, $this);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $this->stellendaten=$this->user->getStellen('Bezeichnung');
    $this->layerdaten = $mapdb->get_postgis_layers('Name');
    # Fonts auslesen
    $this->ddl->fonts = $this->ddl->get_fonts();
    if($this->formvars['selected_layer_id']){
      $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
      $layerdb->setClientEncoding();
      $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
			# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
			$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, NULL, true);
      $this->ddl->layouts = $this->ddl->load_layouts(NULL, NULL, $this->formvars['selected_layer_id'], NULL);
    }
    if($this->formvars['aktivesLayout']){
    	$this->ddl->selectedlayout = $this->ddl->load_layouts(NULL, $this->formvars['aktivesLayout'], NULL, NULL);
    }
    if($this->ddl->selectedlayout != NULL){
      $this->previewfile = $this->sachdaten_druck_editor_preview($this->ddl->selectedlayout[0]);
    }
    $this->main='datendrucklayouts.php';
    $this->titel='Datendruck-Layouteditor';
    $this->output();
	}
	
	function sachdaten_druck_editor_speichern(){
		$_files = $_FILES;
		$ddl=new ddl($this->database);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$this->formvars['aktivesLayout'] = $this->ddl->save_layout($this->formvars, $this->attributes, $_files, $this->Stelle->id);
		$this->sachdaten_druck_editor();
	}
	
	function sachdaten_druck_editor_aendern(){
		$_files = $_FILES;
		$ddl=new ddl($this->database);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    $this->ddl->update_layout($this->formvars, $this->attributes, $_files);
		$this->sachdaten_druck_editor();
	}
	
	function sachdaten_druck_editor_loeschen(){
		$ddl=new ddl($this->database);
    $this->ddl=$ddl;
    $this->ddl->delete_layout($this->formvars);
		$this->sachdaten_druck_editor();
	}
	
	function sachdaten_druck_editor_add2stelle(){
		$ddl=new ddl($this->database);
    $this->ddl=$ddl;
    $this->ddl->add_layout2stelle($this->formvars['aktivesLayout'], $this->formvars['stelle']);
		$this->sachdaten_druck_editor();
	}
	
	function sachdaten_druck_editor_Freitexthinzufuegen(){
		$_files = $_FILES;
		$ddl=new ddl($this->database);
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    $this->ddl->update_layout($this->formvars, $this->attributes, $_files);
    $this->ddl->addfreetext($this->formvars);
		$this->sachdaten_druck_editor();
	}
	
	function sachdaten_druck_editor_Freitextloeschen(){
		$_files = $_FILES;
		$ddl=new ddl($this->database);
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    $this->ddl->update_layout($this->formvars, $this->attributes, $_files);
    $this->ddl->removefreetext($this->formvars);
		$this->sachdaten_druck_editor();
	}
	
	function sachdaten_druck_editor_preview($selectedlayout, $pdfobject = NULL, $offsetx = NULL, $offsety = NULL){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true);
    # Testdaten erzeugen
		if($selectedlayout['type'] != 0)$count = 5;else $count = 1;		# nur beim Untereinandertyp oder eingebettet-Typ mehrere Datensätze erzeugen
    for($i = 0; $i < $count; $i++){
	    for($j = 0; $j < count($attributes['name']); $j++){
	    	if($attributes['type'][$j] != 'geometry' ){
	    		if($attributes['alias'][$j] == '')$attributes['alias'][$j] = $attributes['name'][$j];
	    		$result[$i][$attributes['name'][$j]] = $attributes['alias'][$j];      		      		
	    	}
	    }
    }
    $pdf_file = $this->ddl->createDataPDF($pdfobject, $offsetx, $offsety, $layerdb, $layerset, $attributes, $this->formvars['selected_layer_id'], $selectedlayout, NULL, $result, $this->Stelle, $this->user, true); 
		if($pdfobject == NULL){		# nur wenn kein PDF-Objekt aus einem übergeordneten Layer übergeben wurde, PDF erzeugen
			# in jpg umwandeln
			$currenttime = date('Y-m-d_H_i_s',time());
			exec(IMAGEMAGICKPATH.'convert '.$pdf_file.' -resize 595 '.dirname($pdf_file).'/'.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg');
			#echo IMAGEMAGICKPATH.'convert '.$pdf_file.' -resize 595 '.dirname($pdf_file).'/'.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg';
			if(!file_exists(IMAGEPATH.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg')){
				return TEMPPATH_REL.basename($pdf_file, ".pdf").'-'.$currenttime.'-0.jpg';
			}
			else{
				return TEMPPATH_REL.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg';
			}
		}
	}

	function generischer_sachdaten_druck(){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->ddl = new ddl($this->database, $this);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $path = $mapDB->getPath($this->formvars['chosen_layer_id']);
    $privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
    $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
    # order by rausnehmen
  	$orderbyposition = strpos(strtolower($newpath), 'order by');
  	if($orderbyposition !== false){
	  	$newpath = substr($newpath, 0, $orderbyposition);
  	}
    $attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true);
		$this->attributes = $attributes; 
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    # Daten abfragen
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
        $sql = $newpath." AND ".$element[1].".oid = ".$element[3];
        $oids[] = $element[3];
        #echo $sql.'<br><br>';
        $this->debug->write("<p>file:kvwmap class:generischer_sachdaten_druck :",4);
        $ret = $layerdb->execSQL($sql,4, 1);
        if (!$ret[0]) {
          while ($rs=pg_fetch_array($ret[1])) {
            $result[] = $rs;
          }
        }
      }
    }
    # Layouts abfragen
    $this->ddl->layouts = $this->ddl->load_layouts($this->Stelle->id, NULL, $this->formvars['chosen_layer_id'], array(0,1));
    if(count($this->ddl->layouts) == 1)$this->formvars['aktivesLayout'] = $this->ddl->layouts[0]['id']; 
    # aktives Layout abfragen
    if($this->formvars['aktivesLayout'] != ''){
    	$this->ddl->selectedlayout = $this->ddl->load_layouts(NULL, $this->formvars['aktivesLayout'], NULL, array(0,1));
	    # PDF erzeugen
	    $pdf_file = $this->ddl->createDataPDF(NULL, NULL, NULL, $layerdb, $layerset, $attributes, $this->formvars['chosen_layer_id'], $this->ddl->selectedlayout[0], $oids, $result, $this->Stelle, $this->user);
	    # in jpg umwandeln
	    $currenttime = date('Y-m-d_H_i_s',time());
	    exec(IMAGEMAGICKPATH.'convert '.$pdf_file.' -resize 595 '.dirname($pdf_file).'/'.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg');
	    #echo IMAGEMAGICKPATH.'convert '.$pdf_file.' -resize 595 '.dirname($pdf_file).'/'.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg';
	    if(!file_exists(IMAGEPATH.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg')){
	    	$this->previewfile = TEMPPATH_REL.basename($pdf_file, ".pdf").'-'.$currenttime.'-0.jpg';
	    }
	    else{
	    	$this->previewfile = TEMPPATH_REL.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg';
	    }
    }
    $this->main='generischer_sachdaten_druck.php';
    $this->titel='Sachdaten-Druck';
    $this->output();
	}
	
	function generischer_sachdaten_druck_drucken($pdfobject = NULL, $offsetx = NULL, $offsety = NULL){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$ddl = new ddl($this->database, $this);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $path = $mapDB->getPath($this->formvars['chosen_layer_id']);
    $privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
    $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
    # order by rausnehmen
  	$orderbyposition = strpos(strtolower($newpath), 'order by');
  	if($orderbyposition !== false){
	  	$newpath = substr($newpath, 0, $orderbyposition);
  	}
    $attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true);
		$this->attributes = $attributes; 
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    # Daten abfragen
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
        $sql = $newpath." AND ".$element[1].".oid = ".$element[3];
        $oids[] = $element[3];
        #echo $sql.'<br><br>';
        $this->debug->write("<p>file:kvwmap class:generischer_sachdaten_druck :",4);
        $ret = $layerdb->execSQL($sql,4, 1);
        if (!$ret[0]) {
          while ($rs=pg_fetch_array($ret[1])) {
            $result[] = $rs;
          }
        }
      }
    } 
    # aktives Layout abfragen
    if($this->formvars['aktivesLayout'] != ''){
    	$ddl->selectedlayout = $ddl->load_layouts(NULL, $this->formvars['aktivesLayout'], NULL, NULL);
	    # PDF erzeugen
	    $this->outputfile = basename($ddl->createDataPDF($pdfobject, $offsetx, $offsety, $layerdb, $layerset, $attributes, $this->formvars['chosen_layer_id'], $ddl->selectedlayout[0], $oids, $result, $this->Stelle, $this->user));
    }
		if($pdfobject == NULL){			# nur wenn kein PDF-Objekt aus einem übergeordneten Layer übergeben wurde, PDF anzeigen
			$this->mime_type='pdf';
			$this->output();
		}
	}
	
	function generisches_sachdaten_diagramm($width, $datei = NULL){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $path = $mapDB->getPath($this->formvars['chosen_layer_id']);
    $privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
    $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
    # order by rausnehmen
  	$orderbyposition = strpos(strtolower($newpath), 'order by');
  	if($orderbyposition !== false){
	  	$newpath = substr($newpath, 0, $orderbyposition);
  	}
    $attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);
    $maximum = 0;
    $minimum = 0;
    $maxlabelwidth = 0;
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true);
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    # Daten abfragen
    $sql = $newpath;
    if($this->formvars['all'] != 'true'){
	    for($i = 0; $i < count($checkbox_names); $i++){
	      if($this->formvars[$checkbox_names[$i]] == 'on'){
	        $element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
	        $oids .= $element[3].', ';
	      }
	    }
	    $sql .= " AND ".$element[1].".oid IN (".$oids.'0)';
    }
    if($this->formvars['orderby'.$this->formvars['chosen_layer_id']] != ''){
    	$sql .= ' ORDER BY '.$this->formvars['orderby'.$this->formvars['chosen_layer_id']];
    }
    #echo $sql.'<br><br>';
    $this->debug->write("<p>file:kvwmap class:generisches_sachdaten_diagramm :",4);
    $ret = $layerdb->execSQL($sql,4, 1);
    if (!$ret[0]) {
      while ($rs=pg_fetch_array($ret[1])) {
        $result[] = $rs;
        if($rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]] > $maximum){
        	$maximum = $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
        }
        if($rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]] < $minimum){
        	$minimum = $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
        }
        $summe += $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
        $maxlabelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $rs[$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]]);
        if($maxlabelwidth < $maxlabelbox[2] - $maxlabelbox[0]){
    			$maxlabelwidth = $maxlabelbox[2] - $maxlabelbox[0];
        }
      }
    }
    # defining colors
    $colors['white'] =			 Array(255, 255, 255);
    $colors['yellowLight'] = Array(255, 255, 200);
		$colors['red'] =				 Array(255,  50,  50);
		$colors['blue'] =				 Array( 80,  80, 255);    
    $colors['black'] =  		 Array(  0,   0,   0);
    
    $result_colors = read_colors($this->database);		# Farben fürs Kreisdiagramm
    for($i = 0; $i < count($result_colors); $i++){
    	$piecolors[] = Array($result_colors[$i]['red'], $result_colors[$i]['green'], $result_colors[$i]['blue']);
    }
 
    switch($this->formvars['charttype_'.$this->formvars['chosen_layer_id']]){
    	case 'bar' : {
     	  $image = imagecreatetruecolor(2380, 60*count($result)+170);
		    $chartColors = allocateImageColors($image, $colors);
        
        $backGroundColor = $chartColors['yellowLight'];
				$barLabelSize = 30;
				$barLabelColor = $chartColors['black'];
				$barValueSize = 30;
				$barValueColor = $chartColors['black'];
				$barNegativValueColor = $chartColors['red'];
				$barWidth = 20;
    	  $barColor = $chartColors['blue'];
		    $barBorderWidth = 5;
		    $barBorderColor = $chartColors['black'];
		    
		    $y = 90;
		    #imagefill($image, 0, 0, $backGroundColor);	# geht bei FGS wohl nicht
			  imagefilledrectangle($image, 0, 0, 2380, 60*count($result)+170, $backGroundColor);
		    $label = $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]])$label = $attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
				$value = $this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]]) $value = $attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
		    imagettftext($image, 36, 0, 70, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $label);
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $label);
		    if($maxlabelwidth < $labelbox[2] - $labelbox[0]){
    			$maxlabelwidth = $labelbox[2] - $labelbox[0];
        }
		    imagettftext($image, 36, 0, 130+$maxlabelwidth, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $value);
		    $maxbarwidth = 2000-$maxlabelwidth;
		    $y = 110;
		    
		    $maximum = $maximum - $minimum;		# wenn negative Werte dabei sind, -minimum + maximum addieren
		    
		    for($i = 0; $i < count($result); $i++){
					$value = $result[$i][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
					$label = $result[$i][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
					$y = $y+60;
					imagettftext($image, $barLabelSize, 0, 70, $y, $barLabelColor, dirname(FONTSET).'/arial.ttf', $label);
					$xstart = 130+$maxlabelwidth-($maxbarwidth*$minimum/$maximum);																# Anfang des Rechtecks
					$xstop = 140+$maxlabelwidth-($maxbarwidth*$minimum/$maximum)+($maxbarwidth*$value/$maximum);	# Ende des Rechtecks
					if($xstart > $xstop){		# wenn negativ, dann vertauschen
						$help = $xstart;
						$xstart = $xstop;
						$xstop = $help;
					} 
					if ($barBorderWidth > 0) {
					  imagefilledrectangle($image, $xstart-$barBorderWidth,  $y-$barWidth-$barBorderWidth, $xstop+$barBorderWidth,  $y+$barBorderWidth, $barBorderColor);
					}
					imagefilledrectangle($image, $xstart,  $y-$barWidth, $xstop,  $y, $barColor);
					if (intval($value) < 0) {
					  $useBarValueColor = $barNegativValueColor;
					}
					else {
					  $useBarValueColor = $barValueColor;
					}
					imagettftext($image, $barValueSize, 0, 20+$xstop, $y, $useBarValueColor, dirname(FONTSET).'/arial.ttf', $value);
		    }
    	}break;   
 	
    	case 'mirrorbar' : {
    		$image = imagecreatetruecolor(2380, 15*count($result)+230);
		    $chartColors = allocateImageColors($image, $colors);

        $backgroundColor = $chartColors['yellowLight'];
				$barLabelSize = 30;
				$barLabelColor = $chartColors['black'];
				$barValueSize = 20;
				$barValueColor = $chartColors['black'];
				$leftBarWidth = 15;
    	  $leftBarColor = $chartColors['red'];
		    $leftBarBorderWidth = 3;
		    $leftBarBorderColor = $chartColors['black'];
    	  $rightBarWidth = 15;
    	  $rightBarColor = $chartColors['blue'];
		    $rightBarBorderWidth = 3;
		    $rightBarBorderColor = $chartColors['black'];

		    $y = 90;
		    #imagefill($image, 0, 0, $backgroundColor);		# geht bei FGS wohl nicht
			  imagefilledrectangle($image, 0, 0, 2380, 15*count($result)+230, $backgroundColor);
		    $label = $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]])$label = $attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
				$value = $this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]])$value = $attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $label);
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    $maxbarwidth = (1600-$maxlabelwidth)/2;
		    
		    $scalewidth = (1900-$maxlabelwidth)/2;
		    
		    # -------- Überschrift Mittelachse -----------
		    imagettftext($image, 36, 0, 1190-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $label);
		    
		    # -------- Überschriften der Bars -------------
		     # rechts
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $result[0][$this->formvars['chartsplit_'.$this->formvars['chosen_layer_id']]].'label1');
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, 1785-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $result[0][$this->formvars['chartsplit_'.$this->formvars['chosen_layer_id']]]);
		     # links
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $result[1][$this->formvars['chartsplit_'.$this->formvars['chosen_layer_id']]].'label2');
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, 595-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $result[1][$this->formvars['chartsplit_'.$this->formvars['chosen_layer_id']]]);
		    
		    $y = 120;
		    # -------------- Gitter ----------------
		    $linewidth = 1;
		    $startright = 1190+130+$maxlabelwidth/2;
		    $endleft = 1190-$maxlabelwidth/2-130;
		    $bottom = 15*count($result)+140;
		    $top = 120;
				$maxscale = str_pad(intval(substr($maximum, 0, 1))+1, strlen($maximum), '0');
					# rechts
		    imagefilledrectangle($image, $startright,  $bottom, $startright+$scalewidth,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $startright,  $top, $startright+$scalewidth,  $top+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $startright,  $top, $startright+$linewidth,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $startright+$scalewidth-$linewidth/2,  $top, $startright+$scalewidth+$linewidth/2,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $startright+$scalewidth/2-$linewidth/2,  $top, $startright+$scalewidth/2+$linewidth/2,  $bottom+$linewidth, $chartColors['black']);
		    	# links
		    imagefilledrectangle($image, $endleft-$scalewidth,  $bottom, $endleft,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $endleft-$scalewidth,  $top, $endleft,  $top+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $endleft-$linewidth,  $top, $endleft,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $endleft-$scalewidth-$linewidth/2,  $top, $endleft-$scalewidth+$linewidth/2,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $endleft-$scalewidth/2-$linewidth/2,  $top, $endleft-$scalewidth/2+$linewidth/2,  $bottom+$linewidth, $chartColors['black']);
		    
		    # ------------- Skala --------------
		    	# 0
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', '0');
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, $startright-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', '0');
		    imagettftext($image, 36, 0, $endleft-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', '0');
		    	# Max
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $maxscale);
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, $startright+$scalewidth-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $maxscale);
		    imagettftext($image, 36, 0, $endleft-$scalewidth-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $maxscale);
		    	# Mitte
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $maxscale/2);
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, $startright+$scalewidth/2-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $maxscale/2);
		    imagettftext($image, 36, 0, $endleft-$scalewidth/2-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $maxscale/2);
		    
		    # ----------------- Balken ----------------
		    imagesetthickness($image, 12);
		    for($i = 0; $i < count($result); $i=$i+2){
		    	$y = $y+30;
		    	
		    	 # Label Mittelachse für jeden zweiten Wert
		    	if($i == 0 or (floor(($i-2)/4)*2 == ($i-2)/2)) {
		    	
						$label = $result[$i][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
						$labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $label.'label1');
						$labelwidth = $labelbox[2] - $labelbox[0];
						imagettftext($image, $barLabelSize, 0, 1210-$labelwidth/2, $y, $barLabelColor, dirname(FONTSET).'/arial.ttf', $label);
					}

					 # rechts
					$value = $result[$i+1][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
					if ($leftBarWidth > 0) {
					  imagefilledrectangle($image, $endleft-$scalewidth*$value/$maxscale-$leftBarBorderWidth,  $y-14-$leftBarBorderWidth, $endleft,  $y+$leftBarBorderWidth, $leftBarBorderColor);
					}
					imagefilledrectangle($image, $endleft-$scalewidth*$value/$maxscale,  $y-14, $endleft,  $y, $leftBarColor);
					
           # links 
					$value = $result[$i][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
					if ($rightBarWidth > 0) {
					  imagefilledrectangle($image, $startright,  $y-14-$rightBarBorderWidth, $startright+$scalewidth*$value/$maxscale+$rightBarBorderWidth,  $y+$rightBarBorderWidth, $rightBarBorderColor);
					}
					imagefilledrectangle($image, $startright,  $y-14, $startright+$scalewidth*$value/$maxscale,  $y, $rightBarColor);
					# -------------- Linie ----------------
					if($this->formvars['chartcomparison_'.$this->formvars['chosen_layer_id']] != ''){
							# rechts
						$value = $result[$i+1][$this->formvars['chartcomparison_'.$this->formvars['chosen_layer_id']]];
						if($x_left) imageline($image , $endleft-$scalewidth*$value/$maxscale, $y-7, $x_left, $y_left, $chartColors['black']);
						$x_left = $endleft-$scalewidth*$value/$maxscale;
						$y_left = $y-7;
						  # links
						$value = $result[$i][$this->formvars['chartcomparison_'.$this->formvars['chosen_layer_id']]];
						if($x_right) imageline($image , $startright+$scalewidth*$value/$maxscale, $y-7, $x_right, $y_right, $chartColors['black']);
						$x_right = $startright+$scalewidth*$value/$maxscale;
						$y_right = $y-7;
					}     		
		    }
    	}break;
    	
    	case 'circle' : {
     	  $image = imagecreatetruecolor(2000, 2000);
		    $chartColors = allocateImageColors($image, $colors);
		    $pieColors = allocateImageColors($image, $piecolors); 
        
        $backGroundColor = $chartColors['white'];
				$barLabelSize = 30;
				$barLabelColor = $chartColors['black'];
				$barValueSize = 30;
				$barValueColor = $chartColors['black'];
				$barNegativValueColor = $chartColors['red'];
				$barWidth = 40;
    	  $barColor = $chartColors['blue'];
		    $barBorderWidth = 4;
		    $barBorderColor = $chartColors['black'];
		    
		    $y = 120;
		    #imagefill($image, 0, 0, $backGroundColor);	# geht bei FGS wohl nicht
			  imagefilledrectangle($image, 0, 0, 2000, 2000, $backGroundColor);
			  #Layername als Überschrift
			  $labelbox = imagettfbbox(50, 0, dirname(FONTSET).'/arial_bold.ttf', $layerset[0]['Name']);
			  $labelwidth = $labelbox[2] - $labelbox[0];
			  imagettftext($image, 50, 0, 1000-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial_bold.ttf', $layerset[0]['Name']);
			  $y = $y + 120;
		    $label = $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]])$label = $attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
				$value = $this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]]) $value = $attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
		    imagettftext($image, 36, 0, 70, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $label);
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $label);
		    if($maxlabelwidth < $labelbox[2] - $labelbox[0]){
    			$maxlabelwidth = $labelbox[2] - $labelbox[0];
        }
		    imagettftext($image, 36, 0, 190+$maxlabelwidth, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $value);
		    $maxbarwidth = 2000-$maxlabelwidth;
		    $y = $y + 20;
		    
		    $maximum = $maximum - $minimum;		# wenn negative Werte dabei sind, -minimum + maximum addieren
		    
		    $endwinkel = 0;
		    for($i = 0; $i < count($result); $i++){
		    	$value = $result[$i][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
					$label = $result[$i][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];					
					$y = $y+60;
					if ($barBorderWidth > 0) {
					  imagefilledrectangle($image, 70-$barBorderWidth,  $y-$barWidth-$barBorderWidth, 110+$barBorderWidth,  $y+$barBorderWidth, $barBorderColor);
					}
					imagefilledrectangle($image, 70,  $y-$barWidth, 110,  $y, $pieColors[$i]);
					imagettftext($image, $barLabelSize, 0, 130, $y, $barLabelColor, dirname(FONTSET).'/arial.ttf', $label);
					$xstop = 190+$maxlabelwidth;
					imagettftext($image, $barValueSize, 0, $xstop, $y, $useBarValueColor, dirname(FONTSET).'/arial.ttf', $value);
					#Piechart
					$offset = count($result)*60+80;
					$startwinkel = $endwinkel;		# das nächste Stück fängt da an, wo das letzte aufhörte 
					$endwinkel = $startwinkel + (360*$value/$summe);
					imagesetthickness($image, $barBorderWidth);
					imagefilledarc($image, 1000, (2000-$offset)/2+$offset, 1700-$offset, 1700-$offset, $startwinkel, $endwinkel, $pieColors[$i], IMG_ARC_PIE);
					imagefilledarc($image, 1000, (2000-$offset)/2+$offset, 1700-$offset, 1700-$offset, $startwinkel, $endwinkel, $barValueColor, IMG_ARC_PIE+IMG_ARC_NOFILL+IMG_ARC_EDGED);
		    }
    	}break;   
    }
    
    $imagewidth = imagesx($image);
  	$imageheight = imagesy($image);
		$height = $imageheight * $width/$imagewidth;
  	$finalimage = imagecreatetruecolor($width, $height);
  	imagecopyresampled($finalimage, $image,0, 0, 0, 0, $width, $height, $imagewidth, $imageheight);
        
    //$imagename = rand(0, 1000000).'.png';
    //imagepng($finalimage, IMAGEPATH.$imagename);
    if($datei == NULL){
    	ob_end_clean();
    	ob_start("output_handler");
    }
    #ImagePNG($finalimage);
    #echo $datei;
    ImageJPEG($finalimage, $datei);
    //return TEMPPATH_REL.$imagename;
    //$this->output();
	}

  function generic_csv_export(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $path = $mapDB->getPath($this->formvars['chosen_layer_id']);
    $privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
    $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);    
    $attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true);
		
		# order by rausnehmen
  	$orderbyposition = strpos(strtolower($newpath), 'order by');
  	if($orderbyposition !== false){
	  	$orderby = ' '.substr($newpath, $orderbyposition);
	  	$newpath = substr($newpath, 0, $orderbyposition);
  	}
  	
  	# group by rausnehmen
		$groupbyposition = strpos(strtolower($newpath), 'group by');
		if($groupbyposition !== false){
			$groupby = ' '.substr($newpath, $groupbyposition);
			$newpath = substr($newpath, 0, $groupbyposition);
  	}

    if($this->formvars['all'] != 'true'){                     // nur ausgewählte Datensätze abfragen
      $checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
      # Daten abfragen
      $element = explode(';', $checkbox_names[0]);   #  check;table_alias;table;oid
      $where = " AND ".$element[1].".oid IN (";
      for($i = 0; $i < count($checkbox_names); $i++){
        if($this->formvars[$checkbox_names[$i]] == 'on'){
          $element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
          $where = $where."'".$element[3]."',";
        }
      }
      $where .= "0)";
            
      $sql.= $newpath.$where.$groupby.$orderby;
      #echo $sql.'<br><br>';
      $this->debug->write("<p>file:kvwmap class:generic_csv_export :",4);
      $ret = $layerdb->execSQL($sql,4, 1);
      if (!$ret[0]) {
      	while ($rs=pg_fetch_array($ret[1])) {
        	$result[] = $rs;
      	}
      }
    }
    else{                                           // alle Treffer abfragen
      $sql = stripslashes($this->formvars['sql_'.$this->formvars['chosen_layer_id']]);
      $this->debug->write("<p>file:kvwmap class:generic_csv_export :",4);
      $ret = $layerdb->execSQL($sql,4, 1);
      if (!$ret[0]) {
        while ($rs=pg_fetch_array($ret[1])) {
          $result[] = $rs;
        }
      }
    }

    # Spaltenüberschriften schreiben
    # Excel is zu blöd für 'ID' als erstes Attribut
    if($attributes['alias'][0] == 'ID'){
      $attributes['alias'][0] = 'id';
    }
    if($attributes['name'][0] == 'ID'){
      $attributes['name'][0] = 'id';
    }
    for($i = 0; $i < count($attributes['name']); $i++){
    	if($attributes['type'][$i] != 'geometry' AND $attributes['name'][$i] != 'lock'){
	      if($attributes['alias'][$i] != ''){
	        $name = $attributes['alias'][$i];
	      }
	      else{
	        $name = $attributes['name'][$i];
	      }
	      $csv .= $name.';';
    	}
    }
    $csv .= chr(10);

    # Daten schreiben
    for($i = 0; $i < count($result); $i++){
      for($j = 0; $j < count($attributes['name']); $j++){
      	if($attributes['type'][$j] != 'geometry' AND $attributes['name'][$i] != 'lock'){
      		$csv .= '"';
	        if(in_array($attributes['type'][$j], array('numeric', 'float4', 'float8'))){
	        	$result[$i][$attributes['name'][$j]] = str_replace('.', ",", $result[$i][$attributes['name'][$j]]);	
	        }
	        $result[$i][$attributes['name'][$j]] = str_replace(';', ",", $result[$i][$attributes['name'][$j]]);
	        $result[$i][$attributes['name'][$j]] = str_replace(chr(10), " ", $result[$i][$attributes['name'][$j]]);
	        $result[$i][$attributes['name'][$j]] = str_replace(chr(13), "", $result[$i][$attributes['name'][$j]]);
	        $csv .= $result[$i][$attributes['name'][$j]].'";';
      	}
      }
      $csv .= chr(10);
    }
    
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeCSV($currenttime,$this->formvars['chosen_layer_id'],count($result));

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  attachment; filename=".$this->user->id."_".$this->formvars['chosen_layer_id']."_export.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
	  
  function uko_export(){
  	$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->uko = new uko($this->pgdatabase);
    $this->uko->uko_export($this->formvars, $layerdb);
  }
	
  function uko_import(){
	$this->titel='UKO-Import';
    $this->main='uko_import.php';
    $this->uko = new uko($this->pgdatabase);
    $this->output();
  }
  
  function uko_import_importieren(){
    $this->titel='UKO-Import';
    $this->main='uko_import.php';
    $this->uko = new uko($this->pgdatabase);
    $id = $this->uko->uko_importieren($this->formvars, $this->user->Name, $this->user->id, $this->pgdatabase);
	if($this->uko->success == true){
		$this->main='map.php';
		$this->loadMap('DataBase');
		$this->zoomToPolygon('uko_polygon', $id, 20, $this->uko->srid);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->drawMap();
		$this->saveMap('');
	}
    $this->output();
  }
	
	function gpx_import(){
    $this->titel='GPX-Import';
    $this->main='gpx_import.php';
    $this->gpx = new gpx();
    $this->gpx->gpx_import($this->formvars);
    $this->output();
  }
  
  function gpx_import_importieren(){
    $this->titel='GPX-Import';
    $this->main='gpx_import.php';
    $this->gpx = new gpx();
    $this->gpx->gpx_import_importieren($this->formvars, $this->pgdatabase);
    $this->output();
  }

  function TIFExport(){
    $this->loadMap('DataBase');
    $breite = $this->map->extent->maxx - $this->map->extent->minx;
    $this->formvars['resolution'] = $breite/$this->map->width;
    $this->titel='TIF-Export';
    $this->main='tif_export.php';
    $this->output();
  }
  
  function TIFExport_erzeugen(){
    $this->loadMap('DataBase');
    $this->tif = new tif($this->map, $this->formvars['resolution']);
    $this->map = $this->tif->setmap();
    $this->drawMap();
    $this->tif->create_tif($this->img['hauptkarte']);
    $this->tif->create_tfw();
    $this->titel='TIF-Export';
    $this->main='tif_export.php';
    $this->output();
  }

	function create_shp_rollenlayer(){
		$this->titel='Shape-Datei Anzeigen';
    $this->main='create_shape_rollenlayer.php';
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    $this->output();
	}
	
	function create_shp_rollenlayer_load(){
		$this->shape = new shape();
		$layer_id = $this->shape->create_shape_rollenlayer($this->formvars, $this->Stelle, $this->user, $this->database, $this->pgdatabase);
		$this->loadMap('DataBase');
		$this->zoomToMaxLayerExtent($layer_id);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->saveMap('');
    $this->output();
	}	

  function shp_import_speichern(){
    $this->titel='Shape-Import';
    $this->main='shape_import.php';
    $this->shape = new shape();
    $this->shape->shp_import_speichern($this->formvars, $this->pgdatabase);
    $this->output();
  }

  function shp_import(){
    $this->titel='Shape-Import';
    $this->main='shape_import.php';
    $this->shape = new shape();
    $this->shape->shp_import($this->formvars);
    $this->output();
  }

  function simple_shp_import_speichern(){
    $this->titel='Shape-Import';
    $this->main='simple_shape_import.php';
    $this->shape = new shape();
    $this->shape->simple_shp_import_speichern($this->formvars, $this->pgdatabase);
    $this->output();
  }

  function simple_shp_import(){
    $this->shape = new shape();
    $this->shape->simple_shp_import($this->formvars, $this->pgdatabase);
    $this->main='simple_shape_import.php';
    $this->titel='Shape-Import';
    $this->output();
  }

  function shp_export(){
    $this->titel='Shape-Export';
    if($this->formvars['chosen_layer_id'] != '')$this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];		# aus der Sachdatenanzeige des GLE
    $this->main='shape_export.php';
    $this->loadMap('DataBase');
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
    $this->shape = new shape();
  	if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($this->formvars['layer_id']){
	    # Geometrie-Übernahme-Layer:
	    # Spaltenname und from-where abfragen
	    $data = $this->mapDB->getData($this->formvars['layer_id']);
	    #echo $data;
	    $data_explosion = explode(' ', $data);
	    $this->formvars['columnname'] = $data_explosion[0];
	    $select = $this->mapDB->getSelectFromData($data);
	    # order by rausnehmen
	  	$orderbyposition = strpos(strtolower($select), 'order by');
	  	if($orderbyposition !== false){
		  	$select = substr($select, 0, $orderbyposition);
	  	}
	    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
	    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
	      $this->formvars['fromwhere'] .= ' where (1=1)';
	    }
	    ###################### über Checkboxen aus der Sachdatenanzeige des GLE ausgewählt ###############
	    $anzahl = 0;
      $checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
      # Daten abfragen
      $element = explode(';', $checkbox_names[0]);   #  check;table_alias;table;oid
      $where = " AND ".$element[1].".oid IN (";
      for($i = 0; $i < count($checkbox_names); $i++){
        if($this->formvars[$checkbox_names[$i]] == 'on'){
          $element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
          $where = $where."'".$element[3]."',";
          $anzahl++;
        }
      }
      $where .= "0)";
      if($anzahl > 0){
      	$this->formvars['sql_'.$this->formvars['selected_layer_id']] = $where.$orderby;
      	$this->formvars['anzahl'] = $anzahl;
      }
			####################################################################################################
    }
    if($this->formvars['CMD']== 'Full_Extent' OR $this->formvars['CMD'] == 'recentre' OR $this->formvars['CMD'] == 'zoomin' OR $this->formvars['CMD'] == 'zoomout' OR $this->formvars['CMD'] == 'previous' OR $this->formvars['CMD'] == 'next') {
      $this->navMap($this->formvars['CMD']);
    }
    else{
      $this->formvars['load'] = true;
    }
    $this->shape->shp_export($this->formvars, $this->Stelle, $this->mapDB);
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function shp_export_exportieren(){
    $this->shape = new shape();
    $this->formvars['filename'] = $this->shape->shp_export_exportieren($this->formvars, $this->Stelle, $this->user);
    $this->shp_export();
  }

  function Attributeditor(){
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel='Attribut-Editor';
    $this->main='attribut_editor.php';
    $this->layerdaten = $mapdb->get_postgis_layers('Name');
    if($this->formvars['selected_layer_id']){
      $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
      $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
      # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
			#$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, NULL, true);
    }
    $this->output();
  }

  function Attributeditor_speichern(){
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $path = $mapdb->getPath($this->formvars['selected_layer_id']);
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    $mapdb->save_attributes($this->attributes, $this->database, $this->formvars);
    $this->Attributeditor();
  }

  function layer_attributes_privileges(){
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel='Rechteverwaltung der Layerattribute';
    $this->main='attribut_privileges_form.php';   
		$this->layerdaten = $mapdb->get_postgis_layers('Name');    
    if($this->formvars['selected_layer_id'] != ''){
    	$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    	$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    	$this->stellen = $mapdb->get_stellen_from_layer($this->formvars['selected_layer_id']);
    	$this->layer[0] = $mapdb->get_Layer($this->formvars['selected_layer_id']);
    }
    $this->output();
  }

  function layer_attributes_privileges_save(){
  	$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    if($this->formvars['stelle'] != '' AND $this->formvars['selected_layer_id'] != ''){
			$stellen = explode('|', $this->formvars['stelle']);
			foreach($stellen as $stelleid){
				$stelle = new stelle($stelleid, $this->database);
				$stelle->set_attributes_privileges($this->formvars, $this->attributes);
				$stelle->set_layer_privileges($this->formvars['selected_layer_id'], $this->formvars['privileg'.$stelleid]);
			}
    }
    elseif($this->formvars['selected_layer_id'] != ''){
      $mapdb->set_default_layer_privileges($this->formvars, $this->attributes);
    }
    $this->layer_attributes_privileges();
  }

  function StelleAendern() {
  	$_files = $_FILES;
    if (!$this->formvars['bezeichnung'] or !$this->formvars['Referenzkarte_ID']) {
      # Fehler bei der Formulareingabe
      $this->Meldung=$ret[1];
    }
    else {
      if($_files['wappen']['name']){
        $this->formvars['wappen'] = 'wappen';
        $nachDatei = WWWROOT.APPLVERSION.WAPPENPATH.$_files['wappen']['name'];
        if (move_uploaded_file($_files['wappen']['tmp_name'],$nachDatei)) {
            #echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
        }
      }
      if($_files['wasserzeichen']['name']){
        $this->formvars['wasserzeichen'] = 'wasserzeichen';
        $nachDatei = WWWROOT.APPLVERSION.WAPPENPATH.$_files['wasserzeichen']['name'];
        if (move_uploaded_file($_files['wasserzeichen']['tmp_name'],$nachDatei)) {
            #echo '<br>Lade '.$_files['wasserzeichen']['tmp_name'].' nach '.$nachDatei.' hoch';
        }
      }
      $stelleid = $this->formvars['selected_stelle_id'];
      $Stelle = new stelle($stelleid,$this->user->database);
      $Stelle->language = $this->Stelle->language;
      $Stelle->charset = $this->Stelle->charset;
      $Stelle->Aendern($this->formvars);
      if($this->formvars['id'] != ''){
        $new_stelle = new stelle($this->formvars['id'],$this->user->database);
        $new_stelleid = $this->formvars['id'];
        $this->formvars['selected_stelle_id'] = $new_stelleid;
      }
      else{
        $new_stelle = $Stelle;
        $new_stelleid = $stelleid;
      }
      $menues = explode(', ',$this->formvars['selmenues']);
      $functions = explode(', ',$this->formvars['selfunctions']);
      $frames = explode(', ',$this->formvars['selframes']);
      $layer = explode(', ',$this->formvars['sellayer']);
      $selectedusers = explode(', ',$this->formvars['selusers']);
      $users= $Stelle->getUser();
      $stelle_id = explode(',',$stelleid);
      $new_stelle_id = explode(',',$new_stelleid);
      $new_stelle->deleteMenue(0);    // erst alle Menüs rausnehmen
      $new_stelle->addMenue($menues); // und dann hinzufügen, damit die Reihenfolge stimmt
      if($layer[0] != NULL){
        $new_stelle->addLayer($layer, 0); # Hinzufügen der Layer zur Stelle
      }
      $new_stelle->removeFunctions();   // Entfernen aller Funktionen
      if($functions[0] != NULL){
        $new_stelle->addFunctions($functions, 0); # Hinzufügen der Funktionen zur Stelle
      }
      $document = new Document($this->database);
      $document->removeFrames($new_stelleid);   // Entfernen aller Druckrahmen der Stelle
      if($frames[0] != NULL){
        for($i = 0; $i < count($frames); $i++){
          $document->add_frame2stelle($frames[$i], $new_stelleid); # Hinzufügen der Druckrahmen zur Stelle
        }
      }
      for($i=0; $i<count($selectedusers); $i++){
        $this->user->rolle->setRollen($selectedusers[$i],$new_stelle_id); # Hinzufügen einer neuen Rolle (selektierte User zur Stelle)
        $this->user->rolle->setMenue($selectedusers[$i],$new_stelle_id); # Hinzufügen der selectierten Obermenüs zur Rolle
        $this->user->rolle->setGroups($selectedusers[$i], $new_stelle_id, $layer, 0); # Hinzufügen der Layergruppen der selektierten Layer zur Rolle
        $this->user->rolle->setLayer($selectedusers[$i], $new_stelle_id, 0); # Hinzufügen der Layer zur Rolle
        $this->selected_user = new user(0,$selectedusers[$i],$this->user->database);
        $this->selected_user->checkstelle();
      }
      /* Löschen der in der Selectbox entfernten Menues
      $stellenmenues = $Stelle->getMenue(0);
      for($i = 0; $i < count($stellenmenues['ID']); $i++){
        $found = false;
        for($j = 0; $j < count($menues); $j++){
          if($menues[$j] == $stellenmenues['ID'][$i]){
            $found = true;
          }
        }
        if($found == false){
          $deletemenues[] = $stellenmenues['ID'][$i];
        }
      }
      if($deletemenues != 0){
        $Stelle->deleteMenue($deletemenues);
        for($i = 0; $i < count($deletemenues); $i++){
          $menue_id = array($deletemenues[$i]);
          for($j = 0; $j < count($users['ID']); $j++){
            $this->user->rolle->deleteMenue($users['ID'][$j], $stelle_id, $menue_id);
          }
        }
      }
    # /Löschen der in der Selectbox entfernten Menues
    */

    # Löschen der in der Selectbox entfernten Layer
      $stellenlayer = $Stelle->getLayers(NULL);
      for($i = 0; $i < count($stellenlayer['ID']); $i++){
        $found = false;
        for($j = 0; $j < count($layer); $j++){
          if($layer[$j] == $stellenlayer['ID'][$i]){
            $found = true;
          }
        }
        if($found == false){
          $deletelayer[] = $stellenlayer['ID'][$i];
        }
      }
      if($deletelayer != 0){
        $Stelle->deleteLayer($deletelayer);
        for($i = 0; $i < count($deletelayer); $i++){
          $layerid = $deletelayer[$i];
          $layer_id = explode(',',$layerid);
          for($j = 0; $j < count($users['ID']); $j++){
            $this->user->rolle->deleteLayer($users['ID'][$j], $stelle_id, $layer_id);
            $this->user->rolle->updateGroups($users['ID'][$j],$stelleid, $layerid);
          }
        }
      }
    # /Löschen der in der Selectbox entfernten Layer

    # Löschen  der User, die nicht mehr zur Stelle gehören sollen
    # Löschen der in der Selectbox entfernten User
      for($i = 0; $i < count($users['ID']); $i++){
        $found = false;
        for($j = 0; $j < count($selectedusers); $j++){
          if($selectedusers[$j] == $users['ID'][$i]){
            $found = true;
          }
        }
        if($found == false){
          $deleteuser[] = $users['ID'][$i];
        }
      }
      $anzdeleteuser=count($deleteuser);
      if($anzdeleteuser>0){
        for($i=0; $i<$anzdeleteuser; $i++){
          $this->user->rolle->deleteRollen($deleteuser[$i], $stelle_id);
          $this->user->rolle->deleteMenue($deleteuser[$i], $stelle_id, 0);
          $this->user->rolle->deleteGroups($deleteuser[$i], $stelle_id);
          $this->user->rolle->deleteLayer($deleteuser[$i], $stelle_id, 0);
          $this->selected_user = new user(0,$deleteuser[$i],$this->user->database);
          $this->selected_user->checkstelle();
        }
      }
    # /Löschen der in der Selectbox entfernten User

      if ($ret[0]) {
        $this->Meldung=$ret[1];
      }
      else {
        $this->Meldung='Daten der Stelle erfolgreich eingetragen!';
      }
    }
    $this->Stelleneditor();
  }

  function StelleAnlegen() {
  	$_files = $_FILES;
    if (!$this->formvars['bezeichnung'] or !$this->formvars['Referenzkarte_ID']) {
      # Fehler bei der Formulareingabe
      showAlert('Füllen Sie alle mit * gekennzeichneten Formularfelder aus.');
    }
    else {
      if($_files['wappen']['name']){
        $this->formvars['wappen'] = 'wappen';
        $nachDatei = WWWROOT.APPLVERSION.WAPPENPATH.$_files['wappen']['name'];
        if (move_uploaded_file($_files['wappen']['tmp_name'],$nachDatei)) {
            #echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
        }
      }
      $ret=$this->Stelle->NeueStelleAnlegen($this->formvars);
      if ($ret[0]) {
          # Fehler beim Eintragen der Stellendaten
          $this->Meldung=$ret[1];
      }
     else {
        $neue_stelle_id=$ret[1];
        $Stelle = new stelle($neue_stelle_id,$this->user->database);
        $menues = explode(', ',$this->formvars['selmenues']);
        $functions = explode(', ',$this->formvars['selfunctions']);
        $frames = explode(', ',$this->formvars['selframes']);
        $layer = explode(', ',$this->formvars['sellayer']);
        $users = explode(', ',$this->formvars['selusers']);
        $neue_stelle_id = explode(',',$neue_stelle_id);
        # wenn Stelle ausgewählt, Daten kopieren
        if($this->formvars['selected_stelle_id']){
          $Stelle->copyLayerfromStelle($layer, $this->formvars['selected_stelle_id']);
        }
        $Stelle->addMenue($menues);
        if($functions[0] != NULL){
          $Stelle->addFunctions($functions, 0); # Hinzufügen der Funktionen zur Stelle
        }
        if($layer[0] != NULL){
          $Stelle->addLayer($layer, 0);
        }
        $document = new Document($this->database);
        if($frames[0] != NULL){
          for($i = 0; $i < count($frames); $i++){
            $document->add_frame2stelle($frames[$i], $neue_stelle_id[0]); # Hinzufügen der Druckrahmen zur Stelle
          }
        }
        for($i=0; $i<count($users); $i++){
          $this->user->rolle->setRollen($users[$i],$neue_stelle_id);
          $this->user->rolle->setMenue($users[$i],$neue_stelle_id);
          $this->user->rolle->setGroups($users[$i], $neue_stelle_id, $layer, 0);
          $this->user->rolle->setLayer($users[$i], $neue_stelle_id, 0);
          $this->selected_user = new user(0,$users[$i],$this->user->database);
          $this->selected_user->checkstelle();
        }
        if ($ret[0]) {
          $this->Meldung=$ret[1];
        }
        else {
          $this->Meldung='Daten der Stelle erfolgreich eingetragen!';
        }
      }
    }
    $this->formvars['selected_stelle_id'] = $ret[1];
    $this->Stelleneditor();
  }

  function StellenAnzeigen() {
    # Abfragen aller Stellen
    if($this->formvars['order'] == ''){
      $this->formvars['order'] = 'Bezeichnung';
    }
    $this->stellendaten=$this->Stelle->getStellen($this->formvars['order']);
    $this->titel='Stellendaten';
    $this->main='stellendaten.php';
    $this->output();
  }

  function Stelleneditor() {
    $this->titel='Stellen Editor';
    $this->main='stelle_formular.php';
    $document = new Document($this->database);
    # Abfragen der Stellendaten wenn eine stelle_id zur Änderung selektiert ist
    if ($this->formvars['selected_stelle_id']>0) {
      $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
      $Stelle->language = $this->Stelle->language;
      $Stelle->charset = $this->Stelle->charset;
      $this->stellendaten = $Stelle->getstellendaten();
      $this->formvars['bezeichnung'] = $this->stellendaten['Bezeichnung'];
      $this->formvars['minxmax'] = $this->stellendaten['minxmax'];
      $this->formvars['minymax'] = $this->stellendaten['minymax'];
      $this->formvars['maxxmax'] = $this->stellendaten['maxxmax'];
      $this->formvars['maxymax'] = $this->stellendaten['maxymax'];
      $this->formvars['epsg_code'] = $this->stellendaten['epsg_code'];
      $this->formvars['Referenzkarte_ID'] = $this->stellendaten['Referenzkarte_ID'];
      $this->formvars['start'] = $this->stellendaten['start'];
      $this->formvars['stop'] = $this->stellendaten['stop'];
      $this->formvars['pgdbhost'] = $this->stellendaten['pgdbhost'];
      $this->formvars['pgdbname'] = $this->stellendaten['pgdbname'];
      $this->formvars['pgdbuser'] = $this->stellendaten['pgdbuser'];
      $this->formvars['pgdbpasswd'] = $this->stellendaten['pgdbpasswd'];
      $this->formvars['ows_title'] = $this->stellendaten['ows_title'];
      $this->formvars['ows_abstract'] = $this->stellendaten['ows_abstract'];
      $this->formvars['wms_accessconstraints'] = $this->stellendaten['wms_accesscontraints'];
      $this->formvars['ows_contactperson'] = $this->stellendaten['ows_contactperson'];
      $this->formvars['ows_contactorganization'] = $this->stellendaten['ows_contactorganization'];
      $this->formvars['ows_contactemailaddress'] = $this->stellendaten['ows_contactemailaddress'];
      $this->formvars['ows_contactposition'] = $this->stellendaten['ows_contactposition'];
      $this->formvars['ows_fees'] = $this->stellendaten['ows_fees'];
      $this->formvars['ows_srs'] = $this->stellendaten['ows_srs'];
      $this->formvars['wappen'] = $this->stellendaten['wappen'];
      $this->formvars['wasserzeichen'] = $this->stellendaten['wasserzeichen'];
      $this->formvars['alb_raumbezug'] = $this->stellendaten['alb_raumbezug'];
      $this->formvars['alb_raumbezug_wert'] = $this->stellendaten['alb_raumbezug_wert'];
      $this->formvars['checkPasswordAge'] = $this->stellendaten['check_password_age'];
      $this->formvars['allowedPasswordAge'] = $this->stellendaten['allowed_password_age'];
      $this->formvars['use_layer_aliases'] = $this->stellendaten['use_layer_aliases'];
      $this->formvars['selmenues'] = $Stelle->getMenue(0);
      $Stelle->getFunktionen();
      $this->formvars['selfunctions'] = $Stelle->funktionen['array'];
      $this->formvars['selframes'] = $document->load_frames($this->formvars['selected_stelle_id'], NULL);
      $this->formvars['sellayer'] = $Stelle->getLayers(NULL, 'Name');
      $this->formvars['selusers'] = $Stelle->getUser();
    }
    # Abfragen aller möglichen Menuepunkte
    $this->Menue=new menue($this->user->rolle->language,$this->user->rolle->charset);
    $this->formvars['menues']=$this->Menue->getallOberMenues();
    # Abfragen aller möglichen Funktionen
    $funktion = new funktion($this->database);
    $this->formvars['functions'] = $funktion->getFunktionen(NULL, 'bezeichnung');
    # Abfragen aller möglichen Druckrahmen
    $this->formvars['frames'] = $document->load_frames(NULL, NULL);
    # Abfragen aller möglichen Layer
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->formvars['layer']=$mapDB->getall_Layer('Name');
    $this->formvars['groups']=$mapDB->getall_Groups('Gruppenname');
    # Abfragen aller möglichen User
    $this->formvars['users']=$this->user->getall_Users('Name');
    # Abfragen aller möglichen EPSG-Codes
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    $this->output();
  }

  function StelleLoeschen(){
    $selected_stelle=new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $selected_stelle->Löschen();
    $selected_stelle->deleteMenue(0);
    $selected_stelle->deleteLayer(0);
    $user = $selected_stelle->getUser();
    $stelle_id = explode(',',$selected_stelle->id);
    for($i = 0; $i < count($user['ID']); $i++){
      $this->user->rolle->deleteRollen($user['ID'][$i], $stelle_id);
      $this->user->rolle->deleteMenue($user['ID'][$i], $stelle_id, 0);
      $this->user->rolle->deleteGroups($user['ID'][$i], $stelle_id);
      $this->user->rolle->deleteLayer($user['ID'][$i], $stelle_id, 0);
    }
    $this->titel='Stellendaten';
    $this->main='stellendaten.php';
    # Abfragen aller Stellen
    $this->stellendaten=$this->Stelle->getStellen($this->formvars['order']);
    $this->output();
  }

  function Filterverwaltung() {
    $this->loadMap('DataBase');
    $this->titel='Filterverwaltung';
    $this->main='attribut_eingabe_form.php';
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    $showpolygon = true;
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
  	if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($this->formvars['layer_id']){
	    # Geometrie-Übernahme-Layer:
	    # Spaltenname und from-where abfragen
	    $data = $this->mapDB->getData($this->formvars['layer_id']);
	    #echo $data;
	    $data_explosion = explode(' ', $data);
	    $this->formvars['columnname'] = $data_explosion[0];
	    $select = $this->mapDB->getSelectFromData($data);
	    # order by rausnehmen
	  	$orderbyposition = strpos(strtolower($select), 'order by');
	  	if($orderbyposition !== false){
		  	$select = substr($select, 0, $orderbyposition);
	  	}
	    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
	    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
	      $this->formvars['fromwhere'] .= ' where (1=1)';
	    }
    }
    if($this->formvars['stelle'] != ''){
      $stelle = new stelle($this->formvars['stelle'], $this->database);
      $this->layerdaten = $stelle->getLayers(NULL, 'Name');
      if($this->formvars['selected_layers'] != ''){
        $this->selected_layers = explode(', ', $this->formvars['selected_layers']);
        $layerdb = $this->mapDB->getlayerdatabase($this->selected_layers[0], $this->Stelle->pgdbhost);
        $this->attributes = $this->mapDB->getDataAttributes($layerdb, $this->selected_layers[0]);
        $poly_id = $this->mapDB->getPolygonID($this->formvars['stelle'],$this->selected_layers[0]);
        for($i = 1; $i < count($this->selected_layers); $i++){
          $layerdb = $this->mapDB->getlayerdatabase($this->selected_layers[$i], $this->Stelle->pgdbhost);
          $attributes = $this->mapDB->getDataAttributes($layerdb, $this->selected_layers[$i]);
          $this->attributes['name'] = array_intersect($this->attributes['name'], $attributes['name']);
          $next_poly_id = $this->mapDB->getPolygonID($this->formvars['stelle'],$this->selected_layers[$i]);
          if($poly_id != $next_poly_id){
            $showpolygon = false;
          }
          $poly_id = $next_poly_id;
        }
        $keys = array_keys($this->attributes['name']);
        $lastindex = $keys[count($keys)-1];
        for($i = 0; $i < $lastindex+1; $i++){
          if(array_key_exists($i, $this->attributes['name'])){
            $this->formvars['operator_'.$this->attributes['name'][$i]] = '';
            $this->formvars['value_'.$this->attributes['name'][$i]] = '';
          }
        }
        for($j = 0; $j < count($this->selected_layers); $j++){
          $filter = $this->mapDB->readAttributeFilter($this->formvars['stelle'], $this->selected_layers[$j]);
          for($i = 0; $i < count($filter); $i++){
            if($this->formvars['value_'.$filter[$i]['attributname']] == NULL OR
              ($this->formvars['value_'.$filter[$i]['attributname']] == $filter[$i]['attributvalue'] AND
               $this->formvars['operator_'.$filter[$i]['attributname']] == $filter[$i]['operator'])){
              $this->formvars['value_'.$filter[$i]['attributname']] = $filter[$i]['attributvalue'];
              $this->formvars['operator_'.$filter[$i]['attributname']] = $filter[$i]['operator'];
              $setAttributes[$filter[$i]['attributname']]++;
            }
            else{
              $this->formvars['value_'.$filter[$i]['attributname']] = '---- verschieden ----';
            }
          }
        }
        for($i = 0; $i < count($setAttributes); $i++){
          $element = each($setAttributes);
          if($element['value'] < count($this->selected_layers)){
            $this->formvars['value_'.$element['key']] = '---- verschieden ----';
          }
        }
        if ($this->formvars['CMD']!='') {
          # Es soll navigiert werden
          # Navigieren
          $this->navMap($this->formvars['CMD']);
          $this->user->rolle->saveSettings($this->map->extent);
          $this->user->rolle->readSettings();
        }
        else {
          # Zoom zum Polygon des Filters
          if ($poly_id != '' AND $showpolygon == true){
            $PolygonAsSVG = $this->pgdatabase->selectPolyAsSVG($poly_id, $this->user->rolle->epsg_code);
            $PolygonAsSVG = transformCoordsSVG($PolygonAsSVG);    				
            $this->zoomToPolygon('u_polygon', $poly_id,20, $this->user->rolle->epsg_code);
            $this->user->rolle->saveSettings($this->map->extent);
            $this->user->rolle->readSettings();
            $this->formvars['newpath'] = $PolygonAsSVG;
            $PolygonAsText = $this->pgdatabase->selectPolyAsText($poly_id, $this->user->rolle->epsg_code);
            $this->formvars['newpathwkt'] = $PolygonAsText;
            $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
          }
        }
      }
      else{
        # Es soll navigiert werden
        # Navigieren
        $this->navMap($this->formvars['CMD']);
        $this->user->rolle->saveSettings($this->map->extent);
        $this->user->rolle->readSettings();
      }
    }
    else{
      # Es soll navigiert werden
      # Navigieren
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveSettings($this->map->extent);
      $this->user->rolle->readSettings();
    }
  }

  function Filter_speichern($formvars){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    if($formvars['selected_layers'] != ''){
      $this->selected_layers = explode(', ', $formvars['selected_layers']);
      $layerdb = $mapDB->getlayerdatabase($this->selected_layers[0], $this->Stelle->pgdbhost);
      $this->attributes = $mapDB->getDataAttributes($layerdb, $this->selected_layers[0]);
      for($i = 1; $i < count($this->selected_layers); $i++){
        $layerdb = $mapDB->getlayerdatabase($this->selected_layers[$i], $this->Stelle->pgdbhost);
        $attributes = $mapDB->getDataAttributes($layerdb, $this->selected_layers[$i]);
        $this->attributes['name'] = array_intersect($this->attributes['name'], $attributes['name']);
      }
      $keys = array_keys($this->attributes['name']);
      $lastindex = $keys[count($keys)-1];
      for($i = 0; $i < $lastindex+1; $i++){
        if(array_key_exists($i, $this->attributes['name'])){
          if($this->attributes['type'][$i] != 'geometry'){
            //---------- normales Attribut -------------//
            if($formvars['value_'.$this->attributes['name'][$i]] != '' AND $formvars['value_'.$this->attributes['name'][$i]] != '---- verschieden ----'){
              //---------------- einfügen --------------//
              $formvars['attributname'] = $this->attributes['name'][$i];
              $formvars['attributvalue'] = $formvars['value_'.$this->attributes['name'][$i]];
              $formvars['operator'] = $formvars['operator_'.$this->attributes['name'][$i]];
              $formvars['type'] = $this->attributes['type'][$i];
              for($j = 0; $j < count($this->selected_layers); $j++){
                $formvars['layer'] = $this->selected_layers[$j];
                $mapDB->saveAttributeFilter($formvars);
              }
            }
            elseif($formvars['value_'.$this->attributes['name'][$i]] != '---- verschieden ----'){
              //--------------- löschen ----------------//
              for($j = 0; $j < count($this->selected_layers); $j++){
                $mapDB->deleteFilter($formvars['stelle'], $this->selected_layers[$j], $this->attributes['name'][$i]);
              }
            }
          }
          else{   //---------- the_geom -------------//
            if($this->formvars['check_'.$this->attributes['name'][$i]] != ''){
              $polygonWeltkoordinaten = $this->formvars['newpathwkt'];
              if(strpos($polygonWeltkoordinaten,'P') == 0){
                $polygonWeltkoordinaten = str_replace('POLYGON((', 'MULTIPOLYGON(((', $polygonWeltkoordinaten);
                $polygonWeltkoordinaten .= ')';
              }
              if($this->formvars['value_'.$this->attributes['name'][$i]] != '' AND $this->formvars['value_'.$this->attributes['name'][$i]] != '---- verschieden ----'){
                //-------------- update -----------------//
                $this->pgdatabase->updatepolygon($polygonWeltkoordinaten, $this->user->rolle->epsg_code, $this->formvars['value_'.$this->attributes['name'][$i]]);
                $formvars['attributname'] = $this->attributes['name'][$i];
                $formvars['attributvalue'] = $this->formvars['value_'.$this->attributes['name'][$i]];
                $formvars['operator'] = $formvars['operator_'.$this->attributes['name'][$i]];
                $formvars['type'] = $this->attributes['type'][$i];
                for($j = 0; $j < count($this->selected_layers); $j++){
                  $formvars['layer'] = $this->selected_layers[$j];
                  $mapDB->saveAttributeFilter($formvars);
                }
              }
              else{
                //-------------- neu einfügen -----------------//
                $poly_id = $this->pgdatabase->insertpolygon($polygonWeltkoordinaten, $this->user->rolle->epsg_code);
                $formvars['attributname'] = $this->attributes['name'][$i];
                $formvars['attributvalue'] = $poly_id;
                $formvars['operator'] = $formvars['operator_'.$this->attributes['name'][$i]];
                $formvars['type'] = $this->attributes['type'][$i];
                for($j = 0; $j < count($this->selected_layers); $j++){
                  //-------------- wenn vorhanden, alte Polygone löschen ----------//
                  $poly_id = $mapDB->getPolygonID($this->formvars['stelle'],$this->selected_layers[$j]);
                  if($poly_id != NULL){
                    $this->pgdatabase->deletepolygon($poly_id);
                  }
                  $formvars['layer'] = $this->selected_layers[$j];
                  $mapDB->saveAttributeFilter($formvars);
                }
              }
            }
            elseif($this->formvars['value_'.$this->attributes['name'][$i]] != '' AND $formvars['value_'.$this->attributes['name'][$i]] != '---- verschieden ----'){
              //-------------- löschen -----------------//
              for($j = 0; $j < count($this->selected_layers); $j++){
                $mapDB->deleteFilter($formvars['stelle'], $this->selected_layers[$j], $this->attributes['name'][$i]);
              }
              if($mapDB->checkPolygon($this->formvars['value_'.$this->attributes['name'][$i]]) == false){
                $this->pgdatabase->deletepolygon($this->formvars['value_'.$this->attributes['name'][$i]]);
              }
              $this->formvars['newpath'] = NULL;
              $this->formvars['pathwkt'] = NULL;
              $this->formvars['newpathwkt'] = NULL;
              $this->formvars['result'] = NULL;
            }
          }
        }
      }
    }
    for($i = 0; $i < count($this->selected_layers); $i++){
      $filter = $mapDB->readAttributeFilter($this->formvars['stelle'], $this->selected_layers[$i]);
      $mapDB->writeFilter($this->pgdatabase, $filter, $this->selected_layers[$i], $formvars['stelle']);
    }
    $this->Filterverwaltung();
  }

  function StatistikAuswahl() {
    # Abfragen aller Stellen für die Statistik oder Abrechnung
    $this->account = new account($this->database);
    $this->user2 = new user(0,'',$this->database);
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    if($this->formvars['go'] == 'StatistikAuswahl_Stelle'){
    	$this->stellendaten=$this->user->getStellen('Bezeichnung');
    }
    $this->UserDaten=$this->user2->getUserDaten('','','Name');
    $this->titel='Auswahl zur Statistik';
    $this->main='StatistikWaehlen.php';
    $this->output();
  } # END of function StatistikAuswahl

  function StatistikAuswahlErgebnis(){
    # Abfragen und Abfangen von Fehleingaben der Eingabe für Ausgabe der Statistik
    if ($this->formvars['stelle']=='' AND $this->formvars['nutzer']==''){
        $errmsg='Wählen Sie bitte die entsprechende Stelle und/oder den Nutzer aus!';
        $this->Meldung=$errmsg;
        $this->StatistikAuswahl();
        showAlert($this->Meldung);
        return;
    }
    else {
      if($this->Meldung != ''){
        showAlert($this->Meldung);
      }
      if ($this->formvars['stelle']!='' AND $this->formvars['nutzer']=='') {
        $this->formvars['nutzung']='stelle';
      }
      if ($this->formvars['stelle']=='' AND $this->formvars['nutzer']!=''){
        $this->formvars['nutzung']='nutzer';
      }
      if ($this->formvars['stelle']!='' AND $this->formvars['nutzer']!='') {
        $this->formvars['nutzung']='stelle_nutzer';
      }
    }

    if ($this->formvars['zeitraum']=='month' OR $this->formvars['zeitraum']=='week' OR $this->formvars['zeitraum']=='day' OR $this->formvars['zeitraum']=='era') {
        if ($this->formvars['zeitraum']=='month') {
          if ($this->formvars['month_m']=='' OR $this->formvars['year_m']==''){
            $errmsg='Wählen Sie bitte Monat und Jahr aus!';
            $this->Meldung=$errmsg;
            $this->StatistikAuswahl();
            showAlert($this->Meldung);
            return;
          }
        }
        if ($this->formvars['zeitraum']=='week') {
          if ($this->formvars['week_w']=='' OR $this->formvars['year_w']=='') {
            $errmsg='Wählen Sie bitte die gewünschte Kalenderwoche und das Jahr aus!';
            $this->Meldung=$errmsg;
            $this->StatistikAuswahl();
            showAlert($this->Meldung);
          return;
          }
        }
        if ($this->formvars['zeitraum']=='day') {
          if ($this->formvars['day_d']=='' OR $this->formvars['month_d']=='' OR $this->formvars['year_d']=='' ){
            $errmsg='Wählen Sie bitte Tag, Monat und Jahr für die Ausgabe aus!';
            $this->Meldung=$errmsg;
            $this->StatistikAuswahl();
            showAlert($this->Meldung);
          return;
          }
        }
        if ($this->formvars['zeitraum']=='era') {
          if ($this->formvars['day_e1']=='' OR $this->formvars['month_e1']=='' OR $this->formvars['year_e1']=='' OR $this->formvars['day_e2']=='' OR $this->formvars['month_e2']=='' OR $this->formvars['year_e2']=='') {
            $errmsg='Wählen Sie bitte Tag, Monat und Jahr aus, von wann bis wann die Ausgabe erfolgen soll!';
            $this->Meldung=$errmsg;
            $this->StatistikAuswahl();
            showAlert($this->Meldung);
            return;
          }
        }
    }
    else {
        $errmsg='Wählen Sie bitte den Zeitraum für die Statistik aus!';
        $this->Meldung=$errmsg;
        $this->StatistikAuswahl();
        showAlert($this->Meldung);
        return;
    }
    $this->account = new account($this->database);
    $this->account->getStatistik($this->formvars['nutzer'],$this->formvars['nutzung'],$this->formvars['stelle'],$this->formvars['zeitraum'],$this->formvars['day_d'],$this->formvars['week_w'],$this->formvars['month_d'],$this->formvars['month_w'],$this->formvars['month_m'],$this->formvars['year_m'],$this->formvars['year_w'],$this->formvars['year_d'],$this->formvars['day_e1'],$this->formvars['day_e2'],$this->formvars['month_e1'],$this->formvars['month_e2'],$this->formvars['year_e1'],$this->formvars['year_e2']);

    $this->account->ALKA4 = 0;
    $this->account->ALKA3 = 0;
    for($i = 0; $i < count($this->account->ALKNumbOfAccess); $i++){
      if(($this->account->ALKNumbOfAccess[$i]['Druckformat'] == 'A4hoch' OR $this->account->ALKNumbOfAccess[$i]['Druckformat'] == 'A4quer') AND $this->account->ALKNumbOfAccess[$i]['Preis'] > 0){
        $this->account->ALKA4 += $this->account->ALKNumbOfAccess[$i]['NumberOfAccess'];
      }
      else{
        $this->account->ALKA3 += $this->account->ALKNumbOfAccess[$i]['NumberOfAccess'];
      }
    }
    $this->account->ALB = 0;
    for($i = 0; $i < count($this->account->ALBNumbOfAccess); $i++){
        $this->account->ALB += $this->account->ALBNumbOfAccess[$i]['NumberOfAccess'];
    }

    $this->titel='Zugriffsstatistik ';
    switch($this->formvars['nutzung']){
      case 'stelle' : {
        $this->titel .= 'der Stelle '.$this->account->Bezeichnung;
      } break;

      case 'nutzer' : {
        $this->titel .= 'des Nutzers '.$this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'];
      } break;

      case 'stelle_nutzer' : {
        $this->titel .= 'des Nutzers '.$this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'].' in der Stelle '.$this->account->Bezeichnung;
      } break;
    }
    $this->main='StatistikUebersicht.php';
    $this->output();
  }# END of function StatistikAuswahlErgebnis

  function export_georg($formvars){
    $georg = new georg_export();
    $georg->Amt = $georg->get_gemeindedata_from_file($this->formvars['bezeichnung']);
    if($georg->Amt == NULL){
      $this->Meldung = 'Die ausgewählte Stelle entspricht keiner der in der Datei \''.GEORG_AMTS_DATEI.'\' aufgeführten Ämter.';
    }
    else{
      $georg->user = $this->user->Name;
      $georg->ALKA3 = $this->formvars['anzahlA3'];
      $georg->ALKA4 = $this->formvars['anzahlA4'];
      $georg->ALB = $this->formvars['anzahlALB'];
      $document = new Document($this->database);
      $georg->preisALKA4 = $document->get_price('A4hoch');
      $georg->preisALKA3 = $document->get_price('A3hoch');
      $georg->betragALK = ($georg->preisALKA4 * $georg->ALKA4 + $georg->preisALKA3 * $georg->ALKA3)/100;
      $georg->betragALB = ($georg->preisALB * $georg->ALB)/100;
      $georg->endbetrag = $georg->betragALB + $georg->betragALK;
      $faelligtime = time() + (21 * 86400);
      $georg->faellig = date('d.m.Y', $faelligtime);
      if($this->formvars['zeitraum']=='month'){
        $georg->architekt = 'monatliche Abrechnung';
      }
      $georg->write_file();
      $this->Meldung = 'Georg-Datei erzeugt.';
    }

    $this->StatistikAuswahlErgebnis();
  }

  function StyleLabelEditor(){
    $this->user->rolle->nImageWidth = 500;
    $this->user->rolle->nImageHeight = 500;
    if($this->formvars['neuladen']){
      $this->changeMap();
    }
    else{
      $this->loadMap('DataBase');
    }
    $this->main='style_label_editor.php';
    $this->titel='Style- und Labeleditor';
    $this->fonts = $this->getfonts();
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->layerdaten = $mapDB->getall_Layer('Name');
    if($this->formvars['selected_layer_id'] != ''){
      $this->allclassdaten = $mapDB->read_Classes($this->formvars['selected_layer_id']);
      if($this->formvars['selected_class_id'] != ''){
        $this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['selected_class_id']);
        if($this->formvars['selected_style_id'] != ''){
          $this->styledaten = $mapDB->get_Style($this->formvars['selected_style_id']);
        }
        if($this->formvars['selected_label_id'] != ''){
          $this->labeldaten = $mapDB->get_Label($this->formvars['selected_label_id']);
        }
      }
    }
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
    }
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->output();
  }

  function getfonts(){
    $fontset = file(FONTSET);
    for($i = 0; $i < count($fontset); $i++){
      $explosion = explode(' ', trim($fontset[$i]));
      $first = trim(strtok(trim($fontset[$i]), " \n\t"));
      $second = trim(strtok(" \n\t"));
      $fonts['name'][] = $first;
      $fonts['filename'][] = $second;
    }
    return $fonts;
  }

  function createFontSampleImage($fontfile, $fontname){
    $image = imagecreatetruecolor(180,18);
    $backgroundColor = ImageColorAllocate ($image, 255, 255, 255);
    imagefill ($image, 0, 0, $backgroundColor);
    imagecolortransparent($image, $backgroundColor);
    $black = ImageColorAllocate ($image, 0, 0, 0);
    imagettftext($image, 11, 0, 3, 15, $black, dirname(FONTSET).'/'.$fontfile, $fontname);
    $imagename = rand(0, 1000000).'.png';
    imagepng($image, IMAGEPATH.$imagename);
    return TEMPPATH_REL.$imagename;
  }

  function FunktionenAnzeigen(){
    $this->main='funktionen.php';
    # Abfragen aller Funktionen
    $this->funktion = new funktion($this->database);
    $this->funktionen = $this->funktion->getFunktionen(NULL, $this->formvars['order']);
    $this->output();
  }

  function FunktionenFormular(){
    $this->main='funktionen_formular.php';
    if ($this->formvars['selected_function_id']>0) {
      $this->funktion = new funktion($this->database);
      $this->funktionen = $this->funktion->getFunktionen($this->formvars['selected_function_id'], NULL);
      $this->formvars['bezeichnung'] = $this->funktionen[0]['bezeichnung'];
    }
    $this->output();
  }

  function FunktionAnlegen() {
    $this->funktion = new funktion($this->database);
    $ret = $this->funktion->NeuAnlegen($this->formvars);
    if ($ret[0]) {
      # Fehler beim Eintragen der Funktion
      $this->Meldung=$ret[1];
    }
    else {
      $neue_function_id = $ret[1];
      if ($ret[0]) {
        $this->Meldung=$ret[1];
      }
      else {
        $this->Meldung='Daten der Funktion erfolgreich eingetragen!';
      }
    }
    $this->formvars['selected_function_id'] = $neue_function_id;
    $this->FunktionenFormular();
  }

  function FunktionAendern(){
    $this->funktion = new funktion($this->database);
    $ret = $this->funktion->Aendern($this->formvars);
    if($this->formvars['id'] != ''){
      $this->formvars['selected_function_id'] = $this->formvars['id'];
    }
    if ($ret[0]) {
      $this->Meldung=$ret[1];
    }
    else {
      $this->Meldung='Daten der Funktion erfolgreich eingetragen!';
    }
    $this->FunktionenFormular();
  }

  function FunktionLoeschen(){
    $this->main='funktionen.php';
    $this->funktion = new funktion($this->database);
    $ret = $this->funktion->Loeschen($this->formvars);
    $this->funktionen = $this->funktion->getFunktionen(NULL, $this->formvars['order']);
    $this->output();
  }

 /**
  * Läd das Formular zur Eingabe von Benutzerdaten
  *
  * Die Funktion läd das Template userdaten_formular.php trägt existierende Werte vom Benutzer ein, wenn es um eine Änderung geht und stellt die Stellenname zur Auswahl bereit, zu der der Nutzer Zugang hat
  *
  * Reihenfolge: Übersichtssatz - Kommentar - Tags.
  *
  * @see    BenutzerLöschen(), BenutzerdatenAnzeigen(), BenutzerdatenAnlegen(), BenutzerdatenAendern(), $postgres, $alb
  */
  function BenutzerdatenFormular() {
    $this->titel='Benutzerdaten Editor';
    $this->main='userdaten_formular.php';
    # Abfragen der Benutzerdaten wenn eine user_id zur Änderung selektiert ist
    if ($this->formvars['selected_user_id']>0) {
      $this->userdaten=$this->user->getUserDaten($this->formvars['selected_user_id'],'','');
      $this->formvars['nachname']=$this->userdaten[0]['Name'];
      $this->formvars['vorname']=$this->userdaten[0]['Vorname'];
      $this->formvars['loginname']=$this->userdaten[0]['login_name'];
      $this->formvars['Namenszusatz']=$this->userdaten[0]['Namenszusatz'];
      $this->formvars['password_setting_time']=$this->userdaten[0]['password_setting_time'];
      $this->formvars['start']=$this->userdaten[0]['start'];
      $this->formvars['stop']=$this->userdaten[0]['stop'];
      $this->formvars['ips']=$this->userdaten[0]['ips'];
      $this->formvars['phon']=$this->userdaten[0]['phon'];
      $this->formvars['email']=$this->userdaten[0]['email'];
    # Abfragen der Stellen des Nutzers
      $this->selected_user=new user(0,$this->formvars['selected_user_id'],$this->user->database);
      $this->formvars['selstellen']=$this->selected_user->getStellen(0);
    }
    # Abfragen aller möglichen Stellen
    $this->formvars['stellen']=$this->Stelle->getStellen('Bezeichnung');
    $this->output();
  }

  function BenutzerLöschen(){
    $this->selected_user=new user(0,$this->formvars['selected_user_id'],$this->user->database);
    $stellen = $this->selected_user->getStellen(0);
    $this->selected_user->Löschen($this->formvars['selected_user_id']);
    $this->user->rolle->deleteRollen($this->formvars['selected_user_id'], $stellen['ID']);
    $this->user->rolle->deleteMenue($this->formvars['selected_user_id'], $stellen['ID'], 0);
    $this->user->rolle->deleteGroups($this->formvars['selected_user_id'], $stellen['ID']);
    $this->user->rolle->deleteLayer($this->formvars['selected_user_id'], $stellen['ID'], 0);
    $this->titel='Benutzerdaten';
    $this->main='userdaten.php';
    # Abfragen aller Benutzer
    $this->userdaten=$this->user->getUserDaten(0,'',$this->formvars['order']);
    $this->output();
  }

  function BenutzerdatenAnzeigen() {
  	if($this->formvars['order'] == ''){
      $this->formvars['order'] = 'Name';
    }
    $this->titel='Benutzerdaten';
    $this->main='userdaten.php';
    # Abfragen aller Benutzer
    $this->userdaten=$this->user->getUserDaten(0,'',$this->formvars['order']);
    $this->output();
  }

  function BenutzerdatenAnlegen() {
    $ret=$this->user->checkUserDaten($this->formvars);
    if ($ret[0]) {
      # Fehler bei der Formulareingabe
      $this->Meldung=$ret[1];
    }
    else{
      $ret=$this->user->NeuAnlegen($this->formvars);
      if ($ret[0]) {
        # Fehler beim Eintragen der Benutzerdaten
        $this->Meldung=$ret[1];
      }
      else {
        $neue_user_id=$ret[1];
        $stellen = explode(', ',$this->formvars['selstellen']);
        $this->user->rolle->setRollen($neue_user_id,$stellen);
        $this->user->rolle->setMenue($neue_user_id,$stellen);
        $this->user->rolle->setLayer($neue_user_id, $stellen, 0);
				for($i = 0; $i < count($stellen); $i++){
					$stelle = new stelle($stellen[$i], $this->database);
					$layers = $stelle->getLayers(NULL);
					$this->user->rolle->setGroups($neue_user_id, array($stellen[$i]), $layers['ID'], 0);
				}
        if ($ret[0]) {
          $this->Meldung=$ret[1];
        }
        else {
          $this->Meldung='Daten des Benutzers erfolgreich eingetragen!';
        }
      }
    }
    $this->formvars['selected_user_id'] = $neue_user_id;
    $this->BenutzerdatenFormular();
  }

  function BenutzerdatenAendern() {
    $ret=$this->user->checkUserDaten($this->formvars);
    if ($ret[0]) {
      # Fehler bei der Formulareingabe
      $this->Meldung=$ret[1];
    }
    else {
      $stellen = explode(', ',$this->formvars['selstellen']);
      $ret=$this->user->Aendern($this->formvars);
      if($this->formvars['id'] != ''){
        $this->formvars['selected_user_id'] = $this->formvars['id'];
      }
      $this->user->rolle->setRollen($this->formvars['selected_user_id'],$stellen);
      $this->user->rolle->setMenue($this->formvars['selected_user_id'],$stellen);
      $this->user->rolle->setLayer($this->formvars['selected_user_id'], $stellen, 0);
			for($i = 0; $i < count($stellen); $i++){
				$stelle = new stelle($stellen[$i], $this->database);
				$layers = $stelle->getLayers(NULL);
				$this->user->rolle->setGroups($this->formvars['selected_user_id'], array($stellen[$i]), $layers['ID'], 0);
			}
      $this->selected_user=new user(0,$this->formvars['selected_user_id'],$this->user->database);
      # Löschen der in der Selectbox entfernten Stellen
      $userstellen =  $this->selected_user->getStellen(0);
      for($i = 0; $i < count($userstellen['ID']); $i++){
        $found = false;
        for($j = 0; $j < count($stellen); $j++){
          if($stellen[$j] == $userstellen['ID'][$i]){
            $found = true;
          }
        }
        if($found == false){
          $deletestellen[] = $userstellen['ID'][$i];
        }
      }
      $this->user->rolle->deleteRollen($this->formvars['selected_user_id'], $deletestellen);
      $this->user->rolle->deleteMenue($this->formvars['selected_user_id'], $deletestellen, 0);
      $this->user->rolle->deleteGroups($this->formvars['selected_user_id'], $deletestellen);
      $this->user->rolle->deleteLayer($this->formvars['selected_user_id'], $deletestellen, 0);
      # Überprüfen ob alte Stelle noch gültig ist
      $this->selected_user->checkstelle();
      if ($ret[0]) {
        # Fehler beim Ändern der Benutzerdaten
        $this->Meldung=$ret[1];
      }
      else {
        $this->Meldung='Daten des Benutzers erfolgreich eingetragen!';
      }
    }
    $this->BenutzerdatenFormular();
  }

	function BenutzerNachStellenAnzeigen(){
    $this->titel='Benutzer-Stellen-Übersicht';
    $this->main='userstellendaten.php';
    # Abfragen aller Stellen
    $this->stellen = $this->Stelle->getStellen('Bezeichnung');
    for($i = 0; $i < count($this->stellen['ID']); $i++){
    	# Abfragen der Benutzer der Stelle
    	$stelle = new stelle($this->stellen['ID'][$i], $this->database);
    	$this->stellen['user'][$i] = $stelle->getUser();
    }
    $this->unassigned_users = $this->user->get_Unassigned_Users();
    $all_users = $this->user->getall_Users(NULL);
    $this->user_count = count($all_users['ID']); 
    $this->output();
  }
  
  function BenutzerderStelleAnzeigen(){
    $this->titel='Benutzer-Stellen-Übersicht';
    $this->main='userstellendaten.php';
		$this->stellen['ID'][0] = $this->Stelle->id;
		$this->stellen['Bezeichnung'][0] = $this->Stelle->Bezeichnung;
		# Abfragen der Benutzer der Stelle
		$this->stellen['user'][0] = $this->Stelle->getUser();
    $this->output();
  }
  
  function LayerUebersicht(){
  	$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel='Themenübersicht';
    $this->main='layer_uebersicht.php';
    # Abfragen aller Layer
    $this->layer = $mapDB->getall_Layer('Gruppenname, Name');
    $this->output();
  }

  function geothermie_start() {
    $this->loadMap('DataBase');
    $this->titel='Auslegung von Erdw&auml;rmesonden';
    $this->main="geothermieabfrageformular.php";
    $this->navMap($this->formvars['CMD']);
    $this->saveMap('');
    $this->drawMap();
    $this->output();
  }

  function geothermie_anfrage() {
    $border=25;
    if ($this->formvars['entzugsenergie']=='' OR $this->formvars['loc_x']=='' OR $this->formvars['loc_y']==''){
      $errmsg='Die Angaben sind nicht vollständig!';
      $this->Meldung=$errmsg;
      $this->geothermie_start();
      showAlert($this->Meldung);
      return;
    }
#### flurstücksuchen
    $pointobj=ms_newPointObj();
    $pointobj->setXY($this->formvars['loc_x'],$this->formvars['loc_y']);
    $flurstueck=new flurstueck('',$this->database);
    $Flurst=$flurstueck->getFlurstByPoint($pointobj);
#   echo("FKz: ".$Flurst);

#### zoomtoflurstueck
    $this->loadMap('DataBase');
    $bounds=$Flurst['bounds'];
    $randx=($bounds->maxx-$bounds->minx)*$border/100;
    $randy=($bounds->maxy-$bounds->miny)*$border/100;
    $this->map->setextent($bounds->minx-$randx,$bounds->miny-$randy,$bounds->maxx+$randx,$bounds->maxy+$randy);
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Ausweisen der Erdw&auml;rmesonden';
    $this->main="geothermieeingabeformular.php";
    $this->output();
  }

  function nachweisSuchparameterSetzen() {

  }

  function suchparameterSetzen() {
    # speichern der Suchparameter und der Markierungsparameter
    if ($this->formvars['f'] OR $this->formvars['k'] OR $this->formvars['g']) {
      if (!$this->formvars['f']) {
        $this->formvars['f']='0';
      }
      if (!$this->formvars['k']) {
        $this->formvars['k']='0';
      }
      if (!$this->formvars['g']) {
        $this->formvars['g']='0';
      }
      $this->formvars['art_einblenden']=$this->formvars['f'].$this->formvars['k'].$this->formvars['g'];
    }

    $_SESSION['f']=$this->formvars['f'];
    $_SESSION['k']=$this->formvars['k'];
    $_SESSION['g']=$this->formvars['g'];

    if ($this->formvars['art_einblenden']!='') {
      $_SESSION['art_einblenden']=$this->formvars['art_einblenden'];
    }
      if ($this->formvars['art_einblenden']=='111'){
        $_SESSION['f']='1' AND $_SESSION['k']='1' AND $_SESSION['g']='1';
      }
      if ($this->formvars['art_einblenden']=='100'){
        $_SESSION['f']='1';
      }
      if ($this->formvars['art_einblenden']=='010'){
        $_SESSION['k']='1';
      }
      if ($this->formvars['art_einblenden']=='001'){
        $_SESSION['g']='1';
      }
      if ($this->formvars['art_einblenden']=='110'){
        $_SESSION['f']='1' AND $_SESSION['k']='1' AND $_SESSION['g']='0';
      }
      if ($this->formvars['art_einblenden']=='101'){
        $_SESSION['f']='1' AND $_SESSION['g']='1' AND $_SESSION['k']='0';
      }
      if ($this->formvars['art_einblenden']=='011'){
        $_SESSION['k']='1' AND $_SESSION['g']='1' AND $_SESSION['f']='0' ;
      }

    if ($this->formvars['art_markieren']!='') {
      $_SESSION['art_markieren']=$this->formvars['art_markieren'];
    }
    if ($this->formvars['abfrage_art']!='') {
      $_SESSION['abfrage_art']=$this->formvars['abfrage_art'];
    }
    if($this->formvars['FlurID']!=''){
      $_SESSION['FlurID']=$this->formvars['FlurID'];
    }
    if($this->formvars['stammnr']!=''){
      $_SESSION['stammnr']=$this->formvars['stammnr'];
    }
  	if($this->formvars['rissnummer']!=''){
      $_SESSION['rissnummer']=$this->formvars['rissnummer'];
    }
    if($this->formvars['antr_nr_a']!=''){
      $_SESSION['antr_nr_a']=$this->formvars['antr_nr_a'];
    }
    if($this->formvars['antr_nr_b']!=''){
      $_SESSION['antr_nr_b']=$this->formvars['antr_nr_b'];

    }
    if($this->formvars['antr_nr']!=''){
      $_SESSION['antr_nr']=$this->formvars['antr_nr'];
    }
    if($this->suchpolygon!=''){
      $_SESSION['suchpolygon']=$this->suchpolygon;
    }
  }

  function suchparameterLesen() {
    $this->formvars['art_einblenden']=$_SESSION['art_einblenden'];
    $this->formvars['art_markieren']=$_SESSION['art_markieren'];
    $this->formvars['abfrage_art']=$_SESSION['abfrage_art'];
    $this->formvars['FlurID']=$_SESSION['FlurID'];
    $this->formvars['stammnr']=$_SESSION['stammnr'];
    $this->formvars['rissnummer']=$_SESSION['rissnummer'];
    $this->formvars['antr_nr_a']=$_SESSION['antr_nr_a'];
    $this->formvars['antr_nr_b']=$_SESSION['antr_nr_b'];
    $this->formvars['f']=$_SESSION['f'];
    $this->formvars['k']=$_SESSION['k'];
    $this->formvars['g']=$_SESSION['g'];
    $this->formvars['antr_nr']=$_SESSION['antr_nr'];
    $this->suchpolygon=$_SESSION['suchpolygon'];
  }

  function DokumenteZuAntraegeAnzeigen() {
    #echo 'antr'.$this->formvars['antr_selected'];
    $this->formvars['suchffr']=1;
    $this->formvars['suchkvz']=1;
    $this->formvars['suchgn']=1;
    $this->formvars['suchan']=1;
    $this->formvars['suchantrnr']=$this->formvars['antr_selected'];
    $this->formvars['abfrageart']='antr_nr';
    $this->nachweiseRecherchieren();
  }

  function nachweiseRecherchieren() {
    # Suchparameter, die neu gesetzt worden sind in formvars, sollen übernommen werden und gespeichert werden
    # für späterer Suchanfragen und die anderen sollen aus der Datenbank abgefragt werden.
    # Setzen von Such- und Anzeigeparametern die neu gesetzt worden sind
    # (nur neu gesetzte werden überschrieben)
    if ($this->formvars['abfrageart']=='poly') {
      $this->formvars['suchpolygon'] = $this->formvars['newpathwkt'];
    }
    $this->user->rolle->setNachweisSuchparameter($this->formvars['suchffr'],$this->formvars['suchkvz'],$this->formvars['suchgn'], $this->formvars['suchan'], $this->formvars['abfrageart'],$this->formvars['suchgemarkung'],$this->formvars['suchflur'],$this->formvars['suchstammnr'],$this->formvars['suchrissnr'],$this->formvars['suchfortf'],$this->formvars['suchpolygon'],$this->formvars['suchantrnr']);
    # Die Anzeigeparameter werden so gesetzt, daß genau das gezeigt wird, wonach auch gesucht wurde.
    # bzw. was als Suchparameter im Formular angegeben wurde.
    $this->user->rolle->setNachweisAnzeigeparameter($this->formvars['suchffr'],$this->formvars['suchkvz'],$this->formvars['suchgn'],$this->formvars['suchan'],$this->formvars['suchffr'],$this->formvars['suchkvz'],$this->formvars['suchgn']);
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $this->formvars = array_merge($this->formvars, $this->user->rolle->getNachweisParameter());
    # Nachweisobjekt bilden
    $this->nachweis = new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    # Suchparameter in Ordnung
    # Recherchieren nach den Nachweisen
    $ret=$this->nachweis->getNachweise(0,$this->formvars['suchpolygon'],$this->formvars['suchgemarkung'],$this->formvars['suchstammnr'],$this->formvars['suchrissnr'],$this->formvars['suchfortf'],$this->formvars['art_einblenden'],$this->formvars['richtung'],$this->formvars['abfrageart'], $this->formvars['order'],$this->formvars['suchantrnr'], $this->formvars['datum'], $this->formvars['VermStelle'], $this->formvars['gueltigkeit'], $this->formvars['datum2'], $this->formvars['suchflur']);
    #$this->nachweis->getAnzahlNachweise($this->formvars['suchpolygon']);
    if($ret!=''){
      # Fehler bei der Recherche im Datenbestand
      $this->rechercheFormAnzeigen();
      ?><script type="text/javascript">
      alert("<?php echo $ret; ?>");
      </script><?php
    }
    else {
      # Recherche erfolgreich verlaufen
      if ($this->nachweis->erg_dokumente==0) {
        # Keine Dokumente zur Auswahl gefunden.
        $this->rechercheFormAnzeigen();
        ?><script type="text/javascript">
        alert("Es konnten keine Dokumente zu der Auswahl gefunden werden.\nWählen Sie neue Suchparameter.");
        </script><?php
      }
      else {
        # Anzeige des Rechercheergebnisses
        $this->nachweisAnzeige();
      }
    }
  }

  function changeMap() {
  	# Scrollposition der Legende wird gespeichert
  	$this->user->rolle->setScrollPosition($this->formvars['scrollposition']);
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    # Ein- oder Ausblenden der Klassen
    $this->user->rolle->setClassStatus($this->formvars);
    # Wenn ein Button im Kartenfenster gewählt wurde,
    # werden auch die Einstellungen aus der Legende übernommen
    $this->user->rolle->setAktivLayer($this->formvars,$this->Stelle->id,$this->user->id);
    $this->user->rolle->setQueryStatus($this->formvars);
    # Karteninformationen lesen
    $this->loadMap('DataBase');
    # zwischenspeichern des vorherigen Maßstabs
    $oldscale=round($this->map_scaledenom);
    # Zoom auf den in der Referenzkarte ausgewählten Ausschnitt
    if ($this->formvars['refmap_x'] > 0) {
      $this->zoomToRefExt();
    }
    else {
      # Wenn ein Navigationskommando ausgewählt/übergeben wurde
      # Zoom/Pan auf den in der Karte ausgewählten Ausschnitt
      if ($this->formvars['CMD']!='') {
        $this->navMap($this->formvars['CMD']);
      }
    }
    if ($oldscale!=$this->formvars['nScale'] AND $this->formvars['nScale'] != '') {
      # Zoom auf den in der Maßstabsauswahl ausgewählten Maßstab
      # wenn er sich von der vorherigen Maßstabszahl unterscheidet
      # (das heißt wenn eine andere Zahl eingegeben wurde)
      $this->scaleMap($this->formvars['nScale']);
    }
  }
  
  function zoom2coord(){
  	$this->zoomMap(1);
  	$this->scaleMap(5000);
  }
  
	function zoom2wkt(){
    $rect = $this->pgdatabase->getWKTBBox($this->formvars['wkt'], $this->formvars['epsg'], $this->user->rolle->epsg_code);
    $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
		if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

# 2006-03-20 pk
  function zoomToStoredMapExtent($storetime){
    # Karteninformationen lesen
    $this->loadMap('DataBase');
    # Abfragen der gespeicherten Kartenausdehnung
    $ret=$this->user->rolle->getConsume($storetime);
    if ($ret3[0]) {
      $this->errmsg="Der gespeicherte Kartenausschnitt konnte nicht abgefragt werden.<br>".$ret[1];
    }
    else {

      $this->user->rolle->set_last_time_id($storetime);
      $this->user->rolle->newtime = $storetime;
      $this->map->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
    	if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
      #echo '<br>gewechselt auf Einstellung von:'.$this->consumetime;
    }
    $this->saveMap('');
    $this->drawMap();
    $this->output();
  }

  # 2006-03-20 pk
  function setPrevMapExtent($consumetime) {
    $currentextent = ms_newRectObj();
    $prevextent = ms_newRectObj();
    $currentextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $prevextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $ret = $this->user->rolle->getConsume($consumetime);
    $i = 0;
    while($i < 100 AND (string)$currentextent->minx == (string)$prevextent->minx AND (string)$currentextent->miny == (string)$prevextent->miny AND (string)$currentextent->maxx == (string)$prevextent->maxx AND (string)$currentextent->maxy == (string)$prevextent->maxy){
      # Setzen des next Wertes des vorherigen Kartenausschnittes
      $prevtime=$ret[1]['prev'];
      $this->user->rolle->newtime = $prevtime;
      if (!($prevtime=='' OR $prevtime=='2006-09-29 12:55:50')) {
        $ret=$this->user->rolle->updateNextConsumeTime($prevtime,$consumetime);
        if ($ret[0]) {
          $this->errmsg="Der Nachfolger für den letzten Kartenausschnitt konnte nicht eingetragen werden.<br>".$ret[1];
        }
        else {
          # Abfragen der vorherigen Kartenausdehnung
          $ret=$this->user->rolle->getConsume($prevtime);
          if ($ret[0]) {
            $this->errmsg="Der letzte Kartenausschnitt konnte nicht abgefragt werden.<br>".$ret[1];
          }
          else {
           $consumetime = $prevtime;
           $prevextent->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
            #echo '<br>gewechselt auf Einstellung von:'.$consumetime;
          }
        }
      }
      $i++;
    }
    $this->user->rolle->set_last_time_id($prevtime);
    $this->map->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  # 2006-03-20 pk
  function setNextMapExtent($consumetime) {
    $currentextent = ms_newRectObj();
    $nextextent = ms_newRectObj();
    $currentextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $nextextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    # Abfragen der nächsten Kartenausdehnung
    $ret = $this->user->rolle->getConsume($consumetime);
    $i = 0;
    while($i < 100 AND (string)$currentextent->minx == (string)$nextextent->minx AND (string)$currentextent->miny == (string)$nextextent->miny AND (string)$currentextent->maxx == (string)$nextextent->maxx AND (string)$currentextent->maxy == (string)$nextextent->maxy){
      $lasttime = $nexttime;
      $nexttime=$ret[1]['next'];
      if($nexttime == NULL){
        $nexttime = $lasttime;
        $i = 100;
      }
      $this->user->rolle->newtime = $nexttime;
      $ret=$this->user->rolle->getConsume($nexttime);
      if ($ret[0]) {
        $this->errmsg="Der nächste Kartenausschnitt konnte nicht abgefragt werden.<br>".$ret[1];
      }
      else {
        $nextextent->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
        #echo '<br>gewechselt auf Einstellung von:'.$this->consumetime;
      }
      $i++;
    }
    $this->user->rolle->set_last_time_id($ret[1]['time_id']);
    $this->map->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  # 2006-03-20 pk
  function mapCommentForm() {
    $this->titel='Kommentar zum Kartenausschnitt';
    $this->main='MapCommentForm.php';
    $this->loadMap('DataBase');
    $this->drawMap();
    $this->output();
  }

  # 2006-03-20 pk
  function mapCommentStore() {
    $ret=$this->user->rolle->insertMapComment($this->formvars['consumetime'],$this->formvars['comment']);
    $this->Fehlermeldung='Kommentar zum Kartenausschnitt gespeichert';
    $this->go='changemenue';
    $ret=$this->user->rolle->getConsume($this->formvars['consumetime']);
    if ($ret[0]) {
      $this->errmsg="Der nächste Kartenausschnitt konnte nicht abgefragt werden.<br>".$ret[1];
    }
    else {
      $this->consumetime=$ret[1]['time_id'];
      $this->user->rolle->newtime = $this->consumetime;
    }
    $this->loadMap('DataBase');
    $this->drawMap();
    $this->output();
  }

  function DeleteStoredMapExtent(){
    $this->user->rolle->deleteMapComment($this->formvars['storetime']);
    $this->mapCommentSelectForm();
  }

  # 2006-03-20 pk
  function mapCommentSelectForm() {
    $this->titel='Gespeicherte Kartenausschnitte wählen';
    $this->main='MapCommentSelectForm.php';
    $ret=$this->user->rolle->getMapComments(NULL);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine gespeicherten Kartenausschnitte abgefragt werden.<br>'.$ret[1];
    }
    else {
      $this->mapComments=$ret[1];
    }
    $this->output();
  }

  function vermessungsantragAnlegen() {
    $this->antrag= new antrag('',$this->pgdatabase);
    $ret=$this->antrag->pruefe_antrag_eintragen($this->formvars['antr_nr'],$this->formvars['VermStelle'],$this->formvars['verm_art'],$this->formvars['datum']);
    if($ret==''){
      $ret=$this->antrag->antrag_eintragen($this->formvars['antr_nr'],$this->formvars['VermStelle'],$this->formvars['verm_art'],$this->formvars['datum']);
    }
    $this->Meldung=$ret;
    $this->titel='Neuen Antrag anlegen';
    $this->vermessungsAntragEingabeForm();
    showAlert($ret);
  }

  function vermessungsantragAendern() {
    $this->antrag= new antrag('',$this->pgdatabase);
    $ret=$this->antrag->antrag_aendern($this->formvars['antr_nr'],$this->formvars['VermStelle'],$this->formvars['verm_art'],$this->formvars['datum']);
    if ($ret[0]) {
      $this->vermessungsantragsFormular();
    }
    else {
      $this->Antraege_Anzeigen();
    }
    showAlert($ret[1]);
  }

  function Datei_Download($filename) {
    $this->formvars['filesize'] = filesize(IMAGEPATH.basename($filename));
    $this->formvars['filename'] = $filename;
    $this->titel='Datei-Download';
    $this->main='dateidownload.php';
    $this->output();
  }

  function Antraege_Anzeigen() {
    $this->menue='menue.php';
    $this->titel='Antr&auml;ge';
    $this->main='antragsanzeige.php';
    $this->antrag = new antrag('',$this->pgdatabase);
    $this->antrag->getAntraege('','',$this->formvars['richtung'],$this->formvars['order']);
    $this->output();
  }

  function Antrag_Loeschen(){
    # 2006-01-30 pk
    if ($this->formvars['bestaetigung']=='JA') {
      $this->antrag = new antrag('',$this->pgdatabase);
      $antragsnummern=array_keys ($this->formvars['id']);
      $ret=$this->antrag->antrag_loeschen($antragsnummern[0]);
      $this->Antraege_Anzeigen();
      showAlert($ret);
    }
    else {
      if ($this->formvars['bestaetigung']=='NEIN') {
        $this->Antraege_Anzeigen();
      }
      else {
        #$this->formvars['nachfrage_quelle']='Antrag_loeschen';
        $this->formvars['nachfrage']='Möchten Sie den Antrag ['.$this->formvars['antr_nr'].'] wirklich löschen?<br>Es werden auch alle im Rechercheordner zusammengestellten Dokumente des Auftrages gelöscht!';
        $this->formvars['id']=$this->formvars['antr_nr'];
        $this->bestaetigungsformAnzeigen();
      }
    }
  }

  function erzeugenUebergabeprotokollNachweise($antr_nr) {
    if ($antr_nr==''){
      $this->Antraege_Anzeigen();
      showAlert('Wählen Sie bitte eine Antragsnummer aus! ');
    }
    else {
      $this->antrag = new antrag($antr_nr,$this->pgdatabase);
      $ret=$this->antrag->getFFR($this->formvars);
      if ($ret[0]) {
        $this->Fehlermeldung=$ret[1];
        # Abbruch mit Fehlermeldung und Rücksprung in Auswahl
        $this->Antraege_Anzeigen();
      }
      else{
      	$this->main = 'uebergabeprotokoll.php';
      	$this->titel = 'Übergabeprotokoll zusammenstellen';
        $this->output();
      }
    }
  }
  
  function erzeugenUebergabeprotokollNachweise_PDF(){
  	# Erzeugen des Übergabeprotokolls mit der Zuordnung der Nachweise zum gewählten Auftrag als PDF-Dokument
  	if($this->formvars['antr_selected'] == ''){
      $this->Antraege_Anzeigen();
      showAlert('Wählen Sie bitte eine Antragsnummer aus! ');
    }
    else {
      $this->antrag = new antrag($this->formvars['antr_selected'],$this->pgdatabase);
      $ret=$this->antrag->getFFR($this->formvars);
      if ($ret[0]) {
        $this->Fehlermeldung=$ret[1];
        # Abbruch mit Fehlermeldung und Rücksprung in Auswahl
        $this->Antraege_Anzeigen();
      }
      else{
		    include (PDFCLASSPATH."class.ezpdf.php");
		    $pdf=new Cezpdf();
		    $pdf=$this->antrag->erzeugenUbergabeprotokoll_PDF($this->formvars);
		    $this->pdf=$pdf;
		    $dateipfad=IMAGEPATH;
		    $currenttime = date('Y-m-d_H:i:s',time());
		    $name = umlaute_umwandeln($this->user->Name);
		    $dateiname = $name.'-'.$currenttime.'.pdf';
		    $this->outputfile = $dateiname;
		    $fp=fopen($dateipfad.$dateiname,'wb');
		    fwrite($fp,$this->pdf->ezOutput());
		    fclose($fp);
		    $this->mime_type='pdf';
		    $this->output();
      }
    }
  }
  
  function erzeugenUebergabeprotokollNachweise_CSV(){
  	# Erzeugen des Übergabeprotokolls mit der Zuordnung der Nachweise zum gewählten Auftrag als CSV-Dokument
  	if($this->formvars['antr_selected'] == ''){
      $this->Antraege_Anzeigen();
      showAlert('Wählen Sie bitte eine Antragsnummer aus! ');
    }
    else {
      $this->antrag = new antrag($this->formvars['antr_selected'],$this->pgdatabase);
      $ret=$this->antrag->getFFR($this->formvars);
      if ($ret[0]) {
        $this->Fehlermeldung=$ret[1];
        # Abbruch mit Fehlermeldung und Rücksprung in Auswahl
        $this->Antraege_Anzeigen();
      }
      else{
		    $csv=$this->antrag->erzeugenUbergabeprotokoll_CSV($this->formvars);
		    ob_end_clean();
		    header("Content-type: application/vnd.ms-excel");
		    header("Content-disposition:  inline; filename=Übergabeprotokoll_".date('Y-m-d_G-i-s').".csv");
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    print utf8_decode($csv);
      }
    }
  }

  function vermessungsantragsFormular() {
    if ($this->formvars['antr_nr']!=''){
      $this->titel='Antrag überarbeiten';
      # Antragsdaten aus der Dtaenbank abfragen
      $this->antrag = new antrag('',$this->pgdatabase);
      $ret=$this->antrag->getAntraege(array($this->formvars['antr_nr']),'',$this->formvars['richtung'],$this->formvars['order']);
      if ($ret[0]==0) {
        $this->formvars['verm_art']=$this->antrag->antragsliste[0]['verm_art'];
        $this->formvars['antr_nr']=$this->antrag->antragsliste[0]['antr_nr'];
        $this->formvars['datum']=$this->antrag->antragsliste[0]['datum'];
        $this->formvars['VermStelle']=$this->antrag->antragsliste[0]['vermstelle'];
        $this->formvars['antr_nr_a']=$this->antrag->antragsliste[0]['antr_nr_a'];
        $this->formvars['antr_nr_b']=$this->antrag->antragsliste[0]['antr_nr_b'];
        $this->formvars['go']='Antrag_Aendern';
        $this->vermessungsAntragEingabeForm();
      }
      else {
        $this->Antraege_Anzeigen();
        showAlert($ret[1]);
      }
    }
    else {
      $this->titel='Antrag eingeben';
      $this->formvars['go']='Nachweis_antragsnummer_Senden';
      $this->vermessungsAntragEingabeForm();
    }
  }

	function check_nachweis_poly(){
		$this->nachweis = new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
		if(ALKIS){echo $this->nachweis->check_poly_in_flurALKIS($this->formvars['umring'], $this->formvars['flur'], $this->formvars['gemkgschl'], $this->user->rolle->epsg_code);}
		else{echo $this->nachweis->check_poly_in_flur($this->formvars['umring'], $this->formvars['flur'], $this->formvars['gemkgschl'], $this->user->rolle->epsg_code);}
	}

  function nachweisFormSenden() {
    #2005-11-24_pk
    $this->nachweis = new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    # Aus Formularvariablen zusammengesetzte Werte bilden.
    # Zusammensetzen der flurid
    $this->formvars['flurid']=$this->formvars['Gemarkung'].str_pad(intval(trim($this->formvars['Flur'])),3,'0',STR_PAD_LEFT);
    # Umwandeln des Kodes für die Dokumentenarten in eine Abkürzung
    $this->formvars['artname']=ArtCode2Abk($this->formvars['art']);
    # Zusammensetzen der übergebenen Parameter für das Polygon
    $this->formvars['umring'] = $this->formvars['newpathwkt'];
    ######################################
    # Eingabe eines neuen Dokumentes
    if ($this->formvars['id']=='') {
      # Prüfen der Eingabewerte
      #echo '<br>Prüfen der Eingabewerte.';
      $ret=$this->nachweis->pruefeEingabedaten($this->formvars['datum'],$this->formvars['VermStelle'],$this->formvars['art'],$this->formvars['gueltigkeit'],$this->formvars['stammnr'],$this->formvars['rissnummer'], $this->formvars['fortfuehrung'], $this->formvars['Blattformat'],$this->formvars['Blattnr'],$this->formvars['changeDocument'],$this->formvars['Bilddatei_name'],$this->formvars['pathlength'],$this->formvars['umring']);
      if ($ret[0]) {
        #echo '<br>Ergebnis der Prüfung: '.$ret;
        $errmsg=$ret[1];
      }
      else {
        #echo '<br>Prüfung der Eingabewerte ok';
        # 2. Eingabewerte in Ordnung
        # 2.1 Speichern der Bilddatei zum Nachweis auf dem Server
        # Zusammensetzen des Dateinamen unter dem das Dokument gespeichert werden soll.
        $this->formvars['zieldateiname']=$this->nachweis->getZielDateiName($this->formvars);
        $ret=$this->nachweis->dokumentenDateiHochladen($this->formvars['flurid'], $this->nachweis->buildNachweisNr($this->formvars[NACHWEIS_PRIMARY_ATTRIBUTE], $this->formvars[NACHWEIS_SECONDARY_ATTRIBUTE]),$this->formvars['artname'],$this->formvars['Bilddatei'],$this->formvars['zieldateiname']);
        if ($ret!='') { $errmsg=$ret; }
        else {
          # Speicherung der Bilddatei erfolgreich, Eintragen in Datenbank
          $this->nachweis->database->begintransaction();
          $ret=$this->nachweis->eintragenNeuesDokument($this->formvars['datum'],$this->formvars['flurid'],$this->formvars['VermStelle'], $this->formvars['art'], $this->formvars['andere_art'], $this->formvars['gueltigkeit'],$this->formvars['stammnr'],$this->formvars['Blattformat'],$this->formvars['Blattnr'],$this->formvars['rissnummer'],$this->formvars['fortfuehrung'],$this->formvars['bemerkungen'],$this->formvars['artname']."/".$this->formvars['zieldateiname'],$this->formvars['umring']);
          if ($ret[0]) {
            $this->nachweis->database->rollbacktransaction();
            $errmsg=$ret[1];
          }
          else {
            $this->nachweis->database->committransaction();
            # Alle Aufgaben erfolgreich ausgeführt
            $errmsg='Daten zum neuen Dokument erfolgreich eingetragen!';
          } # ende Speicherung der Metadaten war erfolgreich
        } # ende Speicherung der Bilddatei war erfolgreich
      } # ende Prüfung war erfolgreich
      # Auswertung/Behandlung bei Aufgetretenen Fehlern
      $this->Meldung=$errmsg;
      $this->nachweisFormAnzeige();
      showAlert($this->Meldung);
    } # ende Fall Eintragen Daten zum neuen Dokument
    else {
      ##################################################
      # 1.2. Änderung eines vorhandenen Dokumentes
      $ret=$this->nachweis->changeDokument($this->formvars);
      $this->Meldung=$ret[1];
      if ($ret[0]) {
        # Die Änderung wurde auf Grund eines Fehlers nicht durchgeführt
        # 1.3 Zurück zum Änderungsformular mit Anzeige der Fehlermeldung
        $this->nachweisFormAnzeige();
        showAlert($this->Meldung);
      } # end of fehler bei der Änderung
      else {
      # 1.4 Zur zur Anzeige der Rechercheergebnisse mit Meldung über Erfolg der Änderung
      # 1.4.1 Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
      $this->formvars=$this->user->rolle->getNachweisParameter();
      $ret=$this->nachweis->getNachweise(0,$this->formvars['suchpolygon'],$this->formvars['suchgemarkungflurid'],$this->formvars['suchstammnr'],$this->formvars['suchrissnr'],$this->formvars['suchfortf'],$this->formvars['art_einblenden'],$this->formvars['richtung'],$this->formvars['abfrageart'], $this->formvars['order'],$this->formvars['suchantrnr']);
      # 1.4.2 Anzeige der Rechercheergebnisse
      $this->nachweisAnzeige();
      # 1.4.3 Anzeige der Erfolgsmeldung
        showAlert($this->Meldung);
      } # end of Änderung war erfolgreich
    }
    return 1;
  }

  function composePolygonWKTString($pathx,$pathy,$minx,$miny,$scale) {
    # Bildung des WKT-Stings für das Umringpolygon aus den Formularwerten
    $pix_rechts=explode(',',$pathx);
    # Prüfen ob die übergebenen Werte 3 Eckpunkte für ein Polygon beinhalten
    if (count($pix_rechts)<3) { # die Anzahl der übergebenen Rechtswerte ist schon mal zu gering
      # es kann sich nicht um ein Polygon handeln
      $umring='';
    }
    else {
      if($minx AND $miny AND $scale){
        $pix_hoch=explode(',',$pathy);
        $x=round($minx+$pix_rechts[0]*$scale,2);
        $y=round($miny+$pix_hoch[0]*$scale,2);
        $umring ='POLYGON(('.$x.' '.$y;
        for ($i=1;$i<count($pix_rechts);$i++) {
          $x=round($minx+$pix_rechts[$i]*$scale,2);
          $y=round($miny+$pix_hoch[$i]*$scale,2);
          $umring.=','.$x.' '.$y;
        }
        $x=round($minx+$pix_rechts[0]*$scale,2);
        $y=round($miny+$pix_hoch[0]*$scale,2);
        $umring.=','.$x.' '.$y.'))';
      }
      else{
        $pix_hoch=explode(',',$pathy);
        $x = $pix_rechts[0];
        $y = $pix_hoch[0];
        $umring ='POLYGON(('.$x.' '.$y;
        for ($i = 1; $i < count($pix_rechts); $i++) {
          $x = $pix_rechts[$i];
          $y = $pix_hoch[$i];
          $umring.=','.$x.' '.$y;
        }
        $x = $pix_rechts[0];
        $y = $pix_hoch[0];
        $umring.=','.$x.' '.$y.'))';
      }
    }
    return $umring;
  }

  function composePolygon2Array($umring,$minx,$miny,$scale) {
    # Bildung des Polygonarrays zur SVG-Ausgabe aus Umringpolygon
    $ret = NULL;
    $umring_teil=strrpos($umring,'((')+1;
    $umring_path=substr($umring,$umring_teil,count($umring_teil)-3);
    $umring_paare=explode(',',$umring_path);
    if(count($umring_paare) > 2){
      $umring_xy=explode(' ',$umring_paare[0]);
      $pathx=round(($umring_xy[0]-$minx)/$scale,2);
      $pathy=round(($umring_xy[1]-$miny)/$scale,2);
      for ($i=1;$i<count($umring_paare)-1;$i++) {
        $umring_xy=explode(' ',$umring_paare[$i]);
        $pathx.=','.round(($umring_xy[0]-$minx)/$scale,2);
        $pathy.=','.round(($umring_xy[1]-$miny)/$scale,2);
      }
      $ret['pathx'] = $pathx;
      $ret['pathy'] = $pathy;
    }
    return $ret;
  }

  function composePoint2Array($point,$minx,$miny,$scale) {
    # Bildung der Textposition zur SVG-Ausgabe
    $point_teil=strrpos($point,'(')+1;
    $point_paar=substr($point,$point_teil,count($point_teil)-2);
    #echo '$point_paar: '.$point_paar;
    $point_xy=explode(' ',$point_paar);
    $pathx=round(($point_xy[0]-$minx)/$scale,2);
    $pathy=round(($point_xy[1]-$miny)/$scale,2);
    $ret['loc_x'] = $pathx;
    $ret['loc_y'] = $pathy;
    return $ret;
  }

  function composeArrayFromPolygonWKTString($umring) {
    $points=explode(',',$umring);
    for ($i=0;$i<count($points)-1;$i++) {
      $koord=explode(' ',$points[$i]);
      $polyarray[$i]['x']=$koord[0];
      $polyarray[$i]['y']=$koord[1];
    }
    return $polyarray;
  }

  function pixel2weltKoordPath($path, $minx, $miny, $pixsize) {
    $explosion = explode(' ', $path);
    for($i = 0; $i < count($explosion); $i++){
      if($explosion[$i] != 'M' AND $explosion[$i] != ''){
        $explosion[$i] = ($explosion[$i] * $pixsize) + $minx;
        $explosion[$i+1] = ($explosion[$i+1] * $pixsize) + $miny;
        $i++;
      }
    }
    $path = '';
    for($i = 0; $i < count($explosion); $i++){
      $path .= $explosion[$i].' ';
    }
    return $path;
  }

  function welt2pixelKoordPath($pathWelt, $minx, $miny, $pixsize) {
    # Umrechnung von Weltkoordinaten in Bildkoordinaten
    $explosion = explode(' ', $pathWelt);
    for($i = 0; $i < count($explosion); $i++){
      if($explosion[$i] != 'M' AND $explosion[$i] != ''){
        $explosion[$i] = round(($explosion[$i] - $minx) / $pixsize);
        $explosion[$i+1] = round(($explosion[$i+1] - $miny) / $pixsize);
        $i++;
      }
    }
    $path = '';
    for($i = 0; $i < count($explosion); $i++){
      $path .= $explosion[$i].' ';
    }
    return $path;
  }

  function welt2pixelKoordWKT($weltwkt, $minx, $miny, $pixsize){
    $ebene1 = explode('(((', $weltwkt);
    if(count($ebene1) == 1){    # POLYGON
      $type = 'POLYGON';
      $ebene1 = explode('((', $weltwkt);
      $pixelwkt = $ebene1[0].'((';
    }
    else{
      $ebene1[1] = str_replace(')))', '', $ebene1[1]);
      $pixelwkt = $ebene1[0].'(((';
    }
    $ebene2 = explode(')),((', $ebene1[1]);
    for($i = 0; $i < count($ebene2); $i++){
      if($i > 0)$pixelwkt .= ')),((';
      $ebene3 = explode('),(', $ebene2[$i]);
      for($j = 0; $j < count($ebene3); $j++){
        if($j > 0)$pixelwkt .= '),(';
        $coordpair = explode(',',$ebene3[$j]);
        for($k = 0; $k < count($coordpair); $k++){
          $coord = explode(' ',$coordpair[$k]);
          $x = round($coord[0] - $minx) / $pixsize;
          $y = round($coord[1] - $miny) / $pixsize;
          if($k > 0){
            $pixelwkt .= ',';
          }
          $pixelwkt .= $x.' '.$y;
        }
      }
    }
    if($type == 'POLYGON'){
      $pixelwkt .= '))';
    }
    else{
      $pixelwkt .= ')))';
    }
    return $pixelwkt;
  }

  function pixel2weltKoordWKT($pixelwkt, $minx, $miny, $pixsize){
    $ebene1 = explode('(((', $pixelwkt);
    if(count($ebene1) == 1){    # POLYGON
      $type = 'POLYGON';
      $ebene1 = explode('((', $pixelwkt);
      $weltwkt = $ebene1[0].'((';
    }
    else{     # MULTIPOYGON
      $ebene1[1] = str_replace(')))', '', $ebene1[1]);
      $weltwkt = $ebene1[0].'(((';
    }
    $ebene2 = explode(')),((', $ebene1[1]);
    for($i = 0; $i < count($ebene2); $i++){
      if($i > 0)$weltwkt .= ')),((';
      $ebene3 = explode('),(', $ebene2[$i]);
      for($j = 0; $j < count($ebene3); $j++){
        if($j > 0)$weltwkt .= '),(';
        $coordpair = explode(',',$ebene3[$j]);
        for($k = 0; $k < count($coordpair); $k++){
          $coord = explode(' ',$coordpair[$k]);
          $x = ($coord[0] * $pixsize) + $minx;
          $y = ($coord[1] * $pixsize) + $miny;
          if($k > 0){
            $weltwkt .= ',';
          }
          $weltwkt .= $x.' '.$y;
        }
      }
    }
    if($type == 'POLYGON'){
      $weltwkt .= '))';
    }
    else{
      $weltwkt .= ')))';
    }
    return $weltwkt;
  }

  function pixel2weltKoord($pathx,$pathy) {
    # Umrechnung von Bildkoordinaten mit Ursprung links unten, hochwert nach oben zählend
    # in Koordinaten des übergeordeten Koordinatensystems
    # die x-Werte der Bildkoordinaten sind als textstrings getrennt mit Kommas in pathPixX
    # dito für y-Werte der Bildkoordinaten
    # Konvertieren des Textes mit Koordinatenwerten in ein Array
    $listePixX=explode(',',$pathx);
    $listePixY=explode(',',$pathy);
    # Umrechnung von Pixelkoordinaten in Weltkoordinaten und Zuweisen in einer Liste
    # Verwendet werden dabei die aktuellen Einstellungen der GUI für minx, miny und pixsize
    for ($i=0;$i<count($listePixX);$i++) {
      $listeWelt[$i]=new point($listePixX[$i],$listePixY[$i]);
      $listeWelt[$i]->pixel2welt($this->map->extent->minx,$this->map->extent->miny,$this->user->rolle->pixsize);
    }
    # Übergeben wird eine Liste mit Punktobjekten, die jeweils die x und y Werte im
    # Weltkoordinatensystem haben
    return $listeWelt;
  }

  function welt2pixelKoord($polygonWelt) {
    # Umrechnung von Weltkoordinaten in Bildkoordinaten
    # mit Ursprung links unten, hochwert nach oben zählend
    $anzPunkte=count($polygonWelt);
    # Umrechnen der Punkte und zuweisen der Bildkoordinaten in ein Textstring für jeweils x und y
    # getrennt durch Komma
    $obj=$polygonWelt[0];
    $obj->welt2pixel($this->map->extent->minx,$this->map->extent->miny,$this->user->rolle->pixsize);
    $pathxyPixel['x']=$obj->x;
    $pathxyPixel['y']=$obj->y;
    for ($i=1;$i<$anzPunkte;$i++) {
      $obj=$polygonWelt[$i];
      $obj->welt2pixel($this->map->extent->minx,$this->map->extent->miny,$this->user->rolle->pixsize);
      $pathxyPixel['x'].=','.$obj->x;
      $pathxyPixel['y'].=','.$obj->y;
    }
    return $pathxyPixel;
  }

	function nachweisFormAnzeigeVorlage(){
		# Nachweisdaten aus Datenbank abfragen
    $nachweis=new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    $ret=$nachweis->getNachweise($this->formvars['id'],'','','','','','','','MergeIDs','',0,0);
    $nachweis->document=$nachweis->Dokumente[0];
    # Zuweisen der Werte des Dokumentes zum Formular
    $this->formvars['flurid']=$nachweis->document['flurid'];
    $this->formvars['stammnr']=$nachweis->document['stammnr'];
    $this->formvars['rissnummer']=$nachweis->document['rissnummer'];
    $this->formvars['art']=$nachweis->document['art'];
    $this->formvars['Blattnr']=$nachweis->document['blattnummer'];
    $this->formvars['datum']=$nachweis->document['datum'];
    $this->formvars['VermStelle']=$nachweis->document['vermstelle'];
    $this->formvars['Blattformat']=$nachweis->document['format'];
    $this->formvars['gueltigkeit']=$nachweis->document['gueltigkeit'];
    $this->formvars['Gemeinde']=$nachweis->document['Gemeinde'];
    $this->formvars['Gemarkung']=substr($this->formvars['flurid'],0,6);
    $this->formvars['Flur']=intval(substr($this->formvars['flurid'],6,9));
    $this->formvars['Bilddatei']=NACHWEISDOCPATH.$nachweis->document['link_datei'];
    $this->formvars['andere_art']=$nachweis->document['andere_art'];
    $this->formvars['id'] = '';
    $this->nachweisFormAnzeige($nachweis);
	}

  function nachweisFormAnzeige($nachweis = NULL) {
    # letzte Änderung 2006-01-23 pk
    # Anzeige des Formulars zum Eintragen neuer/Ändern vorhandener Metadaten zu einem Nachweisdokument
    # (FFR, KVZ oder GN)
    $this->menue='menue.php';

    # Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
    if ($this->formvars['oid']=='') {
      $this->titel='Dokumenteneingabe';
    }
    else {
      $this->titel='Dokumenteneingabe (neuer Ausschnitt)';
    }
    $this->main="dokumenteneingabeformular.php";
    # 2006-01-27
    # aktuellen Kartenausschnitt laden + zeichnen!
    $this->loadMap('DataBase');
    
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
  	if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($this->formvars['layer_id']){
	    $data = $this->mapDB->getData($this->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $this->formvars['columnname'] = $data_explosion[0];
	    $select = $this->mapDB->getSelectFromData($data);
	    
	    # order by rausnehmen
	  	$orderbyposition = strpos(strtolower($select), 'order by');
	  	if($orderbyposition !== false){
		  	$select = substr($select, 0, $orderbyposition);
	  	}
	    
	    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
	    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
	      $this->formvars['fromwhere'] .= ' where (1=1)';
	    }
	  }
        
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveDrawmode($this->formvars['always_draw']);
    }
    elseif($nachweis != '') {
      # Zoom zum Polygon des Dokumentes
      $this->zoomToGeom($nachweis->document['geom'],10);
      $this->user->rolle->saveSettings($this->map->extent);
      $this->user->rolle->readSettings();
      # Übernahme des Nachweisumrings aus der PostGIS-Datenbank
      $this->formvars['newpath'] = transformCoordsSVG($nachweis->document['svg_umring']);
      $this->formvars['newpathwkt'] = $nachweis->document['wkt_umring'];
      $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
    }
    
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
	
	if($this->formvars['Gemarkung'] == '')$this->formvars['Gemarkung'] = $this->Lagebezeichung['gemkgschl'];
	if($this->formvars['Flur'] == '')$this->formvars['Flur'] = $this->Lagebezeichung['flur'];
    
    # Abfragen der Gemarkungen
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$this->pgdatabase);
    if(ALKIS){$GemListe=$Gemeinde->getGemeindeListeALKIS($GemeindenStelle, 'bezeichnung');}
    else{$GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');}
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    if(ALKIS){$GemkgListe=$Gemarkung->getGemarkungListeALKIS($GemListe['ID'],'','gmk.bezeichnung');}
    else{$GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');}
        
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $this->GemkgFormObj=new FormObject("Gemarkung","select",$GemkgListe['GemkgID'],$this->formvars['Gemarkung'],$GemkgListe['Bezeichnung'],"1","","",NULL);

    # erzeugen des Formularobjektes für die VermessungsStellen
    $this->FormObjVermStelle=$this->getFormObjVermStelle($this->formvars['VermStelle']);

    # abfragen der Dokumentarten
    $nachweis = new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->dokumentarten = $nachweis->getDokumentarten();
    $this->output();
  }

  function nachweisAnzeige() {
    $this->menue='menue.php';
    $this->titel='Rechercheergebnis';
    $this->main='nachweisanzeige.php';
    $this->FormObjAntr_nr=$this->getFormObjAntr_nr($this->formvars['suchantrnr']);
    $this->output();
  }

  function nachweiseZuAuftrag() {
    # echo 'Start der Zuweisung der Dokumente zum Antrag';
    # Hinzufügen von recherchierten Nachweisen zu einem Auftrag
    $this->nachweis = new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    $ret=$this->nachweis->pruefe_Auftrag_hinzufuegen_entfernen($this->formvars['suchantrnr']);
    if ($ret!=''){ # Fehler bei der Prüfung der Eingangsparameter
      $errmsg=$ret;
    }
    else {
      # Hinzufügen der Dokumente zum Auftrag
      $ret=$this->nachweis->zum_Auftrag_hinzufuegen($this->formvars['suchantrnr'],$this->formvars['id']);
      if ($ret[0]) { # Fehler beim Hinzufügen der Dokumente zum Antrag in der Datenbank
        $errmsg=$ret[1];
      }
      else {
        $okmsg=$ret[1];
      }
    }
    # Zurück zur Anzeige des Rechercheergebnisses mit Meldung über Zuordnung der Dokumente zum Auftrag
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $this->formvars=$this->user->rolle->getNachweisParameter();
    $this->nachweis = new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    $ret=$this->nachweis->getNachweise(0,$this->formvars['suchpolygon'],$this->formvars['suchgemarkungflurid'],$this->formvars['suchstammnr'],$this->formvars['suchrissnr'],$this->formvars['suchfortf'],$this->formvars['art_einblenden'],$this->formvars['richtung'],$this->formvars['abfrageart'], $this->formvars['order'],$this->formvars['suchantrnr']);
    if ($ret!='') {
      $errmsg.=$ret;
    }
    # Anzeige der Rechercheergebnisse
    $this->nachweisAnzeige();
    if($errmsg!=''){ # Anzeig der Fehlermeldung
      showAlert($errmsg);
    }
    else { # Ohne Fehler bei der Abfrage der Dokumente Anzeige der Erfolgsmeldung
      showAlert($okmsg);
    }
  }

  function nachweiseZuAuftragEntfernen() {
    # nachweisobjekt erstellen
    $this->nachweis = new nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    # Abfrage, ob schon Löschvorgang schon bestätigt wurde
    if($this->formvars['bestaetigung']=='') {
      # Löschvorgang wurde noch nicht bestätigt
      # Aufrufen eines Formulars zur Bestätigung des Löschvorganges
      $this->formvars['nachfrage_quelle']='Antrag_entfernen';
      $this->formvars['nachfrage']='Möchten sie wirklich Dokumente von der Antragsnummer: ['.$this->formvars['suchantrnr'].'] entfernen!';
      $this->bestaetigungsformAnzeigen($this->formvars['suchantrnr']);
    }
    else {
      if ($this->formvars['bestaetigung']=='JA') {
        # Löschvorgang wurde bestätigt
        # Eingabeparameter prüfen
        $ret=$this->nachweis->pruefe_Auftrag_hinzufuegen_entfernen($this->formvars['suchantrnr']);
        if($ret!=''){ # Fehler bei der Prüfung der Eingangsparameter
          $errmsg=$ret;
        }
        else {
          # Eingabeparameter in Ordnung
          # Nachweise aus Antrag entfernen
          $ret=$this->nachweis->aus_Auftrag_entfernen($this->formvars['suchantrnr'],$this->formvars['id']);
          $errmsg=$ret[1];
        } # ende Eingabeparameter sind ok
      } # ende Löschvorgang wurde bestätigt
      else { # Löschvorgang wurde abgebrochen
        $errmsg='Löschvorgang abgebrochen.';
      }
      # Zurück zur Anzeige des Rechercheergebnisses mit Meldung über Zuordnung der Dokumente zum Auftrag
      # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
      $this->formvars=$this->user->rolle->getNachweisParameter();
      $this->nachweis = new nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
      $ret=$this->nachweis->getNachweise(0,$this->formvars['suchpolygon'],$this->formvars['suchgemarkungflurid'],$this->formvars['suchstammnr'],$this->formvars['suchrissnr'],$this->formvars['suchfortf'],$this->formvars['art_einblenden'],$this->formvars['richtung'],$this->formvars['abfrageart'], $this->formvars['order'],$this->formvars['suchantrnr']);
      $errmsg.=$ret[1];
      # Anzeige der Rechercheergebnisse
      $this->nachweisAnzeige();
      showAlert($errmsg);

    } # ende Bestätigung ist erfolgt
  } # ende function nachweiseZuAuftragEntfernen

  function nachweisDokumentAnzeigen() {
    $this->nachweis = new nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    $ret=$this->nachweis->getDocLocation($this->formvars['id']);
    if($ret[0]!='') {
      showAlert($ret[0]);
      return 0;
    }
    else {
      $dateiname=basename($ret[1]);
      $dateinamensteil=explode('.',$dateiname);
      ob_end_clean();
      header("Content-type: image/".$dateinamensteil[1]);
      header("Content-Disposition: attachment; filename=".$dateiname);
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      readfile($ret[1]);
      ob_flush();
      return 1;
    }
  }
  
	function nachweisDokumentVorschau() {
    $this->nachweis = new nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
    $ret=$this->nachweis->getDocLocation($this->formvars['id']);
    if($ret[0]!='') {
      showAlert($ret[0]);
      return 0;
    }
    else {
	    $dateiname=basename($ret[1]);
      $dateinamensteil=explode('.',$dateiname);
      if(!file_exists(IMAGEPATH.$dateinamensteil[0].'.jpg')){
      	exec(IMAGEMAGICKPATH.'convert '.$ret[1].' -resize 600x500 '.IMAGEPATH.$dateinamensteil[0].'.jpg');
      	#echo IMAGEMAGICKPATH.'convert '.$ret[1].' -resize 600x500 '.IMAGEPATH.$dateinamensteil[0].'.jpg';
      }
      echo '<img style="border: 1px solid black" src="'.TEMPPATH_REL.$dateinamensteil[0].'.jpg">';
    }
  }
  

  function nachweisLoeschen(){
    # Abfragen ob der Löschvorgang schon bestätigt wurde.
    if ($this->formvars['bestaetigung']=='') {
      # Der Löschvorgang wurde noch nicht bestätigt
      $this->suchparameterSetzen();
      $this->formvars['nachfrage']='Möchten Sie den Nachweis wirklich löschen? ';
      $this->bestaetigungsformAnzeigen();
    }
    else {
      $this->nachweis = new nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
      # Abfrage ob gelöscht werden soll oder nicht
      if ($this->formvars['bestaetigung']=='JA') {
        # Der Löschvorgang wurde bestätigt und wird jetzt ausgeführt
        $idListe=array_keys($this->formvars['id']);
        $ret=$this->nachweis->nachweiseLoeschen($idListe,1);
        if ($ret[0]) { # Fehler beim Löschen in Fehlermeldung übergeben
          $this->Fehlermeldung=$ret[1];
        }
        else {
          showAlert($ret[1]);
        }
      }
      # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
      $this->formvars=$this->user->rolle->getNachweisParameter();
      # Abfragen der Nachweise entsprechend der eingestellten Suchparameter
      $ret=$this->nachweis->getNachweise(0,$this->formvars['suchpolygon'],$this->formvars['suchgemarkungflurid'],$this->formvars['suchstammnr'],$this->formvars['suchrissnr'],$this->formvars['suchfortf'],$this->formvars['art_einblenden'],$this->formvars['richtung'],$this->formvars['abfrageart'], $this->formvars['order'],$this->formvars['suchantrnr']);
      if ($ret!='') {
        $this->Fehlermeldung.=$ret;
      }
      # Anzeige der Rechercheergebnisse
      $this->nachweisAnzeige();
    }
  }

  function bestaetigungsformAnzeigen(){
    $this->menue='menue.php';
    $this->titel='Bestätigung';
    $this->main='bestaetigungsformular.php';
    $this->output();
  }

# Die function bestaetigung($nachfrage_quelle,$entscheidung)
# wurde am 2006-01-30 gelöscht weil nicht mehr benutzt

  function rechercheFormAnzeigen() {
    # 2006-01-23 pk
    $this->menue='menue.php';
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $nachweisSuchParameter=$this->user->rolle->getNachweisParameter();
    $this->formvars=array_merge($this->formvars,$nachweisSuchParameter);
    # erzeugen des Formularobjektes für Antragsnr
    $this->FormObjAntr_nr=$this->getFormObjAntr_nr($this->formvars['suchantrnr']);    
    # Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
    if ($this->formvars['oid']=='') {
      $this->titel='Dokumentenrecherche';
    }
    else {
      $this->titel='Dokumentenrecherche ändern';
    }
    $this->main="dokumentenabfrageformular.php";
    
	# Gemeindedaten laden
    #$GemObj=new gemeinde(0,$this->database);
    #$Gemeindeliste=$GemObj->getGemeindeListe(Array(), "g.Gemeindename");
    # Formularobjekt für Gemeinde bilden
    #$this->GemFormObj=new FormObject("gemeinde_id","select",$Gemeindeliste["ID"],$this->formvars['gemeinde_id'],$Gemeindeliste["Name"],1,0,0,NULL);
			
	# Abfragen der Gemarkungen
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$this->pgdatabase);
    if(ALKIS){$GemListe=$Gemeinde->getGemeindeListeALKIS($GemeindenStelle, 'bezeichnung');}
    else{$GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');}
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    if(ALKIS){$GemkgListe=$Gemarkung->getGemarkungListeALKIS($GemListe['ID'],'','gmk.bezeichnung');}
    else{$GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');}
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $this->GemkgFormObj=new FormObject("suchgemarkung","select",$GemkgListe['GemkgID'],$this->formvars['suchgemarkung'],$GemkgListe['Bezeichnung'],"1","","",NULL);
	$this->GemkgFormObj->insertOption('',0,'--Auswahl--',0);
			
    # erzeugen des Formularobjektes für die VermessungsStellen
    $this->FormObjVermStelle=$this->getFormObjVermStelle($this->formvars['VermStelle']);
    $this->FormObjVermStelle->insertOption('', NULL, '--- Auswahl ---', 0);    
    # aktuellen Kartenausschnitt laden + zeichnen!
    $this->loadMap('DataBase');
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveDrawmode($this->formvars['always_draw']);
    }
	
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id);
    # Spaltenname und from-where abfragen
  	if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($this->formvars['layer_id']){
	    $data = $this->mapDB->getData($this->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $this->formvars['columnname'] = $data_explosion[0];
	    $select = $this->mapDB->getSelectFromData($data);
	    
	    # order by rausnehmen
	  	$orderbyposition = strpos(strtolower($select), 'order by');
	  	if($orderbyposition !== false){
		  	$select = substr($select, 0, $orderbyposition);
	  	}
	    
	    $this->formvars['fromwhere'] = 'from ('.$select.') as foo where 1=1';
	    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
	      $this->formvars['fromwhere'] .= ' where (1=1)';
	    }
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
			
    $this->output();

//    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
//    $this->formvars=$this->user->rolle->getNachweisParameter();
//    # erzeugen des Formularobjektes für Antragsnr
//    $this->FormObjAntr_nr=$this->getFormObjAntr_nr($this->formvars['suchantrnr']);
//    $this->loadMap('DataBase');
//    $this->drawMap();
//    $this->output();
//    $this->saveMap('');
  }

  function vermessungsAntragEingabeForm(){
    $this->menue='menue.php';
    $this->main='antragsnr_eingabe_form.php';
    $this->FormObjVermStelle=$this->getFormObjVermStelle($this->formvars['VermStelle']);
    $this->FormObjVermArt=$this->getFormObjVermArt($this->formvars['verm_art']);
    $this->output();
  }

  function getFormObjGemGemkgFlur($Gemeinde,$Gemarkung,$Flur) {
    $GemObj = new gemeinde(0,$this->database);
    $back=$GemObj->getGemeindeListe(0, 'GemeindeName');
    if ($Gemeinde=='') {
      $Gemeinde=$back['ID'][0];
    }
    $GemFormObj=new selectFormObject('Gemeinde','select',$back['ID'],array($Gemeinde),$back['Name'],1,0,0,NULL);
    $GemkgObj = new gemarkung(0,$this->database);
    $back=$GemkgObj->getGemarkungListe(array($Gemeinde),0,'gmk.GemkgName');
    $GemkgFormObj=new selectFormObject('Gemarkung','select',$back['GemkgID'],array($Gemarkung),$back['Name'],1,0,0,NULL);
    if (in_array ($Gemarkung, $back['GemkgID'])==FALSE) {
      $Gemarkung=$back['GemkgID'][0];
    }
    $FlurObj = new flur(0,0,0);
    $back=$FlurObj->getFlurListe($Gemarkung,0,'FlurNr');
    $FlurFormObj=new selectFormObject('Flur','select',$back['FlurID'],array($Flur),$back['Name'],1,0,0,NULL);
    if (count($back['FlurID'])==0) {
      $this->Fehlermeldung='<font color="#ff0000">Keine Fluren zur Gemarkung gefunden!</font>';
    }
    else {
      if ($Flur=='' OR in_array ($Flur, $back['FlurID'])==FALSE) {
        $Flur=$back['FlurID'][0];
      }
    }
    # Zuweisen der Formularobjekte zur Rückgabevariable
    $ret['Gemeinde']=$GemFormObj;
    $ret['Gemarkung']=$GemkgFormObj;
    $ret['Flur']=$FlurFormObj;
    return $ret;
  }

  function getFormObjVermStelle($VermStelle) {
    $VermStObj = new Vermessungsstelle($this->pgdatabase);
    $back=$VermStObj->getVermStelleListe();
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjVermStelle=new FormObject('VermStelle','select',$back[1]['id'],array($VermStelle),$back[1]['name'],1,0,0,200);
    }
    else {
      $FormObjVermStelle=new FormObject('VermStelle','text',array($back[0]),'','',25,255,0,NULL);
    }
    return $FormObjVermStelle;
  }

  function getFormObjVermArt($verm_art) {
    $VermArtObj = new Vermessungsart($this->pgdatabase);
    $back=$VermArtObj->getVermArtListe();
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjVermArt=new FormObject('verm_art','select',$back[1]['id'],array($verm_art),$back[1]['art'],1,0,0,NULL);
    }
    else {
      $FormObjVermArt=new FormObject('verm_art','text',array($back[0]),'','',25,255,0,NULL);
    }
    return $FormObjVermArt;
  }

  function getFormObjAntr_nr($antr_nr) {
    $Antrag = new Antrag($antr_nr,$this->pgdatabase);
    $back=$Antrag->getAntragsnr_Liste();
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjAntr_nr=new FormObject('suchantrnr','select',$back[1]['antr_nr'],array($antr_nr),$back[1]['antr_nr'],1,0,0,NULL);
    }
    else {
      $FormObjAntr_nr=new FormObject('suchantrnr','text',array($back[0]),'','',25,255,0,NULL);
    }
    return $FormObjAntr_nr;
  }

  function getFunktionen() {
    $this->Stelle->getFunktionen();
  }

  function setSpatialFilter($layername) {
    echo '<br>Starte mit dem Setzen der Filter für die genutzen Layer...';
    # Abfragen der Zuordnungen zwischen Stellen und Layern, die eine räumliche
    # Einschränkung haben sollen (used_layer).
    echo '<br>Abfrage der zu filternden Layer für Layer: '.$layername;
    $ret=$this->database->getFilteredUsedLayer($layername);
    if ($ret[0]!='') {
      echo '<br>Fehler in kvwmap.php setSpatialFilter('.$layername.')<br>'.$ret[1];
      echo 'Beim Abfragen der used_layer, die gefiltert werden sollen.';
    }
    else {
      $query=$ret[1];
      while ($rs=mysql_fetch_array($query)) {
        # Abfragen der Filterpolygone zu used_layer
        #$used_layer_id=$rs['used_layer_id'];
        $layer_id=$rs['Layer_ID'];
        $data=$rs['data'];
        $stelle_id=$rs['Stelle_ID'];
        echo '<br>Abfrge der Polygone zum räumlichen Filtern für used_layer:'.$layer_id.' stelle:'.$stelle_id;
        $ret=$this->database->getFilterPolygons($layer_id,$stelle_id);
        if ($ret[0]!='') {
          echo '<br>Fehler in kvwmap.php setSpatialFilter('.$layername.')<br>'.$ret[1];
          echo 'Beim Abfragen der Filterpolygone';
        }
        else {
          $query1=$ret[1];
          $filteridliste=array();
          while ($rs1=mysql_fetch_array($query1)) {
            # Verschneidung der Polygone, die als Filter dienen sollen
            # mit den Features des layers und listen der Feature_id´s die zugelassen sind
            $filterdatei=$rs1['datei'];
            $filterattribut=$rs1['feldname'];
            $filterpolygon=$rs1['polygonname'];
            echo '<br>Verschneidung des Layers '.$layername.' mit Filterpolygon: '.$filterpolygon.' aus Datei: '.$filterdatei;
            # Anhängen der Liste der jeweils gefundenen IDs, die zugelassen sind
            $filteridliste=array_merge($filteridliste,$this->getFeatureIDbyPolygon($data,$filterdatei,$filterattribut,$filterpolygon));
          }
          # Komprimierung des Filterstrings (Expression)
          # 1. Eliminieren von mehrfach vorkommenden Eintragen
          $filteridliste=array_unique($filteridliste);
          # 2. Lauflängenkodierung
          $filterstring=runLenComp($filteridliste);
          # Eintragen des Filters (Expression für den used_layer
          $ret=$this->database->setFilter($layer_id,$stelle_id,$filterstring);
          if ($ret[0]!='') {
            echo '<br>Fehler in kvwmap.php setSpatialFilter('.$layername.')<br>'.$ret[1];
            echo 'Beim Eintragen des neuen Filters für used_layer';
          }
          else {
            $msg.='<br>Filter:<br>'.$filterstring.'<br>für layer: '.$layername;
            $msg.=', stelle:'.$stelle_id.' gesetzt.';
          }
        }
      }
    }
    echo '<br>'.$msg.'...fertig';
    return $msg;
  }

  function getFeatureIDbyPolygon($data,$filterdatei,$filterattribut,$filtervalue) {
    # Mapobjekt erzeugen
    $map=ms_newMapObj('');
    $dbMapObj=new db_mapObj(0,0);
    $maxextent=$dbMapObj->getMaxMapExtent();
    $map->set('width',500);
    $map->set('height',500);
    $map->setextent($maxextent['minxmax'],$maxextent['minymax'],$maxextent['maxxmax'],$maxextent['maxymax']);
    # layerobjekt erzeugen
    $filterlayer=ms_newLayerObj($map);
    $filterlayer->set('data',SHAPEPATH.$filterdatei);
    $filterlayer->set('status',MS_ON);
    $filterlayer->set('template', ' ');
    $filtershape=$dbMapObj->getShapeByAttribute($filterlayer,$filterattribut,$filtervalue);
    $layer=ms_newLayerObj($map);
    $layer->set('data',SHAPEPATH.$data);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->queryByShape($filtershape);
    $layer->open();
    $anzResult=$layer->getNumResults();
    for ($j=0;$j<$anzResult;$j++) {
      $result=$layer->getResult($j);
      $shapeindex=$result->shapeindex;
      if(MAPSERVERVERSION > 500){
        $shape=$layer->getFeature($shapeindex,-1);
      }
      else{
        $shape=$layer->getShape(-1,$shapeindex);
      }
      $idliste[]=$shape->values["ID"];
    }
    return $idliste;
  }

  function ALK_Fortfuehrung() {
    $this->titel='ALK Fortführung';
    $this->ALK=new ALK();
    $this->ALK->database=$this->database;
    # Aktualisieren der ALK-Daten
    # Flurstuecksdaten aktualisieren
    $anzflurstuecke=$this->ALK->updateFlurstuecke();
    $this->alk_protokoll_einlesen.=$this->setSpatialFilter('Flurstuecke');
    # Gebäudedaten aktualisieren
    $anzgebaeude=$this->ALK->updateGebaeude();
    $this->alk_protokoll_einlesen.=$this->setSpatialFilter('Gebaeude');
    # Nutzungsarten aktualisieren
    $anznutzungen=$this->ALK->updateNutzungen();
    $this->alk_protokoll_einlesen.=$this->setSpatialFilter('Nutzung');
    # Ausgestaltungen aktualisieren
    $anzausgestaltungen=$this->ALK->updateAusgestaltungen();
    $this->alk_protokoll_einlesen.=$this->setSpatialFilter('Ausgestaltung');
    # Buchen des Aktualisierungsvorganges
    $this->ALK->setUpdateMessage($anzflurstuecke,$anzgebaeude,$anznutzungen,$anzausgestaltungen);
    # Anzeigen der Ergebnisse der Fortführung
    $this->main='okalkfortfuehrung.php';
  }

  function Adm_Fortfuehrung() {
    $this->titel='Aktualisierung administrativer Grenzen';
    $kreis=new kreis(0,$this->database);
  #  $this->protokoll=$kreis->updateKreise();

    $gemeinde=new gemeinde(0,$this->database);
    $gemeinde->database=$this->database;
    $this->protokoll.=$gemeinde->updateGemeinden();

    $gemarkung=new gemarkung(0,$this->database);
    $gemarkung->database=$this->database;
    $this->protokoll.='<br>'.$gemarkung->updateGemarkungen();

    $flur=new flur(0,0,0);
    $flur->database=$this->database;
    $this->protokoll.='<br>'.$flur->updateFluren();

    $this->main='okadmfortfuehrung.php';

    $Gemeinde=new Gemeinde(0);
    $dbffile=$Gemeinde->getDataSourceName();
    $def=$Gemeinde->getTableDef();
    $dbfinid=dbase_open (SHAPEPATH.'temp/'.$dbffile.'.dbf',0);
    $dbfoutid=dbase_create(SHAPEPATH.'temp/'.$dbffile.'_neu.dbf',$def);
    if ($dbfinid==0 OR $dbfoutid==0) {
      echo "<b>Fehler beim öffnen der dbf-Tabelle für die Gemeinden!</b>";
      exit;
    }
    $numfields=dbase_numfields($dbfinid);
    echo "<br>Beginne mit schreiben der Tabelle ".$dbffile."_neu.dbf...";
    for ($i=1;$i<=dbase_numrecords($dbfinid);$i++) {
      $dbfrs=dbase_get_record ($dbfinid,$i);
      echo "<br>";
      for ($j=0;$j<$numfields;$j++) {
        $dbfrs[$j]=trim(ANSII2DOS($dbfrs[$j]));
        echo $dbfrs[$j].", ";
      }
      array_pop($dbfrs);
      $dbfrs[$numfields]=$i;
      echo $dbfrs[$numfields];
      if (!dbase_add_record($dbfoutid,$dbfrs)) {
        echo "<br><b>Fehler beim umschreiben der dbf-Tabelle in Zeile ".$i."!</b>";
      }
    }
    echo "...fertig<br>".$i." Zeilen in neue dbf-Tabelle geschrieben";
    dbase_close ($dbfinid);
    dbase_close($dbfoutid);

    # Gemarkungen
    $Gemarkung=new Gemarkung(0,$this->database);
    $dbffile=$Gemarkung->getDataSourceName();
    $def=$Gemarkung->getTableDef();
    $dbfinid=dbase_open (SHAPEPATH.'temp/'.$dbffile.'.dbf',0);
    $dbfoutid=dbase_create(SHAPEPATH.'temp/'.$dbffile.'_neu.dbf',$def);
    if ($dbfinid==0 OR $dbfoutid==0) {
      echo "<b>Fehler beim öffnen der dbf-Tabelle für die Gemarkungen!</b>";
      exit;
    }
    $numfields=dbase_numfields($dbfinid);
    echo "<br>Beginne mit schreiben der Tabelle ".$dbffile."_neu.dbf...";
    for ($i=1;$i<=dbase_numrecords($dbfinid);$i++) {
      $dbfrs=dbase_get_record ($dbfinid,$i);
      echo "<br>";
      for ($j=0;$j<$numfields;$j++) {
        $dbfrs[$j]=trim(ANSII2DOS($dbfrs[$j]));
        echo $dbfrs[$j].", ";
      }
      array_pop($dbfrs);
      $dbfrs[$numfields]=$i;
      echo $dbfrs[$numfields];
      if (!dbase_add_record($dbfoutid,$dbfrs)) {
        echo "<br><b>Fehler beim umschreiben der dbf-Tabelle in Zeile ".$i."!</b>";
      }
    }
    echo "...fertig<br>".$i." Zeilen in neue dbf-Tabelle geschrieben";
    dbase_close ($dbfinid);
    dbase_close($dbfoutid);
  }

  function ALB_Anzeigen($FlurstKennz,$formnummer,$Grundbuchbezirk,$Grundbuchblatt) {
    if($FlurstKennz == NULL AND $formnummer < 26){
      $grundbuch=new grundbuch($Grundbuchbezirk,$Grundbuchblatt,$this->pgdatabase);
      # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
      $ret=$grundbuch->getBuchungen('','','',1);
      $buchungen=$ret[1];
      for ($b=0;$b < count($buchungen);$b++) {
        $FlurstKennz[] = $buchungen[$b]['flurstkennz'];
      }
    }

    # Abfrage der Berechtigung zum Anzeigen der FlurstKennz
    $ret=$this->Stelle->getFlurstueckeAllowed($FlurstKennz,$this->pgdatabase);

    if ($ret[0]) {
      $this->Fehlermeldung=$ret[1];
      $this->titel='Flurstücksanzeige';
      $this->main='flurstuecksanzeige.php';
    }
    else {
      $FlurstKennz=$ret[1];
      $this->getFunktionen();
      # Prüfen ob stelle Formular 30 sehen darf
      if ($formnummer==30) {
        if(!$this->Stelle->funktionen['ALB-Auszug 30']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 35 sehen darf
      if ($formnummer==35) {
        if(!$this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 40 sehen darf
      if ($formnummer==40) {
        if(!$this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 20 sehen darf
      if ($formnummer==20) {
        if(!$this->Stelle->funktionen['ALB-Auszug 20']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 25 sehen darf
      if ($formnummer==25) {
        if(!$this->Stelle->funktionen['ALB-Auszug 25']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle ohne wz ausgeben darf
      if ($this->formvars['wz']==0) {
        if(!$this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) {
          showAlert('Die Anzeige ohne Wasserzeichen ist für diese Stelle nicht erlaubt.');
          # Wenn nicht erlaubt wird wz auf 1 gesetzt.
          $this->formvars['wz']=1;
        }
      }
      # Ausgabe der Flurstücksdaten im PDF Format
      include (PDFCLASSPATH."class.ezpdf.php");
      $pdf=new Cezpdf();
      $ALB=new ALB($this->pgdatabase);

      if($this->formvars['wz']){
        if($this->Stelle->wasserzeichen){
          $wasserzeichen = WAPPENPATH.$this->Stelle->wasserzeichen;
        }
        else{
          $wasserzeichen = WASSERZEICHEN;
        }
      }

      if($formnummer < 26){
        $log_number = array($Grundbuchbezirk.'-'.$Grundbuchblatt);
        $currenttime=date('Y-m-d H:i:s',time());
        $pdf=$ALB->ALBAuszug_Bestand($Grundbuchbezirk,$Grundbuchblatt,$formnummer,$wasserzeichen);
        $this->user->rolle->setConsumeALB($currenttime,$formnummer,$log_number,$this->formvars['wz'],$pdf->pagecount);
      }
      else{
        $currenttime=date('Y-m-d H:i:s',time());
        $pdf=$ALB->ALBAuszug_Flurstueck($FlurstKennz,$formnummer,$wasserzeichen);
        $this->user->rolle->setConsumeALB($currenttime,$formnummer,$FlurstKennz,$this->formvars['wz'],$pdf->pagecount);
      }
      $this->pdf=$pdf;

      $dateipfad=IMAGEPATH;
      $currenttime = date('Y-m-d_H-i-s',time());
      $name = umlaute_umwandeln($this->user->Name);
      $dateiname = $name.'-'.$currenttime.'.pdf';
      $this->outputfile = $dateiname;
      $fp=fopen($dateipfad.$dateiname,'wb');
      fwrite($fp,$this->pdf->ezOutput());
      fclose($fp);

      $this->mime_type='pdf';
    }
    $this->output();
  }

  function tmp_Adr_Tabelle_Aktualisieren() {
    echo '<br>Beginne mit der Aktualisierung der Adresstabelle für Abfragen...';
    if ($this->formvars['databasetype']=='mysql') {
      $adresse=new adresse('','','',$this->database);
    }
    if ($this->formvars['databasetype']=='postgresql') {
      $adresse=new adresse('','','',$this->pgdatabase);
    }
    #$adresse->setDBConn($GUI->dbConn);
    $this->Fehlermeldung.=$adresse->updateAdressTable();
    echo 'fertig.';
  }

  function ALB_Fortfuehren() {
    $this->ALB->database->setFortfuehrung(1);
    # logSQL ist ersetzt durch die Formularvariable logALBSQL, default im Formular=1
    # Wenn die SQL-Statements in einer Datei ausgegeben werden sollen, öffnen der Datei
    # übergeben an das Datenbankobjekt und unterdrücken der Ausgabe in die debug-Datei
    if ($this->formvars['logALBSQL']) {
      $albsqllogfilename=LOGPATH.'WLDGE_update_Dump_'.$this->ALB->database->type.'_'.date('YmdHis',time()).'.sql';
      $ALBLogFile=new LogFile($albsqllogfilename,'text','',$this->ALB->database->commentsign.' Fortführung des ALB am '.date('Y-m-d H:i:s',time()));
      $this->ALB->database->setLogLevel(1,$ALBLogFile);
    }
    echo 'Historische ALB Daten werden';
    if ($this->formvars['historische_loeschen']==1 OR ($this->formvars['historische_loeschen']=='' AND WLDGE_HISTORISCHE_LOESCHEN_DEFAULT)) {
      $this->ALB->historische_loeschen=1;
    }
    else {
      echo ' nicht';
      $this->ALB->historische_loeschen=0;
    }
    echo ' gelöscht.';
    $this->Fehlermeldung=$this->ALB->Fortfuehren();
    if ($this->Fehlermeldung=='') {
      # Eintragen des Datums der Grundausstattung und des neuen Fortführungszeitraumes
      $ret=$this->ALB->database->insertAbgabeZeitraum($this->ALB->dategrundausstattung,$this->ALB->zeitraumvon,$this->ALB->zeitraumbis);
      if ($ret[0]) {
        # Abbruch der Datenbanktransaktion herstellen des alten Zustandes
        # Hier wird nur die bisher erfolgte Fortführung in den Stammtabellen zurückgestellt auf den vorherigen Stand
        # Die in die temporäre Tabelle eingelesenen Daten bleiben bestehen.
        $this->ALB->database->rollbacktransaction();
        $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
        $errmsg.='<br>beim Einfügen des Zeitraumes der Fortführung in function insertAbgabeZeitraum kataster.php line: '.__LINE__;
        $errmsg.='<br>'.$ret[1];
        $this->Fehlermeldung=$errmsg;
        $this->WLDGE_Auswaehlen();
      }
      else {
        # Durchführen und anschließend beenden der Transaktion
        $this->ALB->database->committransaction();
        echo "<br>Datum Grundausstattung: ".$this->ALB->dategrundausstattung." Fortführung von:".$this->ALB->zeitraumvon." bis:".$this->ALB->zeitraumbis. " eingetragen.";
        $this->titel='ALB Fortführung';
        # ALB Datenbestand erfolgreich aktualisiert
        # Anzeige des Protokolls zum Einlesen
        $this->main='okalbfortfuehrung.php';
      }
    }
    else {
      $this->WLDGE_Auswaehlen();
    }
    # reorganisieren des Datenbankspeicherns
    $this->database->vacuum();
  }

  function ALB_Grundausstattung() {
    $this->ALB->database->setFortfuehrung(0);
    # logSQL ist ersetzt durch die Formularvariable logALBSQL, default im Formular=1
    # Wenn die SQL-Statements in einer Datei ausgegeben werden sollen, öffnen der Datei
    # übergeben an das Datenbankobjekt und unterdrücken der Ausgabe in die debug-Datei
    if ($this->formvars['logALBSQL']) {
      $albsqllogfilename=LOGPATH.'WLDGE_Dump_'.$this->ALB->database->type.'_'.date('YmdHis',time()).'.sql';
      $ALBLogFile=new LogFile($albsqllogfilename,'text','',$this->ALB->database->commentsign.' WLDGE Daten vom '.date('Y-m-d H:i:s',time()));
      $this->ALB->database->setLogLevel(1,$ALBLogFile);
    }
    $this->Fehlermeldung=$this->ALB->GrundausstattungAnlegen();
    if ($this->Fehlermeldung=='') {
      echo '<br>Grundausstattung erfolgreich angelegt!';
      # Eintragen des Datums der Grundausstattung
      # Fortführungszeitraum ist Datum der Grundausstattung
      $ret=$this->ALB->database->insertAbgabeZeitraum($this->ALB->zeitraumvon,$this->ALB->zeitraumvon,$this->ALB->zeitraumbis);
      if ($ret[0]) {
        # Fehler beim Eintragen des Datums der Grundausstattung
        $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
        $errmsg.='<br>beim Einfügen des Zeitraumes der Fortführung in function insertAbgabeZeitraum kataster.php line: '.__LINE__;
        $errmsg.='<br>'.$ret[1];
        echo $errmsg;
        $this->WLDGE_Auswaehlen();
      }
      else {
        echo "<br>Datum Grundausstattung: ".$this->ALB->zeitraumvon." eingetragen.";
        # ALB Datenbestand erfolgreich neu angelegt
        $this->titel='Anlegen ALB-Grundausstattung';
        # Anzeige des Protokolls zum Einlesen
        $this->main='okalbgrundausstattung.php';
      }
    }
    else {
      $this->WLDGE_Auswaehlen();
    }
  }

  function ALB_Aenderung() {
  	$_files = $_FILES;
    # Funktion zur Änderung der ALB Information in den Datenbanken von kvwmap
    # Es wird unterschieden in die Art der Datenbank, wo die Daten rein sollen
    # und in die Art der Fortführung (Grundausstattung oder Fortführung)
    # Test ob die wldge_datei lokal auf dem Server schon liegt, oder hochgeladen wurde
    if ($this->formvars['WLDGE_lokal']>0) {
      # Die WLDGE-Datei ist schon auf dem Server verfügbar
      $WLDGE_Datei['tmp_name']=$this->formvars['WLDGE_Datei_lokal'];
    }
    else {
      # Die Datei wird mit dem Formular über die Methode Post übermittelt
      $WLDGE_Datei=$_files['WLDGE_Datei'];
    }

    # Datei steht zum Einlesen bereit, ALB Daten können geändert werden
    # Wenn ALB in MySQL-Datenbank angelegt werden soll, wird $this->database übergeben.
    # Wenn in PostgreSQL dann $this->pgdatabase
    if ($this->formvars['databasetype']=='mysql') {
      $this->ALB=new ALB($this->database);
    }
    if ($this->formvars['databasetype']=='postgresql') {
      $this->ALB=new ALB($this->pgdatabase);
    }

    # vacuum in Datenbank bei Stapelverarbeitung unterdrücken
    # wird an Ende der Funktion ALB_Aenderung_Stapel() gesondert ausgeführt.
    if ($this->vacuumOff) {
      $this->ALB->database->vacuumOff=1;
    }

    # Blockieren von Transaktionen. Zur Steigerung der Geschwindigkeit von großen Datenbeständen anwendbar
    if ($this->formvars['blocktransaction']) {
      $this->ALB->database->blocktransaction=1;
    }

    $this->ALB->WLDGE_Datei=$WLDGE_Datei;

    # Prüfen ob Dateiname schon übermittelt wurde
    if ($WLDGE_Datei['tmp_name']=='') {
      # Nein Dateiname ist nicht bekannt oder wurde nicht angegeben
      # Auswählen der WLDGE Datei für die Fortführung vom lokalen Rechner
      $this->Fehlermeldung='Bitte WLDGE-Datei zum Einlesen angeben.';
    }
    else {
      # Prüfen ob die Datei existiert und eine Datei ist
      if (!is_file($WLDGE_Datei['tmp_name'])) {
        $this->Fehlermeldung='Die Datei "'.$WLDGE_Datei['tmp_name'].'" ist keine Datei.';
      }
    }
    # Wenn Fehlermeldungen aufgetreten sind
    if ($this->Fehlermeldung!='') {
      $this->WLDGE_Auswaehlen();
    }
    else {
      #2006-12-12 pk
      # Die Überprüfung der Headerzeilen für die Zeit und ob richtige Abgabeart kann im
      # Formular unterdrückt werden wenn dontCheckHeader=1 gesetzt wurde
      # Das leeren der ALB-Tabellen kann im Formular unterdrückt werden
      # ist notwendig, wenn der Grunddatenbestand in mehreren Teilen eingelesen werden soll.
      # Übergabe des Parameter an da Objekt ALB
      if ($this->formvars['dontCheckHeader']=='1') {
        $this->ALB->checkHeader=false;
      }
      else {
        $this->ALB->checkHeader=true;
      }
      $this->ALB->truncateTables=$this->formvars['truncateTables'];
      ob_end_flush();
      # Unterscheidung, ob es sich um eine Grundausstattung oder Fortführungsdatei handelt
      if ($this->formvars['ist_Fortfuehrung']) {
        $this->ALB_Fortfuehren();
      }
      else {
        $this->ALB_Grundausstattung();
      }
      if ($this->ALB->WLDGE_Datei_fehlerhaft) {
        echo "<br>".$this->Fehlermeldung;
        echo "<br>Abbruch des Einlesevorganges wegen fehlerhafter WLDGE-Datei.";
      }
      else {
        # Aktualisieren der temporären Adressentabelle für das Suchen nach Adressen
        $this->tmp_Adr_Tabelle_Aktualisieren();
      }
      if ($this->formvars['logALBSQL']) {
        $this->ALB->database->logfile->close();
        if ($this->ALB->WLDGE_Datei_fehlerhaft) {
          $this->ALB->database->logfile->delete();
        }
        $this->ALB->database->setLogLevel(-1,0);
      }
    }
  } # end of funktion ALB_Aenderung

  function ALB_Aenderung_Stapel() {
    # Abfragen welche Dateien sich im Stapelverzeichnis befinden
    $stapelpfad=$this->formvars['WLDGE_Pfad_lokal'];
    $wldgefiles = searchdir($stapelpfad, false);
    $wldgefiles=array_values($wldgefiles);
    if (count($wldgefiles)==0) {
      $this->Fehlermeldung='<br>Keine WLDGE-Dateien im Verzeichnis '.$stapelpfad.' gefunden';
      $this->WLDGE_Auswaehlen();
    }
    else {
      # Vacuum in Datenbank unterdrücken, bis die letzte Datei eingelesen wurde
      $this->vacuumOff=true;
      # Schleif zur Abarbeitung der Dateien
      for ($i=0;$i<count($wldgefiles);$i++) {
        # Name zuweisen
        echo '<br>'.$wldgefiles[$i];
        $this->formvars['WLDGE_Datei_lokal']=$wldgefiles[$i];
        $this->ALB_Aenderung();
        # Protokoll zwischenspeichern
        $protokoll.='<hr>'.$wldgefilename[$i];
        $protokoll.=$this->ALB->Protokoll_Einlesen;
        $protokoll.='<p>'.$this->ALB->Protokoll_Aktualisieren;
        if ($this->Fehlermeldung!='') {
          exit;
        }
        # altes ALB Object zerstören
        unset($this->ALB);
      }
      # Neues ALB Objekt anlegen zur Ausgabe der Protokolle und zur Datenbank Optimierung
      if ($this->formvars['databasetype']=='mysql') {
        $this->ALB=new ALB($this->database);
      }
      if ($this->formvars['databasetype']=='postgresql') {
        $this->ALB=new ALB($this->pgdatabase);
      }
      # Vacuum der Datenbank wieder einschalten und ausführen.
      $this->vacuumOff=false;
      $this->ALB->database->vacuum();
      # Festlegen der Inhalte für die Anzeige nach Abarbeitung des Stapels
      $this->titel='ALB Fortführung aus Stapelverarbeitung';
      # Anzeige des Protokolls zum Einlesen
      $this->main='okalbfortfuehrung.php';
      $this->ALB->Protokoll_Aktualisieren=$protokoll;
    }
  }

  function rollenwahl($Stelle_ID) {
    $this->user->Stellen=$this->user->getStellen(0);
    $this->Hinweis.='Aktuelle Stellen_ID: '.$Stelle_ID;
    $StellenFormObj=new FormObject("Stelle_ID","select",$this->user->Stellen['ID'],$Stelle_ID,$this->user->Stellen['Bezeichnung'],'Anzahl Werte',"","",NULL);
    # hinzufügen von Javascript welches dafür sorgt, dass die Angegebenen Werte abgefragt werden
    # und die genannten Formularobjekte mit diesen Werten bestückt werden
    # übergebene Werte
    # SQL für die Abfrage, es darf nur eine Zeile zurückkommen
    # Liste der Formularelementnamen, die betroffen sind in der Reihenfolge,
    # wie die Spalten in der Abfrage
    $select ="nZoomFactor,gui,CONCAT(nImageWidth,'x',nImageHeight) AS mapsize";
    $select.=",CONCAT(minx,' ',miny,',',maxx,' ',maxy) AS newExtent,epsg_code,fontsize_gle,highlighting,runningcoords";
    $from ='rolle';
    $where ="stelle_id='+this.form.Stelle_ID.value+' AND user_id=".$this->user->id;
    $StellenFormObj->addJavaScript("onchange","ahah('".URL.APPLVERSION."index.php','go=getRow&select=".urlencode($select)."&from=".$from."&where=".$where."',new Array(nZoomFactor,gui,mapsize,newExtent,epsg_code,fontsize_gle,highlighting,runningcoords));");
    #echo URL.APPLVERSION."index.php?go=getRow&select=".urlencode($select)."&from=".$from."&where=stelle_id=3 AND user_id=7";
    $StellenFormObj->outputHTML();
    $this->StellenForm=$StellenFormObj;
    $this->main='rollenwahl.php';
    # Suchen nach verfügbaren Layouts
    # aus dem Stammordner layouts (vom System angebotene)
    $this->layoutfiles = searchdir(LAYOUTPATH, false);
    for($i = 0; $i < count($this->layoutfiles); $i++){
      if(strpos($this->layoutfiles[$i], '.php') > 0){
        $this->guifiles[] = $this->layoutfiles[$i];
      }
    }
    # aus dem Customordner (vom Nutzer hinzugefügte Layouts)
    $this->customlayoutfiles = searchdir(LAYOUTPATH.'custom', true);
    for($i = 0; $i < count($this->customlayoutfiles); $i++){
      if(strpos($this->customlayoutfiles[$i], '.php') > 0){
        $this->customguifiles[] = $this->customlayoutfiles[$i];
      }
    }
    # Abfrage der verfügbaren Kartenprojektionen in PostGIS (Tabelle spatial_ref_sys)
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    # Voreinstellen des aktuellen EPSG-Codes der Rolle
    if ($this->formvars['epsg_code']=='') {
      $this->formvars['epsg_code']=$this->user->rolle->epsg_code;
    }
    # Abfragen der Farben für die Suchergebnisse
    $this->result_colors = read_colors($this->database);
  }

  function flurstSuchen() {
    # 2006-02-01 pk
    $GemID=$this->formvars['GemID'];
    $GemkgID=$this->formvars['GemkgID'];
    if ($this->formvars['FlurID']!='-1') {
      # dreistelliges auffüllen der Flurnummer mit Nullen
      $FlurID=str_pad($this->formvars['FlurID'],3,"0",STR_PAD_LEFT);
    }
    else {
      $FlurID=$this->formvars['FlurID'];
    }
    #$FlstID=$this->formvars['FlstID'];
    $FlstID=$this->formvars['selFlstID'];
    $FlstNr=$this->formvars['FlstNr'];
    #$this->searchInExtent=$this->formvars['searchInExtent'];
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    # abfragen, ob es sich um eine gültige GemarkungsID handelt
    if(ALKIS)$GemkgListe=$Gemarkung->getGemarkungListeALKIS(array($GemID),array($GemkgID),'');
    else $GemkgListe=$Gemarkung->getGemarkungListe(array($GemID),array($GemkgID),'');
    if(count($GemkgListe['GemkgID']) > 0){
      # Die Gemarkung ist ausgewählt und gültig aber Flur leer, zoom auf Gemarkung
      if($FlurID==0 OR $FlurID=='-1'){
        $this->loadMap('DataBase');
        $this->zoomToALKGemarkung($GemkgID,10);				# ALKIS TODO
        $currenttime=date('Y-m-d H:i:s',time());
        $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
        $this->drawMap();
        $this->saveMap('');
      }
      else {
        # ist Gemarkung und Flur ausgefüllt aber keine Angabe zum Flurstück, zoom auf Flur
        if(($FlstID=='' AND $FlstNr=='') OR ($FlstID=='-1')){
        	if($this->formvars['ALK_Suche'] == 1){
	          $this->loadMap('DataBase');
	          $this->zoomToALKFlur($GemID,$GemkgID,$FlurID,10);			# ALKIS TODO
	          $currenttime=date('Y-m-d H:i:s',time());
	          $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
	          $this->drawMap();
	          $this->saveMap('');
        	}
	        else{			# Anzeige der Flurstuecke der Flur
	      		$FlstNr=new flurstueck('',$this->pgdatabase);
	      		$FlstNrListe=$FlstNr->getFlstListe($GemID,$GemkgID,$FlurID,'flurstkennz', $this->formvars['historical']);
		        $FLstID=$FlstNrListe['FlstID'][0];
		        $FlstID = $FlstNrListe['FlstID'];
	          $FlurstKennz = array_values(array_unique($FlstID));
	          $this->flurstAnzeige($FlurstKennz);
	      	}
        }
        else {
          # es existiert eine Angabe zum Flurstück
          $Flurstueck=new flurstueck('',$this->pgdatabase);
          # wenn keine FlstID angegeben wurde, wird versucht die FlstID aus der FlstNr abzuleiten
          if ($FlstID=='') {
            # ableiten der FlstID aus den Angaben in FlstNr
            $FlurstKennz[0]=$Flurstueck->is_FlurstNr($GemkgID,$FlurID,$FlstNr);
            if ($FlurstKennz[0]==0) {
              # aus FlstNr konnte kein eindeutiges FlurstKennz abgeleitet werden
              # Abfrage ob der Zähler eines Flurstücks mit FlstNr übereinstimmt
              $FlurstKennz=$Flurstueck->is_FlurstZaehler($GemkgID,$FlurID,$FlstNr);
              # wenn im Ergebnis die Anzahl der gefundenen FlurstKennz 0 ist wird weiter unten Suche abgebrochen
            }
          }
          else {
            # wenn FlstID nicht leer ist, wird diese zur Suche übernommen
            $FlurstKennz = explode(', ', $FlstID);
            $FlurstKennz = array_values(array_unique($FlurstKennz));
          }
          $anzFlurst=count($FlurstKennz);
          if ($anzFlurst==0) {
            # es konnten überhaupt keine gültigen Flurstuecke aus den Angaben FlstNr gefunden werden
            # zurück zur Auswahl mit Hinweis, daß Flurstücksauswahl zu keinem Ergebnis führt
            $this->Fehlermeldung='Zu diesem Flurstück wurden keine Angaben gefunden!';
            $this->flurstwahl();
          }
          else {
            # Es wurde mindestens ein eindeutiges FlurstKennz in FlstID ausgewählt, oder ein oder mehrere über FlstNr gefunden
            # Zoom auf Flurstücke
            if($this->formvars['ALK_Suche'] == 1){
            	$this->loadMap('DataBase');
		          $this->zoomToALKFlurst($FlurstKennz,10);				# ALKIS TODO
							if($this->formvars['go_next'] != '')header('location: index.php?go='.$this->formvars['go_next']);
		          $currenttime=date('Y-m-d H:i:s',time());
		          $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
		          $this->drawMap();
		          $this->saveMap('');
            }
            else{	  # Anzeige der ALB-daten in Flurstücksanzeige
            	$this->flurstAnzeige($FlurstKennz);
            }
          }
        } # ende Suche nach Flurstück
      } # ende Suche nach Flur
    }
    else {
      $this->Fehlermeldung='Wählen Sie eine Gemarkung!';
      $this->flurstwahl();
    }
  } # ende function flurstSuchen
	
	function flurstSuchenByLatLng() {
    $flurstueck = new flurstueck('',$this->pgdatabase);
		if ($this->formvars['version'] == '1.0') {
			$result= $flurstueck->getFlurstByLatLng($this->formvars['latitude'], $this->formvars['longitude']);
			$layerset['landId'] = $result['land'];
			$layerset['kreisId'] = $result['kreis'];
			$layerset['gemeindId'] = $result['gemeinde'];
			$layerset['gemarkungId'] = $result['gemarkungsnummer'];
			$layerset['gemarkungName'] = $result['gemarkungname'];
			$layerset['flurId'] = $result['flurnummer'];			
			$layerset['flurstueckId'] = $result['flurstkennz'];
			$layerset['flurstueckNummer'] = $result['flurstuecksnummer'];
			$this->qlayerset[0]['shape'][0] = $layerset;
			$this->mime_type = 'formatter';			
		}
		else {
			$this->loadMap('DataBase');
      $this->user->rolle->newtime = $this->user->rolle->last_time_id;
      $this->drawMap();
     	$this->saveMap('');			
		}	
	} # ende function flurstSuchenByLatLng

  function flurstAnzeige($FlurstKennzListe) {
    # 2006-01-26 pk
    # Abfrage der Berechtigung zum Anzeigen der FlurstKennzListe
    $ret=$this->Stelle->getFlurstueckeAllowed($FlurstKennzListe, $this->pgdatabase);
    if ($ret[0]) {
      $this->Fehlermeldung=$ret[1];
      $anzFlurst=0;
    }
    else {
      $FlurstKennzListe=$ret[1];
      $anzFlurst=count($FlurstKennzListe);
    }
	
    $this->mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layer = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
    $privileges = $this->Stelle->get_attributes_privileges($layer[0]['Layer_ID']);
    $layer[0]['attributes'] = $this->mapDB->read_layer_attributes($layer[0]['Layer_ID'], $this->pgdatabase, $privileges['attributenames']);

    if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
      for($j = 0; $j < count($layer[0]['attributes']['name']); $j++){
        $layer[0]['attributes']['privileg'][$j] = '0';
        $layer[0]['attributes']['privileg'][$layer[0]['attributes']['name'][$j]] = '0';
      }
    }
    else{
      for($j = 0; $j < count($layer[0]['attributes']['name']); $j++){
        $layer[0]['attributes']['privileg'][$j] = $privileges[$layer[0]['attributes']['name'][$j]];
        $layer[0]['attributes']['privileg'][$layer[0]['attributes']['name'][$j]] = $privileges[$layer[0]['attributes']['name'][$j]];
      }
    }
    $this->qlayerset[] = $layer[0];
    $this->main = $layer[0]['template'];
	
	$this->user->rolle->delete_last_query();
	$this->user->rolle->save_last_query('Flurstueck_Anzeigen', $layer[0]['Layer_ID'], implode(';', $FlurstKennzListe), NULL, NULL, NULL);

    for ($i=0;$i<$anzFlurst;$i++) {
      $this->qlayerset[0]['shape'][$i]['flurstkennz'] = $FlurstKennzListe[$i];
    }
    $i = 0;
  }

  function sachdaten_speichern(){
  	$_files = $_FILES;
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $form_fields = explode('|', $this->formvars['form_field_names']);
    $success = true;
    $old_layer_id = '';
    for($i = 0; $i < count($form_fields); $i++){
      if($form_fields[$i] != ''){
        $element = explode(';', $form_fields[$i]);
        $layer_id = $element[0];
        $attributname = $element[1];
        $tablename = $element[2];
        $oid = $element[3];
        $formtype = $element[4];
        $datatype = $element[6];
        if($layer_id != $old_layer_id AND $tablename != ''){
          $layerdb = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
          $layerdb->setClientEncoding();
          #$filter = $mapdb->getFilter($layer_id, $this->Stelle->id);		# siehe unten
          $old_layer_id = $layer_id;
        }
        if(($this->formvars['go'] == 'Dokument_Loeschen' OR $this->formvars['changed_'.$oid] == 1 OR $this->formvars['embedded']) AND $attributname != 'oid' AND $tablename != ''){
          # 2008-03-26 pk
          switch($formtype) {
            case 'Dokument' : {
              # Prüfen ob ein neues Bild angegebeben wurde
              if($_files[$form_fields[$i]]['name']){
                # Dateiname erzeugen
                $name_array=explode('.',basename($_files[$form_fields[$i]]['name']));
                $datei_name=$name_array[0];
                $datei_erweiterung=array_pop($name_array);
                $doc_path = $mapdb->getDocument_Path($layer_id);
                $currenttime = date('Y-m-d_H_i_s',time());
                $nachDatei = $doc_path.$currenttime.'-'.rand(0, 1000000).'.'.$datei_erweiterung;
                $eintrag = $nachDatei."&original_name=".$_files[$form_fields[$i]]['name'];
                if($datei_name == 'delete')$eintrag = '';
                # Bild in das Datenverzeichnis kopieren
                if (move_uploaded_file($_files[$form_fields[$i]]['tmp_name'],$nachDatei) OR $datei_name == 'delete') {
                  #echo '<br>Lade '.$_files[$form_fields[$i]]['tmp_name'].' nach '.$nachDatei.' hoch';
                  # Wenn eine alte Datei existiert, die nicht so heißt wie die neue --> löschen
                  $old = $this->formvars[str_replace(';Dokument;', ';Dokument_alt;', $form_fields[$i])];
                  if ($old != '' AND $old != $eintrag) {
                  	$this->deleteDokument($old);
                  }
                  # Dateiname in der Datentabelle aktualisieren
                  $sql = "UPDATE ".$tablename." SET ".$attributname." = '".$eintrag."' WHERE oid = '".$oid."'";
                } # ende von Datei wurde erfolgreich in Datenverzeichnis kopiert
                else {
                  echo '<br>Datei: '.$_files[$form_fields[$i]]['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
                }
              } # ende vom Fall, dass ein neues Dokument hochgeladen wurde
            } break; # ende case Bild
            case 'Time' : {
              $sql = "UPDATE ".$tablename." SET ".$attributname." = '".date('Y-m-d G:i:s')."' WHERE oid = '".$oid."'";
            } break;
            case 'User' : {
              $sql = "UPDATE ".$tablename." SET ".$attributname." = '".$this->user->Vorname." ".$this->user->Name."' WHERE oid = '".$oid."'";
            } break;
            case 'UserID' : {
              $sql = "UPDATE ".$tablename." SET ".$attributname." = '".$this->user->id."' WHERE oid = '".$oid."'";
            } break;
            case 'Stelle' : {
              $sql = "UPDATE ".$tablename." SET ".$attributname." = '".$this->Stelle->Bezeichnung."' WHERE oid = '".$oid."'";
            } break;
            case 'Geometrie' : {
              # nichts machen
            } break;
            case 'Checkbox' : {
            	if($this->formvars[$form_fields[$i]] == '')$this->formvars[$form_fields[$i]] = 'f';
              $sql = "UPDATE ".$tablename." SET ".$attributname." = '".$this->formvars[$form_fields[$i]]."' WHERE oid = '".$oid."'";
            } break;
            default : {
              if($tablename AND $formtype != 'Text_not_saveable' AND $formtype != 'Auswahlfeld_not_saveable' AND $formtype != 'SubFormPK' AND $formtype != 'SubFormFK' AND $formtype != 'SubFormEmbeddedPK' AND $attributname != 'the_geom'){
              	if(in_array($datatype, array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
              		$this->formvars[$form_fields[$i]] = str_replace(' ', '', $this->formvars[$form_fields[$i]]);		# bei Zahlen das Leerzeichen (Tausendertrenner) entfernen
              	}
                if($this->formvars[$form_fields[$i]] == ''){
                  $sql = "UPDATE ".$tablename." SET ".$attributname." = NULL WHERE oid = '".$oid."'";
                }
                else{
                  $sql = "UPDATE ".$tablename." SET ".$attributname." = '".pg_escape_string(stripslashes($this->formvars[$form_fields[$i]]))."' WHERE oid = '".$oid."'";
                }
              }
            } # end of default case
          } # end of switch for type
          
          #if($filter != ''){							# erstmal wieder rausgenommen, weil der Filter sich auf Attribute beziehen kann, die zu anderen Tabellen gehören
          #  $sql .= " AND ".$filter;
          #}
          $this->debug->write("<p>file:kvwmap class:sachdaten_speichern :",4);
          $ret = $layerdb->execSQL($sql,4, 1);
          
          if ($ret[0]) {
            $success = false;
          }
        }
      }
    }
    if($success == false){
      showAlert('Änderung fehlgeschlagen');
    }
    else{
      if($this->formvars['close_window'] == ""){
        showAlert('Änderung erfolgreich');
      }
    }
    if($this->formvars['embedded'] != ''){    # wenn es ein Datensatz aus einem embedded-Formular ist, muss das entsprechende Attribut des Hauptformulars aktualisiert werden
      header('Content-type: text/html; charset=UTF-8');
      $attributenames[0] = $this->formvars['targetattribute'];
      $attributes = $mapdb->read_layer_attributes($this->formvars['targetlayer_id'], $layerdb, $attributenames);
      switch ($attributes['form_element_type'][0]){
        case 'SubFormEmbeddedPK' : {
          $this->formvars['embedded_subformPK'] = true;
          echo '^';
          $this->GenerischeSuche_Suchen();
        }break;
      }
    }
    else{
      if($this->formvars['search']){        # man kam von der Suche   -> nochmal suchen
        $this->GenerischeSuche_Suchen();
      }
      else{                                 # man kam aus einer Sachdatenabfrage    -> nochmal abfragen
        $this->sachdaten_anzeigen();
      }
    }
  }

 function sachdaten_anzeigen(){
  if($this->formvars['querypolygon'] != ''){
    $rect = $this->formvars['querypolygon'];
  }
  else{
    $rect = ms_newRectObj();
    $rect->setextent($this->formvars['rectminx'],$this->formvars['rectminy'],$this->formvars['rectmaxx'],$this->formvars['rectmaxy']);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }
  $this->loadMap('DataBase');
  $this->Sachdatenanzeige($rect);
  if($this->formvars['printversion'] != ''){
    $this->mime_type = 'printversion';
  }
  $this->output();
 }

 # 2006-07-26 pk
 function SachdatenAnzeige($rect) {
	if($this->last_query != ''){
		foreach($this->last_query['layer_ids'] as $layer_id){
			$this->formvars['qLayer'.$layer_id] = 1;
		}
	}
	$this->user->rolle->delete_last_query();
    if(is_string($rect)){
      $this->querypolygon = $rect;
    }
    $this->queryrect = $rect;
    # Abfragen der Layer, die zur Stelle gehören
    $layerset=$this->user->rolle->getLayer('');
    $anzLayer=count($layerset);
    $map=ms_newMapObj('');
    $map->set('shapepath', SHAPEPATH);
    for ($i=0;$i<$anzLayer;$i++) {
    	$sql_order = ''; 
      if ($this->formvars['qLayer'.$layerset[$i]['Layer_ID']]=='1' AND ($layerset[$i]['maxscale'] == 0 OR $layerset[$i]['maxscale'] > $this->map_scaledenom) AND ($layerset[$i]['minscale'] == 0 OR $layerset[$i]['minscale'] < $this->map_scaledenom)) {
        # Dieser Layer soll abgefragt werden
        switch ($layerset[$i]['connectiontype']) {
          case MS_SHAPEFILE : { # Shape File Layer (1)
            if ($this->formvars['searchradius'] > 0 OR $this->querypolygon != '') {
              showAlert('Sie können für die Abfrage von Shapelayern nur die einfache Sachdatenabfrage verwenden.');
            }
            else{
              $layer=ms_newLayerObj($map);
              $layer->set('data', $layerset[$i]['Data']);
              $layer->set('status',MS_ON);
              if ($layerset[$i]['template']!='') {
                $layer->set('template',$layerset[$i]['template']);
              }
              else {
                $layer->set('template',DEFAULTTEMPLATE);
              }
              $projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
    					$projTO = ms_newprojectionobj("init=epsg:".$layerset[$i]['epsg_code']);
    					$rect->project($projFROM, $projTO);
              @$layer->queryByRect($rect);
              $layer->open();
              $anzResult=$layer->getNumResults();
              for ($j=0;$j<$anzResult;$j++) {
                $result=$layer->getResult($j);
                $shapeindex=$result->shapeindex;
                if(MAPSERVERVERSION > 500){
                  $layerset[$i]['shape'][$j]=$layer->getFeature($shapeindex,-1);
                }
                else{
                  $layerset[$i]['shape'][$j]=$layer->getShape(-1,$shapeindex);
                }
              }
              $this->qlayerset[]=$layerset[$i];
            }
          } break; # ende Layer ist ein Shapefile
          case MS_OGR : { # OGR Layer (4)
            $layer=ms_newLayerObj($map);
            if (MAPSERVERVERSION < '540') {
				      $layer->set('connectiontype',$layerset[$i]['connectiontype']);
				    }
				    else {
				      $layer->setConnectionType($layerset[$i]['connectiontype']);
				    }
            $layer->set('connection', $layerset[$i]['connection']);
            $layer->set('type',$layerset[$i]['Datentyp']);
            $layer->set('status',MS_ON);
            if ($layerset[$i]['template']!='') {
              $layer->set('template',$layerset[$i]['template']);
            }
            else {
              $layer->set('template',DEFAULTTEMPLATE);
            }
            @$layer->queryByRect($rect);
            $layer->open();
            $anzResult=$layer->getNumResults();
            for ($j=0;$j<$anzResult;$j++) {
              $result=$layer->getResult($j);
              $shapeindex=$result->shapeindex;
              if(MAPSERVERVERSION > 500){
                $layerset[$i]['shape'][$j]=$layer->getFeature($shapeindex,-1);
              }
              else{
                $layerset[$i]['shape'][$j]=$layer->getShape(-1,$shapeindex);
              }
            }
            $this->qlayerset[]=$layerset[$i];
          } break;
          case MS_POSTGIS : { # PostGIS Layer (6)
            # Für die performante Suche wird immer zunächst ein Suchrechteck (searchbox) gebildet, egal ob punktuell
            # oder in einem Suchfenster gesucht wird
            # Für die Bildung der searchbox wird entweder mit dem angegebenen Suchradius tolerance in der Einheit toleranceunit
            # aus der Tabelle layers gerechnet oder mit dem im Formular eingegebenen Suchradius (searchradius)


            # Datenbankobjekt aus Layerdefinition erzeugen
            # Path laden
            # Rechte abfragen
            # Path auf Basis der Rechte einschränken
            # Attribute aus Path laden
            # Rechte den Attributen zuweisen

            $layerdb = $this->mapDB->getlayerdatabase($layerset[$i]['Layer_ID'], $this->Stelle->pgdbhost);
            $layerdb->setClientEncoding();
            $path = $layerset[$i]['pfad'];
            $privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
            $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
            $layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], $layerdb, $privileges['attributenames']);
				    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)  ---> steht weiter unten

            # order by rausnehmen
				  	$orderbyposition = strpos(strtolower($newpath), 'order by');
				  	if($orderbyposition !== false){
					  	$layerset[$i]['attributes']['orderby'] = ' '.substr($newpath, $orderbyposition);
					  	$newpath = substr($newpath, 0, $orderbyposition);
				  	}
				  	
				  	# group by rausnehmen
						$groupbyposition = strpos(strtolower($newpath), 'group by');
						if($groupbyposition !== false){
							$layerset[$i]['attributes']['groupby'] = ' '.substr($newpath, $groupbyposition);
							$newpath = substr($newpath, 0, $groupbyposition);
				  	}

            if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
              for($j = 0; $j < count($layerset[$i]['attributes']['name']); $j++){
                $layerset[$i]['attributes']['privileg'][$j] = '0';
                $layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['name'][$j]] = '0';
              }
            }
            else{
              for($j = 0; $j < count($layerset[$i]['attributes']['name']); $j++){
                $layerset[$i]['attributes']['privileg'][$j] = $privileges[$layerset[$i]['attributes']['name'][$j]];
                $layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['name'][$j]] = $privileges[$layerset[$i]['attributes']['name'][$j]];
              }
            }
            # Wenn kein Template --> generischer Layereditor: Pfad um oids der verwendeten Tabellen erweitern (erstmal testweise rausgenommen)
            #if($layerset[$i]['template'] == ''){
              $distinctpos = strpos(strtolower($newpath), 'distinct');
              if($distinctpos !== false && $distinctpos < 10){
                $pfad = substr(trim($newpath), $distinctpos+8);
                $distinct = true;
              }
              else{
                $pfad = substr(trim($newpath), 7);
              }
              $j = 0;
              foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
                if($layerset[$i]['attributes']['oids'][$j]){      # hat Tabelle oids?
                  $pfad = $layerset[$i]['attributes']['table_alias_name'][$tablename].'.oid AS '.$tablename.'_oid, '.$pfad;
                }
                $j++;
              }
              if($distinct == true){
                $pfad = 'DISTINCT '.$pfad;
              }
            #}
            #else{
            #  $pfad = substr(trim($newpath), 7);
            #}

            /*
						if(strpos(strtolower($pfad), 'as the_geom') !== false){
              $the_geom = 'query.the_geom';
            }
            else{
            */
            	if($layerset[$i]['attributes']['the_geom'] == ''){					# Geometriespalte ist nicht geladen, da auf "nicht sichtbar" gesetzt --> aus Data holen
            		$data_attributes = $this->mapDB->getDataAttributes($layerdb, $layerset[$i]['Layer_ID']);
            		$layerset[$i]['attributes']['the_geom'] = $data_attributes['the_geom'];
            	}
              /*if($layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']]){
                $the_geom = $layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$layerset[$i]['attributes']['the_geom'];
              }
              else{*/
                $the_geom = $layerset[$i]['attributes']['the_geom'];
            //  }
            //} 

            # Unterscheidung ob mit Suchradius oder ohne gesucht wird
            if ($this->formvars['searchradius']>0) {
              $layerset[$i]['toleranceunits']='meters';
              $layerset[$i]['tolerance']=$this->formvars['searchradius'];
            }
            switch ($layerset[$i]['toleranceunits']) {
              case 'pixels' : $pixsize=$this->user->rolle->pixsize; break;
              case 'meters' : $pixsize=1; break;
              default : $pixsize=$this->user->rolle->pixsize;
            }
            $rand=$layerset[$i]['tolerance']*$pixsize;

            # Aktueller EPSG in der die Abfrage ausgeführt wurde
            $client_epsg=$this->user->rolle->epsg_code;
            # EPSG-Code des Layers der Abgefragt werden soll
            $layer_epsg=$layerset[$i]['epsg_code'];
            # Bildung der Where-Klausel für die räumliche Abfrage mit der searchbox
            $searchbox_wkt ="POLYGON((";
            $searchbox_wkt.=strval($rect->minx)." ".strval($rect->miny).",";
            $searchbox_wkt.=strval($rect->maxx)." ".strval($rect->miny).",";
            $searchbox_wkt.=strval($rect->maxx)." ".strval($rect->maxy).",";
            $searchbox_wkt.=strval($rect->minx)." ".strval($rect->maxy).",";
            $searchbox_wkt.=strval($rect->minx)." ".strval($rect->miny)."))";
            
            if($this->querypolygon != ''){
              $searchbox_wkt = $this->querypolygon;
            }

            # ---------- punktuelle Suche ---------- #
            if ($rect->minx==$rect->maxx AND $rect->miny==$rect->maxy AND $this->querypolygon == '') {
            	$loosesearchbox_wkt ="POLYGON((";
	            $loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand).",";
	            $loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->miny-$rand).",";
	            $loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->maxy+$rand).",";
	            $loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->maxy+$rand).",";
	            $loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand)."))";
              # Behandlung der Suchanfrage mit Punkt, exakte Suche im Kreis
              if ($client_epsg!=$layer_epsg) {
              	$sql_where =" AND ".$the_geom." && st_transform(st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg."),".$layer_epsg.")";
                $sql_where.=" AND st_distance(".$the_geom.",st_transform(st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."),".$layer_epsg."))";
              }
              else {
              	$sql_where =" AND ".$the_geom." && st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg.")";
                $sql_where.=" AND st_distance(".$the_geom.",st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."))";
              }
              $sql_where.=" <= ".$rand;
            }
            # ---------- Suche über Polygon ---------- #
            else {		
              # Behandlung der Suchanfrage mit Rechteck, exakte Suche im Rechteck
              if ($client_epsg!=$layer_epsg) {
                $sql_where =" AND st_intersects(".$the_geom.",st_transform(st_geomfromtext('".$searchbox_wkt."',".$client_epsg."),".$layer_epsg."))";
              }
              else {
                $sql_where =" AND st_intersects(".$the_geom.",st_geomfromtext('".$searchbox_wkt."',".$client_epsg."))";
              }
            }
            # Filter zur Where-Klausel hinzufügen
            if($layerset[$i]['Filter'] != ''){
            	$layerset[$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset[$i]['Filter']);
              $sql_where .= " AND ".$layerset[$i]['Filter'];
            }
            
            if($this->formvars['CMD'] == 'touchquery'){
            	if(substr_count(strtolower($pfad), ' from ') > 1){			# mehrere froms -> das FROM der Hauptabfrage muss groß geschrieben sein
            		$fromposition = strpos($pfad, ' FROM ');
            	}
            	else{
            		$fromposition = strpos(strtolower($pfad), ' from ');
            	}
            	$new_pfad = $the_geom." ".substr($pfad, $fromposition);
            	#if($the_geom == 'query.the_geom'){
	              $sql = "SELECT * FROM (SELECT ".$new_pfad.") as query WHERE 1=1 ".$sql_where;
	            #}
	            #else{
	            #  $sql = "SELECT ".$new_pfad." ".$sql_where;
	            #}
	            $ret=$layerdb->execSQL($sql,4, 0);
	            if(!$ret[0]){
	            	while($rs=pg_fetch_array($ret[1])){
	              	$geoms[]=$rs[0];
	              }
	            }
	            $sql = '';
	            for($g = 0; $g < count($geoms); $g++){
	            	if($g > 0)$sql .= " UNION ";
	            	$sql .= "SELECT ".$pfad." AND ".$the_geom." && ('".$geoms[$g]."') AND (st_intersects(".$the_geom.", ('".$geoms[$g]."')) OR ".$the_geom." = ('".$geoms[$g]."'))";
	            }
            }
            else{
	            #if($the_geom == 'query.the_geom'){
	            	# group by wieder einbauen
								if($layerset[$i]['attributes']['groupby'] != ''){
									$pfad .= $layerset[$i]['attributes']['groupby'];
									$j = 0;
									foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
												if($layerset[$i]['attributes']['oids'][$j]){      # hat Tabelle oids?
													$pfad .= ','.$tablename.'_oid ';
												}
												$j++;
									}
		          	}
	              $sql = "SELECT * FROM (SELECT ".$pfad.") as query WHERE 1=1 ".$sql_where;
	            #}
	            /*else{
	              $sql = "SELECT ".$pfad." ".$sql_where;
	              # group by wieder einbauen
								if($layerset[$i]['attributes']['groupby'] != ''){
									$sql .= $layerset[$i]['attributes']['groupby'];
									$j = 0;
									foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
												if($layerset[$i]['attributes']['oids'][$j]){      # hat Tabelle oids?
													$sql .= ','.$tablename.'_oid ';
												}
												$j++;
									}
		          	}
	            }*/
            }
			
			# order by
			if($this->formvars['orderby'.$layerset[$i]['Layer_ID']] != ''){									# Fall 1: im GLE soll nach einem Attribut sortiert werden
			  $sql_order = ' ORDER BY '.$this->formvars['orderby'.$layerset[$i]['Layer_ID']];
			}
			elseif($layerset[$i]['attributes']['orderby'] != ''){														# Fall 2: der Layer hat im Pfad ein ORDER BY
				$sql_order = $layerset[$i]['attributes']['orderby'];
			}
			if($layerset[$i]['template'] == ''){																				# standardmäßig wird nach der oid sortiert
				$j = 0;
				foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
					if($tablename == $layerset[$i]['maintable'] AND $layerset[$i]['attributes']['oids'][$j]){      # hat die Haupttabelle oids, dann wird immer ein order by oid gemacht, sonst ist die Sortierung nicht eindeutig
						if($sql_order == '')$sql_order = ' ORDER BY '.$layerset[$i]['maintable'].'_oid ';
						else $sql_order .= ', '.$layerset[$i]['maintable'].'_oid ';
					}
					$j++;
				}
			}
						
			if($this->last_query != ''){
				$sql = $this->last_query[$layerset[$i]['Layer_ID']]['sql'];
				if($this->formvars['orderby'.$layerset[$i]['Layer_ID']] == '')$sql_order = $this->last_query[$layerset[$i]['Layer_ID']]['orderby'];
				$this->formvars['anzahl'] = $this->last_query[$layerset[$i]['Layer_ID']]['limit'];
				if($this->formvars['offset_'.$layerset[$i]['Layer_ID']] == '')$this->formvars['offset_'.$layerset[$i]['Layer_ID']] = $this->last_query[$layerset[$i]['Layer_ID']]['offset'];
			}

            # Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
            if($this->formvars['anzahl'] == ''){
              $this->formvars['anzahl'] = MAXQUERYROWS;
            }
            $sql_limit =' LIMIT '.$this->formvars['anzahl'];
            if($this->formvars['offset_'.$layerset[$i]['Layer_ID']] != ''){
              $sql_limit.=' OFFSET '.$this->formvars['offset_'.$layerset[$i]['Layer_ID']];
            }

            $layerset[$i]['sql'] = $sql;
						
			$this->user->rolle->save_last_query('Sachdaten', $layerset[$i]['Layer_ID'], $sql, $sql_order, $this->formvars['anzahl'], $this->formvars['offset_'.$layerset[$i]['Layer_ID']]);
			
            $ret=$layerdb->execSQL($sql.$sql_order.$sql_limit,4, 0);
            if (!$ret[0]) {
              while ($rs=pg_fetch_array($ret[1])) {
                $layerset[$i]['shape'][]=$rs;
              }
              # Anzahl der Datensätze abfragen
              $sql = "SELECT count(*) FROM (".$sql.") as foo";
              $ret=$layerdb->execSQL($sql,4, 0);
              if (!$ret[0]) {
                $rs=pg_fetch_array($ret[1]);
                $layerset[$i]['count'] = $rs[0];
              }
            }
            # Hier nach der Abfrage der Sachdaten die weiteren Attributinformationen hinzufügen
            # Steht an dieser Stelle, weil die Auswahlmöglichkeiten von Auswahlfeldern abhängig sein können
            $layerset[$i]['attributes'] = $this->mapDB->add_attribute_values($layerset[$i]['attributes'], $layerdb, $layerset[$i]['shape']);
                        
            # Querymaps erzeugen
            if($layerset[$i]['querymap'] == 1 AND $layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['the_geom']] >= '0' AND ($layerset[$i]['Datentyp'] == 1 OR $layerset[$i]['Datentyp'] == 2)){
              for($k = 0; $k < count($layerset[$i]['shape']); $k++){
                $layerset[$i]['querymaps'][$k] = $this->createQueryMap($layerset[$i], $k);
              }
            }
            
            # Datendrucklayouts abfragen
            $this->ddl = new ddl($this->database);
            $layerset[$i]['layouts'] = $this->ddl->load_layouts($this->Stelle->id, NULL, $layerset[$i]['Layer_ID'], array(0,1));
            
            $this->qlayerset[]=$layerset[$i];
          }  break; # ende Layer ist aus postgis

          case MS_WMS : {
            $request=$layerset[$i]['connection'];

            # GetMap durch GetFeatureInfo ersetzen
            $request = str_replace('getmap','GetFeatureInfo',strtolower($request));
            $request = $request.'&REQUEST=GetFeatureInfo&SERVICE=WMS';

            # Anzufragenden Layernamen
            $reqStr=explode('&',strstr(strtolower($request),'layers='));
            $layerStr=explode('=',$reqStr[0]);
            $request .='&QUERY_LAYERS='.$layerStr[1];

            # Boundingbox im System des Layers anhängen
            $projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
            $projTO = ms_newprojectionobj("init=epsg:".$layerset[$i]['epsg_code']);

            $bbox=ms_newRectObj();
            $bbox->setextent($this->map->extent->minx,$this->map->extent->miny,$this->map->extent->maxx,$this->map->extent->maxy);
            
            $bbox = $this->pgdatabase->transformRect($bbox,$this->user->rolle->epsg_code,$layerset[$i]['epsg_code']);            
            $bbox = $bbox[1];
            
            #$bbox->project($projFROM, $projTO);
            #echo $bbox->minx.','.$bbox->miny.','.$bbox->maxx.','.$bbox->maxy.'<br>';

            $request .='&BBOX='.$bbox->minx.','.$bbox->miny.','.$bbox->maxx.','.$bbox->maxy;
            $request .='&WIDTH='.$this->user->rolle->nImageWidth.'&HEIGHT='.$this->user->rolle->nImageHeight;

            # EPSG-Code anhängen
            $request .='&SRS=EPSG:'.$layerset[$i]['epsg_code'];

            # Anfrageposition anhängen
            $imgxy=explode(';',$this->formvars['INPUT_COORD']);
            $minxy=explode(',',$imgxy[0]);
            $maxxy=explode(',',$imgxy[1]);
            $x=($maxxy[0]+$minxy[0])/2;
            $y=($maxxy[1]+$minxy[1])/2;
            $request .='&X='.$x.'&Y='.$y;

            # Ausgabeformat
            if(strpos(strtolower($request), 'info_format') === false){
            	$request .='&INFO_FORMAT=text/html';
            }

            $layerset[$i]['GetFeatureInfoRequest']=$request;
            #echo $request;

            $this->qlayerset[]=$layerset[$i];
          }  break;

          case MS_WFS : { # WFS Layer (9)
            switch ($layerset[$i]['toleranceunits']) {
              case 'pixels' : $pixsize=$this->user->rolle->pixsize; break;
              case 'meters' : $pixsize=1; break;
              default : $pixsize=$this->user->rolle->pixsize;
            }
            if($rect->minx == $rect->maxx AND $rect->miny == $rect->maxy){
            	$rand=$layerset[$i]['tolerance']*$pixsize;
            }
            $projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
            $projTO = ms_newprojectionobj("init=epsg:".$layerset[$i]['epsg_code']);
            $rect->project($projFROM, $projTO);
            $searchbox_minx=strval($rect->minx-$rand);
            $searchbox_miny=strval($rect->miny-$rand);
            $searchbox_maxx=strval($rect->maxx+$rand);
            $searchbox_maxy=strval($rect->maxy+$rand);

            $url = $layerset[$i]['connection'];
            $version = '1.0.0';
            $typename = $layerset[$i]['wms_name'];
            $bbox=$searchbox_minx.','.$searchbox_miny.','.$searchbox_maxx.','.$searchbox_maxy;
            $wfs = new wfs($url, $version, $typename);
            # Attributnamen ermitteln
            $wfs->describe_featuretype_request();            
            $wfs->parse_gml('sequence');
            $layerset[$i]['attributes'] = $wfs->get_attributes();
            # Abfrage absetzen
            $wfs->get_feature_request($bbox, NULL, MAXQUERYROWS);
            $wfs->parse_gml('gml:featureMember');
            $features = $wfs->extract_features();
            for($j = 0; $j < count($features); $j++){
              for($k = 0; $k < count($layerset[$i]['attributes']['name']); $k++){
                $layerset[$i]['shape'][$j][$layerset[$i]['attributes']['name'][$k]] = $features[$j]['value'][$k];
                $layerset[$i]['attributes']['privileg'][$k] = 0;
              }
              $layerset[$i]['shape'][$j]['geom'] = $features[$j]['geom'];
            }
            $this->qlayerset[]=$layerset[$i];
          } break;

          default : { # alle anderen Layertypen
            echo 'Die Sachdatenabfrage für den connectiontype: '.$layerset[$i]['connectiontype'].' wird nicht unterstützt.';
          }
        } # ende Switch
      } # ende der Behandlung der zur Abfrage ausgewählten Layer
    } # ende der Schleife zur Abfrage der Layer der Stelle
    $this->main='sachdatenanzeige.php';
  }

  function WLDGE_Auswaehlen() {
    $this->debug->write("kvwmap.php WLDGE_Auswaehlen",4);
    $this->titel='WLDGE Datei auswählen';
    $this->main='wldgedateiauswahl.php';
  }

  function createReferenceMap($width, $height, $minx, $miny, $maxx, $maxy, $zoomfactor, $refmapfile){
    $refmap = ms_newMapObj($refmapfile);
    $refmap->set('width', $width);
    $refmap->set('height', $height);
    $refmap->setextent($minx,$miny,$maxx,$maxy);
    $projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
    $projTO = ms_newprojectionobj("init=epsg:".EPSGCODE);
    $refmap->extent->project($projFROM, $projTO);
    # zoomen
    $oPixelPos=ms_newPointObj();
    $oPixelPos->setXY($width/2,$height/2);
    //$refmap->zoomscale($scale,$oPixelPos,$width,$height,$refmap->extent,$this->Stelle->MaxGeorefExt);
    $refmap->zoompoint($zoomfactor,$oPixelPos,$width,$height,$refmap->extent);

    if($refmap->selectOutputFormat('jpeg_print') == 1){
      $refmap->selectOutputFormat('jpeg');
    }
    $image_map = $refmap->draw();
    $filename = $this->map_saveWebImage($image_map,'jpeg');
    $newname = $this->user->id.basename($filename);
    rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
    $uebersichtskarte = IMAGEURL.$newname;
    return $uebersichtskarte;
  }

  function spatial_processing(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    if($layerdb == NULL){
      $layerdb = $this->pgdatabase;
    }
    $this->processor = new spatial_processor($this->user->rolle, $this->database, $layerdb);
    $this->processor->process_query($this->formvars);
  }

  function getRow() {
    $ret=$this->database->getRow($this->formvars['select'],$this->formvars['from'],$this->formvars['where']);
    $first=1;
    while (list($key, $val) = each($ret[1])) {
      if (!$first) {
        echo "^";
      }
      echo $val;
      $first=0;
    }
  }

  function layerfromMapfile(){
    $this->titel='Layer aus Mapdatei laden';
    $this->main='layerfrommapfile_formular.php';
  }

  function layerfromMapfile_addlayer($formvars){
    if($formvars['mapfilename'] != ''){
      $this->layercount = 0;
      $this->classcount = 0;
      $this->stylecount = 0;
      $this->labelcount = 0;
      $this->fontfilecount = 0;
      $this->fontsetcount = 0;
      $this->symbolcount = 0;
      $this->groupcount = 0;
      $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
      $this->mapobject = ms_newMapObj($formvars['mapfilename']);

      # Fonts
      if($this->formvars['checkfont'] AND $this->mapobject->fontsetfilename != ''){
        if(strpos($this->mapobject->fontsetfilename,'.') === 0){
          $fontsetfilename = trim($this->mapobject->fontsetfilename,'.');
          $fontpath = dirname($formvars['mapfilename']).$fontsetfilename;
          $new_fontset = file($fontpath);
          $kvwmap_fontset = fopen(FONTSET, 'a');
          for($i = 0; $i < count($new_fontset); $i++){
            # kvwmap-fontset erweitern
            fwrite($kvwmap_fontset, $new_fontset[$i]);
            $this->fontsetcount++;
            # Fontdateien kopieren
            $explosion = explode(' ', trim($new_fontset[$i]));
            $font = array_pop($explosion);
            if(file_exists(dirname($fontpath).'/'.$font)){  // Datei vorhanden?
              if(!file_exists(dirname(FONTSET).'/'.$font)){ // nichts überschreiben
                copy(dirname($fontpath).'/'.$font, dirname(FONTSET).'/'.$font);
                $this->fontfilecount++;
              }
            }
          }
          fclose($kvwmap_fontset);
        }
        else{
          echo 'Dieses Mapfile verweist zwar auf eine Fontdatei, die Pfadangabe ist jedoch nicht relativ.';
        }
      }

      # Symbole
      if($this->formvars['checksymbol'] AND $this->mapobject->symbolsetfilename != ''){
        if(strpos($this->mapobject->symbolsetfilename,'.') === 0){
          $kvwmap_symbolset = file(SYMBOLSET);
          while(strpos(array_pop($kvwmap_symbolset), 'END') === false){} // letztes END löschen
          $symbolsetfilename = trim($this->mapobject->symbolsetfilename,'.');
          $symbolpath = dirname($formvars['mapfilename']).$symbolsetfilename;
          $new_symbolset = file($symbolpath);
          while(strpos(array_shift($new_symbolset), 'SYMBOLSET') === false){} // SYMBOLSET am Anfang löschen
          $symbols = fopen(SYMBOLSET, 'w');
          for($i = 0; $i < count($kvwmap_symbolset); $i++){
            fwrite($symbols, $kvwmap_symbolset[$i]);
          }
          for($i = 0; $i < count($new_symbolset); $i++){
            fwrite($symbols, $new_symbolset[$i]);
            if(strpos($new_symbolset[$i], 'SYMBOL') !== false){
              $this->symbolcount++;
            }
          }
          fclose($symbols);
        }
        else{
          echo 'Dieses Mapfile verweist zwar auf eine Symboldatei, die Pfadangabe ist jedoch nicht relativ.';
        }
      }

      # Layerarray füllen und nach Gruppen sortieren
      for($i = 0; $i < $this->mapobject->numlayers; $i++){
        $layers[] = $this->mapobject->getLayer($i);
        if($layers[$i]->group == ''){
          $layers[$i]->group = 'Gruppe1';
        }
      }
      usort($layers, 'compare_groups');
      # Layer etc. in DB schreiben
      $lastgroup = NULL;
      for($i = 0; $i < $this->mapobject->numlayers; $i++){
        if($formvars['layer'.$i] != ''){
          $layer = $layers[$formvars['layer'.$i]];
          if($lastgroup != $layer->group){
            $group_id = $mapDB->newGroup($layer->group);
            $this->groupcount++;
            $lastgroup = $layer->group;
          }
          $layer->group = $group_id;
          $layer_id = $mapDB->newLayer($layer);
          $this->layercount++;
          for($j = 0; $j < $layer->numclasses; $j++){
            $class = $layer->getClass($j);
            $class->layer_id = $layer_id;
            $class->drawingorder = $j;
            $class_id = $mapDB->new_Class($class);
            $this->classcount++;
            for($k = 0; $k < $class->numstyles; $k++){
              $style = $class->getStyle($k);
              $style_id = $mapDB->new_Style($style);
              $mapDB->addStyle2Class($class_id, $style_id, $k);
              $this->stylecount++;
            }
            $label = $class->label;
            if($label != NULL){
              $label_id = $mapDB->new_Label($label);
              $mapDB->addLabel2Class($class_id, $label_id);
              $this->labelcount++;
            }
          }
        }
      }
    }
    $this->layerfromMapfile();
  }

  function layerfromMapfile_load($formvars){
  	$_files = $_FILES;
    if($_files['mapfile']['name']){           # eine einzelne Mapdatei wurde ausgewählt
    $nachDatei = UPLOADPATH.$_files['mapfile']['name'];
    $this->formvars['mapfile'] = $nachDatei;
      if(move_uploaded_file($_files['mapfile']['tmp_name'],$nachDatei)){
        #echo '<br>Lade '.$_files['mapfile']['tmp_name'].' nach '.$nachDatei.' hoch';
        $this->mapobject = ms_newMapObj($nachDatei);
        for($i = 0; $i < $this->mapobject->numlayers; $i++){
          $this->layers[] = $this->mapobject->getLayer($i);
        }
      }
    }
    elseif($_files['zipfile']['name']){     # eine Zipdatei wurde ausgewählt
    $this->formvars['zipfile'] = $_files['zipfile']['name'];
    $nachDatei = UPLOADPATH.$_files['zipfile']['name'];
      if(move_uploaded_file($_files['zipfile']['tmp_name'],$nachDatei)){
        #echo '<br>Lade '.$_files['zipfile']['tmp_name'].' nach '.$nachDatei.' hoch';
        # ersten Ordner im Archiv finden
        exec('unzip -l '.$nachDatei.' -d '.UPLOADPATH, $output);
        $line = $output[3];
        $explosion = explode('/', $line);
        if(count($explosion) > 1){
          # unzip
          exec('unzip '.$nachDatei.' -d '.UPLOADPATH);
          $explosion = explode(' ', $explosion[0]);
          $this->firstfolder = array_pop($explosion);
        }
        else{
          $folder = rand(0,10000);
          mkdir($folder);
          # unzip
          exec('unzip '.$nachDatei.' -d '.UPLOADPATH.$folder);
          $this->firstfolder = $folder;
        }
        $dir = searchdir(UPLOADPATH.$this->firstfolder, true);
        for($i = 0; $i < count($dir); $i++){
          $explosion = explode('.',$dir[$i]);
          if($explosion[count($explosion)-1] == 'map'){
            $this->mapfiles[] = $dir[$i];
          }
        }
      }
    }
    elseif($formvars['zipmapfile']){      # aus dem Zip-Archiv wurde eine Mapdatei ausgewählt
      $this->formvars['mapfile'] = $formvars['zipmapfile'];
      $this->mapobject = ms_newMapObj($formvars['zipmapfile']);
      # Layerarray füllen und nach Gruppen sortieren
      for($i = 0; $i < $this->mapobject->numlayers; $i++){
        $this->layers[] = $this->mapobject->getLayer($i);
        if($this->layers[$i]->group == ''){
          $this->layers[$i]->group = 'Gruppe1';
        }
      }
      usort($this->layers, 'compare_groups');
      $this->firstfolder = $formvars['firstfolder'];
    }
    $this->layerfromMapfile();
  }

  function tooltip_query($rect){
    $this->mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->queryrect = $rect;
    if($this->formvars['querylayer_id'] != 'undefined'){
      $layerset = $this->user->rolle->getLayer($this->formvars['querylayer_id']);
      $anzLayer=count($layerset);
    }
    else{
      echo 'Bitte wählen Sie einen Layer zur Sachdatenabfrage aus.~';
    }
    $map=ms_newMapObj('');
    $map->set('shapepath', SHAPEPATH);
    for ($i=0;$i<$anzLayer;$i++) {
      # Dieser Layer soll abgefragt werden
      if($layerset[$i]['alias'] != '' AND $this->Stelle->useLayerAliases){
      	$layerset[$i]['Name'] = $layerset[$i]['alias'];
      }	
      $output .= $layerset[$i]['Name'].' : || ';
      $layerdb = $this->mapDB->getlayerdatabase($layerset[$i]['Layer_ID'], $this->Stelle->pgdbhost);
      $path = $layerset[$i]['pfad'];
      $privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
      #$path = $this->Stelle->parse_path($layerdb, $path, $privileges);
      $layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], $layerdb, $privileges['attributenames']);
	    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
	    #$layerset[$i]['attributes'] = $this->mapDB->add_attribute_values($layerset[$i]['attributes'], $layerdb, NULL, true);
      

      # order by rausnehmen
	  	$orderbyposition = strpos(strtolower($path), 'order by');
	  	if($orderbyposition !== false){
		  	$layerset[$i]['attributes']['orderby'] = ' '.substr($path, $orderbyposition);
		  	$path = substr($path, 0, $orderbyposition);
	  	}
	  	
	  	# group by rausnehmen
			$groupbyposition = strpos(strtolower($path), 'group by');
			if($groupbyposition !== false){
				$layerset[$i]['attributes']['groupby'] = ' '.substr($path, $groupbyposition);
				$path = substr($path, 0, $groupbyposition);
	  	}

      $show = false;
      for($j = 0; $j < count($layerset[$i]['attributes']['name']); $j++){
        $layerset[$i]['attributes']['tooltip'][$j] = $privileges['tooltip_'.$layerset[$i]['attributes']['name'][$j]];
        if($layerset[$i]['attributes']['tooltip'][$j] == 1){
          $show = true;
        }
      }
      if(!$show){
        return NULL;
      }
      $pfad = substr(trim($path), 7);

      /*if(strpos(strtolower($pfad), 'as the_geom') !== false){
        $the_geom = 'query.the_geom';
      }
      else{*/
      	if($layerset[$i]['attributes']['the_geom'] == ''){					# Geometriespalte ist nicht geladen, da auf "nicht sichtbar" gesetzt --> aus Data holen
      		$data_attributes = $this->mapDB->getDataAttributes($layerdb, $layerset[$i]['Layer_ID']);
      		$layerset[$i]['attributes']['the_geom'] = $data_attributes['the_geom'];
      	}
        /*if($layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']]){
          $the_geom = $layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$layerset[$i]['attributes']['the_geom'];
        }
        else{*/
          $the_geom = $layerset[$i]['attributes']['the_geom'];
      //  }
      //}
      
      //$the_geom = $layerset[$i]['attributes']['the_geom'];

      switch ($layerset[$i]['toleranceunits']) {
        case 'pixels' : $pixsize=$this->user->rolle->pixsize; break;
        case 'meters' : $pixsize=1; break;
        default : $pixsize=$this->user->rolle->pixsize;
      }
      $rand=$layerset[$i]['tolerance']*$pixsize;
      
      # Aktueller EPSG in der die Abfrage ausgeführt wurde
      $client_epsg=$this->user->rolle->epsg_code;
      # EPSG-Code des Layers der Abgefragt werden soll
      $layer_epsg=$layerset[$i]['epsg_code'];
      # Bildung der Where-Klausel für die räumliche Abfrage mit der searchbox
      $loosesearchbox_wkt ="POLYGON((";
      $loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand).",";
      $loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->miny-$rand).",";
      $loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->maxy+$rand).",";
      $loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->maxy+$rand).",";
      $loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand)."))";


      # Wenn das Koordinatenssystem des Views anders ist als vom Layer wird die Suchbox und die Suchgeometrie
      # in epsg des layers transformiert
      if ($client_epsg!=$layer_epsg) {
        $sql_where =" AND ".$the_geom." && st_transform(st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg."),".$layer_epsg.")";
      }
      else {
        $sql_where =" AND ".$the_geom." && st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg.")";
      }

      # Wenn es sich bei der Suche um eine punktuelle Suche handelt, wird die where Klausel um eine
      if($rect->minx==$rect->maxx AND $rect->miny==$rect->maxy AND $this->querypolygon == ''){
        if ($client_epsg!=$layer_epsg) {
          $sql_where.=" AND st_distance(".$the_geom.",st_transform(st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."),".$layer_epsg."))";
        }
        else {
          $sql_where.=" AND st_distance(".$the_geom.",st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."))";
        }
        $sql_where.=" <= ".$rand;
      }
      
      # SVG-Geometrie abfragen für highlighting
      if($this->user->rolle->highlighting == '1'){
        $pfad = "st_assvg(st_transform(".$layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$the_geom.", ".$client_epsg."), 0, 8) AS highlight_geom, ".$pfad;
      }
      
      # 2006-06-12 sr   Filter zur Where-Klausel hinzugefügt
      if($layerset[$i]['Filter'] != ''){
      	$layerset[$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset[$i]['Filter']);
        $sql_where .= " AND ".$layerset[$i]['Filter'];
      }
      #if($the_geom == 'query.the_geom'){
        $sql = "SELECT * FROM (SELECT ".$pfad.") as query WHERE 1=1 ".$sql_where;
      /*}
      else{
        $sql = "SELECT ".$pfad." ".$sql_where;
      }
      */
            
      # group by wieder einbauen
    	if($layerset[$i]['attributes']['groupby'] != ''){
      	$sql .= $layerset[$i]['attributes']['groupby'];
      	$j = 0;
      	foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
					if($layerset[$i]['attributes']['oids'][$j]){      # hat Tabelle oids?
						$sql .= ','.$tablename.'_oid ';
					}
					$j++;
      	}	
      }
      
      # order by wieder einbauen
			if($layerset[$i]['attributes']['orderby'] != ''){										#  der Layer hat im Pfad ein ORDER BY
      	$sql .= $layerset[$i]['attributes']['orderby'];
      }
      
      # Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
      $sql_limit.=' LIMIT '.MAXQUERYROWS;

      #echo '<br>sql:<br>'.$sql;
      $ret=$layerdb->execSQL($sql.$sql_limit,4, 0);
      if (!$ret[0]) {
        while ($rs=pg_fetch_array($ret[1])) {
          $layerset[$i]['shape'][]=$rs;
        }
      }
      $this->qlayerset[]=$layerset[$i];
    } # ende der Schleife zur Abfrage der Layer der Stelle
    # Tooltip-Abfrage
    if($this->show_query_tooltip == true){
      for($i = 0; $i < count($this->qlayerset); $i++) {
      	$layer = $this->qlayerset[$i];
 				$attributes = $layer['attributes'];
        $anzObj = count($layer['shape']);
        for($k = 0; $k < $anzObj; $k++) {
          $attribcount = 0;
          for($j = 0; $j < count($attributes['name']); $j++){
            if($attributes['tooltip'][$j]){
            	switch ($attributes['form_element_type'][$j]){
				        case 'Dokument' : {
				        	$filename = explode('&', $layer['shape'][$k][$attributes['name'][$j]]);
				        	if(file_exists($filename[0])){
				        		$info = pathinfo($filename[0]);
										if(in_array(strtolower($info['extension']), array('jpg', 'png', 'gif'))){
				        			$image = copy_file_to_tmp($filename[0]);
				          		$pictures .= '| '.$image;
										}
				        	}
				        }break;
				        case 'Link': {
		              $attribcount++;
									if($layer['shape'][$k][$attributes['name'][$j]]!='') {
										$link = 'xlink:'.$layer['shape'][$k][$attributes['name'][$j]];
										$links .= $link.'~';
									}
								} break;
				        default : {
		              if($attributes['alias'][$j] != ''){
		                $output .=  $attributes['alias'][$j].': ';
		              }
		              else{
		                $output .= $attributes['name'][$j].': ';
		              }
		              $attribcount++;
		              $output .= $layer['shape'][$k][$attributes['name'][$j]].'  ';
		              $output .= '~';
				        }
            	}
            }
          }
          # Links und Bild-URLs anfügen
          $output .= $links;
      		$output .= $pictures;
      		$pictures = '';
          $output .= '|| ';
        }
      }
      # highlighting-Geometrie anfügen
      $output .= '||| '.$this->qlayerset[0]['shape'][0]['highlight_geom'];
      echo umlaute_javascript(umlaute_html($output));
    }
  }

  function setFullExtent() {
    $this->map->setextent($this->Stelle->MaxGeorefExt->minx,$this->Stelle->MaxGeorefExt->miny,$this->Stelle->MaxGeorefExt->maxx,$this->Stelle->MaxGeorefExt->maxy);
  }


  function zoomToALKGemeinde($Gemeinde,$border) {
    # 2006-01-31 pk
    # 1. Funktion ermittelt das umschließende Rechteck der $Gemeinde aus der postgis Datenbank
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gemeinde in einem gesonderten Layer in Gelb dar
    # zu 1)
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromGemeinde($Gemeinde, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnte keine Gemeinde gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
      $this->adresswahl();
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    # zu 2)
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    # zu 3)
    $GemObj=new Gemeinde($Gemeinde,$this->pgdatabase);
    $layer=ms_newLayerObj($this->map);
    $datastring ="the_geom from (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflur as fl";
    $datastring.=",alb_v_gemarkungen AS g WHERE o.objnr=fl.objnr AND fl.gemkgschl::integer=g.gemkgschl";
    $datastring.=" AND g.gemeinde=".(int)$Gemeinde;
    $datastring.=") as foo using unique oid using srid=".EPSGCODE;
    $legendentext ="Gemeinde: ".$GemObj->getGemeindeName($Gemeinde);
    $layer->set('data',$datastring);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$legendentext);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    if (MAPSERVERVERSION < '540') {
      $layer->set('connectiontype', 6);
    }
    else {
      $layer->setConnectionType(6);
    }
    $connectionstring ='user='.$this->pgdatabase->user;
    $connectionstring.=' password='.$this->pgdatabase->passwd;
    if($this->pgdatabase->host != ''){
      $connectionstring.=' host='.$this->pgdatabase->host;
    }
    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
    $layer->set('connection',$connectionstring);
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0'); #2005-11-30_pk
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression($expression);
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(255,255,128);
    $style->outlinecolor->setRGB(0,0,0);
  }

  function zoomToGemeinde($GemID,$border) {
    $Gemeinde=new Gemeinde($GemID);
    # 1. Anlegen eines neuen Layers für die Suche nach Gemeinde
    $layer=ms_newLayerObj($this->map);
    $layer->set('data',SHAPEPATH.$Gemeinde->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name','Gemeinde: '.$Gemeinde->getGemeindeName());
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0');
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression('([GEMEINDE_L]='.$GemID.')');
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(200,0,0);
    # 2. zoom auf eine Gemeinde
    $this->setFullExtent();
    $rect=$Gemeinde->getMER($layer);
    if ($rect==0) {
      $this->Fehlermeldung='Diese Gemeinde konnte nicht gefunden werden.';
      $rect=$this->Stelle->MaxGeorefExt;
    }
    else {
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomToGemarkung($GemID,$GemkgID,$border) {
    $Gemarkung=new Gemarkung($GemkgID,$this->database);
    # 1. Anlegen eines neuen Layers für die Suche nach Gemarkung
    $layer=ms_newLayerObj($this->map);
    $layer->set('data',SHAPEPATH.$Gemarkung->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name','Gemarkung: '.$Gemarkung->getGemkgName());
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0');
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression('([GEMARKUNG_]='.$GemkgID.')');
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(200,0,0);
    # 2. zoom auf eine Gemarkung
    $this->setFullExtent();
    $rect=$Gemarkung->getMER($layer);
    if ($rect==0) {
      $this->Fehlermeldung='Diese Gemarkung konnte nicht gefunden werden.';
      $rect=$this->Stelle->MaxGeorefExt;
    }
    else {
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomToALKGemarkung($Gemkgschl,$border) {
    # 2006-02-01 pk
    # 1. Funktion ermittelt das umschließende Rechteck der $Gemarkung aus der postgis Datenbank
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gemarkung in einem gesonderten Layer in Gelb dar
    # zu 1)
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromGemarkung($Gemkgschl, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnte keine Gemarkung gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
      $this->flurstwahl();
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    # zu 2)
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    # zu 3)
    $GemkgObj=new Gemarkung($Gemkgschl,$this->pgdatabase);
    $layer=ms_newLayerObj($this->map);
    $datastring ="the_geom from (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflur as fl";
    $datastring.=",alb_v_gemarkungen AS g WHERE o.objnr=fl.objnr";
    $datastring.=" AND fl.gemkgschl='".$Gemkgschl."'";
    $datastring.=") as foo using unique oid using srid=".EPSGCODE;
    $legendentext ="Gemarkung: ".$GemkgObj->getGemkgName($Gemkgschl);
    $layer->set('data',$datastring);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$legendentext);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    if (MAPSERVERVERSION < '540') {
      $layer->set('connectiontype', 6);
    }
    else {
      $layer->setConnectionType(6);
    }
    $connectionstring ='user='.$this->pgdatabase->user;
    $connectionstring.=' password='.$this->pgdatabase->passwd;
    if($this->pgdatabase->host != ''){
      $connectionstring.=' host='.$this->pgdatabase->host;
    }
    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
    $layer->set('connection',$connectionstring);
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0'); #2005-11-30_pk
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression($expression);
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(255,255,128);
    $style->outlinecolor->setRGB(0,0,0);
  }

  function zoomToALKFlur($GemID,$GemkgID,$FlurID,$border) {
    # 2006-02-01 pk
    # 1. Funktion ermittelt das umschließende Rechteck der $Gemarkung aus der postgis Datenbank
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gemarkung in einem gesonderten Layer in Gelb dar
    # zu 1)
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromFlur($GemkgID,$FlurID, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnte keine Flur gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    # zu 2)
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    # zu 3)
    $GemkgObj=new Gemarkung($GemkgID,$this->pgdatabase);
    $layer=ms_newLayerObj($this->map);
    $datastring ="the_geom from (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflur as fl";
    $datastring.=",alb_v_gemarkungen AS g WHERE o.objnr=fl.objnr";
    $datastring.=" AND fl.gemkgschl='".$GemkgID."'";
    $datastring.=" AND fl.flur='".$FlurID."'";
    $datastring.=") as foo using unique oid using srid=".EPSGCODE;
    $legendentext ="Gemarkung: ".$GemkgObj->getGemkgName($GemkgID);
    $legendentext .="<br>Flur: ".$FlurID;
    $layer->set('data',$datastring);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$legendentext);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    if (MAPSERVERVERSION < '540') {
      $layer->set('connectiontype', 6);
    }
    else {
      $layer->setConnectionType(6);
    }
    $connectionstring ='user='.$this->pgdatabase->user;
    if($this->pgdatabase->passwd != ''){
      $connectionstring.=' password='.$this->pgdatabase->passwd;
    }
    if($this->pgdatabase->host != ''){
      $connectionstring.=' host='.$this->pgdatabase->host;
    }
    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
    $connectionstring.=' port=5432';
    $layer->set('connection',$connectionstring);
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0'); #2005-11-30_pk
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression($expression);
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(255,255,128);
    $style->outlinecolor->setRGB(0,0,0);
  }

  function zoomToFlur($GemID,$GemkgID,$FlurID,$border) {
    $Gemarkung=new Gemarkung($GemkgID,$this->database);
    $Flur=new Flur($GemID,$GemkgID,$FlurID,$this->database);
    # 1. Anlegen eines neuen Layers für die Suche nach Fluren
    $layer=ms_newLayerObj($this->map);
    $layer->set('data',SHAPEPATH.$Flur->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$Gemarkung->getGemkgName().'<br>Flur: '.$FlurID);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0');
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression('([FLUR_ID]='.$GemkgID.$FlurID.')');
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(200,0,0);
    # 2. zoom auf eine Flur
    $this->setFullExtent();
    $rect=$Flur->getMER($layer);
    if ($rect==0) {
      $this->Fehlermeldung='Diese Flur konnte nicht gefunden werden.';
      $rect=$this->Stelle->MaxGeorefExt;
    }
    else {
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomToFlurst($FlurstListe,$border) {
    #2005-11-30_pk
    if (count($FlurstListe)>1) {
      $expression='("[FKZ]" eq "'.$FlurstListe['FlurstKennz'][0].'"';
      $LegendeText='Flurstücke:<br>'.$FlurstListe['FlurstNr'][0];
      for ($i=1;$i<count($FlurstListe['FlurstKennz']);$i++) {
        $expression.=' OR "[FKZ]" eq "'.$FlurstListe['FlurstKennz'][$i].'"';
        $LegendeText.=', '.$FlurstListe['FlurstNr'][$i];
      }
      $expression.=')';
      $ALK=new ALK();
      # 1. Anlegen eines neuen Layers für die Suche nach Flurstücken
      $layer=ms_newLayerObj($this->map);
      $layer->set('data',SHAPEPATH.$ALK->getDataSourceName());
      $layer->set('status',MS_ON);
      $layer->set('template', ' ');
      $layer->set('name',$LegendeText);
      $layer->set('type',2);
      $layer->set('group','Suchergebnis');
      $layer->setMetaData('off_requires',0);
      $layer->setMetaData('layer_has_classes',0);
      $this->map->setMetaData('group_status_Suchergebnis','0');
      $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
      $layer->setMetaData('queryStatus','2');
      $layer->setMetaData('wms_queryable','1');
      $layer->setMetaData('layer_hidden','0');
      $klasse=ms_newClassObj($layer);
      $klasse->set('status', MS_ON);
      $klasse->setexpression($expression);
      $style=ms_newStyleObj($klasse);
      $style->color->setRGB(255,255,128);
      # 2. zoom auf ein oder mehrere Flurstücke
      $this->setFullExtent();
      $rect=$ALK->getRectByFlurstListe($FlurstListe['FlurstKennz'],$layer);
      if ($rect==0) {
        $this->Fehlermeldung='Es konnten keine Flurstücke gefunden werden.';
        $rect=$this->Stelle->MaxGeorefExt;
      }
      else {
        $randx=($rect->maxx-$rect->minx)*$border/100;
        $randy=($rect->maxy-$rect->miny)*$border/100;
      }
      $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
  }

  function zoomToALKFlurst($FlurstListe,$border){
    #2005-11-30_pk
    # 1. Funktion ermittelt das umschließende Rechteck der in $FlurstListe übergebenen
    # Flurstückskennz aus der postgis Datenbank
    # mit Rand entsprechend dem Faktor $border
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Flurstücke in einem gesonderten Layer in Gelb dar
    # zu 1)
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromFlurstuecke($FlurstListe, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine Flurstücke gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }

    # zu 3)
    if(ALKIS){
    	$datastring ="the_geom from (select f.gml_id as oid, wkb_geometry as the_geom from alkis.ax_flurstueck as f";
    	$datastring.=" WHERE f.flurstueckskennzeichen IN ('".$FlurstListe[0]."'";
    	$epsg = EPSGCODE_ALKIS;
    }
    else{
    	$datastring ="the_geom from (select o.objnr as id, o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflst as f";
    	$datastring.=" WHERE o.objnr=f.objnr AND f.flurstkennz IN ('".$FlurstListe[0]."'";
    	$epsg = EPSGCODE;
    }
    $legendentext="Flurstück";
    if(count($FlurstListe) > 1){
      $legendentext .= "e";
    }
    $legendentext .= " (".date('d.m. H:i',time())."):<br>".$FlurstListe[0];
    for ($i=1;$i<count($FlurstListe);$i++) {
      $datastring.=",'".$FlurstListe[$i]."'";
      $legendentext.=",<br>".$FlurstListe[$i];
    }
   	$datastring.=")) as foo using unique oid using srid=".$epsg;

    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);

    $group = $dbmap->getGroupbyName('Suchergebnis');
    if($group != ''){
      $groupid = $group['id'];
    }
    else{
      $groupid = $dbmap->newGroup('Suchergebnis');
    }

    $this->formvars['user_id'] = $this->user->id;
    $this->formvars['stelle_id'] = $this->Stelle->id;
    $this->formvars['aktivStatus'] = 1;
    $this->formvars['Name'] = $legendentext;
    $this->formvars['Gruppe'] = $groupid;
    $this->formvars['Typ'] = 'search';
    $this->formvars['Datentyp'] = 2;
    $this->formvars['Data'] = $datastring;
    $this->formvars['connectiontype'] = 6;
    $connectionstring ='user='.$this->pgdatabase->user;
    if($this->pgdatabase->passwd != ''){
      $connectionstring.=' password='.$this->pgdatabase->passwd;
    }
    if($this->pgdatabase->host != ''){
      $connectionstring.=' host='.$this->pgdatabase->host;
    }
    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
    $this->formvars['connection'] = $connectionstring;
    $this->formvars['epsg_code'] = $epsg;
    $this->formvars['transparency'] = 60;

    $layer_id = $dbmap->newRollenLayer($this->formvars);
    
    $classdata[0] = '';
    $classdata[1] = -$layer_id;
    $classdata[2] = '';
    $classdata[3] = 0;
    $class_id = $dbmap->new_Class($classdata);

		$color = $this->user->rolle->readcolor();
    $style['colorred'] = $color['red'];
		$style['colorgreen'] = $color['green'];
		$style['colorblue'] = $color['blue'];    
    $style['outlinecolorred'] = 0;
    $style['outlinecolorgreen'] = 0;
    $style['outlinecolorblue'] = 0;
    $style['size'] = 1;
    $style['symbol'] = NULL;
    $style['symbolname'] = NULL;
    $style['backgroundcolor'] = NULL;
    $style['minsize'] = NULL;
    $style['maxsize'] = 100000;
    if (MAPSERVERVERSION > '500') {
    	$style['angle'] = 360;
    }
    $style_id = $dbmap->new_Style($style);

    $dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
    $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen

    $this->loadMap('DataBase');
    # zu 2)
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomToALKGebaeude($Gemeinde,$Strasse,$StrName,$Hausnr,$border) {
    # 2006-01-31 pk
    # 1. Funktion ermittelt das umschließende Rechteck der mit $Gemeinde,$Strasse und $Hausnr übergebenen
    # Gebaeude aus der postgis Datenbank mit Rand entsprechend dem Faktor $border
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gebaeude in einem gesonderten Layer in Gelb dar
    # zu 1)
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine Gebäude gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    
	    # zu 2)
	    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
	    # zu 3)
	    $layer=ms_newLayerObj($this->map);
	    if(ALKIS){
	    	$epsg = EPSGCODE_ALKIS;
	    	$datastring ="the_geom from (select g.gml_id as oid, wkb_geometry as the_geom FROM alkis.ax_gemeinde gem, alkis.ax_gebaeude g";
		    $datastring.=" LEFT JOIN alkis.alkis_beziehungen v ON g.gml_id=v.beziehung_von"; 
				$datastring.=" LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON v.beziehung_zu=l.gml_id";
				$datastring.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde";
				$datastring.=" AND l.lage = lpad(s.lage,5,'0')";
				$datastring.=" WHERE gem.gemeinde = l.gemeinde";
		    if ($Hausnr!='') {
		    	$Hausnr = str_replace(", ", ",", $Hausnr);
		    	$Hausnr = strtolower(str_replace(",", "','", $Hausnr));    	
		      $datastring.=" AND gem.schluesselgesamt||'-'||l.lage||'-'||TRIM(LOWER(l.hausnummer)) IN ('".$Hausnr."')";
		    }
	    	else{
			    $datastring.=" AND gem.schluesselgesamt=".(int)$Gemeinde;
			    if ($Strasse!='') {
			      $datastring.=" AND l.lage='".$Strasse."'";
			    }
		    }
	    }
	    else{
	    	$epsg = EPSGCODE;
		    $datastring ="the_geom from (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknhaus as h";
		    $datastring.=" WHERE o.objnr=h.objnr";
		    if (trim($Hausnr)!='') {
		    	$Hausnr = str_replace(", ", ",", $Hausnr);
		    	$Hausnr = strtolower(str_replace(",", "','", $Hausnr));
		      $datastring.=" AND h.gemeinde||'-'||h.strasse||'-'||TRIM(LOWER(h.hausnr)) IN ('".$Hausnr."')";
		    }
	    	else{
			    $datastring.=" AND h.gemeinde=".(int)$Gemeinde;
			    if ($Strasse!='') {
			      $datastring.=" AND h.strasse='".$Strasse."'";
			    }
		    }
	    }
	    $datastring.=") as foo using unique oid using srid=".$epsg;
	    $legendentext ="Geb&auml;ude<br>";
	    if ($Hausnr!='') {
	      $legendentext.="HausNr: ".str_replace(',', '<br>', $Hausnr);
	    }
	    else{
	    	$legendentext.=$StrName;
	    }
	    	    
	    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);

	    $group = $dbmap->getGroupbyName('Suchergebnis');
	    if($group != ''){
	      $groupid = $group['id'];
	    }
	    else{
	      $groupid = $dbmap->newGroup('Suchergebnis');
	    }
	
	    $this->formvars['user_id'] = $this->user->id;
	    $this->formvars['stelle_id'] = $this->Stelle->id;
	    $this->formvars['aktivStatus'] = 1;
	    $this->formvars['Name'] = $legendentext;
	    $this->formvars['Gruppe'] = $groupid;
	    $this->formvars['Typ'] = 'search';
	    $this->formvars['Datentyp'] = 2;
	    $this->formvars['Data'] = $datastring;
	    $this->formvars['connectiontype'] = 6;
	    $connectionstring ='user='.$this->pgdatabase->user;
	    if($this->pgdatabase->passwd != ''){
	      $connectionstring.=' password='.$this->pgdatabase->passwd;
	    }
	    if($this->pgdatabase->host != ''){
	      $connectionstring.=' host='.$this->pgdatabase->host;
	    }
	    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
	    $this->formvars['connection'] = $connectionstring;
	    $this->formvars['epsg_code'] = $epsg;
	    $this->formvars['transparency'] = 60;
	
	    $layer_id = $dbmap->newRollenLayer($this->formvars);
	    
	    $classdata[0] = '';
	    $classdata[1] = -$layer_id;
	    $classdata[2] = '';
	    $classdata[3] = 0;
	    $class_id = $dbmap->new_Class($classdata);
	
			$color = $this->user->rolle->readcolor();
	    $style['colorred'] = $color['red'];
			$style['colorgreen'] = $color['green'];
			$style['colorblue'] = $color['blue'];    
	    $style['outlinecolorred'] = 0;
	    $style['outlinecolorgreen'] = 0;
	    $style['outlinecolorblue'] = 0;
	    $style['size'] = 1;
	    $style['symbol'] = NULL;
	    $style['symbolname'] = NULL;
	    $style['backgroundcolor'] = NULL;
	    $style['minsize'] = NULL;
	    $style['maxsize'] = 100000;
	    if (MAPSERVERVERSION > '500') {
	    	$style['angle'] = 360;
	    }
	    $style_id = $dbmap->new_Style($style);
	
	    $dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
	    $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
	
	    $this->loadMap('DataBase');
	    # zu 2)
	    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
    return $ret;
  }

  function zoomToGebaeude($GebaeudeListe,$border) {
    $expression='("[ID]" eq "'.$GebaeudeListe['ID'][0].'"';
    $LegendeText='Gemeinde: '.$GebaeudeListe['GemeindeSchl'][0].'<br>Strasse: '.$GebaeudeListe['StrassenSchl'][0].'<br>Gebäude Nr: '.$GebaeudeListe['HausNr'][0];
    for ($i=1;$i<count($GebaeudeListe['ID']);$i++) {
      $expression.=' OR "[ID]" eq "'.$GebaeudeListe['ID'][$i].'"';
      $LegendeText.=', '.$GebaeudeListe['HausNr'][$i];
    }
    $expression.=')';
    $Gebaeude=new Gebaeude('');
    # 1. Anlegen eines neuen Layers für die Suche nach Flurstücken
    $layer=ms_newLayerObj($this->map);
    $layer->set('data',SHAPEPATH.$Gebaeude->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$LegendeText);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0');
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression($expression);
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(255,255,128);
    # 2. zoom auf ein oder mehrere Gebaeude
    $this->setFullExtent();
    $rect=$Gebaeude->getRectByGebaeudeListe($GebaeudeListe['ID'],$layer);
    if ($rect==0) {
      $this->Fehlermeldung='Es konnten keine Gebäude gefunden werden.';
      $rect=$this->Stelle->MaxGeorefExt;
    }
    else {
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    # Aktiviere Gebäude und Flurstückslayer
    $geblayer=$this->map->getLayerByName('Gebaeude');
    $geblayer->set('status',MS_ON);
    $flstlayer=$this->map->getLayerByName('Flurstuecke');
    $flstlayer->set('status',MS_ON);
    $this->Stelle->addAktivLayer(array(2,3));
  }

	function zoomToGeom($geom,$border) {
    # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
    $sql.="SELECT st_xmin(st_extent('".$geom."')) AS minx,st_ymin(st_extent('".$geom."')) AS miny";
		$sql.=",st_xmax(st_extent('".$geom."')) AS maxx,st_ymax(st_extent('".$geom."')) AS maxy";
		$ret=$this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1].='Fehler bei der Abfrage der Boundingbox! \n';
    }
    else {
      # Abfrage fehlerfrei
      # Erzeugen eines RectObject
      $rect= ms_newRectObj();
      # Abfragen und zuordnen der Koordinaten der Box
      $rs=pg_fetch_array($ret[1]);
      if ($rs['maxx']-$rs['minx']==0) {
        $rs['maxx']=$rs['maxx']+1;
        $rs['minx']=$rs['minx']-1;        
      }
      if ($rs['maxy']-$rs['miny']==0) {
        $rs['maxy']=$rs['maxy']+1;
        $rs['miny']=$rs['miny']-1;        
      }
      $rect->minx=$rs['minx']; $rect->miny=$rs['miny'];
      $rect->maxx=$rs['maxx']; $rect->maxy=$rs['maxy'];
    	$randx=($rect->maxx-$rect->minx)*$border/100;
    	$randy=($rect->maxy-$rect->miny)*$border/100;
    	# Setzen der neuen Kartenausdehnung.
    	$this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
    }
  }

  function zoomToNachweis($nachweis,$border) {
    # Abfragen der Ausdehnung des Umringes des Nachweises
    $ret=$nachweis->getBBoxAsRectObj($nachweis->document['id'],'nachweis');
    if ($ret[0]) {
      # Fehler bei der Abfrage der BoundingBox
      # Es erfolgt keine Änderung der aktuellen Ausdehnung
    }
    else {
      $rect=$ret[1];
      # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
      # Setzen der neuen Kartenausdehnung.
      #var_dump($this->map->extent);
      $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
  }

  function zoomToPolygon($table, $poly_id,$border, $srid) {
    $ret=$this->pgdatabase->getPolygonBBox($table, $poly_id, $srid);
    if ($ret[0]) {
      # Fehler bei der Abfrage der BoundingBox
      # Es erfolgt keine Änderung der aktuellen Ausdehnung
    }
    else {
      $rect=$ret[1];
      # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
      # Setzen der neuen Kartenausdehnung.
      #var_dump($this->map->extent);
      $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
  }

  function zoomToMaxLayerExtent($layer_id) {
    # Abfragen der maximalen Ausdehnung aller Daten eines Layers
		
    # Abfragen des Data Statements des Layers
    $data=$this->mapDB->getData($layer_id);
    
    # suchen nach dem ersten Vorkommen von using unique
    $pos = strpos(strtolower($data),'using unique');

    # Abschneiden der unique Wörter im Datastatement wenn unique verwendet wurde
    if ($pos !== false) {
      $subquery=substr($data,0,$pos);
    }
    else {
      # unique kommt nicht vor, es handelt sich um ein einfaches Data Statement in der Form
      # the_geom from tabelle, übernehmen wie es ist.
      $subquery = $data;
    }
	
		# Abfragen der Datenbankverbindung des Layers
    $layerdb=$this->mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
    
  	#$data_attributes = $this->mapDB->getDataAttributes($layerdb, $layer_id);
	  	#$this->attributes['the_geom'] = $data_attributes['the_geom'];
	  	$explosion = explode(' ', $data);
  	$this->attributes['the_geom'] = $explosion[0];

		# Filter berücksichtigen
		$filter = $this->mapDB->getFilter($layer_id, $this->Stelle->id);
		if($filter != ''){
			if(strpos(strtolower($subquery), ' where ') !== false){
				$subquery .= ' AND '.$filter;
			}
			else{
				$subquery .= ' WHERE '.$filter;
			}
		}

    # Erzeugen des Abfragestatements für den maximalen Extent aus dem Data String
    $sql ='SELECT st_extent(st_transform('.$this->attributes['the_geom'].','.$this->user->rolle->epsg_code.')) AS extent FROM (SELECT ';
    $sql.=$subquery;
    $sql.=') AS fooForMaxLayerExtent';
    #echo $sql;

    # Abfragen der Layerausdehnung
    $ret=$layerdb->execSQL($sql,4,0);
    if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
    $rs = pg_fetch_array($ret[1]);
    if($rs['extent'] != ''){
	    $coords=explode(',',trim(strtolower($rs['extent']),'box()'));
	    #var_dump($coords);
	    $sw=explode(' ',$coords[0]);
	    $ne=explode(' ',$coords[1]);
	    $minx=$sw[0]-10;
	    $miny=$sw[1]-10;
	    $maxx=$ne[0]+10;
	    $maxy=$ne[1]+10;
	    #echo 'box:'.$minx.' '.$miny.','.$maxx.' '.$maxy;
	    $this->map->setextent($minx,$miny,$maxx,$maxy);
	    # damit nicht außerhalb des Stellen-Extents gezoomt wird
	    $oPixelPos=ms_newPointObj();
	    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
	    $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);    
	    #var_dump($this->map->extent);
	    if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
  }

  function createQueryMap($layerset, $k){
  	if($layerset['attributes']['the_geom'] != ''){
	    $layer_id = $layerset['Layer_ID'];
	    $tablename = $layerset['attributes']['table_name'][$layerset['attributes']['the_geom']];
	    $oid = $layerset['shape'][$k][$tablename.'_oid'];
	    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
	    $map = ms_newMapObj('');
	    $layerdb = $mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
	    # Auf den Datensatz zoomen
	    $sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
	    $sql.=" FROM (SELECT box2D(st_transform(".$layerset['attributes']['the_geom'].", ".$this->user->rolle->epsg_code.")) as bbox";
	    $sql.=" FROM ".$tablename." WHERE oid = '".$oid."') AS foo";
	    $ret = $layerdb->execSQL($sql, 4, 0);
	    $rs = pg_fetch_array($ret[1]);
	    $rect = ms_newRectObj();
	    $rect->minx=$rs['minx']; 
	    $rect->maxx=$rs['maxx'];
	    $rect->miny=$rs['miny']; 
	    $rect->maxy=$rs['maxy'];
	    $randx=($rect->maxx-$rect->minx)*50/100;
	    $randy=($rect->maxy-$rect->miny)*50/100;
	    if($rect->minx != ''){
	    	$map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
		    # Haupt-Layer erzeugen
		    $layer=ms_newLayerObj($map);
		    $layer->set('data',$layerset['Data']);    
		    $layer->set('status',MS_ON);
		    $layer->set('template', ' ');
		    $layer->set('name','querymap'.$k);
		    $layer->set('type',$layerset['Datentyp']);
		    if (MAPSERVERVERSION < '540') {
		      $layer->set('connectiontype', 6);
		    }
		    else {
		      $layer->setConnectionType(6);
		    }
		    $layer->set('connection',$layerset['connection']);
		    $layer->setProjection('+init=epsg:'.$layerset['epsg_code']);
		    $layer->setMetaData('wms_queryable','0');
		    $klasse=ms_newClassObj($layer);
		    $klasse->set('status', MS_ON);
		    $style=ms_newStyleObj($klasse);
		    $style->color->setRGB(12,255,12);
		    if (MAPSERVERVERSION > '500') {
		    	$style->set('width', 2);
		    }
		    $style->outlinecolor->setRGB(0,0,0);
		    # Datensatz-Layer erzeugen
		    $layer=ms_newLayerObj($map);
		    if($layerset['schema'] != ''){
		    	$tablename = $layerset['schema'].'.'.$tablename;
		    }    
		    $datastring = $layerset['attributes']['the_geom']." from (select oid as id, ".$layerset['attributes']['the_geom']." from ".$tablename;
		    $datastring.=" WHERE oid = '".$oid."'";
		    $datastring.=") as foo using unique id using srid=".$layerset['epsg_code'];
		    $layer->set('data',$datastring);
		    $layer->set('status',MS_ON);
		    $layer->set('template', ' ');
		    $layer->set('name','querymap'.$k);
		    $layer->set('type',$layerset['Datentyp']);
		    if (MAPSERVERVERSION < '540') {
		      $layer->set('connectiontype', 6);
		    }
		    else {
		      $layer->setConnectionType(6);
		    }
		    $layer->set('connection',$layerset['connection']);
		    $layer->setProjection('+init=epsg:'.$layerset['epsg_code']);
		    $layer->setMetaData('wms_queryable','0');
		    $klasse=ms_newClassObj($layer);
		    $klasse->set('status', MS_ON);
		    $style=ms_newStyleObj($klasse);
		    $style->color->setRGB(255,5,12);
		    if (MAPSERVERVERSION > '500') {
		    	$style->set('width', 2);
		    }
		    $style->outlinecolor->setRGB(0,0,0);
		    # Karte rendern
		    $map->setProjection('+init=epsg:'.$this->user->rolle->epsg_code,MS_TRUE);
		    $map->web->set('imagepath', IMAGEPATH);
		    $map->web->set('imageurl', IMAGEURL);
		    $map->set('width', 50);
		    $map->set('height', 50);
		    $image_map = $map->draw();
		    $filename = $this->map_saveWebImage($image_map,'jpeg');
		    $newname = $this->user->id.basename($filename);
		    rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);    
		    return IMAGEURL.$newname;
	    }
	    else{
	    	return GRAPHICSPATH.'nogeom.png';
	    }
  	}
  }

  function queryMap() {
    # Abfragebereich berechnen
    $corners=explode(';',$this->formvars['INPUT_COORD']);
    if(count($corners) < 3){
      $lo=explode(',',$corners[0]); # linke obere Ecke in Bildkoordinaten von links oben gesehen
      $ru=explode(',',$corners[1]); # reche untere Ecke des Auswahlbereiches in Bildkoordinaten von links oben gesehen
      $width=$this->user->rolle->pixsize*($ru[0]-$lo[0]); # Breite des Auswahlbereiches in m
      $height=$this->user->rolle->pixsize*($ru[1]-$lo[1]); # Höhe des Auswahlbereiches in m
      #echo 'Abfragerechteck im Bild: '.$lo[0].' '.$lo[1].' '.$ru[0].' '.$ru[1];
      # linke obere Ecke im Koordinatensystem in m
      $minx=$this->user->rolle->oGeorefExt->minx+$this->user->rolle->pixsize*$lo[0]; # x Wert
      $miny=$this->user->rolle->oGeorefExt->miny+$this->user->rolle->pixsize*($this->user->rolle->nImageHeight-$ru[1]); # y Wert
      $maxx=$minx+$width;
      $maxy=$miny+$height;
      $rect=ms_newRectObj();
      $rect->setextent($minx,$miny,$maxx,$maxy);
    }
    else{
      $polygon = 'POLYGON((';
      for($i = 0; $i < count($corners); $i++){
        $coord = explode(',',$corners[$i]);
        $coordx[$i] = $this->user->rolle->oGeorefExt->minx+$this->user->rolle->pixsize*$coord[0];
        $coordy[$i] = $this->user->rolle->oGeorefExt->miny+$this->user->rolle->pixsize*($coord[1]);
        $polygon .= $coordx[$i].' '.$coordy[$i].',';
      }
      $polygon .= $coordx[0].' '.$coordy[0].'))';
      $rect = $polygon;
    }
    if($this->show_query_tooltip == true){
      $this->tooltip_query($rect);
    }
    else{
      $this->SachdatenAnzeige($rect);
    }
  }

  function zoomToRefExt() {
    # Zoomen auf den in der Referenckarte gesetzten Punkt
    # Berechnen der Koordinaten des angeklickten Punktes in der Referencekarte
    $refmapwidthm=($this->map->reference->extent->maxx-$this->map->reference->extent->minx);
    $refmappixsize=$refmapwidthm/$this->map->reference->width;
    $refmapxposm=$this->map->reference->extent->minx+$refmappixsize*$this->formvars['refmap_x'];
    $refmapyposm=$this->map->reference->extent->maxy-$refmappixsize*$this->formvars['refmap_y'];
    $halfmapwidthm=($this->map->extent->maxx-$this->map->extent->minx)/2;
    $halfmapheight=($this->map->extent->maxy-$this->map->extent->miny)/2;
    $zoommaxx=$refmapxposm+$halfmapwidthm;
    $zoomminx=$refmapxposm-$halfmapwidthm;
    $zoommaxy=$refmapyposm+$halfmapheight;
    $zoomminy=$refmapyposm-$halfmapheight;
    # ersetzen durch zoomPoint Funktion von mapObject.
    $this->map->setextent($zoomminx,$zoomminy,$zoommaxx,$zoommaxy);
    $oPixelPos=ms_newPointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
    $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
    $this->saveMap('');
  }


	function layer_error_handling(){
		return '<br><br>Eines der Themen ist fehlerhaft. Klicken Sie <a href="index.php?go=reset_layers">auf Neu starten</a> um alle Themen auszuschalten.';
	}

  # Zeichnet die Kartenelemente Hauptkarte, Legende, Maßstab und Referenzkarte
  # drawMap #
  function drawMap() {
    if($this->main == 'map.php' AND MINSCALE != '' AND $this->map_factor == '' AND $this->map_scaledenom < MINSCALE){
      $this->scaleMap(MINSCALE);
    }    
    $this->image_map = $this->map->draw() OR die($this->layer_error_handling());    
    $filename = $this->user->id.'_'.rand(0, 1000000).'.'.$this->map->outputformat->extension;
    $this->image_map->saveImage(IMAGEPATH.$filename);
    $this->img['hauptkarte'] = IMAGEURL.$filename;
    $this->debug->write("Name der Hauptkarte: ".$this->img['hauptkarte'],4);

    $this->legende = $this->create_dynamic_legend();
    $this->debug->write("Legende erzeugt",4);
    
    # Erstellen des Maßstabes
    $this->switchScaleUnitIfNecessary();
    $img_scalebar = $this->map->drawScaleBar();
    $filename = $this->map_saveWebImage($img_scalebar,'png');
    $newname = $this->user->id.basename($filename);
    rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
    $this->img['scalebar'] = IMAGEURL.$newname;
    $this->debug->write("Name des Scalebars: ".$this->img['scalebar'],4);
		
		$this->calculatePixelSize();
		
		$this->drawReferenceMap();
  }

  # Flurstücksauswahl
  function flurstwahl() {
    if($this->formvars['historical'] == 1){
      $this->titel='historische Flurstückssuche';
    }
    elseif($this->formvars['ALK_Suche'] == 1){
      $this->titel='ALK-Flurstückssuche';
    }
    else{
    	$this->titel='Flurstückssuche';
    }
    $this->main='flurstueckssuche.php';
    ####### Import ###########
		$_files = $_FILES;
    if($_files['importliste']['name']){
			$importliste = file($_files['importliste']['tmp_name'], FILE_IGNORE_NEW_LINES);
			$this->formvars['selFlstID'] = implode(', ', $importliste);
			$this->formvars['GemkgID'] = substr($importliste[0], 0, 6);
			$this->formvars['FlurID'] = substr($importliste[0], 7, 3);
		}
		##########################
		# Übernahme der Formularwerte für die Einstellung der Auswahlmaske
		$GemID=$this->formvars['GemID'];
		$GemkgID=$this->formvars['GemkgID'];
		$FlurID=$this->formvars['FlurID'];
		$FlstID=$this->formvars['FlstID'];
		$FlstNr=$this->formvars['FlstNr'];
		$selFlstID = explode(', ',$this->formvars['selFlstID']);
    #$this->searchInExtent=$this->formvars['searchInExtent'];
    # Abfragen für welche Gemeinden die Stelle Zugriffsrechte hat
    # GemeindenStelle wird eine Liste mit ID´s der Gemeinden zugewiesen, die zur Stelle gehören
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$this->pgdatabase);
    # Abfrage der Gemeinde Namen
    if(ALKIS)$GemListe=$Gemeinde->getGemeindeListeALKIS($GemeindenStelle,'bezeichnung');
    else $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle,'GemeindeName');
    # Abfragen der Gemarkungen zur Gemeinde
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    # Auswahl nur über die zulässigen Gemeinden
    if(ALKIS)$GemkgListe=$Gemarkung->getGemarkungListeALKIS($GemListe['ID'],'','gmk.bezeichnung');
    else $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    if (count($GemkgListe['GemkgID'])>0) {
      if (count($GemkgListe['GemkgID'])==1) { $GemkgID=$GemkgListe['GemkgID'][0]; }
      $GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    }
    else {
      $GemkgFormObj=new selectFormObject("GemkgID","text","","","","25","25","",NULL);
    }
    $GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemkgFormObj->outputHTML();
    # Wenn Gemarkung gewählt wurde, oder nur eine Gemarkung zur Wahl steht, Auswahlliste für Flur erzeugen
    if ($GemkgFormObj->selected) {
      # Abragen der Fluren zur Gemarkung
      if ($GemkgID==0) { $GemkgID=$GemkgListe['GemkgID'][0]; }
      $Flur=new Flur('','','',$this->pgdatabase);
    	if($this->formvars['ALK_Suche'] == 1){
    		if(ALKIS)$FlurListe=$Flur->getFlurListeALKIS($GemkgID,'','gemarkungsteilflur', $this->formvars['historical']);
      	else $FlurListe=$Flur->getFlurListeALK($GemkgID,'flurid', $this->formvars['historical']);
    	}
    	else{
    		if(ALKIS)$FlurListe=$Flur->getFlurListeALKIS($GemkgID,'','gemarkungsteilflur', $this->formvars['historical']);
    		else $FlurListe=$Flur->getFlurListe($GemkgID,'','FlurNr', $this->formvars['historical']);
    	}
      # Erzeugen des Formobjektes für die Flurauswahl
      if (count($FlurListe['FlurID'])==1) { $FlurID=$FlurListe['FlurID'][0]; }
      $FlurFormObj=new selectFormObject("FlurID","select",$FlurListe['FlurID'],array($FlurID),$FlurListe['Name'],"1","","",NULL);
      $FlurFormObj->insertOption(-1,0,'--Auswahl--',0);
      $FlurFormObj->outputHTML();
      # Wenn Flur gewählt wurde, oder nur eine Flur zur Auswahl steht, Auswahllist für Flurstuecke erzeugen
      if ($FlurFormObj->selected) {
        # Abfragen der Flurstücke zur Flur
        $FlstNr=new flurstueck('',$this->pgdatabase);
        # Wenn mal ALK Flächendeckend vorhanden ist, können Flurstücke auch über aktuellen Ausschnitt gewählt werden.
        # dann die nächste aktive Zeile durch die beiden nächsten auskommentierten Zeilen ersetzen
        # $FlstNrExtentListe=$FlstNr->getFlstListeByExtent($this->user->rolle->oGeorefExt);
        # $FlstNrListe=$FlstNr->getFlstListe($GemID,$GemkgID,$FlurID,$FlstNrExtentListe,'FKZ');
        if ($FlurID==0) { $FlurID=$FlurListe['FlurID'][0]; }
        if($this->formvars['ALK_Suche'] == 1){
        	if(ALKIS)$FlstNrListe=$FlstNr->getFlstListeALKIS($GemID,$GemkgID,$FlurID,'flurstueckskennzeichen', $this->formvars['historical']);
        	else $FlstNrListe=$FlstNr->getFlstListeALK($GemID,$GemkgID,$FlurID,'flurstkennz', $this->formvars['historical']);
        }
        else{
        	if(ALKIS)$FlstNrListe=$FlstNr->getFlstListeALKIS($GemID,$GemkgID,$FlurID,'flurstueckskennzeichen', $this->formvars['historical']);
        	else $FlstNrListe=$FlstNr->getFlstListe($GemID,$GemkgID,$FlurID,'flurstkennz', $this->formvars['historical']);
        }
        # Erzeugen des Formobjektes für die Flurstücksauswahl
        if (count($FlstNrListe['FlstID'])==1){
          $FLstID=$FlstNrListe['FlstID'][0];
          $FlstID = array($FLstID);
        }
        $FlstNrFormObj=new FormObject("FlstID","select",$FlstNrListe['FlstID'],array($FlstID),$FlstNrListe['FlstNr'],"12","","multiple",100);
        $FlstNrFormObj->outputHTML();
        if($this->formvars['selFlstID'] != ''){
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select", $selFlstID, NULL, $selFlstID,"12","","multiple",170);
        }
        else{
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select",NULL,NULL,"","12","","multiple",170);
        }
        $SelectedFlstNrFormObj->outputHTML();
      }
      else {
        if($this->formvars['selFlstID'] != ''){
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select", $selFlstID, NULL, $selFlstID,"12","","multiple",100);
          $SelectedFlstNrFormObj->outputHTML();
        }
        else{
          $FlstNrFormObj=new FormObject("FlstNr","text","","","","5","5","multiple",NULL);
        }
      }
    }
    else {
      $FlurFormObj=new FormObject("FlurID","text","","","","25","25","multiple",NULL);
      $FlstNrFormObj=new FormObject("FlstNr","text","","","","5","5","multiple",NULL);
    }
    $this->FormObject["Gemeinden"]=$GemFormObj;
    $this->FormObject["Gemarkungen"]=$GemkgFormObj;
    $this->FormObject["GemkgSchl"]=$GemkgSchlFormObj;
    $this->FormObject["Fluren"]=$FlurFormObj;
    $this->FormObject["FlstNr"]=$FlstNrFormObj;
    $this->FormObject["selectedFlstNr"]=$SelectedFlstNrFormObj;
  }

  # adressenauswahl
  function adresswahl() {
    $Adresse=new adresse('','','',$this->pgdatabase);
    $this->main='adresssuche.php';
    if($this->formvars['ALK_Suche'] == 1){
    	$this->titel='ALK-Adressensuche';
    }
    else{
    	$this->titel='Adressensuche';
    }
    if ($this->formvars['aktualisieren']=='Neu') {
      $GemID=0; $StrID=0; $StrName=''; $HausID=0; $HausNr='';
    }
    else {
      $GemID=$this->formvars['GemID'];
      $GemkgID=$this->formvars['GemkgID'];
      $StrID=$this->formvars['StrID'];
      $StrName=$this->formvars['StrName'];
      if ($StrName!='') {
        $StrID=$Adresse->getStrIDfromName($GemID,$StrName);
      }
      $HausID=$this->formvars['HausID'];
      $HausNr=$this->formvars['HausNr'];
      $selHausID = explode(', ',$this->formvars['selHausID']);
    }
    $Gemeinde=new gemeinde('',$this->pgdatabase);
    # 2006-01-02 pk
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    if(ALKIS)$GemListe=$Gemeinde->getGemeindeListeALKIS($GemeindenStelle, 'Name');
    else $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'Name');
    # Wenn nur eine Gemeinde zur Auswahl steht, wird diese gewählt
    # Verhalten so, als würde die Gemeinde vorher gewählt worden sein.
    if (count($GemListe['ID'])==1) {
      $GemID=$GemListe['ID'][0];
    }
    
    # Abfragen der Gemarkungen zur Gemeinde
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    if(ALKIS)$GemkgListe=$Gemarkung->getGemarkungListeALKIS($GemListe['ID'],'','gmk.bezeichnung');
    else $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    if (count($GemkgListe['GemkgID'])==1) { $GemkgID=$GemkgListe['GemkgID'][0]; }
    $GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    $GemkgFormObj->addJavaScript('onclick', 'document.GUI.GemID.disabled = true');
    $GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemkgFormObj->outputHTML();
    
    // Sortieren der Gemeinden unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemListe['Name'], $GemListe['ID']);
    $GemListe['Name'] = $sorted_arrays['array'];
    $GemListe['ID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemeindeauswahl
    $GemFormObj=new selectFormObject("GemID","select",$GemListe['ID'],array($GemID),$GemListe['Name'],"1","","",NULL);
    $GemFormObj->addJavaScript('onclick', 'document.GUI.GemkgID.disabled = true');
    $GemFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemFormObj->outputHTML();
    # Wenn Gemeinde gewählt wurde, oder nur eine zur Auswahl stand, Auswahlliste für Strassen erzeugen
    if ($GemFormObj->selected OR $GemkgFormObj->selected){
    	if($GemFormObj->selected)$StrassenListe=$Adresse->getStrassenListe($GemID,'','StrassenName');
    	elseif($GemkgFormObj->selected)$StrassenListe=$Adresse->getStrassenListeByGemkg($GemkgID,'','StrassenName');
      $StrSelected[0]=$StrID;
      # Erzeugen des Formobjektes für die Strassenauswahl
      $StrFormObj=new selectFormObject("StrID","select",$StrassenListe['StrID'],$StrSelected,$StrassenListe['Name'],"1","","",NULL);
      # Unterscheidung ob Strasse ausgewählt wurde
      if ($StrFormObj->selected){
      	if($GemID == -1){
		    	if(ALKIS)$Gemeinde = $Gemarkung->getGemarkungListeALKIS(NULL, array($this->formvars['GemkgID']), NULL);
		    	else $Gemeinde = $Gemarkung->getGemarkungListe(NULL, array($this->formvars['GemkgID']), NULL);
		    	$GemID = $Gemeinde['gemeinde'][0];
		    }
        $HausNrListe=$Adresse->getHausNrListe($GemID,$StrID,'','','hausnr*1,ASCII(REVERSE(hausnr)),quelle');
        # Erzeugen des Formobjektes für die Flurstücksauswahl
        if (count($HausNrListe['HausID'])==1){
          $HausID=$HausNrListe['HausID'][0];
          $HausID = array($HausID);
        }
        $HausNrFormObj=new FormObject("HausID","select",$HausNrListe['HausID'],array($HausID),$HausNrListe['HausNr'],"12","","multiple",100);
        $HausNrFormObj->outputHTML();
        if($this->formvars['selHausID'] != ''){
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select", $selHausID, NULL, $selHausID,"12","","multiple",170);
        }
        else{
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select",NULL,NULL,"","12","","multiple",170);
        }
        $SelectedHausNrFormObj->outputHTML();
      }
      
    	else {
        if($this->formvars['selHausID'] != ''){
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select", $selHausID, NULL, $selHausID,"12","","multiple",100);
          $SelectedHausNrFormObj->outputHTML();
        }
        else{
          $HausNrFormObj=new FormObject("HausNr","text","","","","5","5","multiple",NULL);
        }
      }
    }
    else {
      # Es wurde noch keine Gemeinde ausgewählt, Strasse und Hausnummer als Textfelder
      $StrFormObj=new selectFormObject("StrName","text","","","","25","25","",NULL);
      $HausNrFormObj=new selectFormObject("HausNr","text","","","","5","5","",NULL);
    }
    $this->FormObject["Gemeinden"]=$GemFormObj;
    $this->FormObject["Gemarkungen"]=$GemkgFormObj;
    $this->FormObject["Strassen"]=$StrFormObj;
    $this->FormObject["HausNr"]=$HausNrFormObj;
    $this->FormObject["selectedHausNr"]=$SelectedHausNrFormObj;
  }

  function adresseSuchen() {
    # 2006-01-31 pk
    #echo 'GemeindeID'.$this->formvars['GemID'];
    #echo '<br>StrasseID'.$this->formvars['StrID'];
    #echo '<br>HausID'.$this->formvars['selHausID'];
    $GemID=$this->formvars['GemID'];
    if($GemID == -1){
    	$Gemarkung=new gemarkung('',$this->pgdatabase);
    	if(ALKIS)$Gemeinde = $Gemarkung->getGemarkungListeALKIS(NULL, array($this->formvars['GemkgID']), NULL);
    	else $Gemeinde = $Gemarkung->getGemarkungListe(NULL, array($this->formvars['GemkgID']), NULL);
    	$GemID = $Gemeinde['gemeinde'][0];
    }
    if ($GemID!='-1') {
      $Adresse=new adresse($GemID,'','',$this->pgdatabase);
      $StrID=$this->formvars['StrID'];
      $StrName=$this->formvars['StrName'];
      if($StrName!='') {
        $StrID=$Adresse->getStrIDfromName($GemID,$StrName);
      }
    	else{
        $StrName=$Adresse->getStrNamefromID($GemID,$StrID);
      }
      $Adresse->StrassenSchl=$StrID;
      $HausID=$this->formvars['selHausID'];
      $HausNr=$this->formvars['HausNr'];
      if ($HausNr!='') {
        $HausID=$HausNr;
      }
      if ($HausID=='-1') {
        $HausID='';
      }
      $Adresse->HausNr=$HausID;
      # $this->searchInExtent=$this->formvars['searchInExtent'];
      # Wenn keine Strasse angegeben ist zoom auf die ganze Gemeinde
      if ($StrID<'1') {
        $this->loadMap('DataBase');
        $this->zoomToALKGemeinde($GemID,10);
        $currenttime=date('Y-m-d H:i:s',time());
        $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
        $this->drawMap();
        $this->saveMap('');
      }
      else {
        # StrassenID ist angegeben
        # Abfrage der Flurstücks aus dem ALB über die Adresse
        $FlurstKennz=$Adresse->getFlurstKennzListe();
        if($this->formvars['ALK_Suche'] == 1){
        	$this->loadMap('DataBase');
	        $ret = $this->zoomToALKGebaeude($GemID,$StrID,$StrName,$HausID,100);
	        if($ret[0]){
	        	$this->zoomToALKFlurst($FlurstKennz,100);
	        }
					if($this->formvars['go_next'] != '')header('location: index.php?go='.$this->formvars['go_next']);
	        $currenttime=date('Y-m-d H:i:s',time());
          $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
          $this->drawMap();
          $this->saveMap('');
        }
        else{
	        if ($FlurstKennz > 0) {
	          # Anzeige der ALB-daten in Flurstücksanzeige
	          $this->flurstAnzeige($FlurstKennz);
	        }
	        else {
	          # Anzeige der Gebaeude in der ALK
	          # Karte laden, auf die Gebaeude zoomen, Karte Zeichnen und speichern für späteren gebrauch
	          $this->loadMap('DataBase');
	          $this->zoomToALKGebaeude($GemID,$StrID,$StrName,$HausID,100);
	          $currenttime=date('Y-m-d H:i:s',time());
	          $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
	          $this->drawMap();
	          $this->saveMap('');
	        }
        }
      }
    }
    else {
      $this->Fehlermeldung='Wählen Sie eine Gemeinde aus!';
      $this->adresswahl();
    }
  }

} # end of class GUI


########################################################
# Klasse zum dynamischen Erzeugen von Formularobjekten #
########################################################
# Klasse FormObject #
#####################

class FormObject {
  var $type;
  var $html;
  var $selected;
  var $select;
  var $hidden;
  var $text;
  var $anzValues;

  ###################### Liste der Funktionen ####################################
  #
  # function FormObject($name,$type,$value,$selectedValue,$label,$size,$maxlenght,$multiple) - Construktor
  # function addOption($value,$selected,$label)
  # function changeSize($size)
  # function insertOption($value,$selected,$label,$insertafter)
  # function outputHTML()
  #
  ################################################################################

  function FormObject($name,$type,$value,$selectedValue,$label,$size,$maxlenght,$multiple, $width) {
    if (!is_array($selectedValue)) { $selectedValue=array($selectedValue); }
    $this->type=$type;
    $this->width=$width;
    switch ($type) {
      case "select" : {
        if($value){
          $this->AnzValues=count($value);
        }
        $this->select['name']=$name;
        if ($size=='Anzahl Werte') {
          $this->select['size']=$this->AnzValues;
        }
        else {
          $this->select['size']=$size;
        }
        $this->select['multiple']=$multiple;
        for ($i=0;$i<$this->AnzValues;$i++) {
          $this->select['option'][$i]['value']=$value[$i];
          for ($j=0;$j<count($selectedValue);$j++) {
            if ($selectedValue[$j]==$value[$i]) {
              $this->selected=1;
              $this->select['option'][$i]['selected']=1;
            }
          }
          $this->select['option'][$i]['label']=$label[$i];
        }
      } break;
      case "text" : {
        $this->text['name']=$name;
        $this->text['value']=$value[0];
        $this->text['size']=$size;
        $this->text['maxlength']=$maxlength;
      } break;
      default : { # type hidden
        $this->hidden['name']=$name;
        $this->hidden['value']=$value[0];
      }
    } # ende switch type
    $this->outputHTML();
  } # ende constructor

  function addJavaScript($event,$script){
    $this->JavaScript=$event.'="'.$script.'"';
  }

  function addOption($value,$selected,$label) {
    $anzOption=count($this->select['option']);
    $this->select[option][$anzOption]['value']=$value;
    $this->select[option][$anzOption]['selected']=$selected;
    $this->select[option][$anzOption]['label']=$label;
  }

  function insertOption($value,$selected,$label,$insertafter) {
    # insertafter ist die Nummer der Option, nach der die neue Option eingefügt werden soll
    # die Zählung beginnt mit 1. Wenn z.B. eine Option an den Anfang gestellt werden soll
    # muss insertafter = 0 sein.
    $anzOption=count($this->select['option']);
    $oldvalue=$value;
    $oldselected=$selected;
    $oldlabel=$label;
    for($i=$insertafter;$i<$anzOption;$i++) {
      $tmpvalue=$this->select['option'][$i]['value'];
      $tmpselected=$this->select['option'][$i]['selected'];
      $tmplabel=$this->select['option'][$i]['label'];
      $this->select['option'][$i]['value']=$oldvalue;
      $this->select['option'][$i]['selected']=$oldselected;
      $this->select['option'][$i]['label']=$oldlabel;
      $oldvalue=$tmpvalue;
      $oldselected=$tmpselected;
      $oldlabel=$tmplabel;
    }
    $this->select['option'][$anzOption]['value']=$oldvalue;
    $this->select['option'][$anzOption]['selected']=$oldselected;
    $this->select['option'][$anzOption]['label']=$oldlabel;
  }

  function changeSize($size) {
    switch ($this->type) {
      case 'select' : {
        $this->select['size']=$size;
      } break;
      case 'text' : {
        $this->text['size']=$size;
      } break;
    }
  }

  function outputHTML() {
    #2005-11-29_pk
    switch ($this->type) {
      case "select" : {
        $this->html ="<select name='".$this->select["name"]."' size='".$this->select["size"]."' ";
        if($this->width > 0){
          $this->html.="style='width:".$this->width."px'";
        }
        if ($this->select["multiple"]) {
          $this->html.=" multiple";
        }
        if ($this->JavaScript!='') {
          $this->html.=$this->JavaScript;
        }
        $this->html.=">\n";
        for ($i=0;$i<count($this->select[option]);$i++) {
          $this->html.="<option value='".$this->select["option"][$i]["value"]."'";
          if ($this->select["option"][$i]["selected"]) {
            $this->html.=" selected";
          }
          $this->html.=">".$this->select["option"][$i]["label"]."</option>\n";
        }
        $this->html.="</select>";
      } break;
      case "text" : {
        $this->html ="<input type='text' name='".$this->text["name"]."' value='".$this->text["value"]."'";
        $this->html.=" size='".$this->text["size"]."' maxlength='".$this->text["size"]."'>";
      } break;
      case "hidden" : {
        $this->html ="<input type='hidden' name='".$this->hidden["name"]."' value='".$this->hidden["value"]."'";
      }
    }
  }
} # end of Classe FormObject


##########################################################################################
# Classe zum dynamischen Erzeugen von Formularobjekten mit automatischem Abschicken nach #
# des Formulars nach Änderung der Auswahl über                                           #
# Java Script Funktionen (onchange='...' Erweiterung von Classe FormObject               #
##########################################################################################
# Klasse selectFormObject #
###########################

class selectFormObject extends FormObject{

  ###################### Liste der Funktionen ####################################
  #
  # function outputHTML()
  #
  ################################################################################

  function outputHTML() {
    $this->onchange=$onchange;
    switch ($this->type) {
      case 'select' : {
        $this->html ="<select name=\"".$this->select['name']."\" size=\"".$this->select['size']."\"";
        if ($this->select['multiple']) {
          $this->html.=' multiple';
        }
        if ($this->JavaScript!='') {
          $this->html.=$this->JavaScript;
        }
        if($this->nochange != true){
          $this->html.=" onchange=\"document.GUI.submit()\">\n";
        }
        for ($i=0;$i<count($this->select[option]);$i++) {
          $this->html.="<option value=\"".$this->select['option'][$i]['value']."\"";
          if ($this->select['option'][$i]['selected']) {
            $this->html.=' selected';
          }
          $this->html.=">".$this->select['option'][$i]['label']."</option>\n";
        }
        $this->html.="</select>\n";
      } break;

      case 'text' : {
        $this->html ='<input type="text" name="'.$this->text['name'].'" value="'.$this->text['value'].'"';
        $this->html.=' size="'.$this->text['size'].'" maxlength="'.$this->text['size'].'">';
      } break;

      case 'hidden' : {
        $this->html ='<input type="hidden" name="'.$this->hidden['name'].'" value="'.$this->hidden['value'].'"';
      }
    }
  }
}


##############################################################
# Klasse MapObject zum laden der Map-Daten aus der Datenbank #
##############################################################
# Klasse db_mapObj #
####################

class db_mapObj extends db_mapObj_core{
  var $debug;
  var $referenceMap;
  var $Layer;
  var $anzLayer;
  var $nurAufgeklappteLayer;
  var $Stelle_ID;
  var $User_ID;

  ###################### Liste der Funktionen ####################################
  #
  # deleteFilter($stelle_id, $layer_id, $attributname)
	# function db_mapObj($Stelle_ID,$User_ID) - Construktor
	# function read_ReferenceMap()
  # function read_Classes($Layer_ID)
  # function read_Styles($Style_ID)
  # function read_Label($Label_ID)
  # function getShapeByAttribute($layer,$attribut,$value)
  # function getMaxMapExtent()
  # function outputHTML()
	# zoomToDatasets($oids, $tablename, $columnname, $border, $layerdb, $client_epsg)
  #
  ################################################################################

  function db_mapObj($Stelle_ID,$User_ID) {
    global $debug;
    $this->debug=$debug;
    $this->Stelle_ID=$Stelle_ID;
    $this->User_ID=$User_ID;
  }

	function zoomToDatasets($oids, $tablename, $columnname, $border, $layerdb, $client_epsg) {
  	$sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
  	$sql.=" FROM (SELECT box2D(st_transform(st_union(".$columnname."), ".$client_epsg.")) as bbox";
  	$sql.=" FROM ".$tablename." WHERE oid IN (";
  	for($i = 0; $i < count($oids); $i++){
    	$sql .= "'".$oids[$i]."',";
    }
    $sql = substr($sql, 0, -1);
		$sql.=")) AS foo";
    $ret = $layerdb->execSQL($sql, 4, 0);
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

  function deleteFilter($stelle_id, $layer_id, $attributname){
    $sql = 'DELETE FROM u_attributfilter2used_layer WHERE Stelle_ID = '.$stelle_id.' AND Layer_ID = '.$layer_id.' AND attributname = "'.$attributname.'"';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteFilter - Löschen eines Attribut-Filters eines used_layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

  function writeFilter($database, $filter, $layer, $stelle){
    if($filter != ''){
      $layerdata = $this->get_Layer($layer);
      $filterstring = '(1 = 1';
      for($i = 0; $i < count($filter); $i++){
        if($filter[$i]['type'] == 'geometry'){
          $poly_geom = $database->getpolygon($filter[$i]['attributvalue'], $layerdata['epsg_code']);
          $filterstring .= ' AND '.$filter[$i]['attributname'].' && \''.$poly_geom.'\'';
          $filterstring .= ' AND '.$filter[$i]['operator'].'('.$filter[$i]['attributname'].',\''.$poly_geom.'\')';
        }
        else{
          if($filter[$i]['operator'] == 'IS'){
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' '.$filter[$i]['attributvalue'];
          }
          elseif($filter[$i]['operator'] == 'IN'){
            if($filter[$i]['type'] == 'varchar' OR $filter[$i]['type'] == 'text'){
              $values = explode(',', $filter[$i]['attributvalue']);
              $filter[$i]['attributvalue'] = "'".implode("','", $values)."'";
            }
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' ('.$filter[$i]['attributvalue'].')';
          }
          else{
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' \''.$filter[$i]['attributvalue'].'\'';
          }
        }
      }
      $filterstring .= ')';
    }
    $sql = 'UPDATE used_layer SET Filter = "'.$filterstring.'" WHERE Stelle_ID = '.$stelle.' AND Layer_ID = '.$layer;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->writeFilter - Speichern des Filterstrings:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

  function checkPolygon($poly_id){
    $sql = 'SELECT * FROM u_attributfilter2used_layer WHERE attributvalue = "'.$poly_id.'" AND type = "geometry"';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->checkPolygon - Testen ob Polygon_id noch in einem Filter benutzt wird:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    if($rs == NULL){
      return false;
    }
    else{
      return true;
    }
  }

  function getPolygonID($stelle_id,$layer_id) {
    $sql = 'SELECT attributvalue AS id FROM u_attributfilter2used_layer';
    $sql.= ' WHERE stelle_id = "'.$stelle_id.'" AND layer_id = "'.$layer_id.'" AND type = "geometry"';
    #echo $sql;
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $ret=mysql_fetch_row($query);
    $poly_id = $ret[0];
    return $poly_id;
  }

  function saveAttributeFilter($formvars){
    if(MYSQLVERSION > 410){
      $sql = 'INSERT INTO u_attributfilter2used_layer SET';
      $sql .= ' attributname = "'.$formvars['attributname'].'",';
      $sql .= ' attributvalue = "'.$formvars['attributvalue'].'",';
      $sql .= ' operator = "'.$formvars['operator'].'",';
      $sql .= ' type = "'.$formvars['type'].'",';
      $sql .= ' Stelle_ID = '.$formvars['stelle'].',';
      $sql .= ' Layer_ID = '.$formvars['layer'];
      $sql .= ' ON DUPLICATE KEY UPDATE  attributvalue = "'.$formvars['attributvalue'].'", operator = "'.$formvars['operator'].'"';
    }
    else{
      $sql = 'REPLACE INTO u_attributfilter2used_layer SET';
      $sql .= ' attributname = "'.$formvars['attributname'].'",';
      $sql .= ' attributvalue = "'.$formvars['attributvalue'].'",';
      $sql .= ' operator = "'.$formvars['operator'].'",';
      $sql .= ' type = "'.$formvars['type'].'",';
      $sql .= ' Stelle_ID = '.$formvars['stelle'].',';
      $sql .= ' Layer_ID = '.$formvars['layer'];
    }
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->saveAttributeFilter - Speichern der Attribute-Filter-Parameter:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

  function readAttributeFilter($Stelle_ID, $Layer_ID){
    $sql ='SELECT * FROM u_attributfilter2used_layer WHERE Stelle_ID = '.$Stelle_ID.' AND Layer_ID = '.$Layer_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->readAttributeFilter - Lesen der Attribute-Filter-Parameter:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = mysql_fetch_array($query)){
      $filter[] = $rs;
    }
    return $filter;
  }

	function getFilter($layer_id, $stelle_id){
    $sql ='SELECT Filter FROM used_layer WHERE Layer_ID = '.$layer_id.' AND Stelle_ID = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getFilter - Lesen des Filter-Statements des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $filter = $rs[0];
    return $filter;
  }

  function getData($layer_id){
  	if($layer_id < 0){	# Rollenlayer
  		$sql ='SELECT Data FROM rollenlayer WHERE -id = '.$layer_id;
  	}
  	else{
    	$sql ='SELECT Data FROM layer WHERE Layer_ID = '.$layer_id;
  	}
  	#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getData - Lesen des Data-Statements des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $data = $rs[0];
    return $data;
  }

  function getPath($layer_id){
    $sql ='SELECT Pfad FROM layer WHERE Layer_ID = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getPath - Lesen des Path-Statements des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $pfad = $rs[0];
    return $pfad;
  }
  
  function getDocument_Path($layer_id){
    $sql ='SELECT document_path FROM layer WHERE Layer_ID = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getDocument_Path - Lesen des Document_Path des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $doc_path = $rs[0];
    if($doc_path == '')$doc_path = CUSTOM_IMAGE_PATH;
    return $doc_path;
  }

  function getlayerdatabase($layer_id, $host){
  	if($layer_id < 0){	# Rollenlayer
  		$sql ='SELECT `connection`, "" as `schema` FROM rollenlayer WHERE -id = '.$layer_id.' AND connectiontype = 6';
  	}
  	else{
    	$sql ='SELECT `connection`, `schema` FROM layer WHERE Layer_ID = '.$layer_id.' AND connectiontype = 6';
  	}
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getlayerdatabase - Lesen des connection-Strings des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $connectionstring = $rs[0];
    if($connectionstring != ''){
      $layerdb = new pgdatabase();
      if($rs[1] == ''){
      	$rs[1] = 'public';
      }
      $layerdb->schema = $rs[1];
      $connection = explode(' ', trim($connectionstring));
      for($j = 0; $j < count($connection); $j++){
        if($connection[$j] != ''){
          $value = explode('=', $connection[$j]);
          if(strtolower($value[0]) == 'user'){
            $layerdb->user = $value[1];
          }
          if(strtolower($value[0]) == 'dbname'){
            $layerdb->dbName = $value[1];
          }
          if(strtolower($value[0]) == 'password'){
            $layerdb->passwd = $value[1];
          }
          if(strtolower($value[0]) == 'host'){
            $layerdb->host = $value[1];
          }
          if(strtolower($value[0]) == 'port'){
            $layerdb->port = $value[1];
          }
        }
      }
      if (!isset($layerdb->host)) {
        $layerdb->host = $host;
      }
      if (!$layerdb->open()) {
        echo 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden:';
        echo '<br>Host: '.$layerdb->host;
        echo '<br>User: '.$layerdb->user;
        echo '<br>Datenbankname: '.$layerdb->dbName;
        exit;
      }
    }
    return $layerdb;
  }

  function getSelectFromData($data){
    if(strpos($data, '(') === false){
      $from = stristr($data, ' from ');
      $fooposition = strpos($from, 'as foo');
      if($fooposition > 0){
        $from = substr($from, 0, $fooposition);
      }
      $select = 'select * '.$from.' where 1=1';
    }
    else{
      $select = stristr($data,'(');
      $select = trim($select, '(');
      $select = substr($select, 0, strrpos($select, ')'));
      if(strpos($select, 'select') != false){
        $select = stristr($select, 'select');
      }
    }
    return $select;
  }

  function getDataAttributes($database, $layer_id){
    $data = $this->getData($layer_id);
    if($data != ''){
      $select = $this->getSelectFromData($data);
      if($database->schema != ''){
      	$select = str_replace($database->schema.'.', '', $select);	
      }
      $attribute = $database->getFieldsfromSelect($select);
      return $attribute;
    }
    else{
      echo 'Das Data-Feld des Layers mit der Layer-ID '.$layer_id.' ist leer.';
      return NULL;
    }
  }

  function getPathAttributes($database, $path){
    if($path != ''){
      $attribute = $database->getFieldsfromSelect($path);
      return $attribute;
    }
  }

  function add_attribute_values($attributes, $database, $query_result, $withvalues = true){
    # Diese Funktion fügt den Attributen je nach Attributtyp zusätzliche Werte hinzu. Z.B. bei Auswahlfeldern die Auswahlmöglichkeiten.
    for($i = 0; $i < count($attributes['name']); $i++){
      if($attributes['constraints'][$i] != '' AND $attributes['constraints'][$i] != 'PRIMARY KEY'){  # das sind die Auswahlmöglichkeiten, die durch die Tabellendefinition in Postgres fest vorgegeben sind
      	$attributes['enum_value'][$i] = explode(',', str_replace("'", "", $attributes['constraints'][$i]));
      	$attributes['enum_output'][$i] = $attributes['enum_value'][$i];
      }
      if($withvalues == true){
        switch($attributes['form_element_type'][$i]){
          # Auswahlfelder
          case 'Auswahlfeld' : {
            if($attributes['options'][$i] != ''){     # das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
              if(strpos($attributes['options'][$i], "'") === 0){      # Aufzählung wie 'wert1','wert2','wert3'
                $attributes['enum_value'][$i] = explode(',', str_replace("'", "", $attributes['options'][$i]));
                $attributes['enum_output'][$i] = $attributes['enum_value'][$i];
              }
              elseif(strpos(strtolower($attributes['options'][$i]), "select") === 0){     # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
                $optionen = explode(';', $attributes['options'][$i]);  # SQL; weitere Optionen
                $attributes['options'][$i] = $optionen[0]; 
                # ------<required by>------
                $req_by_start = strpos(strtolower($attributes['options'][$i]), "<required by>");
                if($req_by_start > 0){
                  $req_by_end = strpos(strtolower($attributes['options'][$i]), "</required by>");
                  $req_by = trim(substr($attributes['options'][$i], $req_by_start+13, $req_by_end-$req_by_start-13));
                  $attributes['req_by'][$i] = $req_by;    # das abhängige Attribut
                  $attributes['options'][$i] = substr($attributes['options'][$i], 0, $req_by_start);    # required-Tag aus SQL entfernen
                }
                # ------<required by>------
                # -----<requires>------
                $req_start = strpos(strtolower($attributes['options'][$i]), "<requires>");
                if($req_start > 0){
                  $req_end = strpos(strtolower($attributes['options'][$i]), "</requires>");                  
    							$sql_rest = substr($attributes['options'][$i], $req_end+11);
                  $req = trim(substr($attributes['options'][$i], $req_start+10, $req_end-$req_start-10));
                  $attributes['req'][$i] = $req;    # das Attribut von dem dieses Attribut abhängig ist
                  if($query_result != NULL){
                    $options = $attributes['options'][$i];
                    for($k = 0; $k < count($query_result); $k++){
                      if($query_result[$k][$req] != ''){
                        $attributes['dependent_options'][$i][$k] = substr($options, 0, $req_start)."'".$query_result[$k][$req]."' ".$sql_rest;    # requires-Tag aus SQL entfernen und ein Array erzeugen, welches die korrekten SQLs jedem Datensatz zuordnet
                      }
                      else{
                        $attributes['dependent_options'][$i][$k] = '';    # wenn in diesem Datensatz des Query-Results das benötigte Attribut keinen Wert hat, sind die abhängigen Optionen für diesen Datensatz leer
                      }
                    }
                  }
                  else{
                    $attributes['options'][$i] = '';      # wenn kein Query-Result übergeben wurde, sind die Optionen leer
                  }
                }
                # -----<requires>------
                if(is_array($attributes['dependent_options'][$i])){   # mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
                  for($k = 0; $k < count($query_result); $k++){
                    $sql = $attributes['dependent_options'][$i][$k];
                    if($sql != ''){
                      $ret=$database->execSQL($sql,4,0);
                      if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
                      while($rs = pg_fetch_array($ret[1])){
                        $attributes['enum_value'][$i][$k][] = $rs['value'];
                        $attributes[$attributes['name'][$i]]['enum_value'][$k][] = $rs['value'];
                        $attributes['enum_output'][$i][$k][] = $rs['output'];
                      }
                    }
                  }
                }
                elseif($attributes['options'][$i] != ''){
                  $sql = $attributes['options'][$i];
                  $ret=$database->execSQL($sql,4,0);
                  if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
                  while($rs = pg_fetch_array($ret[1])){
                    $attributes['enum_value'][$i][] = $rs['value'];
                    $attributes[$attributes['name'][$i]]['enum_value'][] = $rs['value'];
                    $attributes['enum_output'][$i][] = $rs['output'];
                  }
                }
                if($optionen[1] != ''){   
                  $further_options = explode(' ', $optionen[1]);      # die weiteren Optionen exploden (opt1 opt2 opt3)
                  for($k = 0; $k < count($further_options); $k++){
                    if(strpos($further_options[$k], 'layer_id') !== false){     #layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
                      $attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
                      $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
                      $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
                    }
                    elseif($further_options[$k] == 'embedded'){       # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }
                  }
                }
              }
            }
          }break;
  
          # SubFormulare mit Primärschlüssel(n)
          case 'SubFormPK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,pkey1,pkey2,pkey3...; weitere optionen
              $subform = explode(',', $options[0]);  
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform); $k++){
                $attributes['subform_pkeys'][$i][] = $subform[$k];
              }
              if($options[1] != ''){
                if($options[1] == 'no_new_window'){
                  $attributes['no_new_window'][$i] = true;
                }
              }
            }
          }break;
  
          # SubFormulare mit Fremdschlüssel
          case 'SubFormFK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,fkey1,fkey2,fkey3...; weitere optionen
              $subform = explode(',', $options[0]);  
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform); $k++){
                $attributes['subform_fkeys'][$i][] = $subform[$k];
                $attributes['invisible'][$subform[$k]] = 'true';
              }
              if($options[1] != ''){
                if($options[1] == 'no_new_window'){
                  $attributes['no_new_window'][$i] = true;
                }
              }
            }
          }break;
          
          # eingebettete SubFormulare mit Primärschlüssel(n)
          case 'SubFormEmbeddedPK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,pkey1,pkey2,preview_attribute; weitere Optionen
              $subform = explode(',', $options[0]);  
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform)-1; $k++){
                $attributes['subform_pkeys'][$i][] = $subform[$k];
              }
              $attributes['preview_attribute'][$i] = $subform[$k];
              if($options[1] != ''){
                $further_options = explode(' ', $options[1]);     # die weiteren Optionen exploden (opt1 opt2 opt3)
                for($k = 0; $k < count($further_options); $k++){
                  switch ($further_options[$k]){
                    case 'no_new_window': {
                      $attributes['no_new_window'][$i] = true;
                    }break;
                    case 'embedded': {                            # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }break;
                  }
                }
              }
            }
          }break;
        }
      }
    }
    return $attributes;
  }

  function load_attributes($database, $path){
    # Attributname und Typ aus Pfad-Statement auslesen:
    $attributes = $this->getPathAttributes($database, $path);
    return $attributes;
  }
  
  function save_postgis_attributes($layer_id, $attributes, $maintable){
    	for($i = 0; $i < count($attributes['name']); $i++){
    		$sql = "INSERT INTO layer_attributes SET ";
  	  	$sql.= "layer_id = ".$layer_id.", ";
  	  	$sql.= "name = '".$attributes['name'][$i]."', ";
  	  	$sql.= "real_name = '".$attributes['real_name'][$attributes['name'][$i]]."', ";
  	  	$sql.= "tablename = '".$attributes['table_name'][$i]."', ";
  	  	$sql.= "table_alias_name = '".$attributes['table_alias_name'][$attributes['name'][$i]]."', ";
  	  	$sql.= "type = '".$attributes['type'][$i]."', ";
  	  	$sql.= "geometrytype = '".$attributes['geomtype'][$attributes['name'][$i]]."', ";
  	  	$sql.= "constraints = '".addslashes($attributes['constraints'][$i])."', ";
  	  	$sql.= "nullable = ".$attributes['nullable'][$i].", ";
  	  	$sql.= "length = ".$attributes['length'][$i].", ";
  	  	$sql.= "decimal_length = ".$attributes['decimal_length'][$i].", ";
  	  	$sql.= "`default` = '".addslashes($attributes['default'][$i])."', ";
  	  	$sql.= "`order` = ".$i;
  	  	$sql.= " ON DUPLICATE KEY UPDATE ";
  	  	$sql.= "real_name = '".$attributes['real_name'][$attributes['name'][$i]]."', ";
  	  	$sql.= "tablename = '".$attributes['table_name'][$i]."', ";
  	  	$sql.= "table_alias_name = '".$attributes['table_alias_name'][$attributes['name'][$i]]."', ";
  	  	$sql.= "type = '".$attributes['type'][$i]."', ";
  	  	$sql.= "geometrytype = '".$attributes['geomtype'][$attributes['name'][$i]]."', ";
  	  	$sql.= "constraints = '".addslashes($attributes['constraints'][$i])."', ";
  	  	$sql.= "nullable = ".$attributes['nullable'][$i].", ";
  	  	$sql.= "length = ".$attributes['length'][$i].", ";
  	  	$sql.= "decimal_length = ".$attributes['decimal_length'][$i].", ";
  	  	$sql.= "`default` = '".addslashes($attributes['default'][$i])."', ";
  	  	$sql.= "`order` = ".$i;
  	  	$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>".$sql,4);
  	    $query=mysql_query($sql);
  	    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    	}
    	
    	if($maintable == ''){
    		$sql = "UPDATE layer SET maintable = '".$attributes['all_table_names'][0]."' WHERE (maintable IS NULL OR maintable = '') AND Layer_ID = ".$layer_id;
    		$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>".$sql,4);
  	    $query=mysql_query($sql);
  	    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    	}
    	
    	# den PRIMARY KEY constraint rausnehmen, falls der tablename nicht der maintable entspricht
    	$sql = "UPDATE layer_attributes, layer SET constraints = '' WHERE layer_attributes.layer_id = ".$layer_id." AND layer.Layer_ID = ".$layer_id." AND constraints = 'PRIMARY KEY' AND tablename != maintable";
    	$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>".$sql,4);
  	  $query=mysql_query($sql);
  	  if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }	
  }
  
  function delete_old_attributes($layer_id, $attributes){
  	$sql = "DELETE FROM layer_attributes WHERE layer_id = ".$layer_id;
  	if($attributes){
  		$sql.= " AND name NOT IN (";
	  	for($i = 0; $i < count($attributes['name']); $i++){
	  		$sql .= "'".$attributes['name'][$i]."',";
	  	}
	  	$sql = substr($sql, 0, -1);
	  	$sql .=")";
  	}
  	#echo $sql.'<br><br>';
  	$this->debug->write("<p>file:kvwmap class:db_mapObj->delete_old_attributes - Löschen von alten Layerattributen:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }

	function create_layer_dumpfile($database, $layer_ids){
		$sql .= 'SET @group_id = 1;'.chr(10);
		$sql .= 'SET @connection = \'user=xxxx password=xxxx dbname=kvwmapsp\';'.chr(10).chr(10);
		for($i = 0; $i < count($layer_ids); $i++){
			$layer = $database->create_insert_dump('layer', '', 'SELECT `Name`, `Datentyp`, \'@group_id\' AS `Gruppe`, `pfad`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, wms_auth_username, wms_auth_password, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg` FROM layer WHERE Layer_ID='.$layer_ids[$i]);
			$sql .= $layer['insert'][0];
			$last_layer_id = '@last_layer_id'.$layer_ids[$i];
			$sql .= chr(10).'SET '.$last_layer_id.'=LAST_INSERT_ID();'.chr(10);
			$classes = $database->create_insert_dump('classes', 'Class_ID', 'SELECT `Class_ID`, `Name`, \''.$last_layer_id.'\' AS `Layer_ID`, `Expression`, `drawingorder`, `text` FROM classes WHERE Layer_ID='.$layer_ids[$i]);
			$layer_attributes = $database->create_insert_dump('layer_attributes', '', 'SELECT \''.$last_layer_id.'\' AS `layer_id`, `name`, real_name, tablename, table_alias_name, `type`, geometrytype, constraints, nullable, length, form_element_type, options, alias, tooltip, `order`, `privileg` FROM layer_attributes WHERE layer_id = '.$layer_ids[$i]);
			for($j = 0; $j < count($layer_attributes['insert']); $j++){
				$sql .= $layer_attributes['insert'][$j].chr(10);
			}
			for($j = 0; $j < count($classes['insert']); $j++){
				$sql .= $classes['insert'][$j];
				$sql .= chr(10).'SET @last_class_id=LAST_INSERT_ID();'.chr(10);
				$styles = $database->create_insert_dump('styles', '', 'SELECT `symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem` FROM styles, u_styles2classes WHERE u_styles2classes.style_id = styles.Style_ID AND Class_ID='.$classes['extra'][$j].' ORDER BY drawingorder');
				for($k = 0; $k < count($styles['insert']); $k++){
					$sql .= $styles['insert'][$k];
					$sql .= chr(10).' SET @last_style_id=LAST_INSERT_ID();'.chr(10);
					$sql .= 'INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, '.$k.');'.chr(10);
				}
				$labels = $database->create_insert_dump('labels', '', 'SELECT `font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force` FROM labels, u_labels2classes WHERE u_labels2classes.label_id = labels.Label_ID AND Class_ID='.$classes['extra'][$j]);
				for($k = 0; $k < count($labels['insert']); $k++){
					$sql .= $labels['insert'][$k];
					$sql .= chr(10).' SET @last_label_id=LAST_INSERT_ID();'.chr(10);
					$sql .= 'INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);'.chr(10);
				} 
			}
			$sql .= chr(10);
		}
		for($i = 0; $i < count($layer_ids); $i++){
			$sql .= 'UPDATE layer_attributes SET options = REPLACE(options, \''.$layer_ids[$i].'\', @last_layer_id'.$layer_ids[$i].') WHERE layer_id IN(@last_layer_id'.implode(', @last_layer_id', $layer_ids).') AND form_element_type IN (\'SubFormPK\', \'SubFormFK\', \'SubFormEmbeddedPK\');'.chr(10);
		}
		$filename = rand(0, 1000000).'.sql';
		$fp = fopen(IMAGEPATH.$filename, 'w');
		fwrite($fp, utf8_decode($sql));
		return $filename;
	}

  function deleteLayer($id){
    $sql = 'DELETE FROM layer WHERE Layer_ID = '.$id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteLayer - Löschen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    if(MYSQLVERSION > 412){
      # Den Autowert für die Layer_id zurücksetzen
      $sql ="ALTER TABLE layer AUTO_INCREMENT = 1";
      $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteLayer - Zurücksetzen des Auto_Incrementwertes:<br>".$sql,4);
      #echo $sql;
      $query=mysql_query($sql);
      if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    }
  }

  function deleteRollenLayer($id){
  	$sql = 'SELECT Typ, Data FROM rollenlayer WHERE id = '.$id;
  	$query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $rs=mysql_fetch_array($query);
    if($rs['Typ'] == 'import'){		# beim Shape-Import-Layern die Tabelle löschen
    	$explosion = explode(CUSTOM_SHAPE_SCHEMA.'.', $rs['Data']);
    	$sql = 'DROP TABLE '.CUSTOM_SHAPE_SCHEMA.'.'.$explosion[1].';';
    	$sql.= 'DELETE FROM geometry_columns WHERE f_table_schema = \''.CUSTOM_SHAPE_SCHEMA.'\' AND f_table_name = \''.$explosion[1].'\'';
    	$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Löschen eines RollenLayers:<br>".$sql,4);
      $query=pg_query($sql);
    }
    $sql = 'DELETE FROM rollenlayer WHERE id = '.$id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Löschen eines RollenLayers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    if(MYSQLVERSION > 412){
      # Den Autowert für die Layer_id zurücksetzen
      $sql ="ALTER TABLE rollenlayer AUTO_INCREMENT = 1";
      $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Zurücksetzen des Auto_Incrementwertes:<br>".$sql,4);
      #echo $sql;
      $query=mysql_query($sql);
      if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    }
  }

  function newRollenLayer($formvars){
    $formvars['Data'] = str_replace ( "'", "''", $formvars['Data']);

    $sql = "INSERT INTO rollenlayer (`user_id`, `stelle_id`, `aktivStatus`, `Name`, `Datentyp`, `Gruppe`, `Typ`, `Data`, `connection`, `connectiontype`, `transparency`, `epsg_code`, `labelitem`) VALUES(";
    $sql .= "'".$formvars['user_id']."', ";
    $sql .= "'".$formvars['stelle_id']."', ";
    $sql .= "'".$formvars['aktivStatus']."', ";
    $sql .= "'".addslashes($formvars['Name'])."', ";
    $sql .= "'".$formvars['Datentyp']."', ";
    $sql .= "'".$formvars['Gruppe']."', ";
    $sql .= "'".$formvars['Typ']."', ";
    $sql .= "'".$formvars['Data']."', ";
    $sql .= "'".$formvars['connection']."', ";
    $sql .= "'".$formvars['connectiontype']."', ";
    $sql .= "'".$formvars['transparency']."', ";
    $sql .= "'".$formvars['epsg_code']."', ";
    $sql .= "'".$formvars['labelitem']."'";
    $sql .= ")";
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->newRollenLayer - Erzeugen eines RollenLayers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    return mysql_insert_id();
  }

	function createAutoClasses($values, $attribute, $layer_id, $datatype, $database){
		$result_colors = read_colors($database);
		shuffle($result_colors);
		for($i = 0; $i < count($values); $i++){
			if($i == count($result_colors))return;				# Anzahl der Klassen ist auf die Anzahl der Colors beschränkt
			$classdata[0] = $values[$i].' ';
      $classdata[1] = -$layer_id;
      $classdata[2] = "('[".$attribute."]' eq '".$values[$i]."')";
      $classdata[3] = 0;
      $class_id = $this->new_Class($classdata);
    	$style['colorred'] = $result_colors[$i]['red'];
      $style['colorgreen'] = $result_colors[$i]['green'];
      $style['colorblue'] = $result_colors[$i]['blue'];
      $style['outlinecolorred'] = 0;
      $style['outlinecolorgreen'] = 0;
      $style['outlinecolorblue'] = 0;
     	$style['size'] = 3;
     	if($datatype < 2){
      	$style['symbolname'] = 'circle';
      	if($datatype == 0){
      		$style['size'] = 13;
      		$style['minsize'] = 5;
      		$style['maxsize'] = 20;
      	}
     	}
      $style['backgroundcolor'] = NULL;
      if (MAPSERVERVERSION > '500') {
      	$style['angle'] = 360;
      }
      $style_id = $this->new_Style($style);
      $this->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
		}
	}

  function updateLayer($formvars){
  	$formvars['pfad'] = str_replace(array("\r\n", "\n"), '', $formvars['pfad']);
    $formvars['pfad'] = str_replace ( "'", "''", $formvars['pfad']);
    $formvars['Data'] = str_replace ( "'", "''", $formvars['Data']);

    $sql = 'UPDATE layer SET ';
    if($formvars['id'] != ''){
      $sql.="Layer_ID = ".$formvars['id'].", ";
    }
    $sql .= "Name = '".$formvars['Name']."', ";
    $sql .= "alias = '".$formvars['alias']."', ";
    $sql .= "Datentyp = '".$formvars['Datentyp']."', ";
    $sql .= "Gruppe = '".$formvars['Gruppe']."', ";
    $sql .= "pfad = '".$formvars['pfad']."', ";
    $sql .= "maintable = '".$formvars['maintable']."', ";
    $sql .= "Data = '".$formvars['Data']."', ";
    $sql .= "`schema` = '".$formvars['schema']."', ";
    $sql .= "document_path = '".$formvars['document_path']."', ";
    $sql .= "tileindex = '".$formvars['tileindex']."', ";
    $sql .= "tileitem = '".$formvars['tileitem']."', ";
    $sql .= "labelangleitem = '".$formvars['labelangleitem']."', ";
    $sql .= "labelitem = '".$formvars['labelitem']."', ";
    if ($formvars['labelmaxscale']!='') {
      $sql .= "labelmaxscale = ".$formvars['labelmaxscale'].", ";
    }
    if ($formvars['labelminscale']!='') {
      $sql .= "labelminscale = ".$formvars['labelminscale'].", ";
    }
    $sql .= "labelrequires = '".$formvars['labelrequires']."', ";
    $sql .= "`connection` = '".$formvars['connection']."', ";
    $sql .= "`printconnection` = '".$formvars['printconnection']."', ";
    $sql .= "connectiontype = '".$formvars['connectiontype']."', ";
    $sql .= "classitem = '".$formvars['classitem']."', ";
    $sql .= "filteritem = '".$formvars['filteritem']."', ";
    $sql .= "tolerance = '".$formvars['tolerance']."', ";
    $sql .= "toleranceunits = '".$formvars['toleranceunits']."', ";
    $sql .= "epsg_code = '".$formvars['epsg_code']."', ";
    $sql .= "template = '".$formvars['template']."', ";
    $sql .= "queryable = '".$formvars['queryable']."', ";
    if($formvars['transparency'] == ''){$formvars['transparency'] = 'NULL';}
    $sql .= "transparency = ".$formvars['transparency'].", ";
    if($formvars['drawingorder'] == ''){$formvars['drawingorder'] = 'NULL';}
    $sql .= "drawingorder = ".$formvars['drawingorder'].", ";
    if($formvars['minscale'] == ''){$formvars['minscale'] = 'NULL';}
    $sql .= "minscale = ".$formvars['minscale'].", ";
    if($formvars['maxscale'] == ''){$formvars['maxscale'] = 'NULL';}
    $sql .= "maxscale = ".$formvars['maxscale'].", ";
    $sql .= "offsite = '".$formvars['offsite']."', ";
    $sql .= "ows_srs = '".$formvars['ows_srs']."', ";
    $sql .= "wms_name = '".$formvars['wms_name']."', ";
    $sql .= "wms_server_version = '".$formvars['wms_server_version']."', ";
    $sql .= "wms_format = '".$formvars['wms_format']."', ";
    $sql .= "wms_connectiontimeout = '".$formvars['wms_connectiontimeout']."', ";
    $sql .= "wms_auth_username = '".$formvars['wms_auth_username']."', ";
    $sql .= "wms_auth_password = '".$formvars['wms_auth_password']."', ";
    $sql .= "wfs_geom = '".$formvars['wfs_geom']."', ";
    $sql .= "selectiontype = '".$formvars['selectiontype']."',";
    $sql .= "querymap = '".$formvars['querymap']."',";
    $sql .= "processing = '".$formvars['processing']."',";
    $sql .= "kurzbeschreibung = '".$formvars['kurzbeschreibung']."',";
    $sql .= "datenherr = '".$formvars['datenherr']."',";
    $sql .= "metalink = '".$formvars['metalink']."'";
    $sql .= " WHERE Layer_ID = ".$formvars['selected_layer_id'];
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->updateLayer - Aktualisieren eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }

  function newLayer($layerdata) {
    # Erzeugt einen neuen Layer (entweder aus formvars oder aus einem Layerobjekt)
    if(is_array($layerdata)){
      $formvars = $layerdata;   # formvars wurden übergeben

      $formvars['pfad'] = stripslashes($formvars['pfad']);
      $formvars['Data'] = stripslashes($formvars['Data']);
      $formvars['pfad'] = str_replace ( "'", "''", $formvars['pfad']);
      $formvars['Data'] = str_replace ( "'", "''", $formvars['Data']);

      $sql = "INSERT INTO layer (";
      if($formvars['id'] != ''){
        $sql.="`Layer_ID`, ";
      }
      $sql.= "`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`) VALUES(";
      if($formvars['id'] != ''){
        $sql.="'".$formvars['id']."', ";
      }
      $sql .= "'".$formvars['Name']."', ";
      $sql .= "'".$formvars['alias']."', ";
      $sql .= "'".$formvars['Datentyp']."', ";
      $sql .= "'".$formvars['Gruppe']."', ";
      if($formvars['pfad'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['pfad']."', ";
      }
    	if($formvars['maintable'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['maintable']."', ";
      }
      if($formvars['Data'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['Data']."', ";
      }
      if($formvars['schema'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['schema']."', ";
      }
      if($formvars['document_path'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['document_path']."', ";
      }
      $sql .= "'".$formvars['tileindex']."', ";
      $sql .= "'".$formvars['tileitem']."', ";
      $sql .= "'".$formvars['labelangleitem']."', ";
      $sql .= "'".$formvars['labelitem']."', ";
      if($formvars['labelmaxscale']==''){$formvars['labelmaxscale']='NULL';}
      $sql .= $formvars['labelmaxscale'].", ";
      if($formvars['labelminscale']==''){$formvars['labelminscale']='NULL';}
      $sql .= $formvars['labelminscale'].", ";
      $sql .= "'".$formvars['labelrequires']."', ";
      $sql .= "'".$formvars['connection']."', ";
      $sql .= "'".$formvars['printconnection']."', ";
      $sql .= $formvars['connectiontype'].", ";
      $sql .= "'".$formvars['classitem']."', ";
      $sql .= "'".$formvars['filteritem']."', ";
      if($formvars['tolerance']==''){$formvars['tolerance']='3';}
      $sql .= $formvars['tolerance'].", ";
      if($formvars['toleranceunits']==''){$formvars['toleranceunits']='pixels';}
      $sql .= "'".$formvars['toleranceunits']."', ";
      $sql .= "'".$formvars['epsg_code']."', ";
      $sql .= "'".$formvars['template']."', ";
      $sql .= "'".$formvars['queryable']."', ";
      if($formvars['transparency']==''){$formvars['transparency']='NULL';}
      $sql .= $formvars['transparency'].", ";
      if($formvars['drawingorder']==''){$formvars['drawingorder']='NULL';}
      $sql .= $formvars['drawingorder'].", ";
      if($formvars['minscale']==''){$formvars['minscale']='NULL';}
      $sql .= $formvars['minscale'].", ";
      if($formvars['maxscale']==''){$formvars['maxscale']='NULL';}
      $sql .= $formvars['maxscale'].", ";
      $sql .= "'".$formvars['offsite']."', ";
      $sql .= "'".$formvars['ows_srs']."', ";
      $sql .= "'".$formvars['wms_name']."', ";
      $sql .= "'".$formvars['wms_server_version']."', ";
      $sql .= "'".$formvars['wms_format']."', ";
      if ($formvars['wms_connectiontimeout']=='') {
        $formvars['wms_connectiontimeout']='60';
      }
      $sql .= $formvars['wms_connectiontimeout'].", ";
      $sql .= "'".$formvars['wms_auth_username']."', ";
      $sql .= "'".$formvars['wms_auth_password']."', ";
      $sql .= "'".$formvars['wfs_geom']."', ";
      $sql .= "'".$formvars['selectiontype']."', ";
      $sql .= "'".$formvars['querymap']."', ";
      $sql .= "'".$formvars['processing']."', ";
      $sql .= "'".$formvars['kurzbeschreibung']."', ";
      $sql .= "'".$formvars['datenherr']."', ";
      $sql .= "'".$formvars['metalink']."'";
      $sql .= ")";

    }
    else{
      $layer = $layerdata;      # ein Layerobject wurde übergeben
      $projection = explode('epsg:', $layer->getProjection());
      $sql = "INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`) VALUES(";
      $sql .= "'".$layer->name."', ";
      $sql .= "'".$layer->type."', ";
      $sql .= "'".$layer->group."', ";
      $sql .= "'', ";                 # pfad
      $sql .= "'".$layer->data."', ";
      $sql .= "'".$layer->tileindex."', ";
      $sql .= "'".$layer->tileitem."', ";
      $sql .= "'".$layer->labelangleitem."', ";
      $sql .= "'".$layer->labelitem."', ";
      $sql .= $layer->labelmaxscale.", ";
      $sql .= $layer->labelminscale.", ";
      $sql .= "'".$layer->labelrequires."', ";
      $sql .= "'".$layer->connection."', ";
      $sql .= $layer->connectiontype.", ";
      $sql .= "'".$layer->classitem."', ";
      $sql .= "'".$layer->filteritem."', ";
      $sql .= $layer->tolerance.", ";
      $sql .= "'".$layer->toleranceunits."', ";
      $sql .= "'".$projection[1]."', ";               # epsg_code
      $sql .= "'', ";               # ows_srs
      $sql .= "'', ";               # wms_name
      $sql .= "'', ";               # wms_server_version
      $sql .= "'', ";               # wms_format
      $sql .= "60";                 # wms_connectiontimeout
      $sql .= ")";
    }

    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->newLayer - Erzeugen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }

    return mysql_insert_id();
  }

  function save_attributes($attributes, $database, $formvars){
    for($i = 0; $i < count($attributes['name']); $i++){
      $sql = 'INSERT INTO layer_attributes SET ';
      $sql.= 'layer_id = '.$formvars['selected_layer_id'].', ';
      $sql.= 'name = "'.$attributes['name'][$i].'", ';
      $sql.= 'form_element_type = "'.$formvars['form_element_'.$attributes['name'][$i]].'", ';
      $sql.= 'options = "'.$formvars['options_'.$attributes['name'][$i]].'", ';
      $sql.= 'tooltip = "'.$formvars['tooltip_'.$attributes['name'][$i]].'", ';
      $sql.= '`group` = "'.$formvars['group_'.$attributes['name'][$i]].'", ';
      if($formvars['mandatory_'.$attributes['name'][$i]] == ''){
      	$formvars['mandatory_'.$attributes['name'][$i]] = 'NULL';
      }
      $sql.= 'mandatory = '.$formvars['mandatory_'.$attributes['name'][$i]].', ';
      $sql.= 'alias = "'.$formvars['alias_'.$attributes['name'][$i]].'" ';
      $sql.= 'ON DUPLICATE KEY UPDATE name = "'.$attributes['name'][$i].'", form_element_type = "'.$formvars['form_element_'.$attributes['name'][$i]].'", options = "'.$formvars['options_'.$attributes['name'][$i]].'", tooltip = "'.$formvars['tooltip_'.$attributes['name'][$i]].'", `group` = "'.$formvars['group_'.$attributes['name'][$i]].'", alias = "'.$formvars['alias_'.$attributes['name'][$i]].'", mandatory = '.$formvars['mandatory_'.$attributes['name'][$i]].' ';
      $this->debug->write("<p>file:kvwmap class:Document->save_attributes :",4);
      $database->execSQL($sql,4, 1);
    }
  }

	function delete_layer_filterattributes($layer_id){
    $sql = 'DELETE FROM u_attributfilter2used_layer WHERE layer_id = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_filterattributes:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }

  function delete_layer_attributes($layer_id){
    $sql = 'DELETE FROM layer_attributes WHERE layer_id = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_attributes:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }
  
  function delete_layer_attributes2stelle($layer_id, $stelle_id){
    $sql = 'DELETE FROM layer_attributes2stelle WHERE layer_id = '.$layer_id.' AND stelle_id = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_attributes2stelle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }
  
  function read_layer_attributes($layer_id, $layerdb, $attributenames){
    	if($attributenames != NULL){
    		$einschr = ' AND name IN (\'';
    		$einschr.= implode('\', \'', $attributenames);
    		$einschr.= '\')';
    	}
      $sql = 'SELECT * FROM layer_attributes WHERE layer_id = '.$layer_id.$einschr.' ORDER BY `order`';
      $this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes:<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
      $i = 0;
      while($rs=mysql_fetch_array($query)){
      	$attributes['name'][$i]= $rs['name'];
      	$attributes['real_name'][$rs['name']]= $rs['real_name'];
      	if($rs['tablename'])$attributes['table_name'][$i]= $rs['tablename'];
      	if($rs['tablename'])$attributes['table_name'][$rs['name']] = $rs['tablename']; 
      	if($rs['table_alias_name'])$attributes['table_alias_name'][$i]= $rs['table_alias_name'];
      	if($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']]= $rs['table_alias_name'];
      	$attributes['table_alias_name'][$rs['tablename']]= $rs['table_alias_name'];
      	$attributes['type'][$i]= $rs['type'];
      	if($rs['type'] == 'geometry'){
      		$attributes['the_geom'] = $rs['name'];
      	}
      	$attributes['geomtype'][$i]= $rs['geometrytype'];
      	$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
      	$attributes['constraints'][$i]= $rs['constraints'];
      	$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
      	$attributes['nullable'][$i]= $rs['nullable'];
      	$attributes['length'][$i]= $rs['length'];
      	$attributes['decimal_length'][$i]= $rs['decimal_length'];
  		
  		if(substr($rs['default'], 0, 6) == 'SELECT'){					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
  			$ret1 = $layerdb->execSQL($rs['default'], 4, 0);					
  			if($ret1[0]==0){
  				$attributes['default'][$i] = array_pop(pg_fetch_row($ret1[1]));
  			}
  		}
  		else{															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den Layer in dieser Version einmal gespeichert hat
  			$attributes['default'][$i]= $rs['default'];
  		}
      	$attributes['form_element_type'][$i]= $rs['form_element_type'];
      	$attributes['form_element_type'][$rs['name']]= $rs['form_element_type'];
      	$attributes['options'][$i]= $rs['options'];
      	$attributes['options'][$rs['name']]= $rs['options'];
      	$attributes['alias'][$i]= $rs['alias'];
      	$attributes['alias'][$attributes['name'][$i]]= $rs['alias'];
      	$attributes['tooltip'][$i]= $rs['tooltip'];
      	$attributes['group'][$i]= $rs['group'];
      	$attributes['mandatory'][$i]= $rs['mandatory'];
      	$attributes['privileg'][$i]= $rs['privileg'];
      	$attributes['query_tooltip'][$i]= $rs['query_tooltip'];
      	$i++;
      }
      if($attributes['table_name'] != NULL){   
        $attributes['all_table_names'] = array_unique($attributes['table_name']);
        //$attributes['all_alias_table_names'] = array_values(array_unique($attributes['table_alias_name']));
        foreach($attributes['all_table_names'] as $tablename){
          $attributes['oids'][] = $layerdb->check_oid($tablename);   # testen ob Tabelle oid hat
        }
      }
      else{
      	$attributes['all_table_names'] = array();
      }
      return $attributes;
  }

  function getall_Layer($order) {
    $sql ='SELECT * FROM layer, u_groups';
    $sql.=' WHERE layer.Gruppe = u_groups.id';
    if($order != ''){$sql .= ' ORDER BY '.$order;}
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Layer - Lesen aller Layer:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while($rs=mysql_fetch_array($query)) {
          $layer['ID'][]=$rs['Layer_ID'];
          $layer['Bezeichnung'][]=$rs['Name'];
          $layer['Gruppe'][]=$rs['Gruppenname'];
					$layer['GruppeID'][]=$rs['Gruppe'];
          $layer['Kurzbeschreibung'][]=$rs['kurzbeschreibung'];
          $layer['Datenherr'][]=$rs['datenherr'];
          $layer['alias'][]=$rs['alias'];
      }
    if($order == 'Bezeichnung'){
      // Sortieren der Layer unter Berücksichtigung von Umlauten
      $sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
      $layer['Bezeichnung'] = $sorted_arrays['array'];
      $layer['ID'] = $sorted_arrays['second_array'];
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['GruppeID']);
			$layer['GruppeID'] = $sorted_arrays['second_array'];
    }
    return $layer;
  }
  
  function get_stellen_from_layer($layer_id){
    $sql = 'SELECT ID, Bezeichnung FROM stelle, used_layer WHERE used_layer.Stelle_ID = stelle.ID AND used_layer.Layer_ID = '.$layer_id.' ORDER BY Bezeichnung';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_stellen_from_layer - Lesen der Stellen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $stellen['ID'][]=$rs['ID'];
      $stellen['Bezeichnung'][]=$rs['Bezeichnung'];
    }
    return $stellen;
  }

  function get_postgis_layers($order) {
    $sql ='SELECT * FROM layer, u_groups';
    $sql.=' WHERE layer.Gruppe = u_groups.id AND connectiontype = 6';
    if($order != ''){$sql .= ' ORDER BY '.$order;}
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Layer - Lesen aller Layer:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while($rs=mysql_fetch_array($query)) {
          $layer['ID'][]=$rs['Layer_ID'];
          $layer['Bezeichnung'][]=$rs['Name'];
      }
    if($order == 'Bezeichnung'){
      // Sortieren der Layer unter Berücksichtigung von Umlauten
      $sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
      $layer['Bezeichnung'] = $sorted_arrays['array'];
      $layer['ID'] = $sorted_arrays['second_array'];
    }
    return $layer;
  }

  function get_Layer($id) {
    $sql ='SELECT * FROM layer WHERE Layer_ID = '.$id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Layer - Lesen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $layer = mysql_fetch_array($query);
    return $layer;
  }
  
	function set_default_layer_privileges($formvars, $attributes){
		for($i = 0; $i < count($attributes['type']); $i++){
			if($formvars['privileg_'.$attributes['name'][$i]] == '')$formvars['privileg_'.$attributes['name'][$i]] = 'NULL';
			$sql = 'UPDATE layer_attributes SET ';
			$sql.= 'privileg = '.$formvars['privileg_'.$attributes['name'][$i]];
			if($formvars['tooltip_'.$attributes['name'][$i]] == 'on'){
				$sql.= ', query_tooltip = 1';
			}
			else{
				$sql.= ', query_tooltip = 0';
			}
			$sql.= ' WHERE layer_id = '.$formvars['selected_layer_id'].' AND name = "'.$attributes['name'][$i].'"';
			$this->debug->write("<p>file:users.php class:stelle->set_default_layer_privileges - Speichern des Layerrechte zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			$sql = 'UPDATE layer SET privileg = "'.$formvars['privileg'].'" WHERE ';
			$sql.= 'Layer_ID = '.$formvars['selected_layer_id'];
			$this->debug->write("<p>file:users.php class:stelle->set_default_layer_privileges - Speichern der Layerrechte zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
	}
  
	function get_layersfromgroup($group_id ){
    $sql ='SELECT * FROM layer';
		if($group_id != '')$sql.=' WHERE Gruppe = '.$group_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Layerfromgroup - Lesen der Layer einer Gruppe:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
    	$layer['ID'][]=$rs['Layer_ID'];
      $layer['Bezeichnung'][]=$rs['Name'];
    }
    // Sortieren der Layer unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
    $layer['Bezeichnung'] = $sorted_arrays['array'];
    $layer['ID'] = $sorted_arrays['second_array'];
    return $layer;
  }
	
	function id_exists($tablename, $id) {
	  $layer = $this->get_Layer($id);
		if ($layer) {
		  return true;
		}
		else {
		  return false;
		}
	}
	
	function get_table_information($dbname, $tablename) {
		$sql = "SELECT * FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = '".$tablename."'";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_table_information - Lesen der Metadaten der Tabelle ".$tablename." in db ".$dbname.":<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $metadata = mysql_fetch_array($query);
    return $metadata;
	}
  
  function get_used_Layer($id) {
    $sql ='SELECT * FROM used_layer WHERE Layer_ID = '.$id.' AND Stelle_ID = '.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_used_Layer - Lesen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $layer = mysql_fetch_array($query);
    return $layer;
  }

  function newGroup($groupname){
    $sql = 'INSERT INTO u_groups SET Gruppenname = "'.$groupname.'"';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Group - Erstellen einer Gruppe:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    return mysql_insert_id();
  }

  function getall_Groups() {
    $sql ='SELECT * FROM u_groups ORDER BY Gruppenname';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Groups - Lesen aller Gruppen:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while($rs=mysql_fetch_array($query)) {
          $groups[$rs['id']] = $rs;
      }
    return $groups;
  }

  function getGroupbyName($groupname){
    $sql ="SELECT * FROM u_groups WHERE Gruppenname = '".$groupname."'";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getGroupbyName - Lesen einer Gruppe:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $rs=mysql_fetch_array($query);
    return $rs;
  }

  function getClassFromObject($select, $layer_id){
    # diese Funktion bestimmt für ein über die oid gegebenes Objekt welche Klasse dieses Objekt hat
    $classes = $this->read_Classes($layer_id);
    $anzahl = count($classes);
    if($anzahl == 1){
      return $classes[0]['Class_ID'];
    }
    else{
      for($i = 0; $i < $anzahl; $i++){
        $exp = str_replace(array("'[", "]'", '[', ']', ')', '('), '', $classes[$i]['Expression']);
        $exp = str_replace('eq', '=', $exp);
        $exp = str_replace('ne', '!=', $exp);

				# wenn im Data sowas wie "tabelle.attribut" vorkommt, soll das anstatt dem "attribut" aus der Expression verwendet werden        
        //$attributes = explode(',', substr($select, 0, strpos(strtolower($select), ' from ')));
        $attributes = get_select_parts(substr($select, 0, strpos(strtolower($select), ' from ')));							
        $exp_parts = explode(' ', $exp);
        for($k = 0; $k < count($exp_parts); $k++){
	      	for($j = 0; $j < count($attributes); $j++){
	      		if($exp_parts[$k] != '' AND strpos(strtolower($attributes[$j]), '.'.$exp_parts[$k]) !== false){
	      			$exp_parts[$k] = str_replace('select ', '', strtolower($attributes[$j]));
	      		}		
	      	}
	      }
	      $exp = implode(' ', $exp_parts);
        $sql = $select." AND ".$exp;
        $this->debug->write("<p>file:kvwmap class:db_mapObj->getClassFromObject - Lesen einer Klasse eines Objektes:<br>".$sql,4);
        $query=pg_query($sql);
        if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
        $count=pg_num_rows($query);
        if($count == 1){
          return $classes[$i]['Class_ID'];
        }
      }
    }
  }
  
	function copyStyle($style_id){
		$sql = "INSERT INTO styles (symbol,symbolname,size,color,backgroundcolor,outlinecolor,minsize,maxsize,angle,angleitem,antialias,width,minwidth,maxwidth,sizeitem) SELECT symbol,symbolname,size,color,backgroundcolor,outlinecolor,minsize,maxsize,angle,angleitem,antialias,width,minwidth,maxwidth,sizeitem FROM styles WHERE Style_ID = ".$style_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->copyStyle - Kopieren eines Styles:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		return mysql_insert_id();
	}
	
	function copyLabel($label_id){
		$sql = "INSERT INTO labels (font,type,color,outlinecolor,shadowcolor,shadowsizex,shadowsizey,backgroundcolor,backgroundshadowcolor,backgroundshadowsizex,backgroundshadowsizey,size,minsize,maxsize,position,offsetx,offsety,angle,autoangle,buffer,antialias,minfeaturesize,maxfeaturesize,partials,wrap,the_force) SELECT font,type,color,outlinecolor,shadowcolor,shadowsizex,shadowsizey,backgroundcolor,backgroundshadowcolor,backgroundshadowsizex,backgroundshadowsizey,size,minsize,maxsize,position,offsetx,offsety,angle,autoangle,buffer,antialias,minfeaturesize,maxfeaturesize,partials,wrap,the_force FROM labels WHERE Label_ID = ".$label_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->copyLabel - Kopieren eines Labels:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		return mysql_insert_id();
	}
	
  function copyClass($class_id, $layer_id){
    # diese Funktion kopiert eine Klasse mit Styles und Labels und gibt die ID der neuen Klasse zurück
    $class = $this->read_ClassesbyClassid($class_id);
    $sql = "INSERT INTO classes (Name,Layer_ID,Expression,drawingorder,text) SELECT Name, ".$layer_id.",'',drawingorder,text FROM classes WHERE Class_ID = ".$class_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->copyClass - Kopieren einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $new_class_id = mysql_insert_id();
    for($i = 0; $i < count($class[0]['Style']); $i++){
      $new_style_id = $this->copyStyle($class[0]['Style'][$i]['Style_ID']);
      $this->addStyle2Class($new_class_id, $new_style_id, $class[0]['Style'][$i]['drawingorder']);
    }
    for($i = 0; $i < count($class[0]['Label']); $i++){
			$new_label_id = $this->copyLabel($class[0]['Label'][$i]['Label_ID']);
      $this->addLabel2Class($new_class_id, $new_label_id);
    }
    return $new_class_id;
  }

  function new_Class($classdata) {
    if(is_array($classdata)){
      $attrib = $classdata;         # Attributarray wurde übergeben
      # attrib:(Name, Layer_ID, Expression, drawingorder)
      $sql = 'INSERT INTO classes (Name, Layer_ID, Expression, drawingorder) VALUES ("'.$attrib[0].'", '.$attrib[1].', "'.$attrib[2].'", "'.$attrib[3].'")';
    }
    else{
      $class = $classdata;        # Classobjekt wurde übergeben
      if(MAPSERVERVERSION > 500){
        $expression = $class->getExpressionString();
      }
      else{
        $expression = $class->getExpression();
      }
      $sql = 'INSERT INTO classes (Name, Layer_ID, Expression, drawingorder) VALUES ';
      $sql.= '("'.$class->name.'", '.$class->layer_id.', "'.$expression.'", "'.$class->drawingorder.'")';
    }
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Group - Erstellen einer Klasse zu einem Layer:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }

    return mysql_insert_id();
  }

  function delete_Class($class_id){
    $sql = 'DELETE FROM classes WHERE Class_ID = '.$class_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Class - Löschen einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }

    # Einträge in u_styles2classes und evtl. die Styles mitlöschen
    $styles = $this->read_Styles($class_id);
    for($i = 0; $i < count($styles); $i++){
    	$this->removeStyle2Class($class_id, $styles[$i]['style_id']);
      $other_classes = $this->get_classes2style($styles[$i]['style_id']);
      if($other_classes == NULL){
      	$this->delete_Style($styles[$i]['style_id']);
      }
    }
    # Einträge in u_labels2classes und evtl. die Labels mitlöschen
    $labels = $this->read_Label($class_id);
    for($i = 0; $i < count($labels); $i++){
    	$this->removeLabel2Class($class_id, $labels[$i]['label_id']);
    	$other_classes = $this->get_classes2label($labels[$i]['label_id']);
    	if($other_classes == NULL){
      	$this->delete_Label($labels[$i]['label_id']);
    	}
    }
  }

  function update_Class($attrib){
    # attrib:(Name, Layer_ID, Expression, drawingorder, Class_ID)
    $sql = 'UPDATE classes SET Name = "'.$attrib[0].'", Layer_ID = '.$attrib[1].', Expression = "'.$attrib[2].'", drawingorder = "'.$attrib[3].'" WHERE Class_ID = '.$attrib[4];
    #echo $sql.'<br>';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->update_Class - Aktualisieren einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function new_Style($style){
    if(is_array($style)){
      $sql = "INSERT INTO styles SET ";
      if($style['color']){$sql.= "color = '".$style['color']."'";}
      if($style['colorred']){$sql.= "color = '".$style['colorred']." ".$style['colorgreen']." ".$style['colorblue']."'";}
      if($style['symbol']){$sql.= ", symbol = '".$style['symbol']."'";}
      if($style['symbolname']){$sql.= ", symbolname = '".$style['symbolname']."'";}
      if($style['size']){$sql.= ", size = '".$style['size']."'";}
      if($style['backgroundcolor'] !== NULL){$sql.= ", backgroundcolor = '".$style['backgroundcolor']."'";}
      if($style['backgroundcolorred'] !== NULL){$sql.= ", backgroundcolor = '".$style['backgroundcolorred']." ".$style['backgroundcolorgreen']." ".$style['backgroundcolorblue']."'";}
      if($style['outlinecolor'] !== NULL){$sql.= ", outlinecolor = '".$style['outlinecolor']."'";}
      if($style['outlinecolorred'] !== NULL){$sql.= ", outlinecolor = '".$style['outlinecolorred']." ".$style['outlinecolorgreen']." ".$style['outlinecolorblue']."'";}
      if($style['minsize']){$sql.= ", minsize = '".$style['minsize']."'";}
      if($style['maxsize']){$sql.= ", maxsize = '".$style['maxsize']."'";}
      if($style['angle']){$sql.= ", angle = '".$style['angle']."'";}
      if($style['width']){$sql.= ", width = '".$style['width']."'";}
      if($style['minwidth']){$sql.= ", minwidth = '".$style['minwidth']."'";}
      if($style['maxwidth']){$sql.= ", maxwidth = '".$style['maxwidth']."'";}
    }
    else{
    # Styleobjekt wird übergeben
      $sql = "INSERT INTO styles SET ";
      $sql.= "symbol = '".$style->symbol."', ";
      $sql.= "symbolname = '".$style->symbolname."', ";
      $sql.= "size = '".$style->size."', ";
      $sql.= "color = '".$style->color->red." ".$style->color->green." ".$style->color->blue."', ";
      $sql.= "backgroundcolor = '".$style->backgroundcolor->red." ".$style->backgroundcolor->green." ".$style->backgroundcolor->blue."', ";
      $sql.= "outlinecolor = '".$style->outlinecolor->red." ".$style->outlinecolor->green." ".$style->outlinecolor->blue."', ";
      $sql.= "minsize = '".$style->minsize."', ";
      $sql.= "maxsize = '".$style->maxsize."'";
    }
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Style - Erzeugen eines Styles:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    return mysql_insert_id();
  }

	function get_classes2style($style_id){
		$sql = 'SELECT class_id FROM u_styles2classes WHERE Style_ID = '.$style_id;
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_classes2style - Abfragen der Klassen, die einen Style benutzen:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $classes[]=$rs[0];
    }
    return $classes;
	}

  function delete_Style($style_id){
    $sql = 'DELETE FROM styles WHERE Style_ID = '.$style_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Style - Löschen eines Styles:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }
  
  function moveup_Style($style_id, $class_id){
    $sql = 'SELECT * FROM u_styles2classes WHERE class_id = '.$class_id.' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($query)) {
      $styles[$i]=$rs;
      if($rs['style_id'] == $style_id){
        $index = $i;
      }
      $i++;
    }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index+1]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index+1]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }
  
  function movedown_Style($style_id, $class_id){
    $sql = 'SELECT * FROM u_styles2classes WHERE class_id = '.$class_id.' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($query)) {
      $styles[$i]=$rs;
      if($rs['style_id'] == $style_id){
        $index = $i;
      }
      $i++;
    }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index-1]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index-1]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function delete_Label($label_id){
    $sql = 'DELETE FROM labels WHERE Label_ID = '.$label_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Label - Löschen eines Labels:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function addStyle2Class($class_id, $style_id, $drawingorder){
    if($drawingorder == NULL){
      $sql = 'SELECT MAX(drawingorder) FROM u_styles2classes WHERE class_id = '.$class_id;
      $this->debug->write("<p>file:kvwmap class:db_mapObj->addStyle2Class :<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
      $rs = mysql_fetch_array($query);
      $drawingorder = $rs[0]+1;
    }
    $sql = 'INSERT INTO u_styles2classes VALUES ('.$class_id.', '.$style_id.', "'.$drawingorder.'")';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->addStyle2Class - Hinzufügen eines Styles zu einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function removeStyle2Class($class_id, $style_id){
    $sql = 'DELETE FROM u_styles2classes WHERE class_id = '.$class_id.' AND style_id = '.$style_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->removeStyle2Class - Löschen eines Styles:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function save_Style($formvars){
  	# wenn der Style nicht der Klasse zugeordnet ist, zuordnen
  	$classes = $this->get_classes2style($formvars["style_id"]);
  	if(!in_array($formvars["class_id"], $classes))$this->addStyle2Class($formvars["class_id"], $formvars["style_id"], NULL);
    $sql ="UPDATE styles SET ";
    if($formvars["symbol"]){$sql.="symbol = '".$formvars["symbol"]."',";}else{$sql.="symbol = NULL,";}
    $sql.="symbolname = '".$formvars["symbolname"]."',";
    if($formvars["size"] != ''){$sql.="size = '".$formvars["size"]."',";}else{$sql.="size = NULL,";}
    if($formvars["color"] != ''){$sql.="color = '".$formvars["color"]."',";}else{$sql.="color = NULL,";}
    if($formvars["backgroundcolor"] != ''){$sql.="backgroundcolor = '".$formvars["backgroundcolor"]."',";}else{$sql.="backgroundcolor = NULL,";}
    if($formvars["outlinecolor"] != ''){$sql.="outlinecolor = '".$formvars["outlinecolor"]."',";}else{$sql.="outlinecolor = NULL,";}
    if($formvars["minsize"] != ''){$sql.="minsize = '".$formvars["minsize"]."',";}else{$sql.="minsize = NULL,";}
    if($formvars["maxsize"] != ''){$sql.="maxsize = '".$formvars["maxsize"]."',";}else{$sql.="maxsize = NULL,";}
    if($formvars["angle"] != ''){$sql.="angle = ".$formvars["angle"].",";}else{$sql.="angle = NULL,";}
    $sql.="angleitem = '".$formvars["angleitem"]."',";
    if($formvars["antialias"] != ''){$sql.="antialias = '".$formvars["antialias"]."',";}else{$sql.="antialias = NULL,";}
    if($formvars["width"] != ''){$sql.="width = '".$formvars["width"]."',";}else{$sql.="width = NULL,";}
    if($formvars["minwidth"] != ''){$sql.="minwidth = '".$formvars["minwidth"]."',";}else{$sql.="minwidth = NULL,";}
    if($formvars["maxwidth"] != ''){$sql.="maxwidth = '".$formvars["maxwidth"]."',";}else{$sql.="maxwidth = NULL,";}
    $sql.="sizeitem = '".$formvars["sizeitem"]."',";
    if($formvars["offsetx"] != ''){$sql.="offsetx = '".$formvars["offsetx"]."',";}else{$sql.="offsetx = NULL,";}
    if($formvars["offsety"] != ''){$sql.="offsety = '".$formvars["offsety"]."',";}else{$sql.="offsety = NULL,";}
    if($formvars["pattern"] != ''){$sql.="pattern = '".$formvars["pattern"]."',";}else{$sql.="pattern = NULL,";}
  	if($formvars["geomtransform"] != ''){$sql.="geomtransform = '".$formvars["geomtransform"]."',";}else{$sql.="geomtransform = NULL,";}
		if($formvars["gap"] != ''){$sql.="gap = ".$formvars["gap"].",";}else{$sql.="gap = NULL,";}
		if($formvars["linecap"] != ''){$sql.="linecap = '".$formvars["linecap"]."',";}else{$sql.="linecap = NULL,";}
		if($formvars["linejoin"] != ''){$sql.="linejoin = '".$formvars["linejoin"]."',";}else{$sql.="linejoin = NULL,";}
		if($formvars["linejoinmaxsize"] != ''){$sql.="linejoinmaxsize = ".$formvars["linejoinmaxsize"].",";}else{$sql.="linejoinmaxsize = NULL,";}
    $sql.="Style_ID = ".$formvars["new_style_id"];
    $sql.=" WHERE Style_ID = ".$formvars["style_id"];
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->save_Style - Speichern der Styledaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function get_Style($style_id){
  	if($style_id){
	    $sql ='SELECT * FROM styles AS s';
	    $sql.=' WHERE s.Style_ID = '.$style_id;
	    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Style - Lesen der Styledaten:<br>".$sql,4);
	    $query=mysql_query($sql);
	    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
	    $rs=mysql_fetch_assoc($query);
	    return $rs;
  	}
  }

  function save_Label($formvars){
    $sql ="UPDATE labels SET ";
    if($formvars["font"]){$sql.="font = '".$formvars["font"]."',";}
    if($formvars["type"]){$sql.="type = '".$formvars["type"]."',";}
    if($formvars["color"]){$sql.="color = '".$formvars["color"]."',";}
    if($formvars["outlinecolor"] != ''){$sql.="outlinecolor = '".$formvars["outlinecolor"]."',";}else{$sql.="outlinecolor = NULL,";}
    if($formvars["shadowcolor"] != ''){$sql.="shadowcolor = '".$formvars["shadowcolor"]."',";}else{$sql.="shadowcolor = NULL,";}
    if($formvars["shadowsizex"] != ''){$sql.="shadowsizex = '".$formvars["shadowsizex"]."',";}else{$sql.="shadowsizex = NULL,";}
    if($formvars["shadowsizey"] != ''){$sql.="shadowsizey = '".$formvars["shadowsizey"]."',";}else{$sql.="shadowsizey = NULL,";}
    if($formvars["backgroundcolor"] != ''){$sql.="backgroundcolor = '".$formvars["backgroundcolor"]."',";}else{$sql.="backgroundcolor = NULL,";}
    if($formvars["backgroundshadowcolor"] != ''){$sql.="backgroundshadowcolor = '".$formvars["backgroundshadowcolor"]."',";}else{$sql.="backgroundshadowcolor = NULL,";}
    if($formvars["backgroundshadowsizex"] != ''){$sql.="backgroundshadowsizex = '".$formvars["backgroundshadowsizex"]."',";}else{$sql.="backgroundshadowsizex = NULL,";}
    if($formvars["backgroundshadowsizey"] != ''){$sql.="backgroundshadowsizey = '".$formvars["backgroundshadowsizey"]."',";}else{$sql.="backgroundshadowsizey = NULL,";}
    if($formvars["size"]){$sql.="size = '".$formvars["size"]."',";}
    if($formvars["minsize"]){$sql.="minsize = '".$formvars["minsize"]."',";}
    if($formvars["maxsize"]){$sql.="maxsize = '".$formvars["maxsize"]."',";}
    if($formvars["position"]){$sql.="position = '".$formvars["position"]."',";}
    if($formvars["offsetx"] != ''){$sql.="offsetx = '".$formvars["offsetx"]."',";}else{$sql.="offsetx = NULL,";}
    if($formvars["offsety"] != ''){$sql.="offsety = '".$formvars["offsety"]."',";}else{$sql.="offsety = NULL,";}
    if($formvars["angle"] != ''){$sql.="angle = '".$formvars["angle"]."',";}else{$sql.="angle = NULL,";}
    if($formvars["autoangle"]){$sql.="autoangle = '".$formvars["autoangle"]."',";}
    if($formvars["buffer"]){$sql.="buffer = '".$formvars["buffer"]."',";}
    if($formvars["antialias"] != ''){$sql.="antialias = '".$formvars["antialias"]."',";}else{$sql.="antialias = NULL,";}
    if($formvars["minfeaturesize"]){$sql.="minfeaturesize = '".$formvars["minfeaturesize"]."',";}
    if($formvars["maxfeaturesize"]){$sql.="maxfeaturesize = '".$formvars["maxfeaturesize"]."',";}
    if($formvars["partials"] != ''){$sql.="partials = '".$formvars["partials"]."',";}
    if($formvars["wrap"] != ''){$sql.="wrap = '".$formvars["wrap"]."',";}
    if($formvars["the_force"] != ''){$sql.="the_force = '".$formvars["the_force"]."',";}
    $sql.="Label_ID = ".$formvars["new_label_id"];
    $sql.=" WHERE Label_ID = ".$formvars["label_id"];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->save_Label - Speichern der Labeldaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function get_Label($label_id) {
    $sql ='SELECT * FROM labels AS l';
    $sql.=' WHERE l.Label_ID = '.$label_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Label - Lesen der Labeldaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $rs=mysql_fetch_assoc($query);
    return $rs;
  }

  function new_Label($label){
  	if(is_array($label)){
  	$sql = "INSERT INTO labels SET ";
	    if($label[type]){$sql.= "type = '".$label[type]."', ";}
	    if($label[font]){$sql.= "font = '".$label[font]."', ";}
	    if($label[size]){$sql.= "size = '".$label[size]."', ";}
	    if($label[color]){$sql.= "color = '".$label[color]."', ";}
	    if($label[shadowcolor]){$sql.= "shadowcolor = '".$label[shadowcolor]."', ";}
	    if($label[shadowsizex]){$sql.= "shadowsizex = '".$label[shadowsizex]."', ";}
	    if($label[shadowsizey]){$sql.= "shadowsizey = '".$label[shadowsizey]."', ";}
	    if($label[backgroundcolor]){$sql.= "backgroundcolor = '".$label[backgroundcolor]."', ";}
	    if($label[backgroundshadowcolor]){$sql.= "backgroundshadowcolor = '".$label[backgroundshadowcolor]."', ";}
	    if($label[backgroundshadowsizex]){$sql.= "backgroundshadowsizex = '".$label[backgroundshadowsizex]."', ";}
	    if($label[backgroundshadowsizey]){$sql.= "backgroundshadowsizey = '".$label[backgroundshadowsizey]."', ";}
	    if($label[outlinecolor]){$sql.= "outlinecolor = '".$label[outlinecolor]."', ";}
	    if($label[position]){$sql.= "position = '".$label[position]."', ";}
	    if($label[offsetx]){$sql.= "offsetx = '".$label[offsetx]."', ";}
	    if($label[offsety]){$sql.= "offsety = '".$label[offsety]."', ";}
	    if($label[angle]){$sql.= "angle = '".$label[angle]."', ";}
	    if($label[autoangle]){$sql.= "autoangle = '".$label[autoangle]."', ";}
	    if($label[buffer]){$sql.= "buffer = '".$label[buffer]."', ";}
	    if($label[antialias]){$sql.= "antialias = '".$label[antialias]."', ";}
	    if($label[minfeaturesize]){$sql.= "minfeaturesize = '".$label[minfeaturesize]."', ";}
	    if($label[maxfeaturesize]){$sql.= "maxfeaturesize = '".$label[maxfeaturesize]."', ";}
	    if($label[partials]){$sql.= "partials = '".$label[partials]."', ";}
	    if($label[wrap]){$sql.= "wrap = '".$label[wrap]."', ";}
	    if($label[the_force]){$sql.= "the_force = '".$label[the_force]."', ";}
	    if($label[minsize]){$sql.= "minsize = '".$label[minsize]."', ";}
	    if($label[maxsize]){$sql.= "maxsize = '".$label[maxsize]."'";}
  	}
  	else{
	    # labelobjekt wird übergeben
	    $sql = "INSERT INTO labels SET ";
	    if($label->type){$sql.= "type = '".$label->type."', ";}
	    if($label->font){$sql.= "font = '".$label->font."', ";}
	    if($label->size){$sql.= "size = '".$label->size."', ";}
	    if($label->color){$sql.= "color = '".$label->color->red." ".$label->color->green." ".$label->color->blue."', ";}
	    if($label->shadowcolor){$sql.= "shadowcolor = '".$label->shadowcolor->red." ".$label->shadowcolor->green." ".$label->shadowcolor->blue."', ";}
	    if($label->shadowsizex){$sql.= "shadowsizex = '".$label->shadowsizex."', ";}
	    if($label->shadowsizey){$sql.= "shadowsizey = '".$label->shadowsizey."', ";}
	    if($label->backgroundcolor){$sql.= "backgroundcolor = '".$label->backgroundcolor->red." ".$label->backgroundcolor->green." ".$label->backgroundcolor->blue."', ";}
	    if($label->backgroundshadowcolor){$sql.= "backgroundshadowcolor = '".$label->backgroundshadowcolor->red." ".$label->backgroundshadowcolor->green." ".$label->backgroundshadowcolor->blue."', ";}
	    if($label->backgroundshadowsizex){$sql.= "backgroundshadowsizex = '".$label->backgroundshadowsizex."', ";}
	    if($label->backgroundshadowsizey){$sql.= "backgroundshadowsizey = '".$label->backgroundshadowsizey."', ";}
	    if($label->outlinecolor){$sql.= "outlinecolor = '".$label->outlinecolor->red." ".$label->outlinecolor->green." ".$label->outlinecolor->blue."', ";}
	    if($label->position){$sql.= "position = '".$label->position."', ";}
	    if($label->offsetx){$sql.= "offsetx = '".$label->offsetx."', ";}
	    if($label->offsety){$sql.= "offsety = '".$label->offsety."', ";}
	    if($label->angle){$sql.= "angle = '".$label->angle."', ";}
	    if($label->autoangle){$sql.= "autoangle = '".$label->autoangle."', ";}
	    if($label->buffer){$sql.= "buffer = '".$label->buffer."', ";}
	    if($label->antialias){$sql.= "antialias = '".$label->antialias."', ";}
	    if($label->minfeaturesize){$sql.= "minfeaturesize = '".$label->minfeaturesize."', ";}
	    if($label->maxfeaturesize){$sql.= "maxfeaturesize = '".$label->maxfeaturesize."', ";}
	    if($label->partials){$sql.= "partials = '".$label->partials."', ";}
	    if($label->wrap){$sql.= "wrap = '".$label->wrap."', ";}
	    if($label->the_force){$sql.= "the_force = '".$label->the_force."', ";}
	    if($label->minsize){$sql.= "minsize = '".$label->minsize."', ";}
	    if($label->maxsize){$sql.= "maxsize = '".$label->maxsize."'";}
  	}
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Style - Erzeugen eines Styles:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    return mysql_insert_id();
  }

	function get_classes2label($label_id){
		$sql = 'SELECT class_id FROM u_labels2classes WHERE Label_ID = '.$label_id;
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_classes2label - Abfragen der Klassen, die ein Label benutzen:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $classes[]=$rs[0];
    }
    return $classes;
	}
  
  function addLabel2Class($class_id, $label_id){
    $sql = 'INSERT INTO u_labels2classes VALUES ('.$class_id.', '.$label_id.')';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->addLabel2Class - Hinzufügen eines Labels zu einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function removeLabel2Class($class_id, $label_id){
    $sql = 'DELETE FROM u_labels2classes WHERE class_id = '.$class_id.' AND label_id = '.$label_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->removeLabels2Class - Löschen eines Labels:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function getShapeByAttribute($layer,$attribut,$value) {
    $layer->queryByAttributes($attribut,$value,0);
    $result=$layer->getResult(0);
    if ($layer->getNumResults()==0) {
      return 0;
    }
    else {
      $layer->open();
      if(MAPSERVERVERSION > 500){
        $shape=$layer->getFeature($result->shapeindex,-1);
      }
      else{
        $shape=$layer->getShape(-1,$result->shapeindex);
      }
    }
    return $shape;
  }

  function getMaxMapExtent() {
    $rect=ms_newRectObj();
    $sql ='SELECT MIN(minxmax) AS minxmax, MIN(minymax) AS minymax';
    $sql.=', MAX(maxxmax) AS maxxmax, MAX(maxymax) AS maxymax FROM stelle';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getMaxMapExtent - Lesen der Maximalen Kartenausdehnung:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $rs=mysql_fetch_array($query);
    return $rs;
  }
}

###########################################
# Klasse zum Menüoptionen zusammenstellen #
###########################################
# Klasse Menue #
################

# functions of class menue
# load Menue -- load all menue items according to the stelle

class Menue {
  var $html;
  var $debug;

  ###################### Liste der Funktionen ####################################
  #
  # function Menue () - Construktor
  # function loadMenue ($Stelle_ID)
  # function getallMenues()
  #
  ################################################################################

  function menue ($language,$charset){
    global $debug;
    $this->debug=$debug;
    $this->language=$language;
    $this->charset=$charset;
  }

  function loadMenue($Stelle_ID, $User_ID) {
    $sql ='SELECT status, m.id, m.links, name as name_german,';
    if ($this->language != 'german') {
      $sql.=' `name_'.$this->language.'_'.$this->charset.'` AS ';
    }
    $sql.='name,m.menueebene,m.obermenue, m.target';
    $sql.=' FROM u_menue2rolle, u_menue2stelle AS m2s, u_menues AS m';
    $sql.=' WHERE m2s.stelle_id = u_menue2rolle.stelle_id AND m2s.stelle_id = '.$Stelle_ID;
    $sql.=' AND m2s.menue_id = m.id AND u_menue2rolle.menue_id = m2s.menue_id AND u_menue2rolle.user_id = '.$User_ID.' ORDER  BY m2s.menue_order';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Menue - Lesen der Menüangaben:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) {

    }
    else {
      while($rs=mysql_fetch_array($query)) {
        $this->Menueoption[]=$rs;
      }
    }
  }

  function get_menue_width($Stelle_ID){
    $sql ='SELECT r.width FROM referenzkarten AS r, stelle AS s WHERE r.ID=s.Referenzkarte_ID';
    $sql.=' AND s.ID='.$Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:Menue->get_menue_width - Lesen der Menuebreite:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_row($query);
    $this->width = $rs[0];
  }

  function getallOberMenues(){
    $sql.='SELECT id,';
    if ($this->language != 'german') {
      $sql.='`name_'.$this->language.'_'.$this->charset.'` AS ';
    }
    $sql.=' name, `order`, menueebene FROM u_menues WHERE menueebene = 1 ORDER BY `order`';
    $this->debug->write("<p>file:kvwmap class:Menue - Lesen aller OberMenüs:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) {

    }
    else {
    while($rs=mysql_fetch_array($query)) {
          $menues['ID'][]=$rs['id'];
          $menues['Bezeichnung'][]=$rs['name'];
          $menues['ORDER'][]=$rs['order'];
		  $menues['menueebene'][]=$rs['menueebene'];
      }
      return $menues;
    }
  }

  function getsubmenues($menue_id){
    $sql.='SELECT id,';
    if ($this->language != 'german') {
      $sql.='`name_'.$this->language.'_'.$this->charset.'` AS ';
    }
    $sql.=' name, `order`, menueebene FROM u_menues WHERE obermenue = '.$menue_id.' AND menueebene = 2 ORDER BY `order`, name';
    $this->debug->write("<p>file:kvwmap class:Menue - Lesen aller OberMenüs:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) {

    }
    else {
    while($rs=mysql_fetch_array($query)) {
          $menues['ID'][]=$rs['id'];
          $menues['Bezeichnung'][]=$rs['name'];
		  $menues['ORDER'][]=$rs['order'];
		  $menues['menueebene'][]=$rs['menueebene'];
      }
      return $menues;
    }
  }

}

class point {
  var $x;
  var $y;

  function point($x,$y) {
    $this->x=$x;
    $this->y=$y;
  }

  function pixel2welt($minX,$minY,$pixSize) {
    # Rechnet Pixel- in Weltkoordinaten um mit minx, miny und pixsize
    $this->x=($this->x*$pixSize)+$minX;
    $this->y=($this->y*$pixSize)+$minY;
  }

  function welt2pixel($minX,$minY,$pixSize) {
    # Rechnet Welt- in Pixelkoordinaten um mit minx, miny und pixsize
    $this->x=round(($this->x-$minX)/$pixSize);
    $this->y=round(($this->y-$minY)/$pixSize);
  }
}

###########################################
# Dokumentenklasse                        #
###########################################
# Klasse Document #
################

# functions of class Document

class Document {
  var $html;
  var $debug;
  var $head;
  var $headquery;

  ###################### Liste der Funktionen ####################################
  #
  # function Document() - Construktor
  # function load_heads()
  # function load_head($headid)
  # function save_head($formvars)
  # function update_head($formvars)
  # function save_active_head($id,$userid, $stelleid)
  # function get_active_headid($userid, $stelleid)
  # function get_head($userid, $stelleid)
  #
  ################################################################################

  function Document ($database){
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }

  function delete_ausschnitt($stelle_id, $user_id, $id){
    $sql = 'DELETE FROM druckausschnitte WHERE ';
    $sql.= 'stelle_id = '.$stelle_id.' AND ';
    $sql.= 'user_id = '.$user_id;
    if($id != ''){
      $sql.= ' AND id = '.$id;
    }
    $this->debug->write("<p>file:kvwmap class:Document->delete_ausschnitt :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_ausschnitt($stelle_id, $user_id, $name, $center_x, $center_y, $print_scale, $angle, $frame_id){
    $sql = 'INSERT INTO druckausschnitte SET ';
    $sql.= 'stelle_id = '.$stelle_id.', ';
    $sql.= 'user_id = '.$user_id.', ';
    $sql.= 'name = "'.$name.'", ';
    $sql.= 'center_x = '.$center_x.', ';
    $sql.= 'center_y = '.$center_y.', ';
    $sql.= 'print_scale = '.$print_scale.', ';
    $sql.= 'angle = '.$angle.', ';
    $sql.= 'frame_id = '.$frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->save_ausschnitt :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function load_ausschnitte($stelle_id, $user_id, $id){
    $sql = 'SELECT * FROM druckausschnitte WHERE ';
    $sql.= 'stelle_id = '.$stelle_id.' AND ';
    $sql.= 'user_id = '.$user_id;
    if($id != ''){
      $sql.= ' AND id = '.$id;
    }
    $this->debug->write("<p>file:kvwmap class:Document->load_ausschnitte :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $ausschnitte[] = $rs;
    }
    return $ausschnitte;
  }

  function load_frames($stelle_id, $frameid){
    $sql = 'SELECT DISTINCT druckrahmen.* FROM druckrahmen';
    if($frameid AND !$stelle_id){$sql .= ' WHERE druckrahmen.id ='.$frameid;}
    if($stelle_id AND !$frameid){
    	$sql.= ', druckrahmen2stelle WHERE druckrahmen2stelle.druckrahmen_id = druckrahmen.id';
    	$sql .= ' AND druckrahmen2stelle.stelle_id = '.$stelle_id;
    }
    if($frameid AND $stelle_id){
    	$sql.= ', druckrahmen2stelle WHERE druckrahmen2stelle.druckrahmen_id = druckrahmen.id';
    	$sql .= ' AND druckrahmen2stelle.stelle_id = '.$stelle_id;
    	$sql .= ' AND druckrahmen.id ='.$frameid;
    }
    $sql .= ' ORDER BY Name';
    #echo $sql.'<br>';
    $this->debug->write("<p>file:kvwmap class:Document->load_frames :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($query)){
      $frames[] = $rs;
      $frames[0]['bilder'] = $this->load_bilder($rs['id']);
      $frames[0]['texts'] = $this->load_texts($rs['id']);
      $i++;
    }
    return $frames;
  }

  function load_texts($frame_id){
    $sql = 'SELECT druckfreitexte.* FROM druckrahmen, druckfreitexte, druckrahmen2freitexte';
    $sql.= ' WHERE druckrahmen2freitexte.druckrahmen_id = '.$frame_id;
    $sql.= ' AND druckrahmen2freitexte.druckrahmen_id = druckrahmen.id';
    $sql.= ' AND druckrahmen2freitexte.freitext_id = druckfreitexte.id';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->load_texts :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $texts[] = $rs;
    }
    return $texts;
  }

  function load_bilder($frame_id){
    $sql = 'SELECT b.src,r2b.posx,r2b.posy,r2b.width,r2b.height,r2b.angle';
    $sql.= ' FROM druckrahmen AS r, druckfreibilder AS b, druckrahmen2freibilder AS r2b';
    $sql.= ' WHERE r.id = r2b.druckrahmen_id';
    $sql.= ' AND b.id = r2b.freibild_id';
    $sql.= ' AND r.id = '.$frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->load_bilder :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $bilder[] = $rs;
    }
    return $bilder;
  }

  function addfreetext($formvars){
    $sql = 'INSERT INTO druckfreitexte SET';
    $sql .= ' text = "",';
    $sql .= ' posx = 0,';
    $sql .= ' posy = 0,';
    $sql .= ' size = 0,';
    $sql .= ' font = "",';
    $sql .= ' angle = 0';
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $lastinsert_id = mysql_insert_id();
    $sql = 'INSERT INTO druckrahmen2freitexte (druckrahmen_id, freitext_id) VALUES('.$formvars['aktiverRahmen'].', '.$lastinsert_id.')';
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removefreetext($formvars){
    $sql = 'DELETE FROM druckfreitexte WHERE id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:Document->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $sql = 'DELETE FROM druckrahmen2freitexte WHERE freitext_id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:Document->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_price($format){
    $sql ='SELECT preis FROM druckrahmen WHERE `format` = \''.$format.'\'';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->get_price :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);

    return $rs[0];
  }

  function delete_frame($selected_frame_id){
 //   $sql ="DELETE FROM druckrahmen WHERE id = ".$selected_frame_id;
 //   $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
 //   $this->database->execSQL($sql,4, 1);
    $sql ="DELETE FROM druckrahmen2stelle WHERE druckrahmen_id = ".$selected_frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_frame($formvars, $_files, $stelle_id){
    if($formvars['Name']){
      $frames = $this->load_frames($this->Stelle->id, NULL);
      for($i = 0; $i < count($frames); $i++){
        if($frames[$i]['Name'] == $formvars['Name']){
          $this->Document->fehlermeldung = 'Name schon vergeben';
        return;
        }
      }
      $formvars['cent'] = str_pad ($formvars['cent'], 2, "0", STR_PAD_RIGHT);
      $preis = $formvars['euro'] * 100 + $formvars['cent'];

      $sql = "INSERT INTO `druckrahmen`";
      $sql .= " SET `Name` = '".$formvars['Name']."'";
      $sql .= ", `headposx` = ".$formvars['headposx'];
      $sql .= ", `headposy` = ".$formvars['headposy'];
      $sql .= ", `headwidth` = ".$formvars['headwidth'];
      $sql .= ", `headheight` = ".$formvars['headheight'];
      $sql .= ", `mapposx` = ".$formvars['mapposx'];
      $sql .= ", `mapposy` = ".$formvars['mapposy'];
      $sql .= ", `mapwidth` = ".$formvars['mapwidth'];
      $sql .= ", `mapheight` = ".$formvars['mapheight'];
      if($formvars['refmapposx']){$sql .= ", `refmapposx` = ".$formvars['refmapposx'];}
      if($formvars['refmapposy']){$sql .= ", `refmapposy` = ".$formvars['refmapposy'];}
      if($formvars['refmapwidth']){$sql .= ", `refmapwidth` = ".$formvars['refmapwidth'];}
      if($formvars['refmapheight']){$sql .= ", `refmapheight` = ".$formvars['refmapheight'];}
      if($formvars['refposx']){$sql .= ", `refposx` = ".$formvars['refposx'];}
      if($formvars['refposy']){$sql .= ", `refposy` = ".$formvars['refposy'];}
      if($formvars['refwidth']){$sql .= ", `refwidth` = ".$formvars['refwidth'];}
      if($formvars['refheight']){$sql .= ", `refheight` = ".$formvars['refheight'];}
      if($formvars['refzoom']){$sql .= ", `refzoom` = ".$formvars['refzoom'];}
      if($formvars['dateposx']){$sql .= ", `dateposx` = ".$formvars['dateposx'];}
      if($formvars['dateposy']){$sql .= ", `dateposy` = ".$formvars['dateposy'];}
      if($formvars['datesize']){$sql .= ", `datesize` = ".$formvars['datesize'];}
      if($formvars['scaleposx']){$sql .= ", `scaleposx` = ".$formvars['scaleposx'];}
      if($formvars['scaleposy']){$sql .= ", `scaleposy` = ".$formvars['scaleposy'];}
      if($formvars['scalesize']){$sql .= ", `scalesize` = ".$formvars['scalesize'];}
      if($formvars['oscaleposx']){$sql .= ", `oscaleposx` = ".$formvars['oscaleposx'];}
      if($formvars['oscaleposy']){$sql .= ", `oscaleposy` = ".$formvars['oscaleposy'];}
      if($formvars['oscalesize']){$sql .= ", `oscalesize` = ".$formvars['oscalesize'];}
      if($formvars['gemarkungposx']){$sql .= ", `gemarkungposx` = ".$formvars['gemarkungposx'];}
      if($formvars['gemarkungposy']){$sql .= ", `gemarkungposy` = ".$formvars['gemarkungposy'];}
      if($formvars['gemarkungsize']){$sql .= ", `gemarkungsize` = ".$formvars['gemarkungsize'];}
      if($formvars['flurposx']){$sql .= ", `flurposx` = ".$formvars['flurposx'];}
      if($formvars['flurposy']){$sql .= ", `flurposy` = ".$formvars['flurposy'];}
      if($formvars['flursize']){$sql .= ", `flursize` = ".$formvars['flursize'];}
      if($formvars['legendposx']){$sql .= ", `legendposx` = ".$formvars['legendposx'];}
      if($formvars['legendposy']){$sql .= ", `legendposy` = ".$formvars['legendposy'];}
      if($formvars['legendsize']){$sql .= ", `legendsize` = ".$formvars['legendsize'];}
      if($formvars['arrowposx']){$sql .= ", `arrowposx` = ".$formvars['arrowposx'];}
      if($formvars['arrowposy']){$sql .= ", `arrowposy` = ".$formvars['arrowposy'];}
      if($formvars['arrowlength']){$sql .= ", `arrowlength` = ".$formvars['arrowlength'];}
      if($formvars['userposx']){$sql .= ", `userposx` = '".$formvars['userposx']."'";}
      if($formvars['userposy']){$sql .= ", `userposy` = '".$formvars['userposy']."'";}
      if($formvars['usersize']){$sql .= ", `usersize` = '".$formvars['usersize']."'";}
      if($formvars['watermark']){$sql .= ", `watermark` = '".$formvars['watermark']."'";}
      if($formvars['watermarkposx']){$sql .= ", `watermarkposx` = ".$formvars['watermarkposx'];}
      if($formvars['watermarkposy']){$sql .= ", `watermarkposy` = ".$formvars['watermarkposy'];}
      if($formvars['watermarksize']){$sql .= ", `watermarksize` = ".$formvars['watermarksize'];}
      if($formvars['watermarkangle']){$sql .= ", `watermarkangle` = ".$formvars['watermarkangle'];}
      if($formvars['watermarktransparency']){$sql .= ", `watermarktransparency` = '".$formvars['watermarktransparency']."'";}
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", `variable_freetexts` = ".$formvars['variable_freetexts'];
      if($formvars['format']){$sql .= ", `format` = '".$formvars['format']."'";}
      if($preis){$sql .= ", `preis` = '".$preis."'";}
      if($formvars['font_date']){$sql .= ", `font_date` = '".$formvars['font_date']."'";}
      if($formvars['font_scale']){$sql .= ", `font_scale` = '".$formvars['font_scale']."'";}
      if($formvars['font_gemarkung']){$sql .= ", `font_gemarkung` = '".$formvars['font_gemarkung']."'";}
      if($formvars['font_flur']){$sql .= ", `font_flur` = '".$formvars['font_flur']."'";}
      if($formvars['font_legend']){$sql .= ", `font_legend` = '".$formvars['font_legend']."'";}
      if($formvars['font_user']){$sql .= ", `font_user` = '".$formvars['font_user']."'";}
      if($formvars['font_watermark']){$sql .= ", `font_watermark` = '".$formvars['font_watermark']."'";}

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `headsrc` = '".$_files['headsrc']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `headsrc` = '".$formvars['headsrc_save']."'";
      }
      if($_files['refmapsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapsrc']['name'];
        if (move_uploaded_file($_files['refmapsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapsrc` = '".$_files['refmapsrc']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `refmapsrc` = '".$formvars['refmapsrc_save']."'";
      }
      if($_files['refmapfile']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapfile']['name'];
        if (move_uploaded_file($_files['refmapfile']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapfile` = '".$_files['refmapfile']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `refmapfile` = '".$formvars['refmapfile_save']."'";
      }
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);
      $lastdruckrahmen_id = mysql_insert_id();

      $sql = 'INSERT INTO druckrahmen2stelle (stelle_id, druckrahmen_id) VALUES('.$stelle_id.', '.$lastdruckrahmen_id.')';
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "INSERT INTO druckfreitexte SET `text` = '".$formvars['text'.$i]."'";
        $sql .= ", `posx` = ".$formvars['textposx'.$i];
        $sql .= ", `posy` = ".$formvars['textposy'.$i];
        $sql .= ", `size` = ".$formvars['textsize'.$i];
        $sql .= ", `angle` = ".$formvars['textangle'.$i];
        $sql .= ", `font` = '".$formvars['textfont'.$i]."'";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
        $lastfreitext_id = mysql_insert_id();

        $sql = 'INSERT INTO druckrahmen2freitexte (druckrahmen_id, freitext_id) VALUES('.$lastdruckrahmen_id.', '.$lastfreitext_id.')';
        $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
    return $lastdruckrahmen_id;
  }

  function update_frame($formvars, $_files){
    if($formvars['Name']){
      $formvars['cent'] = str_pad ($formvars['cent'], 2, "0", STR_PAD_RIGHT);
      $preis = $formvars['euro'] * 100 + $formvars['cent'];

      $sql ="UPDATE `druckrahmen`";
      $sql .= " SET `Name` = '".$formvars['Name']."'";
      $sql .= ", `headposx` = '".$formvars['headposx']."'";
      $sql .= ", `headposy` = '".$formvars['headposy']."'";
      $sql .= ", `headwidth` = '".$formvars['headwidth']."'";
      $sql .= ", `headheight` = '".$formvars['headheight']."'";
      $sql .= ", `mapposx` = '".$formvars['mapposx']."'";
      $sql .= ", `mapposy` = '".$formvars['mapposy']."'";
      $sql .= ", `mapwidth` = '".$formvars['mapwidth']."'";
      $sql .= ", `mapheight` = '".$formvars['mapheight']."'";
      $sql .= ", `refmapposx` = '".$formvars['refmapposx']."'";
      $sql .= ", `refmapposy` = '".$formvars['refmapposy']."'";
      $sql .= ", `refmapwidth` = '".$formvars['refmapwidth']."'";
      $sql .= ", `refmapheight` = '".$formvars['refmapheight']."'";
      $sql .= ", `refposx` = '".$formvars['refposx']."'";
      $sql .= ", `refposy` = '".$formvars['refposy']."'";
      $sql .= ", `refwidth` = '".$formvars['refwidth']."'";
      $sql .= ", `refheight` = '".$formvars['refheight']."'";
      $sql .= ", `refzoom` = '".$formvars['refzoom']."'";
      $sql .= ", `dateposx` = '".$formvars['dateposx']."'";
      $sql .= ", `dateposy` = '".$formvars['dateposy']."'";
      $sql .= ", `datesize` = '".$formvars['datesize']."'";
      $sql .= ", `scaleposx` = '".$formvars['scaleposx']."'";
      $sql .= ", `scaleposy` = '".$formvars['scaleposy']."'";
      $sql .= ", `scalesize` = '".$formvars['scalesize']."'";
      $sql .= ", `oscaleposx` = '".$formvars['oscaleposx']."'";
      $sql .= ", `oscaleposy` = '".$formvars['oscaleposy']."'";
      $sql .= ", `oscalesize` = '".$formvars['oscalesize']."'";
      $sql .= ", `gemarkungposx` = '".$formvars['gemarkungposx']."'";
      $sql .= ", `gemarkungposy` = '".$formvars['gemarkungposy']."'";
      $sql .= ", `gemarkungsize` = '".$formvars['gemarkungsize']."'";
      $sql .= ", `flurposx` = '".$formvars['flurposx']."'";
      $sql .= ", `flurposy` = '".$formvars['flurposy']."'";
      $sql .= ", `flursize` = '".$formvars['flursize']."'";
      $sql .= ", `legendposx` = '".$formvars['legendposx']."'";
      $sql .= ", `legendposy` = '".$formvars['legendposy']."'";
      $sql .= ", `legendsize` = '".$formvars['legendsize']."'";
      $sql .= ", `arrowposx` = '".$formvars['arrowposx']."'";
      $sql .= ", `arrowposy` = '".$formvars['arrowposy']."'";
      $sql .= ", `arrowlength` = '".$formvars['arrowlength']."'";      
      $sql .= ", `userposx` = '".$formvars['userposx']."'";
      $sql .= ", `userposy` = '".$formvars['userposy']."'";
      $sql .= ", `usersize` = '".$formvars['usersize']."'";
      $sql .= ", `watermark` = '".$formvars['watermark']."'";
      $sql .= ", `watermarkposx` = '".$formvars['watermarkposx']."'";
      $sql .= ", `watermarkposy` = '".$formvars['watermarkposy']."'";
      $sql .= ", `watermarksize` = '".$formvars['watermarksize']."'";
      $sql .= ", `watermarkangle` = '".$formvars['watermarkangle']."'";
      $sql .= ", `watermarktransparency` = '".$formvars['watermarktransparency']."'";
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", `variable_freetexts` = ".$formvars['variable_freetexts'];
      $sql .= ", `format` = '".$formvars['format']."'";
      $sql .= ", `preis` = '".$preis."'";
      $sql .= ", `font_date` = '".$formvars['font_date']."'";
      $sql .= ", `font_scale` = '".$formvars['font_scale']."'";
      $sql .= ", `font_gemarkung` = '".$formvars['font_gemarkung']."'";
      $sql .= ", `font_flur` = '".$formvars['font_flur']."'";
      $sql .= ", `font_legend` = '".$formvars['font_legend']."'";
      $sql .= ", `font_user` = '".$formvars['font_user']."'";
      $sql .= ", `font_watermark` = '".$formvars['font_watermark']."'";

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `headsrc` = '".$_files['headsrc']['name']."'";
          #echo $sql;
        }
        else {
            //echo '<br>Datei: '.$_files['Wappen']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
          }
      }
      if($_files['refmapsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapsrc']['name'];
        if (move_uploaded_file($_files['refmapsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapsrc` = '".$_files['refmapsrc']['name']."'";
          #echo $sql;
        }
        else {
            //echo '<br>Datei: '.$_files['Wappen']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
          }
      }
      if($_files['refmapfile']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapfile']['name'];
        if (move_uploaded_file($_files['refmapfile']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapfile` = '".$_files['refmapfile']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      $sql .= " WHERE `id` =".(int)$formvars['aktiverRahmen'];
      $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "UPDATE druckfreitexte SET `text` = '".$formvars['text'.$i]."'";
        $sql .= ", `posx` = ".$formvars['textposx'.$i];
        $sql .= ", `posy` = ".$formvars['textposy'.$i];
        $sql .= ", `size` = ".$formvars['textsize'.$i];
        $sql .= ", `angle` = ".$formvars['textangle'.$i];
        $sql .= ", `font` = '".$formvars['textfont'.$i]."'";
        $sql .= " WHERE id = ".$formvars['text_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
  }

  function add_frame2stelle($id, $stelleid){
    $sql ="INSERT IGNORE INTO druckrahmen2stelle VALUES (".$stelleid.", ".$id.")";
    $this->debug->write("<p>file:kvwmap class:Document->add_frame2stelle :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removeFrames($stelleid){
    $sql ="DELETE FROM druckrahmen2stelle WHERE stelle_id = ".$stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->removeFrames :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_active_frame($id, $userid, $stelleid){
    $sql ="UPDATE `rolle` SET `active_frame` = '".$id."' WHERE `user_id` =".$userid." AND `stelle_id` =".$stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->save_active_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_active_frameid($userid, $stelleid){
    $sql ='SELECT active_frame from rolle, druckrahmen WHERE active_frame = druckrahmen.id AND `user_id` ='.$userid.' AND `stelle_id` ='.$stelleid;
    $this->debug->write("<p>file:kvwmap class:GUI->get_active_frameid :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs[0];
  }
}
?>
