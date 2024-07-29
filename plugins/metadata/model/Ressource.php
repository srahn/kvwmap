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
    echo '<br>update_outdated';
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
      if (count($ressources) > 0) {
        $ressource = $ressources[0];
        $result = $ressource->run_update();
        $this->write_update_log($result, $this->get('status_id'));
      }
    }
  }

  function write_update_log($result, $status_id) {

  }

  function run_update() {
    $ressource->update_status('status_id = 1');

    $result = $ressource->download();
    if (!$result['success']) { return $result; }

    $ressource->unpack();
    if (!$result['success']) { return $result; }

    $ressource->import();
    if (!$result['success']) { return $result; }

    $ressource->transform();
    if (!$result['success']) { return $result; }

    $ressource->update_status('status_id = 0');
    return array(
      'success' => true,
      'msg' => 'Ressource erfolgreich aktualisiert.'
    );
  }

  function update_status($status_id) {
    $this->update($status_id);
  }

  ####################
  # Download methods #
  ####################
  function download() {
    if ($this->get('download_method') != '') {
      $method_name = 'download_' . $this->get('download_method');
      $this->update_status('status_id = 2');
      $result = $this->${method_name}();
      if (!$result['success']) { return $result; }

      $this->update_status('status_id = 3');
      return $result;
    }
    return array(
      'success' => true,
      'msg' => 'Keine Downloadmethode angegeben.'
    );
  }
  /**
   * Download dataset or its subsets to dest_path
   */
  function download_urls() {
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
      echo '<p>Download from URLs:<br>' . implode('<br>', $download_urls);
      $dest_path = SHAPEPATH . $this->get('dest_path');
      echo '<br>to destination Verzeichnis:' . $dest_path;
      if (!file_exists($dest_path)) {
        echo '<br>Lege Verzeicnis an, weil es noch nicht existiert!';
        mkdir($dest_path, 0777, true);
      }
      foreach($download_urls AS $download_url) {
        echo '<br>Download ' . basename($download_url) . ' from url: ' . $download_url;
        copy($download_url, $dest_path . basename($download_url));
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
    if ($this->get('unpack_method') != '') {
      $method_name = 'unpack_' . $this->get('unpack_method');
      $this->update_status('status_id = 4');
      $this->${method_name}();
      $this->update_status('status_id = 5');
    }
  }
  /**
   * Function unzip specific or all files of a directory to a destination directory,
   * log it in a logfile,
   * and remove the zip-files afterward
   */
  function unpack_unzip() {
    # find files in directory (dest_path)
    # extract to dest_path
    $cmd = 'unzip -j *.zip -d ' . $this->get('dest_path');
    exec($cmd, $output, $return_var);
    if ($return_var != '') {
      return array(
        'success' => false,
        'msg' => 'Fehler bei unzip der Ressource ' . $this->get_id . ' Rückgabewert: ' . $return_var
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
    $this->update_status('status_id = 6');
    $result = $this->${method_name}();
    if (!$result['success']) {
      $this->update_status('status_id = -1');
      return $result;
    }

    $this->update_status('status_id = 7');
    return $result;
  }

  /**
   * Import shape with ogr2ogr to Postgres
   */
  function import_ogr2ogr_shape($filename) {
    // ToDo: implement on demand
    echo '<br>import_org2ogr_shape';
    if ($this->get('import_table') == '') {
      return array(
        'success' => false,
        'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
      );
    }
    $result = $this->ogr2ogr_import('import', $this->get('import_table'), $this->get('import_epsg'), $filename, $this->database, '', NULL, NULL, 'UTF-8');
    if ($result != '') {
      return array(
        'success' => false,
        'msg' => $result
      );
    }
    return array(
      'success' => true,
      'msg' => 'Shape-Datei ' . $filename . ' erfolgreich eingelesen'
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
    if ($this->get('transform_method') != '') {
      $method_name = 'transform_' . $this->get('transform_method');
      $this->update_status('status_id = 8');
      $this->${method_name}();
      $this->update_status('status_id = 9');
    }
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
