<?php
  include('models/haltestelle.php');

  switch($_REQUEST['request']) {
	case 'findByRadius' : {
	  $haltestelle = new Haltestelle();
	  $haltestellen = $haltestelle->findByRadius($_REQUEST['lat'], $_REQUEST['lng'], $_REQUEST['radius']);
	  Haltestelle::output($haltestellen, $_REQUEST['radius'], $_REQUEST['format']);
	} break;

	default : {
	  echo 'Geben Sie im Parameter request einen der folgenden Werte an:<br>';
	  echo 'findByRadius';
	}
  }
?>