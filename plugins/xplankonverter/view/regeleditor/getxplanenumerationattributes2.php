<?php

//Wenn [1] oder [1]
if($arrayEnum == true){
	echo '<select id="wenn_dann_enumeration">';
// Wenn [0..*] oder [1..*]
} else {
	echo '<select id="wenn_dann_enumeration">';
}
echo '<option value="">Enumerationswert waehlen...</option>';
// Insert a newoption for each attribute returned
while ($row = pg_fetch_row($this->result)){
	echo '<option value="' . $enumerationsliste . '_' . $row[0] . '">' . $row[0] . ' ( ' . $row[1] . ' )</option>';
}
echo '</select>';
// Wenn [0..*] oder [1..*]
// Hier war beim Original noch EnumerationAddArray
?>