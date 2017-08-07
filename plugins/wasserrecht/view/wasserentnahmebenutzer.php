<style>
 /* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
    width: 100%;
    height: 100%
}

table#wasserentnahmebenutzer_tabelle {
    width: 95%;
/*     border: 1px solid black; */
    border-collapse: collapse;
}

#wasserentnahmebenutzer_tabelle th, #wasserentnahmebenutzer_tabelle td {
/*     border: 1px solid black; */
/*     border-collapse: collapse; */
    padding: 5px;
    text-align: left;
    font-size: 12px;
    background-color: white;
}

#wasserentnahmebenutzer_tabelle a{
    font-size: 12px;
}
</style>

<script type="text/javascript">
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

function setNewErhebungsJahr(selectObject)
{
	var value = selectObject.value;
	replaceParameterInUrl('year', value);
}

function setNewBehoerde(selectObject)
{
	var value = selectObject.value;
	replaceParameterInUrl('behoerde', value);
}

function setNewAdressat(selectObject)
{
	var value = selectObject.value;
	replaceParameterInUrl('adressat', value);
}

function replaceParameterInUrl(key, value)
{
	var url = window.location.href;
	if (url.indexOf('?') > -1){
	   if (url.indexOf(key) > -1){
		   if(url.indexOf(key + '=' + value) > -1){
		   }
		   else{
			   var oldValue = url.substring(url.indexOf(key) + key.length + 1, url.length);
			   if(oldValue.indexOf('&') > -1){
				   oldValue = oldValue.substring(0, oldValue.indexOf('&'));
			   }
// 			   alert(oldValue);
// 			   alert(url);
			   url = url.replace(key + '=' + oldValue, key + '=' + value);
		   }	   	   
	   } 
	   else{
		   url += '&' + key + '=' + value
	   }
	}else{
	   url += '?' + key + '=' + value
	}
	window.location.href = url;
}
</script>

<div class="tab">
	<button class="tablinks active" onclick="alert('test1')">Aufforderung zur Erklärung</button>
	<button class="tablinks" onclick="alert('test2')">Entgeltbescheid</button>
</div>

<div id="aufforderung_zur_erklaerung" class="tabcontent">
	
		<label style="float: left">Erhebungsjahr:
				<select onchange="setNewErhebungsJahr(this)">
					<?php
						$wasserrechtlicheZulassung = new WasserrechtlicheZulassungen($this);
// 						$wasserrechtlicheZulassung = $wasserrechtlicheZulassung->find_where('gueltigkeit IS NOT NULL');
// 						foreach($wasserrechtlicheZulassung AS $wrz)
// 						{
// 							echo '<option>' . $wrz->data['gueltigkeit'] . "</option>";
// 						}

                        $wrzProGueltigkeitsJahr = $wasserrechtlicheZulassung->find_gueltigkeitsjahre($this);
                        if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->gueltigkeitsJahre))
                        {
                            $gueltigkeitsjahre = $wrzProGueltigkeitsJahr->gueltigkeitsJahre;
                            if(!empty($gueltigkeitsjahre) && count($gueltigkeitsjahre) > 0)
                            {
                                $getYear = !empty(htmlspecialchars($_GET['year'])) ? htmlspecialchars($_GET['year']) : $gueltigkeitsjahre[0];
                                
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
		</label>
		
		<br />
		<br />
		
		<label style="float: left">
			Behörde: 
			<select onchange="setNewBehoerde(this)">
				<?php
				
				$getBehoerde = !empty(htmlspecialchars($_GET['behoerde'])) ? htmlspecialchars($_GET['behoerde']) : null;
				
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
				                if(empty($getBehoerde))
				                {
				                    $getBehoerde = $wrz->behoerde->getId();
				                }
				                
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
		</label>
		
		<br />
		<br />
		<br />
		
		<label style="float: left">
			Adressat:
			<select onchange="setNewAdressat(this)">
				<?php
				
				$getAdressat = !empty(htmlspecialchars($_GET['adressat'])) ? htmlspecialchars($_GET['adressat']) : null;
				
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
				                if(empty($getAdressat))
				                {
				                    $getAdressat = $wrz->adressat->getId();
				                }
				                
				                if(!in_array($wrz->adressat->toString(), $adressatArray))
				                {
				                    $adressatArray[]=$wrz->adressat->toString();
				                    
				                    echo '<option value='. $wrz->adressat->getId() . ' ' . ($wrz->adressat->getId() === $getAdressat ? "selected" : "") . '>' . $wrz->adressat->getName() . "</option>";
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
		</label>
		
		<br />
		<br />
		<br />
		
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
                		          		<td>
                		          			<input type="checkbox">
                		          		</td>
                		          		<td>
                		          			<?php 
                		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Anlagen'] . '&value_id=' . $wrz->anlagen[0]->getId() . 'operator_id==">' . $wrz->anlagen[0]->getName() . '</a>';
                		          			?>
                		          		</td>
                		          		<td>
                		          			<?php 
                		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Wasserrechtliche_Zulassungen'] . '&value_id=' . $wrz->getId() . 'operator_id==">' . $wrz->getName() . '</a>';
            //     		          			     echo $wrz->getName();
            //     		          			     var_dump($wrz);
                		          			?>
                		          		</td>
                		          		<td>
                		          			<?php
                		          			     if(!empty($wrz->gewaesserbenutzungen) && !empty($wrz->gewaesserbenutzungen[0]))
                		          			     {
                		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Umfang'] . '&value_id=' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getId() . 'operator_id==">' . $wrz->gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getUmfang() . '</a>';
                		          			     }
                		          			?>
                		          		</td>
                		          		<td>
                		          			<?php 
                    		          			if(!empty($wrz->gewaesserbenutzungen) && !empty($wrz->gewaesserbenutzungen[0]))
                    		          			{
                		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen'] . '&value_id=' . $wrz->gewaesserbenutzungen[0]->getId() . 'operator_id==">' . $wrz->gewaesserbenutzungen[0]->getKennummer() . '</a>';
                    		          			}
                		          			?>
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
    		?>
		</table>
</div>

<?php
// echo 'Hello Wasserrecht';

//javascript:ahah('index.php',%20'go=neuer_Layer_Datensatz&selected_layer_id=9&embedded=true&fromobject=subform2_0_3&targetobject=zustaend_stalu_0&targetlayer_id=2&targetattribute=zustaend_stalu',%20new%20Array(document.getElementById('subform2_0_3')),%20new%20Array('sethtml'));

//http://10.4.84.131/kvwmap/index.php?go=Layer-Suche_Suchen&selected_layer_id=25&value_wrz_id=1&operator_wrz_id==
?>