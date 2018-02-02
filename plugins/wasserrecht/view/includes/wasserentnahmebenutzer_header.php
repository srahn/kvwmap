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

$getYear = !empty(htmlspecialchars($_REQUEST[ERHEBUNGSJAHR_URL])) ? htmlspecialchars($_REQUEST[ERHEBUNGSJAHR_URL]) : $this->date->getLastYear();

//Get Behörde
$getBehoerde = !empty(htmlspecialchars($_REQUEST[BEHOERDE_URL])) ? htmlspecialchars($_REQUEST[BEHOERDE_URL]) : null;
if(!empty($firstWrZ) && !empty($firstWrZ->zustaendigeBehoerde) && empty($getBehoerde))
{
    $getBehoerde = $firstWrZ->zustaendigeBehoerde->getId();
}
// print_r("getBehoerde: " . $getBehoerde);

//Get Adressat
if($showAdressat)
{
    $getAdressat = !empty(htmlspecialchars($_REQUEST[ADRESSAT_URL])) ? htmlspecialchars($_REQUEST[ADRESSAT_URL]) : null;
    $selectedAdressat = null;
    if(!empty($firstWrZ) && !empty($firstWrZ->adressat) && empty($getAdressat))
    {
        $getAdressat = $firstWrZ->adressat->getId();
        $selectedAdressat = $wrzProGueltigkeitsJahreArray->getAdressatInYearAndBehoerde($wasserrechtlicheZulassungen, $getYear, $getBehoerde, $getAdressat);
    }
    $adressatStable = $_SESSION[GET_SESSION_ADRESSAT_URL] === $getAdressat;
    // echo "adressatStable: " . var_export($adressatStable, true);
    $_SESSION[GET_SESSION_ADRESSAT_URL] = $getAdressat;
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
}
?>

<div class="wasserrecht_display_table">
		
		<div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Erhebungsjahr:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
                	<select name="<?php echo ERHEBUNGSJAHR_URL ?>" onchange="setNewUrlParameterAndKeepGo(this,'<?php echo ERHEBUNGSJAHR_URL ?>','<?php echo $go ?>')">
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
                	echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . BEHOERDE_LAYER_ID . '&value_' . BEHOERDE_OPERATOR_ID . '=' . $getBehoerde . '&operator_' . BEHOERDE_OPERATOR_ID . '==">Behörde: </a>';
    			     ?>
                </div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_cell">
                	<select name="<?php echo BEHOERDE_URL ?>" onchange="setNewUrlParameterAndKeepGo(this,'<?php echo BEHOERDE_URL ?>','<?php echo $go ?>')">
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
            				            if(!empty($wrz->zustaendigeBehoerde))
            				            {
            				                if(!in_array($wrz->zustaendigeBehoerde->toString(), $behoerdeArray))
            				                {
            				                    $behoerdeArray[]=$wrz->zustaendigeBehoerde->toString();
//             				                    echo "getBehörde: " . $getBehoerde;
//             				                    echo "wrz->zustaendigeBehoerde->getId(): " . $wrz->zustaendigeBehoerde->getId();
            				                    if($wrz->zustaendigeBehoerde->getId() === $getBehoerde)
            				                    {
            				                        $optionSelected = true;
            				                    }
            				                    $options = $options . "<option value='" . $wrz->zustaendigeBehoerde->getId() . "' " . ($wrz->zustaendigeBehoerde->getId() === $getBehoerde ? "selected" : "") . ">" . $wrz->zustaendigeBehoerde->getName() . "</option>";
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
         
        <?php 
        if($showAdressat)
        {
        ?>
         
            <div class="wasserrecht_display_table_row">
    			<div class="wasserrecht_display_table_row_spacer"></div>
    			<div class="wasserrecht_display_table_cell_spacer"></div>
    			<div class="wasserrecht_display_table_row_spacer"></div>
            </div>
            
            <div class="wasserrecht_display_table_row">
            	<div class="wasserrecht_display_table_cell_caption">
            		<?php 
        			    echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . PERSONEN_LAYER_ID . '&value_' . PERSONEN_OPERATOR_ID . '=' . $getAdressat . '&operator_' . PERSONEN_OPERATOR_ID . '==">Adressat:</a>';
                    ?>
            	</div>
            	<div class="wasserrecht_display_table_cell_spacer"></div>
            	<div class="wasserrecht_display_table_cell">
    				<input autocomplete="off" title="Adressat"
    					onkeydown="if(this.backup_value==undefined){this.backup_value=this.value; document.getElementById('25_<?php echo PERSONEN_OPERATOR_ID ?>_0').backup_value=document.getElementById('25_<?php echo PERSONEN_OPERATOR_ID ?>_0').value;}"
    					onkeyup="autocomplete1('25', '<?php echo PERSONEN_OPERATOR_ID ?>', '25_<?php echo PERSONEN_OPERATOR_ID ?>_0', this.value);"
    					onchange="if(document.getElementById('suggests_25_<?php echo PERSONEN_OPERATOR_ID ?>_0').style.display=='block'){this.value=this.backup_value; document.getElementById('25_<?php echo PERSONEN_OPERATOR_ID ?>_0').value=document.getElementById('25_<?php echo PERSONEN_OPERATOR_ID ?>_0').backup_value;setTimeout(function(){document.getElementById('suggests_25_<?php echo PERSONEN_OPERATOR_ID ?>_0').style.display = 'none';}, 500);}"
    					id="output_25_<?php echo PERSONEN_OPERATOR_ID ?>_0" value="<?php echo !empty($selectedAdressat) && !empty($selectedAdressat->getName()) ? $selectedAdressat->getName() : '' ?>" type="text" /> 
    				<input onchange="setNewUrlParameterAndKeepGo(this,'<?php echo ADRESSAT_URL ?>','<?php echo $go ?>')" id="25_<?php echo PERSONEN_OPERATOR_ID ?>_0" type="hidden" />
    				<div valign="top" style="height: 0px; position: relative;">
    					<div id="suggests_25_<?php echo PERSONEN_OPERATOR_ID ?>_0" style="z-index: 3000; display: none; left: 0px; top: 0px; width: 400px; vertical-align: top; overflow: hidden; border: 1px solid grey;"></div>
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
         <?php 
         }
         ?>
        
    </div>