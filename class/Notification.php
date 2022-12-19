<?php
include_once(CLASSPATH . 'User2Notification.php');
class Notification extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'notifications');
		$this->setKeys(
			array(
				"id",
				"notification",
				"veroeffentlichungsdatum",
				"ablaufdatum",
				"stellen_filter"
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
				'description' => 'Es können nur mit Komma getrennte Stellen-IDs oder der Text "nicht in Gaststellen" angegeben werden.'
			)
		);
	}

	function create_with_users() {
		$results = $this->create();
		$sql = "
			INSERT INTO user2notifications (user_id, notification_id)
			SELECT DISTINCT
				user_id,
				" . $this->get($this->identifier) . " AS notification_id
			FROM
				rolle r
			WHERE
				true
		";
		$this->database->execSQL($sql);
		if ($this->database->success) {
			$results[] = array(
				'success' => true,
				'msg' => 'Zuordnungen der Benachichtungen zu den Nutzern erfolgreich angelegt.'
			);
		}
		else {
			$errormessage = $this->database->mysqli->error;
			$results[] = array(
				'success' => false,
				'msg' => $errormessage,
				'err_msg' => $errormessage
			);
		}
		return $results;
	}

	public static function find_by_id($gui, $id) {
		$notification = new Notification($gui);
		$notification->find_by('id', $id);
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
					notifications m JOIN
					user2notifications u2m ON m.id = u2m.notification_id
				",
				'where' => "
					u2m.user_id = " . $gui->user->id . " AND
					CURRENT_DATE BETWEEN COALESCE(veroeffentlichungsdatum, CURRENT_DATE) AND COALESCE(ablaufdatum, CURRENT_DATE) AND
					(
						m.stellen_filter IS NULL OR
						m.stellen_filter = '' OR
						concat(',', m.stellen_filter, ',') LIKE '," . $gui->Stelle->id . ",'
					) AND
					NOT (
						m.stellen_filter = 'nicht in Gaststellen' AND
						" . $gui->Stelle->is_gast_stelle() . "
					)
				",
				'order' => "veroeffentlichungsdatum, id"
			)
		);
		if ($notification->database->errormessage != '') {
			return array(
				'success' => false,
				'err_msg' => $notification->database->mysqli->error
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

	/*
	$this->message->data = formvars_strip($this->formvars, $this->message->getKeys(), 'keep');
		$this->message->set('veroeffentlichungsdatum', (value_of($this->formvars, 'veroeffentlichungsdatum') == '' ? $currenttime=date('Y-m-d H:i:s',time()););
			$this->cronjob->set('stelle_id', $this->Stelle->id);
			$this->cronjob->set('query', $this->formvars['query']);

		));
	*/
}
?>
