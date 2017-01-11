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

	public function get_crontab_line() {
		$line = '';
		if (!empty($this->get('time'))) {
			if (!empty($this->get('query'))) {
				$line = $this->get('time') . ' PGPASSWORD=' . $this->gui->pgdatabase->passwd . ' psql -h pgsql -U ' . $this->gui->pgdatabase->user . ' -c "' . preg_replace('/\s+/', ' ', $this->get('query')) . '" ' . $this->gui->pgdatabase->dbName;
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
