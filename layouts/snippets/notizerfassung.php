<?php
  # 2007-01-24 pkvvm
  include(LAYOUTPATH.'languages/notizerfassung_'.rolle::$language.'.php');
?>
<script language="JavaScript">
<!--

function send(){
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == '' && document.GUI.loc_x.value == ''){
			alert('Geben Sie ein Polygon oder eine Textposition an.');
		}
		else{
			document.GUI.newpathwkt.value = SVG.buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.go_plus.value = 'Senden';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
	}
}

function edit() { <?
	if ($this->Stelle->isFunctionAllowed('kategorienverwaltung')) { ?>
		document.GUI.go_plus.value = 'KatVerwaltung';
		document.GUI.submit();<?
	}
	else { ?>
		alert('<?php echo $this->TaskChangeWarning; ?>');<?
	} ?>
}

function neu(){
 	document.GUI.go_plus.value = 'Kat_anlegen';
	document.GUI.submit();
}

//-->
</script>

<?php
	if ($this->Meldung == '') {
	  $bgcolor = BG_FORM;
	}
	else {
	  $bgcolor = BG_FORMFAIL;
		showAlert('Fehler bei der Eingabe:\n' . $this->Meldung);
}
if ($this->Fehlermeldung!='') {
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>
<br> 
<table border="0" cellspacing="0" cellpadding="2">
  <tr align="center"> 
    <td colspan="2"><h2> 
      <?php echo $strTitle; ?>
      <input type="hidden" name="go" value="Notizenformular">
      <input type="hidden" name="oid" value="<?php echo $this->formvars['oid']; ?>">
      </h2></td>
  </tr>
  <tr align="center"> 
    <td rowspan="2" valign="top" align="left"><?php echo $strNotice; ?><br>
      <textarea name="notiz" cols="35" rows="4" wrap="VIRTUAL"><?php echo $this->formvars['notiz']; ?></textarea>
    </td>
    <td valign="top" align="left"><p><?php echo $strCategory; ?>
        <!--<input type="text" name="kategorie" value="<?php echo $this->formvars['kategorie']; ?>"> --> 
        <select name="kategorie_id">
          <?
      	for ($i = 0; $i < count($this->notizen->anlegenKategorien); $i++){
      		if($this->formvars['kategorie_id'] == $this->notizen->anlegenKategorien[$i]['id']){
      			echo '<option selected value = '.$this->notizen->anlegenKategorien[$i]['id'].'>'.$this->notizen->anlegenKategorien[$i]['kategorie'].'</option>';
      		}
      		else{
      			echo '<option value = '.$this->notizen->anlegenKategorien[$i]['id'].'>'.$this->notizen->anlegenKategorien[$i]['kategorie'].'</option>';
      		}
      	}
      	if($this->notizen->notizKategorie){
      		echo '<option selected value = '.$this->notizen->notizKategorie[0]['id'].'>'.$this->notizen->notizKategorie[0]['kategorie'].'</option>';
      	}
      	?>
        </select>&nbsp;<a href="JavaScript:edit()" title="Auswahl von Kategorien ändern"><?php echo $this->strChange; ?></a><br>
      <br>
	  </p>
      </td>
  </tr>
  <tr align="center"> 
    <td valign="top" align="left"><?php echo $strName; ?><br>
      <input type="text" name="person" value="<?php echo $this->formvars['person']; ?>">
    </td>
  </tr>
  <tr valign="top"> 
    <td align="center" colspan="2"> 
      <?php
	  # Wenn ein Polygon übergeben wird, wird es in SVG mit dargestellt.
      include(LAYOUTPATH.'snippets/SVG_polygon_xor_point.php');
    ?>
    </td>
  </tr>
  <tr align="center"> 
    <td colspan="2">
    	<input type="hidden" name="always_draw" value="<? echo $always_draw; ?>"> 
    	<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="">
			<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="">
			<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">
			<INPUT TYPE="HIDDEN" NAME="orderby" VALUE="<? echo $this->formvars['orderby']; ?>">
      <input type="reset" name="reset" value="<?php echo $this->strButtonBack; ?>">
      <input type="hidden" name="go_plus" value="">
      <input type="button" name="sendenbutton" value="<?php echo $this->strSend; ?>" onclick="send();">
    </td>
  </tr>
</table>
