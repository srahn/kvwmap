<?
  # 2007-12-30 pk
	include(LAYOUTPATH.'languages/rollenwahl_'.rolle::$language.'.php');
	include(LAYOUTPATH.'languages/map_'.rolle::$language.'.php');
	include(SNIPPETS . 'sachdatenanzeige_functions.php');
	global $supportedLanguages;
	global $last_x;
	$show_layer_parameter = value_of($this->formvars, 'show_layer_parameter');
?>

<script type="text/javascript">

	function start1(){
		document.GUI.submit();
	}
	
	function openPasswordForm(){
		var form = document.getElementById('password_form');
		if(form.style.display == 'none')form.style.display = 'block';
		else form.style.display = 'none';
	}
	
	function savePassword(){
		document.GUI.go.value = 'Stelle_waehlen_Passwort_aendern';
		document.GUI.submit();
	}

	/* This function can be overwritten
	  * when some action should happen
	 * after parameter changed
	 */
	function onLayerParameterChanged(parameter) {
		/* nothing to do here */
	}

</script>

<style>
/* Tabs mit radio-Buttons */
.tabbed figure { 
	display: block; 
	margin: 8px 0 0 0; 
	clear: both;
	width: 100%;
}

.tabbed > input,
.tabbed figure > div { display: none; }


#tab1:checked ~ figure .tab1,
#tab2:checked ~ figure .tab2,
#tab3:checked ~ figure .tab3,
#tab4:checked ~ figure .tab4,
#tab5:checked ~ figure .tab5 { display: block; }


nav label {
	float: left;
	padding: 9px 0 9px 0;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	color: #888;
	width: 100%;
	cursor: pointer;
}

nav label:hover { background: rgb(238, 238, 239); color: #666; }
nav label:active{
	background: #c7d9e6;
}

#tab1:checked ~ nav label[for="tab1"],
#tab2:checked ~ nav label[for="tab2"],
#tab3:checked ~ nav label[for="tab3"],
#tab4:checked ~ nav label[for="tab4"],
#tab5:checked ~ nav label[for="tab5"] {
	background: #c7d9e6;
	color: #111;
	border-bottom: none;
	font-family: SourceSansPro2;
}

</style>

<br>
<h2><? echo $this->titel.$strTitleRoleSelection; ?></h2>
<br>
<? 
if ($this->Fehlermeldung!='') {
	include(LAYOUTPATH."snippets/Fehlermeldung.php");
} ?>

<div id="rollenwahl_main_div">
	<div id="rollenwahl_optionen_div" class="tabbed">
		<? if ($this->formvars['show_layer_parameter']) { ?>
		<input id="tab1"<? echo ($show_layer_parameter ? ' checked="checked"' : '') ?> type="radio" name="tabs" />
		<? } ?>
		<input id="tab2"<? echo ($show_layer_parameter ? '' : ' checked="checked"') ?> type="radio" name="tabs" />
		<input id="tab3" type="radio" name="tabs" />
		<input id="tab4" type="radio" name="tabs" />
		<input id="tab5" type="radio" name="tabs" />

		<nav>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><?
					if ($show_layer_parameter) { ?>
						<th width="20%" align="center"><label for="tab1">&nbsp;<? echo $this->strLayerParameters; ?>&nbsp;</label></th><?
					} ?>
					<th width="20%" align="center"><label for="tab2"><? echo $strGeneralOptions; ?></label></th>
					<th width="20%" align="center"><label for="tab3"><? echo $strButtons; ?></label></th>
					<th width="20%" align="center"><label for="tab4">&nbsp;<? echo $strMapOptions; ?>&nbsp;</label></th>
					<th width="20%" align="center"><label for="tab5">&nbsp;<? echo $strDataPresentation; ?>&nbsp;</label></th>
				</tr>
			</table>
		</nav>

		<figure>
			<? if ($show_layer_parameter) { ?>
			<div id="layer_parameters_div" class="tab1">
					<? echo $this->get_layer_params_form(); ?>
			</div>
			<? } ?>
			<div class="rollenwahl-gruppe tab2">
				<table class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="rollenwahl-gruppen-options">
							<table border="0" cellpadding="0" cellspacing="0"><?
								if (value_of($this->formvars, 'hide_stellenwahl')) { ?>
									<tr>
										<td>
											<input type="hidden" name="Stelle_ID" value="<? echo $this->user->stelle_id; ?>">
										</td>
									</tr><?
								}
								else { ?>
									<tr>
										<td valign="top" class="rollenwahl-option-header">
											<? echo $strTask; ?>
										</td>
										<td class="rollenwahl-option-data">
											<div style="display: flex;">
												<div style="float: left;">
													<? echo $this->StellenForm->html; ?>
												</div>
												<div style="float: left; margin-left: 5px;">
													<span data-tooltip="<? echo $strHintTask; ?>"></span>
												</div>
												<div style="width: 80px; text-align: center;">
													<i id="sign_in_stelle" title="<? echo $this->strEnter; ?>" class="fa fa-sign-out fa-2x" onclick="document.GUI.submit();" style="cursor: pointer;display: none;"></i>
												</div>
											</div>
										</td>
									</tr><?
								}
								if (array_key_exists('stelle_angemeldet', $_SESSION) AND $_SESSION['stelle_angemeldet'] === true) {
									if($this->user->Name != 'gast'){ ?>
									<tr>
										<td valign="top" class="rollenwahl-option-header">
											<? echo $strPassword; ?>:
										</td>
										<td class="rollenwahl-option-data">
											<i class="fa fa-key options-button" aria-hidden="true"></i><a href="javascript:openPasswordForm();"><? echo $strChangePassword; ?></a>
											<div id="password_form" style="border: 1px solid #cbcbcb;width: 290px;<? if ($this->PasswordError == '') echo 'display: none'; ?>">
												<table cellspacing="3" style="width: 100%">
													<tr>
														<td><span class="px16"><? echo $strCurrentPassword; ?>: </span></td>
														<td>
															<input style="width: 130px" type="password" value="<? echo $this->formvars['passwort']; ?>" id="passwort" name="passwort" /><i style="margin-left: -18px" class="fa fa-eye-slash" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#passwort').attr('type') == 'text') { $('#passwort').attr('type', 'password') } else { $('#passwort').attr('type', 'text'); }"></i>
														</td>
													</tr><?
													if($this->PasswordError){ ?>
														<tr>
															<td colspan="2" style="color: red;">
																<? echo $this->PasswordError; ?>
															</td>
														</tr><?
													} ?>
													<tr>
														<td><span class="px16"><? echo $strNewPassword; ?>: </span></td>
														<td>
															<input style="width: 130px" maxlength="<? echo PASSWORD_MAXLENGTH; ?>" type="password" value="<? echo $this->formvars['new_password']; ?>" id="new_password" name="new_password"/><i style="margin-left: -18px" class="fa fa-eye-slash" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#new_password').attr('type') == 'text') { $('#new_password').attr('type', 'password') } else { $('#new_password').attr('type', 'text'); }"></i><?php
															if (defined ('PASSWORD_INFO') AND PASSWORD_INFO != '') { ?>
																<div style="float: right; margin-left: 5px;">
																	<span data-tooltip="<? echo PASSWORD_INFO; ?>"></span>
																</div><?php
															} ?>
														</td>
													</tr>
													<tr>
														<td><span class="px16"><? echo $strRepeatPassword; ?>: </span></td>
														<td><input style="width: 130px" maxlength="<? echo PASSWORD_MAXLENGTH; ?>" type="password" value="<? echo $this->formvars['new_password_2']; ?>" id="new_password_2" name="new_password_2"/><i style="margin-left: -18px" class="fa fa-eye-slash" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#new_password_2').attr('type') == 'text') { $('#new_password_2').attr('type', 'password') } else { $('#new_password_2').attr('type', 'text'); }"></i></td>
													</tr>
													<tr>
														<td colspan="2" align="center">
															<input type="button" value="<? echo $this->strSave; ?>" onclick="savePassword();">
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<? } ?>
									<tr>
										<td class="rollenwahl-option-header">
											<? echo $strLanguage; ?>:
										</td>
										<td class="rollenwahl-option-data">
											<select name="language">
												<? if(in_array('german', $supportedLanguages)){ ?><option value="german"<? if(rolle::$language == 'german') { echo ' selected'; }	?>><? echo $strGerman; ?></option><? } ?>
												<? if(in_array('low-german', $supportedLanguages)){ ?><option value="low-german"<? if(rolle::$language == 'low-german') { echo ' selected'; }	?>><? echo $strPlatt; ?></option><? } ?>
												<? if(in_array('english', $supportedLanguages)){ ?><option value="english"<? if(rolle::$language == 'english') { echo ' selected'; }	?>><? echo $strEnglish; ?></option><? } ?>
												<? if(in_array('polish', $supportedLanguages)){ ?><option value="polish"<? if(rolle::$language == 'polish') { echo ' selected'; }	?>><? echo $strPolish; ?></option><? } ?>
												<? if(in_array('vietnamese', $supportedLanguages)){ ?><option value="vietnamese"<? if(rolle::$language == 'vietnamese') { echo ' selected'; }	?>><? echo $strVietnamese; ?></option><? } ?>
											</select>&nbsp;
											<span data-tooltip="<? echo $strHintLanguage; ?>"></span>
										</td>
									</tr>
									<tr <? if(count($this->guifiles) < 2){echo 'style="display: none"';} ?>>
										<td class="rollenwahl-option-header">
											<? echo $strGUI; ?>:
										</td>
										<td class="rollenwahl-option-data">
											<select name="gui"><?
												for ($i = 0; $i < count($this->guifiles); $i++) { ?>
													<option	value="<? echo $this->guifiles[$i]; ?>"<?	echo ($this->user->rolle->gui == $this->guifiles[$i] ? ' selected' : ''); ?>><? echo $this->guifiles[$i]; ?></option>
										<?	}		?>
											</select>
											<span data-tooltip="<? echo $strHintGUI; ?>"></span>
										</td>
									</tr>
									<tr>
										<td class="rollenwahl-option-header">
											<? echo $strVisuallyImpaired; ?>:
										</td>
										<td class="rollenwahl-option-data">
											<input name="visually_impaired" type="checkbox" value="1" <? if($this->user->rolle->visually_impaired == '1') { echo 'checked="true"';} ?> >
											<span data-tooltip="<? echo $strHintVisuallyImpaired; ?>"></span>
										</td>
									</tr>
									<tr>
										<td class="rollenwahl-option-header">
											<label for="font_size_factor">Textgröße</label>
										</td>
										<td class="rollenwahl-option-data">
											<input
											  type="range"
											  name="font_size_factor"
											  id="font_size_factor"
											  min="0.75"
											  max="1.25"
											  step="0.125"
											  value="<?php echo $this->user->rolle->font_size_factor; ?>"
												style="width: 300px"
												onchange="$('#font_size_factor_text').css('font-size', ($('#font_size_factor').val() * 15) + 'px')"
											/>
											<output class="font_size_factor" for="font_size_factor"></output>
											<?php
											/* ToDo pk:
											  - language for font_size_factor eintragen
											  - styles mit font_size_factor ausstatten mit laden des Styles zum zeitpunkt des Ladens der Seite in php
											  - prüfen wo überall mail_css.php geladen wird
											  - Anpassung laden von custom css
											*/
											?>
											<span data-tooltip="Verändert die Textgröße"></span><br>
											<div id="font_size_factor_text" style="font-size: <?php echo $this->user->rolle->font_size_factor * 15;?>px">
												<div style="float: left; width: 100px; text-align: left;">klein</div>
												<div style="float: left; width: 100px; text-align: center">mittel</div>
												<div style="float: left; width: 100px; text-align: right">groß</div>
											</div>
										</td>
									</tr><?
								} ?>
							</table>
						</td>
					</tr>
				</table>
			</div><?
	###
	if (array_key_exists('stelle_angemeldet', $_SESSION) AND $_SESSION['stelle_angemeldet'] === true) { ?>
			<div class="rollenwahl-gruppe tab3">
				<table class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="rollenwahl-gruppen-options">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td valign="top" class="rollenwahl-option-header">
										<? echo $strMapTools; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<div class="button_selection">
											<div title="<? echo $strPreviousView; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? echo previous('', $strPreviousView, ''); ?></svg></div><input type="checkbox" name="back" value="1" <? if($this->user->rolle->back){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strNextView; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo forward('', $strNextView, ''); ?></svg></div><input type="checkbox" name="forward" value="1" <? if($this->user->rolle->forward){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strZoomIn; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo zoomin($strZoomIn); ?></svg></div><input type="checkbox" name="zoomin" value="1" <? if($this->user->rolle->zoomin){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strZoomOut; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo zoomout($strZoomOut); ?></svg></div><input type="checkbox" name="zoomout" value="1" <? if($this->user->rolle->zoomout){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strZoomToFullExtent; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo zoomall($strZoomToFullExtent); ?></svg></div><input type="checkbox" name="zoomall" value="1" <? if($this->user->rolle->zoomall){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strPan; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo recentre($strPan); ?></svg></div><input type="checkbox" name="recentre" value="1" <? if($this->user->rolle->recentre){echo 'checked="true"';} ?>>

											<div title="<? echo $strCoordinatesZoom; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo coords1($strCoordinatesZoom); ?></svg></div><input type="checkbox" name="jumpto" value="1" <? if($this->user->rolle->jumpto){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strCoordinatesQuery; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo coords2($strCoordinatesQuery); ?></svg></div><input type="checkbox" name="coord_query" value="1" <? if($this->user->rolle->coord_query){echo 'checked="true"';} ?>>&nbsp;
											<? if (defined('DGM_LAYER_ID') AND DGM_LAYER_ID != '') { ?>
											<div title="<? echo $strElevationProfile; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo elevation_profile($strElevationProfile); ?></svg></div><input type="checkbox" name="elevation_profile" value="1" <? if($this->user->rolle->elevation_profile){echo 'checked="true"';} ?>>&nbsp;
											<? } ?>
											<div title="<? echo $strInfo; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo ppquery($strInfo); ?></svg></div><input type="checkbox" name="query" value="1" <? if($this->user->rolle->query){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strTouchInfo; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo touchquery($strTouchInfo); ?></svg></div><input type="checkbox" name="touchquery" value="1" <? if($this->user->rolle->touchquery){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strInfoWithRadius; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo pquery($strInfoWithRadius); ?></svg></div><input type="checkbox" name="queryradius" value="1" <? if($this->user->rolle->queryradius){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strInfoInPolygon; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo polygonquery($strInfoInPolygon); ?></svg></div><input type="checkbox" name="polyquery" value="1" <? if($this->user->rolle->polyquery){echo 'checked="true"';} ?>>&nbsp;

											<div title="<? echo $strRuler; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo dist($strRuler); ?></svg></div><input type="checkbox" name="measure" value="1" <? if($this->user->rolle->measure){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strPunktfang; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo punktfang($strPunktfang); ?></svg></div><input type="checkbox" name="punktfang" value="1" <? if($this->user->rolle->punktfang){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strFreePolygon; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo freepolygon($strFreePolygon); ?></svg></div><input type="checkbox" name="freepolygon" value="1" <? if($this->user->rolle->freepolygon){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strFreeText; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo freetext($strFreeText); ?></svg></div><input type="checkbox" name="freetext" value="1" <? if($this->user->rolle->freetext){echo 'checked="true"';} ?> onchange="$('#freeTextOptionsDiv').toggle();">&nbsp;
											<div title="<? echo $strFreeArrow; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo freearrow($strFreeArrow); ?></svg></div><input type="checkbox" name="freearrow" value="1" <? if($this->user->rolle->freearrow){echo 'checked="true"';} ?>>&nbsp;
											<div title="<? echo $strGPS; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo gps_follow($strGPS, 'on'); ?></svg></div><input type="checkbox" name="gps" value="1" <? if($this->user->rolle->gps){echo 'checked="true"';} ?>>&nbsp;
											<? if (defined('ROUTING_URL') AND ROUTING_URL != '') { ?>
											<div title="<? echo $strRouting; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo routing($strRouting); ?></svg></div><input type="checkbox" name="routing" value="1" <? if($this->user->rolle->routing){echo 'checked="true"';} ?>>&nbsp;
											<? } ?>
											<div style="margin: 10px;">
												<span data-tooltip="<? echo $strHintButtons; ?>"></span>
											</div>
											<div id="freeTextOptionsDiv" style="display: <? echo ($this->user->rolle->freetext ? 'block' : 'none'); ?>">
												<? echo $strRedlineTextOptions; ?>:<br>
												<div style="width: 500px">
													<div style="width: 200px; float: left;">
														<? echo $strRedlineTextColor; ?>: <input type="color" name="redline_text_color" value="<?php echo $this->user->rolle->redline_text_color; ?>" style="width: 76px"/>
													</div>
													<div style="width: 200px; float: left;"><?
														echo $strRedlineFontFamily; ?>: <? echo FormObject::createSelectField(
															'redline_font_family',
															array('Arial', 'Courier', 'Helvetica', 'Verdana'),
															$this->user->rolle->redline_font_family,
															1, 	# size
															'', # no style
															'', # no onchange
															'', # no id
															'', # not multiple
															'', # no class
															''  # no first_option
														); ?>
													</div>
												</div>
												<div style="width: 500px; clear: both;">
													<div style="width: 200px; float: left;"><?
														echo $strRedlineFontSize; ?>: <input type="number" name="redline_font_size" min="10" max="40" step="1" value="<?php echo $this->user->rolle->redline_font_size; ?>" style="width: 43px"/>
													</div>
													<div style="width: 200px; float: left"><?
														echo $strRedlineFontWeight; ?>: <? echo FormObject::createSelectField(
															'redline_font_weight',
															array('normal', 'bold', 'bolder', 'lighter', 'initial', 'inherit', '100', '200', '300', '400', '500', '600', '700', '800', '900'),
															$this->user->rolle->redline_font_weight,
															1, 	# size
															'', # no style
															'', # no onchange
															'', # no id
															'', # not multiple
															'', # no class
															''  # no first_option
														); ?>
													</div>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strMapFunctions; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="showmapfunctions" type="checkbox" value="1" <? if($this->user->rolle->showmapfunctions == '1') { echo 'checked="true"'; } ?> >&nbsp;
										<span data-tooltip="<? echo $strHintShowMapFunctions; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strShowLayerOptions; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="showlayeroptions" type="checkbox" value="1" <? if($this->user->rolle->showlayeroptions == '1') { echo 'checked="true"'; } ?> >&nbsp;
										<span data-tooltip="<? echo $strHintShowLayerOptions; ?>"></span>
									</td>
								</tr>
								<tr <? if(!ROLLENFILTER){echo 'style="display: none"';} ?>>
									<td class="rollenwahl-option-header">
										<? echo $strShowRollenFilter; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="showrollenfilter" type="checkbox" value="1" <? if($this->user->rolle->showrollenfilter == '1') { echo 'checked="true"'; } ?> >&nbsp;
										<span data-tooltip="<? echo $strHintShowRollenFilter; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strMenuAutoClose; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="menu_auto_close" type="checkbox" value="1" <? if($this->user->rolle->menu_auto_close == '1'){echo 'checked="true"';} ?> >&nbsp;
										<span data-tooltip="<? echo $strHintMenuAutoClose; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strMenueButtons; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="menue_buttons" type="checkbox" value="1" <? if($this->user->rolle->menue_buttons == '1'){echo 'checked="true"';} ?> >&nbsp;
										<span data-tooltip="<? echo $strHintMenueButtons; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strLayerSelectionMode; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="layer_selection_mode" type="checkbox" value="1" <? if ($this->user->rolle->layer_selection_mode == '1'){echo 'checked="true"';} ?> >&nbsp;
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>

			<div class="rollenwahl-gruppe tab4">
				<table class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="rollenwahl-gruppen-options">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strZoomFactor; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="nZoomFactor" type="text" value="<? echo $this->user->rolle->nZoomFactor; ?>" size="2" maxlength="3">&nbsp;
										<span data-tooltip="<? echo $strHintZoomFactor; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strMapSize; ?>:
									</td>
									<td class="rollenwahl-option-data">  
										<select name="mapsize">
											<? $selected = false; ?>
											<option value="auto" <? if($this->user->rolle->auto_map_resize){ echo "selected"; $selected = true;}?>><? echo $strAutoResize; ?></option>              	
											<option value="300x300" <?php if ($this->user->rolle->mapsize=="300x300"){ echo "selected"; $selected = true;} ?>>300x300</option>
											<option value="400x400" <?php if ($this->user->rolle->mapsize=="400x400"){ echo "selected"; $selected = true;} ?>>400x400</option>
											<option value="500x500" <?php if ($this->user->rolle->mapsize=="500x500"){ echo "selected"; $selected = true;} ?>>500x500</option>
											<option value="600x600" <?php if ($this->user->rolle->mapsize=="600x600"){ echo "selected"; $selected = true;} ?>>600x600</option>
											<option value="800x600" <?php if ($this->user->rolle->mapsize=="800x600"){ echo "selected"; $selected = true;} ?>>800x600</option>
											<option value="850x700" <?php if ($this->user->rolle->mapsize=="850x700"){ echo "selected"; $selected = true;} ?>>850x700</option>
											<option value="1000x800" <?php if ($this->user->rolle->mapsize=="1000x800"){ echo "selected"; $selected = true;} ?>>1000x800</option>
											<option value="1150x700" <?php if ($this->user->rolle->mapsize=="1150x700"){ echo "selected"; $selected = true;} ?>>1150x700</option>
											<option value="1200x850" <?php if ($this->user->rolle->mapsize=="1200x850"){ echo "selected"; $selected = true;} ?>>1200x850</option>
											<option value="1400x850" <?php if ($this->user->rolle->mapsize=="1400x850"){ echo "selected"; $selected = true;} ?>>1400x850</option>
											<option value="1600x850" <?php if ($this->user->rolle->mapsize=="1600x850"){ echo "selected"; $selected = true;} ?>>1600x850</option>
											<? if($selected == false){ ?>
											<option value="<? echo $this->user->rolle->mapsize; ?>" selected><? echo $this->user->rolle->mapsize; ?></option>              	
											<? } ?>
										</select>&nbsp;
										<span data-tooltip="<? echo $strHintMapSize; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strMapExtent; ?>:
									</td>
									<td class="rollenwahl-option-data"><?
										$curExtentText=round($this->user->rolle->oGeorefExt->minx, 3).' '.round($this->user->rolle->oGeorefExt->miny, 3).', '.round($this->user->rolle->oGeorefExt->maxx, 3).' '.round($this->user->rolle->oGeorefExt->maxy, 3);
									 ?><input name="newExtent" id="newExtent" type="text" size="<? echo strlen($curExtentText); ?>" value="<? echo $curExtentText; ?>">&nbsp;
										<span data-tooltip="<? echo $strHintMapExtent; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strMapProjection; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<select name="epsg_code" onchange="ahah('index.php','go=spatial_processing&newSRID='+this.form.epsg_code.value+'&operation=transform&resulttype=wkt',new Array(newExtent), '');">
											<?
											foreach($this->epsg_codes as $epsg_code){
												echo '<option';
												if($this->user->rolle->epsg_code == $epsg_code['srid'])echo ' selected';
												echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
											}
											?>
										</select>&nbsp;
										<span style="--left: -500px" data-tooltip="<? echo $strHintMapProjection; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strSecondMapProjection; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<select name="epsg_code2">
											<option value="">--<? echo $this->strChoose; ?>--</option>
											<?
											foreach($this->epsg_codes as $epsg_code){
												echo '<option';
												if($this->user->rolle->epsg_code2 == $epsg_code['srid'])echo ' selected';
												echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
											}
											?>
										</select>&nbsp;
										<span style="--left: -500px" data-tooltip="<? echo $strHintSecondMapProjection; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strCoordType; ?>:
									</td>
									<td  class="rollenwahl-option-data">
										<select name="coordtype">
											<option value="dec" <? if ($this->user->rolle->coordtype=="dec") { echo "selected"; } ?>><? echo $strdecimal; ?></option>
											<option value="dms" <? if ($this->user->rolle->coordtype=="dms") { echo "selected"; } ?>><? echo $strgrad1; ?></option>
											<option value="dmin" <? if ($this->user->rolle->coordtype=="dmin") { echo "selected"; } ?>><? echo $strgrad2; ?></option>				
										</select>&nbsp;
										<span data-tooltip="<? echo $strHintCoordType; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strPrintScale; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<select name="print_scale">
											<option value="auto" <? if($this->user->rolle->print_scale == "auto"){ echo "selected"; } ?>><? echo $strPrintScaleAuto; ?></option>
											<option value="" <? if($this->user->rolle->print_scale != "auto"){ echo "selected"; } ?>><? echo $strPrintScaleLast; ?></option>
										</select>&nbsp;
										<span data-tooltip="<? echo $strHintPrintScale; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strRunningCoords; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="runningcoords" type="checkbox" value="1" <? if($this->user->rolle->runningcoords == '1'){echo 'checked="true"';} ?> >&nbsp;
										<span data-tooltip="<? echo $strHintRunningCoords; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strInstantReload; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="instant_reload" type="checkbox" value="1" <? if($this->user->rolle->instant_reload == '1'){echo 'checked="true"';} ?> >
										<span style="--left: -200px" data-tooltip="<? echo $strHintInstantReload; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strSearchColor; ?>:
									</td>
									<td class="rollenwahl-option-data">
									<?
									
									for($i = 0; $i < count($this->result_colors); $i++){
										if($this->user->rolle->result_color == $this->result_colors[$i]['id']){
											$bgcolor = str_pad(dechex($this->result_colors[$i]['red']), 2, '0').str_pad(dechex($this->result_colors[$i]['green']), 2, '0').str_pad(dechex($this->result_colors[$i]['blue']), 2, '0');
										}
									}
									
									?>
										<select name="result_color" style="background-color:#<? echo $bgcolor; ?>" onchange="this.setAttribute('style', this.options[this.selectedIndex].getAttribute('style'));">
											<?
											for($i = 0; $i < count($this->result_colors); $i++){
												echo '<option ';
												if($this->user->rolle->result_color == $this->result_colors[$i]['id']){
													echo ' selected';
												}
												echo ' style="background-color: rgb('.$this->result_colors[$i]['red'].', '.$this->result_colors[$i]['green'].', '.$this->result_colors[$i]['blue'].')"';
												echo ' value="'.$this->result_colors[$i]['id'].'">';
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
												echo "</option>\n";
											}
											?>
										</select>&nbsp;
										<span data-tooltip="<? echo $strHintColor; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strSearchHatching; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="result_hatching" type="checkbox" value="1" <? if($this->user->rolle->result_hatching == '1'){echo 'checked="true"';} ?> >
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strSearchTransparency; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="result_transparency" onchange="transparency_slider.value=parseInt(result_transparency.value);" style="width: 30px" value="<? echo $this->user->rolle->result_transparency; ?>">
										<input type="range" id="transparency_slider" name="transparency_slider" style="width: 120px; height: 6px" value="<? echo $this->user->rolle->result_transparency; ?>" onchange="result_transparency.value=parseInt(transparency_slider.value);result_transparency.onchange()" oninput="result_transparency.value=parseInt(transparency_slider.value);result_transparency.onchange()">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>

			<div class="rollenwahl-gruppe tab5">
				<table class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="rollenwahl-gruppen-options">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strMapQuery; ?>:
									</td>
									<td  class="rollenwahl-option-data">
										<select name="singlequery">
											<option value="0"<? if($this->user->rolle->singlequery == '0') { echo ' selected'; }	?>><? echo $strMapQuery0; ?></option>
											<option value="1"<? if($this->user->rolle->singlequery == '1') { echo ' selected'; }	?>><? echo $strMapQuery1; ?></option>
											<option value="2"<? if($this->user->rolle->singlequery == '2') { echo ' selected'; }	?>><? echo $strMapQuery2; ?></option>
										</select>
										&nbsp;
										<span data-tooltip="<? echo $strHintMapQuery; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strQuerymode; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input name="querymode" type="checkbox" value="1" <? if($this->user->rolle->querymode == '1'){echo 'checked="true"';} ?> >&nbsp;
										<span data-tooltip="<? echo $strHintQuerymode; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strNewDatasetOrder; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<select name="geom_edit_first">
											<option value="0"<? if($this->user->rolle->geom_edit_first == '0') { echo ' selected'; }	?>><? echo $strGeomSecond; ?></option>
											<option value="1"<? if($this->user->rolle->geom_edit_first == '1') { echo ' selected'; }	?>><? echo $strGeomFirst; ?></option>
										</select>&nbsp;
										<span data-tooltip="<? echo $strHintNewDatasetOrder; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strDatasetOperationsPosition; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<select name="dataset_operations_position">
											<option value="unten"<? if ($this->user->rolle->dataset_operations_position == 'unten') { echo ' selected'; }	?>><? echo $strDatasetOperationsPositionUnten; ?></option>
											<option value="oben"<? if ($this->user->rolle->dataset_operations_position == 'oben') { echo ' selected'; }	?>><? echo $strDatasetOperationsPositionOben; ?></option>
											<!-- Bis jetzt nur drüber und drunter weil nicht klar ist wie sich das auswirken soll wenn oben und unten angegeben wäre.
												option value="beide"<? if ($this->user->rolle->dataset_operations_position == 'beide') { echo ' selected'; }	?>><? echo $strDatasetOperationsPositionBeide; ?></option//-->
										</select>&nbsp;
										<span data-tooltip="<? echo $strHintDatasetOperationsPosition; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strAlwaysCreateNext; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input
											name="immer_weiter_erfassen"
											type="checkbox"
											value="1"<?
											echo ($this->user->rolle->immer_weiter_erfassen == '1' ? ' checked="true"' : ''); ?>
										>&nbsp;<span data-tooltip="<? echo $strHintAlwaysCreateNext; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strUploadOnlyFileMetadata; ?>:
									</td>
									<td class="rollenwahl-option-data">
										<input
											name="upload_only_file_metadata"
											type="checkbox"
											value="1"<?
											echo ($this->user->rolle->upload_only_file_metadata == '1' ? ' checked="true"' : ''); ?>
										>&nbsp;<span data-tooltip="<? echo $strHintUploadOnlyFileMetadata; ?>"></span>
									</td>
								</tr>
								<tr>
									<td class="rollenwahl-option-header">
										<? echo $strTooltipQuery; ?>:
									</td>
									<td  class="rollenwahl-option-data">
										<input name="tooltipquery" type="checkbox" value="1" <? if($this->user->rolle->tooltipquery == '1'){echo 'checked="true"';} ?> >&nbsp;
										<span data-tooltip="<? echo $strHintTooltipQuery; ?>"></span>
									</td>
								</tr>
								<tr <? if(!$this->Stelle->hist_timestamp)echo 'style="display:none"'; ?> >		
									<td class="rollenwahl-option-header">
										<? echo $this->histTimestamp; ?>:&nbsp;<a href="javascript:;" onclick="new CalendarJS().init('hist_timestamp', 'timestamp');"><img title="TT.MM.JJJJ hh:mm:ss" src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar_hist_timestamp" class="calendar" style="bottom:40px"></div></td>
									<td class="rollenwahl-option-data">
										<input onchange="if(this.value.length == 10)this.value = this.value + ' 06:00:00'" id="hist_timestamp" name="hist_timestamp" type="text" value="<? echo $this->user->rolle->hist_timestamp_de; ?>" size="16">&nbsp;
										<span data-tooltip="<? echo $strHinthist_timestamp; ?>"></span>
									</td>			
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div><?
	} ?>
		</figure>
	</div>
</div>
<table>
  <tr>
    <td></td>
    <td><input id="save_options_button" type="button" name="starten" onclick="start1();" value="<? echo $this->strEnter; ?>" style="margin-bottom: 10px"></td>
  </tr>
</table>
<input type="hidden" name="go" value="">