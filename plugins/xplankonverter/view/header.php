<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="../../../../devk/styles/bootstrap-table.css" type="text/css">
<link rel="stylesheet" href="../../../../devk/styles/design.css" type="text/css">
<link rel="stylesheet" href="plugins/xplankonverter/styles/styles.css" type="text/css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="../../../../devk/javascript/tableExport.js"></script>
<script src="../../../../devk/javascript/jquery.base64.js"></script>
<script src="../../../../devk/javascript/bootstrap-table.js"></script>
<script src="../../../../devk/javascript/bootstrap-table-export.js"></script>
<script src="../../../../devk/javascript/bootstrap-table-flatJSON.js"></script>
<div class="xplankonverter">
<ul class='nav nav-tabs'>
  <li class='<?php echo ($config['active']==='step1' ? 'active' : '');?>'><a data-toggle='tab'>Schritt 1</a></li>
  <li class='<?php echo ($config['step2']['disabled'] ? 'disabled' : ($config['active']==='step2' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 2</a></li>
  <li class='<?php echo ($config['step3']['disabled'] ? 'disabled' : ($config['active']==='step3' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 3</a></li>
  <li class='<?php echo ($config['step4']['disabled'] ? 'disabled' : ($config['active']==='step4' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 4</a></li>
  <li><a data-toggle='tab' href='#map'>Karte</a></li>
</ul>
-