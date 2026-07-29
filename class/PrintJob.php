<?php
#############################
# Klasse PrintJob #
#############################

class PrintJob extends PgObject {

	public $write_debug = false;
	static $schema = 'public';
	static $tableName = 'print_jobs';

	function __construct($gui) {
		parent::__construct($gui, PrintJob::$schema, PrintJob::$tableName);
		$this->select = "*";
		$this->from = PrintJob::$schema . '.' . PrintJob::$tableName;
		$this->where = "";
	}

  public static function find_by_id($gui, $id) {
		$obj = new PrintJob($gui);
		$print_job = $obj->find_by('id', $id);
		return $print_job;
	}

	/**
	 * Function find $num_jobs with status beauftragt and block it
	 * with status running for next calls.
	 * @return array(PrintJob) or empty Array if no found.
	 */
	public static function find_next($gui, $num_jobs = 1) {
		$print_job = new PrintJob($gui);
		$running_print_jobs = $print_job->find_where("status = 'running'");
		if (count($running_print_jobs) > 150) {
			return null;
		}
		$sql = "
			WITH next AS (
				SELECT
					*
				FROM
					public.print_jobs
				WHERE
					status = 'beauftragt'
				ORDER BY
					created_at
				LIMIT " . $num_jobs . "
			)
			UPDATE
				public.print_jobs p
			SET
				status = 'running',
				started_at = now()
			FROM
				next
			WHERE
			  p.id = next.id
			RETURNING p.*
		";
		#echo "\nSQL zur Abfrage des nächsten print_jobs: " . $sql;
		$query = $print_job->execSQL($sql);
		$results = array();
		while ($rs = pg_fetch_assoc($query)) {
			$results[] = PrintJob::find_by_id($gui, $rs['id']);
		}
		return $results;
	}

	function print() {
		// get layer for bogen
		$this->gui->formvars['aktivesLayout'] = $this->get('ddl_id');
		$this->gui->formvars['chosen_layer_id'] = $this->get('layer_id');
		$this->gui->formvars['checkbox_names_' . $this->get('layer_id')] = 'check;' . $this->get('table_alias') . ';' . $this->get('table_name') . ';' . $this->get('feature_id') . ';' . $this->get('layer_id');
		$this->gui->formvars['check;' . $this->get('table_alias') . ';' . $this->get('table_name') . ';' . $this->get('feature_id') . ';' . $this->get('layer_id')] = 'on';
		$this->gui->qlayerset[0]['shape'] = null;
		$result = $this->gui->generischer_sachdaten_druck_createPDF();
		$dest_path = pathinfo($this->get('pdf_path'),  PATHINFO_DIRNAME);
		if (!is_dir($dest_path)) {
			mkdir($dest_path, 0777, true);
		}
		// copy pdf to Bogen Ordner
		if (!rename($result['pdf_file'], $this->get('pdf_path'))) {
			return array(
				'success' => false,
				'msg' => 'Fehler: Datei: ' . $this->get('pdf_path') . ' konnte nicht erstellt werden.'
			);
		}
		return array(
			'success' => true,
			'msg' => 'Datei ' . $this->get('pdf_path') . ' mit Druckjob id: ' . $this->get_id() . ' gedruckt.'
		);
	}
}