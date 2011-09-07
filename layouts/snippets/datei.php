
<h2>Dateien</h2><?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
    ?>
   
<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>">
    <td><b>Datei</b></td>
  </tr>
  <?php
	  for ($j=0;$j<$anzObj;$j++) {
	    ?>
  <tr>
    <td>
      <a target="_blank" href="<?php echo copy_file_to_tmp(SHAPEPATH.'docs/'.$this->qlayerset[$i]['shape'][$j]['dateiname'].'.pdf'); ?>"><img src="<? echo GRAPHICSPATH; ?>button_ansicht.png"></a>
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
?><?php
