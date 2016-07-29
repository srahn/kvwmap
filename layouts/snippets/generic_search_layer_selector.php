  <table style="margin-top: 12px; margin-bottom: 12px" cellpadding="3">
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5"><? echo $strGroups; ?></td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5">
      <select style="width:250px" size="1"  name="selected_group_id" onchange="document.GUI.selected_layer_id.value='';document.GUI.submit();" <? if(count($this->layergruppen['ID'])==0){ echo 'disabled';}?>>
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
  	</td>
  </tr>
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5"><? echo $strLayers; ?></td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5">
      <select style="width:250px" size="1"  name="selected_layer_id" onchange="document.GUI.searchmask_count.value=0;document.GUI.submit();" <? if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
        <option value="">  -- <? echo $this->strPleaseSelect; ?> --  </option>
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
  	</td>
  </tr>

  <tr>
    <td id="searches1"><? if($this->formvars['selected_layer_id'] != ''){ ?><a href="javascript:showsearches();"><? echo $strSearches; ?></a><? if(count($this->searchset) > 0)echo ' ('.count($this->searchset).')';} ?>&nbsp;</td>
  </tr>
  <tr id="searches2" style="display:none">
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3">
    	<table border="0" cellspacing="0" cellpadding="1">
    		<tr align="center"> 
			    <td colspan="2"  align="right">
			    	<? echo $this->strName; ?>:&nbsp;<input type="text" name="search_name" value="<? echo $this->formvars['searches']; ?>">
			    	<input class="button" type="button" style="width:74px" name="speichern" value="<? echo $this->strSave; ?>" onclick="save_search();">
			    </td>
			  </tr>
    		<tr>
			  	<td align="right"  colspan="2">
			  		<input class="button" type="button" style="width:74px" name="delete" value="<? echo $this->strDelete; ?>" onclick="delete_search();">
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
			  		<input class="button" type="button" style="width:74px" name="laden" value="<? echo $this->strLoad; ?>" onclick="document.GUI.submit();">
			    </td>
			  </tr>
    	</table>
    </td>
  </tr>

  <? if($this->formvars['columnname'] != ''){ ?>
  <tr>
    <td id="map1" <? if($this->formvars['map_flag'] != ''){echo 'style="border-top: 1px solid #C3C7C3;border-left: 1px solid #C3C7C3;border-right: 1px solid #C3C7C3"';} ?>><a href="javascript:showmap();"><? echo $strSpatialFiltering; ?></a>&nbsp;</td>
  </tr>
  <? if($this->formvars['map_flag'] != ''){ ?>
  <tr id="map2"> 
    <td align="right" style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3">
			<input type="checkbox" name="within" value="1" <? if($this->formvars['within'] == 1)echo 'checked'; ?>>
			<? echo $strWithin; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	<? echo $this->strUseGeometryOf; ?>: 
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select>
  		<?
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  <? }} ?>
</table>