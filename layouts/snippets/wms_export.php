
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><h2><?php echo $this->titel; ?></h2></td> 
  </tr>
  <tr>
    <td colspan="2" align="left">Diese Funktion speichert die aktuellen Kartendarstellungen
      mit zus&auml;tzlichen Metadaten zum Projekt in einer Map-Datei ab, sodass
      die Inhalte in einem OGC konformen Web Map Service (WMS) anderen unabh&auml;ngig
      von <em>kvwmap</em> zur Verf&uuml;gung
      gestellt werden k&ouml;nnen.
Der Speicherort ist in der Konstante WMS_MAPFILE_PATH festzulegen.</td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left">Sollen alle Layer der Stelle ausgegeben werden oder nur
      die aktiven?<br>
    <input type="radio" name="nurAktiveLayer" value="0" checked>
    alle
    <input type="radio" name="nurAktiveLayer" value="1">
    nur aktive</td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"> <p>W&auml;hlen Sie einen Namen f&uuml;r die Map-Datei:<br>
      <input name="mapfile_name" type="text" id="mapfile_name" value="<?php echo 'wms_test.map'; ?>" size="50" maxlength="50">
    </p>
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><p>Wie soll der Service bezeichnet werden (wms_title):<br>
      <input name="wms_title" type="text" id="wms_title" value="z.B. <?php echo WMS_TITLE; ?>" size="100" maxlength="100">
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Geben Sie eine kurze Beschreibung zum Web Map Service (wms_abstract):<br>
      <textarea name="wms_abstract" cols="30" rows="3" id="wms_abstract"><?php echo WMS_ABSTRACT; ?></textarea>      <br>
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Kontaktinformationen:</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Person (wms_contactperson): 
      <input name="wms_contactperson" type="text" id="wms_contactperson" value="<?php echo WMS_CONTACTPERSON; ?>" size="50" maxlength="50"></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Organisation (wms_contactorganization):
      <input name="wms_contactorganization" type="text" id="wms_contactorganization3" value="<?php echo WMS_CONTACTORGANIZATION; ?>" size="50" maxlength="50"></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">E-Mail (wms_contactelectronicmailaddress):
        <input name="wms_contactelectronicmailaddress" type="text" value="<?php echo WMS_CONTACTELECTRONICMAILADDRESS; ?>" size="50" maxlength="150">
    </td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Kosten f&uuml;r die Nutzung des Dienstes (wms_fees):
    <input name="wms_fees" type="text" value="<?php echo WMS_FEES; ?>" size="50" maxlength="100"></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td> 
    <td align="center"><input type="hidden" name="go" value="WMS_Export">      <input type="submit" name="go_plus" value="Abbrechen">&nbsp;
      <input type="submit" name="go_plus" value="Senden">
</td></tr>
</table>
