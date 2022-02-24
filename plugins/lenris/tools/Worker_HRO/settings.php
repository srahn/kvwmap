<?php

$host='';
$dbname='';
$user='';
$password='';

$nachweis_dir = "/mnt/gds/Risse/";

$select = "
		a.id,
		'13' || a.gemarkung || '000' AS flurid,
		a.blatt AS blattnummer,
		a.datum,
		1 AS gueltigkeit,
		'" . $nachweis_dir . "' || a.gemarkung || '/PDFA/' || a.pdf as link_datei,
		a.antragsnummer AS stammnr,
		CASE
      WHEN NOT ST_IsValid(a.geometrie)
			THEN ST_Envelope(a.geometrie)
		ELSE 
			a.geometrie
		END AS the_geom,
		a.jahr as fortfuehrung,
		a.riss AS rissnummer,
		1 as geprueft,
		a.art_lenris AS art
";

?>
