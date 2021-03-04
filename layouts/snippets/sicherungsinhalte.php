<?php
  include(LAYOUTPATH . 'languages/sicherungsinhalte_' . $this->user->rolle->language . '.php');
?>
<table style="width: 100%">
	<tr>
		<td align="left"><h2><? echo $strTitle; ?></h2></td>
	</tr>
</table>
<br>
<table id="tbl_inhalte" style="width: 100%" cellpadding="5" cellspacing="0">
	<head>
		<tr>
			<th><?php echo $strName ?></td>
			<th><?php echo $strBeschreibung ?></td>
			<th><?php echo $strMethode ?></td>
			<th><?php echo $strQuelle . ' -> ' . $strZielBezeichnung ?></td>
			<td></td>
		</tr>
	</head>
	<body><?php
		if (!empty($this->sicherung->inhalte)) {
			foreach ($this->sicherung->inhalte as $inhalt) { ?>
				<tr>
					<td><?php echo $inhalt->get('name') ?></td>
					<td><?php echo $inhalt->get('beschreibung') ?></td>
					<td><?php echo $inhalt->get('methode') ?></td>
					<td><?php echo $inhalt->get('source') . ' -> ' . $inhalt->get('target') ?></td>
					<td width="50px">
						<a href="index.php?go=sicherungsinhalt_editieren&id=<?php echo $inhalt->get('id'); ?>">
							<i class="fa fa-pencil fa_lg" style="color: #a82e2e;"></i>
						</a>
						<a href="index.php?go=sicherungsinhalt_loeschen&id=<?php echo $inhalt->get('id'); ?>" style="margin-left: 10px;">
							<i class="fa fa-trash-o fa_lg" style="color: #a82e2e;"></i>
						</a>
					</td>
				</tr><?php
			}
		} ?>
		<tr>
			<td colspan="5" align="right">
				<input
					type="button"
					name="bttn"
					onclick="document.location.href = 'index.php?go=sicherungsinhalt_editieren&sicherung_id=<?php echo $this->sicherung->get('id') ?>'" 
					value="<?php echo $strneuerInhalt ?>"
				>
			</td>
		</tr>
	</body>
</table>