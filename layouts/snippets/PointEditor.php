
<script language="JavaScript">
<!--

function send(){
	if(document.GUI.loc_x.value == ''){
		alert('Geben Sie einen Punkt an.');
	}
	else{
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
	}
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

<table width="760" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" colspan="3"><strong><font size="+1"><a name="geoedit_anchor"><?php echo $this->titel; ?></a></font></strong></td>
  </tr>
  <tr> 
    <td rowspan="2">&nbsp;</td>
    <td colspan="2" rowspan="2"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_point.php')
			?>
    </td>
  </tr>
  <? if($this->new_entry != true){ ?>
  <tr> 
    <td align="center"> <input type="button" name="senden" value="Senden" onclick="send();"> </td>
  </tr>
  <? }else{ ?>
  <tr>
  	<td></td>
  </tr>
  <? } ?>
  <tr>
  	<td>&nbsp;</td>
  	<td>&nbsp;<b>Maßstab&nbsp;1:&nbsp;</b><input type="text" id="scale" name="nScale" size="5" value="<?php echo round($this->map_scaledenom); ?>"></td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="dimension" VALUE="<?php echo $this->formvars['dimension']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="<?php echo $this->formvars['layer_id']; ?>">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="tablename" VALUE="<?php echo $this->formvars['tablename']; ?>">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<?php echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>"> 
<? if($this->formvars['go'] == 'PointEditor'){ ?>   
	<INPUT TYPE="HIDDEN" NAME="go" VALUE="PointEditor" >
<? } ?>
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	
