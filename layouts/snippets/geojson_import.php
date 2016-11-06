<?php
  include(LAYOUTPATH.'languages/geojson_import_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
<!--

function save(){
	document.GUI.go_plus.value = 'Importieren';
	document.GUI.submit();
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="6" valign="top" style="height: 40px"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
	<tr>
		<td rowspan="2" align="right"><span class="fett"><?php echo $strFile; ?></span></td>
		<td rowspan="2"><input type="file" name="file1"></td>
		<td rowspan="2" colspan="2">==>&nbsp;</td>
		<td align="center"><span class="fett"><? echo $strSchema; ?></span></td>
		<td align="center"><span class="fett"><? echo $strTable; ?></span></td>
	</tr>
	<tr>
		<td align="center" height="35"><input name="schema_name" type="text" value="" size="15" class="input"></td>
		<td align="center" height="35"><input name="table_name" type="text" value="" size="15" class="input"></td>
	</tr>
	<tr>
		<td colspan="6" align="center"><input class="button" type="button" name="save1" value="<? echo $strImport; ?>" onclick="save();"></td>
	</tr>
  <tr> 
    <td colspan="6">&nbsp;</td>
  </tr>
	<? if($this->result != NULL){?>
	<tr>
		<td colspan="6" align="center"><? echo $strSucces; ?></td>
	</tr>
	<? } ?>
</table>

<input type="hidden" name="go" value="GeoJSON_Import">
<input type="hidden" name="go_plus" value="">


