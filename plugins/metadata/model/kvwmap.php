<?php

	$GUI->metadatenSuchen = function() {
		$GUI->metadata = new metadata($GUI);
		$GUI->metadaten = $GUI->metadata->findQuickSearch($GUI->formvars);
		$GUI->main = PLUGINS . 'metadata/view/searchresults.php';
		$GUI->output();
	};

  $GUI->metadateneingabe = function() {
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
?>