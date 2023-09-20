<style>

</style>
<script language="javascript" type="text/javascript">
	function edit(target) {
		var id = $(target).parent().parent().attr('id');
		console.log('edit id: ', id);
		$('.control').hide();
		$('.mod').hide();
		$('.btn-new.mod').show();
		$('#save_link_' + id).show();
		$('#quit_link_' + id).show();
		$('tr [id=' + id + '] td').each(makeEditable);
	}

	function quit(target) {
		var id = $(target).parent().parent().attr('id');
		console.log('quit id: ', id);
		$('.control').show();
		$('.mod').hide();
		$('form')[0].reset();
		$('tr [id=' + id + '] td').each(makeReadable);
	}

	function add() {
		var row = $('#myobjects_head').next().clone();
		$('.control').hide();
		$('.mod').hide();
		$('.btn-new.mod').show();
		row.attr('id', 0);
		row.children().html('');
		row.children().last().html('\
				<a id="edit_link_0" href="#" onclick="edit(this, \'<? echo $this->myobjects[0]->tableName; ?>\');" style="margin-left: 1px; display: none;" class="control"><i class="fa fa-pencil fa_lg" style="color: #b22222;" title="Ändern"></i></a>\
				<a id="drop_link_0" href="#" onclick="drop(this, \'<? echo $this->myobjects[0]->tableName; ?>\');" style="margin-left: 5px; display: none;" class="control"><i class="fa fa-trash-o fa_lg" style="color: #b22222" title="Löschen"></i></a>\
				<a id="save_link_0" href="#" onclick="save(this, \'<? echo $this->myobjects[0]->tableName; ?>\');" style="margin-left: 1px;" class="mod"><i class="fa fa-check fa_lg" style="color: #087e08;" title="Speichern"></i></a>\
				<a id="quit_link_0" href="#" onclick="drop(this, \'<? echo $this->myobjects[0]->tableName; ?>\');" style="margin-left: 5px;" class="mod"><i class="fa fa-times fa_lg" style="color: #b22222" title="Abbrechen"></i></a>\
		');
		$('#myobjects_head').after(row);
		$('tr [id=0] td').each(makeEditable);
	}

	function save(target, go) {
		var id = $(target).parent().parent().attr('id');
		console.log('save id: ', id);

		//todo testen mit ajax context in success und error func hinzufügen
		$.ajax({
			url: 'index.php?go=' + go + '_' + (id == 0 ? 'create' : 'update'),
			data: $('form td .editable input').serialize() + '&id=' + id + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>',
			success: function(response) {
				var result = JSON.parse(response)[0],
						new_id = result.id;

				if (result.success) {
					$('.control').show();
					$('.mod').hide();
					if (id == 0 && new_id != '') {
						// set new_id in ID field and in id attribut of tr tag
						$('tr [id=' + id + ']').attr('id', new_id).children().first().html(new_id);
						id = new_id;
					}
					$('tr [id=' + id + '] td').each(makeReadable);
					message([{type: 'notice', msg: result.msg}]);
				}
				else {
					message([{type: 'error', msg: result.err_msg}]);
				}
			}
		});
	}

	function drop(target, go) {
		var row = $(target).parent().parent(),
				id = row.attr('id'),
				r = false;
		console.log('drop id: ', id);

		if (id == 0) {
			r = confirm('Eingabe verwerfen?');
			if (r == true) {
				row.fadeOut(500, function () {
					$(this).remove();
					$('.control').show();
					$('.mod').hide();
				});
			}
		}
		else {
			r = confirm('ID: ' + id + ' wirklich Löschen?');
			if (r == true) {
				// todo testen mit ajax und error func hinzufügen
				$.ajax({
					url: 'index.php?go=' + go + '_delete',
					data: {
						id: id,
						csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
					},
					success: function(response) {
						var result = JSON.parse(response)[0];
						if (result.success) {
							row.fadeOut(1000, function () {
								$(this).remove();
								message([{type: 'notice', msg: 'ID: ' + $(this).attr('id') + ' erfolgreich gelöscht!'}]);
							});
						}
						else {
							message([{type: 'error', msg: result.err_msg}]);
						}
					}
				});
			}
			else {
				message([{type: 'notice', msg: 'OK, nix passiert'}]);
			}
		}
	}

	function makeEditable() {
		var elm = $(this),
				name = elm.attr('name'),
				type = elm.attr('type'),
				value = elm.attr('value'),
				size = elm.attr('size');

		if (elm.attr('class') == 'editable') {
			console.log('makeEditable field: ' + name);
			elm.html('<input type="' + (type == 'text' ? 'text' : 'password') + '" name="' + name + '" value="' + (type == 'password' ? value : elm.html()) + '" size="' + size + '">');
		}
	}

	function makeReadable() {
		var elm = $(this),
				name = elm.attr('name'),
				type = elm.attr('type'),
				value = elm.children().val();

		if (elm.attr('class') == 'editable') {
			console.log('makeReadable field: ' + name);
			if (elm.attr('type') == 'password') {
				elm.attr('value', value);
				elm.html('****');
			}
			else {
				elm.html(value);
			}
		}
	}

	function update(id, go) {
		// get data from form
		// data = ...
		window.location = 'index.php?go=' + go + '_speichern' + data;
	}
</script>
<table width="1000" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin-bottom: 40px;">
  <tr>
    <td align="center" colspan="7" style="height: 30px"><h2><? echo $this->myobjects[0]->alias; ?></h2></td>
	</tr>
	<tr id="myobjects_head"><?
		foreach ($this->myobjects[0]->attributes as $attribute) {
			echo '<th>' . $attribute['alias'] . '</th>';
		} ?>
		<th width="40px">
			<a class="btn btn-new control" href="#" onclick="add()"><i titel="Lege einen neuen Datensatz an." class="fa fa-plus" style="color: white; width: 25px"></i></a>
			<a class="btn btn-new mod" href="#" style="display:none; background-color: gray; background-image: linear-gradient(white, gray); border: 1px solid gray; cursor: default"><i class="fa fa-plus" style="color: white; width: 25px;"></i></a>
		</th>
	</tr><?php
	foreach ($this->myobjects AS $myobject) { ?>
		<tr id="<?php echo $myobject->get('id'); ?>"><?
			foreach ($myobject->attributes as $attribute) {
				echo '
				<td 
					class="' . $attribute['privileg'] . '" 
					name="' . $attribute['attribute'] . '" 
					type="' . $attribute['type'] . '" 
					size="' . $attribute['size'] . '"
					' . ($attribute['type'] == 'password'? 'value="' . $myobject->get($attribute['attribute']) . '"' : '') . '
				>' . ($attribute['type'] == 'password'? '****' : $myobject->get($attribute['attribute'])) . '</td>';
			}
			echo '
				<td width="40px">
					<a id="edit_link_' . $myobject->get('id') . '" href="#" onclick="edit(this, \'' . $myobject->tableName . '\');" style="margin-left: 1px;" class="control"><i class="fa fa-pencil fa_lg" title="Ändern"></i></a>
					<a id="drop_link_' . $myobject->get('id') . '" href="#" onclick="drop(this, \'' . $myobject->tableName . '\');" style="margin-left: 5px;" class="control"><i class="fa fa-trash-o fa_lg" title="Löschen"></i></a>
					<a id="save_link_' . $myobject->get('id') . '" href="#" onclick="save(this, \'' . $myobject->tableName . '\');" style="margin-left: 1px; display: none" class="mod"><i class="fa fa-check fa_lg" style="color: #087e08;" title="Speichern"></i></a>
					<a id="quit_link_' . $myobject->get('id') . '" href="#" onclick="quit(this, \'' . $myobject->tableName . '\');" style="margin-left: 5px; display: none" class="mod"><i class="fa fa-times fa_lg" title="Abbrechen"></i></a>
				</td>
		<tr>';
	} ?>
</table>
<? if($this->formvars['selected_layer_id']){ ?>
	<input type="button" value="zurück zum Layer" onclick="location.href='index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'"><br><br>
<? } ?>