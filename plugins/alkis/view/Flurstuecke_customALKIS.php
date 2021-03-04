<?
	include_once(PLUGINS.'alkis/model/kataster.php');
	include_once(SNIPPETS.'sachdatenanzeige_functions.php');
	global $layer_ids_flst_auszuege;
	if(!empty($layer_ids_flst_auszuege)){
		include_once(CLASSPATH.'datendrucklayout.php');
		$ddl = new ddl($this->database);
		foreach($layer_ids_flst_auszuege as $layer_id){
			$generische_auszuege[$layer_id] = $ddl->load_layouts($this->Stelle->id, NULL, $layer_id, array(0,1));
		}
	}
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
	if (go == 'kvwkol') {
		message('Öffne folgende Flurstücke in Kolibri:<br>' + flurstkennz.replace(';', '<br>'));
		window.location.href = 'kvwkol://FlurstKennz=' + flurstkennz.replace(';', ',');
	}
	else {
		currentform.target = '';
		if(target == '_blank'){
			currentform.target = '_blank';
		}
		currentform.go.value=go;
		currentform.FlurstKennz.value=flurstkennz;
		currentform.formnummer.value=formnummer;
		currentform.wz.value=wz;
		overlay_submit(currentform, false, 'root');
		stopwaiting();
	}
}

backto = function(go){
  currentform.go.value=go;
  currentform.submit();
}

show_all = function(){
	currentform.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = 0;
	currentform.anzahl.value = currentform.anzahl.options[currentform.anzahl.options.length-1].value;
	overlay_submit(currentform, false);
}

show_versions = function(flst){
	document.getElementById('no_versions_'+flst).style.display = 'none';
	document.getElementById('versions_'+flst).style.display = '';
	ahah('index.php', 'go=Flurstueck_GetVersionen&flurstkennz='+flst, new Array(document.getElementById('versions_'+flst)), new Array('sethtml'));
}

hide_versions = function(flst){
	document.getElementById('no_versions_'+flst).style.display = 'inline';
	document.getElementById('versions_'+flst).style.display = 'none';
}

</script>
<br>
<a name="anfang"></a>
<h2>Flurst&uuml;cke</h2>
<table border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td align="center">
<?php
	$timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $this->user->rolle->hist_timestamp_de);
	$sql = "SELECT max(beginnt)::date FROM alkis.ax_fortfuehrungsfall;";
  $ret=$this->pgdatabase->execSQL($sql,4,0);
  $aktalkis = pg_fetch_row($ret[1]);

	$this->Stelle->getFunktionen();
	$forall = false;
	if($i == '')$i = 0;
	$gesamt = $this->qlayerset[$i]['count'];
  $anzObj = @count($this->qlayerset[$i]['shape']);
	if($gesamt == '')$gesamt = $anzObj;
	$von = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + 1;
	$bis = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + $this->formvars['anzahl'];
  if ($anzObj>0) {
		$this->found = 'true';
	?>
		<br>
		<? if($this->user->rolle->hist_timestamp_de == ''){ ?>
		<span style="font-size:80%;">Stand ALKIS vom: <? echo $aktalkis[0]; ?><br></span>
		<? }else{ ?>
		<span class="fett" style="color: #a82e2e;">historischer Stand vom: <? echo $this->user->rolle->hist_timestamp_de; ?><br></span>
		<? } ?>
		<br>
    <u><? echo $gesamt; ?> Flurstück<? if ($gesamt>1) { echo "e"; } ?> abgefragt</u>
		<? if($gesamt > $anzObj){ ?>
		&nbsp;<a href="javascript:show_all();">alle anzeigen</a>
    <br><br>
		<u>Flurstücke <? echo $von; ?> bis <? echo $bis; ?></u>
		<? } ?>
		:<br>

    <?
		
		if (!function_exists('sort_flst')) {
			function sort_flst($a, $b){
				if($a->Nachfolger != '' AND $b->Nachfolger == '')return -1;		# historische
				if($a->FlurstNr == '' AND $b->FlurstNr != '')return 1;				# nicht gefundene
				return strcmp($a->FlurstKennz, $b->FlurstKennz);
			}
		}
		$flst_array = array();
		
    for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
      if($this->qlayerset[$i]['attributes']['privileg'][$this->qlayerset[$i]['attributes']['name'][$j]] != ''){
        $privileg_[$this->qlayerset[$i]['attributes']['name'][$j]] = true;
        if($j > 0){ $attribute .= ';';}
        $attribute .= $this->qlayerset[$i]['attributes']['name'][$j];
      }
    }		
    for ($a=0;$a<$anzObj;$a++){
      $flurstkennz_a=$this->qlayerset[$i]['shape'][$a]['flurstkennz'];
			$flst=new flurstueck($flurstkennz_a,$this->pgdatabase);
      $flst->readALB_Data($flurstkennz_a, $this->formvars['without_temporal_filter']);	# bei without_temporal_filter=true, wird unabhängig vom Zeitstempel abgefragt (z.B. bei der historischen Flurstückssuche oder Flst.-Listenimport oder beim Sprung zum Vorgänger/Nachfolger)
			$flst->Grundbuecher=$flst->getGrundbuecher();
			$flst->Buchungen=$flst->getBuchungen(NULL,NULL,$flst->hist_alb);
			$flst_array[] = $flst;
		}
		usort($flst_array, 'sort_flst');
		echo '<tr><td align="center"><table><tr><td align="left">';
		for($k=0;$k<count($flst_array);$k++){
			$flst = $flst_array[$k];
			$gemkg=substr($flst->FlurstKennz, 0, 6);
      $flur=substr($flst->FlurstKennz, 6, 3);
      $zaehler=ltrim(substr($flst->FlurstKennz, 9, 5), '0');
      $nenner=ltrim(rtrim(substr($flst->FlurstKennz, 14, 6), '_'), '0');
			if ($nenner!='') {
        $nenner="/".$nenner;
      }
			if($flst->FlurstNr){
				echo '<a href="#'.$flst->FlurstKennz.'">Gemarkung: '.$gemkg.' - Flur: '.ltrim($flur,"0").' - Flurst&uuml;ck: '.$zaehler.$nenner.'</a>';
				if($flst->Nachfolger != '')echo ' (H)';
				echo '<br>';
			}
			else{
				echo 'Gemarkung: '.$gemkg.' - Flur: '.ltrim($flur,"0").' - Flurst&uuml;ck: '.$zaehler.$nenner.' nicht gefunden<br>';
			}
    }
		echo '</td></tr></table></td></tr>';

    for($k=0;$k<count($flst_array);$k++){
			$flst = $flst_array[$k];
			if($flst->FlurstNr == '')continue;
			$set_timestamp = '';
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
			<div <? if($this->user->rolle->querymode == 1){ ?>onmouseenter="highlight_object(<? echo $this->qlayerset[$i]['Layer_ID']; ?>, '<? echo $flst->oid; ?>');" <? } ?> style="position:relative; top:0px; right:0px; padding:0px; border: 1px solid <?php echo BG_DEFAULT ?>;">
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
									<? if($privileg_['flurstkennz']){ ?>
										<table border="0" cellspacing="0" cellpadding="2">
											<tr>
												<td colspan="2">
													<table cellspacing="0" cellpadding="0">
														<tr>
															<td valign="top">
																<span class="px17 fett"><? echo $flst->Flurstkennz_alt; ?></span>
															</td>
															<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
															<td>
																<? if($this->Stelle->hist_timestamp AND !$flst->hist_alb){ ?>
																<div id="no_versions_<? echo $flst->FlurstKennz; ?>">
																	<table cellspacing="0" cellpadding="2">
																		<tr>
																			<td>
																				<a href="javascript:show_versions('<? echo $flst->FlurstKennz; ?>');"><img src="<? echo GRAPHICSPATH.'plus.gif'; ?>"></a>
																			</td>
																			<td>
																				<span class="px14">Versionen</span>
																			</td>
																		</tr>
																	</table>
																</div>
																<div id="versions_<? echo $flst->FlurstKennz; ?>" style="border: 1px solid <? echo BG_DEFAULT ?>; display:none"></div>
															</td>
															<? } ?>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									<? } ?>

								<table border="0" cellspacing="0" cellpadding="2">
								
								<? if($privileg_['flurstkennz']){ ?>
											<tr>
												<td align="right"><span class="fett">Flurst&uuml;cksnummer&nbsp;</span></td>
												<td>
													<? echo $flst->FlurstNr; ?>
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
												<td><?php echo $flst->ALB_Flaeche; ?>&nbsp;m&sup2;</td>
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
												$anzStrassen = @count($flst->Adresse);
												for ($s=0;$s<$anzStrassen;$s++) {
													$flst->selHausID[] = $flst->Adresse[$s]["gemeinde"].'-'.$flst->Adresse[$s]["strasse"].'-'.$flst->Adresse[$s]["hausnr"];	# für die Adressensuche
													echo $flst->Adresse[$s]["gemeindename"]; ?><br><?php
													echo $flst->Adresse[$s]["strassenname"]; ?>&nbsp;<?php
													echo $flst->Adresse[$s]["hausnr"]; ?><br><?php
												}
												$anzLage = @count($flst->Lage);
												$Lage='';
												for ($j=0;$j<$anzLage;$j++) {
													$Lage.= $flst->Lage[$j].'<br>';
												}
												if ($Lage!='') {
													?><?php echo TRIM($Lage);
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
															if($this->Stelle->hist_timestamp AND ($timestamp == NULL OR $timestamp < $beginnt OR $timestamp >= $endet)){
																$set_timestamp = 'setHistTimestamp&timestamp='.$flst->beginnt;
																echo '<a target="root" href="index.php?go='.$set_timestamp.'" title="in die Zeit des Flurstücks wechseln">&nbsp;(endet: '.$flst->endet.')</a>';
															}
															else echo "&nbsp;(endet: ".$flst->endet.")";
														} 
													} 
													else{
														if($this->Stelle->hist_timestamp AND $timestamp != NULL AND $timestamp < $beginnt){
															$set_timestamp = 'setHistTimestamp';
															echo '<a target="root" href="index.php?go='.$set_timestamp.'" title="Zeitpunkt auf aktuell setzen">aktuelles&nbsp;Flurst&uuml;ck</a>';
														}
														else{
															echo "aktuelles&nbsp;Flurst&uuml;ck";
															if($timestamp != '')echo ' (historische Version)';
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
										<?php if ($privileg_['nachfolger'] AND $flst->Nachfolger != '' AND $flst->Nachfolger[0]['nachfolger'] != 'BOV') { ?>
										<tr>
											<td align="right" valign="top"><span class="fett">Nachfolger</span></td>
											<td>
												<?
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
									<?php } 
									
											global $kvwmap_plugins;
											if(in_array('probaug', $kvwmap_plugins) AND $this->Stelle->isFunctionAllowed('Bauakteneinsicht')){
												include_once(PLUGINS.'probaug/model/bau.php');
												$this->bau = new Bauauskunft($this->baudatabase);
												$searchvars['flurstkennz'] = $flst->Flurstkennz_alt;
												$this->bau->getbaudaten($searchvars);
												if(@count($this->bau->baudata) != 0){
												?>
											<tr>
												<td align="right"><span class="fett"> Baudaten&nbsp;</span></td>
												<td><a target="root" href="index.php?go=Bauauskunft_Suche_Suchen&flurstkennz=<? echo $flst->Flurstkennz_alt; ?>&distinct=1">anzeigen</a></td>
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
								
								 <? if($privileg_['festlegungen'] AND (@count($flst->Strassenrecht) > 0 OR @count($flst->Wasserrecht) > 0 OR @count($flst->Schutzgebiet) > 0 OR @count($flst->NaturUmweltrecht) > 0 OR @count($flst->BauBodenrecht) > 0 OR @count($flst->Denkmalschutzrecht) > 0 OR @count($flst->Forstrecht) > 0 OR @count($flst->Sonstigesrecht) > 0)){
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
												for($j = 0; $j < @count($flst->Strassenrecht); $j++){
													echo '<tr><td valign="top">'.$flst->Strassenrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Strassenrecht[$j]['art'].': '.$flst->Strassenrecht[$j]['bezeichnung'].'</td></tr>';
												}
												for($j = 0; $j < @count($flst->Wasserrecht); $j++){
													echo '<tr><td valign="top">'.$flst->Wasserrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Wasserrecht[$j]['art'].': '.$flst->Wasserrecht[$j]['bezeichnung'].'</td></tr>';
												}
												for($j = 0; $j < @count($flst->Schutzgebiet); $j++){
													echo '<tr><td valign="top">'.$flst->Schutzgebiet[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Schutzgebiet[$j]['art'].'</td></tr>';
												}
												for($j = 0; $j < @count($flst->NaturUmweltrecht); $j++){
													echo '<tr><td valign="top">'.$flst->NaturUmweltrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->NaturUmweltrecht[$j]['art'].'</td></tr>';
												}
												for($j = 0; $j < @count($flst->BauBodenrecht); $j++){
													echo '<tr><td valign="top">'.$flst->BauBodenrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->BauBodenrecht[$j]['art'];
													if($flst->BauBodenrecht[$j]['bezeichnung'] != '')echo ': '.$flst->BauBodenrecht[$j]['bezeichnung'];
													if($flst->BauBodenrecht[$j]['stelle'] != '')echo ' ('.$flst->BauBodenrecht[$j]['stelle'].')';
													echo '</td></tr>';
												}
												if($flst->abweichenderrechtszustand == 'ja')echo '<tr><td colspan="2" width="600px">In einem durch Gesetz geregelten Verfahren der Bodenordnung ist für das Flurstück ein neuer Rechtszustand eingetreten. Die Festlegungen des Verfahrens sind noch nicht in das Liegenschaftskataster übernommen. Dieser Nachweis entspricht deshalb nicht dem aktuellen Stand.</td></tr>';
												if($flst->zweifelhafterflurstuecksnachweis == 'ja')echo '<tr><td colspan="2" width="600px">Zweifelhafter Flurstücksnachweis</td></tr>';
												for($j = 0; $j < @count($flst->Denkmalschutzrecht); $j++){
													echo '<tr><td valign="top">'.$flst->Denkmalschutzrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Denkmalschutzrecht[$j]['art'].' '.$flst->Denkmalschutzrecht[$j]['name'].'</td></tr>';
												}
												for($j = 0; $j < @count($flst->Forstrecht); $j++){
													echo '<tr><td valign="top">'.$flst->Forstrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Forstrecht[$j]['art'].': '.$flst->Forstrecht[$j]['funktion'].'</td></tr>';
												}
												for($j = 0; $j < @count($flst->Sonstigesrecht); $j++){
													echo '<tr><td valign="top">'.$flst->Sonstigesrecht[$j]['flaeche'].' m²</td><td width="500px">'.$flst->Sonstigesrecht[$j]['art'].' '.$flst->Sonstigesrecht[$j]['name'].'</td></tr>';
												}
												?>
												</table>
											</td>
										 </tr>
									<?
								} ?>
								

								<?php if ($privileg_['freitext'] AND @count($flst->FreiText)>0) { ?>
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
								<?php if ($privileg_['baulasten'] != '' AND @count($flst->Baulasten)>0) { ?>
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
												<td><span class="fett">Nutzung</span></td>
											</tr>
											<tr>
												<td>
											<?php
											$anzNutzung = @count($flst->Nutzung);
											if($anzNutzung > 0){
												for ($j=0;$j<$anzNutzung;$j++){
													if($flst->Nutzung[$j]['bereich'] != $flst->Nutzung[$j-1]['bereich']){
														if($j > 0){ ?></table></div></div><? } ?>
														<div id="nu_bereich">
															<span id="nu_bereich_span"><? echo $flst->Nutzung[$j]['bereich']; ?></span>
															<div id="nu_gruppe_nutzungsart">
													<? }
															if($flst->Nutzung[$j]['gruppe'] != $flst->Nutzung[$j-1]['gruppe']){
																if($j > 0 AND $flst->Nutzung[$j]['bereich'] == $flst->Nutzung[$j-1]['bereich']){ ?></table><? } ?>
																<span id="nu_gruppe_nutzungsart_span"><? echo $flst->Nutzung[$j]['gruppe']; ?></span>
																<table id="nu_gruppe_nutzungsart_table">
																	<tr>
																		<th>Fläche</th><th>Schlüssel</th><th>Nutzung</th>
																	</tr>
													<?  } ?>
																	<tr>
																		<td align="right"><? echo $flst->Nutzung[$j]['flaeche']; ?> m&sup2;&nbsp;</td>
																		<td><? echo $flst->Nutzung[$j]['nutzungskennz']; ?></td>
																		<td>
																			<? echo implode(', ', array_filter(array($flst->Nutzung[$j]['nutzungsart'], $flst->Nutzung[$j]['untergliederung1'], $flst->Nutzung[$j]['untergliederung2'])));
																				 if($flst->Nutzung[$j]['nutzungsart'] == '' AND $flst->Nutzung[$j]['untergliederung1'] == '' AND $flst->Nutzung[$j]['untergliederung2'] == '')echo '&mdash;'; ?>
																		</td>
																	</tr>
														<? } ?>
															</table>
														</div>
													</div>
												<? } ?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<? } ?>
								<? if($privileg_['bestandsnr']){
										$currenttime=date('Y-m-d H:i:s',time());
										$this->user->rolle->setConsumeALB($currenttime, 'Flurstücksanzeige', array($flst->FlurstKennz), 0, 'NULL');		# das Flurstückskennzeichen wird geloggt
								?>
								<tr>
								<td colspan="2">
										<table border="0" cellspacing="0" cellpadding="2">
										<? 
										for ($b=0; $b < @count($flst->Buchungen);$b++) {
											$BestandStr = $flst->Buchungen[$b]['bezeichnung'].' ';
											$BestandStr.='<a target="root" href="index.php?go=Grundbuchblatt_Auswaehlen_Suchen&selBlatt='.$flst->Buchungen[$b]['bezirk'].'-'.$flst->Buchungen[$b]['blatt'].'">'.$flst->Buchungen[$b]['bezirk'].'-'.ltrim($flst->Buchungen[$b]['blatt'], '0').'</a>';
											$BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
											$BestandStr.=', Laufende Nummer '.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
											if($flst->Buchungen[$b]['sondereigentum'] != ''){
												$BestandStr.='<br><br>verbunden mit Sondereigentum "'.$flst->Buchungen[$b]['sondereigentum'].'" Nr. "'.$flst->Buchungen[$b]['auftplannr'].'" laut Aufteilungsplan.';
											} ?>
											<tr>
												<td class="fett">Buchung:</td>
											</tr>
											<tr>
												<td colspan="2" style="padding-left: 20px"><? echo $BestandStr; ?></td>
											</tr>
											<? if($flst->Buchungen[$b]['buchungstext'] != ''){ ?>
											<tr>
												<td class="fett">Buchungstext:</td>
											</tr>
											<tr>
												<td colspan="2" style="padding-left: 20px">
													<? echo nl2br($flst->Buchungen[$b]['buchungstext']); ?>
												</td>
											</tr>
										<?	} 
											if($flst->Buchungen[$b]['blattart'] == 3000){ ?>
											<tr>
												<td></td>
												<td colspan="2">Im Grundbuch noch nicht gebucht.</td>
											</tr>
										<? }
										if($privileg_['eigentuemer'] AND $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr'])){
											reset($Eigentuemerliste);
											?>
											<tr>
												<td class="fett">
												<? 	if($flst->Buchungen[$b]['buchungsart'] >= 2101){
															echo 'Berechtigter';
														}
														else{
															echo 'Eigentümer';
														}
												?>:
												</td>
											</tr>
											<tr>
												<td colspan="3">
													<table>				<?
											echo $flst->outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 'Long', $this->Stelle->isFunctionAllowed('Adressaenderungen'), NULL, $this->database);
											?>	</table>
												</td>
											</tr>
								<?	}} ?>
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
							<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: 1px solid <?php echo BG_DEFAULT ?>;">
								<tr align="center" valign="top">
									<td colspan="2">
										<div class="fstanzeigecontainer button_background">
											<a target="root" href="index.php?go=Flurstueck_<? if($flst->endet!="" OR $flst->hist_alb == 1)echo 'hist_';?>Auswaehlen&searchInExtent=<?php echo $this->searchInExtent;
											?>&GemID=<?php echo $flst->GemeindeID;
											?>&GemkgID=<?php echo $flst->GemkgSchl; ?>&FlurID=<?php echo $flst->FlurID;
											?>&FlstID=<?php echo $flst->FlurstKennz; ?>">
												<div class="fstanzeigehover">&nbsp;&nbsp;zur Flurstückssuche&nbsp;&nbsp;</div>
											</a>
											<a target="root" href="index.php?go=Adresse_Auswaehlen&searchInExtent=<?php echo $this->searchInExtent;
											?>&GemID=<? echo $flst->GemeindeID;
											?>&StrID=<? echo $flst->Adresse[0]["strasse"];
											?>&selHausID=<? if($flst->selHausID != '')echo implode($flst->selHausID, ', '); ?>">
												<div class="fstanzeigehover">&nbsp;&nbsp;zur Adresssuche&nbsp;&nbsp;</div>
											</a>
											<?
												if($flst->hist_alb != 1){
													$zoomlink = 'ZoomToFlst&FlurstKennz='.$flst->FlurstKennz; 
													if($set_timestamp != '')$zoomlink = $set_timestamp.'&go_next='.$zoomlink;else $zoom_all = true;
											?>
													&nbsp;&nbsp;
													<a target="root" title="Zoom auf Flurstück und Flurstück hervorheben" href="index.php?go=<? echo $zoomlink;?>"><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
													&nbsp;&nbsp;
													<a title="Zoom auf Flurstück und andere Flurstücke ausblenden" href="javascript:zoom2object(<? echo $this->qlayerset[$i]['Layer_ID'];?>, 'Polygon', 'ax_flurstueck', 'wkb_geometry', '<?php echo $flst->oid; ?>', 'true');"><div class="button zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
													
											<? }
											if (in_array('kolibri', $kvwmap_plugins) AND $this->Stelle->isFunctionAllowed('Kolibristart')) { ?>
												<a href="kvwkol://FlurstKennz=<?php echo $flst->FlurstKennz; ?>" onclick="message('Öffne Flurstück <?php echo $flst->FlurstKennz; ?> in Kolibri.');">
													<div class="fstanzeigehover">&nbsp;&nbsp;Öffnen in Kolibri&nbsp;&nbsp;</div>
												</a><?php
											} ?>

											<div class="fstanzeigehover">
												&nbsp;&nbsp;
												Auszug:
												<select style="width: 130px" onchange="this.options[this.selectedIndex].onchange();this.selectedIndex=0">
													<option>-- Auswahl --</option>
													<? if($this->Stelle->funktionen['MV0510']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0510&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücksnachweis</option><? } ?>
													<? if($this->Stelle->funktionen['MV0550']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0550&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücks- und Eigentumsnachweis</option><? } ?>
													<? if($this->Stelle->funktionen['MV0520']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0520&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücksnachweis mit Bodenschätzung</option><? } ?>
													<? if($this->Stelle->funktionen['MV0560']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0560&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücks- und Eigentumsnachweis mit Bodenschätzung</option><? } ?>

													<? if($this->Stelle->funktionen['ALB-Auszug 30']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALB_Anzeige&formnummer=30&wz=1&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurst&uuml;cksdaten</option><? } ?>
													<? if($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALB_Anzeige&formnummer=35&wz=1&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurst&uuml;cksdaten&nbsp;mit&nbsp;Eigent&uuml;mer</option><? } ?>
													<? if($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']){ ?><option onchange="window.open('index.php?go=ALB_Anzeige&formnummer=40&wz=1&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Eigent&uuml;merdaten&nbsp;zum&nbsp;Flurst&uuml;ck</option><? } ?>
													
													<?
														if(!empty($generische_auszuege)){
															foreach($generische_auszuege as $layer_id => $layouts){
																foreach($layouts as $layout){ ?>
																	<option onchange="window.open('index.php?go=generischer_Flurstuecksauszug&selected_layer_id=<? echo $layer_id; ?>&formnummer=<? echo $layout['id']; ?>&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')"><? echo $layout['name']; ?></option>
													<?		}
															}
														}
													?>
												</select>
												&nbsp;&nbsp;
											</div>
										</div>
									</td>
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
			<td>Das Flurstück mit Kennzeichen: <?php echo $flurstkennz; ?> ist nicht in der aktuellen<br> PostGIS-Datenbank enthalten. Aktualisieren Sie die ALB und ALK-Daten.</td>
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
            </a><?

						global $kvwmap_plugins;
						if (in_array('kolibri', $kvwmap_plugins) AND $this->Stelle->isFunctionAllowed('Kolibristart')) { ?>
							<a href="javascript:send_selected_flurst('kvwkol', '', '', '_blank');">
								<div class="fstanzeigehover">
									&nbsp;&nbsp;
									Öffnen in Kolibri
									&nbsp;&nbsp;
								</div>
							</a><?
						} ?>

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
				<select style="width: 130px" onchange="this.options[this.selectedIndex].onchange();this.selectedIndex=0">
					<option>-- Auswahl --</option>
					<? if($this->Stelle->funktionen['MV0510']['erlaubt']){ ?><option onchange="send_selected_flurst('ALKIS_Auszug', 'MV0510', 1, '_blank');">Flurstücksnachweis</option><? } ?>
					<? if($this->Stelle->funktionen['MV0550']['erlaubt']){ ?><option onchange="send_selected_flurst('ALKIS_Auszug', 'MV0550', 1, '_blank');">Flurstücks- und Eigentumsnachweis</option><? } ?>
					<? if($this->Stelle->funktionen['MV0520']['erlaubt']){ ?><option onchange="send_selected_flurst('ALKIS_Auszug', 'MV0520', 1, '_blank');">Flurstücksnachweis mit Bodenschätzung</option><? } ?>
					<? if($this->Stelle->funktionen['MV0560']['erlaubt']){ ?><option onchange="send_selected_flurst('ALKIS_Auszug', 'MV0560', 1, '_blank');">Flurstücks- und Eigentumsnachweis mit Bodenschätzung</option><? } ?>

					<? if($this->Stelle->funktionen['ALB-Auszug 30']['erlaubt']){ ?><option onchange="send_selected_flurst('ALB_Anzeige', '30', 1, '_blank');">Flurst&uuml;cksdaten</option><? } ?>
					<? if($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']){ ?><option onchange="send_selected_flurst('ALB_Anzeige', '35', 1, '_blank');">Flurst&uuml;cksdaten&nbsp;mit&nbsp;Eigent&uuml;mer</option><? } ?>
					<? if($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']){ ?><option onchange="send_selected_flurst('ALB_Anzeige', '40', 1, '_blank');">Eigent&uuml;merdaten&nbsp;zum&nbsp;Flurst&uuml;ck</option><? } ?>
					
					<?
						if(!empty($generische_auszuege)){
							foreach($generische_auszuege as $layer_id => $layouts){
								foreach($layouts as $layout){ ?>
									<option onchange="currentform.selected_layer_id.value=<? echo $layer_id; ?>;send_selected_flurst('generischer_Flurstuecksauszug', '<? echo $layout['id']; ?>', 1, '_blank');"><? echo $layout['name']; ?></option>
					<?		}
							}
						}
					?>
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
<input type="hidden" name="selected_layer_id" value="<? echo $this->formvars['selected_layer_id']; ?>">

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