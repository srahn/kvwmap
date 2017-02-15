<?php
	# 2008-01-11 pk
	include(LAYOUTPATH.'languages/menue_body_'.$this->user->rolle->language.'.php');
?><table width="<? echo $this->Menue->width+7 ?>" height="100%" border="0" cellpadding="0" cellspacing="2">
<?php
	if (MENU_WAPPEN=="oben") {
?>
	<tr>
		<td align="center">
			<div style="position: relative; visibility: visible; left: 0px; top: 0px">
	<?
				$this->debug->write("Include Wappen <b>".WAPPENPATH.$this->Stelle->getWappen()."</b> in menue.php",4);
				$wappen_link = $this->Stelle->getWappenLink();
				if ($wappen_link != '') {
					?><a href="<?php echo $wappen_link; ?>" target="_blank"><?php
				}
				?><img src="<? echo WAPPENPATH.$this->Stelle->getWappen(); ?>" alt="Wappen" align="middle" border="0"><?php
				if ($wappen_link != '') {
					?></a><?php
				}
				?></td>
			</div>
			</td>
	</tr>
<?php
			 }
	if ($this->img['referenzkarte']!='' AND MENU_REFMAP == "oben") {
		?>
	<tr>
		<td><input style="border: 1px solid #cccccc;" type="image" id="refmap" onmousedown="document.GUI.go.value='neu Laden';" name="refmap" src="<?php echo $this->img['referenzkarte']; ?>" alt="Referenzkarte" align="right" hspace="0"></td>
	</tr><?php } ?>
	<tr>
		<td valign="top" bgcolor="<? echo BG_MENUETOP; ?>">
			<a href="index.php?go=logout">
				<div>
				<div class="menu">
					<img src="<? echo GRAPHICSPATH; ?>leer.gif" width="17" height="17" border="0"><span class="red">&nbsp;Logout</span>
				</div>
				</div>
			</a>
		</td>
	</tr>
				<?php
					$last = 0;
				for ($i=0;$i<count($this->Menue->Menueoption);$i++) {
					if ($this->Menue->Menueoption[$i]['menueebene']==2) {
						if($last==1){
							$count1 = 1;
							if($this->Menue->Menueoption[$i-$count1]['status']==1){
								echo'
								<div id="menue'.$this->Menue->Menueoption[$i-1]['id'].'sub" style="background-color: '.BG_MENUESUB.';">
								<table cellspacing="2" cellpadding="0" border="0">
									';
							}
							else{
								echo'
								<div id="menue'.$this->Menue->Menueoption[$i-1]['id'].'sub" style="background-color: '.BG_MENUESUB.';display: none">
								<table cellspacing="2" cellpadding="0" border="0">
									';
							}
						}
						echo'
										<tr>
											<td valign="top">
												<img src="'.GRAPHICSPATH.'submenue.png" width="17" height="17" border="0">
											</td>
											<td>';
						?>
												<a href="<?
											if ($this->Menue->Menueoption[$i]['target']=='confirm') {
													?>javascript:Bestaetigung('<?
											}
											echo $this->Menue->Menueoption[$i]['links'];
											if ($this->Menue->Menueoption[$i]['target']=='confirm') {
													?>','Diese Aktion wirklich ausführen?')<?
													$this->Menue->Menueoption[$i]['target']='';
											}
											?>" class="menuered"<?
											if ($this->Menue->Menueoption[$i]['target']!='') {
													?> target="<? echo $this->Menue->Menueoption[$i]['target']; ?>"<?
											}
											?>><?

							echo				$this->Menue->Menueoption[$i]['name'].'</a>
											</td>
										</tr>
						';
						$count1++;
						$last=2;
					}
					else {						// Obermenuepunkte
						if($last==2){
							echo'
										</table>
								</div>
							</div>
								</td>
							</tr>
						';
						}
						elseif($last==1){
							echo '</div>
									</td>
								</tr>'
								;
						}
						if($this->Menue->Menueoption[$i+1]['obermenue'] == $this->Menue->Menueoption[$i]['id']){
							if($this->Menue->Menueoption[$i]['status']==0){
								if(POPUPMENUE == 'true'){
									echo'
									<tr>
										<td valign="top" onmouseover="popup(\'menue'.$this->Menue->Menueoption[$i]['id'].'\')" onmouseout="popdown(\'menue'.$this->Menue->Menueoption[$i]['id'].'\')">
											<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" >
											<div name="obermenu" id="menue'.$this->Menue->Menueoption[$i]['id'].'" class="" style="background-color: '.BG_MENUETOP.';position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">
											<img id="image_'.$this->Menue->Menueoption[$i]['id'].'" src="'.GRAPHICSPATH.'menue_top.gif" width="17" height="17" border="0">
												<span class="black">'.$this->Menue->Menueoption[$i]['name'].'</span>';
								}
								else{
									echo '
									<tr>
										<td valign="top" bgcolor="'.BG_MENUETOP.'">
											<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" >
											<div name="obermenu" id="menue'.$this->Menue->Menueoption[$i]['id'].'">
												<div class="menu">
													<img id="image_'.$this->Menue->Menueoption[$i]['id'].'" src="'.GRAPHICSPATH.'menue_top.gif" width="17" height="17" border="0">
													<span class="black">'.$this->Menue->Menueoption[$i]['name'].'</span>
												</div>';
								}
							}
							else{
								echo'
									<tr>
										<td valign="top" bgcolor="'.BG_MENUETOP.'">
											<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" >
											<div name="obermenu" id="menue'.$this->Menue->Menueoption[$i]['id'].'">
												<div class="menu">
													<img id="image_'.$this->Menue->Menueoption[$i]['id'].'" src="'.GRAPHICSPATH.'menue_top_open.gif" width="17" height="17" border="0">
													<span class="black">'.$this->Menue->Menueoption[$i]['name'].'</span>
												</div>';
							}
						}
						else{
							echo'
								<tr>
									<td valign="top" bgcolor="'.BG_MENUETOP.'">
										<a href="'.$this->Menue->Menueoption[$i]['links'].'" target="'.$this->Menue->Menueoption[$i]['target'].'" >
										<div id="menue'.$this->Menue->Menueoption[$i]['id'].'">
											<div class="menu">
												<img src="'.GRAPHICSPATH.'leer.gif" width="17" height="17" border="0">
												<span class="red">'.$this->Menue->Menueoption[$i]['name'].'</span>
											</div>';
						}
							$last=1;
					}
				}
				if($last == 2){
					echo'
							</table>
						</div>
				';
				}
				echo '</div></a>';
				$i=0;
				?>
		</td>
	</tr>
	<tr>
		<!--td colspan="2" width="215" style="background-color: <? //echo BG_MENUETOP; ?>;">
			<img src="<? //echo GRAPHICSPATH; ?>leer.gif" width="17" height="17" border="0"><strong><a href="help/Dokumentation.htm" target="_blank" title="Hilfe" class="red"><?php echo $strHelp; ?></a></strong>
		</td-->
	</tr><?php
	if ($this->img['referenzkarte']!='' AND MENU_REFMAP !="oben") {
		?><tr>
		<td><input style="border: 1px solid #cccccc;" id="refmap" type="image" onmousedown="document.GUI.go.value='neu Laden';" name="refmap" src="<?php echo $this->img['referenzkarte']; ?>" alt="Referenzkarte" align="right" hspace="0"></td>
	</tr>
	<? } 
	if (MENU_WAPPEN=="unten") {
?>
	<tr>
		<td valign="top" align="center">
			<? $this->debug->write("Include Wappen <b>".WAPPENPATH.$this->Stelle->getWappen()."</b> in menue.php",4); ?>
			<img src="<? echo WAPPENPATH.$this->Stelle->getWappen(); ?>" alt="Wappen" align="middle">
		</td>
	</tr>
		<?
 }
?>
</table>