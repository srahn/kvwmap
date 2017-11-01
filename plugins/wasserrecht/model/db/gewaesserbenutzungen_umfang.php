<?php
class GewaesserbenutzungenUmfang extends WrPgObject {

	protected $tableName = 'fiswrv_gewaesserbenutzungen_umfang';
	
	public $name;
	public $einheit;
	
	public function find_where_with_subtables($where, $order = NULL, $select = '*')
	{
	    $this->log->log_info('*** GewaesserbenutzungenUmfang->find_where_with_subtables ***');
	    $this->log->log_debug('where: ' . $where);
	    
	    $gewaesserbenutzungenUmfang = $this->find_where($where, $order, $select);
	    
	    if(!empty($gewaesserbenutzungenUmfang))
	    {
	        foreach ($gewaesserbenutzungenUmfang AS $gewaesserbenutzungUmfang)
	        {
	            if(!empty($gewaesserbenutzungUmfang))
	            {
	                $gun = new GewaesserbenutzungenUmfangName($this->gui);
	                if(!empty($gewaesserbenutzungUmfang->data['name']))
	                {
	                    $gewaesserbenutzungUmfangName = $gun->find_where('id=' . $gewaesserbenutzungUmfang->data['name']);
	                    if(!empty($gewaesserbenutzungUmfangName))
	                    {
	                        $gewaesserbenutzungUmfang->name = $gewaesserbenutzungUmfangName[0];
	                    }
	                }
	                
	                $gue = new GewaesserbenutzungenUmfangEinheiten($this->gui);
	                if(!empty($gewaesserbenutzungUmfang->data['name']))
	                {
	                    $gewaesserbenutzungUmfangEinheit = $gue->find_where('id=' . $gewaesserbenutzungUmfang->data['einheit']);
	                    if(!empty($gewaesserbenutzungUmfangEinheit))
	                    {
	                        $gewaesserbenutzungUmfang->einheit = $gewaesserbenutzungUmfangEinheit[0];
	                    }
	                }
	            }
	        }
	    }
	    
	    return $gewaesserbenutzungenUmfang;
	}
	
	public function getWert()
	{
	    return $this->data['wert'];
	}
}
?>