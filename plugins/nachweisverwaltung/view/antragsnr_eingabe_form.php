<?php
if ($this->Meldung=='' OR $this->Meldung=='Auftragsnummer erfolgreich übernommen! ') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}
 ?>

<table style="border: 1px solid; border-color: #eeeeee; border-left: none; border-right: none" width="0%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td><div align="center"><h2><?php echo $this->titel; ?></h2> 
      </div></td>
  </tr>
  <tr> 
    <?php $this->formvars['order']='';?>
    <td><table border="0" cellspacing="0" cellpadding="5">
        <tr bgcolor="#FFFFFF"> 
          <td><div align="center"><span class="fett">Antragsnummer</span></div></td>
          <td><div align="center"><span class="fett">Vermessungsstelle</span></div></td>
          <td><div align="center"><span class="fett">Vermessungsart</span></div></td>
          <td width="100"><div align="center"><span class="fett">Datum</span></div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="4"><hr></td>
        </tr>
        <tr align="left" valign="bottom" bgcolor="EBEBEB"> 
          <td>  
            <div align="left">
              <input name="antr_nr" type="text" value="<?php echo $this->formvars['antr_nr']?>" size="10" maxlength="10">&nbsp;
            </div>
          </td>
          <td>
            <div align="center"> 
              <? $this->FormObjVermStelle->outputHTML(); echo $this->FormObjVermStelle->html; ?>            
            </div>
          </td>
          <td>
            <div align="left">
              <? $this->FormObjVermArt->outputHTML(); echo $this->FormObjVermArt->html; ?>   
            </div>
          </td>
          <td width="100">
            <div align="left">
              <em><font size="2">Tag.Monat.Jahr</font></em><br>
              <input name="datum" type="text" value="<?php echo $this->formvars['datum']; ?>" size="11"> 
            </div>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="right"><input type="submit" name="submit" value="Senden">
      <input type="hidden" name="go" value="<?php echo $this->formvars['go']; ?>">
      <input type="hidden" name="stelle_id" value="<?php echo $this->formvars['stelle_id']; ?>">
    </td>
  </tr>
</table>
