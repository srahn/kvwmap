<?php 
$tab1_id=WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL;
$tab1_name="Aufforderung zur Erklärung";
$tab1_active=false;
$tab1_visible=true;
$tab2_id=WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL;
$tab2_name="Entgeltbescheid";
$tab2_active=true;
$tab2_visible=true;
include_once ('includes/header.php');

$gesamtEntnahmemenge = 0;
$gesamtEntgelt = 0;

if($_SERVER ["REQUEST_METHOD"] == "POST")
{
//     print_r($_POST);
    
    $entgeltbescheid_erstellen = htmlspecialchars($_POST[ENTGELTBESCHEID_ERSTELLEN_URL]);
    $auswahl_checkbox_array = $_POST[AUSWAHL_CHECKBOX_URL];
    if(!empty($entgeltbescheid_erstellen) && !empty($auswahl_checkbox_array) && is_array($auswahl_checkbox_array))
    {
        $wrzs = array();
        
        foreach ($auswahl_checkbox_array as $auswahl_checkbox)
        {
            if(!empty($auswahl_checkbox))
            {
                $auswahl_checkbox_escaped = htmlspecialchars($auswahl_checkbox);
                
                $idValues = findIdAndYearFromValueString($this, $auswahl_checkbox_escaped);
                $this->log->log_debug('idValues: ' . var_export($idValues, true));
                
                $festsetzungWrz = new WasserrechtlicheZulassungen($this);
                $wrz = $festsetzungWrz->find_by_id($this, 'id', $idValues["wrz_id"]);
                // 		    var_dump($wrz);
                // 		    echo "<br />wrz id: " . $wrz->getId();
                
                $gb = new Gewaesserbenutzungen($this);
                $gwb = $gb->find_where_with_subtables('id=' . $idValues["gewaesserbenutzung_id"], 'id');
                
                if(!empty($wrz) && !empty($gwb))
                {
                    $wrz->gewaesserbenutzungen = $gwb;
                    $wrzs[] = $wrz;
                }
            }
        }
        
        $festsetzung_dokument_name = festsetzung_erstellen($this, $wrzs, $idValues["erhebungsjahr"]);
        if(!empty($festsetzung_dokument_name))
        {
            $this->add_message("notice", "Festsetzungsbescheid: '" . $festsetzung_dokument_name . "' erfolgreich erstellt!");
        }
        else
        {
            $this->add_message("error", "Der Festsetzungsbescheid konnte nicht erstellt werden!");
        }
    }
}

function festsetzung_erstellen(&$gui, &$wrzs, &$erhebungsjahr)
{
    $gui->log->log_info('*** wasserentnahmebenutzer_entgeltbescheid->festsetzung_erstellen ***');
    
    if(!empty($wrzs) && count($wrzs) > 0 && !empty($wrzs[0]))
    {
        $festsetzungsSammelbescheidDaten = new FestsetzungsSammelbescheidDaten($gui);
        
        $festsetzungsSammelbescheidDaten->setErhebungsjahr($erhebungsjahr);
        
        foreach ($wrzs as $wrz)
        {
            if(!empty($wrz))
            {
                $gewaesserbenutzung = $wrz->gewaesserbenutzungen[0];
                
                //get all dependent objects
                $wrz->getDependentObjectsInternal($gui, $wrz, false);
                
                if(!empty($gewaesserbenutzung))
                {
                    $gui->log->log_debug('gewaesserbenutzung id: ' . var_export($gewaesserbenutzung->getId(), true));
                    
                    //alle Daten für den Festsetzungsbescheid sammeln
                    $festsetzungsSammelbescheidDaten->addWrz($wrz);
                    $festsetzungsSammelbescheidDaten->addGewaesserbenutzungen($gewaesserbenutzung);
                    
                    //bestehendes Festsetzungsdokument löschen, wenn vorhanden
                    if($gewaesserbenutzung->isFestsetzungFreigegeben($erhebungsjahr) && !$gewaesserbenutzung->isFestsetzungDokumentErstellt($erhebungsjahr))
                    {
                        if(!empty($gewaesserbenutzung->getFestsetzungDokument($erhebungsjahr)))
                        {
                            $oldFestsetzungsDocumentId = $gewaesserbenutzung->getFestsetzungDokument($erhebungsjahr);
                            $gewaesserbenutzung->deleteFestsetzungDokument($erhebungsjahr);
                            
                            $festsetzung_delete_dokument = new Dokument($gui);
                            $festsetzung_delete_dokument->deleteDocument($oldFestsetzungsDocumentId);
                        }
                    }
                }
            }
        }
        
        //Freitext bekommen
        $festsetzungsSammelbescheidDaten->setFreitextFromAllEntries();
        
        //write the document path to the database
        $word_file_name = festsetzung_dokument_erstellen($gui, $festsetzungsSammelbescheidDaten);
        if(!empty($word_file_name))
        {
            $festsetzung_dokument = new Dokument($gui);
            $festsetzung_dokument_name = 'FestsetzungBescheid_' . basename($word_file_name, '.docx');
            $festsetzung_dokument_identifier = $festsetzung_dokument->createDocument($festsetzung_dokument_name, $word_file_name);
            
            foreach ($wrzs as $wrz)
            {
                if(!empty($wrz))
                {
                    $gewaesserbenutzung = $wrz->gewaesserbenutzungen[0];
                    if(!empty($gewaesserbenutzung))
                    {
                        $gewaesserbenutzung->insertFestsetzungDokument($erhebungsjahr, $festsetzung_dokument_identifier, null);
                    }
                }
            }
            
            return $festsetzung_dokument_name;
        }
    }
    
    return null;
}

/**
 * Fesetzungsbescheid erstellen
 */
function festsetzung_dokument_erstellen(&$gui, &$festsetzungsSammelbescheidDaten)
{
    $gui->log->log_info('*** wasserentnahmebenutzer_entgeltbescheid->festsetzung_dokument_erstellen ***');
    $festsetzungsSammelbescheidDaten->toString();
    $gui->log->log_debug('festsetzungsSammelbescheidDaten->isValid(): ' . var_export($festsetzungsSammelbescheidDaten->isValid(), true));
    
    if($festsetzungsSammelbescheidDaten->isValid())
    {
        $wrz = $festsetzungsSammelbescheidDaten->getWrzs()[0];
        
        //get a unique word file name
        $uniqid = uniqid();
        $word_file_name = $uniqid . ".docx";
        $word_file = WASSERRECHT_DOCUMENT_PATH . $word_file_name;
        $gui->log->log_debug('word_file_name: ' . $word_file_name);
        
        //get the parameter
        $datum = $gui->date->getToday();
        $erhebungsjahr = htmlspecialchars($_REQUEST[ERHEBUNGSJAHR_URL]);
        
        $bearbeiter = $gui->user->Name . ' ' . $gui->user->Vorname;
        $bearbeiter_telefon = $gui->user->phon;
        $bearbeiter_email = $gui->user->email;
        if(!empty($wrz->zustaendigeBehoerde))
        {
            $behoerde_name = $wrz->zustaendigeBehoerde->getName();
            
            if(!empty($wrz->zustaendigeBehoerde->adresse))
            {
                $bearbeiter_plz = $wrz->zustaendigeBehoerde->adresse->getPLZ();
                $bearbeiter_ort = $wrz->zustaendigeBehoerde->adresse->getOrt();
                
                $behoerde_strasse = $wrz->zustaendigeBehoerde->adresse->getStrasse();
                $behoerde_hausnummer = $wrz->zustaendigeBehoerde->adresse->getHausnummer();
                $behoerde_plz = $wrz->zustaendigeBehoerde->adresse->getPLZ();
                $behoerde_ort = $wrz->zustaendigeBehoerde->adresse->getOrt();
            }
            
            if(!empty($wrz->zustaendigeBehoerde->art))
            {
                $behoerde_art_name = $wrz->zustaendigeBehoerde->art->getName();
            }
            
            if(!empty($wrz->zustaendigeBehoerde->konto))
            {
                $behoerde_iban = $wrz->zustaendigeBehoerde->konto->getIBAN();
                $behoerde_bankname = $wrz->zustaendigeBehoerde->konto->getBankname();
                $behoerde_bic = $wrz->zustaendigeBehoerde->konto->getBIC();
            }
        }
        
        if(!empty($wrz->adressat))
        {
            $adressat_id = $wrz->adressat->getId();
            $adressat_name = $wrz->adressat->getName();
            
            if(!empty($wrz->adressat->adresse))
            {
                $adressat_strasse = $wrz->adressat->adresse->getStrasse();
                $adressat_hausnummer = $wrz->adressat->adresse->getHausnummer();
                $adressat_plz = $wrz->adressat->adresse->getPLZ();
                $adressat_ort = $wrz->adressat->adresse->getOrt();
            }
        }
        
        $erklaerung_datum = $festsetzungsSammelbescheidDaten->getErklaerung_datum_String();
        
        $parameter = [
            "Datum" => $datum,
            "Erhebungsjahr" => $erhebungsjahr,
            "Bearbeiter" => $bearbeiter,
            "Bearbeiter_Telefon" => $bearbeiter_telefon,
            "Bearbeiter_EMail" => $bearbeiter_email,
            "Bearbeiter_PLZ" => $bearbeiter_plz,
            "Bearbeiter_Ort" => $bearbeiter_ort,
            "Adressat_ID" => $adressat_id,
            "Behoerde_Name" => $behoerde_name,
            "Behoerde_Strasse" => $behoerde_strasse,
            "Behoerde_Hnr" => $behoerde_hausnummer,
            "Behoerde_PLZ" => $behoerde_plz,
            "Behoerde_Ort" => $behoerde_ort,
            "Behoerde_Art_Name" => $behoerde_art_name,
            "Behoerde_IBAN" => $behoerde_iban,
            "Behoerde_Bankname" => $behoerde_bankname,
            "Behoerde_BIC" => $behoerde_bic,
            "Adressat_Name" => $adressat_name,
            "Adressat_Strasse" => $adressat_strasse,
            "Adressat_Hnr" => $adressat_hausnummer,
            "Adressat_PLZ" => $adressat_plz,
            "Adressat_Ort" => $adressat_ort,
            "Adressat_Ort" => $adressat_ort,
            "Erklaerung_Datum" => $erklaerung_datum,
            "Festsetzung_Freitext" => $festsetzungsSammelbescheidDaten->getFreitext()
        ];
        
        //write the word file
        writeFestsetzungsWordFile($gui, PLUGINS . FESTSETZUNG_BESCHEID_PATH, $word_file, $parameter, $festsetzungsSammelbescheidDaten);
        
        return $word_file_name;
    }
    
    return null;
}
?>

<div id="<?php echo WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL ?>" class="tabcontent" style="display: inline">

	<form action="index.php" id="aufforderung_form" accept-charset="" method="POST">
	
		<?php
		      $go=WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL;
		      $showAdressat=true;
		      include_once ('includes/wasserentnahmebenutzer_header.php');
		?>
    	
		<table id="wasserentnahmebenutzer_tabelle">
			<tr>
				<th>Anlage</th>
    			<th>Wasserrechtliche Zulassung</th>
    			<th>Benutzung</th>
    			<th>Aufforderung</th>
    			<th>Erklärung</th>
    			<th>Entnahmemenge</th>
    			<th>Entgelt</th>
    			<th>Festsetzung</th>
    			<th>Auswahl</th>
    			<th>Bescheid</th>
    			<th style="background-color: inherit; width: 10px"></th>
    			<th>Entgelt eingenommen</th>
    			<th>Entgelt abgeführt</th>
    		</tr>
    		<?php 
        		
    		      if(!empty($wasserrechtlicheZulassungen))
        		  {
        		      //var_dump($wasserrechtlicheZulassungen);
        		      foreach($wasserrechtlicheZulassungen AS $wrz)
        		      {
        		          if(!empty($wrz) && in_array($getYear, $wrz->gueltigkeitsJahre))
        		          {
        		              if(empty($getBehoerde) || $getBehoerde === $wrz->zustaendigeBehoerde->getId())
        		              {
        		                  if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
        		                  {
        		                      $gewaesserbenutzungen = $wrz->gewaesserbenutzungen;
        		                      
        		                      $gewaesserbenutzungen_count = 1;
        		                      if(!empty($gewaesserbenutzungen))
        		                      {
        		                          $gewaesserbenutzungen_count = count($gewaesserbenutzungen);
        		                      }
        		                      
        		                      for ($i = 0; $i < $gewaesserbenutzungen_count; $i++) 
        		                      {
        		                          $gewaesserbenutzung = $gewaesserbenutzungen[$i];
        		                          
        		                          if(!empty($gewaesserbenutzung))
        		                          {
        		                              ?>
        		                         <tr>
        		                           <td>
        		                              <?php
        		                              echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . ANLAGEN_LAYER_ID . '&value_' . ANLAGEN_OPERATOR_ID . '=' . $wrz->anlage->getId() . '&operator_' . ANLAGEN_OPERATOR_ID . '==">' . $wrz->anlage->getName() . '</a>';
        		                              ?>
                    		          	    </td>
                    		          		<td>
                    		          			<?php 
                    		          			     echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . WRZ_LAYER_ID . '&value_' . WRZ_OPERATOR_ID . '=' . $wrz->getId() . '&operator_' . WRZ_OPERATOR_ID . '==">' . $wrz->getBezeichnung() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			echo '<a href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . GEWAESSERBENUTZUNGEN_LAYER_ID . '&value_' . GEWAESSERBENUTZUNGEN_OPERATOR_ID . '=' . $gewaesserbenutzung->getId() . '&operator_' . GEWAESSERBENUTZUNGEN_OPERATOR_ID . '==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $gewaesserbenutzung->getAufforderungDatumHTML($getYear);
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $gewaesserbenutzung->getErklaerungDatumHTML($getYear);
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     if($gewaesserbenutzung->isFestsetzungFreigegeben($getYear))
                    		          			     {
//                     		          			         $gewaesserbenutzung->getUmfangAllerTeilbenutzungen()
                    		          			         $entnahmemenge = $gewaesserbenutzung->getFestsetzungSummeEntnahmemengen($getYear);
                    		          			         $gesamtEntnahmemenge = $gesamtEntnahmemenge + $entnahmemenge;
                    		          			         
                    		          			         echo $entnahmemenge;
                    		          			     }    
                                                ?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			    if($gewaesserbenutzung->isFestsetzungFreigegeben($getYear))
                        		          			{
                        		          			    $entgelt = $gewaesserbenutzung->getFestsetzungSummeEntgelt($getYear);
                        		          			    $gesamtEntgelt = $gesamtEntgelt + $entgelt;
                        		          			    echo $entgelt;
                        		          			}
                                                ?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     if($gewaesserbenutzung->isFestsetzungFreigegeben($getYear))
                    		          			     {?>
                    		          			     	<a href="<?php echo $this->actual_link . '?go=' . WASSERENTNAHMEENTGELT_FESTSETZUNG_URL . '&' . GET_FESTSETZUNG_URL . '=' . $wrz->getId() . '_' . $gewaesserbenutzung->getId() . '_' . $getYear ?>"><?php echo $gewaesserbenutzung->getFestsetzungDatum($getYear); ?></a>
                    		          			     <?php
                    		          			     }
                    		          			
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     if($gewaesserbenutzung->isFestsetzungFreigegeben($getYear) && !$gewaesserbenutzung->isFestsetzungDokumentErstellt($getYear))
                    		          			     {?>
                    		          			     	<input type="checkbox" name="<?php echo AUSWAHL_CHECKBOX_URL ?>[]" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $getYear; ?>" />
                    		          			     <?php
                    		          			     }
                    		          			
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     echo $gewaesserbenutzung->getFestsetzungDokumentDatum($getYear);
                    		          			?>
                    		          		</td>
                    		          		<td style="background-color: inherit; width: 10px">
                    		          		</td>
                    		          		<td>
                    		          		</td>
                    		          		<td>
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
    		<tr>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit">Summe:</td>
          		<td style="background-color: inherit"><input class="wasserentnahmebenutzer_entgeltbescheid_inputfield_small" type="text" id="summe_entnahmemengen" name="summe_zugelassene_entnahmemengen" readonly="readonly" value="<?php echo $gesamtEntnahmemenge === 0 ? "" : $gesamtEntnahmemenge ?>"></td>
          		<td style="background-color: inherit"><input class="wasserentnahmebenutzer_entgeltbescheid_inputfield_small" type="text" id="summe_entgelt" name="summe_entgelt" readonly="readonly" value="<?php echo $gesamtEntgelt === 0 ? "" : $gesamtEntgelt ?>"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit; width: 10px"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
    		</tr>
    	</table>
    	
    	<div class="wasserrecht_display_table" style="margin-top: 10px;float: right;">
    		
    		<div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Sammelentgeltbescheid für ausgewählte Entnahmebenutzungen erstellen:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_row_spacer"></div>
			</div>
    		 <div class="wasserrecht_display_table_row">
        		<div class="wasserrecht_display_table_cell_caption">
        			<input type="hidden" name="go" value="<?php echo WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL ?>">
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
        			<input type="submit" value="Entgeltscheid erstellen!" id="<?php echo ENTGELTBESCHEID_ERSTELLEN_URL ?>" name="<?php echo ENTGELTBESCHEID_ERSTELLEN_URL ?>" <?php echo $gesamtEntnahmemenge < 2000 ? "disabled='disabled'" : "" ?> />
        		</div>
             </div>
             
             <div class="wasserrecht_display_table_row">
		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		<div class="wasserrecht_display_table_cell_spacer"></div>
		   		<div class="wasserrecht_display_table_row_spacer"></div>
   			</div>
    	
       		  <div class="wasserrecht_display_table_row">
           			<div class="wasserrecht_display_table_cell_caption">Abgelegte Sammelbescheide</div>
    		  </div>
    		  <?php 
    		    if(!empty($wasserrechtlicheZulassungen))
    			{
    			    $dokumentIds = array();
    			    $festsetzungDokumente = array();
    			    
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
    			                    $this->log->log_debug('gwbs count: ' . var_export(sizeof($gwbs), true));
    			                    
    			                    if(!empty($gwbs))
    			                    {
    			                        foreach ($gwbs as $gwb)
    			                        {
    			                            if(!empty($gwb))
    			                            {
    			                                $this->log->log_debug('gwb id: ' . var_export($gwb->getId(), true));
    			                                
    			                                $festsetzungsDokumentErstellt = $gwb->isFestsetzungDokumentErstellt($getYear);
    			                                $this->log->log_debug('festsetzungsDokumentErstellt: ' . var_export($festsetzungsDokumentErstellt, true));
    			                                
    			                                if($festsetzungsDokumentErstellt)
    			                                {
    			                                    $festsetzungDokument = $gwb->getFestsetzungDokument($getYear);
    			                                    if(!empty($festsetzungDokument))
    			                                    {
    			                                        if(!in_array($festsetzungDokument->getId(), $dokumentIds))
    			                                        {
    			                                            $dokumentIds[] = $festsetzungDokument->getId();
    			                                            $festsetzungDokument->addWrz_id($wrz->getId());
    			                                            $festsetzungDokumente[$festsetzungDokument->getId()] = $festsetzungDokument;
    			                                        }
    			                                        else
    			                                        {
    			                                            $festsetzungDokumente[$festsetzungDokument->getId()]->addWrz_id($wrz->getId());
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
    			    
    			    if(count($festsetzungDokumente) > 0)
    			    {
    			        foreach ($festsetzungDokumente as $festsetzungDokument)
    			        {
    			            if(!empty($festsetzungDokument))
    			            {
    			                $absoluteURL = getDocumentUrlFromPath($this, $festsetzungDokument->getPfad())
    			                
    			                ?>
			                    <div class="wasserrecht_display_table_row">
                					<div class="wasserrecht_display_table_cell_caption">
                					<?php
                					   echo '<a href="' . $absoluteURL . '" target="_blank">' . $festsetzungDokument->getName() . ', WrZs: (' . $festsetzungDokument->getWrz_idsString() . ')</a>';
                					?>
                           			</div>
                				</div>
			                	<?php 
    			            }
    			         }
    			    }
    			}
    		?>
			
			<div class="wasserrecht_display_table_row">
		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		<div class="wasserrecht_display_table_cell_spacer"></div>
		   		<div class="wasserrecht_display_table_row_spacer"></div>
   			</div>
			
    		 <div class="wasserrecht_display_table_row">
        		<div class="wasserrecht_display_table_cell_caption">
        			<input type="submit" value="Verwaltungsaufwand beantragen!" id="<?php echo VERWALTUNGSAUFWAND_BEANTRAGEN_URL ?>" name="<?php echo VERWALTUNGSAUFWAND_BEANTRAGEN_URL ?>" />
        		</div>
             </div>
			
       </div>
    	
    </form>

</div>