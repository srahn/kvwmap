<?php
  include(LAYOUTPATH.'languages/geojson_anzeigen_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
<!--

function save(){
	document.GUI.go_plus.value = 'Datei laden';
	document.GUI.submit();
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="3" valign="top" style="height: 40px"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <? if($this->shape->formvars['file1'] == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
		<td align="right"><span class="fett"><?php echo $strFile; ?></span></td>
		<td><input type="file" name="file1"></td>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td colspan="3" align="center"><input type="button" name="save1" value="Datei laden" onclick="save();"></td>
	</tr>
	<? }
			else{ ?> 
  <tr>
  	<td>&nbsp;</td>
  	<td>  		
  	</td>
  	<td>&nbsp;</td>
  </tr>
  <? } ?>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="GeoJSON_Anzeigen">
<input type="hidden" name="go_plus" value="">


