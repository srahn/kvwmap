<script type="text/javascript">
<!--

function setfields(){
	if(document.GUI.mitbildern.checked == true){
		document.GUI.username.disabled = false;
		document.GUI.passwort.disabled = false;
	}
	else{
		document.GUI.username.disabled = true;
		document.GUI.passwort.disabled = true;
	}
}

-->
</script>

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center"> 
		<td><h2><?php echo $this->titel; ?></h2></td>
	</tr>
		<?
		if($this->synchro->result != NULL){
			echo '<tr>
							<td>Es wurden '.$this->synchro->result['newcount'].' neue Datensätze eingelesen.</td>
						</tr>
						<tr>
							<td>Es wurden '.$this->synchro->result['oldcount'].' alte Datensätze eingelesen.</td>
						</tr>
						';
		}
	?>
	<tr>
  	<td>
   		<table>
				<tr>
					<td>
						<div id="map_div" style="border:1px solid #C3C7C3;">
						 <?php include(LAYOUTPATH.'snippets/SVG_style_preview.php');  ?>
						</div>
					</td>
					<td valign="top">
			      <table cellspacing=0 cellpadding=2 border=0 style="border:1px solid #C3C7C3;">
			        <tr align="center">
			          <td>Verfügbare Themen:</td>
			        </tr>
			        <tr align="left">
			          <td>
			          	<div align="center"><input type="submit" class="button" name="neuladen" value="neu Laden"></div>
			          		<br>
			        			<div style="width:230; height:<?php echo $this->map->height-59; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
				          		&nbsp;
				          		<img src="graphics/layer.png" alt="Themensteuerung" title="Themensteuerung" width="20" height="20"><br>
											<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
				          	<div id="legend_div"><? echo $this->legende; ?></div>
				        	</div>
			          </td>
			      	</tr>
			      </table>
			   	</td>
				</tr>
				<tr>	
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%">
							<tr>
								<td width="35%">&nbsp;</td>	
								<td colspan="2" align="left">
									<input type="checkbox" name="mitbildern" onchange="setfields();">Bilder kopieren
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td width="8%">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username:&nbsp;
								</td>
								<td>
									<input disabled="true" type="text" name="username">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Passwort:&nbsp;
								</td>
								<td>
									<input disabled="true" type="password" name="passwort">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>	
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="go_plus" value="importieren"></td> 	
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
<input type="hidden" name="go" value="import_layer">
