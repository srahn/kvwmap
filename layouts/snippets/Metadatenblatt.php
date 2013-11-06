<br>
<h2><?php echo $this->titel; ?></h2>
<?php
	if ($this->errmsg!='') {
		echo '<br>'.$this->errmsg;
	}
?>
<table border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="134">Titel:</td>
        <td width="206"><strong><?php echo $this->metadataset['restitle']; ?></strong>&nbsp;</td>
      </tr>
      <tr>
        <td>Zusammenfassung:</td>
        <td><?php echo $this->metadataset['idabs']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td>Metadaten_ID:</td>
        <td><?php echo $this->metadataset['mdfileid']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><hr>
      Metadaten der Organisation</td>
      </tr>
      <tr>
        <td>Name:</td>
        <td><strong><?php echo $this->metadataset['rporgname']; ?></strong>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Adresse:</td>
        <td><?php echo $this->metadataset['delpoint']; ?><br>
            <?php echo $this->metadataset['postcode']; ?>&nbsp;<?php echo $this->metadataset['city']; ?><br>
            <?php echo $this->metadataset['adminarea']; ?><br>
            <?php echo $this->metadataset['country']; ?></td>
      </tr>
      <tr>
        <td valign="top">Link:</td>
        <td><a href="<?php echo $this->metadataset['linkage']; ?>" class="green"><?php echo $this->metadataset['linkage']; ?></a></td>
      </tr>
      <tr>
        <td>Metadatentyp:</td>
        <td><?php echo $this->metadataset['idtype']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><hr>
      Metadaten f&uuml;r Services</td>
      </tr>
      <tr>
        <td valign="top">Service Typ:</td>
        <td><strong><?php echo $this->metadataset['servicetype']; ?></strong>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Service Version:</td>
        <td><?php echo $this->metadataset['serviceversion']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Online Link:</td>
        <td><a href="<?php echo $this->metadataset['onlinelinke']; ?>" class="green"><?php echo $this->metadataset['onlinelinke']; ?></a>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><hr>
      Metadaten f&uuml;r Datens&auml;tze</td>
      </tr>
      <tr>
        <td valign="top">R&auml;umlicher Typ:</td>
        <td><?php echo $this->metadataset['spatialtype']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Zielma&szlig;stab:</td>
        <td><?php echo $this->metadataset['vector_scale']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Aufl&ouml;sung:</td>
        <td><?php echo $this->metadataset['solution']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Status:</td>
        <td><?php echo $this->metadataset['status']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Pflegezyklus:</td>
        <td><?php echo $this->metadataset['cyclus']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Referenzsystem:</td>
        <td><?php echo $this->metadataset['sparefsystem']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Lieferformat:</td>
        <td><?php echo $this->metadataset['sformat']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Formatversion:</td>
        <td><?php echo $this->metadataset['sformatversion']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Downloadm&ouml;glichkeit:</td>
        <td><a href="<?php echo $this->metadataset['download']; ?>" class="green"><?php echo $this->metadataset['download']; ?></a>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><hr>
      Metadaten f&uuml;r Anwendungen:</td>
      </tr>
      <tr>
        <td valign="top">Online Link:</td>
        <td><a href="<?php echo $this->metadataset['onlinelink']; ?>" class="green"><?php echo $this->metadataset['onlinelink']; ?></a>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Querverweis:</td>
        <td><?php echo $this->metadataset['relation']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Rechte:</td>
        <td><?php echo $this->metadataset['accessrights']; ?>&nbsp;</td>
      </tr>
      <tr align="center">
        <td colspan="2" valign="top"><input type="hidden" name="go" value="Metadaten_Auswaehlen">
            <input type="hidden" name="was" value="<?php echo $this->formvars['was']; ?>">
            <input type="hidden" name="vonwann" value="<?php echo $this->formvars['vonwann']; ?>">
            <input type="hidden" name="biswann" value="<?php echo $this->formvars['biswann']; ?>">
            <input type="hidden" name="wer" value="<?php echo $this->formvars['wer']; ?>">
            <input type="hidden" name="wo" value="<?php echo $this->formvars['wo']; ?>">
            <input type="hidden" name="northbl" value="<?php echo $this->formvars['northbl']; ?>">
            <input type="hidden" name="southbl" value="<?php echo $this->formvars['southbl']; ?>">
            <input type="hidden" name="westbl" value="<?php echo $this->formvars['westbl']; ?>">
            <input type="hidden" name="eastbl" value="<?php echo $this->formvars['eastbl']; ?>">
            <input type="submit" name="Submit" value="Zur Metadatensuche">
        </td>
      </tr>
    </table></td>
  </tr>
</table>
