<h2>Flurst&uuml;cke</h2>
<table width="600" border="0" cellpadding="2" cellspacing="0">
<?php
  $sql_aktalb="SELECT (max(ffzeitraum_bis)) FROM alb_fortfuehrung;";
  $query=pg_query($sql_aktalb);
  $r_aktalb = pg_fetch_array($query);
  $aktalb = date("d.m.Y", mktime(0, 0, 0, substr($r_aktalb[0], 3, 2), substr($r_aktalb[0], 0, 2), substr($r_aktalb[0], 6, 4)));
  $sql_aktalk="SELECT (max(datumein)) FROM edbsdatei;";
  @$query=pg_query($sql_aktalk);
  @$r_aktalk = pg_fetch_array($query);
  if($r_aktalk){
  	$aktalk = date("d.m.Y", mktime(0, 0, 0, substr($r_aktalk[0], 3, 2), substr($r_aktalk[0], 0, 2), substr($r_aktalk[0], 6, 4)));
  }

  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {?>
	  <span style="font-size:80%;">
	  Stand ALB vom: <?php echo $aktalb; ?><br>
	  Stand ALK vom: <?php echo $aktalk; ?><br><br></span>
	  Anzahl der gefundenen Flurstücke: <?php echo $this->qlayerset[$i]['count'].'<br><br>';
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
?><tr> 
    <td valign="top"> 
      <table border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td align="right"><strong>Flurst&uuml;ck&nbsp;</strong></td>
          <td> <?php echo $flst->FlurstNr; ?> (<?php echo $flst->FlurstKennz; ?>)</td>
        </tr>
        <tr> 
          <td align="right"><strong>Gemarkung&nbsp;</strong></td>
          <td><?php echo $flst->GemkgName; ?> (<?php echo $flst->GemkgSchl; ?>)</td>
        </tr>
        <tr> 
          <td height="20" align="right"><strong>Flur&nbsp;</strong></td>
          <td><?php echo $flst->FlurNr; ?></td>
        </tr>
        <tr> 
          <td align="right"><strong>Gemeinde&nbsp;</strong></td>
          <td><?php echo $flst->GemeindeName; ?> (<?php echo $flst->GemeindeID; ?>)</td>
        </tr>
        <tr> 

          <td align="right"><strong>Kreis&nbsp;</strong></td>

          <td><?php echo $flst->KreisName; ?> (<?php echo $flst->KreisID; ?>)</td>

        </tr>

        <tr> 

          <td align="right"><strong> Finanzamt&nbsp;</strong></td>

          <td><?php echo $flst->FinanzamtName; ?> (<?php echo $flst->FinanzamtSchl; ?>)</td>

        </tr>

        <tr>

          <td align="right"><strong>Forstamt&nbsp;</strong></td>

          <td><?php echo $flst->Forstamt['name']; ?> (<?php echo '00'.$flst->Forstamt['schluessel']; ?>)</td>

        </tr>

      </table></td>

    <td valign="top"> 

      <table border="0" cellspacing="0" cellpadding="2">

        <tr> 

          <td align="right"><strong>Fl&auml;che&nbsp;</strong></td>

          <td><?php echo $flst->ALB_Flaeche; ?>m&sup2;</td>

        </tr>

        <tr> 

          <td align="right" valign="top"><strong>Lage:&nbsp;</strong></td>

          <td><?php

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

        <tr> 

          <td align="right"><strong>Entstehung&nbsp;</strong></td>

          <td><?php echo $flst->Entstehung; ?></td>

        </tr>

        <tr> 

          <td align="right"><strong>Fortf&uuml;hrung&nbsp;</strong></td>

          <td><?php echo $flst->LetzteFF; ?></td>

        </tr>

        <tr> 

          <td align="right"><strong> Flurkarte/Ri&szlig;&nbsp;</strong></td>

          <td><?php echo $flst->Flurkarte; ?></td>

        </tr>
        
        <?        
        	if($this->Stelle->isFunctionAllowed('Bauakteneinsicht')){
						$this->bau = new Bauauskunft($this->baudatabase);
						$searchvars['flurstkennz'] = $flst->FlurstKennz;
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

      </table></td>

  </tr>

<?php

        if ($flst->Klassifizierung['tabkenn']!='') {

          ?>

  <tr> 

    <td colspan="2">

        <table border="0" cellspacing="0" cellpadding="2">

  <tr>

    <td align="right"><strong>Gesetzl.&nbsp;Klassifizierung</strong></td>

    <td><?php echo $flst->Klassifizierung['tabkenn']; ?></td>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td align="right"><?php echo $flst->Klassifizierung['flaeche']; ?> m&sup2;</td>

    <td><?php echo $flst->Klassifizierung['tabkenn'].'-'.$flst->Klassifizierung['klass']; ?></td>

    <td><?php echo $flst->Klassifizierung['bezeichnung']; ?></td>

  </tr>

  <tr>

    <td align="right">&nbsp;</td>

    <td>&nbsp;</td>

    <td><?php echo $flst->Klassifizierung['abkuerzung']; ?></td>

  </tr>

</table>

</td>

</tr><?php 

        } 

        ?><?php

        if (count($flst->FreiText)>0) {

          ?>

  <tr> 

    <td colspan="2">

        <table border="0" cellspacing="0" cellpadding="2">

  <tr valign="top">

    <td align="right"><strong>Zus&auml;tzliche&nbsp;Angaben</strong></td>

    <td><?php

  for ($j=0;$j<count($flst->FreiText);$j++) {

    if ($j>0) { ?><br>

      <?php }

    echo $flst->FreiText[$j]['text'];

  }

  ?></td>

  </tr>

</table>

</td>

</tr><?php 

        } 

        ?><?php

        if ($flst->Hinweis[0]['hinwzflst']!='') {

          ?>

  <tr> 

    <td colspan="2">

        <table border="0" cellspacing="0" cellpadding="2">

  <tr>

    <td><strong>Hinweise:</strong>&nbsp;</td>
    <td><?php 
    for($h = 0; $h < count($flst->Hinweis); $h++){
    	echo $flst->Hinweis[$h]['bezeichnung'].'<br>';
    } 
    ?></td>

  </tr>

</table>

</td>

</tr><?php 

        } 

        ?><?php

        if (count($flst->Baulasten)>0) {

          ?>

  <tr> 

    <td colspan="2">

        <table border="0" cellspacing="0" cellpadding="2">

  <tr>

    <td><strong>Baulastenblatt-Nr</strong></td>

    <td><?php 

                   for ($b=0;$b<count($flst->Baulasten);$b++) {

                  echo " ".$flst->Baulasten[$b]['blattnr'];

               } 

         ?></td>

  </tr>

</table>

</td>

</tr><?php 

        } 

        ?><?php 

 if ($flst->Verfahren['flurstkennz']!='') {

?>

  <tr>

    <td colspan="2">

        <table border="0" cellspacing="0" cellpadding="2">

      <tr valign="top">

        <td align="right"><strong>Ausführende&nbsp;Stelle</strong></td>

        <td><?php echo $flst->Verfahren['ausfstelleid']; ?></td>

        <td><?php echo $flst->Verfahren['ausfstellename']; ?></td>

        </tr>

      <tr valign="top">

        <td align="right"><strong>Verfahren</strong></td>

        <td><?php echo $flst->Verfahren['verfnr']; ?></td>

        <td>(<?php echo $flst->Verfahren['verfbemid']; ?>)&nbsp;<?php echo $flst->Verfahren['verfbemerkung']; ?></td>

      </tr>

    </table>

    </td>

  </tr><?php

  }

   ?>

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

        for ($j=0;$j<$anzNutzung;$j++) {



    ?> 

          <tr> 

            <td align="right"><?php echo $flst->Nutzung[$j][flaeche]; ?> 

              m&sup2;&nbsp;</td>

            <td align="right">&nbsp;<?php echo $flst->Nutzung[$j][nutzungskennz]; ?>&nbsp;</td>

            <td><?php echo $flst->Nutzung[$j][bezeichnung];

if ($flst->Nutzung[$j][kurzbezeichnung]!='') {

  ?> (<?php echo $flst->Nutzung[$j][kurzbezeichnung]; ?>)<?php 

}



 ?></td>

          </tr>

          <?php }

    ?> 

        </table>

    </td></tr>

  <tr valign="top"> 

    <td colspan="2"> <br> 

      <table border="0" cellspacing="0" cellpadding="2">

        <tr> 

          <td align="right"><b>Amtsgericht:</b>&nbsp;</td>

          <td><?php echo $flst->Amtsgericht['schluessel']; ?></td>

                  <td><?php echo $flst->Amtsgericht['name']; ?></td>

        </tr>

        <tr> 

          <td align="right"><b>Grundbuchbezirk:</b>&nbsp;</td>

          <td><?php echo $flst->Grundbuchbezirk['schluessel']; ?></td>

                  <td><?php echo $flst->Grundbuchbezirk['name']; ?></td>

        </tr>

      </table><br/>
    </td>
  </tr>
<?php
     } # Ende es wurde auch was zum Flurstück gefunden
     else {
  ?><tr>
     	<td>Das Flurstück mit Kennzeichen: <?php echo $flurstkennz; ?> ist nicht in der aktuellen PostGIS-Datenbank enthalten.
     	<br>Aktualisieren Sie die ALB und ALK-Daten.</td>
    </tr><?php
     } ?>
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
  ?>">zur Namensuche</a> |
  <a href="index.php?go=ZoomToFlst&FlurstKennz=<?php echo $flst->FlurstKennz; ?>">Kartenausschnitt</a>
  <?php
    $this->getFunktionen();
    if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) { ?>
      | <a href="index.php?go=showFlurstuckKoordinaten&FlurstKennz=<?php echo $flst->FlurstKennz; ?>" target="_blank">Koordinaten</a>
    <?php }   
  ?>  
 </td>
</tr>
<tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
    <td colspan="2">
        <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=30&wz=1" target="_blank">ALB-Auszug&nbsp;30&nbsp;mit&nbsp;WZ</a>
      <?php
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
        $this->getFunktionen();
        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=30&wz=0" target="_blank">ALB-Auszug&nbsp;30&nbsp;ohne&nbsp;WZ</a>
          <?php } ?>
        <?php
        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=35&wz=0" target="_blank">ALB-Auszug&nbsp;35&nbsp;ohne&nbsp;WZ</a>
        <?php }
        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=40&wz=0" target="_blank">ALB-Auszug&nbsp;40&nbsp;ohne&nbsp;WZ</a>
        <?php } ?>
      </td>
  </tr><?php
	  } # Ende der Schleife zur Abfrage und Anzeige der Flurstücke
	?>
	
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Für alle Flurstücke:</b><br></td>
	</tr>
	<tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
    <td colspan="2">
        <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php
        	echo $Flurstueckskennz; 
         ?>&formnummer=30&wz=1" target="_blank">ALB-Auszug&nbsp;30&nbsp;mit&nbsp;WZ</a>
      <?php
      if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) { ?>
        | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php
        	echo $Flurstueckskennz;
        ?>&formnummer=35&wz=1" target="_blank">ALB-Auszug&nbsp;35&nbsp;mit&nbsp;WZ</a>
      <?php }
      if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']) { ?>
        | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php
        	echo $Flurstueckskennz;
				?>&formnummer=40&wz=1" target="_blank">ALB-Auszug&nbsp;40&nbsp;mit&nbsp;WZ</a>
      <?php } ?>
    </td>
  </tr>
    <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
      <td colspan="2">
        <?php
        $this->getFunktionen();
        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php
          echo $Flurstueckskennz;
						 ?>&formnummer=30&wz=0" target="_blank">ALB-Auszug&nbsp;30&nbsp;ohne&nbsp;WZ</a>
          <?php } ?>
        <?php
        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php 
          echo $Flurstueckskennz;
         ?>&formnummer=35&wz=0" target="_blank">ALB-Auszug&nbsp;35&nbsp;ohne&nbsp;WZ</a>
        <?php }
        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php
          echo $Flurstueckskennz; 
          ?>&formnummer=40&wz=0" target="_blank">ALB-Auszug&nbsp;40&nbsp;ohne&nbsp;WZ</a>
        <?php } ?>
      </td>
  </tr>
  <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
  	<td colspan="2">
  		<a href="index.php?go=ZoomToFlst&FlurstKennz=<?php echo $Flurstueckskennz; ?>">Kartenausschnitt</a>
  	</td>
  </tr>
  <tr>
		<td>&nbsp;</td>
	</tr>
	
	<? 
  } # Ende es liegen Flurstücke im Suchbereich
  else {
  	?><br><strong><font color="#FF0000">
	  Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
	  Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
	  <?php  	
  }
#/2005-11-30_pk
?>

</table>