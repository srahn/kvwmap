<?php
class WasserrechtlicheZulassungen extends WrPgObject {

	protected $tableName = 'wasserrechtliche_zulassungen';
	
	public $gueltigkeitsJahr;
	public $behoerde;
	public $adressat;
	public $anlagen;
	public $gewaesserbenutzungen;

	public function find_gueltigkeitsjahre($gui) {
	    
		$results = $this->find_where('gueltigkeit IS NOT NULL');
		$wrzProGueltigkeitsJahr = new WRZProGueltigkeitsJahr();
		
		if(!empty($results))
		{
			$wasserrechtlicheZulassungGueltigkeitJahrReturnArray = array();
			foreach($results AS $result)
			{
// 				var_dump($this->debug);
			    $this->debug->write('result: ' . var_export($result->data, true), 4);
			    $wrzGueltigkeit = new WasserrechtlicheZulassungenGueltigkeit($gui);
			    $wasserrechtlicheZulassungGueltigkeit = $wrzGueltigkeit->find_by_id($gui, 'id', $result->data['gueltigkeit']);
				if(!empty($wasserrechtlicheZulassungGueltigkeit))
				{
					$datum = $wasserrechtlicheZulassungGueltigkeit->data['gueltig_bis'];
					//var_dump($datum);
					$date = DateTime::createFromFormat("d.m.Y", $datum);
					$year = $date->format("Y");
					if (!in_array($year, $wasserrechtlicheZulassungGueltigkeitJahrReturnArray))
					{
						$wasserrechtlicheZulassungGueltigkeitJahrReturnArray[] = $year;
					}
					
					$result->gueltigkeitsJahr=$year;
					
					//get the 'Adressat'
					if(!empty($result->data['adressat']))
					{
					    $person = new Personen($gui);
					    $adressat = $person->find_by_id($gui, 'id', $result->data['adressat']);
					    $result->adressat = $adressat;
					}
					
					//get the 'Behoerde'
					if(!empty($result->data['ausstellbehoerde']))
					{
					    $bh = new Behoerde($gui);
					    $behoerde = $bh->find_by_id($gui, 'id', $result->data['ausstellbehoerde']);
					    $result->behoerde = $behoerde;
					}
					
					//get the 'Anlage'
					if(!empty($result->data['anlage']))
					{
					    $anlage = new Anlage($gui);
					    $anlagen = $anlage->find_where('id=' . $result->data['anlage']);
					    $result->anlagen = $anlagen;
					}
					
					//get the 'Gewaesserbenutzungen'
					$gewaesserbenutzung = new Gewaesserbenutzungen($gui);
					$gewaesserbenutzungen = $gewaesserbenutzung->find_where_with_umfang('wasserrechtliche_zulassungen=' . $result->getId());
					$result->gewaesserbenutzungen = $gewaesserbenutzungen;
					
					$wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[]=$result;
				}
			}
			$wrzProGueltigkeitsJahr->gueltigkeitsJahre=$wasserrechtlicheZulassungGueltigkeitJahrReturnArray;
			return $wrzProGueltigkeitsJahr;
// 			return $wasserrechtlicheZulassungGueltigkeitJahrReturnArray;
		}
		
		$wrzProGueltigkeitsJahr->gueltigkeitsJahre=array('n/a');
		return $wrzProGueltigkeitsJahr;
// 		return array('n/a');
	}
	
	public function toString() {
	    return "gueltigkeitsJahr: " . $this->gueltigkeitsJahr . (!empty($this->behoerde) ? " behoerde: " . $this->behoerde->data['id'] : "" ) . (!empty($this->adressat) ? " adressat: " . $this->adressat->data['id'] : "");
	}
	
	public function getBehoerdeName() {
	    return !empty($this->behoerde) ?  $this->behoerde->getName() : null;
	}
	
	public function getBehoerdeId() {
	    return !empty($this->behoerde) ?  $this->behoerde->getId() : null;
	}
}
?>
