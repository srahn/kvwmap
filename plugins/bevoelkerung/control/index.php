<?php

function go_switch_bevoelkerung($go){
	global $GUI;		
	switch($go){
		case 'bevoelkerung_bericht' : {
			$GUI->main = PLUGINS.'bevoelkerung/view/bevoelkerung_bericht.php';
			$GUI->output();
		}break;

		case 'bevoelkerung_bericht_Bericht erstellen' : {
			$GUI->main = PLUGINS.'bevoelkerung/view/bevoelkerung_bericht.php';
			$GUI->output();
		}break;

		case 'bevoelkerung_import_prognose' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			$prog = new prognose($GUI->pgdatabase);
			$prog->import();
		} break;

		case 'bevoelkerung_transpose_prognose' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			$prog = new prognose($GUI->pgdatabase);
			$prog->transpose();
		} break;

		case 'bevoelkerung_transpose_table' : {
			$year = $_REQUEST['year'];
			$tablename = $_REQUEST['tablename'];
			if (empty($year) or empty($tablename)) {
				echo 'Es müssen die Parameter <b>year</b> und <b>tablename</b> angegeben werden.';
			}
			else {
				include(PLUGINS.'bevoelkerung/model/prognose.php');
				$prog = new prognose($GUI->pgdatabase);
				$prog->transposeTable($year, $tablename);
			}
		} break;

		case 'show_ternary_charts' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			$prog = new prognose($GUI->pgdatabase);
			$GUI->triple = $prog->find_triple($GUI->formvars['layer_parameter_jahr'], $GUI->formvars['layer_parameter_geschlecht'], 'junge', 'ew', 'alte');
			$GUI->main = PLUGINS.'bevoelkerung/view/bevoelkerung_dreiecksdiagramm.php';
			$GUI->output();
		} break;

		case 'create_ternary_charts' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			include PLUGINS.'bevoelkerung/model/chart.php';
			$prog = new prognose($GUI->pgdatabase);
			$style = array(													// Optionen
				"size"					 => 500,							//	- Größe des Charts in Pixeln (quadratisch, default 400px)
				"titleColor"		 => 'black',					//	- Farbe der Überschrift (default: black, kann Format '#xxxxxx' sein oder Farbname aus Definition s.u.)
				"axisColor"			=> 'blue',						//	- Farbe der Achsen (default: black)
				"minorAxisColor" => 'gray50',					//	- Farbe der Gitterachsen (default: axisColor)
				"valueColor1"		 => 'green',					//	- Farbe der Datenpunkte (default: green)
				"valueColor2"		 => 'blue',					//	- Farbe der Datenpunkte auf Ebene 2(default: blue)
				"valueColor3"		 => 'red',					//	- Farbe der Datenpunkte auf Ebene 2(default: red)
				"labelColor"		 => 'magenta',				//	- Farbe der Achsenbeschriftungen (default: gray75)
				"backgoundColor" => 'white',				//	- Hintergrundfarbe (default: white)
				"markerSize"		 => 7									//	- Größe der Datenpunkte in Pixeln (default: 5px)
			);
			$geschlechter = array('g' => 'Gesamtbevölkerung', 'm' => 'Bevölkerung männlich', 'w' => 'Bevölkerung weiblich');
			$axis = array("Junge", "Erwachsene", "Alte");
			$GUI->von_jahr = 15;
			$GUI->bis_jahr = 40;
			$GUI->image_maps = array();
			/*
			$chart_file = PLUGINS . 'bevoelkerung/img/tc_test.png';
			drawTernaryChart(
				$chart_file,
				array("Junge", "Erwachsene", "Alte"),
				array(array(20, 50, 30)),
				'Gesamtbevölkerung im Jahr 20' . $jahr,
				$style
			);
			*/
			for($jahr = $GUI->von_jahr; $jahr <= $GUI->bis_jahr; $jahr++) {
				foreach($geschlechter AS $geschlecht_abk => $geschlecht_label) {
					$chart_file = PLUGINS . 'bevoelkerung/img/tc_' . $geschlecht_abk . '_' . $jahr . '.png';
					if (!file_exists($chart_file)) {
						$areas = drawTernaryChart(
							$chart_file,
							$axis,
							array(
								array(
									'legend_name' => 'Nahbereiche',
									'values' => $prog->find_triple('bereichs', $jahr, $geschlecht_abk, 'junge', 'ew', 'alte', 'gebiet')
								),
								array(
									'legend_name' => 'Kreise',
									'values' => $prog->find_triple('kreis', $jahr, $geschlecht_abk, 'junge', 'ew', 'alte', 'gebiet')
								),
								array(
									'legend_name' => 'Land',
									'values' => $prog->find_triple('land', $jahr, $geschlecht_abk, 'junge', 'ew', 'alte', 'gebiet')
								)
							),
							$geschlecht_label . ' im Jahr 20' . $jahr,
							$style
						);
						$areas_file = PLUGINS . 'bevoelkerung/img/tc_' . $geschlecht_abk . '_' . $jahr . '_areas.html';
						writeImageMap(
							$areas_file,
							$areas
						);
					}
				}
			}

			$GUI->main = PLUGINS.'bevoelkerung/view/bevoelkerung_dreiecksdiagramme.php';
			$GUI->output();
		} break;

		case 'bevoelkerung_dynamische_karten' : {
			$GUI->params = $GUI->user->rolle->get_layer_params($GUI->Stelle->selectable_layer_params, $GUI->pgdatabase);
			$GUI->title = "Dynamische Karte";
			$GUI->message = "Diese Funktion erzeugt alle durch die Layer-Parameter möglichen Varianten der aktuell angezeigten Kartendarstellung und zeigt ein Formular an in dem man sich diese schnell nacheinander ansehen kann.";

			$geschlechter = array('g' => 'Gesamtbevölkerung', 'm' => 'Bevölkerung männlich', 'w' => 'Bevölkerung weiblich');
			$datenreihen = array();
			foreach ($GUI->params['datenreihe']['options'] AS $option) {
				$datenreihen[$option['value']] = $option['output'];
			}
			$GUI->von_jahr = 15;
			$GUI->bis_jahr = 40;
			$GUI->maps = array();

			for($jahr = $GUI->von_jahr; $jahr <= $GUI->bis_jahr; $jahr++) {
				foreach($geschlechter AS $geschlecht_abk => $geschlecht_label) {
					foreach($datenreihen AS $datenreihe_abk => $datenreihe_label) {
						# setze layer parameter
						rolle::$layer_params = (array)json_decode('{"jahr":"' . $jahr . '", "geschlecht":"' . $geschlecht_abk . '", "datenreihe":"' . $datenreihe_abk . '"}');
						$map_image_file = PLUGINS . 'bevoelkerung/img/maps/' . $datenreihe_abk . '_' . $geschlecht_abk . '_' . $jahr . '.jpg';

						if (!file_exists($map_image_file)) {
							# erzeuge die Karte
							$GUI->loadMap('DataBase');
							$GUI->drawMap();
							# kopiere die temporäre Datei in cache Verzeichnis und benenne um.
							rename(
								IMAGEPATH . basename($GUI->img['hauptkarte']),
								$map_image_file
							);
						}
					}
				}
			}

			$GUI->main = PLUGINS . 'bevoelkerung/view/bevoelkerung_dynamische_karten.php';
			$GUI->output();
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>