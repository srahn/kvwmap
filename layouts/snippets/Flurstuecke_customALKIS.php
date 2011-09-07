
<script language="JavaScript" type="text/javascript">
<!--
function send_selected_flurst(go, formnummer, wz, target){
	document.GUI.go_backup.value=document.GUI.go.value;	
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
  document.GUI.target = '';
  if(target == '_blank'){
    document.GUI.target = '_blank';
  }
  document.GUI.go.value=go;
  document.GUI.FlurstKennz.value=flurstkennz;
  document.GUI.formnummer.value=formnummer;
  document.GUI.wz.value=wz;
  document.GUI.submit();
}

function browser_switch(go){
  if(navigator.appName == 'Microsoft Internet Explorer'){
    send_selected_flurst(go, '', '', '_blank');
  }
  else{
    send_selected_flurst(go, '', '', '');
  }
}

function backto(go){
  document.GUI.go.value=go;
  document.GUI.submit();
}


-->
</script>
<a name="anfang"></a>
<h2>Flurst&uuml;cke</h2>
<table border="0" cellpadding="2" cellspacing="0">
<?php
	$forall = false;
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) { ?>
		<br><br>
    <u><? echo $anzObj; ?> Flurstück<? if ($anzObj>1) { echo "e"; } ?> gefunden:</u>
    <br>

    <?
    for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
      if($this->qlayerset[$i]['attributes']['privileg'][$this->qlayerset[$i]['attributes']['name'][$j]] != ''){
        $privileg[$this->qlayerset[$i]['attributes']['name'][$j]] = true;
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
      $flst->readALB_Data($flurstkennz);
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

              <? if($privileg['flurstkennz']){ ?>
              <tr>
                <td align="right"><strong>Flurst&uuml;ck&nbsp;</strong></td>
                <td> <? echo $flst->FlurstNr; ?>&nbsp;(<?php echo $flst->Flurstkennz_alt; ?>)</td>
              </tr>
              <? }
          $both = ($privileg['gemkgname'] AND $privileg['gemkgschl']);
          if($privileg['gemkgname'] OR $privileg['gemkgschl']){
              ?>
              <tr>
                <td align="right"><strong>Gemarkung&nbsp;</strong></td>
                <td><?php if($privileg['gemkgname']){echo $flst->GemkgName;} ?> <?php if($both){ echo '('.$flst->GemkgSchl.')';} elseif($privileg['gemkgschl']){ echo $flst->GemkgSchl;}?></td>
              </tr>
              <? }
              if($privileg['flurnr']){ ?>
              <tr>
                <td height="20" align="right"><strong>Flur&nbsp;</strong></td>
                <td><?php echo $flst->FlurNr; ?></td>
              </tr>
              <? }
              $both = ($privileg['gemeinde'] AND $privileg['gemeindename']);
              if($privileg['gemeinde'] OR $privileg['gemeindename']){
              ?>
              <tr>
                <td align="right"><strong>Gemeinde&nbsp;</strong></td>
                <td><?php if($privileg['gemeindename']){ echo $flst->GemeindeName;} ?> <?php if($both){ echo '('.$flst->GemeindeID.')';} elseif($privileg['gemeinde']){ echo $flst->GemeindeID;} ?></td>
              </tr>
              <? }
              $both = ($privileg['kreisname'] AND $privileg['kreisid']);
              if($privileg['kreisname'] OR $privileg['kreisid']){
              ?>
              <tr>
                <td align="right"><strong>Kreis&nbsp;</strong></td>
                <td><?php if($privileg['kreisname']){ echo $flst->KreisName;} ?> <?php if($both){echo '('.$flst->KreisID.')';} elseif($privileg['kreisid']){ echo $flst->KreisID;} ?></td>
              </tr>
          <? }
          $both = ($privileg['finanzamtname'] AND $privileg['finanzamt']);
          if($privileg['finanzamtname'] OR $privileg['finanzamt']){
          ?>
              <tr>
                <td align="right"><strong> Finanzamt&nbsp;</strong></td>
                <td><?php if($privileg['finanzamtname']){ echo $flst->FinanzamtName;} ?> <?php if($both){echo '('.$flst->FinanzamtSchl.')';} elseif($privileg['finanzamt']){ echo $flst->FinanzamtSchl; } ?></td>
              </tr>
          <? }
          $both = ($privileg['forstname'] AND $privileg['forstschluessel']);
          if($privileg['forstname'] OR $privileg['forstschluessel']){
          ?>
              <tr>
                <td align="right"><strong>Forstamt&nbsp;</strong></td>
                <td><?php if($privileg['forstname']){echo $flst->Forstamt['name'];} ?> <?php if($both){echo '(00'.$flst->Forstamt['schluessel'].')';} elseif($privileg['forstschluessel']){ echo '00'.$flst->Forstamt['schluessel'];} ?></td>
              </tr>
          <? }
          if($privileg['flaeche']){ ?>
              <tr>
                <td align="right"><strong>Fl&auml;che&nbsp;</strong></td>
                <td><?php echo $flst->ALB_Flaeche; ?>m&sup2;</td>
              </tr>
          <? }
          $both = ($privileg['amtsgerichtname'] AND $privileg['amtsgerichtnr']);
          if($privileg['amtsgerichtname'] OR $privileg['amtsgerichtnr']){
          ?>
          <tr>
                <td align="right"><b>Amtsgericht:</b>&nbsp;</td>
                <td><?php if($privileg['amtsgerichtnr']){echo $flst->Amtsgericht['schluessel'];} ?>&nbsp;&nbsp;<?php if($privileg['amtsgerichtname']){ echo $flst->Amtsgericht['name'];} ?></td>
              </tr>
              <? }
              $both = ($privileg['grundbuchbezirkname'] AND $privileg['grundbuchbezirkschl']);
          if($privileg['grundbuchbezirkname'] OR $privileg['grundbuchbezirkschl']){
          ?>
              <tr>
                <td align="right"><b>Grundbuchbezirk:</b>&nbsp;</td>
                <td><?php if($privileg['grundbuchbezirkschl']){ echo $flst->Grundbuchbezirk['schluessel'];} ?>&nbsp;&nbsp;<?php if($privileg['grundbuchbezirkname']){ echo $flst->Grundbuchbezirk['name'];} ?></td>
              </tr>
          <? }
          if($privileg['lagebezeichnung']){ ?>
              <tr>
                <td align="right" valign="top"><strong>Lage:&nbsp;</strong></td>
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
          if($privileg['entsteh']){ ?>
              <tr>
                <td align="right"><strong>Entstehung&nbsp;</strong></td>
                <td><?php echo $flst->Entstehung; ?></td>
              </tr>
          <? }
          if($privileg['letzff']){ ?>
              <tr>
                <td align="right"><strong>Fortf&uuml;hrung&nbsp;</strong></td>
                <td><?php echo $flst->LetzteFF; ?></td>
              </tr>
          <? }
          if($privileg['karte']){ ?>
              <tr>
                <td align="right"><strong> Flurkarte/Ri&szlig;&nbsp;</strong></td>
                <td><?php echo $flst->Flurkarte; ?></td>
              </tr>
              <? }
              if($privileg['status']){ ?>
              <tr>
                <td align="right"><strong> Status&nbsp;</strong></td>
                <td><?php if ($flst->Status=="H") { echo "historisches"; } else { echo "aktuelles"; } echo "&nbsp;Flurst&uuml;ck&nbsp;(".$flst->Status.")"; ?></td>
              </tr>
              <? } ?>
              <?php if ($privileg['vorgaenger'] AND $flst->Vorgaenger != '') { ?>
          <tr>
              <td align="right"><strong>Vorgänger</strong></td>
              <td>
                <?php
                for($v = 0; $v < count($flst->Vorgaenger); $v++){ ?>
                  <a href="index.php?go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Vorgaenger[$v]['vorgaenger']; ?>">
                  <? echo $flst->Vorgaenger[$v]['vorgaenger'].' (H)<br>'; ?>
                  </a>
                <? } ?>
                </td>
                <td>
                <? if (count($flst->Vorgaenger) > 1){?>
                  <a href="index.php?go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Vorgaenger[0]['vorgaenger'];
                  for($v = 1; $v < count($flst->Vorgaenger); $v++)
                  echo ';'.$flst->Vorgaenger[$v]['vorgaenger']; ?>
                  ">alle</a>
                <? } ?>
              </td>
          </tr>
          <? } ?>
          <?php if ($privileg['nachfolger'] AND $flst->Nachfolger != '') { ?>
          <tr>
              <td align="right" valign="top"><strong>Nachfolger</strong></td>
              <td>
                <?php
                for($v = 0; $v < count($flst->Nachfolger); $v++){ ?>
                  <a href="index.php?go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Nachfolger[$v]['nachfolger']; ?>">
                  <? echo $flst->Nachfolger[$v]['nachfolger'];
                  if($flst->Nachfolger[$v]['status'] == 'H'){
                    echo ' ('.$flst->Nachfolger[$v]['status'].')';
                  }
                  echo '<br>';
                } ?>
                </a>
              </td>
              <td>
              <? if(count($flst->Nachfolger) > 1){ ?>
                <a href="index.php?go=Flurstueck_Anzeigen&FlurstKennz=<?php echo $flst->Nachfolger[0]['nachfolger'];
                for($v = 1; $v < count($flst->Nachfolger); $v++)
                echo ';'.$flst->Nachfolger[$v]['nachfolger']; ?>
                ">alle</a>
              <? } ?>
              </td>
          </tr>
          <?php } ?>

              <?
              if($this->Stelle->isFunctionAllowed('Bauakteneinsicht')){
            $this->bau = new Bauauskunft($this->baudatabase);
            $searchvars['flurstkennz'] = $flst->Flurstkennz_alt;
              $this->bau->getbaudaten($searchvars);
              if(count($this->bau->baudata) != 0){
              ?>
          <tr>
              <td align="right"><strong> Baudaten&nbsp;</strong></td>
              <td><a href="index.php?go=Bauauskunft_Suche_Suchen&flurstkennz=<? echo $flst->FlurstKennz; ?>&distinct=1">anzeigen</a></td>
            </tr>
              <?
              }
          } # ende Bauakteneinsicht
              ?>

            </table>
          </td>
        </tr>
				
        <?php if ($privileg['klassifizierung'] AND $flst->Klassifizierung[0]['tabkenn']!='') { ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td align="right"><strong>Gesetzl.&nbsp;Klassifizierung</strong></td>
              <td align="right"><?php echo $flst->Klassifizierung[0]['flaeche']; ?> m&sup2;</td>
              <td><?php echo $flst->Klassifizierung[0]['tabkenn'].'-'.$flst->Klassifizierung[0]['klass']; ?></td>
              <td><?php echo $flst->Klassifizierung[0]['bezeichnung']; ?></td>
            </tr>
            <? for($j = 1; $j < count($flst->Klassifizierung)-1; $j++){ ?>
            <tr>
              <td align="right">&nbsp;</td>
              <td align="right"><?php echo $flst->Klassifizierung[$j]['flaeche']; ?> m&sup2;</td>
              <td><?php echo $flst->Klassifizierung[$j]['tabkenn'].'-'.$flst->Klassifizierung[$j]['klass']; ?></td>
              <td><?php echo $flst->Klassifizierung[$j]['bezeichnung']; ?></td>
            </tr>
            <? } ?>
          </table>
          </td>
         </tr>
        <?php } ?>
        <?php if ($privileg['freitext'] AND count($flst->FreiText)>0) { ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr valign="top">
              <td align="right"><strong>Zus&auml;tzliche&nbsp;Angaben</strong></td>
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
        <?php if ($privileg['hinweis'] AND $flst->Hinweis[0]['hinwzflst']!='') { ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td valign="top"><strong>Hinweise:</strong>&nbsp;</td>
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
        <?php if ($privileg['baulasten'] != '' AND count($flst->Baulasten)>0) { ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td><strong>Baulastenblatt-Nr</strong></td>
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
        if (($privileg['verfahren'] OR $privileg['ausfstelle']) AND $flst->Verfahren[0]['flurstkennz']!='') {
          for($j = 0; $j < count($flst->Verfahren); $j++){
          ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
            <?php if ($privileg['ausfstelle'])
            { ?>
              <tr valign="top">
			          <td align="right"><strong>Ausf&uuml;hrende&nbsp;Stelle</strong></td>
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
              if ($privileg['verfahren']){ ?>
              <tr valign="top">
			          <td align="right"><strong>Verfahren</strong></td>
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
        <?php if ($privileg['nutzung']){ ?>
        <tr>
          <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="3"><strong>Nutzung</strong></td>
              </tr>
              <tr>
                <td><b>Fl&auml;che&nbsp;</b></td>
                <td><b>Nutzung&nbsp;</b></td>
                <td><b>Bezeichnung</b></td>
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
        <? if($privileg['eigentuemer']){?>
        <tr>
        <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="3"><strong>Eigentümer</strong></td>
              </tr>
            <? for ($g=0;$g<count($flst->Grundbuecher);$g++) {
            $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
            for ($b=0;$b<count($flst->Buchungen);$b++) {
                if($privileg['bestandsnr']){
                  $BestandStr ='<a href="index.php?go=Grundbuchblatt_Auswaehlen_Suchen&selBlatt='.$flst->Buchungen[$b]['bezirk'].'-'.$flst->Buchungen[$b]['blatt'].'">'.$flst->Buchungen[$b]['bezirk'].'-'.intval($flst->Buchungen[$b]['blatt']).'</a>';
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
                    $eigentuemer = new eigentuemer(NULL, NULL);
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
                echo '<span style="font-size:90%;"><b><u>Aktualisierte Adresse ('.$aendatum.' - '.$user->Name.'):</u></b><br>';
                    if($adressaenderungen['neu_name3'] == ''){
                      echo '&nbsp;&nbsp;<b>(Name3 leer)</b><br>';
                    }
                    else{
                      echo '&nbsp;&nbsp;<b>'.$adressaenderungen['neu_name3'].'</b><br>';
                    }
                    if($adressaenderungen['neu_name4'] == ''){
                      echo '&nbsp;&nbsp;<b>(Name4 leer)</b><br>';
                    }
                    else{
                      echo '&nbsp;&nbsp;<b>'.$adressaenderungen['neu_name4'].'</b></span><br>';
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
        ?>&GemID=<?php echo $this->formvars['GemID'];
        ?>&StrID=<?php echo $this->formvars['StrID'];
        ?>&HausID=<?php echo $this->formvars['HausID'];
        ?>">zur Adresssuche</a> |
        <a href="index.php?go=Namen_Auswaehlen&name1=<?php echo $this->formvars['name1'];
        ?>&name2=<?php echo $this->formvars['name2'];
        ?>&name3=<?php echo $this->formvars['name3'];
        ?>&name4=<?php echo $this->formvars['name4'];
        ?>">zur Namensuche</a>
        <? if($flst->Status != 'H'){
        		$forall = true; 
        ?>
           |
          <a href="index.php?go=ZoomToFlst&FlurstKennz=<?php echo $flst->FlurstKennz; ?>">Kartenausschnitt</a>
          | <a href="index.php?go=showFlurstuckKoordinaten&FlurstKennz=<?php echo $flst->FlurstKennz; ?>" target="_blank">Koordinaten</a>
          </td>
          </tr>
          <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
          <td colspan="2">
          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=30&wz=1" target="_blank">ALB-Auszug&nbsp;30&nbsp;mit&nbsp;WZ</a>
          <?php
          $this->getFunktionen();
          if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=35&wz=1" target="_blank">ALB-Auszug&nbsp;35&nbsp;mit&nbsp;WZ</a>
          <?php }
          if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=40&wz=1" target="_blank">ALB-Auszug&nbsp;40&nbsp;mit&nbsp;WZ</a>
          <?php } ?>
          </td>
          </tr>
          <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
          <td colspan="2">
          <?php
          if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=30&wz=0" target="_blank">ALB-Auszug&nbsp;30&nbsp;ohne&nbsp;WZ</a>
          <?php } ?>
          <?php
          if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=35&wz=0" target="_blank">ALB-Auszug&nbsp;35&nbsp;ohne&nbsp;WZ</a>
          <?php }
          if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=40&wz=0" target="_blank">ALB-Auszug&nbsp;40&nbsp;ohne&nbsp;WZ</a>
          <?php } ?>
        	<? }
        	 else{ ?>
        	 	</td>
          </tr>
          <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
          <td colspan="2">
        	 	<a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=30&wz=1" target="_blank">ALB-Auszug&nbsp;30&nbsp;mit&nbsp;WZ</a>
        	<? if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          	| <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=30&wz=0" target="_blank">ALB-Auszug&nbsp;30&nbsp;ohne&nbsp;WZ</a>
          <?php } ?>
        	 <? } ?>
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
        <b>Für alle ausgewählten Flurstücke:</b><br>
      </td>
    </tr>
    <? if($forall == true){ ?>
      <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
          <td colspan="2">
              <a href="javascript:send_selected_flurst('ALB_Anzeige', 30, 1, '_blank');">ALB-Auszug&nbsp;30&nbsp;mit&nbsp;WZ</a>
            <?php
            if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) { ?>
              | <a href="javascript:send_selected_flurst('ALB_Anzeige', 35, 1, '_blank');">ALB-Auszug&nbsp;35&nbsp;mit&nbsp;WZ</a>
            <?php }
            if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']) { ?>
              | <a href="javascript:send_selected_flurst('ALB_Anzeige', 40, 1, '_blank');">ALB-Auszug&nbsp;40&nbsp;mit&nbsp;WZ</a>
            <?php } ?>
          </td>
      </tr>
      <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
          <td colspan="2">
            <?php
            $this->getFunktionen();
            if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
                <a href="javascript:send_selected_flurst('ALB_Anzeige', 30, 0, '_blank');">ALB-Auszug&nbsp;30&nbsp;ohne&nbsp;WZ</a>
            <?php } ?>
            <?php
            if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
                | <a href="javascript:send_selected_flurst('ALB_Anzeige', 35, 0, '_blank');">ALB-Auszug&nbsp;35&nbsp;ohne&nbsp;WZ</a>
            <?php }
            if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
                | <a href="javascript:send_selected_flurst('ALB_Anzeige', 40, 0, '_blank');">ALB-Auszug&nbsp;40&nbsp;ohne&nbsp;WZ</a>
            <?php } ?>
          </td>
      </tr>
    <? } ?>
    <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
      <td colspan="2">
        <a href="javascript:browser_switch('Flurstuecks-CSV-Export');">CSV-Export</a>&nbsp;|&nbsp;
        <? if($privileg['eigentuemer']){?><a href="javascript:send_selected_flurst('Eigentümer-CSV-Export', '', '', '');">CSV-Export-Eigentümer</a>&nbsp;|&nbsp;<?}?>
        <a href="javascript:send_selected_flurst('Nutzungsarten-CSV-Export', '', '', '');">CSV-Export_Nutzungsarten</a>
        <? if($flst->Status != 'H'){ ?>&nbsp;|&nbsp;<a href="javascript:send_selected_flurst('ZoomToFlst', '', '', '');">Kartenausschnitt</a><? } ?>
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
    ?><br><strong><font color="#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
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