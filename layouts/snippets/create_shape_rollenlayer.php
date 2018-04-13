<?php
  include(LAYOUTPATH.'languages/shape_import_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function save(){
	// if(document.GUI.epsg.value == ''){
		// alert('Bitte geben Sie ein Koordinatensystem (EPSG-Code) an.');
	// }else{
		document.GUI.go_plus.value = 'Datei laden';
		document.GUI.submit();
	// }
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="3"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
	<? if($this->data_import_export->shapefile == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2"><?php echo $strLoadZipArchieve; ?></td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="right"><span class="fett"><?php echo $strZipArchive; ?></span></td>
		<td><input type="file" name="file1"></td>
		<td>&nbsp;</td>
	</tr>
	<? }else{ ?>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2"><?php echo $strEnterEpsgCode; ?></td>
  	<td>&nbsp;</td>
  </tr>
	<tr id="epsg_selector" align="center">
		<td>&nbsp;</td>
    <td align="right" style="border-bottom:1px solid #C3C7C3"><span class="fett"><?php echo $strMapProjection; ?>:&nbsp;</span></td>
    <td align="left" style="border-bottom:1px solid #C3C7C3">
      <select name="epsg">
		    <option value="">--<?php echo $this->strChoose; ?>--</option>
		    <?
  			foreach($this->epsg_codes as $epsg_code){
  				echo '<option value="'.$epsg_code['srid'].'">';
  				echo $epsg_code['srid'].': '.$epsg_code['srtext'];
  				echo "</option>\n";
  			}
  			?>
		  </select>
    </td>
    <td>&nbsp;</td>
  </tr>
	<? } ?>
	<tr>
		<td colspan="3" align="center">
      <input type="button" name="cancel" value="Abbrechen" onclick="home();">
      <input type="button" name="save1" value="Datei laden" onclick="save();"></td>
	</tr>
  <tr>
  	<td>&nbsp;</td>
  	<td>  		
  	</td>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="SHP_Anzeigen">
<input type="hidden" name="shapefile" value="<? echo $this->data_import_export->shapefile; ?>">
<input type="hidden" name="go_plus" value="">


