<?php
include_once(CLASSPATH . 'PgObject.php');
########################
# Klasse Metadatensatz #
########################
class metadata extends PgObject {

	static $schema = 'public';
	static $tableName = 'md_metadata';

	function metadata($gui, $select = '*') {
		$this->PgObject($gui, metadata::$schema, metadata::$tableName);
		$this->select = $select;
		$this->identifier = 'id';
		$this->identifier_type = 'integer';
	}

	function findQuickSearch($md) {
		$where = "true";
		if ($md['was'] != '') {
			$where .= "
				AND
				(
					restitle LIKE '%" . $md['was'] . "%' OR
					(
						k.keyword LIKE '%" . $md['was'] . "%' AND
						k.keytyp = 'theme'
					)
				)
			";
		}
		if ($md['wer'] != '') {
			$where .= "
				AND
				(
					rporgname LIKE '%" . $md['wer'] . "%' OR
					linkage LIKE '%" . $md['wer'] . "%'
				)
			";
		}
		if ($md['wo'] != '') {
			$where .= "
				AND
				(
					k.keyword LIKE '%" . $md['wo'] . "%' AND
					k.keytyp='place'
				)
			";
		}
		if ($md['vonwann'] != '') {
			$where .= "
				AND validtill >= '" . $md['vonwann'] . "'
			";
		}
		if ($md['biswann'] != '') {
			$where .= "
				AND validfrom <= '" . $md['biswann'] . "'
			";
		}
		if ($md['northbl'] != '') {
			# Umringspolygon fï¿½r die Suche in der Datenbank aus den ï¿½bergebenen Koordinaten zusammensetzen
			$md['umring']  = 'POLYGON(('.$md['eastbl'].' '.$md['southbl'].','.$md['westbl'].' '.$md['southbl'];
			$md['umring'] .= ','.$md['westbl'].' '.$md['northbl'].','.$md['eastbl'].' '.$md['northbl'];
			$md['umring'] .= ','.$md['eastbl'].' '.$md['southbl'].'))';
			# sql-Teil fï¿½r rï¿½umliche Abfrage bilden
			$where .= " AND the_geom && st_geometryfromtext('".$md['umring']."',".EPSGCODE.") AND st_intersects(the_geom,st_geometryfromtext('".$md['umring']."',".EPSGCODE."))";
		}

		$sql = "
			SELECT DISTINCT
				m.oid,
				m.*
			FROM
				md_metadata AS m JOIN
				md_keywords2metadata AS k2m ON m.id = k2m.metadata_id JOIN
				md_keywords AS k ON k2m.keyword_id = k.id
			WHERE
				" . $where . "
		";
		#echo '<br>Sql: ' . $sql;
		return $this->getSQLResults($sql);

/*
				# Abfragen und Zuweisen der Keywortbezeichnungen
				$theme=$this->getKeywords('','','theme','',$rs['id'],'keyword');
				$themes=$theme[1]['keyword'];
				$rs['themekeywords']=$themes[0];
				for ($i=1;$i<count($themes);$i++) {
					$rs['themekeywords'].=', '.$themes[$i];
				}
				$place=$this->getKeywords('','','place','',$rs['id'],'keyword');
				$places=$place[1]['keyword'];
				$rs['placekeywords']=$places[0];
				for ($i=1;$i<count($places);$i++) {
					$rs['placekeywords'].=', '.$places[$i];
				}
*/
	}

}

###
# Alte Klasse, depricated, später löschen
#
class metadatensatz {
	var $MD_id;
	var $debug;
		
	################### Liste der Funktionen #######################
	#
	################################################################

	function metadatensatz($MD_id, $db) {
		global $debug;
		$this->debug=$debug;
		if ($MD_id!='') {
			$this->MD_id=$MD_id;
		}
		$this->database=$db;
	}
	
	function getMetadaten($md) {
		# Liesst Metadatenwerte zu einer übergegebenen Metadatensatz_id
		$ret=$this->database->getMetadata($md);
		if ($ret[0]) {
			$ret[1]='Fehler beim Abfragen der Datenbank'.$ret[1];
		}
		else {
			$this->anzMetadatensaetze=count($ret[1]);
		}
		return $ret;
	}
	
	function getMetadatenQuickSearch($md) {
		# Durchsucht die Datenbank mit der Schnellsuche nach Metadatensätzen
		$ret=$this->database->getMetadataQuickSearch($md);
		if ($ret[0]) {
			$ret[1]='Fehler beim Abfragen der Datenbank'.$ret[1];
		}
		else {
			$this->anzMetadatensaetze=count($ret[1]);
		}
		return $ret;
	}
	
	function readDefaultValues($user) {
		#2005-11-29_pk
		# Weißt Standardwerte zu den Metadatenfeldern zu und liefert diese als Array zurück
		$md['mdfileid']=rand();
		$md['mddatest']=date('Y-m-d');
		$md['reseddate']=date('Y-m-d');
		$md['validfrom']=date('Y-m-d');
		$md['validtill']=date('Y-m-d',mktime(0, 0, 0, date('m'),	date('d'),	date('Y')+1));
		$md['westbl']=round($user->rolle->oGeorefExt->minx);
		$md['eastbl']=round($user->rolle->oGeorefExt->maxx);
		$md['southbl']=round($user->rolle->oGeorefExt->miny);
		$md['northbl']=round($user->rolle->oGeorefExt->maxy);
		$md['serviceversion']='1.0.0';
		return $md;
	}
	
	function getKeywords($id,$keyword,$keytyp,$thesaname,$metadata_id,$order) {
		$ret=$this->database->getKeywords($id,$keyword,$keytyp,$thesaname,$metadata_id,$order);
		return $ret;
	}
	
	function speichern($metadaten) {
		#2005-11-29_pk
		# Prüfen der Metadaten
		if ($ret[0]) {
			$ret[1]='<br>Fehler beim Prüfen der Metadaten.'.$ret[1];
		}
		else {
			if ($metadaten['id']) {
				# Es handelt sich um schon vorhandene Metadaten, die aktualisiert werden sollen
				# UPDATE
			}
			else {
				# Neuer Metadatensatz INSERT
				$ret=$this->database->insertMetadata($metadaten);
			}
		}
		return $ret;
	}
	
	function checkMetadata($md) {
		#2005-11-29_pk
		# Prüft die eingegebenen Metadaten auf Richtigkeit und formt gegebenenfalls Datentypen um
		if ($md['restitle']=='') { $errmsg='<br>Geben Sie einen Titel an.'; }
		if ($md['mdfileid']=='') {
			$errmsg='<br>Geben Sie eine eindeutige Metadatenid an.';
		}
		else {
			# Abfragen ob es diese eindeutige Kennung schon in der Datenbank gibt
			$ret=$this->database->getMetadataByMdFileID($md['mdfileid']);
			var_dump($ret);
			if ($ret[0]) {
				$errmsg.='<br>Fehler beim Abfragen der neuen Metadatenid.<br>'.$ret[1];
			}
			else {
				if ($ret[1]['mdfileid']==$md['mdfileid']) {
					$errmsg.='<br>Die angegebene Identifikation ist schon vorhanden.';
				}
			}
		}

		if ($md['postcode']=='') { $md['postcode']='NULL'; }
		if ($md['vector_scale']=='') { $md['vector_scale']='NULL'; }
		if ($md['mdcontact']=='') { $md['mdcontact']='NULL'; }
		if ($md['spatrepinfo']=='') { $md['spatrepinfo']='NULL'; }
		if ($md['refsysinfo']=='') { $md['refsysinfo']='NULL'; }
		if ($md['mdextinfo']=='') { $md['mdextinfo']='NULL'; }
		if ($md['dataidinfo']=='') { $md['dataidinfo']='NULL'; }
		if ($md['continfo']=='') { $md['continfo']='NULL'; }
		if ($md['distinfo']=='') { $md['distinfo']='NULL'; }
		if ($md['databinding']=='') { $md['databinding']=0; }
		# Zusammenfassen der selectierten Schlagwörter
		if ($md['selectedthemekeywordids']=='') {
			$errmsg.='<br>Geben Sie thematische Schlagwörter ein.';
		}
		else {
			$keywords=array_unique(explode(", ",$md['selectedthemekeywordids']));
			$md['selectedthemekeywordids']=$keywords[0];
			for ($i=1;$i<count($keywords);$i++) {
				$md['selectedthemekeywordids'].=", ".$keywords[$i];
			}
		}
		if ($md['selectedplacekeywordids']=='') {
			$errmsg.='<br>Geben Sie räumliche Schlagwörter ein.';
		}
		else {
			$keywords=array_unique(explode(", ",$md['selectedplacekeywordids']));
			$md['selectedplacekeywordids']=$keywords[0];
			for ($i=1;$i<count($keywords);$i++) {
				$md['selectedthemekeywordids'].=", ".$keywords[$i];
			}
		}
		$md['umring'] ='POLYGON(('.$md['eastbl'].' '.$md['southbl'].','.$md['westbl'].' '.$md['southbl'];
		$md['umring'].=','.$md['westbl'].' '.$md['northbl'].','.$md['eastbl'].' '.$md['northbl'];
		$md['umring'].=','.$md['eastbl'].' '.$md['southbl'].'))';
		if ($errmsg!='') {
			$ret[0]=1; $ret[1]=$errmsg;
		}
		else {
			$ret[0]=0; # fehlerfrei
			$ret[1]=$md;
		}
		return $ret;
	}
} # Ende Klasse Metadaten

?>