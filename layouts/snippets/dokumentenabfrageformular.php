
<script type="text/javascript">
<!--

function save(){
	art = document.getElementsByName('abfrageart');
	document.GUI.gemarkung.value = document.GUI.gemarkung1.value + document.GUI.gemarkung2.value; 
	if(art[1].checked == true && document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.go_plus.value = 'Senden';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
	}
}

function buildwktpolygonfromsvgpath(svgpath){
	var koords;
	wkt = "POLYGON((";
	parts = svgpath.split("M");
	for(j = 1; j < parts.length; j++){
		if(j > 1){
			wkt = wkt + "),("
		}
		koords = ""+parts[j];
		coord = koords.split(" ");
		wkt = wkt+coord[1]+" "+coord[2];
		for(var i = 3; i < coord.length-1; i++){
			if(coord[i] != ""){
				wkt = wkt+","+coord[i]+" "+coord[i+1];
			}
			i++;
		}
	}
	wkt = wkt+"))";
	return wkt;
}	
  
//-->
</script>

<?php
if ($this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}
 ?>

<table border="0" cellpadding="4" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td colspan="3"> <div align="center"></div>      <div align="center"><strong><font size="+1"><?php echo $this->titel; ?></font></strong> 
    </div></td>
  </tr>
  <tr> 
    <td rowspan="17">&nbsp;</td>
    <td rowspan="17"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_polygon_box_query_area.php')
			?>
    </td>
    <td></td>
  </tr>
  <tr> 
    <td>Recherche nach folgenden Dokumenten:</td>
  </tr>
  <tr> 
    <td><input type="checkbox" name="suchffr" value="1"<?php if ($this->formvars['suchffr']) { ?> checked<?php } ?>>&nbsp;Fortführungsriss&nbsp;(FFR) </td>
  </tr>
  <tr> 
    <td><input type="checkbox" name="suchkvz" value="1"<?php if ($this->formvars['suchkvz']) { ?> checked<?php } ?>>&nbsp;Koordinatenverzeichnis&nbsp;(KVZ)</td>
  </tr>
  <tr> 
    <td><input type="checkbox" name="suchgn" value="1"<?php if ($this->formvars['suchgn']) { ?> checked<?php } ?>>&nbsp;Grenzniederschrift&nbsp;(GN)</td>
  </tr>
  <tr> 
    <td><input type="checkbox" name="suchan" value="1"<?php if ($this->formvars['suchan']) { ?> checked<?php } ?>>&nbsp;Andere&nbsp;</td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td>Auswahlverfahren:</td>
  </tr>
  <tr> 
    <td><strong>&nbsp;Individuelle Nummer:<br>
    </strong>
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td rowspan="7"><input type="radio" name="abfrageart" value="indiv_nr" <?php if ($this->formvars['abfrageart']=='indiv_nr') { ?> checked<?php } ?>>
          </td>
          <td>Gemarkung&nbsp;</td>
          <td>Flur</td>
        </tr>
        <tr>
          <td width="80px">
          	<input type="text" name="gemarkung1" value="13" style="width:23px"  maxlength="2">
          	<input type="text" name="gemarkung2" value="<?php echo substr($this->formvars['gemarkung'], 2, 4); ?>" style="width:46px" maxlength="6">
          	<input type="hidden" name="gemarkung" value="">
          </td>
          <td align="left "><input type="text" name="flur" value="<?php echo $this->formvars['flur']; ?>" size="3" maxlength="3">
          </td>
        </tr>
        <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){ ?>
        <tr>
          <td colspan="2">          Antragsnummer<br>
  					<input type="text" name="suchstammnr" value="<?php echo $this->formvars['suchstammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
 					</td>    
        </tr>
        <? } ?>
        <tr>
          <td colspan="2">          Rissnummer<br>
  					<input type="text" name="suchrissnr" value="<?php echo $this->formvars['suchrissnr']; ?>" size="<?php echo RISSNUMMERMAXLENGTH; ?>" maxlength="<?php echo RISSNUMMERMAXLENGTH; ?>">
 					</td>    
        </tr>
        <? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){ ?>
        <tr>
          <td colspan="2">          Antragsnummer<br>
  					<input type="text" name="suchstammnr" value="<?php echo $this->formvars['suchstammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
 					</td>    
        </tr>
        <? } ?>
        <tr>
          <td colspan="2">          Fortfuehrung<br>
  					<input type="text" name="suchfortf" value="<?php echo $this->formvars['suchfortf']; ?>" size="4" maxlength="4">
 					</td>    
        </tr>
        <tr> 
			    <td colspan="2">
			    		Datum:<font size="1"><em>(1989-05-31)</em></font><br>
			    		<input name="datum" type="text" value="<?php echo $this->formvars['datum']; ?>" size="10" maxlength="50">
			    </td>
			  </tr>
			  <tr>
			    <td colspan="2">Vermessungsstelle:<br> 
			      <?php
	              $this->FormObjVermStelle->outputHTML();
	              echo $this->FormObjVermStelle->html;
	          ?>
			    </td>
			  </tr>
      </table>
    </td>
  </tr>
  <tr> 
  
    <td>
      <strong>&nbsp;Suchpolygon/-fenster:</strong></td>
  </tr>
  <tr> 
    <td><input type="radio" name="abfrageart" value="poly" <?php if ($this->formvars['abfrageart']=='poly' OR $this->formvars['abfrageart']=='') { ?> checked<?php } ?>> 
   <em>Auswahl im Kartenausschnitt</em></td>
  </tr>
  <tr> 
    <td>
      <strong>&nbsp;Vorbereitungsnummer:</strong></td>
  </tr>
  <tr> 
    <td><input type="radio" name="abfrageart" value="antr_nr" <?php if ($this->formvars['abfrageart']=='antr_nr') { ?> checked<?php } ?>>
      <?php $this->FormObjAntr_nr->outputHTML();
        echo $this->FormObjAntr_nr->html;?>
    </td>
  </tr>
  <tr>	
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>Geometrie übernehmen von:<br>
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<option value="">--- Auswahl ---</option>
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select> 
  	</td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td align="left"> <input type="reset" name="go_reset" value="Zurücksetzen">&nbsp;<input type="button" name="senden" value="Senden" onclick="save();"> </td>
  </tr>
  <tr>
  	<td></td>
  	<td align="right"><input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;</td>
  </tr>
</table>
		
		<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
		<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
    <input type="hidden" name="imgxy" value="300 300"> 
    <input type="hidden" name="imgbox" value="-1 -1 -1 -1">
    <input type="hidden" name="art_markieren" value="111" >
    <input type="hidden" name="go" value="Nachweisrechercheformular" >
    <input type="hidden" name="go_plus" value="" >
