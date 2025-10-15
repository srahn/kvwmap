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

  public static function find_next($gui) {
    $pj = new PrintJob($gui);
	  $print_jobs = $pj->find_where("status = 'beauftragt'", 'created_at', '*', 1);
    if (count($print_jobs) > 0) {
      return $print_jobs[0];
    }
    return null;
  }

  function print() {
    // get layer for bogen
    $this->gui->formvars['aktivesLayout'] = $this->get('ddl_id');
    $this->gui->formvars['chosen_layer_id'] = $this->get('layer_id');
    $this->gui->formvars['checkbox_names_' . $this->get('layer_id')] = 'check;' . $this->get('table_alias') . ';' . $this->get('table_name') . ';' . $this->get('feature_id') . ';' . $this->get('layer_id');
    $this->gui->formvars['check;' . $this->get('table_alias') . ';' . $this->get('table_name') . ';' . $this->get('feature_id') . ';' . $this->get('layer_id')] = 'on';
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