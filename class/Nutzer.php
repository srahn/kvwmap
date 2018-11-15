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

	public static function register($gui, $stelle_id) {
		$gui->debug->show('Nutzer register', Nutzer::$write_debug);
		$user = new Nutzer($gui);
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
			if ($rolle->setRollen($user->get('ID'), array($stelle_id))) {
				if ($rolle->setMenue($user->get('ID'), array($stelle_id))) {
					if ($rolle->setLayer($user->get('ID'), array($stelle_id), 0)) {
						$stelle = new stelle($stelle_id, $gui->database);
						$layers = $stelle->getLayers(NULL);
						if ($rolle->setGroups($user->get('ID'), array($stelle_id), $layers['ID'], 0)) {
							$result['success'] = true;
						}
					}
				}
			}
			if ($result['success'] == 0) {
				$succsess['msg'] = mysql_error($gui->database->dbConn);
			}
		}
		return $result;
	}
}
?>
