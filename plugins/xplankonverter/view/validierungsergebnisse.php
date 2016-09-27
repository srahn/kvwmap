<?php include('header.php'); ?>

<h2>Validierungsergebnisse</h2>
<?php
if ($this->Fehlermeldung!='') {
       include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>
</div>
<br>
<script language="javascript" type="text/javascript">
</script>
<table
  id="validierungsergebnis_table"
  data-toggle="table"
  data-url="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_VALIDIERUNGSERGEBNISSE_LAYER_ID; ?>&anzahl=1000&operator_konvertierung_id==&value_konvertierung_id=<?php echo $this->formvars['konvertierung_id']; ?>&mime_type=formatter&format=json"
  data-height="100%"
  data-click-to-select="false"
  data-sort-name="validierungsergebnis_id"
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
        data-field="validierungsergebnis_id"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-right"
      >ID</th>
      <th
        data-field="validierung_name"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-left"
      >Validierung</th>
      <th
        data-field="ergebnis_status"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-left"
      >Status</th>
      <th
        data-field="ergebnis_msg"
        data-visible="true"
        data-switchable="true"
        data-sortable="true"
        class="text-left"
      >Meldung</th>
    </tr>
  </thead>
</table>
<div style="clear:both"></div>
<form action="index.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="go" value="xplankonverter_konvertierungen_index">
  <input type="hidden" name="konvertierung_id" value="<?php echo $this->formvars['konvertierung_id']; ?>">
	<input type="submit" name="submit" value="zurück">
</form>
<p>