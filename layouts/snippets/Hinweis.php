<?
	include(LAYOUTPATH . 'languages/invitations_' . $this->user->rolle->language . '.php');
?>
<div id="hinweis_div" style="height: 800px">
	<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
		<tr align="center">
			<td><h2>Hinweis</h2></td>
		</tr>
		<tr>
			<td>
				<? echo $this->Hinweis; ?>
			</td>
		</tr>
	</table>
</div>
