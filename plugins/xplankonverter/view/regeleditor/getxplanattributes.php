<?php
?>
<div id="<?php echo $this->formvars['featuretype']; ?>_attributes_table">
<table border="1" cellspacing="0" cellpadding="2" style="width:100%; margin-top: 6px">
<tr>
	<th style="background-color: <?php echo BG_DEFAULT; ?>"><b>Attributname</b></th>
	<th style="background-color: <?php echo BG_DEFAULT; ?>"><b>Datentyp</b></th>
	<th style="background-color: <?php echo BG_DEFAULT; ?>"><b>Multiplizität</b></th>
	<th style="background-color: <?php echo BG_DEFAULT; ?>">&nbsp;</th>
</tr><?php

// Insert a new row for each attribute
while ($row = pg_fetch_row($this->result)){
	// entnimmt Metadaten u. Assoziationen
	if ($row[0]!='gml_id' 
		and $row[0]!='stellen_id'
		and $row[0]!='user_id'
		and $row[0]!='konvertierung_id'
		and $row[0]!='created_at'
		and $row[0]!='changed_at'
		and $row[0]!='updated_at'
		// Assoziationen bereich, präsentationsobjekte, textabschnitt, begründungsabschnitt
		and $row[0]!='gehoertzubereich'
		and $row[0]!='wirddargestelltdurch'
		and $row[0]!='reftextinhalt'
		and $row[0]!='refbegruendunginhalt'
	)
	{
		if($row[2] == 'YES'){$row[2] = 'Nein';}
		if($row[2] == 'NO'){$row[2] = 'Ja';}
		echo '<tr><td>';
		if($row[0] == 'rechtscharakter' || $row[0] == 'position') {echo '<b>';}
		if($row[1] == '_rp_zentralerorttypen' && $row[0] == 'typ') {echo '<b>';}
		echo $row[0];
		if($row[0] == 'rechtscharakter' || $row[0] == 'position') {echo '*</b>';}
		if($row[1] == '_rp_zentralerorttypen' && $row[0] == 'typ') {echo '*</b>';}
		echo '</td><td><span id="wertedefinition_' . $row[0] . '">'; 
		#wenn es mit _ startet, ist es ein array, dann das _ entfernen
		$trimmedDefinition = ltrim($row[1], '_');
		//varchar wird in text umgewandelt
		if(($trimmedDefinition == 'varchar') || ($trimmedDefinition == 'text')) {
			echo 'text';
		} elseif ($trimmedDefinition == 'int4') {
			echo 'integer';
		} elseif ($trimmedDefinition == 'bool'){
			echo 'boolean';
		} else {
			echo $trimmedDefinition;
		}
		echo '</span></td><td><span id="wertespanne_' . $row[0] . '">';
		// Falls Pflichtattribute ZentralerOrtTypen u. Rechtscharakter
		if($row[3] == 'NO' || $row[1] == '_rp_zentralerorttypen' || $row[1] == 'rp_rechtscharakter' || $row[1] == 'xp_variablegeometrie') {
			echo '1';
		} else {echo '0';}
		echo '..';
		if($row[2] == 'ARRAY') {
			echo '*';
		} else {echo '1';}
		echo '</span></td><td>';
		#Gibt keine Add/Remove Buttons für Position aus
		if($row[0] != "position"){
			echo '<button id="add_' . $row[0] . '" title="Wert hinzufuegen" onclick="addAttribut(this.id)"><i class="fa fa-plus fa-lg"></i></button>';
			echo '<button id="remove_' . $row[0] . '" title="Wert leeren" onclick="removeAttribut(this.id)" style="display:none"><i class="fa fa-trash-o fa-lg"></i></button>';
		}
		echo '</td></tr>';
	}
}

echo '</table>';
echo '</div>';

?>