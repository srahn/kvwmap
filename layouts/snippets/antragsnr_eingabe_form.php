<?php
if ($this->Meldung=='' OR $this->Meldung=='Auftragsnummer erfolgreich übernommen! ') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}
 ?>

<table width="0%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td><div align="center"><strong><font size="+1"><?php echo $this->titel; ?></font></strong> 
      </div></td>
  </tr>
  <tr> 
    <?php $this->formvars['order']='';?>
    <td><table border="0" cellspacing="0" cellpadding="5">
        <tr bgcolor="#FFFFFF"> 
          <td><div align="center"><strong>Antragsnummer</strong></div></td>
          <td><div align="center"><strong>Vermessungsstelle</strong></div></td>
          <td><div align="center"><strong>Vermessungsart</strong></div></td>
          <td width="100"><div align="center"><strong>Datum</strong></div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="4"><hr></td>
        </tr>
        <tr align="left" valign="bottom" bgcolor="EBEBEB"> 
          <td>  
            <div align="left">
              <input name="antr_nr_a" type="text" value="<?php echo $this->formvars['antr_nr_a']?>" size="2" maxlength="2">&nbsp;V&nbsp;<input name="antr_nr_b" type="text" value="<?php echo $this->formvars['antr_nr_b']?>" size="4" maxlength="4">
          </div></td>
          <td><div align="center"> 
          <?php $this->FormObjVermStelle->outputHTML(); echo $this->FormObjVermStelle->html; ?>            <div align="left"></div></td>
          <td>            <?php $this->FormObjVermArt->outputHTML(); echo $this->FormObjVermArt->html; ?>   
            <div align="left"></div></td>
          <td width="100"><div align="left"><em><font size="2">Jahr-Monat-Tag</font></em><br>
              <input name="datum" type="text" value="<?php echo $this->formvars['datum']; ?>" size="11"> 
          </div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="right"><input type="submit" name="submit" value="Senden">
      <input type="hidden" name="go" value="<?php echo $this->formvars['go']; ?>"></td>
  </tr>
</table>
