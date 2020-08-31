<?php
	$url_parts = explode('/', JQUERY_PATH);
	$dir_parts = explode('-' , $url_parts[count($url_parts) - 2]);
?>
<link rel="stylesheet" href="<?php echo BOOTSTRAP_PATH; ?>css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo BOOTSTRAPTABLE_PATH; ?>bootstrap-table.min.css" type="text/css">
<link rel="stylesheet" href="plugins/xplankonverter/styles/design.css" type="text/css">
<link rel="stylesheet" href="plugins/xplankonverter/styles/styles.css" type="text/css">

<script src="<?php echo JQUERY_PATH; ?>jquery-<?php echo $dir_parts[1]; ?>.min.js"></script>
<script src="<?php echo JQUERY_PATH; ?>jquery.base64.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>js/bootstrap.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>js/bootstrap-table-flatJSON.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>bootstrap-table.min.js"></script>

<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>libs/FileSaver/FileSaver.min.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>libs/js-xlsx/xlsx.core.min.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>tableExport.min.js"></script>

<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>locale/bootstrap-table-de-DE.min.js"></script>

