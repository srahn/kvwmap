<h2>Grenzpunkte</h2><br>
<?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
    ?><table border="1" cellspacing="0" cellpadding="2">
    <tr bgcolor="<?php echo BG_DEFAULT ?>"> 
    <td><b>Punktkennzeichen</b></td>
    <td><b>Rechtswert</b></td>
    <td><b>Hochwert</b></td>
    <td><b>verhandelt</b></td>        
    </tr><?php
    for ($j=0;$j<$anzObj;$j++) {
    	$rs=$this->qlayerset[$i]['shape'][$j];
      ?><tr>
        <td><?php echo $rs['pkz']; ?></td>
        <td><?php echo $rs['x']; ?></td>
        <td><?php echo $rs['y']; ?></td>
        <td><?php if ($rs['verhandelt']) { echo 'ja'; } else { echo 'nein'; } ?></td>        
      </tr><?php
    }
    ?></table><br><?php    
  }
  else {
	  ?><br><strong><font color="#FF0000">
	  Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
	  Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
	  <?php  	
  }
?>
