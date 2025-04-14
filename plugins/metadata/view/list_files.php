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
    }
    else {
      ?><span class="file"><? echo $path_parts['basename']; ?></span><?
    }
	} ?>
</div>