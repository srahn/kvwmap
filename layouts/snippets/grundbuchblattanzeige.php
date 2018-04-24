<script type="text/javascript">
<!--

function backto_namesearch(){
	document.GUI.go.value="Namen_Auswaehlen_Suchen";
	document.GUI.submit();
}

function backto_gbbsearch(){
	document.GUI.go.value="Grundbuchblatt_Auswaehlen";
	document.GUI.submit();
}

function flurstanzeige(flurstkennz){
	document.GUI.FlurstKennz.value = flurstkennz;
	document.GUI.go.value = 'Flurstueck_Anzeigen';
	document.GUI.submit();
}

//-->
</script>

<?php
$alle_flst = array();
$this->Stelle->getFunktionen();
for($gb = 0; $gb < count($this->gbblaetter); $gb++){
	$this->buchungen = $this->gbblaetter[$gb];
	$alle_flst_pro_buchung = array();	
	$currenttime=date('Y-m-d H:i:s',time());
	$this->user->rolle->setConsumeALB($currenttime, 'Grundbuchblattanzeige', array($this->buchungen[0]['bezirk'].'-'.$this->buchungen[0]['blatt']), 0, 'NULL');		# das Grundbuchblattkennzeichen wird geloggt
  $anzObj=count($this->buchungen);
  if ($anzObj>0) { 
		$flst=new flurstueck($this->buchungen[0]['flurstkennz'],$this->pgdatabase);
	  $ret=$flst->readALB_Data($this->buchungen[0]['flurstkennz']);
    $Eigentuemerliste=$flst->getEigentuemerliste($this->buchungen[0]['bezirk'],$this->buchungen[0]['blatt'],$this->buchungen[0]['bvnr']);
    $Eigentuemer = '<tr><td colspan="2" valign="top" style="height: 16px">Eigentümer:</td></tr>';
		reset($Eigentuemerliste);
    $Eigentuemer .= $flst->outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 'Short');
		$Eigentuemer .= '<tr><td style="height: 100%"></td></tr>';
	?>
	<table cellspacing="0" cellpadding="2" id="gbb_grundbuchblatt">
	  <tr>
	    <th colspan="2"><h2><? echo $this->titel.' '.$this->buchungen[0]['bezirk'].'-'.$this->buchungen[0]['blatt']; ?></h2></th>
    </tr>
		<tr>
			<td style="padding-bottom: 6px;height:100%">
				<table id="gbb_eigentuemer">
					<? echo $Eigentuemer; ?>
				</table>
			</td>
			<td style="height:100%">
				<table cellpadding="0" cellspacing="0" style="height:100%">
	  <?		
	  for ($i=0;$i<$anzObj;$i++) {
	  	$Nutzunglangtext = '';
	  	$Adressbezeichnung = '';
	  	$flst=new flurstueck($this->buchungen[$i]['flurstkennz'],$this->pgdatabase);
	  	$alle_flst_pro_buchung[] = $this->buchungen[$i]['flurstkennz'];
			$alle_flst[] = $this->buchungen[$i]['flurstkennz'];
	  	$ret=$flst->readALB_Data($this->buchungen[$i]['flurstkennz']);
	  	for($s=0;$s<count($flst->Adresse);$s++) {
	      $Adressbezeichnung.=$flst->Adresse[$s]["strassenname"];
	      $Adressbezeichnung.='&nbsp;'.$flst->Adresse[$s]["hausnr"];
	    }
	    for($s=0;$s<count($flst->Lage);$s++) {
      	$Adressbezeichnung .= $flst->Lage[$s];
    	}
	    for($n=0;$n<count($flst->Nutzung);$n++) {
	    	if($flst->Nutzung[$n]['bezeichnung'] != ''){
	    		$Nutzunglangtext.=tausenderTrenner($flst->Nutzung[$n]['flaeche']).' m<sup>2</sup> '.$flst->Nutzung[$n]['bezeichnung'].'<br>';
	    	}
	    }
			if($this->buchungen[$i-1]['bvnr'] != $this->buchungen[$i]['bvnr']){
						if($i > 0){ ?>
							</table></tr></td>
						<? } ?>
						<tr><td style="padding-bottom: 6px"><div id="gbb_grundstueck"><table style="padding: 7px">
							<tr>
								<td style="height: 50px" valign="top">
									Bestandsverzeichnisnummer: <? echo $this->buchungen[$i]['bvnr']; ?>
								</td>
								<td style="height: 50px" valign="top">
									<? if($this->Stelle->funktionen['MV0600']['erlaubt']){ ?>&nbsp;<a href="index.php?go=ALKIS_Auszug&formnummer=MV0600&Buchungsstelle=<? echo $this->buchungen[$i]['gml_id'] ?>" target="_blank">Grundstücksnachweis</a>&nbsp;<? } ?>
								</td>
							</tr>
							<tr>
								<td style="height: 30px">Flurstücke</td>
							</tr>
				<? } ?>
							<tr>
								<td>
									<a href="javascript:flurstanzeige('<?php echo $this->buchungen[$i]['flurstkennz']; ?>');" title="Flurstücksdaten anzeigen">
								<? echo $this->buchungen[$i]['gemkgname'].', Flur '.$this->buchungen[$i]['flur'].', '.$this->buchungen[$i]['flurstuecksnr']; ?>
									</a>
								<? echo '<br><br>'.$this->buchungen[$i]['buchungsart'].' - '.$this->buchungen[$i]['bezeichnung'] ?>
								<? if($this->buchungen[$i]['anteil'] != ''){
										if($this->buchungen[$i]['anteil'] == '99999/99999')echo '<br>Anteil nicht ermittelbar an Miteigentumsanteil am Grundstück';
										else echo 'zu '.$this->buchungen[$i]['anteil'];
									 } ?>
								<? if(in_array($this->buchungen[$i]['buchungsart'], array(2101, 2203, 2303)))echo ' - belastet mit Erbbaurecht - '; if(in_array($this->buchungen[$i]['buchungsart'], array(2103)))echo ' - belastet mit Nutzungsrecht - '; ?>
								</td>
								<td valign="top">
									<? echo $Adressbezeichnung; ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<? echo $Nutzunglangtext; ?>
									<br>
								</td>
							</tr>
	  <? } ?>
					</table></div></td></tr></table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<table border="0" cellspacing="0" cellpadding="3">
						<tr align="center">
							<td>	<? 
								if(count($alle_flst_pro_buchung) > 1){ ?><a href="javascript:flurstanzeige('<?php echo implode(';', $alle_flst_pro_buchung); ?>');" title="Flurstücksdaten anzeigen">alle Flurstücke anzeigen</a>&nbsp;|<? } 
								if($this->Stelle->funktionen['MV0700']['erlaubt']){ ?>&nbsp;<a href="index.php?go=ALKIS_Auszug&formnummer=MV0700&Grundbuchbezirk=<? echo $this->buchungen[0]['bezirk'] ?>&Grundbuchblatt=<? echo $this->buchungen[0]['blatt'] ?>" target="_blank">Bestandsnachweis</a>&nbsp;<? }							
								if($this->Stelle->funktionen['ALB-Auszug 20']['erlaubt']){ ?>|&nbsp;<a href="index.php?go=ALB_Anzeige_Bestand&formnummer=20&Grundbuchbezirk=<? echo $this->buchungen[0]['bezirk'] ?>&Grundbuchblatt=<? echo $this->buchungen[0]['blatt'] ?>&wz=1" target="_blank">Bestandsdaten</a>&nbsp;<? }
								if($this->Stelle->funktionen['ALB-Auszug 25']['erlaubt']){ ?>|&nbsp;<a href="index.php?go=ALB_Anzeige_Bestand&formnummer=25&Grundbuchbezirk=<? echo $this->buchungen[0]['bezirk'] ?>&Grundbuchblatt=<? echo $this->buchungen[0]['blatt'] ?>&wz=1" target="_blank">&Uuml;bersicht&nbsp;Bestandsdaten</a>&nbsp;<? } ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
</table>
 <?  }
  else {?>
   <br><span class="fett" style="color:"#FF0000">Es wurden keine Daten gefunden.</span><br>
 <? }
} 

if(count($alle_flst) > 1){ ?><a href="javascript:flurstanzeige('<?php echo implode(';', $alle_flst); ?>');" title="Flurstücksdaten anzeigen">alle Flurstücke anzeigen</a><br><br><? } 

?>
<a href="javascript:backto_gbbsearch();">zurück zur Grundbuchblattsuche</a>
<br>
<br>

 <input type="hidden" name="FlurstKennz" value="">
 <input name="grundbuchsuche" type="hidden" value="true">
 <input name="selBlatt" type="hidden" value="<? echo $this->formvars['selBlatt']; ?>">
 <input name="Bezirk" type="hidden" value="<? echo $this->formvars['Bezirk']; ?>">
 <input name="go" type="hidden" value="">

 <?
 if($this->formvars['namensuche'] == 'true'){
 ?>
 	<input name="name1" type="hidden" value="<? echo $this->formvars['name1']; ?>">
  <input name="name2" type="hidden" value="<? echo $this->formvars['name2']; ?>">
  <input name="name3" type="hidden" value="<? echo $this->formvars['name3']; ?>">
  <input name="name4" type="hidden" value="<? echo $this->formvars['name4']; ?>">
  <input name="bezirk" type="hidden" value="<? echo $this->formvars['bezirk']; ?>">
  <input name="blatt" type="hidden" value="<? echo $this->formvars['blatt']; ?>">
  <input name="GemkgID" type="hidden" value="<? echo $this->formvars['GemkgID']; ?>">
  <input name="offset" type="hidden" value="<? echo $this->formvars['offset']; ?>">
	<input name="order" type="hidden" value="<? echo $this->formvars['order'] ?>">
	<input name="anzahl" type="hidden" value="<?php echo $this->formvars['anzahl']; ?>">
  <a href="javascript:backto_namesearch();">zurück zur Namensuche</a>
 <?}?>