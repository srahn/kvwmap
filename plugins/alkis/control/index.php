<?php

function go_switch_alkis($go){
	global $GUI;
	switch($go){

		case "Flurstueckshistorie_drucken" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstueckshistorie_drucken();
		} break;
		
		case "Flurstueckshistorie" : {
			$GUI->sanitize(['flurstueckskennzeichen' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstueckshistorie();
			$GUI->output();
		} break;
		
		case "ZoomToFlst" : {
			$GUI->sanitize(['FlurstKennz' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
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
		
		case "Adresse_Auswaehlen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed($go);
			$GUI->adresswahl();
			$GUI->output();
		} break;

		case "ALK-Adresse_Auswaehlen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed($go);
			$GUI->formvars['ALK_Suche'] = 1;
			$GUI->adresswahl();
			$GUI->output();
		} break;

		case "Adresse_Auswaehlen_Suchen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->adresseSuchen();
			$GUI->output();
		} break;

		case "ALK-Adresse_Auswaehlen_Suchen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->adresseSuchen();
			$GUI->output();
		} break;

		case "Flurstueck_hist_Auswaehlen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed($go);
			$GUI->formvars['history_mode'] = 'historisch';
			$GUI->flurstwahl();
			$GUI->output();
		} break;

		case "ALK-Flurstueck_Auswaehlen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed($go);
			$GUI->formvars['ALK_Suche'] = 1;
			$GUI->flurstwahl();
			$GUI->output();
		} break;

		case "Flurstueck_Auswaehlen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed($go);
			$GUI->flurstwahl();
			$GUI->output();
		} break;

		case "Flurstueck_GetVersionen" : {
			$GUI->sanitize(['flurstkennz' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->Flurstueck_GetVersionen();
		} break;

		case "Flurstueck_Auswaehlen_Suchen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstSuchen();
			$GUI->output();
		} break;

		case "ALK-Flurstueck_Auswaehlen_Suchen" : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstSuchen();
			$GUI->output();
		} break;
		
		case 'ALKIS_WSDL' : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->ALKIS_WSDL();
		} break;

		case 'ALKIS_Auszug' : {
			$GUI->sanitize(['formnummer' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$flurst_array = explode(';', $GUI->formvars['FlurstKennz']);
			$GUI->ALKIS_Auszug($flurst_array, $GUI->formvars['Grundbuchbezirk'], $GUI->formvars['Grundbuchblatt'], $GUI->formvars['Buchungsstelle'], $GUI->formvars['formnummer']);
		} break;

		case  'ALB_Anzeige' : {
			$GUI->sanitize(['FlurstKennz' => 'text', 'formnummer' => 'text', 'wz' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$flurst_array = explode(';', $GUI->formvars['FlurstKennz']);
			$GUI->ALB_Anzeigen($flurst_array,$GUI->formvars['formnummer'], NULL, NULL);
		} break;

		case  'ALB_Anzeige_Bestand' : {
			$GUI->sanitize(['Grundbuchbezirk' => 'text', 'Grundbuchblatt' => 'text', 'formnummer' => 'text', 'wz' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->ALB_Anzeigen(NULL, $GUI->formvars['formnummer'], $GUI->formvars['Grundbuchbezirk'], $GUI->formvars['Grundbuchblatt']);
		} break;
		
		case  'generischer_Flurstuecksauszug' : {
			$GUI->sanitize(['selected_layer_id' => 'text', 'formnummer' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$flurst_array = explode(';', $GUI->formvars['FlurstKennz']);
			$GUI->generischer_Flurstuecksauszug($flurst_array);
		} break;
		
		# Eigentuemerfortführung
		case 'Adressaenderungen_Export' : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed($go);
			$GUI->export_Adressaenderungen();
		} break;

		# Eigentuemerfortführung
		case 'Adressaenderungen_Export_Exportieren' : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed('Adressaenderungen_Export');
			$GUI->export_Adressaenderungen_exportieren();
		} break;
		
		case 'Flurstuecks-CSV-Export' : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->export_flurst_csv();
		} break;

		case 'Flurstuecks-CSV-Export_Auswahl_speichern' : {
			$GUI->sanitize(['name' => 'text', 'attributes' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->export_flurst_csv_auswahl_speichern();
		} break;

		case 'Flurstuecks-CSV-Export_Auswahl_laden' : {
			$GUI->sanitize(['selection' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->export_flurst_csv_auswahl_laden();
		} break;

		case 'Flurstuecks-CSV-Export_Auswahl_loeschen' : {
			$GUI->sanitize(['selection' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->export_flurst_csv_auswahl_loeschen();
		} break;

		case 'Flurstuecks-CSV-Export_Exportieren' : {
			$GUI->sanitize(['FlurstKennz' => 'text', 'formnummer' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->export_flurst_csv_exportieren();
		} break;

		case 'Grundbuchblatt_Auswaehlen' : {
			$GUI->sanitize(['Bezirk' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed($go);
			$GUI->grundbuchblattWahl();
		} break;

		case 'Grundbuchblatt_Auswaehlen_Suchen' : {
			$GUI->sanitize(['selBlatt' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed('Grundbuchblatt_Auswaehlen');
			if($GUI->last_query != ''){
				$GUI->formvars['selBlatt'] = $GUI->last_query[0]['sql'];
			}
			$GUI->grundbuchblattSuchen();
		} break;

		case 'Flurstueck_Anzeigen' : {
			$GUI->sanitize(['FlurstKennz' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed($go);
			if($GUI->last_query != ''){
				$GUI->formvars['FlurstKennz'] = $GUI->last_query[$GUI->last_query['layer_ids'][0]]['sql'];
			}
			$explodedFlurstKennz = explode(';',$GUI->formvars['FlurstKennz']);
			$GUI->flurstAnzeige($explodedFlurstKennz);
			$GUI->output();
		} break;
		
		case 'Namen_Auswaehlen' : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->namenWahl();
		} break;

		case 'Namen_Auswaehlen_Suchen' : {
			$GUI->sanitize([
				'name*' => 'text', 
				'gml_id' => 'text', 
				'bezirk' => 'text', 
				'blatt' => 'text', 
				'GemkgID' => 'text', 
				'FlurID' => 'int', 
				'anzahl' => 'int', 
				'offset' => 'int',
				'order' => 'text']);			
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->checkCaseAllowed('Namensuche');
			$GUI->nameSuchen();
		} break;

		case 'Suche_Flurstuecke_zu_Grundbuechern' : {
			$GUI->sanitize(['selBlatt' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstuecksSucheByGrundbuecher();
		} break;

		case 'Zeige_Flurstuecke_zu_Grundbuechern' : {
			$GUI->sanitize(['selBlatt' => 'text']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstuecksAnzeigeByGrundbuecher();
		} break;

		case 'Suche_Flurstuecke_zu_Namen' : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstuecksSucheByNamen();
		} break;

		case 'Zeige_Flurstuecke_zu_Namen' : {
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstuecksAnzeigeByNamen();
		} break;

		case "Suche_Flurstueck_zu_LatLng" : {
			$GUI->sanitize(['latitude' => 'float', 'longitude' => 'float']);
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			$GUI->flurstSuchenByLatLng();
			$GUI->output();
		} break;		
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
?>