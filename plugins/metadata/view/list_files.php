<h2 style="margin: 10px;">Dateien</h2>
Verzeichnis: <? echo $this->formvars['dir']; ?><p>
<?php
	if ($this->Fehlermeldung != '') {
    include(LAYOUTPATH . "snippets/Fehlermeldung.php");
  }
?>
<ul><?php
  foreach ($files AS $file) { ?>
  	<li><? echo $file; ?></li><?
	} ?>
</ul>