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

	public static function find_beauftragte($gui, $where = "status = 'beauftragt'") {
		$convert_job = new ConvertJob($gui);
		$convert_jobs = $convert_job->find_where($where);
		return $convert_jobs;
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

	function get_convert_cmd($dst_file = '') {
		if ($dst_file == '') {
			$dst_file = $this->get('dst_file');
		}
		$cmd = "convert -limit memory 512MiB -limit map 1GiB "
					. escapeshellarg($this->get('src_file')) . " -sampling-factor 4:2:0 -strip "
					. "-quality 85 -define " . $this->get('define_options') . " "
					. escapeshellarg($dst_file) . " 2>&1";
		return $cmd;
	}

	function get_exiftool_cmd() {
		$data = json_decode($this->get('exif_data'), true);
		$cmd = 'exiftool -overwrite_original -IPTC:2#005="' . $data['2#005'] . '" -IPTC:2#080="' . $data['2#080'] . '" -IPTC:2#116="' . $data['2#116'] . '" -IPTC:2#120="' . $data['2#120'] . '" ' . $this->get('dst_file');

		// Alternativ geht auch direkt mit Tag-Namen
		// exiftool -overwrite_original \
		//   -ObjectName="title" \
		//   -Byline="Organisation" \
		//   -Source="organisation-title" \
		//   -Caption-Abstract="image-title" \
		//   image.jpg
		return $cmd;
	}

	function convert() {
		if (!is_file($this->get('src_file'))) {
			return array(
				'success' => false,
				'msg' => 'Fehler: Quelldatei: ' . $this->get('src_file') . ' nicht gefunden.'
			);
		}
		if (!is_dir(dirname($this->get('dst_file')))) {
			mkdir(dirname($this->get('dst_file')), 0777, true);
		}

		// Variante mit Begrenzung von Speicher
		// convert -limit memory 512MiB -limit map 1GiB src.jpg -sampling-factor 4:2:0 -strip -quality 85 -define jpeg:extent=2000kb dst.jpg
		// Zur Problembehebung mit den < 20 KB Dateien ohne jpeg:extent testen.
		$tmp_file = $this->get('dst_file') . '.tmp_' . uniqid();
		$cmd = $this->get_convert_cmd($tmp_file);
		exec($cmd, $output, $return_var);
		if ($return_var !== 0) {
			return array(
				'success' => false,
				'msg' => "\n" . implode("\n", $output)
			);
		}
		$msg = "Befehl ohne Abbruch ausgeführt.";

		if (!is_file($tmp_file)) {
			return array(
				'success' => false,
				'msg' => 'Fehler: Tempdatei: ' . $tmp_file . ' konnte nicht erstellt werden.'
			);
		}

		if (!rename($tmp_file, $this->get('dst_file'))) {
			unlink($tmp_file);
			return array(
				'success' => false,
				'msg' => 'Temporäre Datei: ' . $tmp_file . ' konnte nicht nach: ' . $this->get('dst_file') . ' verschoben werden.'
			);
		}
		$msg .= " Zieldatei angelegt: " . $this->get('dst_file');

		if ($this->get('exif_data') != '') {
			set_exif_data(
				$this->get('dst_file'),
				json_decode($this->get('exif_data'), true)
			);
			$msg .= " Exif data gesetzt.";
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