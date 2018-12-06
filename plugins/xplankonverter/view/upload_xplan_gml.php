<h2 style="margin-top: 20px;"><?php echo$this->formvars['planart']; ?> GML hochladen</h2>
<form id="file_form" action="" method="post" enctype="multipart/form-data">
	<input type="file" id="file_select" name="gml_file" style="margin-top: 50px;"/><br>
	<button id="upload_button" style="margin-top: 50px;">Hochladen</button><br>
	<div id="upload_message" style="margin-top: 50px; margin-bottom: 20px;"></div>
</form>

<form action="index.php?go=xplankonverter_extract_gml_to_form&planart=<?php echo $this->formvars['planart']?>" method="post">
	<button id="extract_to_form" style="display:none" name="gml_file" value="">Daten in Formular laden</button>
</form>
<script>
	var form = $('#file_form');
	var fileSelect = $('#file_select');
	var uploadButton = $('#upload_button');
	var uploadMessage = $('#upload_message');
	var extractToForm = $('#extract_to_form');

	uploadButton.on(
		'click',
		function(event) {
			event.preventDefault();

			// checks if exists
			if (fileSelect.val() == '' ){
				message([{ type: 'error', msg: 'Keine Datei ausgewählt!'}]);
				return;
			}
			// Update button text.
			uploadButton.html('Uploading...');

			var file = fileSelect[0].files[0];
			var formData = new FormData();
			formData.append('gml_file', file, file.name);
			formData.append('go', 'xplankonverter_upload_xplan_gml');
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'index.php', true);
			xhr.onload = function () {
				if (xhr.status === 200) {
					// File(s) uploaded.
					uploadButton.hide();
					fileSelect.hide();
					uploadMessage.html('Die GML-Datei ' + file.name + ' wurde erfolgreich hochgeladen');
				} else {
					message([{ type: 'notice', msg: 'Fehler beim hochladen!'}]);
				}
			};
			// Send the Data.
			xhr.send(formData);

			extractToForm.val(file.name).show();
		}
	);
</script>