<?php
class Teilgewaesserbenutzungen extends WrPgObject {

	protected $tableName = 'fiswrv_teilgewaesserbenutzungen';
	
	public $gewaesserbenutzungArt;
	public $gewaesserbenutzungZweck;
	public $mengenbestimmung;
	public $art_benutzung;
	public $entgeltsatz;
	public $teilgewaesserbenutzungen_art;
	public $gewaesserbenutzungen;
	
	public function find_where_with_subtables($where, $order = NULL, $select = '*')
	{
	    $this->log->log_info('*** Teilgewaesserbenutzungen->find_where_with_subtables ***');
	    $this->log->log_debug('where: ' . $where);
	    
	    $teilgewaesserbenutzungen = $this->find_where($where, $order, $select);
	    
	    if(!empty($teilgewaesserbenutzungen))
	    {
	        foreach ($teilgewaesserbenutzungen AS $teilgewaesserbenutzung)
	        {
	            if(!empty($teilgewaesserbenutzung))
	            {
	                $gwa = new GewaesserbenutzungenArt($this->gui);
	                if(!empty($teilgewaesserbenutzung->data['art']))
	                {
	                    $gewaesserbenutzungArt = $gwa->find_where('id=' . $teilgewaesserbenutzung->data['art']);
	                    if(!empty($gewaesserbenutzungArt))
	                    {
	                        $teilgewaesserbenutzung->gewaesserbenutzungArt = $gewaesserbenutzungArt[0];
	                    }
	                }
	                
	                $gwz = new GewaesserbenutzungenZweck($this->gui);
	                if(!empty($teilgewaesserbenutzung->data['zweck']))
	                {
	                    $gewaesserbenutzungZweck = $gwz->find_where('id=' . $teilgewaesserbenutzung->data['zweck']);
	                    if(!empty($gewaesserbenutzungZweck))
	                    {
	                        $teilgewaesserbenutzung->gewaesserbenutzungZweck = $gewaesserbenutzungZweck[0];
	                    }
	                }
	                
// 	                $gwu = new GewaesserbenutzungenUmfang($this->gui);
// 	                if(!empty($teilgewaesserbenutzung->data['umfang']))
// 	                {
// 	                    // 	                echo 'id=' . $teilgewaesserbenutzung->data['umfang'];
// 	                    $gewaesserbenutzungUmfang = $gwu->find_where('id=' . $teilgewaesserbenutzung->data['umfang']);
// 	                    if(!empty($gewaesserbenutzungUmfang))
// 	                    {
// 	                        $teilgewaesserbenutzung->gewaesserbenutzungUmfang = $gewaesserbenutzungUmfang[0];
// 	                    }
// 	                }
	                
	                $mb = new Mengenbestimmung($this->gui);
	                if(!empty($teilgewaesserbenutzung->data['mengenbestimmung']))
	                {
	                    $mengenbestimmung = $mb->find_where('id=' . $teilgewaesserbenutzung->data['mengenbestimmung']);
	                    if(!empty($mengenbestimmung))
	                    {
	                        $teilgewaesserbenutzung->mengenbestimmung = $mengenbestimmung[0];
	                    }
	                }
	                
	                $ab = new GewaesserbenutzungenArtBenutzung($this->gui);
	                if(!empty($teilgewaesserbenutzung->data['art_benutzung']))
	                {
	                    $gewaesserbenutzungArtBenutzung = $ab->find_where('id=' . $teilgewaesserbenutzung->data['art_benutzung']);
	                    if(!empty($gewaesserbenutzungArtBenutzung))
	                    {
	                        $teilgewaesserbenutzung->art_benutzung = $gewaesserbenutzungArtBenutzung[0];
	                    }
	                }
	                
	                $eesatz = new GewaesserbenutzungenWeeSatz($this->gui);
	                if(!empty($teilgewaesserbenutzung->data['entgeltsatz']))
	                {
	                    $entgeltsatz = $eesatz->find_where('id=' . $teilgewaesserbenutzung->data['entgeltsatz']);
	                    if(!empty($entgeltsatz))
	                    {
	                        $teilgewaesserbenutzung->entgeltsatz = $entgeltsatz[0];
	                    }
	                }
	                
	                $tgba = new TeilgewaesserbenutzungenArt($this->gui);
	                if(!empty($teilgewaesserbenutzung->data['teilgewaesserbenutzungen_art']))
	                {
	                    $teilgewaesserbenutzungen_art = $tgba->find_where('id=' . $teilgewaesserbenutzung->data['teilgewaesserbenutzungen_art']);
	                    if(!empty($teilgewaesserbenutzungen_art))
	                    {
	                        $teilgewaesserbenutzung->teilgewaesserbenutzungen_art = $teilgewaesserbenutzungen_art[0];
	                    }
	                }
	                
	                $gb = new Gewaesserbenutzungen($this->gui);
	                if(!empty($teilgewaesserbenutzung->data['gewaesserbenutzungen']))
	                {
	                    $gewaesserbenutzungen = $gb->find_where('id=' . $teilgewaesserbenutzung->data['gewaesserbenutzungen']);
	                    if(!empty($gewaesserbenutzungen))
	                    {
	                        $teilgewaesserbenutzung->gewaesserbenutzungen = $gewaesserbenutzungen[0];
	                    }
	                }
	            }
	        }
	    }
	    
	    return $teilgewaesserbenutzungen;
	}
	
	public function getErhebungsjahr() {
	    return $this->data['erhebungsjahr'];
	}
	
	public function getWiedereinleitungNutzer() {
	    return $this->data['wiedereinleitung_nutzer'];
	}
	
	public function getWiedereinleitungBearbeiter() {
	    if(!empty($this->data['wiedereinleitung_bearbeiter']))
	    {
	        if($this->data['wiedereinleitung_bearbeiter'] === 't')
	        {
	            return true;
	        }
	        elseif($this->data['wiedereinleitung_bearbeiter'] === 'f')
	        {
	            return false;
	        }
	    }
	    return null;
	}
	
	public function getBefreiungstatbestaende() {
	    if(!empty($this->data['befreiungstatbestaende']))
	    {
	        if($this->data['befreiungstatbestaende'] === 't')
	        {
	            return true;
	        }
	        elseif($this->data['befreiungstatbestaende'] === 'f')
	        {
	            return false;
	        }
	    }
	    return null;
	}
	
	public function getUmfang() {
	    return $this->data['umfang'];
	}
	
	public function getUmfangHTML() 
	{
	    if(!empty($this->getUmfang()))
	    {
	        return number_format($this->getUmfang(), 0, '', ' ')  . " m³/a";
	    }
	    
	    return "";
	}
	
	public function getBerechneterEntgeltsatzZugelassen() {
	    return $this->data['berechneter_entgeltsatz_zugelassen'];
	}
	
	public function getBerechneterEntgeltsatzNichtZugelassen() {
	    return $this->data['berechneter_entgeltsatz_nicht_zugelassen'];
	}
	
	public function getBerechnetesEntgeltZugelassen() {
	    return $this->data['berechnetes_entgelt_zugelassen'];
	}
	
	public function getBerechnetesEntgeltNichtZugelassen() {
	    return $this->data['berechnetes_entgelt_nicht_zugelassen'];
	}
	
	public function getEntgeltsatz($artBenutzungId, $befreit, $zugelassen, $ermaessigt)
	{
	    $this->log->log_info('*** Teilgewaesserbenutzungen->getEntgeltsatz ***');
	    
	    $this->log->log_debug('artBenutzungId: ' . var_export($artBenutzungId, true));
	    $this->log->log_debug('befreit: ' . var_export($befreit, true));
	    $this->log->log_debug('zugelassen: ' . var_export($zugelassen, true));
	    $this->log->log_debug('ermaessigt: ' . var_export($ermaessigt, true));
	    
	    if(!empty($this->entgeltsatz))
	    {
// 	        echo "Entgeltsatz not null";
	        
	        if(!empty($artBenutzungId))
	        {
	            if($artBenutzungId === "1") //GW
	            {
	                if($befreit)
	                {
	                    return $this->entgeltsatz->getSatzGW_Befreit();
	                }
	                elseif($zugelassen)
	                {
	                    if($ermaessigt)
	                    {
	                        return $this->entgeltsatz->getSatzGW_ZugelassenErmaessigt();
	                    }
	                    else
	                    {
	                        return $this->entgeltsatz->getSatzGW_Zugelassen();
	                    }
	                }
	                else
	                {
	                    return $this->entgeltsatz->getSatzGW_NichtZugelassen();
	                }
	            }
	            elseif ($artBenutzungId === "2") //OW
	            {
	                if($befreit)
	                {
	                    return $this->entgeltsatz->getSatzOW_Befreit();
	                }
	                elseif($zugelassen)
	                {
	                    if($ermaessigt)
	                    {
	                        return $this->entgeltsatz->getSatzOW_ZugelassenErmaessigt();
	                    }
	                    else
	                    {
	                        return $this->entgeltsatz->getSatzOW_Zugelassen();
	                    }
	                }
	                else
	                {
	                    return $this->entgeltsatz->getSatzOW_NichtZugelassen();
	                }
	            }
	        }
	    }
// 	    else
// 	    {
// 	        echo "Entgeltsatz is null";
// 	    }
	    
	    return "<div style=\"color: red;\">Fehler</div>";
	}
	
	public function getEntgelt($umfang, $artBenutzungId, $befreit, $zugelassen, $ermaessigt)
	{
	    $entgeltsatz = $this->getEntgeltsatz($artBenutzungId, $befreit, $zugelassen, $ermaessigt);
	    if(is_numeric($entgeltsatz))
	    {
	        return $umfang * $entgeltsatz;
	    }
	    
	    return $entgeltsatz;
	}
	
	public function getFreitext() {
	    return $this->data['freitext'];
	}
	
	public function createTeilgewaesserbenutzung_Nutzer($gewaesserbenutzungen, $erhebungsjahr, $art = NULL, $zweck = NULL, $umfang = NULL, $wiedereinleitung_nutzer = NULL, $mengenbestimmung = NULL, $teilgewaesserbenutzungen_art = NULL, $entgeltsatz = NULL) 
	{
	    if (!empty($gewaesserbenutzungen) && !empty($erhebungsjahr))
	    {
	        $teilgewaesserbenutzung_value_array = array
	        (
	            'gewaesserbenutzungen' => $gewaesserbenutzungen,
	            'erhebungsjahr' => $erhebungsjahr
	        );
	        
	        $this->addToArray($teilgewaesserbenutzung_value_array, 'art', $art);
	        $this->addToArray($teilgewaesserbenutzung_value_array, 'zweck', $zweck);
	        $this->addToArray($teilgewaesserbenutzung_value_array, 'umfang', $umfang);
	        $this->addToArray($teilgewaesserbenutzung_value_array, 'wiedereinleitung_nutzer', $wiedereinleitung_nutzer);
	        $this->addToArray($teilgewaesserbenutzung_value_array, 'mengenbestimmung', $mengenbestimmung);
	        $this->addToArray($teilgewaesserbenutzung_value_array, 'teilgewaesserbenutzungen_art', $teilgewaesserbenutzungen_art);
	        $this->addToArray($teilgewaesserbenutzung_value_array, 'entgeltsatz', $entgeltsatz);
	        
// 	        print_r($teilgewaesserbenutzung_value_array);
	        $this->log->log_debug('teilgewaesserbenutzung_value_array: ' . var_export($teilgewaesserbenutzung_value_array, true));
	        
	        return $this->create(
	               $teilgewaesserbenutzung_value_array
	            );
	    }
	}
	
// 	public function createTeilgewaesserbenutzung_Bearbeiter($gewaesserbenutzungen, $art_benutzung = NULL, $wiedereinleitung_bearbeiter = NULL, $befreiungstatbestaende = NULL)
// 	{
// 	    if (!empty($gewaesserbenutzungen))
// 	    {
// 	        $teilgewaesserbenutzung_value_array = array
// 	        (
// 	            'gewaesserbenutzungen' => $gewaesserbenutzungen
// 	        );
	        
// 	        $this->addToArray($teilgewaesserbenutzung_value_array, 'art_benutzung', $art_benutzung);
// 	        $this->addToArray($teilgewaesserbenutzung_value_array, 'wiedereinleitung_bearbeiter', $wiedereinleitung_bearbeiter);
// 	        $this->addToArray($teilgewaesserbenutzung_value_array, 'befreiungstatbestaende', $befreiungstatbestaende);
	        
// // 	        print_r($teilgewaesserbenutzung_value_array);
// 	        $this->log->log_debug('teilgewaesserbenutzung_value_array: ' . var_export($teilgewaesserbenutzung_value_array, true));
	        
// 	        return $this->create(
// 	            $teilgewaesserbenutzung_value_array
// 	            );
// 	    }
// 	}
	
    public function updateTeilgewaesserbenutzung_Nutzer($erhebungsjahr = NULL, $art = NULL, $zweck = NULL, $umfang = NULL, $wiedereinleitung_nutzer = NULL, $mengenbestimmung = NULL, $teilgewaesserbenutzungen_art = NULL, $entgeltsatz = NULL) 
	{
	    $this->updateData('erhebungsjahr', $erhebungsjahr);
	    $this->updateData('art', $art);
	    $this->updateData('zweck', $zweck);
	    $this->updateData('umfang', $umfang);
	    $this->updateData('wiedereinleitung_nutzer', $wiedereinleitung_nutzer);
	    $this->updateData('mengenbestimmung', $mengenbestimmung);
	    $this->updateData('entgeltsatz', $entgeltsatz);
	    $this->updateData('teilgewaesserbenutzungen_art', $teilgewaesserbenutzungen_art);
	    
	    $this->log->log_debug('kvp update: ' . var_export($this->getKVP(), true));
	    
	    $this->update();
	    
	    return $this->getId();
	}
	
	public function updateTeilgewaesserbenutzung_Bearbeiter($erhebungsjahr = NULL, $art_benutzung = NULL, $wiedereinleitung_bearbeiter = NULL, $befreiungstatbestaende = NULL, $freitext = NULL, $berechneter_entgeltsatz_zugelassen = NULL,  $berechneter_entgeltsatz_nicht_zugelassen = NULL, $berechnetes_entgelt_zugelassen = NULL, $berechnetes_entgelt_nicht_zugelassen = NULL)
	{
	    $this->updateData('erhebungsjahr', $erhebungsjahr);
	    $this->updateData('art_benutzung', $art_benutzung);
	    $this->updateData('wiedereinleitung_bearbeiter', $wiedereinleitung_bearbeiter);
	    $this->updateData('befreiungstatbestaende', $befreiungstatbestaende);
	    $this->updateData('freitext', $freitext);
	    $this->updateData('berechneter_entgeltsatz_zugelassen', $berechneter_entgeltsatz_zugelassen);
	    $this->updateData('berechneter_entgeltsatz_nicht_zugelassen', $berechneter_entgeltsatz_nicht_zugelassen);
	    $this->updateData('berechnetes_entgelt_zugelassen', $berechnetes_entgelt_zugelassen);
	    $this->updateData('berechnetes_entgelt_nicht_zugelassen', $berechnetes_entgelt_nicht_zugelassen);
	    
	    $this->log->log_debug('kvp update: ' . var_export($this->getKVP(), true));
	    
	    $this->update();
	    
	    return $this->getId();
	}
}
?>