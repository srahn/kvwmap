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
# class_account
# class_user
# class_rolle
# class_stelle

##############################
# class_account
class account {
	# Klasse für die Abrechnung und Statistik von Zugriffen

	var $database;

	function account ($database) {
		global $debug;
		$this->debug=$debug;
		$this->database=$database;
	}

	function getStatistik($nutzer,$nutzung,$stelle,$zeitraum,$day_d,$week_w,$month_d,$month_w,$month_m,$year_m,$year_w,$year_d,$day_e1,$day_e2,$month_e1,$month_e2,$year_e1,$year_e2){
			# Ausführen der einzelnen Funktionen für
			# die Statistik zur Anfrage
			$BezeichnungStelle = new stelle($stelle,$this->database);
			$UserName = new user('','',$this->database);
	
			$this->epoch=$this->epoch();
			#$this->getLayer=$this->getLayer('');
			#$this->getLoggedLayer=$this->getLayer(1);
			#$this->allLayerAccess=$this->getAllAccess('2layer');
			#$this->allAccess=$this->getAllAccess('');
	
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
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id','MONTH',$month_m, $year_m,$stelle,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id','MONTH',$month_m, $year_m,$stelle,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id','MONTH',$month_m, $year_m,$stelle,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id','MONTH',$month_m, $year_m,$stelle,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id','MONTH',$month_m, $year_m,$stelle,'');
				}
				if ($nutzung=='nutzer') {
					$this->UName=$UserName->getUserDaten($nutzer,'','');
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.user_id','MONTH',$month_m,$year_m,$nutzer,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.user_id','MONTH',$month_m,$year_m,$nutzer,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.user_id','MONTH',$month_m, $year_m,$nutzer,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.user_id','MONTH',$month_m, $year_m,$nutzer,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.user_id','MONTH',$month_m, $year_m,$nutzer,'');
				}
				if ($nutzung=='stelle_nutzer') {
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->UName=$UserName->getUserDaten($nutzer,'','');
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.stelle_id, c.user_id','MONTH',$month_m,$year_m,$stelle,$nutzer);
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.stelle_id, u_consumeALK.user_id','MONTH',$month_m,$year_m,$stelle,$nutzer);
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.stelle_id, u_consumeALB.user_id','MONTH',$month_m,$year_m,$stelle,$nutzer);
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.stelle_id, u_consumeCSV.user_id','MONTH',$month_m,$year_m,$stelle,$nutzer);
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.stelle_id, u_consumeShape.user_id','MONTH',$month_m, $year_m,$stelle,$nutzer);
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
					$this->UName=$UserName->getUserDaten($nutzer,'','');
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.user_id','WEEK',$week_w,$year_w,$nutzer,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.user_id','WEEK',$week_w,$year_w,$nutzer,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.user_id','WEEK',$week_w,$year_w,$nutzer,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.user_id','WEEK',$week_w, $year_w,$nutzer,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.user_id','WEEK',$week_w, $year_w,$nutzer,'');
				}
				if ($nutzung=='stelle_nutzer') {
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->UName=$UserName->getUserDaten($nutzer,'','');
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
					$this->UName=$UserName->getUserDaten($nutzer,'','');
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.user_id','DATE',$year_d.'-'.$month_d.'-'.$day_d,'',$nutzer,'');
				}
				if ($nutzung=='stelle_nutzer'){
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->UName=$UserName->getUserDaten($nutzer,'','');
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
					$this->UName=$UserName->getUserDaten($nutzer,'','');
					$this->NumbOfAccess=$this->getAccessToLayer($nutzung,$zeitraum,$date1,$date2,'c.user_id','DATE','','',$nutzer,'');
					$this->ALKNumbOfAccess=$this->getAccessToALK($nutzung,$zeitraum,$date1,$date2,'u_consumeALK.user_id','DATE','','',$nutzer,'');
					$this->ALBNumbOfAccess=$this->getAccessToALB($nutzung,$zeitraum,$date1,$date2,'u_consumeALB.user_id','DATE','','',$nutzer,'');
					$this->CSVNumbOfAccess=$this->getAccessToCSV($nutzung,$zeitraum,$date1,$date2,'u_consumeCSV.user_id','DATE','','',$nutzer,'');
					$this->ShapeNumbOfAccess=$this->getAccessToShape($nutzung,$zeitraum,$date1,$date2,'u_consumeShape.user_id','DATE','','',$nutzer,'');
				}
				if ($nutzung=='stelle_nutzer'){
					$this->Bezeichnung=$BezeichnungStelle->getName();
					$this->UName=$UserName->getUserDaten($nutzer,'','');
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
		$sql.=' FROM u_consume AS c,u_consume2layer AS c2l, layer AS l';
		if ($nutzung=='stelle'){
			$sql.=' , stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,stelle AS s, user AS u';
		}
		$sql.=' WHERE c.user_id=c2l.user_id';
		$sql.=' AND c2l.layer_id = l.Layer_ID';
		$sql.=' AND c.time_id=c2l.time_id';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'(c.time_id)='.$date.' AND YEAR(c.time_id)='.$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'(c.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(c.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}

		if ($case=='c.stelle_id'){
			$sql.=' AND c.stelle_id=s.ID AND s.ID='.$id;
			$sql.=' GROUP BY (CONCAT(c2l.layer_id,c.stelle_id)) ORDER BY Name';
		}
		if ($case=='c.user_id') {
			$sql.=' AND c.user_id=u.ID AND u.ID='.$id;
			$sql.=' GROUP BY (CONCAT(c2l.layer_id,c.user_id)) ORDER BY Name';
		}
		if ($case=='c.stelle_id, c.user_id'){
			$sql.=' AND c.stelle_id=s.ID AND s.ID='.$id;
			$sql.=' AND c.user_id=u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY (CONCAT(c2l.layer_id,c.stelle_id,c.user_id)) ORDER BY Bezeichnung,lName,Name';
		}
		$this->debug->write("<p>file:kvwmap class:account->getNumber_of_Access_to_Layer:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
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
		$sql.=' FROM u_consumeCSV';
		if ($nutzung=='stelle'){
			$sql.=' , stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,stelle AS s, user AS u';
		}
		$sql.=' WHERE (1=1)';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'(u_consumeCSV.time_id)='.$date.' AND YEAR(u_consumeCSV.time_id)='.$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'(u_consumeCSV.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(u_consumeCSV.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}

		if ($case=='u_consumeCSV.stelle_id'){
			$sql.=' AND u_consumeCSV.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' GROUP BY (CONCAT(u_consumeCSV.art,u_consumeCSV.stelle_id)) ORDER BY art';
		}
		if ($case=='u_consumeCSV.user_id') {
			$sql.=' AND u_consumeCSV.user_id = u.ID AND u.ID='.$id;
			$sql.=' GROUP BY (CONCAT(u_consumeCSV.art,u_consumeCSV.user_id)) ORDER BY art';
		}
		if ($case=='u_consumeCSV.stelle_id, u_consumeCSV.user_id'){
			$sql.=' AND u_consumeCSV.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' AND u_consumeCSV.user_id = u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY (CONCAT(u_consumeCSV.art,u_consumeCSV.stelle_id,u_consumeCSV.user_id)) ORDER BY art';
		}
		$this->debug->write("<p>file:kvwmap class:account->getAccessToCSV:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
			$NumbOfAccess[]=$rs;
			$sql ='SELECT u_consumeCSV.time_id, concat(u.Vorname, " ", u.Name) as Name';
			$sql.=' FROM user AS u, stelle AS s, u_consumeCSV';
			$sql.=' WHERE 1=1';
			if ($zeitraum=='month' OR $zeitraum=='week')  {
				$sql.=' AND '.$era.'(u_consumeCSV.time_id)='.$date.' AND YEAR(u_consumeCSV.time_id)='.$year;
			}
			if ($zeitraum=='day'){
				$sql.=' AND ('.$era.'(u_consumeCSV.time_id))="'.$date.'"';
			}
			if ($zeitraum=='era') {
				$sql.=' AND ((DATE(u_consumeCSV.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
			}
			$sql.=' AND u_consumeCSV.stelle_id = s.ID';
			$sql.=' AND u_consumeCSV.user_id = u.ID';
			$sql.= ' AND art="'.$rs['art'].'"';
			if($rs['stelle_id'] != ''){
				$sql.= ' AND u_consumeCSV.stelle_id='.$rs['stelle_id'];
			}
			if($rs['user_id'] != ''){
				$sql.= ' AND u_consumeCSV.user_id='.$rs['user_id'];
			}
			#echo $sql.'<br><br>';
			$this->debug->write("<p>file:kvwmap class:account->getAccessToCSV:<br>".$sql,4);
			$query_array[]=mysql_query($sql);
			if ($query_array[count($query_array)-1]==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
			$NumbOfAccessTimeIDs = array();
			while($rs=mysql_fetch_array($query_array[count($query_array)-1])) {
				$NumbOfAccessTimeIDs[]=$rs;
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
		$sql.=' FROM u_consumeShape';
		if ($nutzung=='stelle'){
			$sql.=' , stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,stelle AS s, user AS u';
		}
		$sql.=' WHERE (1=1)';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'(u_consumeShape.time_id)='.$date.' AND YEAR(u_consumeShape.time_id)='.$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'(u_consumeShape.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(u_consumeShape.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}

		if ($case=='u_consumeShape.stelle_id'){
			$sql.=' AND u_consumeShape.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' GROUP BY (CONCAT(u_consumeShape.layer_id,u_consumeShape.stelle_id)) ORDER BY layer_id';
		}
		if ($case=='u_consumeShape.user_id') {
			$sql.=' AND u_consumeShape.user_id = u.ID AND u.ID='.$id;
			$sql.=' GROUP BY (CONCAT(u_consumeShape.layer_id,u_consumeShape.user_id)) ORDER BY layer_id';
		}
		if ($case=='u_consumeShape.stelle_id, u_consumeShape.user_id'){
			$sql.=' AND u_consumeShape.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' AND u_consumeShape.user_id = u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY (CONCAT(u_consumeShape.layer_id,u_consumeShape.stelle_id,u_consumeShape.user_id)) ORDER BY layer_id';
		}
		$this->debug->write("<p>file:kvwmap class:account->getAccessToShape:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
			$NumbOfAccess[]=$rs;
			$sql ='SELECT u_consumeShape.time_id, concat(u.Vorname, " ", u.Name) as Name';
			$sql.=' FROM user AS u, stelle AS s, u_consumeShape';
			$sql.=' WHERE 1=1';
			if ($zeitraum=='month' OR $zeitraum=='week')  {
				$sql.=' AND '.$era.'(u_consumeShape.time_id)='.$date.' AND YEAR(u_consumeShape.time_id)='.$year;
			}
			if ($zeitraum=='day'){
				$sql.=' AND ('.$era.'(u_consumeShape.time_id))="'.$date.'"';
			}
			if ($zeitraum=='era') {
				$sql.=' AND ((DATE(u_consumeShape.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
			}
			$sql.=' AND u_consumeShape.stelle_id = s.ID';
			$sql.=' AND u_consumeShape.user_id = u.ID';
			$sql.= ' AND u_consumeShape.layer_id="'.$rs['layer_id'].'"';
			if($rs['stelle_id'] != ''){
				$sql.= ' AND u_consumeShape.stelle_id='.$rs['stelle_id'];
			}
			if($rs['user_id'] != ''){
				$sql.= ' AND u_consumeShape.user_id='.$rs['user_id'];
			}
			#echo $sql.'<br><br>';
			$this->debug->write("<p>file:kvwmap class:account->getAccessToShape:<br>".$sql,4);
			$query_array[]=mysql_query($sql);
			if ($query_array[count($query_array)-1]==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
			$NumbOfAccessTimeIDs = array();
			while($rs=mysql_fetch_array($query_array[count($query_array)-1])) {
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
		$sql.=' FROM u_consumeALB';
		if ($nutzung=='stelle'){
			$sql.=' , stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,stelle AS s, user AS u';
		}
		$sql.=' WHERE (1=1)';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'(u_consumeALB.time_id)='.$date.' AND YEAR(u_consumeALB.time_id)='.$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'(u_consumeALB.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(u_consumeALB.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}

		if ($case=='u_consumeALB.stelle_id'){
			$sql.=' AND u_consumeALB.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' GROUP BY (CONCAT(u_consumeALB.format,u_consumeALB.stelle_id)) ORDER BY format';
		}
		if ($case=='u_consumeALB.user_id') {
			$sql.=' AND u_consumeALB.user_id = u.ID AND u.ID='.$id;
			$sql.=' GROUP BY (CONCAT(u_consumeALB.format,u_consumeALB.user_id)) ORDER BY format';
		}
		if ($case=='u_consumeALB.stelle_id, u_consumeALB.user_id'){
			$sql.=' AND u_consumeALB.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' AND u_consumeALB.user_id = u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY (CONCAT(u_consumeALB.format,u_consumeALB.stelle_id,u_consumeALB.user_id)) ORDER BY format';
		}
		$this->debug->write("<p>file:kvwmap class:account->getAccessToALB:<br>".$sql,4);
		#echo $sql.'<br><br>';
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
			$NumbOfAccess[]=$rs;
			$sql ='SELECT u_consumeALB.time_id, concat(u.Vorname, " ", u.Name) as Name';
			$sql.=' FROM user AS u, stelle AS s, u_consumeALB';
			if(LAYER_IDS_DOP) $sql.=' LEFT JOIN u_consume2layer c2l LEFT JOIN layer l ON l.Layer_ID = c2l.layer_id ON c2l.time_id = u_consumeALB.time_id AND c2l.user_id = u_consumeALB.user_id AND c2l.stelle_id = u_consumeALB.stelle_id AND c2l.layer_id IN ('.LAYER_IDS_DOP.')';
			$sql.=' WHERE 1=1';
			if ($zeitraum=='month' OR $zeitraum=='week')  {
				$sql.=' AND '.$era.'(u_consumeALB.time_id)='.$date.' AND YEAR(u_consumeALB.time_id)='.$year;
			}
			if ($zeitraum=='day'){
				$sql.=' AND ('.$era.'(u_consumeALB.time_id))="'.$date.'"';
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
			$query_array[]=mysql_query($sql);
			if ($query_array[count($query_array)-1]==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
			$NumbOfAccessTimeIDs = array();
			while($rs=mysql_fetch_array($query_array[count($query_array)-1])) {
				$NumbOfAccessTimeIDs[]=$rs;
			}
			$NumbOfAccess[count($NumbOfAccess)-1]['time_ids'] = $NumbOfAccessTimeIDs;
		}
		return $NumbOfAccess;
	} #END of function getAccessToALB

	function getAccessToALK($nutzung,$zeitraum,$date1,$date2,$case,$era,$date,$year,$id,$id_2){
		# Abfrage der Anzahl der PDF-Exporte mit Druckrahmen
		# in Abhängigkeit von Stelle, Nutzer und Zeitraum
		$sql ='SELECT u_consumeALK.druckrahmen_id,'.$case.',count(u_consumeALK.time_id) AS NumberOfAccess,';
		if(LAYER_IDS_DOP) $sql.='c2l.layer_id, concat(druckrahmen.Name, \' \',ifnull(l.Name, \'\')) AS druckrahmenname';
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
		$sql.=' FROM druckrahmen';
		if ($nutzung=='stelle'){
			$sql.=' , stelle AS s';
		}
		if ($nutzung=='nutzer') {
			$sql.=' , user AS u';
		}
		if ($nutzung=='stelle_nutzer') {
			$sql.=' ,stelle AS s, user AS u';
		}
		$sql.=', u_consumeALK';
		if(LAYER_IDS_DOP) $sql.=' LEFT JOIN u_consume2layer c2l LEFT JOIN layer l ON l.Layer_ID = c2l.layer_id ON c2l.time_id = u_consumeALK.time_id AND c2l.user_id = u_consumeALK.user_id AND c2l.stelle_id = u_consumeALK.stelle_id AND c2l.layer_id IN ('.LAYER_IDS_DOP.')';
		$sql.=' WHERE u_consumeALK.druckrahmen_id = druckrahmen.id';

		if ($zeitraum=='month' OR $zeitraum=='week')  {
			$sql.=' AND '.$era.'(u_consumeALK.time_id)='.$date.' AND YEAR(u_consumeALK.time_id)='.$year;
		}
		if ($zeitraum=='day'){
			$sql.=' AND ('.$era.'(u_consumeALK.time_id))="'.$date.'"';
		}
		if ($zeitraum=='era') {
			$sql.=' AND ((DATE(u_consumeALK.time_id)) BETWEEN "'.$date1.'"  AND "'.$date2.'")';
		}
		if(LAYER_IDS_DOP) $groupby = 'ifnull(c2l.layer_id, ""), ';
		if ($case=='u_consumeALK.stelle_id'){
			$sql.=' AND u_consumeALK.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' GROUP BY (CONCAT('.$groupby.'u_consumeALK.druckrahmen_id,u_consumeALK.stelle_id)) ORDER BY druckrahmen.Name';
		}
		if ($case=='u_consumeALK.user_id') {
			$sql.=' AND u_consumeALK.user_id = u.ID AND u.ID='.$id;
			$sql.=' GROUP BY (CONCAT('.$groupby.'u_consumeALK.druckrahmen_id,u_consumeALK.user_id)) ORDER BY druckrahmen.Name';
		}
		if ($case=='u_consumeALK.stelle_id, u_consumeALK.user_id'){
			$sql.=' AND u_consumeALK.stelle_id = s.ID AND s.ID='.$id;
			$sql.=' AND u_consumeALK.user_id = u.ID AND u.ID='.$id_2;
			$sql.=' GROUP BY (CONCAT('.$groupby.'u_consumeALK.druckrahmen_id,u_consumeALK.stelle_id,u_consumeALK.user_id)) ORDER BY Bezeichnung,druckrahmen.Name,u.Name';
		}
		#echo $sql.'<br><br>';
		$this->debug->write("<p>file:kvwmap class:account->getAccessToALK:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
			$NumbOfAccess[]=$rs;
			$sql ='SELECT u_consumeALK.time_id, concat(u.Vorname, " ", u.Name) as Name';
			$sql.=' FROM druckrahmen, user AS u, stelle AS s, u_consumeALK';
			if(LAYER_IDS_DOP) $sql.=' LEFT JOIN u_consume2layer c2l LEFT JOIN layer l ON l.Layer_ID = c2l.layer_id ON c2l.time_id = u_consumeALK.time_id AND c2l.user_id = u_consumeALK.user_id AND c2l.stelle_id = u_consumeALK.stelle_id AND c2l.layer_id IN ('.LAYER_IDS_DOP.')';
			$sql.=' WHERE u_consumeALK.druckrahmen_id = druckrahmen.id';
			if ($zeitraum=='month' OR $zeitraum=='week')  {
				$sql.=' AND '.$era.'(u_consumeALK.time_id)='.$date.' AND YEAR(u_consumeALK.time_id)='.$year;
			}
			if ($zeitraum=='day'){
				$sql.=' AND ('.$era.'(u_consumeALK.time_id))="'.$date.'"';
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
			$query_array[]=mysql_query($sql);
			if ($query_array[count($query_array)-1]==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
			$NumbOfAccessTimeIDs = array();
			while($rs=mysql_fetch_array($query_array[count($query_array)-1])) {
				$NumbOfAccessTimeIDs[]=$rs;
			}
			$NumbOfAccess[count($NumbOfAccess)-1]['time_ids'] = $NumbOfAccessTimeIDs;
		}
		return $NumbOfAccess;
	} #END of function getAccessToALK

	function getLayer($logged){
		# Abfrage der Anzahl der Layer
		$sql ='SELECT COUNT(Layer_ID) AS layers FROM layer';
		if ($logged) {
			$sql.=' WHERE logconsume="1"';
		}
		$this->debug->write("<p>file:kvwmap class:account->getNumber_of_Access_to_Layer:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		$rs=mysql_fetch_array($query);
		$this->AnzLayer=$rs;
		return $this->AnzLayer;
	} # END of function getLayer

	function getAllAccess($case){
		# Abfragen aller Zugriffe der Layer
		$sql ='SELECT count(time_id) AS allAccess FROM u_consume'.$case;
		$query=mysql_query($sql);
		$rs=mysql_fetch_array($query);
		$this->allAccess=$rs;
		return $this->allAccess;
	} # END of function getAllAccess

	function epoch(){
		# Abfragen, für welchen Zeitraum die statistische Abfrage möglich ist
		$sql ='SELECT day(MIN(time_id)) AS min_d, month(MIN(time_id)) AS min_m, year(MIN(time_id)) AS min_y' .
		$sql.=' , day(MAX(time_id)) AS max_d, month(MAX(time_id)) AS max_m, year(MAX(time_id)) AS max_y';
		$sql.=' FROM `u_consume2layer`';
		$this->debug->write("<p>file:kvwmap class:account->getNumber_of_Access_to_Layer:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
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
	# todo
	# Beim Anlegen eines neuen Benutzers müssen die Einstellungen für die Karte
	# aus der Stellenbeschreibung als Anfangswerte übernommen werden

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

	
	function user($login_name,$id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->database=$database;
		if($login_name){
			$this->login_name=$login_name;
			$this->readUserDaten(0,$login_name);
			$this->remote_addr=getenv('REMOTE_ADDR');
		}
		else{
			$this->id = $id;
			$this->readUserDaten($id,0);
		}
	}
	
	function clientIpIsValide($remote_addr) {
    # Prüfen ob die übergebene IP Adresse zu den für den Nutzer eingetragenen Adressen passt
    $ips=explode(';',$this->ips);
    foreach ($ips AS $ip) {
      if (trim($ip)!='') {
        $ip=trim($ip);
				if(!is_numeric(array_pop(explode('.', $ip))))$ip = gethostbyname($ip);			# für dyndns-Hosts
        if (in_subnet($remote_addr, $ip)) {
          $this->debug->write('<br>IP:'.$remote_addr.' paßt zu '.$ip,4);
          #echo '<br>IP:'.$remote_addr.' paßt zu '.$ip;
          return 1;
        }
      }
    }
    return 0;
  }

  function readUserDaten($id,$login_name) {
    $sql ='SELECT * FROM user WHERE 1=1';
    if ($id>0) {
      $sql.=' AND ID='.$id;
    }
    if ($login_name!='') {
      $sql.=' AND login_name LIKE "'.$login_name.'"';
    }
    $this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->id=$rs['ID'];
    $this->login_name=$rs['login_name'];
    $this->Namenszusatz=$rs['Namenszusatz'];
    $this->Name=$rs['Name'];
    $this->Vorname=$rs['Vorname'];
    $this->stelle_id=$rs['stelle_id'];
    $this->phon=$rs['phon'];
    $this->email=$rs['email'];
    if (CHECK_CLIENT_IP) {
      $this->ips=$rs['ips'];
    }
    $this->password_setting_time=$rs['password_setting_time'];
  }
  
  function getLastStelle() {
    $sql = 'SELECT stelle_id FROM user WHERE ID='.$this->id;
    $this->debug->write("<p>file:users.php class:user->getLastStelle - Abfragen der zuletzt genutzten Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['stelle_id'];
  }
  
	function setSize($mapsize) {
		$this->rolle->setSize($mapsize);
		return 1;
	}

	function setRolle($stelle_id) {
		# Abfragen und zuweisen der Einstellungen für die Rolle		
		$rolle=new rolle($this->id,$stelle_id,$this->database);		
		if ($rolle->readSettings()) {
			$this->rolle=$rolle;			
			return 1;
		}
		return 0;
	}

	function getall_Users($order){
		$sql ='SELECT * FROM user';
		if($order != ''){$sql .= ' ORDER BY '.$order;}
		$this->debug->write("<p>file:kvwmap class:user->getall_Users - Lesen aller User:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
			$user['ID'][]=$rs['ID'];
			$user['Bezeichnung'][]=$rs['Name'].', '.$rs['Vorname'];
		}
		// Sortieren der User unter Berücksichtigung von Umlauten
		$sorted_arrays = umlaute_sortieren($user['Bezeichnung'], $user['ID']);
		$user['Bezeichnung'] = $sorted_arrays['array'];
		$user['ID'] = $sorted_arrays['second_array'];
		return $user;
	}

	function get_Unassigned_Users(){
		# Lesen der User, die keiner Stelle zugeordnet sind
		$sql ='SELECT * FROM user WHERE ID NOT IN (SELECT DISTINCT user.ID FROM user, rolle WHERE rolle.user_id = user.ID) ORDER BY Name';
		$this->debug->write("<p>file:users.php class:user->get_Unassigned_Users - Lesen der User zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$user['ID'][]=$rs['ID'];
				$user['Bezeichnung'][]=$rs['Name'].', '.$rs['Vorname'];
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
		$sql ='SELECT * FROM user WHERE stop != "0000-00-00 00:00:00" AND "'.date('Y-m-d h:i:s').'" > stop ORDER BY Name';
		$this->debug->write("<p>file:users.php class:user->get_Expired_Users - Lesen der User zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$user['ID'][]=$rs['ID'];
				$user['Bezeichnung'][]=$rs['Name'].', '.$rs['Vorname'];
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
	
	function getUserDaten($id,$login_name,$order) {
		$sql ='SELECT * FROM user WHERE 1=1';
		if ($id>0) {
			$sql.=' AND ID='.$id;
		}
		if ($login_name!='') {
			$sql.=' AND login_name LIKE "'.$login_name.'"';
		}
		if ($order!='') {
			$sql.=' ORDER BY '.$order;
		}
		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_array($query)) {
			$userdaten[]=$rs;
		}
		return $userdaten;
	}

	function getFunktion($id) {
		# Abfragen der Rollen und Funktion, die dem Benutzer zugewiesen sind
		$sql ="SELECT Name, Vorname, Funktion FROM user,rolle";
		$sql.=" WHERE user.ID=rolle.user_id";
		$sql.=" AND user.ID LIKE '".$id."' AND passwort LIKE '".$passwort."'";
		$this->debug->write("<p>file:users.php class:user->getFunktion - Abfragen des Namens des Benutzers:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		if (mysql_num_rows($query_id)==0) {
			$err_nr = 1;
			$view='anmelden';
		}
	}

	function getStellen($stelle_ID) {
		$sql ='SELECT s.ID,s.Bezeichnung FROM stelle AS s,rolle AS r';
		$sql.=' WHERE s.ID=r.stelle_id AND r.user_id='.$this->id;
		if ($stelle_ID>0) {
			$sql.=' AND s.ID='.$stelle_ID;
		}
		# Zeiteinschränkung
		$sql.=' AND (';
		# Zeiteinschränkung wird berücksichtigt
		$sql.='("'.date('Y-m-d h:i:s').'" >= s.start AND "'.date('Y-m-d h:i:s').'" <= s.stop)';
		$sql.=' OR ';
		# Zeiteinschränkung wird nicht berücksichtigt.
		$sql.='(s.start="0000-00-00 00:00:00" AND s.stop="0000-00-00 00:00:00")';
		$sql.=')';
		$sql.=' ORDER BY Bezeichnung';
		#echo $sql;
		$this->debug->write("<p>file:users.php class:user->getStellen - Abfragen der Stellen die der User einnehmen darf:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs=mysql_fetch_array($query)) {
			$stellen['ID'][]=$rs['ID'];
			$stellen['Bezeichnung'][]=$rs['Bezeichnung'];
		}
		return $stellen;
	}

	function updateStelleID($stelle_id) {
		# sezten der aktuell für den Nutzer eingestellten Stelle
		$sql ='UPDATE user SET stelle_id='.$stelle_id.' WHERE ID='.$this->id;
		$this->debug->write("<p>file:users.php class:user->setStelle - Setzen der aktuellen Stelle<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$this->debug->write('Stelle gewechselt, neue Stellen_ID: '.$neueStelle,4);
	}

	function setStelle($stelle_id,$formvars) {
		# Speicherung dass der User diese Stelle als letztes genutzt hat
		# setzen der Werte, die aktuell für die Nutzung der Stelle durch den Nutzer gelten sollen.
		$this->updateStelleID($stelle_id);
		# zerlegen der Variable für die Kartengröße
		$teil=explode('x',$formvars['mapsize']);
		$nImageWidth=$teil[0];
		$nImageHeight=$teil[1];
		if($teil[2] == 'auto')$auto_map_resize = '1';
		else $auto_map_resize = '0';
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
		if($formvars['gui'] != '' AND $formvars['mapsize'] != ''){
			$sql ='UPDATE rolle SET nZoomFactor='.$formvars['nZoomFactor'].',nImageWidth='.$nImageWidth;
			$sql.=',nImageHeight='.$nImageHeight.',gui="'.$formvars['gui'].'"';
			$sql.=',auto_map_resize='.$auto_map_resize;
			$sql.=',epsg_code="'.$formvars['epsg_code'].'"';
			$sql.=',epsg_code2="'.$formvars['epsg_code2'].'"';
			$sql.=',coordtype="'.$formvars['coordtype'].'"';
			$sql.=',minx='.$newExtent['minx'].',miny='.$newExtent['miny'];
			$sql.=',maxx='.$newExtent['maxx'].',maxy='.$newExtent['maxy'];
			$sql.=',language="'.$formvars['language'].'"';
			if($formvars['fontsize_gle'])$sql.=',fontsize_gle="'.$formvars['fontsize_gle'].'"';
			if($formvars['highlighting'] != '')	$sql.=',highlighting="1"';
			else $sql.=',highlighting="0"';
			$sql.=',result_color="'.$formvars['result_color'].'"';
			if($formvars['runningcoords'] != '') $sql.=',runningcoords="1"';
			else	$sql.=',runningcoords="0"';
			if($formvars['singlequery'] != '') $sql.=',singlequery="1"';
			else $sql.=',singlequery="0"';
			if($formvars['instant_reload'] != '') $sql.=',instant_reload="1"';
			else $sql.=',instant_reload="0"';
			if($formvars['menu_auto_close'] != '') $sql.=',menu_auto_close="1"';
			else $sql.=',menu_auto_close="0"';
			$sql .= ', visually_impaired=' . (($formvars['visually_impaired'] != '') ? '"1"' : '"0"');
			if($formvars['querymode'] != '') $sql.=',querymode="1"';
			else $sql.=',querymode="0", overlayx=400, overlayy=150';
			$sql.=',geom_edit_first="'.$formvars['geom_edit_first'].'"';
			if($formvars['hist_timestamp'] != '') $sql.=',hist_timestamp="'.DateTime::createFromFormat('d.m.Y H:i:s', $formvars['hist_timestamp'])->format('Y-m-d H:i:s').'"';
			else $sql.=',hist_timestamp = NULL';
			if($formvars['back']){$buttons .= 'back,';}
			if($formvars['forward']){$buttons .= 'forward,';}
			if($formvars['zoomin']){$buttons .= 'zoomin,';}
			if($formvars['zoomout']){$buttons .= 'zoomout,';}
			if($formvars['zoomall']){$buttons .= 'zoomall,';}
			if($formvars['recentre']){$buttons .= 'recentre,';}
			if($formvars['jumpto']){$buttons .= 'jumpto,';}
			if($formvars['coord_query']){$buttons .= 'coord_query,';}
			if($formvars['query']){$buttons .= 'query,';}
			if($formvars['touchquery']){$buttons .= 'touchquery,';}
			if($formvars['queryradius']){$buttons .= 'queryradius,';}
			if($formvars['polyquery']){$buttons .= 'polyquery,';}
			if($formvars['measure']){$buttons .= 'measure,';}
			if($formvars['freepolygon']){$buttons .= 'freepolygon,';}
			if($formvars['freearrow']){$buttons .= 'freearrow,';}
			if($formvars['freetext']){$buttons .= 'freetext';}
			if($buttons != '')$sql.=",buttons = '".$buttons."'";
			$sql.=",selectedButton='zoomin'";

			$sql.=' WHERE stelle_id='.$stelle_id.' AND user_id='.$this->id;
			#echo $sql;
			$this->debug->write("<p>file:users.php class:user->setStelle - Setzen der Einstellungen für die Rolle<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
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
		if(count($stellen['ID']) > 0){
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
		$sql ='SELECT * FROM user WHERE ID='.$id;
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Die Abfrage konnte nicht ausgeführt werden.'.$ret[1];
		}
		else {
			if (mysql_num_rows($ret[1])>0) {
				$ret[1]=1;
			}
			else {
				$ret[1]=0;
			}
		}
		return $ret;
	}

	function loginname_exists($login) {
		$Meldung='';
		# testen ob es einen user unter dieser id in der Datenbanktabelle gibt
		$sql ="SELECT * FROM user WHERE login_name='".$login."'";
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Die Abfrage konnte nicht ausgeführt werden.'.$ret[1];
		}
		else {
			if (mysql_num_rows($ret[1])>0) {
				$ret[1]=1;
			}
			else {
				$ret[1]=0;
			}
		}
		return $ret;
	}

	function checkUserDaten($userdaten) {
		$Meldung='';
		# Prüfen ob die user_id schon existiert
		if ($userdaten['id']!='') {
			$ret=$this->exist($userdaten['id']);
			if ($ret[0]) {
				$Meldung.=$ret[1];
			}
		}
		if ($userdaten['nachname']=='') { $Meldung.='<br>Nachname fehlt.'; }
		if ($userdaten['vorname']=='') { $Meldung.='<br>Vorname fehlt.'; }
		if ($userdaten['loginname']=='') { $Meldung.='<br>Login Name fehlt.'; }
		elseif($userdaten['go_plus'] == 'Als neuen Nutzer eintragen'){
			$ret=$this->loginname_exists($userdaten['loginname']);
			if ($ret[1] == 1) {
				$Meldung.= '<br>Es existiert bereits ein Nutzer mit diesem Loginnamen.';
			}
		}
		if($userdaten['changepasswd'] == 1){
			if ($userdaten['password1']=='') { $Meldung.='<br>Die erste Passwordeingabe fehlt.'; }
			if ($userdaten['password2']=='') { $Meldung.='<br>Die Passwordwiederholung fehlt.'; }
			if ($userdaten['password1']!=$userdaten['password2']) { $Meldung.='<br>Die Passwörter stimmen nicht überein.'; }
		}
		if ($userdaten['phon']!='' AND strlen($userdaten['phon'])<3) { $Meldung.='<br>Die Telefonnummer ist zu kurz.'; }
		if ($userdaten['email']!='') { $Meldung.=emailcheck($userdaten['email']); }
		if ($Meldung!='') {
			$ret[0]=1; $ret[1]=$Meldung;
		}
		else {
			$ret[0]=0;
		}
		return $ret;
	}

	function NeuAnlegen($userdaten) {
		$stellen = explode(', ',$userdaten['selstellen']);
		# Neuen Nutzer anlegen
		$sql ='INSERT INTO user SET';
		if($userdaten['id'] != ''){
			$sql.=' ID='.$userdaten['id'].', ';
		}
		$sql.=' Name="'.$userdaten['nachname'].'"';
		$sql.=',Vorname="'.$userdaten['vorname'].'"';
		$sql.=',login_name="'.$userdaten['loginname'].'"';
		$sql.=',Namenszusatz="'.$userdaten['Namenszusatz'].'"';
		$sql.=',passwort=MD5("'.$userdaten['password2'].'")';
		$sql.=',password_setting_time=CURRENT_TIMESTAMP()';
		if ($userdaten['phon']!='') {
			$sql.=',phon="'.$userdaten['phon'].'"';
		}
		if ($userdaten['email']!='') {
			$sql.=',email="'.$userdaten['email'].'"';
		}
		$sql.=',start="'.$userdaten['start'].'"';
		$sql.=',stop="'.$userdaten['stop'].'"';
		if ($userdaten['ips']!='') {
			$sql.=',ips="'.$userdaten['ips'].'"';
		}
		if($stellen[0] != ''){
			$sql.=',stelle_id='.$stellen[0];
		}
		# Abfrage starten
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1].='<br>Die Benutzerdaten konnten nicht eingetragen werden.<br>'.$ret[1];
		}
		else {
			# User Erfolgreich angelegt
			# Abfragen der user_id des neu eingetragenen Benutzers
			$sql ='SELECT ID FROM user WHERE';
			$sql.=' Name="'.$userdaten['nachname'].'"';
			$sql.=' AND Vorname="'.$userdaten['vorname'].'"';
			$sql.=' AND login_name="'.$userdaten['loginname'].'"';
			if ($userdaten['phon']!='') {
				$sql.=' AND phon="'.$userdaten['phon'].'"';
			}
			if ($userdaten['email']!='') {
				$sql.=' AND email="'.$userdaten['email'].'"';
			}
			# Starten der Anfrage
			$ret=$this->database->execSQL($sql,4, 0);
			#echo $sql;
			if ($ret[0]) {
				# Fehler bei der Datenbankanfrage
				$ret[1].='<br>Die Benutzerdaten konnten nicht eingetragen werden.<br>'.$ret[1];
			}
			else {
				# Abfrage erfolgreich durchgeführt, übergeben der user_id zur Rückgabe der Funktion
				$rs=mysql_fetch_array($ret[1]);
				$ret[1]=$rs['ID'];
			}
		}
		return $ret;
	}

	function Aendern($userdaten) {
		$sql ='UPDATE user SET';
		if($userdaten['id'] != ''){
			$sql.=' ID='.$userdaten['id'].', ';
		}
		$sql.=' Name="'.$userdaten['nachname'].'"';
		$sql.=',Vorname="'.$userdaten['vorname'].'"';
		$sql.=',login_name="'.$userdaten['loginname'].'"';
		$sql.=',Namenszusatz="'.$userdaten['Namenszusatz'].'"';
		if($userdaten['changepasswd']){
			$sql.=',passwort=MD5("'.$userdaten['password2'].'")';
			$sql.=',password_setting_time=CURRENT_TIMESTAMP()';
		}
		if ($userdaten['phon']!='') {
			$sql.=',phon="'.$userdaten['phon'].'"';
		}
		if ($userdaten['email']!='') {
			$sql.=',email="'.$userdaten['email'].'"';
		}
		$sql.=',start="'.$userdaten['start'].'"';
		$sql.=',stop="'.$userdaten['stop'].'"';
		$sql.=',ips="'.$userdaten['ips'].'"';
		$sql.=' WHERE ID='.$userdaten['selected_user_id'];
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Die Benutzerdaten konnten nicht aktualisiert werden.<br>'.$ret[1];
		}
		return $ret;
	}

	/**
	 * Aktualisiert das Passwort und setzt ein neuen Zeitstempel
	 *
	 * Diese Funktion trägt für den Benutzer in diesem Objekt ein neues Passwort ein und setzt als Datum das aktuelle Datum.
	 *
	 * Reihenfolge: Übersichtssatz - Kommentar - Tags.
	 *
	 * @param string password Einzutragendes Password als Text
	 * @return array liefert zweidimensionales Array zurück,
	 *                 Wenn array[0]=0 enthält array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
	 *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthält eine Fehlermeldung.
	 * @see    NeuAnlegen(), Aendern(), Loeschen(), $user, $rolle, $stelle
	 */
	function setNewPassword($password) {
		$sql ='UPDATE user SET';
		$sql.=' passwort=MD5("'.$password.'")';
		$sql.=',password_setting_time=CURRENT_TIMESTAMP()';
		$sql.=' WHERE ID='.$this->id;
		#echo $sql;
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Die Benutzerdaten konnten nicht aktualisiert werden.<br>'.$ret[1];
		}
		return $ret;
	}

	function Löschen($user_id) {
		$sql ='DELETE FROM user';
		$sql.=' WHERE ID = '.$user_id;
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Der Benutzer konnte nicht gelöscht werden.<br>'.$ret[1];
		}
		else {
			$sql ='ALTER TABLE `user` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0';
			$sql.=' AUTO_INCREMENT =1';
			$ret=$this->database->execSQL($sql,4, 0);
			if ($ret[0]) {
				$ret[1].='<br>Das Autoincrement für die Tabelle Benutzer konnte nicht zurückgesetzt werden.<br>'.$ret[1];
			}
		}
		return $ret;
	}
}

###################################
# class_rolle #
class rolle {
	var $user_id;
	var $stelle_id;
	var $debug;
	var $database;
	var $loglevel;
	static $hist_timestamp;

	function rolle($user_id,$stelle_id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->user_id=$user_id;
		$this->stelle_id=$stelle_id;
		$this->database=$database;
		#$this->layerset=$this->getLayer('');
		#$this->groupset=$this->getGroups('');
		$this->loglevel = 0;
	}

	function setGroupStatus($formvars) {
		$this->groupset=$this->getGroups('');
		# Eintragen des group_status=1 für Gruppen, die angezeigt werden sollen
		for ($i=0;$i<count($this->groupset);$i++) {
			if($formvars['group_'.$this->groupset[$i]['id']] !== NULL){
				if ($formvars['group_'.$this->groupset[$i]['id']] == 1) {
					$group_status=1;
				}
				else {
					$group_status=0;
				}
				$sql ='UPDATE u_groups2rolle set status="'.$group_status.'"';
				$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
				$sql.=' AND id='.$this->groupset[$i]['id'];
				$this->debug->write("<p>file:users.php class:rolle->setGroupStatus - Speichern des Status der Gruppen zur Rolle:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
		return $formvars;
	}

  function setSelectedButton($selectedButton) {
    $this->selectedButton=$selectedButton;
    # Eintragen des aktiven Button
    $sql ='UPDATE rolle SET selectedButton="'.$selectedButton.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    $this->debug->write("<p>file:users.php class:rolle->setSelectedButton - Speichern des zuletzt gewählten Buttons aus dem Kartenfensters:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }

  function getLayer($LayerName) {
		global $language;

		# Abfragen der Layer in der Rolle
		if($language != 'german') {
			$name = "CASE WHEN `Name_{$language}` != '' THEN `Name_{$language}` ELSE `Name` END";
		}
		else {
			$name = 'name';
		}

		if ($LayerName != '') {
			$where_name = " AND (l.Name LIKE '{$LayerName}'";
			if(is_numeric($LayerName)) {
				$where_name .= " OR l.Layer_ID = {$LayerName}";
			}
			$where_name .= ")";
		}

		$sql = "
			SELECT
				{$name} AS Name,
				l.Layer_ID,
				alias, Datentyp, Gruppe, pfad, maintable, maintable_is_view, Data, `schema`, document_path, labelitem, connection,
				printconnection, connectiontype, epsg_code, tolerance, toleranceunits, wms_name, wms_auth_username, wms_auth_password,
				wms_server_version, ows_srs, wfs_geom, selectiontype, querymap, processing, kurzbeschreibung, datenherr, metalink,
				status,
				trigger_function,
				ul.`queryable`, ul.`drawingorder`, ul.`minscale`, ul.`maxscale`, ul.`offsite`,
				coalesce(r2ul.transparency, ul.transparency, 100) as transparency,
				ul.`postlabelcache`, `Filter`,
				CASE r2ul.gle_view WHEN '0' THEN 'generic_layer_editor.php' ELSE ul.`template` END as template,
				`header`,
				`footer`,
				ul.`symbolscale`,
				ul.`logconsume`,
				ul.`requires`,
				ul.`privileg`,
				ul.`export_privileg`,
				`start_aktiv`,
				r2ul.showclasses
			FROM
				layer AS l,
				used_layer AS ul,
				u_rolle2used_layer as r2ul
			WHERE
				l.Layer_ID = ul.Layer_ID AND
				r2ul.Stelle_ID = ul.Stelle_ID AND
				r2ul.Layer_ID = ul.Layer_ID AND
				ul.Stelle_ID = {$this->stelle_id} AND
				r2ul.User_ID={$this->user_id}
				{$where_name}
			ORDER BY ul.drawingorder desc
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:users.php class:rolle->getLayer - Abfragen der Layer zur Rolle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$i = 0;
		while ($rs=mysql_fetch_assoc($query)) {
			$layer[$i]=$rs;
			$layer['layer_ids'][$rs['Layer_ID']] =& $layer[$i];
			$layer['layer_ids'][$layer[$i]['requires']]['required'] = $rs['Layer_ID'];
			$i++;
		}
		return $layer;
	}

  # 2006-02-11 pk
  function getAktivLayer($aktivStatus,$queryStatus,$logconsume) {
    # Abfragen der zu loggenden Layer der Rolle
    $sql ='SELECT r2ul.layer_id FROM u_rolle2used_layer AS r2ul';
    if ($logconsume) {
      $sql.=',used_layer AS ul,layer AS l,stelle AS s';
    }
    $sql.=' WHERE r2ul.user_id='.$this->user_id.' AND r2ul.stelle_id='.$this->stelle_id;
    if ($logconsume) {
      $sql.=' AND r2ul.layer_id=ul.Layer_ID AND r2ul.stelle_id=ul.Stelle_ID';
      $sql.=' AND ul.Layer_ID=l.Layer_ID AND ul.Stelle_ID=s.ID';
      $sql.=' AND (s.logconsume="1"';
      $sql.=' OR l.logconsume="1"';
      $sql.=' OR ul.logconsume="1"';
      $sql.=' OR r2ul.logconsume="1")';
    }
    $anzaktivStatus=count($aktivStatus);
    if ($anzaktivStatus>0) {
      $sql.=' AND r2ul.aktivStatus IN ("'.$aktivStatus[0].'"';
      for ($i=1;$i<$anzaktivStatus;$i++) {
        $sql.=',"'.$aktivStatus[$i].'"';
      }
      $sql.=')';
    }
    $anzqueryStatus=count($queryStatus);
    if ($anzqueryStatus>0) {
      $sql.=' AND r2ul.queryStatus IN ("'.$queryStatus[0].'"';
      for ($i=1;$i<$anzqueryStatus;$i++) {
        $sql.=',"'.$queryStatus[$i].'"';
      }
      $sql.=')';
    }
    #echo $sql;
    $this->debug->write("<p>file:users.php class:rolle->getAktivLayer - Abfragen der aktiven Layer zur Rolle:<br>".$sql,4);
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Die aktiven Layer konnten nicht abgefragt werden.<br>'.$ret[1];
    }
    else {
      while ($rs=mysql_fetch_array($queryret[1])) {
        $layer[]=$rs['layer_id'];
      }
      $ret[0]=0;
      $ret[1]=$layer;
    }
    return $ret;
  }

  function getGroups($GroupName) {
		global $language;
    # Abfragen der Gruppen in der Rolle
    $sql ='SELECT g2r.*, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Gruppenname_'.$language.'` != "" THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS ';
		}
		$sql.='Gruppenname FROM u_groups AS g, u_groups2rolle AS g2r ';
    $sql.=' WHERE g2r.stelle_ID='.$this->stelle_id.' AND g2r.user_id='.$this->user_id;
    $sql.=' AND g2r.id = g.id';
    if ($GroupName!='') {
      $sql.=' AND Gruppenname LIKE "'.$GroupName.'"';
    }
    $this->debug->write("<p>file:users.php class:rolle->getGroups - Abfragen der Gruppen zur Rolle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $groups[]=$rs;
    }
    return $groups;
  }
	
	function switch_gle_view($layer_id) {
    $sql ='UPDATE u_rolle2used_layer SET gle_view = CASE WHEN gle_view IS NULL THEN 0 ELSE NOT gle_view END';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND layer_id='.$layer_id;
     #echo $sql;
    $this->debug->write("<p>file:users.php class:rolle function:switch_gle_view - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }
		
	function setLanguage($language) {
    $sql ='UPDATE rolle SET language="'.$language.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    # echo $sql;
    $this->debug->write("<p>file:users.php class:rolle function:setLanguage - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }
	
  function saveSettings($extent) {
    $sql ='UPDATE rolle SET minx='.$extent->minx.',miny='.$extent->miny;
    $sql.=',maxx='.$extent->maxx.',maxy='.$extent->maxy;
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    # echo $sql;
    $this->debug->write("<p>file:users.php class:rolle function:saveSettings - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }
  
	function saveDrawmode($always_draw){
		if($always_draw == '')$always_draw = 'false';
    $sql ='UPDATE rolle SET always_draw = '.$always_draw;
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    #echo $sql;
    $this->debug->write("<p>file:users.php class:rolle function:saveDrawmode - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }

	function setHistTimestamp($timestamp, $go_next = '') {
		$sql ='UPDATE rolle SET ';
		if($timestamp != ''){
			$time = new DateTime(DateTime::createFromFormat('d.m.Y H:i:s', $timestamp)->format('Y-m-d H:i:s'));
			$sql.='hist_timestamp="'.$time->format('Y-m-d H:i:s').'"';
			showAlert('Der Zeitpunkt der ALKIS-Historie wurde auf '.$time->format('d.m.Y H:i:s').' geändert.');
		}
		else{
			$sql.='hist_timestamp = NULL';
			if(rolle::$hist_timestamp != '')showAlert('Der Zeitpunkt der ALKIS-Historie ist jetzt wieder aktuell.');
		}
		$sql.=' WHERE stelle_id='.$this->stelle_id.' AND user_id='.$this->user_id;		
		#echo $sql;
		$this->debug->write("<p>file:users.php class:user->setHistTimestamp - Setzen der Einstellungen für die Rolle<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$this->debug->write('Neue Werte für Rolle eingestellt: '.$formvars['nZoomFactor'].', '.$formvars['mapsize'],4);
		if($go_next != '')echo "<script>window.location.href='index.php?go=".$go_next."';</script>";
	}
	
  function readSettings() {
		global $language;
    # Abfragen und Zuweisen der Einstellungen der Rolle
    $sql ='SELECT * FROM rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    #echo $sql;
    $this->debug->write("<p>file:users.php class:rolle function:readSettings - Abfragen der Einstellungen der Rolle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
		if(mysql_num_rows($query) > 0){
			$rs=mysql_fetch_assoc($query);
			$this->oGeorefExt=ms_newRectObj();
			$this->oGeorefExt->setextent($rs['minx'],$rs['miny'],$rs['maxx'],$rs['maxy']);
			$this->nImageWidth=$rs['nImageWidth'];
			$this->nImageHeight=$rs['nImageHeight'];			
			$this->mapsize=$this->nImageWidth.'x'.$this->nImageHeight;
			$this->auto_map_resize=$rs['auto_map_resize'];
			@$this->pixwidth=($rs['maxx']-$rs['minx'])/$rs['nImageWidth'];
			@$this->pixheight=($rs['maxy']-$rs['miny'])/$rs['nImageHeight'];
			$this->pixsize=($this->pixwidth+$this->pixheight)/2;
			$this->nZoomFactor=$rs['nZoomFactor'];
			$this->epsg_code=$rs['epsg_code'];
			$this->epsg_code2=$rs['epsg_code2'];
			$this->coordtype=$rs['coordtype'];
			$this->last_time_id=$rs['last_time_id'];
			$this->gui=$rs['gui'];
			$this->language=$rs['language'];
			$language = $this->language;
			$this->hideMenue=$rs['hidemenue'];
			$this->hideLegend=$rs['hidelegend'];
			$this->fontsize_gle=$rs['fontsize_gle'];
			$this->highlighting=$rs['highlighting'];
			$this->scrollposition=$rs['scrollposition'];
			$this->result_color=$rs['result_color'];
			$this->always_draw=$rs['always_draw'];
			$this->runningcoords=$rs['runningcoords'];
			$this->singlequery=$rs['singlequery'];
			$this->querymode=$rs['querymode'];
			$this->geom_edit_first=$rs['geom_edit_first'];		
			$this->overlayx=$rs['overlayx'];
			$this->overlayy=$rs['overlayy'];
			$this->instant_reload=$rs['instant_reload'];
			$this->menu_auto_close=$rs['menu_auto_close'];
			$this->visually_impaired = $rs['visually_impaired'];
			if($rs['hist_timestamp'] != ''){
				$this->hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('d.m.Y H:i:s');			# der wird zur Anzeige des Timestamps benutzt
				rolle::$hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('Y-m-d\TH:i:s\Z');	# der hat die Form, wie der timestamp in der PG-DB steht und wird für die Abfragen benutzt
			}
			else rolle::$hist_timestamp = $this->hist_timestamp = '';
			$this->selectedButton=$rs['selectedButton'];
			$buttons = explode(',', $rs['buttons']);
			$this->back = in_array('back', $buttons);
			$this->forward = in_array('forward', $buttons);
			$this->zoomin = in_array('zoomin', $buttons);
			$this->zoomout = in_array('zoomout', $buttons);
			$this->zoomall = in_array('zoomall', $buttons);
			$this->recentre = in_array('recentre', $buttons);
			$this->jumpto = in_array('jumpto', $buttons);
			$this->coord_query = in_array('coord_query', $buttons);			
			$this->query = in_array('query', $buttons);
			$this->queryradius = in_array('queryradius', $buttons);
			$this->polyquery = in_array('polyquery', $buttons);
			$this->touchquery = in_array('touchquery', $buttons);
			$this->measure = in_array('measure', $buttons);
			$this->freepolygon = in_array('freepolygon', $buttons);
			$this->freetext = in_array('freetext', $buttons);
			$this->freearrow = in_array('freearrow', $buttons);
			return 1;
		}else return 0;
  }
	  
  function set_last_time_id($time){
    # Eintragen der last_time_id
    $sql = 'UPDATE rolle SET last_time_id="'.$time.'"';
    $sql.= ' WHERE user_id = '.$this->user_id.' AND stelle_id = '.$this->stelle_id;
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    return $ret;
  }

  # 2006-02-16 pk
  function getLastConsumeTime() {
    $sql ='SELECT time_id,prev FROM u_consume';
    $sql.=' WHERE stelle_id='.$this->stelle_id.' AND user_id='.$this->user_id;
    $sql.=' ORDER BY time_id DESC limit 1';
    #echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Fehler bei der Abfrage der letzten Zugriffszeit.<br>'.$ret[1];
    }
    else {
      $rs=mysql_fetch_array($queryret[1]);
      $ret[0]=0;
      $ret[1]=$rs;
    }
    return $ret;
  }

  # 2006-02-16 pk
  function  getConsume($consumetime) {
    $sql ='SELECT * FROM u_consume';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    $sql.=' AND time_id="'.$consumetime.'"';
    #echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Fehler bei der Abfrage der letzten Zugriffszeit.<br>'.$ret[1];
    }
    else {
      $rs=mysql_fetch_array($queryret[1]);
      $ret[0]=0;
      $ret[1]=$rs;
    }
    return $ret;
  }
  
# 2006-03-20 pk
  function updateNextConsumeTime($time_id,$nexttime) {
    $sql ='UPDATE u_consume SET next="'.$nexttime.'"';
    $sql.=' WHERE time_id="'.$time_id.'"';
    #echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Fehler bei der Aktualisierung des Zeitstempels des Nachfolgers Next.<br>'.$ret[1];
    }
    else {
      $ret[0]=0;
      $ret[1]=1;
    }
    return $ret;
  }

  # 2006-03-20 pk
  function updatePrevConsumeTime($time_id,$prevtime) {
    $sql ='UPDATE u_consume SET prev="'.$prevtime.'"';
    $sql.=' WHERE time_id="'.$time_id.'"';
    #echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Fehler bei der Aktualisierung des Zeitstempels des Vorgängers Prev.<br>'.$ret[1];
    }
    else {
      $ret[0]=0;
      $ret[1]=1;
    }
    return $ret;
  }


  function setConsumeALK($time,$druckrahmen_id) {
    if (LOG_CONSUME_ACTIVITY==1) {
      # function setzt eine ALK-PDF-EXportaktivität
      $sql ='INSERT INTO u_consumeALK SET';
      $sql.=' user_id='.$this->user_id;
      $sql.=', stelle_id='.$this->stelle_id;
      $sql.=', time_id="'.$time.'"';
      $sql .= ', druckrahmen_id = "'.$druckrahmen_id.'"';
      #echo $sql;
      $ret=$this->database->execSQL($sql,4, 1);
      if ($ret[0]) {
        # Fehler bei Datenbankanfrage
        $errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
      }
    }
    else {
      $ret[0]=0;
      $ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
    }
    return $ret;
  }


# 2006-03-20 pk
  function setConsumeActivity($time,$activity,$prevtime) {
    if (LOG_CONSUME_ACTIVITY==1) {
      # function setzt eine Verbraucheraktivität (den Zugriff auf Layer oder Daten)
      # Starten der Transaktion
      $sql ='START TRANSACTION';
      $ret=$this->database->execSQL($sql,4, 1);
      if ($ret[0]) {
        # Fehler bei Datenbankanfrage
        $ret[1]='<br>Die Transaktion zur Eintragung der Verbraucheraktivität konnte gestartet werden.<br>'.$ret[1];
      }
      else {
        # Eintragen der Consume Activity
        $sql ='INSERT INTO u_consume SET';
        $sql.=' user_id='.$this->user_id;
        $sql.=', stelle_id='.$this->stelle_id;
        $sql.=', time_id="'.$time.'"';
        $sql.=',activity="'.$activity.'"';
        if ($prevtime=="0000-00-00 00:00:00" OR $prevtime=='') {
          $prevtime=$time;
        }
        $sql.=',prev="'.$prevtime.'"';        
        $sql.=', nimagewidth='.$this->nImageWidth.',nimageheight='.$this->nImageHeight;
				$sql.=", epsg_code='".$this->epsg_code."'";
        $sql.=', minx='.$this->oGeorefExt->minx.', miny='.$this->oGeorefExt->miny;
        $sql.=', maxx='.$this->oGeorefExt->maxx.', maxy='.$this->oGeorefExt->maxy;
        #echo $sql;
        $ret=$this->database->execSQL($sql,4, 1);
        
        if ($ret[0]) {
          # Fehler bei Datenbankanfrage
          $errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
        }
        if($activity != 'print' AND $activity != 'print_preview'){    # bei der Druckvorschau und dem PDF-Export zwar loggen aber nicht in die History aufnehmen
          $this->newtime = $time;
          $ret = $this->set_last_time_id($time);
          if ($ret[0]) {
            # Fehler bei Datenbankanfrage
            $errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
          }
        }
        
        # Abfragen der aktiven Layer
        $ret=$this->getAktivLayer(array(1,2),array(),1);
        if ($ret[0]) {
          # Fehler bei Datenbankanfrage
          $errmsg.='<br>Fehler bei der Abfrage der aktiven Layer.<br>'.$ret[1];
        }
        else {
          $layer=$ret[1];
          # Eintragung des Zugriffs auf die angeschalteten Layer
          for ($i=0;$i<count($layer);$i++) {
            # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            # Hier eventuell später mal einbauen, dass geprüft wird, ob die Layer wirklich verfügbar sind.
            # Wichtig wird das besonders für externe Datenquellen wie fremde WMS oder WFS Layer
            # Die dürfen nicht mit abgerechnet werden, wenn sie beim Client nicht erscheinen.
            # bzw. nicht geliefert werden
            $sql ='INSERT INTO u_consume2layer SET';
            $sql.=' user_id='.$this->user_id;
            $sql.=', stelle_id='.$this->stelle_id;
            $sql.=', time_id="'.$time.'"';
            $sql.=', layer_id='.$layer[$i];
            $ret=$this->database->execSQL($sql,4, 1);
            if ($ret[0]) {
              # Fehler bei Datenbankanfrage
              $errmsg.='<br>Die Verbraucheraktivität für den Zugiff auf den Layer: '.$layer[$i].' konnte nicht eingetragen werden.<br>'.$ret[1];
            }
          } # ende eintragen aktiver Layer
        } # ende erfolgreiches Abfragen der aktiven Layer
      } # ende kartenausschnitt loggen
      if ($errmsg!='') {
        # Es sind Fehler innerhalb der Transaktion aufgetreten, Abbrechen der Transaktion
        $sql ='ROLLBACK TRANSACTION';
        $ret=$this->database->execSQL($sql,4, 1);
        if ($ret[0]) {
          # Fehler bei Datenbankanfrage
          $errmsg.='<br>Die Transaktion zum Eintragen der Verbraucheraktivität konnte nicht abgebrochen werden.<br>'.$ret[1];
        }
        $ret[0]=1;
        $ret[1]=$errmsg;
      }
      else {
        # Es sind keine Fehler innerhalb der Transaktion aufgetreten, Erfolgreich abschließen der Transaktion
        $sql ='COMMIT';
        $ret=$this->database->execSQL($sql,4, 1);
        if ($ret[0]) {
          # Fehler bei Datenbankanfrage
          $errmsg.='<br>Die Transaktion zum Eintragen der Verbraucheraktivität konnte nicht erfolgreich abgeschlossen werden.<br>'.$ret[1];
        }
        $ret[0]=0;
        $ret[1]='<br>Verbraucheraktivität erfolgreich eingetragen.';
      }
    }
    else {
      $ret[0]=0;
      $ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
    }
    return $ret;
  }
	
	function save_last_query($go, $layer_id, $query, $sql_order, $limit, $offset){
		if($limit == '')$limit = 'NULL';
		if($offset == '')$offset = 'NULL';
		$sql = "INSERT INTO rolle_last_query (user_id, stelle_id, go, layer_id, `sql`, orderby, `limit`, `offset`) VALUES (";
		$sql.= $this->user_id.", ".$this->stelle_id.", '".$go."', ".$layer_id.", '".addslashes($query)."', '".$sql_order."', ".$limit.", ".$offset.")";
		$this->debug->write("<p>file:users.php class:rolle->save_last_query - Speichern der letzten Abfrage:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}
	
	function delete_last_query(){
		$sql = "DELETE FROM rolle_last_query WHERE user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
		$this->debug->write("<p>file:users.php class:rolle->delete_last_query - Löschen der letzten Abfrage:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}
	
	function get_last_query($layer_id = NULL){
		$sql = "SELECT * FROM rolle_last_query WHERE user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
		if($layer_id != NULL)$sql .= " AND layer_id = ".$layer_id;
		$this->debug->write("<p>file:users.php class:rolle->get_last_query - Abfragen der letzten Abfrage:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_assoc($query)) {
			$last_query['go'] = $rs['go'];
			$last_query['layer_ids'][] = $rs['layer_id'];
			$last_query[$rs['layer_id']] = $rs;
		}
		return $last_query;
	}
	
	function get_csv_attribute_selections(){
		$sql = 'SELECT name FROM rolle_csv_attributes WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' ORDER BY name';
		$this->debug->write("<p>file:users.php class:rolle->get_csv_attribute_selections - Abfragen der gespeicherten CSV-Attributlisten der Rolle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_assoc($query)) {
			$attribute_selections[]=$rs;
		}
		return $attribute_selections;
	}

	function get_csv_attribute_selection($name){
		$sql = 'SELECT name, attributes FROM rolle_csv_attributes WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND name = "'.$name.'"';
		$this->debug->write("<p>file:users.php class:rolle->get_csv_attribute_selection - Abfragen einer CSV-Attributliste der Rolle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_assoc($query);
		return $rs;
	}

	function save_csv_attribute_selection($name, $attributes){
		# alle anderen Listen unter dem Namen löschen
		$this->delete_csv_attribute_selection($name);
		$sql = 'INSERT INTO rolle_csv_attributes (user_id, stelle_id, name, attributes) VALUES ('.$this->user_id.', '.$this->stelle_id.', "'.$name.'", "'.$attributes.'");';
		$this->debug->write("<p>file:users.php class:rolle->save_search - Speichern einer Attributauswahl:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function delete_csv_attribute_selection($name){
		if($name != ''){
			$sql = 'DELETE FROM rolle_csv_attributes WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND name = "'.$name.'"';
			$this->debug->write("<p>file:users.php class:rolle->delete_search - Loeschen einer Suchabfrage:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function save_search($attributes, $formvars){
		# alle anderen Suchabfragen unter dem Namen löschen
		$this->delete_search($formvars['search_name'], $formvars['selected_layer_id']);
		for($m = 0; $m <= $formvars['searchmask_count']; $m++){
			if($m > 0){				// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
				$prefix = $m.'_';
			}
			else{
				$prefix = '';
			}
			for($i = 0; $i < count($attributes['name']); $i++){
				if($formvars[$prefix.'value_'.$attributes['name'][$i]] != '' OR $formvars[$prefix.'operator_'.$attributes['name'][$i]] == 'IS NULL' OR $formvars[$prefix.'operator_'.$attributes['name'][$i]] == 'IS NOT NULL'){
					$sql = 'INSERT INTO search_attributes2rolle VALUES ("'.$formvars['search_name'].'", '.$this->user_id.', '.$this->stelle_id.', '.$formvars['selected_layer_id'].', "'.$attributes['name'][$i].'", "'.$formvars[$prefix.'operator_'.$attributes['name'][$i]].'", "'.$formvars[$prefix.'value_'.$attributes['name'][$i]].'", "'.$formvars[$prefix.'value2_'.$attributes['name'][$i]].'", '.$m.', "'.$formvars['boolean_operator_'.$m].'");';
					$this->debug->write("<p>file:users.php class:rolle->save_search - Speichern einer Suchabfrage:",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
			}
		}
	}

	function delete_search($search, $layer_id){
		if($search != ''){
			$sql = 'DELETE FROM search_attributes2rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND layer_id='.$layer_id.' AND name = "'.$search.'"';
			$this->debug->write("<p>file:users.php class:rolle->delete_search - Loeschen einer Suchabfrage:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function getsearches($layer_id){
		$sql = 'SELECT distinct a.name, a.layer_id, b.Name as layername FROM search_attributes2rolle as a, layer as b WHERE a.layer_id = b.Layer_ID AND user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		if($layer_id != '') $sql.= ' AND a.layer_id='.$layer_id;
		$sql .= ' ORDER BY b.Name, a.name';
		$this->debug->write("<p>file:users.php class:rolle->getsearches - Abfragen der gespeicherten Suchabfragen der Rolle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_assoc($query)) {
			$searches[]=$rs;
		}
		return $searches;
	}

	function getsearch($layer_id, $name){
		$sql = 'SELECT * FROM search_attributes2rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND layer_id='.$layer_id.' AND name = "'.$name.'" ORDER BY searchmask_number DESC';
		$this->debug->write("<p>file:users.php class:rolle->getsearch - Abfragen der gespeicherten Suchabfrage:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_assoc($query)) {
			$search[]=$rs;
		}
		return $search;
	}

	function read_Group($id) {
		$sql ='SELECT g2r.*, g.Gruppenname FROM u_groups AS g, u_groups2rolle AS g2r';
		$sql.=' WHERE g2r.stelle_ID='.$this->stelle_id.' AND g2r.user_id='.$this->user_id.' AND g2r.id = g.id AND g.id='.$id;
		$this->debug->write("<p>file:kvwmap class:rolle->read_Group - Lesen einer Gruppe der Rolle:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		$rs=mysql_fetch_array($query);
		return $rs;
	}

	function setOneLayer($layer_id, $status){
		$sql ='UPDATE u_rolle2used_layer SET aktivStatus="'.$status.'"';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$layer_id;
		$this->debug->write("<p>file:users.php class:rolle->setOneLayer - Setzen eines Layers:",4);
		$this->database->execSQL($sql,4, $this->loglevel);

		$sql ='UPDATE u_rolle2used_layer set queryStatus="'.$status.'"';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$layer_id;
		$this->debug->write("<p>file:users.php class:rolle->setOneLayer - Setzen eines Layers:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setScrollPosition($scrollposition){
		if($scrollposition != ''){
			$sql = 'UPDATE rolle SET scrollposition = '.$scrollposition;
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$this->debug->write("<p>file:users.php class:rolle->setOneLayer - Setzen eines Layers:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function getRollenLayer($LayerName, $typ = NULL) {
    $sql ="SELECT l.*, -l.id as Layer_ID, l.query as pfad, 1 as queryable FROM rollenlayer AS l";
    $sql.=' WHERE l.stelle_id = '.$this->stelle_id.' AND l.user_id = '.$this->user_id;
    if ($LayerName!='') {
      $sql.=' AND (l.Name LIKE "'.$LayerName.'" ';
      if(is_numeric($LayerName)){
        $sql.='OR l.id = "'.$LayerName.'")';
      }
      else{
        $sql.=')';
      }
    }
		if($typ != NULL){
			$sql .= " AND Typ = '".$typ."'";
		}
    #echo $sql.'<br>';
    $this->debug->write("<p>file:users.php class:rolle->getRollenLayer - Abfragen der Rollenlayer zur Rolle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$layer = array();
    while ($rs=mysql_fetch_assoc($query)) {
      $layer[]=$rs;
    }
    return $layer;
  }
	
	function resetLayers($layer_id){
		$mapdb = new db_mapObj($this->stelle_id, $this->user_id);
		$sql ="UPDATE u_rolle2used_layer SET aktivStatus='0'";
		$sql.=" WHERE user_id=".$this->user_id." AND stelle_id=".$this->stelle_id;
		if($layer_id != ''){
			if($layer_id > 0){
				$sql.=" AND layer_id = ".$layer_id;					# 1 normaler Layer soll deaktiviert werden
			}
			else{
				$mapdb->deleteRollenLayer(-$layer_id);			# 1 Rollenlayer soll deaktiviert=gelöscht werden
				# auch die Klassen und styles löschen
				if($rollenlayerset[$i]['Class'] != ''){
					foreach($rollenlayerset[$i]['Class'] as $class){
						$mapdb->delete_Class($class['Class_ID']);
						foreach($class['Style'] as $style){
							$mapdb->delete_Style($style['Style_ID']);
						}
					}
				}
				return;
			}
		}
		else{
			$rollenlayerset = $mapdb->read_RollenLayer();					# wenn alle Layer deaktiviert werden sollen, auch alle Rollenlayer löschen
			for($i = 0; $i < count($rollenlayerset); $i++){
				if($formvars['thema_rolle'.$rollenlayerset[$i]['id']] == 0){
					$mapdb->deleteRollenLayer($rollenlayerset[$i]['id']);
					# auch die Klassen und styles löschen
					if($rollenlayerset[$i]['Class'] != ''){
						foreach($rollenlayerset[$i]['Class'] as $class){
							$mapdb->delete_Class($class['Class_ID']);
							foreach($class['Style'] as $style){
								$mapdb->delete_Style($style['Style_ID']);
							}
						}
					}
				}
			}
		}
		$this->debug->write("<p>file:users.php class:rolle->resetLayers - resetten aller aktiven Layer zur Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);		
	}

	function resetQuerys($layer_id){
		$sql ="UPDATE u_rolle2used_layer SET queryStatus='0'";
		$sql.=" WHERE user_id=".$this->user_id." AND stelle_id=".$this->stelle_id;
		if($layer_id != '')$sql.=" AND layer_id = ".$layer_id;
		$this->debug->write("<p>file:users.php class:rolle->resetQuerys - resetten aller aktiven Layer zur Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function resetClasses(){
		$sql = 'DELETE FROM u_rolle2used_class WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$this->debug->write("<p>file:users.php class:rolle->resetQuerys - resetten aller aktiven Layer zur Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}
	
	function setAktivLayer($formvars, $stelle_id, $user_id, $ignore_rollenlayer = false) {
		$this->layerset=$this->getLayer('');
		if(!$ignore_rollenlayer){
			$rollenlayer=$this->getRollenLayer('', NULL);
			$this->layerset = array_merge($this->layerset, $rollenlayer);
		}
		# Eintragen des Status der Layer, 1 angezeigt oder 0 nicht.
		for ($i=0;$i<count($this->layerset);$i++) {
			#echo $i.' '.$this->layerset[$i]['Layer_ID'].' '.$formvars['thema'.$this->layerset[$i]['Layer_ID']].'<br>';
			$aktiv_status = $formvars['thema'.$this->layerset[$i]['Layer_ID']];
			$requires_status = $formvars['thema'.$this->layerset[$i]['requires']];
			if(isset($aktiv_status) OR isset($requires_status)){										// entweder ist der Layer selber an oder sein requires-Layer
				$aktiv_status = $aktiv_status + $requires_status;
				$sql ='UPDATE u_rolle2used_layer SET aktivStatus="'.$aktiv_status.'"';
				$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
				$sql.=' AND layer_id='.$this->layerset[$i]['Layer_ID'];
				$this->debug->write("<p>file:users.php class:rolle->setAktivLayer - Speichern der aktiven Layer zur Rolle:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
				
				#Anne
				#neu eintragen der deaktiven Klassen
				if($aktiv_status != 0){
					$sql = 'SELECT Class_ID FROM classes WHERE Layer_ID='.$this->layerset[$i]['Layer_ID'].';';
					$query = mysql_query($sql);
					while($row = @mysql_fetch_array($query)){
						if($formvars['class'.$row['Class_ID']] == '0' OR $formvars['class'.$row['Class_ID']] == '2'){
							$sql2 = 'REPLACE INTO u_rolle2used_class (user_id, stelle_id, class_id, status) VALUES ('.$this->user_id.', '.$this->stelle_id.', '.$row['Class_ID'].', '.$formvars['class'.$row['Class_ID']].');';
							$this->database->execSQL($sql2,4, $this->loglevel);
						}
						elseif($formvars['class'.$row['Class_ID']] == '1'){
							$sql1 = 'DELETE FROM u_rolle2used_class WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND class_id='.$row['Class_ID'].';';
							$this->database->execSQL($sql1,4, $this->loglevel);
						}
					}
				}
			}
		}
		if(!$ignore_rollenlayer){
			$mapdb = new db_mapObj($stelle_id, $user_id);
			$rollenlayerset = $mapdb->read_RollenLayer();
			for($i = 0; $i < count($rollenlayerset); $i++){
				if($formvars['thema'.$rollenlayerset[$i]['Layer_ID']] == 0){
					$mapdb->deleteRollenLayer($rollenlayerset[$i]['id']);
					$mapdb->delete_layer_attributes(-$rollenlayerset[$i]['id']);
					# auch die Klassen und styles löschen
					if($rollenlayerset[$i]['Class'] != ''){
						foreach($rollenlayerset[$i]['Class'] as $class){
							$mapdb->delete_Class($class['Class_ID']);
							if($class['Style'] != ''){
								foreach($class['Style'] as $style){
									$mapdb->delete_Style($style['Style_ID']);
								}
							}
						}
					}
				}
			}
		}
		return 1;
	}

	function setQueryStatus($formvars) {
		# Eintragen des query_status=1 für Layer, die für die Abfrage selektiert wurden
		for ($i=0;$i<count($this->layerset);$i++){
			$query_status = $formvars['qLayer'.$this->layerset[$i]['Layer_ID']];
			if(isset($query_status)){	
				if($this->layerset[$i]['Layer_ID'] > 0){
					$sql ='UPDATE u_rolle2used_layer set queryStatus="'.$query_status.'"';
					$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
					$sql.=' AND layer_id='.$this->layerset[$i]['Layer_ID'];
				}
				else{		# Rollenlayer
					$sql ='UPDATE rollenlayer set queryStatus="'.$query_status.'"';
					$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
					$sql.=' AND id='.-$this->layerset[$i]['Layer_ID'];
				}
				$this->debug->write("<p>file:users.php class:rolle->setQueryStatus - Speichern des Abfragestatus der Layer zur Rolle:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
		return 1;
	}

	function setClassStatus($formvars) {
		if($formvars['layer_id'] != ''){
			# Eintragen des showclasses=1 für Klassen, die angezeigt werden sollen
			$sql ='UPDATE u_rolle2used_layer set showclasses = "'.$formvars['show_classes'].'"';
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$sql.=' AND layer_id='.$formvars['layer_id'];
			$this->debug->write("<p>file:users.php class:rolle->setClassStatus - Speichern des Status der Klassen zur Rolle:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}
	
	function setTransparency($formvars) {
		if($formvars['layer_options_open'] > 0){		# normaler Layer
			$sql ='UPDATE u_rolle2used_layer set transparency = '.$formvars['layer_options_transparency'];
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$sql.=' AND layer_id='.$formvars['layer_options_open'];
			$this->debug->write("<p>file:users.php class:rolle->setTransparency:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
		elseif($formvars['layer_options_open'] < 0){		# Rollenlayer
			$sql ='UPDATE rollenlayer set transparency = '.$formvars['layer_options_transparency'];
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$sql.=' AND id= -1*'.$formvars['layer_options_open'];
			$this->debug->write("<p>file:users.php class:rolle->setTransparency:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}
	
	function removeTransparency($formvars) {
		$sql ='UPDATE u_rolle2used_layer set transparency = NULL';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$formvars['layer_options_open'];
		$this->debug->write("<p>file:users.php class:rolle->setTransparency:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setSize($mapsize) {
		# setzen der Werte, die aktuell für die Nutzung der Stelle durch den Nutzer gelten sollen.
		$teil=explode('x',$mapsize);
		$nImageWidth=$teil[0];
		$nImageHeight=$teil[1];
		$sql ='UPDATE rolle SET nImageWidth='.$nImageWidth;
		$sql.=',nImageHeight='.$nImageHeight.' WHERE stelle_id='.$this->stelle_id.' AND user_id='.$this->user_id;
		$this->debug->write("<p>file:users.php class:user->setStelle - Setzen der Einstellungen für die Rolle",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		$this->debug->write('Neue Werte für Rolle eingestellt: '.$formvars['nZoomFactor'].', '.$formvars['mapsize'],4);
		return 1;
	}

	function setRollen($user_id,$stellen) {
		# trägt die Stellen für einen Benutzer ein.
		$sql ='INSERT IGNORE INTO rolle (user_id, stelle_id, epsg_code) ';
		$sql.= 'SELECT '.$user_id.', ID, epsg_code FROM stelle WHERE ID IN ('.implode($stellen, ',').')';
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:rolle function:setRollen - Einfügen neuen Rollen:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function deleteRollen($user_id,$stellen) {
		# löscht die übergebenen Stellen für einen Benutzer.
		for ($i=0;$i<count($stellen);$i++) {
			$sql ='DELETE FROM `rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stellen[$i];
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:users.php class:rolle function:deleteRollen - Löschen der Rollen:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# rolle_nachweise
			$sql ='DELETE FROM `rolle_nachweise` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stellen[$i];
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:users.php class:rolle function:deleteRollen - Löschen der Rollen:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}


	function setMenue($user_id,$stellen) {
		# trägt die Menuepunkte der übergebenen Stellenids für einen Benutzer ein.
		for ($i=0;$i<count($stellen);$i++) {
			$sql ='INSERT IGNORE INTO u_menue2rolle SELECT '.$user_id.', '.$stellen[$i].', menue_id, 0';
			$sql.=' FROM u_menue2stelle';
			$sql.=' WHERE u_menue2stelle.stelle_id = '.$stellen[$i];
			#echo '<br>Menue: '.$sql;
			$this->debug->write("<p>file:users.php class:rolle function:setMenue - Setzen der Menuepunkte der Rollen:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function deleteMenue($user_id,$stellen,$menues) {
		# löscht die Menuepunkte der übergebenen Stellen für einen Benutzer.
		if($menues == 0){
			for ($i=0;$i<count($stellen);$i++) {
				# löscht alle Menuepunkte der Stelle
				$sql ='DELETE FROM `u_menue2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stellen[$i];
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:users.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		else{
			for ($i=0;$i<count($stellen);$i++) {
				for ($j=0;$j<count($menues);$j++) {
					$sql ='DELETE FROM `u_menue2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stellen[$i];
					$sql.=' AND `menue_id` = '.$menues[$j];
					#echo '<br>'.$sql;
					$this->debug->write("<p>file:users.php class:rolle function:deleteMenue - Löschen der Menuepunkte der Rollen:<br>".$sql,4);
					$query=mysql_query($sql,$this->database->dbConn);
					if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }

					/*  $sql ='SELECT id FROM u_menues WHERE obermenue = '.$menues[$i];
					 #echo '<br>'.$sql;
					 $this->debug->write("<p>file:users.php class:rolle->deleteMenue - Lesen der Untermenuepunkte zu den Obermenuepunken zur Stelle:<br>".$sql,4);
					 $query=mysql_query($sql,$this->database->dbConn);
					 if ($query==0) {
					 $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
					 }
					 else{
					 while($rs=mysql_fetch_array($query)) {
					 $sql ='DELETE FROM `u_menue2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stellen[$i].' AND `menue_id` = '.$rs[0];
					 #echo '<br>'.$sql;
					 $this->debug->write("<p>file:users.php class:rolle->deleteMenue - Löschen von Menuepunkten zur Rolle:<br>".$sql,4);
					 $query1=mysql_query($sql,$this->database->dbConn);
					 if ($query1==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
					 }
					 }
					 */
				}
			}
		}
		return 1;
	}


	function set_one_Group($user_id, $stelle_id, $group_id, $open) {
		$sql ='REPLACE INTO u_groups2rolle VALUES('.$user_id.', '.$stelle_id.', '.$group_id.', '.$open.')';
		$this->debug->write("<p>file:users.php class:rolle function:setGroups - Setzen der Gruppen der Rollen:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function setGroups($user_id, $stellen, $layerids, $open) {
		# trägt die Gruppen und Obergruppen der übergebenen Stellenids und Layerids für einen Benutzer ein.
		for($i = 0; $i < count($stellen); $i++) {
			for($j = 0; $j < count($layerids); $j++){
				$sql ='INSERT IGNORE INTO u_groups2rolle SELECT DISTINCT '.$user_id.', '.$stellen[$i].', u_groups.id, '.$open;
				$sql.=' FROM (SELECT @id AS id, @id := IF(@id IS NOT NULL, (SELECT obergruppe FROM u_groups WHERE id = @id), NULL) AS obergruppe';
				$sql.='       FROM u_groups, (SELECT @id := (SELECT Gruppe FROM layer where layer.Layer_ID = '.$layerids[$j].')) AS vars';
				$sql.='       WHERE @id IS NOT NULL';
				$sql.='	    ) AS dat';
				$sql.='	JOIN u_groups ON dat.id = u_groups.id';
				#echo '<br>Gruppen: '.$sql;
				$this->debug->write("<p>file:users.php class:rolle function:setGroups - Setzen der Gruppen der Rollen:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}

	function deleteGroups($user_id,$stellen) {
		# löscht die Gruppen der übergebenen Stellen für einen Benutzer.
		for ($i=0;$i<count($stellen);$i++) {
			$sql ='DELETE FROM `u_groups2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stellen[$i];
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:users.php class:rolle function:deleteGroups - Löschen der Gruppen der Rollen:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function updateGroups($user_id,$stelle_id, $layer_id) {
		# überprüft anHand der übergebenen layer_id ob die entsprechende Gruppe und deren Obergruppen in u_groups2rolle überflüssig sind
		#
		# Abfragen der Gruppe und der Obergruppen
		$sql ='SELECT DISTINCT u_groups.id';
		$sql.=' FROM (SELECT @id AS id, @id := IF(@id IS NOT NULL, (SELECT obergruppe FROM u_groups WHERE id = @id), NULL) AS obergruppe';
		$sql.='       FROM u_groups, (SELECT @id := (SELECT Gruppe FROM layer where layer.Layer_ID = '.$layer_id.')) AS vars';
		$sql.='       WHERE @id IS NOT NULL';
		$sql.='	    ) AS dat';
		$sql.='	JOIN u_groups ON dat.id = u_groups.id';
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:rolle function:updateGroups - überprüft anHand der übergebenen layer_id ob die entsprechende Gruppe in u_groups2rolle überflüssig ist:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while($rs = mysql_fetch_array($query)) {
			$gruppen_ids[] = $rs[0];
		}
		
		# Test ob Layer in den Gruppen vorhanden sind
		$sql ='SELECT DISTINCT Gruppe FROM layer, u_rolle2used_layer AS r2ul WHERE Gruppe IN ('.implode(',', $gruppen_ids).') AND ';
		$sql.='r2ul.layer_id = layer.Layer_ID AND ';
		$sql.='r2ul.user_id = '.$user_id.' AND ';
		$sql.='r2ul.stelle_id = '.$stelle_id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:rolle function:updateGroups - überprüft anHand der übergebenen layer_id ob die entsprechende Gruppe in u_groups2rolle überflüssig ist:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while($rs = mysql_fetch_array($query)){
			$rs_layer[$rs['Gruppe']] = $rs['Gruppe'];		# ein Array mit den GruppenIDs, die noch Layer haben
		}
		
		# Test ob Untergruppen in den Gruppen vorhanden sind
		$sql ='SELECT u_groups.id FROM u_groups, u_groups2rolle as r2g WHERE u_groups.id NOT IN ('.implode(',', $gruppen_ids).') AND u_groups.obergruppe IN ('.implode(',', $gruppen_ids).') AND ';
		$sql.='r2g.id = u_groups.id AND ';
		$sql.='r2g.user_id = '.$user_id.' AND ';
		$sql.='r2g.stelle_id = '.$stelle_id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:rolle function:updateGroups - überprüft anHand der übergebenen layer_id ob die entsprechende Gruppe in u_groups2rolle überflüssig ist:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$rs_subgroups = mysql_fetch_array($query);
		
		if($rs_layer[$gruppen_ids[0]] == ''){					# wenn die erste Gruppe, also die Gruppe des Layers keine Layer hat, diese löschen
			$sql ='DELETE FROM `u_groups2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stelle_id.' AND `id` = '.$gruppen_ids[0].';';
			if($rs_layer == '' AND !$rs_subgroups[0]){				# wenn darüberhinaus keine Layer oder Untergruppen in den Gruppen darüber vorhanden sind, diese auch löschen
				$sql ='DELETE FROM `u_groups2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stelle_id.' AND `id` IN ('.implode(',', $gruppen_ids).');';
				$this->debug->write("<p>file:users.php class:rolle function:deleteGroups - Löschen der Gruppen der Rollen:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
			$this->debug->write("<p>file:users.php class:rolle function:deleteGroups - Löschen der Gruppen der Rollen:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function set_one_Layer($user_id, $stelle_id, $layer_id,  $active) {
		$sql ='INSERT IGNORE INTO u_rolle2used_layer VALUES ('.$user_id.', '.$stelle_id.', '.$layer_id.', "'.$active.'", "0", "1", "0")';
		$this->debug->write("<p>file:users.php class:rolle function:set_one_Layer - Setzen eines Layers der Rolle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function setLayer($user_id, $stellen, $active) {
		#
		# trägt die Layer der entsprehenden Rollen für einen Benutzer ein.
		for ($i=0;$i<count($stellen);$i++) {
			$sql ='INSERT IGNORE INTO u_rolle2used_layer (user_id, stelle_id, layer_id, aktivStatus, queryStatus, showclasses, logconsume) SELECT '.$user_id.', used_layer.Stelle_ID, used_layer.Layer_ID, "'.$active.'", "0", "1","0"';
			$sql.=' FROM `used_layer`';
			$sql.=' WHERE used_layer.Stelle_ID = '.$stellen[$i];
			$this->debug->write("<p>file:users.php class:rolle function:setLayer - Setzen der Layer der Rollen:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function deleteLayer($user_id,$stellen,$layer) {
		# löscht die Layer der übergebenen Stellen für einen Benutzer.
		for ($i=0;$i<count($stellen);$i++) {
			for ($j=0;$j<count($layer);$j++) {
				$sql ='DELETE FROM `u_rolle2used_layer` WHERE `stelle_id` = '.$stellen[$i];
				if($user_id != 0){
					$sql .= ' AND user_id = '.$user_id;
				}
				if($layer != 0){
					$sql.=' AND `layer_id` = '.$layer[$j];
				}
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:users.php class:rolle function:deleteLayer - Löschen der Layer der Rollen:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}

	function readcolor(){
		return $this->database->read_color($this->result_color);
	}

	function hideMenue($hide) {
		# speichern des Zustandes des Menües
		# hide=0 Menü ist zu sehen
		# hide=1 Menü wird nicht angezeigt
		$sql ="UPDATE rolle SET hidemenue='".$hide."'";
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:users.php class:rolle function:hideMenue - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}

	function changeLegendDisplay($hide) {
		# speichern des Zustandes der Legende
		# hide=0 Legende ist zu sehen
		# hide=1 Legende wird nicht angezeigt
		$sql ="UPDATE rolle SET hidelegend='".$hide."'";
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:users.php class:rolle function:hideMenue - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}
	
	function saveOverlayPosition($x, $y){
		if($x < 0)$x = 10;
		$sql ="UPDATE rolle SET overlayx = ".$x.", overlayy=".abs($y);
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:users.php class:rolle function:saveOverlayPosition - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}

	function getMapComments($consumetime) {
		$sql ='SELECT time_id,comment FROM u_consume2comments WHERE';
		$sql.=' user_id='.$this->user_id;
		$sql.=' AND stelle_id='.$this->stelle_id;
		if ($consumetime!='') {
			$sql.=' AND time_id="'.$consumetime.'"';
		}
		$sql.=' ORDER BY time_id DESC';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 0);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Laden des Kommentares zum Kartenausschnitt.<br>'.$ret[1];
		}
		else {
			while ($rs=mysql_fetch_array($queryret[1])) {
				$mapComments[]=$rs;
			}
			$ret[0]=0;
			$ret[1]=$mapComments;
		}
		return $ret;
	}
	
	function getLayerComments($id) {
		$sql ='SELECT id, name, layers, query FROM rolle_saved_layers WHERE';
		$sql.=' user_id='.$this->user_id;
		$sql.=' AND stelle_id='.$this->stelle_id;
		if($id!=''){
			$sql.=' AND id="'.$id.'"';
		}
		$sql.=' ORDER BY name';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 0);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Laden der Themenauswahl.<br>'.$ret[1];
		}
		else {
			while ($rs=mysql_fetch_array($queryret[1])) {
				$layerComments[]=$rs;
			}
			$ret[0]=0;
			$ret[1]=$layerComments;
		}
		return $ret;
	}

	function insertMapComment($consumetime,$comment) {
		$sql ='REPLACE INTO u_consume2comments SET';
		$sql.=' user_id='.$this->user_id;
		$sql.=', stelle_id='.$this->stelle_id;
		$sql.=', time_id="'.$consumetime.'"';
		$sql.=', comment="'.$comment.'"';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Speichern des Kommentares zum Kartenausschnitt.<br>'.$ret[1];
		}
		else {
			$ret[0]=0;
			$ret[1]=1;
		}
		return $ret;
	}
	
	function insertLayerComment($layerset,$comment) {
		$layers = array();
		$query = array();
		$sql ='REPLACE INTO rolle_saved_layers SET';
		$sql.=' user_id='.$this->user_id;
		$sql.=', stelle_id='.$this->stelle_id;
		$sql.=', name="'.$comment.'"';
		for($i=0; $i < count($layerset); $i++){
			if($layerset[$i]['Layer_ID'] > 0 AND $layerset[$i]['aktivStatus'] == 1){
				$layers[] = $layerset[$i]['Layer_ID'];
				if($layerset[$i]['queryStatus'] == 1)$query[] = $layerset[$i]['Layer_ID'];
			}
		}
		$sql.=', layers="'.implode(',', $layers).'"';
		$sql.=', query="'.implode(',', $query).'"';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Speichern des Kommentares zur Layerauswahl.<br>'.$ret[1];
		}
		else {
			$ret[0]=0;
			$ret[1]=1;
		}
		return $ret;
	}

	function deleteMapComment($storetime){
		$sql = 'DELETE FROM u_consume2comments WHERE user_id = '.$this->user_id.' AND stelle_id = '.$this->stelle_id.' AND time_id = "'.$storetime.'"';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Löschen des Kommentares zum Kartenausschnitt.<br>'.$ret[1];
		}
	}
		
	function deleteLayerComment($id){
		$sql = 'DELETE FROM rolle_saved_layers WHERE user_id = '.$this->user_id.' AND stelle_id = '.$this->stelle_id.' AND id = "'.$id.'"';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Löschen der Themenauswahl.<br>'.$ret[1];
		}
	}

	function setConsumeALB($time,$format,$log_number,$wz,$pagecount) {
		if (LOG_CONSUME_ACTIVITY==1) {
			for($i = 0; $i < count($log_number); $i++){
				# function setzt eine ALB-PDF-EXportaktivität
				$sql ='INSERT INTO u_consumeALB SET';
				$sql.=' user_id='.$this->user_id;
				$sql.=', stelle_id='.$this->stelle_id;
				$sql.=', time_id="'.$time.'"';
				$sql.=', format="'.$format.'"';
				$sql .= ', log_number = "'.$log_number[$i].'"';
				$sql .= ', wz = "'.$wz.'"';
				$sql .= ', numpages = '.$pagecount;
				#echo $sql.'<br>';
				$ret=$this->database->execSQL($sql,4, 1);
				if ($ret[0]) {
					# Fehler bei Datenbankanfrage
					$errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
				}
			}
		}
		else {
			$ret[0]=0;
			$ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
		}
		return $ret;
	}

	function setConsumeCSV($time,$art,$numdatasets) {
		if (LOG_CONSUME_ACTIVITY==1) {
			$sql ='INSERT INTO u_consumeCSV SET';
			$sql.=' user_id='.$this->user_id;
			$sql.=', stelle_id='.$this->stelle_id;
			$sql.=', time_id="'.$time.'"';
			$sql.=', art="'.$art.'"';
			$sql .= ', numdatasets = "'.$numdatasets.'"';
			#echo $sql;
			$ret=$this->database->execSQL($sql,4, 1);
			if ($ret[0]) {
				# Fehler bei Datenbankanfrage
				$errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
			}
		}
		else {
			$ret[0]=0;
			$ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
		}
		return $ret;
	}

	function setConsumeShape($time,$layerid,$numdatasets) {
		if (LOG_CONSUME_ACTIVITY==1) {
			$sql ='INSERT INTO u_consumeShape SET';
			$sql.=' user_id='.$this->user_id;
			$sql.=', stelle_id='.$this->stelle_id;
			$sql.=', time_id="'.$time.'"';
			$sql.=', layer_id="'.$layerid.'"';
			$sql .= ', numdatasets = "'.$numdatasets.'"';
			#echo $sql;
			$ret=$this->database->execSQL($sql,4, 1);
			if ($ret[0]) {
				# Fehler bei Datenbankanfrage
				$errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
			}
		}
		else {
			$ret[0]=0;
			$ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
		}
		return $ret;
	}

}

#####################################
# class_stelle #
class stelle {
	var $id;
	var $Bezeichnung;
	var $debug;
	var $nImageWidth;
	var $nImageHeight;
	var $oGeorefExt;
	var $pixsize;
	var $selectedButton;
	var $database;

	function stelle($id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->id=$id;
		$this->database=$database;
		$this->Bezeichnung=$this->getName();
		$this->readDefaultValues();
	}

	function getsubmenues($id){
		$sql ='SELECT menue_id,';
		if ($this->language != 'german') {
			$sql.='`name_'.$this->language.'` AS ';
		}
		$sql .=' name, target, links FROM u_menue2stelle, u_menues';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND obermenue = '.$id;
		$sql .=' AND menueebene = 2';
		$sql .=' AND u_menue2stelle.menue_id = u_menues.id';
		$sql .= ' ORDER BY menue_order';
		$this->debug->write("<p>file:users.php class:stelle->getsubMenues - Lesen der UnterMenuepunkte eines Menüpunktes:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$menue['name'][]=$rs['name'];
				$menue['target'][]=$rs['target'];
				$menue['links'][]=$rs['links'];
			}
		}
		$html = '<table cellspacing="2" cellpadding="0" border="0">';
		for ($i = 0; $i < count($menue['name']); $i++) {
			$html .='
        <tr>
          <td> 
            <img src="'.GRAPHICSPATH.'leer.gif" width="17" height="1" border="0">
					</td>
					<td>
            <a href="';
			if ($menue['target'][$i]=='confirm') {
				$html .='javascript:Bestaetigung(\'';
			}
			$html .= $menue['links'][$i];
			if ($menue['target'][$i]=='confirm') {
				$html .= '\',\'Diese Aktion wirklich ausf&uuml;hren?\')';
				$menue['target'][$i]='';
			}
			$html .= '" class="menuered"';
			if ($menue['target'][$i]!='') {
				$html .= ' target="'.$menue['target'][$i].'"';
			}
			$html .= '>'.$menue['name'][$i].'</a>
          </td>
        </tr>';
		}
		$html .= '</table>';
		return $html;
	}
	
  function getName() {
    $sql ='SELECT ';
    if ($this->language != 'german' AND $this->language != ''){
      $sql.='`Bezeichnung_'.$this->language.'` AS ';
    }
    $sql.='Bezeichnung FROM stelle WHERE ID='.$this->id;
    #echo $sql;
    $this->debug->write("<p>file:users.php class:stelle->getName - Abfragen des Namens der Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->Bezeichnung=$rs['Bezeichnung'];
    return $rs['Bezeichnung'];
  }

  function readDefaultValues() {
    $sql ='SELECT * FROM stelle WHERE ID='.$this->id;
    $this->debug->write("<p>file:users.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);    
    $this->MaxGeorefExt=ms_newRectObj();
    $this->MaxGeorefExt->setextent($rs['minxmax'],$rs['minymax'],$rs['maxxmax'],$rs['maxymax']);
    $this->epsg_code=$rs["epsg_code"];
    $this->alb_raumbezug=$rs["alb_raumbezug"];
    $this->alb_raumbezug_wert=$rs["alb_raumbezug_wert"];
    $this->pgdbhost = ($rs["pgdbhost"] == 'PGSQL_PORT_5432_TCP_ADDR') ? getenv('PGSQL_PORT_5432_TCP_ADDR') : $rs["pgdbhost"];
    $this->pgdbname=$rs["pgdbname"];
    $this->pgdbuser=$rs["pgdbuser"];
    $this->pgdbpasswd=$rs["pgdbpasswd"];
    $this->protected=$rs["protected"];
    //---------- OWS Metadaten ----------//
    $this->ows_title=$rs["ows_title"];
    $this->ows_abstract=$rs["ows_abstract"];
    $this->wms_accessconstraints=$rs["wms_accessconstraints"];
    $this->ows_contactperson=$rs["ows_contactperson"];
    $this->ows_contactorganization=$rs["ows_contactorganization"];
    $this->ows_contactelectronicmailaddress=$rs["ows_contactemailaddress"];
    $this->ows_contactposition=$rs["ows_contactposition"];
    $this->ows_fees=$rs["ows_fees"];
    $this->ows_srs=$rs["ows_srs"];
    $this->check_client_ip=$rs["check_client_ip"];
    $this->checkPasswordAge=$rs["check_password_age"];
    $this->allowedPasswordAge=$rs["allowed_password_age"];
    $this->useLayerAliases=$rs["use_layer_aliases"];
		$this->hist_timestamp=$rs["hist_timestamp"];
  }

  function checkClientIpIsOn() {
    $sql ='SELECT check_client_ip FROM stelle WHERE ID = '.$this->id;
    $this->debug->write("<p>file:users.php class:stelle->checkClientIpIsOn- Abfragen ob IP's der Nutzer in der Stelle getestet werden sollen<br>".$sql,4);
    #echo '<br>'.$sql;
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    if ($rs['check_client_ip']=='1') {
      return 1;
    }
    return 0;
  }
	
	function Löschen() {
		$sql ='DELETE FROM stelle';
		$sql.=' WHERE ID = '.$this->id;
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Die Stelle konnte nicht gelöscht werden.<br>'.$ret[1];
		}
		return $ret;
	}

	function deleteMenue($menues) {
		if($menues == 0){
			# löscht alle Menuepunkte der Stelle
			$sql ='DELETE FROM `u_menue2stelle` WHERE `stelle_id` = '.$this->id;
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:users.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		else{
			# löscht die übergebenen Menuepunkte der Stelle
			for ($i=0;$i<count($menues);$i++) {
				$sql ='DELETE FROM `u_menue2stelle` WHERE `stelle_id` = '.$this->id.' AND `menue_id` = '.$menues[$i];
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:users.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }

				/*  $sql ='SELECT id FROM u_menues WHERE obermenue = '.$menues[$i];
				 #echo '<br>'.$sql;
				 $this->debug->write("<p>file:users.php class:stelle->deleteMenue - Lesen der Untermenuepunkte zu den Obermenuepunken zur Stelle:<br>".$sql,4);
				 $query=mysql_query($sql,$this->database->dbConn);
				 if ($query==0) {
				 $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
				 }
				 else{
				 while($rs=mysql_fetch_array($query)) {
				 $sql ='DELETE FROM `u_menue2stelle` WHERE `stelle_id` = '.$this->id.' AND `menue_id` = '.$rs[0];
				 #echo '<br>'.$sql;
				 $this->debug->write("<p>file:users.php class:stelle->deleteMenue - Löschen von Menuepunkten zur Stelle:<br>".$sql,4);
				 $query1=mysql_query($sql,$this->database->dbConn);
				 if ($query1==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				 }
				 }
				 */
			}
		}
		return 1;
	}

	function deleteLayer($layer, $pgdatabase) {
		if($layer == 0){
			# löscht alle Layer der Stelle
			$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id;
			$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# Filter löschen
			$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			while($rs=mysql_fetch_row($query)){
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
			}
			$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		else{
			# löscht die übergebenen Layer der Stelle
			for ($i=0;$i<count($layer);$i++) {
				$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id.' AND `layer_id` = '.$layer[$i];
				$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; 	}			
				# Filter löschen
				$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				$rs=mysql_fetch_row($query);
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
				$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}
	
	function deleteDruckrahmen() {
		# löscht alle Druckrahmenzuordnungen der Stelle
		$sql ='DELETE FROM `druckrahmen2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:stelle function:deleteDruckrahmen - Löschen der Druckrahmen der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteStelleGemeinden() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `stelle_gemeinden` WHERE `Stelle_ID` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:stelle function:deleteStelleGemeinden - Löschen der StelleGemeinden der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteFunktionen() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `u_funktion2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:stelle function:deleteFunktionen - Löschen der Funktionen der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function getstellendaten() {
		$sql ='SELECT * FROM stelle';
		$sql.=' WHERE ID = '.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->getstellendaten - Abfragen der Stellendaten<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_array($query);
		return $rs;
	}

	function NeueStelleAnlegen($stellendaten) {
		$_files = $_FILES;
		# Neue Stelle anlegen
		$sql ='INSERT INTO stelle SET';
		if($stellendaten['id'] != ''){
			$sql.=' ID='.$stellendaten['id'].', ';
		}
		$sql.=' Bezeichnung="'.$stellendaten['bezeichnung'].'"';
		$sql.=', Referenzkarte_ID='.$stellendaten['Referenzkarte_ID'];
		$sql.=', alb_raumbezug="'.$stellendaten['alb_raumbezug'].'"';
		$sql.=', alb_raumbezug_wert="'.$stellendaten['alb_raumbezug_wert'].'"';
		$sql.=', minxmax= "'.$stellendaten['minxmax'].'"';
		$sql.=', minymax= "'.$stellendaten['minymax'].'"';
		$sql.=', maxxmax= "'.$stellendaten['maxxmax'].'"';
		$sql.=', maxymax= "'.$stellendaten['maxymax'].'"';
		$sql.=', epsg_code= "'.$stellendaten['epsg_code'].'"';
		$sql.=', start= "'.$stellendaten['start'].'"';
		$sql.=', stop= "'.$stellendaten['stop'].'"';
		if ($stellendaten['pgdbhost']!='') {
			$sql.=', pgdbhost= "'.$stellendaten['pgdbhost'].'"';
		}
		$sql.=', pgdbname= "'.$stellendaten['pgdbname'].'"';
		$sql.=', pgdbuser= "'.$stellendaten['pgdbuser'].'"';
		$sql.=', pgdbpasswd= "'.$stellendaten['pgdbpasswd'].'"';
		$sql.=', ows_title= "'.$stellendaten['ows_title'].'"';
		$sql.=', ows_abstract= "'.$stellendaten['ows_abstract'].'"';
		$sql.=', wms_accessconstraints= "'.$stellendaten['wms_accessconstraints'].'"';
		$sql.=', ows_contactperson= "'.$stellendaten['ows_contactperson'].'"';
		$sql.=', ows_contactorganization= "'.$stellendaten['ows_contactorganization'].'"';
		$sql.=', ows_contactemailaddress= "'.$stellendaten['ows_contactemailaddress'].'"';
		$sql.=', ows_contactposition= "'.$stellendaten['ows_contactposition'].'"';
		$sql.=', ows_fees= "'.$stellendaten['ows_fees'].'"';
		$sql.=', ows_srs= "'.$stellendaten['ows_srs'].'"';
		$sql.=', wappen_link= "'.$stellendaten['wappen_link'].'"';
		if($stellendaten['wappen']){
			$sql.=', wappen="'.$_files['wappen']['name'].'"';
		}
		elseif($stellendaten['wappen_save']){
			$sql.=', wappen="'.$stellendaten['wappen_save'].'"';
		}
		$sql.=', check_client_ip="';if($stellendaten['checkClientIP']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', check_password_age="';if($stellendaten['checkPasswordAge']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', allowed_password_age=';if($stellendaten['allowedPasswordAge']!='')$sql.=$stellendaten['allowedPasswordAge'];else $sql.='6';
		$sql.=', use_layer_aliases="';if($stellendaten['use_layer_aliases']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', hist_timestamp="';if($stellendaten['hist_timestamp']=='1')$sql.='1';else $sql.='0';$sql.='"';
		# Abfrage starten
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1].='<br>Die Stellendaten konnten nicht eingetragen werden.<br>'.$ret[1];
		}
		else {
			# Stelle Erfolgreich angelegt
			# Abfragen der stelle_id des neu eingetragenen Benutzers
			$sql ='SELECT ID FROM stelle WHERE';
			$sql.=' Bezeichnung="'.$stellendaten['bezeichnung'].'"';
			# Starten der Anfrage
			$ret=$this->database->execSQL($sql,4, 0);
			#echo $sql;
			if ($ret[0]) {
				# Fehler bei der Datenbankanfrage
				$ret[1].='<br>Die Stellendaten konnten nicht eingetragen werden.<br>'.$ret[1];
			}
			else {
				# Abfrage erfolgreich durchgeführt, übergeben der stelle_id zur Rückgabe der Funktion
				$rs=mysql_fetch_array($ret[1]);
				$ret[1]=$rs['ID'];
			}
		}
		return $ret;
	}

	function Aendern($stellendaten) {
		# Stelle ändern
		$sql ='UPDATE stelle SET';
		if($stellendaten['id'] != ''){
			$sql.=' ID='.$stellendaten['id'].', ';
		}
		$sql.=' Bezeichnung="'.$stellendaten['bezeichnung'].'"';
		$sql.=', Referenzkarte_ID='.$stellendaten['Referenzkarte_ID'];
		$sql.=', alb_raumbezug="'.$stellendaten['alb_raumbezug'].'"';
		$sql.=', alb_raumbezug_wert="'.$stellendaten['alb_raumbezug_wert'].'"';
		$sql.=', minxmax= "'.$stellendaten['minxmax'].'"';
		$sql.=', minymax= "'.$stellendaten['minymax'].'"';
		$sql.=', maxxmax= "'.$stellendaten['maxxmax'].'"';
		$sql.=', maxymax= "'.$stellendaten['maxymax'].'"';
		$sql.=', epsg_code= "'.$stellendaten['epsg_code'].'"';
		$sql.=', start= "'.$stellendaten['start'].'"';
		$sql.=', stop= "'.$stellendaten['stop'].'"';
		if ($stellendaten['pgdbhost']!='') {
			$sql.=', pgdbhost= "'.$stellendaten['pgdbhost'].'"';
		}
		$sql.=', pgdbname= "'.$stellendaten['pgdbname'].'"';
		$sql.=', pgdbuser= "'.$stellendaten['pgdbuser'].'"';
		$sql.=', pgdbpasswd= "'.$stellendaten['pgdbpasswd'].'"';
		$sql.=', ows_title= "'.$stellendaten['ows_title'].'"';
		$sql.=', ows_abstract= "'.$stellendaten['ows_abstract'].'"';
		$sql.=', wms_accessconstraints= "'.$stellendaten['wms_accessconstraints'].'"';
		$sql.=', ows_contactperson= "'.$stellendaten['ows_contactperson'].'"';
		$sql.=', ows_contactorganization= "'.$stellendaten['ows_contactorganization'].'"';
		$sql.=', ows_contactemailaddress= "'.$stellendaten['ows_contactemailaddress'].'"';
		$sql.=', ows_contactposition= "'.$stellendaten['ows_contactposition'].'"';
		$sql.=', ows_fees= "'.$stellendaten['ows_fees'].'"';
		$sql.=', ows_srs= "'.$stellendaten['ows_srs'].'"';
		$sql.=', wappen_link= "'.$stellendaten['wappen_link'].'"';
		if($stellendaten['wappen']){
			$sql.=', wappen="'.$stellendaten['wappen'].'"';
		}
		$sql.=', check_client_ip="';if($stellendaten['checkClientIP']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', check_password_age="';if($stellendaten['checkPasswordAge']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', allowed_password_age=';if ($stellendaten['allowedPasswordAge']!='')$sql.=$stellendaten['allowedPasswordAge'];else $sql.='6';
		$sql.=', use_layer_aliases="';if ($stellendaten['use_layer_aliases']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', hist_timestamp="';if($stellendaten['hist_timestamp']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=' WHERE ID = '.$this->id;
		#echo $sql;
		# Abfrage starten
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1].='<br>Die Stellendaten konnten nicht eingetragen werden.<br>'.$ret[1];
		}
		return $ret[1];
	}

	function getStellen($order) {
		$sql ='SELECT s.ID,s.Bezeichnung FROM stelle AS s';
		if ($order!='') {
			$sql.=' ORDER BY '.$order;
		}
		$this->debug->write("<p>file:users.php class:stelle->getStellen - Abfragen aller Stellen<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs=mysql_fetch_array($query)) {
			$stellen['ID'][]=$rs['ID'];
			$stellen['Bezeichnung'][]=$rs['Bezeichnung'];
		}
		return $stellen;
	}

	function getFunktionen() {
		# Abfragen der Funktionen, die in der Stelle ausgeführt werden dürfen
		$sql ='SELECT f.id,f.bezeichnung, 1 as erlaubt FROM u_funktionen AS f,u_funktion2stelle AS f2s';
		$sql.=' WHERE f.id=f2s.funktion_id AND f2s.stelle_id='.$this->id.' ORDER BY bezeichnung';
		$this->debug->write("<p>file:users.php class:stelle->getFunktionen - Fragt die Funktionen der Stelle ab:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Abfrage der Funktionen für die Stelle';
		}
		else {
			while($rs=mysql_fetch_array($query)) {
				$funktionen[$rs['bezeichnung']]=$rs;
				$funktionen['array'][]=$rs;
			}
		}
		$this->funktionen=$funktionen;
		return $errmsg;
	}

	function isFunctionAllowed($functionname) {
		$this->getFunktionen();
		if($this->funktionen[$functionname]['erlaubt']) {
			return 1;
		}
		else {
			return 0;
		}
	}

	function isMenueAllowed($menuename){
		$sql = "SELECT distinct a.* from u_menues as a, u_menue2stelle as b ";
		$sql.= "WHERE links LIKE 'index.php?go=".$menuename."%' AND b.menue_id = a.id AND b.stelle_id = ".$this->id;
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->isMenueAllowed - Guckt ob der Menuepunkt der Stelle zugeordnet ist:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Ueberpruefung des Menuepunkts für die Stelle';
		}
		else{
			$rs=mysql_fetch_array($query);
		}
		if($rs[0] != '') {
			return 1;
		}
		else {
			return 0;
		}
	}

	function getFlurstueckeAllowed($FlurstKennz, $database) {
		include_(CLASSPATH.'alb.php');
		$GemeindenStelle = $this->getGemeindeIDs();
		if($GemeindenStelle != NULL){
			$alb = new ALB($database);
			$ret=$alb->getFlurstKennzByGemeindeIDs($GemeindenStelle, $FlurstKennz);
			if ($ret[0]==0) {
				$anzFlurstKennz=count($ret[1]);
				if ($anzFlurstKennz==0) {
					$ret[0]=1;
					$ret[1]="Sie haben keine Berechtigung zur Ansicht diese(s)r Flurstücke(s)";
				}
			}
			# ret[0] = 1 wenn Fehler in Datenbankabfrage oder keine FlurstKennz zurück
			# ret[1] = Fehlermeldung oder Liste FlurstKennz
		}
		else{
			$ret[1] = $FlurstKennz;
		}
		return $ret;
	}

	function addMenue($menue_ids) {
		# Hinzufügen von Menuepunkten zur Stelle
		$sql ='SELECT MAX(menue_order) FROM u_menue2stelle WHERE stelle_id = '.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->addMenue - Lesen der maximalen menue_order der Menuepunkte der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			$rs=mysql_fetch_array($query);
		}
		$count = $rs[0];
		for ($i=0;$i<count($menue_ids);$i++) {
			$sql ="INSERT IGNORE INTO u_menue2stelle ( `stelle_id` , `menue_id` , `menue_order` ) VALUES ('".$this->id."', '".$menue_ids[$i]."', '".$count."')";
			$count++;
			$this->debug->write("<p>file:users.php class:stelle->addMenue - Hinzufügen von Menuepunkten zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }

			/* $sql ='SELECT id FROM u_menues WHERE obermenue = '.$menue_ids[$i];
			 $this->debug->write("<p>file:users.php class:stelle->addMenue - Lesen der Untermenuepunkte zu den Obermenuepunken zur Stelle:<br>".$sql,4);
			 $query=mysql_query($sql,$this->database->dbConn);
			 if ($query==0) {
			 $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
			 }
			 else{
			 while($rs=mysql_fetch_array($query)) {
			 $sql ="INSERT IGNORE INTO u_menue2stelle ( `stelle_id` , `menue_id` , `menue_order` ) VALUES ('".$this->id."', '".$rs[0]."', '".$count."')";
			 $count++;
			 $this->debug->write("<p>file:users.php class:stelle->addMenue - Hinzufügen von Menuepunkten zur Stelle:<br>".$sql,4);
			 $query1=mysql_query($sql,$this->database->dbConn);
			 if ($query1==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			 }
			 }
			 */
		}
		return 1;
	}

	function getMenue($ebene) {
		# Lesen der Menuepunkte zur Stelle
		$sql ='SELECT menue_id,';
		if ($this->language != 'german') {
			$sql.='`name_'.$this->language.'` AS ';
		}
		$sql.=' name, menueebene, `order` FROM u_menue2stelle, u_menues';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND menue_id = u_menues.id';
		if($ebene != 0){
			$sql .=' AND menueebene = '.$ebene;
		}
		$sql .= ' ORDER BY menue_order';
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getMenue - Lesen der Menuepunkte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$menue['ID'][]=$rs['menue_id'];
				$menue['ORDER'][]=$rs['order'];
				$menue['menueebene'][]=$rs['menueebene'];
				if($rs['menueebene'] == 2){
					$menue['Bezeichnung'][]='&nbsp;&nbsp;-->&nbsp;'.$rs['name'];
				}
				else{
					$menue['Bezeichnung'][]=$rs['name'];
				}
			}
		}
		return $menue;
	}

	function copyLayerfromStelle($layer_ids, $alte_stelle_id){
		# kopieren der Layer von einer Stelle
		for ($i=0;$i<count($layer_ids);$i++) {
			$sql ='INSERT IGNORE INTO used_layer ( `Stelle_ID` , `Layer_ID` , `queryable` , `drawingorder` , `minscale` , `maxscale` , `offsite` , `transparency`, `Filter` , `template` , `header` , `footer` , `symbolscale`, `logconsume`, `requires`, `privileg` )';
			$sql .= ' SELECT '.$this->id.', `Layer_ID` , `queryable` , `drawingorder` , `minscale` , `maxscale` , `offsite` , `transparency`, `Filter` , `template` , `header` , `footer` , `symbolscale`, `logconsume`, `requires`, `privileg` FROM used_layer WHERE Stelle_ID = '.$alte_stelle_id.' AND Layer_ID = '.$layer_ids[$i];
			$this->debug->write("<p>file:users.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# Layerattributrechte mitkopieren
			$sql ='INSERT IGNORE INTO layer_attributes2stelle (layer_id, attributename, stelle_id, privileg, tooltip) ';
			$sql.='SELECT layer_id, attributename, '.$this->id.', privileg, tooltip FROM layer_attributes2stelle WHERE stelle_id = '.$alte_stelle_id.' AND layer_id = '.$layer_ids[$i];
			$this->debug->write("<p>file:users.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function addFunctions($function_ids){
		# Hinzufügen von Funktionen zur Stelle
		for ($i=0;$i<count($function_ids);$i++) {
			$sql ='INSERT IGNORE INTO u_funktion2stelle ( `funktion_id` , `stelle_id`)';
			$sql.="VALUES ('".$function_ids[$i]."', '".$this->id."')";
			$this->debug->write("<p>file:users.php class:stelle->addFunctions - Hinzufügen von Funktionen zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function removeFunctions(){
		# Entfernen von Funktionen zur Stelle
		$sql ='DELETE FROM u_funktion2stelle ';
		$sql.='WHERE stelle_id = '.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->removeFunctions - Entfernen von Funktionen zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function addLayer($layer_ids, $drawingorder, $filter = '') {
		# Hinzufügen von Layern zur Stelle
		for ($i=0;$i<count($layer_ids);$i++) {
			$sql = "
				SELECT
					queryable, template, transparency, drawingorder, minscale, maxscale, symbolscale, offsite, requires, privileg, postlabelcache
				FROM
					layer
				WHERE
					Layer_ID = " . $layer_ids[$i];
			#echo '<br>sql: ' . $sql;
			$this->debug->write("<p>file:users.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			$rs = mysql_fetch_array($query);
			$queryable = $rs['queryable'];
			$template = $rs['template'];
			if($rs['transparency'] == ''){
				$rs['transparency'] = 'NULL';
			}
			$transparency = $rs['transparency'];
			$drawingorder = $rs['drawingorder'];
			$minscale = $rs['minscale'];
			$maxscale = $rs['maxscale'];
			$symbolscale = $rs['symbolscale'];
			$offsite = $rs['offsite'];
			$privileg = $rs['privileg'];
			$postlabelcache = $rs['postlabelcache'];
			if($rs['requires'] == '')$rs['requires']='NULL';
			$requires = $rs['requires'];
			$sql = "
				INSERT IGNORE INTO used_layer (
					`Stelle_ID`,
					`Layer_ID`,
					`queryable`,
					`drawingorder`,
					`minscale`,
					`maxscale`,
					`symbolscale`,
					`offsite`,
					`transparency`,
					`Filter`,
					`template`,
					`header`,
					`footer`,
					`privileg`,
					`postlabelcache`,
					`requires`
				)
				VALUES (
					'" . $this->id . "',
					'" . $layer_ids[$i] . "',
					'" . $queryable . "',
					'" . $drawingorder . "',
					'" . $minscale . "',
					'" . $maxscale . "',
					'" . $symbolscale . "',
					'" . $offsite . "',
					" . $transparency . ",
					'" . $filter . "',
					'" . $template . "',
					NULL,
					NULL,
					'" . $privileg . "',
					'" . $postlabelcache . "',
					" . $requires . "
				)
			";
			#echo '<br>' . $sql;
			$this->debug->write("<p>file:users.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			
			if(mysql_affected_rows() > 0){
				$sql = "INSERT IGNORE INTO layer_attributes2stelle (layer_id, attributename, stelle_id, privileg, tooltip) ";
				$sql.= "SELECT ".$layer_ids[$i].", name, ".$this->id.", privileg, query_tooltip FROM layer_attributes WHERE layer_id = ".$layer_ids[$i]." AND privileg IS NOT NULL";
				#echo $sql.'<br>';
				$this->debug->write("<p>file:users.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}

	function updateLayer($formvars){
		# Aktualisieren der LayerzuStelle-Eigenschaften
		$sql = 'UPDATE used_layer SET Layer_ID = '.$formvars['selected_layer_id'];
		$sql .= ', queryable = "'.$formvars['queryable'].'"';
		$sql .= ', use_geom = '.$formvars['use_geom'];
		if ($formvars['minscale']!='') {
			$sql .= ', minscale = '.$formvars['minscale'];
		}
		else{
			$sql .= ', minscale = NULL';
		}
		if ($formvars['maxscale']!='') {
			$sql .= ', maxscale = '.$formvars['maxscale'];
		}
		else{
			$sql .= ', maxscale = NULL';
		}
		$sql .= ', offsite = "'.$formvars['offsite'].'"';
		if ($formvars['transparency']!='') {
			$sql .= ', transparency = '.$formvars['transparency'];
		}
		else{
			$sql .= ', transparency = NULL';
		}
		$sql .= ', postlabelcache = "'.$formvars['postlabelcache'].'"';
		$sql .= ", Filter = '".$formvars['Filter']."'";
		$sql .= ', template = "'.$formvars['template'].'"';
		$sql .= ', header = "'.$formvars['header'].'"';
		$sql .= ', footer = "'.$formvars['footer'].'"';
		if ($formvars['symbolscale']!='') {
			$sql .= ', symbolscale = '.$formvars['symbolscale'];
		}
		else{
			$sql .= ', symbolscale = NULL';
		}
		if($formvars['requires'] == '')$formvars['requires'] = 'NULL';
		$sql .= ', requires = '.$formvars['requires'];
		$sql .= ', start_aktiv = "'.$formvars['startaktiv'].'"';
		$sql .= ', logconsume = "'.$formvars['logconsume'].'"';
		$sql .= ' WHERE Stelle_ID = '.$formvars['selected_stelle_id'].' AND Layer_ID = '.$formvars['selected_layer_id'];
		#echo $sql.'<br>';
		$this->debug->write("<p>file:users.php class:stelle->updateLayer - Aktualisieren der LayerzuStelle-Eigenschaften:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function updateLayerdrawingorder($formvars){
		# Aktualisieren der LayerzuStelle-Eigenschaften
		$sql = 'UPDATE used_layer SET Layer_ID = '.$formvars['selected_layer_id'];
		$sql .= ', drawingorder = '.$formvars['drawingorder'];
		$sql .= ' WHERE Stelle_ID = '.$formvars['selected_stelle_id'].' AND Layer_ID = '.$formvars['selected_layer_id'];
		#echo $sql.'<br>';
		$this->debug->write("<p>file:users.php class:stelle->updateLayerdrawingorder - Aktualisieren der LayerzuStelle-Eigenschaften:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function getLayers($group, $order = NULL) {
		# Lesen der Layer zur Stelle
		$sql ='SELECT layer.Layer_ID, layer.Gruppe, Name, used_layer.drawingorder FROM used_layer, layer, u_groups';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND layer.Gruppe = u_groups.id';
		$sql .=' AND layer.Layer_ID = used_layer.Layer_ID';
		if($group != NULL){
			$sql .= ' AND layer.Gruppe = '.$group;
		}
		if($order != NULL){
			$sql .= ' ORDER BY '.$order;
		}
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getLayers - Lesen der Layer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
				$layer['drawingorder'][]=$rs['drawingorder'];
				$layer['Gruppe'][]=$rs['Gruppe'];
			}
			if($order == 'Name'){
				// Sortieren der Layer unter Berücksichtigung von Umlauten
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
				$sorted_layer['Bezeichnung'] = $sorted_arrays['array'];
				$sorted_layer['ID'] = $sorted_arrays['second_array'];
				
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['drawingorder']);
				$sorted_layer['drawingorder'] = $sorted_arrays['second_array'];
				
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['Gruppe']);
				$sorted_layer['Gruppe'] = $sorted_arrays['second_array'];
				$layer = $sorted_layer;
			}
		}
		return $layer;
	}

	function getqueryablePostgisLayers($privileg, $export_privileg = NULL, $no_subform_layers = false){
		$sql = 'SELECT distinct Layer_ID, Name, alias, export_privileg FROM (';
		$sql .='SELECT layer.Layer_ID, layer.Name, layer.alias, used_layer.export_privileg, form_element_type as subformfk, las.privileg as privilegfk ';
		$sql .='FROM u_groups, layer, used_layer ';
		$sql .='LEFT JOIN layer_attributes as la ON la.layer_id = used_layer.Layer_ID AND form_element_type = \'SubformFK\' ';
		$sql .='LEFT JOIN layer_attributes2stelle as las ON las.stelle_id = used_layer.Stelle_ID AND  used_layer.Layer_ID = las.layer_id AND las.attributename = SUBSTRING_INDEX(SUBSTRING_INDEX(la.options, \';\', 1) , \',\',  -1) ';		
		$sql .=' WHERE used_layer.stelle_id = '.$this->id;
		$sql .=' AND layer.Gruppe = u_groups.id AND layer.connectiontype = 6';
		$sql .=' AND layer.Layer_ID = used_layer.Layer_ID';
		$sql .=' AND used_layer.queryable = \'1\'';
		if($privileg != NULL){
			$sql .=' AND used_layer.privileg >= "'.$privileg.'"';
		}
		if($export_privileg != NULL){
			$sql .=' AND used_layer.export_privileg > 0';
		}
		$sql .= ' ORDER BY Name) as foo ';
		if($privileg > 0 AND $no_subform_layers){
			$sql .= 'WHERE subformfk IS NULL OR privilegfk = 1';			# nicht editierbare SubformFKs ausschliessen
		}
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getqueryablePostgisLayers - Lesen der abfragbaren PostgisLayer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				if($rs['alias'] != '' AND $this->useLayerAliases){
					$rs['Name'] = $rs['alias'];
				}
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
				$layer['export_privileg'][]=$rs['export_privileg'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
			$sorted_arrays2 = umlaute_sortieren($layer['Bezeichnung'], $layer['export_privileg']);
			$layer['Bezeichnung'] = $sorted_arrays['array'];
			$layer['ID'] = $sorted_arrays['second_array'];
			$layer['export_privileg'] = $sorted_arrays2['second_array'];
		}
		return $layer;
	}

	function getqueryableVectorLayers($privileg, $user_id, $group_id = NULL, $layer_ids = NULL, $rollenlayer_type = NULL, $use_geom = NULL){
		global $language;
		$sql = 'SELECT layer.Layer_ID, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Name_'.$language.'` != "" THEN `Name_'.$language.'` ELSE `Name` END AS ';
		}
		$sql .='Name, alias, Gruppe, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Gruppenname_'.$language.'` != "" THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS ';
		}
		$sql .='Gruppenname, `connection` FROM used_layer, layer, u_groups';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND layer.Gruppe = u_groups.id AND (layer.connectiontype = 6 OR layer.connectiontype = 9)';
		$sql .=' AND layer.Layer_ID = used_layer.Layer_ID';
		if($use_geom != NULL){
			$sql .=' AND used_layer.use_geom = 1';
		}
		else{
			$sql .=' AND used_layer.queryable = \'1\'';
		}
		if($privileg != NULL){
			$sql .=' AND used_layer.privileg >= "'.$privileg.'"';
		}		
		if($group_id != NULL){
			$sql .=' AND u_groups.id = '.$group_id;
		}
		if($layer_ids != NULL){
			$sql .=' AND layer.Layer_ID IN ('.implode($layer_ids, ',').')';
		}
		if($user_id != NULL){
			$sql .= ' UNION ';
			$sql .= 'SELECT -id as Layer_ID, concat(substring( `Name` FROM 1 FOR locate( ")", `Name` )), CASE WHEN Typ = "search" THEN " -Suchergebnis-" ELSE " -Shape-Import-" END), "", Gruppe, " ", `connection` FROM rollenlayer';
			$sql .= ' WHERE stelle_id = '.$this->id.' AND user_id = '.$user_id.' AND connectiontype = 6';			
			if($rollenlayer_type != NULL){
				$sql .=' AND Typ = "'.$rollenlayer_type.'"';
			}
			if($group_id != NULL){
				$sql .=' AND Gruppe = '.$group_id;
			}
		}
		$sql .= ' ORDER BY Name';
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getqueryableVectorLayers - Lesen der abfragbaren VektorLayer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);		
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_assoc($query)){
				 
				# fremde Layer werden auf Verbindung getestet (erstmal rausgenommen, dauert relativ lange)
				// if(strpos($rs['connection'], 'host') !== false AND strpos($rs['connection'], 'host=localhost') === false){
					// $connection = explode(' ', trim($rs['connection']));
					// for($j = 0; $j < count($connection); $j++){
						// if($connection[$j] != ''){
							// $value = explode('=', $connection[$j]);
							// if(strtolower($value[0]) == 'host'){
								// $host = $value[1];
							// }
							// if(strtolower($value[0]) == 'port'){
								// $port = $value[1];
							// }
						// }
					// }
					// if($port == '')$port = '5432';
					// $fp = @fsockopen($host, $port, $errno, $errstr, 0.1);
					// if(!$fp){			# keine Verbindung --> Layer ausschalten
						// #$this->Fehlermeldung = $errstr.' für Layer: '.$rs['Name'].'<br>';
						// continue;
					// }
				// }
				
				if($rs['alias'] != '' AND $this->useLayerAliases){
					$rs['Name'] = $rs['alias'];
				}
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
				$layer['Gruppe'][]=$rs['Gruppe'];
				$layer['Gruppenname'][]=$rs['Gruppenname'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
			$layer['Bezeichnung'] = $sorted_arrays['array'];
			$layer['ID'] = $sorted_arrays['second_array'];
		}
		return $layer;
	}

	function addAktivLayer($layerid) {
		# Hinzufügen der Layer als aktive Layer
		for ($i=0;$i<count($layerid);$i++) {
			$sql ='UPDATE used_layer SET aktivStatus="1"';
			$sql.=' WHERE Stelle_ID='.$this->id.' AND Layer_ID='.$layerid[$i];
			$this->debug->write("<p>file:users.php class:stelle->addAktivLayer - Hinzufügen von aktiven Layern zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function setAktivLayer($formvars) {
		# Eintragen des Status der Layer, 1 angezeigt oder 0 nicht.
		$layerset=$this->getLayer('');
		for ($i=0;$i<count($layerset);$i++) {
			if ($formvars['thema'.$layerset[$i]['Layer_ID']]==1) {
				$aktiv_status=1;
			}
			else {
				$aktiv_status=0;
			}
			$sql ='UPDATE used_layer SET aktivStatus="'.$aktiv_status.'"';
			$sql.=' WHERE Stelle_ID='.$this->id.' AND Layer_ID='.$layerset[$i]['Layer_ID'];
			$this->debug->write("<p>file:users.php class:stelle->setAktivLayer - Speichern der aktiven Layer zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function setQueryStatus($formvars) {
		# Eintragen des query_status=1 für Layer, die für die Abfrage selektiert wurden
		$layerset=$this->getLayer('');
		for ($i=0;$i<count($layerset);$i++) {
			if ($formvars['qLayer'.$layerset[$i]['Layer_ID']]) {
				$query_status=1;
			}
			else {
				$query_status=0;
			}
			$sql ='UPDATE used_layer set queryStatus="'.$query_status.'"';
			$sql.=' WHERE Layer_ID='.$layerset[$i]['Layer_ID'];
			$this->debug->write("<p>file:users.php class:stelle->setQueryStatus - Speichern des Abfragestatus der Layer zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function getLayer($Layer_id) {
		# Abfragen der Layer der Stelle
		$sql ='SELECT l.*, ul.* FROM layer AS l, used_layer AS ul';
		$sql.=' WHERE l.Layer_ID=ul.Layer_ID AND Stelle_ID='.$this->id;
		if ($Layer_id!='') {
			$sql.=' AND l.Layer_ID = "'.$Layer_id.'"';
		}
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getLayer - Abfragen der Layer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_array($query)) {
			$layer[]=$rs;
		}
		return $layer;
	}

	function get_attributes_privileges($layer_id){
		$sql = 'SELECT attributename, privileg, tooltip FROM layer_attributes2stelle WHERE stelle_id = '.$this->id.' AND layer_id = '.$layer_id;
		$this->debug->write("<p>file:users.php class:stelle->get_attributes_privileges - Abfragen der Layerrechte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_array($query)) {
			$privileges[$rs['attributename']] = $rs['privileg'];
			$privileges['tooltip_'.$rs['attributename']] = $rs['tooltip'];
			$privileges['attributenames'][] = $rs['attributename'];
		}
		return $privileges;
	}

	function parse_path($database, $path, $privileges, $attributes = NULL){
		$distinctpos = strpos(strtolower($path), 'distinct');
		if($distinctpos !== false && $distinctpos < 10){
			$offset = $distinctpos+8;
		}
		else{
			$offset = 7;
		}
		$offstring = substr($path, 0, $offset);
		$path = $database->eliminate_star($path, $offset);
		if(substr_count(strtolower($path), ' from ') > 1){
			$whereposition = strpos($path, ' WHERE ');
			$withoutwhere = substr($path, 0, $whereposition);
			$fromposition = strpos($withoutwhere, ' FROM ');
		}
		else{
			$whereposition = strpos(strtolower($path), ' where ');
			$withoutwhere = substr($path, 0, $whereposition);
			$fromposition = strpos(strtolower($withoutwhere), ' from ');
		}
		if($privileges == NULL){  # alle Attribute sind abfragbar
			$newpath = $path;
		}
		else{
			$where = substr($path, $whereposition);
			$from = substr($withoutwhere, $fromposition);

			$attributesstring = substr($path, $offset, $fromposition-$offset);
			//$fieldstring = explode(',', $attributesstring);
			$fieldstring = get_select_parts($attributesstring);
			$count = count($fieldstring);
			for($i = 0; $i < $count; $i++){
				if(strpos(strtolower($fieldstring[$i]), ' as ')){   # Ausdruck AS attributname
					$explosion = explode(' as ', strtolower($fieldstring[$i]));
					$attributename = array_pop($explosion);
					$real_attributename = $explosion[0];
				}
				else{   # tabellenname.attributname oder attributname
					$explosion = explode('.', strtolower($fieldstring[$i]));
					$attributename = trim($explosion[count($explosion)-1]);
					$real_attributename = $fieldstring[$i];
				}
				if($privileges[$attributename] != ''){
					$type = $attributes['type'][$attributes['indizes'][$attributename]];
					if(POSTGRESVERSION >= 930 AND substr($type, 0, 1) == '_' OR is_numeric($type))$newattributesstring .= 'to_json('.$real_attributename.') as '.$attributename.', ';		# Array oder Datentyp
					else $newattributesstring .= $fieldstring[$i].', ';																																			# normal
				}
				if(substr_count($fieldstring[$i], '(') - substr_count($fieldstring[$i], ')') > 0){
					$fieldstring[$i+1] = $fieldstring[$i].','.$fieldstring[$i+1];
				}
			}
			$newattributesstring = substr($newattributesstring, 0, strlen($newattributesstring)-2);
			$newpath = $offstring.' '.$newattributesstring.' '.$from.$where;
		}
		return $newpath;
	}

	function set_layer_privileges($layer_id, $privileg, $exportprivileg){
		$sql = 'UPDATE used_layer SET privileg = "'.$privileg.'", export_privileg = "'.$exportprivileg.'" WHERE ';
		$sql.= 'layer_id = '.$layer_id.' AND stelle_id = '.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->set_layer_privileges - Speichern der Layerrechte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function set_attributes_privileges($formvars, $attributes){
		# erst alles löschen zu diesem Layer und Stelle
		$sql = 'DELETE FROM layer_attributes2stelle WHERE ';
		$sql.= 'layer_id = '.$formvars['selected_layer_id'].' AND ';
		$sql.= 'stelle_id = '.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		# dann Attributrechte eintragen
		for($i = 0; $i < count($attributes['type']); $i++){
			if($formvars['privileg_'.$attributes['name'][$i].$this->id] !== ''){
				$sql = 'INSERT INTO layer_attributes2stelle SET ';
				$sql.= 'layer_id = '.$formvars['selected_layer_id'].', ';
				$sql.= 'stelle_id = '.$this->id.', ';
				$sql.= 'attributename = "'.$attributes['name'][$i].'", ';
				$sql.= 'privileg = '.$formvars['privileg_'.$attributes['name'][$i].$this->id];
				if($formvars['tooltip_'.$attributes['name'][$i].$this->id] == 'on'){
					$sql.= ', tooltip = 1';
				}
				else{
					$sql.= ', tooltip = 0';
				}
				$this->debug->write("<p>file:users.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
	}

	function getGemeindeIDs() {
		$sql = 'SELECT Gemeinde_ID, Gemarkung, Flur FROM stelle_gemeinden WHERE Stelle_ID = '.$this->id;
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getGemeindeIDs - Lesen der GemeindeIDs zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if(mysql_num_rows($query) > 0){
			$liste['ganze_gemeinde'] = Array();
			$liste['eingeschr_gemeinde'] = Array();
			$liste['ganze_gemarkung'] = Array();
			$liste['eingeschr_gemarkung'] = Array();
			while($rs=mysql_fetch_assoc($query)) {
				if($rs['Gemarkung'] != ''){
					$liste['eingeschr_gemeinde'][$rs['Gemeinde_ID']] = NULL;
					if($rs['Flur'] != '')$liste['eingeschr_gemarkung'][$rs['Gemarkung']][] = $rs['Flur'];
					else $liste['ganze_gemarkung'][$rs['Gemarkung']] = NULL;
				}
				else{
					$liste['ganze_gemeinde'][$rs['Gemeinde_ID']] = NULL;
				}
			}
		}
		return $liste;		
	}

	function getGemeinden($database) {
		if($database->type == 'mysql'){
			$ret=$this->database->getGemeindebyID_Name($this->id);
			if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
			if (mysql_num_rows($ret[1])==0) {
				$GemeindeListe['ID'][0]=0;
			}
			else{
				while ($rs=mysql_fetch_array($ret[1])) {
					$GemeindeListe['ID'][]=$rs['ID'];
					$GemeindeListe['Name'][]=$rs['Name'];
				}
			}
		}
		elseif($database->type == 'postgresql'){
			$liste = $this->getGemeindeIDs();
			for($i = 0; $i < count($liste); $i++){
				$ret = $database->getGemeindeName($liste[$i]);
				$GemeindeListe['ID'][]=$liste[$i];
				$GemeindeListe['Name'][]=$ret[1]['name'];
			}
		}
		return $GemeindeListe;
	}

	function getUser() {
		# Lesen der User zur Stelle
		$sql ='SELECT user.* FROM user, rolle';
		$sql .=' WHERE rolle.stelle_id = '.$this->id;
		$sql .=' AND rolle.user_id = user.ID';
		$sql .= ' ORDER BY Name';
		$this->debug->write("<p>file:users.php class:stelle->getUser - Lesen der User zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$user['ID'][]=$rs['ID'];
				$user['Bezeichnung'][]=$rs['Name'].', '.$rs['Vorname'];
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

	function getWappen() {
		$sql ='SELECT wappen FROM stelle WHERE ID='.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_array($query);
		return $rs['wappen'];
	}
	
	function getWappenLink() {
		$sql ='SELECT wappen_link FROM stelle WHERE ID='.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_array($query);
		return $rs['wappen_link'];
	} 
}
?>
