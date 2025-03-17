<?
	include_once(CLASSPATH . 'Notification.php');
	$result = Notification::find_for_user($this);
?>

<div title="Benachrichtigungen" style="float: right; display: block;">
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

<div><? 
	if (array_key_exists('prev_login_name', $_SESSION)) {
		echo '<a href="index.php?go=als_voriger_Nutzer_anmelden" class="fett" style="white-space: nowrap;">zurück zum vorigen Nutzer wechseln</a>';
	} ?>
</div>

<div title="<?php echo $this->strSettings; ?>">
	<div id="user_options" class="user-options">
		<div class="user-options-header">
			<? echo $this->loggedInAs; ?>: <?php echo $this->user->login_name; ?>&nbsp;
			<span data-tooltip="<? echo $this->user->Vorname . ' ' . $this->user->Name . '&#xa;' . $this->user->organisation . '&#xa' . $this->user->email . '&#xa;Tel.: ' . $this->user->phon . '&#xa;'; ?>"></span>
		</div>
		<div class="user-options-section-header">
			<i class="fa fa-tasks options-button"></i><? echo $this->inWorkingGroup; ?>:
		</div><?php
		$this->user->Stellen = $this->user->getStellen(0);
		if (count($this->user->Stellen['ID']) > 21) { ?>
			<select onchange="window.location.href='index.php?Stelle_ID=' + this.value + '&browserwidth=' + document.GUI.browserwidth.value + '&browserheight=' + document.GUI.browserheight.value" style="margin: 0px 3px 0px 6px"><?
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
						onclick="window.location.href='index.php?Stelle_ID=<? echo $this->user->Stellen['ID'][$id]; ?>&browserwidth=' + document.GUI.browserwidth.value + '&browserheight=' + document.GUI.browserheight.value" <?
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

<div style="padding: 6px; height: 22px"><?
	include(SNIPPETS . 'geo_name_search.php'); ?>
</div>

<svg xmlns="http://www.w3.org/2000/svg" <? echo ((strpos($this->user->rolle->gui, 'gui_light.php') !== false)? 'width="223" height="45"' : 'width="180"'); ?>">
	<g>
		<rect x="0" y="0" rx="3" ry="3" width="<? echo ((strpos($this->user->rolle->gui, 'gui_light.php') !== false)? '216' : '180'); ?>" height="36" class="navbutton_bg"/>
		<g transform="translate(0 0)">
			<title><? echo $this->strPlaceSearch; ?></title>
			<a href="javascript:void(0);" onclick="$('#search_div').toggle(); $('#geo_name_search_result_div').show()">
				<rect x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" width="36" height="36" transform="translate(6 6) scale(0.045 0.045)">
					<path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
				</g>
			</a>
		</g>
		<g transform="translate(36 0)">
			<title><? echo $this->strShowMap; ?></title>
			<a href="index.php?go=neu%20Laden&csrf_token=<? echo $_SESSION['csrf_token']; ?>">
				<rect x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" width="36" height="36" transform="translate(6.5 5) scale(0.04 0.05)">
					<path d="M384 476.1L192 421.2l0-385.3L384 90.8l0 385.3zm32-1.2l0-386.5L543.1 37.5c15.8-6.3 32.9 5.3 32.9 22.3l0 334.8c0 9.8-6 18.6-15.1 22.3L416 474.8zM15.1 95.1L160 37.2l0 386.5L32.9 474.5C17.1 480.8 0 469.2 0 452.2L0 117.4c0-9.8 6-18.6 15.1-22.3z"/>
				</g>
			</a>
		</g>
		<g transform="translate(72 0)">
			<title><? echo $this->strPrintMapArea; ?></title>
			<a href="index.php?go=Druckausschnittswahl&csrf_token=<? echo $_SESSION['csrf_token']; ?>">
				<rect x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" width="36" height="36" transform="translate(5 5) scale(0.05 0.05)">
					<path d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
				</g>
			</a>
		</g>
		<g transform="translate(108 0)">
			<title><? echo $this->strNotifications; ?></title>
			<a href="javascript:void(0);" onclick="if ($('#user_notifications').is(':visible') && $('.notification-box').filter(':visible').length > 0) { $('#user_notifications').hide('swing'); } else {
			<? if (count_or_0($result['notifications']) == 0) { echo 'message([{ type: \'notice\', msg: \'Keine neuen Benachrichtigungen vorhanden.\'}]);'; } ?> $('.notification-box').show(); $('#user_notifications').show('swing'); }">
				<rect x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" width="36" height="36" transform="translate(6.5 5) scale(0.05 0.05)">
					<path d="M224 0c-17.7 0-32 14.3-32 32l0 19.2C119 66 64 130.6 64 208l0 18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416l384 0c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8l0-18.8c0-77.4-55-142-128-156.8L256 32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3l-64 0-64 0c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"/>
				</g>
				<?
					if ($result['success'] AND count($result['notifications']) > 0) { ?>
						<g transform="translate(21 20)">
							<circle cx="4" cy="-4" r="7" style="fill:orange; stroke-width: 2px"/>
							<text style="stroke: none; fill: white; font-size:14px;"><?	echo count($result['notifications']); ?></text>
						</g><?
					} ?>
			</a>
		</g>
		<g transform="translate(144 0)">
			<title><? echo $this->strSettings; ?></title>
			<a href="javascript:void(0);" onclick="$('#user_options').toggle(); $('#sperr_div').toggle();">
				<rect x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" width="36" height="36" transform="translate(8 6) scale(0.045 0.045)">
					<path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/>
				</g>
			</a>
		</g>
		<? if (strpos($this->user->rolle->gui, 'gui_light.php') !== false) { ?>
			<g transform="translate(180 0)">
				<title><? echo $this->strLegend; ?></title>
				<a href="javascript:void(0);" onclick="$('#legenddiv').toggle();">
					<rect x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
					<g class="navbutton" width="36" height="36" transform="translate(7 5) scale(0.05 0.05)">
						<path d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/>
					</g>
				</a>
			</g>
		<? } ?>
	</g>
</svg>