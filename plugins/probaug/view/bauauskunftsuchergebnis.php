<script type="text/javascript">
<!--
	
	function showdata(jahr, obergruppe, nummer){
		if(jahr == 'leer'){jahr = '';}
		if(obergruppe == 'leer'){obergruppe = '';}
		if(nummer == 'leer'){nummer = '';}
		document.GUI.go.value = "Baudatenanzeige";
		document.GUI.jahr.value = jahr;
		document.GUI.obergruppe.value = obergruppe;
		document.GUI.nummer.value = nummer;
		document.GUI.gemarkung.value = '';
		document.GUI.flur.value = '';
		document.GUI.flurstueck.value = '';
		document.GUI.withlimit.value = 'false';
		document.GUI.distinct.value = 0;
		document.GUI.submit();
	}
	
	function next(){
		document.GUI.go_plus.value = "Suchen";
		document.GUI.offset.value = Number(document.GUI.offset.value)+20;
		document.GUI.submit();
	}
	
	function prev(){
		document.GUI.go_plus.value = "Suchen";
		document.GUI.offset.value = Number(document.GUI.offset.value)-20;
		document.GUI.submit();
	}

//-->
</script>

<input type="hidden" name="go" value="Bauauskunft_Suche">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="withlimit" value="<? echo $this->formvars['withlimit']; ?>">
<h2><?php echo $this->titel; ?></h2>

<?php if ($this->Fehlermeldung!='') {

include(LAYOUTPATH."snippets/Fehlermeldung.php");

}

?>       

<table border="0" cellspacing="2" cellpadding="0" width=100%>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width=100%>
    	<table cellspacing="2" cellpadding="2" style="border:1px solid #C3C7C3" width=100%>
    		<tr>
	  		  	<td class="fett" style="border-bottom:1px solid #C3C7C3">
	  		  		Aktenzeichen		
	  		  	</td>
	  		  	<td class="fett" style="border-bottom:1px solid #C3C7C3">
	  		  		Vorhaben		
	  		  	</td>
						<td class="fett" style="border-bottom:1px solid #C3C7C3">
	  		  		Vorhaben-Merkmal
	  		  	</td>
	  		  	<td class="fett" style="border-bottom:1px solid #C3C7C3">
	  		  		Antragsteller		
	  		  	</td>
	  		  	<td class="fett" style="border-bottom:1px solid #C3C7C3">
	  		  		Bauort
	  		  	</td>
  		  	</tr>
  	<? if($this->bau->baudata[0]['feld1'] == ''){
  			echo '
  				<tr>
  					<td colspan=4 align=center>
  						Es wurden keine Datensätze gefunden.
  					</td>
  				</tr>
  			';
  		}  
  	
  		for($i = 0; $i < @count($this->bau->baudata); $i++){
  			$feld1 = $this->bau->baudata[$i]['feld1'];
  			$feld2 = $this->bau->baudata[$i]['feld2'];
  			$feld3 = $this->bau->baudata[$i]['feld3'];
  			if($this->bau->baudata[$i]['feld1'] == ''){$feld1 = "'leer'";}
			if($this->bau->baudata[$i]['feld2'] == ''){$feld2 = "'leer'";}
			if($this->bau->baudata[$i]['feld3'] == ''){$feld3 = "'leer'";}         ?>
			
  			<tr>
  				<td style="border-bottom:1px solid #C3C7C3" valign="top">
  					<a href="javascript:showdata(<? echo $feld1.', '.$feld2.', '.$feld3; ?>)"><? echo $this->bau->baudata[$i]['feld3'].'-'.$this->bau->baudata[$i]['feld1'].'-'.$this->bau->baudata[$i]['feld2'] ?></a>
  				</td>	
  				<td style="border-bottom:1px solid #C3C7C3" valign="top"> 
  					<? echo $this->bau->baudata[$i]['feld8'] ?>	
  				</td>
					<td style="border-bottom:1px solid #C3C7C3" valign="top"> 
  					<? echo $this->bau->baudata[$i]['feld4'].' '.$this->bau->baudata[$i]['feld5'].' '.$this->bau->baudata[$i]['feld6'].' '.$this->bau->baudata[$i]['feld7'] ?>	
  				</td>
  				<td style="border-bottom:1px solid #C3C7C3" valign="top"> 
  					<? echo $this->bau->baudata[$i]['feld20'] ?>	
  				</td>
  				<td style="border-bottom:1px solid #C3C7C3" valign="top">
  					<? echo $this->bau->baudata[$i]['bauort']; ?>
  				</td>
  			</tr>
  	<?  }     ?>
    	</table> 
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
  	<td>
  		&nbsp;
  	</td>
  </tr>
  
  
  <tr align="center"> 
    <td colspan="3"> 
 		<input type="hidden" name="verfahrensart" value="<? echo $this->formvars['verfahrensart']; ?>">
 		<input type="hidden" name="vorhaben" value="<? echo $this->formvars['vorhaben']; ?>">
 		<input type="hidden" name="nummer" value="<? echo $this->formvars['nummer']; ?>">
 		<input type="hidden" name="jahr" value="<? echo $this->formvars['jahr']; ?>">
 		<input type="hidden" name="obergruppe" value="<? echo $this->formvars['obergruppe']; ?>">
 		<input type="hidden" name="vonJahr" value="<? echo $this->formvars['vonJahr']; ?>">
 		<input type="hidden" name="bisJahr" value="<? echo $this->formvars['bisJahr']; ?>">
 		<input type="hidden" name="nachname" value="<? echo $this->formvars['nachname']; ?>">
 		<input type="hidden" name="vorname" value="<? echo $this->formvars['vorname']; ?>">
 		<input type="hidden" name="strasse" value="<? echo $this->formvars['strasse']; ?>">
 		<input type="hidden" name="hausnummer" value="<? echo $this->formvars['hausnummer']; ?>">
 		<input type="hidden" name="plz" value="<? echo $this->formvars['plz']; ?>">
 		<input type="hidden" name="ort" value="<? echo $this->formvars['ort']; ?>">
 		<input type="hidden" name="gemarkung" value="<? echo $this->formvars['gemarkung']; ?>">
 		<input type="hidden" name="flur" value="<? echo $this->formvars['flur']; ?>">
 		<input type="hidden" name="flurstueck" value="<? echo $this->formvars['flurstueck']; ?>">
 		
 		<input type="hidden" name="nummer_" value="<? echo $this->formvars['nummer']; ?>">
 		<input type="hidden" name="jahr_" value="<? echo $this->formvars['jahr']; ?>">
 		<input type="hidden" name="obergruppe_" value="<? echo $this->formvars['obergruppe']; ?>">
 		<input type="hidden" name="gemarkung_" value="<? echo $this->formvars['gemarkung']; ?>">
 		<input type="hidden" name="flur_" value="<? echo $this->formvars['flur']; ?>">
 		<input type="hidden" name="flurstueck_" value="<? echo $this->formvars['flurstueck']; ?>">
 		
 		<input type="hidden" name="limit" value="<? echo $this->formvars['limit'] ?>">
 		<input type="hidden" name="offset" value="<? echo $this->formvars['offset'] ?>">
 		<input type="hidden" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>">
 		<input type="hidden" name="distinct" value="<? echo $this->formvars['distinct']; ?>">
 		<input type="submit" name="zurueck" value="zurück zur Suche">
 		 
 		
    </td>
  </tr>
  <tr align="center">
  	<td>
  	</td>
  	<td><? if($this->formvars['withlimit'] == 'true' && $this->bau->baudata[0]['feld1'] != ''){
  			$von = $this->formvars['offset'] + 1;
  			if($this->formvars['anzahl'] - $this->formvars['offset'] < $this->formvars['limit']){
  				$bis = $this->formvars['anzahl'];
  			}
  			else{
  				$bis = $this->formvars['offset'] + $bis = $this->formvars['limit'];
  			}
  		 ?>
  		<table>
  			<tr>
			  	<td>
			  		<? if($this->formvars['offset'] > 0){ echo '<a href="javascript:prev()">zurück</a>';} ?>
			  	</td>
			  	<td align="center" width="100%">
			  		Suchergebnisse <? echo $von.' bis '.$bis.' von '.$this->formvars['anzahl']; ?> 
			  	</td>
			  	<td>
			  		<? if($this->formvars['anzahl'] > $this->formvars['offset']+$this->formvars['limit']){ echo '<a href="javascript:next()">vor</a>';} ?>
			  	</td>
			</tr>
		</table>
		<? } ?>
	</td>
	<td>
	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
</table>

