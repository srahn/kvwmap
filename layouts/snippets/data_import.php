<?php
  include(LAYOUTPATH.'languages/data_import_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
<!--

	var filelist = [];  // Ein Array, das alle hochzuladenden Files enthält
	var filesizes = [];
	var filenames = [];
	var totalCount = 0;
	var currentUpload = null; // Enthält die Datei, die aktuell hochgeladen wird
	var currentUploadId = 0;

	function preventDefaults(e){
		e.preventDefault()
		e.stopPropagation()
	}
	
	function handleFileDrop(event){
		preventDefaults(event);
		for(var i = 0; i < event.dataTransfer.files.length; i++){
			filelist.push(event.dataTransfer.files[i]);
			filesizes.push(event.dataTransfer.files[i].size);
			filenames.push(event.dataTransfer.files[i].name);			
			createProgressDiv(totalCount);
			totalCount++;
    }
		startNextUpload(currentUploadId);
	}
		
	function startNextUpload(){
		if(filelist.length > 0){
			currentUpload = filelist.shift();
			uploadFile(currentUpload, currentUploadId);
			currentUploadId++;
    }
	}
	
	function createProgressDiv(uploadId){
		fileProgress = document.createElement("div");
		fileProgress.id = 'progress'+uploadId;
		fileProgress.className = 'file_status';
		fileProgress.innerHTML = filenames[uploadId];
		fileProgress.innerHTML = '<div>'+filenames[uploadId]+':</div><div class="uploadPercentage"></div><div class="serverResponse"></div>';
		document.getElementById('data_import_upload_progress').appendChild(fileProgress);
	}
	
	function uploadFile(file, uploadId){		
		var formdata = new FormData();
		formdata.append('uploadfile', file);
		ahah('index.php?go=Daten_Import_Upload&upload_id='+uploadId, 
					formdata, 
					[document.getElementById('progress'+uploadId).querySelector('.serverResponse'), ''], 
					['sethtml', 'execute_function'], 
					function(e){handleProgress(e, uploadId)}
				);
	}
	  
	function handleProgress(event, uploadId){
		var progress;
		if(event.loaded > filesizes[uploadId])progress = 100;
		else progress = Math.round(event.loaded / filesizes[uploadId] * 100);
		document.getElementById('progress'+uploadId).querySelector('.uploadPercentage').innerHTML = progress + '%';
	}
	
	function restartProcessing(uploadId){
		ahah('index.php?go=Daten_Import_Process', 
					'&upload_id='+uploadId+'&filename='+document.getElementById('filename'+uploadId).value+'&epsg='+document.getElementById('epsg'+uploadId).value, 
					[document.getElementById('progress'+uploadId).querySelector('.serverResponse')], 
					['sethtml']
				);
	}
	
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" style="width:400px" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" valign="top" style="height: 40px"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
	<tr>
		<td>
			<div id="data_import_upload_zone" ondrop="handleFileDrop(event)" ondragover="this.className='dragover';preventDefaults(event);" ondragleave="this.className='';preventDefaults(event);" onmouseout="this.className='';">
				<div id="text" class="px20 fett"><? echo $strDropFilesHere; ?></div>				
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id="data_import_upload_progress"></div>
		</td>
	</tr>
</table>


