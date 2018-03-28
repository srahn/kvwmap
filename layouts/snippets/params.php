<?php
	$params = $this->user->rolle->get_layer_params($this->Stelle->selectable_layer_params, $this->pgdatabase);
?><script language="javascript" type="text/javascript">
	function toggleLayerParamsBar() {
		var openLayerParamBarIcon = $('#openLayerParamBarIcon'),
				layerParamsBar = $('#layerParamsBar');
				if (layerParamsBar.is(':visible'))
					layerParamsBar.fadeOut()
				else
					layerParamsBar.fadeIn()
	}

	function updateLayerParams() {
		var data = 'go=setLayerParams<?php
		foreach($params AS $param) {
			echo '&layer_parameter_' . $param['key'] . "=' + document.getElementById('layer_parameter_" . $param['key'] . "').value + '";
		} ?>';
		ahah('index.php', data, [''], ['execute_function']);
	}

	function onLayerParameterChanged(parameter) {
		var updateLayerParameterButton = $('#update_layer_parameter_button');
		updateLayerParameterButton.fadeIn();
	}

	function onLayerParamsUpdated(status) {
		var updateLayerParameterButton = $('#update_layer_parameter_button'),
				layerParamsBar = $("#layerParamsBar");
		updateLayerParameterButton.fadeOut();
		layerParamsBar.fadeOut();
	}
</script><?
if (!empty($params)) { ?>
	<table>
		<tr width="100%" align="center">
			<td>
				<div style="position: relative;">
					<div style="position: relative;">
						<i id="openLayerParamBarIcon" class="fa fa-bars button pointer" onclick="toggleLayerParamsBar();"></i>
					</div>
					<div id="layerParamsBar" class="layerOptions">
						<div style="position: absolute; top: 2px; right: 2px;">
							<img style="border:none" src="graphics/exit2.png" onclick="toggleLayerParamsBar();">
						</div>
						<table>
							<tr height="22px">
								<td class="layerOptionsHeader" colspan="2" width="350"><span class="fett">Themenparameter</span></td>
							</tr><?php
							foreach($params AS $param) { ?>
								<tr>
									<td class="layerOptionHeader"><?php echo $param['alias']; ?></td>
									<td><?php
										echo FormObject::createSelectField(
											'layer_parameter_' . $param['key'],		# name
											$param['options'],										# options
											rolle::$layer_params[$param['key']],	# value
											1,																		# size
											'',																		# style
											'onLayerParameterChanged(this);',			# onchange
											'layer_parameter_' . $param['key'],		# id
											''																		# multiple
										); ?>
									</td>
								</tr><?php
							} ?>
							<tr>
								<td colspan="2" align="center">
									<input type="button" id="update_layer_parameter_button" value="Speichern" style="display: none" onclick="updateLayerParams();">
								</td>
							</tr>
						</table>
					</div>
				</div>
			</td>
		</tr>
	</table><?
} ?>