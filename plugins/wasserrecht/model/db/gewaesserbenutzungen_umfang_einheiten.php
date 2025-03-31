<?php
class GewaesserbenutzungenUmfangEinheiten extends WrPgObject {

	protected $tableName = 'fiswrv_gewaesserbenutzungen_umfang_einheiten';

	public function getAbkuerzung()
	{
	    return $this->data['abkuerzung'];
	}
}
?>