<?php
	# 2008-01-11 pk
	include(LAYOUTPATH.'languages/menue_body_'.$this->user->rolle->language.'.php');
	$wappen_html = '<img src="' . WAPPENPATH . $this->Stelle->getWappen() . '" alt="Wappen" align="middle" border="0">';
	$wappen_link = $this->Stelle->getWappenLink();
	if ($wappen_link != '') {
		$wappen_html = '<a href="' . $wappen_link . '" target="_blank">' . $wappen_html . '</a>';
	}
	$refmap_html = '
		<input
			style="margin: 2px;border: 1px solid #cccccc;"
			type="image"
			id="refmap"
			onmousedown="document.GUI.go.value=\'neu Laden\';"
			name="refmap"
			src="' . $this->img['referenzkarte'] . '"
			alt="Referenzkarte"
			align="right"
			hspace="0"
		>';
?>
<table width="<? echo $sizes[$this->user->rolle->gui]['menue']['width']; ?>" height="100%" border="0" cellpadding="0" cellspacing="0"><?php
	if (MENU_WAPPEN=="oben") { ?>
		<tr>
			<td align="center">
				<div style="position: relative; visibility: visible; left: 0px; top: 0px"><?
					$this->debug->write("<br>Include Wappen <b>".WAPPENPATH.$this->Stelle->getWappen()."</b> in menue.php",4);
					echo $wappen_html; ?>
				</div>
			</td>
		</tr><?php
	}
	if ($this->img['referenzkarte'] != '' AND MENU_REFMAP == "oben") { ?>
		<tr>
			<td>
				<?php echo $refmap_html; ?>
			</td>
		</tr><?php
	} ?>
	<tr>
		<td>
			<div id="logout_menue">
				<div title="" class="menu hauptmenue" onclick="location.href='index.php?go=logout'">
					<span style="vertical-align: top">Logout</span>
				</div>
			</div>
		<?
			$this->menues = Menue::loadMenue($this);
			foreach($this->menues as $menue){				
				if($menue->get('menueebene') == 1) echo $menue->html();
			}
		 ?>
		</td>
	</tr><?php
	if ($this->img['referenzkarte']!='' AND MENU_REFMAP !="oben") { ?>
		<tr>
			<td>
				<?php echo $refmap_html; ?>
			</td>
		</tr><?
	} 
	if (MENU_WAPPEN=="unten") { ?>
		<tr>
			<td align="center">
				<div style="position: relative; visibility: visible; left: 0px; top: 0px"><?
					$this->debug->write("Include Wappen <b>".WAPPENPATH.$this->Stelle->getWappen()."</b> in menue.php",4);
					echo $wappen_html; ?>
				</div>
			</td>
		</tr><?php
 	} ?>
</table>