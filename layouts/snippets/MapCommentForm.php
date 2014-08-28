<?php
 # 2008-02-05 pkvvm
  include(LAYOUTPATH.'languages/MapCommentForm_'.$this->user->rolle->language.'.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<br><table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>">
  <td align="center">
<h2><?php echo $strTitle; ?></h2>
</td>
</tr>
<tr><td align="center"><?php echo $strTime; ?><?php
    # 2006-03-20 pk
     echo $this->formvars['consumetime']; 
     
     ?><br> 
    <textarea name="comment" cols="35" rows="4" wrap="VIRTUAL"><?php echo $this->formvars['comment']; ?></textarea>
<br>	<input type="hidden" name="go" value="Kartenkommentar">
	<input type="hidden" name="consumetime" value="<?php echo $this->formvars['consumetime']; ?>">
    <input type="hidden" name="go_plus" id="go_plus" value="">
    <input type="button" name="dummy" value="<?php echo $this->strCancel; ?>" onclick="submitWithValue('GUI','go_plus','Abbrechen')">&nbsp;
	<input type="reset" name="reset" value="<?php echo $this->strReset; ?>">&nbsp;    
    <input type="button" name="dummy" value="<?php echo $this->strSave; ?>" onclick="submitWithValue('GUI','go_plus','Speichern')">
	</td>
  </tr>
  <tr> 
	<td>
	<img src="<?php echo $this->formvars['hauptkarte']; ?>">
	</td>
  </tr>
</table>