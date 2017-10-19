<?php
$wrz = null;
$gewaesserbenutzung = null;
$erhebungsjahr = null;
$errorEingabeErklaerung = null;
$leerEingabeErklaerung = false;
$speereEingabeErklaerung = false;

$findDefaultWrz = true;

$isTrue = ["true",1,"t"];

// print_r($_REQUEST);
		  
if($_SERVER ["REQUEST_METHOD"] == "POST")
{
//     print_r($_POST);

    foreach($_POST as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(startsWith($keyEscaped, "erklaerung_freigeben_"))
        {
            $findDefaultWrz = false;
            erklaerung_freigeben($this, $valueEscaped, $wrz, $gewaesserbenutzung, $erhebungsjahr, true, $errorEingabeErklaerung, $speereEingabeErklaerung);
            break;
        }
        elseif(startsWith($keyEscaped, "erklaerung_entspeeren_"))
        {
            $findDefaultWrz = false;
            erklaerung_entspeeren($this, $valueEscaped, $wrz, $gewaesserbenutzung, $erhebungsjahr);
            break;
        }
        elseif(startsWith($keyEscaped, "erklaerung_"))
		{
		    $this->debug->write('*** erklaerung ***', 4);
		    
            $findDefaultWrz = false;
            
            $idValues = findIdAndYearFromValueString($this, $valueEscaped);
            $this->debug->write('idValues: ' . var_export($idValues, true), 4);
		    
		    $erklaerungWrz = new WasserrechtlicheZulassungen($this);
		    $wrz = $erklaerungWrz->find_by_id($this, 'id', $idValues["wrz_id"]);
		    if(!empty($wrz))
		    {
		        $gb = new Gewaesserbenutzungen($this);
		        $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
		        if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0)
		        {
		            foreach ($gewaesserbenutzungen as $gwb)
		            {
		                if(!empty($gwb) && $gwb->getId() === $idValues["gewaesserbenutzung_id"])
		                {
		                    $gewaesserbenutzung = $gwb;
		                    break;
		                }
		            }
		        }
// 		        echo "<br />gewaesserbenutzung: " . $gewaesserbenutzung[0]->getId();

		        if(!empty($gewaesserbenutzung))
		        {
		            $erhebungsjahr = $idValues["erhebungsjahr"];
		            
		            if($gewaesserbenutzung->isErklaerungFreigegeben($erhebungsjahr))
		            {
		                $speereEingabeErklaerung = true;
		            }
		        }
		    }
		    
		    break;
		 }
    }
}
elseif($_SERVER ["REQUEST_METHOD"] == "GET")
{
    foreach($_GET as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(strtolower($keyEscaped) === "geterklaerung")
        {
            $this->debug->write('*** geterklaerung ***', 4);
            
            $findDefaultWrz = false;
            
            $idValues = findIdAndYearFromValueString($this, $valueEscaped);
            $this->debug->write('idValues: ' . var_export($idValues, true), 4);
            
            $erklaerungWrz = new WasserrechtlicheZulassungen($this);
            $wrz = $erklaerungWrz->find_by_id($this, 'id', $idValues["wrz_id"]);
            if(!empty($wrz) && !empty($wrz->getId()))
            {
                $gb = new Gewaesserbenutzungen($this);
                $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
                if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0)
                {
                    foreach ($gewaesserbenutzungen as $gwb)
                    {
                        if(!empty($gwb) && $gwb->getId() === $idValues["gewaesserbenutzung_id"])
                        {
                            $gewaesserbenutzung = $gwb;
                            break;
                        }
                    }
                }
                // 		        echo "<br />gewaesserbenutzung: " . $gewaesserbenutzung[0]->getId();
                
                if(!empty($gewaesserbenutzung))
                {
                    $erhebungsjahr = $idValues["erhebungsjahr"];
                    
                    if($gewaesserbenutzung->isErklaerungFreigegeben($erhebungsjahr))
                    {
                        $speereEingabeErklaerung = true;
                    }
                }
            }
            
            break;
        }
    }
}

function erklaerung_entspeeren($gui, &$valueEscaped, &$wrz, &$gewaesserbenutzung, &$erhebungsjahr)
{
    $gui->debug->write('*** erklaerung_entspeeren ***', 4);
    
    $idValues = findIdAndYearFromValueString($gui, $valueEscaped);
    $gui->debug->write('idValues: ' . var_export($idValues, true), 4);
    
    $erklaerungEntsperrenWrz = new WasserrechtlicheZulassungen($gui);
    $wrz = $erklaerungEntsperrenWrz->find_by_id($gui, 'id', $idValues["wrz_id"]);
    
    if(!empty($wrz))
    {
        $gui->debug->write('wrz id: ' . var_export($wrz->getId(), true), 4);
        
        $gb = new Gewaesserbenutzungen($gui);
        $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
        $gewaesserbenutzung = null;
        if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0)
        {
            foreach ($gewaesserbenutzungen as $gwb)
            {
                if(!empty($gwb) && $gwb->getId() === $idValues["gewaesserbenutzung_id"])
                {
                    $gewaesserbenutzung = $gwb;
                    break;
                }
            }
        }
        
        if(!empty($gewaesserbenutzung))
        {
            $gui->debug->write('gewaesserbenutzung id: ' . var_export($gewaesserbenutzung->getId(), true), 4);
            
            $erhebungsjahr = $idValues["erhebungsjahr"];
            
            $speereEingabeErklaerung = false;
        }
        else
        {
            $gui->add_message('error', 'Keine gültige Gewässerbenutzung gefunden!');
            $gui->debug->write('ERROR: Keine gültige WrZ gefunden!', 4);
        }
    }
    else
    {
        $gui->add_message('error', 'Keine gültige WrZ gefunden!');
        $gui->debug->write('ERROR: Keine gültige WrZ gefunden!', 4);
    }
}

function erklaerung_freigeben($gui, &$valueEscaped, &$wrz, &$gewaesserbenutzung, &$erhebungsjahr, $insertDate, &$errorEingabeErklaerung, &$speereEingabeErklaerung)
{
    $gui->debug->write('*** erklaerung_freigeben ***', 4);
    
    $idValues = findIdAndYearFromValueString($gui, $valueEscaped);
    $gui->debug->write('idValues: ' . var_export($idValues, true), 4);
    
    $erklaerungFreigebenWrz = new WasserrechtlicheZulassungen($gui);
    $wrz = $erklaerungFreigebenWrz->find_by_id($gui, 'id', $idValues["wrz_id"]);
    
    if(!empty($wrz)) 
    {
        $gui->debug->write('wrz id: ' . var_export($wrz->getId(), true), 4);
        
        $gb = new Gewaesserbenutzungen($gui);
        $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
        $gewaesserbenutzung = null;
        $gui->debug->write('count gewaesserbenutzungen: ' . count($gewaesserbenutzungen), 4);
        if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0)
        {
            foreach ($gewaesserbenutzungen as $gwb)
            {
                if(!empty($gwb) && $gwb->getId() === $idValues["gewaesserbenutzung_id"])
                {
                    $gewaesserbenutzung = $gwb;
                    break;
                }
            }
        }
        
        if(!empty($gewaesserbenutzung)) 
        {
            $gui->debug->write('gewaesserbenutzung id: ' . var_export($gewaesserbenutzung->getId(), true), 4);
            
            $erhebungsjahr = $idValues["erhebungsjahr"];
            
            $teilgewaesserbenutzungsart = htmlspecialchars($_POST["teilgewaesserbenutzungsart"]);
            // echo "teilgewaesserbenutzungsart: " . $teilgewaesserbenutzungsart;
            if (empty($teilgewaesserbenutzungsart) || strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $teilgewaesserbenutzungsart) === 0) {
                $errorEingabeErklaerung = - 1;
            } else {
                $errorEingabeErklaerung = null;
            }
            
            if ($errorEingabeErklaerung === null) {
                
                /**
                 * Auf leere Tabelle überprüfen
                 */
                for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i ++)
                {
                    $gewaesserbenutzungsart = htmlspecialchars($_POST["gewaesserbenutzungsart_" . $i]);
                    $gewaesserbenutzungszweck = htmlspecialchars($_POST["gewaesserbenutzungszweck_" . $i]);
                    $gewaesserbenutzungsumfang = htmlspecialchars($_POST["gewaesserbenutzungsumfang_" . $i]);
                    if(!empty($gewaesserbenutzungsumfang))
                    {
                        $gewaesserbenutzungsumfang = str_replace(' ', '', $gewaesserbenutzungsumfang);
                    }
                    $wiedereinleitung = htmlspecialchars($_POST["wiedereinleitung_" . $i]);
                    $mengenbestimmung = htmlspecialchars($_POST["mengenbestimmung_" . $i]);
                    
                    //Angaben leer
                    if (strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungsart) === 0
                        && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungszweck) === 0
                        && empty($gewaesserbenutzungsumfang) 
                        && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $wiedereinleitung) === 0
                        && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $mengenbestimmung) === 0) {
                            $leerEingabeErklaerung = true;
                    }
                    else
                    {
                        $leerEingabeErklaerung = false;
                        break;
                    }
                }
                
                /**
                 * Auf unvollständige Tabelle überprüfen
                 */
                if(!$leerEingabeErklaerung)
                {
                    for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i ++)
                    {
                        $gewaesserbenutzungsart = htmlspecialchars($_POST["gewaesserbenutzungsart_" . $i]);
                        $gewaesserbenutzungszweck = htmlspecialchars($_POST["gewaesserbenutzungszweck_" . $i]);
                        $gewaesserbenutzungsumfang = htmlspecialchars($_POST["gewaesserbenutzungsumfang_" . $i]);
                        if(!empty($gewaesserbenutzungsumfang))
                        {
                            $gewaesserbenutzungsumfang = str_replace(' ', '', $gewaesserbenutzungsumfang);
                        }
                        $wiedereinleitung = htmlspecialchars($_POST["wiedereinleitung_" . $i]);
                        $mengenbestimmung = htmlspecialchars($_POST["mengenbestimmung_" . $i]);
                        
                        if (strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungsart) === 0
                            && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungszweck) === 0
                            && empty($gewaesserbenutzungsumfang)
                            && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $wiedereinleitung) === 0
                            && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $mengenbestimmung) === 0) {
                                break;
                            }
                        else
                        {
                            //Angaben vollständig
                            if (!empty($gewaesserbenutzungsart) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungsart) !== 0
                                && !empty($gewaesserbenutzungszweck) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungszweck) !== 0
                                && !empty($gewaesserbenutzungsumfang) && is_numeric($gewaesserbenutzungsumfang)
                                && !empty($wiedereinleitung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $wiedereinleitung) !== 0
                                && !empty($mengenbestimmung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $mengenbestimmung) !== 0)
                            {
                                $errorEingabeErklaerung = null;
                            }
                            else //Angaben unvollständig
                            {
                                $errorEingabeErklaerung = $i;
                                break;
                            }
                        }
                    }
                }
                
                /**
                 * Angaben aus der Tabelle in DB schreiben
                 */
                if(!$leerEingabeErklaerung && $errorEingabeErklaerung === null)
                {
                    $teilgewasserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr);
                    for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i ++)
                    {
                        $gewaesserbenutzungsart = htmlspecialchars($_POST["gewaesserbenutzungsart_" . $i]);
                        $gewaesserbenutzungszweck = htmlspecialchars($_POST["gewaesserbenutzungszweck_" . $i]);
                        $gewaesserbenutzungsumfang = htmlspecialchars($_POST["gewaesserbenutzungsumfang_" . $i]);
                        if(!empty($gewaesserbenutzungsumfang))
                        {
                            $gewaesserbenutzungsumfang = str_replace(' ', '', $gewaesserbenutzungsumfang);
                        }
                        $wiedereinleitung = htmlspecialchars($_POST["wiedereinleitung_" . $i]);
                        $mengenbestimmung = htmlspecialchars($_POST["mengenbestimmung_" . $i]);
                        
                        // check for not filled out lines
                        if (strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungsart) === 0
                            && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungszweck) === 0
                            && empty($gewaesserbenutzungsumfang)
                            && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $wiedereinleitung) === 0
                            && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $mengenbestimmung) === 0) {
                                break;
                            }
                            elseif (!empty($gewaesserbenutzungsart) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungsart) !== 0
                                && !empty($gewaesserbenutzungszweck) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $gewaesserbenutzungszweck) !== 0
                                && !empty($gewaesserbenutzungsumfang) && is_numeric($gewaesserbenutzungsumfang)
                                && !empty($wiedereinleitung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $wiedereinleitung) !== 0
                                && !empty($mengenbestimmung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $mengenbestimmung) !== 0) {
                                    
                                    $speereEingabeErklaerung = true;
                                    //                         echo var_dump($speereEingabeErklaerung);
                                    
                                    // update an existing teilgewaesserbenutzung
                                    if (!empty($teilgewasserbenutzungen[$i - 1])) {
                                        $teilgewaesserbenutzung = $teilgewasserbenutzungen[$i - 1];
                                        $teilgewaesserbenutzungId = $teilgewaesserbenutzung->updateTeilgewaesserbenutzung_Nutzer($erhebungsjahr, $gewaesserbenutzungsart, $gewaesserbenutzungszweck, $gewaesserbenutzungsumfang, $wiedereinleitung, $mengenbestimmung, $teilgewaesserbenutzungsart);
                                        
                                        $gui->add_message('notice', 'Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId . ') erfolgreich geändert!');
                                        $gui->debug->write('Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId . ') erfolgreich geändert!', 4);
                                    }                        // else --> if not there --> create one
                                    else {
                                        $teilgewaesserbenutzung = new Teilgewaesserbenutzungen($gui);
                                        $teilgewaesserbenutzungId = $teilgewaesserbenutzung->createTeilgewaesserbenutzung_Nutzer($gewaesserbenutzung->getId(), $erhebungsjahr, $gewaesserbenutzungsart, $gewaesserbenutzungszweck, $gewaesserbenutzungsumfang, $wiedereinleitung, $mengenbestimmung, $teilgewaesserbenutzungsart, WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_ENTGELTSATZ);
                                        
                                        $gui->add_message('notice', 'Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId . ') erfolgreich eingetragen!');
                                        $gui->debug->write('Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId . ') erfolgreich eingetragen!', 4);
                                    }
                                    
                                }
                               
                                // echo $i;
                    }
                }
            }
            
            if(!$leerEingabeErklaerung && $errorEingabeErklaerung === null) 
            {
                /**
                 * Datum hinzufügen
                 */
                if($insertDate)
                {
                    $erklaerungDatum = htmlspecialchars($_POST["datum_erklaerung"]);
                    $erklaerungNutzer = $gui->user->Vorname . ' ' . $gui->user->Name;
                    $gewaesserbenutzung->insertErklaerung($erhebungsjahr, $erklaerungDatum, $erklaerungNutzer);
                }
                
                // update gewaesserbenutzungen, because teilgewaesserbenutzungen where added
                $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
                $gewaesserbenutzung = $gewaesserbenutzungen[0];
                
            } else {
                if ($errorEingabeErklaerung > 0) {
                    $gui->add_message('error', 'Eingabe in Zeile ' . $errorEingabeErklaerung . ' ist fehlerhaft oder nicht vollständig! Bitte überprüfen Sie Ihre Angaben!');
                    $gui->debug->write('ERROR: Eingabe in Zeile ' . $errorEingabeErklaerung . ' ist fehlerhaft oder nicht vollständig! Bitte überprüfen Sie Ihre Angaben!', 4);
                } elseif ($errorEingabeErklaerung === - 1) {
                    $gui->add_message('error', 'Eingabe ob Angaben auf einer Erklärung oder Schätzung beruhen sind fehlerhaft oder nicht vollständig! Bitte überprüfen Sie Ihre Angaben!');
                    $gui->debug->write('ERROR: Eingabe ob Angaben auf einer Erklärung oder Schätzung beruhen sind fehlerhaft oder nicht vollständig! Bitte überprüfen Sie Ihre Angaben!', 4);
                }
                elseif ($leerEingabeErklaerung)
                {
                    $gui->add_message('error', 'Bitte Angaben in der Tabelle machen!');
                    $gui->debug->write('ERROR: Bitte Angaben in der Tabelle machen!', 4);
                }
            }
        }
        else
        {
            $gui->add_message('error', 'Keine gültige Gewässerbenutzung gefunden!');
            $gui->debug->write('ERROR: Keine gültige Gewässerbenutzung gefunden!', 4);
        }
    }
    else
    {
        $gui->add_message('error', 'Keine gültige WrZ gefunden!');
        $gui->debug->write('ERROR: Keine gültige WrZ gefunden!', 4);
    }
}

//try to find the first WRZ if, no wrz was given
if((empty($wrz) || empty($wrz->getId())) && $findDefaultWrz)
{
    $defaultWrz = new WasserrechtlicheZulassungen($this);
    $results = $defaultWrz->find_where('1=1', 'id');
    
    if(!empty($results) && count($results) > 0 && !empty($results[0]))
    {
        $wrz = $results[0];
        $wrz->getDependentObjects($this, $wrz);
        
        if(empty($gewaesserbenutzung) && !empty($wrz->gewaesserbenutzungen) && count($wrz->gewaesserbenutzungen) > 0 && !empty($wrz->gewaesserbenutzungen[0]))
        {
            $gewaesserbenutzung = $wrz->gewaesserbenutzungen[0];
        }
        
        if(empty($erhebungsjahr))
        {
            $erhebungsjahr = $wrz->gueltigkeitsJahre[0];
        }
        
        if(!empty($gewaesserbenutzung) && $gewaesserbenutzung->isErklaerungFreigegeben($erhebungsjahr))
        {
            $speereEingabeErklaerung = true;
        }
    }
    
}

if(!empty($wrz) && !empty($wrz->getId()))
{
    $wrz->getDependentObjects($this, $wrz);
    
    if($findDefaultWrz && empty($gewaesserbenutzung) && !empty($wrz->gewaesserbenutzungen) && count($wrz->gewaesserbenutzungen) > 0 && !empty($wrz->gewaesserbenutzungen[0]))
    {
        $gewaesserbenutzung = $wrz->gewaesserbenutzungen[0];
    }
    
    if(!empty($gewaesserbenutzung))
    {
        if(!empty($erhebungsjahr))
        {
            $tab1_id="wasserentnahmeentgelt_erklaerung_der_entnahme";
            $tab1_name="Erklärung der Entnahme";
            $tab1_active=true;
            $tab1_visible=true;
            $tab2_id="wasserentnahmeentgelt_festsetzung";
            $tab2_name="Festsetzung";
            $tab2_active=false;
            $tab2_extra_parameter_key="getfestsetzung";
            $tab2_extra_parameter_value=$wrz->getId() . "_" . $gewaesserbenutzung->getId() . "_" . $erhebungsjahr;
            //     var_dump($tab2_extra_parameter_key);
            //     var_dump($tab2_extra_parameter_value);
            if($gewaesserbenutzung->isErklaerungFreigegeben($erhebungsjahr))
            {
                $tab2_visible=true;
            }
            else
            {
                $tab2_visible=false;
            }
            include_once ('includes/header.php');
            
            ?>
        	<div id="wasserentnahmeentgelt_erklaerung_der_entnahme" class="tabcontent" style="display: block">
        
        		<form action="index.php" id="erklaerung_freigeben_form" accept-charset="" method="POST">
        		
        			<?php 
        			     include_once ('includes/wasserentnahmeentgelt_header.php'); 
        			?>
                    
                    <table id="erklaerung_freigeben_table" class="wasserrecht_table" style="margin-top: 20px">
                      <tr>
                      	<th></th>
                        <th>Erklärter Teil-Benutzungsart</th>
                        <th>Erklärter Teil-Benutzungszweck</th>
                        <th>Erklärter Teil-Benutzungsumfang in m³/a</th>
                        <th title="Wiedereinleitung mit einem Verlust von weniger als 1% pro Jahr">Wiedereinleitung</th>
                        <th>Mengenbestimmung</th>
                      </tr>
                      <?php
                          $teilgewasserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr);
                          for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++)
                          {
                            
                             $teilgewaesserbenutzung = null;
                             if(!empty($teilgewasserbenutzungen) && count($teilgewasserbenutzungen) > 0
                                 && count($teilgewasserbenutzungen) > ($i - 1) && !empty($teilgewasserbenutzungen[$i -1]))
                             {
                                 $teilgewaesserbenutzung = $teilgewasserbenutzungen[$i - 1];
//                                  var_dump($teilgewaesserbenutzung);
                             }
                             
//                              var_dump($errorEingabeErklaerung);
                              
                          	 if($errorEingabeErklaerung === $i)
                          	 {
                          	 ?>
                          	     <tr style="border: 3px solid red">
                          	<?php
                         	 }
                         	 else
                         	 {
                         	     ?>
                         	     <tr>
                         	<?php
                         	 }    
                          	?>
                              	<td><?php echo $i; ?>.</td>
                                <td>
                                    <?php
//                                      var_dump($teilgewaesserbenutzung->gewaesserbenutzungArt->data);
                                        $getGewaesserbenutzungsArt = null;
                                        if(!empty(htmlspecialchars($_REQUEST['gewaesserbenutzungsart_' . $i])))
                                        {
                                            if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['gewaesserbenutzungsart_' . $i])) !== 0)
                                            {
                                                $getGewaesserbenutzungsArt = htmlspecialchars($_REQUEST['gewaesserbenutzungsart_' . $i]);
                                            }
                                            else
                                            {
                                                $getGewaesserbenutzungsArt = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                            }
                                        }
                                        else
                                        {
                                            if(!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->gewaesserbenutzungArt))
                                            {
                                                $getGewaesserbenutzungsArt = $teilgewaesserbenutzung->gewaesserbenutzungArt->getId();
                                            }    
                                        }    
                                    ?>
                                	<select class="wasserrecht_table_inputfield" name="gewaesserbenutzungsart_<?php echo $i; ?>" <?php echo $speereEingabeErklaerung ? "disabled='disabled'" : "" ?>>
                                		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                		<?php 
                                		  $gwba = new GewaesserbenutzungenArt($this);
                                		  $gewaesserbenutzungenArten = $gwba->find_where('1=1', 'id');
                                		  if(!empty($gewaesserbenutzungenArten) && count($gewaesserbenutzungenArten) > 0)
                                		  {
                                		      foreach ($gewaesserbenutzungenArten AS $gewaesserbenutzungenArt)
                                		      {
                                		          echo '<option value="'. $gewaesserbenutzungenArt->getId() . '" ' . (!empty($getGewaesserbenutzungsArt) && $getGewaesserbenutzungsArt === $gewaesserbenutzungenArt->getId() ?  'selected' : '') . ' >' . $gewaesserbenutzungenArt->getName() . "</option>";
                                		      }    
                                		  }
                                		?>
                                	</select>
                                </td>
                                <td>
                                	<?php 
                                	   $getGewaesserbenutzungsZweck = null;
                                	   if(!empty(htmlspecialchars($_REQUEST['gewaesserbenutzungszweck_' . $i])))
                                	   {
                                	       if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['gewaesserbenutzungszweck_' . $i])) !== 0)
                                	       {
                                	           $getGewaesserbenutzungsZweck = htmlspecialchars($_REQUEST['gewaesserbenutzungszweck_' . $i]);
                                	       }
                                	       else
                                	       {
                                	           $getGewaesserbenutzungsZweck = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                	       }
                                	   }
                                	   else
                                	   {
                                	       if(!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->gewaesserbenutzungZweck))
                                	       {
                                	           $getGewaesserbenutzungsZweck = $teilgewaesserbenutzung->gewaesserbenutzungZweck->getId();
                                	       }
                                	   }    
                                	?>
                                	<select class="wasserrecht_table_inputfield" name="gewaesserbenutzungszweck_<?php echo $i; ?>" <?php echo $speereEingabeErklaerung ? "disabled='disabled'" : "" ?>>
                                		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                		<?php 
                                		  $gwbz = new GewaesserbenutzungenZweck($this);
                                		  $gewaesserbenutzungenZwecke = $gwbz->find_where('1=1', 'id');
                                		  if(!empty($gewaesserbenutzungenZwecke) && count($gewaesserbenutzungenZwecke) > 0)
                                		  {
                                		      foreach ($gewaesserbenutzungenZwecke AS $gewaesserbenutzungenZweck)
                                		      {
                                		          echo '<option value="'. $gewaesserbenutzungenZweck->getId() . '" ' . (!empty($getGewaesserbenutzungsZweck) && $getGewaesserbenutzungsZweck === $gewaesserbenutzungenZweck->getId() ?  'selected' : '') . ' >' . $gewaesserbenutzungenZweck->getName() . "</option>";
                                		      }    
                                		  }
                                		?>
                                	</select>
                                </td>
                                <td>
                                	<?php 
                                	   $getGewaesserbenutzungsUmfang = null;
                                	   if(!empty(htmlspecialchars($_REQUEST['gewaesserbenutzungsumfang_' . $i])))
                                	   {
                                	       $getGewaesserbenutzungsUmfang = htmlspecialchars($_REQUEST['gewaesserbenutzungsumfang_' . $i]);
                                	   }
                                	   elseif(!empty(htmlspecialchars($_REQUEST['gewaesserbenutzungsumfang_' . $i . '_cleared'])))
                                	   {
                                	       $getGewaesserbenutzungsUmfang = null;
                                	   }
                                	   elseif(!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->getUmfang()))
                                	   {
                                	        $getGewaesserbenutzungsUmfang = $teilgewaesserbenutzung->getUmfang();
                                	   }
                                	   
                                	   if(!empty($getGewaesserbenutzungsUmfang) && is_numeric($getGewaesserbenutzungsUmfang))
                                	   {
                                	       $getGewaesserbenutzungsUmfang = number_format($getGewaesserbenutzungsUmfang, 0, '', ' ');
                                	   }
                                	   
                                	?>
                                	<input class="wasserrecht_table_inputfield numberField inputClear" type="text" name="gewaesserbenutzungsumfang_<?php echo $i; ?>" value="<?php echo !empty($getGewaesserbenutzungsUmfang) ? $getGewaesserbenutzungsUmfang : '' ?>" <?php echo $speereEingabeErklaerung ? "disabled='disabled'" : "" ?>>
                                </td>
                                <td>
                                	<?php 
                                	   $getWiedereinleitungNutzer = null;
                                	   if(!empty(htmlspecialchars($_REQUEST['wiedereinleitung_' . $i])))
                                	   {
                                	       if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['wiedereinleitung_' . $i])) !== 0)
                                	       {
                                	           $getWiedereinleitungNutzer = htmlspecialchars($_REQUEST['wiedereinleitung_' . $i]);
                                	           $getWiedereinleitungNutzer = in_array(strtolower($getWiedereinleitungNutzer), $isTrue);
                                	       }
                                	   }
                                	   else
                                	   {
                                	       if(!empty($teilgewaesserbenutzung))
                                	       {
//                                 	           var_dump("getWiedereinleitungNutzer: " . $teilgewaesserbenutzung->getWiedereinleitungNutzer());
                                	           $getWiedereinleitungNutzer = $teilgewaesserbenutzung->getWiedereinleitungNutzer();
                                	           $getWiedereinleitungNutzer = in_array(strtolower($getWiedereinleitungNutzer), $isTrue);
                                	       }
                                	   }    
                                	   
                                    ?>
                                	<select class="wasserrecht_table_inputfield" name="wiedereinleitung_<?php echo $i; ?>" <?php echo $speereEingabeErklaerung ? "disabled='disabled'" : "" ?>>
                                		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                		<option value="true" <?php echo !is_null($getWiedereinleitungNutzer) && $getWiedereinleitungNutzer ?  'selected' : ''?>>ja</option>
                                		<option value="false" <?php echo !is_null($getWiedereinleitungNutzer) && !$getWiedereinleitungNutzer ?  'selected' : ''?>>nein</option>
                                	</select>
                                </td>
                                <td>
                                	<?php 
                                	   $getMengenbestimmung = null;
                                	   if(!empty(htmlspecialchars($_REQUEST['mengenbestimmung_' . $i])))
                                	   {
                                	       if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['mengenbestimmung_' . $i])) !== 0)
                                	       {
                                	           $getMengenbestimmung = htmlspecialchars($_REQUEST['mengenbestimmung_' . $i]);
                                	       }
                                	       else
                                	       {
                                	           $getMengenbestimmung = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                	       }
                                	   }
                                	   else
                                	   {
                                	       if(!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->mengenbestimmung))
                                	       {
                                	           $getMengenbestimmung = $teilgewaesserbenutzung->mengenbestimmung->getId();
                                	       }
                                	   }    
                                	?>
                                	<select class="wasserrecht_table_inputfield" name="mengenbestimmung_<?php echo $i; ?>" <?php echo $speereEingabeErklaerung ? "disabled='disabled'" : "" ?>>
                                		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                		<option value="1" <?php echo !empty($getMengenbestimmung) && $getMengenbestimmung === "1" ?  'selected' : ''?>>Messung</option>
                                		<option value="2" <?php echo !empty($getMengenbestimmung) && $getMengenbestimmung === "2" ?  'selected' : ''?>>Berechnung</option>
                                		<option value="3" <?php echo !empty($getMengenbestimmung) && $getMengenbestimmung === "3" ?  'selected' : ''?>>Schätzung</option>
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
                            <div class="wasserrecht_display_table_cell_white" <?php echo $errorEingabeErklaerung === -1 ? "style='border: 3px solid red'" : ""?>>
                                <select class="wasserrecht_display_table_cell_white" name="teilgewaesserbenutzungsart" <?php echo $speereEingabeErklaerung ? "disabled='disabled'" : "" ?>>
                                	<?php 
                                	    $teilgewasserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr);
                                    	$teilgewaesserbenutzung = null;
                                    	if(!empty($teilgewasserbenutzungen) && count($teilgewasserbenutzungen) > 0 && !empty($teilgewasserbenutzungen[0]))
                                    	{
                                    	    $teilgewaesserbenutzung = $teilgewasserbenutzungen[0];
                                    	    //var_dump($teilgewaesserbenutzung);
                                    	}
                                    	
                                    	$getTeilgewaesserbenutzungsArt = null;
                                    	if(!empty(htmlspecialchars($_REQUEST['teilgewaesserbenutzungsart'])))
                                    	{
                                    	    if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['teilgewaesserbenutzungsart'])) !== 0)
                                    	    {
                                    	        $getTeilgewaesserbenutzungsArt = htmlspecialchars($_REQUEST['teilgewaesserbenutzungsart']);
                                    	    }
                                    	    else
                                    	    {
                                    	        $getTeilgewaesserbenutzungsArt = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                    	    }
                                    	}
                                    	else
                                    	{
                                    	    if(!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->teilgewaesserbenutzungen_art))
                                    	    {
                                    	        $getTeilgewaesserbenutzungsArt = $teilgewaesserbenutzung->teilgewaesserbenutzungen_art->getId();
                                    	    }
                                    	}
                                	?>
                                	<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                	<option value="1" <?php echo !empty($getTeilgewaesserbenutzungsArt) && $getTeilgewaesserbenutzungsArt === "1" ?  'selected' : ''?>>Erklärung</option>
                                	<option value="2" <?php echo !empty($getTeilgewaesserbenutzungsArt) && $getTeilgewaesserbenutzungsArt === "2" ?  'selected' : ''?>>Schätzung</option>
                                </select>
                             </div>
                        </div>
                        
                      <?php
                        if (!$speereEingabeErklaerung && !$gewaesserbenutzung->isFestsetzungFreigegeben($erhebungsjahr)) {
                            ?>
                			<div id="calendar" class="calendar">
                				<div id="calendar_datum_erklaerung"></div>
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
                    			<input type="hidden" name="go" value="wasserentnahmeentgelt">
        						<button class="wasserrecht_button" name="erklaerung_entspeeren_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" type="submit" id="erklaerung_entspeeren_button_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" <?php echo !$speereEingabeErklaerung || $gewaesserbenutzung->isFestsetzungFreigegeben($erhebungsjahr) ? "disabled='disabled'" : "" ?>>Erklärung entspeeren</button>
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
                    			<input type="hidden" name="go" value="wasserentnahmeentgelt">
        						<button class="wasserrecht_button" name="erklaerung_freigeben_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" type="submit" id="erklaerung_freigeben_button_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" <?php echo $speereEingabeErklaerung || $gewaesserbenutzung->isFestsetzungFreigegeben($erhebungsjahr) ? "disabled='disabled'" : "" ?>>Erklärung freigeben</button>
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
                            	   if(!$speereEingabeErklaerung && !$gewaesserbenutzung->isFestsetzungFreigegeben($erhebungsjahr))
                            	   {?>
                            	   		<div id="layer" onclick="remove_calendar();"></div>
                            			<a id="caldbl" href="javascript:;" title="(TT.MM.JJJJ) Datum auf das die Erklärung datiert wird." onclick="$('.calendar').show();add_calendar(event, 'datum_erklaerung');" ondblclick="$('.calendar').hide(); $('#datum_erklaerung').val('08.09.2017')">
                            				<img src="graphics/calendarsheet.png" border="0">
                            			</a>
            							<input onchange="" title="Datum Erklärung" id="datum_erklaerung" name="datum_erklaerung" value="<?php echo $gewaesserbenutzung->getErklaerungDatum($erhebungsjahr) === null ? date("d.m.Y") : $gewaesserbenutzung->getErklaerungDatum($erhebungsjahr); ?>" type="text" readonly="readonly">
                            	   <?php
                            	   }
                            	   else
                            	   {
                            	       echo $gewaesserbenutzung->getErklaerungDatumHTML($erhebungsjahr);
                            	   }
                            	?>
                            	<?php
                        	    ?>
                            </div>
                        </div>
                        
                        <div class="wasserrecht_display_table_row">
                    		<div class="wasserrecht_display_table_cell_caption">Bearbeiter Erklärung:</div>
                            <div class="wasserrecht_display_table_cell_spacer"></div>
                            <div class="wasserrecht_display_table_cell_white">
                            <?php 
                                echo $gewaesserbenutzung->getErklaerungNutzerHTML($erhebungsjahr);
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
            echo '<h1 style=\"color: red;\">Kein Erhebungsjahr gefunden!<h1>';
        }
    }
    else
    {
        echo '<h1 style=\"color: red;\">Keine Gewässerbenutzung gefunden!<h1>';
    }
}
else
{
    echo '<h1 style=\"color: red;\">Keine Wasserrechtliche Zulassung gefunden!<h1>';
}
?>