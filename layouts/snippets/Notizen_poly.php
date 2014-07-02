<h2>Fl&auml;chennotizen</h2>
<?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) { 
    ?>
   
<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>"> 
    <td><span class="fett">Notiz</span></td>
    <td><span class="fett">Kategorie</span></td>
    <td><span class="fett">Person</span></td>
    <td><span class="fett">Datum</span></td>
	<td><span class="fett">&nbsp;</span></td>
	<td><span class="fett">&nbsp;</span></td>
  </tr>
  <?php
    for ($j=0;$j<$anzObj;$j++) {
    	$this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
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
    ?><br><span class="fett" style="color:#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</span><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php   
  }
?>
