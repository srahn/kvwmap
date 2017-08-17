<?php
class Teilgewaesserbenutzungen extends WrPgObject {

	protected $tableName = 'teilgewaesserbenutzungen';
	
	public $gewaesserbenutzungArt;
	public $gewaesserbenutzungZweck;
	public $gewaesserbenutzungUmfang;
	public $mengenbestimmung;
	public $art_benutzung;
	public $entgeltsatz;
	public $teilgewaesserbenutzungen_art;
	public $gewaesserbenutzungen;

	public function find_where_with_subtables($where, $order = NULL, $select = '*')
	{
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
	                
	                $gwu = new GewaesserbenutzungenUmfang($this->gui);
	                if(!empty($teilgewaesserbenutzung->data['umfang']))
	                {
	                    // 	                echo 'id=' . $teilgewaesserbenutzung->data['umfang'];
	                    $gewaesserbenutzungUmfang = $gwu->find_where('id=' . $teilgewaesserbenutzung->data['umfang']);
	                    if(!empty($gewaesserbenutzungUmfang))
	                    {
	                        $teilgewaesserbenutzung->gewaesserbenutzungUmfang = $gewaesserbenutzungUmfang[0];
	                    }
	                }
	                
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
	
	public function getWiedereinleitungNutzer() {
	    return $this->data['wiedereinleitung_nutzer'];
	}
	
	public function getWiedereinleitungBearbeiter() {
	    return $this->data['wiedereinleitung_bearbeiter'];
	}
	
	public function getBefreiungstatbestaende() {
	    return $this->data['befreiungstatbestaende'];
	}
	
	public function createTeilgewaesserbenutzung($gewaesserbenutzungen, $art = NULL, $zweck = NULL, $umfang = NULL, $wiedereinleitung_nutzer = NULL, $wiedereinleitung_bearbeiter = NULL, $mengenbestimmung = NULL, $art_benutzung = NULL, $befreiungstatbestaende = NULL, $entgeltsatz = NULL, $teilgewaesserbenutzungen_art = NULL) 
	{
	    if (!empty($gewaesserbenutzungen))
	    {
	        $teilgewaesserbenutzung_value_array = array
	        (
	            'gewaesserbenutzungen' => $gewaesserbenutzungen
	        );
	        
	        addToArray($teilgewaesserbenutzung_value_array, 'art', $art);
	        addToArray($teilgewaesserbenutzung_value_array, 'zweck', $zweck);
	        addToArray($teilgewaesserbenutzung_value_array, 'umfang', $umfang);
	        addToArray($teilgewaesserbenutzung_value_array, 'wiedereinleitung_nutzer', $wiedereinleitung_nutzer);
	        addToArray($teilgewaesserbenutzung_value_array, 'wiedereinleitung_bearbeiter', $wiedereinleitung_bearbeiter);
	        addToArray($teilgewaesserbenutzung_value_array, 'mengenbestimmung', $mengenbestimmung);
	        addToArray($teilgewaesserbenutzung_value_array, 'art_benutzung', $art_benutzung);
	        addToArray($teilgewaesserbenutzung_value_array, 'befreiungstatbestaende', $befreiungstatbestaende);
	        addToArray($teilgewaesserbenutzung_value_array, 'entgeltsatz', $entgeltsatz);
	        addToArray($teilgewaesserbenutzung_value_array, 'teilgewaesserbenutzungen_art', $teilgewaesserbenutzungen_art);
	        
	        return $this->create(
	               $teilgewaesserbenutzung_value_array
	            );
	    }
	}
}
?>