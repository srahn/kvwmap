<!DOCTYPE html>
<html>
<head>
<?php
  $debug_js = true;
  $local_js = true;
  if ($local_js) {
    if ($debug_js) {
      echo "
  <link rel='stylesheet' href='/kvwmap/plugins/xplankonverter/3rdparty/bootstrap-3.3.6/css/bootstrap.css'></link>
  <script src='/kvwmap/plugins/xplankonverter/3rdparty/jQuery-1.12.0/jquery-1.12.0.js'></script>
  <script src='/kvwmap/plugins/xplankonverter/3rdparty/bootstrap-3.3.6/js/bootstrap.js'></script>";
    } else {
      echo "
  <link rel='stylesheet' href='/kvwmap/plugins/xplankonverter/3rdparty/bootstrap-3.3.6/css/bootstrap.min.css'></link>
  <script src='/kvwmap/plugins/xplankonverter/3rdparty/jQuery-1.12.0/jquery-1.12.0.min.js'></script>
  <script src='/kvwmap/plugins/xplankonverter/3rdparty/bootstrap-3.3.6/js/bootstrap.min.js'></script>";
    }
  } else {
    echo "
  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'></link>
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
  <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js' 
    integrity='sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS' crossorigin='anonymous'>
  </script>";
  }
?>
  </head>
<body style='background-color:transparent;'>
<ul class='nav nav-tabs'>
  <li class='active'><a data-toggle='tab' href='#step_1'>Schritt 1</a></li>
  <li class='disabled'><a data-toggle='tab' href='#step_2'>Schritt 2</a></li>
  <li class='disabled'><a data-toggle='tab' href='#step_3'>Schritt 3</a></li>
  <li class='disabled'><a data-toggle='tab' href='#step_4'>Schritt 4</a></li>
  <li><a data-toggle='tab' href='#map'>Karte</a></li>
</ul>

<div class='tab-content'>
  <div id='step_1' class='tab-pane fade in active container-fluid'>
    <h4>Auswahl der Konvertierung</h4>
    <div class='panel panel-info'>
      <div class='panel-heading'>Ihre Konvertierungen</div>
      <!-- Table -->
      <table class='table table-bordered table-hover'>
        <thead>
          <tr>
            <th>Regel-Template</th>
            <th>Status</th>
            <th>Funktionen</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>John</td>
            <td class='success'>Konvertierung ausgef&uuml;hrt</td>
            <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
          </tr>
          <tr>
            <td>Mary</td>
            <td class='warning'>Konvertierung offen</td>
            <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
                      </tr>
          <tr>
            <td>July</td>
            <td class='info'>in Bearbeitung</td>
            <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
          </tr>
        </tbody>
      </table>
    </div>
    <button type='button' class='btn btn-primary'>Neue Konvertierung anlegen</button>
  </div>
  <div id='step_2' class='tab-pane fade'>
    <h3>Schritt 2</h3>
    <p>Some content in menu 1.</p>
  </div>
  <div id='step_3' class='tab-pane fade'>
    <h3>Schritt 3</h3>
    <p>Some content in menu 2.</p>
  </div>
  <div id='step_4' class='tab-pane fade'>
    <h3>Schritt 4</h3>
    <p>Some content in menu 2.</p>
  </div>
  <div id='map' class='tab-pane fade'>
    <h3>Karte</h3>
    <p>Some content in menu 2.</p>
  </div>
</div>
</body>
</html>