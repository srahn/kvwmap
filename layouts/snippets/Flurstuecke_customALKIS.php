
<script language="JavaScript" type="text/javascript">
<!--
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


-->
</script>
<br>
<a name="anfang"></a>
<h2>Flurst&uuml;cke</h2>
<table border="0" cellpadding="2" cellspacing="0">
<?php
	$this->Stelle->getFunktionen();
	$forall = false;
	if($i == '')$i = 0;
  $anzObj=count($this->qlayerset[$i]['shape']);	
  if ($anzObj>0) { ?>
		<br><br>
    <u><? echo $anzObj; ?> Flurstück<? if ($anzObj>1) { echo "e"; } ?> gefunden:</u>
    <br>

    <?
    for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
      if($this->qlayerset[$i]['attributes']['privileg'][$this->qlayerset[$i]['attributes']['name'][$j]] != ''){
        $privileg_[$this->qlayerset[$i]['attributes']['name'][$j]] = true;
        if($j > 0){ $attribute .= ';';}
        $attribute .= $this->qlayerset[$i]['attributes']['name'][$j];
      }
    }

    for ($a=0;$a<$anzObj;$a++) { // 2007-11-13_mh
      $flurstkennz_a=$this->qlayerset[$i]['shape'][$a]['flurstkennz'];      
      $gemkg=substr($flurstkennz_a, 0, 6);
      $flur=substr($flurstkennz_a, 6, 3);
      $zaehler=ltrim(substr($flurstkennz_a, 9, 5), '0');
      $nenner=ltrim(rtrim(substr($flurstkennz_a, 14, 6), '_'), '0');
      if ($nenner!='') {
        $nenner="/".$nenner;
      }
      echo '<a href="#'.$flurstkennz_a.'">Gemarkung: '.$gemkg.' - Flur: '.ltrim($flur,"0").' - Flurst&uuml;ck: '.$zaehler.$nenner.'</a><br>';
    }


    for ($k=0;$k<$anzObj;$k++) {
      $flurstkennz=$this->qlayerset[$i]['shape'][$k]['flurstkennz'];
      #echo '<br>'.$flurstkennz; #2005-11-30_pk
      $flst=new flurstueck($flurstkennz,$this->pgdatabase);
      $flst->readALB_Data($flurstkennz, $this->formvars['hist_alb']);
    #  $flst->isALK($flurstkennz);
      if ($flst->FlurstNr!='') {
        if($k > 0){
          $Flurstueckskennz .= ';';
        }
        $Flurstueckskennz .= $flurstkennz;
?>

  <!-- Für jedes einzelne Flurstück -->
  <tr>
    <td colspan="2">&nbsp;<a name="<? echo $flurstkennz; ?>" href="#<? echo $flurstkennz; ?>"></a></td>
  </tr>
  <tr>
    <td>
    <div style="position:relative; top:0px; right:0px; padding:=px; border-color:<?php echo BG_DEFAULT ?>; border-width:1px; border-style:solid;">
      <table border="0" cellspacing="0" cellpadding="0">
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
                <td> <? echo $flst->FlurstNr; ?>&nbsp;(<?php echo $flst->Flurstkennz_alt; ?>)</td>
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
                <td align="right"><span class="fett">Amtsgericht:</span>&nbsp;</td>
                <td><?php if($privileg_['amtsgerichtnr']){echo $flst->Amtsgericht['schluessel'];} ?>&nbsp;&nbsp;<?php if($privileg_['amtsgerichtname']){ echo $flst->Amtsgericht['name'];} ?></td>
              </tr>
              <? }
              $both = ($privileg_['grundbuchbezirkname'] AND $privileg_['grundbuchbezirkschl']);
          if($privileg_['grundbuchbezirkname'] OR $privileg_['grundbuchbezirkschl']){
          ?>
              <tr>
                <td align="right"><span class="fett">Grundbuchbezirk:</span>&nbsp;</td>
                <td><?php if($privileg_['grundbuchbezirkschl']){ echo $flst->Grundbuchbezirk['schluessel'];} ?>&nbsp;&nbsp;<?php if($privileg_['grundbuchbezirkname']){ echo $flst->Grundbuchbezirk['name'];} ?></td>
              </tr>
          <? }
          if($privileg_['lagebezeichnung']){ ?>
              <tr>
                <td align="right" valign="top"><span class="fett">Lage:&nbsp;</span></td>
                <td>
                <?php
                $anzStrassen=count($flst->Adresse);
                for ($s=0;$s<$anzStrassen;$s++) {
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
                <td><?php if ($flst->endet!="" OR $flst->hist_alb != '') { echo "historisches&nbsp;Flurst&uuml;ck"; if($flst->endet != '')echo "&nbsp;(endet: ".$flst->endet.")"; } else { echo "aktuelles&nbsp;Flurst&uuml;ck"; }  ?></td>
              </tr>
              <? } ?>
              <?php if ($privileg_['vorgaenger'] AND $flst->Vorgaenger != '') { ?>
          <tr>
              <td align="right"><span class="fett">Vorgänger</span></td>
              <td>
                <?php
                for($v = 0; $v < count($flst->Vorgaenger); $v++){ ?>
                  <a href="javascript:overlay_link('go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Vorgaenger[$v]['vorgaenger']; ?>&without_temporal_filter=true&hist_alb=<? echo $flst->Vorgaenger[$v]['hist_alb']; ?>');">
                  <? echo formatFlurstkennzALK($flst->Vorgaenger[$v]['vorgaenger']).' (H)<br>'; ?>
                  </a>
                <? } ?>
                </td>
                <td>
                <? if (count($flst->Vorgaenger) > 1){?>
                  <a href="javascript:overlay_link('go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Vorgaenger[0]['vorgaenger'];
                  for($v = 1; $v < count($flst->Vorgaenger); $v++)echo ';'.$flst->Vorgaenger[$v]['vorgaenger']; ?>&without_temporal_filter=true&hist_alb=<? echo $flst->Vorgaenger[0]['hist_alb']; ?>');">alle</a>
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
                  <a href="javascript:overlay_link('go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Nachfolger[$v]['nachfolger']; ?>&without_temporal_filter=true&hist_alb=<? echo $flst->Nachfolger[$v]['hist_alb']; ?>');">
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
                echo ';'.$flst->Nachfolger[$v]['nachfolger']; ?>&without_temporal_filter=true&hist_alb=<? echo $flst->Nachfolger[0]['hist_alb']; ?>');">alle</a>
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
	        		$ratio = $flst->ALB_Flaeche/$flst->Klassifizierung[0]['flstflaeche'];
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
		            <?  $emzges_222 = 0; $emzges_223 = 0;
		            	$flaeche_222 = 0; $flaeche_223 = 0;
		            	for($j = 0; $j < count($flst->Klassifizierung); $j++){
		            	$wert=$flst->Klassifizierung[$j]['wert'];
									$flst->Klassifizierung[$j]['flaeche'] = $flst->Klassifizierung[$j]['flaeche'] * $ratio;
				          $emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
				          if($flst->Klassifizierung[$j]['objart'] == 1000){
				          	$emzges_222 = $emzges_222 + $emz;
				          	$flaeche_222 = $flaeche_222 + $flst->Klassifizierung[$j]['flaeche'];
				          }
				          if($flst->Klassifizierung[$j]['objart'] == 3000){
				          	$emzges_223 = $emzges_223 + $emz;
				          	$flaeche_223 = $flaeche_223 + $flst->Klassifizierung[$j]['flaeche'];
				          }
		            	?>
		            <tr>
		              <td></td>
		              <td><? echo round($flst->Klassifizierung[$j]['flaeche']); ?> m&sup2&nbsp;</td>
		              <td><? echo $flst->Klassifizierung[$j]['label']; ?></td>
		              <td>EMZ: <? echo $emz; ?></td>
		            </tr>
		            <? } // end for
		            $nichtgeschaetzt=round($flst->ALB_Flaeche-$flaeche_222-$flaeche_223);
		            if ($nichtgeschaetzt>0) { ?>
          				<tr>
          					<td></td>
          					<td colspan="3">nicht geschätzt: <? echo $nichtgeschaetzt; ?> m&sup2;</td>
          				</tr>
		            <? }
        			if ($emzges_222 > 0)  {
        					$BWZ_222 = round($emzges_222/$flaeche_222*100);
        					?>
          				<tr>
          					<td></td>
          					<td colspan="3">Ackerland gesamt: EMZ <? echo $emzges_222; ?>, BWZ <? echo $BWZ_222; ?></td>
          				</tr>
        			<?	}
        			if ($emzges_223 > 0) {
        					$BWZ_223 = round($emzges_223/$flaeche_223*100);
        					?>
          				<tr>
          					<td></td>
          					<td colspan="3">Grünland gesamt: EMZ <? echo $emzges_223; ?>, BWZ <? echo $BWZ_223; ?></td>
          				</tr>
        			<?	} ?>
		          </table>
		          </td>
		         </tr>
	        <?
	        	}
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
        <?php if ($privileg_['hinweis'] AND $flst->Hinweis[0]['hinwzflst']!='') { ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td valign="top"><span class="fett">Hinweise:</span>&nbsp;</td>
              <td>
              <?php
              for($h = 0; $h < count($flst->Hinweis); $h++){
                echo $flst->Hinweis[$h]['bezeichnung'].'<br>';
              }
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
			          if (strlen($flst->Nutzung[$j][bezeichnung])>60) {
			            $needle=array(
			             strrpos(substr($flst->Nutzung[$j][bezeichnung],0,60),','),
			             strrpos(substr($flst->Nutzung[$j][bezeichnung],0,60),'-'),
			             strrpos(substr($flst->Nutzung[$j][bezeichnung],0,60),'/'),
			             strrpos(substr($flst->Nutzung[$j][bezeichnung],0,60),' ')
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
        <? if($privileg_['eigentuemer']){?>
        <tr>
        <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="3"><span class="fett">Eigentümer</span></td>
              </tr>
            <? for ($g=0;$g<count($flst->Grundbuecher);$g++) {
            $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],$this->formvars['hist_alb']);
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
              <? }
              $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
                  $anzEigentuemer=count($Eigentuemerliste);
                  for ($e=0;$e<$anzEigentuemer;$e++) { ?>
              <tr>
              <td valign="top"><? echo $Eigentuemerliste[$e]->Nr ?>&nbsp;&nbsp;&nbsp;</td>
              <td valign="top">
              <?
                    $anzNamenszeilen=count($Eigentuemerliste[$e]->Name);
                    $Eigentuemerliste[$e]->Name_bearb = $Eigentuemerliste[$e]->Name;
                    $Eigentuemerliste[$e]->Name_bearb[0] = str_replace(',,,', '', $Eigentuemerliste[$e]->Name_bearb[0]);
              $Eigentuemerliste[$e]->Name_bearb[0] = str_replace(',,', ',', $Eigentuemerliste[$e]->Name_bearb[0]);
              if(substr($Eigentuemerliste[$e]->Name_bearb[0], strlen($Eigentuemerliste[$e]->Name_bearb[0])-1) == ','){
                $Eigentuemerliste[$e]->Name_bearb[0] = substr($Eigentuemerliste[$e]->Name_bearb[0], 0, strlen($Eigentuemerliste[$e]->Name_bearb[0])-1);
              }
              if(false AND $this->Stelle->isFunctionAllowed('Adressaenderungen')) {
                    $eigentuemer = new eigentuemer(NULL, NULL, $this->pgdatabase);
                    $adressaenderungen =  $eigentuemer->getAdressaenderungen($Eigentuemerliste[$e]->Name[0], $Eigentuemerliste[$e]->Name[1], $Eigentuemerliste[$e]->Name[2], $Eigentuemerliste[$e]->Name[3]);
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
                      if (!($Eigentuemerliste[$e]->Name_bearb[$n]=="" OR $Eigentuemerliste[$e]->Name_bearb[$n]=='.')) {
                          echo $Eigentuemerliste[$e]->Name_bearb[$n].'<br>';
                      }
                    }
                  if ($adressaenderungen['user_id'] != '') {
                echo '<span style="font-size:90%;"><span class="fett"><u>Aktualisierte Adresse ('.$aendatum.' - '.$user->Name.'):</u></span><br>';
                    if($adressaenderungen['neu_name3'] == ''){
                      echo '&nbsp;&nbsp;<span class="fett">(Name3 leer)</span><br>';
                    }
                    else{
                      echo '&nbsp;&nbsp;<span class="fett">'.$adressaenderungen['neu_name3'].'</span><br>';
                    }
                    if($adressaenderungen['neu_name4'] == ''){
                      echo '&nbsp;&nbsp;<span class="fett">(Name4 leer)</span><br>';
                    }
                    else{
                      echo '&nbsp;&nbsp;<span class="fett">'.$adressaenderungen['neu_name4'].'</span></span><br>';
                    }
                  }
                  ?>
                  </td>
                  <td valign="bottom">
                  <?
                  if(false AND $this->Stelle->isFunctionAllowed('Adressaenderungen')){
                    if ($adressaenderungen['user_id'] == '') {
                      echo '<img src="'.GRAPHICSPATH.'pfeil_links.gif" width="12" height="12" border="0">'; ?>&nbsp;<a target="_blank" href="index.php?go=neuer_Layer_Datensatz&close_after_saving=true&selected_layer_id=<? echo LAYER_ID_ADRESSAENDERUNGEN; ?>&attributenames[0]=name1&attributenames[1]=name2&attributenames[2]=name3&attributenames[3]=name4&attributenames[4]=neu_name3&attributenames[5]=neu_name4&attributenames[6]=user_id&values[0]=<? echo urlencode($Eigentuemerliste[$e]->Name[0]); ?>&values[1]=<? echo urlencode($Eigentuemerliste[$e]->Name[1]); ?>&values[2]=<? echo urlencode($Eigentuemerliste[$e]->Name[2]); ?>&values[3]=<? echo urlencode($Eigentuemerliste[$e]->Name[3]); ?>&values[4]=<? echo urlencode($Eigentuemerliste[$e]->Name[2]); ?>&values[5]=<? echo urlencode($Eigentuemerliste[$e]->Name[3]); ?>&values[6]=<? echo $this->user->id ?>">Adresse aktualisieren</a>
                  <?}
                    else {
                      echo '<img src="'.GRAPHICSPATH.'pfeil_links.gif" width="12" height="12" border="0">'; ?>&nbsp;<a target="_blank" href="index.php?go=Layer-Suche_Suchen&close_after_saving=true&selected_layer_id=<? echo LAYER_ID_ADRESSAENDERUNGEN; ?>&value_name1=<? echo urlencode($Eigentuemerliste[$e]->Name[0]); ?>&operator_name1==&value_name2=<? echo urlencode($Eigentuemerliste[$e]->Name[1]); ?>&operator_name2==&value_name3=<? echo urlencode($Eigentuemerliste[$e]->Name[2]); ?>&operator_name3==&value_name4=<? echo urlencode($Eigentuemerliste[$e]->Name[3]); ?>&operator_name4==&attributenames[0]=user_id&values[0]=<? echo $this->user->id ?>">Adresse &auml;ndern</a>
                  <?}
                  }?>
                    </td>
                  <tr>
                  <? if($Eigentuemerliste[$e]->Anteil != ''){ ?>
                  <tr>
                  	<td>zu <? echo $Eigentuemerliste[$e]->Anteil; ?></td>
                  </tr>
                  <? } ?>
                </table>
                </td>
                <? } ?>
              </tr>
              <? if($flst->Buchungen[$b]['zusatz_eigentuemer'] != ''){
									$flst->Buchungen[$b]['zusatz_eigentuemer'] = str_replace('zu', '<br>zu', $flst->Buchungen[$b]['zusatz_eigentuemer']);
									$flst->Buchungen[$b]['zusatz_eigentuemer'] = str_replace('<br>zu 1/', 'zu 1/', $flst->Buchungen[$b]['zusatz_eigentuemer']);
      						echo '<tr><td></td><td colspan="2">'.$flst->Buchungen[$b]['zusatz_eigentuemer'].'</td></tr>';
      			 			} ?>
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
      <table cellspacing="0" cellpading="0" border="0">
        <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
        <td colspan="2">
        <a href="index.php?go=Flurstueck_Auswaehlen&searchInExtent=<?php echo $this->searchInExtent;
        ?>&GemID=<?php echo $flst->GemeindeID;
        ?>&GemkgID=<?php echo $flst->GemkgSchl; ?>&FlurID=<?php echo $flst->FlurID;
        ?>&FlstID=<?php echo $flst->FlurstKennz; ?>">zur Flurstückssuche</a> |
        <a href="index.php?go=Adresse_Auswaehlen&searchInExtent=<?php echo $this->searchInExtent;
        ?>&GemID=<?php echo $flst->GemeindeID;
        ?>&StrID=<?php echo $this->formvars['StrID'];
        ?>&HausID=<?php echo $this->formvars['HausID'];
        ?>">zur Adresssuche</a>
           |
          <a href="index.php?go=ZoomToFlst&FlurstKennz=<?php echo $flst->FlurstKennz; ?>">Kartenausschnitt</a>
					|
					<!--a target="_blank" href="index.php?go=ALKIS_Auszug&formnummer=MV0510&FlurstKennz=<?php echo $flst->FlurstKennz; ?>">ALKIS-Auszug</a-->
					Auszug:
					<select style="width: 200px">
						<option>-- Auswahl --</option>
						<? if($this->Stelle->funktionen['MV0510']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0510&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücksnachweis</option><? } ?>
						<? if($this->Stelle->funktionen['MV0550']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0550&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücks- und Eigentumsnachweis</option><? } ?>
						<? if($this->Stelle->funktionen['MV0520']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0520&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücksnachweis mit Bodenschätzung</option><? } ?>
						<? if($this->Stelle->funktionen['MV0560']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0560&FlurstKennz=<?php echo $flst->FlurstKennz; ?>','_blank')">Flurstücks- und Eigentumsnachweis mit Bodenschätzung</option><? } ?>
					</select>
          </td>
          </tr>
          <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
          <td colspan="2">          
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
    <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
		  <td colspan="2">
        <a href="javascript:send_selected_flurst('Flurstuecks-CSV-Export', 'Flurstück', '', '');">CSV-Export FST</a>&nbsp;|&nbsp;
        <? if($privileg_['eigentuemer']){ ?>
        <a href="javascript:send_selected_flurst('Flurstuecks-CSV-Export', 'Eigentümer', '', '');">CSV-Export Eigent&uuml;mer</a>&nbsp;|&nbsp;
        <? } ?>
        <a href="javascript:send_selected_flurst('Flurstuecks-CSV-Export', 'Nutzungsarten', '', '');">CSV-Export NA</a>&nbsp;|&nbsp;
        <a href="javascript:send_selected_flurst('Flurstuecks-CSV-Export', 'Klassifizierung', '', '');">CSV-Export Klassifizierung</a>
  		  </td>
		</tr>
		<tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
		  <td>
        <a href="javascript:send_selected_flurst('ZoomToFlst', '');">Kartenausschnitt</a> | 
				Auszug:
				<select style="width: 200px">
					<option>-- Auswahl --</option>
					<? if($this->Stelle->funktionen['MV0510']['erlaubt']){ ?><option onclick="send_selected_flurst('ALKIS_Auszug', 'MV0510', 1, '_blank');">Flurstücksnachweis</option><? } ?>
					<? if($this->Stelle->funktionen['MV0550']['erlaubt']){ ?><option onclick="send_selected_flurst('ALKIS_Auszug', 'MV0550', 1, '_blank');">Flurstücks- und Eigentumsnachweis</option><? } ?>
					<? if($this->Stelle->funktionen['MV0520']['erlaubt']){ ?><option onclick="send_selected_flurst('ALKIS_Auszug', 'MV0520', 1, '_blank');">Flurstücksnachweis mit Bodenschätzung</option><? } ?>
					<? if($this->Stelle->funktionen['MV0560']['erlaubt']){ ?><option onclick="send_selected_flurst('ALKIS_Auszug', 'MV0560', 1, '_blank');">Flurstücks- und Eigentumsnachweis mit Bodenschätzung</option><? } ?>
				</select>
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