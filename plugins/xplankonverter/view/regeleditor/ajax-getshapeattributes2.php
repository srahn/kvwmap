<?PHP
# DB_Conn
$conn = pg_connect('dbname=kvwmapsp user=pgadmin password=PicaPica_Elster2');
if (!$conn){echo "Verbindung mit der Datenbank konnte nicht hergestellt werden.\n"; exit;}
# Variablen, die aus Konvertierung übergeben werden sollten:
$konvertierung_id = $_GET['konvertierung_id'];

// Retrieve data from Query String
$shapefile = $_GET['shapefile'];
	
//build query
$sql = "
  SELECT
    column_name
  FROM
    information_schema.columns
  WHERE
    table_name = '" . $shapefile . "'
  AND
    table_schema = 'xplan_shapes_" . $konvertierung_id . "'
  ORDER BY
    column_name
  ";

//Execute query

// sets für alle aus SHP
$result = pg_query($conn, $sql);
echo '<select id="alle_aus_shape_attribut_attribut_select" style="display: none" onChange="alleAusShapeAttributEintragen()">';
echo '<option value="default_shape_attribut_select">Shape-File Attribut waehlen ...</option>';

// Insert a newoption for each attribute returned
while($row = pg_fetch_row($result)){
    echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
}

echo '<div id="set_shape_attributes"></div>';
echo '</select>';

// sets für wenn dann SHP
$result = pg_query($conn, $sql);
echo '<select id="wenn_dann_shape_attribut_attribut_selector" style="display: none" onChange="getShapeAttributeDistinct()">';
echo '<option value="default_shape_attribut_select">Shape-File Attribut waehlen ...</option>';

// Insert a newoption for each attribute returned
while($row = pg_fetch_row($result)){
    echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
}

echo '<div id="set_shape_attributes"></div>';
echo '</select>';
?>