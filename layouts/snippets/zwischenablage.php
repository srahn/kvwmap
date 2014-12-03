
<script type="text/javascript">
<!--

//-->
</script>

<br>
<input type="hidden" name="go" value="">
<h2><?php echo $this->titel; ?></h2>    

<table border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>
			<table cellpadding="0" cellspacing="0">
		<?	for($i = 0; $i < count($this->layer); $i++){ ?>
					<tr>
						<td style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border:1px solid #C3C7C3;border-right:none;">
							<a href="index.php?go=gemerkte_Datensaetze_anzeigen&layer_id=<? echo $this->layer[$i]['layer_id']; ?>"><div style="padding:5px;"><span class="fett"><? echo $this->layer[$i]['Name']; ?>:</span></div></a>
						</td>
						<td style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border:1px solid #C3C7C3;border-left:none;">
							<a href="index.php?go=gemerkte_Datensaetze_anzeigen&layer_id=<? echo $this->layer[$i]['layer_id']; ?>"><div style="padding:5px;"><span><? echo $this->layer[$i]['count']; if($this->layer[$i]['count'] == 1)echo ' Datensatz'; else echo ' Datensätze';?></span></div></a>
						</td>
						<td>
							&nbsp;&nbsp;<a title="entfernen" href="index.php?go=Datensaetze_nicht_mehr_merken&chosen_layer_id=<? echo $this->layer[$i]['layer_id']; ?>"><img style="border: 1px solid #C3C7C3" src="<? echo GRAPHICSPATH ?>datensatz_loeschen.png"></a>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
		<?		} ?>				
      </table> 
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr> 
	<? if($this->num_rows == 0){ ?>
	<tr>
		<td>&nbsp;</td>
		<td>Keine Datensätze vorhanden.</td>
	<tr>
	<? }else{ ?>
  <tr>
		<td>&nbsp;</td>
    <td align="center"><a title="entfernen" href="index.php?go=Datensaetze_nicht_mehr_merken">Zwischenablage leeren</a></td>
  </tr>
	<? } ?>
</table>
<input type="hidden" name="go_plus" value="">
