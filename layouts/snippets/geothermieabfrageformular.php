<?php
if ($this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}
 ?>

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td> <div align="center"></div></td>
    <td colspan="4"><div align="center"><strong><font size="+1"><?php echo $this->titel; ?></font></strong> 
      </div></td>
  </tr>
  <tr> 
    <td rowspan="5">&nbsp;</td>
    <td colspan="2" rowspan="5"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_geothermieabfrageformular.php')
			?>
    </td>
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td colspan="2"><p><strong>Schritt 1: </strong></p>
      <p>Klicken Sie auf der Karte in das betreffende Flurst&uuml;ck!</p>
      </td>
  </tr>
  <tr> 
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td colspan="2"><p><strong>Schritt 2:</strong></p>
      <p>Angabe der gew&uuml;nschten Entzugsenergie [kWh/a]:</p>
      <p> 
        <input type="text" name="entzugsenergie" value="" size="6" maxlength="6">
        kWh/a</p></td>
  </tr>
  <tr> 
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td colspan="3"> <div align="left"> </div></td>
    <td align="left"> <input type="reset" name="go_plus" value="Zurücksetzen"> 
    </td>
    <td align="left"><input type="submit" name="go_plus2" value="Senden"></td>
  </tr>
</table>
      <INPUT TYPE="HIDDEN" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>"> 
      <INPUT TYPE="HIDDEN" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>"> 
      <INPUT TYPE="HIDDEN" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>"> 
      <INPUT TYPE="HIDDEN" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>"> 
      <INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>">    
    	<INPUT TYPE="HIDDEN" NAME="go" VALUE="Geothermie_Eingabe" >