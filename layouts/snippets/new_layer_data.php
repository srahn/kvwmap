<?php
	include(SNIPPETS.'generic_form_parts.php');
  include(LAYOUTPATH.'languages/new_layer_data_'.$this->user->rolle->language.'.php');
	
	include(SNIPPETS.'sachdatenanzeige_functions.php'); 
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<? if($this->user->rolle->querymode == 1){ ?>
	<script type="text/javascript">
		if(document.getElementById('overlayfooter') != undefined)document.getElementById('overlayfooter').style.display = 'none';
		if(document.getElementById('savebutton') != undefined)document.getElementById('savebutton').style.display = 'none';
	</script>
<? } ?>

<style>
#nds_titel {
	font-family: SourceSansPro3;
	font-size: 20px;
	margin-bottom: 0px;
	margin-top: 10px;
}
#nds_titel p {
	margin: 0px 0px -32px 0px;
}
#nds_formular {
	margin: 40px 0px 20px 0px;
	padding-left: 20px;
}
.nds_select  {
	display: flex;
	width: 500px;
	margin: 0px 0px 10px 0px;
}
.nds_select select {
	height: 25px;
	width: 360px;
	border-radius: 2px;
	border: 1px solid #777;
	padding-left: 5px;
}
.nds_select div:first-child {
	margin-right: 10px;
	align-self: center;
	width: 50px;
}
#nds_submit {
	display:flex;
	flex-flow: row nowrap;
	justify-content: center;
	margin-bottom: 15px;
}
#nds_submit>div {
	display: flex;
	align-items: center;
}
#nds_submit input {
	margin-right: 5px;
}
#nds_submit input[type="checkbox"] {
	margin-top: auto;
	margin-bottom: auto;
	margin-left: 15px;
}
#nds_submit span {
	margin: auto;
	margin-left: 3px;
}

#nds_edit #dataset_operations {
	display: none;
}
</style>


<div id="nds_titel">
	<p><?php echo $strtitle; ?><? if($this->qlayerset[0]['alias'] != '' or $this->qlayerset[0]['Name'] != '')echo ': '; ?><? if($this->qlayerset[0]['alias'] != '')echo $this->qlayerset[0]['alias']; else echo $this->qlayerset[0]['Name']; ?></p>
</div>
<div id="nds_formular" <? if($this->formvars['selected_layer_id'] != '')echo 'style="display:none"'; ?>>
	<div class="nds_select">
		<div><?php echo $strLayer; ?></div>
		<div>
			<select size="1" name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
				<option value=""><?php echo $strPleaseSelect; ?></option>
<?
				for($i = 0; $i < count($this->layerdaten['ID']); $i++){    			
					echo '<option';
					if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
						echo ' selected';
					}
					echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
				}
?>			
			</select>
		</div>
	</div>	
</div>
<div id="nds_edit">
<?
if($this->formvars['selected_layer_id'] AND $this->Fehler == ''){	
	$i = 0;	
	if($this->qlayerset[0]['template']=='generic_layer_editor.php' OR $this->qlayerset[0]['template']==''){
		include(SNIPPETS.'generic_layer_editor_2.php');
	}
	else{
		if(is_file(SNIPPETS.$this->qlayerset[0]['template'])){
			include(SNIPPETS.$this->qlayerset[0]['template']);
		}
		else{
			if(file_exists(PLUGINS.$this->qlayerset[0]['template'])){
				include(PLUGINS.$this->qlayerset[0]['template']);
			}
			else{
				$this->alert = 'Kein Template vorhanden.\nBitte kontaktieren Sie die Administration!';
			}
		}
	}	
?>
<div id="nds_submit">
	<div>
		<? if($this->formvars['subform'] == 'true'){ ?>
		<input type="button" name="abort" value="<? echo $this->strCancel; ?>" onclick="currentform.go.value='get_last_query';overlay_submit(currentform);">
		<? } ?>
		<input type="button" name="go_plus" id="sachdatenanzeige_save_button" value="<? echo $strSave; ?>" onclick="save_new_dataset();">
		<input
			type="checkbox"
			name="weiter_erfassen"
			value="1"<?
			echo (($this->formvars['weiter_erfassen'] == 1 OR $this->user->rolle->immer_weiter_erfassen) ? ' checked="true"' : ''); ?>
		><span><? echo $strCreateAnotherOne; ?></span>
	</div>
</div>
	<? } ?>

<input type="hidden" name="close_window" value="">
<input type="hidden" name="go" value="neuer_Layer_Datensatz">
<input name="form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
<input type="hidden" name="geomtype" value="<? echo $this->geomtype; ?>">
<input type="hidden" name="layer_options_open" value="">

<?
	if ($this->Meldung1!='') {
		$this->alert = 'Fehler bei der Eingabe:\n'.$this->Meldung1;
	}
?>  

