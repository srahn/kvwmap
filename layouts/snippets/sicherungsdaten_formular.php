<?php
  include(LAYOUTPATH . 'languages/sicherungsdaten_' . $this->user->rolle->language . '.php');
?>
<script>$('#gui-table').css('width', '100%')</script>
<script language="javascript" type="text/javascript">
	function set_intervall_typ(typ){
		const obj = document.getElementById('intervall_typ_list');

		if (obj){
			for (i = 0; i < obj.length; i++ ){
				if (obj.options[i].value == typ){
					obj.options[i].selected='selected';
				}
			}
		}
	}

	function set_intervall_optionen(value){
			var l1 = document.getElementById('select_parameter1_list');
			var l2 = document.getElementById('select_parameter2_list');

			l1.length=0;
			l2.length=0;


		switch (value) {
			case "daily":
				document.getElementById('label_para1_list').innerHTML='ausführen an Wochentag von';
				l1.options[0]=new Option("Sonntag",0);
				l1.options[1]=new Option("Montag",1);
				l1.options[2]=new Option("Dienstag",2);
				l1.options[3]=new Option("Mittwoch",3);
				l1.options[4]=new Option("Donnerstag",4);
				l1.options[5]=new Option("Freitag",5);
				l1.options[6]=new Option("Samstag",6);

				document.getElementById('tr_select_para2_list').style.display='';
				document.getElementById('label_para2_list').innerHTML='bis';
				l2.options[0]=new Option("Sonntag",0);
				l2.options[1]=new Option("Montag",1);
				l2.options[2]=new Option("Dienstag",2);
				l2.options[3]=new Option("Mittwoch",3);
				l2.options[4]=new Option("Donnerstag",4);
				l2.options[5]=new Option("Freitag",5);
				l2.options[6]=new Option("Samstag",6);

				break;
			case "weekly":
				document.getElementById('label_para1_list').innerHTML='ausführen am Wochentag';
				l1.options[0]=new Option("Sonntag",0);
				l1.options[1]=new Option("Montag",1);
				l1.options[2]=new Option("Dienstag",2);
				l1.options[3]=new Option("Mittwoch",3);
				l1.options[4]=new Option("Donnerstag",4);
				l1.options[5]=new Option("Freitag",5);
				l1.options[6]=new Option("Samstag",6);

				document.getElementById('tr_select_para2_list').style.display='none';

				break;
			case "monthly":
				document.getElementById('label_para1_list').innerHTML='ausführen am ... des Monats';
				for (i = 0; i < 31; i++){ //foreach TODO
					l1.options[i]=new Option(i+1,i);
				}
				document.getElementById('tr_select_para2_list').style.display='none';
				break;
			case "manual":
				break;
		}

		if ('<?php echo $this->sicherung->get('intervall_parameter_1'); ?>'.length == 0){
			var l1_pos = 0;
		} else {
			var l1_pos = '<?php echo $this->sicherung->get('intervall_parameter_1'); ?>';
			l1.options[l1_pos].selected = 'selected';
		}

		if ('<?php echo $this->sicherung->get('intervall_parameter_2'); ?>'.length == 0){
			var l2_pos = 0;
		} else {
			var l2_pos = '<?php echo $this->sicherung->get('intervall_parameter_2'); ?>';
			l2.options[l2_pos].selected = 'selected';
		}

	}

	function init(){
		var intervaltyp = '<?php echo $this->sicherung->get('intervall_typ'); ?>';
		set_intervall_typ(intervaltyp);
		set_intervall_optionen(intervaltyp);
/*
		if ('<?php echo $this->sicherung->get('intervall_parameter_1'); ?>'.length == 0){
			pos1 = 0
		} else {
			pos1 = <?php echo $this->sicherung->get('intervall_parameter_1'); ?>-1;
		}

		if ('<?php echo $this->sicherung->get('intervall_parameter_2'); ?>'.length == 0){
			pos2 = 0
		} else {
			pos2 = <?php echo $this->sicherung->get('intervall_parameter_2'); ?>-1;
		}
*/
		//document.getElementById('select_parameter1_list').options[pos1].selected='selected';
		//document.getElementById('select_parameter2_list').options[pos2].selected='selected';

	}

	document.addEventListener("DOMContentLoaded", function(event) {
		init();

	});

</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
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
		<td align="left"><h2><?php echo $strTitel_single; ?></h2></td>
		<td align="right"><a class="btn btn-new" href="index.php?go=Sicherungen_anzeigen"><i class="fa fa-list" style="color: white;"></i><?php echo $strAlleSicherungen ?></a></td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strName ?></td>
		<td>
			<input name="name" type="text" value="<?php echo $this->sicherung->get('name'); ?>" size="80" maxlength="255" placeholder="<? echo $strName; ?>">
		</td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strBeschreibung ?></td>
		<td>
			<input name="beschreibung" type="text" value="<?php echo $this->sicherung->get('beschreibung'); ?>" size="80" maxlength="255" placeholder="<? echo $strBeschreibung; ?>">
		</td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strZielVerz ?></td>
		<td>
			<input name="target_dir" type="text" value="<?php echo $this->sicherung->get('target_dir'); ?>" size="80" maxlength="255" placeholder="<? echo $strZielVerz; ?>">
		</td>
	</tr>
	<tr id="intervalltyp">
		<td class="fetter" align="right"><?php echo $strIntervall ?></td>
		<td>
			<select id="intervall_typ_list" name="intervall_typ" onchange="javascript: set_intervall_optionen(this.options[this.selectedIndex].value);">
				<option value="daily">täglich</option>
				<option value="weekly">wöchentlich</option>
				<option value="monthly">monatlich</option>
			</select>
		</td>
	</tr>
	<tr id="tr_select_para1_list">
		<td class="fetter" align="right" id="label_para1_list">Tag von</td>
		<td>
			<select id="select_parameter1_list" name="intervall_parameter_1">
				<option></option>
			</select>
		</td>
	</tr>
	<tr id="tr_select_para2_list">
		<td class="fetter" align="right" id="label_para2_list">Tag bis</td>
		<td>
			<select id="select_parameter2_list" name="intervall_parameter_2">
				<option></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="fetter" align="right"><?php echo $strexectime	?></td>
		<td> <input type="time" name="intervall_start_time" value="<?php echo $this->sicherung->get_intervall_start_time_for_html_input() ?>"></td>
	</tr>
	<tr>
		<td align="right"><div class="fetter"><? echo $strKeepNDays ?></div> 0 = nicht löschen</td>
		<td><input type="number" name="keep_for_n_days" min="0" step="1" value="<? echo $this->formvars['keep_for_n_days'] ?>"></td>
	</tr>
	<tr>
		<td><?php echo '' ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<input type="hidden" name="go" value="Sicherung_speichern">
			<input type="hidden" name="id" value="<?php echo $this->sicherung->get('id') ?>">
			<input type="button" name="cancle" value="<? echo $this->strCancel; ?>" onclick="document.location.href = 'index.php?go=Sicherungen_anzeigen'">
			<input type="submit" name="bttn" value="<?php echo ($this->sicherung->get('id') > 0 ? $this->strSave : $this->strCreate); ?>" >
		</td>
	</tr>
</table>
<br><?php
if ( $this->sicherung->get('id') > 0 ) {
	include(SNIPPETS . 'sicherungsinhalte.php');
} ?>
