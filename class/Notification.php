<?php
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'User2Notification.php');
class Notification extends PgObject {

	static $write_debug = false;
	var $selusers;
	var $selstellen;
	var $validations;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'notifications');
		$this->selusers = array();
		$this->selstellen = array();
		$this->setKeys(
			array(
				"id",
				"notification",
				"veroeffentlichungsdatum",
				"ablaufdatum",
				"stellen_filter",
				"user_filter"
			)
		);
		$this->validations = array(
			array(
				'attribute' => 'notification',
				'condition' => 'not_null',
				'description' => 'Bezeichnung muss angegeben werden.'
			),
			array(
				'attribute' => 'veroeffentlichungsdatum',
				'condition' => 'presence'
			),
			array(
				'attribute' => 'ablaufdatum',
				'condition' => 'not_null',
				'description' => 'Es muss ein Ablaufdatum angegeben werden.'
			),
			array(
				'attribute' => 'veroeffentlichungsdatum',
				'condition' => 'format',
				'description' => 'Datum muss das Format DD.MM.JJJJ haben.',
				'option' => 'DD.MM.YYYY'
			),
			array(
				'attribute' => 'veroeffentlichungsdatum',
				'condition' => 'date',
				'description' => 'Datum ist ungültig!'
			),
			array(
				'attribute' => 'ablaufdatum',
				'condition' => 'format',
				'description' => 'Datum muss das Format DD.MM.JJJJ haben.',
				'option' => 'DD.MM.YYYY'
			),
			array(
				'attribute' => 'ablaufdatum',
				'condition' => 'date',
				'description' => 'Datum ist ungültig!'
			),
			array(
				'attribute' 	=> 'ablaufdatum',
				'condition' 	=> 'greater_or_equal',
				'description' => 'Das Ablaufdatum muss größer oder gleich dem Veröffentlichungsdatum sein.',
				'option' 			=> array('other_key' => 'veroeffentlichungsdatum')
			),
			array(
				'attribute' => 'stellen_filter',
				'condition' => 'comma_list',
				'description' => 'Es können nur mit Komma getrennte Stellen-IDs angegeben werden.'
			),
			array(
				'attribute' => 'user_filter',
				'condition' => 'comma_list',
				'description' => 'Es können nur mit Komma getrennte User-IDs angegeben werden.'
			)
		);
	}

	function create_with_users() {
		$results = $this->create();
		array_merge($results, User2Notification::create_user2notifications($this->gui, $this));
		return $results;
	}

	function update_with_users() {
		$results = $this->update();
		array_merge($results, User2Notification::update_notification($this->gui, $this));
		return $results;
	}

	function create_stellen_filter() {
		$this->set('stellen_filter', $this->get_stellen_filter());
	}

	public static function find_by_id($gui, $id) {
		$notification = new Notification($gui);
		$notification->find_by('id', $id);
		if ($notification->get('user_filter') != '') {
			$notification->selusers = Nutzer::find($gui, 'id IN (' . $notification->get('user_filter') . ')', 'Vorname, Name');
		}
		if ($notification->get('stellen_filter') != '') {
			$notification->selstellen = Stelle::find($gui, 'id IN (' . $notification->get('stellen_filter') . ')', 'Bezeichnung');
		}
		$notification->set('users', User2Notification::find_by_notification_id($gui, $id));
		return $notification;
	}

	public static	function find_for_user($gui) {
		$gui->debug->show('Frage Notifications ab, die dem Nutzer angezeigt werden sollen.', Notification::$write_debug);
		$notification = new Notification($gui);
		$notifications = $notification->find_by_sql(
			array(
				'select' => "m.*",
				'from' => "
					kvwmap.notifications m JOIN
					kvwmap.user2notifications u2m ON m.id = u2m.notification_id
				",
				'where' => "
					u2m.user_id = " . $gui->user->id . " AND
					CURRENT_DATE BETWEEN COALESCE(veroeffentlichungsdatum, CURRENT_DATE) AND COALESCE(ablaufdatum, CURRENT_DATE) AND
					(
						m.stellen_filter IS NULL OR
						m.stellen_filter = '' OR
						concat(', ', m.stellen_filter, ',') LIKE '%, " . $gui->Stelle->id . ",%'
					)
				",
				'order' => "id desc"
			)
		);
		if ($notification->database->errormessage != '') {
			return array(
				'success' => false,
				'err_msg' => $notification->database->errormessage
			);
		}
		return array(
			'success' => true,
			'notifications' => array_map(
				function($notification) {
					return $notification->data;
				},
				$notifications
			)
		);
	}

	public static	function find($gui, $where = '1=1') {
		$notification = new Notification($gui);
		return $notification->find_where($where, 'veroeffentlichungsdatum, id');
	}

	function get_stellen_filter() {
		$stellen_ids = [];
		$stellen_ids = array_filter(explode(',', $this->get('stellen_filter')));
		foreach($stellen_ids as $stelle_id) {
			#$stellen_ids = array_merge($stellen_ids, $this->gui->Stelle->getChildren($stelle_id, '', 'only_ids', true));
		}
		return implode(',', $stellen_ids);
	}

	/*
	$this->message->data = formvars_strip($this->formvars, $this->message->getKeys(), 'keep');
		$this->message->set('veroeffentlichungsdatum', (value_of($this->formvars, 'veroeffentlichungsdatum') == '' ? $currenttime=date('Y-m-d H:i:s',time()););
			$this->cronjob->set('stelle_id', $this->Stelle->id);
			$this->cronjob->set('query', $this->formvars['query']);

		));
	*/
}
?>
