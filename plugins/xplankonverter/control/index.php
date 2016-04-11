<?php
$this->goNotExecutedInPlugins = false;

include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'LayerGroup.php');
include_once(CLASSPATH . 'data_import_export.php');
include(PLUGINS . 'xplankonverter/model/konvertierung.php');
include(PLUGINS . 'xplankonverter/model/shapefiles.php');
include(PLUGINS . 'xplankonverter/model/xplan.php');

switch($this->go){

  case 'get_comments':
    $xplan->get_comments($this->formvars);
    break;
  case 'get_planzeichen':
    $xplan->get_planzeichen($this->formvars);
    break;
  case 'get_plaene':
    $xplan->get_plaene($this->formvars);
    break;
  case 'get_inspirefeaturetype':
    $xplan->get_inspirefeaturetype($this->formvars);
    break;
  case 'get_inspireenumeration':
    $xplan->get_inspireenumeration($this->formvars);
    break;
  case 'load_xsd':
    $xplan->load_xsd($this->formvars['file'], $this->formvars['truncate']);
    break;
  case 'show_comments':
    if ($this->formvars['action'] == 'Hinzufügen') {
      create_comments($this->formvars);
      show_comments();
    }
    break;
  case 'show_calendar':
    $xplan->show_calendar();
    break;
  case 'show_elements':
    $xplan->show_elements();
    break;
  case 'show_hilfe':
    $xplan->show_hilfe();
    break;
  case 'show_ontologie':
    $xplan->show_ontologie();
    break;
  case 'show_inspire':
    $xplan->show_inspire();
    break;
  case 'show_impressium':
    $xplan->show_impressium();
    break;
  case 'show_plaene':
    $xplan->show_plaene();
    break;
  case 'show_planzeichen':
    $xplan->show_planzeichen();
    break;
  case 'show_simple_types':
    $this->main = PLUGINS . 'xplankonverter/view/simple_types.php';
    $this->output();
    break;
  case 'show_uml':
    $xplan->show_uml();
    break;
  
  case 'build_gml' : {
    include(PLUGINS . 'xplankonverter/model/build_gml.php');
    
    // Die Verbindung zur Datenbank kvwmapsp ist verfügbar in
    //$this->pgdatabase->dbConn);
    $this->gml_builder = new gml_builder($this->pgdatabase);
    
    // Einbindung des Views
    $this->main=PLUGINS . 'xplankonverter/view/build_gml.php';
    
    $this->output();
    
  } break;

  case 'convert' : {
    include(PLUGINS . 'xplankonverter/model/converter.php');
    include(PLUGINS . 'xplankonverter/model/constants.php');

    // Die Verbindung zur Datenbank kvwmapsp ist verfügbar in
    //$this->pgdatabase->dbConn);
    $this->converter = new Converter($this->pgdatabase, PG_CONNECTION);
    
    // Einbindung des Views
    $this->main = PLUGINS . 'xplankonverter/view/convert.php';
    
    $this->initialData = array(
      'config' => array(
        'active' => 'step1',
        'step1' => array(
            'disabled' => false
        ),
        'step2' => array(
            'disabled' => true
        ),
        'step3' => array(
            'disabled' => true
        ),
        'step4' => array(
            'disabled' => true
        )
      )
    );
    
    $this->initialData['step1']['konvertierungen'] = $this->converter->getConversions();
    
    $this->output();
    
  } break;

  case 'xplankonverter_konvertierungen_index' : {
    $this->main = '../../plugins/xplankonverter/view/konvertierungen.php';
    $this->output();
  } break;

  case 'xplankonverter_shapefiles_index' : {
    if ($this->formvars['konvertierung_id'] == '') {
      $this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
      $this->main = 'Hinweis.php';
    }
    else {
      $this->konvertierung = new Konvertierung($this, 'xplankonverter', 'konvertierungen');
      $this->konvertierung->find_by('id', $this->formvars['konvertierung_id']);
      if (isInStelleAllowed($this->Stelle->id, $this->konvertierung->get('stelle_id'))) {
        if (isset($_FILES['shape_files']) and $_FILES['shape_files']['name'][0] != '') {
          $upload_path = XPLANKONVERTER_SHAPE_PATH . $this->formvars['konvertierung_id'] . '/';

          # create upload dir if not exists
          if (!is_dir($upload_path)) {
            $old = umask(0);
            mkdir($upload_path, 0770, true);
            umask($old);
          }

          # unzip and copy files to upload folder
          $uploaded_files = xplankonverter_unzip_and_copy($_FILES['shape_files'], $upload_path);

          # get layerGroupId or create a group if not exists
          $layer_group_id = $this->konvertierung->get('layer_group_id');
          if (empty($layer_group_id))
            $layer_group_id = $this->konvertierung->createLayerGroup();

          foreach($uploaded_files AS $uploaded_file) {
            if ($uploaded_file['extension'] == 'dbf' and $uploaded_file['state'] != 'ignoriert') {

              # delete existing shape file 
              $shapeFile = new ShapeFile($this, 'xplankonverter', 'shapefiles');
              $shapeFile = $shapeFile->find_where("
                filename = '" . $uploaded_file['filename'] . "' AND
                konvertierung_id = '" . $this->konvertierung->get('id') . "' AND
                stelle_id = " . $this->konvertierung->get('stelle_id')
              );
              if (!empty($shapeFile->data)) {
                $this->debug('<p>Lösche gefundenes shape file.');
                $shapeFile->deleteLayer();
                $shapeFile->deleteDataTable();
                $shapeFile->delete();
              }

              # create new record in shapefile table
              $shapeFile->create(
                array(
                  'filename' => $uploaded_file['filename'],
                  'konvertierung_id' => $this->konvertierung->get('id'),
                  'stelle_id' => $this->konvertierung->get('stelle_id'),
                  'epsg_code' => $this->formvars['epsg_code']
                )
              );

              # Create schema for data table if not exists
              $shapeFile->createDataTableSchema();

              # load into database table
              $created_tables = $shapeFile->loadIntoDataTable();

              # Set datatype for shapefile
              $shapeFile->set('datatype', $created_tables[0]['datatype']);
              $shapeFile->update();

              # create layer
              $this->formvars['Name'] = $shapeFile->get('filename');
              $this->formvars['Datentyp'] = $shapeFile->get('datatype');
              $this->formvars['Gruppe'] = $layer_group_id;
              $this->formvars['pfad'] = 'Select * from ' . $shapeFile->dataTableName() . ' where 1=1';
              $this->formvars['Data'] = 'the_geom from (select oid, * from ' .
                $shapeFile->dataSchemaName() . '.' . $shapeFile->dataTableName() .
                ' where 1=1) as foo using unique oid using srid=' . $shapeFile->get('epsg_code');
              $this->formvars['maintable'] = $shapeFile->dataTableName();
              $this->formvars['schema'] = $shapeFile->dataSchemaName();
              $this->formvars['connection'] = $this->pgdatabase->connect_string;
              $this->formvars['connectiontype'] = '6';
              $this->formvars['filteritem'] = 'oid';
              $this->formvars['tolerance'] = '5';
              $this->formvars['toleranceunits'] = 'pixels';
              $this->formvars['epsg_code'] = $shapeFile->get('epsg_code');
              $this->formvars['querymap'] = '1';
              $this->formvars['queryable'] = '1';
              $this->formvars['transparency'] = '75';
              $this->formvars['postlabelcache'] = '0';
              $this->formvars['allstellen'] = '2300';
              $this->formvars['ows_srs'] = 'EPSG:' . $shapeFile->get('epsg_code') . ' EPSG:25833 EPSG:4326 EPSG:2398';
              $this->formvars['wms_server_version'] = '1.1.0';
              $this->formvars['wms_format'] = 'image/png';
              $this->formvars['wms_connectiontimeout'] = '60';
              $this->formvars['selstellen'] = '1, ' . $this->konvertierung->get('stelle_id') . ', 1, ' . $this->konvertierung->get('stelle_id');
              $this->LayerAnlegen();

              # Assign layer_id to shape file record
              $shapeFile->set('layer_id', $this->formvars['selected_layer_id']);
              $shapeFile->update();

              # Ordne layer zur Stelle
              $this->Stellenzuweisung(
                array($shapeFile->get('layer_id')),
                array($this->konvertierung->get('stelle_id'))
              );

              # Füge eine Klasse zum neuen Layer hinzu.
              $this->formvars['class_name'] = 'alle';
              $this->formvars['class_id'] = $this->Layereditor_KlasseHinzufuegen();

              # Füge einen Style zur Klasse hinzu
              $this->add_style();

            }
          }
        } # end of upload files
        $this->main = '../../plugins/xplankonverter/view/shapefiles.php';
      }
    }
    $this->output();
  } break;

  case 'xplankonverter_shapefiles_delete' : {
    if ($this->formvars['shapefile_id'] == '') {
      $this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher ein Shape Datei ausgewählt wurde.';
      $this->main = 'Hinweis.php';
    }
    else {
      $shapefile = new Shapefile($this, 'xplankonverter', 'shapefiles');
      $shapefile->find_by('id', $this->formvars['shapefile_id']);
      if (isInStelleAllowed($this->Stelle->id, $shapefile->get('stelle_id'))) {
        # Delete the layerdefinition in mysql (rolleneinstellungen, layer, classes, styles, etc.)
        $shapefile->deleteLayer();
        # Delete the postgis data table that hold the data of the shape file
        $shapefile->deleteDataTable();
        # Delete the uploaded shape files itself
        $shapefile->deleteUploadFiles();
        # Delete the record in postgres shapefile table (unregister for konverter)
        $shapefile->delete();
        $this->main = '../../plugins/xplankonverter/view/shapefiles.php';
      }
    }
    $this->output();
  } break;

  case 'home' : {
    // Einbindung des Views
    $this->main=PLUGINS . 'xplankonverter/view/home.php';

    $this->output();

  } break;

  default : {
    $this->goNotExecutedInPlugins = true;    // in diesem Plugin wurde go nicht ausgeführt
  }
}

function isInStelleAllowed($guiStelleId, $requestStelleId) {
  if ($guiStelleId == $requestStelleId)
    return true;
  else {
    echo '<br>(Diese Aktion kann nur von der Stelle ' . $this->Stelle->Bezeichnung . ' aus aufgerufen werden';
    return false;
  }
}

/*
* extract zip files if necessary and copy files to upload folder
*/
function xplankonverter_unzip_and_copy($shape_files, $dest_dir) {
  $uploaded_files = array();
  # extract zip files if necessary and copy files to upload folder
  foreach($shape_files['name'] AS $i => $shape_file_name) {
    $path_parts = pathinfo($shape_file_name);

    if (strtolower($path_parts['extension']) == 'zip') {
      # extract files if the extension is zip
      $temp_files = extract_uploaded_zip_file($shape_files['tmp_name'][$i]);
    }
    else {
      # set data from single file
      $path_parts = pathinfo($shape_file_name);
      $temp_files = array(
        array(
          'basename' => $path_parts['basename'],
          'filename' => $path_parts['filename'],
          'extension' => strtolower($path_parts['extension']),
          'tmp_name' => $shape_files['tmp_name'][$i],
          'unziped' => false
        )
      );
    }

    # copy temp shape files to destination
    foreach($temp_files AS $temp_file) {
      $uploaded_files[] = xplankonverter_copy_uploaded_shp_file($temp_file, $dest_dir);
    }
  }
  return $uploaded_files;
}

/*
* Packt die angegebenen Zip-Datei im sys_temp_dir Verzeichnis aus
* und gibt die ausgepackten Dateien in der Struktur von
* hochgeladenen Dateien aus
*/
function extract_uploaded_zip_file($zip_file) {
  $sys_temp_dir = sys_get_temp_dir();
  $extracted_files = array_map(
    function($extracted_file) {
      $path_parts = pathinfo($extracted_file);
      return array(
        'basename' => $path_parts['basename'],
        'filename' => $path_parts['filename'],
        'extension' => $path_parts['extension'],
        'tmp_name' => sys_get_temp_dir() . '/' . $extracted_file,
        'unziped' => true
      );
    },
    unzip($zip_file, false, false, true)
  );
  return $extracted_files;
}

/*
* Copy files from sys_temp_dir to upload directory and mark if
* files are new, override older or are ignored
*/
function xplankonverter_copy_uploaded_shp_file($file, $dest_dir) {
  $messages = array();
  if (in_array($file['extension'], array('dbf', 'shx', 'shp'))) {
    if (file_exists($dest_dir . $file['basename'])) {
      $file['state'] = 'geändert';
    }
    else {
      $file['state'] = 'neu';
    }
    if ($file['unziped']) {
      rename($file['tmp_name'], $dest_dir . $file['basename']);
    }
    else {
      move_uploaded_file($file['tmp_name'], $dest_dir . $file['basename']);
    }
  }
  else {
    if ($file['unziped'])
      unlink($file['tmp_name']);
    $file['state'] = 'ignoriert';
  }
  return $file;
}
?>