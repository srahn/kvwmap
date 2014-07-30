
<script type="text/javascript" src="funktionen/calendar.js"></script>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<SCRIPT src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></SCRIPT>
<script type="text/javascript">
<!--

Text[1]=["Achtung:","Bei Auswahl von Gemarkung und Flur erfolgt eine räumliche Suche über die aktuelle Flurgeometrie."]


function save(){
	art = document.getElementsByName('abfrageart');
	if(art[0].checked == true){
		if(document.GUI.suchgemarkung.value == '' && (document.GUI.suchflur.value != '' || document.GUI.suchrissnr.value != '' || document.GUI.suchfortf.value != '')){
			alert('Bitte geben Sie die Gemarkung an.');
			return;
		}
		if(document.GUI.sVermStelle.value != '' && document.GUI.suchgemarkung.value == '' && document.GUI.suchstammnr.value == '' && document.GUI.suchrissnr.value == '' && document.GUI.sdatum.value == '' && document.GUI.suchfortf.value == ''){
			alert('Bitte geben Sie eine Gemarkung, eine Antragsnummer, eine Rissnummer, ein Datum oder eine Fortführung an.');
			return;
		}
		if(document.GUI.suchgemarkung.value == '' && document.GUI.suchstammnr.value == '' && document.GUI.sdatum.value == ''){
			alert('Bitte geben Sie Suchparameter an.');
			return;
		}
		if(document.GUI.sdatum.value != ''){
			date1 = datecheck(document.GUI.sdatum.value);
			if(!date1){
				alert('Das Datum muss im Format TT.MM.JJJJ angegeben werden.');
				return;
			}
			if(document.GUI.sdatum2.value != ''){
				date2 = datecheck(document.GUI.sdatum2.value);
				if(!date2){
					alert('Das Datum muss im Format TT.MM.JJJJ angegeben werden.');
					return;
				}
				if(date2 < date1){
					alert('Das zweite Datum muss zeitlich nach dem ersten liegen.');
					return;
				}
			}
			else{
				alert('Bitte geben Sie ein zweites Datum ein.');
				return;
			}
		}
	}
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

function updateGemarkungsauswahl(){
	document.GUI.gemschl.value = document.GUI.gemschl1.value+document.GUI.gemschl2.value;
	selectbyString(document.GUI.suchgemarkung, document.GUI.gemschl.value);
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

<table style="border: 1px solid; border-color: #eeeeee; border-left: none; border-right: none" border="0" cellpadding="4" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td colspan="3"> <div align="center"></div>      <div align="center"><h2><?php echo $this->titel; ?></h2> 
    </div></td>
  </tr>
  <tr> 
    <td rowspan="16">&nbsp;</td>
    <td rowspan="16"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_polygon_box_query_area.php')
			?>
    </td>
    <td></td>
  </tr>
  <tr> 
    <td colspan="2">Recherche nach folgenden Dokumenten:</td>
  </tr>
  <tr> 
    <td colspan="2"><input type="checkbox" name="suchffr" value="1"<?php if ($this->formvars['suchffr']) { ?> checked<?php } ?>>&nbsp;Fortführungsriss&nbsp;(FFR) </td>
  </tr>
  <tr> 
    <td colspan="2"><input type="checkbox" name="suchkvz" value="1"<?php if ($this->formvars['suchkvz']) { ?> checked<?php } ?>>&nbsp;Koordinatenverzeichnis&nbsp;(KVZ)</td>
  </tr>
  <tr> 
    <td colspan="2"><input type="checkbox" name="suchgn" value="1"<?php if ($this->formvars['suchgn']) { ?> checked<?php } ?>>&nbsp;Grenzniederschrift&nbsp;(GN)</td>
  </tr>
  <tr> 
    <td width="20%"><input type="checkbox" name="suchan" value="1"<?php if ($this->formvars['suchan']) { ?> checked<?php } ?>>&nbsp;Andere&nbsp;</td>
  </tr>
  <tr>
	<td>&nbsp;Gültigkeit:
		<select name="gueltigkeit">
			<option value="">--- Auswahl ---</option>
			<option value="1">gültige Nachweise</option>
			<option value="0">ungültige Nachweise</option>
		</select>
	</td>
  </tr>
  <tr> 
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td>Auswahlverfahren:</td>
  </tr>
  <tr> 
    <td colspan="2">
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td rowspan="7" valign="top"><input type="radio" name="abfrageart" value="indiv_nr" <?php if ($this->formvars['abfrageart']=='indiv_nr') { ?> checked<?php } ?>>
          </td>
        </tr>
		<tr><td colspan="3"><span class="fett">Auswahl über Attribute<br></span></td></tr>
        <tr>
          <td colspan="3">
			Gemarkung:<br>
			<input name="gemschl1" type="text" value="13" style="width:23px" onkeyup="updateGemarkungsauswahl();">
			<input name="gemschl2" type="text" value="<? echo substr($this->formvars['suchgemarkung'], 2, 4); ?>" style="width:46px" onkeyup="updateGemarkungsauswahl();">
			<input name="gemschl" type="hidden" value="<? echo $this->formvars['suchgemarkung']; ?>">
			<?php 
			  $this->GemkgFormObj->outputHTML();
			  echo $this->GemkgFormObj->html;
			 ?>
          </td>
		</tr>
		<tr>
          <td align="left" colspan="3">Flur:&nbsp;&nbsp;&nbsp;&nbsp;
						<div style="position: relative">
						<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[1],Style[0], document.getElementById('TipLayer'))" onmouseout="htm()"><br><input type="text" name="suchflur" value="<?php echo $this->formvars['suchflur']; ?>" size="3" maxlength="3">
						<DIV id="TipLayer" style="visibility:hidden;position:absolute;z-index:1000;left: -50px"></DIV>
						</div>
          </td>
        </tr>
		<tr>
        <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){ ?>
          <td align="left">          Antragsnummer<br>
  					<input type="text" name="suchstammnr" value="<?php echo $this->formvars['suchstammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
 					</td>    
        <? } ?>
          <td align="left">          Rissnummer<br>
  					<input type="text" name="suchrissnr" value="<?php echo $this->formvars['suchrissnr']; ?>" size="<?php echo RISSNUMMERMAXLENGTH; ?>" maxlength="<?php echo RISSNUMMERMAXLENGTH; ?>">
 					</td>    
        <? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){ ?>
          <td align="left">          Antragsnummer<br>
  					<input type="text" name="suchstammnr" value="<?php echo $this->formvars['suchstammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
 					</td>    
        <? } ?>
          <td width="50%" align="left">          Fortführungsjahr<br>
			<input type="text" name="suchfortf" value="<?php echo $this->formvars['suchfortf']; ?>" size="4" maxlength="4">
		  </td>    
        </tr>
        <tr> 
			    <td colspan="3">
			    		Datum:<br>
						<a href="javascript:;" title=" (TT.MM.JJJJ) " onclick="new CalendarJS().init('sdatum')"><img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_sdatum"></a></div>
			    		<input id="sdatum" name="sdatum" type="text" onchange="if(document.GUI.sdatum2.value=='')document.GUI.sdatum2.value=this.value" value="<?php echo $this->formvars['sdatum']; ?>" size="10" maxlength="50">
						&nbsp;bis&nbsp;
						<a href="javascript:;" title=" (TT.MM.JJJJ) " onclick="new CalendarJS().init('sdatum2')"><img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_sdatum2"></a></div>
			    		<input id="sdatum2" name="sdatum2" type="text" onchange="" value="<?php echo $this->formvars['sdatum2']; ?>" size="10" maxlength="50">
			    </td>
			  </tr>
			  <tr>
			    <td colspan="3">Vermessungsstelle:<br> 
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
    <td height="35px" colspan="2"><input type="radio" name="abfrageart" value="poly" <?php if ($this->formvars['abfrageart']=='poly' OR $this->formvars['abfrageart']=='') { ?> checked<?php } ?>> 
   <span class="fett">Auswahl im Kartenausschnitt über Suchpolygon</span></td>
  </tr>
  <tr> 
    <td colspan="2"><input type="radio" name="abfrageart" value="antr_nr" <?php if ($this->formvars['abfrageart']=='antr_nr') { ?> checked<?php } ?>>
		<span class="fett">Vorbereitungsnummer:</span>
      <?php $this->FormObjAntr_nr->outputHTML();
        echo $this->FormObjAntr_nr->html;?>
    </td>
  </tr>
  <tr>	
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="2">Geometrie übernehmen von:<br>
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
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td align="center" colspan="2"><input type="button" name="senden" value="Suchen" onclick="save();"> </td>
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
