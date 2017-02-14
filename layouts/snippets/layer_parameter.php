<script type="text/javascript">
<!--
	function addParam(){
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
				</tr>
			<? for($i = -1; $i < count($this->params); $i++){ ?>
				<tr id="param<? echo $i; ?>" <? if($i == -1)echo 'style="display:none"'; ?>>
					<td style="border:1px solid #C3C7C3"><input name="id[]" type="text" readonly="true" value="<? echo $this->params[$i]['id']; ?>" size="4"></td>
					<td style="border:1px solid #C3C7C3"><input name="key[]" type="text" value="<? echo $this->params[$i]['key']; ?>" size="20" maxlength="50"></td>
					<td style="border:1px solid #C3C7C3"><input name="alias[]" type="text" value="<? echo $this->params[$i]['alias']; ?>" size="20" maxlength="50"></td>
					<td style="border:1px solid #C3C7C3"><input name="default_value[]" type="text" value="<? echo $this->params[$i]['default_value'];; ?>" size="20"></td>
					<td style="border:1px solid #C3C7C3"><textarea name="options_sql[]" style="height: 22px"><? echo $this->params[$i]['options_sql']; ?></textarea></td>
				</tr>
			<? } ?>
			</table>
			<i class="fa fa-plus-square pointer" style="margin: 5px; font-size: 20px" aria-hidden="true" title="Parameter hinzufügen" onclick="addParam();"></i>
		</td>
	</tr>
	<tr>
		<td align="center">			
			<input type="submit" value="Speichern">
		</td>
	</tr>
</table>
<input type="hidden" name="go" value="Layer_Parameter_speichern">
      
