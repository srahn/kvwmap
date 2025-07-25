<?php
class Nutzer extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'user');
		$this->identifier = 'ID';
		$this->validations = array(
			array(
				'attribute' => 'ID',
				'condition' => 'unique',
				'description' => 'Die id darf nur ein Mal vorkommen.',
				'option' => 'on Insert'
			),
			array(
				'attribute' => 'login_name',
				'condition' => 'not_null',
				'description' => 'Es muss ein login-Name angegeben werden.',
				'option' => null
			)
		);
	}

	public static	function find($gui, $where, $order = '', $sort_direction = '') {
		$nutzer = new Nutzer($gui);
		return $nutzer->find_where($where, $order, $sort_direction);
	}

	public static	function find_by_login_name($gui, $login_name) {
		$gui->debug->show('Frage Nutzer mit login_name ab.', Nutzer::$write_debug);
		$user = new Nutzer($gui);
		return $user->find_by('login_name', $gui->database->mysqli->real_escape_string($login_name));
	}

	function get_name() {
		return ($this->get('Name') ? $this->get('Name') . ', ' : '') . ($this->get('Vorname') ? $this->get('Vorname') : $this->login_name);
	}

	public static function increase_num_login_failed($gui, $login_name) {
		$num_login_failed = 0;
		$nutzer = Nutzer::find_by_login_name($gui, $login_name);
		if ($nutzer->has_key('num_login_failed')) {
			$num_login_failed = $nutzer->get('num_login_failed') + 1;
			$gui->debug->show('Setze num_login_failed auf ' . $num_login_failed, Nutzer::$write_debug);

			$nutzer->update(array(
				'num_login_failed' => $num_login_failed,
				'login_locked_until' => date('Y-m-d H:i:s', time() + ($num_login_failed <= 5 ? 0 : $num_login_failed * 5))
			));
		}
		return $nutzer;
	}

	public static function reset_num_login_failed($gui, $login_name) {
		$nutzer = Nutzer::find_by_login_name($gui, $login_name);
		if ($nutzer->has_key('num_login_failed')) {
			$nutzer->update(array(
				'ID' => $nutzer->get('ID'),
				'num_login_failed' => 0,
				'login_locked_until' => ''
			));
		}
		return $nutzer;
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
				'password' => SHA1($gui->formvars['new_password']),
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

	function get_rolle() {
		$this->rolle = null;
		if ($this->get('stelle_id') != '') {
			$db_object = new MyObject(
				$this->gui,
				'rolle',
				array(
					array(
						'key' =>'user_id',
						'type' => 'integer'
					),
					array(
						'key' => 'stelle_id',
						'type' => 'integer'
					)
				),
				'array'
			);
			$db_object->data = array(
				'user_id' 	=> $this->get('ID'),
				'stelle_id' => $this->get('stelle_id')
			);
			$this->rolle = $db_object->find_by_ids(array(
				'user_id' => $db_object->get('user_id'),
				'stelle_id' => $db_object->get('stelle_id')
			));
		}
		return $this->rolle;
	}
}
?>
