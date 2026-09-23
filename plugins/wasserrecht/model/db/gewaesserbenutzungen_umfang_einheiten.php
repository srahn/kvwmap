<?php
class GewaesserbenutzungenUmfangEinheiten extends WrPgObject {

	public static $tableName = 'fiswrv_gewaesserbenutzungen_umfang_einheiten';

	public function getAbkuerzung()
	{
	    return $this->data['abkuerzung'];
	}
}
?>