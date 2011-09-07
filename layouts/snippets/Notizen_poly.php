<h2>Fl&auml;chennotizen</h2>
<?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) { 
    ?>
   
<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>"> 
    <td><b>Notiz</b></td>
    <td><b>Kategorie</b></td>
    <td><b>Person</b></td>
    <td><b>Datum</b></td>
	<td><b>&nbsp;</b></td>
	<td><b>&nbsp;</b></td>
  </tr>
  <?php
    for ($j=0;$j<$anzObj;$j++) {
    	$this->notiz=new notiz($this->pgdatabase);
  		$this->notiz->aendernKategorie = $this->notiz->getKategorie($this->qlayerset[$i]['shape'][$j]['id'], $this->Stelle->id, NULL, NULL, 'true');
      ?>
  <tr> 
    <td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['notiz']; ?>
    </td>
    <td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['kategorie']; ?>
    </td>
    <td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['person']; ?>
    </td>
    <td> 
      <?php echo $this->qlayerset[$i]['shape'][$j]['datum']; ?>
      &nbsp; </td>
    <td>
    <? if($this->notiz->aendernKategorie){ ?> 
      <a href="index.php?go=Notizenformular&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>">bearbeiten</a>
    </td>
    <td> 
      <a href="javascript:Bestaetigung('index.php?go=Notiz_Loeschen&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>', 'Wollen Sie diese Notiz wirklich löschen?');">löschen</a>
    <?	}
    else {?>
    &nbsp;
    </td>
    <td>
    &nbsp;
    <?}?>
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
