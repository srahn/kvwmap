<?php
class Nutzer extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'user');
		$this->identifier = 'id';
		$this->validations = array(
			array(
				'attribute' => 'id',
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
		return $user->find_by('login_name', pg_escape_string($login_name));
	}

	public static	function find_by_id($gui, $id) {
		$gui->debug->show('Frage Nutzer mit id ab.', Nutzer::$write_debug);
		$user = new Nutzer($gui);
		return $user->find_by('id', pg_escape_string($id));
	}

	function get_name() {
		return ($this->get('name') ? $this->get('name') . ', ' : '') . ($this->get('vorname') ? $this->get('vorname') : $this->login_name);
	}

	public static function increase_num_login_failed($gui, $login_name) {
		$num_login_failed = 0;
		$nutzer = Nutzer::find_by_login_name($gui, $login_name);
		if ($nutzer->has_key('num_login_failed')) {
			$num_login_failed = $nutzer->get('num_login_failed') + 1;
			$gui->debug->show('Setze num_login_failed auf ' . $num_login_failed, Nutzer::$write_debug);

			$nutzer->update(
				array(
					'num_login_failed' => $num_login_failed,
					'login_locked_until' => date('Y-m-d H:i:s', time() + ($num_login_failed <= 5 ? 0 : $num_login_failed * 5))
				),
				false
			);
		}
		return $nutzer;
	}

	public static function reset_num_login_failed($gui, $login_name) {
		$nutzer = Nutzer::find_by_login_name($gui, $login_name);
		if ($nutzer->has_key('num_login_failed')) {
			$nutzer->update(
				array(
					'id' => $nutzer->get('id'),
					'num_login_failed' => 0,
					'login_locked_until' => ''
				),
				false
			);
		}
		return $nutzer;
	}

	public static function register($gui, $stellen_ids) {
		$gui->debug->show('Nutzer register', Nutzer::$write_debug);
		$user = new Nutzer($gui);
		$results = $user->create(
			array(
				'login_name' => $gui->formvars['login_name'],
				'name' => $gui->formvars['name'],
				'vorname' => $gui->formvars['vorname'],
				'namenszusatz' => $gui->formvars['namenszusatz'],
				'password' => sha1($gui->formvars['new_password']),
				'phon' => $gui->formvars['phon'],
				'email' => $gui->formvars['email'],
				'stelle_id' => $stellen_ids[0]
			)
		);
		if (!$results['success']) {
			return $results;
		}

		foreach($stellen_ids as $stelle_id) {
			$stelle = new stelle($stelle_id, $gui->pgdatabase);

			$create_rolle_result = rolle::create($gui->pgdatabase, $stelle_id, $user->get('id'), $stelle->default_user_id, $stelle->getLayers(NULL)['ID']);
			if (!$create_rolle_result['success']) {
				return $create_rolle_result;
			}
		}

		return array(
			'success' => true,
			'msg' => $result['msg'] . '<br>' . $create_rolle_result['msg']
		);
	}

	function get_rolle() {
		$this->rolle = null;
		if ($this->get('stelle_id') != '') {
			$db_object = new PgObject(
				$this->gui,
				'kvwmap',
				'rolle',
				array(
					array(
						'column' =>'user_id',
						'type' => 'integer'
					),
					array(
						'column' => 'stelle_id',
						'type' => 'integer'
					)
				),
				'array'
			);
			$db_object->data = array(
				'user_id' 	=> $this->get('id'),
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
