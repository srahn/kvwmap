<?php

//Summe aller Zugriffe:

$AlleZugriffe=0;
$anz=count($this->account->NumbOfAccessUserStelleM);
$effekt3d=15; // Gibt die Dicke vom Pie Chart an. 
$b_ellipse=200; // Breite der Ellipse
$h_ellipse=100; // Höhe der Ellipse
$rr=255; $gg=255; $bb=255; //Legt die Hintergrundfarbe fest
$titel="Zugriffe pro Layer! "; // Überschrift zum Pie Chart

// Ermitteln der Summe aller Zugriffe auf die einzelnen Layer 
for ($i=0; $i<$anz; $i++) {
  $AlleZugriffe=$AlleZugriffe+$this->account->NumbOfAccessUserStelleM[$i]['NumberOfAccess'];
}

//Anzahl, Name, aus Datei als Variable schreiben und Prozente berechnen

for ($i=0; $i<$anz; $i++) {
  $anzahl[$i]=$this->account->NumbOfAccessUserStelleM[$i]['NumberOfAccess'];
  $name[$i]=$this->account->NumbOfAccessUserStelleM[$i]['lName'];
  $proz[$i]=number_format(((100*$this->account->NumbOfAccessUserStelleM[$i]['NumberOfAccess'])/$AlleZugriffe),1);
}

//Summe der Prozente berechnen
$summe=0;
for($i=0;$i<$anz;$i++)
{$summe=$summe+$proz[$i];}
//Summe der Prozente darf nicht grösser als 100 sein.
if($summe>100)
{echo 'Error!!! Summe der Prozente ist große als 100.';}

//Höhe und Breite des gesamten Bildes
$breitebild=3*$b_ellipse;
$hoehebild=1.5*$h_ellipse;
//Mittelpunkt der Ellipse festlegen:
$posx=.6*$b_ellipse;
$posy=.75*$h_ellipse;

//Bild, in welches der Kuchen gezeichnet werden soll, wird konstruiert.
$bild = imagecreate($breitebild,$hoehebild);
//Hintergrundfarbe wird gezeichnet.
$farbe    = imagecolorallocate($bild, $rr,$gg,$bb);

//Punkte auf Ellipsenbegrenzung bestimmen
$pt0=0;
$pt1=$proz[0]*3.6;
for($k=1,$l=2;$k<$anz;$k++,$l++)
{${pt.$l}=${pt.$k}+3.6*$proz[$k];}

//Jeder Farbe (rbg) eine Zufallszahl zuweisen von 1 bis 225
for($i=0;$i<$anz;$i++)
{
$r=mt_rand(0,225); $g=mt_rand(0,225); $b=mt_rand(0,225);
${farbe.$i}=imagecolorallocate($bild, $r, $g, $b);
${farbehell.$i}=imagecolorallocate($bild, $r+30, $g+30, $b+30);
}

//Kuchen zeichnen: von unten her ganze Ellipsen (!) zeichnen; am Schluss zuoberst helle Ellipse
for($z=$posy+$effekt3d;$z>$posy;$z--)
{
         for($i=0,$k=1;$i<$anz;$i++,$k++)
         {
         imagefilledarc($bild,$posx,$z,$b_ellipse,$h_ellipse, ${pt.$i},${pt.$k},${farbe.$i},IMG_ARC_PIE);
         }
}
for($i=0,$k=1;$i<$anz;$i++,$k++)
{
imagefilledarc($bild,$posx,$posy,$b_ellipse,$h_ellipse, ${pt.$i},${pt.$k},${farbehell.$i},IMG_ARC_PIE);
}

//x-Wert und y-Werte des obersten Quadrates festlegen
$xx=1.2*$b_ellipse;
$yy=$posy-$anz*6;

//Quadrate platzieren
for($i=0,$k=1;$k<$anz+1;$i++,$k++){
  imagefilledrectangle($bild,$xx,$yy+$i*15,$xx+10,$yy+$i*15+10,${farbehell.$k});
}
//Titel schreiben
$schwarz=imagecolorallocate($bild,0,0,0);
imagettftext($bild,14,0,10,20,$schwarz,WWWROOT.APPLVERSION."fonts/arial.ttf",$titel);

//Legende schreiben (Bild, Schriftgrösse, Schriftneigung, x, y, Schriftfarbe, Schrift, Text)
for($i=0;$i<$anz;$i++)
{
$legende= " - ". $name[$i].": ".$anzahl[$i]." Zugriffe (".$proz[$i]."%)";
imagettftext($bild,10,0,$xx+20,$yy+$i*15-5,$schwarz,WWWROOT.APPLVERSION."fonts/arial.ttf",$legende);
}

//png-Datei erzeugen:

imagepng($bild,GRAPHICSPATH.'diagramm_kuchen.png');
echo '<img src="'.copy_file_to_tmp(GRAPHICSPATH."diagramm_kuchen.png").'">';
imagedestroy($bild);
unlink(GRAPHICSPATH.'diagramm_kuchen.png')

?>
