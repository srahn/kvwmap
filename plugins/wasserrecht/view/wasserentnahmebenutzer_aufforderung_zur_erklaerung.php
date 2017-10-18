<?php 
$tab1_id="wasserentnahmebenutzer_aufforderung_zur_erklaerung";
$tab1_name="Aufforderung zur Erklärung";
$tab1_active=true;
$tab1_visible=true;
$tab2_id="wasserentnahmebenutzer_entgeltbescheid";
$tab2_name="Entgeltbescheid";
$tab2_active=false;
$tab2_visible=true;
include_once ('includes/header.php');

// 		  print_r($_REQUEST);

if($_SERVER ["REQUEST_METHOD"] == "POST")
{
    // 		      print_r($_POST);
    
    foreach($_POST as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(startsWith($keyEscaped, "aufforderung_checkbox_"))
        {
//             		              echo '<br />Key = ' . $keyEscaped . '<br />';
//             		              echo 'Value= ' . $valueEscaped;
            createAufforderungsDokument($this, $valueEscaped);
        }
    }
}

function createAufforderungsDokument(&$gui, &$valueEscaped)
{
    $gui->debug->write('*** createAufforderungsDokument ***', 4);
    
    $idValues = findIdFromValueString($gui, $valueEscaped);
    $gui->debug->write('idValues: ' . var_export($idValues, true), 4);
    
    $aufforderungWrz1 = new WasserrechtlicheZulassungen($gui);
    $aufforderungWrz2 = $aufforderungWrz1->find_by_id($gui, 'id', $idValues["wrz_id"]);
    if(!empty($aufforderungWrz2))
    {
        //get all dependent objects
        $aufforderungWrz2->getDependentObjects($gui, $aufforderungWrz2);
        
        $gewaesserbenutzungen = $aufforderungWrz2->gewaesserbenutzungen;
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
            $gui->debug->write('gewaesserbenutzung id: ' . var_export($gewaesserbenutzung->getId(), true), 4);
            
            if(empty($gewaesserbenutzung->getAufforderungDatum()))
            {
                if(!empty($_POST['aufforderung']) && empty($gewaesserbenutzung->getAufforderungDokument(null)))
                {
                    //get a unique word file name
                    $uniqid = uniqid();
                    $word_file_name = $uniqid . ".docx";
                    $word_file = WASSERRECHT_DOCUMENT_PATH . $word_file_name;
                    
                    //get the parameter
                    $datum = date("d.m.Y");
                    $nextyear = date('Y', strtotime('+1 year'));
                    $erhebungsjahr = htmlspecialchars($_REQUEST['erhebungsjahr']);
                    //                     var_dump($gui->user);
                    $bearbeiter = $gui->user->Name . ' ' . $gui->user->Vorname;
                    $bearbeiter_telefon = $gui->user->phon;
                    $bearbeiter_email = $gui->user->email;
                    $bearbeiter_plz = $aufforderungWrz2->behoerde->adresse->getPLZ();
                    $bearbeiter_ort = $aufforderungWrz2->behoerde->adresse->getOrt();
                    $adressat_id = $aufforderungWrz2->adressat->getId();
                    $behoerde_name = $aufforderungWrz2->behoerde->getName();
                    $behoerde_strasse = $aufforderungWrz2->behoerde->adresse->getStrasse();
                    $behoerde_hausnummer = $aufforderungWrz2->behoerde->adresse->getHausnummer();
                    $behoerde_plz = $aufforderungWrz2->behoerde->adresse->getPLZ();
                    $behoerde_ort = $aufforderungWrz2->behoerde->adresse->getOrt();
                    $behoerde_art_name = $aufforderungWrz2->behoerde->art->getName();
                    $adressat_name = $aufforderungWrz2->adressat->getName();
                    $adressat_strasse = $aufforderungWrz2->adressat->adresse->getStrasse();
                    $adressat_hausnummer = $aufforderungWrz2->adressat->adresse->getHausnummer();
                    $adressat_plz = $aufforderungWrz2->adressat->adresse->getPLZ();
                    $adressat_ort = $aufforderungWrz2->adressat->adresse->getOrt();
                    
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
                    
                    //write the word file
                    writeAufforderungZurErklaerungWordFile($gui, PLUGINS . 'wasserrecht/templates/Aufforderung_Erklaerung.docx', $word_file, $parameter);
                    
                    //write the document path to the database
                    $aufforderung_dokument = new Dokument($gui);
                    $aufforderung_document_identifier = $aufforderung_dokument->createDocument('Aufforderung_' . $idValues["wrz_id"], $word_file_name);
                    
                    $gewaesserbenutzung->insertAufforderung($aufforderung_document_identifier, null, null);
                }
            }
            else
            {
                $gui->debug->write('Do not write Aufforderung, because it already exists', 4);
            }
        }
    }
}
?>

<div id="wasserentnahmebenutzer_aufforderung_zur_erklaerung" class="tabcontent" style="display: block">

	<form action="index.php" id="aufforderung_form" accept-charset="" method="POST">
	
		<?php 
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
        		              if(empty($getBehoerde) || $getBehoerde === $wrz->behoerde->getId())
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
                        		          			    if(empty($gewaesserbenutzung->isAufforderungFreigegeben()))
                            		          			{
                            		          			    ?>
                            		          				<input type="checkbox" name="aufforderung_checkbox_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>">
                            		          		<?php
                            		          			} 
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php 
                        		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Anlagen'] . '&value_anlage_id=' . $wrz->anlagen->getId() . '&operator_anlage_id==">' . $wrz->anlagen->getName() . '</a>';
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php 
                        		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getBezeichnung() . '</a>';
                    //     		          			     echo $wrz->getName();
                    //     		          			     var_dump($wrz);
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php
                        		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Gewässerbenutzungen'] . '&value_id=' . $gewaesserbenutzung->getId() . '&operator_id==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php 
                        		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Gewässerbenutzungen'] . '&value_id=' . $gewaesserbenutzung->getId() . '&operator_id==">' . $gewaesserbenutzung->getKennummer() . '</a>';
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php
                        		          			     echo '<a style="color: red; text-decoration: underline;" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getHinweis() . '</a>';
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          			<?php
                        		          			     echo $gewaesserbenutzung->getAufforderungDatumHTML();
                        		          			?>
                        		          		</td>
                        		          		<td>
                        		          				<?php 
                        		          				    if(empty($gewaesserbenutzung->getErklaerungDatum($getYear)))
                        		          				    {
                        		          				?>
        														<button name="erklaerung_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>" type="submit" id="erklaerung_button_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>">Erklärung</button>
                        		          				<?php
                        		          				    }
                        		          				?>
                        		          		</td>
                        		          		<td>
                        		          			<?php
                        		          			     echo '<a href="' . $this->actual_link . '?go=wasserentnahmeentgelt_erklaerung_der_entnahme&geterklaerung=' . $wrz->getId() . "_" . $gewaesserbenutzung->getId() . "_" . $getYear . '">' . $gewaesserbenutzung->getErklaerungDatumHTML($getYear) . '</a>';
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
                            	<input type="hidden" name="erhebungsjahr" value="<?php echo $getYear ?>">
                            <?php
                        }
        			?>
        			<?php 
        			     if(!empty($getBehoerde))
        			     {
        			         ?>
        			         	<input type="hidden" name="behoerde" value="<?php echo $getBehoerde ?>">
        			         <?php
        			     }
        			?>
        			<?php 
        			     if(!empty($getAdressat))
        			     {
        			         ?>
        			         	<input type="hidden" name="adressat" value="<?php echo $getAdressat ?>">
        			         <?php
        			     }
        			 ?>
					<input type="hidden" name="go" value="wasserentnahmebenutzer_aufforderung_zur_erklaerung">
<!-- 						<input type="hidden" name="post_action_2" value="wasserentnahmebenutzer_aufforderung_zur_erklaerung"> -->
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
				    foreach($wasserrechtlicheZulassungen AS $wrz)
				    {
				        if(!empty($wrz) && in_array($getYear, $wrz->gueltigkeitsJahre))
				        {
				            if(empty($getBehoerde) || $getBehoerde === $wrz->behoerde->getId())
				            {
				                if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
				                {
				                    $gwbs = $wrz->gewaesserbenutzungen;
				                    if(!empty($gwbs))
				                    {
				                        foreach ($gwbs as $gwb)
				                        {
				                            if(!empty($gwb))
				                            {
				                                $auffoderung_dokument = $gwb->getAufforderungDokument(null);
				                                
				                                if(!empty($auffoderung_dokument))
				                                {
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
			?>
       </div>
	</form>
</div>