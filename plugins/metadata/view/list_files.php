<style>
  .file-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
    /* height: 300px; Predefined height */
    overflow-y: auto; /* Scroll if content overflows */
    padding: 10px;
    border: 1px solid #ccc;
  }

  .dir-item {
    font-size: larger;
    color: darkblue;
    line-height: 1.2;
  }

  .file-item {
    background: #f0f0f0;
    padding: 10px;
    border-radius: 5px;
    text-align: left;
  }
</style>
<h2 style="margin: 10px;">Dateien unter <? echo $this->search_dir; ?></h2>
<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo METADATA_RESSOURCES_LAYER_ID; ?>&value_id=<?php echo $this->formvars['ressource_id']; ?>&operator_id==&csrf_token=<? echo $_SESSION['csrf_token']; ?>">zurück zur Ressource</a>
<?php
	if ($this->Fehlermeldung != '') {
    include(LAYOUTPATH . "snippets/Fehlermeldung.php");
  }
?>
<div class="file-container" style="margin: 25px; text-align: left"><?php
  $last_dir_name = '';
  foreach ($this->files AS $file) {
    $path_parts = pathinfo($file);
    if ($path_parts['dirname'] != $last_dir_name) {
      $last_dir_name = $path_parts['dirname'] ?>
      <div class="dir-item"><? echo str_replace($this->search_dir, '', $path_parts['dirname']) . '/'; ?></div><?
    } ?>
    <div class="file-item"><? echo $path_parts['basename']; ?></div><?
	} ?>
</div>