<?php

//Konfiguration
//Allgemeine Diagrammdaten

$Diagrammbreite = 500;
$Diagrammhoehe = 400;

$Diagrammtitel = "Darstellung der Anzahl der Zugriffe pro Layer";

//Ränder & Abstände

$AbstandAussen = 3;

$RandOben = 30;
$RandLinks = 30;
$RandUnten = 80;
$RandRechts = 20;


//Diagrammfarben

$HintergrundfarbeR = 255;
$HintergrundfarbeG = 255;
$HintergrundfarbeB = 255;

$HintergrundfarbeAuswertungsbereichR = 224;
$HintergrundfarbeAuswertungsbereichG = 224;
$HintergrundfarbeAuswertungsbereichB = 224;

$TextfarbeR = 64;
$TextfarbeG = 64;
$TextfarbeB = 64;

$AchsenfarbeR = 0;
$AchsenfarbeG = 0;
$AchsenfarbeB = 0;

$BalkenfarbeR = 32;
$BalkenfarbeG = 128;
$BalkenfarbeB = 196;


#######################################

// Daten aus der Datenbank zuordnen

   for ($i=0, $XWerte="", $YWerte=""; $i<=count($this->account->NumbOfAccessUserStelleM); $i++) { 
	$XWerte[] = $this->account->NumbOfAccessUserStelleM[$i]['lName'];
	$YWerte[] = $this->account->NumbOfAccessUserStelleM[$i]['NumberOfAccess'];
   }

// Festlegung der Minima und Maxima der X- und Y-Werte

$XMin = 0;
$XMax = count($XWerte);

$Tmp = $YWerte;
sort($Tmp);
$YMin = $Tmp[0];
rsort($Tmp);
$YMax = $Tmp[0];


//Festlegung des Inhalttyps für den Webbrowser

# header("Content-type: image/png");
$Grafik = imagecreatetruecolor($Diagrammbreite, $Diagrammhoehe);


//Festlegung der verwendeten Farben

$Textfarbe = imagecolorallocate($Grafik, $TextfarbeR, $TextfarbeG, $TextfarbeB);
$Hintergrundfarbe = imagecolorallocate($Grafik, $HintergrundfarbeR, $HintergrundfarbeG, $HintergrundfarbeB);
$HintergrundfarbeAuswertungsbereich = imagecolorallocate($Grafik, $HintergrundfarbeAuswertungsbereichR, $HintergrundfarbeAuswertungsbereichG, $HintergrundfarbeAuswertungsbereichB);
$Achsenfarbe = imagecolorallocate($Grafik, $AchsenfarbeR, $AchsenfarbeG, $AchsenfarbeB);
$Balkenfarbe = imagecolorallocate($Grafik, $BalkenfarbeR, $BalkenfarbeG, $BalkenfarbeB);


//Allgemeinen Hintergrund und Auswertungsbereich einfärben

imagefill($Grafik, 0, 0, $Hintergrundfarbe);
imagefilledrectangle($Grafik, $AbstandAussen+$RandLinks, $AbstandAussen+$RandOben, $Diagrammbreite-1-$AbstandAussen-$RandRechts, $Diagrammhoehe-1-$AbstandAussen-$RandUnten, $HintergrundfarbeAuswertungsbereich);


//Allgemeine Berechnungen zur X- und Y-Achse

if($YMax>10)
{
	$YMaxAbstand = 10;
}
else
{
	$YMaxAbstand = $YMax;
}

$YAbstand = ($Diagrammhoehe-2*$AbstandAussen-$RandOben-$RandUnten-10)/$YMaxAbstand;
$YWertHoehe = ($Diagrammhoehe-2*$AbstandAussen-$RandOben-$RandUnten-10)/$YMax;
$XAbstand = ($Diagrammbreite-2*$AbstandAussen-$RandRechts-$RandLinks-10)/$XMax;
$Balkendicke = ($XAbstand-4)/2;


//Hilfslinien einzeichnen

imagesetstyle($Grafik, array($Achsenfarbe, $Achsenfarbe, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT));

for($i=0; $i<$YMaxAbstand; $i++)
{
	imageline($Grafik, $AbstandAussen+$RandLinks, $AbstandAussen+$RandOben+10+($YAbstand*$i), $Diagrammbreite-$AbstandAussen-$RandRechts-1, $AbstandAussen+$RandOben+10+($YAbstand*$i), IMG_COLOR_STYLED);
}


//X-Achse einzeichnen

imageline($Grafik, $AbstandAussen+$RandLinks, $Diagrammhoehe-$AbstandAussen-$RandUnten, $Diagrammbreite-$AbstandAussen-$RandRechts, $Diagrammhoehe-$AbstandAussen-$RandUnten, $Achsenfarbe);
imageline($Grafik, $AbstandAussen+$RandLinks, $Diagrammhoehe-$AbstandAussen-$RandUnten-1, $Diagrammbreite-$AbstandAussen-$RandRechts, $Diagrammhoehe-$AbstandAussen-$RandUnten-1, $Achsenfarbe);
for($i=0; $i<$XMax; $i++)
{
	imageline($Grafik, $AbstandAussen+$RandLinks+($XAbstand*$i)+($XAbstand/2+3), $Diagrammhoehe-$AbstandAussen-$RandUnten-2, $AbstandAussen+$RandLinks+($XAbstand*$i)+($XAbstand/2+3), $Diagrammhoehe-$AbstandAussen-$RandUnten+2, $Achsenfarbe);
	imagestringup($Grafik, 1, $AbstandAussen+$RandLinks+($XAbstand*$i)+($XAbstand/2), $Diagrammhoehe-$AbstandAussen-$RandUnten+4+(imagefontwidth(1)*strlen($XWerte[$i])), $XWerte[$i], $Achsenfarbe);
}


//Y-Achse einzeichnen

imageline($Grafik, $AbstandAussen+$RandLinks, $AbstandAussen+$RandOben, $AbstandAussen+$RandLinks, $Diagrammhoehe-$AbstandAussen-$RandUnten, $Achsenfarbe);
imageline($Grafik, $AbstandAussen+$RandLinks+1, $AbstandAussen+$RandOben, $AbstandAussen+$RandLinks+1, $Diagrammhoehe-$AbstandAussen-$RandUnten, $Achsenfarbe);
for($i=0; $i<=$YMaxAbstand; $i++)
{
	imageline($Grafik, $AbstandAussen+$RandLinks-2, $AbstandAussen+$RandOben+10+($YAbstand*$i), $AbstandAussen+$RandLinks+2, $AbstandAussen+$RandOben+10+($YAbstand*$i), $Achsenfarbe);
	if($YMax>10)
	{
		imagestring($Grafik, 1, $AbstandAussen+$RandLinks-4-(imagefontwidth(1)*strlen(round($YMax-(($YMax/10)*$i), 0))), $AbstandAussen+$RandOben+10-(imagefontheight(1)/2)+($YAbstand*$i), round($YMax-(($YMax/10)*$i), 0), $Achsenfarbe);
	}
	else
	{
		imagestring($Grafik, 1, $AbstandAussen+$RandLinks-4-(imagefontwidth(1)*strlen($YMax-$i)), $AbstandAussen+$RandOben+10-(imagefontheight(1)/2)+($YAbstand*$i), $YMax-$i, $Achsenfarbe);
	}
}


//Werte einzeichnen

for($i=0; $i<$XMax; $i++)
{
	imagerectangle($Grafik, $AbstandAussen+$RandLinks+($XAbstand*($i+1))-($XAbstand/2-3)-$Balkendicke, $Diagrammhoehe-$AbstandAussen-$RandUnten-($YWertHoehe*$YWerte[$i]), $AbstandAussen+$RandLinks+($XAbstand*($i+1))-($XAbstand/2-3)+$Balkendicke, $Diagrammhoehe-$AbstandAussen-$RandUnten, $Achsenfarbe);
	imagefilledrectangle($Grafik, $AbstandAussen+$RandLinks+($XAbstand*($i+1))-($XAbstand/2-3)-$Balkendicke+1, $Diagrammhoehe-$AbstandAussen-$RandUnten-($YWertHoehe*$YWerte[$i])+1, $AbstandAussen+$RandLinks+($XAbstand*($i+1))-($XAbstand/2-3)+$Balkendicke-1, $Diagrammhoehe-$AbstandAussen-$RandUnten-2, $Balkenfarbe);
}


//Diagrammtitel einzeichnen

imagestring($Grafik, 5, $AbstandAussen+$RandLinks+($Diagrammbreite-(2*$AbstandAussen+$RandLinks+$RandRechts)-imagefontwidth(5)*strlen($Diagrammtitel))/2, $AbstandAussen, $Diagrammtitel, $Textfarbe);


// Ausgabe des Bildes und Freigabe des belegten Speichers

imagepng($Grafik,GRAPHICSPATH.'diagramm_saeule.png');
echo '<img src="'.copy_file_to_tmp(GRAPHICSPATH."diagramm_saeule.png").'">';
imagedestroy($Grafik);
unlink(GRAPHICSPATH.'diagramm_saeule.png')
?>