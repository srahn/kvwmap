<script language="JavaScript" type="text/javascript">
<!--

function browser_switch(){
  if(navigator.appName == 'Microsoft Internet Explorer'){
		document.GUI.target = '_blank';
  }
  document.GUI.submit();
}

function back(){
	document.GUI.target = '';
	if(document.GUI.GemkgID != undefined){
		document.GUI.go.value = 'Flurstueck_Auswaehlen';
		document.GUI.go_plus.value = 'Suchen';
		document.GUI.submit();
	}
	else{
		document.GUI.go.value = 'Sachdaten';
		document.GUI.go_plus.value = '';
		document.GUI.submit();
	}
}

function save_selection(){
	if(document.GUI.name.value != ''){
		var attributes = '';
		if(document.GUI.flurstkennz.checked == true){attributes = attributes+'flurstkennz|'}
		if(document.GUI.flurnr != undefined && document.GUI.flurnr.checked == true){attributes = attributes+'flurnr|'}
		if(document.GUI.entsteh != undefined && document.GUI.entsteh.checked == true){attributes = attributes+'entsteh|'}
		if(document.GUI.letzff != undefined && document.GUI.letzff.checked == true){attributes = attributes+'letzff|'}
		if(document.GUI.status != undefined && document.GUI.status.checked == true){attributes = attributes+'status|'}
		if(document.GUI.flaeche != undefined && document.GUI.flaeche.checked == true){attributes = attributes+'flaeche|'}
		if(document.GUI.karte != undefined && document.GUI.karte.checked == true){attributes = attributes+'karte|'}
		if(document.GUI.kreisid != undefined && document.GUI.kreisid.checked == true){attributes = attributes+'kreisid|'}
		if(document.GUI.kreisname != undefined && document.GUI.kreisname.checked == true){attributes = attributes+'kreisname|'}
		if(document.GUI.gemkgschl != undefined && document.GUI.gemkgschl.checked == true){attributes = attributes+'gemkgschl|'}
		if(document.GUI.gemkgname != undefined && document.GUI.gemkgname.checked == true){attributes = attributes+'gemkgname|'}
		if(document.GUI.gemeinde != undefined && document.GUI.gemeinde.checked == true){attributes = attributes+'gemeinde|'}
		if(document.GUI.gemeindename != undefined && document.GUI.gemeindename.checked == true){attributes = attributes+'gemeindename|'}
		if(document.GUI.finanzamt != undefined && document.GUI.finanzamt.checked == true){attributes = attributes+'finanzamt|'}
		if(document.GUI.finanzamtname != undefined && document.GUI.finanzamtname.checked == true){attributes = attributes+'finanzamtname|'}
		if(document.GUI.forstschluessel != undefined && document.GUI.forstschluessel.checked == true){attributes = attributes+'forstschluessel|'}
		if(document.GUI.forstname != undefined && document.GUI.forstname.checked == true){attributes = attributes+'forstname|'}
		if(document.GUI.lagebezeichnung != undefined && document.GUI.lagebezeichnung.checked == true){attributes = attributes+'lagebezeichnung|'}
		if(document.GUI.nutzung != undefined && document.GUI.nutzung.checked == true){attributes = attributes+'nutzung|'}
		if(document.GUI.ausfstelle != undefined && document.GUI.ausfstelle.checked == true){attributes = attributes+'ausfstelle|'}
		if(document.GUI.verfahren != undefined && document.GUI.verfahren.checked == true){attributes = attributes+'verfahren|'}
		if(document.GUI.vorgaenger != undefined && document.GUI.vorgaenger.checked == true){attributes = attributes+'vorgaenger|'}
		if(document.GUI.blattnr != undefined && document.GUI.blattnr.checked == true){attributes = attributes+'blattnr|'}
		if(document.GUI.buchungsart != undefined && document.GUI.buchungsart.checked == true){attributes = attributes+'buchungsart|'}
		if(document.GUI.bvnr != undefined && document.GUI.bvnr.checked == true){attributes = attributes+'bvnr|'}
		if(document.GUI.pruefzeichen != undefined && document.GUI.pruefzeichen.checked == true){attributes = attributes+'pruefzeichen|'}
		if(document.GUI.pruefzeichen_f != undefined && document.GUI.pruefzeichen_f.checked == true){attributes = attributes+'pruefzeichen_f|'}
		if(document.GUI.eigentuemer != undefined && document.GUI.eigentuemer.checked == true){attributes = attributes+'eigentuemer|'}
		if(document.GUI.freitext != undefined && document.GUI.freitext.checked == true){attributes = attributes+'freitext|'}
		if(document.GUI.hinweis != undefined && document.GUI.hinweis.checked == true){attributes = attributes+'hinweis|'}
		if(document.GUI.baulasten != undefined && document.GUI.baulasten.checked == true){attributes = attributes+'baulasten|'}
		if(document.GUI.amtsgerichtname != undefined && document.GUI.amtsgerichtname.checked == true){attributes = attributes+'amtsgerichtname|'}
		if(document.GUI.amtsgerichtnr != undefined && document.GUI.amtsgerichtnr.checked == true){attributes = attributes+'amtsgerichtnr|'}
		if(document.GUI.grundbuchbezirkname != undefined && document.GUI.grundbuchbezirkname.checked == true){attributes = attributes+'grundbuchbezirkname|'}
		if(document.GUI.grundbuchbezirkschl != undefined && document.GUI.grundbuchbezirkschl.checked == true){attributes = attributes+'grundbuchbezirkschl|'}
		if(document.GUI.klassifizierung != undefined && document.GUI.klassifizierung.checked == true){attributes = attributes+'klassifizierung|'}
		document.GUI.attributes.value = attributes;
		document.GUI.go_plus.value = 'Auswahl_speichern';
		document.GUI.submit();
	}
	else{
		alert('Bitte geben Sie einen Namen für die Auswahl an.');
	}
}

function load_selection(){
	if(document.GUI.selection.value != ''){
		document.GUI.go_plus.value = 'Auswahl_laden';
		document.GUI.submit();
	}
	else{
		alert('Bitte wählen Sie eine Auswahl aus.');
	}
}

function delete_selection(){
	if(document.GUI.selection.value != ''){
		document.GUI.go_plus.value = 'Auswahl_loeschen';
		document.GUI.submit();
	}
	else{
		alert('Bitte wählen Sie eine Auswahl aus.');
	}
}

-->
</script>

<table width="100%" height="100" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="center" colspan="2" height="60" valign="bottom" align="left" >
    	<h3><?php echo $this->titel; ?></h3>
    </td>
  </tr>
  <tr>
  	<td align="center">Welche Attribute sollen zusätzlich ausgegeben werden?</td>
  </tr>
  <tr> 
    <td colspan="2" ><div align="center">
      <table border="0" cellspacing="0" cellpadding="4">
      <? for($i = 0; $i < count($this->attribute); $i++){
			      $privileg[$this->attribute[$i]] = true;
			    } 
			?>
        <tr>
          <td valign="top" width="250">
          <?
          if($privileg['amtsgerichtname']){ echo '<input  name="amtsgerichtname" type="checkbox" '; if($this->formvars['amtsgerichtname'] == 'true') echo 'checked'; echo '>Amtsgerichtname<br>';}
          if($privileg['amtsgerichtnr']){ echo '<input  name="amtsgerichtnr" type="checkbox" '; if($this->formvars['amtsgerichtnr'] == 'true') echo 'checked'; echo '>Amtsgerichtschlüssel<br>';}
          if($privileg['ausfstelle']){ echo '<input  name="ausfstelle" type="checkbox" '; if($this->formvars['ausfstelle'] == 'true') echo 'checked'; echo '>ausführende Stelle<br>';}
          if($privileg['baulasten']){ echo '<input  name="baulasten" type="checkbox" '; if($this->formvars['baulasten'] == 'true') echo 'checked'; echo '>Baulasten<br>';}
          if($privileg['bestandsnr']){ echo '<input  name="blattnr" type="checkbox" '; if($this->formvars['blattnr'] == 'true') echo 'checked'; echo '>Blattnummer<br>';}
          if($privileg['bestandsnr']){ echo '<input  name="buchungsart" type="checkbox" '; if($this->formvars['buchungsart'] == 'true') echo 'checked'; echo '>Buchungsart<br>';}
          if($privileg['bestandsnr']){ echo '<input  name="bvnr" type="checkbox" '; if($this->formvars['bvnr'] == 'true') echo 'checked'; echo '>Bestandsverzeichnisnummer<br>';}
          if($privileg['entsteh']){ echo '<input  name="entsteh" type="checkbox" '; if($this->formvars['entsteh'] == 'true') echo 'checked'; echo '>Entstehung<br>';}
          if($this->formvars['formnummer'] != 'Eigentümer' AND $privileg['eigentuemer']){ echo '<input  name="eigentuemer" type="checkbox" '; if($this->formvars['eigentuemer'] == 'true') echo 'checked'; echo '>Eigentümer<br>';}
          if($privileg['finanzamtname']){ echo '<input  name="finanzamtname" type="checkbox" '; if($this->formvars['finanzamtname'] == 'true') echo 'checked'; echo '>Finanzamtname<br>';}
			    if($privileg['finanzamt']){ echo '<input  name="finanzamt" type="checkbox" '; if($this->formvars['finanzamt'] == 'true') echo 'checked'; echo '>Finanzamtschlüssel<br>';}
			    if($privileg['karte']){ echo '<input  name="karte" type="checkbox" '; if($this->formvars['karte'] == 'true') echo 'checked'; echo '>Flurkarte<br>';}
			    if($privileg['flurnr']){ echo '<input  name="flurnr" type="checkbox" '; if($this->formvars['flurnr'] == 'true') echo 'checked'; echo '>Flurnummer<br>';}
			    if($privileg['flaeche']){ echo '<input  name="flaeche" type="checkbox" '; if($this->formvars['flaeche'] == 'true') echo 'checked'; echo '>Flurstücksfläche<br>';}
          if($privileg['flurstkennz']){ echo '<input  name="flurstkennz" checked="true" type="checkbox" '; if($this->formvars['flurstkennz'] == 'true') echo 'checked'; echo '>Flurstückskennzeichen<br>';}
          if($privileg['forstname']){ echo '<input  name="forstname" type="checkbox" '; if($this->formvars['forstname'] == 'true') echo 'checked'; echo '>Forstamtname<br>';}
			    if($privileg['forstschluessel']){ echo '<input  name="forstschluessel" type="checkbox" '; if($this->formvars['forstschluessel'] == 'true') echo 'checked'; echo '>Forstamtschlüssel<br>';}
			    if($privileg['letzff']){ echo '<input  name="letzff" type="checkbox" '; if($this->formvars['letzff'] == 'true') echo 'checked'; echo '>Fortführung<br>';}
			    ?>
          </td>
          <td valign="top">
          <?
			    if($privileg['freitext']){ echo '<input  name="freitext" type="checkbox" '; if($this->formvars['freitext'] == 'true') echo 'checked'; echo '>Freitext<br>';}
			    if($privileg['gemkgname']){ echo '<input  name="gemkgname" type="checkbox" '; if($this->formvars['gemkgname'] == 'true') echo 'checked'; echo '>Gemarkungsname<br>';}
			    if($privileg['gemkgschl']){ echo '<input  name="gemkgschl" type="checkbox" '; if($this->formvars['gemkgschl'] == 'true') echo 'checked'; echo '>Gemarkungsschlüssel<br>';}
			    if($privileg['gemeinde']){ echo '<input  name="gemeinde" type="checkbox" '; if($this->formvars['gemeinde'] == 'true') echo 'checked'; echo '>Gemeinde<br>';}
			    if($privileg['gemeindename']){ echo '<input  name="gemeindename" type="checkbox" '; if($this->formvars['gemeindename'] == 'true') echo 'checked'; echo '>Gemeindename<br>';}
			    if($privileg['grundbuchbezirkname']){ echo '<input  name="grundbuchbezirkname" type="checkbox" '; if($this->formvars['grundbuchbezirkname'] == 'true') echo 'checked'; echo '>Grundbuchbezirkname<br>';}
			    if($privileg['grundbuchbezirkschl']){ echo '<input  name="grundbuchbezirkschl" type="checkbox" '; if($this->formvars['grundbuchbezirkschl'] == 'true') echo 'checked'; echo '>Grundbuchbezirkschlüssel<br>';}
			    if($privileg['hinweis']){ echo '<input  name="hinweis" type="checkbox" '; if($this->formvars['hinweis'] == 'true') echo 'checked'; echo '>Hinweis<br>';}
			    if($privileg['lagebezeichnung']){ echo '<input  name="lagebezeichnung" type="checkbox" '; if($this->formvars['lagebezeichnung'] == 'true') echo 'checked'; echo '>Lage<br>';}
			    if($privileg['klassifizierung']){ echo '<input  name="klassifizierung" type="checkbox" '; if($this->formvars['klassifizierung'] == 'true') echo 'checked'; echo '>Klassifizierung<br>';}
			    if($privileg['kreisname']){ echo '<input  name="kreisname" type="checkbox" '; if($this->formvars['kreisname'] == 'true') echo 'checked'; echo '>Kreisname<br>';}
			    if($privileg['kreisid']){ echo '<input  name="kreisid" type="checkbox" '; if($this->formvars['kreisid'] == 'true') echo 'checked'; echo '>Kreisschlüssel<br>';}
			    if($this->formvars['formnummer'] != 'Nutzungsarten' AND $privileg['nutzung']){ echo '<input  name="nutzung" type="checkbox" '; if($this->formvars['nutzung'] == 'true') echo 'checked'; echo '>Nutzung<br>';}
			    if($privileg['bestandsnr']){ echo '<input  name="pruefzeichen" type="checkbox" '; if($this->formvars['pruefzeichen'] == 'true') echo 'checked'; echo '>Prüfzeichen Buchung<br>';}
			    if($privileg['bestandsnr']){ echo '<input  name="pruefzeichen_f" type="checkbox" '; if($this->formvars['pruefzeichen_f'] == 'true') echo 'checked'; echo '>Prüfzeichen Flurstück<br>';}
			    if($privileg['status']){ echo '<input  name="status" type="checkbox" '; if($this->formvars['status'] == 'true') echo 'checked'; echo '>Status<br>';}
			    if($privileg['verfahren']){ echo '<input  name="verfahren" type="checkbox" '; if($this->formvars['verfahren'] == 'true') echo 'checked'; echo '>Verfahren<br>';}
			    if($privileg['vorgaenger']){ echo '<input  name="vorgaenger" type="checkbox" '; if($this->formvars['vorgaenger'] == 'true') echo 'checked'; echo '>Vorgänger<br>';}			    
			    ?>
          </td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr align="center"> 
			    <td colspan="2"  align="left"> 
			      Attributauswahl
			    	<input type="text" name="name" value="<? echo $this->selection['name']; ?>" style="width:220px" >&nbsp;<input class="button" type="button" style="width:84px" name="speichern" value="speichern" onclick="save_selection();">
			    </td>
			  </tr>
			  <tr>
			  	<td align="left"  colspan="2">
			  		<input class="button" type="button" style="width:84px" name="delete" value="löschen" onclick="delete_selection();">&nbsp;			
			  		<select name="selection" onchange="load_selection();" style="width:220px">
			  			<option value="">-- Auswahl --</option>
			  			<?
			  				for($i = 0; $i < count($this->attribute_selections); $i++){
			  					echo '<option value="'.$this->attribute_selections[$i]['name'].'" ';
			  					if($this->selection['name'] == $this->attribute_selections[$i]['name'])echo 'selected';
			  					echo ' >'.$this->attribute_selections[$i]['name'].'</option>';
			  				}
			  			?>
			  		</select>
			    </td>
			  </tr>
        
      </table>
      </div>
	</td>
  </tr>
   <tr> 
    <td align="center" >
    	<input type="button" name="zurück" onclick="back();" value="zurück">&nbsp;
    	<input type="button" name="exportieren" onclick="browser_switch();" value="Exportieren">
    </td>
  </tr>
</table>

<input type="hidden" name="FlurstKennz" value="<? echo $this->formvars['FlurstKennz']; ?>"></td>
<input type="hidden" name="formnummer" value="<? echo $this->formvars['formnummer']; ?>"></td>
<input type="hidden" name="go" value="<? echo $this->formvars['go']; ?>"></td>
<input type="hidden" name="go_plus" value="Exportieren"></td>
<input type="hidden" name="attributes" value=""></td>
<input type="hidden" name="attributliste" value="<? echo $this->formvars['attributliste']; ?>"></td>
<input name="querypolygon" type="hidden" value="<?php echo $this->querypolygon; ?>">
<input name="rectminx" type="hidden" value="<?php echo $this->formvars['rectminx'] ? $this->formvars['rectminx'] : $this->queryrect->minx; ?>">
<input name="rectminy" type="hidden" value="<?php echo $this->formvars['rectminy'] ? $this->formvars['rectminy'] : $this->queryrect->miny; ?>">
<input name="rectmaxx" type="hidden" value="<?php echo $this->formvars['rectmaxx'] ? $this->formvars['rectmaxx'] : $this->queryrect->maxx; ?>">
<input name="rectmaxy" type="hidden" value="<?php echo $this->formvars['rectmaxy'] ? $this->formvars['rectmaxy'] : $this->queryrect->maxy; ?>">


<?
		$this->qlayerset=$this->user->rolle->getLayer('');
    $anzLayer=count($this->qlayerset);
  	for($i = 0; $i < $anzLayer; $i++){
  		if($this->formvars['qLayer'.$this->qlayerset[$i]['Layer_ID']] == 1){
  			echo '<input name="qLayer'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="1">';
  			echo '<input id="offset_'.$this->qlayerset[$i]['Layer_ID'].'" name="offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
  			echo '<input name="sql_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->qlayerset[$i]['sql'].'">';
  		}
  	}

  	if($this->formvars['GemkgID'] != ''){			# wenn man von der Suche kam -> Hidden Felder zum Speichern der Suchparameter
  		echo '<input type="hidden" name="selFlstID" value="'.$this->formvars['selFlstID'].'">';
  		echo '<input type="hidden" name="GemID" value="'.$this->formvars['GemID'].'">';
  		echo '<input type="hidden" name="GemkgID" value="'.$this->formvars['GemkgID'].'">';
  		echo '<input type="hidden" name="FlstNr" value="'.$this->formvars['FlstNr'].'">';
  		echo '<input type="hidden" name="FlurID" value="'.$this->formvars['FlurID'].'">';
  	}
  ?>



