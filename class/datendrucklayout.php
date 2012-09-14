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
    
  function ddl($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }
  
  function add_static_elements(){
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
    	$this->pdf->addText($this->layout['userposx'],$this->layout['userposy'],$this->layout['usersize'], 'Stelle: '.$this->Stelle->Bezeichnung.', Nutzer: '.$this->user->Name);
    }
    # feste Freitexte
    for($j = 0; $j < count($this->layout['texts']); $j++){
    	if($this->layout['type'] == 0 OR $this->layout['texts'][$j]['type'] == 1){		# entweder Layout ist vom seitenweisen Typ oder die Texte sind feststehend
	      $this->pdf->selectFont($this->layout['texts'][$j]['font']);
	      $freitext = explode(';', $this->layout['texts'][$j]['text']);
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
	        $this->pdf->addTextWrap($posx,$posy,$width,$this->layout['texts'][$j]['size'],$freitext[$z],$justification,-1 * $alpha);
	      }
	    }
	  }
  }
  
  function createDataPDF($attributes, $selected_layer_id, $layout, $result, $stelle, $user){
  	$this->layout = $layout;
  	$this->Stelle = $stelle;
  	$this->user = $user;
  	$this->maxy = 0;
  	$this->miny = 1000000;
  	$datasetcount_on_page = 0;
		# Einbinden der PDF Klassenbibliotheken
    include (PDFCLASSPATH."class.ezpdf.php");
    # Erzeugen neue pdf-Klasse
    $this->pdf=new Cezpdf();
    $this->add_static_elements();
    for($i = 0; $i < count($result); $i++){
    	$new_dataset = true;
    	$this->yoffset_onpage = $this->maxy - $this->miny;
    	if($this->layout['type'] == 0 AND $i > 0){		# neue Seite beim seitenweisen Typ und neuem Datensatz 
    		$this->pdf->newPage();
    		$this->add_static_elements();
    	}
	    if($this->layout['type'] == 1  AND $this->miny - $datasetcount_on_page * ($this->maxy-$this->miny+5) < 50){		# neue Seite beim Untereinander-Typ und Seitenüberlauf
				$datasetcount_on_page = 0;
				$this->pdf->newPage();
				$this->add_static_elements();
			}		
			# fortlaufende Freitexte
			if($this->layout['type'] == 1){
		    for($j = 0; $j < count($this->layout['texts']); $j++){
		    	if($this->layout['texts'][$j]['type'] == 0){		# Layout ist vom Untereinander-Typ und die Texte sind fortlaufend
			      $this->pdf->selectFont($this->layout['texts'][$j]['font']);
			      $freitext = explode(';', $this->layout['texts'][$j]['text']);
			      $anzahlzeilen = count($freitext);
			      $alpha = $this->layout['texts'][$j]['angle'];
			      for($z = 0; $z < $anzahlzeilen; $z++){
			        $h = $z * $this->layout['texts'][$j]['size'] * 1.25;
			        $a = sin(deg2rad($alpha)) * $h;
			        $b = cos(deg2rad($alpha)) * $h;
			        $posx = $this->layout['texts'][$j]['posx'] + $a;
			        $y = $this->layout['texts'][$j]['posy'] - $b;
			        # beim ersten Datensatz die Gesamthoehe der Elemente eines Datensatzes ermitteln 
		      		if($i == 0){
		      			if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
		      		}
		      		if($new_dataset){
	  						$this->miny = 10000;
	  						$new_dataset = false;
		      		}
			        if($i > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben
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
			        $this->pdf->addTextWrap($posx,$y,$width,$this->layout['texts'][$j]['size'],$freitext[$z],$justification,-1 * $alpha);
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
						switch ($attributes['form_element_type'][$j]){
							case 'Auswahlfeld' : {
								for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
									if($attributes['enum_value'][$j][$e] == $result[$i][$attributes['name'][$j]]){
										$auswahlfeld_output = $attributes['enum_output'][$j][$e];
										$auswahlfeld_output_laenge=strlen($auswahlfeld_output);
										$data = array(array($attributes['name'][$j] => $auswahlfeld_output));
										break;
									}
									else $data = array(array($attributes['name'][$j] => $result[$i][$attributes['name'][$j]]));
								}
							}break;
							default: {
								$data = array(array($attributes['name'][$j] => $result[$i][$attributes['name'][$j]]));
							}break;
						}
						# Zeilenumbruch berücksichtigen
						$text = $result[$i][$attributes['name'][$j]];
						$size = $this->layout['elements'][$attributes['name'][$j]]['fontsize'];
						$textwidth = $this->pdf->getTextWidth($size, $text);
						$width = $this->layout['elements'][$attributes['name'][$j]]['width'];
						if($width != '' AND $textwidth/$width > 1){ 
							while($text != ''){
								$text = $this->pdf->addTextWrap(-100, -100, $width, $size, $text);
								$wordwrapoffset++;
							} 
						}
						$ypos = $this->layout['elements'][$attributes['name'][$j]]['ypos'];
						$zeilenhoehe = $this->layout['elements'][$attributes['name'][$j]]['fontsize'];      		      		
	      		$y = $ypos+$zeilenhoehe+5;
	      		if($this->layout['type'] == 1 AND $i > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben
	      			$y = $y - $this->yoffset_onpage-18;
	      		}	
	      		$this->pdf->ezSetY($y);
	      		# beim jedem Datensatz die Gesamthoehe der Elemente des Datensatzes ermitteln
	      		if($i == 0){
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

	function save_layout($formvars, $attributes, $_FILES, $stelle_id){
    if($formvars['name']){
    	for($i = 0; $i< count($formvars); $i++){
    		if($formvars[key($formvars)] == NULL)$formvars[key($formvars)] = 'NULL';
    		next($formvars);
    	}
    	if($formvars['font_date'] == 'NULL')$formvars['font_date'] = NULL;
      $sql = "INSERT INTO `datendrucklayouts`";
      $sql .= " SET `name` = '".$formvars['name']."'";
      $sql .= ", `layer_id` = ".$formvars['selected_layer_id'];
  		$sql .= ", `bgposx` = ".$formvars['bgposx'];
      $sql .= ", `bgposy` = ".$formvars['bgposy'];
      $sql .= ", `bgwidth` = ".$formvars['bgwidth'];
      $sql .= ", `bgheight` = ".$formvars['bgheight'];
      $sql .= ", `dateposx` = ".$formvars['dateposx'];
      $sql .= ", `dateposy` = ".$formvars['dateposy'];
      $sql .= ", `datesize` = ".$formvars['datesize'];
      $sql .= ", `userposx` = ".$formvars['userposx'];
      $sql .= ", `userposy` = ".$formvars['userposy'];
      $sql .= ", `usersize` = ".$formvars['usersize'];
      $sql .= ", `font_date` = '".$formvars['font_date']."'";
      $sql .= ", `font_user` = '".$formvars['font_user']."'";
      $sql .= ", `type` = ".$formvars['type'];
      if($_FILES['bgsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_FILES['bgsrc']['name'];
        if (move_uploaded_file($_FILES['bgsrc']['tmp_name'],$nachDatei)) {
          $sql .= ", `bgsrc` = '".$_FILES['bgsrc']['name']."'";
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
				if($formvars['border_'.$attributes['name'][$i]] == '')$formvars['border_'.$attributes['name'][$i]] = 'NULL';
				if($formvars['width_'.$attributes['name'][$i]] == '')$formvars['width_'.$attributes['name'][$i]] = 'NULL';
				if($attributes['type'][$i] != 'geometry'){
					$sql = "REPLACE INTO ddl_elemente SET ddl_id = ".$lastddl_id;
					$sql.= " ,name = '".$attributes['name'][$i]."'";
					$sql.= " ,xpos = ".$formvars['posx_'.$attributes['name'][$i]];
					$sql.= " ,ypos = ".$formvars['posy_'.$attributes['name'][$i]];
					$sql.= " ,width = ".$formvars['width_'.$attributes['name'][$i]];
					$sql.= " ,border = ".$formvars['border_'.$attributes['name'][$i]];
					$sql.= " ,font = '".$formvars['font_'.$attributes['name'][$i]]."'";
					$sql.= " ,fontsize = ".$formvars['fontsize_'.$attributes['name'][$i]];
					#echo $sql;
	        $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
	        $this->database->execSQL($sql,4, 1);
				}
			}

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        if($formvars['text'.$i] == 'NULL')$formvars['text'.$i] = NULL;
        if($formvars['textfont'.$i] == 'NULL')$formvars['textfont'.$i] = NULL;
        $sql = "INSERT INTO druckfreitexte SET `text` = '".$formvars['text'.$i]."'";
        $sql .= ", `posx` = ".$formvars['textposx'.$i];
        $sql .= ", `posy` = ".$formvars['textposy'.$i];
        $sql .= ", `size` = ".$formvars['textsize'.$i];
        $sql .= ", `angle` = ".$formvars['textangle'.$i];
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
  
  function update_layout($formvars, $attributes, $_FILES){
    if($formvars['name']){
    	for($i = 0; $i< count($formvars); $i++){
    		if($formvars[key($formvars)] == NULL)$formvars[key($formvars)] = 'NULL';
    		next($formvars);
    	}
    	if($formvars['font_date'] == 'NULL')$formvars['font_date'] = NULL;
      $sql = "UPDATE `datendrucklayouts`";
      $sql .= " SET `name` = '".$formvars['name']."'";
      $sql .= ", `layer_id` = ".$formvars['selected_layer_id'];
  		$sql .= ", `bgposx` = ".$formvars['bgposx'];
      $sql .= ", `bgposy` = ".$formvars['bgposy'];
      $sql .= ", `bgwidth` = ".$formvars['bgwidth'];
      $sql .= ", `bgheight` = ".$formvars['bgheight'];
      $sql .= ", `dateposx` = ".$formvars['dateposx'];
      $sql .= ", `dateposy` = ".$formvars['dateposy'];
      $sql .= ", `datesize` = ".$formvars['datesize'];
      $sql .= ", `userposx` = ".$formvars['userposx'];
      $sql .= ", `userposy` = ".$formvars['userposy'];
      $sql .= ", `usersize` = ".$formvars['usersize'];
      $sql .= ", `font_date` = '".$formvars['font_date']."'";
      $sql .= ", `font_user` = '".$formvars['font_user']."'";
      $sql .= ", `type` = ".$formvars['type'];
      if($_FILES['bgsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_FILES['bgsrc']['name'];
        if (move_uploaded_file($_FILES['bgsrc']['tmp_name'],$nachDatei)) {
          $sql .= ", `bgsrc` = '".$_FILES['bgsrc']['name']."'";
        }
      }
      else{
        $sql .= ", `bgsrc` = '".$formvars['bgsrc_save']."'";
      }
      $sql .= " WHERE id = ".$formvars['aktivesLayout'];
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);
      $lastddl_id = mysql_insert_id();

			for($i = 0; $i < count($attributes['name']); $i++){
				if($formvars['border_'.$attributes['name'][$i]] == '')$formvars['border_'.$attributes['name'][$i]] = 'NULL';
				if($formvars['width_'.$attributes['name'][$i]] == '')$formvars['width_'.$attributes['name'][$i]] = 'NULL';
				if($attributes['type'][$i] != 'geometry'){
					$sql = "REPLACE INTO ddl_elemente SET ddl_id = ".$formvars['aktivesLayout'];
					$sql.= " ,name = '".$attributes['name'][$i]."'";
					$sql.= " ,xpos = ".$formvars['posx_'.$attributes['name'][$i]];
					$sql.= " ,ypos = ".$formvars['posy_'.$attributes['name'][$i]];
					$sql.= " ,width = ".$formvars['width_'.$attributes['name'][$i]];
					$sql.= " ,border = ".$formvars['border_'.$attributes['name'][$i]];
					$sql.= " ,font = '".$formvars['font_'.$attributes['name'][$i]]."'";
					$sql.= " ,fontsize = ".$formvars['fontsize_'.$attributes['name'][$i]];
					#echo $sql;
	        $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
	        $this->database->execSQL($sql,4, 1);
				}
			}
			$sql = "DELETE FROM ddl_elemente WHERE ((xpos IS NULL AND ypos IS NULL) OR (xpos = 0 AND ypos = 0) OR (xpos > 595 AND ypos > 842)) AND ddl_id = ".$formvars['aktivesLayout'];
			#echo $sql;
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        if($formvars['text'.$i] == 'NULL')$formvars['text'.$i] = NULL;
        if($formvars['textfont'.$i] == 'NULL')$formvars['textfont'.$i] = NULL;
        $sql = "UPDATE druckfreitexte SET `text` = '".$formvars['text'.$i]."'";
        $sql .= ", `posx` = ".$formvars['textposx'.$i];
        $sql .= ", `posy` = ".$formvars['textposy'.$i];
        $sql .= ", `size` = ".$formvars['textsize'.$i];
        $sql .= ", `angle` = ".$formvars['textangle'.$i];
        $sql .= ", `font` = '".$formvars['textfont'.$i]."'";
        if($formvars['texttype'.$i] == '')$formvars['texttype'.$i] = 0;
        $sql .= ", `type` = ".$formvars['texttype'.$i];
        $sql .= " WHERE id = ".$formvars['text_id'.$i];
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
    $sql = "DELETE FROM `datendrucklayouts` WHERE id = ".$formvars['selected_layout_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->delete_layout :",4);
    $this->database->execSQL($sql,4, 1);

		$sql = "DELETE FROM ddl_elemente WHERE ddl_id = ".$formvars['selected_layout_id'];					
    $this->debug->write("<p>file:kvwmap class:ddl->delete_layout :",4);
    $this->database->execSQL($sql,4, 1);

    $sql = "DELETE FROM druckfreitexte, ddl2freitexte USING druckfreitexte, ddl2freitexte WHERE ddl2freitexte.freitext_id = druckfreitexte.id AND ddl2freitexte.ddl_id = ".$formvars['selected_layout_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->delete_layout :",4);
    $this->database->execSQL($sql,4, 1);
    
    $sql = "DELETE FROM ddl2stelle WHERE ddl_id = ".$formvars['selected_layout_id'];					
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
