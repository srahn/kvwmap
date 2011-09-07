
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo BG_FORM ?>">
  <tr> 
    <td> <div align="center"></div></td>
    <td colspan="4"><div align="center"><strong><font size="+1"><?php echo $this->titel; ?></font></strong> </div></td>
  </tr>
  <tr> 
    <td rowspan="5">&nbsp;</td>
    <td colspan="2" rowspan="5"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_geothermieeingabeformular.php')
			?>
    </td>
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td colspan="2"><strong>Ergebnis:</strong> 
    	<p>vorgegebene Entzugsenergie: <strong><?php echo $this->formvars['entzugsenergie'] /* echo $_REQUEST["entzugsenergie"] */ ?> kWh/a</strong>,</p>
      <p> erforderliche Anzahl Erdw&auml;rmesonden (EWS): <strong>3</strong>,</p>
      <p> durchschnittliche Entzugsleistung: <strong>15 W/m</strong></p></td>
  </tr>
  <tr> 
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td colspan="2">Verschieben Sie die Ellipsen <font size="-2">(EWS und Temperaturabsenkungstrichter)</font> 
      <br>
      und best&auml;tigen Sie die Lage durch klicken des 'Senden'-Buttons!</td>
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
    	<INPUT TYPE="HIDDEN" NAME="go" VALUE="Geothermie_Abfrage" >
