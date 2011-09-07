<?php
  include(LAYOUTPATH.'languages/flurstueck_suche_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
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

-->
</script>

<input type="hidden" name="go" value="Flurstueck_Auswaehlen">
<br><h2><?php echo $this->titel; ?></h2>
<?php
if ($this->Fehlermeldung!='') {
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?><p>
<table border="0" cellspacing="2" cellpadding="0">
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
    <td align="right"><strong><?php echo $strGemkgschl; ?>:&nbsp;</strong></td>
    <td colspan="3">
    	<input name="gemschl1" type="text" value="13" style="width:23px" onkeyup="updateGemarkungsauswahl();">
    	<input name="gemschl2" type="text" value="<? echo substr($this->formvars['GemkgID'], 2, 4); ?>" style="width:46px" onkeyup="updateGemarkungsauswahl();">
    	<input name="gemschl" type="hidden" value="<? echo $this->formvars['GemkgID']; ?>">
    </td>
  </tr>
  <tr>
    <td align="right"><strong><?php echo $strGemkgGem; ?>:&nbsp;</strong></td>
    <td colspan="3"><?php echo $this->FormObject["Gemarkungen"]->html; ?></td>
  </tr>
  <tr align="center">
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td align="right"><strong><?php echo $strFlur; ?>:&nbsp;</strong></td>
    <td colspan="3"><?php
     echo $this->FormObject["Fluren"]->html;
   ?></td>
  </tr>
  <tr align="center">
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td align="right"><strong><?php echo $strFst; ?>:&nbsp;</strong></td>
    <td>
    <? if($this->FormObject["selectedFlstNr"]->html != ''){ ?>
    	<?php echo $strSelected; ?>:<br>
    	<?php echo $this->FormObject["selectedFlstNr"]->html; ?>
    </td>
    <td align="center" valign="middle" width="1">
    	<input type="button" name="addPlaces" value="&lt;&lt;" onClick="addOptions(document.GUI.FlstID,document.GUI.selectedFlstID,document.GUI.selFlstID,'value'); blur();">
    	<input type="button" name="substractPlaces" value="&gt;&gt;" onClick="substractOptions(document.GUI.selectedFlstID,document.GUI.selFlstID,'value'); blur();">
    </td>
    <td align="left" width="46%">
    	<?php echo $strExist; ?>:<br>
    	<? } ?>
    	<?php echo $this->FormObject["FlstNr"]->html; ?>
    </td>
  </tr>
  <tr align="center">
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr align="center">
    <td align="right">&nbsp;
<!--
      <input type="submit" name="aktualisieren" value="<?php echo $strClear; ?>">
-->
    </td>
    <td align="left" colspan="3">
      <input type="hidden" name="go_plus" id="go_plus" value="">
      <input type="button" name="dummy" value="<?php echo $strSearch; ?>" onclick="submitWithValue('GUI','go_plus','Suchen')">
   </td>
  </tr>
</table>
<input type="hidden" name="selFlstID" value="<? echo $this->formvars['selFlstID']; ?>">
<input name="historical" type="hidden" value="<? echo $this->formvars['historical']; ?>">
<input name="ALK_Suche" type="hidden" value="<? echo $this->formvars['ALK_Suche']; ?>">
