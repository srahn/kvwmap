<?PHP
// sets für alle aus SHP
echo '<select id="alle_aus_shape_attribut_attribut_select" style="display: none" onChange="alleAusShapeAttributEintragen()">';
echo '<option value="default_shape_attribut_select">Shape-File Attribut waehlen ...</option>';

// Insert a newoption for each attribute returned
while($row = pg_fetch_row($this->result)){
	echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
}

echo '<div id="set_shape_attributes"></div>';
echo '</select>';

// sets für wenn dann SHP
$result = pg_query($this->pgdatabase->dbConn, $sql);
echo '<select id="wenn_dann_shape_attribut_attribut_selector" style="display: none" onChange="getShapeAttributeDistinct()">';
echo '<option value="default_shape_attribut_select">Shape-File Attribut waehlen ...</option>';

// Insert a newoption for each attribute returned
while($row = pg_fetch_row($result)){
	echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
}

echo '<div id="set_shape_attributes"></div>';
echo '</select>';
?>