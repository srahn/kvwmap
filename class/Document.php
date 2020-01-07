<?php
###########################################
# Dokumentenklasse                        #
###########################################
class Document {
  var $html;
  var $debug;
  var $head;
  var $headquery;

  ###################### Liste der Funktionen ####################################
  #
  # function Document() - Construktor
  # function load_heads()
  # function load_head($headid)
  # function save_head($formvars)
  # function update_head($formvars)
  # function save_active_head($id,$userid, $stelleid)
  # function get_active_headid($userid, $stelleid)
  # function get_head($userid, $stelleid)
  #
  ################################################################################

  function Document ($database){
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }

  function delete_ausschnitt($stelle_id, $user_id, $id){
    $sql = 'DELETE FROM druckausschnitte WHERE ';
    $sql.= 'stelle_id = '.$stelle_id.' AND ';
    $sql.= 'user_id = '.$user_id;
    if($id != ''){
      $sql.= ' AND id = '.$id;
    }
    $this->debug->write("<p>file:kvwmap class:Document->delete_ausschnitt :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_ausschnitt($stelle_id, $user_id, $name, $center_x, $center_y, $print_scale, $angle, $frame_id){
    $sql = "
			INSERT INTO
				druckausschnitte
			SET
				id = COALESCE(
					(
						SELECT 
							new_id
						FROM
						(
							SELECT
								max(id) + 1 AS new_id
							FROM
								druckausschnitte
							WHERE
								stelle_id = " . $stelle_id . " AND
								user_id = " . $user_id . "
						) as foo
					),
					1
				),
				stelle_id = " . $stelle_id . ",
				user_id = " . $user_id . ",
				name = '" . $name . "',
				center_x = " . $center_x . ",
				center_y = " . $center_y . ",
				print_scale = " . $print_scale . ",
				angle = " . $angle . ",
				frame_id = " . $frame_id . "
		";
    $this->debug->write("<p>file:kvwmap class:Document->save_ausschnitt :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function load_ausschnitte($stelle_id, $user_id, $id){
    $sql = 'SELECT * FROM druckausschnitte WHERE ';
    $sql.= 'stelle_id = '.$stelle_id.' AND ';
    $sql.= 'user_id = '.$user_id;
    if($id != ''){
      $sql.= ' AND id = '.$id;
    }
    $this->debug->write("<p>file:kvwmap class:Document->load_ausschnitte :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $ausschnitte[] = $rs;
    }
    return $ausschnitte;
  }

  function load_frames($stelle_id, $frameid, $return = '') {
    $sql = 'SELECT DISTINCT druckrahmen.* FROM druckrahmen';
    if($frameid AND !$stelle_id){$sql .= ' WHERE druckrahmen.id ='.$frameid;}
    if($stelle_id AND !$frameid){
    	$sql.= ', druckrahmen2stelle WHERE druckrahmen2stelle.druckrahmen_id = druckrahmen.id';
    	$sql .= ' AND druckrahmen2stelle.stelle_id = '.$stelle_id;
    }
    if($frameid AND $stelle_id){
    	$sql.= ', druckrahmen2stelle WHERE druckrahmen2stelle.druckrahmen_id = druckrahmen.id';
    	$sql .= ' AND druckrahmen2stelle.stelle_id = '.$stelle_id;
    	$sql .= ' AND druckrahmen.id ='.$frameid;
    }
    $sql .= ' ORDER BY Name';
    #echo $sql.'<br>';
		$ret1 = $this->database->execSQL($sql, 4, 1);
  	if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($ret1[1])){
			if ($return == 'only_ids') {
				$frames[] = $rs['id'];
			}
			else {
	      $frames[] = $rs;
	      $frames[0]['bilder'] = $this->load_bilder($rs['id']);
	      $frames[0]['texts'] = $this->load_texts($rs['id']);
	      $i++;
			}
    }
    return $frames;
  }

  function load_texts($frame_id){
    $sql = 'SELECT druckfreitexte.* FROM druckrahmen, druckfreitexte, druckrahmen2freitexte';
    $sql.= ' WHERE druckrahmen2freitexte.druckrahmen_id = '.$frame_id;
    $sql.= ' AND druckrahmen2freitexte.druckrahmen_id = druckrahmen.id';
    $sql.= ' AND druckrahmen2freitexte.freitext_id = druckfreitexte.id';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->load_texts :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $texts[] = $rs;
    }
    return $texts;
  }

  function load_bilder($frame_id){
    $sql = 'SELECT b.src,r2b.posx,r2b.posy,r2b.width,r2b.height,r2b.angle';
    $sql.= ' FROM druckrahmen AS r, druckfreibilder AS b, druckrahmen2freibilder AS r2b';
    $sql.= ' WHERE r.id = r2b.druckrahmen_id';
    $sql.= ' AND b.id = r2b.freibild_id';
    $sql.= ' AND r.id = '.$frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->load_bilder :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $bilder[] = $rs;
    }
    return $bilder;
  }

  function addfreetext($formvars){
    $sql = 'INSERT INTO druckfreitexte SET';
    $sql .= ' text = "",';
    $sql .= ' posx = 0,';
    $sql .= ' posy = 0,';
    $sql .= ' size = 0,';
    $sql .= ' font = "",';
    $sql .= ' angle = 0';
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $lastinsert_id = mysql_insert_id();
    $sql = 'INSERT INTO druckrahmen2freitexte (druckrahmen_id, freitext_id) VALUES('.$formvars['aktiverRahmen'].', '.$lastinsert_id.')';
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removefreetext($formvars){
    $sql = 'DELETE FROM druckfreitexte WHERE id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:Document->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $sql = 'DELETE FROM druckrahmen2freitexte WHERE freitext_id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:Document->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_price($format){
    $sql ='SELECT preis FROM druckrahmen WHERE `format` = \''.$format.'\'';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->get_price :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);

    return $rs[0];
  }

  function delete_frame($selected_frame_id){
    $sql ="DELETE FROM druckrahmen WHERE id = " . $selected_frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
    $this->database->execSQL($sql,4, 1);
    $sql ="DELETE FROM druckrahmen2stelle WHERE druckrahmen_id = " . $selected_frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_frame($formvars, $_files, $stelle_id){
    if($formvars['Name']){
      $frames = $this->load_frames($this->Stelle->id, NULL);
      for($i = 0; $i < count($frames); $i++){
        if($frames[$i]['Name'] == $formvars['Name']){
          $this->Document->fehlermeldung = 'Name schon vergeben';
        return;
        }
      }
      $formvars['cent'] = str_pad ($formvars['cent'], 2, "0", STR_PAD_RIGHT);
      $preis = $formvars['euro'] * 100 + $formvars['cent'];

      $sql = "INSERT INTO `druckrahmen`";
      $sql .= " SET `Name` = '" . $formvars['Name']."'";
			$sql .= ", `dhk_call` = '" . $formvars['dhk_call']."'";
      $sql .= ", `headposx` = " . $formvars['headposx'];
      $sql .= ", `headposy` = " . $formvars['headposy'];
      $sql .= ", `headwidth` = " . $formvars['headwidth'];
      $sql .= ", `headheight` = " . $formvars['headheight'];
      $sql .= ", `mapposx` = " . $formvars['mapposx'];
      $sql .= ", `mapposy` = " . $formvars['mapposy'];
      $sql .= ", `mapwidth` = " . $formvars['mapwidth'];
      $sql .= ", `mapheight` = " . $formvars['mapheight'];
      if($formvars['refmapposx']){$sql .= ", `refmapposx` = " . $formvars['refmapposx'];}
      if($formvars['refmapposy']){$sql .= ", `refmapposy` = " . $formvars['refmapposy'];}
      if($formvars['refmapwidth']){$sql .= ", `refmapwidth` = " . $formvars['refmapwidth'];}
      if($formvars['refmapheight']){$sql .= ", `refmapheight` = " . $formvars['refmapheight'];}
      if($formvars['refposx']){$sql .= ", `refposx` = " . $formvars['refposx'];}
      if($formvars['refposy']){$sql .= ", `refposy` = " . $formvars['refposy'];}
      if($formvars['refwidth']){$sql .= ", `refwidth` = " . $formvars['refwidth'];}
      if($formvars['refheight']){$sql .= ", `refheight` = " . $formvars['refheight'];}
      if($formvars['refzoom']){$sql .= ", `refzoom` = " . $formvars['refzoom'];}
      if($formvars['dateposx']){$sql .= ", `dateposx` = " . $formvars['dateposx'];}
      if($formvars['dateposy']){$sql .= ", `dateposy` = " . $formvars['dateposy'];}
      if($formvars['datesize']){$sql .= ", `datesize` = " . $formvars['datesize'];}
      if($formvars['scaleposx']){$sql .= ", `scaleposx` = " . $formvars['scaleposx'];}
      if($formvars['scaleposy']){$sql .= ", `scaleposy` = " . $formvars['scaleposy'];}
      if($formvars['scalesize']){$sql .= ", `scalesize` = " . $formvars['scalesize'];}
			if($formvars['scalebarposx']){$sql .= ", `scalebarposx` = " . $formvars['scalebarposx'];}
      if($formvars['scalebarposy']){$sql .= ", `scalebarposy` = " . $formvars['scalebarposy'];}
      if($formvars['oscaleposx']){$sql .= ", `oscaleposx` = " . $formvars['oscaleposx'];}
      if($formvars['oscaleposy']){$sql .= ", `oscaleposy` = " . $formvars['oscaleposy'];}
      if($formvars['oscalesize']){$sql .= ", `oscalesize` = " . $formvars['oscalesize'];}
			if($formvars['lageposx']){$sql .= ", `lageposx` = " . $formvars['lageposx'];}
      if($formvars['lageposy']){$sql .= ", `lageposy` = " . $formvars['lageposy'];}
      if($formvars['lagesize']){$sql .= ", `lagesize` = " . $formvars['lagesize'];}
			if($formvars['gemeindeposx']){$sql .= ", `gemeindeposx` = " . $formvars['gemeindeposx'];}
      if($formvars['gemeindeposy']){$sql .= ", `gemeindeposy` = " . $formvars['gemeindeposy'];}
      if($formvars['gemeindesize']){$sql .= ", `gemeindesize` = " . $formvars['gemeindesize'];}
      if($formvars['gemarkungposx']){$sql .= ", `gemarkungposx` = " . $formvars['gemarkungposx'];}
      if($formvars['gemarkungposy']){$sql .= ", `gemarkungposy` = " . $formvars['gemarkungposy'];}
      if($formvars['gemarkungsize']){$sql .= ", `gemarkungsize` = " . $formvars['gemarkungsize'];}
      if($formvars['flurposx']){$sql .= ", `flurposx` = " . $formvars['flurposx'];}
      if($formvars['flurposy']){$sql .= ", `flurposy` = " . $formvars['flurposy'];}
      if($formvars['flursize']){$sql .= ", `flursize` = " . $formvars['flursize'];}
			if($formvars['flurstposx']){$sql .= ", `flurstposx` = " . $formvars['flurstposx'];}
      if($formvars['flurstposy']){$sql .= ", `flurstposy` = " . $formvars['flurstposy'];}
      if($formvars['flurstsize']){$sql .= ", `flurstsize` = " . $formvars['flurstsize'];}
      if($formvars['legendposx']){$sql .= ", `legendposx` = " . $formvars['legendposx'];}
      if($formvars['legendposy']){$sql .= ", `legendposy` = " . $formvars['legendposy'];}
      if($formvars['legendsize']){$sql .= ", `legendsize` = " . $formvars['legendsize'];}
      if($formvars['arrowposx']){$sql .= ", `arrowposx` = " . $formvars['arrowposx'];}
      if($formvars['arrowposy']){$sql .= ", `arrowposy` = " . $formvars['arrowposy'];}
      if($formvars['arrowlength']){$sql .= ", `arrowlength` = " . $formvars['arrowlength'];}
      if($formvars['userposx']){$sql .= ", `userposx` = '" . $formvars['userposx']."'";}
      if($formvars['userposy']){$sql .= ", `userposy` = '" . $formvars['userposy']."'";}
      if($formvars['usersize']){$sql .= ", `usersize` = '" . $formvars['usersize']."'";}
      if($formvars['watermark']){$sql .= ", `watermark` = '" . $formvars['watermark']."'";}
      if($formvars['watermarkposx']){$sql .= ", `watermarkposx` = " . $formvars['watermarkposx'];}
      if($formvars['watermarkposy']){$sql .= ", `watermarkposy` = " . $formvars['watermarkposy'];}
      if($formvars['watermarksize']){$sql .= ", `watermarksize` = " . $formvars['watermarksize'];}
      if($formvars['watermarkangle']){$sql .= ", `watermarkangle` = " . $formvars['watermarkangle'];}
      if($formvars['watermarktransparency']){$sql .= ", `watermarktransparency` = '" . $formvars['watermarktransparency']."'";}
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", `variable_freetexts` = " . $formvars['variable_freetexts'];
      if($formvars['format']){$sql .= ", `format` = '" . $formvars['format']."'";}
      if($preis){$sql .= ", `preis` = '" . $preis."'";}
      if($formvars['font_date']){$sql .= ", `font_date` = '" . $formvars['font_date']."'";}
      if($formvars['font_scale']){$sql .= ", `font_scale` = '" . $formvars['font_scale']."'";}
			if($formvars['font_lage']){$sql .= ", `font_lage` = '" . $formvars['font_lage']."'";}
			if($formvars['font_gemeinde']){$sql .= ", `font_gemeinde` = '" . $formvars['font_gemeinde']."'";}
      if($formvars['font_gemarkung']){$sql .= ", `font_gemarkung` = '" . $formvars['font_gemarkung']."'";}
      if($formvars['font_flur']){$sql .= ", `font_flur` = '" . $formvars['font_flur']."'";}
			if($formvars['font_flurst']){$sql .= ", `font_flurst` = '" . $formvars['font_flurst']."'";}
      if($formvars['font_legend']){$sql .= ", `font_legend` = '" . $formvars['font_legend']."'";}
      if($formvars['font_user']){$sql .= ", `font_user` = '" . $formvars['font_user']."'";}
      if($formvars['font_watermark']){$sql .= ", `font_watermark` = '" . $formvars['font_watermark']."'";}

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `headsrc` = '" . $_files['headsrc']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `headsrc` = '" . $formvars['headsrc_save']."'";
      }
      if($_files['refmapsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapsrc']['name'];
        if (move_uploaded_file($_files['refmapsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapsrc` = '" . $_files['refmapsrc']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `refmapsrc` = '" . $formvars['refmapsrc_save']."'";
      }
      if($_files['refmapfile']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapfile']['name'];
        if (move_uploaded_file($_files['refmapfile']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapfile` = '" . $_files['refmapfile']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `refmapfile` = '" . $formvars['refmapfile_save']."'";
      }
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);
      $lastdruckrahmen_id = mysql_insert_id();

      $sql = 'INSERT INTO druckrahmen2stelle (stelle_id, druckrahmen_id) VALUES('.$stelle_id.', '.$lastdruckrahmen_id.')';
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "INSERT INTO druckfreitexte SET `text` = '" . $formvars['text'.$i]."'";
        $sql .= ", `posx` = " . $formvars['textposx'.$i];
        $sql .= ", `posy` = " . $formvars['textposy'.$i];
        $sql .= ", `size` = " . $formvars['textsize'.$i];
        $sql .= ", `angle` = " . $formvars['textangle'.$i];
        $sql .= ", `font` = '" . $formvars['textfont'.$i]."'";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
        $lastfreitext_id = mysql_insert_id();

        $sql = 'INSERT INTO druckrahmen2freitexte (druckrahmen_id, freitext_id) VALUES('.$lastdruckrahmen_id.', '.$lastfreitext_id.')';
        $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
    return $lastdruckrahmen_id;
  }

  function update_frame($formvars, $_files){
    if($formvars['Name']){
      $formvars['cent'] = str_pad ($formvars['cent'], 2, "0", STR_PAD_RIGHT);
      $preis = $formvars['euro'] * 100 + $formvars['cent'];

      $sql ="UPDATE `druckrahmen`";
      $sql .= " SET `Name` = '" . $formvars['Name']."'";
			$sql .= ", `dhk_call` = '" . $formvars['dhk_call']."'";
      $sql .= ", `headposx` = '" . $formvars['headposx']."'";
      $sql .= ", `headposy` = '" . $formvars['headposy']."'";
      $sql .= ", `headwidth` = '" . $formvars['headwidth']."'";
      $sql .= ", `headheight` = '" . $formvars['headheight']."'";
      $sql .= ", `mapposx` = '" . $formvars['mapposx']."'";
      $sql .= ", `mapposy` = '" . $formvars['mapposy']."'";
      $sql .= ", `mapwidth` = '" . $formvars['mapwidth']."'";
      $sql .= ", `mapheight` = '" . $formvars['mapheight']."'";
      $sql .= ", `refmapposx` = '" . $formvars['refmapposx']."'";
      $sql .= ", `refmapposy` = '" . $formvars['refmapposy']."'";
      $sql .= ", `refmapwidth` = '" . $formvars['refmapwidth']."'";
      $sql .= ", `refmapheight` = '" . $formvars['refmapheight']."'";
      $sql .= ", `refposx` = '" . $formvars['refposx']."'";
      $sql .= ", `refposy` = '" . $formvars['refposy']."'";
      $sql .= ", `refwidth` = '" . $formvars['refwidth']."'";
      $sql .= ", `refheight` = '" . $formvars['refheight']."'";
      $sql .= ", `refzoom` = '" . $formvars['refzoom']."'";
      $sql .= ", `dateposx` = '" . $formvars['dateposx']."'";
      $sql .= ", `dateposy` = '" . $formvars['dateposy']."'";
      $sql .= ", `datesize` = '" . $formvars['datesize']."'";
      $sql .= ", `scaleposx` = '" . $formvars['scaleposx']."'";
      $sql .= ", `scaleposy` = '" . $formvars['scaleposy']."'";
      $sql .= ", `scalesize` = '" . $formvars['scalesize']."'";
			$sql .= ", `scalebarposx` = '" . $formvars['scalebarposx']."'";
      $sql .= ", `scalebarposy` = '" . $formvars['scalebarposy']."'";
      $sql .= ", `oscaleposx` = '" . $formvars['oscaleposx']."'";
      $sql .= ", `oscaleposy` = '" . $formvars['oscaleposy']."'";
      $sql .= ", `oscalesize` = '" . $formvars['oscalesize']."'";
			$sql .= ", `lageposx` = '" . $formvars['lageposx']."'";
      $sql .= ", `lageposy` = '" . $formvars['lageposy']."'";
      $sql .= ", `lagesize` = '" . $formvars['lagesize']."'";
			$sql .= ", `gemeindeposx` = '" . $formvars['gemeindeposx']."'";
      $sql .= ", `gemeindeposy` = '" . $formvars['gemeindeposy']."'";
      $sql .= ", `gemeindesize` = '" . $formvars['gemeindesize']."'";
      $sql .= ", `gemarkungposx` = '" . $formvars['gemarkungposx']."'";
      $sql .= ", `gemarkungposy` = '" . $formvars['gemarkungposy']."'";
      $sql .= ", `gemarkungsize` = '" . $formvars['gemarkungsize']."'";
      $sql .= ", `flurposx` = '" . $formvars['flurposx']."'";
      $sql .= ", `flurposy` = '" . $formvars['flurposy']."'";
      $sql .= ", `flursize` = '" . $formvars['flursize']."'";
			$sql .= ", `flurstposx` = '" . $formvars['flurstposx']."'";
      $sql .= ", `flurstposy` = '" . $formvars['flurstposy']."'";
      $sql .= ", `flurstsize` = '" . $formvars['flurstsize']."'";
      $sql .= ", `legendposx` = '" . $formvars['legendposx']."'";
      $sql .= ", `legendposy` = '" . $formvars['legendposy']."'";
      $sql .= ", `legendsize` = '" . $formvars['legendsize']."'";
      $sql .= ", `arrowposx` = '" . $formvars['arrowposx']."'";
      $sql .= ", `arrowposy` = '" . $formvars['arrowposy']."'";
      $sql .= ", `arrowlength` = '" . $formvars['arrowlength']."'";
      $sql .= ", `userposx` = '" . $formvars['userposx']."'";
      $sql .= ", `userposy` = '" . $formvars['userposy']."'";
      $sql .= ", `usersize` = '" . $formvars['usersize']."'";
      $sql .= ", `watermark` = '" . $formvars['watermark']."'";
      $sql .= ", `watermarkposx` = '" . $formvars['watermarkposx']."'";
      $sql .= ", `watermarkposy` = '" . $formvars['watermarkposy']."'";
      $sql .= ", `watermarksize` = '" . $formvars['watermarksize']."'";
      $sql .= ", `watermarkangle` = '" . $formvars['watermarkangle']."'";
      $sql .= ", `watermarktransparency` = '" . $formvars['watermarktransparency']."'";
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", `variable_freetexts` = " . $formvars['variable_freetexts'];
      $sql .= ", `format` = '" . $formvars['format']."'";
      $sql .= ", `preis` = '" . $preis."'";
      $sql .= ", `font_date` = '" . $formvars['font_date']."'";
      $sql .= ", `font_scale` = '" . $formvars['font_scale']."'";
			$sql .= ", `font_lage` = '" . $formvars['font_lage']."'";
			$sql .= ", `font_gemeinde` = '" . $formvars['font_gemeinde']."'";
      $sql .= ", `font_gemarkung` = '" . $formvars['font_gemarkung']."'";
      $sql .= ", `font_flur` = '" . $formvars['font_flur']."'";
			$sql .= ", `font_flurst` = '" . $formvars['font_flurst']."'";
      $sql .= ", `font_legend` = '" . $formvars['font_legend']."'";
      $sql .= ", `font_user` = '" . $formvars['font_user']."'";
      $sql .= ", `font_watermark` = '" . $formvars['font_watermark']."'";

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `headsrc` = '" . $_files['headsrc']['name']."'";
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
          $sql .= ", `refmapsrc` = '" . $_files['refmapsrc']['name']."'";
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
          $sql .= ", `refmapfile` = '" . $_files['refmapfile']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      $sql .= " WHERE `id` =".(int)$formvars['aktiverRahmen'];
      $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "UPDATE druckfreitexte SET `text` = '" . $formvars['text'.$i]."'";
        $sql .= ", `posx` = " . $formvars['textposx'.$i];
        $sql .= ", `posy` = " . $formvars['textposy'.$i];
        $sql .= ", `size` = " . $formvars['textsize'.$i];
        $sql .= ", `angle` = " . $formvars['textangle'.$i];
        $sql .= ", `font` = '" . $formvars['textfont'.$i]."'";
        $sql .= " WHERE id = " . $formvars['text_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
  }

  function add_frame2stelle($id, $stelleid){
    $sql ="INSERT IGNORE INTO druckrahmen2stelle VALUES (" . $stelleid.", " . $id.")";
    $this->debug->write("<p>file:kvwmap class:Document->add_frame2stelle :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removeFrames($stelleid){
    $sql ="DELETE FROM druckrahmen2stelle WHERE stelle_id = " . $stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->removeFrames :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_active_frame($id, $userid, $stelleid){
    $sql ="UPDATE `rolle` SET `active_frame` = '" . $id."' WHERE `user_id` =" . $userid." AND `stelle_id` =" . $stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->save_active_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_active_frameid($userid, $stelleid){
    $sql ='SELECT active_frame from rolle WHERE `user_id` ='.$userid.' AND `stelle_id` ='.$stelleid;
    $this->debug->write("<p>file:kvwmap class:GUI->get_active_frameid :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs[0];
  }
}

class point {
  var $x;
  var $y;

  function point($x,$y) {
    $this->x=$x;
    $this->y=$y;
  }

  function pixel2welt($minX,$minY,$pixSize) {
    # Rechnet Pixel- in Weltkoordinaten um mit minx, miny und pixsize
    $this->x=($this->x*$pixSize)+$minX;
    $this->y=($this->y*$pixSize)+$minY;
  }

  function welt2pixel($minX,$minY,$pixSize) {
    # Rechnet Welt- in Pixelkoordinaten um mit minx, miny und pixsize
    $this->x=round(($this->x-$minX)/$pixSize);
    $this->y=round(($this->y-$minY)/$pixSize);
  }
}
?>
