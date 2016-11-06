<?PHP
# DB_Conn
include('config.php');
$conn = pg_connect('dbname=' . DBNAME . ' user=' . DBUSER .  ' password=' . DBPASS);
if (!$conn){echo "Verbindung mit der Datenbank konnte nicht hergestellt werden.\n"; exit;}
# Variablen, die aus Konvertierung übergeben werden sollten:
#$konvertierung_id = $_REQUEST['konvertierung_id'];
$konvertierung_id = $_GET['konvertierung_id'];
  
// Retrieve data from Query String
$shapefile = $_GET['shapefile'];
$shapefile_attribut = $_GET['shapefile_attribut'];

//build query
$sql = "
  SELECT
    DISTINCT 
  " . $shapefile_attribut . "
  FROM
    xplan_shapes_" . $konvertierung_id . "." .  $shapefile . "
  ORDER BY
    ". $shapefile_attribut ."
  ";
//Execute query
// sets für alle aus SHP
$result = pg_query($conn, $sql);

echo '<select id="distinctShpWert">';
// Insert a newoption for each attribute returned
while($row = pg_fetch_row($result)){
    echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
}
'</select>';
?>