<?php

########################################################################################################################################################################
#																																																																																			 #
#	Dieses Skript überprüft ob in Layerdefinitionen oids verwendet werden und welche ID-Spalte man stattdessen verwenden kann.																					 #									 		 #
#																																																																																			 #
########################################################################################################################################################################

global $admin_stellen;
if(!in_array($this->Stelle->id, $admin_stellen)){
	exit;
}

$this->layer_dbs = array();

$db_check_sqls[] = [
										'name' => 'Views',
										'sql' => "
											WITH RECURSIVE views AS (
												SELECT  v.oid::regclass         AS \"view\"
																,v.relkind = 'm'        AS is_materialized
																,1                      AS level
																,ns.nspname             AS \"schema\"
																,d.refobjid::regclass   AS \"tabelle\"
												FROM    pg_attribute    AS a
												JOIN    pg_depend       AS d    ON  d.refobjsubid = a.attnum
																												AND d.refobjid = a.attrelid
												JOIN    pg_class        AS c    ON  a.attrelid = c.oid
												JOIN    pg_rewrite      AS r    ON  r.oid = d.objid
												JOIN    pg_class        AS v    ON  v.oid = r.ev_class
												JOIN    pg_namespace    AS ns   ON  ns.oid = v.relnamespace
												JOIN    pg_class        AS t    ON  d.refobjid = t.oid
												WHERE   v.relkind IN ('v','m')
												AND     d.classid = 'pg_rewrite'::regclass
												AND     d.refclassid = 'pg_class'::regclass
												AND     d.deptype = 'n'    -- normal dependency
												AND     a.attname = 'oid'
												AND     a.attnum < 0

										--		UNION
										--			 -- von Views abhängige Views
										--		SELECT   v.oid::regclass,
										--						 v.relkind = 'm',
										--						 views.level + 1
										--						,ns.nspname       AS \"schema\"
										--						,null::regclass  AS \"tabelle\"
										--		FROM     views
										--		JOIN     pg_depend   AS d    ON d.refobjid = views.view
										--		JOIN     pg_rewrite  AS r    ON r.oid = d.objid
										--		JOIN     pg_class    AS v    ON v.oid = r.ev_class
										--		JOIN     pg_namespace    AS ns   ON  ns.oid = v.relnamespace
										--		WHERE    v.relkind IN ('v', 'm')
										--		AND      d.classid = 'pg_rewrite'::regclass
										--		AND      d.refclassid = 'pg_class'::regclass
										--		AND      d.deptype = 'n'
										--		AND      v.oid <> views.view  -- bitte keine Schleife
										)

										SELECT
											views.schema                    AS \"Schema\"
											,substring(views.view::text FROM strpos(views.view::text, '.')+1 ) 			 AS \"View\"
											,views.tabelle as \"Tabelle\"
										FROM
											views
										GROUP BY view, is_materialized, level, schema, tabelle
										ORDER BY level ASC"
									 ];
									 
$db_check_sqls[] = [
										'name' => 'Contraints',
										'sql' => "
											SELECT  ns.nspname     AS \"Schema\"
															,c.relname      AS \"Tabelle\"
															,con.conname    AS \"Constraint\"
											FROM    pg_catalog.pg_class c
											JOIN    pg_catalog.pg_namespace ns      ON ns.oid = c.relnamespace
											JOIN    pg_catalog.pg_constraint con    ON c.oid = con.conrelid
											JOIN    pg_catalog.pg_attribute  attr   ON  (
																																			(attr.attnum = any(con.conkey) AND con.confkey IS NULL)
																																	OR
																																			(attr.attnum = any(con.confkey) AND con.conkey IS NULL)
																																	)
																																	AND c.oid = attr.attrelid
											WHERE   ns.nspname NOT IN ('pg_catalog')
											AND     attr.attnum < 1 /*system columns wie oid*/
											AND     lower(attr.attname) LIKE '%oid%'"
										];
										
$db_check_sqls[] = [
										'name' => 'Funktionen',
										'sql' => "
											SELECT ns.nspname AS \"Schema\",
													p.proname as \"Funktion\"
											FROM pg_proc p
											JOIN pg_namespace ns ON p.pronamespace = ns.oid
											WHERE (ns.nspname <> ALL (ARRAY['pg_catalog'::name, 'information_schema'::name, 'public'::name]))
											AND regexp_replace(upper(pg_get_functiondef(p.oid)), 'PG_[A-Z]*\.OID', '', 'g') ~~ '%.OID%'::text
											AND p.proisagg = false
											ORDER BY ns.nspname"
										];
										
$db_check_sqls[] = [
										'name' => 'Indizes',
										'sql' => "
											SELECT  ns.nspname              AS  \"Schema\"
															,t.relname              AS  \"Tabelle\"
															,ci.relname     	AS  \"Index\"
											FROM    pg_index        i
											JOIN    pg_class        ci  ON  i.indexrelid = ci.oid
											JOIN    pg_class        t   ON  i.indrelid = t.oid
											JOIN    pg_namespace    ns  ON  ci.relnamespace = ns.oid
											JOIN    pg_attribute    a   ON  a.attrelid = t.oid
																											AND a.attnum = any(i.indkey)
											WHERE   ns.nspname NOT IN ('pg_catalog','pg_toast')
											AND     a.attnum < 0 --system columns, vermutlich oids
											AND     a.attname = 'oid'"
										];

function checkStatus($layer) {
	$status['oid'] = ($layer['oid'] == 'oid' ? false : true);
	$status['query'] = ((strpos($layer['pfad'], ' oid') !== false OR strpos($layer['pfad'], ',oid') !== false OR strpos($layer['pfad'], '.oid') !== false)? false : true);
	$status['data'] = (strpos($layer['Data'], 'oid') !== false ? false : true);
	return $status;
}

function get_oid_alternative($layer) {
	global $GUI;
	if (!array_key_exists($layer['connection_id'], $GUI->layer_dbs)){
		$GUI->layer_dbs[$layer['connection_id']] = new pgdatabase();
		if (!$GUI->layer_dbs[$layer['connection_id']]->dbConn = @pg_connect($layer['connectionstring'])){
			$result['error'] = 'Verbindung zur PostgreSQL-DB nicht erfolgreich!';
			return $result;
		}
	}
	if ($layer['maintable'] == ''){
		$result['error'] = 'Haupttabelle ist nicht gesetzt.';
	}
	else {
		$sql = "
			SET search_path = " . ($layer['schema'] != '' ? $layer['schema'] . ', ' : '') . " public;
			SELECT 
				a.attname as pk
			FROM 
				pg_attribute a 
				LEFT JOIN pg_index i ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey) AND i.indnatts = 1
			WHERE  
				a.attrelid = '" . $layer['maintable'] . "'::regclass and 
				attnum > 0 and 
				attisdropped is false and 
				(pg_get_serial_sequence('" . $layer['maintable'] . "', attname) IS NOT NULL OR i.indisunique OR atttypid::regtype = 'uuid'::regtype)
		";
		$ret = @pg_query($GUI->layer_dbs[$layer['connection_id']]->dbConn, $sql);
		if($ret == false){
			$result['error'] = @pg_last_error($GUI->layer_dbs[$layer['connection_id']]->dbConn);
		}
		else{
			$rs=pg_fetch_assoc($ret);
			$result['oid_alternative'] = $rs['pk'];
		}
	}
	return $result;
}

function delete_oid_in_sql($query) {
	$query = str_replace([' as oid', ' AS oid', ' oid,', ',oid', ', oid'], ['', '', ' '], $query);
	if (strpos($query, '.oid') !== false) {
		$query = preg_replace('/\w+\.oid,/', '', $query);		#	tablename.oid,
	}
	return $query;
}

function replace_oid_in_data($data, $id) {
	if ($id != '') {
		$data = delete_oid_in_sql($data);
		if (!strpos($data, ',' . $id) AND		#	,id
				!strpos($data, $id . ',') AND		#	id,
				!strpos($data, '*')) {					# *
			$pos = stripos($data, 'select ');
			if ($pos !== false) {
				$data = substr_replace($data, 'SELECT ' . $id . ', ', $pos, 7);
			}
		}
		$data = str_ireplace('unique oid', 'unique ' . $id, $data);
	}
	return $data;
}

switch ($this->formvars['action']) {
	case 'set_new_oid' : {
		foreach ($this->formvars['layer_id'] as $layer_id) {
			if ($this->formvars['check_' . $layer_id] AND $this->formvars['new_oid_' . $layer_id] != '') {
				$sql = "
					UPDATE
						layer
					SET
						oid = '" . $this->formvars['new_oid_' . $layer_id] . "'
					WHERE
						Layer_ID = " . $layer_id . "
				";
				$result = $this->database->execSQL($sql);
			}
		}
	}break;
	case 'set_new_query' : {
		foreach ($this->formvars['layer_id'] as $layer_id) {
			if ($this->formvars['check_' . $layer_id] AND $this->formvars['new_query_' . $layer_id] != '') {
				$sql = "
					UPDATE
						layer
					SET
						pfad = '" . $this->formvars['new_query_' . $layer_id] . "'
					WHERE
						Layer_ID = " . $layer_id . "
				";
				$result = $this->database->execSQL($sql);
			}
		}
	}break;
	case 'set_new_data' : {
		foreach ($this->formvars['layer_id'] as $layer_id) {
			if ($this->formvars['check_' . $layer_id] AND strpos(strtolower($this->formvars['new_data_' . $layer_id]), 'using ') !== false) {
				$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
				$select = getDataParts($this->formvars['new_data_' . $layer_id])['select'];
				$from = substr($select, strrpos(strtolower($select), 'from') + 5);
				if (strpos($from, ',') === false) {
					$table = explode(' ', $from);
					$table_part = explode('.', $table[0]);
					if ($table_part[1] == ''){
						$table_part[1] = $table_part[0];
					}
					if (trim($table_part[1]) == $this->formvars['maintable_' . $layer_id]) {
						$sql = "
							UPDATE
								layer
							SET
								Data = '" . $this->formvars['new_data_' . $layer_id] . "'
							WHERE
								Layer_ID = " . $layer_id . "
						";
						$result = $this->database->execSQL($sql);
					}
				}
			}
		}
	}break;
}

$color[false] = '#db5a5a';
$color[true] = '#36908a';

$this->formvars['order'] = $this->formvars['order'] ?: 'Name';

$query = "
	SELECT DISTINCT 
		layer.*,
		ul.Layer_ID as used_layer_layer_id,
		CONCAT('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password) as connectionstring,
		g.Gruppenname
	FROM 
		`layer` 
		LEFT JOIN used_layer ul ON ul.Layer_ID = layer.Layer_ID
		LEFT JOIN	u_groups g ON layer.Gruppe = g.id 
		LEFT JOIN	connections c ON c.id = connection_id
	WHERE
		connectiontype = 6
	ORDER BY " . $this->formvars['order'] . "
";

# nur bestimmte Layer einschließen
#$with_layer_id = '1,2,3,4';
$with_layer_id = '';
if ($with_layer_id != '') {
	$query .= '	AND Layer_ID IN (' . $with_layer_id . ')';
}
# bestimmte Layer ausschließen
#$without_layer_id = '1,2,3,4';
$without_layer_id = '';
if ($without_layer_id != '') {
	$query .= '	AND Layer_ID NOT IN (' . $without_layer_id . ')';
}

#echo '<br>get layer with sql: ' . $query;
$result = $this->database->execSQL($query);

?>

<style>
	#main{
		position: relative;
		padding-top: 130px;
	}
	#head{
		position: absolute;
		top: 10px;
		margin-left: 40%;
		line-height: 23px;
	}
	.nav_link{
		margin-left: -1px;
	}
	#tab_div{
		margin: 5px;
		position: relative;
		padding-top: 30px;
		border: 1px solid #555;
	}
	#tab{
		border-collapse: collapse;
		width: 1670px;
	}
	#tab tbody{
		border-top: 1px solid #555;
	}
	#tab tr{
		border-bottom: 1px solid #555;
	}
	#tab td{
		border-right: 1px solid #555;
		padding: 5px;
		word-break: break-word;
		min-width: 80px;
	}
	#tab textarea{
		height: 100px;
		width: 350px;
	}
	#tab .replaced{
		padding: 5px;
		background: yellow;
	}	
	.scrolltable_header {
    position: absolute;
    top: -25px;
    height: 31px;
    border-left: 1px solid #555;
    padding: 5px 0 0 5px;
    margin-left: -6px;
    margin-top: -6px;
	}
	
	#db_check {
		margin-top: 20px;
		padding: 5px;
		text-align: left;
	}
	#db_check table{
		border: 1px solid #555;
		border-collapse: collapse;
	}
	#db_check td {
		border: 1px solid #555;
		padding: 2px 5px 2px 5px;
	}
	
</style>

<script type="text/javascript">

	var ok_layer_display = '';
	var nicht_zu_stelle_zugeordnet_layer_display = '';

	function select_text(textarea, str){
		var index = textarea.value.indexOf(str);
		if(index > 0){
			textarea.focus();
			textarea.setSelectionRange(index, index + str.length);
		}
	}
	
	function toggle_layer(){
		if (ok_layer_display == '') {
			ok_layer_display = 'none';
			document.getElementById('layer_toggle_link').innerHTML = 'alle PostGIS-Layer anzeigen';
		}
		else {
			ok_layer_display = '';
			document.getElementById('layer_toggle_link').innerHTML = 'nur die anzupassenden PostGIS-Layer anzeigen';
		}
		var ok_layers = document.querySelectorAll('.layer_ok');
		[].forEach.call(ok_layers, function (ok_layer){
				ok_layer.style.display = ok_layer_display;
		});
	}
	
	function toggle_layer2(){
		if (nicht_zu_stelle_zugeordnet_layer_display == '') {
			nicht_zu_stelle_zugeordnet_layer_display = 'none';
			document.getElementById('nicht_zu_stelle_zugeordnet_layer_toggle_link').innerHTML = 'alle PostGIS-Layer anzeigen';
		}
		else {
			nicht_zu_stelle_zugeordnet_layer_display = '';
			document.getElementById('nicht_zu_stelle_zugeordnet_layer_toggle_link').innerHTML = 'nur die PostGIS-Layer mit Stellenzuordnung anzeigen';
		}
		var nicht_zu_stelle_zugeordnet_layers = document.querySelectorAll('.nicht_zu_stelle_zugeordnet');
		[].forEach.call(nicht_zu_stelle_zugeordnet_layers, function (nicht_zu_stelle_zugeordnet_layer){
				nicht_zu_stelle_zugeordnet_layer.style.display = nicht_zu_stelle_zugeordnet_layer_display;
		});
	}

</script>

<div id="main">

	<div id="tab_div">
		<table id="tab" class="scrolltable">
			<tbody style="max-height: 730px">
<?

$umlaute=array("Ä","Ö","Ü");

$oid_layer_count = 0;
$i = 0;
$layer_count = $this->database->result->num_rows;
while ($layer = $this->database->result->fetch_assoc()) {
	$class = '';
  $status = checkStatus($layer);
	$result = array();
	$result = get_oid_alternative($layer);
	if ($layer['used_layer_layer_id'] == '') {
		$class .= 'nicht_zu_stelle_zugeordnet ';
	}
	if ($status['oid'] AND $status['query'] AND $status['data']) {
		$class .= 'layer_ok ';
	}
	else{
		$oid_layer_count++;
	}
	echo '
		<tr class="' . $class . '">
			<td valign="top">';
				if($i == 0)echo '<div class="fett scrolltable_header">Layer</div>';
				if (!in_array(strtoupper(mb_substr($layer[$this->formvars['order']],0,1,'UTF-8')),$umlaute) AND strtoupper(mb_substr($layer[$this->formvars['order']],0,1,'UTF-8')) != $first) {
					$nav_bar .= "<a href='#".strtoupper(mb_substr($layer[$this->formvars['order']],0,1,'UTF-8'))."'><div class='menu abc'>".strtoupper(mb_substr($layer[$this->formvars['order']],0,1,'UTF-8'))."</div></a>";
					$first=strtoupper(mb_substr($layer[$this->formvars['order']],0,1));
					echo '<a class="nav_link menu abc" name="'.$first.'">'.$first.'</a>';
				}
	echo '<div style="float: right; margin-top: 0px;">
					' . $layer["Gruppenname"] . '
				</div>
				<div style="width: 200px; margin-top: 40px;">
					<input type="hidden" name="layer_id[]" value="' . $layer["Layer_ID"] . '">
					<input style="float: left" type="checkbox" name="check_' . $layer["Layer_ID"] . '" value="1">
					<div>&nbsp;<a href="index.php?go=Layereditor&selected_layer_id='.$layer["Layer_ID"].'&csrf_token=' . $_SESSION['csrf_token'] . '"target="_blank">'.$layer["Name"].'</a></div>
				</div>
			</td>
			<td style="background-color: '.$color[$status['oid']].'">
				' . ($i == 0 ? '<div class="fett scrolltable_header">ID-Spalte</div>' : '') . '
				' . $layer['oid'] . '
			</td>
			<td valign="top" style="background-color: '.$color[$status['query']].'">
				' . ($i == 0 ? '<div class="fett scrolltable_header">Query</div>' : '') . '
				<textarea onmouseenter="select_text(this, \'oid\');">' . $layer['pfad'] . '</textarea>
				' . ((!$status['query']) ? '<div class="replaced"><textarea name="new_query_' . $layer["Layer_ID"] . '" onmouseenter="select_text(this, \'oid\');">' . delete_oid_in_sql($layer['pfad']) . '</textarea></div>' : '') . '
			</td>
			<td valign="top" style="background-color: '.$color[$status['data']].'">
				' . ($i == 0 ? '<div class="fett scrolltable_header">Data</div>' : '') . '
				<textarea onmouseenter="select_text(this, \'oid\');">' . $layer['Data'] . '</textarea>
				' . ((!$status['data'] AND $result['oid_alternative']) ? '<div class="replaced"><textarea name="new_data_' . $layer["Layer_ID"] . '" onmouseenter="select_text(this, \'oid\');">' . replace_oid_in_data($layer['Data'], $result['oid_alternative']) . '</textarea></div>' : '') . '
			</td>
			<td>
				' . ($i == 0 ? '<div class="fett scrolltable_header">oid-Alternative</div>' : '') . '
				<input type="text" size="15" name="new_oid_' . $layer["Layer_ID"] . '" value="' . $result['oid_alternative'] . '">
			</td>			
			<td>
				' . ($i == 0 ? '<div class="fett scrolltable_header">Haupttabelle</div>' : '') . '
				<input type="text" size="15" name="maintable_' . $layer["Layer_ID"] . '" value="' . $layer['maintable'] . '">
			</td>
			<td>
				' . ($i == 0 ? '<div class="fett scrolltable_header">Fehlermeldung</div>' : '') . '
				' . $result['error'] . '
			</td>
		</tr>';
	$i++;
}
echo '</tbody></table></div>';

?>

	<div id="head">
		<table>
			<tr>
				<td>
					<? echo $layer_count; ?> PostGIS-Layer insgesamt<br>
					<? echo $oid_layer_count; ?> PostGIS-Layer müssen angepasst werden<br>
					<a href="javascript:void(0);" id="layer_toggle_link" onclick="toggle_layer();">nur die anzupassenden PostGIS-Layer anzeigen</a>
					<br>
					<a href="javascript:void(0);" id="nicht_zu_stelle_zugeordnet_layer_toggle_link" onclick="toggle_layer2();">nur die PostGIS-Layer mit Stellenzuordnung anzeigen</a>
					<br>
					Sortierung: 
					<select name="order" onchange="document.GUI.submit();">
						<option value="Name" <? if($this->formvars['order'] == 'Name')echo 'selected'; ?>>Name</option>
						<option value="Gruppenname" <? if($this->formvars['order'] == 'Gruppenname')echo 'selected'; ?>>Gruppe</option>
					</select>
					<div id="nav_bar"><? echo $nav_bar; ?></div>
				</td>
				<td valign="top">
					Für alle ausgewählten Layer:<br>
					<input type="button" onclick="document.GUI.action.value='set_new_oid';document.GUI.submit();" value="oid-Alternative als ID-Spalte übernehmen"><br>
					<input type="button" onclick="document.GUI.action.value='set_new_query';document.GUI.submit();" value="Query-Vorschlag übernehmen"><br>
					<input type="button" onclick="document.GUI.action.value='set_new_data';document.GUI.submit();" value="Data-Vorschlag übernehmen">
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="db_check">
	<h2>Datenbankobjekte die oids verwenden</h2>
<?
		$credentials = $this->pgdatabase->get_object_credentials();
		$sql = "
			SELECT 
				datname 
			FROM 
				pg_database
			WHERE 
				datistemplate = false AND 
				datname != 'postgres';
		";
		$ret1 = $this->pgdatabase->execSQL($sql);
		while($rs = pg_fetch_assoc($ret1[1])){		
			$database = new pgdatabase();
			$credentials['dbname'] = $rs['datname'];
			$database->set_object_credentials($credentials);
			echo '<h3>' . $credentials['dbname'] . ':</h3>';
			try {
				if ($database->open()) {
					foreach ($db_check_sqls as $db_check_sql) {
						$ret = @pg_query($database->dbConn, $db_check_sql['sql']);
						if($ret == false){
							echo @pg_last_error($database->dbConn);
						}
						else{
							if ($rs = pg_fetch_all($ret)) {
								echo '<h4>' . $db_check_sql['name'] . '</h4>';
								echo '<table>';
								foreach ($rs[0] as $key => $value) {
										echo "<th>" . $key . "</th>";
								}
								foreach ($rs as $row) {
									echo "<tr>";
									foreach ($row as $cell) {
										echo "<td>" . $cell . "</td>";
									} 
									echo "</tr>";
								} 
								echo "</table>";
							}
						}
					}
				}
				else {
					echo 'Verbindung nicht möglich';
				}
			}
			catch (Exception $e){
				echo 'Fehler: ' . $e->getMessage(), "\n";
			}
		}
?>
</div>


<input type="hidden" name="go" value="layer_check_oids">
<input type="hidden" name="action" value="">