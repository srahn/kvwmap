<?php
  include('header.php');
?>
<script language="javascript" type="text/javascript">
	function konvertierungFunctionsFormatter(value, row) {
    output =  '<a title="Konvertierung bearbeiten" href="index.php?go=Layer-Suche_Suchen&selected_layer_id=8&operator_konvertierung_id==&value_konvertierung_id=' + value + '"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
    output += '<a title="Shapefiles bearbeiten" href="index.php?go=xplankonverter_shapefiles_index&konvertierung_id=' + value + '"><i class="fa fa-upload"></i></a>&nbsp;&nbsp;';
    output += '<a title="Konvertierung validieren" href="index.php?go=xplankonverter_konvertierungen_validate&konvertierung_id=' + value + '"><i class="fa fa-check-square-o"></i></a>&nbsp;';
    output += '<a title="Konvertierung ausf&uuml;hren" href="index.php?go=xplankonverter_konvertierungen_execute&konvertierung_id=' + value + '"><i class="fa fa-play"></i></a>&nbsp;';
    output += '<a title="Konvertierung l&ouml;schen" href=""><i class="fa fa-trash"></i></a>&nbsp;';
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
<table
  id="konvertierungen_table"
  data-toggle="table"
  data-url="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID; ?>&mime_type=formatter&format=json"
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
