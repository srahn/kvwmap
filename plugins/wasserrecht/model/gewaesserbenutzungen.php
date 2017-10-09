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
	    $this->debug->write('*** Gewaesserbenutzungen->getTeilgewaesserbenutzungNichtZugelasseneMenge ***', 4);
	    
	    $this->debug->write('teilgewaesserbenutzungId: ' . var_export($teilgewaesserbenutzungId, true), 4);
	    $this->debug->write('zugelassenerUmfang: ' . var_export($zugelassenerUmfang, true), 4);
	    
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
	    $this->debug->write('*** Gewaesserbenutzungen->getTeilgewaesserbenutzungEntgeltsatz ***', 4);
	    
	    $this->debug->write('getArtBenutzung: ' . var_export($getArtBenutzung, true), 4);
	    $this->debug->write('getBefreiungstatbestaende: ' . var_export($getBefreiungstatbestaende, true), 4);
	    $this->debug->write('getWiedereinleitungBearbeiter: ' . var_export($getWiedereinleitungBearbeiter, true), 4);
	    $this->debug->write('zugelassenesEntnahmeEntgelt: ' . var_export($zugelassenesEntnahmeEntgelt, true), 4);
	    $this->debug->write('nichtZugelassenesEntnahmeEntgelt: ' . var_export($nichtZugelassenesEntnahmeEntgelt, true), 4);
	    $this->debug->write('zugelassenerUmfang: ' . var_export($zugelassenerUmfang, true), 4);
	    
	    if(!empty($teilgewaesserbenutzung))
	    {
	        $teilbenutzungNichtZugelasseneMenge = $this->getTeilgewaesserbenutzungNichtZugelasseneMenge($teilgewaesserbenutzung->getId(), $zugelassenerUmfang);
// 	        echo "teilbenutzungNichtZugelasseneMenge: " . $teilbenutzungNichtZugelasseneMenge . " <br>";

	        if($teilbenutzungNichtZugelasseneMenge > 0)
	        {
	            if($teilbenutzungNichtZugelasseneMenge === $teilgewaesserbenutzung->getUmfang())
	            {
	                $entgeltsatz_nicht_zugelassen = $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $returnArray = array(null, $entgeltsatz_nicht_zugelassen);
	                $this->debug->write('returnArray: ' . var_export($returnArray, true), 4);
	                return $returnArray;
	            }
	            else
	            {
	                $entgeltsatz_zugelassen = $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	                $entgeltsatz_nicht_zugelassen = $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $returnArray = array($entgeltsatz_zugelassen, $entgeltsatz_nicht_zugelassen);
	                $this->debug->write('returnArray: ' . var_export($returnArray, true), 4);
	                return $returnArray;
	            }
	        }
	        else
	        {
	            $entgeltsatz_zugelassen = $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	            $returnArray = array($entgeltsatz_zugelassen);
	            $this->debug->write('returnArray: ' . var_export($returnArray, true), 4);
	            return $returnArray;
	        }
	    }
	    
	    $returnArray = array(null, null, "Error");
	    $this->debug->write('returnArray: ' . var_export($returnArray, true), 4);
	    return $returnArray;
	}
	
	public function getTeilgewaesserbenutzungEntgelt($teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, &$zugelassenesEntnahmeEntgelt, &$nichtZugelassenesEntnahmeEntgelt, &$zugelassenerUmfang)
	{
	    $this->debug->write('*** Gewaesserbenutzungen->getTeilgewaesserbenutzungEntgelt ***', 4);
	    
	    $this->debug->write('getArtBenutzung: ' . var_export($getArtBenutzung, true), 4);
	    $this->debug->write('getBefreiungstatbestaende: ' . var_export($getBefreiungstatbestaende, true), 4);
	    $this->debug->write('getWiedereinleitungBearbeiter: ' . var_export($getWiedereinleitungBearbeiter, true), 4);
	    $this->debug->write('zugelassenesEntnahmeEntgelt: ' . var_export($zugelassenesEntnahmeEntgelt, true), 4);
	    $this->debug->write('nichtZugelassenesEntnahmeEntgelt: ' . var_export($nichtZugelassenesEntnahmeEntgelt, true), 4);
	    $this->debug->write('zugelassenerUmfang: ' . var_export($zugelassenerUmfang, true), 4);
	    
	    if(!empty($teilgewaesserbenutzung))
	    {
	        $teilbenutzungNichtZugelasseneMenge = $this->getTeilgewaesserbenutzungNichtZugelasseneMenge($teilgewaesserbenutzung->getId(), $zugelassenerUmfang);
	        $this->debug->write('teilbenutzungNichtZugelasseneMenge: ' . var_export($teilbenutzungNichtZugelasseneMenge, true), 4);
	        
	        if($teilbenutzungNichtZugelasseneMenge > 0)
	        {
	            if($teilbenutzungNichtZugelasseneMenge === $teilgewaesserbenutzung->getUmfang())
	            {
	                $entnahmeEntgeltNichtErlaubt = $teilgewaesserbenutzung->getEntgelt($teilgewaesserbenutzung->getUmfang(), $getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $nichtZugelassenesEntnahmeEntgelt = $nichtZugelassenesEntnahmeEntgelt + $entnahmeEntgeltNichtErlaubt;
	                
	                $returnArray = array(null, $entnahmeEntgeltNichtErlaubt);
	                $this->debug->write('returnArray: ' . var_export($returnArray, true), 4);
	                return $returnArray;
	            }
	            else
	            {
	                $entnahmeEntgeltNichtErlaubt = $teilgewaesserbenutzung->getEntgelt($teilbenutzungNichtZugelasseneMenge, $getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $nichtZugelassenesEntnahmeEntgelt =  $nichtZugelassenesEntnahmeEntgelt + $entnahmeEntgeltNichtErlaubt;
	                
	                $entnahmeEntgeltErlaubt = $teilgewaesserbenutzung->getEntgelt($teilgewaesserbenutzung->getUmfang() - $teilbenutzungNichtZugelasseneMenge, $getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	                $zugelassenesEntnahmeEntgelt = $zugelassenesEntnahmeEntgelt + $entnahmeEntgeltErlaubt;
	                
	                $returnArray = array($entnahmeEntgeltErlaubt, $entnahmeEntgeltNichtErlaubt);
	                $this->debug->write('returnArray: ' . var_export($returnArray, true), 4);
	                return $returnArray;
	            }
	        }
	        else
	        {
	            $entnahmeEntgeltErlaubt = $teilgewaesserbenutzung->getEntgelt($teilgewaesserbenutzung->getUmfang(), $getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	            $zugelassenesEntnahmeEntgelt = $zugelassenesEntnahmeEntgelt + $entnahmeEntgeltErlaubt;
	            
	            $returnArray = array($entnahmeEntgeltErlaubt);
	            $this->debug->write('returnArray: ' . var_export($returnArray, true), 4);
	            return $returnArray;
	        }
	    }
	    
	    $returnArray = array(null, null, "Error");
	    $this->debug->write('returnArray: ' . var_export($returnArray, true), 4);
	    return $returnArray;
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
	    $sql = "SELECT " . $fieldname ." FROM " . $this->schema . '.' . $this->tableName . "_bezeichnung WHERE id = $1;";
	    // 	    echo "sql: " . $sql;
	    $bezeichnung = $this->getSQLResult($sql, array($this->getId()), $fieldname);
	    // 	    echo "bezeichnung: " . $bezeichnung;
	    if(!empty($bezeichnung) && count($bezeichnung) > 0 && !empty($bezeichnung[0]))
	    {
	        return $bezeichnung[0];
	    }
	    
	    return null;
	}
}
?>