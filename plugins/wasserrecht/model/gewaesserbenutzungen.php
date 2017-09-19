<?php
class Gewaesserbenutzungen extends WrPgObject {

	protected $tableName = 'fiswrv_gewaesserbenutzungen';
	
	public $gewaesserbenutzungUmfang;
	public $gewaesserbenutzungArt;
	public $gewaesserbenutzungZweck;
	public $teilgewaesserbenutzungen;

	public function find_where_with_subtables($where, $order = NULL, $select = '*') {
// 	    $this->debug->write('find_where_with_subtables: ' . $where, 4);
// 	    echo "<br />find_where_with_subtables: " . $where;
	    $gewaesserbenutzungen = $this->find_where($where, $order, $select);
	    if(!empty($gewaesserbenutzungen))
	    {
	        foreach ($gewaesserbenutzungen AS $gewaesserbenutzung)
	        {
	            if(!empty($gewaesserbenutzung))
	            {
	                $gwu = new GewaesserbenutzungenUmfang($this->gui);
	                if(!empty($gewaesserbenutzung->data['umfang_entnahme']))
	                {
	                    //echo 'id=' . $gewaesserbenutzung->data['umfang'];
	                    $gewaesserbenutzungUmfang = $gwu->find_where('id=' . $gewaesserbenutzung->data['umfang_entnahme']);
	                    if(!empty($gewaesserbenutzungUmfang))
	                    {
	                        $gewaesserbenutzung->gewaesserbenutzungUmfang = $gewaesserbenutzungUmfang[0];
	                    }
	                }
	                
	                $gwa = new GewaesserbenutzungenArt($this->gui);
	                if(!empty($gewaesserbenutzung->data['art']))
	                {
	                    $gewaesserbenutzungArt = $gwa->find_where('id=' . $gewaesserbenutzung->data['art']);
	                    if(!empty($gewaesserbenutzungArt))
	                    {
	                        $gewaesserbenutzung->gewaesserbenutzungArt = $gewaesserbenutzungArt[0];
	                    }
	                }
	                
	                $gwz = new GewaesserbenutzungenZweck($this->gui);
	                if(!empty($gewaesserbenutzung->data['zweck']))
	                {
	                    $gewaesserbenutzungZweck = $gwz->find_where('id=' . $gewaesserbenutzung->data['zweck']);
	                    if(!empty($gewaesserbenutzungZweck))
	                    {
	                        $gewaesserbenutzung->gewaesserbenutzungZweck = $gewaesserbenutzungZweck[0];
	                    }
	                }
	                
	                $teilgewaesserbenutzung = new Teilgewaesserbenutzungen($this->gui);
	                $teilgewaesserbenutzungen = $teilgewaesserbenutzung->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	                $gewaesserbenutzung->teilgewaesserbenutzungen = $teilgewaesserbenutzungen;
	                
// 	                echo "<br />gewaesserbenutzung: " . $gewaesserbenutzung->getKennummer();
	            }
	        }
	        
	        return $gewaesserbenutzungen;
	    }
	    
	    return null;
	}
	
	public function getUmfangAllerTeilbenutzungen()
	{
	    $gesamtUmfang = 0;
	    
	    for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++)
	    {
	        $teilgewaesserbenutzung = null;
	        if(!empty($this->teilgewaesserbenutzungen) && count($this->teilgewaesserbenutzungen) > 0
	            && count($this->teilgewaesserbenutzungen) > ($i - 1) && !empty($this->teilgewaesserbenutzungen[$i - 1]))
	        {
	            $teilgewaesserbenutzung = $this->teilgewaesserbenutzungen[$i - 1];
	            
	            if(!empty($teilgewaesserbenutzung))
	            {
	                $gesamtUmfang = $gesamtUmfang + $teilgewaesserbenutzung->getUmfang();
	            }
	        }
	    }
	    
	    return $gesamtUmfang;
	}
	
	public function getZugelassenerUmfang()
	{
	    if(!empty($this->gewaesserbenutzungUmfang) && !empty($this->gewaesserbenutzungUmfang->getErlaubterUmfang()))
	    {
	        $zugelassenerUmfang = $this->gewaesserbenutzungUmfang->getErlaubterUmfang();
	        return $zugelassenerUmfang;
	        // 	    echo "zugelassenerUmfang: " . $zugelassenerUmfang . "<br/>";
	    }
	    
	    return null;
	}
	
	public function getTeilgewaesserbenutzungNichtZugelasseneMenge($teilgewaesserbenutzungId, &$zugelassenerUmfang)
	{
// 	    echo "teilgewaesserbenutzungId: " . $teilgewaesserbenutzungId . "<br/>";
// 	    echo "zugelassenerUmfang: " . $zugelassenerUmfang . "<br/>";
	    
	    if(!empty($teilgewaesserbenutzungId))
	    {
	        $gesamtUmfang = 0;
	        $bisZuDieserTeilgewaesserbenutzungKumulierterUmfang = 0;
	        for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++)
	        {
	            $teilgewaesserbenutzung = null;
	            if(!empty($this->teilgewaesserbenutzungen) && count($this->teilgewaesserbenutzungen) > 0
	                && count($this->teilgewaesserbenutzungen) > ($i - 1) && !empty($this->teilgewaesserbenutzungen[$i - 1]))
	            {
	                $teilgewaesserbenutzung = $this->teilgewaesserbenutzungen[$i - 1];
	                
	                if(!empty($teilgewaesserbenutzung))
	                {
// 	                    echo "teilgewaesserbenutzung->getId(): " . $teilgewaesserbenutzung->getId() . "<br/>";
// 	                    if($teilgewaesserbenutzung->getId() === $teilgewaesserbenutzungId)
// 	                    {
// 	                        $bisZuDieserTeilgewaesserbenutzungKumulierterUmfang = $gesamtUmfang + $teilgewaesserbenutzung->getUmfang();
// 	                    }
	                    
	                    $gesamtUmfang = $gesamtUmfang + $teilgewaesserbenutzung->getUmfang();
	                }
	            }
	        }
// 	        echo "gesamtUmfang: " . $gesamtUmfang . "<br/>";
	        
	        for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++)
	        {
	            $teilgewaesserbenutzung = null;
	            if(!empty($this->teilgewaesserbenutzungen) && count($this->teilgewaesserbenutzungen) > 0
	                && count($this->teilgewaesserbenutzungen) > ($i - 1) && !empty($this->teilgewaesserbenutzungen[$i - 1]))
	            {
	                $teilgewaesserbenutzung = $this->teilgewaesserbenutzungen[$i - 1];
	                
	                if(!empty($teilgewaesserbenutzung))
	                {
	                    $teilgewaesserbenutzungUmfang = $teilgewaesserbenutzung->getUmfang();
	                    
	                    if($teilgewaesserbenutzung->getId() === $teilgewaesserbenutzungId)
	                    {
	                        if($gesamtUmfang <= $zugelassenerUmfang)
	                        {
// 	                            echo "gesamtUmfang <= zugelassenerUmfang <br>";
	                            return 0;
	                        }
	                        elseif ($zugelassenerUmfang === 0)
	                        {
// 	                            echo "zugelassenerUmfang === 0";
	                            return $teilgewaesserbenutzungUmfang;
	                        }
// 	                        elseif($bisZuDieserTeilgewaesserbenutzungKumulierterUmfang <= $zugelassenerUmfang)
// 	                        {
// 	                            return 0;
// 	                        }
	                        elseif($teilgewaesserbenutzungUmfang <= $zugelassenerUmfang)
	                        {
// 	                            echo "teilgewaesserbenutzungUmfang <= zugelassenerUmfang <br>";
	                            $zugelassenerUmfang = $zugelassenerUmfang - $teilgewaesserbenutzungUmfang;
	                            return 0;
	                        }
	                        elseif($teilgewaesserbenutzungUmfang > $zugelassenerUmfang)
	                        {
// 	                            echo "teilgewaesserbenutzungUmfang > zugelassenerUmfang <br>";
	                            $returnValue = $teilgewaesserbenutzungUmfang - $zugelassenerUmfang;
	                            $zugelassenerUmfang = 0;
// 	                            echo "returnValue :" . $returnValue;
	                            return $returnValue;
	                        }
	                    }
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	public function getTeilgewaesserbenutzungEntgeltsatz($teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, &$zugelassenesEntnahmeEntgelt, &$nichtZugelassenesEntnahmeEntgelt, &$zugelassenerUmfang)
	{
// 	    $this->debug->write('getTeilgewaesserbenutzungEntgeltsatz getArtBenutzung:' . var_export($getArtBenutzung, true), 4);
// 	    $this->debug->write('getTeilgewaesserbenutzungEntgeltsatz getBefreiungstatbestaende:' . var_export($getBefreiungstatbestaende, true), 4);
// 	    $this->debug->write('getTeilgewaesserbenutzungEntgeltsatz getWiedereinleitungBearbeiter:' . var_export($getWiedereinleitungBearbeiter, true), 4);
// 	    $this->debug->write('getTeilgewaesserbenutzungEntgeltsatz zugelassenesEntnahmeEntgelt:' . var_export($zugelassenesEntnahmeEntgelt, true), 4);
// 	    $this->debug->write('getTeilgewaesserbenutzungEntgeltsatz nichtZugelassenesEntnahmeEntgelt:' . var_export($nichtZugelassenesEntnahmeEntgelt, true), 4);
// 	    $this->debug->write('getTeilgewaesserbenutzungEntgeltsatz zugelassenerUmfang:' . var_export($zugelassenerUmfang, true), 4);
	    
	    if(!empty($teilgewaesserbenutzung))
	    {
	        $teilbenutzungNichtZugelasseneMenge = $this->getTeilgewaesserbenutzungNichtZugelasseneMenge($teilgewaesserbenutzung->getId(), $zugelassenerUmfang);
// 	        echo "teilbenutzungNichtZugelasseneMenge: " . $teilbenutzungNichtZugelasseneMenge . " <br>";

	        if($teilbenutzungNichtZugelasseneMenge > 0)
	        {
	            if($teilbenutzungNichtZugelasseneMenge === $teilgewaesserbenutzung->getUmfang())
	            {
	                return $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	            }
	            else
	            {
	                return $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter) . " (zugelassener Umfang)<br />" . $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter) . " (nicht zugelassener Umfang)";
	            }
	        }
	        else
	        {
	            return $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	        }
	    }
	    
	    return "Error";
	}
	
	public function getTeilgewaesserbenutzungEntgelt($teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, &$zugelassenesEntnahmeEntgelt, &$nichtZugelassenesEntnahmeEntgelt, &$zugelassenerUmfang)
	{
	    if(!empty($teilgewaesserbenutzung))
	    {
	        $teilbenutzungNichtZugelasseneMenge = $this->getTeilgewaesserbenutzungNichtZugelasseneMenge($teilgewaesserbenutzung->getId(), $zugelassenerUmfang);
// 	        echo "teilbenutzungNichtZugelasseneMenge: " . $teilbenutzungNichtZugelasseneMenge . "<br/>";
	        if($teilbenutzungNichtZugelasseneMenge > 0)
	        {
	            if($teilbenutzungNichtZugelasseneMenge === $teilgewaesserbenutzung->getUmfang())
	            {
	                $entnahmeEntgeltNichtErlaubt = $teilgewaesserbenutzung->getEntgelt($teilgewaesserbenutzung->getUmfang(), $getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $nichtZugelassenesEntnahmeEntgelt = $nichtZugelassenesEntnahmeEntgelt + $entnahmeEntgeltNichtErlaubt;
	                
	                return $entnahmeEntgeltNichtErlaubt;
	            }
	            else
	            {
	                $entnahmeEntgeltNichtErlaubt = $teilgewaesserbenutzung->getEntgelt($teilbenutzungNichtZugelasseneMenge, $getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $nichtZugelassenesEntnahmeEntgelt =  $nichtZugelassenesEntnahmeEntgelt + $entnahmeEntgeltNichtErlaubt;
	                
	                $entnahmeEntgeltErlaubt = $teilgewaesserbenutzung->getEntgelt($this->gewaesserbenutzungUmfang->getErlaubterUmfang(), $getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	                $zugelassenesEntnahmeEntgelt = $zugelassenesEntnahmeEntgelt + $entnahmeEntgeltErlaubt;
	                
	                return $entnahmeEntgeltErlaubt + $entnahmeEntgeltNichtErlaubt;
	            }
	        }
	        else
	        {
	            $entnahmeEntgeltErlaubt = $teilgewaesserbenutzung->getEntgelt($teilgewaesserbenutzung->getUmfang(), $getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	            $zugelassenesEntnahmeEntgelt = $zugelassenesEntnahmeEntgelt + $entnahmeEntgeltErlaubt;
	            
	            return $entnahmeEntgeltErlaubt;
	        }
	    }
	    
	    return "Error";
	}
	
	public function getEntnahmemenge($zugelassen)
	{
	    $gesamtUmfang = $this->getUmfangAllerTeilbenutzungen();
	    
	    $zugelassenerUmfang = 0;
	    if(!empty($this->gewaesserbenutzungUmfang) && !empty($this->gewaesserbenutzungUmfang->getErlaubterUmfang()))
	    {
	        $zugelassenerUmfang = $this->gewaesserbenutzungUmfang->getErlaubterUmfang();
	    }
	    
	    if(!empty($gesamtUmfang) && !empty($zugelassenerUmfang))
	    {
	        if($zugelassen)
	        {
	            if($gesamtUmfang > $zugelassenerUmfang)
	            {
	                return $zugelassenerUmfang;
	            }
	            else
	            {
	                return $gesamtUmfang;
	            }
	        }
	        else
	        {
	            if($gesamtUmfang > $zugelassenerUmfang)
	            {
	                return $gesamtUmfang - $zugelassenerUmfang;
	            }
	            else
	            {
	                return 0;
	            }
	        }
	    }
	   
	    return null;
	}
	
	public function getKennummer() {
	    return $this->data['kennnummer'];
	}
	
	public function getBezeichnung() {
	    $fieldname = 'bezeichnung';
// 	    $sql = "SELECT COALESCE(e.name,'') ||' (Aktenzeichen: '|| COALESCE(a.aktenzeichen,'') ||')'||' vom '|| COALESCE(a.datum_postausgang::text,'') || ' zum ' || COALESCE(c.name,'') || ' von ' || COALESCE(d.max_ent_a::text,'') || ' m³/Jahr' AS " . $fieldname ." FROM " . $this->schema . '.' . "wasserrechtliche_zulassungen a LEFT JOIN " . $this->schema . '.' . $this->tableName . " b ON b.wasserrechtliche_zulassungen = a.id LEFT JOIN " . $this->schema . '.' . "gewaesserbenutzungen_art c ON c.id = b.art LEFT JOIN " . $this->schema . '.' . "gewaesserbenutzungen_umfang_entnahme d ON b.umfang = d.id LEFT JOIN " . $this->schema . '.' . "wasserrechtliche_zulassungen_ausgangsbescheide_klasse e ON a.klasse = e.id WHERE b.id = '" . $this->getId() . "';";
	    $sql = "SELECT " . $fieldname ." FROM " . $this->schema . '.' . $this->tableName . "_bezeichnung WHERE id = '" . $this->getId() . "';";
	    // 	    echo "sql: " . $sql;
	    $bezeichnung = $this->getSQLResult($sql, $fieldname);
	    // 	    echo "bezeichnung: " . $bezeichnung;
	    if(!empty($bezeichnung) && count($bezeichnung) > 0 && !empty($bezeichnung[0]))
	    {
	        return $bezeichnung[0];
	    }
	    
	    return null;
	}
}
?>