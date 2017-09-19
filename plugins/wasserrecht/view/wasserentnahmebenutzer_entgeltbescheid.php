<?php 
$tab1_id="wasserentnahmebenutzer_aufforderung_zur_erklaerung";
$tab1_name="Aufforderung zur Erklärung";
$tab1_active=false;
$tab1_visible=true;
$tab2_id="wasserentnahmebenutzer_entgeltbescheid";
$tab2_name="Entgeltbescheid";
$tab2_active=true;
$tab2_visible=true;
include_once ('includes/header.php');

$gesamtEntnahmemenge = 0;
$gesamtEntgelt = 0;

if($_SERVER ["REQUEST_METHOD"] == "POST")
{
//     print_r($_POST);
    
    $entgeltbescheid_erstellen = htmlspecialchars($_POST["entgeltscheid_erstellen"]);
    $auswahl_checkbox_array = $_POST["auswahl_checkbox"];
    if(!empty($entgeltbescheid_erstellen) && !empty($auswahl_checkbox_array) && is_array($auswahl_checkbox_array))
    {
        $wrzs = array();
        $gwb = array();
        
        foreach ($auswahl_checkbox_array as $auswahl_checkbox) 
        {
            if(!empty($auswahl_checkbox))
            {
                $auswahl_checkbox_escaped = htmlspecialchars($auswahl_checkbox);
                
                $festsetzungWrz = new WasserrechtlicheZulassungen($this);
                $wrz = $festsetzungWrz->find_by_id($this, 'id', $auswahl_checkbox_escaped);
                // 		    var_dump($wrz);
                // 		    echo "<br />wrz id: " . $wrz->getId();
                if(!empty($wrz))
                {
                    $wrzs[] = $wrz;
                    
                    // 		        echo "<br />wrz id: " . $wrz->getId();
                    $gb = new Gewaesserbenutzungen($this);
                    $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
                    $gewaesserbenutzung = null;
                    if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0 && !empty($gewaesserbenutzungen[0]))
                    {
                        $gewaesserbenutzung = $gewaesserbenutzungen[0];
                    }
                    // 		        echo "<br />gewaesserbenutzung: " . $gewaesserbenutzung[0]->getId();
                    
                    if(!empty($gewaesserbenutzung))
                    {
                        $gwb[] = $gewaesserbenutzung;
                    }
                }
            }
        }
        
        $returnValue = festsetzung_erstellen($this, $wrzs, $gwb);
        if($returnValue)
        {
            $this->add_message('notice', 'Festetzung erfolgreich erstellt!');
        }
        else
        {
            $this->add_message('error', 'Der Festsetzungsbescheid konnte nicht erstellt werden!');
        }
    }
}

function festsetzung_erstellen(&$gui, &$wrzs, &$gewaesserbenutzungen)
{
    if(count($wrzs) > 0 && count($gewaesserbenutzungen) > 0 && count($wrzs) === count($gewaesserbenutzungen))
    {
        foreach ($wrzs as $wrz)
        {
            if(!empty($wrz))
            {
                foreach ($gewaesserbenutzungen as $gewaesserbenutzung)
                {
                    if(!empty($gewaesserbenutzung))
                    {
                        $teilgewasserbenutzung = null;
                        if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0)
                        {
                            $teilgewasserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[0];
                        }
                        
                        if(!empty($teilgewasserbenutzung))
                        {
                            if($wrz->isFestsetzungFreigegeben() && !$wrz->isFestsetzungDokumentErstellt())
                            {
                                /**
                                 * Fesetzungsbescheid erstellen
                                 */
                                //Festsetzungsdokument anlegen
                                if(!empty($wrz->getFestsetzungDokument()))
                                {
                                    $oldFestsetzungsDocumentId = $wrz->getFestsetzungDokument();
                                    $wrz->deleteFestsetzungDokument();
                                    
                                    $festsetzung_delete_dokument = new Dokument($gui);
                                    $festsetzung_delete_dokument->deleteDocument($oldFestsetzungsDocumentId);
                                }
                                
                                //get a unique word file name
                                $uniqid = uniqid();
                                $word_file_name = $uniqid . ".docx";
                                $word_file = WASSERRECHT_DOCUMENT_PATH . $word_file_name;
                                
                                //get the parameter
                                $festsetzungsNutzer = $gui->user->Vorname . ' ' . $gui->user->Name;
                                $freitext = $teilgewasserbenutzung->getFreitext();
                                
                                //write the word file
                                writeFestsetzungsWordFile(PLUGINS . 'wasserrecht/templates/Festsetzung_Sammelbescheid.docx', $word_file, $festsetzungsNutzer, $freitext);
                                
                                //write the document path to the database
                                $festsetzung_dokument = new Dokument($gui);
                                $festsetzung_dokument_identifier = $festsetzung_dokument->createDocument('FestsetzungBescheid_' . $wrz->getId(), $word_file_name);
                                $wrz->insertFestsetzungDokument($festsetzung_dokument_identifier);
                                
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }
    
    return false;
}
?>

<div id="wasserentnahmebenutzer_entgeltbescheid" class="tabcontent" style="display: block">

	<form action="index.php" id="aufforderung_form" accept-charset="" method="POST">
	
		<?php 
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
        		
        		  if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
        		  {
        		      $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
        		      
        		      //var_dump($wasserrechtlicheZulassungen);
        		      foreach($wasserrechtlicheZulassungen AS $wrz)
        		      {
        		          if(!empty($wrz) && $getYear === $wrz->gueltigkeitsJahr)
        		          {
        		              if(empty($getBehoerde) || $getBehoerde === $wrz->behoerde->getId())
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
        		                          ?>
    		                          <tr>
                    		          		<td>
                    		          			<?php 
                    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_anlage_id=' . $wrz->anlagen->getId() . '&operator_anlage_id==">' . $wrz->anlagen->getName() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getBezeichnung() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     if(!empty($gewaesserbenutzung))
                    		          			     {
                    		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Gewässerbenutzungen'] . '&value_id=' . $gewaesserbenutzung->getId() . '&operator_id==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
                    		          			     }
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $wrz->getAufforderungDatumAbsendHTML();
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $wrz->getErklaerungDatumHTML();
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     if($wrz->isFestsetzungFreigegeben())
                    		          			     {
//                     		          			         $gewaesserbenutzung->getUmfangAllerTeilbenutzungen()
                    		          			         $entnahmemenge = $wrz->getFestsetzungSummeZugelasseneEntnahmemengen();
                    		          			         $gesamtEntnahmemenge = $gesamtEntnahmemenge + $entnahmemenge;
                    		          			         
                    		          			         echo $entnahmemenge;
                    		          			     }    
                                                ?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                        		          			if($wrz->isFestsetzungFreigegeben())
                        		          			{
                        		          			    $entgelt = $wrz->getFestsetzungSummeEntgelt();
                        		          			    $gesamtEntgelt = $gesamtEntgelt + $entgelt;
                        		          			    echo $entgelt;
                        		          			}
                                                ?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     if($wrz->isFestsetzungFreigegeben())
                    		          			     {?>
                    		          			     	<a href="<?php echo $this->actual_link . "?go=wasserentnahmeentgelt_festsetzung&getfestsetzung=" . $wrz->getId() ?>"><?php echo $wrz->getFestsetzungDatum(); ?></a>
                    		          			     <?php
                    		          			     }
                    		          			
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     if($wrz->isFestsetzungFreigegeben() && !$wrz->isFestsetzungDokumentErstellt())
                    		          			     {?>
                    		          			     	<input type="checkbox" name="auswahl_checkbox[]" value="<?php echo $wrz->getId(); ?>" />
                    		          			     <?php
                    		          			     }
                    		          			
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php echo $wrz->getFestsetzungDokumentDatum() ?>
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
        			<input type="hidden" name="go" value="wasserentnahmebenutzer_entgeltbescheid">
        			<input type="submit" value="Entgeltscheid erstellen!" id="entgeltscheid_erstellen" name="entgeltscheid_erstellen" />
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
    			if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
    			{
    			    $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
    			    
    			    foreach($wasserrechtlicheZulassungen AS $wrz)
    			    {
    			        if(!empty($wrz) && $getYear === $wrz->gueltigkeitsJahr)
    			        {
    			            if(empty($getBehoerde) || $getBehoerde === $wrz->behoerde->getId())
    			            {
    			                if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
    			                {
    			                    if($wrz->isFestsetzungDokumentErstellt())
    			                    {
    			                    ?>
    				                    <div class="wasserrecht_display_table_row">
                        					<div class="wasserrecht_display_table_cell_caption">
                        					<?php
                        					   echo '<a href="' . $this->actual_link . WASSERRECHT_DOCUMENT_URL_PATH . $wrz->festsetzung_dokument->getPfad() . '" target="_blank">' . $wrz->festsetzung_dokument->getName() . '</a>';
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
    		?>
			
			<div class="wasserrecht_display_table_row">
		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		<div class="wasserrecht_display_table_cell_spacer"></div>
		   		<div class="wasserrecht_display_table_row_spacer"></div>
   			</div>
			
    		 <div class="wasserrecht_display_table_row">
        		<div class="wasserrecht_display_table_cell_caption">
        			<input type="submit" value="Verwaltungsaufwand beantragen!" id="verwaltungsaufwand_beantragen" name="verwaltungsaufwand_beantragen" />
        		</div>
             </div>
			
       </div>
    	
    </form>

</div>