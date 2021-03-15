<?php
  include(LAYOUTPATH . 'languages/sicherungsdaten_' . $this->user->rolle->language . '.php');
	include_once(CLASSPATH . 'FormObject.php');
?>

<script language="javascript" type="text/javascript">

	function set_default_targetname(){
		const list_methode = document.getElementById('methode');
		const text_target  = document.getElementById('feld_target');

		if (list_methode && text_target) {
			var methode			= list_methode.options[list_methode.selectedIndex].value;
			var source_obj	= document.getElementById('source');

			if (source_obj){
					switch (list_methode.options[list_methode.selectedIndex].value) {
						case 'Mysql Dump':
							text_target.value = source_obj.options[source_obj.selectedIndex].value + '.dump';
							break;
						case 'Postgres Dump':
							text_target.value = source_obj.options[source_obj.selectedIndex].innerHTML + '.dump';
							break;
						case 'Verzeichnissicherung':
							var fullPath = source_obj.value;
							var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
    					var filename = fullPath.substring(startIndex);
    					if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
									filename = filename.substring(1);
							}
							text_target.value = filename + '.tar.gz';
							break;
					}
			}
		}

	}

	function switch_methode(methode){
		var l = document.getElementById('td_select_source');

		//alle Kinder im <td> entfernen
		while (l.firstChild) {
			l.removeChild(l.firstChild);
 		}

		switch (methode) {
			case 'Mysql Dump':
				s = document.createElement('select');
				s.setAttribute("name","source");
				s.setAttribute("id","source");
				s.setAttribute("onclick","javascript: set_default_targetname();");
				<?php
					$i=0;
					foreach ($this->inhalt->get_mysql_database_names() as $key => $value) {
						echo "s.options[". $i ."]=new Option('" . $value['Database'] . "','" . $value['Database'] . "');" . PHP_EOL;
						echo $this->formvars['source'] == $value['Database']?"s.options[". $i ."].selected=true;":"";
						$i++;
					}
				?>
				break;
			case 'Postgres Dump':
				s = document.createElement('select');
				s.setAttribute("name","connection_id");
				s.setAttribute("id","source");
				s.setAttribute("onclick","javascript: set_default_targetname(); ");
				<?php
					$i=0;
					foreach ($this->inhalt->get_pgsql_database_names() as $db) {
						echo "s.options[". $i ."]=new Option('" . $db[1] . "','" . $db[0] . "');" . PHP_EOL;
						echo $this->formvars['connection_id'] == $db[0]?"s.options[". $i ."].selected=true;":"";
						$i++;
					}
				?>
				break;


			default: //dir, rsync
				s = document.createElement('input');
				s.setAttribute("name","source");
				s.setAttribute("type","text");
				s.setAttribute("value","<?php echo $this->inhalt->get('source'); ?>");
				s.setAttribute("size","80");
				s.setAttribute("maxlength","255");
				s.setAttribute("placeholder","<? echo $strQuelle; ?>");
				s.setAttribute("id","source");
				s.setAttribute("onclick","javascript: set_default_targetname();");
				s.addEventListener('input', set_default_targetname);

				t = document.getElementById('feld_target');
				if (t){
					switch (methode) {
						case 'Verzeichnisinhalte kopieren':
							placeholder = "<? echo $strRsyncPlaceholder ?>";
							break;
						case 'Verzeichnissicherung':
						 	placeholder = "";
							break;
					}
					t.setAttribute("placeholder",placeholder);
				}
				break;
		}
		l.appendChild(s);

		switch_parameter(methode);
		set_default_targetname();
	}



	function switch_parameter(methode){
		var tab_obj = document.getElementById("tab_options");

		if (tab_obj){

			while (tab_obj.firstChild) {
				tab_obj.removeChild(tab_obj.lastChild);
			}

			switch (methode) {

				case 'Postgres Dump':

					//1. Zeile INSERT anstatt COPY
					var tr1 = tab_obj.insertRow(-1);

					var col1 = document.createElement("td");
					var col2 = document.createElement("td");
					col1.setAttribute("class","fetter");
					col1.setAttribute("align","right");
					col1.innerHTML = "--inserts";

					var chkbx_1	=	document.createElement("input");
					chkbx_1.setAttribute("type","checkbox");
					chkbx_1.setAttribute("name","pgdump_insert");
					chkbx_1.setAttribute("value","1");
					<?php if ($this->formvars['pgdump_insert']) { ?>
						chkbx_1.checked = true;
					<?php } ?>

					tr1.appendChild(col1);
					tr1.appendChild(col2);
					col2.appendChild(chkbx_1);


					//2. Zeile --column-inserts
					const tr2 = tab_obj.insertRow(-1);

					var col1 = document.createElement("td");
					var col2 = document.createElement("td");
					col1.setAttribute("class","fetter");
					col1.setAttribute("align","right");
					col1.innerHTML = "--column-inserts";

					var chkbx_1	=	document.createElement("input");
					chkbx_1.setAttribute("type","checkbox");
					chkbx_1.setAttribute("name","pgdump_columninserts");
					chkbx_1.setAttribute("value","1");
					<?php if ($this->formvars['pgdump_columninserts']) { ?>
						chkbx_1.checked = true;
					<?php } ?>

					tr2.appendChild(col1);
					tr2.appendChild(col2);
					col2.appendChild(chkbx_1);

					//3. Zeile Schema ein/ausschließen
					const tr3 = tab_obj.insertRow(-1);

					var col1 = document.createElement("td");
					var col2 = document.createElement("td");
					col1.setAttribute("class","fetter");
					col1.setAttribute("align","right");
					col1.setAttribute("style","vertical-align:top");
					col1.innerHTML = "folgende Schemas";

					// Radiobuttons bauen
					var fieldset =	document.createElement("fieldset");
					var radio_0	=	document.createElement("input");
					radio_0.setAttribute("type","radio");
					radio_0.setAttribute("name","pgdump_in_exclude_schemas");
					radio_0.setAttribute("id","pg_exclude_schema");
					radio_0.setAttribute("value","");
					var label_0 = document.createElement("label");
					label_0.setAttribute("for","inexclude_schema_none");
					label_0.innerHTML = "Option nicht verwenden";

					var radio_1 = document.createElement("input");
					radio_1.setAttribute("type","radio");
					radio_1.setAttribute("name","pgdump_in_exclude_schemas");
					radio_1.setAttribute("id","pg_include_schema");
					radio_1.setAttribute("value","n");
					var label_1 = document.createElement("label");
					label_1.setAttribute("for","pg_exclude_schema");
					label_1.innerHTML = "einschließen";

					var radio_2	=	document.createElement("input");
					radio_2.setAttribute("type","radio");
					radio_2.setAttribute("name","pgdump_in_exclude_schemas");
					radio_2.setAttribute("id","pg_exclude_schema");
					radio_2.setAttribute("value","N");
					var label_2 = document.createElement("label");
					label_2.setAttribute("for","pg_include_schema");
					label_2.innerHTML = "ausschließen";

					<?php
						switch ($this->formvars['pgdump_in_exclude_schemas']) {
						case 'N':	//exclude
							echo 'radio_2.checked = true;';
							break;
						case 'n':	//include
							echo 'radio_1.checked = true;';
							break;
						case '':	//nicht gesesetz
							echo 'radio_0.checked = true;';
							break;
						default:
							echo 'radio_0.checked = true;';
							break;
						}
				 	?>

					fieldset.appendChild(radio_1);
					fieldset.appendChild(label_1);
					fieldset.appendChild(radio_2);
					fieldset.appendChild(label_2);
					fieldset.appendChild(radio_0);
					fieldset.appendChild(label_0);

					var schema_liste = document.createElement("input");
					schema_liste.setAttribute("type","text")
					schema_liste.setAttribute("name","pgdump_schema_list");
					schema_liste.setAttribute("size","80");
					schema_liste.setAttribute("maxlength","255");
					schema_liste.setAttribute("placeholder","kommagetrennte Liste der Schemen");
					schema_liste.setAttribute("value","<?php echo $this->formvars['pgdump_schema_list']; ?>");

					// Zeile füllen
					tr3.appendChild(col1);
					tr3.appendChild(col2);
					col2.appendChild(fieldset);
					col2.appendChild(schema_liste);

					//4. Zeile Tabelle ein/ausschließen
					const tr4 = tab_obj.insertRow(-1);

					var col1 = document.createElement("td");
					var col2 = document.createElement("td");
					col1.setAttribute("class","fetter");
					col1.setAttribute("align","right");
					col1.setAttribute("style","vertical-align:top");
					col1.innerHTML = "folgende Tabellen";

					// Radiobuttons bauen
					var fieldset =	document.createElement("fieldset");
					var radio_0	=	document.createElement("input");
					radio_0.setAttribute("type","radio");
					radio_0.setAttribute("name","pgdump_in_exclude_tables");
					radio_0.setAttribute("id","pg_exclude_table");
					radio_0.setAttribute("value","");
					var label_0 = document.createElement("label");
					label_0.setAttribute("for","pg_exclude_table");
					label_0.innerHTML = "Option nicht verwenden";

					var radio_1 = document.createElement("input");
					radio_1.setAttribute("type","radio");
					radio_1.setAttribute("name","pgdump_in_exclude_tables");
					radio_1.setAttribute("id","pg_include_table");
					radio_1.setAttribute("value","t");
					var label_1 = document.createElement("label");
					label_1.setAttribute("for","pg_exclude_table");
					label_1.innerHTML = "einschließen";

					var radio_2	=	document.createElement("input");
					radio_2.setAttribute("type","radio");
					radio_2.setAttribute("name","pgdump_in_exclude_tables");
					radio_2.setAttribute("id","pg_exclude_table");
					radio_2.setAttribute("value","T");
					var label_2 = document.createElement("label");
					label_2.setAttribute("for","pg_exclude_table");
					label_2.innerHTML = "ausschließen";

					<?php
						switch ($this->formvars['pgdump_in_exclude_tables']) {
						case 'T':	//exclude
							echo 'radio_2.checked = true;';
							break;
						case 't':	//include
							echo 'radio_1.checked = true;';
							break;
						case '':	//nicht gesesetz
							echo 'radio_0.checked = true;';
							break;
						default:
							echo 'radio_0.checked = true;';
							break;
						}
				 	?>

					fieldset.appendChild(radio_1);
					fieldset.appendChild(label_1);
					fieldset.appendChild(radio_2);
					fieldset.appendChild(label_2);
					fieldset.appendChild(radio_0);
					fieldset.appendChild(label_0);

					const table_liste = document.createElement("input");
					table_liste.setAttribute("type","text")
					table_liste.setAttribute("name","pgdump_table_list");
					table_liste.setAttribute("size","80");
					table_liste.setAttribute("maxlength","255");
					table_liste.setAttribute("placeholder","kommagetrennte Liste der Tabellen");
					table_liste.setAttribute("value","<?php echo $this->formvars['pgdump_table_list']; ?>");

					// Zeile füllen
					tr4.appendChild(col1);
					tr4.appendChild(col2);
					col2.appendChild(fieldset);
					col2.appendChild(table_liste);

					break;

				case 'Verzeichnissicherung':
					//1. Zeile INSERT anstatt COPY
					var tr1 = tab_obj.insertRow(-1);

					var col1 = document.createElement("td");
					var col2 = document.createElement("td");
					col1.setAttribute("class","fetter");
					col1.setAttribute("align","right");
					col1.innerHTML = "Archiv komprimieren (gzip)";

					var chkbx_1	=	document.createElement("input");
					chkbx_1.setAttribute("type","checkbox");
					chkbx_1.setAttribute("name","tar_compress");
					chkbx_1.setAttribute("value","1");
					<?php if ($this->formvars['tar_compress']) { ?>
						chkbx_1.checked = true;
					<?php } ?>

					tr1.appendChild(col1);
					tr1.appendChild(col2);
					col2.appendChild(chkbx_1);

					break;

				default:
					break;
			}
		}
	}

</script>
<?php $this->inhalt->get_mysql_database_names(); ?>

<table border="0"e cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr>
		<td colspan="2" align="center"><?php
			if ($this->Meldung == $strSuccess OR $this->Meldung == '') {
				$bgcolor = BG_FORM;
			}
			else {
				$this->Fehlermeldung = $this->Meldung;
				include('Fehlermeldung.php');
				$bgcolor = BG_FORMFAIL;
			} ?>
		</td>
	</tr>
	<tr>
		<td align="left"><h2><?php echo $strSicherungsinhalt; ?></h2></td>
		<td align="right"><a class="btn btn-new" href="index.php?go=Sicherung_editieren&id=<?php echo ( empty($this->formvars['sicherung_id']) ? $this->formvars['sicherung_id'] : $this->inhalt->get('sicherung_id') )?>" ><i class="fa fa-list" style="color: white;"></i><?php echo $strSicherung_anzeigen ?></a></td>
	</tr>
	<tr>
		<td colspan="2">
			<? if (!$this->inhalt->get_sicherung_has_target_dir()){
				echo $strHintNurRSYNC;
			}
			 ?>
		</td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strName ?></td>
		<td>
			<input name="name" type="text" value="<?php echo $this->formvars['name']; ?>" size="80" maxlength="255" placeholder="<? echo $strName; ?>">
		</td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strBeschreibung ?></td>
		<td>
			<input name="beschreibung" type="text" value="<?php echo $this->formvars['beschreibung']; ?>" size="80" maxlength="255" placeholder="<? echo $strBeschreibung; ?>">
		</td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strMethode ?></td>
		<td><?
			echo FormObject::createSelectField(
				'methode',
				$this->inhalt->get_option_list_for_methods(),
				$this->formvars['methode'],
				1,
				'',
				'javascript: switch_methode(this.options[this.selectedIndex].value)'
			); ?>
		</td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strQuelle ?></td>
		<td id="td_select_source">
			<input id="select_source_text" name="source" type="text" value="<?php echo $this->formvars['source']; ?>" size="80" maxlength="255" placeholder="<? echo $strQuelle; ?>">
		</td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strZielBezeichnung ?></td>
		<td>
			<input id="feld_target" name="target" type="text" value="<?php echo $this->formvars['target']; ?>" size="80" maxlength="255" placeholder="<? echo $strZielBezeichnung; ?>">
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<table id="tab_options" border="0" cellpadding="5" cellspacing="0">
			</table>
		</td>
	</tr>
	<tr id="tr_save_abort_buttons">
		<td colspan="2" align="right">
			<input type="hidden" name="go" value="sicherungsinhalt_speichern">
			<input type="hidden" name="sicherung_id" value="<?php echo $this->formvars['sicherung_id'] ?>">
			<input type="hidden" name="id" value="<?php echo $this->formvars['id']  ?>">
			<input type="button" name="cancle" value="<? echo $this->strCancel; ?>" onclick="document.location.href = 'index.php?go=Sicherung_editieren&id=<?php echo ( empty($this->inhalt->get('sicherung_id')) ? $this->formvars['sicherung_id'] : $this->inhalt->get('sicherung_id') )?>'">
			<input type="submit" name="bttn" value="<?php echo ($this->formvars['id'] > 0 ? $this->strSave : $this->strCreate); ?>" >
		</td>
	</tr>
</table>
<script>

	var o = document.getElementById('methode');
	if (o) {
		o.onchange();
	}

</script>
