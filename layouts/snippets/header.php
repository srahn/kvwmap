<?php
	global $supportedLanguages;
	include(LAYOUTPATH . 'languages/header_' . $this->user->rolle->language . '.php'); 
?>

<style>
	#search_div {
		display: none;
	}
</style>

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
			$('#sperr_div').toggle();
		"></i>
		<div id="user_options" class="user-options">
			<div class="user-options-header">
				<? echo $this->loggedInAs; ?>: <?php echo $this->user->login_name; ?>
			</div>
			<div class="user-options-section-header">
				<i class="fa fa-tasks options-button"></i><? echo $this->inWorkingGroup; ?>:
			</div><?php
			$this->user->Stellen = $this->user->getStellen(0);
			if (count($this->user->Stellen['ID']) > 21) { ?>
				<select onchange="window.location.href='index.php?Stelle_ID=' + this.value" style="margin: 0px 3px 0px 6px"><?
					foreach (array_keys($this->user->Stellen['ID']) AS $id) {
						echo '
							<option value="' . $this->user->Stellen['ID'][$id] . '"' . ($this->user->Stellen['ID'][$id] == $this->user->stelle_id ? ' selected' : '') . '>' .
								$this->user->Stellen['Bezeichnung'][$id] . '
							</option>
						';
					} ?>
				</select><?
			}
			else {
				foreach (array_keys($this->user->Stellen['ID']) AS $id) { ?>
					<div
						class="user-option"
						style="margin-left: 0px" <?
						if ($this->user->Stellen['ID'][$id] != $this->user->stelle_id) { ?>
							onclick="window.location.href='index.php?Stelle_ID=<? echo $this->user->Stellen['ID'][$id]; ?>'" <?
						} ?>
					><? echo $this->user->Stellen['Bezeichnung'][$id];
					if ($this->user->Stellen['ID'][$id] == $this->user->stelle_id) {
						?> <i class="fa fa-check" aria-hidden="true" style="color: #9b2434; margin: 0px 0px 0px 7px"></i><?
					} ?>
					</div><?
				}
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

	<div title="Benachrichtigungen" style="float: right; display: block;">
		<?
		include_once(CLASSPATH . 'Notification.php');
		$result = Notification::find_for_user($this); ?>
		<a href="#" onclick="if ($('#user_notifications').is(':visible') && $('.notification-box').filter(':visible').length > 0) { $('#user_notifications').hide('swing'); } else {
			<? if (@count($result['notifications']) == 0) { echo 'message([{ type: \'notice\', msg: \'Keine neuen Benachrichtigungen vorhanden.\'}]);'; } ?> $('.notification-box').show(); $('#user_notifications').show('swing'); }">
			<i class="fa fa-bell" aria-hidden="true" style="
				font-size: 150%;
				padding: 5px 0px 4px 0;
			"></i><?
			if ($result['success'] AND count($result['notifications']) > 0) { ?>
				<div id="num_notification_div" style="
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
					text-align: center;"
				><?
					echo count($result['notifications']); ?>
				</div><?
			} ?>
		</a>
		<div id="user_notifications" style="display: none; position: absolute;right: 30px; z-index: 9999;padding: 5px;top: 30px;"><?
			if ($result['success']) {
				foreach($result['notifications'] AS $notification) { ?>
					<div id="notification_box_<? echo $notification['id']; ?>" class="notification-box">
						<div style="float: left"><a href="#" class="notification-hide-icon"><i class="fa fa-times" aria-hidden="true" style="font-size: 100%" onclick="if ($('#notification_delete_checkbox_<? echo $notification['id']; ?>').is(':checked')) { delete_user2notification(<? echo $notification['id']; ?>); } else { $(this).parent().parent().parent().hide('swing'); }"></i></a></div>
						<div style="float: left; margin-left: 5px; max-width: 200px"><?
							echo $notification['notification']; ?>
						</div>
						<div style="clear: both"></div>
						<div style="margin-top: 2px; width: 100%; text-align: center;"><input id="notification_delete_checkbox_<? echo $notification['id']; ?>" type="checkbox"/> nicht mehr anzeigen</div>
					</div><?
				}
			} ?>
		</div>
	</div>

	<div title="<? echo $this->strPrintMapArea; ?>">
		<a href="index.php?go=Druckausschnittswahl&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-print header-button" style="font-size: 160%" aria-hidden="true"></i></a>
	</div>

	<div title="<? echo $this->strShowMap; ?>">
		<a href="index.php?go=neu%20Laden&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-map header-button" aria-hidden="true"></i></a>
	</div>

	<div title="<? echo $this->strPlaceSearch; ?>">
		<i id="search_icon" class="fa fa-search header-button" aria-hidden="true" style="font-size: 150%;" onclick="$('#search_div, #search_icon, #close_icon').toggle(); $('#geo_name_search_result_div').show()"></i>
		<i id="close_icon" class="fa fa-times header-button" aria-hidden="true" style="font-size: 150%; display: none;" onclick="$('#search_div, #search_icon, #close_icon').toggle(); $('#geo_name_search_result_div').hide()"></i>
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