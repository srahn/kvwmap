<?php
$wasserrechtlicheZulassung = new WasserrechtlicheZulassungen($this);
$wrzProGueltigkeitsJahreArray = $wasserrechtlicheZulassung->find_gueltigkeitsjahre($this);
$gueltigkeitsjahre = null;
$wasserrechtlicheZulassungen = null;
$firstWrZ = null;
if(!empty($wrzProGueltigkeitsJahreArray))
{
//     print_r("wrzProGueltigkeitsJahreArray: " . $wrzProGueltigkeitsJahreArray);
    $gueltigkeitsjahre = $wrzProGueltigkeitsJahreArray->gueltigkeitsJahre;
    $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahreArray->getAllWrZs();
    $firstWrZ = $wrzProGueltigkeitsJahreArray->getFirstWrZ($wasserrechtlicheZulassungen);
}

$getYear = !empty(htmlspecialchars($_REQUEST['erhebungsjahr'])) ? htmlspecialchars($_REQUEST['erhebungsjahr']) : WasserrechtlicheZulassungen::getLastYear();

//Get Behörde
$getBehoerde = !empty(htmlspecialchars($_REQUEST['behoerde'])) ? htmlspecialchars($_REQUEST['behoerde']) : null;
if(!empty($firstWrZ) && !empty($firstWrZ->behoerde) && empty($getBehoerde))
{
    $getBehoerde = $firstWrZ->behoerde->getId();
}
// print_r("getBehoerde: " . $getBehoerde);

//Get Adressat
$getAdressat = !empty(htmlspecialchars($_REQUEST['adressat'])) ? htmlspecialchars($_REQUEST['adressat']) : null;
$selectedAdressat = null;
if(!empty($firstWrZ) && !empty($firstWrZ->adressat) && empty($getAdressat))
{
    $getAdressat = $firstWrZ->adressat->getId();
    $selectedAdressat = $wrzProGueltigkeitsJahreArray->getAdressatInYearAndBehoerde($wasserrechtlicheZulassungen, $getYear, $getBehoerde, $getAdressat);
}
$adressatStable = $_SESSION['getAdressat'] === $getAdressat;
// echo "adressatStable: " . var_export($adressatStable, true);
$_SESSION['getAdressat'] = $getAdressat;
//Das selektierte Adressaten-Objekt finden
if(empty($selectedAdressat) && !empty($getAdressat) && !empty($wrzProGueltigkeitsJahreArray))
{
    $adressat = $wrzProGueltigkeitsJahreArray->getAdressatInYearAndBehoerde($wasserrechtlicheZulassungen, $adressatStable ? $getYear : null, null, $getAdressat);
    if(!empty($adressat) && $adressat->isWrzAdressat())
    {
        $selectedAdressat = $adressat;
    }
}

// echo "adressat: " . $getAdressat;
?>

<div class="wasserrecht_display_table">
		
		<div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Erhebungsjahr:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
                	<select name="erhebungsjahr" onchange="setNewUrlParameterAndKeepGo(this,'erhebungsjahr','<?php echo $go ?>')">
    					<?php
        					if(!empty($gueltigkeitsjahre) && count($gueltigkeitsjahre) > 0)
        					{
        					    foreach($gueltigkeitsjahre AS $gueltigkeitsjahr)
        					    {
        					        echo '<option value='. $gueltigkeitsjahr . ' ' . ($gueltigkeitsjahr === $getYear ? "selected" : "") . '>' . $gueltigkeitsjahr . "</option>";
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
                	<select name="behoerde" onchange="setNewUrlParameterAndKeepGo(this,'behoerde','<?php echo $go ?>')">
        				<?php
        				    if(!empty($wasserrechtlicheZulassungen))
            				{
            				    $behoerdeArray = array();
            				    
            				    $optionSelected = false;
            				    $options = "";
            				    //var_dump($wasserrechtlicheZulassungen);
            				    foreach($wasserrechtlicheZulassungen AS $wrz)
            				    {
//             				        print_r($wrz->getId());
            				        if(!empty($wrz) && in_array($getYear, $wrz->gueltigkeitsJahre))
            				        {
            				            if(!empty($wrz->behoerde))
            				            {
            				                if(!in_array($wrz->behoerde->toString(), $behoerdeArray))
            				                {
            				                    $behoerdeArray[]=$wrz->behoerde->toString();
//             				                    echo "getBehörde: " . $getBehoerde;
//             				                    echo "wrz->behoerde->getId(): " . $wrz->behoerde->getId();
            				                    if($wrz->behoerde->getId() === $getBehoerde)
            				                    {
            				                        $optionSelected = true;
            				                    }
            				                    $options = $options . "<option value='" . $wrz->behoerde->getId() . "' " . ($wrz->behoerde->getId() === $getBehoerde ? "selected" : "") . ">" . $wrz->behoerde->getName() . "</option>";
            				                }
            				            }
            				        }
            				    }
            				    
            				    if(empty($options))
            				    {
            				        echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
            				    }
            				    else
            				    {
            				        if(!$optionSelected)
            				        {
            				            $options = "<option value='" . WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE . "'>" . WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT . "</option>" . $options;
            				        }
            				        
            				        echo $options;
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
				<input autocomplete="off" title="Adressat"
					onkeydown="if(this.backup_value==undefined){this.backup_value=this.value; document.getElementById('25_personen_id_0').backup_value=document.getElementById('25_personen_id_0').value;}"
					onkeyup="autocomplete1('25', 'personen_id', '25_personen_id_0', this.value);"
					onchange="if(document.getElementById('suggests_25_personen_id_0').style.display=='block'){this.value=this.backup_value; document.getElementById('25_personen_id_0').value=document.getElementById('25_personen_id_0').backup_value;setTimeout(function(){document.getElementById('suggests_25_personen_id_0').style.display = 'none';}, 500);}"
					id="output_25_personen_id_0" value="<?php echo !empty($selectedAdressat) && !empty($selectedAdressat->getName()) ? $selectedAdressat->getName() : '' ?>" type="text" /> 
				<input onchange="setNewUrlParameterAndKeepGo(this,'adressat','<?php echo $go ?>')" id="25_personen_id_0" type="hidden" />
				<div valign="top" style="height: 0px; position: relative;">
					<div id="suggests_25_personen_id_0" style="z-index: 3000; display: none; left: 0px; top: 0px; width: 400px; vertical-align: top; overflow: hidden; border: 1px solid grey;"></div>
				</div>
			</div>
        </div>

		<div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Straße Hausnummer:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
              		<input type="text" name="strasse_hausnummer" disabled="disabled" value="<?php echo !empty($selectedAdressat) ? $selectedAdressat->getAdresseStrasse() : ""; ?> <?php echo !empty($selectedAdressat) ? $selectedAdressat->getAdresseHausnummer() : ""; ?>" />
                </div>
        </div>
        
        <div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Postleitzahl Ort:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
              		<input type="text" name="plz_ort" disabled="disabled" value="<?php echo !empty($selectedAdressat) ? $selectedAdressat->getAdressePLZ() : ""; ?> <?php echo !empty($selectedAdressat) ? $selectedAdressat->getAdresseOrt() : ""; ?>" />
                </div>
        </div>
        
    </div>