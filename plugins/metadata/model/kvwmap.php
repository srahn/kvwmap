<?php
	$GUI = $this;

	$this->metadatenSuchen = function() {
		$this->metadata = new metadata($this);
		$this->metadaten = $this->metadata->findQuickSearch($this->formvars);
		$this->main = PLUGINS . 'metadata/view/searchresults.php';
		$this->output();
	};

  $this->metadateneingabe = function() {
    $metadatensatz = new metadatensatz($this->formvars['oid'],$this->pgdatabase);
    if ($this->formvars['oid']!='') {
      # Es handelt sich um eine Änderung eines Datensatzes
      # Auslesen der Metadaten aus der Datenbank und Zuweisung zu Formularobjekten
      $ret=$metadatensatz->getMetadaten($this->formvars);
      if ($ret[0]) {
        $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
      }
      else {
        $this->formvars=array_merge($this->formvars,$ret[1][0]);
      }
      $this->titel='Metadatenänderung';
    }
    else {
      # Anzeigen des Metadateneingabeformulars
      $this->titel='Metadateneingabe';
      # Zuweisen von defaultwerten für die Metadatenelemente wenn nicht vorher
      # schon ein Formular ausgefüllt wurde
      if ($this->formvars['mdfileid']=='') {
        $defaultvalues=$metadatensatz->readDefaultValues($this->user);
        $this->formvars=array_merge($this->formvars,$defaultvalues);
      }
      else {
        # Wenn das Formular erfolgreich eingetragen wurde neue mdfileid vergeben
        if ($this->Fehlermeldung=='') {
          $this->formvars['mdfileid']=rand();
        }
      }
    }
    # Erzeugen der Formularobjekte für die Schlagworteingabe
    $ret=$metadatensatz->getKeywords('','','theme','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $this->formvars['allthemekeywords']=$ret[1];
    }

    $ret=$metadatensatz->getKeywords('','','place','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $this->formvars['allplacekeywords']=$ret[1];
    }
    $this->allthemekeywordsFormObj = new FormObject(
			"allthemekeywords",
			"select",
			$this->formvars['allthemekeywords']['id'],
			explode(", ",$this->formvars['selectedthemekeywordids']),
			$this->formvars['allthemekeywords']['keyword'],
			4,
			0,
			1,
			'200'
		);
    $this->allplacekeywordsFormObj = new FormObject(
			"allplacekeywords",
			"select",
			$this->formvars['allplacekeywords']['id'],
			explode(", ",$this->formvars['selectedplacekeywordids']),
			$this->formvars['allplacekeywords']['keyword'],
			4,
			0,
			1,
			'200'
		);
    $this->main='metadateneingabeformular.php';
    $this->loadMap('DataBase');
    if ($this->formvars['refmap_x']!='') {
      $this->zoomToRefExt();
    }
    $this->navMap($this->formvars['CMD']);
    $this->saveMap('');
    $this->drawMap();
    $this->output();
  };
?>