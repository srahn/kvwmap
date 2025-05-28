<?
	include(LAYOUTPATH.'languages/showadminfunctions_'.rolle::$language.'.php');
?>

<script type="text/javascript">

function toggleGroup(group, show){
	var img = document.getElementById(group);
	var constants = document.getElementsByClassName('constants_'+group);
	if(show || img.src.split(/[\\/]/).pop() == 'plus.gif'){
		img.src = '<? echo GRAPHICSPATH.'minus.gif'; ?>';		
		display = '';
	}
	else{
		img.src = '<? echo GRAPHICSPATH.'plus.gif'; ?>';		
		display = 'none';
	}
	[].forEach.call(constants, function(constant){constant.style.display = display;});
}

</script>

<br><h2><?php echo $strTitle; ?></h2><br>
<? global $kvwmap_plugins; ?>

<table cellpadding="2" cellspacing="12">
	<? if(defined('GIT_USER') AND GIT_USER != ''){ ?>
	<tr>
		<td valign="top" align="center" style="border:1px solid #C3C7C3">			
			<table width="100%" cellpadding="4" cellspacing="2" border="0" style="border:1px solid #C3C7C3;border-collapse:collapse">
				<tr style="border:1px solid #C3C7C3">
					<td colspan="3" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17"><? echo $strUpdateCode; ?></span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3">
					<td><?
						include(SNIPPETS . 'git_remote_update.php');
						include(SNIPPETS . 'git_status.php');?>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input
							type="button"
							onclick="location.href='index.php?go=Administratorfunktionen&func=update_code_and_databases&csrf_token=<? echo $_SESSION['csrf_token']; ?>'"<?
							if ($num_commits_behind == '' AND !$diverged) { ?>
								disabled<?
							} ?>
							value="<? echo $strUpdate; ?>"
						></td>
				</tr>
			</table> 
		</td>
	</tr>
	<? } ?>
	<tr>
		<td valign="top" align="center" style="border:1px solid #C3C7C3">			
			<table width="100%" cellpadding="4" cellspacing="2" border="0" style="border:1px solid #C3C7C3;border-collapse:collapse">
				<tr style="border:1px solid #C3C7C3">
					<td colspan="3" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17"><? echo $strUpdateDBs; ?></span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3">
					<td><span class="fett"><? echo $strComponent; ?></span></td>
					<td align="right"><span class="fett">Status</span></td>
				</tr><?
				foreach ($this->administration->schema_migration_files as $component => $component_migrations) {
					$postgresql_counter = (array_key_exists($component, $this->administration->migrations_to_execute['postgresql']) ? count($this->administration->migrations_to_execute['postgresql'][$component]) : 0);
					$seed_counter = (array_key_exists($component, $this->administration->seeds_to_execute['postgresql']) ? count($this->administration->seeds_to_execute['postgresql'][$component]) : 0); ?>
					<tr style="border:1px solid #C3C7C3;">
						<td><?	echo $component; ?></td>
						<td align="right"><?
							if ($postgresql_counter == 0) {
								echo ' Schemata aktuell';
							}
							else {
								$title = @implode('&#10;', $this->administration->migrations_to_execute['postgresql'][$component] ?: []);
								echo '<span class="fett red" title="'.$title.'">';
								$update_necessary = true;
								if($postgresql_counter > 0)echo 'PostgreSQL-Schema ';
								echo ' nicht aktuell</span>';
							}
							if ($seed_counter > 0) {
								$update_necessary = true;
								echo ',<br> neue Menüs- bzw. Layer verfügbar';
							} ?>
						</td>
					</tr><?
				}
				if ($this->administration->seed_files != '') {
					foreach ($this->administration->seed_files as $component => $component_seeds) {			// die restlichen Plugins, die kein DB-Schema haben
						if ($this->administration->schema_migration_files[$component] == NULL AND (array_key_exists($component, $this->administration->seeds_to_execute['postgresql']) ? count($this->administration->seeds_to_execute['postgresql'][$component]) : 0) > 0) { ?>
							<tr style="border:1px solid #C3C7C3;">
								<td><?	echo $component; ?></td>
								<td align="right">
								<?	$update_necessary = true;
										echo 'neue Menüs- bzw. Layer verfügbar';
										?>
								</td>
							</tr><?
						}
					}
				} ?>
				<tr>
					<td colspan="2" align="center"><input type="button" onclick="location.href='index.php?go=Administratorfunktionen&func=update_databases&csrf_token=<? echo $_SESSION['csrf_token']; ?>'" <? if(!$update_necessary)echo 'disabled'; ?> value="<? echo $strUpdate; ?>"></td>
				</tr>
			</table> 
		</td>
	</tr>
	<tr>
		<td valign="top" align="center" style="border:1px solid #C3C7C3">			
			<table width="100%" cellpadding="4" cellspacing="2" style="width: 584px" class="table_border_collapse">
				<tr>
					<td colspan="4" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17"><? echo $strConfigParams; ?></span></td>
				</tr><? 
					global $kvwmap_plugins;
					$last_group = '';
					foreach ($this->administration->config_params as $param) {
						if ($param['plugin'] == '' OR in_array($param['plugin'], $kvwmap_plugins)) {
							if ($last_group != $param['group']) {
								$last_group = $param['group']; ?>
								<tr>
									<td colspan="4" class="fett" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><a href="javascript:toggleGroup('<? echo $param['group']; ?>', false);"><img id="<? echo $param['group']; ?>" src="<? echo GRAPHICSPATH.'plus.gif'; ?>"></a>&nbsp;<? echo $param['group']; ?></td>
								</tr>
								<tr class="constants_<? echo $param['group']; ?>" style="display: none">
									<td class="fett">Name</td>
									<td class="fett">Prefix</td>
									<td class="fett"><? echo $strValue; ?></td>
									<td class="fett">Info</td>
								</tr><?
							} ?>
							<tr class="constants_<? echo $param['group']; ?> config_param_saved_<? echo $param['saved']; ?>" style="display: none">
								<td><? echo $param['name']; ?></td>
								<td><?
									$real_prefix_value = '';
									$prefixes = explode('.', $param['prefix']);
									foreach($prefixes as $prefix){
										$real_prefix_value .= $this->administration->config_params[$prefix]['real_value'];
									}
									if (in_array($param['editable'], array(1, 3))) { ?>
										<input title="<? echo $real_prefix_value; ?>" type="text" name="<? echo $param['name']; ?>_prefix" value="<? echo $param['prefix']; ?>" size="50"><?
									}
									else {
										echo '<span title="'.$real_prefix_value.'">'.$param['prefix'].'</span>';
									} ?>
								</td>
								<td><?
									if ($param['editable'] > 1) {
										if ($param['type'] == 'array'){
											echo '<textarea style="width: 300px" rows="'.substr_count($param['value'], "\n").'" name="'.$param['name'].'">' . $param['value'] . '</textarea>';
										}
										else {
											$type = ($param['type'] == 'password' ? 'password' : 'text');
											echo '<input type="' . $type . '" style="width: 300px" name="' . $param['name'] . '" value="' . $param['value'] . '">';
										}
									}
									else {
										echo $param['value'];
									} ?>
								</td>
								<td align="center"><?
									if ($param['description'] != '') { ?>
										<span style="--left: none" data-tooltip="<? echo str_replace(array("\r\n", "\r", "\n"), '&#xa;', htmlentities($param['description'], ENT_QUOTES)); ?>"></span>
							<?	} ?>
								</td>
							</tr><?
							if ($param['saved'] == 0) { ?>
								<script type="text/javascript">toggleGroup('<? echo $param['group']; ?>', true);</script><?
							}
						}
					} ?>
				<tr >
					<td colspan="4" align="center">
						<input
							type="button"
							onclick="
								document.GUI.func.value = 'save_config';
								document.GUI.submit();
							"
							value="<? echo $this->strSave; ?>"
						>
					</td>
				</tr>
			</table> 
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top" align="center" style="border:1px solid #C3C7C3">
			<table width="100%" cellpadding="4" cellspacing="2" border="0" style="border:1px solid #C3C7C3;border-collapse:collapse">
				<tr style="border:1px solid #C3C7C3;">
					<td style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17"><? echo $strFurtherOptions; ?></span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3;">
					<td align="center"><span class="fett"><a href="index.php?go=Administratorfunktionen&func=createRandomPassword&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $strCreateRandomPassword; ?></a></span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3;">
					<td align="center"><span class="fett"><a href="index.php?go=Administratorfunktionen&func=save_all_layer_attributes&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $strSaveAllLayerAttributes; ?></a></span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3;">
					<td align="center">
						<span class="fett create_inserts_from_dataset" onclick="$('.create_inserts_from_dataset').toggle();"><a href="#"><? echo  $strCreateInsertsFromDataset; ?></a></span>
						<style>
							label {
								float: left;
								width: 100px;
							}
							label:after {
								content: ": "
							}
						</style>
						<div class="create_inserts_from_dataset" style="margin-left: 25%; text-align: center; display: none;">
							<label>Schema</label><input style="float: left" type="text" name="schema" value="mvbio"/>
							<div style="clear: both"></div>
							<label>Tabelle</label><input style="float: left" type="text" name="table" value="kampagnen"/><br>
							<div style="clear: both"></div>
							<label>WHERE</label><input style="float: left" type="text" name="where" value="id = 11"/><br>
							<div style="clear: both"></div>
							<input
								style="float: left; margin-left: 50px; margin-top: 5px"
								type="button"
								onclick="$('.create_inserts_from_dataset').toggle()"
								value="Abbrechen"
							>
							<input
								style="float: left; margin-left: 5px; margin-top: 5px"
								style="margin-left: 10px"
								type="button"
								onclick="
									document.GUI.func.value = 'create_inserts_from_dataset';
									document.GUI.submit();
								"
								value="Erzeugen"
							>
						</div>
					</td>
				</tr>
				<tr style="border:1px solid #C3C7C3;">
					<td align="center">
						<span class="fett" onclick="$('#show_constants').toggle();"><a href="#">Zeige Konstanten an</a></span>
						<style>
							.constant-name {
								width: 40%;
								text-align: left;
							}
							.constant-value {
								text-align: left;
							}
							.constant-box {
								display: none;
							}
						</style>
						<div id="show_constants" style="margin-left: 20px; text-align: center; display: none;">
							- <span id="show_all_constants" onclick="$('.constant-box').show(); $('#show_all_constants, #hide_all_constants').toggle()"><a href="#">alle aufklappen</a></span><span id="hide_all_constants" style="display: none;" onclick="$('.constant-box').hide(); $('#show_all_constants, #hide_all_constants')"><a href="#">alle zuklappen</a></span> -<?php
							$constant_array = get_defined_constants(true);
							foreach ($constant_array AS $category => $constants) { ?>
								<a name="<?php echo $category; ?>"></a>
								<h3 onclick="$(this).next().toggle();"><a href="#<?php echo $category; ?>"><?php echo $category; ?></a></h3>
								<div class="constant-box"><?php
								foreach ($constants AS $constant => $value) { ?>
									<label class="constant-name"><?php echo $constant; ?></label>
									<div class="constant-value"><?php echo (($category == 'user' AND in_array($constant, array('POSTGRES_PASSWORD', 'MAILSMTPPASSWORD'))) ? '*****' : $value); ?></div>
									<div style="clear: both"></div><?
								} ?>
								</div><?php
							} ?>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<input type="hidden" name="go" value="Administratorfunktionen">
<input type="hidden" name="func" value="">
