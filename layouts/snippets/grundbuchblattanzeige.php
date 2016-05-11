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
$this->Stelle->getFunktionen();
for($gb = 0; $gb < count($this->gbblaetter); $gb++){
	$this->buchungen = $this->gbblaetter[$gb];
	$alle_flst = array();
	echo '<br><h2>'.$this->titel.' '.$this->buchungen[0]['bezirk'].'-'.$this->buchungen[0]['blatt'].'</h2><br>';
	$currenttime=date('Y-m-d H:i:s',time());
	$this->user->rolle->setConsumeALB($currenttime, 'Grundbuchblattanzeige', array($this->buchungen[0]['bezirk'].'-'.$this->buchungen[0]['blatt']), 0, 'NULL');		# das Grundbuchblattkennzeichen wird geloggt
  $anzObj=count($this->buchungen);
  if ($anzObj>0) {?>
	<table border="1" cellspacing="0" cellpadding="2" style="width:1000px">
	  <tr bgcolor="<?php echo BG_DEFAULT ?>">
	    <th colspan="3">Buchung</th>
	    <th colspan="6">Flurst&uuml;ck</th>
      </tr>
	  <tr bgcolor="<?php echo BG_DEFAULT ?>">
	    <th>BVNR</th>
	    <th>EBRH</th>
	    <th style="width:250px">Eigentümer</th>
	    <th>Lage</th>
	    <th width="250">Nutzung</th>
	    <th>Gemarkung</th>
	    <th>Flur</th>
	    <th>Flst.</th>
	    <th>&nbsp;</th>
	  </tr>
	  <?
	  $flst=new flurstueck($this->buchungen[0]['flurstkennz'],$this->pgdatabase);
	  $alle_flst[] = $this->buchungen[0]['flurstkennz'];
	  $ret=$flst->readALB_Data($this->buchungen[0]['flurstkennz']);
    $Eigentuemerliste=$flst->getEigentuemerliste($this->buchungen[0]['bezirk'],$this->buchungen[0]['blatt'],$this->buchungen[0]['bvnr']);
    $Eigentuemer = '';
    for ($i=0;$i<count($Eigentuemerliste);$i++) {
    	$Eigentuemer .= '<tr><td valign="top">'.$Eigentuemerliste[$i]->Nr.'</td><td>';
    	for ($k=0;$k<count($Eigentuemerliste[$i]);$k++) {
      	$Eigentuemer .= $Eigentuemerliste[$i]->Name[$k].'<br>';
    	}			
			if($Eigentuemerliste[$i]->zusatz_eigentuemer != ''){
				$Eigentuemer .= '</td></tr><tr><td colspan="2">'.$Eigentuemerliste[$i]->zusatz_eigentuemer; if($Eigentuemerliste[$i]->Anteil != '')$Eigentuemer .= ' zu '.$Eigentuemerliste[$i]->Anteil; $Eigentuemer .= '</td></tr><tr><td>';
			}
			elseif($Eigentuemerliste[$i]->Anteil)$Eigentuemer .= 'zu '.$Eigentuemerliste[$i]->Anteil.'<br>';
    	$Eigentuemer .= '</td></tr>';
    }
    $Adressbezeichnung = '';
    for($s=0;$s<count($flst->Adresse);$s++) {
      $Adressbezeichnung.=$flst->Adresse[$s]["strassenname"];
      $Adressbezeichnung.='&nbsp;'.$flst->Adresse[$s]["hausnr"];
    }
    for($s=0;$s<count($flst->Lage);$s++) {
      $Adressbezeichnung .= '<br>'.$flst->Lage[$s];
    }
    $Nutzunglangtext = '';
    for($i=0;$i<count($flst->Nutzung);$i++) {
    	if($flst->Nutzung[$i]['bezeichnung'] != ''){
    		$Nutzunglangtext.=$flst->Nutzung[$i]['flaeche'].'m<sup>2</sup> '.$flst->Nutzung[$i]['bezeichnung'].'<br>';
    	}
    }
		$index = 0;
		$rowspan[$index] = 0;
		for($i=0;$i<$anzObj;$i++){
			if($this->buchungen[$index]['bvnr'] == $this->buchungen[$i]['bvnr']){
				$rowspan[$index]++;
			}
			else{
				$index = $i;
				$rowspan[$index] = 1;
			}
		}
		
	  ?>
	  <tr>
      <td valign="top" align="center" <? if($rowspan[0] > 1)echo 'rowspan="'.$rowspan[0].'"' ?>>
			<? echo $this->buchungen[0]['bvnr'];
			if($this->Stelle->funktionen['MV0600']['erlaubt']){ ?>&nbsp;<a href="index.php?go=ALKIS_Auszug&formnummer=MV0600&Buchungsstelle=<? echo $this->buchungen[0]['gml_id'] ?>" target="_blank">Grundstücksnachweis</a>&nbsp;<? } ?>
			</td>
      <td valign="top" align="center"><?php echo '&nbsp;'.$this->buchungen[0]['erbbaurechtshinw'];?></td>
      <td style="width:250px" valign="top" rowspan="<? echo $anzObj; ?>">
      	<table>
      		<? echo $Eigentuemer; ?>
      	</table>
      </td>
    	<td valign="top"><? echo $Adressbezeichnung.'&nbsp;'; ?></td>
    	<td valign="top"><? echo $Nutzunglangtext.'&nbsp;'; ?></td>
      <td valign="top" align="center"><?php echo $this->buchungen[0]['gemkgname']; ?></td>
      <td valign="top" align="center"><?php echo $this->buchungen[0]['flur']; ?></td>
      <td valign="top" align="center">
      	<? echo $this->buchungen[0]['flurstuecksnr'];
					if($this->buchungen[0]['sondereigentum'] != ''){
      			echo '<br><br>'.$this->buchungen[0]['anteil'].' Miteigentumsanteil an '.$this->buchungen[0]['sondereigentum'];
      		}
      		elseif($this->buchungen[0]['anteil'] != ''){
      			echo '<br><br>zu '.$this->buchungen[0]['anteil'];
      		}
      	?>
      </td>
      <td valign="top" align="center"><a href="javascript:flurstanzeige('<?php echo $this->buchungen[0]['flurstkennz']; ?>');" title="Flurstücksdaten anzeigen">anzeigen</a></td>
	  </tr>
	  <?php
	  for ($i=1;$i<$anzObj;$i++) {
	  	$Nutzunglangtext = '';
	  	$Adressbezeichnung = '';
	  	$flst=new flurstueck($this->buchungen[$i]['flurstkennz'],$this->pgdatabase);
	  	$alle_flst[] = $this->buchungen[$i]['flurstkennz'];
	  	$ret=$flst->readALB_Data($this->buchungen[$i]['flurstkennz']);
	  	for($s=0;$s<count($flst->Adresse);$s++) {
	      $Adressbezeichnung.=$flst->Adresse[$s]["strassenname"];
	      $Adressbezeichnung.='&nbsp;'.$flst->Adresse[$s]["hausnr"];
	    }
	    for($s=0;$s<count($flst->Lage);$s++) {
      	$Adressbezeichnung .= '<br>'.$flst->Lage[$s];
    	}
	    for($n=0;$n<count($flst->Nutzung);$n++) {
	    	if($flst->Nutzung[$n]['bezeichnung'] != ''){
	    		$Nutzunglangtext.=$flst->Nutzung[$n]['flaeche'].'m<sup>2</sup> '.$flst->Nutzung[$n]['bezeichnung'].'<br>';
	    	}
	    }
	    ?><tr>
				<? if($rowspan[$i] != ''){ ?>
	      <td valign="top" align="center" <? if($rowspan[$i] > 1)echo 'rowspan="'.$rowspan[$i].'"' ?>>
					<? echo $this->buchungen[$i]['bvnr'];
					if($this->Stelle->funktionen['MV0600']['erlaubt']){ ?>&nbsp;<a href="index.php?go=ALKIS_Auszug&formnummer=MV0600&Buchungsstelle=<? echo $this->buchungen[$i]['gml_id'] ?>" target="_blank">Grundstücksnachweis</a>&nbsp;<? } ?>
				</td>
				<? } ?>
	      <td valign="top" align="center"><?php echo '&nbsp;'.$this->buchungen[$i]['erbbaurechtshinw'];?></td>
	    	<td valign="top"><? echo $Adressbezeichnung.'&nbsp;'; ?></td>
	    	<td valign="top"><? echo $Nutzunglangtext.'&nbsp;'; ?></td>
	      <td valign="top" align="center"><?php echo $this->buchungen[$i]['gemkgname']; ?></td>
	      <td valign="top" align="center"><?php echo $this->buchungen[$i]['flur']; ?></td>
	      <td valign="top" align="center">
      	<? echo $this->buchungen[$i]['flurstuecksnr'];
      		if($this->buchungen[$i]['anteil'] != ''){
      			echo '<br><br>zu '.$this->buchungen[$i]['anteil'];
      		} 
      	?>
      </td>
	      <td valign="top" align="center"><a href="javascript:flurstanzeige('<?php echo $this->buchungen[$i]['flurstkennz']; ?>');" title="Flurstücksdaten anzeigen">anzeigen</a></td>
	  </tr>
	  <?php }
	  if(count($alle_flst) > 1){ ?>
	  <tr>
	  	<td colspan="7">&nbsp;</td>
	  	<td colspan="2" align="center"><a href="javascript:flurstanzeige('<?php echo implode(';', $alle_flst); ?>');" title="Flurstücksdaten anzeigen">alle anzeigen</a></td>
	  </tr>
	  <? } ?>
</table>
<table border="0" cellspacing="0" cellpadding="2">
   <tr><td colspan="8">&nbsp;</td></tr>
   <tr>
     <td colspan="8">
       <table widt="100%" border="0" cellspacing="0" cellpadding="2">
         <tr align="center" bgcolor="<?php echo BG_DEFAULT ?>">
           <td>
            	<? if($this->Stelle->funktionen['MV0700']['erlaubt']){ ?>&nbsp;<a href="index.php?go=ALKIS_Auszug&formnummer=MV0700&Grundbuchbezirk=<? echo $this->buchungen[0]['bezirk'] ?>&Grundbuchblatt=<? echo $this->buchungen[0]['blatt'] ?>" target="_blank">Bestandsnachweis</a>&nbsp;<? } ?>
           </td>
					 <td>
            	<? if($this->Stelle->funktionen['ALB-Auszug 20']['erlaubt']){ ?>|&nbsp;<a href="index.php?go=ALB_Anzeige_Bestand&formnummer=20&Grundbuchbezirk=<? echo $this->buchungen[0]['bezirk'] ?>&Grundbuchblatt=<? echo $this->buchungen[0]['blatt'] ?>&wz=1" target="_blank">Bestandsdaten</a>&nbsp;<? } ?>
              <? if($this->Stelle->funktionen['ALB-Auszug 25']['erlaubt']){ ?>|&nbsp;<a href="index.php?go=ALB_Anzeige_Bestand&formnummer=25&Grundbuchbezirk=<? echo $this->buchungen[0]['bezirk'] ?>&Grundbuchblatt=<? echo $this->buchungen[0]['blatt'] ?>&wz=1" target="_blank">&Uuml;bersicht&nbsp;Bestandsdaten</a>&nbsp;<? } ?>
           </td>
         </tr>
       </table>
     </td>
   </tr>
   <tr><td colspan="8">&nbsp;</td></tr>
 </table>
 <?  }
  else {?>
   <br><span class="fett" style="color:"#FF0000">Es wurden keine Daten gefunden.</span><br>
 <? }
} ?>
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