<? include(LAYOUTPATH . 'languages/zwischenablage_' . $this->user->rolle->language . '.php'); ?>

<script type="text/javascript">
	<!--
	document.addEventListener('DOMContentLoaded', function () {
    // Find select elements with type=customMulti and multiple attribute
    let deleteDivs = document.querySelectorAll('.delete-button-item');

		deleteDivs.forEach(function (deleteDiv) {
      var deleteDivId = deleteDiv.id.split('_')[1];

			deleteDiv.addEventListener('click', function () {
				let url = `index.php?go=Datensaetze_nicht_mehr_merken${deleteDivId == 'all' ? '' : `&chosen_layer_id=${deleteDivId}`}`;
				console.log('Lösche Einträge für Layer mit url: ', url);
				window.location = url;
			});

		});

		let printDivs = document.querySelectorAll('.print-button-item');
		printDivs.forEach(function (printDiv) {
      var printDivId = printDiv.id.split('_')[1];

			printDiv.addEventListener('click', function () {
				let url = `index.php?go=gemerkte_Datensaetze_drucken${printDivId == 'all' ? '' : `&chosen_layer_id=${printDivId}`}`;
				// console.log('Drucke Einträge für Layer mit url: ', url);
				window.location = url;
			});

		});
	});
	-->
</script>
<style>

	.header-item {
		font-weight: bold;
	}

	.item {
    background-color: #b4caed;
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
		cursor: pointer;
	}
	.delete-button-item {
		float: left;
		width: 10%;
		cursor: pointer;
  }
</style>

<input type="hidden" name="go" value="">
<h2 style="margin: 20px"><?php echo $strBookmarks; ?></h2>

<div class="header-item item layer-item"><? echo $this->strLayer; ?></div>

<div class="header-item item show-item"><? echo $this->strLimit; ?></div>

<div class="header-item item print-button-item"><? echo $this->strPrint; ?></div>

<div class="header-item item delete-button-item"><? echo $this->strDelete; ?></div><?

for ($i = 0; $i < @count($this->layer); $i++) { ?>
	<div class="item layer-item">
		<a href="index.php?go=gemerkte_Datensaetze_anzeigen&layer_id=<? echo $this->layer[$i]['layer_id']; ?>"><? echo $this->layer[$i]['Name']; ?></a>
	</div>

	<div class="item show-item">
		<a href="index.php?go=gemerkte_Datensaetze_anzeigen&layer_id=<? echo $this->layer[$i]['layer_id']; ?>"><? echo $this->layer[$i]['count'] . ' ' . ($this->layer[$i]['count'] == 1 ? $strRecord : $strRecords); ?></a>
	</div>

	<div id="print_<? echo $this->layer[$i]['layer_id']; ?>" class="item print-button-item button drucken">
		<img src="graphics/leer.gif">
	</div>

	<div id="delete_<? echo $this->layer[$i]['layer_id']; ?>" class="item delete-button-item button datensatz_loeschen">
		<img src="graphics/leer.gif">
	</div>

	<div style="clear: both"></div><?
} ?>

<div class="item layer-item"></div>

<div class="item show-item">
	<?php echo $this->num_rows == 0 ? $strNoRecords : $this->all . ' ' . $strRecords; ?>
</div>

<div id="print_all" class="item print-button-item<?php echo $this->num_rows == 0 ? '' : ' button drucken'; ?>">
	<img src="graphics/leer.gif">
</div>

<div id="delete_all" class="item delete-button-item<?php echo $this->num_rows == 0 ? '' : ' button datensatz_loeschen'; ?>">
	<img src="graphics/leer.gif">
</div>
<input type="hidden" name="go_plus" value="">