<?php
echo '<select id="distinctShpWert">';
// Insert a newoption for each attribute returned
while($row = pg_fetch_row($this->result)){
	echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
}
'</select>';
?>