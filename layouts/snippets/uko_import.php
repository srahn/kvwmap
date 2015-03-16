<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/shape_import_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function save(){
	if(document.GUI.epsg.value == ''){
		alert('Bitte geben Sie ein Koordinatensystem (EPSG-Code) an.');
	}else{
		document.GUI.go_plus.value = 'Importieren';
		document.GUI.submit();
		document.GUI.go_plus.value = '';
	}
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="3"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <? if($this->formvars['ukofile'] == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
		<td align="center" style="border-bottom:1px solid #C3C7C3"><span class="fett">UKO- oder Zip-Datei:</span>
		<input class="button" type="file" name="ukofile" size="12">
		<input class="button" type="button" name="import" onclick="save();" value="Importieren"></td>
		<td>&nbsp;</td>
	</tr>
	<tr align="center">
		<td>&nbsp;</td>
		<td>
			<table>
				<tr>
					<td align="right"><span class="fett"><?php echo $strMapProjection; ?>:&nbsp;</span></td>
					<td align="left">
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
				</tr>
			</table>
		</td>
	<? }else{ ?>
	<tr>
		<td>&nbsp;</td>
		<? if($this->data_import_export->success == true){ ?>
		<td>UKO-Datei erfolgreich importiert</td>
		<? }else{ ?>
			<td>UKO-Datei Import fehlgeschlagen.</td>
		<? } ?>
		<td>&nbsp;</td>
	</tr> 
  <? } ?>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="UKO_Import">
<input type="hidden" name="go_plus" value="">


