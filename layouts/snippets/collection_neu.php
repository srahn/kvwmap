<?php
	include(LAYOUTPATH . 'languages/collection_formular_' . rolle::$language . '.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script>
	function gotoLayerGroupEditor(layerGroupId) {
		location.href = 'index.php?go=Layergruppe_Editor&selected_group_id=' + layerGroupId + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
	}
	function gotoCollectionNew(layerGroupId) {
		location.href = `index.php?go=Collection_Neu&selected_group_id=${layerGroupId}&csrf_token=<? echo $_SESSION['csrf_token']; ?>`;
	}
</script>
<style>
	.collection-button {
		margin-left: 5px;
	}
</style>
<div class="center-outerdiv">
	<div class="input-form">
		<h2><?php echo $strTitel; ?></h2>
		<em><span class="px13">Werte mit * mussen eingetragen werden</span></em><p><?php
		echo $this->collection->as_form_html(); ?>
		<div class="clear"></div>
		<div style="text-align: -webkit-center">
			<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
				<tr>
					<td align="center">
						<input type="hidden" name="go_plus" id="go_plus" value="">
						<input value="zurück zur Gruppe" title="zurück zur Gruppe" type="button" class="collection-button" onclick="gotoLayerGroupEditor(<? echo $this->formvars['selected_group_id']; ?>)"><?
						if ($this->collection->get('id') != '') { ?>
							<input value="Ändern" title="Änderungen Speichern" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Ändern')" class="collection-button">
							<input value="Zurücksetzen" title="Setzt alle Werte zurück auf die vorhigen." type="reset" name="reset1" class="layergruppe-button">
							<input type="hidden" name="selected_group_id" value="<?php echo  $this->collection->get('id'); ?>" class="collection-button"><?php
							if (intval($this->formvars['selected_group_id']) > 0) { ?>
								<input value="<? echo $this->formvars['selected_group_id']; ?>" type="hidden" name="selected_group_id"><?
							}
						}
						else { ?>
							<input value="Anlegen" title="Anlegen" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Anlegen')" class="collection-button">
							<input value="Zurücksetzen" title="Setzt die Werte zurück auf den letzten Stand." type="button" class="collection-button" onclick="gotoCollectionNew(<? echo $this->formvars['selected_group_id']; ?>)"><?
						} ?>
				  </td>
			  </tr>
			</table>
			<input type="hidden" name="go" value="Collection">
			<input type="hidden" name="selected_group_id" value="<? echo  $this->collection->get('id'); ?>">
		</div>
	</div>
</div>