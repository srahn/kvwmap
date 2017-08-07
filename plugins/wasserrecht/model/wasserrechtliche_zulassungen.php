<?php
#############################
# Klasse Konvertierung #
#############################

class WasserrechtlicheZulassungen extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'wasserrechtliche_zulassungen';
	static $write_debug = true;
	
	public $gueltigkeitsJahr;
	public $behoerde;
	public $adressat;

	function WasserrechtlicheZulassungen($gui) {
		parent::__construct($gui, WasserrechtlicheZulassungen::$schema, WasserrechtlicheZulassungen::$tableName);
	}

	public static function find_by_id($gui, $by, $id) {
		$wasserrechtlicheZulassung = new WasserrechtlicheZulassungen($gui);
		$wasserrechtlicheZulassung->find_by($by, $id);
		return $wasserrechtlicheZulassung;
	}
	
	public function find_where($where, $order = NULL, $select = '*') {
		return parent::find_where($where, $order, $select);
	}
	
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
				$wasserrechtlicheZulassungGueltigkeit = WasserrechtlicheZulassungenGueltigkeit::find_by_id($gui, 'id', $result->data['gueltigkeit']);
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
					    $adressat = Personen::find_by_id($gui, 'id', $result->data['adressat']);
					    $result->adressat = $adressat;
					}
					
					//get the 'Behoerde'
					if(!empty($result->data['ausstellbehoerde']))
					{
					    $behoerde = Behoerde::find_by_id($gui, 'id', $result->data['ausstellbehoerde']);
					    $result->behoerde = $behoerde;
					}
					
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
