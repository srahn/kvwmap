<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
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
# Eine deutsche Übersetzung zur Lizenz finden Sie unter:          #
# http://www.gnu.de/gpl-ger.html                                  #
#                                                                 #
# Kontakt:                                                        #
# Peter Korduan peter.korduan@uni-rostock.de                      #
###################################################################
ob_start ();    // Ausgabepufferung starten
include('config.php');
if(!$_SESSION['angemeldet']){
  include(LAYOUTPATH.'snippets/'.LOGIN);
}
include(WWWROOT.APPLVERSION.'start.php');
#$starttime=microtime_float();
# Übergeben des Anwendungsfalles
$go=$GUI->formvars['go'];
$debug->write("<br><b>Anwendungsfall go: ".$go."</b>",4);
if ($GUI->formvars['go_plus']!='') {
  $go = $go.'_'.$GUI->formvars['go_plus'];
  $debug->write("<br>goplus: ".$GUI->formvars['go_plus'],4);
}
$GUI->go=$go;
$GUI->requeststring = $QUERY_STRING;
##### Anwendungsfälle der Benutzeroberfläche GUI #################
# ALB_Aenderung
# ALB_Anzeige
# ALK_Fortfuehrung
# Adm_Fortfuehrung
# Administratorfunktionen
# Adresse_Auswaehlen
# Adresse_Auswaehlen_Suchen
# Ändern
# Antraege_Anzeigen
# Antrag_Aendern
# Antrag_Loeschen
# Antraganzeige_Festpunkte_in_Liste_anzeigen
# Antraganzeige_Festpunkte_in_Karte_anzeigen
# Antraganzeige_Festpunkte_in_KVZ_schreiben
# Antraganzeige_Uebergabeprotokoll_Erzeugen
# Antraganzeige_Rechercheergebnis_in_Ordner_zusammenstellen
# Antraganzeige_Zugeordnete_Dokumente_Anzeigen
# Attributeditor
# Attributeditor_speichern
# Bauauskunft
# Bauauskunft_Suche
# Bauauskunft_Suche_Suchen
# bauleitplanung
# bauleitplanung_Senden
# Benutzerdaten_Als neuen Nutzer eintragen
# Benutzerdaten_Ändern
# Benutzerdaten_Formular
# Benutzer_Löschen
# Benutzerdaten_Anzeigen
# Bestaetigung
# Bodenrichtwertformular
# Bodenrichtwertformular_Aendern
# Bodenrichtwertformular_senden
# Bodenrichtwertzone_Loeschen
# BodenrichtwertzonenKopieren
# changemenue
# document_anzeigen
# Dokumentenkoepfe
# Dokumentenkoepfe_als neuen Kopf speichern
# Dokumentenkoepfe_Änderungen Speichern
# Dokumentenkoepfe_übernehmen >>
# Druckausschnittswahl
# Druckausschnittswahl_Vorschau
# editLayerForm($layerName,$oid)
# ExportMapToPDF
# Externer_Druck
# Externer_Druck_Drucken
# FestpunktDateiAktualisieren
# FestpunktDateiUebernehmen
# Festpunkte Anzeigen
# Festpunkte_Auswaehlen
# Festpunkte_Auswaehlen_Suchen
# Festpunkte zum Antrag Anzeigen
# Festpunkte zum Antrag Hinzufügen
# Festpunkte zum Antrag Hinzufügen_Senden
# FestpunkteSkizzenZuordnung
# FestpunkteSkizzenZuordnung_Senden
# Flurstueck_Anzeigen
# Flurstueck_Auswaehlen
# Flurstueck_Auswaehlen_Suchen
# Full_Extent
# Geothermie_Abfrage
# Geothermie_Eingabe
# getMenueWithAjax
# getRow
# Grundbuchblatt_Auswaehlen
# Grundbuchblatt_Auswaehlen_Suchen
# Hausnummernkorrektur
# hideMenueWithAjax
# help
# Kartenkommentar_Formular
# Kartenkommentar_Speichern
# Kartenkommentar_Waehlen
# Kartenkommentar_Zoom
# Layer-Suche
# Layer-Suche_Suchen
# Layerattribut-Rechteverwaltung
# Layerattribut-Rechteverwaltung_speichern
# Metadaten_Auswaehlen
# Metadaten_Auswaehlen_Senden
# Metadatenblattanzeige
# Metadateneingabe
# Metadateneingabe_Senden
# neu Laden
# neuer Layer
# neuerLayer_Senden
# Nachweis_antragsnr_form_aufrufen
# Nachweis_antragsnummer_Senden
# Nachweisanzeige
# Nachweisanzeige_aus_Auftrag_entfernen
# Nachweisanzeige_zum_Auftrag_hinzufuegen
# Nachweisbearbeitung_Senden
# Nachweisformular
# Nachweisformular_Senden
# Nachweisloeschen
# Nachweisrechercheformular
# Nachweisrechercheformular_Senden
# Namen_Auswaehlen
# Namen_Auswaehlen_Suchen
# Notizenformular_KatVerwaltung
# NotizKategorie_aendern
# NotizKategorie_hinzufuegen
# NotizKategorie_loeschen
# Metadaten_Auswaehlen
# OWS
# sendeDokument
# sendImage
# sendeFestpunktskizze
# setMapExtent
# spatial_processing
# StatistikAuswahl
# StatistikAuswahl_anzeigen
# Stelle Wählen
# Stelleneditor
# Stelle_Löschen
# Stelleneditor_Als neue Stelle eintragen
# Stelleneditor_Ändern
# Stellen_Anzeigen
# Suche_Flurstuecke_zu_Namen
# tmp_Adr_Tabelle_Aktualisieren
# Versiegelung
# WMS_Export
# WMS_Export_Senden
# WMS_Import
# WMS_Import_Eintragen
# ZoomToFlst
#
####################################################################
#echo 'go: '.$go;
#var_dump($GUI->formvars);

switch($GUI->go) {

	case 'Multi_Geometrien_splitten' : {
	  $GUI->split_multi_geometries();
	}break;

	case 'bevoelkerung_bericht' : {
	  $GUI->bevoelkerung_bericht();
	}break;
	
	case 'bevoelkerung_bericht_Bericht erstellen' : {
	  $GUI->bevoelkerung_bericht_erstellen();
	}break;

	case 'reset_layers' : {
    $GUI->reset_layers();
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

	case 'getSVG_vertices' : {
		$GUI->getSVG_vertices();
  } break;
  
  case 'getSVG_foreign_vertices' : {
		$GUI->getSVG_foreign_vertices();
  } break;

  case 'ResizeMap2Window' : {
    $GUI->resizeMap2Window();
    $GUI->loadMap('DataBase');
    $GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
    $GUI->drawMap();
    $GUI->saveMap('');
    $GUI->output();
  } break;

  case 'delete_bplan' : {
    $GUI->delete_bplan();
  } break;
  
  case 'delete_fplan' : {
    $GUI->delete_fplan();
  } break;
  
  case 'zoomtobplan' : {
    $GUI->zoomtobplan();
  } break;
  
  case 'zoomtofplan' : {
    $GUI->zoomtofplan();
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

  # CASE für Testzwecke der postgresql-Datenbankanfragens
  case 'loadDenkmale_laden' : {
    $GUI->loadDenkmale_laden();
    $GUI->output();
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

  # Legende erzeugen
  case 'get_legend' : {
    $GUI->get_legend();
  } break;

  # GPS-Position auslesen
  case 'get_gps_position' : {
    $GUI->get_gps_position();
  } break;

  # Eigentuemerfortführung
  case 'export_ESAF64' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->export_ESAF64();
  } break;

  # Eigentuemerfortführung
  case 'export_ESAF64_Exportieren' : {
    $GUI->checkCaseAllowed('export_ESAF64');
    $GUI->export_ESAF64_exportieren();
  } break;

  # Eigentuemerfortführung
  case 'export_ESAF64_Tabelle Bereinigen' : {
    $GUI->checkCaseAllowed('export_ESAF64');
   	$GUI->export_ESAF64_bereiningen();
  } break;
  
  case 'exportWMC' :{
	  $GUI->exportWMC();
	} break;

  case 'spatialDocIndexing' : {
    $GUI->spatialDocIndexing();
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
    session_destroy();
    $locationStr='index.php';
    if (isset($newPassword)) {
      $locationStr.='?newPassword='.$newPassword;
      $locationStr.='&msg='.$GUI->Fehlermeldung;
    }
    header('Location: '.$locationStr);
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
    
  case 'googlemaps' : {
    $GUI->googlemaps();
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
    $GUI->zoom_toPoint();
  }break;

  # zoomToPolygon
  case 'zoomtoPolygon' : {
    $GUI->zoom_toPolygon();
  }break;
  
  # zoomToLine
  case 'zoomToLine' : {
    $GUI->zoom_toLine();
  }break;

  # zoom to maximum extent of the layer
  case 'zoomToMaxLayerExtent' : {
    # Karteninformationen lesen
    $GUI->loadMap('DataBase');
    $GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
    $GUI->zoomToMaxLayerExtent($GUI->formvars['layer_id']);
    $GUI->drawMap();
    $GUI->saveMap('');
    $GUI->output();
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

  # Anliegerbeiträge
  case 'anliegerbeitraege' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->Anliegerbeiträge_editor();
  }break;

  # Anliegerbeiträge Strasse speichern
  case 'anliegerbeitraege_strasse_speichern' : {
    $GUI->Anliegerbeiträge_strasse_speichern();
  }break;

  # Anliegerbeiträge Buffer speichern
  case 'anliegerbeitraege_buffer_speichern' : {
    $GUI->Anliegerbeiträge_buffer_speichern();
  }break;

  # Sachdaten anzeigen
  case 'Sachdaten' : {
    $GUI->sachdaten_anzeigen();
  }break;

  # Jagdbezirke Sachdaten anzeigen
  case 'jagdbezirk_show_data' : {
    $GUI->jagdbezirk_show_data();
  }break;

  # zoomtoJagdbezirke
  case 'zoomtojagdbezirk' : {
    $GUI->zoomtojagdbezirk();
  }break;
  
  # Jagdbezirke Suchen
  case 'jagdbezirke_auswaehlen_Suchen' : {
    $GUI->jagdbezirke_auswaehlen_suchen();
  }break;

  # Jagdbezirke Suchen
  case 'jagdbezirke_auswaehlen' : {
    $GUI->jagdbezirke_auswaehlen();
  }break;

  # Jagdkatastereditor
  case 'jagdkatastereditor' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->jagdkatastereditor();
  }break;

  # Jagdkatastereditor
  case 'jagdkatastereditor_Senden' : {
    $GUI->jagdkatastereditor_senden();
  }break;

  # Jagdkatastereditor
  case 'jagdkatastereditor_Loeschen' : {
    $GUI->jagdkatastereditor_loeschen();
  }break;

  # Jagdkatastereditor
  case 'jagdkatastereditor_kopieren' : {
    $GUI->jagdkatastereditor_kopieren();
  }break;

  # Jagdkatastereditor
  case 'jagdkatastereditor_Flurstuecke_Listen' : {
    $GUI->jagdkatastereditor_listflurst();
  }break;
  
  # Jagdkatastereditor
  case 'jagdkatastereditor_Flurstuecke_Listen_csv' : {
    $GUI->jagdkatastereditor_listflurst_csv();
  }break;

  # Jagdkatastereditor
  case 'jagdkatastereditor_Paechter_Listen' : {
    $GUI->jagdkatastereditor_listpaechter();
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

  # 2006-04-03 CG
  case 'StatistikAuswahl_anzeigen' : {
    $GUI->StatistikAuswahlErgebnis();
  }break;

  # 2006-03-20 pk
  case 'Kartenkommentar_Formular' : {
    $GUI->mapCommentForm();
  } break;

  # 2006-03-20 pk
  case 'Kartenkommentar_Speichern' : {
    $GUI->mapCommentStore();
  } break;

  # 2006-03-20 pk
  case 'Kartenkommentar_Waehlen' : {
    $GUI->mapCommentSelectForm();
  } break;

  case 'Kartenkommentar_Zoom' : {
    $GUI->zoomToStoredMapExtent($GUI->formvars['storetime']);
  } break;

  case 'Kartenkommentar_loeschen' : {
    $GUI->DeleteStoredMapExtent($GUI->formvars['storetime']);
  } break;

  #2006-01-03 pk
  case 'Grundbuchblatt_Auswaehlen' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->grundbuchblattWahl();
  } break;

  #2006-01-03 pk
  case 'Grundbuchblatt_Auswaehlen_Suchen' : {
    $GUI->grundbuchblattSuchen();
  } break;

  # 2006-01-26 pk
  case 'Flurstueck_Anzeigen' : {
  	$GUI->checkCaseAllowed($go);
    $explodedFlurstKennz = explode(';',$GUI->formvars['FlurstKennz']);
    $GUI->flurstAnzeige($explodedFlurstKennz);
    $GUI->output();
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
  
  case 'changeLegendDisplay' : {
    $GUI->changeLegendDisplay();
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
  
  case 'Bauauskunft_Suche' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->bauauskunftSuche();
    $GUI->output();
  } break;

  case 'Bauauskunft_Suche_Suchen' : {
  	$GUI->checkCaseAllowed('Bauakteneinsicht');
    $GUI->bauauskunftSucheSenden($GUI->formvars['flurstkennz']);
    $GUI->output();
  } break;

  case 'Baudatenanzeige' : {
  	$GUI->checkCaseAllowed('Bauakteneinsicht');
    $GUI->bauauskunftanzeige();
    $GUI->output();
  } break;

  case 'Druckrahmen' : {
  	$GUI->checkCaseAllowed($go);
  	$GUI->druckrahmen_init();
  	$GUI->druckrahmen_load();
    $GUI->output();
  } break;

  case 'Druckrahmen_Freitexthinzufuegen' : {
  	$GUI->checkCaseAllowed('Druckrahmen');
	  $GUI->druckrahmen_init();
	  $GUI->Document->update_frame($GUI->formvars, $_FILES);
	  $GUI->Document->addfreetext($GUI->formvars);
	  $GUI->druckrahmen_load();
    $GUI->output();
  } break;

  case 'Druckrahmen_Freitextloeschen' : {
  	$GUI->checkCaseAllowed('Druckrahmen');
    $GUI->druckrahmen_init();
    $GUI->Document->update_frame($GUI->formvars, $_FILES);
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

  case 'Druckrahmen_als neuen Rahmen speichern' : {
  	$GUI->checkCaseAllowed('Druckrahmen');
    $GUI->druckrahmen_init();
    $GUI->formvars['aktiverRahmen'] = $GUI->Document->save_frame($GUI->formvars, $_FILES, $GUI->Stelle->id);
    $GUI->druckrahmen_load();
    $GUI->output();
  } break;

  case 'Druckrahmen_Änderungen Speichern' : {
  	$GUI->checkCaseAllowed('Druckrahmen');
    $GUI->druckrahmen_init();
    $GUI->Document->update_frame($GUI->formvars, $_FILES);
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
  
  case 'Metadaten_Recherche' : {
    $GUI->metadaten_suche();
  } break;
  
  case 'Metadaten_generieren' : {
    $GUI->metadaten_generieren($this->formvars['layer_id']);
  } break;
	
  case 'Metadaten_Auswaehlen' : {
    $GUI->metadatenSuchForm();
  } break;

  case 'Metadaten_Auswaehlen_Senden' : {
    $GUI->metadatenSuchen();
  } break;

  case 'Metadatenblattanzeige' : {
    $GUI->metadatenblattanzeige();
  } break;

  case 'Metadateneingabe' : {
    $GUI->metadateneingabe();
  } break;

  case 'Metadateneingabe_Senden' : {
    $GUI->metadatensatzspeichern();
  } break;

  case 'Sachdaten_Festpunkte Anzeigen' : {
    $GUI->festpunkteZeigen();
  } break;

  case 'Festpunkte Anzeigen' : {
    $GUI->festpunkteZeigen();
  } break;

  case 'Festpunkte in Liste Anzeigen' : {
    $GUI->festpunkteSuchen();
  } break;

  case 'Festpunkte_Auswaehlen' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->festpunkteWahl();
  } break;

  case 'Festpunkte_Auswaehlen_Suchen' : {
    $GUI->festpunkteSuchen();
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

  case 'Sachdaten_Festpunkte zu Auftrag Hinzufügen' : {
    $GUI->festpunkteZuAuftragFormular();
  } break;
  
  case 'Sachdaten_KVZ-Datei erzeugen' : {
  	$GUI->formvars['antr_selected'] = 'ohne';
    $GUI->festpunkteInKVZschreiben();
    ob_end_clean();
    header("Content-type: text/kvz");
    header("Content-Disposition: attachment; filename=".basename($GUI->datei));
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    readfile($GUI->datei);
  } break;

  case 'Festpunkte zum Antrag Hinzufügen_Senden' : {
    $GUI->festpunkteZuAuftragSenden();
  } break;

  case 'sendImage' : {
    if ($GUI->formvars['format'] == '') {
      $GUI->formvars['format']='png';
    }
    $GUI->sendImage($GUI->formvars['name'],'png');
  } break;

  case 'sendeFestpunktskizze' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->sendeFestpunktskizze($GUI->formvars['name'],PUNKTDATEIPATH);
  } break;

  case 'sendeDokument' : {
    $GUI->sendeDokument($GUI->formvars['dokument'], $GUI->formvars['original_name']);
  } break;

  case 'sendeDokument_mit_vorschau' : {
    $GUI->sendeDokument_mit_vorschau($GUI->formvars['dokument'], $GUI->formvars['original_name']);    
  } break;

  case 'FestpunktDateiUebernehmen' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->uebernehmeFestpunkte();
  } break;

  case 'FestpunktDateiAktualisieren' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->aktualisiereFestpunkte();
  } break;

  case 'Sachdaten_FestpunkteSkizzenZuordnung' : {
    $GUI->showFestpunkteSkizze();
  } break;

  case 'FestpunkteSkizzenZuordnung_Senden' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->ordneFestpunktSkizzen();
  } break;

  case 'ExportMapToPDF' : {
    $GUI->exportMapToPDF();
  } break;

  case 'Bodenrichtwertzone_Loeschen' : {
    $GUI->bodenRichtWertZoneLoeschen();
  } break;

  case 'Bodenrichtwertformular' : {
  	$GUI->checkCaseAllowed('Bodenrichtwertformular');
    $GUI->titel='Bodenrichtwerterfassung';
    $GUI->bodenRichtWertErfassung();
  } break;

  case 'Bodenrichtwertformular_Aendern' : {
  	$GUI->checkCaseAllowed('Bodenrichtwertformular');
    $GUI->aendernBodenRichtWert();
  } break;

  case 'Bodenrichtwertformular_Senden' : {
  	$GUI->checkCaseAllowed('Bodenrichtwertformular');
    $GUI->bodenRichtWertFormSenden();
  } break;

  case 'BodenrichtwertzonenKopieren' : {
  	$GUI->checkCaseAllowed('BodenrichtwertzonenKopieren');
    $GUI->waehleBodenwertStichtagToCopy();
  } break;

  case 'BodenrichtwertzonenKopieren_Senden' : {
  	$GUI->checkCaseAllowed('BodenrichtwertzonenKopieren');
    $GUI->copyBodenrichtwertzonen();
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

  case 'WMS_Import_Eintragen' : {
  	$GUI->checkCaseAllowed('WMS_Import');
    $GUI->wmsImportieren();
  } break;

  case 'WMS_Import' : {
  	$GUI->checkCaseAllowed('WMS_Import');
    $GUI->wmsImportFormular();
  } break;
  
  case 'UKO_Export' : {
  	$GUI->checkCaseAllowed('UKO_Export');
    $GUI->uko_export();
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
	$GUI->checkCaseAllowed('GPX_Import');
    $GUI->gpx_import();
  } break;
  
  case 'GPX_Import_importieren' : {
  	$GUI->checkCaseAllowed('GPX_Import');
    $GUI->gpx_import_importieren();
  } break;

	case 'SHP_Anzeigen' : {
    $GUI->create_shp_rollenlayer();
  } break;
  
  case 'SHP_Anzeigen_Datei laden' : {
    $GUI->create_shp_rollenlayer_load();
  } break;

  case 'simple_SHP_Import' : {
  	$GUI->checkCaseAllowed('simple_SHP_Import');
    $GUI->simple_shp_import();
  } break;

  case 'simple_SHP_Import_speichern' : {
  	$GUI->checkCaseAllowed('simple_SHP_Import');
    $GUI->simple_shp_import_speichern();
  } break;
  
  case 'SHP_Import' : {
  	$GUI->checkCaseAllowed('SHP_Import');
    $GUI->shp_import();
  } break;

  case 'SHP_Import_speichern' : {
  	$GUI->checkCaseAllowed('SHP_Import');
    $GUI->shp_import_speichern();
  } break;

  case 'SHP_Export' : {
  	$GUI->checkCaseAllowed('SHP_Export');
    $GUI->shp_export();
  } break;

  case 'SHP_Export_Shape-Datei erzeugen' : {
  	$GUI->checkCaseAllowed('SHP_Export');
    $GUI->shp_export_exportieren();
  } break;

  case 'Layer-Suche_Suchen' : {
    $GUI->GenerischeSuche_Suchen();
  } break;

  case 'Layer-Suche' : {
    $GUI->GenerischeSuche();
  } break;
  
  case 'Layer-Suche_Suchabfrage_speichern' : {
    $GUI->GenerischeSuche();
  } break;
  
  case 'Layer-Suche_Suchabfrage_löschen' : {
    $GUI->GenerischeSuche();
  } break;

  case 'Layer_Datensaetze_Loeschen' : {
    $GUI->layer_Datensaetze_loeschen();
  } break;
  
  case 'Dokument_Loeschen' : {
    $GUI->dokument_loeschen();
  } break;

  case 'neuer_Layer_Datensatz' : {
    $GUI->neuer_Layer_Datensatz();
  } break;

  case 'neuer_Layer_Datensatz_speichern' : {
    $GUI->neuer_Layer_Datensatz_speichern();
  } break;

  case 'generischer_csv_export' : {
    $GUI->generic_csv_export();
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
  
  case 'Layer_Export' : {
  	$GUI->checkCaseAllowed($go);
    $GUI->layer_export();
  } break;
  
  case 'Layer_Export_Exportieren' : {
  	$GUI->checkCaseAllowed('Layer_Export');
    $GUI->layer_export_exportieren();
  } break;

	case 'Style_Label_Editor' : {
		$GUI->checkCaseAllowed($go);
    $GUI->StyleLabelEditor();
  } break;

  case 'Layereditor' : {
  	$GUI->checkCaseAllowed('Layer_Anzeigen');
    $GUI->Layereditor();
  } break;

  case 'Layereditor_Klasse_Löschen' : {
  	$GUI->checkCaseAllowed('Layer_Anzeigen');
   	$GUI->Layereditor_KlasseLoeschen();
  } break;

  case 'Layereditor_Klasse_Hinzufügen' : {
  	$GUI->checkCaseAllowed('Layer_Anzeigen');
    $GUI->Layereditor_KlasseHinzufuegen();    
  } break;

  case 'Layereditor_Als neuen Layer eintragen' : {
  	$GUI->checkCaseAllowed('Layer_Anzeigen');
    $GUI->LayerAnlegen();
  } break;

  case 'Layereditor_Ändern' : {
  	$GUI->checkCaseAllowed('Layer_Anzeigen');
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

  case 'Layer_Anzeigen' : {
    $GUI->checkCaseAllowed($go);
    $GUI->LayerAnzeigen();
  } break;
  
  case 'Layer_Uebersicht' : {
    $GUI->LayerUebersicht();
  } break;

  case 'Layer_Löschen' : {    
    $GUI->checkCaseAllowed('Layer_Anzeigen');
    $GUI->LayerLoeschen();
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

  case 'Antraege_Anzeigen' : {
  	$GUI->checkCaseAllowed('Antraege_Anzeigen');
    $GUI->Antraege_Anzeigen();
  } break;

  case 'Antrag_loeschen' : {
    $GUI->checkCaseAllowed($go);
    $GUI->Antrag_Loeschen();
  } break;

  case 'Antraganzeige_Festpunkte_in_Karte_Anzeigen' : {
    $GUI->festpunkteZuAntragZeigen();
  } break;

  case 'Antraganzeige_Festpunkte_in_Liste_Anzeigen' : {
    $GUI->festpunkteSuchen();
  } break;

  case 'Antraganzeige_Festpunkte_in_KVZ_schreiben' : {
    $GUI->festpunkteInKVZschreiben();
    $GUI->Antraege_Anzeigen();
  } break;

  case 'Antraganzeige_Zugeordnete_Dokumente_Anzeigen' : {
    $GUI->checkCaseAllowed($go);
    $GUI->DokumenteZuAntraegeAnzeigen();
  } break;

  case 'Antraganzeige_Uebergabeprotokoll_Erzeugen' : {
    $GUI->erzeugenUebergabeprotokollNachweise($GUI->formvars['antr_selected']);
  } break;

  case 'Antraganzeige_Rechercheergebnis_in_Ordner_zusammenstellen' : {
    $ret=$GUI->DokumenteZumAntragInOrdnerZusammenstellen();
    $GUI->Antraege_Anzeigen();
    showAlert($ret);
  } break;

  case 'Antraganzeige_Recherche_Ordner_packen' : {
    $filename = $GUI->DokumenteOrdnerPacken();
    $GUI->Datei_Download($filename);
  } break;

  case 'Antraganzeige_Zusammenstellen_Zippen' : {
    $ret=$GUI->DokumenteZumAntragInOrdnerZusammenstellen();
    showAlert($ret);
    $filename = $GUI->DokumenteOrdnerPacken();
    $GUI->Datei_Download($filename);
  } break;


  # Die Werte zum Eintragen von neuen Documenten in die DB werden an "nachweisFormSenden"
  case 'Nachweisformular_Senden' : {
    $GUI->formvars['Bilddatei']=$_FILES['Bilddatei']['tmp_name'];
    $GUI->formvars['Bilddatei_name']=$_FILES['Bilddatei']['name'];
    $GUI->formvars['Bilddatei_size']=$_FILES['Bilddatei']['size'];
    $GUI->formvars['Bilddatei_type']=$_FILES['Bilddatei']['type'];
    $GUI->nachweisFormSenden();
  } break;

  case 'Nachweisloeschen':{
    $GUI->checkCaseAllowed($go);
    $GUI->nachweisLoeschen();
  } break;


#Documente die in der Ergebnisliste ausgewählt wurden sollen weiterverarbeitet werden!
# 2006-01-26 pk
  case 'Nachweisanzeige_zum_Auftrag_hinzufuegen' : {
    $GUI->checkCaseAllowed($go);
    $GUI->nachweiseZuAuftrag();
  } break;

  case 'Nachweisanzeige_aus_Auftrag_entfernen':{
    $GUI->checkCaseAllowed($go);
    $GUI->nachweiseZuAuftragEntfernen();
  } break;

  # Der case 'Bestaetigung' wurde am 2006-01-30 gelöscht, weil nicht mehr benutzt

  # Rechercheanfrage an die Datenbank senden / mit prüfen der Eingabedaten
  case 'Nachweisanzeige' : {
    if($GUI->formvars['art_markieren'] AND $GUI->formvars['art_einblenden']){
      $GUI->formvars['showffr']=substr($GUI->formvars['art_einblenden'],0,1);
      $GUI->formvars['showkvz']=substr($GUI->formvars['art_einblenden'],1,1);
      $GUI->formvars['showgn']=substr($GUI->formvars['art_einblenden'],2,1);
      $GUI->formvars['showan']=substr($GUI->formvars['art_einblenden'],3,1);
      $GUI->formvars['markffr']=substr($GUI->formvars['art_markieren'],0,1);
      $GUI->formvars['markkvz']=substr($GUI->formvars['art_markieren'],1,1);
      $GUI->formvars['markgn']=substr($GUI->formvars['art_markieren'],2,1);
      $GUI->user->rolle->setNachweisAnzeigeparameter($GUI->formvars['showffr'],$GUI->formvars['showkvz'],$GUI->formvars['showgn'],$GUI->formvars['showan'],$GUI->formvars['markffr'],$GUI->formvars['markkvz'],$GUI->formvars['markgn']);
    }
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $GUI->savedformvars=$GUI->user->rolle->getNachweisParameter();
    $GUI->formvars=array_merge($GUI->savedformvars,$GUI->formvars);
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkungflurid'],$GUI->formvars['suchstammnr'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr']);
    if($ret!=''){
      $GUI->nachweisAnzeige();
      showAlert($ret);
    }
    else {
      $GUI->nachweisAnzeige();
    }
  } break;

  case 'document_anzeigen' : {
    $GUI->nachweisDokumentAnzeigen();
  } break;

  # Eingabe oder Änderung der Daten zu einem Dokument im Katasternachweis
  # für Fortführungsrisse, Koordinatenverzeichnisse und Grenzniederschriften

  case 'Nachweisformular' : {
  	$GUI->checkCaseAllowed($go);
    # Unterscheidung ob vorhandene Dokumente geändert werden sollen oder neu eingegeben
    if ($GUI->formvars['id']!='') {
      # Ein Nachweis soll geändert werden
      $GUI->nachweisAenderungsformular();
    }
    else {
      # Eingabe von Daten zu einem neuen Nachweisdokument
      # Anzeige des Neueingabeformulars
      $GUI->nachweisFormAnzeige();
    }
  } break;
  
  case 'Nachweisformular_Vorlage' : {
  	# Eingabe von Daten zu einem neuen Nachweisdokument
    $GUI->nachweisFormAnzeigeVorlage();
  } break;

	case 'check_nachweis_poly' : {
    $GUI->check_nachweis_poly();
  } break;

  case 'Antrag_Aendern' : {
    $GUI->checkCaseAllowed($go);
    $GUI->vermessungsantragAendern();
  } break;

  case 'Nachweis_antragsnr_form_aufrufen' : {
    $GUI->checkCaseAllowed($go);
    $GUI->vermessungsantragsFormular();
  } break;

  case 'Nachweis_antragsnummer_Senden' : {
    $GUI->vermessungsantragAnlegen();
  } break;

  case 'Nachweisrechercheformular':{
  	$GUI->checkCaseAllowed($go);
    $GUI->rechercheFormAnzeigen();
  } break;

  # Rechercheanfrage an die Datenbank senden/ mit prüfen der Eingabedaten
  case 'Nachweisrechercheformular_Senden':{
    $GUI->nachweiseRecherchieren();
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
    $GUI->changeMap();
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
    $GUI->changeMap();
    $GUI->saveMap('');
    $GUI->drawMap();
    $GUI->output();
  } break;

  case "ZoomToFlst" : {
    $GUI->loadMap('DataBase');
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

  case "Flurstueck_Auswaehlen_Suchen" : {
    $GUI->flurstSuchen();
    $GUI->output();
  } break;
  
  case "ALK-Flurstueck_Auswaehlen_Suchen" : {
    $GUI->flurstSuchen();
    $GUI->output();
  } break;

  case "Hausnummernkorrektur" : {
    $ALB=new ALB($GUI->database);
    $ALB->HausNrTextKorrektur();
    $Adresse=new adresse('','','',$this->database);
    $Adresse->updateAdressTable();
    $GUI->adresswahl();
    $GUI->output();
  } break;

  case 'ALK_Fortfuehrung' : {
    $GUI->checkCaseAllowed($go);
    $GUI->ALK_Fortfuehrung();
    $GUI->tmp_Adr_Tabelle_Aktualisieren();
    $GUI->output();
  } break;

  case 'Adm_Fortfuehrung' : {
    $GUI->Adm_Fortfuehrung();
    $GUI->output();
  } break;

  case  'ALB_Anzeige' : {
    $flurst_array = explode(';', $GUI->formvars['FlurstKennz']);
    $GUI->ALB_Anzeigen($flurst_array,$GUI->formvars['formnummer'], NULL, NULL);
  } break;

  case  'ALB_Anzeige_Bestand' : {
    $GUI->ALB_Anzeigen(NULL, $GUI->formvars['formnummer'], $GUI->formvars['Grundbuchbezirk'], $GUI->formvars['Grundbuchblatt']);
  } break;

  # Aktualisierung des ALB-Datenbestandes an Hand einer WLDGE-Datei
  # Dieser Anwendungsfall deckt das Anlegen eines neuen ALB-Bestandes mit ab, dazu muß die Variable ist_Fortführung=0 sein
  case 'ALB_Aenderung' : {
    $GUI->checkCaseAllowed($go);
    if ($GUI->formvars['WLDGE_lokal']==2) {
      $GUI->ALB_Aenderung_Stapel();
    }
    else {
      $GUI->ALB_Aenderung();
    }
    $GUI->output();
  } break; # end of Änderung des ALB-Datenbestandes

  case 'tmp_Adr_Tabelle_Aktualisieren' : {
    $GUI->tmp_Adr_Tabelle_Aktualisieren();
    $GUI->loadMap('DataBase');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
    $GUI->output();
    $GUI->saveMap('');
  } break;

  # Auswählen einer neuen Stelle
  case 'Stelle Wählen' : {
    $GUI->rollenwahl($Stelle_ID);
    $GUI->output();
  } break;
  
   # Auswählen einer neuen Stelle
  case 'Stelle_waehlen' : {
    $GUI->rollenwahl($Stelle_ID);
    $GUI->output();
  } break;

	case 'getMap_ajax' : {   
		$GUI->formvars['nurAktiveLayer'] = true;		
    $GUI->loadMap('DataBase');		
    $GUI->navMap($GUI->formvars['CMD']);
    $GUI->saveMap('');    
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();    
    $GUI->mime_type='map_ajax';
    $GUI->output();
  }break;

  default : {
    # Karteninformationen lesen
    $GUI->loadMap('DataBase');
    $GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
    $GUI->drawMap();
    $GUI->saveMap('');
    $GUI->output();
  }
}
include('end.php');
#$executiontime=microtime_float()-$starttime;
#echo date('G:i:s', time());
#if (0) { echo '<br>Laufzeit:'.$executiontime.'s'; }
?>
