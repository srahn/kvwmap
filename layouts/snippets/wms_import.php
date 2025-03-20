<?php
 # 2008-01-20 pkvvm
  include(LAYOUTPATH.'languages/wms_import_'.rolle::$language.'.php');
	
	function print_layer_tree($layers){
		for($i = 0; $i < count($layers); $i++){
			$layer = $layers[$i];
			echo '<div style="border: none;padding: 0;margin-left: 10px"><table cellpadding="0" cellspacing="0"><tr><td>';
			if($layer['layers'] != '')echo '<img src="'.GRAPHICSPATH.'minus.gif">';
			elseif($i+1 == count($layers))echo '<img src="'.GRAPHICSPATH.'subfolder.png">';
			else echo '<img src="'.GRAPHICSPATH.'subfolder2.png">';
			echo '</td><td>';
			if($layer['name'] != ''){
				echo '<input type="checkbox" name="layers[]" value="'.$layer['name'].'">';
				echo '<input type="hidden" name="srs[]" value="'.$layer['srs'].'">';
			}
			echo '<span style="">&nbsp;'.$layer['name'].'&nbsp;('.$layer['title'].')</span></td></tr></table>';
			if($layer['layers'] != '')print_layer_tree($layer['layers']);
			echo '</div>';
		}
	}
	
 ?>
 
<script type="text/javascript">
	
	function add_layers(){
		document.GUI.go_plus.value='eintragen';
		document.GUI.submit();
	}
	
</script>

<table border="0" style="margin: 5px" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr>
    <td colspan="2" align="center"><h2><?php echo $this->titel; ?></h2></td> 
  </tr>
  <tr>
    <td colspan="2" align="center"><?php
if ($this->Fehlermeldung!='') {
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?></td>
  </tr>
	<tr>
		<td>
			<span class="fett"><?php echo $strAdresse; ?></span>
		</td>
		<td>
			<input style="width: 100%" type="text" size="60" name="wms_url" value="<? echo $this->formvars['wms_url']; ?>">
		</td>
	</tr>
	<tr class="wms-auth" style="display: none;">
		<td>
			<span class="fett">Nutzername:</span>
		</td>
		<td>
			<input style="width: 150px" type="text" maxlength="100" name="wms_auth_username" value="<? echo $this->formvars['wms_auth_username']; ?>">
		</td>
	</tr>
	<tr class="wms-auth" style="display: none;">
		<td>
			<span class="fett">Password:</span>
		</td>
		<td>
			<input style="width: 150px" type="password" maxlength="50" name="wms_auth_password" value="<? echo $this->formvars['wms_auth_password']; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input
				class="wms-auth"
				type="button"
				value="ohne Basic Authentication"
				onclick="
					$('.wms-auth').toggle();
					$('input[name=wms_auth_username], input[name=wms_auth_password]').val('');
				"
				style="float: left; display: none;"
			>
			<input
				class="wms-auth"
				type="button"
				value="mit Basic Authentication"
				onclick="$('.wms-auth').toggle()"
				style="float: left; display: block;"
			>
			<input
				type="submit"
				name="Input"
				value="<?php echo $this->strConnect; ?>"
				style="margin-left: 20px; float: left"
			>
		</td>
	</tr><? if ($this->layers != '') { ?>
	<tr>
		<td colspan="2"><span class="fett"><? echo $strAvailableLayers; ?>&nbsp;</span><span><? echo $strName.' ('.$strTitle.')' ?></span></td>
	</tr>
	<tr>
		<td colspan="2" width="100%" style="margin: 5px; border: 1px solid #cccccc">
	<?
		print_layer_tree($this->layers);
	?>
		</td>
	</tr>
	<tr>
    <td align="right">&nbsp;</td> 
    <td align="center">
      <input type="button" name="apply" onclick="add_layers();" value="<?php echo $this->strEnter; ?>">
		</td>
	</tr>
	<? } ?>
</table>
<input type="hidden" name="go" value="WMS_Import">
<input type="hidden" name="go_plus" value="">