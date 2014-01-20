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
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de                #
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
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de
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

	## functions
	# account ($database)
	# getLayer()
	# getAllAccess($case)
	# getAccessToLayer($nutzung,$zeitraum,$date1,$date2,$case,$era,$date,$year,$id)
	# getStatistik($nutzer,$nutzung,$stelle,$zeitraum,$day_d,$week_w,$month_d,$month_w,$month_m,$year_m,$year_w,$year_d,$day_e1,$day_e2,$month_e1,$month_e2,$year_e1,$year_e2)
	# epoch()


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
			$sql.= ' AND format='.$rs['format'];
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
class user extends user_core{
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

	## functions
	# getFunktion($id)
	# readUserDaten($id,$login_name)
	# setStelle($stelle_id,$nZoomFactor,$mapsize)
	# StelleWechseln($stelle_id)
	# StellenZugriff($stelle_id)
	# user($login_name,$database) - konstruktor

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
		$language_charset=explode('_',$formvars['language_charset']);
		$formvars['language']=$language_charset['0'];
		$formvars['charset']=$language_charset['1'];

		# Eintragen der neuen Einstellungen für die Rolle
		if($formvars['gui'] != '' AND $formvars['mapsize'] != ''){
			$sql ='UPDATE rolle SET nZoomFactor='.$formvars['nZoomFactor'].',nImageWidth='.$nImageWidth;
			$sql.=',nImageHeight='.$nImageHeight.',gui="'.$formvars['gui'].'"';
			$sql.=',epsg_code="'.$formvars['epsg_code'].'"';
			$sql.=',epsg_code2="'.$formvars['epsg_code2'].'"';
			$sql.=',coordtype="'.$formvars['coordtype'].'"';
			$sql.=',minx='.$newExtent['minx'].',miny='.$newExtent['miny'];
			$sql.=',maxx='.$newExtent['maxx'].',maxy='.$newExtent['maxy'];
			$sql.=',language="'.$formvars['language'].'"';
			$sql.=',charset="'.$formvars['charset'].'"';
			if($formvars['fontsize_gle'])$sql.=',fontsize_gle="'.$formvars['fontsize_gle'].'"';
			if($formvars['highlighting'] != ''){
				$sql.=',highlighting="1"';
			}
			else{
				$sql.=',highlighting="0"';
			}
			$sql.=',result_color="'.$formvars['result_color'].'"';
			if($formvars['runningcoords'] != ''){
				$sql.=',runningcoords="1"';
			}
			else{
				$sql.=',runningcoords="0"';
			}
			if($formvars['singlequery'] != ''){
				$sql.=',singlequery="1"';
			}
			else{
				$sql.=',singlequery="0"';
			}
			if($formvars['back']){$buttons .= 'back,';}
			if($formvars['forward']){$buttons .= 'forward,';}
			if($formvars['zoomin']){$buttons .= 'zoomin,';}
			if($formvars['zoomout']){$buttons .= 'zoomout,';}
			if($formvars['zoomall']){$buttons .= 'zoomall,';}
			if($formvars['recentre']){$buttons .= 'recentre,';}
			if($formvars['jumpto']){$buttons .= 'jumpto,';}
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
class rolle extends rolle_core{
	var $user_id;
	var $stelle_id;
	var $debug;
	var $database;
	var $loglevel;

	##functionliste
	# getLayer()
	# getMapComments($consumetime)
	# getSelectedButton()
	# insertMapComment($consumetime,$comment)
	# readSettings()
	# rolle($user_id,$stelle_id) konstruktor
	# setAktivLayer($formvars)
	# setConsumeActivity($time,$activity,$prevtime)
	# setSelectedButton($selectedButton)
	# updateNextConsumeTime($time_id,$prevtime)
	# updatePrevConsumeTime($time_id,$prevtime)

	function rolle($user_id,$stelle_id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->user_id=$user_id;
		$this->stelle_id=$stelle_id;
		$this->database=$database;
		$this->layerset=$this->getLayer('');
		$this->groupset=$this->getGroups('');
		$this->loglevel = 0;
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
	
	function get_last_query(){
		$sql = "SELECT * FROM rolle_last_query WHERE user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
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
		for($i = 0; $i < count($attributes['name']); $i++){
			if($formvars['value_'.$attributes['name'][$i]] != '' OR $formvars['operator_'.$attributes['name'][$i]] == 'IS NULL' OR $formvars['operator_'.$attributes['name'][$i]] == 'IS NOT NULL'){
				$sql = 'INSERT INTO search_attributes2rolle VALUES ("'.$formvars['search_name'].'", '.$this->user_id.', '.$this->stelle_id.', '.$formvars['selected_layer_id'].', "'.$attributes['name'][$i].'", "'.$formvars['operator_'.$attributes['name'][$i]].'", "'.$formvars['value_'.$attributes['name'][$i]].'", "'.$formvars['value2_'.$attributes['name'][$i]].'");';
				$this->debug->write("<p>file:users.php class:rolle->save_search - Speichern einer Suchabfrage:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
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
		$sql = 'SELECT name FROM search_attributes2rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND layer_id='.$layer_id.' ORDER BY name';
		$this->debug->write("<p>file:users.php class:rolle->getsearches - Abfragen der gespeicherten Suchabfragen der Rolle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_assoc($query)) {
			$searches[]=$rs;
		}
		return $searches;
	}

	function getsearch($layer_id, $name){
		$sql = 'SELECT * FROM search_attributes2rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND layer_id='.$layer_id.' AND name = "'.$name.'"';
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

	function resetLayers(){
		$sql ="UPDATE u_rolle2used_layer SET aktivStatus='0'";
		$sql.=" WHERE user_id=".$this->user_id." AND stelle_id=".$this->stelle_id;
		$this->debug->write("<p>file:users.php class:rolle->resetLayers - resetten aller aktiven Layer zur Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		$mapdb = new db_mapObj($this->stelle_id, $this->user_id);
		$rollenlayerset = $mapdb->read_RollenLayer();
		for($i = 0; $i < count($rollenlayerset); $i++){
			if($formvars['thema_rolle'.$rollenlayerset[$i]['id']] == 0){
				$mapdb->deleteRollenLayer($rollenlayerset[$i]['id']);
				# auch die Klassen und styles löschen
				foreach($rollenlayerset[$i]['Class'] as $class){
					$mapdb->delete_Class($class['Class_ID']);
					foreach($class['Style'] as $style){
						$mapdb->delete_Style($style['Style_ID']);
					}
				}
			}
		}
	}

	function resetQuerys(){
		$sql ="UPDATE u_rolle2used_layer SET queryStatus='0'";
		$sql.=" WHERE user_id=".$this->user_id." AND stelle_id=".$this->stelle_id;
		$this->debug->write("<p>file:users.php class:rolle->resetQuerys - resetten aller aktiven Layer zur Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setAktivLayer($formvars, $stelle_id, $user_id) {
		# Eintragen des Status der Layer, 1 angezeigt oder 0 nicht.
		for ($i=0;$i<count($this->layerset);$i++) {
			#echo $i.' '.$this->layerset[$i]['Layer_ID'].' '.$formvars['thema'.$this->layerset[$i]['Layer_ID']].'<br>';
			$aktiv_status = $formvars['thema'.$this->layerset[$i]['Layer_ID']];
			if(isset($aktiv_status)){
				$sql ='UPDATE u_rolle2used_layer SET aktivStatus="'.$aktiv_status.'"';
				$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
				$sql.=' AND layer_id='.$this->layerset[$i]['Layer_ID'];
				$this->debug->write("<p>file:users.php class:rolle->setAktivLayer - Speichern der aktiven Layer zur Rolle:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
				
				#Anne
				#neu eintragen der deaktiven Klassen
				if($aktiv_status!=0){
					$sql = 'SELECT Class_ID FROM classes WHERE Layer_ID='.$this->layerset[$i]['Layer_ID'].';';
					$query = mysql_query($sql);
					while($row = @mysql_fetch_array($query)){
						if($formvars['class'.$row['Class_ID']]=='0'){
							$sql2 = 'REPLACE INTO u_rolle2used_class (user_id, stelle_id, class_id) VALUES ('.$this->user_id.', '.$this->stelle_id.', '.$row['Class_ID'].');';
							$this->database->execSQL($sql2,4, $this->loglevel);
						}
						elseif ($formvars['class'.$row['Class_ID']]=='1'){
							$sql1 = 'DELETE FROM u_rolle2used_class WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND class_id='.$row['Class_ID'].';';
							$this->database->execSQL($sql1,4, $this->loglevel);
						}
					}
				}
			}
		}
		$mapdb = new db_mapObj($stelle_id, $user_id);
		$rollenlayerset = $mapdb->read_RollenLayer();
		for($i = 0; $i < count($rollenlayerset); $i++){
			if($formvars['thema'.$rollenlayerset[$i]['Layer_ID']] == 0){
				$mapdb->deleteRollenLayer($rollenlayerset[$i]['id']);
				# auch die Klassen und styles löschen
				foreach($rollenlayerset[$i]['Class'] as $class){
					$mapdb->delete_Class($class['Class_ID']);
					foreach($class['Style'] as $style){
						$mapdb->delete_Style($style['Style_ID']);
					}
				}
			}
		}
		return 1;
	}

	function setQueryStatus($formvars) {
		# Eintragen des query_status=1 für Layer, die für die Abfrage selektiert wurden
		for ($i=0;$i<count($this->layerset);$i++) {
			if ($formvars['qLayer'.$this->layerset[$i]['Layer_ID']] == 1) {
				$query_status=1;
			}
			elseif ($formvars['qLayer'.$this->layerset[$i]['Layer_ID']] == 2) {
				$query_status=2;
			}
			else{
				$query_status=0;
			}
			$sql ='UPDATE u_rolle2used_layer set queryStatus="'.$query_status.'"';
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$sql.=' AND layer_id='.$this->layerset[$i]['Layer_ID'];
			$this->debug->write("<p>file:users.php class:rolle->setQueryStatus - Speichern des Abfragestatus der Layer zur Rolle:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
		return 1;
	}

	function setClassStatus($formvars) {
		# Eintragen des showclasses=1 für Klassen, die angezeigt werden sollen
		for ($i=0;$i<count($this->layerset);$i++) {
			if ($formvars['classes_'.$this->layerset[$i]['Layer_ID']] != ''){
				if ($formvars['classes_'.$this->layerset[$i]['Layer_ID']] == 1) {
					$showclasses=1;
				}
				else {
					$showclasses=0;
				}
				$sql ='UPDATE u_rolle2used_layer set showclasses = "'.$showclasses.'"';
				$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
				$sql.=' AND layer_id='.$this->layerset[$i]['Layer_ID'];
				$this->debug->write("<p>file:users.php class:rolle->setClassStatus - Speichern des Status der Klassen zur Rolle:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
	}

	function setGroupStatus($formvars) {
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
		/*for ($i=0;$i<count($this->layerset);$i++) {										# hat bewirkt, dass als abfragbar markierte Layer, deren Gruppe zugeklappt ist, nicht abfragbar waren -> ist eigetnlich überflüssig
		 $tempgroup = $this->read_Group($this->layerset[$i]['Gruppe']);
		 if($tempgroup['status'] == 0){
		 # Setzen des layer_status und des query_status= 2 für Layer, deren Gruppe nicht angezeigt wird
		 if($formvars['qLayer'.$this->layerset[$i]['Layer_ID']] != 0){
		 $formvars['qLayer'.$this->layerset[$i]['Layer_ID']] = 2;
		 }
		 if($formvars['thema'.$this->layerset[$i]['Layer_ID']] != 0){
		 $formvars['thema'.$this->layerset[$i]['Layer_ID']] = 2;
		 }
		 }
		 else{
		 if($formvars['qLayer'.$this->layerset[$i]['Layer_ID']] == 2){
		 $formvars['qLayer'.$this->layerset[$i]['Layer_ID']] = 1;
		 }
		 if($formvars['thema'.$this->layerset[$i]['Layer_ID']] == 2){
		 $formvars['thema'.$this->layerset[$i]['Layer_ID']] = 1;
		 }
		 }
		 }*/
		return $formvars;
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
			$sql ='INSERT IGNORE INTO u_rolle2used_layer SELECT '.$user_id.', used_layer.Stelle_ID, used_layer.Layer_ID, "'.$active.'", "0", "1","0"';
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
		$this->debug->write("<p>file:users.php class:rolle function:hideMenue - Speichern der Einstellungen zum Menü in der Rolle:",4);
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
		$this->debug->write("<p>file:users.php class:rolle function:hideMenue - Speichern der Einstellungen zum Menü in der Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}

	function setNachweisSuchparameter($suchffr,$suchkvz,$suchgn,$suchan,$abfrageart,$suchgemarkung,$suchflur,$stammnr,$suchrissnr,$suchfortf,$suchpolygon,$suchantrnr) {
		$sql ='UPDATE rolle_nachweise SET ';
		if ($suchffr!='') { $sql.='suchffr="'.$suchffr.'",'; }else{$sql.='suchffr="0",';}
		if ($suchkvz!='') { $sql.='suchkvz="'.$suchkvz.'",'; }else{$sql.='suchkvz="0",';}
		if ($suchgn!='') { $sql.='suchgn="'.$suchgn.'",'; }else{$sql.='suchgn="0",';}
		if ($suchan!='') { $sql.='suchan="'.$suchan.'",'; }else{$sql.='suchan="0",';}
		if ($abfrageart!='') { $sql.='abfrageart="'.$abfrageart.'",'; }
		$sql.='suchgemarkung="'.$suchgemarkung.'",';
		$sql.='suchflur="'.$suchflur.'",';
		$sql.='suchstammnr="'.$stammnr.'",';
		$sql.='suchrissnr="'.$suchrissnr.'",';
		if($suchfortf == '')$suchfortf = 'NULL';
		$sql.='suchfortf='.$suchfortf.',';
		if ($suchpolygon!='') { $sql.='suchpolygon="'.$suchpolygon.'",'; }
		if ($suchantrnr!='') { $sql.='suchantrnr="'.$suchantrnr.'",'; }
		$sql .= 'user_id = '.$this->user_id;
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$this->debug->write("<p>file:users.php class:rolle->setNachweisSuchparameter - Setzen der aktuellen Parameter für die Nachweissuche",4);
		$this->database->execSQL($sql,4, 1);
		return 1;
	}

	function setNachweisAnzeigeparameter($showffr,$showkvz,$showgn,$showan,$markffr,$markkvz,$markgn) {
		$sql ='UPDATE rolle_nachweise SET ';
		if ($showffr!='') { $sql.='showffr="'.$showffr.'",'; }else{$sql.='showffr="0",';}
		if ($showkvz!='') { $sql.='showkvz="'.$showkvz.'",'; }else{$sql.='showkvz="0",';}
		if ($showgn!='') { $sql.='showgn="'.$showgn.'",'; }else{$sql.='showgn="0",';}
		if ($showan!='') { $sql.='showan="'.$showan.'",'; }else{$sql.='showan="0",';}
		if ($markffr!='') { $sql.='markffr="'.$markffr.'",'; }
		if ($markkvz!='') { $sql.='markkvz="'.$markkvz.'",'; }
		if ($markgn!='') { $sql.='markgn="'.$markgn.'",'; }
		$sql .= 'user_id = '.$this->user_id;
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$this->debug->write("<p>file:users.php class:rolle->setNachweisAnzeigeparameter - Setzen der aktuellen Anzeigeparameter für die Nachweissuche",4);
		$this->database->execSQL($sql,4, 1);
		return 1;
	}

	function getNachweisParameter() {
		$sql ='SELECT *,CONCAT(showffr,showkvz,showgn,showan) AS art_einblenden';
		$sql.=',CONCAT(markffr,markkvz,markgn) AS art_markieren FROM rolle_nachweise';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$this->debug->write("<p>file:users.php class:user->getNachweisParameter - Abfragen der aktuellen Parameter für die Nachweissuche<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		if (mysql_num_rows($query)==0) {
			echo 'Der User mit der ID:'.$this->user_id.' fehlt mit der Stellen_ID:'.$this->stelle_id.' in der Tabelle rolle_nachweise.';
		}
		else {
			$rs=mysql_fetch_array($query);
		}
		return $rs;
	}


	# 2006-03-20 pk
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
			$ret[1]='<br>Fehler beim Speichern des Kommentares zum Kartenausschnitt.<br>'.$ret[1];
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

	# 2006-03-20 pk
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
				$sql .= ', numpages = '.$pagecount[$i];
				#echo $sql;
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
class stelle extends stelle_core{
	var $id;
	var $Bezeichnung;
	var $debug;
	var $nImageWidth;
	var $nImageHeight;
	var $oGeorefExt;
	var $pixsize;
	var $selectedButton;
	var $database;

	## Funktionen
	# addAktivLayer($layerid)
	# addLayer($layer_ids)
	# addMenue($menue_ids)
	# Aendern($stellendaten)
	# deleteLayer($layer)
	# deleteMenue($menues)
	# getFunktionen($action)
	# getLayers()
	# getMenues($ebene)
	# getName()
	# getGemeindeIDs()
	# getGemeinden()
	# getUsers()
	# getWappen()
	# setAktivLayer($formvars)
	# getstellendaten($stellendaten)
	# getLayer($LayerName)
	# isFunctionAllowed($functionname)
	# Löschen()
	# NeueStelleAnlegen($stellendaten)
	# readDefaultValues()
	# setAktivLayer($formvars)
	# setQueryStatus($formvars)
	# stelle - konstruktor

	function stelle($id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->id=$id;
		$this->database=$database;
		$this->Bezeichnung=$this->getName();
		$this->readDefaultValues();
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

	function deleteLayer($layer) {
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
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
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
		if($stellendaten['wappen']){
			$sql.=', wappen="'.$_files['wappen']['name'].'"';
		}
		elseif($stellendaten['wappen_save']){
			$sql.=', wappen="'.$stellendaten['wappen_save'].'"';
		}
		if($stellendaten['wasserzeichen']){
			$sql.=', wasserzeichen="'.$_files['wasserzeichen']['name'].'"';
		}
		elseif($stellendaten['wasserzeichen_save']){
			$sql.=', wasserzeichen="'.$stellendaten['wasserzeichen_save'].'"';
		}
		$sql.=', check_password_age="';
		if ($stellendaten['checkPasswordAge']=='1') {
			$sql.='1';
		}
		else {
			$sql.='0';
		}
		$sql.='"';
		$sql.=', allowed_password_age=';
		if ($stellendaten['allowedPasswordAge']!='') {
			$sql.=$stellendaten['allowedPasswordAge'];
		}
		else {
			$sql.='6';
		}
		$sql.=', use_layer_aliases="';
		if ($stellendaten['use_layer_aliases']=='1') {
			$sql.='1';
		}
		else {
			$sql.='0';
		}
		$sql.='"';
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
		if($stellendaten['wappen']){
			$sql.=', wappen="'.$stellendaten['wappen'].'"';
		}
		if($stellendaten['wasserzeichen']){
			$sql.=', wasserzeichen="'.$stellendaten['wasserzeichen'].'"';
		}
		$sql.=', check_password_age="';
		if ($stellendaten['checkPasswordAge']=='1') {
			$sql.='1';
		}
		else {
			$sql.='0';
		}
		$sql.='"';
		$sql.=', allowed_password_age=';
		if ($stellendaten['allowedPasswordAge']!='') {
			$sql.=$stellendaten['allowedPasswordAge'];
		}
		else {
			$sql.='6';
		}
		$sql.=', use_layer_aliases="';
		if ($stellendaten['use_layer_aliases']=='1') {
			$sql.='1';
		}
		else {
			$sql.='0';
		}
		$sql.='"';
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

	function getsubmenues($id){
		$sql ='SELECT menue_id,';
		if ($this->language != 'german') {
			$sql.='`name_'.$this->language.'_'.$this->charset.'` AS ';
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
            <img src="'.GRAPHICSPATH.'leer.gif" width="13" height="1" border="0">
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
			$html .= '" class="testred"';
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

	function getMenue($ebene) {
		# Lesen der Menuepunkte zur Stelle
		$sql ='SELECT menue_id,';
		if ($this->language != 'german') {
			$sql.='`name_'.$this->language.'_'.$this->charset.'` AS ';
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

	function addLayer($layer_ids, $drawingorder) {
		# Hinzufügen von Layern zur Stelle
		for ($i=0;$i<count($layer_ids);$i++) {
			$sql = "SELECT queryable, template, transparency, drawingorder, minscale, maxscale, offsite, privileg FROM layer WHERE Layer_ID = ".$layer_ids[$i];
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
			$offsite = $rs['offsite'];
			$privileg = $rs['privileg'];
			$sql ='INSERT IGNORE INTO used_layer ( `Stelle_ID` , `Layer_ID` , `queryable` , `drawingorder` , `minscale` , `maxscale` , `offsite` , `transparency`, `Filter` , `template` , `header` , `footer` , `symbolscale`, `privileg` )';
			$sql.="VALUES ('".$this->id."', '".$layer_ids[$i]."', '".$queryable."', '".$drawingorder."', '".$minscale."', '".$maxscale."', '".$offsite."' , ".$transparency.", NULL,'".$template."' , NULL , NULL , NULL, '".$privileg."')";
			#echo $sql.'<br>';
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
		$sql .= ', Filter = "'.$formvars['Filter'].'"';
		$sql .= ', template = "'.$formvars['template'].'"';
		$sql .= ', header = "'.$formvars['header'].'"';
		$sql .= ', footer = "'.$formvars['footer'].'"';
		if ($formvars['symbolscale']!='') {
			$sql .= ', symbolscale = '.$formvars['symbolscale'];
		}
		else{
			$sql .= ', symbolscale = NULL';
		}
		$sql .= ', requires = "'.$formvars['requires'].'"';
		$sql .= ', start_aktiv = "'.$formvars['startaktiv'].'"';
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
				$layer['Bezeichnung'] = $sorted_arrays['array'];
				$layer['ID'] = $sorted_arrays['second_array'];
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['drawingorder']);
				$layer['drawingorder'] = $sorted_arrays['second_array'];
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['Gruppe']);
				$layer['Gruppe'] = $sorted_arrays['second_array'];
			}
		}
		return $layer;
	}

	function getqueryablePostgisLayers($privileg){
		$sql = 'SELECT layer.Layer_ID, Name FROM used_layer, layer, u_groups';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND layer.Gruppe = u_groups.id AND layer.connectiontype = 6';
		$sql .=' AND layer.Layer_ID = used_layer.Layer_ID';
		$sql .=' AND used_layer.queryable = \'1\'';
		if($privileg != NULL){
			$sql .=' AND used_layer.privileg >= "'.$privileg.'"';
		}
		$sql .= ' ORDER BY Name';
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getqueryablePostgisLayers - Lesen der abfragbaren PostgisLayer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
			$layer['Bezeichnung'] = $sorted_arrays['array'];
			$layer['ID'] = $sorted_arrays['second_array'];
		}
		return $layer;
	}

	function getqueryableVectorLayers($privileg, $user_id, $group_id = NULL){
		$sql = 'SELECT layer.Layer_ID, Name, Gruppe, Gruppenname, `connection` FROM used_layer, layer, u_groups';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND layer.Gruppe = u_groups.id AND (layer.connectiontype = 6 OR layer.connectiontype = 9)';
		$sql .=' AND layer.Layer_ID = used_layer.Layer_ID';
		$sql .=' AND used_layer.queryable = \'1\'';
		if($privileg != NULL){
			$sql .=' AND used_layer.privileg >= "'.$privileg.'"';
		}
		if($group_id != NULL){
			$sql .=' AND u_groups.id = '.$group_id;
		}
		if($user_id != NULL){
			$sql .= ' UNION ';
			$sql .= 'SELECT -id as Layer_ID, concat(substring( `Name` FROM 1 FOR locate( ")", `Name` )), CASE WHEN Typ = "search" THEN " -Suchergebnis-" ELSE " -Shape-Import-" END), -1, " ", `connection` FROM rollenlayer';
			$sql .= ' WHERE stelle_id = '.$this->id.' AND user_id = '.$user_id.' AND connectiontype = 6';
		}


		$sql .= ' ORDER BY Name';
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getqueryableVectorLayers - Lesen der abfragbaren VektorLayer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)){
				 
				# fremde Layer werden auf Verbindung getestet
				if(strpos($rs['connection'], 'host') !== false AND strpos($rs['connection'], 'host=localhost') === false){
					$connection = explode(' ', trim($rs['connection']));
					for($j = 0; $j < count($connection); $j++){
						if($connection[$j] != ''){
							$value = explode('=', $connection[$j]);
							if(strtolower($value[0]) == 'host'){
								$host = $value[1];
							}
							if(strtolower($value[0]) == 'port'){
								$port = $value[1];
							}
						}
					}
					if($port == '')$port = '5432';
					$fp = @fsockopen($host, $port, $errno, $errstr, 0.1);
					if(!$fp){			# keine Verbindung --> Layer ausschalten
						#$this->Fehlermeldung = $errstr.' für Layer: '.$rs['Name'].'<br>';
						continue;
					}
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

	function parse_path($database, $path, $privileges){
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
				}
				else{   # tabellenname.attributname oder attributname
					$explosion = explode('.', strtolower($fieldstring[$i]));
					$attributename = trim($explosion[count($explosion)-1]);
				}
				if($privileges[$attributename] != ''){
					$newattributesstring .= $fieldstring[$i].', ';
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

	function set_layer_privileges($layer_id, $privileg){
		$sql = 'UPDATE used_layer SET privileg = "'.$privileg.'" WHERE ';
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
		$sql = 'SELECT Gemeinde_ID AS ID FROM stelle_gemeinden WHERE Stelle_ID = '.$this->id;
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getGemeindeIDs - Lesen der GemeindeIDs zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		while($rs=mysql_fetch_array($query)) {
			$liste[] = $rs;
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
}
?>
