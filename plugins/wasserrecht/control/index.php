<?php
$this->goNotExecutedInPlugins = false;
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'Layer.php');
// include('funktionen/input_check_functions.php');
include_once(PLUGINS . 'wasserrecht/config/config_sample.php');
include(PLUGINS . 'wasserrecht/helper/Log.php');
include(PLUGINS . 'wasserrecht/helper/CommonClassTrait.php');
include(PLUGINS . 'wasserrecht/helper/DateHelper.php');
include(PLUGINS . 'wasserrecht/model/FestsetzungsSammelbescheidDaten.php');
include(PLUGINS . 'wasserrecht/model/WRZProGueltigkeitsJahre.php');
include(PLUGINS . 'wasserrecht/model/WRZProGueltigkeitsJahreArray.php');
include(PLUGINS . 'wasserrecht/model/db/WrPgObject.php');
include(PLUGINS . 'wasserrecht/model/db/anlage.php');
include(PLUGINS . 'wasserrecht/model/db/personen.php');
include(PLUGINS . 'wasserrecht/model/db/adresse.php');
include(PLUGINS . 'wasserrecht/model/db/konto.php');
include(PLUGINS . 'wasserrecht/model/db/behoerde.php');
include(PLUGINS . 'wasserrecht/model/db/behoerde_art.php');
include(PLUGINS . 'wasserrecht/model/db/dokument.php');
include(PLUGINS . 'wasserrecht/model/db/bescheid.php');
include(PLUGINS . 'wasserrecht/model/db/aufforderung.php');
include(PLUGINS . 'wasserrecht/model/db/erklaerung.php');
include(PLUGINS . 'wasserrecht/model/db/festsetzung.php');
include(PLUGINS . 'wasserrecht/model/db/gewaesserbenutzungen.php');
include(PLUGINS . 'wasserrecht/model/db/gewaesserbenutzungen_umfang.php');
include(PLUGINS . 'wasserrecht/model/db/gewaesserbenutzungen_art.php');
include(PLUGINS . 'wasserrecht/model/db/gewaesserbenutzungen_zweck.php');
include(PLUGINS . 'wasserrecht/model/db/gewaesserbenutzungen_wee_satz.php');
include(PLUGINS . 'wasserrecht/model/db/gewaesserbenutzungen_art_benutzung.php');
include(PLUGINS . 'wasserrecht/model/db/mengenbestimmung.php');
include(PLUGINS . 'wasserrecht/model/db/teilgewaesserbenutzungen.php');
include(PLUGINS . 'wasserrecht/model/db/teilgewaesserbenutzungen_art.php');
include(PLUGINS . 'wasserrecht/model/db/wasserrechtliche_zulassungen.php');

require_once (CLASSPATH . '/../vendor/autoload.php');
// include(PLUGINS . 'wasserrecht/view/KvwmapJodDocumentConverter.class.php');
include(PLUGINS . 'wasserrecht/bescheide/create_bescheide.php');

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    
    return (substr($haystack, -$length) === $needle);
}

// function findIdFromValueString(&$gui, $valueEscaped)
// {
//     $gui->log->log_info('*** findIdFromValueString ***');
    
//     $gui->log->log_debug('valueEscaped: ' . $valueEscaped);
    
//     $lastIndex = strripos($valueEscaped, "_");
//     $gewaesserbenutzungId = substr($valueEscaped, $lastIndex + 1);
//     $wrzId = substr($valueEscaped, 0, $lastIndex);
    
//     $returnArray = array(
//         "wrz_id" => $wrzId,
//         "gewaesserbenutzung_id" => $gewaesserbenutzungId
//     );
    
//     $gui->log->log_debug('returnArray: ' . var_export($returnArray, true));
    
//     return $returnArray;
// }

function findIdAndYearFromValueString(&$gui, $valueEscaped)
{
    $gui->log->log_info('*** findIdAndYearFromValueString ***');
    $gui->log->log_debug('valueEscaped: ' . $valueEscaped);
    
    $lastIndex = strripos($valueEscaped, "_");
    $firstIndex = strpos($valueEscaped, "_");
    
    $wrzId = substr($valueEscaped, 0, $firstIndex);
    $gewaesserbenutzungId = substr($valueEscaped, $firstIndex + 1, $lastIndex - 2);
    $erhebungsjahr = substr($valueEscaped, $lastIndex + 1);
    
    $returnArray = array(
        "wrz_id" => $wrzId,
        "gewaesserbenutzung_id" => $gewaesserbenutzungId,
        "erhebungsjahr" => $erhebungsjahr
    );
    
    $gui->log->log_debug('returnArray: ' . var_export($returnArray, true));
    
    return $returnArray;
}

$this->actual_link = parse_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", PHP_URL_PATH);

/**
 * LOG
 */
$this->log = new Log($this->debug);

/**
 * Date
 */
$this->date = new DateHelper($this);


//$anlage = new Anlage($this);
//$anlagen = $anlage->find_where('true');

//$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
//$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
//$this->log->log_debug(var_dump($layerdb));

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

// if(!empty($_POST["post_action"]))
// {
//     $parts = parse_url($url);
//     print_r($_GET);
    
//     $this->go=htmlspecialchars($_POST["post_action"]);
// }

if($_SERVER ["REQUEST_METHOD"] == "POST")
{
//     print_r($_POST);
    
    foreach($_POST as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        if(is_array($value)) //skip arrays
        {
            break;
        }
        $valueEscaped = htmlspecialchars($value);
        
        if(startsWith($keyEscaped, "erklaerung_"))
        {
            $this->go = "wasserentnahmeentgelt_erklaerung_der_entnahme";
            break;
        }
        elseif($keyEscaped === "verwaltungsaufwand_beantragen")
        {
            $this->go = "erstattung_des_verwaltungsaufwands";
        }
    }
}

/**
 * Anwendungsfälle
 * wasserentnahmebenutzer
 * wasserrecht_deploy
 * wasserrecht_deploy_Starten
 */
switch($this->go){

	case 'wasserentnahmebenutzer': {
	    $this->log->log_debug('wasserentnahmebenutzer called!');
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmebenutzer_aufforderung_zur_erklaerung.php';
	    $this->output();
	}	break;
	
	case 'wasserentnahmebenutzer_aufforderung_zur_erklaerung': {
	    $this->log->log_debug('wasserentnahmebenutzer_aufforderung_zur_erklaerung called!');
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmebenutzer_aufforderung_zur_erklaerung.php';
	    $this->output();
	}	break;
	
	case 'wasserentnahmebenutzer_entgeltbescheid': {
	    $this->log->log_debug('wasserentnahmebenutzer_entgeltbescheid called!');
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmebenutzer_entgeltbescheid.php';
	    $this->output();
	}	break;
	
	case 'wasserentnahmeentgelt': {
	    $this->log->log_debug('wasserentnahmeentgelt called!');
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmeentgelt_erklaerung_der_entnahme.php';
	    $this->output();
	}	break;
	
	case 'wasserentnahmeentgelt_erklaerung_der_entnahme': {
	    $this->log->log_debug('wasserentnahmeentgelt_erklaerung_der_entnahme called!');
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmeentgelt_erklaerung_der_entnahme.php';
	    $this->output();
	}	break;
	
	case 'wasserentnahmeentgelt_festsetzung': {
	    $this->log->log_debug('wasserentnahmeentgelt_festsetzung called!');
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmeentgelt_festsetzung.php';
	    $this->output();
	}	break;
	
	case 'zentrale_stelle': {
	    $this->log->log_debug('zentrale_stelle called!');
	    
	    if ($this->user->funktion === 'admin') 
	    {
	        $this->log->log_debug('zentrale_stelle Zugriff erlaubt');
	        
	        $this->main = PLUGINS . 'wasserrecht/view/zentrale_stelle.php';
	        $this->output();
	    }
	    else 
	    {
	        $this->log->log_debug('zentrale_stelle Zugriff verweigert');
	        
	        echo 'Zugriff verweigert';
	    }
	    
	}	break;
	
	case 'erstattung_des_verwaltungsaufwands': {
	    $this->log->log_debug('erstattung_des_verwaltungsaufwands called!');
	    
	    $this->main = PLUGINS . 'wasserrecht/view/erstattung_des_verwaltungsaufwands.php';
	    $this->output();
	}  break;

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