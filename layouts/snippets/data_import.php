<?php
  include(LAYOUTPATH.'languages/data_import_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
<!--

	var Formdata = new FormData();
	Formdata.append('go', 'Daten_Import_Upload');
	Formdata.append('after_import_action', '<? echo $this->after_import_action; ?>');
	
	function restartProcessing(uploadId, filenumber, filename){
		ahah('index.php?go=Daten_Import_Process', 
					'&upload_id='+uploadId+'&filenumber='+filenumber+'&filename='+filename+'&epsg='+document.getElementById('epsg'+filename).value+'&after_import_action=<? echo $this->after_import_action; ?>', 
					[document.getElementById('progress'+uploadId).querySelector('.serverResponse #serverResponse'+filenumber)], 
					['sethtml']
				);
	}
	
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" valign="top" style="height: 40px"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
	<tr> 
    <td align="center" valign="top"><? echo $strSupportedFileTypes; ?>Shape (*.dbf, *.shp, *.shx), GeoJSON, KML, KMZ, DXF, GPX, UKO, OVL, GeoTiff</td>
  </tr>
	<tr>
		<td>
			<? include(SNIPPETS . 'multi_file_upload.php'); ?>
		</td>
	</tr>
</table>


