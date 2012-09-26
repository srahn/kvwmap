<h2>B-Plan-Änderungen</h2>
<?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
    ?><table border="1" cellspacing="0" cellpadding="2">
    <tr bgcolor="<?php echo BG_DEFAULT ?>"> 
	    <td><b>Nutzer</b></td>
	    <td><b>Datum</b></td>
	    <td><b>Hinweis</b></td>
	    <td><b>Bemerkung</b></td>
		  <td><b>&nbsp;</b></td>
    </tr><?php
    for ($j=0;$j<$anzObj;$j++) {
      ?><tr> 
	    <td> 
	      <?php echo $this->qlayerset[$i]['shape'][$j]['username']; ?>
	    </td>
	    <td> 
	      <?php echo $this->qlayerset[$i]['shape'][$j]['datum']; ?>
	    </td>
	    <td>
	      <?php echo $this->qlayerset[$i]['shape'][$j]['hinweis']; ?>
	    </td>
	    <td> 
	      <?php echo $this->qlayerset[$i]['shape'][$j]['bemerkung']; ?>
	      &nbsp; 
	    </td>
      <td><?php
      if($this->Stelle->isFunctionAllowed('BplanAenderungLoeschen')){
        ?><a href="javascript:Bestaetigung('index.php?go=bauleitplanung_Loeschen&id=<?php echo $this->qlayerset[$i]['shape'][$j]['id']; ?>', 'Wollen Sie diese B-Plan-Änderung wirklich löschen?');">löschen</a><?php
      }
      else {
        ?>&nbsp;<?php
      }
      ?></td>
      </tr><?php
    }
    ?></table>
    <br/><?php    
  }
  else {
    ?><br><strong><font color="#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php   
  }
?>