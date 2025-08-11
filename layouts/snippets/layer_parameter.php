<script type="text/javascript">
<!--
	function addParam() {
		document.getElementById('param-1').style.display = '';

		param = document.getElementById('param-1').cloneNode(true);
		document.getElementById('param-1').style.display = 'none';
		document.getElementById('params_table').getElementsByTagName('tbody')[0].appendChild(param);
	}
-->
</script>
<br>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2>Layer-Parameter</h2></td>
  </tr>
  <tr>
    <td align="left">
			<table id="params_table" border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse">
				<tr>
					<th align="left" style="border:1px solid #C3C7C3">ID</th>
					<th align="left" style="border:1px solid #C3C7C3">Name</th>
					<th align="left" style="border:1px solid #C3C7C3">Alias</th>
					<th align="left" style="border:1px solid #C3C7C3">Standard-Wert</th>
					<th align="left" style="border:1px solid #C3C7C3">Auswahlmöglichkeiten</th>
					<th align="left" style="border:1px solid #C3C7C3">Mehrfachauswahl</th>
					<th align="left" style="border:1px solid #C3C7C3">Verwendung in Layer</th>
					<th align="left" style="border:1px solid #C3C7C3">&nbsp;</th>
				</tr><?
				for ($i = -1; $i < count($this->params); $i++) { ?>
					<tr id="param<? echo $i; ?>" <? if ($i == -1) echo 'style="display:none"'; ?>>
						<td style="border:1px solid #C3C7C3"><input name="id[]" type="text" readonly="true" value="<? echo $this->params[$i]['id']; ?>" size="4"></td>
						<td style="border:1px solid #C3C7C3"><input name="key[]" type="text" value="<? echo $this->params[$i]['key']; ?>" size="20" maxlength="50"></td>
						<td style="border:1px solid #C3C7C3"><input name="alias[]" type="text" value="<? echo $this->params[$i]['alias']; ?>" size="20" maxlength="50"></td>
						<td style="border:1px solid #C3C7C3"><input name="default_value[]" type="text" value="<? echo $this->params[$i]['default_value'];; ?>" size="20"></td>
						<td style="border:1px solid #C3C7C3"><textarea name="options_sql[]" style="height: 22px"><? echo $this->params[$i]['options_sql']; ?></textarea></td>
						<td style="border:1px solid #C3C7C3"><input name="multiple[<? echo $i+1; ?>]" type="checkbox"<? echo ($this->params[$i]['multiple'] == 't' ? ' checked' : ''); ?>/></td>
						<td style="border:1px solid #C3C7C3"><?
							$layer_count = count_or_0($this->params_layer[$this->params[$i]['id']]) - 1;
							if ($layer_count > 0){
								echo '<ul>';
								for ($j = 0; $j < $layer_count; $j++){
									echo '<li>
										<a href="index.php?go=Layereditor&selected_layer_id=' . $this->params_layer[$this->params[$i]['id']][$j]['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->params_layer[$this->params[$i]['id']][$j]['name'] . ' (' . $this->params_layer[$this->params[$i]['id']][$j]['layer_id'] . ')</a>
									</li>';
								}
								echo '</ul>';
							} ?>
						</td>
						<td style="border:1px solid #C3C7C3"><i class="fa fa-trash pointer" aria-hidden="true" onclick="$(this).parent().parent().find(':input').val('')"></i></td>
					</tr><?
				} ?>
			</table>
			<a href="javascript:void(0);" onclick="addParam();">
				<i class="fa fa-plus buttonlink" style="margin: 5px; padding: 6px" aria-hidden="true" title="Parameter hinzufügen"></i>
			</a>
		</td>
	</tr>
	<tr>
		<td align="center">	
			<input type="submit" value="Speichern">
		</td>
	</tr>
</table>
<input type="hidden" name="go" value="Layer_Parameter_speichern">