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
#############################
# Klasse dbf #
#############################

class esaf {

  ################### Liste der Funktionen ########################################################################################################
  # esaf($database)
  ##################################################################################################################################################

  function esaf($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }

  function read_eigentuemer_data(){
    $sql = " SELECT DISTINCT gb.pruefzeichen, e.bezirk, e.blatt, e.namensnr, n.lfd_nr_name, n.name1, n.name2, n.name3, n.name4, nt.neu_name3, nt.neu_name4, e.anteilsverhaeltnis AS Anteil, e.eigentuemerart AS art";
    $sql.= " FROM alb_g_eigentuemer AS e, alb_g_namen AS n, alb_g_namen_temp AS nt, alb_grundbuecher as gb";
    $sql.= " WHERE e.lfd_nr_name = n.lfd_nr_name";
    $sql.= " AND e.bezirk = gb.bezirk";
    $sql.= " AND e.blatt = gb.blatt";
    $sql.= " AND gb.aktualitaetsnr NOT LIKE 'hist'";
    $sql.= " AND ((nt.name1 IS NULL AND (n.name1 IS NULL OR n.name1 = '')) OR nt.name1 = n.name1)";
    $sql.= " AND ((nt.name2 IS NULL AND (n.name2 IS NULL OR n.name2 = '')) OR nt.name2 = n.name2)";
    if (n.name2=='') {
      $sql.= " AND ((nt.name3 IS NULL AND (n.name3 IS NULL OR n.name3 = '')) OR nt.name3 = n.name3)";
      $sql.= " AND ((nt.name4 IS NULL AND (n.name4 IS NULL OR n.name4 = '')) OR nt.name4 = n.name4)";
    }
    $sql.= " ORDER BY e.bezirk";
    #echo $sql;
    $ret = $this->database->execSQL($sql, 4, 0);
    while($rs = pg_fetch_array($ret[1])){
      $this->eigentuemerliste[] = $rs;
    }
  }

  function get_last_auftragsnummer($kswert){
    $sql = "SELECT datum FROM tabelleninfo WHERE thema = 'adressaend".$kswert."'";
    $ret = $this->database->execSQL($sql, 4, 0);
    $rs = pg_fetch_array($ret[1]);
    $this->auftragsnummer = $rs[0];
  }

  function update_auftragsnummer($nummer, $kswert){
    $sql = "UPDATE tabelleninfo SET datum = '".$nummer."' WHERE thema = 'adressaend".$kswert."'";
    $ret = $this->database->execSQL($sql, 4, 0);
  }

  function delete_old_entries(){
    $sql = "DELETE FROM alb_g_namen_temp ";
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_g_namen";
    }
    $sql.= " WHERE ((alb_g_namen_temp.name1 IS NULL AND (alb_g_namen.name1 IS NULL OR alb_g_namen.name1 = '')) OR alb_g_namen_temp.name1 = alb_g_namen.name1)";
    $sql.= " AND ((alb_g_namen_temp.name2 IS NULL AND (alb_g_namen.name2 IS NULL OR alb_g_namen.name2 = '')) OR alb_g_namen_temp.name2 = alb_g_namen.name2)";
    $sql.= " AND ((alb_g_namen_temp.neu_name3 IS NULL AND (alb_g_namen.name3 IS NULL OR alb_g_namen.name3 = '')) OR alb_g_namen_temp.neu_name3 = alb_g_namen.name3)";
    $sql.= " AND ((alb_g_namen_temp.neu_name4 IS NULL AND (alb_g_namen.name4 IS NULL OR alb_g_namen.name4 = '')) OR alb_g_namen_temp.neu_name4 = alb_g_namen.name4)";
    #echo $sql;
    $ret = $this->database->execSQL($sql, 4, 0);
  }

  function export_into_file(){
  	global $katasterfuehrendestelle;
    if($this->eigentuemerliste != ''){
      $last_bezirk = '';
      $fp = NULL;
      $folder = 'esaf64_export';
      mkdir(IMAGEPATH.$folder);                       # Ordner erzeugen
      for($i = 0; $i < count($this->eigentuemerliste); $i++){
        if($this->eigentuemerliste[$i]['bezirk'] != $last_bezirk){
        	if($katasterfuehrendestelle){
	        	foreach ($katasterfuehrendestelle as $key => $value) {
					    if($this->eigentuemerliste[$i]['bezirk'] <= $key) {
					      $kswert = $value;
					      break;
					    }
	        	}
        	}
        	$this->get_last_auftragsnummer($kswert);
        	if($kswert == ''){
          	$kstelle = substr(AMT, -4, 4);
          }
          else{
          	$kstelle = $kswert;
          }
          if($fp != NULL){
            fclose($fp);
          }
          $filename = 'esaf64-export_'.$this->eigentuemerliste[$i]['bezirk'].'_'.$this->auftragsnummer.'.esaf';
          $filenames[] = $filename;
          $fp = fopen(IMAGEPATH.$folder.'/'.$filename, 'w');
          fwrite($fp, '*64; '.$kstelle.'; '.str_pad($this->auftragsnummer, 5, '0', STR_PAD_LEFT).'; ; ; ; '.chr(10));
          $last_bezirk = $this->eigentuemerliste[$i]['bezirk'];
          $this->auftragsnummer = $this->auftragsnummer + 1;
          $this->update_auftragsnummer($this->auftragsnummer, $kswert);
        }
        $pointpos = strpos($this->eigentuemerliste[$i]['namensnr'], '.');
        $explosion = explode('.', $this->eigentuemerliste[$i]['namensnr']);
        if($pointpos !== false){
          $rest = substr($this->eigentuemerliste[$i]['namensnr'], $pointpos);
        }
        else{
          $rest = '';
        }
        $namensnr = str_pad(str_pad($explosion[0], 4, '0', STR_PAD_LEFT).$rest, 16, '.00');
        $data = utf8_decode('4'.$this->eigentuemerliste[$i]['bezirk'].'-'.$this->eigentuemerliste[$i]['blatt'].' '.$this->eigentuemerliste[$i]['pruefzeichen'].'; ; '.$namensnr.'  ; ; '.$this->eigentuemerliste[$i]['art'].'; '.$this->eigentuemerliste[$i]['name1'].'# '.$this->eigentuemerliste[$i]['name2'].'# '.$this->eigentuemerliste[$i]['neu_name3'].'# '.$this->eigentuemerliste[$i]['neu_name4'].';0D0A'.chr(10));
        fwrite($fp, $data);
      }

      exec(ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*'); # Ordner zippen
      $zipfilename = TEMPPATH_REL.$folder.'.zip';
      for($i = 0; $i < count($filenames); $i++){
        unlink(IMAGEPATH.$folder.'/'.$filenames[$i]);
      }
      rmdir(IMAGEPATH.$folder);         # Ordner löschen

      return $zipfilename;
    }
  }

}
?>
