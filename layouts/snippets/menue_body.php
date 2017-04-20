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

	if(MENU_WAPPEN=="oben") { ?>
	<div style="position: relative; visibility: visible; left: 0px; top: 0px"><?
		echo $wappen_html; ?>
	</div>		<? }
	
	if($this->img['referenzkarte'] != '' AND MENU_REFMAP == "oben")echo $refmap_html; ?>
	
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

	if($this->img['referenzkarte']!='' AND MENU_REFMAP !="oben")echo $refmap_html;
	
	if(MENU_WAPPEN=="unten"){ ?>
	<div style="position: relative; visibility: visible; left: 0px; top: 0px"><?
		echo $wappen_html; ?>
	</div>
 	<? } ?>