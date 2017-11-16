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
  
  function add_static_elements($offsetx){
		$offsety = $this->offsety;
		# Hintergrundbild    
		if($this->layout['bgsrc']){
    	$this->pdf->addJpegFromFile(DRUCKRAHMEN_PATH.basename($this->layout['bgsrc']),$this->layout['bgposx']+$offsetx,$this->layout['bgposy']-$offsety,$this->layout['bgwidth']);
		}
    # Datum
    if($this->layout['datesize']){
    	$this->pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/' . $this->layout['font_date']);
			$x = $this->layout['dateposx'];
			$y = $this->layout['dateposy'] - $offsety;
			$this->putText(date("d.m.Y"), $this->layout['datesize'], NULL, $x, $y, $offsetx);
    }
    # Nutzer
    if($this->layout['usersize']){
    	$this->pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/' . $this->layout['font_user']);			
			$x = $this->layout['userposx'];
			$y = $this->layout['userposy'] - $offsety;
			$this->putText('Stelle: '.$this->Stelle->Bezeichnung.', Nutzer: '.$this->user->Name, $this->layout['usersize'], NULL, $x, $y, $offsetx);
    }
  }
	
	function add_freetexts($i, $offsetx, $type, $pagenumber = NULL, $pagecount = NULL){
		if(count($this->remaining_freetexts) == 0)return;
    for($j = 0; $j < count($this->layout['texts']); $j++){
			if($type != 'everypage' AND $this->page_overflow){
				$this->pdf->reopenObject($this->page_id_before_sublayout);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
				#if($this->layout['type'] == 0)$this->page_overflow = false;			# if ???							wegen dem Geometrie-Attribut auskommentiert
			}
			# der Freitext wurde noch nicht geschrieben und ist entweder ein fester Freitext oder ein fortlaufender oder einer, der auf jeder Seite erscheinen soll
    	if(in_array($this->layout['texts'][$j]['id'], $this->remaining_freetexts) AND $this->layout['texts'][$j]['posy'] != ''){	# nur Freitexte mit einem y-Wert werden geschrieben
				if(($type == 'fixed' AND $this->layout['texts'][$j]['type'] != 2 AND ($this->layout['type'] == 0 OR $this->layout['texts'][$j]['type'] == 1)) 
				OR ($type == 'running' AND $this->layout['type'] != 0 AND $this->layout['texts'][$j]['type'] == 0)
				OR ($type == 'everypage' AND $this->layout['texts'][$j]['type'] == 2)){									
					$this->pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/' . $this->layout['texts'][$j]['font']);								
					$x = $this->layout['texts'][$j]['posx'];
					$y = $this->layout['texts'][$j]['posy'];
					$offset_attribute = $this->layout['texts'][$j]['offset_attribute'];
					if($offset_attribute != ''){			# ist ein offset_attribute gesetzt
						$offset_attributes = explode(',', $offset_attribute);		# es können mehrere Offset-Attribute durch Komma getrennt angegeben sein (ist aber noch nicht in der Oberfläche umgesetzt)
						$smallest_offset_value = 10000;
						foreach($offset_attributes as $offset_attribute){
							$offset_value = $this->layout['offset_attributes'][$offset_attribute];
							if($offset_value == ''){		# wenn eines der Offset-Attribute noch keinen Wert hat, also noch nicht geschrieben wurde, abbrechen
								$smallest_offset_value = '';
								break;
							}
							if($offset_value < $smallest_offset_value)$smallest_offset_value = $offset_value;
						}
						if($smallest_offset_value != ''){																							# dieses Attribut wurde auch schon geschrieben, d.h. dessen y-Position ist bekannt -> Freitext relativ dazu setzen
							$y = $this->handlePageOverflow($offset_attribute, $smallest_offset_value, $y);		# Seitenüberläufe berücksichtigen
						}
						else{
							$remaining_freetexts[] = $this->layout['texts'][$j]['id'];
							continue;			# der Freitext ist abhängig aber das Attribut noch nicht geschrieben, Freitext merken und überspringen
						}
					}
					if($offset_attribute == '')$y = $y - $this->offsety;
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
					$y = $this->putText($text, $this->layout['texts'][$j]['size'], NULL, $x, $y, $offsetx);
					if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y)$this->miny[$this->pdf->currentContents] = $y;		# miny ist die unterste y-Position das aktuellen Datensatzes 
				}
				else{
					$remaining_freetexts[] = $this->layout['texts'][$j]['id'];
				}
			}
			if($type != 'everypage' AND $this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages'])+1)$this->pdf->closeObject();			# falls in eine alte Seite geschrieben wurde, zurückkehren
	  }
		return $remaining_freetexts;
	}
	
	function add_lines($offsetx, $type){
		if(count($this->remaining_lines) == 0)return;
    for($j = 0; $j < count($this->layout['lines']); $j++){
			if($type != 'everypage' AND $this->page_overflow){
				$this->pdf->reopenObject($this->page_id_before_sublayout);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
				if($this->layout['type'] == 0)$this->page_overflow = false;			# if ???							
			}
			# die Linie wurde noch nicht geschrieben und ist entweder eine feste Linie oder eine fortlaufende oder eine, der auf jeder Seite erscheinen soll
    	if(in_array($this->layout['lines'][$j]['id'], $this->remaining_lines) AND $this->layout['lines'][$j]['posy'] != ''){	# nur Linien mit einem y-Wert werden geschrieben
				if(($type == 'fixed' AND $this->layout['lines'][$j]['type'] != 2 AND ($this->layout['type'] == 0 OR $this->layout['lines'][$j]['type'] == 1)) 
				OR ($type == 'running' AND $this->layout['type'] != 0 AND $this->layout['lines'][$j]['type'] == 0)
				OR ($type == 'everypage' AND $this->layout['lines'][$j]['type'] == 2)){							
					$x = $this->layout['lines'][$j]['posx'] + $offsetx;
					$y_orig = $y = $this->layout['lines'][$j]['posy'];
					$endx = $this->layout['lines'][$j]['endposx'] + $offsetx;
					$endy = $this->layout['lines'][$j]['endposy'];
					$offset_attribute = $this->layout['lines'][$j]['offset_attribute'];
					if($offset_attribute != ''){			# ist ein offset_attribute gesetzt
						$offset_attributes = explode(',', $offset_attribute);		# es können mehrere Offset-Attribute durch Komma getrennt angegeben sein (ist aber noch nicht in der Oberfläche umgesetzt)
						$smallest_offset_value = 10000;
						foreach($offset_attributes as $offset_attribute){
							$offset_value = $this->layout['offset_attributes'][$offset_attribute];
							if($offset_value == ''){		# wenn eines der Offset-Attribute noch keinen Wert hat, also noch nicht geschrieben wurde, abbrechen
								$smallest_offset_value = '';
								break;
							}
							if($offset_value < $smallest_offset_value)$smallest_offset_value = $offset_value;
						}
						if($smallest_offset_value != ''){																							# dieses Attribut wurde auch schon geschrieben, d.h. dessen y-Position ist bekannt -> Linie relativ dazu setzen
							$y = $this->handlePageOverflow($offset_attribute, $smallest_offset_value, $y);		# Seitenüberläufe berücksichtigen
							$endy = $this->handlePageOverflow($offset_attribute, $smallest_offset_value, $endy);		# Seitenüberläufe berücksichtigen
						}
						else{
							$remaining_lines[] = $this->layout['lines'][$j]['id'];
							continue;			# die Linie ist abhängig aber das Attribut noch nicht geschrieben, Linie merken und überspringen
						}
					}
					if($offset_attribute == ''){
						$y = $y - $this->offsety;
						$endy = $endy - ($y_orig - $y);		# y-Endposition auch anpassen
					}
					if($type == 'running'){	# fortlaufende Linien
						$pagecount = count($this->pdf->objects['3']['info']['pages']);								
						if($this->layout['type'] == 1 AND $offset_attribute == '' AND $pagecount > 1){
							$y = $y + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
							$endy = $endy + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
						}
						if($this->i_on_page == 0){
							#if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
							#if($this->maxy < $endy)$this->maxy = $endy;		# beim ersten Datensatz das maxy ermitteln							
						}						
						if($offset_attribute == '' AND $this->i_on_page > 0){		# bei allen darauffolgenden den y-Wert um Offset verschieben (aber nur bei absolut positionierten)
							$y = $y - $this->yoffset_onpage-$this->layout['gap'];
							$endy = $endy - $this->yoffset_onpage-$this->layout['gap'];
						}
					}
					$this->pdf->setLineStyle($this->layout['lines'][$j]['breite']);
					$this->pdf->line($x,$y,$endx,$endy);
					if($this->layout['lines'][$j]['type'] === 0){
						#if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y)$this->miny[$this->pdf->currentContents] = $y;		# miny ist die unterste y-Position das aktuellen Datensatzes 
						#if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $endy)$this->miny[$this->pdf->currentContents] = $endy;		# miny ist die unterste y-Position das aktuellen Datensatzes 
					}
				}
				else{
					$remaining_lines[] = $this->layout['lines'][$j]['id'];
				}
			}
			if($type != 'everypage' AND $this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages'])+1)$this->pdf->closeObject();			# falls in eine alte Seite geschrieben wurde, zurückkehren
	  }
		return $remaining_lines;
	}	
	
	function add_attribute_elements($selected_layer_id, $layerdb, $attributes, $oids, $offsetx, $i, $preview){
		for($j = 0; $j < count($attributes['name']); $j++){		
			$wordwrapoffset = 1;
			if(in_array($attributes['name'][$j], $this->remaining_attributes) AND $this->layout['elements'][$attributes['name'][$j]]['ypos'] > 0){		# wenn Attribut noch nicht geschrieben wurde und einen y-Wert hat
				# da ein Attribut zu einem Seitenüberlauf führen kann, müssen davor alle festen Freitexte geschrieben werden, die geschrieben werden können
				# d.h. alle, deren Position nicht abhängig vom einem Attribut ist und alle deren Position abhängig ist und das Attribut schon geschrieben wurde
				$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, 'fixed');			#  feste Freitexte hinzufügen
				$this->remaining_lines = $this->add_lines($offsetx, 'fixed');			# feste Linien hinzufügen
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
								
								$offy = 842 - $ypos + $this->offsety;
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
									for($p = 0; $p < count($attributes['name']); $p++){			# erstmal alle Suchparameter des übergeordneten Layers für die Layersuche leeren
										$this->gui->formvars['value_'.$attributes['name'][$p]] = '';
										$this->gui->formvars['operator_'.$attributes['name'][$p]] = '';
									}
									$this->gui->formvars['value_'.$this->layerset['maintable'].'_oid'] = '';
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
										$this->page_overflow = true;		# bei einem Seitenüberlauf, der durch ein Sublayout verursacht wurde, wird sich hier die vorhergehende Page-ID gemerkt
										$this->page_id_before_sublayout = $page_id_before_sublayout;
										#$this->miny_on_new_page = $y;
										#$this->miny[$this->pdf->currentContents] = $y;
									}
								}
								# den letzten y-Wert dieses Elements in das Offset-Array schreiben
								$this->layout['offset_attributes'][$attributes['name'][$j]] = $y;
								if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y){
									if(($this->miny[$this->pdf->currentContents] - $y) > $this->max_dataset_height)$this->max_dataset_height = $this->miny[$this->pdf->currentContents] - $y;
									$this->miny[$this->pdf->currentContents] = $y;		# miny ist die unterste y-Position das aktuellen Datensatzes									
								}
								
								$this->layout['page_id'][$attributes['name'][$j]] = $this->pdf->currentContents;		# und die Page-ID merken, in der das Attribut beendet wurde								
								if($this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages'])+1)$this->pdf->closeObject();									# falls in eine alte Seite geschrieben wurde, zurückkehren
								# Saves wieder setzen
								$this->gui->formvars['selected_layer_id'] = $layerid_save;
								$this->gui->formvars['chosen_layer_id'] = $layerid_save;
								$this->gui->formvars['aktivesLayout'] = $layoutid_save;
							}
						}break;
						
						default : {
							if($this->page_overflow)$this->pdf->reopenObject($this->page_id_before_sublayout);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
							$this->pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/' . $this->layout['elements'][$attributes['name'][$j]]['font']);
							if($this->layout['elements'][$attributes['name'][$j]]['fontsize'] > 0 OR $attributes['form_element_type'][$j] == 'Dokument'){
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
								if($offset_attribute == '')$y = $y - $this->offsety;
								if($this->layout['type'] != 0 AND $offset_attribute == '' AND $this->i_on_page > 0){		# beim Untereinander-Typ y-Wert um Offset verschieben (aber nur bei absolut positionierten)
									$y = $y - $this->yoffset_onpage-$this->layout['gap'];
								}			
								# beim jedem Datensatz die Gesamthoehe der Elemente des Datensatzes ermitteln
								if($this->i_on_page == 0){
									if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
								}
								
								$width = $this->layout['elements'][$attributes['name'][$j]]['width'];
								
								if($attributes['form_element_type'][$j] == 'Dokument'){
									if($width == '')$width = 50;
									$dokumentpfad = $this->result[$i][$this->attributes['name'][$j]];
									$pfadteil = explode('&original_name=', $dokumentpfad);
									$dateiname = $pfadteil[0];
									if($dateiname == $this->attributes['alias'][$j] AND $preview)$dateiname = WWWROOT.APPLVERSION.GRAPHICSPATH.'nogeom.png';		// als Platzhalter im Editor
									if($dateiname != '' AND file_exists($dateiname)){
										$dateinamensteil=explode('.', $dateiname);
										if(in_array(strtolower($dateinamensteil[1]), array('jpg', 'png', 'gif', 'tif', 'pdf'))){
											$new_filename = IMAGEPATH.basename($dateinamensteil[0]).'.jpg';
											exec(IMAGEMAGICKPATH.'convert '.$dateiname.' -background white -flatten '.$new_filename);
											$size = getimagesize($new_filename);
											$ratio = $size[1]/$size[0];
											$height = $ratio*$width;
											$y = $y-$height;
											$this->pdf->addJpegFromFile($new_filename, $x, $y, $width);
										}
									}
								}
								else{
									$text = $this->get_result_value_output($i, $j, $preview);
									$y = $this->putText($text, $zeilenhoehe, $width, $x, $y, $offsetx);
								}								
																
								if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y){
									if(($this->miny[$this->pdf->currentContents] - $y) > $this->max_dataset_height)$this->max_dataset_height = $this->miny[$this->pdf->currentContents] - $y;
									$this->miny[$this->pdf->currentContents] = $y;		# miny ist die unterste y-Position das aktuellen Datensatzes									
								}
								
								$this->layout['offset_attributes'][$attributes['name'][$j]] = $y;					# den unteren y-Wert dieses Elements in das Offset-Array schreiben
								$this->layout['page_id'][$attributes['name'][$j]] = $this->pdf->currentContents;		# und die Page-ID merken, in der das Attribut beendet wurde
								if($this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages'])+1)$this->pdf->closeObject();									# falls in eine alte Seite geschrieben wurde, zurückkehren
							}
						}
					}
				}
				elseif($attributes['name'][$j] == $attributes['the_geom'] AND $this->layout['elements'][$attributes['name'][$j]]['xpos'] > 0){		# Geometrie
					if($this->page_overflow)$this->pdf->reopenObject($this->page_id_before_sublayout);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
					$this->gui->map->set('width', $this->layout['elements'][$attributes['name'][$j]]['width']*MAPFACTOR);
					$this->gui->map->set('height', $this->layout['elements'][$attributes['name'][$j]]['width']*MAPFACTOR);
					if($oids[$i] != ''){
						if($this->layout['elements'][$attributes['name'][$j]]['fontsize'] > 0)$rand = $this->layout['elements'][$attributes['name'][$j]]['fontsize'];		# bei Geometrie-Attributen wird in fontsize der Zoom-Rand gespeichert
						elseif(defined('ZOOMBUFFER') AND ZOOMBUFFER > 0)$rand = ZOOMBUFFER;
						else $rand = 100;
						if($attributes['geomtype'][$attributes['the_geom']] == 'POINT'){
							include_(CLASSPATH.'pointeditor.php');
							$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->gui->user->rolle->epsg_code);							
							$point = $pointeditor->getpoint($oids[$i], $attributes['table_name'][$attributes['the_geom']], $attributes['the_geom']);
							$rect = ms_newRectObj();
							$rect->minx = $point['pointx']-$rand;
							$rect->maxx = $point['pointx']+$rand;
							$rect->miny = $point['pointy']-$rand;
							$rect->maxy = $point['pointy']+$rand;
						}
						else{
							include_(CLASSPATH.'polygoneditor.php');
							$polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->gui->user->rolle->epsg_code);
							$rect = $polygoneditor->zoomTopolygon($oids[$i], $attributes['table_name'][$attributes['the_geom']], $attributes['the_geom'], $rand);
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
					$y = $this->layout['elements'][$attributes['name'][$j]]['ypos'] - $this->offsety;
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
		if($this->layout['page_id'][$offset_attribute] != $this->pdf->currentContents){
			$backto_oldpage = true;															# das Offset-Attribut wurde auf einer anderen Seite beendet -> zu dieser Seite zurückkehren
		}
		if($offset_value - $ypos < 40){	# Seitenüberlauf
			$offset_value = 842 + $offset_value - 40 - 30;	# Offsetwert so anpassen, dass er für die neue Seite passt
			$next_page = $this->getNextPage($this->layout['page_id'][$offset_attribute]);
			if($next_page != NULL){
				$this->pdf->reopenObject($next_page);		# die nächste Seite der Seite des Offset-Attributes nehmen
			}
			else{																			# wenns noch keine gibt, neue Seite erstellen
				$page_id_before = $this->pdf->currentContents;
				$this->pdf->ezNewPage();			# eine neue Seite beginnen
				$this->miny[$this->pdf->currentContents] = 842;
				$this->maxy = 800;
				if($this->layout['type'] == 2)$this->offsety = 50;
				$this->page_overflow = true;
				$this->page_id_before_sublayout = $page_id_before;
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
			if($pages[$i]+1 == $pageid AND $pages[$i+1] != ''){			# die Page-IDs sind komischerweise alle um 1 größer
				return $pages[$i+1]+1;
			}
		}
		return NULL;
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
		$ret = $this->pdf->ezText(iconv("UTF-8", "CP1252//TRANSLIT", $text), $fontsize, $options);
		$page_id_after_puttext = $this->pdf->currentContents;		
		#echo $page_id_before_puttext.' '.$page_id_after_puttext.' - '.$y.' - '.$text.'<br>';
		if($page_id_before_puttext != $page_id_after_puttext){
			$this->page_overflow = true;
			$this->page_id_before_sublayout = $page_id_before_puttext;
			if($this->getNextPage($page_id_before_puttext) != $page_id_after_puttext)$this->pdf->overflow_error = true;		# eine oder mehr Seiten übersprungen -> Fehler
		}
		return $ret;
	}
  
  function substituteFreitext($text, $i, $pagenumber, $pagecount){
  	$text = str_replace('$stelle', $this->Stelle->Bezeichnung, $text);
  	$text = str_replace('$user', $this->user->Name, $text);
		$text = str_replace('$pagenumber', $pagenumber, $text);
		$text = str_replace('$pagecount', $pagecount, $text);		
		$text = str_replace(';', chr(10), $text);
		if(strpos($text, '${') !== false){
			for($j = 0; $j < count($this->attributes['name']); $j++){
				$text = str_replace('${'.$this->attributes['name'][$j].'}', $this->get_result_value_output($i, $j, true), $text);
			}
		}
		if(strpos($text, '$') !== false){
			for($j = 0; $j < count($this->attributes['name']); $j++){
				$text = str_replace('$'.$this->attributes['name'][$j], $this->get_result_value_output($i, $j, true), $text);
			}
		}
  	return $text;
  }
  
  function get_result_value_output($i, $j, $preview){
				# $i ist der result-counter, $j ist der attribute-counter
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
		$this->layerset = $layerset[0];
  	$this->layout = $layout;
  	$this->Stelle = $stelle;
		$this->attributes = $attributes;
		$this->result = $result;
  	$this->user = $user;
  	$this->maxy = 0;
		$this->offsety = $offsety;
  	#$this->miny = 1000000;
  	$this->i_on_page = -1;
		$this->page_overflow = false;
		if($pdfobject == NULL){
			include (CLASSPATH . 'class.ezpdf.php');
			$this->pdf=new Cezpdf();
		}
		else{
			$this->pdf = $pdfobject;			# ein PDF-Objekt wurde aus einem übergeordneten Druckrahmen/Layer übergeben
		}
		$this->miny[$this->pdf->currentContents] = 1000000;
		$this->max_dataset_height = 0;
		if($this->offsety)$this->miny[$this->pdf->currentContents] = 842 - $this->offsety;
		$this->pdf->ezSetMargins(40,30,0,0);
    if($this->layout['elements'][$attributes['the_geom']]['xpos'] > 0){		# wenn ein Geometriebild angezeigt werden soll -> loadmap()
    	$this->gui->map_factor = MAPFACTOR;
    	$this->gui->loadmap('DataBase');
    }
		$this->add_static_elements($offsetx);
		$layout_with_sublayout = false;
		for($j = 0; $j < count($this->attributes['name']); $j++){
			if(in_array($this->attributes['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK')) AND $this->layout['elements'][$attributes['name'][$j]]['font'] != ''){
				$layout_with_sublayout = true;
			}
		}
    for($i = 0; $i < count($result); $i++){
			$lastpage = end($this->pdf->objects['3']['info']['pages'])+1;
    	$this->i_on_page++;
			# beim Untereinander-Typ oder eingebettet-Typ ohne Sublayouts oder wenn Datensätze nicht durch Seitenumbruch 
			# unterbrochen werden dürfen, eine Transaktion starten um evtl. bei einem Seitenüberlauf zurückkehren zu können
			if($this->layout['type'] != 0 AND (!$layout_with_sublayout OR $this->layout['no_record_splitting'])){
				$this->pdf->transaction('start');
				$this->transaction_start_pageid = $this->pdf->currentContents;
				$this->transaction_start_y = $this->miny[$this->pdf->currentContents];
			}
			if($this->layout['type'] == 0 AND $i > 0){		# neue Seite beim seitenweisen Typ und neuem Datensatz 
    		$this->pdf->newPage();
				$this->add_static_elements($offsetx);
    	}
			$this->yoffset_onpage = $this->maxy - $this->miny[$lastpage];			# der Offset mit dem die Elemente beim Untereinander-Typ nach unten versetzt werden
			if($this->layout['type'] != 0 AND $this->miny[$lastpage] != '' AND $this->miny[$lastpage] < 60){		# neue Seite beim Untereinander-Typ oder eingebettet-Typ und Seitenüberlauf
				$this->i_on_page = 0;
				#$this->maxy = 0;
				if(!$this->initial_yoffset)$this->initial_yoffset = 780-$this->maxy;			# der Offset von oben gesehen, mit dem das erste fortlaufende Element auf der ersten Seite beginnt; wird benutzt, um die fortlaufenden Elemente ab der 2. Seite oben beginnen zu lassen
				if($this->layout['type'] == 2)$this->offsety = 50; else $this->offsety = 0;
				$this->pdf->newPage();
				$lastpage = end($this->pdf->objects['3']['info']['pages'])+1;
				$this->miny[$lastpage] = 1000000;
			}			
			$this->layout['offset_attributes'] = array();
			
			for($j = 0; $j < count($this->layout['texts']); $j++){
				if($i == 0 OR $this->layout['type'] != 1 OR $this->layout['texts'][$j]['type'] != 1){
					$this->remaining_freetexts[] = $this->layout['texts'][$j]['id'];		# zu Beginn jedes Datensatzes sind alle Freitexte noch zu schreiben, bei fortlaufenden Layouts aber nur die fortlaufenden Freitexte
				}
			}
			for($j = 0; $j < count($this->layout['lines']); $j++){
				if($i == 0 OR $this->layout['type'] != 1 OR $this->layout['lines'][$j]['type'] != 1){
					$this->remaining_lines[] = $this->layout['lines'][$j]['id'];		# zu Beginn jedes Datensatzes sind alle Linien noch zu schreiben, bei fortlaufenden Layouts aber nur die fortlaufenden Linien
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
				$this->add_attribute_elements($selected_layer_id, $layerdb, $this->attributes, $oids, $offsetx, $i, $preview);	# übrig sind die, die noch nicht geschrieben wurden, weil sie abhängig sind
				$test++;
			}			
			################# Daten schreiben ###############
			
			#################  feste Freitexte und Linien hinzufügen, falls keine Attribute da sind ##################
			$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, 'fixed');
			$this->remaining_lines = $this->add_lines($offsetx, 'fixed');
			###############################################################################################
			
			################# fortlaufende Freitexte schreiben ###############
			# (die festen Freitexte werden vor jedem Attribut geschrieben, da ein Attribut zu einem Seitenüberlauf führen können)
			$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, 'running');
			$this->remaining_lines = $this->add_lines($offsetx, 'running');
			################# fortlaufende Freitexte schreiben ###############
			
			if($this->layout['type'] != 0 AND (!$layout_with_sublayout OR $this->layout['no_record_splitting'])){				
				# Ein listenförmiges Layout hat einen Seitenüberlauf verursacht und in diesem gibt es entweder
				# keine weiteren Sublayouts an deren Datensätzen man den Seitenumbruch durchführen könnte oder 
				# das Unterbrechen von Datensätzen ist nicht gewollt. Deshalb wird bis zum Beginn des letzten 
				# Datensatzes zurückgerollt und die Seite vorher umgebrochen, so dass sauber zwischen 2 Datensätzen 
				# und nicht innerhalb eines Datensatzes getrennt wird.
				if($this->page_overflow != false){
					if($this->pdf->overflow_error != true AND ($this->getNextPage($this->transaction_start_pageid) != $this->pdf->currentContents		# wenn die Transaktion aber mehr als 2 Seiten umfasst
					OR $this->transaction_start_y > $this->miny[$this->pdf->currentContents] - 50)){							# oder insgesamt länger als 1 Seite ist, bringt es nichts auf einer neuen Seite zu beginnen, dann committen
						$this->pdf->transaction('commit');
						$this->page_overflow = false;
					}
					else{
						$this->page_overflow = false;
						$this->pdf->transaction('rewind');
						$i--;
						$this->i_on_page = -1;
						$this->maxy = 0;
						if(!$this->initial_yoffset)$this->initial_yoffset = 780-$this->maxy;			# der Offset von oben gesehen, mit dem das erste fortlaufende Element auf der ersten Seite beginnt; wird benutzt, um die fortlaufenden Elemente ab der 2. Seite oben beginnen zu lassen
						if($this->layout['type'] == 2)$this->offsety = 50; else $this->offsety = 0;
						$this->pdf->newPage();
						$lastpage = end($this->pdf->objects['3']['info']['pages'])+1;
						$this->miny[$lastpage] = 0;
					}
				}
				else{
					$this->pdf->transaction('commit');
				}
			}
			elseif($this->page_overflow != false){		# Ein Sublayout hat einen Seitenüberlauf verursacht.
				$this->page_overflow = false;
				$this->i_on_page = 0;
				if(!$this->initial_yoffset)$this->initial_yoffset = 780-$this->maxy;			# der Offset von oben gesehen, mit dem das erste fortlaufende Element auf der ersten Seite beginnt; wird benutzt, um die fortlaufenden Elemente ab der 2. Seite oben beginnen zu lassen
				if($this->layout['type'] == 2)$this->offsety = 50; else $this->offsety = 0;
				$this->miny[$lastpage] = 1000000;
				$this->maxy = 800;
			}
    }
		if($pdfobject == NULL){		# nur wenn kein PDF-Objekt aus einem übergeordneten Layer übergeben wurde, PDF erzeugen
			# Freitexte hinzufügen, die auf jeder Seite erscheinen sollen (Seitennummerierung etc.)
			$this->add_everypage_elements();
			$dateipfad=IMAGEPATH;
			if($this->layout['filename'] != ''){
				$dateiname = $this->layout['filename'];
				for($j = 0; $j < count($this->attributes['name']); $j++){
					$dateiname = str_replace('${'.$this->attributes['name'][$j].'}', $this->get_result_value_output(0, $j, true), $dateiname);
				}
			}
			else{
				$currenttime = date('Y-m-d_H_i_s',time());
				$dateiname = $this->user->Name.'-'.$currenttime;
			}
			$dateiname = umlaute_umwandeln($dateiname).'.pdf';
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
			$this->add_freetexts(0, 0, 'everypage', $i + 1, $pagecount);
			$this->add_lines(0, 'everypage');
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
			if($formvars['gap'] != '')$sql .= ", `gap` = ".(int)$formvars['gap'];
      if($formvars['type'] != '')$sql .= ", `type` = ".(int)$formvars['type'];
      else $sql .= ", `type` = NULL";
			$sql .= ", `no_record_splitting` = ".(int)$formvars['no_record_splitting'];
			if($formvars['filename'])$sql .= ", `filename` = '".$formvars['filename']."'";
      else $sql .= ", `filename` = NULL";			
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
				if($formvars['fontsize_'.$attributes['name'][$i]])$sql.= " ,fontsize = ".(int)$formvars['fontsize_'.$attributes['name'][$i]];
				else $sql.= " ,fontsize = NULL";
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
			$sql .= ", `no_record_splitting` = ".(int)$formvars['no_record_splitting'];
			if($formvars['filename'])$sql .= ", `filename` = '".$formvars['filename']."'";
      else $sql .= ", `filename` = NULL";			
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
				if($formvars['fontsize_'.$attributes['name'][$i]])$sql.= " ,fontsize = ".(int)$formvars['fontsize_'.$attributes['name'][$i]];
				else $sql.= " ,fontsize = NULL";
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
			
      for($i = 0; $i < $formvars['linecount']; $i++){
        $sql = "UPDATE druckfreilinien SET `breite` = '".$formvars['breite'.$i]."'";
        if($formvars['lineposx'.$i])$sql .= ", `posx` = ".(int)$formvars['lineposx'.$i];
        else $sql .= ", `posx` = NULL";
        if($formvars['lineposy'.$i])$sql .= ", `posy` = ".(int)$formvars['lineposy'.$i];
        else $sql .= ", `posy` = NULL";
				if($formvars['lineendposx'.$i])$sql .= ", `endposx` = ".(int)$formvars['lineendposx'.$i];
        else $sql .= ", `endposx` = NULL";
        if($formvars['lineendposy'.$i])$sql .= ", `endposy` = ".(int)$formvars['lineendposy'.$i];
        else $sql .= ", `endposy` = NULL";
				if($formvars['lineoffset_attribute'.$i])$sql .= ", `offset_attribute` = '".$formvars['lineoffset_attribute'.$i]."'";
        else $sql .= ", `offset_attribute` = NULL";
        if($formvars['linetype'.$i] == '')$formvars['linetype'.$i] = 0;
        $sql .= ", `type` = '".$formvars['linetype'.$i]."'";
        $sql .= " WHERE id = ".(int)$formvars['line_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->update_layout :",4);
        $this->database->execSQL($sql,4, 1);
        $lastline_id = mysql_insert_id();
      }
    }
  }
 	
	function load_layouts($stelle_id, $ddl_id, $layer_id, $types, $return = '') {
		$sql = "
			SELECT DISTINCT
				datendrucklayouts.*
			FROM
				datendrucklayouts
		";
    if($ddl_id AND !$stelle_id){
			$sql .= ' WHERE datendrucklayouts.id ='.$ddl_id;
		}
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
			if ($return == 'only_ids') {
				$layouts[] = $rs['id'];
			}
			else {
				$layouts[] = $rs;
				#$layouts[0]['bilder'] = $this->load_bilder($rs['id']);
				$layouts[0]['elements'] = $this->load_elements($rs['id']);
				$layouts[0]['texts'] = $this->load_texts($rs['id']);
				$layouts[0]['lines'] = $this->load_lines($rs['id']);
				$i++;
			}
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
	
  function load_lines($ddl_id){
    $sql = 'SELECT druckfreilinien.* FROM druckfreilinien, ddl2freilinien';
    $sql.= ' WHERE ddl2freilinien.ddl_id = '.$ddl_id;
    $sql.= ' AND ddl2freilinien.line_id = druckfreilinien.id';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:ddl->load_lines :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $lines[] = $rs;
    }
    return $lines;
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
	
  function addline($formvars){
		$i = $formvars['linecount'] - 1;
		if($formvars['linebreite'.$i] != '')$breite = $formvars['linebreite'.$i];	else $breite = 1;
		if($formvars['lineposx'.$i] != '')$posx = $formvars['lineposx'.$i]; else $posx = 70;
		if($formvars['lineposy'.$i] != '')$posy = $formvars['lineposy'.$i]-20; else $posy = 0;
		if($formvars['lineendposx'.$i] != '')$endposx = $formvars['lineendposx'.$i]; else $endposx = 520;
		if($formvars['lineendposy'.$i] != '')$endposy = $formvars['lineendposy'.$i]-20; else $endposy = 0;
    $sql = 'INSERT INTO druckfreilinien SET';
    $sql .= ' posx = '.$posx.',';
    $sql .= ' posy = '.$posy.',';
		$sql .= ' endposx = '.$endposx.',';
    $sql .= ' endposy = '.$endposy.',';
    $sql .= ' breite = '.$breite;
    $this->debug->write("<p>file:kvwmap class:ddl->addline :",4);
    $this->database->execSQL($sql,4, 1);
    $lastinsert_id = mysql_insert_id();
    $sql = 'INSERT INTO ddl2freilinien (ddl_id, line_id) VALUES ('.$formvars['aktivesLayout'].', '.$lastinsert_id.')';
    $this->debug->write("<p>file:kvwmap class:ddl->addline :",4);
    $this->database->execSQL($sql,4, 1);
  }
  
  function removeline($formvars){
    $sql = 'DELETE FROM druckfreilinien WHERE id = '.$formvars['line_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->removeline :",4);
    $this->database->execSQL($sql,4, 1);
    $sql = 'DELETE FROM ddl2freilinien WHERE line_id = '.$formvars['line_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->removeline :",4);
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
 
	function get_fonts() {
		$font_files = searchdir(WWWROOT . APPLVERSION . 'fonts/PDFClass/', true);
		$fonts = array();
		foreach($font_files AS $font_file) {
			if (strpos($font_file, 'php_') === false) {
				$pathinfo = pathinfo($font_file);
				$fonts[] = array(
					'value' => $pathinfo['basename'],
					'output' => $pathinfo['filename']
				);
			}
		}
		#print_r($fonts);
		return $fonts;
	}

	function get_din_formats() {
		$din_formats = array(
			'A5hoch' => array('value' => 'A5hoch', 'output' => 'A5 hoch', 'size' => '(420 x 595)'),
			'A5quer' => array('value' => 'A5quer', 'output' => 'A5 quer', 'size' => '(595 x 420)'),
			'A4hoch' => array('value' => 'A4hoch', 'output' => 'A4 hoch', 'size' => '(595 x 842)'),
			'A4quer' => array('value' => 'A4quer', 'output' => 'A4 quer', 'size' => '(842 x 595)'),
			'A3hoch' => array('value' => 'A3hoch', 'output' => 'A3 hoch', 'size' => '(842 x 1191)'),
			'A3quer' => array('value' => 'A3quer', 'output' => 'A3 quer', 'size' => '(1191 x 842)'),
			'A2hoch' => array('value' => 'A2hoch', 'output' => 'A2 hoch', 'size' => '(1191 x 1684)'),
			'A2quer' => array('value' => 'A2quer', 'output' => 'A2 quer', 'size' => '(1684 x 1191)'),
			'A1hoch' => array('value' => 'A1hoch', 'output' => 'A1 hoch', 'size' => '(1684 x 2384)'),
			'A1quer' => array('value' => 'A1quer', 'output' => 'A1 quer', 'size' => '(2384 x 1684)'),
			'A0hoch' => array('value' => 'A0hoch', 'output' => 'A0 hoch', 'size' => '(2384 x 3370)'),
			'A0quer' => array('value' => 'A0quer', 'output' => 'A0 quer', 'size' => '(3370 x 2384)'),
		);
		return $din_formats;
	}
}
?>
