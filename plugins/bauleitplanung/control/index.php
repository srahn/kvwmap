<?

function go_switch_bauleitplanung($go){
	global $GUI;
	switch($go){  
		case 'delete_bplan' : {
			include(PLUGINS.'bauleitplanung/model/rok.php');
			$mapdb = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
			$layerdb = $mapdb->getlayerdatabase($GUI->formvars['selected_layer_id'], $GUI->Stelle->pgdbhost);
			$rok = new rok($layerdb);
			$rok->delete_bplan($GUI->formvars['plan_id']);
			if($GUI->formvars['value_b_plan_stammdaten_oid'] == ''){
				$GUI->GenerischeSuche_Suchen();		# Trefferliste wieder anzeigen
			}
			else{
				$GUI->loadMap('DataBase');					# Karte anzeigen
				$currenttime=date('Y-m-d H:i:s',time());
				$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
				$GUI->drawMap();
				$GUI->saveMap('');
				$GUI->output();
			}
		} break;
		
		case 'delete_fplan' : {
			include(PLUGINS.'bauleitplanung/model/rok.php');
			$mapdb = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
			$layerdb = $mapdb->getlayerdatabase($GUI->formvars['selected_layer_id'], $GUI->Stelle->pgdbhost);
			$rok = new rok($layerdb);
			$rok->delete_fplan($GUI->formvars['plan_id']);
			if($GUI->formvars['value_f_plan_stammdaten_oid'] == ''){
				$GUI->GenerischeSuche_Suchen();		# Trefferliste wieder anzeigen
			}
			else{
				$GUI->loadMap('DataBase');					# Karte anzeigen
				$currenttime=date('Y-m-d H:i:s',time());
				$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
				$GUI->drawMap();
				$GUI->saveMap('');
				$GUI->output();
			}
		} break;
		
		case 'copy_bplan' : {
			include(PLUGINS.'bauleitplanung/model/rok.php');
			$mapdb = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
			$layerdb = $mapdb->getlayerdatabase($GUI->formvars['selected_layer_id'], $GUI->Stelle->pgdbhost);
			$rok = new rok($layerdb);
			$GUI->formvars['value_b_plan_stammdaten_oid'] = $rok->copy_bplan($GUI->formvars['plan_id']);
			$GUI->GenerischeSuche_Suchen();
		} break;
		
		case 'copy_fplan' : {
			include(PLUGINS.'bauleitplanung/model/rok.php');
			$mapdb = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
			$layerdb = $mapdb->getlayerdatabase($GUI->formvars['selected_layer_id'], $GUI->Stelle->pgdbhost);
			$rok = new rok($layerdb);
			$GUI->formvars['value_b_plan_stammdaten_oid'] = $rok->copy_fplan($GUI->formvars['plan_id']);
			$GUI->GenerischeSuche_Suchen();
		} break;
		
		case 'update_bplan_from_rok' : {
			include(PLUGINS.'bauleitplanung/model/rok.php');
			$mapdb = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
			$layerdb = $mapdb->getlayerdatabase($GUI->formvars['selected_layer_id'], $GUI->Stelle->pgdbhost);
			$rok = new rok($layerdb);
			$rok->update_bplan_from_rok($GUI->formvars['plan_id']);
			$GUI->GenerischeSuche_Suchen();
		} break;
		
		case 'update_fplan_from_rok' : {
			include(PLUGINS.'bauleitplanung/model/rok.php');
			$mapdb = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
			$layerdb = $mapdb->getlayerdatabase($GUI->formvars['selected_layer_id'], $GUI->Stelle->pgdbhost);
			$rok = new rok($layerdb);
			$rok->update_fplan_from_rok($GUI->formvars['plan_id']);
			$GUI->GenerischeSuche_Suchen();
		} break;
		
		case 'zoomtobplan' : {
			include(PLUGINS.'bauleitplanung/model/rok.php');
			$rok = new rok($GUI->pgdatabase);
			$rect = $rok->getExtentFromRokNrBplan($GUI->formvars['roknr'], $GUI->formvars['art'], 10, $GUI->user->rolle->epsg_code);
			$GUI->loadMap('DataBase');
			if ($rect->minx!=0 and $rect->miny!=0 and $rect->maxx!=0 and $rect->maxy!=0) {
				$GUI->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
				if (MAPSERVERVERSION > 600) {
					$GUI->map_scaledenom = $GUI->map->scaledenom;
				}
				else {
					$GUI->map_scaledenom = $GUI->map->scale;
				}
			}
			else {
				$GUI->Fehlermeldung='Es konnte kein Geltungsbereich mit ROK-Nr. = '.$GUI->formvars['roknr'].' gefunden werden.';
			}
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
		} break;
		
		case 'zoomtofplan' : {
			include(PLUGINS.'bauleitplanung/model/rok.php');
			$rok = new rok($GUI->pgdatabase);
			$rect = $rok->getExtentFromRokNrFplan($GUI->formvars['gkz'], 10, $GUI->user->rolle->epsg_code);
			$GUI->loadMap('DataBase');
			if ($rect->minx!=0 and $rect->miny!=0 and $rect->maxx!=0 and $rect->maxy!=0) {
				$GUI->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);	  
			}
			else {
				$GUI->Fehlermeldung='Es konnte keine Gemeinde mit GKZ = '.$GUI->formvars['gkz'].' gefunden werden.';
			}
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
		} break;
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>