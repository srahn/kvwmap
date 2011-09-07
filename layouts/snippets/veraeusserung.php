

<script type="text/javascript">
<!--

function show_details(flurstkennz, lfdnr){
	document.GUI.go.value = 'Layer-Suche_Suchen';
	document.GUI.search.value = 'true';
	document.GUI.selected_layer_id.value = <? echo $this->qlayerset[$i]['Layer_ID'] ?>;
	document.GUI.details.value = 'true';
	document.GUI.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = '';
	if(document.GUI.fromsearch.value == 1){
<?	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['type']); $j++){
  		if($this->qlayerset[0]['attributes']['type'][$j] != 'geometry'){
				echo '
					document.GUI.value_'.$this->qlayerset[0]['attributes']['name'][$j].'.value = ""; 
					document.GUI.value2_'.$this->qlayerset[0]['attributes']['name'][$j].'.value = ""; 
					document.GUI.operator_'.$this->qlayerset[0]['attributes']['name'][$j].'.value = "="; 
				';
  		}
  	}
?>
	}
	
	document.GUI.value_flurstkennz.value = flurstkennz;
	document.GUI.operator_flurstkennz.value = '=';
	document.GUI.value_lfdnr.value = lfdnr;
	document.GUI.operator_lfdnr.value = '=';
	document.GUI.submit();
}

function go_back(){
	document.GUI.details.value = '';
	if(document.GUI.fromsearch.value == 1){
		document.GUI.go.value = 'Layer-Suche_Suchen';
		document.GUI.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = document.GUI._offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value;
<?	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['type']); $j++){
  		if($this->qlayerset[0]['attributes']['type'][$j] != 'geometry'){
				echo '
					document.GUI.value_'.$this->qlayerset[0]['attributes']['name'][$j].'.value = document.GUI._value_'.$this->qlayerset[0]['attributes']['name'][$j].'.value;
					document.GUI.value2_'.$this->qlayerset[0]['attributes']['name'][$j].'.value = document.GUI._value2_'.$this->qlayerset[0]['attributes']['name'][$j].'.value; 
					document.GUI.operator_'.$this->qlayerset[0]['attributes']['name'][$j].'.value = document.GUI._operator_'.$this->qlayerset[0]['attributes']['name'][$j].'.value; 
				';
  		}
  	}
?>
	}
	else{
		document.GUI.go.value = 'Sachdaten';
	}
	document.GUI.submit();
}

//-->
</script>

<?php
  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
  	
  	if($this->formvars['details'] == true){
			if(GLEVIEW == '2'){
	    	include(SNIPPETS.'generic_layer_editor_2.php');			# Attribute zeilenweise
	   	}
	   	else{
	   		include(SNIPPETS.'generic_layer_editor.php');				# Attribute spaltenweise
	   	}
	   	echo'
	   		<table width="100%" border="0" cellpadding="2" cellspacing="0">
					<tr align="center"> 
				  	<td><input type="button" class="button" name="back" value="zurück" onclick="go_back();"></td>
				  </tr>
				</table><br>
			';
			
  		echo '<input name="qLayer'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['qLayer'.$this->qlayerset[$i]['Layer_ID']].'">';
  		echo '<input name="_offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['_offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
  		echo '<input type="hidden" name="fromsearch" value="'.$this->formvars['fromsearch'].'">';
  		
  		for($j = 0; $j < count($this->qlayerset[$i]['attributes']['type']); $j++){
	  		if($this->qlayerset[0]['attributes']['type'][$j] != 'geometry'){
					echo '
						<input name="_value_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['_value_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="_value2_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['_value2_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="_operator_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['_operator_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
					';
	  		}
	  	}
			
		}
		else{
			echo '<input name="_offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
			for($j = 0; $j < count($this->qlayerset[$i]['attributes']['type']); $j++){
	  		if($this->qlayerset[0]['attributes']['type'][$j] != 'geometry'){
					echo '
						<input name="_value_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['value_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="_value2_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['value2_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="_operator_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['operator_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
					';
	  		}
	  	}
			
    ?>
  <input type="hidden" name="fromsearch" value="<? echo $this->search; ?>">
	<h2>Veräußerung</h2><br>	   
	<table border="0" cellspacing="2" cellpadding="2">
	  <tr>
	  	<td><b>Flurstück</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Lfdnr</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Bereich</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>verkaufte&nbsp;Fläche</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Verkaufspreis</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Vertrag</b></td>
	  </tr>
	  <tr>
	  	<td><b>Lage</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Projekt</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>ALB-Fläche</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Gesamtpreis</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Urkunde</b></td>
	  </tr>
	  <tr>
	  	<td><b>1. Käufer</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Preis/m<sub>2</sub></b></td>
	  	<td><b>&nbsp;</b></td>
	  	<td><b>Grundbuchstelle</b></td>
	  </tr>
	  <?php
		for ($j=0;$j<$anzObj;$j++) {
			
			if($this->qlayerset[$i]['shape'][$j]['bereich']){
				$sql = "SELECT bereich_txt from lie_v_bereiche where bereich = '".$this->qlayerset[$i]['shape'][$j]['bereich']."'";
				$ret1 = $this->pgdatabase->execSQL($sql, 4, 0);
		    if($ret1[0]==0){
		      $bereich_txt = pg_fetch_row($ret1[1]);
		    }
			}
			else{
				$bereich_txt = '';
			}
			
			if($this->qlayerset[$i]['shape'][$j]['projekt']){
				$sql = "SELECT projekt_txt from lie_v_projekte where projekt = '".$this->qlayerset[$i]['shape'][$j]['projekt']."'";
				$ret1 = $this->pgdatabase->execSQL($sql, 4, 0);
		    if($ret1[0]==0){
		      $projekt_txt = pg_fetch_row($ret1[1]);
		    }
			}
			else{
				$projekt_txt = '';
			}
			
			if($this->qlayerset[$i]['shape'][$j]['liegenschaftsart']){
				$sql = "SELECT lie_art_txt from lie_v_lie_arten where lie_art = '".$this->qlayerset[$i]['shape'][$j]['liegenschaftsart']."'";
				$ret1 = $this->pgdatabase->execSQL($sql, 4, 0);
		    if($ret1[0]==0){
		      $art_txt = pg_fetch_row($ret1[1]);
		    }
			}
			else{
				$art_txt = '';
			}
			
			if($this->qlayerset[$i]['shape'][$j]['erb_form']){
				$sql = "SELECT erb_form_txt from lie_v_erb_formen where erb_form = '".$this->qlayerset[$i]['shape'][$j]['erb_form']."'";
				$ret1 = $this->pgdatabase->execSQL($sql, 4, 0);
		    if($ret1[0]==0){
		      $form_txt = pg_fetch_row($ret1[1]);
		    }
			}
			else{
				$form_txt = '';
			}
					
		?>
		<tr>
	  	<td colspan="11"><hr></td>
	  </tr> 	
		<tr>
			<td><?php echo $this->qlayerset[$i]['shape'][$j]['flurstkennz']; ?></td>
			<td><b>&nbsp;</b></td>
			<td><?php echo $this->qlayerset[$i]['shape'][$j]['lfdnr']; ?></td>
			<td><b>&nbsp;</b></td>
			<td><?php echo $bereich_txt[0]; ?></td>
			<td><b>&nbsp;</b></td>
			<td><?php echo $this->qlayerset[$i]['shape'][$j]['verkaufsflaeche']; ?></td>
			<td><b>&nbsp;</b></td>
			<td><?php echo $this->qlayerset[$i]['shape'][$j]['kaufpreis']; ?></td>
			<td><b>&nbsp;</b></td>
			<td><?php echo $this->qlayerset[$i]['shape'][$j]['vertrag_az']; ?></td>
		</tr>
		<tr>
			<td><?php echo $this->qlayerset[$i]['shape'][$j]['lagebezeichnung']; ?></td>
	  	<td>&nbsp;</td>
	  	<td>&nbsp;</td>
	  	<td>&nbsp;</td>
	  	<td><?php echo $projekt_txt[0]; ?></td>
	  	<td>&nbsp;</td>
	  	<td><?php echo $this->qlayerset[$i]['shape'][$j]['flaeche']; ?></td>
	  	<td>&nbsp;</td>
	  	<td><?php echo $this->qlayerset[$i]['shape'][$j]['preis_ges']; ?></td>
	  	<td>&nbsp;</td>
	  	<td><?php echo $this->qlayerset[$i]['shape'][$j]['urkunde_az']; ?></td>
	  </tr>
		<tr>
			<td colspan="5"><?php echo $this->qlayerset[$i]['shape'][$j]['kaeufer']; ?></td>
			<td>&nbsp;</td>
	  	<td><a href="javascript:show_details('<?php echo $this->qlayerset[$i]['shape'][$j]['flurstkennz']; ?>', '<?php echo $this->qlayerset[$i]['shape'][$j]['lfdnr']; ?>');">Details</a></td>
	  	<td>&nbsp;</td>
	  	<td><?php echo $this->qlayerset[$i]['shape'][$j]['preis_qm_m_nk']; ?></td>
	  	<td>&nbsp;</td>
	  	<td><?php echo $this->qlayerset[$i]['shape'][$j]['grundbuchst']; ?></td>
	  </tr>
	  <?php
		  }
		  ?>
	</table>
	<br/>
<?php
		}    
  }
  else {
  	?><br><strong><font color="#FF0000">
	  Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
	  Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
	  <?php  	
  }
?>
<input type="hidden" name="details" value="<? echo $this->formvars['details']; ?>">
<input type="hidden" name="search" value="">
<input type="hidden" name="selected_layer_id" value="<? echo $this->qlayerset[$i]['Layer_ID'] ?>">
<? if($this->search != true){ ?>
	<input type="hidden" name="value_flurstkennz" value="">
	<input type="hidden" name="operator_flurstkennz" value="">
	<input type="hidden" name="value_lfdnr" value="">
	<input type="hidden" name="operator_lfdnr" value="">
<? } ?>



