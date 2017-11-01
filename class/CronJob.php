<?php
include_once(CLASSPATH . 'MyObject.php');
class CronJob extends MyObject {

	static $write_debug = false;

	function CronJob($gui) {
		$this->MyObject($gui, 'cron_jobs');
		$this->identifier = 'id';
		$this->setKeys(
			array(
				"id",
				"bezeichnung",
				"beschreibung",
				"time",
				"query",
				"dbname",
				"function",
				"user_id",
				"stelle_id"
			)
		);
		$this->log_file_name = '/var/www/logs/cron/cron.log';
	}

	public function validate() {
		$results = array();
		$results[] = $this->validates('bezeichnung', 'presence');
		$results[] = $this->validates('bezeichnung', 'not_null', 'Bezeichnung muss angegeben werden.');
		$results[] = $this->validates('time', 'presence');
		$results[] = $this->validates('time', 'not_null', 'Zeit muss angegeben werden.');
		$results[] = $this->validates('time', 'format', 'Zeit muss im Format Minute (*|0-59), Stunde(*|0-23), Tag(*|1-31), Monat(*|1-12), Tag der Woche (*|0-6, 0 = Sonntag) mit Leerzeichen getrennt angegeben werden, z.B. "0 10 1 * *" für jeden 1. des Monats um 10:00 Uhr morgens.', '* * * * *');
		$results[] = $this->validates(array('query', 'function'), 'presence_one_of', 'Es muss entweder <i>SQL-Anfrage</i> oder <i>Shell Komando</i> angegeben werden.');

		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public static function find_by_id($gui, $id) {
		$cronjob = new CronJob($gui);
		return $cronjob->find_by('id', $id);
	}

	public static	function find($gui, $where = '1=1') {
		$cronjob = new CronJob($gui);
		return $cronjob->find_where($where, 'bezeichnung');
	}

	/*
	* writes function as command in crontab if sql is not defined
	*
	*/
	public function get_crontab_line() {
		$line = '';
		$dbname_from_job = $this->get('dbname');
		$dbname_from_stelle = $this->gui->Stelle->pgdbname;
		$dbname_from_gui = $this->gui->pgdatabase->dbName;
		$dbname = (!empty($dbname_from_job) ? $dbname_from_job : (!empty($dbname_from_stelle) ? $dbname_from_stelle :  $dbname_from_gui));
		#echo "<br>dbname from job: {$dbname_from_job}, stelle: {$dbname_from_stelle}, gui: {$dbname_from_gui}, chosen: {$dbname}";

		if (!empty($this->get('time'))) {
			if (!empty($this->get('query'))) {
				$line = $this->get('time') . ' PGPASSWORD=' . $this->gui->pgdatabase->passwd . ' psql -h pgsql -U ' . $this->gui->pgdatabase->user . ' -c "' . preg_replace('/\s+/', ' ', $this->get('query')) . '" ' . $dbname . ' >> ' . $this->log_file_name;
			}
			else {
				if (!empty($this->get('function'))) {
					$line = $this->get('time') . ' ' . $this->get('function');
				}
			}
		}
		return $line;
	}

}
?>
