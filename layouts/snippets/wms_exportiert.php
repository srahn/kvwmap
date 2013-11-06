
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td> 
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Die aktuellen Karteneinstellungen wurden als WMS exportiert.<br>
    Die exportierte Datei kann nun f&uuml;r WMS verwendet werden.</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Hier k&ouml;nnen Sie sich die <a href="<?php
    echo '../'.WMS_MAPFILE_REL_PATH.$this->formvars['mapfile_name']; 
    ?>" target="_blank"><strong>Map-Datei
    ansehen</strong></a>.</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Ein getCapabilitie-Request zu diesem WMS sieht folgendermassen
      aus:<br>
      <strong><a href="<?php echo $this->wms_onlineresource; ?>REQUEST=getCapabilities&VERSION=<?php echo SUPORTED_WMS_VERSION; ?>&SERVICE=wms" target="_blank"><?php echo $this->wms_onlineresource; ?>request=getCapabilities&amp;VERSION=<?php echo SUPORTED_WMS_VERSION; ?>&amp;SERVICE=wms</a></strong></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Die Karte l&auml;&szlig;t sich abfragen mit einem getMap-Request, z.B.<br>
      <strong><a href="<?php echo $this->getMapRequestExample; ?>" target="_blank"><?php echo $this->getMapRequestExample; ?></a></strong></td>
  </tr>

</table>
