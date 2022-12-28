<?php
	global $supportedLanguages;
	include(LAYOUTPATH . 'languages/header_' . $this->user->rolle->language . '.php'); 
?>
<div style="
	width: 100%;
	height: 100%;
	background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);
">

	<div style="padding: 6px; float: left; width: 54%; text-align: left;">
		<span class="fett px20"><?php
			echo $this->Stelle->getName(); ?>
		</span>
	</div>

	<div title="<?php echo $this->strSettings; ?>">
		<i class="fa fa-user header-button" aria-hidden="true" onclick="
		$('#user_options').toggle();
		$('#sperr_div').toggle()
	"></i>
		<div id="user_options" class="user-options">
			<div class="user-options-header">
				<? echo $this->loggedInAs; ?>: <?php echo $this->user->login_name; ?>
			</div>
			<div class="user-options-section-header">
				<i class="fa fa-tasks options-button"></i><? echo $this->inWorkingGroup; ?>:
			</div><?php
			$this->user->Stellen = $this->user->getStellen(0);
			foreach (array_keys($this->user->Stellen['ID']) AS $id) { ?>
				<div
					class="user-option"
					style="margin-left: 0px" <?
					if ($this->user->Stellen['ID'][$id] != $this->user->stelle_id) { ?>
						onclick="window.location.href='index.php?browserwidth=' + $('input[name=browserwidth]').val() + '&browserheight=' + $('input[name=browserheight]').val() + '&Stelle_ID=<? echo $this->user->Stellen['ID'][$id]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'" <?
					} ?>
				><? echo $this->user->Stellen['Bezeichnung'][$id];
				if ($this->user->Stellen['ID'][$id] == $this->user->stelle_id) {
					?> <i class="fa fa-check" aria-hidden="true" style="color: #9b2434; margin: 0px 0px 0px 7px"></i><?
				} ?>
				</div><?
			} ?>
			<div class="options-devider"></div>
			<div
				class="user-option"
				onclick="window.location.href='index.php?go=Stelle_waehlen&show_layer_parameter=1&hide_stellenwahl=1&csrf_token=<? echo $_SESSION['csrf_token']; ?>'"
			><i class="fa fa-ellipsis-v options-button"></i><? echo $this->strSettings; ?></div>
		<div class="options-devider"></div>
		<div
			class="user-option"
			onclick="window.location.href='index.php?go=logout&csrf_token=<? echo $_SESSION['csrf_token']; ?>'"
		><i class="fa fa-sign-out options-button"></i>Logout</div>
		</div>
	</div>

	<div title="Hinweise" style="float: right; display: block;">
		<a href="#" onclick="message('<b>Achtung Wartungsarbeiten!</b><br><br>Die Anwendung wird so umgebaut, dass die Layeroptionen zur Einstellung von Kampagne, Kartiergebiet, Kartierebene und Bogenart im Kopf der Anwendung zu sehen ist! Die Anwendung kann während des Umbaus weiter wie gewohnt genutzt werden.');">
			<i class="fa fa-bell" aria-hidden="true" style="
				font-size: 150%;
				padding: 5px 0px 4px 0;
			"></i>
			<div style="
					margin: -27 0 0 14;
					width: 12;
					height: 12;
					border-radius: 8px;
					background-color: orange;
					font-size: 10px;
					font-weight: bold;
					font-family: arial;
					color: white;
					padding: 0px 2px 3px 2px;
					position: relative;
					text-align: center;
					">1</div>
		</a>
	</div>

	<div title="<? echo $this->strPrintMapArea; ?>">
		<a href="index.php?go=Druckausschnittswahl&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-print header-button" style="font-size: 160%" aria-hidden="true"></i></a>
	</div>

	<div title="<? echo $this->strShowMap; ?>">
		<a href="index.php?go=neu%20Laden&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-map header-button" aria-hidden="true"></i></a>
	</div>

	<div title="<? echo $this->strPlaceSearch; ?>">
		<i class="fa fa-search header-button" aria-hidden="true" style="font-size: 150%;" onclick="$('#search_div, .fa-search, .fa-times').toggle(); $('#geo_name_search_result_div').show()"></i>
		<i class="fa fa-times header-button" aria-hidden="true" style="font-size: 150%; display: none;" onclick="$('#search_div, .fa-search, .fa-times').toggle(); $('#geo_name_search_result_div').hide()"></i>
	</div>

	<div style="padding: 4px; float: right; width: 30%;"><?
		include(SNIPPETS . 'geo_name_search.php'); ?>
	</div>
</div>

<div id="sperr_div" class="sperr-div" onclick="
	$('#user_options').toggle();
	$('#sperr_div').toggle()
">
</div>