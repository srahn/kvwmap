<?php
#############################
# Klasse ConvertJob #
#############################

class ConvertJob extends PgObject {

	public $write_debug = false;
	static $schema = 'public';
	static $tableName = 'convert_jobs';

	function __construct($gui) {
		parent::__construct($gui, ConvertJob::$schema, ConvertJob::$tableName);
		$this->select = "*";
		$this->from = ConvertJob::$schema . '.' . ConvertJob::$tableName;
		$this->where = "";
	}

	/**
	 * Fragt den nächsten convert_job ab der auf Status beauftragt steht.
	 */
	public static function find_next($gui) {
		$convert_job = new ConvertJob($gui);
		$running_convert_jobs = $convert_job->find_where("status = 'running'");
		if (count($running_convert_jobs) > 10) {
			return null;
		}
		$sql = "
			WITH next AS (
				SELECT
					*
				FROM
					public.convert_jobs
				WHERE
					status = 'beauftragt'
				ORDER BY
					created_at
				LIMIT 1
			)
			UPDATE
				public.convert_jobs p
			SET
				status = 'running',
				started_at = now()
			FROM
				next
			WHERE
				p.id = next.id
			RETURNING p.*
		";
		// echo "\nSQL zur Abfrage des nächsten convert_jobs: " . $sql;
		$query = $convert_job->execSQL($sql);
		$convert_job->data = pg_fetch_assoc($query);

		if ($convert_job->data !== false) {
			return $convert_job;
		}
		return null;
	}

	function convert() {
		echo "\nConvertjob mit ID " . $this->get_id() . " wird ausgeführt.";
		if (!is_file($this->get('src_file'))) {
			return array(
				'success' => false,
				'msg' => 'Fehler: Quelldatei: ' . $this->get('src_file') . ' nicht gefunden.'
			);
		}
		if (!is_dir(dirname($this->get('dst_file')))) {
			mkdir(dirname($this->get('dst_file')), 0777, true);
		}

		$cmd = "convert " . $this->get('src_file') . " -define " . $this->get('define_options') . " " . $this->get('dst_file');
		exec($cmd, $output, $return_var);

		if (!is_file($this->get('dst_file'))) {
			return array(
				'success' => false,
				'msg' => 'Fehler: Zieldatei: ' . $this->get('dst_file') . ' konnte nicht erstellt werden. Befehl war: ' . $cmd
			);
		}
		else {
			$msg = $this->get('src_file') . ' converted to ' . $this->get('dst_file');
		}

		if ($this->get('exif_data') != '') {
			set_exif_data(
				$this->get('dst_file'),
				json_decode($this->get('exif_data'), true)
			);
			$msg .= ' Exif data gesetzt.';
		}
		return array(
			'success' => true,
			'msg' => $msg
		);
	}

	function update_dst_file() {
		$obj = new PgObject($this->gui, $this->get('dst_schema'), $this->get('dst_table'));
		$foto = $obj->find_by('dst_file', $this->get('src_file'));
		$result = $foto->update_attr(
			array(
				$this->get('dst_column') . " = '" . $this->get('dst_file') . "'"
			)
		);
		return $result;
	}

}