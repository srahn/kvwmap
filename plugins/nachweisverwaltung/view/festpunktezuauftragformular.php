<div align="center"><h2><br>
  <?php echo $this->titel; ?><br>
</h2><br>
<?php
if ($this->Fehlermeldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
 ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" valign="middle">
	     <input type="hidden" name="pkn[<?php echo $this->pkn[0]; ?>]" value="0"><?php
		 echo $this->pkn[0];
	  	 for($i=1;$i<$this->anzPunkte;$i++) {
		    ?><input type="hidden" name="pkn[<?php echo $this->pkn[$i]; ?>]" value="0"><?php
			echo ', '.$this->pkn[$i];
		 }
		 ?><br>
      <br>
      Auftrag, zu dem die Festpunkte hinzugef&uuml;gt
      werden sollen: <?php $this->FormObjAntr_nr->outputHTML();
      echo $this->FormObjAntr_nr->html; ?>
      <br>
      <br>
      <input type="submit" name="go_plus" value="Senden">
      <br>
      <input type="hidden" name="go" value="Festpunkte zum Antrag Hinzufügen">

</td>
  </tr>
</table></div>

  