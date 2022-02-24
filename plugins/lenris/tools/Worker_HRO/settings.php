<?php

$host='';
$dbname='';
$user='';
$password='';

$nachweis_dir = "/risse/";

$select = "
		a.id,
		'13' || a.gemarkung || '000' AS flurid,
		a.blatt AS blattnummer,
		a.datum,
		TRUE AS gueltigkeit,
		" . $nachweis_dir . " || a.gemarkung || '/' || a.bild as link_datei,
		a.antragsnr AS stammnr,
		CASE WHEN NOT ST_IsValid(a.geometrie)
			THEN ST_Transform(ST_Envelope(a.geometrie), 25833) 
		ELSE 
			ST_Transform(a.geometrie, 25833)
		END AS the_geom,
		a.jahr as fortfuehrung,
		a.riss AS rissnummer,
		1 as geprueft,
		a.art_lenris AS art
";

?>
