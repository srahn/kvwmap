
<h2>Altlasten</h2><?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
    ?>
   
<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>">
    <td><span class="fett">Rechts</span></td>
    <td><span class="fett">Hoch</span></td>    
  </tr>
  <?php
    for ($j=0;$j<$anzObj;$j++) {
      ?>
  <tr>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['RECHTS']; ?></td>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['HOCH']; ?></td>
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
?><?php
