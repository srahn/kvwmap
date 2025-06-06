<?php
	include(LAYOUTPATH . 'languages/layergruppe_formular_' . rolle::$language . '.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script>
	function gotoLayerEditor(layerId) {
		location.href = 'index.php?go=Layereditor&selected_layer_id=' + layerId + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
	}
</script>
<style>
	.layergruppe-button {
		margin-left: 5px;
	}
</style>
<div class="center-outerdiv">
	<div class="input-form">
		<h2><?php echo $strTitel; ?></h2>
		<em><span class="px13">Werte mit * mussen eingetragen werden</span></em><p><?php
		echo $this->layergruppe->as_form_html(); ?>
		<div class="clear"></div>
		<div style="text-align: -webkit-center">
			<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
				<tr>
					<td align="center">
						<input type="hidden" name="go_plus" id="go_plus" value="">
						<input value="zurück" title="Zeigt die ganze Liste an." type="button" name="go" onclick="document.location.href='index.php?go=Layergruppen_Anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>#group_<?php echo $this->layergruppe->get('id'); ?>'" class="layergruppe-button"><?php
						if ($this->layergruppe->get('id') != '') { ?>
							<input value="Ändern" title="Änderungen Speichern" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Ändern')" class="layergruppe-button">
							<input value="Zurücksetzen" title="Setzt alle Werte zurück auf die vorhigen." type="reset" name="reset1" class="layergruppe-button">
							<input type="hidden" name="selected_group_id" value="<?php echo  $this->layergruppe->get('id'); ?>" class="layergruppe-button"><?php
							if (intval($this->formvars['selected_layer_id']) > 0) { ?>
								<input value="<? echo $this->formvars['selected_layer_id']; ?>" type="hidden" name="selected_layer_id">
								<input value="zurück zum Layer" title="zurück zum Layer" type="button" class="layergruppe-button" onclick="gotoLayerEditor(<? echo $this->formvars['selected_layer_id']; ?>)"><?
							}
						}
						else { ?>
							<input value="Speichern" title="Speichern" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Speichern')" class="layergruppe-button">
							<input value="Zurücksetzen" title="Setzt die Werte zurück auf den letzten Stand." type="button" onclick="document.location.href='index.php?go=Layergruppe_Editor&selected_group_id=<?php echo  $this->layergruppe->get('id'); ?>'" class="layergruppe-button"><?
						} ?>
						<!--input type="button" name="dummy" value="Als neue Gruppe eintragen" onclick="submitWithValue('GUI','go_plus','Als neue Gruppe eintragen')"-->
				  </td>
			  </tr>
			</table>
			<input type="hidden" name="go" value="Layergruppe">
			<input type="hidden" name="selected_group_id" value="<? echo  $this->layergruppe->get('id'); ?>">
		</div>
	</div>
</div>