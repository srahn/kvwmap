<?php
	/*
	*	Fragt die Doppikklassen ab und ruft den View zur Darstellung auf über den man neue Doppikobjekte anlegen kann.
	*/
	$GUI->ukos_show_doppikklassen = function() use ($GUI) {
		$sql = "
			SELECT
				cn.nspname AS schema_name,
				c.relname AS table_name,
				CASE WHEN p.relname LIKE '%punkt' THEN '0' WHEN p.relname LIKE '%strecke' THEN '1' ELSE '2' END AS geometry_type
			FROM
				pg_inherits i JOIN
				pg_class AS c ON i.inhrelid = c.oid JOIN
				pg_class as p ON i.inhparent = p.oid JOIN
				pg_namespace AS pn ON p.relnamespace = pn.oid JOIN
				pg_namespace AS cn ON c.relnamespace = cn.oid
			WHERE
				pn.nspname = 'ukos_okstra' AND
			  p.relname IN ('strassenausstattung_punkt', 'strassenausstattung_strecke', 'verkehrsflaeche')
			ORDER BY
				c.relname
		";
		#echo '<br>Sql: ' . $sql;
		$ret = $GUI->pgdatabase->execSQL($sql, 1, 4);
		if ($ret['success'] AND pg_num_rows($ret[1]) > 0) {
			$GUI->ukos_doppikklassen = pg_fetch_all($ret[1]);
		}
		else {
			$GUI->ukos_doppikklassen = array();
		}
		$GUI->main = '../../plugins/ukos/view/doppikobjekte.php';
		$GUI->output();
	};

	$GUI->ukos_new_doppikobjekt = function($schema_name, $table_name, $geometry_type) use ($GUI) {
		$sql = "
			SELECT
				`Layer_ID`
			FROM
				`layer`
			WHERE
				`schema` LIKE '%" . $schema_name . "%' AND
				`maintable` LIKE '%" . $table_name . "%' AND
				`Datentyp` = " . $geometry_type . "
		";
		#echo '<br>Sql: ' . $sql;
		$ret = $GUI->database->execSQL($sql, 1, 4);

		if ($ret['success'] AND mysql_num_rows($ret[1]) == 1) {
			$rs = mysql_fetch_assoc($ret[1]);
			$GUI->formvars['selected_layer_id'] = $rs['Layer_ID'];
			$GUI->neuer_Layer_Datensatz();
		}
		else {
			$GUI->add_message('error', 'Zu dieser Doppikklasse wurde kein passender Layer gefunden. Legen Sie einen Layer an mit Schema ukos_doppik, dem richtigen Tabellennamen und Geometrietyp!');
			$GUI->ukos_show_doppikklassen();
		}
	};
?>