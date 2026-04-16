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
# Klasse drucklayout #
#############################

class drucklayout {
	public $debug;
	public $database;
	public $din_formats;
	public $colors;
	public $pdf;

	function __construct($database) {
    global $debug;
    $this->debug = $debug;
		$this->database = $database;
		$this->colors = $this->read_colors();
  }

	function set_pdf_color($color_id){
		$this->pdf->setColor($this->colors[$color_id]['red']/255, $this->colors[$color_id]['green']/255, $this->colors[$color_id]['blue']/255);
	}

	function get_fonts() {
		$font_files = searchdir(PDF_FONT_PATH, true);
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
	
	function read_colors(){
		$sql = "SELECT * FROM kvwmap.ddl_colors";
  	#echo $sql;
  	$ret = $this->database->execSQL($sql, 4, 0);
    if($ret[0]==0){
			while ($row = pg_fetch_assoc($ret[1])){
				$row['style'] = 'background-color: rgb(' . $row['red'] . ', ' . $row['green'] . ', ' . $row['blue'] . ')';
				$row['value'] = $row['id'];
				$row['output'] = '';
				$colors[$row['id']] = $row;
      }
    }
    return $colors;
  }

	function output_color_select($fieldname, $value){
		return FormObject::createCustomSelectField(
						$fieldname,																					# name
						$this->colors,																			# options
						$value,																							# value
						1,																									# size
						'width: 80px',																			# style
						'',																									# onchange
						$fieldname,																					# id
						'',																									# multiple
						'',																									# class
						' ',																								# firstoption
						'min-width: 80px'																		# optionstyle
					);
}

}
	