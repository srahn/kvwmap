<?php
	# 2008-01-12 pkvvm
	include(LAYOUTPATH.'languages/layer_export_'.$this->user->rolle->language.'.php');
?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"	type="text/javascript"></script>
<script type="text/javascript">
<!--
	function export_layer(){
		document.GUI.selected_layers.value = '';
		addSelectedValuesToHiddenField(document.GUI.layer, document.GUI.selected_layers);
		document.GUI.go.value = 'Layer_Export_Exportieren';
		document.GUI.submit();
	}
//-->
</script>

<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center"> 
		<td colspan="5"><h2><?php echo $this->titel; ?></h2></td>
	</tr>
	<tr>
		<td colspan="5">
			<div style="margin-bottom: 10px;padding: 5px; border: 1px solid #C3C7C3;">
				<?php echo $strLayer;?><br><?
				$layerSelectField = new FormObject(
					"layer",
					"select",
					$this->layerdaten['ID'],
					$this->formvars['selected_layer_id'],
					$this->layerdaten['Bezeichnung'],
					20,
					0,
					true,
					250,
					count($this->layerdaten['ID']) == 0,
					"margin-top: 5px;"
				);
				echo $layerSelectField->html; ?>
			</div>
			<input type="checkbox" name="with_privileges" value="true"<? echo ($this->formvars['with_privileges'] != '' ? ' checked' : ''); ?>>
				Mit Stellenzugehörigkeit
			</input></td>
		</td>
	</tr>
	<tr>
		<td><input type="button" class="button" value="Exportieren" onclick="export_layer();"></td>
	</tr>
	<? if($this->layer_dumpfile != ''){ ?>
	<tr>
		<td>Layer wurden exportiert. <a href="<? echo TEMPPATH_REL.$this->layer_dumpfile; ?>"><span class="fett">Herunterladen</span></a></td>
	</tr>	
	<? } ?>
</table>

<input type="hidden" name="go" value="Layer_Export">
<input type="hidden" name="selected_layers" value="">