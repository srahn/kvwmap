<?php
  include(LAYOUTPATH.'languages/data_import_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
<!--

	var filelist = [];  // Ein Array, das alle hochzuladenden Files enthält
	var filesizes = [];  
	var uploadCount = 0;
	var totalSize = 0; // Enthält die Gesamtgröße aller hochzuladenden Dateien
	var totalProgress = 0; // Enthält den aktuellen Gesamtfortschritt
	var currentUpload = null; // Enthält die Datei, die aktuell hochgeladen wird

	function preventDefaults(e){
		e.preventDefault()
		e.stopPropagation()
	}
	
	function handleFileDrop(event){
		preventDefaults(event);
		for(var i = 0; i < event.dataTransfer.files.length; i++){
			filelist.push(event.dataTransfer.files[i]);
			filesizes.push(event.dataTransfer.files[i].size);
    }
		startNextUpload();
	}
	
	function handleDragOver(event){
		if(event.stopPropagation)event.stopPropagation();
	}
	
	function startNextUpload(){
		if(filelist.length > 0){
			currentUpload = filelist.shift();
			uploadFile(currentUpload);
    }
	}
	
	function createProgressDiv(){
		fileProgress = document.createElement("div");
		fileProgress.id = 'progress'+uploadCount;
		document.getElementById('progress').appendChild(fileProgress);
	}
	
	function uploadFile(file){
		createProgressDiv();
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener("progress", function(e){handleProgress(e, uploadCount)});
		xhr.addEventListener("load", handleComplete);
		xhr.addEventListener("error", handleError);
		xhr.open('POST', 'index.php?go=Daten_Import_Upload');
		var formdata = new FormData();
		formdata.append('uploadfile', file);
		xhr.send(formdata);
		uploadCount++;
	}
	
	function handleComplete(event){
    totalProgress += currentUpload.size;
    startNextUpload();
	}
 
	function handleError(event){
		alert("Upload failed");
		totalProgress += currentUpload.size;
		startNextUpload();
	}
 
	function handleProgress(event, id){
		console.log(id);
		document.getElementById('progress'+id).innerHTML = 'Aktueller Fortschritt: ' + Math.round(filesizes[id] / event.loaded * 100) + '%';
	}
	
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" style="width:400px" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" valign="top" style="height: 40px"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
	<tr>
		<td>
			<div id="data_import_upload_zone" ondrop="handleFileDrop(event)" ondragenter="preventDefaults(event);" ondragover="preventDefaults(event);" ondragleave="preventDefaults(event);">
				<div id="text" class="px20 fett"><? echo $strDropFilesHere; ?></div>				
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id="progress" class="px17 fett"></div>
		</td>
	</tr>
  <tr> 
    <td align="center"><? echo $strType; ?>:&nbsp;
			<select name="go" onchange="document.GUI.submit();">
				<option value="">---<? echo $this->strChoose; ?>---</option>
				<option value="SHP_Anzeigen">Shape</option>
				<option value="GeoJSON_Anzeigen">GeoJSON</option>
				<option value="DXF_Import">DXF</option>
				<option value="OVL_Import">OVL</option>
				<option value="GPX_Import">GPX</option>
				<option value="Punktliste_Anzeigen"><? echo $strPointlist; ?></option>
			</select>
		</td>
  </tr>
</table>


