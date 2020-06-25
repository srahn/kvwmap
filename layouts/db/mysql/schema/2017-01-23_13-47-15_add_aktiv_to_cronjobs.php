<?php

$sql = "
	SELECT
		1
	FROM
		INFORMATION_SCHEMA.COLUMNS
	WHERE
		table_name = 'cron_jobs' AND
		column_name = 'aktiv'
";
$result = $this->database->execSQL($sql, 0, 0);
if ($result[0]) {
	echo '<br>Fehler bei der Abfrage ob die Spalte aktiv schon in der Tabelle cron_jobs existiert.<br>';
}
else {
	if ($this->database->result->num_rows() == 0) {
		$sql = "ALTER TABLE `cron_jobs` ADD `aktiv` BOOLEAN NOT NULL DEFAULT false AFTER `stelle_id`;";
		$result = $this->database->execSQL($sql, 0, 0);
	}

	$sql = "UPDATE migrations SET filename = '2017-01-23_13-47-15_add_aktiv_to_cronjobs.php' WHERE filename = '2017-01-23_13:47:15_add_aktiv_to_cronjobs.sql'";
	$result = $this->database->execSQL($sql, 0, 0);
}

?>
