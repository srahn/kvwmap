<?php
include "chart.php";

$filename = "img/charttest.png";

drawTernaryChart($filename,               // Dateiname in die das Chart geplottet wird
  array("Label A", "Label B", "Label C"), // Achsenbeschriftungen
  array(                                  // Array der Datenwerte
    array(20,30,50),
    array(10,40,50),
    array(21,53,26),
    array(20,40,40)
  ),
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
<html>
  <head><title>Chart-Beispiel</title>
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
  </head>
  <body>
    <p>Beispiel-Plot eines Dreiecksdiagramms<br>Die Farben und Gr&ouml;&szlig;en k&ouml;nnen mit den Optionen eingestellt werden</p>
    <img src=<?php echo $filename ?>>
  </body>
  </html>