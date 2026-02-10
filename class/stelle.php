<?php
#####################################
/* class_stelle
* functions
*		stelle($id, $database)
*		getsubmenues($id)
*		getName()
*		readDefaultValues()
*		Löschen()
*		deleteMenue(text)
*		deleteLayer($layer, $pgdatabase)
*		deleteDruckrahmen()
*		deleteStelleGemeinden()
*		deleteFunktionen()
*		getstellendaten()
*		NeueStelleAnlegen($stellendaten)
*		Aendern($stellendaten)
*		getStellen($order)
*/
class stelle {
	var $id;
	var $Bezeichnung;
	var $debug;
	var $log;
	var $MaxGeorefExt;
	var $nImageWidth;
	var $nImageHeight;
	var $oGeorefExt;
	var $pixsize;
	var $selectedButton;
	var $database;
	var $data;
	var $epsg_code;
	var $protected;
	var $ows_title;
	var $ows_abstract;
	var $ows_namespace;
	var $ows_updatesequence;
	var $ows_geographicdescription;
	var $ows_fees;
	var $ows_inspireidentifiziert;
	var $ows_srs;

	var $ows_contactorganization;
	var $ows_contacturl;
	var $ows_contactaddress;
	var $ows_contactpostalcode;
	var $ows_contactcity;
	var $ows_contactadministrativearea;
	var $ows_contactemailaddress;
	var $ows_contactperson;
	var $ows_contactposition;
	var $ows_contactvoicephone;
	var $ows_contactfacsimile;

	var $ows_distributionorganization;
	var $ows_distributionurl;
	var $ows_distributionaddress;
	var $ows_distributionpostalcode;
	var $ows_distributioncity;
	var $ows_distributionadministrativearea;
	var $ows_distributionemailaddress;
	var $ows_distributionperson;
	var $ows_distributionposition;
	var $ows_distributionvoicephone;
	var $ows_distributionfacsimile;

	var $ows_contentorganization;
	var $ows_contenturl;
	var $ows_contentaddress;
	var $ows_contentpostalcode;
	var $ows_contentcity;
	var $ows_contentadministrativearea;
	var $ows_contentemailaddress;
	var $ows_contentperson;
	var $ows_contentposition;
	var $ows_contentvoicephone;
	var $ows_contentfacsimile;

	var $wms_accessconstraints;
	var $check_client_ip;
	var $checkPasswordAge;
	var $allowedPasswordAge;
	var $useLayerAliases;
	var $selectable_layer_params;
	var $hist_timestamp;
	var $default_user_id;
	var $show_shared_layers;
	var $style;
	var $reset_password_text;
	var $invitation_text;
	public $pgdbhost = 'pgsql';

	function __construct($id, $database) {
		global $debug;
		global $log_mysql;
		$this->debug = $debug;
		$this->log = $log_mysql;
		$this->id = $id;
		$this->database = $database;
		$ret = $this->readDefaultValues();
	}

	public static	function find($gui, $where, $order = '', $sort_direction = '') {
		$stelle = new MyObject($gui, 'stelle');
		return $stelle->find_where($where, $order, $sort_direction);
	}

	function get($attribute) {
		return $this->data[$attribute];
	}

	function set($attribute, $value) {
		$this->data[$attribute] = $value;
	}

	function getsubmenues($id) {
		global $language;
		$sql ='SELECT menue_id,';
		if ($language != 'german') {
			$sql.='`name_'.$language.'` AS ';
		}
		$sql .=' name, target, links FROM u_menue2stelle, u_menues';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND obermenue = '.$id;
		$sql .=' AND menueebene = 2';
		$sql .=' AND u_menue2stelle.menue_id = u_menues.id';
		$sql .= ' ORDER BY menue_order';
		$this->debug->write("<p>file:stelle.php class:stelle->getsubMenues - Lesen der UnterMenuepunkte eines Menüpunktes:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		else {
			while ($rs = $this->database->result->fetch_array()) {
				$menue['name'][]=$rs['name'];
				$menue['target'][]=$rs['target'];
				$menue['links'][]=$rs['links'];
			}
		}
		$html = '<table cellspacing="2" cellpadding="0" border="0">';
		for ($i = 0; $i < count($menue['name']); $i++) {
			$html .='
        <tr>
          <td> 
            <img src="'.GRAPHICSPATH.'leer.gif" width="17" height="1" border="0">
					</td>
					<td>
            <a href="';
			if ($menue['target'][$i]=='confirm') {
				$html .='javascript:Bestaetigung(\'';
			}
			$html .= $menue['links'][$i];
			if ($menue['target'][$i]=='confirm') {
				$html .= '\',\'Diese Aktion wirklich ausf&uuml;hren?\')';
				$menue['target'][$i]='';
			}
			$html .= '" class="menuered"';
			if ($menue['target'][$i]!='') {
				$html .= ' target="'.$menue['target'][$i].'"';
			}
			$html .= '>'.$menue['name'][$i].'</a>
          </td>
        </tr>';
		}
		$html .= '</table>';
		return $html;
	}
	
  function getName() {
		global $language;
    $sql ='SELECT ';
    if ($language != 'german' AND $language != ''){
      $sql.='`Bezeichnung_'.$language.'` AS ';
    }
    $sql.='Bezeichnung FROM stelle WHERE ID='.$this->id;
    #echo '<p>SQL zur Abfrage des Stellennamens: ' . $sql;
    $this->debug->write("<p>file:stelle.php class:stelle->getName - Abfragen des Namens der Stelle:<br>",4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		$rs = $this->database->result->fetch_array();
    $this->Bezeichnung=$rs['Bezeichnung'];
    return $rs['Bezeichnung'];
  }

	function readDefaultValues() {
		global $language;
		if ($language != '' AND $language != 'german') {
			$name_column = "
			CASE
				WHEN s.`Bezeichnung_" . $language . "` != \"\" THEN s.`Bezeichnung_" . $language . "`
				ELSE s.`Bezeichnung`
			END AS Bezeichnung";
		}
		else {
			$name_column = "s.`Bezeichnung`";
		}

		$sql = "
			SELECT
				`ID`," .
				$name_column . ",
				`start`,
				`stop`, `minxmax`, `minymax`, `maxxmax`, `maxymax`, `epsg_code`, `Referenzkarte_ID`, `Authentifizierung`, `ALB_status`, `wappen`, `wappen_link`, `logconsume`,
				`ows_namespace`,
				`ows_title`,
				`wms_accessconstraints`,
				`ows_abstract`,
				`ows_updatesequence`,
				`ows_geographicdescription`,
				`ows_fees`,
				#`ows_inspireidentifiziert`,
				`ows_srs`,

				`ows_contactorganization`,
				`ows_contacturl`,
				`ows_contactaddress`,
				`ows_contactpostalcode`,
				`ows_contactcity`,
				`ows_contactadministrativearea`,
				`ows_contactemailaddress`,
				`ows_contactperson`,
				`ows_contactposition`,
				`ows_contactvoicephone`,
				`ows_contactfacsimile`,

				`ows_distributionorganization`,
				`ows_distributionurl`,
				`ows_distributionaddress`,
				`ows_distributionpostalcode`,
				`ows_distributioncity`,
				`ows_distributionadministrativearea`,
				`ows_distributionemailaddress`,
				`ows_distributionperson`,
				`ows_distributionposition`,
				`ows_distributionvoicephone`,
				`ows_distributionfacsimile`,

				`ows_contentorganization`,
				`ows_contenturl`,
				`ows_contentaddress`,
				`ows_contentpostalcode`,
				`ows_contentcity`,
				`ows_contentadministrativearea`,
				`ows_contentemailaddress`,
				`ows_contentperson`,
				`ows_contentposition`,
				`ows_contentvoicephone`,
				`ows_contentfacsimile`,

				`protected`, `check_client_ip`, `check_password_age`, `allowed_password_age`, `use_layer_aliases`, `selectable_layer_params`, `hist_timestamp`, `default_user_id`,
				`style`,
				`show_shared_layers`,
				`reset_password_text`,
				`invitation_text`
			FROM
				stelle s
			WHERE
				ID = " . $this->id . "
		";
		#echo 'SQL zum Abfragen der Stelle: ' . $sql;
		$this->debug->write('<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>', 4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return $ret;
		}
		$rs = $this->database->result->fetch_array();
		$this->data = $rs;
		$this->Bezeichnung = $rs['Bezeichnung'];
		$this->MaxGeorefExt = rectObj($rs['minxmax'], $rs['minymax'], $rs['maxxmax'], $rs['maxymax']);
		$this->epsg_code = $rs['epsg_code'];
		$this->protected = $rs['protected'];
		//---------- OWS Metadaten ----------//
		$this->ows_title = $rs['ows_title'];
		$this->ows_abstract = $rs['ows_abstract'];
		$this->ows_namespace = $rs['ows_namespace'];
		$this->ows_updatesequence = $rs['ows_updatesequence'];
		$this->ows_geographicdescription = $rs['ows_geographicdescription'];
		$this->ows_fees = $rs['ows_fees'];
		$this->ows_inspireidentifiziert = $rs['ows_inspireidentifiziert'];
		$this->ows_srs = preg_replace(array('/: +/', '/ +:/'), ':', $rs['ows_srs']);

		$this->ows_contactorganization = $rs['ows_contactorganization'];
		$this->ows_contacturl = $rs['ows_contacturl'];
		$this->ows_contactaddress = $rs['ows_contactaddress'];
		$this->ows_contactpostalcode = $rs['ows_contactpostalcode'];
		$this->ows_contactcity = $rs['ows_contactcity'];
		$this->ows_contactadministrativearea = $rs['ows_contactadministrativearea'];
		$this->ows_contactemailaddress = $rs['ows_contactemailaddress'];
		$this->ows_contactperson = $rs['ows_contactperson'];
		$this->ows_contactposition = $rs['ows_contactposition'];
		$this->ows_contactvoicephone = $rs['ows_contactvoicephone'];
		$this->ows_contactfacsimile = $rs['ows_contactfacsimile'];

		$this->ows_distributionorganization = $rs['ows_distributionorganization'];
		$this->ows_distributionurl = $rs['ows_distributionurl'];
		$this->ows_distributionaddress = $rs['ows_distributionaddress'];
		$this->ows_distributionpostalcode = $rs['ows_distributionpostalcode'];
		$this->ows_distributioncity = $rs['ows_distributioncity'];
		$this->ows_distributionadministrativearea = $rs['ows_distributionadministrativearea'];
		$this->ows_distributionemailaddress = $rs['ows_distributionemailaddress'];
		$this->ows_distributionperson = $rs['ows_distributionperson'];
		$this->ows_distributionposition = $rs['ows_distributionposition'];
		$this->ows_distributionvoicephone = $rs['ows_distributionvoicephone'];
		$this->ows_distributionfacsimile = $rs['ows_distributionfacsimile'];

		$this->ows_contentorganization = $rs['ows_contentorganization'];
		$this->ows_contenturl = $rs['ows_contenturl'];
		$this->ows_contentaddress = $rs['ows_contentaddress'];
		$this->ows_contentpostalcode = $rs['ows_contentpostalcode'];
		$this->ows_contentcity = $rs['ows_contentcity'];
		$this->ows_contentadministrativearea = $rs['ows_contentadministrativearea'];
		$this->ows_contentemailaddress = $rs['ows_contentemailaddress'];
		$this->ows_contentperson = $rs['ows_contentperson'];
		$this->ows_contentposition = $rs['ows_contentposition'];
		$this->ows_contentvoicephone = $rs['ows_contentvoicephone'];
		$this->ows_contentfacsimile = $rs['ows_contentfacsimile'];

		$this->wms_accessconstraints = $rs['wms_accessconstraints'];
		$this->check_client_ip = $rs['check_client_ip'];
		$this->checkPasswordAge = $rs['check_password_age'];
		$this->allowedPasswordAge = $rs['allowed_password_age'];
		$this->useLayerAliases = $rs['use_layer_aliases'];
		$this->selectable_layer_params = $rs['selectable_layer_params'];
		$this->hist_timestamp = $rs['hist_timestamp'];
		$this->default_user_id = $rs['default_user_id'];
		$this->show_shared_layers = $rs['show_shared_layers'];
		$this->style = $rs['style'];
		$this->reset_password_text = $rs['reset_password_text'];
		$this->invitation_text = $rs['invitation_text'];
	}

	function delete() {
		$sql = "
			DELETE FROM stelle
			WHERE
				ID = " . $this->id . "
		";
		$ret=$this->database->execSQL($sql, 4, 0);
		if ($ret[0]) {
			$ret[1] .= '<br>Die Stelle konnte nicht gelöscht werden.<br>' . $ret[1];
		}
		return $ret;
	}

	function deleteMenue($menue_ids) {
		$where_menue_id = ((is_array($menue_ids) and count($menue_ids) > 0) ? " AND `menue_id` IN (" . implode(", ", $menue_ids) . ")" : "");
		# Löschen der Zuordnung der Menüs zu der Stelle
		$sql = "
			DELETE FROM
				`u_menue2stelle`
			WHERE
				`stelle_id` = " . $this->id .
				$where_menue_id . "
		";
		#echo '<br>stelle.php deleteMenue(' . (is_array($menue_ids) ? implode(', ', $menue_ids) : $menue_ids) . ') Löschen der Menüpunkte der Stelle mit sql: ' . $sql . '!';
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Stelle in menue2stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }

		/*		erstmal rausgenommen, weil sonst beim Ändern einer Stelle die Menüeinstellungen der Nutzer, insbesondere des Default-Nutzers verloren gehen
		# Löschen der Zuordnung der Menüs zu den Rollen der Stelle
		$sql = "
			DELETE FROM
				`u_menue2rolle`
			WHERE
				`stelle_id` = " . $this->id .
				$where_menue_id . "
		";
		#echo '<br>stelle.php deleteMenue (' . (is_array($menue_ids) ? implode(', ', $menue_ids) : $menue_ids) . 'Löschen der Menüpunkte der Rollen der Stellen sql: ' . $sql . '!';
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Rollen der Stelle in menue2rolle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		*/
		return 1;
	}

	function deleteLayer($layer, $pgdatabase) {
		#echo 'stelle.php deleteLayer ids: ' . implode(', ', $layer);
		if($layer == 0){
			# löscht alle Layer der Stelle
			$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
			$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
			# Filter löschen
			$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
			while ($rs = $this->database->result->fetch_row()) {
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
			}
			$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		}
		else{
			# löscht die übergebenen Layer der Stelle
			for ($i=0;$i<count($layer);$i++) {
				$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
				$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id.' AND `layer_id` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; 	}			
				# Filter löschen
				$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
				$rs = $this->database->result->fetch_array();
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
				$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}
	
	function deleteDruckrahmen() {
		# löscht alle Druckrahmenzuordnungen der Stelle
		$sql ='DELETE FROM `druckrahmen2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteDruckrahmen - Löschen der Druckrahmen der Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteStelleGemeinden() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `stelle_gemeinden` WHERE `Stelle_ID` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteStelleGemeinden - Löschen der StelleGemeinden der Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteFunktionen() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `u_funktion2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteFunktionen - Löschen der Funktionen der Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function getstellendaten() {
		$sql = "
			SELECT
				*
			FROM
				stelle
			WHERE
				ID = " . $this->id . "
		";
		#echo '<p>SQL zum Abfragen der Stellendaten: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getstellendaten - Abfragen der Stellendaten<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs = $this->database->result->fetch_array();
		$this->data = $rs;
		return $rs;
	}

	/**
	 * Query stellendaten with getstellendaten from database
	 * and complete organization, person and emailaddress for content from contact
	 * and for distribution from content if they are empty
	 * @return array associative array with values of stelle
	 */
	function getstellendaten_full_contact() {
		$stellendaten = $this->getstellendaten();
		if (empty($stellendaten['ows_contentorganization'])) {
			$stellendaten['ows_contentorganization'] = $stellendaten['ows_contactorganization'];
		}
		if (empty($stellendaten['ows_distributionorganization'])) {
			$stellendaten['ows_distributionorganization'] = $stellendaten['ows_contactorganization'];
		}
		if (empty($stellendaten['ows_distributionurl'])) {
			$stellendaten['ows_distributionurl'] = $stellendaten['ows_contacturl'];
		}
		if (empty($stellendaten['ows_contentperson'])) {
			$stellendaten['ows_contentperson'] = $stellendaten['ows_contactperson'];
		}
		if (empty($stellendaten['ows_distributionperson'])) {
			$stellendaten['ows_distributionperson'] = $stellendaten['ows_contactperson'];
		}

		if (empty($stellendaten['ows_contentemailaddress'])) {
			$stellendaten['ows_contentemailaddress'] = $stellendaten['ows_contactemailaddress'];
		}
		if (empty($stellendaten['ows_distributionemailaddress'])) {
			$stellendaten['ows_distributionemailaddress'] = $stellendaten['ows_contactemailaddress'];
		}
		return $stellendaten;
	}

	function NeueStelleAnlegen($stellendaten) {
		$_files = $_FILES;
		# Neue Stelle anlegen
		$sql = "
			INSERT INTO stelle
			SET
				" . ($stellendaten['id'] != '' ? "`ID` = " . $stellendaten['id'] . ", " : "") . "
				`Bezeichnung` = '" . $stellendaten['bezeichnung'] . "',
				`Referenzkarte_ID` = " . $stellendaten['Referenzkarte_ID'] . ",
				`minxmax` = " . $stellendaten['minxmax'] . ",
				`minymax` = " . $stellendaten['minymax'] . ",
				`maxxmax` = " . $stellendaten['maxxmax'] . ",
				`maxymax` = " . $stellendaten['maxymax'] . ",
				`epsg_code` = " . $stellendaten['epsg_code'] . ",
				`start` = '" . ($stellendaten['start'] == '' ? '0000-00-00' : $stellendaten['start']) . "',
				`stop` = '" . ($stellendaten['stop'] == '' ? '0000-00-00' : $stellendaten['stop']). "',
				`ows_title` = '" . $stellendaten['ows_title'] . "',
				`ows_abstract` = '" . $stellendaten['ows_abstract'] . "',
				`wms_accessconstraints` = '" . $stellendaten['wms_accessconstraints'] . "',
				`ows_contactorganization` = '" . $stellendaten['ows_contactorganization'] . "',
				`ows_contacturl` = '" . $stellendaten['ows_contacturl'] . "',
				`ows_contactemailaddress` = '" . $stellendaten['ows_contactemailaddress'] . "',
				`ows_contactperson` = '" . $stellendaten['ows_contactperson'] . "',
				`ows_contactposition` = '" . $stellendaten['ows_contactposition'] . "',
				`ows_contactvoicephone` = '" . $stellendaten['ows_contactvoicephone'] . "',
				`ows_contactfacsimile` = '" . $stellendaten['ows_contactfacsimile'] . "',
				`ows_contactaddress` = '" . $stellendaten['ows_contactaddress'] . "',
				`ows_contactpostalcode` = '" . $stellendaten['ows_contactpostalcode'] . "',
				`ows_contactcity` = '" . $stellendaten['ows_contactcity'] . "',
				`ows_contactadministrativearea` = '" . $stellendaten['ows_contactadministrativearea'] . "',
				`ows_contentorganization` = '" . $stellendaten['ows_contentorganization'] . "',
				`ows_contenturl` = '" . $stellendaten['ows_contenturl'] . "',
				`ows_contentemailaddress` = '" . $stellendaten['ows_contentemailaddress'] . "',
				`ows_contentperson` = '" . $stellendaten['ows_contentperson'] . "',
				`ows_contentposition` = '" . $stellendaten['ows_contentposition'] . "',
				`ows_contentvoicephone` = '" . $stellendaten['ows_contentvoicephone'] . "',
				`ows_contentfacsimile` = '" . $stellendaten['ows_contentfacsimile'] . "',
				`ows_contentaddress` = '" . $stellendaten['ows_contentaddress'] . "',
				`ows_contentpostalcode` = '" . $stellendaten['ows_contentpostalcode'] . "',
				`ows_contentcity` = '" . $stellendaten['ows_contentcity'] . "',
				`ows_contentadministrativearea` = '" . $stellendaten['ows_contentadministrativearea'] . "',
				`ows_geographicdescription` = '" . $stellendaten['ows_geographicdescription'] . "',
				`ows_distributionorganization` = '" . $stellendaten['ows_distributionorganization'] . "',
				`ows_distributionurl` = '" . $stellendaten['ows_distributionurl'] . "',
				`ows_distributionemailaddress` = '" . $stellendaten['ows_distributionemailaddress'] . "',
				`ows_distributionperson` = '" . $stellendaten['ows_distributionperson'] . "',
				`ows_distributionposition` = '" . $stellendaten['ows_distributionposition'] . "',
				`ows_distributionvoicephone` = '" . $stellendaten['ows_distributionvoicephone'] . "',
				`ows_distributionfacsimile` = '" . $stellendaten['ows_distributionfacsimile'] . "',
				`ows_distributionaddress` = '" . $stellendaten['ows_distributionaddress'] . "',
				`ows_distributionpostalcode` = '" . $stellendaten['ows_distributionpostalcode'] . "',
				`ows_distributioncity` = '" . $stellendaten['ows_distributioncity'] . "',
				`ows_distributionadministrativearea` = '" . $stellendaten['ows_distributionadministrativearea'] . "',
				`ows_fees` = '" . $stellendaten['ows_fees'] . "',
				`ows_inspireidentifiziert` = '" . ($stellendaten['ows_inspireidentifiziert'] == '1' ? "1" : "0") . "',
				`ows_srs` = '" . preg_replace(array('/: +/', '/ +:/'), ':', $stellendaten['ows_srs']) . "',
				`wappen_link` = '" . $stellendaten['wappen_link'] . "',
				`wappen` = '" . ($stellendaten['wappen'] ? $_files['wappen']['name'] : $stellendaten['wappen_save']) . "',
				`default_user_id` = " . ($stellendaten['default_user_id'] != '' ? $stellendaten['default_user_id'] : 'NULL') . ",
				`check_client_ip` = '" . ($stellendaten['checkClientIP'] == '1'	? "1" : "0") . "',
				`check_password_age` = '" . ($stellendaten['checkPasswordAge'] == '1' ? "1" : "0") . "',
				`allowed_password_age` = " . ($stellendaten['allowedPasswordAge'] != '' ? $stellendaten['allowedPasswordAge'] : "6") . ",
				`use_layer_aliases` = '" . (value_of($stellendaten, 'use_layer_aliases') == '1'	? "1" : "0") . "',
				`hist_timestamp` = '" . (value_of($stellendaten, 'hist_timestamp') == '1' ? 1 : 0) . "',
				`show_shared_layers` = " . ($stellendaten['show_shared_layers'] ? 1 : 0) . ",
				`version` = '" . ($stellendaten['version'] == '' ? "1.0.0" : $stellendaten['version']) . "',
				`reset_password_text` = '" . $stellendaten['reset_password_text'] . "',
				`invitation_text` = '" . $stellendaten['invitation_text'] . "',				
				`comment` = '" . $stellendaten['comment'] . "'				
		";
		#echo '<br>SQL zum Ändern der Stelle: ' . $sql;
		$ret = $this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1] .= '<br>Die Stellendaten konnten nicht eingetragen werden.<br>'.$ret[1];
		}
		else {
			# Stelle Erfolgreich angelegt
			# Abfragen der stelle_id des neu eingetragenen Benutzers
			$sql ='SELECT ID FROM stelle WHERE';
			$sql.=' Bezeichnung="'.$stellendaten['bezeichnung'].'"';
			# Starten der Anfrage
			$this->database->execSQL($sql,4, 0);
			#echo $sql;
			if (!$this->database->success) {
				# Fehler bei der Datenbankanfrage
				$ret[1] .= '<br>Die Stellendaten konnten nicht eingetragen werden.<br>' . $this->database->errormessage;
			}
			else {
				# Abfrage erfolgreich durchgeführt, übergeben der stelle_id zur Rückgabe der Funktion
				$rs = $this->database->result->fetch_array();
				$ret[1] = $rs['ID'];
			}
		}
		return $ret;
	}

	# Stelle ändern
	function Aendern($stellendaten) {
		$language = rolle::$language;
		$stelle = ($stellendaten['id'] != '' ? "`ID` = " . $stellendaten['id'] . ", " : "");
		$wappen = (value_of($stellendaten, 'wappen') != '' ? "`wappen` = '" . $stellendaten['wappen'] . "', " : "");
		$sql = "
			UPDATE
				stelle
			SET" .
				$stelle .
				$wappen . "
				`Bezeichnung` = '" . $stellendaten['bezeichnung'] . "'," .
				(array_key_exists('Bezeichnung_' . $language, $stellendaten) ? "
				`Bezeichnung_" . $language . "` = '" . $stellendaten['Bezeichnung_' . $language] . "'," : "") . "
				`Referenzkarte_ID` = " . $stellendaten['Referenzkarte_ID'] . ",
				`minxmax` = '" . $stellendaten['minxmax'] . "',
				`minymax` = '" . $stellendaten['minymax'] . "',
				`maxxmax` = '" . $stellendaten['maxxmax'] . "',
				`maxymax` = '" . $stellendaten['maxymax'] . "',
				`epsg_code` = '" . $stellendaten['epsg_code'] . "',
				`start` = '" . ($stellendaten['start'] == '' ? '0000-00-00' : $stellendaten['start']) . "',
				`stop` = '" . ($stellendaten['stop'] == '' ? '0000-00-00' : $stellendaten['stop']). "',
				`ows_title` = '" . $stellendaten['ows_title'] . "',
				`ows_namespace` = '" . $stellendaten['ows_namespace'] . "',
 				`ows_abstract` = '" . $stellendaten['ows_abstract'] . "',
				`wms_accessconstraints` = '" . $stellendaten['wms_accessconstraints'] . "',
				`ows_contactorganization` = '" . $stellendaten['ows_contactorganization'] . "',
				`ows_contacturl` = '" . $stellendaten['ows_contacturl'] . "',
				`ows_contactemailaddress` = '" . $stellendaten['ows_contactemailaddress'] . "',
				`ows_contactperson` = '" . $stellendaten['ows_contactperson'] . "',
				`ows_contactposition` = '" . $stellendaten['ows_contactposition'] . "',
				`ows_contactvoicephone` = '" . $stellendaten['ows_contactvoicephone'] . "',
				`ows_contactfacsimile` = '" . $stellendaten['ows_contactfacsimile'] . "',
				`ows_contactaddress` = '" . $stellendaten['ows_contactaddress'] . "',
				`ows_contactpostalcode` = '" . $stellendaten['ows_contactpostalcode'] . "',
				`ows_contactcity` = '" . $stellendaten['ows_contactcity'] . "',
				`ows_contactadministrativearea` = '" . $stellendaten['ows_contactadministrativearea'] . "',
				`ows_contentorganization` = '" . $stellendaten['ows_contentorganization'] . "',
				`ows_contenturl` = '" . $stellendaten['ows_contenturl'] . "',
				`ows_contentemailaddress` = '" . $stellendaten['ows_contentemailaddress'] . "',
				`ows_contentperson` = '" . $stellendaten['ows_contentperson'] . "',
				`ows_contentposition` = '" . $stellendaten['ows_contentposition'] . "',
				`ows_contentvoicephone` = '" . $stellendaten['ows_contentvoicephone'] . "',
				`ows_contentfacsimile` = '" . $stellendaten['ows_contentfacsimile'] . "',
				`ows_contentaddress` = '" . $stellendaten['ows_contentaddress'] . "',
				`ows_contentpostalcode` = '" . $stellendaten['ows_contentpostalcode'] . "',
				`ows_contentcity` = '" . $stellendaten['ows_contentcity'] . "',
				`ows_contentadministrativearea` = '" . $stellendaten['ows_contentadministrativearea'] . "',
				`ows_geographicdescription` = '" . $stellendaten['ows_geographicdescription'] . "',
				`ows_distributionorganization` = '" . $stellendaten['ows_distributionorganization'] . "',
				`ows_distributionurl` = '" . $stellendaten['ows_distributionurl'] . "',
				`ows_distributionemailaddress` = '" . $stellendaten['ows_distributionemailaddress'] . "',
				`ows_distributionperson` = '" . $stellendaten['ows_distributionperson'] . "',
				`ows_distributionposition` = '" . $stellendaten['ows_distributionposition'] . "',
				`ows_distributionvoicephone` = '" . $stellendaten['ows_distributionvoicephone'] . "',
				`ows_distributionfacsimile` = '" . $stellendaten['ows_distributionfacsimile'] . "',
				`ows_distributionaddress` = '" . $stellendaten['ows_distributionaddress'] . "',
				`ows_distributionpostalcode` = '" . $stellendaten['ows_distributionpostalcode'] . "',
				`ows_distributioncity` = '" . $stellendaten['ows_distributioncity'] . "',
				`ows_distributionadministrativearea` = '" . $stellendaten['ows_distributionadministrativearea'] . "',
				`ows_fees` = '" . $stellendaten['ows_fees'] . "',
				`ows_inspireidentifiziert` = '" . ($stellendaten['ows_inspireidentifiziert'] == '1' ? "1" : "0") . "',
				`ows_srs` = '" . preg_replace(array('/: +/', '/ +:/'), ':', $stellendaten['ows_srs']) . "',
				`wappen_link` = '" . $stellendaten['wappen_link'] . "',
				`check_client_ip` =				'" . ($stellendaten['checkClientIP'] 			== '1'	? "1" : "0") . "',
				`check_password_age` =		'" . ($stellendaten['checkPasswordAge'] 	== '1'	? "1" : "0") . "',
				`use_layer_aliases` = 		'" . (value_of($stellendaten, 'use_layer_aliases') 	== '1'	? "1" : "0") . "',
				`hist_timestamp` = 				'" . (value_of($stellendaten, 'hist_timestamp') 		== '1'	? "1" : "0") . "',
				`allowed_password_age` = 	'" . ($stellendaten['allowedPasswordAge'] != '' 	? $stellendaten['allowedPasswordAge'] : "6") . "',
				`default_user_id` = " . ($stellendaten['default_user_id'] != '' ? $stellendaten['default_user_id'] : 'NULL') . ",
				`show_shared_layers` = " . ($stellendaten['show_shared_layers'] ? 1 : 0) . ",
				`version` = '" . ($stellendaten['version'] == '' ? "1.0.0" : $stellendaten['version']) . "',
				`reset_password_text` = '" . $stellendaten['reset_password_text'] . "',
				`invitation_text` = '" . $stellendaten['invitation_text'] . "',
				`comment` = '" . $stellendaten['comment'] . "'
			WHERE
				ID = " . $this->id . "
		";

		#echo '<br>sql' . $sql;
		# Abfrage starten
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1].='<br>Die Stellendaten konnten nicht eingetragen werden.<br>'.$ret[1];
		}
		return $ret[1];
	}

	function metadaten_aendern($stellendaten) {
		$sql = "
			UPDATE
				stelle
			SET
				`ows_title` = '" . $stellendaten['ows_title'] . "',
				`ows_namespace` = '" . $stellendaten['ows_namespace'] . "',
				`ows_abstract` = '" . $stellendaten['ows_abstract'] . "',
				`wms_accessconstraints` = '" . $stellendaten['wms_accessconstraints'] . "',
				`ows_contactorganization` = '" . $stellendaten['ows_contactorganization'] . "',
				`ows_contacturl` = '" . $stellendaten['ows_contacturl'] . "',
				`ows_contactemailaddress` = '" . $stellendaten['ows_contactemailaddress'] . "',
				`ows_contactperson` = '" . $stellendaten['ows_contactperson'] . "',
				`ows_contactposition` = '" . $stellendaten['ows_contactposition'] . "',
				`ows_contactvoicephone` = '" . $stellendaten['ows_contactvoicephone'] . "',
				`ows_contactfacsimile` = '" . $stellendaten['ows_contactfacsimile'] . "',
				`ows_contactaddress` = '" . $stellendaten['ows_contactaddress'] . "',
				`ows_contactpostalcode` = '" . $stellendaten['ows_contactpostalcode'] . "',
				`ows_contactcity` = '" . $stellendaten['ows_contactcity'] . "',
				`ows_contactadministrativearea` = '" . $stellendaten['ows_contactadministrativearea'] . "',
				`ows_contentorganization` = '" . $stellendaten['ows_contentorganization'] . "',
				`ows_contenturl` = '" . $stellendaten['ows_contenturl'] . "',
				`ows_contentemailaddress` = '" . $stellendaten['ows_contentemailaddress'] . "',
				`ows_contentperson` = '" . $stellendaten['ows_contentperson'] . "',
				`ows_contentposition` = '" . $stellendaten['ows_contentposition'] . "',
				`ows_contentvoicephone` = '" . $stellendaten['ows_contentvoicephone'] . "',
				`ows_contentfacsimile` = '" . $stellendaten['ows_contentfacsimile'] . "',
				`ows_contentaddress` = '" . $stellendaten['ows_contentaddress'] . "',
				`ows_contentpostalcode` = '" . $stellendaten['ows_contentpostalcode'] . "',
				`ows_contentcity` = '" . $stellendaten['ows_contentcity'] . "',
				`ows_contentadministrativearea` = '" . $stellendaten['ows_contentadministrativearea'] . "',
				`ows_geographicdescription` = '" . $stellendaten['ows_geographicdescription'] . "',
				`ows_distributionorganization` = '" . $stellendaten['ows_distributionorganization'] . "',
				`ows_distributionurl` = '" . $stellendaten['ows_distributionurl'] . "',
				`ows_distributionemailaddress` = '" . $stellendaten['ows_distributionemailaddress'] . "',
				`ows_distributionperson` = '" . $stellendaten['ows_distributionperson'] . "',
				`ows_distributionposition` = '" . $stellendaten['ows_distributionposition'] . "',
				`ows_distributionvoicephone` = '" . $stellendaten['ows_distributionvoicephone'] . "',
				`ows_distributionfacsimile` = '" . $stellendaten['ows_distributionfacsimile'] . "',
				`ows_distributionaddress` = '" . $stellendaten['ows_distributionaddress'] . "',
				`ows_distributionpostalcode` = '" . $stellendaten['ows_distributionpostalcode'] . "',
				`ows_distributioncity` = '" . $stellendaten['ows_distributioncity'] . "',
				`ows_distributionadministrativearea` = '" . $stellendaten['ows_distributionadministrativearea'] . "',
				`ows_fees` = '" . $stellendaten['ows_fees'] . "',
				`ows_inspireidentifiziert` = '" . ($stellendaten['ows_inspireidentifiziert'] == '1' ? "1" : "0") . "',
				`ows_srs` = '" . preg_replace(array('/: +/', '/ +:/'), ':', $stellendaten['ows_srs']) . "'
			WHERE
				ID = " . $this->id . "
		";

		#echo '<br>SQL zum Updaten der Stellenmetadaten' . $sql;
		# Abfrage starten
		$ret = $this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1] .= '<br>Die Stellendaten konnten nicht eingetragen werden.<br>' . $ret[1];
		}
		return $ret[1];
	}

	function getStellen($order, $user_id = 0, $where = "1") {
		global $admin_stellen;
		$stellen = array(
			'ID' => array(),
			'index' => array(),
			'Bezeichnung' => array(),
			'show_shared_layers' => array(),
			'Bezeichnung_parent' => array()
		);
		$sql = "
			SELECT
				s.ID,
				s.Bezeichnung,
				s.show_shared_layers,
				(
					SELECT 
						group_concat(es.Bezeichnung)
					FROM 
						`stellen_hierarchie` AS h,
						stelle es
					WHERE
						s.`ID` = h.`child_id` AND 
						es.ID = h.parent_id
				) as Bezeichnung_parent,
				(
					SELECT
						max(last_time_id)
					FROM
						`rolle`
					WHERE
					`rolle`.stelle_id = s.ID
				) as last_time_id
			FROM
				`stelle` AS s" . (($user_id > 0 AND !in_array($this->id, $admin_stellen)) ? " LEFT JOIN
				`rolle` AS r ON s.ID = r.stelle_id
				" : "") . "
			WHERE " .
				$where . (($user_id > 0 AND !in_array($this->id, $admin_stellen)) ? " AND
				(r.user_id = " . $user_id . " OR r.stelle_id IS NULL)" : "") . "
			ORDER BY " .
				($order != '' ? $order : "s.`Bezeichnung`") . "
		";
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:stelle.php class:stelle->getStellen - Abfragen aller Stellen<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$i = 0;
		while($rs = $this->database->result->fetch_array()) {
			$stellen['ID'][] = $rs['ID'];
			$stellen['index'][$rs['ID']] = $i;
			$stellen['Bezeichnung'][] = $rs['Bezeichnung'];
			$stellen['show_shared_layers'][] = $rs['show_shared_layers'];
			$stellen['Bezeichnung_parent'][] = $rs['Bezeichnung_parent'];
			$stellen['last_time_id'][] = $rs['last_time_id'];
			$i++;
		}
		return $stellen;
	}
	
	function getStellenhierarchie() {
		$this->links = Array();
		$sql = "
			SELECT
				*
			FROM
				`stellen_hierarchie`
		";
		$this->debug->write("<p>file:stelle.php class:stelle->getStellenhierarchie - <br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return array(); }
		while($rs = $this->database->result->fetch_assoc()) {
			$this->links[$rs['parent_id']][] = $rs['child_id'];
		};
		
		$this->multi_parent_childs = Array();	# die Stellen, die mehrere Eltern haben
		$this->clusters = Array();						# Stellen-Cluster
		$cluster_of_root = Array();						# ein Array, dass den Clusterindex zu jeder Wurzel angibt
		$this->all_childs_of = Array();				# Kinder und Kindeskinder jeder Stelle
		$this->all_childs = Array();					# alle Kindstellen mit ihren direkten Eltern

		# zu jeder Elternstelle alle Kindstellen finden (Kindeskinder) und in $this->all_childs_of speichern
		# und gleichzeitig die Stellen ermitteln, die mehrere Eltern haben und in $this->multi_parent_childs speichern
		foreach ($this->links as $parent => $childs) {
			if (!array_key_exists($parent, $this->all_childs)) {
				$this->getAllChildren($parent);
			}
		}
		
		# die Eltern, die selber Kinder sind, wieder aus $this->all_childs_of entfernen
		foreach ($this->all_childs_of as $parent => $childs) {
			if (array_key_exists($parent, $this->all_childs)) {
				unset($this->all_childs_of[$parent]);
			}
		}
		# in $this->all_childs_of bleiben die Stellen übrig, die keine Eltern haben (Wurzeln)
		
		# jetzt muss ermittelt werden, welche Stellenbäume mit einander verbunden sind
		# dazu werden ausgehend von den $this->multi_parent_childs die Bäume nach oben durchlaufen und am Ende die Wurzeln ermittelt, die weiter unten verbunden sind
		# diese Stellen werden zu einem Cluster zusammengefasst
		foreach ($this->multi_parent_childs as $multi_child) {
			$cluster_index = -1;
			$connected_roots = $this->getHighestParents($multi_child);
			if (count($connected_roots) > 1) {
				$cluster = Array();
				foreach ($connected_roots as $root) {
					if (!array_key_exists($root, $cluster_of_root)) {
						# alle noch nicht zu einem Cluster zugeordneten, verbundenen Wurzeln zu einem temporären Cluster zusammensammeln
						$cluster = array_unique(array_merge($cluster, array_merge(array($root), $this->all_childs_of[$root])));
						unset($this->all_childs_of[$root]);
					}
					else {
						$cluster_index = $cluster_of_root[$root];		# wenn diese Wurzel schon einem Cluster zugeordnet wurde, Clusterindex merken
					}
				}
				if (!empty($cluster)) {
					if ($cluster_index == -1) {	# kein Cluster gefunden, zu dem mind. eine Wurzel gehört -> neues Cluster anlegen
						$this->clusters[] = Array();
						$cluster_index = count($this->clusters) - 1;
					}
					foreach ($connected_roots as $root) {
						# alle Wurzeln zum Cluster zuordnen
						$cluster_of_root[$root] = $cluster_index;
					}
					# das temporäre Cluster dem Cluster mit dem Clusterindex hinzufügen
					$this->clusters[$cluster_index] = array_unique(array_merge($this->clusters[$cluster_index] ?: [], $cluster));
				}
			}
		}
		
		# jetzt noch die Bäume zum Cluster-Array hinzufügen, die nur eine Wurzel haben
		foreach ($this->all_childs_of as $root => $childs) {
			$this->clusters[] = array_merge(array($root), $this->all_childs_of[$root]);
		}
		
		return ['clusters' => $this->clusters, 'links' => $this->links];
	}
	
	function getHighestParents($child){
		$parents = Array();
		if (!array_key_exists($child, $this->all_childs)){
			$parents[] = $child;
		}
		else {
			foreach ($this->all_childs[$child] as $parent) {
				$parents = array_unique(array_merge($parents, $this->getHighestParents($parent)));
			}
		}
		return $parents;
	}
	
	function getAllChildren($parent){
		if (array_key_exists($parent, $this->links)) {
			foreach ($this->links[$parent] as $child) {
				$this->all_childs_of[$parent][] = $child;
				$this->all_childs_of[$parent] = array_merge($this->all_childs_of[$parent], $this->all_childs_of[$child] ?: $this->getAllChildren($child));
				$this->all_childs[$child][] = $parent;
				if (count($this->all_childs[$child]) == 2) {
					$this->multi_parent_childs[] = $child;
				}
			}
		}
		return $this->all_childs_of[$parent] ?: [];
	}	

	function getParents($order = '', $return = '') {
		$parents = array();
		$sql = "
			SELECT
				s.`ID`,
				s.`Bezeichnung`
			FROM
				`stelle` AS s JOIN
				`stellen_hierarchie` AS h ON (s.`ID` = h.`parent_id`)
			WHERE
				h.`child_id`= ".$this->id." "
			.$order;
		#echo '<br>stelle.php getParents sql:<br>' . $sql;

		$this->debug->write("<p>file:stelle.php class:stelle->getParents - Abfragen aller Elternstellen<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return array(); }
		while($rs = $this->database->result->fetch_assoc()) {
			$parents[] = ($return == 'only_ids' ? $rs['ID'] : $rs);
		};
		return $parents;
	}

	function getChildren($parent_id, $order = '', $return = '', $recursive = false, $loop_test = false, $loop_counter = 0) {
		$children = array();
		$sql = "
			SELECT
				s.`ID`,
				s.`Bezeichnung`
			FROM
				`stelle` AS s JOIN
				`stellen_hierarchie` AS h ON (s.`ID` = h.`child_id`)
			WHERE
				h.`parent_id`= ".$parent_id." "
			.$order;
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:stelle.php class:getChildren - Abfragen aller Kindstellen<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return array(); }
		$result = $this->database->result;
		while($rs = $result->fetch_assoc()) {
			if ($loop_counter == 1000) {
				if ($loop_test) {
					GUI::add_message_('error', 'Achtung! Es gibt einen Zirkelbezug in der Stellenhierarchie!');
				}
				return [];
			}
			$children[] = ($return == 'only_ids' ? $rs['ID'] : $rs);
			if($recursive){
				$children = array_merge($children, $this->getChildren($rs['ID'], $order, $return, true, $loop_test, $loop_counter++));
			}
		};
		return $children;
	}

	function getFunktionen($return = '') {
		$funktionen = array();
		# Abfragen der Funktionen, die in der Stelle ausgeführt werden dürfen
		$sql = "
			SELECT
				f.id,
				f.bezeichnung,
				1 as erlaubt
			FROM
				u_funktionen AS f,
				u_funktion2stelle AS f2s
			WHERE
				f.id=f2s.funktion_id AND
				f2s.stelle_id = " . $this->id . "
			ORDER BY bezeichnung
		";
		#echo '</script>SQL zur Abfrage der Funktionen: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getFunktionen - Fragt die Funktionen der Stelle ab:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Abfrage der Funktionen für die Stelle';
			return $errmsg;
		}
		else {
			while($rs=$this->database->result->fetch_array()) {
				if ($return == 'only_ids') {
					$funktionen[] = $rs['id'];
				}
				else {
					$funktionen[$rs['bezeichnung']]=$rs;
					$funktionen['array'][]=$rs;
				}
			}
		}
		$this->funktionen=$funktionen;
		return $funktionen;
	}

	function isFunctionAllowed($functionname) {
		if ($this->funktionen == NULL) {
			$this->getFunktionen();
		}
		if ($this->funktionen[$functionname]['erlaubt']) {
			return 1;
		}
		else {
			return 0;
		}
	}

	function isMenueAllowed($menuename){
		$sql = "
			SELECT
				distinct a.*
			FROM
				u_menues as a,
				u_menue2stelle as b
			WHERE
				links LIKE 'index.php?go=" . $menuename . "%' AND
				b.menue_id = a.id AND
				b.stelle_id = " . $this->id . "
		";
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->isMenueAllowed - Guckt ob der Menuepunkt der Stelle zugeordnet ist:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Ueberpruefung des Menuepunkts für die Stelle';
		}
		else{
			$rs=$this->database->result->fetch_array();
		}
		if($rs[0] != '') {
			return 1;
		}
		else {
			return 0;
		}
	}

	function getFlurstueckeAllowed($FlurstKennz, $database) {
		include_once(PLUGINS.'alkis/model/alkis.php');
		$GemeindenStelle = $this->getGemeindeIDs();
		if (!empty($GemeindenStelle['ganze_gemeinde']) OR !empty($GemeindenStelle['ganze_gemarkung']) OR !empty($GemeindenStelle['eingeschr_gemarkung'])) {   // Stelle ist auf Gemeinden eingeschränkt
			$alkis = new alkis($database);
			$ret=$alkis->getFlurstKennzByGemeindeIDs($GemeindenStelle, $FlurstKennz);
			if ($ret[0]==0) {
				$anzFlurstKennz = count_or_0($ret[1]);
				if ($anzFlurstKennz==0) {
					$ret[0]=1;
					$ret[1]="Sie haben keine Berechtigung zur Ansicht diese(s)r Flurstücke(s)";
				}
			}
			# ret[0] = 1 wenn Fehler in Datenbankabfrage oder keine FlurstKennz zurück
			# ret[1] = Fehlermeldung oder Liste FlurstKennz
		}
		else{
			$ret[1] = $FlurstKennz;
		}
		return $ret;
	}

	/**
	 * Add inheritted menues, functions, layouts, layers and users that not allready exists in formvars
	 * Remove inheritted menues, functions, layouts, layers and users that currently exists in formvars
	 * @param int[] $selected_parents Array of ids from parent stellen.
	 */
	function apply_parent_selection(
		$selected_parents,
		&$menues,
		&$functions,
		&$frames,
		&$layouts,
		&$layer
	) {
		include_once(CLASSPATH . 'datendrucklayout.php');
		$results = array();
		$old_parents = $this->getParents('ORDER BY `ID`', 'only_ids');
		$document = new Document($this->database);
		$ddl = new ddl($this->database);
				
		# immer alle Elternstellen und deren Zuordnungen entfernen und wieder neu hinzufügen
		foreach($old_parents AS $drop_parent_id) {
			$parent_stelle = new stelle($drop_parent_id, $this->database);
			$menues = array_values(array_diff($menues, $parent_stelle->getMenue(0, 'only_ids')));
			$functions = array_values(array_diff($functions, $parent_stelle->getFunktionen('only_ids')));
			$layouts = array_values(array_diff($layouts, $ddl->load_layouts($drop_parent_id, '', '', '', 'only_ids')));
			$frames = array_values(array_diff($frames, $document->load_frames($drop_parent_id, false, 'only_ids')));
			$parent_layer = $parent_stelle->getLayer('', 'only_ids');
			$layer = array_values(array_diff($layer, $parent_layer));
			$this->dropParent($drop_parent_id);
		}
		
		# Füge Einstellungen der Elternstellen zur Stelle hinzu
		foreach($selected_parents AS $new_parent_id) {
			$parent_stelle = new stelle($new_parent_id, $this->database);
			$menues = $this->sort_menues(array_merge($menues, $parent_stelle->getMenue(0, 'only_ids')));
			$functions = array_values(array_unique(array_merge($functions, $parent_stelle->getFunktionen('only_ids'))));
			$layouts = array_values(array_unique(array_merge($layouts, $ddl->load_layouts($new_parent_id, '', '', '', 'only_ids'))));
			$frames = array_values(array_unique(array_merge($frames, $document->load_frames($new_parent_id, false, 'only_ids'))));
			$layer = array_values(array_unique(array_merge($layer, $parent_stelle->getLayer('', 'only_ids'))));
			$results[] = $this->addParent($new_parent_id);
		}
		return $results;
	}

	function sort_menues($menues){
		# sortiert zunächst die Menüs von Ebene 1 nach order und dann innerhalb der Obermenüpunkte die Untermenüpunkte nach order
		$sql = '
			SELECT 
				CASE WHEN m.menueebene = 1 THEN m.`order` ELSE om.`order` END as order1, 
				CASE WHEN m.menueebene = 1 THEN m.`id` ELSE m.`obermenue` END as order2,
				m.`id`
			FROM `u_menues` as m 
			LEFT JOIN `u_menues` as om ON om.id = m.obermenue
			WHERE
				m.id IN (' . implode(',', $menues) . ')
			ORDER BY order1, order2, m.menueebene, m.`order`
		';
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=$this->database->result->fetch_array()) {
				$result[] = $rs['id'];
			}
		}
		return $result;
	}

	function addParent($parent_id) {
		$sql = "
			INSERT INTO `stellen_hierarchie` (
				`parent_id`,
				`child_id`
			)
			VALUES (
				" . $parent_id . ",
				" . $this->id . "
			)
		";
		#echo 'Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->addParent - Add Parent Id: " . $parent_id . " zu Stelle Id: " . $this->id . "<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Eintragen der Elternstelle: ' . $this->databse->errormessage
			);
		}

		return array(
			'type' => 'notice',
			'message' => 'Elternstelle Id: ' . $parent_id . ' erfolgreich zugewiesen.'
		);
	}

	function dropParent($drop_parent_id) {
		$sql = "
			DELETE FROM `stellen_hierarchie`
			WHERE
				`parent_id` = " . $drop_parent_id . " AND
				`child_id` = " . $this->id . "
		";
		#echo '<p>stelle.php dropParent(' . $drop_parent_id . ') Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->dropParent - Delete Parent Id: " . $drop_parent_id . " von Stelle Id: " . $this->id . "<br>", 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Löschen der Elternstelle: ' . $this->databse->errormessage
			);
		}
		return array(
			'type' => 'notice',
			'message' => 'Elternstelle ID: ' . $drop_parent_id . ' erfolgreich entfernt.'
		);
	}
	
	function addChild($child_id) {
		$sql = "
			INSERT INTO `stellen_hierarchie` (
				`parent_id`,
				`child_id`
			)
			VALUES (
				" . $this->id . ",
				" . $child_id . "
			)
		";
		#echo 'Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->addChild - Add Child Id: " . $child_id . " zu Stelle Id: " . $this->id . "<br>", 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Eintragen der Kindstelle: ' . $this->databse->errormessage
			);
		}

		return array(
			'type' => 'notice',
			'message' => 'Kindstelle ID: ' . $child_id . ' erfolgreich zugewiesen.'
		);
	}	
	
	function dropChild($drop_child_id) {
		$sql = "
			DELETE FROM `stellen_hierarchie`
			WHERE
				`parent_id` = " . $this->id . " AND
				`child_id` = " . $drop_child_id . "
		";
		$this->debug->write("<p>file:stelle.php class:stelle->dropChild - Delete Child Id: " . $drop_child_id . " von Stelle Id: " . $this->id . "<br>", 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Löschen der Kindstelle: ' . $this->databse->errormessage
			);
		}
		return array(
			'type' => 'notice',
			'message' => 'Kindstelle ID: ' . $drop_child_id . ' erfolgreich entfernt.'
		);
	}	

	/*
	* Hinzufügen von Menuepunkten zur Stelle
	*/
	function addMenue($menue_ids) {
		$sql = "
			SELECT
				MAX(menue_order)
			FROM
				u_menue2stelle
			WHERE
				stelle_id = " . $this->id . "
		";
		#echo '<br>stelle.php addMenue Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->addMenue - Lesen der maximalen menue_order der Menuepunkte der Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		else {
			$rs = $this->database->result->fetch_array();
		}
		$count = ($rs[0] == '' ? 0 : $rs[0]);
		for ($i = 0; $i <@ count($menue_ids); $i++) {
			$sql ="
				INSERT IGNORE INTO
					u_menue2stelle (
						`stelle_id`,
						`menue_id`,
						`menue_order`
					)
				VALUES (
					'" . $this->id ."',
					'" . $menue_ids[$i] . "',
					'" . $count . "'
				)
			";
			#echo '<br>stelle.php addMenue Sql: ' . $sql;
			$count++;
			$this->debug->write("<p>file:stelle.php class:stelle->addMenue - Hinzufügen von Menuepunkten zur Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function getMenue($ebene, $return = '') {
		global $language;
		$menue['ID'] = array();
		# Lesen der Menuepunkte zur Stelle
		if ($language != 'german') {
			$name_column = "
			CASE
				WHEN m.`name_" . $language . "` != \"\" THEN m.`name_" . $language . "`
				ELSE m.`name`
			END AS `name`";
		}
		else
			$name_column = "m.`name`";

		$sql = "
			SELECT
				`menue_id`," .
				$name_column . ",
				`menueebene`,
				`order`
			FROM
				`u_menues` m JOIN
				`u_menue2stelle` m2s ON m.`id` = m2s.`menue_id`
			WHERE
				m2s.`stelle_id` = " . $this->id .
				($ebene != 0 ? " AND menueebene = " . $ebene : "") . "
			ORDER BY
				menue_order
		";
		#echo '<br>stelle.php getMenue(' . $ebene . ') Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getMenue - Lesen der Menuepunkte zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=$this->database->result->fetch_array()) {
				$menue['ID'][]=$rs['menue_id'];
				$menue['ORDER'][]=$rs['order'];
				$menue['menueebene'][]=$rs['menueebene'];
				if($rs['menueebene'] == 2){
					$menue['Bezeichnung'][]='&nbsp;&nbsp;-->&nbsp;'.$rs['name'];
				}
				else{
					$menue['Bezeichnung'][]=$rs['name'];
				}
			}
		}
		if ($return == 'only_ids') {
			return $menue['ID'];
		} else {
			return $menue;
		}
	}

	function copyLayerfromStelle($layer_ids, $alte_stelle_id){
		# kopieren der Layer von einer Stelle
		for ($i = 0; $i < count($layer_ids); $i++) {
			# usedlayer
			$columns = '
				`Layer_ID`, 
				`queryable`, 
				`legendorder`,
				`minscale`, 
				`maxscale`, 
				`offsite`, 
				`transparency`, 
				`postlabelcache`, 
				`Filter`, 
				`template`, 
				`header`, 
				`footer`, 
				`symbolscale`, 
				`logconsume`, 
				`requires`, 
				`privileg`, 
				`export_privileg`,
				`use_parent_privileges`,
				`start_aktiv`,
				`use_geom`
			';
			$sql = '
				INSERT IGNORE INTO used_layer ( 
					`Stelle_ID` , 
					' . $columns . ')
				SELECT 
					'.$this->id.', 
					' . $columns . '
				FROM 
					used_layer 
				WHERE 
					Stelle_ID = '.$alte_stelle_id.' AND 
					Layer_ID = '.$layer_ids[$i];
			$this->debug->write("<p>file:stelle.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }

			# Layerattributrechte
			$sql = '
				INSERT IGNORE INTO layer_attributes2stelle (
					layer_id, 
					attributename, 
					stelle_id, 
					privileg, 
					tooltip)
				SELECT 
					layer_id, 
					attributename, 
					'.$this->id.', 
					privileg, 
					tooltip 
				FROM 
					layer_attributes2stelle 
				WHERE 
					stelle_id = '.$alte_stelle_id.' AND 
					layer_id = '.$layer_ids[$i];
			$this->debug->write("<p>file:stelle.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }

			#  u_attributfilter2used_layer
			$sql = '
				INSERT IGNORE INTO u_attributfilter2used_layer (
					`Stelle_ID`, 
					`Layer_ID`, 
					`attributname`, 
					`attributvalue`, 
					`operator`, 
					`type`)
				SELECT
					'.$this->id.', 
					`Layer_ID`, 
					`attributname`, 
					`attributvalue`, 
					`operator`, 
					`type`
				FROM 
				u_attributfilter2used_layer 
				WHERE 
					stelle_id = '.$alte_stelle_id.' AND 
					layer_id = '.$layer_ids[$i];
			$this->debug->write("<p>file:stelle.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function addFunctions($function_ids){
		# Hinzufügen von Funktionen zur Stelle
		for ($i=0;$i<count($function_ids);$i++) {
			$sql ='INSERT IGNORE INTO u_funktion2stelle ( `funktion_id` , `stelle_id`)';
			$sql.="VALUES ('".$function_ids[$i]."', '".$this->id."')";
			$this->debug->write("<p>file:stelle.php class:stelle->addFunctions - Hinzufügen von Funktionen zur Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function removeFunctions(){
		# Entfernen von Funktionen zur Stelle
		$sql ='DELETE FROM u_funktion2stelle ';
		$sql.='WHERE stelle_id = '.$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->removeFunctions - Entfernen von Funktionen zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	/**
	 * Function assign layers with $layer_ids to this stelle
	 * ToDo sr: Beschreibung der 3 verschiedenen Fälle hinzufügen
	 * - !$assign_default_values
	 * - $assign_default_values OR $this->database->mysqli->affected_rows == 0
	 * - !$assign_default_values AND $this->database->mysqli->affected_rows > 0
	 * If $assign_default_values are defined these will be used for used_layers table
	 * @param Array{String} $layer_ids
	 * @param Integer $drawinorder
	 * @param String $filter
	 * @param Boolean $assign_default_values
	 * @param String $privileg default 'default'
	 * @return Boolean Always 1
	 */
	function addLayer($layer_ids, $filter = '', $assign_default_values = false, $privileg = 'default') {
		#echo '<br>stelle.php addLayer ids: ' . implode(', ', $layer_ids);
		# Hinzufügen von Layern zur Stelle
		for ($i = 0; $i < count($layer_ids); $i++) {
			$insert = "(
				`Stelle_ID`,
				`Layer_ID`,
				`queryable`,
				`use_geom`,
				`legendorder`,
				`minscale`,
				`maxscale`,
				`symbolscale`,
				`offsite`,
				`transparency`,
				`Filter`,
				`template`,
				`header`,
				`footer`,
				`privileg`,
				`export_privileg`,
				`postlabelcache`,
				`requires`,
				`start_aktiv`
			)";
			if (!$assign_default_values) {
				# Einstellungen von der Elternstelle übernehmen
				$sql = "
					INSERT INTO used_layer " . $insert . "
					SELECT
						'" . $this->id . "',
						'" . $layer_ids[$i] . "',
						queryable,
						use_geom,
						legendorder, 
						minscale, 
						maxscale, 
						symbolscale, 
						offsite, 
						transparency, 
						filter,
						template, 
						header,
						footer,
						`privileg`,
						`export_privileg`,
						postlabelcache,
						requires,
						`start_aktiv`
					FROM
						used_layer as l,
						stellen_hierarchie
					WHERE
						COALESCE((select use_parent_privileges from used_layer where layer_id = " . $layer_ids[$i] . " AND stelle_id = " . $this->id . "), 1) AND
						layer_id = " . $layer_ids[$i] . " AND
						stelle_id = parent_id AND
						child_id = " . $this->id . "
					ON DUPLICATE KEY UPDATE 
						queryable = l.queryable, 
						use_geom = l.use_geom, 
						legendorder = l.legendorder, 
						minscale = l.minscale, 
						maxscale = l.maxscale, 
						symbolscale = l.symbolscale, 
						offsite = l.offsite, 
						transparency = l.transparency, 
						template = l.template, 
						postlabelcache = l.postlabelcache,
						`privileg` = l.`privileg`,
						`export_privileg` = l.`export_privileg`,
						requires = l.requires
				";
				// echo $sql.'<br><br>';
				$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) {
					$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
					return 0;
				}
			}
			if ($assign_default_values OR $this->database->mysqli->affected_rows == 0) {
				# wenn nicht von Elternstelle übernommen, Defaulteinstellungen übernehmen bzw. ignorieren, falls schon vorhanden
				$sql = "INSERT " . (!$assign_default_values ? "IGNORE" : "") . " INTO used_layer " . $insert . "
					SELECT
						'" . $this->id . "',
						'" . $layer_ids[$i] . "',
						queryable,
						use_geom,
						legendorder, 
						minscale, 
						maxscale, 
						symbolscale, 
						offsite, 
						transparency, 
						'" . $filter . "',
						template, 
						NULL,
						NULL,
						" . ($privileg == 'editable'? "'1'" : 'privileg') . ",
						`export_privileg`,
						postlabelcache,
						requires,
						'0'
					FROM
						layer as l
					WHERE
						l.Layer_ID = ".$layer_ids[$i];
					if($assign_default_values){
						$sql .= "
						ON DUPLICATE KEY UPDATE 
							queryable = l.queryable, 
							use_geom = l.use_geom, 
							legendorder = l.legendorder, 
							minscale = l.minscale, 
							maxscale = l.maxscale, 
							symbolscale = l.symbolscale, 
							offsite = l.offsite, 
							transparency = l.transparency, 
							template = l.template, 
							postlabelcache = l.postlabelcache,
							requires = l.requires
						";
					}
				#echo '<br>SQL zur Zuordnung eines Layers zur Stelle: ' . $sql;
				$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) {
					$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
					return 0;
				}
			}

			if (!$assign_default_values AND $this->database->mysqli->affected_rows > 0) {
				$insert = "
					INSERT IGNORE INTO layer_attributes2stelle (
						layer_id,
						attributename,
						stelle_id,
						privileg,
						tooltip
					)
				";
				# Rechte von der Elternstelle übernehmen (bei mehreren Elternstellen die höchsten Rechte)
				$sql = $insert . "
					SELECT * FROM (
						SELECT 
							layer_id,
							attributename,
							" . $this->id . ",
							max(privileg) as privileg,
							max(tooltip) as tooltip
						FROM
							layer_attributes2stelle l,
							stellen_hierarchie
						WHERE
							(select use_parent_privileges from used_layer where layer_id = " . $layer_ids[$i] . " AND stelle_id = " . $this->id . ") AND
							layer_id = " . $layer_ids[$i] . " AND
							stelle_id = parent_id AND
							child_id = " . $this->id . "
						  GROUP BY attributename
					) as foo
					ON DUPLICATE KEY UPDATE
						layer_id = foo.layer_id, 
						attributename = foo.attributename, 
						stelle_id = " . $this->id . ", 
						privileg = foo.privileg, 
						tooltip = foo.tooltip
					";
				#echo 'SQL zum Anlegen eines used layers :' . $sql . '<br>';
				$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) {
					$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
					return 0;
				}
				if ($this->database->mysqli->affected_rows != 0) {
					# löschen der Einträge für "kein Zugriff" Rechte
					$sql = "
					DELETE l 
					FROM 
						layer_attributes2stelle l 
						LEFT JOIN (
							SELECT 
								layer_id, stelle_id, attributename 
							FROM 
								layer_attributes2stelle l2 
								JOIN stellen_hierarchie ON 
								" . $this->id . " = child_id
								WHERE
								l2.layer_id = " . $layer_ids[$i] . " AND 
								l2.stelle_id = parent_id
						) as foo ON 
							l.layer_id = foo.layer_id AND
							l.attributename = foo.attributename
					WHERE
						l.layer_id = " . $layer_ids[$i] . " AND 
							l.stelle_id = " . $this->id . " AND
							foo.layer_id IS NULL;
						";
					#echo $sql.'<br>';
					$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
					$this->database->execSQL($sql);
					if (!$this->database->success) {
						$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
						return 0;
					}
				}
				else {
					# wenn nicht von Elternstelle übernommen, Defaultrechte übernehmen
					$sql = $insert . "
						SELECT 
							" . $layer_ids[$i] . ",
							name,
							" . $this->id . ",
							" . ($privileg == 'editable'? '1' : 'privileg') . ",
							query_tooltip 
						FROM 
							layer_attributes 
						WHERE 
							layer_id = ".$layer_ids[$i]." AND 
							privileg IS NOT NULL";
				}
				#echo $sql.'<br>';
				$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) {
					$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
					return 0;
				}
			}
		}
		return 1;
	}
	
	/**
	 * check if layer_params are used in any layer or menue of stelle
	 * and set the ids in selectable_layer_params of stelle
	 */
	function updateLayerParams() {
		$sql = "
			UPDATE stelle
			SET
				selectable_layer_params = COALESCE((
					SELECT GROUP_CONCAT(id)
					FROM
						(
							SELECT DISTINCT
								id
							FROM
								(
									SELECT
										id
										FROM
										`layer_parameter` as p,
										used_layer as ul,
										layer as l
									--	LEFT JOIN layer_attributes la ON la.layer_id = l.Layer_ID
									WHERE
										ul.Stelle_ID = " . $this->id . " AND
										ul.Layer_ID = l.Layer_ID AND
										(
											locate(
												concat('$', p.key),
												concat(l.Name, COALESCE(l.alias, ''), l.schema, l.connection, l.Data, l.pfad, l.classitem, l.classification, l.maintable, l.tileindex, COALESCE(l.connection, ''), COALESCE(l.processing, ''))
											) > 0
										-- OR						-- aus Performancegründen rausgenommen
										-- 	locate(
										-- 		concat('$', p.key),
										-- 		concat(la.options, la.default)
										-- 	) > 0
										)
									UNION
									SELECT
										p.id
									FROM
										u_menues AS m JOIN
										u_menue2stelle AS m2s ON (m.id = m2s.menue_id) JOIN
										layer_parameter AS p ON (
											locate(
												concat('$', p.key),
												m.links
											) > 0
										)
									WHERE
										m2s.stelle_id = " . $this->id . "
								) AS params
						) AS foo
					),
					''
				)
			WHERE stelle.ID = " . $this->id . "
		";
		// echo '<br>SQL zur Aktualisierung der selectable_layer_params: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayerParams:<br>".$sql,4);

		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
	}

	function updateLayer($formvars) {
		# Aktualisieren der LayerzuStelle-Eigenschaften
		$sql = "
			UPDATE
				used_layer
			SET
				`Layer_ID` 				= "  . $formvars['selected_layer_id'] . ",
				`use_geom`				= "  . $formvars['use_geom'] 					. ",
				`postlabelcache` 	= "  . $formvars['postlabelcache'] 		. ",
				`offsite` 				= '" . $formvars['offsite'] 					. "',
				`Filter` 					= '" . $formvars['Filter'] 						. "',
				`template` 				= '" . $formvars['template'] 					. "',
				`header`   				= '" . $formvars['header'] 						. "',
				`footer`   				= '" . $formvars['footer'] 						. "',
				`logconsume` 			= '" . $formvars['logconsume'] 				. "',
				`queryable`				= '" . ($formvars['queryable'] 	== '0' ? "0" : "1") . "',
				`start_aktiv` 		= '" . ($formvars['start_aktiv'] == '0' ? "0"  : "1") 	. "',
				`group_id`				= "  . ($formvars['group_id'] 					? $formvars['group_id'] : "NULL")			. ",
				`transparency` 		= "  . ($formvars['transparency'] 			? $formvars['transparency'] : "NULL") . ",
				`minscale` 				= "  . ($formvars['minscale'] 		!= '' ? $formvars['minscale'] : "NULL") 		. ",
				`maxscale` 				= "  . ($formvars['maxscale'] 		!= '' ? $formvars['maxscale'] : "NULL") 		. ",
				`symbolscale` 		= "  . ($formvars['symbolscale'] 	!= '' ? $formvars['symbolscale'] : "NULL")	. ",
				`requires` 				= "  . ($formvars['requires'] 		!= '' ? "'" . $formvars['requires'] . "'" : "NULL") . "
			WHERE
				Stelle_ID = " . $formvars['selected_stelle_id'] .  " AND
				Layer_ID = " . $formvars['selected_layer_id'] . "
		";
		//  echo $sql . '<br>';
		//  exit;
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayer - Aktualisieren der LayerzuStelle-Eigenschaften:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__, 4);
			return 0;
		}
	}

	// function updateLayer($formvars){
	// 	# Aktualisieren der LayerzuStelle-Eigenschaften
	// 	$sql = 'UPDATE used_layer SET Layer_ID = '.$formvars['selected_layer_id'];
	// 	$sql .= ', queryable = "'.$formvars['queryable'].'"';
	// 	$sql .= ', use_geom = '.$formvars['use_geom'];
	// 	if ($formvars['minscale']!='') {
	// 		$sql .= ', minscale = '.$formvars['minscale'];
	// 	}
	// 	else{
	// 		$sql .= ', minscale = NULL';
	// 	}
	// 	if ($formvars['maxscale']!='') {
	// 		$sql .= ', maxscale = '.$formvars['maxscale'];
	// 	}
	// 	else{
	// 		$sql .= ', maxscale = NULL';
	// 	}
	// 	$sql .= ', offsite = "'.$formvars['offsite'].'"';
	// 	if ($formvars['transparency']!='') {
	// 		$sql .= ', transparency = '.$formvars['transparency'];
	// 	}
	// 	else{
	// 		$sql .= ', transparency = NULL';
	// 	}
	// 	$sql .= ', postlabelcache = "'.$formvars['postlabelcache'].'"';
	// 	$sql .= ", Filter = '".$formvars['Filter']."'";
	// 	$sql .= ', template = "'.$formvars['template'].'"';
	// 	$sql .= ', header = "'.$formvars['header'].'"';
	// 	$sql .= ', footer = "'.$formvars['footer'].'"';
	// 	if ($formvars['symbolscale']!='') {
	// 		$sql .= ', symbolscale = '.$formvars['symbolscale'];
	// 	}
	// 	else{
	// 		$sql .= ', symbolscale = NULL';
	// 	}
	// 	if($formvars['requires'] == '')$formvars['requires'] = 'NULL';
	// 	$sql .= ', requires = '.$formvars['requires'];
	// 	$sql .= ', start_aktiv = "'.$formvars['startaktiv'].'"';
	// 	$sql .= ', logconsume = "'.$formvars['logconsume'].'"';
	// 	$sql .= ' WHERE Stelle_ID = '.$formvars['selected_stelle_id'].' AND Layer_ID = '.$formvars['selected_layer_id'];
	// 	#echo $sql.'<br>';
	// 	$this->debug->write("<p>file:stelle.php class:stelle->updateLayer - Aktualisieren der LayerzuStelle-Eigenschaften:<br>".$sql,4);
	// 	$this->database->execSQL($sql);
	// 	if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
	// }

	function updateLayerOrder($formvars){
		# Aktualisieren der LayerzuStelle-Eigenschaften
		$sql = '
			UPDATE 
				used_layer 
			SET 
				legendorder = ' . ($formvars['legendorder'] ?: 'NULL') . '
			WHERE 
				Stelle_ID = ' . $formvars['selected_stelle_id'] . ' AND 
				Layer_ID = ' . $formvars['selected_layer_id'];
		#echo $sql.'<br>';
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayerorder - Aktualisieren der LayerzuStelle-Eigenschaften:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
	}

  function getGroups() {
		global $language;
		$sql = 'SELECT DISTINCT';
		if($language != 'german') {
			$sql.=' CASE WHEN `Gruppenname_'.$language.'` IS NOT NULL THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS';
		}
		$sql.=' Gruppenname, obergruppe, g.id FROM u_groups AS g, u_groups2rolle AS g2r';
		$sql.=' WHERE g2r.stelle_ID='.$this->id;
		$sql.=' AND g2r.id = g.id';
		$sql.=' ORDER BY `order`';
		#echo $sql; exit;
    $this->debug->write("<p>file:kvwmap class:stelle->getGroups - Lesen der Gruppen der Stelle:<br>".$sql,4);
    $this->database->execSQL($sql);
    if (!$this->database->success) { echo "<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while ($rs=$this->database->result->fetch_assoc()) {
      $groups[$rs['id']] = array_merge($groups[$rs['id']] ?: [], $rs);
			if($rs['obergruppe'])$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
    }
    return $groups;
  }

	function getLayers($group, $order = 'ul.legendorder, l.drawingorder desc', $return = '') {
		$layer = array(
			'ID' => array(),
			'Bezeichnung' => array(),
			'drawingorder' => array(),
			'Gruppe' => array()
		);

		$condition = "
			ul.stelle_id = " . $this->id .
			($group != NULL ? " AND COALESCE(ul.group_id, l.Gruppe) = " . $group : "") . "
		";
		$order = ($order != NULL ? 'ORDER BY ' . $order : 'ORDER BY ul.legendorder, l.drawingorder desc');

		# Lesen der Layer zur Stelle
		$sql = "
			SELECT
				l.Layer_ID,
				COALESCE(ul.group_id, l.Gruppe) AS Gruppe,
				l.Name,
				l.alias,
				l.drawingorder,
				ul.legendorder
			FROM
				used_layer ul JOIN
				layer l ON ul.Layer_ID = l.Layer_ID 
			WHERE" .
				$condition .
				$order . "
		";
		#echo '<br>stelle.php getLayers Sql:<br>' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getLayers - Lesen der Layer zur Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__, 4);
			return 0;
		} else {
			$i = 0;
			while ($rs = $this->database->result->fetch_assoc()) {
				$layer['ID'][] 						= $rs['Layer_ID'];
				$layer['Name'][]					= $rs['Name'];
				$layer['alias'][]					= $rs['alias'];
				$layer['Name_or_alias']		= $rs[($rs['alias'] AND $this->useLayerAliases) ? 'alias' : 'Name'];
				$layer['Bezeichnung'][]		= $rs['Name'] . ($rs['alias']? ' [' . $rs['alias'] . ']' : '');
				$layer['drawingorder'][]	= $rs['drawingorder'];
				$layer['legendorder'][]		= $rs['legendorder'];
				$layer['Gruppe'][]				= $rs['Gruppe'];
				$layer['layers_of_group'][$rs['Gruppe']][] = $i;
				$i++;
			}
			if ($order == 'Name') {
				// Sortieren der Layer unter Berücksichtigung von Umlauten
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
				$sorted_layer['Bezeichnung'] = $sorted_arrays['array'];
				$sorted_layer['ID'] = $sorted_arrays['second_array'];

				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['drawingorder']);
				$sorted_layer['drawingorder'] = $sorted_arrays['second_array'];

				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['legendorder']);
				$sorted_layer['legendorder'] = $sorted_arrays['second_array'];

				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['Gruppe']);
				$sorted_layer['Gruppe'] = $sorted_arrays['second_array'];
				$layer = $sorted_layer;
			}
		}
		if ($return == 'only_ids') {
			return $layer['ID'];
		} else {
			return $layer;
		}
	}

	// function getLayers($group, $order = 'legendorder, drawingorder desc', $return = '') {
	// 	$layer = array(
	// 		'ID' => array(),
	// 		'Bezeichnung' => array(),
	// 		'drawingorder' => array(),
	// 		'Gruppe' => array()
	// 	);

	// 	$condition = "
	// 		stelle_id = " . $this->id .
	// 		($group != NULL ? " AND layer.Gruppe = " . $group : "") . "
	// 	";
	// 	$order = ($order != NULL ? 'ORDER BY '.$order : 'ORDER BY legendorder, drawingorder desc');

	// 	# Lesen der Layer zur Stelle
	// 	$sql = "
	// 		SELECT
	// 			layer.Layer_ID,
	// 			layer.Gruppe,
	// 			Name,
	// 			used_layer.drawingorder,
	// 			used_layer.legendorder
	// 		FROM
	// 			used_layer JOIN
	// 			layer ON used_layer.Layer_ID = layer.Layer_ID 
	// 		WHERE" .
	// 			$condition .
	// 		$order . "
	// 	";
	// 	#echo '<br>stelle.php getLayers Sql:<br>' . $sql;
	// 	$this->debug->write("<p>file:stelle.php class:stelle->getLayers - Lesen der Layer zur Stelle:<br>".$sql,4);
	// 	$this->database->execSQL($sql);
	// 	if (!$this->database->success) {
	// 		$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
	// 	}
	// 	else {
	// 		$i = 0;
	// 		while ($rs = $this->database->result->fetch_assoc()) {
	// 			$layer['ID'][] 						= $rs['Layer_ID'];
	// 			$layer['Bezeichnung'][]		= $rs['Name'];
	// 			$layer['drawingorder'][]	= $rs['drawingorder'];
	// 			$layer['legendorder'][]		= $rs['legendorder'];
	// 			$layer['Gruppe'][]				= $rs['Gruppe'];
	// 			$layer['layers_of_group'][$rs['Gruppe']][] = $i;
	// 			$i++;
	// 		}
	// 		if ($order == 'Name') {
	// 			// Sortieren der Layer unter Berücksichtigung von Umlauten
	// 			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
	// 			$sorted_layer['Bezeichnung'] = $sorted_arrays['array'];
	// 			$sorted_layer['ID'] = $sorted_arrays['second_array'];
				
	// 			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['drawingorder']);
	// 			$sorted_layer['drawingorder'] = $sorted_arrays['second_array'];
				
	// 			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['legendorder']);
	// 			$sorted_layer['legendorder'] = $sorted_arrays['second_array'];
				
	// 			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['Gruppe']);
	// 			$sorted_layer['Gruppe'] = $sorted_arrays['second_array'];
	// 			$layer = $sorted_layer;
	// 		}
	// 	}
	// 	if ($return == 'only_ids') {
	// 		return $layer['ID'];
	// 	}
	// 	else {
	// 		return $layer;
	// 	}
	// }

	function getqueryablePostgisLayers($privileg, $export_privileg = NULL, $no_subform_layers = false, $layer_id = NULL){
		global $language;
		$language_postfix = ($language == 'german' ? "" : "_" . $language);
		$language_layer_name = "Name" . $language_postfix;
		# nicht editierbare SubformFKs ausschliessen
		$condition = (($privileg > 0 AND $no_subform_layers) ? "subformfk IS NULL OR privilegfk = 1" : "true");
		$sql = "
			SELECT DISTINCT
				Layer_ID,
				Name,
				alias,
				export_privileg
			FROM
				(
					SELECT
						l.Layer_ID,
						CASE WHEN l.`" . $language_layer_name . "` != '' THEN l.`" . $language_layer_name . "` ELSE l.`Name` END AS Name,
						l.alias,
						ul.export_privileg,
						form_element_type as subformfk,
						las.privileg as privilegfk
					FROM
						layer l LEFT JOIN
						used_layer ul ON l.Layer_ID = ul.Layer_ID LEFT JOIN
						u_groups g ON COALESCE(ul.group_id, l.Gruppe) = g.id LEFT JOIN
						layer_attributes AS la ON la.layer_id = ul.Layer_ID AND form_element_type = 'SubformFK' LEFT JOIN
						layer_attributes2stelle AS las ON las.stelle_id = ul.Stelle_ID AND ul.Layer_ID = las.layer_id AND las.attributename = SUBSTRING_INDEX(SUBSTRING_INDEX(la.options, ';', 1) , ',',  -1)
					WHERE
						ul.stelle_id = " . $this->id . " AND
						l.connectiontype = 6 AND
						ul.queryable = '1'"
						. ($privileg != NULL ? " AND ul.privileg >= '" . $privileg . "'" : "")
						. ($export_privileg != NULL ? " AND ul.export_privileg > 0" : "")
						. ($layer_id != NULL ? " AND l.Layer_ID = " . $layer_id : "") . "
					ORDER BY
						Name
				) as foo
			WHERE
				" . $condition . "
		";
		#echo 'SQL zur Abfrage der abfragbaren Layer in der Stelle: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getqueryablePostgisLayers - Lesen der abfragbaren PostgisLayer zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		else {
			$layer = array(
				'ID' => array(),
				'Bezeichnung' => array(),
				'export_privileg' => array()
			);
			while($rs=$this->database->result->fetch_array()) {
				$rs['Name'] = replace_params_rolle($rs['Name']);
				$rs['alias'] = replace_params_rolle($rs['alias']);
				$rs['Name_or_alias'] = $rs[($rs['alias'] AND $this->useLayerAliases) ? 'alias' : 'Name'];
				$layer['ID'][] = $rs['Layer_ID'];
				$layer['Bezeichnung'][] = $rs['Name_or_alias'];
				$layer['export_privileg'][] = $rs['export_privileg'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			if (count($layer['Bezeichnung']) > 0) {
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
				$sorted_arrays2 = umlaute_sortieren($layer['Bezeichnung'], $layer['export_privileg']);
				$layer['Bezeichnung'] = $sorted_arrays['array'];
				$layer['ID'] = $sorted_arrays['second_array'];
				$layer['export_privileg'] = $sorted_arrays2['second_array'];
			}
		}
		return $layer;
	}

	function getqueryableVectorLayers($privileg, $user_id, $group_id = NULL, $layer_ids = NULL, $rollenlayer_type = NULL, $use_geom = NULL, $no_query_layers = false,  $export_privileg = NULL){
		global $language;
		$language_postfix = ($language == 'german' ? "" : "_" . $language);
		$language_layer_name = "Name" . $language_postfix;
		$language_group_name = "Gruppenname" . $language_postfix;
		$sql = "
			SELECT
				l.`Layer_ID`,
				CASE WHEN l.`" . $language_layer_name . "` != '' THEN l.`" . $language_layer_name . "` ELSE l.`Name` END AS Name,
				l.`alias`,
				COALESCE(ul.group_id, l.`Gruppe`) AS Gruppe,
				CASE WHEN g.`" . $language_group_name . "` != '' THEN g.`" . $language_group_name . "` ELSE g.`Gruppenname` END AS Gruppenname,
				l.`connection`,
				ul.export_privileg
			FROM
				layer l LEFT JOIN
				used_layer ul ON l.Layer_ID = ul.layer_id LEFT JOIN
				u_groups g ON COALESCE(ul.group_id, l.Gruppe) = g.id
			WHERE
				stelle_id = " . $this->id . " AND
				(
					l.connectiontype = 6 OR
					l.connectiontype = 9
				) AND "
				. ($use_geom != NULL ? "ul.use_geom = 1" : "ul.queryable = '1'")
				. ($no_query_layers ? " AND l.`Datentyp` != 5" : "")
				. ($privileg != NULL ? " AND ul.privileg >= '" . $privileg . "'" : "")
				. ($export_privileg != NULL ? " AND ul.export_privileg > 0" : "")
				. ($group_id != NULL ? " AND COALESCE(ul.group_id, l.`Gruppe`) = " . $group_id : "")
				. ($layer_ids != NULL ? " AND l.`Layer_ID` IN (" . implode(',', $layer_ids) . ")" : "") . "
		";
		if ($user_id != NULL) {
			$sql .= "
				UNION
				SELECT
					-id AS Layer_ID,
					concat(`Name`, CASE WHEN Typ = 'search' THEN ' -eigene Abfrage-' ELSE ' -eigener Import-' END) AS Name,
					'' AS alias,
					Gruppe,
					' ' AS Gruppenname,
					`connection`,
					1 AS export_privileg
				FROM
					rollenlayer
				WHERE
					stelle_id = " . $this->id . " AND
					user_id = " . $user_id . " AND
					connectiontype = 6"
					. ($rollenlayer_type != NULL ? " AND Typ = '" . $rollenlayer_type . "'" : "")
					. ($group_id != NULL ? " AND Gruppe = " . $group_id : "") . "
			";
		}
		if ($this->useLayerAliases) {
			$sql .= " ORDER BY COALESCE(NULLIF(alias, ''), Name)";
		}
		else {
			$sql .= " ORDER BY Name";
		}
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getqueryableVectorLayers - Lesen der abfragbaren VektorLayer zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);		
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		else {
			while ($rs=$this->database->result->fetch_assoc()){
				$rs['Name'] = replace_params_rolle($rs['Name']);
				$rs['alias'] = replace_params_rolle($rs['alias']);
				$rs['Name_or_alias'] = $rs[($rs['alias'] AND $this->useLayerAliases) ? 'alias' : 'Name'];
				$layer['ID'][] = $rs['Layer_ID'];
				$layer['Bezeichnung'][] = $rs['Name_or_alias'];
				$layer['Gruppe'][] = $rs['Gruppe'];
				$layer['Gruppenname'][] = $rs['Gruppenname'];
				$layer['export_privileg'][] = $rs['export_privileg'];
			}
		}
		return $layer;
	}

	function addAktivLayer($layerid) {
		# Hinzufügen der Layer als aktive Layer
		for ($i=0;$i<count($layerid);$i++) {
			$sql ='UPDATE used_layer SET aktivStatus="1"';
			$sql.=' WHERE Stelle_ID='.$this->id.' AND Layer_ID='.$layerid[$i];
			$this->debug->write("<p>file:stelle.php class:stelle->addAktivLayer - Hinzufügen von aktiven Layern zur Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function setAktivLayer($formvars) {
		# Eintragen des Status der Layer, 1 angezeigt oder 0 nicht.
		$layerset=$this->getLayer('');
		for ($i=0;$i<count($layerset);$i++) {
			if ($formvars['thema'.$layerset[$i]['Layer_ID']]==1) {
				$aktiv_status=1;
			}
			else {
				$aktiv_status=0;
			}
			$sql ='UPDATE used_layer SET aktivStatus="'.$aktiv_status.'"';
			$sql.=' WHERE Stelle_ID='.$this->id.' AND Layer_ID='.$layerset[$i]['Layer_ID'];
			$this->debug->write("<p>file:stelle.php class:stelle->setAktivLayer - Speichern der aktiven Layer zur Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function setQueryStatus($formvars) {
		# Eintragen des query_status=1 für Layer, die für die Abfrage selektiert wurden
		$layerset=$this->getLayer('');
		for ($i=0;$i<count($layerset);$i++) {
			if ($formvars['qLayer'.$layerset[$i]['Layer_ID']]) {
				$query_status=1;
			}
			else {
				$query_status=0;
			}
			$sql ='UPDATE used_layer set queryStatus="'.$query_status.'"';
			$sql.=' WHERE Layer_ID='.$layerset[$i]['Layer_ID'];
			$this->debug->write("<p>file:stelle.php class:stelle->setQueryStatus - Speichern des Abfragestatus der Layer zur Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	/*
	* Abfragen der Layer der Stelle
	*/
	function getLayer($Layer_id, $result = '') {
		$layer = array();
		$sql = "
			SELECT
				l.Layer_ID,
				l.Name,
				l.Gruppe,
				l.document_path,
				ul.use_parent_privileges,
				ul.privileg,
				ul.export_privileg,
				ul.requires,
				ul.`queryable`, 
				l.`drawingorder`, 
				ul.`legendorder`, 
				ul.`minscale`, 
				ul.`maxscale`, 
				ul.`offsite`, 
				ul.`transparency`, 
				ul.`postlabelcache`, 
				ul.`Filter`, 
				ul.`template`, 
				ul.`symbolscale`, 
				ul.`logconsume`, 
				ul.`start_aktiv`, 
				ul.`use_geom`,
				ul.`group_id`,
				parent_id,
				GROUP_CONCAT(ul2.Stelle_ID) as used_layer_parent_id,
				GROUP_CONCAT(s.Bezeichnung) as used_layer_parent_bezeichnung
			FROM
				layer AS l 
				JOIN used_layer AS ul ON l.Layer_ID = ul.Layer_ID
				LEFT JOIN stellen_hierarchie ON child_id = " . $this->id . "
				LEFT JOIN used_layer AS ul2 ON 
					l.Layer_ID = ul2.Layer_ID AND	
					ul2.Stelle_ID = parent_id
				LEFT JOIN stelle AS s ON s.ID = ul2.Stelle_ID
			WHERE
				ul.Stelle_ID = " . $this->id .
				($Layer_id != '' ? " AND l.Layer_ID = " . $Layer_id : '') . "
			GROUP BY 
				l.Layer_ID, l.Name, l.Gruppe, ul.use_parent_privileges, ul.privileg, ul.export_privileg,
				ul.`queryable`, 
				l.`drawingorder`, 
				ul.`legendorder`, 
				ul.`minscale`, 
				ul.`maxscale`, 
				ul.`offsite`, 
				ul.`transparency`, 
				ul.`postlabelcache`, 
				ul.`Filter`, 
				ul.`template`, 
				ul.`symbolscale`, 
				ul.`logconsume`, 
				ul.`start_aktiv`, 
				ul.`use_geom`
		";
		#echo '<br>getLayer Sql:<br>'. $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getLayer - Abfragen der Layer zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		while ($rs=$this->database->result->fetch_assoc()) {
			$layer[] = ($result == 'only_ids' ? $rs['Layer_ID'] : $rs);
		}
		return $layer;
	}

	/*
	* Function return layerdef for kvportal
	* It query mapOptions, groups as themes, base and overlay layers
	* and compose it to a layerdef conform object structure
	*/
	function get_layerdef() {
		#echo 'Stelle->get_layerdef';
		include_once(CLASSPATH . 'Layer2Stelle.php');
		include_once(CLASSPATH . 'Layer.php');
		include_once(CLASSPATH . 'LayerGroup.php');

		$stelle_id = $this->id;
		$stellendaten = $this->getstellendaten();
		$stellenextent = $this->MaxGeorefExt;
		$projFROM = new projectionObj("init=epsg:" . $this->epsg_code);
		$projTO = new projectionObj("init=epsg:4326");
		$stellenextent->project($projFROM, $projTO);

		$layerdef = (Object) array(
			'mapOptions' => (Object) array(
				'center' => (Object) array(
					'lat' => round(($stellenextent->maxy - $stellenextent->miny) / 2 + $stellenextent->miny, 5),
					'lng' => round(($stellenextent->maxx - $stellenextent->minx) / 2 + $stellenextent->minx, 5)
				),
				'zoom' => $stellendaten['minzoom'],
				'maxBounds' => array(
					array(round($stellenextent->miny, 5), round($stellenextent->minx, 5)),
					array(round($stellenextent->maxy, 5), round($stellenextent->maxx, 5))
				),
				'minZoom' => $stellendaten['minzoom']
			),
			'default_wms_legend_icon' => 'img/noun_Globe.svg',
			'themes' => array_map(
				function($parent) use ($stelle_id) {
					#echo '<br>call get_layerdef for group: ' . $parent->get('id') . ' in stelle_id: ' . $stelle_id;
					return $parent->get_layerdef('', $stelle_id);
				},
				LayerGroup::find_top_parents($this->database->gui, $this->id)
			),
			'baseLayers' => array_map(
				function($layer2Stelle) {
					$layer = Layer::find_by_id($layer2Stelle->gui, $layer2Stelle->get('Layer_ID'));
					if ($layer) {
						// return only baselayer_def if layer has been found
						return $layer->get_baselayers_def($this->id);
					}
				},
				Layer2Stelle::find_base_layers($this->database->gui, $this->id)
			),
			'overlays' => array_map(
				function($layer2Stelle) {
					$layer = Layer::find_by_id($layer2Stelle->gui, $layer2Stelle->get('Layer_ID'));
					if ($layer2Stelle->get('group_id')) {
						$layer->set('Gruppe', $layer2Stelle->get('group_id'));
					}
					if ($layer) {
						// return overlay_def only if layer has been found
						$layer->minScale = $layer2Stelle->get('minscale');
						$layer->maxScale = $layer2Stelle->get('maxscale');
						$layer->opacity  = $layer2Stelle->get('transparency') ?: 100;
						#echo '<br>call get_overlay_layers for layer_id: ' . $layer->get('Layer_ID');
						return $layer->get_overlays_def($this->id);
					}
				},
				Layer2Stelle::find_overlay_layers($this->database->gui, $this->id)
			),
			'geocoder' => (Object) array(
				'type' => 'nominatim',
				'params' => (Object) array(
					'viewbox' => round($stellenextent->minx, 5) . ',' . round($stellenextent->miny, 5) . ',' . round($stellenextent->maxx, 5) . ',' . round($stellenextent->maxy, 5)
				)
			)
		);
		return $layerdef;
	}

	function get_attributes_privileges($layer_id) {
		if ($layer_id > 0) {
			$sql = "
				SELECT
					`attributename`,
					`privileg`,
					`tooltip`
				FROM
					`layer_attributes2stelle`
				WHERE
					`stelle_id` = " . $this->id . " AND
					`layer_id` = " . $layer_id;
		}
		else {
			$sql = "
				SELECT 
					name as `attributename`,
					0 as `privileg`
				FROM 
					`layer_attributes` 
				WHERE 
				`layer_id` = " . $layer_id;
		}
		// echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->get_attributes_privileges - Abfragen der Layerrechte zur Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__, 4); return 0; }
		while ($rs = $this->database->result->fetch_array()) {
			$privileges[$rs['attributename']] = $rs['privileg'];
			$privileges['tooltip_' . $rs['attributename']] = $rs['tooltip'];
			$privileges['attributenames'][] = $rs['attributename'];
		}
		return $privileges;
	}

	function set_layer_privileges($formvars){
		if ($formvars['used_layer_parent_ids'] != '' AND $formvars['use_parent_privileges' . $this->id] == 1) {
			# wenn Eltern-Stelle für diesen Layer vorhanden, deren Rechte übernehmen
			$privileg = 0;
			$export_privileg = 0;
			foreach($formvars['used_layer_parent_ids'] as $parent_id) {
				# unter allen Elternstellen das höchste Recht finden
				if ($formvars['privileg' . $parent_id] > $privileg) {
					$privileg = $formvars['privileg' . $parent_id];
				}
				if ($formvars['export_privileg' . $parent_id] > $export_privileg or $formvars['export_privileg' . $parent_id] == 1) {
					$export_privileg = $formvars['export_privileg' . $parent_id];
				}
			}
			$formvars['privileg' . $this->id] = $privileg;
			$formvars['export_privileg' . $this->id] = $export_privileg;
		}
		$sql = '
			UPDATE 
				used_layer 
			SET 
				privileg = "' . $formvars['privileg' . $this->id] . '", 
				export_privileg = "' . $formvars['export_privileg' . $this->id] . '" ,
				use_parent_privileges = "' . ($formvars['use_parent_privileges' . $this->id] ?: 0) . '" 
			WHERE 
				layer_id = ' . $formvars['selected_layer_id'] . ' AND 
				stelle_id = ' . $this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->set_layer_privileges - Speichern der Layerrechte zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
	}

	function set_attributes_privileges($formvars, $attributes){
		# erst alles löschen zu diesem Layer und Stelle
		$sql = "
			DELETE FROM
				`layer_attributes2stelle`
			WHERE
				`layer_id` = " . $formvars['selected_layer_id'] . " AND
				`stelle_id` = " . $this->id . "
		";
		#echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__, 4); return 0; }
		# dann Attributrechte eintragen
		for ($i = 0; $i < count($attributes['type']); $i++) {
			if ($formvars['used_layer_parent_ids'] != '' AND $formvars['use_parent_privileges' . $this->id] == 1) {
				# wenn Eltern-Stelle für diesen Layer vorhanden, deren Rechte übernehmen
				$privileg = '';
				$tooltip = '';
				foreach($formvars['used_layer_parent_ids'] as $parent_id) {
					# unter allen Elternstellen das höchste Recht finden
					if ($formvars['privileg_' . $attributes['name'][$i] .'_'. $parent_id] > $privileg) {
						$privileg = $formvars['privileg_' . $attributes['name'][$i] .'_'. $parent_id];
					}
					if ($formvars['tooltip_' . $attributes['name'][$i] .'_'. $parent_id] > $tooltip) {
						$tooltip = $formvars['tooltip_' . $attributes['name'][$i] .'_'. $parent_id];
					}
				}
				$formvars['privileg_' . $attributes['name'][$i] .'_'. $this->id] = $privileg;
				$formvars['tooltip_' . $attributes['name'][$i] .'_'. $this->id] = $tooltip;
			}
			if($formvars['privileg_'.$attributes['name'][$i].'_'.$this->id] !== '') {
				$sql = "
					INSERT INTO
						layer_attributes2stelle
					SET 
						`layer_id` = " . $formvars['selected_layer_id'] . ",
						`stelle_id` = " . $this->id . ",
						`attributename` = '" . $attributes['name'][$i] . "',
						`privileg` = " . $formvars['privileg_' . $attributes['name'][$i] .'_'. $this->id] .",
						`tooltip`= " . ($formvars['tooltip_' . $attributes['name'][$i] .'_'. $this->id] == 'on' ? "1" : "0") . "
				";
				#echo '<br>Sql: ' . $sql;
				$this->debug->write("<p>file:stelle.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>" . $sql, 4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__, 4); return 0; }
			}
		}
	}

	function getGemeindeIDs() {
		$liste = [];
		$liste['ganze_gemeinde'] = Array();
		$liste['eingeschr_gemeinde'] = Array();
		$liste['ganze_gemarkung'] = Array();
		$liste['eingeschr_gemarkung'] = Array();
		$liste['ganze_flur'] = Array();
		$liste['eingeschr_flur'] = Array();
		$sql = 'SELECT Gemeinde_ID, Gemarkung, Flur, Flurstueck FROM stelle_gemeinden WHERE Stelle_ID = '.$this->id;
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getGemeindeIDs - Lesen der GemeindeIDs zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if ($this->database->result->num_rows > 0) {
			while ($rs=$this->database->result->fetch_assoc()) {
				if ($rs['Gemarkung'] != '') {
					$liste['eingeschr_gemeinde'][$rs['Gemeinde_ID']] = NULL;
					if ($rs['Flur'] != '') {
						$liste['eingeschr_gemarkung'][$rs['Gemarkung']][] = $rs['Flur'];
						if ($rs['Flurstueck'] != '') {
							$liste['eingeschr_flur'][$rs['Gemarkung']][$rs['Flur']][] = $rs['Flurstueck'];
						}
						else {
							$liste['ganze_flur'][$rs['Gemarkung']][] = $rs['Flur'];
						}
					}
					else {
						$liste['ganze_gemarkung'][$rs['Gemarkung']] = NULL;
					}
				}
				else{
					$liste['ganze_gemeinde'][$rs['Gemeinde_ID']] = NULL;
				}
			}
		}
		return $liste;
	}

/*
	function getGemeinden($database) {
		if($database->type == 'mysql'){
			$ret=$this->database->getGemeindebyID_Name($this->id);
			if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
			if ($this->database->result->num_rows == 0) {
				$GemeindeListe['ID'][0]=0;
			}
			else{
				while ($rs = $this->database->result->fetch_array()) {
					$GemeindeListe['ID'][]=$rs['ID'];
					$GemeindeListe['Name'][]=$rs['Name'];
				}
			}
		}
		elseif($database->type == 'postgresql'){
			$liste = $this->getGemeindeIDs();
			for($i = 0; $i < count($liste); $i++){
				$ret = $database->getGemeindeName($liste[$i]);
				$GemeindeListe['ID'][]=$liste[$i];
				$GemeindeListe['Name'][]=$ret[1]['name'];
			}
		}
		return $GemeindeListe;
	}
*/

	function getUser($result = '') {
		$user['ID'] = array();
		# Lesen der User zur Stelle
		$sql = "
			SELECT
				user.*
			FROM
				user JOIN
				rolle ON user.ID = rolle.user_id JOIN 
				stelle ON stelle.ID = rolle.stelle_id
			WHERE
				archived IS NULL AND 
				rolle.stelle_id = " . $this->id . "
			ORDER BY 
				user.ID = stelle.default_user_id desc, user.Name
		";
		#debug_write('Abfrage der Nutzer der Stelle mit getUser', $sql, 1);
		$this->debug->write("<p>file:stelle.php class:stelle->getUser - Lesen der User zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=$this->database->result->fetch_array()) {
				$user['ID'][] = $rs['ID'];
				$user['Bezeichnung'][] = $rs['Name'].', '.$rs['Vorname'];
				$user['position'][] = $rs['position'];
				$user['email'][] = $rs['email'];
			}
		}
		if ($result == 'only_ids') {
			return $user['ID'];
		}
		else {
			return $user;
		}
	}

	function getWappen() {
		$sql = "
			SELECT
				wappen, wappen_link
			FROM
				stelle
			WHERE
				ID = " . $this->id . "
		";
		$this->debug->write("<p>file:stelle.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__, 4); return 0; }
		$rs = $this->database->result->fetch_assoc();
		return $rs;
	}

	/**
	* Function reads all mapfiles in directory WMS_MAPFILE_PATH . $this->Stelle->id
	* @return array An array of mapfiles in the mapfiles directory of the stelle
	*/
	function get_mapfiles() {
		$mapfiles = array();
		if (is_dir(WMS_MAPFILE_PATH . $this->id)) {
			$mapfiles = array_diff(scandir(WMS_MAPFILE_PATH . $this->id), array('.', '..'));
		}
		#echo '<p>Stelle->get_mapfile returns mapfiles: ' . print_r($mapfiles, true);
		return $mapfiles;
	}

	function is_gast_stelle() {
		global $gast_stellen;
		return (in_array($this->id, array_values($gast_stellen)) ? 'true' : 'false');
	}
}
?>
