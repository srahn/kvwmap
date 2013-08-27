		<td width="280px">
			<table>
  			<tr>
			  	<td colspan="4" width="100%">
			  		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			  			<tr>
			  				<? if($this->stelle->id != '' AND $this->layer[0]['Name'] != ''){ ?>
						  	<td height="50px" valign="top" align="center"><b><span style="font-size:15px"><? echo $this->stelle->Bezeichnung; ?></span></b></td>
						  	<? }elseif($this->layer[0]['Name'] != ''){ ?>
						  	<td height="50px" valign="top" align="center"><b><span style="font-size:15px">Default-Rechte</span></b></td>
						  	<? } ?>
			  			</tr>
			  		</table>
			  	</td>
			  </tr>
			  <tr>
			  	<td colspan="4">
			    	<table align="center" border="0" cellspacing="2" cellpadding="2">
			    		<tr>
						  	<td align="center"><b>Layerzugriffsrechte</b></td>
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
										<b>Attribut</b>
									</td>
									<td>&nbsp;</td>
									<td align="center">
										<b>Privileg</b>
									</td>
									<td>&nbsp;</td>
									<td align="center">
										<b>Tooltip</b>
									</td>
								</tr>
						';
						if($this->stelle->id != '' AND $this->attributes_privileges == NULL){				# zu diesem Layer und Stelle gibt es keinen Eintrag -> alle Attribute sind lesbar
							$noentry = true;
						}
						else{
							$noentry = false;
						}
			    	for($i = 0; $i < count($this->attributes['type']); $i++){
			    		if($this->stelle->id == ''){
			    			$this->attributes_privileges[$this->attributes['name'][$i]] = $this->attributes['privileg'][$i]; 	# die default-Rechte kommen aus layer_attributes
			    			$this->attributes_privileges['tooltip_'.$this->attributes['name'][$i]] = $this->attributes['query_tooltip'][$i]; 	# die default-Rechte kommen aus layer_attributes
			    		}
			    		$attribute_names .= $this->attributes['name'][$i].'|';
							echo '
							<tr>
							  <td align="center">
							  	<input class="input" style="width:100px" type="text" name="attribute_'.$this->attributes['name'][$i].'" value="'.$this->attributes['name'][$i].'" readonly>
							  </td>
							  <td>&nbsp;</td>
							  <td align="center" style="height:21px">
							  	<select class="select" style="width:100px" name="privileg_'.$this->attributes['name'][$i].$this->stelle->id.'">';
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
							  	<input class="input" style="width:100px" type="text" name="" value="alle" readonly>
							  </td>';} echo '
							  <td>&nbsp;</td>
							  <td align="center">
							  	<select class="select" style="width:100px" name="" onchange="set_all(\''.$attribute_names.'\', \''.$this->stelle->id.'\', this.value);"">
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
							if($this->stelle->id != '' AND $this->layer[0]['Name'] != '')echo '<a href="javascript:get_from_default(\''.$attribute_names.'\', '.$this->stelle->id.');">Default-Rechte übernehmen</a>';
							echo '</td>
							</tr>
							<tr>
			 					<td align="center" colspan="5"><input class="button" type="button" onclick="save(\''.$this->stelle->id.'\');" name="speichern" value="speichern">
			 					</td>
			 				</tr>
			 				<tr>
			 					<td>&nbsp;</td>
			 				</tr>
			 				<tr>
			 					<td height="40px" align="center" colspan="5"><b><span style="font-size:15px">'.$this->stelle->Bezeichnung.'</span></b></td>
			 				</tr>';
						}
					} 
						?>
			      </table>
			     </td>
			    </tr>
			   </table>
			  </td>
