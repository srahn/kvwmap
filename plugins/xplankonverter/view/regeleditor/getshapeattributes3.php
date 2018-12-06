<?PHP
// sets für Where-Filter für Shape SHP
echo '<select id="where_shape_attribut_attribut_selector" onChange="getShapeAttributeDistinct2()">';
echo '<option value="where_default_shape_attribut_select">Shape-File Attribut waehlen ...</option>';

// Insert a newoption for each attribute returned
while($row = pg_fetch_row($this->result)){
	echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
}

echo '<div id="where_set_shape_attributes"></div>';
echo '</select>';
?>