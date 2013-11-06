<script type="text/javascript" src="funktionen/calendar.js"></script>
<script type="text/javascript">
<!--

function save(){
	document.GUI.result2.value = '';
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
		}
	}
	if(document.GUI.newpathwkt.value != ''){
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=check_nachweis_poly&umring="+document.GUI.newpathwkt.value+"&flur="+document.GUI.Flur.value+"&gemkgschl="+document.GUI.Gemarkung.value, new Array(top.document.GUI.result2), "");
		document.GUI.check.value = 'checking';
	}
}

function check_poly(){
	if(document.GUI.check.value == 'checking'){
		if(document.GUI.result2.value == 'invalid'){
			alert('Achtung! Das Polygon ist fehlerhaft. Bitte korrigieren.');
			document.GUI.check.value = '';
		}
		else{
			if(document.GUI.result2.value == 'f'){
				document.GUI.check.value = '';
				conf = confirm('Achtung! Das Polygon liegt nicht in der angegebenen Flur.\nTrotzdem Speichern?');
				if(conf == true){
					document.GUI.go_plus.value = 'Senden';
					document.GUI.submit();
				}
				else{
					return;
				}
			}
			if(document.GUI.result2.value == 't'){
				window.clearInterval(polycheck);
				document.GUI.go_plus.value = 'Senden';
				document.GUI.submit();
			}
		}
	}
}

var polycheck = window.setInterval("check_poly()", 500);

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
    <td colspan="5">
      <table border="0" align="right" cellpadding="5" cellspacing="0">
        <tr> 
          <td>Dokument<?php 
		  if ($this->formvars['id']!='') { 
		    ?> auch ändern: 
            <input type="checkbox" name="changeDocument" value="1" <?php if ($this->formvars['changeDocument']) { ?> checked<?php } ?>><?php
		  }
		  else {
		    ?><input type="hidden" name="changeDocument" value="1"><?php
		  }
		  ?></td>
          <td>Flur:&nbsp; 
            <input name="Flur" type="text" value="<?php echo $this->formvars['Flur']; ?>" size="3" maxlength="3">
          </td>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){ ?>
          <td>Antragsnr:&nbsp; 
            <input name="stammnr" type="text" value="<?php echo $this->formvars['stammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
          </td>
          <? } ?>
          <td>Rissnummer:
			  		<input name="rissnummer" type="text" value="<?php echo $this->formvars['rissnummer']; ?>" size="<?php echo RISSNUMMERMAXLENGTH; ?>" maxlength="<? echo RISSNUMMERMAXLENGTH; ?>">
			 		</td>
			 		<? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){ ?>
          <td>Antragsnr:&nbsp; 
            <input name="stammnr" type="text" value="<?php echo $this->formvars['stammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
          </td>
          <? } ?>
        </tr>
        <tr> 
          <td>Datei vom lokalen Rechner:<br> <input name="Bilddatei" type="file" onchange="this.title=this.value;" value="<?php echo $this->formvars['Bilddatei']; ?>" size="22" accept="image/*.jpg"> 
          </td>
         
          <td colspan="3">Gemarkung/Gemeinde: 
            <?php 
		  $this->GemkgFormObj->outputHTML();
		  echo $this->GemkgFormObj->html;
		  ?>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td rowspan="20">&nbsp; </td>
    <td rowspan="20" colspan="2"> 
      <?php
 				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
    
  <tr> 
    <td colspan="2"><input type="radio" name="art" value="100"<?php if ($this->formvars['art']=='100') { ?> checked<?php } ?>>
      Fortführungsriss&nbsp;(FFR)
    </td>
  </tr>
  <tr> 
    <td colspan="2"><input type="radio" name="art" value="010"<?php if ($this->formvars['art']=='010') { ?> checked<?php } ?>>
      Koordinatenverzeichnis&nbsp;(KVZ)
    </td>
  </tr>
  <tr> 
    <td colspan="2"><input type="radio" name="art" value="001"<?php if ($this->formvars['art']=='001') { ?> checked<?php } ?>>
      Grenzniederschrift&nbsp;(GN)
    </td>
  </tr>
  <tr>
    <td colspan="2"><input type="radio" name="art" value="111"<?php if ($this->formvars['art']=='111') { ?> checked<?php } ?>>
      andere:
      <select name="andere_art" onchange="document.getElementsByName('art')[3].checked=true;">
      	<option value="">-- Auswahl --</option>
      	<? for($i = 0; $i < count($this->dokumentarten['id']); $i++){?>
      	<option <? if($this->formvars['andere_art'] == $this->dokumentarten['id'][$i]){echo 'selected';} ?> value="<? echo $this->dokumentarten['id'][$i]; ?>"><? echo $this->dokumentarten['art'][$i]; ?></option>	
      	<? } ?>
      </select>
    </td>
  </tr>
  <tr> 
    <td colspan="2"><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td width="80px">Blattnummer:</td>
    <td>
  		<input name="Blattnr" type="text" value="<?php echo $this->formvars['Blattnr']; ?>" size="<?php echo BLATTNUMMERMAXLENGTH; ?>" maxlength="<?php echo BLATTNUMMERMAXLENGTH; ?>">
 		</td>
 	</tr>
 	<tr> 
    <td>Fortführung:</td>
    <td>
  		<input name="fortfuehrung" type="text" value="<?php echo $this->formvars['fortfuehrung']; ?>" maxlength="4" size="4">
 		</td>
 	</tr>
 	<tr> 
    <td colspan="2">Bemerkungen:
  		<textarea style="width:85%" name="bemerkungen"><?php echo $this->formvars['bemerkungen']; ?></textarea>
 		</td>
 	</tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td>Datum:</td>
    <td>
		<a href="javascript:;" title=" (TT.MM.JJJJ) " onclick="new CalendarJS().init('datum')"><img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_datum"></a></div>
		<input name="datum" id="datum" type="text" onchange="" value="<?php echo $this->formvars['datum']; ?>" size="10" maxlength="50">
	</td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2">Vermessungsstelle:<br> 
      <?php
              $this->FormObjVermStelle->outputHTML();
              echo $this->FormObjVermStelle->html;
          ?>
    </td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
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
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
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
  	<td>&nbsp;</td> 
    <td><?php if ($this->formvars[NACHWEIS_PRIMARY_ATTRIBUTE]!='') { ?><a href="index.php?go=Nachweisanzeige">&lt;&lt;&nbsp;zur&uuml;ck&nbsp;zum&nbsp;Rechercheergebnis</a><?php } ?></td>
    <td align="right"><input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;</td>
    <td colspan="2" align="center">
    	<table border="0">
        <tr> 
          <td><input type="reset" name="go_plus2" value="Zurücksetzen"></td>
          <td><input type="button" name="senden" value="Senden" onclick="save();"></td>
        </tr>
      </table>
      <input type="hidden" name="id" value="<?php echo $this->formvars['id']; ?>">
      <input type="hidden" name="go" value="Nachweisformular">
      <input type="hidden" name="go_plus" value="">
      <input type="hidden" name="area" value="">
			<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
			<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
			<INPUT TYPE="hidden" NAME="result2" VALUE="">
			<INPUT TYPE="hidden" NAME="check" VALUE="">
    </td>
  </tr>
</table>
