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
                    		          		</td>
                    		          		<td>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     echo $wrz->getFestsetzungDatum();
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<input type="checkbox" name="auswahl_checkbox_<?php echo $wrz->getId(); ?>">
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
    	</table>
    	
    	<div class="wasserrecht_display_table" style="margin-top: 10px;">
	   		  <div class="wasserrecht_display_table_row">
           			<div class="wasserrecht_display_table_cell_caption">Abgelegte Festsetzungen</div>
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
       </div>
    	
    </form>

</div>