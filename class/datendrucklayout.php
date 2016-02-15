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
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
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
  
  function add_static_elements($offsetx, $offsety){
		# Hintergrundbild    
		if($this->layout['bgsrc']){
    	$this->pdf->addJpegFromFile(DRUCKRAHMEN_PATH.basename($this->layout['bgsrc']),$this->layout['bgposx']+$offsetx,$this->layout['bgposy']-$offsety,$this->layout['bgwidth']);
		}
    # Datum
    if($this->layout['datesize']){
    	$this->pdf->selectFont($this->layout['font_date']);
			$x = $this->layout['dateposx'];
			$y = $this->layout['dateposy'] - $offsety;
			$this->putText(date("d.m.Y"), $this->layout['datesize'], NULL, $x, $y, $offsetx);
    }
    # Nutzer
    if($this->layout['usersize']){
    	$this->pdf->selectFont($this->layout['font_user']);			
			$x = $this->layout['userposx'];
			$y = $this->layout['userposy'] - $offsety;
			$this->putText('Stelle: '.$this->Stelle->Bezeichnung.', Nutzer: '.$this->user->Name, $this->layout['usersize'], NULL, $x, $y, $offsetx);
    }
  }
	
	function add_freetexts($i, $offsetx, $offsety, $type, $pagenumber = NULL, $pagecount = NULL){
		if(count($this->remaining_freetexts) == 0)return;
    for($j = 0; $j < count($this->layout['texts']); $j++){
			if($type != 'everypage' AND $this->page_overflow_by_sublayout){
				$this->pdf->reopenObject($this->page_id_before_sublayout);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
				if($this->layout['type'] == 0)$this->page_overflow_by_sublayout = false;			# if ???							
			}
			# der Freitext wurde noch nicht geschrieben und ist entweder ein fester Freitext oder ein fortlaufender oder einer, der auf jeder Seite erscheinen soll
    	if(in_array($this->layout['texts'][$j]['id'], $this->remaining_freetexts) AND $this->layout['texts'][$j]['posy'] > 0){	# nur Freitexte mit einem y-Wert werden geschrieben
				if(($type == 'fixed' AND $this->layout['texts'][$j]['type'] != 2 AND ($this->layout['type'] == 0 OR $this->layout['texts'][$j]['type'] == 1)) 
				OR ($type == 'running' AND $this->layout['type'] != 0 AND $this->layout['texts'][$j]['type'] == 0)
				OR ($type == 'everypage' AND $this->layout['texts'][$j]['type'] == 2)){									
					$this->pdf->selectFont($this->layout['texts'][$j]['font']);								
					$x = $this->layout['texts'][$j]['posx'];
					$y = $this->layout['texts'][$j]['posy'];
					$offset_attribute = $this->layout['texts'][$j]['offset_attribute'];
					if($offset_attribute != ''){			# ist ein offset_attribute gesetzt
						$offset_value = $this->layout['offset_attributes'][$offset_attribute];
						if($offset_value != ''){																							# dieses Attribut wurde auch schon geschrieben, d.h. dessen y-Position ist bekannt -> Freitext relativ dazu setzen
							$y = $this->handlePageOverflow($offset_attribute, $offset_value, $y);		# Seitenüberläufe berücksichtigen
						}
						else{
							$remaining_freetexts[] = $this->layout['texts'][$j]['id'];
							continue;			# der Freitext ist abhängig aber das Attribut noch nicht geschrieben, Freitext merken und überspringen
						}
					}
					if($offset_attribute == '')$y = $y - $offsety;
					if($type == 'running'){	# fortlaufende Freitexte
						$pagecount = count($this->pdf->objects['3']['info']['pages']);								
						if($this->layout['type'] == 1 AND $offset_attribute == '' AND $pagecount > 1)$y = $y + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
						if($this->i_on_page == 0){
							if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
						}
						if($offset_attribute == '' AND $this->i_on_page > 0){		# bei allen darauffolgenden den y-Wert um Offset verschieben (aber nur bei absolut positionierten)
							$y = $y - $this->yoffset_onpage-$this->layout['gap'];
						}
					}
					$text = $this->substituteFreitext($this->layout['texts'][$j]['text'], $i, $pagenumber, $pagecount);
					$this->putText($text, $this->layout['texts'][$j]['size'], NULL, $x, $y, $offsetx);
				}
				else{
					$remaining_freetexts[] = $this->layout['texts'][$j]['id'];
				}
			}
			if($type != 'everypage')$this->pdf->closeObject();									# falls in eine alte Seite geschrieben wurde, zurückkehren
	  }
		return $remaining_freetexts;
	}
	
	function add_attribute_elements($selected_layer_id, $layerdb, $attributes, $oids, $offsetx, $offsety, $i, $preview){
		for($j = 0; $j < count($attributes['name']); $j++){		
			$wordwrapoffset = 1;
			if(in_array($attributes['name'][$j], $this->remaining_attributes) AND $this->layout['elements'][$attributes['name'][$j]]['ypos'] > 0){		# wenn Attribut noch nicht geschrieben wurde und einen y-Wert hat
				# da ein Attribut zu einem Seitenüberlauf führen kann, müssen davor alle festen Freitexte geschrieben werden, die geschrieben werden können
				# d.h. alle, deren Position nicht abhängig vom einem Attribut ist und alle deren Position abhängig ist und das Attribut schon geschrieben wurde
				$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, $offsety, 'fixed');			#  feste Freitexte hinzufügen
				if($attributes['type'][$j] != 'geometry'){
					switch ($attributes['form_element_type'][$j]){
						case 'SubFormPK' : case 'SubFormEmbeddedPK' : {
							if($this->layout['elements'][$attributes['name'][$j]]['font'] != ''){
								# Parameter saven ##
								$layerid_save = $selected_layer_id;
								$layoutid_save = $this->layout['id'];
								####################
								$this->gui->formvars['selected_layer_id'] = $this->gui->formvars['chosen_layer_id'] = $attributes['subform_layer_id'][$j];
								$sublayout = $this->layout['elements'][$attributes['name'][$j]]['font'];								
								$offx = $this->layout['elements'][$attributes['name'][$j]]['xpos'] + $offsetx;
								$ypos = $this->layout['elements'][$attributes['name'][$j]]['ypos'];
								
								#### relative Positionierung über Offset-Attribut ####
								$offset_attribute = $this->layout['elements'][$attributes['name'][$j]]['offset_attribute'];
								if($offset_attribute != ''){			# es ist ein offset_attribute gesetzt
									$offset_value = $this->layout[offset_attributes][$offset_attribute];
									if($offset_value != ''){		# Offset wurde auch schon bestimmt, relative y-Position berechnen
										$ypos = $this->handlePageOverflow($offset_attribute, $offset_value, $ypos);		# Seitenüberläufe berücksichtigen
									}
									else{															# Offset noch nicht da, überspringen
										# Saves wieder setzen
										$this->gui->formvars['selected_layer_id'] = $layerid_save;
										$this->gui->formvars['chosen_layer_id'] = $layerid_save;
										$this->gui->formvars['aktivesLayout'] = $layoutid_save;
										continue 2; 
									}
								}
								#### relative Positionierung über Offset-Attribut ####
								
								$pagecount = count($this->pdf->objects['3']['info']['pages']);								
								if($this->layout['type'] == 1 AND $offset_attribute == '' AND $pagecount > 1)$ypos = $ypos + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
								
								$offy = 842 - $ypos + $offsety;
								
								if($this->layout['type'] != 0 AND $offset_attribute == '' AND $this->i_on_page > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben (aber nur bei abolut positionierten)
									$offy = $offy + $this->yoffset_onpage+$this->layout['gap'];
								}	
								# beim jedem Datensatz die Gesamthoehe der Elemente des Datensatzes ermitteln
								if($this->i_on_page == 0){
									if($this->maxy < 842-$offy)$this->maxy = 842-$offy;		# beim ersten Datensatz das maxy ermitteln
								}
								
								if($preview){
									$sublayoutobject = $this->load_layouts(NULL, $sublayout, NULL, NULL);
									$y = $this->gui->sachdaten_druck_editor_preview($sublayoutobject[0], $this->pdf, $offx, $offy);
								}
								else{
									$this->gui->formvars['embedded_dataPDF'] = true;
									for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){			# die Suchparameter für die Layersuche
										$this->gui->formvars['value_'.$this->attributes['subform_pkeys'][$j][$p]] = $this->result[$i][$attributes['subform_pkeys'][$j][$p]];
										$this->gui->formvars['operator_'.$this->attributes['subform_pkeys'][$j][$p]] = '=';
									}							
									$this->gui->GenerischeSuche_Suchen();
									for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){			# die Suchparameter für die Layersuche wieder leeren
										$this->gui->formvars['value_'.$this->attributes['subform_pkeys'][$j][$p]] = '';
									}	
									$this->gui->formvars['aktivesLayout'] = $sublayout;
									$page_id_before_sublayout = $this->pdf->currentContents;
									$y = $this->gui->generischer_sachdaten_druck_drucken($this->pdf, $offx, $offy);
									$page_id_after_sublayout = $this->pdf->currentContents;
									if($page_id_before_sublayout != $page_id_after_sublayout){
										$this->page_overflow_by_sublayout = true;		# bei einem Seitenüberlauf, der durch ein Sublayout verursacht wurde, wird sich hier die vorhergehende Page-ID gemerkt
										$this->page_id_before_sublayout = $page_id_before_sublayout;
										#$this->miny_on_new_page = $y;
										#$this->miny[$this->pdf->currentContents] = $y;
									}
								}
								# den letzten y-Wert dieses Elements in das Offset-Array schreiben
								$this->layout['offset_attributes'][$attributes['name'][$j]] = $y;
								if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y)$this->miny[$this->pdf->currentContents] = $y;		# miny ist die unterste y-Position das aktuellen Datensatzes 
								
								$this->layout['page_id'][$attributes['name'][$j]] = $this->pdf->currentContents;		# und die Page-ID merken, in der das Attribut beendet wurde								
								$this->pdf->closeObject();			# falls in eine alte Seite geschrieben wurde, zurückkehren
								# Saves wieder setzen
								$this->gui->formvars['selected_layer_id'] = $layerid_save;
								$this->gui->formvars['chosen_layer_id'] = $layerid_save;
								$this->gui->formvars['aktivesLayout'] = $layoutid_save;
							}
						}break;
						
						default : {
							if($this->page_overflow_by_sublayout)$this->pdf->reopenObject($this->page_id_before_sublayout);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
							$this->pdf->selectFont($this->layout['elements'][$attributes['name'][$j]]['font']);
							if($this->layout['elements'][$attributes['name'][$j]]['fontsize'] > 0 OR $this->layout['elements'][$attributes['name'][$j]]['width'] > 0){
								$y = $this->layout['elements'][$attributes['name'][$j]]['ypos'];
								#### relative Positionierung über Offset-Attribut ####
								$offset_attribute = $this->layout['elements'][$attributes['name'][$j]]['offset_attribute'];
								if($offset_attribute != ''){			# es ist ein offset_attribute gesetzt
									$offset_value = $this->layout['offset_attributes'][$offset_attribute];
									if($offset_value != ''){		# Offset wurde auch schon bestimmt, relative y-Position berechnen
										$y = $this->handlePageOverflow($offset_attribute, $offset_value, $y);		# Seitenüberläufe berücksichtigen
									}
									else{
										#$remaining_attributes[] = $attributes['name'][$j];	# Offset wurde noch nicht bestimmt, Attribut merken und überspringen
										continue 2; 
									}
								}
								elseif($this->layout['type'] == 0 AND $this->pdf->getFirstPageId() != end($this->pdf->objects['3']['info']['pages'])+1){
									#$this->pdf->reopenObject($this->pdf->getFirstPageId());		# zurück zur ersten Seite bei seitenweisem Typ und allen absolut positionierten Attributen, wenn erforderlich
								}
								#### relative Positionierung über Offset-Attribut ####									
								
								$zeilenhoehe = $this->layout['elements'][$attributes['name'][$j]]['fontsize'];      		      		
								$x = $this->layout['elements'][$attributes['name'][$j]]['xpos'];								
								
								$pagecount = count($this->pdf->objects['3']['info']['pages']);								
								if($this->layout['type'] == 1 AND $offset_attribute == '' AND $pagecount > 1)$y = $y + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
								
								if($offset_attribute == '')$y = $y - $offsety;
								
								if($this->layout['type'] != 0 AND $offset_attribute == '' AND $this->i_on_page > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben (aber nur bei absolut positionierten)
									$y = $y - $this->yoffset_onpage-$this->layout['gap'];
								}	
								
								# beim jedem Datensatz die Gesamthoehe der Elemente des Datensatzes ermitteln
								if($this->i_on_page == 0){
									if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
								}
								
								$width = $this->layout['elements'][$attributes['name'][$j]]['width'];
								
								if($attributes['form_element_type'][$j] == 'Dokument'){
									$dokumentpfad = $this->result[$i][$this->attributes['name'][$j]];
									$pfadteil = explode('&original_name=', $dokumentpfad);
									$dateiname = $pfadteil[0];
									if($dateiname == $this->attributes['alias'][$j] AND $preview)$dateiname = WWWROOT.APPLVERSION.GRAPHICSPATH.'nogeom.png';		// als Platzhalter im Editor
									if($dateiname != '' AND file_exists($dateiname)){
										$dateinamensteil=explode('.', $pfadteil[0]);
										$new_filename = $dateinamensteil[0].'_.jpg';
										exec(IMAGEMAGICKPATH.'convert '.$dateiname.' -background white -flatten '.$new_filename);
										$size = getimagesize($new_filename);
										$ratio = $size[1]/$size[0];
										$height = $ratio*$width;
										$y = $y-$height;
										$this->pdf->addJpegFromFile($new_filename, $x, $y, $width);
										unlink($new_filename);
									}
								}
								else{
									$text = $this->get_result_value_output($i, $j, $preview);
									$y = $this->putText($text, $zeilenhoehe, $width, $x, $y, $offsetx);
								}								
																
								if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y)$this->miny[$this->pdf->currentContents] = $y;		# miny ist die unterste y-Position das aktuellen Datensatzes 
								
								$this->layout['offset_attributes'][$attributes['name'][$j]] = $y;					# den unteren y-Wert dieses Elements in das Offset-Array schreiben
								$this->layout['page_id'][$attributes['name'][$j]] = $this->pdf->currentContents;		# und die Page-ID merken, in der das Attribut beendet wurde
								$this->pdf->closeObject();									# falls in eine alte Seite geschrieben wurde, zurückkehren
							}
						}
					}
				}
				elseif($attributes['name'][$j] == $attributes['the_geom'] AND $this->layout['elements'][$attributes['name'][$j]]['xpos'] > 0){		# Geometrie
					$this->gui->map->set('width', $this->layout['elements'][$attributes['name'][$j]]['width']*MAPFACTOR);
					$this->gui->map->set('height', $this->layout['elements'][$attributes['name'][$j]]['width']*MAPFACTOR);
					if($oids[$i] != ''){
						if($attributes['geomtype'][$attributes['the_geom']] == 'POINT'){
							include_(CLASSPATH.'pointeditor.php');
							$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->gui->user->rolle->epsg_code);							
							$point = $pointeditor->getpoint($oids[$i], $attributes['table_name'][$attributes['the_geom']], $attributes['the_geom']);
							$rect = ms_newRectObj();
							if(defined('ZOOMBUFFER') AND ZOOMBUFFER > 0)$rand = ZOOMBUFFER;
							else $rand = 100;
							$rect->minx = $point['pointx']-$rand;
							$rect->maxx = $point['pointx']+$rand;
							$rect->miny = $point['pointy']-$rand;
							$rect->maxy = $point['pointy']+$rand;
						}
						else{
							include_(CLASSPATH.'polygoneditor.php');
							$polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->gui->user->rolle->epsg_code);
							$rect = $polygoneditor->zoomTopolygon($oids[$i], $attributes['table_name'][$attributes['the_geom']], $attributes['the_geom'], 10);
						}
						$this->gui->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
					}					
					if($this->gui->map->selectOutputFormat('jpeg_print') == 1){
						$this->gui->map->selectOutputFormat('jpeg');
					}
					$image_map = $this->gui->map->draw();
					$filename = $this->gui->map_saveWebImage($image_map,'jpeg');
					$newname = $this->user->id.basename($filename);
					rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
					$x = $this->layout['elements'][$attributes['name'][$j]]['xpos'] + $offsetx;
					$y = $this->layout['elements'][$attributes['name'][$j]]['ypos'] - $offsety;
					if($this->i_on_page == 0){
						if($this->maxy < $y+$this->layout['elements'][$attributes['name'][$j]]['width'])$this->maxy = $y+$this->layout['elements'][$attributes['name'][$j]]['width'];		# beim ersten Datensatz das maxy ermitteln
					}    
					if($this->layout['type'] != 0 AND $this->i_on_page > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben
						$y = $y - $this->yoffset_onpage-$this->layout['gap'];
					}
					$this->pdf->addJpegFromFile(IMAGEPATH.$newname, $x, $y, $this->layout['elements'][$attributes['name'][$j]]['width']);
					# Rechteck um die Karte
					$this->pdf->rectangle($x, $y, $this->layout['elements'][$attributes['name'][$j]]['width'], $this->layout['elements'][$attributes['name'][$j]]['width']);
					if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y)$this->miny[$this->pdf->currentContents] = $y;
				}
				unset($this->remaining_attributes[$attributes['name'][$j]]);		# das Attribut aus den remaining_attributes entfernen
			}
		}
	}
	
	function handlePageOverflow($offset_attribute, $offset_value, $ypos){
		#if($this->layout['page_id'][$offset_attribute] != end($this->pdf->ezPages)){
		if($this->layout['page_id'][$offset_attribute] != $this->pdf->currentContents){
			$backto_oldpage = true;															# das Offset-Attribut wurde auf einer anderen Seite beendet -> zu dieser Seite zurückkehren
		}
		if($offset_value - $ypos < 40){	# Seitenüberlauf
			$offset_value = 842 + $offset_value - 40 - 30;	# Offsetwert so anpassen, dass er für die neue Seite passt
			if($backto_oldpage){
				$this->pdf->reopenObject($this->getNextPage($this->layout['page_id'][$offset_attribute]));		# die nächste Seite der Seite des Offset-Attributes nehmen
			}
			else{
				$this->pdf->ezNewPage();			# eine neue Seite beginnen
				$this->miny[$this->pdf->currentContents] = 900;
			}
		}
		elseif($backto_oldpage){
			$this->pdf->reopenObject($this->layout['page_id'][$offset_attribute]);		# die Seite des Offset-Attributes nehmen
		}
		$ypos = $offset_value - $ypos;
		return $ypos;
	}
	
	function getNextPage($pageid){
		$pages = $this->pdf->objects['3']['info']['pages'];
		for($i = 0; $i <= count($pages); $i++){
			if($pages[$i]+1 == $pageid){			# die Page-IDs sind komischerweise alle um 1 größer
				return $pages[$i+1]+1;
			}
		}
	}
	
	function putText($text, $fontsize, $width, $x, $y, $offsetx){	
		if($x < 0){		# rechtsbündig
			$x = 595 + $x;
			$x = $x + $offsetx;
			$options = array('aright'=>$x, 'justification'=>'right');
		}
		else{							# linksbündig
			$x = $x + $offsetx;
			if($width != '')$right = 595 - $width - $x + 20;
			else $right = NULL;
			$options = array('aleft'=>$x, 'right'=>$right, 'justification'=>'full');
		}
		$fh = $this->pdf->getFontHeight($fontsize);
		$y = $y + $fh;
		$this->pdf->ezSetY($y);		
		$page_id_before_puttext = $this->pdf->currentContents;
		$ret = $this->pdf->ezText(iconv("UTF-8", "CP1252", $text), $fontsize, $options);
		$page_id_after_puttext = $this->pdf->currentContents;
		if($page_id_before_puttext != $page_id_after_puttext){
			$this->page_overflow_by_sublayout = true;
			$this->page_id_before_sublayout = $page_id_before_puttext;
		}
		return $ret;
	}
  
  function substituteFreitext($text, $i, $pagenumber, $pagecount){
  	$text = str_replace('$stelle', $this->Stelle->Bezeichnung, $text);
  	$text = str_replace('$user', $this->user->Name, $text);
		$text = str_replace('$pagenumber', $pagenumber, $text);
		$text = str_replace('$pagecount', $pagecount, $text);		
		$text = str_replace(';', chr(10), $text);
		for($j = 0; $j < count($this->attributes['name']); $j++){
			$text = str_replace('$'.$this->attributes['name'][$j], $this->get_result_value_output($i, $j, true), $text);
		}
  	return $text;
  }
  
  function get_result_value_output($i, $j, $preview){		# $i ist der result-counter, $j ist der attribute-counter
		if($this->result[$i][$this->attributes['name'][$j]] == '')$this->result[$i][$this->attributes['name'][$j]] = ' ';		# wenns der result-value leer ist, ein Leerzeichen setzen, wegen der relativen Positionierung
		switch ($this->attributes['form_element_type'][$j]){
			case 'Auswahlfeld' : {
				if(is_array($this->attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
					for($e = 0; $e < count($this->attributes['enum_value'][$j][$i]); $e++){
						if($this->attributes['enum_value'][$j][$i][$e] == $this->result[$i][$this->attributes['name'][$j]]){
							$output = $this->attributes['enum_output'][$j][$i][$e];
							break;
						}
						else $output = $this->result[$i][$this->attributes['name'][$j]];
					}
				}
				else{
					for($e = 0; $e < count($this->attributes['enum_value'][$j]); $e++){
						if($this->attributes['enum_value'][$j][$e] == $this->result[$i][$this->attributes['name'][$j]]){
							$output = $this->attributes['enum_output'][$j][$e];
							break;
						}
						else $output = $this->result[$i][$this->attributes['name'][$j]];
					}
				}
				if(count($this->attributes['enum_value'][$j]) == 0){	
					$output = $this->result[$i][$this->attributes['name'][$j]];
				}			
			}break;
			case 'Autovervollständigungsfeld' : {
				if(count($this->attributes['enum_output'][$j]) == 0){	
					$output = $this->result[$i][$this->attributes['name'][$j]];		# preview
				}	
				else $output = $this->attributes['enum_output'][$j][$i];
			}break;
			default: {
				if(!$preview AND $this->attributes['type'][$j] == 'bool'){
					$this->result[$i][$this->attributes['name'][$j]] = str_replace('t', "ja", $this->result[$i][$this->attributes['name'][$j]]);	
					$this->result[$i][$this->attributes['name'][$j]] = str_replace('f', "nein", $this->result[$i][$this->attributes['name'][$j]]);
				}
				$output = $this->result[$i][$this->attributes['name'][$j]];
			}break;
		}
		return $output;
  }
  
  function createDataPDF($pdfobject, $offsetx, $offsety, $layerdb, $layerset, $attributes, $selected_layer_id, $layout, $oids, $result, $stelle, $user, $preview = NULL){
		# Für einen ausgewählten Layer wird das übergebene Result-Set nach den Vorgaben des übergebenen Layouts in ein PDF geschrieben
		# Werden $pdfobject, $offsetx und $offsety übergeben, wird kein neues PDF-Objekt erzeugt, sondern das übergebene PDF-Objekt eines übergeordneten Layers+Layout verwendet (eingebettete Layouts)
  	$this->layout = $layout;
  	$this->Stelle = $stelle;
		$this->attributes = $attributes;
		$this->result = $result;
  	$this->user = $user;
  	$this->maxy = 0;
  	#$this->miny = 1000000;
  	$this->i_on_page = -1;
  	$this->datasetcount_on_page = 0;
		$this->page_overflow_by_sublayout = false;
		if($pdfobject == NULL){
			include (PDFCLASSPATH."class.ezpdf.php");				# Einbinden der PDF Klassenbibliotheken
			$this->pdf=new Cezpdf();			# neues PDF-Objekt erzeugen
		}
		else{
			$this->pdf = $pdfobject;			# ein PDF-Objekt wurde aus einem übergeordneten Druckrahmen/Layer übergeben
		}
		$this->miny[$this->pdf->currentContents] = 1000000;
		if($offsety)$this->miny[$this->pdf->currentContents] = 842 - $offsety;
		$this->pdf->ezSetMargins(40,30,0,0);
    if($this->layout['elements'][$attributes['the_geom']]['xpos'] > 0){		# wenn ein Geometriebild angezeigt werden soll -> loadmap()
    	$this->gui->map_factor = MAPFACTOR;
    	$this->gui->loadmap('DataBase');
    }
		$this->add_static_elements($offsetx, $offsety);
    for($i = 0; $i < count($result); $i++){
			$lastpage = end($this->pdf->objects['3']['info']['pages'])+1;
    	$this->i_on_page++;
			if($this->page_overflow_by_sublayout != false){
				$this->page_overflow_by_sublayout = false;						
				#$this->miny = $this->miny_on_new_page; 
				if(!$this->initial_yoffset)$this->initial_yoffset = 780-$this->maxy;			# der Offset von oben gesehen, mit dem das erste fortlaufende Element auf der ersten Seite beginnt; wird benutzt, um die fortlaufenden Elemente ab der 2. Seite oben beginnen zu lassen
				$this->datasetcount_on_page = 0; # ??
				$this->i_on_page = 1;	# ??
				$this->maxy = 800;
				if($this->layout['type'] == 2)$offsety = 50;		# das ist für den Fall, dass ein Sublayout in einem Sublayout einen Seitenüberlauf verursacht hat (hier muss eigentlich der Offset der nächsten Seite rein)
			}
    	if($this->layout['type'] == 0 AND $i > 0){		# neue Seite beim seitenweisen Typ und neuem Datensatz 
    		$this->pdf->newPage();
				$this->add_static_elements($offsetx, $offsety);
    	}	
	    if($this->datasetcount_on_page > 0 AND $this->layout['type'] != 0 AND $this->miny[$lastpage] < $this->yoffset_onpage/$this->datasetcount_on_page + 70){		# neue Seite beim Untereinander-Typ oder eingebettet-Typ und Seitenüberlauf
				$this->datasetcount_on_page = 0;
				$this->i_on_page = 0;
				#$this->maxy = 0;
				if(!$this->initial_yoffset)$this->initial_yoffset = 780-$this->maxy;			# der Offset von oben gesehen, mit dem das erste fortlaufende Element auf der ersten Seite beginnt; wird benutzt, um die fortlaufenden Elemente ab der 2. Seite oben beginnen zu lassen
				if($this->layout['type'] == 2)$offsety = 50; else $offsety = 0;
				$this->pdf->newPage();
				$this->miny[$lastpage] = 1000000;
				#$this->add_static_elements($offsetx, $offsety);
			}
			$this->yoffset_onpage = $this->maxy - $this->miny[$lastpage];			# der Offset mit dem die Elemente beim Untereinander-Typ nach unten versetzt werden
			$this->layout['offset_attributes'] = array();
			
			for($j = 0; $j < count($this->layout['texts']); $j++){
				if($i == 0 OR $this->layout['type'] != 1 OR $this->layout['texts'][$j]['type'] != 1){
					$this->remaining_freetexts[] = $this->layout['texts'][$j]['id'];		# zu Beginn jedes Datensatzes sind alle Freitexte noch zu schreiben, bei fortlaufenden Layouts aber nur die fortlaufenden Freitexte
				}
			}
			################# Daten schreiben ###############
			for($j = 0; $j < count($this->attributes['name']); $j++){
				if($this->layout['elements'][$attributes['name'][$j]]['ypos'] > 0){
					$this->remaining_attributes[$this->attributes['name'][$j]] = $this->attributes['name'][$j];		# zum Anfang sind alle Attribute noch zu schreiben
				}
			}
			$test = 0;			
			while($test < 100 AND count($this->remaining_attributes) > 0){
				$this->add_attribute_elements($selected_layer_id, $layerdb, $this->attributes, $oids, $offsetx, $offsety, $i, $preview);	# übrig sind die, die noch nicht geschrieben wurden, weil sie abhängig sind
				$test++;
			}			
			################# Daten schreiben ###############
			
			################# fortlaufende Freitexte schreiben ###############
			# (die festen Freitexte werden vor jedem Attribut geschrieben, da ein Attribut zu einem Seitenüberlauf führen können)
			$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, $offsety, 'running');
			################# fortlaufende Freitexte schreiben ###############
      $this->datasetcount_on_page++;
    }
		if($pdfobject == NULL){		# nur wenn kein PDF-Objekt aus einem übergeordneten Layer übergeben wurde, PDF erzeugen
			# Freitexte hinzufügen, die auf jeder Seite erscheinen sollen (Seitennummerierung etc.)
			$this->add_everypage_elements();
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
		else{
			return $this->miny[$this->pdf->currentContents];		# der letzte y-Wert wird zurückgeliefert, um nachfolgende Elemente darunter zu setzen
		}
	}
	
	function add_everypage_elements(){
		$this->pdf->ezSetMargins(0,0,0,0);
		$pages = $this->pdf->objects['3']['info']['pages'];
		$pagecount = count($pages);
		for($i = 0; $i < $pagecount; $i++){
			$this->pdf->reopenObject($pages[$i]+1);		# die Page-IDs sind komischerweise alle um 1 größer
			$this->add_freetexts(0, 0, 0, 'everypage', $i + 1, $pagecount);
			$this->pdf->closeObject();
		}
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
			$sql .= ", `gap` = ".(int)$formvars['gap'];
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
				if($formvars['offset_attribute_'.$attributes['name'][$i]])$sql.= " ,offset_attribute = '".$formvars['offset_attribute_'.$attributes['name'][$i]]."'";
				else $sql.= " ,offset_attribute = NULL";
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
        if($formvars['textposx'.$i] !== NULL)$sql .= ", `posx` = ".(int)$formvars['textposx'.$i];
        else $sql .= ", `posx` = 0";
        if($formvars['textposy'.$i] !== NULL)$sql .= ", `posy` = ".(int)$formvars['textposy'.$i];
        else $sql .= ", `posy` = 0";
				if($formvars['textoffset_attribute'.$i])$sql .= ", `offset_attribute` = '".$formvars['textoffset_attribute'.$i]."'";
        else $sql .= ", `offset_attribute` = NULL";
        if($formvars['textsize'.$i] !== NULL)$sql .= ", `size` = ".(int)$formvars['textsize'.$i];
        else $sql .= ", `size` = 0";
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
			$sql .= ", `gap` = ".(int)$formvars['gap'];
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
				if($formvars['offset_attribute_'.$attributes['name'][$i]])$sql.= " ,offset_attribute = '".$formvars['offset_attribute_'.$attributes['name'][$i]]."'";
				else $sql.= " ,offset_attribute = NULL";
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
				if($formvars['textoffset_attribute'.$i])$sql .= ", `offset_attribute` = '".$formvars['textoffset_attribute'.$i]."'";
        else $sql .= ", `offset_attribute` = NULL";
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
 	
 	function load_layouts($stelle_id, $ddl_id, $layer_id, $types){
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
		if($types != NULL){
			$sql .= ' AND type IN ('.implode(',', $types).')';
		}
    $sql .= ' ORDER BY layer_id, name';
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
		$i = $formvars['textcount'] - 1;
		if($formvars['textsize'.$i] != '')$size = $formvars['textsize'.$i];	else $size = 11;
		if($formvars['textfont'.$i] != '')$font = $formvars['textfont'.$i];
		if($formvars['textposx'.$i] != '')$posx = $formvars['textposx'.$i]; else $posx = 70;
		if($formvars['textposy'.$i] != '')$posy = $formvars['textposy'.$i]-20; else $posy = 0;
    $sql = 'INSERT INTO druckfreitexte SET';
    $sql .= ' text = "",';
    $sql .= ' posx = '.$posx.',';
    $sql .= ' posy = '.$posy.',';
    $sql .= ' size = '.$size.',';
    $sql .= ' font = "'.$font.'",';
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
	
	function removelayouts($stelleid){
		$sql ="DELETE FROM ddl2stelle WHERE stelle_id = ".$stelleid;
    $this->debug->write("<p>file:kvwmap class:ddl->removelayouts :",4);
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
