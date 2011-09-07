<?php
class TK {
  var $name;
  var $nr;
  var $database;
  var $extent;

  ##################### Liste der Funktionen ####################################
  #
  # function TK()  - Construktor
  #
  ################################################################################

  function TK($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }
  
  function getNrByName($name) {
    
    
    if ($error) {
      $ret[0]=1;
      $ret[1]=$errormsg;
    }
    else {
      $ret[0]=0;
      $blatt['nr']=$nr;
      $blatt['art']=$art;
      $ret[1]=$blatt;
    }
    return $ret;
  }
  
  function getExtent($art,$nr) {
    # Testen ob Nummer valide ist
    if ($valide) {
      # Berechnen der Koordinaten
      
    }
    else {
      $ret[0]=1;
      $ret[1]="";     
    }
    return $extent;
  }
}
?>
