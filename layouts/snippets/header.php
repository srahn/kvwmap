<div style="
	width: 100%;
	height: 100%;
	background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);
">

	<div style="padding: 6px; float: left; width: 54%; text-align: left;">
		<span class="fett px20"><?php
			echo $this->Stelle->Bezeichnung; ?>
		</span>
	</div>

	<div title="Einstellungen">
		<i class="fa fa-user header-button" aria-hidden="true" onclick="
		$('#user_options').toggle();
		$('#sperr_div').toggle()
	"></i>
		<div id="user_options" class="user-options">
			<div class="user-options-header">
				Angemeldet als: <?php echo $this->user->login_name; ?>
			</div>
			<div class="user-options-section-header">
				<i class="fa fa-tasks options-button"></i>in Stelle:
			</div><?php
			$this->user->Stellen = $this->user->getStellen(0);
			foreach (array_keys($this->user->Stellen['ID']) AS $id) { ?>
				<div
					class="user-option"
					style="margin-left: 0px" <?
					if ($this->user->Stellen['ID'][$id] != $this->user->stelle_id) { ?>
						onclick="window.location.href='index.php?browserwidth=' + $('input[name=browserwidth]').val() + '&browserheight=' + $('input[name=browserheight]').val() + '&Stelle_ID=<? echo $this->user->Stellen['ID'][$id]; ?>'" <?
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
				onclick="window.location.href='index.php?go=Stelle_waehlen&show_layer_parameter=1&hide_stellenwahl=1'"
			><i class="fa fa-ellipsis-v options-button"></i>Einstellungen</div>
		<div class="options-devider"></div>
		<div
			class="user-option"
			onclick="window.location.href='index.php?go=logout'"
		><i class="fa fa-sign-out options-button"></i>Logout</div>
		</div>
	</div>

	<div title="Kartenausschnitt Drucken">
		<a href="index.php?go=Druckausschnittswahl"><i class="fa fa-print header-button" style="font-size: 160%" aria-hidden="true"></i></a>
	</div>

	<div title="Karte Anzeigen">
		<a href="index.php?go=neu%20Laden"><i class="fa fa-map header-button" aria-hidden="true"></i></a>
	</div>

	<div title="Ortssuche">
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