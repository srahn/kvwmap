<br>
<h2><? echo $this->titel; ?></h2>
<br>

<style>

  .lzr_type {
    width: 20px;
    text-align: center;
  }

  .lzr_order {
    margin: 4px 3px 3px 3px
  }
  
</style>

<?

$type_symbols = [
  0 => 'circle-o',
  1 => 'chevron-up',
  2 => 'square',
  3 => 'picture-o'
];

if ($this->formvars['auto_order']) {
  $this->add_message('info', 'Die Reihenfolge wurde automatisch generiert und kann nun durch Klick auf "Speichern" übernommen werden.');
}

$layer = array_reverse($this->layerdaten);
echo '
  <div style="display: flex; overflow-y: auto; height: ' . ($this->user->rolle->nImageHeight - 20) . 'px">
    <div>
      <div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
for ($i = 0; $i < count($layer['ID']); $i++) {
  if ($layer['datentyp'][$i] != 5) {
    echo '<div class="dragObject" style="height: 16px;" draggable="true" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
            <i class="fa fa-' . $type_symbols[$layer['datentyp'][$i]] . ' lzr_type" aria-hidden="true"></i>
            <span>' . $layer['Name_or_alias'][$i] . '</span>
            <input name="layers[]" type="hidden" value="'.$layer['ID'][$i].'">
          </div>';
    echo '<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
  }
}
echo '
    </div>
    <div>';
$c = 0;
for ($i = 0; $i < count($layer['ID']); $i++) {
  if ($layer['datentyp'][$i] != 5) {
    $c++;
    echo '<div class="lzr_order"><input name="orders[]" type="text" value="' . ($this->formvars['auto_order'] ? $c : $layer['drawingorder'][$i]) . '"></div>';
  }
}
echo '
    </div>
  </div>
';
?>
<br>
<input type="hidden" name="go" value="Layer_Zeichenreihenfolge">
<input type="hidden" name="auto_order" value="">
<input type="submit" name="go_plus" value="Speichern">
<input type="button" onclick="document.GUI.auto_order.value = 1; document.GUI.submit();" value="automatisch sortieren">
<br><br>