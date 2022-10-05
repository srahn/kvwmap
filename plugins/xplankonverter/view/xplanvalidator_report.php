<?php
	include('header.php');
?><script language="javascript" type="text/javascript">
// formatter functions
function validierung_msg_formatter(value, row) {
	if (row.ergebnis_status == 'Fehler')
		return row.validierung_msg_error + '<br>' + value;
	else if (row.ergebnis_status == 'Warnung')
		return row.validierung_msg_warning + '<br>' + value;
	else
		return (value !='' ? value : row.validierung_msg_success);
}

var styleMap = {
	"Erfolg"			: "success",
	"Warnung"		 : "warning",
	"Fehler"			: "danger",
	"Information" : "info"
}

function booleanFormatter(value, row) {
	return (value ? 'ja' : 'nein');
}

function validierungsergebnisseRowStyle(row, index){
	return {
		classes: styleMap[row.ergebnis_status]
	};
}
function validierungsergebnisseRowAttribs(row, index){
	return {
		title : row.name + ' ' + row.message
	};
}
</script>
<h2>Ergebnisse der semantischen Prüfung<br>im XPlanValidator der Leitstelle</h2>
<br>
<h4><?php echo $this->konvertierung->plan->planart . ': ' . htmlspecialchars($this->konvertierung->plan->get_anzeige_name()); ?></h4>

<link href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css" rel="stylesheet">

<!--script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/export/bootstrap-table-export.min.js"></script//-->

<div class="table-wrapper">
	<table id="xplanvalidator_semantic_report"
		data-toggle="table"
		data-url="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_XPLANVALIDATOR_SEMANTIC_REPORT_LAYER_ID; ?>&anzahl=1000&operator_konvertierung_id==&value_konvertierung_id=<?php echo $this->formvars['konvertierung_id']; ?>&mime_type=formatter&format=json"
		data-height="100%"
		data-click-to-select="true"
		data-toolbar="#toolbar"
		data-sort-name="id"
		data-sort-order="asc"
		data-search="false"
		data-show-refresh="false"
		data-show-toggle="true"
		data-show-columns="true"
		data-query-params="queryParams"
		data-pagination="true"
		data-side-pagination="server"
		data-page-list=[10,15,25,50,100,250,all]
		data-page-size="25"
		data-show-export="true"
		data-row-style="validierungsergebnisseRowStyle"
		data-row-attributes="validierungsergebnisseRowAttribs"
		data-export-types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
	>
		<thead>
			<tr>
				<th
					data-field="id"
					data-visible="false"
					data-switchable="true"
					data-sortable="true"
					class="text-right"
				>ID</th>
				<th
					data-field="name"
					data-visible="true"
					data-switchable="true"
					data-sortable="true"
					class="text-left"
				>Regel-Nr.</th>
				<th
					data-field="message"
					data-visible="true"
					data-switchable="true"
					data-sortable="true"
					class="text-left"
				>Regel</th>
				<th
					data-field="isvalid"
					data-visible="true"
					data-switchable="true"
					data-sortable="true"
					data-formatter="booleanFormatter"
					class="text-left"
				>valide</th>
				<th
					data-field="invalidefeatures"
					data-visible="true"
					data-switchable="true"
					data-sortable="true"
					class="text-left"
				>nicht valide Objekte</th>
				<th
					data-field="konvertierung_id"
					data-visible="false"
					data-switchable="true"
					data-sortable="true"
					class="text-left"
				>Konvertierung-ID</th>
				<th
					data-field="stelle_id"
					data-visible="false"
					data-switchable="true"
					data-sortable="true"
					class="text-left"
				>Stelle-ID</th>
			</tr>
		</thead>
	</table>
</div>
<div style="clear:both"></div>
<form action="index.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="go" value="xplankonverter_plaene_index">
	<input type="hidden" name="planart" value="<?php echo $this->konvertierung->get('planart'); ?>">
	<input type="hidden" name="konvertierung_id" value="<?php echo $this->formvars['konvertierung_id']; ?>">
	<input type="submit" name="submit" value="zurück">
</form>