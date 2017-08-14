<link rel="stylesheet" type="text/css" href="<?php echo $this->actual_link ?>/../plugins/wasserrecht/styles/wasserrecht_view.css">
<script type="text/javascript" src="<?php echo $this->actual_link ?>/../plugins/wasserrecht/javascript/wasserrecht_view.js"></script>

<div class="tab">
	<div class="tablinks active" onclick="changeTab(event, 'aufforderung_zur_erklaerung')">Aufforderung zur Erklärung</div>
	<div class="tablinks" onclick="changeTab(event, 'entgeltbescheid')">Entgeltbescheid</div>
</div>

<div id="aufforderung_zur_erklaerung" class="tabcontent" style="display: block">

		<?php
		
// 		  print_r($_REQUEST); 
		  
		  if($_SERVER ["REQUEST_METHOD"] == "POST")
		  {
// 		      print_r($_POST);

		      foreach($_POST as $key => $value)
		      {
		          if(substr($key, 0, strlen($key) - 1) === "auswahl_checkbox_")
		          {
// 		              echo '<br />Key = ' . $key . '<br />';
// 		              echo 'Value= ' . $value;
		              
		              $aufforderungWrz1 = new WasserrechtlicheZulassungen($this);
		              $aufforderungWrzId = substr($key, strlen($key) - 1, strlen($key));
// 		              echo "<br />aufforderungWrzId: " . $aufforderungWrzId;
		              $aufforderungWrz2 = $aufforderungWrz1->find_by_id($this, 'id', $aufforderungWrzId);
		              if(!empty($aufforderungWrz2) && empty($aufforderungWrz2->getAufforderungDatumAbsend()))
		              {
		                  //echo $aufforderungWrz2->toString();
		                  $aufforderungWrz2->insertAufforderungDatumAbsend();
		              }
		          }
		          elseif ($key === "aufforderung")
		          {
// 		              writeWordFile(PLUGINS . 'wasserrecht/templates/Anhang_IV.docx', PLUGINS . 'wasserrecht/results/test.docx');
		          }
		      }
		  }
		
		?>
		
		<form action="index.php" id="aufforderung_form" accept-charset="" method="POST">

    		<fieldset class="fieldset1">
    			<span>
        			<label for="erhebungsjahr">Erhebungsjahr:</label>
        			<select name="erhebungsjahr" onchange="setNewErhebungsJahr(this)">
            					<?php
            						$wasserrechtlicheZulassung = new WasserrechtlicheZulassungen($this);
            
                                    $wrzProGueltigkeitsJahr = $wasserrechtlicheZulassung->find_gueltigkeitsjahre($this);
                                    if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->gueltigkeitsJahre))
                                    {
                                        $gueltigkeitsjahre = $wrzProGueltigkeitsJahr->gueltigkeitsJahre;
                                        if(!empty($gueltigkeitsjahre) && count($gueltigkeitsjahre) > 0)
                                        {
                                            $getYear = !empty(htmlspecialchars($_REQUEST['year'])) ? htmlspecialchars($_REQUEST['year']) : $gueltigkeitsjahre[0];
                                            
                                            foreach($gueltigkeitsjahre AS $gueltigkeitsjahr)
                                            {
                                                echo '<option value='. $gueltigkeitsjahr . ' ' . ($gueltigkeitsjahr === $getYear ? "selected" : "") . '>' . $gueltigkeitsjahr . "</option>";
                                            }
                                            
                                            $nextyear = date('Y', strtotime('+1 year'));
                                            echo '<option value='. $nextyear . ' ' . ($nextyear === $getYear ? "selected" : "") . '>' . $nextyear . "</option>";
                                        }
                                        else
                                        {
                                            echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
                                        }
                                    }
                                    else
                                    {
                                        echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
                                    }
            					?>
            		</select>
    			</span>
    			<span>
            		<label for="behoerde">
            			<?php 
            			     $getBehoerde = !empty(htmlspecialchars($_REQUEST['behoerde'])) ? htmlspecialchars($_REQUEST['behoerde']) : null;
            			     
            			     if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen)
            			         && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->behoerde) && empty($getBehoerde))
            			     {
            			         $getBehoerde = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->behoerde->getId();
            			     }
            			     
            			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Behoerde'] . '&value_id=' . $getBehoerde . '&operator_id==">Behörde: </a>';
            			?>
            		</label>
            		<select name="behoerde" onchange="setNewBehoerde(this)">
            				<?php
            				
            				if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
            				{
            				    $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
            				    
            				    $behoerdeArray = array();
            				    
            				    //var_dump($wasserrechtlicheZulassungen);
            				    foreach($wasserrechtlicheZulassungen AS $wrz)
            				    {
            				        if(!empty($wrz) && $getYear === $wrz->gueltigkeitsJahr)
            				        {
            				            if(!empty($wrz->behoerde))
            				            {
            				                if(!in_array($wrz->behoerde->toString(), $behoerdeArray))
            				                {
            				                    $behoerdeArray[]=$wrz->behoerde->toString();
            				                    
            				                    echo '<option value='. $wrz->behoerde->getId() . ' ' . ($wrz->behoerde->getId() === $getBehoerde ? "selected" : "") . '>' . $wrz->behoerde->getName() . "</option>";
            				                }
            				            }
            				        }
            				        else
            				        {
            				            echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
            				            break;
            				        }
            				    }
            				    
            				}
            				
            				?>
            			</select>
            	</span>
            </fieldset>
            
            <fieldset class="fieldset2">
        		<span>
                	<label for="adressat">
            			<?php 
            			
            			    $getAdressat = !empty(htmlspecialchars($_REQUEST['adressat'])) ? htmlspecialchars($_REQUEST['adressat']) : null;
            			    $selectedAdressat = null;
            			    if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen) 
            			        && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->adressat) && empty($getAdressat))
            			    {
            			        $getAdressat = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->adressat->getId();
            			        $selectedAdressat = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[0]->adressat;
            			    }
            			
            			    echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Personen'] . '&value_personen_id=' . $getAdressat . '&operator_personen_id==">Adressat:</a>';
                        ?>
            		</label>
            		<select name="adressat" onchange="setNewAdressat(this)">
            				<?php
            				
            				if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
            				{
            				    $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
            				    
            				    $adressatArray = array();
            				    
            				    //var_dump($wasserrechtlicheZulassungen);
            				    foreach($wasserrechtlicheZulassungen AS $wrz)
            				    {
            				        if(!empty($wrz) && $getYear === $wrz->gueltigkeitsJahr)
            				        {
            				            if(!empty($wrz->adressat))
            				            {
            				                if(!in_array($wrz->adressat->toString(), $adressatArray))
            				                {
            				                    $adressatArray[]=$wrz->adressat->toString();
            				                    
            				                    echo '<option value='. $wrz->adressat->getId() . ' ' . ($wrz->adressat->getId() === $getAdressat ? "selected" : "") . '>' . $wrz->adressat->getName() . "</option>";
            				                    
            				                    if($wrz->adressat->getId() === $getAdressat)
            				                    {
            				                        $selectedAdressat = $wrz->adressat;
            				                    }
            				                }
            				            }
            				        }
            				        else
            				        {
            				            echo "<option>Keinen Eintrag in der Datenbank gefunden!</option>";
            				            break;
            				        }
            				    }
            				    
            				}
            				
            				?>
            		</select>
            	</span>
            	<span>
            		<label for="strasse">Straße:</label>
            		<input type="text" name="strasse" readonly="readonly" value="<?php echo $selectedAdressat->getAdresseStrasse(); ?>" />
            	</span>
            	<span>
            		<label for="hausnummer">Hausnummer:</label>
            		<input type="text" name="hausnummer" readonly="readonly" value="<?php echo $selectedAdressat->getAdresseHausnummer(); ?>" />
            	</span>
            	<span>
            		<label for="plz">PLZ:</label>
            		<input type="text" name="plz" readonly="readonly" value="<?php echo $selectedAdressat->getAdressePLZ(); ?>" />
            	</span>
            	<span>
            		<label for="ort">Ort:</label>
            		<input type="text" name="ort" readonly="readonly" value="<?php echo $selectedAdressat->getAdresseOrt(); ?>" />
            	</span>
        	</fieldset>
        	
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
        		<?php 
        		
        		  if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
        		  {
        		      $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
        		      
        		      //     		  var_dump($wasserrechtlicheZulassungen);
        		      foreach($wasserrechtlicheZulassungen AS $wrz)
        		      {
        		          if(!empty($wrz) && $getYear === $wrz->gueltigkeitsJahr)
        		          {
        		              if(empty($getBehoerde) || $getBehoerde === $wrz->behoerde->getId())
        		              {
        		                  if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
        		                  {
        		                      
    //     		                      var_dump($wrz);
            		                      ?>
                    		          	<tr>
                    		          		<td style="background-color: inherit;">
                    		          			<?php 
                        		          			$datumAbsend = $wrz->getAufforderungDatumAbsend();
                        		          			if(empty($datumAbsend))
                        		          			{
                        		          			    ?>
                        		          				<input type="checkbox" name="auswahl_checkbox_<?php echo $wrz->getId(); ?>">
                        		          		<?php
                        		          			} 
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Anlagen'] . '&value_anlage_id=' . $wrz->anlagen->getId() . '&operator_anlage_id==">' . $wrz->anlagen->getName() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Wasserrechtliche_Zulassungen'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getName() . '</a>';
                //     		          			     echo $wrz->getName();
                //     		          			     var_dump($wrz);
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     if(!empty($wrz->gewaesserbenutzungen) && !empty($wrz->gewaesserbenutzungen[0]) && !empty($wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang))
                    		          			     {
                    		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Umfang'] . '&value_id=' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getId() . '&operator_id==">' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getUmfang() . '</a>';
                    		          			     }
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                        		          			if(!empty($wrz->gewaesserbenutzungen) && !empty($wrz->gewaesserbenutzungen[0]))
                        		          			{
                    		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen'] . '&value_id=' . $wrz->gewaesserbenutzungen[0]->getId() . '&operator_id==">' . $wrz->gewaesserbenutzungen[0]->getKennummer() . '</a>';
                        		          			}
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo '<a style="color: red; text-decoration: underline;" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Wasserrechtliche_Zulassungen'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->gueltigkeit->getHinweis() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $wrz->getAufforderungDatumAbsendHTML();
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          				<form action="index.php" id="erklaerung_form_<?php echo $wrz->getId(); ?>" accept-charset="" method="POST">
                    		          					<input type="hidden" name="go" value="wasserentnahmeentgelt">
    													<button name="erklaerung_<?php echo $wrz->getId(); ?>" value="erklaerung_<?php echo $wrz->getId(); ?>" type="submit" id="erklaerung_button_<?php echo $wrz->getId(); ?>">Erklärung</button>
    												</form>
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
        		?>
    		</table>
    		
    		<p style="float: left; margin-top: 20px">
    			<label for="aufforderung" style="float: left">Sammelaufforderung für ausgewählte Entnahmebenutzungen erstellen</label>
    			<br />
    <!-- 				<input type="hidden" name="post_action" value="aufforderung_date_insert" /> -->
    				<input type="hidden" name="go" value="wasserentnahmebenutzer">
    <!-- 				<input type="submit" name="go_plus" value="Starten"> -->
    				<input type="submit" value="Aufforderung erstellen!" id="aufforderung_button" name="aufforderung" style="float: left; margin-top: 10px; font-size: 14px" />
    		</p>
    		
    		<p style="float: left; margin-top: 100px">
    			<label for="test" style="float: left;">Abgelegte Sammelaufforderungen</label>
    <!-- 			<br /> -->
    		</p>
		
		</form>
</div>

<div id="entgeltbescheid" class="tabcontent">

	<h1>Entgeltbescheid</h1>

</div>

<?php
// echo 'Hello Wasserrecht';

//javascript:ahah('index.php',%20'go=neuer_Layer_Datensatz&selected_layer_id=9&embedded=true&fromobject=subform2_0_3&targetobject=zustaend_stalu_0&targetlayer_id=2&targetattribute=zustaend_stalu',%20new%20Array(document.getElementById('subform2_0_3')),%20new%20Array('sethtml'));

//http://10.4.84.131/kvwmap/index.php?go=Layer-Suche_Suchen&selected_layer_id=25&value_wrz_id=1&operator_wrz_id==
?>