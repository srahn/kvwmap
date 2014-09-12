<br><h2><?php echo $this->titel; ?></h2><br>
<? global $kvwmap_plugins; ?>

<table cellpadding="2" cellspacing="12">
	<tr>
		<td valign="top" align="center" style="border:1px solid #C3C7C3">			
			<table width="400px" cellpadding="4" cellspacing="2" border="0" style="border:1px solid #C3C7C3;border-collapse:collapse">
				<tr style="border:1px solid #C3C7C3">
					<td colspan="3" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17">Aktualisierung der Datenbanken</span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3">
					<td><span class="fett">Komponente</span></td>
					<td><span class="fett">Status</span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3;">
					<td>kvwmap</td>
					<td></td>
				</tr>
				<? for($i = 0; $i < count($kvwmap_plugins); $i++){ ?>
					<tr style="border:1px solid #C3C7C3;">
						<td>Plugin: <? echo $kvwmap_plugins[$i]; ?></td>
						<td></td>
					</tr>
				<? } ?>
				<tr >
					<td colspan="2" align="center"><input type="button" value="Aktualisieren"></td>
				</tr>
			</table> 
		</td>
		
		<td valign="top" align="center" style="border:1px solid #C3C7C3">
			<table cellpadding="4" cellspacing="2" border="0" style="border:1px solid #C3C7C3;border-collapse:collapse">
				<tr style="border:1px solid #C3C7C3;">
					<td style="background-color:<? echo BG_GLEATTRIBUTE; ?>;"><span class="fetter px17">weitere Funktionen</span></td>
				</tr>
				<tr style="border:1px solid #C3C7C3;">
					<td><span class="fett"><a href="index.php?go=Administratorfunktionen&func=showConstants">Anzeige der Konstanten</a></span></td>
				</tr>
				<!--tr>
					<td bordercolor="#000000" bgcolor="<?php echo BG_GLEATTRIBUTE ?>">
						<span class="fett"><font color="#000000"><a href="index.php?go=Administratorfunktionen&func=closelogfiles">Logfiles abschliessen</a></font></span>
					</td>
				</tr-->
				<tr style="border:1px solid #C3C7C3;">
					<td><span class="fett"><a href="index.php?go=Administratorfunktionen&func=createRandomPassword">Erzeuge zufälliges Passwort</a></span></td>
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
					<td><span class="fett"><a href="index.php?go=Administratorfunktionen&func=save_all_layer_attributes">Alle Layerattribute speichern</a></span></td>
				</tr>  
			</table>
		</td>
	</tr>
</table>
