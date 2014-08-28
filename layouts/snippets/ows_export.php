<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/ows_export_'.$this->user->rolle->language.'.php');
 ?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr>
    <td colspan="2" align="left"><?php echo $strWMSExportWarning; ?></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><?php echo $strAllOrActiveLayer; ?><br>
    <input type="radio" name="nurAktiveLayer" value="0" checked>
    <?php echo $strAll; ?>
    <input type="radio" name="nurAktiveLayer" value="1">
    <?php echo $strActiveLayer; ?></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"> <p><?php echo $strNameOfMapFile; ?><br>
      <input name="mapfile_name" type="text" id="mapfile_name" value="<?php echo 'wms_test.map'; ?>" size="50" maxlength="50">
    </p>
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><p><?php echo $strOwsTitle; ?><br>
      <input name="ows_title" type="text" id="ows_title" value="<?php echo OWS_TITLE; ?>" size="100" maxlength="100">
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><?php echo $strOwsAbstract; ?><br>
      <textarea name="ows_abstract" cols="30" rows="3" id="ows_abstract"><?php echo OWS_ABSTRACT; ?></textarea>      <br>
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><?php echo $strContactInfo; ?></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><?php echo $strContactPerson; ?>
      <input name="ows_contactperson" type="text" id="ows_contactperson" value="<?php echo OWS_CONTACTPERSON; ?>" size="50" maxlength="50"></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><?php echo $strOrganisation; ?>
      <input name="ows_contactorganization" type="text" id="ows_contactorganization3" value="<?php echo OWS_CONTACTORGANIZATION; ?>" size="50" maxlength="50"></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><?php echo $strEMail; ?>
        <input name="ows_contactelectronicmailaddress" type="text" value="<?php echo OWS_CONTACTELECTRONICMAILADDRESS; ?>" size="50" maxlength="150">
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><?php echo $strFee; ?>
    <input name="ows_fees" type="text" value="<?php echo OWS_FEES; ?>" size="50" maxlength="100"></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="center"><input type="hidden" name="go" value="WMS_Export">      <input type="submit" name="go_plus" value="<?php echo $this->strCancel; ?>">&nbsp;
      <input type="submit" name="go_plus" value="<?php echo $this->strSend; ?>">
</td></tr>
</table>
