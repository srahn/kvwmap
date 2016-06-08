<?php

	$this->goNotExecutedInPlugins = false;
		
	switch($this->go){
		case 'bevoelkerung_bericht' : {
			$this->main = PLUGINS.'bevoelkerung/view/bevoelkerung_bericht.php';
			$this->output();
		}break;

		case 'bevoelkerung_bericht_Bericht erstellen' : {
			$this->main = PLUGINS.'bevoelkerung/view/bevoelkerung_bericht.php';
			$this->output();
		}break;

		case 'bevoelkerung_import_prognose' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			$prog = new prognose($this->pgdatabase);
			$prog->import();
		} break;

		case 'bevoelkerung_transpose_prognose' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			$prog = new prognose($this->pgdatabase);
			$prog->transpose();
		} break;

		case 'show_ternary_charts' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			$prog = new prognose($this->pgdatabase);
			$this->triple = $prog->find_triple($this->formvars['layer_parameter_jahr'], $this->formvars['layer_parameter_geschlecht'], 'junge', 'ew', 'alte');
			$this->main = PLUGINS.'bevoelkerung/view/bevoelkerung_dreiecksdiagramm.php';
			$this->output();
		} break;

		case 'create_ternary_charts' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			include PLUGINS.'bevoelkerung/model/chart.php';
			$prog = new prognose($this->pgdatabase);
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
			$this->von_jahr = 15;
			$this->bis_jahr = 40;
			$this->image_maps = array();
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
			for($jahr = $this->von_jahr; $jahr <= $this->bis_jahr; $jahr++) {
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

			$this->main = PLUGINS.'bevoelkerung/view/bevoelkerung_dreiecksdiagramme.php';
			$this->output();
		} break;

		case 'bevoelkerung_dynamische_karten' : {
			$this->params = $this->user->rolle->get_layer_params($this->Stelle->selectable_layer_params, $this->pgdatabase);
			$this->title = "Dynamische Karte";
			$this->message = "Diese Funktion erzeugt alle durch die Layer-Parameter möglichen Varianten der aktuell angezeigten Kartendarstellung und zeigt ein Formular an in dem man sich diese schnell nacheinander ansehen kann.";

			$geschlechter = array('g' => 'Gesamtbevölkerung', 'm' => 'Bevölkerung männlich', 'w' => 'Bevölkerung weiblich');
			$datenreihen = array();
			foreach ($this->params['datenreihe']['options'] AS $option) {
				$datenreihen[$option['value']] = $option['output'];
			}
			$this->von_jahr = 15;
			$this->bis_jahr = 40;
			$this->maps = array();

			for($jahr = $this->von_jahr; $jahr <= $this->bis_jahr; $jahr++) {
				foreach($geschlechter AS $geschlecht_abk => $geschlecht_label) {
					foreach($datenreihen AS $datenreihe_abk => $datenreihe_label) {
						# setze layer parameter
						rolle::$layer_params = (array)json_decode('{"jahr":"' . $jahr . '", "geschlecht":"' . $geschlecht_abk . '", "datenreihe":"' . $datenreihe_abk . '"}');
						$map_image_file = PLUGINS . 'bevoelkerung/img/maps/' . $datenreihe_abk . '_' . $geschlecht_abk . '_' . $jahr . '.jpg';

						if (!file_exists($map_image_file)) {
							# erzeuge die Karte
							$this->loadMap('DataBase');
							$this->drawMap();
							# kopiere die temporäre Datei in cache Verzeichnis und benenne um.
							rename(
								IMAGEPATH . basename($this->img['hauptkarte']),
								$map_image_file
							);
						}
					}
				}
			}

			$this->main = PLUGINS . 'bevoelkerung/view/bevoelkerung_dynamische_karten.php';
			$this->output();
		} break;

		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	
?>