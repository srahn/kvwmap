<?php
	/*
	*	Fragt die Doppikklassen ab und ruft den View zur Darstellung auf über den man neue Doppikobjekte anlegen kann.
	*/
	$GUI->ukos_show_doppikklassen = function() use ($GUI) {
		$sql = "
			SELECT
				objektart,
				schema_name,
				CASE WHEN objektart = 'punktobjekt' THEN 'sep_' ELSE '' END || table_name AS table_name,
				table_name AS doppik_objekt_name,
				CASE WHEN objektart = 'punktobjekt' THEN '0' WHEN objektart = 'streckenobjekt' THEN '1' ELSE '2' END AS geometry_type
			FROM
				(
					SELECT
						COALESCE(
							CASE WHEN p3.relname = 'basisobjekt' THEN NULL ELSE p3.relname END,
							CASE WHEN p2.relname = 'basisobjekt' THEN NULL ELSE p2.relname END,
							p.relname
						) AS objektart,
						pn3.nspname || '.' || p3.relname ebene_0,
						pn2.nspname || '.' || p2.relname ebene_1,
						pn.nspname || '.' || p.relname parent_name,
						cn.nspname AS schema_name,
						c.relname AS table_name
					FROM
						pg_inherits i JOIN
						pg_class AS c ON i.inhrelid = c.oid JOIN
						pg_class as p ON i.inhparent = p.oid JOIN
						pg_namespace AS pn ON p.relnamespace = pn.oid JOIN
						pg_namespace AS cn ON c.relnamespace = cn.oid JOIN
						pg_inherits i2 ON p.oid = i2.inhrelid JOIN
						pg_class AS p2 ON i2.inhparent = p2.oid JOIN
						pg_namespace AS pn2 ON p2.relnamespace = pn2.oid LEFT JOIN
						pg_inherits i3 ON p2.oid = i3.inhrelid LEFT JOIN
						pg_class AS p3 ON i3.inhparent = p3.oid LEFT JOIN
						pg_namespace AS pn3 ON p3.relnamespace = pn3.oid
				) AS hierarchy
			WHERE
				schema_name = 'ukos_doppik'
				AND objektart IN ('punktobjekt', 'streckenobjekt', 'verkehrsflaeche') -- damit werden punktundstreckenobjekt sowie schild erstmal ausgelassen
				OR (schema_name = 'ukos_okstra' AND table_name = 'verkehrseinschraenkung')
			ORDER BY
				doppik_objekt_name
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
				`Layer_ID`,
				`Name`
			FROM
				`layer`
			WHERE
				`schema` LIKE '%" . $schema_name . "%' AND
				`maintable` = '" . $table_name . "' AND
				`Datentyp` = " . $geometry_type . "
		";
		# echo '<br>Sql: ' . $sql;
		$ret = $GUI->database->execSQL($sql, 1, 4);

		$nix_gefunden = true;
		if ($ret['success'] AND $GUI->database->result->num_rows() == 1) {
			$nix_gefunden = false;
			$rs = $GUI->database->result->fetch_assoc();
			$GUI->formvars['selected_layer_id'] = $rs['layer_id'];
			$GUI->neuer_Layer_Datensatz();
		}
		else {
			if ($schema_name == 'ukos_doppik' AND $table_name == 'strasse' AND $geometry_type == 2) {
				while ($rs = $GUI->database->result->fetch_assoc()) {
					if ($rs['Name'] == 'Straßen') {
						$nix_gefunden = false;
						$GUI->formvars['selected_layer_id'] = $rs['layer_id'];
						$GUI->neuer_Layer_Datensatz();
					}
				}
			}
		}

		if ($nix_gefunden) {
			$GUI->add_message('error', 'Zu dieser Doppikklasse wurde kein passender Layer gefunden. Legen Sie einen Layer an mit Schema ukos_doppik, dem richtigen Tabellennamen und Geometrietyp!');
			$GUI->ukos_show_doppikklassen();
		}
	};
?>