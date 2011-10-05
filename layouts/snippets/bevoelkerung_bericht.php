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
	    			<input name="check1" type="checkbox" size="25" maxlength="100"><b>Einwohner pro Landkreis</b>
	    			http://www.mdi-de.org:8080/kvwmap_dev/index.php?go=generisches_sachdaten_diagramm&chosen_layer_id=465&chartvalue_465=einwohner2009&chartlabel_465=kreis&charttype_465=bar&anzahl=30&all=true&width=1000
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check2" type="checkbox" size="25" maxlength="100"><b>Einwohnerdichte pro Landkreis</b>
	    			http://www.mdi-de.org:8080/kvwmap_dev/index.php?go=generisches_sachdaten_diagramm&chosen_layer_id=465&chartvalue_465=einwohnerproqkm2009&chartlabel_465=kreis&charttype_465=bar&anzahl=30&all=true&width=1000
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check3" type="checkbox" size="25" maxlength="100"><b>Einwohner pro Altersgruppe</b>
	    			http://www.mdi-de.org:8080/kvwmap_dev/index.php?go=generisches_sachdaten_diagramm&chosen_layer_id=551&chartvalue_551=einwohner2009&chartlabel_551=altersgruppe&chartsplit_551=geschlecht&chartcomparison_551=einwohner2030p&charttype_551=mirrorbar&orderby551=altersgruppe%20desc,%20geschlecht&anzahl=300&all=true&width=1000
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check4" type="checkbox" size="25" maxlength="100"><b>Durchschnittsalter pro Landkreis</b>
	    			http://www.mdi-de.org:8080/kvwmap_dev/index.php?go=generisches_sachdaten_diagramm&chosen_layer_id=466&chartvalue_466=durchschnittsalter2009&chartlabel_466=kreis&charttype_466=bar&orderby466=durchschnittsalter2009&anzahl=30&all=true&width=1000
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check5" type="checkbox" size="25" maxlength="100"><b>Zusammengefasste Geburtenziffer</b>
	    			http://www.mdi-de.org:8080/kvwmap_dev/index.php?go=generisches_sachdaten_diagramm&chosen_layer_id=94&chartvalue_94=geburtenziffer&chartlabel_94=kreis&charttype_94=bar&orderby94=geburtenziffer&anzahl=30&all=true&width=1000
	  		</td>
	  	</tr>
	  	<tr>
	  		<td>&nbsp;</td>
	    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
	    			<input name="check6" type="checkbox" size="25" maxlength="100"><b>Bevölkerungsbewegung</b>
	    			http://www.mdi-de.org:8080/kvwmap_dev/index.php?go=generisches_sachdaten_diagramm&chosen_layer_id=524&chartvalue_524=rueckgang&chartlabel_524=kreis&charttype_524=bar&orderby524=rueckgang&anzahl=30&all=true&width=1000
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
    	<input type="submit" name="go_plus" id="go_plus" value="Bericht erstellen">
		</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="bevoelkerung_bericht">
