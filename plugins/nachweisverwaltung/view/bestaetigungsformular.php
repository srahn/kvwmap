<table width="100%" height="100" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td colspan="2" bgcolor="<?php echo BG_FORM ?>"><div align="center"><h2><?php echo $this->titel; ?></h2></div></td>
  </tr>
  <tr> 
    <td colspan="2" bgcolor="<?php echo BG_FORM ?>"><div align="center" style="color: #cc0000; font-family: SourceSansPro3"><?php echo $this->formvars['nachfrage']; ?></div></td>
  </tr>
  <tr> 
    <td colspan="2" bgcolor="<?php echo BG_FORM ?>"> </td>
  </tr>
  <!--<tr>
    <td align="right" bgcolor="<?php echo BG_FORM ?>"><input type="checkbox" name="loeschenDateien" value="1" checked></td>
    <td bgcolor="<?php echo BG_FORM ?>">Auch die Bilddateien vom Server l&ouml;schen?</td>
  </tr> //-->
  <tr> 
    <td width="50%" bgcolor="<?php echo BG_FORM ?>"><div align="right"> 
        <input type="submit" name="bestaetigung" value="JA">
      </div></td>
    <td width="50%" bgcolor="<?php echo BG_FORM ?>"><input type="submit" name="bestaetigung" value="NEIN"></td>
  </tr>
  <tr> 
    <td colspan="2" bgcolor="<?php echo BG_FORM ?>">
	        <input type="hidden" name="go" value="<?php echo $this->formvars['go']?>">
					<input type="hidden" name="go_plus" value="<?php echo $this->formvars['go_plus']?>">
          <input type="hidden" name="suchantrnr" value="<?php echo $this->formvars['suchantrnr']; ?>">
					<input type="hidden" name="stelle_id" value="<?php echo $this->formvars['stelle_id']; ?>">
					<input type="hidden" name="order" value="<? echo $this->formvars['order']; ?>">
					<input type="hidden" name="flur_thematisch" value="<? echo $this->formvars['flur_thematisch']; ?>">
					<input type="hidden" name="go_next" value="<? echo $this->formvars['go_next']; ?>">
<?php
# 2006-01-03 pk
if (is_array($this->formvars['id'])) {
	# die Variable für id enthält einen einzelnen Wert
	$idListe=$this->formvars['id'];
}
else {
	# die Variable für id ist eine Liste (Array)
	$idListe[]=$this->formvars['id'];
}
for ($i=0;$i<count($idListe);$i++) {
  ?><input type="hidden" name="id[]" value="<?php echo $idListe[$i]; ?>"><?php
}
?>
    </td>
  </tr>
</table>
