<?php

include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'Layer.php');
// include('funktionen/input_check_functions.php');
include_once(PLUGINS . 'wasserrecht/config/config.php');
include(PLUGINS . 'wasserrecht/helper/Log.php');
include(PLUGINS . 'wasserrecht/helper/CommonClassTrait.php');
include(PLUGINS . 'wasserrecht/helper/DateHelper.php');
// include(PLUGINS . 'wasserrecht/helper/LayerNames.php');
include(PLUGINS . 'wasserrecht/model/FestsetzungsSammelbescheidDaten.php');
include(PLUGINS . 'wasserrecht/model/AufforderungsBescheidDaten.php');
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
include(PLUGINS . 'wasserrecht/model/db/gewaesserbenutzungen_umfang_name.php');
include(PLUGINS . 'wasserrecht/model/db/gewaesserbenutzungen_umfang_einheiten.php');
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

function getDocumentUrlFromPath(&$gui, $documentPath) 
{
    $document_full_path = WASSERRECHT_DOCUMENT_PATH . $documentPath;
    $gui->log->log_debug('dokument_full_path: ' . var_export($document_full_path, true));
    $gui->allowed_documents[] = addslashes($document_full_path);
    $gui->log->log_debug('this->allowed_documents: ' . var_export($gui->allowed_documents, true));
    $url = IMAGEURL . $gui->document_loader_name . '?dokument=';
    $gui->log->log_debug('url: ' . var_export($url, true));
    $absoluteURL = $url . $document_full_path;
    $gui->log->log_debug('absoluteURL: ' . var_export($absoluteURL, true));
    
    return $absoluteURL;
}

$GUI->actual_link = parse_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", PHP_URL_PATH);

/**
 * LOG
 */
$GUI->log = new Log($GUI->debug);

/**
 * Date
 */
$GUI->date = new DateHelper($GUI);

/**
 * Layer Names
 */
// $GUI->layer_names = new LayerNames($GUI, Layer::find($GUI, "true"));

// print_r($_POST);
// print_r($_REQUEST);

// if(!empty($_POST["post_action"]))
// {
//     $parts = parse_url($url);
//     print_r($_GET);
    
//     $GUI->go=htmlspecialchars($_POST["post_action"]);
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
        
        if(startsWith($keyEscaped, ERKLAERUNG_URL))
        {
            $GUI->go = WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL;
            break;
        }
        elseif($keyEscaped === VERWALTUNGSAUFWAND_BEANTRAGEN_URL)
        {
            $GUI->go = ERSTATTUNG_DES_VERWALTUNGSAUFWANDS_URL;
        }
    }
}


function go_switch_wasserrecht($go){
	global $GUI;
	switch($GUI->go){

			case WASSERENTNAHMEBENUTZER: {
					$GUI->log->log_debug(WASSERENTNAHMEBENUTZER . ' called!');
				
				$GUI->main = PLUGINS . 'wasserrecht/view/'. WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL . '.php';
				$GUI->output();
		}	break;
		
		case WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL: {
				$GUI->log->log_debug(WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL . ' called!');
				
				$GUI->main = PLUGINS . 'wasserrecht/view/'. WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL . '.php';
				$GUI->output();
		}	break;
		
		case WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL: {
				$GUI->log->log_debug(WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL . ' called!');
				
				$GUI->main = PLUGINS . 'wasserrecht/view/' . WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL .'.php';
				$GUI->output();
		}	break;
		
		case WASSERENTNAHMEENTGELT_URL: {
				$GUI->log->log_debug(WASSERENTNAHMEENTGELT_URL . ' called!');
				
				$GUI->main = PLUGINS . 'wasserrecht/view/' . WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL . '.php';
				$GUI->output();
		}	break;
		
		case WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL: {
				$GUI->log->log_debug(WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL . ' called!');
				
				$GUI->main = PLUGINS . 'wasserrecht/view/' . WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL .'.php';
				$GUI->output();
		}	break;
		
		case WASSERENTNAHMEENTGELT_FESTSETZUNG_URL: {
				$GUI->log->log_debug(WASSERENTNAHMEENTGELT_FESTSETZUNG_URL . 'called!');
				
				$GUI->main = PLUGINS . 'wasserrecht/view/' . WASSERENTNAHMEENTGELT_FESTSETZUNG_URL . '.php';
				$GUI->output();
		}	break;
		
		case ZENTRALE_STELLE_URL: {
				$GUI->log->log_debug(ZENTRALE_STELLE_URL . ' called!');
				
	// 	    if ($GUI->user->funktion === 'admin') 
	// 	    {
						$GUI->log->log_debug(ZENTRALE_STELLE_URL . ' Zugriff erlaubt');
						
						$GUI->main = PLUGINS . 'wasserrecht/view/' . ZENTRALE_STELLE_URL . '.php';
						$GUI->output();
	// 	    }
	// 	    else 
	// 	    {
	// 	        $GUI->log->log_debug(ZENTRALE_STELLE_URL . ' Zugriff verweigert');
						
	// 	        echo 'Zugriff verweigert';
	// 	    }
				
		}	break;
		
		case ERSTATTUNG_DES_VERWALTUNGSAUFWANDS_URL: {
				$GUI->log->log_debug(ERSTATTUNG_DES_VERWALTUNGSAUFWANDS_URL . ' called!');
				
				$GUI->main = PLUGINS . 'wasserrecht/view/'. ERSTATTUNG_DES_VERWALTUNGSAUFWANDS_URL . '.php';
				$GUI->output();
		}  break;

		case 'wasserrecht_deploy': {
			$GUI->checkCaseAllowed($GUI->go);
			if ($GUI->user->funktion == 'admin') {
				$GUI->main = PLUGINS . 'wasserrecht/view/deploy_form.php';
				$GUI->output();
			}
			else {
				echo 'Zugriff verweigert';
			};
		} break;

		case 'wasserrecht_deploy_Starten': {
			$GUI->checkCaseAllowed('wasserrecht_deploy');
			if ($GUI->user->funktion == 'admin') {
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
						$GUI->result['update_mysql'] = 'Datenbankverbindung fehlgeschlagen<br>' . mysqli_connect_error();
						$GUI->main = PLUGINS . 'wasserrecht/view/deploy_results.php';
						$GUI->output();
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
					$GUI->formvars['func'] = 'update_code';
					$ausgabe = $GUI->adminFunctions();
					$result['pull_git'] = $ausgabe[0];
					$result['pull_git'] .= "<br>Git Repository aktualisiert";
				}

				# reset pgsql schema
				{
					$msg = array();
					foreach (glob(PLUGINS . "wasserrecht/db/postgresql/schema/*.sql") as $filename) {
						$sql = file_get_contents($filename);
						$GUI->pgdatabase->execSQL($sql, 4, 1);
						$msg[] = basename($filename) . ' eingelesen.';
					}
					
					$result['reset_pgsql_schema'] = implode('<br>', $msg);
				}

				# reset_pgsql_data
				{
					$msg = array();
					foreach (glob(PLUGINS . "wasserrecht/db/postgresql/data/*.sql") as $filename) {
						$sql = file_get_contents($filename);
						$GUI->pgdatabase->execSQL($sql, 4, 1);
						$msg[] = basename($filename) . ' eingelesen.';
					}
					
					$result['reset_pgsql_data'] = implode('<br>', $msg);
				}

				$GUI->result = $result;
				$GUI->main = PLUGINS . 'wasserrecht/view/deploy_results.php';
				$GUI->output();
			}
			else {
				echo 'Zugriff verweigert';
			};
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>