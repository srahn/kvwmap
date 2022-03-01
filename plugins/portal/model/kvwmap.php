<?php
	/*
	* Cases:
	* Layer2Stelle_Reihenfolge_Layerdef
	* 
	*/
	$GUI->Layer2Stelle_Reihenfolge_Layerdef = function() use ($GUI) {
		$pfad = WWWROOT . APPLVERSION . 'tools/';
		$file = 'layerdef.json';
		$GUI->selected_stelle = new stelle($GUI->formvars['selected_stelle_id'], $GUI->user->database);
		$layerdef = $GUI->selected_stelle->get_layerdef();
		if (defined('LAYERDEF_EXPORT_FILE') AND LAYERDEF_EXPORT_FILE != '') {
			if (!file_exists(LAYERDEF_EXPORT_FILE)) {
				$GUI->add_message('notice', 'Datei ' . LAYERDEF_EXPORT_FILE . ' wird neu angelegt!');
			}
			if (is_writable(LAYERDEF_EXPORT_FILE)) {
				file_put_contents(LAYERDEF_EXPORT_FILE, json_encode($layerdef));
				$GUI->add_message('notice', 'Layerdef erfolgreich in Datei: ' . LAYERDEF_EXPORT_FILE . ' geschrieben!');
			}
			else {
				$GUI->add_message('error', 'Keine Berechtigung zum Schreiben in Datei: ' . LAYERDEF_EXPORT_FILE . '!');
			}
			$GUI->t_visible = 5000;
			$GUI->go = 'Layer2Stelle_Reihenfolge';
			$GUI->goNotExecutedInPlugins = true;
		}
		else {
			echo json_encode($layerdef);
		}
	};
?>