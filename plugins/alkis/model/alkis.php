<?php
#--------------------------------------------------------------------------------------------------------------
################
# Klasse ALKIS #
################

class ALKIS {
  var $debug;
  var $database;

  function __construct($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $database->setDebugLevel=1;
  }
	
	function dhk_wsdl_login(){
		$params = Array(
			'soap_version' => 'SOAP_1_1',
			'login' => "rahn", 
			'password' => "d1dPv_r*",
			'trace' => 1,
			'exceptions' => 1
		);
		$client = new SoapClient('http://webdhk-vr.lk-vr.de:8090/?wsdl', $params);
		$auth = array(
        'Username'=>'rahn',
        'Password'=>'d1dPv_r*',
    );
		#$header = new SoapHeader('http://webdhk-vr.lk-vr.de:8090/?wsdl','UserIdentifierSoapHeaderIn',$auth,false);
		#$header = new SoapHeader('http://webdhk-vr.lk-vr.de:8090/?wsdl','SOAPAction','getCapabilities',false);
		$client->__setSoapHeaders($header);
		#$functions = $client->__getFunctions();
		#print_r($functions);
		$response = $client->getCapabilities();
		echo "Request-Headers:\n".$client->__getLastRequestHeaders();
		echo "Response-Headers:\n".$client->__getLastResponseHeaders();
		echo $client->__getLastRequest();
		var_dump($response);
	}
	

	
	function dhk_call_login($url, $username, $password){
		$data = 'cmd=login&j_username='.$username.'&j_password='.$password;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		curl_close($ch);
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE,1);
		xml_parse_into_struct($parser, $result, $values, $index);
		xml_parser_free($parser);
		return $values[$index['JSESSIONID'][0]]['value'];
	}
		
	function dhk_call_getPDF($url, $sessionid, $nasfile, $filename){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if (version_compare(phpversion(), '5.5.0', '<')) {
			$curl_file = '@'.$nasfile;
		}
		else {
			curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
			$curl_file = new CURLFILE($nasfile, 'text/xml', 'nasfile');
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('cmd' => 'ausfuehren', 'jsessionid' => $sessionid, 'nasfile' => $curl_file));
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	function create_nas_request_xml_file($FlurstKennz, $Grundbuchbezirk, $Grundbuchblatt, $Buchnungstelle, $print_params, $formnummer){
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		
<CPA_Benutzungsauftrag 
 xmlns="http://www.cpa-systems.de/namespaces/adv/gid/7.1" 
 xmlns:cpa="http://www.cpa-systems.de/namespaces/adv/gid/7.1"
 xmlns:ext="http://www.supportgis.de/cpa" 
 xmlns:adv="http://www.adv-online.de/namespaces/adv/gid/7.1" 
 xmlns:gco="http://www.isotc211.org/2005/gco" 
 xmlns:gmd="http://www.isotc211.org/2005/gmd" 
 xmlns:gml="http://www.opengis.net/gml/3.2" 
 xmlns:gmlcov="http://www.opengis.net/gmlcov/1.0" 
 xmlns:gmlexr="http://www.opengis.net/gml/3.3/exr" 
 xmlns:ows="http://www.opengis.net/ows/1.1" 
 xmlns:wfs="http://www.opengis.net/wfs/2.0" 
 xmlns:wfsext="http://www.adv-online.de/namespaces/adv/gid/wfsext/2.0" 
 xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
 xmlns:xlink="http://www.w3.org/1999/xlink" 
 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
 xmlns:fes="http://www.opengis.net/fes/2.0" 
 xsi:schemaLocation="http://www.cpa-systems.de/namespaces/adv/gid/7.1 AAA-Extensions_CPA.xsd">

	<empfaenger>
		<AA_Empfaenger>
			<adv:direkt>true</adv:direkt>
		</AA_Empfaenger>
	</empfaenger>
	<ausgabeform>application/xml</ausgabeform>
	<art xlink:href="https://registry.gdi-de.org/codelist/de.adv-online.gid/AA_Anlassart_Benutzungsauftrag/'.$formnummer.'"/>
	<koordinatenreferenzsystem xlink:href="urn:adv:crs:ETRS89_UTM33"/>
	';
	
	if($print_params == NULL) $xml .= '<anforderungsmerkmale>';
	
	switch($formnummer){
		case '0110' : case '0111' : case '0120' : case '0121' : {   
			$xml .= '
			<zentrumskoordinate>
				<gml:Point srsName="urn:adv:crs:ETRS89_UTM33">
					<gml:pos>'.$print_params['coord'].'</gml:pos>
				</gml:Point>	
			</zentrumskoordinate>';
		}break;
	
		case 'MV0700' : {   
			$xml .= '
			<wfs:Query typeNames="adv:AX_Buchungsblatt">
				<fes:Filter>
					<fes:PropertyIsEqualTo>
						<fes:ValueReference>buchungsblattkennzeichen</fes:ValueReference>
						<fes:Literal>'.$Grundbuchbezirk.str_pad($Grundbuchblatt, 7, '0',STR_PAD_LEFT).'</fes:Literal>
					</fes:PropertyIsEqualTo>';
		}break;
		
		case 'MV0600' : {   
			$xml .= '
			<wfs:Query typeNames="adv:AX_Buchungsstelle">
				<fes:Filter>
					<fes:PropertyIsEqualTo>
						<fes:ValueReference>id</fes:ValueReference>
						<fes:Literal>'.$Buchnungstelle.'</fes:Literal>
					</fes:PropertyIsEqualTo>';
		}break;
		
		default : {
			$xml .= '
			<wfs:Query typeNames="adv:AX_Flurstueck">
			<fes:Filter>';
			if(count($FlurstKennz) > 1)$xml .= '<fes:Or>';
			foreach($FlurstKennz as $flurst){
				$xml .= '
				<fes:PropertyIsEqualTo>
					<fes:ValueReference>flurstueckskennzeichen</fes:ValueReference>
					<fes:Literal>'.$flurst.'</fes:Literal>
				</fes:PropertyIsEqualTo>';
			}
			if(count($FlurstKennz) > 1)$xml .= '</fes:Or>';
		}
	}
	
	if($print_params == NULL){
		$xml .='
					</fes:Filter>
				</wfs:Query>
		</anforderungsmerkmale>';
	}

  # check ob die Konstanten existieren. Kann in einer Version > 2.4 rausgenommen werden.
  if (!defined('DHK_CALL_PROFILKENNUNG')) define('DHK_CALL_PROFILKENNUNG', 'mvaaa');
  if (!defined('DHK_CALL_ANTRAGSNUMMER')) define('DHK_CALL_ANTRAGSNUMMER', 'BWAPK_0000002');

	$xml .='
	<profilkennung>' . DHK_CALL_PROFILKENNUNG . '</profilkennung>
	<antragsnummer>' . DHK_CALL_ANTRAGSNUMMER . '</antragsnummer>
	<selektionsmassstab>1000</selektionsmassstab>
	<mitMetadaten>false</mitMetadaten>
	<folgeverarbeitung>
		<CPA_FOLGEVA>
			<ausgabemasstab>'.$print_params['printscale'].'</ausgabemasstab>
			<formatangabe>'.$print_params['format'].'</formatangabe>
			<ausgabemedium>1000</ausgabemedium>
			<datenformat>5000</datenformat>
		</CPA_FOLGEVA>
	</folgeverarbeitung>
	<auftragsnummer>' . DHK_CALL_ANTRAGSNUMMER . '</auftragsnummer>
	<portionierung></portionierung>
	<konvertierungskonfig></konvertierungskonfig>
</CPA_Benutzungsauftrag>';
	
		$currenttime = date('Y-m-d_H_i_s',time());
		$nasfile = IMAGEPATH.'nas_call_'.$currenttime.'-'.rand(0, 1000000).'.xml';
		file_put_contents($nasfile, $xml);
		return $nasfile;
	}

  function export_klassifizierung_csv($flurstuecke, $formvars){
		if($formvars['flurstkennz']){ $csv .= 'FlstKZ;';}
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ_kurz;';}
    if($formvars['gemkgname']){ $csv .= 'Gemkg-Name;';}
    if($formvars['gemkgschl']){ $csv .= 'Gemkg-Schl.;';}
    if($formvars['flurnr']){ $csv .= 'Flur;';}
    if($formvars['gemeindename']){ $csv .= 'Gem-Name;';}
    if($formvars['gemeinde']){ $csv .= 'Gemeinde;';}
    if($formvars['kreisname']){ $csv .= 'Kreisname;';}
    if($formvars['kreisid']){ $csv .= 'Kreisschlüssel;';}
    if($formvars['finanzamtname']){ $csv .= 'Finanzamtname;';}
    if($formvars['finanzamt']){ $csv .= 'Finanzamtschlüssel;';}
    if($formvars['forstname']){ $csv .= 'Forstamtname;';}
    if($formvars['forstschluessel']){ $csv .= 'Forstamtschlüssel;';}
    if($formvars['flaeche']){ $csv .= 'amtliche Fläche;';}
    if($formvars['amtsgerichtnr']){ $csv .= 'Amtsgericht;';}
    if($formvars['amtsgerichtname']){ $csv .= 'Amtsgerichtname;';}
    if($formvars['grundbuchbezirkschl']){ $csv .= 'GBBschlüssel;';}
    if($formvars['grundbuchbezirkname']){ $csv .= 'GBBname;';}
    if($formvars['lagebezeichnung']){ $csv .= 'Lage;';}
    if($formvars['entsteh']){ $csv .= 'Entstehung;';}
    if($formvars['letzff']){ $csv .= 'Fortführung;';}
    if($formvars['karte']){ $csv .= 'Flurkarte;';}
    if($formvars['status']){ $csv .= 'Status;';}
    if($formvars['vorgaenger']){ $csv .= 'Vorgaenger;';}
    if($formvars['nachfolger']){ $csv .= 'Nachfolger;';}
    $csv .= 'Klassifizierung;Klass-Fläche;EMZ;gesamt;';
    if($formvars['freitext']){ $csv .= 'Freitext;';}
    if($formvars['hinweis']){ $csv .= 'Hinweis;';}
    if($formvars['baulasten']){ $csv .= 'Baulasten;';}
    if($formvars['ausfstelle']){ $csv .= 'ausführende Stelle;';}
    if($formvars['verfahren']){ $csv .= 'Verfahren;';}
    if($formvars['nutzung']){ $csv .= 'Nutzung;';}
    if($formvars['blattnr']){ $csv .= 'Blattnummer;';}
    if($formvars['pruefzeichen']){ $csv .= 'P Buchung;';}
  	if($formvars['pruefzeichen_f']){ $csv .= 'P Flurstück;';}
    if($formvars['bvnr']){ $csv .= 'BVNR;';}
    if($formvars['buchungsart']){ $csv .= 'Buchungsart;';}
    if($formvars['eigentuemer']){ $csv .= 'Namensnummer;Eigentümer;';}
		if($formvars['abweichenderrechtszustand']){ $csv .= 'abweichender Rechtszustand;';}
		if($formvars['baubodenrecht']){ $csv .= 'Bauraum/Bodenordnungsrecht;';}
    
    $csv .= chr(10);
    for($i = 0; $i < count_or_0($flurstuecke); $i++) {
      $flurstkennz = $flurstuecke[$i];
      $flst = new flurstueck($flurstkennz,$this->database);
      $flst->readALB_Data($flurstkennz, true, 'ogc_fid');
			$flst->Grundbuecher=$flst->getGrundbuecher();
			$flst->Buchungen=$flst->getBuchungen(NULL,NULL,0);
			$emzges_a = $flaeche_a = $emzges_agr = $flaeche_agr = $emzges_gr = $flaeche_gr = $emzges_gra = $flaeche_gra = 0;			
			
			$limit = count_or_0($flst->Klassifizierung)+2;
      for($kl = 0; $kl < $limit; $kl++){
				if($kl == $limit-2){              
					$nichtgeschaetzt=$flst->Klassifizierung['nicht_geschaetzt'];
					if($nichtgeschaetzt <= 0)continue;
				}
	      if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
	      if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
	      if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
	      if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
	      if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
	      if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
	      if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
	      if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
	      if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
	      if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
	      if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
	      if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
	      if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
	      if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
	      if($formvars['amtsgerichtnr']){ $csv .= implode(",", array_map(function($e){return $e['schluessel'];},	$flst->Amtsgerichte)) . ';';}
	      if($formvars['amtsgerichtname']){ $csv .= implode(",", array_map(function($e){return $e['name'];},	$flst->Amtsgerichte)) . ';';}
	      if($formvars['grundbuchbezirkschl']){ $csv .= implode(",", array_map(function($e){return $e['schluessel'];},	$flst->Grundbuchbezirke)) . ';';}
	      if($formvars['grundbuchbezirkname']){ $csv .= implode(",", array_map(function($e){return $e['name'];},	$flst->Grundbuchbezirke)) . ';';}
	      if($formvars['lagebezeichnung']){
	        $anzStrassen= count_or_0($flst->Adresse);
	        for ($s=0;$s<$anzStrassen;$s++) {
	          $csv .= $flst->Adresse[$s]["gemeindename"].' ';
	          $csv .= $flst->Adresse[$s]["strassenname"].' ';
	          $csv .= $flst->Adresse[$s]["hausnr"].' ';
	        }
	        $anzLage= count_or_0($flst->Lage);
	        $Lage='';
	        for ($j=0;$j<$anzLage;$j++) {
	          $Lage.=' '.$flst->Lage[$j];
	        }
	        if ($Lage!='') {
	          $csv .= TRIM($Lage);
	        }
	        $csv .= ';';
	      }
	      if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
	      if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
	      if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
	      if($formvars['status']){ $csv .= $flst->Status.';';}
	      if($formvars['vorgaenger']){
	        for($v = 0; $v < count_or_0($flst->Vorgaenger); $v++){
	          $csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
	        }
	        $csv .= ';';
	      }
	      if($formvars['nachfolger']){
	        for($v = 0; $v < count_or_0($flst->Nachfolger); $v++){
	          $csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
	        }
	        $csv .= ';';
	      }
      
      	// Klassifizierung
				$wert=$flst->Klassifizierung[$kl]['wert'];
				if($wert != ''){
					$csv .= '"';
					$emz = round($flst->Klassifizierung[$kl]['flaeche'] * $wert / 100);

					if($flst->Klassifizierung[$kl]['objart'] == 1000){
						$emzges_a = $emzges_a + $emz;
						$flaeche_a = $flaeche_a + $flst->Klassifizierung[$kl]['flaeche'];
					}
					if($flst->Klassifizierung[$kl]['objart'] == 2000){
						$emzges_agr = $emzges_agr + $emz;
						$flaeche_agr = $flaeche_agr + $flst->Klassifizierung[$kl]['flaeche'];
					}
					if($flst->Klassifizierung[$kl]['objart'] == 3000){
						$emzges_gr = $emzges_gr + $emz;
						$flaeche_gr = $flaeche_gr + $flst->Klassifizierung[$kl]['flaeche'];
					}
					if($flst->Klassifizierung[$kl]['objart'] == 4000){
						$emzges_gra = $emzges_gra + $emz;
						$flaeche_gra = $flaeche_gra + $flst->Klassifizierung[$kl]['flaeche'];
					}
					
					$csv .= $flst->Klassifizierung[$kl]['label'];
					$csv .= '";';
					$csv .= $flst->Klassifizierung[$kl]['flaeche'].';';
					$csv .= $emz;
					$flst->emz = true;
				}
				elseif($kl != $limit-2) $csv .= ';;';
				
				if($kl == $limit-2){
					if($nichtgeschaetzt>0){
						$csv .= 'nicht geschätzt: ;'.$nichtgeschaetzt.';';
					}
					else{
						$csv .= ';;';
					}
				}
				$csv .= ';';
				if($kl == $limit-1){
					if($emzges_a > 0){
						$BWZ_a = round($emzges_a/$flaeche_a*100);
						$csv .= ' Ackerland gesamt: EMZ '.$emzges_a.' , BWZ '.$BWZ_a." ";
					}
					if($emzges_gr > 0){
						$BWZ_gr = round($emzges_gr/$flaeche_gr*100);
						$csv .= ' Grünland gesamt: EMZ '.$emzges_gr.' , BWZ '.$BWZ_gr;
					}
					if($emzges_agr > 0){
						$BWZ_agr = round($emzges_agr/$flaeche_agr*100);
						$csv .= ' Acker-Grünland gesamt: EMZ '.$emzges_agr.' , BWZ '.$BWZ_agr;
					}
					if($emzges_gra > 0){
						$BWZ_gra = round($emzges_gra/$flaeche_gra*100);
						$csv .= ' Grünland-Acker gesamt: EMZ '.$emzges_gra.' , BWZ '.$BWZ_gra;
					}					
				}
				$csv .= ';';
        	      
	      if($formvars['freitext']) {
	        for($j = 0; $j < count_or_0($flst->FreiText); $j++){
	        	if($j > 0)$csv .= ' | ';
	          $csv .= $flst->FreiText[$j]['text'];
	        }
	        $csv .= ';';
	      }
	      if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
	      if ($formvars['baulasten']){
	        for($b=0; $b < count_or_0($flst->Baulasten); $b++) {
	          $csv .= " ".$flst->Baulasten[$b]['blattnr'];
	        }
	        $csv .= ';';
	      }
	      if ($formvars['ausfstelle']){ 
	      	for($v = 0; $v < count_or_0($flst->Verfahren); $v++){
	      		if($v > 0)$csv .= ' | ';
	      		$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
	      	}
	      	$csv .= ';';
	      }
	      if ($formvars['verfahren']){
	      	for($v = 0; $v < count_or_0($flst->Verfahren); $v++){
	      		if($v > 0)$csv .= ' | ';
	      		$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
	      	}
	      	$csv .= ';';
	      }
	      if ($formvars['nutzung']){
	        $anzNutzung= count_or_0($flst->Nutzung);
	        for ($j = 0; $j < $anzNutzung; $j++){
	        	if($j > 0)$csv .= ' | ';
	          $csv .= $flst->Nutzung[$j]['flaeche'].' m2 ';
	          $csv .= $flst->Nutzung[$j]['nutzungskennz'].' ';
	          if($flst->Nutzung[$j]['abkuerzung']!='') {
	            $csv .= $flst->Nutzung[$j]['abkuerzung'].'-';
	          }
	          $csv .= $flst->Nutzung[$j]['bezeichnung'];
	        }
	        $csv .= ';';
	      }
	      
					if($formvars['blattnr']){
						for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
							if($b > 0)$csv .= ' | ';
							$csv .= intval($flst->Buchungen[$b]['blatt']).'|';
						}
		        $csv .= ';';
			    }
			    
			    if($formvars['pruefzeichen']){
						for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
							if($b > 0)$csv .= ' | ';
							$csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
						}
		        $csv .= ';';
			    }
			    
	    		if($formvars['pruefzeichen_f']){
		      	$csv .= $flst->Pruefzeichen;
		      	$csv .= ';';
		      }
			    
			    if($formvars['bvnr']){
						for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
							if($b > 0)$csv .= ' | ';
							$csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
						}
		        $csv .= ';';
			    }
			    
			    if($formvars['buchungsart']){
						for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
							if($b > 0)$csv .= ' | ';
							$csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
							$csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
						}
		        $csv .= ';';
		      }
	      
	      if($formvars['eigentuemer']){
					$csv .= '"';
	          for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
	            $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
							reset($Eigentuemerliste);
							$csv .= $flst->outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 'Namensnummer');
	          }
	        $csv .= '";';
	      	$csv .= '"';
	          for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
	            $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
							reset($Eigentuemerliste);
							$csv .= $flst->outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 'Text');
	          }
	        $csv .= '";';
	      }
				
				if($formvars['abweichenderrechtszustand']){
					$csv .= $flst->abweichenderrechtszustand;
					$csv .= ';';
				}
				
				if($formvars['baubodenrecht']){
					for($j = 0; $j < count_or_0($flst->BauBodenrecht); $j++){
						if($j > 0)$csv .= ' | ';
						$csv .= $flst->BauBodenrecht[$j]['art'].' '.$flst->BauBodenrecht[$j]['bezeichnung'];
						if($flst->BauBodenrecht[$j]['stelle'] != '')$csv .=  ' ('.$flst->BauBodenrecht[$j]['stelle'].')';
					}
					$csv .= ';';
				}
				
	      $csv .= chr(10);
	    }
	    $csv .= chr(10);
    }

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
  
  function export_eigentuemer_csv($flurstuecke, $formvars){
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ;';}
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ_kurz;';}
    if($formvars['gemkgname']){ $csv .= 'Gemkg-Name;';}
    if($formvars['gemkgschl']){ $csv .= 'Gemkg-Schl.;';}
    if($formvars['flurnr']){ $csv .= 'Flur;';}
    if($formvars['gemeindename']){ $csv .= 'Gem-Name;';}
    if($formvars['gemeinde']){ $csv .= 'Gemeinde;';}
    if($formvars['kreisname']){ $csv .= 'Kreisname;';}
    if($formvars['kreisid']){ $csv .= 'Kreisschlüssel;';}
    if($formvars['finanzamtname']){ $csv .= 'Finanzamtname;';}
    if($formvars['finanzamt']){ $csv .= 'Finanzamtschlüssel;';}
    if($formvars['forstname']){ $csv .= 'Forstamtname;';}
    if($formvars['forstschluessel']){ $csv .= 'Forstamtschlüssel;';}
    if($formvars['flaeche']){ $csv .= 'amtliche Fläche;';}
    if($formvars['amtsgerichtnr']){ $csv .= 'Amtsgericht;';}
    if($formvars['amtsgerichtname']){ $csv .= 'Amtsgerichtname;';}
    if($formvars['grundbuchbezirkschl']){ $csv .= 'GBBschlüssel;';}
    if($formvars['grundbuchbezirkname']){ $csv .= 'GBBname;';}
    if($formvars['lagebezeichnung']){ $csv .= 'Lage;';}
    if($formvars['entsteh']){ $csv .= 'Entstehung;';}
    if($formvars['letzff']){ $csv .= 'Fortführung;';}
    if($formvars['karte']){ $csv .= 'Flurkarte;';}
    if($formvars['status']){ $csv .= 'Status;';}
    if($formvars['vorgaenger']){ $csv .= 'Vorgaenger;';}
    if($formvars['nachfolger']){ $csv .= 'Nachfolger;';}
  	if($formvars['klassifizierung']){ $csv .= 'Klassifizierung;';}
    if($formvars['freitext']){ $csv .= 'Freitext;';}
    if($formvars['hinweis']){ $csv .= 'Hinweis;';}
    if($formvars['baulasten']){ $csv .= 'Baulasten;';}
    if($formvars['ausfstelle']){ $csv .= 'ausführende Stelle;';}
    if($formvars['verfahren']){ $csv .= 'Verfahren;';}
    if($formvars['nutzung']){ $csv .= 'Nutzung;';}
    if($formvars['blattnr']){ $csv .= 'Blattnummer;';}
    if($formvars['pruefzeichen']){ $csv .= 'P Buchung;';}
  	if($formvars['pruefzeichen_f']){ $csv .= 'P Flurstück;';}
    if($formvars['bvnr']){ $csv .= 'BVNR;';}
    if($formvars['buchungsart']){ $csv .= 'Buchungsart;';}
		if($formvars['abweichenderrechtszustand']){ $csv .= 'abweichender Rechtszustand;';}
		if($formvars['baubodenrecht']){ $csv .= 'Bauraum/Bodenordnungsrecht;';}
    $csv .= 'Namensnummer;'; 
    $csv .= 'Vorname;Name;Zusatz;Adresse;Ort;';
    
    $csv .= chr(10);
    for($i = 0; $i < count_or_0($flurstuecke); $i++) {
      $flurstkennz = $flurstuecke[$i];
      $flst = new flurstueck($flurstkennz,$this->database);
      $flst->readALB_Data($flurstkennz, true, 'ogc_fid');
      $flst->Grundbuecher=$flst->getGrundbuecher();
			$flst->Buchungen=$flst->getBuchungen(NULL,NULL,0);
				for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
					$Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
					reset($Eigentuemerliste);
					$flst->orderEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 0);
					usort($Eigentuemerliste, 'compare_orders');
					foreach($Eigentuemerliste as $eigentuemer){
						if($eigentuemer->zusatz_eigentuemer != '' or $eigentuemer->Nr != ''){
							if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
							if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
							if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
							if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
							if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
							if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
							if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
							if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
							if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
							if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
							if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
							if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
							if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
							if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
							if($formvars['amtsgerichtnr']){ $csv .= implode(",", array_map(function($e){return $e['schluessel'];},	$flst->Amtsgerichte)) . ';';}
							if($formvars['amtsgerichtname']){ $csv .= implode(",", array_map(function($e){return $e['name'];},	$flst->Amtsgerichte)) . ';';}
							if($formvars['grundbuchbezirkschl']){ $csv .= implode(",", array_map(function($e){return $e['schluessel'];},	$flst->Grundbuchbezirke)) . ';';}
							if($formvars['grundbuchbezirkname']){ $csv .= implode(",", array_map(function($e){return $e['name'];},	$flst->Grundbuchbezirke)) . ';';}
							if($formvars['lagebezeichnung']){
								$anzStrassen= count_or_0($flst->Adresse);
								for ($s=0;$s<$anzStrassen;$s++) {
									$csv .= $flst->Adresse[$s]["gemeindename"].' ';
									$csv .= $flst->Adresse[$s]["strassenname"].' ';
									$csv .= $flst->Adresse[$s]["hausnr"].' ';
								}
								$anzLage= count_or_0($flst->Lage);
								$Lage='';
								for ($j=0;$j<$anzLage;$j++) {
									$Lage.=' '.$flst->Lage[$j];
								}
								if ($Lage!='') {
									$csv .= TRIM($Lage);
								}
								$csv .= ';';
							}
							if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
							if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
							if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
							if($formvars['status']){ $csv .= $flst->Status.';';}
							if($formvars['vorgaenger']){
								for($v = 0; $v < count_or_0($flst->Vorgaenger); $v++){
									$csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
								}
								$csv .= ';';
							}
							if($formvars['nachfolger']){
								for($v = 0; $v < count_or_0($flst->Nachfolger); $v++){
									$csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
								}
								$csv .= ';';
							}
						if($formvars['klassifizierung']){
							$csv .= '"';
							$emzges_a = $flaeche_a = $emzges_agr = $flaeche_agr = $emzges_gr = $flaeche_gr = $emzges_gra = $flaeche_gra = 0;			
							for($j = 0; $j < count_or_0($flst->Klassifizierung); $j++){
								if($flst->Klassifizierung[$j]['flaeche'] != ''){
									$wert=$flst->Klassifizierung[$j]['wert'];
									$emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
									
									if($flst->Klassifizierung[$j]['objart'] == 1000){
										$emzges_a = $emzges_a + $emz;
										$flaeche_a = $flaeche_a + $flst->Klassifizierung[$j]['flaeche'];
									}
									if($flst->Klassifizierung[$j]['objart'] == 2000){
										$emzges_agr = $emzges_agr + $emz;
										$flaeche_agr = $flaeche_agr + $flst->Klassifizierung[$j]['flaeche'];
									}
									if($flst->Klassifizierung[$j]['objart'] == 3000){
										$emzges_gr = $emzges_gr + $emz;
										$flaeche_gr = $flaeche_gr + $flst->Klassifizierung[$j]['flaeche'];
									}
									if($flst->Klassifizierung[$j]['objart'] == 4000){
										$emzges_gra = $emzges_gra + $emz;
										$flaeche_gra = $flaeche_gra + $flst->Klassifizierung[$j]['flaeche'];
									}
									
									$csv .= $flst->Klassifizierung[$j]['flaeche'].' m² ';
									$csv .= $flst->Klassifizierung[$j]['label'];
									$csv .= ' EMZ: '.$emz." \n";
								}
							}
							$nichtgeschaetzt=$flst->Klassifizierung['nicht_geschaetzt'];
							if($nichtgeschaetzt > 0){
								$csv .= 'nicht geschätzt: '.$nichtgeschaetzt." m² \n";
							}
							if($emzges_a > 0){
								$BWZ_a = round($emzges_a/$flaeche_a*100);
								$csv .= ' Ackerland gesamt: EMZ '.$emzges_a.' , BWZ '.$BWZ_a." \n";
							}
							if($emzges_gr > 0){
								$BWZ_gr = round($emzges_gr/$flaeche_gr*100);
								$csv .= ' Grünland gesamt: EMZ '.$emzges_gr.' , BWZ '.$BWZ_gr." \n";
							}
							if($emzges_agr > 0){
								$BWZ_agr = round($emzges_agr/$flaeche_agr*100);
								$csv .= ' Acker-Grünland gesamt: EMZ '.$emzges_agr.' , BWZ '.$BWZ_agr." \n";
							}
							if($emzges_gra > 0){
								$BWZ_gra = round($emzges_gra/$flaeche_gra*100);
								$csv .= ' Grünland-Acker gesamt: EMZ '.$emzges_gra.' , BWZ '.$BWZ_gra." \n";
							}	
							$csv .= '";';
						}      
							if($formvars['freitext']) {
								for($j = 0; $j < count_or_0($flst->FreiText); $j++){
									if($j > 0)$csv .= ' | ';
									$csv .= $flst->FreiText[$j]['text'];
								}
								$csv .= ';';
							}
							if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
							if ($formvars['baulasten']){
								for($bl=0; $bl < count_or_0($flst->Baulasten); $bl++) {
									$csv .= " ".$flst->Baulasten[$bl]['blattnr'];
								}
								$csv .= ';';
							}
							if ($formvars['ausfstelle']){ 
								for($v = 0; $v < count_or_0($flst->Verfahren); $v++){
									if($v > 0)$csv .= ' | ';
									$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
								}
								$csv .= ';';
							}
							if ($formvars['verfahren']){
								for($v = 0; $v < count_or_0($flst->Verfahren); $v++){
									if($v > 0)$csv .= ' | ';
									$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
								}
								$csv .= ';';
							}
							if ($formvars['nutzung']){
								$anzNutzung= count_or_0($flst->Nutzung);
								for ($j = 0; $j < $anzNutzung; $j++){
									if($j > 0)$csv .= ' | ';
									$csv .= $flst->Nutzung[$j]['flaeche'].'m2 ';
									$csv .= $flst->Nutzung[$j]['nutzungskennz'].' ';
									if($flst->Nutzung[$j]['abkuerzung']!='') {
										$csv .= $flst->Nutzung[$j]['abkuerzung'].'-';
									}
									$csv .= $flst->Nutzung[$j]['bezeichnung'];
								}
								$csv .= ';';
							}
							
				 if($formvars['blattnr']){
					$csv .= intval($flst->Buchungen[$b]['blatt']);
					$csv .= ';';
				}
				
				if($formvars['pruefzeichen']){
					$csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
					$csv .= ';';
				}
				
				if($formvars['pruefzeichen_f']){
					$csv .= $flst->Pruefzeichen;
					$csv .= ';';
				}
				
				if($formvars['bvnr']){
					$csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
					$csv .= ';';
				}
				
				if($formvars['buchungsart']){
					$csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
					$csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
					$csv .= ';';
				}
				
				if($formvars['abweichenderrechtszustand']){
					$csv .= $flst->abweichenderrechtszustand;
					$csv .= ';';
				}
				
				if($formvars['baubodenrecht']){
					for($j = 0; $j < count_or_0($flst->BauBodenrecht); $j++){
						if($j > 0)$csv .= ' | ';
						$csv .= $flst->BauBodenrecht[$j]['art'].' '.$flst->BauBodenrecht[$j]['bezeichnung'];
						if($flst->BauBodenrecht[$j]['stelle'] != '')$csv .=  ' ('.$flst->BauBodenrecht[$j]['stelle'].')';
					}
					$csv .= ';';
				}
							
					if($eigentuemer->Nr != '')$csv .= '\''.$eigentuemer->Nr.'\'';
					$csv .= $eigentuemer->zusatz_eigentuemer;
					if($eigentuemer->Anteil !=''){$csv .= '  zu '.$eigentuemer->Anteil;}
					$csv .= ';';
					$csv .= $eigentuemer->vorname.';';
					$csv .= $eigentuemer->nachnameoderfirma;
					if($Eigentuemer->namensbestandteil != '')$csv .= ', '.$Eigentuemer->namensbestandteil;
					if($Eigentuemer->akademischergrad != '')$csv .= ', '.$Eigentuemer->akademischergrad;
					$csv .= ';';
					if($eigentuemer->geburtsname != '')$csv .= 'geb. '.$eigentuemer->geburtsname.' ';
					$csv .= $eigentuemer->geburtsdatum;
					$csv .= ';';
					$csv .= $eigentuemer->anschriften[0]['strasse'].' '.$eigentuemer->anschriften[0]['hausnummer'].';';
					$csv .= $eigentuemer->anschriften[0]['postleitzahlpostzustellung'].' '.$eigentuemer->anschriften[0]['ort_post'].' '.$eigentuemer->anschriften[0]['ortsteil'].';';
					$csv .= chr(10);
				}
			}
		 }
     $csv .= chr(10);
    }

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
  
  function export_nutzungsarten_csv($flurstuecke, $formvars){
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ;';}
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ_kurz;';}
    if($formvars['gemkgname']){ $csv .= 'Gemkg-Name;';}
    if($formvars['gemkgschl']){ $csv .= 'Gemkg-Schl.;';}
    if($formvars['flurnr']){ $csv .= 'Flur;';}
    if($formvars['gemeindename']){ $csv .= 'Gem-Name;';}
    if($formvars['gemeinde']){ $csv .= 'Gemeinde;';}
    if($formvars['kreisname']){ $csv .= 'Kreisname;';}
    if($formvars['kreisid']){ $csv .= 'Kreisschlüssel;';}
    if($formvars['finanzamtname']){ $csv .= 'Finanzamtname;';}
    if($formvars['finanzamt']){ $csv .= 'Finanzamtschlüssel;';}
    if($formvars['forstname']){ $csv .= 'Forstamtname;';}
    if($formvars['forstschluessel']){ $csv .= 'Forstamtschlüssel;';}
    if($formvars['flaeche']){ $csv .= 'amtliche Fläche;';}
    if($formvars['amtsgerichtnr']){ $csv .= 'Amtsgericht;';}
    if($formvars['amtsgerichtname']){ $csv .= 'Amtsgerichtname;';}
    if($formvars['grundbuchbezirkschl']){ $csv .= 'GBBschlüssel;';}
    if($formvars['grundbuchbezirkname']){ $csv .= 'GBBname;';}
    if($formvars['lagebezeichnung']){ $csv .= 'Lage;';}
    if($formvars['entsteh']){ $csv .= 'Entstehung;';}
    if($formvars['letzff']){ $csv .= 'Fortführung;';}
    if($formvars['karte']){ $csv .= 'Flurkarte;';}
    if($formvars['status']){ $csv .= 'Status;';}
    if($formvars['vorgaenger']){ $csv .= 'Vorgaenger;';}
    if($formvars['nachfolger']){ $csv .= 'Nachfolger;';}
  	if($formvars['klassifizierung']){ $csv .= 'Klassifizierung;';}
    if($formvars['freitext']){ $csv .= 'Freitext;';}
    if($formvars['hinweis']){ $csv .= 'Hinweis;';}
    if($formvars['baulasten']){ $csv .= 'Baulasten;';}
    if($formvars['ausfstelle']){ $csv .= 'ausführende Stelle;';}
    if($formvars['verfahren']){ $csv .= 'Verfahren;';}
   	if($formvars['blattnr']){ $csv .= 'Blattnummer;';}
    if($formvars['pruefzeichen']){ $csv .= 'P Buchung;';}
  	if($formvars['pruefzeichen_f']){ $csv .= 'P Flurstück;';}
    if($formvars['bvnr']){ $csv .= 'BVNR;';}
    if($formvars['buchungsart']){ $csv .= 'Buchungsart;';}
		if($formvars['abweichenderrechtszustand']){ $csv .= 'abweichender Rechtszustand;';}
		if($formvars['baubodenrecht']){ $csv .= 'Bauraum/Bodenordnungsrecht;';}
    if($formvars['eigentuemer']){ $csv .= 'Namensnummer;Eigentümer;';}
    $csv .= 'Nutzung - Fläche;';
    $csv .= 'Nutzung - Kennzeichen;';
    $csv .= 'Nutzung - Bezeichnung;';
    
    $csv .= chr(10);
    for($i = 0; $i < count_or_0($flurstuecke); $i++) {
      $flurstkennz = $flurstuecke[$i];
      $flst = new flurstueck($flurstkennz,$this->database);
      $flst->readALB_Data($flurstkennz, true, 'ogc_fid');
      $flst->Grundbuecher=$flst->getGrundbuecher();
			$flst->Buchungen=$flst->getBuchungen(NULL,NULL,0);
      $anzNutzung= count_or_0($flst->Nutzung);
			for ($n = 0; $n < $anzNutzung; $n++){
	      if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
	      if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
	      if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
	      if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
	      if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
	      if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
	      if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
	      if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
	      if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
	      if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
	      if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
	      if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
	      if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
	      if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
	      if($formvars['amtsgerichtnr']){ $csv .= implode(",", array_map(function($e){return $e['schluessel'];},	$flst->Amtsgerichte)) . ';';}
	      if($formvars['amtsgerichtname']){ $csv .= implode(",", array_map(function($e){return $e['name'];},	$flst->Amtsgerichte)) . ';';}
	      if($formvars['grundbuchbezirkschl']){ $csv .= implode(",", array_map(function($e){return $e['schluessel'];},	$flst->Grundbuchbezirke)) . ';';}
	      if($formvars['grundbuchbezirkname']){ $csv .= implode(",", array_map(function($e){return $e['name'];},	$flst->Grundbuchbezirke)) . ';';}
	      if($formvars['lagebezeichnung']){
	        $anzStrassen = count_or_0($flst->Adresse);
	        for ($s=0;$s<$anzStrassen;$s++) {
	          $csv .= $flst->Adresse[$s]["gemeindename"].' ';
	          $csv .= $flst->Adresse[$s]["strassenname"].' ';
	          $csv .= $flst->Adresse[$s]["hausnr"].' ';
	        }
	        $anzLage = count_or_0($flst->Lage);
	        $Lage='';
	        for ($j=0;$j<$anzLage;$j++) {
	          $Lage.=' '.$flst->Lage[$j];
	        }
	        if ($Lage!='') {
	          $csv .= TRIM($Lage);
	        }
	        $csv .= ';';
	      }
	      if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
	      if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
	      if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
	      if($formvars['status']){ $csv .= $flst->Status.';';}
	      if($formvars['vorgaenger']){
	        for($v = 0; $v < count_or_0($flst->Vorgaenger); $v++){
	          $csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
	        }
	        $csv .= ';';
	      }
	      if($formvars['nachfolger']){
	        for($v = 0; $v < count_or_0($flst->Nachfolger); $v++){
	          $csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
	        }
	        $csv .= ';';
	      }
			if($formvars['klassifizierung']){
				$csv .= '"';
				$emzges_a = $flaeche_a = $emzges_agr = $flaeche_agr = $emzges_gr = $flaeche_gr = $emzges_gra = $flaeche_gra = 0;
				for($j = 0; $j < count_or_0($flst->Klassifizierung); $j++){
					if($flst->Klassifizierung[$j]['flaeche'] != ''){
						$wert=$flst->Klassifizierung[$j]['wert'];
						$emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
						
						if($flst->Klassifizierung[$j]['objart'] == 1000){
							$emzges_a = $emzges_a + $emz;
							$flaeche_a = $flaeche_a + $flst->Klassifizierung[$j]['flaeche'];
						}
						if($flst->Klassifizierung[$j]['objart'] == 2000){
							$emzges_agr = $emzges_agr + $emz;
							$flaeche_agr = $flaeche_agr + $flst->Klassifizierung[$j]['flaeche'];
						}
						if($flst->Klassifizierung[$j]['objart'] == 3000){
							$emzges_gr = $emzges_gr + $emz;
							$flaeche_gr = $flaeche_gr + $flst->Klassifizierung[$j]['flaeche'];
						}
						if($flst->Klassifizierung[$j]['objart'] == 4000){
							$emzges_gra = $emzges_gra + $emz;
							$flaeche_gra = $flaeche_gra + $flst->Klassifizierung[$j]['flaeche'];
						}						
						
						$csv .= $flst->Klassifizierung[$j]['flaeche'].' m² ';
						$csv .= $flst->Klassifizierung[$j]['label'];
						$csv .= ' EMZ: '.$emz." \n";
					}
				}
				$nichtgeschaetzt=$flst->Klassifizierung['nicht_geschaetzt'];
				if($nichtgeschaetzt > 0){
					$csv .= 'nicht geschätzt: '.$nichtgeschaetzt." m² \n";
				}
				if($emzges_a > 0){
					$BWZ_a = round($emzges_a/$flaeche_a*100);
					$csv .= ' Ackerland gesamt: EMZ '.$emzges_a.' , BWZ '.$BWZ_a." \n";
				}
				if($emzges_gr > 0){
					$BWZ_gr = round($emzges_gr/$flaeche_gr*100);
					$csv .= ' Grünland gesamt: EMZ '.$emzges_gr.' , BWZ '.$BWZ_gr." \n";
				}
				if($emzges_agr > 0){
					$BWZ_agr = round($emzges_agr/$flaeche_agr*100);
					$csv .= ' Acker-Grünland gesamt: EMZ '.$emzges_agr.' , BWZ '.$BWZ_agr." \n";
				}
				if($emzges_gra > 0){
					$BWZ_gra = round($emzges_gra/$flaeche_gra*100);
					$csv .= ' Grünland-Acker gesamt: EMZ '.$emzges_gra.' , BWZ '.$BWZ_gra." \n";
				}
        $csv .= '";';
      }      
	      if($formvars['freitext']) {
	        for($j = 0; $j < count_or_0($flst->FreiText); $j++){
	        	if($j > 0)$csv .= ' | ';
	          $csv .= $flst->FreiText[$j]['text'];
	        }
	        $csv .= ';';
	      }
	      if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
	      if ($formvars['baulasten']){
	        for($b=0; $b < count_or_0($flst->Baulasten); $b++) {
	          $csv .= " ".$flst->Baulasten[$b]['blattnr'];
	        }
	        $csv .= ';';
	      }
	      if ($formvars['ausfstelle']){ 
	      	for($v = 0; $v < count_or_0($flst->Verfahren); $v++){
	      		if($v > 0)$csv .= ' | ';
	      		$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
	      	}
	      	$csv .= ';';
	      }
	      if ($formvars['verfahren']){
	      	for($v = 0; $v < count_or_0($flst->Verfahren); $v++){
	      		if($v > 0)$csv .= ' | ';
	      		$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
	      	}
	      	$csv .= ';';
	      }
		      
		    if($formvars['blattnr']){
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						if($b > 0)$csv .= ' | ';
						$csv .= intval($flst->Buchungen[$b]['blatt']);
					}
	        $csv .= ';';
		    }
		    
		    if($formvars['pruefzeichen']){
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						if($b > 0)$csv .= ' | ';
						$csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
					}
	        $csv .= ';';
		    }
		    
				if($formvars['pruefzeichen_f']){
	      	$csv .= $flst->Pruefzeichen;
	      	$csv .= ';';
	      }
		    
		    if($formvars['bvnr']){
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						if($b > 0)$csv .= ' | ';
						$csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
					}
	        $csv .= ';';
		    }
		    
		    if($formvars['buchungsart']){
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						if($b > 0)$csv .= ' | ';
						$csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
						$csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
					}
	        $csv .= ';';
	      }
				
			if($formvars['abweichenderrechtszustand']){
				$csv .= $flst->abweichenderrechtszustand;
				$csv .= ';';
			}
			
			if($formvars['baubodenrecht']){
				for($j = 0; $j < count_or_0($flst->BauBodenrecht); $j++){
					if($j > 0)$csv .= ' | ';
					$csv .= $flst->BauBodenrecht[$j]['art'].' '.$flst->BauBodenrecht[$j]['bezeichnung'];
					if($flst->BauBodenrecht[$j]['stelle'] != '')$csv .=  ' ('.$flst->BauBodenrecht[$j]['stelle'].')';
				}
				$csv .= ';';
			}
	      
  		if($formvars['eigentuemer']){
				$csv .= '"';
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						$Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
						reset($Eigentuemerliste);
						$csv .= $flst->outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 'Namensnummer');
					}
				$csv .= '";';
				$csv .= '"';
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						$Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
						reset($Eigentuemerliste);
						$csv .= $flst->outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 'Text');
					}
				$csv .= '";';
			}

        
        $csv .= $flst->Nutzung[$n]['flaeche'].';';
        $csv .= $flst->Nutzung[$n]['nutzungskennz'].';';
        if($flst->Nutzung[$n]['abkuerzung']!='') {
          $csv .= $flst->Nutzung[$n]['abkuerzung'].'-';
        }
        $csv .= $flst->Nutzung[$n]['bezeichnung'].';';             
       
      	$csv .= ';';


        $csv .= chr(10);
      }
      $csv .= chr(10);
    }

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }

  function export_flurst_csv($flurstuecke, $formvars){
    if($formvars['flurstkennz']){ $csv .= 'FlstKZ;';}
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ_kurz;';}
    if($formvars['gemkgname']){ $csv .= 'Gemkg-Name;';}
    if($formvars['gemkgschl']){ $csv .= 'Gemkg-Schl.;';}
    if($formvars['flurnr']){ $csv .= 'Flur;';}
    if($formvars['gemeindename']){ $csv .= 'Gem-Name;';}
    if($formvars['gemeinde']){ $csv .= 'Gemeinde;';}
    if($formvars['kreisname']){ $csv .= 'Kreisname;';}
    if($formvars['kreisid']){ $csv .= 'Kreisschlüssel;';}
    if($formvars['finanzamtname']){ $csv .= 'Finanzamtname;';}
    if($formvars['finanzamt']){ $csv .= 'Finanzamtschlüssel;';}
    if($formvars['forstname']){ $csv .= 'Forstamtname;';}
    if($formvars['forstschluessel']){ $csv .= 'Forstamtschlüssel;';}
    if($formvars['flaeche']){ $csv .= 'amtliche Fläche;';}
    if($formvars['amtsgerichtnr']){ $csv .= 'Amtsgericht;';}
    if($formvars['amtsgerichtname']){ $csv .= 'Amtsgerichtname;';}
    if($formvars['grundbuchbezirkschl']){ $csv .= 'GBBschlüssel;';}
    if($formvars['grundbuchbezirkname']){ $csv .= 'GBBname;';}
    if($formvars['lagebezeichnung']){ $csv .= 'Lage;';}
    if($formvars['entsteh']){ $csv .= 'Entstehung;';}
    if($formvars['letzff']){ $csv .= 'Fortführung;';}
    if($formvars['karte']){ $csv .= 'Flurkarte;';}
    if($formvars['status']){ $csv .= 'Status;';}
    if($formvars['vorgaenger']){ $csv .= 'Vorgaenger;';}
    if($formvars['nachfolger']){ $csv .= 'Nachfolger;';}
    if($formvars['klassifizierung']){ $csv .= 'Klassifizierung;';}
    if($formvars['freitext']){ $csv .= 'Freitext;';}
    if($formvars['hinweis']){ $csv .= 'Hinweis;';}
    if($formvars['baulasten']){ $csv .= 'Baulasten;';}
    if($formvars['ausfstelle']){ $csv .= 'ausführende Stelle;';}
    if($formvars['verfahren']){ $csv .= 'Verfahren;';}
    if($formvars['nutzung']){ $csv .= 'Nutzung;';}
    if($formvars['blattnr']){ $csv .= 'Blattnummer;';}
    if($formvars['pruefzeichen']){ $csv .= 'P Buchung;';}
  	if($formvars['pruefzeichen_f']){ $csv .= 'P Flurstück;';}
    if($formvars['bvnr']){ $csv .= 'BVNR;';}
    if($formvars['buchungsart']){ $csv .= 'Buchungsart;';}
		if($formvars['abweichenderrechtszustand']){ $csv .= 'abweichender Rechtszustand;';}
		if($formvars['baubodenrecht']){ $csv .= 'Bauraum/Bodenordnungsrecht;';}
    if($formvars['eigentuemer']){ $csv .= 'Namensnummer;Eigentümer;';}
    
    $csv .= chr(10);
    for($i = 0; $i < count_or_0($flurstuecke); $i++) {
      $flurstkennz = $flurstuecke[$i];
      $flst = new flurstueck($flurstkennz,$this->database);
      $flst->readALB_Data($flurstkennz, true, 'ogc_fid');
			$flst->Grundbuecher=$flst->getGrundbuecher();
			$flst->Buchungen=$flst->getBuchungen(NULL,NULL,0);
      if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
      if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
      if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
      if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
      if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
      if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
      if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
      if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
      if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
      if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
      if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
      if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
      if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
      if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
      if($formvars['amtsgerichtnr']){ $csv .= implode(",", array_map(function($e){return $e['schluessel'];},	$flst->Amtsgerichte)) . ';';}
			if($formvars['amtsgerichtname']){ $csv .= implode(",", array_map(function($e){return $e['name'];},	$flst->Amtsgerichte)) . ';';}
			if($formvars['grundbuchbezirkschl']){ $csv .= implode(",", array_map(function($e){return $e['schluessel'];},	$flst->Grundbuchbezirke)) . ';';}
			if($formvars['grundbuchbezirkname']){ $csv .= implode(",", array_map(function($e){return $e['name'];},	$flst->Grundbuchbezirke)) . ';';}
      if($formvars['lagebezeichnung']){
			$anzStrassen = count_or_0($flst->Adresse);
        for ($s=0;$s<$anzStrassen;$s++) {
          $csv .= $flst->Adresse[$s]["gemeindename"].' ';
          $csv .= $flst->Adresse[$s]["strassenname"].' ';
          $csv .= $flst->Adresse[$s]["hausnr"].' ';
        }
        $anzLage= count_or_0($flst->Lage);
        $Lage='';
        for ($j=0;$j<$anzLage;$j++) {
          $Lage.=' '.$flst->Lage[$j];
        }
        if ($Lage!='') {
          $csv .= TRIM($Lage);
        }
        $csv .= ';';
      }
      if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
      if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
      if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
      if($formvars['status']){ $csv .= $flst->Status.';';}
      if($formvars['vorgaenger']){
        for($v = 0; $v < count_or_0($flst->Vorgaenger); $v++){
          $csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
        }
        $csv .= ';';
      }
      if($formvars['nachfolger']){
        for($v = 0; $v < count_or_0($flst->Nachfolger); $v++){
          $csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
        }
        $csv .= ';';
      }
      if($formvars['klassifizierung']){
				$csv .= '"';
				$emzges_a = $flaeche_a = $emzges_agr = $flaeche_agr = $emzges_gr = $flaeche_gr = $emzges_gra = $flaeche_gra = 0;
				for($j = 0; $j < count_or_0($flst->Klassifizierung); $j++){
					if($flst->Klassifizierung[$j]['flaeche'] != ''){
						$wert=$flst->Klassifizierung[$j]['wert'];
						$emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
						
						if($flst->Klassifizierung[$j]['objart'] == 1000){
							$emzges_a = $emzges_a + $emz;
							$flaeche_a = $flaeche_a + $flst->Klassifizierung[$j]['flaeche'];
						}
						if($flst->Klassifizierung[$j]['objart'] == 2000){
							$emzges_agr = $emzges_agr + $emz;
							$flaeche_agr = $flaeche_agr + $flst->Klassifizierung[$j]['flaeche'];
						}
						if($flst->Klassifizierung[$j]['objart'] == 3000){
							$emzges_gr = $emzges_gr + $emz;
							$flaeche_gr = $flaeche_gr + $flst->Klassifizierung[$j]['flaeche'];
						}
						if($flst->Klassifizierung[$j]['objart'] == 4000){
							$emzges_gra = $emzges_gra + $emz;
							$flaeche_gra = $flaeche_gra + $flst->Klassifizierung[$j]['flaeche'];
						}						
						
						$csv .= $flst->Klassifizierung[$j]['flaeche'].' m² ';
						$csv .= $flst->Klassifizierung[$j]['label'];
						$csv .= ' EMZ: '.$emz." \n";
					}
				}
				$nichtgeschaetzt=$flst->Klassifizierung['nicht_geschaetzt'];
				if($nichtgeschaetzt > 0){
					$csv .= 'nicht geschätzt: '.$nichtgeschaetzt." m² \n";
				}
				if($emzges_a > 0){
					$BWZ_a = round($emzges_a/$flaeche_a*100);
					$csv .= ' Ackerland gesamt: EMZ '.$emzges_a.' , BWZ '.$BWZ_a." \n";
				}
				if($emzges_gr > 0){
					$BWZ_gr = round($emzges_gr/$flaeche_gr*100);
					$csv .= ' Grünland gesamt: EMZ '.$emzges_gr.' , BWZ '.$BWZ_gr." \n";
				}
				if($emzges_agr > 0){
					$BWZ_agr = round($emzges_agr/$flaeche_agr*100);
					$csv .= ' Acker-Grünland gesamt: EMZ '.$emzges_agr.' , BWZ '.$BWZ_agr." \n";
				}
				if($emzges_gra > 0){
					$BWZ_gra = round($emzges_gra/$flaeche_gra*100);
					$csv .= ' Grünland-Acker gesamt: EMZ '.$emzges_gra.' , BWZ '.$BWZ_gra." \n";
				}
        $csv .= '";';
      }      
      if($formvars['freitext']) {
        for($j = 0; $j < count_or_0($flst->FreiText); $j++){
        	if($j > 0)$csv .= ' | ';
          $csv .= $flst->FreiText[$j]['text'];
        }
        $csv .= ';';
      }
      if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
      if ($formvars['baulasten']){
        for($b=0; $b < count_or_0($flst->Baulasten); $b++) {
          $csv .= " ".$flst->Baulasten[$b]['blattnr'];
        }
        $csv .= ';';
      }
      if ($formvars['ausfstelle']){ 
      	for($v = 0; $v < count_or_0($flst->Verfahren); $v++){
      		if($v > 0)$csv .= ' | ';
      		$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
      	}
      	$csv .= ';';
      }
      if ($formvars['verfahren']){
      	for($v = 0; $v < count_or_0($flst->Verfahren); $v++){
      		if($v > 0)$csv .= ' | ';
      		$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
      	}
      	$csv .= ';';
      }
      if ($formvars['nutzung']){
        $anzNutzung= count_or_0($flst->Nutzung);
        for ($j = 0; $j < $anzNutzung; $j++){
        	if($j > 0)$csv .= ' | ';
          $csv .= $flst->Nutzung[$j]['flaeche'].' m2 ';
          $csv .= $flst->Nutzung[$j]['nutzungskennz'].' ';
          if($flst->Nutzung[$j]['abkuerzung']!='') {
            $csv .= $flst->Nutzung[$j]['abkuerzung'].'-';
          }
          $csv .= $flst->Nutzung[$j]['bezeichnung'];
        }
        $csv .= ';';
      }
      
      if($formvars['blattnr']){
				for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
					if($b > 0)$csv .= ' | ';
					$csv .= intval($flst->Buchungen[$b]['blatt']);
				}
				$csv .= ';';
			}
		    
			if($formvars['pruefzeichen']){
				for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
					if($b > 0)$csv .= ' | ';
					$csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
				}
				$csv .= ';';
			}
		    
    		if($formvars['pruefzeichen_f']){
	      	$csv .= $flst->Pruefzeichen;
	      	$csv .= ';';
	      }
		    
		    if($formvars['bvnr']){
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						if($b > 0)$csv .= ' | ';
						$csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
					}
	        $csv .= ';';
		    }
		    
		    if($formvars['buchungsart']){
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						if($b > 0)$csv .= ' | ';
						$csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
						$csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
					}
	        $csv .= ';';
	      }
				
			if($formvars['abweichenderrechtszustand']){
				$csv .= $flst->abweichenderrechtszustand;
				$csv .= ';';
			}
			
			if($formvars['baubodenrecht']){
				for($j = 0; $j < count_or_0($flst->BauBodenrecht); $j++){
					if($j > 0)$csv .= ' | ';
					$csv .= $flst->BauBodenrecht[$j]['art'].' '.$flst->BauBodenrecht[$j]['bezeichnung'];
					if($flst->BauBodenrecht[$j]['stelle'] != '')$csv .=  ' ('.$flst->BauBodenrecht[$j]['stelle'].')';
				}
				$csv .= ';';
			}
      
      if($formvars['eigentuemer']){
				$csv .= '"';
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						$Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
						reset($Eigentuemerliste);
						$csv .= $flst->outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 'Namensnummer');
					}
				$csv .= '";';
				$csv .= '"';
					for($b = 0; $b < count_or_0($flst->Buchungen); $b++){
						$Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
						reset($Eigentuemerliste);
						$csv .= $flst->outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, 'Text');
					}
				$csv .= '";';
			}
      $csv .= chr(10);
    }

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
  

  function getFlurstKennzByRaumbezug($FlurstKennz,$Raumbezug,$Wert) {
    $ret = $this->database->getFlurstuecksKennzByRaumbezug($FlurstKennz,$Raumbezug,$Wert);
    return $ret;
  }

  function getFlurstKennzByGemeindeIDs($GemeindenStelle, $FlurstKennz) {
    $ret = $this->database->getFlurstuecksKennzByGemeindeIDs($GemeindenStelle, $FlurstKennz);
    return $ret;
  }
	
	function ALBAuszug_outputEigentuemer($gml_id, $Eigentuemerliste, $indent = NULL, &$pdf,$Ueberschrift,$art,&$seite,&$row,$fontSize,&$pagecount,$f, $col0, $col1, $col9_1){
		$eigentuemer = $Eigentuemerliste[$gml_id];
		if($row<120){
			# Seitenumbruch
			$seite++;
			# aktuelle Seite abschließen
			$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
			# neue Seite beginnen
			$pageid=$pdf->newPage();
			$pagecount[$f] = $pagecount[$f] + 1;
			$row=825; # 812 -> 825 2007-04-02 Schmidt;
			$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
		}
		else {
			#$row-=12;
		}
		$_col0 = $col0 + $indent;
		$_col1 = $col1 + $indent;
		if($eigentuemer->Nr != '')$pdf->addText($_col0,$row,$fontSize,$eigentuemer->Nr);
		if($eigentuemer->zusatz_eigentuemer != ''){
			$zusatz = $eigentuemer->zusatz_eigentuemer; if($eigentuemer->Anteil != '')$zusatz .= ' zu '.$eigentuemer->Anteil;
			$pdf->addText($_col1,$row-=12,$fontSize,utf8_decode($zusatz));
		}
		elseif($eigentuemer->Anteil != ''){
			$pdf->addText($_col1,$row-=12,$fontSize,utf8_decode($eigentuemer->Anteil));
		}
		unset($namenszeilen);
		if($eigentuemer->vorname != '')$namenszeilen[0] = $eigentuemer->vorname.' ';
		$namenszeilen[0] .= $eigentuemer->nachnameoderfirma;
		if($eigentuemer->namensbestandteil != '')$namenszeilen[0] .= ', '.$eigentuemer->namensbestandteil;
		if($eigentuemer->akademischergrad != '')$namenszeilen[0] .= ', '.$eigentuemer->akademischergrad;
		if($eigentuemer->geburtsname != '')$namenszeilen[1] .= 'geb. '.$eigentuemer->geburtsname.' ';
		$namenszeilen[1] .= $eigentuemer->geburtsdatum;
		if ($eigentuemer->sterbedatum != '') {
			$namenszeilen[1] .=  ' +' . $eigentuemer->sterbedatum;
		}
		$namenszeilen[2] .= $eigentuemer->anschriften[0]['strasse'].' '.$eigentuemer->anschriften[0]['hausnummer'];
		$namenszeilen[3] .= $eigentuemer->anschriften[0]['postleitzahlpostzustellung'].' '.$eigentuemer->anschriften[0]['ort_post'].' '.$eigentuemer->anschriften[0]['ortsteil'];		
		$anzNamenszeilen= count_or_0($namenszeilen);
		for ($k=0;$k<$anzNamenszeilen;$k++) {
			if($namenszeilen[$k] != '')$pdf->addText($_col1,$row-=12,$fontSize,utf8_decode($namenszeilen[$k]));
		}
		
		if($eigentuemer->children != ''){
			$indent = $indent + 20;
			foreach($eigentuemer->children as $child){
				$this->ALBAuszug_outputEigentuemer($child, $Eigentuemerliste, $indent, $pdf,$Ueberschrift,$art,$seite,$row,$fontSize,$pagecount,$f, $col0, $col1, $col9_1);
				$row = $row - 24;
			}
		}
	}
	
	function ALBAuszug_SeitenKopf(&$pdf,$flst,$Ueberschrift,$art,$seite,&$row,$fontSize,$BestandStr,$AktualitaetsNr) {
    # 2006-11-23 Holger Riedel Formatierungsänderung
    $col0=50; # 28 -> 50 2007-04-02 Schmidt
    $col1=$col0+7.23;
    $col27=$col0+195.17;
    $col37=$col0+267.45;
    $col42=$col0+303.59;
    $col48=$col0+346.96;
    $col58=$col0+419.24;
    $col59=$col0+426.47;
    $col64=$col0+462.61;
    $col70=$col0+505.99;

    $pdf->addText($col0,$row-=24,$fontSize*1.5, utf8_decode($Ueberschrift));

    if($art != 'Bestand'){
      $pdf->addText($col0,$row-=24,$fontSize, utf8_decode($art.' '.mb_substr($flst->FlurstKennz,0,20,'utf8')));
    }
    else{
      $pdf->addText($col0,$row-=24,$fontSize, utf8_decode($art.' '.$BestandStr));
    }
    $pdf->addText($col0,$row-=24,$fontSize,'Datum: '.date('d.m.Y'));
    $pdf->addText(527,$row,$fontSize,'Seite '.$seite);

    $row-=24;
    $pdf->setLineStyle(1);
    $pdf->line(50,745,565,745);
  }

  function ALBAuszug_Flurstueck($FlurstKennz,$formnummer) {
  	global $katasterfuehrendestelle;
    $pdf=new Cezpdf();
    $pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/Helvetica.afm');
    # Hilfsobjekte erzeugen
    $fontSize=12;
    $col00=28;
    $col0=50; # 35 -> 50 2007-04-02 Schmidt
    $col1=$col0+20;
    $col1a=$col1+16;
    $col1b=$col1a+30;
    $col1_1=115;
    $col2=$col0+100;
    $col2_1=$col2+50;
    $col2_2=$col2_1+20;
    $col2_3=$col2+25;
    $col3=$col0+185;
    $col4=$col0+200;
    $col4a=$col0+228;
    $col5=$col0+248;
    $col6=342;
    $col7=363;
    $col8=$col6+70;
		$col8a=490;
    $col9=527;
    $col9_1=$col9-50;

    for($f = 0; $f < count_or_0($FlurstKennz); $f++){
      $pagecount[$f] = $pagecount[$f] + 1;
      $seite=1;
      $row=825; # 812 -> 825  2007-04-02 Schmidt
      $nennerausgabe= '';
      $flst=new flurstueck($FlurstKennz[$f],$this->database);
      $flst->database=$this->database;
      $ret=$flst->readALB_Data($FlurstKennz[$f], true, 'ogc_fid');
			$flst->Grundbuecher=$flst->getGrundbuecher();
			$flst->Buchungen=$flst->getBuchungen(NULL,NULL,0);
      if ($ret!='') {
        return $ret;
      }

      if ($flst->Status != 'H' OR $formnummer = '30') {
        switch ($formnummer) {
          case '30' : {
            $Ueberschrift='Flurstücksdaten - interne Verwendung';
            $art = 'Flurstück';
          } break;
          case '35' : {
            $Ueberschrift='Flurstücks- und Eigentümerdaten - interne Verwendung';
            $art = 'Flurstück';
          } break;
          case '40' : {
            $Ueberschrift='Eigentümerdaten zum Flurstück - interne Verwendung';
            $art = 'Flurstück';
          } break;
        }

        $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,$art,$seite,$row,$fontSize,NULL,$AktualitaetsNr);

        if(LANDKREIS != '')$pdf->addText($col0,$row-=12,$fontSize,utf8_decode(LANDKREIS));
        if(AMT != ''){
        	$amt = AMT;
        	if($katasterfuehrendestelle){
	        	foreach ($katasterfuehrendestelle as $key => $value) {
					    if($flst->Grundbuecher[0]['bezirk'] <= $key) {
					      $amt .= $value;
					      break;
					    }
	        	}
        	}
        	$pdf->addText($col0,$row-=12,$fontSize,utf8_decode($amt));
        }
        if(($formnummer == '30' OR $formnummer == '35') AND BEARBEITER == 'true')$pdf->addText($col7,$row,$fontSize,utf8_decode(BEARBEITER_NAME));
        if(STRASSE != '')$pdf->addText($col0,$row-=12,$fontSize,utf8_decode(STRASSE));
        if(STRASSE2 != '')$pdf->addText($col0,$row-=12,$fontSize,utf8_decode(STRASSE2));
        if(PLZ != '')$pdf->addText($col0,$row-=12,$fontSize,utf8_decode(PLZ.' '.ORT));
      	if(POSTANSCHRIFT != '')$pdf->addText($col0,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT));
        if(POSTANSCHRIFT_STRASSE != '')$pdf->addText($col0,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT_STRASSE));
        if(POSTANSCHRIFT_PLZ != '')$pdf->addText($col0,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT_PLZ.' '.POSTANSCHRIFT_ORT));
        $row-=24;

        $pdf->addText($col0,$row-=12,$fontSize,'Gemarkung');
        $pdf->addText($col2,$row,$fontSize,utf8_decode($flst->GemkgName).' ('.$flst->GemkgSchl.')');
        $pdf->addText($col0,$row-=12,$fontSize,'Flur');
        $pdf->addText($col2,$row,$fontSize,ltrim(substr($flst->FlurstKennz,6,3),'0'));
        if ($flst->Nenner!=0) { $nennerausgabe="/".$flst->Nenner; }
        $pdf->addText($col0,$row-=12,$fontSize, utf8_decode('Flurstück'));
        $pdf->addText($col2,$row,$fontSize,$flst->Zaehler.$nennerausgabe);


        if($formnummer == '30' || $formnummer == '35'){
          $pdf->addText($col0,$row-=12,$fontSize,'Gemeinde');
          $pdf->addText($col2,$row,$fontSize,utf8_decode($flst->GemeindeName).' ('.$flst->GemeindeID.')');

          if ($flst->KreisName != '') {
            $pdf->addText($col0,$row-=12,$fontSize,'Kreis/Stadt');
            $pdf->addText($col2,$row,$fontSize,utf8_decode($flst->KreisName).' ('.$flst->KreisID.')');
          }

          if ($flst->FinanzamtName != '') {
            $pdf->addText($col0,$row-=12,$fontSize,'Finanzamt');
            $pdf->addText($col2,$row,$fontSize,utf8_decode($flst->FinanzamtName).' ('.$flst->FinanzamtSchl.')');
          }

          if ($flst->Forstamt['name'] != 'ungebucht') {
            $pdf->addText($col0,$row-=12,$fontSize,'Forstamt');
            $pdf->addText($col2,$row,$fontSize,utf8_decode($flst->Forstamt['name']).' ('.$flst->Forstamt['schluessel'].')');
          }
        }

        if($formnummer == '30' || $formnummer == '35'){
          if($flst->Status == 'H'){ ### Status gibt es in ALKIS nicht mehr, der Hinweis kommt nicht!
          	$pdf->addText($col0,$row-=24,$fontSize, utf8_decode('Status: Historisches Flurstück '.$flst->Status));
          }

          $pdf->addText($col0,$row-=24,$fontSize,'Entstehung');
          if($flst->Entstehung == '/     -'){
            $flst->Entstehung = 2;
          }
          $pdf->addText($col2,$row,$fontSize,$flst->Entstehung);
          $pdf->addText($col0,$row-=12,$fontSize, utf8_decode('Fortführung'));
          $pdf->addText($col2,$row,$fontSize,$flst->LetzteFF);
          $pdf->addText($col0,$row-=12,$fontSize, utf8_decode('Flurkarte Riß'));

          $pdf->addText($col0,$row-=24,$fontSize,'Lage');
          # Ausgabe der Adressangabe zur Lage
          $anzStrassen = count_or_0($flst->Adresse);
          for ($s=0;$s<$anzStrassen;$s++) {
            $Adressbezeichnung =$flst->Adresse[$s]["strasse"];
            $Adressbezeichnung.=' '.$flst->Adresse[$s]["strassenname"];
            $Adressbezeichnung.=' '.$flst->Adresse[$s]["hausnr"];
            $ausgabetext=zeilenumbruch($Adressbezeichnung,60);
            for ($j=0;$j<count($ausgabetext);$j++) {
              $pdf->addText($col2,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
          }
          # Ausgabe Lagebezeichnung falls vorhanden
          $Lagebezeichnung=$flst->Lage;
          for ($i=0; $i < count_or_0($Lagebezeichnung); $i++) {
            $pdf->addText($col2,$row-=12,$fontSize,utf8_decode($Lagebezeichnung[$i]));
          }

          $pdf->addText($col0,$row-=24,$fontSize, utf8_decode('Amtliche Fläche'));
          $pdf->addText($col2,$row,$fontSize, utf8_decode($flst->ALB_Flaeche.' m²'));

          # Hinweise zum Flurstücke
          if($flst->abweichenderrechtszustand == 'ja'){
						$pdf->addText($col0,$row-=24,$fontSize, utf8_decode('In einem durch Gesetz geregelten Verfahren der Bodenordnung ist für das Flurstück ein neuer'));
						$pdf->addText($col0,$row-=12,$fontSize, utf8_decode('Rechtszustand eingetreten. Die Festlegungen des Verfahrens sind noch nicht in das Liegen-'));
						$pdf->addText($col0,$row-=12,$fontSize, utf8_decode('schaftskataster übernommen. Dieser Nachweis entspricht deshalb nicht dem aktuellen Stand.'));
					}
					if($flst->zweifelhafterflurstuecksnachweis == 'ja')$pdf->addText($col0,$row-=24,$fontSize, utf8_decode('Zweifelhafter Flurstücksnachweis'));

          # Baulastenblattnummer
          if (count_or_0($flst->Baulasten)>0) {
            $pdf->addText($col0,$row-=24,$fontSize,'Baulastenblatt-Nummer');
            $row-=6;
            $BaulastenStr=$flst->Baulasten[0]['blattnr'];
            for ($k=1;$k<count($flst->Baulasten);$k++) {
              $BaulastenStr.=', '.$flst->Baulasten[$k]['blattnr'];
            }
            $ausgabetext=zeilenumbruch($BaulastenStr,60);
            $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($ausgabetext[0]));
            for ($j=1;$j<count($ausgabetext);$j++) {
              $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
          }

          # BauBodenrecht
          if (count_or_0($flst->BauBodenrecht)>0) {
            $pdf->addText($col0,$row-=24,$fontSize,'Bau- und Bodenordnungsrecht');
            $row-=6;
            for ($i=0; $i < count_or_0($flst->BauBodenrecht); $i++){
  	          if($row<120) {
  	            # Seitenumbruch
  	            $seite++;
  	            # aktuelle Seite abschließen
  	            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
  	            # neue Seite beginnen
  	            $pageid=$pdf->newPage();
  	            $pagecount[$f] = $pagecount[$f] + 1;
  	            $row=825; # 812 -> 825 2007-04-02 Schmidt
  	          	$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
  	          }
  	          $pdf->addText($col1,$row-=12,$fontSize, utf8_decode($flst->BauBodenrecht[$i]['flaeche'].'m²'));
  	          $art=zeilenumbruch($flst->BauBodenrecht[$i]['art'],60);
  	          $pdf->addText($col2,$row,$fontSize,utf8_decode($art[0]).' ('.$flst->BauBodenrecht[$i]['bezeichnung'].')');
  	          for ($j=1;$j<count($art);$j++) {
  	          	$pdf->addText($col2,$row-=12,$fontSize,utf8_decode($art[$j]));
  	          }
  	          if($flst->BauBodenrecht[$i]['stelle'] != ''){
  	          	$AusfStelleName=zeilenumbruch('Ausführende Stelle: ' . $flst->BauBodenrecht[$i]['stelle'],60);
  	          	$pdf->addText($col2,$row-=12,$fontSize,utf8_decode($AusfStelleName[0]));
  	          	for ($j=1;$j<count($AusfStelleName);$j++) {
  	          		$pdf->addText($col2,$row-=12,$fontSize,utf8_decode($AusfStelleName[$j]));
  	          	}
  	          }
            }
          }

          # Freier Text zum Flurstück
          if (count_or_0($flst->FreiText)>0) {
            $pdf->addText($col0,$row-=24,$fontSize, utf8_decode('Zusätzliche Angaben'));
            $row-=6;
            for ($z=0;$z<count($flst->FreiText);$z++) {
              if ($z==0) { $row+=12; }
              $ausgabetext=zeilenumbruch($flst->FreiText[$z]['text'],40);
              $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($ausgabetext[0]));
              for ($j=1;$j<count($ausgabetext);$j++) {
                $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
              }
            }
          }

          # Vorgängerflurstücke
          if (count_or_0($flst->Vorgaenger)>0) {
            $pdf->addText($col0,$row-=24,$fontSize, utf8_decode('Vorgängerflurstück'));
            $pdf->addText($col2_1,$row,$fontSize,mb_substr($flst->Vorgaenger[0]['vorgaenger'],0,20,'utf8'));
            for ($v=1;$v<count($flst->Vorgaenger);$v++) {
              $pdf->addText($col2_1,$row-=12,$fontSize,mb_substr($flst->Vorgaenger[$v]['vorgaenger'],0,20,'utf8'));
            }
          }
          # Nachfolgerflurstücke
          if (count_or_0($flst->Nachfolger)>0) {
            $pdf->addText($col0,$row-=24,$fontSize, utf8_decode('Nachfolgerflurstück'));
            $pdf->addText($col2_1,$row,$fontSize,mb_substr($flst->Nachfolger[0]['nachfolger'],0,20,'utf8'));
            for ($v=1;$v<count($flst->Nachfolger);$v++) {
              $pdf->addText($col2_1,$row-=12,$fontSize,mb_substr($flst->Nachfolger[$v]['nachfolger'],0,20,'utf8'));
            }
          }

# Tatsächliche Nutzung
          if (trim($flst->Nutzung[0]['flaeche'])!='') {
            $pdf->addText($col0,$row-=24,$fontSize, utf8_decode('Tatsächliche Nutzung'));
            $row-=6;
            for ($i=0;$i<count($flst->Nutzung);$i++) {
            	# Seitenumbruch wenn erforderlich
              if($row<120) {
                # Seitenumbruch
                $seite++;
                # aktuelle Seite abschließen
                $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
                # neue Seite beginnen
                $pageid=$pdf->newPage();
                $pagecount[$f] = $pagecount[$f] + 1;
                $row=825; # 812 -> 825 2007-04-02 Schmidt
                $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
              }
              $pdf->addText($col1,$row-=12,$fontSize, utf8_decode($flst->Nutzung[$i]['flaeche'].' m²'));
              $pdf->addText($col2,$row,$fontSize, utf8_decode('('.$flst->Nutzung[$i]['nutzungskennz'].')'));
              $Nutzunglangtext = '';
							if($flst->Nutzung[$i]['untergliederung2'])$Nutzunglangtext = $flst->Nutzung[$i]['untergliederung2'].' ';
							if($flst->Nutzung[$i]['untergliederung1'])$Nutzunglangtext.= $flst->Nutzung[$i]['untergliederung1'].' ';
							if($flst->Nutzung[$i]['nutzungsart'])$Nutzunglangtext.= $flst->Nutzung[$i]['nutzungsart'].' ';
							if($Nutzunglangtext != '')$Nutzunglangtext.= '(Gruppe: '.$flst->Nutzung[$i]['gruppe'].') ';
							else $Nutzunglangtext.= $flst->Nutzung[$i]['gruppe'];
              if ($flst->Nutzung[$i]['abkuerzung']!='') {
                $Nutzunglangtext.=' ('.$flst->Nutzung[$i]['abkuerzung'].')';
              }
              $ausgabetext=zeilenumbruch($Nutzunglangtext,60);
              $pdf->addText($col2_2,$row,$fontSize,utf8_decode($ausgabetext[0]));
              for ($j=1;$j<count($ausgabetext);$j++) {
                $pdf->addText($col2_2,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
              }
            }
          }

# Gesetzliche Klassifizierung
          if($flst->Klassifizierung[0]['wert'] != ''){
			$pdf->addText($col0,$row-=24,$fontSize, utf8_decode('Gesetzl. Klassifizierung Bodenschätzung'));
			$row-=6;
			$emzges_a = 0; $emzges_gr = 0; $emzges_agr = 0; $emzges_gra = 0;
			$flaeche_a = 0; $flaeche_gr = 0; $flaeche_agr = 0; $flaeche_gra = 0;
			for($j = 0; $j < count_or_0($flst->Klassifizierung); $j++){
				if($flst->Klassifizierung[$j]['flaeche'] != ''){
					if($row<120) {
						# Seitenumbruch
						$seite++;
						# aktuelle Seite abschließen
						$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
						# neue Seite beginnen
						$pageid=$pdf->newPage();
						$pagecount[$f] = $pagecount[$f] + 1;
						$row=825; # 812 -> 825 2007-04-02 Schmidt
						$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
					}
					$wert=$flst->Klassifizierung[$j]['wert'];
					$emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
					if($flst->Klassifizierung[$j]['objart'] == 1000){
						$emzges_a = $emzges_a + $emz;
						$flaeche_a = $flaeche_a + $flst->Klassifizierung[$j]['flaeche'];
					}
					if($flst->Klassifizierung[$j]['objart'] == 2000){
						$emzges_agr = $emzges_agr + $emz;
						$flaeche_agr = $flaeche_agr + $flst->Klassifizierung[$j]['flaeche'];
					}
					if($flst->Klassifizierung[$j]['objart'] == 3000){
						$emzges_gr = $emzges_gr + $emz;
						$flaeche_gr = $flaeche_gr + $flst->Klassifizierung[$j]['flaeche'];
					}
					if($flst->Klassifizierung[$j]['objart'] == 4000){
						$emzges_gra = $emzges_gra + $emz;
						$flaeche_gra = $flaeche_gra + $flst->Klassifizierung[$j]['flaeche'];
					}
					$pdf->addText($col1,$row-=12,$fontSize, utf8_decode(round($flst->Klassifizierung[$j]['flaeche']).' m²'));
					$pdf->addText($col2,$row,$fontSize,utf8_decode($flst->Klassifizierung[$j]['label']));
					$pdf->addText($col5,$row,$fontSize, 'EMZ: '.$emz);
					$pdf->addText($col7,$row,$fontSize, 'BWZ: '.$wert);
				}
			}
	if($row<120) {
				# Seitenumbruch
				$seite++;
				# aktuelle Seite abschließen
				$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
				# neue Seite beginnen
				$pageid=$pdf->newPage();
				$pagecount[$f] = $pagecount[$f] + 1;
				$row=825; # 812 -> 825 2007-04-02 Schmidt
				$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
			}
			$nichtgeschaetzt=$flst->Klassifizierung['nicht_geschaetzt'];
			if($nichtgeschaetzt>0){
				$pdf->addText($col1,$row-=12,$fontSize, utf8_decode('nicht geschätzt: '.$nichtgeschaetzt.' m²'));
			}
			if($emzges_a > 0){
				$BWZ_a = round($emzges_a/$flaeche_a*100);
				$pdf->addText($col1,$row-=12,$fontSize, utf8_decode('Ackerland gesamt: ' . $flaeche_a . ' m², EMZ '.$emzges_a.', BWZ '.$BWZ_a));
			}
			if($emzges_gr > 0){
				$BWZ_gr = round($emzges_gr/$flaeche_gr*100);
				$pdf->addText($col1,$row-=12,$fontSize, utf8_decode('Grünland gesamt: ' . $flaeche_gr . ' m², EMZ '.$emzges_gr.', BWZ '.$BWZ_gr));
			}
			if($emzges_agr > 0){
				$BWZ_agr = round($emzges_agr/$flaeche_agr*100);
				$pdf->addText($col1,$row-=12,$fontSize, utf8_decode('Acker-Grünland gesamt: ' . $flaeche_agr . ' m², EMZ '.$emzges_agr.', BWZ '.$BWZ_agr));
			}
			if($emzges_gra > 0){
					$BWZ_gra = round($emzges_gra/$flaeche_gra*100);
					$pdf->addText($col1,$row-=12,$fontSize, utf8_decode('Grünland-Acker gesamt: ' . $flaeche_gra . ' m², EMZ '.$emzges_gra.', BWZ '.$BWZ_gra));
			}
		}

        if($row<120) {
            # Seitenumbruch
            $seite++;
            # aktuelle Seite abschließen
            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
            # neue Seite beginnen
            $pageid=$pdf->newPage();
            $pagecount[$f] = $pagecount[$f] + 1;
            $row=825; # 812 -> 825 2007-04-02 Schmidt
          	$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
          }


        } # endif 30 oder 35
		$row-=18;

		if($flst->Status != 'H'){ ### Status H gibt es nicht mehr!!!

	        # Amtsgericht, Grundbuchbezirk
	        $pdf->addText($col0,$row-=12,$fontSize,'Amtsgericht');
	        $pdf->addText($col2,$row,$fontSize,utf8_decode(implode(", ", array_map(function($e){return $e['name'] . ' (' . $e['schluessel'] . ')';},	$flst->Amtsgerichte))));
	        $pdf->addText($col0,$row-=12,$fontSize,'Grundbuchbezirk');
	        $pdf->addText($col2,$row,$fontSize,utf8_decode(implode(", ", array_map(function($e){return $e['name'] . ' (' . $e['schluessel'] . ')';},	$flst->Grundbuchbezirke))));
		    $row-=18;
	        ################################################################################
	        # Bestandsnachweis #
	        ####################
	        switch ($formnummer) {
	          case 40 : {
							for ($b=0;$b<count($flst->Buchungen);$b++) {
								# Seitenumbruch wenn erforderlich
								if($row<120) {
									# Seitenumbruch
									$seite++;
									# aktuelle Seite abschließen
									$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
									# neue Seite beginnen
									$pageid=$pdf->newPage();
									$pagecount[$f] = $pagecount[$f] + 1;
									$row=825; # 812 -> 825 2007-04-02 Schmidt
									$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
								}

								# Ausgabe der Zeile für die Bestandbezeichnung
								$pdf->addText($col0,$row-=12,$fontSize,'Bestand');
								$BestandStr =$flst->Buchungen[$b]['bezirk'].'-'.intval($flst->Buchungen[$b]['blatt']);
								$BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
								$BestandStr.=' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
								$BestandStr.=' ('.$flst->Buchungen[$b]['buchungsart'].')';
								$BestandStr.=' '.$flst->Buchungen[$b]['bezeichnung'];
								$pdf->addText($col2,$row,$fontSize, utf8_decode($BestandStr));
								
								if($flst->Buchungen[$b]['sondereigentum'] != ''){
									$row = $row - 12;
									$sondereigentum = 'verbunden mit Sondereigentum an '.$flst->Buchungen[$b]['sondereigentum'].'. Nr. '.$flst->Buchungen[$b]['auftplannr'].' laut Aufteilungsplan.';
									while(strlen($sondereigentum) > 60){
										$positionkomma=mb_strrpos(mb_substr($sondereigentum,0,60,'utf8'), ",", 0, 'utf8');
										$positionleerzeichen=mb_strrpos(mb_substr($sondereigentum,0,60,'utf8'), " ", 0, 'utf8');
										if($positionkomma>$positionleerzeichen){
											$positiontrenner=$positionkomma;
										}
										else{
											$positiontrenner=$positionleerzeichen;
										}
										if($row<120) {
											# Seitenumbruch
											$seite++;
											# aktuelle Seite abschließen
											$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
											# neue Seite beginnen
											$pageid=$pdf->newPage();
											$pagecount[$f] = $pagecount[$f] + 1;
											$row=825; # 812 -> 825 2007-04-02 Schmidt;
											$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
										}
										$pdf->addText($col2,$row-=12,$fontSize,utf8_decode(mb_substr($sondereigentum,0,$positiontrenner,'utf8')));
										$sondereigentum=mb_substr($sondereigentum,$positiontrenner+1, 999,'utf8');
									}
									$pdf->addText($col2,$row-=12,$fontSize,utf8_decode($sondereigentum));
								}

								# Abfragen und Ausgeben der Eigentümer zum Grundbuchblatt
								$Eigentuemerliste=$flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
								reset($Eigentuemerliste);
								$this->ALBAuszug_outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, NULL, $pdf,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,$pagecount,$f, $col0, $col1, $col9_1);
								if ($flst->Buchungen[$b]['zusatz_eigentuemer'] != '') {
									$zusatzeigentuemertext = $flst->Buchungen[$b]['zusatz_eigentuemer'];
									while(strlen($zusatzeigentuemertext) > 60){
										$positionkomma=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'), ",", 0, 'utf8');
										$positionleerzeichen=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'), " ", 0, 'utf8');
										if($positionkomma>$positionleerzeichen){
											$positiontrenner=$positionkomma;
										}
										else{
											$positiontrenner=$positionleerzeichen;
										}
										if($row<120) {
											# Seitenumbruch
											$seite++;
											# aktuelle Seite abschließen
											$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
											# neue Seite beginnen
											$pageid=$pdf->newPage();
											$pagecount[$f] = $pagecount[$f] + 1;
											$row=825; # 812 -> 825 2007-04-02 Schmidt;
											$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
										}
										$pdf->addText($col1,$row-=12,$fontSize,utf8_decode(mb_substr($zusatzeigentuemertext,0,$positiontrenner,'utf8')));
										$zusatzeigentuemertext=mb_substr($zusatzeigentuemertext,$positiontrenner+1, 999,'utf8');
									}
									$pdf->addText($col1,$row-=12,$fontSize,utf8_decode($zusatzeigentuemertext));
								}
								$row = $row - 24;
							} # ende Schleife Bestand
	          } # ende Ausgabe Formular 40
	          break;
	          case 35 : {
							for ($b=0;$b<count($flst->Buchungen);$b++) {
								# Seitenumbruch wenn erforderlich
								if($row<120) {
									# Seitenumbruch
									$seite++;
									# aktuelle Seite abschließen
									$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
									# neue Seite beginnen
									$pageid=$pdf->newPage();
									$pagecount[$f] = $pagecount[$f] + 1;
									$row=825; # 812 -> 825 2007-04-02 Schmidt;
									$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
								}

								# Ausgabe der Zeile für die Bestandbezeichnung
								$pdf->addText($col0,$row-=12,$fontSize,'Bestand');
								$BestandStr =$flst->Buchungen[$b]['bezirk'].'-'.intval($flst->Buchungen[$b]['blatt']);
								$BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
								$BestandStr.=' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
								$BestandStr.=' ('.$flst->Buchungen[$b]['buchungsart'].')';
								$BestandStr.=' '.$flst->Buchungen[$b]['bezeichnung'];
								$pdf->addText($col2,$row,$fontSize, utf8_decode($BestandStr));
								
								if($flst->Buchungen[$b]['sondereigentum'] != ''){
									$row = $row - 12;
									$sondereigentum = 'verbunden mit Sondereigentum an '.$flst->Buchungen[$b]['sondereigentum'].'. Nr. '.$flst->Buchungen[$b]['auftplannr'].' laut Aufteilungsplan.';
									while(strlen($sondereigentum) > 60){
										$positionkomma=mb_strrpos(mb_substr($sondereigentum,0,60,'utf8'), ",", 0, 'utf8');
										$positionleerzeichen=mb_strrpos(mb_substr($sondereigentum,0,60,'utf8'), " ", 0, 'utf8');
										if($positionkomma>$positionleerzeichen){
											$positiontrenner=$positionkomma;
										}
										else{
											$positiontrenner=$positionleerzeichen;
										}
										if($row<120) {
											# Seitenumbruch
											$seite++;
											# aktuelle Seite abschließen
											$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
											# neue Seite beginnen
											$pageid=$pdf->newPage();
											$pagecount[$f] = $pagecount[$f] + 1;
											$row=825; # 812 -> 825 2007-04-02 Schmidt;
											$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
										}
										$pdf->addText($col2,$row-=12,$fontSize,utf8_decode(mb_substr($sondereigentum,0,$positiontrenner,'utf8')));
										$sondereigentum=mb_substr($sondereigentum,$positiontrenner+1, 999,'utf8');
									}
									$pdf->addText($col2,$row-=12,$fontSize,utf8_decode($sondereigentum));
								}

								# Abfragen und Ausgeben der Eigentümer zum Grundbuchblatt
								$Eigentuemerliste=$flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
								reset($Eigentuemerliste);
								$this->ALBAuszug_outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, NULL, $pdf,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,$pagecount,$f, $col0, $col1, $col9_1);
								if ($flst->Buchungen[$b]['zusatz_eigentuemer'] != '') {
									$zusatzeigentuemertext = $flst->Buchungen[$b]['zusatz_eigentuemer'];
									while(strlen($zusatzeigentuemertext) > 60){
										$positionkomma=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'), ",", 0, 'utf8');
										$positionleerzeichen=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'), " ", 0, 'utf8');
										if($positionkomma>$positionleerzeichen){
											$positiontrenner=$positionkomma;
										}
										else{
											$positiontrenner=$positionleerzeichen;
										}
										if($row<120) {
											# Seitenumbruch
											$seite++;
											# aktuelle Seite abschließen
											$pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
											# neue Seite beginnen
											$pageid=$pdf->newPage();
											$pagecount[$f] = $pagecount[$f] + 1;
											$row=825; # 812 -> 825 2007-04-02 Schmidt;
											$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
										}
										$pdf->addText($col1,$row-=12,$fontSize,utf8_decode(mb_substr($zusatzeigentuemertext,0,$positiontrenner,'utf8')));
										$zusatzeigentuemertext=mb_substr($zusatzeigentuemertext,$positiontrenner+1, 999,'utf8');
									}
									$pdf->addText($col1,$row-=12,$fontSize,utf8_decode($zusatzeigentuemertext));
								}
								$row = $row - 24;
							} # ende Schleife Bestand
	          } # ende Ausgabe Formular 35
	          break;
	          case 30 : {
	            # Bestand

							$pdf->addText($col0,$row-=24,$fontSize,'Bestand');
							for ($b=0;$b<count($flst->Buchungen);$b++) {
								# Seitenumbruch wenn erforderlich
								if($row<60) {
									# Seitenumbruch
									$seite++;
									# aktuelle Seite abschließen
									$pdf->addText($col9_1,$row-=24,$fontSize,'Forts. Seite '.$seite);
									# neue Seite beginnen
									$pageid=$pdf->newPage();
									$pagecount[$f] = $pagecount[$f] + 1;
									$row=825; # 812 -> 825 2007-04-02 Schmidt
									$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
									$pdf->addText($col0,$row-=24,$fontSize,'Bestand');
								}

								# Ausgabe der Zeile für die Bestandbezeichnung
								$BestandStr =$flst->Buchungen[$b]['bezirk'].'-'.intval($flst->Buchungen[$b]['blatt']);
								$BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
								$BestandStr.=' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
								$BestandStr.=' ('.$flst->Buchungen[$b]['buchungsart'].')';
								$BestandStr.=' '.$flst->Buchungen[$b]['bezeichnung'];
								$pdf->addText($col2,$row,$fontSize, utf8_decode($BestandStr));
								$row-=18;

							} # ende Schleife Bestand
	          } # ende Ausgabe Bestandsnachweis Formular 30
	          break;
	        } # end of switch for Bestandsnachweis
				}
        # neue Seite beginnen
        if($f < count_or_0($FlurstKennz)-1){
          $pageid=$pdf->newPage();
          //$pagecount[$f] = $pagecount[$f] + 1;
        }
      } # end of flurstück is not historisch
    } # end of for all flurstücke
    $pdf->pagecount = $pagecount;
    return $pdf;
  }

  function ALBAuszug_Bestand($Grundbuchbezirk,$Grundbuchblatt,$formnummer) {
    $pdf=new Cezpdf();
		$pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/Helvetica.afm');
    # Hilfsobjekte erzeugen

    $grundbuch=new grundbuch($Grundbuchbezirk,$Grundbuchblatt,$this->database);
    # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
    $ret=$grundbuch->getBuchungen('','','',1);
    $buchungen=$ret[1];

    # ein Flurstück erzeugen
    $flst=new flurstueck($buchungen[0]['flurstkennz'],$this->database);
    $flst->database=$this->database;
    $ret=$flst->readALB_Data($buchungen[0]['flurstkennz'], true, 'ogc_fid');
		$flst->Grundbuecher=$flst->getGrundbuecher();

    $seite=1;
    $fontSize=12;
    $col0=50; # 28 -> 50 Schmidt 2007-04-02
    $col1=$col0+7.23;
    $col6=$col0+43.37;
    $col10=$col0+72.28;
    $col18=$col0+130.11;
    $col27=$col0+195.17;
    $col34=$col0+245.76;
    $col37=$col0+267.45;
    $col42=$col0+303.59;
    $col44=$col0+318.05;
    $col48=$col0+346.96;
    $col57=$col0+412.02;
    $col58=$col0+419.24;
    $col59=$col0+426.47;
    $col62=$col0+448.16;
    $col64=$col0+462.61;
    $col70=$col0+505.99;
    $col00=28;
#    $col0=35;
#    $col1=$col0+20;
#    $col1=$col0+35;
#    $col1a=$col1+16;
#    $col1b=$col1a+30;
#    $col1_1=115;
#    $col2=$col0+100;
#    $col2_1=$col2+50;
#    $col2_2=$col2_1+20;
#    $col2_3=$col2+25;
#    $col3=$col0+185;
#    $col3=$col0+187;
#    $col4=$col0+200;
#    $col5=$col0+248;
#    $col6=342;
#    $col7=363;
#    $col7=330;
#    $col8=$col6+70;
#    $col9=527;
#    $col9_1=$col9-50;
#23.11.06 H.Riedel, eingefuegt
#    $col9_10=$col9-38;
    $row=825; # 812 -> 825 2007-04-02 Schmidt
    switch ($formnummer) {
      case '20' : {
#        $Ueberschrift='******** Bestandsnachweis *******';
        $Ueberschrift='Bestandsdaten - interne Verwendung';
        $art = 'Bestand';
      } break;
      case '25' : {
#        $Ueberschrift='******** Bestandsübersicht *******';
        $Ueberschrift='Übersicht Bestandsdaten - interne Verwendung';
        $art = 'Bestand';
      } break;
    }

#    $BestandStr =$buchungen[0]['bezirk'].'-'.intval($buchungen[0]['blatt']);
    $BestandStr =$buchungen[0]['bezirk'].'-'.($buchungen[0]['blatt'].' ');
#    $BestandStr.=' '.str_pad($buchungen[0]['pruefzeichen'],3,' ',STR_PAD_LEFT);
    $BestandStr.=str_pad($buchungen[0]['pruefzeichen'],2,' ',STR_PAD_LEFT);
#28.11.2006 H.Riedel, Aktualitaetsnr uebergeben
    $AktualitaetsNr=$buchungen[0]['aktualitaetsnr'];
    $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,$art,$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
  	if(AMT != ''){
    	$amt = AMT;
    	if($katasterfuehrendestelle){
    		foreach ($katasterfuehrendestelle as $key => $value) {
					if($flst->Grundbuecher[0]['bezirk'] <= $key) {
		      	$amt .= $value;
		      	break;
		    	}
        }
      }
	  $pdf->addText($col0,$row-=12,$fontSize,utf8_decode($amt));
    }
    if(LANDKREIS != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(LANDKREIS));
    if(STRASSE != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(STRASSE));
    if(STRASSE2 != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(STRASSE2));
    if(PLZ != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(PLZ.' '.ORT));
    if(POSTANSCHRIFT != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT));
    if(POSTANSCHRIFT_STRASSE != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT_STRASSE));
    if(POSTANSCHRIFT_PLZ != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT_PLZ.' '.POSTANSCHRIFT_ORT));
    # Amtsgericht, Grundbuchbezirk
    $pdf->addText($col1,$row-=12,$fontSize,'Grundbuchbezirk');
    $pdf->addText($col27,$row,$fontSize,str_pad(implode(", ", array_map(function($e){return $e['schluessel'];},	$flst->Grundbuchbezirke)),11," "));
    $pdf->addText($col42,$row,$fontSize,utf8_decode(implode(", ", array_map(function($e){return $e['name'];},	$flst->Grundbuchbezirke))));
    $pdf->addText($col1,$row-=12,$fontSize,'Amtsgericht');
    $pdf->addText($col27,$row,$fontSize,str_pad(implode(", ", array_map(function($e){return $e['schluessel'];},	$flst->Amtsgerichte)),11," "));
    $pdf->addText($col42,$row,$fontSize,utf8_decode(implode(", ", array_map(function($e){return $e['name'];},	$flst->Amtsgerichte))));
#   $pdf->addText($col00,$row-=12,$fontSize,str_repeat("-",75));
    $pdf->addText($col0,$row-=12,$fontSize,str_repeat("-",73));

    ################################################################################
    # Bestandsnachweis #
    ####################
    switch ($formnummer) {
      case 25 : {
        # Bestand

        # Abfragen und Ausgeben der Eigentümer zum Grundbuchblatt
        $Eigentuemerliste=$flst->getEigentuemerliste($buchungen[0]['bezirk'],$buchungen[0]['blatt'],$buchungen[0]['bvnr']);
				reset($Eigentuemerliste);
				$this->ALBAuszug_outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, NULL, $pdf,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,$pagecount,$f, $col0, $col1, $col9_1);

        if ($buchungen[0]['zusatz_eigentuemer'] != '') {
                $zusatzeigentuemertext=$buchungen[0]['zusatz_eigentuemer'];
                while(strlen($zusatzeigentuemertext) > 60){
                  $positionkomma=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'), ",", 0, 'utf8');
                  $positionleerzeichen=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'), " ", 0, 'utf8');
                  if($positionkomma>$positionleerzeichen){
                    $positiontrenner=$positionkomma;
                  }
                  else{
                    $positiontrenner=$positionleerzeichen;
                  }
                  if($row<120) {
                    # Seitenumbruch
                    $seite++;
                    # aktuelle Seite abschließen
                    $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.$seite);
                    # neue Seite beginnen
                    $pageid=$pdf->newPage();
                    $row=825; # 812 -> 825 2007-04-02 Schmidt;
                    $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Bestand',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
                  }
                  $pdf->addText($col1,$row-=12,$fontSize,utf8_decode(mb_substr($zusatzeigentuemertext,0,$positiontrenner,'utf8')));
                  $zusatzeigentuemertext=mb_substr($zusatzeigentuemertext,$positiontrenner+1, 999, 'utf8');
                }
                $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($zusatzeigentuemertext));
              }

        $gesamtflaeche = 0;
        for ($b=0;$b < count_or_0($buchungen);$b++) {
          # Flurstück erzeugen
          $flst=new flurstueck($buchungen[$b]['flurstkennz'],$this->database);
          $flst->database=$this->database;
          $ret=$flst->readALB_Data($buchungen[$b]['flurstkennz'], true, 'ogc_fid');

          # Seitenumbruch wenn erforderlich
          if($row<120) {
            # Seitenumbruch
            $seite++;
            # aktuelle Seite abschließen
#            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
            $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
            # neue Seite beginnen
            $pageid=$pdf->newPage();
            $row=825; # 812 -> 825 2007-04-02 Schmidt
#            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr);
            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
          }
# 23.11.2006 H. Riedel, if-Schleife bzgl. Gemarkungsausgabe anpassen
          if ($buchungen[$b-1]['gemkgname'] != $buchungen[$b]['gemkgname']) {
#            $pdf->addText($col1b,$row-=36,$fontSize,'Gemarkung  '.$flst->GemkgName);
            $pdf->addText($col10,$row-=24,$fontSize,'Gemarkung  '.utf8_decode($flst->GemkgName));
#         $pdf->addText($col0,$row-=24,$fontSize,'BVNR Art GMKG   FLR FLURST-NR    P');
      $pdf->addText($col1,$row-=24,$fontSize,'BVNR Art GMKG   FLR FLURST-NR    P');
#         $pdf->addText($col9-10,$row,$fontSize,'Fläche');
#29.11.2006 H. Riedel, Flurkarte, Riss hinzugefügt
      $pdf->addText($col44,$row,$fontSize,'Flurkarte Riss');
      $pdf->addText($col64,$row,$fontSize, utf8_decode('Fläche'));
        }
          if ($flst->Nenner!=0) {
            $nennerausgabe="/".$flst->Nenner;
          }
          else{
            $nennerausgabe= '';
          }
         if($buchungen[$b-1]['bvnr'] != $buchungen[$b]['bvnr']){
            $pdf->addText($col1,$row-=12,$fontSize,str_pad(intval($buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT).' ('.utf8_decode($buchungen[$b]['bezeichnung']).') ');
            if($buchungen[$b]['anteil'] != ''){
#              if($buchungen[$b]['buchungsart'] == 'N' OR $buchungen[$b]['buchungsart'] == 'W'){
              if($buchungen[$b]['buchungsart'] == '1101' OR $buchungen[$b]['buchungsart'] == '1102' OR $buchungen[$b]['buchungsart'] == '1301' OR $buchungen[$b]['buchungsart'] == '1302'){
                $pdf->addText($col10,$row-=12,$fontSize, utf8_decode($buchungen[$b]['anteil'].' Miteigentumsanteil an'));
              }
#              elseif($buchungen[$b]['buchungsart'] == 'H'){
              elseif($buchungen[$b]['buchungsart'] == '2205' OR $buchungen[$b]['buchungsart'] == '2305'){
                $pdf->addText($col10,$row-=12,$fontSize, $buchungen[$b]['anteil'].' '.utf8_decode($buchungen[$b]['bezeichnung']).' an');
              }
              $row = $row-12;
            }
#            elseif($buchungen[$b]['buchungsart'] != 'N'){
            elseif($buchungen[$b]['buchungsart'] != '2101'){
              $pdf->addText($col10,$row-=12,$fontSize, utf8_decode($buchungen[$b]['bezeichnung']).' an');
            }
						$row-=12;
          }
          else{
            $row = $row-12;
          }
          $pdf->addText($col10,$row,$fontSize,$flst->GemkgSchl." ".str_pad($flst->FlurNr,3," ",STR_PAD_LEFT)." ".str_pad($flst->Zaehler,5," ",STR_PAD_LEFT).$nennerausgabe);
          #$pdf->addText($col34,$row,$fontSize,$flst->getPruefKZ());
          $pdf->addText($col59,$row,$fontSize, utf8_decode(str_pad(str_space($flst->ALB_Flaeche,3).' m2',14,' ',STR_PAD_LEFT)));
          $gesamtflaeche += $flst->ALB_Flaeche;

#         $pdf->addText($col1b,$row-=12,$fontSize,'Lage');
          $pdf->addText($col10,$row-=12,$fontSize,'Lage');
          # Ausgabe der Adressangabe zur Lage
          $anzStrassen= count_or_0($flst->Adresse);
          for ($s=0;$s<$anzStrassen;$s++) {
            $Adressbezeichnung.=$flst->Adresse[$s]["strassenname"];
            $Adressbezeichnung.=' '.$flst->Adresse[$s]["hausnr"];
            $ausgabetext=zeilenumbruch($Adressbezeichnung,40);
            $pdf->addText($col18,$row-=$s*12,$fontSize,utf8_decode($ausgabetext[0]));
            for ($j=1;$j<count($ausgabetext);$j++) {
              $pdf->addText($col18+43,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
          }
          if ($anzStrassen == 0) {
          	$Adressbezeichnung=$flst->Lage[0];
            $pdf->addText($col18,$row,$fontSize,utf8_decode($Adressbezeichnung));
          }
          $Adressbezeichnung = '';

#         $pdf->addText($col1b,$row-=12,$fontSize,'Nutzung');
          $pdf->addText($col10,$row-=12,$fontSize,'Nutzung');
          for ($i=0;$i<count($flst->Nutzung);$i++) {
            # Seitenumbruch wenn erforderlich
            if($row<120) {
              # Seitenumbruch
              $seite++;
              # aktuelle Seite abschließen
  #            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
              $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
              # neue Seite beginnen
              $pageid=$pdf->newPage();
              $row=825; # 812 -> 825 2007-04-02 Schmidt
  #            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr);
              $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
            }
#30.11.2006 H.Riedel, Zeilenweiterzaehlen wenn mehr als eine Nutzungsart pro Flst.
            if ($i>=1) {
              $row-=12;
            }
            $Nutzunglangtext = '';
						if($flst->Nutzung[$i]['untergliederung2'])$Nutzunglangtext = $flst->Nutzung[$i]['untergliederung2'].' ';
						if($flst->Nutzung[$i]['untergliederung1'])$Nutzunglangtext.= $flst->Nutzung[$i]['untergliederung1'].' ';
						if($flst->Nutzung[$i]['nutzungsart'])$Nutzunglangtext.= $flst->Nutzung[$i]['nutzungsart'].' ';
						if($Nutzunglangtext != '')$Nutzunglangtext.= '(Gruppe: '.$flst->Nutzung[$i]['gruppe'].') ';
						else $Nutzunglangtext.= $flst->Nutzung[$i]['gruppe'];
            if ($flst->Nutzung[$i]['abkuerzung']!='') {
              $Nutzunglangtext.=' ('.$flst->Nutzung[$i]['abkuerzung'].')';
            }
            $ausgabetext=zeilenumbruch($Nutzunglangtext,40);
#           $pdf->addText($col2_3,$row,$fontSize,$ausgabetext[0]);
            $pdf->addText($col18,$row,$fontSize,utf8_decode($ausgabetext[0]));
            for ($j=1;$j<count($ausgabetext);$j++) {
#             $pdf->addText($col2_3,$row-=12,$fontSize,$ausgabetext[$j]);
              $pdf->addText($col18,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
#           $pdf->addText($col9-10,$row,$fontSize,str_pad($flst->Nutzung[$i]['flaeche'],6, ' ', STR_PAD_LEFT).' m2');
            $pdf->addText($col59,$row,$fontSize, utf8_decode(str_pad(str_space($flst->Nutzung[$i]['flaeche'],3).' m2',14, ' ', STR_PAD_LEFT)));
          }
          if($buchungen[$b+1]['bvnr'] != $buchungen[$b]['bvnr']){
            if($buchungen[$b]['sondereigentum'] != ''){
              $sondereigentum = zeilenumbruch('verbunden mit dem Sondereigentum an '.$buchungen[$b]['sondereigentum'],40);
              $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($sondereigentum[0]));
              for ($j=1;$j<count($sondereigentum);$j++) {
                $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($sondereigentum[$j]));
              }
            }
            if($buchungen[$b]['auftplannr'] != ''){
              $pdf->addText($col6,$row-=12,$fontSize, utf8_decode('Aufteilungsplan-Nr. '.$buchungen[$b]['auftplannr']));
            }
#            if($buchungen[$b]['erbbaurechtshinw'] == 'E'){
            if($buchungen[$b]['buchungsart'] == '2101' OR $buchungen[$b]['buchungsart'] == '2203' OR $buchungen[$b]['buchungsart'] == '2303'){
              $pdf->addText($col6,$row-=12,$fontSize, 'belastet mit Erbbaurecht');
            }
#            if($buchungen[$b]['erbbaurechtshinw'] == 'G'){
            if($buchungen[$b]['buchungsart'] == '2103'){
              $pdf->addText($col6,$row-=12,$fontSize, 'belastet mit Nutzungsrecht');
            }
						$row-=12;
          }
					
#30.11.2006 H.Riedel, Zeile zur Trennung zw. den Flst.
        } # ende Schleife Bestand
#        $pdf->addText($col7+10,$row-=12,$fontSize,str_repeat("-",29));
        $pdf->addText($col44,$row-=12,$fontSize,str_repeat("-",29));
#       $pdf->addText($col7+10,$row-=12,$fontSize,'Bestandsfläche '.str_pad($gesamtflaeche,11,'*',STR_PAD_LEFT).' m2');
        $pdf->addText($col44,$row-=12,$fontSize, utf8_decode('Bestandsfläche '.str_pad(str_space($gesamtflaeche,3).' m2',14,'*',STR_PAD_LEFT)));
#       $pdf->addText($col7+10,$row-=12,$fontSize,str_repeat("=",29));
        $pdf->addText($col44,$row-=12,$fontSize,str_repeat("=",29));
      } # ende Ausgabe Formular 25
      break;
      case 20 : {
        # Bestand

        # Abfragen und Ausgeben der Eigentümer zum Grundbuchblatt
        $Eigentuemerliste=$flst->getEigentuemerliste($buchungen[0]['bezirk'],$buchungen[0]['blatt'],$buchungen[0]['bvnr']);
        reset($Eigentuemerliste);
				$this->ALBAuszug_outputEigentuemer(key($Eigentuemerliste), $Eigentuemerliste, NULL, $pdf,$Ueberschrift,'Flurstück',$seite,$row,$fontSize,$pagecount,$f, $col0, $col1, $col9_1);

				if ($buchungen[0]['zusatz_eigentuemer'] != '') {
          $zusatzeigentuemertext=$buchungen[0]['zusatz_eigentuemer'];
          while(strlen($zusatzeigentuemertext) > 60){
            $positionkomma=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'), ",", 0, 'utf8');
            $positionleerzeichen=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'), " ", 0, 'utf8');
            if($positionkomma>$positionleerzeichen){
              $positiontrenner=$positionkomma;
            }
            else{
              $positiontrenner=$positionleerzeichen;
            }
            if($row<120) {
              # Seitenumbruch
              $seite++;
              # aktuelle Seite abschließen
              $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.$seite);
              # neue Seite beginnen
              $pageid=$pdf->newPage();
              $row=825; # 812 -> 825 2007-04-02 Schmidt;
              $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Bestand',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
            }
            $pdf->addText($col1,$row-=12,$fontSize,utf8_decode(mb_substr($zusatzeigentuemertext,0,$positiontrenner,'utf8')));
            $zusatzeigentuemertext=mb_substr($zusatzeigentuemertext,$positiontrenner+1, 999, 'utf8');
          }
          $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($zusatzeigentuemertext));
        }

        $gesamtflaeche = 0;
        for ($b=0;$b < count_or_0($buchungen);$b++) {
          # Flurstück erzeugen
          $flst=new flurstueck($buchungen[$b]['flurstkennz'],$this->database);
          $flst->database=$this->database;
          $ret=$flst->readALB_Data($buchungen[$b]['flurstkennz'], true, 'ogc_fid');

          # Seitenumbruch wenn erforderlich
          if($row<120) {
            # Seitenumbruch
            $seite++;
            # aktuelle Seite abschließen
            $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
            # neue Seite beginnen
            $pageid=$pdf->newPage();
            $row=825; # 812 -> 825 2007-04-02 Schmidt
            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
          }
          if($buchungen[$b-1]['gemkgname'] != $buchungen[$b]['gemkgname']) {
            $pdf->addText($col10,$row-=36,$fontSize,'Gemarkung  '.utf8_decode($flst->GemkgName));
            $pdf->addText($col1,$row-=24,$fontSize,'BVNR Art GMKG   FLR FLURST-NR    P');
            $pdf->addText($col64,$row,$fontSize, utf8_decode('Fläche'));
          }
          if ($flst->Nenner!=0) {
            $nennerausgabe="/".$flst->Nenner;
          }
          else{
            $nennerausgabe= '';
          }
          if($buchungen[$b-1]['bvnr'] != $buchungen[$b]['bvnr']){
            $pdf->addText($col1,$row-=12,$fontSize, utf8_decode($buchungen[$b]['buchungsart'].' '.str_pad(intval($buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT).' ('.utf8_decode($buchungen[$b]['bezeichnung']).') '));
            if($buchungen[$b]['anteil'] != ''){
#              if($buchungen[$b]['buchungsart'] == 'N' OR $buchungen[$b]['buchungsart'] == 'W'){
              if($buchungen[$b]['buchungsart'] == '1101' OR $buchungen[$b]['buchungsart'] == '1102' OR $buchungen[$b]['buchungsart'] == '1301' OR $buchungen[$b]['buchungsart'] == '1302'){
                $pdf->addText($col10,$row-=12,$fontSize, utf8_decode($buchungen[$b]['anteil'].' Miteigentumsanteil an'));
              }
#              elseif($buchungen[$b]['buchungsart'] == 'H'){
              elseif($buchungen[$b]['buchungsart'] == '2205' OR $buchungen[$b]['buchungsart'] == '2305'){
                $pdf->addText($col10,$row-=12,$fontSize, $buchungen[$b]['anteil'].' '.utf8_decode($buchungen[$b]['bezeichnung']));
              }
              $row = $row-12;
            }
 #           elseif($buchungen[$b]['buchungsart'] != 'N'){
            elseif($buchungen[$b]['buchungsart'] != '2101'){
              $pdf->addText($col10,$row-=12,$fontSize, utf8_decode($buchungen[$b]['bezeichnung']).' an');
						}
            $row = $row-12;
          }
          else{
            $row = $row-12;
          }
          $pdf->addText($col10,$row,$fontSize,$flst->GemkgSchl." ".str_pad($flst->FlurNr,3," ",STR_PAD_LEFT)." ".str_pad($flst->Zaehler,5," ",STR_PAD_LEFT).$nennerausgabe);
          #$pdf->addText($col34,$row,$fontSize,$flst->getPruefKZ());
          $pdf->addText($col59,$row,$fontSize, utf8_decode(str_pad(str_space($flst->ALB_Flaeche,3).' m2',14,' ',STR_PAD_LEFT)));
          $gesamtflaeche += $flst->ALB_Flaeche;
          if($buchungen[$b+1]['bvnr'] != $buchungen[$b]['bvnr']){
            if($buchungen[$b]['sondereigentum'] != ''){
              $sondereigentum = zeilenumbruch('verbunden mit dem Sondereigentum an '.$buchungen[$b]['sondereigentum'],40);
              $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($sondereigentum[0]));
              for ($j=1;$j<count($sondereigentum);$j++) {
                $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($sondereigentum[$j]));
              }
            }
            if($buchungen[$b]['auftplannr'] != ''){
              $pdf->addText($col6,$row-=12,$fontSize, utf8_decode('Aufteilungsplan-Nr. '.$buchungen[$b]['auftplannr']));
            }
#            if($buchungen[$b]['erbbaurechtshinw'] == 'E'){
            if($buchungen[$b]['buchungsart'] == '2101' OR $buchungen[$b]['buchungsart'] == '2203' OR $buchungen[$b]['buchungsart'] == '2303'){
              $pdf->addText($col6,$row-=12,$fontSize, 'belastet mit Erbbaurecht');
            }
#            if($buchungen[$b]['erbbaurechtshinw'] == 'G'){
            if($buchungen[$b]['buchungsart'] == '2103'){
              $pdf->addText($col6,$row-=12,$fontSize, 'belastet mit Nutzungsrecht');
            }
          }
        } # ende Schleife Bestand
#        $pdf->addText($col7+10,$row-=12,$fontSize,str_repeat("-",29));
        $pdf->addText($col44,$row-=12,$fontSize,str_repeat("-",29));
#       $pdf->addText($col7+10,$row-=12,$fontSize,'Bestandsfläche '.str_pad($gesamtflaeche,11,'*',STR_PAD_LEFT).' m2');
  $pdf->addText($col44,$row-=12,$fontSize, utf8_decode('Bestandsfläche '.str_pad(str_space($gesamtflaeche,3).' m2',14,'*',STR_PAD_LEFT)));
#       $pdf->addText($col7+10,$row-=12,$fontSize,str_repeat("=",29));
  $pdf->addText($col44,$row-=12,$fontSize,str_repeat("=",29));
      } # ende Ausgabe Formular 20
      break;
    } # end of switch for Bestandsnachweis
    $pdf->pagecount[] = $pdf->numPages;
    return $pdf;
  }
		
  function getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $epsgcode) {
    $ret=$this->database->getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $epsgcode);
    if ($ret[0]==0) {
      $rect = rectObj(
      	$ret[1]['minx'],
      	$ret[1]['miny'],				
				$ret[1]['maxx'],
				$ret[1]['maxy']
			);
      $ret[1]['rect'] = $rect;
    }
    return $ret;
  }
  
  function getMERfromGemeinde($Gemeinde, $epsgcode) {
    $ret = $this->database->getMERfromGemeinde($Gemeinde, $epsgcode);
    if ($ret[0] == 0) {
      $rect = rectObj(
      	$ret[1]['minx'],
      	$ret[1]['miny'],				
				$ret[1]['maxx'],
				$ret[1]['maxy']
			);
      $ret[1] = $rect;
    }
    return $ret;
  }
  
  function getMERfromGemarkung($Gemkgschl, $epsgcode) {
    # 2006-02-01 pk
    $ret=$this->database->getMERfromGemarkung($Gemkgschl, $epsgcode);
    if ($ret[0]==0) {
      $rect = rectObj(
      	$ret[1]['minx'],
      	$ret[1]['miny'],				
				$ret[1]['maxx'],
				$ret[1]['maxy']
			);
      $ret[1] = $rect;
    }
    return $ret;
  }

  function getMERfromFlur($Gemarkung,$Flur, $epsgcode) {
    # 2006-02-01 pk
    $ret=$this->database->getMERfromFlur($Gemarkung,$Flur,$epsgcode);
    if ($ret[0]==0) {
      $rect = rectObj(
      	$ret[1]['minx'],
      	$ret[1]['miny'],				
				$ret[1]['maxx'],
				$ret[1]['maxy']
			);
      $ret[1]=$rect;
    }
    return $ret;
  }
    
  function getMERfromFlurstuecke($flstliste, $epsgcode, $without_temporal_filter = 0) {
    $ret=$this->database->getMERfromFlurstuecke($flstliste, $epsgcode, $without_temporal_filter);
    if ($ret[0]==0) {
      $rect = rectObj(
      	$ret[1]['minx'],
      	$ret[1]['miny'],				
				$ret[1]['maxx'],
				$ret[1]['maxy']
			);
      $ret[1]=$rect;
    }
    return $ret;
  }	

}
