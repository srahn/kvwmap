<?php
#############################
# Klasse Ressource #
#############################

class Ressource extends PgObject {
	
	static $schema = 'metadata';
	static $tableName = 'ressources';
	static $write_debug = false;
  public $has_subressources = false;
  public $has_ressource_ranges = false;

  public $sub_ressources = array();

	function __construct($gui) {
		$gui->debug->show('Create new Object ressource', Ressource::$write_debug);
		parent::__construct($gui, Ressource::$schema, Ressource::$tableName);
		include_(CLASSPATH . 'data_import_export.php');
    $this->gui->data_import_export = new data_import_export('gid');
    // $this->typen = array(
		// 	'Punkte',
		// 	'Linien',
		// 	'Flächen'
		// );
	}

	public static	function find_by_id($gui, $by, $id) {
		$ressource = new Ressource($gui);
		$ressource->find_by($by, $id);
    $ressource->get_subressources();
		return $ressource;
	}

  function get_subressources() {
    $subresource = new Subressource($this->gui);
    $subressources = $subresource->find_by_ressource_id($this->get_id());
    $this->has_subressources = count($subressources) > 0;
    $this->subressources = $subressources;
    return $subressources;
  }

  function destroy() {
		#echo "\ndestroy Dataset: " . $this->get($this->identifier);
		$this->debug->show('destroy dataset ' . $this->get('datenquelle'), Dataset::$write_debug);
		$this->delete();
	}

  /**
   * Function find first outdated ressource and run the update process
   * if less than 10 processes running already
   * A ressource is outdated if
   * status is not set or 0 and
   * auto_update is set to true and (
   *   last_update is not defined or (
   *     last_update is defined and
   *     update_internval is defined and
   *     last_update + update_interval is in the past
   *   )
   * )
   */
  public static function update_outdated($gui, $ressource_id = null) {
    $gui->debug->show('<br>Starte Funktion update_outdated' . ($ressource_id != null ? ' mit Ressource id: ' . $ressource_id : ''), true);
    $ressource = new Ressource($gui);
    if ($ressource_id != null) {
      $ressources = $ressource->find_where('id = ' . $ressource_id);
    }
    else {
      $results = $ressource->getSQLResults("
        SELECT count(id) AS num_running FROM metadata.ressources WHERE status_id > 0;
      ");
      if ($results[0]['num_running'] < 10) {
        $ressources = $ressource->find_where(
          "
            (status_id IS NULL OR status_id = 0) AND
            auto_update AND
            (
              last_update IS NULL OR
              (
                last_update IS NOT NULL AND
                update_interval IS NOT NULL AND
                last_update + update_interval < now()
              )
            )
          ",
          "last_update",
          "*",
          1
        );
      }
    }
    $gui->debug->show('Anzahl gefundener Ressourcen: ' . count($ressources), true);
    if (count($ressources) > 0) {
      $ressource = $ressources[0];
      $result = $ressource->run_update();
      $ressource->log($result, true);
    }
    else {
      return array(
        'success' => true,
        'msg' => 'Es sind zur Zeit keine Ressourcen zu aktualisieren.'
      );
    }
  }

  function log($result, $show = false) {
    UpdateLog::write($this->gui, $this, $result, $show);
  }

  function run_update() {
    $this->debug->show('Run Update für Ressource id: ' . $this->get_id(), true);
    $this->update_status(1, $msg);
    $result = $this->download();
    if (!$result['success']) { return $result; }

    $result = $this->unpack();
    if (!$result['success']) { return $result; }

    $result = $this->import();
    if (!$result['success']) { return $result; }

    $result = $this->transform();
    if (!$result['success']) { return $result; }

    $this->update_status(0);
    return array(
      'success' => true,
      'msg' => $msg . '<br>Ressource erfolgreich aktualisiert.'
    );
  }

  function update_status($status_id, $msg = '') {
    if ($msg != '') {
      echo '<br>Update Status auf ' . $status_id . '<br>Msg: ' . $msg;
    }
    $this->update_attr(array('status_id = ' . (string)$status_id));
  }

  ####################
  # Download methods #
  ####################
  function download() {
    $this->debug->show('Starte Funktion download', true);
    if ($this->get('download_method') != '') {
      $method_name = 'download_' . $this->get('download_method');
      if (!method_exists($this, $method_name)) {
        return array(
          'success' => false,
          'msg' => 'Die Funktion ' . $method_name . ' zum Download der Ressource existiert nicht.'
        );
      }

      $this->update_status(2);
      $result = $this->${method_name}();
      if (!$result['success']) { return $result; }

      $this->update_status(3);
      return $result;
    }
    return array(
      'success' => true,
      'msg' => 'Keine Downloadmethode angegeben.'
    );
  }
  /**
   * Download dataset or its subsets to download_path
   */
  function download_urls() {
    $this->debug->show('Starte Funktion download_urls');
    $download_urls = array();
    try {
      if ($this->get('download_url') != '') {
        $download_urls = array($this->get('download_url'));
      }
      else {
        if ($this->has_subressources) {
          $download_urls = array_merge(
            $download_urls,
            array_merge(
              ...array_map(
                function($subresource) {
                  $urls = $subresource->get_download_urls();
                  return $urls;
                },
                $this->subressources
              )
            )
          );
        }
      }
      $this->debug->show('Download from URLs:<br>' . implode('<br>', $download_urls), true);
      if ($this->get('download_path') == '') {
        return array(
          'success' => false,
          'msg' => 'Es ist kein relatives Download-Verzeichnis angegeben.'
        );
      }
      $download_path = SHAPEPATH . 'datentool/' . $this->get('download_path');
      if (strpos($download_path, '/var/www/data/') !== 0) {
        return array(
          'success' => false,
          'msg' => 'Das Download-Verzeichnis ' . $download_path . ' fängt nicht mit /var/www/data/ an.'
        );
      }
      if (!file_exists($download_path)) {
        $this->debug->show('Lege Verzeichnis ' . $download_path . ' an, weil es noch nicht existiert!', true);
        mkdir($download_path, 0777, true);
      }
      foreach ($download_urls AS $download_url) {
        $this->debug->show('Download ' . basename($download_url) . ' from url: ' . $download_url . ' to ' . $download_path, true);
        copy($download_url, $download_path . basename($download_url));
      }
    }
    catch (Exception $e) {
      return array(
        'success' => false,
        'msg' => 'Fehler beim Download der Daten: ', $e->getMessage()
      );
    }

    return array(
      'success' => true,
      'msg' => 'Download von URLs erfolgreich beendet.'
    );
  }

  /**
   * Download from WMS
   */
  function download_wms() {
    // Die Download Methode läd die Daten nicht beim Aktualisierungsprozess runter sondern nur bei der Visualisierung
    // Im Datenpaket werden Ressourcen mit dieser Downloadmethode als Remote WMS beschrieben und mit Metadatend versehen.
    // ToDo: Hier könnte aber ggf. die Verfügbarkeit des Dienstes geprüft werden und im Fehler fall in dem Protokoll des Updates mit ausgegeben werden.
    // letzer_update wäre dann das Datum wann das letzte mal geprüft wurde ob der Lienst funktioniert.
    return array(
      'success' => true,
      'msg' => 'Download von WMS erfolgreich beendet.'
    );
  }

  /**
   * Download from WFS
   */
  function download_wfs() {
    // ToDo: implement on demand
    return array(
      'success' => true,
      'msg' => 'Download von WFS erfolgreich beendet.'
    );
  }

  ##################
  # Unpack methods #
  ##################
  function unpack() {
    $this->debug->show('Starte Funktion unpack', true);
    if ($this->get('unpack_method') != '') {
      $method_name = 'unpack_' . $this->get('unpack_method');
      if (!method_exists($this, $method_name)) {
        return array(
          'success' => false,
          'msg' => 'Die Funktion ' . $method_name . ' zum Auspacken der Ressource existiert nicht.'
        );
      }
      $this->update_status(4);
      $result = $this->${method_name}();
      if (!$result['success']) { return $result; }
      $this->update_status(5);
      return $result;
    }
    return array(
      'success' => true,
      'msg' => 'Keine Auspackmethode angegeben.'
    );
  }
  /**
   * Function unzip specific or all files of a directory to a destination directory,
   * log it in a logfile,
   * and remove the zip-files afterward
   */
  function unpack_unzip() {
    $this->debug->show('Starte Funktion unpack_unzip', true);
    if ($this->get('dest_path') == '') {
      return array(
        'success' => false,
        'msg' => 'Es ist kein relatives Auspackverzeichnis angegeben.'
      );
    }
    $dest_path = SHAPEPATH . 'datentool/' . $this->get('dest_path');
    if (strpos($dest_path, '/var/www/data/') !== 0) {
      return array(
        'success' => false,
        'msg' => 'Das Auspackverzeichnis ' . $dest_path . ' fängt nicht mit /var/www/data/ an.'
      );
    }
    if (!file_exists($dest_path)) {
      $this->debug->show('Lege Verzeichnis ' . $dest_path . ' an, weil es noch nicht existiert!', true);
      mkdir($dest_path, 0777, true);
    }
    $download_path = SHAPEPATH . 'datentool/' . $this->get('download_path');
    $cmd = 'unzip -j -o ' . $download_path . '*.zip -d ' . $dest_path;
    $this->debug->show('Packe Datei aus mit Befehl: ' . $cmd, true);
    $descriptorspec = [
      0 => ["pipe", "r"],  // stdin
      1 => ["pipe", "w"],  // stdout
      2 => ["pipe", "w"],  // stderr
    ];
    $process = proc_open($cmd, $descriptorspec, $pipes, dirname(__FILE__), null);
    $line = __LINE__;
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    #    exec($cmd, $output, $return_var);
    if ($stderr != '') {
      return array(
        'success' => false,
        'msg' => 'Fehler bei unzip der Ressource ' . $this->get_id() . ' in Datei: ' . basename(__FILE__) . ' Zeile: ' . $line . ' Rückgabewert: ' . $stderr
      );
    }
    return array(
      'success' => true,
      'msg' => 'Ressource erfolgreich ausgepackt.'
    );
  }

  ##################
  # Import methods #
  ##################
  function import() {
    if ($this->get('import_method') == '') {
      return array(
        'success' => true,
        'msg' => 'Keine Importmethode definiert.'
      );
    }

    $method_name = 'import_' . $this->get('import_method');
    if (!method_exists($this, $method_name)) {
      return array(
        'success' => false,
        'msg' => 'Die Funktion ' . $method_name . ' zum importieren der Ressource existiert nicht.'
      );
    }
    $this->update_status(6);
    $result = $this->${method_name}();
    if (!$result['success']) {
      $this->update_status(-1);
      return $result;
    }

    $this->update_status(7);
    return $result;
  }

  /**
   * Import shape with ogr2ogr to Postgres
   */
  function import_ogr2ogr_shape() {
    $this->debug->show('Starte Funktion import_org2ogr_shape', true);
    if ($this->get('import_table') == '') {
      return array(
        'success' => false,
        'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
      );
    }


    $dest_path = SHAPEPATH . 'datentool/' . $this->get('dest_path');
    $files = array_filter(
      scandir($dest_path),
      function($entry) use ($dest_path) {
        return is_file($dest_path . $entry);
      }
    );

    $this->debug->show('Dateien im Verzeichnis:<br>' . implode('<br>', $files), true);
    $result = required_shape_files_exists($files);
    if (!$result['success']) { return $result; }
    $shp_file = '';
    foreach($files AS $file) {
      $info = pathinfo($dest_path . $file);
      if (strtolower($info['extension']) == 'shp') {
        $shp_file = $info['basename'];
      }
    };
    $this->debug->show('Der Name des Shapes lautet: ' . $shp_file, true);

    $result = $this->gui->data_import_export->ogr2ogr_import('import', $this->get('import_table'), $this->get('import_epsg'), $dest_path . $shp_file, $this->database, '', NULL, '-overwrite', 'UTF-8', true);
    if ($result != '') {
      return array(
        'success' => false,
        'msg' => $result
      );
    }
    return array(
      'success' => true,
      'msg' => 'Shape-Datei ' . $shp_file . ' erfolgreich eingelesen.'
    );
  }

  /**
   * Import raster files to Postgres
   */
  function import_raster2pgsql() {

  }

  #####################
  # Transform methods #
  #####################
  function transform() {
    if ($this->get('transform_method') == '') {
      return array(
        'success' => true,
        'msg' => 'Keine Transformationsmethode definiert.'
      );
    }

    $method_name = 'transform_' . $this->get('transform_method');
    if (!method_exists($this, $method_name)) {
      return array(
        'success' => false,
        'msg' => 'Die Funktion ' . $method_name . ' zum transformieren der Ressource existiert nicht.'
      );
    }

    function transform_exec_sql() {

    }

    function transform_replace_from_import() {
      $sql = "

      ";
    }

    $this->update_status(8);
    $result = $this->${method_name}();
    if (!$result['success']) {
      $this->update_status(-1);
      return $result;
    }
    $this->update_status(9);
    return $result;
  }
  /**
   * Overwrite if exists from import
   */
  function replace_from_import() {
    // ToDo: implement on demand
    return array(
      'success' => true,
      'msg' => 'Transformation in den Zieldatensatz erfolgreich beendet.'
    );
  }

  function waermebedarf() {
    // ToDo: implement on demand

  }

}

?>
