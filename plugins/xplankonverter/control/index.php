<?php

  $this->goNotExecutedInPlugins = false;
  
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
      $this->main = '../../plugins/xplankonverter/view/shapefiles.php';
      $this->output();
    } break;

    case 'xplankonverter_shapefiles_upload' : {
      var_dump($this->formvars);
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

?>