<?php
if ($this->Meldung=='Daten zum neuen Dokument erfolgreich eingetragen!' OR $this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}
 ?>
      
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr> 
    <td colspan="5"><table border="0" align="right" cellpadding="5" cellspacing="0">
        <tr> 
          <td>Dokument<?php 
		  if ($this->formvars['id']!='') { 
		  ?> auch ändern: 
          <input type="checkbox" name="changeDocument" value="1"><?php
		  }
		  ?></td>
          <td rowspan="2">&nbsp;</td>
          <td valign="top">Individuelle Nummer:</td>
          <td align="right" valign="top">Flur:&nbsp;
            <input name="Flur" type="text" value="<?php echo $this->formvars['Flur']; ?>" size="3" maxlength="3">
            &nbsp;
            Stammnr:&nbsp;
            <input name="stammnr" type="text" id="stammnr" value="<?php echo str_pad(intval($this->formvars['stammnr']),STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT); ?> " size="9" maxlength="8">            <br> 
          </td>
        </tr>
        <tr> 
          <td>Datei vom lokalen Rechner:<br> <input name="Bilddatei" type="file" value="<?php echo $this->formvars['Bilddatei']; ?>" size="22" accept="image/*.jpg"> 
          </td>
         
          <td colspan="2" align="right" valign="top">Gemarkung:
            <?php 
		  $this->GemkgFormObj->outputHTML();
		  echo $this->GemkgFormObj->html;
		  ?>
        <!-- <input name="Gemarkung" type="text" value="<?php echo $this->
          formvars['Gemarkung']; ?>" size="7" maxlength="6"> //--> </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td rowspan="15">&nbsp; </td>
    <td rowspan="15"> 
      <?php
 				include(LAYOUTPATH.'snippets/SVG_dokumentenformular.php')
			?>
    </td>
    <td colspan="2"><hr align="center" noshade></td>
  <tr> 
    <td colspan="2"><input type="radio" name="art" value="100"<?php if ($this->formvars['art']=='100') { ?> checked<?php } ?>>
      Fortführungsriss&nbsp;(FFR)</td>
  <tr> 
    <td colspan="2"><input type="radio" name="art" value="010"<?php if ($this->formvars['art']=='010') { ?> checked<?php } ?>>
      Koordinatenverzeichnis&nbsp;(KVZ)</td>
  <tr> 
    <td colspan="2"><input type="radio" name="art" value="001"<?php if ($this->formvars['art']=='001') { ?> checked<?php } ?>>
      Grenzniederschrift&nbsp;(GN)</td>
  <tr> 
    <td colspan="2"><hr align="center" noshade></td>
  <tr> 
    <td colspan="2">Blattnummer: 
      <input name="Blattnr" type="text" value="<?php echo str_pad(intval($this->formvars['Blattnr']),3,'0',STR_PAD_LEFT); ?>" size="4" maxlength="3"> 
    </td>
  <tr> 
    <td colspan="2">&nbsp;</td>
  <tr> 
    <td>Datum:<br> <font size="1"><em>(1989-05-31)</em></font></td>
    <td><input name="datum" type="text" value="<?php echo $this->formvars['datum']; ?>" size="10" maxlength="50"></td>
  <tr> 
    <td colspan="2">&nbsp;</td>
  <tr> 
    <td colspan="2">Vermessungsstelle:<br> 
      <?php
              $this->FormObjVermStelle->outputHTML();
              echo $this->FormObjVermStelle->html;
          ?>
    </td>
  <tr> 
    <td colspan="2">&nbsp;</td>
  <tr> 
    <td colspan="2">Blattformat: 
      <?php 
              $i=0;
                while ($i<3) {
                  $BlattformatZahl[]=++$i;
                }
                $FormatWerte = array('A4','A3','SF');               
                $FormatBez = array('A4','A3','Sonderformat');
                $Blattformat = new FormObject('Blattformat','select',$FormatWerte,array($this->formvars['Blattformat']),$FormatBez,1,$maxlenght,$multiple,NULL);
                $Blattformat->OutputHTML();
                echo $Blattformat->html;
              ?>
    </td>
  <tr> 
    <td colspan="2">&nbsp;</td>
  <tr> 
    <td colspan="2"><table border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td>g&uuml;ltig 
            <input type="radio" name="gueltigkeit" value="1" <?php if ($this->formvars['gueltigkeit']=='1' OR $this->formvars['gueltigkeit']=='') { ?> checked<?php } ?>> 
          </td>
          <td> ung&uuml;ltig 
            <input type="radio" name="gueltigkeit" value="0" <?php if ($this->formvars['gueltigkeit']=='0') { ?> checked<?php } ?>> 
          </td>
        </tr>
      </table></td>
  <tr> 
    <td colspan="2"><hr align="center" noshade></td>
  <tr> 
    <td colspan="2"><?php if ($this->formvars['stammnr']!='') { ?><a href="file:///H|/kvwmap/2005-09-15_kvwmap_dev_home/layouts/snippets/index.php?go=Nachweisanzeige">&lt;&lt;&nbsp;zur&uuml;ck&nbsp;zum&nbsp;Rechercheergebnis</a><?php } ?></td>
    <td colspan="2"><table border="0">
        <tr> 
          <td><input type="reset" name="go_plus2" value="Zurücksetzen"></td>
          <td><input type="submit" name="go_plus" value="Senden"></td>
        </tr>
      </table></td>
  </tr>
</table>
      <input type="hidden" name="go" value="Nachweisformular" >
      <input type="hidden" name="MAX_FILE_SIZE" value="5000">
      <input type="hidden" name="id" value="<?php echo $this->formvars['id']; ?>">
      <input type="HIDDEN" name="minx" value="<?php echo $this->map->extent->minx; ?>">
      <input type="HIDDEN" name="miny" value="<?php echo $this->map->extent->miny; ?>">
      <input type="HIDDEN" name="maxx" value="<?php echo $this->map->extent->maxx; ?>">
      <input type="HIDDEN" name="maxy" value="<?php echo $this->map->extent->maxy; ?>">
      <input type="HIDDEN" name="scale" value="<?php echo $scale; ?>">
      <input type="HIDDEN" name="CMD" value="">
      <input type="HIDDEN" name="INPUT_TYPE" value="">
      <input type="HIDDEN" name="INPUT_COORD" value="">
      <input type="hidden" name="imgxy" value="300 300">
      <input type="hidden" name="imgbox" value="-1 -1 -1 -1">
  