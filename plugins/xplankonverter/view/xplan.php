<?php
/*
* Session und Parameterhandling
*/
include('config/environment.php');
session_start();
$params = $_REQUEST;
$go = $params['go']; unset($params['go']);
$action = $params['action']; unset($params['action']);
$cwd = getcwd();
$config = json_decode(
  file_get_contents($cwd . '/config/database.conf'),
  true
);
$conn = pg_connect('dbname=' . $config['dbname'] . ' user=' . $config['user'] . ' password=' . $config['password']);
$angemeldet = angemeldet();

/*
  PHP Konstanten
*/

$laender = array( "nicht spezifiziert", "Schleswig-Holstein", "Hamburg", "Niedersachsen", "Bremen", "Nordrhein-Westfalen", "Hessen", "Rheinland-Pfalz", "Baden-Württemberg", "Bayern", "Saarland", "Berlin", "Brandenburg", "Mecklenburg-Vorpommern", "Sachsen", "Sachsen-Anhalt", "Thüringen", "Länderübergreifender Plan", "Bund");

$planarten = array(
  array("", "Alle"),
  array("0", "nicht spezifiziert"),
  array("1", "Teilfortschreibung"),
  array("4", "Änderung"),
  array("5", "Ergänzung"),
  array("10", "offen"),
  array("11", "Sachlicher Teilplan"),
  array("12", "Räumlicher Teilplan"),
  array("9999", "integrierter Plan")
);

$bundeslaender = array(
  array("", "Alle"),
  array("BY", "Bayern"),
  array("BE", "Berlin"),
  array("BW", "Baden-Württemberg"),
  array("BB", "Brandenburg"),
  array("HB", "Bremen"),
  array("HH", "Hamburg"),
  array("HE", "Hessen"),
  array("MV", "Mecklenburg-Vorpommern"),
  array("NI", "Niedersachsen"),
  array("NW", "Nordrhein-Westfalen"),
  array("RP", "Rheinland-Pfalz"),
  array("SL", "Saarland"),
  array("SN", "Sachsen"),
  array("ST", "Sachsen-Anhalt"),
  array("SH", "Schleswig-Holstein"),
  array("TH", "Thüringen"),
  array("DE", "Deutschland")
);

$rechtsstati = array(
  array("", "Alle"),
  array("1", "Grundsatz"),
  array("2", "Ziel/Grundsatz"),
  array("3", "Nachrichtliche Übernahme"),
  array("4", "nur Informationsgehalt"),
  array("5", "offen"),
  array("6", "Vorschlag"),
  array("7", "textliches Ziel"),
  array("8", "Bebauungsplan"),
  array("9999", "Ziel")
);

$planstati= array(
  array("", "Alle"),
  array("0", "nicht spezifiziert"),
  array("1", "verbindlich"),
  array("2", "Entwurf"),
  array("3", "Entwurf, genehmigt"),
  array("4", "Entwurf, geändert"),
  array("5", "außer Kraft"),
  array("6", "Plan ungültig"),
  array("7", "Entwurf, aufgegeben"),
  array("8", "Entwurf, ruht"),
  array("9", "allgemeine Planungsabsichten")
);

$raeumlichekonkretheiten = array(
  array("", "Alle"),
  array("1", "ortsteilscharf"),
  array("2", "gebietsscharf"),
  array("3", "standortscharf"),
  array("4", "Symbol m Standortbezug"),
  array("5", "Korridorbezug"),
  array("6", "offen"),
  array("7", "nicht identifizierbar"),
  array("9999", "gemeindescharf")
);

$gebietstypen = array(
  array("", "Alle"),
  array("1", "Vorbehaltsgebiet"),
  array("2", "Vorrangstandort"),
  array("3", "Vorbehaltsstandort"),
  array("4", "Vorsorgegebiet"),
  array("5", "Vorsorgestandort"),
  array("6", "Ausschlussgebiet"),
  array("7", "Vorranggeb/Eignungsgeb"),
  array("8", "Eignungsgebiet"),
  array("9", "offen"),
  array("10", "nicht identifizierbar"),
  array("9999", "Vorranggebiet")
);

$ebenen = array(
  "Alle",
  "Bund",
  "Region",
  "Land",
  "Braunkohlenplan"
);

$kernmodelle = array(
  "Alle",
  "RP_Freiraumstruktur",
  "RP_Infrastruktur",
  "RP_Siedlungsstruktur",
  "RP_Sonstiges"
);

$rechtscharaktere = array(
  "Alle",
  "ZielDerRaumordnung",
  "GrundsatzDerRaumordnung",
	"NachrichtlicheUebernahme",
	"NachrichtlicheUebernahmeZiel",
	"NachrichtlicheUebernahmeGrundsatz",
	"nurInformationsgehalt",
	"textlichesZiel",
	"ZielundGrundsatz",
	"Vorschlag"
);

$packages = array();
$sql  = "
  SELECT
    DISTINCT package
  FROM
    " . SCHEMA_PREFIX . "xplan.elements
  ORDER BY
    package
";
$result = pg_query($conn, $sql);
$packages = pg_fetch_all($result);
array_unshift($packages, array('package' => 'Alle'));

$objectids = array();
$sql  = "SELECT DISTINCT objectid FROM  ". SCHEMA_PREFIX . "roplamo.plaene ";
$sql .= " ORDER BY objectid";
$result = pg_query($conn, $sql);
$objectids = pg_fetch_all($result);

/************************************************************************************************
*                        Anwendungsfälle                                                        *
*************************************************************************************************/
switch ($go) {
  case 'get_comments':
    ($angemeldet) ? get_comments($params) : show_login();
    break;
  case 'get_planzeichen':
    ($angemeldet) ? get_planzeichen($params) : show_login();
    break;
  case 'get_plaene':
    ($angemeldet) ? get_plaene($params) : show_login();
    break;
  case 'get_inspirefeaturetype':
    ($angemeldet) ? get_inspirefeaturetype($params) : show_login();
    break;
  case 'get_inspireenumeration':
    ($angemeldet) ? get_inspireenumeration($params) : show_login();
    break;
  case 'load_xsd':
    ($angemeldet) ? load_xsd($_REQUEST['file'], $_REQUEST['truncate']) : show_login();
    break;
  case 'show_comments':
    if ($angemeldet) {
      if ($action == 'Hinzufügen') create_comments($params);
      show_comments();
    }
    else {
     show_login();
    }
    break;
  case 'show_calendar':
    ($angemeldet) ? show_calendar() : show_login();
    break;
  case 'show_elements':
    ($angemeldet) ? show_elements() : show_login();
    break;
  case 'show_hilfe':
    ($angemeldet) ? show_hilfe() : show_login();
    break;
  case 'show_ontologie':
    ($angemeldet) ? show_ontologie() : show_login();
    break;
  case 'show_inspire':
    ($angemeldet) ? show_inspire() : show_login();
    break;
  case 'show_impressium':
    show_impressium();
    break;
  case 'show_login':
    session_destroy();
    show_login();
    break;
  case 'show_plaene':
    ($angemeldet) ? show_plaene() : show_login();
    break;
  case 'show_planzeichen':
    ($angemeldet) ? show_planzeichen() : show_login();
    break;
  case 'show_simple_types':
    ($angemeldet) ? show_simple_types() : show_login();
    break;
  case 'show_uml':
    ($angemeldet) ? show_uml() : show_login();
    break;
  #case 'show_konvertierungform':
    #($angemeldet) ? show_konvertierungform() : show_login();
    #break;
  default:
    ($angemeldet) ? show_home() : show_login();
    break;
}
pg_close($conn);

/****************************************************************************************************************
*  PHP Funktionen                                                                                               *
*****************************************************************************************************************/
function angemeldet() {
  $angemeldet = false;
  if ($_SESSION['angemeldet']) {
    $angemeldet = true;
  }
  else {
    if (array_key_exists('username', $_POST) AND $_POST['username'] != '' AND array_key_exists('userbemerkung', $_POST) AND $_POST['userbemerkung'] != '') {
      # authentify the user
      $angemeldet = authentified($_POST['username'], $_POST['userbemerkung']);
      if ($angemeldet) {
        $_SESSION['angemeldet'] = true;
        $_SESSION['username'] = $_POST['username'];
      }
    }
  }
  return $angemeldet;
}

function authentified($username, $passwort){
  global $conn;
  $sql = "
    SELECT
      true AS angemeldet
    FROM
      " . SCHEMA_PREFIX . "roplamo.users
    WHERE
      username = '" . $username . "' AND
      passwort = '". md5($passwort) . "'
    ";
  $result = pg_query($conn, $sql);

  $num_rows = pg_num_rows($result);
  
  if ($num_rows > 0) {
    return true;
  }
  else{
    return false;
  }
}

function debug($msg) {
  echo 'Message:<br>' . $msg . '<br>';
}

function get_plaene($params) {
  include('classes/plaene.php');
  $plan = new Plan;
  output_data(
    $plan->get($params),
    $params['format']
  );
}

function get_inspirefeaturetype($params) {
  include('classes/inspirefeaturetype.php');
  $inspire = new inspire;
  output_data(
    $inspire->get($params),
    $params['format']
  );
}

function get_inspireenumeration($params) {
  include('classes/inspireenumerations.php');
  $inspire = new inspire;
  output_data(
    $inspire->get($params),
    $params['format']
  );
}

function get_planzeichen($params) {
  include('classes/planzeichen.php');
  $planzeichen = new Planzeichen;
  output_data(
    $planzeichen->get($params),
    $params['format']
  );
}

function output_csv($data) {
  # insert the column names as the first row into the data
  array_unshift(
    $data,
    array_keys($data[0])
  );
  header("Content-type: text/csv");
  header("Content-Disposition: attachment; filename=planzeichen.csv");
  header("Pragma: no-cache");
  header("Expires: 0");
  $outputBuffer = fopen("php://output", 'w');
  foreach($data as $val) {
    fputcsv($outputBuffer, $val);
  }
  fclose($outputBuffer);
}

/*
* This function output the array in $data in the given $format
* @params {array} $data: The array that should be output to the client
* @params {string} $format: The format that should be used for output
* currently only csv and json is supported. json is default if $format is empty
*/
function output_data($data, $format) {
  switch ($format) {
    case 'csv' :
      output_csv($data);
      break;
    default:
      echo json_encode($data);
  }
}

function output_header($with_menu) {
  global $angemeldet; ?>
<html>
  <head>
    <title>XPlanung</title>
    <meta name="author" content="GDI-Service" />
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8">

		<?php include('header.php'); ?>

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
  </head>
  <body><?php
  if ($with_menu) { ?>
    <div class="header">
    <nav>
      <a href="http://www.bbsr.bund.de/"><img src="images/BBSR-Logo.png" alt="BBSRIcon" class="logo"></a>
      <a href="http://www.xplanung.de/"><img src="images/XPlanung-Logo.png" alt="XPlanungIcon" class="logo"></a>
      <span class="spacer">&nbsp;</span>
      <a href="index.php?go=show_home" class="menue">Home</a>
      <ul>
        <li><a>XPlan</a>
          <ul>
            <li><a href="index.php?go=show_uml">XPlan-Modell</a></li>
            <li><a href="index.php?go=show_elements">XPlan&nbsp;Elemente</a></li>
            <li><a href="index.php?go=show_simple_types">Codelisten</a></li>
          </ul>
        </li>
        <li><a>ROPLAMO</a>
          <ul>
            <li><a href="index.php?go=show_plaene">Pläne</a></li>
            <li><a href="index.php?go=show_planzeichen">Planzeichen</a></li>
          </ul>
        </li>
      </ul>
      <!--<a href="index.php?go=show_elements" class="menue">XPlan&nbsp;Elemente</a>-->
      <!--<a href="index.php?go=show_simple_types" class="menue">Codelisten</a>  -->  
      <!--<a href="index.php?go=show_plaene" class="menue">Pläne</a>-->
      <!--<a href="index.php?go=show_planzeichen" class="menue">Planzeichen</a>-->
      <a href="index.php?go=show_inspire" class="menue">INSPIRE</a>
      <a href="index.php?go=show_ontologie" class="menue">Ontologie</a>
      <a href="index.php?go=show_comments" class="menue">Kommentare</a>
      <!--<a href="index.php?go=show_konvertierungform" class="menue">Konverter</a>-->
      <a href="index.php?go=show_hilfe" class="menue">Hilfe</a>
      </nav>
        
      <?php
      if ($angemeldet) { ?>
        <a href="index.php?go=show_login" title="Abmelden"><img src="images/logout.png" alt="Neu einloggen" class = "logout-image"></a>
        <span class="login-info"><?php echo $_SESSION['username']; ?></span> <?php
      } ?>
    </div><?php
  }
}

function output_footer() {?>
    <div class="footer">
      Erstellt von <a href="http://gdi-service.de/" class="hlink" target="_blank">GDI-Service</a> im Auftrag des <a href="http://www.bbsr.bund.de/" class="hlink" target="_blank">BBSR</a>.
      <a href="index.php?go=show_impressium" class="hlink" style="float:right">Impressium</a>
    </div>
  </body>
</html><?php
}

function show_plaene() {
  output_header(true); ?>
  <div id="main"><?php
  global $conn, $planarten, $bundeslaender, $planstati, $ebenen, $params;
  echo "<h1><center>Pläne</center><hr>";
  include('views/helptable.php');
  echo "</h1>";
  ?>
  <div id="plaene-container">
    <form method="Post" action="index.php">
      <input type="hidden" name="go" value="show_plaene">
      Bundesland:
      <select id="bundeslandselektor" name="bundesland_abk" size="1">
        <?php for ($i=0; $i < count($bundeslaender); $i++) { ?>
        <option value="<?php echo $bundeslaender[$i][0]; ?>"<?php if ($params['bundesland_abk'] == $bundeslaender[$i][0]) { ?> selected<?php } ?>><?php echo $bundeslaender[$i][1]; ?></option>
        <?php } ?>
      </select>
      Planart:
      <select id="planartselektor" name="planart" size="1">
       <?php for ($i=0; $i < count($planarten); $i++) { ?>
         <option value="<?php echo $planarten[$i][0]; ?>"<?php if ($params['planart'] == $planarten[$i][0]) { ?> selected<?php } ?>><?php echo $planarten[$i][1]; ?></option>
       <?php } ?>
       </select>
      Ebene:
      <select id="ebeneselektor" name="ebene" size="1">
        <?php for ($i=0; $i < count($ebenen); $i++) { ?>
          <option value="<?php echo $ebenen[$i]; ?>"<?php if ($params['ebene'] == $ebenen[$i]) { ?> selected<?php } ?>><?php echo $ebenen[$i]; ?></option>
        <?php } ?>
      </select>
      <input type="submit" value="Aktualisieren">
    </form>
  </div>
  <script language="javascript" type="text/javascript">
		function planIdFormatter(value, row) {
      if (row.hat_planzeichen == 't') {
		    output = '<a href="index.php?go=show_planzeichen&planid=' + value + '" target="_blank">' + value + '</a>';        
      }
      else {
        output = value;
      }
      output += '<a href="index.php?go=show_comments&new_object_type=Plan&new_object_id=' + row.objectid + '&new_object_name=' + row.planid + ' (' + row.name + ')">';
      output += '<img src="images/ICONcomments';
      if (row.hat_comments == 'f') output += '_leer';
      output += '.png" style="width:15;height:15; float:right;">';
      output += '</a>';
      return output;
		}
  </script>
    <table
      id="plaene_table"
      class="table table-striped"
      data-toggle="table"
      data-url="index.php?go=get_plaene&<?php echo http_build_query($params); ?>"
      data-height="800"
      data-click-to-select="false"
      data-sort-name="legcode"
      data-sort-order="asc"
      data-search="true"
      data-show-refresh="true"
      data-show-toggle="true"
      data-show-columns="true"
      data-query-params="queryParams"
      data-pagination="true"
      data-page-size="25"
      data-show-export="true"
      data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
      >
      <thead>
        <tr>
          <th
            data-field="objectid"
            data-visible="false"
          >Plan-ID</th>
          <th
            data-field="hat_comments"
            data-visible="false"
          >Kommentare</th>
          <th
            data-flat="true"
            data-field="planid"
            data-align="left"
            data-sortable="true"
            data-switchable="false"
						data-formatter="planIdFormatter"
          >Plan-Code</th>
          <th
            data-field="lnd"
            data-align="left"
            data-sortable="true"
						data-visible="false"
          >Bundesland</th>
          <th
            data-field="name"
            data-align="left"
            data-sortable="true"
          >Name</th>
          <th
            data-field="plr"
            data-align="left"
						data-sortable="true"
          >PLR</th>
          <th
            data-field="arttext"
            data-align="left"
            data-visible="false"
          >Planart</th>
          <th
            data-field="traeger"
            data-align="left"
            data-visible="false"
          >Träger</th>
          <th
            data-field="anmerkungen"
            data-align="left"
            data-visible="false"
          >Anmerkungen</th>
          <th
            data-field="ebene_planung"
            data-align="left"
            data-visible="true"
          >Ebene</th>
					 <th
            data-field="datum"
            data-align="left"
						data-sortable="true"
            data-visible="false"
          >Datum des Inkrafttretens</th>
        </tr>
      </thead>
    </table>
  </div><?php
  output_footer();
}

function show_planzeichen() {
  output_header(true); ?>
  <div id="main"><?php
    global $conn, $bundeslaender, $kernmodelle, $rechtscharaktere, $planstati, $params;
    echo "<h1><center>Planzeichen</center><hr>";
    include('views/helptable.php');
    echo "</h1>";
    ?>
    <script language="javascript" type="text/javascript">    
      function legCodeFormatter(value, row) {
        output = value + '<a href="index.php?go=show_comments&new_object_type=Planzeichen&new_object_id=' + row.objectid + '&new_object_name=' + row.legcode + '">';
        output += '<img src="images/ICONcomments';
        if (row.hat_comments == 'f') output += '_leer';
        output += '.png" style="width:15;height:15; float:right;">';
        output += '</a>';
        return '<div class="legcode">' + output + '</div>';
      }
    
      function planIdFormatter(value, row) {
        return '<a href="index.php?go=show_plaene&planid=' + value + '" target="_blank">' + value + '</a>';
      }

      function packageFormatter(value, row) {
        return value;
      }
    
      function paketFormatter(value, row) {
        return  '<a href="index.php?go=show_uml#xplan:' + value + '">' + value + '</a>';
      }

      function featureTypeFormatter(value, row) {
        return '<a href="index.php?go=show_elements#xplan:' + value + '">' + value + '</a>';
      }

  		function enumerationsFormatter(value, row) {
  			console.log(value);
  			var parts = [];
  			if (value != null)  parts = value.split(' in ');
  			if (parts.length == 2) {
  				return parts[0] + ' in <a href="index.php?go=show_simple_types#xplan:' + parts[1] + '">' + parts[1] + '</a>';
  			}
  			else {
  				return value;
  			}
  		}
    </script>
    <div id="planzeichen-container">
      <form method="Post" action="index.php">
        <input type="hidden" name="go" value="show_planzeichen">
        Bundesland:
        <select id="bundeslandselektor" name="bundesland_abk" size="1">
          <?php for ($i=0; $i < count($bundeslaender); $i++) { ?>
          <option value="<?php echo $bundeslaender[$i][0]; ?>"<?php if ($params['bundesland_abk'] == $bundeslaender[$i][0]) { ?> selected<?php } ?>>
            <?php echo $bundeslaender[$i][1]; ?>
          </option>
          <?php } ?>
        </select>
        Paket:
        <select id="paketselektor" name="paket" size="1">
          <?php for ($i=0; $i < count($kernmodelle); $i++) { ?>
          <option value="<?php echo $kernmodelle[$i]; ?>"<?php if ($params['paket'] == $kernmodelle[$i]) { ?> selected<?php } ?>>
            <?php echo $kernmodelle[$i]; ?>
          </option>
          <?php } ?>
        </select>
  			 Rechtscharakter:
        <select id="rechtscharakterselektor" name="rechtscharakter" size="1">
          <?php for ($i=0; $i < count($rechtscharaktere); $i++) { ?>
          <option value="<?php echo $rechtscharaktere[$i]; ?>"<?php if ($params['rechtscharakter'] == $rechtscharaktere[$i]) { ?> selected<?php } ?>>
            <?php echo $rechtscharaktere[$i]; ?>
          </option>
          <?php } ?>
        </select>		
        <input type="submit" value="Aktualisieren">
      </form>

      <table
        class="table table-striped"
        data-toggle="table"
        data-url="index.php?go=get_planzeichen&<?php echo http_build_query($params); ?>"
        data-height="800"
        data-click-to-select="false"
        data-sort-name="legcode"
        data-sort-order="asc"
        data-search="true"
        data-show-refresh="true"
        data-show-toggle="true"
        data-show-columns="true"
        data-query-params="queryParams"
        data-pagination="true"
        data-page-size="25"
        data-show-export="true"
        data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
        >
        <thead>
          <tr>
            <th
              data-field="objectid"
              data-visible="false"
            >Planzeichen-ID</th>
            <th
              data-field="hat_comments"
              data-visible="false"
            >Kommentare</th>
            <th
              data-flat="true"
              data-field="legcode"
              data-align="left"
              data-sortable="true"
              data-switchable="false"
              data-formatter="legCodeFormatter"
            >Planzeichen-Code</th>
            <th
              data-field="planid"
              data-align="right"
              data-sortable="true"
              data-formatter="planIdFormatter"
            >Plan-ID</th>
            <th
              data-field="name"
              data-align="left"
              data-sortable="true"
            >Name</th>
            <th
              data-field="gruppe"
              data-align="left"
              data-visible="false"
            >Gruppe</th>
            <th
              data-field="untergr"
              data-align="left"
              data-visible="false"
            >Untergruppe</th>
            <th
              data-field="sonst"
              data-align="left"
              data-visible="false"
              >Sonstiges</th>
            <th
              data-field="paketeigen"
              data-align="left"
              data-sortable="true"
              data-formatter="paketFormatter"
              >Vorschlag XPlan Paket Entsprechung</th>
            <th
              data-field="featuretypeeigen"
              data-align="left"
              data-sortable="true"
              data-formatter="featureTypeFormatter"
            >Vorschlag XPlan Featuretype Entsprechung</th>
            <th
              data-field="enumerationseigen"
              data-align="left"
              data-sortable="true"
  					 data-formatter="enumerationsFormatter"
            >Vorschlag XPlan Enumerations Entsprechung</th>
  					<th
              data-field="xplan_rprechtscharakter_entwurf"
              data-align="left"
  						data-sortable="true"
              data-visible="true"
            >RP_Rechtscharakter</th>
            <th
              data-field="rprechtsstandeigen"
              data-align="left"
  						data-sortable="true"
              data-visible="true"
            >RP_Rechtsstand</th>
            <th
              data-field="rpgebietstypeigen"
              data-align="left"
  						data-sortable="true"
              data-visible="false"
            >RP_GebietsTyp</th>
  					<th
              data-field="plr"
              data-align="left"
  						data-sortable="false"
              data-visible="false"
            >PLR</th>
            	<th
              data-field="hsrcl"
              data-align="left"
  						data-sortable="true"
              data-visible="false"
            >HSRCL</th>
          </tr>
        </thead>
      </table>
    </div>
  
    <div id="comments-window">
      <a href="#" onclick="hideComments();"><img src="images/close_window.png" title="Fenster schließen" alt="Fenster schließen" class = "closewindow-image"></a>
      <b>Planzeichen:</b> <span id="planzeichen-legcode"></span><br>
      <b>Name:</b> <span id="planzeichen-name"></span><br>
      <b>Plan:</b> <span id="planzeichen-planid"></span><br>
      <form>
        <b>Ansprechpartner für Rückfragen:</b> <br/>
        <input type='text' name='name' id='comment-name'/><br/>
        <b>Kommentar:</b> <br/>
        <textarea name='comment' id='comment-text'></textarea><br/>
        <!-- captcha braucht eventuell noch ID-->
        <input type='button' value='Kommentar hinzufügen' onclick="postComments();">  
      </form>
      <div id="comments-container"></div>
    </div>
  </div><?php
  output_footer();
}

function show_calendar() {
  output_header(true); ?>
  <iframe src="https://www.google.com/calendar/embed?src=gdi-service.de_k03497i3du1rqrusj9ldneerj0%40group.calendar.google.com&ctz=Europe/Berlin" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
  <?php output_footer();
}

function show_comments() {
  global $conn, $params;
  $authors = array();
  $sql  = "
    SELECT DISTINCT
      u.id AS author_id,
      u.vorname || ' ' || u.nachname || ' (' || u.organisation || ')' AS author_name
    FROM
       ". SCHEMA_PREFIX . "roplamo.comments c,
      " . SCHEMA_PREFIX . "roplamo.users u
    WHERE
      u.id = c.user_id
    ORDER BY
      author_name
  ";
  $result = pg_query($conn, $sql);
  $authors = pg_fetch_all($result);
  array_unshift(
    $authors,
    array(
      'author_id' => '',
      'author_name' => 'Alle'
    )
  );

  $object_types = array(
    'Alle',
    'Plan',
    'Planzeichen',
    'XPlan-Element',
    'FeatureType',
    'Paket',
    'XPlan-Enumeration',
    'Kommentar'
  );
  
  output_header(true); ?>
  <div id="main">
  <?php
    echo "<h1><center>Kommentare</center><hr>";
    include ('views/helptable.php');
    echo "</h1>";
   ?>
    <script language="javascript" type="text/javascript">      
      function timestampFormatter(value, row) {
        parts = value.split(' ');
        date_parts = parts[0].split('-');
        time_parts = parts[1].split('.');
        return date_parts[2] + '.' + date_parts[1] + '.' + date_parts[0] + ' ' + time_parts[0];
      }
      
      function objectFormatter(value, row) {
        return row.object_type + '<br>' + row.object_name;
      }
      
      function authorFormatter(value, row) {
        return row.author_forename + ' ' + row.author_surename + '<br>(' + row.author_organisation + ')';
      }
      
      function messageFormatter(value, row) {
        output = value + '<a href="index.php?go=show_comments&new_object_type=Kommentar&new_object_id=' + row.id + '&new_object_name=' + row.message.substring(0, 25);
        if (row.message.length > 25) output += '...';
        output += '">';
        output += '<img src="images/ICONcomments';
        if (row.hat_comments == 'f') output += '_leer';
        output += '.png" style="width:15;height:15; float:right;">';
        output += '</a>';
        return '<div class="legcode">' + output + '</div>';
      }
      
      function hideNewCommentForm() {
        $('#new_comment').hide();
        $('#new_object_id').val('');
      }
    </script>
    <div id="planzeichen-container">
      <form method="Post" action="index.php">
        <input type="hidden" name="go" value="show_comments"><?php
        if ($params['new_object_id'] != '') { ?>
          <div id="new_comment">
            Neuer Kommentar zu <b><?php echo $params['new_object_type']; ?>:</b> <?php echo $params['new_object_name']; ?><br>
            <input type="hidden" name="new_object_type" value="<?php echo $params['new_object_type']; ?>">
            <input type="hidden" name="new_object_name" value="<?php echo $params['new_object_name']; ?>">
            <input type="hidden" id="new_object_id" name="new_object_id" value="<?php echo $params['new_object_id']; ?>">
            <textarea name="new_comment" cols="100" rows="5"><?php echo $params['new_comment']; ?></textarea>
            <input type="submit" name="action" value="Hinzufügen">
            <input type="button" name="action" value="Abbrechen" onclick="hideNewCommentForm()">
          </div><?php
        } ?>
        Autor:
        <select id="autorenselektor" name="author_id" size="1">
          <?php for ($i=0; $i < count($authors); $i++) { ?>
          <option value="<?php echo $authors[$i]['author_id']; ?>"<?php if ($params['author_id'] == $authors[$i]['author_id']) { ?> selected<?php } ?>>
            <?php echo $authors[$i]['author_name']; ?>
          </option>
          <?php } ?>
        </select>
        Objektart:
        <select id="object_type_selector" name="object_type" size="1">
          <?php for ($i=0; $i < count($object_types); $i++) { ?>
          <option value="<?php echo $object_types[$i]; ?>"<?php if ($params['object_type'] == $object_types[$i]) { ?> selected<?php } ?>>
            <?php echo $object_types[$i]; ?>
          </option>
          <?php } ?>
        </select>
        <input type="submit" name="action" value="Aktualisieren">
      </form>
      <table
        class="table table-striped"
        data-toggle="table"
        data-url="index.php?go=get_comments&<?php echo http_build_query($params); ?>"
        data-height="800"
        data-click-to-select="false"
        data-search="true"
        data-show-refresh="true"
        data-show-toggle="true"
        data-show-columns="true"
        data-query-params="queryParams"
        data-pagination="true"
        data-page-size="25"
        >
        <thead>
          <tr>
            <th
              data-field="id"
              data-visible="false"
            >Kommentar_id</th>
            <th
              data-flat="true"
              data-field="created_at"
              data-align="left"
              data-sortable="true"
              data-switchable="false"
              data-formatter="timestampFormatter"
            >von Wann?</th>
            <th
              data-field="author_username"
              data-align="left"
              data-sortable="true"
              data-formatter="authorFormatter"
            >von Wem?</th>
            <th
              data-field="object_type"
              data-align="left"
              data-sortable="true"
              data-formatter="objectFormatter"
            >zu Was?</th>
            <th
              data-field="message"
              data-align="left"
              data-sortable="false"
              data-formatter="messageFormatter"
            >Kommentar</th>
          </tr>
        </thead>
      </table>
    </div>
  </div><?php
  output_footer();
}

function get_comments($params) {
  include('classes/comments.php');
  $comment = new Comment;
  output_data(
    $comment->get($params),
    $params['format']
  );
}

function create_comments($params) {
  include('classes/comments.php');
  $comment = new Comment;
  $comment->create($params);
}

#function show_konvertierungform() {
  #output_header(true);
 #include('classes/konvertierungform.php');
 #output_footer();
#}


function show_login() {
  output_header(true); ?>
  <div id="main">
    <div id="login-window">
      <h1>Login</h1><?php
      if ((array_key_exists('username', $_POST) AND $_POST['username'] != '') OR (array_key_exists('userbemerkung', $_POST) AND $_POST['userbemerkung'] != '')) { ?>
        <span class="error_msg">Anmeldung fehlgeschlagen!</span><br><?php
      } ?>
      <form action="index.php" method="Post">
      	<b>Username:</b><br>
      	<input type ='text' id='usernameeingabe' maxlength='18' name="username"><br>
      	<b>Passwort:</b><br>
      	<input type ='password' id='passworteingabe' maxlength='18' name="userbemerkung"><br><br>
      	<input type="submit" value = "Anmelden">
      </form>
    </div>
  </div><?php
  output_footer();
}

function show_home() {
  output_header(true);
	?>
  <div id="main">
        <!--  Javascript für Show und Hide () -->
    <script type="text/javascript" language="JavaScript">
    function HideContent(d) {
    document.getElementById(d).style.display = "none";
    }
    function ShowContent(d) {
    document.getElementById(d).style.display = "block";
    }
    function ReverseDisplay(d) {
    if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
    else { document.getElementById(d).style.display = "none"; }
    }
    </script>
		<div class="textsite">
			<center>
      <h1>Modellvorhaben der Raumordnung</h1>
			<h2>Entwicklung und Implementierung eines Standards für den Datenaustausch in der Raumordnungsplanung</h2>
      <hr>
      </center>
      <p>
        <h3>Änderungen zum 15.01.2016</h3>
        <li>Bereitstellung der <a href="http://xplan-raumordnung.de/iqvoc/de.html">Ontologie</a></li>
        <li>Bereitstellung einer <a href="http://xplan-raumordnung.de/index.php?go=show_inspire">INSPIRE-Sektion</a> mit Mapping-Tables von XPlan nach INSPIRE</li>
        <li>Die HSRCL-Zuordnung aller Planzeichen des ROPLAMO kann auf Wunsch ausgewählt werden</li>
        <li>Bereitstellung einer Liste aller Modelländerungen zum <a href="/model/2015_12_03_Aenderungsliste_XPlan_Raumordnungsmodell.doc">herunterladen</a></li>
        <li>Bereitstellung der Raumordnungsplan Konformitätsbedingungen <a href="/model/2016_01_11_Konformitaetsbedingungen.doc">herunterladen</a></li>
        <li>Zusammenfassung XPlan und ROPLAMO für XPlan Elemente und Codelisten sowie Pläne und Planzeichen im Menü mit Unterpunkten</li>
      </p>
      <a href="javascript:ReverseDisplay('aeltereupdates')" class=hlink>
      Ältere Änderungen
      </a>
      <div id="aeltereupdates" style="display:none;">
        <p>
          <h3>Änderungen zum 11.12.2015</h3>
          <li>Erweiterung des UML- und Datenbank-Modells, inklusive Änderungen auf Basis der Gespräche mit der AG E-Government und der Länder</li>      
          <li>Bereitstellung des INSPIRE HSRCL Übersetzungsvorschlags zum <a href="inspire/hsrcl_uebersetzung.xlsx">herunterladen</a></li>
          <li>Änderungen einiger Textstellen der Homepage</li>
        </p>
          <h3>Änderungen zum 23.07.2015</h3>
          <li>Funktionen zum Export der Tabellen der Pläne und Planzeichen in die Formate (JSON, XML, CSV, TXT, SQL und MS-Excel)</li>      
          <li>Änderungen des UML- und Datenbank-Modells auf Basis der Gespräche mit der AG E-Government der MKRO (in UML sind besprochene Änderungen als graue Kommentare gekennzeichnet)</li>
          <li>Herausnahme von nicht-verbindlichen Plänen und Planzeichen aus den ROPLAMO-Tabellen</li>
          <li>Änderungen in den ROPLAMO-Zuordnungen auf Basis der erhaltenen Kommentare</li>
          <li>Erweiterte Dokumentation, zum Beispiel FeatureType-Definitionen in der UML-Modelldarstellung als Mouseover-Effekt</li>
          <li>Ein Modelldownload als PDF ist nun auf der Modell-Sektion möglich</li>
          <li>Weitere kleinere Änderungen und Bugfixes</li>
        </p>
        <p>
          <h3>Änderungen zum 16.09.2015</h3>
          <li>Erweiterung des UML- und Datenbank-Modells, inklusive Änderungen auf Basis der Gespräche mit der AG E-Government</li>      
          <li>Bereitstellung des INSPIRE HSRCL Übersetzungsvorschlags zum <a href="inspire/hsrcl_uebersetzung.xlsx">herunterladen</a></li>
          <li>Änderungen in den ROPLAMO-Zuordnungen auf Basis der erhaltenen Kommentare</li>
        </p>
      </div>
			<form action ="index.php">
			<input type="hidden"name="go" value="show_home">
      <h3>Einleitung</h3>
			<p>
				Um einen effektiven Austausch von Geodaten der Raumordnung im Bundesgebiet zu ermöglichen, der nicht nur die öffentliche Verwaltung, sondern auch Träger öffentlicher Belange, die Privatwirtschaft und Bürger mit einschließt, ist eine Standardisierung erforderlich, die auch den Umfang der bei den Geodaten zu dokumentierenden Sachattribute thematisiert. In dem Modellvorhaben der Raumordnung "Entwicklung und Implementierung eines Standards für den Datenaustausch in der Raumordnungsplanung" des Bundesinstituts für Bau-, Stadt- und Raumforschung (BBSR) wird das XPlanung-Datenaustauschformat für Raumordnungspläne in enger Zusammenarbeit mit der AG E-Government des Ausschusses für Struktur und Umwelt sowie der Länder weiterentwickelt.
			</p>
			<h3>XPlan</h3>
			<p>
				Die derzeitige Arbeitsversion des XPlan Raumordnungsmodells kann in der Sektion <a href=index.php?go=show_uml class=hlink>XPlan-Modell</a> des Menüpunktes XPlan als interaktives Unified Modelling Language Klassendiagramm eingesehen werden. Seine Grundlage bildet das Regionalplan Kernmodell von XPlan 4.1, welches auf Basis der Daten des Raumordnungsplanmonitors (ROPLAMO) des BBSR, der Erweiterungsmodelle Niedersachsen-Schleswig-Holstein-Mecklenburg-Vorpommern (NSM), Rheinland-Pfalz (RLP) sowie Nordrhein-Westfalen (NRW), Gespräche mit der AG E-Government, Gespräche mit den einzelnen Bundesländer und weiterer Quellen verbessert wurde. Gleichzeitig finden sich hier auch Downloads des Modells als Enterprise Architect-Datei, als XMI-Datei, die zum Modell gehörigen XSD's, eine Liste der Modelländerungen, eine PDF-Datei der relevanten UML-Graphiken und die erweiterten Konformitätsbedingungen des Modells.
			<p>
				In den Sektionen <a href=index.php?go=show_elements class=hlink>Xplan Elemente</a> und <a href=index.php?go=show_simple_types class=hlink>Codelisten</a> des Menüpunktes XPlan werden einzelnde Featuretypen, Enumerations und ähnliche Elemente des Modells aufgelistet, definiert und miteinander in Verknüpfung gestellt. Gleichzeitig bestehen Links zu Einträgen im xplanungwiki, falls diese dort vorhanden sind.<br>
			</p>
			<h3>ROPLAMO</h3>
			<p>
				Die im Raumordnungsplan-Monitor (ROPLAMO) aufgenommenen Daten wurden für das Projekt untersucht und ihre generelle Kompabilität in Bezug auf das erweiterte XPlan-Schema überprüft. Diese können von den Ländern eingesehen werden und dienen als Referenz für eine zukünftige Konvertierung der Daten. Die ROPLAMO-Daten sind in den Sektionen <a href=index.php?go=show_plaene class=hlink>Pläne</a> und <a href=index.php?go=show_planzeichen class=hlink>Planzeichen</a> des Menüpunktes ROPLAMO zu finden. Sie können z.B. länder-oder planspezifisch sortiert werden und erlauben gleichfalls einen optionalen Vergleich mit den Daten anderen Länder. Eine genauere Erläuterung der beiden Sektionen finden Sie im Menüpunkt Hilfe.
			</p>
			<p>
				Gleichzeitig können die vorgenommenen Kategorisierungsvorschläge der Planzeichen in das XPlan-Schema überprüft werden und gegebenenfalls falsche Kategorisierungen, Kommentare oder Erweiterungswünsche durch das dazugehörige Kommentarfeld durch einen Klick auf die Planzeichen-ID abgegeben werden. Hierbei ist zu überprüfen, ob die Daten dem Inhalt und der Funktion der Pläne und Planzeichen gerecht eingeordnet sind. Durch abgegebene Kommentare kann das Schema verbessert werden, um alle bestehenden Pläne und Planzeichen sinngerecht aufzunehmen.
			</p>
			<p>
				Raumordnungspläne selbst werden in XPlan dem FeatureType RP_Plan in RP_Basisobjekte zugeordnet. Dieser FeatureType hat den Anspruch, alle Raumordnungspläne, die auch im ROPLAMO enthalten sind, zu erfassen. Falls ein Plan hier nicht aufgenommen werden kann oder Erweiterungsbedarf des Pakets besteht, kann dieser nach Rücksprache erweitert werden.
			</p>
      <h3>INSPIRE</h3>
			<p>
				Die Sektion <a href=index.php?go=show_inspire class=hlink>INSPIRE</a> enthält Tabellen zum Mapping von XPlan nach INSPIRE, einen Übersetzungsvorschlag für die HSRCL-Codeliste sowie Dokumente zu Pflichtelementen und Attributierungen des Schemas.
			</p>
      <h3>Ontologie</h3>
			<p>
				In der <a href=http://xplan-raumordnung.de/iqvoc/de.html class=hlink>Ontologie</a> werden verschiedene Konzepte und Definitionen festgehalten. Es finden sich Konzepte zum XPlan-Modell, zum INSPIRE-Modell, den dazugehörigen HILUCS und HSRCL-Listen sowie die durch das Projekt entworfene Nationale Codeliste Deutschlands für Raumordnungsdaten und weitere Listen zu Planzeichenkatalogen verschiedener Länder. Die Elemente der verschiedenen Listen referenzieren sich und auch externe Thesauri gegenseitig, so dass zum Beispiel Definitionsunterschiede verschiedener Planzeichen nachgeschlagen werden können.
			</p>
      <h3>Kommentare</h3>
			<p>
				Die <a href=index.php?go=show_comments class=hlink>Kommentare</a>-Sektion beinhaltet abegebene Kommentare zu den ROPLAMO-Zuordnungen und Elementen sowie allgemeines Feedback der Planträger der Länder.
			</p>
			<h3>Hilfe</h3>
			<p>
				Die <a href=index.php?go=show_hilfe class=hlink>Hilfe</a>-Sektion beantwortet häufig gestellte Fragen und enthält weiterführende Links zum MORO Projekt und XPlanung.
			</p>
		</div>
  </div>  
	<?php
  output_footer();
}

function show_impressium() {
  output_header(true); ?>
  <div id="main">
		<div class="textsite">
			<center>
      <h1>Impressium</h1>
      </center>
      <hr>
			<h2>Herausgeber:</h2>
			<p>
				Bundesministerium für Verkehr und digitale Infrastruktur (BMVI)<br>
				Invalidenstraße 44<br>
				10115 Berlin <br>
				<a href="http://www.bmvi.de"> Homepage des Bundesministeriums für Verkehr und digitale Infrastruktur</a>
			</p>
			<h2>Wissenschaftliche Begleitung</h2>
			<p>
				Bundesinstitut für Bau-, Stadt- und Raumforschung (BBSR) im Bundesamt für <br>
				Bauwesen und Raumordnung (BBR)<br>
				Deichmanns Aue 31-37<br>
				53179 Bonn <br>
				<a href="http://www.bbsr.bund.de">Homepage des Bundesinstituts für Bau-, Stadt- und Raumforschung</a>
			</p>
			<h2>Rechtliche Hinweise</h2>
			<p>
				Das Bundesministerium für Verkehr, und digitale Infrastruktur (BMVI) und das<br>
				Bundesamt für Bauwesen und Raumordnung (BBR) übernehmen keine Haftung für <br>
				die Inhalte dieser Website. An den Inhalten besteht Urheberrechtsschutz. Für die <br>
				Inhalte fremder Seiten, die über einen Link erreicht werden, sind BMVBS und BBR<br>
				nicht verantwortlich.
			</p>
			<h2>Realisierung</h2>
			<p>
				GDI-Service Rostock<br>
				Joachim-Jungius-Straße 9, Haus 2, Raum 65<br>
				18059 Rostock<br>
				Tel.: 0381 / 403 44444<br>
				E-Mail: <a href="mailto:peter.korduan@gdi-service.de">peter.korduan@gdi-service.de</a><br>
				Web: <a href="http://www.gdi-service.de">www.gdi-service.de</a>
			</p>
			<h1>Haftungsausschluss</h1>
			<h2>Inhalte des Onlineangebotes</h2>
			<p>
				Der Herausgeber übernimmt keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen. Haftungsansprüche gegen den Herausgeber, welche sich auf Schäden materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollständiger Informationen verursacht wurden, sind grundsätzlich ausgeschlossen. Der Herausgeber behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.
			</p>
			<h2>Verweise und Links</h2>
			<p>
				Für alle direkten oder indirekten Verweise auf fremde Webseiten ("Hyperlinks"), die außerhalb des Verantwortungsbereiches des Herausgebers liegen, wird keine Haftung übernommen. Der Herausgeber erklärt hiermit ausdrücklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zukünftige Gestaltung, die Inhalte oder die Urheberschaft der gelinkten/verknüpften Seiten hat der Herausgeber keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdrücklich von allen Inhalten aller gelinkten/verknüpften Seiten, die nach der Linksetzung verändert wurden. Für illegale, fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der über Links auf die jeweilige Veröffentlichung lediglich verweist.
			</p>
			<h2>Urheber- und Kennzeichenrecht</h2>
			<p>
				Der Herausgeber ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Grafiken, Tondokumente, Videosequenzen und Texte zu beachten, von ihm selbst erstellte Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zurückzugreifen. Alle innerhalb des Internetangebotes genannten und ggf. durch Dritte geschützten Marken- und Warenzeichen unterliegen uneingeschränkt den Bestimmungen des jeweils gültigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigentümer. Allein aufgrund der bloßen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter geschützt sind! Das Copyright für veröffentlichte, vom Herausgeber selbst erstellte Objekte bleibt allein beim Herausgeber der Seiten. Eine Vervielfältigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdrückliche Zustimmung des Herausgebers nicht gestattet.
			</p>
			<h2>Datenschutz</h2>
			<p>
				Sofern innerhalb des Internetangebotes die Möglichkeit zur Eingabe persönlicher oder geschäftlicher Daten (Emailadressen, Namen, Anschriften) besteht, so erfolgt die Preisgabe dieser Daten seitens des Nutzers auf ausdrücklich freiwilliger Basis. Die Nutzung der im Rahmen des Impressums oder vergleichbarer Angaben veröffentlichten Kontaktdaten wie Postanschriften, Telefon- und Faxnummern sowie Emailadressen durch Dritte zur Übersendung von nicht ausdrücklich angeforderten Informationen ist nicht gestattet. Rechtliche Schritte gegen die Versender von sogenannten Spam-Mails bei Verstössen gegen dieses Verbot sind ausdrücklich vorbehalten.
			</p>
		</div>
  </div><?php
  output_footer();
}


function show_inspire() {
  global $conn, $params;

  output_header(true); ?>
  <div id="main">
    <div class="textsite">
        <!--  Javascript für Show und Hide () -->
      <script type="text/javascript" language="JavaScript">
        function HideContent(d) {
        document.getElementById(d).style.display = "none";
        }
        function ShowContent(d) {
        document.getElementById(d).style.display = "block";
        }
        function ReverseDisplay(d) {
        if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
        else { document.getElementById(d).style.display = "none"; }
        }
        </script>
     
      <h1><center>INSPIRE<hr></center>
      <?php
      include ('views/helptable.php');
      ?>
      </h1></center>
     
     
      <h3>
      XPlan-FeatureTypes auf INSPIRE HSRCL als 
      <a href="javascript:ReverseDisplay('xplanfeaturetypetoinspire')" class=hlink>
      Tabelle
      </a>
      </h3>
      
      Diese Tabelle beinhaltet eine Zuordnung der in XPlan auftretenden FeatureTypes für Raumordnungsdaten auf die INSPIRE Hierarchical Supplementary Codelist (HSRCL) für den FeatureType SupplementaryRegulation. Diese Zuordnung stellt auch die Basis für die automatische Konvertierung von XPlan-konformen Elementen im Konverter. Falls gleichzeitig Enumerationszuweisungen in XPlan bestehen, überschreiben diese gegebenenfalls die FeatureType-Zuordnung.
      <div id="xplanfeaturetypetoinspire" style="display:none;">
      <p>
        <div id="planzeichen-container">
          <form method="Post" action="index.php">
            <input type="hidden" name="go" value="show_inspire">
          </form>
          <table
            class="table table-striped"
            data-toggle="table"
            data-url="index.php?go=get_inspirefeaturetype&<?php echo http_build_query($params); ?>"
            data-height="800"
            data-click-to-select="false"
            data-search="true"
            data-show-refresh="true"
            data-show-toggle="true"
            data-show-columns="false"
            data-query-params="queryParams"
            data-pagination="true"
            data-page-size="50"
            data-show-export="true"
            data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
            >
            <thead>
              <tr>
                <th
                  data-flat="true"
                  data-field="xplan_featuretype"
                  data-align="left"
                  data-sortable="true"
                  data-switchable="false"
                >XPlan-FeatureType</th>
                <th
                  data-flat="true"
                  data-field="hsrcl"
                  data-align="left"
                  data-sortable="true"
                  data-switchable="false"
                >HSRCL</th>
              </tr>
            </thead>
          </table>
        </div>
      </p>
      </div>
      <h3>XPlan-Enumerations auf INSPIRE HSRCL als
      <a href="javascript:ReverseDisplay('xplanenumerationtoinspire')" class=hlink>
      Tabelle
      </a>
      </h3>
      Diese Tabelle beinhaltet eine Zuordnung der in XPlan auftretenden Enumerationen für Raumordnungsdaten auf die INSPIRE Hierarchical Supplementary Codelist (HSRCL) für den FeatureType SupplementaryRegulation. Diese Zuordnung stellt auch die Basis für die automatische Konvertierung von XPlan-konformen Elementen im Konverter. Nicht beachtet sind hierbei Zuordnungen zu Enumerationen der Basisobjekte (RP_Basisobjekte), da diese nicht auf die HSRCL-Liste sondern auf weitere Attribute des FeatureTypes SupplementaryRegulation selbst gemappt werden.
      Enumerationszuweisungen haben dabei in der automatischen Konvertierung Hoheit über die FeatureType-Zuordnung. Falls ein FeatureType multiple Enumerationen enthält, bestimmt eine festgelegte Hierarchie, welche Elemente verwendet werden sollen. 
      <div id="xplanenumerationtoinspire" style="display:none;">
      <p>
        <div id="planzeichen-container">
          <form method="Post" action="index.php">
            <input type="hidden" name="go" value="show_inspire">
          </form>
          <table
            class="table table-striped"
            data-toggle="table"
            data-url="index.php?go=get_inspireenumeration&<?php echo http_build_query($params); ?>"
            data-height="800"
            data-click-to-select="false"
            data-search="true"
            data-show-refresh="true"
            data-show-toggle="true"
            data-show-columns="false"
            data-query-params="queryParams"
            data-pagination="true"
            data-page-size="50"
            data-show-export="true"
            data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
            >
            <thead>
              <tr>
               <th
                  data-flat="true"
                  data-field="xplan_simpletype"
                  data-align="left"
                  data-sortable="true"
                  data-switchable="false"
                >XPlan-SimpleType</th>
                <th
                  data-flat="true"
                  data-field="xplan_enumeration"
                  data-align="left"
                  data-sortable="true"
                  data-switchable="false"
                >XPlan-Enumeration</th>
                <th
                  data-flat="true"
                  data-field="hsrcl"
                  data-align="left"
                  data-sortable="true"
                  data-switchable="false"
                >HSRCL</th>
              </tr>
            </thead>
          </table>
        </div>
      </p>
      </div>
     <p>
     <h3>INSPIRE HSRCL Übersetzung zum <a href="inspire/hsrcl_uebersetzung.xlsx">herunterladen</a></h3>
     Dieser im Laufe des Projekts herausgearbeitete Übersetzungsvorschlag wird den Ländern und der AG E-Government zur Besichtigung und zur Verbesserung bereitgestellt werden.
     </p>
     <p>
     <h3>INSPIRE HSRCL Nationale Codeliste zum <a href="inspire/2016-01-18_NationaleCodeliste.ods">herunterladen</a></h3>
     Die bereitgestellte INSPIRE HSRCL Nationale Codeliste bietet einen Vorschlag zur Abbildung der Nationalen Raumordnungselemente in XPlan. Diese Liste lehnt sich dabei streng an XPlan und soll bei einer Konvertierung automatisch für das Attribut SpecificSupplementaryRegulation des FeatureTypes SupplementaryRegulation befüllt werden. Hiermit können INSPIRE-Daten theoretisch auch teilweise nach XPlan rückkonvertiert werden. Davon ausgenommen sind Elemente wie etwa der Rechtscharakter eines Planzeichens, welche gesondert von den HSRCL-Listen im FeatureType SupplementaryRegulation festgehalten werden.
     </p>
      <h3>Dokument zu INSPIRE und XPlan Attributen, Pflichtelementen und Definitionen zum <a href="inspire/2015-10-13_INSPIRE_Pflichtelemente und XPlan-Formulare.xls">herunterladen</a></h3>
      Das Dokument beinhaltet Daten zu Attributen, Pflichtelementen und Definitionen des INSPIRE Planned Land Use Schemas. Gleichzeitig beschreibt es deren Äquivalenz zu XPlan-Attributen, was für das Mapping von XPlan nach INSPIRE von Bedeutung ist. Vorschläge zu Pflichtattributierungen von XPlan sind für die Länder zur Information bereitgestellt worden, damit diese diskutiert werden können.
     </p>
     </div>
  </div>
  <?php
  output_footer();
}     


function show_hilfe() {
	output_header(true); ?>
  <div id="main">
  	<div class="textsite">
      <center>
  		<h1>Hilfe</h1>
  		<form action ="index.php">
  		<input type="hidden"name="go" value="show_hilfe">
  		<h2>Häufig gestellte Fragen</h2>
      </center>
      <hr>
  		<h3>Was ist Xplanung?</h3>
  		<p>
  			XPlanung war ein E-Government Projekt, welches das objektorientiertes Datenaustauschformat XPlanGML entwickelte. XPlanGML gestattet den verlustfreien Austausch von Bauleitplänen, Regionalplänen und Landschaftsplänen zwischen unterschiedlichen IT-Systemen, unterstützt die Bereitstellung von Plänen online und ermöglicht die planübergreifende Auswertung und Visualisierung von Planinhalten.
        <br>
        Diese Standardisierung eröffnet hohe Potentiale, Verwaltungsvorgänge im Bereich der raumbezogenen Planung effektiver und kostengünstiger zu gestalten sowie qualitativ zu verbessern. Gleichzeitig eröffnen sich Möglichkeiten, planungsrelevante Daten auf kostengünstige Art und Weise der Wirtschaft (z.B. für regionale Wirtschaftsförderung oder Standortmarketing), anderen Fachbehörden und Trägern öffentlicher Belange sowie der Öffentlichkeit (Bürgerbeteiligung) zur Verfügung zu stellen.
  			<br>
  			XPlanGML ist ein offenes, XML-basiertes Datenaustauschformat, das auf Geography Markup Language Version 3 (GML3) aufbaut, dem erweiterbaren Standard für raumbezogene Daten, entwickelt vom Open Geospatial Consortium (OGC) und dem ISO TC211. XPlanGML orientiert sich technisch am ALKIS/NAS Standard der Arbeitsgemeinschaft der Vermessungsverwaltungen Deutschland (AdV).
  			<br>
  			Quelle:<a href="http://www.xplanungwiki.de/" class=hlink>xplanungwiki</a>
  		</p>
  		<h3>Was ist das Ziel des MORO Projekts?</h3>
  		<p>
  			Das Modellvorhaben der Raumordnung: "Entwicklung und Implementierung eines Standards für den Datenaustausch in der Raumordnung" hat das Ziel, das Raumordnungsschema des XPlan-Datenmodells weiterzuentwickeln um einen effektiven Austausch von Geodaten der Raumordnung zu ermöglichen. Hierbei soll das Modell selbst unter Einbezug und Mitarbeit der Länder verbessert werden und ein Konverter entwickelt werden, der Shapefiles nach XPlanGML und XPlanGML-Daten nach INSPIRE konvertiert. 
  			<br>
  			Quelle: <a href="http://www.bbsr.bund.de/BBSR/DE/FP/MORO/Studien/2014/XPlanung/03_Ergebnisse.html?nn=406988" class=hlink>BBSR</a>
  		</p>
		
  		<h3>Wozu dienen die Liste der Planzeichen und Pläne?</h3>
  		<p>
        Die aus dem Raumordnungsplanmonitor (ROPLAMO) entnommenen Listen der Planzeichen und Pläne sind einerseits Quellen für die Verbesserung des Modells und dienen andererseits Dokumentationszwecken. Anhand der Einträge des ROPLAMO für Planzeichen und Pläne kann überprüft werden, ob das derzeitige Modell alle Daten der Raumordnungsplanung wiedergeben kann. Hierzu wurde jedem Eintrag konzeptionell ein FeatureType und, falls vorhanden, eine Enumeration zugeordnet. Diese können bei einer Konvertierung Hilfestellung zur Zuordnung von Planzeichen auf das XPlan-Modell liefern und erlauben den Vergleich von Daten mit anderen Regionen oder Ländern. Gleichfalls können Kommentare bezüglich Planzeichen oder Zuordnungen geschrieben werden, um sicherzugehen ob eine Kategorisierung zutrifft. Hierbei ist wie folgt vorzugehen:
        <br>
  			<br>
  			1. Klicken Sie auf den Header Planzeichen. Dieses bringt Sie zur Darstellung der Planzeichen des ROPLAMO.
  			<br>
  			2. Wählen Sie Ihr Land aus der Spalte ID aus und klicken Sie auf "Aktualisieren" um nur die Planzeichen Ihres Landes anzuzeigen. Beispielhaft wird hierfür Thüringen ausgewählt.
  			<br>
  			3. Wählen Sie Ihren Plan aus der Spalte Plan-ID und klicken Sie auf "Aktualisieren" um nur die Planzeichen Ihres Landes anzuzeigen. Beispielhaft wird hierfür Thüringen ausgewählt.
  			<br>
  			4. Die nun angezeigten Planzeichen enthalten alle im ROPLAMO erfassten Planzeichen dieses Plans, welche, soweit erfasst, den  Namen, die Gruppe, Untergruppe, Anmerkungen, den Rechtlicher Status, die Räumliche Konkretheit und den Gebietstyp des Plans enthalten. So enthält der  Plan  TH-LEPL-072014 zum Beispiel das Planzeichen Autobahn oder das Planzeichen Industriegroßfläche. Überprüfen Sie anhand Ihrer Pläne für jeden Plan ob diese Daten vollständig aufgenommen sind.
  			<br>
  			5. Rechts in der Tabelle erkennen Sie gleichfalls die Spalten "Vorschlag XPlan Paket Entsprechung", "Vorschlag XPlan Featuretype Entsprechung" und "Vorschlag XPlan Enumerations Entsprechung". Diese Spalten beinhalten die von GDI-Service Rostock vorgenommenen XPlan-Kategorisierungsvorschläge. So ist zum Beispiel das Planzeichen Autobahn im Plan TH-LEPL-072014 in das Paket RP_Infrastruktur, den Featuretype RP_Strassenverkehrr und die Enumeration Autobahn = 1002 in RP_StrassenverkehrTypen kategorisiert. Nehmen Sie zum Überprüfen dieser Kategorisierung das UML-Modell Infrastruktur, die Dokumentation der Elemente und Codelisten oder die <a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/Objektartenkatalog_XPlanGML%204.1.html" class=hlink>XPlan Dokumentation des Xplanung-Wikis</a> zu Hilfe.Falls Sie mit der vorgeschlagenen Kategorisierung nicht einverstanden sind, eine bessere Kategorie finden,  Kommentare oder Fragen haben, tragen Sie diese bitte im dazugehörigen Kommentarfeld ein. Dieses öffnen Sie durch Klicken auf die jeweilige ID des zu kommentierenden Planzeichens. Featuretypen und Enumerations können gegebenenfalls ergänzt werden, um eine komplette Abdeckung der Planzeichen zu gewährleisten. Hierzu ist noch anzumerken, dass Planzeichen multiplen Enumerations zugeordnet werden können. So könnte eine Autobahn gleichfalls vierstreifig = 1001 in RP_BesondereStrassenverkehrTypen sein. Hierbei wird bei der vorgenommenen Klassifizierung vorerst nur eine Klassifikation angezeigt.
  			<br><br>
  			Die vorgenommene Kategorisierung ist hierbei konzeptionell und benötigt keine Veränderung Ihrer Daten, erlaubt Ihnen jedoch den Vergleich und Austausch Ihrer Daten mit anderen Regionen, Ländern und, durch die geplante INSPIRE Konformität, innerhalb der Europäischen Union. Falls Ihre Daten jedoch nicht alle von XPlanung und INSPIRE erforderlichen Informationen enthalten, ergibt sich ein weiterer optionaler Aufwand, diese Daten vor der Konvertierung zu ergänzen und anzupassen.
  		</p>
      <center>
  		<h2>Kontakt</h2>
      <hr>
      </center>
  		<p>
  			GDI-Service Rostock
  			<br>
  			Dr.-Ing. Peter Korduan
  			<br>
  			Joachim-Jungius-Straße 9
  			<br>
  			18059 Rostock
  			<br>
  			im Rostocker Innovations- und Gründerzentrum (RIGZ)
  			<br>
  			Haus 2, Erdgeschoss, Raum 65
  			<br>
  			Tel: 0381 / 4034444 4
  			<br>
  			Fax: 032 / 223381 799
  			<br>
  			<a href="mailto:info@gdi-service.de" class=hlink>info@gdi-service.de</a>	
  		</p>
  		<center>
      <h2>Links</h2>
  		<hr>
      </center>
      <p>
      <h3>Allgemein</h3>
        <a href="http://www.bbsr.bund.de/BBSR/DE/FP/MORO/Studien/2014/XPlanung/01_Start.html" class=hlink>BBSR MORO: Entwicklung und Implementierung eines Standards für den Datenaustausch in der Raumordnungsplanung<a/>
          <br>
          <a href="http://www.gdi-service.de/" class=hlink>GDI-Service Rostock</a>
          <br>
        <h3>X-Plan</h3>
          <a href="http://www.iai.fzk.de/www-extern/index.php?id=679" class=hlink>XPlanung Homepage des KIT</a>
          <br>
          <a href="http://www.xplanungwiki.de/" class=hlink>XPlanung Wiki</a>
          </br>
          <a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_Regionalplan_Kernmodell.html" class=hlink>Raumordnungsplan_Kernmodell (XPlanGML 4.1)</a>
          </br>
          <a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_RP__Basisobjekte.html" class=hlink>RP_Basisobjekte (XPlanGML 4.1)</a>
          </br>
        <h3>INSPIRE</h3>
          <a href="http://inspire.ec.europa.eu/documents/Data_Specifications/INSPIRE_DataSpecification_LU_v3.0rc3.pdf" class=hlink>INSPIRE Data Specificationon Land Use - Draft Technical Guidelines</a>
          <br>
          <a href="http://inspire.ec.europa.eu/codelist/HILUCSValue/" class=hlink>Codeliste land use categories des Hierarchical INSPIRE Land Use Classifiation System (HILUCS)</a>
          <br>
        <h3>Beispielpläne</h3>
          <a href="http://www.gera.ihk.de/Standortpolitik/Raumordnung/LEP_Thueringen/331612" class=hlink>Landesentwicklungsprogramm Thüringen 2025 (Beispiel)</a>
          <br>
          <a href="http://www.regionalplanung.thueringen.de/rpg/ost/regionalplan/rrop/index.asp" class=hlink>Regionalplan Ostthüringen (Beispiel)</a>
  			<br>
  		</p>
  	</div>
  </div><?php
  output_footer();
}

function show_ontologie() {
  header ("Location: http://xplan-raumordnung.de/iqvoc/de.html");
}

function show_uml() {
  output_header(true); ?>
  <div id="main">
    <!--  Javascript für Show und Hide () -->
    <script type="text/javascript" language="JavaScript">
    function HideContent(d) {
    document.getElementById(d).style.display = "none";
    }
    function ShowContent(d) {
    document.getElementById(d).style.display = "block";
    }
    function ReverseDisplay(d) {
    if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
    else { document.getElementById(d).style.display = "none"; }
    }
    </script>
  
    <map name ="RP_Basisobjekte1">
      <area shape="rect" coords="19,69,342,427" href="index.php?go=show_elements&package=Alle#xplan:XP_Plan" alt="XP_Plan" title="XP_Plan: Abstrakte Oberklasse für alle Klassen von raumbezogenen Plänen.">
      <area shape="rect" coords="49,479,293,579" href="index.php?go=show_elements&package=Alle#xplan:XP_Textabschnitt" alt="XP_Textabschnitt" title="XP_Textabschnitt: Ein Abschnitt der textlich formulierten Inhalte des Plans.">
      <area shape="rect" coords="46,612,304,736" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
      <area shape="rect" coords="412,63,665,382" href="index.php?go=show_elements#xplan:RP_Plan" alt="RP_Plan" title="RP_Plan: Die Klasse modelliert einen Raumordnungsplan.">
      <area shape="rect" coords="332,481,538,541" href="index.php?go=show_elements#xplan:RP_Textabschnitt" alt="RP_Textabschnitt" title="RP_Textabschnitt: Texlich formulierter Inhalt eines Raumordnungsplans, der einen anderen Rechtscharakter als das zugrunde liegende Fachobjekt hat (Attribut rechtscharakter des Fachobjektes), oder dem Plan als Ganzes zugeordnet ist.">
      <area shape="rect" coords="455,586,686,698" href="index.php?go=show_elements#xplan:RP_Bereich" alt="RP_Bereich" title="RP_Bereich: Die Klasse modelliert einen Bereich eines Raumordnungsplans.">
      <area shape="rect" coords="461,721,700,884" href="index.php?go=show_simple_types#xplan:RP_Art" alt="RP_Art" title="RP_Art: Art des Raumordnungsplans.">
      <area shape="rect" coords="76,962,225,998" href="index.php?go=show_simple_types#xplan:RP_Status" alt="RP_Status" title="RP_Status: Status des Plans, definiert über eine CodeList.">
      <area shape="rect" coords="71,1027,204,1067" href="index.php?go=show_simple_types#xplan:RP_SonstPlanArt" alt="SonstPlanArt" title="RP_SonstPlanArt: Spezifikation einer weiteren Planart (CodeList) bei planArt == 9999.">
      <area shape="rect" coords="285,775,,416,1073" href="index.php?go=show_simple_types#xplan:XP_Bundeslaender" alt="XP_Bundeslaender" title="XP_Bundeslaender: Zuständige Bundesländer.">
      <area shape="rect" coords="602,400,779,508" href="index.php?go=show_simple_types#xplan:RP_Verfahren" alt="RP_Verfahren" title="RP_Verfahren: Typ des Planverfahrens.">
      <area shape="rect" coords="58,754,267,942" href="index.php?go=show_simple_types#xplan:RP_Rechtsstand" alt="RP_Rechtsstand" title="RP_Rechtsstand: Rechtsstand des Plans.">
      <area shape="rect" coords="467,908,717,1071" href="index.php?go=show_simple_types#xplan:RP_Rechtscharakter" alt="RP_Rechtscharakter" title="RP_Rechtscharakter: Rechtscharakter des textlich formulierten Planinhalts.">
    </map>
    
    <map name="RP_Basisobjekte2">
      <area shape="rect" coords="210,323,512,538" href="index.php?go=show_elements&package=Alle#xplan:XP_Objekt" alt="XP_Objekt" title="XP_Objekt: Abstrakte Oberklasse für alle XPlanGML-Fachobjekte. Die Attribute dieser Klasse werden über den Vererbungs-Mechanismus an alle Fachobjekte weitergegeben.">
      <area shape="rect" coords="5,170,222,283" href="index.php?go=show_elements&package=Alle#xplan:XP_AbstraktesPraesentationsobjekt" alt="XP_AbstraktesPraesentationsobjekt" title="XP_AbstraktesPraesentationsobjekt: Abstrakte Basisklasse für alle Präsentationsobjekte. Die Attribute entsprechen dem ALKIS-Objekt AP_GPO, wobei das Attribut signaturnummer in stylesheetId umbenannt wurde. Bei freien Präsentationsobjekten ist die Relation dientZurDarstellungVon unbelegt, bei gebundenen Präsentationsobjekten zeigt die Relation auf ein von XP_Objekt abgeleitetes Fachobjekt. 
          Freie Präsentationsobjekte dürfen ausschließlich zur graphischen Annotation eines Plans verwendet werden 
          Gebundene Präsentationsobjekte mit Raumbezug dienen ausschließlich dazu, Attributwerte des verbundenen Fachobjekts im Plan darzustellen. Die Namen der darzustellenden Fachobjekt-Attribute werden über das Attribut art spezifiziert.">
      <area shape="rect" coords="537,446,765,558" href="index.php?go=show_elements#xplan:RP_Objekt" alt="RP_Objekt" title="RP_Objekt: Basisklasse für alle spezifischen Festlegungen eines Raumordnungsplans.">
      <area shape="rect" coords="536,656,769,729" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Raumordnungsplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">    
      <area shape="rect" coords="10,599,233,687" href="index.php?go=show_elements#xplan:RP_Praesentationsobjekt" alt="RP_Praesentationsobjekt" title="RP_Praesentationsobjekt: Objekt enthält Daten zur Legende im Ursprungsplan.">  
      <area shape="rect" coords="258,601,502,700" href="index.php?go=show_elements&package=Alle#xplan:XP_Textabschnitt" alt="XP_Textabschnitt" title="XP_Textabschnitt: Ein Abschnitt der textlich formulierten Inhalte des Plans.">
      <area shape="rect" coords="249,33,508,160" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
      <area shape="rect" coords="274,760,484,821" href="index.php?go=show_elements#xplan:RP_Textabschnitt" alt="RP_Textabschnitt" title="RP_Textabschnitt: Texlich formulierter Inhalt eines Raumordnungsplans, der einen anderen Rechtscharakter als das zugrunde liegende Fachobjekt hat (Attribut rechtscharakter des Fachobjektes), oder dem Plan als Ganzes zugeordnet ist.">
      <area shape="rect" coords="542,45,767,151" href="index.php?go=show_elements#xplan:RP_Bereich" alt="RP_Bereich" title="RP_Bereich: Die Klasse modelliert einen Bereich eines Raumordnungsplans.">
      <area shape="rect" coords="43,894,245,1110" href="index.php?go=show_simple_types#xplan:RP_GebietsTyp" alt="RP_GebietsTyp" title="RP_GebietsTyp: Klassifikation des Gebietes nach Bundesraumordnungsgesetz.">
      <area shape="rect" coords="558,942,738,1106" href="index.php?go=show_simple_types#xplan:RP_Bedeutsamkeit" alt="RP_Bedeutsamkeit" title ="RP_Bedeutsamkeit: Klassifikation der Bedeutsamkeit eines Objekts.">
      <area shape="rect" coords="307,862,438,900" href="index.php?go=show_simple_types#xplan:RP_FeatureTypeListe" alt="RP_FeatureTypeListe" title ="RP_FeatureTypeListe: -.">
      <area shape="rect" coords="267,945,514,1109" href="index.php?go=show_simple_types#xplan:RP_Rechtscharakter" alt="RP_Rechtscharakter" title="RP_Rechtscharakter: Rechtscharakter des textlich formulierten Planinhalts.">
    </map>  

    <map name="RP_Freiraumstruktur1">
      <area shape="rect" coords="201,14,426,103" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
      <area shape="rect" coords="196,139,436,210" href="index.php?go=show_elements#xplan:RP_Freiraum" alt="RP_Freiraum" title="RP_Freiraum: Freiraum">
      <area shape="rect" coords="30,224,226,286" href="index.php?go=show_elements#xplan:RP_Bodenschutz" alt="RP_Bodenschutz" title="RP_Bodenschutz: Bodenschutz">
      <area shape="rect" coords="36,303,200,364" href="index.php?go=show_elements#xplan:RP_GruenzugGruenzaesur" alt="RP_GruenzugGruenzaesur" title="RP_GruenzugGruenzaesur: Regionaler Grünzug/Grünzäsur">
      <area shape="rect" coords="38,386,257,444" href="index.php?go=show_elements#xplan:RP_Hochwasserschutz" alt="RP_Hochwasserschutz" title="RP_Hochwasserschutz: Hochwasserschutz und Vorbeugender Hochwasserschutz">
      <area shape="rect" coords="41,466,254,523" href="index.php?go=show_elements#xplan:RP_NaturLandschaft" alt="RP_NaturLandschaft" title="RP_NaturLandschaft: Natur und Landschaft">
      <area shape="rect" coords="401,226,682,301" href="index.php?go=show_elements#xplan:RP_NaturschutzrechtlichesSchutzgebiet" alt="RP_NaturschutzrechtlichesSchutzgebiet" title="RP_NaturschutzrechtlichesSchutzgebiet: Schutzgebiet nach Bundes-Naturschutzgesetz">
      <area shape="rect" coords="401,309,598,383" href="index.php?go=show_elements#xplan:RP_Wasserschutz" alt="RP_Wasserschutz" title="RP_Wasserschutz: Grund- und Oberflächenwasserschutz">
      <area shape="rect" coords="390,396,604,455" href="index.php?go=show_elements#xplan:RP_Gewaesser" alt="RP_Gewaesser" title="RP_Gewaesser: Gewässer">
      <area shape="rect" coords="396,473,555,532" href="index.php?go=show_elements#xplan:RP_Klimaschutz" alt="RP_Klimaschutz" title="RP_Klimaschutz: (Siedlungs-) Klimaschutz">
      <area shape="rect" coords="232,639,492,737" href="index.php?go=show_simple_types#xplan:RP_BodenschutzTypen" alt="RP_BodenschutzTypen" title="BodenschutzTypen: Typ des Bodenschutzes">
      <area shape="rect" coords="325,821,483,908" href="index.php?go=show_simple_types#xplan:RP_ZaesurTypen" alt="RP_ZaesurTypen" title="RP_ZaesurTypen: Typ der Zäsur">
      <area shape="rect" coords="322,1023,482,1108" href="index.php?go=show_simple_types#xplan:RP_LuftTypen" alt="RP_LuftTypen" title="RP_LuftTypen: Typ des Klimas">
      <area shape="rect" coords="500,577,777,806" href="index.php?go=show_simple_types#xplan:XP_KlassifizSchutzgebietNaturschutzrecht" alt="XP_KlassifizSchutzgebietNaturschutzrecht" title="XP_KlassifizSchutzgebietNaturschutzrecht: Klassifikation des Naturschutzgebietes.">
      <area shape="rect" coords="321,924,459,1009" href="index.php?go=show_simple_types#xplan:RP_WasserschutzZone" alt="RP_WasserschutzZone" title="RP_WasserschutzZone: Wasserschutzzone">  
      <area shape="rect" coords="28,555,220,744" href="index.php?go=show_simple_types#xplan:RP_WasserschutzTypen" alt="RP_WasserschutzTypen" title="RP_WasserschutzTypen: Typ des Wasserschutzes">
      <area shape="rect" coords="505,832,776,1112" href="index.php?go=show_simple_types#xplan:RP_HochwasserschutzTypen" alt="RP_HochwasserschutzTypen" title="RP_HochwasserschutzTypen: Typ des vorbeugenden Hochwasserschutzes">
      <area shape="rect" coords="13,756,305,1114" href="index.php?go=show_simple_types#xplan:RP_NaturLandschaftTypen" alt="RP_NaturLandschaftTypen" title="RP_NaturLandschaftTypen: Typ des Naturschutzes oder Landschaftsschutzes">
      
    <map name="RP_Freiraumstruktur2">
      <area shape="rect" coords="296,6,517,95" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
      <area shape="rect" coords="294,159,535,231" href="index.php?go=show_elements#xplan:RP_Freiraum" alt="RP_Freiraum" title="RP_Freiraum: Freiraum">
      <area shape="rect" coords="36,265,379,351" href="index.php?go=show_elements#xplan:RP_Erholung" alt="RP_Erholung" title="RP_Erholung: Erholung">
      <area shape="rect" coords="42,375,272,435" href="index.php?go=show_elements#xplan:RP_ErneuerbareEnergie" alt="RP_ErneuerbareEnergie" title="RP_ErneuerbareEnergie: Nutzung Erneuerbarer Energien">
      <area shape="rect" coords="39,454,239,514" href="index.php?go=show_elements#xplan:RP_Forstwirtschaft" alt="RP_Forstwirtschaft" title="RP_Forstwirtschaft: Forstwirtschaft">
      <area shape="rect" coords="40,534,259,594" href="index.php?go=show_elements#xplan:RP_KulturLandschaft" alt="RP_KulturLandschaft" title="RP_KulturLandschaft: KulturLandschaft">
      <area shape="rect" coords="488,281,693,339" href="index.php?go=show_elements#xplan:RP_Landwirtschaft" alt="RP_Landwirtschaft" title="RP_Landwirtschaft: Landwirtschaft">
      <area shape="rect" coords="494,359,726,420" href="index.php?go=show_elements#xplan:RP_RadwegWanderweg" alt="RP_RadwegWanderweg" title="RP_RadwegWanderweg: Radwege und Wanderwege">  
      <area shape="rect" coords="495,452,691,512" href="index.php?go=show_elements#xplan:RP_Sportanlage" alt="RP_Sportanlage" title="RP_Sportanlage: Sportanlage">
      <area shape="rect" coords="496,542,662,577" href="index.php?go=show_elements#xplan:RP_SonstigerFreiraumschutz" alt="RP_SonstigerFreiraumschutz" title="RP_SonstigerFreiraumschutz: Sonstiger Freiraumschutz">
      <area shape="rect" coords="46,984,258,1095" href="index.php?go=show_simple_types#xplan:RP_ErneuerbareEnergieTypen" alt="RP_ErneuerbareEnergieTypen" title="RP_ErneuerbareEnergieTypen: Typ der Erneuerbaren Energie">
      <area shape="rect" coords="586,738,764,889" href="index.php?go=show_simple_types#xplan:RP_SportanlageTypen" alt="RP_SportanlageTypen" title="RP_SportanlageTypen: Typ der Sportanlage">
      <area shape="rect" coords="47,764,215,851" href="index.php?go=show_simple_types#xplan:RP_TourismusTypen" alt="RP_TourismusTypen" title="RP_TourismusTypen: Typ des Tourismus">
      <area shape="rect" coords="47,869,271,954" href="index.php?go=show_simple_types#xplan:RP_BesondereTourismusErholungTypen" alt="RP_BesondereTourismusErholungTypen" title="RP_BesondereTourismusErholungTypen: BesondereTypen von Tourismus und/oder Erholung">
      <area shape="rect" coords="401,617,580,754" href="index.php?go=show_simple_types#xplan:RP_RadwegWanderwegTypen" alt="RP_RadwegWanderwegTypen" title="RP_RadwegWanderwegTypen: Typ des Radweges oder Wanderweges">
      <area shape="rect" coords="37,617,393,752" href="index.php?go=show_simple_types#xplan:RP_ErholungTypen" alt="RP_ErholungTypen" title="RP_ErholungTypen: Typ der Erholung">
      <area shape="rect" coords="299,1000,521,1098" href="index.php?go=show_simple_types#xplan:RP_KulturlandschaftTypen" alt="RP_KulturlandschaftTypen" title="RP_Kulturlandschaft: Klassifikation der Kulturlandschaft.">
      <area shape="rect" coords="542,920,773,1098" href="index.php?go=show_simple_types#xplan:RP_LandwirtschaftTypen" alt="RP_LandwirtschaftTypen" title="RP_LandwirtschaftTypen: Typ der Landwirtschaft">
      <area shape="rect" coords="287,767,533,983" href="index.php?go=show_simple_types#xplan:RP_ForstwirtschaftTypen" alt="RP_ForstwirtschaftTypen" title="RP_ForstwirtschaftTypen: Typ der Forstwirtschaft">
    </map>
  
    <map name="RP_Freiraumstruktur3">
      <area shape="rect" coords="285,33,508,123" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
      <area shape="rect" coords="282,171,518,241" href="index.php?go=show_elements#xplan:RP_Freiraum" alt="RP_Freiraum" title="RP_Freiraum: Freiraum">
      <area shape="rect" coords="251,293,557,429" href="index.php?go=show_elements#xplan:RP_Rohstoff" alt="RP_Rohstoff" title="RP_Rohstoff: Rohstoffsicherung">
      <area shape="rect" coords="577,15,771,841" href="index.php?go=show_simple_types#xplan:RP_RohstoffTypen" alt="RP_RohstoffTypen" title="RP_RohstoffTypen: Abgebauter Rohstoff.">
      <area shape="rect" coords="314,507,476,580" href="index.php?go=show_simple_types#xplan:RP_BodenschatzTiefe" alt="RP_BodenschatzTiefe" title="RP_BodenschatzTiefe: Tiefe des Bodenschatzvorkommens">
      <area shape="rect" coords="31,657,238,834" href="index.php?go=show_simple_types#xplan:RP_BergbauFolgenutzung" alt="RP_BergbauFolgenutzung" title="RP_BergbauFolgenutzung: Folgenutzung von Bergbau">
      <area shape="rect" coords="282,644,514,836" href="index.php?go=show_simple_types#xplan:RP_BergbauplanungTypen" alt="RP_BergbauplanungTypen" title="RP_BergbauplanungTypen: Typen der Bergbauplanung.">
      <area shape="rect" coords="63,503,188,576" href="index.php?go=show_simple_types#xplan:RP_Zeitstufe" alt="RP_Zeitstufe" title="RP_Zeitstufe>: Zeitstufe des Tagebaus">
    </map>

    <map name="RP_Infrastruktur1">
      <area shape="rect" coords="266,13,485,101" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
      <area shape="rect" coords="12,151,294,236" href="index.php?go=show_elements#xplan:RP_Energieversorgung" alt="RP_Energieversorgung" title="RP_Energieversorgung: Infrastruktur zur Energieversorgung">
      <area shape="rect" coords="32,250,257,335" href="index.php?go=show_elements#xplan:RP_Entsorgung" alt="RP_Entsorgung" title="RP_Entsorgung: Entsorgungs-Infrastruktur">
      <area shape="rect" coords="65,356,278,414" href="index.php?go=show_elements#xplan:RP_Kommunikation" alt="RP_Kommunikation" title="RP_Kommunikation: Infrastruktur zur Telekommunikation">
      <area shape="rect" coords="432,165,632,226" href="index.php?go=show_elements#xplan:RP_LaermschutzBauschutz" alt="RP_LaermschutzBauschutz" title="RP_LaermschutzBauschutz: Infrastruktur zum Lärmschutz und Bauschutz.">
      <area shape="rect" coords="428,348,643,406" href="index.php?go=show_elements#xplan:RP_Wasserwirtschaft" alt="RP_Wasserwirtschaft" title="RP_Wasserwirtschaft: Wasserwirtschaft">
      <area shape="rect" coords="434,265,664,326" href="index.php?go=show_elements#xplan:RP_SozialeInfrastruktur" alt="RP_SozialeInfrastruktur" title="RP_SozialeInfrastruktur: Soziale Infrastruktur">
      <area shape="rect" coords="272,454,460,492" href="index.php?go=show_elements#xplan:RP_SonstigeInfrastruktur" alt="RP_SonstigeInfrastruktur" title="RP_SonstigeInfrastruktur: Sonstige Infrastruktur">    
      <area shape="rect" coords="554,537,783,676" href="index.php?go=show_simple_types#xplan:RP_LaermschutzTypen" alt="RP_LaermschutzTypen" title="RP_LaermschutzTypen: Typen des Lärmschutzes">
      <area shape="rect" coords="571,718,775,869" href="index.php?go=show_simple_types#xplan:RP_WasserwirtschaftTypen" alt="RP_WasserwirtschaftTypen" title="RP_WasserwirtschaftTypen: Klassifikation von Anlagen und Einrichtungen der Wasserwirtschaft">
      <area shape="rect" coords="584,914,790,1065" href="index.php?go=show_simple_types#xplan:RP_SozialeInfrastrukturTypen" alt="RP_SozialeInfrastrukturTypen" title="RP_SozialeInfrastrukturTypen: Typ der Sozialen Infrastruktur">
      <area shape="rect" coords="32,442,236,685" href="index.php?go=show_simple_types#xplan:RP_EnergieversorgungTypen" alt="RP_EnergieversorgungTypen" title="RP_EnergieversorgungTypen: Typ der Energieversorgung">
      <area shape="rect" coords="324,684,512,888" href="index.php?go=show_simple_types#xplan:RP_PrimaerenergieTypen" alt="RP_PrimaerenergieTypen" title="RP_PrimaerenergieTypen: Typ der Primärenergie">
      <area shape="rect" coords="458,929,573,1027" href="index.php?go=show_simple_types#xplan:RP_SpannungTypen" alt="RP_SpannungTypen" title="RP_SpannungTypen: Typ der Spannung">
      <area shape="rect" coords="319,530,507,642" href="index.php?go=show_simple_types#xplan:RP_KommunikationTyp" alt="RP_KommunikationTyp" title="KommunikationTyp: Typ der Kommunikationsinfrastruktur">
      <area shape="rect" coords="34,707,266,898" href="index.php?go=show_simple_types#xplan:RP_AbfallentsorgungTypen" alt="RP_AbfallentsorgungTypen" title="RP_AbfallentsorgungTypen: Typ der Abfallentsorgung">
      <area shape="rect" coords="218,926,441,1067" href="index.php?go=show_simple_types#xplan:RP_AbwasserTypen" alt="RP_AbwasserTypen" title="RP_AbwasserTypen: Typ der Abwasserinfrastruktur">
      <area shape="rect" coords="36,936,202,1061" href="index.php?go=show_simple_types#xplan:RP_AbfallTypen" alt="RP_AbfallTypen" title="RP_AbfallTypen: Klassifikation von Abfalltypen.">    
 </map> 
 
  <map name="RP_Infrastruktur2">
      <area shape="rect" coords="281,17,500,105" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
      <area shape="rect" coords="276,131,494,218" href="index.php?go=show_elements#xplan:RP_Verkehr" alt="RP_Verkehr" title="RP_Verkehr: Verkehrs-Infrastruktur">
      <area shape="rect" coords="20,238,351,309" href="index.php?go=show_elements#xplan:RP_Strassenverkehr" alt="RP_Strassenverkehr" title="RP_Strassenverkehr: Strassenverkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
      <area shape="rect" coords="447,235,781,310" href="index.php?go=show_elements#xplan:RP_Schienenverkehr" alt="RP_Schienenverkehr" title="RP_Schienenverkehr: Schienenverkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
      <area shape="rect" coords="469,329,667,389" href="index.php?go=show_elements#xplan:RP_Wasserverkehr" alt="RP_Wasserverkehr" title="RP_Wasserverkehr: Wasserverkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
      <area shape="rect" coords="136,323,315,384" href="index.php?go=show_elements#xplan:RP_Luftverkehr" alt="RP_Luftverkehr" title="RP_Luftverkehr: Luftverkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
      <area shape="rect" coords="289,411,483,471" href="index.php?go=show_elements#xplan:RP_SonstVerkehr" alt="RP_SonstVerkehr" title="RP_SonstVerkehr: Sonstige Verkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
      <area shape="rect" coords="229,649,537,827" href="index.php?go=show_simple_types#xplan:VerkehrStatus" alt="RP_VerkehrStatus" title="RP_VerkehrStatus: Klassifikation des Verkehrsstatus">
      <area shape="rect" coords="564,637,777,841" href="index.php?go=show_simple_types#xplan:RP_SonstVerkehrTypen" alt="RP_SonstVerkehrTypen" title="RP_SonstVerkehrTypen: Sonstige Verkehrstypen">  
      <area shape="rect" coords="19,478,191,590" href="index.php?go=show_simple_types#xplan:RP_VerkehrTypen" alt="RP_VerkehrTypen" title="RP_VerkehrTypen: Klassifikation der Verkehrs-Arten.">  
      <area shape="rect" coords="570,409,765,625" href="index.php?go=show_simple_types#xplan:RP_WasserverkehrTypen" alt="RP_WasserverkehrTypen" title="RP_WasserverkehrTypen: Klassifikation des Wasserverkehrs">
      <area shape="rect" coords="15,612,204,840" href="index.php?go=show_simple_types#xplan:RP_StrassenverkehrTypen" alt="RP_StrassenverkehrTypen" title="RP_StrassenverkehrTypen: Klassifikation des Straßenverkehrs">
      <area shape="rect" coords="247,509,497,634" href="index.php?go=show_simple_types#xplan:RP_BesondereStrassenverkehrTypen" alt="RP_BesondereStrassenverkehrTypen" title="RP_BesondereStrassenverkehrTypen: Klassifikation des besonderen Straßenverkehrs">
      <area shape="rect" coords="11,856,241,1110" href="index.php?go=show_simple_types#xplan:RP_SchienenverkehrTypen" alt="RP_SchienenverkehrTypen" title="RP_SchienenverkehrTypen: Klassifikation des Schienenverkehrs">
      <area shape="rect" coords="250,871,553,1113" href="index.php?go=show_simple_types#xplan:RP_BesondereSchienenverkehrTypen" alt="RP_BesondereSchienenverkehrTypen" title="RP_BesondereSchienenverkehrTypen: Klassifikation von besonderen Schienenverkehrtypen">
      <area shape="rect" coords="561,851,787,1118" href="index.php?go=show_simple_types#xplan:RP_LuftverkehrTypen" alt="RP_LuftverkehrTypen" title="RP_LuftverkehrTypen: Klassifikation des Luftverkehrs">
    </map>    

    <map name="RP_Siedlungsstruktur1">
      <area shape="rect" coords="237,14,452,103" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
      <area shape="rect" coords="14,139,328,211" href="index.php?go=show_elements#xplan:RP_Raumkategorie" alt="RP_Raumkategorie" title="RP_Raumkategorie: Raumkategorien">
      <area shape="rect" coords="115,237,282,297" href="index.php?go=show_elements#xplan:RP_Achse" alt="RP_Achse" title="RP_Achse: Siedlungsachse. Früher Linienobjekt, nun Geometrieobjekt">
      <area shape="rect" coords="402,141,589,199" href="index.php?go=show_elements#xplan:RP_Sperrgebiet" alt="RP_Sperrgebiet" title="RP_Sperrgebiet: Sperrgebiet">
      <area shape="rect" coords="401,228,675,299" href="index.php?go=show_elements#xplan:RP_ZentralerOrt" alt="RP_ZentralerOrt" title="RP_ZentralerOrt: Zentrale Orte">
      <area shape="rect" coords="385,318,591,389" href="index.php?go=show_elements#xplan:RP_Funktionszuweisung" alt="RP_Funktionszuweisung" title="RP_Funktionszuweisung: Funktionen von Gemeinden und Gebieten.">
      <area shape="rect" coords="34,363,310,736" href="index.php?go=show_simple_types#xplan:RP_RaumkategorieTypen" alt="RP_RaumkategorieTypen" title="RP_RaumkategorieTypen: Klassifikation von Raumkategorien">
      <area shape="rect" coords="52,783,259,868" href="index.php?go=show_simple_types#xplan:RP_BesondereRaumkategorieTypen" alt="RP_BesondereRaumkategorieTypen" title="RP_BesondereRaumkategorieTypen: Klassifikation von Besonderen Raumkategorien">    
       <area shape="rect" coords="357,426,557,576" href="index.php?go=show_simple_types#xplan:RP_SperrgebietTypen" alt="RP_SperrgebietTypen" title="RP_SperrgebietTypen: Klassifikation von Sperrgebieten">
      <area shape="rect" coords="339,615,555,870" href="index.php?go=show_simple_types#xplan:RP_ZentralerOrtTypen" alt="RP_ZentralerOrtTypen" title="RP_ZentralerOrtTypen: Klassifikation von Zentralen Orten">
      <area shape="rect" coords="569,616,783,884" href="index.php?go=show_simple_types#xplan:RP_ZentralerOrtSonstigeTypen" alt="RP_ZentralerOrtSonstigeTypen" title="RP_ZentralerOrtSonstigeTypen: Klassifikation von sonstigen Typen zentraler Orte">
      <area shape="rect" coords="450,912,776,1076" href="index.php?go=show_simple_types#xplan:RP_FunktionszuweisungTypen" alt="RP_FunktionszuweisungTypen" title="RP_FunktionszuweisungTypen">
      <area shape="rect" coords="53,906,324,1083" href="index.php?go=show_simple_types#xplan:RP_AchsenTypen" alt="RP_AchsenTypen" title="RP_AchsenTypen: Klassifikation von Achsentypen">
    </map>
   
    <map name="RP_Siedlungsstruktur2">
      <area shape="rect" coords="275,46,499,134" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
      <area shape="rect" coords="251,209,518,293" href="index.php?go=show_elements#xplan:RP_Siedlung" alt="RP_Siedlung" title="RP_Siedlung: Allgemeine Siedlungsstrukturen">
      <area shape="rect" coords="55,327,271,387" href="index.php?go=show_elements#xplan:RP_WohnenSiedlung" alt="WohnenSiedlung" title="RP_WohnenSiedlung: Objekte mit Bezug zu Wohnen und Siedlungen.">
      <area shape="rect" coords="107,457,305,516" href="index.php?go=show_elements#xplan:RP_Einzelhandel" alt="RP_Einzelhandel" title="RP_Einzelhandel: Einzelhandelsstruktur und -funktionen">
      <area shape="rect" coords="468,458,686,516" href="index.php?go=show_elements#xplan:RP_IndustrieGewerbe" alt="RP_IndustrieGewerbe" title="RP_IndustrieGewerbe: Industrie- und Gewerbestrukturen und -funktionen">
      <area shape="rect" coords="541,334,718,368" href="index.php?go=show_elements#xplan:RP_SonstigerSiedlungsbereich" alt="RP_SonstigerSiedlungsbereich" title="RP_SonstigerSiedlungsbereich: Sonstiger Siedlungsbereich">
      <area shape="rect" coords="42,650,248,787" href="index.php?go=show_simple_types#xplan:RP_WohnenSiedlungTypen" alt="RP_WohnenSiedlungTypen" title="RP_WohnenSiedlungTypen: Klassifikation von Wohntypen und Siedlungen">
      <area shape="rect" coords="314,856,560,1033" href="index.php?go=show_simple_types#xplan:RP_EinzelhandelTypen" alt="RP_EinzelhandelTypen" title="RP_EinzelhandelTypen: Klassifikation von Einzelhandel">
      <area shape="rect" coords="304,597,628,813" href="index.php?go=show_simple_types#xplan:RP_IndustrieGewerbeTypen" alt="RP_IndustriegewerbeTypen" title="RP_IndustrieGewerbeTypen: Klassifikation von Industrie oder Gewerbe">
    </map>
    
    <map name="RP_Sonstiges">
      <area shape="rect" coords="324,107,538,197" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
      <area shape="rect" coords="570,129,792,188" href="index.php?go=show_elements#xplan:RP_GenerischesObjekt" alt="RP_GenerischesObjekt" title="RP_GenerischesObjekt: Klasse zur Modellierung aller Inhalte des Regionalplans, die durch keine andere Klasse des RPlan-Fachschemas dargestellt werden können.">
      <area shape="rect" coords="10,118,296,203" href="index.php?go=show_elements#xplan:RP_Grenze" alt="RP_Grenze" title="RP_Grenze: Grenzen.">
      <area shape="rect" coords="267,247,574,306" href="index.php?go=show_elements#xplan:RP_Planungsraum" alt="RP_Planungsraum" title="RP_Planungsraum: Planungsraum.">
      <area shape="rect" coords="566,365,736,419" href="index.php?go=show_simple_types#xplan:RP_GenerischesObjektTypen" alt="RP_GenerischesObjektTypen" title="RP_GenerischesObjektTypen">
      <area shape="rect" coords="569,446,706,499" href="index.php?go=show_simple_types#xplan:RP_SonstGrenzeTypen" alt="RP_SonstGrenzeTypen" title="RP_SonstGrenzeTypen">
      <area shape="rect" coords="33,361,279,591" href="index.php?go=show_simple_types#xplan:XP_GrenzeTypen" alt="XP_GrenzeTypen" title="XP_GrenzeTypen: Typ der Grenze">
      <area shape="rect" coords="304,356,537,521" href="index.php?go=show_simple_types#xplan:RP_SpezifischeGrenzeTypen" alt="RP_SpezifischeGrenzeTypen" title="RP_SpezifischeGrenzeTypen: Spezifischer Typ der Grenze">
    </map>

    <map name="RP_Raster">
      <area shape="rect" coords="100,101,361,227" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
      <area shape="rect" coords="64,305,379,483" href="index.php?go=show_elements&package=Alle#xplan:XP_RasterplanAenderung" alt="XP_RasterplanAenderung" title="XP_RasterplanAenderung: Basisklasse für georeferenzierte Rasterdarstellungen von Änderungen des Basisplans, die nicht in die Rasterdarstellung XP_RasterplanBasis integriert sind.">
      <area shape="rect" coords="471,110,702,223" href="index.php?go=show_elements#xplan:RP_Bereich" alt="RP_Bereich" title="RP_Bereich: Die Klasse modelliert einen Bereich eines Raumordnungsplans">
      <area shape="rect" coords="92,578,339,741" href="index.php?go=show_elements#xplan:RP_RasterplanAenderung" alt="RP_RasterplanAenderung" title="RP_RasterplanAenderung">
    </map>
	 
    <map name="XP_Basisobjekte1">
      <area shape="rect" coords="29,69,338,427" href="index.php?go=show_elements&package=Alle#xplan:XP_Plan" alt="XP_Plan" title="XP_Plan: Abstrakte Oberklasse für alle Klassen von raumbezogenen Plänen.">
      <area shape="rect" coords="239,520,479,631" href="index.php?go=show_elements&package=Alle#xplan:XP_TextAbschnitt" alt="XP_TextAbschnitt" title="XP_TextAbschnitt: Ein Abschnitt der textlich formulierten Inhalte des Plans.">
      <area shape="rect" coords="19,530,214,628" href="index.php?go=show_elements&package=Alle#xplan:XP_Begruendungsabschnitt" alt="XP_Begruendungsabschnitt" title="XP_Begruendungsabschnitt">
      <area shape="rect" coords="409,70,555,181" href="index.php?go=show_elements&package=Alle#xplan:XP_Verfahrensmerkmal" alt="XP_Verfahrensmerkmal" title="XP_Verfahrensmerkmal: Vermerk eines am Planungsverfahrens beteiligten Akteurs.">
      <area shape="rect" coords="369,198,597,360" href="index.php?go=show_elements&package=Alle#xplan:XP_ExterneReferenz" alt="XP_ExterneReferenz" title="XP_ExterneReferenz: Verweis auf ein extern gespeichertes Dokument, einen extern gespeicherten, georeferenzierten Plan oder einen Datenbank-Eintrag. Einer der beiden Attribute 'referenzName' bzw. 'referenzURL' muss belegt ">
      <area shape="rect" coords="79,671,387,757" href="index.php?go=show_elements&package=Alle#xplan:XP_VerbundenerPlan" alt="XP_VerbundenerPlan" title="XP_VerbundenerPlan: Spezifikation eines anderen Plans, der mit dem Ausgangsplan verbunden ist und diesen ändert bzw. von ihm geändert wird.">	
      <area shape="rect" coords="569,801,699,880" href="index.php?go=show_elements&package=Alle#xplan:XP_StringAttribut" alt="XP_StringAttribut" title="XP_StringAttribut: Generisches Attribut vom Datentyp "CharacterString">		
      <area shape="rect" coords="330,800,467,860" href="index.php?go=show_elements&package=Alle#xplan:XP_GenerAttribut" alt="XP_GenerAttribut" title="XP_GenerAttribut: Abstrakte Basisklasse für Generische Attribute.">
      <area shape="rect" coords="108,798,211,870" href="index.php?go=show_elements&package=Alle#xplan:XP_DoubleAttribut" alt="XP_DoubleAttribut" title="XP_DoubleAttribut: Generisches Attribut vom Datentyp "Double".">	
      <area shape="rect" coords="581,898,680,979" href="index.php?go=show_elements&package=Alle#xplan:XP_DatumAttribut" alt="XP_DatumAttribut" title="XP_DatumAttribut: Generische Attribute vom Datentyp "Datum">	
      <area shape="rect" coords="108,888,211,969" href="index.php?go=show_elements&package=Alle#xplan:XP_IntegerAttribut" alt="XP_IntegerAttribut" title="XP_IntegerAttribut: Generische Attribute vom Datentyp "Integer".">
  		<area shape="rect" coords="339,990,441,1071" href="index.php?go=show_elements&package=Alle#xplan:XP_URLAttribut" alt="XP_URLAttribut" title="XP_URLAttribut: Generische Attribute vom Datentyp "URL"> 		
  		<area shape="rect" coords="631,220,761,299" href="index.php?go=show_simple_types#xplan:XP_ExterneReferenzArt" alt="XP_ExterneReferenzArt" title="XP_ExterneReferenzArt: Typisierung der referierten Dokumente">
  		<area shape="rect" coords="530,380,730,647" href="index.php?go=show_simple_types#xplan:XP_MimeTypes" alt="XP_MimeTypes" title="XP_MimeTypes: Mime-Type des referierten Dokumentes">
      <area shape="rect" coords="450,672,699,759" href="index.php?go=show_simple_types#xplan:XP_RechtscharakterPlanaenderung" alt="XP_RechtscharakterPlanaenderung" title="XP_RechtscharakterPlanaenderung: Rechtscharakter der Planänderung.">
      
    <map name="XP_Basisobjekte2">
      <area shape="rect" coords="251,278,541,508" href="index.php?go=show_elements&package=Alle#xplan:XP_Objekt" alt="XP_Objekt" title="XP_Objekt: Abstrakte Oberklasse für alle XPlanGML-Fachobjekte. Die Attribute dieser Klasse werden über den Vererbungs-Mechanismus an alle Fachobjekte weitergegeben.">
      <area shape="rect" coords="240,58,490,196" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
      <area shape="rect" coords="440,579,679,692" href="index.php?go=show_elements&package=Alle#xplan:XP_TextAbschnitt" alt="XP_TextAbschnitt" title="XP_TextAbschnitt: Ein Abschnitt der textlich formulierten Inhalte des Plans.">
      <area shape="rect" coords="188,579,384,679" href="index.php?go=show_elements&package=Alle#xplan:XP_Begruendungsabschnitt" alt="XP_Begruendungsabschnitt" title="XP_Begruendungsabschnitt">
  		<area shape="rect" coords="509,39,778,190" href="index.php?go=show_elements&package=Alle#xplan:XP_Hoehenangabe" alt="XP_Hoehenangabe" title="XP_Hoehenangabe: Spezifikation einer Angabe zur vertikalen Höhe oder zu einem Bereich vertikaler Höhen. Es ist möglich, spezifische Höhenangaben (z.B. die First- oder Traufhöhe eines Gebäudes) vorzugeben oder einzuschränken, oder den Gültigkeitsbereich eines Planinhalts auf eine bestimmte Höhe (hZwingend) bzw. einen Höhenbereich (hMin - hMax) zu beschränken, was vor allem bei der höhenabhängigen Festsetzung einer überbaubaren Grundstücksfläche (BP_UeberbaubareGrundstuecksflaeche), einer Baulinie (BP_Baulinie) oder einer Baugrenze (BP_Baugrenze) relevant ist. In diesem Fall bleibt das Attribut bezugspunkt unbelegt.">
  		<area shape="rect" coords="279,728,462,810" href="index.php?go=show_elements&package=Alle#xplan:XP_Plangeber" alt="XP_Plangeber" title="XP_Plangeber: Spezifikation der Institution, die für den Plan verantwortlich ist.">
  		<area shape="rect" coords="19,710,233,822" href="index.php?go=show_elements&package=Alle#xplan:XP_Gemeinde" alt="XP_Gemeinde" title="XP_Gemeinde: Spezifikation einer Gemeinde">
  		<area shape="rect" coords="479,723,768,820" href="index.php?go=show_elements&package=Alle#xplan:XP_SPEMassnahmenDaten" alt="XP_SPEMassnahmenDaten" title="XP_SPEMassnahmenDaten: Spezifikation der Attribute für einer Schutz-, Pflege- oder Entwicklungsmaßnahme.">
      <area shape="rect" coords="20,50,191,261" href="index.php?go=show_simple_types#xplan:XP_BedeutungenBereich" alt="XP_BedeutungenBereich" title="XP_BedeutungenBereich: Spezifikation der semantischen Bedeutung eines Bereiches.">
      <area shape="rect" coords="38,419,153,521" href="index.php?go=show_simple_types#xplan:XP_Rechtsstand" alt="XP_Rechtsstand" title="XP_Rechtsstand: Gibt an ob der Planinhalt bereits besteht, geplant ist, oder zukünftig wegfallen soll.">
      <area shape="rect" coords="570,209,756,319" href="index.php?go=show_simple_types#xplan:XP_ArtHoehenbezug" alt="XP_ArtHoehenbezug" title="XP_ArtHoehenbezug: Art des Höhenbezuges. ">
      <area shape="rect" coords="591,330,740,521" href="index.php?go=show_simple_types#xplan:XP_ArtHoehenbezugspunkt" alt="XP_ArtHoehenbezugspunkt" title="XP_ArtHoehenbezugspunkt: Bestimmung des Bezugspunktes der Höhenangaben. Wenn dies Attribut nicht belegt ist, soll die Höhenangabe als verikale Einschränkung des zugeordneten Planinhalts interpretiert werden. ">
      <area shape="rect" coords="571,841,769,1094" href="index.php?go=show_simple_types#xplan:XP_SPEMassnahmenTypen" alt="XP_SPEMassnahmenTypen" title="XP_SPEMassnahmenTypen: Klassifikation der Maßnahme">
  		<area shape="rect" coords="29,329,181,379" href="index.php?go=show_simple_types#xplan:XP_GesetzlicheGrundlage" alt="XP_GesetzlicheGrundlage" title="XP_GesetzlicheGrundlage: Angagbe der Gesetzlichen Grundlage des Planinhalts.">
    </map>  
    
    <map name ="XP_Praesentationsobjekte">
      <area shape="rect" coords="640,89,760,150" href="index.php?go=show_elements&package=Alle#xplan:XP_Objekt" alt="XP_Objekt" title="XP_Objekt: Abstrakte Oberklasse für alle XPlanGML-Fachobjekte. Die Attribute dieser Klasse werden über den Vererbungs-Mechanismus an alle Fachobjekte weitergegeben.">
      <area shape="rect" coords="260,79,390,150" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
      <area shape="rect" coords="31,158,152,218" href="index.php?go=show_simple_types#XP_StylesheetListe" alt="XP_StylesheetListe" title="XP_StylesheetListe:">
      <area shape="rect" coords="200,238,409,338" href="index.php?go=show_simple_types#XP_AbstraktesPraesentationsobjekt" alt="XP_AbstraktesPraesentationsobjekt" title="XP_AbstraktesPraesentationsobjekt:Abstrakte Basisklasse für alle Präsentationsobjekte. Die Attribute entsprechen dem ALKIS-Objekt AP_GPO, wobei das Attribut 'signaturnummer' in stylesheetId umbenannt wurde. Bei freien Präsentationsobjekten ist die Relation 'dientZurDarstellungVon' unbelegt, bei gebundenen Präsentationsobjekten zeigt die Relation auf ein von XP_Objekt abgeleitetes Fachobjekt.
      Freie Präsentationsobjekte dürfen ausschließlich zur graphischen Annotation eines Plans verwendet werden.
      Gebundene Präsentationsobjekte mit Raumbezug dienen ausschließlich dazu, Attributwerte des verbundenen Fachobjekts im Plan darzustellen. Die Namen der darzustellenden Fachobjekt-Attribute werden über das Attribut 'art' spezifiziert.">
      <area shape="rect" coords="29,389,163,440" href="index.php?go=show_simple_types#XP_Praesentationsobjekt" alt="XP_Praesentationsobjekt" title="XP_Praesentationsobjekt: Entspricht der ALKIS-Objektklasse AP_Darstellung mit dem Unterschied, dass auf das Attribut 'positionierungssregel' verzichtet wurde.  Die Klasse darf nur als gebundenes Präsentationsobjekt verwendet werden. Die Standard-Darstellung des verbundenen Fachobjekts wird dann durch die über stylesheetId spezifizierte Darstellung ersetzt. Die Umsetzung dieses Konzeptes ist der Implementierung überlassen.">
      <area shape="rect" coords="46,510,228,597" href="index.php?go=show_simple_types#XP_PPO" alt="XP_PPO" title="XP_PPO">
      <area shape="rect" coords="179,630,352,690" href="index.php?go=show_simple_types#XP_LPO" alt="XP_LPO" title="XP_LPO">
      <area shape="rect" coords="309,460,495,520" href="index.php?go=show_simple_types#XP_FPO" alt="XP_FPO" title="XP_FPO">
      <area shape="rect" coords="429,610,796,721" href="index.php?go=show_simple_types#XP_TPO" alt="XP_TPO" title="XP_TPO">
      <area shape="rect" coords="409,820,577,900" href="index.php?go=show_simple_types#XP_PTO" alt="XP_PTO" title="XP_PTO">
      <area shape="rect" coords="600,819,772,890" href="index.php?go=show_simple_types#XP_LTO" alt="XP_LTO" title="XP_LTO">
      <area shape="rect" coords="407,976,561,1049" href="index.php?go=show_simple_types#XP_Nutzungsschablone" alt="XP_Nutzungsschablone" title="XP_Nutzungsschablone">
      <area shape="rect" coords="18,728,162,819" href="index.php?go=show_simple_types#XP_HorizontaleAusrichtung" alt="XP_HorizontaleAusrichtung" title="XP_HorizontaleAusrichtung">
      <area shape="rect" coords="199,727,332,821" href="index.php?go=show_simple_types#XP_VertikaleAusrichtung" alt="XP_VertikaleAusrichtung" title="XP_VertikaleAusrichtung">
    </map>
  
    <map name="INSPIRE">
      <area shape="rect" coords="220,86,473,336" alt="INSPIRE_SpatialPlan" title="SpatialPlan: Modelliert einen Plan. Definition: A set of documents that indicates a strategic direction for the development of a given geogrpahic area, states the policies, priorities, programmes and land allocations that will implement the strategic direction and influences the distribution of people and activities in space of various scales. Spatial plans may be developed for urban planning, regional planning, environmental planning, landscape planning, national spatial plans, or spatial planning at the Union level.">
      <area shape="rect" coords="586,90,897,352" alt="INSPIRE_ZoningElement" title="ZoningElement: Modelliert Flächenschlussobjekte. Definition: A spatial object which is homogeneous regarding the permitted uses of land based on zoning which separate one set of land uses from another. Description: Zoning elements refer to the regulation of the kinds of activities which will be acceptable on particular lots (such as open space, residential, agricultural, commercial or industrial). The intensity of use at which those activities can be performed (from low-density housing such as single family homes to high-density such as high-rise apartment buildings), the height of buildings, the amount of space that structures may occupy, the proprotions of the types of space on a lot, such as how much landscaped space, impervious surface, traffic lanes, and parking may be provided.">
      <area shape="rect" coords="211,455,619,731" alt="INSPIRE_SupplementaryRegulation" title="SupplementaryRegulation: Modelliert übrige Elemente ohne Flächenschluss. Die Hierarchical Supplementary Regulation Codelist (HSRCL) in supplementaryRegulation: SupplementaryRegulationValue (1..*) legt die spezifischen FeatureTypes fest. Definition: A spatial object (point, line or polygon) of a spatial plan that provides supplementary information and/or limitation of the use of land/water necessary for spatial planning reasons or to formalise external rules defined in legal text. Description: NOTE the supplementary regulations affects all land use that overlap with the geometry. EXAMPLE an air field generates restrictions in its surroundings regarding aircraft landing, radar and telecommunication devices. It is the buffer around these artefacts that generates the supplementary regulation on the Land Use.">
      <area shape="rect" coords="652,409,886,524" alt="INSPIRE_OfficialDocumentation" title="Official Documentation: Modelliert textlich formulierte Planinhalte und Dokumente. Definition: The official documentation that composes the spatial plan; it may be composed of, the applicapble legislation, the regulations, cartographic elements, descriptive elements that may be associated with the complete spatial plan, a zoning element or a supplementary regulation. In some Member States the actual textual regulation will be part of the data set (and can be put in the regulationText attribute), in other Member States the text will not be part of the data set and will be referenced via a reference to a document or a legal act. At least one of the three voidable values shall be provided. Description: NOTE: The LegislationCitation is the value type of the attribute regulation reference. An example of a regulation reference would be: http://www2.vlaanderen.be/ruimtelijk/grup/00350/00362_00001/data/212_003262_00001_d_0BVR.pdf.">
    </map>
    <div class="textsite">
      <?php
      echo '<center><h1>Modell</h1><h2>UML-Modell (interaktiv)</h2><hr></center>';
      echo "<form action =\"index.php\">\n";
      echo "<input type=\"hidden\"name=\"go\" value=\"show_uml\">";
      ?>
      <?php include('views/helpmodell.php'); ?>
      <!--  Javascript für Show und Hide () -->   
      <a href="javascript:ReverseDisplay('basisobjekteuml')" class=hlink>
      <h3>RP_Basisobjekte</h3>
      </a>  
      <p>Arbeitsversion 2015-12-03</p>
      <div id="basisobjekteuml" style="display:none;">
      <p><img src="images/2015_12_03-Modell/RP_Basisobjekte_1.png" alt="RP_Basisobjekte1" style="width:826px;height:1168px;" usemap="#RP_Basisobjekte1"><img src="images/2015_12_03-Modell/RP_Basisobjekte_2.png" alt="RP_Basisobjekte2" style="width:826px;height:1168px;" usemap="#RP_Basisobjekte2"></p>
      </div>

      <a href="javascript:ReverseDisplay('freiraumstrukturuml')" class=hlink>
      <h3>RP_Freiraumstruktur</h3>
      </a>
      <p>Arbeitsversion 2015-12-03</p>
      <div id="freiraumstrukturuml" style="display:none;">
      <p><img src="images/2015_12_03-Modell/RP_Freiraumstruktur_1.png" alt="RP_Freiraumstruktur1" style="width:826px;height:1168px;" usemap="#RP_Freiraumstruktur1"><img src="images/2015_12_03-Modell/RP_Freiraumstruktur_2.png" alt="RP_Freiraumstruktur2" style="width:826px;height:1168px;" usemap="#RP_Freiraumstruktur2"><img src="images/2015_12_03-Modell/RP_Freiraumstruktur_3.png" alt="RP_Freiraumstruktur3" style="width:826px;height:1168px;" usemap="#RP_Freiraumstruktur3"></p>
      </div>

      <a href="javascript:ReverseDisplay('infrastrukturuml')" class=hlink>
      <h3>RP_Infrastruktur</h3>
      </a>
      <p>Arbeitsversion 2015-12-03</p>
      <div id="infrastrukturuml" style="display:none;">
      <p><img src="images/2015_12_03-Modell/RP_Infrastruktur_1.png" alt="RP_Infrastruktur1" style="width:826px;height:1168px;" usemap="#RP_Infrastruktur1"><img src="images/2015_12_03-Modell/RP_Infrastruktur_2.png" alt="RP_Infrastruktur2" style="width:826px;height:1168px;" usemap="#RP_Infrastruktur2"></p>
      </div>

      <a href="javascript:ReverseDisplay('siedlungsstrukturuml')" class=hlink>
      <h3>RP_Siedlungsstruktur</h3>
      </a>
      <p>Arbeitsversion 2015-12-03</p>
      <div id="siedlungsstrukturuml" style="display:none;">
      <p><img src="images/2015_12_03-Modell/RP_Siedlungsstruktur_1.png" alt="RP_Siedlungsstruktur1" style="width:826px;height:1168px;" usemap="#RP_Siedlungsstruktur1"><img src="images/2015_12_03-Modell/RP_Siedlungsstruktur_2.png" alt="RP_Siedlungsstruktur1" style="width:826px;height:1168px;" usemap="#RP_Siedlungsstruktur2"></p>
      </div>

      <a href="javascript:ReverseDisplay('sonstigesuml')" class=hlink>
      <h3>RP_Sonstiges</h3>
      </a>
      <p>Arbeitsversion 2015-12-03</p>
      <div id="sonstigesuml" style="display:none;">
      <p><img src="images/2015_12_03-Modell/RP_Sonstiges.png" alt="RP_Sonstiges" style="width:826px;height:1168px;" usemap="#RP_Sonstiges"></p>
      </div>
    
      <a href="javascript:ReverseDisplay('rasteruml')" class=hlink>
      <h3>RP_Raster</h3>
      </a>
      <p>XPlan 4.1</p>
      <div id="rasteruml" style="display:none;">
      <p><img src="images/2015_12_03-Modell/RP_Raster.png" alt="RP_Raster" style="width:826px;height:1168px;" usemap="#RP_Raster"></p>
      </div>
    
      <a href="javascript:ReverseDisplay('xpbasisobjekteuml')" class=hlink>
      <h3>XP_Basisobjekte</h3>
      </a>
      <p>XPlan 4.1</p>
      <div id="xpbasisobjekteuml" style="display:none;">
      <p><img src="images/2015_12_03-Modell/XP_Basisobjekte_1.png" alt="XP_Basisobjekte1" style="width:826px;height:1168px;"usemap="#XP_Basisobjekte1"><img src="images/2015_12_03-Modell/XP_Basisobjekte_2.png" alt="XP_Basisobjekte2" style="width:826px;height:1168px;"usemap="#XP_Basisobjekte2"><p>
      </div>
      
      <a href="javascript:ReverseDisplay('xppraesentationsobjekteuml')" class=hlink>
      <h3>XP_Praesentationsobjekte</h3>
      </a>
      <p>XPlan 4.1</p>
      <div id="xppraesentationsobjekteuml" style="display:none;">
      <p><img src="images/2015_12_03-Modell/XP_Praesentationsobjekte.png" alt="XP_Praesentationsobjekte" style="width:826px;height:1168px;"usemap="#XP_Praesentationsobjekte"><p>
      </div>
    
      <a href="javascript:ReverseDisplay('inspireuml')" class=hlink>
      <h3>INSPIRE Modell Planned Land Use</h3>
      </a>
      <p>Version 3.0</p>
      <div id="inspireuml" style="display:none;">
      <p><img src="images/inspireuml.png" alt="INSPIRE Modell" style="width:1168px;height:826px;" usemap="#INSPIRE"></p>
      </div>
      
      <center><h2>Modell-Downloads</h2></center><hr>
      <h3>UML-Modell als PDF zum <a href="/model/XPlan Raumordnungsplan Arbeitsmodell (Version 15-12-03).pdf">herunterladen</a> (Arbeitsversion 2015-12-03)</h3>
      Diese Datei enthält alle Pakete des XP- und RP-Schemas. Die UML-Repräsentation in PDF-Form ist eine graphische Darstellung der relevanten Modellelemente und erlaubt die Suche von Begriffen innerhalb des Modells.
      <h3>Liste aller Modelländerungen zum <a href="/model/2015_12_03_Aenderungsliste_XPlan_Raumordnungsmodell.doc">herunterladen</a> (Arbeitsversion 2015-12-03)</h3>
      Änderungen am RP-Schema von XPlanGML4.1 seit Projektbeginn sind hier textlich dokumentiert und werden mit jeder Modelländerung aktualisiert.
      <h3>Konformitätsbedingungen für das Raumordnungsschema zum <a href="/model/2016_01_11_Konformitaetsbedingungen.doc">herunterladen</a> (Arbeitsversion 2015-12-03)</h3>
      Konformitätsbedingungen beschreiben Regeln und Relationen des Modells, welche nicht direkt in UML festgehalten werden können. Diese sind jedoch für eine vollständige und valide Konvertierung wichtig. Da XPlanGML4.1 keine Konformitätsbedingungen für das RP-Schema besitzt, wurden für das Projekt neue Konformitätsbedingungen für die Raumordnung zur Integration in die bestehenden Konformitätsbedingungen von XPlan aufgestellt.
      <h3>Enterprise Architect Modell zum <a href="/model/2016-01-18_Modell_EA.eap">herunterladen</a> (Arbeitsversion 2015-01-18)</h3>
      Die Modellierung und Erweiterung des Modells erfolgt anhand der UML-Modellierungssoftware Enterprise Architect von SparxSystems. Durch die .eap Datei kann das derzeitige Modell in Enterprise Architect genutzt werden. Änderungen von Projektseite fanden dabei nur im Paket Raumordnungsplanung (früher: Kernmodell_Regionalplanung) statt.
      <h3>XMI-Datei zum <a href="/model/xplan.xmi">herunterladen</a> (Arbeitsversion 2016-01-18)</h3>
      XMI (XML Metadata Interchange) ist ein anbieterneutrales Format des Modells, welches zum Austausch zwischen Software-Entwicklungswerkzeugen benutzt werden kann. Die dazugehörige Datei erlaubt die Nutzung des Modells mit jeglicher Software.
     <!--<h3>Featurekatalog</h3>
      Diese Sektion enthält den Featurekatalog des Modells zum herunterladen.-->
      <h3>XSD-Dateien zum <a href="/model/2016_01_18_XSD_Modell.zip">herunterladen</a> (Arbeitsversion 2016-01-18)</h3>
      XSD(XML Schema Definition)-Dateien definieren die Strukturen von XML-Dokumenten. Für das Projekt sind diese zur Definition der Struktur eines XPlanGML-Dokuments nötig. Anbei werden die XSD-Dateien aller relevanten Pakete des Modells als .zip bereitgestellt.
      <h3>Modell Report zum <a href="/model/2016_01_26_model_report.pdf">herunterladen</a> (Arbeitsversion 2016-01-26)</h3>
      Der Modellreport aus Enterprise Architect zeigt eine textliche Gesamtübersicht über das gesamte Modell zu Dokumentationszwecken.
    </div>
  </div><?php
  output_footer();
}

function show_elements() {
  global $conn, $params, $packages;
  if ($params['package'] == '') $params['package'] = 'RPlan';
  output_header(true);  
   ?>
  <div id="main">
    <div class="textsite">
      <?php
        echo '<h1><center>XPlan Elemente und Attribute</center><hr>';
        include('views/helplists.php');
        echo '</h1>';
      ?>
      <form action="index.php">
        <input type="hidden" name="go" value="show_elements">
        Package: 
        <select id="element_package_selektor" name="package" size="1" onchange="document.location.href='index.php?go=show_elements&package=' + $(this).val()">
          <?php for ($i=0; $i < count($packages); $i++) {
            ?><option value="<?php echo $packages[$i]['package']; ?>"<?php if ($packages[$i]['package'] == $params['package']) { ?> selected<?php } ?>><?php echo $packages[$i]['package']; ?></option>
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
            " . SCHEMA_PREFIX . "xplan.elements e LEFT JOIN
            " . SCHEMA_PREFIX . "xplan.attributes a ON e.id = a.element_id
          WHERE
            e.name NOT LIKE '_GenericApplicationPropertyOf%'
          ";
        if ($params['package'] != '' AND $params['package'] != 'Alle') $sql .= " AND package LIKE '" . $params['package'] ."'";
        $sql .= "
          ORDER BY ";
        if ($params['order'] != '') {
          $sql .= $params['order'];
        } else {
          $sql .= "
            e.package,
            e.name,
            a.name
          ";
        }

        #echo '<br>' . $sql;
        $result = pg_query($conn, $sql);
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
  output_footer();
}

function output_elements($packages) {
  foreach ($packages AS $package) {
    ?><h2>
      <a href="javascript:toggleVisibility('<?php echo $package['name']; ?>')">
        <img src="images/minus.png" id="<?php echo $package['name']; ?>_minimize_img" alt="Minimize Pakete" class = "minimize_img">
        <img src="images/plus.png" id="<?php echo $package['name']; ?>_maximize_img" alt="Maximize Pakete" class = "maximize_img">
      </a>
      <a href="javascript:toggleVisibility(new Array('<?php echo $package['name']; ?>_all',<?php echo implode(',', array_map(function($element) { return $element['id']; }, $package['elements'])); ?>))">
        <img src="images/minimize.png" id="<?php echo $package['name']; ?>_all_minimize_img" alt="Minimize Pakete" class = "minimize_img">
        <img src="images/maximize.png" id="<?php echo $package['name']; ?>_all_maximize_img" alt="Maximize Pakete" class = "maximize_img">
      </a>
      <?php echo $package['name']; ?>
    </h2>
    <div id="<?php echo $package['name']; ?>" class="toggleable"><?php
      foreach ($package['elements'] AS $element) {
        ?><a href="javascript:toggleVisibility('<?php echo $element['id']; ?>')" class=hlink><img src="images/minimize.png" id="<?php echo $element['id']; ?>_minimize_img" alt="Minimize Pakete" class = "minimize_img"><img src="images/maximize.png" id="<?php echo $element['id']; ?>_maximize_img" alt="Maximize Pakete" class = "maximize_img"></a>
        <b><a name="xplan:<?php echo $element['element_name']; ?>" class="anchor"><?php 
        if ($element['altoderneuelements'] == "veraltet"){
          ?><s><?php echo $element['element_name']; ?></s></a> <img src="images/veraltet.png" height="13"></b><?php
        }    
        else {
          echo $element['element_name']; ?></a></b><?php
        }
        if ($element['altoderneuelements'] == "neu") {
          ?> <img src="images/aenderung.png" height="13"><?php
        }

        else {
          ?><a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/xplan_<?php echo $element['element_name']; ?>.html" target="_blank"><img src="images/Link.png" width="15"></a><?php
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
		echo " <img src=\"images/veraltet.png\" height=\"13\"></s>";
	}
	if ($attribute['altoderneuattributes'] == "neu") {
		echo " <img src=\"images/neu.png\" height=\"13\">";
	}
	echo '<br>';
}

function show_simple_types() {
  global $conn, $params;
  output_header(true);?>
  <div id="main">
  <div class="textsite">
  <?php
      echo '<h1><center>Codelisten</center><hr>';
      include('views/helplists.php');
      echo '</h1>';
    ?>
    <h2>
      <a href="javascript:toggleVisibility('paketeminmax')" class=hlink><img src="images/minimize.png" id="paketeminmax_minimize_img" alt="Minimize Pakete" class = "minimize_img"><img src="images/maximize.png" id="paketeminmax_maximize_img" alt="Maximize Pakete" class = "maximize_img"></a>
      Pakete
    </h2>
    <div id="paketeminmax" class="toggleable">
      <p>
        <h3>
          <a href="javascript:toggleVisibility('basisobjekteminmax')" class=hlink><img src="images/minimize.png" id="basisobjekteminmax_minimize_img" alt="Minimize Basisobjekte" class = "minimize_img"><img src="images/maximize.png" id="basisobjekteminmax_maximize_img" alt="Maximize Pakete" class = "maximize_img"></a>
          Basisobjekte</h3>
          <div id="basisobjekteminmax" class="toggleable">
            XP_Basisobjekte<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_XP_Basisobjekte.html" target="_blank"><img src="images/Link.png" width="15" ></a><br>
            XP_Enumerationen<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_XP_Enumerationen.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            XP_Praesentationsobjekte<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_XP_Praesentationsobjekte.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            XP_Raster<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_XP_Raster.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
          </div>
        <h3>
          <a href="javascript:toggleVisibility('bebauungsplanminmax')" class=hlink><img src="images/minimize.png" id="bebauungsplanminmax_minimize_img" alt="Minimize Bebauungsplan" class = "minimize_img"><img src="images/maximize.png" id="bebauungsplanminmax_maximize_img" alt="Maximize Bebauungsplan" class = "maximize_img"></a>  
          Bebauungsplan</h3>
          <div id="bebauungsplanminmax" class="toggleable">
            BP_Basisobjekte<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP__Basisobjekte.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Aufschuettung_Abgrabung_Bodenschaetze<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Aufschuettung_Abgrabung_Bodenschaetze.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Bebauung<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Bebauung.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Erhaltungssatzung_und_Denkmalschutz<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Erhaltungssatzung_und_Denkmalschutz.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Gemeinbedarf_Spiel_und_Sportanlagen<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Gemeinbedarf_Spiel_und_Sportanlagen.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Landwirtschaft, Wald- und Grünflächen<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Landwirtschaft,%20Wald-%20und%20Gr%fcnfl%e4chen.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Naturschutz_Landschaftsbild_Naturhaushalt<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Naturschutz_Landschaftsbild_Naturhaushalt.html	" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Verkehr<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Verkehr.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Sonstiges<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Sonstiges.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Raster<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Raster.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Wasser<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP__Basisobjekte.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Umwelt<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Umwelt.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            BP_Ver_und_Entsorgung<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_BP_Ver_und_Entsorgung.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
          </div>
        <h3>
          <a href="javascript:toggleVisibility('flaechennutzungsplanminmax')" class=hlink><img src="images/minimize.png" id="flaechennutzungsplanminmax_minimize_img" alt="Minimize Flaechennutzungsplan" class = "minimize_img"><img src="images/maximize.png" id="flaechennutzungsplanminmax_maximize_img" alt="Maximize Flaechennutzungssplan" class = "maximize_img"></a>  
          Flaechennutzungsplan</h3>
          <div id="flaechennutzungsplanminmax" class="toggleable">
            FP_Basisobjekte<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP__Basisobjekte.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Aufschuettung_Abgrabung_Bodenschaetze<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Aufschuettung_Abgrabung_Bodenschaetze.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Bebauung<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Bebauung.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Gemeinbedarf_Spiel_und_Sportanlagen<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/xplan_FP_SpielSportanlage.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Landwirtschaft_Wald_und_Gruen<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Landwirtschaft_Wald_und_Gruen.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Naturschutz<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Naturschutz.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Raster<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Raster.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Sonstiges<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Sonstiges.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Verkehr<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Verkehr.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Ver- und Entsorgung<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Ver- und Entsorgung.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            FP_Wasser<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_FP_Wasser.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
          </div>
        <h3>
          <a href="javascript:toggleVisibility('regionalplanminmax')" class=hlink><img src="images/minimize.png" id="regionalplanminmax_minimize_img" alt="Minimize Regionalplan" class = "minimize_img"><img src="images/maximize.png" id="regionalplanminmax_maximize_img" alt="Maximize Regionalplan" class = "maximize_img"></a>  
          Regionalplan</h3>
          <div id="regionalplanminmax" class="toggleable">
            RP_Basisobjekte<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_RP__Basisobjekte.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            RP_Freiraumstruktur<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_RP_KernmodellFreiraumstruktur.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            RP_Infrastruktur<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_RP_KernmodellInfrastruktur.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            RP_Siedlungsstruktur<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_RP_KernmodellSiedlungsstruktur.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            RP_Sonstiges<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_RP_KernmodellSonstiges.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
          </div>
        <h3>
          <a href="javascript:toggleVisibility('landschaftsplanminmax')" class=hlink><img src="images/minimize.png" id="landschaftsplanminmax_minimize_img" alt="Minimize Landschaftsplan" class = "minimize_img"><img src="images/maximize.png" id="landschaftsplanminmax_maximize_img" alt="Maximize Landschaftsplan" class = "maximize_img"></a>  
          Landschaftsplan</h3>
          <div id="landschaftsplanminmax" class="toggleable">
            LP_Basisobjekte<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_LP__Basisobjekte.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            LP_Erholung<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_LP__Erholung.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            LP_MassnahmenNaturschutz<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_LP__MassnahmenNaturschutz.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            LP_SchutzgebieteObjekte<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_LP__SchutzgebieteObjekte.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            LP_Sonstiges<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_LP__Sonstiges.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
          </div>
        <h3>
          <a href="javascript:toggleVisibility('sonstigeplanwerkeminmax')" class=hlink><img src="images/minimize.png" id="sonstigeplanwerkeminmax_minimize_img" alt="MinimizeSonstige Planwerke" class = "minimize_img"><img src="images/maximize.png" id="sonstigeplanwerkeminmax_maximize_img" alt="Maximize Sonstige Planwerke" class = "maximize_img"></a>  
          SonstigePlanwerke</h3>
          <div id="sonstigeplanwerkeminmax" class="toggleable">
            SO_Basisobjekte<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_SO_Basisobjekte.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            SO_NachrichtlicheUebernahmen<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_SO_NachrichtlicheUebernahmen.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            SO_Raster<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_SO_Raster.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            SO_Schutzgebiete<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_SO_Schutzgebiete.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            SO_Sonstiges<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_SO_Sonstiges.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
            SO_SonstigeGebiete<a href="http://www.xplanungwiki.de/upload/XPlanGML/4.1-Kernmodell/Objektartenkatalog/html/Package_xplan_SO_SonstigeGebiete.html" target="_blank"><img src="images/Link.png" width="15"></a><br>
          </div>
      </p>
    </div>
      <h2><a href="javascript:toggleVisibility('enumerationstypesminmax')" class=hlink><img src="images/minimize.png" id="enumerationstypesminmax_minimize_img" alt="Minimize Pakete" class = "minimize_img"><img src="images/maximize.png" id="enumerationstypesminmax_maximize_img" alt="Maximize Pakete" class = "maximize_img"></a> Enumerations</h2> 
    <div id="enumerationstypesminmax" class="toggleable">
      <p>
    <?php
    echo "<form action=\"index.php\">\n";
    echo "<input type=\"hidden\" name=\"go\" value=\"show_simple_types\">";
    echo "Package: <input name=\"package\" value=\"" . $params['package']. "\" size=\"35\">";

    $sql = "SELECT st.id simple_type_id, st.name simple_type_name, st.package, en.id enumeration_id, en.name enumeration_name, en.value enumeration_value, st.altoderneusimpletypes, en.altoderneuenumerations FROM " . SCHEMA_PREFIX . "xplan.simple_types st, " . SCHEMA_PREFIX . "xplan.enumerations en WHERE st.id = en.simple_type_id";
    if ($params['package'] != '') $sql .= " AND package LIKE '" . $params['package'] ."'";
    $sql .= ' ORDER BY ';
    if ($params['order'] != '') {
      $sql .= $params['order'];
    } else {
      $sql .= 'st.package, st.name, en.value';
    }

    #echo '<br>' . $sql;
    $result = pg_query($conn, $sql);
    $simple_type_id = 0;
    $package_value = '';
    while ($row = pg_fetch_assoc($result)) {
      if ($package_value != $row['package']) {
        $package_value = $row['package'];
        echo '<h3>' . $package_value . '</h3>';
      }
      if ($simple_type_id != $row['simple_type_id']) {
        $simple_type_id = $row['simple_type_id'];
        echo '<b><a name= "xplan:' . $row['simple_type_name'] . '">';
        if ($row['altoderneusimpletypes'] == "veraltet"){echo "<s>";}
        echo $row['simple_type_name'];
        if ($row['altoderneusimpletypes'] == "neu"){echo " <img src=\"images/aenderung.png\" height=\"13\">";}
        echo '</a>';
        if ($row['altoderneusimpletypes'] == "veraltet"){echo " <img src=\"images/veraltet.png\" height=\"13\"></s>";}
        echo '</a></b><br>' . '&nbsp;&nbsp; ';
        if ($row['altoderneuenumerations'] == "veraltet"){echo '<s>';}
        echo $row['enumeration_value'] . ' '. $row['enumeration_name'];
        if ($row['altoderneuenumerations'] == "neu"){
          echo " <img src=\"images/aenderung.png\" height=\"13\">";
        }
        if ($row['altoderneuenumerations'] == "veraltet"){
          echo "</s> <img src=\"images/veraltet.png\" height=\"13\">";  
        }
        echo '</a><br>';
      
      } else {
        echo '&nbsp;&nbsp; ';
        if ($row['altoderneuenumerations'] == "veraltet") {echo "<s>";}
        echo  $row['enumeration_value'] . ' '. $row['enumeration_name'];
        if ($row['altoderneuenumerations'] == "neu") {echo " <img src=\"images/aenderung.png\" height=\"13\">";}
        if ($row['altoderneuenumerations'] == "veraltet") {echo "</s> <img src=\"images/veraltet.png\" height=\"13\">";}
        echo '</a><br>';
      }
    }
    ?>
    </p>
    </div>  
  	<h2><a href="javascript:toggleVisibility('codelistsminmax')" class=hlink><img src="images/minimize.png" id="codelistsminmax_minimize_img" alt="Minimize Codelists" class = "minimize_img"><img src="images/maximize.png" id="codelistsminmax_maximize_img" alt="Maximize Codelists" class = "maximize_img"></a> Externe Codelisten</h2> 
    <div id="codelistsminmax" class="toggleable">
      <p>
  			&nbsp;&nbsp;<a name="xplan:BP_AbweichendeBauweise"> </a>BP_AbweichendeBauweise<br>
  			&nbsp;&nbsp;<a name="xplan:BP_DetailArtDerBaulNutzung"> </a>BP_DetailArtDerBaulNutzung<br>
  			&nbsp;&nbsp;<a name="xplan:BP_DetailDachform"> </a>BP_DetailDachform<br>
  			&nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestGemeinbedarf"> </a>BP_DetailZweckbestGemeinbedarf<br>
  			&nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestGemeinschaftsanlagen"> </a>BP_DetailZweckbestGemeinschaftsanlagen<br>
  			&nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestGewaesser"> </a>BP_DetailZweckbestGewaesser<br>
  			&nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestLandwirtschaft"> </a>BP_DetailZweckbestLandwirtschaft<br>
        &nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestNaturschutzgebiet"> </a>BP_DetailZweckbestNaturschutzgebiet<br>
        &nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestNebenanlagen"> </a>BP_DetailZweckbestNebenanlagen<br>
        &nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestSpielSportanlage"> </a>BP_DetailZweckbestSpielSportanlage<br>
        &nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestStrassenverkehr"> </a>BP_ZweckbestStrassenverkehr<br>
        &nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestVerEntsorgung"> </a>BP_DetailZweckbestVerEntsorgung<br>
        &nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestWaldFlaeche"> </a>BP_DetailZweckbestWaldFlaeche<br>
  			&nbsp;&nbsp;<a name="xplan:BP_DetailZweckbestWasserwirtschaft"> </a>BP_DetailZweckbestWasserwirtschaft<br>
        &nbsp;&nbsp;<a name="xplan:BP_SonstPlanArt"> </a>BP_SonstPlanArt<br>
        &nbsp;&nbsp;<a name="xplan:BP_SpezielleBauweiseSonstTypen"> </a>BP_SpezielleBauweiseSonstTypen<br>
        &nbsp;&nbsp;<a name="xplan:BP_Status"> </a>BP_Status<br>
        &nbsp;&nbsp;<a name="xplan:BP_ZweckbestimmungGenerischeObjekte"> </a>BP_ZweckbestimmungGenerischeObjekte<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailArtderBaulNutzung"> </a>FP_DetailArtderBaulNutzung<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailZweckbestGemeinbedarf"> </a>FP_DetailZweckbestGemeinbedarf<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailZweckbestGewaesser"> </a>FP_DetailZweckbestGewaesser<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailZweckbestGruen"> </a>FP_DetailZweckbestGruen<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailZweckbestLandwirtschaftsFlaeche"> </a>FP_DetailZweckbestLandwirtschaftsFlaeche<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailZweckbestSpielSport"> </a>FP_DetailZweckbestSpielSport<<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetaiLZweckbestStrassenverkehr"> </a>FP_DetaiLZweckbestStrassenverkehr<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailZweckbestVerEntsorgung"> </a>FP_DetailZweckbestVerEntsorgung<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailZweckbestWaldFlaeche"> </a>FP_DetailZweckbestWaldFlaeche<br>
        &nbsp;&nbsp;<a name="xplan:FP_DetailZweckbestWasserwirtschaft"> </a>FP_DetailZweckbestWasserwirtschaft<br>
        &nbsp;&nbsp;<a name="xplan:FP_SonstPlanArt"> </a>FP_SonstPlanArt<br>
        &nbsp;&nbsp;<a name="xplan:FP_SpezifischePraegungTypen"> </a>FP_SpezifischePraegungTypen<br>
        &nbsp;&nbsp;<a name="xplan:FP_Status"> </a>FP_Status<br>
        &nbsp;&nbsp;<a name="xplan:FP_ZentralerVersorgungsbereichAuspraegung"> </a>FP_ZentralerVersorgungsbereichAuspraegung<br>
        &nbsp;&nbsp;<a name="xplan:FP_ZweckbestimmungGenerischeObjekte"> </a>FP_ZweckbestimmungGenerischeObjekte<br>
        &nbsp;&nbsp;<a name="xplan:LP_BodenschutzrechtDetailTypen"> </a>LP_BodenschutzrechtDetailTypen<br>
        &nbsp;&nbsp;<a name="xplan:LP_DenkmalschutzrechtDetailTypen"> </a>LP_DenkmalschutzrechtDetailTypen<br>
  			&nbsp;&nbsp;<a name="xplan:LP_ErholungFreizeitDetaiLFunktionen"> </a>LP_ErholungFreizeitDetaiLFunktionen<br>
        &nbsp;&nbsp;<a name="xplan:LP_InternatSchutzobjektDetailTypen"> </a>LP_InternatSchutzobjektDetailTypen<br>
        &nbsp;&nbsp;<a name="xplan:LP_SchutzgebietDetailTypen"> </a>LP_SchutzgebietDetailTypen<br>
        &nbsp;&nbsp;<a name="xplan:LP_SchutzobjektLandesrechtDetailTypen"> </a>LP_SchutzobjektLandesrechtDetailTypen<br>
  			&nbsp;&nbsp;<a name="xplan:LP_SonstRechtDetailTypen"> </a>LP_SonstRechtDetailTypen<br>
        &nbsp;&nbsp;<a name="xplan:LP_WaldschutzDetailTypen"> </a>LP_WaldschutzDetailTypen<br>
        &nbsp;&nbsp;<a name="xplan:LP_WasserrechtGemeingebrEinschraenkungNaturschutzdetailTypen"> </a>LP_WasserrechtGemeingebrEinschraenkungNaturschutzdetailTypen<br>
        &nbsp;&nbsp;<a name="xplan:LP_WasserrechtSchutzgebietDetailTypen"> </a>LP_WasserrechtSchutzgebietDetailTypen<br>
        &nbsp;&nbsp;<a name="xplan:LP_WasserrechtSonstigeTypen"> </a>LP_WasserrechtSonstigeTypen<br>
  			&nbsp;&nbsp;<a name="xplan:LP_WasserrechtWirtschaftAbflussHochwSchutzDetailTypen"> </a>LP_WasserrechtWirtschaftAbflussHochwSchutzDetailTypen<br>
        &nbsp;&nbsp;<a name="xplan:RP_FeatureTypeListe"> </a>RP_FeatureTypeListe<br>
        &nbsp;&nbsp;<a name="xplan:RP_SonstGrenzeTypen"> </a>RP_SonstGrenzeTypen<br>
        &nbsp;&nbsp;<a name="xplan:RP_SonstPlanArt"> </a>RP_SonstPlanArt<br>
        &nbsp;&nbsp;<a name="xplan:RP_Status"> </a>RP_Status<br>
        &nbsp;&nbsp;<a name="xplan:RP_ZweckbestimmungGenerischeObjekte"> </a>RP_ZweckbestimmungGenerischeObjekte<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachBodenschutzrecht"> </a>SO_DetailKlassifizNachBodenschutzrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachDenkmalschutzrecht"> </a>SO_DetailKlassifizNachDenkmalschutzrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachForstrecht"> </a>SO_DetailKlassifizNachForstrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachLuftverkehrsrecht"> </a>SO_DetailKlassifizNachLuftverkehrsrecht<br>
  			&nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachSchienenverkehrsrecht"> </a>SO_DetailKlassifizNachSchienenverkehrsrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachSonstigemRecht"> </a>SO_DetailKlassifizNachSonstigemRecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachStrassenverkehrsrecht"> </a>SO_DetailKlassifizNachStrassenverkehrsrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachWasserrecht"> </a>SO_DetailKlassifizNachWasserrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizSchutzgebietNaturschutzrecht"> </a>SO_DetailKlassifizSchutzgebietNaturschutzrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizNachBodenschutzrecht"> </a>SO_DetailKlassifizNachBodenschutzrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizSchutzgebietSonstRecht"> </a>SO_DetailKlassifizSchutzgebietSonstRecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_DetailKlassifizSchutzgebietWasserrecht"> </a>SO_DetailKlassifizSchutzgebietWasserrecht<br>
        &nbsp;&nbsp;<a name="xplan:SO_SonstGebietsArt"> </a>SO_SonstGebietsArt<br>
  			&nbsp;&nbsp;<a name="xplan:SO_SonstGrenzeTypen"> </a>SO_SonstGrenzeTypen<br>
  			&nbsp;&nbsp;<a name="xplan:SO_SonstRechtscharakter"> </a>SO_SonstRechtscharakter<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FestsetzungBPlan<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DarstellungFPlan<br>
  			&nbsp;&nbsp;<a name="xplan:VegetationsobjektTypen"> </a>VegetationsobjektTypen<br>
  			&nbsp;&nbsp;<a name="xplan:XP_ExterneReferenzArt"> </a>XP_ExterneReferenzArt<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dokument<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PlanMitGeoreferenz<br>
  			&nbsp;&nbsp;<a name="xplan:XP_GesetzlicheGrundlage"> </a>XP_GesetzlicheGrundlage<br>
  			&nbsp;&nbsp;<a name="xplan:XP_MimeType"> </a>XP_MimeType<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/pdf<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/zip<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/xml<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/msword<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/msexcel<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/vnd.ogc.sld+xml<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/vnd.ogc.wms_xml<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/vnd.ogc.gml<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;application/odt<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;image/jpg<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;image/png<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;image/tiff<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;image/ecw<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;image/svg+xml<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;text/html<br>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;text/plain<br>
  			&nbsp;&nbsp;<a name="xplan:XP_StylesheetListe"> </a>XP_StylesheetListe<br>
      </p>
    </div> 
	
    <h2><a href="javascript:toggleVisibility('gmltypesminmax')" class=hlink><img src="images/minimize.png" id="gmltypesminmax_minimize_img" alt="Minimize Pakete" class = "minimize_img"><img src="images/maximize.png" id="gmltypesminmax_maximize_img" alt="Maximize Pakete" class = "maximize_img"></a> GML-Types</h2> 
    <div id="gmltypesminmax" class="toggleable">
      <p>
        &nbsp;&nbsp;<a name="gml:AbstractGeometry"> </a>gml:AbstractGeometry<br>
        &nbsp;&nbsp;<a name="gml:AbstractRing"> </a>gml:AbstractRing<br>
        &nbsp;&nbsp;<a name="gml:AngleType"> </a>gml:AngleType<br>
        &nbsp;&nbsp;<a name="gml:AreaType"> </a>gml:AreaType<br>
        &nbsp;&nbsp;<a name="gml:boundedBy"> </a>gml:boundedBy<br>
        &nbsp;&nbsp;<a name="gml:CodeType"> </a>gml:CodeType<br>
        &nbsp;&nbsp;<a name="gml:coverageFunction"> </a>gml:coverageFunction<br>
        &nbsp;&nbsp;<a name="gml:curveMember"> </a>gml:curveMember<br>
        &nbsp;&nbsp;<a name="gml:description"> </a>gml:description<br>
        &nbsp;&nbsp;<a name="gml:descriptionReference"> </a>gml:descriptionReference<br>
        &nbsp;&nbsp;<a name="gml:DirectPositionType"> </a>gml:DirectPositionType<br>
        &nbsp;&nbsp;<a name="gml:domainSet"> </a>gml:domainSett<br>
        &nbsp;&nbsp;<a name="gml:doubleOrNilReasonTupleList"> </a>gml:doubleOrNilReasonTupleList<br>
        &nbsp;&nbsp;<a name="gml:Envelope"> </a>gml:Envelope<br>
        &nbsp;&nbsp;<a name="gml:featureMember"> </a>gml:featureMember<br>
        &nbsp;&nbsp;<a name="gml:exterior"> </a>gml:exterior<br>
        &nbsp;&nbsp;<a name="gml:Geometry"> </a>gml:GeometryPropertyType<br> <!--  hier nur property statt property type, da Parser Type abschneidet-->
        &nbsp;&nbsp;<a name="gml:identifier"> </a>gml:identifier<br>
        &nbsp;&nbsp;<a name="gml:integerList"> </a>gml:integerList<br>
        &nbsp;&nbsp;<a name="gml:interior"> </a>gml:interior<br>
        &nbsp;&nbsp;<a name="gml:LengthType"> </a>gml:LengthType<br>
        &nbsp;&nbsp;<a name="gml:name"> </a>gml:name<br>
        &nbsp;&nbsp;<a name="gml:patches"> </a>gml:patches<br>
        &nbsp;&nbsp;<a name="gml:pointMember"> </a>gml:pointMember<br>
        &nbsp;&nbsp;<a name="gml:pos"> </a>gml:pos<br>
        &nbsp;&nbsp;<a name="gml:posList"> </a>gml:posList<br>
        &nbsp;&nbsp;<a name="gml:rangeParameters"> </a>gml:rangeParameters<br>
        &nbsp;&nbsp;<a name="gml:rangeSet"> </a>gml:rangeSet<br>
        &nbsp;&nbsp;<a name="gml:ReferenceType"> </a>gml:ReferenceType<br>
        &nbsp;&nbsp;<a name="gml:segments"> </a>gml:segments<br>
        &nbsp;&nbsp;<a name="gml:SequenceRuleType"> </a>gml:SequenceRuleType<br>
        &nbsp;&nbsp;<a name="gml:solidMember"> </a>gml:solidMember<br>
        &nbsp;&nbsp;<a name="gml:surfaceMember"> </a>gml:surfaceMember<br>
        &nbsp;&nbsp;<a name="gml:VectorType"> </a>gml:VectorType<br>
        &nbsp;&nbsp;<a name="gml:VolumeType"> </a>gml:VolumeType<br>
      </p>
    </div>
    <h2><a href="javascript:toggleVisibility('simpletypesminmax')" class=hlink><img src="images/minimize.png" id="simpletypesminmax_minimize_img" alt="Minimize Pakete" class = "minimize_img"><img src="images/maximize.png" id="simpletypesminmax_maximize_img" alt="Maximize Pakete" class = "maximize_img"></a> Simple-Types</h2> 
    <div id="simpletypesminmax" class="toggleable">
      <p>
        &nbsp;&nbsp;<a name="anyURI"> </a>anyURI<br>
        &nbsp;&nbsp;<a name="boolean"> </a>boolean<br>
        &nbsp;&nbsp;<a name="codetype"> </a>codetype<br>
        &nbsp;&nbsp;<a name="date"> </a>date<br>
        &nbsp;&nbsp;<a name="double"> </a>double<br>
        &nbsp;&nbsp;<a name="integer"> </a>integer<br>
        &nbsp;&nbsp;<a name="string"> </a>string<br>
    </div>
    </div>
  </div><?php
  output_footer();
}


function load_xsd($file_name, $truncate) {
  global $conn;
  output_header(true);?>
  <div id="main"><?php
  $fp = fopen($file_name, "r");
  $file_parts = explode('_', $file_name, 2);
  $path_parts = pathinfo($file_parts[1]);
  $package = $path_parts['filename'];
  $num_elements = 0;
  $num_simple_types = 0;
  $in_sequence = false;
  $in_enumeration = false;
  $attributes = array();
  
  echo '<h1>Laden des XPlanung-Schemas von XSD-Dateien</h1>';
  echo '<br><b>Lade Datei:</b>';
  echo '<br>' . $file_name;

  if ($truncate == 1) {
    $sql  = 'TRUNCATE " . SCHEMA_PREFIX . "xplan.elements;';
    $sql .= 'ALTER SEQUENCE " . SCHEMA_PREFIX . "xplan.elements_id_seq RESTART;';
    $sql .= 'TRUNCATE " . SCHEMA_PREFIX . "xplan.attributes;';
    $sql .= 'ALTER SEQUENCE " . SCHEMA_PREFIX . "xplan.attributes_id_seq RESTART;';
    $sql .= 'TRUNCATE " . SCHEMA_PREFIX . "xplan.simple_types;';
    $sql .= 'ALTER SEQUENCE " . SCHEMA_PREFIX . "xplan.simple_types_id_seq RESTART;';
    $sql .= 'TRUNCATE " . SCHEMA_PREFIX . "xplan.enumerations;';
    $sql .= 'ALTER SEQUENCE " . SCHEMA_PREFIX . "xplan.enumerations_id_seq RESTART;';
    echo '<br><b>Leeren der Datentabellen und zurücksetzen der Autowerte.</b>';
    echo '<br>' . $sql;
    pg_query($conn, $sql);
  }

  if ($fp) {
    echo '<br><b>Schreiben der Elemente seiner Attribute, Simple Types und Enumeration in die Datenbank.</b>';
    while (($line = fgets($fp)) !== false) {
      // process the line read.
      if (is_element($line)) {
        $num_elements++;
        $element = get_kvps_from_tag($line);
        if ($in_sequence) {
          echo '<br>Attribut: ' . $element['name'];
          $attributes[] = $element;
        }
        else {
          $new_element = $element;
          echo '<br>Element: ' . $element['name'];
          $element_id = write_element($package, $element);
        }
      }
      if (is_documentation($line)) {
        update_documentation($element_id, strip_tags(trim($line)));
      }
      if (is_sequence_start($line)) {
        $in_sequence = true;
        $attributes = array();
      }
      if (is_sequence_end($line)) {
        $in_sequence = false;
        write_attributes($element_id, $attributes);
      }
      if (is_simple_type($line)) {
        $num_simple_types++;
        $simple_type = get_kvps_from_tag($line);
        echo '<br>SimpleType: '. $simple_type['name'];
        $simple_type_id = write_simple_type($package, $simple_type);
      }
      if (is_enumeration_start($line)) {
        $in_enumeration = true;
        $enumeration = get_kvps_from_tag($line);
      }
      if (is_enumeration_comment($in_enumeration, $line)) {
        $enumeration['name'] = get_text_from_comment($line);
      }
      if (is_enumeration_end($line)) {
        $in_enumeration = false;
        write_enumeration($simple_type_id, $enumeration);
      }
    }
    fclose($fp);
    echo '<br>';
    echo '<br><b>Anzahl elements:</b> ' . $num_elements;
    echo '<br><b>Anzahl simple types:</b> '. $num_simple_types;
    
  } else {
    echo "error beim oeffnen der datei";
  } ?>
  </div><?php
  output_footer();
}

function is_element($line) {
  if (strpos($line, '<element') === false) {
    return false;
  } else {
    return true;
  }
}

function is_simple_type($line) {
  if (strpos($line, '<simpleType') === false) {
    return false;
  } else {
    return true;
  }
}

function is_enumeration_comment($in_enumeration, $line) {
  if (strpos($line, '<!--') === false) {
    return false;
  } else {
    if ($in_enumeration) {
      return true;
    }
    else {
      return false;
    }
  }
}

function is_sequence_start($line) {
  if (strpos($line, '<sequence>') === false) {
    return false;
  } else {
    return true;
  }
}

function is_enumeration_start($line) {
  if (strpos($line, '<enumeration') === false) {
    return false;
  } else {
    return true;
  }
}

function is_sequence_end($line) {
  if (strpos($line, '</sequence>') === false) {
    return false;
  } else {
    return true;
  }
}

function is_enumeration_end($line) {
  if (strpos($line, '</enumeration>') === false) {
    return false;
  } else {
    return true;
  }
}

function is_documentation($line) {
  if (strpos($line, '<documentation') === false) {
    return false;
  } else {
    return true;
  }
}

function get_kvps_from_tag($line) {
  $line = str_replace('/>', '', $line);
  $line = str_replace('>', '', $line);
  $el_parts = explode(' ', trim($line));
  $kvps = array();
  for ($i = 1; $i < count($el_parts); $i++) {
    $kvp_parts = explode('=', $el_parts[$i]);
    $kvps[$kvp_parts[0]] = trim($kvp_parts[1], '"');
  }
  return $kvps;
}

function get_text_from_comment($line) {
  $line_parts = explode('--', $line);
  return trim($line_parts[1]);
}

/**
  * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
  * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
  */
function csv_encode( array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false ) {
  $delimiter_esc = preg_quote($delimiter, '/');
  $enclosure_esc = preg_quote($enclosure, '/');

  $output = array();
  foreach ( $fields as $field ) {
    if ($field === null && $nullToMysqlNull) {
      $output[] = 'NULL';
      continue;
    }

    // Enclose fields containing $delimiter, $enclosure or whitespace
    if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
      $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
    }
    else {
      $output[] = $field;
    }
  }

  return implode( $delimiter, $output );
}

function write_element($package, $element) {
  global $conn;
  $sql = "INSERT INTO " . SCHEMA_PREFIX . "xplan.elements (name, type, \"substitutionGroup\", package) VALUES('" . $element['name'] . "', '" . $element['type'] . "', '" . $element['substitutionGroup'] . "', '" . $package . "') RETURNING id";
  echo '<br>' . $sql;
  $result = pg_query($conn, $sql);
  $row = pg_fetch_row($result);
  return $row[0];
}

function write_simple_type($package, $simple_type) {
  global $conn;
  $sql = "INSERT INTO " . SCHEMA_PREFIX . "xplan.simple_types (name, package) VALUES('" . $simple_type['name'] . "', '" . $package . "') RETURNING id";
  echo '<br>' . $sql;
  $result = pg_query($conn, $sql);
  $row = pg_fetch_row($result);
  return $row[0];
}

function write_enumeration($simple_type_id, $enumeration) {
  global $conn;
  $sql = "INSERT INTO " . SCHEMA_PREFIX . "xplan.enumerations (simple_type_id, value, name) VALUES(". $simple_type_id .", '" . $enumeration['value'] . "', '" . $enumeration['name'] . "') RETURNING id";
  echo '<br>' . $sql;
  $result = pg_query($conn, $sql);
  $row = pg_fetch_row($result);
  return $row[0];
}

function update_documentation($element_id, $documentation) {
  global $conn;
  $documentation = str_replace('&lt;i&gt;', '', $documentation);
  $documentation = str_replace('&lt;/i&gt;', '', $documentation);  
  $sql = "UPDATE " . SCHEMA_PREFIX . "xplan.elements SET documentation = '" . $documentation . "' WHERE id = " . $element_id;
  echo '<br>' . $sql;
  $result = pg_query($conn, $sql);
}

function write_attributes($element_id, $attributes) {
  global $conn;
  for ($i = 0; $i < count($attributes); $i++) {
    $sql = "INSERT INTO " . SCHEMA_PREFIX . "xplan.attributes (\"name\", \"minOccurs\", \"maxOccurs\", \"type\", \"ref\", element_id) VALUES('" . $attributes[$i]['name'] . "', '" . $attributes[$i]['minOccurs'] . "', '" . $attributes[$i]['maxOccurs'] . "', '" . $attributes[$i]['type'] . "', '" . $attributes[$i]['ref'] . "', " . $element_id . ")";
    echo '<br>' . $sql;
    $result = pg_query($conn, $sql);
  }
}
?>