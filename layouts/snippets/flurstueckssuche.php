<?php
  include(LAYOUTPATH.'languages/flurstueck_suche_'.$this->user->rolle->language.'.php');
 ?>

<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>

<script type="text/javascript">
<!--

function updateGemarkungsauswahl(){
	document.GUI.gemschl.value = document.GUI.gemschl1.value+document.GUI.gemschl2.value;
	selectbyString(document.GUI.GemkgID, document.GUI.gemschl.value);
	if(document.GUI.gemschl.value.length == 6){
		document.GUI.submit();
	}
}

function showimport(){
	if(document.getElementById('import2').style.display == 'none'){
		document.getElementById('import1').style.borderTop="1px solid #C3C7C3";
		document.getElementById('import1').style.borderLeft="1px solid #C3C7C3";
		document.getElementById('import1').style.borderRight="1px solid #C3C7C3";
		document.getElementById('import2').style.display = '';
	}
	else{
		document.getElementById('import1').style.border="none";
		document.getElementById('import2').style.display = 'none';
	}
}

-->
</script>

<input type="hidden" name="go" value="<? if($this->formvars['ALK_Suche']) echo 'ALK-'; ?>Flurstueck_Auswaehlen">
<br><h2><?php echo $this->titel; ?></h2>
<?php
if ($this->Fehlermeldung!='') {
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?><p>
<table border="0" cellpadding="5" cellspacing="2">
<!--
  <tr>
    <td>&nbsp;</td>
    <td>
      <input type="checkbox" name="searchInExtent" value="1"<?php if ($this->searchInExtent) { ?> checked<?php } ?>>
      nur im aktuellen Kartenfenster
    </td>
  </tr>
//-->
	<tr>
    <td align="right"><span class="fett"><?php echo $strGemkgschl; ?>:&nbsp;</span></td>
    <td colspan="3">
    	<input name="gemschl1" type="text" value="13" style="width:23px" onkeyup="updateGemarkungsauswahl();">
    	<input name="gemschl2" type="text" maxlength="4" value="<? echo substr($this->formvars['GemkgID'], 2, 4); ?>" style="width:46px" onkeyup="updateGemarkungsauswahl();">
    	<input name="gemschl" type="hidden" value="<? echo $this->formvars['GemkgID']; ?>">
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fett"><?php echo $strGemkgGem; ?>:&nbsp;</span></td>
    <td colspan="3"><?php echo $this->FormObject["Gemarkungen"]->html; ?></td>
  </tr>
  <tr>
    <td align="right"><span class="fett"><?php echo $strFlur; ?>:&nbsp;</span></td>
    <td colspan="3"><?php
     echo $this->FormObject["Fluren"]->html;
   ?></td>
  </tr>
  <tr>
    <td align="right"><span class="fett"><?php echo $strFst; ?>:&nbsp;</span></td>
    <td>
    <? if($this->FormObject["selectedFlstNr"]->html != ''){ ?>
    	<?php echo $strSelected; ?>:<br>
    	<?php echo $this->FormObject["selectedFlstNr"]->html; ?>
    </td>
    <td align="center" valign="middle" width="1">
    	<input type="button" name="addPlaces" value="&lt;&lt;" onClick="addOptions(document.GUI.FlstID,document.GUI.selectedFlstID,document.GUI.selFlstID,'value');">
    	<input type="button" name="substractPlaces" value="&gt;&gt;" onClick="substractOptions(document.GUI.selectedFlstID,document.GUI.selFlstID,'value');">
    </td>
    <td align="left" width="46%">
    	<?php echo $strExist; ?>:<br>
    	<? } ?>
    	<?php echo $this->FormObject["FlstNr"]->html; ?>
    </td>
  </tr>
	<tr>
		<td id="import1" colspan="4" align="center"><a href="javascript:showimport();">Import Flurstücksliste...</a></td>
	</tr>
	<tr id="import2" style="display:none">
		<td colspan="4" style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3">
			<table width="100%" cellpadding="0" cellspacing="0">
				<td><input name="importliste" type="file" value="" style="width: 340px" tabindex="2"></td>
				<td><input type="submit" value="Laden"></td>
			</table>
		</td>
	</tr>
  <tr align="center">
    <td align="center" colspan="4">
      <input type="hidden" name="go_plus" id="go_plus" value="">
      <input type="button" name="dummy" value="<?php echo $strSearch; ?>" onclick="submitWithValue('GUI','go_plus','Suchen')">
   </td>
  </tr>
</table>
<input type="hidden" name="selFlstID" value="<? echo $this->formvars['selFlstID']; ?>">
<input name="historical" type="hidden" value="<? echo $this->formvars['historical']; ?>">
<input name="without_temporal_filter" type="hidden" value="<? echo $this->formvars['without_temporal_filter']; ?>">
<input name="ALK_Suche" type="hidden" value="<? echo $this->formvars['ALK_Suche']; ?>">
<input name="go_next" type="hidden" value="<? echo $this->formvars['go_next']; ?>">
