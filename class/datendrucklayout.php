<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 # 
# Copyright (C) 2010  Peter Korduan                               #
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
#############################
# Klasse ddl #
#############################

class ddl {
    
  function ddl($database, $gui = NULL) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
    $this->gui = $gui;
  }
  
  function add_static_elements($i){
		# Hintergrundbild    
		if($this->layout['bgsrc']){
    	$this->pdf->addJpegFromFile(DRUCKRAHMEN_PATH.basename($this->layout['bgsrc']),$this->layout['bgposx'],$this->layout['bgposy'],$this->layout['bgwidth']);
		}
    # Datum
    if($this->layout['datesize']){
    	$this->pdf->selectFont($this->layout['font_date']);
    	$this->pdf->addText($this->layout['dateposx'],$this->layout['dateposy'],$this->layout['datesize'],date("d.m.Y"));
    }
    # Nutzer
    if($this->layout['usersize']){
    	$this->pdf->selectFont($this->layout['font_user']);
    	$this->pdf->addText($this->layout['userposx'],$this->layout['userposy'],$this->layout['usersize'], utf8_decode('Stelle: '.$this->Stelle->Bezeichnung.', Nutzer: '.$this->user->Name));
    }
    # feste Freitexte
    for($j = 0; $j < count($this->layout['texts']); $j++){
    	if($this->layout['type'] == 0 OR $this->layout['texts'][$j]['type'] == 1){		# entweder Layout ist vom seitenweisen Typ oder die Texte sind feststehend
	      $this->pdf->selectFont($this->layout['texts'][$j]['font']);
	      $freitext = explode(';', $this->substituteFreitext($this->layout['texts'][$j]['text'], $i));
	      $anzahlzeilen = count($freitext);
	      $alpha = $this->layout['texts'][$j]['angle'];
	      for($z = 0; $z < $anzahlzeilen; $z++){
	        $h = $z * $this->layout['texts'][$j]['size'] * 1.25;
	        $a = sin(deg2rad($alpha)) * $h;
	        $b = cos(deg2rad($alpha)) * $h;
	        $posx = $this->layout['texts'][$j]['posx'] + $a;
	        $posy = $this->layout['texts'][$j]['posy'] - $b;
	        $width = $this->pdf->getTextWidth($this->layout['texts'][$j]['size'], $freitext[$z]);
	        if($posx < 0){
	        	$posx = 595 + $posx - $width;
	        	$justification = 'right';
	        }
	        else{
	        	$justification = 'left';
	        }
	        $this->pdf->addTextWrap($posx,$posy,$width,$this->layout['texts'][$j]['size'],utf8_decode($freitext[$z]),$justification,-1 * $alpha);
	      }
	    }
	  }
  }
  
  function substituteFreitext($text, $i){
  	$text = str_replace('$stelle', $this->Stelle->Bezeichnung, $text);
  	$text = str_replace('$user', $this->user->Name, $text);
	for($j = 0; $j < count($this->attributes['name']); $j++){
		$text = str_replace('$'.$this->attributes['name'][$j], $this->get_result_value_output($i, $j), $text);
	}
  	return $text;
  }
  
  function get_result_value_output($i, $j){		# $i ist der result-counter, $j ist der attribute-counter
	switch ($this->attributes['form_element_type'][$j]){
		case 'Auswahlfeld' : {
			for($e = 0; $e < count($this->attributes['enum_value'][$j]); $e++){
				if($this->attributes['enum_value'][$j][$e] == $this->result[$i][$this->attributes['name'][$j]]){
					$output = utf8_decode($this->attributes['enum_output'][$j][$e]);
					break;
				}
				else $output = utf8_decode($this->result[$i][$this->attributes['name'][$j]]);
			}
			if(count($this->attributes['enum_value'][$j]) == 0){	
				$output = utf8_decode($this->result[$i][$this->attributes['name'][$j]]);
			}
		}break;
		default: {
			$output = utf8_decode($this->result[$i][$this->attributes['name'][$j]]);
		}break;
	}
	return $output;
  }
  
  function createDataPDF($layerdb, $layerset, $attributes, $selected_layer_id, $layout, $oids, $result, $stelle, $user){
  	$this->layout = $layout;
  	$this->Stelle = $stelle;
	$this->attributes = $attributes;
	$this->result = $result;
  	$this->user = $user;
  	$this->maxy = 0;
  	$this->miny = 1000000;
  	$i_on_page = -1;
  	$datasetcount_on_page = 0;
		# Einbinden der PDF Klassenbibliotheken
    include (PDFCLASSPATH."class.ezpdf.php");
    # Erzeugen neue pdf-Klasse
    $this->pdf=new Cezpdf();
    $this->add_static_elements(0);
    if($this->layout['elements'][$attributes['the_geom']]['xpos'] > 0){		# wenn ein Geometriebild angezeigt werden soll -> loadmap()
    	$this->gui->map_factor = MAPFACTOR;
    	$this->gui->loadmap('DataBase');
    }
    for($i = 0; $i < count($result); $i++){
    	$i_on_page++;
    	$new_dataset = true;
    	if($this->layout['type'] == 0 AND $i > 0){		# neue Seite beim seitenweisen Typ und neuem Datensatz 
    		$this->pdf->newPage();
    		$this->add_static_elements($i);
    	}
	    if($datasetcount_on_page > 0 AND $this->layout['type'] == 1  AND $this->miny < $this->yoffset_onpage/$datasetcount_on_page + 50){		# neue Seite beim Untereinander-Typ und Seitenüberlauf
				$datasetcount_on_page = 0;
				$i_on_page = 0;
				$this->maxy = 0;
  			$this->miny = 1000000;
				$this->pdf->newPage();
				$this->add_static_elements($i);
			}
			$this->yoffset_onpage = $this->maxy - $this->miny;			# der Offset mit dem die Elemente beim Untereinander-Typ nach unten versetzt werden
			# fortlaufende Freitexte
			if($this->layout['type'] == 1){
		    for($j = 0; $j < count($this->layout['texts']); $j++){
		    	if($this->layout['texts'][$j]['type'] == 0){		# Layout ist vom Untereinander-Typ und die Texte sind fortlaufend
			      $this->pdf->selectFont($this->layout['texts'][$j]['font']);
			      $freitext = explode(';', $this->substituteFreitext($this->layout['texts'][$j]['text'], $i));
			      $anzahlzeilen = count($freitext);
			      $alpha = $this->layout['texts'][$j]['angle'];
			      for($z = 0; $z < $anzahlzeilen; $z++){
			        $h = $z * $this->layout['texts'][$j]['size'] * 1.25;
			        $a = sin(deg2rad($alpha)) * $h;
			        $b = cos(deg2rad($alpha)) * $h;
			        $posx = $this->layout['texts'][$j]['posx'] + $a;
			        $y = $this->layout['texts'][$j]['posy'] - $b;
			        # beim ersten Datensatz die Gesamthoehe der Elemente eines Datensatzes ermitteln 
		      		if($i_on_page == 0){
		      			if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
		      		}
		      		if($new_dataset){
	  						$this->miny = 10000;
	  						$new_dataset = false;
		      		}
			        if($i_on_page > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben
		      			$y = $y - $this->yoffset_onpage-18;
		      		}
			        $width = $this->pdf->getTextWidth($this->layout['texts'][$j]['size'], $freitext[$z]);
			        if($posx < 0){
			        	$posx = 595 + $posx - $width;
			        	$justification = 'right';
			        }
			        else{
			        	$justification = 'left';
			        }
			        $this->pdf->addTextWrap($posx,$y,$width,$this->layout['texts'][$j]['size'],utf8_decode($freitext[$z]),$justification,-1 * $alpha);
			      }
			    }
			  }
			}			
			# Daten schreiben
      for($j = 0; $j < count($attributes['name']); $j++){
      	$wordwrapoffset = 0;
      	if($attributes['type'][$j] != 'geometry' && $this->layout['elements'][$attributes['name'][$j]]['fontsize'] > 0){
      		$this->pdf->selectFont($this->layout['elements'][$attributes['name'][$j]]['font']);
					if($result[$i][$attributes['name'][$j]] != ''){
						$data = array(array($attributes['name'][$j] => $this->get_result_value_output($i, $j)));
						# Zeilenumbruch berücksichtigen
						$text = $result[$i][$attributes['name'][$j]];
						$size = $this->layout['elements'][$attributes['name'][$j]]['fontsize'];
						$textwidth = $this->pdf->getTextWidth($size, $text);
						$width = $this->layout['elements'][$attributes['name'][$j]]['width'];
						if($width != '' AND $textwidth/$width > 1){ 
							while($text != ''){
								$text = $this->pdf->addTextWrap(-100, -100, $width, $size, utf8_decode($text));
								$wordwrapoffset++;
							} 
						}
						$ypos = $this->layout['elements'][$attributes['name'][$j]]['ypos'];
						$zeilenhoehe = $this->layout['elements'][$attributes['name'][$j]]['fontsize'];      		      		
	      		$y = $ypos+$zeilenhoehe+5;
	      		if($this->layout['type'] == 1 AND $i_on_page > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben
	      			$y = $y - $this->yoffset_onpage-18;
	      		}	
	      		$this->pdf->ezSetY($y);
	      		# beim jedem Datensatz die Gesamthoehe der Elemente des Datensatzes ermitteln
	      		if($i_on_page == 0){
	      			if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
	      		}
	      		if($new_dataset){
  						$this->miny = 10000;
  						$new_dataset = false;
	      		} 
      			if($this->miny > $y-$wordwrapoffset*$zeilenhoehe)$this->miny = $y-$wordwrapoffset*$zeilenhoehe;		# miny ist die unterste y-Position das aktuellen Datensatzes 
	      			
	      		if($this->layout['elements'][$attributes['name'][$j]]['xpos'] < 0){
	      			$this->layout['elements'][$attributes['name'][$j]]['xpos'] = 595 + $this->layout['elements'][$attributes['name'][$j]]['xpos'];
	      			$justification = 'right';
	      			$orientation = 'left';
	      		}
	      		else{
	      			$justification = 'left';
	      			$orientation = 'right';
	      		}
	      		if($this->layout['elements'][$attributes['name'][$j]]['border'] == ''){
	      			$this->layout['elements'][$attributes['name'][$j]]['border'] = 0;
	      		}
	      		$this->pdf->ezTable($data, NULL, NULL, 
	      		array('xOrientation'=>$orientation, 
									'xPos'=>$this->layout['elements'][$attributes['name'][$j]]['xpos'], 
									'width'=>$this->layout['elements'][$attributes['name'][$j]]['width'], 
									'maxWidth'=>$this->layout['elements'][$attributes['name'][$j]]['width'], 
									'fontSize'=>$this->layout['elements'][$attributes['name'][$j]]['fontsize'], 
									'showHeadings'=>0, 
									'shaded'=>0, 
									'cols'=>array($attributes['name'][$j]=>array('justification'=>$justification)),
									'showLines'=>$this->layout['elements'][$attributes['name'][$j]]['border']));
					}
      	}
      	elseif($attributes['name'][$j] == $attributes['the_geom'] AND $this->layout['elements'][$attributes['name'][$j]]['xpos'] > 0){		# Geometrie
      		if($oids[$i] != ''){
      			$polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->gui->user->rolle->epsg_code);
      			$rect = $polygoneditor->zoomTopolygon($oids[$i], $attributes['table_name'][$attributes['the_geom']], $attributes['the_geom'], 10);
      			$this->gui->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
      		}
      		$this->gui->map->set('width', $this->layout['elements'][$attributes['name'][$j]]['width']*MAPFACTOR);
        	$this->gui->map->set('height', $this->layout['elements'][$attributes['name'][$j]]['width']*MAPFACTOR);
      		if($this->gui->map->selectOutputFormat('jpeg_print') == 1){
			      $this->gui->map->selectOutputFormat('jpeg');
			    }
      		$image_map = $this->gui->map->draw();
			    $filename = $this->gui->map_saveWebImage($image_map,'jpeg');
			    $newname = $this->user->id.basename($filename);
			    rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
			    $y = $this->layout['elements'][$attributes['name'][$j]]['ypos'];
      		if($i_on_page == 0){
	      		if($this->maxy < $y+$this->layout['elements'][$attributes['name'][$j]]['width'])$this->maxy = $y+$this->layout['elements'][$attributes['name'][$j]]['width'];		# beim ersten Datensatz das maxy ermitteln
	      	}    
			    if($this->layout['type'] == 1 AND $i_on_page > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben
      			$y = $y - $this->yoffset_onpage-18;
      		}
			    $this->pdf->addJpegFromFile(IMAGEPATH.$newname, $this->layout['elements'][$attributes['name'][$j]]['xpos'], $y, $this->layout['elements'][$attributes['name'][$j]]['width']);
			    if($this->miny > $y)$this->miny = $y;
      	}
      }
      $datasetcount_on_page++;
    }
    $dateipfad=IMAGEPATH;
    $currenttime = date('Y-m-d_H_i_s',time());
    $name = umlaute_umwandeln($this->user->Name);    
    $dateiname = $name.'-'.$currenttime.'.pdf';
    $this->outputfile = $dateiname;
    $fp=fopen($dateipfad.$dateiname,'wb');
    fwrite($fp,$this->pdf->ezOutput());
    fclose($fp);
    return $dateipfad.$dateiname;
	}

	function save_layout($formvars, $attributes, $_files, $stelle_id){
    if($formvars['name']){
    	if($formvars['font_date'] == 'NULL')$formvars['font_date'] = NULL;
      $sql = "INSERT INTO `datendrucklayouts`";
      $sql .= " SET `name` = '".$formvars['name']."'";
      $sql .= ", `layer_id` = ".(int)$formvars['selected_layer_id'];
  		if($formvars['bgposx'])$sql .= ", `bgposx` = ".(int)$formvars['bgposx'];
  		else $sql .= ", `bgposx` = NULL";
      if($formvars['bgposy'])$sql .= ", `bgposy` = ".(int)$formvars['bgposy'];
      else $sql .= ", `bgposy` = NULL";
      if($formvars['bgwidth'])$sql .= ", `bgwidth` = ".(int)$formvars['bgwidth'];
      else $sql .= ", `bgwidth` = NULL";
      if($formvars['bgheight'])$sql .= ", `bgheight` = ".(int)$formvars['bgheight'];
      else $sql .= ", `bgheight` = NULL";
      if($formvars['dateposx'])$sql .= ", `dateposx` = ".(int)$formvars['dateposx'];
      else $sql .= ", `dateposx` = NULL";
      if($formvars['dateposy'])$sql .= ", `dateposy` = ".(int)$formvars['dateposy'];
      else $sql .= ", `dateposy` = NULL";
      if($formvars['datesize'])$sql .= ", `datesize` = ".(int)$formvars['datesize'];
      else $sql .= ", `datesize` = NULL";
      if($formvars['userposx'])$sql .= ", `userposx` = ".(int)$formvars['userposx'];
      else $sql .= ", `userposx` = NULL";
      if($formvars['userposy'])$sql .= ", `userposy` = ".(int)$formvars['userposy'];
      else $sql .= ", `userposy` = NULL";
      if($formvars['usersize'])$sql .= ", `usersize` = ".(int)$formvars['usersize'];
      else $sql .= ", `usersize` = NULL";
      $sql .= ", `font_date` = '".$formvars['font_date']."'";
      $sql .= ", `font_user` = '".$formvars['font_user']."'";
      if($formvars['type'] != '')$sql .= ", `type` = ".(int)$formvars['type'];
      else $sql .= ", `type` = NULL";
      if($_files['bgsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['bgsrc']['name'];
        if (move_uploaded_file($_files['bgsrc']['tmp_name'],$nachDatei)) {
          $sql .= ", `bgsrc` = '".$_files['bgsrc']['name']."'";
        }
      }
      else{
        $sql .= ", `bgsrc` = '".$formvars['bgsrc_save']."'";
      }
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);
      $lastddl_id = mysql_insert_id();

      $sql = 'INSERT INTO ddl2stelle (stelle_id, ddl_id) VALUES('.$stelle_id.', '.$lastddl_id.')';
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);

			for($i = 0; $i < count($attributes['name']); $i++){
				if($formvars['font_'.$attributes['name'][$i]] == 'NULL')$formvars['font_'.$attributes['name'][$i]] = NULL;
				$sql = "REPLACE INTO ddl_elemente SET ddl_id = ".$lastddl_id;
				$sql.= " ,name = '".$attributes['name'][$i]."'";
				$sql.= " ,xpos = ".(real)$formvars['posx_'.$attributes['name'][$i]];
				$sql.= " ,ypos = ".(real)$formvars['posy_'.$attributes['name'][$i]];
				if($formvars['width_'.$attributes['name'][$i]])$sql.= " ,width = ".(int)$formvars['width_'.$attributes['name'][$i]];
				else $sql.= " ,width = NULL";
				if($formvars['border_'.$attributes['name'][$i]])$sql.= " ,border = ".(int)$formvars['border_'.$attributes['name'][$i]];
				else $sql.= " ,border = NULL";
				$sql.= " ,font = '".$formvars['font_'.$attributes['name'][$i]]."'";
				$sql.= " ,fontsize = ".(int)$formvars['fontsize_'.$attributes['name'][$i]];
				#echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
        $this->database->execSQL($sql,4, 1);
			}
			
			$sql = "DELETE FROM ddl_elemente WHERE ((xpos IS NULL AND ypos IS NULL) OR (xpos = 0 AND ypos = 0) OR (xpos > 595 AND ypos > 842)) AND ddl_id = ".$lastddl_id;
			#echo $sql;
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        if($formvars['text'.$i] == 'NULL')$formvars['text'.$i] = NULL;
        if($formvars['textfont'.$i] == 'NULL')$formvars['textfont'.$i] = NULL;
        $sql = "INSERT INTO druckfreitexte SET `text` = '".$formvars['text'.$i]."'";
        if($formvars['textposx'.$i])$sql .= ", `posx` = ".(int)$formvars['textposx'.$i];
        else $sql .= ", `posx` = NULL";
        if($formvars['textposy'.$i])$sql .= ", `posy` = ".(int)$formvars['textposy'.$i];
        else $sql .= ", `posy` = NULL";
        if($formvars['textsize'.$i])$sql .= ", `size` = ".(int)$formvars['textsize'.$i];
        else $sql .= ", `size` = NULL";
        if($formvars['textangle'.$i])$sql .= ", `angle` = ".(int)$formvars['textangle'.$i];
        else $sql .= ", `angle` = NULL";
        $sql .= ", `font` = '".$formvars['textfont'.$i]."'";
        $sql .= ", `type` = '".$formvars['texttype'.$i]."'";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
        $this->database->execSQL($sql,4, 1);
        $lastfreitext_id = mysql_insert_id();

        $sql = 'INSERT INTO ddl2freitexte (ddl_id, freitext_id) VALUES('.$lastddl_id.', '.$lastfreitext_id.')';
        $this->debug->write("<p>file:kvwmap class:ddl->save_layout :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
    return $lastddl_id;
  } 
  
  function update_layout($formvars, $attributes, $_files){
  	$_files = $_FILES;
    if($formvars['name']){
    	if($formvars['font_date'] == 'NULL')$formvars['font_date'] = NULL;
      $sql = "UPDATE `datendrucklayouts`";
      $sql .= " SET `name` = '".$formvars['name']."'";
      $sql .= ", `layer_id` = ".(int)$formvars['selected_layer_id'];
  		if($formvars['bgposx'])$sql .= ", `bgposx` = ".(int)$formvars['bgposx'];
  		else $sql .= ", `bgposx` = NULL";
      if($formvars['bgposy'])$sql .= ", `bgposy` = ".(int)$formvars['bgposy'];
      else $sql .= ", `bgposy` = NULL";
      if($formvars['bgwidth'])$sql .= ", `bgwidth` = ".(int)$formvars['bgwidth'];
      else $sql .= ", `bgwidth` = NULL";
      if($formvars['bgheight'])$sql .= ", `bgheight` = ".(int)$formvars['bgheight'];
      else $sql .= ", `bgheight` = NULL";
      if($formvars['dateposx'])$sql .= ", `dateposx` = ".(int)$formvars['dateposx'];
      else $sql .= ", `dateposx` = NULL";
      if($formvars['dateposy'])$sql .= ", `dateposy` = ".(int)$formvars['dateposy'];
      else $sql .= ", `dateposy` = NULL";
      if($formvars['datesize'])$sql .= ", `datesize` = ".(int)$formvars['datesize'];
      else $sql .= ", `datesize` = NULL";
      if($formvars['userposx'])$sql .= ", `userposx` = ".(int)$formvars['userposx'];
      else $sql .= ", `userposx` = NULL";
      if($formvars['userposy'])$sql .= ", `userposy` = ".(int)$formvars['userposy'];
      else $sql .= ", `userposy` = NULL";
      if($formvars['usersize'])$sql .= ", `usersize` = ".(int)$formvars['usersize'];
      else $sql .= ", `usersize` = NULL";
      $sql .= ", `font_date` = '".$formvars['font_date']."'";
      $sql .= ", `font_user` = '".$formvars['font_user']."'";
      if($formvars['type'])$sql .= ", `type` = ".(int)$formvars['type'];
      else $sql .= ", `type` = NULL";
      if($_files['bgsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['bgsrc']['name'];
        if (move_uploaded_file($_files['bgsrc']['tmp_name'],$nachDatei)) {
          $sql .= ", `bgsrc` = '".$_files['bgsrc']['name']."'";
        }
      }
      else{
        $sql .= ", `bgsrc` = '".$formvars['bgsrc_save']."'";
      }
      $sql .= " WHERE id = ".(int)$formvars['aktivesLayout'];
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);
      $lastddl_id = mysql_insert_id();

			for($i = 0; $i < count($attributes['name']); $i++){
				$sql = "REPLACE INTO ddl_elemente SET ddl_id = ".(int)$formvars['aktivesLayout'];
				$sql.= " ,name = '".$attributes['name'][$i]."'";
				$sql.= " ,xpos = ".(real)$formvars['posx_'.$attributes['name'][$i]];
				$sql.= " ,ypos = ".(real)$formvars['posy_'.$attributes['name'][$i]];
				if($formvars['width_'.$attributes['name'][$i]] != '')$sql.= " ,width = ".(int)$formvars['width_'.$attributes['name'][$i]];
				else $sql.= " ,width = NULL";
				if($formvars['border_'.$attributes['name'][$i]] != '')$sql.= " ,border = ".(int)$formvars['border_'.$attributes['name'][$i]];
				else $sql.= " ,border = NULL";
				$sql.= " ,font = '".$formvars['font_'.$attributes['name'][$i]]."'";
				$sql.= " ,fontsize = ".(int)$formvars['fontsize_'.$attributes['name'][$i]];
				#echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
        $this->database->execSQL($sql,4, 1);
			}
			$sql = "DELETE FROM ddl_elemente WHERE ((xpos IS NULL AND ypos IS NULL) OR (xpos = 0 AND ypos = 0) OR (xpos > 595 AND ypos > 842)) AND ddl_id = ".(int)$formvars['aktivesLayout'];
			#echo $sql;
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        if($formvars['text'.$i] == 'NULL')$formvars['text'.$i] = NULL;
        if($formvars['textfont'.$i] == 'NULL')$formvars['textfont'.$i] = NULL;
        $sql = "UPDATE druckfreitexte SET `text` = '".$formvars['text'.$i]."'";
        if($formvars['textposx'.$i])$sql .= ", `posx` = ".(int)$formvars['textposx'.$i];
        else $sql .= ", `posx` = NULL";
        if($formvars['textposy'.$i])$sql .= ", `posy` = ".(int)$formvars['textposy'.$i];
        else $sql .= ", `posy` = NULL";
        if($formvars['textsize'.$i])$sql .= ", `size` = ".(int)$formvars['textsize'.$i];
        else $sql .= ", `size` = NULL";
        if($formvars['textangle'.$i])$sql .= ", `angle` = ".(int)$formvars['textangle'.$i];
        else $sql .= ", `angle` = NULL";
        $sql .= ", `font` = '".$formvars['textfont'.$i]."'";
        if($formvars['texttype'.$i] == '')$formvars['texttype'.$i] = 0;
        $sql .= ", `type` = '".$formvars['texttype'.$i]."'";
        $sql .= " WHERE id = ".(int)$formvars['text_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->update_layout :",4);
        $this->database->execSQL($sql,4, 1);
        $lastfreitext_id = mysql_insert_id();
      }
    }
  }
 	
 	function load_layouts($stelle_id, $ddl_id, $layer_id){
    $sql = 'SELECT DISTINCT datendrucklayouts.* FROM datendrucklayouts';
    if($ddl_id AND !$stelle_id){$sql .= ' WHERE datendrucklayouts.id ='.$ddl_id;}
    if($stelle_id AND !$layer_id AND !$ddl_id){
    	$sql.= ', ddl2stelle WHERE ddl2stelle.ddl_id = datendrucklayouts.id';
    	$sql .= ' AND ddl2stelle.stelle_id = '.$stelle_id;
    }
    if($ddl_id AND $stelle_id){
    	$sql.= ', ddl2stelle WHERE ddl2stelle.ddl_id = '.$ddl_id;
    	$sql .= ' AND ddl2stelle.stelle_id = '.$stelle_id;
    	$sql .= ' AND datendrucklayouts.id ='.$ddl_id;
    }
    if($layer_id AND !$stelle_id){
    	$sql .= ' WHERE layer_id = '.$layer_id;
    }
    if($layer_id AND $stelle_id){
    	$sql.= ', ddl2stelle WHERE ddl2stelle.ddl_id = datendrucklayouts.id';
    	$sql .= ' AND layer_id = '.$layer_id;
    	$sql .= ' AND ddl2stelle.stelle_id = '.$stelle_id;
    }
    $sql .= ' ORDER BY name';
    #echo $sql.'<br>';
    $this->debug->write("<p>file:kvwmap class:ddl->load_layouts :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($query)){
      $layouts[] = $rs;
      #$layouts[0]['bilder'] = $this->load_bilder($rs['id']);
      $layouts[0]['elements'] = $this->load_elements($rs['id']);
      $layouts[0]['texts'] = $this->load_texts($rs['id']);
      $i++;
    }
    return $layouts;
  }
  
  function delete_layout($formvars){
    $sql = "DELETE FROM `datendrucklayouts` WHERE id = ".(int)$formvars['selected_layout_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->delete_layout :",4);
    $this->database->execSQL($sql,4, 1);

		$sql = "DELETE FROM ddl_elemente WHERE ddl_id = ".(int)$formvars['selected_layout_id'];					
    $this->debug->write("<p>file:kvwmap class:ddl->delete_layout :",4);
    $this->database->execSQL($sql,4, 1);

    $sql = "DELETE FROM druckfreitexte, ddl2freitexte USING druckfreitexte, ddl2freitexte WHERE ddl2freitexte.freitext_id = druckfreitexte.id AND ddl2freitexte.ddl_id = ".(int)$formvars['selected_layout_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->delete_layout :",4);
    $this->database->execSQL($sql,4, 1);
    
    $sql = "DELETE FROM ddl2stelle WHERE ddl_id = ".(int)$formvars['selected_layout_id'];					
    $this->debug->write("<p>file:kvwmap class:ddl->delete_layout :",4);
    $this->database->execSQL($sql,4, 1);
  }
  
  function load_elements($ddl_id){
    $sql = 'SELECT * FROM ddl_elemente';
    $sql.= ' WHERE ddl_elemente.ddl_id = '.$ddl_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:ddl->load_elements :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $elements[$rs['name']] = $rs;
    }
    return $elements;
  }
  
  function load_texts($ddl_id){
    $sql = 'SELECT druckfreitexte.* FROM druckfreitexte, ddl2freitexte';
    $sql.= ' WHERE ddl2freitexte.ddl_id = '.$ddl_id;
    $sql.= ' AND ddl2freitexte.freitext_id = druckfreitexte.id';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:ddl->load_texts :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $texts[] = $rs;
    }
    return $texts;
  }
  
  function addfreetext($formvars){
    $sql = 'INSERT INTO druckfreitexte SET';
    $sql .= ' text = "",';
    $sql .= ' posx = 0,';
    $sql .= ' posy = 0,';
    $sql .= ' size = 0,';
    $sql .= ' font = "",';
    $sql .= ' angle = 0';
    $this->debug->write("<p>file:kvwmap class:ddl->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $lastinsert_id = mysql_insert_id();
    $sql = 'INSERT INTO ddl2freitexte (ddl_id, freitext_id) VALUES ('.$formvars['aktivesLayout'].', '.$lastinsert_id.')';
    $this->debug->write("<p>file:kvwmap class:ddl->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }
  
  function removefreetext($formvars){
    $sql = 'DELETE FROM druckfreitexte WHERE id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $sql = 'DELETE FROM ddl2freitexte WHERE freitext_id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }
  
  function add_layout2stelle($id, $stelleid){
    $sql ="INSERT IGNORE INTO ddl2stelle VALUES (".$stelleid.", ".$id.")";
    $this->debug->write("<p>file:kvwmap class:ddl->add_layout2stelle :",4);
    $this->database->execSQL($sql,4, 1);
  }
 
 	function get_fonts(){
 		$fonts = searchdir(PDFCLASSPATH.'fonts/', true);
 		$count = count($fonts);
 		for($i = 0; $i < $count; $i++){
 			if(strpos($fonts[$i], 'php_')){
 				unset($fonts[$i]);
 			}
 		}
 		sort($fonts);
 		return $fonts;
 	}
 
}
	
?>
