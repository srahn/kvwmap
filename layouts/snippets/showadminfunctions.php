<script src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></script>

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

<br><h2><?php echo $this->titel; ?></h2><br>
<? global $kvwmap_plugins; ?>

<table cellpadding="2" cellspacing="12">
	<? if(defined('GIT_USER') AND GIT_USER != ''){ ?>
	<tr>
		<td valign="top" align="center" style="border:1px solid #C3C7C3">			
			<table width="100%" cellpadding="4" cellspacing="2" border="0" style="border:1px solid #C3C7C3;border-collapse:collapse">
				<tr style="border:1px solid #C3C7C3">
					<td colspan="3" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17">Aktualisierung des Quellcodes</span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3">
					<td><? include(SNIPPETS.'git_remote_update.php');include(SNIPPETS.'git_status.php'); ?></td>
				</tr>
				<tr >
					<td colspan="2" align="center"><input type="button" onclick="location.href='index.php?go=Administratorfunktionen&func=update_code'" <? if(!$num_commits_behind)echo 'disabled'; ?> value="Aktualisieren"></td>
				</tr>
			</table> 
		</td>
	</tr>
	<? } ?>
	<tr>
		<td valign="top" align="center" style="border:1px solid #C3C7C3">			
			<table width="100%" cellpadding="4" cellspacing="2" border="0" style="border:1px solid #C3C7C3;border-collapse:collapse">
				<tr style="border:1px solid #C3C7C3">
					<td colspan="3" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17">Aktualisierung der Datenbanken</span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3">
					<td><span class="fett">Komponente</span></td>
					<td align="right"><span class="fett">Status</span></td>
				</tr>
				<? foreach($this->administration->schema_migration_files as $component => $component_migrations){
						$mysql_counter = count($this->administration->migrations_to_execute['mysql'][$component]);
						$postgresql_counter = count($this->administration->migrations_to_execute['postgresql'][$component]);
						$seed_counter = count($this->administration->seeds_to_execute['mysql'][$component]);
				?>
					<tr style="border:1px solid #C3C7C3;">
						<td><?	echo $component; ?></td>
						<td align="right">
						<?	if($mysql_counter == 0 AND $postgresql_counter == 0)echo ' Schemata aktuell'; 
								else {
									echo '<span class="fett red blink">';
									$update_necessary = true;
									if($mysql_counter > 0)echo 'MySQL-Schema ';
									if($postgresql_counter > 0)echo 'PostgreSQL-Schema ';
									echo ' nicht aktuell</span>';
								}
								if($seed_counter > 0){
									$update_necessary = true;
									echo ',<br> neue Menüs- bzw. Layer verfügbar';
								}
								?>
						</td>
					</tr>
				<? }
				if($this->administration->seed_files != ''){
					foreach($this->administration->seed_files as $component => $component_seeds){			// die restlichen Plugins, die kein DB-Schema haben
						if($this->administration->schema_migration_files[$component] == NULL AND count($this->administration->seeds_to_execute['mysql'][$component]) > 0){
					?>
						<tr style="border:1px solid #C3C7C3;">
							<td><?	echo $component; ?></td>
							<td align="right">
							<?	$update_necessary = true;
									echo 'neue Menüs- bzw. Layer verfügbar';
									?>
							</td>
						</tr>
					<? }
					}
				}?>
				<tr >
					<td colspan="2" align="center"><input type="button" onclick="location.href='index.php?go=Administratorfunktionen&func=update_databases'" <? if(!$update_necessary)echo 'disabled'; ?> value="Aktualisieren"></td>
				</tr>
			</table> 
		</td>
	</tr>
	<tr>
		<td valign="top" align="center" style="border:1px solid #C3C7C3">			
			<table width="100%" cellpadding="4" cellspacing="2" class="table_border_collapse">
				<tr>
					<td colspan="4" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17">Konfigurationsparameter</span></td>
				</tr>
				<? 
					global $kvwmap_plugins;
					$last_group = '';
					foreach($this->administration->config_params as $param){
						if($param['plugin'] == '' OR in_array($param['plugin'], $kvwmap_plugins)){
							if($last_group != $param['group']){
								$last_group = $param['group'];			?>
							<tr>
								<td colspan="4" class="fett" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><a href="javascript:toggleGroup('<? echo $param['group']; ?>', false);"><img id="<? echo $param['group']; ?>" src="<? echo GRAPHICSPATH.'plus.gif'; ?>"></a>&nbsp;<? echo $param['group']; ?></td>
							</tr>
							<tr class="constants_<? echo $param['group']; ?>" style="display: none">
								<td class="fett">Name</td>
								<td class="fett">Prefix</td>
								<td class="fett">Wert</td>
								<td class="fett">Info</td>
							</tr>
				<?		}		?>
				<tr class="constants_<? echo $param['group']; ?> config_param_saved_<? echo $param['saved']; ?>" style="display: none">
					<td><? echo $param['name']; ?></td>
					<td><? echo $param['prefix']; ?></td>
					<td>
						<? 
							if($param['type'] == 'array'){
								echo '<textarea style="width: 300px" rows="'.substr_count($param['value'], "\n").'" name="'.$param['name'].'">'.$param['value'].'</textarea>';
							}
							else{
								if($param['type'] == 'password')$type = 'password';
								else $type = 'text';
								echo '<input type="'.$type.'" style="width: 300px" name="'.$param['name'].'" value="'.$param['value'].'">';
							}
						?>
					</td>
					<td align="center">
						<? if($param['description'] != ''){ ?>
						<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(['Beschreibung:', '<? echo str_replace(array("\r\n", "\r", "\n"), '', htmlentities($param['description'], ENT_QUOTES)); ?>'], Style[0], document.getElementById('Tip_<? echo $param['name']; ?>'))" onmouseout="htm()">
						<div id="Tip_<? echo $param['name']; ?>" style="right: 10px;visibility:hidden;position:absolute;z-index:1000;"></div>
						<? } ?>
					</td>
				</tr>
<?			if($param['saved'] == 0){ ?>
					<script type="text/javascript">toggleGroup('<? echo $param['group']; ?>', true);</script>
		<?	}
			}} ?>
				<tr >
					<td colspan="4" align="center"><input type="button" onclick="location.href='index.php?go=Administratorfunktionen&func=save_config'" value="Speichern"></td>
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
					<td style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17">weitere Funktionen</span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3;">
					<td align="center"><span class="fett"><a href="index.php?go=Administratorfunktionen&func=showConstants">Anzeige der Konstanten</a></span></td>
				</tr>
				<!--tr>
					<td bordercolor="#000000" bgcolor="<?php echo BG_GLEATTRIBUTE ?>">
						<span class="fett"><font color="#000000"><a href="index.php?go=Administratorfunktionen&func=closelogfiles">Logfiles abschliessen</a></font></span>
					</td>
				</tr-->
				<tr style="border:1px solid #C3C7C3;">
					<td align="center"><span class="fett"><a href="index.php?go=Administratorfunktionen&func=createRandomPassword">Erzeuge zufälliges Passwort</a></span></td>
				</tr>
				<!--tr>
					<td bordercolor="#000000" bgcolor="<?php echo BG_GLEATTRIBUTE ?>">
						<span class="fett">
							<a href="index.php?go=loadDenkmale_laden">
								Laden von Denkmaldaten aus HIDA XML Exportdatei
							</a>
						</span>
					</td>
				</tr-->
				<tr style="border:1px solid #C3C7C3;">
					<td align="center"><span class="fett"><a href="index.php?go=Administratorfunktionen&func=save_all_layer_attributes">Alle Layerattribute speichern</a></span></td>
				</tr>  
			</table>
		</td>
	</tr>
</table>
