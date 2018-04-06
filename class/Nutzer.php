<?php
class Nutzer extends MyObject {

	static $write_debug = false;

	function Nutzer($gui) {
		$this->MyObject($gui, 'user');
		$this->identifier = 'ID';
	}

	public static	function find_by_login_name($gui, $login_name) {
		$user = new Nutzer($gui);
		return $user->find_by('login_name', $login_name);
	}

	public static function increase_num_login_failed($gui, $login_name) {
		$num_login_failed = 0;
		$user = Nutzer::find_by_login_name($gui, $login_name);
		if ($user->has_key('num_login_failed')) {
			$num_login_failed = $user->get('num_login_failed') + 1;
			$user->update(array(
				'ID' => $user->get('ID'),
				'num_login_failed' => $num_login_failed
			));
		}
		return $num_login_failed;
	}

	public static function reset_num_login_failed($gui, $login_name) {
		$user = Nutzer::find_by_login_name($gui, $login_name);
		if ($user->has_key('num_login_failed')) {
			$user->update(array(
				'ID' => $user->get('ID'),
				'num_login_failed' => 0
			));
		}
	}

	public static function register($gui, $email, $stelle_id) {
		$user = new Nutzer($gui);
		#// TODO: erzeuge neuen Nutzers und Ordne der Stelle zu
		$user.create(
			array(
				'login_name' => $gui->formvars['login_name'],
				'Name' => $gui->formvars['Name'],
				'Vorname' => $gui->formvars['Vorname'],
				'Namenszusatz' => $gui->formvars['Namenszusatz'],
				'password' => $gui->formvars['passwort'],
				'phon' => $gui->formvars['phon'],
				'email' => $email,
				'stelle_id' => $stelle_id
			)
		);
		return new user($user.get('login_name'), 0, $gui->database);
	}
}
?>
