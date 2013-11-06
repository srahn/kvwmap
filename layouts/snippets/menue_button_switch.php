<?php
  $this->menuebodyfile = 'menue_button.php';
  include(LAYOUTPATH.'snippets/menue_head.php');
  ?><div id="menueTable"><?php
  if (!$this->user->rolle->hideMenue) {
  	include(LAYOUTPATH.'snippets/'.$this->menuebodyfile);
  }
  ?></div><?php
?>