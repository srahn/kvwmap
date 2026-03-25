<?

include_once(CLASSPATH.'drucklayout.php');

class kdl extends drucklayout{
  var $html;
  var $debug;
  var $head;
  var $headquery;

  ###################### Liste der Funktionen ####################################
  #
  # function kdl() - Construktor
  # function load_heads()
  # function load_head($headid)
  # function save_head($formvars)
  # function update_head($formvars)
  # function save_active_head($id,$userid, $stelleid)
  # function get_active_headid($userid, $stelleid)
  # function get_head($userid, $stelleid)
  #
  ################################################################################

  function __construct($database){
    parent::__construct($database);
  }

	function delete_ausschnitt($stelle_id, $user_id, $id) {
		$sql = "
			DELETE FROM
				kvwmap.druckausschnitte
			WHERE
				stelle_id = " . $stelle_id . " AND
				user_id = " . $user_id . "
				" . ($id != '' ? " AND id = " . $id : '') . "
		";
		$this->debug->write("<p>file:kvwmap class:Document->delete_ausschnitt :",4);
		$this->database->execSQL($sql,4, 1);
	}

	function save_ausschnitt($stelle_id, $user_id, $name, $epsg_code, $center_x, $center_y, $print_scale, $angle, $frame_id){
		$sql = "
			INSERT INTO
				kvwmap.druckausschnitte
			VALUES (
				" . $stelle_id . ",
				" . $user_id . ",
				COALESCE(
					(
						SELECT
							new_id
						FROM
						(
							SELECT
								max(id) + 1 AS new_id
							FROM
								kvwmap.druckausschnitte
							WHERE
								stelle_id = " . $stelle_id . " AND
								user_id = " . $user_id . "
						) as foo
					),
					1
				),
				'" . $name . "',
				'" . $epsg_code . "',
				" . $center_x . ",
				" . $center_y . ",
				" . $print_scale . ",
				" . $angle . ",
				" . $frame_id . "
			)
		";
		$this->debug->write("<p>file:kvwmap class:Document->save_ausschnitt :",4);
		$this->database->execSQL($sql,4, 1);
	}

  function load_ausschnitte($stelle_id, $user_id, $id){
    $sql = 'SELECT * FROM kvwmap.druckausschnitte WHERE ';
    $sql.= 'stelle_id = '.$stelle_id.' AND ';
    $sql.= 'user_id = '.$user_id;
    if($id != ''){
      $sql.= ' AND id = '.$id;
    }
    $this->debug->write("<p>file:kvwmap class:Document->load_ausschnitte :<br>" . $sql,4);
		$ret1 = $this->database->execSQL($sql, 4, 1);
		if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = pg_fetch_assoc($ret1[1])){
      $ausschnitte[] = $rs;
    }
    return $ausschnitte;
  }

  function load_frames($stelle_id, $frameid, $return = '') {
		$frames = array();
    $sql = 'SELECT DISTINCT druckrahmen.* FROM kvwmap.druckrahmen';
    if($frameid AND !$stelle_id){$sql .= ' WHERE druckrahmen.id ='.$frameid;}
    if($stelle_id AND !$frameid){
    	$sql.= ', kvwmap.druckrahmen2stelle WHERE druckrahmen2stelle.druckrahmen_id = druckrahmen.id';
    	$sql .= ' AND druckrahmen2stelle.stelle_id = '.$stelle_id;
    }
    if($frameid AND $stelle_id){
    	$sql.= ', kvwmap.druckrahmen2stelle WHERE druckrahmen2stelle.druckrahmen_id = druckrahmen.id';
    	$sql .= ' AND druckrahmen2stelle.stelle_id = '.$stelle_id;
    	$sql .= ' AND druckrahmen.id ='.$frameid;
    }
    $sql .= ' ORDER BY name';
    #echo $sql.'<br>';
		$ret1 = $this->database->execSQL($sql, 4, 1);
  	if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = pg_fetch_assoc($ret1[1])){
			if ($return == 'only_ids') {
				$frames[] = $rs['id'];
			}
			else {
	      $frames[] = $rs;
	      $frames[0]['bilder'] = $this->load_bilder($rs['id']);
	      $frames[0]['texts'] = $this->load_texts($rs['id']);
			}
    }
    return $frames;
  }

  function load_texts($frame_id){
		$texts = array();
    $sql = 'SELECT druckfreitexte.* FROM kvwmap.druckrahmen, kvwmap.druckfreitexte, kvwmap.druckrahmen2freitexte';
    $sql.= ' WHERE druckrahmen2freitexte.druckrahmen_id = '.$frame_id;
    $sql.= ' AND druckrahmen2freitexte.druckrahmen_id = druckrahmen.id';
    $sql.= ' AND druckrahmen2freitexte.freitext_id = druckfreitexte.id';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->load_texts :<br>" . $sql,4);
    $ret1 = $this->database->execSQL($sql, 4, 1);
    if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = pg_fetch_assoc($ret1[1])){
      $texts[] = $rs;
    }
    return $texts;
  }

  function load_bilder($frame_id){
		$bilder = array();
    $sql = 'SELECT b.src,r2b.posx,r2b.posy,r2b.width,r2b.height,r2b.angle';
    $sql.= ' FROM kvwmap.druckrahmen AS r, kvwmap.druckfreibilder AS b, kvwmap.druckrahmen2freibilder AS r2b';
    $sql.= ' WHERE r.id = r2b.druckrahmen_id';
    $sql.= ' AND b.id = r2b.freibild_id';
    $sql.= ' AND r.id = '.$frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->load_bilder :<br>" . $sql,4);
		$ret1 = $this->database->execSQL($sql, 4, 1);
    if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = pg_fetch_assoc($ret1[1])){
      $bilder[] = $rs;
    }
    return $bilder;
  }

  function addfreetext($formvars){
    $sql = "
			INSERT INTO kvwmap.druckfreitexte	(
				text,
				posx,
				posy,
				size,
				font,
				angle)
			VALUES (
    		'',
    		0,
    		0,
    		0,
    		'Helvetica.afm', -- Ein Wert muss gesetzt werden, weil beim Layer-Export Null rauskommen würde und das darf für font nicht sein.
    		0
			) RETURNING id";
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $ret = $this->database->execSQL($sql,4, 1);
		$rs = pg_fetch_assoc($ret[1]);
    $lastinsert_id = $rs['id'];
    $sql = 'INSERT INTO kvwmap.druckrahmen2freitexte (druckrahmen_id, freitext_id) VALUES('.$formvars['aktiverRahmen'].', '.$lastinsert_id.')';
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removefreetext($formvars){
    $sql = 'DELETE FROM kvwmap.druckfreitexte WHERE id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:Document->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $sql = 'DELETE FROM kvwmap.druckrahmen2freitexte WHERE freitext_id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:Document->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_price($format){
    $sql ='SELECT preis FROM kvwmap.druckrahmen WHERE format = \''.$format.'\'';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->get_price :<br>" . $sql,4);
    $ret = $this->database->execSQL($sql,4, 1);
    $rs = pg_fetch_row($ret[1]);
    return $rs[0];
  }

  function delete_frame($selected_frame_id){
    $sql ="DELETE FROM kvwmap.druckrahmen WHERE id = " . $selected_frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
    $this->database->execSQL($sql,4, 1);
    $sql ="DELETE FROM kvwmap.druckrahmen2stelle WHERE druckrahmen_id = " . $selected_frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_frame($formvars, $_files, $stelle_id){
    if($formvars['name']){
      $frames = $this->load_frames($this->Stelle->id, NULL);
      for($i = 0; $i < count($frames); $i++){
        if($frames[$i]['name'] == $formvars['name']){
          $this->Document->fehlermeldung = 'Name schon vergeben';
        return;
        }
      }
      $formvars['cent'] = str_pad ($formvars['cent'], 2, "0", STR_PAD_RIGHT);
      $preis = $formvars['euro'] * 100 + $formvars['cent'];
			$columns = [
      	'name' => "'" . $formvars['name'] . "'",
				'dhk_call' => "'" . $formvars['dhk_call'] . "'",
      	'headposx' => $formvars['headposx'],
      	'headposy' => $formvars['headposy'],
      	'headwidth' => $formvars['headwidth'],
      	'headheight' => $formvars['headheight'],
      	'mapposx' => $formvars['mapposx'],
      	'mapposy' => $formvars['mapposy'],
      	'mapwidth' => $formvars['mapwidth'],
      	'mapheight' => $formvars['mapheight']
			];
      if($formvars['refmapposx'] != ''){$columns['refmapposx'] = $formvars['refmapposx'];}
      if($formvars['refmapposy'] != ''){$columns['refmapposy'] = $formvars['refmapposy'];}
      if($formvars['refmapwidth'] != ''){$columns['refmapwidth'] = $formvars['refmapwidth'];}
      if($formvars['refmapheight'] != ''){$columns['refmapheight'] = $formvars['refmapheight'];}
      if($formvars['refposx'] != ''){$columns['refposx'] = $formvars['refposx'];}
      if($formvars['refposy'] != ''){$columns['refposy'] = $formvars['refposy'];}
      if($formvars['refwidth'] != ''){$columns['refwidth'] = $formvars['refwidth'];}
      if($formvars['refheight'] != ''){$columns['refheight'] = $formvars['refheight'];}
      if($formvars['refzoom'] != ''){$columns['refzoom'] = $formvars['refzoom'];}
      if($formvars['dateposx'] != ''){$columns['dateposx'] = $formvars['dateposx'];}
      if($formvars['dateposy'] != ''){$columns['dateposy'] = $formvars['dateposy'];}
      if($formvars['datesize'] != ''){$columns['datesize'] = $formvars['datesize'];}
      if($formvars['datecolor'] != ''){$columns['datecolor'] = $formvars['datecolor'];}
      if($formvars['scaleposx'] != ''){$columns['scaleposx'] = $formvars['scaleposx'];}
      if($formvars['scaleposy'] != ''){$columns['scaleposy'] = $formvars['scaleposy'];}
      if($formvars['scalesize'] != ''){$columns['scalesize'] = $formvars['scalesize'];}
      if($formvars['scalecolor'] != ''){$columns['scalecolor'] = $formvars['scalecolor'];}
			if($formvars['scalebarposx'] != ''){$columns['scalebarposx'] = $formvars['scalebarposx'];}
      if($formvars['scalebarposy'] != ''){$columns['scalebarposy'] = $formvars['scalebarposy'];}
      if($formvars['oscaleposx'] != ''){$columns['oscaleposx'] = $formvars['oscaleposx'];}
      if($formvars['oscaleposy'] != ''){$columns['oscaleposy'] = $formvars['oscaleposy'];}
      if($formvars['oscalesize'] != ''){$columns['oscalesize'] = $formvars['oscalesize'];}
			if($formvars['lageposx'] != ''){$columns['lageposx'] = $formvars['lageposx'];}
      if($formvars['lageposy'] != ''){$columns['lageposy'] = $formvars['lageposy'];}
      if($formvars['lagesize'] != ''){$columns['lagesize'] = $formvars['lagesize'];}
      if($formvars['lagecolor'] != ''){$columns['lagecolor'] = $formvars['lagecolor'];}
			if($formvars['gemeindeposx'] != ''){$columns['gemeindeposx'] = $formvars['gemeindeposx'];}
      if($formvars['gemeindeposy'] != ''){$columns['gemeindeposy'] = $formvars['gemeindeposy'];}
      if($formvars['gemeindesize'] != ''){$columns['gemeindesize'] = $formvars['gemeindesize'];}
      if($formvars['gemeindecolor'] != ''){$columns['gemeindecolor'] = $formvars['gemeindecolor'];}
      if($formvars['gemarkungposx'] != ''){$columns['gemarkungposx'] = $formvars['gemarkungposx'];}
      if($formvars['gemarkungposy'] != ''){$columns['gemarkungposy'] = $formvars['gemarkungposy'];}
      if($formvars['gemarkungsize'] != ''){$columns['gemarkungsize'] = $formvars['gemarkungsize'];}
      if($formvars['gemarkungcolor'] != ''){$columns['gemarkungcolor'] = $formvars['gemarkungcolor'];}
      if($formvars['flurposx'] != ''){$columns['flurposx'] = $formvars['flurposx'];}
      if($formvars['flurposy'] != ''){$columns['flurposy'] = $formvars['flurposy'];}
      if($formvars['flursize'] != ''){$columns['flursize'] = $formvars['flursize'];}
      if($formvars['flurcolor'] != ''){$columns['flurcolor'] = $formvars['flurcolor'];}
			if($formvars['flurstposx'] != ''){$columns['flurstposx'] = $formvars['flurstposx'];}
      if($formvars['flurstposy'] != ''){$columns['flurstposy'] = $formvars['flurstposy'];}
      if($formvars['flurstsize'] != ''){$columns['flurstsize'] = $formvars['flurstsize'];}
      if($formvars['flurstcolor'] != ''){$columns['flurstcolor'] = $formvars['flurstcolor'];}
      if($formvars['legendposx'] != ''){$columns['legendposx'] = $formvars['legendposx'];}
      if($formvars['legendposy'] != ''){$columns['legendposy'] = $formvars['legendposy'];}
      if($formvars['legendsize'] != ''){$columns['legendsize'] = $formvars['legendsize'];}
      if($formvars['arrowposx'] != ''){$columns['arrowposx'] = $formvars['arrowposx'];}
      if($formvars['arrowposy'] != ''){$columns['arrowposy'] = $formvars['arrowposy'];}
      if($formvars['arrowlength'] != ''){$columns['arrowlength'] = $formvars['arrowlength'];}
      if($formvars['userposx'] != ''){$columns['userposx'] = $formvars['userposx'];}
      if($formvars['userposy'] != ''){$columns['userposy'] = $formvars['userposy'];}
      if($formvars['usersize'] != ''){$columns['usersize'] = $formvars['usersize'];}
      if($formvars['usercolor'] != ''){$columns['usercolor'] = $formvars['usercolor'];}
      if($formvars['watermark'] != ''){$columns['watermark'] = "'" . $formvars['watermark'] ."'";}
      if($formvars['watermarkposx'] != ''){$columns['watermarkposx'] = $formvars['watermarkposx'];}
      if($formvars['watermarkposy'] != ''){$columns['watermarkposy'] = $formvars['watermarkposy'];}
      if($formvars['watermarksize'] != ''){$columns['watermarksize'] = $formvars['watermarksize'];}
      if($formvars['watermarkangle'] != ''){$columns['watermarkangle'] = $formvars['watermarkangle'];}
      if($formvars['watermarktransparency'] != ''){$columns['watermarktransparency'] = $formvars['watermarktransparency'];}
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $columns['variable_freetexts'] = $formvars['variable_freetexts'];
      if($formvars['format']){$columns['format'] = "'" . $formvars['format'] ."'";}
      if($preis){$columns['preis'] = $preis;}
      if($formvars['font_date']){$columns['font_date'] = "'" . $formvars['font_date'] . "'";}
      if($formvars['font_scale']){$columns['font_scale'] = "'" . $formvars['font_scale'] . "'";}
			if($formvars['font_lage']){$columns['font_lage'] = "'" . $formvars['font_lage'] . "'";}
			if($formvars['font_gemeinde']){$columns['font_gemeinde'] = "'" . $formvars['font_gemeinde'] . "'";}
      if($formvars['font_gemarkung']){$columns['font_gemarkung'] = "'" . $formvars['font_gemarkung'] . "'";}
      if($formvars['font_flur']){$columns['font_flur'] = "'" . $formvars['font_flur'] . "'";}
			if($formvars['font_flurst']){$columns['font_flurst'] = "'" . $formvars['font_flurst'] . "'";}
      if($formvars['font_legend']){$columns['font_legend'] = "'" . $formvars['font_legend'] . "'";}
      if($formvars['font_user']){$columns['font_user'] = "'" . $formvars['font_user'] . "'";}
      if($formvars['font_watermark']){$columns['font_watermark'] = "'" . $formvars['font_watermark'] . "'";}

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $columns['headsrc'] = "'" . $_files['headsrc']['name'] . "'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $columns['headsrc'] = "'" . $formvars['headsrc_save'] . "'";
      }
      if($_files['refmapsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapsrc']['name'];
        if (move_uploaded_file($_files['refmapsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $columns['refmapsrc'] = "'" . $_files['refmapsrc']['name'] . "'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $columns['refmapsrc'] = "'" . $formvars['refmapsrc_save']."'";
      }
      if($_files['refmapfile']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapfile']['name'];
        if (move_uploaded_file($_files['refmapfile']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $columns['refmapfile'] = "'" . $_files['refmapfile']['name'] . "'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $columns['refmapfile'] = "'" . $formvars['refmapfile_save'] . "'";
      }
			$sql = "
				INSERT INTO kvwmap.druckrahmen
					(" . implode(', ', array_keys($columns)) . ")
				VALUES 
					(" . implode(', ', $columns) . ")
				RETURNING id";
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $ret = $this->database->execSQL($sql,4, 1);
			$rs = pg_fetch_assoc($ret[1]);
      $lastdruckrahmen_id = $rs['id'];

      $sql = 'INSERT INTO kvwmap.druckrahmen2stelle (stelle_id, druckrahmen_id) VALUES('.$stelle_id.', '.$lastdruckrahmen_id.')';
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "
					INSERT INTO kvwmap.druckfreitexte	(
						text,
						posx,
						posy,
						size,
						angle,
						font,
            color)
				VALUES (
					'" . $formvars['text'.$i] . "',
        	" . $formvars['textposx' . $i] . ",
        	" . $formvars['textposy' . $i] . ",
        	" . $formvars['textsize' . $i] . ",
        	" . $formvars['textangle' . $i] .",
        	'" . $formvars['textfont' . $i] . "',
          " . value_or_null($formvars['textcolor' . $i]) ."
				) RETURNING id";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $ret = $this->database->execSQL($sql,4, 1);
				$rs = pg_fetch_assoc($ret[1]);
    		$lastfreitext_id = $rs['id'];

        $sql = 'INSERT INTO kvwmap.druckrahmen2freitexte (druckrahmen_id, freitext_id) VALUES('.$lastdruckrahmen_id.', '.$lastfreitext_id.')';
        $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
    return $lastdruckrahmen_id;
  }

  function update_frame($formvars, $_files){
    if($formvars['name']){
      $formvars['cent'] = str_pad ($formvars['cent'], 2, "0", STR_PAD_RIGHT);
      $preis = $formvars['euro'] * 100 + $formvars['cent'];

      $sql ="UPDATE kvwmap.druckrahmen";
      $sql .= " SET Name = '" . $formvars['name']."'";
			$sql .= ", dhk_call = '" . $formvars['dhk_call']."'";
      $sql .= ", headposx = " . value_or_null($formvars['headposx']);
      $sql .= ", headposy = " . value_or_null($formvars['headposy']);
      $sql .= ", headwidth = " . value_or_null($formvars['headwidth']);
      $sql .= ", headheight = " . value_or_null($formvars['headheight']);
      $sql .= ", mapposx = " . value_or_null($formvars['mapposx']);
      $sql .= ", mapposy = " . value_or_null($formvars['mapposy']);
      $sql .= ", mapwidth = " . value_or_null($formvars['mapwidth']);
      $sql .= ", mapheight = " . value_or_null($formvars['mapheight']);
      $sql .= ", refmapposx = " . value_or_null($formvars['refmapposx']);
      $sql .= ", refmapposy = " . value_or_null($formvars['refmapposy']);
      $sql .= ", refmapwidth = " . value_or_null($formvars['refmapwidth']);
      $sql .= ", refmapheight = " . value_or_null($formvars['refmapheight']);
      $sql .= ", refposx = " . value_or_null($formvars['refposx']);
      $sql .= ", refposy = " . value_or_null($formvars['refposy']);
      $sql .= ", refwidth = " . value_or_null($formvars['refwidth']);
      $sql .= ", refheight = " . value_or_null($formvars['refheight']);
      $sql .= ", refzoom = " . value_or_null($formvars['refzoom']);
      $sql .= ", dateposx = " . value_or_null($formvars['dateposx']);
      $sql .= ", dateposy = " . value_or_null($formvars['dateposy']);
      $sql .= ", datesize = " . value_or_null($formvars['datesize']);
      $sql .= ", datecolor = " . value_or_null($formvars['datecolor']);
      $sql .= ", scaleposx = " . value_or_null($formvars['scaleposx']);
      $sql .= ", scaleposy = " . value_or_null($formvars['scaleposy']);
      $sql .= ", scalesize = " . value_or_null($formvars['scalesize']);
      $sql .= ", scalecolor = " . value_or_null($formvars['scalecolor']);
			$sql .= ", scalebarposx = " . value_or_null($formvars['scalebarposx']);
      $sql .= ", scalebarposy = " . value_or_null($formvars['scalebarposy']);
      $sql .= ", oscaleposx = " . value_or_null($formvars['oscaleposx']);
      $sql .= ", oscaleposy = " . value_or_null($formvars['oscaleposy']);
      $sql .= ", oscalesize = " . value_or_null($formvars['oscalesize']);
			$sql .= ", lageposx = " . value_or_null($formvars['lageposx']);
      $sql .= ", lageposy = " . value_or_null($formvars['lageposy']);
      $sql .= ", lagesize = " . value_or_null($formvars['lagesize']);
      $sql .= ", lagecolor = " . value_or_null($formvars['lagecolor']);
			$sql .= ", gemeindeposx = " . value_or_null($formvars['gemeindeposx']);
      $sql .= ", gemeindeposy = " . value_or_null($formvars['gemeindeposy']);
      $sql .= ", gemeindesize = " . value_or_null($formvars['gemeindesize']);
      $sql .= ", gemeindecolor = " . value_or_null($formvars['gemeindecolor']);
      $sql .= ", gemarkungposx = " . value_or_null($formvars['gemarkungposx']);
      $sql .= ", gemarkungposy = " . value_or_null($formvars['gemarkungposy']);
      $sql .= ", gemarkungsize = " . value_or_null($formvars['gemarkungsize']);
      $sql .= ", gemarkungcolor = " . value_or_null($formvars['gemarkungcolor']);
      $sql .= ", flurposx = " . value_or_null($formvars['flurposx']);
      $sql .= ", flurposy = " . value_or_null($formvars['flurposy']);
      $sql .= ", flursize = " . value_or_null($formvars['flursize']);
      $sql .= ", flurcolor = " . value_or_null($formvars['flurcolor']);
			$sql .= ", flurstposx = " . value_or_null($formvars['flurstposx']);
      $sql .= ", flurstposy = " . value_or_null($formvars['flurstposy']);
      $sql .= ", flurstsize = " . value_or_null($formvars['flurstsize']);
      $sql .= ", flurstcolor = " . value_or_null($formvars['flurstcolor']);
      $sql .= ", legendposx = " . value_or_null($formvars['legendposx']);
      $sql .= ", legendposy = " . value_or_null($formvars['legendposy']);
      $sql .= ", legendsize = " . value_or_null($formvars['legendsize']);
      $sql .= ", arrowposx = " . value_or_null($formvars['arrowposx']);
      $sql .= ", arrowposy = " . value_or_null($formvars['arrowposy']);
      $sql .= ", arrowlength = " . value_or_null($formvars['arrowlength']);
      $sql .= ", userposx = " . value_or_null($formvars['userposx']);
      $sql .= ", userposy = " . value_or_null($formvars['userposy']);
      $sql .= ", usersize = " . value_or_null($formvars['usersize']);
      $sql .= ", usercolor = " . value_or_null($formvars['usercolor']);
      $sql .= ", watermark = '" . $formvars['watermark']."'";
      $sql .= ", watermarkposx = " . value_or_null($formvars['watermarkposx']);
      $sql .= ", watermarkposy = " . value_or_null($formvars['watermarkposy']);
      $sql .= ", watermarksize = " . value_or_null($formvars['watermarksize']);
      $sql .= ", watermarkangle = " . value_or_null($formvars['watermarkangle']);
      $sql .= ", watermarktransparency = " . value_or_null($formvars['watermarktransparency']);
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", variable_freetexts = " . $formvars['variable_freetexts'];
      $sql .= ", format = '" . $formvars['format']."'";
      $sql .= ", preis = '" . $preis."'";
      $sql .= ", font_date = '" . $formvars['font_date']."'";
      $sql .= ", font_scale = '" . $formvars['font_scale']."'";
			$sql .= ", font_lage = '" . $formvars['font_lage']."'";
			$sql .= ", font_gemeinde = '" . $formvars['font_gemeinde']."'";
      $sql .= ", font_gemarkung = '" . $formvars['font_gemarkung']."'";
      $sql .= ", font_flur = '" . $formvars['font_flur']."'";
			$sql .= ", font_flurst = '" . $formvars['font_flurst']."'";
      $sql .= ", font_legend = '" . $formvars['font_legend']."'";
      $sql .= ", font_user = '" . $formvars['font_user']."'";
      $sql .= ", font_watermark = '" . $formvars['font_watermark']."'";

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", headsrc = '" . $_files['headsrc']['name']."'";
          #echo $sql;
        }
        else {
            //echo '<br>Datei: '.$_files['Wappen']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
          }
      }
      if($_files['refmapsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapsrc']['name'];
        if (move_uploaded_file($_files['refmapsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", refmapsrc = '" . $_files['refmapsrc']['name']."'";
          #echo $sql;
        }
        else {
            //echo '<br>Datei: '.$_files['Wappen']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
          }
      }
      if($_files['refmapfile']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapfile']['name'];
        if (move_uploaded_file($_files['refmapfile']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", refmapfile = '" . $_files['refmapfile']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      $sql .= " WHERE id =".(int)$formvars['aktiverRahmen'];
      $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "UPDATE kvwmap.druckfreitexte SET text = '" . $formvars['text'.$i]."'";
        $sql .= ", posx = " . $formvars['textposx'.$i];
        $sql .= ", posy = " . $formvars['textposy'.$i];
        $sql .= ", size = " . $formvars['textsize'.$i];
        $sql .= ", angle = " . $formvars['textangle'.$i];
        $sql .= ", font = '" . $formvars['textfont'.$i]."'";
        $sql .= ", color = " . value_or_null($formvars['textcolor'.$i]);
        $sql .= " WHERE id = " . $formvars['text_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
  }

  function add_frame2stelle($id, $stelleid){
    $sql = "
			INSERT INTO 
				kvwmap.druckrahmen2stelle 
			VALUES (
				" . $stelleid . ", 
				" . $id . ")
			ON CONFLICT (stelle_id, druckrahmen_id) DO NOTHING";
    $this->debug->write("<p>file:kvwmap class:Document->add_frame2stelle :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removeFrames($stelleid){
    $sql ="DELETE FROM kvwmap.druckrahmen2stelle WHERE stelle_id = " . $stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->removeFrames :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_active_frame($id, $userid, $stelleid){
    $sql ="UPDATE kvwmap.rolle SET active_frame = '" . $id."' WHERE user_id =" . $userid." AND stelle_id =" . $stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->save_active_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_active_frameid($userid, $stelleid){
    $sql ='SELECT active_frame from kvwmap.rolle WHERE user_id ='.$userid.' AND stelle_id ='.$stelleid;
    $this->debug->write("<p>file:kvwmap class:GUI->get_active_frameid :<br>" . $sql,4);
    $ret = $this->database->execSQL($sql,4, 1);
		$rs = pg_fetch_row($ret[1]);
    return $rs[0];
  }
}

?>