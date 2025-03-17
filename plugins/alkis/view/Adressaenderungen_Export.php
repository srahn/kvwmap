<br><h2><?php echo $this->titel; ?></h2><br>
<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>
<table border="0" cellspacing="2" cellpadding="0">
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
</table>
<input type="hidden" name="go" value="Adressaenderungen_Export">

