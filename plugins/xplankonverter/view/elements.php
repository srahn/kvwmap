<script src="<?php echo JQUERY_PATH; ?>jquery-1.12.0.min.js"></script>
<script src="<?php echo JQUERY_PATH; ?>jquery.base64.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>js/bootstrap.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH; ?>bootstrap-table-flatJSON.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>tableExport.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>bootstrap-table.js"></script>
<script src="<?php echo BOOTSTRAPTABLE_PATH; ?>bootstrap-table-export.js"></script>

<script language="javascript" type="text/javascript">
  /*
  * Konvertiert den Zeitstempel aus einem Javascript Date Objekt in ein Objekt mit formatierten Elementen für Jahr, Monat, Tag etc.
  */
  function explodeJsDate(d) {
    m = d.getMinutes();
    o = {
      day: d.getDate(),
      month: d.getMonth() + 1,
      year: d.getFullYear(),
      hour: d.getHours(),
      minute: (m < 10) ? 0 + m : m,
      second: d.getSeconds()
    }
    return o
  };
  /*
  * Konvertiert den Zeitstempel aus der Postgres Datenbank als Text in das Textformat DD.MM.YYYY HH:mm
  */
  function pgToJsTime(pgTime) {
    p = new Date(pgTime);
    d = explodeJsDate(p);
    result =  d.day + '.' + d.month + '.' + d.year + ' ' + d.hour + ':' + d.minute;
    return result;
  }
  /*
  * Konvertiert ein JS Date Objekt in ein Textformat YYYY-MM-DD HH:mm:SS wie es in Postgres timestamps benötigt wird
  */
  function jsToPgTime(jsTime) {
    d = explodeJsDate(jsTime);
    return d.year + '-' + d.month + '-' + d.day + ' ' + d.hour + ':' + d.minute + ':' + d.second;
  }

  function toggleVisibility(div_id) {
    if ($.isArray(div_id)) {
      for ( var i = 0, l = div_id.length; i < l; i++ ) {
        toggleVisibility(div_id[i]);
      }
    }
    else {
      $('#' + div_id + '_minimize_img').toggle(500);
      $('#' + div_id + '_maximize_img').toggle(500);
      $('#' + div_id).toggle(500);
    }
  }
  
  $(window).resize(function() {
    $('table').bootstrapTable('resetView' , {
      height: $(window).height() - 280
    });
  });
</script>

<?php
  if ($this->formvars['package'] == '') $this->formvars['package'] = 'RPlan';
?>
<div id="main">
  <div class="textsite">
    <?php
      echo '<h1><center>XPlan Elemente und Attribute</center><hr>';
      echo '</h1>';
    ?>
    <form action="index.php">
      <input type="hidden" name="go" value="show_elements">
      Package: 
      <select id="element_package_selektor" name="package" size="1" onchange="document.location.href='index.php?go=show_elements&package=' + $(this).val()">
        <?php for ($i=0; $i < count($this->packages); $i++) {
          ?><option value="<?php echo $this->packages[$i]['package']; ?>"<?php if ($this->packages[$i]['package'] == $this->formvars['package']) { ?> selected<?php } ?>><?php echo $this->packages[$i]['package']; ?></option>
        <?php } ?>
      </select>
      <?php
      $sql = "
        SELECT
          e.id element_id,
          e.name element_name,
          e.documentation,
          e.type element_type,
          e.\"substitutionGroup\" \"element_substitutionGroup\",
          a.element_id attribut_element_id,
          a.name attribut_name,
          a.\"minOccurs\",
          CASE WHEN a.\"minOccurs\" = '0' THEN '0' ELSE '1' END \"attribut_minOccurs\",
          a.\"maxOccurs\",
          CASE WHEN a.\"maxOccurs\" = 'unbounded' THEN '*' ELSE '1' END \"attribut_maxOccurs\",
          a.type attribut_type,
          a.ref attribut_ref,
          e.package,
          e.altoderneuelements,
          a.altoderneuattributes,
          a.documentationattribute
        FROM
          xplan.elements e LEFT JOIN
          xplan.attributes a ON e.id = a.element_id
        WHERE
          e.name NOT LIKE '_GenericApplicationPropertyOf%'
        ";
      if ($this->formvars['package'] != '' AND $this->formvars['package'] != 'Alle') $sql .= " AND package LIKE '" . $this->formvars['package'] ."'";
      $sql .= "
        ORDER BY ";
      if ($this->formvars['order'] != '') {
        $sql .= $this->formvars['order'];
      } else {
        $sql .= "
          e.package,
          e.name,
          a.name
        ";
      }

      #echo '<br>' . $sql;
      $result = pg_query($this->pgdatabase->dbConn, $sql);
      $packages = array();
      $element_id = 0;
      $package = array();
      $element = array();
      $attribute = array();
      while ($row = pg_fetch_assoc($result)) {
        if ($package['name'] != $row['package']) {
          # new package
          # close the old package if exists
          if (! empty($package)) {
            $packages[] = $package;
          }
          # start with new package
          $package = array('name' => $row['package'], 'elements' => array());
        }
        if ($element['id'] != $row['element_id']) {
          # new element
          # close the old element if exists
          if (! empty($element)) {
            $package['elements'][] = $element;
          }
          # start with new element
          $element = array('id' => $row['element_id'], 'element_name' => $row['element_name'], 'altoderneuelements' => $row['altoderneuelements'], 'documentation' => $row['documentation'], 'attributes' => array(), 'associations' => array());
        }
        if ($row['attribut_name'] != '' AND $attribute['name'] != $row['attribut_name']) {
          # new attribute

          $attribute = array('attribut_type' => $row['attribut_type'], 'attribut_name' => $row['attribut_name'], 'altoderneuattributes' => $row['altoderneuattributes'], 'attribut_minOccurs' => $row['attribut_minOccurs'], 'attribut_maxOccurs' => $row['attribut_maxOccurs'], 'attribut_ref' => $row['attribut_ref'], 'documentationattribute' => $row['documentationattribute']);
          if ($attribute['attribut_type'] == 'gml:ReferenceType') {
            $element['associations'][] = $attribute;
          } else {
            $element['attributes'][] = $attribute;
          }
        }
      }
      # assign last element
      $package['elements'][] = $element;
      # assign last package
      $packages[] = $package;
    
      output_elements($packages);
    ?></form>
  </div>
</div>
<?php
function output_elements($packages) {
  foreach ($packages AS $package) {
    ?><h2>
      <a href="javascript:toggleVisibility('<?php echo $package['name']; ?>')">
        <img src="plugins/xplankonverter/images/minus.png" id="<?php echo $package['name']; ?>_minimize_img" alt="Minimize Pakete" class = "minimize_img">
        <img src="plugins/xplankonverter/images/plus.png" id="<?php echo $package['name']; ?>_maximize_img" alt="Maximize Pakete" class = "maximize_img">
      </a>
      <a href="javascript:toggleVisibility(new Array('<?php echo $package['name']; ?>_all',<?php echo implode(',', array_map(function($element) { return $element['id']; }, $package['elements'])); ?>))">
        <img src="plugins/xplankonverter/images/minimize.png" id="<?php echo $package['name']; ?>_all_minimize_img" alt="Minimize Pakete" class = "minimize_img">
        <img src="plugins/xplankonverter/images/maximize.png" id="<?php echo $package['name']; ?>_all_maximize_img" alt="Maximize Pakete" class = "maximize_img">
      </a>
      <?php echo $package['name']; ?>
    </h2>
    <div id="<?php echo $package['name']; ?>" class="toggleable"><?php
      foreach ($package['elements'] AS $element) {
        ?><a href="javascript:toggleVisibility('<?php echo $element['id']; ?>')" class=hlink><img src="plugins/xplankonverter/images/minimize.png" id="<?php echo $element['id']; ?>_minimize_img" alt="Minimize Pakete" class = "minimize_img"><img src="plugins/xplankonverter/images/maximize.png" id="<?php echo $element['id']; ?>_maximize_img" alt="Maximize Pakete" class = "maximize_img"></a>
        <b><a name="xplan:<?php echo $element['element_name']; ?>" class="anchor"><?php 
        if ($element['altoderneuelements'] == "veraltet"){
          ?><s><?php echo $element['element_name']; ?></s></a> <img src="plugins/xplankonverter/images/veraltet.png" height="13"></b><?php
        }    
        else {
          echo $element['element_name']; ?></a></b><?php
        }
        if ($element['altoderneuelements'] == "neu") {
          ?> <img src="plugins/xplankonverter/images/aenderung.png" height="13"><?php
        }

        else {
          ?><a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/xplan_<?php echo $element['element_name']; ?>.html" target="_blank"><img src="plugins/xplankonverter/images/Link.png" width="15"></a><?php
        }
        ?>
        <div id="<?php echo $element['id']; ?>" class="toggleable">
          <i>Dokumentation:</i><br><?php
          echo $element['documentation'];
          ?><br><i>Attribute:</i><br><?php
          foreach ($element['attributes'] AS $attribute) {
            output_attribute($attribute);


          }
          if (count($element['associations']) > 0) {
            ?><i>Assoziationen:</i><br><?php
            foreach ($element['associations'] AS $association) {
              output_attribute($association);
            }
          }
        ?></div><br><?php
      }
    ?></div><?php
  }
}

function output_attribute($attribute) {
  # xplan element: vorn 'xplan:' und hinten 'PropertyType'
  # xplan enumeration: vorn 'xplan:' und hinten kein 'PropertyType'
  # gml type: vorn 'gml:'
  # simple type: alle anderen
  if (substr($attribute['attribut_type'], 0, 6) == 'xplan:') {
    if(substr($attribute['attribut_type'], -12) == 'PropertyType') {
      # xplan element
      $go = 'show_elements';
    } else {
      # xplan enumeration
      $go = 'show_simple_types';
    }
  } else {
    if (substr($attribute['attribut_type'], 0, 4) == 'gml:') {
      # gml type
      $go = 'show_simple_types';      
    }
    else {
      # simple type
      $go = 'show_simple_types';      
    }
  }
  $href = 'index.php?go=' . $go . '#' . str_replace('PropertyType', '', $attribute['attribut_type']);
	
	if ($attribute['altoderneuattributes'] == "veraltet"){
          echo "<s>";
        }    
  echo '&nbsp;&nbsp;name: <span title="' . $attribute['documentationattribute'] .  '">' . $attribute['attribut_name'] . '</span> [' . $attribute['attribut_minOccurs'] . '..' . $attribute['attribut_maxOccurs'] . ']' . ' type: <a href="' . $href . '">' . $attribute['attribut_type'] . '</a> ref: ' . $attribute['attribut_ref'] ;
	if ($attribute['altoderneuattributes'] == "veraltet") {
		echo " <img src=\"plugins/xplankonverter/images/veraltet.png\" height=\"13\"></s>";
	}
	if ($attribute['altoderneuattributes'] == "neu") {
		echo " <img src=\"plugins/xplankonverter/images/neu.png\" height=\"13\">";
	}
	echo '<br>';
}
?>