<br><h2><?php echo $this->titel; ?></h2>

<?php if ($this->Fehlermeldung!='') {

include(LAYOUTPATH."snippets/Fehlermeldung.php");

}

?><p>

<table border="0" cellpadding="0" cellspacing="2">
  <tr>
    <td align="right"><span class="fett">Suche nach Kilometerquadrat:</span></td>
    <td><input name="kiloquad" type="text" value="<?php echo $this->formvars['kiloquad']; ?>" size="9" tabindex="1"></td>
  </tr>
  <tr>
    <td align="right"><span class="fett">Suche nach Punktkennzeichen:</span></td>
    <td><input name="pkn" type="text" value="<?php echo $this->formvars['pkn']; ?>" size="25" tabindex="2"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFCC"><em>Zur Pr&uuml;fung der Einmessungsskizzen
        gelangen Sie nur<br> 
      bei einer Suche nach einem vollst&auml;ndigen Kilometerquadrat.<br>
      Zur nicht exakten Suche im Punktkennzeichen
        geben Sie den<br> 
      Platzhalter % ein.
      z.B. erhalten Sie alle AP&acute;s 
      aus Kilometerquadrat<br> 
      45601234 mit der
    Eingabe 45601234-1-%</em></td>
  </tr>

  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2" align="center"> 
<input type="hidden" name="go" value="Festpunkte_Auswaehlen">

<input type="submit" name="go_plus" value="Suchen" tabindex="3">&nbsp;<input type="submit" name="go_plus" value="Abbrechen">&nbsp;<input type="reset" name="reset" value="Zur&uuml;cksetzen">
   </td>

  </tr>
</table>

