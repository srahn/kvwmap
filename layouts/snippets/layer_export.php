<?php
	# 2008-01-12 pkvvm
	include(LAYOUTPATH . 'languages/layer_export_' . $this->user->rolle->language . '.php');
	include_once(CLASSPATH . 'FormObject.php');
?>
<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center"> 
		<td><h2><?php echo $this->titel; ?></h2></td>
	</tr>
	<tr>
		<td>
			<div style="margin-bottom: 10px;padding: 5px; text-align: center">
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
				<p>
				<input type="checkbox" name="with_privileges" value="true"<? echo ($this->formvars['with_privileges'] != '' ? ' checked' : ''); ?>>
					Mit Stellenzugehörigkeit
				</input>
				<p>
				<i>Derzeit wird davon ausgegangen, dass alle auf ein mal exportierten Layer zu jeweils einer Gruppe gehören. Die Gruppen-ID kann im Dumpfile manuell gesetzt werden. Erfolgt der Export mit Stellenzuordnung, kann die Erstellung der Stelle im Dumpfile nachträglich manuell unterbunden werden und statt dessen die Stellen_ID des Zielsystems eingetragen werden.</i>
				<p>
				<input type="submit" value="Exportieren" name="go_plus"/>
				<p><?
				if($this->layer_dumpfile != ''){ ?>
					Layer wurden exportiert. <a href="<? echo TEMPPATH_REL.$this->layer_dumpfile; ?>"><span class="fett">Herunterladen</span></a><?
				} ?>
			</div>
		</td>
	</tr>
</table>

<input type="hidden" name="go" value="Layer_Export">