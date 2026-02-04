<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2004  Peter Korduan                               #
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
# users.php Klassenbibliothek zu Nutzern und Stellen #
######################################################
# Copyright (C) 2004  Peter Korduan
# This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
#
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
#
############################################################################################
# Klassen in dieser Datei
# account
# user

##############################
# class_account
class account {
	# Klasse für die Abrechnung und Statistik von Zugriffen

	var $database;
	var $debug;

	function __construct($database) {
		global $debug;
		$this->debug = $debug;
		$this->database = $database;
	}

	function getStatistik($nutzer,$nutzung,$stelle,$zeitraum,$day_d,$week_w,$month_d,$month_w,$month_m,$year_m,$year_w,$year_d,$day_e1,$day_e2,$month_e1,$month_e2,$year_e1,$year_e2) {
			# Ausführen der einzelnen Funktionen für
			# die Statistik zur Anfrage
			if($stelle != ''){
				$BezeichnungStelle = new stelle($stelle,$this->database);
			}
			if($nutzer != ''){
				$UserName = new user('',$nutzer,$this->database);
				$this->UName=$UserName->getUserDaten($nutzer,'','');
			}

			$this->epoch=$this->epoch();
			#$this->getLayer=$this->getLayer('');
			#$this->getLoggedLayer=$this->getLayer(1);

			$day_d=str_pad($day_d, 2, "0", STR_PAD_LEFT);
			$month_d=str_pad($month_d, 2, "0", STR_PAD_LEFT);
			$month_e1=str_pad($month_e1, 2, "0", STR_PAD_LEFT);
			$month_e2=str_pad($month_e2, 2, "0", STR_PAD_LEFT);
			$day_e1=str_pad($day_e1, 2, "0", STR_PAD_LEFT);
			$day_e2=str_pad($day_e2, 2, "0", STR_PAD_LEFT);
			$date1=$year_e1.'-'.$month_e1.'-'.$day_e1;
			$date2=$year_e2.'-'.$month_e2.'-'.$day_e2 ;

			if ($zeitraum=='month') {
				if ($nutzung=='stelle'){
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id', "date_part('month', ",$month_m, $year_m,$stelle,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id', "date_part('month', ", $month_m, $year_m,$stelle,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id', "date_part('month', ", $month_m, $year_m,$stelle,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id', "date_part('month', ", $month_m, $year_m,$stelle,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id', "date_part('month', ", $month_m, $year_m,$stelle,'');
				}
				if ($nutzung=='nutzer') {
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.user_id', "date_part('month', ", $month_m,$year_m,$nutzer,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.user_id', "date_part('month', ", $month_m,$year_m,$nutzer,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.user_id', "date_part('month', ", $month_m, $year_m,$nutzer,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.user_id', "date_part('month', ", $month_m, $year_m,$nutzer,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.user_id', "date_part('month', ", $month_m, $year_m,$nutzer,'');
				}
				if ($nutzung=='stelle_nutzer') {
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id, c.user_id', "date_part('month', ", $month_m,$year_m,$stelle,$nutzer);
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id, u_consumeALK.user_id', "date_part('month', ", $month_m,$year_m,$stelle,$nutzer);
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id, u_consumeALB.user_id', "date_part('month', ", $month_m,$year_m,$stelle,$nutzer);
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id, u_consumeCSV.user_id', "date_part('month', ", $month_m,$year_m,$stelle,$nutzer);
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id, u_consumeShape.user_id', "date_part('month', ", $month_m, $year_m,$stelle,$nutzer);
				}
			}
			if ( $zeitraum=='week' ) {
				if ($nutzung=='stelle') {
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id','WEEK',$week_w,$year_w,$stelle,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id','WEEK',$week_w,$year_w,$stelle,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id','WEEK',$week_w, $year_w,$stelle,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id','WEEK',$week_w, $year_w,$stelle,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id','WEEK',$week_w, $year_w,$stelle,'');
				}
				if ($nutzung=='nutzer') {
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.user_id','WEEK',$week_w,$year_w,$nutzer,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.user_id','WEEK',$week_w,$year_w,$nutzer,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.user_id','WEEK',$week_w,$year_w,$nutzer,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.user_id','WEEK',$week_w, $year_w,$nutzer,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.user_id','WEEK',$week_w, $year_w,$nutzer,'');
				}
				if ($nutzung=='stelle_nutzer') {
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id, c.user_id','WEEK',$week_w,$year_w,$stelle,$nutzer);
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id, u_consumeALK.user_id','WEEK',$week_w,$year_w,$stelle,$nutzer);
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id, u_consumeALB.user_id','WEEK',$week_w,$year_w,$stelle,$nutzer);
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id, u_consumeCSV.user_id','WEEK',$week_w,$year_w,$stelle,$nutzer);
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id, u_consumeShape.user_id','WEEK',$week_w,$year_w,$stelle,$nutzer);
				}
			}
			if ( $zeitraum=='day') {
				if ($nutzung=='stelle') {
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,'');
				}
				if ($nutzung=='nutzer') {
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
				}
				if ($nutzung=='stelle_nutzer'){
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id, c.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,$nutzer);
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id, u_consumeALK.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,$nutzer);
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id, u_consumeALB.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,$nutzer);
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id, u_consumeCSV.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,$nutzer);
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id, u_consumeShape.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$stelle,$nutzer);
				}
			}
			if ( $zeitraum=='era') {
				if ($nutzung=='stelle') {
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id','DATE','','',$stelle,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id','DATE','','',$stelle,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id','DATE','','',$stelle,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id','DATE','','',$stelle,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id','DATE','','',$stelle,'');
				}
				if ($nutzung=='nutzer') {
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.user_id','DATE','','',$nutzer,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.user_id','DATE','','',$nutzer,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.user_id','DATE','','',$nutzer,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.user_id','DATE','','',$nutzer,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.user_id','DATE','','',$nutzer,'');
				}
				if ($nutzung=='stelle_nutzer'){
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id, c.user_id','DATE','','',$stelle,$nutzer);
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id, u_consumeALK.user_id','DATE','','',$stelle,$nutzer);
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id, u_consumeALB.user_id','DATE','','',$stelle,$nutzer);
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id, u_consumeCSV.user_id','DATE','','',$stelle,$nutzer);
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id, u_consumeShape.user_id','DATE','','',$stelle,$nutzer);
				}
			}
			return $this->NumbOfAccess;
	} # END of function getStatistik

	function getAccessToLayer($nutzung,$zeitraum,$date1,$date2,$case,$era,$date,$year,$id,$id_2){
		# Abfrage der Anzahl der Zugriffe auf die Layer
		# in Abhängigkeit von Stelle, Nutzer und Zeitraums
		$sql ='SELECT c2l.layer_id,'.$case.',count(c.time_id) AS NumberOfAccess,l.Name AS lName';
		if ($nutzung=='stelle') {
			$sql.=' ,s.Bezeichnung';
		}
		if ($nutzung=='nutzer') {
			$sql.=' ,u.Name';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,s.Bezeichnung, u.Name';
		}
		$sql.=' FROM kvwmap.u_consume AS c, kvwmap.u_consume2layer AS c2l, kvwmap.layer AS l';
		if ($nutzung=='stelle'){
			$sql.=' , kvwmap.stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , kvwmap.user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' , kvwmap.stelle AS s, kvwmap.user AS u';
		}
		$sql.=' WHERE c.user_id=c2l.user_id';
		$sql.=' AND c2l.layer_id = l.layer_id';
		$sql.=' AND c.time_id=c2l.time_id';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND ' . $era . 'c.time_id)=' . $date . " AND date_part('year', c.time_id) = " . $year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'c.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(c.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}

		if ($case=='c.stelle_id'){
			$sql.=' AND c.stelle_id=s.ID AND s.ID='.$id;
			$sql.=' GROUP BY c2l.layer_id, c.stelle_id, l.name, s.bezeichnung ORDER BY Name';
		}
		if ($case=='c.user_id') {
			$sql.=' AND c.user_id=u.ID AND u.ID='.$id;
			$sql.=' GROUP BY c2l.layer_id, c.user_id ORDER BY Name';
		}
		if ($case=='c.stelle_id, c.user_id'){
			$sql.=' AND c.stelle_id=s.ID AND s.ID='.$id;
			$sql.=' AND c.user_id=u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY c2l.layer_id, c.stelle_id, c.user_id ORDER BY Bezeichnung,lName,Name';
		}
		$this->debug->write("<p>file:kvwmap class:account->getNumber_of_Access_to_Layer:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
		while ($rs = pg_fetch_array($ret[1])) {
			$NumbOfAccessUser[]=$rs;
		}
		return $NumbOfAccessUser;
	} #END of function getAccessToLayer

	function getAccessToCSV($nutzung,$zeitraum,$date1,$date2,$case,$era,$date,$year,$id,$id_2){
		# Abfrage der Anzahl der ALB-CSV-Exporte
		# in Abhängigkeit von Stelle, Nutzer und Zeitraum
		$sql ='SELECT u_consumeCSV.art,'.$case.',count(u_consumeCSV.time_id) AS NumberOfAccess, sum(numdatasets) as datasets';
		if ($nutzung=='stelle') {
			$sql.=' ,s.Bezeichnung';
		}
		if ($nutzung=='nutzer') {
			$sql.=' ,u.Name';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,s.Bezeichnung, u.Name AS Username';
		}
		$sql.=' FROM kvwmap.u_consumeCSV';
		if ($nutzung=='stelle'){
			$sql.=' , kvwmap.stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , kvwmap.user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' , kvwmap.stelle AS s, kvwmap.user AS u';
		}
		$sql.=' WHERE (1=1)';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'u_consumeCSV.time_id)='.$date." AND date_part('year', u_consumeCSV.time_id) = ".$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'u_consumeCSV.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(u_consumeCSV.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}

		if ($case=='u_consumeCSV.stelle_id'){
			$sql.=' AND u_consumeCSV.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' GROUP BY u_consumeCSV.art,u_consumeCSV.stelle_id';
		}
		if ($case=='u_consumeCSV.user_id') {
			$sql.=' AND u_consumeCSV.user_id = u.ID AND u.ID='.$id;
			$sql.=' GROUP BY u_consumeCSV.art,u_consumeCSV.user_id';
		}
		if ($case=='u_consumeCSV.stelle_id, u_consumeCSV.user_id'){
			$sql.=' AND u_consumeCSV.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' AND u_consumeCSV.user_id = u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY u_consumeCSV.art,u_consumeCSV.stelle_id,u_consumeCSV.user_id';
		}
		if ($nutzung=='stelle') {
			$sql.=' ,s.Bezeichnung';
		}
		if ($nutzung=='nutzer') {
			$sql.=' ,u.Name';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,s.Bezeichnung, u.Name';
		}
		$sql .= ' ORDER BY art';
		$this->debug->write("<p>file:kvwmap class:account->getAccessToCSV:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
		while ($rs = pg_fetch_array($ret[1])) {
			$NumbOfAccess[]=$rs;
			$sql ='SELECT u_consumeCSV.time_id, concat(u.vorname, \' \', u.name) as Name';
			$sql.=' FROM kvwmap.user AS u, kvwmap.stelle AS s, kvwmap.u_consumeCSV';
			$sql.=' WHERE 1=1';
			if ($zeitraum=='month' OR $zeitraum=='week')  {
				$sql.=' AND '.$era.'u_consumeCSV.time_id)='.$date." AND date_part('year', u_consumeCSV.time_id) = " . $year;
			}
			if ($zeitraum=='day'){
				$sql.=' AND ('.$era.'u_consumeCSV.time_id))="'.$date.'"';
			}
			if ($zeitraum=='era') {
				$sql.=' AND ((DATE(u_consumeCSV.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
			}
			$sql.=' AND u_consumeCSV.stelle_id = s.ID';
			$sql.=' AND u_consumeCSV.user_id = u.ID';
			$sql.= ' AND art = \'' . $rs['art'] . '\'';
			if($rs['stelle_id'] != ''){
				$sql.= ' AND u_consumeCSV.stelle_id='.$rs['stelle_id'];
			}
			if($rs['user_id'] != ''){
				$sql.= ' AND u_consumeCSV.user_id='.$rs['user_id'];
			}
			#echo $sql.'<br><br>';
			$this->debug->write("<p>file:kvwmap class:account->getAccessToCSV:<br>".$sql,4);
			$ret = $this->database->execSQL($sql);
			if (!$this->database->success) { echo "<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
			$NumbOfAccessTimeIDs = array();
			while ($rs = pg_fetch_array($ret[1])) {
				$NumbOfAccessTimeIDs[] = $rs;
			}
			$NumbOfAccess[count($NumbOfAccess)-1]['time_ids'] = $NumbOfAccessTimeIDs;
		}
		return $NumbOfAccess;
	} #END of function getAccessToCSV

	function getAccessToShape($nutzung,$zeitraum,$date1,$date2,$case,$era,$date,$year,$id,$id_2){
		# Abfrage der Anzahl der Shape-Exporte
		# in Abhängigkeit von Stelle, Nutzer und Zeitraum
		$sql ='SELECT u_consumeShape.layer_id,'.$case.',count(u_consumeShape.time_id) AS NumberOfAccess, sum(numdatasets) as datasets';
		if ($nutzung=='stelle') {
			$sql.=' ,s.Bezeichnung';
		}
		if ($nutzung=='nutzer') {
			$sql.=' ,u.Name';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,s.Bezeichnung, u.Name AS Username';
		}
		$sql.=' FROM kvwmap.u_consumeShape';
		if ($nutzung=='stelle'){
			$sql.=' , kvwmap.stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , kvwmap.user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' , kvwmap.stelle AS s, kvwmap.user AS u';
		}
		$sql.=' WHERE (1=1)';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'u_consumeShape.time_id)='.$date . " AND date_part('year', u_consumeShape.time_id) = " .$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'u_consumeShape.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(u_consumeShape.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}

		if ($case=='u_consumeShape.stelle_id'){
			$sql.=' AND u_consumeShape.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' GROUP BY u_consumeShape.layer_id,u_consumeShape.stelle_id';
		}
		if ($case=='u_consumeShape.user_id') {
			$sql.=' AND u_consumeShape.user_id = u.ID AND u.ID='.$id;
			$sql.=' GROUP BY u_consumeShape.layer_id,u_consumeShape.user_id';
		}
		if ($case=='u_consumeShape.stelle_id, u_consumeShape.user_id'){
			$sql.=' AND u_consumeShape.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' AND u_consumeShape.user_id = u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY u_consumeShape.layer_id,u_consumeShape.stelle_id,u_consumeShape.user_id';
		}
		if ($nutzung=='stelle') {
			$sql.=' ,s.Bezeichnung';
		}
		if ($nutzung=='nutzer') {
			$sql.=' ,u.Name';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,s.Bezeichnung, u.Name';
		}
		$sql .= ' ORDER BY layer_id';
		$this->debug->write("<p>file:kvwmap class:account->getAccessToShape:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
		while ($rs = pg_fetch_array($ret[1])) {
			$NumbOfAccess[]=$rs;
			$sql ='SELECT u_consumeShape.time_id, concat(u.Vorname, \' \', u.Name) as Name';
			$sql.=' FROM kvwmap.user AS u, kvwmap.stelle AS s, kvwmap.u_consumeShape';
			$sql.=' WHERE 1=1';
			if ($zeitraum=='month' OR $zeitraum=='week')  {
				$sql.=' AND '.$era.'u_consumeShape.time_id)='.$date . " AND date_part('year', u_consumeShape.time_id) = " .$year;
			}
			if ($zeitraum=='day'){
				$sql.=' AND ('.$era.'u_consumeShape.time_id))="'.$date.'"';
			}
			if ($zeitraum=='era') {
				$sql.=' AND ((DATE(u_consumeShape.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
			}
			$sql.=' AND u_consumeShape.stelle_id = s.ID';
			$sql.=' AND u_consumeShape.user_id = u.ID';
			$sql.= ' AND u_consumeShape.layer_id = ' . $rs['layer_id'];
			if($rs['stelle_id'] != ''){
				$sql.= ' AND u_consumeShape.stelle_id='.$rs['stelle_id'];
			}
			if($rs['user_id'] != ''){
				$sql.= ' AND u_consumeShape.user_id='.$rs['user_id'];
			}
			#echo $sql.'<br><br>';
			$this->debug->write("<p>file:kvwmap class:account->getAccessToShape:<br>".$sql,4);
			$ret = $this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
			$NumbOfAccessTimeIDs = array();
			while ($rs = pg_fetch_array($ret[1])) {
				$NumbOfAccessTimeIDs[]=$rs;
			}
			$NumbOfAccess[count($NumbOfAccess)-1]['time_ids'] = $NumbOfAccessTimeIDs;
		}
		return $NumbOfAccess;
	} #END of function getAccessToShape

	function getAccessToALB($nutzung,$zeitraum,$date1,$date2,$case,$era,$date,$year,$id,$id_2){
		# Abfrage der Anzahl der ALB-PDF-Exporte
		# in Abhängigkeit von Stelle, Nutzer und Zeitraum
		$sql ='SELECT u_consumeALB.format,'.$case.',count(u_consumeALB.time_id) AS NumberOfAccess, sum(numpages) as pages';
		if ($nutzung=='stelle') {
			$sql.=' ,s.Bezeichnung';
		}
		if ($nutzung=='nutzer') {
			$sql.=' ,u.Name';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,s.Bezeichnung, u.Name AS Username';
		}
		$sql.=' FROM kvwmap.u_consumeALB';
		if ($nutzung=='stelle'){
			$sql.=' , kvwmap.stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , kvwmap.user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' , kvwmap.stelle AS s, kvwmap.user AS u';
		}
		$sql.=' WHERE (1=1)';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'u_consumeALB.time_id)='.$date . " AND date_part('year', u_consumeALB.time_id) = " .$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'u_consumeALB.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(u_consumeALB.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}

		if ($case=='u_consumeALB.stelle_id'){
			$sql.=' AND u_consumeALB.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' GROUP BY u_consumeALB.format,u_consumeALB.stelle_id ';
		}
		if ($case=='u_consumeALB.user_id') {
			$sql.=' AND u_consumeALB.user_id = u.ID AND u.ID='.$id;
			$sql.=' GROUP BY u_consumeALB.format,u_consumeALB.user_id ';
		}
		if ($case=='u_consumeALB.stelle_id, u_consumeALB.user_id'){
			$sql.=' AND u_consumeALB.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' AND u_consumeALB.user_id = u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY u_consumeALB.format,u_consumeALB.stelle_id,u_consumeALB.user_id ';
		}
		if ($nutzung=='stelle') {
			$sql.=' ,s.Bezeichnung';
		}
		if ($nutzung=='nutzer') {
			$sql.=' ,u.Name';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,s.Bezeichnung, u.Name';
		}
		$sql .= ' ORDER BY format';
		$this->debug->write("<p>file:kvwmap class:account->getAccessToALB:<br>".$sql,4);
		#echo $sql.'<br><br>';
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
		while ($rs = pg_fetch_array($ret[1])) {
			$NumbOfAccess[]=$rs;
			$sql ='SELECT u_consumeALB.time_id, concat(u.vorname, \' \', u.name) as Name';
			$sql.=' FROM kvwmap.user AS u, kvwmap.stelle AS s, kvwmap.u_consumeALB';
			if(LAYER_IDS_DOP) $sql.=' LEFT JOIN kvwmap.u_consume2layer c2l LEFT JOIN kvwmap.layer l ON l.layer_id = c2l.layer_id ON c2l.time_id = u_consumeALB.time_id AND c2l.user_id = u_consumeALB.user_id AND c2l.stelle_id = u_consumeALB.stelle_id AND c2l.layer_id IN ('.LAYER_IDS_DOP.')';
			$sql.=' WHERE 1=1';
			if ($zeitraum=='month' OR $zeitraum=='week')  {
				$sql.=' AND '.$era.'u_consumeALB.time_id)='.$date . " AND date_part('year', u_consumeALB.time_id) = " .$year;
			}
			if ($zeitraum=='day'){
				$sql.=' AND ('.$era.'u_consumeALB.time_id))="'.$date.'"';
			}
			if ($zeitraum=='era') {
				$sql.=' AND ((DATE(u_consumeALB.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
			}
			$sql.=' AND u_consumeALB.stelle_id = s.ID';
			$sql.=' AND u_consumeALB.user_id = u.ID';
			$sql.= " AND format='".$rs['format']."'";
			if($rs['stelle_id'] != ''){
				$sql.= ' AND u_consumeALB.stelle_id='.$rs['stelle_id'];
			}
			if($rs['user_id'] != ''){
				$sql.= ' AND u_consumeALB.user_id='.$rs['user_id'];
			}
			if($rs['layer_id'] != '') $sql.= ' AND c2l.layer_id='.$rs['layer_id'];
			#echo $sql.'<br><br>';
			$this->debug->write("<p>file:kvwmap class:account->getAccessToALB:<br>".$sql,4);

			$this->debug->write("<p>file:kvwmap class:account->getAccessToALB:<br>".$sql,4);
			$ret = $this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
			$NumbOfAccessTimeIDs = array();
			while ($rs = pg_fetch_array($ret[1])) {
				$NumbOfAccessTimeIDs[] = $rs;
			}
			$NumbOfAccess[count($NumbOfAccess)-1]['time_ids'] = $NumbOfAccessTimeIDs;
		}
		return $NumbOfAccess;
	} #END of function getAccessToALB

	function getAccessToALK($nutzung,$zeitraum,$date1,$date2,$case,$era,$date,$year,$id,$id_2){
		# Abfrage der Anzahl der PDF-Exporte mit Druckrahmen
		# in Abhängigkeit von Stelle, Nutzer und Zeitraum
		$sql ='SELECT u_consumeALK.druckrahmen_id,'.$case.',count(u_consumeALK.time_id) AS NumberOfAccess,';
		if(LAYER_IDS_DOP) $sql.='c2l.layer_id, concat(druckrahmen.Name, \' \',l.Name) AS druckrahmenname';
		else $sql.='druckrahmen.Name AS druckrahmenname';
		$sql.=', druckrahmen.format AS Druckformat, druckrahmen.preis AS Preis';
		if ($nutzung=='stelle') {
			$sql.=' ,s.Bezeichnung';
		}
		if ($nutzung=='nutzer') {
			$sql.=' ,u.Name';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,s.Bezeichnung, u.Name AS Username';
		}
		$sql.=' FROM kvwmap.druckrahmen';
		if ($nutzung=='stelle'){
			$sql.=' , kvwmap.stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , kvwmap.user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' , kvwmap.stelle AS s, kvwmap.user AS u';
		}
		$sql.=', kvwmap.u_consumeALK';
		if(LAYER_IDS_DOP) {
			$sql.= " 
				LEFT JOIN kvwmap.u_consume2layer c2l ON c2l.time_id = u_consumeALK.time_id AND c2l.user_id = u_consumeALK.user_id AND c2l.stelle_id = u_consumeALK.stelle_id AND c2l.layer_id IN (" . LAYER_IDS_DOP . ")
				LEFT JOIN kvwmap.layer l ON l.layer_id = c2l.layer_id ";
		}
		$sql.=' WHERE u_consumeALK.druckrahmen_id = druckrahmen.id';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'u_consumeALK.time_id)='.$date . " AND date_part('year', u_consumeALK.time_id) = " .$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'u_consumeALK.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(u_consumeALK.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}
		if(LAYER_IDS_DOP) $groupby = 'c2l.layer_id, ';
		if ($case=='u_consumeALK.stelle_id'){
			$sql.=' AND u_consumeALK.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' GROUP BY ' . $groupby . 'u_consumeALK.druckrahmen_id,u_consumeALK.stelle_id, druckrahmen.Name, l.name, druckrahmen.format, druckrahmen.preis, s.bezeichnung ORDER BY druckrahmen.Name';
		}
		if ($case=='u_consumeALK.user_id') {
			$sql.=' AND u_consumeALK.user_id = u.ID AND u.ID='.$id;
			$sql.=' GROUP BY ' . $groupby . 'u_consumeALK.druckrahmen_id,u_consumeALK.user_id, druckrahmen.Name, l.name, druckrahmen.format, druckrahmen.preis, s.bezeichnung ORDER BY druckrahmen.Name';
		}
		if ($case=='u_consumeALK.stelle_id, u_consumeALK.user_id'){
			$sql.=' AND u_consumeALK.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' AND u_consumeALK.user_id = u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY ' . $groupby . 'u_consumeALK.druckrahmen_id,u_consumeALK.stelle_id,u_consumeALK.user_id, druckrahmen.Name, l.name, druckrahmen.format, druckrahmen.preis, s.bezeichnung  ORDER BY Bezeichnung,druckrahmen.Name,u.Name';
		}
		#echo $sql.'<br><br>';
		$this->debug->write("<p>file:kvwmap class:account->getAccessToALK:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { echo $sql;exit;$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
		while ($rs = pg_fetch_array($ret[1])) {
			$NumbOfAccess[]=$rs;
			$sql ='SELECT u_consumeALK.time_id, concat(u.Vorname, \' \', u.Name) as Name';
			$sql.=' FROM kvwmap.druckrahmen, kvwmap.user AS u, kvwmap.stelle AS s, kvwmap.u_consumeALK';
			if(LAYER_IDS_DOP) $sql.=' LEFT JOIN kvwmap.u_consume2layer c2l LEFT JOIN kvwmap.layer l ON l.layer_id = c2l.layer_id ON c2l.time_id = u_consumeALK.time_id AND c2l.user_id = u_consumeALK.user_id AND c2l.stelle_id = u_consumeALK.stelle_id AND c2l.layer_id IN ('.LAYER_IDS_DOP.')';
			$sql.=' WHERE u_consumeALK.druckrahmen_id = druckrahmen.id';
			if ($zeitraum=='month' OR $zeitraum=='week')  {
				$sql.=' AND '.$era.'u_consumeALK.time_id)='.$date . " AND date_part('year', u_consumeALK.time_id) = " .$year;
			}
			if ($zeitraum=='day'){
				$sql.=' AND ('.$era.'u_consumeALK.time_id))="'.$date.'"';
			}
			if ($zeitraum=='era') {
				$sql.=' AND ((DATE(u_consumeALK.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
			}
			$sql.=' AND u_consumeALK.stelle_id = s.ID';
			$sql.=' AND u_consumeALK.user_id = u.ID';
			$sql.= ' AND druckrahmen.id='.$rs['druckrahmen_id'];
			if($rs['stelle_id'] != ''){
				$sql.= ' AND u_consumeALK.stelle_id='.$rs['stelle_id'];
			}
			if($rs['user_id'] != ''){
				$sql.= ' AND u_consumeALK.user_id='.$rs['user_id'];
			}
			if($rs['layer_id'] != '') $sql.= ' AND c2l.layer_id='.$rs['layer_id'];
			#echo $sql.'<br><br>';
			$this->debug->write("<p>file:kvwmap class:account->getAccessToALK:<br>".$sql,4);
			$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
			$NumbOfAccessTimeIDs = array();
			while ($rs = pg_fetch_array($ret[1])) {
				$NumbOfAccessTimeIDs[] = $rs;
			}
			$NumbOfAccess[count($NumbOfAccess)-1]['time_ids'] = $NumbOfAccessTimeIDs;
		}
		return $NumbOfAccess;
	} #END of function getAccessToALK

	function getLayer($logged) {
		# Abfrage der Anzahl der Layer
		$sql = "
			SELECT
				COUNT(layer_id) AS layers
			FROM
				kvwmap.layer
			WHERE
				" . ($logged ? "logconsume = '1'" : "true") . "
		";
		$this->debug->write("<p>file:users.php class:user->getLayer:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
		$rs = pg_fetch_array($ret[1]);
		$this->AnzLayer = $rs;
		return $this->AnzLayer;
	} # END of function getLayer

	function epoch(){
		# Abfragen, für welchen Zeitraum die statistische Abfrage möglich ist
		$sql = "
			SELECT 
				date_part('day', MIN(time_id)) AS min_d, 
				date_part('month', MIN(time_id)) AS min_m, 
				date_part('year', MIN(time_id)) AS min_y , 
				date_part('day', MAX(time_id)) AS max_d, 
				date_part('month', MAX(time_id)) AS max_m, 
				date_part('year', MAX(time_id)) AS max_y
		FROM 
			kvwmap.u_consume2layer";
		$this->debug->write("<p>file:kvwmap class:account->getNumber_of_Access_to_Layer:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0; }
		while ($rs = pg_fetch_array($ret[1])) {
			$epoch['min_d']=$rs['min_d'];
			$epoch['min_m']=$rs['min_m'];
			$epoch['min_y']=$rs['min_y'];
			$epoch['max_d']=$rs['max_d'];
			$epoch['max_m']=$rs['max_m'];
			$epoch['max_y']=$rs['max_y'];
		}
		return $epoch;
	} # END of function epoch

} # END of class_account

#########################
# class_user #
class user {
	# // TODO: Beim Anlegen eines neuen Benutzers müssen die Einstellungen für die Karte aus der Stellenbeschreibung als Anfangswerte übernommen werden

	var $id;
	var $Name;
	var $Vorname;
	var $login_name;
	var $funktion;
	var $dbConn; # Datenbankverbindungskennung
	var $Stellen;
	var $nZoomFactor;
	var $nImageWidth;
	var $nImageHeight;
	var $database;
	var $remote_addr;
	var $has_logged_in;
	var $language = 'german';
	var $debug;
	var $share_rollenlayer_allowed;
	var $stelle_id;

	/**
	 * Create a user object
	 * if only login_name is defined, find_by login_name only
	 * if login_name and password is defined, find_by login_name and password
	*/
	function __construct($login_name, $id, $database, $password = '', $archived = false) {
		global $debug;
		$this->debug = $debug;
		$this->database = $database;
		$this->has_logged_in = false;
		$this->login_name = $login_name;
		$this->id = (int) $id;
		$this->remote_addr = getenv('REMOTE_ADDR');
		$this->readUserDaten($this->id, $this->login_name, $password, $archived);
	}

	public static	function find($gui, $where, $order = '', $sort_direction = '') {
		$user = new PgObject($gui, 'kvwmap' ,'user');
		return $user->find_where($where, $order, $sort_direction);
	}

	/*
	* Function return true if the case is for the user allowed to execute
	* Currently only the following cases will be tested against the rights of the user
	* 	- share_rollenlayer
	* This method works independently from the functions isMenueAllowed AND isFunctionAllowed.
	* It means, when these other functions would return false this function can
	* nevertheless return true. It cares not on the outher case permissions
	* @param $case The name of the use case, normaly used in switch from index.php
	* @return true if case is allowed else false
	*/
	function is_case_allowed($case) {
		if ($case == 'share_rollenlayer' AND $this->share_rollenlayer_allowed) {
			return true;
		}
		elseif (
			in_array($case, array('Attributeditor', 'Layereditor', 'Klasseneditor', 'Style_Label_Editor', 'Layerattribut-Rechteverwaltung')) AND
			$this->database->gui->formvars['selected_layer_id'] != '' AND
			$this->layerIsSharedFrom($this->database->gui->formvars['selected_layer_id'], $this->id)
		) {
			return true;
		}
		elseif (
			$case == 'Layereditor' AND
			$this->database->gui->go == 'Klasseneditor'
		) {
			return true;
		}
		else {
			return false;
		}
	}

	function layerIsSharedFrom($layer_id, $user_id) {
		$sql = "
			SELECT
				shared_from
			FROM
				kvwmap.layer
			WHERE
				layer_id = " . $layer_id . " AND
				shared_from = " . $user_id . "
		";
		$this->debug->write("<p>file:users.php class:user->layerIsSharedFrom:<br>" . $sql, 4);
		#echo '<br>sql zur Abfrage der Layer: ' . $sql;
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . INFO1 . "<p>", 4); return 0;
		}
		return (pg_num_rows($ret[1]) > 0);
	}

	function clientIpIsValide($remote_addr) {
    # Prüfen ob die übergebene IP Adresse zu den für den Nutzer eingetragenen Adressen passt
    $ips=explode(';',$this->ips);
    foreach ($ips AS $ip) {
      if (trim($ip)!='') {
        $ip=trim($ip);
				if (!is_numeric(explode('.', $ip)[0])) {
					$ip = gethostbyname($ip);			# für dyndns-Hosts
				}
        if (in_subnet($remote_addr, $ip)) {
          $this->debug->write('<br>IP:'.$remote_addr.' paßt zu '.$ip,4);
          #echo '<br>IP:'.$remote_addr.' paßt zu '.$ip;
          return 1;
        }
      }
    }
    return 0;
  }
	
	function wrong_password($password) {
		$sql = "
			SELECT
				1
			FROM
				kvwmap.user
			WHERE
				id = " . $this->id . " AND
				password = kvwmap.sha1('" . pg_escape_string($password) . "')
		";
		$ret = $this->database->execSQL($sql, 4, 0, true);
		return pg_num_rows($ret[1]) == 0;
	}

	function login_is_locked() {
		return ($this->login_locked_until != '' AND strtotime($this->login_locked_until) > time());
	}

	function readUserDaten($id, $login_name = '', $password = '', $archived) {
		$where = array();
		if ($id > 0) array_push($where, "ID = " . $id);
		if ($login_name != '') array_push($where, "login_name = '" . pg_escape_string($login_name) . "'");
		if ($password != '') array_push($where, "password = kvwmap.sha1('" . pg_escape_string($password) . "')");
		if (!$archived) array_push($where, "archived IS NULL");
		$sql = "
			SELECT
				*
			FROM
				kvwmap.user
			WHERE
				" . implode(" AND ", $where);
		// echo '<br>SQL to read user data: ' . $sql;
		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>", 3);
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if(!$ret[0]) {
      $rs = pg_fetch_assoc($ret[1]);
    }
		$this->id = $rs['id'];
		$this->login_name = $rs['login_name'];
		$this->Namenszusatz = $rs['namenszusatz'];
		$this->Name = $rs['name'];
		$this->Vorname = $rs['vorname'];
		$this->stelle_id = $rs['stelle_id'];
		$this->phon = $rs['phon'];
		$this->email = $rs['email'];
		$this->organisation = $rs['organisation'];
		$this->position = $rs['position'];
		if (CHECK_CLIENT_IP) {
			$this->ips = $rs['ips'];
		}
		$this->funktion = $rs['funktion'];
		$this->debug->user_funktion = $this->funktion;
		$this->password_setting_time = $rs['password_setting_time'];
		$this->password_expired = $rs['password_expired'] === 't';
		$this->userdata_checking_time = $rs['userdata_checking_time'];
		$this->agreement_accepted = $rs['agreement_accepted'];
		$this->start = $rs['start'];
		$this->stop = $rs['stop'];
		$this->archived = $rs['archived'];
		$this->share_rollenlayer_allowed = $rs['share_rollenlayer_allowed'];
		$this->layer_data_import_allowed = $rs['layer_data_import_allowed'];
		$this->tokens = $rs['tokens'];
		$this->num_login_failed = $rs['num_login_failed'];
		$this->login_locked_until = $rs['login_locked_until'];
	}

	/*
	* Liefert die ID der vom Nutzer zuletzt verwendeten Stelle
	* wird keine gefunden wird eine 0 zurückgegeben
	* @return integer
	*/
	function getLastStelle() {
		$sql = "
			SELECT
				stelle_id
			FROM
				kvwmap.user
			WHERE
				id= " . $this->id ."
		";
		$this->debug->write("<p>file:users.php class:user->getLastStelle - Abfragen der zuletzt genutzten Stelle:<br>" . $sql, 4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4); return 0; }
		$rs = pg_fetch_array($ret[1]);
		return $rs['stelle_id'];
	}

	function setSize($mapsize) {
		$this->rolle->setSize($mapsize);
		return 1;
	}

	function setRolle($stelle_id) {
		# Abfragen und zuweisen der Einstellungen für die Rolle
		$rolle = new rolle($this->id, $stelle_id, $this->database);
		if ($rolle->readSettings()) {
			$this->rolle=$rolle;
			return 1;
		}
		return 0;
	}

	function getall_Users($order, $stelle_id = 0, $admin_id = 0) {
		global $admin_stellen;
		$more_from = '';
		$where = 'archived IS NULL ';

		#echo '<br>getall_Users für Stelle: ' . $stelle_id . ' und User: ' . $admin_id;
		if ($admin_id > 0 AND !in_array($stelle_id, $admin_stellen)) {
			$more_from = "
					JOIN kvwmap.rolle rall ON u.id = rall.user_id
					JOIN kvwmap.rolle radm ON rall.stelle_id = radm.stelle_id
			";
			$where .= " AND radm.user_id = " . $admin_id;
		}

		if ($order != '') {
			$order = ' ORDER BY ' . replace_semicolon($order);
		}

		$sql = "
			SELECT DISTINCT
				u.*
			FROM
				kvwmap.user u " .
				$more_from . "
			WHERE " .
				$where .
			$order . "
		";
		#echo '<br>getall_Users sql: ' . $sql;

		$this->debug->write("<p>file:kvwmap class:user->getall_Users - Lesen aller User:<br>" . $sql, 4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>wegen: ' . $sql . '<p>' . INFO1 . '<p>', 4); return 0; }
		while ($rs = pg_fetch_assoc($ret[1])) {
			$user['ID'][] = $rs['id'];
			$user['Bezeichnung'][] = $rs['name'] . ', ' . $rs['vorname'];
		}
		// Sortieren der User unter Berücksichtigung von Umlauten
		$sorted_arrays = umlaute_sortieren($user['Bezeichnung'], $user['ID']);
		$user['Bezeichnung'] = $sorted_arrays['array'];
		$user['ID'] = $sorted_arrays['second_array'];
		return $user;
	}

	function get_Unassigned_Users(){
		# Lesen der User, die keiner Stelle zugeordnet sind
		$sql = '
			SELECT 
				* 
			FROM 
				kvwmap.user 
			WHERE 
				archived IS NULL AND 
				ID NOT IN (
					SELECT DISTINCT 
						"user".id
					FROM 
						kvwmap.user, 
						kvwmap.rolle 
					WHERE 
						rolle.user_id = "user".id
				) 
			ORDER BY Name';
		$this->debug->write("<p>file:users.php class:user->get_Unassigned_Users - Lesen der User zur Stelle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4);
			return 0;
		}
		else {
			while ($rs = pg_fetch_assoc($ret[1])) {
				$user['ID'][] = $rs['id'];
				$user['Bezeichnung'][] = $rs['name'].', '.$rs['vorname'];
				$user['email'][]=$rs['email'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($user['Bezeichnung'], $user['ID']);
			$sorted_arrays2 = umlaute_sortieren($user['Bezeichnung'], $user['email']);
			$user['Bezeichnung'] = $sorted_arrays['array'];
			$user['ID'] = $sorted_arrays['second_array'];
			$user['email'] = $sorted_arrays2['second_array'];
		}
		return $user;
	}

	function get_Expired_Users(){
		# Lesen der User, die abgelaufen sind
		$sql = "
			SELECT 
				* 
			FROM 
				kvwmap.user 
			WHERE 
				archived IS NULL AND 
				stop IS NOT NULL AND 
				'" . date('Y-m-d h:i:s') . "' > stop 
			ORDER BY Name";
		$this->debug->write("<p>file:users.php class:user->get_Expired_Users - Lesen der User zur Stelle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4);
			return 0;
		}
		else {
			while ($rs = pg_fetch_assoc($ret[1])) {
				$user['ID'][] = $rs['id'];
				$user['Bezeichnung'][] = $rs['name'].', '.$rs['vorname'];
				$user['email'][] = $rs['email'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($user['Bezeichnung'], $user['ID']);
			$sorted_arrays2 = umlaute_sortieren($user['Bezeichnung'], $user['email']);
			$user['Bezeichnung'] = $sorted_arrays['array'];
			$user['ID'] = $sorted_arrays['second_array'];
			$user['email'] = $sorted_arrays2['second_array'];
		}
		return $user;
	}

	function getUserDaten($id, $login_name, $order, $stelle_id = 0, $admin_id = 0, $archived = false) {
		global $admin_stellen;
		$where = array();

		if (!$archived) {
			$where[] = 'u.archived IS NULL';
		}

		if ($admin_id > 0 AND !in_array($stelle_id, $admin_stellen)) {
			$more_from = "
				LEFT JOIN kvwmap.rolle rall ON u.id = rall.user_id
				LEFT JOIN kvwmap.rolle radm ON radm.stelle_id = rall.stelle_id
			";
			$where[] = "(radm.user_id = ".$admin_id.")";
		}

		if ($id > 0) {
			$where[] = 'u.id = ' . $id;
		}

		if ($login_name != '') {
			$where[] = 'login_name = "' . $login_name . '"';
		}

		if ($order != '') {
			$order = ' ORDER BY ' . replace_semicolon($order);
		}

		$sql = "
			SELECT DISTINCT
				u.*, (select max(r.last_time_id) from kvwmap.rolle r where u.id = r.user_id ) as last_timestamp
			FROM
				kvwmap.user u " .
				$more_from .
			(count($where) > 0 ? " WHERE " . implode(' AND ', $where) : "") .
			$order . "
		";
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4);
			return 0;
		}
		while ($rs = pg_fetch_assoc($ret[1])) {
			$userdaten[] = $rs;
		}
		return $userdaten;
	}

	function getStellen($stelle_ID, $with_expired = false) {
		global $language;
		if ($language != '' AND $language != 'german') {
			$name_column = "
			CASE
				WHEN s.bezeichnung_" . $language . " != \"\" THEN s.bezeichnung_" . $language . "
				ELSE s.bezeichnung
			END AS bezeichnung";
		}
		else {
			$name_column = "s.bezeichnung";
		}
		$sql = "
			SELECT
				s.id,
				" . $name_column . "
			FROM
				kvwmap.stelle AS s,
				kvwmap.rolle AS r
			WHERE
				s.id = r.stelle_id AND
				r.user_id = " . $this->id .
				($stelle_ID > 0 ? " AND s.id = " . $stelle_ID : "") . 
				(!$with_expired ? "
				AND (
					(
						('" . date('Y-m-d h:i:s') . "' >= s.start OR s.start IS NULL) AND 
						('" . date('Y-m-d h:i:s') . "' <= s.stop OR s.stop IS NULL)
					)
					OR
					(s.start IS NULL AND s.stop IS NULL)
				)" : "") . "
			ORDER BY
				bezeichnung;
		";
		#debug_write('<br>sql: ', $sql, 1);
		$this->debug->write("<p>file:users.php class:user->getStellen - Abfragen der Stellen die der User einnehmen darf:", 4);
		$ret = $this->database->execSQL($sql, 4, 0, true);
		$stellen = array( 'ID' => array(), 'Bezeichnung' => array());
		while ($rs = pg_fetch_assoc($ret[1])) {
			$stellen['ID'][]=$rs['id'];
			$stellen['Bezeichnung'][]=$rs['bezeichnung'];
		}
		return $stellen;
	}

	function updateStelleID($stelle_id) {
		# sezten der aktuell für den Nutzer eingestellten Stelle
		$sql = "
			UPDATE
				kvwmap.user
			SET
				stelle_id = " . $stelle_id . "
			WHERE
				id = " . $this->id . "
		";
		$this->debug->write("<p>file:users.php class:user->updateStelleID - Setzen der aktuellen Stellen-ID für den User<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4); return 0; }
		$this->debug->write('Stelle gewechselt, neue Stellen_ID: ' . $stelle_id, 4);
	}

	function update_agreement_accepted($accepted) {
		$sql = "
			UPDATE
				kvwmap.user
			SET
				agreement_accepted = " . $accepted . "
			WHERE
				id = " . $this->id . "
		";
		$this->debug->write("<p>file:users.php class:user->agreement_accepted - Setzen ob Agreement akzeptiert.<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4); return 0; }
	}

	function update_tokens($token) {
		$sql = "
			UPDATE
				kvwmap.user
			SET
				tokens = '" . implode(',', array_merge([$token], array_slice(array_filter(explode(',', $this->tokens, 5)), 0, 4))) . "'
			WHERE
				id = " . $this->id . "
		";
		$this->debug->write("<p>file:users.php class:user->updateTokens - Speichern des Tokens.<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4); return 0; }
	}

	function setOptions($stelle_id, $formvars) {
		$nImageWidth = '';
		$nImageHeight = '';
		$buttons = '';

		# Setzen der Werte, die aktuell für die Nutzung der Stelle durch den Nutzer gelten sollen.
		if ($formvars['mapsize'] == 'auto') {
			$auto_map_resize = '1';
		}
		else {
			$auto_map_resize = '0';
			$teil = explode('x', $formvars['mapsize']);
			$nImageWidth = ($teil[0] > 0 ? $teil[0] : 1000);
			$nImageHeight = ($teil[1] > 0 ? $teil[1] : 800);
		}
		# Zoomfaktor (Wenn 1 erfolgt kein Zoom durch einfaches klicken in die Karte)
		if ($formvars['nZoomFactor']=='' OR $formvars['nZoomFactor']==0) {
			$formvars['nZoomFactor']=2;
		}
		# Reduzierung der eingestellten Kartenausdehnung auf die in der Stelle vorgegebene maximal mögliche
		# Berücksichtigen des Verhältnisses aus Höhe und Breite des Kartenfensters bei der Festlegung des Maximalen Extents
		# Zerhacken des String, in dem die neue Kartenausdehnung steht
		$newMinMaxPoints=explode(',',$formvars['newExtent']);
		$newMinPoint=explode(' ',trim($newMinMaxPoints[0]));
		$newMaxPoint=explode(' ',trim($newMinMaxPoints[1]));
		$newExtent['minx']=$newMinPoint[0]; $newExtent['miny']=$newMinPoint[1];
		$newExtent['maxx']=$newMaxPoint[0]; $newExtent['maxy']=$newMaxPoint[1];
		$formvars['language']=$formvars['language'];
		# Eintragen der neuen Einstellungen für die Rolle
		if ($formvars['gui'] != '' AND $formvars['mapsize'] != '') {
			$sql = "
				UPDATE
					kvwmap.rolle
				SET
					nZoomFactor = " . $formvars['nZoomFactor'] . "
					" . ($nImageWidth != '' ? ", nImageWidth = " . $nImageWidth : "") . "
					" . ($nImageHeight != '' ? ", nImageHeight = " . $nImageHeight : "") . "
					, gui = '" . $formvars['gui'] . "'
					, auto_map_resize = " . $auto_map_resize . "
					, epsg_code = '" . $formvars['epsg_code'] . "'
					, epsg_code2 = '" . $formvars['epsg_code2'] . "'
					, coordtype = '" . $formvars['coordtype'] . "'
					, minx = " . $newExtent['minx'] . "
					, miny = " . $newExtent['miny'] . "
					, maxx = " . $newExtent['maxx'] . "
					, maxy = " . $newExtent['maxy'] . "
					, language = '" . $formvars['language'] . "'
					, tooltipquery = '" . ($formvars['tooltipquery'] != '' ? "1" : "0") . "'
					, result_color = '" . $formvars['result_color'] . "'
					, result_hatching = '" . (value_of($formvars, 'result_hatching') == '' ? '0' : '1') . "'
					, result_transparency = '" . $formvars['result_transparency'] . "'
					, runningcoords = '" . (value_of($formvars, 'runningcoords') == '' ? '0' : '1') . "'
					, showmapfunctions = '" . ($formvars['showmapfunctions'] == '' ? '0' : '1') . "'
					, showlayeroptions = '" . ($formvars['showlayeroptions'] == '' ? '0' : '1') . "'
					, showrollenfilter = '" . (value_of($formvars, 'showrollenfilter') == '' ? '0' : '1') . "'
					, menue_buttons = '" . (value_of($formvars, 'menue_buttons') == '' ? '0' : '1') . "'
					" . (value_of($formvars, 'redline_text_color')  != '' ? ", redline_text_color  = '" . $formvars['redline_text_color']  . "'" : '') . "
					" . (value_of($formvars, 'redline_font_family') != '' ? ", redline_font_family = '" . $formvars['redline_font_family'] . "'" : '') . "
					" . (value_of($formvars, 'redline_font_size')   != '' ? ", redline_font_size   = '" . $formvars['redline_font_size']   . "'" : '') . "
					" . (value_of($formvars, 'redline_font_weight') != '' ? ", redline_font_weight = '" . $formvars['redline_font_weight'] . "'" : '') . "
					, singlequery = '" . value_of($formvars, 'singlequery') . "'
					, instant_reload = '" . (value_of($formvars, 'instant_reload') == '' ? '0' : '1') . "'
					, menu_auto_close = '" . (value_of($formvars, 'menu_auto_close') == '' ? '0' : '1') . "'
					, visually_impaired = '" . (value_of($formvars, 'visually_impaired') == '' ? '0' : '1') . "'
					" . (value_of($formvars, 'font_size_factor') != '' ? ", font_size_factor = " . $formvars['font_size_factor'] : '') . "
					, querymode = " . (value_of($formvars, 'querymode') != '' ? "'1'" : "'0', overlayx = 400, overlayy = 150") . "
					, geom_edit_first = '" . $formvars['geom_edit_first'] . "'
					, dataset_operations_position = '" . $formvars['dataset_operations_position'] . "'
					, immer_weiter_erfassen = " . quote_or_null($formvars['immer_weiter_erfassen']) . "
					, upload_only_file_metadata = " . quote_or_null($formvars['upload_only_file_metadata']) . "
			";
			$sql.=",print_scale = CASE WHEN print_scale = 'auto' OR '" . $formvars['print_scale'] . "' = 'auto' THEN '" . $formvars['print_scale'] ."' ELSE print_scale END";
			if($formvars['hist_timestamp'] != '') $sql.=",hist_timestamp='" . DateTime::createFromFormat('d.m.Y H:i:s', $formvars['hist_timestamp'])->format('Y-m-d H:i:s') . "'";
			else $sql.=',hist_timestamp = NULL';
			if($formvars['back']) { $buttons .= 'back,';}
			if($formvars['forward']){$buttons .= 'forward,';}
			if($formvars['zoomin']){$buttons .= 'zoomin,';}
			if($formvars['zoomout']){$buttons .= 'zoomout,';}
			if($formvars['zoomall']){$buttons .= 'zoomall,';}
			if($formvars['recentre']){$buttons .= 'recentre,';}
			if($formvars['jumpto']){$buttons .= 'jumpto,';}
			if($formvars['coord_query']){$buttons .= 'coord_query,';}
			if($formvars['elevation_profile']){$buttons .= 'elevation_profile,';}			
			if($formvars['query']){$buttons .= 'query,';}
			if($formvars['touchquery']){$buttons .= 'touchquery,';}
			if($formvars['queryradius']){$buttons .= 'queryradius,';}
			if($formvars['polyquery']){$buttons .= 'polyquery,';}
			if($formvars['measure']){$buttons .= 'measure,';}
			if($formvars['routing']){$buttons .= 'routing,';}
			if($formvars['punktfang']){$buttons .= 'punktfang,';}
			if (value_of($formvars, 'freepolygon')) { $buttons .= 'freepolygon,';}
			if (value_of($formvars, 'freearrow')) { $buttons .= 'freearrow,';}
			if (value_of($formvars, 'freetext')) { $buttons .= 'freetext,';}
			if (value_of($formvars, 'gps')) { $buttons .= 'gps';}
			if($buttons != '')$sql.=",buttons = '".$buttons."'";
			$sql.=",selectedButton='zoomin'";
			$sql.=' WHERE stelle_id='.$stelle_id.' AND user_id='.$this->id;
			#echo $sql;
			$this->debug->write("<p>file:users.php class:user->setOptions - Setzen der Einstellungen für die Rolle des Users<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4); return 0; }
			$this->debug->write('Neue Werte für Rolle eingestellt: '.$formvars['nZoomFactor'].', '.$formvars['mapsize'],4);
		}
		return 1;
	}

	function checkstelle(){
		# Funktion wird nach Änderungen im Nutzer- und Stelleneditor aufgerufen und überprüft
		# ob die letzte Stellen_ID leer ist und ob die letzte Stellen_ID nicht mehr zu den dem Nutzer
		# zugeordneten Stellen gehört. Die letzte Stellen_ID wird in beiden Fällen auf die erste von den
		# dem Nutzer zugeordneten Stellen gesetzt.
		$stellen= $this->getStellen(0);
		if(count_or_0($stellen['ID']) > 0){
			$stelle_id = $this->getLastStelle();
			if($stelle_id != ''){
				$valid = false;
				for($i = 0; $i < count($stellen['ID']); $i++){
					if($stellen['ID'][$i] == $stelle_id){
						$valid = true;
						break;
					}
				}
				if($valid == false){
					$this->updateStelleID($stellen['ID'][0]);
				}
			}
			else{
				$this->updateStelleID($stellen['ID'][0]);
			}
		}
	}

	function StellenZugriff($stelle_id) {
		$this->Stellen=$this->getStellen($stelle_id);
		if (count($this->Stellen['ID'])>0) {
			return 1;
		}
		return 0;
	}

	function exist($id) {
		$Meldung='';
		# testen ob es einen user unter dieser id in der Datenbanktabelle gibt
		$sql = "
			SELECT
				*
			FROM
				kvwmap.user
			WHERE
				id = " . $id . "
		";
		$ret = $this->database->execSQL($sql,4, 0);
		if (!$this->database->success) {
			$ret[1] .= '<br>Die Abfrage konnte nicht ausgeführt werden.' . $ret[1];
		}
		else {
			if (pg_num_rows($ret[1]) > 0) {
				$ret[1] = 1;
			}
			else {
				$ret[1] = 0;
			}
		}
		return $ret;
	}

	/**
	 * Function return a unique login_name for $vorname and $nachname
	 * that not allready exists in tabel user.
	 * The loginname will be composed on the first letter of $vorname and $nachname
	 * If it exists a increasing nummer will be added until the loginname not exists in
	 * database. This that first not exists will be returned as unique and valid login_name.
	 * @param String $vorname,
	 * @param String $nachname,
	 * @return String The loginname
	 */
	function get_login_name($vorname, $nachname) {
		$loginname_exists = true;
		$postfix = '';
		do {
			$login_name = strtolower(substr($vorname, 0, 1)) . strtolower($nachname) . $postfix;
			$ret = $this->loginname_exists($login_name);
			if ($ret[1] == 1) {
				$postfix = ($postfix == '' ? 2 : $postfix + 1);
			}
			if ($postfix > 10) {
				$ret[1] = 0; // Abbruch
				$login_name = strtolower(substr($vorname, 0, 1)) . strtolower($nachname) . rand(11, 9999);
			}
		} while ($ret[1] == 1);
		return $login_name;
	}

	function loginname_exists($login, $id = NULL) {
		$Meldung='';
		# testen ob es einen user mit diesem login_namen in der Datenbanktabelle gibt und diesen dann zurückliefern
		$sql ="
			SELECT
				*
			FROM
				kvwmap.user
			WHERE
				login_name = '" . $login . "'
		";
		if ($id != NULL) {
			$sql.= "
				AND id != " . $id;
		}
		$ret = $this->database->execSQL($sql,4, 0);
		if (!$this->database->success) {
			$ret[1].='<br>Die Abfrage konnte nicht ausgeführt werden.'.$ret[1];
		}
		else {
			if (pg_num_rows($ret[1]) > 0) {
				$ret['user'] = pg_fetch_array($ret[1]);
			}
			else {
				$ret[1] = 0;
			}
		}
		return $ret;
	}

	/**
	 * Check the data in $userdaten
	 * - Check if $userdaten['id'] exists in table users column ID with function exists
	 * - Check if nachname, vorname or loginname is missing in $userdaten
	 * - If loginname exists check if it exists in users table.
	 * - If changepasswd is set, check if password1 and password2 exists and are equal.
	 * - If phon is set check the length.
	 * - If email is set check if it is valid with function emailcheck
	 * @return Array $ret If $ret[0] = 1 $ret[1] returns the message about missing or failed userdata, else $ret[0] is 0 which indicates no problems. 
	 */
	function checkUserDaten($userdaten) {
		$Meldung='';
		# Prüfen ob die user_id schon existiert
		if ($userdaten['id'] != '') {
			$ret = $this->exist($userdaten['id']);
			if ($ret[0]) {
				$Meldung .= $ret[1];
			}
		}
		if ($userdaten['nachname'] == '') { $Meldung .= '<br>Nachname fehlt.'; }
		if ($userdaten['vorname'] == '') { $Meldung .= '<br>Vorname fehlt.'; }
		if ($userdaten['loginname'] == '') { $Meldung .= '<br>Login Name fehlt.'; }
		else {
			$ret = $this->loginname_exists($userdaten['loginname'], $userdaten['id']);
			if ($ret['user']) {
				$Meldung .= '<br>Es existiert bereits ein Nutzer mit diesem Loginnamen.';
			}
		}
		if ($userdaten['changepasswd'] == 1){
			if ($userdaten['password1'] == '') { $Meldung.='<br>Die erste Passwordeingabe fehlt.'; }
			if ($userdaten['password2'] == '') { $Meldung.='<br>Die Passwordwiederholung fehlt.'; }
			if ($userdaten['password1'] != $userdaten['password2']) { $Meldung .= '<br>Die Passwörter stimmen nicht überein.'; }
		}
		if ($userdaten['phon'] != '' AND strlen($userdaten['phon']) < 3) { $Meldung .= '<br>Die Telefonnummer ist zu kurz.'; }
		if ($userdaten['email'] != '') { $Meldung .= emailcheck($userdaten['email']); }
		if ($Meldung != '') {
			$ret[0] = 1; $ret[1] = $Meldung;
		}
		else {
			$ret[0] = 0;
		}
		return $ret;
	}

	function NeuAnlegen($userdaten) {
		$stellen = array_filter(explode(', ',$userdaten['selstellen']));
		# Neuen Nutzer anlegen
		if($userdaten['id'] != ''){
			$columns['id'] = $userdaten['id'];
		}
		$columns['name'] = "'" . $userdaten['nachname'] . "'";
		$columns['vorname'] = "'" . $userdaten['vorname'] . "'";
		$columns['login_name'] = "'" . trim($userdaten['loginname']) . "'";
		$columns['namenszusatz'] = "'" . $userdaten['namenszusatz'] . "'";
		$columns['password'] = "kvwmap.sha1('" . pg_escape_string(trim($userdaten['password2'])) . "')";
		$columns['password_setting_time'] = "CURRENT_TIMESTAMP";
		$columns['password_expired'] = ($userdaten['password_expired'] ? 'true' : 'false');
		if ($userdaten['phon']!='') {
			$columns['phon'] = "'" . $userdaten['phon'] . "'";
		}
		if ($userdaten['email']!='') {
			$columns['email'] = "'" . $userdaten['email'] . "'";
		}
		if ($userdaten['organisation']!='') {
			$columns['organisation'] = "'" . $userdaten['organisation'] . "'";
		}
		if ($userdaten['position']!='') {
			$columns['position'] = "'" . $userdaten['position'] . "'";
		}
		if ($userdaten['comment']!='') {
			$columns['comment'] = "'" . $userdaten['comment'] . "'";
		}
		if ($userdaten['start'] != '') {
			$columns['start'] = ($userdaten['start'] ? "'" . $userdaten['start'] . "'" : 'NULL');
		}
		if ($userdaten['stop'] != '') {
			$columns['stop'] = ($userdaten['stop'] ? "'" . $userdaten['stop'] . "'" : 'NULL');
		}
		if ($userdaten['ips']!='') {
			$columns['ips'] = "'" . $userdaten['ips'] . "'";
		}
		if($stellen[0] != ''){
			$columns['stelle_id'] = $stellen[0];
		}
		$sql = "
			INSERT INTO 
				kvwmap.user
			(" . implode(', ', array_keys($columns)) . ")
			VALUES	
				(" . implode(', ', $columns) . ")
			RETURNING id";
		
		// echo '<p>SQL zum Eintragen eines neuen Nutzers: ' . $sql;
		# Abfrage starten
		$ret = $this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1].='<br>Die Benutzerdaten konnten nicht eingetragen werden.<br>'.$ret[1];
		}
		else {
			# Abfrage erfolgreich durchgeführt, übergeben der user_id zur Rückgabe der Funktion
			$rs = pg_fetch_assoc($ret[1]);
			$ret[1] = $rs['id'];
		}
		return $ret;
	}

	function Aendern($userdaten) {
		$password_columns = '';
		if ($userdaten['changepasswd'] == 1) {
			$password_columns = ",
				password = kvwmap.sha1('" . pg_escape_string(trim($userdaten['password2'])) . "'),
				password_setting_time = CURRENT_TIMESTAMP,
				password_expired = " . ($userdaten['reset_password'] ? 'true' : 'false') . "
			";
		}

		$sql = "
			UPDATE
				kvwmap.user
			SET
				name = '" . $userdaten['nachname'] . "',
				vorname = '" . $userdaten['vorname'] . "',
				login_name = '" . trim($userdaten['loginname']) . "',
				namenszusatz = '" . $userdaten['namenszusatz'] . "',
				start = " . ($userdaten['start'] ? "'" . $userdaten['start'] . "'" : 'NULL') . ",
				stop = " . ($userdaten['stop'] ? "'" . $userdaten['stop'] . "'" : 'NULL') . ",
				archived= " . ($userdaten['archived']? "'" . $userdaten['archived'] . "'" : "NULL") . ",
				id =  " . $userdaten['id'].",
				phon = '" . $userdaten['phon']."',
				email = '" . $userdaten['email']."',
				organisation = '" . $userdaten['organisation']."',
				position = '" . $userdaten['position']."',
				comment = '" . $userdaten['comment']."',
				ips = '" . $userdaten['ips'] . "',
				agreement_accepted = " . ($userdaten['agreement_accepted'] == 1 ? 1 : 0) . ",
				share_rollenlayer_allowed = " . ($userdaten['share_rollenlayer_allowed'] == 1 ? 1 : 0) . ",
				layer_data_import_allowed = " . ($userdaten['layer_data_import_allowed'] == 1 ? 1 : 0) .
				$password_columns . "
			WHERE
				id = " . $userdaten['selected_user_id'] . "
		";
		#echo 'SQL: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		if ($ret[0]) {
			$ret[1] .= '<br>Die Benutzerdaten konnten nicht aktualisiert werden.<br>'.$ret[1];
		}
		return $ret;
	}

	function archivieren() {
		$sql = "
			UPDATE
				kvwmap.user
			SET
				archived = CURRENT_TIMESTAMP
			WHERE
				id = " . $this->id . "
		";
		#echo 'SQL: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Der Benutzer konnte nicht archiviert werden.<br>'.$ret[1];
		}
		return $ret;
	}	
	
	function set_userdata_checking_time() {
		$sql = "
			UPDATE
				kvwmap.user
			SET
				userdata_checking_time = CURRENT_TIMESTAMP
			WHERE
				id = " . $this->id . "
		";
		#echo 'SQL: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Fehler beim Eintragen von userdata_checking_time.<br>'.$ret[1];
		}
		return $ret;
	}		

	/**
	 * Aktualisiert das Passwort und setzt ein neuen Zeitstempel
	 * Diese Funktion trägt für den Benutzer in diesem Objekt ein neues Passwort ein und setzt als Datum das aktuelle Datum.
	 * Zusätzlich wird das flag password_expired auf true gesetzt, damit der Nutzer auch zur Eingabe eines neuen Passwortes
	 * aufgefordert wird wenn in der Stelle das Passwortalter nicht geprüft wird	 * 
	 * Reihenfolge: Übersichtssatz - Kommentar - Tags.	 * 
	 * @param string password Einzutragendes Password als Text
	 * @return array liefert zweidimensionales Array zurück,
	 * 							Wenn array[0]=0 enthält array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
	 * 							Wenn array[0]=1 liegt ein Fehler vor und array[1] enthält eine Fehlermeldung.
	 * @see NeuAnlegen(), Aendern(), Loeschen(), $user, $rolle, $stelle
	 */
	function setNewPassword($password) {
		$password_setting_time = date('Y-m-d H:i:s', time());
		$sql = "
			UPDATE
				kvwmap.user
			SET
				password = kvwmap.sha1('" . pg_escape_string($password) . "'),
				password_setting_time = '" . $password_setting_time . "',
				password_expired = false
			WHERE
				id = " . $this->id . "
		";
		#echo $sql;
		$ret = $this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1] .= '<br>Die Benutzerdaten konnten nicht aktualisiert werden.<br>'.$ret[1];
		}
		else {
			$this->password_setting_time = $password_setting_time;
			$this->password_expired = false;
		}
		return $ret;
	}

	function delete($user_id) {
		$sql ="
			DELETE FROM
				kvwmap.user
			WHERE
				id = " . $user_id . "
		";
		$ret=$this->database->execSQL($sql, 4, 0);
		if ($ret[0]) {
			$ret[1] .= '<br>Der Benutzer konnte nicht gelöscht werden.<br>' . $ret[1];
		}
		else {
			// $sql = "
			// 	ALTER TABLE user PACK_KEYS = 0 CHECKSUM = 0 DELAY_KEY_WRITE = 0 AUTO_INCREMENT = 1
			// ";
			// $ret = $this->database->execSQL($sql, 4, 0);
			// if ($ret[0]) {
			// 	$ret[1] .= '<br>Das Autoincrement für die Tabelle Benutzer konnte nicht zurückgesetzt werden.<br>'.$ret[1];
			// }
		}
		return $ret;
	}

	function is_gast() {
		return ($this->Name == 'gast' && $this->Vorname =='gast' && $this->funktion == 'gast');
	}
}

?>