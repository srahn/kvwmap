<script type="text/javascript">
<!--


//-->
</script>

<br><h2><?php echo $this->titel; ?></h2>
<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?><p>
<table border="0" cellpadding="0" cellspacing="2">
  <tr>
    <td height="28" align="right"><strong>Gemarkung (Gemeinde):</strong></td>
    <td><?php echo $this->GemkgFormObj->html; ?></td>
  </tr>
  <tr>
    <td height="28" align="right"><strong>Nutzungsart:</strong></td>
    <td><input name="nutzung" type="text" value="<? echo $this->formvars['nutzung']; ?>"></td>
  </tr>
  <tr> 
    <td height="28" align="right"><strong>Anzahl&nbsp;Treffer&nbsp;anzeigen:</strong></td>
    <td><input name="anzahl" type="text" value="<?php echo $this->formvars['anzahl']; ?>" size="2" tabindex="5"></td>
  </tr>
  <tr> 
   <td colspan="3" align="center"> 
<br>
<input type="hidden" name="go" value="Nutzung_auswaehlen">
<input type="submit" name="go_plus" value="Suchen" tabindex="6">&nbsp;<input type="submit" name="go_plus" value="Abbrechen">&nbsp;<input type="reset" name="reset" value="Zur&uuml;cksetzen">
<br>
   </td>
  </tr><?php 
  $anzNamen=count($this->flurstuecke);
  if ($anzNamen>0) {
   ?>
<tr>
  <td colspan="3" align="center">
		<strong><br>
		Treffer gesamt: <?php echo $this->anzNamenGesamt; ?>
	    <br>
	    <br>
		</strong>	
		<table width="100%" border="1" cellpadding="3" cellspacing="0">
	  	<tr>
	    	<td><b>Flurstück</b></td>
	    	<td><b>Gemarkung</b></td>
	    	<td><b>ALB-Fläche</b></td>
	    	<td><b>Nutzung</b></td>
	    	<td colspan="2"><b>ALB-Auszug</b></td>
	    	<td><b>Kartenausschnitt</b></td>
	    </tr>
		   <?	for($j = 0; $j < count($this->flurstuecke); $j++){ ?>
			<tr>
				<td><? echo $this->flurstuecke[$j]['flurstkennz']; ?></td>
				<td><? echo $this->flurstuecke[$j]['gemkgname']; ?></td>
	    	<td><? echo $this->flurstuecke[$j]['flaeche']; ?> m²</td>
	    	<td><? echo $this->flurstuecke[$j]['bezeichnung'].' ('.$this->flurstuecke[$j]['nutzkennz'].') '.$this->flurstuecke[$j]['nutzflaeche']; ?>&nbsp;m²</td>
	    	<td>
	    		<? $this->getFunktionen();
	        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
	          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $this->flurstuecke[$j]['flurstkennz']; ?>&formnummer=30&wz=0" target="_blank">30</a>
	          <?php } ?>
	      </td>
	      <td>
	        <?php
	        if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
	          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $this->flurstuecke[$j]['flurstkennz']; ?>&formnummer=35&wz=0" target="_blank">35</a>
	        <?php } ?>
				</td>
				<td><a href="index.php?go=ZoomToFlst&FlurstKennz=<?php echo $this->flurstuecke[$j]['flurstkennz']; ?>">Kartenausschnitt</a></td>
	    </tr>
			<? } ?>
		</table>
	</td>
</tr>    
	<?	}  ?>
</table>
<input type="hidden" name="order" value="">

