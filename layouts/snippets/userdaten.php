<?php
	include(LAYOUTPATH.'languages/userdaten_' . $this->user->rolle->language . '.php');
	include(LAYOUTPATH.'languages/userdaten_formular_' . $this->user->rolle->language . '.php');
	$has_shared_user = array_reduce(
		$this->userdaten,
		function($has_shared_user, $user_data) {
			return $has_shared_user OR $user_data['share_rollenlayer_allowed'];
		},
		0
	);
 ?>
<a name="oben"></a>
<table width="1300" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <? if($this->formvars['order']=="Name") { ?>
  <tr height="50px" valign="bottom">
    <td>
    <? $umlaute=array("Ä","Ö","Ü");
       for ($i=0;$i<count($this->userdaten);$i++) {
         if(!in_array(strtoupper(mb_substr($this->userdaten[$i]['Name'],0,1)),$umlaute) AND strtolower(mb_substr($this->userdaten[$i]['Name'],0,1)) != $first) {
					 echo "<a href='#".strtoupper(mb_substr($this->userdaten[$i]['Name'],0,1))."'><div class='menu abc'>".strtoupper(mb_substr($this->userdaten[$i]['Name'],0,1))."</div></a>";
           $first=strtolower(mb_substr($this->userdaten[$i]['Name'],0,1));
         }
       } ?> 
    </td>
  </tr>
  <? } ?>
  <tr>
    <td>
			<div class="userdaten-topdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<th>&nbsp;</th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=ID"><?php echo $this->strID . '&csrf_token=' . $_SESSION['csrf_token']; ?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=Name"><?php echo $this->strName . '&csrf_token=' . $_SESSION['csrf_token'];?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=stop"><?php echo $strIntervall . '&csrf_token=' . $_SESSION['csrf_token'];?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=last_timestamp"><?php echo $strLastActivity . '&csrf_token=' . $_SESSION['csrf_token'];?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=organisation"><?php echo $strOrganisation . '&csrf_token=' . $_SESSION['csrf_token'];?></a></th>
						<th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=position"><?php echo $strPosition . '&csrf_token=' . $_SESSION['csrf_token'];?></a></th>
						<th align="left"><?php echo $this->strTel;?></th>
						<th align="left"><?php echo $this->strEMail;?></th><?
						if ($has_shared_user) { ?>
							<th>&nbsp;</th><?
						} ?>
						<th>&nbsp;</th>
					</tr><?php
					for ($i = 0; $i < count($this->userdaten); $i++) {
						if ($this->formvars['order']=="Name") {
							$first = strtoupper(mb_substr($this->userdaten[$i]['Name'],0,1));
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
						<tr class="listen-tr">
							<td>&nbsp;</td>
							<td><?php echo $this->userdaten[$i]['ID']; ?>&nbsp;&nbsp;</td>
							<td>
								<a href="index.php?go=Benutzerdaten_Formular&selected_user_id=<?php echo $this->userdaten[$i]['ID'] . '&csrf_token=' . $_SESSION['csrf_token']; ?>" title="<?php echo $this->strChange; ?>">
									<?php echo $this->userdaten[$i]['Namenszusatz'].' '; ?><?php echo $this->userdaten[$i]['Name']; ?>,&nbsp;<?php echo $this->userdaten[$i]['Vorname']; ?>
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
							<td><a href="javascript:Bestaetigung('index.php?go=Benutzer_Löschen&selected_user_id=<?php echo $this->userdaten[$i]['ID']; ?>&order=<? echo $this->formvars['order'] . '&csrf_token=' . $_SESSION['csrf_token']; ?>','Wollen Sie den Benutzer <?php echo $this->userdaten[$i]['Vorname']." ".$this->userdaten[$i]['Name']; ?> wirklich löschen?')" title="<?php echo $this->strDelete?>"><i class="fa fa-trash-o"></i></a></td>
						</tr><?php  
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
