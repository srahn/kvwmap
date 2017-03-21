<!DOCTYPE html>
<HTML>
	<HEAD>
		<TITLE>Regeleditor</TITLE>
		<META charset="UTF-8">
		<script src="<?php echo JQUERY_PATH; ?>jquery-1.12.0.min.js"></script>
		<script src="plugins/xplankonverter/view/regeleditor/control.js">"use strict";</script>
		<!-- Setzt PHP Variable als (Global) Javascript Variable -->
		<script>konvertierung_id = '<?php echo $konvertierung_id; ?>';</script>
		<link rel="stylesheet" href="<?php echo FONTAWESOME_PATH; ?>css/font-awesome.min.css" type="text/css"/>
		<link rel="stylesheet" href="plugins/xplankonverter/view/regeleditor/styles.css" type="text/css"/>
	</HEAD>
	<BODY>
		<div id="debug"></div>
		<input type="hidden" id="field_id" value="<? echo $_REQUEST['field_id']; ?>">
		<h1 align="center">Regeleditor</h1>
		<!-- Reload-->
		<a onClick="reloadPage()" href="" class="float-right" title="Lädt die Seite neu und löscht alle Einträge"><i class="fa fa-undo fa-lg"></i></a>
		<br>
		<!-- Info -->
		<a onClick="infoPage()" class="float-right" title="Info"><i class="fa fa-info fa-lg"></i></a>
		<div id="myModal" class="modal">
		<!-- Modal content -->
			<div class="modal-content">
				<span class="close">×</span>
				<h3>Shape2XPlan SQL-Query Builder</h3>
				<b>Dokumentation</b><br><hr>
				<i class="fa fa-undo fa-lg"></i> Neu laden<br>
				Lädt die Seite neu und leert alle Eingaben.<br><br>
				<i class="fa fa-exclamation-triangle fa-lg"></i> Warnung<br>
				Warnungen sind Hinweise des Konverters zur korrekten Befüllung von XPlanung-Daten<br><br>
				<i class="fa fa-filter fa-lg"></i> WHERE<br>
				Trägt einen WHERE-Filter in die Abfrage ein. Dieser legt Konditionen fest, welche die Abfrage erfüllen muss.<br>
				Mehr Informationen zur WHERE Kondition:
				https://www.postgresql.org/docs/9.5/static/sql-select.html#SQL-WHERE<br><br>
				<i class="fa fa-plus fa-lg"></i> Hinzufügen<br>
				Bearbeitet ein XPlanung-Attribut<br><br>
				<i class="fa fa-question-circle fa-lg"></i> Shape-Information<br>
				Zeigt bis zu hundert einzigartige Werte einer Shape-Datei an.<br>
				Lässt sich ein und ausschalten.<br><br>
				<i class="fa fa-level-up fa-lg"></i> Hinzufügen und Beenden<br>
				Beendet die Eingabe weiterer Werte für ein Attribut.<br><br>
				<i class="fa fa-trash-o fa-lg"></i> Verwerfen<br>
				Verwirft ein zugewiesenes Attribut oder ein sich in der Zuweisung befindliches Attribut<br><br>
			</div>
		</div>
		<!-- Fehler -->
		<div id="fehler_area"></div>
		<!-- Warnungen -->
		<div id="warnung_area">
			<div id="Warnung_1" class="center60p"><i class="fa fa-exclamation-triangle fa-lg"> Es muss eine XPlan-Klasse ausgewählt werden!</i></div>
			<div id="Warnung_2" class="center60p"><i class="fa fa-exclamation-triangle fa-lg"> Es muss eine Shape-Datei ausgewählt werden!</i></div>
			<div id="Warnung_3" style="display: none" class="center60p"><i class="fa fa-exclamation-triangle fa-lg"> Das Attribut rechtscharakter ist ein Pflichtattribut und muss immer befüllt sein!</i></div>
				<div id="Warnung_4" style="display: none" class="center60p"><i class="fa fa-exclamation-triangle fa-lg"> Das Attribut typ ist ein Pflichtattribut für Zentrale Orte und muss immer befüllt sein!</i></div>
		</div>
		<!-- SQL-Ausgabefenster -->
		<div id="sql_area" class="ganze-breite" style="display: none">
			<div id="sql_ausgabe" class="center60p box">
				<h3>SQL-Statement:
				<!--<a class="float-right" id="setValue"><input type="button" onclick="setValue();" value="Übernehmen"></a></h3>-->
				<a class="float-right" id="setValue"><button onclick="setValue()" value="Übernehmen"><i class="fa fa-arrow-circle-up fa-3x"></i></button></a></h3>
				<div id="sql_ausgabefenster" class="boxsql">
					<b>INSERT INTO</b> xplan_gml.<span id="sql_insertinto_featuretype"></span>(<span id="sql_insertinto_attributes"></span>position)</br>
					<b>SELECT</b>
					<div id="sql_select"></div>
					&nbsp;&nbsp;<span id="sql_position">the_geom AS position</span><br>
					<b>FROM</b><br>
					&nbsp;&nbsp;<a id="sql_shape_table"></a><br>
					<span id="sql_where" style="display:none">
						<b>WHERE</b>
					</span>
				</div>
				<!-- WHERE-Filter-->
				<!-- Filter Erstellen u. Löschen-->
				<button id="filter" onClick="filterEintragen()" title="Fügt der Regel einen Filter in Form einer WHERE Kondition hinzu, um nur bestimmte Teile einer Shape-Datei zu verwenden"><i class="fa fa-filter fa-lg"></i></button>
					<button id="filterloeschen" onClick="filterLoeschen()" title="Löscht alle WHERE-Konditions-Filter in der Regel" style="display:none"><i class="fa fa-trash-o fa-lg"></i></button>
				<!-- Filter Zuweisung -->
				<span id="where_zuweisung" style="display:none">
					<span id='where_zuweisung_shp_attribute'></span><!-- Zuweisung der SHP-Attribute durch Ajax -->
					<select id="where_operator_selector" onchange="getWhereOperator(this)">
						<option value="">Operator waehlen ...</option>
						<option value="=">=</option>
						<option value="<"><</option>
						<option value=">">></option>
						<option value="like">Aehnlich Wie (LIKE)</option>
					</select>
					<span id ="where_compare_value"></span>
					<input id="where_like_eingabe" style="display:none"></input>
					<!-- hier noch Integer und Distinct Values von Shape als Eingabe ermöglichen-->
					<button id="where_and_add" onClick="whereEintragenAnd()" title="Wert hinzufuegen und weiteren Wert aufnehmen"><i class="fa fa-plus fa-lg"></i></button>
					<button id="where_add" onClick="whereEintragen()" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg"></i></button>
				</span>
			</div>
		</div>
		<div style="clear: both; margin: 0px"></div>
		<!-- Regel-Zuweisungsgebiet -->
		<div id="rule_area" class="ganze-breite" style="display: none">
			<h2>Attributzuweisungen</h2>
			<div class="breite80p box">
				<span id="zuweisung_xplan_attribut"></span> <!-- Hier wird XPlan-Attribut eingetragen -->
				<span id="attribut_assignment_area" style="display:none"> : <!-- Gesamtes Zuweisungsgebiet-->
					<span id="attribut_assignment_area_normal">
						<select id="zuweisung_selector" style="display:none" onchange="zuweisungSelect()">
							<option value="">Zuweisungsart waehlen ...</option>
							<option value="fester_wert_selector">fester Wert</option>
							<option value="alle_aus_shape_attribut_selector">alle aus Shape Attribut</option>
							<option value="wenn_dann_selector">wenn dann aus Shape Attribut</option>
							</select>
						<!-- Fester Wert (Text) -->
						<input id="fester_wert_text" style="display: none"></input>
						<button id="fester_wert_text_array_add" style="display:none" onClick="festenWertTextEintragen(this.id)" title="Wert hinzufuegen und weiteren Wert aufnehmen"><i class="fa fa-plus fa-lg" ></i></button>
						<button id="fester_wert_text_add" style="display:none" onClick="festenWertTextEintragen(this.id)" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg"></i></button>
						<!-- Fester Wert (Integer) -->
						<input id="fester_wert_integer" type="text" style="display: none" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input> <!-- Legt fest, dass nur Zahlen eingegeben werden können-->
						<button id="fester_wert_integer_add" style="display:none" onClick="festenWertEintragenInteger()" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg"></i></button>
						<!-- Fester Wert: Boolean -->
						<select id="fester_wert_boolean" style="display:none" onChange="festenWertEintragenBoolean()">
							<option value="">Boolean-Wert waehlen ...</option>
							<option value="true">true</option>
							<option value="false">false</option>
						</select>
						<!-- Fester Wert: Enumerationsliste-->
						<!-- Hier werden Enumerationswerte mit Ajax als Optionen eingetragen-->
						<span id ="enumeration_select"></span>
						<!-- Wenn Dann -->
						<span id="wenn" style="display:none">wenn</span>
						<!-- Alle aus Shape-Attribut und Wenn Dann aus Shp Attribut-->
						<!-- -Wird durch AJAX Request (shpattribute2)gesetzt-->
						<span id='zuweisung_shp_attribute'></span>
						<span id='zuweisung_shp_attribute_wenn_dann' style="display:none"></span><!-- zeigt das Shapefile Attribut an, dass zum befüllen verwendet wird-->
						<span id="wenn_dann" style="display: none">
							<select id="wenn_dann_operator_selector">
								<option value="">Operator waehlen ...</option>
								<option value="=">=</option>
								<option value="<"><</option>
								<option value=">">></option>
								<!--<option value="LIKE">LIKE</option>--><!--Siehe WHERE-Filter LIKE. Für LIKE muss immer ein Texteingabefeld für die Zuordnung gegeben sein-->
							</select>
							<!-- Wählt distinkte Werte des jeweiligen Attributs durch AJAX aus-->
							<span id ="wenn_dann_compare_value"></span>
							dann
							<!-- Hier statt neuen Wenn Dann Selector die bereits existierenden Selektoren anderer Abfragen darstellen?-->
							<input id="wenn_dann_value_text_selector" style="display:none"></input>
							<button id="wenn_dann_text_case_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen und weiteren Case aufnehmen"><i class="fa fa-list fa-lg" ></i></button>
							<button id="wenn_dann_text_array_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen und weiteren Wert aufnehmen"><i class="fa fa-plus fa-lg" ></i></button>
							<button id="wenn_dann_text_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg"></i></button>
							<input id="wenn_dann_value_integer_selector" style="display:none" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input>
							<button id="wenn_dann_integer_case_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen und weiteren Case aufnehmen"><i class="fa fa-list fa-lg" ></i></button>
							<button id="wenn_dann_integer_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg" ></i></button>
							<select id="wenn_dann_value_boolean_selector" style="display:none">
								<option value="">Boolean-Wert waehlen ...</option>
								<option value="true">true</option>
								<option value="false">false</option>
							</select>
							<button id="wenn_dann_boolean_case_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen und weiteren Case aufnehmen"><i class="fa fa-list fa-lg" ></i></button>
							<button id="wenn_dann_boolean_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg"></i></button>
							<input id="wenn_dann_value_codelisten_selector" style="display:none"></input>
							<button id="wenn_dann_codelisten_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg"></i></button>
							<!-- Hier werden Enumerationswerte mit Ajax als Optionen eingetragen-->
							<span id ="wenn_dann_enumeration_select"></span>
							<button id="wenn_dann_enumeration_case_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen und weiteren Case aufnehmen"><i class="fa fa-list fa-lg" ></i></button>
							<button id="wenn_dann_enumeration_array_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen und weiteren Wert aufnehmen"><i class="fa fa-plus fa-lg" ></i></button>
							<button id="wenn_dann_enumeration_add" style="display:none" onClick="wennDannEintragen(this.id)" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg"></i></button>
						</span>
					</span>
					<!-- Externe Codeliste -->
					<span id="externe_codeliste" style="display: none">
						Quelle: 
						<input id="externe_codeliste_quelle"></input>
						Wert: 
						<input id="externe_codeliste_wert"></input>
						<button id="externe_codeliste_add" onClick="externeCodelisteEintragen()" title="Wert hinzufuegen"><i class="fa fa-level-up fa-lg"></i></button>
					</span>
					<!-- Fügt Tables für 3 komplexe Typen ExterneReferenz, GenerAttribut und Hoehenbezug ein-->
					<span id="komplexer_wert"><?include 'komplexerWertZusatz.html';?></span>
				</span>
			</div>
			<div class="breite100px box">
				<!--<button id="add_zuweisung" title= "Weitere Zuweisung für dieses Attribut hinzufuegen" onClick="weitereZuweisung()" style="display:none"><i class="fa fa-plus"></i></button>-->
				<button id="remove_zuweisung" title= "Attributzuweisung löschen" onClick="resetAttributzuweisungen()"><i class="fa fa-trash-o fa-lg"></i></button>
			</div>
		</div>
		<div id="source_and_target_area">
			<table style="width:96%">
				<tr align="left" valign="top" style="width:96%">
					<td align="top left">
						<!--<div class="halbe-breite box">-->
						<?php
							featuretype_liste($this->pgdatabase->dbConn, $class_name);
						?>
						<div id="target_table"></div>
						</div>
					</td>
					<td width="48%" align="top left">
						<!--<div class="halbe-breite box">-->
						<select id="source_selector" onChange="setShapefile()">
						<option value="">Shape Datei waehlen ...</option>
							<?php
								shapeTables($this->pgdatabase->dbConn, $konvertierung_id);
							?>
						</div>
					</td>
				<tr>
			</table>
		</div>
		<div style="clear: both; margin: 0px"></div>
	</BODY>

	<!-- PHP FUNKTIONEN -->
	<?php
	function shapeTables($conn, $konvertierung_id){
		$shp_tables = array();
		$sql = "
			SELECT
				table_name
			FROM
				information_schema.tables
			WHERE
				table_schema = 'xplan_shapes_" . $konvertierung_id . "'
			ORDER BY
				table_name
		";
		echo '<br>Frage Shape Tabellen der Konvertierung (' . $konvertierung_id . ') ab mit sql:<br>';
		$result = pg_query($conn, $sql);
		$num_rows = pg_num_rows($result);
		while ($row = pg_fetch_row($result)) {
			echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
			$shp_tables[] = $row[0];
		}
		echo '</select>';

		#Fehlermeldung bei nicht vorhandenen Shape-Dateien
		if($num_rows =0) {
			echo '<script>';
			echo '$("#fehler_area").append("<b>Fehler: Bevor der Query Builder verwendet werden kann, müssen zum Plan gehörige Shapefiles hochgeladen werden!</b>");';
			echo '$("#target_selector").hide();';
			echo '$("#source_selector").hide();';
			echo '$("#warnung_area").hide();';
			echo '</script>';
		}
		echo '<b><span id="source"></span></b>';
		echo '<br>';
		foreach($shp_tables as $shp_table) { ?>
			<div id="<?php echo $shp_table; ?>_source_attributes" style="display: none">
				<table border="1" cellspacing="0" cellpadding="2" style="width:100%; margin-top: 6px"">
					<tr>
						<th style="background-color: <?php echo BG_DEFAULT; ?>"><b>Attribut</b></th>
						<th style="background-color: <?php echo BG_DEFAULT; ?>"><b>Datentyp</b></th>
						<th style="background-color: <?php echo BG_DEFAULT; ?>">Werte</th>
					</tr><?php
			$sql ="
				SELECT
					column_name, data_type
				FROM
					information_schema.columns
				WHERE
					table_name='" . $shp_table . "'
				AND
					table_schema = 'xplan_shapes_" . $konvertierung_id . "'
				ORDER BY
					column_name
				";
			$result = pg_query($conn, $sql);

			$sql_geom ="
				SELECT ST_GeometryType(the_geom)
				FROM
				xplan_shapes_" . $konvertierung_id . "." . $shp_table . " LIMIT 1";
			$result_geom = pg_query($conn, $sql_geom);

			while ($row =pg_fetch_row($result)){
				echo '<tr>';
				echo '<td>' . $row[0] . '</td>';
				if($row[0] != 'the_geom'){
					echo '<td>' . $row[1] . '</td>';
				} else { while($row_geom = pg_fetch_row($result_geom)){
						// Removes ST_ from ST_MultiLineString or ST_Point
						$geom = substr($row_geom[0], 3);
						echo '<td><div id="geom_' . $shp_table . '">' . $geom . '</td>';
					}
				}
				echo '<td align="center"><button id ="distinct_' . $row[0] . '" title="Zeigt die ersten 100 Werte an" onclick="distinctValues(this.id)"><i class="fa fa-question-circle fa-lg"></i></button>';
				echo '<span id="distinctValues_' . $shp_table . "_" . $row[0] . '" style="display: none">';
				$sqlDistinct = "
					SELECT 
						DISTINCT " . $row[0] . "
					FROM
						xplan_shapes_" . $konvertierung_id . "." . $shp_table . "
					LIMIT 100
				";
				$resultDistinct = pg_query($conn, $sqlDistinct);
				while ($rowDistinct = pg_fetch_row($resultDistinct)) {
					echo '<br>' . $rowDistinct[0];
				}
				echo '</span>';
				echo '</tr>';
				}
			echo '</table>';
			echo '</div>';
		}
	}

	function featuretype_liste($conn, $class_name){
		# Wählt zuerst alle Featuretypes aus xplan_gml aus, auf die Regeln geschrieben werden können
		$gml_classes = array();
		$sql = "
			SELECT 
				table_name
			FROM 
				information_schema.tables 
			WHERE 
				table_schema = 'xplan_gml'
			AND
				table_name LIKE 'rp_%'
			AND
				table_name NOT IN ('rp_plan', 'rp_geometrieobjekt', 'rp_objekt', 'rp_bereich', 'rp_status', 'rp_bereich_zu_rp_objekt', 'rp_bereich_zu_rp_rasterplanaenderung', 'rp_featuretypeliste', 'rp_praesentationsobjekt', 'rp_textabschnitt', 'rp_spezifischegrenzetypen', 'rp_generischesobjekttypen', 'rp_rasterplanaenderung', 'rp_sonstgrenzetypen', 'rp_sonstplanart')
			ORDER BY table_name
		";
		$result = pg_query($conn, $sql);
		# Setzt ein Selektorfeld, das jeden Featuretype beinhaltet und speichert die Werte in einer Variable
		echo '<select id="target_selector" onChange="chooseFeatureTable()">';
		echo '<option value="">XPlan FeatureType waehlen ...</option>';
		$was_selected = false;
		while ($row = pg_fetch_assoc($result)) {
			if ($row['table_name'] == strtolower($class_name)) {
				$selected = ' selected';
				$was_selected = true;
			}
			else {
				$selected = '';
			}
			echo '<option value="' . $row['table_name'] . '"' . $selected . '>' . $row['table_name'] . '</option>';
			$gml_classes[] = $row['table_name'];
		}
		echo ' </select>';
		echo '<b><span id="target"></span></b><br>';
		if ($was_selected) { ?>
				<script type="text/javascript">
				 chooseFeatureTable();
				</script><?php
		}
	}
?></HTML>