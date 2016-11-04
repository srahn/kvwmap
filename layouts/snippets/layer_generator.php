<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
	function erzeuge_layer(){
		document.GUI.go.value = 'Layer_Generator_Erzeugen';
		document.GUI.submit();
	}
</script>
<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>" width="100%">
	<tr> 
		<td align="center" colspan="2"><h2><?php echo $this->titel; ?></h2></td>
	</tr>
	<tr align="center"> 
		<td colspan="2">Diese Administrationsfunktion erzeugt SQL für Layerdefinitionenvon von allen Tabellen im angegebenen PostgreSQL-Schema der Datenbank <?php echo $this->pgdatabase->dbName; ?>.</td>
	</tr>
	<tr> 
		<td align="right" width="50%">PostgreSQL Datenbankschema:</td>
		<td align="left" width="50%"><input type="text" name="pg_schema" value="<?php echo $this->formvars['pg_schema']; ?>"></td>
	</tr>
	<tr>
		<td align="center" colspan="2"><input type="button" class="button" value="Erzeugen" onclick="erzeuge_layer();"></td>
	</tr>
	<tr>
		<td align="center" colspan="2"><textarea cols="150" rows="35"><?php echo $this->sql; ?></textarea></td>
	</tr>
</table>

<input type="hidden" name="go" value="Layer_Generator">