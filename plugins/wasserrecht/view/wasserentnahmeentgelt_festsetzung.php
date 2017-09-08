<?php
$wrz = null;
$gewaesserbenutzung = null;
		
// print_r($_REQUEST); 
		  
if($_SERVER ["REQUEST_METHOD"] == "GET")
{
//     print_r($_POST);

    foreach($_REQUEST as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(strtolower($keyEscaped) === "getfestsetzung")
		{
		    $lastIndex = strripos($valueEscaped, "_");
		    $festsetzungWrzId = substr($valueEscaped, $lastIndex + 1);
// 		    echo "<br />lastIndex: " . $lastIndex . " festsetzungWrzId: " . $festsetzungWrzId;
		    $festsetzungWrz = new WasserrechtlicheZulassungen($this);
		    $wrz = $festsetzungWrz->find_by_id($this, 'id', $festsetzungWrzId);
		    if(!empty($wrz))
		    {
		        $gewaesserbenutzungId = substr($valueEscaped, 0, $lastIndex);
// 		        echo "<br />gewaesserbenutzungId: " . $gewaesserbenutzungId;
		        $gb = new Gewaesserbenutzungen($this);
		        $gewaesserbenutzungen = $gb->find_where_with_subtables('id=' . $gewaesserbenutzungId);
		        if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0 && !empty($gewaesserbenutzungen[0]))
		        {
		            $gewaesserbenutzung = $gewaesserbenutzungen[0];
		        }
// 		        echo "<br />gewaesserbenutzung: " . $gewaesserbenutzung[0]->getId();
		    }
		    break;
		 }
    }
}

//try to find the first WRZ if, no wrz was given
if(empty($wrz))
{
    $defaultWrz = new WasserrechtlicheZulassungen($this);
    $results = $defaultWrz->find_where('1=1', 'id');
    
    if(!empty($results) && count($results) > 0)
    {
        $wrz = $results[0];
    }
}

if(!empty($wrz))
{
    $wrz->getDependentObjects($this, $wrz);
    if(empty($gewaesserbenutzung) && !empty($wrz->gewaesserbenutzungen) && count($wrz->gewaesserbenutzungen) > 0 && !empty($wrz->gewaesserbenutzungen[0]))
    {
        $gewaesserbenutzung = $wrz->gewaesserbenutzungen[0];
    }
    
    $tab1_id="wasserentnahmeentgelt_erklaerung_der_entnahme";
    $tab1_name="Erklärung der Entnahme";
    $tab1_active=false;
    $tab1_visible=true;
    $tab2_id="wasserentnahmeentgelt_festsetzung";
    $tab1_extra_parameter_key="geterklaerung";
    $tab1_extra_parameter_value=empty($gewaesserbenutzung) ? "0" . "_" . $wrz->getId() : $gewaesserbenutzung->getId() . "_" . $wrz->getId();
    $tab2_name="Festsetzung";
    $tab2_active=true;
    $tab2_visible=true;
    include_once ('includes/header.php'); 
    
    ?>

	<div id="wasserentnahmeentgelt_festsetzung" class="tabcontent" style="display: block">

    	<form action="index.php" id="erklaerung_freigeben_form" accept-charset="" method="POST">
        		
    		<?php 
    		     include_once ('wasserentnahmeentgelt_header.php'); 
    		?>
    		
    		  <table class="wasserrecht_table" style="margin-top: 20px; width: 1000px">
                  <tr>
                  	<th></th>
                    <th>Erklärter Teil-Benutzungsart</th>
                    <th>Erklärter Teil-Benutzungszweck</th>
                    <th>Erklärter Teil-Benutzungsumfang in m³/a</th>
                    <th>Wiedereinleitung</th>
                    <th>Mengenbestimmung</th>
                    <th>Art der Benutzung</th>
                    <th>Wiedereinleitung</th>
                    <th>Befreiungstatbestände nach § 16 LWaG</th>
                    <th>Entgeltsatz</th>
                    <th>Entgelt</th>
                  </tr>
                  <tr>
           		  <?php
                  for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++) 
                  {
                      
                          $teilgewaesserbenutzung = null;
                          if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0 
                              && count($gewaesserbenutzung->teilgewaesserbenutzungen) > ($i - 1) && !empty($gewaesserbenutzung->teilgewaesserbenutzungen[$i -1]))
                          {
                              $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[$i - 1];
//                               var_dump($teilgewaesserbenutzung->gewaesserbenutzungArt->getName());

                              if(!empty($teilgewaesserbenutzung))
                              {
                                  //Art Benutzung
                                  $getArtBenutzung = !empty(htmlspecialchars($_REQUEST['artbenutzung'])) ? htmlspecialchars($_REQUEST['artbenutzung']) : null;
                                  if(!empty($getArtBenutzung) && strpos($getArtBenutzung, '_') !== false)
                                  {
                                      $lastIndex = strripos($getArtBenutzung, "_");
                                      $getArtBenutzung = substr($getArtBenutzung, $lastIndex + 1);
                                  }    
                                  elseif(!empty($teilgewaesserbenutzung->art_benutzung))
                                  {
                                      $getArtBenutzung = $teilgewaesserbenutzung->art_benutzung->getId();
                                  }
                                  else
                                  {
                                      $getArtBenutzung = "1";
                                  }    
                                  
                                  //Wiedereinleitung Bearbeiter
                                  $getWiedereinleitungBearbeiter = !empty(htmlspecialchars($_REQUEST['wiedereinleitungbearbeiter'])) ? htmlspecialchars($_REQUEST['wiedereinleitungbearbeiter']) : null;
                                  if(!empty($getWiedereinleitungBearbeiter) && strpos($getWiedereinleitungBearbeiter, '_') !== false)
                                  {
                                      $lastIndex = strripos($getWiedereinleitungBearbeiter, "_");
                                      $getWiedereinleitungBearbeiter = substr($getWiedereinleitungBearbeiter, $lastIndex + 1);
                                      $getWiedereinleitungBearbeiter = strtolower($getWiedereinleitungBearbeiter) === 'true'? true: false;
                                  }
                                  else
                                  {
                                      $getWiedereinleitungBearbeiter = $teilgewaesserbenutzung->getWiedereinleitungBearbeiter();
                                  }
                                  
                                  //Befreiungstatbestände
                                  $getBefreiungstatbestaende = !empty(htmlspecialchars($_REQUEST['befreiungstatbestaende'])) ? htmlspecialchars($_REQUEST['befreiungstatbestaende']) : null;
                                  if(!empty($getBefreiungstatbestaende) && strpos($getBefreiungstatbestaende, '_') !== false)
                                  {
                                      $lastIndex = strripos($getBefreiungstatbestaende, "_");
                                      $getBefreiungstatbestaende = substr($getBefreiungstatbestaende, $lastIndex + 1);
                                      $getBefreiungstatbestaende = strtolower($getBefreiungstatbestaende) === 'true'? true: false;
                                  }
                                  else
                                  {
                                      $getBefreiungstatbestaende = $teilgewaesserbenutzung->getBefreiungstatbestaende();
                                  }
                                  
                                  ?>
                                  <td><?php echo $i; ?>.</td>
                                  <td><?php echo !empty($teilgewaesserbenutzung->gewaesserbenutzungArt) ? $teilgewaesserbenutzung->gewaesserbenutzungArt->getName() : "" ?></td>
                                  <td><?php echo !empty($teilgewaesserbenutzung->gewaesserbenutzungZweck) ? $teilgewaesserbenutzung->gewaesserbenutzungZweck->getName() : "" ?></td>
                                  <td><?php echo !empty($teilgewaesserbenutzung->getUmfang()) ? $teilgewaesserbenutzung->getUmfang() : "" ?></td>
                                  <td><?php echo !empty($teilgewaesserbenutzung->getWiedereinleitungNutzer()) && $teilgewaesserbenutzung->getWiedereinleitungNutzer() === "t" ? "ja" : "nein" ?></td>
                                  <td><?php echo !empty($teilgewaesserbenutzung->mengenbestimmung) ? $teilgewaesserbenutzung->mengenbestimmung->getName() : "" ?></td>
                                  <td>
                                  	<select name="teilgewaesserbenutzung_art_benutzung_<?php echo $i; ?>" onchange="setNewUrlParameter(this,'artbenutzung')">
                                		<option value="<?php echo $i; ?>_1" <?php echo $getArtBenutzung === "1" ?  'selected' : ''?>>GW</option>
                                		<option value="<?php echo $i; ?>_2" <?php echo $getArtBenutzung === "2" ?  'selected' : ''?>>OW</option>
                                	</select>
                                  </td>
                                  <td>
                                  	<select name="teilgewaesserbenutzung_wiedereinleitung_bearbeiter_<?php echo $i; ?>" onchange="setNewUrlParameter(this,'wiedereinleitungbearbeiter')">
                                		<option value="<?php echo $i; ?>_true" <?php echo $getWiedereinleitungBearbeiter ?  'selected' : ''?>>ja</option>
                                		<option value="<?php echo $i; ?>_false" <?php echo !$getWiedereinleitungBearbeiter ?  'selected' : ''?>>nein</option>
                                	</select>
                                  </td>
                                  <td>
                                  	<select name="teilgewaesserbenutzung_befreiungstatbestaende_<?php echo $i; ?>" onchange="setNewUrlParameter(this,'befreiungstatbestaende')">
                                		<option value="<?php echo $i; ?>_true" <?php echo $getBefreiungstatbestaende ?  'selected' : ''?>>ja</option>
                                		<option value="<?php echo $i; ?>_false" <?php echo !$getBefreiungstatbestaende ?  'selected' : ''?>>nein</option>
                                	</select>
                                  </td>
                                  <td>
                                  	<?php echo $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter) ?>
                                  </td>
                                  <td>
                                  	<?php echo $teilgewaesserbenutzung->getEntgelt($getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter) ?>
                                  </td>
                           <?php
                              }
                          }
                      }
                  ?>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td></td>
                  	<td>Zugelassene Entnahmemenge:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="zugelassene_entnahmemenge" name="zugelassene_entnahmemenge" readonly="readonly" value=""></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td>Zugelassene Entnahme Entgelt:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="zugelassene_entnahme_entgelt" name="zugelassene_entnahme_entgelt" readonly="readonly" value=""></td>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td></td>
                  	<td>Nicht zugelassene Entnahme:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="nicht_zugelassene_entnahmemenge" name="nicht_zugelassene_entnahmemenge" readonly="readonly" value=""></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td>Nicht zugelassene Entnahmeentgelt:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="nicht_zugelassene_entnahme_entgelt" name="nicht_zugelassene_entnahme_entgelt" readonly="readonly" value=""></td>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td></td>
                  	<td>Summe Entnahmemengen:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_entnahmemengen" name="summe_zugelassene_entnahmemengen" readonly="readonly" value="<?php echo $gewaesserbenutzung->getUmfangAllerTeilbenutzungen() ?>"></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td>Summe Entgelt:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_entgelt" name="summe_entgelt" readonly="readonly" value=""></td>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td>Summe gebucht:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_gebucht" name="summe_gebucht" readonly="readonly" value=""></td>
                  </tr>
              </table>
              
           <div class="wasserrecht_display_table" style="margin-top: 20px; margin-left: 15px">
            
                <div class="wasserrecht_display_table_row">
                    <div class="wasserrecht_display_table_cell_caption">Erklärung oder Schätzung:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                        <select class="wasserrecht_display_table_cell_white" name="teilgewaesserbenutzungsart">
                        	<?php 
                            	$teilgewaesserbenutzung = null;
                            	if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0 && !empty($gewaesserbenutzung->teilgewaesserbenutzungen[0]))
                            	{
                            	    $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[0];
                            	    //var_dump($teilgewaesserbenutzung);
                            	}
                        	?>
                        	<option value="1" <?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->teilgewaesserbenutzungen_art) && $teilgewaesserbenutzung->teilgewaesserbenutzungen_art->getId() === "1" ?  'selected' : ''?>>Erklärung</option>
                        	<option value="2" <?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->teilgewaesserbenutzungen_art) && $teilgewaesserbenutzung->teilgewaesserbenutzungen_art->getId() === "2" ?  'selected' : ''?>>Schätzung</option>
                        </select>
                     </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
           		<div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Datum Erklärung:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    	<?php
                            echo $wrz->getErklaerungDatumHTML();
                	    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Bearbeiter Erklärung:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    <?php 
                        echo $this->user->Vorname . ' ' . $this->user->Name
                    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
	   			<div class="wasserrecht_display_table_row">
	   				<div class="wasserrecht_display_table_cell_caption">
            			<input type="hidden" name="go" value="wasserentnahmeentgelt_festsetzung">
						<button class="wasserrecht_button" name="festsetzung_speichern_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>" value="festsetzung_speichern_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>" type="submit" id="festsetzung_speichern_button_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>">Festsetzung speichern</button>
           			</div>
           			<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
           		</div>
	   			
	   			<div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
	   			<div class="wasserrecht_display_table_row">
	   				<div class="wasserrecht_display_table_cell_caption">
						<button class="wasserrecht_button" name="festsetzung_freigeben_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>" value="festsetzung_freigeben_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>" type="submit" id="festsetzung_freigeben_button_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>">Festsetzung freigeben</button>
           			</div>
           			<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
           		</div>
           		
           		<div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
	   			<div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Datum Freigabe:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    	<?php
//                             echo $wrz->getErklaerungDatumHTML();
                	    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Bearbeiter Freigabe:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    <?php
                        echo $this->user->Vorname . ' ' . $this->user->Name
                    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		</div>
                
                <div class="wasserrecht_display_table_row">
           			<div class="wasserrecht_display_table_cell_caption">Abgelegte Festsetzungen</div>
				</div>
				
				<div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
	   			<div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Sammelbescheid erstellt:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    	<?php
//                             echo $wrz->getErklaerungDatumHTML();
                	    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Verwaltungsaufwand beantragt:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    <?php
//                         echo $this->user->Vorname . ' ' . $this->user->Name
                    ?>
                    </div>
                </div>
                
            </div>
              
 	</form>
</div>
    
    <?php
}
else
{
    echo '<h1 style=\"color: red;\">Keine Wasserrechtliche Zulassung gefunden!<h1>';
}

?>