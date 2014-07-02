<?php
  include(LAYOUTPATH.'languages/namensuche_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>

<script type="text/javascript">
<!--

	function checkall(name){
		var flurstkennz = "";
		var flurstarray = document.getElementsByName(name);
		if(flurstarray[0].checked){
			check = false;
		}
		else{
			check = true;
		}
		for(i = 0; i < flurstarray.length; i++){
			flurstarray[i].checked = check;
		}
	}

	function changeorder(orderby){
		document.GUI.order.value = orderby;
		document.GUI.go.value = 'Namen_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function nextquery(offset){
		if(offset.value == ''){
			offset.value = 0;
		}
		offset.value = parseInt(offset.value) + parseInt(document.GUI.anzahl.value);
		document.GUI.go.value = 'Namen_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function prevquery(offset){
		if(parseInt(offset.value) < parseInt(document.GUI.anzahl.value)){
			offset.value = 0;
		}
		else{
			offset.value = parseInt(offset.value) - parseInt(document.GUI.anzahl.value);
		}
		document.GUI.go.value = 'Namen_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function save(){
		document.GUI.offset.value = 0;
		document.GUI.go.value = 'Namen_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function send_selected_flurst(url, i, target){
		var semi = false;
		var flurstkennz = "";
		var flurstarray = document.getElementsByName("check_flurstueck_"+i);
		for(i = 0; i < flurstarray.length; i++){
	  	if(flurstarray[i].checked == true){
	  		if(semi == true){
	    		flurstkennz += ';';
	    	}
	    	flurstkennz += flurstarray[i].value;
	    	semi = true;
	    }
	  }
	  if(semi == true){
		  url += '&FlurstKennz='+flurstkennz;
		  if(target == '_blank'){
		  	window.open(url, "kvwmap", "toolbar=yes,status=yes,menubar=yes,width="+self.screen.width+",height="+self.screen.height);
		  }
		  else{
		  	location.href=url;
		 	}
		}
		else{
			alert('Es wurden keine Flurstücke ausgewählt.');
		}
	}

	function send_selected_grundbuecher(url){
		var semi = false;
		var grundbuecher = "";
		url += '&name1='+document.GUI.name1.value;
		url += '&name2='+document.GUI.name2.value;
		url += '&name3='+document.GUI.name3.value;
		url += '&name4='+document.GUI.name4.value;
		url += '&bezirk='+document.GUI.bezirk.value;
		url += '&blatt='+document.GUI.blatt.value;
		url += '&GemkgID='+document.GUI.GemkgID.value;
		url += '&offset='+document.GUI.offset.value;
		url += '&order='+document.GUI.order.value;
		url += '&anzahl='+document.GUI.anzahl.value;
		url += '&namensuche=true';
		var gbarray = document.getElementsByName("check_grundbuch");
		for(i = 0; i < gbarray.length; i++){
	  	if(gbarray[i].checked == true){
	  		if(semi == true){
	    		grundbuecher += ';';
	    	}
	    	grundbuecher += gbarray[i].value;
	    	semi = true;
	    }
	  }
	  if(semi == true){
		  url += '&Grundbuecher='+grundbuecher;
		 	location.href=url;
		}
		else{
			alert('Es wurden keine Grundbuchblätter ausgewählt.');
		}
	}

	function grundbuchsuche(bezirk, blatt){
		document.GUI.selBlatt.value = bezirk+'-'+blatt;
		document.GUI.go.value = 'Grundbuchblatt_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function flurstsuche(bezirk, blatt){
		document.GUI.Grundbuecher.value = bezirk+'-'+blatt;
		document.GUI.go.value = 'Suche_Flurstuecke_zu_Grundbuechern';
		document.GUI.submit();
	}


//-->
</script>

<br><h2><?php echo $strTitle; ?></h2>
<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?><p>
<table width="100%" border="0" cellpadding="0" cellspacing="2">
  <tr>
    <td align="right" width="40%"><span class="fett"><?php echo $strName1.', '.$strName2; ?>:</span>
    </td>
    <td width="60%"><input name="name1" type="text" value="<?php echo $this->formvars['name1']; ?>" size="25" tabindex="1"></td>
  </tr>
  <tr>
    <td width="100%" align="right"><span class="fett"><?php echo $strName3.'/'.$strName4; ?>:</span>
      </td>
    <td><input name="name2" type="text" value="<?php echo $this->formvars['name2']; ?>" size="25" tabindex="2"></td>
  </tr>
  <tr>
    <td align="right"><span class="fett"><?php echo $strName5.' '.$strName6; ?>:</span>
      </td>
    <td><input name="name3" type="text" value="<?php echo $this->formvars['name3']; ?>" size="25"  tabindex="3"></td>
  </tr>
  <tr>
    <td height="28" align="right"><span class="fett"><?php echo $strName7.' '.$strName8; ?>:</span>
      </td>
    <td><input name="name4" type="text" value="<?php echo $this->formvars['name4']; ?>" size="25"  tabindex="4"></td>
  </tr>
  <tr>
    <td height="28" align="right"><span class="fett"><?php echo $strGbbez; ?>:</span></td>
    <td><input name="bezirk" type="text" value="<?php echo $this->formvars['bezirk']; ?>" size="6"  tabindex="5"></td>
  </tr>
  <tr>
    <td height="28" align="right"><span class="fett"><?php echo $strGbbl; ?>:</span></td>
    <td><input name="blatt" type="text" value="<?php echo $this->formvars['blatt']; ?>" size="6"  tabindex="6"></td>
  </tr>
  <tr>
    <td height="28" align="right"><span class="fett"><?php echo $strGemkg; ?>:</span></td>
    <td><?php echo $this->GemkgFormObj->html; ?></td>
  </tr>
  <tr>
    <td height="28" align="right"><span class="fett"><?php echo $strFlur; ?>:</span></td>
    <td><?php echo $this->FlurFormObj->html; ?></td>
  </tr>
  <!--
  <tr>
    <td><span class="fett"><?php echo $strHintCaseSens; ?></span>&nbsp;
    <input name="caseSensitive" type="checkbox" value="1"<?php if ($this->formvars['caseSensitive']) { ?> checked<?php } ?>><tr><td colspan="2"></td>
  <tr><td colspan="2"></tr>//-->
  <tr bgcolor="#FFFFCC">
    <td colspan="2"><em><?php echo $strHintWildcard; ?>.</em></td>
  </tr>
  <tr>
    <td colspan="1"><span class="fett"><?php echo $strShowHits; ?>:</span><input name="anzahl" type="text" value="<?php echo $this->formvars['anzahl']; ?>" size="2" tabindex="5"></td>
    <td colspan="1"><span class="fett"><?php echo $strShowWithFst; ?>:</span><input name="withflurst" type="checkbox" <? if($this->formvars['withflurst'] == 'on'){echo 'checked';} ?>></td>
  </tr>
  <tr>
   <td colspan="3" align="center">
<br>
<input type="hidden" name="go" value="Namen_Auswaehlen">
<input type="submit" onclick="save();" style="width: 0px;height: 0px;border: none">
<input type="button" name="go_plus" onclick="save();" value="<?php echo $strSearch; ?>" tabindex="0"><br>
   </td>
  </tr><?php
  $anzNamen=count($this->namen);
  if ($anzNamen>0) {
   ?>
<tr>
    <td colspan="3" align="center">
	<span class="fett"><br>
	<?php echo $strTotalHits; ?>: <?php echo $this->anzNamenGesamt; ?>
    <br>
    <br>
</span>	<table border="1" cellpadding="3" cellspacing="0">
      <tr bgcolor="<?php echo BG_DEFAULT ?>">
      	<td>&nbsp;</td>
        <td align="center"><span class="fett"><a href="javascript:changeorder('bezirk');"><?php echo $strGbbezShort; ?></a></span></td>
        <td align="center"><span class="fett"><a href="javascript:changeorder('blatt');"><?php echo $strGbblShort; ?></a></span></td>
        <td align="left"><span class="fett"><a href="javascript:changeorder('name1');"><?php echo $strName1Short; ?></a></span></td>
        <td align="left"><span class="fett"><a href="javascript:changeorder('name2');"><?php echo $strName2Short; ?></a></span></td>
        <td align="left" bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett"><a href="javascript:changeorder('name3');"><?php echo $strName3Short; ?></a></span></td>
        <td align="left"><span class="fett"><a href="javascript:changeorder('name4');"><?php echo $strName4Short; ?></a></span></td>
        <td align="center" colspan="2"><span class="fett"><?php echo $strFst; ?></span></td>
        <? if($this->Stelle->isFunctionAllowed('Jagdkataster')){ ?>
        <td align="center"><span class="fett"><?php echo $strHunt; ?></span></td>
        <? } ?>
      </tr>
  <?php
  for ($i=0;$i<count($this->namen);$i++) {
  	
  	$this->namen[$i]['name1'] = str_replace(',,,', '', $this->namen[$i]['name1']);
		$this->namen[$i]['name1'] = str_replace(',,', ',', $this->namen[$i]['name1']);
		if(substr($this->namen[$i]['name1'], strlen($this->namen[$i]['name1'])-1) == ',') {
			$this->namen[$i]['name1'] = substr($this->namen[$i]['name1'], 0, strlen($this->namen[$i]['name1'])-1);
		}
  	
  ?>
      <tr>
      	<td><input type="checkbox" name="check_grundbuch" value="<? echo $this->namen[$i]['bezirk'].'-'.$this->namen[$i]['blatt']; ?>"></td>
        <td align="center"><a href="javascript:grundbuchsuche(<?php echo '\''.$this->namen[$i]['bezirk'].'\',\''.$this->namen[$i]['blatt'].'\''; ?>);"><?php echo $this->namen[$i]['bezirk']; ?></a></td>
        <td align="center"><a href="javascript:grundbuchsuche(<?php echo '\''.$this->namen[$i]['bezirk'].'\',\''.$this->namen[$i]['blatt'].'\''; ?>);"><?php echo $this->namen[$i]['blatt']; ?></a></td>
        <td align="left"><?php echo str_replace(' ','&nbsp;',$this->namen[$i]['name1']); if ($this->namen[$i]['name1']=='') { ?>&nbsp;<?php } ?></td>
        <td align="left"><?php echo str_replace(' ','&nbsp;',$this->namen[$i]['name2']); if ($this->namen[$i]['name2']=='') { ?>&nbsp;<?php } ?></td>
        <td align="left"><?php echo str_replace(' ','&nbsp;',$this->namen[$i]['name3']); if ($this->namen[$i]['name3']=='') { ?>&nbsp;<?php } ?></td>
        <td align="left"><?php echo str_replace(' ','&nbsp;',$this->namen[$i]['name4']); if ($this->namen[$i]['name4']=='') { ?>&nbsp;<?php } ?></td>
        <td align="center"><a href="javascript:flurstsuche('<?php echo $this->namen[$i]['bezirk'].'\',\''.$this->namen[$i]['blatt']; ?>');"><?php echo $strShowFst; ?></a></td>
				<td align="center"><a href="index.php?go=Zeige_Flurstuecke_zu_Grundbuechern&Grundbuecher=<?php echo $this->namen[$i]['bezirk'].'-'.$this->namen[$i]['blatt'];?>"><?php echo $strToMap; ?></a></td>

		<? if($this->Stelle->isFunctionAllowed('Jagdkataster')){ ?>
		<td align="center"><a href="index.php?go=jagdkatastereditor&lfd_nr_name=<?php echo $this->namen[$i]['lfd_nr_name'];
		?>&name1=<?php echo $this->formvars['name1'];
		?>&name2=<?php echo $this->formvars['name2'];
		?>&name3=<?php echo $this->formvars['name3'];
		?>&name4=<?php echo $this->formvars['name4'];
		?>&bezirk=<?php echo $this->formvars['bezirk'];
		?>"><?php echo $strHuntEdit; ?></a></td>
		<? }	?>
      </tr>
    <? if($this->formvars['withflurst'] == 'on'){ ?>
    	<tr>
    		<td colspan="10">

    			<table width="100%" border="0" cellpadding="0" cellspacing="0">
    				<tr>
    					<td></td>
    					<td><span style="font-size:12px;"><span class="fett"><?php echo $strParcelNo; ?></span></span></td>
    					<td><span style="font-size:12px;"><span class="fett"><?php echo $strGemkgName; ?></span></span></td>
    					<td><span style="font-size:12px;"><span class="fett"><?php echo $strAreaALB; ?></span></span></td>
    					<td colspan="2"><span style="font-size:12px;"><span class="fett"><?php echo $strDoPrintoutsALB; ?></span></span></td>
    					<td><span style="font-size:12px;"><span class="fett"><?php echo $strMapSection; ?></span></span></td>
    				</tr>
	    <?	for($j = 0; $j < count($this->namen[$i]['flurstuecke']); $j++){ ?>
			      <tr>
			      	<td>
			      	<? if(count($this->namen[$i]['flurstuecke']) > 1){ ?>
			      		<input type="checkbox" name="check_flurstueck_<? echo $i; ?>" value="<? echo $this->namen[$i]['flurstuecke'][$j]; ?>">
			      	<? }
			      		 else{ ?>
			      		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			      	<? } ?>
			      	</td>
			      	<td><span style="font-size:12px;"><? echo $this->namen[$i]['flurstuecke'][$j]; ?></span></td>
			      	<td><span style="font-size:12px;"><? echo $this->namen[$i]['alb_data'][$j]['gemkgname']; ?></span></td>
			      	<td><span style="font-size:12px;"><? echo $this->namen[$i]['alb_data'][$j]['flaeche']; ?> m²</span></td>
			      	<td>
			      		<? $this->getFunktionen();
				        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
				          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>&formnummer=30&wz=0" target="_blank"><span style="font-size:12px;"><?php echo $strPrintoutALB; ?>&nbsp;30</span></a>
				  <?php }else{ ?>
				  				<a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>&formnummer=30&wz=1" target="_blank"><span style="font-size:12px;"><?php echo $strPrintoutALB; ?>&nbsp;30</span></a>
				  				<? } ?>
				      </td>
				      <td>
				        <?php
				        if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
				          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>&formnummer=35&wz=0" target="_blank"><span style="font-size:12px;"><?php echo $strPrintoutALB; ?>&nbsp;35</span></a>
				     <?php }else{ ?>
				     			<a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>&formnummer=35&wz=1" target="_blank"><span style="font-size:12px;"><?php echo $strPrintoutALB; ?>&nbsp;35</span></a>
				     	<? } ?>
							</td>
							<td><a href="index.php?go=ZoomToFlst&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>"><span style="font-size:12px;"><?php echo $strMapSection; ?></span></a></td>
			      </tr>
	    <?	}
	    		if(count($this->namen[$i]['flurstuecke']) > 1 AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']){ ?>
	    		<tr>
	    			<td colspan="6">&nbsp;&nbsp;<? echo '<a href="javascript:checkall(\'check_flurstueck_'.$i.'\');"><img src="'.GRAPHICSPATH.'pfeil_unten-rechts.gif" width="10" height="20" border="0"></a>'; ?>&nbsp;<?php echo $strSelFst; ?>:
	    			<? if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
			          <a href="javascript:send_selected_flurst('index.php?go=ALB_Anzeige&formnummer=30&wz=0', '<? echo $i; ?>', '_blank');"><?php echo $strPrintoutALB; ?>&nbsp;30</a>
		        <? }
		        if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
			          | <a href="javascript:send_selected_flurst('index.php?go=ALB_Anzeige&formnummer=35&wz=0', '<? echo $i; ?>','_blank');"><?php echo $strPrintoutALB; ?>&nbsp;35</a>
			      <? } ?>
	    			</td>
	    		</tr>
	    		<? } ?>
				  <tr><td><span style="font-size:8px;">&nbsp;</span></td></tr>
					</table>

				</td>
			</tr>
	<?	}
	  }
	  ?>
    </table></td>
  </tr>
  <tr height="20px" valign="bottom">
		<td colspan="2">&nbsp;&nbsp;&nbsp;<? echo '<a href="javascript:checkall(\'check_grundbuch\');">'.$strCheckAll.'</a>'; ?>
		</td>
	</tr>
  <tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;<? echo '<a href="javascript:checkall(\'check_grundbuch\');"><img src="'.GRAPHICSPATH.'pfeil_unten-rechts.gif" width="10" height="20" border="0"></a>'; ?>&nbsp;<?php echo $strSelGbbl; ?>: <a href="javascript:send_selected_grundbuecher('index.php?go=Suche_Flurstuecke_zu_Grundbuechern');"><?php echo $strShowFst; ?></a>&nbsp;|&nbsp;<a href="javascript:send_selected_grundbuecher('index.php?go=Zeige_Flurstuecke_zu_Grundbuechern');"><?php echo $strShowFstInMap; ?></a>
		</td>
	</tr>
  <tr>
  	<td colspan="9" align="center">

  	<?	# Blätterfunktion
	   if($this->formvars['offset'] == ''){
		   $this->formvars['offset'] = 0;
		 }
		 $von = $this->formvars['offset'] + 1;
	   $bis = $this->formvars['offset'] + $this->formvars['anzahl'];
	   if($bis > $this->anzNamenGesamt){
	   	$bis = $this->anzNamenGesamt;
	   }
	   echo'
	   <table width="400" border="0" cellpadding="2" cellspacing="0">
	   	<tr><td colspan="3">&nbsp;</td></tr>
	   	<tr align="center">
	   		<td width="100">
	   		  <table border="0" align="right">
	   		    <tr>
	   		      <td>&nbsp;';
	   		        if($this->formvars['offset'] > 0){
	   		        	echo '<a href="javascript:prevquery(document.GUI.offset);">'.$strBack.'&nbsp;<img src="'.GRAPHICSPATH.'pfeil_links.gif" width="10" height="10" border="0"></a>';
	   		        }
	              echo '
			      </td>
			    </tr>
			  </table>
			</td>
			<td width="150">
			  <span style="color:#32326E;">
			    '.$von.' - '.$bis.' von '.$this->anzNamenGesamt.'
			  </span>
			</td>
	        <td width="100">
	   		  <table border="0" align="left">
	   		    <tr>
	   		      <td>';
	      if($bis < $this->anzNamenGesamt){
	      	echo '<a href="javascript:nextquery(document.GUI.offset);"><img src="'.GRAPHICSPATH.'pfeil_rechts.gif" width="10" height="10" border="0">&nbsp;'.$strNext.'</a>';
	      }
	      echo '&nbsp;
			      </td>
			    </tr>
			  </table>
			</td>
	    </tr>
	   </table>
	   ';
  ?>
  	</td>
  </tr>

  <?php
  }
  ?>

</table>
<input name="namensuche" type="hidden" value="true">
<input name="selBlatt" type="hidden" value="">
<input name="Grundbuecher" type="hidden" value="">
<input name="lfd_nr_name" type="hidden" value="">
<input name="offset" type="hidden" value="<? echo $this->formvars['offset']; ?>">
<input type="hidden" name="order" value="<? echo $this->formvars['order'] ?>">

