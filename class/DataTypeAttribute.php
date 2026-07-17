<?php
class DataTypeAttribute extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'datatype_attributes');
	}

	public static	function find($gui, $where) {
		$datatype_attribute = new DataTypeAttribute($gui);
		return $datatype_attribute->find_where($where);
	}

	function get_document_select($layer, $attribute) {
		$document_select = "SPLIT_PART((" . ($attribute->is_array_type() ? "UNNEST(" . $attribute->get('name') . ")" : $attribute->get('name')) . ")." . $this->get('name') . ", '&original_name=', 1)";
		if ($layer->get('document_url') != '') {
			$document_select = "REPLACE(" . $document_select . ", '" . $layer->get('document_url') . "', '" . $layer->get('document_path') . "')";
		}
		return $document_select;
	}

	function get_document_where($layer, $attribute) {
		$document_where = $attribute->get('name') . " IS NOT NULL";
		if ($layer->get('document_url') != '') {
			$document_where .= " AND " . ($attribute->is_array_type() ? "array_to_string(" . $attribute->get('name') . ", ',')" : $attribute->get('name')) . " LIKE '%" . $layer->get('document_url') . "%'";
		}
		return $document_where;
	}

	function is_document_attribute() {
		return $this->get('form_element_type') == 'Dokument';
	}
}
?>