<?php
class Nutzer extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'user');
		$this->identifier = 'ID';
	}

	public static	function find_by_login_name($gui, $login_name) {
		$user = new Nutzer($gui);
		return $user->find_by('login_name', $gui->database->mysqli->real_escape_string($login_name));
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

	public static function register($gui, $stelle_id) {
		$gui->debug->show('Nutzer register', Nutzer::$write_debug);
		$user = new Nutzer($gui);
		$stelle = new stelle($stelle_id, $gui->database);
		$results = $user->create(
			array(
				'login_name' => $gui->formvars['login_name'],
				'Name' => $gui->formvars['Name'],
				'Vorname' => $gui->formvars['Vorname'],
				'Namenszusatz' => $gui->formvars['Namenszusatz'],
				'passwort' => md5($gui->formvars['new_password']),
				'phon' => $gui->formvars['phon'],
				'email' => $gui->formvars['email'],
				'stelle_id' => $stelle_id
			)
		);

		if ($results[0]['success']) {
			$result['success'] = false;
			$rolle = new rolle($user->get('ID'), $stelle_id, $gui->database);
			if ($rolle->setRolle($user->get('ID'), $stelle_id, $stelle->default_user_id)) {
				if ($rolle->setMenue($user->get('ID'), $stelle_id, $stelle->default_user_id)) {
					if ($rolle->setLayer($user->get('ID'), $stelle_id, $stelle->default_user_id)) {
						$layers = $stelle->getLayers(NULL);
						if ($rolle->setGroups($user->get('ID'), $stelle_id, $stelle->default_user_id, $layers['ID'])) {
							$rolle->setSavedLayersFromDefaultUser($user->get('ID'), $stelle_id, $stelle->default_user_id);
							$result['success'] = true;
						}
					}
				}
			}
			if ($result['success'] == 0) {
				$succsess['msg'] = $gui->database->error;
			}
		}
		return $result;
	}
}
?>
