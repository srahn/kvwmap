<? include(LAYOUTPATH . 'languages/zwischenablage_' . $this->user->rolle->language . '.php'); ?>

<style>

	.item {
    background-color: #DAE4EC;
    border: 1px solid #C3C7C3;
    padding: 5 5 5 5;
		height: 25px;
	  line-height: 25px; 
		margin: 1px;
	}

	.layer-item {
		float: left;
    width: 40%;
    text-align: left;
    margin-left: 50px;
	}

	.show-item {
		float: left;
		width: 20%;
		text-align: left;
	}

	.print-button-item {
		float: left;
		width: 10%;
	}
	.delete-button-item {
		float: left;
		width: 10%;
  }
</style>

<input type="hidden" name="go" value="">
<h2 style="margin: 20px"><?php echo $strBookmarks; ?></h2>

<div class="item fett layer-item"><? echo $this->strLayer; ?></div>

<div class="item fett show-item"><? echo $this->strCount; ?></div>

<div class="item fett print-button-item"><? echo $this->strPrint; ?></div>

<div class="item fett delete-button-item"><? echo $this->strDelete; ?></div><?

$print_all = false;

for ($i = 0; $i < @count($this->layer); $i++) { ?>
	<div class="item layer-item">
		<a href="index.php?go=gemerkte_Datensaetze_anzeigen&layer_id=<? echo $this->layer[$i]['layer_id']; ?>"><? echo $this->layer[$i]['Name_or_alias']; ?></a>
	</div>

	<div class="item show-item">
		<a href="index.php?go=gemerkte_Datensaetze_anzeigen&layer_id=<? echo $this->layer[$i]['layer_id']; ?>"><? echo $this->layer[$i]['count'] . ' ' . ($this->layer[$i]['count'] == 1 ? $strRecord : $strRecords); ?></a>
	</div>
<? 
	if (!empty($this->layer[$i]['layouts'])) {
		$print_all = true;	 ?>
		<a href="index.php?go=gemerkte_Datensaetze_drucken&chosen_layer_id=<? echo $this->layer[$i]['layer_id']; ?>">
			<div class="item print-button-item button drucken">
			</div>
		</a>	<? 
	}
	else { ?>
		<div class="item print-button-item">
		</div>
<? } ?>

	<a href="index.php?go=Datensaetze_nicht_mehr_merken&chosen_layer_id=<? echo $this->layer[$i]['layer_id']; ?>">
		<div class="item delete-button-item button datensatz_loeschen">
		</div>
	</a>

	<div style="clear: both"></div><?
} ?>
<br>
<div class="item layer-item"></div>

<div class="item show-item">
	<?php echo $this->num_rows == 0 ? '' : $this->all . ' ' . $strRecords; ?>
</div>

<?
	if ($print_all) { ?>
		<a href="index.php?go=gemerkte_Datensaetze_drucken">
			<div id="print_all" class="item print-button-item button drucken">
			</div>
		</a> <?
	}
	else { ?>
		<div id="print_all" class="item print-button-item">
		</div>
<?}?>

<a href="index.php?go=Datensaetze_nicht_mehr_merken">
	<div id="delete_all" class="item delete-button-item<?php echo $this->num_rows == 0 ? '' : ' button datensatz_loeschen'; ?>">
	</div>
</a>

<? 

if ($this->num_rows == 0) {
	echo '<div style="margin-top: 70px">' . $strNoRecords . '</div>';
}

?>

<input type="hidden" name="go_plus" value="">