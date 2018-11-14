<?php
	include PLUGINS.'bevoelkerung/model/chart.php';

$filename = "charttest.png";

drawTernaryChart(
  IMAGEPATH . $filename,               // Dateiname in die das Chart geplottet wird
  array("Junge", "Erwachsene", "Alte"), // Achsenbeschriftungen
  $this->triple,
  "Ternary Chart - Titel Test",           // Titelzeile des Diagramms
  array(                                  // Optionen
    "size"           => 500,              //  - Größe des Charts in Pixeln (quadratisch, default 400px)
    "titleColor"     => 'black',          //  - Farbe der Überschrift (default: black, kann Format '#xxxxxx' sein oder Farbname aus Definition s.u.)
    "axisColor"      => 'blue',           //  - Farbe der Achsen (default: black)
    "minorAxisColor" => 'gray50',         //  - Farbe der Gitterachsen (default: axisColor)
    "valueColor"     => 'green',          //  - Farbe der Datenpunkte (default: red)
    "labelColor"     => 'magenta',        //  - Farbe der Achsenbeschriftungen (default: gray75)
    "backgoundColor" => '#ffffaa',        //  - Hintergrundfarbe (default: white)
    "markerSize"     => 7                 //  - Größe der Datenpunkte in Pixeln (default: 5px)
  )
);

?>
<p>Beispiel-Plot eines Dreiecksdiagramms<br>Die Farben und Gr&ouml;&szlig;en k&ouml;nnen mit den Optionen eingestellt werden</p>
<img src=<?php echo TEMPPATH_REL. $filename ?>>

