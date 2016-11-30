<?php
  // the view's configuration
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

  function outputHTML($content = ''){
    ?>
    <!DOCTYPE html><html>
      <?php if (is_callable($content)) $content(); else echo $content;?>
    </html>
    <?php
  };
  
  function outputHeader() {
		include('header.php');
  };
  
  function outputBody($content = ''){
    ?>
    <body class='container-fluid' style='background-color:transparent;'>
      <?php if (is_callable($content)) $content(); else echo $content;?>
    </body>
    <?php
  }
  
  function outputTabs($data) {
    $config = $data['config']
    ?>
    <ul class='nav nav-tabs'>
      <li class='<?php echo ($config['active']==='step1' ? 'active' : '');?>'><a data-toggle='tab'>Schritt 1</a></li>
      <li class='<?php echo ($config['step2']['disabled'] ? 'disabled' : ($config['active']==='step2' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 2</a></li>
      <li class='<?php echo ($config['step3']['disabled'] ? 'disabled' : ($config['active']==='step3' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 3</a></li>
      <li class='<?php echo ($config['step4']['disabled'] ? 'disabled' : ($config['active']==='step4' ? 'active' : ''));?>'><a data-toggle='tab'>Schritt 4</a></li>
      <li><a data-toggle='tab' href='#map'>Karte</a></li>
    </ul>
    <?php
  }
  
  function outputTabPanel($data) {
    $config = $data['config'];
    $convers = $data['step1']['konvertierungen'];
    ?>
    <div class='tab-content'>
      <div id='step_1' class='tab-pane fade in <?php echo ($config['active']==='step1' ? 'active' : '');?>'><div class='panel panel-default'><div class='_panel-heading panel-body'>
        <h4>Auswahl der Konvertierung</h4>
        <p>Ihre Konvertierungen</p>
        <div class='panel panel-default' style='height:45ex;overflow-y:scroll;'>
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
            <?php {
              $highlighting = array('info','warning', 'success');
              foreach ($convers as $conv) {
              ?>
              <tr>
                <td><?php echo $conv['name'];?></td>
                <td><?php echo $conv['ruleset'];?></td>
                <td class='<?php echo $highlighting[$conv['status']].'\'>'.Converter::$STATUS[$conv['status']];?></td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
              <?php
              }}
            ?>
           </tbody>
          </table>
        </div>
        <button id='xpc-new-conversion' type='button' class='btn btn-primary' onclick='step1_new_conversion'>Neue Konvertierung anlegen</button>
      </div></div></div>
      <div id='step_2' class='tab-pane fade in <?php echo ($config['active']==='step2' ? 'active' : '');?>'><div class='panel panel-default'><div class='_panel-heading panel-body'>
        <h4>Auswahl eines Konvertierungs-Regelsatzes</h4>
        <p>Ihre Konvertierungsregels&auml;tze</p>
        <div class='panel panel-default' style='height:45ex;overflow-y:scroll;'>
          <!-- Table -->
          <table class='table table-bordered table-hover'>
            <thead>
              <tr>
                <th>Regelsatzname</th>
                <th>Letzte &Auml;nderung</th>
                <th>Bearbeiter</th>
                <th>Funktionen</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Regelsatz A</td>
                <td>01.03.2015</td>
                <td>Korduan</td>
                <td><a>Anzeigen</a> <a>Ausw&auml;hlen</a> <a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
              <tr>
                <td>Regelsatz B</td>
                <td>11.08.2015</td>
                <td>Kraetschmer</td>
                <td><a>Anzeigen</a> <a>Ausw&auml;hlen</a> <a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
              <tr>
                <td>Regelsatz C</td>
                <td>27.09.2015</td>
                <td>Korduan</td>
                <td><a>Anzeigen</a> <a>Ausw&auml;hlen</a> <a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
            </tbody>
          </table>
        </div>
        <button id='xpc-new-conversion' type='button' class='btn btn-primary' onclick='step1_new_conversion'>Neuen Regelsatz anlegen</button>
      </div></div></div>
      <div id='step_3' class='tab-pane fade in <?php echo ($config['active']==='step3' ? 'active' : '');?>'><div class='panel panel-default'><div class='_panel-heading panel-body'>
        <h4>Shape-Files hochladen / bearbeiten</h4>
        <p>Hochgeladene Dateien</p>
        <div class='panel panel-default' style='height:45ex;overflow-y:scroll;'>
          <!-- Table -->
          <table class='table table-bordered table-hover'>
            <thead>
              <tr>
                <th>Dateiname</th>
                <th>Letzte &Auml;nderung</th>
                <th>Funktionen</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Schienenverkehr.shp</td>
                <td>01.03.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a> <a>Karte</a></td>
              </tr>
              <tr>
                <td>Schienenverkehr.shx</td>
                <td>01.03.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
              <tr>
                <td>Schienenverkehr.dbf</td>
                <td>01.03.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
              <tr>
                <td>Naturschutz.shp</td>
                <td>11.08.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a> <a>Karte</a></td>
              </tr>
              <tr>
                <td>Naturschutz.shx</td>
                <td>11.08.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
              <tr>
                <td>Naturschutz.dbf</td>
                <td>11.08.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
              <tr>
                <td>Windeignung.shp</td>
                <td>27.09.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a> <a>Karte</a></td>
              </tr>
              <tr>
                <td>Windeignung.shx</td>
                <td>27.09.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
              <tr>
                <td>Windeignung.dbf</td>
                <td>27.09.2015</td>
                <td><a>Bearbeiten</a> <a>L&ouml;schen</a></td>
              </tr>
            </tbody>
          </table>
        </div>
        <button id='xpc-new-conversion' type='button' class='btn btn-primary' onclick='step1_new_conversion'>Neuen Regelsatz anlegen</button>
        </div></div></div>
      <div id='step_4' class='tab-pane fade in <?php echo ($config['active']==='step4' ? 'active' : '');?>'><div class='panel panel-default'><div class='_panel-heading panel-body'>
        <h4>Konsistenzpr�fung</h4>
      </div></div></div>
    </div>
  <?php
  }
  
  function show($data) {
    outputHTML(function() use ($data) {
      outputHeader();
      outputBody(function() use ($data) {
        outputTabs($data);
        outputTabPanel($data);
      });
    });
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