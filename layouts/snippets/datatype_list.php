<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center"> 
		<td><h2><?php echo $this->titel; ?></h2></td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<th align="left">Id</th>
					<th align="left">Name</th>
					<th align="left">Datenbankname</th>
					<th align="left">Datenbankschema</th>
				</tr>
			<?php
			for ($i=0; $i<count($this->datatypes);$i++) { ?>
				<tr>
					<td align="right"><?php echo $this->datatypes[$i]['id']; ?></td>
					<td align="left"><?php echo $this->datatypes[$i]['name']; ?></td>
					<td align="right"><?php echo $this->datatypes[$i]['pgdbname']; ?></td>
					<td align="right"><?php echo $this->datatypes[$i]['pgschema']; ?></td>
				</tr><?php
			} ?>
			</table>
		</td>
	</tr>
</table>
