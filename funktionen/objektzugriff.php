<?php
/**
 * function objektzugriff($stelle_id) am 12.07.2005 gelöscht
 * wenn diese wieder gebraucht wird kann Sie aus Version 1.4.4 entnommen werden
 */

/**
 * 
 */
function compressListe($liste) {
  $anz=count($liste);
  $intervalLaenge=0; # Differenz aus letzen und ersten Element eines Intervals, z.B. bei 4,5,6 (6-4)=2
  $compListe=$liste[0]; 
  for ($i=1;$i<$anz;$i++) {
    if ($liste[$i]-$liste[$i-1]>1) {
      if ($intervalLaenge>0) {
  $compListe.='-'.$liste[$i-1].';'.$liste[$i];
      }
      else {
  $compListe.=';'.$liste[$i];
      }
      $intervalLaenge=0;
    }
    else {
      $intervalLaenge++;
    }
  }
  if ($intervalLaenge>0) {
    $compListe.='-'.$liste[$i-1];
  }
  return $compListe;
}

function decompressListe($liste) {
  $intervals=explode(";",$liste);
  for ($i=0;$i<count($intervals);$i++) {
    if ($intervals[$i]!="") {
      $interval=explode("-",$intervals[$i]);
      if (count($interval)==1) {
        # nur ein einzelner Wert
        $decompListe[]=$interval[0];
      }
      else {
        for ($j=0;$j<$interval[1]-$interval[0]+1;$j++) {
          $decompListe[]=$interval[0]+$j;
        }
      }
    }
  }
  return $decompListe;
}
?>