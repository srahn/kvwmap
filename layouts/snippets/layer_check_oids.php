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
	$status['query'] = ((strpos($layer['pfad'], ' oid') !== false OR strpos($layer['pfad'], ',oid') !== false)? false : true);
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
		margin: 5px;
		border-collapse: collapse;
	}
	#main td{
		border: 1px solid #555;
		padding: 5px;
	}
	#main th{
		font-face: SourceSansPro3;
		border: 1px solid #555;
	}	
	#main textarea{
		height: 50px;
		width: 300px;
	}
</style>

<script type="text/javascript">

	function select_text(textarea, str){
		var index = textarea.value.indexOf(str);
		if(index > 0){
			textarea.focus();
			textarea.setSelectionRange(index, index + str.length);
		}
	}

</script>

<table id="main">
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
while($layer = $this->database->result->fetch_assoc()){
  $status = checkStatus($layer);
	$result = array();
	if (!$status['oid']) {
		$result = get_oid_alternative($layer);
	}
	if (!$status['oid'] AND !$status['query'] AND !$status['data'])	{
		echo '
			<tr>
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
				</td>
				<td style="background-color: '.$color[$status['data']].'">
					<textarea onmouseenter="select_text(this, \'oid\');">' . $layer['Data'] . '</textarea>
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
}
echo '</table>';

?>