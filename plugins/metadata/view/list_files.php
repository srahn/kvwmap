<style>
  .dir {
    font-size: larger;
    color: darkblue;
    line-height: 1.2;
  }

  .file {
    margin-left: 20px;
  }
</style>
<h2 style="margin: 10px;">Dateien unter <? echo $this->search_dir; ?></h2>
<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo METADATA_RESSOURCES_LAYER_ID; ?>&value_id=<?php echo $this->formvars['ressource_id']; ?>&operator_id==&csrf_token=<? echo $_SESSION['csrf_token']; ?>">zurück zur Ressource</a>
<?php
	if ($this->Fehlermeldung != '') {
    include(LAYOUTPATH . "snippets/Fehlermeldung.php");
  }
?>
<div style="margin-left: 25px; text-align: left"><?php
  $last_dir_name = '';
  foreach ($this->files AS $file) {
    $path_parts = pathinfo($file);
    echo '<br>';
    if ($path_parts['dirname'] != $last_dir_name) {
      $last_dir_name = $path_parts['dirname'];
      ?><span class="dir"><? echo str_replace($this->search_dir, '', $path_parts['dirname']) . '/'; ?></span><?
    } ?>
    <br><span class="file"><? echo $path_parts['basename']; ?></span><?
	} ?>
</div>