<?php
#############################
# Klasse Recht #
#############################
include_once(CLASSPATH . 'PgObject.php');	

class Recht extends PgObject {

	static $schema = 'grstsich';
	static $tableName = 'rechte';
	public $layer_id = GRSTSICH_RECHTE_LAYER_ID;
	public $unlogged = true;

	function __construct($gui) {
		$gui->debug->show('Create new Recht Objekt based on table ' . Ressource::$schema . '.' . Ressource::$tableName, $this->show);
		parent::__construct($gui, Recht::$schema, Recht::$tableName);
	}


	/**
	 * Erstellt Rechte basierend auf der übergebenen Geometrie und dem Rechteart-Objekt.
	 * Es werden nur Rechte für Flurstücke angelegt, die mit der Geometrie $geom + $buffer schneiden und
	 * die noch keine Rechte dieser Rechteart im gleichen Teilprojekt haben.
	 * 
	 * @param object $gui Das GUI-Objekt.
	 * @param object $rechteart Das Rechteart-Objekt.
	 * @param string $name_prefix Präfix für die Bezeichnung der Rechte.
	 * @param string $geom Die Geometrie als WKT.
	 * @param float $buffer Optionaler Pufferwert.
	 * @param int $layer_id Die ID des Layers.
	 * @param int $feature_id Die ID des Features.
	 * @return array Ergebnis des Vorgangs mit 'success' und 'msg'.
	 */
	public static function create_from_geom($gui, $rechteart, $name_prefix, $geom, $buffer = 0, $layer_id, $feature_id) {
		$geom_str = ($buffer > 0 ? "ST_Buffer('" . $geom . "', " . $buffer . ")" : "'" . $geom . "'");
		$sql = "
			INSERT INTO grstsich.rechte (bezeichnung, art_id, flurstueck, geom, layer_id, feature_id, teilprojekt_id)
			SELECT
				CONCAT_WS(' ', '" . $rechteart->get('bezeichnung') . ", " . $name_prefix . ", Flst: ', f.zaehler || COALESCE ('/' || f.nenner, '')) AS bezeichnung,
				" . $rechteart->get_id() . " AS art_id,
				f.flurstueckskennzeichen AS flurstueck,
				ST_Intersection(ST_Transform(" . $geom_str . ", 25833), ST_Transform(f.wkb_geometry, 25833)) geom,
				" . $layer_id . " AS layer_id,
				" . $feature_id . " AS feature_id,
				(SELECT id FROM grstsich.teilprojekte WHERE ST_Intersects(ST_Transform('" . $geom . "', 25833), geom) LIMIT 1) AS teilprojekt_id
			FROM
				alkis.ax_flurstueck f
			WHERE
				ST_Transform(" . $geom_str . ", 25833) && ST_Transform(f.wkb_geometry, 25833) AND
				ST_Intersects(ST_Transform(" . $geom_str . ", 25833), ST_Transform(f.wkb_geometry, 25833)) AND
				f.endet IS NULL
		";
		$gui->debug->show('SQL zum Anlegen der Rechte: ' . $sql, false);
		$query = @pg_query($gui->pgdatabase->dbConn, $sql);
		if (!$query) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Anlegen der Rechte: ' . pg_last_error($gui->pgdatabase->dbConn) . ' Ausgeführte Anweisung: ' . $sql
			);
		}
		return array(
			'success' => true,
			'msg' => 'Rechte erfolgreich angelegt' . ' mit sql: ' . $sql
		);
	}

	/**
	 * Funktion die Rechte anlegt oder ändert.
	 * Rechte die schon gesichert sind bleiben bestehen im Teilprojekt,
	 * neue werden angelegt, wenn es für diese Rechteart in dem Flurstück noch kein Recht gibt.
	 */
	public static function update_from_geom() {
		// TODO: evt. Implementieren abgleich mit create_from_geom
	}

	public static function find_by_layer_feature($gui, $layer_id, $feature_id) {
		$obj = new Recht($gui);
		$rechte = $obj->find_where("layer_id = " . $layer_id . " AND feature_id = " . $feature_id, 'bezeichnung', "*, round(ST_Area(geom)::numeric, 0) AS flaeche");
		return $rechte;
	}

}

?>
