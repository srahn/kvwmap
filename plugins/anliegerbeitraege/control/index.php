<?
	
	function go_switch_anliegerbeitraege($go){
		global $GUI;
		switch($go){
			# Anliegerbeiträge
			case 'anliegerbeitraege' : {
				$GUI->checkCaseAllowed($go);
				include(PLUGINS.'anliegerbeitraege/model/kvwmap.php');
				$GUI->Anliegerbeiträge_editor();
			}break;

			# Anliegerbeiträge Strasse speichern
			case 'anliegerbeitraege_strasse_speichern' : {
				include(PLUGINS.'anliegerbeitraege/model/kvwmap.php');
				include(PLUGINS.'anliegerbeitraege/model/anliegerbeitraege.php');
				$GUI->Anliegerbeiträge_strasse_speichern();
			}break;

			# Anliegerbeiträge Buffer speichern
			case 'anliegerbeitraege_buffer_speichern' : {
				include(PLUGINS.'anliegerbeitraege/model/kvwmap.php');
				include(PLUGINS.'anliegerbeitraege/model/anliegerbeitraege.php');
				$GUI->Anliegerbeiträge_buffer_speichern();
			}break;
			
			default : {
				$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
			}
		}
	}
	
?>