<?php
class User2Notification extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct(
			$gui,
			'user2notifications',
			array(
				'user_id',
				'notification_id'
			)
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
}
?>
