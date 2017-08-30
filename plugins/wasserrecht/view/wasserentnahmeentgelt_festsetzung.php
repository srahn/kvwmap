<?php 
$tab1_id="wasserentnahmeentgelt_erklaerung_der_entnahme";
$tab1_name="Erklärung der Entnahme";
$tab1_active=false;
$tab2_id="wasserentnahmeentgelt_festsetzung";
$tab2_name="Festsetzung";
$tab2_active=true;
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
        
        if(startsWith($keyEscaped, "erklaerung_"))
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
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
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
                     <div class="wasserrecht_display_table_cell_caption">Zugelassene Entnahmemenge:</div>
                     <div class="wasserrecht_display_table_cell_spacer"></div>
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