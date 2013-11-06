<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/shape_import_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function save(){
	if(document.GUI.epsg.value == ''){
		alert('Bitte geben Sie ein Koordinatensystem (EPSG-Code) an.');
	}else{
		document.GUI.go_plus.value = 'Datei laden';
		document.GUI.submit();
	}
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="3"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <? if($this->shape->formvars['zipfile'] == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2"><?php echo $strLoadZipArchieve; ?></td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="right"><b><?php echo $strZipArchive; ?></b></td>
		<td><input type="file" name="zipfile" style="width:140px" ></td>
		<td>&nbsp;</td>
	</tr>
	<tr align="center">
		<td>&nbsp;</td>
    <td align="right" style="border-bottom:1px solid #C3C7C3"><b><?php echo $strMapProjection; ?>:&nbsp;</b></td>
    <td align="left" style="border-bottom:1px solid #C3C7C3">
      <select name="epsg">
		    <option value="">--<?php echo $this->strChoose; ?>--</option>
		    <?
  			for($i = 0; $i < count($this->epsg_codes); $i++){
  				echo '<option value="'.$this->epsg_codes[$i]['srid'].'">';
  				echo $this->epsg_codes[$i]['srid'].': '.$this->epsg_codes[$i]['srtext'];
  				echo "</option>\n";
  			}
  			?>
		  </select>
    </td>
    <td>&nbsp;</td>
  </tr>
	
	<tr>
		<td colspan="3" align="center"><input class="button" type="button" name="save1" value="Datei laden" onclick="save();"></td>
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

<input type="hidden" name="go" value="SHP_Anzeigen">
<input type="hidden" name="go_plus" value="">


