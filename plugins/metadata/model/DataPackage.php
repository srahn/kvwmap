<?php
#############################
# Klasse DataPackage #
#############################
include_once(PLUGINS . 'metadata/model/PackLog.php');

class DataPackage extends PgObject {

	static $schema = 'metadata';
	static $tableName = 'data_packages';
	public $write_debug = false;
	public $layer;
	public $export_format;
	public $datatype;
	public $datatype_icon;
	public $num_features;

	function __construct($gui) {
		$gui->debug->show('Create new Object from Class DataPackage in table ' . DataPackage::$schema . '.' .  DataPackage::$tableName, $this->write_debug);
		parent::__construct($gui, DataPackage::$schema, DataPackage::$tableName);
		$this->select = "
			r.id AS ressource_id,
			r.bezeichnung,
			r.layer_id,
			r.ansprechperson,
			p.id,
			p.stelle_id,
			p.pack_status_id,
			s.status AS pack_status,
			l.connectiontype,
			l.datentyp,
			p.created_at,
			p.created_from,
			p.updated_at,
			i.dateninhaber,
			i.abk
		";
		$this->from = "
			metadata.ressources r LEFT JOIN
			(
				SELECT
					*
				FROM
					metadata.data_packages
			) p ON r.id = p.ressource_id LEFT JOIN
			metadata.pack_status s ON p.pack_status_id = s.id LEFT JOIN
			kvwmap.layer l ON r.layer_id = l.layer_id LEFT JOIN
			metadata.dateninhaber i ON r.dateninhaber_id = i.id
		";
		$this->where = "
			r.use_for_datapackage AND
			r.layer_id IS NOT NULL
		";
	}

	public static	function find($gui, $where, $order = '') {
		$datapackage = new DataPackage($gui);
		return $datapackage->find_where($where, $order);
	}

	public static	function find_by_id($gui, $id) {
		// echo '<br>DataPackage->find_by_id with id: ' . $id;
		$package = new DataPackage($gui);
		$params = array(
			'select' => $package->select,
			'from' => $package->from,
			'where' => $package->where . " AND
				p.id = " . $id . "
			",
			'order' => "r.bezeichnung"
		);
		$packages = $package->find_by_sql($params);
		if (count($packages) == 0) {
			return false;
		}
		else {
			$package = $packages[0];
			$package->get_layer();
			return $package;
		}
	}

	public static	function find_by_stelle_id($gui, $stelle_id) {
		$package = new DataPackage($gui);
		$params = array(
			'select' => $package->select,
			'from' => "
				metadata.ressources r LEFT JOIN
				(
					SELECT
						*
					FROM
						metadata.data_packages
					WHERE
						stelle_id = " . $stelle_id . "
				) p ON r.id = p.ressource_id LEFT JOIN
				metadata.pack_status s ON p.pack_status_id = s.id LEFT JOIN
				kvwmap.layer l ON r.layer_id = l.layer_id LEFT JOIN
				metadata.dateninhaber i ON r.dateninhaber_id = i.id JOIN
				kvwmap.used_layer ul ON r.layer_id = ul.layer_id
			",
			'where' => $package->where . " AND
				(
					p.stelle_id IS NULL OR
					p.stelle_id = " . $stelle_id . "
				) AND
				ul.stelle_id = " . $stelle_id . "
			",
			'order' => "r.bezeichnung"
		);
		$packages = $package->find_by_sql($params);
		$packages = array_map(
			function ($package) {
				$package->get_layer();
				$package->get_export_format();
				$package->get_datatype();
				return $package;
			},
			$packages
		);
		return $packages;
	}

	public static	function find_by_ressource_id($gui, $ressource_id) {
		// echo '<br>DataPackage->find_by_ressource_id: ' . $ressource_id;
		$package = new DataPackage($gui);
		$params = array(
			'select' => $package->select,
			'from' => $package->from,
			'where' => $package->where . " AND
				r.id = " . $ressource_id . "
			",
			'order' => "r.bezeichnung"
		);
		$packages = $package->find_by_sql($params);
		return $packages;
	}

	public static	function find_by_status($gui, $pack_status) {
		$package = new DataPackage($gui);
		$params = array(
			'select' => $package->select,
			'from' => $package->from,
			'where' => $package->where . " AND
				p.pack_status_id = " . $pack_status . "
			",
			'order' => "r.bezeichnung"
		);
		$packages = $package->find_by_sql($params);
		$packages = array_map(
			function($package) {
				$package->get_layer();
				return $package;
			},
			$packages
		);
		return $packages;
	}

	public static	function find_first_by_status($gui, $pack_status) {
		$package = new DataPackage($gui);
		$params = array(
			'select' => $package->select,
			'from' => $package->from,
			'where' => $package->where . " AND
				p.pack_status_id = " . $pack_status . "
			",
			'order' => "r.bezeichnung"
		);
		$packages = $package->find_by_sql($params);
		if (count($packages) == 0) {
			return false;
		}
		else {
			$package = $packages[0];
			if ($package->get_id()) {
				$package->get_layer();
			}
			return $package;
		}
	}

	function get_layer() {
		include_once(CLASSPATH . 'Layer.php');
		$this->layer = Layer::find_by_id($this->gui, $this->get('layer_id'));
		return $this->layer;
	}

	function get_export_path() {
		$this->export_path = METADATA_DATA_PATH . 'datenpakete/' . $this->get('stelle_id') . '/' . $this->layer->get('Name') . '/';
		return $this->export_path;
	}

	function get_export_file() {
		$export_path = $this->get_export_path();
		$this->export_file = (substr($export_path, -1, 1) == '/' ? substr($export_path, 0, -1) : $export_path) . '.zip';
		return $this->export_file;
	}

	function get_inhaber_info() {
		$infos = array();
		if ($this->get('dateninhaber') != '') {
			$infos[] = 'Dateninhaber: ' . $this->get('dateninhaber');
		}
		if ($this->get('abk') != '') {
			$infos[] = 'Abkürzung: ' . $this->get('abk');
		}
		if ($this->get('ansprechperson') != '') {
			$infos[] = 'Ansprechperson: ' . $this->get('ansprechperson');
		}
		return implode('<br>', $infos);
	}

	function delete_export_path() {
		$export_path = $this->get_export_path();
		array_map('unlink', glob($export_path . '*.*'));
		rmdir($export_path);
		return $export_path;
	}

	function delete_export_file() {
		$export_file = $this->get_export_file();
		unlink($export_file);
		return $export_file;
	}

	/**
	 * Function return an array with path and filename of bundle package in stelle $stelle_id
	 * @param int $stelle_id Id of stelle in which package has to be packed
	 * @return array{ 0: string, 1: String}
	 */
	public static	function get_bundle_package_file($stelle_id) {
		$bundle_package_file = array(
			METADATA_DATA_PATH . 'datenpakete/' . $stelle_id . '/',
			METADATA_BUNDLE_PACKAGE_NAME . '.zip'
		);
		return $bundle_package_file;
	}

	/**
	 * Function returns the export format of data package.
	 * If the layer of the data package have connectiontype 6
	 * it returns shape if the layer have geom else csv.
	 * If connectiontype is 7 or 9 it returns 'WMS' or 'WFS'
	 * @return array{ success: Boolean, export_format : String | msg: String}
	 */
	function get_export_format() {
		try {
			if ($this->layer->get('connectiontype') == 6) {
				$export_format = ($this->layer->get('geom_column') == '' ? 'CSV' : 'Shape');
			}
			elseif ($this->layer->get('connectiontype') == 7) {
				$export_format = 'WMS';
			}
			elseif ($this->layer->get('connectiontype') == 9) {
				$export_format = 'WFS';
			}
			else {
				$export_format = 'CSV';
			}
			$this->export_format = $export_format;
			return array(
				'success' => true,
				'export_format' => $export_format
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler: ' . print_r($e, true)
			);
		}
	}

	function get_datatype() {
		$icon = '';
		switch (true) {
			case ($this->get('connectiontype') == 6 AND $this->get('datentyp') == 0) : {
				$type = 'Vektordaten Punkte';
				$icon = 'map-marker';
			} break;
			case ($this->get('connectiontype') == 6 AND $this->get('datentyp') == 1) : {
				$type = 'Vektordaten Linien';
				$icon = 'minus';
			} break;
			case ($this->get('connectiontype') == 6 AND $this->get('datentyp') == 2) : {
				$type = 'Vektordaten Polygone';
				$icon = 'map';
			} break;
			case ($this->get('connectiontype') == 6 AND $this->get('datentyp') == 8) : {
				$type = 'Vektordaten Polygone';
				$icon = 'map';
			} break;
			case ($this->get('connectiontype') == 6 AND $this->get('datentyp') == 5) : {
				$type = 'Tabellendaten ohne Geometrie';
				$icon = 'file-excel-o';
			} break;
			case ($this->get('connectiontype') == 7) : {
				$type = 'Rasterdatendienst (WMS)';
				$icon = 'globe';
			} break;
			case ($this->get('connectiontype') == 9) : {
				$type = 'Vektordatendienst (WFS)';
				$icon = 'file-code-o';
			} break;
			default : {
				$icon = 'file-o';
			}
		}
		$this->datatype = $type;
		$this->datatype_icon = $icon;
		return $icon;
	}

	function log($msg) {
		return PackLog::write($this->gui, $this, $msg);
	}
} 