<?
	# Überprüfung der Nutzerdaten

	if (date('Y-m-d h:m:s', strtotime('- 1 year')) > date($GUI->user->userdata_checking_time)) {

		$GUI->add_message('info', '
			Guten Tag,
			
			zur Zeit sind folgende Nutzerdaten zu ihrem Account hinterlegt:
			
			' . $GUI->user->Vorname . ' ' . $GUI->user->Name . '
			' . $GUI->user->organisation . '
			' . $GUI->user->email . '
			Tel.: ' . $GUI->user->phon .'
			
			Sind diese Daten noch aktuell? Falls nicht, können Sie uns eine <a href="mailto:test@test.de?subject=Aktualisierung Nutzerdaten ID ' . $GUI->user->id . '&body=Folgende Daten haben sich geändert:">Email</a> mit Ihren aktuellen Daten schicken.
		');

		$GUI->user->set_userdata_checking_time();
		
	}
?>


<?
	# Anzeige von Nutzerhinweisen

	include_once(CLASSPATH . 'PgObject.php');
	$pgObject = new PgObject($GUI, 'public', 'hinweise');
	$hinweise = $pgObject->find_where('current_timestamp BETWEEN startdatum AND startdatum + (dauer::text||\' day\')::INTERVAL');
	
	if ($hinweise != '') {
		foreach($hinweise as $hinweis){
			$GUI->add_message('warning', $hinweis->get('hinweis'));
		}
	}
?>