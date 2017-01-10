<?php
include_once(CLASSPATH . 'MyObject.php');
class CronJob extends MyObject {

	static $write_debug = false;

	function CronJob($gui) {
		$this->MyObject($gui, 'cron_jobs');
		$this->identifier = 'id';
	}

	public static function find_by_id($gui, $id) {
		$cronjob = new CronJob($gui);
		return $cronjob->find_by('id', $id);
	}

	public static	function find($gui, $where = '1=1') {
		$cronjob = new CronJob($gui);
		return $cronjob->find_where($where);
	}

}
?>
