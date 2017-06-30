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

	function readUserDaten($id, $login_name) {
		$where = array();
		if ($id > 0) array_push($where, "ID = " . $id);
		if ($login_name != '') array_push($where, "login_name LIKE '" . $login_name . "'");
		$sql = "
			SELECT
				*
			FROM
				user
			WHERE " .
				implode(" AND ", $where) . "
		";
		#echo '<br>Sql: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>" . $sql, 4);
		$query = mysql_query($sql,$this->database->dbConn);
		if ($query == 0) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__, 4); return 0; }
		$rs = mysql_fetch_array($query);
		$this->id = $rs['ID'];
		$this->login_name = $rs['login_name'];
		$this->Namenszusatz = $rs['Namenszusatz'];
		$this->Name = $rs['Name'];
		$this->Vorname = $rs['Vorname'];
		$this->stelle_id = $rs['stelle_id'];
		$this->phon = $rs['phon'];
		$this->email = $rs['email'];
		if (CHECK_CLIENT_IP) {
			$this->ips = $rs['ips'];
		}
		$this->funktion = $rs['Funktion'];
		$this->password_setting_time = $rs['password_setting_time'];
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
		$rolle = new rolle($this->id, $stelle_id, $this->database);		
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
		$sql = "
			SELECT
				s.ID,
				s.Bezeichnung
			FROM
				stelle AS s,
				rolle AS r
			WHERE
				s.ID = r.stelle_id AND
				r.user_id = " . $this->id .
				($stelle_ID > 0 ? " AND s.ID = " . $stelle_ID : "") . "
				AND (
					('" . date('Y-m-d h:i:s') . "' >= s.start AND '" . date('Y-m-d h:i:s') . "' <= s.stop)
					OR
					(s.start = '0000-00-00 00:00:00' AND s.stop = '0000-00-00 00:00:00')
				)
			ORDER BY
				Bezeichnung;
		";

		#echo '<br>sql: ' . $sql;
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

			$sql .= ', runningcoords = "' . ($formvars['runningcoords'] == '' ? '0' : '1') . '"';
			$sql .= ', showmapfunctions = "' . ($formvars['showmapfunctions'] == '' ? '0' : '1') . '"';
			$sql .= ', showlayeroptions = "' . ($formvars['showlayeroptions'] == '' ? '0' : '1') . '"';
			$sql .= ', menue_buttons = "' . ($formvars['menue_buttons'] == '' ? '0' : '1') . '"';

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

?>
