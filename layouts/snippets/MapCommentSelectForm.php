<?php
 # 2008-02-05 pkvvm
  include(LAYOUTPATH.'languages/MapCommentSelectForm_'.rolle::$language.'.php');
 ?>
<h2><?php echo $strTitleExtent; ?></h2>
<br>
<table border="1" style="border-collapse:collapse; border: 1px solid grey" cellspacing="0" cellpadding="4">
<tr bgcolor="<?php echo BG_DEFAULT ?>">
  <th><a href="index.php?go=Kartenkommentar_Waehlen&order=time_id DESC"><?php echo $strTime; ?></a></th>
	<th><a href="index.php?go=Kartenkommentar_Waehlen&order=Name, Vorname"><?php echo $strUser; ?></a></th>
  <th><a href="index.php?go=Kartenkommentar_Waehlen&order=comment"><?php echo $this->strComment; ?></a></th>
  <th>&nbsp;</th>
</tr>
<?php
  $anzMapComments = count_or_0($this->mapComments);
  for ($i=0;$i<$anzMapComments;$i++) {
  	?>
  <tr>
    <td><a href="index.php?go=Kartenkommentar_Zoom&storetime=<?php echo $this->mapComments[$i]['time_id']; ?>&user_id=<?php echo $this->mapComments[$i]['user_id']; ?>"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $this->mapComments[$i]['time_id'])->format('d.m.Y H:i:s'); ?></a></td>
		<td><? echo $this->mapComments[$i]['Vorname'].' '.$this->mapComments[$i]['Name']; ?></td>
    <td><?php echo $this->mapComments[$i]['comment']; ?></td>
    <td>
			<? if($this->mapComments[$i]['user_id'] == $this->user->id){ ?>
			<a href="javascript:Bestaetigung('index.php?go=Kartenkommentar_loeschen&storetime=<?php echo $this->mapComments[$i]['time_id']; ?>','<?php echo $this->strDeleteWarningMessage; ?>');"><?php echo $this->strDelete; ?></a>
			<? } ?>
		</td>
  </tr>
  <?php 
  }
  ?>
</table>