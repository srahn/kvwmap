<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>

<script type="text/javascript">
<!--

function updateBezirksauswahl(){
	selectbyString(document.GUI.Bezirk, document.GUI.bezirk.value);
	if(document.GUI.bezirk.value.length == 6){
		document.GUI.submit();
	}
}

function updateBezirksschluessel(){
	document.GUI.bezirk.value = document.GUI.Bezirk.options[document.GUI.Bezirk.selectedIndex].value;
	document.GUI.submit();
}

function backto_namesearch(){
	document.GUI.go.value="Namen_Auswaehlen_Suchen";
	document.GUI.submit();	
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

<h2><?php echo $this->titel; ?></h2>

<?php if ($this->Fehlermeldung!='') {

include(LAYOUTPATH."snippets/Fehlermeldung.php");

}

?><p>

<table border="0" cellpadding="5" cellspacing="2">

  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="right"><strong>Grundbuchbezirksschlüssel:</strong></td>
    <td colspan="3">
    	<input name="bezirk" style="width:230px" type="text" value="<?php echo $this->formvars['bezirk']; ?>" onkeyup="updateBezirksauswahl();" size="25" tabindex="1">
    </td>
  </tr>
  <tr> 
    <td align="right"><strong>Grundbuchbezirk:</strong></td>
    <td colspan="3">
    	<select style="width:230px" class="select" name="Bezirk" onchange="updateBezirksschluessel();">
    		<option value="">--- Auswahl ---</option>
    		<?for($i = 0; $i < count($this->gbliste['schluessel']); $i++){?>
    			<option 
    		<?if($this->formvars['Bezirk'] == $this->gbliste['schluessel'][$i]){?>
    		 selected
    		 <?}?>
    		 value="<? echo $this->gbliste['schluessel'][$i]; ?>"><? echo $this->gbliste['beides'][$i]; ?></option>
    		<? } ?>
    	</select>
    </td>
  </tr>
  <? if($this->formvars['Bezirk'] == ''){ ?>
  <tr>
    <td align="right"><strong>Grundbuchblatt:</strong></td>
    <td colspan="3"><input name="Blatt" style="width:230px" type="text" value="<?php echo $this->formvars['Blatt']; ?>" size="25" tabindex="2"></td>
  </tr>
  <? }else{ ?>
  <tr>
    <td align="right"><strong>Grundbuchblatt:</strong></td>
    <td>
    	<br>ausgewählte:<br>
    	<select size="10" style="width:110px" multiple="true" class="select" name="selectedBlatt">
    		 <?
          for($i=0; $i < count($this->selblattliste); $i++){
          	echo '<option value="'.$this->selblattliste[$i].'">'.$this->selblattliste[$i].'</option>';
          }
          ?>
    	</select>
   	</td>
    <td align="center" valign="middle" width="1">
      <input type="button" name="addPlaces" value="&lt;&lt;" onClick="addOptions(document.GUI.Blatt,document.GUI.selectedBlatt,document.GUI.selBlatt,'value'); blur();">
    	<input type="button" name="substractPlaces" value="&gt;&gt;" onClick="substractOptions(document.GUI.selectedBlatt,document.GUI.selBlatt,'value'); blur();">
    </td>
    <td>
    	<br>vorhandene:<br>
    	<select size="10" style="width:80px" multiple="true" class="select" name="Blatt">
    		<?for($i = 0; $i < count($this->blattliste['blatt']); $i++){?>
    			<option
    			<?if($this->formvars['Blatt'] == $this->blattliste['blatt'][$i]){?>
    		 selected
    		 <?}?> 
    			value="<? echo $this->formvars['Bezirk'].'-'.$this->blattliste['blatt'][$i]; ?>"><? echo $this->blattliste['blatt'][$i]; ?></option>
    		<? } ?>
    	</select>
  	</td>
  </tr>
  <? } ?>
	<tr>
		<td id="import1" colspan="4" align="center"><a href="javascript:showimport();">Import Grundbuchblattliste...</a></td>
	</tr>
	<tr id="import2" style="display:none">
		<td colspan="4" style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3">
			<table>
				<td><input name="importliste" type="file" value="" style="width: 340px" tabindex="2"></td>
				<td><input type="submit" class="button" value="Laden"></td>
			</table>
		</td>
	</tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" align="center"> 

<input type="hidden" name="selBlatt" value="<? echo $this->formvars['selBlatt']; ?>">
<input type="hidden" name="go" value="Grundbuchblatt_Auswaehlen">
<input type="submit" class="button" name="go_plus" value="Suchen" tabindex="6">

   </td>

  </tr><?php 
  $anzNamen=count($this->namen);
  if ($anzNamen>0) {
   ?><?php
  }
  ?>

</table>
<?
 if($this->formvars['namensuche'] == 'true'){
 ?>
 	<input name="name1" type="hidden" value="<? echo $this->formvars['name1']; ?>">
  <input name="name2" type="hidden" value="<? echo $this->formvars['name2']; ?>">
  <input name="name3" type="hidden" value="<? echo $this->formvars['name3']; ?>">
  <input name="name4" type="hidden" value="<? echo $this->formvars['name4']; ?>">
  <input name="bezirk" type="hidden" value="<? echo $this->formvars['bezirk']; ?>">
  <input name="blatt" type="hidden" value="<? echo $this->formvars['blatt']; ?>">
  <input name="GemkgID" type="hidden" value="<? echo $this->formvars['GemkgID']; ?>">
  <input name="offset" type="hidden" value="<? echo $this->formvars['offset']; ?>">
	<input name="order" type="hidden" value="<? echo $this->formvars['order'] ?>">
	<input name="anzahl" type="hidden" value="<?php echo $this->formvars['anzahl']; ?>">
	<br>
  <a href="javascript:backto_namesearch();">zurück zur Namensuche</a>
 <?}?>
