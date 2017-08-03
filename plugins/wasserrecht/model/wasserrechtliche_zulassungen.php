<?php
#############################
# Klasse Konvertierung #
#############################

class WasserrechtlicheZulassungen extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'wasserrechtliche_zulassungen';
	static $write_debug = true;
	
	public $gueltigkeitsJahr;

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
}
?>
