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
                    		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Gewaesserbenutzungen'] . '&value_id=' . $gewaesserbenutzung->getId() . '&operator_id==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
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
                    		          			<input type="checkbox" name="auswahl_checkbox_<?php echo $wrz->getId(); ?>">
                    		          		</td>
                    		          		<td>
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
        			<input type="submit" value="Entgeltscheid erstellen!" id="entgeltscheid_button" name="entgeltscheid" />
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
			    if(!empty($wrz->festsetzung_dokument))
				{?>
    				<div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">
        					<?php
        					   echo '<a href="' . $this->actual_link . WASSERRECHT_DOCUMENT_URL_PATH . $wrz->festsetzung_dokument->getPfad() . '" target="_blank">' . $wrz->festsetzung_dokument->getName() . '</a>';
        					?>
               			</div>
    				</div>
			<?php
				}
			
			?>
			
			<div class="wasserrecht_display_table_row">
		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		<div class="wasserrecht_display_table_cell_spacer"></div>
		   		<div class="wasserrecht_display_table_row_spacer"></div>
   			</div>
			
    		 <div class="wasserrecht_display_table_row">
        		<div class="wasserrecht_display_table_cell_caption">
        			<input type="submit" value="Verwaltungsaufwand beantragen!" id="entgeltscheid_button" name="verwaltungsaufwand" />
        		</div>
             </div>
			
       </div>
    	
    </form>

</div>