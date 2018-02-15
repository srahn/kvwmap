<?php
	# 2008-01-12 pkvvm
	include(LAYOUTPATH . 'languages/layer_export_' . $this->user->rolle->language . '.php');
	include_once(CLASSPATH . 'FormObject.php');
?>
<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center"> 
		<td colspan="5"><h2><?php echo $this->titel; ?></h2></td>
	</tr>
	<tr>
		<td colspan="5">
			<div style="margin-bottom: 10px;padding: 5px; border: 1px solid #C3C7C3;">
				<?php echo $strLayer;?><br><?
				$layerSelectField = new FormObject(
					"layer[]",
					"select",
					$this->layerdaten['ID'],
					$this->formvars['layer'],
					$this->layerdaten['Bezeichnung'],
					20,
					0,
					true,
					250,
					count($this->layerdaten['ID']) == 0,
					"margin-top: 5px;"
				);
				echo $layerSelectField->html; ?>
			</div>
			<input type="checkbox" name="with_privileges" value="true"<? echo ($this->formvars['with_privileges'] != '' ? ' checked' : ''); ?>>
				Mit Stellenzugehörigkeit
			</input>
		</td>
	</tr>
	<tr>
		<td><input type="submit" value="Exportieren" name="go_plus"/></td>
	</tr>
	<? if($this->layer_dumpfile != ''){ ?>
	<tr>
		<td>Layer wurden exportiert. <a href="<? echo TEMPPATH_REL.$this->layer_dumpfile; ?>"><span class="fett">Herunterladen</span></a></td>
	</tr>	
	<? } ?>
</table>

<input type="hidden" name="go" value="Layer_Export">