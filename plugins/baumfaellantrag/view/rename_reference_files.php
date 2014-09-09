<?php
if (array_key_exists('mandateReference', $data) and isset($data['mandateReference']) and !empty($data['mandateReference'])) {
	$mandate_file = UPLOADPATH . "Vollmacht_" . $antrag_id . "." . pathinfo($data['mandateReference'], PATHINFO_EXTENSION);
	if (file_exists($data['mandateReference'])) {
		rename($data['mandateReference'], $mandate_file); // rename mandate file
    $data['mandateReference'] = basename($mandate_file);
  }
	if (file_exists($mandate_file))
		$ref_files[] = $mandate_file;
}

if (array_key_exists('locationSketchReference', $data) and isset($data['locationSketchReference']) and !empty($data['locationSketchReference'])) {
	$sketch_reference_file = UPLOADPATH . "Baumbild_" . $antrag_id . "." . pathinfo($data['locationSketchReference'], PATHINFO_EXTENSION);
	if (file_exists($data['locationSketchReference'])) {
		rename($data['locationSketchReference'], $sketch_reference_file); // rename mandate file
    $data['locationSketchReference'] = basename($sketch_reference_file);
  }
	if (file_exists($sketch_reference_file))
		$ref_files[] = $sketch_reference_file;
}

?>