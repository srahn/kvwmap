<?

  $this->goNotExecutedInPlugins = false;
  
  switch($this->go){

    case 'say_hallo' : {
      include(PLUGINS . 'xplankonverter/model/xplan.php');
      $xplan = new $xplan;
      $this->loadMap('DataBase');          # Karte anzeigen
      $currenttime=date('Y-m-d H:i:s',time());
      $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
      $this->drawMap();
      $this->saveMap('');
      $this->output();
    } break;

    default : {
      $this->goNotExecutedInPlugins = true;    // in diesem Plugin wurde go nicht ausgeführt
    }
  }

?>