<?php
  include('models/schulstandort.php');

  switch($_REQUEST['request']) {
	case 'findByRadius' : {
	  $schulstandort = new Schulstandort();
	  $schulstandorte = $schulstandort->findByRadius($_REQUEST['lat'], $_REQUEST['lng'], $_REQUEST['radius'], $_REQUEST['schulform'], $_REQUEST['limit']);
	  Schulstandort::output($schulstandorte, $_REQUEST['radius'], $_REQUEST['format']);
	} break;

	default : {
	  echo 'Geben Sie im Parameter request einen der folgenden Werte an:<br>';
	  echo 'findByRadius';
	}
  }
?>