<hr>
<h2>Bodenrichtwerte<br><br><?php echo $this->qlayerset[$i]['Name']; ?></h2>
  <?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
  	$GemObj=new gemeinde(0,$this->pgdatabase);
  ?>
  <table border="1" cellspacing="0" cellpadding="2">
    <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
      <td><strong>&nbsp;&nbsp;Stichtag&nbsp;&nbsp;</strong></td> 
    	<td><strong>Gemeinde</strong></td>
    	<td><strong>Bodenwert</strong></td>
    	<td><strong>Verfahren</strong></td>
    	<td>&nbsp;</td>
    	<td>&nbsp;</td>
    </tr><?php
    for ($j=0;$j<$anzObj;$j++) {
      $rs=$this->qlayerset[$i]['shape'][$j];
      ?><tr>
        <td><?php echo $rs['stichtag']; ?></td>
        <td><?php echo $GemObj->getGemeindeName($rs['gemeinde']); ?></td>
        <td align="right"><?php echo $rs['bodenrichtwert']; ?>&nbsp;&euro;</td>
        <td><?php echo $rs['verfahrensgrund'].' '.$rs['verfahrensgrund_zusatz'];; ?></td>
        <td><a href="index.php?go=Bodenrichtwertformular_Aendern&layer_id=<? echo $this->qlayerset[$i]['Layer_ID']; ?>&oid=<?php echo $rs['oid']; ?>">&Auml;ndern</a></td>
        <td><a href="javascript:Bestaetigung('index.php?go=Bodenrichtwertzone_Loeschen&oid=<?php echo $rs['oid']; ?>','Wollen Sie das Objekt mit der ID: <?php echo $rs['oid']; ?> wirklich löschen?')">L&ouml;schen</a></td>
    </tr><?php
    }
    ?></table>
  <br>
  <?php    
  }
  else {?>
    <br><strong><font color="#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php   
  }
?>  
