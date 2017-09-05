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
	                if(!empty($gewaesserbenutzung->data['umfang']))
	                {
	                    //echo 'id=' . $gewaesserbenutzung->data['umfang'];
	                    $gewaesserbenutzungUmfang = $gwu->find_where('id=' . $gewaesserbenutzung->data['umfang']);
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
	
	public function getKennummer() {
	    return $this->data['kennnummer'];
	}
	
	public function getBezeichnung() {
	    $fieldname = 'bezeichnung';
// 	    $sql = "SELECT COALESCE(e.name,'') ||' (Aktenzeichen: '|| COALESCE(a.aktenzeichen,'') ||')'||' vom '|| COALESCE(a.datum_postausgang::text,'') || ' zum ' || COALESCE(c.name,'') || ' von ' || COALESCE(d.max_ent_a::text,'') || ' m³/Jahr' AS " . $fieldname ." FROM " . $this->schema . '.' . "wasserrechtliche_zulassungen a LEFT JOIN " . $this->schema . '.' . $this->tableName . " b ON b.wasserrechtliche_zulassungen = a.id LEFT JOIN " . $this->schema . '.' . "gewaesserbenutzungen_art c ON c.id = b.art LEFT JOIN " . $this->schema . '.' . "gewaesserbenutzungen_umfang d ON b.umfang = d.id LEFT JOIN " . $this->schema . '.' . "wasserrechtliche_zulassungen_ausgangsbescheide_klasse e ON a.klasse = e.id WHERE b.id = '" . $this->getId() . "';";
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