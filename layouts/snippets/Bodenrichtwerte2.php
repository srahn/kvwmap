<hr>
<h2>Bodenrichtwerte<br><br><?php echo $this->qlayerset[$i]['Name']; ?></h2>
  <?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
  	$GemObj=new gemeinde(0,$this->pgdatabase);
  ?>
  <table border="1" cellspacing="0" cellpadding="2">
    <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
      <td><span class="fett">&nbsp;&nbsp;Stichtag&nbsp;&nbsp;</span></td> 
    	<td><span class="fett">Gemeinde</span></td>
    	<td><span class="fett">Bodenwert</span></td>
    	<td><span class="fett">Verfahren</span></td>
    	<td>&nbsp;</td>
    </tr><?php
    for ($j=0;$j<$anzObj;$j++) {
      $rs=$this->qlayerset[$i]['shape'][$j];
      ?><tr>
        <td><?php echo $rs['stichtag']; ?></td>
        <td><?php echo $GemObj->getGemeindeName($rs['gemeinde']); ?></td>
        <td align="right"><?php echo $rs['bodenrichtwert']; ?>&nbsp;&euro;</td>
        <td><?php echo $rs['verfahrensgrund'].' '.$rs['verfahrensgrund_zusatz'];; ?></td>
        <td><a href="index.php?go=Bodenrichtwertformular_Anzeige&layer_id=<? echo $this->qlayerset[$i]['Layer_ID']; ?>&oid=<?php echo $rs['oid']; ?>">Anzeigen</a></td>
    </tr><?php
    }
    ?></table>
  <br>
  <?php    
  }
  else {?>
    <br><span class="fett"><font color="#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</font></span><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php   
  }
?>  
