<br><h2><?php echo $this->titel; ?></h2><br>
<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>
<table border="0" cellspacing="2" cellpadding="0">
	<? if($this->filename != ''){ ?>
	<tr> 
		<td>&nbsp;</td>
  </tr>
  <tr> 
		<td>Export-Datei erzeugt:&nbsp;<a href="<? echo $this->filename; ?>">herunterladen</a></td>
  </tr>
  <? } ?>
	<tr> 
		<td>&nbsp;</td>
  </tr>
  <tr align="center"> 
		<td colspan="4">
			<input type="submit" name="go_plus" value="Exportieren">
		</td>
  </tr>
  <tr>
  	<td></td>
  </tr>
  <tr align="center"> 
		<td colspan="4">
			<input type="submit" name="go_plus" value="Tabelle Bereinigen">
		</td>
  </tr>
</table>
<input type="hidden" name="go" value="Adressaenderungen_Export">

