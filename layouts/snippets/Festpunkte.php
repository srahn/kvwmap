<hr><h2>Festpunkte</h2><br>
<?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
    ?><table border="1" cellspacing="0" cellpadding="2">
    <tr align="right" bgcolor="<?php echo BG_DEFAULT ?>"> 
    <td width="121"><b>Punktkennzeichen</b></td>
    <td width="27"><b>RW</b></td>
    <td width="27"><b>HW</b></td>    
    <td width="17"><strong>H</strong></td>
    <td width="104"><b>Vorschau</b></td>
	<td width="71"><strong>verhandelt</strong></td>
    <td width="62"><strong>vermarkt</strong></td>
    <td width="53"><strong>Anzeige</strong></td>
    <td width="17"><strong>S</strong></td>
    <td width="28"><strong>ZST</strong></td>
    <td width="38"><strong>VMA</strong></td>
    <td width="37"><strong>BEM</strong></td>
    <td width="32"><strong>ENT</strong></td>
    <td width="33"><strong>UNT</strong></td>
    <td width="32"><strong>ZUO</strong></td>
    <td width="19"><strong>LS</strong></td>
    <td width="22"><strong>LG</strong></td>
    <td width="19"><strong>LZ</strong></td>
    <td width="29"><strong>LBJ</strong></td>
    <td width="33"><strong>LAH</strong></td>
    <td width="21"><strong>HS</strong></td>
    <td width="24"><strong>HG</strong></td>
    <td width="21"><strong>HZ</strong></td>
    <td width="31"><strong>HBJ</strong></td>
    <td width="35"><strong>HAH</strong></td>
    <td width="31"><strong>TEX</strong></td>
    </tr><?php
    for ($j=0;$j<$anzObj;$j++) {
    	$rs=$this->qlayerset[$i]['shape'][$j];
      ?><tr>
        <td><?php echo $rs['pkz']; ?>
        <input name="pkz[<?php echo $rs['pkz']; ?>]" type="hidden" value="<?php echo $rs['pkz']; ?>"></td>
        <td><?php echo $rs['rw']; ?></td>
        <td><?php echo $rs['hw']; ?></td>        
        <td>&nbsp;<?php echo trim($rs['hoe']); ?></td>
        <td><?php
        $festpunktskizze_extension="png";
        # Pfüfen ob Festpunktskizze eine PDF Datei ist
        $dateiname=basename($rs['datei']);
        $dateinamensteil=explode('.',$dateiname);
        if (strtolower($dateinamensteil[1])=='pdf') {
        	$festpunktskizze_extension='pdf';
        }
        if (strtolower($dateinamensteil[1])=='tif') {
        	$festpunktskizze_extension='tif';
        }
        if ($dateinamensteil[1]=='TIF') {
        	$festpunktskizze_extension='TIF';
        }
        if ($rs['art']<2) {
        	$blatt=0;
        	$skizzenDatei=substr($rs['datei'],0,-3).$festpunktskizze_extension;
        	if (file_exists(PUNKTDATEIPATH.$skizzenDatei)) {
			      $blatt++;
            # Setzen eines Links auf das Vorschaubilder.
            $link="index.php?go=sendeFestpunktskizze&name=".$skizzenDatei;
            ?><a href="<?php echo $link; ?>" target="_blank"><?php echo $skizzenDatei; ?></a><?php
        	}
					$skizzenDatei_1=substr($rs['datei'],0,-4)."-1.png";
		        	if (file_exists(PUNKTDATEIPATH.$skizzenDatei_1)) {
					  $blatt++;
					  $link="index.php?go=sendeFestpunktskizze&name&name=".$skizzenDatei_1;
					  ?><a href="<?php echo $link; ?>" target="_blank"><?php echo $skizzenDatei_1; ?></a><?php
					}
					$skizzenDatei_2=substr($rs['datei'],0,-4)."-2.png";
		        	if (file_exists(PUNKTDATEIPATH.$skizzenDatei_2)) {
					  $blatt++;
					  $link="index.php?go=sendeFestpunktskizze&name&name=".$skizzenDatei_2;
					  ?><br><a href="<?php echo $link; ?>" target="_blank"><?php echo $skizzenDatei_2; ?></a><?php
					}
					if ($blatt==0) {
					  ?>nicht vorhanden<?php
		        	}
		      }
		      else {
	        	if ($rs['art']==6) {
	        		?>auf TP-Skizze<?php
	        	}
	        	else {
	        		?>auf AP-Skizze<?php
	        	}
					  $blatt=-1;
		      }
        ?>  
        </td>
		<td align="right"><?php if ($rs['verhandelt']) { ?>ja<?php } else { ?>nein<?php } ?></td>
        <td align="right"><?php if ($rs['vermarkt']) { ?>ja<?php } else { ?>nein<?php } ?></td>
        <td align="right"><a href="index.php?go=Festpunkte%20Anzeigen&pkz=<?php echo $rs['pkz']; ?>">in Karte</a></td>
        <td align="right">&nbsp;<?php echo $rs['s']; ?></td>
        <td align="right"><?php if ($rs['zst']=='') { ?>&nbsp;<?php } else { echo $rs['zst']; } ?></td>
        <td align="right"><?php if ($rs['vma']=='') { ?>&nbsp;<?php } else { echo $rs['vma']; } ?></td>
        <td align="right"><?php if (trim($rs['bem'])=='') { ?>&nbsp;<?php } else { echo $rs['bem']; } ?></td>
        <td align="right"><?php if (trim($rs['ent'])=='') { ?>&nbsp;<?php } else { echo $rs['ent']; } ?></td>
        <td align="right">&nbsp;<?php echo $rs['unt']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['zuo']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['ls']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['lg']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['lz']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['lbj']; ?></td>
        <td align="right"><?php if (trim($rs['lah'])=='') { ?>&nbsp;<?php } else { echo $rs['lah']; } ?></td>
        <td align="right">&nbsp;<?php echo $rs['hs']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hg']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hz']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hbj']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hah']; ?></td>
        <td align="right"><?php if ($rs['tex']=='') { ?>&nbsp;<?php } else { echo $rs['tex']; } ?></td>
        </tr><?php
    }
    ?></table>
	<div align="left">
      <table border="0" cellspacing="0" cellpadding="2">
        <tr valign="top">
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="2"><em><font size="-2">Punktkennzeichen, Verwaltung</font></em></td>
              </tr>
              <tr>
                <td width="15%">&nbsp;</td>
                <td width="85%"><em><font size="-2">Punktkennzeichen</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">NBZ</font></em></td>
                <td><em><font size="-2">- Nummerierungsbezirk</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">PAR</font></em></td>
                <td><em><font size="-2">- Punktart</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">PNR</font></em></td>
                <td><em><font size="-2">- Punktnummer</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">P</font></em></td>
                <td><em><font size="-2">Pr&uuml;fzeichen*</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">S</font></em></td>
                <td><em><font size="-2">Punktstatus</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">ZST</font></em></td>
                <td><em><font size="-2">zust&auml;ndige Stelle</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">AK</font></em></td>
                <td><em><font size="-2">Aktualit&auml;t des Punktes*</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">VMA</font></em></td>
                <td><em><font size="-2">Vermarkungsart</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">BEM</font></em></td>
                <td><em><font size="-2">Bemerkung zur Vermarkung</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">ENT</font></em></td>
                <td><em><font size="-2">Entstehung des Punktes</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">UNT</font></em></td>
                <td><em><font size="-2">Untergang des Punktes</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">ZUO</font></em></td>
                <td><em><font size="-2">? entspr. evtl. KB</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">KB</font></em></td>
                <td><em><font size="-2">Kennung f&uuml;r Bemerkung*</font></em></td>
              </tr>
            </table>
          </td>
          <td>&nbsp;</td>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="2"><em><font size="-2">Lage</font></em></td>
            </tr>
            <tr>
              <td width="10%"><em><font size="-2">LS</font></em></td>
              <td width="90%"><em><font size="-2">Lagestatus</font></em></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><em><font size="-2">Lagekoordinate</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">RW</font></em></td>
              <td><em><font size="-2">- Rechtswert</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">HW</font></em></td>
              <td><em><font size="-2">- Hochwert</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">LG</font></em></td>
              <td><em><font size="-2">Lagegenauigkeit - Lagegenauigkeitsstufe</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">LZ</font></em></td>
              <td><em><font size="-2">Lagezuverl&auml;ssigkeit</font></em></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><em><font size="-2">Berechnungshinweis Lage</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">LBJ</font></em></td>
              <td><em><font size="-2"> - Jahr der Berechnung</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">LAH</font></em></td>
              <td><em><font size="-2">- Aktenhinweis</font></em></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2"><em><font size="-2">H&ouml;he</font></em></td>
            </tr>
            <tr>
              <td width="10%"><em><font size="-2">HS</font></em></td>
              <td width="90%"><em><font size="-2">H&ouml;henstatus</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">H</font></em></td>
              <td><em><font size="-2">H&ouml;henangabe</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">HG</font></em></td>
              <td><em><font size="-2">H&ouml;hengenauigkeit- H&ouml;hengenauigkeitsstufe</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">HZ</font></em></td>
              <td><em><font size="-2">H&ouml;henzuverl&auml;ssigkeit</font></em></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><em><font size="-2">Berechnungshinweise H&ouml;he</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">HBJ</font></em></td>
              <td><em><font size="-2"> - Jahr der Berechnung</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">HAH</font></em></td>
              <td><em><font size="-2">- Aktenhinweis</font></em></td>
            </tr>
          </table>
            </td>
        </tr>
        <tr>
          <td colspan="5"><em><font size="-2">* Werden nicht in der Punktdatei
          gef&uuml;hrt</font></em></td>
        </tr>
          </table></div>
      <br>
	  <br>
      <input name="go_plus" type="submit" value="Festpunkte Anzeigen"> 
      <input name="go_plus" type="submit" value="Festpunkte zu Auftrag Hinzufügen">
      <input name="go_plus" type="submit" value="KVZ-Datei erzeugen">
	  <?php if ($this->formvars['kiloquad']!='' AND $this->formvars['pkz']=='') { ?>
	  <input name="kiloquad" type="hidden" value="<?php echo $this->formvars['kiloquad']; ?>">
	  <input name="go" type="hidden" value="Sachdaten">
	  <input name="go_plus" type="submit" value="FestpunkteSkizzenZuordnung">
	  <?php
	}
	if ($this->formvars['kiloquad']!='' OR $this->formvars['pkz']!='') { ?>
	  <br>
	  <br>
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
    <td><a href="index.php">zur&uuml;ck zur Karte</a> | <a href="index.php?go=Festpunkte_Auswaehlen&kiloquad=<?php echo $this->formvars['kiloquad']; ?>&pkz=<?php echo $this->formvars['pkz']; ?>">zur Festpunktsuche</a></td>
  </tr>
</table><?php
}
	?>
	
	<br>
	<br>	
	<?php    
  }
  else {
	  ?><br>
	<strong><font color="#FF0000">
	  Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
	  Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
	  <?php  	
  }
?>
