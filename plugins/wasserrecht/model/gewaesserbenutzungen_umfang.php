<?php
class GewaesserbenutzungenUmfang extends WrPgObject {

	protected $tableName = 'fiswrv_gewaesserbenutzungen_umfang';

	public function getUmfang()
	{
	    if(!empty($this->data['max_ent_a']))
	    {
	        return $this->data['max_ent_a'] . " m³/a";
	    }
	    
	    return "";
// 	    return "m3/a";
	}
}
?>