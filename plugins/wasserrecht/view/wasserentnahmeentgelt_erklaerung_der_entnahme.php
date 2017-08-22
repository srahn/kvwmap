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
$gewaesserbenutzung = null;
		
//print_r($_REQUEST); 
		  
if($_SERVER ["REQUEST_METHOD"] == "POST")
{
//     print_r($_POST);

    foreach($_REQUEST as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(startsWith($keyEscaped, "erklaerung_freigeben_"))
        {
            $lastIndex = strripos($keyEscaped, "_");
            $erklaerungFreigebenWrzId = substr($keyEscaped, $lastIndex + 1);
//             echo "<br />lastIndex: " . $lastIndex . " erklaerungFreigebenWrzId: " . $erklaerungFreigebenWrzId;
            $erklaerungFreigebenWrz = new WasserrechtlicheZulassungen($this);
            $wrz = $erklaerungFreigebenWrz->find_by_id($this, 'id', $erklaerungFreigebenWrzId);
            if(!empty($wrz))
            {
                $gewaesserbenutzungId=substr($keyEscaped, strlen("erklaerung_freigeben_"), $lastIndex - strlen("erklaerung_freigeben_"));
                //echo "<br />gewaesserbenutzungId: " . $gewaesserbenutzungId;
                $gb = new Gewaesserbenutzungen($this);
                $gewaesserbenutzungen = $gb->find_where_with_subtables('id=' . $gewaesserbenutzungId);
                if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0 && !empty($gewaesserbenutzungen[0]))
                {
                    $gewaesserbenutzung = $gewaesserbenutzungen[0];
//                     $gewaesserbenutzungErklaerungFreigegebenId = $gewaesserbenutzung->getId();

                    $teilgewaesserbenutzungsart = htmlspecialchars($_POST["teilgewaesserbenutzungsart"]);
//                     echo "teilgewaesserbenutzungsart: " . $teilgewaesserbenutzungsart;

                    for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++) 
                    {
                        $gewaesserbenutzungsart = htmlspecialchars($_POST["gewaesserbenutzungsart_" . $i]);
                        $gewaesserbenutzungszweck = htmlspecialchars($_POST["gewaesserbenutzungszweck_" . $i]);
                        $gewaesserbenutzungsumfang = htmlspecialchars($_POST["gewaesserbenutzungsumfang_" . $i]);
                        $wiedereinleitung = htmlspecialchars($_POST["wiedereinleitung_" . $i]);
                        $mengenbestimmung = htmlspecialchars($_POST["mengenbestimmung_" . $i]);
                        
                        if(!empty($gewaesserbenutzungsart) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungsart) !== 0
                            && !empty($gewaesserbenutzungszweck) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungszweck)  !== 0
                            && !empty($gewaesserbenutzungsumfang) && is_numeric($gewaesserbenutzungsumfang))
                        {
                            //update an existing teilgewaesserbenutzung
                            if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen[$i - 1]))
                            {
                                $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[$i - 1];
                                $teilgewaesserbenutzungId = $teilgewaesserbenutzung->updateTeilgewaesserbenutzung($gewaesserbenutzung->getId(),
                                    $gewaesserbenutzungsart, $gewaesserbenutzungszweck, $gewaesserbenutzungsumfang, $wiedereinleitung, $mengenbestimmung, $teilgewaesserbenutzungsart);
                                
                                $this->add_message('notice', 'Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId . ') erfolgreich geändert!');
                            }
                            //else --> if not there --> create one 
                            else
                            {
                                $teilgewaesserbenutzung = new Teilgewaesserbenutzungen($this);
                                $teilgewaesserbenutzungId = $teilgewaesserbenutzung->createTeilgewaesserbenutzung($gewaesserbenutzung->getId(),
                                    $gewaesserbenutzungsart, $gewaesserbenutzungszweck, $gewaesserbenutzungsumfang, $wiedereinleitung, $mengenbestimmung, $teilgewaesserbenutzungsart);
                                
                                $this->add_message('notice', 'Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId .') erfolgreich eingetragen!');
                            }
                        }
//                         echo $i;
                    }
                    
                    //update gewaesserbenutzungen, because teilgewaesserbenutzungen where added
                    $gewaesserbenutzungen = $gb->find_where_with_subtables('id=' . $gewaesserbenutzungId);
                    $gewaesserbenutzung = $gewaesserbenutzungen[0];
                }
                
                if(empty($wrz->getErklaerungDatum()))
                {
                    //echo $erklaerungWrz2->toString();
                    $wrz->insertErklaerungDatum();
                }
            }
            
            break;
        }
        elseif(startsWith($keyEscaped, "erklaerung_"))
		{
		    $lastIndex = strripos($keyEscaped, "_");
		    $erklaerungWrzId = substr($keyEscaped, $lastIndex + 1);
// 		    echo "<br />lastIndex: " . $lastIndex . " erklaerungWrzId: " . $erklaerungWrzId;
		    $erklaerungWrz = new WasserrechtlicheZulassungen($this);
		    $wrz = $erklaerungWrz->find_by_id($this, 'id', $erklaerungWrzId);
		    if(!empty($wrz))
		    {
		        $gewaesserbenutzungId = substr($keyEscaped, strlen("erklaerung_"), $lastIndex - strlen("erklaerung_"));
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

?>

<?php 

if(!empty($wrz))
{
    $wrz->getDependentObjects($this, $wrz);
    if(empty($gewaesserbenutzung) && !empty($wrz->gewaesserbenutzungen) && count($wrz->gewaesserbenutzungen) > 0 && !empty($wrz->gewaesserbenutzungen[0]))
    {
        $gewaesserbenutzung = $wrz->gewaesserbenutzungen[0];
    }
    
    ?>
    
    <div id="wasserentnahmeentgelt_erklaerung_der_entnahme" class="tabcontent" style="display: block">
    
    		<form action="index.php" id="erklaerung_freigeben_form" accept-charset="" method="POST">
    		
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
                            if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungUmfang))
                    		{
                    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Umfang'] . '&value_id=' . $gewaesserbenutzung->gewaesserbenutzungUmfang->getId() . '&operator_id==">' . $gewaesserbenutzung->gewaesserbenutzungUmfang->getUmfang() . '</a>';
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
                             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungArt))
                    		 {
                    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Art'] . '&value_id=' . $gewaesserbenutzung->gewaesserbenutzungArt->getId() . '&operator_id==">' . $gewaesserbenutzung->gewaesserbenutzungArt->getName() . '</a>';
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
                             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungZweck))
                    		 {
                    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Zweck'] . '&value_id=' . $gewaesserbenutzung->gewaesserbenutzungZweck->getId() . '&operator_id==">' . $gewaesserbenutzung->gewaesserbenutzungZweck->getName() . '</a>';
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
                             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungUmfang))
                    		 {
                    		     echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Umfang'] . '&value_id=' . $gewaesserbenutzung->gewaesserbenutzungUmfang->getId() . '&operator_id==">' . $gewaesserbenutzung->gewaesserbenutzungUmfang->getUmfang() . '</a>';
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
                      if(!empty($gewaesserbenutzung))
                      {
                          for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++) 
                          {
                          
                              $teilgewaesserbenutzung = null;
                              if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0 
                                  && count($gewaesserbenutzung->teilgewaesserbenutzungen) > ($i - 1) && !empty($gewaesserbenutzung->teilgewaesserbenutzungen[$i -1]))
                              {
                                  $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[$i - 1];
    //                               var_dump($teilgewaesserbenutzung);
                              }
                              ?>
                          
                          	<tr>
                              	<td><?php echo $i; ?>.</td>
                                <td>
                                	<select class="wasserrecht_table_inputfield" name="gewaesserbenutzungsart_<?php echo $i; ?>">
                                		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'>Bitte auswählen</option>
                                		<?php 
                                		  $gwba = new GewaesserbenutzungenArt($this);
                                		  $gewaesserbenutzungenArten = $gwba->find_where('1=1', 'id');
                                		  if(!empty($gewaesserbenutzungenArten) && count($gewaesserbenutzungenArten) > 0)
                                		  {
                                		      foreach ($gewaesserbenutzungenArten AS $gewaesserbenutzungenArt)
                                		      {
                                		          echo '<option value="'. $gewaesserbenutzungenArt->getId() . '" ' . (!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->gewaesserbenutzungArt) && $teilgewaesserbenutzung->gewaesserbenutzungArt->getId() === $gewaesserbenutzungenArt->getId() ?  'selected' : '') . ' >' . $gewaesserbenutzungenArt->getName() . "</option>";
                                		      }    
                                		  }
                                		?>
                                	</select>
                                </td>
                                <td>
                                	<select class="wasserrecht_table_inputfield" name="gewaesserbenutzungszweck_<?php echo $i; ?>">
                                		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'>Bitte auswählen</option>
                                		<?php 
                                		  $gwbz = new GewaesserbenutzungenZweck($this);
                                		  $gewaesserbenutzungenZwecke = $gwbz->find_where('1=1', 'id');
                                		  if(!empty($gewaesserbenutzungenZwecke) && count($gewaesserbenutzungenZwecke) > 0)
                                		  {
                                		      foreach ($gewaesserbenutzungenZwecke AS $gewaesserbenutzungenZweck)
                                		      {
                                		          echo '<option value="'. $gewaesserbenutzungenZweck->getId() . '" ' . (!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->gewaesserbenutzungZweck) && $teilgewaesserbenutzung->gewaesserbenutzungZweck->getId() === $gewaesserbenutzungenZweck->getId() ?  'selected' : '') . ' >' . $gewaesserbenutzungenZweck->getName() . "</option>";
                                		      }    
                                		  }
                                		?>
                                	</select>
                                </td>
                                <td>
                                	<input class="wasserrecht_table_inputfield" type="text" id="numberField" name="gewaesserbenutzungsumfang_<?php echo $i; ?>" value="<?php echo !empty($teilgewaesserbenutzung) ? $teilgewaesserbenutzung->getUmfang() : '' ?>">
                                </td>
                                <td>
                                	<select class="wasserrecht_table_inputfield" name="wiedereinleitung_<?php echo $i; ?>">
                                		<option value="true" <?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->getWiedereinleitungNutzer()) && $teilgewaesserbenutzung->getWiedereinleitungNutzer() === "t" ?  'selected' : ''?>>ja</option>
                                		<option value="false" <?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->getWiedereinleitungNutzer()) && $teilgewaesserbenutzung->getWiedereinleitungNutzer()  === "f" ?  'selected' : ''?>>nein</option>
                                	</select>
                                </td>
                                <td>
                                	<select class="wasserrecht_table_inputfield" name="mengenbestimmung_<?php echo $i; ?>">
                                		<option value="1" <?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->mengenbestimmung) && $teilgewaesserbenutzung->mengenbestimmung->getId() === "1" ?  'selected' : ''?>>Messung</option>
                                		<option value="2" <?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->mengenbestimmung) && $teilgewaesserbenutzung->mengenbestimmung->getId() === "2" ?  'selected' : ''?>>Berechnung</option>
                                		<option value="3" <?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->mengenbestimmung) && $teilgewaesserbenutzung->mengenbestimmung->getId() === "3" ?  'selected' : ''?>>Schätzung</option>
                                	</select>
                                </td>
                              </tr>
                          <?php 
                          }
                      ?>
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
		   				<div class="wasserrecht_display_table_cell_caption">
                			<input type="hidden" name="go" value="wasserentnahmeentgelt">
    						<button class="wasserrecht_button" name="erklaerung_freigeben_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>" value="erklaerung_freigeben_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>" type="submit" id="erklaerung_freigeben_button_<?php echo (empty($gewaesserbenutzung) ? "0" : $gewaesserbenutzung->getId()) . "_" . $wrz->getId(); ?>">Erklärung freigeben</button>
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
                
                <?php 
                      }
               		?>
    		</form>
    </div>
    
    <?php
}
else
{
    echo '<h1 style=\"color: red;\">Keine Wasserrechtliche Zulassung gefunden!<h1>';
}

?>