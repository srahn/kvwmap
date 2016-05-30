<?php
class prognose {
	
	function prognose($database) {
		global $debug;
		$this->debug=$debug;
		$this->database = $database;
	}

	/*
	* Diese Funktion packt die Dateien der Prognosedaten aus,
	* bearbeitet den Inhalt für den Import auf und führt den 
	* Import der Daten in die Datenbank aus.
	*
	*
	*/
	function import() {
		$dir = new DirectoryIterator(BEVOELKERUNG_IMPORT_DATA_PATH);
		echo 'loop through ' . BEVOELKERUNG_IMPORT_DATA_PATH . '*.gz <br>';
		foreach (glob(BEVOELKERUNG_IMPORT_DATA_PATH . '*.gz') as $filename) {
			echo 'Packe ' . $filename . ' aus.<br>';
			exec('gunzip ' . $filename);
		}
		foreach (glob(BEVOELKERUNG_IMPORT_DATA_PATH . '*.sql') as $filename) {
			$this->prepareImport($filename);
			$this->importSQL($filename);
		}
	}

	function transpose() {
/*		$sql = "
			TRUNCATE mvbevoelkerung.zahlen;
		";
		$ret = $this->database->execSQL($sql, 4, 0);
*/
		foreach (glob(BEVOELKERUNG_IMPORT_DATA_PATH . '*.sql') as $filename) {
			if (strpos($filename, 'Prognose') !== false) {
				$year = substr($filename, -13, 4);
				$tablename = 'mvbevoelkerung' . $year . 'p.q1model' . $year . 'p';
				$this->transposeTable($year, $tablename);
			}
			if (strpos($filename, 'Statistik') !== false) {
				$year = substr($filename, -10, 4);
				$tablename = 'mvbevoelkerung' . $year . '.q1modell' . $year . 'nb';
				$this->transposeTable($year, $tablename);
			}
		}
	}

	function prepareImport($filename) {
		if (strpos($filename, 'Statistik') !== false) {
			echo 'Bereite Statistikdatei: ' . $filename . ' für Import auf.<br>';
			$year = substr($filename, -10, 4);
			exec("
			  year=" . $year . "
				sed -i 's|`||g' " . $filename . "
				sed -i 's|MStand|mstand|g' " . $filename . "
				sed -i 's|Gebiet|gebiet|g' " . $filename . "
				sed -i 's|Jahr|jahr|g' " . $filename . "
				sed -i 's|JAHR|jahr|g' " . $filename . "
				sed -i 's|KennungNum|kennungnum|g' " . $filename . "
				sed -i 's|KennungStr|kennungstr|g' " . $filename . "
				sed -i 's|KennungSTR|kennungstr|g' " . $filename . "
				sed -i 's|KKZ|kkz|g' " . $filename . "
				sed -i 's|Masse|masse|g' " . $filename . "
				sed -i 's|PRZ|prz|g' " . $filename . "
				sed -i 's|ENGINE=InnoDB|--ENGINE=InnoDB|g' " . $filename . "
				sed -i 's|UNLOCK TABLES|--LOCK TABLES|g' " . $filename . "
				sed -i 's|int(11)|integer|g' " . $filename . "
				sed -i 's|bigint(20)|bigint|g' " . $filename . "
				sed -i 's|decimal(41,0)|integer|g' " . $filename . "
				sed -i 's|LOCK TABLES|--LOCK TABLES|g' " . $filename . "
				sed -i 's|CHARACTER SET cp850||g' " . $filename . "
				sed -i 's|CHARACTER SET utf8mb4||g' " . $filename . "
				sed -i 's|DROP TABLE|DROP SCHEMA IF EXISTS mvbevoelkerung'$year' CASCADE; DROP SCHEMA IF EXISTS mvbevoelkerung'$year'p CASCADE; CREATE SCHEMA mvbevoelkerung'$year'; DROP TABLE|g' " . $filename . "
				sed -i 's|q1_model|mvbevoelkerung'$year'.q1model|g' " . $filename . "
			");
		}
		if (strpos($filename, 'Prognose') !== false) {
			echo 'Bereite Prognosedatei: ' . $filename . ' für Import auf.<br>';
		  $year = substr($filename, -13, 4);
			exec("
				year=" . $year . "
				sed -i 's|`||g' " . $filename . "
				sed -i 's|MStand|mstand|g' " . $filename . "
				sed -i 's|Gebiet|gebiet|g' " . $filename . "
				sed -i 's|Jahr|jahr|g' " . $filename . "
				sed -i 's|KennungNum|kennungnum|g' " . $filename . "
				sed -i 's|KennungStr|kennungstr|g' " . $filename . "
				sed -i 's|GKZ|gkz|g' " . $filename . "
				sed -i 's|Masse|masse|g' " . $filename . "
				sed -i 's|PRZ|prz|g' " . $filename . "
				sed -i 's|ENGINE=InnoDB|--ENGINE=InnoDB|g' " . $filename . "
				sed -i 's|UNLOCK TABLES|--LOCK TABLES|g' " . $filename . "
				sed -i 's|int(11)|integer|g' " . $filename . "
				sed -i 's|LOCK TABLES|--LOCK TABLES|g' " . $filename . "
				sed -i 's|DROP TABLE|DROP SCHEMA IF EXISTS mvbevoelkerung'" . $year . "'p CASCADE; CREATE SCHEMA mvbevoelkerung'" . $year . "'p; DROP TABLE|g' " . $filename . "
				sed -i 's|q1_model|mvbevoelkerung'$year'p.q1model|g' " . $filename . "
			");
		}
		if (strpos($filename, 'admin_key_lnog') !== false) {
			echo 'Bereite Steuerungsdatei mit Verschlüsselungen: ' . $filename . ' für Import auf.<br>';
			exec("
				sed -i 's|`||g' " . $filename . "
				sed -i 's|Struktur|struktur|g' " . $filename . "
				sed -i 's|Gebiet|gebiet|g' " . $filename . "
				sed -i 's|isKreis|iskreis|g' " . $filename . "
				sed -i 's|isGMD|isgmd|g' " . $filename . "
				sed -i 's|kennung_ID|kennung_id|g' " . $filename . "
				sed -i 's|PRZ|prz|g' " . $filename . "
				sed -i 's|Kreis|kreis|g' " . $filename . "
				sed -i 's|ENGINE=InnoDB|--ENGINE=InnoDB|g' " . $filename . "
				sed -i 's|UNLOCK TABLES|--LOCK TABLES|g' " . $filename . "
				sed -i 's|int(11)|integer|g' " . $filename . "
				sed -i 's|bigint(20)|bigint|g' " . $filename . "
				sed -i 's|LOCK TABLES|--LOCK TABLES|g' " . $filename . "
				sed -i 's|admin_key_lnog|mvbevoelkerung.admin_key_lnog|g' " . $filename . "
			");
		}
		if (strpos($filename, 'massen') !== false) {
			echo 'Bereite Steuerungsdatei mit Massen: ' . $filename . ' für Import auf.<br>';
			exec("
				sed -i 's|`||g' " . $filename . "
				sed -i 's|Auswahlmassen_Nr|auswahlmassen_nr|g' " . $filename . "
				sed -i 's|Kurzname|kurzname|g' " . $filename . "
				sed -i 's|Auswahlmasse|auswahlmasse|g' " . $filename . "
				sed -i 's|ENGINE=InnoDB|--ENGINE=InnoDB|g' " . $filename . "
				sed -i 's|UNLOCK TABLES|--LOCK TABLES|g' " . $filename . "
				sed -i 's|LOCK TABLES|--LOCK TABLES|g' " . $filename . "
				sed -i 's| massen| mvbevoelkerung.massen|g' " . $filename . "
			");
		}
	}

	function importSQL($filename) {
		echo 'Lese SQL-Datei: ' . $filename . ' in Datenbank ein.<br>';
		exec('psql -U kvwmap -f ' . $filename . 'kvwmapsp');
	}
	/*
	* Diese Funktion extrahiert die Prognosedaten und speichert
	* sie in einer neuen Tabelle in transponierter Form
	* in der jeder Datensatz nur eine Bevölkerungszahl eines
	* Nahbereiches und einer Masse beinhaltet
	*/
	function transposeTable($year, $tablename) {
		echo '<br>Schreibe transponierte Daten aus Table: ' . $tablename . ' in CSV-Datei.';

		$sql = "
			SELECT
				*
			FROM
				" . $tablename . "
		";
		print_r($this->database);
		$ret = $this->database->execSQL($sql, 4, 0);

		if ($ret[0] == 0) {
			$csvfilename = BEVOELKERUNG_IMPORT_DATA_PATH . $tablename . '.csv';
			$csvfile = fopen($csvfilename, 'w');
			$values = array();
			# Insgesamt werden folgende Felder ausgegeben:
			# jahr, mstand, kennungnum, masse, prz, wbl, v, bu, z
			while ($row = pg_fetch_assoc($ret[1])) {
				$values = array(
					intval($row['jahr']) - 2000, # jahr
					$row['mstand'], # mstand
					intval($row['kennungnum']), # kennungnum
					$row['masse'], # masse
					intval($row['prz']) # prz
				);
				foreach (array('m' => 'f', 'w' => 't') AS $sex => $wbl) {
					for($i = 0; $i <= 100; $i++) {
						if ($i < 100) {
							$bu = $i + 1;
							$z = $row[$sex . $i . 'bu' . ($i + 1)];
						}
						else {
							$bu = 199;
							$z = $row[$sex . 'ggl100'];
						}
						fputcsv(
							$csvfile,
							array_merge(
								$values,
								array(
									$wbl, # wbl
									$i, # v
									$bu, # bu
									$z #z
								)
							),
							';',
							'"'
						);
					}
				}
			}
			fclose($csvfile);
		}

		echo "<br>Kopiere in Tabelle mvbevoelkerung.zahlen von CSV-Datei: " . $csvfilename;
		$sql = "
			COPY mvbevoelkerung.zahlen (jahr, mstand, kennungnum, masse, prz, wbl, v, bu, z) FROM '" . $csvfilename . "'
			WITH
				CSV
				DELIMITER ';'
		";
		$this->database->execSQL($sql, 4, 0);

/*
				
		$v = $bu = $z = array();
		for ($i = 0; $i < 100; $i++) {
			$m[] = 'f';
			$w[] = 't';
			$v[] = $i;
			$bu[] = $i + 1;
			$zm[] = 'm' . $i . 'bu' . ($i + 1);
			$zw[] = 'w' . $i . 'bu' . ($i + 1);
		}
		$m[] = 'f';
		$w[] = 't';
		$v[] = 100;
		$bu[] = 199;
		$zm[] = 'mggl100';
		$zw[] = 'wggl100';
				
		$sql = "
			INSERT INTO TABLE mvbevoelkerung.zahlen (
				jahr, mstand, kennungnum, masse, prz, wbl, v, bu, z
			)
			SELECT
				jahr - 2000,
				mstand,
				kennungnum,
				masse,
				prz,
				unnest(ARRAY['" .
					implode("', '", $m) . ", " .
					implode("', '", $w) .
				"']) AS wbl,
				unnest(ARRAY[" .
					implode(', ', $v) . ", " .
					implode(', ', $v) .
				"]) AS v,
				unnest(ARRAY[" .
					implode(', ', $bu) . ", " .
					implode(', ', $bu) .
				"]) AS bu,
				unnest(ARRAY[" .
					implode(', ', $zm) . ", " .
					implode(', ', $zw) .
				"]) AS z
			FROM " . $tablename . "
			WHERE
				jahr = '" . $year . "'
		";*/
	}

	function find_triple($bereich, $jahr, $geschlecht, $col1, $col2, $col3, $label) {
		$sql = "
			SELECT
				round((" . $col1 . " / summe * 100)::numeric, 1),
				round((" . $col2 . " / summe * 100)::numeric, 1),
				round((" . $col3 . " / summe * 100)::numeric, 1), " .
				$label . "
			FROM
				mvbevoelkerung." . $bereich . "vergleiche
			WHERE
				jahr = " . $jahr . " AND
				geschlecht = '" . $geschlecht . "'
		";
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		if ($ret[0] == 0) {
			$result = array();
			while ($rs = pg_fetch_row($ret[1])) {
				$result[] = $rs;
			}
		}
		return $result;
	}
}
?>