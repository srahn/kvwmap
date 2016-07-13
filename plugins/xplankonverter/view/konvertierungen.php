<?php
  include('header.php');
?>
<script language="javascript" type="text/javascript">
  $(function () {
    result = $('#eventsResult');

    // event handler
    $('#konvertierungen_table')
    .on('load-success.bs.table', function (e, data) {
      result.text('Tabelle erfolgreich geladen.');
      $('.fa-play').click(
        starteKonvertierung
      );
      $('.fa-trash').click(
        loescheKonvertierung
      );
    })
    .on('load-error.bs.table', function (e, status) {
      result.text('Event: load-error.bs.table');
    });
    // more examples for register events on data tables: http://jsfiddle.net/wenyi/e3nk137y/36/
  });

  // functions
  starteKonvertierung = function(e) {
    var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');
    result.text('Starte Konvertierung für Id: ' + konvertierung_id);
    $.ajax({
      url: 'index.php?go=xplankonverter_konvertierung_ausfuehren',
      data: {
        konvertierung_id: konvertierung_id
      },
      success: function(response) {
        result.text(response.msg);
      }
    });
    location.reload(true);
  };

  loescheKonvertierung = function(e) {
    var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');
    $(this).closest('tr').remove();
    result.text('Lösche Konvertierung für Id: ' + konvertierung_id);
    $.ajax({
      url: 'index.php?go=xplankonverter_konvertierung_loeschen',
      data: {
        konvertierung_id: konvertierung_id
      },
      success: function(response) {
        result.text(response.msg);
      }
    });
  };

	function konvertierungFunctionsFormatter(value, row) {
    output = '<span konvertierung_id="' + value + '">';
    output +=  '<a title="Konvertierung bearbeiten" href="index.php?go=Layer-Suche_Suchen&selected_layer_id=8&operator_konvertierung_id==&value_konvertierung_id=' + value + '"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
    output += '<a title="Shapefiles bearbeiten" href="index.php?go=xplankonverter_shapefiles_index&konvertierung_id=' + value + '"><i class="fa fa-upload"></i></a>&nbsp;&nbsp;';
    output += '<a title="Konvertierung validieren" href="index.php?go=xplankonverter_konvertierungen_validate&konvertierung_id=' + value + '"><i class="fa fa-check-square-o"></i></a>&nbsp;';
    output += '<a id="konvertierung_ausfuehren" href="#" title="Konvertierung ausf&uuml;hren"><i class="fa fa-play"></i></a>&nbsp;';
    output += '<a id="konvertierung_loeschen" href="#" title="Konvertierung l&ouml;schen"><i class="fa fa-trash"></i></a>&nbsp;';
    output += '</span>';
    return output;
  }

</script>
<!--ul class='nav nav-tabs'>
  <li class='<?php echo ($config['active']==='step1' ? 'active' : '');?>'><a data-toggle='tab'>Schritt 1</a></li>
  <li class='<?php echo ($config['step2']['disabled'] ? 'disabled' : ($config['active']==='step2' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 2</a></li>
  <li class='<?php echo ($config['step3']['disabled'] ? 'disabled' : ($config['active']==='step3' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 3</a></li>
  <li class='<?php echo ($config['step4']['disabled'] ? 'disabled' : ($config['active']==='step4' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 4</a></li>
  <li><a data-toggle='tab' href='#map'>Karte</a></li>
</ul//-->
<h2>Konvertierungen</h2>
<div class="alert alert-success" style="white-space: pre-wrap" id="eventsResult">
    Here is the result of event.
</div>
<table
  id="konvertierungen_table"
  data-toggle="table"
  data-url="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID; ?>&anzahl=1000&mime_type=formatter&format=json"
  data-height="100%"
  data-click-to-select="false"
  data-sort-name="bezeichnung"
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
        data-field="konvertierung_id"
        data-sortable="true"
        data-visible="false"
        data-switchable="true"
      >Konvertierung Id</th>
      <th
        data-field="bezeichnung"
        data-sortable="true"
        data-visible="true"
      >Bezeichnung</th>
      <th
        data-field="status"
        data-visible="true"
        data-sortable="true"
      >Status</th>
      <th
        data-field="beschreibung"
        data-visible="false"
      >Beschreibung</th>
      <th
        data-field="konvertierung_id"
        data-visible="true"
        data-formatter="konvertierungFunctionsFormatter"
        data-switchable="false"
        class="text-right"
      >Funktionen</th>
    </tr>
  </thead>
</table>

<button class="button" type="button" id="new_konvertierung" name="go_plus" onclick="location.href='index.php?go=neuer_Layer_Datensatz&selected_layer_id=8'">neu</button>
