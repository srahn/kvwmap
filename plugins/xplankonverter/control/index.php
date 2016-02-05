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
      
      // Die Verbindung zur Datenbank kvwmapsp ist verfügbar in
      //$this->pgdatabase->dbConn);
      $this->converter = new converter($this->pgdatabase);
      
      // Einbindung des Views
      $this->main=PLUGINS . 'xplankonverter/view/convert.php';
      
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

?>