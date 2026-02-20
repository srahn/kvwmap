<?

	$GUI = $this;

	$this->outputGroup = function($group) use ($GUI){	
		$group_layer_ids = $GUI->layers['layers_of_group'][$group['id']];
		$anzahl_layer = count_or_0($group_layer_ids);
    echo '
      <div id="' . $group['id'] . '" class="llr_group dragObject closed" draggable="true" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
        <div class="groupname">
          <a href="javascript:void(0)" onclick="this.parentNode.parentNode.classList.toggle(\'closed\')">&nbsp;&nbsp;&nbsp;&nbsp;</a>
          ' . $group['gruppenname'] . '
          <a title="bearbeiten" class="llr_edit_link" href="index.php?go=Layergruppe_Editor&selected_group_id=' . $group['id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '"><i class="fa fa-pencil" style="padding: 3px"></i></a>
        </div>
        <div class="group_content">
          <div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';

          if($group['untergruppen'] != ''){
            foreach($group['untergruppen'] as $untergruppe){
              $GUI->outputGroup($GUI->groups[$untergruppe]);
            }
          }
          for($i = 0; $i < $anzahl_layer; $i++){
            $GUI->outputLayer($group_layer_ids[$i]);
          }
    echo '
          <div id="dummy">&nbsp;</div>
        </div>
      </div>
      <div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
	};
	
	$this->outputLayer = function($i) use ($GUI) {
    echo '
		  <div id="' . $GUI->layers['ID'][$i] . '" class="llr_layer dragObject closed" draggable="true" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
			  ' . $GUI->layers['Bezeichnung'][$i] . '
			</div>
      <div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>
			';
	};

?>

<script language="JavaScript">

  let group_order = 0;
  const form = new FormData(document.GUI);

  function save(){
    const groups = document.getElementById('llr_main').querySelectorAll(':scope > .llr_group');
	  [].forEach.call(groups, function (group){
      traverse_group(group, null);
    });
    form.append('go', 'Layer_Legendenreihenfolge_Speichern');

    fetch('index.php', {
      method: "POST",
      body: form,
    })
    .then((response)=>{
      document.GUI.submit();
    })
    .catch((error)=>{
      console.error(error);
    })
  }

  function traverse_group(group, uppergroup){
    let layer_order = 1;
    group_order += 1;
    form.append('group_ids[]', group.id);
    form.append('group_orders[]', group_order);
    form.append('group_uppergroups[]', uppergroup?.id ?? '');
    const subgroups = group.querySelectorAll(':scope > .group_content > .llr_group');
	  [].forEach.call(subgroups, function (subgroup){
      traverse_group(subgroup, group);
    });
    const layers = group.querySelectorAll(':scope > .group_content > .llr_layer');
	  [].forEach.call(layers, function (layer){
      form.append('layer_ids[]', layer.id);
      form.append('layer_orders[]', layer_order);
      form.append('layer_groups[]', group.id);
      layer_order += 1;
    });
  }

</script>

<style>

  #llr_main {
    margin: 5px;
  }

  .llr_group {
    position: relative;
    border: 1px solid #949ca8;
    margin: 7px 2px 5px 7px;
    text-align: left;
    height: auto;
    padding: 0 0 2px 0;
    display: grid;
    grid-template-rows: min-content 1fr;
    transition: 0.2s ease;
  }

  .llr_group>.group_content{
    overflow: hidden;
    min-height: 5px;
  }

  .llr_group>.groupname {
    background-color: #DAE4EC;
    border-bottom: 1px solid #ccc;
    padding: 5px 0 1px 5px;
    height: 20px;
  }

  .groupname:hover {
    background-color: #c7d9e6;
  }

  .llr_layer {
    padding: 1 0 1 5px;
  }

  .llr_group>.groupname::before {
    content: "\2212";
    margin-right: -10px;
  }

  .llr_group.closed>.groupname::before {
    content: "\002B";
  }

  .llr_group.closed{    
    grid-template-rows: min-content 0fr;
    padding: 0;
  }

  .llr_group.closed>.group_content{
    min-height: 0;
  }

  .llr_edit_link {
    position: absolute;
    right: 10px;
    top: 3px;
  }

  .dropZone.ready {
    margin: -21 0 -22 15;
    height: 44px;
  }

  .dropZone.over{
    height: 71px;
    margin: -22 0 -23 15;
    transition: height 0.1s ease, margin 0.1s ease;
  }  

</style>

<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<br>
<h2><? echo $this->titel; ?></h2>
<br>
<div id="llr_main">
<?
  echo '<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
  foreach($this->groups as $group){
    if($group['obergruppe'] == ''){
      $this->outputGroup($group);
    }
  }
?> 
</div>
<div>
  <input type="button" name="dummy" value="<?php echo $this->strSave; ?>" onclick="save();">
</div>
<br>
<input type="hidden" name="go" value="Layer_Legendenreihenfolge">
