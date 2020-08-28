<?php
/*
* Dynamic ATOM builder for service, dataset and link to metadata
*/
class Atom{
	function __construct($gui) {
		$this->gui = $gui;
		$service_location_root = 'https://bauleitplaene-mv.de/kvwmap_dev/atom.php';
		//Namespaces e.g. relevant when creating an INSPIRE service and inspire_dls is needed
		$this->service_feed_namespaces = array('xmlns="http://www.w3.org/2005/Atom"','xmlns:georss="http://www.georss.org/georss"');
		$this->service_feed_title = 'Service-Feed des Bauleitplanservers Mecklenburg-Vorpommern';
		$this->service_feed_metadata_location = 'https://testurl.de/servicefeed_metadata.xml';
		$this->service_feed_location = $service_location_root . '?type=service'; //if go case, swap ? with &
		// service-feed-url currently url = id, as always local
		$this->service_feed_id = $this->service_feed_location;
		$this->service_feed_rights = 'none';
		$this->service_feed_updated_at = '2019-01-28T11:29:11+01:00'; // TODO // TODO get this automatically FROM xplankonverter.konvertierungen -> column updated_at (the newest that conforms to publishable datasets or e.g. newest from dataset_entry
		$this->service_feed_author_name = 'Landkreis Nordwestmecklenburg';
		$this->service_feed_author_email = 'j.debold@nordwestmecklenburg.de';

		$this->dataset_feed_namespaces = array('xmlns="http://www.w3.org/2005/Atom"','xmlns:georss="http://www.georss.org/georss"');
		$this->dataset_feed_author_name = 'Landkreis Nordwestmecklenburg';
		$this->dataset_feed_author_email = 'j.debold@nordwestmecklenburg.de';
		// XML escape & 
		$this->dataset_feed_location = $service_location_root . '?type=dataset&amp;dataset_id='; //if go case, swap ? with &
		$this->dataset_feed_metadata_location = 'https://testurl.de/datasetfeed_metadata.xml';

		$this->dataset_feed_rights = 'none';
		$this->dataset_feed_summary = 'dataset_feed_summary';
		$this->dataset_feed_subtitle = 'Example Dataset-Feed-Subtitle';
		$this->dataset_entry_rights = 'none';
		$this->dataset_entry_summary = 'test_summary_dataset_entry';
		//internal location of gml's is currently /var/www/data/$konvertierung_id/xplan_gml/xplan_gml_ + $konvertierung_id
		$this->dataset_entry_file_location = 'https://gdi-service.de/public/services/lbforstbb/fuek_rp/fuek_rp_25833.gml';
		$this->dataset_entry_file_length = 2032682; // TODO use filesize($this->dataset_entry_file_location); to get filesize
	}

	/*
	* Function liefert service feet
	* hier im Beispiel hart programmiert für Bauleitpläne
	* ToDo dynamisch machen für beliebige Feature
	*
	*/
	function build_service_feed() {
		$xml  = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
		$xml .= '<!-- Atom-Feed automatically created by GDI-Service Rostock-->';
		$xml .= '<feed ';
		foreach($this->service_feed_namespaces as $ns) {
			$xml .= $ns . ' ';
		}
		$xml .= '>';
		$xml .= '<title>' . $this->service_feed_title . '</title>';
		$xml .= '<link title="Link on this service-metdata-document" rel="describedBy" href="' . $this->service_feed_metadata_location . '" hreflang="de" type="application.xml" />';
		$xml .= '<link title="Link on this service atom feed" rel="self" href="' . $this->service_feed_location . '" hreflang="de" type="application/atom+xml" />';
		/*
		* If INSPIRE pre-defined download service, add a link to an open search description, e.g.
		* <link title="Open Search Beschreibung des INSPIRE pre-defined Download Dienstes" rel="search" href="" hreflang="de" type="application/opensearchdescription+xml" />
		*/ 
		$xml .= '<id>' . $this->service_feed_id . '</id>';
		$xml .= '<rights>' . $this->service_feed_rights . '</rights>';
		$xml .= '<updated>' . $this->service_feed_updated_at . '</updated>';
		$xml .= '<author>';
		$xml .= '<name>' . $this->service_feed_author_name . '</name>';
		$xml .= '<email>' . $this->service_feed_author_email . '</email>';
		$xml .= '</author>';

		// TODO currently only BP-Pläne, find out first if it should be one service for bp, fp and so together or 3 feeds (then fix anzeigename accordingly)
		// TODO also add Stelle_name in front of anzeigename
		$conn = $this->gui->pgdatabase->dbConn;
		$sql = "
			SELECT
				pa.abkuerzung || ' Nr. ' || p.nummer || ' ' || (p.gemeinde[1]).gemeindename || ' ' || p.name AS anzeigename,
				p.gml_id AS gml_id,
				p.updated_at::timestamp with time zone AS dataset_updated_at,
				k.output_epsg AS dataset_epsg,
				k.id AS konvertierung_id,
				k.planart AS planart,
				ST_AsText(ST_Envelope(ST_Transform(p.raeumlichergeltungsbereich,4326))) AS dataset_polygon
			FROM
				xplan_gml.bp_plan p JOIN
				xplankonverter.konvertierungen k ON p.konvertierung_id = k.id JOIN
				xplan_gml.enum_bp_planart pa ON p.planart[1] = pa.wert::text::xplan_gml.bp_planart
			WHERE
				k.veroeffentlicht = TRUE
/*			AND
				k.status IN(
					'GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
					'INSPIRE-GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
					'INSPIRE-GML-Erstellung abgebrochen'::xplankonverter.enum_konvertierungsstatus,
					'in INSPIRE-GML-Erstellung'::xplankonverter.enum_konvertierungsstatus
				)
*/
			UNION
			SELECT
				pa.abkuerzung || ' Nr. ' || p.nummer || ' ' || (p.gemeinde[1]).gemeindename || ' ' || p.name AS anzeigename,
				p.gml_id AS gml_id,
				p.updated_at::timestamp with time zone AS dataset_updated_at,
				k.output_epsg AS dataset_epsg,
				k.id AS konvertierung_id,
				k.planart AS planart,
				ST_AsText(ST_Envelope(ST_Transform(p.raeumlichergeltungsbereich,4326))) AS dataset_polygon
			FROM
				xplan_gml.fp_plan p JOIN
				xplankonverter.konvertierungen k ON p.konvertierung_id = k.id JOIN
				xplan_gml.enum_fp_planart pa ON p.planart = pa.wert::text::xplan_gml.fp_planart
			WHERE
				k.veroeffentlicht = TRUE
/*			AND
				k.status IN(
					'GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
					'INSPIRE-GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
					'INSPIRE-GML-Erstellung abgebrochen'::xplankonverter.enum_konvertierungsstatus,
					'in INSPIRE-GML-Erstellung'::xplankonverter.enum_konvertierungsstatus
				)
*/
			UNION
			SELECT
				(p.planart).value|| ' ' || (p.gemeinde[1]).gemeindename || ' ' || p.name || coalesce(' Nr. ' || p.nummer,  '') AS anzeigename,
				p.gml_id AS gml_id,
				p.updated_at::timestamp with time zone AS dataset_updated_at,
				k.output_epsg AS dataset_epsg,
				k.id AS konvertierung_id,
				k.planart AS planart,
				ST_AsText(ST_Envelope(ST_Transform(p.raeumlichergeltungsbereich,4326))) AS dataset_polygon
			FROM
				xplan_gml.so_plan p JOIN
				xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
			WHERE
				k.veroeffentlicht = TRUE
/*			AND
				k.status IN(
					'GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
					'INSPIRE-GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
					'INSPIRE-GML-Erstellung abgebrochen'::xplankonverter.enum_konvertierungsstatus,
					'in INSPIRE-GML-Erstellung'::xplankonverter.enum_konvertierungsstatus
				)
*/
			ORDER BY
				dataset_updated_at
			LIMIT 10 --for testing
		";
		#echo '<p>SQL zur Abfrage der Plandaten für Datafeets: ' . $sql; exit;
		$result = pg_query($conn, $sql);
		$konvertierungen = pg_fetch_all($result);
		foreach ($konvertierungen as $konvertierung) {
			$xml .= $this->build_service_entry(
				$konvertierung['dataset_epsg'],
				$this->dataset_feed_author_name,
				$this->dataset_feed_author_email,
				'dataset_feed_' . $konvertierung['gml_id'],
				$this->dataset_feed_metadata_location,
				$this->dataset_feed_location . 'dataset_feed_' . $konvertierung['gml_id'],
				$this->dataset_feed_rights,
				$this->dataset_feed_summary,
				'Dataset Feed Bauleitplanserver Plan ' . $konvertierung['anzeigename'],
				$konvertierung['dataset_updated_at'],
				$konvertierung['dataset_polygon']
			);
		}
		$xml .= '</feed>';
		return $xml;
	}

	function build_service_entry($epsg, $dataset_feed_author_name,$dataset_feed_author_email,$dataset_feed_id, $dataset_feed_metadata_location,$dataset_feed_location,$dataset_feed_rights,$dataset_feed_summary,$dataset_feed_title,$dataset_feed_updated_at,$dataset_feed_georss_polygon) {
		$xml  = '';
		$xml .= '<entry>';
		/*
		* If inspire, add e.g.
			<inspire_dls:spatial_dataset_identifier_code>8D7BE274-EB8A-4E63-8111-A0E5A0CBFC12</inspire_dls:spatial_dataset_identifier_code>
			<inspire_dls:spatial_dataset_identifier_namespace>http://metaver.de/igc_bb/lfb</inspire_dls:spatial_dataset_identifier_namespace>
		*/
		$xml .= '<category label="EPSG:' . $epsg . '" term="http://www.opengis.net/def/crs/EPSG/0/' . $epsg . '"/>';
		$xml .= '<author>';
		$xml .= '<name>' . $dataset_feed_author_name . '</name>';
		$xml .= '<email>' . $dataset_feed_author_email . '</email>';
		$xml .= '</author>';
		$xml .= '<id>' . $dataset_feed_id . '</id>';
		$xml .= '<link title="Link on the dataset-metadata-document" rel="describedby" href="' . $dataset_feed_metadata_location . '" hreflang="de" type="application.xml" />';
		$xml .= '<link title="Link on the dataset atom feed" rel="describedby" href="' . $dataset_feed_location . '" hreflang="de" type="application.xml" />';
		$xml .= '<rights>' . $dataset_feed_rights . '</rights>';
		$xml .= '<summary>' . $dataset_feed_summary . '</summary>';
		$xml .= '<title>' . $dataset_feed_title . '</title>';
		$xml .= '<updated>' . $dataset_feed_updated_at . '</updated>';
		$dataset_feed_georss_polygon = str_replace(",","",$dataset_feed_georss_polygon);
		$dataset_feed_georss_polygon = str_replace("POLYGON","",$dataset_feed_georss_polygon);
		$dataset_feed_georss_polygon = str_replace("(","",$dataset_feed_georss_polygon);
		$dataset_feed_georss_polygon = str_replace(")","",$dataset_feed_georss_polygon);
		$xml .= '<georss:polygon>' . $dataset_feed_georss_polygon . '</georss:polygon>';
		$xml .= '</entry>';
		return $xml;
	}

	function build_dataset_feed($gml_id) {
		$conn = pg_connect('host=85.214.52.246' . ' dbname=kvwmapsp' . ' user=kvwmap' . ' password=nSx5-F8C*53QEcz');
		$sql = "
		SELECT
			pa.abkuerzung || ' Nr. ' || p.nummer || ' ' || (p.gemeinde[1]).gemeindename || ' ' || p.name AS anzeigename,
			p.gml_id AS gml_id,
			p.updated_at::timestamp with time zone AS dataset_updated_at,
			k.output_epsg AS dataset_epsg,
			k.id AS konvertierung_id,
			k.planart AS planart,
			Box2D(p.raeumlichergeltungsbereich)::text AS dataset_bbox,
			ST_AsText(ST_Envelope(ST_Transform(p.raeumlichergeltungsbereich,4326))) AS dataset_polygon
		FROM
			xplan_gml.bp_plan p
		INNER JOIN
			xplankonverter.konvertierungen k
		ON
			p.konvertierung_id = k.id,
			xplan_gml.enum_bp_planart pa
		WHERE
			k.veroeffentlicht = TRUE
		AND
			k.status IN(
				'GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
				'INSPIRE-GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
				'INSPIRE-GML-Erstellung abgebrochen'::xplankonverter.enum_konvertierungsstatus,
				'in INSPIRE-GML-Erstellung'::xplankonverter.enum_konvertierungsstatus
			)
		AND
			p.planart[1] = pa.wert::text::xplan_gml.bp_planart
		AND
			p.gml_id::text = '" . $gml_id . "'
		UNION
		SELECT
			pa.abkuerzung || ' Nr. ' || p.nummer || ' ' || (p.gemeinde[1]).gemeindename || ' ' || p.name AS anzeigename,
			p.gml_id AS gml_id,
			p.updated_at::timestamp with time zone AS dataset_updated_at,
			k.output_epsg AS dataset_epsg,
			k.id AS konvertierung_id,
			k.planart AS planart,
			Box2D(p.raeumlichergeltungsbereich)::text AS dataset_bbox,
			ST_AsText(ST_Envelope(ST_Transform(p.raeumlichergeltungsbereich,4326))) AS dataset_polygon
		FROM
			xplan_gml.fp_plan p
		INNER JOIN
			xplankonverter.konvertierungen k
		ON
			p.konvertierung_id = k.id,
			xplan_gml.enum_fp_planart pa
		WHERE
			k.veroeffentlicht = TRUE
		AND
			k.status IN(
				'GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
				'INSPIRE-GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
				'INSPIRE-GML-Erstellung abgebrochen'::xplankonverter.enum_konvertierungsstatus,
				'in INSPIRE-GML-Erstellung'::xplankonverter.enum_konvertierungsstatus
			)
		AND
			p.planart = pa.wert::text::xplan_gml.fp_planart
		AND
			p.gml_id::text = '" . $gml_id . "'
		UNION
		SELECT
			pa.value|| ' ' || (p.gemeinde[1]).gemeindename || ' ' || p.name || coalesce(' Nr. ' || p.nummer,  '') AS anzeigename,
			p.gml_id AS gml_id,
			p.updated_at::timestamp with time zone AS dataset_updated_at,
			k.output_epsg AS dataset_epsg,
			k.id AS konvertierung_id,
			k.planart AS planart,
			Box2D(p.raeumlichergeltungsbereich)::text AS dataset_bbox,
			ST_AsText(ST_Envelope(ST_Transform(p.raeumlichergeltungsbereich,4326))) AS dataset_polygon
		FROM
			xplan_gml.so_plan p
		INNER JOIN
			xplankonverter.konvertierungen k
		ON
			p.konvertierung_id = k.id,
			xplan_gml.so_planart  pa
		WHERE
			k.veroeffentlicht = TRUE
		AND
			k.status IN(
				'GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
				'INSPIRE-GML-Erstellung abgeschlossen'::xplankonverter.enum_konvertierungsstatus,
				'INSPIRE-GML-Erstellung abgebrochen'::xplankonverter.enum_konvertierungsstatus,
				'in INSPIRE-GML-Erstellung'::xplankonverter.enum_konvertierungsstatus
			)
		AND
			p.gml_id::text = '" . $gml_id . "'
		ORDER BY
			dataset_updated_at;";

		$result = pg_query($conn, $sql);
		$datasets = pg_fetch_all($result);
		if(empty($datasets)) {
			return '<warning>No feed found under id ' . $gml_id . '</warning>';
		}

		$xml  = '';
		$xml  = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
		$xml .= '<!-- Atom-Feed automatically created by GDI-Service Rostock-->';
		$xml .= '<feed ';
		foreach($this->dataset_feed_namespaces as $ns) {
			$xml .= $ns . ' ';
		}
		$xml .= '>';
		$xml .= '<title>' . $this->dataset_feed_title . '</title>';
		$xml .= '<subtitle>' . $this->dataset_feed_subtitle . '</subtitle>';
		$xml .= '<link title="Link on the dataset metadata document" rel="describedby" href="' . $this->dataset_feed_metadata_link . '" hreflang="de" type="application.xml" />';
		$xml .= '<link title="Link on this dataset atom feed" rel="self" href="' . $this->dataset_feed_location . '" hreflang="de" type="application.xml" />';
		$xml .= '<link title="Link on the service atom feed" rel="describedby" href="' . $this->service_feed_location . '" hreflang="de" type="application/atom+xml" />';
		/*
		* If INSPIRE pre-defined download service, add a link to an open search description, e.g.
		*$xml .= '<link title="Open Search Beschreibung des INSPIRE pre-defined Download Dienstes" rel="search" href="" hreflang="de" type="application/opensearchdescription+xml" />';
		*/
		$xml .= '<id>' . 'dataset_feed_' . $gml_id . '</id>';
		$xml .= '<rights>' . $this->dataset_feed_rights . '</rights>';
		$xml .= '<updated>' . $dataset['dataset_updated_at'] . '</updated>';
		$xml .= '<author>';
		$xml .= '<name>' . $this->dataset_feed_author_name . '</name>';
		$xml .= '<email>' . $this->dataset_feed_author_email . '</email>';
		$xml .= '</author>';
		foreach($datasets as $dataset) {
			$xml .= $this->build_dataset_entry($dataset['dataset_epsg'],
												'dataset_feed_' . $dataset['gml_id'],
												'GML Dataset Bauleitplanserver Plan ' . $dataset['gml_id'] . ' ' . $dataset['anzeigename'],
												'GML Dataset Bauleitplanserver Plan ' . $dataset['gml_id'], /* is shortened to prevent special characters in xml attribute that are allowed in Anzeigename, e.g. " " */
												$this->dataset_entry_file_location, /* TODO Figure out how to expose xplan-GML path (folder-structure with konvertierung_id */
												$this->dataset_entry_file_length,
												$dataset['dataset_bbox'],
												$this->dataset_entry_rights,
												$this->dataset_entry_summary,
												$dataset['dataset_updated_at'],
												$dataset['dataset_polygon']
			);
		}
		$xml .= '</feed>';
		return $xml;
	}

	function build_dataset_entry($dataset_feed_epsg, $dataset_entry_id, $dataset_entry_title, $dataset_entry_title_link, $dataset_entry_file_location, $dataset_entry_file_length, $dataset_bbox, $dataset_entry_rights, $dataset_entry_summary,$dataset_entry_updated_at, $dataset_feed_georss_polygon) {
		$xml  = '';
		$xml .= '<entry>';
		$xml .= '<category label="EPSG:' . $dataset_feed_epsg . '" term="http://www.opengis.net/def/crs/EPSG/0/' . $dataset_feed_epsg . '"/>';
		$xml .= '<id>' . $dataset_entry_id .  '</id>';
		// TODO consider doing this in SQL-request
		$dataset_bbox = str_replace(",","",$dataset_bbox);
		$dataset_bbox = str_replace("BOX","",$dataset_bbox);
		$dataset_bbox = str_replace("(","",$dataset_bbox);
		$dataset_bbox = str_replace(")","",$dataset_bbox);

		$xml .= '<link title="' . $dataset_entry_title_link . '" rel="alternate" href="' . $dataset_entry_file_location . '" hreflang="de" length="' . $dataset_entry_file_length . '" bbox="' . $dataset_bbox . '" type="application/gml+xml"/>';
		$xml .= '<rights>' . $dataset_entry_rights . '</rights>';
		$xml .= '<summary>' . $dataset_entry_summary . '</summary>';
		$xml .= '<title>' . $dataset_entry_title . '</title>';
		$xml .= '<updated>' . $dataset_entry_updated_at . '</updated>';
		$dataset_feed_georss_polygon = str_replace(",","",$dataset_feed_georss_polygon);
		$dataset_feed_georss_polygon = str_replace("POLYGON","",$dataset_feed_georss_polygon);
		$dataset_feed_georss_polygon = str_replace("(","",$dataset_feed_georss_polygon);
		$dataset_feed_georss_polygon = str_replace(")","",$dataset_feed_georss_polygon);
		$xml .= '<georss:polygon>' . $dataset_feed_georss_polygon . '</georss:polygon>';

		$xml .= '</entry>';
		return $xml;
	}
}
?>