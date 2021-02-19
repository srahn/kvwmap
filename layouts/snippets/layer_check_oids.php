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

function checkStatus($layer){
	$status['oid'] = ($layer['oid'] == 'oid' ? false : true);
	$status['query'] = ((strpos($layer['pfad'], ' oid') !== false OR strpos($layer['pfad'], ',oid') !== false OR strpos($layer['pfad'], '.oid') !== false)? false : true);
	$status['data'] = (strpos($layer['Data'], 'oid') !== false ? false : true);
	return $status;
}

function get_oid_alternative($layer){
	global $GUI;
	if ($layer['maintable'] == ''){
		$result['error'] = 'Haupttabelle ist nicht gesetzt.';
	}
	else {
		$sql = "
			SELECT 
				a.attname as pk
			FROM 
				pg_attribute a 
				LEFT JOIN pg_index i ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey) AND i.indnatts = 1
			WHERE  
				a.attrelid = '" . ($layer['schema'] ?: 'public'). "." . $layer['maintable'] . "'::regclass and 
				attnum > 0 and 
				attisdropped is false and 
				(pg_get_serial_sequence('" . ($layer['schema'] ?: 'public'). "." . $layer['maintable'] . "', attname) IS NOT NULL OR i.indisunique)
		";
		$ret = @pg_query($GUI->pgdatabase->dbConn, $sql);
		if($ret == false){
			$result['error'] = pg_last_error($GUI->pgdatabase->dbConn);
		}
		else{
			$rs=pg_fetch_assoc($ret);
			$result['oid_alternative'] = $rs['pk'];
		}
	}
	return $result;
}

function delete_oid_in_sql($query){
	$query = str_replace([' as oid', ' AS oid', ' oid,', ',oid', ', oid'], ['', '', ' '], $query);
	if (strpos($query, '.oid') !== false) {
		$query = preg_replace('/\w+\.oid,/', '', $query);		#	tablename.oid,
	}
	return $query;
}

function replace_oid_in_data($data, $id){
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

$color[false] = '#db5a5a';
$color[true] = '#36908a';

$query = "SELECT * FROM `layer` WHERE connectiontype = 6 ORDER BY name";

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
		padding-top: 80px
	}
	#head{
		position: absolute;
		top: 10px;
		margin-left: 40%;
		line-height: 23px;
	}
	#tab{
		margin: 5px;
		border-collapse: collapse;
	}
	#tab td{
		border: 1px solid #555;
		padding: 5px;
	}
	#tab th{
		font-face: SourceSansPro3;
		border: 1px solid #555;
	}	
	#tab textarea{
		height: 100px;
		width: 350px;
	}
	#tab .replaced{
		padding: 5px;
		background: yellow;
	}
</style>

<script type="text/javascript">

	var ok_layer_display = '';

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

</script>

<div id="main">

	<table id="tab">
		<tr>
			<th>
				Layer
			</th>
			<th>
				ID-Spalte
			</th>
			<th>
				Query
			</th>
			<th>
				Data
			</th>
			<th>
				oid-Alternative
			</th>		
			<th>
				Haupttabelle
			</th>
			<th>
				Fehlermeldung
			</th>
		</tr>

<?
$oid_layer_count = 0;
while($layer = $this->database->result->fetch_assoc()){
  $status = checkStatus($layer);
	$result = array();
	if (!$status['oid']) {
		$result = get_oid_alternative($layer);
	}
	if ($status['oid'] AND $status['query'] AND $status['data']) {
		$class = 'layer_ok';
	}
	else{
		$class = '';
		$oid_layer_count++;
	}
	echo '
		<tr class="' . $class . '">
			<td>
				<div style="width: 200px; overflow: auto;">
					<a href="index.php?go=Layereditor&selected_layer_id='.$layer["Layer_ID"].'"target="_blank">'.$layer["Name"].'</a>
				</div>
			</td>
			<td style="background-color: '.$color[$status['oid']].'">
				' . $layer['oid'] . '
			</td>
			<td style="background-color: '.$color[$status['query']].'">
				<textarea onmouseenter="select_text(this, \'oid\');">' . $layer['pfad'] . '</textarea>
				' . ((!$status['query'] AND $result['oid_alternative']) ? '<div class="replaced"><textarea onmouseenter="select_text(this, \'oid\');">' . delete_oid_in_sql($layer['pfad']) . '</textarea></div>' : '') . '
			</td>
			<td style="background-color: '.$color[$status['data']].'">
				<textarea onmouseenter="select_text(this, \'oid\');">' . $layer['Data'] . '</textarea>
				' . ((!$status['data'] AND $result['oid_alternative']) ? '<div class="replaced"><textarea onmouseenter="select_text(this, \'oid\');">' . replace_oid_in_data($layer['Data'], $result['oid_alternative']) . '</textarea></div>' : '') . '
			</td>
			<td>
				' . $result['oid_alternative'] . '
			</td>			
			<td>
				' . $layer['maintable'] . '
			</td>
			<td>
				<div style="width: 250px; overflow: hidden">' . $result['error'] . '</div>
			</td>
		</tr>';
}
echo '</table>';

?>

	<div id="head">
		<? echo $this->database->result->num_rows; ?> PostGIS-Layer insgesamt<br>
		<? echo $oid_layer_count; ?> PostGIS-Layer müssen angepasst werden<br>
		<a href="javascript:void(0);" id="layer_toggle_link" onclick="toggle_layer();">nur die anzupassenden PostGIS-Layer anzeigen</a>
	</div>
</div>