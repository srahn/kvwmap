<?php
	include(LAYOUTPATH . 'languages/collection_formular_' . rolle::$language . '.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script>
	function gotoCollectionList() {
		location.href = 'index.php?go=collections_anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
	}
</script>
<style>
	.collection-button {
		margin-left: 5px;
	}
	.full-width-centered {
		margin-top: 20px;
    width: 100%;
    box-sizing: border-box;
    text-align: center;
	}
</style>

<div class="center-outerdiv">
	<div class="input-form">
		<h2><?php echo $strTitelEditor; ?></h2>
		<div class="full-width-centered"><em>Werte mit * mussen eingetragen werden</em></div>
		<p>
		<br><?php
		echo $this->collection->as_form_html(); ?>
		<div class="clear"></div>
		<div style="text-align: -webkit-center">
			<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
				<tr>
					<td align="center">
						<input type="hidden" name="go_plus" id="go_plus" value="Ändern">
						<input value="zurück zur Liste" title="zurück zur Collectionliste" type="button" class="collection-button" onclick="gotoCollectionList(<? echo $this->collection->get_id(); ?>)"><?
						if ($this->collection->get('id') != '') { ?>
							<input value="Zurücksetzen" title="Setzt alle Werte zurück auf die vorhigen." type="reset" name="reset1" class="collection-button">
							<input value="Ändern" title="Änderungen Speichern" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','save')" class="collection-button">
							<input value="Löschen" title="Collection Löschen" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','delete')" class="collection-button">
							<input type="hidden" name="selected_collection_id" value="<?php echo  $this->collection->get_id(); ?>" class="collection-button"><?
						} ?>
				  </td>
			  </tr>
			</table>
			<input type="hidden" name="id" value="<? echo $this->collection->get_id(); ?>">
			<input type="hidden" name="go" value="collection">
		</div>
	</div>
</div>
