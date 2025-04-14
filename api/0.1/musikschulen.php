<?php
  include('models/musikschule.php');

  switch($_REQUEST['request']) {
	case 'findByRadius' : {
	  $musikschule = new Musikschule();
	  $musikschulen = $musikschule->findByRadius($_REQUEST['lat'], $_REQUEST['lng'], $_REQUEST['radius'], $_REQUEST['limit']);
	  Musikschule::output($musikschulen, $_REQUEST['radius'], $_REQUEST['format']);
	} break;

	default : {
	  echo 'Geben Sie im Parameter request einen der folgenden Werte an:<br>';
	  echo 'findByRadius';
	}
  }
?>