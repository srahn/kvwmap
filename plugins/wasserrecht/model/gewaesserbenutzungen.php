<?php
class Gewaesserbenutzungen extends WrPgObject {

	protected $tableName = 'fiswrv_gewaesserbenutzungen';
	
	public $gewaesserbenutzungUmfang;
	public $gewaesserbenutzungArt;
	public $gewaesserbenutzungZweck;
	public $teilgewaesserbenutzungen;
	public $aufforderungen;
	public $erklaerungen;
	public $festsetzungen;

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
	                
	                //get the Aufforderungen
	                Gewaesserbenutzungen::getAufforderungen($this->gui, $gewaesserbenutzung);
	                
	                //get the Erklaerungen
	                Gewaesserbenutzungen::getErklaerungen($this->gui, $gewaesserbenutzung);
	                
	                //get the Festsetzungen
	                Gewaesserbenutzungen::getFestsetzungen($this->gui, $gewaesserbenutzung);
	                
// 	                echo "<br />gewaesserbenutzung: " . $gewaesserbenutzung->getKennummer();
	            }
	        }
	        
	        return $gewaesserbenutzungen;
	    }
	    
	    return null;
	}
	
	/**
	 * get the Aufforderungen
	 * @param $gewaesserbenutzung
	 */
	public static function getAufforderungen(&$gui, &$gewaesserbenutzung)
	{
	    $aufforderung = new Aufforderung($gui);
	    $aufforderungen = $aufforderung->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	    $gewaesserbenutzung->aufforderungen = $aufforderungen;
	}
	
	/**
	 * get the Erklaerungen
	 * @param $gui
	 * @param $gewaesserbenutzung
	 */
	public static function getErklaerungen(&$gui, &$gewaesserbenutzung)
	{
	    $erklaerung = new Erklaerung($gui);
	    $erklaerungen = $erklaerung->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	    $gewaesserbenutzung->erklaerungen = $erklaerungen;
	}
	
	/**
	 * get the Festsetzungen
	 * @param $gui
	 * @param $gewaesserbenutzung
	 */
	public static function getFestsetzungen(&$gui, &$gewaesserbenutzung)
	{
	    $festsetzung = new Festsetzung($gui);
	    $festsetzungen = $festsetzung->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	    $gewaesserbenutzung->festsetzungen = $festsetzungen;
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
	
	////////////////////////////////////////////////////////////////////
	
	public function getAufforderungDatum($erhebungsjahr = NULL) {
	    $aufforderung = $this->getAufforderungForErhebungsjahr($erhebungsjahr);
	    if(!empty($aufforderung))
	    {
	        return $aufforderung->getDatum();
	    }
	    
	    return null;
	}
	
	public function getAufforderungDatumHTML() {
	    $datumAufforderung = $this->getAufforderungDatum($erhebungsjahr);
	    if(!empty($datumAufforderung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $datumAufforderung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht aufgefordert</div>";
	}
	
	public function isAufforderungFreigegeben($erhebungsjahr = NULL) {
	    $aufforderung = $this->getAufforderungForErhebungsjahr($erhebungsjahr);
	    if(!empty($aufforderung))
	    {
	        return $aufforderung->isFreigegeben();
	    }
	    
	    return false;
	}
	
	public function insertAufforderung($dokumentId, $erhebungsjahr, $dateVale)
	{
	    $this->debug->write('*** insertAufforderung ***', 4);
	    
	    $aufforderungen = $this->aufforderungen;
	    if(!empty($aufforderungen) && !empty($aufforderungen[0]))
	    {
	        $aufforderung = $aufforderungen[0];
	        
	        $this->debug->write('aufforderung mit id: ' . $aufforderung->getId() . ' existiert schon: update', 4);
	        
	        //if date is not set --> set it to today's date
	        if(empty($dateValue))
	        {
	            $dateValue = date("d.m.Y");
	        }
	        
	        $aufforderung_id = $aufforderung->updateAufforderung($erhebungsjahr, $dokumentId, $dateVale, null);
	        Gewaesserbenutzungen::getAufforderungen($this->gui, $this);
	        return $aufforderung_id;
	    }
	    else
	    {
	        $this->debug->write('aufforderung wird neu angelegt', 4);
	        
	        if(!empty($dokumentId))
	        {
	            //if date is not set --> set it to today's date
	            if(empty($dateValue))
	            {
	                $dateValue = date("d.m.Y");
	            }
	            
	            $aufforderung = new Aufforderung($this->gui);
	            $aufforderung_id = $aufforderung->createAufforderung($this->getId(), $erhebungsjahr, $dokumentId, $dateValue, null);
	            
	            Gewaesserbenutzungen::getAufforderungen($this->gui, $this);
	            
	            return $aufforderung_id;
	        }
	    }
	    
	    return null;
	}
	
	public function getAufforderungDokument($erhebungsjahr = NULL) {
	    $aufforderung = $this->getAufforderungForErhebungsjahr($erhebungsjahr);
	    if(!empty($aufforderung))
	    {
	        if(!empty($aufforderung->dokument))
	        {
	            return $aufforderung->dokument;
	        }
	    }
	    
	    return null;
	}
	
	public function getAufforderungForErhebungsjahr($erhebungsjahr = NULL)
	{
// 	    $this->debug->write('*** getAufforderungForErhebungsjahr ***', 4);
	    
	    $aufforderungen = $this->aufforderungen;
	    
	    if(!empty($aufforderungen))
	    {
	        foreach ($aufforderungen as $aufforderung)
	        {
	            if(!empty($aufforderung))
	            {
	                if(!empty($erhebungsjahr) && !empty($aufforderung->erhebungsjahr))
	                {
	                    if($aufforderung->erhebungsjahr === $erhebungsjahr)
	                    {
	                        return $aufforderung;
	                    }
	                }
	                else
	                {
	                    return $aufforderung;
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	////////////////////////////////////////////////////////////////////
	
	public function insertErklaerung($erhebungsjahr, $dateValue, $erklaerungNutzer) {
	    $this->debug->write('*** insertErklaerung ***', 4);
	    
	    $this->debug->write('erhebungsjahr: ' . var_export($erhebungsjahr, true), 4);
	    $this->debug->write('erklaerungNutzer: ' . var_export($erklaerungNutzer, true), 4);
	    $this->debug->write('dateValue: ' . var_export($dateValue, true), 4);
	    
	    $erklaerungen = $this->erklaerungen;
	    if(!empty($erklaerungen))
	    {
	        foreach ($erklaerungen as $erklaerung)
	        {
	            if(!empty($erklaerung))
	            {
	                if($erklaerung->compare($erhebungsjahr))
	                {
	                    $this->debug->write('erklaerung mit id: ' . $erklaerung->getId() . ' existiert schon: update', 4);
	                    
	                    //if date is not set --> set it to today's date
	                    if(empty($dateValue))
	                    {
	                        $dateValue = date("d.m.Y");
	                    }
	                    
	                    $erklaerung_id = $erklaerung->updateErklaerung($erhebungsjahr, $dateVale, $erklaerungNutzer);
	                    Gewaesserbenutzungen::getErklaerungen($this->gui, $this);
	                    return $erklaerung_id;
	                }
	            }
	        }
	    }
	    
	    $this->debug->write('erklaerung wird neu angelegt', 4);
	    
	    //if date is not set --> set it to today's date
	    if(empty($dateValue))
	    {
	        $dateValue = date("d.m.Y");
	    }
	    
	    $erklaerung = new Erklaerung($this->gui);
	    $erklaerung_id = $erklaerung->createErklaerung($this->getId(), $erhebungsjahr, $dateValue, $erklaerungNutzer);
	    
	    Gewaesserbenutzungen::getErklaerungen($this->gui, $this);
	    
	    return $erklaerung_id;
	}
	
	public function isErklaerungFreigegeben($erhebungsjahr) {
	    $erklaerung = $this->getErklaerungForErhebungsjahr($erhebungsjahr);
	    if(!empty($erklaerung))
	    {
	        $datumErklaerung = $erklaerung->getDatum();
	        
	        if(!empty($datumErklaerung))
	        {
	            return true;
	        }
	    }
	    
	    return false;
	}
	
	public function getErklaerungDatum($erhebungsjahr) {
	    $erklaerung = $this->getErklaerungForErhebungsjahr($erhebungsjahr);
	    if(!empty($erklaerung))
	    {
	        return $erklaerung->getDatum();
	    }
	    
	    return null;
	}
	
	public function getErklaerungDatumHTML($erhebungsjahr) {
	    $datumErklaerung = $this->getErklaerungDatum($erhebungsjahr);
	    if(!empty($datumErklaerung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $datumErklaerung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht erklärt</div>";
	}
	
	public function getErklaerungNutzer($erhebungsjahr) {
	    $erklaerung = $this->getErklaerungForErhebungsjahr($erhebungsjahr);
	    if(!empty($erklaerung))
	    {
	        return $erklaerung->getNutzer();
	    }
	    
	    return null;
	}
	
	public function getErklaerungNutzerHTML($erhebungsjahr) {
	    $nutzerErklaerung = $this->getErklaerungNutzer($erhebungsjahr);
	    if(!empty($nutzerErklaerung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $nutzerErklaerung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht erklärt</div>";
	}
	
	public function getErklaerungForErhebungsjahr($erhebungsjahr)
	{
// 	    $this->debug->write('*** getErklaerungForErhebungsjahr ***', 4);
	    
	    if(!empty($erhebungsjahr))
	    {
	        $erklaerungen = $this->erklaerungen;
	        
	        if(!empty($erklaerungen))
	        {
	            foreach ($erklaerungen as $erklaerung)
	            {
	                if(!empty($erklaerung))
	                {
	                    if($erklaerung->getErhebungsjahr() === $erhebungsjahr)
	                    {
	                        return $erklaerung;
	                    }
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	////////////////////////////////////////////////////////////////////
	
	public function deleteFestsetzungDokument($erhebungsjahr) 
	{
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        $festsetzungId = $festsetzung->deleteFestsetzungDokument();
	        Gewaesserbenutzungen::getFestsetzungen($this->gui, $this);
	        return $festsetzungId;
	    }
	}
	
	public function insertFestsetzungWithoutDokument($erhebungsjahr, $datum, $festsetzungNutzer,
	    $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	    $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt) {
	    
        //if date is not set --> set it to today's date
        if(empty($datum))
        {
            $datum = date("d.m.Y");
        }
	    
	    return $this->insertFestsetzung($erhebungsjahr, null, $datum, null, $festsetzungNutzer, 
	        $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen, 
	        $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt);
	}
	
	public function insertFestsetzungDokument($erhebungsjahr, $dokumentId, $dokumentDatum) {
	    
	    //if date is not set --> set it to today's date
	    if(empty($dokumentDatum))
	    {
	        $dokumentDatum = date("d.m.Y");
	    }
	     
	    return $this->insertFestsetzung($erhebungsjahr, $dokumentId, null, $dokumentDatum, null, 
	        null, null, null, 
	        null, null, null);
	}
	
	public function insertFestsetzung($erhebungsjahr, $dokumentId, $datum, $dokumentDatum, $festsetzungNutzer, 
	    $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	    $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt) {
	    $this->debug->write('*** insertFestsetzung ***', 4);
	    
	    $this->debug->write('erhebungsjahr: ' . var_export($erhebungsjahr, true), 4);
	    $this->debug->write('dokumentId: ' . var_export($dokumentId, true), 4);
	    $this->debug->write('datum: ' . var_export($datum, true), 4);
	    $this->debug->write('dokumentDatum: ' . var_export($dokumentDatum, true), 4);
	    $this->debug->write('festsetzungNutzer: ' . var_export($festsetzungNutzer, true), 4);
	    
	    $this->debug->write('summeNichtZugelasseneEntnahmemengen: ' . var_export($summeNichtZugelasseneEntnahmemengen, true), 4);
	    $this->debug->write('summeZugelasseneEntnahmemengen: ' . var_export($summeZugelasseneEntnahmemengen, true), 4);
	    $this->debug->write('summeEntnahmemengen: ' . var_export($summeEntnahmemengen, true), 4);
	    
	    $this->debug->write('summeNichtZugelassenesEntgelt: ' . var_export($summeNichtZugelassenesEntgelt, true), 4);
	    $this->debug->write('summeZugelassenesEntgelt: ' . var_export($summeZugelassenesEntgelt, true), 4);
	    $this->debug->write('summeEntgelt: ' . var_export($summeEntgelt, true), 4);
	    
	    $festsetzungen = $this->festsetzungen;
	    if(!empty($festsetzungen))
	    {
	        foreach ($festsetzungen as $festsetzung)
	        {
	            if(!empty($festsetzung))
	            {
	                if($festsetzung->compare($erhebungsjahr))
	                {
	                    $this->debug->write('Festsetzung mit id: ' . $festsetzung->getId() . ' existiert schon: update', 4);
	                    
	                    $festsetzung_id = $festsetzung->updateFestsetzung($erhebungsjahr, $dokumentId, $datum, $dokumentDatum, $festsetzungNutzer,
	                        $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	                        $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt);
	                    Gewaesserbenutzungen::getFestsetzungen($this->gui, $this);
	                    return $festsetzung_id;
	                }
	            }
	        }
	    }
	    
	    $this->debug->write('Festsetzung wird neu angelegt', 4);
	    
	    $festsetzung = new Festsetzung($this->gui);
	    $festsetzung_id = $festsetzung->createFestsetzung($this->getId(), $erhebungsjahr, $dokumentId, $datum, $dokumentDatum, $festsetzungNutzer,
	        $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	        $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt);
	    
	    Gewaesserbenutzungen::getFestsetzungen($this->gui, $this);
	    
	    return $festsetzung_id;
	}
	
	public function isFestsetzungFreigegeben($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        $datumFestsetzung = $festsetzung->getDatum();
	        
	        if(!empty($datumFestsetzung))
	        {
	            return true;
	        }
	    }
	    
	    return false;
	}
	
	public function getFestsetzungDatum($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->getDatum();
	    }
	    
	    return null;
	}
	
	public function getFestsetzungDatumHTML($erhebungsjahr) {
	    $datumFestsetzung = $this->getFestsetzungDatum($erhebungsjahr);
	    if(!empty($datumFestsetzung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $datumFestsetzung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht festgesetzt</div>";
	}
	
	public function getFestsetzungNutzer($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->getNutzer();
	    }
	    
	    return null;
	}
	
	public function getFestsetzungNutzerHTML($erhebungsjahr) {
	    $nutzerFestsetzung = $this->getFestsetzungNutzer($erhebungsjahr);
	    if(!empty($nutzerFestsetzung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $nutzerFestsetzung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht festgesetzt</div>";
	}
	
	public function getFestsetzungDokument($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        if(!empty($festsetzung->dokument))
	        {
	            return $festsetzung->dokument;
	        }
	    }
	    
	    return null;
	}
	
	public function isFestsetzungDokumentErstellt($erhebungsjahr)
	{
	    $festsetzungDokument = $this->getFestsetzungDokumentDatum($erhebungsjahr);
	    if(!empty($festsetzungDokument))
	    {
	        return true;
	    }
	    
	    return false;
	}
	
	public function getFestsetzungDokumentDatum($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->getDokumentDatum();
	    }
	    
	    return null;
	}
	
	public function getFestsetzungForErhebungsjahr($erhebungsjahr)
	{
// 	    $this->debug->write('*** getFestsetzungForErhebungsjahr ***', 4);
	    
	    if(!empty($erhebungsjahr))
	    {
	        $festsetzungen = $this->festsetzungen;
	        
	        if(!empty($festsetzungen))
	        {
	            foreach ($festsetzungen as $festsetzung)
	            {
	                if(!empty($festsetzung))
	                {
	                    if($festsetzung->getErhebungsjahr() === $erhebungsjahr)
	                    {
	                        return $festsetzung;
	                    }
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	public function getFestsetzungSummeZugelasseneEntnahmemengen($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_zugelassene_entnahmemengen;
	    }
	}
	
	public function getFestsetzungSummeNichtZugelasseneEntnahmemengen($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_nicht_zugelassene_entnahmemengen;
	    }
	}
	
	public function getFestsetzungSummeEntnahmemengen($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_entnahmemengen;
	    }
	}
	
	
	public function getFestsetzungSummeZugelassenesEntgelt($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_zugelassenes_entgelt;
	    }
	}
	
	public function getFestsetzungSummeNichtZugelassenesEntgelt($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_nicht_zugelassenes_entgelt;
	    }
	}
	
	public function getFestsetzungSummeEntgelt($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_entgelt;
	    }
	}
}
?>