<?php
 # 2008-01-20 pkvvm
  include(LAYOUTPATH.'languages/wms_import_'.$this->user->rolle->language.'.php');
 ?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr>
    <td colspan="2" align="center"><h2><?php echo $this->titel; ?></h2></td> 
  </tr>
  <tr>
    <td colspan="2" align="center"><?php
if ($this->Fehlermeldung!='') {
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?></td>
  </tr>
  <tr>
    <td colspan="2"><span class="fett"><?php echo $strFindGoodWMS; ?></span></td>
  </tr>
  <tr>
    <td colspan="2">
    <ul>
      <li><a href="http://www.geoportal.bund.de/" target="_blank">GeoMIS.Bund</a></li>
      <li>          <a href="http://www.refractions.net/white_papers/ogcsurvey/index.php" target="_blank">OGC Survey refractions.net</a></li>
    </ul></td>
  </tr>
  <tr>
    <td colspan="2"><span class="fett"><?php echo $strAdresseCapabilitiesDocument; ?></span><br>
      <textarea name="capabilitiesURI" cols="80" rows="3"><?php
    if ($this->formvars['capabilitiesURI']!='') {
      echo $this->formvars['capabilitiesURI'];
    }
    else {
      echo "http://server?SERVICE=wms&REQUEST=GetCapabilities";
    }
     ?></textarea><br>
    <em><font size="-1">z.B.:&nbsp;http://kvwmap.geoinformatik.uni-rostock.de/cgi-bin/mapserv?map=/www/kvwmap/wms/TK750-MV.map&amp;SERVICE=WMS&amp;REQUEST=GetCapabilities </font></em><font size="-1">&nbsp; </font></td>
  </tr>
  <?php
    $anzWMSlayer=count($this->wms->objLayer);
    if ($anzWMSlayer>0) { 
?>  
  <tr>
    <td colspan="2"><?php
   if ($this->formvars['capabilitiesURI']!='') {
      ?>
      <span class="fett">Capabilities &Uuml;bersicht:</span> <a href="<?php echo $this->formvars['capabilitiesURI']; ?>">als
        XML-Datei</a> <input type="checkbox" name="capabilitiesAnzeigen" value="1"<?php if ($this->formvars['capabilitiesAnzeigen']=='1') { ?> checked<?php  } ?>>
      Kurzdarstellung hier anzeigen<?php
     }
  ?>
  </td>
  </tr>
  <?php
  if ($this->formvars['capabilitiesAnzeigen']) {
  ?><tr>
    <td colspan="2"><?php 
      $this->wms->displayWMS();
  ?></td>
  </tr>
  <?php
  }
   ?>
  <tr>
    <td colspan="2"><span class="fett">Haken Sie die getMap-Requests an, die sie in die Stelle als Layer einbinden wollen.</span></td>
  </tr><?php
   $this->wms->wms_getmap.='VERSION='.$this->wms->wms_version.'&REQUEST=GetMap&SERVICE=wms&LAYERS=';  
#   for ($i=0;$i<$anzWMSlayer;$i++) {
#     echo $this->wms->objLayer[$i]->layer_name.',';
#   }
  for ($i=0;$i<$anzWMSlayer;$i++) {
  ?>
  <tr>
    <td align="right"><input type="checkbox" name="selectedwmslayer[<?php
     $i;
    ?>]" value="<?php
     echo $this->wms->objLayer[$i]->layer_id;
    ?>"></td>
    <td align="left">Name: <span class="fett"><?php echo $this->wms->objLayer[$i]->layer_name; ?></span> Titel: <span class="fett"><?php echo $this->wms->objLayer[$i]->layer_title; ?></span>
  <br><a href="<?php echo $this->wms->wms_getmap.$this->wms->objLayer[$i]->layer_name; ?>" target="_blank"><?php echo $this->wms->wms_getmap.$this->wms->objLayer[$i]->layer_name; ?></a></td>
  </tr><?php
     }
   ?>
  <tr>
    <td colspan="2"><em><font size="-1">Testen Sie die angegebenen Links vor
          dem Einbinden in kvwmap. Im allgemeinen m&uuml;ssen
      Karten zur&uuml;ckgeliefert werden, wenn man auf die Links klickt. Zus&auml;tzliche
      Parameter wie die BBox, Width und Hight werden vom kvwmap-Client zur Laufzeit
    hinzugef&uuml;gt.</font></em></td>
  </tr>
   <?php
   }
   ?>

  <tr>
    <td align="right">&nbsp;</td> 
    <td align="center"><input type="hidden" name="go" value="WMS_Import">      <input type="submit" name="go_plus" value="<?php echo $this->strCancel; ?>">&nbsp;
      <input type="submit" name="Input" value="<?php echo $this->strSend; ?>">&nbsp;<?php
      if ($anzWMSlayer>0) {
       ?><input name="go_plus" type="submit" id="go_plus" value="Eintragen"><?php
      }
      ?>
</td></tr>
</table>
