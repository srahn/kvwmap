<?php
 # 2008-02-05 pkvvm
  include(LAYOUTPATH.'languages/MapCommentForm_'.$this->user->rolle->language.'.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<br><table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>">
  <td align="center">
<h2><?php echo $strTitleLayers; ?></h2>
</td>
</tr>
<tr><td align="center">
    <textarea name="comment" cols="35" autofocus rows="4" wrap="VIRTUAL"><?php echo $this->formvars['comment']; ?></textarea>
<br>	<input type="hidden" name="go" value="Layerauswahl">
	<input type="hidden" name="consumetime" value="<?php echo $this->user->rolle->newtime; ?>">
    <input type="hidden" name="go_plus" id="go_plus" value="">
    <input type="button" name="dummy" value="<?php echo $this->strCancel; ?>" onclick="submitWithValue('GUI','go_plus','Abbrechen')">&nbsp;
	<input type="reset" name="reset" value="<?php echo $this->strReset; ?>">&nbsp;    
    <input type="button" name="dummy" value="<?php echo $this->strSave; ?>" onclick="submitWithValue('GUI','go_plus','Speichern')">
	</td>
  </tr>
  <tr> 
	<td>
	<img src="<?php echo $this->img['hauptkarte']; ?>">
	</td>
  </tr>
</table>