<h2>Flurstücksrechte im Vorhabensgebiet: <?php echo htmlspecialchars($this->vorhabensgebiet->get('bezeichnung')); ?> (id: <?php echo $this->vorhabensgebiet->get_id(); ?>)</h2><?
if ($this->Fehlermeldung != '') {
	include(SNIPPETS . 'Fehlermeldung.php');
}
else { ?>
  Auflistung der Flurstücksrechte im Vorhabensgebiet sortiert nach Eigentümer, Flurstück und Rechteart:<br><br><?php
  foreach ($this->flurstuecksrechte as $fr) {
    echo '<p>' . print_r($fr->data, true);
  } 
}?>