<?php
  include('header.php');
  if ($this->Fehlermeldung!='') {
    include(LAYOUTPATH."snippets/Fehlermeldung.php");
  }
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

function validierung_msg_correcture_formatter(value, row) {
	if ( row.ergebnis_status == 'Fehler' || row.ergebnis_status == 'Warnung') {
		output = row.validierung_msg_correcture;
	}
	else {
		output = '';
	}
	if (row.regel_id)
		output += '<br>zur Regel <a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_REGELN_LAYER_ID; ?>&operator_id==&value_id=' + row.regel_id + '"><i class="fa fa-lg fa-pencil"></i></a>';
	if (row.shape_gid) {
		if (row.regel_shp_layer_id) {
			// Direkt zur Anzeige des Datensatzes
			output += ' zum Objekt <a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=' + row.regel_shp_layer_id + '&operator_gid==&value_gid=' + row.shape_gid + '"><i class="fa fa-lg fa-pencil"></i></a>';
		}
		else {
			// Zur Suche in der Layergruppe der Shape-Dateien der Konvertierung
			output += ' zum Objekt <a href="index.php?go=Layer-Suche&selected_group_id=' + row.shape_layer_group_id + '"><i class="fa fa-lg fa-pencil"></i></a>';
		}
	}
	return output;
}

var styleMap = {
  "Erfolg"      : "success",
  "Warnung"     : "warning",
  "Fehler"      : "danger",
  "Information" : "info"
}
function validierungsergebnisseRowStyle(row, index){
  return {
    classes: styleMap[row.ergebnis_status]
  };
}
function validierungsergebnisseRowAttribs(row, index){
  return {
    title : row.validierung_beschreibung
  };
}
</script>
<h2>Validierungsergebnisse</h2>
<br>
<h4><?php echo $this->konvertierung->plan->planart . ': ' . htmlspecialchars($this->konvertierung->plan->get_anzeige_name()); ?></h4>

<link href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/export/bootstrap-table-export.min.js"></script>

<style>
#toolbar {
  margin: 0;
}
.btn-group > .btn:first-child {
	margin-left: 0px;
	height: 32px;
}
</style>

<div id="toolbar" class="select">
  <select class="form-control">
    <option value="">Export Basic</option>
    <option value="all">Export All</option>
    <option value="selected">Export Selected</option>
  </select>
</div>

<table id="validierungsergebnis_table"
  data-toggle="table"
  data-url="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_VALIDIERUNGSERGEBNISSE_LAYER_ID; ?>&anzahl=1000&operator_konvertierung_id==&value_konvertierung_id=<?php echo $this->formvars['konvertierung_id']; ?>&mime_type=formatter&format=json"
  data-height="100%"
  data-click-to-select="true"
  data-toolbar="#toolbar"
  data-sort-name="ergebnis_order"
  data-sort-order="asc"
  data-search="false"
  data-show-refresh="false"
  data-show-toggle="true"
  data-show-columns="true"
  data-query-params="queryParams"
  data-pagination="true"
  data-side-pagination="server"
  data-page-size="25"
  data-show-export="true"
  data-row-style="validierungsergebnisseRowStyle"
  data-row-attributes="validierungsergebnisseRowAttribs"
  data-export-types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
>
  <thead>
    <tr>
      <th
        data-field="validierungsergebnis_id"
        data-visible="false"
        data-switchable="true"
        data-sortable="true"
        class="text-right"
      >ID</th>
      <th
        data-field="regel_name"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-left"
      >Regel</th>
      <th
        data-field="validierung_name"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-left"
      >Validierung</th>
      <th
        data-field="validierung_beschreibung"
        data-visible="false"
        data-switchable="true"
        data-sortable="false"
        class="text-left"
      >Beschreibung</th>
      <th
        data-field="ergebnis_status"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-left"
      >Status</th>
      <th
        data-field="ergebnis_order"
        data-visible="false"
        data-switchable="false"
        data-sortable="false"
        class="text-left"
      >Reihenfolge</th>
      <th
        data-field="ergebnis_msg"
        data-formatter="validierung_msg_formatter"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-left"
      >Meldungen</th>
      <th
        data-field="regel_sql"
        data-visible="false"
        data-switchable="true"
        data-sortable="false"
        class="text-left"
      >Regel SQL</th>
      <th
        data-field="validierung_msg_correcture"
				data-formatter="validierung_msg_correcture_formatter"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-left"
      >Korrekturhinweise</th>
      <th
        data-field="regel_id"
        data-visible="false"
        data-switchable="false"
        class="text-right"
      >Regel_id</th>
    </tr>
  </thead>
</table>
<script>
/*
  var $table = $('#validierungsergebnis_table')

  $(function() {
    $('#toolbar').find('select').change(function () {
      $table.bootstrapTable('destroy').bootstrapTable({
        exportDataType: $(this).val(),
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
        columns: [
          {
            field: 'state',
            checkbox: true,
            visible: $(this).val() === 'selected'
          },
          {
            field: 'id',
            title: 'ID'
          }, {
            field: 'name',
            title: 'Item Name'
          }, {
            field: 'price',
            title: 'Item Price'
          }
        ]
      })
    }).trigger('change')
  })
*/
</script>
<div style="clear:both"></div>
<form action="index.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="go" value="xplankonverter_plaene_index">
	<input type="hidden" name="planart" value="<?php echo $this->konvertierung->get('planart'); ?>">
	<input type="hidden" name="konvertierung_id" value="<?php echo $this->formvars['konvertierung_id']; ?>">
	<input type="submit" name="submit" value="zurück">
</form>
<p>
