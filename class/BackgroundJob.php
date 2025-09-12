<?
include_once(CLASSPATH . 'PgObject.php');
class BackgroundJob extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'background_jobs');
	}

	public static	function find($gui, $where, $order = '') {
		$background_job = new BackgroundJob($gui);
		return $background_job->find_where($where, $order);
	}
}