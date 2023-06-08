<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

	function update_inputs(layer, name){
		if (document.getElementById('check_' + layer + '_' +name)?.checked == true){
		}
		else{
			var dragobject = document.getElementById(layer + '_row_' + name);
			var dropzone = document.getElementById(layer + '_row_' + name).nextElementSibling;
			document.getElementById(layer + '_table').appendChild(dragobject);
			document.getElementById(layer + '_table').appendChild(dropzone);
		}
	}
	
	function save(){
		document.GUI.go_plus.value = 'importieren';
		document.GUI.submit();
	}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><h2>Daten-Import</h2></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td valign="top" align="center" colspan="2" style="padding: 10px">
			<div class="fett" style="padding: 5px"><? echo $this->rollenlayer[0]['Name'] ?></div>
  		<div id="rollenlayer_table" style="position: relative">
				<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div><?
				for ($i = 0; $i < count($this->rollenlayer_attributes) - 2; $i++) { ?>
					<div id="rollenlayer_row_<? echo $this->rollenlayer_attributes[$i]['name'] ?>" class="dragObject" draggable="true" onmouseup="update_inputs('rollenlayer', '<? echo $this->rollenlayer_attributes[$i]['name']; ?>');" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
						<input name="rollenlayer_attributes[]" id="rollenlayer_<? echo $this->rollenlayer_attributes[$i]['name']; ?>" style="pointer-events: none; border: none" type="text" value="<? echo $this->rollenlayer_attributes[$i]['name']; ?>" readonly size="25">
					</div>
					<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div><?
				} ?>
  		</div>
  	</td>
		<td valign="top" align="center" style="padding: 13px">
			<div class="fett" style="padding: 5px">&nbsp;</div> <?
			for ($i = 0; $i < count($this->layer_attributes['name']); $i++) {
				if ($this->layer_attributes['saveable'][$i]) {
					echo '<div style="height: 29px; margin: 3 0 0 15; display: flow-root;">==>&nbsp;&nbsp;&nbsp;&nbsp;</div>';
				}
			} ?>
		</td>
  	<td valign="top" align="center" colspan="2" style="padding: 10px">
			<div class="fett" style="padding: 5px"><? echo $this->layer[0]['Name'] ?></div>
  		<div border="0" id="layer_table" style="position: relative">
				<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div><?
				for ($i = 0; $i < count($this->layer_attributes['name']); $i++) {
					if ($this->layer_attributes['saveable'][$i]) { ?>
						<div id="layer_row_<? echo $this->layer_attributes['name'][$i] ?>" class="dragObject" draggable="true" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
							<input name="layer_attributes[]" id="check_layer_<? echo $this->layer_attributes['name'][$i] ?>" type="checkbox" value="<? echo $this->layer_attributes['real_name'][$this->layer_attributes['name'][$i]]; ?>" onclick="update_inputs('layer', '<? echo $this->layer_attributes['name'][$i]; ?>');" checked>
							<input style="pointer-events: none; border: none;" type="text" value="<? echo $this->layer_attributes['alias'][$i] ?: $this->layer_attributes['name'][$i]; ?>" readonly size="25">
						</div>
						<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div><?
					}
				} ?>
  		</div>
  	</td>		
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="5" align="center">
			<input name="retry" value="zurücksetzen" type="button" onclick="document.GUI.submit();">
			<input name="save1" value="importieren" type="button" onclick="save();">
		</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="import_rollenlayer_into_layer">
<input type="hidden" name="rollenlayer_id" value="<? echo $this->formvars['rollenlayer_id']; ?>">
<input type="hidden" name="layer_id" value="<? echo $this->formvars['layer_id']; ?>">
<input type="hidden" name="go_plus" value="">


