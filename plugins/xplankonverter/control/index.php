<?php

  $this->goNotExecutedInPlugins = false;

  include_once(CLASSPATH . 'PgObject.php');
  include(PLUGINS . 'xplankonverter/model/konvertierung.php');
  include(PLUGINS . 'xplankonverter/model/shapefiles.php');
  include(PLUGINS . 'xplankonverter/model/validator.php');

  switch($this->go){

    case 'say_hallo' : {
      include(PLUGINS . 'xplankonverter/model/xplan.php');

      // Die Verbindung zur Datenbank kvwmapsp ist verfügbar in
      //$this->pgdatabase->dbConn);
      $this->xplan = new xplan($this->pgdatabase);

      // Einbindung des Views
      $this->main=PLUGINS . 'xplankonverter/view/xplan.php';

      $this->output();

    } break;

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
        $this->konvertierung = new Konvertierung($this->pgdatabase, 'xplankonverter', 'konvertierungen');
        $this->konvertierung->find_by_id($this->formvars['konvertierung_id']);
        if (isInStelleAllowed($this->Stelle->id, $this->konvertierung->get('stelle_id'))) {
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
        $shapefile = new Shapefile($this->pgdatabase, 'xplankonverter', 'shapefiles');
        $shapefile->find_by_id($this->formvars['shapefile_id']);
        if (isInStelleAllowed($this->Stelle->id, $shapefile->get('stelle_id'))) {
          $shapefile->deleteShape();
          $this->main = '../../plugins/xplankonverter/view/shapefiles.php';
        }
      }
      $this->output();
    } break;

    case 'xplankonverter_konvertierungen_validate': {
      if ($this->formvars['konvertierung_id'] == '') {
        $this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
        $this->main = 'Hinweis.php';
      }
      else {
        $this->konvertierung = new Konvertierung($this->pgdatabase, 'xplankonverter', 'konvertierungen');
        $this->konvertierung->find_by_id($this->formvars['konvertierung_id']);
        if (isInStelleAllowed($this->Stelle->id, $this->konvertierung->get('stelle_id'))) {
          if ($this->konvertierung->get('status') == Konvertierung::$STATUS[1]) {
            // set status
            $this->konvertierung->set('status', Konvertierung::$STATUS[2]);
            $this->konvertierung->update();
            $this->validator = new Validator($this->pgdatabase, PG_CONNECTION);
            $this->validator->validateKonvertierung(
                $this->konvertierung,
                function() { // Validation successful
                  echo 'SUCCESS';
                },
                function($error) { // Validation failed
                  echo $error;
                }
            );
          }
          $this->main = '../../plugins/xplankonverter/view/konvertierungen.php';
        }
      }
      $this->output();
    } break;

    case 'xplankonverter_konvertierungen_execute': {
      if ($this->formvars['konvertierung_id'] == '') {
        $this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
        $this->main = 'Hinweis.php';
      }
      else {
        $this->konvertierung = new Konvertierung($this->pgdatabase, 'xplankonverter', 'konvertierungen');
        $this->konvertierung->find_by_id($this->formvars['konvertierung_id']);
        if (isInStelleAllowed($this->Stelle->id, $this->konvertierung->get('stelle_id'))) {
          if ($this->konvertierung->get('status') != 'validiert') {
            $this->validator = new Validator($this->pgdatabase, PG_CONNECTION);
            $this->validator->validateKonvertierung(
                $this->konvertierung,
                function() { // Validation successful
                  echo 'SUCCESS';
                },
                function($error) { // Validation failed
                  echo $error;
                }
            );
          } else {
            echo 'Jetz Konvertieren';
          }
          $this->main = '../../plugins/xplankonverter/view/konvertierungen.php';
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
?>