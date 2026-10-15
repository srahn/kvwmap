<?php
class GewaesserbenutzungenUmfangName extends WrPgObject {

	public static $tableName = 'fiswrv_gewaesserbenutzungen_umfang_name';

	public function getAbkuerzung()
	{
	    return $this->data['abkuerzung'];
	}
	
	public function getBeschreibung()
	{
	    return $this->data['beschreibung'];
	}
}
?>