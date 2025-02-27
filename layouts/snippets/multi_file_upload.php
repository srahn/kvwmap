<?php
  include(LAYOUTPATH.'languages/data_import_'.$this->user->rolle->language.'.php');
?>
<script type="text/javascript">
<!--

	var filelist = [];  // Ein Array, das alle hochzuladenden Files enthält
	var filesizes = [];
	var filenames = [];
	var totalCount = 0;
	var totalSize = 0;
	var maxTotalSize = <? echo MAXUPLOADSIZE; ?>;
	var maxFileSize = <? echo get_max_file_size() ?>;
	var currentUpload = null; // Enthält die Datei, die aktuell hochgeladen wird
	var currentUploadId = 0;

	function preventDefaults(e){
		e.preventDefault()
		e.stopPropagation()
	}

	function handleFileDrop(event) {
		preventDefaults(event);
		var files = [].slice.call(event.dataTransfer?.files || event.target.files);	// ein richtiges Array aus dem FileList-Objekt machen
		files.sort(function(a, b) {
			if (a.name < b.name) { return -1; }
			if (a.name > b.name) { return 1; }
			return 0;
		});
		console.log('handleFileDrop: ', files);
		for (var i = 0; i < files.length; i++) {
			if (totalSize + files[i].size > maxTotalSize * 1024 * 1024) { // Byte -> MegaByte
				message('<? echo $strMaxUploadSize; ?> von ' + maxTotalSize + ' MB <? echo $this->strExceeded; ?>');
				return;
			}
			if (files[i].size > maxFileSize * 1024 * 1024) { // Byte -> MegaByte
				message('<? echo $strMaxFileSize; ?> von ' + maxFileSize + ' MB <? echo $this->strExceeded; ?>');
				return;
			}
			filelist.push(files[i]);
			filesizes.push(files[i].size);
			filenames.push(files[i].name);
			createProgressDiv(totalCount);
			totalSize += files[i].size;
			totalCount++;
    }
		startNextUpload(currentUploadId);
	}

	function startNextUpload() {
		console.log('startNextUpload: ', filelist);
		if (filelist.length > 0) {
			currentUpload = filelist.shift();
			uploadFile(currentUpload, currentUploadId);
			currentUploadId++;
		}
	}

	function createProgressDiv(uploadId) {
		fileProgress = document.createElement("div");
		fileProgress.id = 'progress'+uploadId;
		fileProgress.className = 'file_status';
		fileProgress.innerHTML = filenames[uploadId];
		fileProgress.innerHTML = '<div>&nbsp;'+filenames[uploadId]+':&nbsp;</div><div class="uploadPercentage"></div><div class="serverResponse"></div>';
		document.getElementById('data_import_upload_progress').appendChild(fileProgress);
	}

	function uploadFile(file, uploadId) {
		console.log('uploadFile: ', file);
		var formdata = Formdata;
		formdata.append('uploadfile', file);
		formdata.append('upload_id', uploadId);
		formdata.append('lastmodified', file.lastModified);
		console.log('formdata: ', formdata);
		ahah('index.php',
			formdata,
			[document.getElementById('progress' + uploadId).querySelector('.serverResponse'), ''], 
			['sethtml', 'execute_function'],
			function(e) { handleProgress(e, uploadId) }
		);
	}

	function handleProgress(event, uploadId) {
		var progress;
		if (event.loaded > filesizes[uploadId]) {
			progress = 100;
		}
		else {
			progress = Math.round(event.loaded / filesizes[uploadId] * 100);
		}
		document.getElementById('progress' + uploadId).querySelector('.uploadPercentage').innerHTML = progress + '%';
	}
//-->
</script>

<div id="data_import_upload_zone" ondrop="handleFileDrop(event)" ondragover="this.className='dragover';preventDefaults(event);" ondragleave="this.className='';preventDefaults(event);" onmouseout="this.className='';">
	<div id="text" class="px20 fett"><? echo $strDropFilesHere; ?></div>				
	<div style="text-align: center;  margin: 10px;  bottom: 5px;  position: absolute;  width: 100%;">
		<input type="file" multiple oninput="handleFileDrop(event)">
	</div>
</div>
<div id="data_import_upload_progress"></div>
		


