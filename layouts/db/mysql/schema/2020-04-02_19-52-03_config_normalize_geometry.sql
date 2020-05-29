BEGIN;

	INSERT INTO `config` (
		`name`,
		`prefix`,
		`value`,
		`description`,
		`type`,
		`group`,
		`saved`,
		`editable`
	) VALUES (
		'NORMALIZE_AREA_THRESHOLD',
		'',
		'0.5',
		'Maximale Flächengröße von Dreiecken, die durch 3 benachbarte Stützpunkte gebildet werden mit dem Winkel im mittleren Stützpunkt kleiner als NORMALIZE_ANGLE_THRESHOLD verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Quadatmeter. Zentralpunkte, deren Flächen kleiner sind, werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.5. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/',
		'double precision',
		'Datenbanken',
		0,
		2
	), (
		'NORMALIZE_ANGLE_THRESHOLD',
		'',
		'0.5',
		'Maximale Winkelgröße im mittleren Stützpunkt von 3 benachbarten Stützpunkten, deren Fläche kleiner als NORMALIZE_AREA_THRESHOLD ist. Zentralpunkte in denen der Winkel kleiner ist werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Angegeben in Dezimalgrad. Default 0.5 Grad.  Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/',
		'double precision',
		'Datenbanken',
		0,
		2
	), (
		'NORMALIZE_POINT_DISTANCE_THRESHOLD',
		'',
		'0.005',
		'Maximaler Abstand von benachbarten Punkten in einem Dreieck welches kleiner ist als NORMALIZE_AREA_THRESHOLD unter Berücksichtigung von NORMALIZE_ANGLE_THRESHOLD verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Meter. Ein Punkt bei dem der Abstand zum anderen kleiner wird bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.005. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/',
		'double precision',
		'Datenbanken',
		0,
		2
	), (
		'NORMALIZE_NULL_AREA',
		'',
		'0.0001',
		'Maximale Flächengröße von Dreiecken, die durch 3 benachbarte Stützpunkte gebildet werden unabhängig von den Winkeln verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Quadatmeter. Zentralpunkte, deren Flächen kleiner sind, werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.0001. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/',
		'double precision',
		'Datenbanken',
		0,
		2
	);

COMMIT;
