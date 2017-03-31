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
			style="border: 1px solid #cccccc;"
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
<table width="<? echo $this->Menue->width+7 ?>" height="100%" border="0" cellpadding="0" cellspacing="2"><?php
	if (MENU_WAPPEN=="oben") { ?>
		<tr>
			<td align="center">
				<div style="position: relative; visibility: visible; left: 0px; top: 0px"><?
					$this->debug->write("Include Wappen <b>".WAPPENPATH.$this->Stelle->getWappen()."</b> in menue.php",4);
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
		<td><?php
			foreach(Menue::find_all_obermenues($this) AS $obermenue) {
				echo $obermenue->html();
			}
		 ?>
		</td>
	</tr><?php
	if ($this->img['referenzkarte']!='' AND MENU_REFMAP !="oben") { ?>
		<tr>
			<td><input style="border: 1px solid #cccccc;" id="refmap" type="image" onmousedown="document.GUI.go.value='neu Laden';" name="refmap" src="<?php echo $this->img['referenzkarte']; ?>" alt="Referenzkarte" align="right" hspace="0"></td>
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