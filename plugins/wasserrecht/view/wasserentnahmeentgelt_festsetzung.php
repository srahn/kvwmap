<?php
$wrz = null;
$gewaesserbenutzung = null;
$erhebungsjahr = null;

$speereEingabeFestsetzung = false;
$errorEingabeFestsetzung = null;

$zugelassenesEntnahmeEntgelt = 0;
$nichtZugelassenesEntnahmeEntgelt = 0;
$zugelassenerUmfangEntgeltsatz = 0;
$zugelassenerUmfangEntgelt = 0;

$findDefaultWrz = true;

$isTrue = ["true",1,"t"];
		
// print_r($_REQUEST);

if($_SERVER ["REQUEST_METHOD"] == "POST")
{
    //print_r($_POST);
    
    foreach($_POST as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(startsWith($keyEscaped, "festsetzung_freigeben_"))
        {
            $findDefaultWrz = false;
            festsetzung_freigeben($this, $valueEscaped, $erhebungsjahr, true, $wrz, $gewaesserbenutzung, $errorEingabeFestsetzung, $speereEingabeFestsetzung);
            break;
        }
        if(startsWith($keyEscaped, "festsetzung_speichern_"))
        {
            $findDefaultWrz = false;
            festsetzung_freigeben($this, $valueEscaped, $erhebungsjahr, false, $wrz, $gewaesserbenutzung, $errorEingabeFestsetzung, $speereEingabeFestsetzung);
            break;
        }
    }
}
elseif($_SERVER ["REQUEST_METHOD"] == "GET")
{
//     print_r($_GET);

    foreach($_GET as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
//         print_r($keyEscaped);
        
        $this->log->log_trace('keyEscaped: ' . var_export($keyEscaped, true));
        $this->log->log_trace('valueEscaped: ' . var_export($valueEscaped, true));
        
        if(strtolower($keyEscaped) === GET_FESTSETZUNG_URL)
		{
		    $findDefaultWrz = false;
		    
		    $idValues = findIdAndYearFromValueString($this, $valueEscaped);
		    $this->log->log_debug('idValues: ' . var_export($idValues, true));
		    
		    $festsetzungWrz = new WasserrechtlicheZulassungen($this);
		    $wrz = $festsetzungWrz->find_by_id($this, 'id', $idValues["wrz_id"]);
// 		    var_dump($wrz);
// 		    echo "<br />wrz id: " . $wrz->getId();
		    if((!empty($wrz) && !empty($wrz->getId())))
		    {
// 		        echo "<br />wrz id: " . $wrz->getId();

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
		        
		        if(!empty($gewaesserbenutzung))
		        {
		            //echo "<br />gewaesserbenutzung: " . $gewaesserbenutzung->getId();
		            
		            $erhebungsjahr = $idValues["erhebungsjahr"];
		            
		            if($gewaesserbenutzung->isErklaerungFreigegeben($erhebungsjahr) && $gewaesserbenutzung->isFestsetzungFreigegeben($erhebungsjahr))
		            {
		                $speereEingabeFestsetzung = true;
		            }
		        }
		    }
		    
// 		    echo "findDefaultWrz: " . $findDefaultWrz;
		    break;
		 }
    }
}

function festsetzung_freigeben(&$gui, $valueEscaped, &$erhebungsjahr, $festsetzungFreigeben, &$wrz, &$gewaesserbenutzung, &$errorEingabeFestsetzung, &$speereEingabeFestsetzung)
{
    $gui->log->log_info('*** erklaerung_freigeben ***');
    $gui->log->log_debug('festsetzungFreigeben: ' . $festsetzungFreigeben);
    
    $idValues = findIdAndYearFromValueString($gui, $valueEscaped);
    $gui->log->log_debug('idValues: ' . var_export($idValues, true));
    
    $festsetzungFreigebenWrz = new WasserrechtlicheZulassungen($gui);
    $wrz = $festsetzungFreigebenWrz->find_by_id($gui, 'id', $idValues["wrz_id"]);
    if(!empty($wrz))
    {
        $gb = new Gewaesserbenutzungen($gui);
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
        
        if(!empty($gewaesserbenutzung))
        {
            $erhebungsjahr = $idValues["erhebungsjahr"];
            
            if($errorEingabeFestsetzung === null)
            {
                for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i ++)
                {
                    $teilgewaesserbenutzung_art_benutzung = htmlspecialchars($_POST["teilgewaesserbenutzung_art_benutzung_" . $i]);
                    //                         var_dump($teilgewaesserbenutzung_art_benutzung);
                    $teilgewaesserbenutzung_wiedereinleitung_bearbeiter = htmlspecialchars($_POST["teilgewaesserbenutzung_wiedereinleitung_bearbeiter_" . $i]);
                    //                         var_dump($teilgewaesserbenutzung_wiedereinleitung_bearbeiter);
                    $teilgewaesserbenutzung_befreiungstatbestaende = htmlspecialchars($_POST["teilgewaesserbenutzung_befreiungstatbestaende_" . $i]);
                    //                         var_dump($teilgewaesserbenutzung_befreiungstatbestaende);
                    $freitext = htmlspecialchars($_POST["festsetzung_freitext"]);
                    
                    // check for not filled out lines
                    if (empty($teilgewaesserbenutzung_art_benutzung)
                        && empty($teilgewaesserbenutzung_wiedereinleitung_bearbeiter)
                        && empty($teilgewaesserbenutzung_befreiungstatbestaende))
                    {
                        break;
                    }
                    
                    if (!empty($teilgewaesserbenutzung_art_benutzung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $teilgewaesserbenutzung_art_benutzung) !== 0
                        && !empty($teilgewaesserbenutzung_wiedereinleitung_bearbeiter) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $teilgewaesserbenutzung_wiedereinleitung_bearbeiter) !== 0
                        && !empty($teilgewaesserbenutzung_befreiungstatbestaende) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $teilgewaesserbenutzung_befreiungstatbestaende) !== 0)
                    {
                        
                        $errorEingabeFestsetzung = null;
                        if($festsetzungFreigeben)
                        {
                            $speereEingabeFestsetzung = true;
                        }
                        //                         echo var_dump($speereEingabeFestsetzung);
                        
                        // update an existing teilgewaesserbenutzung
                        $teilgewasserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr);
                        if (!empty($teilgewasserbenutzungen[$i - 1])) {
                            
                            $teilgewaesserbenutzung = $teilgewasserbenutzungen[$i - 1];
                            
                            $berechneter_entgeltsatz_zugelassen = htmlspecialchars($_POST["teilgewaesserbenutzung_berechneter_entgeltsatz_zugelassen_" . $i]);
                            $berechneter_entgeltsatz_nicht_zugelassen = htmlspecialchars($_POST["teilgewaesserbenutzung_berechneter_entgeltsatz_nicht_zugelassen_" . $i]);
                            $berechnetes_entgelt_zugelassen = htmlspecialchars($_POST["teilgewaesserbenutzung_berechnetes_entgelt_zugelassen_" . $i]);
                            $berechnetes_entgelt_nicht_zugelassen = htmlspecialchars($_POST["teilgewaesserbenutzung_berechnetes_entgelt_nicht_zugelassen_" . $i]);
                            
                            if((empty($berechneter_entgeltsatz_zugelassen) || is_numeric($berechneter_entgeltsatz_zugelassen))
                                && (empty($berechneter_entgeltsatz_nicht_zugelassen) || is_numeric($berechneter_entgeltsatz_nicht_zugelassen))
                                && (empty($berechnetes_entgelt_zugelassen) || is_numeric($berechnetes_entgelt_zugelassen))
                                && (empty($berechnetes_entgelt_nicht_zugelassen) || is_numeric($berechnetes_entgelt_nicht_zugelassen)))
                            {
                                $teilgewaesserbenutzungId = $teilgewaesserbenutzung->updateTeilgewaesserbenutzung_Bearbeiter($erhebungsjahr, $teilgewaesserbenutzung_art_benutzung, $teilgewaesserbenutzung_wiedereinleitung_bearbeiter, $teilgewaesserbenutzung_befreiungstatbestaende, $freitext, $berechneter_entgeltsatz_zugelassen, $berechneter_entgeltsatz_nicht_zugelassen, $berechnetes_entgelt_zugelassen, $berechnetes_entgelt_nicht_zugelassen);
                                $gui->add_message('notice', 'Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId . ') erfolgreich geändert!');
                                $gui->log->log_success('Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId . ') erfolgreich geändert!');
                            }
                            else
                            {
                                $errorEingabeFestsetzung = $i;
                                break;
                            }
                        }                        // else --> if not there --> create one
                        else {
                            $errorEingabeFestsetzung = $i;
                            break;
                        }
                    }
                    else
                    {
                        $errorEingabeFestsetzung = $i;
                        break;
                    }
                }
            }
            
            if($errorEingabeFestsetzung === null)
            {
                if($festsetzungFreigeben)
                {
                    $festsetzungsNutzer = $gui->user->Vorname . ' ' . $gui->user->Name;
                    
                    $summe_nicht_zugelassene_entnahmemengen = htmlspecialchars($_POST["summe_nicht_zugelassene_entnahmemengen"]);
                    $summe_zugelassene_entnahmemengen = htmlspecialchars($_POST["summe_zugelassene_entnahmemengen"]);
                    $summe_entnahmemengen = htmlspecialchars($_POST["summe_entnahmemengen"]);
                    
                    $summe_nicht_zugelassenes_entnahme_entgelt = htmlspecialchars($_POST["summe_nicht_zugelassenes_entnahme_entgelt"]);
                    $summe_zugelassenes_entnahme_entgelt = htmlspecialchars($_POST["summe_zugelassenes_entnahme_entgelt"]);
                    $summe_ennahme_entgelt = htmlspecialchars($_POST["summe_entnahme_entgelt"]);
                    
                    $gewaesserbenutzung->insertFestsetzungWithoutDokument($erhebungsjahr, null, $festsetzungsNutzer, 
                        $summe_nicht_zugelassene_entnahmemengen, $summe_zugelassene_entnahmemengen, $summe_entnahmemengen, 
                        $summe_nicht_zugelassenes_entnahme_entgelt, $summe_zugelassenes_entnahme_entgelt, $summe_ennahme_entgelt);
                }
                
                // update gewaesserbenutzungen, because teilgewaesserbenutzungen where added
                $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
                $gewaesserbenutzung = $gewaesserbenutzungen[0];
            } else {
                if ($errorEingabeFestsetzung > 0) {
                    $gui->add_message('error', 'Eingabe in Zeile ' . $errorEingabeFestsetzung . ' ist fehlerhaft oder nicht vollständig! Bitte überprüfen Sie Ihre Angaben!');
                    $gui->log->log_error('Eingabe in Zeile ' . $errorEingabeFestsetzung . ' ist fehlerhaft oder nicht vollständig! Bitte überprüfen Sie Ihre Angaben!');
                }
            }
        }
        else
        {
            $gui->add_message('error', 'Keine gültige Gewässerbenutzung gefunden!');
            $gui->log->log_error('Keine gültige Gewässerbenutzung gefunden!');
        }
    }
    else
    {
        $gui->add_message('error', 'Keine gültige WrZ gefunden!');
        $gui->log->log_error('Keine gültige WrZ gefunden!');
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
        
        if($gewaesserbenutzung->isFestsetzungFreigegeben($erhebungsjahr))
        {
            $speereEingabeFestsetzung = true;
        }
    }
}

if(!empty($wrz) && !empty($wrz->getId()))
{
        $wrz->getDependentObjects($this, $wrz);
//         echo "findDefaultWrz: " . $findDefaultWrz;
        if($findDefaultWrz && empty($gewaesserbenutzung) && !empty($wrz->gewaesserbenutzungen) && count($wrz->gewaesserbenutzungen) > 0 && !empty($wrz->gewaesserbenutzungen[0]))
        {
            $gewaesserbenutzung = $wrz->gewaesserbenutzungen[0];
        }
        
        if(!empty($gewaesserbenutzung))
        {
            if(!empty($gewaesserbenutzung->gewaesserbenutzungenUmfang))
            {
                if(!empty($erhebungsjahr))
                {
                    if($gewaesserbenutzung->isErklaerungFreigegeben($erhebungsjahr))
                    {
                        $tab1_id=WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL;
                        $tab1_name="Erklärung der Entnahme";
                        $tab1_active=false;
                        $tab1_visible=true;
                        $tab2_id=WASSERENTNAHMEENTGELT_FESTSETZUNG_URL;
                        $tab1_extra_parameter_key=GET_ERKLAERUNG_URL;
                        $tab1_extra_parameter_value=$wrz->getId() . "_" . $gewaesserbenutzung->getId() . "_" . $erhebungsjahr;
                        $tab2_name="Festsetzung";
                        $tab2_active=true;
                        $tab2_visible=true;
                        include_once ('includes/header.php');
                        
                        ?>
    
                	<div id="<?php echo WASSERENTNAHMEENTGELT_FESTSETZUNG_URL ?>" class="tabcontent" style="display: inline">
                
                    	<form action="index.php" id="festsetzung_freigeben_form" accept-charset="" method="POST">
                        		
                    		<?php 
                    		     include_once ('includes/wasserentnahmeentgelt_header.php'); 
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
                           		  <?php
                           		  
                           		  $zugelassenerUmfangEntgeltsatz = $gewaesserbenutzung->getErlaubterOderReduzierterUmfang();
                           		  $zugelassenerUmfangEntgelt = $zugelassenerUmfangEntgeltsatz;
                           		  
                           		  $teilgewasserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr);
                                  for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++) 
                                  {
                                          $teilgewaesserbenutzung = null;
                                          if(!empty($teilgewasserbenutzungen) && count($teilgewasserbenutzungen) > 0 
                                              && count($teilgewasserbenutzungen) > ($i - 1) && !empty($teilgewasserbenutzungen[$i -1]))
                                          {
                                              $teilgewaesserbenutzung = $teilgewasserbenutzungen[$i - 1];
                //                               var_dump($teilgewaesserbenutzung->gewaesserbenutzungArt->getName());
                //                               echo "<br>teilgewaesserbenutzung: " . var_dump($teilgewaesserbenutzung->gewaesserbenutzungArt->getId());
                
                                              if(!empty($teilgewaesserbenutzung))
                                              {
                                                  //Art Benutzung
                                                  $getArtBenutzung = null;
                                                  if(!empty(htmlspecialchars($_REQUEST['teilgewaesserbenutzung_art_benutzung_' . $i])))
                                                  {
                                                      if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['teilgewaesserbenutzung_art_benutzung_' . $i])) !== 0)
                                                      {
                                                          $getArtBenutzung = htmlspecialchars($_REQUEST['teilgewaesserbenutzung_art_benutzung_' . $i]);
                                                      }
                                                      else
                                                      {
                                                          $getArtBenutzung = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                                      }
                                                  }
                                                  else
                                                  {
                                                      if(!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->art_benutzung))
                                                      {
                                                          $getArtBenutzung = $teilgewaesserbenutzung->art_benutzung->getId();
                                                      }
                                                  }
                                                  
                                                  //Wiedereinleitung Bearbeiter
                                                  $getWiedereinleitungBearbeiter = null;
                                                  if(!empty(htmlspecialchars($_REQUEST['teilgewaesserbenutzung_wiedereinleitung_bearbeiter_' . $i])))
                                                  {
                                                      if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['teilgewaesserbenutzung_wiedereinleitung_bearbeiter_' . $i])) !== 0)
                                                      {
                                                          $getWiedereinleitungBearbeiter = htmlspecialchars($_REQUEST['teilgewaesserbenutzung_wiedereinleitung_bearbeiter_' . $i]);
                                                          $getWiedereinleitungBearbeiter = in_array(strtolower($getWiedereinleitungBearbeiter), $isTrue);
                                                      }
                                                      else
                                                      {
                                                          $getWiedereinleitungBearbeiter = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                                      }
                                                  }
                                                  else
                                                  {
                                                      if(!empty($teilgewaesserbenutzung))
                                                      {
                                                          $getWiedereinleitungBearbeiter = $teilgewaesserbenutzung->getWiedereinleitungBearbeiter();
                                                          if(!is_null($getWiedereinleitungBearbeiter))
                                                          {
                                                              $getWiedereinleitungBearbeiter = in_array(strtolower($getWiedereinleitungBearbeiter), $isTrue);
                                                          }
                                                          else
                                                          {
                                                              $getWiedereinleitungBearbeiter = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                                          }
                                                      }
                                                  }
                //                                   echo "getWiedereinleitungBearbeiter: " . var_dump($getWiedereinleitungBearbeiter);
                                                  
                                                  //Befreiungstatbestände
                                                  $getBefreiungstatbestaende = null;
                                                  if(!empty(htmlspecialchars($_REQUEST['teilgewaesserbenutzung_befreiungstatbestaende_' . $i])))
                                                  {
                                                      if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['teilgewaesserbenutzung_befreiungstatbestaende_' . $i])) !== 0)
                                                      {
                                                          $getBefreiungstatbestaende = htmlspecialchars($_REQUEST['teilgewaesserbenutzung_befreiungstatbestaende_' . $i]);
                                                          $getBefreiungstatbestaende = in_array(strtolower($getBefreiungstatbestaende), $isTrue);
                                                      }
                                                      else
                                                      {
                                                          $getBefreiungstatbestaende = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                                      }
                                                  }
                                                  else
                                                  {
                                                      if(!empty($teilgewaesserbenutzung))
                                                      {
                                                          $getBefreiungstatbestaende = $teilgewaesserbenutzung->getBefreiungstatbestaende();
                                                          if(!is_null($getBefreiungstatbestaende))
                                                          {
                                                              $getBefreiungstatbestaende = in_array(strtolower($getBefreiungstatbestaende), $isTrue);
                                                          }
                                                          else
                                                          {
                                                              $getBefreiungstatbestaende = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                                          }
                                                      }
                                                  }
                //                                   echo "getBefreiungstatbestaende: " . var_dump($getBefreiungstatbestaende);
                                                  
                                                  if($errorEingabeFestsetzung === $i)
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
                                              <td><?php echo !empty($teilgewaesserbenutzung->gewaesserbenutzungArt) ? $teilgewaesserbenutzung->gewaesserbenutzungArt->getName() : "" ?></td>
                                              <td><?php echo !empty($teilgewaesserbenutzung->gewaesserbenutzungZweck) ? $teilgewaesserbenutzung->gewaesserbenutzungZweck->getName() : "" ?></td>
                                              <td><?php echo !empty($teilgewaesserbenutzung->getUmfang()) ? $teilgewaesserbenutzung->getUmfang() : "" ?></td>
                                              <td><?php echo !empty($teilgewaesserbenutzung->getWiedereinleitungNutzer()) && $teilgewaesserbenutzung->getWiedereinleitungNutzer() === "t" ? "ja" : "nein" ?></td>
                                              <td><?php echo !empty($teilgewaesserbenutzung->mengenbestimmung) ? $teilgewaesserbenutzung->mengenbestimmung->getName() : "" ?></td>
                                              <td>
                                              	<select name="teilgewaesserbenutzung_art_benutzung_<?php echo $i; ?>" onchange="setNewTab('<?php echo WASSERENTNAHMEENTGELT_FESTSETZUNG_URL ?>',{'<?php echo GET_FESTSETZUNG_URL ?>':'<?php echo $wrz->getId() . "_" . $gewaesserbenutzung->getId() . "_" . $erhebungsjahr ?>','teilgewaesserbenutzung_art_benutzung_<?php echo $i; ?>':this.value})" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>
                                            		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                            		<option value="1" <?php echo!is_null($getArtBenutzung) && $getArtBenutzung === "1" ?  'selected' : ''?>>GW</option>
                                            		<option value="2" <?php echo !is_null($getArtBenutzung) && $getArtBenutzung === "2" ?  'selected' : ''?>>OW</option>
                                            	</select>
                                              </td>
                                              <td>
                                              	<select name="teilgewaesserbenutzung_wiedereinleitung_bearbeiter_<?php echo $i; ?>" onchange="setNewUrlParameter(this,'teilgewaesserbenutzung_wiedereinleitung_bearbeiter_<?php echo $i; ?>')" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>
                                            		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                            		<option value="true" <?php echo strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getWiedereinleitungBearbeiter) !== 0 && $getWiedereinleitungBearbeiter ?  'selected' : ''?>>ja</option>
                                            		<option value="false" <?php echo strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getWiedereinleitungBearbeiter) !== 0 && !$getWiedereinleitungBearbeiter ?  'selected' : ''?>>nein</option>
                                            	</select>
                                              </td>
                                              <td>
                                              	<select name="teilgewaesserbenutzung_befreiungstatbestaende_<?php echo $i; ?>" onchange="setNewUrlParameter(this,'teilgewaesserbenutzung_befreiungstatbestaende_<?php echo $i; ?>')" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>
                                            		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                            		<option value="true" <?php echo strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getBefreiungstatbestaende) !== 0 && $getBefreiungstatbestaende ?  'selected' : ''?>>ja</option>
                                            		<option value="false" <?php echo strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getBefreiungstatbestaende) !== 0 && !$getBefreiungstatbestaende ?  'selected' : ''?>>nein</option>
                                            	</select>
                                              </td>
                                              <td>
                                              	<?php
                //                                       	var_dump($getArtBenutzung);
                //                                       	var_dump($getBefreiungstatbestaende);
                //                                       	var_dump($getWiedereinleitungBearbeiter);
                                                      	if (!empty($getArtBenutzung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getArtBenutzung) !== 0
                                                      	    && !is_null($getWiedereinleitungBearbeiter) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getWiedereinleitungBearbeiter) !== 0
                                                      	    && !is_null($getBefreiungstatbestaende) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getBefreiungstatbestaende) !== 0)
                                                          	{
                                                          	    $berechneter_entgeltsatz = $gewaesserbenutzung->getTeilgewaesserbenutzungEntgeltsatz($erhebungsjahr, $teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, $zugelassenesEntnahmeEntgelt, $nichtZugelassenesEntnahmeEntgelt, $zugelassenerUmfangEntgeltsatz);
                                                          	    if(count($berechneter_entgeltsatz) === 1) //nur zugelasser Umfang
                                                          	    {
                                                          	        echo $berechneter_entgeltsatz[0] . " (zugelassener Umfang)";
                                                          	        echo "<input type='hidden' name='teilgewaesserbenutzung_berechneter_entgeltsatz_zugelassen_" . $i . "' value='" . $berechneter_entgeltsatz[0] . "' />";
                                                          	    }
                                                          	    elseif (count($berechneter_entgeltsatz) === 2) //zugelassener und nicht zugelassener Umfang ODER nur nicht zugelassener Umfang
                                                          	    {
                                                          	        echo !empty($berechneter_entgeltsatz[0]) ? $berechneter_entgeltsatz[0] . " (zugelassener Umfang)<br />" . $berechneter_entgeltsatz[1] . " (nicht zugelassener Umfang)" : $berechneter_entgeltsatz[1] . " (nicht zugelassener Umfang)";
                                                          	        echo "<input type='hidden' name='teilgewaesserbenutzung_berechneter_entgeltsatz_zugelassen_" . $i . "' value='" . $berechneter_entgeltsatz[0] . "' />";
                                                          	        echo "<input type='hidden' name='teilgewaesserbenutzung_berechneter_entgeltsatz_nicht_zugelassen_" . $i . "' value='" . $berechneter_entgeltsatz[1] . "' />";
                                                          	    }
                                                          	    elseif (count($berechneter_entgeltsatz) === 3) // Error
                                                          	    {
                                                          	        echo $berechneter_entgeltsatz[2];
                                                          	    }
                                                          	}
                                                      	?>
                                              </td>
                                              <td>
                                              	<?php
                                                  	if (!empty($getArtBenutzung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getArtBenutzung) !== 0
                                                  	    && !is_null($getWiedereinleitungBearbeiter) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getWiedereinleitungBearbeiter) !== 0
                                                  	    && !is_null($getBefreiungstatbestaende) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getBefreiungstatbestaende) !== 0)
                                                  	{
                                                  	    $berechnetes_entgelt =  $gewaesserbenutzung->getTeilgewaesserbenutzungEntgelt($erhebungsjahr, $teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, $zugelassenesEntnahmeEntgelt, $nichtZugelassenesEntnahmeEntgelt, $zugelassenerUmfangEntgelt);
                                                  	    if(count($berechnetes_entgelt) === 1) //nur zugelasser Umfang
                                                  	    {
                                                  	        echo $berechnetes_entgelt[0] . " (zugelassener Umfang)";
                                                  	        echo "<input type='hidden' name='teilgewaesserbenutzung_berechnetes_entgelt_zugelassen_" . $i ."' value='" . $berechnetes_entgelt[0] . "' />";
                                                  	    }
                                                  	    elseif (count($berechnetes_entgelt) === 2) //zugelassener und nicht zugelassener Umfang ODER nur nicht zugelassener Umfang
                                                  	    {
                                                  	        echo !empty($berechnetes_entgelt[0]) ? $berechnetes_entgelt[0] . " (zugelassener Umfang)<br />" . $berechnetes_entgelt[1] . " (nicht zugelassener Umfang)" : $berechnetes_entgelt[1] . " (nicht zugelassener Umfang)";
                                                  	        echo "<input type='hidden' name='teilgewaesserbenutzung_berechnetes_entgelt_zugelassen_" . $i . "' value='" . $berechnetes_entgelt[0] . "' />";
                                                  	        echo "<input type='hidden' name='teilgewaesserbenutzung_berechnetes_entgelt_nicht_zugelassen_" . $i . "' value='" . $berechnetes_entgelt[1] . "' />";
                                                  	    }
                                                  	    elseif (count($berechneter_entgeltsatz) === 3) // Error
                                                  	    {
                                                  	        echo $berechnetes_entgelt[2];
                                                  	    }
                                                  	}
                                              	    
                                              	?>
                                              </td>
                                          </tr>
                                           <?php
                                              }
                                          }
                                      }
                                  ?>
                                  <tr>
                                  	<td></td>
                                  	<td></td>
                                  	<td>Zugelassene Entnahmemenge:</td>
                                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_zugelassene_entnahmemengen" name="summe_zugelassene_entnahmemengen" readonly="readonly" value="<?php echo $gewaesserbenutzung->getEntnahmemenge($erhebungsjahr, true) ?>"></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td>Zugelassene Entnahme Entgelt:</td>
                                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_zugelassenes_entnahme_entgelt" name="summe_zugelassenes_entnahme_entgelt" readonly="readonly" value="<?php echo $zugelassenesEntnahmeEntgelt ?>"></td>
                                  </tr>
                                  <tr>
                                  	<td></td>
                                  	<td></td>
                                  	<td>Nicht zugelassene Entnahmemenge:</td>
                                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_nicht_zugelassene_entnahmemengen" name="summe_nicht_zugelassene_entnahmemengen" readonly="readonly" value="<?php echo $gewaesserbenutzung->getEntnahmemenge($erhebungsjahr, false) ?>"></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td>Nicht zugelassene Entnahmeentgelt:</td>
                                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_nicht_zugelassenes_entnahme_entgelt" name="summe_nicht_zugelassenes_entnahme_entgelt" readonly="readonly" value="<?php echo $nichtZugelassenesEntnahmeEntgelt ?>"></td>
                                  </tr>
                                  <tr>
                                  	<td></td>
                                  	<td></td>
                                  	<td>Summe Entnahmemengen:</td>
                                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_entnahmemengen" name="summe_entnahmemengen" readonly="readonly" value="<?php echo $gewaesserbenutzung->getUmfangAllerTeilbenutzungen($erhebungsjahr) ?>"></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td></td>
                                  	<td>Summe Entgelt:</td>
                                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_entnahme_entgelt" name="summe_entnahme_entgelt" readonly="readonly" value="<?php echo $zugelassenesEntnahmeEntgelt + $nichtZugelassenesEntnahmeEntgelt ?>"></td>
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
                                  	<td>Summe gebucht:</td>
                                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_entnahme_entgelt_gebucht" name="summe_entnahme_entgelt_gebucht" readonly="readonly" value=""></td>
                                  </tr>
                              </table>
                           
                           <div class="wasserrecht_display_table" style="margin-top: 20px; margin-left: 15px">
                               <label for="festsetzung_freitext">Festsetzung Freitext:</label>
                               <?php
                                   $teilgewasserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr);
                                   $teilgewaesserbenutzung = null;
                                   if(!empty($teilgewasserbenutzungen) && count($teilgewasserbenutzungen) > 0 && !empty($teilgewasserbenutzungen[0]))
                                   {
                                       $teilgewaesserbenutzung = $teilgewasserbenutzungen[0];
                                       //var_dump($teilgewaesserbenutzung);
                                   }
                               ?>
                               <textarea rows="10" cols="180" id="festsetzung_freitext" name="festsetzung_freitext" style="display: block;" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>><?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->getFreitext()) ? $teilgewaesserbenutzung->getFreitext() : ""; ?></textarea>
                           </div>
                           
                           <div class="wasserrecht_display_table" style="margin-top: 20px; margin-left: 15px">
                            
                                <div class="wasserrecht_display_table_row">
                                    <div class="wasserrecht_display_table_cell_caption">Erklärung oder Schätzung:</div>
                                    <div class="wasserrecht_display_table_cell_spacer"></div>
                                    <div class="wasserrecht_display_table_cell_white">
                                    	<?php 
                //                         	$teilgewaesserbenutzung = null;
                //                         	if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0 && !empty($gewaesserbenutzung->teilgewaesserbenutzungen[0]))
                //                         	{
                //                         	    $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[0];
                //                         	    //var_dump($teilgewaesserbenutzung);
                //                         	}
                                        	echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->teilgewaesserbenutzungen_art) ? $teilgewaesserbenutzung->teilgewaesserbenutzungen_art->getName() : "";
                                    	?>
                                     </div>
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
                                            echo '<a href="' . $this->actual_link . '?go=' . WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL . '&' . GET_ERKLAERUNG_URL . '=' . $wrz->getId() . "_" . $gewaesserbenutzung->getId() . "_" . $erhebungsjahr .'">' . $gewaesserbenutzung->getErklaerungDatumHTML($erhebungsjahr) . '</a>';
                                	    ?>
                                    </div>
                                </div>
                                
                                <div class="wasserrecht_display_table_row">
                            		<div class="wasserrecht_display_table_cell_caption">Bearbeiter Erklärung:</div>
                                    <div class="wasserrecht_display_table_cell_spacer"></div>
                                    <div class="wasserrecht_display_table_cell_white">
                                    <?php 
                                          echo $gewaesserbenutzung->getErklaerungNutzer($erhebungsjahr);
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
                            			<input type="hidden" name="go" value="<?php echo WASSERENTNAHMEENTGELT_FESTSETZUNG_URL ?>">
                						<button class="wasserrecht_button" name="festsetzung_speichern_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" type="submit" id="festsetzung_speichern_button_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>Festsetzung speichern</button>
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
                						<button class="wasserrecht_button" name="festsetzung_freigeben_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" value="<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" type="submit" id="festsetzung_freigeben_button_<?php echo $wrz->getId(); ?>_<?php echo $gewaesserbenutzung->getId(); ?>_<?php echo $erhebungsjahr; ?>" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>Festsetzung freigeben</button>
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
                                        echo $gewaesserbenutzung->getFestsetzungDatumHTML($erhebungsjahr);
                                	 ?>
                                    </div>
                                </div>
                                
                                <div class="wasserrecht_display_table_row">
                            		<div class="wasserrecht_display_table_cell_caption">Bearbeiter Freigabe:</div>
                                    <div class="wasserrecht_display_table_cell_spacer"></div>
                                    <div class="wasserrecht_display_table_cell_white">
                                  	<?php 
                                  	     echo $gewaesserbenutzung->getFestsetzungNutzerHTML($erhebungsjahr);
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
                				<?php 
                				    if(!empty($gewaesserbenutzung->isFestsetzungDokumentErstellt($erhebungsjahr)))
                    				{
                    				    $festsetzungDokument = $gewaesserbenutzung->getFestsetzungDokument($erhebungsjahr);
                    				    if(!empty($festsetzungDokument))
                    				    {
                    				        $absoluteURL = getDocumentUrlFromPath($this, $festsetzungDokument->getPfad())
                    				        
                    				        ?>
                            				<div class="wasserrecht_display_table_row">
                                                <div class="wasserrecht_display_table_cell_caption">
                                					<?php
                                					   echo '<a href="' . $absoluteURL . '" target="_blank">' . $festsetzungDokument->getName() . '</a>';
                                					?>
                                       			</div>
                            				</div>
                    					<?php
                    				    }
                    				}
                				?>
                				
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
                                    	   if($gewaesserbenutzung->isFestsetzungDokumentErstellt($erhebungsjahr))
                                	       {
                                	           echo $gewaesserbenutzung->getFestsetzungDokumentDatum($erhebungsjahr);
                                	       }
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
                        echo '<h1 style=\"color: red;\">Erklärung wurde noch nicht freigegeben!<h1>';
                    }
                }
                else
                {
                    echo '<h1 style=\"color: red;\">Kein Erhebungsjahr gefunden!<h1>';
                }
            }
            else
            {
                echo '<h1 style=\"color: red;\">Kein Gewässerbenutzungsumfang gefunden!<h1>';
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