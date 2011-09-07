<h2>bearbeitete Gebäude</h2>
<?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) { 
    ?>
   
<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>"> 
    <td><b>Gemeinde</b></td>
    <td><b>Straße</b></td>
    <td><b>Hausnummer</b></td>
    <td><b>Zusatz</b></td>
    <td><b>Kommentar</b></td>
    <td>&nbsp;</td>
  </tr>
  <?php
    for ($j=0;$j<$anzObj;$j++) {
      ?>
  <tr>
  	<td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['gemeindename']; ?>
    </td> 
    <td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['strassenname']; ?>
    </td>
    <td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['nummer']; ?>
    </td>
    <td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['zusatz']; ?>
    </td>
    <td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['kommentar']; ?>
    </td>
    <td> 
      <a href="index.php?go=gebaeude_editor&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>">bearbeiten</a>
    </td>
  </tr>
  <?php
    }
    ?>
</table>
<br/>
<?php    
  }
  else {
    ?><br><strong><font color="#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php   
  }
?>
