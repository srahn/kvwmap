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
            
            $aufforderungWrz1 = new WasserrechtlicheZulassungen($this);
            $aufforderungWrzId = $valueEscaped;
            //echo "<br />aufforderungWrzId: " . $aufforderungWrzId;
            $aufforderungWrz2 = $aufforderungWrz1->find_by_id($this, 'id', $aufforderungWrzId);
            if(!empty($aufforderungWrz2) && empty($aufforderungWrz2->getAufforderungDatumAbsend()))
            {
                //echo $aufforderungWrz2->toString();
                $aufforderungWrz2->insertAufforderungDatumAbsend();
                
                if(!empty($_POST['aufforderung']) && empty($aufforderungWrz2->getAufforderungDokument()))
                {
                    //get a unique word file name
                    $uniqid = uniqid();
                    $word_file_name = $uniqid . ".docx";
                    $word_file = WASSERRECHT_DOCUMENT_PATH . $word_file_name;
                    
                    //write the word file
                    writeAufforderungsWordFile(PLUGINS . 'wasserrecht/templates/Anhang_IV.docx', $word_file);
                    
                    //write the document path to the database
                    $aufforderung_dokument = new Dokument($this);
                    $aufforderung_document_identifier = $aufforderung_dokument->createDocument('Test Name' . $aufforderungWrzId, $word_file_name);
                    $aufforderungWrz2->insertAufforderungDokument($aufforderung_document_identifier);
                }
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
    			<td style="background-color: inherit;"><input type="checkbox" id="select_all_checkboxes" onchange="$('input:checkbox').not(this).prop('checked', this.checked);"></td>
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
        		                          
        		                          ?>
    		                          <tr>
                    		          		<td style="background-color: inherit;">
                    		          			<?php 
                        		          			if(empty($wrz->isAufforderungFreigegeben()))
                        		          			{
                        		          			    ?>
                        		          				<input type="checkbox" name="aufforderung_checkbox_<?php echo $wrz->getId(); ?>" value="<?php echo $wrz->getId(); ?>">
                        		          		<?php
                        		          			} 
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_anlage_id=' . $wrz->anlagen->getId() . '&operator_anlage_id==">' . $wrz->anlagen->getName() . '</a>';
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
                    		          			     if(!empty($gewaesserbenutzung))
                    		          			     {
                    		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Gewässerbenutzungen'] . '&value_id=' . $gewaesserbenutzung->getId() . '&operator_id==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
                    		          			     }
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			    if(!empty($gewaesserbenutzung))
                        		          			{
                        		          			    echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Gewässerbenutzungen'] . '&value_id=' . $gewaesserbenutzung->getId() . '&operator_id==">' . $gewaesserbenutzung->getKennummer() . '</a>';
                        		          			}
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo '<a style="color: red; text-decoration: underline;" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getHinweis() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $wrz->getAufforderungDatumAbsendHTML();
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          				<?php 
                    		          				    if(empty($wrz->getErklaerungDatum()))
                    		          				    {
                    		          				?>
                    		          					<!-- <form action="index.php" id="erklaerung_form_<?php echo $wrz->getId(); ?>" accept-charset="" method="POST"> -->
                    		          						<!-- <input type="hidden" name="go" value="wasserentnahmeentgelt_erklaerung_der_entnahme"> -->
<!--                         		          						<input type="hidden" name="post_action_1" value="wasserentnahmeentgelt_erklaerung_der_entnahme"> -->
    														<button name="erklaerung_<?php echo $wrz->getId(); ?>" value="<?php echo $wrz->getId(); ?>" type="submit" id="erklaerung_button_<?php echo $wrz->getId(); ?>">Erklärung</button>
    													<!-- </form> -->
                    		          				<?php
                    		          				    }
                    		          				?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $wrz->getErklaerungDatumHTML();
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
				                    if(!empty($wrz->aufforderung_dokument))
				                    {
				                    ?>
    				                    <div class="wasserrecht_display_table_row">
                        					<div class="wasserrecht_display_table_cell_caption">
                        					<?php
                        					   echo '<a href="' . $this->actual_link . WASSERRECHT_DOCUMENT_URL_PATH . $wrz->aufforderung_dokument->getPfad() . '" target="_blank">' . $wrz->aufforderung_dokument->getName() . '</a>';
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
       </div>
	</form>
</div>