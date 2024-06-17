<br>
<h2><? echo $this->titel; ?></h2>
<br>
<?

$type_symbols = [
  0 => 'circle-o',
  1 => 'chevron-up',
  2 => 'square',
  3 => 'picture-o'
];

$layer = array_reverse($this->layerdaten);
echo '
  <div style="display: flex;">
    <div>
      <div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
for ($i = 0; $i < count($layer['ID']); $i++) {
  if ($layer['Datentyp'][$i] != 5) {
    echo '<div class="dragObject" style="height: 16px;" draggable="true" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
            <i class="fa fa-' . $type_symbols[$layer['Datentyp'][$i]] . '" aria-hidden="true" style="width: 20px;text-align: center"></i>
            <span>' . $layer['Name_or_alias'][$i] . '</span>
            <input name="layers[]" type="hidden" value="'.$layer['Layer_ID'][$i].'">
          </div>';
    echo '<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
  }
}
echo '
    </div>
    <div>';
for ($i = 0; $i < count($layer['ID']); $i++) {
  if ($layer['Datentyp'][$i] != 5) {
    echo '<div style="margin: 3px"><input name="orders[]" type="text" value="'.$layer['drawingorder'][$i].'"></div>';
  }
}
echo '
    </div>
  </div>
';
?>