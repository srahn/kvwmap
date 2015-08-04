
<script language="JavaScript">
<!--

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function send(){
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			check = confirm('Sie haben kein Polygon angelegt. Trotzdem speichern?');
  		if(check == true){
  			document.GUI.oid.value = document.GUI.oid_save.value;
				document.GUI.go_plus.value = 'Senden';
				document.GUI.submit();
  		}
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.oid.value = document.GUI.oid_save.value;
			document.GUI.go_plus.value = 'Senden';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.oid.value = document.GUI.oid_save.value;
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

function update_form(art){
	if(art == 'jbe' || art == 'jbf' || art == 'jbe' || art == 'agf' || art == 'atf' || art == 'atv' || art == 'apf'){
		document.GUI.nummer.value = '';
		document.getElementById('lfdnr').style.display = 'none';
		document.getElementById('dummy').style.display = 'none';
		document.getElementById('zuordnung').style.display = '';
		document.getElementById('status').style.display = '';
		document.getElementById('verzicht').style.display = 'none';
	}
	else{
		if(art == 'ejb' || art == 'ajb'){
			document.getElementById('verzicht').style.display = '';
			document.getElementById('dummy').style.display = 'none';	
		}
		else{
			document.getElementById('verzicht').style.display = 'none';
			document.getElementById('dummy').style.display = '';
		}
		document.GUI.jb_zuordnung.value = '';
		document.GUI.status.value = 0;
		document.getElementById('zuordnung').style.display = 'none';
		document.getElementById('status').style.display = 'none';
		document.getElementById('lfdnr').style.display = '';
	}
}

//-->
</script>

<?php
	if ($this->Meldung=='') {
	  $bgcolor=BG_FORM;
	}
	else {
	  $bgcolor=BG_FORMFAIL;
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
	}
?>

<table style="border: 1px solid; border-color: #eeeeee; border-left: none; border-right: none" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr>
    <td> <div align="center"></div></td>
    <td colspan="3"><div align="center"><h2><?php echo $this->titel; ?></h2>
      </div></td>
  </tr>
  <tr>
    <td rowspan="10">&nbsp;</td>
    <td colspan="2" rowspan="10">
      <?php
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  <tr>
    <td><hr align="center" noshade></td>
  </tr>
  <tr>
  	<td>Fläche:<br><input size="12" type="text" name="area" value="">&nbsp;ha</SUP></td>
  </tr>
  <tr>
  	<td>Name:<br><input type="text" name="name" value="<? echo $this->jagdbezirk['name'] ? $this->jagdbezirk['name']: $this->formvars['name']; ?>"></td>
  </tr>
  <tr>
  	<td>Art:<br>
  		<select onchange="update_form(this.value);" name="art">
  			<option <? if($this->jagdbezirk['art'] == 'ejb' OR $this->formvars['art'] == 'ejb'){echo 'selected';} ?> value="ejb">Eigenjagdbezirk</option>
  			<option <? if($this->jagdbezirk['art'] == 'ajb' OR $this->formvars['art'] == 'ajb'){echo 'selected';} ?> value="ajb">Abgerundeter Eigenjagdbezirk</option>
  			<option <? if($this->jagdbezirk['art'] == 'gjb' OR $this->formvars['art'] == 'gjb'){echo 'selected';} ?> value="gjb">Gemeinschaftlicher Jagdbezirk</option>
  			<option <? if($this->jagdbezirk['art'] == 'tjb' OR $this->formvars['art'] == 'tjb'){echo 'selected';} ?> value="tjb">Teiljagdbezirk</option>
  			<option <? if($this->jagdbezirk['art'] == 'sf' OR $this->formvars['art'] == 'sf'){echo 'selected';} ?> value="sf">Sonderfläche</option>
  			<option <? if($this->jagdbezirk['art'] == 'jbe' OR $this->formvars['art'] == 'jbe'){echo 'selected';} ?> value="jbe">Enklave</option>
  			<option <? if($this->jagdbezirk['art'] == 'jbf' OR $this->formvars['art'] == 'jbf'){echo 'selected';} ?> value="jbf">Jagdbezirksfreie Fläche</option>
  			<option <? if($this->jagdbezirk['art'] == 'agf' OR $this->formvars['art'] == 'agf'){echo 'selected';} ?> value="agf">Angliederungsfläche</option>
  			<option <? if($this->jagdbezirk['art'] == 'atf' OR $this->formvars['art'] == 'atf'){echo 'selected';} ?> value="atf">Abtrennungsfläche</option>
  			<option <? if($this->jagdbezirk['art'] == 'atv' OR $this->formvars['art'] == 'atv'){echo 'selected';} ?> value="atv">Abtrennungsfläche durch Verzicht</option>
  			<option <? if($this->jagdbezirk['art'] == 'apf' OR $this->formvars['art'] == 'apf'){echo 'selected';} ?> value="apf">Anpachtfläche</option>
  		</select>
  	</td>
  </tr>
  <tr id="lfdnr" width="100%">
  	<td>lfd.-Nummer:<br><input type="text" name="nummer" value="<? echo $this->jagdbezirk['id'] ? $this->jagdbezirk['id']: $this->formvars['nummer']; ?>"></td>
  </tr>
  <tr id="dummy">
  	<td>&nbsp;</td>
  </tr>
  <tr id="zuordnung" width="100%">
    <td>Zuordnung:<br>
    	<input type="text" name="jb_zuordnung" value="<? echo $this->jagdbezirk['jb_zuordnung'] ? $this->jagdbezirk['jb_zuordnung']: $this->formvars['jb_zuordnung']; ?>">
    </td>
  </tr>
  <tr id="status" width="100%">
    <td>Status<br>
    	<select name="status">
    		<option value="0" <? if($this->jagdbezirk['status'] == 'f' OR $this->formvars['status'] == 'f'){echo 'selected="true"';} ?>>aktuell</option>
    		<option value="1" <? if($this->jagdbezirk['status'] == 't' OR $this->formvars['status'] == 't'){echo 'selected="true"';} ?>>historisch</option>
    	</select>
    </td>
  </tr>
  <tr id="verzicht" width="100%">
    <td>Verzicht gem. §3<br>
    	<select name="verzicht">
    		<option value="0" <? if($this->jagdbezirk['verzicht'] == 'f' OR $this->formvars['verzicht'] == 'f'){echo 'selected="true"';} ?>>nein</option>
    		<option value="1" <? if($this->jagdbezirk['verzicht'] == 't' OR $this->formvars['verzicht'] == 't'){echo 'selected="true"';} ?>>ja</option>
    	</select>
    </td>
  </tr>
  <tr>
    <td><hr align="center" noshade></td>
  </tr>
  <tr>
  	<td>Geometrie Übernehmen von:<br>
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
    <td width="100%" height="50" align="center" valign="bottom"><input type="button" name="senden" value="Senden" onclick="send();"></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td>&nbsp;</td>
  	<td align="right">
  		<input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;
  		<input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang
  	</td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="">
<INPUT TYPE="HIDDEN" NAME="oid_save" VALUE="<? echo $this->formvars['oid'] ? $this->formvars['oid']: $this->formvars['oid_save']; ?>">
<INPUT TYPE="HIDDEN" NAME="areaunit" VALUE="hektar">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>">
<INPUT TYPE="HIDDEN" NAME="go" VALUE="jagdkatastereditor" >
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
<script language="JavaScript">

	update_form(document.GUI.art.value);

</script>
