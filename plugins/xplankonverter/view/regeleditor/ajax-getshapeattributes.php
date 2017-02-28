<?php
echo '<select id="alle_aus_shape_attribut_attribut_selector" style="display:none" onChange="alleAusShapeAttributEintragen()">';
echo '<option value="default_shape_attribut_select">Shape-File Attribut waehlen ...</option>';
// Insert a newoption for each attribute returned
while($row = pg_fetch_row($result)){
  echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
}
echo '</select>';
?>