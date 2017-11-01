<?php
header('Content-Type: text/html; charset=utf-8');
session_set_cookie_params(0, $_SERVER['CONTEXT_PREFIX']);
session_start();
###################################################################
# kvwmap - Kartenserver für die Verwaltung raumbezogener Daten.   #
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2016  Peter Korduan                               #
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
# Eine deutsche Übersetzung zur Lizenz finden Sie unter:          #
# http://www.gnu.de/gpl-ger.html                                  #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
// function microtime_float1(){
   // list($usec, $sec) = explode(" ", microtime());
   // return ((float)$usec + (float)$sec);
// }
// $starttime = $executiontimes['time'][] = microtime_float1();
// $executiontimes['action'][] = 'Start';

ob_start ();    // Ausgabepufferung starten

/*
* Replace in $tags defined tags from $text recursively
*/
function replace_tags($text, $tags) {
	$new_text = preg_replace("#<\s*\/?(" . $tags . ")\s*[^>]*?>#im", '', $text);
	if ($new_text != $text) $new_text = replace_tags($new_text, $tags);

	return $new_text;
}

foreach($_REQUEST as $key => $value) {
	if (is_string($value)) $_REQUEST[$key] = pg_escape_string(replace_tags($value, 'script|embed'));
}
$formvars = $_REQUEST;

$go = $formvars['go'];
if($formvars['go_plus'] != '') $go = $go.'_'.$formvars['go_plus'];
###########################################################################################################
define(CASE_COMPRESS, false);																																						  #
#																																																					#
#		ALLE:						  - die Stelle muss die IP checken  																								  #
#											- die Stelle muss das Passwortalter checken																					#
#																																																				  #
# 	navMap_ajax: 		  - LAGEBEZEICHNUNGSART muss auf 'Flurbezeichnung' gesetzt sein												#
#											- man muss über den Rechteckzoom unter 1:100 zoomen (kein Mausrad!)									#
#										  - ein räumlich gefilterter Layer muss an sein																				#
#										  - man muss einen anderen EPSG-Code als den der Ref-Karte (2398) eingestellt haben		#
# 	tooltip_query:	  - ein Datensatz mit Bild muss agefragt werden																			  #
#										  - getRollenLayer() reinkopieren																										  #
#   getLayerOptions:  - getRollenLayer() reinkopieren																											#
#																																																				  #
#																																																				  #
###########################################################################################################

$non_spatial_cases = array('getLayerOptions', 'get_select_list');		// für non-spatial cases wird in start.php keine Verbindung zur PostgreSQL aufgebaut usw.
$spatial_cases = array('navMap_ajax', 'tooltip_query', 'get_group_legend');
$fast_loading_cases = array_merge($spatial_cases, $non_spatial_cases);

if(in_array($go, $fast_loading_cases))define(FAST_CASE, true);else define(FAST_CASE, false);

include('config.php');
include(CLASSPATH.'log.php');
if(CASE_COMPRESS)	include(CLASSPATH.'case_compressor.php');

if(DEBUG_LEVEL>0) $debug=new Debugger(DEBUGFILE);	# öffnen der Debug-log-datei
# Öffnen der Log-Dateien. Derzeit werden in den Log-Dateien nur die SQL-Statements gespeichert, die über execSQL in den Klassen mysql und postgres ausgeführt werden.
if (LOG_LEVEL>0) {
 $log_mysql=new LogFile(LOGFILE_MYSQL,'text','Log-Datei MySQL', '#------v: '.date("Y:m:d H:i:s",time()));
 $log_postgres=new LogFile(LOGFILE_POSTGRES,'text', 'Log-Datei-Postgres', '------v: '.date("Y:m:d H:i:s",time()));
}
if(!$_SESSION['angemeldet'] or !empty($formvars['username'])){
	$msg .= '<br>Nicht angemeldet';
	include(CLASSPATH . 'mysql.php');
	$userDb = new database();
	$userDb->host = MYSQL_HOST;
	$userDb->user = MYSQL_USER;
	$userDb->passwd = MYSQL_PASSWORD;
	$userDb->dbName = MYSQL_DBNAME;
	header('logout: true');		// damit ajax-Requests das auch mitkriegen
	if (file_exists(LAYOUTPATH . 'snippets/' . LOGIN)) {
		include(LAYOUTPATH . 'snippets/' . LOGIN);
	}
	else {
		include(LAYOUTPATH . 'snippets/login.php');
	}
}

function include_($filename){
	if(CASE_COMPRESS AND FAST_CASE){		// ein fast-case und er soll komprimiert werden
		$filename = case_compressor::inject($filename);
		include $filename;		
		unlink($filename);
	}
	elseif(FAST_CASE){}				// nix inkludieren, denn die fast-case-Datei enthält ja schon alles
	else include_once $filename;		// normaler include
}

# laden der Klassenbibliotheken
if(!CASE_COMPRESS AND FAST_CASE){
	include (CLASSPATH.'fast_cases/'.$go.'.php');
}
else{
	include_(WWWROOT.APPLVERSION.'funktionen/allg_funktionen.php');
	if($userDb == NULL)include_(CLASSPATH.'mysql.php');
	include_(CLASSPATH . 'kvwmap.php');
	include_(CLASSPATH . 'Menue.php');
	include_(CLASSPATH . 'kataster.php');
	include_(CLASSPATH . 'postgresql.php');
	include_(CLASSPATH . 'users.php');
	include_(CLASSPATH . 'rolle.php');
	include_(CLASSPATH . 'stelle.php');
	include_(CLASSPATH . 'bauleitplanung.php');
}

include(WWWROOT . APPLVERSION.'start.php');

# Übergeben des Anwendungsfalles
$debug->write("<br><b>Anwendungsfall go: ".$go."</b>",4);
$GUI->go=$go;
$GUI->requeststring = $QUERY_STRING;

if(!FAST_CASE)$GUI->loadPlugins();
if(FAST_CASE OR $GUI->goNotExecutedInPlugins){
	if($go == 'get_last_query'){
		$GUI->last_query = $GUI->user->rolle->get_last_query();
		$GUI->last_query_requested = true;		# get_last_query wurde direkt aufgerufen
		$GUI->formvars['go'] = $go = $GUI->last_query['go'];
	}
	#echo '<br>go: ' . $go;
	switch($go){
		case 'navMap_ajax' : {   
      $GUI->formvars['nurAufgeklappteLayer'] = true;		
      $GUI->loadMap('DataBase');
      $GUI->navMap($GUI->formvars['CMD']);
      $GUI->saveMap('');    			
      $currenttime=date('Y-m-d H:i:s',time());
      $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
      $GUI->drawMap();    
      $GUI->mime_type='map_ajax';
      $GUI->output();
		}break;

	  case 'show_snippet' : {
			$GUI->checkCaseAllowed($go);
			$GUI->show_snippet();
	  } break;

		case 'openCustomSubform' : {
			$GUI->openCustomSubform();
	  } break;

		case 'getLayerOptions' : {
			$GUI->getLayerOptions();
	  } break;

		case 'getGroupOptions' : {
			$GUI->getGroupOptions();
	  } break;

		case 'saveLayerOptions' : {
			$GUI->saveLayerOptions();
			$GUI->neuLaden();
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
	  } break;
		
		case 'resetLayerOptions' : {
			$GUI->resetLayerOptions();
			$GUI->neuLaden();
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
	  } break;
				
		case 'toggle_gle_view' : {
			$GUI->switch_gle_view();
	  } break;
				
		case 'setHistTimestamp' : {
			$GUI->setHistTimestamp();
	  } break;
		
		case 'setLanguage' : {
			$GUI->setLanguage();
	  } break;
		
		case 'getNBH' : {
			$GUI->getNBH();
		}break;

		case 'setLayerParams' : {
			$GUI->setLayerParams();
			echo "onLayerParamsUpdated('success')";
		} break;

		case 'changemenue' : {
			$GUI->changemenue($GUI->formvars['id'], $GUI->formvars['status']);
			$GUI->loadMap('DataBase');
			$GUI->drawMap();
			$GUI->output();
	  } break;

	  case 'changemenue_with_ajax' : {
			$GUI->changemenue_with_ajax($GUI->formvars['id'], $GUI->formvars['status']);
	  } break;

	  case 'getMenueWithAjax' : {
			$GUI->getMenueWithAjax();
	  } break;

	  case 'hideMenueWithAjax' : {
			$GUI->hideMenueWithAjax();
	  } break;
	
		# Legende für eine Gruppe erzeugen
	  case 'get_group_legend' : {
			$GUI->get_group_legend();
	  } break;

	  case 'close_group_legend' : {
			$GUI->close_group_legend();
	  } break;
		
		# Legende erzeugen
	  case 'get_legend' : {
			$GUI->loadMap('DataBase');
			echo $GUI->create_dynamic_legend();
	  } break;
	
		case 'autocomplete_request' :{
			$GUI->autocomplete_request();
		}break;
		
		case 'get_quicksearch_attributes' : {
			$GUI->get_quicksearch_attributes();
	  } break;
  	 
		case 'Multi_Geometrien_splitten' : {
		  $GUI->split_multi_geometries();
		}break;

		case 'reset_layers' : {
			$GUI->reset_layers($GUI->formvars['layer_id']);
			$GUI->loadMap('DataBase');
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
	  } break;

		case 'show_all_layers' : {
			$GUI->user->rolle->update_layer_status(NULL, '1');
			$GUI->loadMap('DataBase');
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
	  } break;

	  case 'reset_querys' : {
			$GUI->reset_querys();
			$GUI->loadMap('DataBase');
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
	  } break;

		case 'zoom2coord' : {
			$GUI->loadMap('DataBase');
			$GUI->zoom2coord();
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
		} break;
		
		case 'zoom2wkt' : {
			$GUI->loadMap('DataBase');
			$GUI->zoom2wkt();
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
		} break;

		case 'getSVG_vertices' : {
			$GUI->getSVG_vertices();
	  } break;
	  
	  case 'getSVG_foreign_vertices' : {
			$GUI->getSVG_foreign_vertices();
	  } break;

		case 'ResizeMap2Window' : {
			$GUI->resizeMap2Window();
			if($GUI->formvars['reloadmap']){
				$GUI->loadMap('DataBase');
				$GUI->scaleMap($GUI->formvars['nScale']);
				$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
				$GUI->drawMap();
				$GUI->saveMap('');
				$GUI->output();
			}
	  } break;

	  # auslesen der Layer vom mobilen Client
	  case 'import_layer' : {
		$GUI->checkCaseAllowed($go);
		$GUI->import_layer();
	  } break;

	  # auslesen der Layer vom mobilen Client
	  case 'import_layer_importieren' : {
		$GUI->import_layer_importieren();
	  } break;

	  # auslesen der Layer von der Primärdatenbank
	  case 'export_layer' : {
		$GUI->checkCaseAllowed($go);
		$GUI->export_layer();
	  } break;

	  # auslesen der Layer von der Primärdatenbank
	  case 'export_layer_einlesen' : {
		$GUI->export_layer_exportieren();
	  } break;

	  # liefert die options für ein Selectfeld für abhängige Attribute
	  case 'get_select_list' : {
			$GUI->get_select_list();
	  } break;
		
		# liefert einen automatisch generierten Vorschlag der sich aus einem in options stehenden SQL ergibt
	  case 'auto_generate' : {
			$GUI->auto_generate();
	  } break;
	  
	  # Kartenbild anzeigen
	  case 'showMapImage' : {
		$GUI->showMapImage();
	  } break;

	  # Klassen abfragen
	  case 'getclasses' : {
		$GUI->get_classes();
	  } break;

	  # Styles und Labels abfragen
	  case 'getstyles_labels' : {
		$GUI->get_styles_labels();
	  } break;

	  # Style abfragen
	  case 'get_style' : {
		$GUI->get_style();
	  } break;

	  # Style speichern
	  case 'save_style' : {
		$GUI->save_style();
	  } break;

	  # Style löschen
	  case 'delete_style' : {
		$GUI->delete_style();
	  } break;

	  # neuen Style hinzufügen
	  case 'add_style' : {
		$GUI->add_style();
		$GUI->get_styles();
	  } break;
	  
	  # Style in der Drawingorder nach oben verschieben
	  case 'moveup_style' : {
		$GUI->moveup_style();
	  } break;
	  
	  # Style in der Drawingorder nach unten verschieben
	  case 'movedown_style' : {
		$GUI->movedown_style();
	  } break;

	  # Label abfragen
	  case 'get_label' : {
		$GUI->get_label();
	  } break;

	  # Label speichern
	  case 'save_label' : {
		$GUI->save_label();
	  } break;

	  # Label Löschen
	  case 'delete_label' : {
		$GUI->delete_label();
	  } break;

	  # neues Label hinzufügen
	  case 'add_label' : {
		$GUI->add_label();
	  } break;

	  # Untermenues abfragen
	  case 'getsubmenues' : {
		$GUI->get_sub_menues();
	  } break;
	  
	  # Layer zu einer Gruppe abfragen
	  case 'getlayerfromgroup' : {
		$GUI->getlayerfromgroup();
	  } break;

	  # GPS-Position auslesen
	  case 'get_gps_position' : {
		$GUI->get_gps_position();
	  } break;

	  # Eigentuemerfortführung
	  case 'Adressaenderungen_Export' : {
		$GUI->checkCaseAllowed($go);
		$GUI->export_Adressaenderungen();
	  } break;

	  # Eigentuemerfortführung
	  case 'Adressaenderungen_Export_Exportieren' : {
			$GUI->checkCaseAllowed('Adressaenderungen_Export');
			$GUI->export_Adressaenderungen_exportieren();
	  } break;
	  
	  case 'exportWMC' :{
		  $GUI->exportWMC();
		} break;

	  case 'Externer_Druck' : {
			$GUI->checkCaseAllowed($go);
			$GUI->formvars['loadmapsource'] = 'Post';
			$GUI->druckausschnittswahl($GUI->formvars['loadmapsource']);
	  } break;

	  case 'Externer_Druck_Drucken' : {
			$GUI->createMapPDF($GUI->formvars['aktiverRahmen'], false);
			$GUI->mime_type='pdf';
			$GUI->output();
	  } break;

	  case 'logout' : {
			session_start();
			$_SESSION = array();
			if (ini_get("session.use_cookies")){
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
			}
			session_destroy();
			$locationStr = 'index.php' . ($_REQUEST['gast'] != '' ? '?gast=' . $_REQUEST['gast'] : '');
			if (isset($newPassword)) {
				$locationStr.='?newPassword='.$newPassword;
				$locationStr.='&msg='.$GUI->Fehlermeldung;
				$locationStr.='&passwort='.$passwort;
				$locationStr.='&username='.$username;
			}
			header('Location: ' . $locationStr);
	  } break;

	  case 'Flurstuecks-CSV-Export' : {
			$GUI->export_flurst_csv();
	  } break;
	  
	  case 'Flurstuecks-CSV-Export_Auswahl_speichern' : {
			$GUI->export_flurst_csv_auswahl_speichern();
	  } break;
	  
	  case 'Flurstuecks-CSV-Export_Auswahl_laden' : {
			$GUI->export_flurst_csv_auswahl_laden();
	  } break;
	  
	  case 'Flurstuecks-CSV-Export_Auswahl_loeschen' : {
			$GUI->export_flurst_csv_auswahl_loeschen();
	  } break;
	  
	  case 'Flurstuecks-CSV-Export_Exportieren' : {
			$GUI->export_flurst_csv_exportieren();
	  } break;
		
	  # PointEditor
	  case 'PointEditor' : {
			$GUI->PointEditor();
	  }break;

	  # PointEditor
	  case 'PointEditor_Senden' : {
			$GUI->PointEditor_Senden();
	  }break;

	  case 'zoomto_selected_datasets' : {
			$GUI->zoomto_selected_datasets();
	  }break;

	  # zoomToPoint
	  case 'zoomtoPoint' : {
			if($GUI->formvars['mime_type'] != '')$GUI->mime_type = $GUI->formvars['mime_type'];
			$GUI->zoom_toPoint();
	  }break;

	  # zoomToPolygon
	  case 'zoomtoPolygon' : {
			if($GUI->formvars['mime_type'] != '')$GUI->mime_type = $GUI->formvars['mime_type'];
			$GUI->zoom_toPolygon();
	  }break;
	  
	  # zoomToLine
	  case 'zoomtoLine' : {
			if($GUI->formvars['mime_type'] != '')$GUI->mime_type = $GUI->formvars['mime_type'];
			$GUI->zoom_toLine();
	  }break;

	  # PolygonEditor
	  case 'PolygonEditor' : {
		$GUI->PolygonEditor();
	  }break;

	  # PolygonEditor
	  case 'PolygonEditor_Senden' : {
		$GUI->PolygonEditor_Senden();
	  }break;

	  # LineEditor
	  case 'LineEditor' : {
		$GUI->LineEditor();
	  }break;

	  # LineEditor
	  case 'LineEditor_Senden' : {
		$GUI->LineEditor_Senden();
	  }break;

	  # Sachdaten speichern
	  case 'Sachdaten_speichern' : {
		$GUI->sachdaten_speichern();
	  }break;

	  # Sachdaten anzeigen
	  case 'Sachdaten' : {
			if($GUI->formvars['CMD'] != '')$GUI->user->rolle->setSelectedButton($GUI->formvars['CMD']);
			if($GUI->formvars['legendtouched'])$GUI->saveLegendRoleParameters();			
			$GUI->queryMap();
	  }break;

	  # gibt die Koordinaten des in der Variable FlurstKennz übergebenen Flurstückes aus
	  case 'showFlurstuckKoordinaten' : {
		$GUI->showFlurstueckKoordinaten();
	  } break;

	  # Export der geloggten Zugriffe in eine Georg-Datei
	  case 'georg_export' : {
		$GUI->export_georg($GUI->formvars);
	  }break;

	  # Bauleitplanungsänderung
	  case 'bauleitplanung' : {
		$GUI->bauleitplanung();
	  }break;

	  # Bauleitplanungsänderung
	  case 'bauleitplanung_Senden' : {
		$GUI->bauleitplanungSenden();
	  }break;

	  # Bauleitplanungsänderung
	  case 'bauleitplanung_Loeschen' : {
		$GUI->bauleitplanungLoeschen();
		$GUI->loadMap('DataBase');
		$currenttime=date('Y-m-d H:i:s',time());
		$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
		$GUI->drawMap();
		$GUI->output();
	  }break;

	  # spatial processing include
	  case 'spatial_processing' : {
		$GUI->spatial_processing();
	  }break;

	  # Abfrage einer Zeile in der MySQL Datenbank
	  # Beliebige Tabelle, Einschränkung über c1,c2,c3 und v1,v2,v3 (Werte beliebig)
	  case 'getRow' : {
		# Derzeit nur für die Tabelle Rolle
		if ($GUI->formvars['from'] == 'rolle') {
		  $GUI->getRow();
		}
	  } break;

	  # layer aus mapfile laden
	  case 'layerfrommapfile_Layer hinzufügen' : {
		$GUI->layerfromMapfile_addlayer($GUI->formvars);
		$GUI->output();
	  }break;

	  # layer aus mapfile laden
	  case 'layerfrommapfile' : {
		$GUI->checkCaseAllowed($go);
		$GUI->layerfromMapfile();
		$GUI->output();
	  }break;

	  # layer aus mapfile laden
	  case 'layerfrommapfile_Datei laden' : {
		$GUI->layerfromMapfile_load($GUI->formvars);
		$GUI->output();
	  }break;

		# https_proxy
	  case 'https_Proxy' : {
		$GUI->https_proxy();
	  }break;

	  # OWS
	  case 'OWS' : {
		$GUI->createOWSResponse();
	  }break;

	  # OWS-Proxy erstellt bild nur neu, wenn noch nicht vorher schon mal gefordert
	  case 'OWS-Proxy' : {
			$GUI->owsProxy();
	  }break;

	  # OWS_Ausnahmebehandlung
	  case 'OWS_Exception' : {
			$GUI->createOWSException();
	  }break;

	  # 2006-03-24 CG
	  case 'StatistikAuswahl' : {
			$GUI->checkCaseAllowed($go);
			$GUI->StatistikAuswahl();
	  }break;
		
		case 'loadDenkmale_laden' : {
			$GUI->loadDenkmale_laden();
	  }break;
	  
	  case 'StatistikAuswahl_Stelle' : {
			$GUI->checkCaseAllowed($go);
			$GUI->StatistikAuswahl();
	  }break;

	  # 2006-04-03 CG
	  case 'StatistikAuswahl_anzeigen' : {
			$GUI->StatistikAuswahlErgebnis();
	  }break;
	  
	  case 'StatistikAuswahl_Stelle_anzeigen' : {
	  	$GUI->StatistikAuswahlErgebnis();
	  }break;

	  case 'Kartenkommentar_Formular' : {
			$GUI->mapCommentForm();
	  }break;

	  case 'Kartenkommentar_Speichern' : {
			$GUI->mapCommentStore();
	  }break;

	  case 'Kartenkommentar_Waehlen' : {
			$GUI->mapCommentSelectForm();
	  }break;

	  case 'Kartenkommentar_Zoom' : {
			$GUI->zoomToStoredMapExtent($GUI->formvars['storetime']);
	  }break;

	  case 'Kartenkommentar_loeschen' : {
			$GUI->DeleteStoredMapExtent();
	  }break;
		
		case 'Layerauswahl_Formular' : {
			$GUI->layerCommentForm();
	  }break;

		case 'Layerauswahl_Speichern' : {
			$GUI->layerCommentStore();
	  }break;
		
		case 'Layerauswahl_Waehlen' : {
			$GUI->layerCommentSelectForm();
	  }break;
		
		case 'Layerauswahl_Laden' : {
			$GUI->layerCommentLoad();
	  }break;
		
		case 'Layerauswahl_loeschen' : {
			$GUI->DeleteStoredLayers();
	  }break;

	  #2006-01-03 pk
	  case 'Grundbuchblatt_Auswaehlen' : {
		$GUI->checkCaseAllowed($go);
		$GUI->grundbuchblattWahl();
	  } break;

	  #2006-01-03 pk
	  case 'Grundbuchblatt_Auswaehlen_Suchen' : {
			$GUI->checkCaseAllowed('Grundbuchblatt_Auswaehlen');
			$GUI->grundbuchblattSuchen();
	  } break;

	  # 2006-01-26 pk
	  case 'Flurstueck_Anzeigen' : {
			$GUI->checkCaseAllowed($go);
			if($GUI->last_query != ''){
				$GUI->formvars['FlurstKennz'] = $GUI->last_query[$GUI->last_query['layer_ids'][0]]['sql'];
			}
			$explodedFlurstKennz = explode(';',$GUI->formvars['FlurstKennz']);
			$GUI->flurstAnzeige($explodedFlurstKennz);
			$GUI->output();
	  } break;
  
	  case 'changeLegendDisplay' : {
			$GUI->changeLegendDisplay();
	  } break;
		
		case 'saveOverlayPosition' : {
			$GUI->saveOverlayPosition();
	  } break;

	  case 'Administratorfunktionen' : {
			$GUI->checkCaseAllowed($go);
			$GUI->adminFunctions();
			$GUI->output();
	  } break;
	  
	  case 'Haltestellen_Suche' : {
			$GUI->haltestellenSuche();
			$GUI->output();
	  } break;
	  
	  case 'Druckrahmen' : {
			$GUI->checkCaseAllowed($go);
			$GUI->druckrahmen_init();
			$GUI->druckrahmen_load();
			$GUI->output();
	  } break;

	  case 'Druckrahmen_Freitexthinzufuegen' : {
	  	$_files = $_FILES;
			$GUI->checkCaseAllowed('Druckrahmen');
		  $GUI->druckrahmen_init();
		  $GUI->Document->update_frame($GUI->formvars, $_files);
		  $GUI->Document->addfreetext($GUI->formvars);
		  $GUI->druckrahmen_load();
			$GUI->output();
	  } break;

	  case 'Druckrahmen_Freitextloeschen' : {
	  	$_files = $_FILES;
			$GUI->checkCaseAllowed('Druckrahmen');
			$GUI->druckrahmen_init();
			$GUI->Document->update_frame($GUI->formvars, $_files);
			$GUI->Document->removefreetext($GUI->formvars);
			$GUI->druckrahmen_load();
			$GUI->output();
	  } break;

	  case 'Druckrahmen_übernehmen >>' : {
			$GUI->checkCaseAllowed('Druckrahmen');
			$GUI->druckrahmen_init();
			$GUI->Document->add_frame2stelle($GUI->formvars['aktiverRahmen'], $GUI->formvars['stelle']);
			$GUI->druckrahmen_load();
			$GUI->output();
	  } break;

	  case 'Druckrahmen_als neues Layout speichern' : {
	  	$_files = $_FILES;
			$GUI->checkCaseAllowed('Druckrahmen');
			$GUI->druckrahmen_init();
			$GUI->formvars['aktiverRahmen'] = $GUI->Document->save_frame($GUI->formvars, $_files, $GUI->Stelle->id);
			$GUI->druckrahmen_load();
			$GUI->output();
	  } break;

	  case 'Druckrahmen_Änderungen Speichern' : {
	  	$_files = $_FILES;
			$GUI->checkCaseAllowed('Druckrahmen');
			$GUI->druckrahmen_init();
			$GUI->Document->update_frame($GUI->formvars, $_files);
			$GUI->druckrahmen_load();
			$GUI->output();
	  } break;

	  case 'Druckrahmen_Löschen' : {
			$GUI->checkCaseAllowed('Druckrahmen');
			$GUI->druckrahmen_init();
			$GUI->Document->delete_frame($GUI->formvars['selected_frame_id']);
			$GUI->druckrahmen_load();
			$GUI->output();
	  } break;

	  case 'Druckausschnitt_loeschen' : {
			$GUI->druckausschnitt_löschen($GUI->formvars['loadmapsource']);
	  } break;

	  case 'Druckausschnitt_speichern' : {
		$GUI->druckausschnitt_speichern($GUI->formvars['loadmapsource']);
	  } break;

	  case 'Druckausschnittswahl' : {
			$GUI->formvars['loadmapsource'] = 'DataBase';
			$GUI->druckausschnittswahl($GUI->formvars['loadmapsource']);
	  } break;

	  case 'Druckausschnittswahl_Vorschau' : {
			if(IMAGEMAGICK == 'true'){
				$GUI->druckvorschau();
				$GUI->output();
			}
			else{
				$GUI->druckvorschau_html();
				$GUI->output();
			}
	  } break;

	  case 'Druckausschnittswahl_Drucken' : {
			$GUI->createMapPDF($GUI->formvars['aktiverRahmen'], false);
			$GUI->mime_type='pdf';
			$GUI->output();
	  } break;
	  
	  case 'Schnelle_Druckausgabe' : { 
			if($GUI->formvars['druckrahmen_id'] == ''){
				$GUI->formvars['druckrahmen_id'] = DEFAULT_DRUCKRAHMEN_ID;
			}
			$GUI->createMapPDF($GUI->formvars['druckrahmen_id'], false, true);
			$GUI->mime_type='pdf';
			$GUI->output();
	  } break;

	  case 'Notizenformular' : {
			$GUI->notizErfassung();
	  } break;

	  case 'Notizenformular_Senden' : {
			$GUI->notizSpeichern();
	  } break;

	  case 'Notiz_Loeschen' : {
			$GUI->notizLoeschen($GUI->formvars['oid']);
			$GUI->loadMap('DataBase');
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->output();
	  } break;

	  case 'Notizenformular_KatVerwaltung' : {
			$GUI->checkCaseAllowed($go);
			$GUI->notizKatVerwaltung();
	  } break;

	  case 'NotizKategorie_hinzufuegen' : {
		$GUI->notizKategoriehinzufügen();
	  } break;

	  case 'NotizKategorie_aendern' : {
		$GUI->notizKategorieAendern();
	  } break;

	  case 'NotizKategorie_loeschen' : {
		$GUI->notizKategorieLoeschen();
	  } break;

		case 'Metadaten_Uebersicht' : {
		$GUI->metadaten_uebersicht();
	  } break;

	  case 'Metadaten_generieren' : {
		$GUI->metadaten_generieren($GUI->formvars['layer_id']);
	  } break;
		
	  case 'Metadaten_Auswaehlen' : {
		$GUI->metadatenSuchForm();
	  } break;

	  case 'Metadatenblattanzeige' : {
		$GUI->metadatenblattanzeige();
	  } break;

	  case 'Metadateneingabe_Senden' : {
		$GUI->metadatensatzspeichern();
	  } break;

	  case 'Nutzung_auswaehlen' : {
		$GUI->checkCaseAllowed($go);
		$GUI->nutzungWahl();
	  } break;

	  case 'Nutzung_auswaehlen_Suchen' : {
		$GUI->nutzungsuchen();
	  } break;

	  case 'Namen_Auswaehlen' : {
		$GUI->namenWahl();
	  } break;

	  case 'Namen_Auswaehlen_Suchen' : {
		$GUI->checkCaseAllowed('Namensuche');
		$GUI->nameSuchen();    
	  } break;

	  case 'Suche_Flurstuecke_zu_Grundbuechern' : {
		$GUI->flurstuecksSucheByGrundbuecher();
	  } break;

	  case 'Zeige_Flurstuecke_zu_Grundbuechern' : {
		$GUI->flurstuecksAnzeigeByGrundbuecher();
	  } break;

	  case 'Suche_Flurstuecke_zu_Namen' : {
		$GUI->flurstuecksSucheByNamen();
	  } break;

	  case 'Zeige_Flurstuecke_zu_Namen' : {
		$GUI->flurstuecksAnzeigeByNamen();
	  } break;
		
		case "Suche_Flurstueck_zu_LatLng" : {
			$GUI->flurstSuchenByLatLng();
			$GUI->output();
		} break;

	  case 'ExportMapToPDF' : {
		$GUI->exportMapToPDF();
	  } break;

		case 'TIF_Export' : {
			$GUI->TIFExport();
	  } break;
	  
	  case 'TIF_Export_TIF-Datei erzeugen' : {
			$GUI->TIFExport_erzeugen();
	  } break;

	  case 'WMS_Export_Senden' : {
			$GUI->checkCaseAllowed('WMS_Export');
			$GUI->wmsExportSenden();
	  } break;

	  case 'WMS_Export' : {
			$GUI->checkCaseAllowed('WMS_Export');
			$GUI->wmsExport();
	  } break;

	  case 'WMS_Import_eintragen' : {
			$GUI->checkCaseAllowed('WMS_Import');
			$GUI->wmsImportieren();
	  } break;

	  case 'WMS_Import' : {
			$GUI->checkCaseAllowed('WMS_Import');
			$GUI->wmsImportFormular();
	  } break;
	  	  
	  case 'UKO_Import' : {
			$GUI->checkCaseAllowed('UKO_Import');
			$GUI->uko_import();
	  } break;
	  
	  case 'UKO_Import_Importieren' : {
			$GUI->checkCaseAllowed('UKO_Import');
			$GUI->uko_import_importieren();
	  } break;
		
	  case 'GPX_Import' : {
			$GUI->gpx_import();
	  } break;
	  
	  case 'GPX_Import_Laden' : {
			$GUI->gpx_import_importieren();
	  } break;
		
		case 'OVL_Import' : {
			$GUI->ovl_import();
	  } break;
	  
	  case 'OVL_Import_Laden' : {
			$GUI->ovl_import_importieren();
	  } break;
		
		case 'DXF_Import' : {
			$GUI->dxf_import();
	  } break;
	  
	  case 'DXF_Import_Laden' : {
			$GUI->dxf_import_importieren();
	  } break;
		
		case 'GeoJSON_Anzeigen' : {
			$GUI->create_geojson_rollenlayer();
	  } break;
		
	  case 'GeoJSON_Anzeigen_Datei laden' : {
			$GUI->create_geojson_rollenlayer_load();
	  } break;

		case 'SHP_Anzeigen' : {
			$GUI->create_shp_rollenlayer();
	  } break;
	  
	  case 'SHP_Anzeigen_Datei laden' : {
			$GUI->create_shp_rollenlayer_load();
	  } break;
		
		case 'Punktliste_Anzeigen' : {
			$GUI->create_point_rollenlayer();
	  } break;
		
		case 'Punktliste_Anzeigen_Datei laden' : {
			$GUI->create_point_rollenlayer_load();
	  } break;
		
		case 'Punktliste_Anzeigen_Anzeigen' : {
			$GUI->create_point_rollenlayer_import();
	  } break;
	  
	  case 'SHP_Import' : {
			$GUI->checkCaseAllowed('SHP_Import');
			$GUI->shp_import();
	  } break;
		
		case 'GeoJSON_Import' : {
			$GUI->checkCaseAllowed('GeoJSON_Import');
			$GUI->geojson_import();
	  } break;
		
		case 'GeoJSON_Import_Importieren' : {
			$GUI->checkCaseAllowed('GeoJSON_Import');
			$GUI->geojson_import_importieren();
	  } break;

	  case 'SHP_Import_speichern' : {
			$GUI->checkCaseAllowed('SHP_Import');
			$GUI->shp_import_speichern();
	  } break;
		
		case 'Daten_Import' : {
			$GUI->daten_import();
	  } break;

	  case 'Daten_Export' : {
			$GUI->checkCaseAllowed('Daten_Export');
			$GUI->daten_export();
	  } break;

	  case 'Daten_Export_Exportieren' : {
			$GUI->checkCaseAllowed('Daten_Export');
			$GUI->daten_export_exportieren();
	  } break;
		
		case 'get_last_search' : {
			$GUI->formvars['selected_layer_id'] = $GUI->user->rolle->get_last_search_layer_id();
			$GUI->formvars['searches'] = '<last_search>';
			$GUI->GenerischeSuche();
	  } break;
		
	  case 'Layer-Suche_Suchmaske_generieren' : {
			$GUI->GenerischeSuche_Suchmaske();
	  } break;
		
		case 'Layer-Suche_Suchen' : {
			$GUI->GenerischeSuche_Suchen();
	  } break;
		
		case 'SchnellSuche_Suchen' : {
			$GUI->formvars['selected_layer_id'] = $GUI->formvars['quicksearch_layer_id'];
			$GUI->formvars['keinzurueck'] = true;
			$GUI->formvars['quicksearch'] = true;
			$GUI->GenerischeSuche_Suchen();
	  } break;		

	  case 'Layer-Suche' : {
			$GUI->GenerischeSuche();
	  } break;
	  
		case 'Suchabfragen_auflisten' : {
			$GUI->Suchabfragen_auflisten();
	  } break;
		
	  case 'Layer-Suche_Suchabfrage_speichern' : {
			$GUI->GenerischeSuche();
	  } break;
	  
	  case 'Layer-Suche_Suchabfrage_löschen' : {
			$GUI->GenerischeSuche();
	  } break;		
		
		case 'Datensaetze_Merken' : {
			$GUI->Datensaetze_Merken();
	  } break;
		
		case 'Datensaetze_nicht_mehr_merken' : {
			$GUI->Datensaetze_nicht_mehr_merken();
	  } break;
		
		case 'Zwischenablage' : {
			$GUI->Zwischenablage();
	  } break;
		
		case 'gemerkte_Datensaetze_anzeigen' : {
			$GUI->gemerkte_Datensaetze_anzeigen($GUI->formvars['layer_id']);
	  } break;
				
		case 'Datensatz_dublizieren' : {
			$GUI->dublicate_dataset();
	  } break;
		
	  case 'Layer_Datensaetze_Loeschen' : {
			$GUI->layer_Datensaetze_loeschen();
	  } break;
	  
	  case 'Dokument_Loeschen' : {
			$GUI->sachdaten_speichern();
	  } break;

		case 'neuer_Layer_Datensatz' : {
			$GUI->neuer_Layer_Datensatz();
		} break;

		case 'neuer_Layer_Datensatz_speichern' : {
			$GUI->neuer_Layer_Datensatz_speichern();
		} break;

	  case 'generisches_sachdaten_diagramm' : {
			$GUI->generisches_sachdaten_diagramm($GUI->formvars['width']);
	  } break;
	  
	  case 'generischer_sachdaten_druck' : {
			$GUI->generischer_sachdaten_druck();
	  } break;
	  
	  case 'generischer_sachdaten_druck_Drucken' : {
		$GUI->generischer_sachdaten_druck_drucken();
	  } break;
	  
	  case 'sachdaten_druck_editor' : {
			$GUI->checkCaseAllowed('sachdaten_druck_editor');
			$GUI->sachdaten_druck_editor();
	  } break;
	  
	  case 'sachdaten_druck_editor_als neues Layout speichern' : {
			$GUI->checkCaseAllowed('sachdaten_druck_editor');
			$GUI->sachdaten_druck_editor_speichern();
	  } break;
	  
	  case 'sachdaten_druck_editor_Änderungen Speichern' : {
			$GUI->checkCaseAllowed('sachdaten_druck_editor');
			$GUI->sachdaten_druck_editor_aendern();
			$GUI->sachdaten_druck_editor();
	  } break;
	  
	  case 'sachdaten_druck_editor_Löschen' : {
			$GUI->checkCaseAllowed('sachdaten_druck_editor');
			$GUI->sachdaten_druck_editor_loeschen();
	  } break;
	  
	  case 'sachdaten_druck_editor_übernehmen >>' : {
			$GUI->checkCaseAllowed('sachdaten_druck_editor');
			$GUI->sachdaten_druck_editor_add2stelle();
	  } break;
	  
	  case 'sachdaten_druck_editor_Freitexthinzufuegen' :
			$GUI->checkCaseAllowed('sachdaten_druck_editor'); {
			$GUI->sachdaten_druck_editor_Freitexthinzufuegen();
	  } break;
	  
	  case 'sachdaten_druck_editor_Freitextloeschen' : {
			$GUI->checkCaseAllowed('sachdaten_druck_editor');
			$GUI->sachdaten_druck_editor_Freitextloeschen();
	  } break;
		
		case 'sachdaten_druck_editor_Liniehinzufuegen' :
			$GUI->checkCaseAllowed('sachdaten_druck_editor'); {
			$GUI->sachdaten_druck_editor_Liniehinzufuegen();
	  } break;
	  
	  case 'sachdaten_druck_editor_Linieloeschen' : {
			$GUI->checkCaseAllowed('sachdaten_druck_editor');
			$GUI->sachdaten_druck_editor_Linieloeschen();
	  } break;		
	  
	  case 'Layer_Export' : {
			$GUI->checkCaseAllowed($go);
			$GUI->layer_export();
	  } break;
	  
	  case 'Layer_Export_Exportieren' : {
			$GUI->checkCaseAllowed('Layer_Export');
			$GUI->layer_export_exportieren();
	  } break;

		case 'Layer_Generator' : {
			$GUI->checkCaseAllowed($go);
			$GUI->layer_generator();
		} break;

		case 'Layer_Generator_Erzeugen' : {
			$GUI->checkCaseAllowed('Layer_Generator');
			$GUI->layer_generator_erzeugen();
			$GUI->layer_generator();
		} break;

		case 'Style_Label_Editor' : {
			$GUI->checkCaseAllowed($go);
			$GUI->StyleLabelEditor();
	  } break;

	  case 'Layereditor' : {
			$GUI->checkCaseAllowed('Layereditor');
			$GUI->Layereditor();
	  } break;

	  case 'Layereditor_Klasse_Löschen' : {
			$GUI->checkCaseAllowed('Layereditor');
			$GUI->Layereditor_KlasseLoeschen();
	  } break;

	  case 'Layereditor_Klasse_Hinzufügen' : {
			$GUI->checkCaseAllowed('Layereditor');
			$GUI->Layereditor_KlasseHinzufuegen();
			$GUI->Layereditor();
	  } break;

	  case 'Layereditor_Autoklassen_Hinzufügen' : {
			$GUI->checkCaseAllowed('Layereditor');
			$GUI->Layereditor_AutoklassenHinzufuegen();
			$GUI->Layereditor();
	  } break;

	  case 'Layereditor_Als neuen Layer eintragen' : {
			$GUI->checkCaseAllowed('Layereditor');
			$GUI->LayerAnlegen();
			$GUI->Layereditor();
	  } break;

	  case 'Layereditor_Ändern' : {
			$GUI->checkCaseAllowed('Layereditor');
			$GUI->LayerAendern();
	  } break;

	  case 'Layereditor_erweiterte Einstellungen' : {
			$GUI->checkCaseAllowed('Attributeditor');
			$GUI->Attributeditor();
	  } break;

		case 'Attributeditor' : {
			$GUI->checkCaseAllowed('Attributeditor');
			$GUI->Attributeditor();
		} break;

		case 'Attributeditor_speichern' : {    
			$GUI->checkCaseAllowed('Attributeditor');
			$GUI->Attributeditor_speichern();
		} break;

		case 'Datentypen_Anzeigen' : {
			$GUI->checkCaseAllowed($go);
			$GUI->DatentypenAnzeigen();
		} break;

		case 'Layer_Anzeigen' : {
			$GUI->checkCaseAllowed($go);
			$GUI->LayerAnzeigen();
		} break;

		case 'Layergruppen_Anzeigen' : {
			$GUI->checkCaseAllowed($go);
			$GUI->Layergruppen_Anzeigen();
		} break;

		case 'Layergruppe_Editor' : {
			$GUI->checkCaseAllowed('Layergruppen_Anzeigen');
			$GUI->Layergruppe_Editor();
		} break;

		case 'Layergruppe_Speichern' : {
			$GUI->checkCaseAllowed('Layergruppen_Anzeigen');
			$GUI->Layergruppe_Speichern();
		} break;

		case 'Layergruppe_Ändern' : {
			$GUI->checkCaseAllowed('Layergruppen_Anzeigen');
			$GUI->Layergruppe_Aendern();
		} break;

		case 'Layergruppe_Löschen' : {
			$GUI->checkCaseAllowed('Layergruppen_Anzeigen');
			$GUI->Layergruppe_Loeschen();
		}

	  case 'Layer_Uebersicht' : {
			$GUI->LayerUebersicht();
		} break;

		case 'Layer_Löschen' : {    
			$GUI->checkCaseAllowed('Layer_Anzeigen');
			$GUI->LayerLoeschen();
			$GUI->LayerAnzeigen();
		} break;

	  case 'Layer2Stelle_Reihenfolge' : {
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->Layer2Stelle_Reihenfolge();
	  } break;

	  case 'Layer2Stelle_Reihenfolge_Speichern' : {    
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->Layer2Stelle_ReihenfolgeSpeichern();
	  } break;

	  case 'Layer2Stelle_Editor' : {
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->Layer2Stelle_Editor();
	  } break;

	  case 'Layer2Stelle_Editor_Speichern' : {
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->Layer2Stelle_EditorSpeichern();
	  } break;

		case 'Layerattribut-Rechteverwaltung' : {
			$GUI->checkCaseAllowed('Layerattribut-Rechteverwaltung');
			$GUI->layer_attributes_privileges();
	  } break;

	  case 'Layerattribut-Rechteverwaltung_speichern' : {
			$GUI->checkCaseAllowed('Layerattribut-Rechteverwaltung');
			$GUI->layer_attributes_privileges_save();
	  } break;
		
		case 'Layer_Parameter' : {
			$GUI->checkCaseAllowed('Layer_Parameter');
			$GUI->layer_parameter();
	  } break;
		
		case 'Layer_Parameter_speichern' : {
			$GUI->checkCaseAllowed('Layer_Parameter');
			$GUI->layer_parameter_speichern();
	  } break;

	  case 'Stelleneditor' : {
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->Stelleneditor();
	  } break;

	  case 'Stelle_Löschen' : {
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->StelleLoeschen();
	  } break;

	  case 'Stelleneditor_Als neue Stelle eintragen' : {
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->StelleAnlegen();
	  } break;

	  case 'Stelleneditor_Ändern' : {
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->StelleAendern();
	  } break;

	  case 'Stellen_Anzeigen' : {
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->StellenAnzeigen();
	  } break;

		case 'Menues_Anzeigen' : {
			$GUI->checkCaseAllowed('Menues_Anzeigen');
			$GUI->MenuesAnzeigen();
	  } break;

		case 'Menueeditor' : {
			$GUI->checkCaseAllowed('Menues_Anzeigen');
			$GUI->Menueeditor();
		} break;

		case 'Menue_Speichern' : {
			$GUI->checkCaseAllowed('Menues_Anzeigen');
			$GUI->MenueSpeichern();
		} break;

		case 'Menue_Ändern' : {
			$GUI->checkCaseAllowed('Menues_Anzeigen');
			$GUI->MenueAendern();
		} break;

	  case 'Menue_Löschen' : {
			$GUI->checkCaseAllowed('Menues_Anzeigen');
			$GUI->MenueLoeschen();
	  } break;

	  case 'Filterverwaltung' : {
			$GUI->checkCaseAllowed('Filterverwaltung');
			$GUI->Filterverwaltung();
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->output();
	  }break;

	  case 'Filterverwaltung_speichern' : {
			$GUI->checkCaseAllowed('Filterverwaltung');
			$GUI->Filter_speichern($GUI->formvars);
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->output();
	  }break;

		case 'BenutzerStellen_Anzeigen' : {
			$GUI->BenutzerNachStellenAnzeigen();
	  } break;
	  
	  case 'BenutzerderStelleAnzeigen' : {
			$GUI->BenutzerderStelleAnzeigen();
	  } break;

	  case 'Benutzerdaten_Anzeigen' : {
			$GUI->checkCaseAllowed('Benutzerdaten_Anzeigen');
			$GUI->BenutzerdatenAnzeigen();
	  } break;

	  case 'Benutzerdaten_Formular' : {
			$GUI->checkCaseAllowed('Benutzerdaten_Formular');
			$GUI->BenutzerdatenFormular();
	  } break;

	  case 'Benutzer_Löschen' : {
			$GUI->checkCaseAllowed('Benutzerdaten_Anzeigen');
			$GUI->BenutzerLöschen();
	  } break;

	  case 'Benutzerdaten_Als neuen Nutzer eintragen' : {
			$GUI->checkCaseAllowed('Benutzerdaten_Formular');
			$GUI->BenutzerdatenAnlegen();
	  } break;

	  case 'Benutzerdaten_Ändern' : {
			$GUI->checkCaseAllowed('Benutzerdaten_Formular');
			$GUI->BenutzerdatenAendern();
	  } break;

		case 'cronjobs_anzeigen' : {
			$GUI->checkCaseAllowed('cronjobs_anzeigen');
			$GUI->cronjobs_anzeigen();
		} break;

		case 'cronjob_editieren' : {
			$GUI->checkCaseAllowed('cronjobs_anzeigen');
			$GUI->cronjob_editieren();
		} break;

		case 'cronjob_speichern_Anlegen' : {
			$GUI->checkCaseAllowed('cronjobs_anzeigen');
			$GUI->cronjobs_anlegen();
		} break;

		case 'cronjob_speichern_Speichern' : {
			$GUI->checkCaseAllowed('cronjobs_anzeigen');
			$GUI->cronjob_update();
		} break;

		case 'cronjob_löschen' : {
			$GUI->checkCaseAllowed('cronjobs_anzeigen');
			$GUI->cronjob_delete();
		} break;

		case 'crontab_schreiben' : {
			$GUI->checkCaseAllowed('cronjobs_anzeigen');
			$GUI->crontab_schreiben();
		} break;

	  case 'Funktionen_Anzeigen' : {
			$GUI->checkCaseAllowed('Funktionen_Anzeigen');
			$GUI->FunktionenAnzeigen();
	  } break;

	  case 'Funktionen_Formular' : {    
			$GUI->checkCaseAllowed('Funktionen_Formular');
			$GUI->FunktionenFormular();
	  } break;

	  case 'Funktion_Löschen' : {
			$GUI->checkCaseAllowed('Funktionen_Anzeigen');
			$GUI->FunktionLoeschen();
	  } break;

	  case 'Funktionen_Als neue Funktion eintragen' : {
			$GUI->checkCaseAllowed('Funktionen_Formular');
			$GUI->FunktionAnlegen();    
	  } break;

	  case 'Funktionen_Ändern' : {
			$GUI->checkCaseAllowed('Funktionen_Formular');
			$GUI->FunktionAendern();
	  } break;
		
	  case 'help' : {
			include(WWWROOT.APPLVERSION.'help/hilfe.php');
	  } break;

	  case 'hilfe_nachweisverw': {
			include(WWWROOT.APPLVERSION.'help/hilfe_nachweisverw.php');
	  } break;

	  case 'hilfe_dokumente': {
			include(WWWROOT.APPLVERSION.'help/hilfe_nachweisverw.php');
	  } break;

	  # Flurstuecksauswahl zum festlegen pot. Geothermie-Bohrpunkte
	  case 'Geothermie_Abfrage' : {
			$GUI->geothermie_start();
	  } break;

	  # pot. Geothermie-Bohrpunkte festlegen und zu DB hinzufuegen
	  case 'Geothermie_Eingabe' : {
			$GUI->geothermie_anfrage();
	  } break;

	  # Polygon/Versiegelung digitalisieren
	  case 'Versiegelung' : {
			$GUI->versiegelungsFlaechenErfassung();
	  } break;

		case 'Versiegelung_Senden' : {
			$GUI->versiegelungsFlaechenSenden();
	  } break;

	  case "Ändern" : {
			$GUI->loadMap('DataBase');
			$GUI->scaleMap($GUI->formvars['nScale']);
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
	  } break;

	  case "tooltip_query" : {
			$GUI->show_query_tooltip = true;
			$GUI->queryMap();
	  } break;

	  case "neu Laden" : {
			$GUI->neuLaden();
			$GUI->saveMap('');
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->output();
	  } break;

		case "setMapExtent" : {
			$GUI->setMapExtent();
		} break;

	  case "history_move" : {
			$GUI->neuLaden();
			$GUI->saveMap('');
			$GUI->drawMap();
			$GUI->output();
	  } break;

	  case "ZoomToFlst" : {
			$GUI->loadMap('DataBase');
			if (strpos($GUI->formvars['FlurstKennz'], '/') !== false)$GUI->formvars['FlurstKennz'] = formatFlurstkennzALKIS($GUI->formvars['FlurstKennz']);
			if (substr($GUI->formvars['FlurstKennz'], -1) == '0') $GUI->formvars['FlurstKennz'] = formatFlurstkennzALKIS_0To_($GUI->formvars['FlurstKennz']);

			$explodedFlurstKennz = explode(';',$GUI->formvars['FlurstKennz']);
			$GUI->zoomToALKFlurst($explodedFlurstKennz,10);
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
	  } break;

	  case "Full_Extent" : {
			$GUI->loadMap('DataBase');
			$GUI->navMap('Full_Extent');
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
	  } break;

		case "Adresse_Auswaehlen" : {
			$GUI->checkCaseAllowed($go);
			$GUI->adresswahl();
			$GUI->output();
	  } break;

	  case "ALK-Adresse_Auswaehlen" : {
			$GUI->checkCaseAllowed($go);
			$GUI->formvars['ALK_Suche'] = 1;
			$GUI->adresswahl();
			$GUI->output();
	  } break;

	  case "Adresse_Auswaehlen_Suchen" : {
			$GUI->adresseSuchen();
			$GUI->output();
	  } break;
	  
	  case "ALK-Adresse_Auswaehlen_Suchen" : {
			$GUI->adresseSuchen();
			$GUI->output();
	  } break;

	  case "Flurstueck_hist_Auswaehlen" : {
			$GUI->checkCaseAllowed($go);
			$GUI->formvars['historical'] = 1;
			$GUI->flurstwahl();
			$GUI->output();
	  } break;

		case "ALK-Flurstueck_Auswaehlen" : {
			$GUI->checkCaseAllowed($go);
			$GUI->formvars['ALK_Suche'] = 1;
			$GUI->flurstwahl();
			$GUI->output();
	  } break;

	  case "Flurstueck_Auswaehlen" : {
			$GUI->checkCaseAllowed($go);
			$GUI->flurstwahl();
			$GUI->output();
	  } break;
		
		 case "Flurstueck_GetVersionen" : {
			$GUI->Flurstueck_GetVersionen();
	  } break;

	  case "Flurstueck_Auswaehlen_Suchen" : {
			$GUI->flurstSuchen();
			$GUI->output();
	  } break;
	  
	  case "ALK-Flurstueck_Auswaehlen_Suchen" : {
			$GUI->flurstSuchen();
			$GUI->output();
	  } break;
		
		case 'ALKIS_Auszug' : {
			$flurst_array = explode(';', $GUI->formvars['FlurstKennz']);
			$GUI->ALKIS_Auszug($flurst_array, $GUI->formvars['Grundbuchbezirk'], $GUI->formvars['Grundbuchblatt'], $GUI->formvars['Buchungsstelle'], $GUI->formvars['formnummer']);
	  } break;

	  case  'ALB_Anzeige' : {
			$flurst_array = explode(';', $GUI->formvars['FlurstKennz']);
			$GUI->ALB_Anzeigen($flurst_array,$GUI->formvars['formnummer'], NULL, NULL);
	  } break;

	  case  'ALB_Anzeige_Bestand' : {
			$GUI->ALB_Anzeigen(NULL, $GUI->formvars['formnummer'], $GUI->formvars['Grundbuchbezirk'], $GUI->formvars['Grundbuchblatt']);
	  } break;

	  # Auswählen einer neuen Stelle
	  case 'Stelle Wählen' : {
			$GUI->rollenwahl($Stelle_ID);
			$GUI->output();
	  } break;
	  
	   # Auswählen einer neuen Stelle
	  case 'Stelle_waehlen' : {
			$GUI->checkCaseAllowed($go);
			$GUI->rollenwahl($Stelle_ID);
			$GUI->output();
	  } break;

    default : {
      # Karteninformationen lesen
      $GUI->loadMap('DataBase');
      $GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
     	$GUI->saveMap('');
      $GUI->drawMap();
      $GUI->output();
	  }break;
	}
}
include('end.php');

if(CASE_COMPRESS AND FAST_CASE)case_compressor::write_fast_case_file($go);

// $executiontimes['time'][] = microtime_float1();
// $executiontimes['action'][] = 'Ende';
// for($i = 0;  $i < count($executiontimes['time']); $i++){
	// if($i > 0)$dauer1 = $executiontimes['time'][$i] - $executiontimes['time'][$i-1];
	// else $dauer1 = 0;
	// $dauer2 = $executiontimes['time'][$i] - $starttime;
	// echo chr(10).chr(13).'<br>'.$executiontimes['action'][$i].': '.$dauer1.'s   seit Start '.$dauer2.'s';
// }
?>