<HTML><HEAD><TITLE><? echo TITLE; ?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<? 
include(WWWROOT.APPLVERSION.'funktionen/gui_functions.php');
?>
<link rel="shortcut icon" href="graphics/wappen/favicon.ico">
<link rel="stylesheet" href="<?php echo 'layouts/'.$this->style; ?>">
<? if(defined('CUSTOM_STYLE') AND CUSTOM_STYLE != ''){ ?>
<link rel="stylesheet" href="<?php echo 'layouts/custom/'.CUSTOM_STYLE; ?>">
<script src="<?php echo JQUERY_PATH; ?>/jquery-1.12.0.min.js"></script>
<? } ?>
<? include(WWWROOT.APPLVERSION.'funktionen/msgboxes.php'); ?>
<link rel="stylesheet" href="<?php echo FONTAWESOME_PATH; ?>/css/font-awesome.min.css" type="text/css">
</HEAD>
<BODY onload="onload_functions();">
	<div id="sperrdiv" style="position: absolute;height: 100%;z-index: 1000000;background:rgba(200,200,200,0.3);"></div>
	<div id="sperrspinner" style="position: absolute;height: 100%; width: 100%; z-index: 1000000; display: none; background:rgba(200,200,200,0.3); text-align: center">
		<div style="margin: 300px">
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
			ist in Arbeit ...
		</div>
	</div>
	<a name="oben"></a>
  <table width="900" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top">
      <form name="GUI" enctype="multipart/form-data" method="post" action="index.php" id="GUI">
        <table cellpadding=0 cellspacing=0>
					<tr> 
						<td colspan="2" style="border: 1px solid; border-color: #ffffff #cccccc #bbbbbb;"><?php
						$this->debug->write("Include <b>".LAYOUTPATH."snippets/".HEADER."</b> in gui.php",4);    
						include(LAYOUTPATH."snippets/".HEADER);
				 ?></td>
          </tr>
          <tr> 
						<td width="1%" valign="top" background="<?php echo GRAPHICSPATH."bg.gif"; ?>" style="border: 1px solid; border-color: #FFFFFF #CCCCCC #CCCCCC;"> 
							<?php
								$this->debug->write("Include <b>".LAYOUTPATH."snippets/menue_switch.php</b> in gui.php",4);    
								include(LAYOUTPATH."snippets/menue_switch.php"); ?>
						</td>
            <td align="center" valign="top" background="<?php echo GRAPHICSPATH; ?>bg.gif" style="border-right: 1px solid; border-color: #FFFFFF #CCCCCC #CCCCCC;">
							<div style=" position: relative; overflow: hidden; ">
							<?php
              $this->debug->write("Include <b>".$this->main."</b> in gui.php",4);
				      if(file_exists($this->main)){
				      	include($this->main);			# Pluginviews
				      }
				      else{ 	    
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
				<div id="message_box" class="message_box_hidden"></div>
        </form>
				<script type="text/javascript">
					currentform = document.GUI;
				</script>
<? if($this->user->rolle->querymode == 1){
		include(LAYOUTPATH.'snippets/overlayframe.php');
	 } ?>
      </td>
    </tr>
</table>
</BODY></HTML>
