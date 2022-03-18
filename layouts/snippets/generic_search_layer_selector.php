<div id="gsl_formular">
	<div id="gsl_gruppe_waehlen">
		<div>
			<div><?php echo $strGroups; ?></div>
			<div>
				<select size="1"  name="selected_group_id" onchange="document.GUI.selected_layer_id.value='';document.GUI.submit();" <? if(count($this->layergruppen['ID'])==0){ echo 'disabled';}?>>
					<option value="">  -- <? echo $this->strPleaseSelect; ?> --  </option>
					<?
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
		</div>
	</div>
	<div id="gsl_layer_waehlen">
		<div>
			<div><?php echo $strLayers; ?></div>
			<div>
				<select size="1"  name="selected_layer_id" onchange="document.GUI.searchmask_count.value=0;document.GUI.submit();" <? if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
					<option value="">  -- <? echo $this->strPleaseSelect; ?> --  </option>
					<?
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
	</div>

<? if(value_of($this->formvars, 'selected_layer_id') != '') {  ?>
	<div id="gsl_suche_speichern">
		<div id="gsl_abfrage_speichern">
			<div><a id="gsl_abfrage_speichern_form_link" onclick="showform('gsl_abfrage_speichern_form');"><?php echo $strAbfrageSpeichern; ?></a></div>
			<div id="gsl_abfrage_speichern_form">
				<span><input class="gsl_abfrage_speichern_form_input" type="text" name="search_name" value="" placeholder="<? echo $strAbfrageNamePlaceholder; ?>"></span><br>
				<span><input type="button" name="speichern" value="<? echo $this->strSave; ?>" onclick="save_search();"></span>
			</div>		
		</div>
		<div id="gsl_abfrage_laden" <? if(empty($this->searchset)){echo 'style="display: none"'; } ?>>
			<div><a id="gsl_abfrage_laden_form_link" onclick="showform('gsl_abfrage_laden_form');"><? echo $strSearches; ?></a><? if(count($this->searchset) > 0)echo ' ('.count($this->searchset).')'; ?></div>
			<div id="gsl_abfrage_laden_form">
			  	<select name="searches">
			  		<option value="">  -- <? echo $this->strPleaseSelect; ?> --  </option>
			  		<?
			  			for($i = 0; $i < count($this->searchset); $i++){
			  				echo '<option value="'.$this->searchset[$i]['name'].'" ';
			  				if($this->selected_search[0]['name'] == $this->searchset[$i]['name']){echo 'selected ';}
			  				echo '>'.$this->searchset[$i]['name'].'</option>';
			  			}
			  		?>
			  	</select>
				<input type="button" name="laden" value="<? echo $this->strLoad; ?>" onclick="document.GUI.submit();">
				<a title="<? echo $this->strDelete; ?>" onclick="delete_search();"><i class="fa fa-trash" name="delete"></i></a>
			</div>
		</div>			
<?	if(value_of($this->formvars, 'columnname') != '') {  ?>
		<div id="gsl_suche_raeumlich">
			<div><a onclick="showmap();"><?php echo $strSpatialFiltering; ?></a></div>
		</div>
<?		if(value_of($this->formvars, 'map_flag') != '') {  ?> 
		<div>
			<div id="gsl_suche_raeumlich_params">
				<div>
					<input type="checkbox" name="within" value="1" <? if($this->formvars['within'] == 1)echo 'checked'; ?>>
					<? echo $strWithin; ?>
				</div>
				<div>
					<input type="checkbox" name="singlegeom" value="true" <? if($this->formvars['singlegeom'])echo 'checked="true"'; ?>>
					<? echo $strSingleGeoms; ?>
				</div>
				<div>
					<? echo $this->strUseGeometryOf; ?>: 
					<select name="geom_from_layer" onchange="geom_from_layer_change();">
					<?
						for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
							echo '<option';
							if($this->formvars['geom_from_layer'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
							echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
						}
					?>
					</select>
				</div>
			</div>
			<? include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php') ?>		
		</div>
<?		}  ?>
<?	}  ?>
	</div>
<? }  ?>
</div>
<? if(count($this->attributes) > 0){ ?>
	<div style="display: flex; justify-content: space-between;">
		<div id="gsl_suchhinweise">
			<span data-tooltip="<? echo $strLikeSearchHint."\n\n".$strOperatorHint; ?>"></span>
		</div>
		<div style="margin-right: 30px">
			<a href="javascript:clear();" title="Suchfelder leeren"><img style="vertical-align:top;" src="<? echo GRAPHICSPATH.'edit-clear.png'; ?>"></a>
		</div>
	</div>
<? }  ?>