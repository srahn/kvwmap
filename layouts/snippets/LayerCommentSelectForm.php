<?php
 # 2008-02-05 pkvvm
  include(LAYOUTPATH.'languages/MapCommentSelectForm_'.$this->user->rolle->language.'.php');
 ?>
<h2><?php echo $strTitleLayers; ?></h2>
<br>
<table border="1" cellspacing="0" cellpadding="2">
<tr bgcolor="<?php echo BG_DEFAULT ?>">
  <th><?php echo $strName; ?></th>
  <th>&nbsp;</th>
</tr>
<?php
  # 2006-03-20 pk
  $anzLayerComments=count($this->layerComments);
  for ($i=0;$i<$anzLayerComments;$i++) {
  	?>
  <tr>
    <td><a href="index.php?go=Layerauswahl_Laden&name=<? echo $this->layerComments[$i]['name']; ?>"><? echo $this->layerComments[$i]['name']; ?></a></td>
    <td><a href="javascript:Bestaetigung('index.php?go=Layerauswahl_loeschen&storetime=<?php echo $this->layerComments[$i]['name']; ?>','<? echo $this->strDeleteWarningMessage; ?>');"><?php echo $this->strDelete; ?></a></td>
  </tr>
  <?php 
  }
  ?>
</table>