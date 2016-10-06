<?PHP
# DB_Conn
include('config.php');
$conn = pg_connect('dbname=' . DBNAME . ' user=' . DBUSER .  ' password=' . DBPASS);
if (!$conn){echo "Verbindung mit der Datenbank konnte nicht hergestellt werden.\n"; exit;}
// Retrieve data from Query String
$featuretype = $_GET['featuretype'];

$sql = "
  SELECT
    xmi_id, name, id, general_id
  FROM
   xplan_uml.uml_classes
  ";
$result = pg_query($conn, $sql);
$classes = array();
while ($row = pg_fetch_row($results)){
  $classes[] = $row;
}

$sql ="
  SELECT
    parent_id, child_id
  FROM
    xplan_uml.class_generalizations
  ";
$result = pg_query($conn, $sql);
$generalizations = array();
while ($row = pg_fetch_row($results)){
  $generalizations[] = $row;
}

// Array für die Ids des abzufragenden FeatureTypes und aller Parent-Objekte (RP_Objekt, XP_Objekt)
$classes_all_id = array();
foreach ($classes as $class){
  if($class[1] == $featuretype){
    //Hole alle Ids von parent/generalisierten Objekte (XP_Objekt, RP_Objekt etc.)
    // Bei Anwendung in einem anderen Modell, muss bedacht werden, dass diese Abfrage nur eine bestimmte Tiefe von Generalisierungen erreicht
    $classes_all[] == $class[2];
    foreach($classes as $class_p){
      if($class[3] == $class_p[2]){
        $classes_all[] == $class_p[2];
        foreach($classes as $class_p_p){
          if($class_p[3] == $class_p_p[2]){
            $classes_all[] == $class_p_p[2];
            foreach($classes as $class_p_p_p){
              if($class_p_p[3] == $class_p_p_p[2]){
                $classes_all[] == $class_p_p_p[2];
                foreach($classes as $class_p_p_p_p){
                  if($class_p_p_p[3] == $class_p_p_p_p[2]){
                    $classes_all[] == $class_p_p_p_p[2];
                  }
                }
              }
            }
          }
        }
      }     
    }
  }
foreach ($classes_all_id as $class_all_id{}
  echo $class_all_id;
  echo '<br>';
}
?>