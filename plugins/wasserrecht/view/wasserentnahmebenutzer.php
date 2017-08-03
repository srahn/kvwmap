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
                                $getyear = !empty(htmlspecialchars($_GET['year'])) ? htmlspecialchars($_GET['year']) : $gueltigkeitsjahre[0];
                                
                                foreach($gueltigkeitsjahre AS $gueltigkeitsjahr)
                                {
                                    echo '<option value='. $gueltigkeitsjahr . ' ' . ($gueltigkeitsjahr === $getyear ? "selected" : "") . '>' . $gueltigkeitsjahr . "</option>";
                                }
                                
                                $nextyear = date('Y', strtotime('+1 year'));
                                echo '<option value='. $nextyear . ' ' . ($nextyear === $getyear ? "selected" : "") . '>' . $nextyear . "</option>";
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
		</label>
		
		<br />
		<br />
		<br />
		
		<label style="float: left">
			Adressat:
		</label>
		
		<br />
		<br />
		<br />
		
		<label style="float: left">Anlagen:
				<select>
					<?php
						$anlage = new Anlage($this);

// 						$anlagen = Anlage::find_by_id($this, 'id', 1);
// 						$anlagen = $anlage->find_where('true');
						$anlagen = $anlage->find_where('id=1');
						
// 						$this->debug->write($anlagen, 4);

// 						echo '<option>' . var_dump($anlagen) . "</option>";
// 						echo '<option>' . $anlagen[0]->data['name'] . "</option>";
// 						echo '<option>' . $gueltigkeitsjahr . "</option>";

						foreach($anlagen AS $al) 
						{
							echo '<option>' . $al->data['name'] . "</option>";
						}
					?>
				</select>
		</label>
		
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
    		
//     		  var_dump($this->layerset);
//     		  var_dump($this->layer_names);
//               $anlagen_layer_id;
//               $wrz_layer_id;
//     		  foreach($this->layerset AS $layer)
//     		  {
// //     		      if($layer['Layer_ID'] == 25)
// //     		      {
// //     		          var_dump($layer);
// //     		      }
    		      
//     		      if($layer['Name'] == 'Anlagen')
//     		      {
//     		          $anlagen_layer_id = $layer['Layer_ID'];
//     		          break;
//     		      }
//     		      elseif($layer['Name'] == 'Wasserrechtliche_Zulassungen')
//     		      {
//     		          $wrz_layer_id = $layer['Layer_ID'];
//     		          break;
//     		      }
//     		  }
    		
    		  if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
    		  {
    		      $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
    		      
    		      //     		  var_dump($wasserrechtlicheZulassungen);
    		      foreach($wasserrechtlicheZulassungen AS $wrz)
    		      {
    		          if(!empty($wrz) && $getyear === $wrz->gueltigkeitsJahr)
    		          {
    		              $anlage = new Anlage($this);
    		              $anlagen = $anlage->find_where('id=' . $wrz->data['anlage']);
    		              
    		              $gewaesserbenutzung = new Gewaesserbenutzungen($this);
    		              $gewaesserbenutzungen = $gewaesserbenutzung->find_where_with_umfang('wasserrechtliche_zulassungen=' . $wrz->data['id']);
    		              
    		              ?>
    		          	<tr>
    		          		<td>
    		          			<input type="checkbox">
    		          		</td>
    		          		<td>
    		          			<?php 
    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Anlagen'] . '&value_id=' . $anlagen[0]->data['id'] . 'operator_id==">' . $anlagen[0]->data['name'] . '</a>';
//     		          			     var_dump($wrz);
    		          			?>
    		          		</td>
    		          		<td>
    		          			<?php 
    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Wasserrechtliche_Zulassungen'] . '&value_id=' . $wrz->data['id'] . 'operator_id==">' . $wrz->data['name'] . '</a>';
//     		          			     echo $wrz->data['name'];
//     		          			     var_dump($wrz);
    		          			?>
    		          		</td>
    		          		<td>
    		          			<?php
    		          			     if(!empty($gewaesserbenutzungen) && !empty($gewaesserbenutzungen[0]))
    		          			     {
    		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Umfang'] . '&value_id=' . $gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->data['id'] . 'operator_id==">' . $gewaesserbenutzungen[0]->gewaesserbenutzungUmfang->getUmfang() . '</a>';
    		          			     }
    		          			?>
    		          		</td>
    		          		<td>
    		          			<?php 
        		          			if(!empty($gewaesserbenutzungen) && !empty($gewaesserbenutzungen[0]))
        		          			{
    		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen'] . '&value_id=' . $gewaesserbenutzungen[0]->data['id'] . 'operator_id==">' . $gewaesserbenutzungen[0]->data['kennnummer'] . '</a>';
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
    		?>
		</table>
</div>

<?php
// echo 'Hello Wasserrecht';

//javascript:ahah('index.php',%20'go=neuer_Layer_Datensatz&selected_layer_id=9&embedded=true&fromobject=subform2_0_3&targetobject=zustaend_stalu_0&targetlayer_id=2&targetattribute=zustaend_stalu',%20new%20Array(document.getElementById('subform2_0_3')),%20new%20Array('sethtml'));

//http://10.4.84.131/kvwmap/index.php?go=Layer-Suche_Suchen&selected_layer_id=25&value_wrz_id=1&operator_wrz_id==
?>