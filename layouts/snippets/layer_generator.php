<?php include_once(CLASSPATH . 'FormObject.php'); ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>" width="100%">
	<tr> 
		<td align="center" colspan="2"><h2><?php echo $this->titel; ?></h2></td>
	</tr>
	<tr align="center"> 
		<td colspan="2">Diese Administrationsfunktion erzeugt SQL für Layerdefinitionenvon von ausgewählten Tabellen im angegebenen PostgreSQL-Schema der Datenbank <?php echo $this->pgdatabase->dbName; ?>.</td>
	</tr>
		<tr>
			<td align="right" width="30%">PostgreSQL Datenbankschema:</td>
			<td align="left" width="80%"><?php
				if ($this->formvars['selected_schema'] == '') { ?>
					<input type="text" name="selected_schema" value=""><?php
				}
				else { ?>
					<input type="hidden" name="selected_schema" value="<?php echo $this->formvars['selected_schema']; ?>"><?php
					echo $this->formvars['selected_schema'];
				} ?>
			</td>
		</tr><?php
		if ($this->formvars['selected_schema'] == '') { ?>
			<tr>
				<td align="center" colspan="2">
					<input type="submit" value="Tabellen abfragen">
				</td>
			</tr><?php
		}
		else { ?>
			<tr>
				<td align="right" width="30%">Tabellen:</td>
				<td align="left" width="80%"><?php
					$table_names = array_map(
						function ($table) {
							return $table['name'];
						},
						$this->schema_tables
					);
					$table_select_field = new FormObject(
						'selected_tables[]',
						'select',
						$table_names,
						$this->formvars['selected_tables'],
						$table_names,
						10,
						150,
						true,
						250
					);
					$table_select_field->outputHTML();
					echo $table_select_field->html; ?>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input type="button" value="Ausgabe Erzeugen" onclick="submit();">
				</td>
			</tr><?php
		} ?>
	<tr>
		<td align="center" colspan="2"><textarea cols="150" rows="35"><?php echo $this->sql; ?></textarea></td>
	</tr>
</table>
<input type="hidden" name="go" value="Layer_Generator_Erzeugen">