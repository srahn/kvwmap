<?

if($this->go == 'bevoelkerung_bericht_Bericht erstellen'){
	include (PDFCLASSPATH."class.ezpdf.php");
  $pdf=new Cezpdf();
  $pdf->selectFont(PDFCLASSPATH.'fonts/Helvetica.afm');
  $y = 825;
  $pdf->addText(45, $y-=30, 18, 'Bevölkerungsprognose - Bericht');
  $ueberschriften = array('Einwohner pro Landkreis', 'Einwohnerdichte pro Landkreis', 'Einwohner pro Altersgruppe', 'Durchschnittsalter pro Landkreis', 'Zusammengefasste Geburtenziffer', 'Bevölkerungsbewegung');
  $formvars_chosen_layer_id = array(465, 465, 551, 466, 94, 524);
  $formvars_chartvalue = array('einwohner2009', 'einwohnerproqkm2009', 'einwohner2009', 'durchschnittsalter2009', 'geburtenziffer', 'rueckgang');
  $formvars_chartlabel = array('kreis', 'kreis', 'altersgruppe', 'kreis', 'kreis', 'kreis');
  $formvars_chartsplit = array('', '', 'geschlecht');
  $formvars_chartcomparison = array('', '', 'einwohner2030p');
  $formvars_orderby = array('', '', 'altersgruppe desc, geschlecht', 'durchschnittsalter2009', 'geburtenziffer', 'rueckgang');
  $formvars_charttype = array('bar', 'bar', 'mirrorbar', 'bar', 'bar', 'bar');
  for($i = 0; $i < count($formvars_chosen_layer_id); $i++){
		if($this->formvars['check'.$i] == 'on'){
			$this->formvars['chosen_layer_id'] = $formvars_chosen_layer_id[$i];
			$this->formvars['chartvalue_'.$formvars_chosen_layer_id[$i]] = $formvars_chartvalue[$i];
			$this->formvars['chartlabel_'.$formvars_chosen_layer_id[$i]] = $formvars_chartlabel[$i];
			$this->formvars['chartsplit_'.$formvars_chosen_layer_id[$i]] = $formvars_chartsplit[$i];
			$this->formvars['chartcomparison_'.$formvars_chosen_layer_id[$i]] = $formvars_chartcomparison[$i];
			$this->formvars['orderby_'.$formvars_chosen_layer_id[$i]] = $formvars_orderby[$i];
			$this->formvars['charttype_'.$formvars_chosen_layer_id[$i]] = $formvars_charttype[$i];
			$this->formvars['anzahl'] = 300;
			$this->formvars['all'] = 'true';
			$dateiname=IMAGEPATH.rand(100000,999999).'.jpg';
			$this->generisches_sachdaten_diagramm(1000, $dateiname);
			list($width, $height, $type, $attr)= getimagesize($dateiname);
			# Seitenumbruch wenn erforderlich
			if($y-$height*500/$width-12 < 0) {
        # neue Seite beginnen
        $pageid=$pdf->newPage();
        $y = 825;
      }
      $pdf->addText(45, $y-=30, 14, $ueberschriften[$i]);
			$y = $y-$height*500/$width-12;			
			$pdf->addJpegFromFile($dateiname,45,$y,500); 
		}
  }
	$this->pdf=$pdf;
  $this->mime_type='pdf';
  $dateipfad=IMAGEPATH;
  $currenttime = date('Y-m-d_H_i_s',time());
  $name = str_replace('ä', 'ae', $this->user->Name);
  $name = str_replace('ü', 'ue', $name);
  $name = str_replace('ö', 'oe', $name);
  $name = str_replace('Ä', 'Ae', $name);
  $name = str_replace('Ü', 'Ue', $name);
  $name = str_replace('Ö', 'Oe', $name);
  $name = str_replace('ß', 'ss', $name);
  $dateiname = $name.'-'.$currenttime.'_'.rand(0,99999999).'.pdf';
  $this->outputfile = $dateiname;
  $fp=fopen($dateipfad.$dateiname,'wb');
  fwrite($fp,$this->pdf->ezOutput());
  fclose($fp);
  $this->output();
}
else{

?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td height="50" valign="middle"><strong><font size="+1">Bevölkerungsprognose Berichterstellung</font></strong></td>
  </tr>
  <tr>
    <td align="center">
		<table border="0" cellspacing="0" cellpadding="3" style="border:1px solid <?php echo BG_DEFAULT ?>">
			<tr align="center">
		    <th bgcolor="<?php echo BG_DEFAULT ?>" width="570" colspan="3" style="border-bottom:1px solid #C3C7C3">Auswahl der Diagramme</th>
		  </tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check0" checked="true" type="checkbox" size="25" maxlength="100"><b>Einwohner pro Landkreis</b>
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check1" checked="true" type="checkbox" size="25" maxlength="100"><b>Einwohnerdichte pro Landkreis</b>
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check2" checked="true" type="checkbox" size="25" maxlength="100"><b>Einwohner pro Altersgruppe</b>
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check3" checked="true" type="checkbox" size="25" maxlength="100"><b>Durchschnittsalter pro Landkreis</b>
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check4" checked="true" type="checkbox" size="25" maxlength="100"><b>Zusammengefasste Geburtenziffer</b>
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check5" checked="true" type="checkbox" size="25" maxlength="100"><b>Bevölkerungsbewegung</b>
	  		</td>
	  	</tr>
		</table>
	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="center">
    	<input type="button" name="button" onclick="document.GUI.go_plus.value='Bericht erstellen';document.GUI.target='_blank';submit();" value="Bericht erstellen">
		</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="bevoelkerung_bericht">
<input type="hidden" name="go_plus" value="">

<?
}
?>
