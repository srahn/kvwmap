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
	public $debug;
	public $database;
	public $gui;
	public $din_formats;
	public $remaining_freetexts;
	public $remaining_rectangles;
	public $remaining_lines;
	public $colors;
	public $layout;
	public $transaction_start_y;
	public $transaction_start_pageid;
	public $pdf;
	public $i_on_page;
	public $miny;
	public $maxy;
	public $offsety;
	public $max_dataset_height;
	public $page_overflow;
	public $layerset;
	public $attributes;
	public $xoffset_onpage;
	public $user;
	public $result;
	public $Stelle;

	function __construct($database, $gui = NULL) {
    global $debug;
    $this->debug = $debug;
    $this->database = $database;
    $this->gui = $gui;
		$this->din_formats = array(
			'A4 hoch' => array('width' => 595, 'height' => 842, 'size' => 'A4', 'orientation' => 'portrait'),
			'A4 quer' => array('width' => 842, 'height' => 595, 'size' => 'A4', 'orientation' => 'landscape'),
			'A3 hoch' => array('width' => 842, 'height' => 1191, 'size' => 'A3', 'orientation' => 'portrait'),
			'A3 quer' => array('width' => 1191, 'height' => 842, 'size' => 'A3', 'orientation' => 'landscape')
		);
		$this->remaining_freetexts = array();
		$this->remaining_rectangles = array();
		$this->remaining_lines = array();
		$this->colors = $this->read_colors();
  }
	
	function read_colors(){
		$sql = "SELECT * FROM ddl_colors";
  	#echo $sql;
  	$ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    if($ret[0]==0){
			while($row = $this->database->result->fetch_assoc()){
        $colors[$row['id']] = $row;
      }
    }
    return $colors;
  }
	
  
  function add_static_elements($offsetx){
		$offsety = $this->offsety;
		# Hintergrundbild    
		if($this->layout['bgsrc'] AND $this->layout['bgposx'] AND $this->layout['bgposy']){
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
	
	function add_freetexts($i, $offsetx, $type, $pagenumber = NULL, $pagecount = NULL, $preview) {
		if (count($this->remaining_freetexts) == 0) {
			return array();
		}
		$remaining_freetexts = array();
		for ($j = 0; $j < count($this->layout['texts']); $j++) {
			# der Freitext wurde noch nicht geschrieben und ist entweder ein fester Freitext oder ein fortlaufender oder einer, der auf jeder Seite erscheinen soll
    	if (
				in_array($this->layout['texts'][$j]['id'], $this->remaining_freetexts) AND
				$this->layout['texts'][$j]['posy'] != ''
			) {	# nur Freitexte mit einem y-Wert werden geschrieben
				if (
					($type == 'fixed' AND !in_array($this->layout['texts'][$j]['type'], [2, 3]) AND ($this->layout['type'] == 0 OR $this->layout['texts'][$j]['type'] == 1)) OR
					($type == 'running' AND $this->layout['type'] != 0 AND $this->layout['texts'][$j]['type'] == 0) OR
					($type == 'everypage' AND (
																			$this->layout['texts'][$j]['type'] == 2 OR 
																			($this->layout['texts'][$j]['type'] == 3 AND $this->pdf->getFirstPageId() != $this->pdf->currentContents)
																		)
					)
				) {
					if ($type != 'everypage' AND $this->page_overflow){
						$this->pdf->reopenObject($this->record_startpage);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
						#$this->i_on_page = 0;		# evtl. nicht 0 setzen, sondern ein eigenes i_on_page für jede Seite machen
						#$this->page_overflow = false;		# muss auskommentiert bleiben, da sonst Fehler in EN-Liste1
					}
					$this->pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/' . $this->layout['texts'][$j]['font']);								
					$x = $this->layout['texts'][$j]['posx'];
					$y = $this->layout['texts'][$j]['posy'];
					$offset_attribute = $this->layout['texts'][$j]['offset_attribute'];
					if ($offset_attribute != ''){			# ist ein offset_attribute gesetzt
						$offset_value = $this->layout['offset_attributes'][$offset_attribute];
						if ($offset_value != ''){		# dieses Attribut wurde auch schon geschrieben, d.h. dessen y-Position ist bekannt -> Freitext relativ dazu setzen 
							$y = $this->handlePageOverflow($offset_attribute, $offset_value, $y);		# Seitenüberläufe berücksichtigen
						}
						else {
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
							$y = $y - $this->yoffset_onpage;
							$x = $x + $this->xoffset_onpage;
						}
					}
					$text = $this->substituteFreitext($this->layout['texts'][$j]['text'], $i, $pagenumber, $pagecount, $preview);
					$width = $this->layout['texts'][$j]['width'];
					$border = $this->layout['texts'][$j]['border'];
					if ($text == 'WIRO-Kartenserver') {
						$this->debug->write('Druck pk: write freetext: ' . $text . ' offsety: ' . $offsety . ' x: ' . $offsetx);
						$this->pdf->addJpegFromFile(DRUCKRAHMEN_PATH . 'wiro-bg-druck02.jpg', 1 + $offsetx, 785, 590);
					}
					$y = $this->putText($text, $this->layout['texts'][$j]['size'], $width, $x, $y, $offsetx, $border, $type);
					if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y)$this->miny[$this->pdf->currentContents] = $y;		# miny ist die unterste y-Position das aktuellen Datensatzes 					
					if($type != 'everypage' AND $this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages'])+1)$this->pdf->closeObject();			# falls in eine alte Seite geschrieben wurde, zurückkehren
				}
				else{
					$remaining_freetexts[] = $this->layout['texts'][$j]['id'];
				}
			}			
	  }
		return $remaining_freetexts;
	}
	
	function add_lines($offsetx, $type) {
		if (count($this->remaining_lines) == 0) {
			return array();
		}
		$remaining_lines = array();
    for($j = 0; $j < count($this->layout['lines']); $j++){
			$overflow = false;
			if($type != 'everypage' AND $this->page_overflow){
				$this->pdf->reopenObject($this->record_startpage);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
				#if($this->layout['type'] == 0)$this->page_overflow = false;			# if ???		muss auskommentiert bleiben, sonst ist die Karte im MVBIO-Drucklayout auf der zweiten Seite
			}
			# die Linie wurde noch nicht geschrieben und ist entweder eine feste Linie oder eine fortlaufende oder eine, der auf jeder Seite erscheinen soll
			if(in_array($this->layout['lines'][$j]['id'], $this->remaining_lines) AND $this->layout['lines'][$j]['posy'] != ''){	# nur Linien mit einem y-Wert werden geschrieben
				if (
					($type == 'fixed' AND !in_array($this->layout['lines'][$j]['type'], [2, 3]) AND ($this->layout['type'] == 0 OR $this->layout['lines'][$j]['type'] == 1)) OR 
					($type == 'running' AND $this->layout['type'] != 0 AND $this->layout['lines'][$j]['type'] == 0)	OR 
					($type == 'everypage' AND (
																			$this->layout['lines'][$j]['type'] == 2 OR 
																			($this->layout['lines'][$j]['type'] == 3 AND $this->pdf->getFirstPageId() != $this->pdf->currentContents)
																		)
					)
				) {							
					$x = $this->layout['lines'][$j]['posx'] + $offsetx;
					$y_orig = $y = $this->layout['lines'][$j]['posy'];
					$endx = $this->layout['lines'][$j]['endposx'] + $offsetx;
					$endy = $this->layout['lines'][$j]['endposy'];
					$offset_attribute_start = $this->layout['lines'][$j]['offset_attribute_start'];
					$offset_attribute_end = $this->layout['lines'][$j]['offset_attribute_end'];
					if($offset_attribute_start != ''){			# ist ein offset_attribute gesetzt
						$offset_value = $this->layout['offset_attributes'][$offset_attribute_start];
						if($offset_value != ''){		# dieses Attribut wurde auch schon geschrieben, d.h. dessen y-Position ist bekannt -> Linie relativ dazu setzen
							$y = $this->handlePageOverflow($offset_attribute_start, $offset_value, $y);		# Seitenüberläufe berücksichtigen
						}
						else{
							$remaining_lines[] = $this->layout['lines'][$j]['id'];
							continue;			# die Linie ist abhängig aber das Attribut noch nicht geschrieben, Linie merken und überspringen
						}
					}
					$page_id_start = $this->pdf->currentContents;
					if($offset_attribute_end != ''){			# ist ein offset_attribute gesetzt
						$offset_value = $this->layout['offset_attributes'][$offset_attribute_end];
						if($offset_value != ''){		# dieses Attribut wurde auch schon geschrieben, d.h. dessen y-Position ist bekannt -> Linie relativ dazu setzen
							$endy = $this->handlePageOverflow($offset_attribute_end, $offset_value, $endy);		# Seitenüberläufe berücksichtigen
						}
						else{
							$remaining_lines[] = $this->layout['lines'][$j]['id'];
							continue;			# die Linie ist abhängig aber das Attribut noch nicht geschrieben, Linie merken und überspringen
						}
					}
					if($page_id_start != $this->pdf->currentContents){
						$overflow = true;
					}
					if($offset_attribute_start == ''){
						$y = $y - $this->offsety;
						if($offset_attribute_end == ''){
							$endy = $endy - ($y_orig - $y);		# y-Endposition auch anpassen
						}
					}
					if($type == 'running'){	# fortlaufende Linien
						$pagecount = count($this->pdf->objects['3']['info']['pages']);								
						if($this->layout['type'] == 1 AND $offset_attribute_start == '' AND $pagecount > 1){
							$y = $y + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
							$endy = $endy + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
						}
						if($this->i_on_page == 0){
							#if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
							#if($this->maxy < $endy)$this->maxy = $endy;		# beim ersten Datensatz das maxy ermitteln							
						}						
						if($offset_attribute_start == '' AND $this->i_on_page > 0){		# bei allen darauffolgenden den y-Wert um Offset verschieben (aber nur bei absolut positionierten)
							$y = $y - $this->yoffset_onpage;
							$endy = $endy - $this->yoffset_onpage;
							$x = $x + $this->xoffset_onpage;
						}
					}
					$this->pdf->setLineStyle($this->layout['lines'][$j]['breite'], 'square');
					if($overflow){		# Seitenumbruch dazwischen
						$this->pdf->reopenObject($page_id_start);
						$this->pdf->line($x, $y, $endx, $this->layout['margin_bottom']);
						$this->pdf->closeObject();
						$in_between_page_id = $this->getNextPage($page_id_start);
						while ($in_between_page_id != $this->pdf->currentContents) {
							$this->pdf->reopenObject($in_between_page_id);
							$this->pdf->line($x, $this->layout['height'] - $this->layout['margin_top'] + 10, $endx, $this->layout['margin_bottom']);
							$this->pdf->closeObject();
							$in_between_page_id = $this->getNextPage($in_between_page_id);
						}
						$this->pdf->line($x, $this->layout['height'] - $this->layout['margin_top'] + 10, $endx, $endy);
					}
					else{
						$this->pdf->line($x, $y, $endx, $endy);
					}
					$line['x1'] = $x;
					$line['y1'] = $y;
					$line['x2'] = $endx;
					$line['y2'] = $endy;
					$line['id'] = $this->layout['lines'][$j]['id'];
					$this->gui->lines[$this->pdf->currentContents][] = $line;
					#echo 'zeichne Linie: '.$x.' '.$y.' '.$endx.' '.$endy.'<br>';
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
	
	function add_rectangles($offsetx, $type){
		if (count($this->remaining_rectangles) == 0) {
			return array();
		}
		$remaining_rectangles = array();
    for($j = 0; $j < count($this->layout['rectangles']); $j++){
			$overflow = false;
			if($type != 'everypage' AND $this->page_overflow){
				$this->pdf->reopenObject($this->record_startpage);		# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
				#if($this->layout['type'] == 0)$this->page_overflow = false;			# if ???		muss auskommentiert bleiben, sonst ist die Karte im MVBIO-Drucklayout auf der zweiten Seite
			}
			# das Rechteck wurde noch nicht geschrieben und ist entweder ein festes Rechteck oder ein fortlaufendes oder eins, welches auf jeder Seite erscheinen soll
    	if(in_array($this->layout['rectangles'][$j]['id'], $this->remaining_rectangles) AND $this->layout['rectangles'][$j]['posy'] != ''){	# nur Linien mit einem y-Wert werden geschrieben
				if(($type == 'fixed' AND $this->layout['rectangles'][$j]['type'] != 2 AND ($this->layout['type'] == 0 OR $this->layout['rectangles'][$j]['type'] == 1)) 
				OR ($type == 'running' AND $this->layout['type'] != 0 AND in_array($this->layout['rectangles'][$j]['type'], [0,3]))
				OR ($type == 'everypage' AND $this->layout['rectangles'][$j]['type'] == 2)){			
					$x = $this->layout['rectangles'][$j]['posx'] + $offsetx;
					$y_orig = $y = $this->layout['rectangles'][$j]['posy'];
					$endx = $this->layout['rectangles'][$j]['endposx'] + $offsetx;
					$endy = $this->layout['rectangles'][$j]['endposy'];
					$offset_attribute_start = $this->layout['rectangles'][$j]['offset_attribute_start'];
					$offset_attribute_end = $this->layout['rectangles'][$j]['offset_attribute_end'];
					if($offset_attribute_start != ''){			# ist ein offset_attribute gesetzt
						$offset_value = $this->layout['offset_attributes'][$offset_attribute_start];
						if($offset_value != ''){		# dieses Attribut wurde auch schon geschrieben, d.h. dessen y-Position ist bekannt -> Linie relativ dazu setzen
							$y = $this->handlePageOverflow($offset_attribute_start, $offset_value, $y);		# Seitenüberläufe berücksichtigen
						}
						else{
							$remaining_rectangles[] = $this->layout['rectangles'][$j]['id'];
							continue;			# die Linie ist abhängig aber das Attribut noch nicht geschrieben, Linie merken und überspringen
						}
					}
					$page_id_start = $this->pdf->currentContents;
					if($offset_attribute_end != ''){			# ist ein offset_attribute gesetzt
						$offset_value = $this->layout['offset_attributes'][$offset_attribute_end];
						if($offset_value != ''){		# dieses Attribut wurde auch schon geschrieben, d.h. dessen y-Position ist bekannt -> Linie relativ dazu setzen
							$endy = $this->handlePageOverflow($offset_attribute_end, $offset_value, $endy);		# Seitenüberläufe berücksichtigen
						}
						else{
							$remaining_rectangles[] = $this->layout['rectangles'][$j]['id'];
							continue;			# die Linie ist abhängig aber das Attribut noch nicht geschrieben, Linie merken und überspringen
						}
					}
					if($page_id_start != $this->pdf->currentContents){
						$overflow = true;
					}
					if($offset_attribute_start == ''){
						$y = $y - $this->offsety;
						if($offset_attribute_end == ''){
							$endy = $endy - ($y_orig - $y);		# y-Endposition auch anpassen
						}
					}
					if($type == 'running'){	# fortlaufende Linien
						$pagecount = count($this->pdf->objects['3']['info']['pages']);								
						if($this->layout['type'] == 1 AND $offset_attribute_start == '' AND $pagecount > 1){
							$y = $y + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
							$endy = $endy + $this->initial_yoffset;		# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
						}
						if($this->i_on_page == 0){
							#if($this->maxy < $y)$this->maxy = $y;		# beim ersten Datensatz das maxy ermitteln
							#if($this->maxy < $endy)$this->maxy = $endy;		# beim ersten Datensatz das maxy ermitteln							
						}						
						if($offset_attribute_start == '' AND $this->i_on_page > 0){		# bei allen darauffolgenden den y-Wert um Offset verschieben (aber nur bei absolut positionierten)
							$y = $y - $this->yoffset_onpage;
							$endy = $endy - $this->yoffset_onpage;
							$x = $x + $this->xoffset_onpage;
						}
					}
					$color_id = $this->layout['rectangles'][$j]['color'];
					if($color_id != ''){
						if($this->layout['rectangles'][$j]['type'] != 3 OR !$this->layout['rectangles'][$j]['printed']){		# bei type = 3 (alternierend) gibt 'printed' an ob das letzte Rechteck gefüllt war oder nicht
							$this->layout['rectangles'][$j]['printed'] = true;
							if($overflow){		# Seitenumbruch dazwischen
								$this->pdf->reopenObject($page_id_start);
								$this->pdf->filledRectangleBelow($x, $this->layout['margin_bottom'], $endx-$x, $y - $this->layout['margin_bottom'], $this->colors[$color_id]['red']/255,$this->colors[$color_id]['green']/255,$this->colors[$color_id]['blue']/255);
								$this->pdf->closeObject();
								$this->pdf->filledRectangleBelow($x, $endy, $endx-$x, $this->layout['height'] - $this->layout['margin_top'] - $endy, $this->colors[$color_id]['red']/255,$this->colors[$color_id]['green']/255,$this->colors[$color_id]['blue']/255);
							}
							else{
								$this->pdf->filledRectangleBelow($x, $y, $endx-$x, $endy-$y, $this->colors[$color_id]['red']/255,$this->colors[$color_id]['green']/255,$this->colors[$color_id]['blue']/255);
							}							
						}
						else{
							$this->layout['rectangles'][$j]['printed'] = false;
						}
					}
					if($this->layout['rectangles'][$j]['breite'] > 0){
						$this->pdf->setLineStyle($this->layout['rectangles'][$j]['breite'], 'square');
						if($overflow){		# Seitenumbruch dazwischen
							$this->pdf->reopenObject($page_id_start);
							$this->pdf->rectangle($x, $this->layout['margin_bottom'], $endx-$x, $y - $this->layout['margin_bottom']);
							$this->pdf->closeObject();
							$this->pdf->rectangle($x, $endy, $endx-$x, $this->layout['height'] - $this->layout['margin_top']  - $endy);
						}
						else{
							$this->pdf->rectangle($x, $y, $endx-$x, $endy-$y);
						}
					}
					$rectangle['x1'] = $x;
					$rectangle['y1'] = min($y, $endy);
					$rectangle['x2'] = $endx-$x;
					$rectangle['y2'] = abs($endy-$y);
					$rectangle['id'] = $this->layout['rectangles'][$j]['id'];
					$this->gui->rectangles[$this->pdf->currentContents][] = $rectangle;
					#echo 'zeichne Rechteck: '.$x.' '.$y.' '.$endx.' '.$endy.'<br>';
					if($this->layout['rectangles'][$j]['type'] === 0){
						#if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y)$this->miny[$this->pdf->currentContents] = $y;		# miny ist die unterste y-Position das aktuellen Datensatzes 
						#if(!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $endy)$this->miny[$this->pdf->currentContents] = $endy;		# miny ist die unterste y-Position das aktuellen Datensatzes 
					}
				}
				else{
					$remaining_rectangles[] = $this->layout['rectangles'][$j]['id'];
				}
			}
			if($type != 'everypage' AND $this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages'])+1)$this->pdf->closeObject();			# falls in eine alte Seite geschrieben wurde, zurückkehren
	  }
		return $remaining_rectangles;
	}

	function add_attribute_elements($selected_layer_id, $layerdb, $attributes, $offsetx, $i, $preview) {
		for ($j = 0; $j < count($attributes['name']); $j++) {
			$wordwrapoffset = 1;
			if (
				in_array($attributes['name'][$j], $this->remaining_attributes) AND
				$this->layout['elements'][$attributes['name'][$j]]['ypos'] > 0
			) {
				# wenn Attribut noch nicht geschrieben wurde und einen y-Wert hat 
				# da ein Attribut zu einem Seitenüberlauf führen kann, müssen davor alle festen Freitexte geschrieben werden, die geschrieben werden können
				# d.h. alle, deren Position nicht abhängig vom einem Attribut ist und alle deren Position abhängig ist und das Attribut schon geschrieben wurde
				$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, 'fixed', NULL, NULL, $preview);			#  feste Freitexte hinzufügen
				$this->remaining_lines = $this->add_lines($offsetx, 'fixed');			# feste Linien hinzufügen
				$this->remaining_rectangles = $this->add_rectangles($offsetx, 'fixed');			# feste Rechtecke hinzufügen
				if ($attributes['type'][$j] != 'geometry') {
					switch ($attributes['form_element_type'][$j]) {
						case 'SubFormPK' :
						case 'SubFormEmbeddedPK' : {
							if ($this->page_overflow) {
								# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
								$this->pdf->reopenObject($this->record_startpage);
							}
							if (true or $this->layout['elements'][$attributes['name'][$j]]['font'] != '') {
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
								if ($offset_attribute != '') {
									# es ist ein offset_attribute gesetzt
									$offset_value = $this->layout['offset_attributes'][$offset_attribute];
									if ($offset_value != '') {
										# Offset wurde auch schon bestimmt, relative y-Position berechnen
										$ypos = $this->handlePageOverflow($offset_attribute, $offset_value, $ypos);		# Seitenüberläufe berücksichtigen
									}
									else {
										# Offset noch nicht da, überspringen
										# Saves wieder setzen
										$this->gui->formvars['selected_layer_id'] = $layerid_save;
										$this->gui->formvars['chosen_layer_id'] = $layerid_save;
										$this->gui->formvars['aktivesLayout'] = $layoutid_save;
										continue 2; 
									}
								}
								#### relative Positionierung über Offset-Attribut ####

								$pagecount = count($this->pdf->objects['3']['info']['pages']);
								if (
									$this->layout['type'] == 1 AND
									$offset_attribute == '' AND
									$pagecount > 1
								) {
									# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
									$ypos = $ypos + $this->initial_yoffset;
								}
								$offy = $this->layout['height'] - $ypos;
								if ($offset_attribute == '') {
									$offy = $offy + $this->offsety;
									if ($this->layout['type'] != 0 AND $this->i_on_page > 0) {
										# beim Untereinander-Typ y-Wert um Offset verschieben (aber nur bei abolut positionierten)
										$offy = $offy + $this->yoffset_onpage;
										$offx = $offx + $this->xoffset_onpage;
									}
								}

								# beim jedem Datensatz die Gesamthoehe der Elemente des Datensatzes ermitteln
								if ($this->i_on_page == 0) {
									# beim ersten Datensatz das maxy ermitteln
									if($this->maxy < $this->layout['height']-$offy)$this->maxy = $this->layout['height']-$offy;
								}
								if ($sublayout == '') {
									$y = $ypos - 20;
								}
								else {
									if ($preview) {
										$sublayoutobject = $this->load_layouts(NULL, $sublayout, NULL, NULL);
										$y = $this->gui->sachdaten_druck_editor_preview($sublayoutobject[0], $this->pdf, $offx, $offy);
									}
									else {
										$this->gui->getSubFormResultSet($this->attributes, $j, $this->layerset['maintable'], $this->result[$i]);
										$this->gui->formvars['aktivesLayout'] = $sublayout;
										$page_id_before_sublayout = $this->pdf->currentContents;
										$result = $this->gui->generischer_sachdaten_druck_createPDF($this->pdf, $offx, $offy, false);
										$y = $result['y'];
										$page_id_after_sublayout = $this->pdf->currentContents;
										if ($page_id_before_sublayout != $page_id_after_sublayout) {
											$this->page_overflow = true;
										}
									}
								}
								# den letzten y-Wert dieses Elements in das Offset-Array schreiben
								$this->layout['offset_attributes'][$attributes['name'][$j]] = $y;
								if (!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y) {
									if (($this->miny[$this->pdf->currentContents] - (float)$y) > $this->max_dataset_height) {
										$this->max_dataset_height = $this->miny[$this->pdf->currentContents] - $y;
									}
									# miny ist die unterste y-Position das aktuellen Datensatzes
									$this->miny[$this->pdf->currentContents] = $y;
								}

								# und die Page-ID merken, in der das Attribut beendet wurde
								$this->layout['page_id'][$attributes['name'][$j]] = $this->pdf->currentContents;
								if ($this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages']) + 1) {
									# falls in eine alte Seite geschrieben wurde, zurückkehren
									$this->pdf->closeObject();
								}
								# Saves wieder setzen
								$this->gui->formvars['selected_layer_id'] = $layerid_save;
								$this->gui->formvars['chosen_layer_id'] = $layerid_save;
								$this->gui->formvars['aktivesLayout'] = $layoutid_save;
							}
							if ($this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages']) + 1) {
								# falls in eine alte Seite geschrieben wurde, zurückkehren
								$this->pdf->closeObject();
							}
						} break;

						default : {
							$offset_attribute = $this->layout['elements'][$attributes['name'][$j]]['offset_attribute'];
							$value = $this->result[$i][$attributes['name'][$j]];
							$value_offset_attribute = $this->result[$i][$offset_attribute];
							$zeilenhoehe = $this->layout['elements'][$attributes['name'][$j]]['fontsize'];
							$y = $this->layout['elements'][$attributes['name'][$j]]['ypos'];
							$x = $this->layout['elements'][$attributes['name'][$j]]['xpos'];
							$width = $this->layout['elements'][$attributes['name'][$j]]['width'];
							$border = $this->layout['elements'][$attributes['name'][$j]]['border'];
							$label = $this->layout['elements'][$attributes['name'][$j]]['label'];
							$margin = $this->layout['elements'][$attributes['name'][$j]]['margin'];
							$pagecount = count($this->pdf->objects['3']['info']['pages']);

							if ($label != '') {
								$label_x = $x;
								$x = $x + $margin;
							}

							if ($this->page_overflow) {
								# es gab vorher einen Seitenüberlauf durch ein Sublayout -> zu alter Seite zurückkehren
								$this->pdf->reopenObject($this->record_startpage);
							}
							$this->pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/' . $this->layout['elements'][$attributes['name'][$j]]['font']);
							if (
								$this->layout['elements'][$attributes['name'][$j]]['fontsize'] > 0 OR
								$attributes['form_element_type'][$j] == 'Dokument'
							) {
								#### relative Positionierung über Offset-Attribut #
								if ($offset_attribute != '') {		# es ist ein offset_attribute gesetzt
									$offset_value = $this->layout['offset_attributes'][$offset_attribute];
									if ($offset_value != ''){		# Offset wurde auch schon bestimmt, relative y-Position berechnen
										if ($this->layout['dont_print_empty'] AND $value_offset_attribute == '') {
											$y = 0;
										}
										$y = $this->handlePageOverflow($offset_attribute, $offset_value, $y);		# Seitenüberläufe berücksichtigen
									}
									else {
										#$remaining_attributes[] = $attributes['name'][$j];	# Offset wurde noch nicht bestimmt, Attribut merken und überspringen
										continue 2;
									}
								}
								elseif (
									$this->layout['type'] == 0 AND
									$this->pdf->getFirstPageId() != end($this->pdf->objects['3']['info']['pages']) + 1
								) {
									# zurück zur ersten Seite bei seitenweisem Typ und allen absolut positionierten Attributen, wenn erforderlich
									#$this->pdf->reopenObject($this->pdf->getFirstPageId());
								}
								# relative Positionierung über Offset-Attribut ####
								
								if ($this->layout['type'] == 1 AND $offset_attribute == '' AND $pagecount > 1) {
									# ab der 2. Seite sollen die forlaufenden absolut positionierten Elemente oben auf der Seite beginnen
									$y = $y + $this->initial_yoffset;
								}
								if ($offset_attribute == '') {
									$y = $y - $this->offsety;
								}
								if (
									$this->layout['type'] != 0 AND
									$offset_attribute == '' AND
									$this->i_on_page > 0
								) {
									# beim Untereinander-Typ y-Wert um Offset verschieben (aber nur bei absolut positionierten)
									$y = $y - $this->yoffset_onpage;
									$x = $x + $this->xoffset_onpage;
								}			
								# beim jedem Datensatz die Gesamthoehe der Elemente des Datensatzes ermitteln
								if ($this->i_on_page == 0) {
									if ($this->maxy < $y) {
										# beim ersten Datensatz das maxy ermitteln
										$this->maxy = $y;
									}
								}
								
								if ($label != '' AND !($this->layout['dont_print_empty'] AND $value == '')) {
									$this->putText($label, $zeilenhoehe, NULL, $label_x, $y, $offsetx, $border);
								}

								if (substr($attributes['type'][$j], 0, 1) == '_') {
									# Array
									$values = json_decode($value);
									$x2 = $x;
									$y2 = $miny_array = $y;
									for ($v = 0; $v < count_or_0($values); $v++) {
										if ($attributes['form_element_type'][$j] == 'Dokument') {
											# Dokument-Attribute werden im Raster ausgegeben
											if ($v > 0) {
												if (($x2 + 2 * $width + 20) < ($this->layout['width'] - $this->layout['margin_right'])) {
													# neue Spalte
													$x2 += $width + 20;
												}
												else {
													# neue Zeile
													$x2 = $x;
													$y2 = $miny_array - 20;
												}
											}
											$y = $this->putImage($values[$v], $j, $x2, $y2, $offsetx, $width, $preview);
											if ($y < $miny_array) {
												$miny_array = $y;
											}
										}
										else {
											if ($v > 0) {
												# neue Zeile
												$y2 = $miny_array - 20;
											}
											$text = $this->get_result_value_output($values[$v], $i, $j, $preview);
											$miny_array = $this->putText($text, $zeilenhoehe, $width, $x, $y2, $offsetx, $border);
										}
									}
									$y = $miny_array;
								}
								else {
									# normal
									if ($attributes['form_element_type'][$j] == 'Dokument') {
										$y = $this->putImage($value, $j, $x, $y, $offsetx, $width, $preview);
									}
									else {
										$text = $this->get_result_value_output($value, $i, $j, $preview);
										$y = $this->putText($text, $zeilenhoehe, $width, $x, $y, $offsetx, $border);
									}
								}
								if (!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y) {
									if (($this->miny[$this->pdf->currentContents] - $y) > $this->max_dataset_height) {
										$this->max_dataset_height = $this->miny[$this->pdf->currentContents] - $y;
									}
									# miny ist die unterste y-Position das aktuellen Datensatzes
									$this->miny[$this->pdf->currentContents] = $y;
								}
								# den unteren y-Wert dieses Elements in das Offset-Array schreiben
								$this->layout['offset_attributes'][$attributes['name'][$j]] = $y;
								# und die Page-ID merken, in der das Attribut beendet wurde
								$this->layout['page_id'][$attributes['name'][$j]] = $this->pdf->currentContents;
								if (
									$this->page_overflow AND
									$this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages']) + 1
								) {
									# falls in eine alte Seite geschrieben wurde, zurückkehren
									$this->pdf->closeObject();
								}
							}
						}
					}
				}
				elseif (
					$attributes['name'][$j] == $attributes['the_geom'] AND
					$this->layout['elements'][$attributes['name'][$j]]['xpos'] > 0
				) {
					# Geometrie
					if ($this->layout['type'] == 0 AND $this->record_startpage != end($this->pdf->objects['3']['info']['pages']) + 1) {
						# zurück zur Startseite des Datensatzes
						$this->pdf->reopenObject($this->record_startpage);
					}
					$this->gui->map->width = $this->layout['elements'][$attributes['name'][$j]]['width'] * MAPFACTOR;
					$this->gui->map->height = $this->layout['elements'][$attributes['name'][$j]]['width'] * MAPFACTOR;
					$oid = $this->result[$i][$this->layerset['maintable'].'_oid'];
					# Rollenlayer zum Highlighten erzeugen und auf Objekt zoomen
					if ($oid != ''){
						if ($this->layout['elements'][$attributes['name'][$j]]['fontsize'] > 0) {
							# bei Geometrie-Attributen wird in fontsize der Zoom-Rand gespeichert
							$rand = $this->layout['elements'][$attributes['name'][$j]]['fontsize'];
						}
						elseif (defined('ZOOMBUFFER') AND ZOOMBUFFER > 0) {
							$rand = ZOOMBUFFER;
						}
						else {
							$rand = 100;
						}
						if ($attributes['geomtype'][$attributes['the_geom']] == 'POINT') {
							include_(CLASSPATH.'pointeditor.php');
							$pointeditor = new pointeditor($layerdb, $this->layerset['epsg_code'], $this->gui->user->rolle->epsg_code, $this->layerset['oid']);
							$point = $pointeditor->getpoint($oid, $attributes['table_name'][$attributes['the_geom']], $attributes['real_name'][$attributes['the_geom']]);
							$rect = rectObj(
								$point['pointx'] - $rand,
								$point['pointy'] - $rand,								 
								$point['pointx'] + $rand,
								$point['pointy'] + $rand
							);
						}
						else {
							include_(CLASSPATH.'multigeomeditor.php');
							$polygoneditor = new multigeomeditor($layerdb, $this->layerset['epsg_code'], $this->gui->user->rolle->epsg_code, $this->layerset['oid']);
							$rect = $polygoneditor->zoomToGeom(
								$oid,
								$attributes['table_name'][$attributes['the_geom']],
								$attributes['real_name'][$attributes['the_geom']],
								$rand
							);
						}
						$this->gui->formvars['layer_id'] = $selected_layer_id;
						$this->gui->formvars['oid'] = $oid;
						$this->gui->formvars['selektieren'] = 'false';
						$rollenlayer_id = $this->gui->createZoomRollenlayer($this->gui->mapDB, $layerdb, array($this->layerset), array($oid));
						$rollenlayer = $this->gui->mapDB->read_RollenLayer($rollenlayer_id);
						$this->gui->loadlayer($this->gui->map, $rollenlayer[0]);
						$this->gui->map->setextent($rect->minx, $rect->miny, $rect->maxx, $rect->maxy);
					}
					if ($this->gui->map->selectOutputFormat('jpeg_print') == 1) {
						$this->gui->map->selectOutputFormat('jpeg');
					}
					$this->gui->switchScaleUnitIfNecessary();
					$this->gui->map->scalebar->status = MS_EMBED;
					$this->gui->map->scalebar->position = MS_LR;
					$this->gui->map->scalebar->label->size = 12;
					$this->gui->map->scalebar->width = 180;
					$this->gui->map->scalebar->height = 3;
					# Parameter $scale in Data ersetzen
					for($l = 0; $l < count($this->gui->layers_replace_scale); $l++){
						$this->gui->layers_replace_scale[$l]->data = str_replace('$SCALE', $this->gui->map_scaledenom, $this->gui->layers_replace_scale[$l]->data);
					}
					$image_map = $this->gui->map->draw();
					# Rollenlayer wieder entfernen
					if ($oid != '') {
						$this->gui->mapDB->deleteRollenLayer($rollenlayer_id);
						$this->gui->map->removeLayer($this->gui->map->numlayers - 1);		# der letzte Layer ist die Scalebar
						$this->gui->map->removeLayer($this->gui->map->numlayers - 1);
					}
					$filename = $this->gui->map_saveWebImage($image_map,'jpeg');
					$newname = $this->user->id.basename($filename);
					rename(IMAGEPATH . basename($filename), IMAGEPATH . $newname);
					$x = $this->layout['elements'][$attributes['name'][$j]]['xpos'] + $offsetx;
					$y = $this->layout['elements'][$attributes['name'][$j]]['ypos'] - $this->offsety;
					if ($this->i_on_page == 0) {
						if ($this->maxy < $y+$this->layout['elements'][$attributes['name'][$j]]['width']) {
							# beim ersten Datensatz das maxy ermitteln
							$this->maxy = $y+$this->layout['elements'][$attributes['name'][$j]]['width'];
						}
					}
					if ($this->layout['type'] != 0 AND $this->i_on_page > 0) {
						# beim Untereinander-Typ y-Wert um Offset verschieben
						$y = $y - $this->yoffset_onpage;
						$x = $x - $this->xoffset_onpage;
					}
					$this->pdf->addJpegFromFile(IMAGEPATH . $newname, $x, $y, $this->layout['elements'][$attributes['name'][$j]]['width']);
					# Rechteck um die Karte
					$this->pdf->setLineStyle(1, 'square');
					$this->pdf->rectangle(
						$x, $y,
						$this->layout['elements'][$attributes['name'][$j]]['width'],
						$this->layout['elements'][$attributes['name'][$j]]['width']
					);
					if (!$this->miny[$this->pdf->currentContents] OR $this->miny[$this->pdf->currentContents] > $y) {
						#$this->miny[$this->pdf->currentContents] = $y;		# Fehler bei Druck der VSG mit Maßnahmen im Schutzgebietsportal
					}
					if ($this->pdf->currentContents != end($this->pdf->objects['3']['info']['pages']) + 1) {
						# falls in eine alte Seite geschrieben wurde, zurückkehren
						$this->pdf->closeObject();
					}
				}
				# das Attribut aus den remaining_attributes entfernen
				unset($this->remaining_attributes[$attributes['name'][$j]]);
			}
		}
	}

	function handlePageOverflow($offset_attribute, $offset_value, $ypos){
		$offset_value = (float)$offset_value;
		$ypos = (float)$ypos;
		if($this->layout['page_id'][$offset_attribute] != $this->pdf->currentContents){
			$backto_oldpage = true;															# das Offset-Attribut wurde auf einer anderen Seite beendet -> zu dieser Seite zurückkehren
		}
		if($offset_value - $ypos < 40){	# Seitenüberlauf
			$offset_value = $this->layout['height'] + $offset_value - 40 - 30;	# Offsetwert so anpassen, dass er für die neue Seite passt
			$next_page = $this->getNextPage($this->layout['page_id'][$offset_attribute]);
			if($next_page != NULL){
				$this->pdf->reopenObject($next_page);		# die nächste Seite der Seite des Offset-Attributes nehmen
			}
			else{																			# wenns noch keine gibt, neue Seite erstellen
				$this->pdf->ezNewPage();			# eine neue Seite beginnen
				$this->miny[$this->pdf->currentContents] = $this->layout['height'];
				$this->maxy = 800;
				if($this->layout['type'] == 2)$this->offsety = 50;
				$this->page_overflow = true;
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
	

	function putImage($dokumentpfad, $j, $x, $y, $offsetx, $width, $preview) {
		if ($width == '') {
			$width = 50;
		}
		if (substr($dokumentpfad, 0, 4) == 'http') {
			$file = file_get_contents($dokumentpfad);
			$dokumentpfad = IMAGEPATH . rand(0, 100000) . '.jpg';
			file_put_contents($dokumentpfad, $file);
		}
		$pfadteil = explode('&original_name=', $dokumentpfad);
		$dateiname = $pfadteil[0];
		if ($dateiname == $this->attributes['alias'][$j] AND $preview) {
			$dateiname = WWWROOT . APPLVERSION . GRAPHICSPATH . 'nogeom.png'; # als Platzhalter im Editor
		}

		if ($this->layout['use_previews']) {
			$path_parts = pathinfo($dateiname);
			$preview_img = $path_parts['dirname'] . '/' . $path_parts['filename'] . '_thumb.jpg';
			if (file_exists($preview_img)) {
				$dateiname = $preview_img;
			}
		}

		if ($dateiname != '' AND file_exists($dateiname)) {
			$dateinamensteil = pathinfo($dateiname);
			if (in_array(strtolower($dateinamensteil['extension']), array('jpg', 'png', 'gif', 'tif', 'pdf'))) {
				$new_filename = IMAGEPATH . $dateinamensteil['filename'] . '.jpg';
				if (!file_exists($new_filename)) {
					$command = IMAGEMAGICKPATH . 'convert "' . $dateiname . '" -background white -flatten "' . $new_filename . '"';
					#echo 'Kommando zum konvertieren der Bilddatei: ' . $command;
					exec($command, $result, $status);
					#echo '<br>Result of command: ' . print_r($command, true) . ' status: ' . $status;
				}
				// echo '<br>dateiname: ' . $dateiname;
				// echo '<br>newfile: ' . $new_filename;
				// echo '<br>file_exists: ' . file_exists($new_filename);
				// exit;
				if (file_exists($new_filename)) {
					$size = getimagesize($new_filename);
					$ratio = $size[1] / $size[0];
					$height = $ratio * $width;
					$x = $x + $offsetx;
					$y = $y - $height;
					if ($y < $this->layout['margin_bottom']) {
						$nextpage = $this->getNextPage($this->pdf->currentContents);
						if ($nextpage != NULL) {
							$this->pdf->reopenObject($nextpage);
						}
						else {
							$this->pdf->ezNewPage();
							$this->miny[$this->pdf->currentContents] = $this->layout['height'];
							$this->maxy = 800;
							if($this->layout['type'] == 2)$this->offsety = 50;
							$this->page_overflow = true;
						}
						$y = $this->layout['height'] - $this->layout['margin_top'] - 20 - $height;
					}
					$this->pdf->addJpegFromFile($new_filename, $x, $y, $width);
					if ($this->layout['maxx'] < ($x + $width)){
						$this->layout['maxx'] = ($x + $width);			# maximaler x-Wert für Spaltenanordnung
					}
				}
				else {
					return array(
						'success' => false,
						'msg' => 'Fehler beim hinzufügen der Datei ' . $dateiname . ' zum Drucklayout. Die Datei ' . $new_filename . ' konnte nicht erzeugt oder gefunden werden!'
					);
				}
			}
		}
		return $y;
	}

	function putText($text, $fontsize, $width, $x, $y, $offsetx, $border = false, $type = 'running'){	
		if($y < $this->pdf->ez['bottomMargin']){
			$nextpage = $this->getNextPage($this->pdf->currentContents);
			if($nextpage != NULL){
				$this->pdf->reopenObject($nextpage);
			}
			else{
				$this->pdf->ezNewPage();
				$this->miny[$this->pdf->currentContents] = $this->layout['height'];
				$this->maxy = 800;
				if($this->layout['type'] == 2)$this->offsety = 50;
				$this->page_overflow = true;
			}
			$y = $this->layout['height'] - $this->layout['margin_top'] - 20;
		}
		if($x < 0){		# rechtsbündig
			$x = $this->layout['width'] + $x;
			$x = $x + $offsetx;
			$options = array('aright'=>$x, 'justification'=>'right');
		}
		else{							# linksbündig
			$x = $x + $offsetx;
			if($width != ''){
				$right = $this->layout['width'] - $width - $x;
				$just = 'full';
			}
			else{
				$right = NULL;
				$just = 'left';
			}
			$options = array('aleft'=>$x, 'right'=>$right, 'justification'=>$just);
		}
		$fh = $this->pdf->getFontHeight($fontsize);
		$y = $y + $fh;
		$page_id_before_puttext = $this->pdf->currentContents;
		$this->pdf->ezSetY($y);
		if($border){
			$text = '<box>'.$text.'</box>';
		}
		$ret = $this->pdf->ezText(iconv("UTF-8", "CP1252//TRANSLIT", $text), $fontsize, $options);
		$lines = explode(chr(10), $text);
		foreach($lines as $line){
			$maxx = $this->pdf->getTextWidth($fontsize, $line);
			if($this->layout['maxx'] < $maxx){
				$this->layout['maxx'] = $maxx;			# maximaler x-Wert für Spaltenanordnung
			}
		}
		$page_id_after_puttext = $this->pdf->currentContents;
		#if($this->user->id == 2)echo $page_id_before_puttext.' '.$page_id_after_puttext.' - '.$y.' - '.$text.'<br>';
		if($page_id_before_puttext != $page_id_after_puttext){
			$this->page_overflow = true; 
			if($this->getNextPage($page_id_before_puttext) != $page_id_after_puttext)$this->pdf->overflow_error = true;		# eine oder mehr Seiten übersprungen -> Fehler
		}
		return $ret;
	}
  
  function substituteFreitext($text, $i, $pagenumber, $pagecount, $preview){
  	$text = str_replace('$stelle', $this->Stelle->Bezeichnung, $text);
  	$text = str_replace('$user', $this->user->Name, $text);
		$text = str_replace('$pagenumber', $pagenumber, $text);
		$text = str_replace('$pagecount', $pagecount, $text);		
		$text = str_replace(';', chr(10), $text);
		if(strpos($text, '${') !== false){
			for($j = 0; $j < count($this->attributes['name']); $j++){
				$value = $this->result[$i][$this->attributes['name'][$j]];
				$text = str_replace('${'.$this->attributes['name'][$j].'}', $this->get_result_value_output($value, $i, $j, $preview), $text);
			}
		}
		if(strpos($text, '$') !== false){
			for($j = 0; $j < count($this->attributes['name']); $j++){
				$value = $this->result[$i][$this->attributes['name'][$j]];
				$text = str_replace('$'.$this->attributes['name'][$j], $this->get_result_value_output($value, $i, $j, $preview), $text);
			}
		}
  	return $text;
  }
  
  function get_result_value_output($value, $i, $j, $preview) {
		# $i ist der result-counter, $j ist der attribute-counter
		if ($value == '') {
			$value = ' ';		# wenns der result-value leer ist, ein Leerzeichen setzen, wegen der relativen Positionierung
		}
		switch ($this->attributes['form_element_type'][$j]) {
			case 'Auswahlfeld' : {
				if (is_array($this->attributes['dependent_options'][$j])) {		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
					$enum = $this->attributes['enum'][$j][$i];
				}
				else{
					$enum = $this->attributes['enum'][$j];
				}
				$output = $enum[$value]['output'] ?: $value;
			}break;
			case 'Autovervollständigungsfeld' : {
				if(count_or_0($this->attributes['enum_output'][$j]) == 0){	
					$output = $value;		# preview
				}	
				else $output = $this->attributes['enum_output'][$j][$i];
			}break;
			case 'Radiobutton' : {
				foreach ($this->attributes['enum'][$j] as $enum_key => $enum) {
					if ($enum_key == $value) {
						$output .= '<box><b> X </b></box>  ';
					}
					else {
						$output .= '<box>    </box>  ';
					}
					$output .= $enum['output'] . '   ';
					if(!$this->attributes['horizontal'][$j] OR (is_numeric($this->attributes['horizontal'][$j]) AND ($e+1) % $this->attributes['horizontal'][$j] == 0)){
						$output .= chr(10).chr(10);
					}
				}
				if (count($this->attributes['enum'][$j]) == 0){	
					$output = $value;
				}			
			}break;			
			case 'Checkbox' : {
				$option = (json_decode($this->attributes['options'][$j]));
				$output = ($value != 'f' ? ($option->print->true != '' ? $option->print->true : 'ja') : ($option->print->false != '' ? $option->print->false : 'nein'));
			} break;
			case 'Zahl': {
				$output = (!$preview? tausenderTrenner($value) : $value);
			} break;			
			default: {
				if(!$preview AND $this->attributes['type'][$j] == 'bool'){
					$value = str_replace('t', "ja", $value);	
					$value = str_replace('f', "nein", $value);
				}
				$output = $value;
			}break;
		}
		return $output;
  }

	/**
	* Für einen ausgewählten Layer wird das übergebene Result-Set nach den Vorgaben des übergebenen Layouts in ein PDF geschrieben
	* Werden $pdfobject, $offsetx und $offsety übergeben, wird kein neues PDF-Objekt erzeugt,
	* sondern das übergebene PDF-Objekt eines übergeordneten Layers+Layout verwendet (eingebettete Layouts)
	* @param ...
	* @param array $result Array von Sachdatenabfrageergebnissen
	* @param ...
	* @return array $return_values Full path to created pdf document if $output is true and else only the last y-value of cursor in page
	*/
	function createDataPDF($pdfobject, $offsetx, $offsety, $layerdb, $layerset, $attributes, $selected_layer_id, $layout, $result, $stelle, $user, $preview = NULL, $record_paging = NULL, $output = true, $append = false ) {
		$result = (!$result ? array() : $result);
		$this->layerset = $layerset[0];
		$this->layout = $layout;
		$this->Stelle = $stelle;
		$this->attributes = $attributes;
		$this->result = $result;
		$this->user = $user;
		$this->maxy = 0;
		$this->offsety = $offsety;
		$this->layout['record_paging'] = $record_paging;
		$this->gui->formvars['record_paging'] = NULL; # damit in untergeordneten Layouts nicht auch nummeriert wird
		#$this->miny = 1000000;
		$this->i_on_page = -1;
		$this->xoffset_onpage = 0;
		$new_column = false;
		$this->page_overflow = false;
		$return_values = array(
			'pdf_file' => '',
			'y' => 0
		);
		if ($pdfobject == NULL) {
			include_once (CLASSPATH . 'class.ezpdf.php');
			$this->pdf=new Cezpdf($this->layout['size'], $this->layout['orientation']);
			$this->pdf->ezSetMargins($this->layout['margin_top'], $this->layout['margin_bottom'], $this->layout['margin_left'], $this->layout['margin_right']);
		}
		else {
			$this->pdf = $pdfobject; # ein PDF-Objekt wurde aus einem übergeordneten Druckrahmen/Layer übergeben
			if ($append) {
				$this->pdf->newPage();
			}
		}
		$this->gui->pdf = $this->pdf;
		$this->miny[$this->pdf->currentContents] = 1000000;
		$this->max_dataset_height = 0;
		if ($this->offsety) {
			$this->miny[$this->pdf->currentContents] = $this->layout['height'] - $this->offsety;
		}
		if ($this->layout['elements'][$attributes['the_geom']]['xpos'] > 0) {
			# wenn ein Geometriebild angezeigt werden soll -> loadmap()
			$this->gui->map_factor = MAPFACTOR;
			$this->gui->loadmap('DataBase');
		}
		$this->add_static_elements($offsetx);
		$layout_with_sublayout = false;
		for ($j = 0; $j < count($this->attributes['name']); $j++) {
			if (
				in_array($this->attributes['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK')) AND
				$this->layout['elements'][$attributes['name'][$j]]['font'] != ''
			) {
				$layout_with_sublayout = true;
			}
		}
		if ($this->layout['columns']) {
			# spaltenweiser Typ
			$rowcount = ceil(count($result) / 3);
		}
		for ($i = 0; $i < count_or_0($result); $i++) {
			if (true AND is_numeric($result[$i][$this->layerset['ddl_attribute']])) {
				$this->layout = $this->load_layouts(NULL, $result[$i][$this->layerset['ddl_attribute']], NULL, array(0,1))[0];
			}
			$lastpage = end($this->pdf->objects['3']['info']['pages']) + 1;
			$this->i_on_page++;
			# beim Untereinander-Typ oder eingebettet-Typ wenn Datensätze nicht durch Seitenumbruch 
			# unterbrochen werden dürfen, eine Transaktion starten um evtl. bei einem Seitenüberlauf zurückkehren zu können
			if ($this->layout['type'] != 0 AND $this->layout['no_record_splitting']) {
				$this->pdf->transaction('start');
				$this->transaction_start_pageid = $this->pdf->currentContents;
				$this->transaction_start_y = $this->miny[$this->pdf->currentContents];
			}
			if ($this->layout['type'] == 0 AND $i > 0) {
				# neue Seite beim seitenweisen Typ und neuem Datensatz
				$this->pdf->newPage();
				$this->add_static_elements($offsetx);
			}
			# spaltenweiser Typ von oben nach unten
			// if($this->layout['columns'] AND $this->i_on_page > 0 AND $this->i_on_page % $rowcount == 0){
				// $this->xoffset_onpage = $this->xoffset_onpage + 170;
				// $this->miny[$lastpage] = $this->maxy;
				// $new_column = true;
			// }
			// else $new_column = false;
			# spaltenweiser Typ von links nach rechts
			if ($this->layout['columns'] AND $this->i_on_page > 0) {
				if (($this->xoffset_onpage + $this->layout['maxx'] - $offsetx + $this->layout['gap'] + 60) > ($this->layout['width'] - $this->layout['margin_right'] - $offsetx)) {
					$this->xoffset_onpage = 0;
					$new_column = false;
					$this->layout['maxx'] = 0;
				}
				else {
					$this->xoffset_onpage = $this->xoffset_onpage + $this->layout['maxx'] - $offsetx + $this->layout['gap'];
					if ($this->yoffset_onpage < 0) {
						$this->yoffset_onpage = 0;
					}
					$new_column = true;
				}
			}
			if (!$new_column AND $this->i_on_page > 0) {
				$this->yoffset_onpage = $this->maxy - $this->miny[$lastpage] + $this->layout['gap']; # der Offset mit dem die Elemente beim Untereinander-Typ nach unten versetzt werden
			}
			if (
				!$new_column AND
				$this->layout['type'] != 0 AND
				$this->miny[$lastpage] != '' AND
				($this->miny[$lastpage] - $this->layout['gap']) < 60
			) {
				# neue Seite beim Untereinander-Typ oder eingebettet-Typ und Seitenüberlauf
				$this->i_on_page = 0;
				#$this->maxy = 0;
				if (!$this->initial_yoffset) {
					# der Offset von oben gesehen, mit dem das erste fortlaufende Element auf der ersten Seite beginnt; wird benutzt,
					# um die fortlaufenden Elemente ab der 2. Seite oben beginnen zu lassen
					$this->initial_yoffset = 780-$this->maxy;
				}
				if ($this->layout['type'] == 2) {
					$this->offsety = $this->pdf->ez['topMargin'];
				}
				else {
					$this->offsety = 0;
				}
				$this->pdf->newPage();
				$lastpage = end($this->pdf->objects['3']['info']['pages']) + 1;
				$this->miny[$lastpage] = 1000000;
			}
			$this->record_startpage = $this->pdf->currentContents; # die Seiten-ID auf der der Datensatz beginnt
			$this->layout['offset_attributes'] = array();
			for ($j = 0; $j < count($this->layout['texts']); $j++) {
				if (
					$i == 0 OR
					$this->layout['type'] != 1 OR
					$this->layout['texts'][$j]['type'] != 1
				) {
					# zu Beginn jedes Datensatzes sind alle Freitexte noch zu schreiben, bei fortlaufenden Layouts aber nur die fortlaufenden Freitexte
					$this->remaining_freetexts[] = $this->layout['texts'][$j]['id'];
				}
			}
			for ($j = 0; $j < count($this->layout['lines']); $j++) {
				if (
					$i == 0 OR
					$this->layout['type'] != 1 OR
					$this->layout['lines'][$j]['type'] != 1
				) {
					# zu Beginn jedes Datensatzes sind alle Linien noch zu schreiben, bei fortlaufenden Layouts aber nur die fortlaufenden Linien
					$this->remaining_lines[] = $this->layout['lines'][$j]['id'];
				}
			}
			for ($j = 0; $j < count($this->layout['rectangles']); $j++) {
				if (
					$i == 0 OR
					$this->layout['type'] != 1 OR
					$this->layout['rectangles'][$j]['type'] != 1
				) {
					# zu Beginn jedes Datensatzes sind alle Rechtecke noch zu schreiben, bei fortlaufenden Layouts aber nur die fortlaufenden Rechtecke
					$this->remaining_rectangles[] = $this->layout['rectangles'][$j]['id'];
				}
			}

			################# fortlaufende Freitexte schreiben ###############
			# (die festen Freitexte werden vor jedem Attribut geschrieben, da ein Attribut zu einem Seitenüberlauf führen können)
			$this->remaining_rectangles = $this->add_rectangles($offsetx, 'running');
			$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, 'running', NULL, NULL, $preview);
			$this->remaining_lines = $this->add_lines($offsetx, 'running');
			################# fortlaufende Freitexte schreiben ###############

			################# Daten schreiben ###############
			for ($j = 0; $j < count($this->attributes['name']); $j++) {
				if ($this->layout['elements'][$attributes['name'][$j]]['ypos'] > 0) {
					# zum Anfang sind alle Attribute noch zu schreiben
					$this->remaining_attributes[$this->attributes['name'][$j]] = $this->attributes['name'][$j];
				}
			}

			$test = 0;
			while ($test < 100 AND count_or_0($this->remaining_attributes) > 0) {
				# übrig sind die, die noch nicht geschrieben wurden, weil sie abhängig sind
				$this->add_attribute_elements($selected_layer_id, $layerdb, $this->attributes, $offsetx, $i, $preview);
				$test++;
			}

			################# Daten schreiben ###############

			#################  feste Freitexte und Linien hinzufügen, falls keine Attribute da sind ##################
			$this->remaining_rectangles = $this->add_rectangles($offsetx, 'fixed'); # feste Rechtecke hinzufügen
			$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, 'fixed', NULL, NULL, $preview);
			$this->remaining_lines = $this->add_lines($offsetx, 'fixed');
			###############################################################################################
			
			################# fortlaufende Freitexte schreiben ###############
			# (die festen Freitexte werden vor jedem Attribut geschrieben, da ein Attribut zu einem Seitenüberlauf führen können)
			$this->remaining_rectangles = $this->add_rectangles($offsetx, 'running');
			$this->remaining_freetexts = $this->add_freetexts($i, $offsetx, 'running', NULL, NULL, $preview);
			$this->remaining_lines = $this->add_lines($offsetx, 'running');
			################# fortlaufende Freitexte schreiben ###############

			if ($this->layout['type'] != 0 AND $this->layout['no_record_splitting']) {
				# Ein listenförmiges Layout hat einen Seitenüberlauf verursacht und das Unterbrechen von Datensätzen ist nicht gewollt. 
				# Deshalb wird bis zum Beginn des letzten Datensatzes zurückgerollt und die Seite vorher umgebrochen, so dass sauber zwischen 2 Datensätzen 
				# und nicht innerhalb eines Datensatzes getrennt wird.
				if ($this->page_overflow != false) {
					$lastpage = end($this->pdf->objects['3']['info']['pages']) + 1;
					if (
						$this->pdf->overflow_error != true AND
						(
							# wenn die Transaktion aber mehr als 2 Seiten umfasst
							$this->getNextPage($this->transaction_start_pageid) != $lastpage OR
							$this->transaction_start_y > $this->miny[$lastpage] - 50
						)
					) {
						# oder insgesamt länger als 1 Seite ist, bringt es nichts auf einer neuen Seite zu beginnen, dann committen
						$this->pdf->transaction('commit');
						$this->page_overflow = false;
					}
					else {
						$this->page_overflow = false;
						$this->pdf->transaction('abort');
						$i--;
						$this->i_on_page = -1;
						#$this->maxy = 0;
						if (!$this->initial_yoffset) {
							# der Offset von oben gesehen, mit dem das erste fortlaufende Element auf der ersten Seite beginnt; wird benutzt,
							# um die fortlaufenden Elemente ab der 2. Seite oben beginnen zu lassen
							$this->initial_yoffset = 780-$this->maxy;
						}
						if ($this->layout['type'] == 2) {
							$this->offsety = $this->pdf->ez['topMargin'];
						}
						else {
							$this->offsety = 0;
						}
						$this->pdf->newPage();
						$lastpage = end($this->pdf->objects['3']['info']['pages']) + 1;
						$this->miny[$lastpage] = 0;
					}
				}
				else {
					$this->pdf->transaction('commit');
				}
			}
			elseif ($this->page_overflow != false) {
				# Ein Sublayout hat einen Seitenüberlauf verursacht.
				$this->page_overflow = false;
				$this->i_on_page = 0;
				if (!$this->initial_yoffset) {
					# der Offset von oben gesehen, mit dem das erste fortlaufende Element auf der ersten Seite beginnt; wird benutzt,
					# um die fortlaufenden Elemente ab der 2. Seite oben beginnen zu lassen
					$this->initial_yoffset = 780-$this->maxy;
				}
				if ($this->layout['type'] == 2) {
					$this->offsety = 50;
				}
				else {
					$this->offsety = 0;
				}
				$this->miny[$lastpage] = 1000000;
				$this->maxy = 800;
			}
			if ($this->layout['record_paging']) {
				if ($this->pdf->last_page_index === NULL) {
					$this->pdf->last_page_index = -1;
				}
				$page_count = count($this->pdf->objects['3']['info']['pages']);
				$this->pdf->record_page_count[] = $page_count - $this->pdf->last_page_index - 1;
				$this->pdf->last_page_index = $page_count - 1;
			}
		}
		if ($output) {
			# nur wenn kein PDF-Objekt aus einem übergeordneten Layer übergeben wurde, PDF erzeugen
			# Freitexte hinzufügen, die auf jeder Seite erscheinen sollen (Seitennummerierung etc.)
			$this->add_everypage_elements($preview);
			$dateipfad = IMAGEPATH;
			$currenttime = date('Y-m-d_H_i_s', time());
			if ($this->layout['filename'] != '') {
				$dateiname = $this->layout['filename'];
				# Attribute
				for ($j = 0; $j < count($this->attributes['name']); $j++) {
					$value = $this->result[0][$this->attributes['name'][$j]];
					$dateiname = str_replace('${'.$this->attributes['name'][$j].'}', $this->get_result_value_output($value, 0, $j, $preview), $dateiname);
				}
				for ($j = 0; $j < count($this->attributes['name']); $j++) {
					$value = $this->result[0][$this->attributes['name'][$j]];
					$dateiname = str_replace('$'.$this->attributes['name'][$j], $this->get_result_value_output($value, 0, $j, $preview), $dateiname);
				}
				# Nutzer
				$dateiname = str_replace('$user', $this->user->Vorname.'_'.$this->user->Name, $dateiname);
				# Stelle
				$dateiname = str_replace('$stelle', $this->Stelle->Bezeichnung, $dateiname);
				# Datum
				$dateiname = str_replace('$date', $currenttime, $dateiname);
				# / ersetzen
				$dateiname = str_replace('/', '_', $dateiname);
			}
			if ($dateiname == '') {
				$dateiname = sonderzeichen_umwandeln($this->user->Name . '-' . $currenttime);
			}
			$dateiname = $dateiname . '.pdf';
			$this->outputfile = $dateiname;
			$fp = fopen($dateipfad . $dateiname, 'wb');
			fwrite($fp, $this->pdf->ezOutput());
			fclose($fp);
			$return_values['pdf_file'] = $dateipfad . $dateiname;
		}
		else {
			# der letzte y-Wert wird zurückgeliefert, um nachfolgende Elemente darunter zu setzen
			$return_values['y'] = $this->miny[$this->pdf->currentContents];
		}
		return $return_values;
	}

	function add_everypage_elements($preview){
		$this->pdf->ezSetMargins(0,0,0,0);
		$pages = $this->pdf->objects['3']['info']['pages'];
		$pagecount = count($pages);
		$record_paging_index = 0;
		$record_page_number = 0;
		for($i = 0; $i < $pagecount; $i++){
			$this->pdf->reopenObject($pages[$i]+1);		# die Page-IDs sind komischerweise alle um 1 größer
			if($this->layout['record_paging']){
				$record_page_count = $this->pdf->record_page_count[$record_paging_index];
				$record_page_number++;
				$this->add_freetexts(0, 0, 'everypage', $record_page_number, $record_page_count, $preview);
				if($record_page_number == $record_page_count){
					$record_paging_index++;		# im Array mit den Seitenzahlen pro Datensatz eins weiter rücken
					$record_page_number = 0;	# und Seitennummer wieder auf 0 setzen
				}
			}
			else{
				$this->add_freetexts(0, 0, 'everypage', $i + 1, $pagecount, $preview);
			}
			$this->add_lines(0, 'everypage');
			$this->add_rectangles(0, 'everypage');			# feste Rechtecke hinzufügen
			if($preview){
				$this->pdf->setLineStyle(0.1,'','',array(9,10));
				$this->pdf->line(0, $this->layout['margin_bottom'], $this->layout['width'], $this->layout['margin_bottom']);
				$this->pdf->line(0, $this->layout['height'] - $this->layout['margin_top'], $this->layout['width'], $this->layout['height'] - $this->layout['margin_top']);
				$this->pdf->line($this->layout['margin_left'], $this->layout['height'], $this->layout['margin_left'], 0);
				$this->pdf->line($this->layout['width'] - $this->layout['margin_right'], $this->layout['height'], $this->layout['width'] - $this->layout['margin_right'], 0);
				$this->pdf->setLineStyle(1, 'square', '', []);
			}
			$this->pdf->closeObject();
		}
	}
	
	function autogenerate_layout($layer_id, $attributes, $stelle_id){
		include (CLASSPATH . 'class.ezpdf.php');
		$formvars['selected_layer_id'] = $layer_id;
		$formvars['name'] = 'AutoLayout_'.date("Y-m-d_G-i-s");
		$formvars['format'] = 'A4 hoch';
		$formvars['type'] = '0';
		$maxy = 842;
		$maxx = 595;
		$fontsize = 11;
		$formvars['margin_top'] = 50;
		$formvars['margin_bottom'] = 50;
		$formvars['margin_left'] = 50;
		$formvars['margin_right'] = 50;
		$this->pdf=new Cezpdf();
		$y = $maxy - $formvars['margin_top'] - $fontsize;
		$x = $formvars['margin_left'];
		if($attributes['group'][0] != ''){
			$x = $x + 7;
			$y = $y - 30;
		}
		$rc = 0;
		for($i = 0; $i < count($attributes['name']); $i++){
			if(!in_array($attributes['form_element_type'][$i], ['Geometrie', 'Dokument'])){
				$attribute_offset_x = 0;
				$attribute_offset_y = 0;
				# Gruppe
				if($attributes['group'][$i] != $attributes['group'][$i-1]){
					$gap = 35;
					# Rechteck um die Gruppe
					$rects[$rc]['breite'] = 0.5;
					$rects[$rc]['posx'] = $formvars['margin_left'];
					$rects[$rc]['posy'] = 20;
					$rects[$rc]['offset_attribute_start'] = $attributes['name'][$last_attribute_index];
					if($rc > 0){
						$rects[$rc-1]['endposx'] = $maxx - $formvars['margin_right'];
						$rects[$rc-1]['endposy'] = 10;
						$rects[$rc-1]['offset_attribute_end'] = $attributes['name'][$last_attribute_index];
					}
					$rc++;
					# Gruppenname als Freitext
					$text['text'] = $attributes['group'][$i];
					$text['posx'] = $x;
					$text['posy'] = 33;
					$text['offset_attribute'] = $attributes['name'][$last_attribute_index];
					$text['font'] = 'Helvetica-Bold.afm';
					$text['size'] = $fontsize;
					$this->pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$text['font']);
					$group_text_width = $this->pdf->getTextWidth($fontsize, $text['text']);
					if($group_text_width > ($maxx - $formvars['margin_left'] - $formvars['margin_right'] - 7)){
						$gap = $gap + 15;
					}
					$groupnames[] = $text;
					# Rechteck um den Gruppennamen
					$rect['breite'] = 0.5;
					$rect['posx'] = $formvars['margin_left'];
					$rect['posy'] = 20;
					$rect['offset_attribute_start'] = $attributes['name'][$last_attribute_index];
					$rect['endposx'] = $maxx - $formvars['margin_right'];
					$rect['endposy'] = 4 + $gap;
					$rect['offset_attribute_end'] = $attributes['name'][$last_attribute_index];
					$groupname_rects[] = $rect;
				}
				else{
					$gap = 0;
				}
				# Attributname als Freitext
				$text['text'] = $attributes['alias'][$i] ?: $attributes['name'][$i];
				$text['posx'] = $x;
				$text['posy'] = 20 + $gap;
				$text['offset_attribute'] = $attributes['name'][$last_attribute_index];
				$text['font'] = 'Helvetica-Bold.afm';
				$text['size'] = $fontsize;
				$this->pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$text['font']);
				$attributename_text_width = $this->pdf->getTextWidth($fontsize, $text['text']);
				if($attributename_text_width > 130){
					$attribute_offset_x = $attributename_text_width - 120;		# Attributname zu lang -> Offset für das Attribut
				}
				if($attributename_text_width > ($maxx - $formvars['margin_left'] - $formvars['margin_right'] - 7)){		# Attributname länger als eine Zeile -> Offsets setzen
					$attribute_offset_x = - 130;
					$attribute_offset_y = 20;
				}
				$attributenames[] = $text;				
				# Attribut
				if($attributes['form_element_type'][$i] == 'Textfeld'){		# mehrzeilige Textfelder immer unter dem Attributnamen platzieren
					$attribute_offset_x = - 130;
					$attribute_offset_y = $attribute_offset_y + 20;
				}
				$formvars['posx_'.$attributes['name'][$i]] = $x + 130 + $attribute_offset_x;
				$formvars['posy_'.$attributes['name'][$i]] = 20 + $gap + $attribute_offset_y;
				$formvars['offset_attribute_'.$attributes['name'][$i]] = $attributes['name'][$last_attribute_index];
				if(!in_array($attributes['form_element_type'][$i], ['SubFormEmbeddedPK', 'SubFormPK'])){
					$formvars['font_'.$attributes['name'][$i]] = 'Helvetica.afm';
				}
				$formvars['fontsize_'.$attributes['name'][$i]] = $fontsize;
				$last_attribute_index = $i;
			}
		}
		$formvars['posy_'.$attributes['name'][0]] = $y;
		$attributenames[0]['posy'] = $y;
		$attributenames[0]['offset_attribute'] = '';
		$groupnames[0]['posy'] = $maxy - $formvars['margin_top'] - $fontsize - 3;
		$groupnames[0]['offset_attribute'] = '';
		$rects[0]['posy'] = $maxy - $formvars['margin_top'];
		$groupname_rects[0]['posy'] = $maxy - $formvars['margin_top'];
		$groupname_rects[0]['endposy'] = $groupname_rects[0]['posy'] - 19;
		$rects[$rc-1]['endposx'] = $maxx - $formvars['margin_right'];
		$rects[$rc-1]['endposy'] = 10;
		$rects[$rc-1]['offset_attribute_end'] = $attributes['name'][$last_attribute_index];
		
		$ddl_id = $this->save_layout($formvars, $attributes, NULL, $stelle_id);
		
		$texts = array_merge($attributenames, $groupnames);
		
		$rects = array_merge($rects, $groupname_rects);
		
		for($t = 0; $t < count($texts); $t++){
			$this->addfreetext($ddl_id, $texts[$t]['text'], $texts[$t]['posx'], $texts[$t]['posy'], $texts[$t]['size'], $texts[$t]['font'], $texts[$t]['offset_attribute']);
		}
		
		for($r = 0; $r < count($rects); $r++){
			$this->addrectangle($ddl_id, $rects[$r]['posx'], $rects[$r]['posy'], $rects[$r]['endposx'], $rects[$r]['endposy'], $rects[$r]['breite'], $rects[$r]['offset_attribute_start'], $rects[$r]['offset_attribute_end']);
		}

		return $ddl_id;
	}

	function save_layout($formvars, $attributes, $_files, $stelle_id){
    if ($formvars['name']) {
    	if ($formvars['font_date'] == 'NULL')$formvars['font_date'] = NULL;
			$sql = "
				INSERT INTO
					`datendrucklayouts`
				SET
					`name` = '" . $formvars['name'] . "',
					`layer_id` = " . (int)$formvars['selected_layer_id'] . ",
					`format` = '" . $formvars['format'] . "'";
			if($formvars['bgposx'] != '')$sql .= ", `bgposx` = ".(int)$formvars['bgposx'];
  		else $sql .= ", `bgposx` = NULL";
      if($formvars['bgposy'] != '')$sql .= ", `bgposy` = ".(int)$formvars['bgposy'];
      else $sql .= ", `bgposy` = NULL";
      if($formvars['bgwidth'] != '')$sql .= ", `bgwidth` = ".(int)$formvars['bgwidth'];
      else $sql .= ", `bgwidth` = NULL";
      if($formvars['bgheight'] != '')$sql .= ", `bgheight` = ".(int)$formvars['bgheight'];
      else $sql .= ", `bgheight` = NULL";
      if($formvars['dateposx'] != '')$sql .= ", `dateposx` = ".(int)$formvars['dateposx'];
      else $sql .= ", `dateposx` = NULL";
      if($formvars['dateposy'] != '')$sql .= ", `dateposy` = ".(int)$formvars['dateposy'];
      else $sql .= ", `dateposy` = NULL";
      if($formvars['datesize'] != '')$sql .= ", `datesize` = ".(int)$formvars['datesize'];
      else $sql .= ", `datesize` = NULL";
      if($formvars['userposx'] != '')$sql .= ", `userposx` = ".(int)$formvars['userposx'];
      else $sql .= ", `userposx` = NULL";
      if($formvars['userposy'] != '')$sql .= ", `userposy` = ".(int)$formvars['userposy'];
      else $sql .= ", `userposy` = NULL";
      if($formvars['usersize'] != '')$sql .= ", `usersize` = ".(int)$formvars['usersize'];
      else $sql .= ", `usersize` = NULL";
      $sql .= ", `font_date` = '".$formvars['font_date']."'";
      $sql .= ", `font_user` = '".$formvars['font_user']."'";
			if($formvars['gap'] != '')$sql .= ", `gap` = ".(int)$formvars['gap'];
      if($formvars['type'] != '')$sql .= ", `type` = ".(int)$formvars['type'];
      else $sql .= ", `type` = NULL";
			$sql .= ", `margin_top` = ".(int)$formvars['margin_top'];
			$sql .= ", `margin_bottom` = ".(int)$formvars['margin_bottom'];
			$sql .= ", `margin_left` = ".(int)$formvars['margin_left'];
			$sql .= ", `margin_right` = ".(int)$formvars['margin_right'];
			$sql .= ", `dont_print_empty` = " . (int)$formvars['dont_print_empty'];
			$sql .= ", `no_record_splitting` = ".(int)$formvars['no_record_splitting'];
			$sql .= ", `use_previews` = " . (int)$formvars['use_previews'];
			$sql .= ", `columns` = ".(int)$formvars['columns'];
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
      $lastddl_id = $this->database->mysqli->insert_id;

      $sql = 'INSERT INTO ddl2stelle (stelle_id, ddl_id) VALUES('.$stelle_id.', '.$lastddl_id.')';
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);

			for ($i = 0; $i < count($attributes['name']); $i++){
				if ($formvars['font_'.$attributes['name'][$i]] == 'NULL') {
					$formvars['font_'.$attributes['name'][$i]] = NULL;
				}
				$sql = "
					REPLACE INTO
						ddl_elemente
					SET
						ddl_id = "						. $lastddl_id . ",
						name = '"							. $attributes['name'][$i] . "',
						xpos = "							. (float)$formvars['posx_'. $attributes['name'][$i]] . ",
						ypos = "							. (float)$formvars['posy_'. $attributes['name'][$i]] . ",
						label = "							. ($formvars['label_'			. $attributes['name'][$i]] ? "'" . $formvars['label_'			. $attributes['name'][$i]] . "'" : 'NULL') . ",
						margin = "						. ($formvars['margin_'		. $attributes['name'][$i]] ? $formvars['margin_'		. $attributes['name'][$i]] : 'NULL') . ",
						offset_attribute = "	. ($formvars['offset_attribute_' . $attributes['name'][$i]] ? "'" . $formvars['offset_attribute_' . $attributes['name'][$i]] . "'" : 'NULL') . ",
						width = "							. ($formvars['width_'			. $attributes['name'][$i]] ? $formvars['width_'			. $attributes['name'][$i]] : 'NULL') . ",
						border = "						. ($formvars['border_'		. $attributes['name'][$i]] ? $formvars['border_'		. $attributes['name'][$i]] : 'NULL') . ",
						font = "							. ($formvars['font_'			. $attributes['name'][$i]] ? "'" . $formvars['font_'			. $attributes['name'][$i]] . "'" : 'NULL') . ",
						fontsize = "					. ($formvars['fontsize_'	. $attributes['name'][$i]] ? $formvars['fontsize_'	. $attributes['name'][$i]] : 'NULL') . "
				";
				#echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
        $this->database->execSQL($sql,4, 1);
			}
			
			$sql = "DELETE FROM ddl_elemente WHERE ((xpos IS NULL AND ypos IS NULL) OR (xpos = 0 AND ypos = 0)) AND ddl_id = ".$lastddl_id;
			#echo $sql;
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < count_or_0($formvars['text']); $i++){
        $formvars['text'][$i] = str_replace(chr(10), ';', $formvars['text'][$i]);
        $formvars['text'][$i] = str_replace(chr(13), '', $formvars['text'][$i]);
        if($formvars['text'][$i] == 'NULL')$formvars['text'][$i] = NULL;
        if($formvars['textfont'][$i] == 'NULL')$formvars['textfont'][$i] = NULL;
        $sql = "INSERT INTO druckfreitexte SET `text` = '".$formvars['text'][$i]."'";
        if($formvars['textposx'][$i] !== NULL)$sql .= ", `posx` = ".(int)$formvars['textposx'][$i];
        else $sql .= ", `posx` = 0";
        if($formvars['textposy'][$i] !== NULL)$sql .= ", `posy` = ".(int)$formvars['textposy'][$i];
        else $sql .= ", `posy` = 0";
				if($formvars['textoffset_attribute'][$i])$sql .= ", `offset_attribute` = '".$formvars['textoffset_attribute'][$i]."'";
        else $sql .= ", `offset_attribute` = NULL";
        if($formvars['textsize'][$i] !== NULL)$sql .= ", `size` = ".(int)$formvars['textsize'][$i];
        else $sql .= ", `size` = 0";
				if($formvars['textwidth'][$i] != NULL)$sql.= " ,width = ".(int)$formvars['textwidth'][$i];
				else $sql.= " ,width = NULL";
				if($formvars['textborder'][$i] != NULL)$sql.= " ,border = ".(int)$formvars['textborder'][$i];
				else $sql.= " ,border = NULL";
        if($formvars['textangle'][$i])$sql .= ", `angle` = ".(int)$formvars['textangle'][$i];
        else $sql .= ", `angle` = NULL";
        $sql .= ", `font` = '".$formvars['textfont'][$i]."'";
        $sql .= ", `type` = '".$formvars['texttype'][$i]."'";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
        $this->database->execSQL($sql,4, 1);
        $lastfreitext_id = $this->database->mysqli->insert_id;

        $sql = 'INSERT INTO ddl2freitexte (ddl_id, freitext_id) VALUES('.$lastddl_id.', '.$lastfreitext_id.')';
        $this->debug->write("<p>file:kvwmap class:ddl->save_layout :",4);
        $this->database->execSQL($sql,4, 1);
      }
			
			for($i = 0; $i < $formvars['linecount']; $i++){
        $sql = "INSERT INTO druckfreilinien SET `breite` = '".$formvars['breite'.$i]."'";
        if($formvars['lineposx'.$i] !== NULL)$sql .= ", `posx` = ".(int)$formvars['lineposx'.$i];
        else $sql .= ", `posx` = NULL";
        if($formvars['lineposy'.$i] !== NULL)$sql .= ", `posy` = ".(int)$formvars['lineposy'.$i];
        else $sql .= ", `posy` = NULL";
				if($formvars['lineendposx'.$i] !== NULL)$sql .= ", `endposx` = ".(int)$formvars['lineendposx'.$i];
        else $sql .= ", `endposx` = NULL";
        if($formvars['lineendposy'.$i] !== NULL)$sql .= ", `endposy` = ".(int)$formvars['lineendposy'.$i];
        else $sql .= ", `endposy` = NULL";
				if($formvars['lineoffset_attribute_start'.$i] !== NULL)$sql .= ", `offset_attribute_start` = '".$formvars['lineoffset_attribute_start'.$i]."'";
        else $sql .= ", `offset_attribute_start` = NULL";
				if($formvars['lineoffset_attribute_end'.$i] !== NULL)$sql .= ", `offset_attribute_end` = '".$formvars['lineoffset_attribute_end'.$i]."'";
        else $sql .= ", `offset_attribute_end` = NULL";
        if($formvars['linetype'.$i] == '')$formvars['linetype'.$i] = 0;
        $sql .= ", `type` = '".$formvars['linetype'.$i]."'";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->save_layout :",4);
        $this->database->execSQL($sql,4, 1);
        $lastline_id = $this->database->mysqli->insert_id;
				
				$sql = 'INSERT INTO ddl2freilinien (ddl_id, line_id) VALUES('.$lastddl_id.', '.$lastline_id.')';
        $this->debug->write("<p>file:kvwmap class:ddl->save_layout :",4);
        $this->database->execSQL($sql,4, 1);
      }
			
			for($i = 0; $i < $formvars['rectcount']; $i++){
        $sql = "INSERT INTO druckfreirechtecke SET `breite` = '".$formvars['rectbreite'.$i]."'";
        if($formvars['rectposx'.$i] !== NULL)$sql .= ", `posx` = ".(int)$formvars['rectposx'.$i];
        else $sql .= ", `posx` = NULL";
        if($formvars['rectposy'.$i] !== NULL)$sql .= ", `posy` = ".(int)$formvars['rectposy'.$i];
        else $sql .= ", `posy` = NULL";
				if($formvars['rectendposx'.$i] !== NULL)$sql .= ", `endposx` = ".(int)$formvars['rectendposx'.$i];
        else $sql .= ", `endposx` = NULL";
        if($formvars['rectendposy'.$i] !== NULL)$sql .= ", `endposy` = ".(int)$formvars['rectendposy'.$i];
        else $sql .= ", `endposy` = NULL";
				if($formvars['rectoffset_attribute_start'.$i] !== NULL)$sql .= ", `offset_attribute_start` = '".$formvars['rectoffset_attribute_start'.$i]."'";
        else $sql .= ", `offset_attribute_start` = NULL";
				if($formvars['rectoffset_attribute_end'.$i] !== NULL)$sql .= ", `offset_attribute_end` = '".$formvars['rectoffset_attribute_end'.$i]."'";
        else $sql .= ", `offset_attribute_end` = NULL";
        if($formvars['recttype'.$i] == '')$formvars['recttype'.$i] = 0;
				if($formvars['rectcolor'.$i])$sql .= ", `color` = '".$formvars['rectcolor'.$i]."'";
				else $sql .= ", `color` = NULL";
				$sql .= ", `type` = '".$formvars['recttype'.$i]."'";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->save_layout :",4);
        $this->database->execSQL($sql,4, 1);
        $lastrect_id = $this->database->mysqli->insert_id;
				
				$sql = 'INSERT INTO ddl2freirechtecke (ddl_id, rect_id) VALUES('.$lastddl_id.', '.$lastrect_id.')';
        $this->debug->write("<p>file:kvwmap class:ddl->save_layout :",4);
        $this->database->execSQL($sql,4, 1);
      }			
    }
    return $lastddl_id;
  } 
  
  function update_layout($formvars, $attributes, $_files){
  	$_files = $_FILES;
    if ($formvars['name']){
    	if ($formvars['font_date'] == 'NULL')$formvars['font_date'] = NULL;
				$sql = "
					UPDATE
						`datendrucklayouts`
					SET
						`name` = '".$formvars['name'] . "',
						`layer_id` = " . (int)$formvars['selected_layer_id'] . ",
						`format` = '" . $formvars['format'] . "'
				";
  		if($formvars['bgposx'] != '')$sql .= ", `bgposx` = ".(int)$formvars['bgposx'];
  		else $sql .= ", `bgposx` = NULL";
      if($formvars['bgposy'] != '')$sql .= ", `bgposy` = ".(int)$formvars['bgposy'];
      else $sql .= ", `bgposy` = NULL";
      if($formvars['bgwidth'] != '')$sql .= ", `bgwidth` = ".(int)$formvars['bgwidth'];
      else $sql .= ", `bgwidth` = NULL";
      if($formvars['bgheight'] != '')$sql .= ", `bgheight` = ".(int)$formvars['bgheight'];
      else $sql .= ", `bgheight` = NULL";
      if($formvars['dateposx'] != '')$sql .= ", `dateposx` = ".(int)$formvars['dateposx'];
      else $sql .= ", `dateposx` = NULL";
      if($formvars['dateposy'] != '')$sql .= ", `dateposy` = ".(int)$formvars['dateposy'];
      else $sql .= ", `dateposy` = NULL";
      if($formvars['datesize'] != '')$sql .= ", `datesize` = ".(int)$formvars['datesize'];
      else $sql .= ", `datesize` = NULL";
      if($formvars['userposx'] != '')$sql .= ", `userposx` = ".(int)$formvars['userposx'];
      else $sql .= ", `userposx` = NULL";
      if($formvars['userposy'] != '')$sql .= ", `userposy` = ".(int)$formvars['userposy'];
      else $sql .= ", `userposy` = NULL";
      if($formvars['usersize'] != '')$sql .= ", `usersize` = ".(int)$formvars['usersize'];
      else $sql .= ", `usersize` = NULL";
      $sql .= ", `font_date` = '".$formvars['font_date']."'";
      $sql .= ", `font_user` = '".$formvars['font_user']."'";
			$sql .= ", `gap` = ".(int)$formvars['gap'];
      if($formvars['type'] != '')$sql .= ", `type` = ".(int)$formvars['type'];
      else $sql .= ", `type` = NULL";
			$sql .= ", `margin_top` = ".(int)$formvars['margin_top'];
			$sql .= ", `margin_bottom` = ".(int)$formvars['margin_bottom'];
			$sql .= ", `margin_left` = ".(int)$formvars['margin_left'];
			$sql .= ", `margin_right` = ".(int)$formvars['margin_right'];
			$sql .= ", `dont_print_empty` = " . (int)$formvars['dont_print_empty'];
			$sql .= ", `no_record_splitting` = ".(int)$formvars['no_record_splitting'];
			$sql .= ", `use_previews` = ".(int)$formvars['use_previews'];
			$sql .= ", `columns` = ".(int)$formvars['columns'];
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
      $lastddl_id = $this->database->mysqli->insert_id;

			for($i = 0; $i < count($attributes['name']); $i++){
				$sql = "
					REPLACE INTO
						ddl_elemente
					SET
						ddl_id = "						. (int)$formvars['aktivesLayout'] . ",
						name = '"							. $attributes['name'][$i] . "',
						xpos = "							. (float)$formvars['posx_'. $attributes['name'][$i]] . ",
						ypos = "							. (float)$formvars['posy_'. $attributes['name'][$i]] . ",
						label = "							. ($formvars['label_'			. $attributes['name'][$i]] ? "'" . $formvars['label_'			. $attributes['name'][$i]] . "'" : 'NULL') . ",
						margin = "						. ($formvars['margin_'		. $attributes['name'][$i]] ? $formvars['margin_'		. $attributes['name'][$i]] : 'NULL') . ",
						offset_attribute = "	. ($formvars['offset_attribute_' . $attributes['name'][$i]] ? "'" . $formvars['offset_attribute_' . $attributes['name'][$i]] . "'" : 'NULL') . ",
						width = "							. ($formvars['width_'			. $attributes['name'][$i]] ? $formvars['width_'			. $attributes['name'][$i]] : 'NULL') . ",
						border = "						. ($formvars['border_'		. $attributes['name'][$i]] ? $formvars['border_'		. $attributes['name'][$i]] : 'NULL') . ",
						font = "							. ($formvars['font_'			. $attributes['name'][$i]] ? "'" . $formvars['font_'			. $attributes['name'][$i]] . "'" : 'NULL') . ",
						fontsize = "					. ($formvars['fontsize_'	. $attributes['name'][$i]] ? $formvars['fontsize_'	. $attributes['name'][$i]] : 'NULL') . "
				";
				#echo $sql;
				$this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
				$this->database->execSQL($sql,4, 1);
			}
			$sql = "DELETE FROM ddl_elemente WHERE ((xpos IS NULL AND ypos IS NULL) OR (xpos = 0 AND ypos = 0)) AND ddl_id = ".(int)$formvars['aktivesLayout'];
			#echo $sql;
      $this->debug->write("<p>file:kvwmap class:ddl->save_ddl :",4);
      $this->database->execSQL($sql,4, 1);

      for ($i = 0; $i < count_or_0($formvars['text']); $i++){
        $formvars['text'][$i] = str_replace(chr(10), ';', $formvars['text'][$i]);
        $formvars['text'][$i] = str_replace(chr(13), '', $formvars['text'][$i]);
        if($formvars['text'][$i] == 'NULL')$formvars['text'][$i] = NULL;
        if($formvars['textfont'][$i] == 'NULL')$formvars['textfont'][$i] = NULL;
        $sql = "UPDATE druckfreitexte SET `text` = '".$formvars['text'][$i]."'";
        if($formvars['textposx'][$i] != '')$sql .= ", `posx` = ".(int)$formvars['textposx'][$i];
        else $sql .= ", `posx` = NULL";
        if($formvars['textposy'][$i] != '')$sql .= ", `posy` = ".(int)$formvars['textposy'][$i];
        else $sql .= ", `posy` = NULL";
				if($formvars['textoffset_attribute'][$i] != '')$sql .= ", `offset_attribute` = '".$formvars['textoffset_attribute'][$i]."'";
        else $sql .= ", `offset_attribute` = NULL";
        if($formvars['textsize'][$i] != '')$sql .= ", `size` = ".(int)$formvars['textsize'][$i];
        else $sql .= ", `size` = NULL";
				if($formvars['textwidth'][$i] != NULL)$sql.= " ,width = ".(int)$formvars['textwidth'][$i];
				else $sql.= " ,width = NULL";
				if($formvars['textborder'][$i] != NULL)$sql.= " ,border = ".(int)$formvars['textborder'][$i];
				else $sql.= " ,border = NULL";
        if($formvars['textangle'][$i] != '')$sql .= ", `angle` = ".(int)$formvars['textangle'][$i];
        else $sql .= ", `angle` = NULL";
        $sql .= ", `font` = '".$formvars['textfont'][$i]."'";
        if($formvars['texttype'][$i] == '')$formvars['texttype'][$i] = 0;
        $sql .= ", `type` = '".$formvars['texttype'][$i]."'";
        $sql .= " WHERE id = ".(int)$formvars['text_id'][$i];
        #echo $sql.'<br>';
        $this->debug->write("<p>file:kvwmap class:ddl->update_layout :",4);
        $this->database->execSQL($sql,4, 1);
        $lastfreitext_id = $this->database->mysqli->insert_id;
      }
			
      for($i = 0; $i < $formvars['linecount']; $i++){
        $sql = "UPDATE druckfreilinien SET `breite` = '".$formvars['breite'.$i]."'";
        if($formvars['lineposx'.$i] != '')$sql .= ", `posx` = ".(int)$formvars['lineposx'.$i];
        else $sql .= ", `posx` = NULL";
        if($formvars['lineposy'.$i] != '')$sql .= ", `posy` = ".(int)$formvars['lineposy'.$i];
        else $sql .= ", `posy` = NULL";
				if($formvars['lineendposx'.$i] != '')$sql .= ", `endposx` = ".(int)$formvars['lineendposx'.$i];
        else $sql .= ", `endposx` = NULL";
        if($formvars['lineendposy'.$i] != '')$sql .= ", `endposy` = ".(int)$formvars['lineendposy'.$i];
        else $sql .= ", `endposy` = NULL";
				if($formvars['lineoffset_attribute_start'.$i] != '')$sql .= ", `offset_attribute_start` = '".$formvars['lineoffset_attribute_start'.$i]."'";
        else $sql .= ", `offset_attribute_start` = NULL";
				if($formvars['lineoffset_attribute_end'.$i] != '')$sql .= ", `offset_attribute_end` = '".$formvars['lineoffset_attribute_end'.$i]."'";
        else $sql .= ", `offset_attribute_end` = NULL";
        if($formvars['linetype'.$i] == '')$formvars['linetype'.$i] = 0;
        $sql .= ", `type` = '".$formvars['linetype'.$i]."'";
        $sql .= " WHERE id = ".(int)$formvars['line_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->update_layout :",4);
        $this->database->execSQL($sql,4, 1);
        $lastline_id = $this->database->mysqli->insert_id;
      }
			
      for($i = 0; $i < $formvars['rectcount']; $i++){
        $sql = "UPDATE druckfreirechtecke SET `breite` = '".$formvars['rectbreite'.$i]."'";
        if($formvars['rectposx'.$i] != '')$sql .= ", `posx` = ".(int)$formvars['rectposx'.$i];
        else $sql .= ", `posx` = NULL";
        if($formvars['rectposy'.$i] != '')$sql .= ", `posy` = ".(int)$formvars['rectposy'.$i];
        else $sql .= ", `posy` = NULL";
				if($formvars['rectendposx'.$i] != '')$sql .= ", `endposx` = ".(int)$formvars['rectendposx'.$i];
        else $sql .= ", `endposx` = NULL";
        if($formvars['rectendposy'.$i] != '')$sql .= ", `endposy` = ".(int)$formvars['rectendposy'.$i];
        else $sql .= ", `endposy` = NULL";
				if($formvars['rectoffset_attribute_start'.$i] != '')$sql .= ", `offset_attribute_start` = '".$formvars['rectoffset_attribute_start'.$i]."'";
        else $sql .= ", `offset_attribute_start` = NULL";
				if($formvars['rectoffset_attribute_end'.$i] != '')$sql .= ", `offset_attribute_end` = '".$formvars['rectoffset_attribute_end'.$i]."'";
        else $sql .= ", `offset_attribute_end` = NULL";
        if($formvars['recttype'.$i] == '')$formvars['recttype'.$i] = 0;
				if($formvars['rectcolor'.$i] != '')$sql .= ", `color` = '".$formvars['rectcolor'.$i]."'";
				else $sql .= ", `color` = NULL";
        $sql .= ", `type` = '".$formvars['recttype'.$i]."'";
        $sql .= " WHERE id = ".(int)$formvars['rect_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:ddl->update_layout :",4);
        $this->database->execSQL($sql,4, 1);
        $lastrect_id = $this->database->mysqli->insert_id;
      }			
    }
  }

	function load_layouts($stelle_id, $ddl_id, $layer_id, $types = array(), $return = '') {
		#echo '<br>load_layouts with stelle_id ' . $stelle_id. ', ddl_id: ' . $ddl_id . ', layer_id: ' . $layer_id . ', types: ' . implode(', ', $types) . ', return: ' . $return;
		$layouts = array();

/* sql Bildung vorher
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
*/

/* SQL einfach korrigiert
		$sql = "
			SELECT DISTINCT
				datendrucklayouts.*
			FROM
				datendrucklayouts
		";
		$where = false;
    if($ddl_id AND !$stelle_id) {
			$where = true;
			$sql .= ' WHERE datendrucklayouts.id ='.$ddl_id;
		}
    if($stelle_id AND !$layer_id AND !$ddl_id){
			$where = true;
    	$sql.= ', ddl2stelle WHERE ddl2stelle.ddl_id = datendrucklayouts.id';
    	$sql .= ' AND ddl2stelle.stelle_id = '.$stelle_id;
    }
    if($ddl_id AND $stelle_id){
			$where = true;
    	$sql.= ', ddl2stelle WHERE ddl2stelle.ddl_id = '.$ddl_id;
    	$sql .= ' AND ddl2stelle.stelle_id = '.$stelle_id;
    	$sql .= ' AND datendrucklayouts.id ='.$ddl_id;
    }
    if($layer_id AND !$stelle_id){
			$where = true;
    	$sql .= ' WHERE layer_id = '.$layer_id;
    }
    if($layer_id AND $stelle_id){
			$where = true;
    	$sql.= ', ddl2stelle WHERE ddl2stelle.ddl_id = datendrucklayouts.id';
    	$sql .= ' AND layer_id = '.$layer_id;
    	$sql .= ' AND ddl2stelle.stelle_id = '.$stelle_id;
    }
		if($types != NULL){
			if ($where) {
				$sql .= ' AND ';
			}
			else {
				$sql .= ' WHERE ';
			}
			$sql .= 'type IN ('.implode(',', $types).')';
		}
    $sql .= ' ORDER BY layer_id, name';
*/
		# korrigiert und reduziert
		$where_clauses = array();
		if ($ddl_id) {
			$where_clauses[] = 'd.id = ' . $ddl_id;
		}
		if ($layer_id) {
			$where_clauses[] = "(d.layer_id = " . $layer_id . " OR d.layer_id = l.duplicate_from_layer_id)";
		}
		if ($types != NULL) {
			$where_clauses[] = "d.type IN (" . implode(", ", $types) . ")";
		}
		if ($stelle_id) {
			$where_clauses[] = 'd2s.stelle_id = ' . $stelle_id;
		}
		$sql = "
			SELECT DISTINCT
				d.*
			FROM
				datendrucklayouts d LEFT JOIN
				ddl2stelle d2s ON d.id = d2s.ddl_id
				" . ($layer_id ? 'LEFT JOIN layer l ON l.Layer_ID = ' . $layer_id : '') . "
			" . (!empty($where_clauses)? ' WHERE ' : '') . "
				" . implode(" AND ", $where_clauses) . "
			ORDER BY
				layer_id,
				name
		";

		#echo '<br>SQL zur Abfrage von Datendrucklayouts: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:ddl->load_layouts :<br>" . $sql, 4);
		$ret1 = $this->database->execSQL($sql, 4, 1);
		if ($ret1[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$i = 0;
		$result = $this->database->result;
		while ($rs = $result->fetch_assoc()) {
			if ($return == 'only_ids') {
				$layouts[] = $rs['id'];
			}
			else {
				$rs['width'] = $this->din_formats[$rs['format']]['width'];
				$rs['height'] = $this->din_formats[$rs['format']]['height'];
				$rs['size'] = $this->din_formats[$rs['format']]['size'];
				$rs['orientation'] = $this->din_formats[$rs['format']]['orientation'];
				$layouts[] = $rs;
				#$layouts[0]['bilder'] = $this->load_bilder($rs['id']);
				$layouts[0]['elements'] = $this->load_elements($rs['id']);
				$layouts[0]['texts'] = $this->load_texts($rs['id']);
				$layouts[0]['lines'] = $this->load_lines($rs['id']);
				$layouts[0]['rectangles'] = $this->load_rectangles($rs['id']);
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
		$elements = array();
    $sql = 'SELECT * FROM ddl_elemente';
    $sql.= ' WHERE ddl_elemente.ddl_id = '.$ddl_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:ddl->load_elements :<br>".$sql,4);
    $ret1 = $this->database->execSQL($sql, 4, 1);
    if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = $this->database->result->fetch_assoc()){
      $elements[$rs['name']] = $rs;
    }
    return $elements;
  }

	function load_texts($ddl_id, $freetext_id = NULL) {
		$texts = array();
		$sql = "
			SELECT
				t.*
			FROM
				druckfreitexte t JOIN
				ddl2freitexte d2t ON t.id = d2t.freitext_id
			WHERE
				d2t.ddl_id = " . $ddl_id . "
				" . ($freetext_id != NULL ? " AND t.id = " . $freetext_id : '') . "
		";
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:ddl->load_texts :<br>" . $sql, 4);
		$ret1 = $this->database->execSQL($sql, 4, 1);
		if ($ret1[0]) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__, 4);
			return 0;
		}
		while ($rs = $this->database->result->fetch_assoc()) {
			$texts[] = $rs;
		}
		return $texts;
	}

	function output_freetext_form($texts, $layer_id, $ddl_id){
		for($i = 0; $i < count_or_0($texts); $i++){
			$texts[$i]['text'] = str_replace(';', chr(10), $texts[$i]['text']);
			echo '
			<tr>
				<td style="border-top:2px solid #C3C7C3">&nbsp;x:</td>
				<td style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="textposx[]" value="'.$texts[$i]['posx'].'" size="5"></td>						
				<td rowspan="4" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan=3>
					<textarea name="text[]" cols="37" rows="6">'.$texts[$i]['text'].'</textarea>
				</td>
				<td style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3">
					Breite:&nbsp;<input type="text" name="textwidth[]" value="'.$texts[$i]['width'].'" size="2">
				</td>
				<td style="border-top:2px solid #C3C7C3;" colspan=2 align="left">
					<input type="text" onmouseenter="show_select(this, \'fonts\')" name="textfont[]" value="'.$texts[$i]['font'].'">
				</td>
			</tr>
			<tr>
				<td>&nbsp;y:</td>
				<td style="border-right:1px solid #C3C7C3"><input type="text" name="textposy[]" value="'.$texts[$i]['posy'].'" size="5"><input type="hidden" name="text_id[]" value="'.$texts[$i]['id'].'"></td>
				<td style="border-right:1px solid #C3C7C3">
					Rahmen:
					<input type="hidden" name="textborder[]" value="'.$texts[$i]['border'].'"><input type="checkbox" onclick="if(this.checked){this.previousSibling.value=1;}else{this.previousSibling.value=0;}" '.(($texts[$i]['border'] == '1') ? 'checked="true"' : '').'>
				</td>
				<td colspan="2"><input type="text" title="Schriftgröße" name="textsize[]" value="'.$texts[$i]['size'].'" size="5">&nbsp;pt</td>
			</tr>
			<tr>
				<td colspan="2" valign="bottom" style="border-right:1px solid #C3C7C3">&nbsp;unterhalb&nbsp;von:</td>
				<td style="border-right:1px solid #C3C7C3"></td>
				<td colspan="2" valign="bottom">&nbsp;Platzierung:</td>
			</tr>
			<tr>
				<td colspan="2" valign="top" style="border-right:1px solid #C3C7C3">
					<input type="text" onmouseenter="show_select(this, \'attributes\')" name="textoffset_attribute[]" value="'.$texts[$i]['offset_attribute'].'">
				</td>
				<td style="border-right:1px solid #C3C7C3"></td>
				<td align="left" valign="top">
					<select style="width: 110px" name="texttype[]">
						<option value="0">normal</option>';
						echo '<option value="1" '; if($texts[$i]['type'] == 1)echo ' selected '; echo '>fixiert</option>';
						echo '<option value="2" '; if($texts[$i]['type'] == 2)echo ' selected '; echo '>auf jeder Seite</option>';
						echo '<option value="3" '; if($texts[$i]['type'] == 3)echo ' selected '; echo '>ab der 2. Seite auf jeder Seite</option>
					</select>
				</td>
				<td align="right">
					<a href="javascript:Bestaetigung(\'index.php?go=sachdaten_druck_editor_Freitextloeschen&freitext_id=' . $texts[$i]['id'] . '&selected_layer_id=' . $layer_id . '&aktivesLayout=' . $ddl_id . '&csrf_token=' . $_SESSION['csrf_token'] . '\', \'Wollen Sie den Freitext wirklich löschen?\');">löschen&nbsp;</a>
				</td>
			</tr>';
		}
	}

	function load_lines($ddl_id){
		$lines = array();
		$sql = "
			SELECT
				druckfreilinien.*
			FROM
				druckfreilinien, ddl2freilinien
			WHERE
				ddl2freilinien.ddl_id = " . $ddl_id . " AND
				ddl2freilinien.line_id = druckfreilinien.id
		";
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:ddl->load_lines :<br>".$sql,4);
		$ret1 = $this->database->execSQL($sql, 4, 1);
		if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = $this->database->result->fetch_assoc()){
			$lines[] = $rs;
		}
		return $lines;
	}

  function load_rectangles($ddl_id){
		$rects = array();
    $sql = 'SELECT druckfreirechtecke.* FROM druckfreirechtecke, ddl2freirechtecke';
    $sql.= ' WHERE ddl2freirechtecke.ddl_id = '.$ddl_id;
    $sql.= ' AND ddl2freirechtecke.rect_id = druckfreirechtecke.id';
    #echo $sql.'<br>';
    $this->debug->write("<p>file:kvwmap class:ddl->load_rectangles :<br>".$sql,4);
    $ret1 = $this->database->execSQL($sql, 4, 1);
    if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = $this->database->result->fetch_assoc()){
      $rects[] = $rs;
    }
    return $rects;
  }		
  
  function addfreetext($ddl_id, $text, $posx, $posy, $size, $font, $offset_attribute){		
    $sql = 'INSERT INTO druckfreitexte SET';
    $sql .= ' text = "'.$text.'",';
    $sql .= ' posx = '.$posx.',';
    $sql .= ' posy = '.$posy.',';
    $sql .= ' size = '.$size.',';
    $sql .= ' font = "'.$font.'",';
    $sql .= ' angle = 0';
		if($offset_attribute){
			$sql .= ", `offset_attribute` = '".$offset_attribute."'";
		}
    else{
			$sql .= ", `offset_attribute` = NULL";
		}
    $this->debug->write("<p>file:kvwmap class:ddl->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $lastinsert_id = $this->database->mysqli->insert_id;
    $sql = 'INSERT INTO ddl2freitexte (ddl_id, freitext_id) VALUES ('.$ddl_id.', '.$lastinsert_id.')';
    $this->debug->write("<p>file:kvwmap class:ddl->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
		return $lastinsert_id;
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
    $lastinsert_id = $this->database->mysqli->insert_id;
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
	
  function addrectangle($ddl_id, $posx, $posy, $endposx, $endposy, $breite, $offset_attribute_start, $offset_attribute_end){
    $sql = 'INSERT INTO druckfreirechtecke SET';
    $sql .= ' posx = '.$posx.',';
    $sql .= ' posy = '.$posy.',';
		$sql .= ' endposx = '.$endposx.',';
    $sql .= ' endposy = '.$endposy.',';
    $sql .= ' breite = '.$breite;
		if($offset_attribute_start){
			$sql .= ", `offset_attribute_start` = '".$offset_attribute_start."'";
		}
    else{
			$sql .= ", `offset_attribute_start` = NULL";
		}
		if($offset_attribute_end){
			$sql .= ", `offset_attribute_end` = '".$offset_attribute_end."'";
		}
    else{
			$sql .= ", `offset_attribute_end` = NULL";
		}		
		#echo $sql.'<br>';
    $this->debug->write("<p>file:kvwmap class:ddl->addrectangle :",4);
    $this->database->execSQL($sql,4, 1);
    $lastinsert_id = $this->database->mysqli->insert_id;
    $sql = 'INSERT INTO ddl2freirechtecke (ddl_id, rect_id) VALUES ('.$ddl_id.', '.$lastinsert_id.')';
    $this->debug->write("<p>file:kvwmap class:ddl->addline :",4);
    $this->database->execSQL($sql,4, 1);
  }
  
  function removerectangle($formvars){
    $sql = 'DELETE FROM druckfreirechtecke WHERE id = '.$formvars['rect_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->removerectangle :",4);
    $this->database->execSQL($sql,4, 1);
    $sql = 'DELETE FROM ddl2freirechtecke WHERE rect_id = '.$formvars['rect_id'];
    $this->debug->write("<p>file:kvwmap class:ddl->removerectangle :",4);
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

}
?>
