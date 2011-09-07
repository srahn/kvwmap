
<script language="JavaScript">
<!--

function send(){
	document.GUI.go_plus.value = "speichern";
	document.GUI.submit();
}

//-->
</script>

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
  <tr> 
    <td> <div align="center"></div></td>
    <td colspan="3"><div align="center"><strong><font size="+1"><?php echo $this->titel; ?></font></strong> 
      </div></td>
  </tr>
  <tr> 
    <td rowspan="10">&nbsp;</td>
    <td colspan="2" rowspan="10"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_line_query.php')
			?>
    </td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr>
  	<td>Gemeinde:<br><? echo $this->FormObject["Gemeinden"]->html; ?></td>
  </tr>
  <tr>
  	<td>Strasse:<br><? echo $this->FormObject["Strassen"]->html; ?></td>
  </tr>
  <tr>
  	<td>Hausnummer:<br><input type="text" value="<? echo $this->formvars['nummer'] ?>" name="nummer"></td>
  </tr>
  <tr>
  	<td>Zusatz:<br><input type="text" value="<? echo $this->formvars['zusatz'] ?>" name="zusatz"></td>
  </tr>
  <tr>
  	<td>Kommentar:<br><input type="text" value="<? echo $this->formvars['kommentar'] ?>" name="kommentar"></td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td align="center"> <input type="button" name="senden" value="Senden" onclick="send();"> </td>
  </tr>
</table>

<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<? echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="HIDDEN" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>"> 
<INPUT TYPE="HIDDEN" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>"> 
<INPUT TYPE="HIDDEN" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>"> 
<INPUT TYPE="HIDDEN" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>"> 
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>">    
<INPUT TYPE="HIDDEN" NAME="go" VALUE="gebaeude_editor" >
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	