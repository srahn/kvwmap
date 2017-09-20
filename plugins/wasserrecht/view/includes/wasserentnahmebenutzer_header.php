<?php 
$wasserrechtlicheZulassung = new WasserrechtlicheZulassungen($this);
$wrzProGueltigkeitsJahr = $wasserrechtlicheZulassung->find_gueltigkeitsjahre($this);
$gueltigkeitsjahre = $wrzProGueltigkeitsJahr->gueltigkeitsJahre;

$getYear = !empty(htmlspecialchars($_REQUEST['erhebungsjahr'])) ? htmlspecialchars($_REQUEST['erhebungsjahr']) : $gueltigkeitsjahre[0];

//Get Behörde
$getBehoerde = !empty(htmlspecialchars($_REQUEST['behoerde'])) ? htmlspecialchars($_REQUEST['behoerde']) : null;
if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen)
    && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->behoerde) && empty($getBehoerde))
{
    $getBehoerde = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->behoerde->getId();
}

//Get Adressat
$getAdressat = !empty(htmlspecialchars($_REQUEST['adressat'])) ? htmlspecialchars($_REQUEST['adressat']) : null;
$selectedAdressat = null;
if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen)
    && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->adressat) && empty($getAdressat))
{
    $getAdressat = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->adressat->getId();
    $selectedAdressat = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->adressat;
}

// echo "adressat: " . $getAdressat;
?>


<div class="wasserrecht_display_table">
		
		<div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Erhebungsjahr:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
                	<select name="erhebungsjahr" onchange="setNewUrlParameter(this,'year')">
    					<?php
                            if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->gueltigkeitsJahre))
                            {
                                if(!empty($gueltigkeitsjahre) && count($gueltigkeitsjahre) > 0)
                                {
                                    foreach($gueltigkeitsjahre AS $gueltigkeitsjahr)
                                    {
                                        echo '<option value='. $gueltigkeitsjahr . ' ' . ($gueltigkeitsjahr === $getYear ? "selected" : "") . '>' . $gueltigkeitsjahr . "</option>";
                                    }
                                    
                                    $nextyear = date('Y', strtotime('+1 year'));
                                    echo '<option value='. $nextyear . ' ' . ($nextyear === $getYear ? "selected" : "") . '>' . $nextyear . "</option>";
                                }
                                else
                                {
                                    echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
                                }
                            }
                            else
                            {
                                echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
                            }
    					?>
    				</select>
                </div>
            </div>
            
        <div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">
                	<?php 
    			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Behoerde'] . '&value_id=' . $getBehoerde . '&operator_id==">Behörde: </a>';
    			     ?>
                </div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
                	<select name="behoerde" onchange="setNewUrlParameter(this,'behoerde')">
        				<?php
            				if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
            				{
            				    $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
            				    
            				    $behoerdeArray = array();
            				    
            				    //var_dump($wasserrechtlicheZulassungen);
            				    foreach($wasserrechtlicheZulassungen AS $wrz)
            				    {
            				        if(!empty($wrz) && $getYear === $wrz->gueltigkeitsJahr)
            				        {
            				            if(!empty($wrz->behoerde))
            				            {
            				                if(!in_array($wrz->behoerde->toString(), $behoerdeArray))
            				                {
            				                    $behoerdeArray[]=$wrz->behoerde->toString();
            				                    
            				                    echo '<option value='. $wrz->behoerde->getId() . ' ' . ($wrz->behoerde->getId() === $getBehoerde ? "selected" : "") . '>' . $wrz->behoerde->getName() . "</option>";
            				                }
            				            }
            				        }
            				        else
            				        {
            				            echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
            				            break;
            				        }
            				    }
            				    
            				}
        				?>
    				</select>
         		</div>
         </div>
         
        <div class="wasserrecht_display_table_row">
			<div class="wasserrecht_display_table_row_spacer"></div>
			<div class="wasserrecht_display_table_cell_spacer"></div>
			<div class="wasserrecht_display_table_row_spacer"></div>
        </div>
        
        <div class="wasserrecht_display_table_row">
        	<div class="wasserrecht_display_table_cell_caption">
        		<?php 
    			    echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Personen'] . '&value_personen_id=' . $getAdressat . '&operator_personen_id==">Adressat:</a>';
                ?>
        	</div>
        	<div class="wasserrecht_display_table_cell_spacer"></div>
        	<div class="wasserrecht_display_table_cell">
        		<?php
        				
        				if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
        				{
        				    $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
        				    
        				    $adressatArray = array();
        				    
        				    //var_dump($wasserrechtlicheZulassungen);
        				    foreach($wasserrechtlicheZulassungen AS $wrz)
        				    {
        				        if(!empty($wrz) && $getYear === $wrz->gueltigkeitsJahr)
        				        {
        				            if(!empty($wrz->adressat))
        				            {
        				                if(!in_array($wrz->adressat->toString(), $adressatArray))
        				                {
        				                    $adressatArray[]=$wrz->adressat->toString();
        				                    
//         				                    echo '<option value='. $wrz->adressat->getId() . ' ' . ($wrz->adressat->getId() === $getAdressat ? "selected" : "") . '>' . $wrz->adressat->getName() . "</option>";
        				                    
        				                    if($wrz->adressat->getId() === $getAdressat)
        				                    {
        				                        $selectedAdressat = $wrz->adressat;
        				                    }
        				                }
        				            }
        				        }
//         				        else
//         				        {
//         				            echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
//         				            break;
//         				        }
        				    }
        				    
        				}
        				
        			?>
				<input autocomplete="off" title="Adressat"
					onkeydown="if(this.backup_value==undefined){this.backup_value=this.value; document.getElementById('25_personen_id_0').backup_value=document.getElementById('25_personen_id_0').value;}"
					onkeyup="autocomplete1('25', 'personen_id', '25_personen_id_0', this.value);"
					onchange="if(document.getElementById('suggests_25_personen_id_0').style.display=='block'){this.value=this.backup_value; document.getElementById('25_personen_id_0').value=document.getElementById('25_personen_id_0').backup_value;setTimeout(function(){document.getElementById('suggests_25_personen_id_0').style.display = 'none';}, 500);}"
					id="output_25_personen_id_0" value="<?php echo !empty($selectedAdressat) && !empty($selectedAdressat->getName()) ? $selectedAdressat->getName() : '' ?>" type="text" /> 
				<input onchange="setNewUrlParameter(this,'adressat')" id="25_personen_id_0" type="hidden" />
				<div valign="top" style="height: 0px; position: relative;">
					<div id="suggests_25_personen_id_0" style="z-index: 3000; display: none; left: 0px; top: 0px; width: 400px; vertical-align: top; overflow: hidden; border: 1px solid grey;"></div>
				</div>
			</div>
        </div>

		<div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Straße:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
              		<input type="text" name="strasse" readonly="readonly" value="<?php echo !empty($selectedAdressat) ? $selectedAdressat->getAdresseStrasse() : ""; ?>" />
                </div>
        </div>
        
        <div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Hausnummer:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
              		<input type="text" name="hausnummer" readonly="readonly" value="<?php echo !empty($selectedAdressat) ? $selectedAdressat->getAdresseHausnummer() : ""; ?>" />
                </div>
        </div>
        
        <div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">PLZ:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
              		<input type="text" name="plz" readonly="readonly" value="<?php echo !empty($selectedAdressat) ? $selectedAdressat->getAdressePLZ() : ""; ?>" />
                </div>
        </div>
        
         <div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Ort:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
              		<input type="text" name="ort" readonly="readonly" value="<?php echo !empty($selectedAdressat) ? $selectedAdressat->getAdresseOrt() : ""; ?>" />
                </div>
        </div>
        
    </div>