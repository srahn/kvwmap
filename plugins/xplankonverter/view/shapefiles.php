<link rel="stylesheet" href="<?php echo BOOTSTRAP_PATH; ?>css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo BOOTSTRAPTABLE_PATH; ?>bootstrap-table.min.css" type="text/css">
<link rel="stylesheet" href="plugins/xplankonverter/styles/design.css" type="text/css">
<link rel="stylesheet" href="plugins/xplankonverter/styles/styles.css" type="text/css">

<script src="<?php echo JQUERY_PATH; ?>jquery-1.12.0.min.js"></script>
<script src="<?php echo JQUERY_PATH; ?>jquery.base64.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>js/bootstrap.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>js/bootstrap-table-flatJSON.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>bootstrap-table.min.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>extension/bootstrap-table-export.min.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>locale/bootstrap-table-de-DE.min.js"></script>
<h2>Hochgeladene Dateien</h2>
<br>
<script language="javascript" type="text/javascript">
  function shapeFileFunctionsFormatter(value, row) {
		output  = '\
			<span class="btn-group" role="group" shapefiles_oid="' + row.shapefiles_oid + '" shapefile_id="' + value + '">\
    		<a\
					title="Shape-Datei bearbeiten"\
					href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_SHAPEFILES_LAYER_ID; ?>&operator_shapefile_id==&value_shapefile_id=' + value + '"\
					class="btn btn-link btn-xs xpk-func-btn"\
				>\
					<i class="btn-link fa fa-lg fa-pencil"></i>\
				</a>\
				&nbsp;&nbsp;\
				<a\
					title="Shape-Datei l&ouml;schen"\
					href="#"\
					class="btn btn-link btn-xs xpk-func-btn"\
					onclick="loescheShapeFile(this)"\
				>\
					<i class="btn-link fa fa-lg fa-trash"></i>\
				</a>\
			</span>&nbsp;\
		';
    return output;
  }

	function loescheShapeFile(e) {
		var shapefile_id = e.parentElement.getAttribute('shapefile_id'),
				shapefile_oid = e.parentElement.getAttribute('shapefiles_oid'),
		    r = confirm("Soll die Shape-Datei mit der Id " + shapefile_id + " wirklich gelöscht werden?");

		if (r == true) {
			message([{type: 'notice', msg: 'Lösche Shape-Datei mit Id: ' + shapefile_id}]);
			$.ajax({
				url: 'index.php',
				data: {
					go: 'xplankonverter_shapefile_loeschen',
					shapefile_oid: shapefile_oid
				},
				success: function(response) {
					$(e).closest('tr').remove();
					message([{type: 'notice', msg: response.msg}]);
				}
			});
		}
	};

	function uploadFiles() {
		$('#GUI input[name=go]').val('xplankonverter_shapefiles_index')
		if ($('#GUI input[name="shape_files[]"]')[0].files.length == 0) {
			message('Es muss mindestens eine Datei ausgewählt werden. Klicken Sie dazu auf Dateien auswählen!');
		}
		else {
			$('#GUI').submit();
		}
	}
	function backToPlaene() {
		$('#GUI input[name=go]').val('xplankonverter_plaene_index')
		$('#GUI').submit();
	}
</script>
<table
  id="shapefiles_table"
  data-toggle="table"
  data-url="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_SHAPEFILES_LAYER_ID; ?>&anzahl=1000&operator_konvertierung_id==&value_konvertierung_id=<?php echo $this->formvars['konvertierung_id']; ?>&mime_type=formatter&format=json"
  data-height="100%"
  data-click-to-select="false"
  data-sort-name="filename"
  data-sort-order="asc"
  data-search="false"
  data-show-refresh="false"
  data-show-toggle="false"
  data-show-columns="true"
  data-query-params="queryParams"
  data-pagination="true"
  data-page-size="25"
  data-show-export="false"
  data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
>
  <thead>
    <tr>
      <th
        data-field="shapefiles_oid"
        data-visible="false"
        data-switchable="true"
        class="text-right"
      >DB OID</th>
      <th
        data-field="shapefile_id"
        data-visible="false"
        data-switchable="true"
        class="text-right"
      >Shapefile ID</th>
      <th
        data-field="filename"
        data-sortable="true"
        data-visible="true"
      >Shapedatei Dateiname</th>
      <th
        data-field="epsg_code"
        data-sortable="true"
        data-visible="true"
      >EPSG-Code</th>
      <th
        data-field="shapefile_id"
        data-visible="true"
        data-formatter="shapeFileFunctionsFormatter"
        data-switchable="false"
        class="text-right"
      >Bearbeiten</th>
    </tr>
  </thead>
</table>
<div style="clear:both"></div>
<input type="hidden" name="konvertierung_id" value="<?php echo $this->formvars['konvertierung_id']; ?>">
<input type="hidden" name="planart" value="<?php echo $this->konvertierung->get('planart'); ?>">
<input type="hidden" name="go" value="">
<input type="file" name="shape_files[]" multiple>
<select name="epsg_code"><?php
	foreach($this->konvertierung->get_input_epgs_codes() AS $epsg_code) {
		echo "<option value=\"{$epsg_code}\"" . ($this->konvertierung->get('input_epsg') == $epsg_code ? ' selected' : '') . ">{$epsg_code}</option>";
	} ?>
</select>
<input type="button" value="Upload" onclick="uploadFiles()"><br>
<input type="button" value="zurück" style="margin-top: 5px" onclick="backToPlaene()">
<p>
<?php
  if (!empty($uploaded_files)) {
    echo '<p><b>Hochgeladene Dateien</b>';
    array_map(
      function($uploaded_file) {
        echo '<br>Datei ' . $uploaded_file['state'] . ': ' . $uploaded_file['basename'];
      },
      $uploaded_files
    );
  }
?>