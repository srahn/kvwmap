<?php
  function outputHTML($content){
    $html = "<!DOCTYPE html><html>".$content."</html>";
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadHTML($html);
    $dom->saveHTMLFile('test.html');
    return strtr($dom->saveHTML(), '"', "'");
  };
  
  function outputHeader() {
    $debug_js = true;
    $local_js = true;
    $header = "<head>";
    if ($local_js) {
      if ($debug_js) {
        $header .= "<link rel='stylesheet' href='/kvwmap/plugins/xplankonverter/3rdparty/bootstrap-3.3.6/css/bootstrap.css'/>
              <script src='/kvwmap/plugins/xplankonverter/3rdparty/jQuery-1.12.0/jquery-1.12.0.js'></script>
              <script src='/kvwmap/plugins/xplankonverter/3rdparty/bootstrap-3.3.6/js/bootstrap.js'></script>";
      } else {
        $header .= "<link rel='stylesheet' href='/kvwmap/plugins/xplankonverter/3rdparty/bootstrap-3.3.6/css/bootstrap.min.css'>
              <script src='/kvwmap/plugins/xplankonverter/3rdparty/jQuery-1.12.0/jquery-1.12.0.min.js'></script>
              <script src='/kvwmap/plugins/xplankonverter/3rdparty/bootstrap-3.3.6/js/bootstrap.min.js'></script>";
      }
    } else {
      $header .= "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
            <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js' 
              integrity='sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS' crossorigin='anonymous'>
            </script>";
    }
    $header .= "</head>";
    return $header;
  };
  
  function outputBody($content){
    $body = "<body class='container-fluid' style='background-color:transparent;'>";
    $body .= $content;
    $body .= "</body>";
    return $body;
  }
  
  function outputTabPanel($config) {
    $tabPanel = "
      <ul class='nav nav-tabs'>
        <li class='" . ($config['active']==='step1' ? 'active' : '') . "'><a data-toggle='tab'>Schritt 1</a></li>
        <li class='" . ($config['active']==='step2' ? 'active' : '') . " " . ($config['step2']['disabled'] ? 'disabled' : '') . "'><a data-toggle='tab'>Schritt 2</a></li>
        <li class='" . ($config['active']==='step3' ? 'active' : '') . " " . ($config['step3']['disabled'] ? 'disabled' : '') . "'><a data-toggle='tab'>Schritt 3</a></li>
        <li class='" . ($config['active']==='step4' ? 'active' : '') . " " . ($config['step4']['disabled'] ? 'disabled' : '') . "'><a data-toggle='tab'>Schritt 4</a></li>
        <li><a data-toggle='tab' href='#map'>Karte</a></li>
      </ul>";
    return $tabPanel;
  }
  
  function showStep1() {
    $tabConfig = array(
        'active' => 'step1',
        'step1' => array(
            'disabled' => false
        ),
        'step2' => array(
            'disabled' => true
        ),
        'step3' => array(
            'disabled' => true
        ),
        'step4' => array(
            'disabled' => true
        ),
    );
    $tabContent = "
      <div class='tab-content'>
        <div id='step_1' class='tab-pane fade in active'><div class='panel panel-default'><div class='_panel-heading panel-body'>
          <h4>Auswahl der Konvertierung</h4>
          <p>Ihre Konvertierungen</p>
          <div class='panel panel-default'>
            <!-- Table -->
            <table class='table table-bordered table-hover'>
              <thead>
                <tr>
                  <th>Plan</th>
                  <th>Regel-Template</th>
                  <th>Status</th>
                  <th>Funktionen</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>John's Plan</td>
                  <td>Regelsatz A</td>
                  <td class='success'>Konvertierung ausgef&uuml;hrt</td>
                  <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
                </tr>
                <tr>
                  <td>Mary's Plan</td>
                  <td>Regelsatz A</td>
                  <td class='warning'>Konvertierung offen</td>
                  <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
                </tr>
                <tr>
                  <td>July's Plan</td>
                  <td>Regelsatz B</td>
                  <td class='info'>in Bearbeitung</td>
                  <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
                </tr>
              </tbody>
            </table>
          </div>
          <button id='xpc-new-conversion' type='button' class='btn btn-primary' onclick='step1_new_conversion'>Neue Konvertierung anlegen</button>
        </div></div></div>
      </div>";
    return outputHTML(outputHeader() . outputBody(outputTabPanel($tabConfig) . $tabContent));
  };
  
  function tabScripts() {
    ?>
    <script type='text/javascript'>
      function step1_new_conversion(event) {
        alert('Step2');
      }
    </script>
    <?php
   }
?>