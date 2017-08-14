<?php
$this->goNotExecutedInPlugins = false;
include_once(CLASSPATH . 'PgObject.php');
// include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'Layer.php');
// include_once(CLASSPATH . 'LayerClass.php');
// include_once(CLASSPATH . 'LayerAttribute.php');
// include_once(CLASSPATH . 'Style2Class.php');
// include_once(CLASSPATH . 'Label2Class.php');
// #include_once(CLASSPATH . 'LayerGroup.php');
// include_once(CLASSPATH . 'data_import_export.php');
include(PLUGINS . 'wasserrecht/model/WrPgObject.php');
include(PLUGINS . 'wasserrecht/model/anlage.php');
include(PLUGINS . 'wasserrecht/model/personen.php');
include(PLUGINS . 'wasserrecht/model/adresse.php');
include(PLUGINS . 'wasserrecht/model/behoerde.php');
include(PLUGINS . 'wasserrecht/model/gewaesserbenutzungen.php');
include(PLUGINS . 'wasserrecht/model/gewaesserbenutzungen_umfang.php');
include(PLUGINS . 'wasserrecht/model/gewaesserbenutzungen_art.php');
include(PLUGINS . 'wasserrecht/model/gewaesserbenutzungen_zweck.php');
include(PLUGINS . 'wasserrecht/model/wasserrechtliche_zulassungen.php');
include(PLUGINS . 'wasserrecht/model/wasserrechtliche_zulassungen_gueltigkeit.php');
include(PLUGINS . 'wasserrecht/model/WRZProGueltigkeitsJahr.php');

require_once (CLASSPATH . '/../vendor/autoload.php');
// include(PLUGINS . 'wasserrecht/view/KvwmapJodDocumentConverter.class.php');
include(PLUGINS . 'wasserrecht/view/create_pdf.php');

/**
* Anwendungsfälle
* wasserentnahmebenutzer
* wasserrecht_deploy
* wasserrecht_deploy_Starten
*/

$this->actual_link = parse_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", PHP_URL_PATH);

//$anlage = new Anlage($this);
//$anlagen = $anlage->find_where('true');

//$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
//$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
//$this->debug->write(var_dump($layerdb), 4);

/*        $this->loadMap('DataBase');
 $layer_names = array();
 foreach($this->layerset['layer_ids'] AS $id => $layer) {
 $layer_names[$layer['Name']] = $id;
 }
 $this->layer_names = $layer_names;
 */

// 	    $layer_name = 'Wasserrechtliche_Zulassungen';
// 	    $this->layers = Layer::find($this, "Name = '" . $layer_name . "'");
$this->layers = Layer::find($this, "true");
// 	    var_dump(count($this->layers));
$layer_names = array();
for ($i = 0; $i <= count($this->layers); $i++) {
    if(isset($this->layers[$i]))
    {
        //echo $this->layers[$i]->get('Name');
        $layer_name = $this->layers[$i]->get('Name');
        $layer_id = $this->layers[$i]->get('Layer_ID');
        $layer_names[$layer_name] = $layer_id;
    }
}
$this->layer_names = $layer_names;
// 	    $this->layers = $layers;
// 	    echo $this->layers[0]->get('Name');

// print_r($_POST);
// print_r($_REQUEST);

// if(isset($_POST["post_action"]) && $_POST["post_action"] === "aufforderung_date_insert")
// {
//     $parts = parse_url($url);
//     print_r($_GET);
    
//     $this->go="wasserentnahmebenutzer";
// }

switch($this->go){

	case 'wasserentnahmebenutzer': {
	    $this->debug->write('wasserentnahmebenutzer called!', 4);
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmebenutzer.php';
	    $this->output();
	}	break;
	
	case 'wasserentnahmeentgelt': {
	    $this->debug->write('wasserentnahmeentgelt called!', 4);
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmeentgelt.php';
	    $this->output();
	}	break;

	case 'wasserrecht_deploy': {
		$this->checkCaseAllowed($this->go);
		if ($this->user->funktion == 'admin') {
			$this->main = PLUGINS . 'wasserrecht/view/deploy_form.php';
			$this->output();
		}
		else {
			echo 'Zugriff verweigert';
		};
	} break;

	case 'wasserrecht_deploy_Starten': {
		$this->checkCaseAllowed('wasserrecht_deploy');
		if ($this->user->funktion == 'admin') {
			$result = array(
				'update_mysql' => 'Fehler',
				'pull_git' => 'Fehlgeschlagen',
				'reset_pgsql_schema' => 'Fehler',
				'reset_pgsql_data' => 'Fehler'
			);

			# update MySQL-Database
			{
				$mysqli = new mysqli("mysql", "kvwmap", "Laridae_Moewe1", "kvwmapdb_wr");
				if (mysqli_connect_errno()) {
					$this->result['update_mysql'] = 'Datenbankverbindung fehlgeschlagen<br>' . mysqli_connect_error();
					$this->main = PLUGINS . 'wasserrecht/view/deploy_results.php';
					$this->output();
					exit();
				}
				$sql_dump .= file_get_contents($_FILES['file']['tmp_name']);
				$msg = array();
				if (strpos($sql_dump, 'phpMyAdmin SQL Dump') === false) {
					$msg[] = 'Datei ' . $_FILES['file']['name'] . ' ist MySQL-Dump.';
				}
				else {
					$sql .= "DROP DATABASE kvwmapdb_wr;";
					$msg[] = 'Lösche Datenbank kvwmapdb_wr.';
					$sql .= "CREATE DATABASE kvwmapdb_wr;";
					$msg[] = 'Erzeuge neue Datenbank kvwmapdb_wr.';
					$sql .= "USE kvwmapdb_wr;";
					$sql .= $sql_dump;
					$msg[] = 'Befülle Datenbank kvwmapdb_wr.';
					if ($mysqli->multi_query($sql)) {
						do {
							/* store first result set */
							if ($result = $mysqli->store_result()) {
								while ($row = $result->fetch_row()) {
									$msg[] = $row[0];
								}
								$result->free();
							}
						} while ($mysqli->next_result());
					}
				}
				$mysqli->close();
				$msg[] = 'MySQL-Datenbank kvwmapdb_wr erfolgreich ausgetauscht.';
				$result['update_mysql'] = implode('<p>', $msg);
			}

			# pull git repository
			{
				$this->formvars['func'] = 'update_code';
				$ausgabe = $this->adminFunctions();
				$result['pull_git'] = $ausgabe[0];
				$result['pull_git'] .= "<br>Git Repository aktualisiert";
			}

			# reset pgsql schema
			{
				$msg = array();
				foreach (glob(PLUGINS . "wasserrecht/db/postgresql/schema/*.sql") as $filename) {
					$sql = file_get_contents($filename);
					$this->pgdatabase->execSQL($sql, 4, 1);
					$msg[] = basename($filename) . ' eingelesen.';
				}
				
				$result['reset_pgsql_schema'] = implode('<br>', $msg);
			}

			# reset_pgsql_data
			{
				$msg = array();
				foreach (glob(PLUGINS . "wasserrecht/db/postgresql/data/*.sql") as $filename) {
					$sql = file_get_contents($filename);
					$this->pgdatabase->execSQL($sql, 4, 1);
					$msg[] = basename($filename) . ' eingelesen.';
				}
				
				$result['reset_pgsql_data'] = implode('<br>', $msg);
			}

			$this->result = $result;
			$this->main = PLUGINS . 'wasserrecht/view/deploy_results.php';
			$this->output();
		}
		else {
			echo 'Zugriff verweigert';
		};
	} break;

	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
	}
}

?>