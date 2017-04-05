<html>
	<head>
		<? include(SNIPPETS . 'gui_head.php'); ?>
	</head>
	<body onload="onload_functions();">
		<div id="waitingdiv" style="position: absolute;height: 100%; width: 100%; display:none; z-index: 1000000; text-align: center">
			<div style="position: absolute;  top: 50%; left: 50%; transform: translate(-50%,-50%);">
				<i class="fa fa-spinner fa-7x wobble-fix spinner"></i>
			</div>
		</div>
		<a name="oben"></a>
		<table align="center" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" valign="top">
					<form name="GUI" enctype="multipart/form-data" method="post" action="index.php" id="GUI">
						<table cellpadding=0 cellspacing=0>
							<tr> 
								<td colspan="2" style="border: 1px solid; border-color: #ffffff #cccccc #bbbbbb;"><?php
									$this->debug->write("Include <b>".LAYOUTPATH."snippets/".HEADER."</b> in gui.php",4);
									include(LAYOUTPATH."snippets/".HEADER); ?>
								</td>
							</tr>
							<tr> 
							<td width="1" valign="top" background="<?php echo GRAPHICSPATH."bg.gif"; ?>" style="border: 1px solid; border-color: #FFFFFF #CCCCCC #CCCCCC; border-bottom: 0px"> <?php
								$this->debug->write("Include <b>".LAYOUTPATH."snippets/menue_switch.php</b> in gui.php",4);
								include(SNIPPETS . "menue_switch.php"); ?>
							</td>
							<td align="center" width="100%" valign="top" background="<?php echo GRAPHICSPATH; ?>bg.gif" style="border-right: 1px solid; border-color: #FFFFFF #CCCCCC #CCCCCC;">
								<div style=" position: relative; overflow: hidden; ">
									<script type="text/javascript">
									currentform = document.GUI;
									</script><?php
									$this->debug->write("Include <b>".$this->main."</b> in gui.php",4);
									if(file_exists($this->main)){
										include($this->main);			# Pluginviews
									}
									else {
										include(LAYOUTPATH."snippets/".$this->main);		# normale snippets
									} ?>
                </div>
							</td>
						</tr>
						<tr> 
							<td colspan="2" style="border: 1px solid; border-color: #cccccc #cccccc #cccccc;"><?php
								$this->debug->write("Include <b>".LAYOUTPATH."snippets/".FOOTER."</b> in gui.php",4);    
								include(LAYOUTPATH."snippets/".FOOTER); ?></td>
							</tr>
						</table>
						<input type="hidden" name="overlayx" value="<? echo $this->user->rolle->overlayx; ?>">
						<input type="hidden" name="overlayy" value="<? echo $this->user->rolle->overlayy; ?>">
						<input type="hidden" name="browserwidth">
						<input type="hidden" name="browserheight">
						<input type="hidden" name="stopnavigation" value="0">
						<div id="message_box" class="message_box_hidden"></div>
					</form><?
					if($this->user->rolle->querymode == 1){
						include(LAYOUTPATH.'snippets/overlayframe.php');
					} ?>
				</td>
			</tr>
		</table>
	</body>
</html>