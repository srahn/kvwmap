<?PHP
# DB_Conn
include('config.php');
$conn = pg_connect('dbname=' . DBNAME . ' user=' . DBUSER .  ' password=' . DBPASS);
if (!$conn){echo "Verbindung mit der Datenbank konnte nicht hergestellt werden.\n"; exit;}

// Retrieve data from Query String
$featuretype = $_GET['featuretype'];

//build query
$sql = "
  SELECT
    column_name, udt_name, data_type, is_nullable
  FROM
    information_schema.columns
  WHERE
    table_name='" . $featuretype . "'
  AND
    table_schema='xplan_classes'
  ORDER BY
    column_name
  ";

//Execute query
$result = pg_query($conn, $sql);
echo '<div id="'.  $featuretype . '_attributes_table">';
echo '<table border="1" style="width:100%">';
echo '<tr>';
echo '<td><b>Attribut</b></td>';
echo '<td><b>Wertedefinition</b></td>';
echo '<td><b>Auspraegung</b></td>';
echo '</tr>';

// Insert a new row for each attribute
while ($row = pg_fetch_row($result)){
  // entnimmt Metadaten u. Assoziationen
  if ($row[0]!='gml_id' 
  and $row[0]!='stellen_id'
  and $row[0]!='user_id'
  and $row[0]!='konvertierung_id'
  and $row[0]!='created_at'
  and $row[0]!='changed_at'
  and $row[0]!='updated_at'
  // Assoziationen bereich, präsentationsobjekte, textabschnitt, begründungsabschnitt
  and $row[0]!='gehoertnachrichtlichzubereich'
  and $row[0]!='gehoertzurp_bereich'
  and $row[0]!='wirddargestelltdurch'
  and $row[0]!='reftextinhalt'
  and $row[0]!='refbegruendunginhalt'
  )
  {
    if($row[2] == 'YES'){$row[2] = 'Nein';}
    if($row[2] == 'NO'){$row[2] = 'Ja';}
    echo '<tr><td>';
    echo $row[0];
    echo '</td><td><span id="wertedefinition_' . $row[0] . '">'; 
    #wenn es mit _ startet, ist es ein array, dann das _ entfernen
    $trimmedDefinition = ltrim($row[1], '_');
    echo $trimmedDefinition;
    echo '</span></td><td><span id="wertespanne_' . $row[0] . '">[';
    if($row[3] == 'NO') {  
      echo '1';
    } else {echo '0';}
    echo '..';
    if($row[2] == 'ARRAY') {  
      echo '*';
    } else {echo '1';}
    echo ']';
    echo '</span></td><td>';
    #Gibt keine Add/Remove Buttons für Position aus
    if($row[0] != "position"){
      echo '<button id="add_' . $row[0] . '" title="Wert hinzufuegen" onclick="addAttribut(this.id)"><i class="fa fa-plus"></i></button>';
      echo '<button  id="remove_' . $row[0] . '" title="Wert leeren" onclick="removeAttribut(this.id)" style="display:none"><i class="fa fa-trash-o"></i></button>';
    }
    echo '</td></tr>';
  }
}

echo '</table>';
echo '</div>';

?>