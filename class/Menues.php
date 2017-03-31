<?php
###########################################
# Klasse zum Menüoptionen zusammenstellen #
###########################################
# Klasse Menue #
################

# functions of class menue
# load Menue -- load all menue items according to the stelle

class Menues {
  var $html;
  var $debug;

  ###################### Liste der Funktionen ####################################
  #
  # function Menue () - Construktor
  # function loadMenue ($Stelle_ID)
  # function getallMenues()
  #
  ################################################################################

  function menues ($language){
    global $debug;
    $this->debug=$debug;
    $this->language=$language;
  }

	function loadMenue($Stelle_ID, $User_ID) {
		$this->stelle_id = $Stelle_ID;
		$this->user_id = $User_ID;
		$sql = "
			SELECT
				status,
				m.id,
				m.links,
				name as name_german," .
				($this->language != 'german' ? "`name_" . $this->language . "` AS" : "") . " name,
				m.menueebene,
				m.obermenue,
				m.target,
				m.title
			FROM
				u_menue2rolle m2r JOIN
				u_menue2stelle AS m2s ON (m2r.stelle_id = m2s.stelle_id AND m2r.menue_id = m2s.menue_id) JOIN
				u_menues AS m ON (m2s.menue_id = m.id)
			WHERE
				m2s.stelle_id = " . $Stelle_ID . " AND
				m2r.user_id = " . $User_ID . "
			ORDER BY
				m2s.menue_order
		";

		#echo 'SQL: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:Menue - Lesen der Menüangaben:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) {

		}
		else {
			while($rs=mysql_fetch_array($query)) {
				$this->Menueoption[]=$rs;
			}
		}
	}

  function get_menue_width($Stelle_ID){
    $sql ='SELECT r.width FROM referenzkarten AS r, stelle AS s WHERE r.ID=s.Referenzkarte_ID';
    $sql.=' AND s.ID='.$Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:Menue->get_menue_width - Lesen der Menuebreite:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_row($query);
    $this->width = $rs[0];
  }

	function get_all_ober_menues() {
		$sql .= "
			SELECT
				m.id,
				m.name" . ($this->language != 'german' ? '_' . $this->language . ' AS name' : '') . ",
				m.order,
				m.menueebene,
				m2r.status
			FROM
				u_menues m join
				u_menue2rolle m2r ON (m.id = m2r.menue_id AND m2r.stelle_id = " . $this->stelle_id . " AND m2r.user_id = " . $this->user_id . ") 
			WHERE
				m.menueebene = 1
			ORDER BY
				m.order
		";
		#echo 'sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:Menue - Lesen aller OberMenüs:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) {
			echo 'error';
		}
		else {
			while($rs = mysql_fetch_assoc($query)) {
				$menues['ID'][] = $rs['id'];
				$menues['Bezeichnung'][] = $rs['name'];
				$menues['ORDER'][] = $rs['order'];
				$menues['menueebene'][] = $rs['menueebene'];
				$menues['status'][] = $rs['status'];
			}
			return $menues;
		}
	}

  function getsubmenues($menue_id){
    $sql.='SELECT id,';
    if ($this->language != 'german') {
      $sql.='`name_'.$this->language.'` AS ';
    }
    $sql.=' name, `order`, menueebene FROM u_menues WHERE obermenue = '.$menue_id.' AND menueebene = 2 ORDER BY `order`, name';
    $this->debug->write("<p>file:kvwmap class:Menue - Lesen aller OberMenüs:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) {

    }
    else {
    while($rs=mysql_fetch_array($query)) {
          $menues['ID'][]=$rs['id'];
          $menues['Bezeichnung'][]=$rs['name'];
		  $menues['ORDER'][]=$rs['order'];
		  $menues['menueebene'][]=$rs['menueebene'];
      }
      return $menues;
    }
  }

}

class point {
  var $x;
  var $y;

  function point($x,$y) {
    $this->x=$x;
    $this->y=$y;
  }

  function pixel2welt($minX,$minY,$pixSize) {
    # Rechnet Pixel- in Weltkoordinaten um mit minx, miny und pixsize
    $this->x=($this->x*$pixSize)+$minX;
    $this->y=($this->y*$pixSize)+$minY;
  }

  function welt2pixel($minX,$minY,$pixSize) {
    # Rechnet Welt- in Pixelkoordinaten um mit minx, miny und pixsize
    $this->x=round(($this->x-$minX)/$pixSize);
    $this->y=round(($this->y-$minY)/$pixSize);
  }
}
?>