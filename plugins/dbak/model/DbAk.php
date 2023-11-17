<?
	class DbAk {

		public $data;

		function __construct($data) {
			# initialize things here
			$this->data = $data;
		}

		/**
		 * Erzeugt ein neues Objekt über die DbAk Schnittstelle
		 */
		function create() {
			return array(
				'success' => true,
				'msg' => 'Funktion zum Anlegen des Datensatzes über die DbAk-Schnittstelle noch nicht implementiert.'
			);
		}

		/**
		 * Aktualisiert ein neues Objekt über die DbAk Schnittstelle
		 */
		function update() {
			return array(
				'success' => true,
				'msg' => 'Funktion zum Aktualisieren des Datensatzes über die DbAk-Schnittstelle noch nicht implementiert.'
			);
		}

		/**
		 * Löscht ein neues Objekt über die DbAk Schnittstelle
		 */
		function delete() {
			return array(
				'success' => true,
				'msg' => 'Funktion zum Löschen des Datensatzes über die DbAk-Schnittstelle noch nicht implementiert.'
			);
		}
	}
?>