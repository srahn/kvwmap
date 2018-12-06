<?

function go_switch_geodoc($go){
	global $GUI;
	switch($go){
	  case 'spatialDocIndexing' : {
			include(PLUGINS.'geodoc/model/kvwmap.php');
			include(PLUGINS.'geodoc/model/documents.php');
			$GUI->spatialDocIndexing();
	  } break;
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>