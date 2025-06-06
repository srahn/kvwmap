<?php
include_once(CLASSPATH . 'PgObject.php');
class Role extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct(
			$gui,
			'kvwmap',
			'rolle',
			array(
				array(
					'key' => 'user_id',
					'type' => 'integer'
				),
				array(
					'key' => 'stelle_id',
					'type' => 'integer'
				)
			),
			'array'
		);
	}

	public static	function find($gui, $where, $order = '', $find_direction = '') {
		$role = new Role($gui);
		$roles = $role->find_where($where, $order, $find_direction);
		foreach ($roles AS $role) {
			$role->user = Nutzer::find_by_id($gui, $role->get('user_id'));
			$stelle = new PgObject($gui, 'kvwmap', 'stelle', 'id');
			$role->stelle = $stelle->find_by('id', $role->get('stelle_id'));
		}
		return $roles;
	}

	public static	function find_by_id($gui, $user_id, $stelle_id) {
		$role_obj = new Role($gui);
		$role = $role_obj->find_by_ids(array(
			'user_id' => $user_id,
			'stelle_id' => $stelle_id
		));
		$role->user = Nutzer::find_by_id($gui, $user_id);
		$stelle = new PgObject($gui, 'kvwmap', 'stelle', 'id');
		$role->stelle = $stelle->find_by('id', $stelle_id);
		return $role;
	}

	function get_rolle_id() {
		return $this->get('user_id') . '_' . $this->get('stelle_id');
	}
}
?>
