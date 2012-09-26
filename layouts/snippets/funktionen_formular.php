<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript">
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1">Funktionen-Editor</font></strong></td>
  </tr>
  <tr>
    <td align="center"><?php
if ($this->Meldung=='Daten der Funktion erfolgreich eingetragen!' OR $this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $this->Fehlermeldung=$this->Meldung;
  include('Fehlermeldung.php');
  $bgcolor=BG_FORMFAIL;
}
 ?>      
 <table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #C3C7C3">
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3">Datenbank-ID</th>
    <td style="border-bottom:1px solid #C3C7C3">
    	<input name="id" type="text" value="<?php echo $this->formvars['selected_function_id']; ?>" size="25" maxlength="11">
    </td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3">Bezeichnung</th>
    <td style="border-bottom:1px solid #C3C7C3">
      <input name="bezeichnung" type="text" value="<?php echo $this->formvars['bezeichnung']; ?>" size="25" maxlength="100">
  	</td>
  </tr>
</table>
</td>
  </tr>
  <tr>
  	<td align="center">
    <input type="hidden" name="go_plus" id="go_plus" value=""><?php
      if ($this->formvars['selected_function_id']>0) { ?>
    	<input type="reset" name="reset1" value="Zurücksetzen">&nbsp;
    	<input type="hidden" name="selected_function_id" value="<?php echo $this->formvars['selected_function_id']; ?>">
      <input type="button" name="dummy" value="Ändern" onclick="submitWithValue('GUI','go_plus','Ändern')">&nbsp;<?php
      }
      else {
      	?><input type="button" value="Zurücksetzen" onclick="document.location.href='index.php?go=Benutzerdaten_Formular'">&nbsp;<?
      } 
      ?><input type="button" name="dummy" value="Als neue Funktion eintragen" onclick="submitWithValue('GUI','go_plus','Als neue Funktion eintragen')">
	  </td>
  </tr>
</table>
<input type="hidden" name="go" value="Funktionen">
<input type="hidden" name="selected_function_id" value="<? echo $this->formvars['selected_function_id']; ?>">
      
