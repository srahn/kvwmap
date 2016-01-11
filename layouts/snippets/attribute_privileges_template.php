		<td width="280px">
			<table>
  			<tr>
			  	<td colspan="4" width="100%">
			  		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  			<tr>
			  				<? if($this->stelle->id != '' AND $this->layer[0]['Name'] != ''){ ?>
						  	<td height="50px" valign="top" align="center"><span class="fetter px16"><? echo $this->stelle->Bezeichnung; ?></span></td>
						  	<? }elseif($this->layer[0]['Name'] != ''){ ?>
						  	<td height="50px" valign="top" align="center"><span class="fetter px16">Default-Rechte</span></td>
						  	<? } ?>
			  			</tr>
			  		</table>
			  	</td>
			  </tr>
			  <tr>
			  	<td colspan="4">
			    	<table align="center" border="0" cellspacing="2" cellpadding="2">
			    		<tr>
						  	<td align="center"><span class="fett">Layerzugriffsrechte</span></td>
						  </tr>
						  <tr>
						  	<td>
						  		<select name="privileg<? echo $this->stelle->id; ?>">
						  			<option <? if($this->layer[0]['privileg'] == '0'){echo 'selected';} ?> value="0">lesen und bearbeiten</option>
						  			<option <? if($this->layer[0]['privileg'] == '1'){echo 'selected';} ?> value="1">neue Datensätze erzeugen</option>
						  			<option <? if($this->layer[0]['privileg'] == '2'){echo 'selected';} ?> value="2">Datensätze erzeugen und löschen</option>
						  		</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
			  	<td colspan="4">
			    	<table align="center" border="0" cellspacing="2" cellpadding="2">
			    		<tr>
						  	<td align="center"><span class="fett">Layerexportrechte</span></td>
						  </tr>
						  <tr>
						  	<td>
						  		<select name="export_privileg<? echo $this->stelle->id; ?>">
						  			<option <? if($this->layer[0]['export_privileg'] == '0'){echo 'selected';} ?> value="0">Export nicht erlaubt</option>						  			
										<option <? if($this->layer[0]['export_privileg'] == '2'){echo 'selected';} ?> value="2">nur Sachdaten</option>
										<option <? if($this->layer[0]['export_privileg'] == '1'){echo 'selected';} ?> value="1">Sach- und Geometriedaten</option>
						  		</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			  <tr>
			  	<td>&nbsp;</td>
			  </tr>
			  <tr> 
			    <td colspan="4">
			    	<table align="center" border="0" cellspacing="0" cellpadding="0">
			        <?
					if ($this->layer[0]['Name'] != '' AND count($this->attributes) != 0) {
						echo '
								<tr>
									<td align="center">
										<span class="fett">Attribut</span>
									</td>
									<td>&nbsp;</td>
									<td align="center">
										<span class="fett">Privileg</span>
									</td>
									<td>&nbsp;</td>
									<td align="center">
										<span class="fett">Tooltip</span>
									</td>
								</tr>
						';
						if($this->stelle->id != '' AND $this->attributes_privileges == NULL){				# zu diesem Layer und Stelle gibt es keinen Eintrag -> alle Attribute sind lesbar
							$noentry = true;
						}
						else{
							$noentry = false;
						}
						$attributenames = implode('|', $this->attributes['name']);
			    	for($i = 0; $i < count($this->attributes['type']); $i++){
			    		if($this->stelle->id == ''){
			    			$this->attributes_privileges[$this->attributes['name'][$i]] = $this->attributes['privileg'][$i]; 	# die default-Rechte kommen aus layer_attributes
			    			$this->attributes_privileges['tooltip_'.$this->attributes['name'][$i]] = $this->attributes['query_tooltip'][$i]; 	# die default-Rechte kommen aus layer_attributes
			    		}
							echo '
							<tr>
							  <td align="center">
							  	<input style="width:100px" type="text" name="attribute_'.$this->attributes['name'][$i].'" value="'.$this->attributes['name'][$i].'" readonly>
							  </td>
							  <td>&nbsp;</td>
							  <td align="center" style="height:21px">
							  	<select  style="width:100px" name="privileg_'.$this->attributes['name'][$i].$this->stelle->id.'">';
							  		echo '
							  		<option value="" ';
							  		if($this->attributes_privileges[$this->attributes['name'][$i]] == '' AND !$noentry){echo 'selected';}
							  		echo ' >nicht sichtbar</option>
							  		<option value="0" ';
							  		if($this->attributes_privileges[$this->attributes['name'][$i]] == '0' OR $noentry){echo 'selected';}
							  		echo ' >lesen</option>
							  		<option value="1" ';
							  		if($this->attributes_privileges[$this->attributes['name'][$i]] == 1 AND !$noentry){echo 'selected';}
							  		echo ' >editieren</option>
							  	</select>
							  </td>
							  <td>&nbsp;</td>
							  <td align="center"><input type="checkbox" name="tooltip_'.$this->attributes['name'][$i].$this->stelle->id.'" ';
							  if($this->attributes_privileges['tooltip_'.$this->attributes['name'][$i]] == 1){
							  	echo 'checked';
							  }
								echo ' ></td>
			        </tr>
			        ';
			    	}
			    	echo '
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>';
			    			if($this->formvars['stelle'] != 'a'){
			    				echo '
							  <td align="center">
							  	<input style="width:100px" type="text" name="" value="alle" readonly>
							  </td>';} echo '
							  <td>&nbsp;</td>
							  <td align="center">
							  	<select  style="width:100px" name="" onchange="set_all(\''.$attributenames.'\', \''.$this->stelle->id.'\', this.value);"">
										<option value=""> - Auswahl - </option>
							  		<option value="">nicht sichtbar</option>
							  		<option value="0">lesen</option>
							  		<option value="1">editieren</option>
							  	</select>
							  </td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
			        </tr>
			        ';
						if(count($this->attributes) > 0){
							echo '
							<tr>
								<td colspan="5" height="40px" align="center" valign="middle">';
							if($this->stelle->id != '' AND $this->layer[0]['Name'] != ''){
								echo '<a href="javascript:get_from_default(\''.$attributenames.'\', \''.$this->stelle->id.'\');">Default-Rechte übernehmen</a>
									</td>
								</tr>
								<tr>
									<td align="center" colspan="5"><input class="button" type="button" onclick="save(\''.implode('|', $this->stellen['ID']).'\');" name="speichern" value="speichern">
									</td>
								</tr>';
							}
							elseif(count($this->stellen['ID']) > 0){
								echo '<a href="javascript:get_from_default(\''.$attributenames.'\', \''.implode('|', $this->stellen['ID']).'\');">Default-Rechte allen Stellen zuweisen</a>
									</td>
								</tr>
								<tr>
									<td align="center" colspan="5"><input class="button" type="button" onclick="save(\'\');" name="speichern" value="speichern">
									</td>
								</tr>';
							}
							echo '
			 				<tr>
			 					<td>&nbsp;</td>
			 				</tr>
			 				<tr>
			 					<td height="40px" align="center" colspan="5"><span class="fett"><span style="font-size:15px">'.$this->stelle->Bezeichnung.'</span></span></td>
			 				</tr>';
						}
					} 
						?>
			      </table>
			     </td>
			    </tr>
			   </table>
			  </td>
