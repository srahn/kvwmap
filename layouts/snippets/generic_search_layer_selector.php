<div class="generic_search generic_search_defaults">

<? if ($this->Stelle->isMenueAllowed('Layer-Suche')) { ?>

	<div id="gsl_formular">
		<div class="gsl_gruppe_waehlen gsl_gruppe_waehlen_name"><?php echo $strGroups; ?></div>
		<div class="gsl_gruppe_waehlen gsl_gruppe_waehlen_select">
			<select size="1"  name="selected_group_id" onchange="document.GUI.selected_layer_id.value='';document.GUI.submit();" <?php if(count($this->layergruppen['ID'])==0){ echo 'disabled';}?>>
				<option value="">  -- <?php echo $this->strPleaseSelect; ?> --  </option>
<?php
				for($i = 0; $i < count($this->layergruppen['ID']); $i++){         
					echo '<option';
					if($this->layergruppen['ID'][$i] == $this->formvars['selected_group_id']){
						echo ' selected';
					}
					echo ' value="'.$this->layergruppen['ID'][$i].'">'.$this->layergruppen['Bezeichnung'][$i].'</option>';
				}
?>
			</select>
		</div>
	
		<div class="gsl_layer_waehlen gsl_layer_waehlen_name"><?php echo $strLayers; ?></div>
		<div class="gsl_layer_waehlen gsl_layer_waehlen_select">
			<select size="1"  name="selected_layer_id" onchange="document.GUI.searchmask_count.value=0;document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
				<option value="">  -- <?php echo $this->strPleaseSelect; ?> --  </option>
<?php
				for($i = 0; $i < count($this->layerdaten['ID']); $i++){         
					echo '<option';
					if($this->layerdaten['ID'][$i] == value_of($this->formvars, 'selected_layer_id')){
						echo ' selected';
					}
					echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
				}
?>
			</select>		
		</div>
	</div>
<?
}
else {
	echo '<input type="hidden" name="selected_layer_id" value="' . $this->formvars['selected_layer_id'] . '">';
}
if(value_of($this->formvars, 'selected_layer_id') != '') {
?>
	<div id="gsl_suche_speichern">
		<div id="gsl_abfrage_speichern">
			<div><a id="gsl_abfrage_speichern_form_link" onclick="showform('gsl_abfrage_speichern_form');"><?php echo $strAbfrageSpeichern; ?></a></div>
			<div id="gsl_abfrage_speichern_form">
				<span><input class="gsl_abfrage_speichern_form_input" type="text" name="search_name" value="" placeholder="<?php echo $strAbfrageNamePlaceholder; ?>"></span><br>
				<span><input type="button" name="speichern" value="<?php echo $this->strSave; ?>" onclick="save_search();"></span>
			</div>		
		</div>
		<div id="gsl_abfrage_laden" <?php if(empty($this->searchset)){echo 'style="display: none"'; } ?>>
			<div><a id="gsl_abfrage_laden_form_link" onclick="showform('gsl_abfrage_laden_form');"><?php echo $strSearches; ?></a><?php if(@count($this->searchset) > 0)echo ' ('.count($this->searchset).')'; ?></div>
			<div id="gsl_abfrage_laden_form">
				<select name="searches">
					<option value="">  -- <?php echo $this->strPleaseSelect; ?> --  </option>
<?php
						for($i = 0; $i < @count($this->searchset); $i++){
							echo '<option value="'.$this->searchset[$i]['name'].'" ';
							if($this->selected_search[0]['name'] == $this->searchset[$i]['name']){echo 'selected ';}
							echo '>'.$this->searchset[$i]['name'].'</option>';
						}
?>
				</select>
				<input type="button" name="laden" value="<?php echo $this->strLoad; ?>" onclick="document.GUI.submit();">
				<a title="<?php echo $this->strDelete; ?>" onclick="delete_search();"><i class="fa fa-trash" name="delete"></i></a>
			</div>
		</div>			
<?php	
	if(value_of($this->formvars, 'columnname') != '') {
?>
		<div id="gsl_suche_raeumlich">
			<div>
				<a onclick="showmap();">
					<?php echo $strSpatialFiltering; ?>
					<?php 
					if(value_of($this->formvars, 'map_flag') != '') { 
					?>
						<span class="gsl_suche_raeumlich_x">×</span>
						<span class="gsl_suche_raeumlich_tooltip"><?php echo $strSpatialFilteringClose; ?></span>
					<?php					
					}
					?>
				</a>
			</div>
		</div>
<?php
	}
?>
	</div>
<?php	
	if(value_of($this->formvars, 'map_flag') != '') {
?> 
	<div class="gsl_suche_raeumlich_map generic_search_defaults">
		<div id="gsl_suche_raeumlich_params">
			<div class="gsl_suche_raeumlich_param">
				<div><input type="checkbox" name="within" value="1" <?php if($this->formvars['within'] == 1)echo 'checked'; ?>></div>
				<div><?php echo $strWithin; ?></div>
			</div>
			<div class="gsl_suche_raeumlich_param">
				<div><input type="checkbox" name="singlegeom" value="true" <?php if($this->formvars['singlegeom'])echo 'checked="true"'; ?>></div>
				<div><?php echo $strSingleGeoms; ?></div>
			</div>
			<div class="gsl_suche_raeumlich_param">
				<div><?php echo $this->strUseGeometryOf; ?>:</div> 
				<div>
					<select name="geom_from_layer" onchange="geom_from_layer_change();">
<?php
					for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
						echo '<option';
						if($this->formvars['geom_from_layer'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
						echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
					}
?>
					</select>
				</div>
			</div>
		</div>
		<?php include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php') ?>		
	</div>
<?php
	}

}  
?>

</div>	
