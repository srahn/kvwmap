<?php 
$tab1_id=WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL;
$tab1_name="Aufforderung zur Erklärung";
$tab1_active=true;
$tab1_visible=true;
$tab2_id=WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL;
$tab2_name="Entgeltbescheid";
$tab2_active=false;
$tab2_visible=true;
include_once ('includes/header.php');

// 		  print_r($_REQUEST);

if($_SERVER ["REQUEST_METHOD"] == "POST")
{
    // 		      print_r($_POST);
    
    $wrzs = array();
    $erhebungsjahr = null;
    
    foreach($_POST as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(startsWith($keyEscaped, AUFFORDERUNG_CHECKBOX_URL))
        {
            findAufforderungWrzsGewaesserbenutzungen($this, $wrzs, $valueEscaped, $erhebungsjahr);
        }
    }
    
    if(!empty($wrzs) && !empty($erhebungsjahr))
    {
        createAufforderungsDokument($this, $wrzs, $erhebungsjahr);
    }
}

function findAufforderungWrzsGewaesserbenutzungen(&$gui, &$wrzs, &$valueEscaped, &$erhebungsjahr)
{
    $gui->log->log_info('*** findAufforderungWrzsGewaesserbenutzungen ***');
    
    $gui->log->log_debug('wrzs length: ' . sizeof($wrzs));
    $gui->log->log_debug('valueEscaped: ' . var_export($valueEscaped, true));
    $gui->log->log_debug('erhebungsjahr: ' . var_export($erhebungsjahr, true));
    
    $idValues = findIdAndYearFromValueString($gui, $valueEscaped);
    $gui->log->log_debug('idValues: ' . var_export($idValues, true));
    
    $erhebungsjahr = $idValues["erhebungsjahr"];
    
    $wrzClass = new WasserrechtlicheZulassungen($gui);
    $wrz = $wrzClass->find_by_id($gui, 'id', $idValues["wrz_id"]);
    if(!empty($wrz))
    {
        $gui->log->log_debug('wrz id: ' . var_export($wrz->getId(), true));
        
        //get all dependent objects
        $wrz->getDependentObjects($gui, $wrz);
        
        $gewaesserbenutzungen = $wrz->gewaesserbenutzungen;
        $gewaesserbenutzung = null;
        if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0)
        {
            foreach ($gewaesserbenutzungen as $gwb)
            {
                if(!empty($gwb) && $gwb->getId() === $idValues["gewaesserbenutzung_id"])
                {
                    $gewaesserbenutzung = $gwb;
                    break;
                }
            }
        }
        
        if(!empty($gewaesserbenutzung))
        {
            $gui->log->log_debug('gewaesserbenutzung id: ' . var_export($gewaesserbenutzung->getId(), true));
            
            if(empty($gewaesserbenutzung->getAufforderungDatum($erhebungsjahr)))
            {
                $wrz->gewaesserbenutzungen = $gewaesserbenutzung;
                $wrzs[] = $wrz;
            }
            else
            {
                $gui->log->log_debug('Do not write Aufforderung, because it already exists');
            }
        }
        
    }
}

function createAufforderungsDokument(&$gui, &$wrzs, &$erhebungsjahr)
{
    $gui->log->log_info('*** createAufforderungsDokument ***');
    
    $gui->log->log_debug('wrzs length: ' . sizeof($wrzs));
    $gui->log->log_debug('erhebungsjahr: ' . var_export($erhebungsjahr, true));
    
    if(!empty($wrzs) && !empty($wrzs[0]))
    {
        $wrz = $wrzs[0];
        
        //get a unique word file name
        $uniqid = uniqid();
        $word_file_name = $uniqid . ".docx";
        $word_file = WASSERRECHT_DOCUMENT_PATH . $word_file_name;
        
        //get the parameter
        $datum = $gui->date->getToday();
        //                     $year = $gui->date->getThisYear();
        $nextyear = $gui->date->getNextYear();
        
        //                     var_dump($gui->user);
        $bearbeiter = $gui->user->Name . ' ' . $gui->user->Vorname;
        $bearbeiter_telefon = $gui->user->phon;
        $bearbeiter_email = $gui->user->email;
        $bearbeiter_plz = $wrz->zustaendigeBehoerde->adresse->getPLZ();
        $bearbeiter_ort = $wrz->zustaendigeBehoerde->adresse->getOrt();
        $adressat_id = $wrz->adressat->getId();
        $behoerde_name = $wrz->zustaendigeBehoerde->getName();
        $behoerde_strasse = $wrz->zustaendigeBehoerde->adresse->getStrasse();
        $behoerde_hausnummer = $wrz->zustaendigeBehoerde->adresse->getHausnummer();
        $behoerde_plz = $wrz->zustaendigeBehoerde->adresse->getPLZ();
        $behoerde_ort = $wrz->zustaendigeBehoerde->adresse->getOrt();
        $behoerde_art_name = $wrz->zustaendigeBehoerde->art->getName();
        $adressat_name = $wrz->adressat->getName();
        $adressat_strasse = $wrz->adressat->adresse->getStrasse();
        $adressat_hausnummer = $wrz->adressat->adresse->getHausnummer();
        $adressat_plz = $wrz->adressat->adresse->getPLZ();
        $adressat_ort = $wrz->adressat->adresse->getOrt();
        
        $parameter = [
            "Datum" => $datum,
            "Next_Year" => $nextyear,
            "Bearbeiter" => $bearbeiter,
            "Bearbeiter_Telefon" => $bearbeiter_telefon,
            "Bearbeiter_EMail" => $bearbeiter_email,
            "Bearbeiter_PLZ" => $bearbeiter_plz,
            "Bearbeiter_Ort" => $bearbeiter_ort,
            "Erhebungsjahr" => $erhebungsjahr,
            "Adressat_ID" => $adressat_id,
            "Behoerde_Name" => $behoerde_name,
            "Behoerde_Strasse" => $behoerde_strasse,
            "Behoerde_Hnr" => $behoerde_hausnummer,
            "Behoerde_PLZ" => $behoerde_plz,
            "Behoerde_Ort" => $behoerde_ort,
            "Behoerde_Art_Name" => $behoerde_art_name,
            "Adressat_Name" => $adressat_name,
            "Adressat_Strasse" => $adressat_strasse,
            "Adressat_Hnr" => $adressat_hausnummer,
            "Adressat_PLZ" => $adressat_plz,
            "Adressat_Ort" => $adressat_ort,
            "WASSERRECHT_VORDRUCK_ERKLAERUNG_WASSERENTNAHMEMENGE" => WASSERRECHT_VORDRUCK_ERKLAERUNG_WASSERENTNAHMEMENGE
        ];
        
        //                     echo var_export($parameter, true);
        
        $aufforderungsBescheidDaten = new AufforderungsBescheidDaten($gui);
        $aufforderungsBescheidDaten->setWrzs($wrzs);
        $aufforderungsBescheidDaten->setParameter($parameter);
        
        //write the word file
        writeAufforderungZurErklaerungWordFile($gui, PLUGINS . AUFFORDERUNG_BESCHEID_PATH, $word_file, $aufforderungsBescheidDaten);
        
        //write the document path to the database
        $aufforderung_dokument = new Dokument($gui);
        $aufforderung_dokument_name = 'Aufforderung_' . $uniqid;
        $aufforderung_document_identifier = $aufforderung_dokument->createDocument($aufforderung_dokument_name, $word_file_name);
        
        foreach ($wrzs as $wrz)
        {
            if(!empty($wrz) && !empty($wrz->gewaesserbenutzungen))
            {
                $gewaesserbenutzung = $wrz->gewaesserbenutzungen;
                
                if(!empty($gewaesserbenutzung))
                {
                    $gewaesserbenutzung->insertAufforderung($aufforderung_document_identifier, $erhebungsjahr, null);
                }
            }
        }
    }
}
?>

<div id="<?php echo WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL ?>" class="tabcontent" style="display: block">

	<form action="index.php" id="aufforderung_form" accept-charset="" method="POST">
	
		<?php
		      $go=WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL;
		      $showAdressat=true;
		      include_once ('includes/wasserentnahmebenutzer_header.php');
		?>
    	
		<table id="wasserentnahmebenutzer_tabelle">
			<tr>
				<th>Auswahl</th>
				<th>Anlage</th>
    			<th>Wasserrechtliche Zulassung</th>
    			<th>Benutzung</th>
    			<th>Benutzungsnummer</th>
    			<th>Hinweis</th>
    			<th>Aufforderung</th>
    			<th>Erklärung anlegen</th>
    			<th>Erklärung</th>
    		</tr>
    		<tr>
    			<td style="background-color: inherit;"><input title="Alle auswählen" type="checkbox" id="select_all_checkboxes" onchange="$('input:checkbox').not(this).prop('checked', this.checked);"></td>
    		</tr>
    		<?php 
    		      if(!empty($wasserrechtlicheZulassungen))
        		  {
//         		      var_dump($wasserrechtlicheZulassungen);
        		      foreach($wasserrechtlicheZulassungen AS $wrz)
        		      {
        		          if(!empty($wrz) && in_array($getYear, $wrz->gueltigkeitsJahre))
        		          {
        		              $this->log->log_debug("wrz id: " . $wrz->getId());
        		              $this->log->log_debug("wrz gueltigkeitsJahre: " . var_export($wrz->gueltigkeitsJahre, true));
        		              
        		              if(empty($getBehoerde) || $getBehoerde === $wrz->zustaendigeBehoerde->getId())
        		              {
        		                  if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
        		                  {
//         		                      var_dump($wrz);
        		                      
        		                      $gewaesserbenutzungen = $wrz->gewaesserbenutzungen;
//         		                      var_dump($gewaesserbenutzungen);
        		                      
        		                      $gewaesserbenutzungen_count = 1;
        		                      if(!empty($gewaesserbenutzungen))
        		                      {
        		                          $gewaesserbenutzungen_count = count($gewaesserbenutzungen);
        		                      }
        		                      
        		                      for ($i = 0; $i < $gewaesserbenutzungen_count; $i++) 
        		                      {
        		                          $gewaesserbenutzung = $gewaesserbenutzungen[$i];
//         		                          echo "wrz_id: ". $wrz->getId() . " i: " . $i;

        		                          if(!empty($gewaesserbenutzung))
        		                          {
        		                              ?>
        		                          	<tr>
                        		          		<td style="background-color: inherit;">
                        		          			<?php 
                        		          			    if(empty($gewaesserbenutzung->isAufforderungFreigegeben($getYear)))
                            		          			{
                            		          			    ?>
                            		          				<input type="checkbox" name="<?php echo AUFFORDERUNG_CHECKBOX_URL ?><?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>">
                            		          		<?php
                            		          			} 
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php 
                        		          			     echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . $this->layer_names[ANLAGEN_LAYER_NAME] . '&value_' . ANLAGEN_LAYER_ID . '=' . $wrz->anlage->getId() . '&operator_' . ANLAGEN_LAYER_ID . '==">' . $wrz->anlage->getName() . '</a>';
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php 
                        		          			     echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . $this->layer_names[WRZ_LAYER_NAME] . '&value_' . WRZ_LAYER_ID . '=' . $wrz->getId() . '&operator_' . WRZ_LAYER_ID . '==">' . $wrz->getBezeichnung() . '</a>';
                    //     		          			     echo $wrz->getName();
                    //     		          			     var_dump($wrz);
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php
                        		          			     echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . $this->layer_names[GEWAESSERBENUTZUNGEN_LAYER_NAME] . '&value_' . GEWAESSERBENUTZUNGEN_LAYER_ID . '=' . $gewaesserbenutzung->getId() . '&operator_' . GEWAESSERBENUTZUNGEN_LAYER_ID . '==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php 
                        		          			     echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . $this->layer_names[GEWAESSERBENUTZUNGEN_LAYER_NAME] . '&value_' . GEWAESSERBENUTZUNGEN_LAYER_ID . '=' . $gewaesserbenutzung->getId() . '&operator_' . GEWAESSERBENUTZUNGEN_LAYER_ID . '==">' . $gewaesserbenutzung->getKennummer() . '</a>';
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php
                        		          			     echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . $this->layer_names[WRZ_LAYER_NAME] . '&value_' . WRZ_LAYER_ID . '=' . $wrz->getId() . '&operator_' . WRZ_LAYER_ID . '==">' . $wrz->getHinweisHTML() . '</a>';
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php
                        		          			     echo $gewaesserbenutzung->getAufforderungDatumHTML($getYear);
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          				<?php 
                        		          				    if(empty($gewaesserbenutzung->getErklaerungDatum($getYear)))
                        		          				    {
                        		          				?>
        														<button name="<?php echo ERKLAERUNG_URL ?><?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>" type="submit" id="<?php echo ERKLAERUNG_URL ?>button_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>">Erklärung</button>
                        		          				<?php
                        		          				    }
                        		          				?>
                        		          		</td>
                        		          		<td>
                        		          			<?php
                        		          			     echo '<a href="' . $this->actual_link . '?go=' . WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL .'&' . GET_ERKLAERUNG_URL .'=' . $wrz->getId() . "_" . $gewaesserbenutzung->getId() . "_" . $getYear . '">' . $gewaesserbenutzung->getErklaerungDatumHTML($getYear) . '</a>';
                        		          			?>
                        		          		</td>
                        		          	</tr>
                    		          <?php
        		                      }
    		                      }
    		                  }
    		              }
    		          }
    		      }
    		  }
    		?>
		</table>
		
	   <div class="wasserrecht_display_table" style="margin-top: 10px;">
			<div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Sammelaufforderung für ausgewählte Entnahmebenutzungen erstellen:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_row_spacer"></div>
			</div>
			<div class="wasserrecht_display_table_row">
				<div class="wasserrecht_display_table_cell_caption">
					<?php 
                        if(!empty($getYear))
                        {
                            ?>
                            	<input type="hidden" name="<?php echo ERHEBUNGSJAHR_URL ?>" value="<?php echo $getYear ?>">
                            <?php
                        }
        			?>
        			<?php 
        			     if(!empty($getBehoerde))
        			     {
        			         ?>
        			         	<input type="hidden" name="<?php echo BEHOERDE_URL ?>" value="<?php echo $getBehoerde ?>">
        			         <?php
        			     }
        			?>
        			<?php 
        			     if(!empty($getAdressat))
        			     {
        			         ?>
        			         	<input type="hidden" name="<?php echo ADRESSAT_URL ?>" value="<?php echo $getAdressat ?>">
        			         <?php
        			     }
        			 ?>
					<input type="hidden" name="go" value="<?php echo WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL ?>">
<!-- 						<input type="hidden" name="post_action_2" value="<?php echo WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL ?>"> -->
       				<input type="submit" value="Aufforderung erstellen!" id="aufforderung_button" name="aufforderung" />
				</div>
			</div>
			<div class="wasserrecht_display_table_row">
		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		<div class="wasserrecht_display_table_cell_spacer"></div>
		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   		</div>
	   		<div class="wasserrecht_display_table_row">
       			<div class="wasserrecht_display_table_cell_caption">Abgelegte Sammelaufforderungen</div>
			</div>
			<?php 
			    if(!empty($wasserrechtlicheZulassungen))
				{
				    $dokumentIds = array();
				    
				    foreach($wasserrechtlicheZulassungen AS $wrz)
				    {
				        if(!empty($wrz) && in_array($getYear, $wrz->gueltigkeitsJahre))
				        {
				            if(empty($getBehoerde) || $getBehoerde === $wrz->zustaendigeBehoerde->getId())
				            {
				                if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
				                {
				                    $this->log->log_debug('dokumentIds: ' . var_export($dokumentIds, true));
				                    
				                    $gwbs = $wrz->gewaesserbenutzungen;
				                    if(!empty($gwbs))
				                    {
				                        foreach ($gwbs as $gwb)
				                        {
				                            if(!empty($gwb))
				                            {
				                                $auffoderung_dokument = $gwb->getAufforderungDokument($getYear);
				                                
				                                if(!empty($auffoderung_dokument))
				                                {
				                                    if(!in_array($auffoderung_dokument->getId(), $dokumentIds))
				                                    {
				                                        $dokumentIds[] = $auffoderung_dokument->getId();
				                                        
				                                        ?>
                    				                    <div class="wasserrecht_display_table_row">
                                        					<div class="wasserrecht_display_table_cell_caption">
                                        					<?php
                                        					   echo '<a href="' . $this->actual_link . WASSERRECHT_DOCUMENT_URL_PATH . $auffoderung_dokument->getPfad() . '" target="_blank">' . $auffoderung_dokument->getName() . '</a>';
                                        					?>
                                                   			</div>
                                        				</div>
                                    				<?php 
				                                    }
            				                    }
				                            }
				                        }
				                    }
				                }
				            }
				        }
				    }
				}
			?>
       </div>
	</form>
</div>