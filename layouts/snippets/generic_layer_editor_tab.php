<link href="<? echo THIRDPARTY_PATH; ?>jquery-ui-1.14.1/jquery-ui.min.css" rel="stylesheet">
<!--link href="<? echo THIRDPARTY_PATH; ?>tabulator/css/tabulator.min.css" rel="stylesheet" //-->
<link href="<? echo THIRDPARTY_PATH; ?>tabulator/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo THIRDPARTY_PATH; ?>jquery-ui-1.14.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo THIRDPARTY_PATH; ?>tabulator/js/tabulator.min.js"></script>
<script type="text/javascript" src="<?php echo THIRDPARTY_PATH; ?>tabulator/js/jquery_wrapper.js"></script>
<h1 style="margin-bottom: 12px"><? echo $this->qlayerset[0]['Name_or_alias']; ?></h1>
<!--Formvars: <? echo print_r($this->formvars, true); ?><p>
Layer Keys: <?php echo implode('<br>', array_keys($this->qlayerset[0])); ?><p>
Attribute: <?php echo print_r($this->qlayerset[0]['attributes'], true); ?><p>
Daten: <?php echo print_r($this->qlayerset[0]['shape'], true); ?><p>//-->
<div id="example-table" style="background-color: '#e6e6e6';"></div>
<script>
 var tabledata = <? echo  json_encode($this->qlayerset[0]['shape']); ?>

 var table = new Tabulator("#example-table", {
 	// height: 550, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
  height: '75vh',
  data:tabledata, //assign data to table
 	layout:"fitColumns", //fit columns to width of table (optional)
 	columns:[ //Define Table Columns
	 	{title:"Id", field:"id", width:150},
	 	{title:"Bezeichnung", field:"bezeichnung", headerFilter:"input"},
	 	{title:"Sorte", field:"sorte", headerFilter:"input"},
	 	{title:"Anzahl", field:"anzahl"},
 	],
});

//trigger an alert message when the row is clicked
table.on("rowClick", function(e, row) {
	console.log("Row " + row.getData().uuid + " Clicked!!!!");
  document.location = `index.php?go=Layer-Suche_Suchen&selected_layer_id=287&value_uuid=${row.getData().uuid}&operator_uuid==&csrf_token=<? echo $_SESSION['csrf_token']; ?>`;
});
</script>


