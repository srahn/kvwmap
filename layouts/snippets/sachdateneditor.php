<?php
	if ($this->Meldung=='') {
	  $bgcolor=BG_FORM;
	}
	else {
	  $bgcolor=BG_FORMFAIL;
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
}
 ?>
  <table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="2"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr><?php
  # attrib ...  The list with the attribute Valuepairs
  #				Die Liste mit Attribut-Wertepaaren
  $numAttrib=count($attrib);
  for($i=0;$i<numAttrib;$i++) {
  ?>
  <tr align="center">
    <td><?php echo $attrib[$i]['name']; ?></td>
    <td><?php echo $attrib[$i]['formobj']->html(); ?></td>
  </tr>
  <tr align="center">
    <td colspan="2"><input type="reset" name="reset" value="Zurücksetzen">&nbsp;<input type="submit" name="go_plus" value="Senden"></td>
    </tr>
  <?php
  }
  ?>
  </table>