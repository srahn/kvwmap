<?
	include_once(SNIPPETS.'sachdatenanzeige_functions.php'); 
?>
<script language="JavaScript" type="text/javascript">

send_selected_flurst = function(go, formnummer, wz, target){
	currentform.go_backup.value=currentform.go.value;
  var semi = false;
  var flurstkennz = "";
  var flurstarray = document.getElementsByName("check_flurstueck");
  for(i = 0; i < flurstarray.length; i++){
    if(flurstarray[i].checked == true){
      if(semi == true){
        flurstkennz += ';';
      }
      flurstkennz += flurstarray[i].value;
      semi = true;
    }
  }
  currentform.target = '';
  if(target == '_blank'){
    currentform.target = '_blank';
  }
  currentform.go.value=go;
  currentform.FlurstKennz.value=flurstkennz;
  currentform.formnummer.value=formnummer;
  currentform.wz.value=wz;
  currentform.submit();
}

backto = function(go){
  currentform.go.value=go;
  currentform.submit();
}

show_all = function(count){
	currentform.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = 0;
	currentform.anzahl.value = count;
	currentform.submit();
}

</script>
<br>
<a name="anfang"></a>
<h2>Flurst&uuml;cke</h2>
<table border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td align="center">
<?php
	$timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $this->user->rolle->hist_timestamp);
	$sql = "SELECT max(beginnt)::date FROM alkis.ax_fortfuehrungsfall;";
  $ret=$this->pgdatabase->execSQL($sql,4,0);
  $aktalkis = pg_fetch_array($ret[1]);

	$this->Stelle->getFunktionen();
	$forall = false;
	if($i == '')$i = 0;
	$gesamt = $this->qlayerset[$i]['count'];
  $anzObj = count($this->qlayerset[$i]['shape']);
	if($gesamt == '')$gesamt = $anzObj;
	$von = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + 1;
	$bis = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + $this->formvars['anzahl'];
  if ($anzObj>0) { ?>
		<br>
		<span style="font-size:80%;">Stand ALKIS vom: <? echo $aktalkis[0]; ?><br></span>
		<br>
    <u><? echo $gesamt; ?> Flurstück<? if ($gesamt>1) { echo "e"; } ?> abgefragt</u>
		<? if($gesamt > $anzObj){ ?>
		&nbsp;<a href="javascript:show_all(<? echo $gesamt; ?>);">alle anzeigen</a>
    <br><br>
		<u>Flurstücke <? echo $von; ?> bis <? echo $bis; ?></u>
		<? } ?>
		:<br>

    <?
    for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
      if($this->qlayerset[$i]['attributes']['privileg'][$this->qlayerset[$i]['attributes']['name'][$j]] != ''){
        $privileg_[$this->qlayerset[$i]['attributes']['name'][$j]] = true;
        if($j > 0){ $attribute .= ';';}
        $attribute .= $this->qlayerset[$i]['attributes']['name'][$j];
      }
    }
		echo '<tr><td align="center"><table><tr><td align="left">';
    for ($a=0;$a<$anzObj;$a++){
      $flurstkennz_a=$this->qlayerset[$i]['shape'][$a]['flurstkennz'];
			$flst=new flurstueck($flurstkennz_a,$this->pgdatabase);
      $flst->readALB_Data($flurstkennz_a, $this->formvars['without_temporal_filter']);	# bei without_temporal_filter=true, wird unabhängig vom Zeitstempel abgefragt (z.B. bei der historischen Flurstückssuche oder Flst.-Listenimport oder beim Sprung zum Vorgänger/Nachfolger)
			$flst->Grundbuecher=$flst->getGrundbuecher();
			$flst->Buchungen=$flst->getBuchungen(NULL,NULL,$flst->hist_alb);
      $gemkg=substr($flurstkennz_a, 0, 6);
      $flur=substr($flurstkennz_a, 6, 3);
      $zaehler=ltrim(substr($flurstkennz_a, 9, 5), '0');
      $nenner=ltrim(rtrim(substr($flurstkennz_a, 14, 6), '_'), '0');
      if ($nenner!='') {
        $nenner="/".$nenner;
      }
			if($flst->FlurstNr){
				$flst_array[] = $flst;
				echo '<a href="#'.$flurstkennz_a.'">Gemarkung: '.$gemkg.' - Flur: '.ltrim($flur,"0").' - Flurst&uuml;ck: '.$zaehler.$nenner.'</a>';
				if($flst->Nachfolger != '')echo ' (H)';
				echo '<br>';
			}
			else{
				echo 'Gemarkung: '.$gemkg.' - Flur: '.ltrim($flur,"0").' - Flurst&uuml;ck: '.$zaehler.$nenner.' nicht gefunden<br>';
			}
    }
		echo '</td></tr></table></td></tr>';

    for ($k=0;$k<count($flst_array);$k++) {
			$set_timestamp = '';
      $flst = $flst_array[$k];
      if ($flst->FlurstNr!='') {
        if($k > 0){
          $Flurstueckskennz .= ';';
        }
        $Flurstueckskennz .= $flurstkennz;
?>

  <!-- Für jedes einzelne Flurstück -->
  <tr>
    <td colspan="2">&nbsp;<a name="<? echo $flst->FlurstKennz; ?>" href="#<? echo $flst->FlurstKennz; ?>"></a></td>
  </tr>
  <tr>
    <td>
    <div style="position:relative; top:0px; right:0px; padding:=px; border-color:<?php echo BG_DEFAULT ?>; border-width:1px; border-style:solid;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2">
          <table width="100%">
            <tr>
              <td align="left"><span style="font-size:80%;">auswählen</span></td>
              <td align="right"><a href="#anfang"><span style="font-size:80%;">>> nach oben</span></a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td valign="top">
          <input type="checkbox" name="check_flurstueck" value="<?php echo $flst->FlurstKennz; ?>" checked>
        </td>
        <td>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
          <table border="0" cellspacing="0" cellpadding="2">

              <? if($privileg_['flurstkennz']){ ?>
              <tr>
                <td align="right"><span class="fett">Flurst&uuml;ck&nbsp;</span></td>
                <td>
									<? echo $flst->FlurstNr; ?>&nbsp;(<?php echo $flst->Flurstkennz_alt; ?>)
									<? if(count($flst->Versionen) > 1){ ?>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											Versionen:
											<select name="versions_<? echo $k; ?>" onchange="location.href='index.php?go=setHistTimestamp&timestamp='+this.value" style="width: 87px">
												<? $selected = false; 
													 for($v = 0; $v < count($flst->Versionen); $v++){
														$beginnt = DateTime::createFromFormat('d.m.Y H:i:s', $flst->Versionen[$v]['beginnt']);
														$endet = DateTime::createFromFormat('d.m.Y H:i:s', $flst->Versionen[$v]['endet']);
														echo '<option ';
														if(
															($timestamp == NULL AND $endet == NULL) OR 																	# timestamp aktuell und letzte Version
															($timestamp >= $beginnt AND $timestamp <= $endet) OR												# timestamp liegt im Intervall
															($v == count($flst->Versionen)-1 AND $selected == false)										# timestamp außerhalb des Intervalls (Vorschau)
														){$selected = true; echo 'selected';}
														if($flst->Versionen[$v]['endet'] != '')echo ' value="'.$flst->Versionen[$v]['beginnt'].'">'.$flst->Versionen[$v]['beginnt'].'</option>';
														else echo ' value="">'.$flst->Versionen[$v]['beginnt'].'</option>';
													 }
												?>
											</select>
									<? }	?>
								</td>
              </tr>
              <? }
          $both = ($privileg_['gemkgname'] AND $privileg_['gemkgschl']);
          if($privileg_['gemkgname'] OR $privileg_['gemkgschl']){
              ?>
              <tr>
                <td align="right"><span class="fett">Gemarkung&nbsp;</span></td>
                <td><?php if($privileg_['gemkgname']){echo $flst->GemkgName;} ?> <?php if($both){ echo '('.$flst->GemkgSchl.')';} elseif($privileg_['gemkgschl']){ echo $flst->GemkgSchl;}?></td>
              </tr>
              <? }
              if($privileg_['flurnr']){ ?>
              <tr>
                <td height="20" align="right"><span class="fett">Flur&nbsp;</span></td>
                <td><?php echo $flst->FlurNr; ?></td>
              </tr>
              <? }
              $both = ($privileg_['gemeinde'] AND $privileg_['gemeindename']);
              if($privileg_['gemeinde'] OR $privileg_['gemeindename']){
              ?>
              <tr>
                <td align="right"><span class="fett">Gemeinde&nbsp;</span></td>
                <td><?php if($privileg_['gemeindename']){ echo $flst->GemeindeName;} ?> <?php if($both){ echo '('.$flst->GemeindeID.')';} elseif($privileg_['gemeinde']){ echo $flst->GemeindeID;} ?></td>
              </tr>
              <? }
              $both = ($privileg_['kreisname'] AND $privileg_['kreisid']);
              if($privileg_['kreisname'] OR $privileg_['kreisid']){
              ?>
              <tr>
                <td align="right"><span class="fett">Kreis&nbsp;</span></td>
                <td><?php if($privileg_['kreisname']){ echo $flst->KreisName;} ?> <?php if($both){echo '('.$flst->KreisID.')';} elseif($privileg_['kreisid']){ echo $flst->KreisID;} ?></td>
              </tr>
          <? }
          $both = ($privileg_['finanzamtname'] AND $privileg_['finanzamt']);
          if($privileg_['finanzamtname'] OR $privileg_['finanzamt']){
          ?>
              <tr>
                <td align="right"><span class="fett"> Finanzamt&nbsp;</span></td>
                <td><?php if($privileg_['finanzamtname']){ echo $flst->FinanzamtName;} ?> <?php if($both){echo '('.$flst->FinanzamtSchl.')';} elseif($privileg_['finanzamt']){ echo $flst->FinanzamtSchl; } ?></td>
              </tr>
          <? }
          $both = ($privileg_['forstname'] AND $privileg_['forstschluessel']);
          if($privileg_['forstname'] OR $privileg_['forstschluessel']){
          ?>
              <tr>
                <td align="right"><span class="fett">Forstamt&nbsp;</span></td>
                <td><?php if($privileg_['forstname']){echo $flst->Forstamt['name'];} ?> <?php if($both){echo '('.$flst->Forstamt['schluessel'].')';} elseif($privileg_['forstschluessel']){ echo $flst->Forstamt['schluessel'];} ?></td>
              </tr>
          <? }
          if($privileg_['flaeche']){ ?>
              <tr>
                <td align="right"><span class="fett">Fl&auml;che&nbsp;</span></td>
                <td><?php echo $flst->ALB_Flaeche; ?>m&sup2;</td>
              </tr>
          <? }
          $both = ($privileg_['amtsgerichtname'] AND $privileg_['amtsgerichtnr']);
          if($privileg_['amtsgerichtname'] OR $privileg_['amtsgerichtnr']){
          ?>
          <tr>
                <td align="right"><span class="fett">Amtsgericht</span>&nbsp;</td>
                <td><?php if($privileg_['amtsgerichtname']){ echo $flst->Amtsgericht['name'];} ?>&nbsp;<? if($privileg_['amtsgerichtnr']){echo '('.$flst->Amtsgericht['schluessel'].')';} ?></td>
              </tr>
              <? }
              $both = ($privileg_['grundbuchbezirkname'] AND $privileg_['grundbuchbezirkschl']);
          if($privileg_['grundbuchbezirkname'] OR $privileg_['grundbuchbezirkschl']){
          ?>
              <tr>
                <td align="right"><span class="fett">Grundbuchbezirk</span>&nbsp;</td>
                <td><?php if($privileg_['grundbuchbezirkname']){ echo $flst->Grundbuchbezirk['name'];} ?>&nbsp;<? if($privileg_['grundbuchbezirkschl']){ echo '('.$flst->Grundbuchbezirk['schluessel'].')';} ?></td>
              </tr>
          <? }
          if($privileg_['lagebezeichnung']){ ?>
              <tr>
                <td align="right" valign="top"><span class="fett">Lage&nbsp;</span></td>
                <td>
                <?php
                $anzStrassen=count($flst->Adresse);
                for ($s=0;$s<$anzStrassen;$s++) {
									$flst->selHausID[] = $flst->Adresse[$s]["gemeinde"].'-'.$flst->Adresse[$s]["strasse"].'-'.$flst->Adresse[$s]["hausnr"];	# für die Adressensuche
                  echo $flst->Adresse[$s]["gemeindename"]; ?><br><?php
                  echo $flst->Adresse[$s]["strassenname"]; ?>&nbsp;<?php
                  echo $flst->Adresse[$s]["hausnr"]; ?><br><?php
                }
                $anzLage=count($flst->Lage);
                $Lage='';
                for ($j=0;$j<$anzLage;$j++) {
                  $Lage.=' '.$flst->Lage[$j];
                }
                if ($Lage!='') {
                  ?><br><?php echo TRIM($Lage);
                }
              ?>
                </td>
              </tr>
          <? }
          if($privileg_['entsteh']){ ?>
              <tr>
                <td align="right"><span class="fett">Entstehung&nbsp;</span></td>
                <td><?php echo $flst->Entstehung; ?></td>
              </tr>
          <? }
          if($privileg_['letzff']){ ?>
              <tr>
                <td align="right"><span class="fett">Fortf&uuml;hrung&nbsp;</span></td>
                <td><?php echo $flst->LetzteFF; ?></td>
              </tr>
          <? }
          if($privileg_['karte']){ ?>
              <tr>
                <td align="right"><span class="fett"> Flurkarte/Ri&szlig;&nbsp;</span></td>
                <td><?php echo $flst->Flurkarte; ?></td>
              </tr>
              <? }
              if($privileg_['status']){ ?>
              <tr>
                <td align="right"><span class="fett"> Status&nbsp;</span></td>
                <td><?php 
									$beginnt = DateTime::createFromFormat('d.m.Y H:i:s', $flst->beginnt);
									$endet = DateTime::createFromFormat('d.m.Y H:i:s', $flst->endet);
									if($flst->Nachfolger != ''){
										echo "historisches&nbsp;Flurst&uuml;ck"; 
										if($flst->endet != ''){
											if($timestamp == NULL OR $timestamp < $beginnt OR $timestamp > $endet){
												$set_timestamp = 'setHistTimestamp&timestamp='.$flst->beginnt;
												echo '<a href="index.php?go='.$set_timestamp.'" title="in die Zeit des Flurstücks wechseln">&nbsp;(endet: '.$flst->endet.')</a>';
											}
											else echo "&nbsp;(endet: ".$flst->endet.")";
										} 
									} 
									else{
										if($timestamp != NULL AND $timestamp < $beginnt){
											$set_timestamp = 'setHistTimestamp';
											echo '<a href="index.php?go='.$set_timestamp.'" title="Zeitpunkt auf aktuell setzen">aktuelles&nbsp;Flurst&uuml;ck</a>';
										}
										else{
											echo "aktuelles&nbsp;Flurst&uuml;ck";
											if($flst->endet != '')echo ' (historische Version)';
										}
									}  ?></td>
              </tr>
              <? } ?>
              <?php if ($privileg_['vorgaenger'] AND $flst->Vorgaenger != '') { ?>
          <tr>
              <td align="right"><span class="fett">Vorgänger</span></td>
              <td>
                <?php
                for($v = 0; $v < count($flst->Vorgaenger); $v++){ ?>
                  <a href="javascript:overlay_link('go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Vorgaenger[$v]['vorgaenger']; ?>&without_temporal_filter=1');">
                  <? echo formatFlurstkennzALK($flst->Vorgaenger[$v]['vorgaenger']).' (H)<br>'; ?>
                  </a>
                <? } ?>
                </td>
                <td>
                <? if (count($flst->Vorgaenger) > 1){?>
                  <a href="javascript:overlay_link('go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Vorgaenger[0]['vorgaenger'];
                  for($v = 1; $v < count($flst->Vorgaenger); $v++)echo ';'.$flst->Vorgaenger[$v]['vorgaenger']; ?>&without_temporal_filter=1');">alle</a>
                <? } ?>
              </td>
          </tr>
          <? } ?>
          <?php if ($privileg_['nachfolger'] AND $flst->Nachfolger != '') { ?>
          <tr>
              <td align="right" valign="top"><span class="fett">Nachfolger</span></td>
              <td>
                <?php
                for($v = 0; $v < count($flst->Nachfolger); $v++){ ?>
                  <a href="javascript:overlay_link('go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Nachfolger[$v]['nachfolger']; ?>&without_temporal_filter=1');">
                  <? echo formatFlurstkennzALK($flst->Nachfolger[$v]['nachfolger']);
                  if($flst->Nachfolger[$v]['endet'] != '' OR $flst->Nachfolger[$v]['hist_alb'] != ''){
                    echo ' (H)';
                  }
                  echo '<br>';
                } ?>
                </a>
              </td>
              <td>
              <? if(count($flst->Nachfolger) > 1){ ?>
                <a href="javascript:overlay_link('go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Nachfolger[0]['nachfolger'];
                for($v = 1; $v < count($flst->Nachfolger); $v++)
                echo ';'.$flst->Nachfolger[$v]['nachfolger']; ?>&without_temporal_filter=1');">alle</a>
              <? } ?>
              </td>
          </tr>
          <?php } ?>

              <?
							global $kvwmap_plugins;
							if(in_array('probaug', $kvwmap_plugins) AND $this->Stelle->isFunctionAllowed('Bauakteneinsicht')){
								include_once(PLUGINS.'probaug/model/bau.php');
								$this->bau = new Bauauskunft($this->baudatabase);
								$searchvars['flurstkennz'] = $flst->Flurstkennz_alt;
								$this->bau->getbaudaten($searchvars);
								if(count($this->bau->baudata) != 0){
								?>
						<tr>
								<td align="right"><span class="fett"> Baudaten&nbsp;</span></td>
								<td><a href="index.php?go=Bauauskunft_Suche_Suchen&flurstkennz=<? echo $flst->Flurstkennz_alt; ?>&distinct=1">anzeigen</a></td>
							</tr>
								<?
								}
          } # ende Bauakteneinsicht
              ?>

            </table>
          </td>
        </tr>

        <? if($privileg_['klassifizierung']){
	        	if($flst->Klassifizierung[0]['wert'] != ''){
	        ?>
	        	<tr>
          		<td colspan="2">
            		<span class="fett">Gesetzl.&nbsp;Klassifizierung Bodensch&auml;tzung</span>
              </td>
            </tr>
            <tr>
                  <td>
		            <table border="0" cellspacing="0" cellpadding="2">
                    <colgroup>
                      <col width="120px">
                      <col width="*">
                      <col width="*">
                      <col width="*">
                    </colgroup>
		            <?  $emzges_a = 0; $emzges_gr = 0; $emzges_agr = 0; $emzges_gra = 0;
										$flaeche_a = 0; $flaeche_gr = 0; $flaeche_agr = 0; $flaeche_gra = 0;
		            	for($j = 0; $j < count($flst->Klassifizierung); $j++){
										if($flst->Klassifizierung[$j]['flaeche'] != ''){
											$wert=$flst->Klassifizierung[$j]['wert'];
											$emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
											if($flst->Klassifizierung[$j]['objart'] == 1000){
												$emzges_a = $emzges_a + $emz;
												$flaeche_a = $flaeche_a + $flst->Klassifizierung[$j]['flaeche'];
											}
											if($flst->Klassifizierung[$j]['objart'] == 2000){
												$emzges_agr = $emzges_agr + $emz;
												$flaeche_agr = $flaeche_agr + $flst->Klassifizierung[$j]['flaeche'];
											}
											if($flst->Klassifizierung[$j]['objart'] == 3000){
												$emzges_gr = $emzges_gr + $emz;
												$flaeche_gr = $flaeche_gr + $flst->Klassifizierung[$j]['flaeche'];
											}
											if($flst->Klassifizierung[$j]['objart'] == 4000){
												$emzges_gra = $emzges_gra + $emz;
												$flaeche_gra = $flaeche_gra + $flst->Klassifizierung[$j]['flaeche'];
											}
											?>
											<tr>
												<td></td>
												<td><? echo $flst->Klassifizierung[$j]['flaeche']; ?> m&sup2&nbsp;</td>
												<td><? echo $flst->Klassifizierung[$j]['label']; ?></td>
												<td>EMZ: <? echo $emz; ?></td><td>BWZ: <? echo $wert; ?></td>
											</tr>
								<?	}
									} // end for
		            if ($flst->Klassifizierung['nicht_geschaetzt'] > 0) { ?>
          				<tr>
          					<td></td>
          					<td colspan="3">nicht geschätzt: <? echo $flst->Klassifizierung['nicht_geschaetzt']; ?> m&sup2;</td>
          				</tr>
		            <? }
        			if ($emzges_a > 0)  {
        					$BWZ_a = round($emzges_a/$flaeche_a*100);
        					?>
          				<tr>
          					<td></td>
          					<td colspan="3">Ackerland gesamt: <? echo round($flaeche_a); ?>m², EMZ <? echo $emzges_a; ?>, BWZ <? echo $BWZ_a; ?></td>
          				</tr>
        			<?	}
        			if ($emzges_gr > 0) {
        					$BWZ_gr = round($emzges_gr/$flaeche_gr*100);
        					?>
          				<tr>
          					<td></td>
          					<td colspan="3">Grünland gesamt: <? echo round($flaeche_gr); ?>m², EMZ <? echo $emzges_gr; ?>, BWZ <? echo $BWZ_gr; ?></td>
          				</tr>
        			<?	}
							if ($emzges_agr > 0) {
        					$BWZ_agr = round($emzges_agr/$flaeche_agr*100);
        					?>
          				<tr>
          					<td></td>
          					<td colspan="3">Acker-Grünland gesamt: <? echo round($flaeche_agr); ?>m², EMZ <? echo $emzges_agr; ?>, BWZ <? echo $BWZ_agr; ?></td>
          				</tr>
        			<?	}
							if ($emzges_gra > 0) {
        					$BWZ_gra = round($emzges_gra/$flaeche_gra*100);
        					?>
          				<tr>
          					<td></td>
          					<td colspan="3">Grünland-Acker gesamt: <? echo round($flaeche_gra); ?>m², EMZ <? echo $emzges_gra; ?>, BWZ <? echo $BWZ_gra; ?></td>
          				</tr>
        			<?	} ?>
		          </table>
		          </td>
		         </tr>
	        <?
	        	}
        } ?>
				
				 <? if($privileg_['festlegungen'] AND (count($flst->Strassenrecht) > 0 OR count($flst->Wasserrecht) > 0 OR count($flst->Schutzgebiet) > 0 OR count($flst->NaturUmweltrecht) > 0 OR count($flst->BauBodenrecht) > 0 OR count($flst->Denkmalschutzrecht) > 0 OR count($flst->Forstrecht) > 0 OR count($flst->Sonstigesrecht) > 0)){
	        ?>
	        	<tr>
          		<td colspan="2">
            		<span class="fett">Öffentlich-rechtliche und sonstige Festlegungen</span>
              </td>
            </tr>
            <tr>
							<td>
		            <table border="0" cellspacing="0" cellpadding="2">
		            <?
								for($j = 0; $j < count($flst->Strassenrecht); $j++){
									echo '<tr><td valign="top">'.$flst->Strassenrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Strassenrecht[$j]['art'].': '.$flst->Strassenrecht[$j]['bezeichnung'].'</td></tr>';
		            }
								for($j = 0; $j < count($flst->Wasserrecht); $j++){
									echo '<tr><td valign="top">'.$flst->Wasserrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Wasserrecht[$j]['art'].': '.$flst->Wasserrecht[$j]['bezeichnung'].'</td></tr>';
		            }
								for($j = 0; $j < count($flst->Schutzgebiet); $j++){
									echo '<tr><td valign="top">'.$flst->Schutzgebiet[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Schutzgebiet[$j]['art'].'</td></tr>';
		            }
								for($j = 0; $j < count($flst->NaturUmweltrecht); $j++){
									echo '<tr><td valign="top">'.$flst->NaturUmweltrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->NaturUmweltrecht[$j]['art'].'</td></tr>';
		            }
								for($j = 0; $j < count($flst->BauBodenrecht); $j++){
									echo '<tr><td valign="top">'.$flst->BauBodenrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->BauBodenrecht[$j]['art'];
									if($flst->BauBodenrecht[$j]['bezeichnung'] != '')echo ': '.$flst->BauBodenrecht[$j]['bezeichnung'];
									if($flst->BauBodenrecht[$j]['stelle'] != '')echo ' ('.$flst->BauBodenrecht[$j]['stelle'].')';
									echo '</td></tr>';
		            }
								if($flst->abweichenderrechtszustand == 'true')echo '<tr><td colspan="2" width="600px">In einem durch Gesetz geregelten Verfahren der Bodenordnung ist für das Flurstück ein neuer Rechtszustand eingetreten. Die Festlegungen des Verfahrens sind noch nicht in das Liegenschaftskataster übernommen. Dieser Nachweis entspricht deshalb nicht dem aktuellen Stand.</td></tr>';
								for($j = 0; $j < count($flst->Denkmalschutzrecht); $j++){
									echo '<tr><td valign="top">'.$flst->Denkmalschutzrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Denkmalschutzrecht[$j]['art'].' '.$flst->Denkmalschutzrecht[$j]['name'].'</td></tr>';
		            }
								for($j = 0; $j < count($flst->Forstrecht); $j++){
									echo '<tr><td valign="top">'.$flst->Forstrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Forstrecht[$j]['art'].': '.$flst->Forstrecht[$j]['funktion'].'</td></tr>';
		            }
								for($j = 0; $j < count($flst->Sonstigesrecht); $j++){
									echo '<tr><td valign="top">'.$flst->Sonstigesrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Sonstigesrecht[$j]['art'].' '.$flst->Sonstigesrecht[$j]['name'].'</td></tr>';
		            }
								?>
								</table>
		          </td>
		         </tr>
	        <?
        } ?>
				

        <?php if ($privileg_['freitext'] AND count($flst->FreiText)>0) { ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr valign="top">
              <td align="right"><span class="fett">Zus&auml;tzliche&nbsp;Angaben</span></td>
              <td>
              <?php for ($j=0;$j<count($flst->FreiText);$j++) {
              if ($j>0) { ?><br>
                <?php }
              echo $flst->FreiText[$j]['text'];
              }?>
              </td>
            </tr>
          </table>
        </td>
        </tr>
        <?php } ?>
        <?php if ($privileg_['hinweis'] AND $flst->strittigeGrenze){ ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td valign="top"><span class="fett">Hinweise zum Flurstück:</span>&nbsp;</td>
              <td>
              <?php
              echo 'strittige Grenze';
              ?>
              </td>
            </tr>
            </table>
          </td>
          </tr>
        <?php } ?>
        <?php if ($privileg_['baulasten'] != '' AND count($flst->Baulasten)>0) { ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td><span class="fett">Baulastenblatt-Nr</span></td>
              <td>
              <?php
                  for ($b=0;$b<count($flst->Baulasten);$b++) {
                  echo " ".$flst->Baulasten[$b]['blattnr'];
                  } ?>
                </td>
              </tr>
          </table>
        </td>
        </tr>
        <?php } ?>
        <?php
        if (($privileg_['verfahren'] OR $privileg_['ausfstelle']) AND $flst->Verfahren[0]['flurstkennz']!='') {
          for($j = 0; $j < count($flst->Verfahren); $j++){
          ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <?php if ($privileg_['ausfstelle'])
            { ?>
              <tr valign="top">
			          <td align="right"><span class="fett">Ausf&uuml;hrende&nbsp;Stelle</span></td>
			          <td valign="top"><?php echo $flst->Verfahren[$j]['ausfstelleid']; ?></td>
			          <td valign="top">
			          <?php
			          if (strlen($flst->Verfahren[$j]['ausfstellename'])>40) {
			            $needle=array(
			             strrpos(substr($flst->Verfahren[$j]['ausfstellename'],0,40),','),
			             strrpos(substr($flst->Verfahren[$j]['ausfstellename'],0,40),'-'),
			             strrpos(substr($flst->Verfahren[$j]['ausfstellename'],0,40),'/'),
			             strrpos(substr($flst->Verfahren[$j]['ausfstellename'],0,40),' ')
			            );
			            rsort($needle);
			            echo substr($flst->Verfahren[$j]['ausfstellename'],0,$needle[0]+1)."<br>".substr($flst->Verfahren[$j]['ausfstellename'],$needle[0]+1);
			          } else {
			            echo $flst->Verfahren[$j]['ausfstellename'];
			          }
			          ?>
			          </td>
			        </tr>
            <?php }
              if ($privileg_['verfahren']){ ?>
              <tr valign="top">
			          <td align="right"><span class="fett">Verfahren</span></td>
			          <td valign="top"><?php echo $flst->Verfahren[$j]['verfnr']; ?></td>
			          <td valign="top">
			          (<?php echo $flst->Verfahren[$j]['verfbemid']; ?>)
			          &nbsp;
			          <?php
			          if (strlen($flst->Verfahren[$j]['verfbemerkung'])>35) {
			            $needle=array(
			             strrpos(substr($flst->Verfahren[$j]['verfbemerkung'],0,35),','),
			             strrpos(substr($flst->Verfahren[$j]['verfbemerkung'],0,35),'-'),
			             strrpos(substr($flst->Verfahren[$j]['verfbemerkung'],0,35),'/'),
			             strrpos(substr($flst->Verfahren[$j]['verfbemerkung'],0,35),' ')
			            );
			            rsort($needle);
			            echo substr($flst->Verfahren[$j]['verfbemerkung'],0,$needle[0]+1)."<br>".substr($flst->Verfahren[$j]['verfbemerkung'],$needle[0]+1);
			          } else {
			            echo $flst->Verfahren[$j]['verfbemerkung'];
			          }
			          ?>
			          </td>
			      	</tr>
            <? } ?>
            </table>
          </td>
        </tr>
          <? } ?>
        <? } ?>
        <?php if ($privileg_['nutzung']){ ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="3"><span class="fett">Nutzung</span></td>
              </tr>
              <tr>
                <td><span class="fett">Fl&auml;che&nbsp;</span></td>
                <td><span class="fett">Nutzung&nbsp;</span></td>
                <td><span class="fett">Bezeichnung</span></td>
              </tr>
              <?php
              $anzNutzung=count($flst->Nutzung);
              for ($j=0;$j<$anzNutzung;$j++) { ?>
              <tr>
			          <td align="right" valign="top"><?php echo $flst->Nutzung[$j][flaeche]; ?> m&sup2;&nbsp;</td>
			          <td align="right" valign="top">&nbsp;<?php echo $flst->Nutzung[$j][nutzungskennz]; ?>&nbsp;</td>
			          <td valign="top">
			          <?php
			          if (strlen($flst->Nutzung[$j][bezeichnung])>80) {
			            $needle=array(
			             strrpos(substr($flst->Nutzung[$j][bezeichnung],0,80),','),
			             strrpos(substr($flst->Nutzung[$j][bezeichnung],0,80),'-'),
			             strrpos(substr($flst->Nutzung[$j][bezeichnung],0,80),'/'),
			             strrpos(substr($flst->Nutzung[$j][bezeichnung],0,80),' ')
			            );
			            rsort($needle);
			            echo substr($flst->Nutzung[$j][bezeichnung],0,$needle[0]+1)."<br>".substr($flst->Nutzung[$j][bezeichnung],$needle[0]+1);
			          } else {
			            echo $flst->Nutzung[$j][bezeichnung];
			          }
			  				if ($flst->Nutzung[$j][kurzbezeichnung]!='') { ?> (<?php echo $flst->Nutzung[$j][kurzbezeichnung]; ?>)<?php } ?>
			  				</td>
			        </tr>
              <?php } ?>
          </table>
          </td>
        </tr>
        <? } ?>
        <? if($privileg_['eigentuemer']){
						$currenttime=date('Y-m-d H:i:s',time());
						$this->user->rolle->setConsumeALB($currenttime, 'Flurstücksanzeige', array($flst->FlurstKennz), 0, 'NULL');		# das Flurstückskennzeichen wird geloggt
				?>
        <tr>
        <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="3"><span class="fett">Eigentümer</span></td>
              </tr>
            <? 
            for ($b=0;$b<count($flst->Buchungen);$b++) {
							if($privileg_['bestandsnr']){
								$BestandStr ='<a href="index.php?go=Grundbuchblatt_Auswaehlen_Suchen&selBlatt='.$flst->Buchungen[$b]['bezirk'].'-'.$flst->Buchungen[$b]['blatt'].'">'.$flst->Buchungen[$b]['bezirk'].'-'.ltrim($flst->Buchungen[$b]['blatt'], '0').'</a>';
								$BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
									$BestandStr.=' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
									$BestandStr.=' ('.$flst->Buchungen[$b]['buchungsart'].')';
									$BestandStr.=' '.$flst->Buchungen[$b]['bezeichnung']; ?>
								<tr>
									<td>Bestand</td>
									<td colspan="2"><? echo $BestandStr; ?></td>
								</tr>
              <? if($flst->Buchungen[$b]['blattart'] == 3000){ ?>
								<tr>
									<td></td>
									<td colspan="2">Im Grundbuch noch nicht gebucht.</td>
								</tr>
							<? }
							}
              $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
                  $anzEigentuemer=count($Eigentuemerliste);
                  for ($e=0;$e<$anzEigentuemer;$e++) { ?>
              <tr>
              <td valign="top"><? echo $Eigentuemerliste[$e]->Nr ?>&nbsp;&nbsp;&nbsp;</td>
              <td valign="top">
              <?
                $anzNamenszeilen=count($Eigentuemerliste[$e]->Name);
                $Eigentuemerliste[$e]->Name_bearb = $Eigentuemerliste[$e]->Name;
								if($this->Stelle->isFunctionAllowed('Adressaenderungen')) {
											$eigentuemer = new eigentuemer(NULL, NULL, $this->pgdatabase);
											$adressaenderungen =  $eigentuemer->getAdressaenderungen($Eigentuemerliste[$e]->gml_id);
											$aendatum=substr($adressaenderungen['datum'],0,10);
								}
								if ($adressaenderungen['user_id'] != '') {
									$user = new user(NULL, $adressaenderungen['user_id'], $this->database);
								}
              ?>
                <table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                  <td>
                  <?
                    for ($n=0;$n<$anzNamenszeilen;$n++) {
                      if (!($Eigentuemerliste[$e]->Name_bearb[$n]=="" OR $Eigentuemerliste[$e]->Name_bearb[$n]==' ')) {
                          echo $Eigentuemerliste[$e]->Name_bearb[$n].'<br>';
                      }
                    }
                  if ($adressaenderungen['user_id'] != '') {
											echo '<span class="fett"><u>Aktualisierte Anschrift ('.$aendatum.' - '.$user->Name.'):</u></span><br>';
                      echo '&nbsp;&nbsp;<span class="fett">'.$adressaenderungen['strasse'].' '.$adressaenderungen['hausnummer'].'</span><br>';
                      echo '&nbsp;&nbsp;<span class="fett">'.$adressaenderungen['postleitzahlpostzustellung'].' '.$adressaenderungen['ort_post'].' '.$adressaenderungen['ortsteil'].'</span><br>';
                  }
                  ?>
                  </td>
                  <td valign="bottom">
                  <?
                  if($this->Stelle->isFunctionAllowed('Adressaenderungen') AND $Eigentuemerliste[$e]->Nr != ''){
                    if ($adressaenderungen['user_id'] == '') {											
											echo '<img src="'.GRAPHICSPATH.'pfeil_links.gif" width="12" height="12" border="0">'; ?>&nbsp;<a class="buttonlink" href="javascript:ahah('index.php', 'go=neuer_Layer_Datensatz&reload=true&selected_layer_id=<? echo LAYER_ID_ADRESSAENDERUNGEN_PERSON; ?>&attributenames[0]=gml_id&attributenames[1]=hat&values[0]=<? echo urlencode($Eigentuemerliste[$e]->gml_id); ?>&values[1]=<? echo urlencode($Eigentuemerliste[$e]->anschrift_gml_id); ?>&embedded=true&fromobject=subform_ax_person_temp<? echo $b.'_'.$e; ?>&targetlayer_id=0&targetattribute=leer', new Array(document.getElementById('subform_ax_person_temp<? echo $b.'_'.$e; ?>')), new Array('sethtml'));"><span> Anschrift aktualisieren</span></a>
                  <?}
                    else {
											echo '<img src="'.GRAPHICSPATH.'pfeil_links.gif" width="12" height="12" border="0">'; ?>&nbsp;<a class="buttonlink" href="javascript:ahah('index.php', 'go=Layer-Suche_Suchen&reload=true&selected_layer_id=<? echo LAYER_ID_ADRESSAENDERUNGEN_PERSON; ?>&value_gml_id=<? echo urlencode($Eigentuemerliste[$e]->gml_id); ?>&operator_gml_id==&attributenames[0]=user_id&values[0]=<? echo $this->user->id ?>&embedded=true&fromobject=subform_ax_person_temp<? echo $b.'_'.$e; ?>&targetlayer_id=0&targetattribute=leer', new Array(document.getElementById('subform_ax_person_temp<? echo $b.'_'.$e; ?>')), '');">Anschrift &auml;ndern</a>
                  <?}
                  }?>
                    </td>
									<tr>
										<td colspan="2"><div id="subform_ax_person_temp<? echo $b.'_'.$e; ?>" style="display:inline"></div></td>
									</tr>
									</tr>
                </table>
                </td>
							</tr>
							<? if($Eigentuemerliste[$e]->zusatz_eigentuemer != ''){ ?>
								<tr>
									<td colspan="2"><? echo $Eigentuemerliste[$e]->zusatz_eigentuemer; if($Eigentuemerliste[$e]->Anteil != '')echo ' zu '.$Eigentuemerliste[$e]->Anteil;?></td>
								</tr>
								<? }
									 elseif($Eigentuemerliste[$e]->Anteil != ''){ ?>
								<tr>
									<td></td>
									<td>zu <? echo $Eigentuemerliste[$e]->Anteil; ?></td>
								</tr>
								<? } ?>
							<? }
               } ?>
          </table>
        </td>
        </tr>
        <?} ?>

        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <table width="100%" cellspacing="0" cellpading="0" border="0">
        <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
		  <td colspan="2">
            <div class="fstanzeigecontainer">

					<a href="index.php?go=Flurstueck_<? if($flst->endet!="" OR $flst->hist_alb == 1)echo 'hist_';?>Auswaehlen&searchInExtent=<?php echo $this->searchInExtent;
					?>&GemID=<?php echo $flst->GemeindeID;
					?>&GemkgID=<?php echo $flst->GemkgSchl; ?>&FlurID=<?php echo $flst->FlurID;
					?>&FlstID=<?php echo $flst->FlurstKennz; ?>">
                    <div class="fstanzeigehover">
					  &nbsp;&nbsp;
					  zur Flurstückssuche
					  &nbsp;&nbsp;
                    </div>
    				</a>
					<a href="index.php?go=Adresse_Auswaehlen&searchInExtent=<?php echo $this->searchInExtent;
					?>&GemID=<? echo $flst->GemeindeID;
					?>&StrID=<? echo $flst->Adresse[0]["strasse"];
					?>&selHausID=<? if($flst->selHausID != '')echo implode($flst->selHausID, ', '); ?>">
										<div class="fstanzeigehover">
					  &nbsp;&nbsp;
					  zur Adresssuche
					  &nbsp;&nbsp;
                    </div>
					</a>
					<?
						if($flst->hist_alb != 1){
							$zoomlink = 'ZoomToFlst&FlurstKennz='.$flst->FlurstKennz; 
							if($set_timestamp != '')$zoomlink = $set_timestamp.'&go_next='.urlencode($zoomlink);else $zoom_all = true;
					?>
							<a href="index.php?go=<? echo $zoomlink;?>">
												<div class="fstanzeigehover">
								&nbsp;&nbsp;
								Kartenausschnitt
								&nbsp;&nbsp;
							</div>
							</a>
					<? } ?>
              <div class="fstanzeigehover">
					  &nbsp;&nbsp;
					  Auszug:
						<select style="width: 130px" onchange="this.options[this.selectedIndex].onchange();">
							<option>-- Auswahl --</option>
							<? if($this->Stelle->funktionen['MV0510']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0510&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücksnachweis</option><? } ?>
							<? if($this->Stelle->funktionen['MV0550']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0550&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücks- und Eigentumsnachweis</option><? } ?>
							<? if($this->Stelle->funktionen['MV0520']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0520&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücksnachweis mit Bodenschätzung</option><? } ?>
							<? if($this->Stelle->funktionen['MV0560']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0560&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücks- und Eigentumsnachweis mit Bodenschätzung</option><? } ?>

							<? if($this->Stelle->funktionen['ALB-Auszug 30']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALB_Anzeige&formnummer=30&wz=1&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurst&uuml;cksdaten</option><? } ?>
							<? if($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALB_Anzeige&formnummer=35&wz=1&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurst&uuml;cksdaten&nbsp;mit&nbsp;Eigent&uuml;mer</option><? } ?>
							<? if($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALB_Anzeige&formnummer=40&wz=1&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Eigent&uuml;merdaten&nbsp;zum&nbsp;Flurst&uuml;ck</option><? } ?>
						</select>
					  &nbsp;&nbsp;
                      </div>

              </div>
			</td>
          </tr>
        </tr>
      </table>
        </td>
      </tr>
      </table>
      </div>
    </td>
  </tr>

  <?} # Ende es wurde auch was zum Flurstück gefunden
    else { ?>
    <tr>
    <td>Das Flurstück mit Kennzeichen: <?php echo $flurstkennz; ?> ist nicht in der aktuellen<br> PostGIS-Datenbank enthalten. Aktualisieren Sie die ALB und ALK-Daten.
    </td>
    </tr>
    <? }
  } # Ende der Schleife zur Abfrage und Anzeige der einzelnen Flurstücke
  ?>

  <!-- Für alle Flurstücke -->
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="bottom">
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td colspan="2">
        <? echo '<img src="'.GRAPHICSPATH.'pfeil_unten-rechts.gif" width="10" height="20" border="0">'; ?>
        <span class="fett">Für alle ausgewählten Flurstücke:</span><br>
      </td>
    </tr>
		<? if($this->qlayerset[$i]['export_privileg'] != 0){ ?>
    <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
		  <td colspan="2">
          <div class="fstanzeigecontainer">

            <a href="javascript:send_selected_flurst('Flurstuecks-CSV-Export', 'Flurstück', '', '');">
            <div class="fstanzeigehover">
              &nbsp;&nbsp;
              CSV-Export FST
              &nbsp;&nbsp;
            </div>
            </a>

            <? if($privileg_['eigentuemer']){ ?>
            <a href="javascript:send_selected_flurst('Flurstuecks-CSV-Export', 'Eigentümer', '', '');">
            <div class="fstanzeigehover">
              &nbsp;&nbsp;
              CSV-Export Eigent&uuml;mer
              &nbsp;&nbsp;
            </div>
            </a>
            <? } ?>

            <a href="javascript:send_selected_flurst('Flurstuecks-CSV-Export', 'Nutzungsarten', '', '');">
            <div class="fstanzeigehover">
              &nbsp;&nbsp;
              CSV-Export NA
              &nbsp;&nbsp;
            </div>
            </a>

            <a href="javascript:send_selected_flurst('Flurstuecks-CSV-Export', 'Klassifizierung', '', '');">
            <div class="fstanzeigehover">
              &nbsp;&nbsp;
              CSV-Export Klassifizierung
              &nbsp;&nbsp;
            </div>
            </a>

          </div>
  		  </td>
		</tr>
		<? } ?>
		<tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
		  <td>
          <div class="fstanzeigecontainer">
            <div style="text-align:center;">
							<? if($zoom_all == true){ ?>
              <a href="javascript:send_selected_flurst('ZoomToFlst', '');">
              <div class="fstanzeigehover">
                &nbsp;&nbsp;
                Kartenausschnitt
                &nbsp;&nbsp;
              </div>
              </a>
							<? } ?>
              <div class="fstanzeigehover">
                &nbsp;&nbsp;
				Auszug:
				<select style="width: 130px" onchange="this.options[this.selectedIndex].onchange();">
					<option>-- Auswahl --</option>
					<? if($this->Stelle->funktionen['MV0510']['erlaubt']){ ?><option onchange="send_selected_flurst('ALKIS_Auszug', 'MV0510', 1, '_blank');">Flurstücksnachweis</option><? } ?>
					<? if($this->Stelle->funktionen['MV0550']['erlaubt']){ ?><option onchange="send_selected_flurst('ALKIS_Auszug', 'MV0550', 1, '_blank');">Flurstücks- und Eigentumsnachweis</option><? } ?>
					<? if($this->Stelle->funktionen['MV0520']['erlaubt']){ ?><option onchange="send_selected_flurst('ALKIS_Auszug', 'MV0520', 1, '_blank');">Flurstücksnachweis mit Bodenschätzung</option><? } ?>
					<? if($this->Stelle->funktionen['MV0560']['erlaubt']){ ?><option onchange="send_selected_flurst('ALKIS_Auszug', 'MV0560', 1, '_blank');">Flurstücks- und Eigentumsnachweis mit Bodenschätzung</option><? } ?>

					<? if($this->Stelle->funktionen['ALB-Auszug 30']['erlaubt']){ ?><option onchange="send_selected_flurst('ALB_Anzeige', '30', 1, '_blank');">Flurst&uuml;cksdaten</option><? } ?>
					<? if($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']){ ?><option onchange="send_selected_flurst('ALB_Anzeige', '35', 1, '_blank');">Flurst&uuml;cksdaten&nbsp;mit&nbsp;Eigent&uuml;mer</option><? } ?>
					<? if($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']){ ?><option onchange="send_selected_flurst('ALB_Anzeige', '40', 1, '_blank');">Eigent&uuml;merdaten&nbsp;zum&nbsp;Flurst&uuml;ck</option><? } ?>
				</select>
                &nbsp;&nbsp;
              </div>

          </div>
        </div>
  		</td>
		</tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
      </table>
    </td>
  </tr>

  <?
  } # Ende es liegen Flurstücke im Suchbereich

  else {
    ?><br><span class="fett"><font color="#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</font></span><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php
  }
?>

</table>
<input type="hidden" name="attributliste" value="<? echo $attribute; ?>">
<input type="hidden" name="FlurstKennz" value="">
<input type="hidden" name="formnummer" value="">
<input type="hidden" name="wz" value="">

<?
if($this->formvars['go'] != 'neu Laden' AND $this->formvars['go'] != 'Layer-Suche' AND $this->formvars['go'] != 'Layer-Suche_Suchen' AND $this->formvars['go'] != 'Sachdaten'){
?>
<input name="go" type="hidden" value="">
<input type="hidden" name="go_backup" value="">
<?}?>


<?
if($this->formvars['grundbuchsuche'] == 'true'){
?>
  <input name="selBlatt" type="hidden" value="<? echo $this->formvars['selBlatt']; ?>">
 	<input name="Bezirk" type="hidden" value="<? echo $this->formvars['Bezirk']; ?>">
  <a href="javascript:backto('Grundbuchblatt_Auswaehlen_Suchen');">zurück zur Grundbuchblattanzeige</a>
  <br><br>
<?}
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
  <a href="javascript:backto('Namen_Auswaehlen_Suchen');">zurück zur Namensuche</a>
  <br><br>
 <?}
 if($this->formvars['jagdkataster'] == 'true'){
?>
  <input name="oid" type="hidden" value="<? echo $this->formvars['oid']; ?>">
  <input name="name" type="hidden" value="<? echo $this->formvars['name']; ?>">
  <input name="search_nummer" type="hidden" value="<?php echo $this->formvars['search_nummer']; ?>">
  <input name="search_name" type="hidden" value="<?php echo $this->formvars['search_name']; ?>">
  <input name="search_art" type="hidden" value="<?php echo $this->formvars['search_art']; ?>">
  <a href="javascript:backto('jagdkatastereditor_Flurstuecke_Listen');">zurück zur Jagdbezirk-Flurstücksliste</a>
  <br><br>
<?} ?>