<?php
  # 2007-01-26 pkvvm
  include(LAYOUTPATH.'languages/Kat_bearbeiten_'.$this->user->rolle->language.'.php');
?>
<script type="text/javascript">
Text[3]=["Tipp:","Mit der Kategorienverwaltung lassen sich Kategorien hinzufügen, löschen oder die Zugriffe der Stellen pro Kategorie festlegen. Wenn Sie ein Kategorie löschen, wird auch die Zuordnung der Stellen gelöscht. Um die entsprechenden Notizen mit zu löschen müssen sie das Häckchen neben dem 'Kategorie löschen'-Link setzen."]

function change(){
	document.GUI.go.value = 'Notizenformular';
	document.GUI.go_plus.value = 'KatVerwaltung';
	document.GUI.submit();
}

function send(){
	document.GUI.go.value = 'NotizKategorie';
	document.GUI.go_plus.value = 'aendern';
	document.GUI.submit();
}

function loeschen(){ 
	document.GUI.go.value = 'NotizKategorie';
	document.GUI.go_plus.value = 'loeschen';
	document.GUI.submit();
}

function hinzufuegen(){
    document.GUI.go.value = 'NotizKategorie';
	document.GUI.go_plus.value = 'hinzufuegen';
	document.GUI.submit();
}
</script>
<br><br> 
<strong><font size="+2"><?php echo $strTitle; ?></font></strong>
<br><br>
 <table align="center" border="0" cellspacing="5" cellpadding="0">
  <tr align="center">
    <td align="center"><table width="100%" align="center" border="1" cellpadding="5" cellspacing="0" rules="groups">
	<tr>
		<td align="right">
			<span data-tooltip="Mit der Kategorienverwaltung lassen sich Kategorien hinzufügen, löschen oder die Zugriffe der Stellen pro Kategorie festlegen. Wenn Sie ein Kategorie löschen, wird auch die Zuordnung der Stellen gelöscht. Um die entsprechenden Notizen mit zu löschen müssen sie das Häckchen neben dem 'Kategorie löschen'-Link setzen" style="--left: -400px"></span>
		</td>
	</tr>
	<tr><td><br>
	<table align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
    <td align="center" colspan="8"><b><?php echo $strCategory; ?></b>&nbsp;
      <select name="kategorie_id" onChange="change()">
      	<option value=""><?php echo $this->strPleaseSelect; ?></option>
        <?php for ($k=0 ; $k<count($this->AllKat) ; $k++) { ?>
        <option value="<?php echo $this->AllKat[$k]['id'];?>"<?php if ($this->AllKat[$k]['id']==$this->Kat[0]['id']) {echo ' selected';} ?>><?php echo $this->AllKat[$k]['kategorie'];?></option>
        <?php } ?>
      </select>
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="bottom"><a href="JavaScript:loeschen()" onClick="return confirm('<?php echo $this->strDeleteWarningMessage; ?>'); submit();" title="Hier klicken, wenn Sie die selektierte Kategorie löschen möchten."><font color="#FF0000" size="-1"><?php echo $strDeleteCategory; ?></font></a></td>
    <td valign="bottom">&nbsp;<input name="plus_notiz" type="checkbox" value="1"><?php echo $strIncludeAllNoticeOfCategory; ?></td>
</table>


<br>
<table align="center" border="1" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" rules="groups">
  <tr align="center"> 
    <td align="center"> <table align="center" id="tablemonth" style="display: <?php if ($this->formvars['zeitraum']=='month' OR $this->formvars['zeitraum']==''){ echo 'block';} else { echo 'none';} ?>" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <th > <?php echo $this->strTask; ?> </th>
		  <th > <?php echo $strViewable; ?> </th>
		  <th > <?php echo $strSelectable; ?></th>
		  <th > <?php echo $strEditable; ?> </th>
        </tr>
		<tr>
		  <td colspan="5"><hr></td>
		</tr>
	<?php 
	  for ($i = 0 ; $i < count($this->Stellen['ID']) ; $i++) {
		$stelleID[$i]='0';
		for ($j = 0 ; $j < count($this->Kat2Stelle) ; $j++) {
	      if ($this->Stellen['ID'][$i]==$this->Kat2Stelle[$j]['stelle']) {
     ?>
			<tr	<?php if ($i%2!=0) { echo 'bgcolor="#FFFFFF"';} ?>> 
			  <input type="hidden" name="<?php echo 'checkstelle'.$i;?>" value="<?php echo $this->Stellen['ID'][$i]?>">
			  <td align="center"><font><?php echo $this->Stellen['Bezeichnung'][$i]; ?></font></td>
			  <td align="center"><input type="checkbox" name="<?php echo 'checkstellelesen'.$i;?>" value="1" <?php  if ($this->Kat2Stelle[$j]['lesen']=='t') { ?> checked<?php }?>></td>
			  <td align="center"><input type="checkbox" name="<?php echo 'checkstelleanlegen'.$i;?>" value="1" <?php  if ($this->Kat2Stelle[$j]['anlegen']=='t') { ?> checked<?php }?>></td>
			  <td align="center"><input type="checkbox" name="<?php echo 'checkstelleaendern'.$i;?>" value="1" <?php  if ($this->Kat2Stelle[$j]['aendern']=='t') { ?> checked<?php }?>></td>
			</tr>
	<?php
	 		$stelleID[$i]='1';
		 }
		}
		if ($stelleID[$i]!='1') {
		 ?>
			<tr <?php if ($i%2!=0) { echo 'bgcolor="#FFFFFF"';} ?>> 
			  <input type="hidden" name="<?php echo 'checkstelle'.$i;?>" value="<?php echo $this->Stellen['ID'][$i]?>">
			  <td align="center"><font><?php echo $this->Stellen['Bezeichnung'][$i]; ?></font></td>
			  <td align="center"><input type="checkbox" name="<?php echo 'checkstellelesen'.$i;?>" value="1"></td>
			  <td align="center"><input type="checkbox" name="<?php echo 'checkstelleanlegen'.$i;?>" value="1"></td>
			  <td align="center"><input type="checkbox" name="<?php echo 'checkstelleaendern'.$i;?>" value="1"></td>
			</tr>		 
		 <?php
	   }
	 }
	 $stellenanzahl=$i;
    ?>
      </table> 
      
</table>
<br><br>
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"><tr align="center"><td align="center">
<input type="button" name="sendenbutton" value="<?php echo $this->strSave; ?>" onclick="send();">
</td></tr></table>
<br><br>
 
</td></tr></table>
</td>
  </tr>
  <tr>
    <td><table width="100%" align="center" border="1" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" rules="groups">
  <tr align="center"> 
    <td align="center"> 
	  <table align="center" id="tablemonth" style="display: <?php if ($this->formvars['zeitraum']=='month' OR $this->formvars['zeitraum']==''){ echo 'block';} else { echo 'none';} ?>" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <th> <?php echo $strNewCategory; ?> </th>
          <td> <input name="newKategorie" id="newKategorie" type="text" size="20" value=""> </td>
		  <td> <input type="button" value="<?php echo $strButtonAdd; ?>" onClick="javascript:hinzufuegen()"> </td>
        </tr>
      </table> 
</table>
  </td>
  </tr>
</table>
<input type="hidden" name="go" value="">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="stellenanzahl" value="<?php echo $stellenanzahl; ?>">




	  