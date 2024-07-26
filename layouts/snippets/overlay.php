<html style="scroll-behavior: smooth">
	<head>
		<title><? echo $this->titel ?: TITLE; ?></title>
		<? include(SNIPPETS . 'gui_head.php'); ?>
	</head>
	<body onload="activate_overlay();" style="background: url(<? echo BG_IMAGE; ?>);">
		<script type="text/javascript">
			root = window.opener;
			if(typeof(root.stopwaiting) == "function"){
				root.stopwaiting();	// wenn man aus der Karte abgefragt hatte, Warteanimation beenden		
		<? if($this->formvars['window_type'] == 'overlay' AND $this->zoomed){ ?>		// wenn auf die Treffer gezoomt wurde, Karte neu laden
				root.startwaiting();
				if(root.document.getElementById('mapimage')){
					root.neuLaden();
				}
				else{
					root.location.href="index.php<? echo ($this->formvars['quicksearch_layer_id'] != '') ? '?quicksearch_layer_id='.$this->formvars['quicksearch_layer_id'] : ''; ?>";
				}
		<? } ?>
			}
			document.addEventListener("scroll", (event) => {enclosingForm.gle_scrollposition.value = document.scrollingElement.scrollTop});
		</script>
		<form name="GUI2" enctype="multipart/form-data" method="post" action="index.php" id="GUI2">
			<div id="message_box"></div>		<!-- muss innerhalb des form stehen -->
			<div id="overlayheader" class="gle_tabs px17 fett" style="display: none"></div>
			<div id="contentdiv" style="background: url(<? echo BG_IMAGE; ?>);width: 100%;position:relative;">
				<table border="0" height="100%" cellpadding="0" cellspacing="0" style="width: 100%">
					<tr>
						<td align="center" width="100%" valign="top" >
							<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>" onload="currentform = document.GUI2;">
				<?
				$this->currentform = 'document.GUI2';
				if (file_exists($this->main)) {
					include($this->main); # Pluginviews
				}
				else {
					include(LAYOUTPATH . "snippets/" . $this->main);		# normale snippets
				}
				?>
						</td>
					</tr>
				</table>
			</div>
			<div id="overlayfooter" style="<? if ($this->noOverlayFooter OR $this->new_entry OR $this->found == 'false' OR $this->formvars['printversion'] != '')echo 'display:none;'; ?>background: url(<? echo BG_IMAGE; ?>);border: 1px solid #cccccc;width: 100%; position:fixed; bottom: 0px">
				<table style="width:100%">
					<tr>
						<? if (is_array($selectable_limits)) { ?>
						<td style="width:40%" class="px13">&nbsp;<? echo $this->strLimit; ?>:&nbsp;										
							<select name="anzahl" id="anzahl" onchange="javascript:currentform.go.value = 'get_last_query'; overlay_submit(currentform, false);">
								<? foreach($selectable_limits as $limit){
								if($this->formvars['anzahl'] != '' AND $custom_limit != true AND !in_array($this->formvars['anzahl'], $selectable_limits) AND $this->formvars['anzahl'] < $limit){
									$custom_limit = true;	?>
									<option value="<? echo $this->formvars['anzahl'];?>" selected><? echo $this->formvars['anzahl']; ?></option>
								<? } ?>
								<option value="<? echo $limit; ?>" <? if($this->formvars['anzahl'] == $limit)echo 'selected'?>><? echo $limit; ?></option>
								<? } ?>
							</select>
						</td>
						<? } ?>
						<td align="center"><div id="savebutton" <? if($this->editable == '')echo 'style="display:none"'; ?>><input type="button" id="sachdatenanzeige_save_button" name="savebutton" value="<? echo $this->strSave; ?>" onclick="save();"></div></td>
						<td style="width:40%" align="right"><a href="javascript:druck();" class="px13"><? echo $this->printversion; ?></a>&nbsp;</td>
					</tr>
				</table>
			</div>
			<input type="hidden" name="window_type" value="">
			<input type="hidden" name="csrf_token" value="<? echo $_SESSION['csrf_token']; ?>">
		</form>
	</body>
</html>