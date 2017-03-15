<?php
//Wenn [1] oder [1]
if($arrayEnum == true){
	echo '<select id="fester_wert_enumeration">';
// Wenn [0..*] oder [1..*]
} else {
	echo '<select id="fester_wert_enumeration" onChange="festenWertEintragenEnumeration()">';
}
echo '<option value="">Enumerationswert waehlen...</option>';
// Insert a newoption for each attribute returned
while ($row = pg_fetch_row($result)){
	echo '<option value="' . $enumerationsliste . '_' . $row[0] . '">' . $row[0] . ' ( ' . $row[1] . ' )</option>';
}
echo '</select>';
// Wenn [0..*] oder [1..*]
if($arrayEnum == true){
	echo '<button id="fester_wert_enumeration_array_add" onClick="festenWertEintragenEnumerationArray(this.id)" title="Wert hinzufügen"><i class="fa fa-plus" aria-hidden="true"></i></button>';
	echo '<button id="fester_wert_enumeration_add" onClick="festenWertEintragenEnumerationArray(this.id)" title="Wert hinzufügen"><i class="fa fa-level-up" aria-hidden="true"></i></button>';
}
?>