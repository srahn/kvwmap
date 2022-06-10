<?php
  include(LAYOUTPATH.'languages/MapCommentForm_'.$this->user->rolle->language.'.php');
	$timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $this->user->rolle->newtime);
?>

<h2><?php echo $strTitleExtent; ?></h2><br>

<table cellspacing="0" cellpadding="2">
	<tr>
		<td align="center">
			<?php echo $strTime.$timestamp->format('d.m.Y H:i:s'); ?><br><br>
			<textarea name="comment" autofocus cols="35" rows="4" wrap="VIRTUAL" placeholder="<? echo $this->strComment; ?>"><?php echo $this->formvars['comment']; ?></textarea><br><br>
			<input type="checkbox" value="1" name="public"> <? echo $strPublic; ?><br><br>
			<input type="hidden" name="go" value="Kartenkommentar">
			<input type="hidden" name="consumetime" value="<?php echo $this->user->rolle->newtime; ?>">
			<input type="hidden" name="go_plus" value="Speichern">
			<input type="button" name="dummy" value="<?php echo $this->strSave; ?>" onclick="document.GUI.submit();">
		</td>
	</tr>
	<tr> 
		<td>
			<img src="<?php echo $this->img['hauptkarte']; ?>">
		</td>
  </tr>
</table>