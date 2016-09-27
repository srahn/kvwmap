<?php
  include('header.php');
?>
<script language="javascript" type="text/javascript">
  $(function () {
    result = $('#eventsResult');
    result.success = function(text){
      result.text(text);
      result.removeClass('alert-danger');
      result.addClass('alert-success');
    };
    result.error = function(text){
      result.text(text);
      result.removeClass('alert-success');
      result.addClass('alert-danger');
    };

    // event handler
    $('#konvertierungen_table')
    .one('load-success.bs.table', function (e, data) {
      result.success('Tabelle erfolgreich geladen.');
    })
    .on('load-success.bs.table', function (e, data) {
      $('.xpk-func-convert').click(
        starteKonvertierung
      );
      $('.xpk-func-generate-gml').click(
        starteGmlAusgabe
      );
      $('.xpk-func-del-konvertierung').click(
        loescheKonvertierung
      );
    })
    .on('load-error.bs.table', function (e, status) {
      result.error('Event: load-error.bs.table');
    });
    // more examples for register events on data tables: http://jsfiddle.net/wenyi/e3nk137y/36/
  });

  // functions
  starteKonvertierung = function(e) {
    var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');
    result.success('Starte Konvertierung und Validierung für Konvertierung-Id: ' + konvertierung_id);
    // set status to 'IN_KONVERTIERUNG'
    $.ajax({
      url: 'index.php?go=xplankonverter_konvertierung_status',
      data: {
        konvertierung_id: konvertierung_id,
        status: "<?php echo Konvertierung::$STATUS['IN_KONVERTIERUNG']; ?>"
      },
      success: function(response) {
        if (!response.success){
          result.success(response.msg);
          return;
        }
        $('#konvertierungen_table').bootstrapTable('refresh');
        // konvertiere wenn Status gesetzt
        $.ajax({
          url: 'index.php?go=xplankonverter_regeln_anwenden',
          data: {
            konvertierung_id: konvertierung_id
          },
          error: function(response) {
            result.error(response.msg);
          },
          success: function(response) {
            result.success(response.msg);
            if (!response.success) return;
            // validiere, wenn Konvertierung erfolgreich
            $.ajax({
              url: 'index.php?go=xplankonverter_konvertierung_validate',
              data: {
                konvertierung_id: konvertierung_id
              },
              error: function(response) {
                result.error(response.msg);
              },
              success: function(response) {
                $('#konvertierungen_table').one('load-success.bs.table', function () {
                  result.success(response.msg);
                });
                $('#konvertierungen_table').bootstrapTable('refresh');
              }
            });
          }
        });
      }
    });
  };

  starteGmlAusgabe = function(e) {
    var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');
    result.success('Starte GML-Ausgabe für Konvertierung-Id: ' + konvertierung_id);
    // set status to 'IN_GML_ERSTELLUNG'
    $.ajax({
      url: 'index.php?go=xplankonverter_konvertierung_status',
      data: {
        konvertierung_id: konvertierung_id,
        status: "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>"
      },
      error: function(response) {
        result.error('Fehler beim Starten der GML-Erstellung für Konvertierung-Id: ' + konvertierung_id);
        return;
      },
      success: function(response) {
        $('#konvertierungen_table').bootstrapTable('refresh');
        // gml-erzeugung starten
        $.ajax({
          url: 'index.php?go=xplankonverter_gml_generieren',
          data: {
            konvertierung_id: konvertierung_id
          },
          error: function(response) {
            $('#konvertierungen_table').bootstrapTable('refresh');
            result.error('Fehler bei der GML-Erstellung für Konvertierung-Id: ' + konvertierung_id);
            console.error(response.responseText);
          },
          success: function(response) {
            if (!response.success){
              result.error(response.msg);
              return;
            }
            $('#konvertierungen_table').one('load-success.bs.table', function () {
              result.success(response.msg);
            });
            $('#konvertierungen_table').bootstrapTable('refresh');
          }
        });
      }
    });
  };

  // formatter functions
  function konvertierungFunctionsFormatter(value, row) {
    var funcIsDisabled, funcIsInProgress
      disableFrag = ' disabled" onclick="return false';
    output = '<span class="btn-group" role="group" konvertierung_id="' + value + '">';
    // enabled by status of konvertierung
    // Bearbeiten
    funcIsDisabled = row.status == "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>"
                  || row.status == "<?php echo Konvertierung::$STATUS['IN_KONVERTIERUNG']; ?>";
    output += '<a title="Konvertierung bearbeiten" class="btn btn-link btn-xs xpk-func-btn' + (funcIsDisabled ? disableFrag : '') + '" href="index.php?go=Layer-Suche_Suchen&selected_layer_id=8&operator_konvertierung_id==&value_konvertierung_id=' + value + '"><i class="fa fa-lg fa-pencil"></i></a>';

    // Shapefile upload
    funcIsDisabled = row.status != "<?php echo Konvertierung::$STATUS['ERSTELLT']; ?>";
    output += '<a title="Shapefiles bearbeiten" class="btn btn-link btn-xs  xpk-func-btn' + (funcIsDisabled ? disableFrag : '') + '" href="index.php?go=xplankonverter_shapefiles_index&konvertierung_id=' + value + '"><i class="fa fa-lg fa-upload"></i></a>';

    // Konvertieren und validieren
    funcIsDisabled = row.status == "<?php echo Konvertierung::$STATUS['IN_ERSTELLUNG']; ?>"
                  || row.status == "<?php echo Konvertierung::$STATUS['IN_KONVERTIERUNG']; ?>"
                  || row.status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_ERR']; ?>"
                  || row.status == "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>";
    funcIsInProgress = row.status == "<?php echo Konvertierung::$STATUS['IN_KONVERTIERUNG']; ?>";
    output += '<a title="Konvertierung durchführen & validieren" class="btn btn-link btn-xs xpk-func-btn xpk-func-convert' + (funcIsDisabled ? disableFrag : '') + '" href="#"><i class="' + (funcIsInProgress ? 'fa fa-spinner fa-pulse fa-fw' : 'fa fa-lg fa-cogs') + '"></i></a>';

    // GML-Erzeugen
    funcIsDisabled = row.status != "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK']; ?>"
                  && row.status != "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK']; ?>";
    funcIsInProgress = row.status == "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>";
    output += '<a title="GML-Datei ausgeben" class="btn btn-link btn-xs xpk-func-btn xpk-func-generate-gml' + (funcIsDisabled ? disableFrag : '') + '" href="#"><i class="' + (funcIsInProgress ? 'fa fa-spinner fa-pulse fa-fw' : 'fa fa-lg fa-code') + '"></i></a>';

    // GML-Download
    funcIsDisabled = row.status != "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK']; ?>";
    output += '<a title="GML-Datei herunterladen" class="btn btn-link btn-xs xpk-func-btn xpk-func-download-gml' + (funcIsDisabled ? disableFrag : '') + '" href="index.php?go=xplankonverter_gml_ausliefern&konvertierung_id=' + value + '" download="xplan_' + value + '.gml"><i class="fa fa-lg fa-download"></i></a>';

    // Konvertierung Löschen
    funcIsDisabled = row.status == "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>"
                  || row.status == "<?php echo Konvertierung::$STATUS['IN_KONVERTIERUNG']; ?>";
    output += '<a title="Konvertierung l&ouml;schen" class="btn btn-link btn-xs xpk-func-btn xpk-func-del-konvertierung' + (funcIsDisabled ? disableFrag : '') + '" href="#"><i class="fa fa-lg fa-trash"></i></a>';

    output += '</span>';
    return output;
  }

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

</script>
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
				class="col-md-2"
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
        class="col-md-4"
      >Funktionen</th>
    </tr>
  </thead>
</table>

<button class="button" type="button" id="new_konvertierung" name="go_plus" onclick="location.href='index.php?go=neuer_Layer_Datensatz&selected_layer_id=8'">neu</button>
