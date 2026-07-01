<style>
    .desc { color: #444; margin-bottom: 20px; }
    table.desc {
      font-family: Arial, sans-serif;
      border-collapse: collapse;
      margin-top: 4px;
      width:100%;
    }
    table.desc th, table.desc td {
      border:1px solid #aaa;
      padding:6px;
    }
    table.desc th { background: #f4f4f4; text-align: left; }
    .required { color: red; font-weight: bold; }
    .type { font-family: monospace; }
</style>
<?php
  function render_schema_doc(string $schemaName): string {
    $file = 'schemas/attribute_options/' . $schemaName . ".json";

    if (!file_exists($file)) {
      return "<p style='color:red'>Schema not found: " . htmlspecialchars($schemaName) . "</p>";
    }    $schema = json_decode(file_get_contents($file), true);

    if (!$schema) {
      return "<p style='color:red'>Invalid JSON in schema: " . htmlspecialchars($schemaName) . " - " . json_last_error() . "</p>";
    }

    $title = $schema['title'] ?? $schemaName;
    $description = $schema['description'] ?? '';
    $properties = $schema['properties'] ?? [];
    $required = $schema['required'] ?? [];

    $html = ""; ?>

    <h2 style=""><? echo htmlspecialchars($title); ?></h2>
    <? echo htmlspecialchars($description); ?>
    <table class="desc">
    <tr>
      <th>Option</th>
      <th>Type</th>
      <th>Beschreibung</th>
      <th>Pflicht</th>
    </tr><?

    foreach ($properties as $name => $field) {

        $type = $field['type'] ?? 'unknown';
        $format = $field['format'] ?? null;

        $typeLabel = $format ? $type . " (" . $format . ")" : $type;
        $desc = $field['description'] ?? '';

        $isRequired = in_array($name, $required);

        $html .= "<tr>";

        $html .= "<td>" . htmlspecialchars($name) . "</td>";
        $html .= "<td style=';font-family:monospace'>" . htmlspecialchars($typeLabel) . "</td>";
        $html .= "<td>" . htmlspecialchars($desc) . "</td>";

        $html .= "<td>"
              . ($isRequired ? "<span style='color:red;font-weight:bold'>ja</span>" : "nein")
              . "</td>";

        $html .= "</tr>";
    }

    $html .= "</table>";

    return $html;
  }

  $schemaDir = WWWROOT . APPLVERSION . 'schemas/attribute_options';
  $files = glob($schemaDir . '/*.json');
  foreach ($files as $file) {
    echo render_schema_doc(basename($file, '.json'));
  }
?>