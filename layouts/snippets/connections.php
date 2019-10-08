<?php
	include(LAYOUTPATH.'languages/connections_' . $this->user->rolle->language . '.php');
?>
<style>
</style>
<script language="javascript" type="text/javascript">
	function edit(target) {
		var id = $(target).parent().parent().attr('id');
		console.log('edit id: ', id);
		$('#edit_link_' + id).hide();
		$('#save_link_' + id).show();
		$('tr [id=' + id + '] td').each(makeEditable);
	}

	function save(target) {
		var id = $(target).parent().parent().attr('id');
		console.log('save id: ', id);

		//todo testen mit ajax context in success und error func hinzufügen
		$.ajax({
			url: 'index.php&go=connection_' + (id == 0 ? 'create' : 'update'),
			data: $('form td .editable input').serialize(),
			success: function(response) {
				var result = JSON.parse(response);
				if (result.success) {
					$('#edit_link_' + id).show();
					$('#save_link_' + id).hide();
					$('tr [id=' + id + '] td').each(makeReadable);
					message([{type: 'notice', msg: result.msg}]);
				}
				else {
					message([{type: 'error', msg: result.err_msg}]);
				}
			}
		});
	}

	function drop(target) {
		var row = $(target).parent().parent(),
				id = row.attr('id');
		console.log('drop id: ', id);

		var r = confirm('Connection id: ' + id + ' wirklich Löschen?');
		if (r == true) {
			// todo testen mit ajax und error func hinzufügen
			$.ajax({
				url: 'index.php&go=connection_delete',
				data: { id: id },
				success: function(response) {
					var result = JSON.parse(response);
					if (result.success) {
						row.fadeOut(1000, function () {
							$(this).remove();
							message([{type: 'notice', msg: 'Connection id: ' + $(this).attr('id') + ' gelöscht!'}]);
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

	function update(id) {
		// get data from form
		// data = ...
		window.location = 'index.php?go=connection_speichern' + data;
	}
</script>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin-bottom: 40px;">
  <tr>
    <td align="center" colspan="7"><h2><?php echo $strTitle; ?></h2></td>
	</tr>
	<tr>
		<th>ID</th>
		<th>Bezeichnung</th>
		<th>Host</th>
		<th>Port</th>
		<th>Datenbankname</th>
		<th>Nutzername</th>
		<th>Password</th>
		<th><a class="btn btn-new" href="index.php?go=connection_editieren"><i titel="Lege einen neue Connection an." class="fa fa-plus" style="color: white; width: 25px"></i></a></th>
	<tr><?php
	foreach ($this->connections AS $connection) { ?>
		<tr id="<?php echo $connection->get('id'); ?>">
			<td><?php echo $connection->get('id'); ?></td>
			<td class="editable" name="name" type="text" size="40"><?php echo $connection->get('name'); ?></td>
			<td class="editable" name="host" type="text" size="8"><?php echo $connection->get('host'); ?></td>
			<td class="editable" name="port" type="text" size="5"><?php echo $connection->get('port'); ?></td>
			<td class="editable" name="dbname" type="text" size="10"><?php echo $connection->get('dbname'); ?></td>
			<td class="editable" name="user" type="text" size="15"><?php echo $connection->get('user'); ?></td>
			<td class="editable" name="password" type="password" value="<?php echo $connection->get('password'); ?>" size="12">****</td>
			<td>
				<a href="#" onclick="edit(this);"><i id="edit_link_<?php echo $connection->get('id'); ?>" class="fa fa-pencil fa_lg" style="color: #a82e2e;"></i></a>
				<a href="#" onclick="save(this);"><i id="save_link_<?php echo $connection->get('id'); ?>" class="fa fa-check fa_lg" style="display: none; color: #087e08;"></i></a>
				<a href="#" onclick="drop(this);" style="margin-left: 5px;"><i class="fa fa-trash-o fa_lg" style="color: #a82e2e;"></i></a>
			</td>
		<tr><?php
	} ?>
</table>