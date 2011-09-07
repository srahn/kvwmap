<?php
#-----------------------------------------------------------------------------------------------------------------
#####################
# Klasse alkisclass #
#####################
class alkisClass {
  var $id;
  var $name;
  var $baseclassid;
  var $database;
  var $wfsbaseurl;

  ##################### Liste der Funktionen ####################################
  #
  # alkisClass()  - Construktor
  # getAttributes($classid,$order)
  # getClasses($id,$name,$baseclassid,$order)
  # getClassRelations($source,$src2trg)
  # listAttributes($classid)
  # listClasses($id,$name,$baseclassid,$order)
  #
  ################################################################################

  function alkisClass() {
    global $debug;
    $this->debug=$debug;
  }

  function getClasses($id,$name,$baseclassid,$order) {
  	# fragt die zu den Parametern passenden Klassen ab.
  	if ($this->database->type=='postgresql') {
	  	$sql ="SELECT * FROM sggenclasstab WHERE 1=1";
	  	if ($id!='') {
	  		$sql.=" AND classid=".$id;
	  	}
	  	if ($name!='') {
	  		$sql.=" AND classname='".$name."'";
	  	}
	  	if ($baseclassid!='') {
	  		$sql.=" AND baseclassid=".$baseclassid;
	  	}
	    if ($order!='') {
	      $sql.=' ORDER BY '.$order; 
	    }
	    #echo $sql;
	    $this->debug->write("<p>alkis.php->getclasses->Abfragen der ALKIS Klassen:<br>".$sql,4);
      $ret=$this->database->execSQL($sql, 4, 0);
	    if ($ret[0]) {
	      $ret[1]="Fehler bei SQL Anweisung:<br>".$sql."<br>".pg_result_error($query);
	    }
	    else {
		    while ($rs=pg_fetch_array($ret[1])) {
		      $classes[]=$rs;
		    }
		    $ret[1]=$classes;
	    }
		} # end Abfrage aus Postgres Datenbank

  	if ($this->wfsbaseurl!='') {
  		# Abfragen der classen über WFS
  		$ret[0]=1;
  		$ret[1]='Die Abfrage von Classen über WFS funzt noch nicht.';
  	} # end Abfrage aus WFS
  	
    return $ret;
  }
  
  function getAttributes($classid,$order) {
  	# fragt die zu der Klasse gehörenden Attribute ab.
  	if ($this->database->type=='postgresql') {
	  	$sql ="SELECT attid,name,type,maxoccurs,writeable FROM sgattdescrtab WHERE 1=1";
	  	if ($classid!='') {
	  		$sql.=" AND classid=".$classid;
	  	}
	    if ($order!='') {
	      $sql.=' ORDER BY '.$order; 
	    }
	    #echo $sql;
	    $this->debug->write("<p>alkis.php->getAttributes->Abfragen der ALKIS Attribute zur Klasse:<br>".$sql,4);
      $ret=$this->database->execSQL($sql, 4, 0);
	    if ($ret[0]) {
	      $ret[1]="Fehler bei SQL Anweisung:<br>".$sql."<br>".pg_result_error($query);
	    }
	    else {
		    while ($rs=pg_fetch_array($ret[1])) {
		      $attributes[]=$rs;
		    }
		    $ret[1]=$attributes;
	    }
		} # end Abfrage aus Postgres Datenbank
    return $ret;
  }

  function getClassRelations($source,$src2trg) {
  	# fragt die Klassenid ab, zu der von der Klasse $source die Beziehungen $src2trg besteht.
  	if ($this->database->type=='postgresql') {
	  	$sql ="SELECT c.classid,c.classname FROM sgclassreltab AS r, sggenclasstab AS c";
	  	$sql.=" WHERE r.target=c.classid";
	  	if ($source!='') {
	  		$sql.=" AND r.source=".$source;
	  	}
	  	if ($src2trg!='') {
	  		$sql.=" AND r.src2trg='".$src2trg."'";
	  	}
	    #echo $sql;
	    $this->debug->write("<p>alkis.php->getClassRelations->Abfragen der Klassenid zu der von einer Klasse aus eine Relation besteht:<br>".$sql,4);
      $ret=$this->database->execSQL($sql, 4, 0);
	    if ($ret[0]) {
	      $ret[1]="Fehler bei SQL Anweisung:<br>".$sql."<br>".pg_result_error($query);
	    }
	    else {
		    $rs=pg_fetch_array($ret[1]);
		    $ret[1]=$rs;
	    }
		} # end Abfrage aus Postgres Datenbank
    return $ret;
  }
 
  function listClasses($startclassid) {
  	# Fragt rekursiv alle Klassen ab beginnend mit id $startclassid und gibt die Namen mit ids aus
  	$ret=$this->getClasses('','',$startclassid,'classname');
  	if ($ret[0]) {
  		echo 'Fehler beim Abfragen der ALKIS Klassen: '.$ret[1];
  	}
  	else {
  		$classes=$ret[1];
	  	for ($i=0; $i<count($classes); $i++) {
    		echo '<div id="class_'.$classes[$i]['classid'].'" class="class">'."\n";
	  		echo '- Klasse: <a name="classanchor_'.$classes[$i]['classid'].'">'.$classes[$i]['classname']."</a> (".$classes[$i]['classid'].")\n";
	  		$this->listAttributes($classes[$i]['classid']);
  			$this->listClasses($classes[$i]['classid']);
  	  	echo '</div>'."\n";
	  	}
  	}
  }
  
  function listAttributes($classid) {
		$ret=$this->getAttributes($classid,'attid');
		if ($ret[0]) {
			echo 'Fehler beim Abfragen der ALKIS Klassenattribute: '.$ret[1];
		}
		else {
			$attributes=$ret[1];
			echo '<div id="classattrib_'.$classid.'" class="classattrib">'."\n";
  		for ($j=0; $j<count($attributes); $j++) {
  			if ($j>0) {
  				echo '<br>';
  			}
  			switch (true) {
  				# Attribut vom Tye einer anderen Klasse (Verweis auf die Klasse)
	  			case ($attributes[$j]['type']>0) : {
	  				echo '- Attribut: '.$attributes[$j]['name']." (".$attributes[$j]['attid'].")";
	 					$ret3=$this->getClasses($attributes[$j]['type'],'','','');
	 					if ($ret3[0]) {
	 						echo 'Fehler beim Abfragen der ALKIS Typenklassen: '.$ret[1];
	 					}
	 					else {
	 						$typeclass=$ret3[1][0];
	 						echo ' vom Typ: <a href="#classanchor_'.$typeclass['classid'].'">'.$typeclass['classname'].'</a> ('.$typeclass['classid'].')';
	 					}
	 				} break;
	 				# Relation zu einer anderen Klasse (Verweis auf die Klasse)
	 				case ($attributes[$j]['maxoccurs']=='0' AND $attributes[$j]['writeable']=='1') : {
	  				echo '- Relation: '.$attributes[$j]['name']." (".$attributes[$j]['attid'].")";
	 					$ret2=$this->getClassRelations($classid,$attributes[$j]['name']);
	 					if ($ret2[0]) {
							echo 'Fehler beim Abfragen der ALKIS Klassenrelationen: '.$ret[1];
	 					}
	 					else {
	 						$relclass=$ret2[1];
	 						echo ' zu: <a href="#classanchor_'.$relclass['classid'].'">'.$relclass['classname'].'</a> ('.$relclass['classid'].')';
	 					}
	 				} break;
	 				# Attribut anderer einfacher Datentypen
	 				default : {
	 					echo '- Attribut: '.$attributes[$j]['name']." (".$attributes[$j]['attid'].")";
	 				}
  			}
  		}
  		echo '</div>'."\n";
		}
  }
}
?>
