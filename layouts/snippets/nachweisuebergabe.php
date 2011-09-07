<table width="100%" height="100" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td colspan="2" bgcolor="<?php echo BG_FORM ?>"><div align="center"><strong><?php echo $this->titel; ?></strong></div>
      <div align="center"></div></td>
  </tr>
  <tr> 
    <td colspan="2" bgcolor="<?php echo BG_FORM ?>"> </td>
  </tr>
  <tr> 
    <td width="50%" bgcolor="<?php echo BG_FORM ?>"><div align="right">Antragsnummer:&nbsp; 
        <?php $this->FormObjAntr_nr->outputHTML();
			  												echo $this->FormObjAntr_nr->html;?>
      </div></td>
    <td width="50%" bgcolor="<?php echo BG_FORM ?>"> <select name="go_plus" onChange="document.GUI.submit()">
        <option value="">---</option>
        <option value="in Ordner zusammenstellen"<?php if ($this->formvars['nachweisaction']=='Ordner') { ?> selected<?php } ?>>in 
        Ordner zusammenstellen</option>
        <option value="in ZIP-Datei packen"<?php if ($this->formvars['nachweisaction']=='ZIP-Datei') { ?> selected<?php } ?>>in 
        ZIP-Datei packen</option>
        <option value="als E-Mail verschicken"<?php if ($this->formvars['nachweisaction']=='E-Mail') { ?> selected<?php } ?>>als 
        E-Mail verschicken</option>
      </select></td>
  </tr>
  <tr> 
    <td colspan="2" bgcolor="<?php echo BG_FORM ?>">&nbsp;</td>
  </tr>
</table>
