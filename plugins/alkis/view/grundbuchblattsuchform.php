<?php
  include(LAYOUTPATH.'languages/grundbuchblatt_suche_'.$this->user->rolle->language.'.php');
 ?>

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
	if(document.getElementById('import2').style.visibility == 'visible'){
		document.getElementById('import2').style.visibility = 'hidden';
	}
	else{
		document.getElementById('import2').style.visibility = 'visible';
	}
}
document.onclick =  function(e){
	if(e.target.id != 'importlink' && e.target.closest('div').id != 'import2'){
		document.getElementById('import2').style.visibility = 'hidden';
	}
};
-->
</script>
<style>
#import1 {
	text-align: left;
	margin: 0 0 10px 0;
	padding-left: 20px;
}
#import2 {
	visibility: hidden;
	position: relative;
	margin: 0 0 10px 20px;
	width: 500px;
	height: 40px;
	text-align: left;
	border: 1px solid #aaaaaa;
	padding: 5px;
	background-color: #E6E6E6;
	box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.3);
	z-index: 1;
}
#import2 input[type="submit"] {
	float: right;
	right: 5px;
	transition: unset;
}
#import2 input {
	margin: 0.5em;
}
#import1 {
	margin: 40px 0px 20px 0px;
	padding-left: 20px;
}
#form_formular-main select, #form_formular-main input[name="Blatt"] {
	width: 260px;
}
.form_formular-input-selector {
	width:100%;
	height: 28px;
	margin: 0px 0px 10px 0px;
	display: flex;
	flex-flow: row nowrap;
}
.form_formular-input-selector {
	<? if($this->formvars['Bezirk'] != ''){ ?>margin: 0px 0px 250px 0px;<? } ?>
}
.form_formular-input input[name="bezirk"] {
	width: 60px;
}
.gsf_suche_form span {
	text-align: left;
}
.form_formular-input-selector>div span {
	margin-left: 10px;
	vertical-align: middle;
}
.gsf_suche_form_add {
	width: 80px;
	text-align: center;
}
</style>

<div id="form-titel"><?php echo $strTitle; ?></div>

<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>

<div id="form_formular-main">
	<div class="form_formular-input form_formular-aic">
		<div><?php echo $strGbbzschl; ?></div>
		<div><input name="bezirk" type="text" value="<?php echo $this->formvars['bezirk']; ?>" onkeyup="updateBezirksauswahl();" autofocus onfocus="var temp_value=this.value; this.value=''; this.value=temp_value;" tabindex="1"></div>
	</div>
	<div class="form_formular-input form_formular-aic">
		<div><?php echo $strGbbzname; ?></div>
		<div>
			<select name="Bezirk" onchange="updateBezirksschluessel();" tabindex="2">
				<option value="">--- Auswahl ---</option>
				<?for($i = 0; $i < count($this->gbliste['schluessel']); $i++){?>
					<option 
				<?if($this->formvars['Bezirk'] == $this->gbliste['schluessel'][$i]){?>
				selected
				<?}?>
				value="<? echo $this->gbliste['schluessel'][$i]; ?>"><? echo $this->gbliste['beides'][$i]; ?></option>
				<? } ?>
			</select>
		</div>
	</div>
	<div class="form_formular-input-selector">
		<div>
			<?php echo $strGbblatt; ?>
		</div>
		<? if($this->formvars['Bezirk'] == ''){ ?>
		<div>
			<input name="Blatt" type="text" value="<?php echo $this->formvars['Blatt']; ?>" tabindex="3">
			<?php if($this->FormObject["selectedFlstNr"]->html == '') { ?>
			<span data-tooltip="Eingabe eines Grundbuchblattes ohne Auswahl von Bezirk.
Eingabeformate:
132423-123
132423-0000123"></span>
			<?	} ?>
		</div>
		<? } else { ?>
		<div>
			<table class="gsf_suche_form">
				<tr>
					<td colspan="2"><span><?php echo $strSelected; ?></span></td>
					<td><span><?php echo $strExist; ?></span></td>
				</tr>
				<tr>
					<td>
						<select size="12" style="width: 170px;" multiple="true" name="selectedBlatt">
						<? for($i=0; $i < count_or_0($this->selblattliste); $i++){ ?>
							<option value=" <? echo $this->selblattliste[$i]; ?>"><? echo $this->selblattliste[$i]; ?></option>';
						<? } ?>
						</select>
					</td>
					<td class="gsf_suche_form_add">
						<div><input type="button" name="addPlaces" value="&laquo;" onClick="addOptions(document.GUI.Blatt,document.GUI.selectedBlatt,document.GUI.selBlatt,'value');" tabindex="4"></div>
						<div><input type="button" name="substractPlaces" value="&raquo;" onClick="substractOptions(document.GUI.selectedBlatt,document.GUI.selBlatt,'value');"></div>
					</td>
					<td>
						<select size="12" style="width: 100px;" multiple="true" name="Blatt" tabindex="3">
						<? for($i = 0; $i < count_or_0($this->blattliste['blatt']); $i++){ ?>
							<option <?if($this->formvars['Blatt'] == $this->blattliste['blatt'][$i]){ echo "selected"; } ?> 
								value="<? echo $this->formvars['Bezirk'].'-'.$this->blattliste['blatt'][$i]; ?>"><? echo ltrim($this->blattliste['blatt'][$i], '0'); ?>
							</option>
						<? } ?>
						</select>
					</td>
				</tr>		
			</table>		
		</div>
		<? } ?>
	</div>
</div>
<div id="import1">
	<a href="javascript:showimport();"><? echo $strImportList; ?></a>
</div>
<div id="import2">
	<input name="importliste" type="file" value="" tabindex="2">
	<span data-tooltip="Eingabeformate:
132423-123
132423-0000123
"></span>
	<input type="submit" value="Laden">
</div>
<div>
	<input type="hidden" name="selBlatt" value="<? echo $this->formvars['selBlatt']; ?>">
	<input type="hidden" name="go" value="Grundbuchblatt_Auswaehlen">
	<input type="submit" name="go_plus" value="<?php echo $strSearch; ?>" tabindex="5">
</div>






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
