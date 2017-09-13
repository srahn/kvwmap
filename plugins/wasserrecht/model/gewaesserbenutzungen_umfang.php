<?php
class GewaesserbenutzungenUmfang extends WrPgObject {

	protected $tableName = 'fiswrv_gewaesserbenutzungen_umfang_entnahme';

	public function getUmfang()
	{
	    if(!empty($this->data['max_ent_a']))
	    {
	        return $this->data['max_ent_a'];
	    }
	    
	    return null;
	}
	
	public function getErlaubterUmfang()
	{
	    if(!empty($this->data['max_ent_wee']))
	    {
	        return $this->data['max_ent_wee'];
	    }
	    
	    return null;
	}
	
	public function getUmfangHTML()
	{
	    if(!empty($this->getUmfang()))
	    {
	        return number_format($this->getUmfang(), 0, '', ' ')  . " m³/a";
	    }
	    
	    return "";
	}
	
	public function getErlaubterUmfangHTML()
	{
	    if(!empty($this->getErlaubterUmfang()))
	    {
	        return number_format($this->getErlaubterUmfang(), 0, '', ' ')  . " m³/a";
	    }
	    
	    return "";
	}
}
?>