<?php
class User2Notification extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct(
			$gui,
			'user2notifications',
			array(
				array('key' => 'user_id', 'type' => 'int'),
				array('key' => 'notification_id', 'type' => 'int')
			),
			'array'
		);
		$this->setKeys(
			array(
				"user_id",
				"notification_id",
			)
		);
	}

	public static function find_by_notification_id($gui, $notification_id) {
		$user2notification = new User2Notification($gui);
		$user2notifications = $user2notification->find_where('notification_id = ' . $notification_id);
		return array_map(
			function($user2notification) {
				return $user2notification->get('user_id');
			},
			$user2notifications
		);
	}

	public static function delete_user2notifications($gui, $notification_id) {
		$user2notification = new User2Notification($gui);
		$sql = "
			DELETE FROM
				user2notifications
			WHERE
				notification_id = " . $notification_id . "
		";
		$user2notification->gui->database->execSQL($sql);
		if ($user2notification->database->success) {
			$result = array(
				'success' => true,
				'msg' => 'Zuordnungen der Benachichtungen zu den Nutzern erfolgreich gelöscht.'
			);
		}
		else {
			$result = array(
				'success' => false,
				'msg' => 'Fehler bei der Zuordnungen der Benachichtungen zu den Nutzern mit SQL: ' . $sql
			);
		}
		return $result;
	}

	public static function create_user2notifications($gui, $notification_id) {
		$user2notification = new User2Notification($gui);
		$sql = "
			INSERT INTO user2notifications (user_id, notification_id)
			SELECT DISTINCT
				user_id,
				" . $notification_id . " AS notification_id
			FROM
				rolle r
			WHERE
				true
		";
		$user2notification->gui->database->execSQL($sql);
		if ($user2notification->gui->database->success) {
			$result = array(
				'success' => true,
				'msg' => 'Zuordnungen der Benachichtungen zu den Nutzern erfolgreich angelegt.'
			);
		}
		else {
			$errormessage = $user2notification->database->mysqli->error;
			$result = array(
				'success' => false,
				'msg' => $errormessage,
				'err_msg' => $errormessage
			);
		}
		return $result;
	}

	public static function update_notification($gui, $notification_id) {
		$results = array();
		$results[] = User2Notification::delete_user2notifications($gui, $notification_id);
		$results[] = User2Notification::create_user2notifications($gui, $notification_id);
		return $results;
	}

	public static function delete_for_user($gui, $notification_id, $user_id) {
		$user2notification = new User2Notification($gui);
		$user2notification->set('notification_id', $notification_id);
		$user2notification->set('user_id', $user_id);
		$results = $user2notification->delete();
		return $results;
	}
}
?>
