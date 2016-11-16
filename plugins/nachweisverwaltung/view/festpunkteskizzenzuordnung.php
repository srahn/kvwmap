<h2><?php echo $this->titel; ?></h2>
<div align="center"><br>
  <?php
if ($this->festpunkte->anzPunkte>0) {
  ?>
  <table border="1" cellspacing="0" cellpadding="2">
      
      <tr bgcolor="<?php echo BG_DEFAULT ?>"> 
      <td><span class="fett">Punktkennzeichen</span></td>
      <td><span class="fett">Rechtswert</span></td>
      <td><span class="fett">Hochwert</span></td>    
      <td><span class="fett">Punkt&nbsp;zuordnen&nbsp;zu&nbsp;folgender&nbsp;Datei:</span></td>
      <td><span class="fett">Vorschaubild:</span></td>
      </tr><?php
    for ($i=0;$i<$this->festpunkte->anzPunkte;$i++) {
    	$rs=$this->festpunkte->liste[$i];
      ?><tr>
      <td><input name="pkn[<?php echo $rs['pkn']; ?>]" type="hidden" value="<?php echo $rs['pkn']; ?>"><?php echo $rs['pkn']; ?></td>
      <td><?php echo $rs['rw']; ?></td>
      <td><?php echo $rs['hw']; ?></td>        
      <td>
      <?php
		  # Anzeige von Formularobjekten zur Eingabe eines anderen Dateinamens oder Uploadfunktion
		  if ($rs['skizze']['tif']) {
		    # Es ist eine Bilddatei im tif-Format für den Punkt vorhanden. Anzeige des Textfeldes mit Dateinamenvorschlag zur Änderungseingabe
		    ?><input name="name[<?php echo $rs['pkn'];?>]" type="text" value="<?php echo substr($rs['datei'],0,-3)."*"; ?>" size="25"><?php
		  }
		  else {	
		    # Zum Punkt existiert keine Einmessungsskizze im tif-Format. Anzeigen eines Uploadformularfeldes.
		    ?><input name="tifUpload<?php echo $rs['pkn']; ?>" type="file"><?php
		  }
			?>  
      </td>
      <td>
      <?php
		  # Anzeige von Links auf Vorschaubilder der Skizzen
		  if ($rs['skizze']['png']) {
			  # Vorschaubilddatei ist vorhanden. Setzen eines Links auf das Vorschaubild.
			  ?><a href="<?php echo 'index.php?go=sendImage&name='.PUNKTDATEIPATH.substr($rs['datei'],0,-3).'png'; ?>" target="_blank" class='name'>ansehen</a><?php
			}
			else {
			  # Vorschaubilddatei nicht vorhanden. Anzeigen eines UploadFormularelements
			  ?><input name="pngUpload<?php echo $rs['pkn'];?>" type="file"><?php
			}
      ?>
		  </td>
      </tr>
      <?php
    }
    ?><tr bgcolor="#FFFFCC">
        <td colspan="6"><font size="-2"><em>Die &Auml;nderungen erfolgen in folgender
              Reihenfolge:<br>
              1.) Dateien aus der 1. Tabelle werden verschoben.<br>
              2.) Dateien die keine Zuordnung hatten werden verschoben.<br>
              Bei 1 und 2 gilt, wenn eine Datei mit dem selben Namen schon existiert,
              wird diese &uuml;berschrieben. Stimmt das Kilometerquadrat im
              Verzeichnisnamen nicht mit der Angabe im Dateinamen &uuml;berein,
              wird die &Auml;nderungsanweisung ignoriert! Verschoben werden immer
              tif und png Dateien soweit beide vorhanden.</em></font></td>
      </tr></table>
</div>
<br>
<table border="1" cellspacing="0" cellpadding="2"> 
    <tr>
      <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Dateien ohne Zuordnung   </span><br>
        <em><font size="-2">(in km-Quadraten der ausgew&auml;hlten Punkte)</font></em></td> 
      <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Umbenennen&nbsp;nach:</span></td>
	  <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Vorschaubild</span></td>
	  <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Archiv</span></td>
      <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">L&ouml;schen</span></td>
    </tr>  
      <?php
    for ($i=0;$i<count($this->skizzenohnezuordnung);$i++) {
	  $dateiName=$this->formvars['kiloquad'].'/'.$this->skizzenohnezuordnung[$i];
    	?><tr>
		<td><?php
		 echo $dateiName.'.tif';
		 ?>
		 </td>
		<td><input name="renamefile[<?php echo $dateiName; ?>]" type="text" value="<?php echo $dateiName.'.*'; ?>" size="27"></td>
		<td><?php
		   $pngDatei=PUNKTDATEIPATH.$dateiName.'.png';
		  if (file_exists($pngDatei)) {
		    # Setzen eines Links zum Anzeigen der Vorschaubilder.
			?><a href="<?php echo "index.php?go=sendImage&name=".$pngDatei; ?>" target="_blank">ansehen</a><?php
          }
		  else {
		    ?>nur tif<?php
		  }
		  ?>
		  </td>
		  		<td><input name="archivfile[<?php echo $dateiName; ?>]" type="checkbox" value="1">
		</td>
		        <td><input name="deletefile[<?php echo $dateiName; ?>]" type="checkbox" value="1"></td>
      </tr><?php 
    }
	  
	  ?><tr bgcolor="#FFFFCC">
	    <td colspan="5"><font size="-2"><em>Die &Auml;nderungen erfolgen in der folgenden
	          Reihenfolge:<br>
	          1.) Umbenennen der Dateien<br>
	          2.) Verschieben der Dateien ins Archiv<br>
	          3.) L&ouml;schen der Dateien<br>
        Befindet sich am neuen Speicherort bereits
	          eine gleichnamige Datei,
	      wird diese &uuml;berschrieben.  Auch hier gilt, dass
	      der Verzeichnisname mit dem Kilometerquadrat aus dem Dateinamen &uuml;bereinstimmen
	      muss.
Verschoben bzw. gel&ouml;scht werden immer tif und png Dateien soweit beide vorhanden. </em></font></td>
	    </tr>
</table>
<br>


  <div align="center">
  <input type="reset" name="Reset" value="Zur&uuml;cksetzen">
&nbsp;
  <input type="hidden" name="go" value="FestpunkteSkizzenZuordnung">
  <input name="go_plus" type="submit" id="go_plus" value="Senden">
  <br><br>

  <table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
    <td><a href="index.php">zur&uuml;ck zur Karte</a> | <input type="hidden" name="kiloquad" value="<?php echo $this->formvars['kiloquad']; ?>"><a href="index.php?go=Festpunkte_Auswaehlen&kiloquad=<?php echo $this->formvars['kiloquad']; ?>">zur Festpunktsuche</a></td>
  </tr>
</table>
  <br>
  <?php    
  }
  else {
	  ?>
  <br>
  <span class="fett"><font color="#FF0000">
	    Zu dieser Anfrage wurden keine Objekte gefunden!</font></span><br>
	    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
	    <?php  	
  }
?>
</div>
