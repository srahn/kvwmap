<?php include('config.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Shape-Datei mit Geltungsbereich des Planes hochladen</title>
		<?php include(SNIPPETS . 'gui_head.php')?>
		<script src="<?php echo JQUERY_PATH; ?>jquery-1.12.0.min.js"></script>
	</head>
	<body style="text-align: center;">
		<h2>Shape-Datei mit Geltungsbereich des Planes hochladen</h2>
		Upload für das Feld: <?php echo $_REQUEST['field_id']; ?><br>
		<form id="file_form" action="index.php" method="post" enctype="multipart/form-data">
			<input type="file" id="file_select" name="shape_file" type="file" style="margin-top: 50px;"/><br>
			<button id="upload_button" type="submit" style="margin-top: 50px;">Hochladen</button><br>
			<div id="upload_message" style="margin-top: 50px;"></div>
		</form>
		<script>
			var form = document.getElementById('file_form');
			var fileSelect = document.getElementById('file_select');
			var uploadButton = document.getElementById('upload_button');

			form.onsubmit = function(event) {
			  event.preventDefault();

			  // Update button text.
			  uploadButton.innerHTML = 'Uploading...';

				var file = fileSelect.files[0];
				var formData = new FormData();
				formData.append('shape_file', file, file.name);
				formData.append('go', 'xplankonverter_upload_geltungsbereich');
				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'index.php', true);
				xhr.onload = function () {
				  if (xhr.status === 200) {
				    // File(s) uploaded.
				    uploadButton.innerHTML = 'Fertig';
						var tableName = JSON.parse(xhr.responseText).result;
						$('#upload_message').html('Die Shapedatei wurde in die Tabelle: ' + tableName + ' importiert!')
						top.document.getElementById('<?php echo $_REQUEST['field_id']; ?>').value = tableName;
						top.closeCustomSubform();
				  } else {
				    alert('Fehler beim hochladen!');
				  }
				};
				// Send the Data.
				xhr.send(formData);
			}
		</script>
	</body>
</html>
