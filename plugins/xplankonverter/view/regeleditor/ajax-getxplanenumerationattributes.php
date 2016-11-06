<?PHP
# DB_Conn
include('config.php');
$conn = pg_connect('dbname=' . DBNAME . ' user=' . DBUSER .  ' password=' . DBPASS);
if (!$conn){echo "Verbindung mit der Datenbank konnte nicht hergestellt werden.\n"; exit;}
# Variablen, die aus Konvertierung übergeben werden sollten:
global $konvertierung_id;
  
// Retrieve data from Query String
$featuretype = $_GET['featuretype'];
$featureattribut = $_GET['xplanattribut'];
	
//build query
$sql = "
  SELECT
    udt_name
  FROM
    information_schema.columns
  WHERE
    table_name='" . $featuretype . "'
  AND
    column_name = '" . $featureattribut . "'
  AND
    table_schema='xplan_classes'
  ORDER BY
    column_name
  ";
  
//Execute query
$result = pg_query($conn, $sql);
while($row = pg_fetch_row($result)) {
  $enumerationsliste = $row[0];
}
# wenn es mit _startet ist es ein array, dann das _ entfernen
$arrayEnum = bool;
if(substr($enumerationsliste, 0, 1) === '_') {
  $arrayEnum = true;
} else {
  $arrayEnum = false;
}
$enumerationsliste = ltrim($enumerationsliste, '_');

$sql = "
  SELECT
    wert,
    beschreibung
  FROM
    xplan_classes. " . $enumerationsliste . "
  ";
$result = pg_query($conn, $sql);
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