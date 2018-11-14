<hr><h2><?php echo $this->qlayerset[$i]['alias']; ?></h2><br>
<?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
		$this->found = 'true';
    ?><table border="1" cellspacing="0" cellpadding="2">
    <tr align="right" bgcolor="<?php echo BG_DEFAULT ?>"> 
    <td width="121"><span class="fett">GML_ID</span></td>
    <td width="121"><span class="fett">PKN</span></td>
    <td width="121"><span class="fett">PAR</span></td>
    <td width="27"><span class="fett">RW</span></td>
    <td width="27"><span class="fett">HW</span></td> 
    <td width="27"><span class="fett">H</span></td> 
    <td width="27"><span class="fett">HOP</span></td> 
    <td width="17"><span class="fett">RHO</span></td>
    <td width="104"><span class="fett">Vorschau</span></td>
    <td width="53"><span class="fett">Anzeige</span></td>
    <td width="71"><span class="fett">FGP</span></td>
    <td width="62"><span class="fett">vermarkt</span></td>
    <td width="17"><span class="fett">KST</span></td>
    <td width="28"><span class="fett">ZST</span></td>
    <td width="38"><span class="fett">ABM</span></td>
    <td width="32"><span class="fett">ZDE</span></td>
    <td width="22"><span class="fett">GST</span></td>
    <td width="19"><span class="fett">VWL</span></td>
    <td width="21"><span class="fett">HS</span></td>
    <td width="24"><span class="fett">HG</span></td>
    <td width="21"><span class="fett">HZ</span></td>
    <td width="31"><span class="fett">HBJ</span></td>
    <td width="35"><span class="fett">HAH</span></td>
    <td width="32"><span class="fett">SOE</span></td>
    <td width="31"><span class="fett">HIN</span></td>
    <td width="31"><span class="fett">beginnt</span></td>
    <td width="31"><span class="fett">endet</span></td>
    </tr><?php
    for ($j=0;$j<$anzObj;$j++) {
      $rs=$this->qlayerset[$i]['shape'][$j];
      ?><tr>
        <td><?php if ($rs['gml_id']=='') { ?>&nbsp;<?php } else { echo $rs['gml_id']; } ?></td>
        <td><?php if ($rs['pkn']=='') { ?>&nbsp;<?php } else { echo $rs['pkn']; } ?>
        <input name="pkn[<?php echo $rs['pkn']; ?>]" type="hidden" value="<?php echo $rs['pkn']; ?>"></td>
        <td><?php echo $rs['par']; ?></td>
        <td><?php echo $rs['rw']; ?></td>
        <td><?php echo $rs['hw']; ?></td>        
        <td><?php if ($rs['hoe']=='') { ?>&nbsp;<?php } else { echo $rs['hoe']; } ?></td>
        <td><?php if ($rs['hop']=='') { ?>&nbsp;<?php } else { echo $rs['hop']; } ?></td>
        <td>&nbsp;<?php echo trim($rs['rho']); ?></td>
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
        if ($rs['par']<2) {
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
            $link="index.php?go=sendeFestpunktskizze&name&name=".  $skizzenDatei_1;
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
          if ($rs['par']=='6') {
            ?>auf TP-Skizze<?php
          }
          else {
            ?>auf AP-Skizze<?php
          }
          $blatt=-1;
        }
        ?>  
        </td>
        <td align="right"><?php if ($rs['pkn']=='') { ?>&nbsp;<?php } else { ?><a href="index.php?go=Festpunkte%20Anzeigen&pkn=<?php echo $rs['pkn']; ?>">in Karte</a> <?php } ?></td>
        <td align="right"><?php if ($rs['fgp']=='true') { ?>ja<?php } else { ?>nein<?php } ?></td>
        <td align="center"><?php if ($rs['abm']=='') { ?>nein<?php } elseif ($rs['abm'] < 9000) { ?>ja<?php } else { ?>nein<?php } ?></td>
        <td align="right">&nbsp;<?php echo $rs['kst']; ?></td>
        <td align="right"><?php if ($rs['zst']=='') { ?>&nbsp;<?php } else { echo $rs['zst']; } ?></td>
        <td align="right"><?php if ($rs['abm']=='') { ?>&nbsp;<?php } else { echo $rs['abm']; } ?></td>
        <td align="right"><?php if (trim($rs['zde'])=='') { ?>&nbsp;<?php } else { echo $rs['zde']; } ?></td>
        <td align="right">&nbsp;<?php echo $rs['gst']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['vwl']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hs']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hg']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hz']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hbj']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['hah']; ?></td>
        <td align="right">&nbsp;<?php echo $rs['soe']; ?></td>
        <td align="right"><?php if ($rs['hin']=='') { ?>&nbsp;<?php } else { echo $rs['hin']; } ?></td>
        <td align="right">&nbsp;<?php echo $rs['beginnt']; ?></td>
        <td align="right"><?php if ($rs['endet']=='') { ?>&nbsp;<?php } else { echo $rs['endet']; } ?></td>
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
                <td><em><font size="-2">GML_ID</font></em></td>
                <td><em><font size="-2">- Identifikator</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">PAR</font></em></td>
                <td><em><font size="-2">- Punktart</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">PKN</font></em></td>
                <td><em><font size="-2">- Punktkennung</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">FGP</font></em></td>
                <td><em><font size="-2">- festgestellter Grenzpunkt</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">KST</font></em></td>
                <td><em><font size="-2">- Koordinatenstatus</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">ZST</font></em></td>
                <td><em><font size="-2">- zust&auml;ndige Stelle</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">VMA</font></em></td>
                <td><em><font size="-2">- Ab-/Vermarkungsart</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">DES</font></em></td>
                <td><em><font size="-2">- Description - Datenerhebung Punktort</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">ZDE</font></em></td>
                <td><em><font size="-2">- Zeitpunkt der Entstehung</font></em></td>
              </tr>
              <tr>
                <td><em><font size="-2">SOE</font></em></td>
                <td><em><font size="-2">- sonstige Eigenschaft</font></em></td>
              </tr>
            </table>
          </td>
          <td>&nbsp;</td>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="2"><em><font size="-2">Lage</font></em></td>
            </tr>
            <tr>
              <td width="20%">&nbsp;</td>
              <td width="80%"><em><font size="-2">Lagekoordinate</font></em></td>
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
            <tr>
              <td><em><font size="-2">RHO</font></em></td>
              <td><em><font size="-2">- Relative H&ouml;he</font></em></td>
            </tr>
              <td><em><font size="-2">GST</font></em></td>
              <td><em><font size="-2">- Genauigkeitsstufe</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">VWL</font></em></td>
              <td><em><font size="-2">- Vertrauensw&uuml;rdigkeit</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">HIN</font></em></td>
              <td><em><font size="-2">- Hinweise</font></em></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2"><em><font size="-2">H&ouml;he</font></em></td>
            </tr>
            <tr>
              <td width="15%"><em><font size="-2">HS</font></em></td>
              <td width="85%"><em><font size="-2">H&ouml;henstatus</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">H</font></em></td>
              <td><em><font size="-2">- H&ouml;henangabe</font></em></td>
            </tr>
            <tr>
              <td><em><font size="-2">HOP</font></em></td>
              <td><em><font size="-2">- H&ouml;he, Oberkante Pfeiler</font></em></td>
            </tr>
          </table>
            </td>
        </tr>
      </table></div>
      <br>
	  <br>
      <input name="go_plus" type="submit" value="Festpunkte Anzeigen"> 
      <input name="go_plus" type="submit" value="Festpunkte zu Auftrag Hinzufügen">
      <input name="go_plus" type="submit" value="KVZ-Datei erzeugen">
	  <?php if ($this->formvars['kiloquad']!='' AND $this->formvars['pkn']=='') { ?>
	  <input name="kiloquad" type="hidden" value="<?php echo $this->formvars['kiloquad']; ?>">
	  <input name="go" type="hidden" value="Sachdaten">
	  <input name="go_plus" type="submit" value="FestpunkteSkizzenZuordnung">
	  <?php
	}
	if ($this->formvars['kiloquad']!='' OR $this->formvars['pkn']!='') { ?>
	  <br>
	  <br>
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
    <td><a href="index.php">zur&uuml;ck zur Karte</a> | <a href="index.php?go=Festpunkte_Auswaehlen&kiloquad=<?php echo $this->formvars['kiloquad']; ?>&pkn=<?php echo $this->formvars['pkn']; ?>">zur Festpunktsuche</a></td>
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
	<span class="fett"><font color="#FF0000">
	  Zu diesem Layer wurden keine Objekte gefunden!</font></span><br>
	  Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
	  <?php  	
  }
?>
