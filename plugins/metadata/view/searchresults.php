<h2>Metadaten Suchergebnis</h2>
<p>
<?php
 $anzResults=count($this->metadaten);
 if ($anzResults == 0) {
	?>
	Keine Treffer.
	<?php
	if ($this->errmsg!='') {
		echo '<br>'.$this->errmsg;
	}
}
else { ?>
	<strong>Anzahl Treffer: <?php echo $anzResults; ?></strong><br>
	<br>
	<p align="left"><?php
		for ($j=0; $j < $anzResults; $j++) {
			$rs = $this->metadaten[$j]; ?>
			<i><?php echo $rs['idtype']; ?>:</i>&nbsp;<a href="index.php?go=Metadatenblattanzeige&oid=<?php echo $rs['oid']; ?>" class="blue_underline"><?php echo $rs['restitle']; ?></a>
			<br><i>Kurzbeschreibung:</i>&nbsp;<?php echo $rs['idabs']; ?>
			<br><i>Schlagwörter:</i>&nbsp;<?php echo $rs['themekeywords']; ?> <?php echo $rs['placekeywords']; ?>
			<br><i>Organization:</i>&nbsp;<?php echo $rs['rporgname']; ?>
			<br><i>Download:</i>&nbsp;<a href="<?php echo $rs['download']; ?>" class="green"><?php echo $rs['download']; ?></a>
			<br><span class="fett"><a href="index.php?go=Metadateneingabe&oid=<?php echo $rs['oid']; ?>">Bearbeiten</a></span><br><br><?php
		} ?>
	</p><?php
} ?>
<br>		 
<input type="hidden" name="go" value="Metadaten_Auswaehlen">
<input type="hidden" name="was" value="<?php echo $this->formvars['was']; ?>">
<input type="hidden" name="vonwann" value="<?php echo $this->formvars['vonwann']; ?>">
<input type="hidden" name="biswann" value="<?php echo $this->formvars['biswann']; ?>">
<input type="hidden" name="wer" value="<?php echo $this->formvars['wer']; ?>">
<input type="hidden" name="wo" value="<?php echo $this->formvars['wo']; ?>">
<input type="hidden" name="northbl" value="<?php echo $this->formvars['northbl']; ?>">
<input type="hidden" name="southbl" value="<?php echo $this->formvars['southbl']; ?>">		
<input type="hidden" name="westbl" value="<?php echo $this->formvars['westbl']; ?>">
<input type="hidden" name="eastbl" value="<?php echo $this->formvars['eastbl']; ?>">
<input type="submit" name="Submit" value="Zur Metadatensuche">
<br>
<br>
<br>