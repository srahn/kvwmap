<?php
 # 2008-02-05 pkvvm
  include(LAYOUTPATH.'languages/MapCommentSelectForm_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<h2><?php echo $strTitle; ?></h2>
<table border="1" cellspacing="0" cellpadding="2">
<tr bgcolor="<?php echo BG_DEFAULT ?>">
  <th><?php echo $strTime; ?></th>
  <th><?php echo $strComment; ?></th>
  <th>&nbsp;</th>
</tr>
<?php
  # 2006-03-20 pk
  $anzMapComments=count($this->mapComments);
  for ($i=0;$i<$anzMapComments;$i++) {
  	?>
  <tr>
    <td><a href="index.php?go=Kartenkommentar_Zoom&storetime=<?php echo $this->mapComments[$i]['time_id']; ?>"><?php echo $this->mapComments[$i]['time_id']; ?></a></td>
    <td><?php echo $this->mapComments[$i]['comment']; ?></td>
    <td><a href="javascript:Bestaetigung('index.php?go=Kartenkommentar_loeschen&storetime=<?php echo $this->mapComments[$i]['time_id']; ?>','<?php echo $this->strDeleteWarningMessage; ?>');"><?php echo $this->strDelete; ?></a></td>
  </tr>
  <?php 
  }
  ?>
</table>