<?php 
$tab1_id="wasserentnahmeentgelt_erklaerung_der_entnahme";
$tab1_name="Erklärung der Entnahme";
$tab1_active=true;
$tab2_id="wasserentnahmeentgelt_festsetzung";
$tab2_name="Festsetzung";
$tab2_active=false;
include_once ('includes/header.php'); 
?>

<?php
$wrz = null;
$erklaerungWrz = new WasserrechtlicheZulassungen($this);
		
// 		  print_r($_REQUEST); 
		  
// 		  if($_SERVER ["REQUEST_METHOD"] == "POST")
// 		  {
// 		      print_r($_POST);

              foreach($_REQUEST as $key => $value)
		      {
		          if(substr($key, 0, strlen($key) - 1) === "erklaerung_")
		          {
		              $erklaerungWrzId = substr($value, strlen($value) - 1, strlen($value));
		              $wrz = $erklaerungWrz->find_by_id($this, 'id', $erklaerungWrzId);
// 		              echo "<br />erklaerungWrzId: " . $erklaerungWrzId;
		              if(!empty($wrz) && empty($wrz->getErklaerungDatum()))
		              {
		                  //echo $erklaerungWrz2->toString();
		                  $wrz->insertErklaerungDatum();
		              }
		              
		              break;
		          }
		      }
// 		  }

//try to find the first WRZ if, no wrz was given
if(empty($wrz))
{
    $results = $erklaerungWrz->find_where('1=1', 'id');
    
    if(!empty($results) && count($results) > 0)
    {
        $wrz = $results[0];
    }
}

?>

<?php 

if(!empty($wrz))
{
    $wrz->getDependentObjects($this, $wrz)
    
    ?>
    
    <div id="wasserentnahmeentgelt_erklaerung_der_entnahme" class="tabcontent" style="display: block">
    
    		<form action="index.php" id="erklaerung_form" accept-charset="" method="POST">
    		
    			<div class="wasserrecht_display_table">
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Erhebungsjahr:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <div class="wasserrecht_display_table_cell_white"><?php echo $wrz->gueltigkeitsJahr ?></div>
                    </div>
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Behörde:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <?php 
                            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Behoerde'] . '&value_id=' . $wrz->behoerde->getId() . '&operator_id==">' . $wrz->behoerde->getName() .'</a>';
                        ?>
                    </div>
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Adressat:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <?php 
                            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Personen'] . '&value_personen_id=' . $wrz->adressat->getId() . '&operator_personen_id==">' . $wrz->adressat->getName() .'</a>';
                        ?>
                    </div>
                    
                    <div class="wasserrecht_display_table_row">
                    	<div class="wasserrecht_display_table_row_spacer"></div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <div class="wasserrecht_display_table_row_spacer"></div>
                    </div>
                    
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Anlage:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <?php 
                            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Anlagen'] . '&value_anlage_id=' . $wrz->anlagen->getId() . '&operator_anlage_id==">' . $wrz->anlagen->getName() . '</a>';
                        ?>
                    </div>
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Wasserrechtliche Zulassung:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <?php 
                            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Wasserrechtliche_Zulassungen'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getName() . '</a>';
                        ?>
                    </div>
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Benutzung:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <?php
                            if(!empty($wrz->gewaesserbenutzungen) && !empty($wrz->gewaesserbenutzungen[0]) && !empty($wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang))
                    		{
                    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Umfang'] . '&value_id=' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getId() . '&operator_id==">' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getUmfang() . '</a>';
                    		}
                    		else
                    		{
                    		    echo '<div class="wasserrecht_display_table_cell_white"></div>';
                    		}
                    	?>
                    </div>
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Hinweise:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <?php
                    		 echo '<a class="wasserrecht_display_table_cell_white" style="color: red; text-decoration: underline;" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Wasserrechtliche_Zulassungen'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->gueltigkeit->getHinweis() . '</a>';
                    	?>
                    </div>
                    
                   <div class="wasserrecht_display_table_row">
                    	<div class="wasserrecht_display_table_row_spacer"></div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <div class="wasserrecht_display_table_row_spacer"></div>
                    </div>
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Benutzungsart:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                         <?php
                             if(!empty($wrz->gewaesserbenutzungen) && !empty($wrz->gewaesserbenutzungen[0]) && !empty($wrz->gewaesserbenutzungen[0]->gewaesserbenutzungArt))
                    		 {
                    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Art'] . '&value_id=' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungArt->getId() . '&operator_id==">' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungArt->getName() . '</a>';
                    		 }
                    		 else
                    		 {
                    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
                    		 }
                    	?>
                    </div>
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Benutzungszweck:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                         <?php
                             if(!empty($wrz->gewaesserbenutzungen) && !empty($wrz->gewaesserbenutzungen[0]) && !empty($wrz->gewaesserbenutzungen[0]->gewaesserbenutzungZweck))
                    		 {
                    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Zweck'] . '&value_id=' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungZweck->getId() . '&operator_id==">' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungZweck->getName() . '</a>';
                    		 }
                    		 else
                    		 {
                    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
                    		 }
                    	?>
                    </div>
                    <div class="wasserrecht_display_table_cell_caption">Benutzungsumfang:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                         <?php
                             if(!empty($wrz->gewaesserbenutzungen) && !empty($wrz->gewaesserbenutzungen[0]) && !empty($wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang))
                    		 {
                    		     echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Umfang'] . '&value_id=' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getId() . '&operator_id==">' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getUmfang() . '</a>';
                    		 }
                    		 else
                    		 {
                    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
                    		 }
                    	?>
                    </div>
                </div>
                
                <table class="wasserrecht_table" style="margin-top: 20px">
                  <tr>
                  	<th></th>
                    <th>Erklärter Teil-Benutzungsart</th>
                    <th>Erklärter Teil-Benutzungszweck</th>
                    <th>Erklärter Teil-Benutzungsumfang in m³/a</th>
                    <th>Wiedereinleitung</th>
                    <th>Mengenbestimmung</th>
                  </tr>
                  <?php 
                      for ($i = 1; $i <= 5; $i++) 
                      {?>
                      
                      	<tr>
                          	<td><?php echo $i; ?>.</td>
                            <td>
                            	<select class="wasserrecht_table_inputfield" name="gewaesserbenutzungsart_<?php echo $i; ?>">
                            		<option value='bitte_auswaehlen' selected="selected">Bitte auswählen</option>
                            		<?php 
                            		  $gwba = new GewaesserbenutzungenArt($this);
                            		  $gewaesserbenutzungenArten = $gwba->find_where('1=1', 'id');
                            		  if(!empty($gewaesserbenutzungenArten) && count($gewaesserbenutzungenArten) > 0)
                            		  {
                            		      foreach ($gewaesserbenutzungenArten AS $gewaesserbenutzungenArt)
                            		      {
                            		          echo '<option value='. $gewaesserbenutzungenArt->getId() . '>' . $gewaesserbenutzungenArt->getName() . "</option>";
                            		      }    
                            		  }
                            		?>
                            	</select>
                            </td>
                            <td>
                            	<select class="wasserrecht_table_inputfield" name="gewaesserbenutzungszweck_<?php echo $i; ?>">
                            		<option value='bitte_auswaehlen' selected="selected">Bitte auswählen</option>
                            		<?php 
                            		  $gwbz = new GewaesserbenutzungenZweck($this);
                            		  $gewaesserbenutzungenZwecke = $gwbz->find_where('1=1', 'id');
                            		  if(!empty($gewaesserbenutzungenZwecke) && count($gewaesserbenutzungenZwecke) > 0)
                            		  {
                            		      foreach ($gewaesserbenutzungenZwecke AS $gewaesserbenutzungenZweck)
                            		      {
                            		          echo '<option value='. $gewaesserbenutzungenZweck->getId() . '>' . $gewaesserbenutzungenZweck->getName() . "</option>";
                            		      }    
                            		  }
                            		?>
                            	</select>
                            </td>
                            <td>
                            	<input  class="wasserrecht_table_inputfield" type="text" id="numberField" name="gewaesserbenutzungsumfang_<?php echo $i; ?>">
                            </td>
                            <td>
                            	<select class="wasserrecht_table_inputfield" name="wiedereinleitung_<?php echo $i; ?>">
                            		<option value="ja">ja</option>
                            		<option value="nein">nein</option>
                            	</select>
                            </td>
                            <td>
                            	<select class="wasserrecht_table_inputfield" name="mengenbestimmung_<?php echo $i; ?>">
                            		<option value="messung">Messung</option>
                            		<option value="berechnung">Berechnung</option>
                            		<option value="schaetzung">Schätzung</option>
                            	</select>
                            </td>
                          </tr>
                      <?php }
                  ?>
                </table>
                
                <div class="wasserrecht_display_table" style="margin-top: 20px; margin-left: 15px">
                
                    <div class="wasserrecht_display_table_row">
                        <div class="wasserrecht_display_table_cell_caption">Erklärung oder Schätzung:</div>
                        <div class="wasserrecht_display_table_cell_spacer"></div>
                        <div class="wasserrecht_display_table_cell_white">
                            <select class="wasserrecht_display_table_cell_white">
                            	<option>Erklärung</option>
                            	<option>Schätzung</option>
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
                			<input type="hidden" name="go" value="wasserentnahmeentgelt">
    						<button class="wasserrecht_button" name="erklaerung_freigeben_<?php echo $wrz->getId(); ?>" value="erklaerung_freigeben_<?php echo $wrz->getId(); ?>" type="submit" id="erklaerung_freigeben_button_<?php echo $wrz->getId(); ?>">Erklärung freigeben</button>
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