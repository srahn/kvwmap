
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

<table style="border:1px solid gray;" width="760" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" colspan="5"><strong><font size="+1"><a name="geoedit_anchor"><?php echo $this->titel; ?></a></font></strong></td>
  </tr>
  <tr> 
    <td rowspan="2">&nbsp;</td>
    <td colspan="4" rowspan="2"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_point.php')
			?>
    </td>
  </tr>
  <? if($this->new_entry != true){ ?>
  <tr> 
    <td align="center">
    	<input type="button" name="senden" value="Speichern" onclick="send();"><br><br>
    	<a href="index.php?go=Layer-Suche&go_plus=Suchen&selected_layer_id=<?php echo $this->formvars['layer_id']; ?>&value_<?php echo $this->formvars['layer_tablename']; ?>_oid=<?php echo $this->formvars['oid']; ?>">Sachdatenanzeige</a>
    </td>
  </tr>
  <? }else{ ?>
  <tr>
  	<td></td>
  </tr>
  <? } ?>
  <tr>
  	<td>&nbsp;</td>
  	<td>&nbsp;<b>Maßstab&nbsp;1:&nbsp;</b><input type="text" id="scale" name="nScale" size="5" value="<?php echo round($this->map_scaledenom); ?>"></td>
	<? if($this->user->rolle->runningcoords != '0'){ ?>
	<td><b>&nbsp;<?php echo $this->strCoordinates; ?>:</b>&nbsp;</td>
	<td><input type="text" style="border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
	<? }else{ ?>
	<td colspan="2"></td>
	<? } ?>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="dimension" VALUE="<?php echo $this->formvars['dimension']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="<?php echo $this->formvars['layer_id']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_columnname" VALUE="<?php echo $this->formvars['layer_columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_tablename" VALUE="<?php echo $this->formvars['layer_tablename']; ?>">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<?php echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>"> 
<? if($this->formvars['go'] == 'PointEditor'){ ?>   
	<INPUT TYPE="HIDDEN" NAME="go" VALUE="PointEditor" >
<? } ?>
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	
