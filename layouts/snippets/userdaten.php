<?php
	include(LAYOUTPATH.'languages/userdaten_formular_' . rolle::$language . '.php');
	include(LAYOUTPATH.'languages/userdaten_' . rolle::$language . '.php');
	
	$loeschen = (NUTZER_ARCHIVIEREN ? 'archivieren' : 'löschen');
	
	$has_shared_user = array_reduce(
		$this->userdaten,
		function($has_shared_user, $user_data) {
			return $has_shared_user OR $user_data['share_rollenlayer_allowed'];
		},
		0
	);
 ?>
<script type="text/javascript">
	function toggle_archived_users() {
		var display = 'none';
		if (document.GUI.archived.checked) {
			var display = '';
		}
		var archived_users = document.querySelectorAll('.archived');
		[].forEach.call(archived_users, function (archived_user){
			archived_user.style.display = display;
		});
	}
</script>
 
<a name="oben"></a>
<table width="1300" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td valign="bottom" style="height: 30px;"><h2><?php echo $strTitle; ?></h2></td>
	</tr>
	<tr>
		<td style="float: right; margin-right: 50px"><input type="checkbox" onclick="toggle_archived_users();" id="archived" name="archived"> archivierte Nutzer anzeigen</td>
		<td style="float: right; margin-right: 50px;">
			<div>
				Filter:
				<input type="text" autocomplete="off" id="layer_search" onkeyup="filterRows(this.value);" value="">
			</div>
		</td>
  </tr>
  <? if (in_array($this->formvars['order'], ['name', 'name,vorname'])) { ?>
  <tr height="50px" valign="bottom">
    <td colspan="2">
    <? $umlaute=array("Ä","Ö","Ü");
       for ($i=0;$i<count($this->userdaten);$i++) {
         if(!in_array(strtoupper(mb_substr($this->userdaten[$i]['name'],0,1)),$umlaute) AND strtolower(mb_substr($this->userdaten[$i]['name'],0,1)) != $first) {
					 echo "<a href='#".strtoupper(mb_substr($this->userdaten[$i]['name'],0,1))."'><div class='menu abc'>".strtoupper(mb_substr($this->userdaten[$i]['name'],0,1))."</div></a>";
           $first=strtolower(mb_substr($this->userdaten[$i]['name'],0,1));
         }
       } ?> 
    </td>
  </tr>
  <? } ?>
  <tr>
    <td colspan="2">
			<div class="userdaten-topdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<th>&nbsp;</th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=id&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strID;?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=name,vorname&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strName;?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=stop&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $strIntervall;?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=last_timestamp&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $strLastActivity;?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=organisation&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $strOrganisation;?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=position&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $strPosition;?></a></th>
						<th align="left"><?php echo $this->strTel;?></th>
						<th align="left"><?php echo $this->strEMail;?></th><?
						if ($has_shared_user) { ?>
							<th>&nbsp;</th><?
						} ?>
						<th>&nbsp;</th>
					</tr><?php
					for ($i = 0; $i < count($this->userdaten); $i++) {
						if (in_array($this->formvars['order'], ['name', 'name,vorname'])) {
							$first = strtoupper(mb_substr($this->userdaten[$i]['name'],0,1));
							if (in_array($first,$umlaute)) {
								switch ($first) {
									case 'Ä': {
									$first = 'A';
									} break;
									case 'Ö': {
									$first='O';
									} break;
									case 'Ü': {
									$first='U';
									} break;
								}
							} 
							if ($first != $nextfirst) { ?>
								<tr>
									<th align="left" style="border-top:1px solid #808080; margin:0px;">
										<? echo "<a name='".$first."'>".$first."</a>";
										$nextfirst = $first;
										if (in_array($first,$umlaute)) {
											switch ($first) {
												case 'Ä': {
													$nextfirst = 'A';
												} break;
												case 'Ö': {
													$nextfirst = 'O';
												} break;
												case 'Ü': {
													$nextfirst = 'U';
												} break;
											}
										} ?>
									</th><?
									$colspan = 10;
									if ($has_shared_user) {
										$colspan += 1;
									} ?>
									<td colspan="<? echo $colspan; ?>" align="right" style="border-top:1px solid #808080; margin:0px;">
										<a href="#oben"><img src="<? echo GRAPHICSPATH; ?>pfeil2.gif" width="11" height="11" border="0"></a>
									</td>
								</tr><?
							}
						} ?>
						<tr class="listen-tr <? if ($this->userdaten[$i]['archived']) {echo ' archived" style="display: none';} ?>">
							<td>&nbsp;</td>
							<td><?php echo $this->userdaten[$i]['id']; ?>&nbsp;&nbsp;</td>
							<td>
								<a href="index.php?go=Benutzerdaten_Formular&selected_user_id=<?php echo $this->userdaten[$i]['id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" title="<?php echo $this->strChange; ?>"><?
									echo $this->userdaten[$i]['namenszusatz'].' '; ?><?php echo $this->userdaten[$i]['name']; ?>,&nbsp;<?php echo $this->userdaten[$i]['vorname']; ?>
								</a>
							</td>
							<td><? if($this->userdaten[$i]['stop'] != '0000-00-00') echo $this->userdaten[$i]['start'].'&nbsp;- '.$this->userdaten[$i]['stop']; ?>&nbsp;</td>
							<td><?php echo $this->userdaten[$i]['last_timestamp']; ?>&nbsp;</td>
							<td><?php echo $this->userdaten[$i]['organisation']; ?>&nbsp;</td>
							<td><?php echo $this->userdaten[$i]['position']; ?>&nbsp;</td>
							<td><?php echo $this->userdaten[$i]['phon']; ?>&nbsp;</td>
							<td><?php echo $this->userdaten[$i]['email']; ?>&nbsp;</td><?
							if ($has_shared_user) { ?>
								<td><?
									if ($this->userdaten[$i]['share_rollenlayer_allowed']) { ?>
										<i class="fa fa-share-alt" title="<? echo $strShareRollenlayerAllowedCheckboxText; ?>"></i><?
									}
									else { ?>
										&nbsp; <?
									} ?>
								</td><?
							} ?>
							<td>
								<? if (!$this->userdaten[$i]['archived']) { ?>
								<a href="javascript:Bestaetigung('index.php?go=Benutzer_Löschen&selected_user_id=<?php echo $this->userdaten[$i]['id']; ?>&order=<? echo $this->formvars['order']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>','<? printf($strConfirmDelete, $this->userdaten[$i]['vorname'] . ' ' . $this->userdaten[$i]['name'], $loeschen); ?>?')" title="<?php echo $this->strDelete?>"><i class="fa fa-trash-o"></i></a>
								<? } ?>
							</td>
						</tr><?
					} ?>
				</table>
			</div>
		</td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
      <input type="hidden" name="go" value="Benutzerdaten">
      <input type="hidden" name="order" value="<? echo $this->formvars['order'] ?>">
