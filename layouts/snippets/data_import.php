<?php
  include(LAYOUTPATH.'languages/data_import_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
<!--


  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" style="width:400px" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" valign="top" style="height: 40px"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr> 
    <td align="center"><? echo $strType; ?>:&nbsp;
			<select name="go" onchange="document.GUI.submit();">
				<option value="">---<? echo $this->strChoose; ?>---</option>
				<option value="SHP_Anzeigen">Shape</option>
				<option value="GeoJSON_Anzeigen">GeoJSON</option>
				<option value="DXF_Import">DXF</option>
				<option value="OVL_Import">OVL</option>
				<option value="GPX_Import">GPX</option>
				<option value="Punktliste_Anzeigen"><? echo $strPointlist; ?></option>
			</select>
		</td>
  </tr>
</table>


