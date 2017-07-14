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
</script>

<div class="tab">
	<button class="tablinks active" onclick="alert('test1')">Aufforderung zur Erklärung</button>
	<button class="tablinks" onclick="alert('test2')">Entgeltbescheid</button>
</div>

<div id="aufforderung_zur_erklaerung" class="tabcontent">
	
		<label style="float: left">Erhebungsjahr:
				<select>
					<?php
						$wasserrechtlicheZulassung = new WasserrechtlicheZulassungen($this);
// 						$wasserrechtlicheZulassung = $wasserrechtlicheZulassung->find_where('gueltigkeit IS NOT NULL');
// 						foreach($wasserrechtlicheZulassung AS $wrz)
// 						{
// 							echo '<option>' . $wrz->data['gueltigkeit'] . "</option>";
// 						}
						$results = $wasserrechtlicheZulassung->find_gueltigkeitsjahr($this);
						foreach($results AS $result)
						{
							echo '<option>' . $result . "</option>";
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
						$anlagen = $anlage->find_where('true');
						
// 						$this->debug->write($anlagen, 4);

// 						echo '<option>' . var_dump($anlagen) . "</option>";
// 						echo '<option>' . $anlagen[0]->data['name'] . "</option>";

						foreach($anlagen AS $al) 
						{
							echo '<option>' . $al->data['name'] . "</option>";
						}
					?>
				</select>
		</label>
		
		<br />
		<br />
	
	</div>

<?php
// echo 'Hello Wasserrecht';

//javascript:ahah('index.php',%20'go=neuer_Layer_Datensatz&selected_layer_id=9&embedded=true&fromobject=subform2_0_3&targetobject=zustaend_stalu_0&targetlayer_id=2&targetattribute=zustaend_stalu',%20new%20Array(document.getElementById('subform2_0_3')),%20new%20Array('sethtml'));

//http://10.4.84.131/kvwmap/index.php?go=Layer-Suche_Suchen&selected_layer_id=25&value_wrz_id=1&operator_wrz_id==
?>