<?php
	# gui functions of plugins/metadata
	include_once(PLUGINS . 'metadata/model/GeonetworkClient.php');

	function exceptions_error_handler($severity, $message, $filename, $lineno) {
	  throw new ErrorException($message, 0, $severity, $filename, $lineno);
	}

#	set_error_handler('exceptions_error_handler');

	$GUI->metadatenSuchen = function() {
		$GUI->metadata = new metadata($GUI);
		$GUI->metadaten = $GUI->metadata->findQuickSearch($GUI->formvars);
		$GUI->main = PLUGINS . 'metadata/view/searchresults.php';
		$GUI->output();
	};

  $GUI->metadateneingabe = function() use ($GUI) {
    $metadatensatz = new metadatensatz($GUI->formvars['oid'],$GUI->pgdatabase);
    if ($GUI->formvars['oid']!='') {
      # Es handelt sich um eine Änderung eines Datensatzes
      # Auslesen der Metadaten aus der Datenbank und Zuweisung zu Formularobjekten
      $ret=$metadatensatz->getMetadaten($GUI->formvars);
      if ($ret[0]) {
        $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
      }
      else {
        $GUI->formvars=array_merge($GUI->formvars,$ret[1][0]);
      }
      $GUI->titel='Metadatenänderung';
    }
    else {
      # Anzeigen des Metadateneingabeformulars
      $GUI->titel='Metadateneingabe';
      # Zuweisen von defaultwerten für die Metadatenelemente wenn nicht vorher
      # schon ein Formular ausgefüllt wurde
      if ($GUI->formvars['mdfileid']=='') {
        $defaultvalues=$metadatensatz->readDefaultValues($GUI->user);
        $GUI->formvars=array_merge($GUI->formvars,$defaultvalues);
      }
      else {
        # Wenn das Formular erfolgreich eingetragen wurde neue mdfileid vergeben
        if ($GUI->Fehlermeldung=='') {
          $GUI->formvars['mdfileid']=rand();
        }
      }
    }
    # Erzeugen der Formularobjekte für die Schlagworteingabe
    $ret=$metadatensatz->getKeywords('','','theme','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $GUI->formvars['allthemekeywords']=$ret[1];
    }

    $ret=$metadatensatz->getKeywords('','','place','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $GUI->formvars['allplacekeywords']=$ret[1];
    }
    $GUI->allthemekeywordsFormObj = new FormObject(
			"allthemekeywords",
			"select",
			$GUI->formvars['allthemekeywords']['id'],
			explode(", ",$GUI->formvars['selectedthemekeywordids']),
			$GUI->formvars['allthemekeywords']['keyword'],
			4,
			0,
			1,
			'200'
		);
    $GUI->allplacekeywordsFormObj = new FormObject(
			"allplacekeywords",
			"select",
			$GUI->formvars['allplacekeywords']['id'],
			explode(", ",$GUI->formvars['selectedplacekeywordids']),
			$GUI->formvars['allplacekeywords']['keyword'],
			4,
			0,
			1,
			'200'
		);
    $GUI->main='metadateneingabeformular.php';
    $GUI->loadMap('DataBase');
    if ($GUI->formvars['refmap_x']!='') {
      $GUI->zoomToRefExt();
    }
    $GUI->navMap($GUI->formvars['CMD']);
    $GUI->saveMap('');
    $GUI->drawMap();
    $GUI->output();
  };

	$GUI->metadata_upload_to_geonetwork = function($data) use ($GUI) {
		$success = false;
		// echo "<br>saveData=truetruetrue send_metadata=\"${send_metadata}\"<br>";
		try {
			$geonetworkClient = new GeonetworkClient(
				METADATA_CATALOG,
				METADATA_CATALOGUSER,
				METADATA_CATALOGPASS
			);
			$connectResult = $geonetworkClient->connect();
			// echo "<textarea id=\"connectResult\" rows=\"10\" style=\"width:100%; background-color:white\"><" .
			// 	print_r($connectResult, true) . "></textarea>";
			#echo "<textarea id=\"connectResult\" rows=\"1\" style=\"width:100%; background-color:white\">angemeldet: " .
				$connectResult->name . "</textarea>";
	
			// uuidProcessing = GENERATEUUID, NOTHING, OVERWRITE
			$putMetaDataResult = $geonetworkClient->putMetaData($data, $uuidProcessing='OVERWRITE');
	
			#echo "<textarea id=\"put-result\" rows=\"10\" style=\"width:100%; background-color:white\"><" .
			 # print_r($putMetaDataResult, true) . "></textarea>";

			if ($putMetaDataResult->success) {
				$success = true;
				$msg = "war erfolgreich\nuuid:\t" . $putMetaDataResult->uuid;
				$metadataInfos = $putMetaDataResult->metadataInfos;
				$infos = array();
				foreach ($metadataInfos as $metadataInfo) {
					$infos[] = $metadataInfo[0];
				}
			} else {
				$msg = "war nicht erfolgreich\nmessage:\t" . $putMetaDataResult->message . "\ndescription: " . $putMetaDataResult->description;
			}
			#echo "<textarea id=\"connectResult\" rows=\"10\" style=\"width:100%; background-color:white\">${msg}</textarea>";

		} catch (Exception $e) {
			$msg = 'Exception abgefangen: ' .  $e->getMessage() . "\n";
			$msg .= $e->getTraceAsString();
		}
		try {
			$geonetworkClient->close();
		} catch (Exception $e) {
			$msg = 'Exception abgefangen: ' . $e->getMessage() . "\n";
			$msg .= $e->getTraceAsString();
		}
		$response = array(
			'success' => $success,
			'msg' => $msg,
			'metadataInfos' => $infos
		);
		return $response;
	}
?>