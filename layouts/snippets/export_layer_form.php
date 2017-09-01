<script type="text/javascript">
<!--

function setfields(){
	if(document.GUI.mitbildern.checked == true){
		document.GUI.username.disabled = false;
		document.GUI.passwort.disabled = false;
	}
	else{
		document.GUI.username.disabled = true;
		document.GUI.passwort.disabled = true;
	}
}

function einlesen(){
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.go_plus.value = 'einlesen';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'einlesen';
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

-->
</script>

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center"> 
		<td><h2><?php echo $this->titel; ?></h2></td>
	</tr>
		<?
		if($this->synchro->result != NULL){
			echo '<tr>
							<td>Es wurden '.$this->synchro->result['count'].' Datensätze eingelesen.</td>
						</tr>';
			if($this->synchro->result['lock'] == true AND $this->synchro->result['count'] > 0){
				echo '<tr>
							<td>Die Datensätze wurden gelockt.</td>
						</tr>';
			}		
	#		echo '<tr>
		#					<td>Es wurden '.$this->synchro->result['imagecount'].' Bilder kopiert.</td>
	#					</tr>
		#				';
		}
	?>
	<tr>
  	<td>
   		<table>
				<tr>
					<td>
						<div id="map_div" style="border:1px solid #C3C7C3;">
						 <?php include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php');  ?>
						</div>
					</td>
					<td valign="top">
			      <table cellspacing=0 cellpadding=2 border=0 style="border:1px solid #C3C7C3;">
			        <tr align="center">
			          <td>Verfügbare Themen:</td>
			        </tr>
			        <tr align="left">
			          <td>
			          	<div align="center"><input type="button" class="button" name="neuladen_button" onclick="neuLaden();" value="neu Laden"></div>
			          		<br>
			        			<div style="width:230; height:<?php echo $this->map->height-59; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
				          		&nbsp;
				          		<img src="graphics/lock.gif" alt="Datensätze sperren" title="ausgelesene Datensätze werden gesperrt" height="20">
				          		<br>
											<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
				          	<div id="legend_div"><? echo $this->legende; ?></div>
				        	</div>
			          </td>
			      	</tr>
			      </table>
			   	</td>
				</tr>
				<tr>	
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%">
							<tr>	
								<td width="35%">&nbsp;</td>
								<td colspan="2" align="left">
									<!--input type="checkbox" name="neuanlegen">Tabellen neu anlegen&nbsp;&nbsp;&nbsp;&nbsp;-->
									<input type="checkbox" checked="true" name="leeren">Client-Tabellen vorher leeren
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>	
								<td colspan="2" align="left">
									<input type="checkbox" name="mitbildern" onchange="setfields();">Bilder kopieren
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td width="8%">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username:&nbsp;
								</td>
								<td>
									<input disabled="true" type="text" name="username">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Passwort:&nbsp;
								</td>
								<td>
									<input disabled="true" type="password" name="passwort">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>	
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="button" onclick="einlesen()" name="los" value="einlesen"></td> 	
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
<input type="hidden" name="go" value="export_layer">
<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="">
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">
<INPUT TYPE="HIDDEN" NAME="orderby" VALUE="<? echo $this->formvars['orderby']; ?>">
<input type="hidden" name="map_minx" value="<? echo $this->map->extent->minx; ?>">
<input type="hidden" name="map_miny" value="<? echo $this->map->extent->miny; ?>">
<input type="hidden" name="map_pixsize" value="<? echo $this->user->rolle->pixsize; ?>">
<input type="hidden" name="area" value="<?echo $this->formvars['area']?>">
<input type="hidden" name="neuladen" value="">
