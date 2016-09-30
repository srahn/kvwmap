<?php
  include('header.php');
?>
<div class="page-header">
  <h2>Validierungsergebnisse</h2>
</div>
<div class="alert alert-danger" style="white-space: pre-wrap" id="eventsResult">
    Die Validierung wurde mit Fehlern abgeschlossen.
</div>
Und hier kommt die Liste der Validierungsergebnisse. Die ist aber leider noch nicht implementiert.
<table class="_table _table-condensed _table-bordered" data-toggle="table">
  <thead>
    <tr>
      <th>Regel</th>
      <th>Ergebnis</th>
    </tr>
  </thead>
  <tbody>
    <tr class="success">
      <td>RP_Objekt</td>
      <td>Alles gut!</td>
    </tr>
    <tr class="warning">
      <td>RP_Objekt</td>
      <td>Warnung.</td>
    </tr>
    <tr class="danger">
      <td>RP_Objekt</td>
      <td>Schlimmer Fehler!</td>
    </tr>
    <tr class="info">
      <td>RP_Objekt</td>
      <td>Das ist nur zur Information.</td>
    </tr>
  </tbody>
</table>